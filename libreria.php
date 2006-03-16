<?php

# dichiara variabili
extract(indici());

//print_r($_COOKIE); //

function indici() {

# carica le variabili custom (quelle che sono specifiche di ogni sito web, es. titolo, e-mail, ecc)
require("custom/config/custom.php");

# formato file di archivio 'tempi_laceno.csv'
$indice_id    = 0;
$indice_nome  = 1;
$indice_posiz = 2;
$indice_tempo = 3;
$indice_anno  = 4;
$indice_nota  = 5;

$num_colonne_prestazioni = 6;

$indice_info = 'info'; # viene aggiunta una colonna di informazioni relative all'atleta

$indici = array('indice_id' => $indice_id,'indice_nome' => $indice_nome,'indice_posiz' => $indice_posiz,'indice_tempo' => $indice_tempo,'indice_anno' => $indice_anno,'indice_nota' => $indice_nota,'num_colonne_prestazioni' => $num_colonne_prestazioni,'indice_info' => $indice_info);


# formato file di archivio 'atleti_laceno.csv'
$indice2_id 		= 0;
$indice2_nome 		= 1;
$indice2_sesso	 	= 2;
$indice2_titolo 	= 3;
$indice2_data_nascita 	= 4;
$indice2_peso 		= 5;
$indice2_link		= 6;
$indice2_foto		= 7;

$num_colonne_atleti = 8;

$indici2 = array('indice2_id' => $indice2_id,'indice2_nome' => $indice2_nome,'indice2_sesso' => $indice2_sesso,'indice2_titolo' => $indice2_titolo,'indice2_data_nascita' => $indice2_data_nascita,'indice2_peso' => $indice2_peso,'indice2_link' => $indice2_link,'indice2_foto' => $indice2_foto,'num_colonne_atleti'  => $num_colonne_atleti);


# formato file di archivio 'organizzatori_laceno.csv'
$indice3_id    		= 0;
$indice3_nome  		= 1;
$indice3_sesso 		= 2;
$indice3_incarico	= 3;
$indice3_anno  		= 4;
$indice3_link  		= 5;
$indice3_nota  		= 6;

$num_colonne_organizzatori = 7;

$indici3 = array('indice3_id' => $indice3_id,'indice3_nome' => $indice3_nome,'indice3_sesso' => $indice3_sesso,'indice3_incarico' => $indice3_incarico,'indice3_anno' => $indice3_anno,'indice3_link' => $indice3_link,'indice3_nota' => $indice3_nota,'num_colonne_organizzatori' => $num_colonne_organizzatori);


# formato file di configurazione 'layout_left.txt' e 'layout_right.txt'
$indice_layout_name = 0;
$indice_layout_caption = 1;
$indice_layout_type = 2;
$indice_layout_data = 3;
$indice_layout_msg_disabled = 4;
$indice_layout_enabled_groups = 5;

$indici_layout = array('indice_layout_name' => $indice_layout_name, 'indice_layout_caption' => $indice_layout_caption,'indice_layout_type' => $indice_layout_type,'indice_layout_data' => $indice_layout_data,'indice_layout_msg_disabled'=>$indice_layout_msg_disabled,'indice_layout_enabled_groups'=>$indice_layout_enabled_groups);


# formato file di configurazione $filename_users
$indice_user_name = 0;
$indice_user_passwd = 1;
$indice_user_groups = 2;

$indici_user = array('indice_user_name'=>$indice_user_name,'indice_user_passwd'=>$indice_user_passwd,'indice_user_groups'=>$indice_user_groups);


#variabili di formattazione
$style_sfondo_maschi = "rgb(249, 255, 255)";
$style_sfondo_femmine = "rgb(255, 234, 234)";


// determina la directory (l'ultimo livello) contenente il sito (deve iniziare con $root_prefix, ad es., se $root_prefix=="sito", "sito2" e' ok)
$path = $_SERVER['SCRIPT_FILENAME'];
$root_prefix_work = "/".$root_prefix;

// determina l'ultima occorrenza di root_prefix
$temp=strpos($path,$root_prefix_work);
if (strlen($temp."a")==1) // verifica che root_prefix sia presente nel path
{
	die("Errore: il path della radice del sito ($path) sul server non contiene '$root_prefix' ");
}
do {
	$path = substr($path,$temp);
	$temp=strpos($path,$root_prefix_work,1)."a";
} while (strlen($temp)>1);
// taglia la parte restante del path fino al simbolo '/'
$path = substr($path,strpos($path,$root_prefix_work));
$root_prefix_work = substr($path,0,strpos($path,"/",1));

// determina il path assoluto nel filesystem del server (serve quando si accede direttamente ai file per leggere o scrivere)
$path = $_SERVER['SCRIPT_FILENAME'];
$end = 0;
do {
	$test = strpos($path,$root_prefix_work,$end);
	if ($test)
	{
		$end = $test+strlen($root_prefix_work)+1;
	}
} while ($test);
$root_path = substr($path,0,$end);

// path assoluto da usare per gli script php
$start = strpos($_SERVER['SCRIPT_NAME'],$root_prefix_work);
if (strlen($start)>0)
{
	$path = $_SERVER['SCRIPT_NAME'];
}
else
{
	$path = $_SERVER['SCRIPT_URL'];
}
$end = 0;
do {
	$test = strpos($path,$root_prefix_work,$end);
	if (strlen($test."a")>1)
	{
		$end = $test+strlen($root_prefix_work)+1;
	}
} while (strlen($test."a")>1);
$script_abs_path = substr($path,0,$end);

// path assoluto da usare per l'html e le immagini
if (array_key_exists('HTTP_HOST',$_SERVER) and array_key_exists('SCRIPT_URI',$_SERVER))
{
	$path = substr($_SERVER['SCRIPT_URI'],strpos($_SERVER['SCRIPT_URI'],$_SERVER['HTTP_HOST'])+strlen($_SERVER['HTTP_HOST']));
}
else
{
	if (substr($_SERVER['DOCUMENT_ROOT'],-1) == '/') // document_root non deve finire per '/'
	{
		$_SERVER['DOCUMENT_ROOT'] = substr($_SERVER['DOCUMENT_ROOT'],0,-1);
	}
	$start = strpos($_SERVER['SCRIPT_FILENAME'],$_SERVER['DOCUMENT_ROOT']);
	if (strlen($start)>0)
	{
		$path = $_SERVER['SCRIPT_FILENAME'];
		$start = strlen($_SERVER['DOCUMENT_ROOT']);
		$path = substr($path,$start);
	}
	else
	{
		die("Errore: non riesco ad individuare il site_abs_path!");
	}
}	
$end = 0;
do {
	$test = strpos($path,$root_prefix_work,$end);
	if (strlen($test."a")>1)
	{
		$end = $test+strlen($root_prefix_work)+1;
	}
} while (strlen($test."a")>1);
$site_abs_path = substr($path,0,$end);


#path assoluti
$modules_site_path		= $script_abs_path."custom/moduli/";
$filedir_counter 		= $root_path."custom/contatori/";
$articles_dir 			= $root_path."custom/articoli/";
$config_dir 			= $root_path."custom/config/";
$album_dir			= $root_path."custom/album/";
$questions_dir			= $root_path."custom/lotterie/";

#nomi di file
$filename_css			= $site_abs_path."custom/config/style.css";
$filename_tempi			= $root_path."custom/dati/".$nome_file_tempi;
$filename_atleti		= $root_path."custom/dati/".$nome_file_atleti;
$filename_organizzatori	= $root_path."custom/dati/".$nome_file_organizzatori;
$article_online_file	= $articles_dir."online.txt";
$filename_links			= $config_dir."links.txt";
$filename_albums		= $config_dir."albums.txt";
$filename_users			= $config_dir."users.php";	// l'estensione e' php in modo che la richiesta della pagina non permetta comunque di visualizzare i dati
$filename_challenge		= $config_dir."challenge.php";	// l'estensione e' php in modo che la richiesta della pagina non permetta comunque di visualizzare i dati

#varie
$tempo_max_grafico = max(array($tempo_max_F,$tempo_max_M));
$symbol_empty= '<img style="display:inline;" align="middle" height="13" width="13" alt="empty" border="0">';
$symbol_1_partecipazione= '<img src="'.$site_abs_path.'images/0x2606(star).bmp" style="display:inline;" align="middle" height="13" alt="1a partecipazione" border="0">';
$symbol_1_partecipazione_best	= '<img src="'.$site_abs_path.'images/0x2606(star_best).bmp" style="display:inline;" align="middle" height="13" alt="1a partecipazione" border="0">';
$symbol_record  		= '<img src="'.$site_abs_path.'images/0x263A(smiling_face).bmp" style="display:inline;" align="middle" height="13" alt="record personale" border="0">';
$symbol_record_best		= '<img src="'.$site_abs_path.'images/0x263A(smiling_face_best).bmp" style="display:inline;" align="middle" height="13" alt="record personale assoluto" border="0">';
$symbol_info			= '<img src="'.$site_abs_path.'images/info.jpg" border="0" width="12">';
$homepage_link 			= '<hr><div align="right"><a class="txt_link" href="'.$script_abs_path.'index.php">Torna alla homepage</a></div>';

#admin
$max_last_editions	= 3;	// numero di ultime edizioni in colonna laterale
$max_online_articles	= 10;	// numero di articoli pubblicati online


$formattazione = array('style_sfondo_maschi' => $style_sfondo_maschi,'style_sfondo_femmine' => $style_sfondo_femmine);
$filenames = array('filename_css' => $filename_css,'filename_tempi' => $filename_tempi,'filename_atleti' => $filename_atleti,
	'filename_organizzatori' => $filename_organizzatori,'filedir_counter' => $filedir_counter,
	'articles_dir' => $articles_dir,'article_online_file' => $article_online_file,'filename_links' => $filename_links,
	'filename_albums' => $filename_albums,'filename_users'=>$filename_users,'filename_challenge'=>$filename_challenge);
$pathnames = array('root_prefix' => $root_prefix,'root_path' => $root_path,'site_abs_path' => $site_abs_path,'script_abs_path' => $script_abs_path,'modules_site_path' => $modules_site_path,'config_dir' => $config_dir,'album_dir' => $album_dir,'questions_dir' => $questions_dir);
$varie = array('symbol_1_partecipazione' => $symbol_1_partecipazione,'symbol_1_partecipazione_best' => $symbol_1_partecipazione_best,'symbol_record' => $symbol_record,'symbol_record_best' => $symbol_record_best,'symbol_info'=>$symbol_info);
$custom = array('homepage_link' => $homepage_link,'tempo_max_M' => $tempo_max_M,'tempo_max_F' => $tempo_max_F,'tempo_max_grafico' => $tempo_max_grafico,'race_name' => $race_name,'web_title' => $web_title,'web_description' => $web_description,'web_keywords' => $web_keywords,'email_info' => $email_info);
$admin = array('password_album' => $password_album,'password_config' => $password_config,'password_articoli' => $password_articoli,'password_upload_file' => $password_upload_file,'max_last_editions' => $max_last_editions,'max_online_articles' => $max_online_articles);

return array_merge($indici,$indici2,$indici3,$indici_layout,$indici_user,$formattazione,$filenames,$pathnames,$varie,$admin,$custom);
}

function load_data($filename,$num_colonne) {

$result=array();

$file = fopen($filename, "r");
if (!$file) {
    echo "<p>Impossibile aprire il file remoto $filename.\n";
    exit;
}
while (!feof ($file)) {
    $linea = rtrim(fgets ($file, 1024));
	$lista = explode(";", $linea,$num_colonne);
	
	if (count($lista)>=$num_colonne) {
		array_push($result,$lista);
		}
}
fclose($file);

return $result;
}


function merge_tempi_atleti($archivio,$atleti,&$edizioni) {

# dichiara variabili
extract(indici());

for ($i = 0; $i < count($archivio); $i++) {
	$info = $atleti[$archivio[$i][$indice_id]];
	
	
	#  gestisci l'header
	if (count($info)==0) 
	{
		$info = $atleti[0];
	}
	else
	{
		$edizione = $archivio[$i][$indice_anno];
		if (!in_array($edizione,$edizioni))
		{
			array_push($edizioni,$edizione);
		}
	}
	
	$archivio[$i][$indice_info] = $info;
	
}

return $archivio;
}


function show_legenda($legenda) {
# mostra note

# dichiara variabili
extract(indici());

#elimina i doppioni;
$legenda=array_unique($legenda);

# ordina la legenda, e lascia solo i simboli noti
$simboli_noti = array('F.T.M.','Rit.','Squ.',$symbol_1_partecipazione,$symbol_1_partecipazione_best,
					$symbol_record,$symbol_record_best,'info');
$legenda_ordinata=array();
foreach ($simboli_noti as $simbolo)
{
	if (in_array($simbolo,$legenda))
	{
		array_push($legenda_ordinata,$simbolo);
	}
}

if (count($legenda_ordinata) > 0)
{
	echo '<br>';
	echo '<table class="tabella_legenda">';
	
	echo "<tr><td colspan='2'>Legenda:</td></tr>\n";
	foreach ($legenda_ordinata as $voce)
	{
		switch ($voce) 
		{
		case 'F.T.M.':
			$info = "fuori tempo massimo ($tempo_max_M minuti uomini, $tempo_max_F  minuti donne)";
			break;
		case 'Rit.':
			$info = "ritirato";
			break;
		case 'Squ.':
			$info = "squalificato";
			break;
		case $symbol_1_partecipazione:
			$info = "1<sup>a</sup> partecipazione";
			break;
		case $symbol_1_partecipazione_best:
			$info = "1<sup>a</sup> partecipazione e record personale assoluto";
			break;
		case $symbol_record:
			$info = "record personale";
			break;
		case $symbol_record_best:
			$info = "record personale assoluto";
			break;
		case 'info':
			$voce = $symbol_info;
			$info = "Ulteriori informazioni disponibili";
			break;
		default:
			$info = "";
			//echo "Voce di legenda sconosciuta!";
		break;
		}
		
		if (strlen($info) > 0)
		{
			echo '<tr><td>';
			echo $voce;
			echo '</td><td>:</td><td class="descrizione">';
			echo $info;
			echo '</td></tr>';
		}
	}
	echo '</table>';
}

}


function show_table($archivio,$mask,$class,$num_colonne = 1,$font_size = -1,$show_note = 1,$tooltip_data) {

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

// verifica il numero delle colonne
$minimo_righe = 10;
if (count($archivio)/$num_colonne < $minimo_righe) // almeno dieci righe per colonne!
{
	//Troppe colonne per i dati da visualizzare, riducili
	$num_colonne=ceil(count($archivio)/$minimo_righe);
}


echo '<div align="center">'; # tieni la tabella al centro

echo "<table class=\"$class\">\n";
echo "  <tbody>\n";
echo "  <tr>\n";
echo "  <td>\n";

$head = $archivio[0];
$head_string = " <thead><tr>\n";
for ($temp = 0; $temp < count($mask); $temp++) {
	$head_string .= "<th valign=\"middle\">".$head[$mask[$temp]]."</th>\n";
	}
$head_string .= "  </tr></thead>\n";




echo "<table>\n";
echo $head_string;
echo "  <tbody>\n";

$note = array(); # insieme delle note visualizzate
$legenda = array(); # insieme delle voci in legenda
$flag_has_info = 0; # vale 1 se viene visualizzata almeno una informazione, e quindi il simbolo va spiegato in legenda
for ($i = 1; $i < count($archivio); $i++) {
	$prestazione = $archivio[$i];
	
	# stile riga:
	$style_row = " ";

	$classe = "";
	# primo arrivato
	if ($prestazione[$indice_posiz] == 1) {
		$classe = "primo ";
		}

	if ($prestazione[$indice_info][$indice2_sesso] == "F") {
		# atleti donna
		$classe .= "atleta_femmina";
	}
	elseif ($prestazione[$indice_info][$indice2_sesso] == "M") {
		# atleti maschi
		$classe .= "atleta_maschio";
	}
	
	$style_row = "class=\"$classe\"";

	echo "<tr ".$style_row.">";

	for ($temp = 0; $temp < count($mask); $temp++) {
		$campo = $prestazione[$mask[$temp]];
		
		$allineamento = "center";
		
		# gestisci le note (se viene visualizzato il tempo)
		if (array_key_exists("info",$prestazione) & ($mask[$temp] == $indice_tempo) ) {
			$nota = trim($prestazione[$indice_nota]);
			if (($show_note) & (strlen($nota) > 0)) {
				if (in_array($nota,$note))
				{
					$id_nota = array_search($nota,$note); // la nota e' ripetuta, recuperane l'indice
				}
				else
				{
					$id_nota = count($note)+1;
				}
				$ks = "<a href=\"#nota_".$id_nota."\">&sect;".$id_nota."</a>";
				$campo .= " <small>".$ks."</small>";
				
				$note[$id_nota] = $nota;
			}
		}
		
		# campo nome (ed eventuale link a pagina info)
		if (array_key_exists("info",$prestazione) & ($mask[$temp] == $indice_nome) ) {
			$allineamento = "left";
			if (mostro_link($prestazione['info']))
			{
				$campo = "<a href=\"info.php?id=".$prestazione['info'][$indice2_id]."\">$campo&nbsp;&nbsp;$symbol_info</a>";
				$flag_has_info = 1;
			}
		}
			
		# campo posizione
		if (array_key_exists("info",$prestazione) & ($mask[$temp] == $indice_posiz) ) {
			if ($campo != '-') {
				$campo = $campo."&deg;";
			}
		}
		
		# gestisci le note (se viene visualizzato il tempo)
		if ($flag_has_info)
		{
			array_push($legenda,'info');
		}
		if ($mask[$temp] == $indice_tempo) // note di gara (F.T.M.,Rit.,Squ.)
		{
			if ((tempo_numerico($campo)) > 500)
			{
				array_push($legenda,$campo);
			}
		}
		if ($mask[$temp] == 'simb') // note con simboli grafici (record personale, prima partecipazione,ecc)
		{
			if (in_array($campo,array($symbol_record_best,$symbol_record,$symbol_1_partecipazione,$symbol_1_partecipazione_best)))
			{
				array_push($legenda,$campo);
			}
		}
		
		// tooltip
		if (array_key_exists($mask[$temp],$tooltip_data)) // se per la cella attuale e' previsto il tooltip...
		{
			$tip = 'title="'.$prestazione[$tooltip_data[$mask[$temp]]].'"';
		}
		else
		{
			$tip = '';
		}
		
		echo "<td nowrap $tip><div align=\"$allineamento\">$campo</div></td>";
	}

	echo "</tr>\n";
	
	# chiudi la colonna ed inizia la successiva
	if ((fmod($i,ceil((count($archivio)-1)/$num_colonne))==0) && ($i < count($archivio)-1)) { 
		echo "</tbody></table></td>";
		if ($i < (count($archivio)-1)) {
			echo "<td><table>$head_string<tbody>";
		}
	}
}

# eventuali righe vuote
$resto = (ceil((count($archivio)-1)/$num_colonne))*$num_colonne-count($archivio)+1;
for ($temp = 0; $temp < $resto; $temp++) {
	echo "<tr style=\"\"><td colspan=".count($mask).">&nbsp;</td></tr>\n";
}

# chiudi l'ultima colonna
echo "  </tbody>\n";
echo "</table>\n";

# chiudi la tabella principale
echo "  </td>\n";
echo "  </tr>\n";
echo "  </tbody>\n";
echo "</table>\n";

echo "</div>";

# mostra note
if (count($note) > 0)
{
	echo "<br>\n";
	echo "<div class=\"nota\">\n";
	echo "Note:<br>\n";
	for ($i = 1; $i <= count($note); $i++)
	{
		echo "<a name=\"nota_".$i."\"><span style=\"color: rgb(255, 0, 0);\">&sect;".$i."</span></a>: ".$note[$i]."<br>\n";
	}
	echo "</div>\n";
}

# mostra legenda
show_legenda($legenda);

}



function filtra_archivio($archivio,$lista_campi,$lista_valori) {
# per filtrare su un sottocampo di $archivio,  basta passare un array, ad esempio per filtrare sulle femmine $lista_campi=array($indice_info,$indice2_sesso);$lista_valori='F'

$archivio_filtrato = array($archivio[0]); # aggiungi l'header
for ($i = 1; $i < count($archivio); $i++) {
	$prestazione = $archivio[$i];
	
	$ok = TRUE;
	
	for ($ii = 0; $ii < count($lista_campi); $ii++) {
		$campo = $lista_campi[$ii];
		if (count($campo) == 1) {
			if (trim($prestazione[$campo]) != trim($lista_valori[$ii])) {
				$ok = FALSE;
				}
			}
		elseif (count($campo) == 2) {
			if (trim($prestazione[$campo[0]][$campo[1]]) != trim($lista_valori[$ii])) {
				$ok = FALSE;
				}
			}
		}
	
	if ($ok) {
		array_push($archivio_filtrato,$prestazione);
//		echo "<p>".$prestazione[$indice_nome]."</p>";
		}
	
	}

return $archivio_filtrato;
}



function ordina_archivio($archivio,$indice1,$indice2,$flag = SORT_ASC) {

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
array_multisort($lista1,$flag,$lista2,$flag,$subarchivio);
$archivio_ordinato = array_merge(array($archivio[0]),$subarchivio); # aggiungi l'header

return $archivio_ordinato;
}



function fondi_nome_id($archivio,$indice_nome,$indice_id) {

# dichiara variabili
extract(indici());

$titolo=$archivio[0];
#$titolo[$indice_nome]="$titolo[$indice_nome] ($titolo[$indice_id])";
$titolo[$indice_nome]="$titolo[$indice_nome] (num.)";
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
// restituisce il tempo in minuti
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
$archivio2[0]['simb'] = "<br>";

$prima_edizione = $archivio[1][$indice_anno];
$lista_record = array();
$lista_prestazioni_best = array();
$lista_prima_prestazione = array();
for ($i = 1; $i < count($archivio); $i++) {
	$prestazione = $archivio[$i];
	
	$id = $prestazione[$indice_id];
	$tempo = tempo_numerico($prestazione[$indice_tempo]);
	$edizione = $prestazione[$indice_anno];
	
	if (!array_key_exists($id,$lista_record))  // se e' la prima volta che compare in archivio
	{
		if ($edizione != $prima_edizione) // usa il simbolo di prima partecipazione...
		{
			$simb = $symbol_1_partecipazione;
		}
		else // ...tranne che per la prima edizione della gara
		{
			$simb = $symbol_empty;
		}
		$lista_record[$id] = $tempo;
		$lista_prima_prestazione[$id] = array($i,$tempo,'unica');
	}
	else
	{
		$lista_prima_prestazione[$id][2]=''; // la persona non ha piu' una unica partecipazione
		if ($lista_record[$id] > $tempo) // se la prestazione in esame presenta un tempo migliore rispetto a quelli precedenti, si tratta di un record personale
		{
			$simb = $symbol_record;
			$lista_record[$id] = $tempo;
			$lista_prestazioni_best[$id] = $i; // id della prestazione con record
		}
		else 
		{
			$simb = $symbol_empty;
		}
	}
	
	$prestazione['simb'] = $simb;
	
	array_push($archivio2,$prestazione);
	}


// aggiungi simbolo migliore prestazione in assoluto
foreach ($lista_prestazioni_best as $id => $id_prestazione) 
{
	if ($archivio2[$id_prestazione]['simb']==$symbol_record)
	{
		$archivio2[$id_prestazione]['simb'] = $symbol_record_best;
	}
}

// aggiungi simbolo prima prestazione e contemporaneamente migliore prestazione in assoluto (ci devono essere state almeno 2 partecipazioni, ed un arrivo regolare))
foreach ($lista_prima_prestazione as $id => $item) 
{
	if (($item[2]!=='unica') & ($item[1]==$lista_record[$id]) & ($item[1] < 500))
	{
		$archivio2[$item[0]]['simb'] = $symbol_1_partecipazione_best;
	}
}

return $archivio2;
}




function get_article_list($articles_dir)
{
	// Leggi l'elenco degli articoli disponibili
	$art_count = 0;
	if (is_dir($articles_dir)) 
	{
		if ($dh = opendir($articles_dir)) 
		{
			while (($file = readdir($dh)) !== false) 
			{
			   if ( (filetype($articles_dir . $file) == "file") and (substr($file,0,4) === "art_") and (substr($file,-1,1)=='t') )
				{
				   	$art_id[++$art_count] = substr($file,4,strlen($file)-8)+0;
				}
			}
			closedir($dh);
		}
	}
	
	if ($art_count > 0)
	{
		sort($art_id);
	}
	else
	{
		$art_id = array();
	}
	
	return $art_id;
}


function get_online_articles($article_online_file)
{
	$bulk = file($article_online_file);
	
	$art_id = array();
	for ($i = 0; $i < count($bulk); $i++)
	{
		$ks = trim($bulk[$i]); // elimina i caratteri di fine linea
		if (!empty($ks))
		{
			array_push($art_id,$ks+0);
		}
	}
	
	return $art_id;
}


function set_online_articles($article_online_file,$article_list)
{
	// scrivi il file degli articoli online
	if ($handle=fopen($article_online_file,'w'))
	{
		for ($i = 0; $i < count($article_list); $i++)
		{
			fwrite($handle, $article_list[$i]);
			fwrite($handle,"\r\n");
		}
		fclose($handle);
	}
	else
	{
		die("$article_online_file e' probabilmente protetto in scrittura! Contattare il webmaster.");
	}
}


function load_article($art_id)
{
	# dichiara variabili
	extract(indici());
	
	$art_file = $articles_dir."art_".($art_id+0).".txt";

	if (file_exists($art_file))
	{
		$bulk = file($art_file);           //read all long entries in a array
		
		$art_data = array();
		
		$art_autore = split("::",$bulk[0]);
		$art_titolo = split("::",$bulk[1]);
		
		$art_data["autore"] = trim($art_autore[1],"\r\n");
		$art_data["titolo"] = trim($art_titolo[1],"\r\n");
		$art_data["testo"] = array_slice($bulk,3,count($bulk)-4);
		$art_data['id'] = $art_id;	
	}
	else
	{
		$art_data = array();
	}
	
	return $art_data;
}


function get_abstract($testo_in,$puntini) 
{
// $puntini e' il link che viene aggiunto in coda, subito prima di chiudere eventuali tag html rimasti aperti

$no_close_tags = array("p","br","?php"); // array di tags che non richiedono il tag di chiusura
	
	$n_max_stop = 2; // numero massimo di righe contenenti un carattere ".","?","!"
		
	$bulk = array();
	$bulk_tag = array();
	$bulk_tag_trim = array();
	$n_stop = 0;
	foreach ($testo_in as $line)
	{
		$vpos = array( strpos($line,"."), strpos($line,"?"), strpos($line,"!") );
		if ( ($vpos[0] || $vpos[1] || $vpos[2]) & (strlen(strpos($line,"\"")."0")==1) ) // non considerare le linee con stringhe delimitate da "
		{
			$pos = max($vpos);
			$n_stop++;			// hai trovato un carattere di punteggiatura, aggiorna il contatore
		}
		
		if ($n_stop >= $n_max_stop)
		{
			$line = substr($line,0,$pos+1); // taglia l'ultima riga fino al segno di punteggiatura
		}
		
		array_push($bulk,$line);
		
		// individua i tag html nella linea aggiunta
		$templine = $bulk[count($bulk)-1];
		$p1 = strpos($templine,"<");
		while (strlen($p1."0")>1)
		{
			$p2 = strpos($templine,">",$p1);
			$tag = substr($templine,$p1+1,$p2-$p1-1); // estrai il tag
			
			$p3 = strpos($tag,' ');
			if ($p3)
			{
				$tag_trim = substr($tag,0,$p3);
			}
			else
			{
				$tag_trim = $tag;
			}
			
			// il tag e' di chiusura?
			if (substr($tag_trim,0,1) === '/') // se si', elimina il tag precedente
			{
				array_pop($bulk_tag_trim);
				array_pop($bulk_tag);
			}
			elseif (!in_array($tag_trim,$no_close_tags)) // altrimenti, se il tag richiede chiusura, mettilo in coda
			{
				array_push($bulk_tag_trim,$tag);
				array_push($bulk_tag,$tag);
			}
			$p1 = strpos($templine,"<",$p2);
		}
		
		if ($n_stop >= $n_max_stop)
		{
			break;
		}
		
	}
	
	// aggiungi il link all'articolo completo
	if (count($puntini) > 0)
	{
		array_push($bulk,$puntini);
	}
	
	// chiudi eventuali tag rimasti aperti
	for ($i = count($bulk_tag)-1; $i>=0; $i--)
	{
		array_push($bulk,"</".$bulk_tag[$i].">");
	}
	
	return $bulk;
}


function template_to_effective($line_in)
{
	# dichiara variabili
	extract(indici());
	
	$template = array("%script_root%","%file_root%");
	$effective = array($script_abs_path,$site_abs_path);
	return str_replace($template, $effective, $line_in);
}
	
	
function show_article($art_data,$mode,$link) 
{
	// $mode=('full','abstract')
	// $link : usato in modo 'abstract', e' il link cui devono puntare i puntini alla fine dell'articolo

	$abstract_lines = 3; // numero di linee da pubblicare nella modalita' abstract

	echo "<tr><td>\n";
	echo "\t<table class=\"article_group\"><tbody><tr><td>\n";

	echo "		<h3>".$art_data["titolo"]."</h3>\n";
	echo "		<div class=\"txt_articolo\">\n";

	if ($mode === 'abstract')
	{
		// abstract dell'articolo
		$puntini = ' <a href="'.$link.'">...</a>';
		$testo_articolo = get_abstract($art_data["testo"],$puntini);
	}
	else
	{
		// testo completo
		$testo_articolo = $art_data["testo"];
	}
	
	foreach ($testo_articolo as $line)
	{
		echo template_to_effective($line);
	}
	
	echo "		<div class=\"txt_firma_articolo\">".$art_data["autore"]."</div>\n";
	echo "		</div>\n";

	echo "\t</td></tr></tbody></table>\n";
	echo "</td></tr>\n\n";	
}


function save_article($id_articolo,$author,$title,$bulk,$uploaddir) {

# nome del file che contiene l'articolo
$art_filename = $uploaddir."art_$id_articolo.txt";

$str_author = "Autore::$author\r\n";
$str_title = "Titolo::$title\r\n";
$str_begin_text = "--- Begin body ---\r\n";
$str_end_text = "--- End body ---\r\n";

$bulk = array_merge($str_author,$str_title,$str_begin_text,$bulk,$str_end_text);

// scrivi il file art_x.txt
if ($handle=fopen($art_filename,'w'))
{
	for ($i=0;$i<count($bulk); $i++)
	{
		$line = $bulk[$i];
		fwrite($handle, rtrim($line,"\r\n"));
		if ( ($i < count($bulk)) & (strlen(rtrim($line,"\r\n")) > 0) )
		{
			fwrite($handle,"\r\n");
		}
	}
	fclose($handle);
}
else
{
	die("Articolo $id_articolo probabilmente protetto in scrittura! Contattare il webmaster.");
}
}


function upload_article($author,$title,$bulk,$uploaddir) {

# dichiara variabili
extract(indici());

# determina l'id del nuovo articolo
$art_id = get_article_list($articles_dir); // carica l'elenco degli articoli disponibili ($articles_dir e' relativo alla radice)

$id_articolo = max($art_id)+1;

echo "il nuovo articolo ha id $id_articolo";

save_article($id_articolo,$author,$title,$bulk,$uploaddir);

# restituisci l'id del nuovo articolo
return $id_articolo;
}


function log_action($workdir,$string)
{
$file = fopen($workdir . 'something_changed.txt', "a");
fputs($file, $string."\r\n");
fclose($file);
}


function publish_online_articles($art_list) {

# dichiara variabili
extract(indici());

// lascia, eventualmente, solo gli ultimi max_online_articles della lista
if (count($art_list) > $max_online_articles)
{
	$art_list = array_slice($art_list,0,$max_online_articles);
}

// salva l'elenco degli articoli online
set_online_articles($article_online_file,$art_list);

return $art_list;
}


function delete_article($art_id)
{

# dichiara variabili
extract(indici());

$art_file = $articles_dir."art_".($art_id+0).".txt";

if (file_exists($art_file))
{
	unlink($art_file);
	return TRUE;
}
else
{
	return FALSE;
}

} // end delete_article


function get_link_list($link_file)
{
	$bulk = file($link_file);
	
	$list = array();
	for ($i = 0; $i < count($bulk); $i++)
	{
		$ks = trim($bulk[$i]); // elimina i caratteri di fine linea
		if (!empty($ks) & (substr($ks,0,1) != "#") )
		{
			$item = split("::",$ks);
			
			if (count($item) == 2)
			{
				array_push($list,$item);
			}
		}
	}
	
	return $list;
}


function get_config_file($conf_file,$expected_items = 1000)
{
// se non si specifica $expected_items, si assume qualsiasi lunghezza

	$bulk = file($conf_file);
	
	for ($i = 0; $i < count($bulk); $i++)
	{
		$ks = trim($bulk[$i]); // elimina i caratteri di fine linea
		if (!empty($ks) & (substr($ks,0,1) != "#") ) // se la linea non e' vuota e non e' un commento...
		{
			if (  (substr($ks,0,1) == "[") and (substr($ks,-1) == "]")  )
			{
				$title = substr($ks,1,-1);
				$settings[$title] = array();
			}
			else
			{
				$item = split("::",$ks);
				if (count($item) <= $expected_items)
				{
					if (empty($settings))
					{
						$title = 'default';
						$settings[$title] = array();
					}
					array_push($settings[$title],$item);
				}
			}
		}
	}
	
	return $settings;
}


function save_config_file($conf_file,$keys)
{
	$cf = fopen($conf_file, 'w');
	if (!$cf)
	{
		echo "<p>Impossibile aprire il file $conf_file.\n";
		exit;
	}
	
	$acapo = "\r\n";
	foreach ($keys as $block_name => $block_item)
	{
		if ($block_name !== 'default')
		{
			$line = "[$block_name]$acapo";
			fwrite($cf, $line);
		}
		
		foreach($block_item as $riga)
		{
			foreach ($riga as $id => $riga_item)
			{
				if ($id > 0)
				{
					fwrite($cf, "::");
				}
				fwrite($cf, $riga_item);
			}
			
			fwrite($cf, $acapo);
		}
		
		fwrite($cf, $acapo);
	}
	
	fclose($cf);
	return 1;
}


function show_template($template_file)
{
	# dichiara variabili
	extract(indici());
	
	$lines = file($root_path."custom/templates/".$template_file);
	
	$stato=0;
	foreach($lines as $line)
	{
		switch ($stato)
		{
		case 0:
			// cambiamento stato
			if (substr($line,0,10)=="%%%% begin") # la linea deve essere "%%%% begin <nome_file_configurazione> <voce_nel_file>"
			{
				$stato = 1;
				
				# on exit
				$template = '';
				$ks = substr($line,11,-1);
				$info = explode(" ",$ks);
				$config_file = $info[0];
				$array_name = $info[1];
				$num_fields = $info[2];
				continue;
			}
			
			// output
			echo template_to_effective($line);
			
			break;
		case 1:
			// cambiamento stato
			if (substr($line,0,8)=="%%%% end")
			{
				$stato = 0;
				
				#on exit
				$conf = get_config_file($config_dir.$config_file,$num_fields);
				foreach ($conf[$array_name] as $item)
				{
					$arr_template = array();
					for ($i=0; $i<count($item); $i++)
					{
						array_push($arr_template,"%field$i%");
					}
					echo str_replace($arr_template, $item, $template);
				}
				
				continue;
			}
			
			// output
			$template .= template_to_effective($line);
			break;
		}
		
	}
}



function group_match($usergroups,$enabled_groups)
{
// enabled_group vuoto -> abilitato
if (empty($enabled_groups[0]))
{
	return true;
}

foreach($enabled_groups as $enabled_group)
{
	if (in_array($enabled_group,$usergroups))
	{
		return true;
	}
}
return false;

} // end function group_match($usergroups,$enabled_groups)


function create_key_files($id_questions,$num_files,$num_keys) {

# dichiara variabili
extract(indici());

$key_filename_format = sprintf($questions_dir."lotteria_%03d",$id_questions)."_keys_%03d.txt";	// formato dei file di chiavi
$count = 0;
while ($count < $num_files)
{
	$numero_chiavi = $num_keys[$count];
	$key_filename = sprintf($key_filename_format,$count);
	echo "Gestione file di chiavi ".($count+1)." ($key_filename): $numero_chiavi chiavi da generare.<br>\n";
	
	// scrivi il file $count-esimo
	$cf = fopen($key_filename, 'w');
	for ($i = 0; $i < $numero_chiavi; $i++)
	{
		$line = md5($count.$i.time())."\r\n";
		//echo $line."<br>";
		fwrite($cf, $line);
	}
	fclose($cf);
	
	$count++;
}

echo "Fatto.<br>\n";

} // end function create_key_files


function get_question_keys($id_questions) {

# dichiara variabili
extract(indici());

$key_filename_format = sprintf($questions_dir."lotteria_%03d",$id_questions)."_keys_%03d.txt";	// formato dei file di chiavi

$ancora = 1;
$count = 0;
$result = '';
while ($ancora)
{
	$key_filename = sprintf($key_filename_format,$count);
	if (file_exists($key_filename))
	{
		$bulk = get_config_file($key_filename);
		$result[$count] = $bulk['default'];
	}
	else
	{
		$ancora = 0;
	}
	$count++;
}
return $result;

} // end function get_question_keys


function check_question_keys($id_questions,$auth_token) {

$allowed_keys = get_question_keys($id_questions);

$found_key = array();
foreach ($allowed_keys as $bunch_id => $key_bunch)
{
	$count = 0;
	foreach ($key_bunch as $key_line)
	{
		$key = $key_line[0];
		if ($key == $auth_token)
		{
			if (empty($found_key))
			{
				$found_key = array($bunch_id,$count,$key_line);
			}
			else
			{
				die("Chiave duplicata! ($key)"); // non si dovrebbe mai verificare!
			}
		}
		$count++;
	}
}
return $found_key;
} // end function check_question_keys


function get_giocata($id_questions,$auth_token)
{
	$file_log_questions = $root_path."custom/lotterie/lotteria_".sprintf("%03d",$id_questions)."_log.txt";	// nome del file di registrazione
	$bulk = get_config_file($file_log_questions);
	
	$result = array();
	$count_giocata = 0;
	foreach($bulk['default'] as $giocata)
	{
		if ($giocata[3] === $auth_token)
		{
			array_push($result,$giocata);
		}
		$count_giocata++;
	}

	return $result;
}


function show_giocate($giocate)
{
	echo '<table border=1><tbody>';

	$count_giocata = 0;
	foreach($giocate as $giocata)
	{
		echo "<tr>";
		echo "<td>".($count_giocata+1)."</td>\n";
		echo "<td>".$giocata[0]."</td>\n";
		//echo "<td>".$giocata[1]."</td>\n";
		echo "<td>".$giocata[2]."</td>\n";
		echo "<td>".$giocata[3]."</td>\n";
		echo "</tr>";
		$count_giocata++;
	}
	
	echo "</tbody></table>";
}


//Page properties definitions
error_reporting(0); // otherwise "StripDoubleColon($HTTP_REFERER);" gives error
//error_reporting(2039); // otherwise "StripDoubleColon($HTTP_REFERER);" gives error. Show all errors but notices

/************ log.inc **********
implement logging and counting features.
Include this file and call page_count().

Actual counting and/or logging action depend on 3 CONSTANT (use define)
- COUNT : if defined, visit will be counted.
          If defined value is > 0 counter will be shown, otherwise, visit will be
          counted but no counter will be shown.
- LOG   : if defined, hit will be logged. The actual value of LOG doesn't
          matter (later it may be used).

3 files are managed by this script :

- $logfile      : will log every hits. Each line is a hit.
                  format : "$PHP_SELF::$QUERY_STRING::$REMOTE_ADDR::$HTTP_REFERER::$HTTP_USER_AGENT::$date\r\n"
- $counterfile  : will hold visit counters for every pages.
                  format : $PHP_SELF.":".$counter."\r\n"
- $lasthitfile  : will temporarily hold every hit to a 'counted' page. This will prevent
                  counting a 'reload' or a 'back' as an actual visit. It works as follow :
                  When a page is hit, we check in thelasthit file if the same page was hitted from
                  the same IP in the last ($trigger) minutes. If yes, the hit is not counted as a visit.
                  All temporary hits that are out of time are removed on the fly. This keep the lasthitfile
                  very small (only the hits for the last ($trigger) minutes are kept.
                  format : "$PHP_SELF:$REMOTE_ADDR:$now\r\n"

History :
- Improved by dq to prevent '::' to appears inside stored fields (StripDoubleColon())
- Improved by Laurent to automagically back up log file when it becomes too large + email report.

*/

define('MAXLOGFILESIZE', 50*1024);    //maximum log file size = 50Kb !!!
define('MAILTO','pasquale_ceres@yahoo.it'); //who to send email reports

function StripDoubleColon($chunk='')
{
  $chunk=ereg_replace(':+',':',$chunk);
  $chunk=ereg_replace('^:','.:',$chunk);
  $chunk=ereg_replace(':$',':.',$chunk);
  return($chunk);
}

function count_page($myself,$flags,$path_prefix = "")
{
/* $myself e' un id che identifica il contatore. Possono essere gestiti piu' contatori, semplicemente dando $myself diversi
   $flags e' un array i cui campi sono:
	$flags['COUNT'] = [0,1,2]	abilita l'incremento del contatore ( 0 --> non contare, 1 -> conta soltanto, 2 --> conta e visualizza le cifre)
	$flags['LOG'] = [0,1]		scrivi o meno nel file di log
*/

  $HTTP_USER_AGENT = $_SERVER['HTTP_USER_AGENT'];
  $REMOTE_ADDR = $_SERVER['REMOTE_ADDR'];
  $HTTP_REFERER = $_SERVER['HTTP_REFERER'];
  $QUERY_STRING = $_SERVER['QUERY_STRING'];
  $username = $_COOKIE['login']['username'];

  $logfile 	= $path_prefix.'logfile.txt'; 				//every hit log file
  $backupfile 	= $path_prefix.'backupfile%03d.txt';   	//log backup file naming. E' importante lasciare alla fine del nome %3d (formato per sprintf)
  $counterfile 	= $path_prefix.'counterfile.txt';		//miscellaneous pages visit counter
  $lasthitfile 	= $path_prefix.'lasthitfile.txt'; 		//last hits ... used with trigger, allow to prevent counting 'reload' as visit
  $imagepath 	= $path_prefix.'images/';           	//path to digit gif image location
  $minlength 	= 3;       	//min length of the counter (will be padded with 0)
  $trigger 	= 120;          //number of minutes while a second hit from the same ip to the same page in not counted

//Read counter from file or reset counter to 0 if counter file doesn't exist
$output='';

if (file_exists($counterfile))
{
  $cf = fopen($counterfile, 'r');
  $counter=0;
  while (!feof($cf))    //Loop for each line in the file
  {
    $line=fgets($cf, 4096);            //get a line;
    if (ereg("^$myself:(.*)\r\n", $line, $reg_array)) //is this the line corresponding to the actual page
    {
      $counter = $reg_array[1];          //Yes, We save the current counter value
    } else $output.=$line;              //No, just keep it for later rewrite
  } // end while
  fclose($cf);

} else $counter = 0; // first time ... counter = 0.

$contatore_out = $counter;


  if (empty($flags['COUNT']) && empty($flags['LOG'])) return $contatore_out; //nothing to do, so just return


  // ************* COUNT section ****************
  if ($flags['COUNT'] > 0)
  {

    //now we should check if this visitor already went visiting not so long ago
    //no need to count every hit...
    $count_it = 1;  // by default count the visit
    $now = time();                //get current time in second since Unix Epoch
    $hits='';
    if(file_exists($lasthitfile))
    {
      $cf = fopen($lasthitfile, 'r');
      while (!feof($cf))
      {
        $line=fgets($cf, 4096);            //get a line;
		
		//Check if we got a valid formatted line
		$batch = explode(":", $line);
        if (count($batch) <> 3) // three elements for each line
		{
		  continue;       
		}
        $where = $batch[0];
		$who = $batch[1];
		$when = $batch[2];
		
        if ($when > $now-($trigger*60))    //'hit log' still 'in time'
        {
          if (($where==$myself) && ($who==$REMOTE_ADDR))
          {
            $count_it=0;
          }
          else $hits.="$where:$who:$when\n";
        }

      }
      fclose($cf);
    }

    $hits.="$myself:$REMOTE_ADDR:$now\r\n";    //Add this hit to the last hits

    //rewrite updated last hit file
    $cf = fopen($lasthitfile, 'w');
    fwrite($cf, $hits);
    fclose($cf);

    if ($count_it == 1)           // We need to count this hit as a 'visit'?
    {
      //increment counter
      $counter=intval($counter);
      $counter++;

      $output.=$myself.':'.$counter."\r\n";

      //write new counter file
      $cf = fopen($counterfile, 'w');
      fwrite($cf, $output);
      fclose($cf);
    }

   $contatore_out = $counter;

    if ($flags['COUNT'] == 2) //should we actually show the counter
    {
      //format counter
      $counter = sprintf('%0'.$minlength.'d', $counter);
      //print each digit
      for ($i=0; $i < strlen($counter); $i++) {
        print("<IMG SRC=\"$imagepath$counter[$i].gif\" ALT=\"$counter[$i]\">\r\n");
      }
    }
  }


  // ************* LOG section ****************
  if ($flags['LOG'] == 1)
  {
    $date=date("l dS of F Y h:i:s A");
    $log=      StripDoubleColon($myself);
    $log.='::'.StripDoubleColon($QUERY_STRING);
    $log.='::'.StripDoubleColon($REMOTE_ADDR);
    $log.='::'.StripDoubleColon($HTTP_REFERER);
    $log.='::'.StripDoubleColon($HTTP_USER_AGENT);
    $log.='::'.StripDoubleColon($date);
	$log.="::$username\r\n";

    //append current visit to log file
    $cf = fopen($logfile, 'a');
    fwrite($cf, $log);
    fclose($cf);

    //while we are playing with log file, why not checking if the log file isn't too big?
    if (filesize($logfile)>MAXLOGFILESIZE)
    {
     $report='';                                        //we will email a report
     $report.="log file size too large (".filesize($logfile).").\n";
		
	// individua il numero del file di backup (se esiste solo backupfile006.txt, il prossimo sara' backupfile007.txt, altrimenti parti da backupfile000.txt)
	$id = -1;
	if ($dh = opendir($path_prefix)) 
	{
		while (($file = readdir($dh)) !== false) 
		{
		   if (substr($file,0,10)=="backupfile")
			{
				if ($id < substr($file,10,3))
				{
					$id = substr($file,10,3)+0;
				}
			}
		}
		closedir($dh);
	}
	$backupfilename = sprintf($backupfile, $id+1);      //build the file name
	 
	if ($id<999)                                                    //Just in case all the back log file names are used
    {
      $report.="A backup has been done to $backupfilename.\r\n";
      $logs = file($logfile);                            //read all long entries in a array
      $nb_entry = count($logs);                          //how many entries do we have ?
      reset($logs);
      $bf=fopen($backupfilename,'w');                    //open backup file to write
      $lf=fopen($logfile,'w');                           //open original log file for rewriting
      for ($i=0;$i<$nb_entry*10/10; $i++) fwrite($bf, $logs[$i]); //Store 100% of the logs in the back up
      $report.="$i entries have been backed up. ".($nb_entry-$i)." are left in the logfile.\n";
      while ($i<$nb_entry) {fwrite($lf, $logs[$i++]);}   //and leave what's left in the original file
      fclose($bf);                                       //close all
      fclose($lf);
    }
    else $report.="warning !!!! Cannot find an unique backup file name !!!";
	
    #if (defined('MAILTO')) 
	#{
	  #echo $report;
	  #mail(MAILTO,"phplab admin report", $report);
	#}
    }
  }
  
  return $contatore_out;
}

?>
