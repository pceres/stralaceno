<?php

DEFINE("indice_download_type"	, 0);
DEFINE("indice_download_name"	, 1);
DEFINE("indice_download_caption", 2);
DEFINE("indice_download_params"	, 3);
DEFINE("indice_download_auth"	, 4);


////////////////////////////////////////////////////////////////////////////////////////
function show_download_folder($tree,$folder_path,$folder_caption,$folder_parent,$folder_auth,$login,$level)
{
$download_error_msg = '';

//
// inizializzazione pagina
//
if ($level == 0)
{
	$item 		= $tree['data_folder'];
	$item_type 	= $item[indice_download_type];
	$item_name 	= $item[indice_download_name];
	$item_caption 	= $item[indice_download_caption];
	$item_params 	= $item[indice_download_params];
	$item_auth 	= $item[indice_download_auth];
	
	
	show_download_header();
	
	// il gruppo dell'utente autenticato puo' accedere al folder?
	if (!group_match($login['username'],$login['usergroups'],split(',',$folder_auth)))
	{
		$download_error_msg = "L'utente &quot;{$login['username']}&quot; not &egrave; abilitato ad accedere alla sezione &quot;$folder_caption&quot;.";
	}
	else
	{
		if ((is_string($folder_parent)) & ($folder_parent === 'folder_root'))
		{
			// root folder
			$folder_parent_link = "index.php";
			$folder_parent_caption = "Homepage";
			$back_msg = "<a href=\"$folder_parent_link\">$folder_parent_caption</a> ->";
			$full_path = "/";
		}
		elseif (!array_key_exists(indice_download_caption,$folder_parent))
		{
			// folder di primo livello (manca il campo folder_parent)
			$folder_parent_link = "download.php";
			$folder_parent_caption = "Sezione download";
			$back_msg = "<a href=\"$folder_parent_link\">$folder_parent_caption</a> ->";
			$full_path = "/$item_params/";
		}
		else
		{
			// folder di livello maggiore
			$folder_parent_type = $folder_parent[indice_download_type];
			$folder_parent_name = $folder_parent[indice_download_name];
			$folder_parent_link = "download.php?resource_type=$folder_parent_type&amp;resource_id=$folder_parent_name";
			$folder_parent_caption = $folder_parent[indice_download_caption];
			$back_msg = "... -> <a href=\"$folder_parent_link\">$folder_parent_caption</a> ->";
			$full_path = "$folder_path/$item_params/";
		}
		
		// struttura superiore pagina download
		echo "<h2 id=\"dm_title\">$folder_caption</h2>\n";
		echo "<div id=\"dm_cats\">\n";
		echo "<h3>Categorie<span>$back_msg $folder_caption</span></h3>\n";
	}
}


//
// corpo pagina
//
if (!empty($download_error_msg))
{
	echo($download_error_msg);
}
else
{
	
	$folders = array_diff(array_keys($tree),Array('data_folder')); // elimino il campo fittizio 'data_folder'
	
	foreach($folders as $folder_item_name)
	{
		if (array_key_exists($folder_item_name,$tree))
		{
			$folder_item = $tree[$folder_item_name];
			$folder_item_type = $folder_item['data_folder'][indice_download_type];
			
			show_download_item($folder_item['data_folder'],$level);
			if ($folder_item_type == 'folder')
			{
// 				show_download_folder($folder_path,$folder_item,'',Array(),'',$login,$level+1);
			}
		}
		else
		{
			die("***");
		}
	}
}

//
// chiusura pagina
//
if ($level == 0)
{
	$write_enabled = 0;
	
	echo "</div>\n";
	if ($write_enabled)
	{
		echo "<br><br><hr><div><a href=\"\">Aggiungi risorsa nel folder $full_path</a></div>";
	}
	
	show_download_footer();
}

} // end show_download_folder($tree,$folder_caption,$folder_parent,$level)


////////////////////////////////////////////////////////////////////////////////////////
function show_download_item($folder_item,$level)
{
$folder_item_type 	= $folder_item[indice_download_type];
$folder_item_name 	= $folder_item[indice_download_name];
$folder_item_caption 	= $folder_item[indice_download_caption];
$folder_item_params 	= $folder_item[indice_download_params];
$folder_item_auth 	= $folder_item[indice_download_auth];

$spacing = str_repeat('&nbsp;',$level*8);
switch ($folder_item_type)
{
case 'folder':
	show_download_item_folder($folder_item);
	break;
	
case 'file':
	show_download_item_file($folder_item);
	break;
	
case 'link':
	show_download_item_link($folder_item);
	break;
	
default:
	die("Tipo $folder_item_type sconosciuto per $folder_item_name!");
}

} // end function show_download_item($folder_item,$level)


//////////////////////////////////////////////////////////////////////////////////////
function show_download_item_folder($folder_item)
{
$folder_item_type 	= $folder_item[indice_download_type];
$folder_item_name 	= $folder_item[indice_download_name];
$folder_item_caption 	= $folder_item[indice_download_caption];
$folder_item_params 	= $folder_item[indice_download_params];
$folder_item_auth 	= $folder_item[indice_download_auth];

// 	echo $spacing."- <a href=\"download.php?resource_type=folder&amp;resource_id=$folder_item_name\">$folder_item_caption</a><br>\n";

?>

<!-- folder <?php echo $folder_item_name; ?>-->
<dl>
<dt class="dm_row">
	<a class="dm_icon" href="download.php?resource_type=folder&amp;resource_id=<?php echo $folder_item_name; ?>">
	<img src="<?php echo $site_abs_path; ?>images/folder.png" alt="folder icon"/></a>
	<a class="dm_name" href="download.php?resource_type=folder&amp;resource_id=<?php echo $folder_item_name; ?>">
		<?php echo $folder_item_caption; ?>
	</a>
</dt>
<dd class="dm_description"><p>Categoria dedicata a "<?php echo $folder_item_caption; ?>"&nbsp;</p></dd>
</dl>

<?php

} // end function show_download_item_folder($folder_item)


//////////////////////////////////////////////////////////////////////////////////////
function show_download_item_file($folder_item)
{
$folder_item_type 	= $folder_item[indice_download_type];
$folder_item_name 	= $folder_item[indice_download_name];
$folder_item_caption 	= $folder_item[indice_download_caption];
$folder_item_params 	= $folder_item[indice_download_params];
$folder_item_auth 	= $folder_item[indice_download_auth];

// 	echo $spacing."- <a href=\"download.php?resource_type=file&amp;resource_id=$folder_item_name\">$folder_item_caption</a><br>\n";

?>

<!-- file <?php echo $folder_item_name; ?>-->
<dl>
<dt>
<a class="dm_icon" href="download.php?resource_type=file&amp;resource_id=<?php echo $folder_item_name; ?>">
	<img src="<?php echo $site_abs_path; ?>images/generic.png" alt="file icon">
</a>
<a class="dm_name" href="download.php?resource_type=file&amp;resource_id=<?php echo $folder_item_name; ?>">
	<?php echo $folder_item_caption; ?>
</a>
</dt>
<dd class="dm_date">
gg.mm.aaaa	</dd>
<dd class="dm_description">
<p>Descrizione della risorsa "<?php echo $folder_item_caption; ?>"&nbsp;</p>	</dd>

<dd class="dm_counter">
Hits: x	</dd>
<dd class="dm_taskbar">
<ul>
<li><a href="download.php?resource_type=file&amp;resource_id=<?php echo $folder_item_name; ?>&amp;download_mode=attachment">Download</a></li>
<li><a href="download.php?resource_type=file&amp;resource_id=<?php echo $folder_item_name; ?>">Visualizza</a></li>
</ul>
</dd>
</dl>

<?php
} // end function show_download_item_file($folder_item)













//////////////////////////////////////////////////////////////////////////////////////
function show_download_item_link($folder_item)
{
$folder_item_type 	= $folder_item[indice_download_type];
$folder_item_name 	= $folder_item[indice_download_name];
$folder_item_caption 	= $folder_item[indice_download_caption];
$folder_item_params 	= $folder_item[indice_download_params];
$folder_item_auth 	= $folder_item[indice_download_auth];

// 	echo $spacing."- <a href=\"download.php?resource_type=link&amp;resource_id=$folder_item_name\"> --&gt;$folder_item_caption</a><br>\n";

?>

<!-- link <?php echo $folder_item_name; ?>-->
<dl>
<dt>
<a class="dm_icon" href="download.php?resource_type=link&amp;resource_id=<?php echo $folder_item_name; ?>">
	<img src="<?php echo $site_abs_path; ?>images/link.png" alt="file icon">
</a>
<a class="dm_name" href="download.php?resource_type=link&amp;resource_id=<?php echo $folder_item_name; ?>">
	<?php echo $folder_item_caption; ?>
</a>
</dt>
<dd class="dm_date">
gg.mm.aaaa	</dd>
<dd class="dm_description">
<p>Descrizione della risorsa "<?php echo $folder_item_caption; ?>"&nbsp;</p>	</dd>

<dd class="dm_counter">
Hits: x	</dd>
<dd class="dm_taskbar">
<ul>
	<li>
		<a href="download.php?resource_type=link&amp;resource_id=<?php echo $folder_item_name; ?>">Visualizza</a>
	</li>
</ul>
</dd>
</dl>

<?php
} // end function show_download_item_link($folder_item)





////////////////////////////////////////////////////////////////////////////////////////
function add_download_folder(&$tree,$folder_name,$config_download,$level)
{
$new_folder_item = $config_download[$folder_name];

foreach($new_folder_item as $id_item => $folder_item)
{
	$new_folder_name = $folder_item[indice_download_name];
	$new_folder_type = $folder_item[indice_download_type];
	
	$tree[$new_folder_name] = Array('data_folder' => $folder_item);
	
	if ($new_folder_type == 'folder')
	{
		add_download_folder($tree[$new_folder_name],$new_folder_name,$config_download,$level+1);
	}
} // end foreach

} // end function add_download_folder($tree,$config_download);


////////////////////////////////////////////////////////////////////////////////////////
function extract_download_item($tree,$resource_type,$resource_id,$level)
{
$download_item_struct = Array();

// path dell'elemento
if (count($level)==0)
{
// 	$folder_parent_name = 'folder_root';
	$folder_parent_name = '';
}
else
{
	$folder_parent_name = $tree['data_folder'][indice_download_params];
}
array_push($level,$folder_parent_name);

// items presenti nel folder
$folders = array_diff(array_keys($tree),Array('data_folder'));

foreach($folders as $folder_item_name)
{
	$folder_item = $tree[$folder_item_name];
	$folder_item_type = $folder_item['data_folder'][indice_download_type];
	
	if ( ($folder_item_type == $resource_type) & ($folder_item_name == $resource_id) )
	{
		$resource_path = implode("/",$level);
		$resource_data = $folder_item;
		$resource_parent = $tree['data_folder'];
		
		$download_item_struct = Array(
			'resource_path' => $resource_path,
			'resource_data' => $resource_data,
			'resource_parent' => $resource_parent
			);
	}
	elseif ($folder_item_type == 'folder')
	{
		$download_item_struct = extract_download_item($folder_item,$resource_type,$resource_id,$level);
	}
	
	if (!empty($download_item_struct))
	{
		return $download_item_struct;
	}
}

} // end function add_download_folder($tree,$config_download);



////////////////////////////////////////////////////////////////////////////////////////
function download_file($download_resource_info,$fullname,$login,$download_mode) {

$resource_path 		= $download_resource_info['resource_type'];
$resource_name 		= $download_resource_info['resource_name'];
$resource_caption 	= $download_resource_info['resource_caption'];
$resource_params 	= $download_resource_info['resource_params'];
$resource_auth 		= $download_resource_info['resource_auth'];

// il gruppo dell'utente autenticato puo' scaricare il contenuto?
$download_error_msg = '';
if (!group_match($login['username'],$login['usergroups'],split(',',$resource_auth)))
{
	$download_error_msg = "L'utente &quot;{$login['username']}&quot; not &egrave; abilitato a scaricare la risorsa $resource_name.";
}

// verifica che il file esista
if (!file_exists($fullname))
{
	$download_error_msg = "Il file &quot;$fullname&quot; not esiste (controllare la risorsa &quot;$resource_name&quot;)!";
}


// se nessun filtro l'impedisce, visualizza il feed
if (!empty($download_error_msg))
{
	show_download_header();
	
	die($download_error_msg);
	
	show_download_footer();
}
else
{
	// sostituisci gli spazi con "%20"
	$template = array(" ");
	$effective = array("%20");
	$fullname = str_replace($template, $effective, $fullname);
	
	$file_name = "file://".$fullname;
	
	$fullfilename = urldecode($fullname);
	
	//send headers
	send_download_headers($fullfilename,$download_mode);
	
	//send file contents
	$fp=fopen($fullfilename, "r");
	fpassthru($fp);
}


} // end function download_file($folder_item)



////////////////////////////////////////////////////////////////////////////////////////
function download_link($download_resource_info,$fullname,$login) {

$resource_path 		= $download_resource_info['resource_type'];
$resource_name 		= $download_resource_info['resource_name'];
$resource_caption 	= $download_resource_info['resource_caption'];
$resource_params 	= $download_resource_info['resource_params'];
$resource_auth 		= $download_resource_info['resource_auth'];

// il gruppo dell'utente autenticato puo' scaricare il contenuto?
$download_error_msg = '';
if (!group_match($login['username'],$login['usergroups'],split(',',$resource_auth)))
{
	$download_error_msg = "L'utente &quot;{$login['username']}&quot; not &egrave; abilitato a scaricare la risorsa $resource_name.";
}

// se nessun filtro l'impedisce, visualizza il feed
if (!empty($download_error_msg))
{
	show_download_header();
	
	die($download_error_msg);
	
	show_download_footer();
}
else
{
	// sostituisci gli spazi con "%20"
	$template = array(" ");
	$effective = array("%20");
	$fullname = str_replace($template, $effective, $resource_params);
	
	header("Location: $fullname");
	exit();
}


} // end function download_file($folder_item)



////////////////////////////////////////////////////////////////////////////////////////
function send_download_headers($fullname,$download_mode) {

$lista_mime_types = Array(
        'HTM' 	=> 'text/html',
        'HTML' 	=> 'text/html',
        'TXT' 	=> 'text/plain',
	'PDF' 	=> 'application/pdf',
	'DOC' 	=> 'application/msword',
	'XLS' 	=> 'application/vnd.ms-excel',
	'SWF' 	=> 'application/x-shockwave-flash',
	'JPG' 	=> 'image/jpeg',
	'JPEG' 	=> 'image/jpeg',
	'GIF' 	=> 'image/gif',
	'WAV' 	=> 'audio/x-wav',
	'MP3' 	=> 'audio/x-mp3',
	'MPEG' 	=> 'audio/mpeg',
	'QT' 	=> 'video/quicktime');


$p = explode('/', $fullname);
$pc = count($p);
$filename = $p[$pc-1];

$p = explode('.', $fullname);
$pc = count($p);
$ext = strtoupper($p[$pc-1]);

if (isset($lista_mime_types[$ext]))
{
	$mimetype = $lista_mime_types[$ext];
}
else
{	
	$mimetype = '';
}


if (!empty($mimetype) && ($download_mode === 'inline') )
{
	//display file inside browser
	header("Content-type: " . $mimetype . "\n");
	header("Content-disposition: inline; filename=\"$filename\"\n");   // THIS LINE IS ADDED TO GIVE A CHOICE TO OPEN, OR SAVE AS FILENAME.
}
else
{
	//force download dialog
	header("Content-type: application/octet-stream\n");
	header("Content-disposition: attachment; filename=\"$filename\"\n");	// THIS LINE IS ADDED TO GIVE A CHOICE TO OPEN, OR SAVE AS FILENAME.
}

header('Cache-Control: public');                // THIS LINE IS ADDED TO MAKE  MSIE WORK AT ALL
header("Content-transfer-encoding: binary\n");
header("Content-length: " . filesize($fullname) . "\n");

} // end function send_download_headers()


////////////////////////////////////////////////////////////////////////////////////////
function show_download_header() {

# dichiara variabili
extract(indici());

?><!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 TRANSITIONAL//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
  <title><?php echo $web_title ?> - Area download </title>
  <meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1">
  <meta name="GENERATOR" content="Quanta Plus">
  <meta name="description" content="Area download del sito <?php echo $web_title; ?>">
  <meta name="keywords" content="area download">
  <style type="text/css">@import "<?php echo $filename_css ?>";</style>
</head>

<body class="download">

<?php
} // end function show_download_header()


////////////////////////////////////////////////////////////////////////////////////////
function show_download_footer() {

?>

</body>
</html>

<?php
} // end function show_download_footer()


////////////////////////////////////////////////////////////////////////////////////////
function get_download_resource_info($tree,$resource_type,$resource_id) {

$download_item_struct = extract_download_item($tree,$resource_type,$resource_id,Array());

$folder_item = $download_item_struct['resource_data']['data_folder'];

$resource_tree 		= $download_item_struct['resource_data'];
$resource_path 		= $download_item_struct['resource_path'];
$resource_parent	= $download_item_struct['resource_parent'];

$resource_type 		= $folder_item[indice_download_type];
$resource_name 		= $folder_item[indice_download_name];
$resource_caption 	= $folder_item[indice_download_caption];
$resource_params 	= $folder_item[indice_download_params];
$resource_auth 		= $folder_item[indice_download_auth];

$result = array('resource_tree' => $resource_tree,
	'resource_path' 	=> $resource_path,
	'resource_parent' 	=> $resource_parent,
	'resource_type' 	=> $resource_type,
	'resource_name' 	=> $resource_name,
	'resource_caption' 	=> $resource_caption,
	'resource_params' 	=> $resource_params,
	'resource_auth' 	=> $resource_auth);

return $result;

} // end function get_download_resource_info($tree,$resource_type,$resource_id)


?>
