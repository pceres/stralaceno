#!/usr/local/bin/php 
<!DOCTYPE public "-//w3c//dtd html 4.01 transitional//en" 
		"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
  <title>Amministrazione</title>
  <meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1">
  <meta name="GENERATOR" content="Quanta Plus">
</head>

<body>

<hr>
<b>Informazioni utili:</b><br>
<?php

echo "document root remota: ".$_SERVER['DOCUMENT_ROOT']."<br>\n";
echo "percorso completo: ".$_SERVER['SCRIPT_FILENAME']."<br>\n";
echo "referrer: ".$HTTP_REFERER;
echo "<hr>";

phpinfo();

?>

</body>
</html>
