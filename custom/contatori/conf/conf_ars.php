<?php /* conf_example.php
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
 Esempio di configurazione per un contatore.

 A partire da questo file è possibile creare una nuova istanza di contatore.

 * Modificare in base alle proprie preferenze le seguenti variabili. Queste, tuttavia, sono
   già configurate correttamente per un utilizzo standard.

 * Salvare questo file con un nome della forma 'config_ID.php', dove ID sarà il suo
   identificatore, e porlo sul server in una sottocartella del fanKounter di nome 'conf'.

   Un ID può essere costituito solamente da caratteri alfanumerici, può avere qualsiasi
   lunghezza, ed è case-sensitive, ossia "test" e "TesT" identificheranno normalmente due
   diverse istanze di contatore, ma questo dipende dal sistema operativo in uso.

   Se, ad esempio, si decide di identificare questo contatore con "test1", allora salvare
   il file con il nome 'conf_test1.php' (senza apici).

      */

############################################################################################
# IMPOSTAZIONI DEL CONTATORE
############################################################################################

$cnf__start_count         = 0;
$cnf__mtime_unique_accs   = 120;
$cnf__expire_on_midnight  = FALSE;
$cnf__count_per_pages     = TRUE;
$cnf__licit_domains_list  = array();
$cnf__IPmasks_ignore_list = array();
$cnf__htime_sync_server   = 0;
$cnf__last_entries        = 15;

############################################################################################
# IMPOSTAZIONI DEL VISUALIZZATORE
############################################################################################

$cnf__userpass       = md5("stralacenoadmin");
$cnf__passwd_protect = FALSE;
$cnf__limit_view     = 15;

// numero di giorni (corrente e precedenti) di cui visualizzare il totale dei contatti
define("PREVIOUS_DAYS",31);

// numero di mesi (corrente e precedenti) di cui visualizzare il totale dei contatti
define("PREVIOUS_MONTHS",13);

// La riga sottostante definisce l'immagine di sfondo del contatore fankounter. Per definirne una nuova, usare image_encode.php. 
// Stringa vuota per usare lo sfondo di default.
$fankounter_image = "R0lGODlhYgAmAIQQABQUUDw8UBQUUDw8UAAkVQAkqgBJVQBJqkmSqm1tqm2SqpK2qpK2/7a2qra2//9tAP///////////////////////////////////////////////////////////////yH+FUNyZWF0ZWQgd2l0aCBUaGUgR0lNUAAsAAAAAGIAJgAABf7gYYjkaJboqabs6rbwK8PN4thNrjd3by87R05I5A2PjEbSuAM6a7rnb2csFpNYpTZ5ODQg4LB4TC6bwd10OM1Wt9mK8OLgkCPOjsM47wWP3npngmRugXyAbQhtcWCKDGEIC2dzYw1dXxAGVjyBg54QXWiBbGtvpV2McweSEA53ZwqvYZZ9oA2xuAsGL59ioWB5or+giAevB4wIyowKrK7KdY3N0K2XaAu4CM0GR0KWvcLRqMTF5XELCK5xrmCxNsrAy+h3tJheCvj4eQ75+JZ/XUyUOTQqVDFfgOJEggAPHwR00ShVY6VATz00t/pZyphv46ZOCNsQIyeJFCE4Ev6hpWNoTNkvdGFiQfgnroG2mw244YqVJ+NNBwbK0GGoSI8pcolOtUyGjJExB1ChtmPEcJ2Imv0U7LvJU2c/oCffEByGSBibaBVXgqIqZuHDoXwwacqXQCsdugpyqssHtq1JYEg7lXtDNW3MVVHXaEXHiCYYHj9u6NhEOceYv2bVKA1cMNpbzxAqGmN0yG2rEV9IHCgQUMQI1mwMFJA9gkAagLcBytjlugTv1i5UBHdRrY8RBg6Q46hSRbkPIUmgbylyPHkPHlGlW7dig4jkKpG95YjOY+ZVcOjTq19/xjH79/DjD7Qmv7599XE/AQAQZn9//mDsB+B9BJJx0SAA8umXIAQLBljgg5XQJ8iCAzJIRoUQFpiTJp5QyKCCH/aXYYZ5cIiggx5a6OCIDzIg4YQDChigfx9iyKJ8B96o43s57ugjekDV8uOQn7hH5JHt7RKNAEwyCYGTTzYpQJRgTBmGlEiyFyQmVXb55JVeTmmll1lqiZoYVqYJ5pdfjhmlm2WCsyWaYa6JpZtwxgnOhqCpySabauKp53ol9nmloIC+KWaTg8qpZKNl5iQkpERKWkNl1kWmqWRZUIYDplRQ4c2ozFFHKqhFAAFcCbYZQMAus7m6y6uyHtBqrLHuoqsJvMbgK2/A/uprCAA7"; // ArsWeb

############################################################################################

?>
