<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 TRANSITIONAL//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<?php

require_once('../libreria.php');

# dichiara variabili
extract(indici());
?>
<head>
  <title>Gestione articoli in prima pagina</title>
  <meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1">
  <meta name="GENERATOR" content="Programmers Notepad">
  <style type="text/css">@import "<?php echo $filename_css ?>";</style>
</head>
<body>

<?php

$mode = $_REQUEST['task'];
$data = $_REQUEST['data'];
$password = $_REQUEST['password'];

$password_ok = $password_articoli;

if ($password != $password_ok)
{
	echo "<a href=\"articoli.php\">Torna indietro</a><br><br>\n";
	die("La password inserita non &egrave; corretta!<br>\n");
}

switch ($mode)
{

case 'set_online_articles':
	$article_list = split('::',$data); // elenco dei titoli da pubblicare
	
	$published_list = publish_online_articles($article_list);
	
	echo "Fatto!<br>\n";
	echo "<br>\n";
	echo "Gli articoli online sono:<br>\n";
	echo "<ul>\n";
	for ($i = 0; $i < count($published_list); $i++) 
	{
		$art_data = load_article($published_list[$i]);
		
		echo "<li>id ".$published_list[$i].") ". $art_data['titolo'] ."</li>\n";
	} 
	echo "</ul><br>\n";
	
	break;
	
case 'cancel':
	echo("Cancellazione dell'articolo con ID ".$data.":<br>\n");
	
	$art_data = load_article($data); // carica l'articolo
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
		echo "<a href=\"articoli.php\">Torna indietro</a><br><br>\n";
		die("L'articolo e' ancora online, non puo' essere cancellato! Provvedi prima a metterlo offline.");
	}
	
	// a questo punto sono sicuro che l'id dell'articolo da cancellare non e' nell'elenco di quelli online
	
	if (delete_article($data)) // cancello fisicamente il file
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
	
	$art_data = load_article($data); // carica l'articolo
	
	if (!empty($art_data)) // se l'articolo esiste...
	{
		echo "<table class=\"frame_delimiter\"><tbody>";
		show_article($art_data);	// visualizza l'articolo
		echo "</tbody></table>";
	}

	?>

	<br>
	
	<form name="form_edit_article" action="manage_articles.php" method="post">
	Titolo: <input type="edit" name="titolo" value="<?php echo htmlentities($art_data['titolo'],ENT_QUOTES); ?>">

	<?php
	echo "<textarea name=\"testo\" rows=15 cols=120>";
	for ($i = 0; $i < count($art_data['testo']); $i++)
	{
		echo trim(htmlentities($art_data['testo'][$i],ENT_QUOTES),"\r\n")."\n";
	}
	echo "</textarea>";
	?>
	Autore: <input type="edit" name="autore" value="<?php echo htmlentities($art_data['autore'],ENT_QUOTES); ?>"><br>

	<input value="Applica modifiche" onClick="form_edit_article.task.value='edited'" type="submit">
	<input name="password" value="<?php echo $password; ?>" type="hidden">
	<input name="task" type="hidden">
	<input name="data" type="hidden" value="<?php echo $data; ?>">
	
	</form>
	
	<?php
	break;

case 'edited':
	$id_articolo = $_REQUEST['data']; // articolo da modificare
	$ks1 = array("\'",'\"',"\\\\");
	$ks2 = array("'","\"","\\");
	$art_data['titolo'] = str_replace($ks1,$ks2,$_REQUEST['titolo']);
	$art_data['autore'] = str_replace($ks1,$ks2,$_REQUEST['autore']);
	$testo = str_replace($ks1,$ks2,$_REQUEST['testo']);
	
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
	
	save_article($id_articolo,$art_data['autore'],$art_data['titolo'],$art_data['testo'],$articles_dir);
	
	echo "Articolo $id_articolo modificato.<br>\n";
	break;
default:
	echo "<a href=\"articoli.php\">Torna indietro</a><br><br>\n";
	die("mode: \"".$mode."\", data: \"".$data."\"\n");
}


log_action($articles_dir,"Action: <$mode>, data: <$data>, ".date("l dS of F Y h:i:s A"));

?>

<hr>
<a href="articoli.php">Torna indietro</a>

</body>
</html>
 
