<?php

$elenco_anni = array();
for ($ii = 1; $ii < count($archivio); $ii++) {
	array_push($elenco_anni,$archivio[$ii][$indice_anno]);
}
$elenco_anni = array_unique($elenco_anni); # elimina gli anni duplicati
$elenco_anni = array_reverse($elenco_anni); # inverti l'ordine

$numero_anni = min(count($elenco_anni),$max_last_editions); # numero delle ultime edizioni da visualizzare

?>
<!-- 
inizio colonna sinistra
-->
<table class="frame_delimiter"><tbody>	
	
	<tr><td>
	  <table class="column_group"><tbody><tr><td>
	  
		<span class="titolo_colonna">La corsa:</span>
		 <table cellpadding="0" cellspacing="0">
		  <tbody>
			<tr style="vertical-align: baseline"><td>&#8250;&nbsp;</td><td nowrap>
				<a href="/my_cgi-bin/stralaceno2/custom/moduli/presentazione/presentazione.php" name="presentazione" class="txt_link">Cos'&egrave; la Stralaceno?</a>
			</td></tr>
			<tr style="vertical-align: baseline"><td>&#8250;&nbsp;</td><td nowrap>
				<a href="filtro7.php" name="Albo_d_oro" class="txt_link">Albo d'oro</a>
			</td></tr>
			<tr style="vertical-align: baseline"><td>&#8250;&nbsp;</td><td nowrap>
				<a onClick="alert('Pagina in allestimento!')" name="regolamento" class="txt_link">Stralcio regolamento</a>
			</td></tr>
		  </tbody>
		 </table>

	  </td></tr></tbody></table>
	</td></tr>


	<tr><td>
	  <table class="column_group"><tbody><tr><td>

		<span class="titolo_colonna">Cronaca corrente:</span>
		 <table cellpadding="0" cellspacing="0">
		  <tbody>

<?php if ($numero_anni > 0) { ?>
			<tr style="vertical-align: baseline"><td>&#8250;&nbsp;</td><td>
				<span class="txt_link">Ultime edizioni:</span>
<?php
		for ($i = 0; $i < $numero_anni; $i++) {
			echo "\t\t\t\t<br>&nbsp;&nbsp;&nbsp;&nbsp;<a href=\"filtro4.php?anno=$elenco_anni[$i]\" class=\"txt_link\">Edizione $elenco_anni[$i]</a>\n";
		}
?>
			</td></tr>
<?php } ?>

			<tr style="vertical-align: baseline"><td>&#8250;&nbsp;</td><td nowrap>
				<a href="/my_cgi-bin/stralaceno2/filtro9.php" name="migliori_prestazioni" class="txt_link">Classifica personali M+F</a>
			</td></tr>
			<tr style="vertical-align: baseline"><td>&#8250;&nbsp;</td><td nowrap>
				<a href="/my_cgi-bin/stralaceno2/filtro10.php" name="migliori_prestazioni_femminili" class="txt_link">Classifica personali F</a>
			</td></tr>
			
			</tbody>
		 </table>

	  </td></tr></tbody></table>
	</td></tr>


	<tr><td>
	  <table class="column_group"><tbody><tr><td>

		<span class="titolo_colonna">Link</span>
		 <table cellpadding="0" cellspacing="0">
		  <tbody>
			<tr style="vertical-align: baseline"><td>&#8250;&nbsp;</td><td>
				<a href="http://www.caposeleonline.it" name="Caposeleonline" class="txt_link">Caposeleonline</a>
			</td></tr>
			<tr style="vertical-align: baseline"><td>&#8250;&nbsp;</td><td>
				<a href="http://www.lagolaceno.it/" name="Lago_Laceno" class="txt_link">Lago Laceno</a>
			</td></tr>
			<!--tr style="vertical-align: baseline"><td>&#8250;&nbsp;</td><td>
				<a href="http://www.skilaceno.com/" name="skilaceno" class="txt_link">Sciare a Laceno</a>
			</td></tr-->
			<tr style="vertical-align: baseline"><td>&#8250;&nbsp;</td><td>
				<a href="http://liceonline.altervista.org/" name="liceocaposele" class="txt_link">Liceo di Caposele</a>
			</td></tr>
		  </tbody>
		 </table>

	  </td></tr></tbody></table>
	</td></tr>

	
</tbody></table>
<!-- 
fine colonna sinistra
-->
