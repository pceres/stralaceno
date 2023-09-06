[help_config]
L'ordine delle sezioni nel file deve esser il seguente:
1) abilitazioni, 2) mansione_xxx, ..., N-1) mansione_yyy, N) elenco_task_atomici
 
E' fondamentale che, spostandosi dall'alto al basso, ciascuna sazione si appoggi a mansioni o task definiti 
nella parte sottostante.
 
Formato [abilitazioni] e [mansione_xxx]:
<tipo>::<nome>::<parametri>::<descrizione>::<gruppi_utenti_abilitati>
	<tipo>			: tipo di mansione:
				  task		: task atomico, <nome> deve essere presente in [elenco task_atomici]
				  mansione	: gruppo di task o di altre mansioni, ci deve essere [<nome>]
	<nome>			: nome del task o della mansione, senza spazi;
	<parametri>		: valori da assegnare agli eventuali parametri, separati da ";";
				  ad es. "album=200[56]" effettuara' la sostituzione nei parametri della mansione
				  o del task cui si fa riferimento ("album" diventa "200[56]"), e tali parametri
				  verificheranno (usando le regexp) le stringhe "2006" o "2005";
	<descrizione>		: descrizione del task o della mansione in forma libera;
	<gruppi_utenti_abilitati>: gruppi ed utenti (questi ultimi preceduti da "@") abilitati, separati da ";";
				   per le mansioni [mansione_xxx], tale campo e' vuoto;
 
Formato [elenco_task_atomici]:
<nome>::<parametri>::<descrizione>
	<nome>			: nome del task (come passato a funzione check_auth);
	<parametri>		: parametri del task (come passato a funzione check_auth);
	<descrizione>		: descrizione del task in forma libera
 

[abilitazioni]
task::admin_index::::Pagina principale dell'interfaccia amministrativa::admin
task::admin_modules::::Pagina dell'interfaccia amministrativa per la gestione dei moduli::admin
task::modifica_file_config::filename=.*::Visualizza e modifica generico file di configurazione::admin
task::scrivi_file_config::filename=.*::Scrivi generico file di configurazione::admin
task::gestione_download::::Gestione sezione download::admin

mansione::mansione_gestione_sezione::sezione=ciclismo::Gestione sezione "ciclismo"::admin,@don.ger
mansione::mansione_gestione_sezione::sezione=FC_caposele::Gestione sezione "FC Caposele"::admin
mansione::mansione_gestione_sezione::sezione=ambiente::Gestione sezione "Ambiente e natura"::admin,@angelo.ceres
mansione::mansione_gestione_sezione::sezione=studi_sport::Gestione sezione "Studi ed approfondimenti dello sport"::admin,@nick
mansione::mansione_gestione_sezione::sezione=.*::Gestione sezione generica::admin

mansione::mansione_modifica_file_configurazione::filename=siti_amici_cfg.txt::gestione file di configurazione del modulo Caposele sul Web::@michele,admin
mansione::mansione_modifica_file_configurazione::filename=albums.txt::gestione file di configurazione album::@paolo,admin


[mansione_gestione_sezione]
task::carica_articolo::sezione=sezione;id_art=.*::Caricare un articolo::
task::admin_index::::pagina principale dell'interfaccia amministrativa::
task::gestione_articoli::mode=edited;data=.*;sezione=sezione::modifica effettiva dell'articolo::
task::gestione_articoli::mode=edit;data=.*;sezione=sezione::pagina di modifica dell'articolo::
task::gestione_articoli::mode=cancel;data=.*;sezione=sezione::cancellazione effettiva dell'articolo::
task::gestione_articoli::mode=set_online_articles;data=.*;sezione=sezione::pubblicazione dell'articolo::
mansione::mansione_modifica_file_configurazione::filename=flash_news_sezione::Gestione flash news della sezione::
mansione::mansione_modifica_file_configurazione::filename=layout_left_sezione::Gestione layout sinistro della sezione::
mansione::mansione_modifica_file_configurazione::filename=layout_right_sezione::Gestione layout destro della sezione::

[mansione_gestione_modulo]
mansione::mansione_modifica_file_configurazione::filename=filename::gestione file di configurazione del modulo::

[mansione_modifica_file_configurazione]
task::admin_index::::pagina principale dell'interfaccia amministrativa::
task::admin_modules::::pagina principale dell'interfaccia amministrativa::
task::modifica_file_config::filename=filename::Visualizzazione e modifica file di configurazione::
task::scrivi_file_config::filename=filename::Scrittura del file di configurazione::

[elenco_task_atomici]
carica_articolo::sezione;id_art::carica articolo
admin_index::::pagina principale dell'interfaccia amministrativa
gestione_articoli::mode;data;sezione::gestione degli articoli
admin_modules::::pagina dell'interfaccia amministrativa per la gestione dei moduli::
modifica_file_config::filename::visualizza e modifica file di configurazione
scrivi_file_config::filename::scrivi file di configurazione
gestione_download::::gestione della sezione download
;item_name::gestione della sezione download
