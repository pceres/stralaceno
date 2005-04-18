<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 TRANSITIONAL//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
  <title>Stralaceno Web</title>
  <meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1">
  <meta name="generator" content="Quanta Plus">
  <meta name="description" content="Sito ufficiale della Stralaceno">
  <meta name="keywords" content="Stralaceno, Caposele, Caposelesi, Corsa podistica, Atletica, Lago Laceno, Laceno">
  <style type="text/css">@import "css/stralaceno.css";</style>
</head>
<body onLoad="azzera_input()">

<?php

include 'libreria.php';

$archivio = load_data($filename_tempi,$num_colonne_prestazioni); # carica i dati relativi a tutti gli anni

?>

<table cellpadding="2" cellspacing="2" border="0" style="text-align: left; width: 100%;">
  <tbody>
    <tr>
      <td style="vertical-align: top; text-align: center;">
	  
<?php
# includi l'intestazione
include("header.php")	  
?>

      </td>
    </tr>
    <tr>
      <td style="vertical-align: top;">
      <table cellpadding="2" cellspacing="2" border="0" style="text-align: left; width: 100%;">
        <tbody>
          <tr>
            <td style="vertical-align: top;">
			
<?php
# includi la barra a sinistra
include("index_left.php")	  
?>

            </td>
            <td style="vertical-align: top;">
			
<?php
# includi il corpo centrale
include("index_middle.php")	  
?>

            </td>
            <td style="vertical-align: top;">
			
<?php
# includi la barra a destra
include("index_right.php")	  
?>

            </td>
          </tr>
        </tbody>
      </table>
      </td>
    </tr>
    <tr>
      <td style="vertical-align: top; text-align: center;">
	  
	  
<?php
# includi il footer
include("footer.php")	  
?>
	  
	  <br>
      </td>
    </tr>
  </tbody>
</table>

<!-- la riga che segue e' il logo del validatore HTML W3C -->
<!--p><a href="http://validator.w3.org/check?uri=referer"><img border="0" src="http://www.w3.org/Icons/valid-html401"
	alt="Valid HTML 4.01!" height="31" width="88"></a-->
	
</body>
</html>
