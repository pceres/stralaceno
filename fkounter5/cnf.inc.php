<?php /* cnf.inc.php
                                        __                      PHP Script    _    vs 5.0
                                       / _| __ _ _ __   /\ /\___  _   _ _ __ | |_ ___ _ __
                                      | |_ / _` | '_ \ / //_/ _ \| | | | '_ \| __/ _ \ '__|
                                      |  _| (_| | | | / __ \ (_) | |_| | | | | ||  __/ |
                                      |_|  \__,_|_| |_\/  \/\___/ \__,_|_| |_|\__\___|_|

                                              fanatiko <fankounter@libero.it>, ITALY
 Documentazione di riferimento
############################################################################################
 license.txt - le condizioni di utilizzo, modifica e redistribuzione per l'utente finale
  manual.txt - la guida alla configurazione, all'installazione e all'uso dello script
    faqs.txt - le risposte alle domande più comuni, sui problemi e sulle funzionalità
 history.txt - la progressione delle versioni, i miglioramenti apportati e i bugs eliminati

 Descrizione del file
############################################################################################
 Modulo importato per la configurazione globale dello script.

 ATTENZIONE - UNA MODIFICA ERRATA DI QUESTO FILE PUO' COMPROMETTERE IL FUNZIONAMENTO DEI
 CONTATORI. MODIFICARE CON CAUTELA E SOLO SE SI SA ESATTAMENTE COSA SI STA FACENDO.

 LE UNICHE VARIABILI CHE DOVREBBERO ESSERE MODIFICATE IN QUESTO FILE SONO QUELLE CHE SI
 TROVANO NELL'ULTIMO PARAGRAFO SULLA 'CONFIGURAZIONE DEFAULT PER UN CONTATORE'.

      */

############################################################################################
# IMPOSTAZIONI DI ESECUZIONE
############################################################################################

// Nasconde qualsiasi messaggio di errore o di avviso run-time
//error_reporting(0); //!!!

// Prefisso per gli input ottenuti mediante get, post, cookie
import_request_variables("gpc","par__");

############################################################################################
# COSTANTI GLOBALI
############################################################################################

//require_once('../libreria.php')

// Memorizza il timestamp del server
define("NOW",time());

// Carattere di fine linea per i file: Win usa "\r\n", Unix usa "\n", Mac usa "\r"
define("EOL","\r\n");

############################################################################################
# IMPOSTAZIONI DI INTEGRITA'
############################################################################################

// Attiva o disattiva la condivisione dei file ottenuta mediante funzione 'flock()'
define("FLOCK",TRUE);

############################################################################################
# IMPOSTAZIONI DI PERFORMANCE
############################################################################################

// Esegue o meno la traduzione di un IP al rispettivo hostname
define("HOSTNAME",TRUE);

// Ricorrenza di tempo (secondi) in cui effettuare lo sfoltimento delle strutture dati
define("CUTTIME",60*60*24*180);

// Limite max di elementi per le strutture dati che possono crescere in modo spropositato
define("CUTSIZE",150);

############################################################################################
# CARTELLE E FILE
############################################################################################

// carica le variabili globali del sito per permettere di recuperare il path assoluto delle directory
require_once('../libreria.php');

define("DATA_FOLDER",$filedir_counter."data/");
define("TEMP_FOLDER",$filedir_counter."temp/");
define("CONFIG_FOLDER",$filedir_counter."conf/");
define("BACKUP_FOLDER",$filedir_counter."back/");

define("CONFIG_FILES","conf_*.php");
define("DATA_FILES","data_*.php");
define("ACCESS_FILES","accs_*.dat");
define("FLOCK_FILES","lock_*.tmp");

define("SCRIPT_PATH",$script_abs_path."fkounter5/");
define("FILE_PATH",$site_abs_path."fkounter5/");

############################################################################################
# CREDITS
############################################################################################

define("HOMEPAGE","http://profitterol.altervista.org/fankounter");
define("EMAIL","fankounter@libero.it");
define("VERSION","5.0.50317");

############################################################################################
# CONFIGURAZIONE DEFAULT PER UN CONTATORE
############################################################################################

// Validatore per la creazione di nuovi contatori
define("MAKE_PATHS","");

// Impostazioni multiutenza
$cnf__username="admin";
$cnf__usermail="user@email.com";
$cnf__userpass=md5("admin");

// Impostazioni del contatore
$cnf__start_count=0;
$cnf__mtime_unique_accs=30;
$cnf__expire_on_midnight=FALSE;
$cnf__count_per_pages=FALSE;
$cnf__licit_domains_list=array();
$cnf__IPmasks_ignore_list=array();
$cnf__htime_sync_server=0;
$cnf__last_entries=15;

// Impostazioni del visualizzatore
$cnf__passwd_protect=FALSE;
$cnf__limit_view=15;

$fankounter_image_default = "iVBORw0KGgoAAAANSUhEUgAAAGIAAAAmCAIAAACwDtkjAAAACXBIWXMAAA7DAAAOwwHHb6hkAAABOklEQVR4nO2Xyw2DMBBEOVAapaQSGkgJlBApx5SRdnJwDkSO490143j5CM3THjAfL3mMEen6YWQtVtcP4+3xZBXqqykQA0VTIXUu/eZCTn5NUyw53BJdk5W69mZzFzy5mRRHQVWrx9R0ud7Tms9rTBmeo0iqyTdEK2pqSVl6SRCrL5szXpWuOGtCOZRdrF7gnVRrakmZ9TOso8F+H+EzgDuto56awJRlSVEfo6opiJeUrybrTtJ2PprU/QVNVWmS2+5pKjzO3TTVLro4QxyuoekQacqyra7T7FD5uylbKbKLNW1hW96nqcnq9EeFs5Br2v3v5QFL0eQYpdOUoomoUBMENUFQEwQ1QVATBDVBUBMENUFQEwQ1QVATBDVBUBMENUFQEwQ1QVATBDVBUBMENUH8aGIV6qOJtVhvitqQTVHOp7kAAAAASUVORK5CYII="; // FanKounter

############################################################################################

?>
