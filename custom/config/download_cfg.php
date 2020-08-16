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
folder::folder_ARS::ARS Amatori Running Sele::Sezione relativa all'associazione::Amatori Running Sele::::admin::22:07 04/07/2007::1171
folder::folder_vari::Files vari::Sezione con risorse di varia natura::vari::::admin::22:07 04/07/2007::687

[folder_vari]
folder::folder_FC_caposele::Documenti relatici a FC Caposele::I documenti costitutivi dell'"FC Caposele": regolamento interno, ecc.::FC_caposele::::admin::22:07 06/07/2018::20
file::file_Tabella_europei_2016_xls::Tabella Europei 2016::Tabella Europei 2016 in formato Excel::Tabella europei 2016.xls::::admin::22:07 06/07/2018::17
file::file_Tabella_europei_2016_pdf::Tabella Europei 2016::Tabella Europei 2016 in formato pdf::Tabella europei 2016.pdf::::admin::22:07 06/07/2018::16
file::file_Tabella_mondiali_2018_xls::Tabella Mondiali 2018::Tabella Mondiali 2018 in formato Excel::Tabella mondiali 2018.xls::::admin::22:07 06/07/2018::92

[folder_FC_caposele]
file::file_Regolamento_Int_FC_Cap_07_08_pdf::Regolamento interno FC Caposele::Regolamento interno FC Caposele 2007/08 in formato pdf::Regolamento Int. FC Cap. 07.08.pdf::::admin::22:07 06/07/2018::12
file::file_Regolamento_Int_FC_Cap_07_08_doc::Regolamento interno FC Caposele::Regolamento interno FC Caposele 2007/08 in formato doc::Regolamento Int. FC Cap. 07.08.doc::::admin::22:07 06/07/2018::12

[folder_ARS]
folder::folder_Atto_costitutivo_statuto_verbali::Atti costitutivi, statuti, regolamenti e verbali::I documenti costitutivi dell'"Amatori Running Sele": atti costitutivi, statuto, regolamento, verbali delle assemblee, ecc.::Atti costitutivi::::admin::22:07 04/07/2007::1047
folder::folder_Moduli::Moduli::Moduli di iscrizione ed altri documenti pubblici::Moduli::::admin::22:07 04/07/2007::299
folder::folder_Rendiconti::Rendiconti e relazioni::Rendiconti economici ufficiali dell'associazione::Rendiconti::::admin::22:07 04/07/2007::1043
folder::folder_Sezione_soci::Sezione soci::Riservata ai membri dell'associazione "Amatori Running Sele"::Sezione soci::soci_ars::admin::22:07 04/07/2007::278
folder::folder_Stralaceno::Stralaceno::Gara podistica Stralaceno::Stralaceno::::admin::23:46 21/10/2007::373
folder::folder_mia_cartella::Gare podistiche::I risultati della gare podistiche alle quali ha preso parte l'ARS::Gare 2008::::admin::15:02 14/03/2008::1057

[folder_Atto_costitutivo_statuto_verbali]
file::file_atto_costitutivo_con_firme::Atto Costitutivo e Statuto ARS con firme::Documento originale scannerizzato::Atto Costit. Statuto ARS con firme.pdf::::admin::22:07 04/07/2007::457
file::file_atto::Atto Costitutivo e Statuto in formato digitale::Atto costitutivo e statuto dell'associazione::Atto.pdf::::admin::14:39 18/03/2008::468
file::file_regolamento_elettorale::Regolamento elettorale::Atto che regolamenta le elezioni per il rinnovo delle cariche di governo dell'associazione::Regolamento elettorale ARS.pdf::::admin::23:59 07/01/2008::489
file::file_assemblea_08::Verbale Assemblea Soci del 22.04.2008::Rinnovo Consiglio Direttivo::Verbale Ass. Ord. approv. Rendiconto 2007 con firme.pdf::::admin::10:32 14/05/2008::548
file::file_direttivo_08::Verbale Consiglio Direttivo del 22.04.2008::Cariche nuovo Consiglio Direttivo::Verbale elez. Direttivo 2008 con firme.pdf::::admin::10:33 14/05/2008::472
file::file_ass_praticante::Verbale C. D. istituzione figura dell'Associato Praticante::Integrazione categorie di soci: Associato Praticante::Verbale Cons. Dir. istituz. associato praticante.pdf::::::::204

[folder_Stralaceno]
file::file_tuttotempi_87_94::Tuttotempi 87-94::Storico tempi relativo alle edizioni 1987-94::Stralaceno -Tuttotempi- 87-94.xls::::admin::20:49 22/10/2007::344
file::file_tuttotempi_95_99::Tuttotempi 95-99::Storico tempi relativo alle edizioni 1995-99::Stralaceno -Tuttotempi- 95-99.xls::::admin::20:49 22/10/2007::326
file::file_tuttotempi_00_04::Tuttotempi 00-04::Storico tempi relativo alle edizioni 2000-04::Stralaceno -Tuttotempi- 00-04.xls::::admin::20:49 22/10/2007::331
file::file_tuttotempi_05_XX::Tuttotempi 05-XX::Storico tempi relativo alle edizioni a partire dal 2005::Stralaceno -Tuttotempi- 05-XX.xls::::admin::20:49 22/10/2007::321

[folder_Moduli]
file::file_domanda_associazione_ars::Modulo Domanda di Associazione all'ARS::Per richiedere l'associazione all'ARS, compilare il modulo e consegnarlo al segretario amministrativo::Modulo Domanda di Associazione.pdf::::admin::22:07 04/07/2007::260
file::file_domanda_associazione_minorenni_ars::Modulo Domanda di Associazione Minorenni all'ARS::Come sopra, ma &egrave; richiesto l'assenso di un genitore::Modulo Domanda di Associazione minorenni.pdf::::admin::22:31 12/07/2007::247
file::file_domanda_assoc_praticante::Moduldo domanda Associato Praticante::Per richiedere l'iscrizione all'ARS come Associato Praticante::Modulo Domanda di Associazione Associato Praticante.pdf::::admin::10:17 08/05/2008::256

[folder_Rendiconti]
file::file_rendiconto_e_relazione_ARS_2005::Rendiconto e Relazione ARS anno 2005::Rendiconto economico finanziario relativo all'esercizio 2005, e breve relazione::Rendiconto e Relazione anno 2005 ARS.pdf::::admin::22:07 04/07/2007::552
file::file_rendiconto_e_relazione_ARS_2006::Rendiconto e Relazione ARS anno 2006::Rendiconto economico finanziario relativo all'esercizio 2006, e breve relazione::::::::::141
00
