<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 TRANSITIONAL//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<?php

require_once('libreria.php');

# dichiara variabili
extract(indici());
?>
<head>
  <title>Stralaceno Web - Archivio storico annuale</title>
  <meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1">
  <meta name="GENERATOR" content="Quanta Plus">
  <style type="text/css">@import "<?php echo $css_site_path ?>/stralaceno.css";</style>
</head>
<body class="tabella">
  
<?php

$archivio = load_data($filename_tempi,$num_colonne_prestazioni);

$atleti = load_data($filename_atleti,$num_colonne_atleti);
$archivio = merge_tempi_atleti($archivio,$atleti);

$anno = $_REQUEST['anno']; 				# anno richiesto

echo "<div class=\"titolo_tabella\">Stralaceno ".$anno." - risultati ufficiali :</div>";

$archivio = aggiungi_simboli($archivio);

$lista_regola_campo = array($indice_anno);
$lista_regola_valore = array($anno);
$archivio_filtrato = filtra_archivio($archivio,$lista_regola_campo,$lista_regola_valore);

$archivio_ordinato = ordina_archivio($archivio_filtrato,$indice_posiz, $indice_nome);

$archivio_rielaborato = fondi_nome_id($archivio_ordinato, $indice_nome, $indice_id);

$mask = array($indice_posiz,$indice_nome,$indice_tempo,'simb'); # escludo l'anno
show_table($archivio_rielaborato,$mask,'tabella',3,12);

# logga il contatto
$counter = count_page("classifica_anno",array("COUNT"=>1,"LOG"=>1),$filedir_counter); # abilita il contatore, senza visualizzare le cifre, e fai il log

?>

<br>
<table class="tabella_legenda">
	<tr>
		<td>F.T.M.</td>
		<td>:</td>
		<td class="descrizione">fuori tempo massimo (40 minuti uomini, 45 minuti donne)</td>
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
	<tr>
		<td>
		<?php echo $symbol_1_partecipazione; ?>
		</td>
		<td>:</td>
		<td class="descrizione">1<sup>a</sup> partecipazione</td>
	</tr>
	<tr>
		<td>
		<?php echo $symbol_record; ?>
		</td>
		<td>:</td>
		<td class="descrizione">miglioramento record personale</td>
	</tr>
</table>

<?php
# visualizzazione organizzatori
$organizzatori = load_data($filename_organizzatori,$num_colonne_organizzatori);

$lista_regola_campo = array($indice_anno);
$lista_regola_valore = array($anno);
$archivio_filtrato = filtra_archivio($organizzatori,$lista_regola_campo,$lista_regola_valore);

#$archivio_ordinato = ordina_archivio($archivio_filtrato,$indice_nome, $indice_id); # se volessi ordinare gli organizzatori
$archivio_ordinato = $archivio_filtrato; # non ordino i nomi, vengono presentati nell'ordine con cui sono stati inseriti nel file Excel 'organizzatori_laceno.csv'

	{
	echo "<div class=\"tabella_organizzatori\" >";
	
	echo "<hr>Organizzatori e collaboratori per l'edizione $anno:&nbsp;&nbsp;";
	
	for ($i = 1; $i < count($archivio_ordinato); $i++) 
		{
		$organizzatore = $archivio_ordinato[$i];
		
		$nome = $organizzatore[$indice3_nome];
		$incarico = $organizzatore[$indice3_incarico];
		$nota = trim($organizzatore[$indice3_nota]);
		if ( (strlen($nota) > 0) & ($nota != '-')) 
			{
			$incarico .= " ($nota)";
			}
		echo "$incarico:<span style=\"font-style: italic;\">$nome</span>;&nbsp;&nbsp;&nbsp;\n";
		}
echo "</div>";
	}
	
if (count($archivio_ordinato) <= 2) 
	{
?>

<br>
<div align="justify" style="font-size: 12;">
L'elenco degli organizzatori e collaboratori per questa edizione e' in fase di realizzazione.
Gli interessati, o chi sia in grado di fornire indicazioni al riguardo, sono pregati di mettersi in contatto tramite 
l'indirizzo e-mail: <a href="mailto:<?php echo $email_info?>?subject=Info%20sui%20collaboratori%20della%20Stralaceno"><?php echo $email_info?></a>.  <br>
</div>

<?php
	}

?>


</body>
</html>

