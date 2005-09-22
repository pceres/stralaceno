#!/usr/local/bin/php 
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 TRANSITIONAL//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<?php

require_once('../libreria.php');

# dichiara variabili
extract(indici());

$filename = $_REQUEST['config_file'];

if (empty($filename) | (!in_array($filename,array('pregfas.txt'))))
{
	die("File inesistente!");
}
	
?>
<head>
  <title>Gestione PREGFAS</title>
  <meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1">
  <meta name="GENERATOR" content="Quanta Plus">
  <style type="text/css">@import "<?php echo $filename_css ?>";</style>
</head>

<body class="admin">

Modifica del file di configurazione <?php echo $filename; ?>:<br>
<!--small>formato:  nome e cognome::promesa solenne::eventuali note</small-->

<?php
$filename = $config_dir.$filename;
?>
<form action="upload_text.php" method="post">
	<input type="hidden" name="filename" value="<?php echo $filename ?>">
	<?php
	$bulk = file($filename);
	echo "<textarea name=\"testo\" rows=15 cols=120>";
	for ($i = 0; $i < count($bulk); $i++)
	{
		echo $bulk[$i];
	}
	echo "</textarea>";
	?>
	Password: <input name="password" type="password">
	<input type="submit" value="Invia File">
</form>




<?php
# logga il contatto
$counter = count_page("admin_links",array("COUNT"=>1,"LOG"=>1),$filedir_counter); # abilita il contatore, senza visualizzare le cifre, e fai il log
?>

</body>
</html>
