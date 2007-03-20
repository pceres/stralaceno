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
if ( !isset($_SERVER['HTTP_REFERER']) | ("http://".$_SERVER['HTTP_HOST'].$script_abs_path."admin/" != substr($_SERVER['HTTP_REFERER'],0,strrpos($_SERVER['HTTP_REFERER'],'/')+1) ) |
(!in_array($login['status'],array('ok_form','ok_cookie'))) )
{
	header("Location: ".$script_abs_path."index.php");
	exit();
}


// carica i dati inviati dal form
$new_name = sanitize_user_input($_REQUEST['filename'],'plain_text',Array()); // path assoluto nel filename del server
$password = sanitize_user_input($_REQUEST['password'],'plain_text',Array());
$testo0 = $_REQUEST['testo'];

// verifica che l'utente sia autorizzato per l'operazione richiesta
$res = check_auth('scrivi_file_config',"$new_name",$login['username'],$login['usergroups'],false);
if (!$res)
{
	die("Mi dispiace, non sei autorizzato!");
}

// carica le infomazioni relative a ciascun file di configurazione
$elenco_cfgfile = get_cfgfile_data($filename_cfgfile); // carica il file di configurazione dei moduli

// verifica se il file indicato e' nell'elenco, e restituisci i relativi dati
$file_ok = false;
foreach ($elenco_cfgfile as $name => $config_data)
{
	$dir = template_to_effective($config_data[$indice_cfgfile_folder]);
	$caption = $config_data[$indice_cfgfile_caption];
	$groups_ok = $config_data[$indice_cfgfile_groups];
	$password_ok = $config_data[$indice_cfgfile_password];
	
	$name_test = $dir.$name;
	
	// verifica la presenza di carattere jolly "?"
	while ($pos=strpos($name_test,'?'))
	{
		$name_test[$pos]=$new_name[$pos];
	}
	
	if ($name_test === $new_name)
	{
		$file_ok = true;
		break;
	}
}

if (!$file_ok)
{
	die("Non e' consentito modificare il file $filename! Contattare il webmaster.");
}

// verifica che il gruppo di appartenenza sia abilitato qui
if (!group_match($username,split(',',$usergroups),split(',',$groups_ok)))
{
	die("Spiacente, non sei abilitato ad accedere a questa pagina! Contatta l'amministratore.<br>\n");
}


?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 TRANSITIONAL//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
  <title>Salvataggio testo</title>
  <meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1">
  <meta name="GENERATOR" content="Quanta Plus">
  <style type="text/css">@import "<?php echo $filename_css ?>";</style>
</head>
<body>


<?php

$ks1 = array("\'",'\"',"\\\\","à"       ,"è"       ,"é"       ,"ì"       ,"ò"       ,"ó"       ,"ù"       ,"°"    ,
	"À"       ,"È"       ,"É"       ,"Ì"       ,"Ò"       ,"Ó"       ,"Ù"       );
$ks2 = array("'" ,"\"","\\"  ,"&agrave;","&egrave;","&eacute;","&igrave;","&ograve;","&oacute;","&ugrave;","&deg;",
	"&Agrave;","&Egrave;","&Eacute;","&Igrave;","&Ograve;","&Oacute;","&ugrave;");
$testo = str_replace($ks1,$ks2,$testo0);


$ok = FALSE;
if ($password_ok == $password)
{
	$ok = TRUE;
}

if ($ok == TRUE) 
{
	// leggi vecchio file
	if (file_exists($new_name))
	{
		$testo_old = file($new_name);
		$confronta = 1;
	}
	else
	{
		$confronta = 0;
	}
	
	// scrivi i dati inviati su file
	if ($handle = fopen($new_name, "w"))
	{
		fwrite($handle, $testo);
		fclose($handle);
		
		// se il file non esisteva, logga solo le modifiche
		if ($confronta == 1)
		{
			// rileggi il file
			$testo = file($new_name);
			
			// trova linee cancellate
			$del = array_diff($testo_old,$testo);
			if (!empty($del))
			{
				$out1 = str_replace("\n","\r\n",print_r($del,TRUE));
			}
			
			// trova linee aggiunte
			$add = array_diff($testo,$testo_old);
			if (!empty($add))
			{
				$out2 = str_replace("\n","\r\n",print_r($add,TRUE));
			}
			
			if (empty($del) && empty($add))
			{
				$testo="<Nessuna modifica>";
			}
			else
			{
				$testo="\r\n<\r\n";
				if (!empty($del))
					$testo .= "eliminato:\r\n".$out1."\r\n";
				if (!empty($add))
					$testo .= "aggiunto:\r\n".$out2."\r\n";
				$testo .= ">\r\n";
			}
		}
		
		print "<pre>Operazione eseguita correttamente.</pre>";
	}
	else
	{
		die("Il file $new_name probabilmente e' protetto in scrittura! Contattare il webmaster.");
	}
}
else 
{
	die("Password errata!");
}

# logga il contatto
$counter = count_page("admin_upload_text",array("COUNT"=>1,"LOG"=>1),$filedir_counter); # abilita il contatore, senza visualizzare le cifre, e fai il log

$last_backslash = strrpos($new_name,'/')+1;
$simple_name = substr($new_name,$last_backslash); // nome del file senza il path
$output_dir = substr($new_name,0,$last_backslash); // path della cartella in cui e' stato scritto il file

log_action($output_dir,"$simple_name:$testo, ".date("l dS of F Y h:i:s A")."\r\n\r\n");

?>

<hr>
<a href="index.php">Torna indietro</a>

</body>
</html>
