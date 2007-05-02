<?php

require_once('libreria.php');

# dichiara variabili
extract(indici());

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
	
	
	// articolo 1
	$items[count($items)] = Array();
	// title
	$items[count($items)-1]['title'] 		= $content_title;
	// description
	$items[count($items)-1]['description'] 		= "<![CDATA[".$content_description."]]>";
	// link
	$items[count($items)-1]['link'] 		= $abs_url.$content_link;
	// guid
	$items[count($items)-1]['guid'] 		= $content_guid;
	// category
	$items[count($items)-1]['category'] 		= $content_category;
	// pubDate
	$items[count($items)-1]['pubDate'] 		= $content_pubDate;
	// author
	$items[count($items)-1]['author'] 		= $content_author;
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


publish_rss20($feed);


function publish_rss20($feed)
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


// inizia a creare la pagina
// header("Content-type: application/rss+xml");
header("Content-type: application/xml");

print '<?xml version="1.0" encoding="ISO-8859-1"?>';
?>
<rss version="2.0">
<channel>
<title><?php echo $feed['title']; ?></title>
<link><?php echo $feed['link']; ?></link>
<description><?php echo $feed['description']; ?></description>
<language><?php echo $feed['language']; ?></language>
<copyright><?php echo $feed['copyright']; ?></copyright>
<pubDate><?php echo $feed['pubDate']; ?></pubDate>
<lastBuildDate><?php echo $feed['lastBuildDate']; ?></lastBuildDate>
<docs><?php echo $feed['docs']; ?></docs>
<generator><?php echo $feed['generator']; ?></generator>
<managingEditor><?php echo $feed['managingEditor']; ?></managingEditor>
<webMaster><?php echo $feed['webMaster']; ?></webMaster>
<ttl><?php echo $feed['ttl']; ?></ttl>

<?php
foreach ($feed['items'] as $item)
{
?>
<item>
<title><?php echo $item['title']; ?></title>
<description>
<?php echo $item['description']; ?>
</description>
<link><?php echo $item['link']; ?></link>
<guid><?php echo $item['guid']; ?></guid>
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
?>