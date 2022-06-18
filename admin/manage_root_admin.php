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


// verifica che si stia arrivando a questa pagina da ../index.php
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
  <title>Amministrazione di root</title>
  <meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1">
  <meta name="GENERATOR" content="Quanta Plus">
  <style type="text/css">@import "<?php echo $filename_css ?>";</style>
</head>

<body class="admin">

<script type="text/javaScript" src="<?php echo $site_abs_path ?>admin/md5.js"></script>

<?php 

// verifica password
$password = $_REQUEST['password'];
$password_ok = $password_root_admin;

if ($password !== $password_ok)
{
	$referer = $_SERVER['HTTP_REFERER'];
	echo "<a href=\"$referer\">Torna indietro</a><br><br>\n";
	die("La password inserita non &egrave; corretta!<br>\n");
}


// solo il root admin ha accesso qui
if (!group_match(explode(',',$usergroups),array("root_admin")))
{
	die("Solo il root_admin e' abilitato ad accedere a questa pagina!<br>\n");
}
?>

<a href='manage_config_file.php?config_file=users.php'>Gestione accounts</a>

<hr>

<a href='manage_config_file.php?config_file=modules_config.php'>Configurazione generale dei moduli</a>

<hr>

<a href='manage_config_file.php?config_file=<?php echo substr($filename_cfgfile,strrpos($filename_cfgfile,'/')+1); ?>'>
Configurazione file di configurazione</a>



<?php
# logga il contatto
$counter = count_page("admin_root_index",array("COUNT"=>1,"LOG"=>1),$filedir_counter); # abilita il contatore, senza visualizzare le cifre, e fai il log
?>

<?php
echo $homepage_link;
?>

</body>
</html>
