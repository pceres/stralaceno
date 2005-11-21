<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 TRANSITIONAL//EN" "http://www.w3.org/TR/html4/loose.dtd">
<?php

require_once('libreria.php');

# dichiara variabili
extract(indici());

?>
<html>
<head>
  <title><?php echo $web_title ?> - Scheda personale</title>
  <meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1">
  <meta name="GENERATOR" content="Quanta Plus">
  <style type="text/css">@import "<?php echo $filename_css ?>";</style>  
</head>
<body>
  
<div align="center"><h2>Dati personali dei partecipanti alla <?php echo $race_name ?></h2></div>
<hr>

<?php

$atleti = load_data($filename_atleti,$num_colonne_atleti);


$id = $_REQUEST['id'];

$atleta = $atleti[$id];
echo "Hai chiesto informazioni su ".$atleta[$indice2_nome].":<br><br>";

echo "Id  : $atleta[$indice2_id] <br>\n";
echo "Nome: $atleta[$indice2_nome] <br>\n";
echo "Sesso: $atleta[$indice2_sesso] <br>\n";
echo "Titolo: $atleta[$indice2_titolo] <br>\n";
echo "Data di nascita: $atleta[$indice2_data_nascita] <br>\n";

$link = trim($atleta[$indice2_link]);
if ($link != "-") {
	if ($link == 'ok') 
	{  
		$link = "personal/$id.htm"; # se non e' specificato un link particolare, usa quello di default
	}
	echo "Sito personale: <a href = \"$link\">$link</a><br>\n";
	}

echo $homepage_link;

?>

</body>
</html>

