<?php

# dichiara variabili
extract(indici());



function indici() {

# formato file di archivio 'tempi_laceno.csv'
$indice_id    = 0;
$indice_nome  = 1;
$indice_posiz = 2;
$indice_tempo = 3;
$indice_anno  = 4;
$indice_nota  = 5;

$num_colonne_prestazioni = 6;

$indice_info = 'info'; #$num_colonne_prestazioni; # viene aggiunta una colonna di informazioni relative all'atleta

# formato file di archivio 'atleti_laceno.csv'
$indice2_id 			= 0;
$indice2_nome 			= 1;
$indice2_sesso	 		= 2;
$indice2_titolo 		= 3;
$indice2_data_nascita 	= 4;
$indice2_peso 			= 5;
$indice2_link			= 6;

$num_colonne_atleti = 7;

# formato file di archivio 'organizzatori_laceno.csv'
$indice3_id    		= 0;
$indice3_nome  		= 1;
$indice3_sesso 		= 2;
$indice3_incarico	= 3;
$indice3_anno  		= 4;
$indice3_link  		= 5;
$indice3_nota  		= 6;

$num_colonne_organizzatori = 7;


#variabili di formattazione
#$style_titoli_tabella = "style=\"background-color: rgb(255, 255, 200); border: double rgb(170,170,170)\"";
$style_titoli_tabella = 'style="border-style: solid; border-color: rgb(170, 170, 170); border-width: thin 1px; padding: 2px 4px; background-color: rgb(255, 255, 200);"';
$style_sfondo_maschi = "rgb(249, 255, 255)";
$style_sfondo_femmine = "rgb(255, 234, 234)";

#nomi di file
$filename_tempi 		= "dati/tempi_laceno.csv";
$filename_atleti 		= "dati/atleti_laceno.csv";
$filename_organizzatori = "dati/organizzatori_laceno.csv";
$filename_counter 		= "dati/counter.txt";

#varie
$email_info		= "stralaceno@freepass.it";
#$symbol_1_partecipazione= '<img src="images/0x2606(star).bmp" width="17">';
$symbol_1_partecipazione= '<img src="/work/stralaceno2/images/0x2606(star).bmp" width="17">';
#$symbol_record  		= '<img src="images/0x263A(smiling_face).bmp" width="17">';
$symbol_record  		= '<img src="/work/stralaceno2/images/0x263A(smiling_face).bmp" width="17">';

$indici = array('indice_id' => $indice_id,'indice_nome' => $indice_nome,'indice_posiz' => $indice_posiz,'indice_tempo' => $indice_tempo,'indice_anno' => $indice_anno,'indice_nota' => $indice_nota,'num_colonne_prestazioni' => $num_colonne_prestazioni,'indice_info' => $indice_info);
$indici2 = array('indice2_id' => $indice2_id,'indice2_nome' => $indice2_nome,'indice2_sesso' => $indice2_sesso,'indice2_titolo' => $indice2_titolo,'indice2_data_nascita' => $indice2_data_nascita,'indice2_peso' => $indice2_peso,'indice2_link' => $indice2_link,'num_colonne_atleti'  => $num_colonne_atleti);
$indici3 = array('indice3_id' => $indice3_id,'indice3_nome' => $indice3_nome,'indice3_sesso' => $indice3_sesso,'indice3_incarico' => $indice3_incarico,'indice3_anno' => $indice3_anno,'indice3_link' => $indice3_link,'indice3_nota' => $indice3_nota,'num_colonne_organizzatori' => $num_colonne_organizzatori);

$formattazione = array('style_sfondo_maschi' => $style_sfondo_maschi,'style_sfondo_femmine' => $style_sfondo_femmine,'style_titoli_tabella' => $style_titoli_tabella);
$filenames = array('filename_tempi' => $filename_tempi,'filename_atleti' => $filename_atleti,'filename_organizzatori' => $filename_organizzatori,'filename_counter' => $filename_counter);
$varie = array('email_info' => $email_info,'symbol_1_partecipazione' => $symbol_1_partecipazione,'symbol_record' => $symbol_record);

return array_merge($indici,$indici2,$indici3,$formattazione,$filenames,$varie);
}

function load_data($filename,$num_colonne) {

$result=array();

$file = fopen($filename, "r");
if (!$file) {
    echo "<p>Impossibile aprire il file remoto.\n";
    exit;
}
while (!feof ($file)) {
    $linea = fgets ($file, 1024);
	$lista = explode(";", $linea,$num_colonne);
	
	if (count($lista)>=$num_colonne) {
		array_push($result,$lista);
		}
}
fclose($file);

return $result;
}


function merge_tempi_atleti($archivio,$atleti) {

# dichiara variabili
extract(indici());

for ($i = 0; $i < count($archivio); $i++) {
	$info = $atleti[$archivio[$i][$indice_id]];
	
	
	#  gestisci l'header
	if (count($info)==0) {
		$info = $atleti[0];
		}
	
	#array_push($archivio[$i],$info);
	$archivio[$i][$indice_info] = $info;
	}
	
return $archivio;
}


function show_table($archivio,$mask,$num_colonne = 1,$font_size = -1) {

# dichiara variabili
extract(indici());

if ($font_size != -1) {
	$main_border = 2;
	$border = 0;
	$cell_padding = 0;
	}
	else {
	$main_border = 0;
	$border = 2;
	$cell_padding = 2;
	}

echo "<div align = \"center\" >"; # tieni la tabella al centro

echo "<table border=\"$main_border\">\n";
echo "  <tbody>\n";
echo "  <tr align\"center\" style=\"vertical-align: top;\">\n";
echo "  <td>\n";

$table_setting = " border=\"$border\" cellpadding=\"$cell_padding\" cellspacing=\"1\"";

$head = $archivio[0];
$head_string = " <thead><tr>\n";
for ($temp = 0; $temp < count($mask); $temp++) {
	$head_string .= "<th scope=col $style_titoli_tabella align=\"center\">".$head[$mask[$temp]]."</th>";
	#echo "<th scope=col $style_titoli_tabella align=\"center\">".$head[$mask[$temp]]."</th>";
	}
$head_string .= "  </tr></thead>\n";


echo "<table $table_setting>\n";
echo $head_string;
echo "  <tbody>\n";

$note = array();
for ($i = 1; $i < count($archivio); $i++) {
	$prestazione = $archivio[$i];

	# stile riga:
	$style_row = "style=\"";
	if ($font_size != -1) {
		$style_row .= "font-size: $font_size;";
		}
	
	# primo arrivato in grassetto
	if ($prestazione[$indice_posiz] == 1) {
		$style_row .= "font-weight: bold;";
		}

	# atleti donna con sfondo rosa
	if ($prestazione[$indice_info][$indice2_sesso] == "F") {
		$style_row .= "background-color: $style_sfondo_femmine;";
		}

	# atleti maschi con sfondo celeste
	if ($prestazione[$indice_info][$indice2_sesso] == "M") {
		$style_row .= "background-color: $style_sfondo_maschi;";
		}

	$style_row .= "\"";


	echo "<tr ".$style_row.">";

	for ($temp = 0; $temp < count($mask); $temp++) {
		$campo = $prestazione[$mask[$temp]];
		
		$allineamento = "center";
		
		# campo nome
		if (array_key_exists("info",$prestazione) & ($mask[$temp] == $indice_nome) ) {
			$allineamento = "left";
			if (mostro_link($prestazione['info'])) {
				$campo = "<a href=\"info.php?id=".$prestazione['info'][$indice2_id]."\">".$campo."</a>";
				}
			}
			
		# campo tempo
		if (array_key_exists("info",$prestazione) & ($mask[$temp] == $indice_tempo) ) {
			$nota = trim($prestazione[$indice_nota]);
			if (strlen($nota) > 0) {
				$id_nota = count($note)+1;
				$ks = "<a href=\"#nota_".$id_nota."\">&sect;".$id_nota."</a>";
				$campo .= " <small>".$ks."</small>";
				
				array_push($note,$nota);
				}
			}
			
		# campo posizione
		if (array_key_exists("info",$prestazione) & ($mask[$temp] == $indice_posiz) ) {
			if ($campo != '-') {
				$campo = $campo."<sup>o</sup>";
				}
			}
		echo "<td nowrap><div align=\"$allineamento\">$campo</div></td>";
		}

	echo "</tr>\n";
	
	if (fmod($i,ceil((count($archivio)-1)/$num_colonne))==0) {
	        echo "</tbody></table></td>";
			if ($i < (count($archivio)-1)) {
				echo "<td><table $table_setting>$head_string<tbody>";
				}
		}
	}

echo "  </tbody>\n";
echo "</table>\n";

echo "  </td>\n";
echo "  </tr>\n";
echo "  </tbody>\n";
echo "</table>\n";


echo "</div>";

# mostra note
if (count($note) > 0) {
	echo "<br>\n";
	echo "<small>\n";
	echo "Note:<br>\n";
	for ($i = 0; $i < count($note); $i++) {
		echo "<a name=\"nota_".($i+1)."\"><span style=\"color: rgb(255, 0, 0);\">&sect;".($i+1)."</span></a>: ".$note[$i]."<br>\n";
		}
	echo "</small>\n";
	}



}



function filtra_archivio($archivio,$lista_campi,$lista_valori) {

$archivio_filtrato = array($archivio[0]); # aggiungi l'header
for ($i = 1; $i < count($archivio); $i++) {
	$prestazione = $archivio[$i];
	
	$ok = TRUE;
	
	for ($ii = 0; $ii < count($lista_campi); $ii++) {
		if (trim($prestazione[$lista_campi[$ii]]) != trim($lista_valori[$ii])) {
			$ok = FALSE;
			}
		}
	
	if ($ok) {
		array_push($archivio_filtrato,$prestazione);
		echo "<p>".$prestazione[$indice_nome]."</p>";
		}
	
	}

return $archivio_filtrato;
}



function ordina_archivio($archivio,$indice1,$indice2) {

# dichiara variabili
extract(indici());

$lista1 = array();
$lista2 = array();
for ($i = 1; $i < count($archivio); $i++) {
	$record = $archivio[$i];
	$item1 = $record[$indice1];
	$item2 = $record[$indice2];
	
	if ($item1 == "-") $item1 = "999";
	if ($item2 == "-") $item2 = "999";
	
	if (array_key_exists('info',$record)) {
		if ($indice1 == $indice_nome) {
			$temp_list = explode(' ',$item1);
			$item1 = implode(' ',array_slice($temp_list,1));
			}
		if ($indice2 == $indice_nome) {
			$temp_list = explode(' ',$item2);
			$item2 = implode(' ',array_slice($temp_list,1));
			}
		}

	array_push($lista1,$item1);
	array_push($lista2,$item2);
	}

$subarchivio = array_slice($archivio,1);
array_multisort($lista1,$lista2,$subarchivio);
$archivio_ordinato = array_merge(array($archivio[0]),$subarchivio); # aggiungi l'header

return $archivio_ordinato;
}



function fondi_nome_id($archivio,$indice_nome,$indice_id) {

# dichiara variabili
extract(indici());

$titolo=$archivio[0];
$titolo[$indice_nome]="$titolo[$indice_nome] ($titolo[$indice_id])";
$archivio_out = array($titolo);

for ($i = 1; $i < count($archivio); $i++) {
	$record = $archivio[$i];
	
	$nome = $record[$indice_nome];
	$id = $record[$indice_id];
	$record[$indice_nome] = "$nome ($id)";

	array_push($archivio_out,$record);
	}

return $archivio_out;
}



function mostro_link($atleta) {

# dichiara variabili
extract(indici());

$mostra = FALSE;

if (trim($atleta[$indice2_titolo]) != '-') {
	$mostra = TRUE;
	}

if (trim($atleta[$indice2_link]) != '-') {
	$mostra = TRUE;
	}

if (trim($atleta[$indice2_data_nascita]) != '?') {
	$mostra = TRUE;
	}

if (trim($atleta[$indice2_peso]) != '?') {	
	$mostra = TRUE;
	}

return $mostra;
}


function action_counter($filename) {

if (!file_exists($filename)) {
	$counter = 0;
	}
else {
	$file = fopen($filename, "r");
	$linea = fgets ($file, 1024);
	$counter = (int)$linea;
	fclose($file);
	}

$counter++;

$file = fopen($filename, "w");
fputs($file, $counter);
fclose($file);

return $counter;
}


function tempo_numerico($tempo) {

if ($tempo[2] == "'") {
	$tempo_numerico = $tempo[0]*10+$tempo[1]+($tempo[3]*10+$tempo[4])/60;
	}
elseif ($tempo == '-') {
	$tempo_numerico = 999;
	}
elseif ($tempo == 'Rit.') {
	$tempo_numerico = 1000;
	}
elseif ($tempo == 'Squ.') {
	$tempo_numerico = 1001;
	}
else {
	$tempo_numerico = 10000;
	}

return $tempo_numerico;
}


function aggiungi_simboli($archivio) {

# dichiara variabili
extract(indici());

$archivio2 = array($archivio[0]);
$archivio2[0]['simb'] = "<br>";#"Simboli";

$lista_record = array();
for ($i = 1; $i < count($archivio); $i++) {
	$prestazione = $archivio[$i];
	
	$id = $prestazione[$indice_id];
	$tempo = tempo_numerico($prestazione[$indice_tempo]);
	
	if (!array_key_exists($id,$lista_record)) {
		$simb = $symbol_1_partecipazione; #'<img src="/work/stralaceno/images/0x2606(star).bmp">';
		$lista_record[$id] = $tempo;
		}
	elseif ($lista_record[$id] > $tempo) {
		$simb = $symbol_record; #'<img src="/work/stralaceno/images/0x263A(smiling_face).bmp">';
		$lista_record[$id] = $tempo;
		}
	else {
		$simb = '<br>';
		}
		
	$prestazione['simb'] = $simb;
	
	array_push($archivio2,$prestazione);
	}

return $archivio2;
}

?>
