<?php

require_once('../libreria.php');

# dichiara variabili
extract(indici());

// verifica che si stia arrivando a questa pagina da quella amministrativa principale
if ( !isset($_SERVER['HTTP_REFERER']) | ("http://".$_SERVER['HTTP_HOST'].$script_abs_path."admin/" != substr($_SERVER['HTTP_REFERER'],0,strrpos($_SERVER['HTTP_REFERER'],'/')+1) ) )
{
	header("Location: ".$script_abs_path."admin/index.php");
	exit();
}

$filename = $_REQUEST['config_file'];

if (empty($filename))
{
	die("File inesistente!");
}

$enabled_config_files = array(
	"links.txt",				// link visualizzati nel modulo "Link"
	"pregfas.txt",				// pubblico registro dei fanfaroni della stralaceno (modulo_custom)
	"lettere_sito.txt",			// le vostre lettere (modulo_custom)
	"layout_left.txt",			// layout della colonna sinistra in homepage
	"layout_right.txt",			// layout della colonna destra in homepage
	"lotteria_???.txt",			// file di configurazione lotteria xxx
	"lotteria_???_ans.php"			// file risposte esatte lotteria xxx
	); // elenco dei file di configurazione che e' possibile modificare 

$enabled_config_dirs = array(
	$config_dir,				// link visualizzati nel modulo "Link"
	$config_dir,				// pubblico registro dei fanfaroni della stralaceno (modulo_custom)
	$config_dir,				// lettere alla Stralaceno (modulo_custom)
	$config_dir,				// layout della colonna sinistra in homepage
	$config_dir,				// layout della colonna destra in homepage
	$questions_dir,				// file di configurazione lotteria xxx
	$questions_dir				// file risposte esatte lotteria xxx
	); // elenco delle directory dei file di configurazione che e' possibile modificare 

$file_ok = false;
foreach ($enabled_config_files as $id => $config_name)
{
	$name = $config_name;
	$dir = $enabled_config_dirs[$id];	/* directory contenente il file in esame */

	// verifica la presenza di carattere jolly "?"
	while ($pos=strpos($name,'?'))
	{
		$name[$pos]=$filename[$pos];
	}
	
	if ($name === $filename)
	{
		$file_ok = true;
		break;
	}
	
}

//if (!in_array($filename,$enabled_config_files))
if (!$file_ok)
{
	die("Non e' possibile modificare il file $filename! Contattare il webmaster.");
}
	
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 TRANSITIONAL//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
  <title>Modifica file di configurazione <?php echo $filename; ?></title>
  <meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1">
  <meta name="GENERATOR" content="Quanta Plus">
  <style type="text/css">@import "<?php echo $filename_css ?>";</style>
</head>

<body class="admin">

<script type="text/javaScript" src="<?php echo $site_abs_path ?>admin/md5.js"></script>

Modifica del file di configurazione <?php echo $filename; ?>:<br>

<?php
$filename = $dir.$filename;
?>
<form action="upload_text.php" method="post" onSubmit="cripta_campo_del_form(this,'password')">
	<input type="hidden" name="filename" value="<?php echo $filename ?>">
	<?php
	$bulk = file($filename);
	echo "<textarea name=\"testo\" rows=15 cols=120>";
	for ($i = 0; $i < count($bulk); $i++)
	{
		echo $bulk[$i];
	}
	echo "</textarea>\n";
	?>
	Password: <input name="password" type="password">
	<input type="submit" value="Invia File">
</form>




<?php
# logga il contatto
$counter = count_page("admin_edit_config",array("COUNT"=>1,"LOG"=>1),$filedir_counter); # abilita il contatore, senza visualizzare le cifre, e fai il log
?>

<hr>
<div align="right"><a href="index.php" class="txt_link">Torna alla pagina amministrativa principale</a></div>

</body>
</html>
