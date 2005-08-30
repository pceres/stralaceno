#!/usr/local/bin/php 
<!DOCTYPE public "-//w3c//dtd html 4.01 transitional//en" 
		"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
  <title><?php echo $web_title ?> - Scheda personale</title>
  <meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1">
  <meta name="GENERATOR" content="Quanta Plus">
</head>
<body>
  
<div align="center"><h2>Dati personali dei partecipanti alla <?php echo $race_name ?></h2></div>
<hr>

<?php

include 'libreria.php';

$atleti = load_data($filename_atleti,$num_colonne_atleti);


$id = $_REQUEST['id'];

$atleta = $atleti[$id];
echo "Hai chiesto informazioni su ".$atleta[$indice2_nome].":<br><br>";

#print_r($atleta);
echo "Id  : $atleta[$indice2_id] <br>\n";
echo "Nome: $atleta[$indice2_nome] <br>\n";
echo "Sesso: $atleta[$indice2_sesso] <br>\n";
echo "Titolo: $atleta[$indice2_titolo] <br>\n";
echo "Data di nascita: $atleta[$indice2_data_nascita] <br>\n";

$link = trim($atleta[$indice2_link]);
if ($link != "-") {
	if ($link == 'ok') {  # se non e' specificato un link particolare, usa quello di default
		$link = "personal/$id.htm";
		}
	echo "<a href = \"$link\">Altre informazioni<a><br>\n";
	}

?>


</body>
</html>

