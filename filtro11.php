#!/usr/local/bin/php
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 TRANSITIONAL//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<?php

require_once('libreria.php');

# dichiara variabili
extract(indici());
?>
<head>
  <title>Stralaceno Web - Classifica partecipazioni</title>
  <meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1">
  <meta name="GENERATOR" content="Quanta Plus">
  <style type="text/css">@import "<?php echo $css_site_path ?>/stralaceno.css";</style>
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
			break;
		}	
		$dev = 0;
		//print_r($anni);
		foreach ($anni as $anno)
		{
			$dev += pow($anno-$media,2);
		}
		$dev = sqrt($dev);
		//echo "$dev,$media";
		//die();
		return $dev;
		break;
	case 3: // prodotto incrementale
		$area = 1;
		for ($i = 1; $i<count($anni);$i++)
		{
			$area *= $anni[$i]-$anni[$i-1];
		}
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
//
$mode = $_REQUEST['mode'];

$archivio = load_data($filename_tempi,$num_colonne_prestazioni);

$indice_dati = $indice_nome+1;
$indice_presenze = $indice_nome+2;
$indice_punteggio = $indice_nome+3;
$indice_str_anni = $indice_nome+4;

$archivio2 = array();
$list_atleti = array();
for ($i = 1; $i < count($archivio); $i++) 
//for ($i = 1; $i <= 440; $i++) 
{
	$id = $archivio[$i][$indice_id];
	
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
#		$archivio2[$indice] = $old_item;		
	}
	else
	{
		$item1 = array_slice($archivio[$i],0,$indice_nome+1);
		$punteggio = punteggio_presenze(array($item2),$mode).'';
		$new_item = array_merge($item1,array(array($item2)),1,$punteggio.'',$archivio[$i][$indice_anno]);
		array_push($list_atleti,$id);
		$indice = count($archivio2);//-1;
		#array_push($archivio2,$new_item);
		
	}
	$archivio2[$indice] = $new_item;	

	$archivio[$i][$indice_presenze] = $new_item[count($new_item)-1];
	//echo $archivio[$i][$indice_posiz];
}

$head = array_merge(array_slice($archivio[0],0,$indice_nome+1),'Dati','Presenze','Punteggio','Tooltip');
$archivio2 = array_merge(array($head),$archivio2);

//echo "$indice_posiz,$indice_nome<br>";
$atleti = load_data($filename_atleti,$num_colonne_atleti);
$archivio = merge_tempi_atleti($archivio2,$atleti);


$archivio_ordinato = ordina_archivio($archivio,$indice_presenze,$indice_punteggio);
//$archivio_ordinato = ordina_archivio($archivio,$indice_tempo, $indice_anno);

$archivio_ordinato2=array_reverse($archivio_ordinato);
$archivio_ordinato = array_merge(array($archivio_ordinato[0]),array_slice($archivio_ordinato2,0,count($archivio_ordinato2)-1));

$archivio_rielaborato = fondi_nome_id($archivio_ordinato, $indice_nome, $indice_id);

//print_r($archivio_rielaborato[1]);
//die();

$mask = array($indice_presenze,$indice_punteggio,$indice_nome); #scegli i campi da visualizzare
show_table($archivio_rielaborato,$mask,'tabella',3,12,0,array($indice_punteggio=>$indice_str_anni)); # tabella in tre colonne, font 12, senza note

# logga il contatto
$counter = count_page("classifica_partecipazioni",array("COUNT"=>1,"LOG"=>1),$filedir_counter); # abilita il contatore, senza visualizzare le cifre, e fai il log

?>

</body>
</html>

