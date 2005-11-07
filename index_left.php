<?php
/*
// carica elenco delle edizioni disponibili
$elenco_anni = array();
for ($ii = 1; $ii < count($archivio); $ii++) {
	array_push($elenco_anni,$archivio[$ii][$indice_anno]);
}
$elenco_anni = array_unique($elenco_anni); # elimina gli anni duplicati
$elenco_anni = array_reverse($elenco_anni); # inverti l'ordine

$numero_anni = min(count($elenco_anni),$max_last_editions); # numero delle ultime edizioni da visualizzare


// carica elenco delle foto disponibili
$elenco_foto = get_config_file($filename_albums,4); // quattro colonne
*/
$pagina = $_REQUEST['page']; // contenuto da visualizzare in colonna centrale

// carica layout colonna sinistra
$filename_layout_left = $config_dir.'layout_left.txt';
$elenco_layout = get_config_file($filename_layout_left,4);

?>
<!-- 
inizio colonna sinistra
-->
<table class="frame_delimiter"><tbody>	

<?php 

$layout_data = array(); // dati da passare ai moduli;

// carica i dati necessari ai vari moduli:
// link::
$link_list = get_link_list($filename_links); 
$layout_data['Link'] = $link_list;

// ultime_edizioni::
// edizioni disponibili
$elenco_anni = array();
for ($ii = 1; $ii < count($archivio); $ii++) {
	array_push($elenco_anni,$archivio[$ii][$indice_anno]);
}
$elenco_anni = array_unique($elenco_anni); # elimina gli anni duplicati
$elenco_anni = array_reverse($elenco_anni); # inverti l'ordine
// carica elenco delle foto disponibili
$elenco_foto = get_config_file($filename_albums,4); // quattro colonne
$layout_data['ultime_edizioni'] = array('elenco_anni'=>$elenco_anni,'elenco_foto'=>$elenco_foto);


if (!empty($pagina)) { // se non si e' nella homepage ($pagina vuoto), visualizza il link alla homepage ?>
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
				<a href="<?php echo $script_site_path ?>index.php?page=articolo&amp;art_id=<?php echo $id ?>" 
					name="articolo_<?php echo $id ?>" class="txt_link">&nbsp;-&nbsp;<?php echo $art_data['titolo'] ?></a>
			</td></tr>
<?php		} // end for  ?>
			
		  </tbody>
		 </table>
		
	  </td></tr></tbody></table>
	</td></tr>
<?php } // if !empty($pagina)  


foreach($elenco_layout as $riquadro => $list_items)
{
	if (is_visible_layout_block($riquadro,$layout_data)) {
		show_layout_block($riquadro,$list_items,$layout_data);
	}
} // foreach $elenco_layout

?>
	
</tbody></table>
<!-- 
fine colonna sinistra
-->
