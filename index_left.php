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
<table width="100%" border="0" cellspacing="0" align="center" bgcolor="#336699"><tbody><tr><td>
   <table width="100%" border="0" cellspacing="" cellpadding="3" bgcolor="#ffffff"><tbody>
	
	
<?php echo $open_border?>
		<strong>La corsa:</strong>
		 <table cellpadding="0" cellspacing="0">
		  <tbody>
			<tr><td style="vertical-align: top;">&#8250;&nbsp;</td><td nowrap>
				<a href="/work/stralaceno2/presentazione.htm" name="presentazione" class="txt_link">Cos'&egrave; la Stralaceno?</a>
			</td></tr>
			<tr><td style="vertical-align: top;">&#8250;&nbsp;</td><td nowrap>
				<a href="filtro7.php" name="Albo d'oro" class="txt_link">Albo d'oro</a>
			</td></tr>
			<tr><td style="vertical-align: top;">&#8250;&nbsp;</td><td nowrap>
				<a name="regolamento" class="txt_link">Stralcio regolamento</a>
			</td></tr>
		  </tbody>
		 </table>
<?php echo $close_border?>

<?php echo $open_border?>
		<strong>Cronaca corrente:</strong>
		 <table cellpadding="0" cellspacing="0">
		  <tbody>

			<tr><td style="vertical-align: top;">&#8250;&nbsp;</td><td>
				<span class="txt_link">Ultime edizioni:</span>
<?php
		for ($i = 0; $i < $numero_anni; $i++) {
			echo "\t\t\t\t<br>&nbsp;&nbsp;&nbsp;&nbsp;<a href=\"filtro4.php?anno=$elenco_anni[$i]\" class=\"txt_link\">Edizione $elenco_anni[$i]</a>\n";
		}
?>
			</td></tr>

			<tr><td style="vertical-align: top;">&#8250;&nbsp;</td><td nowrap>
				<a href="/my_cgi-bin/stralaceno2/filtro9.php" name="migliori prestazioni" class="txt_link">Classifica personali M+F</a>
			</td></tr>
			<tr><td style="vertical-align: top;">&#8250;&nbsp;</td><td nowrap>
				<a href="/my_cgi-bin/stralaceno2/filtro10.php" name="migliori prestazioni femminili" class="txt_link">Classifica personali F</a>
			</td></tr>
			
			</tbody>
		 </table>
<?php echo $close_border?>

<?php echo $open_border?>
		<strong>Link</strong>
		 <table cellpadding="0" cellspacing="0">
		  <tbody>
			<tr><td style="vertical-align: top;">&#8250;&nbsp;</td><td>
				<a href="http://www.caposeleonline.it" name="Caposeleonline" class="txt_link">Caposeleonline</a>
			</td></tr>
			<tr><td style="vertical-align: top;">&#8250;&nbsp;</td><td>
				<a href="http://www.lagolaceno.it/" name="Lago Laceno" class="txt_link">Lago Laceno</a>
			</td></tr>
			<tr><td style="vertical-align: top;">&#8250;&nbsp;</td><td>
				<a href="http://www.skilaceno.com/" name="skilaceno" class="txt_link">Sciare a Laceno</a>
			</td></tr>
		  </tbody>
		 </table>
<?php echo $close_border?>
	
   </tbody></table>
</td></tr></tbody></table>
<!-- 
fine colonna sinistra
-->
