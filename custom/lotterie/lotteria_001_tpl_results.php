<?php

print_header();

$visualizza_giocate_anonime = true;	// true -> vengono visualizzate anche le giocate che non hanno una chiave corretta (giocate anonime)


$risposte_equivalenti=$bulk_punteggi[1][3];
$vettore_risposte_esatte=$bulk_punteggi[1][0];

// ricalcola il punteggio
$gruppo_old = -1; // valore fuori range in modo da capire che il ciclo non e' ancora iniziato
$elenco_giocate2 = array_slice($elenco_giocate,1);
$elenco_giocate3 = array_slice($elenco_giocate,0,1);
array_push($elenco_giocate3[0],'Numero risposte esatte');
foreach ($elenco_giocate2 as $giocata)
{
	$giocata_new = $giocata;
	$vettore_giocata = split(',',$giocata[1]);	// crea un array con le singole giocate
	
	$mazzo_vecchio = $vettore_giocata;		// vechia giocata, nell'ordine in cui e' stata inserita
	$mazzo_nuovo = array();				// diventera' la nuova giocata, riordinata per avere le risposte giuste all'inizio
	$punteggio=0;
	foreach ($soluz as $id => $squadra)
	{
		$gruppo=$vettore_risposte_esatte[$id];	// gruppo equiv. cui appartiene la risposta esatta $id-esima ($vettore_risposte_esatte[$id])
		
		// se e' cambiata la clase di equivalenza, e si passa da $gruppo_old a $gruppo (es. da ottavi di finale a quarti di finale)
		// finisci di copiare le risposte rimaste (non corrette) da $mazzo_vecchio a $mazzo_vuoto
		if (($gruppo_old >= 0) & ($gruppo_old != $gruppo))
		{
			for ($i = $gruppo_old;$i<$gruppo;$i++)
			{
				if (array_key_exists($i-1,$mazzo_vecchio))
				{
					// inseriscila in $mazzo_nuovo, in fondo
					array_push($mazzo_nuovo,$mazzo_vecchio[$i-1]);
					// e togli la squadra trovata da $mazzo_vecchio
					unset($mazzo_vecchio[$i-1]);
				}
			}
		}
		
		// conteggio delle x (giocate corrette)
		if (in_array($vettore_giocata[$id],$risposte_equivalenti[$gruppo]))
		{
			$punteggio++;
			
			// togli la squadra trovata da $mazzo_vecchio (ma non modificare le chiavi)
			unset($mazzo_vecchio[array_search($vettore_giocata[$id],$mazzo_vecchio)]);
			// ed inseriscila in $mazzo_nuovo, in fondo
			array_push($mazzo_nuovo,$vettore_giocata[$id]);
		}
		
		// aggiorna il puntatore al precedente gruppo di equivalenza delle risposte
		$gruppo_old = $gruppo;
		
	}
	
	// in $mazzo_vecchio sono rimaste solo le (eventuali) risposte non esatte dell'ultima classe di equivalenza, mentre in $mazzo_nuovo
	// sono state portate tutte quelle esatte. 
	// Adesso trasferisco tutte le giocate rimaste in $mazzo_nuovo, cosi' quest'ultimo presenta le giocate ordinate: 
	// prima le corrette, poi le errate
	foreach ($mazzo_vecchio as $squadra_restante)
	{
		array_push($mazzo_nuovo,$squadra_restante);
	}
	
	// aggiorno la giocata vecchia, non ordinata (sarebbe stata visualizzata con x sparse), con quella ordinata
	$giocata_riordinata = implode(',',$mazzo_nuovo);
	$giocata_new[1]=$giocata_riordinata;
	
	$giocata_new[count($giocata)] = $punteggio; // nell'ultima colonna aggiungo il punteggio esatto
	
	array_push($elenco_giocate3,$giocata_new);
}
$elenco_giocate = $elenco_giocate3;
$mask=array_merge($mask,Array(count($giocata)));


// alias delle varie risposte
$vettore_alias_domanda = array('Q','Q','Q','Q','Q','Q','Q','Q','Q','Q','Q','Q','Q','Q','Q','Q',
	'W','W','W','W','W','W','W','W',
	'S','S','S','S',
	'F','F','C');
$vettore_alias2_domanda = array('Amm. ottavi', 'Amm. ottavi', 'Amm. ottavi', 'Amm. ottavi',
	'Amm. ottavi', 'Amm. ottavi', 'Amm. ottavi', 'Amm. ottavi',
	'Amm. ottavi', 'Amm. ottavi', 'Amm. ottavi', 'Amm. ottavi',
	'Amm. ottavi', 'Amm. ottavi', 'Amm. ottavi', 'Amm. ottavi',
	'Amm. quarti', 'Amm. quarti', 'Amm. quarti', 'Amm. quarti', 'Amm. quarti', 'Amm. quarti', 'Amm. quarti', 'Amm. quarti',
	'Ammesse alle semifinali', 'Ammesse alle semifinali', 'Ammesse alle semifinali', 'Ammesse alle semifinali',
	'Ammesse in finale', 'Ammesse in finale', 'Campione del mondo');
// e peso corrispondente per la visualizzazione
$vettore_alias_id = array(5,5,5,5,5,5,5,5,5,5,5,5,5,5,5,5,4,4,4,4,4,4,4,4,3,3,3,3,2,2,1);

// indice delle risposte ordinate per importanza (secondo $vettore_alias_id)
$list = $vettore_alias_id;
$list0=array_keys($list);
array_multisort($list,SORT_DESC,$list0);


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
	
	foreach ($risposte_equivalenti[$vettore_risposte_esatte[$id]] as $sq_ok)
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
		$squadra = $soluz[$id];
		
		$gruppo=$vettore_risposte_esatte[$id];
		
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

if ($_REQUEST['debug']=='full')
{
	show_table($elenco_giocate,$mask,'tabella',1,12,1); # tabella in una colonna, font 12, con note
	echo "<br><hr><br>";
}



// verifica che le giocate siano aperte e stampa il relativo messaggio
if ($v_now[0] > $v_results[0])
{
	$mask_new = array_merge(Array(6,0,4,3,1),range(7,7+count($soluz)-1),Array(2)); // codice, giocatore, tipo giocata, punteggio, x, data
}
else
{
	$mask_new = array_merge(Array(6,0,4,1),range(7,7+count($soluz)-1),Array(2)); // codice, tipo giocata, punteggio, x, data
}
if ($_REQUEST['debug'] == 'full')
{
	$mask_new = array_merge(Array(6,0,1,2,3,4,5),range(7,7+count($soluz)-1)); // tutti i campi
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
<td>

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

</td>

</tr></table>

<?php

echo "<hr>";
echo "<a href=\"custom/lotterie/lotteria_".sprintf("%03d",$id_questions)."_tpl_form.php?info_mode=1\">Visualizza tabellone</a>\n";


# logga il contatto
$counter = count_page("questions",array("COUNT"=>1,"LOG"=>1),$filedir_counter); # abilita il contatore, senza visualizzare le cifre, e fai il log

?>
