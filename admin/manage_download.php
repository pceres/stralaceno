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


switch ($admin_action)
{
	case 'update_file':	// browse locale per indiciduare il file locale da ricaricare
		
		show_action_update_file($resource_type,$resource_name,$resource_params);
		break;
		
	case 'write_file':	// scrivi il file sul server
		
		$downloaddir = $root_path."custom/download/";
		$fullname = $downloaddir.$resource_path."/".$resource_params;
		perform_action_write_form_file($downloaddir,$fullname,$download_resource_info,$login,$config_download,$filename_download);
		break;
		
	default:
		die("Azione sconosciuta ($admin_action)!");
		break;
}

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
	// $download_item[indice_download_hits] = 0;			// azzera il contatore degli scaricamenti
	
	// elenco degli indici che individuano complessivamente un item unico
	$unique_ids = Array(indice_download_type,indice_download_name);
	$resource_parent_name = $resource_parent[indice_download_name];
	
	modify_config_file($config_download,$resource_parent_name,$download_item,$unique_ids);
	save_config_file($filename_download,$config_download);
	
	
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

} // end perform_action_write_form_file($downloaddir,$fullname,$download_resource_info,$login)



//////////////////////////////////////////////////////////////////////////////////////
function show_action_update_file($resource_type,$resource_name,$resource_params)
{

$name = $resource_params;

?>
<form enctype="multipart/form-data" action="manage_download.php" method="post">
<input type="hidden" name="MAX_FILE_SIZE" value="300000">
<input type="hidden" name="filename" value="<?php echo $resource_name ?>">
<input type="hidden" name="resource_type" value="<?php echo $resource_type ?>">
<input type="hidden" name="resource_id" value="<?php echo $resource_name ?>">
<input type="hidden" name="admin_action" value="write_file">
Nuovo file da caricare come "<?php echo $resource_params ?>": <input name="userfile" type="file">
<input type="submit" value="Invia File">
</form>
<?php 

} // end function show_action_update_file($resource_type,$resource_name,$resource_params)


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

} // end function show_action_update_file($resource_type,$resource_name,$resource_params)


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

} // end function show_action_update_file($resource_type,$resource_name,$resource_params)

?>
