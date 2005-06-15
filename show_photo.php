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

$nome_album = $_REQUEST['album'];
$id_photo = $_REQUEST['id_photo'];

// carica elenco delle foto disponibili
$elenco_foto = get_config_file($config_dir."albums.txt",3);
$id_nomefile_foto = 0;
$id_titolo_foto = 1;
$id_descrizione_foto = 2;

$album = $elenco_foto[$nome_album];

//$photo_per_row = 3; // numero di foto per riga

?>

<body>
		<img src="<?php echo $site_abs_path ?>custom/images/cornice/Null.gif" border="0" height="10" width="1">
		
		<table border="0" cellpadding="0" cellspacing="0" width="100%"><tbody>
			
			<!-- riga descrizione foto -->
			<tr>
				<td colspan="<?php echo $photo_per_row-1; ?>">
					<font color="#000000" face="Times New Roman,Georgia,Times" size="4">
						&nbsp;&nbsp;&nbsp;&nbsp;
						<a href='album.php?anno=<?php echo $nome_album ?>'><?php echo $nome_album ?></a>
						/Foto #<?php echo $id_photo+1 ?>:
					</font></td>
					
				<td valign="top" width="<?php echo round(100/$photo_per_row) ?>%">
					<div align="right">
						<a href="index.php"><h2>Homepage</h2></a>
					</div>
				</td>
			</tr>
			
		</tbody></table>
		
		
		<br>
		
		<table border="0" cellpadding="0" cellspacing="0" width="100%">
			<tbody><tr valign="top">
				<td width="25%">

					<div align="left">
					<font face="Times New Roman,Georgia,Times">
					<?php 
					if ($id_photo > 0)
					{
					?>
						<a href="show_photo.php?id_photo=<?php echo $id_photo-1 ?>&album=<?php echo $nome_album ?>">(prev) <?php echo $album[$id_photo-1][$id_titolo_foto] ?></a>
					<?php
					} // if $id_photo > 0
					?>
					</font>
					</div>

				</td>

<?php 
$path = $site_abs_path."custom/album/$nome_album/".$album[$id_photo][$id_nomefile_foto];
?>
				<td width="50%">
					<div align="center">
						<font color="#000000" face="Times New Roman,Georgia,Times" size="4">
						<a href="<?php echo $path?>"><?php echo $album[$id_photo][$id_titolo_foto]; ?></a></font>

						<br>
						<font face="Times New Roman,Georgia,Times">
						<?php echo $album[$id_photo][$id_descrizione_foto]; ?></font>
					</div>
				</td>

				<td width="25%">

					<div align="right">
					<font face="Times New Roman,Georgia,Times">
					<?php 
					if ($id_photo < count($album)-1)
					{
					?>
						<a href="show_photo.php?id_photo=<?php echo $id_photo+1 ?>&album=<?php echo $nome_album ?>">(next) <?php echo $album[$id_photo+1][$id_titolo_foto] ?></a>
					<?php
					} // if $id_photo > 0
					?>
					</font>
					</div>

				</td>
			</tr>
		</tbody></table>

		<table border="0" cellpadding="0" cellspacing="0" width="100%">

			<tbody><tr>
				<td colspan="3" valign="top" width="100%">
					<div align="center"><br>
										<table border="0" cellpadding="0" cellspacing="0">
						<tbody><tr>
							<td colspan="3"><img src="<?php echo $site_abs_path ?>custom/images/cornice/Bord_TL.gif" border="0" height="16" width="30"><img src="<?php echo $site_abs_path ?>custom/images/cornice/Bord_T.gif" border="0" height="16" width="392"><img src="<?php echo $site_abs_path ?>custom/images/cornice/Bord_TR.gif" border="0" height="16" width="30"></td>
						</tr> <tr>
							<td><img src="62.jpg.Binder_files/Bord_LT.gif" border="0" height="13" width="16"></td>
							<td rowspan="3" valign="middle"><a href="<?php echo $path ?>"><img src="<?php echo $path ?>" alt="" border="0" height="280" width="420"></a></td>
							<td><img src="<?php echo $site_abs_path ?>custom/images/cornice/Bord_RT.gif" border="0" height="13" width="16"></td>
						</tr> <tr>
							<td><img src="<?php echo $site_abs_path ?>custom/images/cornice/Bord_L.gif" border="0" height="254" width="16"></td>
							<td><img src="<?php echo $site_abs_path ?>custom/images/cornice/Bord_R.gif" border="0" height="254" width="16"></td>
						</tr> <tr>
							<td><img src="<?php echo $site_abs_path ?>custom/images/cornice/Bord_LB.gif" border="0" height="13" width="16"></td>
							<td><img src="<?php echo $site_abs_path ?>custom/images/cornice/Bord_RB.gif" border="0" height="13" width="16"></td>
						</tr> <tr>
							<td colspan="3"><img src="<?php echo $site_abs_path ?>custom/images/cornice/Bord_BL.gif" border="0" height="16" width="30"><img src="<?php echo $site_abs_path ?>custom/images/cornice/Bord_B.gif" border="0" height="16" width="392"><img src="62.jpg.Binder_files/Bord_BR.gif" border="0" height="16" width="30"></td>
						</tr>
					</tbody></table>

					</div>
				</td>
			</tr>

			<tr>
				<td height="20"><img src="62.jpg.Binder_files/Null.gif" border="0" height="20" width="20"></td>
			</tr>



			<tr>
				<td>
					<div align="center">
						<font color="#000000" face="Verdana">Album rendered on <?php echo date("F j, Y, g:i a") ?></font></div>
				</td>
			</tr>
			<tr>
				<td></td>
			</tr>
		</tbody></table>
	</body></html>