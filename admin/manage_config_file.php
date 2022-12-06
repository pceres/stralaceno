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
if (
    !isset($_SERVER['HTTP_REFERER']) // referer not set ...
    | (strlen(strpos(substr($_SERVER['HTTP_REFERER'] ,0,strrpos($_SERVER['HTTP_REFERER'] ,'/')+1),"://".$_SERVER['HTTP_HOST'].$script_abs_path."admin/").' ') == 1) // ...or (referer ~= link_from_admin_pages)
    | (!in_array($login['status'],array('ok_form','ok_cookie'))) // ...or login_was_not_successful
   )
{
	header("Location: ".$script_abs_path."index.php");
	exit();
}

$filename = $_REQUEST['config_file'];

if (empty($filename))
{
	die("File inesistente!");
}


// carica le infomazioni relative a ciascun file di configurazione
$elenco_cfgfile = get_cfgfile_data($filename_cfgfile); // carica il file di configurazione dei moduli

// verifica se il file indicato e' nell'elenco, e restituisci i relativi dati
$file_ok = false;
foreach ($elenco_cfgfile as $name => $config_data)
{
	$dir = template_to_effective($config_data[$indice_cfgfile_folder]);
	$caption = $config_data[$indice_cfgfile_caption];
	$groups_ok = $config_data[$indice_cfgfile_groups];
	$password_ok = $config_data[$indice_cfgfile_password];
	
	// verifica la presenza di carattere jolly "?"
	while ($pos=strpos($name,'?'))
	{
		$name[$pos]=$filename[$pos];
	}
	
	if ($name === $filename)
	{
		$file_ok = true;
		break;
	}
}

if (!$file_ok)
{
	die("Non e' possibile modificare il file $filename! Contattare il webmaster.");
}

// verifica che il gruppo di appartenenza sia abilitato qui
if (!group_match(explode(',',$usergroups),explode(',',$groups_ok)))
{
	die("Spiacente, non sei abilitato ad accedere a questa pagina! Contatta l'amministratore.<br>\n");
}


	
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 TRANSITIONAL//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
  <title>Modifica file di configurazione <?php echo $filename; ?></title>
  <meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1">
  <meta name="GENERATOR" content="Quanta Plus">
  <style type="text/css">@import "<?php echo $filename_css ?>";</style>
</head>

<body class="admin">

<script type="text/javaScript" src="<?php echo $site_abs_path ?>admin/md5.js"></script>

Modifica del file di configurazione <?php echo "$filename ($caption)"; ?>:<br>

<?php
$filename = $dir.$filename;
?>
<form action="upload_text.php" method="post" onSubmit="cripta_campo_del_form(this,'password')">
	<input type="hidden" name="filename" value="<?php echo $filename ?>">
	<?php
	$bulk = file($filename);
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




<?php
# logga il contatto
$counter = count_page("admin_edit_config",array("COUNT"=>1,"LOG"=>1),$filedir_counter); # abilita il contatore, senza visualizzare le cifre, e fai il log

?>

<hr>

<div align="right"><a href="index.php" class="txt_link">Torna alla pagina amministrativa principale</a></div>

</body>
</html>
