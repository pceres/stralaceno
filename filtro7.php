<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 TRANSITIONAL//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<?php

require_once('libreria.php');

# dichiara variabili
extract(indici());
?>
<head>
  <title><?php echo $web_title ?> - Albo d'oro</title>
  <meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1">
  <meta name="GENERATOR" content="Quanta Plus">
  <meta name="description" content="Albo d'oro della gara podistica: i vincitori di ciascuna edizione">
  <meta name="keywords" content="Vincitori, tempi record">
  <style type="text/css">@import "<?php echo $filename_css ?>";</style>
</head>
<body class="tabella">
  
<div class="titolo_tabella">Albo d'oro della <?php echo $race_name ?></div>
<hr>

<?php

$archivio = load_data($filename_tempi,$num_colonne_prestazioni);

$atleti = load_data($filename_atleti,$num_colonne_atleti);
$lista_edizioni=array();
$archivio = merge_tempi_atleti($archivio,$atleti,$lista_edizioni);

$lista_indici = array($indice_anno, $indice_posiz);
$archivio_ordinato = ordina_archivio($archivio,$lista_indici);

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
	$tabella[$puntatore][$indice_tempo] = "<div  style=\"text-decoration: underline;\">$tempo</div>";
	}

$tabella_rielaborata = fondi_nome_id($tabella, $indice_nome, $indice_id);


#$mask = array($indice_id,$indice_nome,$indice_posiz,$indice_tempo,$indice_anno);
$mask = array($indice_nome,$indice_tempo,$indice_anno);

show_table($tabella_rielaborata,$mask,'tabella');

echo $homepage_link;

# logga il contatto
$counter = count_page("albo_d_oro",array("COUNT"=>1,"LOG"=>1),$filedir_counter); # abilita il contatore, senza visualizzare le cifre, e fai il log

?>


</body>
</html>

