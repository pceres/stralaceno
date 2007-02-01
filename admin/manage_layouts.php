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
if ( !isset($_SERVER['HTTP_REFERER']) | ("http://".$_SERVER['HTTP_HOST'].$script_abs_path."admin/" !== substr($_SERVER['HTTP_REFERER'],0,strrpos($_SERVER['HTTP_REFERER'],'/')+1) ) |
(!in_array($login['status'],array('ok_form','ok_cookie'))) )
{
	header("Location: ".$script_abs_path."index.php");
	exit();
}

// input alla pagina
// $sezione = sanitize_user_input($_REQUEST['section'],'plain_text',Array());

// titolo relativo alla sezione in esame
// switch ($sezione)
// {
// case '':
// case 'homepage':
// 	$tag_sezione = "in prima pagina";
// 	break;
// default:
// 	$tag_sezione = "nella sezione &quot;$sezione&quot;";
// 	break;
// }

// individua le sezioni disponibili
$lista_sezioni = get_section_list(); // individua le sezioni disponibili

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 TRANSITIONAL//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
  <title>Gestione layout<?php echo $tag_sezione; ?></title>
  <meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1">
  <META http-equiv="Content-Script-Type" content="text/javascript">
  <meta name="GENERATOR" content="Quanta Plus">
  <style type="text/css">@import "<?php echo $filename_css ?>";</style>
</head>

<body class="admin">


<center>

<!-- 
gestione articoli disponibili
-->
<table class="admin" align="center">
	<caption>Scegli il file di layout che vuoi modificare:</caption>

	<tbody>
<?php

$indici_homepage = indici('homepage');

$file_layout_left_homepage = $indici_homepage['filename_layout_left'];
$file_layout_left_homepage = substr($file_layout_left_homepage,strrpos($file_layout_left_homepage,'/')+1);

$file_layout_right_homepage = $indici_homepage['filename_layout_right'];
$file_layout_right_homepage = substr($file_layout_right_homepage,strrpos($file_layout_right_homepage,'/')+1);

foreach ($lista_sezioni as $id => $sezione)
{
	$indici_sezione = indici($sezione);
	
	$file_layout_left = $indici_sezione['filename_layout_left'];
	$file_layout_left = substr($file_layout_left,strrpos($file_layout_left,'/')+1);
	$testo_col_sinistra = Array('','');
	if (($sezione == 'homepage') | ($file_layout_left !== $file_layout_left_homepage))
	{
		$testo_col_sinistra = Array('left' => "<a href=\"manage_config_file.php?config_file=$file_layout_left\">",'right' => "</a>");
	}
	
	$file_layout_right = $indici_sezione['filename_layout_right'];
	$file_layout_right = substr($file_layout_right,strrpos($file_layout_right,'/')+1);
	$testo_col_destra = Array('','');
	if (($sezione == 'homepage') | ($file_layout_right !== $file_layout_right_homepage))
// 	if ($file_layout_right !== $file_layout_right_homepage)
	{
		$testo_col_destra = Array('left' => "<a href=\"manage_config_file.php?config_file=$file_layout_right\">",'right' => "</a>");
	}
	
?>
		<tr>
		<td>Sezione <?php echo $sezione; ?></td>
		<td><?php echo $testo_col_sinistra['left']; ?>Colonna sinistra<?php echo $testo_col_sinistra['right']; ?></td>
		<td><?php echo $testo_col_destra['left']; ?>Colonna destra<?php echo $testo_col_destra['right']; ?></td>
		</tr>
<?php
} // end if

?>
	</tbody>
</table>


</center>


<?php
# logga il contatto
$counter = count_page("admin_layout",array("COUNT"=>1,"LOG"=>1),$filedir_counter); # abilita il contatore, senza visualizzare le cifre, e fai il log
?>

<hr>
<div align="right"><a href="index.php" class="txt_link">Torna alla pagina amministrativa principale</a></div>

</body>
</html>
