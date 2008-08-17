<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 TRANSITIONAL//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<?php

require_once('libreria.php');

# dichiara variabili
extract(indici());
?>
<head>
  <title><?php echo $web_title ?> - Medagliere</title>
  <meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1">
  <meta name="GENERATOR" content="Quanta Plus">
  <meta name="description" content="Medagliere">
  <meta name="keywords" content="classifica, medagliere">
  <style type="text/css">@import "<?php echo $filename_css ?>";</style>
</head>
<body class="tabella">

<div class="titolo_tabella">Medagliere</div>
<hr>

<?php


function array2string($arr)
{
	$ks = "";
	
	if (count($arr) > 0)
	{
		$ks = " (";
		foreach ($arr as $item)
		{
			$ks .= $item.", ";
		}
		$ks = substr($ks,0,count(ks)-3).")";
	}
	return $ks;
} // end function arr2string

$archivio = load_data($filename_tempi,$num_colonne_prestazioni);

$atleti = load_data($filename_atleti,$num_colonne_atleti);

$lista_edizioni=array();
$archivio = merge_tempi_atleti($archivio,$atleti,$lista_edizioni);

$last_pos = 3;
$lista_med = range(1,$last_pos); // interessano dal 1° al last_pos-simo posto

$med_init = Array();
foreach ($lista_med as $med_id)
{
	$med_init[(int)$med_id] = Array();
}

$medagliere     = Array();
$medagliere_val = Array();
foreach ($lista_edizioni as $anno)
{
	$lista_regola_campo = array($indice_anno);
	$lista_regola_valore = array($anno);
	$archivio_filtrato = filtra_archivio($archivio,$lista_regola_campo,$lista_regola_valore);
	
for ($i = 1; $i < count($archivio_filtrato); $i++)
{
	$id    = $archivio_filtrato[$i][$indice_id];
	$nome  = $archivio_filtrato [$i][$indice_nome];
	$posiz = $archivio_filtrato [$i][$indice_posiz];
	
	if (in_array($posiz,$lista_med))
	{
		$medaglia = $posiz+0;
	}
	else
	{
		$medaglia = 0;
	}
	
	if ($medaglia > 0)
	{
		$medaglia_val = pow(100,(count($lista_med)-$medaglia));
		$medaglie = $medagliere[$id];
		if (empty($medaglie))
		{
			$medaglie = Array('id' => $id, 'medaglie' => $med_init);
		}
		array_push($medaglie['medaglie'][$medaglia],$anno);
		$medagliere[$id] = $medaglie;
		
		$medagliere_val[$id] += $medaglia_val;
	}
}

}
array_multisort($medagliere_val,SORT_DESC,$medagliere);


// var_dump($medagliere);
$archivio2 = array();
foreach ($medagliere as $id0 => $medaglie)
{
	$pos        = ($id0+1)."&deg;";
	$id         = $medaglie['id'];
	$atleta  = "<div align=\"left\">".$atleti[$id][$indice2_nome]." ($id)</div>";
	
	$new_item = Array(0,0,0,0,$pos, $atleta);
	foreach($medaglie['medaglie'] as $indice_med_xxx => $med_xxx)
	{
		$ks_med_xxx = count($med_xxx).array2string($med_xxx);
		array_push($new_item,$ks_med_xxx);
	}
	
	$archivio2[$id0+1] = $new_item;
}

$head = Array('','','','','Pos.','Atleta (num.)','Med. oro','Med. argento','Med. bronzo');
for ($i = 3;$i < count($lista_med); $i++)
{
	array_push($head,$i+1);
}
$archivio2 = array_merge(Array($head),$archivio2);

if (count($archivio2) == 1)
{
	echo "<br><br>Non ci sono dati disponibili per l'edizione: $anno!";
	die();
}


$mask = range(4,count($head)-1); #scegli i campi da visualizzare
show_table($archivio2,$mask,'tabella',1,12,0,array()); # tabella in tre colonne, font 12, senza note

?>
<?php ?>

<br>


<?php

echo $homepage_link;
  
# logga il contatto
$counter = count_page("medagliere",array("COUNT"=>1,"LOG"=>1),$filedir_counter); # abilita il contatore, senza visualizzare le cifre, e fai il log

?>

</body>
</html>

