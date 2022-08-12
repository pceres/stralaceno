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
$password = $_REQUEST['password'];
$author = $_REQUEST['author'];
$title = $_REQUEST['title'];
$sezione = $_REQUEST['section'];
$id_articolo = $_REQUEST['id_articolo'];

// individua cartella relativa alla sezione indicata
$art_file_data = get_articles_path($sezione);
$path_articles 	= $art_file_data["path_articles"];	// cartella contenente gli articoli
$article_online_file = $art_file_data["online_file"];	// file contenente l'elenco degli articoli online

// scelta password
switch ($sezione)
{
case '':
case 'homepage':
	$password_ok = $password_articoli;
	break;
case 'ciclismo':
	$password_ok = 'f055d8b5317237d7e3e50b3c3c38667c'; // "Bartali"
	break;
case 'FC_caposele':
	$password_ok = 'd5aa82c231314da451812262871076bf'; // "palumenta"
	break;
default:
	die("La sezione $sezione non e' ancora gestita! Contattare l'amministratore.");
}

if ($password_ok == $password) 
{
	$ok = TRUE;
}
else
{
	$ok = FALSE;
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
		
		$errno = $_FILES['userfile']['error'];
		switch ($errno) 
		{
	  		case UPLOAD_ERR_INI_SIZE :
		  	case UPLOAD_ERR_FORM_SIZE :
				die("File troppo grande!\n"); 
				break;
			case UPLOAD_ERR_PARTIAL :
				die("Upload eseguito parzialmente!\n"); 
				break;
			case UPLOAD_ERR_NO_FILE :
				die("Nessun file &egrave; stato inviato!\n"); 
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

# logga il contatto
$counter = count_page("admin_upload_articoli",array("COUNT"=>1,"LOG"=>1),$filedir_counter); # abilita il contatore, senza visualizzare le cifre, e fai il log
?>

<hr>
<a href="articoli.php?section=<?php echo $sezione; ?>">Torna indietro</a>

</body>
</html>
