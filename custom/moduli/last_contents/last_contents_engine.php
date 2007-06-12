<?php
/*

input impliciti:

$module_name	: nome del modulo
$relative_path	: path per arrivare alla radice del sito

*/


require_once('../../../last_contents_lib.php');	// gestione ultimi contenuti
require_once('../../../login.php');		// gestione autenticazione

$feed = read_last_contents($login);
publish_config($feed,$module_name,$relative_path);

?>