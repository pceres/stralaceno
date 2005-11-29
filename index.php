<?php

require_once('libreria.php');

# dichiara variabili
extract(indici());

/*
questa libreria esamina i cookies o i parametri http (eventualmente) inviati, e genera l'array $login con i campi
'username',	: login
'usergroups',	: lista dei gruppi di appartenenza (separati da virgola)
'status',		: stato del login: 'none','ok_form','ok_cookie','error_wrong_username','error_wrong_userpass','error_wrong_challenge','error_wrong_IP'
*/
require_once('login.php');

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 TRANSITIONAL//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
  <title><?php echo $web_title ?></title>
  <meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1">
  <meta name="generator" content="Quanta Plus">
  <meta name="description" content="<?php echo $web_description ?>">
  <meta name="keywords" content="<?php echo $web_keywords ?>">
  <style type="text/css">@import "<?php echo $filename_css ?>";</style>
  <!--link href="<?php echo $site_abs_path ?>custom/images/logo_small.gif" rel="SHORTCUT ICON"-->
</head>
<body class="homepage" onLoad="azzera_input()">

<script type="text/javascript">
<!-- 

function azzera_input()
{
/*
Questa funzione, da richiamare in seguito all'evento onLoad del tag <body>, azzera tutte le eventuali precedenti
selezioni di qualsiasi campo select all'interno del documento.
*/
	for (i = 0; i < document.forms.length; i++) {
		for (ii = 0; ii < document.forms[i].elements.length; i++) {
		   //alert(document.forms[i].name+' '+document.forms[i].elements[ii].name+' '+document.forms[i].elements[ii].type);
		   if (document.forms[i].elements[ii].type == 'select-one') {
			   document.forms[i].elements[ii].value = 0;
		   }
		}
	}
}
//-->
</script>

<?php

#
# analisi dei parametri passati alla pagina
#

# pagina da visualizzare; per ora puo' valere:
# 	'' 		: pagina di default, con tutti gli articoli in colonna centrale
#	'articolo'	: viene visualizzato un solo articolo, indicato dal suo id attraverso la variabile aggiuntiva 'art_id'
$pagina = $_REQUEST['page']; // contenuto da visualizzare in colonna centrale

# carica i dati relativi a tutte le edizioni, che devono essere disponibili per i moduli nelle colonne sinistra e destra
$archivio = load_data($filename_tempi,$num_colonne_prestazioni);

require_once('layout.php');	// funzioni necessarie a stampare i layout

?>

<table cellpadding="2" cellspacing="2" border="0" style="text-align: left; width: 100%;">
  <tbody>
    <tr>
      <td style="vertical-align: top; text-align: center;">
	  
<?php
# includi l'intestazione
include("custom/templates/header.php");
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
include("index_left.php");
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
