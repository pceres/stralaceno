#!/usr/local/bin/php
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 TRANSITIONAL//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<?php

require_once('libreria.php');

# dichiara variabili
extract(indici());
?>
<head>
  <title><?php echo $web_title ?> - Album fotografico</title>
  <meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1">
  <meta name="GENERATOR" content="Quanta Plus">
  <style type="text/css">@import "<?php echo $filename_css ?>";</style>
</head>

<?php 

$anno = $_REQUEST['anno'];

// carica elenco delle foto disponibili
$elenco_foto = get_config_file($filename_albums,3);
$id_nomefile_foto = 0;
$id_titolo_foto = 1;
$id_descrizione_foto = 2;

$album = $elenco_foto[$anno];

$photo_per_row = 3; // numero di foto per riga

?>

<body onLoad="FixPhotos('thumb_table')">

<script type="text/javascript">
//<![CDATA[
<!--

function FixPhotos(tag) {

var table;
var i,tag_i,thumb_id;

i=0;
do
{
	tag_i = tag+"_"+i;
	thumb_id = "img_"+i;
	
	if (table = document.getElementById(tag_i))
	{
		//alert(tag_i);
		FixSinglePhoto(table,thumb_id);
	}
	
	i++;
	
} while (table);

} // end FixPhoto


function FixSinglePhoto(table,thumb_id) {

var cell_L,cell_R,image;
var	x;

if (document.all)
{ // IE
	cell_L=table.cells[4];
	cell_R=table.cells[5];
}
else if (document.getElementById)
{ // NS
	cell_L=table.tBodies[0].rows[2].cells[0];
	cell_R=table.tBodies[0].rows[2].cells[1];
}

image=document.getElementById(thumb_id);
x = image.height; // altezza della foto
	
cell_L.height=x-13*2;
cell_R.height=x-13*2;

} // end FixSinglePhoto

//-->
//]]>
</script>


	<!--body alink="#603913" background="Binder_files/bkgrnd.gif" link="#603913" text="#000000" vlink="#603913"-->
		<table border="0" cellpadding="0" cellspacing="0" width="100%">
			<tbody>
			
			<!-- riga vuota -->
			<tr>
				<td colspan="<?php echo $photo_per_row ?>" height="10"><img src="<?php echo $site_abs_path ?>custom/images/cornice/Null.gif" border="0" height="10" width="11"></td>
			</tr>
			
			
			<!-- riga descrizione album -->
			<tr>
				<td colspan="<?php echo $photo_per_row-1; ?>">
					<font color="#000000" face="Times New Roman,Georgia,Times" size="4">
						&nbsp;&nbsp;&nbsp;&nbsp;
						Foto disponibili per l'<a href="filtro4.php?anno=<?php echo $anno ?>">edizione <?php echo $anno ?></a>:
					</font></td>
					
				<td valign="top" width="<?php echo round(100/$photo_per_row) ?>%">
					<div align="right">
						<h2><a href="index.php">Homepage</a></h2>
					</div>
				</td>
			</tr>
			
			
<?php 
$photo_count = 0;
$rem_count = 0;
for ($riga=0; $photo_count<count($album); $riga++)
{
?>
			
			<!-- riga di $photo_per_row foto -->
			<tr>
<?php
for ($i = 0; $i<$photo_per_row; $i++)
{
	if ($photo_count < count($album))
	{
		
		#determina il nome dell'immagine thumbnail. Se la foto e' foto.jpg, il thumbnail si deve chiamare foto-thumb.jpg
		$nome_foto = $album[$photo_count][$id_nomefile_foto];
		$pos = strrpos($nome_foto,'.');
		$nome_thumb = substr($nome_foto,0,$pos)."-thumb".substr($nome_foto,$pos);
		if (file_exists($root_path."custom/album/$anno/".$nome_thumb))
		{	// esiste il thumbnail, usalo
			$nome_foto = $site_abs_path."custom/album/$anno/".$nome_thumb;
		}
		else
		{	// usa direttamente la foto
			$nome_foto = $site_abs_path."custom/album/$anno/".$nome_foto;
		}
		$nome_foto = str_replace(' ', '%20', $nome_foto); // formatta correttamente gli spazi in html
		
?>
<td align='center' width='<?php echo round(100/$photo_per_row); ?>%' valign='middle'>
		<table id="thumb_table_<?php echo $photo_count ?>" border='0' cellpadding='0' cellspacing='0'>
			<tr>
				<td colspan='3'><img src="<?php echo $site_abs_path ?>custom/images/cornice/Bord_TL.gif" alt="TL" width="30" height="16" border="0" /><img src='<?php echo $site_abs_path ?>custom/images/cornice/Bord_T.gif' alt="T" width='105' height='16' border='0' /><img src='<?php echo $site_abs_path ?>custom/images/cornice/Bord_TR.gif' alt="TR" width='30' height='16' border='0' /></td>
			</tr> <tr>
				<td><img src='<?php echo $site_abs_path ?>custom/images/cornice/Bord_LT.gif' alt="LT" width='16' height='13' border='0' /></td>
				<td rowspan='3' valign=middle><a href='show_photo.php?id_photo=<?php echo $photo_count ?>&amp;album=<?php echo $anno ?>'><img id="img_<?php echo $photo_count ?>" src='<?php echo $nome_foto ?>' border='0' width='133' /></a></td>
				<td><img src='<?php echo $site_abs_path ?>custom/images/cornice/Bord_RT.gif' alt="RT" width='16' height='13' border='0' /></td>
			</tr> <tr>
				<td style="background-image:url(<?php echo $site_abs_path ?>custom/images/cornice/Bord_L.gif);">&nbsp;</td>
				<td style="background-image:url(<?php echo $site_abs_path ?>custom/images/cornice/Bord_R.gif);">&nbsp;</td>
			</tr> <tr>
				<td><img src='<?php echo $site_abs_path ?>custom/images/cornice/Bord_LB.gif' alt="LB" width='16' height='13' border='0' /></td>
				<td><img src='<?php echo $site_abs_path ?>custom/images/cornice/Bord_RB.gif' alt="RB" width='16' height='13' border='0' /></td>
			</tr> <tr>
				<td colspan='3'><img src='<?php echo $site_abs_path ?>custom/images/cornice/Bord_BL.gif' alt="BL" width='30' height='16' border='0' /><img src='<?php echo $site_abs_path ?>custom/images/cornice/Bord_B.gif' alt="B" width='105' height='16' border='0' /><img src='<?php echo $site_abs_path ?>custom/images/cornice/Bord_BR.gif' alt="BR" width='30' height='16' border='0' /></td>
			</tr>
		</table>
</td>
<?php
	} // if ($photo_count <= count($elenco_foto))
	$photo_count++;
} // for
?>
			</tr>
			
			
			<!-- riga di $photo_per_row commenti -->
			<tr>
<?php
for ($i = 0; $i<$photo_per_row; $i++)
{
	if ($rem_count < count($album))
	{

?>
			<td align="center" valign="top" width="<?php echo round(100/$photo_per_row); ?>%">
					<a href='show_photo.php?id_photo=<?php echo $rem_count ?>&amp;album=<?php echo $anno ?>'>
					<?php echo $album[$rem_count][$id_titolo_foto]?></a>
					
						<br>
						<?php if (strlen($album[$rem_count][$id_descrizione_foto])>0) { ?>
						<font size="-2">
						<?php echo $album[$rem_count][$id_descrizione_foto] ?>
						</font>
						<?php } // end if ?>
					
					
				</td>
<?php
	} // if ($rem_count <= count($elenco_foto))
	$rem_count++;
} // for
?>				
				
</tr>

<?php 
} // for ($riga=0; $riga<2; $riga++)
?>




</tbody></table>

</body>
</html>
