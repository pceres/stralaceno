<?php

require_once('libreria.php');

# dichiara variabili
extract(indici());

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 TRANSITIONAL//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
  <title><?php echo $web_title ?> - Scheda personale</title>
  <meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1">
  <meta name="GENERATOR" content="Quanta Plus">
  <style type="text/css">@import "<?php echo $filename_css ?>";</style>  
</head>
<body>
  
<div align="center"><h2>Dati personali dei partecipanti alla <?php echo $race_name ?></h2></div>
<hr>

<?php

//$atleti = load_data($filename_atleti,$num_colonne_atleti);

$archivio = load_data($filename_tempi,$num_colonne_prestazioni);

$atleti = load_data($filename_atleti,$num_colonne_atleti);
$lista_edizioni=array();
$archivio = merge_tempi_atleti($archivio,$atleti,$lista_edizioni);


$id = $_REQUEST['id'];

$nome = $atleti[$id][$indice_nome];

$atleta = $atleti[$id];


// tabella prestazioni personali
$lista_regola_campo = array($indice_nome);
$lista_regola_valore = array($nome);
$archivio_filtrato = filtra_archivio($archivio,$lista_regola_campo,$lista_regola_valore);

$mask = array($indice_posiz,$indice_tempo,$indice_anno); # escludo ID e nome
echo "<div align=\"center\">Prestazioni personali di <b>$nome</b></div>";
show_table($archivio_filtrato,$mask,'tabella',3,12,1); # tabella in tre colonne, font 12, con note
echo "<br><hr>";



// gestione eventuale foto
$link_foto = $atleta[$indice2_foto];

if (($link_foto !== '-') & !empty($link_foto))
{
	$flag_internal_photo = 0;
	if (substr($link_foto,0,7) !== 'http://')
	{
		$flag_internal_photo = 1;
		$link_foto = $site_abs_path."custom/album/".$link_foto;
	}
	$link = "<img src=\"$link_foto\" style=\"float:right;margin:1em;\" alt=\"foto di $nome\" border=\"0\" width=\"400\">\n";
	if ($flag_internal_photo)
	{
		$ind = strrpos($link_foto,"/");
		$temp = substr($link_foto,0,$ind);
		$ind2=strrpos($temp,"/");
		$nome_album = substr($temp,$ind2+1,$ind);
		$nome_foto = substr($link_foto,$ind+1);
		$link = "<a href=\"show_photo.php?id_photo=$nome_foto&amp;album=$nome_album\">$link</a>";
	}

	echo $link;
}
else
{
	//die('nessuna foto');
}

echo "Ulteriori informazioni su <b>$nome</b>:<br><br>\n";

echo "Id  : $atleta[$indice2_id] <br>\n";
echo "Nome: $atleta[$indice2_nome] <br>\n";
echo "Sesso: $atleta[$indice2_sesso] <br>\n";
echo "Titolo: $atleta[$indice2_titolo] <br>\n";
echo "Data di nascita: $atleta[$indice2_data_nascita] <br>\n";

$link = trim($atleta[$indice2_link]);
if ($link != "-") {
	if ($link == 'ok') 
	{  
		$link = "personal/$id.htm"; # se non e' specificato un link particolare, usa quello di default
	}
	echo "Sito personale: <a href=\"$link\">$link</a><br>\n";
	}


echo "<hr style=\"clear:right\">\n";

// richiesta informazioni
if (!empty($email_info))
{
?>

<div align="justify" style="font-size: 0.8em;">
Le schede personali dei partecipanti sono in fase di realizzazione.<br>
Gli interessati, per fornire o rettificare le informazioni disponibili, possono mettersi in contatto tramite 
l'indirizzo e-mail: 
<a href="mailto:<?php echo $email_info?>?subject=Info%20sui%20partecipanti%20alla%20<?php echo rawurlencode($race_name) ?>"><?php echo $email_info?></a>.  <br>
</div>

<?php
} // end if (!empty($email_info))


// link alla homepage
echo $homepage_link;

?>


</body>
</html>

