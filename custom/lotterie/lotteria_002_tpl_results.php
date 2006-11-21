<?php

// 
// input impliciti:
// 	$giocate	: archivio delle giocate registrate (da lotteria_XXX_log.php)
// 	$soluz_array	: dati strutturati (da lotteria_XXX_ans.php)
// 	$soluz		: risposte esatte (usate per l'ordinamento)
// 	$numero_risposte_per_giocata : numero di risposte salvate per ciascuna giocata (primo campo)
//	$criteri	: parte fissa (primi 3 campi delle configurazioni di ordinamento)
//	$bulk_punteggi	: dati di configurazione necessari per l'ordinamento (campi successivi al terzo)
//	$lista_criteri	: elenco dei nomi dei criteri di ordinamento
// 

$debug_mode = ($_REQUEST['debug']=='full');



print_header();

//
// configurazioni
//

$visualizza_giocate_anonime = true;	// true -> vengono visualizzate anche le giocate che non hanno una chiave corretta (giocate anonime)

$indice_regola_eliminatorie = $lista_criteri['eliminatorie']; // [0..] posizione (tra le altre regole di ordinamento) regola 'eliminatorie'
$indice_regola_punteggi_specifici = $lista_criteri['punteggi_specifici']; // [0..] indice del criterio "punteggi_specifici"
$indice_regola_esatte_per_gruppi = $lista_criteri['esatte_per_gruppi']; // [0..] indice del criterio "esatte_per_gruppi"


//
// calcoli
//
$vettore_risposte_esatte=$bulk_punteggi[$indice_regola_eliminatorie][1];	// punteggio per ciascuna delle squadre qualificate
$risposte_equivalenti=$bulk_punteggi[$indice_regola_eliminatorie][4];		// squadre associate a ciascun gruppo
//$gruppo_risposta = $bulk_punteggi[$indice_regola_esatte_per_gruppi][0]; // equivalente alla riga sottostante
$gruppo_risposta = $bulk_punteggi[$indice_regola_punteggi_specifici][2];
$punteggio_specifico = $bulk_punteggi[$indice_regola_punteggi_specifici][0];

// print_r($punteggio_specifico);



//
// ricalcola il punteggio (e aggiungilo in una colonna in fondo)
//

// scomponi l'archivio delle giocate:
$elenco_giocate2 = array_slice($elenco_giocate,1);	// giocate effettive (dal secondo elemento in poi)

$elenco_giocate3 = array_slice($elenco_giocate,0,1);	// inizia con gli headers soltanto, poi aggiungi tutte le giocate rielaborate
array_push($elenco_giocate3[0],'Numero risposte esatte');	// aggiungi il titolo per l'ultima colonna da aggiungere (punteggio visibile)
foreach ($elenco_giocate2 as $indice_giocata => $giocata)
{
	$giocata_new = $giocata;
	$vettore_giocata = split(',',$giocata[1]);	// crea un array con le singole giocate
	$vettore_giocata_new = $vettore_giocata;	// andra' riordinato per avvicinare le x

	$punteggio = 0;
	$bulk_gruppi = Array();
	$lista_indici = Array(); // elenco degli indici delle risposte appartenenti allo stesso gruppo
	$lista_risposte = Array(); // elenco delle risposte appartenenti allo stesso gruppo
	$lista_punti = Array(); // elenco dei punti associati alle risposte appartenenti allo stesso gruppo
	$gruppo_old = -1; // valore fuori range in modo da capire che il ciclo non e' ancora iniziato
	foreach($gruppo_risposta as $indice_risposta => $gruppo)
	{
		$risposta = $vettore_giocata[$indice_risposta];
		$punti = (integer)($punteggio_specifico[$indice_risposta][$risposta]);
		
		if ( (($gruppo_old >= 0) & ($gruppo_old != $gruppo)) | (count($gruppo_risposta) == $count) )
		{
			$bulk_gruppi[$gruppo_old] = Array($lista_indici,$lista_risposte,$lista_punti);
			
			$lista_indici = Array($indice_risposta);
			$lista_risposte = Array($risposta);
			$lista_punti = Array($punti);
		}
		else
		{
			array_push($lista_indici,$indice_risposta);
			array_push($lista_risposte,$risposta);
			array_push($lista_punti,$punti);
		}
		
		$punteggio += $punti;
		
		$gruppo_old = $gruppo;
	}
	$bulk_gruppi[$gruppo_old] = Array($lista_indici,$lista_risposte,$lista_punti);
	
	foreach ($bulk_gruppi as $gruppo => $dati_gruppo)
	{
		$lista_indici = $dati_gruppo[0];
		$lista_risposte = $dati_gruppo[1];
		$lista_punti = $dati_gruppo[2];
		
		array_multisort($lista_punti,SORT_DESC,$lista_risposte); // riordina le risposte (ed i relativi punteggi)
		
		foreach($lista_indici as $indice_temp => $indice_risposta)
		{
			$vettore_giocata_new[$indice_risposta] = $lista_risposte[$indice_temp];
		}
	}
	
	$giocata_riordinata = implode(',',$vettore_giocata_new);
	
	// aggiorna giocata new
	$giocata_new[1]=$giocata_riordinata; 		// 1) sostituisci la vecchia giocata con quella riordinata
	$giocata_new[count($giocata)] = $punteggio; 	// 2) nell'ultima colonna aggiungo il punteggio esatto da visualizzare nella classifica
	
	// ed inseriscila in archivio
	array_push($elenco_giocate3,$giocata_new);
}
$elenco_giocate = $elenco_giocate3;

// 	echo "elenco_giocate[0]:<br>";
// 	print_r($elenco_giocate[0]);
// 	echo "<br><br>";
// 	echo "elenco_giocate[1]:<br>";
// 	print_r($elenco_giocate[1]);
// 	echo "<br><br>";



// aggiungi a mask l'indice dell'ultima colonna
$mask=array_merge($mask,count($giocata));


// alias delle varie risposte
/*$vettore_alias_domanda = array('Q','Q','Q','Q','Q','Q','Q','Q','Q','Q','Q','Q','Q','Q','Q','Q',
	'W','W','W','W','W','W','W','W',
	'S','S','S','S',
	'F','F','C');*/
$vettore_alias_domanda = array(
	'W','W','W','W','W','W','W','W',
	'S','S','S','S',
	'F','F','C');
/*$vettore_alias2_domanda = array('Amm. ottavi', 'Amm. ottavi', 'Amm. ottavi', 'Amm. ottavi',
	'Amm. ottavi', 'Amm. ottavi', 'Amm. ottavi', 'Amm. ottavi',
	'Amm. ottavi', 'Amm. ottavi', 'Amm. ottavi', 'Amm. ottavi',
	'Amm. ottavi', 'Amm. ottavi', 'Amm. ottavi', 'Amm. ottavi',
	'Amm. quarti', 'Amm. quarti', 'Amm. quarti', 'Amm. quarti', 'Amm. quarti', 'Amm. quarti', 'Amm. quarti', 'Amm. quarti',
	'Ammesse alle semifinali', 'Ammesse alle semifinali', 'Ammesse alle semifinali', 'Ammesse alle semifinali',
	'Ammesse in finale', 'Ammesse in finale', 'Campione del mondo');*/
$vettore_alias2_domanda = array(
	'Amm. quarti', 'Amm. quarti', 'Amm. quarti', 'Amm. quarti', 'Amm. quarti', 'Amm. quarti', 'Amm. quarti', 'Amm. quarti',
	'Ammesse alle semifinali', 'Ammesse alle semifinali', 'Ammesse alle semifinali', 'Ammesse alle semifinali',
	'Ammesse in finale', 'Ammesse in finale', 'Campione del mondo');
// e peso corrispondente per la visualizzazione
// $vettore_alias_id = array(5,5,5,5,5,5,5,5,5,5,5,5,5,5,5,5,4,4,4,4,4,4,4,4,3,3,3,3,2,2,1);
$vettore_alias_id = array(4,4,4,4,4,4,4,4,3,3,3,3,2,2,1);

// indice delle risposte ordinate per importanza (secondo $vettore_alias_id)
$list = $vettore_alias_id;
$list0=array_keys($list);
array_multisort($list,SORT_DESC,$list0);

/*print_r($list);echo "<br>";
print_r($list0);echo "<br>";*/
// die();

// crea i titoli della nuova matrice
$header_new = array();
array_push($header_new,'Id');
array_push($header_new,'Punti');
array_push($header_new,'Data');
array_push($header_new,'Giocatore');
array_push($header_new,'Codice');
array_push($header_new,'Tipo giocata');
array_push($header_new,'Pos.');

foreach ($list0 as $id)
{
	$squadra = $vettore_alias_domanda[$id];
	
	$titolo = $vettore_alias2_domanda[$id].":";
	
	$gruppo = $list[$id];
	
	foreach ($risposte_equivalenti[$gruppo] as $sq_ok)
	{
		$titolo .= $sq_ok.',';
	}
	$titolo = substr($titolo,0,-1);
	
	$squadra = "<span title=\"$titolo\">$squadra</span>";
	array_push($header_new,$squadra);
}


// prepara elenco di stili
foreach ($lotteria['stili_riga'] as $id_stile => $stile_data)
{
	$stile_tag = $stile_data[0];
	$stile_caption = $stile_data[1];
	$stile_style = $stile_data[2];
	
	$elenco_stili[$stile_tag] = array($stile_caption,$stile_style);
}


// crea nuova tabella
$elenco_new = array($header_new);
$elenco_giocate2 = array_slice($elenco_giocate,1);
$count = 0;
foreach ($elenco_giocate2 as $giocata)
{
	// i campi qui devono essere gli stessi e nello stesso ordine di quelli indicati sopra per $header_new
	$id_della_giocata = $giocata[0];
	$vettore_giocata = split(',',$giocata[1]);
	$data_giocata = $giocata[3];
	$auth_token_ks = $giocata[4];
	$giocatore_ks = "<div align=\"left\" style=\"margin-left:10pt;\">".$giocata[6]."</div>";
	$tipo_giocata_ks = $giocata[8];
	$punteggio = $giocata[count($giocata)-1];
	$count++;

	// elaborazione dei campi
	if ($giocatore_ks === '-')
	{
		$giocatore_ks = "anonimo";
		
		if (!$visualizza_giocate_anonime) continue;
	}
	
	// elaborazione tipo giocata
	if (strlen($tipo_giocata_ks.' ')==1) $tipo_giocata_ks .= '-';
	switch ($tipo_giocata_ks)
	{
	case '0':
		$tipo_giocata_ks = '$';
		break;
	case '1' :
		$tipo_giocata_ks = 'g';
		break;
	default:
		$tipo_giocata_ks = '?';
		//die("Tipo giocata sconosciuto:".$tipo_giocata_ks);
		break;
	}
	
	// inverti data e ora nella data di giocata
	$data_giocata = substr($data_giocata,strpos($data_giocata,' ')+1).' '.substr($data_giocata,0,strpos($data_giocata,' '));

	
	// crea giocata
	$record_new = array();
	array_push($record_new,$id_della_giocata);
	array_push($record_new,$punteggio);
	array_push($record_new,$data_giocata);
	array_push($record_new,$giocatore_ks);
	array_push($record_new,$auth_token_ks);
	array_push($record_new,$tipo_giocata_ks);
	array_push($record_new,$count);
	
	// stile riga
	$found_key = check_question_keys($id_questions,$auth_token_ks);
	$stile_riga = $found_key[2][4];

	if (!empty($stile_riga))
	{
		$record_new['stile_riga'] = $elenco_stili[$stile_riga][1];
	}
	
	foreach ($list0 as $id)
	{
		$squadra = $soluz[$id];	// valore esatto per la risposta in esame
		$gruppo=$list[$id];	// gruppo (4,3,2,1) della risposta
		
		if (in_array($vettore_giocata[$id],$risposte_equivalenti[$gruppo]))
		{
			$simb = "<span title=\"".$vettore_giocata[$id]."\">x</span>\n";
		}
		else
		{
			$simb = "<span title=\"".$vettore_giocata[$id]."\">-</span>\n";
		}
		array_push($record_new,$simb);
	}
	
	// aggiungi il record con i nuovi campi
	array_push($elenco_new,$record_new);
}


echo "<!-- Visualizzazione personalizzata per $lotteria_nome -->\n";
echo "$titolo_pagina<br>\n";

if ($debug_mode)
{
	show_table($elenco_giocate,$mask,'tabella',1,12,1); # tabella in una colonna, font 12, con note
	echo "<br><hr><br>";
}



// verifica che le giocate siano aperte e stampa il relativo messaggio
if ($v_now[0] > $v_results[0])
{
	$mask_new = array_merge(6,0,4,3,1,range(7,7+count($soluz)-1),2); // codice, giocatore, tipo giocata, punteggio, x, data
}
else
{
	$mask_new = array_merge(6,0,4,  1,range(7,7+count($soluz)-1),2); // codice, tipo giocata, punteggio, x, data
}
if ($debug_mode)
{
	$mask_new = array_merge(6,0,1,2,3,4,5,range(7,7+count($soluz)-1)); // tutti i campi
}

show_table($elenco_new,$mask_new,'tabella',1,12,1); # tabella in una colonna, font 12, con note
?>


<br>
<table width=100%><tr valign="top">

<td width=30%>
&nbsp;&nbsp;
Legenda:

<div class="txt_link">
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b>Q</b> : Squadra ammessa agli ottavi di finale<br>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b>W</b> : Squadra ammessa ai quarti di finale<br>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b>S</b> : Squadra semifinalista<br>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b>F</b> : Squadra finalista<br>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b>C</b> : Campione del mondo<br>
</div>

</td>
<!--td>

&nbsp;&nbsp;
Legenda colori:
<div class="txt_link">
<?php
foreach ($lotteria['stili_riga'] as $id_stile => $stile_data)
{
	$stile_tag = $stile_data[0];
	$stile_caption = $stile_data[1];
	$stile_style = $stile_data[2];
	
	$elenco_stili[$stile_tag] = array($stile_caption,$stile_style);

	echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span style=\"$stile_style;border:1px solid;\">&nbsp;&nbsp;&nbsp;&nbsp;</span> : $stile_caption<br>";
	echo "<div style=\"height:2.5pt;\">&nbsp;</div>";

}
?>
</div>

</td-->

</tr></table>

<?php

echo "<hr>";
echo "<a href=\"custom/lotterie/lotteria_".sprintf("%03d",$id_questions)."_tpl_form.php?info_mode=1\">Visualizza tabellone</a>\n";


# logga il contatto
$counter = count_page("questions",array("COUNT"=>1,"LOG"=>1),$filedir_counter); # abilita il contatore, senza visualizzare le cifre, e fai il log

?>
