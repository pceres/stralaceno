<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 TRANSITIONAL//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
  <title>Stralaceno Web - Archivio storico</title>
  <meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1">
  <meta name="GENERATOR" content="Quanta Plus">
  <style type="text/css">@import "/work/stralaceno2/css/stralaceno.css";</style>
</head>
<body class="tabella">
  
<div class="titolo_tabella">Archivio storico completo della Stralaceno</div>
<hr>

<?php

include 'libreria.php';

$archivio = load_data($filename_tempi,$num_colonne_prestazioni);

$atleti = load_data($filename_atleti,$num_colonne_atleti);
$archivio = merge_tempi_atleti($archivio,$atleti);


$archivio_ordinato = ordina_archivio($archivio,$indice_anno, $indice_posiz);

$archivio_rielaborato = fondi_nome_id($archivio_ordinato, $indice_nome, $indice_id);

$mask = array($indice_posiz,$indice_nome,$indice_tempo,$indice_anno); # visualizza tutti i campi
show_table($archivio_rielaborato,$mask,'tabella',3,12);

?>


</body>
</html>

