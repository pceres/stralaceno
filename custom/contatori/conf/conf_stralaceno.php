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

$cnf__start_count         = 1284;
$cnf__mtime_unique_accs   = 120;
$cnf__expire_on_midnight  = FALSE;
$cnf__count_per_pages     = TRUE;
$cnf__licit_domains_list  = array();
$cnf__IPmasks_ignore_list = array();
$cnf__htime_sync_server   = 0;
$cnf__last_entries        = 150;

############################################################################################
# IMPOSTAZIONI DEL VISUALIZZATORE
############################################################################################

$cnf__userpass       = md5("stralacenoadmin");
$cnf__passwd_protect = FALSE;
$cnf__limit_view     = 15;

// La riga sottostante definisce l'immagine di sfondo del contatore fankounter. Per definirne una nuova, usare image_encode.php. 
// Stringa vuota per usare lo sfondo di default.
$fankounter_image = "R0lGODdhYgAmAPcAAAAAAAAAVQAAqgAA/wAkAAAkVQAkqgAk/wBJAABJVQBJqgBJ/wBtAABtVQBtqgBt/wCSAACSVQCSqgCS/wC2AAC2VQC2qgC2/wDbAADbVQDbqgDb/wD/AAD/VQD/qgD//yQAACQAVSQAqiQA/yQkACQkVSQkqiQk/yRJACRJVSRJqiRJ/yRtACRtVSRtqiRt/ySSACSSVSSSqiSS/yS2ACS2VSS2qiS2/yTbACTbVSTbqiTb/yT/ACT/VST/qiT//0kAAEkAVUkAqkkA/0kkAEkkVUkkqkkk/0lJAElJVUlJqklJ/0ltAEltVUltqklt/0mSAEmSVUmSqkmS/0m2AEm2VUm2qkm2/0nbAEnbVUnbqknb/0n/AEn/VUn/qkn//20AAG0AVW0Aqm0A/20kAG0kVW0kqm0k/21JAG1JVW1Jqm1J/21tAG1tVW1tqm1t/22SAG2SVW2Sqm2S/222AG22VW22qm22/23bAG3bVW3bqm3b/23/AG3/VW3/qm3//5IAAJIAVZIAqpIA/5IkAJIkVZIkqpIk/5JJAJJJVZJJqpJJ/5JtAJJtVZJtqpJt/5KSAJKSVZKSqpKS/5K2AJK2VZK2qpK2/5LbAJLbVZLbqpLb/5L/AJL/VZL/qpL//7YAALYAVbYAqrYA/7YkALYkVbYkqrYk/7ZJALZJVbZJqrZJ/7ZtALZtVbZtqrZt/7aSALaSVbaSqraS/7a2ALa2Vba2qra2/7bbALbbVbbbqrbb/7b/ALb/Vbb/qrb//9sAANsAVdsAqtsA/9skANskVdskqtsk/9tJANtJVdtJqttJ/9ttANttVdttqttt/9uSANuSVduSqtuS/9u2ANu2Vdu2qtu2/9vbANvbVdvbqtvb/9v/ANv/Vdv/qtv///8AAP8AVf8Aqv8A//8kAP8kVf8kqv8k//9JAP9JVf9Jqv9J//9tAP9tVf9tqv9t//+SAP+SVf+Sqv+S//+2AP+2Vf+2qv+2///bAP/bVf/bqv/b////AP//Vf//qv///yH5BAAAAAAALAAAAABiACYAAAj/ABUkEEhwoMGCCA8qTMhwocOGEB9KhGjL0i2LtjJqtHWxo0VLG29lFEmS48iTl2ylNLkRpMuKGl9+3GiyZMmUOFXqTKlAga1/QIMKHUq0qNGjSJMqXRr0Vs+f/wb2nNqTqdWrWLMGtfUUaAKbHBVoHUu27D+uPoH6lCOFrRxLCR4qZUcXKDukd80Gzct3aF6hTtP+U2DJbVu4J0VyTfqXbt3Hjv/ddcy3sd3Lkv9K3jwZc+StXQfbkkOatNNbpUlzldrT4F7NfYV23ruZM+fOsW3jtl37bOi1qbmODu4TrFjZjzFnnh0ZdvLkvXdPhv539S21ttpqt5VgdFu2Todr/7+VgPbl2H2Z1569vv153eb/Ov2KPbWc09rBd7dPHnnl9c0t959d6mXml3kGfgZUYFB9VZob9ymAGmkQcnfLd6aVp9eGWFkHFEcfXaQRWCRmxOGJTJGXFkEKGNCaQAO5SFUCBtA4UAFTsZYjaxLFBWNBPr7o0EEJGeTQPwyeJdIltzCJUU01OenRkiblxCRLSzbZEUe3dEmlljaF2CVIME1JU0ok+UYfimy2SZSHbsbZZpJy1skhnXbmSRZaUOnpZ4eh/SnoUtytOeihR813HaKMEnVJoI1GymeklPomWKWMqtgnpofCyWmncS366aCajnpoqaYKimqqfhYqKqt5KkkKq5/kGTprndxdequcuVZUopYhBitiTiRi9CtNNCmmLJQlYcnssyKBJGRBOCZQQFw1WhvXtdoqUG222cYlrpE/TmQuueZGRFBAADs="; // StralacenoWeb

############################################################################################

?>
