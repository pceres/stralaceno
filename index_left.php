<?php

$numero_anni = 4; # numero delle ultime edizioni da visualizzare

$elenco_anni = array();
for ($ii = 1; $ii < count($archivio); $ii++) {
	array_push($elenco_anni,$archivio[$ii][$indice_anno]);
}
$elenco_anni = array_unique($elenco_anni); # elimina gli anni duplicati
$elenco_anni = array_reverse($elenco_anni); # inverti l'ordine

$colore_bordo_left = '#FFFFFF';#$colore_bordo;#'#FFFFFF';

$open_border = "\n<tr><td><table width=\"95%\" border=\"0\" cellspacing=\"0\" align=\"center\" bgcolor=\"$colore_bordo_left\"><tbody><tr><td>\n<table width=\"100%\" border=\"0\" cellspacing=\"\" cellpadding=\"5\" bgcolor=\"$colore_sfondo\"><tbody><tr><td>\n";
$close_border = "</td></tr></tbody></table>\n</td></tr></tbody></table></td></tr>\n\n";

?>


<table width="95%" border="0" cellspacing="0" align="center" bgcolor="#336699"><tbody><tr><td>
	<table width="100%" border="0" cellspacing="" cellpadding="3" bgcolor="#ffffff"><tbody>
	
		<?php echo $open_border?>
		<strong>La corsa:</strong>
			<br>›&nbsp;<a href="/work/stralaceno2/presentazione.htm" name="presentazione">Cos'&egrave; la Stralaceno?</a>
			<br>›&nbsp;<a href="filtro7.php" name="Albo d'oro">Albo d'oro</a>
			<br>›&nbsp;<a name="regolamento">Stralcio regolamento</a>
		<?php echo $close_border?>



		<?php echo $open_border?>
		<strong>Cronaca corrente:</strong>

			<?php
			for ($i = 0; $i < $numero_anni; $i++) {
				echo "<br>›&nbsp;<a href=\"filtro4.php?anno=$elenco_anni[$i]\">Edizione $elenco_anni[$i]</a>\n";
			}
			?>
		
			<br>›&nbsp;<a href="/my_cgi-bin/stralaceno2/filtro9.php" name="migliori prestazioni">Classifica personali</a></li>
			<br>›&nbsp;<a name="migliori prestazioni femminili">Classifica personali femminili</a></li>
		<?php echo $close_border?>


		<?php echo $open_border?>

		<strong>Link</strong>
			<br>›&nbsp;<a href="http://www.caposeleonline.it" name="Caposeleonline">Caposeleonline</a>
			<br>›&nbsp;<a href="http://www.lagolaceno.it/" name="Lago Laceno">Lago Laceno</a>
		<?php echo $close_border?>
	


	</td></tr></tbody></table>
</td></tr></tbody></table>

<?php
#present., albo d'oro, Stralcio regolam. Cronaca corrente: 2004, 2003,..,migl.iori prestaz.(gen. e femminili). Approf: partec. per tempi, Grafici, Per nomi, i personaggi, Organizzatori, classifica num. partecipaz.
?>