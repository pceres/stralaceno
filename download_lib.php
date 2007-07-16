<?php

DEFINE("indice_download_type"		, 0);
DEFINE("indice_download_name"		, 1);
DEFINE("indice_download_caption"	, 2);
DEFINE("indice_download_description"	, 3);
DEFINE("indice_download_params"		, 4);
DEFINE("indice_download_auth_read"	, 5);
DEFINE("indice_download_auth_write"	, 6);
DEFINE("indice_download_ctime"		, 7);
DEFINE("indice_download_hits"		, 8);


////////////////////////////////////////////////////////////////////////////////////////
function show_download_folder(&$config_download,$download_resource_info,&$fulltree,$login,$level,$admin_mode)
{

$download_error_msg = '';

$tree 			= $download_resource_info['resource_tree'];
$folder_path 		= $download_resource_info['resource_path'];
$folder_parent 		= $download_resource_info['resource_parent'];
$folder_caption 	= $download_resource_info['resource_caption'];
$folder_auth_read 	= $download_resource_info['resource_auth_read'];

//
// inizializzazione pagina
//
if ($level == 0)
{
	$item 			= $tree['data_folder'];
	$item_type 		= $item[indice_download_type];
	$item_name 		= $item[indice_download_name];
	$item_caption 		= $item[indice_download_caption];
	$item_description 	= $item[indice_download_description];
	$item_params 		= $item[indice_download_params];
	$item_auth_read 	= $item[indice_download_auth_read];
	$item_auth_write	= $item[indice_download_auth_write];
	$item_ctime 		= $item[indice_download_ctime];
	$item_hits 		= $item[indice_download_hits];
	
	$script_name = $_SERVER['SCRIPT_NAME'];
	
	show_download_header();
	
	// il gruppo dell'utente autenticato puo' accedere al folder?
	if (!group_match($login['username'],$login['usergroups'],split(',',$folder_auth_read)))
	{
		$download_error_msg = "L'utente &quot;{$login['username']}&quot; not &egrave; abilitato ad accedere alla sezione &quot;$folder_caption&quot;.";
	}
	else
	{
		if (($item_type === 'folder') & ($item_name === 'folder_root'))
		{
			// root folder
			$folder_parent_link = "index.php";
			$folder_parent_caption = "Homepage";
			$back_msg = "<a href=\"$folder_parent_link\">$folder_parent_caption</a> ->";
			$full_path = "/";
			$back_arrow = "";
		}
		else
		{
			// folder di livello maggiore
			$folder_parent_type = $folder_parent[indice_download_type];
			$folder_parent_name = $folder_parent[indice_download_name];
			$folder_parent_link = $server_name."?resource_type=$folder_parent_type&amp;resource_id=$folder_parent_name";
			$folder_parent_caption = $folder_parent[indice_download_caption];
			$back_msg = "... -> <a href=\"$folder_parent_link\">$folder_parent_caption</a> ->";
			$back_arrow = "<a class=\"no_underline\" href=\"$folder_parent_link\"><big>&larr;</big></a>&nbsp;&nbsp;";
			$full_path = "$folder_path/$item_params/";
		}
		
		// struttura superiore pagina download
		echo "<h2 id=\"dm_title\">$back_arrow $folder_caption</h2>\n";
		echo "<div id=\"dm_cats\">\n";
		echo "<h3>$item_description\n";
		echo "<span>$back_msg $folder_caption</span></h3>\n";
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
			
			show_download_item($folder_item['data_folder'],$level,$fulltree,$admin_mode);
// 			if ($folder_item_type == 'folder')
// 			{
// 				show_download_folder($folder_path,$folder_item,'',Array(),'',$login,$level+1);
// 			}
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
	
	// aggiorna contatore
	if ( ($item_name !== 'folder_root') & (!$admin_mode) )
	{
		increment_download_counter($config_download,$download_resource_info);
	}
}

} // end show_download_folder($fulltree,$tree,$folder_caption,$folder_parent,$level)


////////////////////////////////////////////////////////////////////////////////////////
function show_download_item($folder_item,$level,&$fulltree,$admin_mode)
{
$folder_item_type 	 = $folder_item[indice_download_type];
$folder_item_name 	 = $folder_item[indice_download_name];
$folder_item_caption 	 = $folder_item[indice_download_caption];
$folder_item_description = $folder_item[indice_download_description];
$folder_item_params 	 = $folder_item[indice_download_params];
$folder_item_auth_read 	 = $folder_item[indice_download_auth_read];
$folder_item_auth_write	 = $folder_item[indice_download_auth_write];
$folder_item_ctime 	 = $folder_item[indice_download_ctime];
$folder_item_hits 	 = $folder_item[indice_download_hits];

$spacing = str_repeat('&nbsp;',$level*8);
switch ($folder_item_type)
{
case 'folder':
	show_download_item_folder($folder_item,$fulltree,$admin_mode);
	break;
	
case 'file':
	show_download_item_file($folder_item,$fulltree,$admin_mode);
	break;
	
case 'link':
	show_download_item_link($folder_item,$fulltree,$admin_mode);
	break;
	
default:
	die("Tipo $folder_item_type sconosciuto per $folder_item_name!");
}

} // end function show_download_item($folder_item,$level,$tree,$admin_mode)


//////////////////////////////////////////////////////////////////////////////////////
function show_download_item_folder($folder_item,&$fulltree,$admin_mode)
{

# dichiara variabili
extract(indici());

$folder_item_type 	 = $folder_item[indice_download_type];
$folder_item_name 	 = $folder_item[indice_download_name];
$folder_item_caption 	 = $folder_item[indice_download_caption];
$folder_item_description = $folder_item[indice_download_description];
$folder_item_params 	 = $folder_item[indice_download_params];
$folder_item_auth_read 	 = $folder_item[indice_download_auth_read];
$folder_item_auth_write	 = $folder_item[indice_download_auth_write];
$folder_item_ctime 	 = $folder_item[indice_download_ctime];
$folder_item_hits 	 = $folder_item[indice_download_hits];

// 	echo $spacing."- <a href=\"download.php?resource_type=folder&amp;resource_id=$folder_item_name\">$folder_item_caption</a><br>\n";

$script_name = $_SERVER['SCRIPT_NAME'];
$resource_link = "$script_name?resource_type=$folder_item_type&amp;resource_id=$folder_item_name";

echo "<dl>";
show_download_item_info($folder_item,&$fulltree,$resource_link);

if ($admin_mode)
{
	$admin_link = "manage_download.php?resource_type=$folder_item_type&amp;resource_id=$folder_item_name";
?>
<dd class="dm_taskbar">
<ul>
<li><a href="<?php echo $admin_link; ?>&amp;admin_action=edit_data">Modifica info</a></li>
<!-- <li><a href="<?php echo $admin_link; ?>&amp;admin_action=delete_folder">Cancella cartella</a></li> -->
<!-- <li><a href="<?php echo $admin_link; ?>&amp;admin_action=new_folder">Nuova cartella</a></li> -->
<!-- <li><a href="<?php echo $admin_link; ?>&amp;admin_action=new_file">Nuovo file</a></li> -->
<!-- <li><a href="<?php echo $admin_link; ?>&amp;admin_action=new_link">Nuovo link</a></li> -->
</ul>
</dd>
<?php
} // end if ($admin_mode)
?>
</dl>

<?php

} // end function show_download_item_folder($folder_item)


//////////////////////////////////////////////////////////////////////////////////////
function show_download_item_file($folder_item,&$fulltree,$admin_mode)
{

# dichiara variabili
extract(indici());

$folder_item_type 	 = $folder_item[indice_download_type];
$folder_item_name 	 = $folder_item[indice_download_name];
$folder_item_caption 	 = $folder_item[indice_download_caption];
$folder_item_description = $folder_item[indice_download_description];
$folder_item_params 	 = $folder_item[indice_download_params];
$folder_item_auth_read 	 = $folder_item[indice_download_auth_read];
$folder_item_auth_write	 = $folder_item[indice_download_auth_write];
$folder_item_ctime 	 = $folder_item[indice_download_ctime];
$folder_item_hits 	 = $folder_item[indice_download_hits];

$script_name = $_SERVER['SCRIPT_NAME'];
$resource_link = "$script_name?resource_type=$folder_item_type&amp;resource_id=$folder_item_name";

echo "<dl>";
show_download_item_info($folder_item,&$fulltree,$resource_link);

echo "<dd class=\"dm_taskbar\">";

if ($admin_mode)
{
	$admin_link = "manage_download.php?resource_type=$folder_item_type&amp;resource_id=$folder_item_name";
?>
<ul>
<!-- <li><a href="<?php echo $admin_link; ?>&amp;admin_action=delete_file">Cancella file</a></li> -->
<li><a href="<?php echo $admin_link; ?>&amp;admin_action=update_file">Ricarica file</a></li>
<!-- <li><a href="<?php echo $admin_link; ?>&amp;admin_action=edit_data">Modifica info</a></li> -->
</ul>
</dd>
<?php
} // end if ($admin_mode)
else
{
?>
<ul>
<li><a href="<?php echo $resource_link; ?>&amp;download_mode=attachment">Download</a></li>
<li><a href="<?php echo $resource_link; ?>">Visualizza</a></li>
</ul>
</dd>
<?php
} // end if ($admin_mode)
?>
</dl>

<?php
} // end function show_download_item_file($folder_item)














//////////////////////////////////////////////////////////////////////////////////////
function show_download_item_link($folder_item,&$fulltree,$admin_mode)
{

# dichiara variabili
extract(indici());

$folder_item_type 	 = $folder_item[indice_download_type];
$folder_item_name 	 = $folder_item[indice_download_name];
$folder_item_caption 	 = $folder_item[indice_download_caption];
$folder_item_description = $folder_item[indice_download_description];
$folder_item_params 	 = $folder_item[indice_download_params];
$folder_item_auth_read 	 = $folder_item[indice_download_auth_read];
$folder_item_auth_write	 = $folder_item[indice_download_auth_write];
$folder_item_ctime 	 = $folder_item[indice_download_ctime];
$folder_item_hits 	 = $folder_item[indice_download_hits];

$script_name = $_SERVER['SCRIPT_NAME'];
$resource_link = "$script_name?resource_type=$folder_item_type&amp;resource_id=$folder_item_name";

echo "<dl>";
show_download_item_info($folder_item,&$fulltree,$resource_link);

echo "<dd class=\"dm_taskbar\">\n";

if ($admin_mode)
{
	$admin_link = "manage_download.php?resource_type=$folder_item_type&amp;resource_id=$folder_item_name";
?>
<ul>
<li><a href="<?php echo $admin_link; ?>&amp;admin_action=new_file">Modifica info</a></li>
<!-- <li><a href="<?php echo $admin_link; ?>&amp;admin_action=edit_data">Cancella link</a></li> -->
<!-- <li><a href="<?php echo $admin_link; ?>&amp;admin_action=new_folder">Ricarica link</a></li> -->
</ul>
<?php
} // end if ($admin_mode)
else
{
?>
<ul>
	<li>
		<a href="download.php?resource_type=link&amp;resource_id=<?php echo $folder_item_name; ?>">Visualizza</a>
	</li>
</ul>
<?php
} // end if ($admin_mode)
?>
</dd>
</dl>

<?php
} // end function show_download_item_link($folder_item)




//////////////////////////////////////////////////////////////////////////////////////
function show_download_item_info($folder_item,&$fulltree,$resource_link)
{

# dichiara variabili
extract(indici());

$folder_item_type 	 = $folder_item[indice_download_type];
$folder_item_name 	 = $folder_item[indice_download_name];
$folder_item_caption 	 = $folder_item[indice_download_caption];
$folder_item_description = $folder_item[indice_download_description];
$folder_item_params 	 = $folder_item[indice_download_params];
$folder_item_auth_read 	 = $folder_item[indice_download_auth_read];
$folder_item_auth_write	 = $folder_item[indice_download_auth_write];
$folder_item_ctime 	 = $folder_item[indice_download_ctime];
$folder_item_hits 	 = $folder_item[indice_download_hits];

$download_item_info = get_resource_fullpath($fulltree,$folder_item_type,$folder_item_name);

$resource_fullname = $download_item_info['fullname'];	// nome completo del file

$last_modify_date = $folder_item_ctime;

if ($folder_item_type === 'folder')
{
	$resource_icon = "folder.png";
}
else
{
	$resource_info = get_filename_mimetype($resource_fullname);
	$resource_icon = $resource_info['icon'];
}

?>

<!-- <?php echo $folder_item_type; ?> <?php echo $folder_item_name; ?>-->
<dt>
<a class="dm_icon" href="<?php echo $resource_link; ?>">
	<img src="<?php echo $site_abs_path; ?>images/filetype_icons/<?php echo $resource_icon; ?>" alt="<?php echo $folder_item_type; ?> icon">
</a>
<a class="dm_name" href="<?php echo $resource_link; ?>">
	<?php echo $folder_item_caption; ?>
</a>
</dt>
<dd class="dm_date">
<?php echo $last_modify_date; ?>
</dd>
<dd class="dm_description">
<p><?php echo $folder_item_description; ?></p>	</dd>

<dd class="dm_counter">
Hits: <?php echo $folder_item_hits; ?>
</dd>

<?php

} // function show_download_item_info($folder_item,&$fulltree)






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

// aggiungi un item folder fittizio, corrispondente al folder_root
if ($level == 0)
{
	$resource_type		= 'folder';
	$resource_name		= 'folder_root';
	$resource_caption	= 'Sezione download';
	$resource_description 	= 'File disponibili per il download, organizzati in una struttura ad albero';
	$resource_params 	= '';
	$resource_auth_read	= '';
	$resource_auth_write 	= 'admin';
	$resource_ctime 	= '0';
	$resource_hits 	 	= '0';
	
	$tree['data_folder'] = Array(
		indice_download_type 		=> $resource_type,
		indice_download_name 		=> $resource_name,
		indice_download_caption 	=> $resource_caption,
		indice_download_description 	=> $resource_description,
		indice_download_params 		=> $resource_params,
		indice_download_auth_read 	=> $resource_auth_read,
		indice_download_auth_write 	=> $resource_auth_write,
		indice_download_ctime 		=> $resource_ctime,
		indice_download_hits 	 	=> $resource_hits
		);
}

} // end function add_download_folder($tree,$config_download);


////////////////////////////////////////////////////////////////////////////////////////
function extract_download_item($tree,$resource_type,$resource_id,$level)
{
$download_item_struct = Array();

// path dell'elemento
if (count($level)==0)
{
	if ( ($resource_type == 'folder') && ($resource_id == 'folder_root') )
	{
		$download_item_struct = Array(
			'resource_path' => '/',
			'resource_data' => Array(),
			'resource_parent' => ''
			);
		return $download_item_struct;
	}
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

foreach($folders as $folder_item_id => $folder_item_name)
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

} // end extract_download_item($tree,$resource_type,$resource_id,$level)



////////////////////////////////////////////////////////////////////////////////////////
function get_resource_fullpath(&$tree,$resource_type,$resource_id) {

# dichiara variabili
extract(indici());

$download_resource_info = get_download_resource_info($tree,$resource_type,$resource_id);

$file_path = $download_resource_info['resource_path'];
$file_abs_path = $root_path."custom/download".$file_path."/";
$file_name = $download_resource_info['resource_params'];
$fullname = $file_abs_path.$file_name;

return array('file_path' => $file_path,'file_abs_path' => $file_abs_path,'file_name' => $file_name,'fullname' => $fullname);

} // end function get_resource_fill_fullpath($tree,$resource_type,$resource_id)


////////////////////////////////////////////////////////////////////////////////////////
function download_file(&$config_download,$download_resource_info,$login,$download_mode,$admin_mode) {

# dichiara variabili
extract(indici());

$resource_path 		= $download_resource_info['resource_path'];
$resource_type 		= $download_resource_info['resource_type'];
$resource_name 		= $download_resource_info['resource_name'];
$resource_caption 	= $download_resource_info['resource_caption'];
$resource_description 	= $download_resource_info['resource_description'];
$resource_params 	= $download_resource_info['resource_params'];
$resource_auth_read 	= $download_resource_info['resource_auth_read'];
$resource_auth_write 	= $download_resource_info['resource_auth_write'];
$resource_ctime 	= $download_resource_info['resource_ctime'];
$resource_hits 	 	= $download_resource_info['resource_hits'];

// il gruppo dell'utente autenticato puo' scaricare il contenuto?
$download_error_msg = '';
if (!group_match($login['username'],$login['usergroups'],split(',',$resource_auth_read)))
{
	$download_error_msg = "L'utente &quot;{$login['username']}&quot; not &egrave; abilitato a scaricare la risorsa $resource_name.";
}

// individua il full path del file
$file_name = $resource_params;
$fullname = $root_path."custom/download".$resource_path."/".$file_name;

// nel log inserisci anche la data di caricamento del file
$_SERVER['QUERY_STRING'] .= "($resource_ctime)";

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
	
	if (!$admin_mode)
	{
		// aggiorna contatore
		increment_download_counter($config_download,$download_resource_info);
	}
}

} // end function download_file($download_resource_info,$fullname,$login,$download_mode)


////////////////////////////////////////////////////////////////////////////////////////
function increment_download_counter(&$config_download,$download_resource_info) {

# dichiara variabili
extract(indici());

// incrementa contatore in $fulltree
$download_item[indice_download_type] 		= $download_resource_info['resource_type'];
$download_item[indice_download_name] 		= $download_resource_info['resource_name'];
$download_item[indice_download_caption] 	= $download_resource_info['resource_caption'];
$download_item[indice_download_description] 	= $download_resource_info['resource_description'];
$download_item[indice_download_params] 		= $download_resource_info['resource_params'];
$download_item[indice_download_auth_read] 	= $download_resource_info['resource_auth_read'];
$download_item[indice_download_auth_write] 	= $download_resource_info['resource_auth_write'];
$download_item[indice_download_ctime] 		= $download_resource_info['resource_ctime'];
$download_item[indice_download_hits] 		= $download_resource_info['resource_hits'];

$resource_parent_name = $download_resource_info['resource_parent'][indice_download_name];

// modifica dei dati dell'item:
$download_item[indice_download_hits] 		= $download_item[indice_download_hits]+1;

// elenco degli indici che individuano complessivamente un item unico
$unique_ids = Array(indice_download_type,indice_download_name);
modify_config_file($config_download,$resource_parent_name,$download_item,$unique_ids);

// save_config_file($filename_download.".bak",$config_download);
save_config_file($filename_download,$config_download);

}



////////////////////////////////////////////////////////////////////////////////////////
function download_link(&$config_download,$download_resource_info,$login,$admin_mode) {

$resource_path 		= $download_resource_info['resource_type'];
$resource_name 		= $download_resource_info['resource_name'];
$resource_caption 	= $download_resource_info['resource_caption'];
$resource_description 	= $download_resource_info['resource_description'];
$resource_params 	= $download_resource_info['resource_params'];
$resource_auth_read 	= $download_resource_info['resource_auth_read'];
$resource_auth_write 	= $download_resource_info['resource_auth_write'];
$resource_ctime 	= $download_resource_info['resource_ctime'];
$resource_hits 		= $download_resource_info['resource_hits'];

// il gruppo dell'utente autenticato puo' scaricare il contenuto?
$download_error_msg = '';
if (!group_match($login['username'],$login['usergroups'],split(',',$resource_auth_read)))
{
	$download_error_msg = "L'utente &quot;{$login['username']}&quot; not &egrave; abilitato a scaricare la risorsa $resource_name.";
}

$_SERVER['QUERY_STRING'] .= "($resource_ctime)"; // nel log inserisci anche la data di caricamento del file

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
	
	// redirect del browser al nuovo link
	header("Location: $fullname");
	
	if (!$admin_mode)
	{
		// aggiorna contatore
		increment_download_counter($config_download,$download_resource_info);
	}
}


} // end function download_link($download_resource_info,$fullname,$login)


////////////////////////////////////////////////////////////////////////////////////////
function get_filename_mimetype($fullname) {

$lista_mime_types = Array(
	'HTM' 	=> Array('text/html','html.png'),
	'HTML' 	=> Array('text/html','html.png'),
	'TXT' 	=> Array('text/plain','txt.png'),
	'PDF' 	=> Array('application/pdf','pdf.png'),
	'DOC' 	=> Array('application/msword','doc.png'),
	'XLS' 	=> Array('application/vnd.ms-excel','xls.png'),
	'SWF' 	=> Array('application/x-shockwave-flash','flash.png'),
	'JPG' 	=> Array('image/jpeg','image.png'),
	'JPEG' 	=> Array('image/jpeg','image.png'),
	'GIF' 	=> Array('image/gif','image.png'),
	'WAV' 	=> Array('audio/x-wav','audio.png'),
	'MP3' 	=> Array('audio/x-mp3','audio.png'),
	'MPEG' 	=> Array('audio/mpeg','video.png'),
	'QT' 	=> Array('video/quicktime','video.png'));

$index_mimetype = 0;
$index_icon 	= 1;

$p = explode('/', $fullname);
$pc = count($p);
$filename = $p[$pc-1];

$p = explode('.', $fullname);
$pc = count($p);
$ext = strtoupper($p[$pc-1]);

if (isset($lista_mime_types[$ext]))
{
	$mimetype 	= $lista_mime_types[$ext][$index_mimetype];
	$icon 		= $lista_mime_types[$ext][$index_icon];
}
else
{	
	$mimetype 	= '';
	$icon 		= 'generic.png';
}

$result = Array('filename' => $filename, 'mimetype' => $mimetype, 'icon' => $icon);
return $result;

} // end function get_filename_mimetype($fullname)


////////////////////////////////////////////////////////////////////////////////////////
function send_download_headers($fullname,$download_mode) {

$result = get_filename_mimetype($fullname);
$filename 	= $result['filename'];
$mimetype 	= $result['mimetype'];
$icon 		= $result['icon'];

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

# dichiara variabili
extract(indici());

echo $homepage_link;

?>

</body>
</html>

<?php
} // end function show_download_footer()


////////////////////////////////////////////////////////////////////////////////////////
function get_download_resource_info($tree,$resource_type,$resource_id) {

if ( ($resource_type == 'folder') && ($resource_id == 'folder_root') )
{
	$folder_item = $tree['data_folder'];
	
	$resource_tree		= $tree;
	$resource_path		= '';
	$resource_parent	= '';
}
else
{
	$download_item_struct = extract_download_item($tree,$resource_type,$resource_id,Array());
	
	$folder_item = $download_item_struct['resource_data']['data_folder'];
	
	$resource_tree 		= $download_item_struct['resource_data'];
	$resource_path 		= $download_item_struct['resource_path'];
	$resource_parent	= $download_item_struct['resource_parent'];
}

$resource_type 		= $folder_item[indice_download_type];
$resource_name 		= $folder_item[indice_download_name];
$resource_caption 	= $folder_item[indice_download_caption];
$resource_description 	= $folder_item[indice_download_description];
$resource_params 	= $folder_item[indice_download_params];
$resource_auth_read 	= $folder_item[indice_download_auth_read];
$resource_auth_write 	= $folder_item[indice_download_auth_write];
$resource_ctime 	= $folder_item[indice_download_ctime];
$resource_hits 		= $folder_item[indice_download_hits];

$result = array('resource_tree' => $resource_tree,
	'resource_path' 	=> $resource_path,
	'resource_parent' 	=> $resource_parent,
	'resource_type' 	=> $resource_type,
	'resource_name' 	=> $resource_name,
	'resource_caption' 	=> $resource_caption,
	'resource_description' 	=> $resource_description,
	'resource_params' 	=> $resource_params,
	'resource_auth_read' 	=> $resource_auth_read,
	'resource_auth_write' 	=> $resource_auth_write,
	'resource_ctime' 	=> $resource_ctime,
	'resource_hits' 	=> $resource_hits);

return $result;

} // end function get_download_resource_info($tree,$resource_type,$resource_id)


?>
