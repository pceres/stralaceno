<?php

require_once('../libreria.php');

# dichiara variabili
extract(indici());

/*
questa libreria esamina i cookies o i parametri http (eventualmente) inviati, e genera l'array $login con i campi
'username',	: login
'usergroups',	: lista dei gruppi di appartenenza (separati da virgola)
'status',		: stato del login: 'none','ok_form','ok_cookie','error_wrong_username','error_wrong_userpass','error_wrong_challenge','error_wrong_IP'
*/
require_once('../login.php');


// verifica che si stia arrivando a questa pagina da quella amministrativa principale
if (
    !isset($_SERVER['HTTP_REFERER']) // referer not set ...
    | (strlen(strpos(substr($_SERVER['HTTP_REFERER'] ,0,strrpos($_SERVER['HTTP_REFERER'] ,'/')+1),"://".$_SERVER['HTTP_HOST'].$script_abs_path."admin/").' ') == 1) // ...or (referer ~= link_from_admin_pages)
    | (!in_array($login['status'],array('ok_form','ok_cookie'))) // ...or login_was_not_successful
   )
{
	header("Location: ".$script_abs_path."index.php");
	exit();
}

// verifica che l'utente sia autorizzato per l'operazione richiesta
$res = check_auth('admin_modules',"",$login['username'],$login['usergroups'],false);
if (!$res)
{
	die("Mi dispiace, non sei autorizzato!");
}

// indici nel file di config. modules_config.php, relativi ad [elenco_moduli]
$indice_module_name = 0;	// nome del modulo (e della cartella in $modules_dir
$indice_module_caption = 1;	// descrizione del modulo



// carica le infomazioni relative a ciascun modulo
$modules_list = get_cfgfile_data($config_dir."modules_config.php");	// elenco dei moduli e file e relativa descrizione

// carica le infomazioni relative a ciascun file di configurazione
$cfgfile_list = get_cfgfile_data($filename_cfgfile);	// elenco dei file di configurazione, e relativi dati


// Esamina i moduli in $modules_dir
$module_cfg_list = array();
if (is_dir($modules_dir))
{
	if ($dh = opendir($modules_dir)) 
	{
		while (($module = readdir($dh)) !== false) 
		{
			if ( (filetype($modules_dir . $module) == "dir") and !in_array($module,array('.','..','CVS')) )
			{
				// modulo trovato, cerca i file di configurazione:
				$module_dir = $modules_dir.$module."/";
				if ($dh2 = opendir($module_dir))
				{
					while (($file = readdir($dh2)) !== false) 
					{
						if ( (filetype($module_dir . $file) == "file") and (substr($file,-8,5) == '_cfg.') )
						{
 							if (array_key_exists($module,$module_cfg_list))
							{
								$item = $module_cfg_list[$module];
							}
							else
							{
								$item = array();
							}
							array_push($item,$file);
							$module_cfg_list[$module] = $item;
						}
					}
					closedir($dh2);
				}
			}
		}
		closedir($dh);
	}
}


// visualizza la pagina
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 TRANSITIONAL//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>

<head>
  <title>Gestione moduli</title>
  <meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1">
  <meta name="GENERATOR" content="Kate">
  <style type="text/css">@import "<?php echo $filename_css ?>";</style>
</head>

<body class="admin">

<script type="text/javaScript" src="<?php echo $site_abs_path ?>admin/md5.js"></script>



<div class="titolo_tabella">Gestione dei moduli</div>

<?php



// gestione dei singoli moduli
foreach ($module_cfg_list as $modulo => $cfg_list) // itera su tutti i moduli trovati nella cartella $modules_dir
{
	$module_caption = $modules_list[$modulo][$indice_module_caption];	// descrizione del modulo
	
	$cfgfile_data = $cfgfile_list[$cfg_list[0]];	// dati del primo file di configurazione del modulo
	if ( (count($cfg_list) == 1) and empty($cfgfile_data[$indice_cfgfile_caption])) // se c'e' un solo file, e la descriz. e' vuota...
	{
		// un solo file di cfg., e relativa caption vuota: link semplice con caption del modulo:
		
		$filename = $cfgfile_data[$indice_cfgfile_name];
		$caption = $module_caption;	// descrizione del modulo (non del singolo file, essendo quest'ultima vuota)
		
		echo "<a href=\"manage_config_file.php?config_file=$filename\">$caption</a>";
		echo "<hr>";
	}
	else
	{
		// titolo con caption del modulo, poi elenco di file di cfg con relativa caption:
		
		echo "<div class=\"titolo_colonna\">".$module_caption."</div>\n";
		
		echo "<ul>\n";
		foreach ($cfg_list as $filename) // per ogni file trovato
		{
			$cfgfile_data = $cfgfile_list[$filename];
			$caption = $cfgfile_data[$indice_cfgfile_caption];
			
			echo "<li><a class=\"txt_link\" href=\"manage_config_file.php?config_file=$filename\">$caption</a>";
		}
		echo "</ul>\n";
		echo "<hr>\n";
	}
}

?>

<div align="right"><a href="index.php" class="txt_link">Torna alla pagina amministrativa principale</a></div>

<?php
echo $homepage_link;


# logga il contatto
$counter = count_page("admin_modules_config",array("COUNT"=>1,"LOG"=>1),$filedir_counter); # abilita il contatore, senza visualizzare le cifre, e fai il log


?>

</body>
</html>
