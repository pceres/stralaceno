<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 TRANSITIONAL//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<?php

require_once('../../../libreria.php');

# dichiara variabili
extract(indici());
?>
<head>
  <title><?php echo $web_title ?> - Lettere a <?php echo $web_title ?></title>
  <meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1">
  <meta name="GENERATOR" content="Quanta Plus">
  <meta name="description" content="Le vostre lettere a <?php echo $web_title ?>">
  <meta name="keywords" content="<?php echo $web_keywords ?>">
  <style type="text/css">@import "<?php echo $filename_css ?>";</style>
</head>
<body>

<?php
show_template("lettere_sito.tpl")
?>


<?php echo $homepage_link ?>

<?php
# logga il contatto
$counter = count_page("modulo_lettere_sito",array("COUNT"=>1,"LOG"=>1),$filedir_counter); # abilita il contatore, senza visualizzare le cifre, e fai il log
?>

</body>
</html>
