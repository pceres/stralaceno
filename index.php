#!/usr/local/bin/php
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 TRANSITIONAL//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<?php

require_once('libreria.php');

# dichiara variabili
extract(indici());
?>
<head>
  <title>Stralaceno Web</title>
  <meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1">
  <meta name="generator" content="Quanta Plus">
  <meta name="description" content="Sito ufficiale della Stralaceno">
  <meta name="keywords" content="Stralaceno, Caposele, Caposelesi, Corsa podistica, Atletica, Lago Laceno, Laceno">
  <style type="text/css">@import "<?php echo $css_site_path ?>/stralaceno.css";</style>
  <!--link href="<?php echo $site_abs_path ?>custom/images/logo_small.gif" rel="SHORTCUT ICON"-->
</head>
<body class="homepage" onLoad="azzera_input()">
<?php

# carica i dati relativi a tutti gli anni, che devono essere disponibili per i moduli nelle colonne sinistra e destra
$archivio = load_data($filename_tempi,$num_colonne_prestazioni);

?>

<table cellpadding="2" cellspacing="2" border="0" style="text-align: left; width: 100%;">
  <tbody>
    <tr>
      <td style="vertical-align: top; text-align: center;">
	  
<?php
# includi l'intestazione
include("custom/templates/header.php")	  
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
include("custom/templates/footer.php")	  
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
