#!/usr/local/bin/php
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 TRANSITIONAL//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
  <title>Stralaceno Web - Albo d'oro</title>
  <meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1">
  <meta name="GENERATOR" content="Quanta Plus">
  <style type="text/css">@import "css/stralaceno.css";</style>
</head>
<body class="tabella">
  
<div class="titolo_tabella">Albo d'oro della Stralaceno</div>
<hr>

<?php

include 'libreria.php';

$archivio = load_data($filename_tempi,$num_colonne_prestazioni);

$atleti = load_data($filename_atleti,$num_colonne_atleti);
$archivio = merge_tempi_atleti($archivio,$atleti);
$archivio_ordinato = ordina_archivio($archivio,$indice_anno, $indice_posiz);

$tabella = array($archivio[0]);

if (array_key_exists($indice_info,$archivio[1])) {
	array_push($tabella[0],$indice_info);
	}

$miglior_tempo=1000;
$puntatore = -1;
for ($i = 1; $i < count($archivio_ordinato); $i++) {
	$prestazione = $archivio_ordinato[$i];
	if ($prestazione[$indice_posiz] == 1) {
		$record = $prestazione;
		
		if (array_key_exists($indice_info,$prestazione)) {
			$record[$indice_info] = $prestazione[$indice_info];
			}
		array_push($tabella,$record);
		
		# individua il record assoluto
		$tempo = tempo_numerico($prestazione[$indice_tempo]);
		if ($tempo < $miglior_tempo) {
			$miglior_tempo = $tempo;
			$puntatore = count($tabella)-1;
			}
		
		}
	}
# evidenzia il record assoluto
if ($puntatore != -1) {
	$tempo = $tabella[$puntatore][$indice_tempo];
	$tabella[$puntatore][$indice_tempo] = "<h><div  style=\"text-decoration: underline;\">$tempo</div></h>";
	}

$tabella_rielaborata = fondi_nome_id($tabella, $indice_nome, $indice_id);


#$mask = array($indice_id,$indice_nome,$indice_posiz,$indice_tempo,$indice_anno);
$mask = array($indice_nome,$indice_tempo,$indice_anno);

show_table($tabella_rielaborata,$mask,'tabella');

# logga il contatto
$counter = count_page("albo_d_oro",array("COUNT"=>1,"LOG"=>1)); # abilita il contatore, senza visualizzare le cifre, e fai il log

?>


</body>
</html>

