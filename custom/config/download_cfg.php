[help_config]
File di configurazione della sezione download. La sezione e' strutturata ad albero, con elemento radice
il folder di nome [folder_root], contenente a sua volta elementi di tipo folder.
 
Formato [folder_xxx]:
<tipo>::<nome>::<descrizione>::<parametri>::<gruppi_utenti_abilitati>
	<tipo>			: tipo di elemento nel folder:
				  folder	: un altro folder, in modo da generare una struttura ad albero
				  file		: file vero e proprio
				  link		: link a file esterno
	<nome>			: nome univoco dell'elemento;
	<descrizione>		: descrizione dell'elemento;
	<parametri>		: parametri dell'elemento; ha significato diverso a seconda del valore di <tipo>:
					tipo = folder -> nome del folder nel filesystem
					tipo = file   -> nome o id nome del file
					tipo = link   -> link della risorsa
	<gruppi_utenti_abilitati>:utenti (preceduti da @) o gruppi abilitati in lettura
 

[folder_root]
folder::folder_ARS::ARS Amatori Running Sele::Amatori Running Sele::soci_ars 
folder::folder_vari::Files vari::vari::

[folder_ARS]
folder::folder_Atti_costitutivi::Atti costitutivi::Atti costitutivi::
folder::folder_Moduli::Moduli::Moduli::
folder::folder_Rendiconti::Rendiconti e relazioni::Rendiconti::soci_ars
folder::folder_Sezione_soci::Sezione soci::Sezione soci::soci_ars
folder::folder_Prestazioni_soci::Risultati gare podistiche::Prestazioni_soci::

[folder_Atti_costitutivi]
file::file_atto_costitutivo_con_firme::Atto Costitutivo e Statuto ARS con firme::Atto Costit. Statuto ARS con firme.pdf::
file::file_atto::Atto Costitutivo in formato digitale::Atto.pdf::

[folder_Moduli]
file::file_domanda_associazione_ars::Modulo Domanda di Associazione all'ARS::Modulo Domanda di Associazione.pdf::

[folder_Rendiconti]
file::file_rendiconto_e_relazione_ARS_2005::Rendiconto e Relazione ARS anno 2005::Rendiconto e Relazione anno 2005 ARS.pdf::soci_ars
file::file_rendiconto_e_relazione_ARS_2006::Rendiconto e Relazione ARS anno 2006::Rendiconto e Relazione anno 2006 ARS.pdf::soci_ars

[folder_Prestazioni_soci]
file::file_Risultati_gare_podistiche_xls::Risultati gare podistiche (xls)::Risultati gare podistiche.xls::
file::file_Risultati_gare_podistiche_pdf::Risultati gare podistiche (pdf)::Risultati gare podistiche.pdf::

[folder_Sezione_soci]
folder::folder_richiesta_finanziamento_regione::Richiesta di finanziamento alla regione Campania::richiesta finanziamento regione::soci_ars

[folder_richiesta_finanziamento_regione]
file::file_domanda_finanziamento::Domanda finanziamento::Domanda finanziamento.doc::soci_ars
file::file_scheda_progettuale::Scheda progettuale (allegato 1)::Scheda progettuale (all.1).doc::soci_ars
file::file_altri_allegati::Altri allegati (2-3-4-5-7)::Altri allegati (2-3-4-5-7).doc::soci_ars
file::file_soci_meno_29_anni::Dichiarazione soci meno 29 anni (allegato 6)::Dichiarazione soci meno 29 anni (all.6).doc::soci_ars
file::file_politiche_giovanili::AZIONE C Reg. Campania - politiche giovanili::AZIONE C Reg. Campania -politiche giovanili.doc::soci_ars

[folder_vari]
link::link_logo_ars::Logo ARS::http://localhost/work/ars/custom/album/varie/logo.jpg::
file::file_html::file HTML::file.html::
