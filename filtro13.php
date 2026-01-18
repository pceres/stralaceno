<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 TRANSITIONAL//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<?php

require_once('libreria.php');

# dichiara variabili
extract(indici());
?>
<head>
  <title><?php echo $web_title ?> - Classifica partecipazioni</title>
  <meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1">
  <meta name="GENERATOR" content="Quanta Plus">
  <meta name="description" content="Classifica partecipazioni">
  <meta name="keywords" content="classifica, numero di partecipazioni">
  <style type="text/css">@import "<?php echo $filename_css ?>";</style>
</head>
<body class="tabella">

<?php
/*

Maria Santissima della Sanità: domenica dopo il 16 Agosto
Stralaceno: venerdì successivo alla festa della Sanità

16 ago 2021 (Lun) --> Sanità 22 agosto 2021 (Dom) --> Stralaceno 27 agosto 2021 (Ven)
16 ago 2022 (Mar) --> Sanità 21 agosto 2022 (Dom) --> Stralaceno 26 agosto 2022 (Ven)
16 ago 2023 (Mer) --> Sanità 20 agosto 2023 (Dom) --> Stralaceno 25 agosto 2023 (Ven)
16 ago 2024 (Ven) --> Sanità 18 Agosto 2024 (Dom) --> Stralaceno 23 agosto 2024 (Ven)
16 ago 2025 (Sab) --> Sanità 17 Agosto 2024 (Dom) --> Stralaceno 22 agosto 2024 (Ven)
16 ago 2025 (Dom) --> Sanità 23 Agosto 2024 (Dom) --> Stralaceno 28 agosto 2024 (Ven)

*/


// Define year and day abbreviations (MATLAB: y='2025', list_day={'Sun','Mon',...})
$y = date("Y"); # anno corrente
$list_day = ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'];

$anno = sanitize_user_input($_REQUEST['anno'], 'number', ['number_type'=>'int']); 				# anno richiesto da form
if (isset($anno) & !empty($anno)) {
    $y = $anno;
}

// Header
echo "<b>Giorno della Stralaceno nell'anno $y</b><br>\n";

// Initial date setup (MATLAB: day=16)
$day = 16;
$ks = "$day/8/$y";
$date = DateTime::createFromFormat('d/m/Y', $ks);
$i = (int)$date->format('w'); // 0 (Sun) to 6 (Sat)
echo "$ks ({$list_day[$i]})<br>\n";

// Calculate first adjustment (MATLAB: d_Sanita = 8 - i)
$d_Sanita = 7 - $i; // Adjusted for PHP's 0-based index
$day += $d_Sanita;
$ks = "$day/8/$y";
$date = DateTime::createFromFormat('d/m/Y', $ks);
$i = (int)$date->format('w');
echo "$ks ({$list_day[$i]}) --> festa della Sanità<br>\n";

// Calculate second adjustment (MATLAB: d_Stralaceno = 5)
$d_Stralaceno = 5;
$day += $d_Stralaceno;
$ks = "$day/8/$y";
$date = DateTime::createFromFormat('d/m/Y', $ks);
$i = (int)$date->format('w');
echo "$ks ({$list_day[$i]}) --> Stralaceno<br>\n";
?>


</body>
</html>

