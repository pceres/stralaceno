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

#phpinfo();


#$counter = action_counter('counter.txt');
#echo "#$counter";


$filename = 'ftp://pceres:avellino@pceres.altervista.org/stralaceno/dati/counter.txt';
$fp = fopen($filename,'r');

$count = 0;
while (!feof($fp) & ($count++ < 20)) {
	$line = fgets($fp,1024);
        print($line);
	}
fclose($fp);

echo "Ho finito.";
?>


</body>
</html>

