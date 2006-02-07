<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 TRANSITIONAL//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
  <title>Grafico tempi</title>
  <meta http-equiv="Expires" content="Mon, 26 Jul 1997 05:00:00 GMT">
  <meta http-equiv="Last-Modified" content="<?php echo gmdate("D, d M Y H:i:s"); ?> GMT">
  <meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1">
  <meta http-equiv="Cache-Control" content="no-store, no-cache, must-revalidate">
  <meta http-equiv="Cache-Control" content="post-check=0, pre-check=0">
  <meta http-equiv="Pragma" content="no-cache">
  <meta name="GENERATOR" content="Quanta Plus">
</head>
<body>

<?php
require_once('../libreria.php');
?>  

<div align="center"><h2>Archivio storico della <?php echo $race_name ?> - Grafico dei tempi</h2></div>
<hr>

<?php

# dichiara variabili
extract(indici());

include 'grafico.php';

$datafile = "grafico.txt";
$pngfile = "grafico.png";

$spessore_linea = 3; # spessore della linea relativa ai tempi di ogni atleta

//$tempo_max_grafico = 90.0; 

$archivio = load_data($filename_tempi,$num_colonne_prestazioni);
$atleti = load_data($filename_atleti,$num_colonne_atleti);


$elenco_id = array(); # id degli atleti da visualizzare
for ($i = 1; $i < count($atleti); $i++) {
	if ($_REQUEST[$i] == "on") {
		array_push($elenco_id,$i);
		}
	}


$elenco_colori = array("black","blue","yellow","pink"); # id degli atleti da visualizzare

$elenco_tempo_min = array();
$elenco_tempo_max = array();
$elenco_tempi_atleta = array();
for ($i = 1; $i < count($archivio); $i++) {
	$prestazione = $archivio [$i];
	
	$id = $prestazione[$indice_id];
	$anno = $prestazione[$indice_anno];
	$tempo = $prestazione[$indice_tempo];
	
	$tempo_numerico = tempo_numerico($tempo);
	
	if (strlen($tempo) > 4) { # se la prestazione ha un tempo regolare
		#individua tempo minimo per ogni anno
		if ( (!array_key_exists($anno,$elenco_tempo_min)) | ($elenco_tempo_min[$anno] > $tempo_numerico) ) {
			$elenco_tempo_min[$anno] = $tempo_numerico;
			}
		#individua tempo massimo per ogni anno (massimo $tempo_max_grafico minuti!)
		if ( (!array_key_exists($anno,$elenco_tempo_max)) | ($elenco_tempo_max[$anno] < $tempo_numerico) ) {
			$elenco_tempo_max[$anno] = ($tempo_numerico > $tempo_max_grafico) ? $tempo_max_grafico : $tempo_numerico;
			}
		#individua tempo atleta id
		if ( in_array($id,$elenco_id) ) {
			$elenco_tempi_atleta[$id][$anno] = $tempo_numerico;
			}
		}
	
	}

$anni = array_merge(array_keys($elenco_tempo_min),array_keys($elenco_tempo_max));
$primo_anno = min($anni);
$ultimo_anno = max($anni);

$tempo_min = floor(min($elenco_tempo_min)*0.9);
$tempo_max = $tempo_max_grafico; #45; #ceil(max($elenco_tempo_max)*1.1);

$handle = fopen($datafile, "w");

# dati grafico
fwrite($handle,"[title]\r\n");
fwrite($handle,"Grafico tempi $race_name\r\n");
fwrite($handle,"[x_label]\r\n");
fwrite($handle,"anno\r\n");
fwrite($handle,"[y_label]\r\n");
fwrite($handle,"tempo impiegato\r\n");
fwrite($handle,"[min_x_label]\r\n");
fwrite($handle,$primo_anno."\r\n");
fwrite($handle,"[max_x_label]\r\n");
fwrite($handle,$ultimo_anno."\r\n");
fwrite($handle,"[min_y_label]\r\n");
fwrite($handle,"$tempo_min\r\n");
fwrite($handle,"[max_y_label]\r\n");
fwrite($handle,"$tempo_max\r\n");
fwrite($handle,"[passo_griglia_x]\r\n");
fwrite($handle,( 1/($ultimo_anno-$primo_anno) )."\r\n");
fwrite($handle,"[passo_griglia_y]\r\n");
fwrite($handle,( 1/($tempo_max-$tempo_min) )."\r\n");
fwrite($handle,"[has_axes]\r\n");
fwrite($handle,"1\r\n");
fwrite($handle,"[has_grid]\r\n");
fwrite($handle,"1\r\n");
fwrite($handle,"[has_legend]\r\n");
fwrite($handle,"1\r\n");
fwrite($handle,"\r\n");


# linea tempi minimi
write_tempi($handle,"primi posti","red",1,$elenco_tempo_min);

# linee atleti
$count = 0;
foreach ($elenco_tempi_atleta as $id => $elenco_tempi) {
	write_tempi($handle,$atleti[$id][$indice2_nome],$elenco_colori[$count++],$spessore_linea,$elenco_tempi);
	}

# linea tempi massimi
write_tempi($handle,"ultimi posti","green",1,$elenco_tempo_max);


fclose($handle);



grafico(950,480,30,$datafile,$pngfile);
echo "<img src=\"".$site_abs_path."grafico/".$pngfile."\" title=\"grafico tempi $race_name\" alt=\"grafico tempi $race_name\">";
exit();


#####################
function write_tempi($handle,$label,$color,$thickness,$elenco_tempi) {

fwrite($handle,"[label]\r\n");
fwrite($handle,"$label\r\n");
fwrite($handle,"[line_color]\r\n");
fwrite($handle,"$color\r\n");
fwrite($handle,"[line_thickness]\r\n");
fwrite($handle,"$thickness\r\n");
fwrite($handle,"[line_style]\r\n");
fwrite($handle,"continuous\r\n");
fwrite($handle,"[(x,y)]\r\n");

$anni = array_keys($elenco_tempi);
for ($i = 0; $i < count($anni); $i++) {
	$ks = $anni[$i]."\t".$elenco_tempi[$anni[$i]]."\r\n";
	fwrite($handle,$ks);

	if (count($anni) == 1) {  # se partecipa per un solo anno, fa comparire un punto
		$ks = ($anni[$i]+0.05)."\t".$elenco_tempi[$anni[$i]]."\r\n";
		fwrite($handle,$ks);
		}
	}
fwrite($handle,"\r\n");
}

?>


</body>
</html>
