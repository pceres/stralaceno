<?php

$numero_anni = 3; # numero delle ultime edizioni da visualizzare

$elenco_anni = array();
for ($ii = 1; $ii < count($archivio); $ii++) {
	array_push($elenco_anni,$archivio[$ii][$indice_anno]);
}
$elenco_anni = array_unique($elenco_anni); # elimina gli anni duplicati
$elenco_anni = array_reverse($elenco_anni); # inverti l'ordine

$colore_bordo_left = '#FFFFFF';#$colore_bordo;#'#FFFFFF';

$open_border = "\t<tr><td><table width=\"95%\" border=\"0\" cellspacing=\"0\" align=\"center\" bgcolor=\"$colore_bordo_left\"><tbody><tr><td>\n\t   <table width=\"100%\" border=\"0\" cellspacing=\"\" cellpadding=\"5\" bgcolor=\"$colore_sfondo\"><tbody><tr><td>\n";
$close_border = "\t   </td></tr></tbody></table>\n\t</td></tr></tbody></table></td></tr>\n\n";

?>
<!-- 
inizio colonna sinistra
-->
<table width="95%" border="0" cellspacing="0" align="center" bgcolor="#336699"><tbody><tr><td>
   <table width="100%" border="0" cellspacing="" cellpadding="3" bgcolor="#ffffff"><tbody>
	
	
<?php echo $open_border?>
		<strong>La corsa:</strong>
		 <table cellpadding="0" cellspacing="0">
		  <tbody>
			<tr><td style="vertical-align: top;">&#8250;&nbsp;</td><td>
				<a href="presentazione.htm" name="presentazione">Cos'&egrave; la Stralaceno?</a>
			</td></tr>
			<tr><td style="vertical-align: top;">&#8250;&nbsp;</td><td>
				<a href="filtro7.php" name="Albo d'oro">Albo d'oro</a>
			</td></tr>
			<tr><td style="vertical-align: top;">&#8250;&nbsp;</td><td>
				<a name="regolamento">Stralcio regolamento</a>
			</td></tr>
		  </tbody>
		 </table>
<?php echo $close_border?>

<?php echo $open_border?>
		<strong>Cronaca corrente:</strong>
		 <table cellpadding="0" cellspacing="0">
		  <tbody>
<?php
		for ($i = 0; $i < $numero_anni; $i++) {
			echo "\t\t\t<tr><td style=\"vertical-align: top;\">&#8250;&nbsp;</td><td>\n";
			echo "\t\t\t\t&nbsp;&nbsp;&nbsp;&nbsp;<a href=\"filtro4.php?anno=$elenco_anni[$i]\">Edizione $elenco_anni[$i]</a>\n";
			echo "\t\t\t</td></tr>\n";
		}
?>
			<tr><td style="vertical-align: top;">&#8250;&nbsp;</td><td>
				<a href="filtro9.php" name="migliori prestazioni">Classifica personali</a>
			</td></tr>
			<tr><td style="vertical-align: top;">&#8250;&nbsp;</td><td>
				<a href="filtro10.php" name="migliori prestazioni femminili">Classifica personali femminili</a>
			</td></tr>
		  </tbody>
		 </table>
<?php echo $close_border?>

<?php echo $open_border?>
		<strong>Link</strong>
		 <table cellpadding="0" cellspacing="0">
		  <tbody>
			<tr><td style="vertical-align: top;">&#8250;&nbsp;</td><td>
				<a href="http://www.caposeleonline.it" name="Caposeleonline">Caposeleonline</a>
			</td></tr>
			<tr><td style="vertical-align: top;">&#8250;&nbsp;</td><td>
				<a href="http://www.lagolaceno.it/" name="Lago Laceno">Lago Laceno</a>
			</td></tr>
			<tr><td style="vertical-align: top;">&#8250;&nbsp;</td><td>
				<a href="http://www.skilaceno.com/" name="skilaceno">Sciare a Laceno</a>
			</td></tr>
		  </tbody>
		 </table>
<?php echo $close_border?>
	
   </tbody></table>
</td></tr></tbody></table>
<!-- 
fine colonna sinistra
-->
