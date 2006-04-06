<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 TRANSITIONAL//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<?php

require_once('libreria.php');

# dichiara variabili
extract(indici());
?>
<head>
  <title><?php echo $web_title ?> - Classifica record personali femminili</title>
  <meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1">
  <meta name="GENERATOR" content="Quanta Plus">
  <meta name="description" content="Classifica dei record personali di ciascun atleta femminile">
  <meta name="keywords" content="record, personale, femminile, miglior tempo">  
  <style type="text/css">@import "<?php echo $filename_css ?>";</style>
</head>
<body class="tabella">
  
<div class="titolo_tabella">Classifica record personali femminili</div>
<hr>

<?php

$archivio = load_data($filename_tempi,$num_colonne_prestazioni);

$atleti = load_data($filename_atleti,$num_colonne_atleti);
$lista_edizioni=array();
$archivio = merge_tempi_atleti($archivio,$atleti,$lista_edizioni);

$lista_regola_campo = array(array($indice_info,$indice2_sesso));
$lista_regola_valore = array('F');
$archivio_filtrato = filtra_archivio($archivio,$lista_regola_campo,$lista_regola_valore);


$lista_indici = array($indice_tempo, $indice_anno);
$archivio_ordinato = ordina_archivio($archivio_filtrato,$lista_indici);



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

echo $homepage_link;

# logga il contatto
$counter = count_page("classifica_generale_F",array("COUNT"=>1,"LOG"=>1),$filedir_counter); # abilita il contatore, senza visualizzare le cifre, e fai il log

?>

</body>
</html>

