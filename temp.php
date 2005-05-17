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

if (1)
{
// impostazioni su altervista
$_SERVER['DOCUMENT_ROOT'] =	"/var/www/html";
$_SERVER['SCRIPT_FILENAME'] = "/membri/pceres/stralaceno/temp.php";
$_SERVER['SCRIPT_NAME']= "/stralaceno/temp.php";
$_SERVER['PHP_SELF']= "/stralaceno/temp.php";
$_SERVER['REQUEST_URI']= "/stralaceno/temp.php";
}

include 'libreria.php';




# visualizza header http
print_r($_SERVER);

$start = strpos($_SERVER['SCRIPT_FILENAME'],$_SERVER['DOCUMENT_ROOT']);

if (strlen($start)==0)
{
	echo "<br>non coincide! ($start)<br>";
}
else
{
	echo "<br>coincide! ($start)<br>";
}

/*
// path assoluto da usare per l'html e le immagini
if (substr($_SERVER['SCRIPT_FILENAME'],0,strlen($_SERVER['DOCUMENT_ROOT'])) === $_SERVER['DOCUMENT_ROOT'])
{
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
}
else
{
	$site_abs_path = $script_abs_path;	
	echo "Directory esterna a Document Root!: provo con lo stesso script_abs_path: $site_abs_path";
}
*/
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

