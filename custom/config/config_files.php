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
config_files.php::%config_dir%::configurazione generale::root_admin::eef358de0c01694206eafe104006f44b
modules_config.php::%config_dir%::moduli_custom presenti nel sito::root_admin::eef358de0c01694206eafe104006f44b
users.php::%config_dir%::account al sito::root_admin::eef358de0c01694206eafe104006f44b

# admin
albums.txt::%config_dir%::configurazione album::admin::d36923926ed59333afab0bba1a4ad5e5
links.txt::%config_dir%::links::admin::d36923926ed59333afab0bba1a4ad5e5
layout_left.txt::%config_dir%::disposizione colonna sinistra::admin::d36923926ed59333afab0bba1a4ad5e5
layout_right.txt::%config_dir%::disposizione colonna destra::admin::d36923926ed59333afab0bba1a4ad5e5

# moduli
pregfas_cfg.txt::%modules_dir%pregfas/::gestione pregfas::admin::d36923926ed59333afab0bba1a4ad5e5
lettere_sito_cfg.txt::%modules_dir%lettere_sito/::gestione archivio lettere::admin::d36923926ed59333afab0bba1a4ad5e5

# sondaggi/lotterie
lotteria_001.txt::%questions_dir%::configurazione "Sondaggio Mondiali di Calcio 2006"::admin::d36923926ed59333afab0bba1a4ad5e5
lotteria_001_ans.txt::%questions_dir%::risultati Mondiali di Calcio 2006::admin::d36923926ed59333afab0bba1a4ad5e5
lotteria_???.txt::%questions_dir%::configurazione sondaggio/lotteria::admin::d36923926ed59333afab0bba1a4ad5e5
lotteria_???_ans.php::%questions_dir%::risposte/soluzione::admin::d36923926ed59333afab0bba1a4ad5e5
