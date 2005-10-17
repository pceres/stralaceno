<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 TRANSITIONAL//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<?php

require_once('libreria.php');

# dichiara variabili
extract(indici());

$nome_album = $_REQUEST['album'];
$id_photo = $_REQUEST['id_photo'];

?>
<head>
  <title><?php echo $web_title ?> - Album fotografico</title>
  <meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1">
  <meta name="GENERATOR" content="Quanta Plus">
  <meta name="description" content="Foto <?php echo $id_photo; ?> dell'album <?php echo $nome_album; ?> della <?php echo $race_name; ?>">
  <meta name="keywords" content="album fotografico, foto">
  <style type="text/css">@import "<?php echo $filename_css ?>";</style>
</head>

<?php 

// carica elenco delle foto disponibili
$elenco_foto = get_config_file($filename_albums,4);
$id_nomefile_foto = 0;
$id_titolo_foto = 1;
$id_descrizione_foto = 2;
$id_descrizione_persone = 3;

$album = $elenco_foto[$nome_album];

$photo_per_row = 3; // numero di foto per riga

?>


<body onLoad="FixPhoto()">

<script type="text/javascript">
//<![CDATA[
<!--

function FixPhoto() {

var table;

if (document.all)
{ // IE
	table = document.all.photo_table;
}
else if (document.getElementById)
{ // NS
	table = document.getElementById('photo_table'); 
}

FixSinglePhoto(table);

} // end FixPhoto


function FixSinglePhoto(table) {

var cell_L,cell_R,image;
var	x;

if (document.all)
{ // IE
	cell_L=table.cells[4];
	cell_R=table.cells[5];
	image=document.all.foto;
}
else if (document.getElementById)
{ // NS
	cell_L=table.tBodies[0].rows[2].cells[0];
	cell_R=table.tBodies[0].rows[2].cells[1];
	image=document.getElementById('foto');
}

x = image.height; // altezza della foto
	
cell_L.height=x-13*2;
cell_R.height=x-13*2;

} // end FixSinglePhoto


function VisualizzaNomi(mode) {

	if (document.all)
	{ // IE
		msg = document.all.msg_descrizione_nomi;
		nomi = document.all.descrizione_nomi;
	}
	else if (document.getElementById)
	{ // NS
		msg = document.getElementById('msg_descrizione_nomi'); 
		nomi = document.getElementById('descrizione_nomi'); 
	}

//	alert(nomi);
	if (mode==1)
	{
	msg.style.display="none";
	nomi.style.display="block";
	}
	else
	{
	msg.style.display="block";
	nomi.style.display="none";
	}
	
} // end VisualizzaNomi

//-->
//]]>
</script>


		<img src="<?php echo $site_abs_path ?>custom/images/cornice/Null.jpg" border="0" height="10" width="1">
		
		<!-- tabella riga header -->
		<table border="0" cellpadding="0" cellspacing="0" width="100%"><tbody>
			<tr>
				<td colspan="<?php echo $photo_per_row-1; ?>">
					<font color="#000000" face="Times New Roman,Georgia,Times" size="4">
						&nbsp;&nbsp;&nbsp;&nbsp;
						<a href='album.php?anno=<?php echo $nome_album ?>'><?php echo $nome_album ?></a>
						/Foto #<?php echo $id_photo+1 ?>:
					</font>
				</td>
					
				<td valign="top" width="<?php echo round(100/$photo_per_row) ?>%">
					<div align="right">
						<!--h2><a href="index.php">Homepage</a></h2-->
						<h2>&nbsp;</h2>
					</div>
				</td>
			</tr>
		</tbody></table>
		
		<br>
		
		<!-- tabella riga link alla foto precedente e successiva -->
		<table border="0" cellpadding="0" cellspacing="0" width="100%"><tbody>
			<tr valign="top">
				<td width="25%">
					<?php 
					if ($id_photo > 0)
					{
					?>
					<div align="left">
					<font face="Times New Roman,Georgia,Times">
						<a href="show_photo.php?id_photo=<?php echo $id_photo-1 ?>&amp;album=<?php echo $nome_album ?>">(prev) <?php echo $album[$id_photo-1][$id_titolo_foto] ?></a>
					</font>
					</div>
					<?php
					} // if $id_photo > 0
					?>
				</td>
				
<?php 
$path = $site_abs_path."custom/album/$nome_album/".$album[$id_photo][$id_nomefile_foto];
$path = str_replace(' ','%20',$path);
?>
				<td width="50%">
					<div align="center">
						<font color="#000000" face="Times New Roman,Georgia,Times" size="4">
						<a href="<?php echo $path?>"><?php echo $album[$id_photo][$id_titolo_foto]; ?></a></font>
						
						<br>
						<?php if (strlen($album[$id_photo][$id_descrizione_foto])>0) { ?>
							<font face="Times New Roman,Georgia,Times">
							<?php echo $album[$id_photo][$id_descrizione_foto]; ?></font>
						<?php } // end if ?>
					</div>
				</td>
				
				<td width="25%">
					<?php 
					if ($id_photo < count($album)-1)
					{
					?>
					<div align="right">
					<font face="Times New Roman,Georgia,Times">
						<a href="show_photo.php?id_photo=<?php echo $id_photo+1 ?>&amp;album=<?php echo $nome_album ?>">(next) <?php echo $album[$id_photo+1][$id_titolo_foto] ?></a>
					</font>
					</div>
					<?php
					} // if $id_photo > 0
					?>
				</td>
				
			</tr>
		</tbody></table>
		
		
		<table border="0" cellpadding="0" cellspacing="0" width="100%"><tbody>
					<tr><td align="center">
					
						<!-- foto incorniciata -->
						<table id="photo_table" border="0" cellpadding="0" cellspacing="0" class="photo"><tbody>
						<tr>
							<td colspan="3"><img src="<?php echo $site_abs_path ?>custom/images/cornice/Bord_TL.gif" border="0" height="16" width="30" alt="TL"><img src="<?php echo $site_abs_path ?>custom/images/cornice/Bord_T.gif" border="0" height="16" width="392" alt="T"><img src="<?php echo $site_abs_path ?>custom/images/cornice/Bord_TR.gif" border="0" height="16" width="30" alt="TR"></td>
						</tr>
						
						<tr>
							<td valign="top"><img src="<?php echo $site_abs_path ?>custom/images/cornice/Bord_LT.gif" border="0" height="13" width="16" alt="LT"></td>
							<td rowspan="3" valign="middle"><a href="<?php echo $path ?>"><img id="foto" src="<?php echo $path ?>" alt="" border="0" width="420"></a></td>
							<td><img src="<?php echo $site_abs_path ?>custom/images/cornice/Bord_RT.gif" border="0" height="13" width="16" alt="RT"></td>
						</tr>
						
						<tr>
							<td style="background-image:url(<?php echo $site_abs_path ?>custom/images/cornice/Bord_L.gif);">&nbsp;</td>
							<td style="background-image:url(<?php echo $site_abs_path ?>custom/images/cornice/Bord_R.gif);">&nbsp;</td>
						</tr>
						
						<tr>
							<td valign="bottom"><img src="<?php echo $site_abs_path ?>custom/images/cornice/Bord_LB.gif" border="0" height="13" width="16" alt="LB"></td>
							<td valign="bottom"><img src="<?php echo $site_abs_path ?>custom/images/cornice/Bord_RB.gif" border="0" height="13" width="16" alt="RB"></td>
						</tr>
						
						<tr>
							<td colspan="3"><img src="<?php echo $site_abs_path ?>custom/images/cornice/Bord_BL.gif" border="0" height="16" width="30" alt="BL"><img src="<?php echo $site_abs_path ?>custom/images/cornice/Bord_B.gif" border="0" height="16" width="392" alt="B"><img src="<?php echo $site_abs_path ?>custom/images/cornice/Bord_BR.gif" border="0" height="16" width="30" alt="BR"></td>
						</tr>
						
						</tbody></table>
					</td></tr>
<?php
if (count($album[$id_photo][$id_descrizione_persone])>0)
{
?>
			<!-- riga con i nomi dei corridori -->
			<tr>
				<td colspan="3" align="center">
					<span id="msg_descrizione_nomi"><a onclick="VisualizzaNomi(1)">Clicca qui per visualizzare i nomi...</a></span>
				</td>
			</tr>
			<tr>
				<td colspan="3" align="center">
					<span style="display: none;" id="descrizione_nomi" onclick="VisualizzaNomi(0)"><?php echo $album[$id_photo][$id_descrizione_persone] ?></span>
				</td>
			</tr>
<?php
} // end if
?>
			
			<!-- riga vuota per distanziare la riga successiva -->
			<tr>
				<td height="20"><img src="<?php echo $site_abs_path ?>custom/images/cornice/Null.jpg" border="0" height="20" width="20"></td>
			</tr>
			
			<!-- riga footer -->
			<tr>
				<td>
					<div align="center"><font color="#000000" face="Verdana">
						Album rendered on <?php echo date("F j, Y, g:i a") ?>
					</font></div>					
				</td>
			</tr>

			<!-- riga vuota per distanziare la riga successiva -->
			<tr>
				<td height="20"><img src="<?php echo $site_abs_path ?>custom/images/cornice/Null.jpg" border="0" height="20" width="20"></td>
			</tr>

<?php
if (strlen($email_info)>0) { // se e' disponibile una email per i contatti
?>
			<tr><td>
				<div align="center">
					Chi desiderasse, per motivi di privacy, non apparire in fotografia, lo comunichi a 
					<a href="mailto:<?php echo $email_info?>?subject=Cancellazione%20foto%20per%20privacy" 
					title="Cancellazione foto per privacy">questo indirizzo</a>
				</div>
			</td></tr>
<?php
} // end if (strlen($email_info)>0) { // se e' disponibile una email per i contatti
?>
			
		</tbody></table>
<?php

echo $homepage_link;

# logga il contatto (modifico la query string per aggiungere nei log una informazione diretta alla foto visualizzata)
$ks = $_SERVER['QUERY_STRING'];
//$ks = ereg_replace('^id_photo=[0-9]+&','',$ks); # elimina 'id_photo=xxx'
$ks = $ks."(".$album[$id_photo][$id_nomefile_foto].")";
$_SERVER['QUERY_STRING'] = $ks;
$counter = count_page("foto",array("COUNT"=>1,"LOG"=>1),$filedir_counter); # abilita il contatore, senza visualizzare le cifre, e fai il log

?>
	</body></html>
