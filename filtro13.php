<?php
/*

Maria Santissima della Sanità: domenica dopo il 16 Agosto
Stralaceno: venerdì successivo alla festa della Sanità

16 ago 2021 (Lun) --> Sanità 22 agosto 2021 (Dom) --> Stralaceno 27 agosto 2021 (Ven)
16 ago 2022 (Mar) --> Sanità 21 agosto 2022 (Dom) --> Stralaceno 26 agosto 2022 (Ven)
16 ago 2023 (Mer) --> Sanità 20 agosto 2023 (Dom) --> Stralaceno 25 agosto 2023 (Ven)
16 ago 2024 (Ven) --> Sanità 18 Agosto 2024 (Dom) --> Stralaceno 23 agosto 2024 (Ven)
16 ago 2025 (Sab) --> Sanità 17 Agosto 2024 (Dom) --> Stralaceno 22 agosto 2024 (Ven)

*/

// Define year and day abbreviations (MATLAB: y='2025', list_day={'Sun','Mon',...})
$y = '2025';
$list_day = ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'];


$anno = $_REQUEST['anno']; 				# anno richiesto da form
if (isset($anno)) {
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
