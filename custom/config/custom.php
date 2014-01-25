<?php 

#
# variabili da modificare per personalizzare il sito:
#


# nome della gara
$race_name = "Stralaceno";


# parte iniziale (senza spazi ne' simboli) della cartella sul server in cui e' contenuto il sito
$root_prefix = "stralaceno";


# titolo delle pagine web
$web_title = "Stralaceno Web";


# campo meta-description
$web_description = "Sito ufficiale della Stralaceno";


# campo meta-keywords (per i motori di ricerca)
$web_keywords = "Stralaceno, Caposele, Caposelesi, Corsa podistica, Atletica, Lago Laceno, Laceno";


# indirizzo e-mail per contatti (stringa vuota se non e' disponibile)
$email_info	= "stralaceno@freepass.it";


# durata massimo della gara [min]
$tempo_max_M = 40;
$tempo_max_F = 45;


# nome dei file csv contenenti tutti i dati della corsa
$nome_file_tempi = "tempi_laceno.csv";
$nome_file_atleti = "atleti_laceno.csv";
$nome_file_organizzatori = "organizzatori_laceno.csv";

# dati custom:
$custom_vars = Array();
// link e caption custom1 della pagina "info"
//$custom_vars['custom1_link']    = "http://localhost/work/PhpGedView/individual.php?pid=<$$>";
$custom_vars['custom1_link']    = "http://ars.altervista.org/PhpGedView/individual.php?pid=<$$>";
$custom_vars['custom1_caption'] = "Link nella <a href=\"<$$>\">&quot;Genealogia caposelese&quot;</a> (un estratto nel grafico sotto)";


#
# password per la sezione amministrativa:
#

# gestione riservata al gruppo root_admin
$password_root_admin = 'eef358de0c01694206eafe104006f44b';

# gestione degli articoli in prima pagina
$password_articoli = 'd36923926ed59333afab0bba1a4ad5e5';

# gestione degli album
$password_album = 'd36923926ed59333afab0bba1a4ad5e5';

# caricamento dei database della gara (tempi, atleti, organizzatori)
$password_upload_file = 'd36923926ed59333afab0bba1a4ad5e5';

# caricamento e gestione dei file di configurazione
$password_config = 'd36923926ed59333afab0bba1a4ad5e5';

# caricamento e gestione delle lotterie/questionari
$password_lotterie = '60539869a90ef7e8655bd2771b44d481';

?>
