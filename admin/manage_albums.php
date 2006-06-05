<?php

require_once('../libreria.php');

# dichiara variabili
extract(indici());

/*
questa libreria esamina i cookies o i parametri http (eventualmente) inviati, e genera l'array $login con i campi
'username',	: login
'usergroups',	: lista dei gruppi di appartenenza (separati da virgola)
'status',		: stato del login: 'none','ok_form','ok_cookie','error_wrong_username','error_wrong_userpass','error_wrong_challenge','error_wrong_IP'
*/
require_once('../login.php');


// verifica che si stia arrivando a questa pagina da quella amministrativa principale
if ( !isset($_SERVER['HTTP_REFERER']) | ("http://".$_SERVER['HTTP_HOST'].$script_abs_path."admin/" != substr($_SERVER['HTTP_REFERER'],0,strrpos($_SERVER['HTTP_REFERER'],'/')+1) ) |
(!in_array($login['status'],array('ok_form','ok_cookie'))) )
{
	header("Location: ".$script_abs_path."index.php");
	exit();
}

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 TRANSITIONAL//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
  <title>Gestione album</title>
  <meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1">
  <meta name="GENERATOR" content="Quanta Plus">
  <style type="text/css">@import "<?php echo $filename_css ?>";</style>
</head>

<body class="admin">

<script type="text/javaScript" src="<?php echo $site_abs_path ?>admin/md5.js"></script>
<script type="text/javascript">
//<![CDATA[
<!--

function do_action(action,data)
{
	
	switch (action)
	{
	case "new_album":
		data = document.forms["form_new_album"].data.value;
		
		var msg="Confermi la creazione dell'album \""+data+"\"?";
		if (!confirm(msg))
		{
			return false;
		}
		
		document.forms["form_new_album"].password.value=hex_md5(document.forms["form_new_album"].password.value);
		document.forms["form_new_album"].task.value='create_album';
		document.forms["form_new_album"].submit();
		
		break;
	case "cancel_album":
		
		var msg="Confermi la cancellazione dell'album \""+data+"\"?";
		if (!confirm(msg))
		{
			return false;
		}
		
		document.forms["form_new_album"].password.value=hex_md5(document.forms["form_new_album"].password.value);
		document.forms["form_new_album"].data.value=data;
		document.forms["form_new_album"].task.value='cancel_album';
		document.forms["form_new_album"].submit();
		
		break;
	}
}

//-->
//]]>
</script>

<script type="text/javaScript" src="<?php echo $site_abs_path ?>admin/md5.js"></script>

Albums attuali:<br>
<small>formato:  file::titolo::descrizione::nomi_persone</small>

<form action="upload_text.php" method="post" onSubmit="cripta_campo_del_form(this,'password')">
	<input type="hidden" name="filename" value="<?php echo $filename_albums ?>">
	<?php
	$bulk = file($filename_albums);
	echo "<textarea name=\"testo\" rows=15 cols=120>";
	for ($i = 0; $i < count($bulk); $i++)
	{
		echo $bulk[$i];
	}
	echo "</textarea>\n";
	?>
	Password: <input name="password" type="password">
	<input type="submit" value="Invia File">
</form>

<hr>
Visualizza tutte le foto disponibili per ciascun album:<br>
<form name="debug_album" action="<?php echo $script_abs_path; ?>album.php" method="post">
<?php
$elenco_foto = get_config_file($filename_albums,4);

// verifica se ci sono directory nascoste (cioe' esistono in $album_dir, e non c'e' la rispettina riga [nome_album] nel file di configurazione)
if ($dh = opendir($album_dir)) 
{
	while (($file = readdir($dh)) !== false)
	{
	   if (is_dir($album_dir.$file) && !in_array(strtoupper($file),array('.','..','CVS','.CVS')))
		{
			if (!isset($elenco_foto[$file]))
			{
				$elenco_foto[$file] = array();
			}
		}
	}
	closedir($dh);
}

foreach ($elenco_foto as $anno => $album)
{
	echo "<span style=\"white-space: nowrap;\"><a href=\"$script_abs_path"."album.php?anno=$anno&amp;password=show_all_photos\">$anno</a>\n ";
	echo "<small>(<a onClick=\"do_action('cancel_album','$anno')\">Cancella</a>)&nbsp;&nbsp;</small></span>\n ";
}
?>
</form>

<hr>

<form name="form_new_album" action="<?php echo $script_abs_path; ?>admin/manage_album.php" method="post">
	Crea un nuovo album: <input type="edit" name="data">
	<input type="hidden" name="task">
	Password: <input name="password" type="password">
	<input type="submit" value="Invia File" onClick="do_action('new_album','')">
</form>

<?php
# logga il contatto
$counter = count_page("admin_albums",array("COUNT"=>1,"LOG"=>1),$filedir_counter); # abilita il contatore, senza visualizzare le cifre, e fai il log
?>

<hr>
<div align="right"><a href="index.php" class="txt_link">Torna alla pagina amministrativa principale</a></div>

</body>
</html>
