<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 TRANSITIONAL//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<?php

require_once('libreria.php');

# dichiara variabili
extract(indici());
?>
<head>
  <title>Stralaceno Web - Album fotografico</title>
  <meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1">
  <meta name="GENERATOR" content="Quanta Plus">
  <style type="text/css">@import "<?php echo $css_site_path ?>/stralaceno.css";</style>
</head>

<?php 

$anno = $_REQUEST['anno'];

// carica elenco delle foto disponibili
$elenco_foto = get_config_file($config_dir."albums.txt",3);
$id_nomefile_foto = 0;
$id_titolo_foto = 1;
$id_descrizione_foto = 2;

$album = $elenco_foto[$anno];

$photo_per_row = 5; // numero di foto per riga

?>

<body>

	<!--body alink="#603913" background="Binder_files/bkgrnd.gif" link="#603913" text="#000000" vlink="#603913"-->
		<table border="0" cellpadding="0" cellspacing="0" width="100%">
			<tbody>
			
			<!-- riga vuota -->
			<tr>
				<td colspan="<?php echo $photo_per_row ?>" height="10"><img src="Binder_files/Null.gif" border="0" height="10" width="11"></td>
			</tr>
			
			
			<!-- riga descrizione album -->
			<tr>
				<td colspan="<?php echo $photo_per_row-1; ?>">
					<font color="#000000" face="Times New Roman,Georgia,Times" size="4">
					&nbsp;&nbsp;&nbsp;&nbsp;<a href="album.php">Foto disponibili per l'edizione <?php echo $anno ?></a>:
					</font></td>
					
				<td valign="top" width="<?php echo round(100/$photo_per_row) ?>%">
					<div align="right">
						<a href="index.php"><h2>Homepage</h2></a>
					</div>
				</td>
			</tr>
			
			
			<!-- riga di $photo_per_row foto -->
			<tr>
<?php
//print_r($album);
$photo_count = 0;
for ($i = 0; $i<$photo_per_row; $i++)
{
	if ($photo_count < count($album))
	{
		$nome_foto = $site_abs_path."custom/album/$anno/".$album[$photo_count][$id_nomefile_foto];
?>
<td align='center' width='<?php echo round(100/$photo_per_row); ?>%' valign='middle'>
		<table border='0' cellpadding='0' cellspacing='0'>
			<tr>
				<td colspan='3'><img src="<?php echo $site_abs_path ?>custom/images/cornice/Bord_TL.gif" width="30" height="16" border="0" /><img src='<?php echo $site_abs_path ?>custom/images/cornice/Bord_T.gif' width='105' height='16' border='0' /><img src='<?php echo $site_abs_path ?>custom/images/cornice/Bord_TR.gif' width='30' height='16' border='0' /></td>
			</tr> <tr>
				<td><img src='<?php echo $site_abs_path ?>custom/images/cornice/Bord_LT.gif' width='16' height='13' border='0' /></td>
				<td rowspan='3' valign=middle><a href='<?php echo $nome_foto ?>'><img src='<?php echo $nome_foto ?>' border='0' width='133' height='100' /></a></td>
				<td><img src='<?php echo $site_abs_path ?>custom/images/cornice/Bord_RT.gif' width='16' height='13' border='0' /></td>
			</tr> <tr>
				<td><img src='<?php echo $site_abs_path ?>custom/images/cornice/Bord_L.gif' width='16' height='74' border='0' /></td>
				<td><img src='<?php echo $site_abs_path ?>custom/images/cornice/Bord_R.gif' width='16' height='74' border='0' /></td>
			</tr> <tr>
				<td><img src='<?php echo $site_abs_path ?>custom/images/cornice/Bord_LB.gif' width='16' height='13' border='0' /></td>
				<td><img src='<?php echo $site_abs_path ?>custom/images/cornice/Bord_RB.gif' width='16' height='13' border='0' /></td>
			</tr> <tr>
				<td colspan='3'><img src='<?php echo $site_abs_path ?>custom/images/cornice/Bord_BL.gif' width='30' height='16' border='0' /><img src='<?php echo $site_abs_path ?>custom/images/cornice/Bord_B.gif' width='105' height='16' border='0' /><img src='<?php echo $site_abs_path ?>custom/images/cornice/Bord_BR.gif' width='30' height='16' border='0' /></td>
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
$rem_count = 0;
for ($i = 0; $i<$photo_per_row; $i++)
{
	if ($rem_count < count($album))
	{
		$nome_foto = $site_abs_path."custom/album/$anno/".$album[$rem_count][$id_nomefile_foto];
?>
			<td align="center" valign="top" width="<?php echo round(100/$photo_per_row); ?>%">
					<a href="<?php echo $nome_foto ?>">
					<?php echo $album[$rem_count][$id_titolo_foto]?></a>
					
						<br>
						<font size="-2">
						<?php echo $album[$rem_count][$id_descrizione_foto] ?>
						</font>
					
					
				</td>
<?php
	} // if ($rem_count <= count($elenco_foto))
	$rem_count++;
} // for
?>				
				
</tr>
</tbody></table>

</body>
</html>
