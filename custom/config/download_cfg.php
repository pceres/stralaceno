[help_config]
File di configurazione della sezione download. La sezione e' strutturata ad albero, con elemento radice
il folder di nome [folder_root], contenente a sua volta elementi di tipo folder.
;
Formato [folder_xxx]:
<tipo>::<nome>::<caption>::<descrizione>::<parametri>::<abilitati_lettura>::<abilitati_scrittura>::<create_time>::<hits>
	<tipo>			: tipo di elemento nel folder:
				  folder	: un altro folder, in modo da generare una struttura ad albero
				  file		: file vero e proprio
				  link		: link a file esterno
	<nome>			: nome univoco dell'elemento;
	<caption>		: nome visualizzabile dell'elemento;
	<descrizione>		: descrizione dell'elemento;
	<parametri>		: parametri dell'elemento; ha significato diverso a seconda del valore di <tipo>:
					tipo = folder -> nome del folder nel filesystem
					tipo = file   -> nome o id nome del file
					tipo = link   -> link della risorsa
	<abilitati_lettura>	: utenti (preceduti da @) o gruppi abilitati in lettura
	<abilitati_scrittura>	: utenti (preceduti da @) o gruppi abilitati in scrittura
	<create_time>		: [hh:mm gg/mm/aaaa] data di creazione dell'item
	<hits>			: numero di scaricamenti (per file e link)
;

[folder_root]
folder::folder_ARS::ARS Amatori Running Sele::Sezione relativa all'associazione::Amatori Running Sele::::admin::22:07 04/07/2007::59
folder::folder_vari::Files vari::Sezione con risorse di varia natura::vari::::admin::22:07 04/07/2007::24

[folder_ARS]
folder::folder_Atto_costitutivo_statuto_verbali::Atti costitutivi, statuti e verbali::I documenti costitutivi dell'associazione "Amatori Running Sele": atti costitutivi, statuto, verbali delle assemblee, ecc.::Atti costitutivi::::admin::22:07 04/07/2007::7
folder::folder_Moduli::Moduli::Moduli di iscrizione ed altri documenti pubblici::Moduli::::admin::22:07 04/07/2007::15
folder::folder_Rendiconti::Rendiconti e relazioni::Rendiconti economici ufficiali dell'associazione::Rendiconti::::admin::22:07 04/07/2007::9
folder::folder_Sezione_soci::Sezione soci::Riservata ai membri dell'associazione "Amatori Running Sele"::Sezione soci::soci_ars::admin::22:07 04/07/2007::17
folder::folder_Prestazioni_sportive_soci::Risultati gare podistiche dei soci ARS::Elenco con i risultati podistici dei membri dell'associazione a partire dall'estate 2005::Prestazioni_soci::::admin::22:07 04/07/2007::59
folder::folder_Stralaceno::Stralaceno::Gara podistica Stralaceno::Stralaceno::::admin::23:46 21/10/2007::5

[folder_Atto_costitutivo_statuto_verbali]
file::file_atto_costitutivo_con_firme::Atto Costitutivo e Statuto ARS con firme::Documento originale scannerizzato::Atto Costit. Statuto ARS con firme.pdf::::admin::22:07 04/07/2007::2
file::file_atto::Atto Costitutivo in formato digitale::Atto costitutivo e statuto dell'associazione::Atto.pdf::::admin::22:07 04/07/2007::1

[folder_Stralaceno]
file::file_tuttotempi_87_94::Tuttotempi 87-94::Storico tempi relativo alle edizioni 1987-94::Stralaceno -Tuttotempi- 87-94.xls::::admin::20:49 22/10/2007::2
file::file_tuttotempi_95_99::Tuttotempi 95-99::Storico tempi relativo alle edizioni 1995-99::Stralaceno -Tuttotempi- 95-99.xls::::admin::20:49 22/10/2007::3
file::file_tuttotempi_00_04::Tuttotempi 00-04::Storico tempi relativo alle edizioni 2000-04::Stralaceno -Tuttotempi- 00-04.xls::::admin::20:49 22/10/2007::1
file::file_tuttotempi_05_XX::Tuttotempi 05-XX::Storico tempi relativo alle edizioni a partire dal 2005::Stralaceno -Tuttotempi- 05-XX.xls::::admin::20:49 22/10/2007::3

[folder_Moduli]
file::file_domanda_associazione_ars::Modulo Domanda di Associazione all'ARS::Per richiedere l'associazione all'ARS, compilare il modulo e consegnarlo al segretario amministrativo::Modulo Domanda di Associazione.pdf::::admin::22:07 04/07/2007::6
file::file_domanda_associazione_minorenni_ars::Modulo Domanda di Associazione Minorenni all'ARS::Come sopra, ma &egrave; richiesto l'assenso di un genitore::Modulo Domanda di Associazione minorenni.pdf::::admin::22:31 12/07/2007::4

[folder_Rendiconti]
file::file_rendiconto_e_relazione_ARS_2005::Rendiconto e Relazione ARS anno 2005::Rendiconto economico finanziario relativo all'esercizio 2005, e breve relazione::Rendiconto e Relazione anno 2005 ARS.pdf::::admin::22:07 04/07/2007::2
file::file_rendiconto_e_relazione_ARS_2006::Rendiconto e Relazione ARS anno 2006::Rendiconto economico finanziario relativo all'esercizio 2006, e breve relazione::Rendiconto e Relazione anno 2006 ARS.pdf::::admin::22:07 04/07/2007::1

[folder_Prestazioni_sportive_soci]
file::file_Risultati_gare_podistiche_xls::Risultati gare podistiche (xls)::Tabella scaricabile::Risultati gare podistiche.xls::::admin::15:31 05/11/2007::24
file::file_Risultati_gare_podistiche_pdf::Risultati gare podistiche (pdf)::Pdf scaricabile::Risultati gare podistiche.pdf::::admin::19:59 06/09/2007::8
file::file_scheda_gara::Scheda partecipazione gara podistica Ars::Modulo da compilare a seguito della partecipazione dei soci Ars ad una manifestazione podistica::Scheda gara.xls::::admin::22:30 21/10/2007::4

[folder_Sezione_soci]
folder::folder_richiesta_finanziamento_regione_2006::Richiesta di finanziamento alla regione Campania (2006)::Documentazione relativa alla richiesta di finanziamento alla regione Campania nel 2006::richiesta finanziamento regione::soci_ars::admin::22:07 04/07/2007::5

[folder_richiesta_finanziamento_regione_2006]
file::file_domanda_finanziamento::Domanda finanziamento::La domanda di finanziamento presentata::Domanda finanziamento.doc::soci_ars::admin::22:07 04/07/2007::2
file::file_scheda_progettuale::Scheda progettuale (allegato 1)::Scheda progettuale con il progetto di spesa::Scheda progettuale (all.1).doc::soci_ars::admin::22:07 04/07/2007::2
file::file_altri_allegati::Altri allegati (2-3-4-5-7)::Allegati vari, tra cui la descrizione dell'associazione::Altri allegati (2-3-4-5-7).doc::soci_ars::admin::22:07 04/07/2007::2
file::file_soci_meno_29_anni::Dichiarazione soci meno 29 anni (allegato 6)::Per presentare la domanda, bisogna che l'associazione abbia almeno il 50% di soci con meno di 29 anni::Dichiarazione soci meno 29 anni (all.6).doc::soci_ars::admin::22:07 04/07/2007::2
file::file_politiche_giovanili::AZIONE C Reg. Campania - politiche giovanili::Le politiche giovanili della regione Campania::AZIONE C Reg. Campania -politiche giovanili.doc::soci_ars::admin::22:07 04/07/2007::1

[folder_vari]
link::link_logo_ars::Logo ARS::Logo ufficiale dell'associazione "Amatori Running Sele"::http://localhost/work/ars/custom/album/varie/logo.jpg::::admin::22:07 04/07/2007::12
folder::folder_FC_caposele::Sezione F.C. Caposele::Documenti vari relativi all'F.C. Caposele::FC_caposele::::admin::23:07 03/12/2007::2

[folder_FC_caposele]
file::file_regolamento_interno_FC_caposele_07_08_doc::Regolamento interno 2007/08 (doc)::Regolamento interno dell'F.C. Caposele, anno 2007/08 (in formato .doc)::Regolamento Int. FC Cap. 07.08.doc::::admin::23:07 03/12/2007::0
file::file_regolamento_interno_FC_caposele_07_08_pdf::Regolamento interno 2007/08 (pdf)::Regolamento interno dell'F.C. Caposele, anno 2007/08 (in formato .pdf)::Regolamento Int. FC Cap. 07.08.pdf::::admin::23:30 03/12/2007::0
