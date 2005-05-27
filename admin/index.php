#!/usr/local/bin/php 
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 TRANSITIONAL//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<?php

require_once('../libreria.php');

# dichiara variabili
extract(indici());
?>
<head>
  <title>Amministrazione</title>
  <meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1">
  <meta name="GENERATOR" content="Quanta Plus">
  <style type="text/css">@import "<?php echo $css_site_path ?>/stralaceno.css";</style>
</head>

<body class="admin">

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

<a href='articoli.php'>Gestione articoli</a>

<hr>

<a href='links.php'>Gestione links</a>

<?php
# logga il contatto
$counter = count_page("admin_index",array("COUNT"=>1,"LOG"=>1),$filedir_counter); # abilita il contatore, senza visualizzare le cifre, e fai il log
?>

</body>
</html>
