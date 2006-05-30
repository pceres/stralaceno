<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 TRANSITIONAL//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<?php

require_once('../libreria.php');

# dichiara variabili
extract(indici());

// verifica che si stia arrivando a questa pagina da ../index.php
$referer = $_SERVER['HTTP_REFERER'];
if ( !isset($_SERVER['HTTP_REFERER']) | (strpos($referer,"http://".$_SERVER['HTTP_HOST'].$script_abs_path."index.php")!='0') )
{
	header("Location: ".$script_abs_path."index.php");
	exit();
}

?>
<head>
  <title>Amministrazione</title>
  <meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1">
  <meta name="GENERATOR" content="Quanta Plus">
  <style type="text/css">@import "<?php echo $filename_css ?>";</style>
</head>

<body class="admin">

<script type="text/javaScript" src="<?php echo $site_abs_path ?>admin/md5.js"></script>

<?php 
$name = $filename_tempi;
$name = substr($name,strrpos($name,'/')+1) 
?>
<form enctype="multipart/form-data" action="upload.php" method="post" onSubmit="cripta_campo_del_form(this,'password')">
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
<form enctype="multipart/form-data" action="upload.php" method="post" onSubmit="cripta_campo_del_form(this,'password')">
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
<form enctype="multipart/form-data" action="upload.php" method="post" onSubmit="cripta_campo_del_form(this,'password')">
<input type="hidden" name="MAX_FILE_SIZE" value="30000">
<input type="hidden" name="filename" value="<?php echo $name ?>">
Nuovo file "<?php echo $name ?>" da caricare: <input name="userfile" type="file">
Password: <input name="password" type="password">
<input type="submit" value="Invia File">
</form>

<hr>

Gestione layout della <a href='manage_config_file.php?config_file=layout_left.txt'>colonna sinistra</a> 
e della <a href='manage_config_file.php?config_file=layout_right.txt'>colonna destra</a>

<hr>

<a href='articoli.php'>Gestione articoli</a>

<hr>

<a href='manage_config_file.php?config_file=links.txt'>Gestione links</a>

<hr>

<a href='manage_albums.php'>Gestione album</a>

<hr>

<a href='manage_config_file.php?config_file=pregfas.txt'>Gestione registro dei fanfaroni</a>

<hr>

<a href='manage_config_file.php?config_file=lettere_sito.txt'>Gestione lettere alla Stralaceno</a>

<hr>

<form action="manage_questions.php?task=index" method="post" onSubmit="cripta_campo_del_form(this,'password')">
Gestione lotterie e questionari: Password: <input name="password" type="password">
<input type="submit" value="Vai">
</form>

<hr>

<?php
# logga il contatto
$counter = count_page("admin_index",array("COUNT"=>1,"LOG"=>1),$filedir_counter); # abilita il contatore, senza visualizzare le cifre, e fai il log
?>

<?php
echo $homepage_link;
?>

</body>
</html>
