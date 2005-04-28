#!/usr/local/bin/php
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 TRANSITIONAL//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
  <title>Gestione articoli in prima pagina</title>
  <meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1">
  <meta name="GENERATOR" content="Programmers Notepad">
  <style type="text/css">@import "/work/stralaceno2/css/stralaceno.css";</style>
</head>
<body>

<?php

require_once('../libreria.php');

$mode = $_REQUEST['task'];
$data = $_REQUEST['data'];

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
case 'delete':
	echo "delete";
	break;
default:
	die("Compito non specificato!");
}


log_action($articles_dir,"Action: $mode, data<$data>, ".date("l dS of F Y h:i:s A"));

?>

<hr>
<a href="articoli.php">Torna indietro</a>

</body>
</html>
 
