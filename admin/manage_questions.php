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

$id_questions = $data;
$basefile_questions = "lotteria_".sprintf("%03d",$id_questions).".txt";
$file_questions = $root_path."custom/lotterie/".$basefile_questions;	// nome del file di configurazione relativo a id_questions
$file_log_questions = $root_path."custom/lotterie/lotteria_".sprintf("%03d",$id_questions)."_log.txt";	// nome del file di registrazione

if (file_exists($file_questions))
{
	$lotteria = get_config_file($file_questions);
	$lotteria_nome = $lotteria["Attributi"][0][0];
	$lotteria_stato = $lotteria["Attributi"][0][1];
	$lotteria_auth = $lotteria["Attributi"][0][2];
	$lotteria_inizio_giocate = $lotteria["Attributi"][0][3];
	$lotteria_fine_giocate = $lotteria["Attributi"][0][4];
	$lotteria_risultati = $lotteria["Attributi"][0][5];
	
	// numero di files di chiavi (eventuali)
	$num_key_files = count($lotteria['keyfiles']);	// numero di files di chiavi da creare (per gestire diverse categorie
	$num_keys = array();				// numero di chiavi per ciascun file
	foreach($lotteria['keyfiles'] as $keyfile_id => $keyfile_data)
	{
		$num_keys[$keyfile_id] = $keyfile_data[2];
	}
}

$tickets_per_row = 3;		// numero di biglietti da stampare per riga;
$tickets_per_column = 2;	// numero di biglietti da stampare per colonna;
$tickets_per_page = $tickets_per_row*$tickets_per_column;		// numero di biglietti da stampare per pagina


switch ($mode)
{
case 'index':
	echo "<div class=\"titolo_tabella\">Lotterie disponibili:</div>";
	
	// individua i file di lotteria (se esiste solo lotteria_006.txt, $id vale 6
	$path_prefix = 	$root_path."custom/lotterie/";	// nome del file di configurazione relativo a id_questions
	$id = -1;
	if ($dh = opendir($path_prefix)) 
	{
		while (($file = readdir($dh)) !== false) 
		{
		   if ( (substr($file,0,9) == "lotteria_") & (substr($file,12,4) == '.txt') & (substr($file,-4) == '.txt') )
			{
				// lotteria trovata
				$file_questions = $file;
				
				// carica file di configurazione della lotteria
				$lotteria = get_config_file($path_prefix.$file_questions);
				
				$lotteria_nome = $lotteria["Attributi"][0][0];
				
				if ($id < substr($file,9,3))
				{
					$id = substr($file,9,3)+0;
				}
				
				echo "$id) <a href=\"manage_questions?task=edit&amp;data=$id\">$lotteria_nome</a> <br>\n";
			}
		}
		closedir($dh);
	}
	if ($id<0)
	{
		echo "Non ci sono lotterie n&eacute; questionari disponibili!<br>\n";
	}
	
	break;
case 'edit':
	// verifica che $id_questions sia un id relativo ad una lotteria o questionario valida
	if (!file_exists($file_questions))
	{
		die("La lotteria $id_questions non esiste!");
	}
	echo "<div class=\"titolo_tabella\">Configurazione &quot;$lotteria_nome&quot;</div>";
	
	// eventuale inizializzazione chiavi
	if ($lotteria_auth == 'key')
	{
		$keys = get_question_keys($id_questions);
		if (count($keys) !== $num_key_files)
		{
			echo "&Egrave; necessario inizializzare i file di chiavi!<br><br>";
			echo "<a href=\"manage_questions?task=init&amp;data=$id_questions\">Inizializza i $num_key_files file di chiavi (";
			foreach ($num_keys as $id => $numero)
			{
				echo $numero;
				if ($id < $num_key_files-1)
				{
					echo ", ";
				}
			}
			echo " chiavi)</a><br>";
		}
	}
	
	// modifica del file di configurazione lotteria_xxx.txt
	echo "<hr>";
	echo "<a href=\"manage_config_file.php?config_file=$basefile_questions\">Configurazione</a>";
	
	// visualizzazione di tutte le giocate
	echo "<hr>";
	echo "<a href=\"manage_questions.php?task=show_giocate&amp;data=$id_questions\">Visualizza giocate</a>";
	
	// stampa dei biglietti
	$keys = get_question_keys($id_questions);
	echo "<hr>";
	echo "Stampa biglietti:<br>\n";
	foreach (array_keys($keys) as $keyfile_id)
	{
		echo "<form action=\"manage_questions.php?task=ticket_page&amp;data=$id_questions\" method=\"post\">\n";
		echo "&nbsp;&nbsp;&nbsp;Biglietti di tipo $keyfile_id (".$lotteria['keyfiles'][$keyfile_id][1]."): \n";
		$tag_string = sprintf("keyfile_%03d",$keyfile_id);
		echo "<select name=\"keyfile_select\">";
		echo "<option value=\"-1\" selected>&nbsp;</option>\n";
		for ($key_id = 0; $key_id < (count($keys[$keyfile_id])-1)/4; $key_id++)
		{
			$start = $key_id*$tickets_per_page+1;
			echo "<option value=\"$start\">".$start."-".($start+$tickets_per_page-1)."</option>\n";
			//for ($i=0;$i<$tickets_per_page;$i++) {echo $keys[$keyfile_id][$key_id*$tickets_per_page+$i][0]."<br>;";}
		}
		echo "</select>";
		echo '<input type="submit" value="Visualizza la pagina da stampare" />';
		echo "<input type=\"hidden\" name=\"keyfile_id\" value=\"$keyfile_id\" />";
		echo "</form>";
	}
	
	break;
case 'ticket_page':
	$selection = $_REQUEST["keyfile_select"];
	$keyfile_id = $_REQUEST["keyfile_id"];
	
	if ($selection == -1)
	{
		die("Errore nell'inserimento");
	}
	
	$keys = get_question_keys($id_questions);
	echo "<table border=1px>\n";
	$n = 0;
	for ($i=0;$i<$tickets_per_column;$i++)
	{
		echo '<tr style="height:330px;">'."\n";
		for ($j=0;$j<$tickets_per_row;$j++)
		{
			$ticket_id = $selection+$n-1;
			echo '<td style="width:530px; vertical-align: top;">'."\n";
			
			// disegna il singolo biglietto
			echo "<b><div style=\"text-align: center;\">$lotteria_nome</div></b>\n";
			echo "<table><tr><td>";
			echo "Modo: ".$lotteria['keyfiles'][$keyfile_id][1]."\n";	// caption relativo al file di chiavi
			echo ", Numero Seriale: ".($ticket_id+1)."<br>\n";		// numero del biglietto (da 1 a ...)
			echo "Key: ".$keys[$keyfile_id][$ticket_id][0]."<br>\n";	// codice segreto unico
			echo "<br>\n";
			echo "Giocato da:&nbsp; &nbsp; <input type=\"edit\" /><br>\n";
			echo "Ricevuto da:&nbsp; &nbsp; <input type=\"edit\" /><br>\n";
			echo "</td></tr>";
			
			echo "<tr><td>";
			echo "<hr>";
			echo "Modo: ".$lotteria['keyfiles'][$keyfile_id][1]."\n";	// caption relativo al file di chiavi
			echo ", Numero Seriale: ".($ticket_id+1)."<br>\n";		// numero del biglietto (da 1 a ...)
			echo "Key: ".$keys[$keyfile_id][$ticket_id][0]."<br>\n";	// codice segreto unico
			echo "<br>\n";
			echo $lotteria['msg_ticked_info'][0][0]."<br>\n";
			echo "<br>\n";
			$domande = $lotteria['Domande'];
			foreach ($domande as $id => $domanda)
			{
				echo "R. #".($id+1)."/".count($domande)."&nbsp; : <input style=\"width:100px;\" type=\"edit\" /> \n";
				if ((round(($id+1)/2)*2-($id+1)) == 0)
				{
					echo "<br>\n";
				}
			}
			echo "</td></tr></table>";
			
			echo "</td>\n";
			$n++;
		}
		echo "</tr>\n";
	}
	echo "</table>\n";
	
	die();
	break;
case 'show_giocate':
	echo "<div class=\"titolo_tabella\">Giocate &quot;$lotteria_nome&quot;</div><br>";
	
	$giocate = get_config_file($file_log_questions);
	show_giocate($giocate['default']);
	break;
case 'init':
	// verifica che $id_questions sia un id relativo ad una lotteria o questionario valida
	if (!file_exists($file_questions))
	{
		die("La lotteria $id_questions non esiste!");
	}
	
	echo "Creo i file di chiavi...<br>\n";
	create_key_files($id_questions,$num_key_files,$num_keys);
	break;
default:
	echo "<a href=\"articoli.php\">Torna indietro</a><br><br>\n";
	die("mode: \"".$mode."\", data: \"".$data."\"\n");
}


log_action($articles_dir,"Action: <$mode>, data: <$data>, ".date("l dS of F Y h:i:s A"));

?>

<hr>
<div align="right"><a href="index.php" class="txt_link">Torna alla pagina amministrativa principale</a></div>

</body>
</html>
 
