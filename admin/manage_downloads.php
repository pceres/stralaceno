<?php

require_once('../libreria.php');

# dichiara variabili
extract(indici());

require_once('../download_lib.php');
require_once('../login.php');		// gestione autenticazione


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

if (empty($resource_id))
{
	$resource_type		= 'folder';
	$resource_id		= 'folder_root';
}



// verifica che l'utente sia autorizzato per l'operazione richiesta
$res = check_auth('gestione_download',"",$login['username'],$login['usergroups'],false);
if (!$res)
{
	die("Mi dispiace, non sei autorizzato!");
}





// siamo in modalita' admin
$admin_mode = true;

// carica configurazione dell'area download
$config_download = get_config_file($filename_download);

// crea struttura ad albero dell'area download
$tree = Array();
add_download_folder($tree,'folder_root',$config_download,0);

if (empty($resource_id))
{
	$resource_type		= 'folder';
	$resource_id		= 'folder_root';
}

$download_resource_info = get_download_resource_info($tree,$resource_type,$resource_id);

$resource_tree 		= $download_resource_info['resource_tree'];
$resource_path 		= $download_resource_info['resource_path'];
$resource_parent 	= $download_resource_info['resource_parent'];
$resource_type 		= $download_resource_info['resource_type'];
$resource_name 		= $download_resource_info['resource_name'];
$resource_caption 	= $download_resource_info['resource_caption'];
$resource_description 	= $download_resource_info['resource_description'];
$resource_params 	= $download_resource_info['resource_params'];
$resource_auth_read 	= $download_resource_info['resource_auth_read'];
$resource_auth_write 	= $download_resource_info['resource_auth_write'];
$resource_ctime 	= $download_resource_info['resource_ctime'];
$resource_hits 	 	= $download_resource_info['resource_hits'];

switch ($resource_type)
{
case 'folder':
	// visualizza struttura parziale dell'albero dell'area download
	show_download_folder($config_download,$download_resource_info,$tree,$login,0,$admin_mode);
	break;
case 'file':
	// scarica il file
	download_file($config_download,$download_resource_info,$login,$download_mode,$admin_mode);
	break;
case 'link':
	download_link($config_download,$download_resource_info,$login,$admin_mode);
	break;
default:
	die("Tipo sconosciuto ($resource_type)!");
	break;
}

# logga il contatto
$counter = count_page("admin_download_browse",array("COUNT"=>1,"LOG"=>1),$filedir_counter); # abilita il contatore, senza visualizzare le cifre, e fai il log


?>
