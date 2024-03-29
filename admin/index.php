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

// verifica che si stia arrivando a questa pagina da ../index.php (unico punto di accesso al sottodominio admin/), oppure da altre pagine in admin/
$referer = $_SERVER['HTTP_REFERER']; // eg. 'https://localhost/work/ars.git/index.php' or 'https://localhost/work/ars.git/admin/manage_config_file.php?config_file=lotteria_012_ans.php'
$debug_login = 1;
if (
    !isset($_SERVER['HTTP_REFERER']) // referer not set ...
    | ( (strlen(strpos($referer,"://".$_SERVER['HTTP_HOST'].$script_abs_path."index.php").' ') == 1) &
    (strlen(strpos(substr($referer ,0,strrpos($referer ,'/')+1),"://".$_SERVER['HTTP_HOST'].$script_abs_path."admin/").' ') == 1) ) // ...or (referer ~= exact_link_from_homepage) & (referer ~= link_from_admin_pages) )
    | (!in_array($login['status'],array('ok_form','ok_cookie'))) // ...or login_was_not_successful
   )
{
    // bad login
    if ($debug_login) {
        // if debug, show info and die...
        echo("(referer not set) | ( (referer ~= exact_link_from_homepage) & (referer ~= link_from_admin_pages) ) | (login_was_not_successful): ");
        echo("  (".!isset($_SERVER['HTTP_REFERER']).") | ( (".
        (strlen(strpos($referer,"://".$_SERVER['HTTP_HOST'].$script_abs_path."index.php").' ') == 1) .") & (".
        (strlen(strpos(substr($referer ,0,strrpos($referer ,'/')+1),"://".$_SERVER['HTTP_HOST'].$script_abs_path."admin/").' ') == 1)   .") ) | (".
        (!in_array($login['status'],array('ok_form','ok_cookie'))).")<br>");
        echo("<br>");
        echo("Input:<br>");
        echo("_SERVER_HTTP_REFERER:(".($_SERVER['HTTP_REFERER']).")<br>");
        echo("referer:(".$referer.")<br>");
        echo("_SERVER_HTTP_HOST:(".$_SERVER['HTTP_HOST'].")<br>");
        echo("login_status:(".$login['status'].")<br>");
        echo("<br>");
        echo("exact_link_from_homepage:<br>");
        echo("($referer)<br>");
        echo("(http://".$_SERVER['HTTP_HOST'].$script_abs_path."index.php)<br>");
        echo("<br>");
        echo("link_from_admin_pages:<br>");
        echo("(". "http://".$_SERVER['HTTP_HOST'].$script_abs_path."admin/" .")<br>");
        echo("(". substr($referer ,0,strrpos($referer ,'/')+1) .")<br>");
        echo("<br>");
        die("error in session mgm: please report to administrator");
    } else {
        // ...else redirect to homepage
        header("Location: ".$script_abs_path."index.php");
        exit();
    }
}

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 TRANSITIONAL//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
  <title>Amministrazione</title>
  <meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1">
  <meta name="GENERATOR" content="Quanta Plus">
  <style type="text/css">@import "<?php echo $filename_css ?>";</style>
</head>

<body class="admin">

<?php

// verifica che l'utente sia autorizzato per l'operazione richiesta
$res = check_auth('admin_index','',$login['username'],$login['usergroups']);
if (!$res)
{
	die("Mi dispiace, non sei autorizzato!");
}

?>

<script type="text/javaScript" src="<?php echo $site_abs_path ?>admin/md5.js"></script>

<?php 
// solo il root admin ha accesso qui
if (group_match($username,explode(',',$usergroups),array("root_admin")))
{
?>
<form action="manage_root_admin.php" method="post" onSubmit="cripta_campo_del_form(this,'password')">
Gestione amministrativa di root: Password: <input name="password" type="password">
<input type="submit" value="Vai">
</form>

<hr>
<?php 
}
?>


<?php 
$name = $filename_tempi;
$name = substr($name,strrpos($name,'/')+1) 
?>
<form enctype="multipart/form-data" action="upload.php" method="post" onSubmit="cripta_campo_del_form(this,'password')">
<input type="hidden" name="MAX_FILE_SIZE" value="30000">
<input type="hidden" name="filename" value="<?php echo $name ?>">
Nuovo file "<?php echo $name ?>" da caricare: <input name="userfile" type="file">
Password: <input name="password" type="password">
<input type="submit" value="Invia File">
</form>

<?php 
$name = $filename_atleti;
$name = substr($name,strrpos($name,'/')+1) 
?>
<form enctype="multipart/form-data" action="upload.php" method="post" onSubmit="cripta_campo_del_form(this,'password')">
<input type="hidden" name="MAX_FILE_SIZE" value="30000">
<input type="hidden" name="filename" value="<?php echo $name ?>">
Nuovo file "<?php echo $name ?>" da caricare: <input name="userfile" type="file">
Password: <input name="password" type="password">
<input type="submit" value="Invia File">
</form>

<?php 
$name = $filename_organizzatori;
$name = substr($name,strrpos($name,'/')+1) 
?>
<form enctype="multipart/form-data" action="upload.php" method="post" onSubmit="cripta_campo_del_form(this,'password')">
<input type="hidden" name="MAX_FILE_SIZE" value="30000">
<input type="hidden" name="filename" value="<?php echo $name ?>">
Nuovo file "<?php echo $name ?>" da caricare: <input name="userfile" type="file">
Password: <input name="password" type="password">
<input type="submit" value="Invia File">
</form>

<hr>

Gestione <a href='manage_layouts.php'>layout (colonna destra e sinistra)</a> 

<hr>

<a href='articoli.php'>Gestione articoli</a>

<hr>

<a href='manage_config_file.php?config_file=links.txt'>Gestione links</a>

<hr>

<a href='manage_albums.php'>Gestione album</a>

<hr>

<a href='manage_modules.php'>Gestione moduli</a>

<hr>

<a href='manage_downloads.php'>Gestione sezione download</a>

<hr>

<form action="manage_questions.php?task=index" method="post" onSubmit="cripta_campo_del_form(this,'password')">
Gestione lotterie e questionari: Password: <input name="password" type="password">
<input type="submit" value="Vai">
</form>

<?php
# logga il contatto
$counter = count_page("admin_index",array("COUNT"=>1,"LOG"=>1),$filedir_counter); # abilita il contatore, senza visualizzare le cifre, e fai il log
?>

<?php
echo $homepage_link;
?>

</body>
</html>
