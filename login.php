<?php
/*
questa libreria esamina i cookies o i parametri http (eventualmente) inviati, e genera l'array $login con i camp
'username',	: login
'usergroups',	: lista dei gruppi di appartenenza (separati da virgola)
'status',		: stato del login: 'none','ok_form','ok_cookie','error_wrong_username','error_wrong_userpass','error_wrong_challenge','error_wrong_IP'
*/

$IP = get_IP(); // informazioni sulla connessione remota

// analisi dei cookie (eventuali)
$cookie_username = $_COOKIE['login']['username'];
$cookie_username = sanitize_user_input($cookie_username,'plain_text',array());		// (eventuale) username (cookie)

$cookie_usergroups = $_COOKIE['login']['usergroups'];
$cookie_usergroups = sanitize_user_input($cookie_usergroups,'plain_text',array()); 	// (eventuale) usergroups (cookie)

$cookie_challenge_id = $_COOKIE['login']['challenge_id'];
$cookie_challenge_id = sanitize_user_input($cookie_challenge_id,'plain_text',array()); 	// (eventuale) challenge id (cookie)

// analisi dell'input inserito dall'utente
$login_action = $_REQUEST['login_action'];
$login_action = sanitize_user_input($login_action,'plain_text',array()); // azione da effettuare

$username = $_REQUEST['username'];
$username = sanitize_user_input($username,'plain_text',array()); // username inserito dall'utente

$userpass = $_REQUEST['userpass'];
$userpass = sanitize_user_input($userpass,'plain_text',array()); // password inserita dall'utente

$challenge = $_REQUEST['challenge'];
$challenge = sanitize_user_input($challenge,'plain_text',array()); // challenge del login

$challenge_id = $_REQUEST['challenge_id'];
$challenge_id = sanitize_user_input($challenge_id,'plain_text',array()); // challenge_id del login


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
	if ( (strlen($cookie_username)>0) &
		 (strlen($cookie_usergroups)>0) &
		 (strlen($cookie_challenge_id)>0) )
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
	elseif ( (strlen($username)>0) &
			 (strlen($userpass)>0) &
			 (strlen($challenge)>0) &
			 (strlen ($challenge_id)>0) ) // login in corso
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
	else
	{
		$bulk = Array();
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


function check_single_auth($mansioni_filtrate,$found_task,$abilitazione,$tag,$params,$username,$usergroups,$level = 1,$debug)
{
	// verifica se la mansione $abilitazione verifica l'abilitazione di livello $level, rispetando le regole $mansioni_filtrate
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
			$params_assignment = explode(';',$abilitazione_params);
			$params_rep = Array();
			foreach ($params_assignment as $param_assignment)
			{
				$temp = explode('=',$param_assignment);
				$params_rep[$temp[0]] = $temp[1];
			}
			
			// applica le trasformazioni ai parametri
			$ks_par = $found_task_params;
			foreach($params_rep as $key => $value)
			{
				$ks_par = preg_replace("~$key~",$value,$ks_par);
			}
			
			// verifica se i parametri coincidono (regexp)
			if (($ks_par == $params) | (preg_match("~$ks_par~",$params)))
			{
				if ($debug)
				{
					echo("Anche i parametri corrispondono ($ks_par,$params)!<br>\n");
				}
				return true;
			}
			else
			{
				if ($debug)
				{
					echo("I parametri non corrispondono ($ks_par,$params)!<br>\n");
				}
//echo "&lt;$ks_par|$params&gt;<br>";
//echo "<br><br>";
//print_r($abilitazione);
//echo "<br><br>";
//print_r($found_task);
//echo "<br><br>";
				return false;
			}
		}
		else
		{
			die("Non dovevo arrivare qui (codice 1)!");
		}
		break;
		
	case "mansione":
		$submansioni = $mansioni_filtrate[$abilitazione_tag];
		if (empty($submansioni))
		{
			die("Non dovevo arrivare qui (codice 2)!");
		}
if ($debug) {		print_r($submansioni);echo "<br><br>\n";}
		
		foreach($submansioni as $submansione)
		{
			$submansione_tipo        = $submansione[index_abilitazione_tipo];
			$subabilitazione_tag     = $submansione[index_abilitazione_tag];
			$subabilitazione_params  = $submansione[index_abilitazione_params];
			$subabilitazione_caption = $submansione[index_abilitazione_caption];
			$subabilitazione_allowed = $submansione[index_abilitazione_allowed];
			
			// trasforma la stringa dei parametri in array
			$params_assignment = explode(';',$abilitazione_params);
			
			$params_rep = Array();
			foreach ($params_assignment as $param_assignment)
			{
				$temp = explode('=',$param_assignment);
				$params_rep[$temp[0]] = $temp[1];
			}
			
			// applica le trasformazioni ai parametri
			$lista_params = explode(';',$subabilitazione_params);
			foreach($lista_params as $id_par => $ks_par)
			{
				foreach($params_rep as $key => $value)
				{
					// sostituisci a destra dell'uguale la stringa $key con $value
					$left_right = explode('=',$ks_par);
					$ks_par = $left_right[0].'='.preg_replace("~$key~",$value,$left_right[1]);
				}
				$lista_params[$id_par] = $ks_par;
			}
			$submansione[index_abilitazione_params] = implode(';',$lista_params);
			
if ($debug) 			echo "Con la modifica: $abilitazione_params => $subabilitazione_params => $ks_par<br><br>\n";
			
			if (check_single_auth($mansioni_filtrate,$found_task,$submansione,$tag,$params,$username,$usergroups, $level+1,$debug))
			{
if ($debug) 				echo("Anche i parametri corrispondono!<br>\n");
				return true;
			}
			else
			{
if ($debug) 				echo("i parametri non corrispondono!<br>\n");
			}
		}
		
		// ho provato tutte le mansioni, nessuna va bene!
		return false;
		
		break;
	}
} // end function check_single_auth(&$mansioni_filtrate,$found_task,$abilitazione,$tag,$params,$level = 1)


function check_auth($tag,$params,$username,$usergroups,$debug = false)
{
	// verifica se l'utente $username, appartenente al gruppo $usergroups (array), e' abilitato alla pagina con tag $tag e parametri $params

	$time0=explode(' ',microtime());$time0 = $time0[1]+$time0[0]; // timestamp per misurare il tempo di calcolo
	
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

if ($debug)
{
echo("<br>$nome_mansione<br><br><br>");
print_r($lista_mansioni);
echo('<br><br><br><br>');
}
		
		if ($debug) echo "<br>".($id_nome_mansione+1).") $nome_mansione<br>\n";
		
		foreach ($lista_mansioni as $id_mansione => $mansione)
		{
			$mansione_tipo   = $mansione[index_mansione_tipo];
			$mansione_tag    = $mansione[index_mansione_tag];
			$mansione_params = $mansione[index_mansione_params];
			$mansione_caption= $mansione[index_mansione_caption];
			
			switch ($mansione_tipo)
			{
			case 'task':
				if ($mansione_tag == $tag)
				{
if ($debug) echo "Trovato task ($tag) !!!<br>\n";
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
if ($debug) 					echo "Trovata mansione ($mansione_tag) !!!<br>\n";
					if (array_key_exists($mansione_tag,$abilitazioni_filtrate))
					{
						if (!array_key_exists($nome_mansione,$abilitazioni_filtrate))
						{
							$abilitazioni_filtrate[$nome_mansione] = Array();
						}
						array_push($abilitazioni_filtrate[$nome_mansione],$mansione);
					}
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
	if ($debug) {echo "Le abilitazioni filtrate fino ad ora sono:";print_r($abilitazioni_filtrate);echo "<br><hr><br>";}
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
			if (group_match($username,$usergroups,explode(',',$abilitazione_allowed)))
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

?>
