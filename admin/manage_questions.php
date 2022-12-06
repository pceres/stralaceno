<?php

require_once('../libreria.php');

# dichiara variabili
extract(indici());

/*
questa libreria esamina i cookies o i parametri http (eventualmente) inviati, e genera l'array $login con i campi
'username',	: login
'usergroups',	: lista dei gruppi di appartenenza (separati da virgola)
'status',		: stato del login: 'none','ok_form','ok_cookie','error_wrong_username','error_wrong_userpass','error_wrong_challenge','error_wrong_IP'
*/
require_once('../login.php');


// verifica che si stia arrivando a questa pagina da quella amministrativa principale
if (
    !isset($_SERVER['HTTP_REFERER']) // referer not set ...
    | (strlen(strpos(substr($_SERVER['HTTP_REFERER'] ,0,strrpos($_SERVER['HTTP_REFERER'] ,'/')+1),"://".$_SERVER['HTTP_HOST'].$script_abs_path."admin/").' ') == 1) // ...or (referer ~= link_from_admin_pages)
    | (!in_array($login['status'],array('ok_form','ok_cookie'))) // ...or login_was_not_successful
   )
{
	header("Location: ".$script_abs_path."index.php");
	exit();
}


$mode = $_REQUEST['task'];
$data = $_REQUEST['data'];
$password = $_REQUEST['password'];
$password_ok = $password_lotterie;

if (($mode === "index") && ($password !== $password_ok))
{
	echo "<a href=\"index.php\">Torna indietro</a><br><br>\n";
	die("La password inserita non &egrave; corretta!<br>\n");
}


function show_header($titolo,$classe_body)
{
# dichiara variabili
extract(indici());

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 TRANSITIONAL//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>

<head>
  <title><?php echo $titolo; ?></title>
  <meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1">
  <meta name="GENERATOR" content="Kate">
  <style type="text/css">@import "<?php echo $filename_css ?>";</style>
</head>
<body <?php echo $classe_body; ?> >
<?php
}

// scegli la classe del body html
switch ($mode)
{
case 'set_nominativi':
case 'ticket_page':
case 'matrice_ticket':
	$classe = "";
	break;
default:
	$classe = "class=\"admin\"";
}

// nomi dei file
$id_questions = $data;
$basefile_questions = "lotteria_".sprintf("%03d",$id_questions).".txt";
$basefile_question_keys = "lotteria_".sprintf("%03d",$id_questions)."_keys_%03d.php";
$basefile_questions_ans = "lotteria_".sprintf("%03d",$id_questions)."_ans.php";	// nome del file con le risposte esatte
$file_questions = $questions_dir.$basefile_questions;	// nome del file di configurazione relativo a id_questions
$file_question_keys = $questions_dir.$basefile_question_keys;	// nome del generico file di chiavi
$file_log_questions = $questions_dir."lotteria_".sprintf("%03d",$id_questions)."_log.txt";	// nome del file di registrazione
$file_questions_ans = $root_path."custom/lotterie/lotteria_".sprintf("%03d",$id_questions)."_ans.php";	// nome del file con le risposte esatte
$file_template_ans = $root_path."custom/lotterie/lotteria_".sprintf("%03d",$id_questions)."_tpl_results.php";	// nome del template per i risultati
$file_template_form = $root_path."custom/lotterie/lotteria_".sprintf("%03d",$id_questions)."_tpl_form.php";	// nome del template per il form

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
	$num_key_chars = array();				// numero di chiavi per ciascun file
	foreach($lotteria['keyfiles'] as $keyfile_id => $keyfile_data)
	{
		$num_keys[$keyfile_id] = $keyfile_data[2];	// numero di chiavi per il file i-esimo
		$num_key_chars[$keyfile_id] = $keyfile_data[3];	// numero di caratteri per ciascuna chiave
	}
}

$tickets_per_row = 3;		// numero di biglietti da stampare per riga;
$tickets_per_column = 2;	// numero di biglietti da stampare per colonna;
$tickets_per_page = $tickets_per_row*$tickets_per_column;		// numero di biglietti da stampare per pagina


switch ($mode)
{
case 'index':
	show_header("Gestione lotterie/questionari",$classe);
	echo "<div class=\"titolo_tabella\">Lotterie disponibili:</div>";
	
	// individua i file di lotteria (se esiste solo lotteria_006.txt, $id vale 6
	$path_prefix = 	$root_path."custom/lotterie/";	// nome del file di configurazione relativo a id_questions
	$id = -1;
	if ($dh = opendir($path_prefix)) 
	{
		// crea la lista delle lotterie disponibili
		$list_questions = Array();
		while (($file = readdir($dh)) !== false) 
		{
		   if ( (substr($file,0,9) == "lotteria_") & (substr($file,12,4) == '.txt') & (substr($file,-4) == '.txt') )
			{
				// lotteria trovata
				$file_questions = $file;
				$id_file = substr($file_questions,9,3)+0;
				
				if ($id < $id_file) // prendi il massimo id
				{
					$id = $id_file;
				}
				
				$list_questions[$id_file] = $file_questions;
			}
		}
		closedir($dh);
		
		// visualizza in ordine di id
		for ($id_file = 1; $id_file <= count($list_questions); $id_file++)
		{
			$file_questions = $list_questions[$id_file];
			
			// carica file di configurazione della lotteria
			$lotteria = get_config_file($path_prefix.$file_questions);
			
			$lotteria_nome = $lotteria["Attributi"][0][0];
			
			echo "$id_file) <a href=\"manage_questions.php?task=edit&amp;data=$id_file\">$lotteria_nome</a> <br>\n";
		}
	}
	if ($id<0)
	{
		echo "Non ci sono lotterie n&eacute; questionari disponibili!<br>\n";
	}
	
	break;
case 'edit':
	show_header("Gestione lotterie/questionari",$classe);
	
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
			echo "<a href=\"manage_questions.php?task=init&amp;data=$id_questions\">Inizializza i $num_key_files file di chiavi (";
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
	echo "<a href=\"manage_questions.php?task=show_giocate&amp;data=$id_questions\">Visualizza giocate grezze</a><br>\n";
	echo "<a href=\"../questions.php?action=results&amp;id_questions=$id_questions&amp;debug=full\">Visualizza giocate ordinate</a>";
	
	// gestione giocate cartacee
	echo "<hr>";
	echo "<a href=\"manage_questions.php?task=manage_giocate_cartacee&amp;data=$id_questions\">Gestione giocate cartacee</a>";
	
	// gestione supplementare files di chiavi
	if ($lotteria_auth == 'key')
	{
		
		// stampa dei biglietti
		$keys = get_question_keys($id_questions);
		echo "<hr>";
		echo "Stampa biglietti:<br>\n";
		foreach (array_keys($keys) as $keyfile_id)
		{
			echo "&nbsp;&nbsp;&nbsp;Biglietti di tipo $keyfile_id (".$lotteria['keyfiles'][$keyfile_id][1]."):<br><br>\n";

			// per stampa della pagina con un sottoinsieme dei biglietti
			echo "<form action=\"manage_questions.php?task=ticket_page&amp;data=$id_questions\" method=\"post\">\n";
			echo "<select name=\"keyfile_select\">";
			echo "<option value=\"-1\" selected>&nbsp;</option>\n";
			for ($key_id = 0; $key_id < (count($keys[$keyfile_id])-1)/4; $key_id++)
			{
				$start = $key_id*$tickets_per_page+1;
				echo "<option value=\"$start\">".$start."-".($start+$tickets_per_page-1)."</option>\n";
			}
			echo "</select>";
			echo '<input type="submit" value="Visualizza la pagina da stampare" />';
			echo "<input type=\"hidden\" name=\"keyfile_id\" value=\"$keyfile_id\" />";
			echo "</form>";
			
			// esporta il file di chiavi (per "stampa unione")
			echo "<form action=\"manage_questions.php?task=matrice_ticket&amp;data=$id_questions\" method=\"post\">\n";
			echo '<input type="submit" value="Visualizza la matrice" />';
			echo "<input type=\"hidden\" name=\"keyfile_id\" value=\"$keyfile_id\" />";
			echo "</form>";
			
			// associazione nominativi alle chiavi
			echo "<form action=\"manage_questions.php?task=set_nominativi&amp;data=$id_questions\" method=\"post\">\n";
			echo '<input type="submit" value="Associazione nominativi ai biglietti" />';
			echo "<input type=\"hidden\" name=\"keyfile_id\" value=\"$keyfile_id\" />";
			echo "</form>";
			
			echo "<br>";
		}
		
	}
	
	// inserimento risposte esatte
	echo "<hr>";
	echo "<a href=\"manage_config_file.php?config_file=$basefile_questions_ans\">Inserimento risposte esatte</a>";
	
	break;
case 'ticket_page':
	show_header("Gestione lotterie/questionari",$classe);
	
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
			echo "<div style=\"text-align: center;\"><b>$lotteria_nome</b></div>\n";
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
case 'matrice_ticket':
	show_header("Gestione lotterie/questionari",$classe);
	
	$keyfile_id = $_REQUEST["keyfile_id"];
	$keys = get_question_keys($id_questions);
	
	echo "#Matrice biglietti ".$lotteria['keyfiles'][$keyfile_id][1].":<br>\n";
	foreach ($keys[$keyfile_id] as $id => $chiave)
	{
		echo ($id+1).";";
		echo $chiave[0].";";
		echo "<br>\n";
	}
	
	die();
	break;
case 'show_giocate':
	show_header("Gestione lotterie/questionari",$classe);
	
	echo "<div class=\"titolo_tabella\">Giocate &quot;$lotteria_nome&quot;</div><br>";
	
	$giocate = get_config_file($file_log_questions);
	show_giocate($giocate['default']);
	break;
case 'init':
	show_header("Gestione lotterie/questionari",$classe);
	
	// verifica che $id_questions sia un id relativo ad una lotteria o questionario valida
	if (!file_exists($file_questions))
	{
		die("La lotteria $id_questions non esiste!");
	}
	
	echo "Creo i file di chiavi...<br>\n";
	if (!create_key_files($id_questions,$num_key_files,$num_keys,$num_key_chars))
	{
		die("<br><br>Attenzione, i file di chiavi gia' esistono!");
	}
	break;
case 'set_nominativi':
	show_header("Gestione lotterie/questionari",$classe);
	
	$keyfile_id = $_REQUEST["keyfile_id"];	// id del file di chiavi in oggetto
	$key_offset = $_REQUEST["key_offset"];	// id del primo biglietto da visualizzare
	if (empty($key_offset))
	{
		$key_offset = 0;
	}
	$key_offset_old = $_REQUEST["key_offset_old"];	// id del primo biglietto della pagina salvata (stiamo arrivando qui da un submit)
	
	$key_max_num = 20;				// numero di chiavi gestite per pagina
	$keys = get_question_keys($id_questions);	// carica i file di chiavi associati alla lotteria

	// verifica se bisogna salvare dei dati precedenti
	if (strlen($key_offset_old.' ')>1)
	{
		for ($id=$key_offset_old;$id<$key_offset_old+$key_max_num;$id++)
		{
			$chiave_name=$_REQUEST["chiave_name_$id"];
			$chiave_responsabile=$_REQUEST["chiave_responsabile_$id"];
			$chiave_data=$_REQUEST["chiave_data_$id"];
			$chiave_nota=$_REQUEST["chiave_nota_$id"];
			
			// aggiorna la struttura $keys con i nuovi dati
			if (!empty($chiave_responsabile))
			{
				$keys[$keyfile_id][$id][1] = $chiave_responsabile;
			}
			if (!empty($chiave_name))
			{
				$keys[$keyfile_id][$id][2] = $chiave_name;
			}
			if (!empty($chiave_data))
			{
				// verifica formato data
				/*if (!preg_match('~^[0-9]{2}:[0-9]{2} [0-9]{2}/[0-9]{2}/[0-9]{4}$~',$chiave_data))
				{
					echo("Formato data errata: $chiave_data!<br>\n");
					die("Il formato giusto e': &quot;12:34 12/10/2006&quot; per indicare le 12:34 del 12 ottobre 2006.");
				}*/
				
				$keys[$keyfile_id][$id][3] = $chiave_data;
			}
			if (!empty($chiave_nota))
			{
				$keys[$keyfile_id][$id][4] = $chiave_nota;
			}
		}
		
		$filename = sprintf($file_question_keys,$keyfile_id);
		$result_save = save_config_file($filename,array('default' => $keys[$keyfile_id]));
	}
	
	
	
	echo "<div class=\"titolo_tabella\">Giocate &quot;$lotteria_nome&quot;";
	echo " - ";
	echo "Matrice biglietti &quot;".$lotteria['keyfiles'][$keyfile_id][1]."&quot;:</div>\n";
	
	if (($prev_offset = $key_offset-$key_max_num) >= 0)
	{
		echo "<a href=manage_questions.php?task=set_nominativi&amp;data=$id_questions&amp;keyfile_id=$keyfile_id&amp;key_offset=$prev_offset>";
		echo "Indietro</a>\n";
	}	
	if (($next_offset = $key_offset+$key_max_num) < $num_keys[$keyfile_id])
	{
		echo "<a href=manage_questions.php?task=set_nominativi&amp;data=$id_questions&amp;keyfile_id=$keyfile_id&amp;key_offset=$next_offset>";
		echo "Avanti</a><br><br>\n";
	}
	
	if ($result_save)
	{
		echo "Dati salvati : biglietti ".($key_offset_old+1)." - ".($key_offset_old+$key_max_num)."<br><br>\n";
	}
	
	echo "<form action=\"manage_questions.php?task=set_nominativi&amp;data=$id_questions\" method=\"post\">\n";
	for ($id = $key_offset;$id < $key_offset+$key_max_num; $id++)
	{
		$chiave_record = $keys[$keyfile_id][$id];
		$chiave_key = $chiave_record[0];
		$chiave_responsabile = $chiave_record[1];
		$chiave_name = $chiave_record[2];
		$chiave_data = $chiave_record[3];
		$chiave_nota = $chiave_record[4];
		
		
		echo "Biglietto ".($id+1);
		echo " (".$chiave_key.") : ";
		echo " consegnato a <input name=\"chiave_name_$id\" value=\"$chiave_name\">";
		echo " da <input name=\"chiave_responsabile_$id\" value=\"$chiave_responsabile\">";
		//echo " in data (formato: \"hh:mm gg/mm/aaaa\")<input name=\"chiave_data_$id\" value=\"$chiave_data\">";
		echo " in data (gg/mm/aaaa)<input name=\"chiave_data_$id\" value=\"$chiave_data\">";
		echo " (<input name=\"chiave_nota_$id\" value=\"$chiave_nota\" size=\"10\">)";
		echo "<br>\n";
	}
	echo "<input type=\"hidden\" name=\"keyfile_id\" value=\"$keyfile_id\">\n";
	echo "<input type=\"hidden\" name=\"key_offset\" value=\"$key_offset\">\n";
	echo "<input type=\"hidden\" name=\"key_offset_old\" value=\"$key_offset\">\n";
	echo '<input type="submit" value="Salva modifiche">';
	echo "\n</form>\n";
		
	break;
case 'manage_giocate_cartacee':
	if (!file_exists($file_template_form)) 
	{
		show_header("Gestione lotterie/questionari",$classe);
	
		// visualizza le domande (default)
		show_question_form($lotteria,"../questions.php","last_check",$id_questions,"","Conferma la giocata");
	}
	else
	{
		$admin_mode = true;
		include($file_template_form);
	}
	
	break;
default:
	show_header("Gestione lotterie/questionari",$classe);
	
	echo "<a href=\"articoli.php\">Torna indietro</a><br><br>\n";
	die("mode: \"".$mode."\", data: \"".$data."\"\n");
}


log_action($questions_dir,"Action: <$mode>, data: <$data>, ".date("l dS of F Y h:i:s A"));

?>

<?php
if (!empty($id_questions) & ($mode!=='edit'))
{
	echo "<hr><a href=\"manage_questions.php?task=edit&amp;data=$id_questions\" class=\"txt_link\">Torna alla pagina amministrativa della lotteria &quot;$lotteria_nome&quot;</a>";
}
echo $homepage_link;
?>

</body>
</html>
