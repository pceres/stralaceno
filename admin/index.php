#!/usr/local/bin/php 
<!DOCTYPE public "-//w3c//dtd html 4.01 transitional//en" 
		"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
  <title>Amministrazione</title>
  <meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1">
  <meta name="GENERATOR" content="Quanta Plus">
</head>

<body>

<?php
include '../libreria.php';
$art_id=get_article_list("../$articles_dir"); // carica l'elenco degli articoli disponibili ($articles dir e' relativo alla radice)
$id = max($art_id)+1; // determina il primo id disponibile
$art_filename = "art_$id.txt";
?>

<form enctype="multipart/form-data" action="upload.php" method="post">
<input type="hidden" name="MAX_FILE_SIZE" value="30000">
<input type="hidden" name="filename" value="tempi_laceno.csv">
Nuovo file "tempi_laceno.csv" da caricare: <input name="userfile" type="file">
Password: <input name="password" type="password">
<input type="submit" value="Invia File">
</form>

<form enctype="multipart/form-data" action="upload.php" method="post">
<input type="hidden" name="MAX_FILE_SIZE" value="30000">
<input type="hidden" name="filename" value="atleti_laceno.csv">
Nuovo file "atleti_laceno.csv" da caricare: <input name="userfile" type="file">
Password: <input name="password" type="password">
<input type="submit" value="Invia File">
</form>

<form enctype="multipart/form-data" action="upload.php" method="post">
<input type="hidden" name="MAX_FILE_SIZE" value="30000">
<input type="hidden" name="filename" value="organizzatori_laceno.csv">
Nuovo file "organizzatori_laceno.csv" da caricare: <input name="userfile" type="file">
Password: <input name="password" type="password">
<input type="submit" value="Invia File">
</form>

<hr>


<form enctype="multipart/form-data" action="upload_article.php" method="post">
<input type="hidden" name="MAX_FILE_SIZE" value="30000">
<input type="hidden" name="id_articolo" value="<?php echo $id; ?>">
<?php echo "<input type=\"hidden\" name=\"filename\" value=\"art_$id.txt\">"; ?>
Nuovo file "<?php echo $art_filename; ?>" da caricare: <input name="userfile" type="file">
<br>
Titolo: <input name="title" type="edit">
<br>
Autore: <input name="author" type="edit">
<br>
Password: <input name="password" type="password">
<br>
<input type="submit" value="Invia File">
</form>


</body>
</html>
