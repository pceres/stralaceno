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
	if (isset($_SERVER['HTTP_X_FORWARDED_FOR']) && $_SERVER['HTTP_X_FORWARDED_FOR'] != '')
	{
		// this is needed in case of proxy: we want the name of the user, not the one of the proxy in between
		$IP = $_SERVER['HTTP_X_FORWARDED_FOR'];
	} else {
		$IP = $_SERVER['REMOTE_ADDR']; // informazioni sulla connessione remota
	}
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

?>
