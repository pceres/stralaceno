<?php

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


// individua data ultima modifica dei contenuti
$filename_stat = stat($filename_logfile_content);
$date_last_modify = gmdate('D, j M Y G:i:s +0000',$filename_stat['mtime']);

// carica il file di log dei nuovi contenuti
$log_contents = get_config_file($filename_logfile_content);
$log_contents = $log_contents['default'];

// path assoluto della radice del sito
$abs_url = 'http://'.$_SERVER['HTTP_HOST'].$site_abs_path;

// filtro sui contenuti da inserire nel feed
$allowed_content_types = Array('forum','articolo_edited','articolo_new','config_file');

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
	
	if (!in_array($content_type,$allowed_content_types))
	{
		// if content type is not allowed in feed (hidden), go on
		continue;
	}
	
	$unix_time = strtotime($content_pubDate);
	if ($unix_time > $last_date)
	{
		$last_date = $unix_time;
	}
	
	
	$item = Array();
	$item['type'] 		= $content_type; 		// titolo
	$item['title'] 		= $content_title; 		// titolo
	$item['description'] 	= $content_description; 	// description
	$item['link'] 		= $abs_url.$content_link;	// link
	$item['guid'] 		= $content_guid;		// guid
	$item['category'] 	= $content_category;		// category
	$item['pubDate'] 	= $content_pubDate;		// pubDate
	$item['author'] 	= $content_author;		// author
	$item['username'] 	= $content_username;		// username
	
	// pubblica l'item soltanto se ha un campo description diverso dal precedente
	if (!array_key_exists('description',$old_item) | ($old_item['description'] !== $item['description']))
	{
		$items[count($items)] = $item;
		$old_item = $item;
	}
}

// dati del feed
// nome del sito:
$feed['title'] 	= $web_title;
// link della homepage del sito:
$feed['link'] 	= $abs_url.'index.php';
// descrizione del sito:
$feed['description'] 	= $web_description;
// linguaggio del feed:
$feed['language'] 	= 'it-it';
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

// items
$feed['items'] 		= $items;

publish_config($feed,$module_name);


function publish_config($feed,$module_name)
{
/*
Pubblica un feed nel formato RSS 2.0

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

//create config file
$filename = $module_name."_cfg.txt";
$cf = fopen($filename, 'w');
if ($cf)
{
	
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
	die("Errore nella scrittura di $filename. Contattare l'amministratore.");
}

// die($log);

} // end function publish_rss20($feed)

?>