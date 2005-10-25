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

# aggiungi foto invisibili (quelle presenti nella relativa directory, ma non nel file di configurazione album.txt
$password = $_REQUEST['password'];

# eventuale password temporanea (stringa vuota "" per disabilitarla)
$temp_password = "caposeleonline";
$temp_timeout = mktime(24,00,00,9,30,2005); // scade il 24:00:00 del 30 settembre 2005
$temp_albums = array("2005"); // album abilitati per la password temporanea

$admin_mode = FALSE;	// default: non visualizzare i controlli per modificare l'album
$browse_mode = FALSE;	// default: visualizza solo le foto in albums.txt

// password per pagina amministrativa
if ($password=="show_all_photos")
{
	$admin_mode = TRUE;
	$browse_mode = TRUE;
}

// eventuale password temporanea
if ( in_array($anno,$temp_albums) && (!empty($temp_password)) && ($password==$temp_password) )
{
	if (time() <= $temp_timeout)
	{
		$browse_mode = TRUE;
	}
	else
	{
		die("La password e' scaduta!");
	}
}

// integra i dati letti da albums.txt con i file effettivamentepresenti nella directory;
if ($browse_mode)
{
	$image_extensions = array('JPG','GIF','PNG'); // estensioni dei file che saranno considerati immagini
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
				if ((filetype($dir . $file) == "file") & (strpos($dir.$file,"thumb",0) == 0) & in_array(strtoupper(substr($dir.$file,-3)),$image_extensions))
				{
					if (in_array($file,$lista_online)) // la foto e' gia' visibile
					{
						$item_data = $album[array_search($file,$lista_online)];
						$item_data[$id_descrizione_foto] = $file."<br>".$item_data[$id_descrizione_foto]; // siamo in browser mode, visualizza il nome del file
					}
					else // la foto e' invisibile
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

<?php 
if ($admin_mode) 
{
?>
<!-- script amministrativi -->
<script type="text/javaScript" src="<?php echo $site_abs_path ?>admin/md5.js"></script>
<script type="text/javascript">
//<![CDATA[
<!--

function do_action(action,data)
{
	
	switch (action)
	{
	case "cancel":
		var msg="Vuoi davvero cancellare la foto "+data+" ?";
		if (!confirm(msg))
		{
			return false;
		}
		document.forms["form_data"].password.value=hex_md5(document.forms["form_data"].password.value);
		document.forms["form_data"].task.value='cancel';
		document.forms["form_data"].data.value=data;
		document.forms["form_data"].submit();
		
		break;
	case "upload":
		var msg="Confermi la pubblicazione della/e foto?";
		if (!confirm(msg))
		{
			return false;
		}

		document.forms["form_upload"].password.value=hex_md5(document.forms["form_data"].password.value);
		document.forms["form_upload"].submit();

		break;
	}
}

//-->
//]]>
</script>
<?php
} // end if $admin_mode
?>


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
		
		if (!$browse_mode) // se non sei in modalita' debug, punta a show_photo con l'id adeguato
		{
			$link_foto = "show_photo.php?id_photo=$photo_count&amp;album=$anno";
		}
		else // altrimenti punta direttamente alla foto
		{
			$link_foto = $site_abs_path."custom/album/$anno/".$album[$photo_count][$id_nomefile_foto];
			$path_completo_foto = $root_path."custom/album/$anno/".$album[$photo_count][$id_nomefile_foto];
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
					<!--  titolo -->
					<a href='show_photo.php?id_photo=<?php echo $rem_count ?>&amp;album=<?php echo $anno ?>'>
					<?php echo $album[$rem_count][$id_titolo_foto]?></a>
					
					<br>
					
					<!--  descrizione -->
					<?php if (strlen($album[$rem_count][$id_descrizione_foto])>0) { ?>
						<font size="-2">
							<?php echo $album[$rem_count][$id_descrizione_foto] ?>
						</font>
					<?php } // end if ?>
					
					<?php if ($admin_mode) // siamo in modalita' amministrativa, visualizza i controlli per cancellare o caricare una foto
					{ ?>
						<!--  controlli amministrativi -->
						<br><br>
						<font size="-2">
							<a OnClick="return do_action('cancel','<?php echo $path_completo_foto; ?>')">Cancella</a>
						</font>
					<?php
					} ?>
					
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
if ($admin_mode) 
{
?>

<hr>
<p>Carica nuove foto nell'album <b><?php echo $anno ?></b>
 (max 100 kB per le foto, 10 kB per i thumbnail (non necessari, ma raccomandati) ):</p>

<form name="form_upload" enctype="multipart/form-data" action="admin/upload_photo.php" method="post">
	<input type="hidden" name="MAX_FILE_SIZE" value="110000">
	<input type="hidden" name="nome_album" value="<?php echo $anno; ?>">
<!--?php echo "\t<input type=\"hidden\" name=\"filename\" value=\"$anno\">\n"; ?-->
	Nuova foto da caricare (n. 1): <input name="userfoto01" type="file">
		Thumbnail: <input name="userthumb01" type="file"><br>
	Nuova foto da caricare (n. 2): <input name="userfoto02" type="file">
		Thumbnail: <input name="userthumb02" type="file"><br>
	Nuova foto da caricare (n. 3): <input name="userfoto03" type="file">
		Thumbnail: <input name="userthumb03" type="file"><br>
	Nuova foto da caricare (n. 4): <input name="userfoto04" type="file">
		Thumbnail: <input name="userthumb04" type="file"><br>
	<input name="password" type="hidden">
	<input type="Submit" value="Invia Foto" onClick="return do_action('upload','')">
</form>

<?php
}
?>


<?php
if (!$admin_mode) 
{
	echo $homepage_link;

	# logga il contatto
	$counter = count_page("album",array("COUNT"=>1,"LOG"=>1),$filedir_counter); # abilita il contatore, senza visualizzare le cifre, e fai il log
}
else
{
	# logga il contatto
	$counter = count_page("admin_album",array("COUNT"=>1,"LOG"=>1),$filedir_counter); # abilita il contatore, senza visualizzare le cifre, e fai il log
?>
	<!-- Form fittizio per il passaggio dei dati -->
	<form name="form_data" action="admin/manage_album.php" method="post">
		Password: 
		<input name="password" type="password">
		<input name="task" type="hidden">
		<input name="data" type="hidden">
	</form>
	
	<hr>
	<div align="right"><a href="admin/index.php" class="txt_link">Torna alla pagina amministrativa principale</a></div>
	
<?php 
} // end if (!$admin_mode)

?>

</body>
</html>
