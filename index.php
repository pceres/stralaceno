#!/usr/local/bin/php
<!DOCTYPE HTML PUBLIC "-//w3c//dtd html 4.01 transitional//en" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
  <title>Stralaceno Web</title>
  <meta http-equiv="Content-Type"
 content="text/html; charset=ISO-8859-1">
  <meta name="GENERATOR" content="Quanta Plus">
</head>
<body>

<?php
include 'libreria.php';
$archivio = load_data($filename_tempi,$num_colonne_prestazioni); # carica i dati relativi a tutti gli anni

$colore_bordo = '#336699'; # colore blu ufficiale del sito
$colore_sfondo = '#FFEFE5'; #'#FFDBC4';  #   l'arancione ufficiale e' #FF6803, qui' e' eschiarito

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
            <td width = "27%" style="vertical-align: top;">
			
<?php
# includi la barra a sinistra
include("index_left.php")	  
?>

            </td>
            <td width = "53%" style="vertical-align: top;">
			
<?php
# includi il corpo centrale
include("index_middle.php")	  
?>

            </td>
            <td width="20%" style="vertical-align: top;">
			
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


</body>
</html>
