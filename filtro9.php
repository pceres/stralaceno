<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 TRANSITIONAL//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<?php

require_once('libreria.php');

# dichiara variabili
extract(indici());
?>
<head>
  <title><?php echo $web_title ?> - Classifica record personali</title>
  <meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1">
  <meta name="GENERATOR" content="Quanta Plus">
  <style type="text/css">@import "<?php echo $filename_css ?>";</style>
</head>
<body class="tabella">
  
<div class="titolo_tabella">Classifica record personali</div>
<hr>

<?php

$archivio = load_data($filename_tempi,$num_colonne_prestazioni);

$atleti = load_data($filename_atleti,$num_colonne_atleti);
$archivio = merge_tempi_atleti($archivio,$atleti);

$archivio_ordinato = ordina_archivio($archivio,$indice_tempo, $indice_anno);



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
show_table($archivio_rielaborato,$mask,'tabella',3,12,0); # tabella in tre colonne, font 12, senza note

# logga il contatto
$counter = count_page("classifica_generale_MF",array("COUNT"=>1,"LOG"=>1),$filedir_counter); # abilita il contatore, senza visualizzare le cifre, e fai il log

?>

<br>
<table class="tabella_legenda">
	<tr>
		<td>F.T.M.</td>
		<td>:</td>
		<td class="descrizione">fuori tempo massimo (<?php echo $tempo_max_M ?> minuti uomini, <?php echo $tempo_max_F ?> minuti donne)</td>
	</tr>
	<tr>
		<td>Rit.</td>
		<td>:</td>
		<td class="descrizione">ritirato</td>
	</tr>
	<tr>
		<td>Squ..</td>
		<td>:</td>
		<td class="descrizione">squalificato</td>
	</tr>
</table>


</body>
</html>

