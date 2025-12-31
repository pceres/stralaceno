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

<table summary="header" width="100%" align="center" style="background-color:rgb(255, 255, 255);">
  <tbody>
	<tr>
		<td align="center">
		<img src="<?php echo $site_abs_path ?>/custom/album/varie/logo.jpg" alt="logo <?php echo $web_title; ?>">
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

<div align="center"><b>Statuto della Stralaceno</b></div>

<div align="justify" class="txt_normal">

<p>
<b>Premessa</b><br>
Ogni anno gli abitanti di Caposele e i loro discendenti si recano sull'altopiano di Laceno per percorrerne il circuito ad andatura libera,
in una manifestazione podistica denominata Stralaceno.
<br><br>
</p>

<p>
<b>Articolo n. 1 - Elementi costitutivi della Stralaceno</b><br>
Gli elementi costitutivi immodificabili della Stralaceno sono:
<ul>
    <li>L'appartenenza obbligatoria alla comunit&agrave; sportiva ideale di Caposele dei partecipanti.</li>
    <li>La cadenza annuale.</li>
    <li>La comparabilit&agrave; dei risultati tra le varie edizioni. Ne consegue l'immutabilit&agrave; del percorso e dei punti di partenza e di arrivo
    (fatte salve le cause di forza maggiore).</li>
</ul>
Sulle eventuali proposte di innovazione di altri aspetti, decide il Consiglio dei decani.
<br><br>
</p>

<p>
<b>
Per lo Statuto completo cliccare
<a href="Statuto della STRALACENO firmato e protocollato 9-6-2025.pdf">qui</a>.
</b>
</p>



</div>


<?php echo $homepage_link ?>

<?php
# logga il contatto
$counter = count_page("modulo_regolamento",array("COUNT"=>1,"LOG"=>1),$filedir_counter); # abilita il contatore, senza visualizzare le cifre, e fai il log
?>

</body>
</html>
