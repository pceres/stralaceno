#!/usr/local/bin/php
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
	  <img src="/work/stralaceno2/images/sweb.gif" alt="logo_stralacenoweb">
	  <!--img src="images/sweb.gif" alt="logo_stralacenoweb"-->
	  </td>
      <td>
      <embed src="/work/stralaceno2/images/filmatoflash300x70.swf"
 quality="high" alt="logo_stralacenoweb_flash"
 pluginspage="http://www.macromedia.com/shockwave/download/index.cgi?P1_Prod_Version=3DShockwaveFlash"
 type="application/x-shockwave-flash">
      <!--embed src="images/filmatoflash300x70.swf" quality="high" alt="logo_stralacenoweb_flash"
 pluginspage="http://www.macromedia.com/shockwave/download/index.cgi?P1_Prod_Version=3DShockwaveFlash"
 type="application/x-shockwave-flash"-->
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
<form action="filtro2.php" method="POST">
	Archivio storico personale (tutti i risultati di un corridore): 


<select name="nome">

<?php
$elenco_nomi = array();
$elenco_cognomi = array();
$elenco_i = array();
for ($i = 1; $i < count($archivio); $i++) {
	$prestazione = $archivio[$i];
	$nome = $prestazione[$indice_nome];
	if (!in_array($nome,$elenco_nomi)) {
		
		# estrai il cognome (escludi il nome all'inizio)
		$lista = split(" ",$nome);
		$cognome = "";
		for ($ii = 1; $ii < count($lista); $ii++) {
			$cognome .= " ".$lista[$ii];
			}
			
		array_push($elenco_nomi,$nome);
		array_push($elenco_cognomi,$cognome);
		array_push($elenco_i,$i);
		}
	}

array_multisort($elenco_cognomi,SORT_ASC, SORT_STRING,$elenco_nomi,SORT_ASC, SORT_STRING, $elenco_i,SORT_ASC, SORT_NUMERIC);
	
for ($i = 0; $i < count($elenco_nomi); $i++) {
	echo "<option value=\"".$elenco_i[$i]."\">".$elenco_nomi[$i]."</option>\n";
	}
	
?>	

</select>

<p></p>
<input type="submit" value="Mostra prestazioni personali">

</form>
</li>
<br>

<li>
<a href="filtro6.php" name="Archivio storico per tempi">Archivio storico (tutti i risultati ordinati per tempi)</a>
</li>
<br>

<li>
<a href="filtro5.php" name="Archivio storico sinottico">Archivio storico (quadro sinottico)</a>
</li>
<br>

<li>
<a href="filtro8.php" name="grafico tempi">Grafico andamento tempi negli anni</a>
</li>
<br>

<li>
<a href="filtro0.php" name="Archivio storico per anni">Archivio storico (tutti i risultati ordinati per anni)</a>
</li>
<br>

</ul>

<hr>


</body>
</html>
