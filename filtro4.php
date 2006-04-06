<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 TRANSITIONAL//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<?php

require_once('libreria.php');

# dichiara variabili
extract(indici());

$anno = $_REQUEST['anno']; 				# anno richiesto

?>
<head>
  <title><?php echo $web_title ?> - Archivio storico annuale - Edizione <?php echo $anno; ?></title>
  <meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1">
  <meta name="GENERATOR" content="Quanta Plus">
  <meta name="description" content="Tempi ufficiali dell'edizione <?php echo $anno; ?>">
  <meta name="keywords" content="Tempi ufficiali, classifica, edizione <?php echo $anno; ?>">
  <style type="text/css">@import "<?php echo $filename_css ?>";</style>
</head>
<body class="tabella">
  
<?php
$archivio = load_data($filename_tempi,$num_colonne_prestazioni);

$atleti = load_data($filename_atleti,$num_colonne_atleti);

$lista_edizioni=array();
$archivio = merge_tempi_atleti($archivio,$atleti,$lista_edizioni);

# individua edizioni precedente e successiva
$prec='';
$succ='';
for ($i=0;$i<count($lista_edizioni);$i++)
{

	if ($lista_edizioni[$i] == $anno)
	{
		if ($i>0) $prec = $lista_edizioni[$i-1];
		if ($i<count($lista_edizioni)) $succ = $lista_edizioni[$i+1];
	}
}

#verifica disponibilita' album fotografico
// carica elenco delle foto disponibili
$elenco_foto = get_config_file($config_dir."albums.txt",3);
$id_nomefile_foto = 0;
$id_titolo_foto = 1;
$id_descrizione_foto = 2;

if (array_key_exists($anno,$elenco_foto))
{
	$nota_album = "<a class=\"txt_link\" href=album.php?anno=$anno>&nbsp;<img src=\"".$site_abs_path."images/fotocamera.gif\" border='0' width=\"50\" alt=\"vedi_album_$anno\"></a>";
}
else
{
	$nota_album = "<img src=\"".$site_abs_path."custom/images/cornice/null.jpg\" border='0' height=\"31\" width=\"1\">";
}

#prepara il titolo
echo "<table width=\"100%\"><tr>";
echo "<td width=\"25%\">";
if (!empty($prec))
{
	echo "<a class=\"txt_link\" href=\"filtro4.php?anno=$prec\">edizione $prec</a>";
}
echo "</td><td align=\"center\" width=\"50%\" nowrap>";
echo "<span class=\"titolo_tabella\">$race_name $anno - risultati ufficiali : $nota_album</span>";
echo "</td><td width=\"25%\" align=\"right\">";
if (!empty($succ))
{
	echo "<a class=\"txt_link\" href=\"filtro4.php?anno=$succ\">edizione $succ</a>";
}
echo "</td>";
echo "</tr></table>";

#prepara la tabella
$archivio = aggiungi_simboli($archivio); // aggiunge il campo 'simb' con il tag html che mostra il simbolo

$lista_regola_campo = array($indice_anno);
$lista_regola_valore = array($anno);
$archivio_filtrato = filtra_archivio($archivio,$lista_regola_campo,$lista_regola_valore);

if (count($archivio_filtrato) == 1)
{
	echo "<br><br>Non ci sono dati disponibili per l'edizione: $anno!";
	die();
}

$lista_indici = array($indice_posiz, $indice_nome);
$archivio_ordinato = ordina_archivio($archivio_filtrato,$lista_indici);

$archivio_rielaborato = fondi_nome_id($archivio_ordinato, $indice_nome, $indice_id);

$mask = array($indice_posiz,$indice_nome,$indice_tempo,'simb'); # escludo l'anno
show_table($archivio_rielaborato,$mask,'tabella',3,12,1); # tabella in tre colonne, font 12, con note

# visualizzazione organizzatori
$organizzatori = load_data($filename_organizzatori,$num_colonne_organizzatori);

$lista_regola_campo = array($indice_anno);
$lista_regola_valore = array($anno);
$archivio_filtrato = filtra_archivio($organizzatori,$lista_regola_campo,$lista_regola_valore);

#$archivio_ordinato = ordina_archivio($archivio_filtrato,$indice_nome, $indice_id); # se volessi ordinare gli organizzatori
$archivio_ordinato = $archivio_filtrato; # non ordino i nomi, vengono presentati nell'ordine con cui sono stati inseriti nel file Excel 'organizzatori_laceno.csv'

# accorpamento collaboratori semplici
$altri_collaboratori = "";
$numero_collaboratori_semplici = 0;
$indice_collaboratore = 0;
$archivio_rielaborato = array($archivio_ordinato[0]);
for ($i = 1; $i < count($archivio_ordinato); $i++) 
{
	$organizzatore = $archivio_ordinato[$i];
	if (in_array(strtoupper($organizzatore[$indice3_incarico]),array("COLLABORATORE","COLLABORATRICE")))
	{
		$altri_collaboratori .= $organizzatore[$indice3_nome].", ";
		$numero_collaboratori_semplici++;
		$indice_collaboratore = $i;
	}
	else
	{
		array_push($archivio_rielaborato,$organizzatore);
	}
}
if ($numero_collaboratori_semplici > 1)
{
	$altri_collaboratori = substr($altri_collaboratori,0,-2);
	$new_collaboratore = array();
	
	$new_collaboratore[$indice3_id] = '';
	$new_collaboratore[$indice3_nome] = $altri_collaboratori;
	$new_collaboratore[$indice3_sesso] = '';
	$new_collaboratore[$indice3_incarico] = 'Altri collaboratori';
	$new_collaboratore[$indice3_anno] = '';
	$new_collaboratore[$indice3_link] = '';
	$new_collaboratore[$indice3_nota] = '';

	array_push($archivio_rielaborato,$new_collaboratore);
}
elseif ($numero_collaboratori_semplici == 1)
{
	array_push($archivio_rielaborato,$archivio_ordinato[$indice_collaboratore]);
}

if (count($archivio_rielaborato) > 1) // la prima riga contiene l'header
{
	echo "<div class=\"tabella_organizzatori\" >";
	echo "<hr>Organizzatori e collaboratori per l'edizione $anno:\n";
	for ($i = 1; $i < count($archivio_rielaborato); $i++) 
	{
		$organizzatore = $archivio_rielaborato[$i];
		
		$nome = $organizzatore[$indice3_nome];
		$incarico = $organizzatore[$indice3_incarico];
		$nota = trim($organizzatore[$indice3_nota]);
		if ( (strlen($nota) > 0) & ($nota != '-')) 
		{
			$incarico .= " ($nota)";
		}
		echo "$incarico:<span style=\"font-style: italic;\">$nome</span>;&nbsp;&nbsp;&nbsp;\n";
	}
	echo "</div>";
}
	
// se ci sono pochi collaboratori in organizzatori.csv, ed e' disponibile un contatto e-mail
if ( (count($archivio_ordinato) <= 1+1) & (strlen($email_info) > 0) )
{
?>

<br>
<div align="justify" style="font-size: 12;">
L'elenco degli organizzatori e collaboratori per questa edizione e' in fase di realizzazione.
Gli interessati, o chi sia in grado di fornire indicazioni al riguardo, sono pregati di mettersi in contatto tramite 
l'indirizzo e-mail: <a href="mailto:<?php echo $email_info?>?subject=Info%20sui%20collaboratori%20della%20<?php echo rawurlencode($race_name) ?>"><?php echo $email_info?></a>.  <br>
</div>

<br>
<?php
}

# logga il contatto
$counter = count_page("classifica_anno",array("COUNT"=>1,"LOG"=>1),$filedir_counter); # abilita il contatore, senza visualizzare le cifre, e fai il log

echo $homepage_link;

?>


</body>
</html>

