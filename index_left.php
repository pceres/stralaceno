<?php

if (!empty($elenco_layout_left))
{
?>
<!-- 
inizio colonna sinistra
-->

<table class="frame_delimiter"><tbody>

<?php 

//
// visualizza i blocchi di layout:
//
foreach($elenco_layout_left as $riquadro => $list_items)
{
	if (is_visible_layout_block($riquadro,$layout_data,'moduli_left'))
	{
		show_layout_block($riquadro,$list_items,$layout_data);
	}
} // foreach $elenco_layout

?>
	
</tbody></table>
<br>

<?php
} // end if !empty($elenco_layout)

if (empty($elenco_layout_right)) // se la colonna destra e' vuota, visualizza qui il contatore di fkounter
{
?>

	<div align="left" class="txt_normal"><i>
		<script language="JavaScript"  type="text/javascript" src="<?php echo $script_abs_path; ?>fkounter5/counter.js.php?id=<?php echo $root_prefix; ?>&amp;mode=graphic"></script>
	</i></div>

<?php
} // if (empty($elenco_layout_right)) 
?> 

<!-- 
fine colonna sinistra
-->


