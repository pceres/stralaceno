#!/usr/local/bin/php
<!DOCTYPE public "-//w3c//dtd html 4.01 transitional//en" 
		"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
  <title>a</title>
  <meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1">
  <meta name="GENERATOR" content="Quanta Plus">
</head>
<body>
  
<?php

include 'libreria.php';

# visualizza header http
print_r($_SERVER);

// path assoluto da usare per l'html e le immagini
echo "<br><br>";
$path = $_SERVER['SCRIPT_FILENAME'];
echo "$path<br>";
$start = strlen($_SERVER['DOCUMENT_ROOT'])+1;
echo $_SERVER['DOCUMENT_ROOT']." -> $start<br>";
$path = substr($path,$start);
echo "$path<br>";
$start = strpos($path,$root_prefix)+strlen($root_prefix)+1;
echo "$start<br>";
$site_abs_path = substr($path,0,$start);
echo "$site_abs_path<br><br>";
echo "<br><br>";


# prova a leggere da remoto un file tramite ftp
echo "<p>Provo a leggere counter.txt:<br>\n";
$filename = 'ftp://pceres:avellino@pceres.altervista.org/stralaceno/dati/counterfile.txt';
#$filename = 'dati/counter.txt';
$fp = fopen($filename,'r');

$count = 0;
while (!feof($fp) & ($count++ < 20)) {
	echo "linea $count:";
	$line = fgets($fp,1024);
    echo "$line<br>\n";
	}
fclose($fp);
echo "Ho finito.\n";

?>


</body>
</html>

