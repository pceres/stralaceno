<?php

require_once('libreria.php');

# dichiara variabili
extract(indici());

/*
questa libreria esamina i cookies o i parametri http (eventualmente) inviati, e genera l'array $login con i campi
'username',	: login
'usergroups',	: lista dei gruppi di appartenenza (separati da virgola)
'status',		: stato del login: 'none','ok_form','ok_cookie','error_wrong_username','error_wrong_userpass','error_wrong_challenge','error_wrong_IP'
*/
require_once('login.php');

$action = $_REQUEST['action'];					// azione da eseguire
$action	= sanitize_user_input($action,'plain_text',array());	// verifica di sicurezza

$auth_token = $_REQUEST['auth_token'];					// chiave o nome da associare alla giocata
$auth_token = sanitize_user_input($auth_token,'plain_text',array());	// verifica di sicurezza

$id_questions = $_REQUEST['id_questions'];					// id della lotteria in oggetto
$id_questions = sanitize_user_input($id_questions,'plain_text',array());	// verifica di sicurezza

$data_giocata = $_REQUEST['data_giocata'];					// data che fa fede per la giocata
$data_giocata = sanitize_user_input($data_giocata,'plain_text',array());	// verifica di sicurezza


// verifica che $id_questions sia un id relativo ad una lotteria o questionario valida, altrimenti prendi l'ultimo
// sondaggio disponibile (quello con l'id piu' alto)
$file_questions = $root_path."custom/lotterie/lotteria_".sprintf("%03d",$id_questions).".txt";	// nome del file di configurazione relativo a id_questions
if (!file_exists($file_questions))
{
	// cerca i sondaggi/lotterie disponibili
	$file_questions_list = array();
	if (is_dir($questions_dir))
	{
		if ($dh = opendir($questions_dir)) 
		{
			while (($available_question_id = readdir($dh)) !== false) 
			{
				if ( (filetype($questions_dir . $available_question_id) == "file") and (preg_match('lotteria_([0-9]+)\.txt',$available_question_id)) )
				{
					// lotteria trovato:
					$available_question_id = preg_replace('~lotteria_([0-9]+)\.txt~',"\\1",$available_question_id);
					array_push($file_questions_list,$available_question_id+0);
				}
			}
			closedir($dh);
		}
	}
	
	$id_questions = max($file_questions_list);
}


$file_questions = $root_path."custom/lotterie/lotteria_".sprintf("%03d",$id_questions).".txt";	// nome del file di configurazione relativo a id_questions
$file_log_questions = $root_path."custom/lotterie/lotteria_".sprintf("%03d",$id_questions)."_log.txt";	// nome del file di registrazione
$file_questions_ans = $root_path."custom/lotterie/lotteria_".sprintf("%03d",$id_questions)."_ans.php";	// nome del file con le risposte esatte
$file_template_ans = $root_path."custom/lotterie/lotteria_".sprintf("%03d",$id_questions)."_tpl_results.php";	// nome del template per i risultati
$file_template_form = $root_path."custom/lotterie/lotteria_".sprintf("%03d",$id_questions)."_tpl_form.php";	// nome del template per il form


// carica file di configurazione della lotteria
$lotteria = get_config_file($file_questions);

$lotteria_nome = $lotteria["Attributi"][0][0];
$lotteria_stato = $lotteria["Attributi"][0][1];
$lotteria_auth = $lotteria["Attributi"][0][2];
$lotteria_inizio_giocate = $lotteria["Attributi"][0][3];
$lotteria_fine_giocate = $lotteria["Attributi"][0][4];
$lotteria_risultati = $lotteria["Attributi"][0][5];

// decodifica date
$v_start = parse_date($lotteria_inizio_giocate);
$v_end = parse_date($lotteria_fine_giocate);
$v_results = parse_date($lotteria_risultati);
$v_now = parse_date(date("H:i d/m/Y"));
// var_dump(Array($v_start[0],$v_end[0],$v_results[0],$v_now[0]));

// impedisci giocate dopo che queste sono chiuse (es. se si fa il reload dal browser di una pagina con action=save)
if ( ($action == "save") and ($v_now[0] > $v_results[0]) )
{
	$action = "results";
}

// smista l'azione di default a seconda della data attuale
if (empty($action))
{
	if (($v_now[0] > $v_results[0]) | (!empty($_REQUEST['debug'])) | (!empty($_REQUEST['filtro'])))
	{
		$action = "results"; 			// azione di default dopo la data di presentazione risultati (v_results)
	}
	else
	{
		if ($lotteria_auth !== 'no_auth')
		{
			$action = "auth"; 			// azione di default nel periodo di giocate aperte, con autorizzazione necessaria
		}
		else
		{
			$action = "fill"; 			// azione di default nel periodo di giocate aperte, con giocate libere
		}
	}
}

$question_tag_format = "question_%02d";

$titolo_pagina = "<div class=\"titolo_tabella\">$lotteria_nome</div>";




function check_answers($lotteria,$answers,&$results,&$msg)
{
	# dichiara variabili
	extract(indici());
	
	$domande = $lotteria["Domande"];
	$result = 1;
	foreach($answers as $id => $answer)
	{
		$results[$id] = 1;
		$msg[$id] = '';
		$domanda =  $domande[$id];
		if ($domanda[$indice_question_ripetibile] === "non_ripetibile")
		{
			foreach($answers as $id2 => $answer2)
			{
				if (($answer2 === $answer) && ($id2 != $id))
				{
					$result = 0;
					$results[$id] = 0;
					$msg[$id] = "&quot;$answer&quot; &egrave; ripetuto nella domanda #".($id2+1)."!";
				}
			}
		}
	}
	return $result;
}



function check_key($id_questions,$auth_token,$msg_auth_failed)
{
	// verifica che la chiave inserita sia corretta, ed individuane il gruppo
	$found_key = check_question_keys($id_questions,$auth_token);
	
	if (empty($found_key))
	{
		die($msg_auth_failed);
	}
	
	return $found_key;
	
} // end function check_key



function print_header($lotteria_nome) {

# dichiara variabili
extract(indici());

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 TRANSITIONAL//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>

<head>
  <title><?php echo $web_title ?> - <?php echo $lotteria_nome ?></title>
  <meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1">
  <meta name="GENERATOR" content="Kate">
  <meta name="description" content="<?php echo $lotteria_nome; ?>">
  <meta name="keywords" content="lotteria, questionario">
  <style type="text/css">@import "<?php echo $filename_css ?>";</style>
</head>

<body>

<?php
} // end function print_header


//echo "$action<br>";
if (!in_array($action,array('check_auth','results','fill')))
{
	print_header($lotteria_nome);
}


switch ($action)
{
case "auth":
	echo $titolo_pagina;
	
	// verifica che le giocate siano aperte e stampa il relativo messaggio
	if ($v_now[0] < $v_start[0])
	{
		echo($lotteria['msg_date'][0][0]);
	}
	elseif ($v_now[0] > $v_end[0])
	{
		echo($lotteria['msg_date'][0][2]);
	}
	else
	{
		echo($lotteria['msg_date'][0][1]);
	}
	echo "<br>\n";
	
	// visualizza form di autenticazione
	if ($lotteria_auth == "no_auth")
	{
		$auth_token = $login['username'];
	}
	elseif ($lotteria_auth == "key")
	{
		if (($v_now[0] >= $v_start[0]) & ($v_now[0] <= $v_end[0]))
		{
			echo "<hr>";
			echo "<form action=\"questions.php\" method=\"post\">\n";
			echo 'Inserisci il codice del biglietto per giocare:<input type="edit" name="secret_key"/><br>';
			echo '<input type="submit" value="vai avanti"/>';
			
			echo "<input type=\"hidden\" name=\"id_questions\" value=\"$id_questions\">\n";
			echo '<input type="hidden" name="action" value="check_auth"/>';
			
			echo "</form>\n";
		}
		
		if ($v_now[0] > $v_start[0])
		{
			echo "<hr>\n";
			echo "<a href=\"questions.php?action=results&amp;id_questions=$id_questions\">Visualizza le giocate</a>\n";
		}
		
		if (file_exists($file_template_form))
		{
			echo "<hr>\n";
			echo "<a href=\"custom/lotterie/lotteria_".sprintf("%03d",$id_questions)."_tpl_form.php?info_mode=1\">Fac-simile scheda per la giocata</a>";
		}
		break;
	}
	elseif ($lotteria_auth == "user")
	{
		$auth_token = $login['username'];
		$giocate = get_giocata($id_questions,$auth_token);
		
		if (count($giocate) > 0)
		{
			echo "Mi dispiace, &egrave consentita una sola giocata!<br>\n";
			if (count($giocate) == 1)
			{
				echo "C'&egrave gi&agrave ".count($giocate)." giocata per ".$login["username"].":<br><br>\n";
			}
			else
			{
				echo "Ci sono gi&agrave ".count($giocate)." giocate per ".$login["username"].":<br><br>\n";
			}
			show_giocate($giocate);
			die();
		}
		echo "Benvenuto ".$login["username"].", puoi giocare.<br><br>\n";
	}
case "check_auth":
	if ($action == "check_auth")
	{
		$secret_key = $_REQUEST['secret_key'];
		$auth_token = $secret_key;
		
		$giocate = get_giocata($id_questions,$auth_token);
		
		if (count($giocate) > 0)
		{
			echo "$titolo_pagina<br>\n";
			echo "Mi dispiace, &egrave consentita una sola giocata!<br>\n";
			if (count($giocate) == 1)
			{
				echo "C'&egrave gi&agrave ".count($giocate)." giocata per la chiave ".$secret_key.":<br><br>\n";
			}
			else
			{
				echo "Ci sono gi&agrave ".count($giocate)." giocate per la chiave ".$secret_key.":<br><br>\n";
			}
			show_giocate($giocate);
			die();
		}
		
		// verifica che la chiave inserita sia corretta, ed individuane il gruppo
		$found_key = check_key($id_questions,$auth_token,$lotteria['msg_auth_failed'][0][0]);
		$nominativo = $found_key[2][2];	// nome di chi ha ricevuto il biglietto, registrato a cura dell'amministratore
		
		
	} // if ($action == "check_auth")
case "fill":
	
	// verifica che le giocate siano aperte e stampa il relativo messaggio
	if ($v_now[0] < $v_start[0])
	{
		// prima dell'apertura giocate
		$messaggio_stato_sondaggio = $lotteria['msg_date'][0][0];
		$info_mode = 1;		// il sondaggio non e' ancora aperto: inibisci la possibilita' di giocare
		$flag_show_results = 0;	// non mostrare il link alle giocate
	}
	elseif ($v_now[0] > $v_end[0])
	{
		// dopo la chiusura delle giocate
		$messaggio_stato_sondaggio = $lotteria['msg_date'][0][2];
		$info_mode = 1;		// il sondaggio e' stato chiuso: inibisci la possibilita' di giocare
		$flag_show_results = 1;	// mostra il link alle giocate
	}
	else
	{
		// durante la fare di giocata abilitata
		$messaggio_stato_sondaggio = $lotteria['msg_date'][0][1];
		$flag_show_results = 1;	// mostra il link alle giocate
	}
	
	
	if (!file_exists($file_template_form)) 
	{
		
		if ($lotteria_auth !== 'no_auth')
		{
			echo "$titolo_pagina<br>\n";
		}
		else
		{
			echo "<br>\n";
		}
		
		
		// visualizza le domande (default)
		if ( ($action !== "fill") & ($lotteria_auth !== 'no_auth') )
		{
			echo "Benvenuto";
			if (!empty($nominativo))
			{
				echo " $nominativo";
			}
			echo ", puoi giocare.<br><br>\n";
		}
		
		show_question_form($lotteria,"questions.php","last_check",$id_questions,$auth_token,"Gioca");
	}
	else
	{
		include($file_template_form);
	}
	
	break;
case "last_check":
	
	if (!empty($data_giocata))
	{
		$admin_mode = true;
	}
	else
	{
		$admin_mode = false;
	}
	
	if ($lotteria_auth === 'key')
	{
		// verifica che la chiave inserita sia corretta, ed individuane il gruppo
		$found_key = check_question_keys($id_questions,$auth_token);
		$nominativo = $found_key[2][2];	// nome di chi ha ricevuto il biglietto, registrato a cura dell'amministratore
		
		if (empty($found_key))
		{
			echo "$titolo_pagina<br>\n";
			die($lotteria['msg_auth_failed'][0][0]);
		}
	}
	
	// ricava elenco delle risposte	
	$question_count = 0;
	$answers=array();
	foreach ($lotteria["Domande"] as $domanda)
	{
		$question_tag = sprintf($question_tag_format,$question_count);
		switch ($domanda[$indice_question_tipo])
		{
		case "free_number":
		case "free_string":
			$answer = sanitize_user_input($_REQUEST[$question_tag],'plain_text',array());	// verifica di sicurezza
			break;
		case "fixed":
			$answer = sanitize_user_input($_REQUEST[$question_tag],'plain_text',array());	// verifica di sicurezza
		}
		array_push($answers,$answer);
		$question_count++;
	}
	$results = array();
	$msg = array();
	$result = check_answers($lotteria,$answers,$results,$msg);
	
	echo "$titolo_pagina<br>\n";
	if ($result)
	{
		echo "Confermi le tue scelte?<br>\n";
		echo "Dopo aver confermato non sar&agrave; possibile tornare indietro.<br><br>\n";
		$conferma_disabled = "";
		$modifica_disabled = "";
	}
	else
	{
		echo "Ci sono errori nelle risposte!<br><br>\n";
		$conferma_disabled = "disabled";
		$modifica_disabled = "";
	}

	echo "<form action=\"questions.php\" method=\"post\">\n";
	$question_count = 0;
	foreach ($lotteria["Domande"] as $domanda)
	{
		echo ($question_count+1).") $domanda[$indice_question_caption]: \n";
		
		switch ($domanda[$indice_question_tipo])
		{
		case "free_number":
		case "free_string":
			$question_tag = sprintf($question_tag_format,$question_count); // question_xx
			$answer = sanitize_user_input($_REQUEST[$question_tag],'plain_text',array());	// verifica di sicurezza
			if ($results[$question_tag] == 0)
			{
				$messaggio = "&quot;<span style=\"color: red;\">".$msg[$question_count]."</span>&quot;";
			}
			else
			{
				$messaggio = "";
			}
			echo(" $answer $messaggio\n");
			echo "<input type=\"hidden\" name=\"$question_tag\" value=\"$answer\">";
			break;
		case "fixed":
			$question_tag = sprintf($question_tag_format,$question_count);
			$answer = sanitize_user_input($_REQUEST[$question_tag],'plain_text',array());	// verifica di sicurezza
			
			if ($results[$question_count] == 0)
			{
				$messaggio = "<span style=\"color: red;\">".$msg[$question_count]."</span>";
			}
			else
			{
				$messaggio = "";
			}
			echo(" $answer $messaggio\n");
			
			// determina le varie risposte possibili
			$gruppi_domande = explode(",",$domanda[$indice_question_gruppo]);
			$voci = array();
			foreach($gruppi_domande as $gruppo_domande)
			{
				$voci = array_merge($lotteria[$gruppo_domande],$voci);
			}
			echo "<input type=\"hidden\" name=\"$question_tag\" value=\"$answer\">";
			break;
		}
		echo "<br>\n\n";
		
		$question_count++;
	}
	
	echo "<input type=\"hidden\" name=\"id_questions\" value=\"$id_questions\">\n";
	echo "<input type=\"hidden\" name=\"action\" value=\"save\">\n";
	echo "<input type=\"hidden\" name=\"auth_token\" value=\"$auth_token\">\n";
	if ($admin_mode)
	{
		echo "<input type=\"hidden\" name=\"data_giocata\" value=\"$data_giocata\">\n";
	}
	echo "<input type=\"submit\" value=\"modifica\" $modifica_disabled OnClick='form[\"action\"].value=\"fill\";' />";
	echo "<input type=\"submit\" value=\"conferma\" $conferma_disabled />";
	
	echo "</form>\n";
	break;
case "save":
	if ($lotteria_auth === 'key')
	{
		// verifica che la chiave sia corretta
		$found_key = check_key($id_questions,$auth_token,$lotteria['msg_auth_failed'][0][0]);
		$nominativo = $found_key[2][2];	// nome di chi ha ricevuto il biglietto, registrato a cura dell'amministratore
	}
	
	// verifica che non si giochi piu' volte la stessa giocata con lo stesso auth_token	
	$giocata_ripetuta = 0;
	$giocate = get_giocata($id_questions,$auth_token);
	
	if (($lotteria_auth !== "no_auth") && (count($giocate)>0))
	//if (count($giocate)>0)
	{
		$giocata_ripetuta = 1;
	}
	
	if (!empty($data_giocata))
	{
		$admin_mode = true;
	}
	else
	{
		$admin_mode = false;
	}
	
	$domande = $lotteria["Domande"];
	$string_answers = '';
	$question_count = 0;
	foreach ($domande as $id => $domanda)
	{
		$question_tag = sprintf($question_tag_format,$question_count);
		
		$answer = sanitize_user_input($_REQUEST[$question_tag],'plain_text',array());	// verifica di sicurezza
		$string_answers .= $answer;
		if ($id+1 < count($domande))
		{
			$string_answers .= ",";
		}
		
		$question_count++;
	}
	
	// gestione data della giocata: per la giocata online si usa l'istante della giocata, per la giocata cartacea inserita dall'amministratore
	// si usa $_REQUEST["data_giocata"] passata dall'interfaccia amministrativa
	if (!$admin_mode)
	{
		$data_giocata = date("H:i d/m/Y");
	}
	else
	{
		// verifica formato data
		if (!preg_match('~^[0-9]{2}:[0-9]{2} [0-9]{2}/[0-9]{2}/[0-9]{4}$~',$data_giocata))
		{
			echo("Formato data errata: $data_giocata!<br>\n");
			die("Il formato giusto e': &quot;12:34 12/10/2006&quot; per indicare le 12:34 del 12 ottobre 2006.");
		}
	}

	
	$log = $string_answers."::".time()."::".$data_giocata."::".$auth_token."\n";
	
	$bulk = get_config_file($file_log_questions);
	$ultima_giocata = $bulk['default'][count($bulk['default'])-1];
	$giocata_da_salvare = explode("::",$log);

	if (($ultima_giocata[0]==$giocata_da_salvare[0]) && ($giocata_da_salvare[1]-$ultima_giocata[1] < 3600*2) && ($ultima_giocata[3]-$giocata_da_salvare[3] == 0))
	{
		$giocata_ripetuta = 1;
	}
	
	if ($giocata_ripetuta)
	{
		echo "$titolo_pagina<br>\n";
		echo("ATTENZIONE! La giocata &egrave; gi&agrave; stata registrata:<br><br>\n");
		show_giocate($giocate);
		
		echo "<hr>\n";
		echo "<a href=\"questions.php?action=results&amp;id_questions=$id_questions\">Visualizza le giocate</a>\n";
	}
	else
	{
		//registra la giocata
		$cf = fopen($file_log_questions, 'a');
		if (!$cf)
		{
			die("Il file $file_log_questions e' probabilmente protetto in scrittura. Contattare l'amministratore.");
		}
		fwrite($cf, $log);
		fclose($cf);
		
		echo "$titolo_pagina<br>\n";
		echo "La giocata ";
		if ($admin_mode)
		{
			echo "cartacea";
		}
		else
		{
			echo "online";
		}
		echo " &egrave; stata registrata:<br><br>\n";
		show_giocate(array($giocata_da_salvare));
		
		echo "<hr>\n";
		echo "<a href=\"questions.php?action=results&amp;id_questions=$id_questions\">Visualizza le giocate</a>\n";
	}
	
	break;
case "results":
	$temp_debug = 0; // enable this for verbose output that enables debugging
	
	$id_regola_gruppo = 0;	// gruppo cui contribuisce la regola a determinare il punteggio
	$id_regola_tipo = 1;	// tipo di regola
	$id_regola_caption = 2;	// testo che compare nella colonna corrispondente
	$id_regola_data = 3;	// primo campo di dati della regola
	
	// criteri per la classifica
	$criteri = $lotteria["classifica"];
	
	// elenco dei criteri di ordinamento
	$lista_criteri = Array();
	foreach ($criteri as $indice_criterio => $criterio)
	{
		$nome_criterio = $criterio[1];
		$lista_criteri[$nome_criterio] = $indice_criterio;
	}
	
	
	// carica giocate
	$giocate = get_config_file($file_log_questions);
	$giocate = $giocate['default'];
	$numero_risposte_per_giocata = count(explode(',',$giocate[0][0])); // numero di risposte salvate per giocata (primo campo)
	
	// carica risposte corrette
	$soluz_array = get_config_file($file_questions_ans);
	if (empty($soluz_array['default']))
	{
		echo "$titolo_pagina<br>\n";
		die("Non &egrave; ancora possibile visualizzare la classifica! Contattare l'amministratore.");
	}
	else
	{
		$soluz = array();
		foreach ($soluz_array['default'] as $id => $soluz_item)
		{
			$soluz[$id] = $soluz_item[0];
		}
	}
	
	// effettua i calcoli preliminari per velocizzare la classifica
	calcoli_preliminari_criteri($bulk_punteggi, $gruppi_regole, $header_punteggi, $header_punteggi_output, $init_punteggi, $init_punteggi_output, $criteri, $soluz_array, $soluz, $lotteria);
	
	
	// titolo delle colonne in $elenco_giocate
	$header = array("id","giocata","time","data giocata","auth_token","cedente","giocatore","data cessione","id tipo giocata","tipo giocata");
	$header = array_merge($header,$header_punteggi,$header_punteggi_output);
	
	// crea $elenco_giocate
	$count = 0;
	$elenco_giocate = array($header);
	foreach ($giocate as $indice_giocata => $giocata)
	{
		$count++;	// incrementale della giocata
		
		// dati relativi alla chiave usata per la giocata
		$auth_token_i = $giocata[3];	// chiave usata per effettuare la giocata
		$found_key = check_question_keys($id_questions,$auth_token_i);
		
		$cedente_biglietto = $found_key[2][1];		// nome di chi ha ceduto il biglietto, registrato a cura dell'amministratore
		if (empty($cedente_biglietto)) $cedente_biglietto="-";
		
		$nominativo_biglietto = $found_key[2][2];	// nome di chi ha ricevuto il biglietto, registrato a cura dell'amministratore
		if (empty($nominativo_biglietto)) $nominativo_biglietto="-";
		
		$data_biglietto = $found_key[2][3];		// data di cessione del biglietto, registrato a cura dell'amministratore
		if (empty($data_biglietto)) $data_biglietto="-";
		
		// dati relativi al tipo di biglietto
		$id_tipo_biglietto = $found_key[0];		// id del tipo del biglietto, registrato a cura dell'amministratore
		$tipo_biglietto = $lotteria['keyfiles'][$found_key[0]][1]; // descrizione del tipo del biglietto
		
		// giocata
		$giocata_risposte = $giocata[0];
		$giocata_array = explode(',',$giocata_risposte);
		
		if ($temp_debug) { // !!!
		echo "<hr>Giocata ".($indice_giocata+1).":<br>";
		print_r($giocata_array);
		echo "<br><br>\n";
		echo "Risposta esatta:<br>";
		print_r($soluz);
		echo "<br>\n";}
		
		$dati_esterni_per_giocata = array($cedente_biglietto, $nominativo_biglietto, $data_biglietto, $id_tipo_biglietto, $tipo_biglietto);

		// calcola i campi dei punteggi per la singola giocata
		$punteggi = $init_punteggi;			// valori di default
		$punteggi_output = $init_punteggi_output;	// valori di default
		foreach($criteri as $id => $criterio)
		{
			$bulk = $bulk_punteggi[$id];	// dati relativi alla regola $id
			if ($temp_debug) echo "<br>Criterio $id:<br>"; // !!!
			
			// calcola il punteggio per la regola $id-esima
			$punteggio = 0;
			$punteggio_output = '';
			calcola_punteggio($temp_debug,$punteggio,$punteggio_output,$gruppo_regole,$giocata,$dati_esterni_per_giocata,$giocata_array,$soluz,$criterio,$bulk);
			
			$punteggi[$gruppo_regole] += $punteggio;
			
			$punteggi_output[$gruppo_regole] .= $punteggio_output;
			if ($punteggi_output[$gruppo_regole][0] == ',')
			{
				$punteggi_output[$gruppo_regole] = substr($punteggi_output[$gruppo_regole],1);
			}
			
			if ($temp_debug) // !!!
			{
				echo "Punteggio $gruppo_regole : $punteggio<br>\n";
				echo "Punteggio_output $gruppo_regole : $punteggio_output<br>\n";
			}
		}
		
		if ($temp_debug) {echo "<br><br>";} // !!!
		
		// crea il record per $elenco_giocate
		//$dati_giocata = array_merge($count, $giocata, $cedente_biglietto, $nominativo_biglietto, $data_biglietto, $id_tipo_biglietto, $tipo_biglietto, $punteggi, $punteggi_output);
		$dati_giocata = array_merge(Array($count), $giocata, $dati_esterni_per_giocata , $punteggi, $punteggi_output);
		array_push($elenco_giocate,$dati_giocata);
	}
	
	if (!empty($_REQUEST['filtro']))
	{
		$lista_regola_campo = array(8); // id_tipo_giocata
		//$lista_regola_valore = array(0);// a pagamento
		$lista_regola_valore = array($_REQUEST['filtro']-1);
		$elenco_giocate = filtra_archivio($elenco_giocate,$lista_regola_campo,$lista_regola_valore);
	}
	
	// indici dei punteggi
	$lista_indici_punteggi = array();
	foreach ($header_punteggi as $header_punteggio)
	{
		array_push($lista_indici_punteggi,array_search($header_punteggio,$header));
	}
	
	if ($temp_debug) {print_r($header);echo "<br>Lista indici punteggi:<br>"; // !!!
	print_r($lista_indici_punteggi);echo "<br><br>";}
	
	// indici dei punteggi
	$lista_indici_punteggi_output = array();
	foreach ($header_punteggi_output as $header_punteggio_output)
	{
		if (($_REQUEST['debug'] === "full") || (!empty($header_punteggio_output)))
		{
			array_push($lista_indici_punteggi_output,array_search($header_punteggio_output,$header));
		}
	}
	if ($temp_debug) {print_r($lista_indici_punteggi_output);echo "<br>";} // !!!
	
	// ordina su tutte le regole di classificazione
	if ($temp_debug)
	{
		echo("elenco_giocate pre ordinamento:<br>");var_dump($elenco_giocate);echo("<br><br>");
		echo("lista_indici_punteggi colonne con i punti su cui ordinare:<br>");var_dump($lista_indici_punteggi);echo("<br><br>");
	}
	$elenco_giocate = ordina_archivio($elenco_giocate,$lista_indici_punteggi);
	if ($temp_debug) {echo("elenco_giocate post ordinamento:<br>");var_dump($elenco_giocate);echo("<br><br>");}
	
	if ($_REQUEST['debug'] === "full")
	{
		$mask = array_keys($header);	// tutte le colonne
	}
	else
	{
		$mask = array(0,6,1,9);
		$mask = array_merge($mask,$lista_indici_punteggi_output);
	}
	
	if (!file_exists($file_template_ans))
	{
		// visualizzazione di default dei risultati
		print_header($lotteria_nome);
		echo "$titolo_pagina<br>\n";
		show_table($elenco_giocate,$mask,'tabella',1,12,1); # tabella in una colonna, font 12, con note
	}
	else
	{
		// visualizzazione personalizzata dei risultati
		include($file_template_ans);
	}
	
	break;
default:
	die("Azione \"$action\" sconosciuta!");
	
} // end switch($action)


echo $homepage_link;

# logga il contatto
$counter = count_page("questions",array("COUNT"=>1,"LOG"=>1),$filedir_counter); # abilita il contatore, senza visualizzare le cifre, e fai il log

?>
</body></html>
<?php



function get_vettore_squadre_vincenti(&$vettore_risposte_esatte,$soluz,$livello_eliminatorie,$temp_debug) {

$counter_squadre=0;
$vettore_risposte_esatte = array();

//print_r($soluz);echo "(soluz)<br>";
foreach ($soluz as $id_soluz => $soluz_item)
{
	//echo "$soluz_item<br>";
	if (!array_key_exists($soluz_item,$vettore_risposte_esatte))
	{
		if ($temp_debug)
		{
			echo "-> $soluz_item<br>";
		}
		$vettore_risposte_esatte[$soluz_item] = 1;
		$counter_squadre++;
	}
	else
	{
		$vettore_risposte_esatte[$soluz_item]++;
	}
}
//print_r($vettore_risposte_esatte);echo "<br>";
$counter_ok = pow(2,$livello_eliminatorie-1);	// numero di squadre presenti nella giocata
// echo $counter_squadre.",".$counter_ok;
if ($counter_squadre != $counter_ok)
{
	if ($temp_debug)
	{
		echo("<br><br><div class=\"titolo_tabella\">Le risposte esatte non sono congruenti con lo schema ad eliminatoria ($counter_squadre individuate invece di $counter_ok)!</div><br><br>");
	}
}

$result = ($counter_squadre == $counter_ok);
if ($temp_debug)
{
	echo("$counter_squadre - $counter_ok - $result<br>");
}

return $result;

} // end function get_vettore_squadre_vincenti



function calcoli_preliminari_criteri(&$bulk_punteggi, &$gruppi_regole, &$header_punteggi, &$header_punteggi_output, &$init_punteggi, &$init_punteggi_output, $criteri, $soluz_array, $soluz, &$lotteria) {

# dichiara variabili
extract(indici());

$temp_debug_calcoli_preliminari_criteri = 0;

// indici campi al'interno di $criterio
$id_regola_gruppo = 0;	// gruppo cui contribuisce la regola a determinare il punteggio
$id_regola_tipo = 1;	// tipo di regola
$id_regola_caption = 2;	// testo che compare nella colonna corrispondente
$id_regola_data = 3;	// primo campo di dati della regola

// esegui calcoli preliminari sulle regole
$bulk_punteggi = array();
$gruppi_regole = array();
$header_punteggi_output = array();
foreach($criteri as $id => $criterio)
{
	// individua i gruppi di regole
	$gruppo_regola = $criterio[$id_regola_gruppo];
	array_push($gruppi_regole,$gruppo_regola);
	
	
	$ks = $criterio[$id_regola_caption];
	$header_punteggi_output[$gruppo_regola] = $ks;
	//array_push($header_punteggi_output,$ks);
	
	// esegui qui i calcoli che si possono fare una sola volta
	switch ($criterio[$id_regola_tipo])
	{
	case "distanza":
		$sort_mask = explode(',',$criterio[$id_regola_data+0]);
		$dist_bkp = explode(',',$criterio[$id_regola_data+1]);
		$dist_weight = explode(',',$criterio[$id_regola_data+2]);
		$question_weight = explode(',',$criterio[$id_regola_data+3]);
		
		$sort_needed = (strlen(array_search(1,$sort_mask).'a') > 1);
		
		$bulk_punteggi[$id] = array($sort_mask,$dist_bkp,$dist_weight,$question_weight,$sort_needed);
		break;
	case "data_giocata":
		$date_mask 	= $criterio[$id_regola_data+0];	// filtro su ora e minuti ai fini dell'ordinamento
		$date_min 	= $criterio[$id_regola_data+1];	// data (hh:mm gg/mm/aaaa) minima ai fini dell'ordinamento
		$date_max 	= $criterio[$id_regola_data+2];	// data (hh:mm gg/mm/aaaa) massima ai fini dell'ordinamento
		
		$bulk_punteggi[$id] = array($date_mask,$date_min,$date_max);
		break;
	case "posizione_esatte":
		$sort_mask = explode(',',$criterio[$id_regola_data+0]);
		$pos_weight = explode(',',$criterio[$id_regola_data+1]);
		
		$sort_needed = (strlen(array_search(1,$sort_mask).'a') > 1);
		
		$bulk_punteggi[$id] = array($sort_mask,$pos_weight,$sort_needed);
		break;
	case "esatte_per_gruppi":
		$pos_groups = explode(',',$criterio[$id_regola_data+0]);
		$modalita = $criterio[$id_regola_data+1];
		$question_weight = explode(',',$criterio[$id_regola_data+2]);
		
		$gruppi_risposte_esatte = array();
		foreach ($soluz as $id_soluz => $soluz_item)
		{
			$gruppo_risposta = $pos_groups[$id_soluz];
			if (array_key_exists($gruppo_risposta,$gruppi_risposte_esatte))
			{
				array_push($gruppi_risposte_esatte[$gruppo_risposta],$soluz_item);
			}
			else
			{
				$gruppi_risposte_esatte[$gruppo_risposta] = array($soluz_item);
			}
		}
		
		$bulk_punteggi[$id] = array($pos_groups,$question_weight,$modalita,$gruppi_risposte_esatte);
		break;
	case "eliminatorie":
		$livello_eliminatorie = $criterio[$id_regola_data+0];
		$maschera_risposte = explode(',',$criterio[$id_regola_data+1]);	// elenco domande che interessano questa regola, vincitore per ultimo
		
		$vettore_risposte_esatte = array(); // viene passato per indirizzo, bisogna inizializzarlo qui
		$soluz_ok = get_vettore_squadre_vincenti($vettore_risposte_esatte,$soluz,$livello_eliminatorie,$temp_debug_calcoli_preliminari_criteri);
		if (!$soluz_ok)
		{
			if ($temp_debug_calcoli_preliminari_criteri)
			{
				echo("Le risposte esatte non sono congruenti con lo schema ad eliminatoria!");
			}
		}
		
		$gruppi_risposte_possibili = array();
		foreach ($lotteria['Domande'] as $domanda_item)
		{
			$gruppi = explode(',',$domanda_item[$indice_question_gruppo]);
			$gruppi_risposte_possibili = array_merge($gruppi_risposte_possibili,$gruppi);
		}
		$gruppi_risposte_possibili = array_unique($gruppi_risposte_possibili);
		$risposte_possibili = array();
		foreach ($gruppi_risposte_possibili as $gruppo_risposte)
		{
			$risposte0 = $lotteria[$gruppo_risposte];
			
			$risposte = array();
			foreach ($risposte0 as $risposta)
			{
				array_push($risposte,$risposta[0]);
			}
			
			$risposte_possibili = array_merge($risposte_possibili,$risposte);
		}
		$risposte_possibili = array_unique($risposte_possibili);
//		print_r($risposte_possibili);
		
		// squadre associate ad ogni girone
		$list_gruppi = Array();
		for ($i = 0; $i < $livello_eliminatorie; $i++)
		{
			$temp = pow(2,($livello_eliminatorie-$i-1));
			for ($i2 = 0; $i2 < $temp; $i2++)
			{
				array_push($list_gruppi,(string)($livello_eliminatorie-$i));
			}
		}
		
		$risposte_equivalenti = Array();
		foreach ($maschera_risposte as $id_temp => $posiz_domanda)
		{
			$gruppo = $list_gruppi[$id_temp];
			$squadra = $soluz[$posiz_domanda];
			if (!array_key_exists($gruppo,$risposte_equivalenti))
			{
				$risposte_equivalenti[$gruppo] = Array($squadra);
			}
			else
			{
				array_push($risposte_equivalenti[$gruppo],$squadra);
			}
		}
		
		$bulk_punteggi[$id] = array($livello_eliminatorie,$vettore_risposte_esatte,$risposte_possibili,$maschera_risposte,$risposte_equivalenti);
		break;

	case "punteggi_specifici":
		$equivalenza_risposte_0 = $soluz_array['equivalenza_risposte'];
		$punteggio_risposte = $soluz_array['punteggio_risposte'];
		
		// individua risposte interessate dal criterio
		$lista_risposte_da_pesare = Array();
		$equivalenza_risposte = array();
		$matrice_punteggi_gruppo = Array();
		foreach ($equivalenza_risposte_0 as $item)
		{
			$item_gruppo 	= $item[0]; // valore numerico associato al gruppo di risposte
			$item_caption 	= $item[1]; // descrizione associata al gruppo di risposte
			$item_answers 	= explode(',',$item[2]); // elenco risposte associate al gruppo
			
			$equivalenza_risposte[$item_gruppo] = $item_answers;
			
			$matrice_punteggi_gruppo[$item_gruppo] = Array();
			foreach($item_answers as $indice_risposta)
			{
				$lista_risposte_da_pesare[$indice_risposta] = $item_gruppo;
			}
		}
		
		// individua punteggi associati alle diverse risposte per ciascun gruppo
		foreach($punteggio_risposte as $item)
		{
			$item_risposta 	= $item[0]; 		// testo della risposta
			$item_punti 	= explode(';',$item[1]); 	// punti associati alla risposta per ciascun gruppo di risposte (indicato in equivalenza_risposte)
			
			foreach($item_punti as $punteggio_0)
			{
				$punteggio = explode(',',$punteggio_0);
				$item_punti_gruppo = $punteggio[0];
				$item_punti_punti  = $punteggio[1];
				
				$matrice_punteggi_gruppo[$item_punti_gruppo][$item_risposta] = $item_punti_punti;
			}
		}
		
		// matrice dei punteggi associati a ciascuna valore di risposta per ciascuna risposta
		$matrice_punteggi = Array();
		foreach($lista_risposte_da_pesare as $indice_risposta => $gruppo_risposta)
		{
			$matrice_punteggi[$indice_risposta] = $matrice_punteggi_gruppo[$gruppo_risposta];
		}
/*		print_r($matrice_punteggi);
		echo "<br><br><br>";
		die('todo');*/
		
		$bulk_punteggi[$id] = array($matrice_punteggi,$matrice_punteggi_gruppo,$lista_risposte_da_pesare);
		break;

	default:
		$bulk_punteggi[$id] = array();
	}
}
$gruppi_regole = array_unique($gruppi_regole); // elenco (senza ripetizione) dei gruppi di regole

// crea gli header di $elenco_giocate
$header_punteggi = array();
$init_punteggi = array();
$init_punteggi_output = array();
foreach($gruppi_regole as $id => $gruppo_regole)
{
	// crea gli header per le colonne dei punteggi in $elenco_giocate
	$ks = "punteggio".$gruppo_regole;
	array_push($header_punteggi,$ks);
	
	$init_punteggi[$gruppo_regole] = 0;
	$init_punteggi_output[$gruppo_regole] = '';
}

} // end function calcoli_preliminari_criteri



function calcola_punteggio($temp_debug,&$punteggio,&$punteggio_output,&$gruppo_regole,&$giocata,$dati_esterni_per_giocata,$giocata_array,$soluz,$criterio,$bulk) {

// indici campi al'interno di $criterio
$id_regola_gruppo = 0;	// gruppo cui contribuisce la regola a determinare il punteggio
$id_regola_tipo = 1;	// tipo di regola
$id_regola_caption = 2;	// testo che compare nella colonna corrispondente
$id_regola_data = 3;	// primo campo di dati della regola


$punteggio = 0;
$punteggio_output = '';

$gruppo_regole = $criterio[$id_regola_gruppo];
$tipo_regola = $criterio[$id_regola_tipo];

if ($temp_debug) {echo "Regola: $tipo_regola<br>";}

switch ($tipo_regola)
{
case "distanza":
	$sort_mask = $bulk[0];		// bitmask delle risposte da ordinare
	$dist_bkp = $bulk[1];		// breakpoint
	$dist_weight = $bulk[2];	// peso in corrispondenza del breakpoint
	$question_weight = $bulk[3];	// peso per ciascuna domanda
	$sort_needed = $bulk[4];	// flag che indica la necessita' di ordinare le risposte
		
	// preordinamento se necessario
	if ($sort_needed)
	{
		sort_masked($giocata_array,$sort_mask,SORT_ASC);
		sort_masked($soluz,$sort_mask,SORT_ASC);
	}
	
	// calcola vettore punti $punti_array
	$punti_array = array();
	foreach ($giocata_array as $id_question => $giocata_item)
	{
		$posiz = array_search($giocata_item,$soluz);
		if (strlen($posiz.' ')>1) // se la giocata e' una delle risposte esatte...
		{
			$err = $id_question-$posiz;  // calcola la distanza dalla posizione in cui sarebbe stata esatta
			if ($temp_debug){echo "$giocata_item) err:$err ($id_question,$posiz)<br>";}
		}
		else
		{
			$err = '-';
		}
		
		if (is_numeric($err))
		{
			if ($err < min($dist_bkp))
			{
				if ($temp_debug) echo("Saturo inferiormente ($err:$giocata_item,$posiz)<br>"); // !!!
				$err=min($dist_bkp);
			}
			if ($err > max($dist_bkp))
			{
				if ($temp_debug) echo("Saturo superiormente ($err:$giocata_item,$posiz)<br>"); // !!!
				$err=max($dist_bkp);
			}
		}
		
		// crea voto corrispondente al punteggio
		$indice=array_search($err,$dist_bkp);
		if (array_key_exists($indice,$dist_weight))
		{
			$voto = $dist_weight[$indice];
		}
		else
		{
			$voto = 0;
		}
		$voto_pesato = $voto*$question_weight[$id_question];
		
		if ($temp_debug) // !!!
		{
			print_r($dist_bkp);
			print_r(($dist_weight));
			echo "$err,$voto,$voto_pesato (".$dist_weight[$voto].",".$question_weight[$id_question].")<br>";
		}
		
		$punti_array[$id_question] = $voto_pesato;
		
		if ($voto_pesato != 0)
		{
			$punteggio_output .= ','.($id_question+1);
			
			//if ($temp_debug) // !!!
			if ($voto_pesato <> 1)
			{
				$punteggio_output .= '('.$voto_pesato.')';
			}
			
		}
	}
	
	if ($temp_debug) // !!!
	{
		echo "punti_array :<br>";print_r($punti_array);echo "<br>\n";
	}
	
	$punteggio = -array_sum($punti_array)+0;
	
	break;
case "data_giocata":
	$date_mask = $bulk[0]; 	// maschera su minuti e ora, per indicare se ignorare o meno minuti ed ora (es. 00:00 ??/??/????)
	$date_min = $bulk[1]; 	// data (hh:mm gg/mm/aaaa) minima di giocata
	$date_max = $bulk[2]; 	// data (hh:mm gg/mm/aaaa) massima di giocata
	
	// applica maschera all'istante di giocata:
	$str_data_giocata_in = $giocata[2];
	$str_data_giocata = $giocata[2];
	for ($i = 0; $i < strlen($str_data_giocata_in); $i++)
	{
		if (($i < strlen($date_mask)) && ($date_mask[$i] !== '?'))
		{
			$str_data_giocata[$i] = $date_mask[$i];
		}
		else
		{
			$str_data_giocata[$i] = $str_data_giocata_in[$i];
		}
	}
	
	$tempi_giocata = parse_date($str_data_giocata);// stringa corrispondente alla data che fa fede per la giocata
	$time_giocata = $tempi_giocata[0];	// valore numerico corrispondente
	
	
	// saturazione data minima e massima di giocata
	$tempi_giocata_min = parse_date($date_min);
	$time_giocata_min = $tempi_giocata_min[0];	// valore numerico corrispondente
	if ($time_giocata_min > $time_giocata) {$time_giocata = $time_giocata_min;}
	
	$tempi_giocata_max = parse_date($date_max);
	$time_giocata_max = $tempi_giocata_max[0];	// valore numerico corrispondente
	if ($time_giocata_max < $time_giocata) {$time_giocata = $time_giocata_max;}
	
	
	// calcolo punteggio
	$time_0 = 0;
	$delta = ($giocata[1]-($time_giocata-$time_0[0]));	// differenza tra l'istante di giocata e la data che fa fede per la classifica
	
	$punteggio = $time_giocata+0;
	$punteggio_output = $giocata[2]." ($delta)";
	
	break;
case "posizione_esatte":
	$sort_mask = $bulk[0];		// bitmask delle risposte da ordinare
	$pos_weight = $bulk[1];		// gerarchia delle risposte
	$sort_needed = $bulk[2];	// flag che indica la necessita' di ordinare le risposte
	
	// preordinamento se necessario
	if ($sort_needed)
	{
		sort_masked($giocata_array,$sort_mask,SORT_ASC);
		sort_masked($soluz,$sort_mask,SORT_ASC);
	}
if ($temp_debug){			print_r($giocata_array);echo "<br>\n";}
	
	// crea vettore corrispondente alle risposte indovinate
	$risposte_esatte = array();
	foreach ($giocata_array as $id_question => $giocata_item)
	{
		if ($giocata_item == $soluz[$id_question])
		{
			$peso = $pos_weight[$id_question];
			$punteggio_output .= ','.($id_question+1);
			$punteggio_output .= "($peso)";
		}
		else
		{
			$peso = max($pos_weight)+1;
		}
		$risposte_esatte[$id_question] = $peso;
	}
if ($temp_debug) {				print_r($risposte_esatte);echo "<br>\n";}
	
	// determina punteggio
	$punteggio = 0;
	$n_questions = count($pos_weight);
	for ($id_risposta = 0; $id_risposta < $n_questions; $id_risposta++)
	{
		$voto = array_shift($risposte_esatte);
		$punteggio = $punteggio*$n_questions+$voto;
	}
	
	break;
case "esatte_per_gruppi":

	$pos_groups = $bulk[0];			// gruppo cui appartiene ciascuna risposta
	$question_weight = $bulk[1];		// peso per ciascuna risposta esatta
	$modalita = $bulk[2];			// modalita' da applicare alle risposte esatte
	$gruppi_risposte_esatte = $bulk[3];	// risposte esatte per ciascun gruppo
	
if ($temp_debug) {echo "gruppi_risposte_esatte:"; print_r($gruppi_risposte_esatte); }
	
	// crea vettore corrispondente alle risposte indovinate
	$risposte_esatte = array();
	foreach ($giocata_array as $id_question => $giocata_item)
	{
		$gruppo_risposta = $pos_groups[$id_question];
		
		if ($temp_debug) { // !!!
		echo "$gruppo_risposta<br>";
		print_r($gruppi_risposte_esatte[$gruppo_risposta]);
		echo "<br><br>";}
		
		if (in_array($giocata_item,$gruppi_risposte_esatte[$gruppo_risposta]))
		{
			if ($modalita == 'posizione')
			{
				$peso = $question_weight[$id_question];
			}
			else
			{
				$peso = 1;
			}
			$punteggio_output .= ','.($id_question+1);
		}
		else
		{
			if ($modalita == 'posizione')
			{
				$peso = max($question_weight)+1;
			}
			else
			{
				$peso = 0;
			}
		}
		$risposte_esatte[$id_question] = $peso;
		
		if ($temp_debug) {echo "$id_question) $giocata_item : $peso<br>";}
	}
	
	// in modalita' posizione devo prima ordinare i voti
	if ($modalita == 'posizione')
	{
		sort($risposte_esatte,SORT_ASC);
		
	}
	
	if ($temp_debug)
	{
		echo "Risposte esatte:<br>";
		print_r($risposte_esatte);echo "<br><br>";
	}
	
	// determina punteggio
	$punteggio = 0;
	$n_questions = count($question_weight);
	for ($id_risposta = 0; $id_risposta < $n_questions; $id_risposta++)
	{
		switch ($modalita)
		{
		case 'numero':
		case 'numero_pesato':
			if ($risposte_esatte[$id_risposta] == 0)
			{
				$voto = 0;
			}
			else
			{
				if ($modalita == 'numero_pesato')
				{
					$voto = $question_weight[$id_risposta];
				}
				else
				{
					$voto = 1;
				}
			}
			
			$punteggio -= $voto;
			
			break;
		case 'posizione':
			$voto = $risposte_esatte[$id_risposta];
			$punteggio = $punteggio*$n_questions+$voto;
			break;
		default:
			die("Modo $modalita non riconosciuto!");
		}
	}
	
	break;
case "eliminatorie":
	$livello_eliminatorie = $bulk[0];	// livello massimo delle eliminatorie (4 se si inizia dagli ottavi)
	$vettore_risposte_esatte = $bulk[1];	// vettore delle squadre premiate ($livello_eliminatorie per il primo, e via via a scendere)
	$risposte_possibili = $bulk[2];		// tutte le possibili risposte
	$maschera_risposte = $bulk[3];		// elenco delle risposte che interessano questa regola, vincitore per ultimo
	$risposte_equivalenti = $bulk[4];	// squadre qualificate in ciascun girone
	
	if ($temp_debug)
	{
		echo "<br><br>livello_eliminatorie:<br>";
		print_r($livello_eliminatorie);
		
		echo "<br><br>vettore_risposte_esatte:<br>";
		print_r($vettore_risposte_esatte);
		
		echo "<br><br>risposte_possibili:<br>";
		print_r($risposte_possibili);
		
		echo "<br><br>giocata_array:<br>";
		print_r($giocata_array);
	}
	
	// seleziona le risposte interessate dalla regola
	$giocata_utile_array = Array();
	foreach ($maschera_risposte as $id_risposta)
	{
		array_push($giocata_utile_array,$giocata_array[$id_risposta]);
	}
	
	// calcolo punteggio
	$vettore_squadre = array(); // viene passato per indirizzo, bisogna inizializzarlo qui
	$punteggio = 0; // più è negativo, più la giocata è buona
	$giocata_ok = get_vettore_squadre_vincenti($vettore_squadre,$giocata_utile_array,$livello_eliminatorie,$temp_debug);
	if (!$giocata_ok)
	{
		// penalità forte, perché non si è rispettato il vincolo che una squadra accede ai gironi successivi dopo aver superato
		// quelli di livello inferiore (ad es. si superano i quarti di finale solo se si sono superati gli ottavi, ecc.)
		$punteggio = $punteggio+1e6;
	}
	array_multisort($vettore_risposte_esatte,SORT_DESC,$risposte_possibili);
	
	$voti = array();
	$punteggio_output = '';
	foreach ($giocata_array as $squadra)
	{
		$voto_presunto = $vettore_squadre[$squadra];
		
		$voto_giusto = $vettore_risposte_esatte[$squadra]+0;
		
		
		$voto_finale = min($voto_presunto,$voto_giusto)+0;
		$voti[$squadra] = $voto_finale;
		
		$punteggio_output .= ",$voto_finale";
		
		if ($temp_debug & !$giocata_ok)
		{
			echo("$squadra => $voto_presunto,$voto_giusto,$voto_finale,".array_sum($voti)."<br>");
		}
	}
	
	$punteggio = $punteggio-array_sum($voti);
	break;
case "punteggi_specifici":
	$matrice_punteggi		= $bulk[0]; // matrice dei punti da associare al valore risposta per ciascun campo di giocata
	$matrice_punteggi_gruppo	= $bulk[1]; // punti associati al valore di risposta per ciascun gruppo delle risposte
	$lista_risposte_da_pesare	= $bulk[2]; // gruppo cui appartengono le risposte di interesse per il criterio
	
	if ($temp_debug)
	{
		echo "<br><br>matrice_punteggi:<br>";
		print_r($matrice_punteggi);
		
		echo "<br><br>matrice_punteggi_gruppo:<br>";
		print_r($matrice_punteggi_gruppo);
		
		echo "<br><br>lista_risposte_da_pesare:<br>";
		print_r($lista_risposte_da_pesare);
		
		echo "<br><br>giocata_array:<br>";
		print_r($giocata_array);
		
		echo "<br><br>";
	}
	
	$voti = array();
	$punteggio_output = '';
	foreach ($lista_risposte_da_pesare as $indice_risposta => $gruppo_risposta)
	{
		$risposte_valide = $matrice_punteggi_gruppo[$gruppo_risposta];
		$risposta = $giocata_array[$indice_risposta];
		$voto_finale = $risposte_valide[$risposta];
		$voti[$indice_risposta] = $voto_finale;
		$punteggio_output .= ",$voto_finale";
		
		if ($temp_debug) {
		echo "$indice_risposta) $gruppo_risposta: $risposta -> $voto_finale<br>";
		print_r($risposte_valide);
		echo "<br><br>";}
	}
	
	$punteggio = -array_sum($voti);
// 		$punteggio_output = 'giocata errata';
	break;
default:
	die("Regola non riconosciuta: ".$tipo_regola);
}

} // end function calcola_punteggio
?>
