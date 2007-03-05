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

#mansione::mansione_gestione_sezione::sezione=ciclismo::Gestione sezione "ciclismo"::@alex,@ceres,@don.ger
mansione::mansione_gestione_sezione::sezione=ciclismo::Gestione sezione "ciclismo"::@ceres,@don.ger
mansione::mansione_gestione_sezione::sezione=FC_caposele::Gestione sezione "FC Caposele"::@alex,@ceres
mansione::mansione_gestione_sezione::sezione=ambiente::Gestione sezione "Ambiente e natura"::@alex,@ceres,@angelo.ceres
mansione::mansione_gestione_sezione::sezione=.*::Gestione sezione generica::@alex,@ceres

[mansione_gestione_sezione]
task::carica_articolo::sezione=sezione;id_art=.*::Caricare un articolo::
task::admin_index::::pagina principale dell'interfaccia amministrativa::
task::gestione_articoli::mode=edited;data=.*;sezione=sezione::modifica effettiva dell'articolo::
task::gestione_articoli::mode=edit;data=.*;sezione=sezione::pagina di modifica dell'articolo::
task::gestione_articoli::mode=cancel;data=.*;sezione=sezione::cancellazione effettiva dell'articolo::
task::gestione_articoli::mode=set_online_articles;data=.*;sezione=sezione::pubblicazione dell'articolo::

[elenco_task_atomici]
carica_articolo::sezione;id_art::carica articolo
admin_index::::pagina principale dell'interfaccia amministrativa
gestione_articoli::mode;data;sezione::gestione degli articoli
