<?php

print_header();

$visualizza_giocate_anonime = true;	// true -> vengono visualizzate anche le giocate che non hanno una chiave corretta (giocate anonime)



$risposte_equivalenti=$bulk_punteggi[1][3];
$vettore_risposte_esatte=$bulk_punteggi[1][0];

// ricalcola il punteggio
$elenco_giocate2 = array_slice($elenco_giocate,1);
$elenco_giocate3 = array_slice($elenco_giocate,0,1);
array_push($elenco_giocate3[0],'Numero risposte esatte');
foreach ($elenco_giocate2 as $giocata)
{
	$giocata_new = $giocata;
	$vettore_giocata = split(',',$giocata[1]);
	$punteggio=0;
	foreach ($soluz as $id => $squadra)
	{
		$gruppo=$vettore_risposte_esatte[$id];
		if (in_array($vettore_giocata[$id],$risposte_equivalenti[$gruppo]))
		{
			$punteggio++;
		}
	}
	
	$giocata_new[count($giocata)] = $punteggio;
	
	array_push($elenco_giocate3,$giocata_new);
}
$elenco_giocate = $elenco_giocate3;
$mask=array_merge($mask,count($giocata));


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
	$giocatore_ks = $giocata[6];
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
	
	// crea giocata
	$record_new = array();
	array_push($record_new,$id_della_giocata);
	array_push($record_new,$punteggio);
	array_push($record_new,$data_giocata);
	array_push($record_new,$giocatore_ks);
	array_push($record_new,$auth_token_ks);
	array_push($record_new,$tipo_giocata_ks);
	array_push($record_new,$count);
	
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
if ($_REQUEST['debug'] == 'full')
{
	$mask_new = array_merge(6,0,1,2,3,4,5,range(7,7+count($soluz)-1)); // tutti i campi
}

show_table($elenco_new,$mask_new,'tabella',1,12,1); # tabella in una colonna, font 12, con note
?>

<br>
&nbsp;&nbsp;
Legenda:

<div class="txt_link">
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b>C</b>: Campione del mondo<br>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b>F</b>: Squadra finalista<br>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b>S</b>: Squadra semifinalista<br>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b>W</b>: Squadra ammessa ai quarti di finale<br>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b>Q</b>: Squadra ammessa agli ottavi di finale<br>
</div>


<?php

echo "<hr>";
echo "<a href=\"custom/lotterie/lotteria_".sprintf("%03d",$id_questions)."_tpl_form.php?info_mode=1\">Visualizza tabellone</a>\n";


# logga il contatto
$counter = count_page("questions",array("COUNT"=>1,"LOG"=>1),$filedir_counter); # abilita il contatore, senza visualizzare le cifre, e fai il log

?>
