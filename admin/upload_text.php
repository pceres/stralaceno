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
  <style type="text/css">@import "<?php echo $filename_css ?>";</style>
</head>
<body>
 

<?php

$password_ok = $password_config; 


$ks1 = array("\'",'\"',"\\\\","à"       ,"è"       ,"é"       ,"ì"       ,"ò"       ,"ù"       ,"°"    );
$ks2 = array("'" ,"\"","\\"  ,"&agrave;","&egrave;","&eacute;","&igrave;","&ograve;","&ugrave;","&deg;");
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

	// verifica che il file non sia protetto in scrittura
	$perm = substr(sprintf('%o', fileperms($new_name)), -4); // attributi in forma ottale
	$write_enable = '0002'; // bit di attributi in forma ottaleche consentono la scrittura
	if (file_exists($new_name) && ((octdec($perm) & octdec($write_enable)) == 0) )
	{
		die("Il file e' protetto in scrittura (".$perm.")! Contatta il webmaster.");
	}	
	
	// leggi vecchio file
	if (file_exists($new_name))
	{
		$testo_old = file($new_name);
		$confronta = 1;
	}
	else
	{
		$confronta = 0;
	}
	
	// scrivi i dati inviati su file
	$handle = fopen($new_name, "w");
	fwrite($handle, $testo);	
	fclose($handle);
	
	// se il file non esisteva, logga solo le modifiche
	if ($confronta == 1)
	{
		// rileggi il file
		$testo = file($new_name);
		
		// trova linee cancellate
		$del = array_diff($testo_old,$testo);
		if (!empty($del))
		{
			$out1 = str_replace("\n","\r\n",print_r($del,TRUE));
		}
		
		// trova linee aggiunte
		$add = array_diff($testo,$testo_old);
		if (!empty($add))
		{
			$out2 = str_replace("\n","\r\n",print_r($add,TRUE));
		}
		
		if (empty($del) && empty($add))
		{
			$testo="<Nessuna modifica>";
		}
		else
		{
			$testo="\r\n<\r\n";
			if (!empty($del))
				$testo .= "eliminato:\r\n".$out1."\r\n";
			if (!empty($add))
				$testo .= "aggiunto:\r\n".$out2."\r\n";
			$testo .= ">\r\n";
		}
	}

	print "<pre>Operazione eseguita correttamente.</pre>";
}
else 
{
	die("Password errata!");
}

# logga il contatto
$counter = count_page("admin_upload_text",array("COUNT"=>1,"LOG"=>1),$filedir_counter); # abilita il contatore, senza visualizzare le cifre, e fai il log

$simple_name = substr($new_name,strrpos($new_name,'/')+1); // nome del file senza il path
log_action($config_dir,"$simple_name:$testo, ".date("l dS of F Y h:i:s A")."\r\n\r\n");

?>

<hr>
<a href="index.php">Torna indietro</a>

</body>
</html>
