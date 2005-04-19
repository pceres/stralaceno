#!/usr/local/bin/php 
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 TRANSITIONAL//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
  <title>Archivio Stralaceno</title>
  <meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1">
  <meta name="GENERATOR" content="Quanta Plus">
  <style type="text/css">@import "/work/stralaceno2/css/stralaceno.css";</style>
</head>
<body>
 

<?php

require_once('../libreria.php');

$uploaddir = substr($filename_tempi,0,strlen($filename_tempi)-16);
$password_ok = "stralacenoadmin"; 

print "<pre>";

$old_name = $_FILES['userfile']['name'];
$new_name = $_REQUEST['filename'];
$password = $_REQUEST['password'];

$ok = FALSE;
if ($password_ok == $password) 
{
	$ok = TRUE;
}

if ($ok == TRUE) 
{
	if (move_uploaded_file($_FILES['userfile']['tmp_name'], $uploaddir . $new_name)) 
	{ 
		$file = fopen($uploaddir . 'something_changed.txt', "a");
		fputs($file, $uploaddir . $new_name . ", " . date("l dS of F Y h:i:s A") . " \n");
		fclose($file);

		print "Il file $new_name &egrave; stato inviato con successo.\n"; 
	} 
	else
	{
		print "Possibile attacco tramite file upload! Alcune informazioni:\n"; 
		print_r($_FILES);
		
		$errno = $_FILES['userfile']['error'];
		switch ($errno) 
		{
			case UPLOAD_ERR_INI_SIZE :
			case UPLOAD_ERR_FORM_SIZE :
				print "File troppo grande!\n"; 
				break;
	 		case UPLOAD_ERR_PARTIAL :
				print "Upload eseguito parzialmente!\n"; 
				break;
			case UPLOAD_ERR_NO_FILE :
				print "Nessun file &egrave; stato inviato!\n"; 
				break;
			default:
				echo "Unknown error type: [$errno]<br>\n";
				break;
		}
	 }
}
else 
{
	echo "Password errata!";
}

# logga il contatto
$counter = count_page("admin_upload",array("COUNT"=>1,"LOG"=>1),$filedir_counter); # abilita il contatore, senza visualizzare le cifre, e fai il log
?>

</body>
</html>
