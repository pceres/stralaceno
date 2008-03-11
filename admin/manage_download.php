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

// azione da eseguire
$admin_action = $_REQUEST['admin_action'];
$admin_action = sanitize_user_input($admin_action,'plain_text','');


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



// carica configurazione dell'area download
$config_download = get_config_file($filename_download);

// crea struttura ad albero dell'area download
$tree = Array();
add_download_folder($tree,'folder_root',$config_download,0);

$admin_mode = true;

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


// visualizza info sulla risorsa
$resource_link = "manage_downloads?resource_type=$resource_type&amp;resource_id=$resource_name";

show_download_header();

echo "<dl>";
show_download_item_info($resource_tree['data_folder'],$tree,$resource_link);
echo "</dl>";
echo "<hr>";


// il gruppo dell'utente autenticato puo' modificare il contenuto?
if (!group_match($login['username'],$login['usergroups'],split(',',$resource_auth_write)))
{
	die("L'utente &quot;{$login['username']}&quot; non &egrave; abilitato a modificare la risorsa $resource_name.");
}


switch ($admin_action)
{
	case 'delete_item':	// elimina il componente indicato
		
		show_action_delete_item($resource_type,$resource_name,$resource_params,$config_download);
		break;
		
	case 'edit_data':	// modifica info del nuovo file da creare
		
		$download_item = $resource_tree['data_folder'];
		show_action_new_resource($download_item,$resource_parent[indice_download_type],$resource_parent[indice_download_name],$resource_parent[indice_download_params],$admin_action);
		break;
		
	case 'new_file':	// inserisci info del nuovo file da creare

		// valori di default
		$download_item[indice_download_type] 		= 'file';
		$download_item[indice_download_name] 		= 'file_';
		$download_item[indice_download_caption] 	= '';
		$download_item[indice_download_description] 	= '';
		$download_item[indice_download_params] 		= '';
		$download_item[indice_download_auth_read] 	= $resource_parent[indice_download_auth_read];
		$download_item[indice_download_auth_write] 	= $resource_parent[indice_download_auth_write];
		$download_item[indice_download_ctime]           = date('G:i d/m/Y');	// data dell'upload
		$download_item[indice_download_hits]            = 0;			// azzera contatore scaricamenti
		
		show_action_new_resource($download_item,$resource_type,$resource_name,$resource_params,$admin_action);
		break;
		
	case 'new_link':	// inserisci info del nuovo link da creare
		
		// valori di default
		$download_item[indice_download_type] 		= 'link';
		$download_item[indice_download_name] 		= 'link_';
		$download_item[indice_download_caption] 	= '';
		$download_item[indice_download_description] 	= '';
		$download_item[indice_download_params] 		= '';
		$download_item[indice_download_auth_read] 	= $resource_parent[indice_download_auth_read];
		$download_item[indice_download_auth_write] 	= $resource_parent[indice_download_auth_write];
		$download_item[indice_download_ctime]           = date('G:i d/m/Y');	// data dell'upload
		$download_item[indice_download_hits]            = 0;			// azzera contatore scaricamenti
		
		show_action_new_resource($download_item,$resource_type,$resource_name,$resource_params,$admin_action);
		break;
		
	case 'new_folder':	// inserisci info del nuovo folder da creare
		
		// valori di default
		$download_item[indice_download_type] 		= 'folder';
		$download_item[indice_download_name] 		= 'folder_';
		$download_item[indice_download_caption] 	= '';
		$download_item[indice_download_description] 	= '';
		$download_item[indice_download_params] 		= '';
		$download_item[indice_download_auth_read] 	= $resource_parent[indice_download_auth_read];
		$download_item[indice_download_auth_write] 	= $resource_parent[indice_download_auth_write];
		$download_item[indice_download_ctime]           = date('G:i d/m/Y');	// data dell'upload
		$download_item[indice_download_hits]            = 0;			// azzera contatore scaricamenti
		
		show_action_new_resource($download_item,$resource_type,$resource_name,$resource_params,$admin_action);
		break;
		
	case 'write_delete_resource':	// elimina il file/link/folder dalla configurazione dei download
		
		$downloaddir = $root_path."custom/download/";
		$fullname = $downloaddir.$resource_path."/".$resource_params;
		perform_action_delete_form_resource($downloaddir,$fullname,$download_resource_info,$login,$config_download,$filename_download,$old_admin_action);
		break;
		
	case 'write_new_resource':	// aggiungi il file/link/folder alla configurazione dei download
		
		// precedente azione
		$old_admin_action = $_REQUEST['old_admin_action'];
		$old_admin_action = sanitize_user_input($old_admin_action,'plain_text','');
		
		$downloaddir = $root_path."custom/download/";
		$fullname = $downloaddir.$resource_path."/".$resource_params;
		perform_action_edit_form_resource($downloaddir,$fullname,$download_resource_info,$login,$config_download,$filename_download,$old_admin_action);
		break;
		
	case 'update_file':	// browse locale per individuare il file locale da ricaricare
		
		show_action_upload_file($resource_type,$resource_name,$resource_params);
		break;
		
	case 'upload_file':	// scrivi il file sul server
		
		$downloaddir = $root_path."custom/download/";
		$fullname = $downloaddir.$resource_path."/".$resource_params;
		perform_action_write_form_file($downloaddir,$fullname,$download_resource_info,$login,$config_download,$filename_download);
		break;
		
	default:
		die("Azione sconosciuta ($admin_action)!");
		break;
}

$back_address = "http://localhost/work/ars/admin/manage_downloads.php?resource_type=folder&resource_id={$resource_parent[indice_download_name]}";

echo "<hr><a href=\"$back_address\">Torna al livello superiore</a>";

show_download_footer();


# logga il contatto
$counter = count_page("admin_download",array("COUNT"=>1,"LOG"=>1),$filedir_counter); # abilita il contatore, senza visualizzare le cifre, e fai il log






//////////////////////////////////////////////////////////////////////////////////////
function perform_action_write_form_file($downloaddir,$fullname,$download_resource_info,$login,$config_download,$filename_download)
{

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

// salva il file nel filesystem remoto
$upload_result = write_form_file(Array('userfile'=>$fullname));

$result = show_action_write_form_file($upload_result,$downloaddir);
if ($result)	// tutti i file sono stato caricati correttamente
{
	// log
	log_action($downloaddir,$_SERVER['REMOTE_ADDR'].",".$fullname.", " . date("l dS of F Y h:i:s A").",".$login['username']);

	// aggiorna il file di configurazione
	$download_item = $resource_tree['data_folder'];
	$download_item[indice_download_ctime] = date('G:i d/m/Y');	// data dell'upload
	$download_item[indice_download_params] = $resource_params;	// nome del file
	// $download_item[indice_download_hits] = 0;			// azzera il contatore degli scaricamenti
	
	// elenco degli indici che individuano complessivamente un item unico
	$unique_ids = Array(indice_download_type,indice_download_name);
	$resource_parent_name = $resource_parent[indice_download_name];
	
	$result = modify_config_file($config_download,$resource_parent_name,$download_item,$unique_ids,Array());
	save_config_file($filename_download,$config_download);
	
	
	if ($result['status'] === 'item_modified')
	{
		// prepara i dati per il log dei nuovi contenuti
		$link = "download.php?resource_type=folder&amp;resource_id={$resource_parent[indice_download_name]}";
		$item['title'] 		= 'Aggiornamento del file: '.$resource_caption;
		$item['description'] 	= $resource_description;
		$item['link'] 		= $link;
		$item['guid'] 		= $link;
		$item['category'] 	= "download";
		
		$upload_date = date('d/m/Y H.i.s');
		$date_unix = substr($upload_date,3,2)."/".substr($upload_date,0,2)."/".substr($upload_date,6,4)." ".substr($upload_date,11,2).":".substr($upload_date,14,2).":".substr($upload_date,17,2);
		$item['pubDate'] 	= gmdate('D, j M Y G:i:s +0000',strtotime($date_unix));
		$item['author'] 	= $login['username'];
		$item['username']	= $login['username'];
		$item['read_allowed']	= $resource_auth_read;	// everyone allowed to see the feed
		
		log_new_content('download',$item);
	}
}

} // end perform_action_write_form_file($downloaddir,$fullname,$download_resource_info,$login)



//////////////////////////////////////////////////////////////////////////////////////
function perform_action_edit_form_resource($downloaddir,$fullname,$download_resource_info,$login,$config_download,$filename_download,$old_admin_action)
{

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

// recupera i dati del form
$new_resource_type              = $_REQUEST['new_resource_type'];
$new_resource_type              = sanitize_user_input($new_resource_type,'plain_text','');

$new_resource_name              = $_REQUEST['new_resource_name'];
$new_resource_name              = sanitize_user_input($new_resource_name,'plain_text','');

$new_resource_caption           = $_REQUEST['new_resource_caption'];
$new_resource_caption           = sanitize_user_input($new_resource_caption,'plain_text','');

$new_resource_description       = $_REQUEST['new_resource_description'];
$new_resource_description       = sanitize_user_input($new_resource_description,'plain_text','');

$new_resource_params            = $_REQUEST['new_resource_params'];
$new_resource_params            = sanitize_user_input($new_resource_params,'plain_text','');

$new_resource_auth_read         = $_REQUEST['new_resource_auth_read'];
$new_resource_auth_read         = sanitize_user_input($new_resource_auth_read,'plain_text','');

$new_resource_auth_write        = $_REQUEST['new_resource_auth_write'];
$new_resource_auth_write        = sanitize_user_input($new_resource_auth_write,'plain_text','');

// $new_resource_ctime             = $_REQUEST['new_resource_ctime'];
// $new_resource_ctime             = sanitize_user_input($new_resource_ctime,'plain_text','');
// 
// $new_resource_hits              = $_REQUEST['new_resource_hits'];
// $new_resource_hits              = sanitize_user_input($new_resource_hits,'plain_text','');


// log
log_action($downloaddir,$_SERVER['REMOTE_ADDR'].",".$fullname.", " . date("l dS of F Y h:i:s A").",".$login['username']);


// aggiorna il file di configurazione
$download_item[indice_download_type] 		= $new_resource_type;
$download_item[indice_download_name] 		= $new_resource_name;
$download_item[indice_download_caption] 	= $new_resource_caption;
$download_item[indice_download_description] 	= $new_resource_description;
$download_item[indice_download_params] 		= $new_resource_params;
$download_item[indice_download_auth_read] 	= $new_resource_auth_read;
$download_item[indice_download_auth_write] 	= $new_resource_auth_write;
$download_item[indice_download_ctime]           = date('G:i d/m/Y');	// data dell'upload
$download_item[indice_download_hits]            = 0;			// azzera il contatore degli scaricamenti

// elenco degli indici che individuano complessivamente un item unico
$unique_ids = Array(indice_download_type,indice_download_name);
$resource_parent_name = $resource_name;

echo "Creazione entry per $new_resource_type $new_resource_name: <br>";

$result = modify_config_file($config_download,$resource_parent_name,$download_item,$unique_ids,Array('allow_add_item'));

if ($result['status'] !== 'item_unchanged')
{
	// se si crea un nuovo folder bisogna creare un nuovo blocco
	if ( ($new_resource_type === 'folder') || ($new_resource_type === 'file') )
	{
		$new_folder = "$fullname/$new_resource_params"; // nome della cartella o file da creare
		
		if ($old_admin_action == 'new_folder')
		{
			if (!array_key_exists($new_resource_name,$config_download))
			{
				$config_download[$new_resource_name] = Array();
				mkdir($new_folder);
			}
			else
			{
				echo("Attenzione! Il folder $new_resource_name gi&agrave; esiste: cambia nome.<br>");
				return;
			}
		}
		elseif ($old_admin_action == 'edit_data')
		{
			$old_foldername = $result['old_item'][indice_download_params];
			$old_folder = "$fullname/$old_foldername"; // nome della cartella da rinominare
			if ($old_foldername !== $new_resource_params)
			{
				if (preg_match("/([\.]{2}|[\\\\\/])/",$new_resource_params,$z))
				{
					echo("Caratteri non consentiti nel nome ($new_resource_params)!");
					return;
				}
				
				$res = rename($old_folder, $new_folder);
				if ($res)
				{
					echo("Folder cambiato: $old_folder ==> $new_folder<br>");
				}
			}
		}
	}
	
	save_config_file($filename_download,$config_download);
	
	echo "&nbsp;&nbsp;&nbsp;Fatto.<br><br>";
	
	if ($old_admin_action == 'edit_data')
	{
		$action_tag = 'Modifica';
	}
	else
	{
		$action_tag = 'Nuovo';
	}
	
	// prepara i dati per il log dei nuovi contenuti
	$link = "download.php?resource_type=folder&amp;resource_id=$resource_name";
	$item['title'] 		= "$action_tag $new_resource_type: $new_resource_caption";
	$item['description'] 	= $new_resource_description;
	$item['link'] 		= $link;
	$item['guid'] 		= $link;
	$item['category'] 	= "download";
	
	$upload_date = date('d/m/Y H.i.s');
	$date_unix = substr($upload_date,3,2)."/".substr($upload_date,0,2)."/".substr($upload_date,6,4)." ".substr($upload_date,11,2).":".substr($upload_date,14,2).":".substr($upload_date,17,2);
	$item['pubDate'] 	= gmdate('D, j M Y G:i:s +0000',strtotime($date_unix));
	$item['author'] 	= $login['username'];
	$item['username']	= $login['username'];
	$item['read_allowed']	= $new_resource_auth_read;	// everyone allowed to see the feed
	
	log_new_content('download',$item);
}
else
{
	echo "&nbsp;&nbsp;&nbsp;il $new_resource_type &egrave; gi&agrave; stato creato!<br><br>";
}

} // end perform_action_edit_form_resource($downloaddir,$fullname,$download_resource_info,$login)



//////////////////////////////////////////////////////////////////////////////////////
function perform_action_delete_form_resource($downloaddir,$fullname,$download_resource_info,$login,$config_download,$filename_download)
{

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


// log
log_action($downloaddir,$_SERVER['REMOTE_ADDR'].",".$fullname.", " . date("l dS of F Y h:i:s A").",".$login['username']);

// elenco degli indici che individuano complessivamente un item unico
$unique_ids = Array(indice_download_type,indice_download_name);
$resource_parent_name = $resource_parent[indice_download_name];

$download_item[indice_download_type] = $resource_type;
$download_item[indice_download_name] = $resource_name;

echo "Cancellazione $resource_type $resource_name: <br>";

$result = modify_config_file($config_download,$resource_parent_name,$download_item,$unique_ids,Array('delete_item'));

// var_dump(Array($resource_parent_name,$download_item,$unique_ids));
// var_dump($fullname);

if ($result['status'] == 'item_deleted')
{
	
	// se si elimina un folder bisogna eliminare anche il blocco
	if ($resource_type == 'folder')
	{
		if (array_key_exists($resource_name,$config_download))
		{
			unset($config_download[$resource_name]);
			if (!rmdir($fullname))
			{
				echo("Attenzione! Non ho potuto eliminare il folder $fullname!<br>");
				return;
			}
		}
		else
		{
			echo("Attenzione! Il folder $resource_name non esiste!<br>");
			return;
		}
	}
	elseif ($resource_type == 'file')
	{
		if (!file_exists($fullname))
		{
			echo("Il file $fullname non esisteva!<br>\n");
		}
		else
		{
			if (!unlink($fullname))
			{
				echo("Attenzione! Non ho potuto eliminare il file $fullname!<br>");
				return;
			}
		}
	}
	
	save_config_file($filename_download,$config_download);
	
	echo "&nbsp;&nbsp;&nbsp;Fatto.<br><br>";
	
	// prepara i dati per il log dei nuovi contenuti
	$link = "download.php?resource_type=folder&amp;resource_id=$resource_parent_name";
	$item['title'] 		= "Cancellazione $resource_type: $resource_caption";
	$item['description'] 	= $resource_description;
	$item['link'] 		= $link;
	$item['guid'] 		= $link;
	$item['category'] 	= "download";
	
	$upload_date = date('d/m/Y H.i.s');
	$date_unix = substr($upload_date,3,2)."/".substr($upload_date,0,2)."/".substr($upload_date,6,4)." ".substr($upload_date,11,2).":".substr($upload_date,14,2).":".substr($upload_date,17,2);
	$item['pubDate'] 	= gmdate('D, j M Y G:i:s +0000',strtotime($date_unix));
	$item['author'] 	= $login['username'];
	$item['username']	= $login['username'];
	$item['read_allowed']	= $resource_auth_read;	// everyone allowed to see the feed
	
	log_new_content('download',$item);
}
else
{
	echo "&nbsp;&nbsp;&nbsp;il $resource_type &egrave; gi&agrave; stato cancellato!<br><br>";
}

} // end perform_action_delete_form_resource($downloaddir,$fullname,$download_resource_info,$login)



//////////////////////////////////////////////////////////////////////////////////////
function show_action_new_resource($new_resource_data,$resource_type,$resource_name,$resource_params,$old_admin_action)
{

// valori da mostrare inizialmente
$new_resource_type          = $new_resource_data[indice_download_type];
$new_resource_name          = $new_resource_data[indice_download_name];
$new_resource_caption       = $new_resource_data[indice_download_caption];
$new_resource_description   = $new_resource_data[indice_download_description];
$new_resource_params        = $new_resource_data[indice_download_params];
$new_resource_auth_read     = $new_resource_data[indice_download_auth_read];
$new_resource_auth_write    = $new_resource_data[indice_download_auth_write];
$new_resource_ctime         = $new_resource_data[indice_download_ctime];
$new_resource_hits          = $new_resource_data[indice_download_hits];

// stringa associata al campo params
switch ($new_resource_type)
{
	case 'file':
		$params_tag = 'nome del file';
		break;
	case 'link': 
		$params_tag = 'URL del link';
		break;
	case 'folder':
		$params_tag = 'nome della cartella';
		break;
}


?>
<form enctype="multipart/form-data" action="manage_download.php" method="post">
<input type="hidden" name="resource_type" value="<?php echo $resource_type ?>">
<input type="hidden" name="resource_id" value="<?php echo $resource_name ?>">
<input type="hidden" name="admin_action" value="write_new_resource">
<input type="hidden" name="old_admin_action" value="<?php echo $old_admin_action ?>">

Dati del nuovo <?php echo $new_resource_type; ?>:

<input type="hidden" name="new_resource_type" value="<?php echo $new_resource_type ?>">

<?php
if ($old_admin_action == 'edit_data')
{
	$ks_input = 'name="dummy_new_resource_name" disabled';
?>

<input type="hidden" name="new_resource_name" value="<?php echo $new_resource_name ?>">

<?php
}
else
{
	$ks_input = 'name="new_resource_name"';
}
?>

<!-- <input type="hidden" name="new_resource_ctime" value="<?php echo $new_resource_ctime ?>"> -->
<!-- <input type="hidden" name="new_resource_hits" value="<?php echo $new_resource_hits ?>"> -->
<input type="hidden" name="new_resource_params" value="">

<table><tbody>

<tr><td>
nome</td><td><input type="text" <?php echo $ks_input ?> value="<?php echo htmlspecialchars($new_resource_name) ?>" size=30 >
</td></tr>

<tr><td>
titolo</td><td><input type="text" name="new_resource_caption" value="<?php echo htmlspecialchars($new_resource_caption) ?>" size=60>
</td></tr>

<tr><td>
descrizione</td><td><input type="text" name="new_resource_description" value="<?php echo htmlspecialchars($new_resource_description) ?>" size=120>
</td></tr>

<tr><td>
<?php echo $params_tag; ?></td><td><input type="text" name="new_resource_params" value="<?php echo htmlspecialchars($new_resource_params) ?>" size=60>
</td></tr>

<tr><td>
auth_read</td><td><input type="text" name="new_resource_auth_read" value="<?php echo htmlspecialchars($new_resource_auth_read) ?>" size=30>
</td></tr>

<tr><td>
auth_write</td><td><input type="text" name="new_resource_auth_write" value="<?php echo htmlspecialchars($new_resource_auth_write) ?>" size=30>
</td></tr>

</tbody></table>

<input type="submit" value="Conferma dati <?php echo $new_resource_type; ?>">
</form>
<?php 

} // end function show_action_new_resource($new_resource_type,$resource_type,$resource_name,$resource_params)


//////////////////////////////////////////////////////////////////////////////////////
function show_action_delete_item($resource_type,$resource_name,$resource_params,$config_download)
{

if (count($config_download[$resource_name]) > 0)
{
	echo("La cartella $resource_name non &egrave; vuota, contiene le seguenti risorse: <br>\n");
	echo "<ul>\n";
	foreach ($config_download[$resource_name] as $id => $download_item)
	{
		echo "<li>{$download_item[indice_download_name]}</li>\n";
	}
	echo "</ul><br>\n";
	echo("Devi svuotarla prima di poterla cancellare!");
	return;
}

?>

Sei sicuro di voler cancellare il <?php echo "$resource_type $resource_name ($resource_params)" ?>?

<form enctype="multipart/form-data" action="manage_download.php" method="post">
<input type="hidden" name="resource_type" value="<?php echo $resource_type ?>">
<input type="hidden" name="resource_id" value="<?php echo $resource_name ?>">
<input type="hidden" name="admin_action" value="write_delete_resource">

<input type="submit" value="Conferma cancellazione <?php echo $new_resource_type; ?>">
</form>
<?php 

} // end function show_action_delete_item($resource_type,$resource_name,$resource_params)


//////////////////////////////////////////////////////////////////////////////////////
function show_action_upload_file($resource_type,$resource_name,$resource_params)
{

?>
<form enctype="multipart/form-data" action="manage_download.php" method="post">
<input type="hidden" name="MAX_FILE_SIZE" value="300000">
<input type="hidden" name="filename" value="<?php echo $resource_name ?>">
<input type="hidden" name="resource_type" value="<?php echo $resource_type ?>">
<input type="hidden" name="resource_id" value="<?php echo $resource_name ?>">
<input type="hidden" name="admin_action" value="upload_file">
Nuovo file da caricare come "<?php echo $resource_params ?>": <input name="userfile" type="file">
<input type="submit" value="Invia File">
</form>
<?php 

} // end function show_action_upload_file($resource_type,$resource_name,$resource_params)


//////////////////////////////////////////////////////////////////////////////////////
function show_action_write_form_file($upload_result,$log_folder)
{


show_download_header;

$MAX_FILE_SIZE = $_REQUEST['MAX_FILE_SIZE'];

$result = 0;


foreach ($upload_result as $_FILE)
{
	$filename = $_FILE['name'];
	$tempfile = $_FILE['tmp_name'];
	$errno = $_FILE['error'];
	$upload_ok = $_FILE['upload_ok'];
	$fullname = $_FILE['fullname'];
	
	echo "Caricamento di $filename: <br><br>";
	
	if ($errno == UPLOAD_ERR_OK)
	{
		if ($upload_ok)
		{
			print "il file $fullname &egrave; stato caricato con successo.<br>\n";
			$result = 1;
		}
		else
		{
			echo("ATTENZIONE! Problema nell'upload del file.");
		}
	}
	else
	{
		print "Errore nell'upload del file:\n"; 
		
		switch ($errno) 
		{
			case UPLOAD_ERR_INI_SIZE :
			case UPLOAD_ERR_FORM_SIZE :
				echo("ATTENZIONE! File troppo grande! La dimensione massima e' di $MAX_FILE_SIZE kBytes\n");
				break;
			case UPLOAD_ERR_PARTIAL :
				echo("ATTENZIONE! Upload eseguito parzialmente!\n"); 
				break;
			case UPLOAD_ERR_NO_FILE :
				echo("ATTENZIONE! Nessun file &egrave; stato inviato!<br><br>\n"); 
				print "\nAlcune informazioni:\n\n";
				print_r($_FILE);
				break;
			default:
				echo("ATTENZIONE! Unknown error type: [$errno]<br><br>\n");
				print "\nAlcune informazioni:\n\n";
				print_r($_FILE);
		}
	}
}

return $result;

} // end function show_action_write_form_file($upload_result,$log_folder)


//////////////////////////////////////////////////////////////////////////////////////
function write_form_file($fullname_in)
{
/*
salva nel filesystem i file passati da form hTML

$fullname_in: 	array con i nomi dei file di destinazione (full path), 
		oppure array con un unico elemento, il folder di destinazione
*/

if (!is_array($fullname_in))
{
	die("L'input deve essere un array!");
}

$list_files = $_FILES;

foreach ($_FILES as $file_id => $_FILE)
{
	$file_data = $_FILE;
	
	$filename = $_FILE['name'];
	$tempfile = $_FILE['tmp_name'];
	$errno = $_FILE['error'];
	
	if (!empty($filename))
	{
		if (count($fullname_in) == count($_FILES))
		{
			if (array_key_exists($file_id,$fullname_in))
			{
				$fullname = $fullname_in[$file_id];
			}
			else
			{
				die("Il file con nome $file_id non e' stato indicato!");
			}
			
		}
		elseif (count($fullname_in) == 1)
		{
			$fullname = $fullname_in[0].$filename;
		}
		else
		{
			die("Problema 1 nell'upload, contattare l'amministratore.");
		}
		
		if (move_uploaded_file($tempfile, $fullname))
		{
			$file_data['upload_ok'] = true;
		}
		else
		{
			$file_data['upload_ok'] = false;
		}
		
		$file_data['fullname'] = $fullname;
		
	}
	else
	{
		die("Problema 2 nell'upload, contattare l'amministratore.");
	}
	
	$list_files[$file_id] = $file_data;
}

return $list_files;

} // end function write_form_file($fullname_in)

?>
