<?php

require_once('../libreria.php');

# dichiara variabili
extract(indici());

/*
questa libreria esamina i cookies o i parametri http (eventualmente) inviati, e genera l'array $login con i campi
'username',	: login
'usergroups',	: lista dei gruppi di appartenenza (separati da virgola)
'status',		: stato del login: 'none','ok_form','ok_cookie','error_wrong_username','error_wrong_userpass','error_wrong_challenge','error_wrong_IP'
*/
require_once('../login.php');


// verifica che si stia arrivando a questa pagina da quella amministrativa principale
if ( !isset($_SERVER['HTTP_REFERER']) | ("http://".$_SERVER['HTTP_HOST'].$script_abs_path."admin/" != substr($_SERVER['HTTP_REFERER'],0,strrpos($_SERVER['HTTP_REFERER'],'/')+1) ) |
(!in_array($login['status'],array('ok_form','ok_cookie'))) )
{
	header("Location: ".$script_abs_path."index.php");
	exit();
}

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 TRANSITIONAL//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
  <title>Caricamento articoli sul sito</title>
  <meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1">
  <meta name="GENERATOR" content="Quanta Plus">
  <style type="text/css">@import "<?php echo $filename_css ?>";</style>
</head>
<body>
 

<?php

$old_name = $_FILES['userfile']['name'];
$new_name = $_REQUEST['filename'];
// $password = $_REQUEST['password'];
$author = $_REQUEST['author'];
$title = $_REQUEST['title'];
$sezione = $_REQUEST['section'];
$id_articolo = $_REQUEST['id_articolo'];

// individua cartella relativa alla sezione indicata
$art_file_data = get_articles_path($sezione);
$path_articles 	= $art_file_data["path_articles"];	// cartella contenente gli articoli
$article_online_file = $art_file_data["online_file"];	// file contenente l'elenco degli articoli online


// verifica che l'utente sia autorizzato per l'operazione richiesta
$res = check_auth('carica_articolo',"$sezione;$id_articolo",$login['username'],$login['usergroups'],false);
if (!$res)
{
	die("Mi dispiace, non sei autorizzato!");
}
else
{
	$ok = TRUE;
}



print "<pre>";

if (file_exists($path_articles . $new_name))
{
	echo "L'articolo $id_articolo &egrave; gi&agrave; stato pubblicato (".$path_articles.$new_name." gia' esiste!)";
	return;
}

if ($ok == TRUE)
{
	if (move_uploaded_file($_FILES['userfile']['tmp_name'], $path_articles . $new_name)) 
	{ 
		log_action($path_articles,$path_articles . $new_name . ", " . date("l dS of F Y h:i:s A").",".$login['username']);
		
		print "Il file $new_name &egrave; stato inviato con successo.\n"; 
	}
	else
	{
		print "Possibile attacco tramite file upload! Alcune informazioni:\n"; 
		print_r($_FILES);
        echo("<br>");
		
		$errno = $_FILES['userfile']['error'];
		switch ($errno) 
		{
            case UPLOAD_ERR_OK:
                echo("File copied to temporary file $tempfile, but error in moving it to the destination folder $path_articles$new_name. Please check permissions (i.e. \"apache\" user has to have write access)!\n");
                        break;
	  		case UPLOAD_ERR_INI_SIZE :
		  	case UPLOAD_ERR_FORM_SIZE :
				die("File troppo grande!\n"); 
				break;
			case UPLOAD_ERR_PARTIAL :
				die("Upload eseguito parzialmente!\n"); 
				break;
			case UPLOAD_ERR_NO_FILE :
				die("Nessun file &egrave; stato selezionato dal browser!\n"); 
				break;
			default:
				die("Unknown error type: [$errno]<br>\n");
				break;
	  	}
	 }
}
else
{
	die("Password errata!");
}

echo "<hr>\n";
echo "id articolo: $id_articolo\n";
echo "title: $title\n";
echo "autore: $author\n";

$bulk = file($path_articles . $new_name);
unlink($path_articles . $new_name); // cancella il file in maniera che l'articolo non venga ancora considerato pubblicato

// salva sul sito l'articolo (ma non e' ancora online)
$id_articolo = upload_article($author,$title,$bulk,$sezione);

// leggi l'elenco degli articoli gia' online
$art_list = get_online_articles($article_online_file); // carica l'elenco degli articoli da pubblicare

$articolo_subito_online = FALSE; // TRUE --> l'articolo caricato e' subito online; FALSE --> l'articolo deve essere messo online in seguito
if ($articolo_subito_online)
{
	// aggiungi il nuovo articolo in cima
	$art_list = array_merge(Array($id_articolo),$art_list);
	
	// salva il nuovo elenco degli articoli online
	publish_online_articles($art_list,$sezione);
}

// prepara i dati per il feed RSS
// error_reporting(2039); // otherwise "StripDoubleColon($HTTP_REFERER);" gives error. Show all errors but notices
$art_data = load_article($id_articolo,$sezione); // carica l'articolo

$azione 	= 'pubblicato';
$content_time 	= strtotime(date('D, j M Y G:i:s'));
$art_link 	= "index.php?page=$sezione&amp;art_id=$id_articolo;time=$content_time";
$guid 		= "$sezione,$id_articolo,{$art_data['autore']}";

$bulk= get_abstract($art_data['testo'],'...');

// estrai le righe che ostituiscono il corpo del testo
$riassunto = '';
$stato = 0;
foreach ($bulk as $id => $linea)
{
	$linea = rtrim($linea);
	
	switch ($stato)
	{
	case 0: // in attesa di Begin body
		if ($linea === "--- Begin body ---")
		{
			$stato = 1;
		}
		break;
	case 1: // in attesa di End body
		if ($linea === "--- End body ---")
		{
			$stato = 2;
		}
		else
		{
			$linea = str_replace(array("\n","\r","::"),array("<br>","",":"),$linea);
			$riassunto .= $linea;
		}
		break;
	case 2: // finito
		break;
	}
}

$titolo = $art_data['titolo'];
if (!empty($art_data['autore']))
{
	$titolo .= " ($azione da {$art_data['autore']})";
}

$item['title'] 		= $titolo;
$item['description'] 	= $riassunto;
$item['link'] 		= $art_link;
$item['guid'] 		= "$sezione,$id_articolo,{$art_data['autore']},".date('D, j M Y G:i:s');
$item['category'] 	= "Sezione $sezione";
$item['pubDate'] 	= gmdate('D, j M Y G:i:s +0000',$content_time);
$item['author'] 	= $art_data['autore'];
$item['username']	= $username;
$item['read_allowed']	= "";	// everyone allowed to see the feed

log_new_content('articolo_new',$item);

# logga il contatto
$counter = count_page("admin_upload_articoli",array("COUNT"=>1,"LOG"=>1),$filedir_counter); # abilita il contatore, senza visualizzare le cifre, e fai il log
?>

<hr>
<a href="articoli.php?section=<?php echo $sezione; ?>">Torna indietro</a>

</body>
</html>
