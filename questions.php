<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 TRANSITIONAL//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
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

$action = $_REQUEST['action'];			// azione da eseguire

$auth_token = $_REQUEST['auth_token'];		// chiave o nome da associare alla giocata

$id_questions = $_REQUEST['id_questions'];	// id della lotteria in oggetto

$data_giocata = $_REQUEST['data_giocata'];	// data che fa fede per la giocata

$file_questions = $root_path."custom/lotterie/lotteria_".sprintf("%03d",$id_questions).".txt";	// nome del file di configurazione relativo a id_questions
$file_log_questions = $root_path."custom/lotterie/lotteria_".sprintf("%03d",$id_questions)."_log.txt";	// nome del file di registrazione

// verifica che $id_questions sia un id relativo ad una lotteria o questionario valida
if (!file_exists($file_questions))
{
	die("La lotteria $id_questions non esiste!");
}

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
$v_now = parse_date(date("h:i d/m/Y"));

// smista l'azione di default a seconda della data attuale
if (empty($action))
{
	if (($v_now[0] > $v_results[0]) | (!empty($_REQUEST['debug'])) | (!empty($_REQUEST['filtro'])))
	{
		$action = "results"; 			// azione di default dopo la data di presentazione risultati (v_results)
	}
	else
	{
		$action = "auth"; 			// azione di default nel periodo di giocate aperte
	}
}


$question_tag_format = "question_%02d";

?>
<head>
  <title><?php echo $web_title ?> - <?php echo $lotteria_nome ?></title>
  <meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1">
  <meta name="GENERATOR" content="Kate">
  <meta name="description" content="<?php echo $lotteria_nome; ?>">
  <meta name="keywords" content="lotteria, questionario">
  <style type="text/css">@import "<?php echo $filename_css ?>";</style>
</head>

<html><body>

<?php

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


function parse_date($data) {

$ore = substr($data,0,2);
$minuti = substr($data,3,2);
$giorno = substr($data,6,2);
$mese = substr($data,9,2);
$anno = substr($data,12,4);

$mins = (((($anno*12+$mese)*31+$giorno)*24+$ore)*60+$minuti)*60;

return array($mins,$anno,$mese,$giorno,$ore,$minuti);
} // end function parse_date


echo "<div class=\"titolo_tabella\">$lotteria_nome</div>";

switch ($action)
{
case "auth":
	// verifica che le giocate siano aperte
	if ($v_now[0] < $v_start[0])
	{
		die($lotteria['msg_date'][0][0]);
	}
	elseif ($v_now[0] > $v_end[0])
	{
		die($lotteria['msg_date'][0][2]);
	}
	else
	{
		echo($lotteria['msg_date'][0][1]);
	}
	echo "<br><hr>\n";
	
	// visualizza form di autenticazione
	if ($lotteria_auth == "no_auth")
	{
		$auth_token = $login['username'];
	}
	elseif ($lotteria_auth == "key")
	{
		echo "<form action=\"questions.php\" method=\"post\">\n";
		echo 'Inserisci la chiave segreta per giocare:<input type="edit" name="secret_key"/><br>';
		echo '<input type="submit" value="vai avanti"/>';
		
		echo "<input type=\"hidden\" name=\"id_questions\" value=\"$id_questions\">\n";
		echo '<input type="hidden" name="action" value="check_auth"/>';
		
		echo "</form>\n";
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
		
		echo "Benvenuto";
		if (!empty($nominativo))
		{
			echo " $nominativo";
		}
		echo ", puoi giocare.<br><br>\n";
	} // if ($action == "check_auth")
case "fill":
	// visualizza le domande	
	show_question_form($lotteria,"questions.php","last_check",$id_questions,$auth_token,"Gioca");
	
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
	
	// verifica che la chiave inserita sia corretta, ed individuane il gruppo
	$found_key = check_question_keys($id_questions,$auth_token);
	$nominativo = $found_key[2][2];	// nome di chi ha ricevuto il biglietto, registrato a cura dell'amministratore
	
	if (empty($found_key))
	{
		die($lotteria['msg_auth_failed'][0][0]);
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
			$answer = $_REQUEST[$question_tag];
			break;
		case "fixed":
			$answer = $_REQUEST[$question_tag];
		}
		array_push($answers,$answer);
		$question_count++;
	}
	$results = array();
	$msg = array();
	$result = check_answers($lotteria,$answers,$results,$msg);
	
	if ($result)
	{
		echo "Confermi le tue scelte?<br><br>\n";
		$conferma_disabled = "";
		$modifica_disabled = "";
	}
	else
	{
		echo "Ci sono errori nelle risposte!<br><br>\n";
		$conferma_disabled = "disabled";
		$modifica_disabled = "";
	}

	echo "<form method=\"post\">\n";
	$question_count = 0;
	foreach ($lotteria["Domande"] as $domanda)
	{
		echo ($question_count+1).") $domanda[$indice_question_caption]: \n";
		
		switch ($domanda[$indice_question_tipo])
		{
		case "free_number":
		case "free_string":
			$question_tag = sprintf($question_tag_format,$question_count); // question_xx
			$answer = $_REQUEST[$question_tag];
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
			$answer = $_REQUEST[$question_tag];
			if ($results[$question_tag] == 0)
			{
				$messaggio = "<span style=\"color: red;\">".$msg[$question_count]."</span>";
			}
			else
			{
				$messaggio = "";
			}
			echo(" $answer $messaggio\n");
			
			// determina le varie risposte possibili
			$gruppi_domande = split(",",$domanda[$indice_question_gruppo]);
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
	// verifica che la chiave sia corretta
	$found_key = check_key($id_questions,$auth_token,$lotteria['msg_auth_failed'][0][0]);
	$nominativo = $found_key[2][2];	// nome di chi ha ricevuto il biglietto, registrato a cura dell'amministratore
	
	// verifica che non si giochi piu' volte la stessa giocata	
	$giocata_ripetuta = 0;
	$giocate = get_giocata($id_questions,$auth_token);
	if ((count($giocate)>0) && ($lotteria_auth !== "no_auth"))
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
		
		$answer = $_REQUEST[$question_tag];
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
		$data_giocata = date("H:i d/m/Y ");
	}
	
	$log = $string_answers."::".time()."::".$data_giocata."::".$auth_token."\n";
	
	$bulk = get_config_file($file_log_questions);
	$ultima_giocata = $bulk['default'][count($bulk['default'])-1];
	$giocata_da_salvare = split("::",$log);

	if (($ultima_giocata[0]==$giocata_da_salvare[0]) && ($giocata_da_salvare[1]-$ultima_giocata[1] < 3600*2) && ($ultima_giocata[3]-$giocata_da_salvare[3] == 0))
	{
		$giocata_ripetuta = 1;
	}
	
	if ($giocata_ripetuta)
	{
		echo("ATTENZIONE! La giocata &egrave; gi&agrave; stata registrata:<br><br>\n");
		show_giocate($giocate);
	}
	else
	{
		//registra la giocata
		$cf = fopen($file_log_questions, 'a');
		fwrite($cf, $log);
		fclose($cf);
		
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
	}
	
	break;
case "results":
	echo "<div class=\"titolo_tabella\">Classifica</div><br>";
	
	$giocate = get_config_file($file_log_questions);
	$giocate = $giocate['default'];
	//show_giocate($giocate);

$header = array("id","giocata","time","data giocata","auth_token","cedente","giocatore","data cessione","id tipo giocata","tipo giocata", 
		"punteggio1","punteggio2");

$count = 0;
$elenco_giocate = array($header);
foreach ($giocate as $giocata)
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
	
	// todo: creazione punteggi per la classifica
	$punteggio1 = $auth_token_i[0];
	$punteggio2 = $auth_token_i[1];

	$dati_giocata = array_merge($count, $giocata, $cedente_biglietto, $nominativo_biglietto, $data_biglietto, $id_tipo_biglietto, $tipo_biglietto, $punteggio1, $punteggio2);
	array_push($elenco_giocate,$dati_giocata);
}

if (!empty($_REQUEST['filtro']))
{
	$lista_regola_campo = array(8); // id_tipo_giocata
	//$lista_regola_valore = array(0);// a pagamento
	$lista_regola_valore = array($_REQUEST['filtro']-1);
	$elenco_giocate = filtra_archivio($elenco_giocate,$lista_regola_campo,$lista_regola_valore);
}

//$elenco_giocate = ordina_archivio($elenco_giocate, 2, -1);
$elenco_giocate = ordina_archivio($elenco_giocate, 10, 11);

if ($_REQUEST['debug'] === "full")
{
	$mask = array(0,1,2,3,4,5,6,7,8,9,10,11);
}
else
{
	$mask = array(0,1,3,4,6,9);
}
show_table($elenco_giocate,$mask,'tabella',3,12,1); # tabella in tre colonne, font 12, con note



	break;
default:
	die("Azione \"$action\" sconosciuta!");
	
} // end switch($action)


echo $homepage_link;

# logga il contatto (modifico la query string per aggiungere nei log una informazione diretta alla foto visualizzata)
$ks = $_SERVER['QUERY_STRING'];
$_SERVER['QUERY_STRING'] = $ks;
$counter = count_page("questions",array("COUNT"=>1,"LOG"=>1),$filedir_counter); # abilita il contatore, senza visualizzare le cifre, e fai il log

?>
</body></html>
