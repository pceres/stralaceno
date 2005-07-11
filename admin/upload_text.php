#!/usr/local/bin/php 
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 TRANSITIONAL//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<?php

require_once('../libreria.php');

# dichiara variabili
extract(indici());
?>
<head>
  <title>Salvataggio testo</title>
  <meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1">
  <meta name="GENERATOR" content="Quanta Plus">
  <style type="text/css">@import "<?php echo $css_site_path ?>/stralaceno.css";</style>
</head>
<body>
 

<?php

$password_ok = "stralacenoadmin"; 


$ks1 = array("\'",'\"',"\\\\","à"       ,"è"       ,"é"       ,"ì"       ,"ò"       ,"ù"       );
$ks2 = array("'" ,"\"","\\"  ,"&agrave;","&egrave;","&eacute;","&igrave;","&ograve;","&ugrave;");
$testo = str_replace($ks1,$ks2,$_REQUEST['testo']);

$new_name = $_REQUEST['filename']; // path assoluto nel filename del server
$password = $_REQUEST['password'];

$ok = FALSE;
if ($password_ok == $password) 
{
	$ok = TRUE;
}

if ($ok == TRUE) 
{
	$handle = fopen($new_name, "w");
	for ($i = 0; $i < count($testo); $i++)
	{
		fwrite($handle, $testo);	
	}
	fclose($file);
	
	print "<pre>Operazione eseguita correttamente.</pre>";
}
else 
{
	die("Password errata!");
}

# logga il contatto
$counter = count_page("admin_upload_text",array("COUNT"=>1,"LOG"=>1),$filedir_counter); # abilita il contatore, senza visualizzare le cifre, e fai il log

$simple_name = substr($new_name,strrpos($new_name,'/')+1); // nome del file senza il path
log_action($config_dir,"$simple_name:\r\n<\r\n$testo\r\n>\r\n, ".date("l dS of F Y h:i:s A")."\r\n\r\n");

?>

<hr>
<a href="index.php">Torna indietro</a>

</body>
</html>
