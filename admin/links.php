#!/usr/local/bin/php 
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 TRANSITIONAL//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<?php

require_once('../libreria.php');

# dichiara variabili
extract(indici());
?>
<head>
  <title>Gestione links</title>
  <meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1">
  <meta name="GENERATOR" content="Quanta Plus">
  <style type="text/css">@import "<?php echo $css_site_path ?>/stralaceno.css";</style>
</head>

<body class="admin">

Links attuali:<br>
<small>formato:  link::name</small>

<form action="upload_text.php" method="post">
	<input type="hidden" name="filename" value="<?php echo $filename_links ?>">
	<?php
	$bulk = file($filename_links);
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
