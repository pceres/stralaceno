<?php

require_once('libreria.php');

# dichiara variabili
extract(indici());

require_once('download_lib.php');
require_once('login.php');		// gestione autenticazione


// tipo di risorsa (file o link)
$resource_type = $_REQUEST['resource_type'];
$resource_type = sanitize_user_input($resource_type,'plain_text','');

// id della risorsa (nome file o link)
$resource_id = $_REQUEST['resource_id'];
$resource_id = sanitize_user_input($resource_id,'plain_text','');

// tipo di download (inline o attachment)
$download_mode = $_REQUEST['download_mode'];
$download_mode = sanitize_user_input($download_mode,'plain_text','');

if (empty($download_mode))
{
	$download_mode = 'inline';
}


// carica configurazione dell'area download
$config_download = get_config_file($filename_download);

// crea struttura ad albero dell'area download
$tree = Array();
add_download_folder($tree,'folder_root',$config_download,0);


if (!empty($resource_id))
{
	$download_resource_info = get_download_resource_info($tree,$resource_type,$resource_id);
	// $download_resource_info = array(
	// 'resource_tree' 	=> $resource_tree,
	// 'resource_path' 	=> $resource_path,
	// 'resource_parent' 	=> $resource_parent,
	// 'resource_type' 	=> $resource_type,
	// 'resource_name' 	=> $resource_name,
	// 'resource_caption' 	=> $resource_caption,
	// 'resource_params' 	=> $resource_params,
	// 'resource_auth' 	=> $resource_auth);
	
	switch ($resource_type)
	{
	case 'folder':
		// visualizza struttura parziale dell'albero dell'area download
		$folder_sub_tree 	= $download_resource_info['resource_tree'];
		$folder_path 		= $download_resource_info['resource_path'];
		$folder_parent		= $download_resource_info['resource_parent'];
		$folder_caption 	= $download_resource_info['resource_caption'];
		$folder_auth		= $download_resource_info['resource_auth'];
		show_download_folder($folder_sub_tree,$folder_path,$folder_caption,$folder_parent,$folder_auth,$login,0);
		break;
	case 'file':
		$file_path = $download_resource_info['resource_path'];
		$file_name = $download_resource_info['resource_params'];
		$fullname = $root_path."custom/download".$file_path."/".$file_name;
		download_file($download_resource_info,$fullname,$login,$download_mode);
		break;
	case 'link':
		download_link($download_resource_info,$fullname,$login);
// 		echo "<a href=\"$resource_params\">$resource_params</a>";
		break;
	default:
		die("Tipo sconosciuto ($resource_type)!");
		break;
	}
}
else
{
	// visualizza struttura ad albero dell'area download
	show_download_folder($tree,'','Sezione download','folder_root','',$login,0);
}

?>
