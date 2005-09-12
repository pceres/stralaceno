<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 TRANSITIONAL//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<?php

require_once('../libreria.php');

# dichiara variabili
extract(indici());
?>
<head>
  <title>Gestione album</title>
  <meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1">
  <meta name="GENERATOR" content="Quanta Plus">
  <style type="text/css">@import "<?php echo $filename_css ?>";</style>
</head>

<body class="admin">

Albums attuali:<br>
<small>formato:  file::titolo::descrizione</small>

<form action="upload_text.php" method="post">
	<input type="hidden" name="filename" value="<?php echo $filename_albums ?>">
	<?php
	$bulk = file($filename_albums);
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

<hr>
Visualiza tutte le foto disponibili per ciascun album:<br>
<form name="debug_album" action="<?php echo $script_abs_path; ?>album.php" method="post">
<?php
$elenco_foto = get_config_file($filename_albums,3);
foreach ($elenco_foto as $anno => $album)
{
	echo "<a href=\"$script_abs_path"."album.php?anno=$anno&amp;password=show_all_photos\">$anno</a> ";
}
?>
</form>


<?php
# logga il contatto
$counter = count_page("admin_albums",array("COUNT"=>1,"LOG"=>1),$filedir_counter); # abilita il contatore, senza visualizzare le cifre, e fai il log
?>

</body>
</html>
