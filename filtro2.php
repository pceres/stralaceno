<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 TRANSITIONAL//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<?php

require_once('libreria.php');

# dichiara variabili
extract(indici());
?>
<head>
  <title>Stralaceno Web - Archivio storico</title>
  <meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1">
  <meta name="GENERATOR" content="Quanta Plus">
  <style type="text/css">@import "<?php echo $css_site_path ?>/stralaceno.css";</style>
</head>
<body class="tabella">
  
<div class="titolo_tabella">Archivio storico personale della Stralaceno</div>
<hr>

<?php

$archivio = load_data($filename_tempi,$num_colonne_prestazioni);

$atleti = load_data($filename_atleti,$num_colonne_atleti);
$archivio = merge_tempi_atleti($archivio,$atleti);


$id = $_REQUEST['id_nome']; 				# prestazione da cui prendere il nome
$nome = $atleti[$id][$indice_nome];

echo "Personale di <b>".$nome."</b> (id: ".$id.") :";

$lista_regola_campo = array($indice_nome);
$lista_regola_valore = array($nome);
$archivio_filtrato = filtra_archivio($archivio,$lista_regola_campo,$lista_regola_valore);

$mask = array($indice_posiz,$indice_tempo,$indice_anno); # escludo ID e nome
show_table($archivio_filtrato,$mask,'tabella');

# logga il contatto
$counter = count_page("tempi_atleta",array("COUNT"=>1,"LOG"=>1),$filedir_counter); # abilita il contatore, senza visualizzare le cifre, e fai il log

?>

</body>
</html>

