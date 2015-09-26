<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 TRANSITIONAL//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<?php

require_once('../../../libreria.php');

# dichiara variabili
extract(indici());
?>
<head>
  <title>Stralaceno Web - Gli organizzatori della Stralaceno</title>
  <meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1">
  <meta name="GENERATOR" content="Quanta Plus">
  <meta name="description" content="Organizzatori della Stralaceno: Associazione ARS (Amatori Running Sele)">
  <meta name="keywords" content="Stralaceno, ARS, Laceno, Caposele, Atletica, Sport">
  <style type="text/css">@import "<?php echo $filename_css ?>";</style>  
</head>
<body>

<table align="center" width="100%" style="background-color:rgb(255, 255, 255);">
	<tbody>
		<tr>
		  <td align="center"> <img src="<?php echo $site_abs_path?>custom/album/varie/logo.jpg" alt="logo <?php echo $web_title; ?>"> </td>
		</tr>
	</tbody>
</table>

<hr>

<div align="justify" class="txt_normal">

<!--p>
TODO: breve testo di descrizione
<hr-->

<h3 style="clear:right;text-align:center;">Statuto dell'Associazione sportiva e culturale "ARS  Amatori Running Sele":</h3>

<img src="<?php echo $site_abs_path ?>custom/moduli/organizzatori/ars_logo.jpg" style="float:right;margin:1em;" alt="scarica lo stemma dell'ARS" border="0" height="200" width="400">

<h3>Art. 1 - Scopo</h3>

<p>L'Associazione &egrave; apolitica e non ha scopo di lucro.

<p>Durante la vita dell'Associazione non potranno essere distribuiti, anche in modo indiretto, avanzi di gestione 
nonch&eacute; fondi, riserve o capitale, salvo che la destinazione o la distribuzione non siano imposte dalla legge.

<p>Essa si propone di:

<ul>
    <li>promuovere e diffondere una corretta pratica dello sport. Per il raggiungimento di tale scopo l'Associazione 
	potr&agrave; organizzare momenti di attivit&agrave; sportiva e manifestazioni di vario tipo, con particolare riguardo al 
	risvolto sociale ed educativo;</li>
    <li>intraprendere iniziative di natura culturale al fine di approfondire il significato dell'attivit&agrave; 
	sportiva nelle sue diverse interpretazioni;</li>
    <li>attuare servizi e strutture per lo svolgimento delle attivit&agrave; fisiche ed intellettuali;</li>
    <li>favorire contatti fra soci aventi specifici interessi culturali e sportivi, costituendo sezioni per le 
	attivit&agrave; di maggior rilievo;</li>
    <li>aderire a qualsiasi attivit&agrave; che direttamente, tramite delibera del Consiglio Direttivo, sia giudicata 
	idonea al raggiungimento degli scopi  sociali.</li>
</ul>
<br>

<a style="font-weight: bold;" href="<?php echo $site_abs_path ?>custom/moduli/organizzatori/atto.htm">
	<big>Visualizza l'atto costitutivo e lo statuto completo dell'ARS</big></a><br>
<br>


<a style="font-weight: bold;" href="<?php echo $site_abs_path ?>download.php?resource_type=folder&amp;resource_id=folder_Atto_costitutivo_statuto_verbali">
	<big>Atti costitutivi, statuti, regolamenti e verbali</big></a><br>
<br>


<a style="font-weight: bold;" href="<?php echo $site_abs_path ?>download.php?resource_type=folder&amp;resource_id=folder_Rendiconti">
	<big>Rendiconti e relazioni</big></a><br>
<br>


<div style="font-weight: bold;"><big>Scarica il modulo di richiesta di adesione all'ARS in formato pdf:</big>
	<a target="_blank" href="richiesta_adesione_ARS.pdf">
		<img src="<?php echo $site_abs_path ?>images/filetype_icons/pdf.png" alt="scarica la richiesta di adesione in formato pdf" 
			border="0" height="36" width="36">
	</a>
</div>
(da consegnare al segretario amministrativo)

<br>


</div>


<?php echo $homepage_link ?>

<?php
# logga il contatto
$counter = count_page("modulo_organizzatori",array("COUNT"=>1,"LOG"=>1),$filedir_counter); # abilita il contatore, senza visualizzare le cifre, e fai il log
?>


</body>
</html>
