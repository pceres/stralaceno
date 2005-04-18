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
$uploaddir = "../$articles_dir";

//$uploaddir = '../articoli/';
$password_ok = "stralacenoadmin"; 
$max_articoli = 3; // numero massimo di articoli online

print "<pre>";

$old_name = $_FILES['userfile']['name'];
$new_name = $_REQUEST['filename'];
$password = $_REQUEST['password'];
$author = $_REQUEST['author'];
$title = $_REQUEST['title'];
$id_articolo = $_REQUEST['id_articolo'];

$ok = FALSE;
if ($password_ok == $password) {
	$ok = TRUE;
	}

if (file_exists($uploaddir . $new_name))
{
	echo "L'articolo $id_articolo &egrave; gi&agrave; stato pubblicato ($uploaddir."."$new_name gia' esiste!)";
	break;
}

if ($ok == TRUE) {
	if (move_uploaded_file($_FILES['userfile']['tmp_name'], $uploaddir . $new_name)) { 
		print "Il file $new_name &egrave; stato inviato con successo.\n"; 

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
		print "Nessun file &egrave; stato inviato!\n"; 
		break;
		default:
		echo "Unkown error type: [$errno]<br>\n";
		break;
	  }
	 }
	}
else {
	die("Password errata!");
	}

// Modifica il file di testo per aggiungere i campi titolo ed autore ed i delimitatori di testo

echo "id articolo: $id\n";
echo "title: $title\n";
echo "autore: $author\n";

$bulk = file($uploaddir . $new_name);

$str_author = "Autore::$author\r\n";
$str_title = "Titolo::$title\r\n";
$str_begin_text = "--- Begin body ---\r\n";
$str_end_text = "--- End body ---\r\n";

$bulk = array_merge($str_author,$str_title,$str_begin_text,$bulk,$str_end_text);

// scrivi il file art_x.txt
$handle=fopen($uploaddir . $new_name,'w');
for ($i=0;$i<count($bulk); $i++) fwrite($handle, $bulk[$i]);
fclose($handle);

// leggi l'elenco degli articoli gia' online
$art_list = get_online_articles("../$article_online_file"); // carica l'elenco degli articoli da pubblicare

// aggiungi il nuovo articolo in cima
$art_list = array_merge($id_articolo,$art_list);

// lascia, eventualmente, solo gli ultimi 10 articoli della lista
if (count($art_list) > $max_articoli)
{
	$art_list = array_slice($art_list,0,$max_articoli);
}

// salva l'elenco degli articoli online
set_online_articles("../$article_online_file",$art_list);

# logga il contatto
# $counter = count_page("admin_upload_articoli",array("COUNT"=>1,"LOG"=>1)); # abilita il contatore, senza visualizzare le cifre, e fai il log
?>


</body>
</html>
