<?php

require_once('../libreria.php');

# dichiara variabili
extract(indici());

// verifica che si stia arrivando a questa pagina da .../album.php
$referer = $_SERVER['HTTP_REFERER'];
if ( !isset($_SERVER['HTTP_REFERER']) | (strpos($referer,"http://".$_SERVER['HTTP_HOST'].$script_abs_path."album.php")!='0') )
{
	header("Location: ".$script_abs_path."admin/index.php");
	exit();
}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 TRANSITIONAL//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>

<head>
  <title>Gestione album</title>
  <meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1">
  <meta name="GENERATOR" content="Programmers Notepad">
  <style type="text/css">@import "<?php echo $filename_css ?>";</style>
</head>
<body>

<?php

$mode = $_REQUEST['task'];
$data = $_REQUEST['data'];
$password = $_REQUEST['password'];

$password_ok = $password_album;

if ($password != $password_ok)
{
	echo "<a href=\"$referer\">Torna indietro</a><br><br>\n";
	die("La password inserita non &egrave; corretta!<br>\n");
}

switch ($mode)
{
case 'cancel':
	echo("Cancellazione della foto ".$data.":<br><br>\n");

	if (!is_file($data))
	{
		echo "Il file $data non esiste!<br><br>\n";
		die("<a href=\"$referer\">Torna indietro</a><br>");
	}
	
	// a questo punto sono sicuro che il file esiste
	if (unlink($data)) // cancello fisicamente il file
	{
		echo "La foto $data e' stata cancellata!<br>\n";
	}
	else
	{
		echo "La foto $data e' gia' stata cancellata!<br>\n";
	}

	// elimina l'eventuale thumbnail
	$thumb = substr($data,0,-4)."-thumb.".substr($data,-3);
	if (unlink($thumb)) // cancello fisicamente il file
	{
		echo "Il thumbnail $thumb e' stato cancellato!<br>\n";
	}

	break;
	
default:
	echo "<a href=\"<?php echo $referer; ?>\">Torna indietro</a><br><br>\n";
	die("mode: \"".$mode."\", data: \"".$data."\"\n");
}


log_action($album_dir,"Author:<".$_SERVER['REMOTE_ADDR'].">, Action: <$mode>, data: <$data>, ".date("l dS of F Y h:i:s A"));

?>

<hr>
<a href="<?php echo $referer; ?>">Torna indietro</a>

</body>
</html>
 
