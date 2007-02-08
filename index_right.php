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

	<div align="right" class="txt_normal"><i>
		<br>
		<script language="JavaScript"  type="text/javascript" src="<?php echo $script_abs_path; ?>fkounter5/counter.js.php?id=<?php echo $root_prefix; ?>&amp;mode=graphic"></script>
	</i></div>

<!-- 
fine colonna destra
-->

<?php
} // end if !empty($elenco_layout)
?>
