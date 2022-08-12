<?php

// 
// input impliciti:
//	$lotteria_nome	: nome del sondaggio/lotteria
//	$lotteria_fine_giocate : data di chiusura delle giocate
// 	$giocate	: archivio delle giocate registrate (da lotteria_XXX_log.php)
// 	$soluz_array	: dati strutturati (da lotteria_XXX_ans.php)
// 	$soluz		: risposte esatte (usate per l'ordinamento)
// 	$numero_risposte_per_giocata : numero di risposte salvate per ciascuna giocata (primo campo)
//	$criteri	: parte fissa (primi 3 campi delle configurazioni di ordinamento)
//	$bulk_punteggi	: dati di configurazione necessari per l'ordinamento (campi successivi al terzo)
//	$lista_criteri	: elenco dei nomi dei criteri di ordinamento
// 

$debug_mode = ($_REQUEST['debug']=='full');



print_header($lotteria_nome);

//
// configurazioni
//

$visualizza_giocate_anonime = true;	// true -> vengono visualizzate anche le giocate che non hanno una chiave corretta (giocate anonime)
$nascondi_pronostici_a_giocate_aperte = true; 	// true -> le giocate saranno visibili solo dalla data di chiusura delle giocate

$indice_regola_eliminatorie 	= $lista_criteri['eliminatorie']; 	// [0..] posizione (tra le altre regole di ordinamento) regola 'eliminatorie'
$indice_regola_punteggi_specifici = $lista_criteri['punteggi_specifici']; // [0..] indice del criterio "punteggi_specifici"
$indice_regola_esatte_per_gruppi= $lista_criteri['esatte_per_gruppi']; 	// [0..] indice del criterio "esatte_per_gruppi"
$indice_regola_data_giocata 	= $lista_criteri['data_giocata']; 	// [0..] posizione (tra le altre regole di ordinamento) regola 'data_giocata'

//
// calcoli
//

// se le giocate sono ancora aperte, non visualizzare le giocate, ma solo i punteggi (se la configurazione lo richiede)
$v_end = parse_date($lotteria_fine_giocate);
$v_now = parse_date(date("H:i d/m/Y"));
if (($v_now[0] < $v_end[0]) and ($nascondi_pronostici_a_giocate_aperte))
{
	$flag_nascondi_pronostici = true;
}
else
{
	$flag_nascondi_pronostici = false;
}

// punteggio per ciascuna delle squadre qualificate (es. Array ( [Barcellona] => 4 [Bayern Monaco] => 3 [Galatasaray] => 2 [Olympiakos Pireo] => 2 [Dynamo Kiev] => 1 [Fc Copenhagen] => 1 [Amburgo] => 1 [Anderlecht] => 1 ) )
$vettore_risposte_esatte=$bulk_punteggi[$indice_regola_eliminatorie][1];

// squadre associate a ciascun gruppo (es. Array ( [4] => Array ( [0] => Barcellona [1] => Bayern Monaco [2] => Galatasaray [3] => Olympiakos Pireo [4] => Dynamo Kiev [5] => Fc Copenhagen [6] => Amburgo [7] => Anderlecht ) [3] => Array ( [0] => Barcellona [1] => Bayern Monaco [2] => Galatasaray [3] => Olympiakos Pireo ) [2] => Array ( [0] => Barcellona [1] => Bayern Monaco ) [1] => Array ( [0] => Barcellona ) ) )
$risposte_equivalenti=$bulk_punteggi[$indice_regola_eliminatorie][4];		// squadre associate a ciascun gruppo

// gruppi cui appartengono le varie domande (es. Array ( [0] => 4 [1] => 4 [2] => 4 [3] => 4 [4] => 4 [5] => 4 [6] => 4 [7] => 4 [8] => 3 [9] => 3 [10] => 3 [11] => 3 [12] => 2 [13] => 2 [14] => 1 ) )
//$gruppo_risposta = $bulk_punteggi[$indice_regola_esatte_per_gruppi][0]; // equivalente alla riga sottostante
$gruppo_risposta = $bulk_punteggi[$indice_regola_punteggi_specifici][2];

$num_risposte_squadre = count($gruppo_risposta); // numero di risposte relative a squadre (dalle qualificate alle eliminatorie fino al vincitore)

// punteggio da assegnate a ciascuna risposta per ciascun gruppo (es. Array ( [0] => Array ( [Barcellona] => 4 [Bayern Monaco] => 4 [Galatasaray] => 4 [Olympiakos Pireo] => 4 [Dynamo Kiev] => 4 [Fc Copenhagen] => 4 [Amburgo] => 4 [Anderlecht] => 4 ) [1] => Array ( [Barcellona] => 4 [Bayern Monaco] => 4 [Galatasaray] => 4 [Olympiakos Pireo] => 4 [Dynamo Kiev] => 4 [Fc Copenhagen] => 4 [Amburgo] => 4 [Anderlecht] => 4 ) ... ) )
$punteggio_specifico = $bulk_punteggi[$indice_regola_punteggi_specifici][0];

// estremi data di giocata minima e massima ai fini dell'ordinamento (prima 2 stringhe hh:mm gg/mm/aaaa, poi 2 corrispondenti val. numerici,
// es. Array ( [0] => 24:00 01/01/2007 [1] => 24:00 31/01/2007 [2] => 1167696000 [3] => 1170288000 )  )
$estremi_date = Array($bulk_punteggi[$indice_regola_data_giocata][1],$bulk_punteggi[$indice_regola_data_giocata][2]);
$temp_min = parse_date($estremi_date[0]);
$temp_max = parse_date($estremi_date[1]);
$estremi_date = Array($estremi_date[0],$estremi_date[1],$temp_min[0],$temp_max[0]);


//
// ricalcola il punteggio (e aggiungilo in una colonna in fondo)
//

// scomponi l'archivio delle giocate:
$elenco_giocate2 = array_slice($elenco_giocate,1);	// giocate effettive (dal secondo elemento in poi)

$elenco_giocate3 = array_slice($elenco_giocate,0,1);	// inizia con gli headers soltanto, poi aggiungi tutte le giocate rielaborate
array_push($elenco_giocate3[0],'Numero risposte esatte');	// aggiungi il titolo per l'ultima colonna da aggiungere (punteggio visibile)
$archivio_punti = Array();	// archivio dei punteggi ordinati (uno per ogni 'x' o '-'
foreach ($elenco_giocate2 as $indice_giocata => $giocata)
{
	$giocata_new = $giocata;
	$vettore_giocata = explode(',',$giocata[1]);	// crea un array con le singole giocate
	$vettore_giocata_new = $vettore_giocata;	// andra' riordinato per avvicinare le x

	$punteggio = 0;
	$bulk_gruppi = Array();
	$lista_indici = Array(); // elenco degli indici delle risposte appartenenti allo stesso gruppo
	$lista_risposte = Array(); // elenco delle risposte appartenenti allo stesso gruppo
	$lista_punti = Array(); // elenco dei punti associati alle risposte appartenenti allo stesso gruppo
	$lista_punti_per_ordinamento = Array(); // elenco dei punti ai fini dell'ordinamento delle colonne
	$vettore_punti = Array(); // elenco dei punti associati a tutte le giocate per cui ci sono putneggi specifici
	$gruppo_old = -1; // valore fuori range in modo da capire che il ciclo non e' ancora iniziato
	foreach($gruppo_risposta as $indice_risposta => $gruppo)
	{
		$risposta = $vettore_giocata[$indice_risposta];
		if (strlen((string)$punteggio_specifico[$indice_risposta][$risposta])==0) // non e' indicato il punteggio...
		{
			$punti = '-';
			$punti_per_ordinamento = -1e6; // ...le partite non ancora disputate vanno a destra
		}
		else
		{
			$punti = (integer)($punteggio_specifico[$indice_risposta][$risposta]);
			$punti_per_ordinamento = $punti;
		}
		
		$vettore_punti[$indice_risposta] = $punti;
		
		if ( (($gruppo_old >= 0) & ($gruppo_old != $gruppo)) | (count($gruppo_risposta) == $count) )
		{
			$bulk_gruppi[$gruppo_old] = Array($lista_indici,$lista_risposte,$lista_punti,$lista_punti_per_ordinamento);
			
			$lista_indici = Array($indice_risposta);
			$lista_risposte = Array($risposta);
			$lista_punti = Array($punti);
			$lista_punti_per_ordinamento = Array($punti_per_ordinamento);
		}
		else
		{
			array_push($lista_indici,$indice_risposta);
			array_push($lista_risposte,$risposta);
			array_push($lista_punti,$punti);
			array_push($lista_punti_per_ordinamento,$punti_per_ordinamento);
		}
		
		$punteggio += $punti;
		
		$gruppo_old = $gruppo;
	}
	$bulk_gruppi[$gruppo_old] = Array($lista_indici,$lista_risposte,$lista_punti,$lista_punti_per_ordinamento);
	
	foreach ($bulk_gruppi as $gruppo => $dati_gruppo)
	{
		$lista_indici = $dati_gruppo[0];
		$lista_risposte = $dati_gruppo[1];
		$lista_punti = $dati_gruppo[2];
		$lista_punti_per_ordinamento = $dati_gruppo[3];
		
		array_multisort($lista_punti_per_ordinamento,SORT_DESC,$lista_punti,$lista_risposte); // riordina le risposte (ed i relativi punteggi)
		
		foreach($lista_indici as $indice_temp => $indice_risposta)
		{
			$vettore_giocata_new[$indice_risposta] = $lista_risposte[$indice_temp];	// ordina le giocate ...
			$vettore_punti[$indice_risposta] = $lista_punti[$indice_temp];		// ...ed i relativi punti
		}
	}
	
	$giocata_riordinata = implode(',',$vettore_giocata_new);
	
	// aggiorna giocata new
	$giocata_new[1]=$giocata_riordinata; 		// 1) sostituisci la vecchia giocata con quella riordinata
	$giocata_new[count($giocata)] = $punteggio; 	// 2) nell'ultima colonna aggiungo il punteggio esatto da visualizzare nella classifica
	
	// ed inseriscila in archivio
	array_push($elenco_giocate3,$giocata_new);

	array_push($archivio_punti,$vettore_punti);	// aggiungi il vettore dei punti per questa giocata

}
$elenco_giocate = $elenco_giocate3;


// aggiungi a mask l'indice dell'ultima colonna
$mask=array_merge($mask,Array(count($giocata)));

// // se si parte dai quarti:
// $id_field_cognome       = 15;
// $id_field_nome          = 16;
// $id_field_data_nascita  = 17;
// $id_field_provenienza   = 18;
// 
// // se si parte dagli ottavi:
// $id_field_cognome       = 31;
// $id_field_nome          = 32;
// $id_field_data_nascita  = 33;
// $id_field_provenienza   = 34;

$id_field_cognome       = $num_risposte_squadre+0;
$id_field_nome          = $num_risposte_squadre+1;
$id_field_data_nascita  = $num_risposte_squadre+2;
$id_field_provenienza   = $num_risposte_squadre+3;


// alias delle varie risposte
$vettore_alias_domanda = array(
	'W','W','W','W','W','W','W','W','W','W','W','W','W','W','W','W',
	'Q','Q','Q','Q','Q','Q','Q','Q',
	'S','S','S','S',
	'F','F','C');
$vettore_alias2_domanda = array(
	'Ammesse agli ottavi', 'Ammesse agli ottavi', 'Ammesse agli ottavi', 'Ammesse agli ottavi', 'Ammesse agli ottavi', 'Ammesse agli ottavi', 'Ammesse agli ottavi', 'Ammesse agli ottavi','Ammesse agli ottavi', 'Ammesse agli ottavi', 'Ammesse agli ottavi', 'Ammesse agli ottavi', 'Ammesse agli ottavi', 'Ammesse agli ottavi', 'Ammesse agli ottavi', 'Ammesse agli ottavi',
	'Ammesse ai quarti', 'Ammesse ai quarti', 'Ammesse ai quarti', 'Ammesse ai quarti', 'Ammesse ai quarti', 'Ammesse ai quarti', 'Ammesse ai quarti', 'Ammesse ai quarti',
	'Ammesse alle semifinali', 'Ammesse alle semifinali', 'Ammesse alle semifinali', 'Ammesse alle semifinali',
	'Ammesse in finale', 'Ammesse in finale', 'Squadra vincitrice');
// e peso corrispondente per la visualizzazione
$vettore_alias_id = $gruppo_risposta; // prendi i raggruppamenti dalla regola punteggi_specifici (es. array(4,4,4,4,4,4,4,4,3,3,3,3,2,2,1); )

// indice delle risposte ordinate per importanza (secondo $vettore_alias_id)
$list = $vettore_alias_id;
$list0=array_keys($list);
array_multisort($list,SORT_DESC,$list0);


// crea i titoli della nuova matrice (in accordo con $record_new creato sotto)
$header_new = array();
array_push($header_new,'Id');
array_push($header_new,'Punti');
array_push($header_new,'Data');
array_push($header_new,'Giocatore');
array_push($header_new,'Codice');
array_push($header_new,'Tipo giocata');
array_push($header_new,'Pos.');
array_push($header_new,'Comune di origine');

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

// indica il campo della giocata su cui effettuare colorazioni personalizzate (uno dei campi di elenco_giocate[0], partendo da 0)
$id_campo_per_stile = 1;


// print_r($elenco_stili);

// crea nuova tabella
$elenco_new = array($header_new);
$elenco_giocate2 = array_slice($elenco_giocate,1);
$elenco_simboli_usati = Array(); // serve per la legenda finale
$count = 0;
foreach ($elenco_giocate2 as $indice_giocata => $giocata)
{
	// i campi qui devono essere gli stessi e nello stesso ordine di quelli indicati sopra per $header_new
	$id_della_giocata = $giocata[0];
	$vettore_giocata = explode(',',$giocata[1]);
	$data_giocata = $giocata[3];
	$auth_token_ks = $giocata[4];

	$cognome 	= $vettore_giocata[$id_field_cognome];
	$cognome 	= prime_lettere_maiuscole(strtolower($cognome));
	
	$nome 		= $vettore_giocata[$id_field_nome];
	$nome 		= prime_lettere_maiuscole(strtolower($nome));
	
	$data_nascita = $vettore_giocata[$id_field_data_nascita];
	$anno_nascita 	= substr($data_nascita,-2);
	
	$provenienza0 = prime_lettere_maiuscole(strtolower($vettore_giocata[$id_field_provenienza]));
	$provenienza = "<div align=\"left\" style=\"margin-left:10pt;\">$provenienza0</div>";
	
	$tooltip = addslashes("$nome $cognome, di $provenienza0, nato il $data_nascita");
	
	$giocatore_ks = "<span title=\"$tooltip\">$cognome $nome &acute;$anno_nascita</span>";
	$giocatore_ks = "<div align=\"left\" style=\"margin-left:10pt;\">".$giocatore_ks."</div>";

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
	
	// rielaborazione data giocata da visualizzare
	
	// saturazione data minima e massima di giocata
	$temp = parse_date($data_giocata);
// 	echo "$data_giocata -> {$temp[0]},{$estremi_date[2]},{$estremi_date[3]}<br>\n";
	if ($temp[0] < $estremi_date[2]) {$data_giocata = $estremi_date[0];}
	if ($temp[0] > $estremi_date[3]) {$data_giocata = $estremi_date[1];}	
	
	// inverti data e ora nella data di giocata
	$data_giocata = substr($data_giocata,strpos($data_giocata,' ')+1).' '.substr($data_giocata,0,strpos($data_giocata,' '));
	$data_giocata = substr($data_giocata,0,-6);	// non visualizzare ora e minuti della giocata

	
	// crea giocata
	$record_new = array();
	array_push($record_new,$id_della_giocata);	// field 0
	array_push($record_new,$punteggio);		// field 1
	array_push($record_new,$data_giocata);		// field 2
	array_push($record_new,$giocatore_ks);		// field 3
	array_push($record_new,$auth_token_ks);		// field 4
	array_push($record_new,$tipo_giocata_ks);	// field 5
	array_push($record_new,$count);			// field 6
	array_push($record_new,$provenienza);		// field 7
	$last_fixed_field = 7;	// pari all'ultimo indice qui sopra (numero elementi meno 1), bisogna aggiornare anche $header_new
	
	// stile riga
	unset($stile_riga);
	if ($lotteria_auth == 'key')
	{
		// se il sondaggio e' basato su codice segreto, vedi la nota associata alla singala chiave
		$found_key = check_question_keys($id_questions,$auth_token_ks);
		$stile_riga = $found_key[2][4];
	}
	else
	{
		// altrimenti basa lo stile sulla colonna configurabile
		$stile_testo = $giocata[$id_campo_per_stile];
		
		foreach ($elenco_stili as $stile_tag => $stile_data)
		{
			$stile_caption 	= $stile_data[0];
			$stile_style 	= $stile_data[1];
			
			if (empty($stile_tag) || preg_match("~$stile_tag~", $stile_testo))
			{
				$stile_riga = $stile_style;
				$stile_riga = $stile_tag;
				break;
			}
		}
	}
	if (!empty($stile_riga))
	{
		$record_new['stile_riga'] = $elenco_stili[$stile_riga][1];
	}
	
	
	// simboli quando si azzecca una risposta
	$simbolo_ok 	= Array(
		'4' 		=> Array('V'		,'','Qualificazione con piu\' punti - Vittoria diretta (4 punti)'),
		'3' 		=> Array('S'		,'','Qualificazione per scontro diretto, diff. reti - Vittoria ai supplementari (3 punti)'),
		'2' 		=> Array('R'		,'','Qualificazione per miglior attacco, class. avulsa, ecc. - Vittoria ai rigori (2 punti)'),
		'1' 		=> Array('+'		,'','???'),
		'default'	=> Array('.'		,'','')
		);

	$simbolo_not_ok	= Array(
		'4' 		=> Array('<b>O</b>'	,'','??'),
		'3' 		=> Array('O'		,'','??'),
		'2' 		=> Array('r'		,'','Eliminazione per miglior attacco, class. avulsa, ecc. - Sconfitta ai rigori (2 punti)'),
		'1' 		=> Array('s'		,'','Eliminazione per scontro diretto, diff. reti - Vittoria ai supplementari (1 punto)'),
		'0'		=> Array('<small>o</small>'		,'','Eliminazione con meno punti - Sconfitta diretta (0 punti)'),
		'default'	=> Array('.'		,'','')
		);
	
	foreach ($list0 as $id)
	{
		$squadra = $soluz[$id];	// valore esatto per la risposta in esame
		$gruppo=$list[$id];	// gruppo (4,3,2,1) della risposta
		
		$punti = $archivio_punti[$indice_giocata][$id];	// punteggio associato alla 'x' o '-'
		
		if (in_array($vettore_giocata[$id],$risposte_equivalenti[$gruppo]))
		{
			// se la risposta e' corretta...
			
			$tipo_simbolo = 'ok';
			$simbolo_item = $simbolo_ok;
			if ($punti > 0)
			{
				$item_simbolo = $simbolo_item[$punti];
			}
			else
			{
				$item_simbolo = $simbolo_item['default'];
			}
			
			$simbolo = $item_simbolo[0];
			$stile_simbolo = $item_simbolo[1];
			
			if ($punti != 1) {$punti_msg = "$punti punti";}
			else {$punti_msg = "$punti punto";}
			
			$simb = "<span title=\"".$vettore_giocata[$id]." ($punti_msg)\" $stile_simbolo>$simbolo</span>\n";
		}
		else
		{
			// ... altrimenti differenzia simboli per i punti
			
			$tipo_simbolo = 'not_ok';
			$simbolo_item = $simbolo_not_ok;
			if ($punti !== '-') // se e' stato definito un punteggio (anche nullo)...
			{
				$item_simbolo = $simbolo_item[$punti];
				
				if ($punti != 1) {$punti_msg = "$punti punti";}
				else {$punti_msg = "$punti punto";}
			}
			else // altrimenti il match non ha dato ancora un punteggio: visualizza il puntino
			{
				$item_simbolo = $simbolo_item['default'];
				
// 				$punti_msg = 'il match relativo non è stato ancora disputato';
				$punti_msg = '0 punti';
			}
			
			$simbolo = $item_simbolo[0];
			$stile_simbolo = $item_simbolo[1];
			
			
			$simb = "<span title=\"".$vettore_giocata[$id]." ($punti_msg)\" $stile_simbolo>$simbolo</span>\n";
		}
		
		$indice_simbolo = $tipo_simbolo.'_'.$punti;
		if (!in_array($indice_simbolo,$elenco_simboli_usati))
		{
			array_push($elenco_simboli_usati,$indice_simbolo);
		}
		
		if ($flag_nascondi_pronostici)
		{
			$simb="$simbolo\n";
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


// $header_new:
// Id		0
// Punti	1
// Data		2
// Giocatore	3
// Codice	4
// Tipo giocata	5
// Pos.		6
// Provenienza	7
// X		8,...

// verifica che le giocate siano aperte e stampa il relativo messaggio
if ($v_now[0] > $v_results[0])
{
	$mask_new = array_merge(Array(6,0,3,7,1),range($last_fixed_field+1,$last_fixed_field+1+count($soluz)-1),Array(2)); // posiz., id, giocatore, provenienza, punti, x, data
}
else
{
	$mask_new = array_merge(Array(6,0,3,7,1),range($last_fixed_field+1,$last_fixed_field+1+count($soluz)-1),Array(2)); // posiz., id, giocatore, provenienza, punti, x, data
// 	$mask_new = array_merge(6,0,1,range($last_fixed_field+1,$last_fixed_field+1+count($soluz)-1),2); // posiz., id, punti, x, data
}
if ($debug_mode)
{
	$mask_new = array_merge(Array(6,0,1,2,3,4,5,7),range($last_fixed_field+1,$last_fixed_field+1+count($soluz)-1)); // tutti i campi
}

show_table($elenco_new,$mask_new,'tabella',1,12,1); # tabella in una colonna, font 12, con note
?>


<br>
<table summary="table_legenda" width=100%><tr valign="top">

<!-- Legenda gruppi -->
<td width=30%>
&nbsp;&nbsp;
Legenda:

<div class="txt_link">
<?php
foreach($vettore_alias_domanda as $id => $alias_domanda)
{
	if (($id == 0) || ($vettore_alias_domanda[$id] != $vettore_alias_domanda[$id-1]))
	{
?>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b><?php echo $vettore_alias_domanda[$id]; ?></b> : <?php echo $vettore_alias2_domanda[$id]; ?><br>
<?php
	}
}
?>
</div>

</td>


<!-- Legenda colori -->
<td>

&nbsp;&nbsp;
Legenda colori:
<div class="txt_link">
<table summary="table_legenda_colori"><tbody>
<?php
foreach ($lotteria['stili_riga'] as $id_stile => $stile_data)
{
	$stile_tag = $stile_data[0];
	$stile_caption = $stile_data[1];
	$stile_style = $stile_data[2];
	
	$elenco_stili[$stile_tag] = array($stile_caption,$stile_style);
	
	echo "<tr>";
	echo "<td valign=\"top\">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span style=\"$stile_style;border:1px solid;\">&nbsp;&nbsp;&nbsp;&nbsp;</span></td>";
	echo "<td valign=\"top\">:</td>";
	echo "<td>$stile_caption</td>";
	echo "</tr>";
}
?>
</tbody></table>
</div>

</td>


<!-- Legenda simboli -->
<td>

&nbsp;&nbsp;
Legenda simboli:
<div class="txt_link">
<table summary="table_legenda_simboli"><tbody>
<?php

$elenco_simboli_item = Array('ok'=>$simbolo_ok,'not_ok'=>$simbolo_not_ok);

if (count($elenco_simboli_usati)>1)
{
	foreach($elenco_simboli_item as $tipo_simbolo => $simboli_item)
	{
		foreach ($simboli_item as $punti => $item_simbolo)
		{
			$simbolo_value = $item_simbolo[0];
			$simbolo_style = $item_simbolo[1];
			$simbolo_testo = $item_simbolo[2];
			
			$chiave_simbolo = $tipo_simbolo.'_'.$punti;
			if (in_array($chiave_simbolo,$elenco_simboli_usati))
			{
				echo "<tr>\n";
				echo "<td align=\"right\">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span style=\"$simbolo_style\">$simbolo_value</span></td>";
				echo "<td> : $simbolo_testo</td>\n";
				echo "</tr>\n";
			}
		}
	}
}
?>
<tr>
<td align="right">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;.</td>
<td> : Match non ancora disputato - Squadra non qualificata</td>
</tr>
</tbody></table>
</div>

</td>

</tr></table>


<br>
<div class="txt_link">
&nbsp;&nbsp;Portando il cursore del mouse sopra i simboli nella tabella viene visualizzato il nome della squadra pronosticata e i punti corrispondenti
<?php
if ($flag_nascondi_pronostici)
{
	echo("(a partire dal $lotteria_fine_giocate)");
}
?>
</div>

<?php

echo "<hr>";
echo "<a href=\"custom/lotterie/lotteria_".sprintf("%03d",$id_questions)."_tpl_form.php?info_mode=1\">Visualizza tabellone</a>\n";


# logga il contatto
$counter = count_page("questions",array("COUNT"=>1,"LOG"=>1),$filedir_counter); # abilita il contatore, senza visualizzare le cifre, e fai il log

?>
