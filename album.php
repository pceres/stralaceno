#!/usr/local/bin/php
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 TRANSITIONAL//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<?php

require_once('libreria.php');

# dichiara variabili
extract(indici());

$anno = $_REQUEST['anno'];
?>
<head>
  <title><?php echo $web_title ?> - Album fotografico</title>
  <meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1">
  <meta name="GENERATOR" content="Quanta Plus">
  <meta name="description" content="Album fotografico dell'edizione <?php echo $anno; ?> della <?php echo $race_name; ?>">
  <meta name="keywords" content="album fotografico, edizione <?php echo $anno; ?>">
  <style type="text/css">@import "<?php echo $filename_css ?>";</style>
</head>

<?php 

// carica elenco delle foto disponibili
$elenco_foto = get_config_file($filename_albums,4);
$id_nomefile_foto = 0;
$id_titolo_foto = 1;
$id_descrizione_foto = 2;
$id_descrizione_persone = 3;

$album = $elenco_foto[$anno];

# aggiungi foto invisibili (quelle presenti nella relativa directory, ma nonnel file di configurazione album.txt
$password = $_REQUEST['password'];
//print_r($_REQUEST);
//die();

# eventuale password temporanea (stringa vuota "" per disabilitarla)
$temp_password = "caposeleonline";
$temp_timeout = mktime(24,00,00,9,30,2005); // scade il 24:00:00 del 30 settembre 2005
$temp_album = "2005"; // album abilitati per la password temporanea

// password per pagina amministrativa
$debug_mode = FALSE;
if ($password=="show_all_photos")
{
	$debug_mode = TRUE;
}

// eventuale password temporanea
if ( ($anno === $temp_album) && (!empty($temp_password)) && ($password==$temp_password) )
{
	if (time() <= $temp_timeout)
	{
		$debug_mode = TRUE;
	}
	else
	{
		die("La password e' scaduta!");
	}
}

if ($debug_mode)
{
	$dir = $root_path."custom/album/$anno/";
	$lista_online = array();
	foreach ($album as $foto)
	{
		array_push($lista_online,$foto[0]);
	}
	$album2 = array();
	if (is_dir($dir)) {
		if ($dh = opendir($dir)) {
			while (($file = readdir($dh)) !== false) {
				if ((filetype($dir . $file) == "file") & (strpos($dir.$file,"thumb",0) == 0))
				{
					if (in_array($file,$lista_online))
					{
						$item_data = $album[array_search($file,$lista_online)];
					}
					else
					{
						$item_data = array($file,"Foto ".(count($album2)+1)." (invisibile)",$file);
					}
					$album2[count($album2)] = $item_data;
				}
			}
		closedir($dh);
		}
	}
	else
	{
		die("L'album $anno non esiste!");
	}
	$album=$album2;
}

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


		<table border="0" cellpadding="0" cellspacing="0" width="100%">
			<tbody>
			
			<!-- riga vuota -->
			<tr>
				<td colspan="<?php echo $photo_per_row ?>" height="10"><img src="<?php echo $site_abs_path ?>custom/images/cornice/Null.jpg" border="0" height="10" width="11"></td>
			</tr>
			
			
			<!-- riga descrizione album -->
<?php
# se il titolo dell'album $anno e' presente nell'archivio tempi, allora metti il link ai tempi di quell'edizione
$archivio = load_data($filename_tempi,$num_colonne_prestazioni);
$descrizione_album = "album <b>$anno</b>";
foreach ($archivio as $prestazione)
{
	if ($prestazione[$indice_anno] == $anno)
	{
		$descrizione_album = "<a href=\"filtro4.php?anno=$anno\">edizione $anno</a>";
	}
}
?>
			<tr>
				<td colspan="<?php echo $photo_per_row-1; ?>">
					<font color="#000000" face="Times New Roman,Georgia,Times" size="4">
						&nbsp;&nbsp;&nbsp;&nbsp;
						Foto disponibili per l'<?php echo $descrizione_album ?>:
					</font></td>
					
				<td valign="top" width="<?php echo round(100/$photo_per_row) ?>%">
					<div align="right">
						<h2>&nbsp;</h2>
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
		
		if (!$debug_mode) // se non sei in modalita' debug, punta a show_photo con l'id adeguato
		{
			$link_foto = "show_photo.php?id_photo=$photo_count&amp;album=$anno";
		}
		else // altrimenti punta direttamente alla foto
		{
			$link_foto = $site_abs_path."custom/album/$anno/".$album[$photo_count][$id_nomefile_foto];
		}
?>

<td width="<?php echo round(100/$photo_per_row); ?>%"><table><tbody>

<!--  foto incorniciata  -->
<tr>
<td align='center' width="<?php echo round(100/$photo_per_row); ?>%" valign='middle'>
		<table id="thumb_table_<?php echo $photo_count ?>" border='0' cellpadding='0' cellspacing='0'>
			<tr>
				<td colspan='3'><img src="<?php echo $site_abs_path ?>custom/images/cornice/Bord_TL.gif" alt="TL" width="30" height="16" border="0" /><img src='<?php echo $site_abs_path ?>custom/images/cornice/Bord_T.gif' alt="T" width='105' height='16' border='0' /><img src='<?php echo $site_abs_path ?>custom/images/cornice/Bord_TR.gif' alt="TR" width='30' height='16' border='0' /></td>
			</tr> <tr>
				<td><img src='<?php echo $site_abs_path ?>custom/images/cornice/Bord_LT.gif' alt="LT" width='16' height='13' border='0' /></td>
				<td rowspan='3' valign=middle><a href='<?php echo $link_foto?>'><img id="img_<?php echo $photo_count ?>" src='<?php echo $nome_foto ?>' border='0' width='133' /></a></td>
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
</tr>

<!--  didascalia (titolo e descrizione)  -->
<tr>

			<td align="center" width="<?php echo round(100/$photo_per_row); ?>%" valign="top" >
					<a href='show_photo.php?id_photo=<?php echo $rem_count ?>&amp;album=<?php echo $anno ?>'>
					<?php echo $album[$rem_count][$id_titolo_foto]?></a>
					
						<br>
						<?php if (strlen($album[$rem_count][$id_descrizione_foto])>0) { ?>
						<font size="-2">
						<?php echo $album[$rem_count][$id_descrizione_foto] ?>
						</font>
						<?php } // end if ?>
					
					
				</td>

</tr>

<!--  riga vuota sotto la foto e la didascalia -->
<tr><td><br></td></tr> 

</tbody></table></td>

<?php
	} // if ($photo_count <= count($elenco_foto))
	else
	{
	?>
	
<!-- foto fittizia -->
<td width="<?php echo round(100/$photo_per_row); ?>%" align="center" valign="middle"><table><tbody>
<tr><td>&nbsp;</td></tr>
<tr><td>&nbsp;</td></tr>
<tr><td>&nbsp;</td></tr> 
</tbody></table></td>

	<?php
	}// else  ($photo_count <= count($elenco_foto))
	$photo_count++;
	$rem_count++;
} // for
?>				

	
</tr>

<?php 
} // for ($riga=0; $riga<2; $riga++)
?>

</tbody></table>

<br>

<?php

echo $homepage_link;

# logga il contatto
$counter = count_page("album",array("COUNT"=>1,"LOG"=>1),$filedir_counter); # abilita il contatore, senza visualizzare le cifre, e fai il log

?>

</body>
</html>
