<?php
/*

input impliciti:

$last_contents_num_items 	: numero di elementi da visualizzare

*/

if (!empty($root_path))
{
	$relative_path = '';
	$standalone = false;
}
else
{
	$relative_path = '../../../';
	require_once($relative_path.'libreria.php');
	
	$quiet_layout = 1;
	require_once($relative_path.'layout.php');
	$standalone = true;
}

if ($standalone)
{
?><!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 TRANSITIONAL//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>

<head>
  <title>Caposele Web - Associazione ARS Amatori Running Sele - Caposele sul Web</title>
  <meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1">
  <meta name="GENERATOR" content="Quanta Plus">
  <meta name="description" content="elenco dei siti caposelesi, a cura dell'Associazione ARS Amatori Running Sele">
  <meta name="keywords" content="Associazione,ARS,Amatori Running Sele, notizie, flash, bacheca, news, ultimi aggiornamenti">
  <style type="text/css">@import "/work/ars/custom/config/style.css";</style>

</head>


<body>
<table>
<?php
} // end if ($standalone)
?>

<?php
// inizio blocco core del modulo

require_once($relative_path.'last_contents_lib.php');

$feed = read_last_contents();
publish_layout_item($feed,$module_name,$relative_path,$last_contents_num_items);

?>
			

<?php
if ($standalone)
{
?>
</table>
</body>
<?php
} // end if ($standalone)
?>
