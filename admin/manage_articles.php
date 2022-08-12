<?php

require_once('../libreria.php');

# dichiara variabili
extract(indici());

/*
questa libreria esamina i cookies o i parametri http (eventualmente) inviati, e genera l'array $login con i camp
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


// input alla pagina
$mode 		= sanitize_user_input($_REQUEST['task'],'plain_text',Array());
$sezione 	= sanitize_user_input($_REQUEST['section'],'plain_text',Array());
$data 		= sanitize_user_input($_REQUEST['data'],'plain_text',Array());
// $password 	= sanitize_user_input($_REQUEST['password'],'plain_text',Array());

// titolo relativo alla sezione in esame
switch ($sezione)
{
case '':
	$sezione = 'homepage'; // se non specificato, la sezione di default e' 'homepage'
case 'homepage':
	$tag_sezione = "in prima pagina";
	break;
default:
	$tag_sezione = "nella sezione &quot;$sezione&quot;";
	break;
}

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 TRANSITIONAL//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>

<head>
  <title>Gestione articoli <?php echo $tag_sezione; ?></title>
  <meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1">
  <meta name="GENERATOR" content="Kate">
  <style type="text/css">@import "<?php echo $filename_css ?>";</style>
</head>
<body>

<?php

// verifica che l'utente sia autorizzato per l'operazione richiesta
$res = check_auth('gestione_articoli',"$mode;$data;$sezione",$login['username'],$login['usergroups'],false);
if (!$res)
{
	die("Mi dispiace, non sei autorizzato!");
}

// individua cartella relativa alla sezione indicata
$art_file_data = get_articles_path($sezione);
$path_articles 	= $art_file_data["path_articles"];	// cartella contenente gli articoli
$article_online_file = $art_file_data["online_file"];	// file contenente l'elenco degli articoli online


switch ($mode)
{

case 'set_online_articles':
	$article_list = explode('::',$data); // elenco dei titoli da pubblicare
	
	$published_list = publish_online_articles($article_list,$sezione);
	
	echo "Fatto!<br>\n";
	echo "<br>\n";
	echo "Gli articoli online sono:<br>\n";
	echo "<ul>\n";
	for ($i = 0; $i < count($published_list); $i++) 
	{
		$art_data = load_article($published_list[$i],$sezione);
		
		echo "<li>id ".$published_list[$i].") ". $art_data['titolo'] ."</li>\n";
	} 
	echo "</ul><br>\n";
	
	break;
	
case 'cancel':
	$id_articolo = $data; // articolo da cancellare
	
	echo("Cancellazione dell'articolo con ID ".$id_articolo.":<br>\n");
	
	$art_data = load_article($id_articolo,$sezione); // carica l'articolo
	if (!empty($art_data)) // se l'articolo esiste...
	{
		echo "<table class=\"frame_delimiter\"><tbody>";
		show_article($art_data);	// visualizza l'articolo
		echo "</tbody></table>";
	}
	
	// leggi l'elenco degli articoli gia' online
	$art_list = get_online_articles($article_online_file); // carica l'elenco degli articoli da pubblicare
	
	if (in_array($id_articolo,$art_list))
	{
		echo "<a href=\"articoli.php?section=$sezione\">Torna indietro</a><br><br>\n";
		die("L'articolo e' ancora online, non puo' essere cancellato! Provvedi prima a metterlo offline.");
	}
	
	// a questo punto sono sicuro che l'id dell'articolo da cancellare non e' nell'elenco di quelli online
	if (delete_article($id_articolo,$sezione)) // cancello fisicamente il file
	{
		echo "L'articolo con id $id_articolo e' stato cancellato!<br>\n";
	}
	else
	{
		echo "L'articolo con id $id_articolo e' gia' stato cancellato!<br>\n";
	}

	break;
	
case 'edit':
	echo("Modifica dell'articolo con ID ".$data.":<br>\n");

	$art_data = load_article($data,$sezione); // carica l'articolo
	if (!empty($art_data)) // se l'articolo esiste...
	{
		echo "<table class=\"frame_delimiter\"><tbody>";
		show_article($art_data);	// visualizza l'articolo
		echo "</tbody></table>";
	}
	
	?>

	<br>
	
	<form name="form_edit_article" action="manage_articles.php" method="post">
	Titolo: <input type="edit" name="titolo" value="<?php echo htmlentities($art_data['titolo'],ENT_QUOTES); ?>"><br>

	<?php
	echo "<textarea name=\"testo\" rows=15 cols=120>";
	for ($i = 0; $i < count($art_data['testo']); $i++)
	{
		echo trim(htmlentities($art_data['testo'][$i],ENT_QUOTES),"\r\n")."\n";
	}
	echo "</textarea>";
	?><br>
	Autore: <input type="edit" name="autore" value="<?php echo htmlentities($art_data['autore'],ENT_QUOTES); ?>"><br>

	<input value="Applica modifiche" onClick="form_edit_article.task.value='edited'" type="submit">
	<input name="password" value="<?php echo $password; ?>" type="hidden">
	<input name="section" value="<?php echo $sezione; ?>" type="hidden">
	<input name="task" type="hidden">
	<input name="data" type="hidden" value="<?php echo $data; ?>">
	
	</form>
	
	<?php
	break;

case 'edited':
	$id_articolo = $data; // articolo da modificare

	$art_data['titolo'] 	= stripslashes($_REQUEST['titolo']);
	$art_data['autore'] 	= stripslashes($_REQUEST['autore']);
	$testo 			= stripslashes($_REQUEST['testo']);
	
	$zz = preg_split("~\n~",$testo);
	
	$art_data['testo'] = Array();
	for ($i = 0; $i<count($zz); $i++)
	{
		$line = trim($zz[$i],"\r\n");

		if ($i<(count($zz)-1))
		{
			$line .= "\r\n";
		}
		$art_data['testo'][$i] = $line;
		
	}
	
	save_article($id_articolo,$art_data['autore'],$art_data['titolo'],$art_data['testo'],$sezione);
	
	
	// prepara i dati per il feed RSS
	$azione 	= 'modificato';
	$content_time = strtotime(date('D, j M Y G:i:s'));
	$art_link 	= "index.php?page=$sezione&amp;art_id=$id_articolo;time=$content_time";
	$guid 		= "$sezione,$id_articolo,{$art_data['autore']}";
	
	$bulk= get_abstract($art_data['testo'],'...');
	
	$riassunto = '';
	foreach ($bulk as $id => $linea)
	{
		$linea = preg_replace(Array("~\n~","\r"),Array("",""),rtrim($linea));
		$riassunto .= $linea;
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
	
	log_new_content('articolo_'.$mode,$item);
	
	
	echo "Articolo $id_articolo modificato.<br>\n";
	break;
default:
	echo "<a href=\"articoli.php?section=$sezione\">Torna indietro</a><br><br>\n";
	die("mode: \"".$mode."\", data: \"".$data."\"\n");
}


log_action($articles_dir,"Action: <$mode>, section: <$sezione>, article id: <$data>, ".date("l dS of F Y h:i:s A").",".$login['username']);

?>

<hr>
<a href="articoli.php?section=<?php echo $sezione; ?>">Torna indietro</a>

</body>
</html>
 
