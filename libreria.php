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
$indice2_id 		= 0;
$indice2_nome 		= 1;
$indice2_sesso	 	= 2;
$indice2_titolo 	= 3;
$indice2_data_nascita 	= 4;
$indice2_peso 		= 5;
$indice2_link		= 6;

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
$style_sfondo_maschi = "rgb(249, 255, 255)";
$style_sfondo_femmine = "rgb(255, 234, 234)";
$colore_blu_cielo_di_Laceno = "rgb(51,102,153)";
$colore_arancio_fondo_vomitatoio = "rgb(255,102,0)";

$path = $_SERVER['SCRIPT_FILENAME'];
$root_prefix = "stralaceno2";
$start = strpos($path,$root_prefix)+strlen($root_prefix)+1;
$root_path = substr($path,0,$start);

#nomi di file
$filename_tempi 		= $root_path."custom/dati/tempi_laceno.csv";
$filename_atleti 		= $root_path."custom/dati/atleti_laceno.csv";
$filename_organizzatori		= $root_path."custom/dati/organizzatori_laceno.csv";
$filedir_counter 		= $root_path."custom/dati/";
$articles_dir 			= $root_path."custom/articoli/";
$article_online_file 		= $articles_dir."online.txt";


#varie
$email_info		= "stralaceno@freepass.it";
$symbol_empty= '<img style="display:inline;" align="middle" height="13" width="13" alt="empty" border="0">';
$symbol_1_partecipazione= '<img src="/work/stralaceno2/images/0x2606(star).bmp" style="display:inline;" align="middle" height="13" alt="1a partecipazione" border="0">';
$symbol_record  		= '<img src="/work/stralaceno2/images/0x263A(smiling_face).bmp" style="display:inline;" align="middle" height="13" alt="record personale" border="0">';

#admin
$max_last_editions	= 3;	// numero di ultime edizioni in colonna laterale
$max_online_articles	= 3;	// numero di articoli pubblicati online



#campi files csv
$indici = array('indice_id' => $indice_id,'indice_nome' => $indice_nome,'indice_posiz' => $indice_posiz,'indice_tempo' => $indice_tempo,'indice_anno' => $indice_anno,'indice_nota' => $indice_nota,'num_colonne_prestazioni' => $num_colonne_prestazioni,'indice_info' => $indice_info);
$indici2 = array('indice2_id' => $indice2_id,'indice2_nome' => $indice2_nome,'indice2_sesso' => $indice2_sesso,'indice2_titolo' => $indice2_titolo,'indice2_data_nascita' => $indice2_data_nascita,'indice2_peso' => $indice2_peso,'indice2_link' => $indice2_link,'num_colonne_atleti'  => $num_colonne_atleti);
$indici3 = array('indice3_id' => $indice3_id,'indice3_nome' => $indice3_nome,'indice3_sesso' => $indice3_sesso,'indice3_incarico' => $indice3_incarico,'indice3_anno' => $indice3_anno,'indice3_link' => $indice3_link,'indice3_nota' => $indice3_nota,'num_colonne_organizzatori' => $num_colonne_organizzatori);

$formattazione = array('style_sfondo_maschi' => $style_sfondo_maschi,'style_sfondo_femmine' => $style_sfondo_femmine,'colore_blu_stralaceno' => $colore_blu_cielo_di_Laceno,'colore_arancio_stralaceno' => $colore_arancio_fondo_vomitatoio);
$filenames = array('filename_tempi' => $filename_tempi,'filename_atleti' => $filename_atleti,'filename_organizzatori' => $filename_organizzatori,'filedir_counter' => $filedir_counter,'articles_dir' => $articles_dir,'article_online_file' => $article_online_file,'root_path' => $root_path);
$varie = array('email_info' => $email_info,'symbol_1_partecipazione' => $symbol_1_partecipazione,'symbol_record' => $symbol_record);
$admin = array('max_last_editions' => $max_last_editions,'max_online_articles' => $max_online_articles);

return array_merge($indici,$indici2,$indici3,$formattazione,$filenames,$varie,$admin);
}

function load_data($filename,$num_colonne) {

$result=array();

$file = fopen($filename, "r");
if (!$file) {
    echo "<p>Impossibile aprire il file remoto $filename.\n";
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


function show_table($archivio,$mask,$class,$num_colonne = 1,$font_size = -1,$show_note = 1) {

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
	
echo "<div align = \"center\">"; # tieni la tabella al centro

echo "<table class=\"$class\">\n";
echo "  <tbody>\n";
echo "  <tr>\n";
echo "  <td>\n";

$head = $archivio[0];
$head_string = " <thead><tr>\n";
for ($temp = 0; $temp < count($mask); $temp++) {
	$head_string .= "<th>".$head[$mask[$temp]]."</th>\n";
	}
$head_string .= "  </tr></thead>\n";


echo "<table>\n";
echo $head_string;
echo "  <tbody>\n";

$note = array();
for ($i = 1; $i < count($archivio); $i++) {
	$prestazione = $archivio[$i];
	

	# stile riga:
	$style_row = " ";

	# primo arrivato
	if ($prestazione[$indice_posiz] == 1) {
		$style_row .= "id=\"primo\" ";
		}

	if ($prestazione[$indice_info][$indice2_sesso] == "F") {
		# atleti donna
		$style_row .= "class=\"atleta_femmina\" ";
	}
	elseif ($prestazione[$indice_info][$indice2_sesso] == "M") {
		# atleti maschi
		$style_row .= "class=\"atleta_maschio\" ";
	}

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
			if (($show_note) & (strlen($nota) > 0)) {
				$id_nota = count($note)+1;
				$ks = "<a href=\"#nota_".$id_nota."\">&sect;".$id_nota."</a>";
				$campo .= " <small>".$ks."</small>";
				
				array_push($note,$nota);
			}
		}
			
		# campo posizione
		if (array_key_exists("info",$prestazione) & ($mask[$temp] == $indice_posiz) ) {
			if ($campo != '-') {
				#$campo = $campo."<sup>o</sup>";
				$campo = $campo."&deg;";
			}
		}
		echo "<td nowrap><div align=\"$allineamento\">$campo</div></td>";
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
	#echo "<tr style=\"font-size:12;\" colspan=".count($mask)."><td>&nbsp;<sup>&nbsp;</sup></td></tr>\n";
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
if (count($note) > 0) {
	echo "<br>\n";
	echo "<div class=\"nota\">\n";
	echo "Note:<br>\n";
	for ($i = 0; $i < count($note); $i++) {
		echo "<a name=\"nota_".($i+1)."\"><span style=\"color: rgb(255, 0, 0);\">&sect;".($i+1)."</span></a>: ".$note[$i]."<br>\n";
		}
	echo "</div>\n";
	}



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
		#$simb = '<br>';
		$simb = $symbol_empty;
		}
		
	$prestazione['simb'] = $simb;
	
	array_push($archivio2,$prestazione);
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
	$handle=fopen($article_online_file,'w');
	for ($i = 0; $i < count($article_list); $i++)
	{
		fwrite($handle, $article_list[$i]);
		fwrite($handle,"\r\n");
	}
	fclose($handle);
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
	
	
function show_article($art_data) 
{
	echo "	<tr><td>";
	echo "  <table class=\"article_group\"><tbody><tr><td>";

	echo "		<h3>".$art_data["titolo"]."</h3>";
	echo "		<div class=\"txt_articolo\">";

	foreach ($art_data["testo"] as $line)
	{
		echo $line;
	}
	
	echo "		<div class=\"txt_firma_articolo\">".$art_data["autore"]."</div>";
	echo "		</div>";

	echo "	</td></tr></tbody></table>";
	echo "</td></tr>";	
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
$handle=fopen($art_filename,'w');
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


?>

<?php

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

  $logfile 	= $path_prefix.'logfile.txt'; 		//every hit log file
  $backupfile 	= $path_prefix.'backupfile%03d.txt';   	//log backup file naming. E' importante lasciare alla fine del nome %3d (formato per sprintf)
  $counterfile 	= $path_prefix.'counterfile.txt';	//miscellaneous pages visit counter
  $lasthitfile 	= $path_prefix.'lasthitfile.txt'; 	//last hits ... used with trigger, allow to prevent counting 'reload' as visit
  $imagepath 	= $path_prefix.'images/';           	//path to digit gif image location
  $minlength 	= 3;       	//min length of the counter (will be padded with 0)
  $trigger 	= 30;  // 0     //number of minutes while a second hit from the same ip to the same page in not counted

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
    $log.="::$date\r\n";

    //append current visit to log file
    $cf = fopen($logfile, 'a');
    fwrite($cf, $log);
    fclose($cf);

    //while we are playing with log file, why not checking if the log file isn't too big?
    if (filesize($logfile)>MAXLOGFILESIZE)
    {
     $report='';                                        //we will email a report
     $report.="log file size too large (".filesize($logfile).").\n";
     $id = 0;                                           //file counter
     do                                                 //let's find out the next logxxx.txt name
     {
      $backupfilename = sprintf($backupfile, $id);      //build the file name
      $id++;
     } while (file_exists($backupfilename) && ($id<999));            //and loop till we reach a free one
     if ($id<999)                                                    //Just in case all the back log file names are used
     {
      $report.="A backup has been done to $backupfilename.\r\n";
      $logs = file($logfile);                            //read all long entries in a array
      $nb_entry = count($logs);                          //how many entries do we have ?
      reset($logs);
      $bf=fopen($backupfilename,'w');                    //open backup file to write
      $lf=fopen($logfile,'w');                           //open original log file for rewriting
      for ($i=0;$i<$nb_entry*9/10; $i++) fwrite($bf, $logs[$i]); //Store 90% of the logs in the back up
      $report.="$i entries have been backed up. ".($nb_entry-$i)." are left in the logfile.\n";
      while ($i<$nb_entry) {fwrite($lf, $logs[$i++]);}   //and leave what's left in the original file
      fclose($bf);                                       //close all
      fclose($lf);
     }
     else $report.="warning !!!! Cannot find an unique backup file name !!!";
     if (defined('MAILTO')) 
	 {
	  #echo $report;
	  #mail(MAILTO,"phplab admin report", $report);
	 }
    }
  }
  
  return $contatore_out;
}
?>
