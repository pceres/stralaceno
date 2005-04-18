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

include '../libreria.php';
$uploaddir = $articles_dir;

$password_ok = "stralacenoadmin"; 

print "<pre>";

$old_name = $_FILES['userfile']['name'];
$new_name = $_REQUEST['filename'];
$password = $_REQUEST['password'];
$author = $_REQUEST['author'];
$title = $_REQUEST['title'];
$id_articolo = $_REQUEST['id_articolo'];

$ok = FALSE;
if ($password_ok == $password) 
{
	$ok = TRUE;
}

if (file_exists($uploaddir . $new_name))
{
	echo "L'articolo $id_articolo &egrave; gi&agrave; stato pubblicato (".$uploaddir.$new_name." gia' esiste!)";
	break;
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
	die("Password errata!");
}

echo "<hr>\n";
echo "id articolo: $id_articolo\n";
echo "title: $title\n";
echo "autore: $author\n";

$bulk = file($uploaddir . $new_name);

publish_article($id_articolo,$author,$title,$bulk,$uploaddir.$new_name,$max_online_articles);


# logga il contatto
$counter = count_page("admin_upload_articoli",array("COUNT"=>1,"LOG"=>1),$filedir_counter); # abilita il contatore, senza visualizzare le cifre, e fai il log
?>


</body>
</html>
