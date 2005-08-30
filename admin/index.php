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
  <style type="text/css">@import "<?php echo $filename_css ?>";</style>
</head>

<body class="admin">

<?php 
$name = $filename_tempi;
$name = substr($name,strrpos($name,'/')+1) 
?>
<form enctype="multipart/form-data" action="upload.php" method="post">
<input type="hidden" name="MAX_FILE_SIZE" value="30000">
<input type="hidden" name="filename" value="<?php echo $name ?>">
Nuovo file "<?php echo $name ?>" da caricare: <input name="userfile" type="file">
Password: <input name="password" type="password">
<input type="submit" value="Invia File">
</form>

<?php 
$name = $filename_atleti;
$name = substr($name,strrpos($name,'/')+1) 
?>
<form enctype="multipart/form-data" action="upload.php" method="post">
<input type="hidden" name="MAX_FILE_SIZE" value="30000">
<input type="hidden" name="filename" value="<?php echo $name ?>">
Nuovo file "<?php echo $name ?>" da caricare: <input name="userfile" type="file">
Password: <input name="password" type="password">
<input type="submit" value="Invia File">
</form>

<?php 
$name = $filename_organizzatori;
$name = substr($name,strrpos($name,'/')+1) 
?>
<form enctype="multipart/form-data" action="upload.php" method="post">
<input type="hidden" name="MAX_FILE_SIZE" value="30000">
<input type="hidden" name="filename" value="<?php echo $name ?>">
Nuovo file "<?php echo $name ?>" da caricare: <input name="userfile" type="file">
Password: <input name="password" type="password">
<input type="submit" value="Invia File">
</form>

<hr>

<a href='articoli.php'>Gestione articoli</a>

<hr>

<a href='links.php'>Gestione links</a>

<hr>

<a href='manage_albums.php'>Gestione album</a>

<?php
# logga il contatto
$counter = count_page("admin_index",array("COUNT"=>1,"LOG"=>1),$filedir_counter); # abilita il contatore, senza visualizzare le cifre, e fai il log
?>

</body>
</html>
