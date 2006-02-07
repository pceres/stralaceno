<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 TRANSITIONAL//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<?php

require_once('../../../libreria.php');

# dichiara variabili
extract(indici());
?>
<head>
  <title>Stralaceno Web - Stralcio regolamento della Stralaceno</title>
  <meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1">
  <meta name="GENERATOR" content="Quanta Plus">
  <meta name="description" content="Stralcio del regolamento della Stralaceno">
  <meta name="keywords" content="Regolamento, Stralaceno, ARS, Laceno, Caposele, Atletica, Sport">  
  <style type="text/css">@import "<?php echo $filename_css ?>";</style>
</head>
<body>

<table width="100%" align="center" style="background-color:rgb(255, 255, 255);">
  <tbody>
	<tr>
		<td align="center">
		<img src="<?php echo $site_abs_path ?>/custom/images/logo.gif" alt="logo_stralacenoweb">
		</td>
		
		<!--td>
		<object	type="application/x-shockwave-flash" data="<?php echo $site_abs_path ?>/custom/images/logoflash300x70.swf" width="300" height="70">
			<param name="movie" value="<?php echo $site_abs_path ?>/custom/images/logoflash300x70.swf" />
		</object>
		</td-->
	</tr>
  </tbody>
</table>

<hr>

<div align="center"><b>Caratteristiche della Stralaceno</b></div>

<div align="justify" class="txt_normal">

<p>
Seguono le caratteristiche principali della Stralaceno, come si sono andate via via
cristallizzando nel corso delle passate edizioni.
<br><br>

<p>
<b>Modalit&agrave; di svolgimento</b>
<ul>
	<li>
	Il percorso consiste nel circuito di Laceno, con partenza in corrispondenza 
	dell'incrocio per Caposele, di fronte all'albergo "4 camini".
	</li>

	<li>
	Il tempo massimo per concludere il percorso &egrave; fissato a 40 minuti per i 
	maschi, 45 minuti per le femmine.
	</li>

	<li>
	La gara non &egrave; competitiva, non &egrave; previsto nessun premio per i 
	vincitori.
	</li>
</ul>

<br>


<b>Chi partecipa</b>
<ul>
	<!--li>
	Tutte le persone dotate di <i>"cittadinanza sportiva"</i> caposelese, in caso di 
	contrasti si ricorre ad una commissione di saggi.
	</li-->

	<li>
	Tutte le persone dotate di <i>"cittadinanza sportiva"</i> caposelese.
	</li>

	<li>
	I maggiorenni, o i minorenni dai 15 anni in su, previa autorizzazione 
	dei genitori.
	</li>
</ul>

<br>

<b>Quando si svolge</b>
<ul>
	<li>
	Solitamente nel periodo 25 agosto-3 settembre.
	</li>

	<li>
	La partenza &egrave; prevista nel tardo pomeriggio.
	</li>

	<li>
	La gara si corre anche in caso di pioggia.
	</li>

</ul>


</div>


<?php echo $homepage_link ?>

<?php
# logga il contatto
$counter = count_page("modulo_regolamento",array("COUNT"=>1,"LOG"=>1),$filedir_counter); # abilita il contatore, senza visualizzare le cifre, e fai il log
?>

</body>
</html>
