<!DOCTYPE public "-//w3c//dtd html 4.01 transitional//en" 
		"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
  <title>Stralaceno Web - Archivio storico</title>
  <meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1">
  <meta name="GENERATOR" content="Quanta Plus">
</head>
<body>
  
<div align="center"><h2>Archivio storico personale della Stralaceno</h2></div>
<hr>

<?php

include 'libreria.php';

$archivio = load_data($filename_tempi,$num_colonne_prestazioni);

$atleti = load_data($filename_atleti,$num_colonne_atleti);
$archivio = merge_tempi_atleti($archivio,$atleti);


$i_nome = $_REQUEST['nome']; 				# prestazione da cui prendere il nome
$nome = $archivio[$i_nome][$indice_nome];

echo "Personale di <b>".$nome."</b> (id: ".$archivio[$i_nome][$indice_id].") :";

$lista_regola_campo = array($indice_nome);
$lista_regola_valore = array($nome);
$archivio_filtrato = filtra_archivio($archivio,$lista_regola_campo,$lista_regola_valore);

$mask = array($indice_posiz,$indice_tempo,$indice_anno); # escludo ID e nome
show_table($archivio_filtrato,$mask);

?>

</body>
</html>

