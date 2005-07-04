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

print_r($_SERVER);

/*
 locale:
SCRIPT_FILENAME: /var/www/htdocs/work/stralaceno2/temp.php
SCRIPT_NAME: /my_cgi-bin/stralaceno2/temp.php
DOCUMENT_ROOT: /var/www/htdocs
ROOT_PREFIX: stralaceno

root_prefix:stralaceno2
root_path:/var/www/htdocs/work/stralaceno2/
script_abs_path:/my_cgi-bin/stralaceno2/
site_abs_path:/work/stralaceno2/
*/


/*
$_SERVER['SCRIPT_FILENAME'] = '/data/members/free/tripod/uk/s/t/r/stralaceno/htdocs/stralaceno/filtro8.php';
$_SERVER['SCRIPT_NAME'] = 'filtro8.php';
$_SERVER['DOCUMENT_ROOT'] = '/data/members/free/tripod/uk/s/t/r/stralaceno/htdocs/';
$_SERVER['SCRIPT_URL'] = '/stralaceno/stralaceno/filtro8.php';
$_SERVER['HTTP_HOST'] = 'members.lycos.co.uk';
$_SERVER['SCRIPT_URI'] = 'http://members.lycos.co.uk/stralaceno/stralaceno/temp.php';
*/

/*
tripod.co.uk:
 SCRIPT_FILENAME: /data/members/free/tripod/uk/s/t/r/stralaceno/htdocs/stralaceno/filtro8.php
SCRIPT_NAME: filtro8.php
DOCUMENT_ROOT: /data/members/free/tripod/uk/s/t/r/stralaceno/htdocs/
ROOT_PREFIX: stralaceno

root_prefix:stralaceno
root_path:/data/members/free/tripod/uk/s/t/r/stralaceno/
script_abs_path:filtro8.php
site_abs_path:stralaceno/
*/


// determina la directory (l'ultimo livello) contenente il sito (deve iniziare con "stralaceno", ad es. "stralaceno2" e' ok)
$path = $_SERVER['SCRIPT_FILENAME'];
$root_prefix = "stralaceno";

echo "HTTP_HOST: ".$_SERVER['HTTP_HOST']."<br>";
echo "SCRIPT_FILENAME: ".$_SERVER['SCRIPT_FILENAME']."<br>";
echo "SCRIPT_NAME: ".$_SERVER['SCRIPT_NAME']."<br>";
echo "SCRIPT_URL: ".$_SERVER['SCRIPT_URL']."<br>";
echo "SCRIPT_URI: ".$_SERVER['SCRIPT_URI']."<br>";
echo "DOCUMENT_ROOT: ".$_SERVER['DOCUMENT_ROOT']."<br>";
echo "ROOT_PREFIX: ".$root_prefix."<br>";
echo "<br>";

// determina l'ultima occorrenza di root_prefix
$temp=strpos($path,$root_prefix);
if (strlen($temp."a")==1) // verifica che root_prefix sia presente nel path
{
	die("Errore: il path della radice del sito ($path) sul server non contiene '$root_prefix' ");
}
do {
	$path = substr($path,$temp);
	$temp=strpos($path,$root_prefix,1)."a";
} while (strlen($temp)>1);
// taglia la parte restante del path fino al simbolo '/'
$path = substr($path,strpos($path,$root_prefix));
$root_prefix = substr($path,0,strpos($path,"/"));
echo("root_prefix:$root_prefix<br>");

// determina il path assoluto nel filesystem del server (serve quando si accede direttamente ai file per leggere o scrivere)
$path = $_SERVER['SCRIPT_FILENAME'];
$end = 0;
do {
	$test = strpos($path,$root_prefix,$end);
	if ($test)
	{
		$end = $test+strlen($root_prefix)+1;
	}
} while ($test);
$root_path = substr($path,0,$end);
echo("root_path:$root_path<br>");

// path assoluto da usare per gli script php
$start = strpos($_SERVER['SCRIPT_NAME'],$root_prefix);
if (strlen($start)>0)
{
	$path = $_SERVER['SCRIPT_NAME'];
}
else
{
	$path = $_SERVER['SCRIPT_URL'];
}
$end = 0;
do {
	$test = strpos($path,$root_prefix,$end);
	if ($test)
	{
		$end = $test+strlen($root_prefix)+1;
	}
} while ($test);
$script_abs_path = substr($path,0,$end);
echo("script_abs_path:$script_abs_path<br>");

// path assoluto da usare per l'html e le immagini
if (array_key_exists('HTTP_HOST',$_SERVER) and array_key_exists('SCRIPT_URI',$_SERVER))
{
	$path = substr($_SERVER['SCRIPT_URI'],strpos($_SERVER['SCRIPT_URI'],$_SERVER['HTTP_HOST'])+strlen($_SERVER['HTTP_HOST']));
}
else
{
	if (substr($_SERVER['DOCUMENT_ROOT'],-1) == '/') // document_root non deve finire per '/'
	{
		$_SERVER['DOCUMENT_ROOT'] = substr($_SERVER['DOCUMENT_ROOT'],0,-1);
	}
	$start = strpos($_SERVER['SCRIPT_FILENAME'],$_SERVER['DOCUMENT_ROOT']);
	if (strlen($start)>0)
	{
		$path = $_SERVER['SCRIPT_FILENAME'];
		$start = strlen($_SERVER['DOCUMENT_ROOT']);
		$path = substr($path,$start);
	}
}	
$end = 0;
do {
	$test = strpos($path,$root_prefix,$end);
	if ($test)
	{
		$end = $test+strlen($root_prefix)+1;
	}
} while ($test);
$site_abs_path = substr($path,0,$end);

/*}
else
{
	echo "2";
	$site_abs_path = $script_abs_path;	// Directory esterna a Document Root!: provo con $site_abs_path = $script_abs_path
}
*/
echo("site_abs_path:$site_abs_path<br>");

die();
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

