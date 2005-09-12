<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 TRANSITIONAL//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<?php

require_once('libreria.php');

# dichiara variabili
extract(indici());
?>
<head>
  <title><?php echo $web_title ?> - Archivio storico</title>
  <meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1">
  <meta name="GENERATOR" content="Quanta Plus">
</head>
<body>
  
<div align="center"><h2>Archivio storico completo della <?php echo $race_name ?> - Tabella sinottica</h2></div>
<hr>

<?php

$archivio = load_data($filename_tempi,$num_colonne_prestazioni);

$atleti = load_data($filename_atleti,$num_colonne_atleti);
#$lista_edizioni=array();
#$archivio = merge_tempi_atleti($archivio,$atleti,$lista_edizioni);

$atleti = fondi_nome_id($atleti, $indice_nome, $indice_id);

$tabella = array();
$elenco_anni = array();
for ($i = 1; $i < count($archivio); $i++) {
	$prestazione = $archivio [$i];
	
	$id = $prestazione[$indice_id];
	
	if (count($tabella[$id]['info']) == 0) {
		$tabella[$id]['info'] = $atleti[$id];
		#echo 'no data';
		}
	
	$tempi_id = $tabella[$id];
	$tempi_id[$prestazione[$indice_anno]] = $prestazione;
	$tabella[$id] = $tempi_id;
	
	#individua anni presenti in archivio
	if (!in_array($prestazione[$indice_anno],$elenco_anni)) {
		array_push($elenco_anni,$prestazione[$indice_anno]);
		}
	}


$head = array_merge("Atleta (ID)",$elenco_anni);

# disegna tabella
echo "<table border=\"2\">\n";
 
echo "  <tbody>\n";

echo " <thead><tr>\n";

for ($i = 0; $i < count($head); $i++) {
	echo "<th  $style_titoli_tabella align=\"center\">".$head[$i]."</th>";
	}

echo "  </tr></thead>\n";

echo "  <tbody>\n";
$note = array();
foreach ($tabella as $atleta) {


	# stile riga:
	$style_row = "style=\"font-size: 12;";
	
	# atleti donna con sfondo rosa
	if ($atleta['info'][$indice2_sesso] == "F") {
		$style_row .= "background-color: $style_sfondo_femmine;";
		}

	# atleti maschi con sfondo celeste
	if ($atleta['info'][$indice2_sesso] == "M") {
		$style_row .= "background-color: $style_sfondo_maschi;";
		}

	$style_row .= "\"";

	echo "<tr ".$style_row.">";



	for ($i = 0; $i < count($head); $i++) {
	
		if ($i == 0) { # colonna del nome
			$valore = $atleta['info'][$indice2_nome];
			
			if (mostro_link($atleta['info']) == TRUE) {
				$valore = "<div align=\"left\"><a href=\"info.php?id=".$atleta['info'][$indice2_id]."\">".$valore."</a></div>";
				}
			else {
				$valore = "<div align=\"left\">".$valore."</div>";
				}
			}
		else {
			$tempo = $atleta[$head[$i]][$indice_tempo];
			$posiz = $atleta[$head[$i]][$indice_posiz];
			if ((strlen($tempo)>1) & ($posiz != '-')) {
				if ($posiz == 1) # per il primo arrivato dati in grassetto
					$cell_style = "style=\"font-weight: bold;\"";
				else
					$cell_style = "";
				$valore = "<span $cell_style>$tempo <span style=\"font-style: italic;\"><small>($posiz<sup>o</sup>)</small></span></span>";
				}
			else {
				$valore = $tempo;
				}
			if (count($valore) == 0) {
				$valore = "<br>";
				}
				
			# eventuale nota
			$nota = trim($atleta[$head[$i]][$indice_nota]);
			if (strlen($nota) > 0) {
				$id_nota = count($note)+1;
				$ks = "<a href=\"#nota_".$id_nota."\">&sect;".$id_nota."</a>";
				$valore .= " <br> ".$ks."";
				
				array_push($note,$nota);
				}
			$valore = "<div align=\"center\">".$valore."</div>";
			}
		echo "<td>".$valore."</td>";
		}

	echo "</tr>\n\n";
	}

echo "  </tbody>\n";
echo "</table>\n";

# mostra note
if (count($note) > 0) {
	echo "<br>\n";
	echo "<small>\n";
	echo "Note:<br>\n";
	for ($i = 0; $i < count($note); $i++) {
		echo "<a name=\"nota_".($i+1)."\"><span style=\"color: rgb(255, 0, 0);\">&sect;".($i+1)."</span></a>: ".$note[$i]."<br>\n";
		}
	echo "</small>\n";
	}

?>


</body>
</html>

