#!/usr/local/bin/php 
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 TRANSITIONAL//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<?php

require_once('../libreria.php');

# dichiara variabili
extract(indici());
?>
<head>
  <title>Archivio <?php echo $race_name ?></title>
  <meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1">
  <meta name="GENERATOR" content="Quanta Plus">
  <style type="text/css">@import "<?php echo $filename_css ?>";</style>
</head>
<body>
 

<?php

$uploaddir = substr($filename_tempi,0,strlen($filename_tempi)-16);
$password_ok = $password_upload_file; 

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
	// verifica che il file non sia protetto in scrittura
	$perm = substr(sprintf('%o', fileperms($uploaddir . $new_name)), -4); // attributi in forma ottale
	$write_enable = '0200'; // bit di attributi in forma ottaleche consentono la scrittura
	if (file_exists($uploaddir . $new_name) && ((octdec($perm) & octdec($write_enable)) == 0) )
	{
		die("Il file e' protetto in scrittura (".$perm.")! Contatta il webmaster.");
	}
	
	if (move_uploaded_file($_FILES['userfile']['tmp_name'], $uploaddir . $new_name)) 
	{ 
		log_action($uploaddir,$uploaddir . $new_name . ", " . date("l dS of F Y h:i:s A"));
		
		print "<pre>Il file $new_name &egrave; stato inviato con successo.</pre>\n"; 
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
				die("File troppo grande!\n"); 
				break;
	 		case UPLOAD_ERR_PARTIAL :
				die("Upload eseguito parzialmente!\n"); 
				break;
			case UPLOAD_ERR_NO_FILE :
				die("Nessun file &egrave; stato inviato!\n"); 
				break;
			default:
				die("Unknown error type: [$errno]<br>\n");
				break;
		}
	 }
}
else 
{
	die("Password errata!");
}

# logga il contatto
$counter = count_page("admin_upload",array("COUNT"=>1,"LOG"=>1),$filedir_counter); # abilita il contatore, senza visualizzare le cifre, e fai il log
?>

<hr>
<a href="index.php">Torna indietro</a>

</body>
</html>
