<!DOCTYPE public "-//w3c//dtd html 4.01 transitional//en" 
		"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
  <title>Stralaceno Web - Classifica record personali femminili</title>
  <meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1">
  <meta name="GENERATOR" content="Quanta Plus">
</head>
<body>
  
<div align="center"><h2>Classifica record personali femminili</h2></div>
<hr>

<?php

include 'libreria.php';

$archivio = load_data($filename_tempi,$num_colonne_prestazioni);

$atleti = load_data($filename_atleti,$num_colonne_atleti);
$archivio = merge_tempi_atleti($archivio,$atleti);

$lista_regola_campo = array(array($indice_info,$indice2_sesso));
$lista_regola_valore = array('F');
$archivio_filtrato = filtra_archivio($archivio,$lista_regola_campo,$lista_regola_valore);


$archivio_ordinato = ordina_archivio($archivio_filtrato,$indice_tempo, $indice_anno);



$archivio_record = array($archivio_ordinato[0]);
$elenco_id = array();
for ($i = 1; $i < count($archivio_ordinato); $i++) {
	$prestazione = $archivio_ordinato[$i];
	
	if (!in_array($prestazione[$indice_id],$elenco_id)) {
		array_push($elenco_id,$prestazione[$indice_id]);
		
		#verifica eventuale ripetizione della stessa posizione in graduatoria
		if ( ($i == 1) | ($prestazione[$indice_tempo] != $prev_prestazione[$indice_tempo]) ) {
			$posizione = count($elenco_id);
			}
		
		$prestazione[$indice_posiz] = $posizione; 
		array_push($archivio_record,$prestazione);
		
		$prev_prestazione = $prestazione;
		}
	}


$archivio_rielaborato = fondi_nome_id($archivio_record, $indice_nome, $indice_id);


#$mask = array($indice_id,$indice_nome,$indice_tempo,$indice_posiz,$indice_anno); # visualizza tutti i campi
#show_table($archivio_record,$mask);
$mask = array($indice_posiz,$indice_nome,$indice_tempo,$indice_anno); # visualizza tutti i campi
show_table($archivio_rielaborato,$mask,3,12);

?>

<br>
<table style="font-size: 12;">
	<tr>
		<td>F.T.M.</td>
		<td>:</td>
		<td style="font-style: italic;">fuori tempo massimo (40 minuti uomini, 45 minuti donne)</td>
	</tr>
	<tr>
		<td>Rit.</td>
		<td>:</td>
		<td style="font-style: italic;">ritirato</td>
	</tr>
	<tr>
		<td>Squ..</td>
		<td>:</td>
		<td style="font-style: italic;">squalificato</td>
	</tr>
</table>


</body>
</html>

