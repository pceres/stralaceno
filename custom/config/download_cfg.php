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
folder::folder_ARS::ARS Amatori Running Sele::Sezione relativa all'associazione::Amatori Running Sele::::admin::22:07 04/07/2007::1555
folder::folder_vari::Files vari::Sezione con risorse di varia natura::vari::::admin::22:07 04/07/2007::1072

[folder_vari]
folder::folder_FC_caposele::Documenti relativi a FC Caposele::I documenti costitutivi dell'"FC Caposele": regolamento interno, ecc.::FC_caposele::::admin::22:07 06/07/2018::256
file::file_Tabella_europei_2016_xls::Tabella Europei 2016::Tabella Europei 2016 in formato Excel::Tabella europei 2016.xls::::admin::22:07 06/07/2018::330
file::file_Tabella_europei_2016_pdf::Tabella Europei 2016::Tabella Europei 2016 in formato pdf::Tabella europei 2016.pdf::::admin::22:07 06/07/2018::330
file::file_Tabella_mondiali_2018_xls::Tabella Mondiali 2018::Tabella Mondiali 2018 in formato Excel::Tabella mondiali 2018.xls::::admin::22:07 06/07/2018::424
file::file_Tabella_mondialieuropei_2021_odf::Tabella Europei 2021::Tabella Europei 2021 in formato ODF::Scheda_per_Europei_2021.ods::::admin::22:28 15/05/2021::204

[folder_FC_caposele]
file::file_Regolamento_Int_FC_Cap_07_08_pdf::Regolamento interno FC Caposele::Regolamento interno FC Caposele 2007/08 in formato pdf::Regolamento Int. FC Cap. 07.08.pdf::::admin::22:07 06/07/2018::264
file::file_Regolamento_Int_FC_Cap_07_08_doc::Regolamento interno FC Caposele::Regolamento interno FC Caposele 2007/08 in formato doc::Regolamento Int. FC Cap. 07.08.doc::::admin::22:07 06/07/2018::263

[folder_ARS]
folder::folder_Atto_costitutivo_statuto_verbali::Atti costitutivi, statuti, regolamenti e verbali::I documenti costitutivi dell'"Amatori Running Sele": atti costitutivi, statuto, regolamento, verbali delle assemblee, ecc.::Atti costitutivi::::admin::22:07 04/07/2007::1503
folder::folder_Moduli::Moduli::Moduli di iscrizione ed altri documenti pubblici::Moduli::::admin::22:07 04/07/2007::510
folder::folder_Rendiconti::Rendiconti e relazioni::Rendiconti economici ufficiali dell'associazione::Rendiconti::::admin::22:07 04/07/2007::1486
folder::folder_Sezione_soci::Sezione soci::Riservata ai membri dell'associazione "Amatori Running Sele"::Sezione soci::::::::45
