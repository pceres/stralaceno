<?php

require_once('../libreria.php');

# dichiara variabili
extract(indici());

/*
questa libreria esamina i cookies o i parametri http (eventualmente) inviati, e genera l'array $login con i campi
'username',	: login
'usergroups',	: lista dei gruppi di appartenenza (separati da virgola)
'status',		: stato del login: 'none','ok_form','ok_cookie','error_wrong_username','error_wrong_userpass','error_wrong_challenge','error_wrong_IP'
*/
require_once('../login.php');


// verifica che si stia arrivando a questa pagina da quella amministrativa principale
if (
    !isset($_SERVER['HTTP_REFERER']) // referer not set ...
    | (strlen(strpos(substr($_SERVER['HTTP_REFERER'] ,0,strrpos($_SERVER['HTTP_REFERER'] ,'/')+1),"://".$_SERVER['HTTP_HOST'].$script_abs_path."admin/").' ') == 1) // ...or (referer ~= link_from_admin_pages)
    | (!in_array($login['status'],array('ok_form','ok_cookie'))) // ...or login_was_not_successful
   )
{
	header("Location: ".$script_abs_path."index.php");
	exit();
}

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 TRANSITIONAL//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
  <title>Archivio <?php echo $race_name ?></title>
  <meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1">
  <meta name="GENERATOR" content="Quanta Plus">
  <style type="text/css">@import "<?php echo $filename_css ?>";</style>
</head>
<body>
 

<?php

$uploaddir = dirname($filename_tempi).'/';
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
	if (move_uploaded_file($_FILES['userfile']['tmp_name'], $uploaddir . $new_name)) 
	{ 
		log_action($uploaddir,$uploaddir . $new_name . ", " . date("l dS of F Y h:i:s A"));
		
		print "<pre>Il file $new_name &egrave; stato inviato con successo.</pre>\n"; 
	} 
	else
	{
		print "Possibile attacco tramite file upload!<br>Alcune informazioni:\n";
		print_r($_FILES);
        echo("<br>");
		
		$errno = $_FILES['userfile']['error'];
		switch ($errno) 
		{
            case UPLOAD_ERR_OK:
                echo("File copied to temporary file $tempfile, but error in moving it to the destination folder $uploaddir$new_name. Please check permissions (i.e. \"apache\" user has to have write access)!\n");
                break;
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
