<?php

if (!empty($elenco_layout_right))
{
?>
<!--
inizio colonna destra
-->

<table class="frame_delimiter"><tbody>

<?php

//
// visualizza i blocchi di layout:
//
foreach($elenco_layout_right as $riquadro => $list_items)
{
	if (is_visible_layout_block($riquadro,$layout_data,'moduli_right'))
	{
		show_layout_block($riquadro,$list_items,$layout_data);
	}
} // foreach $elenco_layout

?>

</tbody></table>

	<div align="right" class="txt_normal">
		<!-- fankounter -->
		<br>
		<i>
			<script language="JavaScript"  type="text/javascript" src="<?php echo $script_abs_path; ?>fkounter5/counter.js.php?id=<?php echo $root_prefix; ?>&amp;mode=graphic"></script>
		</i>
		
		<!-- feed RSS -->
		<br>
		<br>
		<span title="Per usare RSS, copiare il link ed inserirlo in un aggregatore RSS, es. NewsFox">
			Feed RSS 2.0:
			<a href="feed.php">
				<img src="<?php echo $site_abs_path; ?>images/syndicated-feed-icon.gif" alt="RSS 2.0 feed icon" style="border: 0px none;">
			</a>
		</span>

	</div>

<!-- 
fine colonna destra
-->

<?php
} // end if !empty($elenco_layout)
?>
