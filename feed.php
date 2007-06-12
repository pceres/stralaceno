<?php

require_once('libreria.php');
require_once('last_contents_lib.php');	// gestione ultimi contenuti
require_once('login.php');		// gestione autenticazione


$feed = read_last_contents($login);	// carica i dati degli ultimi contenuti aggiunti
publish_rss20($feed);			// genera il testo XML del feed RSS 2.0

# logga il contatto
$counter = count_page("feed",array("COUNT"=>1,"LOG"=>1),$filedir_counter); # abilita il contatore, senza visualizzare le cifre, e fai il log
?>