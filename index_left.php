<?php

$elenco_anni = array();
for ($ii = 1; $ii < count($archivio); $ii++) {
	array_push($elenco_anni,$archivio[$ii][$indice_anno]);
}
$elenco_anni = array_unique($elenco_anni); # elimina gli anni duplicati
$elenco_anni = array_reverse($elenco_anni); # inverti l'ordine

$numero_anni = min(count($elenco_anni),$max_last_editions); # numero delle ultime edizioni da visualizzare


$pagina = $_REQUEST['page']; // contenuto da visualizzare in colonna centrale
?>
<!-- 
inizio colonna sinistra
-->
<table class="frame_delimiter"><tbody>	

<?php if (!empty($pagina)) { // se non si e' nella homepage ($pagina vuoto), visualizza il link alla homepage ?>
	<tr><td>
	  <table class="column_group"><tbody><tr><td>
	  
		<span class="titolo_colonna">Homepage:</span>
		 <table cellpadding="0" cellspacing="0">
		  <tbody>
		  
			<tr style="vertical-align: baseline"><td>&#8250;&nbsp;</td><td nowrap>
				<a href="<?php echo $script_site_path ?>index.php" name="homepage" class="txt_link">Torna alla homepage</a>
			</td></tr>
			
<?php
			$art_list = get_online_articles($article_online_file); // carica l'elenco degli articoli da pubblicare
			for ($i = 0; $i < count($art_list); $i++)
			{
				$id = $art_list[$i];
				$art_data = load_article($id); // carica l'articolo
?>
			<tr style="vertical-align: baseline"><td>&#8250;&nbsp;</td><td nowrap>
				<a href="<?php echo $script_site_path ?>index.php?page=articolo&art_id=<?php echo $id ?>" 
					name="articolo_<?php echo $id ?>" class="txt_link">&nbsp;-&nbsp;<?php echo $art_data['titolo'] ?></a>
			</td></tr>
<?php
			}
?>
			
		  </tbody>
		 </table>
		
	  </td></tr></tbody></table>
	</td></tr>
<?php } // if !empty($pagina)  ?>


	<tr><td>
	  <table class="column_group"><tbody><tr><td>
	  
		<span class="titolo_colonna">La corsa:</span>
		 <table cellpadding="0" cellspacing="0">
		  <tbody>
		  
			<tr style="vertical-align: baseline"><td>&#8250;&nbsp;</td><td nowrap>
				<a href="<?php echo $modules_site_path ?>presentazione/presentazione.php" name="presentazione" class="txt_link">Cos'&egrave; la Stralaceno?</a>
			</td></tr>
			
			<tr style="vertical-align: baseline"><td>&#8250;&nbsp;</td><td nowrap>
				<a href="filtro7.php" name="Albo_d_oro" class="txt_link">Albo d'oro</a>
			</td></tr>
			
			<tr style="vertical-align: baseline"><td>&#8250;&nbsp;</td><td nowrap>
				<a onClick="alert('Pagina in allestimento!')" name="regolamento" class="txt_link">Stralcio regolamento</a>
			</td></tr>
			
			<tr style="vertical-align: baseline"><td>&#8250;&nbsp;</td><td>
				<a href="<?php echo $modules_site_path ?>organizzatori/organizzatori.php" name="organizzatori" class="txt_link">Gli organizzatori</a>
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
				<a href="filtro9.php" name="migliori_prestazioni" class="txt_link">Classifica personali M+F</a>
			</td></tr>
			
			<tr style="vertical-align: baseline"><td>&#8250;&nbsp;</td><td nowrap>
				<a href="filtro10.php" name="migliori_prestazioni_femminili" class="txt_link">Classifica personali F</a>
			</td></tr>
			
			</tbody>
		 </table>
		
	  </td></tr></tbody></table>
	</td></tr>


	<tr><td>
	  <table class="column_group"><tbody><tr><td>
		
		<span class="titolo_colonna">Link:</span>
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
