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


// verifica che si stia arrivando a questa pagina da quella amministrativa principale (o da album.php, in modalita' admin)
$referer = $_SERVER['HTTP_REFERER'];
if ( !isset($_SERVER['HTTP_REFERER']) | 
((strlen(strpos($referer,"http://".$_SERVER['HTTP_HOST'].$script_abs_path."admin/").' ') == 1) & (strlen(strpos($referer,"http://".$_SERVER['HTTP_HOST'].$script_abs_path."album.php").' ') == 1)) |
(!in_array($login['status'],array('ok_form','ok_cookie'))) )
{
	header("Location: ".$script_abs_path."index.php");
	exit();
}

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 TRANSITIONAL//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
  <title>Caricamento foto sul sito</title>
  <meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1">
  <meta name="GENERATOR" content="Quanta Plus">
  <style type="text/css">@import "<?php echo $filename_css ?>";</style>
</head>
<body>
 

<?php

$uploaddir = $articles_dir;

$password_ok = $password_album; 

$MAX_FILE_SIZE = $_REQUEST['MAX_FILE_SIZE'];
$nome_album = $_REQUEST['nome_album'];
$userfoto01 = $_REQUEST['userfoto01'];
$userthumb01 = $_REQUEST['userthumb01'];
$password = $_REQUEST['password'];


$MAX_THUMB_FILE_SIZE = 10000;


$ok = FALSE;
if ($password_ok == $password)
{
	$ok = TRUE;
}


print "<pre>";

if (!file_exists($album_dir.$nome_album))
{
	echo "L'album $nome_album non esiste!";
	break;
}

if ($ok == TRUE) // la password e' ok, procedi
{
	foreach ($_FILES as $_FILE)
	{
		$filename = $_FILE['name'];
		$tempfile = $_FILE['tmp_name'];
		$errno = $_FILE['error'];
		$filesize = $_FILE['size'];
		
		if (!empty($filename))
		{
			echo "Caricamento di $filename ";
			$pos=strpos(strtoupper($filename),'-THUMB.');
			if ($pos === false) // se foto
			{
				$is_thumbnail = false;
				echo "(foto):\n";
			}
			else // altrimenti thumbnail
			{
				$is_thumbnail = true;
				$photo_name = substr($filename,0,$pos).substr($filename,-4);
				echo "(thumbnail di ".substr($filename,0,$pos).substr($filename,-4)."):\n";
			}
			
			$full_filename = $album_dir.$nome_album."/".$filename;
			if ($is_thumbnail && !file_exists($album_dir.$nome_album."/".$photo_name))
			{
				print "   la foto $photo_name non esiste nell'album $nome_album! Impossibile caricare il relativo thumbnail. Caricare $photo_name prima.<br>\n";
			}
			elseif (file_exists($full_filename))
			{
				print "   la foto $filename gi&agrave; esiste nell'album $nome_album! Cancellarla prima, se si vuole caricarla di nuovo.<br>\n";
			}
			elseif ($is_thumbnail && $filesize > $MAX_THUMB_FILE_SIZE)
			{
				print "   il thumbnail $filename ha dimensione maggiore di $MAX_THUMB_FILE_SIZE bytes ($filesize)! Ridurne le dimensioni!<br>\n";
			}
			elseif (move_uploaded_file($tempfile, $full_filename)) 
			{ 
				log_action($album_dir,$_SERVER['REMOTE_ADDR'].",".$full_filename.", " . date("l dS of F Y h:i:s A"));
				
				print "   la foto $filename &egrave; stata caricata con successo nell'album $nome_album.<br>\n"; 
			}
			else
			{
				log_action($album_dir,$_SERVER['REMOTE_ADDR'].",".$full_filename.", " . date("l dS of F Y h:i:s A"));
				
				print "Errore nell'upload della foto:\n"; 
				
				switch ($errno) 
				{
					case UPLOAD_ERR_INI_SIZE :
					case UPLOAD_ERR_FORM_SIZE :
						echo("File troppo grande! La dimensione massima e' di $MAX_FILE_SIZE kBytes\n");
						break;
					case UPLOAD_ERR_PARTIAL :
						echo("Upload eseguito parzialmente!\n"); 
						break;
					case UPLOAD_ERR_NO_FILE :
						echo("Nessun file &egrave; stato inviato!\n"); 
						break;
					default:
						echo("Unknown error type: [$errno]<br>\n");
				}
				
				print "\nAlcune informazioni:\n\n";
				print_r($_FILE);
				//die();
			}
			
			print "\n<hr>\n";
		}
	
	}
}
else
{
	die("Password errata!");
}


# logga il contatto
$counter = count_page("admin_upload_foto",array("COUNT"=>1,"LOG"=>1),$filedir_counter); # abilita il contatore, senza visualizzare le cifre, e fai il log
?>

<hr>
<a href="<?php echo $_SERVER['HTTP_REFERER']; ?>">Torna indietro</a>

</body>
</html>
