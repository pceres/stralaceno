# File di configurazione dei diversi file di configurazione:
#
# Per ciascun cfgfile con nome <nome>, bisogna creare una struttura [nome], con ciascuna riga (un modulo puo' avere piu' file di configurazione) 
# nel formato:
# 	nomefile::cartella::descrizione_file::allowed_groups::md5_password
#
#	nomefile: 		nome del file di configurazione, senza path; NON MODIFICARE!!!
#	cartella:		cartella contenente il file; NON MODIFICARE!!!
#	descrizione_file:	breve descrizione del file di configurazione;
#	allowed_groups:		gruppi abilitati ad accedere e visualizzare il file;
#	md5_password:		hash md5 della password che abilita la scrittura del file;
#

# root_admin
config_files.php::%config_dir%::configurazione generale::root_admin::dfe1e2aa4ab6e1fad33aa05ad982cd82
modules_config.php::%config_dir%::moduli_custom presenti nel sito::root_admin::dfe1e2aa4ab6e1fad33aa05ad982cd82
users.php::%config_dir%::account al sito::root_admin::dfe1e2aa4ab6e1fad33aa05ad982cd82

# admin
albums.txt::%config_dir%::configurazione album::admin::dfe1e2aa4ab6e1fad33aa05ad982cd82
links.txt::%config_dir%::links::admin::dfe1e2aa4ab6e1fad33aa05ad982cd82
layout_left.txt::%config_dir%::disposizione colonna sinistra::admin::dfe1e2aa4ab6e1fad33aa05ad982cd82
layout_right.txt::%config_dir%::disposizione colonna destra::admin::dfe1e2aa4ab6e1fad33aa05ad982cd82
layout_left_FC_caposele.txt::%config_dir%::colonna sin. FC Caposele::admin::dfe1e2aa4ab6e1fad33aa05ad982cd82
layout_right_FC_caposele.txt::%config_dir%::colonna dx. FC Caposele::admin::dfe1e2aa4ab6e1fad33aa05ad982cd82

# moduli
pregfas_cfg.txt::%modules_dir%pregfas/::gestione pregfas::admin::dfe1e2aa4ab6e1fad33aa05ad982cd82
lettere_sito_cfg.txt::%modules_dir%lettere_sito/::gestione archivio lettere::admin::dfe1e2aa4ab6e1fad33aa05ad982cd82
soci_cfg.txt::%modules_dir%soci/::gestione elenco soci e news ARS::admin::dfe1e2aa4ab6e1fad33aa05ad982cd82
flash_news_cfg.txt::%modules_dir%flash_news/::gestione News Flash::admin::dfe1e2aa4ab6e1fad33aa05ad982cd82
siti_amici_cfg.txt::%modules_dir%siti_amici/::gestione elenco siti Caposele sul Web::admin::dfe1e2aa4ab6e1fad33aa05ad982cd82
archivio_cfg.txt::%modules_dir%archivio/::gestione archivio album e articoli::admin::dfe1e2aa4ab6e1fad33aa05ad982cd82
classifica_campionato_cfg.txt::%modules_dir%classifica_campionato/::gestione classifica e risultati ultima giornata::admin::dfe1e2aa4ab6e1fad33aa05ad982cd82

# sondaggi/lotterie
#lotteria_001.txt::%questions_dir%::configurazione "Sondaggio Mondiali di Calcio 2006"::admin::dfe1e2aa4ab6e1fad33aa05ad982cd82
#lotteria_001_ans.txt::%questions_dir%::risultati Mondiali di Calcio 2006::admin::dfe1e2aa4ab6e1fad33aa05ad982cd82
lotteria_???.txt::%questions_dir%::configurazione sondaggio/lotteria::admin::dfe1e2aa4ab6e1fad33aa05ad982cd82
lotteria_???_ans.php::%questions_dir%::risposte/soluzione::admin::dfe1e2aa4ab6e1fad33aa05ad982cd82
