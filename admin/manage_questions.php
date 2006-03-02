<?php

require_once('../libreria.php');

# dichiara variabili
extract(indici());

// verifica che si stia arrivando a questa pagina da quella amministrativa principale
/*if ( !isset($_SERVER['HTTP_REFERER']) | ("http://".$_SERVER['HTTP_HOST'].$script_abs_path."admin/" != substr($_SERVER['HTTP_REFERER'],0,strrpos($_SERVER['HTTP_REFERER'],'/')+1) ) )
{
	header("Location: ".$script_abs_path."admin/index.php");
	exit();
}
*/
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 TRANSITIONAL//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>

<head>
  <title>Gestione lotterie/questionari</title>
  <meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1">
  <meta name="GENERATOR" content="Kate">
  <style type="text/css">@import "<?php echo $filename_css ?>";</style>
</head>
<body>

<?php

$mode = $_REQUEST['task'];
$data = $_REQUEST['data'];
$password = $_REQUEST['password'];

/*$password_ok = $password_articoli;

if ($password != $password_ok)
{
	echo "<a href=\"articoli.php\">Torna indietro</a><br><br>\n";
	die("La password inserita non &egrave; corretta!<br>\n");
}
*/
switch ($mode)
{

case 'init':
	$id_questions = $data;

	$file_questions = $root_path."custom/lotterie/lotteria_".sprintf("%03d",$id_questions).".txt";	// nome del file di configurazione relativo a id_questions
	$file_log_questions = $root_path."custom/lotterie/lotteria_".sprintf("%03d",$id_questions)."_log.txt";	// nome del file di registrazione
	
	// verifica che $id_questions sia un id relativo ad una lotteria o questionario valida
	if (!file_exists($file_questions))
	{
		die("La lotteria $id_questions non esiste!");
	}
	
	echo "Creo i file di chiavi...<br>\n";
	$num_files = 2;			// numero di files di chiavi da creare (per gestire diverse categorie
	$num_keys = array(200,300);	// numero di chiavi per ciascun file
	create_key_files($id_questions,$num_files,$num_keys);
	break;
default:
	echo "<a href=\"articoli.php\">Torna indietro</a><br><br>\n";
	die("mode: \"".$mode."\", data: \"".$data."\"\n");
}


log_action($articles_dir,"Action: <$mode>, data: <$data>, ".date("l dS of F Y h:i:s A"));

?>

<hr>
<!--a href="questions.php">Torna indietro</a-->

</body>
</html>
 
