<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 TRANSITIONAL//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<?php

require_once('../../../libreria.php');

# dichiara variabili
extract(indici());
?>
<head>
  <title>Stralaceno Web - Lettere alla stralaceno</title>
  <meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1">
  <meta name="GENERATOR" content="Quanta Plus">
  <meta name="description" content="Lettere alla Stralaceno">
  <meta name="keywords" content="Lettere, Guestbook, Stralaceno, ARS, Caposele, Atletica">  
  <style type="text/css">@import "<?php echo $filename_css ?>";</style>
  <style>
	p {text-indent: 2em;}
  </style>
</head>
<body class="tabella">

<?php
show_template("lettere_stralaceno.tpl")
?>


<?php echo $homepage_link ?>

<?php
# logga il contatto
$counter = count_page("modulo_lettere_stralaceno",array("COUNT"=>1,"LOG"=>1),$filedir_counter); # abilita il contatore, senza visualizzare le cifre, e fai il log
?>

</body>
</html>
