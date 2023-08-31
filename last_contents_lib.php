<?php
// PHP script per la gestione degli ultimi contenuti (feed, last_contents, ecc.)
//
// bisogna includere preventivamente libreria.php
//

// indici in righe di $filename_logfile_content:
DEFINE("index_logcontent_type"		,0);
DEFINE("index_logcontent_title"		,1);
DEFINE("index_logcontent_description"	,2);
DEFINE("index_logcontent_link"		,3);
DEFINE("index_logcontent_guid"		,4);
DEFINE("index_logcontent_category"	,5);
DEFINE("index_logcontent_pubDate"	,6);
DEFINE("index_logcontent_author"	,7);
DEFINE("index_logcontent_username"	,8);
DEFINE("index_logcontent_read_allowed"	,9);


function read_last_contents($login)
{
/*
restituisce i dati di un feed nel formato:

$feed['title']		: titolo del feed
$feed['link']		: link del sito associato al feed
$feed['description']	: descrizione del feed (come appare nell'aggregatore RSS)
$feed['category']	: categoria del feed
$feed['language']	: lingua del feed
$feed['copyright']	: testo relativo al copyright
$feed['pubDate']	: data di pubblicazione del feed
$feed['lastBuildDate']	: data di ultima modifica del feed
$feed['docs']		: documentazione sul formato
$feed['generator']	: software che genera il feed
$feed['managingEditor']	: editor usato per modificare il feed
$feed['webMaster']	: email del webmaster
$feed['ttl']		: time to live: durata della cache del feed nell'aggregatore
$feed['image-title']	: titolo dell'immagine
$feed['image-url']	: url dell'immagine
$feed['image-link']	: link puntato dall'immagine
$feed['image-description']: descrizione dell'immagine
$feed['image-width']	: ampiezza in pixel dell'immagine
$feed['image-height']	: larghezza in pixel dell'immagine

$feed['items']		: array costituito da elementi con i campi:
				$item['title']		: titolo della notizia
				$item['description']	: descrizione della notizia
				$item['link']		: link della notizia sul sito
				$item['guid']		: id unico associato alla notizia
				$item['category']	: categoria di appartenenza della notizia
				$item['pubDate']	: data di pubblicazione della notizia
				$item['author']		: autore della notizia
*/

# dichiara variabili
extract(indici());
// filtro sui contenuti da inserire nel feed
$allowed_content_types = Array('forum','articolo_edited','articolo_new','config_file','download');

// individua data ultima modifica dei contenuti
$filename_stat = stat($filename_logfile_content);
$date_last_modify = gmdate('D, j M Y G:i:s +0000',$filename_stat['mtime']);

// carica il file di log dei nuovi contenuti
$log_contents = get_config_file($filename_logfile_content);
$log_contents = $log_contents['default'];

// path assoluto della radice del sito
$abs_url = 'http://'.$_SERVER['HTTP_HOST'].$site_abs_path;

$feed = Array();

$last_date = -1e9;	// last publish date
$items = Array();
$old_item = Array();
foreach ($log_contents as $id => $item_data)
{
	$content_type 		= $item_data[index_logcontent_type];
	$content_title		= $item_data[index_logcontent_title];
	$content_description	= $item_data[index_logcontent_description];
	$content_link		= $item_data[index_logcontent_link];
	$content_guid		= $item_data[index_logcontent_guid];
	$content_category	= $item_data[index_logcontent_category];
	$content_pubDate	= $item_data[index_logcontent_pubDate];
	$content_author		= $item_data[index_logcontent_author];
	$content_username	= $item_data[index_logcontent_username];
	$content_read_allowed	= $item_data[index_logcontent_read_allowed];
	
	//
	// decidi se visualizzare il feed o meno
	//
	$visualizza_feed = '';
	
	// tipo di contenuti consentiti?
	if (!in_array($content_type,$allowed_content_types))
	{
		$visualizza_feed = "content not allowed";
	}
	// il gruppo dell'utente autenticato puo' vedere il contenuto?
	if (!group_match($login['username'],$login['usergroups'],explode(',',$content_read_allowed)))
	{
		$visualizza_feed = "usergroup not allowed ($content_read_allowed)";
	}
	
	// se nessun filtro l'impedisce, visualizza il feed
	if (!empty($visualizza_feed))
	{
		// if content type is not allowed in feed (hidden), go on
		continue;
	}
	
	$unix_time = strtotime($content_pubDate);
	if ($unix_time > $last_date)
	{
		$last_date = $unix_time;
	}
	
	$content_abs_link = $abs_url.$content_link;
	$content_abs_link = str_replace('&','&amp;',$content_abs_link);
	$content_abs_link = str_replace('&amp;','&',$content_abs_link);
	
	$item = Array();
	$item['type'] 		= $content_type; 		// titolo
	$item['title'] 		= $content_title; 		// titolo
	$item['description'] 	= $content_description; 	// description
	$item['link'] 		= $content_abs_link;		// link
	$item['guid'] 		= $content_guid;		// guid
	$item['category'] 	= $content_category;		// category
	$item['pubDate'] 	= $content_pubDate;		// pubDate
	$item['author'] 	= $content_author;		// author
	$item['username'] 	= $content_username;		// username
	
	// pubblica l'item soltanto se ha un campo description diverso dal precedente
	$unchanged_field = 'title';
	if (!array_key_exists($unchanged_field,$old_item) | ($old_item[$unchanged_field] !== $item[$unchanged_field]))
	{
		// punta alla prima locazione vuota
		$target_index = count($items);
	}
	else
	{
		// sovrascrivi il precedente
		$target_index = count($items)-1;
	}
	$items[$target_index] = $item;
	$old_item = $item;
}

// dati del feed
// nome del sito:
$feed['title'] 	= $web_title;
// link della homepage del sito:
$feed['link'] 	= $abs_url.'index.php';
// descrizione del sito:
$feed['description'] 	= "Ultimi aggiornamenti su $web_title";
// categoria del feed:
$feed['category'] 	= 'News'; 	// category
// linguaggio del feed:
$feed['language'] 	= 'it-IT';
// copyright:
$feed['copyright'] 	= 'Copyright Pasquale Ceres 2007';
// pubDate, data di pubblicazione del feed:
$feed['pubDate'] 	= gmdate('D, j M Y G:i:s +0000',$unix_time);
// lastBuildDate, data dell'ultima modifica ai contenuti:
$feed['lastBuildDate'] 	= gmdate('D, j M Y G:i:s +0000',$filename_stat['mtime']);
// docs:
$feed['docs'] 		= 'http://blogs.law.harvard.edu/tech/rss';
// generator:
$feed['generator'] 	= 'PHP code by Pasquale Ceres';
// managingEditor
$feed['managingEditor'] = 'kate';
// webMaster
$feed['webMaster'] 	= 'pasquale_c@hotmail.com';
// time to live [s]
$feed['ttl'] 		= '60';

$feed['image-title']	= $web_title;
$feed['image-url']	= $abs_url.'custom/album/varie/logo.jpg';
$feed['image-link']	= $abs_url.'index.php';
$feed['image-description'] = $web_description;
$feed['image-width']	= '132';
$feed['image-height']	= '57';

// items
$feed['items'] 		= $items;

return $feed;

} // end function $feed = read_last_contents($login)


function publish_rss20($feed)
{
/*
Pubblica un feed nel formato RSS 2.0

$feed['title']		: titolo del feed
$feed['link']		: link del sito associato al feed
$feed['description']	: descrizione del feed (come appare nell'aggregatore RSS)
$feed['category']	: categoria del feed
$feed['language']	: lingua del feed
$feed['copyright']	: testo relativo al copyright
$feed['pubDate']	: data di pubblicazione del feed
$feed['lastBuildDate']	: data di ultima modifica del feed
$feed['docs']		: documentazione sul formato
$feed['generator']	: software che genera il feed
$feed['managingEditor']	: editor usato per modificare il feed
$feed['webMaster']	: email del webmaster
$feed['ttl']		: time to live: durata della cache del feed nell'aggregatore
$feed['image-title']	: titolo dell'immagine
$feed['image-url']	: url dell'immagine
$feed['image-link']	: link puntato dall'immagine
$feed['image-description']: descrizione dell'immagine
$feed['image-width']	: ampiezza in pixel dell'immagine
$feed['image-height']	: larghezza in pixel dell'immagine

$feed['items']		: array costituito da elementi con i campi:
				$item['title']		: titolo della notizia
				$item['description']	: descrizione della notizia
				$item['link']		: link della notizia sul sito
				$item['guid']		: id unico associato alla notizia
				$item['category']	: categoria di appartenenza della notizia
				$item['pubDate']	: data di pubblicazione della notizia
				$item['author']		: autore della notizia
*/


// inizia a creare la pagina
header("Content-type: application/xml");

print '<?xml version="1.0" encoding="ISO-8859-1"?>';
?>
<rss version="2.0">
<channel>
<title><?php echo $feed['title']; ?></title>
<link><?php echo $feed['link']; ?></link>
<description><?php echo $feed['description']; ?></description>
<category><?php echo $feed['category']; ?></category>
<language><?php echo $feed['language']; ?></language>
<copyright><?php echo $feed['copyright']; ?></copyright>
<pubDate><?php echo $feed['pubDate']; ?></pubDate>
<lastBuildDate><?php echo $feed['lastBuildDate']; ?></lastBuildDate>
<docs><?php echo $feed['docs']; ?></docs>
<generator><?php echo $feed['generator']; ?></generator>
<managingEditor><?php echo $feed['managingEditor']; ?></managingEditor>
<webMaster><?php echo $feed['webMaster']; ?></webMaster>
<ttl><?php echo $feed['ttl']; ?></ttl>
<image>
	<title><?php echo $feed['image-title']; ?></title>
	<url><?php echo $feed['image-url']; ?></url>
	<link><?php echo $feed['image-link']; ?></link>
	<description><?php echo $feed['image-description']; ?></description>
	<width><?php echo $feed['image-width']; ?></width>
	<height><?php echo $feed['image-height']; ?></height>
</image>
<?php
foreach ($feed['items'] as $item)
{
?>
<item>
<title><?php echo $item['title']; ?></title>
<description><![CDATA[<?php echo $item['description']; ?>]]></description>
<link><?php echo $item['link']; ?></link>
<guid><?php echo $item['link']; ?></guid>
<category><?php echo $item['category']; ?></category>
<pubDate><?php echo $item['pubDate']; ?></pubDate>
<author><?php echo $item['author']; ?></author>
</item>

<?php
} // end foreach ()
?>

</channel>
</rss>

<?php
} // end function publish_rss20($feed)


function publish_config($feed,$module_name)
{
/*
scrivi i dati del feed in un file di configurazione leggibile dai template

$feed['title']		: titolo del feed
$feed['link']		: link del sito associato al feed
$feed['description']	: descrizione del feed (come appare nell'aggregatore RSS)
$feed['language']	: lingua del feed
$feed['copyright']	: testo relativo al copyright
$feed['pubDate']	: data di pubblicazione del feed
$feed['lastBuildDate']	: data di ultima modifica del feed
$feed['docs']		: documentazione sul formato
$feed['generator']	: software che genera il feed
$feed['managingEditor']	: editor usato per modificare il feed
$feed['webMaster']	: email del webmaster
$feed['ttl']		: time to live: durata della cache del feed nell'aggregatore

$feed['items']		: array costituito da elementi con i campi:
				$item['title']		: titolo della notizia
				$item['description']	: descrizione della notizia
				$item['link']		: link della notizia sul sito
				$item['guid']		: id unico associato alla notizia
				$item['category']	: categoria di appartenenza della notizia
				$item['pubDate']	: data di pubblicazione della notizia
				$item['author']		: autore della notizia
*/

# dichiara variabili
extract(indici());

// determina il nome del modulo, ed il path assoluto
$filename = $_SERVER[SCRIPT_FILENAME];						// path assoluto e nome dello script in esecuzione
$module_endpath = substr($filename,strpos($filename,'custom/moduli/')+14);	// path della cartella contenente i moduli (custom/moduli/)
$module_name = substr($module_endpath,0,strrpos($module_endpath,'/'));		// nome del modulo in esecuzione
$module_abs_path = $modules_dir."$module_name/";				// path completo allo script in esecuzione

//create config file
$filename = $module_abs_path.$module_name."_cfg.txt";
$cf = fopen($filename, 'w');
if ($cf)
{
	$log = "[last_contents_data]\n";
	$log .= date('j M Y G:i:s',strtotime($feed['pubDate']))."\n";
	$log .= "\n";
	fwrite($cf, $log);

	$log = "[items]\n";
	fwrite($cf, $log);

	for ($i = count($feed['items'])-1;$i >= 0;$i--)
	{
		$item = $feed['items'][$i];
		
		$log = $item['title'];
		$log .= "::".$item['description'];
		$log .= "::".$item['link'];
		$log .= "::".$item['guid'];
		$log .= "::".$item['category'];
		$log .= "::".date('j M Y G:i:s',strtotime($item['pubDate']));
		$log .= "::".$item['author'];
		$log .= "\n";
		
		fwrite($cf, $log);
	}
	
	fclose($cf);
}
else
{
    $cf = fopen($filename, 'w');
    if (!$cf) {
        die("$filedir_counter: Errore nella scrittura di $filename. Contattare l'amministratore.");
    } else {
        fclose($cf);
        echo "<br>Il file $filename &egrave; stato creato.<br>";
    }
}

} // end function publish_config($feed,$module_name)


function publish_layout_item($feed,$module_name,$relative_path,$last_contents_num_items)
{
/*
Pubblica un elenco degli ultimi contenuti

$feed['title']		: titolo del feed
$feed['link']		: link del sito associato al feed
$feed['description']	: descrizione del feed (come appare nell'aggregatore RSS)
$feed['language']	: lingua del feed
$feed['copyright']	: testo relativo al copyright
$feed['pubDate']	: data di pubblicazione del feed
$feed['lastBuildDate']	: data di ultima modifica del feed
$feed['docs']		: documentazione sul formato
$feed['generator']	: software che genera il feed
$feed['managingEditor']	: editor usato per modificare il feed
$feed['webMaster']	: email del webmaster
$feed['ttl']		: time to live: durata della cache del feed nell'aggregatore

$feed['items']		: array costituito da elementi con i campi:
				$item['title']		: titolo della notizia
				$item['description']	: descrizione della notizia
				$item['link']		: link della notizia sul sito
				$item['guid']		: id unico associato alla notizia
				$item['category']	: categoria di appartenenza della notizia
				$item['pubDate']	: data di pubblicazione della notizia
				$item['author']		: autore della notizia
*/

# dichiara variabili
extract(indici());

?>

	<!-- inizio blocco last_contents -->
	<tr><td>
	  <table class="column_group"><tbody><tr><td>
	  
		<span class="titolo_colonna">Ultimi aggiornamenti</span>
		 <table cellpadding="0" cellspacing="0">
		  <tbody>


<?php

if (!empty($last_contents_num_items))
{
	$max_lines = $last_contents_num_items;
}
else
{
	$max_lines = 4;
}

	for ($i = count($feed['items'])-1;$i >= max(0,count($feed['items'])-$max_lines);$i--)
	{
		$item = $feed['items'][$i];
		
		$log_title    = htmlentities($item['title']);
		$log_data     = date('j M G:i',strtotime($item['pubDate']));
		$log_link     = $item['link'];
		$log_guid     = $item['guid'];
		$log_category = $item['category'];
		
		$layout_item_array =
			array($log_link,"$log_category ($log_guid)","<i>$log_data:</i>$log_category",$log_title);
		$virtual_item = array($indice_layout_type => "raw",$indice_layout_data => $layout_item_array);
		show_layout_block_item($layout_block,$virtual_item,$layout_data);
	}
	
	$virtual_item = array($indice_layout_name="last_contents",$indice_layout_caption="Altri contenuti recenti...",
		$indice_layout_type => "modulo_custom");
	show_layout_block_item($layout_block,$virtual_item,$layout_data);
?>
		  </tbody>
		 </table>
		
	  </td></tr></tbody></table>
	</td></tr>


<?php

} // end function publish_layout_item($feed)


?>
