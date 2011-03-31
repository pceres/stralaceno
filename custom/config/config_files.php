# File di configurazione dei diversi file di configurazione:
#
# Per ciascun cfgfile con nome <nome>, bisogna creare una struttura [nome], con ciascuna riga (un modulo puo' avere piu' file di configurazione) 
# nel formato:
# 	nomefile::cartella::descrizione_file::write_groups::read_groups::md5_password::link::logdir
#
#	nomefile: 		nome del file di configurazione, senza path; NON MODIFICARE!!!
#	cartella:		cartella contenente il file; NON MODIFICARE!!!
#	descrizione_file:	breve descrizione del file di configurazione;
#	write_groups:		gruppi abilitati ad accedere e modificare il file;
#	read_groups:		gruppi abilitati alla notifica della modifica del file;
#	md5_password:		hash md5 della password che abilita la scrittura del file;
#	link:			pagina del modulo, o di presentazione dei dati;
#	logdir:			cartella in cui scrivere something_changed.txt; NON MODIFICARE!!!
#

# root_admin
config_files.php::%config_dir%::configurazione generale::root_admin::root_admin::eef358de0c01694206eafe104006f44b::index.php::%config_dir%
modules_config.php::%config_dir%::moduli custom presenti nel sito::root_admin::root_admin::eef358de0c01694206eafe104006f44b::index.php::%config_dir%
users.php::%config_dir%::account al sito::root_admin::root_admin::eef358de0c01694206eafe104006f44b::index.php::%config_dir%

# admin
albums.txt::%config_dir%::configurazione album::admin,soci_ars::::4d419c10c5adc73ae6c3603c747264b3::index.php::%config_dir%
links.txt::%config_dir%::links::admin::::d36923926ed59333afab0bba1a4ad5e5::index.php::%config_dir%
layout_left.txt::%config_dir%::disposizione colonna sin. homepage::admin::::d36923926ed59333afab0bba1a4ad5e5::index.php::%config_dir%
layout_right.txt::%config_dir%::disposizione colonna dx. homepage::admin::::d36923926ed59333afab0bba1a4ad5e5::index.php::%config_dir%
layout_left_FC_caposele.txt::%config_dir%::colonna sin. FC Caposele::admin::::d36923926ed59333afab0bba1a4ad5e5::index.php?page=FC_caposele::%config_dir%
layout_right_FC_caposele.txt::%config_dir%::colonna dx. FC Caposele::admin::::d36923926ed59333afab0bba1a4ad5e5::index.php?page=FC_caposele::%config_dir%
layout_right_ambiente.txt::%config_dir%::colonna dx. ambiente::admin::::0cdaa4d3a5cfbe7cda4facf4d7501021::index.php?page=ambiente::%config_dir%
layout_left_ciclismo.txt::%config_dir%::colonna sin. ciclismo::admin::::d36923926ed59333afab0bba1a4ad5e5::index.php?page=ciclismo::%config_dir%

# moduli
lettere_sito_cfg.txt::%modules_dir%lettere_sito/::lettere al sito::admin::::d36923926ed59333afab0bba1a4ad5e5::custom/moduli/lettere_sito/lettere_sito.php::%modules_dir%
soci_cfg.txt::%modules_dir%soci/::elenco soci e news ARS::admin::soci_ars::d36923926ed59333afab0bba1a4ad5e5::custom/moduli/soci/soci.php::%modules_dir%
flash_news_cfg.txt::%modules_dir%flash_news/::News Flash::admin::::d36923926ed59333afab0bba1a4ad5e5::custom/moduli/flash_news/flash_news.php::%modules_dir%
flash_news_FC_caposele_cfg.txt::%modules_dir%flash_news/::News Flash sezione F.C. Caposele::admin::::d36923926ed59333afab0bba1a4ad5e5::custom/moduli/flash_news/flash_news.php?page=FC_caposele&module_data=FC_caposele::%modules_dir%
flash_news_ambiente_cfg.txt::%modules_dir%flash_news/::News Flash sezione ambiente::admin::::0cdaa4d3a5cfbe7cda4facf4d7501021::custom/moduli/flash_news/flash_news.php?page=ambiente&module_data=ambiente::%modules_dir%
siti_amici_cfg.txt::%modules_dir%siti_amici/::siti Caposele sul Web::admin::::d36923926ed59333afab0bba1a4ad5e5::custom/moduli/siti_amici/siti_amici.php::%modules_dir%
siti_amici_podismo_cfg.txt::%modules_dir%siti_amici/::Associazioni podistiche::admin::::d36923926ed59333afab0bba1a4ad5e5::custom/moduli/siti_amici/siti_amici.php?module_data=podismo::%modules_dir%
archivio_cfg.txt::%modules_dir%archivio/::archivio album e articoli::admin::::d36923926ed59333afab0bba1a4ad5e5::custom/moduli/archivio/archivio.php::%modules_dir%
archivio_FC_caposele_cfg.txt::%modules_dir%archivio/::archivio sezione FC Caposele::admin::::d36923926ed59333afab0bba1a4ad5e5::custom/moduli/archivio/archivio.php?page=FC_caposele&module_data=FC_caposele::%modules_dir%
classifica_campionato_cfg.txt::%modules_dir%classifica_campionato/::classifica e risultati ultima giornata::admin::::d36923926ed59333afab0bba1a4ad5e5::custom/moduli/classifica_campionato/classifica_campionato.php?page=FC_caposele::%modules_dir%

# sondaggi/lotterie
lotteria_001.txt::%questions_dir%::configurazione "Sondaggio Mondiali di Calcio 2006"::admin::::d36923926ed59333afab0bba1a4ad5e5::questions.php?id_questions=1::%config_dir%
lotteria_001_ans.php::%questions_dir%::risultati Mondiali di Calcio 2006::admin::::d36923926ed59333afab0bba1a4ad5e5::questions.php?id_questions=1&action=results::%config_dir%
lotteria_???.txt::%questions_dir%::configurazione questionario/sondaggio::admin::::d36923926ed59333afab0bba1a4ad5e5::questions.php::%config_dir%
lotteria_???_ans.php::%questions_dir%::risultati questionario/sondaggio::admin::::d36923926ed59333afab0bba1a4ad5e5::questions.php?action=results::%config_dir%
