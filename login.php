<?php
/*
questa libreria esamina i cookies o i parametri http (eventualmente) inviati, e genera l'array $login con i campi
'username',	: login
'usergroups',	: lista dei gruppi di appartenenza (separati da virgola)
'status',		: stato del login: 'none','ok_form','ok_cookie','error_wrong_username','error_wrong_userpass','error_wrong_challenge','error_wrong_IP'
*/

$IP = get_IP(); // informazioni sulla connessione remota

$cookie_username = $_COOKIE['login']['username'];
$cookie_usergroups = $_COOKIE['login']['usergroups'];
$cookie_challenge_id = $_COOKIE['login']['challenge_id'];

$login_action = $_REQUEST['login_action'];
$username = $_REQUEST['username'];
$userpass = $_REQUEST['userpass'];
$challenge = $_REQUEST['challenge'];
$challenge_id = $_REQUEST['challenge_id'];

$EXPIRE_COOKIE = 60*60; // [s] durata dei cookies (un'ora)
$strict = false; // true -> qualsiasi errore blocca l'esecuzione; false -> viene restituito il msg di errore

# formato file di configurazione $filename_users
$elenco_users = get_config_file($filename_users);
$elenco_users = $elenco_users['users'];

switch ($login_action)
{
case '':
case 'login':

	//verifica se l'autenticazione da cookie esiste ed e' valida
	if ( (count($cookie_username)>0) & (count($cookie_usergroups)>0) & (count($cookie_challenge_id)>0) )
	{
		// check corrispondenza challenge_id <--> IP
		if (check_IP_challenge_id($cookie_challenge_id,$IP))
		{
			$username = $cookie_username;
			$usergroups = $cookie_usergroups;
			$login_status = 'ok_cookie';
		}
		else // l'IP non coincide con quello che aveva effettuato il login!
		{
			$username = '';
			$usergroups = '';
			$login_status = 'error_wrong_IP';
			
			// gestione cookies
			setcookie("login[username]","",time()-3600);
			setcookie("login[usergroups]","",time()-3600);
			setcookie("login[challenge_id]","",time()-3600);
		}
		break;
	}
	elseif ( (count($username)>0) & (count($userpass)>0) & (count($challenge)>0) & (count($challenge_id)>0) ) // login in corso
	{ // altrimenti tenta il login con i parametri passati dal form:
		
		// verifica che il challenge non sia gia' stato usato. In tal caso marcalo come usato (registrando l'IP)!
		if (check_challenge($challenge_id,$challenge,$IP))
		{
			$user_found = 0;
			foreach($elenco_users as $userdata)
			{
				if ($username == $userdata[$indice_user_name])
				{
					$user_found = 1;
					if ($userpass == md5($userdata[$indice_user_passwd].$challenge))
					{
						$usergroups = $userdata[$indice_user_groups];
						$login_status = 'ok_form';
						
						// imposta i cookies
						setcookie("login[username]", $username,time()+$EXPIRE_COOKIE);
						setcookie("login[usergroups]", $usergroups,time()+$EXPIRE_COOKIE);
						setcookie("login[challenge_id]",$challenge_id,time()+$EXPIRE_COOKIE);
						
					}
					else
					{
						$usergroups = '';
						$login_status = 'error_wrong_userpass';
						break;
					}
				}
			}
			if (!$user_found)
			{
				$usergroups = '';
				$login_status = 'error_wrong_username';
			}
		}
		else
		{
			$username = '';
			$usergroups = '';
			$login_status = 'error_wrong_challenge';
		}
	}
	else
	{
		if (empty($login_status))
		{
			$username = 'guest';	// utente di default
			$usergroups = 'guests'; // gruppo dell'utente di default
			$login_status = 'none';
		}
	}
	break;
	
case 'logout':
	$username = 'guest';	// utente di default
	$usergroups = 'guests'; // gruppo dell'utente di default
	$login_status = 'none';
	
	// gestione cookies
	setcookie("login[username]","",time()-3600);
	setcookie("login[usergroups]","",time()-3600);
	setcookie("login[challenge_id]","",time()-3600);
	break;
} // end switch $login_action


// gestisci errori:
if (!in_array($login_status,array('none','ok_form','ok_cookie')))
{
	sleep(1);
	
	if ($strict)
	{
		die("Login fallito! (Errore: $login_status)");
	}
	
	// in caso di errore, considera l'utente un guest
	$username = 'guest';	// utente di default
	$usergroups = 'guests'; // gruppo dell'utente di default
}


// da qui in poi esistono le variabili $username e $usergroups e $login_status;
$login = array('username'=>$username,'usergroups'=>explode(',',$usergroups),'status'=>$login_status);


// *******************************************************************************************************************************************************
function get_challenge(&$challenge_id,&$challenge)
{
	# dichiara variabili
	extract(indici());

	if (file_exists($filename_challenge))
	{
		$bulk = file($filename_challenge);
	}
	$challenge_id = md5(time());	
	$challenge = md5($challenge_id.time());
	$bulk[count($bulk)] = "$challenge_id::$challenge::\r\n";
	
	// elimina i challenge oltre gli ultimi 20
	$max_challenges = 20;
	if (count($bulk)>20)
	{
		$offset = count($bulk)-20;
	}
	else
	{
		$offset = 0;
	}
	
	// scrivi il file dei challenge
	if ($handle=fopen($filename_challenge,'w'))
	{
		for ($i = $offset; $i < count($bulk); $i++)
		{
			fwrite($handle, $bulk[$i]);
		}
		fclose($handle);
	}
	else
	{
		die("$filename_challenge e' probabilmente protetto in scrittura! Contattare il webmaster.");
	}

} // end function get_challenge


function get_IP()
{
	$IP = $_SERVER['REMOTE_ADDR']; // informazioni sulla connessione remota
	return $IP;
}


function check_challenge($challenge_id,$challenge,$IP)
{
	# dichiara variabili
	extract(indici());

	if (file_exists($filename_challenge))
	{
		$bulk = file($filename_challenge);
	}
	
	$result = false;
	$indice = '';
	for ($key = count($bulk)-1; $key >= 0; $key--)
	{
		$line_data = substr($bulk[$key],0,-2); // elimina "/r/n" dalla fine della stringa
		$line_data = explode('::',$line_data);
		
		if ($line_data[0] == $challenge_id) // challenge_id trovato
		{
			$challenge_ok = $line_data[1];
			$IP_ok = $line_data[2];
			
			if ($challenge_ok == $challenge) 
			{
				$result = true; // challenge ok
			}
			
			// indice linea che coincide
			$indice = $key;
			
			// scrivi il file dei challenge
			if ($handle=fopen($filename_challenge,'w'))
			{
				foreach ($bulk as $key => $linea)
				{
					if ($key != $indice)
					{
						fwrite($handle, $linea);
					}
					else
					{
						fwrite($handle, "$challenge_id::$challenge::$IP\r\n");
					}
				}
				fclose($handle);
			}
			else
			{
				die("$filename_challenge e' probabilmente protetto in scrittura! Contattare il webmaster.");
			}
			
			break;
		}
	}
	
	return $result;
	
} // end function check_challenge


function check_IP_challenge_id($challenge_id,$IP)
{
	# dichiara variabili
	extract(indici());

	if (file_exists($filename_challenge))
	{
		$bulk = file($filename_challenge);
	}
	
	$result = false;
	$indice = '';
	for ($key = count($bulk)-1; $key >= 0; $key--)
	{
		$line_data = substr($bulk[$key],0,-2); // elimina "/r/n" dalla fine della stringa
		$line_data = explode('::',$line_data);
		
		if (($line_data[0] == $challenge_id) & ($line_data[2] == $IP)) // challenge_id trovato
		{
			$result = true; // challenge ok
			break;
		}
	}
	
	return $result;
	
} // end function check_IP_challenge_id



// indici in [elenco_task_atomici]:
DEFINE("index_task_tag",0);
DEFINE("index_task_params",1);
DEFINE("index_task_caption",2);

// indici in [mansione_xxx]:
DEFINE("index_mansione_tipo",0);
DEFINE("index_mansione_tag",1);
DEFINE("index_mansione_params",2);
DEFINE("index_mansione_caption",3);

// indici in [abilitazione]:
DEFINE("index_abilitazione_tipo",0);
DEFINE("index_abilitazione_tag",1);
DEFINE("index_abilitazione_params",2);
DEFINE("index_abilitazione_caption",3);
DEFINE("index_abilitazione_allowed",4);


function check_single_auth(&$mansioni_filtrate,$found_task,$abilitazione,$tag,$params,$username,$usergroups,$level = 1,$debug)
{
	// verifica se la mansione $abilitazione verifica l'abilitazione di livello $level, rispetando le regole $mansioni_filtrate
	
// 	$debug = false;
// 	$debug = true;
	
	$abilitazione_tipo    = $abilitazione[index_abilitazione_tipo];
	$abilitazione_tag     = $abilitazione[index_abilitazione_tag];
	$abilitazione_params  = $abilitazione[index_abilitazione_params];
	$abilitazione_caption = $abilitazione[index_abilitazione_caption];
	$abilitazione_allowed = $abilitazione[index_abilitazione_allowed];
	
if ($debug) {
	echo "<br>Verifico la seguente mansione di livello $level:<br>\n";
	echo "&nbsp;&nbsp;&nbsp;&nbsp;$abilitazione_tipo<br>\n";
	echo "&nbsp;&nbsp;&nbsp;&nbsp;$abilitazione_tag<br>\n";
	echo "&nbsp;&nbsp;&nbsp;&nbsp;$abilitazione_params<br>\n";
	echo "&nbsp;&nbsp;&nbsp;&nbsp;$abilitazione_caption<br>\n";
	echo "&nbsp;&nbsp;&nbsp;&nbsp;$abilitazione_allowed<br>\n";
	echo "<br>\n";
}
	
	$found_task_tag     = $found_task[index_task_tag];
	$found_task_params  = $found_task[index_task_params];
	$found_task_caption = $found_task[index_task_caption];
	
	switch ($abilitazione_tipo)
	{
	case "task":
		if ($abilitazione_tag == $tag)
		{
			// trasforma la stringa dei parametri in array
			$params_assignment = split(';',$abilitazione_params);
			$params_rep = Array();
			foreach ($params_assignment as $param_assignment)
			{
				$temp = split('=',$param_assignment);
				$params_rep[$temp[0]] = $temp[1];
			}
			
			// applica le trasformazioni ai parametri
			$ks_par = $found_task_params;
			foreach($params_rep as $key => $value)
			{
				$ks_par = ereg_replace($key,$value,$ks_par);
			}
			
			// verifica se i parametri coincidono (regexp)
			if (($ks_par == $params) | (ereg($ks_par,$params)))
			{
if ($debug) {				echo("Anche i parametri corrispondono ($ks_par,$params)!<br>\n");}
				return true;
			}
			else
			{
if ($debug) {				echo("I parametri non corrispondono ($ks_par,$params)!<br>\n");}
				return false;
			}
		}
		else
		{
			die("Non dovevo arrivare qui!");
		}
		break;
		
	case "mansione":
		$submansioni = $mansioni_filtrate[$abilitazione_tag];
		if (empty($submansioni))
		{
			die("Non dovevo arrivare qui!");
		}
if ($debug) {		print_r($submansioni);echo "<br><br>\n";}
		
		foreach($submansioni as $submansione)
		{
			$submansione_tipo        = $submansione[index_abilitazione_tipo];
			$subabilitazione_tag     = $submansione[index_abilitazione_tag];
			$subabilitazione_params  = $submansione[index_abilitazione_params];
			$subabilitazione_caption = $submansione[index_abilitazione_caption];
			$subabilitazione_allowed = $submansione[index_abilitazione_allowed];
			
/*			if (empty($abilitazione_params))
			{
				die("I parametri non sono specificati per una mansione di tipo $abilitazione_tag!");
			}*/
			
			// trasforma la stringa dei parametri in array
			$params_assignment = split(';',$abilitazione_params);
			$params_rep = Array();
			foreach ($params_assignment as $param_assignment)
			{
				$temp = split('=',$param_assignment);
				$params_rep[$temp[0]] = $temp[1];
			}
			
			// applica le trasformazioni ai parametri
			$ks_par = $subabilitazione_params;
			foreach($params_rep as $key => $value)
			{
				$ks_par = ereg_replace("=".$key,"=".$value,$ks_par);
			}
			$submansione[index_abilitazione_params] = $ks_par;
			
if ($debug) 			echo "Con la modifica: $abilitazione_params => $subabilitazione_params => $ks_par<br><br>\n";
			
// 					      &$mansioni_filtrate,$found_task,$abilitazione,$tag,$params,$username,$usergroups,$level = 1,$debug
			if (check_single_auth(&$mansioni_filtrate,$found_task,$submansione,$tag,$params,$username,$usergroups, $level+1,$debug))
			{
if ($debug) 				echo("Anche i parametri corrispondono!<br>\n");
				return true;
			}
			else
			{
if ($debug) 				echo("i parametri non corrispondono!<br>\n");
// 				return false;
			}
		}
		
		// ho provato tutte le mansioni, nessuna va bene!
		return false;
		
// 		die("TODO: gestione mansione!");
		break;
	}
// 	die($abilitazione_tag);

} // end function check_single_auth(&$mansioni_filtrate,$found_task,$abilitazione,$tag,$params,$level = 1)


function check_auth($tag,$params,$username,$usergroups,$debug)
{
	// verifica se l'utente $username, appartenente al gruppo $usergroups (array), e' abilitato alla pagina con tag $tag e parametri $params
	
	$time0=explode(' ',microtime());$time0 = $time0[1]+$time0[0]; // timestamp per misurare il tempo di calcolo
	
// 	$debug = false;
// 	$debug = true;
	
	# dichiara variabili
	extract(indici());
	
if ($debug) echo "Il task e' $tag, con parametri $params<br><br>\n";
	
	$auth_file = $config_dir.'abilitazioni.php';
	
	$auth_data = get_config_file($auth_file);
	
	// lista delle abilitazioni (a gruppi, in cui una abilitazione per ciascuna linea)
	$mansioni = $auth_data;
	unset($mansioni['help_config']);	// elimina la sezione di help
	unset($mansioni['elenco_task_atomici']);// elimina la sezione dei task atomici
	
	// lista dei task atomici (una per ciascuna linea)
	$elenco_task_atomici = $auth_data['elenco_task_atomici'];
	
	// verifica se il $tag e' previsto dai task atomici
	$found_task = Array();
	foreach ($elenco_task_atomici as $id_task => $task_atomico)
	{
		if ($task_atomico[index_task_tag] == $tag)
		{
			// il tag e' stato trovato
			$found_task = $task_atomico;
		}
	}
	// ...e se non lo trovi visualizza un messaggio di errore e finisci
	if (count($found_task) == 0)
	{
		die("Tag \"$tag\" non previsto! Contattare l'amministratore.");
	}
	
	// elimina le mansioni non compatibili con il tag
	$abilitazioni_filtrate = Array();
	
	// elenco delle mansioni presenti
	$lista_nomi_mansione = array_keys($mansioni);
	for ($id_nome_mansione = count($lista_nomi_mansione)-1; $id_nome_mansione >= 0; $id_nome_mansione--)
	{
		$nome_mansione = $lista_nomi_mansione[$id_nome_mansione];
		$lista_mansioni = $mansioni[$nome_mansione];
		
		if ($debug) echo "<br>".($id_nome_mansione+1).") $nome_mansione<br>\n";
		
		foreach ($lista_mansioni as $id_mansione => $mansione)
		{
			$mansione_tipo   = $mansione[index_mansione_tipo];
			$mansione_tag    = $mansione[index_mansione_tag];
			$mansione_params = $mansione[index_mansione_params];
			$mansione_caption= $mansione[index_mansione_caption];
			
// 			echo "&nbsp;&nbsp;&nbsp;&nbsp;$mansione_tipo,$mansione_tag<br>\n";
			
			switch ($mansione_tipo)
			{
			case 'task':
				if ($mansione_tag == $tag)
				{
if ($debug) echo "Trovato ($tag) !!!<br>\n";
					if (!array_key_exists($nome_mansione,$abilitazioni_filtrate))
					{
						$abilitazioni_filtrate[$nome_mansione] = Array();
					}
					array_push($abilitazioni_filtrate[$nome_mansione],$mansione);
				}
				break;
			case 'mansione':
				if (array_key_exists($mansione_tag,$mansioni))
				{
if ($debug) 					echo "Trovato ($mansione_tag) !!!<br>\n";
					if (!array_key_exists($nome_mansione,$abilitazioni_filtrate))
					{
						$abilitazioni_filtrate[$nome_mansione] = Array();
					}
					array_push($abilitazioni_filtrate[$nome_mansione],$mansione);
				}
				else
				{
					die("Mansione \"$mansione_tag\" non prevista! Contattare l'amministratore.");
				}
				break;
			default:
				die("Tipo di mansione \"$mansione_tipo\" non prevista! Contattare l'amministratore.");
			}
		}
	}
	
if ($debug) {
	echo "<br>Ecco le mansioni filtrate:<br>\n";
	print_r($abilitazioni_filtrate);
	echo "<br><br><hr>\n";
}
	
	// verifica se qualcuna delle mansioni abilitate coincide con la coppia $tag,$params
	foreach($abilitazioni_filtrate['abilitazioni'] as $id_abilitazione => $abilitazione)
	{
		$abilitazione_tipo	= $abilitazione[index_abilitazione_tipo];
		$abilitazione_tag	= $abilitazione[index_abilitazione_tag];
		$abilitazione_params	= $abilitazione[index_abilitazione_params];
		$abilitazione_caption	= $abilitazione[index_abilitazione_caption];
		$abilitazione_allowed	= $abilitazione[index_abilitazione_allowed];
		
if ($debug)	echo "$id_abilitazione) $abilitazione_tipo,$abilitazione_tag,$abilitazione_params,$abilitazione_caption,$abilitazione_allowed<br>\n";
		
		$level = 1;
		if (check_single_auth($abilitazioni_filtrate,$found_task,$abilitazione,$tag,$params,$username,$usergroups,$level,$debug))
		{
			// verifica autorizzazioni in lettura
			if (group_match($username,$usergroups,split(',',$abilitazione_allowed)))
			{
				$enabled = 1;
if ($debug) 			echo("L'utente $username e' abilitato ($abilitazione_allowed)<br>");
				
				// appena viene trovato un match, la ricerca si ferma
				if ($debug)
				{
					$time1=explode(' ',microtime());$time1 = $time1[1]+$time1[0];
					$delta=($time1-$time0)*1e3; // in millisecondi
					echo("ok) tempo di calcolo: $delta ms ($time0,$time1)<br>");
				}
				
				return $enabled;
			}
			else
			{
				$enabled = 0;
if ($debug) 			echo("L'utente $username NON e' abilitato ($abilitazione_allowed)<br>");
			}
			
/*			// appena viene trovato un match, la ricerca si ferma
			if ($debug)
			{
				$time1=explode(' ',microtime());$time1 = $time1[1]+$time1[0];
				$delta=($time1-$time0)*1e3; // in millisecondi
				echo("ok) tempo di calcolo: $delta ms ($time0,$time1)<br>");
			}

			return $enabled;*/
		}
	}
	
	
	if ($debug)
	{
		$time1=explode(' ',microtime());$time1 = $time1[1]+$time1[0];
		$delta=($time1-$time0)*1e3; // in millisecondi
		echo("no) tempo di calcolo: $delta ms ($time0,$time1)<br>");
	}
	
	return false;
} // end function check_auth($tag,$params)


function filtra_abilitazioni($abilitazioni,$tag)
{
	// elimina dai dati di abilitazione quelli sicuramente non validi in quanto il tag non corrisponde
	$abilitazioni_filtrate = Array();
}


?>
