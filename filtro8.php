#!/usr/local/bin/php 
<!DOCTYPE public "-//w3c//dtd html 4.01 transitional//en" 
		"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
  <title>Archivio storico annuale</title>
  <meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1">
  <meta name="GENERATOR" content="Quanta Plus">
  <style type="text/css">@import "css/stralaceno.css";</style>  
</head>
<body class="tabella">
  
<div class="titolo_tabella">Grafici tempi Stralaceno</div>
<hr>
<p>Elenco di tutti gli atleti che hanno partecipato ad almeno una edizione della Stralaceno:</p>

<?php

include 'libreria.php';

$archivio = load_data($filename_tempi,$num_colonne_prestazioni);

$atleti = load_data($filename_atleti,$num_colonne_atleti);
$archivio = merge_tempi_atleti($archivio,$atleti);

$archivio = ordina_archivio($archivio,$indice_nome, $indice_posiz);

$archivio = fondi_nome_id($archivio,$indice_nome, $indice_id);


$elenco_atleti = array();
$archivio2 = array($archivio[0]);
for ($i = 1; $i < count($archivio); $i++) {
	$prestazione = $archivio[$i];
	if (!in_array($prestazione[$indice_id],$elenco_atleti)) {
		array_push($elenco_atleti,$prestazione[$indice_id]);
		array_push($archivio2,$prestazione);
		}
	}



$elenco_nomi = array();
$elenco_cognomi = array();
$elenco_i = array();
for ($i = 1; $i < count($archivio2); $i++) {
	$prestazione = $archivio2[$i];
	$nome = $prestazione[$indice_nome];
	if (!in_array($nome,$elenco_nomi)) {
		
		# estrai il cognome (escludi il nome all'inizio)
		$lista = split(" ",$nome);
		$cognome = "";
		for ($ii = 1; $ii < count($lista); $ii++) {
			$cognome .= " ".$lista[$ii];
			}
			
		array_push($elenco_nomi,$nome);
		array_push($elenco_cognomi,$cognome);
		array_push($elenco_i,$prestazione[$indice_info][$indice2_id]);
		}
	}

array_multisort($elenco_cognomi,SORT_ASC, SORT_STRING,$elenco_nomi,SORT_ASC, SORT_STRING, $elenco_i,SORT_ASC, SORT_NUMERIC);

# logga il contatto
$counter = count_page("grafico_tempi",array("COUNT"=>1,"LOG"=>1)); # abilita il contatore, senza visualizzare le cifre, e fai il log

?>


<form action="http://members.lycos.co.uk/pceres/stralaceno/grafico/crea_grafico.php" method="POST">
<!--form action="grafico/crea_grafico.php" method="POST"-->

<table>
	<tr>
<?php

$num_col = 4;
$colonne = ceil(count($archivio2)/$num_col);

for ($i = 1; $i <= $num_col*$colonne; $i++) {
#for ($i = 1; $i < count($elenco_i); $i++) {
	echo "<td>";
#	echo "<input type=\"checkbox\" name=\"$atleta[$indice2_id]\">$atleta[$indice2_nome]\n";
#	$atleta = $archivio2[$i][$indice_info];
	$riga_i = floor(($i-1)/$num_col)+1;
	$colonna_i = fmod($i-1,$num_col)+1;
	$i2 = ($colonna_i-1)*$colonne+$riga_i;
	if ($i2 < count($elenco_i)) {
		echo "<input type=\"checkbox\" name=\"$elenco_i[$i2]\">$elenco_nomi[$i2]\n";
		}
	echo "</td>";
	
	if (fmod($i,$num_col) == 0) {
		echo "</tr><tr>\n";
		}
	}

?>
	</tr>
</table>
<hr>
<div align="center">
<button TYPE="reset" ACCESSKEY="R">Reset</button>
<input type="submit" value="Mostra grafico tempi">
</div>
</form>


</body>
</html>

