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
//require_once('login.php');

$action = $_REQUEST['action'];			// azione da eseguire

$auth_token = $_REQUEST['auth_token'];		// chiave o nome da associare alla giocata

$id_questions = $_REQUEST['id_questions'];	// id della lotteria in oggetto

$data_giocata = $_REQUEST['data_giocata'];	// data che fa fede per la giocata


$file_questions = $root_path."custom/lotterie/lotteria_".sprintf("%03d",$id_questions).".txt";	// nome del file di configurazione relativo a id_questions
$file_log_questions = $root_path."custom/lotterie/lotteria_".sprintf("%03d",$id_questions)."_log.txt";	// nome del file di registrazione
$file_questions_ans = $root_path."custom/lotterie/lotteria_".sprintf("%03d",$id_questions)."_ans.php";	// nome del file con le risposte esatte
$file_template_ans = $root_path."custom/lotterie/lotteria_".sprintf("%03d",$id_questions)."_tpl_results.php";	// nome del template per i risultati
$file_template_form = $root_path."custom/lotterie/lotteria_".sprintf("%03d",$id_questions)."_tpl_form.php";	// nome del template per il form

// verifica che $id_questions sia un id relativo ad una lotteria o questionario valida
if (!file_exists($file_questions))
{
	die("La lotteria $id_questions non esiste!");
}

// carica file di configurazione della lotteria
$lotteria = get_config_file($file_questions);

$lotteria_nome = $lotteria["Attributi"][0][0];
/*$lotteria_stato = $lotteria["Attributi"][0][1];
$lotteria_auth = $lotteria["Attributi"][0][2];
$lotteria_inizio_giocate = $lotteria["Attributi"][0][3];
$lotteria_fine_giocate = $lotteria["Attributi"][0][4];
$lotteria_risultati = $lotteria["Attributi"][0][5];*/


$statistiche_list = $lotteria["statistiche_list"]; // una riga per ciascuna statistica da visualizzare

$indice_stat_tag = 0;	// indice tag univoco per ciascuna singola statistica
$indice_stat_tipo = 1;	// indice tipo di statistica
$indice_stat_msg = 2;	// indice testo della statistica
$indice_stat_item_caption = 3;	// indice testo sulla colonna delle voci della statistica
$indice_stat_item_count = 4;	// indice testo sulla colonna del conteggio
$indice_stat_data = 5;	// indice primo campo dati (interpretati a seconda di $id_stat_tipo)


$question_tag_format = "question_%02d";

$titolo_pagina = "<div class=\"titolo_tabella\">$lotteria_nome</div>";

$id_answers = 0; // campo all'interno di $file_log_questions contenente le risposte concatenate con ','


function print_header($lotteria_nome) {

# dichiara variabili
extract(indici());

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 TRANSITIONAL//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>

<head>
  <title><?php echo $web_title ?> - Statistiche &quot;<?php echo $lotteria_nome ?>&quot;</title>
  <meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1">
  <meta name="GENERATOR" content="Kate">
  <meta name="description" content="Analisi statistica dei dati relativi a <?php echo $lotteria_nome; ?>">
  <meta name="keywords" content="statistiche, lotteria, questionario, sondaggio">
  <!--style type="text/css">@import "<?php echo $filename_css ?>";</style-->
  <style type="text/css">@import "fkounter5/stats.css";</style>
</head>

<body>

<?php
} // end function print_header


switch ($action)
{
case "show":
	
	// carica giocate
	$giocate = get_config_file($file_log_questions);
	$giocate = $giocate['default'];
	
	// crea matrice elementi singoli
	$matr = array();
	foreach ($giocate as $id => $giocata)
	{
		$id_item = 0;
		foreach ($giocata as $id_col => $field)
		{
			//echo "$id-$id_col) ".$field."<br>";
			
			if ($id_col == $id_answers)
			{
				$list = explode(',',$field);
			}
			else
			{
				$list = array($field);
			}
			
			foreach ($list as $item)
			{
				$matr[$id][$id_item++] = $item;
			}
		}
	}
	
	// crea elenco per ciascuna risposta di $matr
	$conteggio = array();
	$num_campioni = count($matr);
	$num_stats = count($matr[0]);
	for ($id_col = 0; $id_col < $num_stats;$id_col++)
	{
		$temp_stat = array();
		for ($id = 0; $id < $num_campioni; $id++)
		{
			$item = $matr[$id][$id_col];
			$temp_stat[$item] = $temp_stat[$item]+1;
		}
		
		$conteggio[$id_col] = $temp_stat;
	}
	
	// crea struttura dati per la visualizzazione con show_statistics()
	$statistiche_results = array();
	foreach ($statistiche_list as $id => $stat_cfg)
	{
		$stat_caption = $stat_cfg[$indice_stat_tag];
		$stat_msg = $stat_cfg[$indice_stat_msg];
		$stat_tipo = $stat_cfg[$indice_stat_tipo];
		$stat_caption_tag = $stat_cfg[$indice_stat_item_caption];	// testo sulla colonna delle voci della statistica
		$stat_count_tag = $stat_cfg[$indice_stat_item_count];		// testo sulla colonna del conteggio
		$stat_bulk = array_slice($stat_cfg,$indice_stat_data);		// dati della statistica
		
		$stat_equivalenza = $stat_cfg[$indice_stat_data];
		
		// all'interno dello switch bisogna preparare, a seconda del tipo di statistica, $vett_dati come vettore ordinato $key => $conteggio
		switch ($stat_tipo)
		{
		case 'stat_domanda':
			$stat_equivalenza = $stat_bulk[0];	// elenco separato da "," di id delle domande da accorpare su cui effettuare la stat.
			
			$domande_equiv = explode(',',$stat_equivalenza);
			
			$vett_dati = array();
			foreach ($domande_equiv as $id_col)
			{
				//echo $id_col;
				foreach ($conteggio[$id_col-1] as $key => $count)
				{
					$vett_dati[$key] += $count;
				}
			}
			
			arsort($vett_dati,SORT_DESC); // ordina (in ordine decrescente) le risposte in base al numero di occorrenze
			
			break;
		case 'stat_risposta':
			$stat_equivalenza = $stat_bulk[0];	// elenco separato da "," di id delle risposte da accorpare su cui effettuare la stat.
			$stat_filtro_domande = $stat_bulk[1];	// elenco sep. da ";" di gruppi di dom. in cui cercare (a loro volta separ. da  ',')
			$stat_filtro_caption = $stat_bulk[2];	// elenco sep. da ";" del testo associato al gruppo di domande
			
			$risposte_equiv = explode(',',$stat_equivalenza);
			$stat_filtro_domande = explode(';',$stat_filtro_domande);
			$stat_filtro_caption = explode(';',$stat_filtro_caption);

			$lista_domande_utili = array();
			$filtro_domande	= array();
			foreach ($stat_filtro_domande as $str_gruppo_domande)
			{
				$gruppo_domande = explode(',',$str_gruppo_domande);
				array_push($filtro_domande,$gruppo_domande);
				$lista_domande_utili = array_merge($lista_domande_utili,$gruppo_domande);
			}
			
			// crea elenco per ciascuna colonna di $matr
			$conteggio_risp = array();
			$num_campioni = count($matr);
			$num_stats = count($matr[0]);
			foreach ($lista_domande_utili as $id_col)
			{
				$lista_gruppi_dest = array();
				foreach ($filtro_domande as $id_gruppo => $gruppo_domande)
				{
					if (in_array($id_col,$gruppo_domande))
					{
						array_push($lista_gruppi_dest,$id_gruppo);
					}
				}
				
				for ($id_campioni = 0; $id_campioni < $num_campioni; $id_campioni++)
				{
					$item = $matr[$id_campioni][$id_col-1];
					foreach ($lista_gruppi_dest as $id_domanda)
					{
						$conteggio_risp[$item][$id_domanda] += 1;
					}
				}
			}
			
			$result = $conteggio_risp[$risposte_equiv[0]]; // !!!

			$result2=array();
			foreach ($result as $id_gruppo => $conteggio)
			{
				$result2[$stat_filtro_caption[$id_gruppo]] = $conteggio;
			}
			$vett_dati = $result2;
			
			arsort($vett_dati,SORT_DESC); // ordina (in ordine decrescente) le risposte in base al numero di occorrenze
			
			break;
		default:
			die("Tipo di statistica sconosciuto ($stat_tipo)!");
		}
		
		$statistiche_results[$id] = array("caption" => $stat_caption,"caption_tag" => $stat_caption_tag,
			"count_tag" => $stat_count_tag,"messaggio" => $stat_msg,"dati" => $vett_dati);
		
	}
	
	// prepara i dati per la visualizzazione
	$mesi = array(1 => "gennaio",2 => "febbraio",3 => "marzo",4 => "aprile",
			5 => "maggio",6 => "giugno",7 => "luglio",8 => "agosto",
			9 => "settembre",10 => "ottobre",11 => "novembre",12 => "dicembre");

	$titolo_statistica="Statistiche per «<span class=\"count\">$lotteria_nome</span>» in data ";
	$titolo_statistica.="<span title=\"martedì\" class=\"hi\" style=\"cursor: help;\">".date("d")." ".$mesi[date("m")+0]." ".date("Y")."</span>";
	$titolo_statistica.=" ore <span class=\"hi\">".date("H:i")."</span>";
	
	$stat_footer = "Servizio statistiche realizzato in PHP";
	
	
	// visualizza la pagina
	print_header($lotteria_nome);
	
	show_statistics($statistiche_results,$titolo_statistica,$stat_footer);
	
	break;
default:
	die("Azione \"$action\" sconosciuta!");
	
} // end switch($action)


echo $homepage_link;

# logga il contatto
$counter = count_page("statistiche",array("COUNT"=>1,"LOG"=>1),$filedir_counter); # abilita il contatore, senza visualizzare le cifre, e fai il log

?>

</body></html>


<?php

function show_statistics($statistiche_results,$titolo_statistica,$stat_footer)
{
	echo "<div align=\"center\">\n";
	echo "<table class=\"conteiner\" cellpadding=\"0\" cellspacing=\"0\"><tbody>\n";
	echo "<tr>\n";
	echo "<td class=\"conteiner\">\n\n";
	
	echo "<p class=\"header\">$titolo_statistica</p>\n";
	
	foreach($statistiche_results as $id => $statistica)
	{
		// titolo della singola statistica (per evidenziare, includere tra i tag: <span class="hi">..</span>)
		$stat_title = $statistica["messaggio"];
		$stat_caption_tag = $statistica["caption_tag"];
		$stat_count_tag = $statistica["count_tag"];
		$dati = $statistica["dati"];
		
		echo "<p class=\"title\">$stat_title</p>\n\n";
		
		echo "<table cellspacing=\"0\" cellpadding=\"0\" class=\"graph\">\n";
		echo "<tr>\n";
		echo "\t<td colspan=\"5\" class=\"header\"><p>$stat_caption_tag</p></td>";
		echo "\t<td colspan=\"4\" class=\"header\"><p>$stat_count_tag</p></td>";
		echo "\t<td colspan=\"1\" class=\"header\"><p>%</p></td>";
		echo "</tr>\n";
		
		
		$num_elementi = array_sum($dati);
		foreach ($dati as $risposta => $count)
		{
			$percentuale = $count/$num_elementi*100;
			$pos_punto = strpos($percentuale,'.');
			if (strlen($pos_punto.' ') > 1) // se la percentuale e' un numero decimale...
			{
				$percentuale = substr($percentuale,0,$pos_punto+3); // ..approssimala lasciando solo due cifre decimali
			}
			
			$stat_caption_string = $risposta;
			$stat_item_count = $count;
			$stat_item_perc = $percentuale;
			
			echo "<tr>\n";
			echo "\t<td colspan=\"5\" class=\"item\"><p>$stat_caption_string</p></td>\n";
			echo "\t<td colspan=\"4\" class=\"chart\">\n";
			echo "\t\t<table cellspacing=\"0\" cellpadding=\"0\" style=\"width:100%;height:auto;\">\n";
			echo "\n";
			echo "\t\t<tr>\n";
			echo "\t\t\t<td class=\"bar\" style=\"width:$stat_item_perc%;\"><span style=\"font-size:1px;\">&nbsp;</span></td>\n";
			echo "\t\t\t<td class=\"hits\">$stat_item_count</td>\n";
			echo "\t\t</tr>\n";
			echo "\t\t</table>\n";
			echo "\t</td>\n";
			echo "\t<td colspan=\"1\" class=\"percentage\">\n";
			echo "\t\t<p>$stat_item_perc</p>\n";
			echo "\t</td>\n";
			echo "</tr>\n";
		}
		echo "</table>\n";
	}
	
	echo "\t\t</td>\n";
	echo "</tr>\n";
	echo "</tbody></table>\n\n";
	if (!empty($stat_footer) )
	{
		echo "<p class=\"credits\">$stat_footer</p>\n\n";
	}
	echo "</div>\n";
}

?>
