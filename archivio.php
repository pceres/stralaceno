<!DOCTYPE public "-//w3c//dtd html 4.01 transitional//en" 
		"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
  <title>Stralaceno Web - Archivio Stralaceno</title>
  <meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1">
  <meta name="GENERATOR" content="Quanta Plus">
</head>
<body>

<table>
  <tbody>
    <tr>
      <td width="100%">
	  <!--img src="/work/stralaceno/images/sweb.gif" alt="logo_stralacenoweb"--> 
	  <img src="images/sweb.gif" alt="logo_stralacenoweb"> 
	  </td>
      <td>
      <!--embed src="/work/stralaceno/images/filmatoflash300x70.swf"
 quality="high" alt="logo_stralacenoweb_flash"
 pluginspage="http://www.macromedia.com/shockwave/download/index.cgi?P1_Prod_Version=3DShockwaveFlash"
 type="application/x-shockwave-flash"--> 
      <embed src="images/filmatoflash300x70.swf" quality="high" alt="logo_stralacenoweb_flash"
 pluginspage="http://www.macromedia.com/shockwave/download/index.cgi?P1_Prod_Version=3DShockwaveFlash"
 type="application/x-shockwave-flash"> 
    </tr>
  </tbody>
</table>

<hr>

<?php

include 'libreria.php';

$archivio = load_data($filename_tempi,$num_colonne_prestazioni);

?>

<ul>


<li>
<form action="filtro4.php" method="POST">

Archivio storico annuale (tutti i risultati di un anno):

<select name="anno">

<?php
$elenco_anni = array();	# elenco degli anni in archivio
$elenco_i = array();		# elenco delle i in corrispondenza delle quali trovare gli anni
for ($i = 1; $i < count($archivio); $i++) {
	$prestazione = $archivio[$i];
	$anno = $prestazione[$indice_anno];
	if (!in_array($anno,$elenco_anni)) {
		array_push($elenco_anni,$anno);
		array_push($elenco_i,$i);
		}
	}
	
array_multisort($elenco_anni,SORT_DESC, SORT_NUMERIC, $elenco_i,SORT_ASC, SORT_NUMERIC);
	
for ($i = 0; $i < count($elenco_anni); $i++) {
	echo "<option value=\"".$elenco_anni[$i]."\">".$elenco_anni[$i]."</option>\n";
	}
?>	

</select>

<p></p>
<input type="submit" value="Mostra prestazioni dell'anno">

</form>
</li>
<br>

<li>
<a href="filtro9.php" name="Classifica personali">Classifica record personali</a>
</li>
<br>

<li>
<a href="filtro7.php" name="Albo d'oro">Albo d'oro</a>
</li>
<br>

<li>
<a href="archivio_approfondito.php">Approfondimenti</a>
</li>

</ul>

<hr>

<div align="right"><i>
L'archivio e' stato consultato 
<?php $counter = action_counter($filename_counter); echo $counter; ?>
 volte.
</i>
</div>

</body>
</html>
