#!/usr/local/bin/php 
<!DOCTYPE public "-//w3c//dtd html 4.01 transitional//en" 
		"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
  <title>Archivio Stralaceno</title>
  <meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1">
  <meta name="GENERATOR" content="Quanta Plus">

</head>
<body>
 

<?php

$uploaddir = '../dati/';
$password_ok = "stralacenoadmin"; 

print "<pre>";

$old_name = $_FILES['userfile']['name'];
$new_name = $_REQUEST['filename'];
$password = $_REQUEST['password'];

$ok = FALSE;
if ($password_ok == $password) {
	$ok = TRUE;
	}

if ($ok == TRUE) {
	if (move_uploaded_file($_FILES['userfile']['tmp_name'], $uploaddir . $new_name)) { 
		print "Il file $new_name è stato inviato con successo.\n"; 

		$file = fopen($uploaddir . 'something_changed.txt', "a");
		fputs($file, $uploaddir . $new_name . ", " . date("l dS of F Y h:i:s A") . ' \n');
		fclose($file);

		#print_r($_FILES);
	} else {
		print "Possibile attacco tramite file upload! Alcune informazioni:\n"; 
		print_r($_FILES);
		
	  $errno = $_FILES['userfile']['error'];
	  switch ($errno) {
	  case UPLOAD_ERR_INI_SIZE :
	  case UPLOAD_ERR_FORM_SIZE :
		print "File troppo grande!\n"; 
		break;
	  case UPLOAD_ERR_PARTIAL :
		print "Upload eseguito parzialmente!\n"; 
		break;
	  case UPLOAD_ERR_NO_FILE :
		print "Nessun file e' stato inviato!\n"; 
		break;
		default:
		echo "Unkown error type: [$errno]<br>\n";
		break;
	  }
	 }
	}
else {
	echo "Password errata!";
	}


?>

</body>
</html>
