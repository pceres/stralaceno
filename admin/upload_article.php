<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 TRANSITIONAL//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<?php

require_once('../libreria.php');

# dichiara variabili
extract(indici());
?>
<head>
  <title>Caricamento articoli sul sito</title>
  <meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1">
  <meta name="GENERATOR" content="Quanta Plus">
  <style type="text/css">@import "<?php echo $filename_css ?>";</style>
</head>
<body>
 

<?php

$uploaddir = $articles_dir;

$password_ok = $password_articoli; 

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
		log_action($uploaddir,$uploaddir . $new_name . ", " . date("l dS of F Y h:i:s A"));
		
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

echo "<hr>\n";
echo "id articolo: $id_articolo\n";
echo "title: $title\n";
echo "autore: $author\n";

$bulk = file($uploaddir . $new_name);
unlink($uploaddir . $new_name); // cancella il file in maniera che l'articolo non venga ancora considerato pubblicato

// salva sul sito l'articolo (ma non e' ancora online)
$id_articolo = upload_article($author,$title,$bulk,$uploaddir);

// leggi l'elenco degli articoli gia' online
$art_list = get_online_articles($article_online_file); // carica l'elenco degli articoli da pubblicare

// aggiungi il nuovo articolo in cima
$art_list = array_merge($id_articolo,$art_list);

// salva il nuovo elenco degli articoli online
publish_online_articles($art_list);


# logga il contatto
$counter = count_page("admin_upload_articoli",array("COUNT"=>1,"LOG"=>1),$filedir_counter); # abilita il contatore, senza visualizzare le cifre, e fai il log
?>

<hr>
<a href="articoli.php">Torna indietro</a>

</body>
</html>
