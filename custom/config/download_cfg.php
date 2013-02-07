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
folder::folder_ARS::ARS Amatori Running Sele::Sezione relativa all'associazione::Amatori Running Sele::::admin::22:07 04/07/2007::876
folder::folder_vari::Files vari::Sezione con risorse di varia natura::vari::::admin::22:07 04/07/2007::446

[folder_ARS]
folder::folder_Atto_costitutivo_statuto_verbali::Atti costitutivi, statuti, regolamenti e verbali::I documenti costitutivi dell'"Amatori Running Sele": atti costitutivi, statuto, regolamento, verbali delle assemblee, ecc.::Atti costitutivi::::admin::22:07 04/07/2007::485
folder::folder_Moduli::Moduli::Moduli di iscrizione ed altri documenti pubblici::Moduli::::admin::22:07 04/07/2007::208
folder::folder_Rendiconti::Rendiconti e relazioni::Rendiconti economici ufficiali dell'associazione::Rendiconti::::admin::22:07 04/07/2007::487
folder::folder_Sezione_soci::Sezione soci::Riservata ai membri dell'associazione "Amatori Running Sele"::Sezione soci::soci_ars::admin::22:07 04/07/2007::157
folder::folder_Stralaceno::Stralaceno::Gara podistica Stralaceno::Stralaceno::::admin::23:46 21/10/2007::245
folder::folder_mia_cartella::Gare podistiche::I risultati della gare podistiche alle quali ha preso parte l'ARS::Gare 2008::::admin::15:02 14/03/2008::808

[folder_Atto_costitutivo_statuto_verbali]
file::file_atto_costitutivo_con_firme::Atto Costitutivo e Statuto ARS con firme::Documento originale scannerizzato::Atto Costit. Statuto ARS con firme.pdf::::admin::22:07 04/07/2007::239
file::file_atto::Atto Costitutivo e Statuto in formato digitale::Atto costitutivo e statuto dell'associazione::Atto.pdf::::admin::14:39 18/03/2008::244
file::file_regolamento_elettorale::Regolamento elettorale::Atto che regolamenta le elezioni per il rinnovo delle cariche di governo dell'associazione::Regolamento elettorale ARS.pdf::::admin::23:59 07/01/2008::283
file::file_assemblea_08::Verbale Assemblea Soci del 22.04.2008::Rinnovo Consiglio Direttivo::Verbale Ass. Ord. approv. Rendiconto 2007 con firme.pdf::::admin::10:32 14/05/2008::292
file::file_direttivo_08::Verbale Consiglio Direttivo del 22.04.2008::Cariche nuovo Consiglio Direttivo::Verbale elez. Direttivo 2008 con firme.pdf::::admin::10:33 14/05/2008::251
file::file_ass_praticante::Verbale C. D. istituzione figura dell'Associato Praticante::Integrazione categorie di soci: Associato Praticante::Verbale Cons. Dir. istituz. associato praticante.pdf::::admin::10:38 14/05/2008::248

[folder_Stralaceno]
file::file_tuttotempi_87_94::Tuttotempi 87-94::Storico tempi relativo alle edizioni 1987-94::Stralaceno -Tuttotempi- 87-94.xls::::admin::20:49 22/10/2007::215
file::file_tuttotempi_95_99::Tuttotempi 95-99::Storico tempi relativo alle edizioni 1995-99::Stralaceno -Tuttotempi- 95-99.xls::::admin::20:49 22/10/2007::190
file::file_tuttotempi_00_04::Tuttotempi 00-04::Storico tempi relativo alle edizioni 2000-04::Stralaceno -Tuttotempi- 00-04.xls::::admin::20:49 22/10/2007::191
file::file_tuttotempi_05_XX::Tuttotempi 05-XX::Storico tempi relativo alle edizioni a partire dal 2005::Stralaceno -Tuttotempi- 05-XX.xls::::admin::20:49 22/10/2007::202

[folder_Moduli]
file::file_domanda_associazione_ars::Modulo Domanda di Associazione all'ARS::Per richiedere l'associazione all'ARS, compilare il modulo e consegnarlo al segretario amministrativo::Modulo Domanda di Associazione.pdf::::admin::22:07 04/07/2007::158
file::file_domanda_associazione_minorenni_ars::Modulo Domanda di Associazione Minorenni all'ARS::Come sopra, ma &egrave; richiesto l'assenso di un genitore::Modulo Domanda di Associazione minorenni.pdf::::admin::22:31 12/07/2007::153
file::file_domanda_assoc_praticante::Moduldo domanda Associato Praticante::Per richiedere l'iscrizione all'ARS come Associato Praticante::Modulo Domanda di Associazione Associato Praticante.pdf::::admin::10:17 08/05/2008::162

[folder_Rendiconti]
file::file_rendiconto_e_relazione_ARS_2005::Rendiconto e Relazione ARS anno 2005::Rendiconto economico finanziario relativo all'esercizio 2005, e breve relazione::Rendiconto e Relazione anno 2005 ARS.pdf::::admin::22:07 04/07/2007::376
file::file_rendiconto_e_relazione_ARS_2006::Rendiconto e Relazione ARS anno 2006::Rendiconto economico finanziario relativo all'esercizio 2006, e breve relazione::Rendiconto e Relazione anno 2006 ARS.pdf::::admin::22:07 04/07/2007::595
file::file_rendiconto_e_relazione_ARS_2007::Rendiconto e Relazione ARS anno 2007::Rendiconto economico finanziario relativo all'esercizio 2007, e breve relazione::Rendiconto e Relazione anno 2007 ARS.pdf::::admin::10:24 08/04/2008::671
file::file_rendiconto_e_relazione_ARS_anno_2008::Rendiconto e Relazione ARS anno 2008::Rendiconto e Relazione ARS anno 2008 e breve relazione::Rendiconto e Relazione anno 2008 ARS.pdf::::admin::9:23 15/04/2009::267
file::file_rendiconto_e_relazione_ARS_anno_2009::Rendiconto e Relazione ARS anno 2009::Rendiconto e Relazione ARS anno 2009 e breve relazione::Rendiconto e Relazione anno 2009 ARS.pdf::::admin::15:21 04/04/2010::363
file::file_rendiconto_e_relazione_ARS_2010::Rendiconto e Relazione ARS anno 2010::Rendiconto e Relazione ARS anno 2010 e breve relazione::Rendiconto e Relazione ARS anno 2010.pdf::::admin::13:33 27/04/2011::325
file::file_rendiconto_e_relazione_ARS_2011::Rendiconto e Relazione ARS anno 2011::Rendiconto e Relazione ARS anno 2011::Rendiconto e Relazione ARS anno 2011.pdf::::admin::21:37 14/04/2012::169

[folder_Sezione_soci]
folder::folder_richiesta_finanziamento_regione_2006::Richiesta di finanziamento alla regione Campania (2006)::Documentazione relativa alla richiesta di finanziamento alla regione Campania nel 2006::richiesta finanziamento regione::soci_ars::admin::22:07 04/07/2007::6

[folder_richiesta_finanziamento_regione_2006]
file::file_domanda_finanziamento::Domanda finanziamento::La domanda di finanziamento presentata::Domanda finanziamento.doc::soci_ars::admin::22:07 04/07/2007::2
file::file_scheda_progettuale::Scheda progettuale (allegato 1)::Scheda progettuale con il progetto di spesa::Scheda progettuale (all.1).doc::soci_ars::admin::22:07 04/07/2007::2
file::file_altri_allegati::Altri allegati (2-3-4-5-7)::Allegati vari, tra cui la descrizione dell'associazione::Altri allegati (2-3-4-5-7).doc::soci_ars::admin::22:07 04/07/2007::2
file::file_soci_meno_29_anni::Dichiarazione soci meno 29 anni (allegato 6)::Per presentare la domanda, bisogna che l'associazione abbia almeno il 50% di soci con meno di 29 anni::Dichiarazione soci meno 29 anni (all.6).doc::soci_ars::admin::22:07 04/07/2007::2
file::file_politiche_giovanili::AZIONE C Reg. Campania - politiche giovanili::Le politiche giovanili della regione Campania::AZIONE C Reg. Campania -politiche giovanili.doc::soci_ars::admin::22:07 04/07/2007::1

[folder_vari]
link::link_logo_ars::Logo ARS::Logo ufficiale dell'associazione "Amatori Running Sele"::http://localhost/work/ars/custom/album/varie/logo.jpg::::admin::22:07 04/07/2007::134
folder::folder_FC_caposele::Sezione F.C. Caposele::Documenti vari relativi all'F.C. Caposele::FC_caposele::::admin::23:07 03/12/2007::268

[folder_FC_caposele]
file::file_regolamento_interno_FC_caposele_07_08_doc::Regolamento interno 2007/08 (doc)::Regolamento interno dell'F.C. Caposele, anno 2007/08 (in formato .doc)::Regolamento Int. FC Cap. 07.08.doc::::admin::23:07 03/12/2007::1926
file::file_regolamento_interno_FC_caposele_07_08_pdf::Regolamento interno 2007/08 (pdf)::Regolamento interno dell'F.C. Caposele, anno 2007/08 (in formato .pdf)::Regolamento Int. FC Cap. 07.08.pdf::::admin::23:07 03/12/2007::254

[folder_mia_cartella]
file::file_mio_file::Tempi atleti ARS::Tabella con tutti i tempi degli atleti ARS::Risultati gare podistiche.xls::::admin::17:02 28/01/2013::289
file::file_campestre_Eboli_2008::Campestre Uisp Eboli 17.02.08::Campestre Uisp Eboli 17.02.08::Campestre Uisp Eboli 17.02.08.pdf::::admin::15:04 07/04/2008::268
file::file_Agropoli_08::Agropoli Half Marathon 06.04.2008::Classifica Agropoli Half Marathon 06.04.2008::Agropoli Half Marathon 06.04.2008 Classifica.pdf::::admin::12:21 11/04/2008::282
file::file_vallesaccarda.s.giuseppe::II Quattro passi a San Giuseppe 01.05.2008::Classifica II Quattro passi a San Giuseppe::Quattro passi a S. Giuseppe.pdf::::admin::11:28 05/05/2008::203
file::file_stabia_08::XXII Notturna città di Stabia 31.05.2008::Classifica XXII Notturna di Stabia::Notturna città di Stabia  31.05.2008.pdf::::admin::10:03 04/06/2008::303
file::file_siano_08::X Notturna Sianese 21.06.2008::Classifica X Notturna Sianese::X Notturna Sianese, 21.06.2008.pdf::::admin::11:34 25/06/2008::314
file::file_polla_08::II Strapollese 05.07.2008::Classifica II Strapollese::II Strapollese, 05.07.2008.pdf::::admin::9:55 07/07/2008::616
file::file_agropoli_lau_08::IV Memorial P. Laureana 13.07.2008::Classifica IV Memorial Laureana::Memorial Laureana, Agropoli 13.07.2008.pdf::::admin::11:50 15/07/2008::356
file::file_melito_08::Podistica Anspi 13.09.2008::Classifica Podistica Anspi::Podistica Anspi Melito Irpino 13.09.2008.pdf::::admin::12:15 22/09/2008::321
file::file_vieri_09::VIII Vietri e dintorni 18.01.2009::Classifica VIII Vietri e dintorni::Vietri 18.01.2009.pdf::::admin::10:19 19/01/2009::228
file::file_paestum_09::V Sulle Orme di Filippide::Classifica V Sulle Orme di Filippide::V Sulle Orme di Filippide 02.05.2009.pdf::::admin::21:25 24/05/2009::175
file::file_san_giorgio_09::Scalata al Castello 24.05.2009::Scalata al Castello, Castel San Giorgio - Classifica a squadre - ::Scalata al Castello - Società 23.05.2009.pdf::::admin::21:36 24/05/2009::197
file::file_corri_laceno_09::Corri sull'Altopiano del Laceno 05.07.2009::Classifica Corri sull'Altopiano del Laceno 2009::Classifica_Corri_Laceno_09.pdf::::admin::13:16 07/07/2009::187
file::file_night_marathon_09::1° Night Marathon S.Maria di Castellabbate 18.07.2009::Classifica 1° Night Marathon S.Maria di Castellabbate 2009::night_marathon_2009.pdf::::admin::9:17 21/07/2009::185
file::file_strasalerno_09::Strasalerno Half Marathon 18.10.2009::Classifica Strasalerno Half Marathon 18.10.2009::Strasalerno09.pdf::::admin::16:26 21/10/2009::263
file::file_sicignano_09::III Memorial B.Germano 08.11.2009::Classifica III Memorial B.Germano 08.11.2009::Sicignano_09.pdf::::admin::10:27 09/11/2009::152
file::file_Paestum10::VI Sulle Orme di Filippide::Classifica VI Sulle Orme di Filippide::Paestum10.pdf::::admin::9:48 11/05/2010::125
file::file_Sicignano2011::V Memorial B.Germano 06.11.2011::Classifica V Memorial B.Germano 06.11.2011::Sicignano2011.pdf::::admin::11:18 07/11/2011::55
file::file_Amalfi_2012::I Trail delle Ferriere 15.04.2012::Classifica I Trail delle Ferriere 15.04.2012::Amalfi_2012.pdf::::admin::10:58 16/04/2012::60
file::file_Vietri2013::Vietri e dintorni trail 2013::Classifica Vietri e dintorni trail 2013::Vietri2013.pdf::::admin::10:23 21/01/2013::15
file::file_na13::Mezza Maratona di Napoli 2013::Classifica Mezza Maratona di Napoli 2013::na13.pdf::::admin::9:19 30/01/2013::13
