<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 TRANSITIONAL//EN" "http://www.w3.org/TR/html4/loose.dtd">
<?php

require_once('libreria.php');

# dichiara variabili
extract(indici());

$id_questions = 1;

$file_questions = $root_path."custom/lotterie/lotteria_".sprintf("%03d",$id_questions).".txt";	// nome del file di configurazione
$file_log_questions = $root_path."custom/lotterie/lotteria_".sprintf("%03d",$id_questions)."_log.txt";	// nome del file di registrazione


$lotteria = get_config_file($file_questions);
$nome_lotteria = $lotteria["Attributi"][0][0];

?>
<html>
<head>
  <title>Archivio giocate <?php echo $nome_lotteria ?></title>
  <meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1">
  <meta name="GENERATOR" content="Quanta Plus">
  <style type="text/css">@import "<?php echo $filename_css ?>";</style>
</head>
<body>
  
<?php

echo htmlspecialchars("ciao l'amore");

if (get_magic_quotes_gpc())
{
	echo("magic_quotes_gpc abilitato");
}
else
{
	echo("magic_quotes_gpc disabilitato");
}

// $bulk = get_config_file($file_log_questions);
// 
// echo "<div class=\"titolo_tabella\">Giocate &quot;$nome_lotteria&quot;</div>\n";
// 
// echo '<table border=1><tbody>';
// 
// $count_giocata = 0;
// foreach($bulk['default'] as $giocata)
// {
// 	echo "<tr>";
// 	echo "<td>".($count_giocata+1)."</td>\n";
// 	echo "<td>".$giocata[0]."</td>\n";
// 	//echo "<td>".$giocata[1]."</td>\n";
// 	echo "<td>".$giocata[2]."</td>\n";
// 	echo "<td>".$giocata[3]."</td>\n";
// 	echo "</tr>";
// 	$count_giocata++;
// }
// 
// echo "</tbody></table>";
// 
// die();

