#!/usr/local/bin/php
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 TRANSITIONAL//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<?php

require_once('libreria.php');

# dichiara variabili
extract(indici());
?>
<head>
  <title><?php echo $web_title ?> - Classifica partecipazioni</title>
  <meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1">
  <meta name="GENERATOR" content="Quanta Plus">
  <meta name="description" content="Classifica partecipazioni">
  <meta name="keywords" content="classifica, numero di partecipazioni">
  <style type="text/css">@import "<?php echo $filename_css ?>";</style>
</head>
<body class="tabella">
  
<div class="titolo_tabella">Classifica partecipazioni</div>
<hr>

<?php

// punteggio dell'atleta in base ai vari anni di partecipazione
//
// criteri di ordinamento (valore di $mode):
//
//  0 : primo anno di partecipazione;
//  1 : media degli anni;
//  2 : deviazione standard (migliore distribuzione)
//  3 : prodotto cumulativo delle differenze tra i vari anni di partecipazione
//  4 : radice del prodotto cumulativo delle differenze tra i vari anni di partecipazione
//  5 : radice del prodotto cumulativo delle differenze tra i vari anni di partecipazionemoltiplicato per il numero di intervalli
//
function punteggio_presenze($dati,$mode)
{
	$anni = array();
	foreach ($dati as $edizione)
	{
		array_push($anni,$edizione[0]);
	}
	
	switch ($mode)
	{
	case 0: // primo anno di partecipazione
		return min($anni);
		break;
	case 1: // media degli anni di partecipazione
	case 2: // deviazione_standard
		$media = array_sum($anni)/count($dati);
		if ($mode == 1)
		{
			return $media;
		}	
		$dev = 0;
		//print_r($anni);
		foreach ($anni as $anno)
		{
			$dev += pow($anno-$media,2);
		}
		$dev = sqrt($dev/(count($anni)-1));
		//echo "$dev,$media";
		//die();
		return $dev;
		break;
	case 3: // prodotto incrementale
	case 4: // radice del prodotto incrementale
	case 5: // radice del prodotto incrementale per numero partecipazioni
		$area = 1;
		for ($i = 1; $i<count($anni);$i++)
		{
			$area *= $anni[$i]-$anni[$i-1];
		}
		if ($mode == 3)
		{
			return $area;
		}
		$area = pow($area,1.0/(count($anni)-1));
		if ($mode == 4)
		{
			//$area = pow($area,1.0/(count($anni)-1));
			return $area;
		}
		$area = pow($area,1.0/(count($anni)-1))*(count($anni)-1);
		return $area;
		break;
	default:
		die("Il modo $mode non e' definito!");
		break;
	}

} // end of function punteggio_presenze($dati,$mode)

//
// criteri di ordinamento:
//
//  0 : primo anno di partecipazione;
//  1 : media degli anni;
//  2 : deviazione standard (migliore distribuzione)
//  3 : prodotto cumulativo delle differenze tra i vari anni di partecipazione
//  4 : radice del prodotto cumulativo delle differenze tra i vari anni di partecipazione
//  5 : radice del prodotto cumulativo delle differenze tra i vari anni di partecipazionemoltiplicato per il numero di intervalli
//
$mode = $_REQUEST['mode'];
if (empty($mode)) // per default usa il prodotto incrementale (mode = 3)
{
	$mode = 3;
}

$archivio = load_data($filename_tempi,$num_colonne_prestazioni);

$indice_dati = $indice_nome+1;
$indice_presenze = $indice_nome+2;
$indice_punteggio = $indice_nome+3;
$indice_str_anni = $indice_nome+4;
$indice_posizione = $indice_nome+5;

$archivio2 = array();
$list_atleti = array();
for ($i = 1; $i < count($archivio); $i++) 
{
	$id = $archivio[$i][$indice_id];
	
	// non considerare la prestazione se non e' stata portata a termine regolarmente, con un tempo ufficiale minore del tempo massimo
	if (tempo_numerico($archivio[$i][$indice_tempo]) >= 500) 
	{
		continue;
	}
	
	$indice = array_search($id,$list_atleti);
	#echo "|$indice|";
	$item2 = array($archivio[$i][$indice_anno],$archivio[$i][$indice_posiz]); // dati interessanti per ogni edizione al fine del calcolo del punteggio partecipazioni
	if (strlen($indice) > 0)
	{
		$old_item = $archivio2[$indice];
		
		// aggiorna i campi con i dati della edizione i-esima
		$old_item[$indice_presenze]++;
		array_push($old_item[$indice_dati],$item2); // aggiungi questa edizione all'elenco degli anni
		$punteggio = substr(punteggio_presenze($old_item[$indice_dati],$mode),0,6);
		$old_item[$indice_punteggio]= $punteggio;
		$old_item[$indice_str_anni].= ", ".$archivio[$i][$indice_anno];
		
		$new_item = $old_item;
	}
	else
	{
		$item1 = array_slice($archivio[$i],0,$indice_nome+1);
		$punteggio = punteggio_presenze(array($item2),$mode).'';
		$new_item = array_merge($item1,array(array($item2)),1,$punteggio.'',$archivio[$i][$indice_anno],1);
		array_push($list_atleti,$id);
		$indice = count($archivio2);//-1;
	}
	$archivio2[$indice] = $new_item;	

	$archivio[$i][$indice_presenze] = $new_item[count($new_item)-1];
}

$head = array_merge(array_slice($archivio[0],0,$indice_nome+1),'Pos.','Numero<br>presenze<br>regolari','Punteggio<br>regolarit&agrave;','Tooltip','Pos.');
$archivio2 = array_merge(array($head),$archivio2);

$atleti = load_data($filename_atleti,$num_colonne_atleti);
$archivio = merge_tempi_atleti($archivio2,$atleti);


$archivio_ordinato = ordina_archivio($archivio,$indice_presenze,$indice_punteggio,SORT_DESC);

for ($i=1; $i<count($archivio_ordinato);$i++)
{
	if (($i == 1) || ($archivio_ordinato[$i][$indice_presenze]!=$archivio_ordinato[$i-1][$indice_presenze]) || ($archivio_ordinato[$i][$indice_punteggio]!=$archivio_ordinato[$i-1][$indice_punteggio]))
	{
		//$archivio_ordinato[$i][$indice_posizione] = $i;	
		$archivio_ordinato[$i][$indice_posiz] = $i;	
	}
	else
	{
		//$archivio_ordinato[$i][$indice_posizione] = $archivio_ordinato[$i-1][$indice_posizione];	
		$archivio_ordinato[$i][$indice_posiz] = $archivio_ordinato[$i-1][$indice_posiz];	
	}
}


$archivio_rielaborato = fondi_nome_id($archivio_ordinato, $indice_nome, $indice_id);


$mask = array($indice_posiz,$indice_presenze,$indice_punteggio,$indice_nome); #scegli i campi da visualizzare
show_table($archivio_rielaborato,$mask,'tabella',2,12,0,array($indice_punteggio=>$indice_str_anni)); # tabella in tre colonne, font 12, senza note

?>

<br>

<div align="justify" style="font-size: 0.75em;font-style:italic;">
La classifica &egrave; realizzata considerando prima di tutto il numero di percorsi regolarmente ultimati 
entro il tempo massimo, poi la cadenza regolare delle partecipazioni, indipendentemente dalle performance. 

<?php if ($mode==3) { ?>
Il punteggio regolarit&agrave;
&egrave; calcolato moltiplicando via via la differenza tra l'anno di partecipazione ed il successivo. Ad es.
un atleta che abbia partecipato nel 1999, 2002 e 2004 avr&agrave; un punteggio regolarit&agrave; pari a 
(2004-2002)x(2002-1999) = 2x3 = 6.
<?php } // end if ?>

</div>

<?php

echo $homepage_link;
  
# logga il contatto
$counter = count_page("classifica_partecipazioni",array("COUNT"=>1,"LOG"=>1),$filedir_counter); # abilita il contatore, senza visualizzare le cifre, e fai il log

?>

</body>
</html>

