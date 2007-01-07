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


// input alla pagina
$mode 		= sanitize_user_input($_REQUEST['task'],'plain_text',Array());
$sezione 	= sanitize_user_input($_REQUEST['section'],'plain_text',Array());
$data 		= sanitize_user_input($_REQUEST['data'],'plain_text',Array());
$password 	= sanitize_user_input($_REQUEST['password'],'plain_text',Array());

// titolo relativo alla sezione in esame
switch ($sezione)
{
case '':
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
}

if ($password != $password_ok)
{
	echo "<a href=\"articoli.php?section=$sezione\">Torna indietro</a><br><br>\n";
	die("La password inserita non &egrave; corretta!<br>\n");
}


// individua cartella relativa alla sezione indicata
$art_file_data = get_articles_path($sezione);
$path_articles 	= $art_file_data["path_articles"];	// cartella contenente gli articoli
$article_online_file = $art_file_data["online_file"];	// file contenente l'elenco degli articoli online


switch ($mode)
{

case 'set_online_articles':
	$article_list = split('::',$data); // elenco dei titoli da pubblicare
	
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
	echo("Cancellazione dell'articolo con ID ".$data.":<br>\n");
	
	$art_data = load_article($data,$sezione); // carica l'articolo
	if (!empty($art_data)) // se l'articolo esiste...
	{
		echo "<table class=\"frame_delimiter\"><tbody>";
		show_article($art_data);	// visualizza l'articolo
		echo "</tbody></table>";
	}
	
	// leggi l'elenco degli articoli gia' online
	$art_list = get_online_articles($article_online_file); // carica l'elenco degli articoli da pubblicare
	
	if (in_array($data,$art_list))
	{
		echo "<a href=\"articoli.php?section=$sezione\">Torna indietro</a><br><br>\n";
		die("L'articolo e' ancora online, non puo' essere cancellato! Provvedi prima a metterlo offline.");
	}
	
	// a questo punto sono sicuro che l'id dell'articolo da cancellare non e' nell'elenco di quelli online
	
	if (delete_article($data,$sezione)) // cancello fisicamente il file
	{
		echo "L'articolo con id $data e' stato cancellato!<br>\n";
	}
	else
	{
		echo "L'articolo con id $data e' gia' stato cancellato!<br>\n";
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
	
	$zz = split("\n",$testo);
	
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
	
	echo "Articolo $id_articolo modificato.<br>\n";
	break;
default:
	echo "<a href=\"articoli.php?section=$sezione\">Torna indietro</a><br><br>\n";
	die("mode: \"".$mode."\", data: \"".$data."\"\n");
}


log_action($articles_dir,"Action: <$mode>, data: <$data>, ".date("l dS of F Y h:i:s A").",".$login['username']);

?>

<hr>
<a href="articoli.php?section=<?php echo $sezione; ?>">Torna indietro</a>

</body>
</html>
 
