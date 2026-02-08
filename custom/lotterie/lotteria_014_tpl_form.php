<?php

//
// input impliciti:
//
// $web_title		: titolo della pagina
// $lotteria_nome	: nome esteso della lotteria/sondaggio
// $filename_css	: url del foglio di stili css
//
// $id_questions	: id della lotteria/questionario
// $auth_token		: chiave unica di identificazione
//
// $admin_mode		: 1 -> si e' in modalita' amministrativa, richiedi la data di giocata
//
// $messaggio_stato_sondaggio	: messaggio di stato della lotteria, basato sulle date di apertura e chiusura ([msg_date] nel file di conf.)
// $flag_show_results	: 1 -> si vuole far visualizzare il link alle giocate gia' effettuate
//
//
// input da metodi POST o GET:
//
// $info_mode		: 1 -> si e' in modalita' visualizzazione, non viene visualizzato il tasto 'Gioca'
//

//
// configurazione
//

$enable_check_Caposele = 0; // 1 --> fai il test di caposelitudine; 0 -> nessun test

//
// parte generica
//

if (@$admin_mode)
{
	$admin_mode = 1;
	$admin_path_correction = "../";
}
else
{
	$admin_mode = 0;
	$admin_path_correction = "";
}

// preparazione nomi di file relativi (con correzione al path per la modalita' amministrativa)
$action = $admin_path_correction."questions.php";	// da non modificare
$javascript_library = $admin_path_correction."questions.js";	// da non modificare

// caricamento, se necessario, della libreria
if (strlen(strpos($_SERVER['SCRIPT_FILENAME'],"questions.php")) == 0)
{
	// se non si sta venendo da questions.php, carica la libreria
	@$admin_mode = $_REQUEST['admin_mode'];					// azione da eseguire
	if ($admin_mode)
	{
		require_once('../libreria.php');
	}
	else
	{
		require_once('../../libreria.php');
	}
}

if (empty($info_mode))
{
	$info_mode = $_REQUEST['info_mode'];					// azione da eseguire
	$info_mode = sanitize_user_input($info_mode,'plain_text',array());	// verifica di sicurezza
}

?><!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 TRANSITIONAL//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>

<head>

<title><?php echo $web_title ?> - <?php echo $lotteria_nome ?></title>
<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1">
<meta name="GENERATOR" content="Kate">
<meta name="description" content="<?php echo $lotteria_nome; ?>">
<meta name="keywords" content="lotteria, questionario, <?php echo $lotteria_nome ?>, sondaggio, classifica">
<style type="text/css">@import "<?php echo $filename_css ?>";</style>


<!--
// 
// 2) Inserire qui sotto gli stili della pagina html customizzata
//
-->
<style type="text/css">

BODY,DIV,TABLE,THEAD,TBODY,TFOOT,TR,TH,TD,P,INPUT
{
	font-family:"Arial";
	font-size:x-small;
}

</style>
<!-- 
// 
// Fine degli stili della pagina html customizzata
//
-->


</head>


<?php
// 
// 3) eventuali customizzazioni sulla proprieta' onLoad
// 
?>
<body link=blue vlink=purple onLoad="document.forms['question_form']['question_67'].value='0'; 
if (document.forms['question_form']['data_giocata']) {document.forms['question_form']['data_giocata'].value='';}
if (document.forms['question_form']['temp_silaritudine']) {document.forms['question_form']['temp_silaritudine'].value='No';}"> 
<!-- inizializza alcuni campi sensibili:
	question_67 (nome del campo nascosto: se ci sono i sedicesimi (n=6), oppure question_35 (n=5) se si arriva agli ottavi. Più in generale, (2^n-1)+5-1 )
	data_giocata: svuota data in cui viene effettuata la giocata
	temp_silaritudine: di default, la giocata non è di un giocatore di Caposele
-->


<!--
//
// Script per il check dei campi 
//
-->

<!-- includi gli script generici in Javascript per leggere i campi qustion_xx del form -->
<script type="text/javaScript" src="<?php echo $javascript_library ?>"></script>



<?php
//
// 4) script specializzati per la lotteria/sondaggio (quando si preme il tasto "gioca", verra' chiamata la funzione check_input
//
?>
<!-- script specializzati per la lotteria/sondaggio -->
<SCRIPT type=text/javascript>
<!-- 

archivio_domande = Array(
<?php

$num_domande = 3*0;		// numero domande da porre
$num_allowed_errors = 1;	// numero massimo di errori accettabili per superare comunque la verifica

$archivio_domande = Array(
	Array(1,"Per chi si reca in gita alla Mauta e' consigliabile:",
			Array("Portare il costume per un bagno nelle acque del Sele","Prolungare la gita fino alle rovine romane di Valle di Porco","Programmare un'escursione in cima al Calvello")							,"001"	),

	Array(2,"Quando e' pscrai?",
			Array("Ieri","Dopodomani","Oggi","Domani")								,"0100"	),

	Array(3,"Cosa significa la parola 'ngimma?"		,
			Array("ginnastica","giorno","sopra","gomma")	 						,"0010" ),

	Array(4,"Qual'e' il Santo patrono di Caposele?"	,
			Array("Lorenzo","Gerardo","Pasquale","Rocco")							,"1000" ),

	Array(5,"Cosa significa la parola 'nbieri?"		,
			Array("Carabinieri","Ieri","Bicchieri","Sotto")  						,"0001" ),

	Array(6,"Vagava per le strade di Caposele"		,
			Array("Peppe il francese","Ciccio l'americano","Antonio l'africano","Gerardo l'austriaco")	,"0001"),

	Array(7,"Quale dei seguenti non e' un quartiere di Caposele?"		,
			Array("Portella","Pianello","Campo Piano","Castello","Sanita'")			,"00100"),

	Array(8,"In che mese si festeggia la Madonna della Sanita'?"		,
			Array("Luglio","Agosto","Settembre","Ottobre")  						,"0100"),

	Array(9,"Da dove posso vedere la preta r' li cuorvi?",
			Array("dalla Castagneta","da Pasano","da Persano","da San Giovanni")	,"1000"),

	Array(10,"Dove vennero ubicate le scuole elementari e medie di Caposele dopo il terremoto del 1980?"  ,
			Array("ai Piani","a li fuossi","alla Sanita'","al ponte")				,"0010"),

	Array(11,"Nel rispetto della tradizione, nell'anno 1974 a Caposele non e' nato nessun bambino con il nome di:"		  ,
			Array("Giuseppe","Rocco","Antonio","Aniello")							,"0001"),

	Array(12,"Che sport praticava Manliuccio?"		   ,
			Array("Tennis","Corsa","Calcio","Ciclismo")								,"0010" ),

//	Array(13,"Dov'e' Tredogge?"	,
//			Array("Alla foce del fiume","Vicino a Materdomini","In montagna","Vicino alle sorgenti del Sele")	,"0001"	),

//	Array(14,"Chi era il sindaco di Caposele nel 2005?"  ,
//			Array("Alfonso Merola","Giuseppe Melillo","Antonio Corona","Agostino Montanari")		,"0100" ),

);





// scegli num_domande indici tra quelli disponibili
$lista_old = range(1,count($archivio_domande));
$lista_new = Array();
while (count($lista_new) < $num_domande)
{
	$ind = rand(1,count($lista_old));
	while (empty($lista_old[$ind-1]))
	{
		$ind = rand(1,count($lista_old));
	}
	array_push($lista_new,$lista_old[$ind-1]);
	$lista_old[$ind-1] = '';
}


// seleziona le domande scelte
$archivio_domande_scelte = Array();
foreach ($lista_new as $id)
{
	array_push($archivio_domande_scelte,$archivio_domande[$id-1]);
}

// stampa le domande
foreach ($archivio_domande_scelte as $id_domanda => $dati_domanda)
{
	echo "\tArray({$dati_domanda[0]},\"{$dati_domanda[1]}\",";
	echo "Array(";
	
	foreach ($dati_domanda[2] as $id => $answer)
	{
		if ($id > 0)
		{
			echo ",";
		}
		echo "\"$answer\"";
	}
	
	echo ")";
	echo ",\"{$dati_domanda[3]}\")";
	if ($id_domanda < count($archivio_domande_scelte)-1)
	{
		echo ",";
	}
	echo "\n";
}
?>
);

var myWind; // handle alla (eventuale) popup window per breve questionario

function ask_question(tag_feedback,question_id,question,answers,right_ans,question_pos,num_domande)
{
// alert(tag_feedback+","+question_id+","+question+","+answers+","+right_ans+","+question_pos+","+num_domande);
// alert('5: '+myWind);
// 	if (!myWind || myWind.closed)
	if (1)
	{
		title_bg 	= "#ffffc0";	// sfondo titolo (giallino)
		odd_row_bg 	= "#e0ffe0";	// sfondo righe dispari (celestino)
		even_row_bg 	= "#e0e0ff";	// sfondo righe pari (verdino)
		
		titolo	= "Dom. " + question_pos + "\\" + num_domande;
		
		var win_width = 500;				// larghezza finestra
		var win_height = (100+50*answers.length);	// altezza finestra
		var win_left = Math.floor((screen.width-win_width)/2);
		var win_top = Math.floor((screen.height-win_height)/2);
		
// alert('5_2: '+win_top);
				window_title = "finestra_"+question_id+"_"+question_pos;
		myWind = window.open("",window_title,"width=" + win_width + ",height=" + win_height + ",top=" + win_top + ",left=" + win_left+", status=off, menubar=off, toolbar=off, scrollbar=off, resizable=off");
// alert('6: '+myWind.closed+" "+titolo);
		
		myWind.document.write("<!DOCTYPE HTML PUBLIC \"-//W3C//DTD HTML 4.01 TRANSITIONAL//EN\" \"http://www.w3.org/TR/html4/loose.dtd\">\n");
		myWind.document.write("<html>\n");
		myWind.document.write("<head>\n");
		myWind.document.write("<title>"+titolo+"<"+"/title>\n");
		myWind.document.write("<style type=\"text/css\">@import \"<?php echo $admin_path_correction; ?>custom/config/style.css\";<"+"/style>\n");
		
		
		myWind.document.write("<"+"/head>\n\n");
		myWind.document.write("<body onLoad=\"self.focus(); \">\n");
//		myWind.document.write("<scr"+"ipt type=\"text/javascript\" src=\"<?php echo $javascript_library; ?>\"><"+"/script>\n\n");
		
		myWind.document.write("<scr"+"ipt type=\"text/javascript\">\n\n");

		// script per leggere la scelta dell'utente (o stringa vuota in sua mancanza)
		myWind.document.write("function read_radio(radio_button_handle){\n");
		myWind.document.write("	for (var i = 0; i < radio_button_handle.length; i++)\n");
		myWind.document.write("	{\n");
		myWind.document.write("		if (radio_button_handle[i].checked) {return radio_button_handle[i].value; }\n");
		myWind.document.write("	}\n");
		myWind.document.write("	return \"\";\n");
		myWind.document.write("} // end function read_radio\n\n");
		
		// script per formattare un numero in modo da avere sempre 2 caratteri)
		myWind.document.write("function formatta(numero)\n");
		myWind.document.write("{\n");
		myWind.document.write("	if (numero<=9) { ks = '0'+numero; }\n");
		myWind.document.write("	else { ks = numero; }\n");
		myWind.document.write("	return ks;\n");
		myWind.document.write("} // end function formatta\n\n");
		
		myWind.document.write("<"+"/script>\n\n");



		
		myWind.document.write("<FORM NAME=\"input\" action=\"post\"><br>\n");
		
		myWind.document.write("<div style=\"background-color:"+title_bg+";\">\n");
		myWind.document.write(question_pos+'/'+num_domande+') '+question+"<br><br>\n");
		myWind.document.write("<"+"/div>\n");
		
		for (i = 0; i < answers.length; i++)
		{
			switch (i-Math.floor(i/2)*2)
			{
			case 0:
				row_bg = even_row_bg; // sfondo righe dispari
				break;
			case 1:
				row_bg = odd_row_bg; // sfondo righe pari
				break;
			}
			
			myWind.document.write("<div style=\"background-color:"+row_bg+";\">\n");
			myWind.document.write("<INPUT TYPE=\"radio\" NAME=\"radio\" VALUE=\""+(i+1)+"\">"+answers[i]+"<br>\n");
			myWind.document.write("<"+"/div>\n");
		}
		// alert('"+tag_feedback+"');
		myWind.document.write("<br><hr>\n");
		myWind.document.write("<INPUT TYPE=\"button\" NAME=\"storage\" VALUE=\"Rispondi\" \n");
		myWind.document.write(" onClick=\"right_ans='"+right_ans+"'; res = read_radio(self.document.forms['input']['radio']);\nif (!res)  {alert('Rispondi prima!');return false;} \nres2=right_ans.substring(res-1,res); \nif (res2<0) {res2=-res2;} \nnew_value=formatta("+question_id+")+formatta(res)+formatta(res2);\nself.opener.document.forms['question_form']['"+tag_feedback+"'].value = self.opener.document.forms['question_form']['"+tag_feedback+"'].value+new_value; \nself.window.close(); \n \nself.opener.check_input(self.opener.document.forms['question_form']);\" \n>\n");
		myWind.document.write("<"+"/FORM>\n");
		myWind.document.write("<"+"/body><"+"/html>\n");
		myWind.document.close();
		myWind.focus();
// alert('6_3: finished window');
	}
	else
	{
		// bring existing subwindow to the front
		myWind.focus();
	}
	
	return false;
	
} // end function ask_question


function check_input(f)
{
// alert('1: '+f['question_67'].value);
// Questa funzione verifica la correttezza della giocata prima di inviare i dati per il salvataggio
	

	// configurazione errori ammissibili
	warn_on_errors = new Array; // 0 -> nessun warning (viene saltata la verifica)
	allow_errors = new Array;   // 0 -> l'errore non permette il salvataggio; 1 -> viene visualizzato soltanto un warning
	
	warn_on_errors = 1; // mettere a 0 per saltare del tutto la verifica sui gironi
	allow_errors   = 0; // mettere a 0 per impedire di giocare con un errore alla regola "N_min..N_max squadre per ciascun girone"
	
	// leggi tutti i campi del tipo question_xx
	list = read_form_fields(f);
	if ( (list.length == 1) & (list[0] == '%errore%') )
	{
		return false;
	}

	// verifica congruenza delle risposte
	//alert('check 1 - numero campi totali: '+list.length);
	risposte_ok = true;
	messaggio_errore = 'Messaggio di errore!';
	
	// ripartizione nelle diverse classi
	list_M = new Array; // sedicesimi
	list_W = new Array;	// ottavi
	list_Q = new Array;	// quarti
	list_S = new Array;	// semifinali
	list_F = new Array;	// finali
	list_C = new Array;	// vincitore
	list_o = new Array;	// others
	
	for (i = 0; i < list.length; i++)
	{
		
		if (i < 32)
		{
			list_M[list_M.length] = list[i];
		}
		else if (i < 32+16)
		{
			list_W[list_W.length] = list[i];
		}
		else if (i < 32+16+8)
		{
			list_Q[list_Q.length] = list[i];
		}
		else if (i < 32+16+8+4)
		{
			list_S[list_S.length] = list[i];
		}
		else if (i < 32+16+8+4+2)
		{
			list_F[list_F.length] = list[i];
		}
		else if (i < 32+16+8+4+2+1)
		{
			list_C[0] = list[i];
		}
		else
		{
			list_o[list_o.length] = list[i];
		}
	}
	
	// verifica correttezza squadre ammesse
	//alert('check 2 - numero campi per gironi: M ('+list_M.length+') - W ('+list_W.length+') - Q ('+list_Q.length+') - S ('+list_S.length+') - F ('+list_F.length+') - C ('+list_C.length+')');
	gironeA = new Array("Messico", "Corea del Sud", "Sudafrica", "Vincitore_D");
	gironeB = new Array("Canada", "Svizzera", "Qatar", "Vincitore_A");
	gironeC = new Array("Brasile", "Marocco", "Scozia", "Haiti");
	gironeD = new Array("USA", "Australia", "Paraguay", "Vincitore_C");
	gironeE = new Array("Germania", "Ecuador", "Costa d'Avorio", "Curaçao");
	gironeF = new Array("Olanda", "Giappone", "Tunisia", "Vincitore_B");
	gironeG = new Array("Belgio", "Iran", "Egitto", "Nuova Zelanda");
	gironeH = new Array("Spagna", "Uruguay", "Arabia Saudita", "Capo Verde");
	gironeI = new Array("Francia", "Senegal", "Norvegia", "Vincitore_2");
	gironeJ = new Array("Argentina", "Austria", "Algeria", "Giordania");
	gironeK = new Array("Portogallo", "Colombia", "Uzbekistan", "Vincitore_1");
	gironeL = new Array("Inghilterra", "Croazia", "Panama", "Ghana");
	
	
	gironi = new Array(gironeA, gironeB, gironeC, gironeD, gironeE, gironeF, gironeG, gironeH, gironeI, gironeJ, gironeK, gironeL);
//	gironi = new Array(gironeA, gironeB, gironeC, gironeD, gironeE, gironeF, gironeG, gironeH);
// 	gironi = new Array(gironeA, gironeB, gironeC, gironeD);
	
	
	num_per_girone_min = 2;
	num_per_girone_max = 3;
	
	const fasi = [
		{ nome_fase:"sedicesimi", lista_corrente:list_M, lista_precedente:null,   num_min:2, num_max:3, check_gironi:true,  warn_on_errors_flag:warn_on_errors, allow_errors_flag:allow_errors },
		{ nome_fase:"ottavi",	  lista_corrente:list_W, lista_precedente:list_M, num_min:0, num_max:0, check_gironi:false, warn_on_errors_flag:false, allow_errors_flag:true },
		{ nome_fase:"quarti",	  lista_corrente:list_Q, lista_precedente:list_W, num_min:0, num_max:0, check_gironi:false, warn_on_errors_flag:false, allow_errors_flag:true },
		{ nome_fase:"semifinali", lista_corrente:list_S, lista_precedente:list_Q, num_min:0, num_max:0, check_gironi:false, warn_on_errors_flag:false, allow_errors_flag:true },
		{ nome_fase:"finale",	  lista_corrente:list_F, lista_precedente:list_S, num_min:0, num_max:0, check_gironi:false, warn_on_errors_flag:false, allow_errors_flag:true },
		{ nome_fase:"vincitrice", lista_corrente:list_C, lista_precedente:list_F, num_min:0, num_max:0, check_gironi:false, warn_on_errors_flag:false, allow_errors_flag:true }
	];
	
	for (fase of fasi)
	{
		//alert('check:'+fase.nome_fase);
		if (!check_fase(gironi,fase))
		{
			//alert('Check '+fase.nome_fase+': false');
			return false;
		}
	}
	
	
	// gestisci dati anagrafici
	auth_nome 	= list_o[0];
	auth_cognome 	= list_o[1];
	auth_nato 	= list_o[2];
	auth_provenienza= list_o[3];
	auth_caposelese = list_o[4];

	if ( (!auth_nome) || (auth_nome.length < 1) )
	{
		alert('Devi inserire il tuo nome!');
		return false;
	}

	if ( (!auth_cognome) || (auth_cognome.length < 1) )
	{
		alert('Devi inserire il tuo cognome!');
		return false;
	}

	if ( (!auth_nato) || (auth_nato.length < 1) )
	{
		alert('Devi inserire la tua data di nascita!');
		return false;
	}

	if ( (!auth_provenienza) || (auth_provenienza.length < 1) )
	{
		alert('Devi specificare la tua provenienza!');
		return false;
	}

	tag_feedback = 'question_67'; // nome del campo nascosto, con cui interagisce la popup window


	// verifica formato data (auth_nato)
	formato_data = /^[0-9]{2}\/[0-9]{2}\/[0-9]{4}$/;
	if (!auth_nato.match(formato_data))
	{
		alert("Il formato della data di nascita deve essere gg/mm/aaaa (es. 31/12/1974)!");
		return false;
	}
	else
	{
		temp = auth_nato.match(formato_data);
		auth_nato_giorno = temp[0].substr(0,2);
		auth_nato_mese   = temp[0].substr(3,2);
		auth_nato_anno   = temp[0].substr(6,4);
		
		// verifica che giorno, mese ed anno portino ad una data corretta
		flg_bad_date = check_day_month_year(auth_nato_giorno,auth_nato_mese,auth_nato_anno);
		if (flg_bad_date)
		{
			alert("La data indicata non e\' corretta! (giorno:"+auth_nato_giorno+",mese:"+auth_nato_mese+",anno:"+auth_nato_anno+")");
			return false;
		}
	}
	
// alert('3: '+auth_provenienza.toUpperCase(auth_provenienza));

	// gestione Caposelesi
	if (!<?php echo $admin_mode; ?> && <?php echo $enable_check_Caposele; ?>)
	{
		switch (auth_provenienza.toUpperCase(auth_provenienza))
		{
		case 'CAPOSELE':
		case 'CAPUT SYLARIS':
		case 'MATERDOMINI':
		case 'PORTELLA':
		case 'BUONINVENTRE':
		case 'PASANO':
			num_domande = <?php echo $num_domande; ?>; // numero di domande da porre
			num_allowed_errors = <?php echo $num_allowed_errors; ?>; // numero di errori consentiti per superare comunque la verifica
			len_risposta = 6;// numero di caratteri per ciascuna risposta (2 per la domanda, 2 per la risposta, 2 per esito)
			
			//alert(auth_caposelese.length);
			if ( auth_caposelese.length < 1+num_domande*len_risposta )
			{
				// la prima volta, visualizza un messaggio
				if ( auth_caposelese.length == 1 )
				{
					// ks_provenienza = auth_provenienza;
					ks_provenienza = 'Caposele';
					
					// messaggio sul numero max. di errori consentiti
					if (num_allowed_errors == 0)
					{
						msg_allowed_errors = '';
					}
					else
					{
						msg_allowed_errors = 'Il numero max. di errori consentito e\': '+num_allowed_errors+'\r\n';
					}
					
					msg = 'Ti faccio qualche domanda aggiuntiva per verificare che tu sia di '+ks_provenienza+'!\r\n'+msg_allowed_errors+'Premi OK per cominciare...';
					
					alert(msg);
				}
				
				pos_domanda = (auth_caposelese.length-1)/len_risposta+1;
				dati_domanda = archivio_domande[pos_domanda-1];
	//alert('4: '+pos_domanda);
				caposelese_doc = ask_question(tag_feedback,dati_domanda[0],dati_domanda[1],dati_domanda[2],dati_domanda[3],pos_domanda,num_domande);
				
				// esci, ci pensera' l'utente, dalla finestra pop-up, a richiamare questa funzione
				return false;
			}
			else
			{
				// hai gia' risposto alle num_domande domande
				right_answers = 0;
				for (i = 0; i < num_domande; i++)
				{
					// se nell'ultima posizione di ciascun esito c'e' 1, la risposta e' corretta
					if (auth_caposelese.substring(i*len_risposta+6,i*len_risposta+6+1) == '1') 
					{
						right_answers++;
					}
				}
				
	//alert(right_answers + '>=' + num_domande + '-' + num_allowed_errors);
				if (right_answers >= num_domande-num_allowed_errors)
				{
					// ha risposto bene a tutte le domande
					auth_caposelese += ';Checked';
	//alert('Checked');
				}
				else
				{
					// ha risposto male almeno ad una domanda
					auth_caposelese += ';Unchecked';
	//alert('Unchecked');
				}
			}
		}
	}
	
	// prima lettera maiuscola, rimanenti minuscole
	auth_nome 	= prima_lettera_maiuscola(auth_nome);
	auth_cognome 	= prima_lettera_maiuscola(auth_cognome);
	auth_provenienza= prima_lettera_maiuscola(auth_provenienza);
	
	// campo univoco (non puo' ripetersi tra due giocate)
	auth_hidden = auth_nome+';'+auth_cognome+';'+auth_nato+';'+auth_provenienza;
	
	// imposta campo auth_token
	tag = 'auth_token'; // nome del campo nascosto
	f[tag].value = auth_hidden;
	
	// imposta campo auth_caposelese
	tag = tag_feedback; // nome del campo nascosto
	f[tag].value = auth_caposelese;
	
	
	// gestione campi amministrativi
	tag = 'data_giocata';
	if (f[tag]) // se il campo esiste (e quindi si e' in modalita' amministrativa...
	{
		data_giocata = f[tag].value;
		
		// verifica formato data (auth_nato)
		formato_data = /^[0-9]{2}\/[0-9]{2}\/[0-9]{4}$/;
		if (!data_giocata.match(formato_data))
		{
			alert("Il formato della data della giocata deve essere gg/mm/aaaa (es. 31/12/2006)!");
			return false;
		}
		else
		{
			data_giocata = "12:00 "+data_giocata; // aggiungi ora e minuti fittizi
			f[tag].value = data_giocata;
		}
	}


	if (!risposte_ok)
	{
		alert(messaggio_errore);
	}
	else
	{
		risposte_ok = confirm("Sei sicuro? Una volta confermato la giocata sara' definitiva.");
	}
	
	if (risposte_ok)
	{
		this.window.document.forms['question_form'].submit();
	}
	else
	{
		// resetta le risposte date
		this.window.document.forms['question_form'][tag_feedback].value = '0';
	}
	
	return risposte_ok;
}

function check_fase(
	gironi,   // array dei gironi (invariato per tutte le fasi)

	{
		lista_corrente,		// es: list_M (sedicesimi), list_W (ottavi), list_Q (quarti), ecc.
		lista_precedente,	// null per sedicesimi; per le altre fasi: lista della fase precedente
		num_min,			// minimo squadre per girone (solo per sedicesimi)
		num_max,			// massimo squadre per girone (solo per sedicesimi)
		check_gironi,		// true per sedicesimi (controllo squadre per girone), false per le altre fasi
		nome_fase,			// nome della fase: "sedicesimi", "ottavi", "quarti", "semifinali", "finale", "vincitrice"
		warn_on_errors_flag,// flag per mostrare messaggi di warning (dipende dalla fase)
		allow_errors_flag	// flag per consentire o meno il salvataggio in presenza di errori
	}
)
{
	let vettore_gironi = new Array(gironi.length).fill(0);

	// --- 1. Controllo duplicati nella lista corrente ---
	for (let i = 0; i < lista_corrente.length; i++) {
		let squadra = lista_corrente[i];

		// duplicati nella stessa fase
		if (occurrencies(squadra, lista_corrente) > 1) {
			alert(`Nei qualificati alla fase ${nome_fase} la squadra ${squadra} compare più volte!`);
			return false;
		}

		// --- 2. Se richiesto, verifica presenza nella fase precedente ---
		if (lista_precedente !== null) {
			if (occurrencies(squadra, lista_precedente) !== 1) {
				alert(`La squadra ${squadra} compare nella fase ${nome_fase}, ma non era presente nella fase precedente!`);
				return false;
			}
		}

		// --- 3. Se richiesto, conteggio squadre per girone ---
		if (check_gironi) {
			let g = get_girone(squadra, gironi);
			vettore_gironi[g]++;
		}
	}
	
	// --- 4. Se non serve controllare i gironi (es. ottavi), finito ---
	if (!check_gironi) return true;
	
	// --- 5. Controllo squadre per girone (solo sedicesimi) ---
	if (warn_on_errors_flag) {
		let msg = "";
		
		gironi_errati = false;
		for (let i = 0; i < gironi.length; i++) {
			let count = vettore_gironi[i];
			let errore = 0;
			
			if (count > num_max) errore = count - num_max;
			else if (count < num_min) errore = count - num_min;
			
			msg += `\n  Girone ${String.fromCharCode(65 + i)}: ${count}`;
			
			if (errore > 0) 
			{
				msg += ` (eliminare ${errore} squadra/e)`;
				gironi_errati = true;
			}
			if (errore < 0) 
			{
				msg += ` (aggiungere ${-errore} squadra/e)`;
				gironi_errati = true;
			}
		}
		
		if (num_max === 1)
			msg = `Nella fase ${nome_fase} va indicata 1 squadra per ciascun girone:` + msg;
		else
			msg = `Nella fase ${nome_fase} vanno indicate da ${num_min} a ${num_max} squadre per girone:` + msg;
		
		if (gironi_errati && !allow_errors_flag)
		{
			alert(msg);
			return false;
		}
	}
	
	return true;
}


function check_day_month_year(auth_nato_giorno,auth_nato_mese,auth_nato_anno)
{
	// verifica mese
	if ( (auth_nato_mese<1) || (auth_nato_mese>12) ) {
		flg_bad_date = 1;
	}
	else {
		// verifica giorno
		switch(parseInt(auth_nato_mese)) {
		case 2: // febbraio
			flg_leap_year = ((auth_nato_anno % 4 == 0) && (auth_nato_anno % 100 != 0)) || (auth_nato_anno % 400 == 0);
			if (flg_leap_year) {
				// anno bisestile
				num_giorni = 29;
			}
			else {
				num_giorni = 28;
			}
			break;
		case 4:  // aprile
		case 6:  // giugno
		case 9:  // settembre
		case 11: // novembre
			num_giorni = 30;
			break;
		default:
			num_giorni = 31;
		}
		if ( (auth_nato_giorno<1) || (auth_nato_giorno>num_giorni) ) {
			flg_bad_date = 1;
		}
		else {
			// verifica anno
			currentTime = new Date();
			if ( (auth_nato_anno<1900) || (auth_nato_anno>currentTime.getFullYear()) ) {
				flg_bad_date = 1;
			}
			else {
				flg_bad_date = 0;
			}
		}
	}
	return flg_bad_date;
}


function toggle_caposelese(f,question_caposelese)
{
caption = f['temp_silaritudine'].value;
if (caption=='Si')
{
	f['temp_silaritudine'].value = 'No';
	
	ks = f[question_caposelese].value;
	f[question_caposelese].value = '0';
}
else
{
	f['temp_silaritudine'].value = 'Si';
	f[question_caposelese].value = '0;Checked';
}
//alert(f[question_caposelese].value);
}


function aggiornaPronostici(livelloModificato, checkGironi) {

	// ============================
	// PARAMETRI CONFIGURABILI
	// ============================
	const SQUADRE_PER_GIRONE = 4;   // es. 4 squadre per girone
	const MAX_PER_GIRONE	 = 3;   // es. max 3 squadre qualificate per girone
	// ============================

	const selects = document.querySelectorAll("select.pronostico");

	// ============================
	// FUNZIONE DI SUPPORTO
	// Estrae i parametri da onchange="aggiornaPronostici(6,1)"
	// ============================
	function estraiParametri(sel) {
		const onchange = sel.getAttribute("onchange");
		const match = onchange.match(/aggiornaPronostici\s*\(\s*(\d+)\s*,\s*(\d+)\s*\)/);
		if (!match) return { livello: null, check: null };
		return {
			livello: parseInt(match[1]),
			check:   parseInt(match[2])
		};
	}

	// ============================
	// 1. Raggruppa le scelte per livello
	// ============================
	const sceltePerLivello = {};

	selects.forEach(sel => {
		const p = estraiParametri(sel);
		const livello = p.livello;
		if (livello == null) return;

		if (!sceltePerLivello[livello]) {
			sceltePerLivello[livello] = new Set();
		}

		const val = sel.value.trim();
		if (val !== "" && val !== "&nbsp;") {
			sceltePerLivello[livello].add(val);
		}
	});

	// ============================
	// 2. Calcola squadre ammesse per ogni livello
	// ============================
	const livelli = Object.keys(sceltePerLivello).map(n => parseInt(n));
	if (livelli.length === 0) return;

	const livelloMax = Math.max(...livelli);
	const squadreAmmesse = {};

	squadreAmmesse[livelloMax] = null; // livello più alto → tutte ammesse

	for (let livello = livelloMax - 1; livello >= 1; livello--) {
		squadreAmmesse[livello] = sceltePerLivello[livello + 1] || new Set();
	}

	// ============================
	// 3. Regola opzionale: max X squadre per girone
	// ============================
	const gironi = {};

	if (checkGironi === 1) {

		// ogni select ha 1 option "vuota" + N squadre
		const totaleOpzioni   = selects[0].options.length;
		const opzioniSquadre  = totaleOpzioni - 1; // esclude la prima "&nbsp;"
		const numeroGironi	= Math.floor(opzioniSquadre / SQUADRE_PER_GIRONE);

		for (let g = 0; g < numeroGironi; g++) {
			gironi[g] = 0;
		}

		selects.forEach(sel => {
			const p = estraiParametri(sel);
			if (p.livello !== livelloModificato) return;

			const squadra = sel.value.trim();
			if (!squadra) return;

			const optIndex = [...sel.options].findIndex(o => o.textContent.trim() === squadra);
			if (optIndex <= 0) return; // evita casi anomali

			const indexReale = optIndex - 1;
			const girone	 = Math.floor(indexReale / SQUADRE_PER_GIRONE);

			gironi[girone]++;
		});
	}

	// ============================
	// 4. Applica tutte le regole
	// ============================
	selects.forEach(sel => {
		const p = estraiParametri(sel);
		const livello = p.livello;
		if (livello == null) return;

		const scelteStessoLivello = sceltePerLivello[livello] || new Set();
		const ammesse			 = squadreAmmesse[livello];

		sel.querySelectorAll("option").forEach((opt, idx) => {
			const squadra = opt.textContent.trim();
			if (squadra === "" || squadra === "&nbsp;") return;

			let disabilita = false;

			// Regola 1: no duplicati nello stesso livello
			if (scelteStessoLivello.has(squadra) && sel.value !== squadra) {
				disabilita = true;
			}

			// Regola 2: solo squadre ammesse dal livello precedente
			if (!disabilita && ammesse !== null && !ammesse.has(squadra)) {
				disabilita = true;
			}

			// Regola 3: max X squadre per girone
			if (!disabilita && p.check === 1 && livello === livelloModificato) {
				if (idx === 0) return; // la &nbsp; non appartiene a nessun girone

				const indexReale = idx - 1;
				const girone	 = Math.floor(indexReale / SQUADRE_PER_GIRONE);

				if (gironi[girone] >= MAX_PER_GIRONE && !scelteStessoLivello.has(squadra)) {
					disabilita = true;
				}
			}

			// Applica disabilitazione
			opt.disabled = disabilita;

			// Reset automatico se la scelta diventa illegale
			if (disabilita && sel.value === squadra) {
				sel.value = ""; // torna alla prima opzione (&nbsp;)
			}
		});
	});
}


// per eseguire il check ad ogni reload, cos' da rendere coerenti i valori selezionati per i vari select,
// con 'abilitazione dei campi option dei select
window.addEventListener("DOMContentLoaded", () => {

	// Trova tutti i select con classe "pronostico"
	const selects = document.querySelectorAll("select.pronostico");

	// Estrae tutte le coppie livello/check presenti negli onchange
	const livelliDaInizializzare = new Set();

	selects.forEach(sel => {
		const onchange = sel.getAttribute("onchange");
		if (!onchange) return;

		const match = onchange.match(/aggiornaPronostici\s*\(\s*(\d+)\s*,\s*(\d+)\s*\)/);
		if (!match) return;

		const livello = parseInt(match[1]);
		const check   = parseInt(match[2]);

		livelliDaInizializzare.add(`${livello},${check}`);
	});

	// Richiama aggiornaPronostici per ogni coppia trovata
	livelliDaInizializzare.forEach(pair => {
		const [livello, check] = pair.split(",").map(Number);
		aggiornaPronostici(livello, check);
	});

});


//-->
</SCRIPT>





<form name="question_form" action="<?php echo $action; ?>" method="get" OnSubmit="return check_input(this)">
<!--
//
// 1) inizio visualizzazione della pagina customizzata: qui giu' incolla il codice html customizzato (senza header ne' tag body)
//

All'interno del codice customizzato, che fara' parte del form, bisognera' inserire dei campo question_xx, con xx=0,1,...

Andranno anche gestiti, con codice php, le variabili:
- $admin_mode	: 1 -> pagina utilizzata dall'interno della pagina amministrativa, bisogna aggiungere il campo per la data della giocata
- $info_mode	: 1 -> visualizza soltanto il form, ma non permettere la giocata

-->


<table SUMMARY=main_table>
<COLGROUP><COL WIDTH=50%><COL WIDTH=5%><COL WIDTH=50%></COLGROUP>
<tbody><tr><td>


<!-- colonna sinistra: form con le select -->
<TABLE FRAME=VOID CELLSPACING=0 RULES=GROUPS BORDER=1 SUMMARY=left_column_table>
	<COLGROUP><COL WIDTH=80><COL WIDTH=80><COL WIDTH=80><COL WIDTH=80></COLGROUP>
	<TBODY>
		<TR>
			<TD COLSPAN=4 WIDTH=801 HEIGHT=23 ALIGN=CENTER><FONT SIZE=4><?php echo $lotteria_nome ?> - Scheda per il sondaggio -</FONT></TD>
			</TR>
<?php
if (!empty($nominativo)) { ?>
		<TR>
			<TD COLSPAN=4 HEIGHT=16 ALIGN=LEFT><b>Benvenuto <?php echo $nominativo ?></b></TD>
		</TR>
<?php
}



//
// 5) visualizzazione del messaggio di stato della lotteria
//
if (!empty($messaggio_stato_sondaggio)) {
?>
 <tr style='height:18.0pt'>
  <td colspan=4 width=666 style='width:500pt' align='center'><?php echo $messaggio_stato_sondaggio; ?></td>
 </tr>

 <tr style='height:3.75pt'>
  <td style='height:3.75pt'></td>
  <td style='height:3.75pt'></td>
  <td style='height:3.75pt'></td>
  <td style='height:3.75pt'></td>
 </tr>
<?php
}
?>

<tr style='height:14.25pt'>
  <td class=xl34>nome</td>
  <td class=xl34>cognome</td>
  <td class=xl34><span title="Il formato della data deve essere del tipo: gg/mm/aaaa">nato/a il</span></td>
  <td class=xl34><span title="Si intende il comune di riferimento per la comunita' di origine, non il luogo di nascita amministrativo">comune di origine</span></td>
</tr>

<tr style='height:14.25pt' align='left'>
	<td>

<!-- #63 (nome) -->
<input name='question_63' type='text' value=''>

	</td>


	<td>

<!-- #64 (cognome) -->
<input name='question_64' type='text' value=''>

	</td>


	<td>

<!-- #65 (nato/a il) -->
<input name='question_65' type='text' value=''>

	</td>


	<td>

<!-- #66 (comune di origine) -->
<input name='question_66' type='text' value=''>

<!-- #67 (hidden per interazione popup) -->
<input name='question_67' type='hidden' value='0'>

	</td>
</tr>

		<!-- gironi A,B,C,D -->
		<TR>
			<TD HEIGHT=16 ALIGN=LEFT><BR></TD>
			<TD ALIGN=LEFT><BR></TD>
			<TD ALIGN=LEFT><BR></TD>
			<TD ALIGN=LEFT><BR></TD>
		</TR>
		<TR>
			<TD COLSPAN=4 HEIGHT=16 ALIGN=LEFT>La composizione dei dodici gironi eliminatori:</TD>
		</TR>
		<TR>
			<TD HEIGHT=18 ALIGN=LEFT BGCOLOR="#FFFF99"><FONT FACE="Arial Narrow">Gruppo A</FONT></TD>
			<TD ALIGN=LEFT BGCOLOR="#FFFF99"><FONT FACE="Arial Narrow">Gruppo B</FONT></TD>
			<TD ALIGN=LEFT BGCOLOR="#FFFF99"><FONT FACE="Arial Narrow">Gruppo C</FONT></TD>
			<TD ALIGN=LEFT BGCOLOR="#FFFF99"><FONT FACE="Arial Narrow">Gruppo D</FONT></TD>
		</TR>
		<TR>
			<TD HEIGHT=18 ALIGN=LEFT BGCOLOR="#E6E6FF">Messico</TD>
			<TD ALIGN=LEFT BGCOLOR="#E6E6FF">Canada</TD>
			<TD ALIGN=LEFT BGCOLOR="#E6E6FF">Brasile</TD>
			<TD ALIGN=LEFT BGCOLOR="#E6E6FF">USA</TD>
		</TR>
		<TR>
			<TD HEIGHT=18 ALIGN=LEFT BGCOLOR="#E6E6FF">Corea del Sud</TD>
			<TD ALIGN=LEFT BGCOLOR="#E6E6FF">Svizzera</TD>
			<TD ALIGN=LEFT BGCOLOR="#E6E6FF">Marocco</TD>
			<TD ALIGN=LEFT BGCOLOR="#E6E6FF">Australia</TD>
		</TR>
		<TR>
			<TD HEIGHT=18 ALIGN=LEFT BGCOLOR="#E6E6FF">Sudafrica</TD>
			<TD ALIGN=LEFT BGCOLOR="#E6E6FF">Qatar</TD>
			<TD ALIGN=LEFT BGCOLOR="#E6E6FF">Scozia</TD>
			<TD ALIGN=LEFT BGCOLOR="#E6E6FF">Paraguay</TD>
		</TR>
		<TR>
			<TD HEIGHT=18 ALIGN=LEFT BGCOLOR="#E6E6FF">Vincitore_D</TD>
			<TD ALIGN=LEFT BGCOLOR="#E6E6FF">Vincitore_A</TD>
			<TD ALIGN=LEFT BGCOLOR="#E6E6FF">Haiti</TD>
			<TD ALIGN=LEFT BGCOLOR="#E6E6FF">Vincitore_C</TD>
		</TR>
		
		
		<!-- gironi E,F,G,H -->
		<TR>
			<TD HEIGHT=16 ALIGN=LEFT><BR></TD>
			<TD ALIGN=LEFT><BR></TD>
			<TD ALIGN=LEFT><BR></TD>
			<TD ALIGN=LEFT><BR></TD>
		</TR>
		<TR>
			<TD HEIGHT=18 ALIGN=LEFT BGCOLOR="#FFFF99"><FONT FACE="Arial Narrow">Gruppo E</FONT></TD>
			<TD ALIGN=LEFT BGCOLOR="#FFFF99"><FONT FACE="Arial Narrow">Gruppo F</FONT></TD>
			<TD ALIGN=LEFT BGCOLOR="#FFFF99"><FONT FACE="Arial Narrow">Gruppo G</FONT></TD>
			<TD ALIGN=LEFT BGCOLOR="#FFFF99"><FONT FACE="Arial Narrow">Gruppo H</FONT></TD>
		</TR>
		<TR>
			<TD HEIGHT=18 ALIGN=LEFT BGCOLOR="#E6E6FF">Germania</TD>
			<TD ALIGN=LEFT BGCOLOR="#E6E6FF">Olanda</TD>
			<TD ALIGN=LEFT BGCOLOR="#E6E6FF">Belgio</TD>
			<TD ALIGN=LEFT BGCOLOR="#E6E6FF">Spagna</TD>
		</TR>
		<TR>
			<TD HEIGHT=18 ALIGN=LEFT BGCOLOR="#E6E6FF">Ecuador</TD>
			<TD ALIGN=LEFT BGCOLOR="#E6E6FF">Giappone</TD>
			<TD ALIGN=LEFT BGCOLOR="#E6E6FF">Iran</TD>
			<TD ALIGN=LEFT BGCOLOR="#E6E6FF">Uruguay</TD>
		</TR>
		<TR>
			<TD HEIGHT=18 ALIGN=LEFT BGCOLOR="#E6E6FF">Costa d'Avorio</TD>
			<TD ALIGN=LEFT BGCOLOR="#E6E6FF">Tunisia</TD>
			<TD ALIGN=LEFT BGCOLOR="#E6E6FF">Egitto</TD>
			<TD ALIGN=LEFT BGCOLOR="#E6E6FF">Arabia Saudita</TD>
		</TR>
		<TR>
			<TD HEIGHT=18 ALIGN=LEFT BGCOLOR="#E6E6FF">Curaçao</TD>
			<TD ALIGN=LEFT BGCOLOR="#E6E6FF">Vincitore_B</TD>
			<TD ALIGN=LEFT BGCOLOR="#E6E6FF">Nuova Zelanda</TD>
			<TD ALIGN=LEFT BGCOLOR="#E6E6FF">Capo Verde</TD>
		</TR>		
		
		<!-- gironi I,J,K,L -->
		<TR>
			<TD HEIGHT=16 ALIGN=LEFT><BR></TD>
			<TD ALIGN=LEFT><BR></TD>
			<TD ALIGN=LEFT><BR></TD>
			<TD ALIGN=LEFT><BR></TD>
		</TR>
		<TR>
			<TD HEIGHT=18 ALIGN=LEFT BGCOLOR="#FFFF99"><FONT FACE="Arial Narrow">Gruppo I</FONT></TD>
			<TD ALIGN=LEFT BGCOLOR="#FFFF99"><FONT FACE="Arial Narrow">Gruppo J</FONT></TD>
			<TD ALIGN=LEFT BGCOLOR="#FFFF99"><FONT FACE="Arial Narrow">Gruppo K</FONT></TD>
			<TD ALIGN=LEFT BGCOLOR="#FFFF99"><FONT FACE="Arial Narrow">Gruppo L</FONT></TD>
		</TR>
		<TR>
			<TD HEIGHT=18 ALIGN=LEFT BGCOLOR="#E6E6FF">Francia</TD>
			<TD ALIGN=LEFT BGCOLOR="#E6E6FF">Argentina</TD>
			<TD ALIGN=LEFT BGCOLOR="#E6E6FF">Portogallo</TD>
			<TD ALIGN=LEFT BGCOLOR="#E6E6FF">Inghilterra</TD>
		</TR>
		<TR>
			<TD HEIGHT=18 ALIGN=LEFT BGCOLOR="#E6E6FF">Senegal</TD>
			<TD ALIGN=LEFT BGCOLOR="#E6E6FF">Austria</TD>
			<TD ALIGN=LEFT BGCOLOR="#E6E6FF">Colombia</TD>
			<TD ALIGN=LEFT BGCOLOR="#E6E6FF">Croazia</TD>
		</TR>
		<TR>
			<TD HEIGHT=18 ALIGN=LEFT BGCOLOR="#E6E6FF">Norvegia</TD>
			<TD ALIGN=LEFT BGCOLOR="#E6E6FF">Algeria</TD>
			<TD ALIGN=LEFT BGCOLOR="#E6E6FF">Uzbekistan</TD>
			<TD ALIGN=LEFT BGCOLOR="#E6E6FF">Panama</TD>
		</TR>
		<TR>
			<TD HEIGHT=18 ALIGN=LEFT BGCOLOR="#E6E6FF">Vincitore_2</TD>
			<TD ALIGN=LEFT BGCOLOR="#E6E6FF">Giordania</TD>
			<TD ALIGN=LEFT BGCOLOR="#E6E6FF">Vincitore_1</TD>
			<TD ALIGN=LEFT BGCOLOR="#E6E6FF">Ghana</TD>
		</TR>
		
		
		<TR>
			<TD HEIGHT=16 ALIGN=CENTER COLSPAN="4">
<?php 
if (!empty($helper_msg))
{
	echo "<a href=\"$data_msg\"><b>\n";
	echo "$helper_msg\n";
	echo "</b></a>\n";
}
else
{
	echo "<br>";
}
?>
			</TD>
		</TR>
		<TR>
			<TD HEIGHT=16 ALIGN=LEFT><BR></TD>
			<TD ALIGN=LEFT><BR></TD>
			<TD ALIGN=LEFT><BR></TD>
			<TD ALIGN=LEFT><BR></TD>
			</TR>


		<TR>
			<TD COLSPAN=4 HEIGHT=16 ALIGN=LEFT><I>saranno ammesse ai Sedicesimi (in ordine libero):</I></TD>
		</TR>
		<TR>
			<TD HEIGHT=21 ALIGN=LEFT BGCOLOR="#CCFFCC">

<!-- M1 -->
<select name="question_00" class="pronostico" onchange="aggiornaPronostici(6,1)">
	<option selected>&nbsp;</option>
	<optgroup label="Girone A">
		<option>Messico</option>
		<option>Corea del Sud</option>
		<option>Sudafrica</option>
		<option>Vincitore_D</option>
	</optgroup>
	<optgroup label="Girone B">
		<option>Canada</option>
		<option>Svizzera</option>
		<option>Qatar</option>
		<option>Vincitore_A</option>
	</optgroup>
	<optgroup label="Girone C">
		<option>Brasile</option>
		<option>Marocco</option>
		<option>Scozia</option>
		<option>Haiti</option>
	</optgroup>
	<optgroup label="Girone D">
		<option>USA</option>
		<option>Australia</option>
		<option>Paraguay</option>
		<option>Vincitore_C</option>
	</optgroup>
	<optgroup label="Girone E">
		<option>Germania</option>
		<option>Ecuador</option>
		<option>Costa d'Avorio</option>
		<option>Curaçao</option>
	</optgroup>
	<optgroup label="Girone F">
		<option>Olanda</option>
		<option>Giappone</option>
		<option>Tunisia</option>
		<option>Vincitore_B</option>
	</optgroup>
	<optgroup label="Girone G">
		<option>Belgio</option>
		<option>Iran</option>
		<option>Egitto</option>
		<option>Nuova Zelanda</option>
	</optgroup>
	<optgroup label="Girone H">
		<option>Spagna</option>
		<option>Uruguay</option>
		<option>Arabia Saudita</option>
		<option>Capo Verde</option>
	</optgroup>
	<optgroup label="Girone I">
		<option>Francia</option>
		<option>Senegal</option>
		<option>Norvegia</option>
		<option>Vincitore_2</option>
	</optgroup>
	<optgroup label="Girone J">
		<option>Argentina</option>
		<option>Austria</option>
		<option>Algeria</option>
		<option>Giordania</option>
	</optgroup>
	<optgroup label="Girone K">
		<option>Portogallo</option>
		<option>Colombia</option>
		<option>Uzbekistan</option>
		<option>Vincitore_1</option>
	</optgroup>
	<optgroup label="Girone L">
		<option>Inghilterra</option>
		<option>Croazia</option>
		<option>Panama</option>
		<option>Ghana</option>
	</optgroup>
</select>

</TD>
			<TD ALIGN=LEFT BGCOLOR="#CCFFCC">

<!-- M2 -->
<select name="question_01" class="pronostico" onchange="aggiornaPronostici(6,1)">
	<option selected>&nbsp;</option>
	<optgroup label="Girone A">
		<option>Messico</option>
		<option>Corea del Sud</option>
		<option>Sudafrica</option>
		<option>Vincitore_D</option>
	</optgroup>
	<optgroup label="Girone B">
		<option>Canada</option>
		<option>Svizzera</option>
		<option>Qatar</option>
		<option>Vincitore_A</option>
	</optgroup>
	<optgroup label="Girone C">
		<option>Brasile</option>
		<option>Marocco</option>
		<option>Scozia</option>
		<option>Haiti</option>
	</optgroup>
	<optgroup label="Girone D">
		<option>USA</option>
		<option>Australia</option>
		<option>Paraguay</option>
		<option>Vincitore_C</option>
	</optgroup>
	<optgroup label="Girone E">
		<option>Germania</option>
		<option>Ecuador</option>
		<option>Costa d'Avorio</option>
		<option>Curaçao</option>
	</optgroup>
	<optgroup label="Girone F">
		<option>Olanda</option>
		<option>Giappone</option>
		<option>Tunisia</option>
		<option>Vincitore_B</option>
	</optgroup>
	<optgroup label="Girone G">
		<option>Belgio</option>
		<option>Iran</option>
		<option>Egitto</option>
		<option>Nuova Zelanda</option>
	</optgroup>
	<optgroup label="Girone H">
		<option>Spagna</option>
		<option>Uruguay</option>
		<option>Arabia Saudita</option>
		<option>Capo Verde</option>
	</optgroup>
	<optgroup label="Girone I">
		<option>Francia</option>
		<option>Senegal</option>
		<option>Norvegia</option>
		<option>Vincitore_2</option>
	</optgroup>
	<optgroup label="Girone J">
		<option>Argentina</option>
		<option>Austria</option>
		<option>Algeria</option>
		<option>Giordania</option>
	</optgroup>
	<optgroup label="Girone K">
		<option>Portogallo</option>
		<option>Colombia</option>
		<option>Uzbekistan</option>
		<option>Vincitore_1</option>
	</optgroup>
	<optgroup label="Girone L">
		<option>Inghilterra</option>
		<option>Croazia</option>
		<option>Panama</option>
		<option>Ghana</option>
	</optgroup>
</select>


</TD>
			<TD ALIGN=LEFT BGCOLOR="#CCFFCC">

<!-- M3 -->
<select name="question_02" class="pronostico" onchange="aggiornaPronostici(6,1)">
	<option selected>&nbsp;</option>
	<optgroup label="Girone A">
		<option>Messico</option>
		<option>Corea del Sud</option>
		<option>Sudafrica</option>
		<option>Vincitore_D</option>
	</optgroup>
	<optgroup label="Girone B">
		<option>Canada</option>
		<option>Svizzera</option>
		<option>Qatar</option>
		<option>Vincitore_A</option>
	</optgroup>
	<optgroup label="Girone C">
		<option>Brasile</option>
		<option>Marocco</option>
		<option>Scozia</option>
		<option>Haiti</option>
	</optgroup>
	<optgroup label="Girone D">
		<option>USA</option>
		<option>Australia</option>
		<option>Paraguay</option>
		<option>Vincitore_C</option>
	</optgroup>
	<optgroup label="Girone E">
		<option>Germania</option>
		<option>Ecuador</option>
		<option>Costa d'Avorio</option>
		<option>Curaçao</option>
	</optgroup>
	<optgroup label="Girone F">
		<option>Olanda</option>
		<option>Giappone</option>
		<option>Tunisia</option>
		<option>Vincitore_B</option>
	</optgroup>
	<optgroup label="Girone G">
		<option>Belgio</option>
		<option>Iran</option>
		<option>Egitto</option>
		<option>Nuova Zelanda</option>
	</optgroup>
	<optgroup label="Girone H">
		<option>Spagna</option>
		<option>Uruguay</option>
		<option>Arabia Saudita</option>
		<option>Capo Verde</option>
	</optgroup>
	<optgroup label="Girone I">
		<option>Francia</option>
		<option>Senegal</option>
		<option>Norvegia</option>
		<option>Vincitore_2</option>
	</optgroup>
	<optgroup label="Girone J">
		<option>Argentina</option>
		<option>Austria</option>
		<option>Algeria</option>
		<option>Giordania</option>
	</optgroup>
	<optgroup label="Girone K">
		<option>Portogallo</option>
		<option>Colombia</option>
		<option>Uzbekistan</option>
		<option>Vincitore_1</option>
	</optgroup>
	<optgroup label="Girone L">
		<option>Inghilterra</option>
		<option>Croazia</option>
		<option>Panama</option>
		<option>Ghana</option>
	</optgroup>
</select>


</TD>
			<TD ALIGN=LEFT BGCOLOR="#CCFFCC">


<!-- M4 -->
<select name="question_03" class="pronostico" onchange="aggiornaPronostici(6,1)">
	<option selected>&nbsp;</option>
	<optgroup label="Girone A">
		<option>Messico</option>
		<option>Corea del Sud</option>
		<option>Sudafrica</option>
		<option>Vincitore_D</option>
	</optgroup>
	<optgroup label="Girone B">
		<option>Canada</option>
		<option>Svizzera</option>
		<option>Qatar</option>
		<option>Vincitore_A</option>
	</optgroup>
	<optgroup label="Girone C">
		<option>Brasile</option>
		<option>Marocco</option>
		<option>Scozia</option>
		<option>Haiti</option>
	</optgroup>
	<optgroup label="Girone D">
		<option>USA</option>
		<option>Australia</option>
		<option>Paraguay</option>
		<option>Vincitore_C</option>
	</optgroup>
	<optgroup label="Girone E">
		<option>Germania</option>
		<option>Ecuador</option>
		<option>Costa d'Avorio</option>
		<option>Curaçao</option>
	</optgroup>
	<optgroup label="Girone F">
		<option>Olanda</option>
		<option>Giappone</option>
		<option>Tunisia</option>
		<option>Vincitore_B</option>
	</optgroup>
	<optgroup label="Girone G">
		<option>Belgio</option>
		<option>Iran</option>
		<option>Egitto</option>
		<option>Nuova Zelanda</option>
	</optgroup>
	<optgroup label="Girone H">
		<option>Spagna</option>
		<option>Uruguay</option>
		<option>Arabia Saudita</option>
		<option>Capo Verde</option>
	</optgroup>
	<optgroup label="Girone I">
		<option>Francia</option>
		<option>Senegal</option>
		<option>Norvegia</option>
		<option>Vincitore_2</option>
	</optgroup>
	<optgroup label="Girone J">
		<option>Argentina</option>
		<option>Austria</option>
		<option>Algeria</option>
		<option>Giordania</option>
	</optgroup>
	<optgroup label="Girone K">
		<option>Portogallo</option>
		<option>Colombia</option>
		<option>Uzbekistan</option>
		<option>Vincitore_1</option>
	</optgroup>
	<optgroup label="Girone L">
		<option>Inghilterra</option>
		<option>Croazia</option>
		<option>Panama</option>
		<option>Ghana</option>
	</optgroup>
</select>


</TD>
		</TR>
		<TR>
			<TD HEIGHT=21 ALIGN=LEFT BGCOLOR="#CCFFCC">

<!-- M5 -->
<select name="question_04" class="pronostico" onchange="aggiornaPronostici(6,1)">
	<option selected>&nbsp;</option>
	<optgroup label="Girone A">
		<option>Messico</option>
		<option>Corea del Sud</option>
		<option>Sudafrica</option>
		<option>Vincitore_D</option>
	</optgroup>
	<optgroup label="Girone B">
		<option>Canada</option>
		<option>Svizzera</option>
		<option>Qatar</option>
		<option>Vincitore_A</option>
	</optgroup>
	<optgroup label="Girone C">
		<option>Brasile</option>
		<option>Marocco</option>
		<option>Scozia</option>
		<option>Haiti</option>
	</optgroup>
	<optgroup label="Girone D">
		<option>USA</option>
		<option>Australia</option>
		<option>Paraguay</option>
		<option>Vincitore_C</option>
	</optgroup>
	<optgroup label="Girone E">
		<option>Germania</option>
		<option>Ecuador</option>
		<option>Costa d'Avorio</option>
		<option>Curaçao</option>
	</optgroup>
	<optgroup label="Girone F">
		<option>Olanda</option>
		<option>Giappone</option>
		<option>Tunisia</option>
		<option>Vincitore_B</option>
	</optgroup>
	<optgroup label="Girone G">
		<option>Belgio</option>
		<option>Iran</option>
		<option>Egitto</option>
		<option>Nuova Zelanda</option>
	</optgroup>
	<optgroup label="Girone H">
		<option>Spagna</option>
		<option>Uruguay</option>
		<option>Arabia Saudita</option>
		<option>Capo Verde</option>
	</optgroup>
	<optgroup label="Girone I">
		<option>Francia</option>
		<option>Senegal</option>
		<option>Norvegia</option>
		<option>Vincitore_2</option>
	</optgroup>
	<optgroup label="Girone J">
		<option>Argentina</option>
		<option>Austria</option>
		<option>Algeria</option>
		<option>Giordania</option>
	</optgroup>
	<optgroup label="Girone K">
		<option>Portogallo</option>
		<option>Colombia</option>
		<option>Uzbekistan</option>
		<option>Vincitore_1</option>
	</optgroup>
	<optgroup label="Girone L">
		<option>Inghilterra</option>
		<option>Croazia</option>
		<option>Panama</option>
		<option>Ghana</option>
	</optgroup>
</select>


</TD>
			<TD ALIGN=LEFT BGCOLOR="#CCFFCC">

<!-- M6 -->
<select name="question_05" class="pronostico" onchange="aggiornaPronostici(6,1)">
	<option selected>&nbsp;</option>
	<optgroup label="Girone A">
		<option>Messico</option>
		<option>Corea del Sud</option>
		<option>Sudafrica</option>
		<option>Vincitore_D</option>
	</optgroup>
	<optgroup label="Girone B">
		<option>Canada</option>
		<option>Svizzera</option>
		<option>Qatar</option>
		<option>Vincitore_A</option>
	</optgroup>
	<optgroup label="Girone C">
		<option>Brasile</option>
		<option>Marocco</option>
		<option>Scozia</option>
		<option>Haiti</option>
	</optgroup>
	<optgroup label="Girone D">
		<option>USA</option>
		<option>Australia</option>
		<option>Paraguay</option>
		<option>Vincitore_C</option>
	</optgroup>
	<optgroup label="Girone E">
		<option>Germania</option>
		<option>Ecuador</option>
		<option>Costa d'Avorio</option>
		<option>Curaçao</option>
	</optgroup>
	<optgroup label="Girone F">
		<option>Olanda</option>
		<option>Giappone</option>
		<option>Tunisia</option>
		<option>Vincitore_B</option>
	</optgroup>
	<optgroup label="Girone G">
		<option>Belgio</option>
		<option>Iran</option>
		<option>Egitto</option>
		<option>Nuova Zelanda</option>
	</optgroup>
	<optgroup label="Girone H">
		<option>Spagna</option>
		<option>Uruguay</option>
		<option>Arabia Saudita</option>
		<option>Capo Verde</option>
	</optgroup>
	<optgroup label="Girone I">
		<option>Francia</option>
		<option>Senegal</option>
		<option>Norvegia</option>
		<option>Vincitore_2</option>
	</optgroup>
	<optgroup label="Girone J">
		<option>Argentina</option>
		<option>Austria</option>
		<option>Algeria</option>
		<option>Giordania</option>
	</optgroup>
	<optgroup label="Girone K">
		<option>Portogallo</option>
		<option>Colombia</option>
		<option>Uzbekistan</option>
		<option>Vincitore_1</option>
	</optgroup>
	<optgroup label="Girone L">
		<option>Inghilterra</option>
		<option>Croazia</option>
		<option>Panama</option>
		<option>Ghana</option>
	</optgroup>
</select>



</TD>
			<TD ALIGN=LEFT BGCOLOR="#CCFFCC">

<!-- M7 -->
<select name="question_06" class="pronostico" onchange="aggiornaPronostici(6,1)">
	<option selected>&nbsp;</option>
	<optgroup label="Girone A">
		<option>Messico</option>
		<option>Corea del Sud</option>
		<option>Sudafrica</option>
		<option>Vincitore_D</option>
	</optgroup>
	<optgroup label="Girone B">
		<option>Canada</option>
		<option>Svizzera</option>
		<option>Qatar</option>
		<option>Vincitore_A</option>
	</optgroup>
	<optgroup label="Girone C">
		<option>Brasile</option>
		<option>Marocco</option>
		<option>Scozia</option>
		<option>Haiti</option>
	</optgroup>
	<optgroup label="Girone D">
		<option>USA</option>
		<option>Australia</option>
		<option>Paraguay</option>
		<option>Vincitore_C</option>
	</optgroup>
	<optgroup label="Girone E">
		<option>Germania</option>
		<option>Ecuador</option>
		<option>Costa d'Avorio</option>
		<option>Curaçao</option>
	</optgroup>
	<optgroup label="Girone F">
		<option>Olanda</option>
		<option>Giappone</option>
		<option>Tunisia</option>
		<option>Vincitore_B</option>
	</optgroup>
	<optgroup label="Girone G">
		<option>Belgio</option>
		<option>Iran</option>
		<option>Egitto</option>
		<option>Nuova Zelanda</option>
	</optgroup>
	<optgroup label="Girone H">
		<option>Spagna</option>
		<option>Uruguay</option>
		<option>Arabia Saudita</option>
		<option>Capo Verde</option>
	</optgroup>
	<optgroup label="Girone I">
		<option>Francia</option>
		<option>Senegal</option>
		<option>Norvegia</option>
		<option>Vincitore_2</option>
	</optgroup>
	<optgroup label="Girone J">
		<option>Argentina</option>
		<option>Austria</option>
		<option>Algeria</option>
		<option>Giordania</option>
	</optgroup>
	<optgroup label="Girone K">
		<option>Portogallo</option>
		<option>Colombia</option>
		<option>Uzbekistan</option>
		<option>Vincitore_1</option>
	</optgroup>
	<optgroup label="Girone L">
		<option>Inghilterra</option>
		<option>Croazia</option>
		<option>Panama</option>
		<option>Ghana</option>
	</optgroup>
</select>





</TD>
			<TD ALIGN=LEFT BGCOLOR="#CCFFCC">

<!-- M8 -->
<select name="question_07" class="pronostico" onchange="aggiornaPronostici(6,1)">
	<option selected>&nbsp;</option>
	<optgroup label="Girone A">
		<option>Messico</option>
		<option>Corea del Sud</option>
		<option>Sudafrica</option>
		<option>Vincitore_D</option>
	</optgroup>
	<optgroup label="Girone B">
		<option>Canada</option>
		<option>Svizzera</option>
		<option>Qatar</option>
		<option>Vincitore_A</option>
	</optgroup>
	<optgroup label="Girone C">
		<option>Brasile</option>
		<option>Marocco</option>
		<option>Scozia</option>
		<option>Haiti</option>
	</optgroup>
	<optgroup label="Girone D">
		<option>USA</option>
		<option>Australia</option>
		<option>Paraguay</option>
		<option>Vincitore_C</option>
	</optgroup>
	<optgroup label="Girone E">
		<option>Germania</option>
		<option>Ecuador</option>
		<option>Costa d'Avorio</option>
		<option>Curaçao</option>
	</optgroup>
	<optgroup label="Girone F">
		<option>Olanda</option>
		<option>Giappone</option>
		<option>Tunisia</option>
		<option>Vincitore_B</option>
	</optgroup>
	<optgroup label="Girone G">
		<option>Belgio</option>
		<option>Iran</option>
		<option>Egitto</option>
		<option>Nuova Zelanda</option>
	</optgroup>
	<optgroup label="Girone H">
		<option>Spagna</option>
		<option>Uruguay</option>
		<option>Arabia Saudita</option>
		<option>Capo Verde</option>
	</optgroup>
	<optgroup label="Girone I">
		<option>Francia</option>
		<option>Senegal</option>
		<option>Norvegia</option>
		<option>Vincitore_2</option>
	</optgroup>
	<optgroup label="Girone J">
		<option>Argentina</option>
		<option>Austria</option>
		<option>Algeria</option>
		<option>Giordania</option>
	</optgroup>
	<optgroup label="Girone K">
		<option>Portogallo</option>
		<option>Colombia</option>
		<option>Uzbekistan</option>
		<option>Vincitore_1</option>
	</optgroup>
	<optgroup label="Girone L">
		<option>Inghilterra</option>
		<option>Croazia</option>
		<option>Panama</option>
		<option>Ghana</option>
	</optgroup>
</select>



</TD>
		</TR>


		<TR>
			<TD HEIGHT=21 ALIGN=LEFT BGCOLOR="#CCFFCC">

<!-- M9 -->
<select name="question_08" class="pronostico" onchange="aggiornaPronostici(6,1)">
	<option selected>&nbsp;</option>
	<optgroup label="Girone A">
		<option>Messico</option>
		<option>Corea del Sud</option>
		<option>Sudafrica</option>
		<option>Vincitore_D</option>
	</optgroup>
	<optgroup label="Girone B">
		<option>Canada</option>
		<option>Svizzera</option>
		<option>Qatar</option>
		<option>Vincitore_A</option>
	</optgroup>
	<optgroup label="Girone C">
		<option>Brasile</option>
		<option>Marocco</option>
		<option>Scozia</option>
		<option>Haiti</option>
	</optgroup>
	<optgroup label="Girone D">
		<option>USA</option>
		<option>Australia</option>
		<option>Paraguay</option>
		<option>Vincitore_C</option>
	</optgroup>
	<optgroup label="Girone E">
		<option>Germania</option>
		<option>Ecuador</option>
		<option>Costa d'Avorio</option>
		<option>Curaçao</option>
	</optgroup>
	<optgroup label="Girone F">
		<option>Olanda</option>
		<option>Giappone</option>
		<option>Tunisia</option>
		<option>Vincitore_B</option>
	</optgroup>
	<optgroup label="Girone G">
		<option>Belgio</option>
		<option>Iran</option>
		<option>Egitto</option>
		<option>Nuova Zelanda</option>
	</optgroup>
	<optgroup label="Girone H">
		<option>Spagna</option>
		<option>Uruguay</option>
		<option>Arabia Saudita</option>
		<option>Capo Verde</option>
	</optgroup>
	<optgroup label="Girone I">
		<option>Francia</option>
		<option>Senegal</option>
		<option>Norvegia</option>
		<option>Vincitore_2</option>
	</optgroup>
	<optgroup label="Girone J">
		<option>Argentina</option>
		<option>Austria</option>
		<option>Algeria</option>
		<option>Giordania</option>
	</optgroup>
	<optgroup label="Girone K">
		<option>Portogallo</option>
		<option>Colombia</option>
		<option>Uzbekistan</option>
		<option>Vincitore_1</option>
	</optgroup>
	<optgroup label="Girone L">
		<option>Inghilterra</option>
		<option>Croazia</option>
		<option>Panama</option>
		<option>Ghana</option>
	</optgroup>
</select>



</TD>
			<TD ALIGN=LEFT BGCOLOR="#CCFFCC">

<!-- M10 -->
<select name="question_09" class="pronostico" onchange="aggiornaPronostici(6,1)">
	<option selected>&nbsp;</option>
	<optgroup label="Girone A">
		<option>Messico</option>
		<option>Corea del Sud</option>
		<option>Sudafrica</option>
		<option>Vincitore_D</option>
	</optgroup>
	<optgroup label="Girone B">
		<option>Canada</option>
		<option>Svizzera</option>
		<option>Qatar</option>
		<option>Vincitore_A</option>
	</optgroup>
	<optgroup label="Girone C">
		<option>Brasile</option>
		<option>Marocco</option>
		<option>Scozia</option>
		<option>Haiti</option>
	</optgroup>
	<optgroup label="Girone D">
		<option>USA</option>
		<option>Australia</option>
		<option>Paraguay</option>
		<option>Vincitore_C</option>
	</optgroup>
	<optgroup label="Girone E">
		<option>Germania</option>
		<option>Ecuador</option>
		<option>Costa d'Avorio</option>
		<option>Curaçao</option>
	</optgroup>
	<optgroup label="Girone F">
		<option>Olanda</option>
		<option>Giappone</option>
		<option>Tunisia</option>
		<option>Vincitore_B</option>
	</optgroup>
	<optgroup label="Girone G">
		<option>Belgio</option>
		<option>Iran</option>
		<option>Egitto</option>
		<option>Nuova Zelanda</option>
	</optgroup>
	<optgroup label="Girone H">
		<option>Spagna</option>
		<option>Uruguay</option>
		<option>Arabia Saudita</option>
		<option>Capo Verde</option>
	</optgroup>
	<optgroup label="Girone I">
		<option>Francia</option>
		<option>Senegal</option>
		<option>Norvegia</option>
		<option>Vincitore_2</option>
	</optgroup>
	<optgroup label="Girone J">
		<option>Argentina</option>
		<option>Austria</option>
		<option>Algeria</option>
		<option>Giordania</option>
	</optgroup>
	<optgroup label="Girone K">
		<option>Portogallo</option>
		<option>Colombia</option>
		<option>Uzbekistan</option>
		<option>Vincitore_1</option>
	</optgroup>
	<optgroup label="Girone L">
		<option>Inghilterra</option>
		<option>Croazia</option>
		<option>Panama</option>
		<option>Ghana</option>
	</optgroup>
</select>


</TD>
			<TD ALIGN=LEFT BGCOLOR="#CCFFCC">

<!-- M11 -->
<select name="question_10" class="pronostico" onchange="aggiornaPronostici(6,1)">
	<option selected>&nbsp;</option>
	<optgroup label="Girone A">
		<option>Messico</option>
		<option>Corea del Sud</option>
		<option>Sudafrica</option>
		<option>Vincitore_D</option>
	</optgroup>
	<optgroup label="Girone B">
		<option>Canada</option>
		<option>Svizzera</option>
		<option>Qatar</option>
		<option>Vincitore_A</option>
	</optgroup>
	<optgroup label="Girone C">
		<option>Brasile</option>
		<option>Marocco</option>
		<option>Scozia</option>
		<option>Haiti</option>
	</optgroup>
	<optgroup label="Girone D">
		<option>USA</option>
		<option>Australia</option>
		<option>Paraguay</option>
		<option>Vincitore_C</option>
	</optgroup>
	<optgroup label="Girone E">
		<option>Germania</option>
		<option>Ecuador</option>
		<option>Costa d'Avorio</option>
		<option>Curaçao</option>
	</optgroup>
	<optgroup label="Girone F">
		<option>Olanda</option>
		<option>Giappone</option>
		<option>Tunisia</option>
		<option>Vincitore_B</option>
	</optgroup>
	<optgroup label="Girone G">
		<option>Belgio</option>
		<option>Iran</option>
		<option>Egitto</option>
		<option>Nuova Zelanda</option>
	</optgroup>
	<optgroup label="Girone H">
		<option>Spagna</option>
		<option>Uruguay</option>
		<option>Arabia Saudita</option>
		<option>Capo Verde</option>
	</optgroup>
	<optgroup label="Girone I">
		<option>Francia</option>
		<option>Senegal</option>
		<option>Norvegia</option>
		<option>Vincitore_2</option>
	</optgroup>
	<optgroup label="Girone J">
		<option>Argentina</option>
		<option>Austria</option>
		<option>Algeria</option>
		<option>Giordania</option>
	</optgroup>
	<optgroup label="Girone K">
		<option>Portogallo</option>
		<option>Colombia</option>
		<option>Uzbekistan</option>
		<option>Vincitore_1</option>
	</optgroup>
	<optgroup label="Girone L">
		<option>Inghilterra</option>
		<option>Croazia</option>
		<option>Panama</option>
		<option>Ghana</option>
	</optgroup>
</select>


</TD>
			<TD ALIGN=LEFT BGCOLOR="#CCFFCC">


<!-- M12 -->
<select name="question_11" class="pronostico" onchange="aggiornaPronostici(6,1)">
	<option selected>&nbsp;</option>
	<optgroup label="Girone A">
		<option>Messico</option>
		<option>Corea del Sud</option>
		<option>Sudafrica</option>
		<option>Vincitore_D</option>
	</optgroup>
	<optgroup label="Girone B">
		<option>Canada</option>
		<option>Svizzera</option>
		<option>Qatar</option>
		<option>Vincitore_A</option>
	</optgroup>
	<optgroup label="Girone C">
		<option>Brasile</option>
		<option>Marocco</option>
		<option>Scozia</option>
		<option>Haiti</option>
	</optgroup>
	<optgroup label="Girone D">
		<option>USA</option>
		<option>Australia</option>
		<option>Paraguay</option>
		<option>Vincitore_C</option>
	</optgroup>
	<optgroup label="Girone E">
		<option>Germania</option>
		<option>Ecuador</option>
		<option>Costa d'Avorio</option>
		<option>Curaçao</option>
	</optgroup>
	<optgroup label="Girone F">
		<option>Olanda</option>
		<option>Giappone</option>
		<option>Tunisia</option>
		<option>Vincitore_B</option>
	</optgroup>
	<optgroup label="Girone G">
		<option>Belgio</option>
		<option>Iran</option>
		<option>Egitto</option>
		<option>Nuova Zelanda</option>
	</optgroup>
	<optgroup label="Girone H">
		<option>Spagna</option>
		<option>Uruguay</option>
		<option>Arabia Saudita</option>
		<option>Capo Verde</option>
	</optgroup>
	<optgroup label="Girone I">
		<option>Francia</option>
		<option>Senegal</option>
		<option>Norvegia</option>
		<option>Vincitore_2</option>
	</optgroup>
	<optgroup label="Girone J">
		<option>Argentina</option>
		<option>Austria</option>
		<option>Algeria</option>
		<option>Giordania</option>
	</optgroup>
	<optgroup label="Girone K">
		<option>Portogallo</option>
		<option>Colombia</option>
		<option>Uzbekistan</option>
		<option>Vincitore_1</option>
	</optgroup>
	<optgroup label="Girone L">
		<option>Inghilterra</option>
		<option>Croazia</option>
		<option>Panama</option>
		<option>Ghana</option>
	</optgroup>
</select>


</TD>
		</TR>
		<TR>
			<TD HEIGHT=21 ALIGN=LEFT BGCOLOR="#CCFFCC">

<!-- M13 -->
<select name="question_12" class="pronostico" onchange="aggiornaPronostici(6,1)">
	<option selected>&nbsp;</option>
	<optgroup label="Girone A">
		<option>Messico</option>
		<option>Corea del Sud</option>
		<option>Sudafrica</option>
		<option>Vincitore_D</option>
	</optgroup>
	<optgroup label="Girone B">
		<option>Canada</option>
		<option>Svizzera</option>
		<option>Qatar</option>
		<option>Vincitore_A</option>
	</optgroup>
	<optgroup label="Girone C">
		<option>Brasile</option>
		<option>Marocco</option>
		<option>Scozia</option>
		<option>Haiti</option>
	</optgroup>
	<optgroup label="Girone D">
		<option>USA</option>
		<option>Australia</option>
		<option>Paraguay</option>
		<option>Vincitore_C</option>
	</optgroup>
	<optgroup label="Girone E">
		<option>Germania</option>
		<option>Ecuador</option>
		<option>Costa d'Avorio</option>
		<option>Curaçao</option>
	</optgroup>
	<optgroup label="Girone F">
		<option>Olanda</option>
		<option>Giappone</option>
		<option>Tunisia</option>
		<option>Vincitore_B</option>
	</optgroup>
	<optgroup label="Girone G">
		<option>Belgio</option>
		<option>Iran</option>
		<option>Egitto</option>
		<option>Nuova Zelanda</option>
	</optgroup>
	<optgroup label="Girone H">
		<option>Spagna</option>
		<option>Uruguay</option>
		<option>Arabia Saudita</option>
		<option>Capo Verde</option>
	</optgroup>
	<optgroup label="Girone I">
		<option>Francia</option>
		<option>Senegal</option>
		<option>Norvegia</option>
		<option>Vincitore_2</option>
	</optgroup>
	<optgroup label="Girone J">
		<option>Argentina</option>
		<option>Austria</option>
		<option>Algeria</option>
		<option>Giordania</option>
	</optgroup>
	<optgroup label="Girone K">
		<option>Portogallo</option>
		<option>Colombia</option>
		<option>Uzbekistan</option>
		<option>Vincitore_1</option>
	</optgroup>
	<optgroup label="Girone L">
		<option>Inghilterra</option>
		<option>Croazia</option>
		<option>Panama</option>
		<option>Ghana</option>
	</optgroup>
</select>


</TD>
			<TD ALIGN=LEFT BGCOLOR="#CCFFCC">

<!-- M14 -->
<select name="question_13" class="pronostico" onchange="aggiornaPronostici(6,1)">
	<option selected>&nbsp;</option>
	<optgroup label="Girone A">
		<option>Messico</option>
		<option>Corea del Sud</option>
		<option>Sudafrica</option>
		<option>Vincitore_D</option>
	</optgroup>
	<optgroup label="Girone B">
		<option>Canada</option>
		<option>Svizzera</option>
		<option>Qatar</option>
		<option>Vincitore_A</option>
	</optgroup>
	<optgroup label="Girone C">
		<option>Brasile</option>
		<option>Marocco</option>
		<option>Scozia</option>
		<option>Haiti</option>
	</optgroup>
	<optgroup label="Girone D">
		<option>USA</option>
		<option>Australia</option>
		<option>Paraguay</option>
		<option>Vincitore_C</option>
	</optgroup>
	<optgroup label="Girone E">
		<option>Germania</option>
		<option>Ecuador</option>
		<option>Costa d'Avorio</option>
		<option>Curaçao</option>
	</optgroup>
	<optgroup label="Girone F">
		<option>Olanda</option>
		<option>Giappone</option>
		<option>Tunisia</option>
		<option>Vincitore_B</option>
	</optgroup>
	<optgroup label="Girone G">
		<option>Belgio</option>
		<option>Iran</option>
		<option>Egitto</option>
		<option>Nuova Zelanda</option>
	</optgroup>
	<optgroup label="Girone H">
		<option>Spagna</option>
		<option>Uruguay</option>
		<option>Arabia Saudita</option>
		<option>Capo Verde</option>
	</optgroup>
	<optgroup label="Girone I">
		<option>Francia</option>
		<option>Senegal</option>
		<option>Norvegia</option>
		<option>Vincitore_2</option>
	</optgroup>
	<optgroup label="Girone J">
		<option>Argentina</option>
		<option>Austria</option>
		<option>Algeria</option>
		<option>Giordania</option>
	</optgroup>
	<optgroup label="Girone K">
		<option>Portogallo</option>
		<option>Colombia</option>
		<option>Uzbekistan</option>
		<option>Vincitore_1</option>
	</optgroup>
	<optgroup label="Girone L">
		<option>Inghilterra</option>
		<option>Croazia</option>
		<option>Panama</option>
		<option>Ghana</option>
	</optgroup>
</select>



</TD>
			<TD ALIGN=LEFT BGCOLOR="#CCFFCC">

<!-- M15 -->
<select name="question_14" class="pronostico" onchange="aggiornaPronostici(6,1)">
	<option selected>&nbsp;</option>
	<optgroup label="Girone A">
		<option>Messico</option>
		<option>Corea del Sud</option>
		<option>Sudafrica</option>
		<option>Vincitore_D</option>
	</optgroup>
	<optgroup label="Girone B">
		<option>Canada</option>
		<option>Svizzera</option>
		<option>Qatar</option>
		<option>Vincitore_A</option>
	</optgroup>
	<optgroup label="Girone C">
		<option>Brasile</option>
		<option>Marocco</option>
		<option>Scozia</option>
		<option>Haiti</option>
	</optgroup>
	<optgroup label="Girone D">
		<option>USA</option>
		<option>Australia</option>
		<option>Paraguay</option>
		<option>Vincitore_C</option>
	</optgroup>
	<optgroup label="Girone E">
		<option>Germania</option>
		<option>Ecuador</option>
		<option>Costa d'Avorio</option>
		<option>Curaçao</option>
	</optgroup>
	<optgroup label="Girone F">
		<option>Olanda</option>
		<option>Giappone</option>
		<option>Tunisia</option>
		<option>Vincitore_B</option>
	</optgroup>
	<optgroup label="Girone G">
		<option>Belgio</option>
		<option>Iran</option>
		<option>Egitto</option>
		<option>Nuova Zelanda</option>
	</optgroup>
	<optgroup label="Girone H">
		<option>Spagna</option>
		<option>Uruguay</option>
		<option>Arabia Saudita</option>
		<option>Capo Verde</option>
	</optgroup>
	<optgroup label="Girone I">
		<option>Francia</option>
		<option>Senegal</option>
		<option>Norvegia</option>
		<option>Vincitore_2</option>
	</optgroup>
	<optgroup label="Girone J">
		<option>Argentina</option>
		<option>Austria</option>
		<option>Algeria</option>
		<option>Giordania</option>
	</optgroup>
	<optgroup label="Girone K">
		<option>Portogallo</option>
		<option>Colombia</option>
		<option>Uzbekistan</option>
		<option>Vincitore_1</option>
	</optgroup>
	<optgroup label="Girone L">
		<option>Inghilterra</option>
		<option>Croazia</option>
		<option>Panama</option>
		<option>Ghana</option>
	</optgroup>
</select>





</TD>
			<TD ALIGN=LEFT BGCOLOR="#CCFFCC">

<!-- M16 -->
<select name="question_15" class="pronostico" onchange="aggiornaPronostici(6,1)">
	<option selected>&nbsp;</option>
	<optgroup label="Girone A">
		<option>Messico</option>
		<option>Corea del Sud</option>
		<option>Sudafrica</option>
		<option>Vincitore_D</option>
	</optgroup>
	<optgroup label="Girone B">
		<option>Canada</option>
		<option>Svizzera</option>
		<option>Qatar</option>
		<option>Vincitore_A</option>
	</optgroup>
	<optgroup label="Girone C">
		<option>Brasile</option>
		<option>Marocco</option>
		<option>Scozia</option>
		<option>Haiti</option>
	</optgroup>
	<optgroup label="Girone D">
		<option>USA</option>
		<option>Australia</option>
		<option>Paraguay</option>
		<option>Vincitore_C</option>
	</optgroup>
	<optgroup label="Girone E">
		<option>Germania</option>
		<option>Ecuador</option>
		<option>Costa d'Avorio</option>
		<option>Curaçao</option>
	</optgroup>
	<optgroup label="Girone F">
		<option>Olanda</option>
		<option>Giappone</option>
		<option>Tunisia</option>
		<option>Vincitore_B</option>
	</optgroup>
	<optgroup label="Girone G">
		<option>Belgio</option>
		<option>Iran</option>
		<option>Egitto</option>
		<option>Nuova Zelanda</option>
	</optgroup>
	<optgroup label="Girone H">
		<option>Spagna</option>
		<option>Uruguay</option>
		<option>Arabia Saudita</option>
		<option>Capo Verde</option>
	</optgroup>
	<optgroup label="Girone I">
		<option>Francia</option>
		<option>Senegal</option>
		<option>Norvegia</option>
		<option>Vincitore_2</option>
	</optgroup>
	<optgroup label="Girone J">
		<option>Argentina</option>
		<option>Austria</option>
		<option>Algeria</option>
		<option>Giordania</option>
	</optgroup>
	<optgroup label="Girone K">
		<option>Portogallo</option>
		<option>Colombia</option>
		<option>Uzbekistan</option>
		<option>Vincitore_1</option>
	</optgroup>
	<optgroup label="Girone L">
		<option>Inghilterra</option>
		<option>Croazia</option>
		<option>Panama</option>
		<option>Ghana</option>
	</optgroup>
</select>



</TD>
		</TR>
		<TR>
			<TD HEIGHT=21 ALIGN=LEFT BGCOLOR="#CCFFCC">

<!-- M17 -->
<select name="question_16" class="pronostico" onchange="aggiornaPronostici(6,1)">
	<option selected>&nbsp;</option>
	<optgroup label="Girone A">
		<option>Messico</option>
		<option>Corea del Sud</option>
		<option>Sudafrica</option>
		<option>Vincitore_D</option>
	</optgroup>
	<optgroup label="Girone B">
		<option>Canada</option>
		<option>Svizzera</option>
		<option>Qatar</option>
		<option>Vincitore_A</option>
	</optgroup>
	<optgroup label="Girone C">
		<option>Brasile</option>
		<option>Marocco</option>
		<option>Scozia</option>
		<option>Haiti</option>
	</optgroup>
	<optgroup label="Girone D">
		<option>USA</option>
		<option>Australia</option>
		<option>Paraguay</option>
		<option>Vincitore_C</option>
	</optgroup>
	<optgroup label="Girone E">
		<option>Germania</option>
		<option>Ecuador</option>
		<option>Costa d'Avorio</option>
		<option>Curaçao</option>
	</optgroup>
	<optgroup label="Girone F">
		<option>Olanda</option>
		<option>Giappone</option>
		<option>Tunisia</option>
		<option>Vincitore_B</option>
	</optgroup>
	<optgroup label="Girone G">
		<option>Belgio</option>
		<option>Iran</option>
		<option>Egitto</option>
		<option>Nuova Zelanda</option>
	</optgroup>
	<optgroup label="Girone H">
		<option>Spagna</option>
		<option>Uruguay</option>
		<option>Arabia Saudita</option>
		<option>Capo Verde</option>
	</optgroup>
	<optgroup label="Girone I">
		<option>Francia</option>
		<option>Senegal</option>
		<option>Norvegia</option>
		<option>Vincitore_2</option>
	</optgroup>
	<optgroup label="Girone J">
		<option>Argentina</option>
		<option>Austria</option>
		<option>Algeria</option>
		<option>Giordania</option>
	</optgroup>
	<optgroup label="Girone K">
		<option>Portogallo</option>
		<option>Colombia</option>
		<option>Uzbekistan</option>
		<option>Vincitore_1</option>
	</optgroup>
	<optgroup label="Girone L">
		<option>Inghilterra</option>
		<option>Croazia</option>
		<option>Panama</option>
		<option>Ghana</option>
	</optgroup>
</select>

</TD>
			<TD ALIGN=LEFT BGCOLOR="#CCFFCC">

<!-- M18 -->
<select name="question_17" class="pronostico" onchange="aggiornaPronostici(6,1)">
	<option selected>&nbsp;</option>
	<optgroup label="Girone A">
		<option>Messico</option>
		<option>Corea del Sud</option>
		<option>Sudafrica</option>
		<option>Vincitore_D</option>
	</optgroup>
	<optgroup label="Girone B">
		<option>Canada</option>
		<option>Svizzera</option>
		<option>Qatar</option>
		<option>Vincitore_A</option>
	</optgroup>
	<optgroup label="Girone C">
		<option>Brasile</option>
		<option>Marocco</option>
		<option>Scozia</option>
		<option>Haiti</option>
	</optgroup>
	<optgroup label="Girone D">
		<option>USA</option>
		<option>Australia</option>
		<option>Paraguay</option>
		<option>Vincitore_C</option>
	</optgroup>
	<optgroup label="Girone E">
		<option>Germania</option>
		<option>Ecuador</option>
		<option>Costa d'Avorio</option>
		<option>Curaçao</option>
	</optgroup>
	<optgroup label="Girone F">
		<option>Olanda</option>
		<option>Giappone</option>
		<option>Tunisia</option>
		<option>Vincitore_B</option>
	</optgroup>
	<optgroup label="Girone G">
		<option>Belgio</option>
		<option>Iran</option>
		<option>Egitto</option>
		<option>Nuova Zelanda</option>
	</optgroup>
	<optgroup label="Girone H">
		<option>Spagna</option>
		<option>Uruguay</option>
		<option>Arabia Saudita</option>
		<option>Capo Verde</option>
	</optgroup>
	<optgroup label="Girone I">
		<option>Francia</option>
		<option>Senegal</option>
		<option>Norvegia</option>
		<option>Vincitore_2</option>
	</optgroup>
	<optgroup label="Girone J">
		<option>Argentina</option>
		<option>Austria</option>
		<option>Algeria</option>
		<option>Giordania</option>
	</optgroup>
	<optgroup label="Girone K">
		<option>Portogallo</option>
		<option>Colombia</option>
		<option>Uzbekistan</option>
		<option>Vincitore_1</option>
	</optgroup>
	<optgroup label="Girone L">
		<option>Inghilterra</option>
		<option>Croazia</option>
		<option>Panama</option>
		<option>Ghana</option>
	</optgroup>
</select>


</TD>
			<TD ALIGN=LEFT BGCOLOR="#CCFFCC">

<!-- M19 -->
<select name="question_18" class="pronostico" onchange="aggiornaPronostici(6,1)">
	<option selected>&nbsp;</option>
	<optgroup label="Girone A">
		<option>Messico</option>
		<option>Corea del Sud</option>
		<option>Sudafrica</option>
		<option>Vincitore_D</option>
	</optgroup>
	<optgroup label="Girone B">
		<option>Canada</option>
		<option>Svizzera</option>
		<option>Qatar</option>
		<option>Vincitore_A</option>
	</optgroup>
	<optgroup label="Girone C">
		<option>Brasile</option>
		<option>Marocco</option>
		<option>Scozia</option>
		<option>Haiti</option>
	</optgroup>
	<optgroup label="Girone D">
		<option>USA</option>
		<option>Australia</option>
		<option>Paraguay</option>
		<option>Vincitore_C</option>
	</optgroup>
	<optgroup label="Girone E">
		<option>Germania</option>
		<option>Ecuador</option>
		<option>Costa d'Avorio</option>
		<option>Curaçao</option>
	</optgroup>
	<optgroup label="Girone F">
		<option>Olanda</option>
		<option>Giappone</option>
		<option>Tunisia</option>
		<option>Vincitore_B</option>
	</optgroup>
	<optgroup label="Girone G">
		<option>Belgio</option>
		<option>Iran</option>
		<option>Egitto</option>
		<option>Nuova Zelanda</option>
	</optgroup>
	<optgroup label="Girone H">
		<option>Spagna</option>
		<option>Uruguay</option>
		<option>Arabia Saudita</option>
		<option>Capo Verde</option>
	</optgroup>
	<optgroup label="Girone I">
		<option>Francia</option>
		<option>Senegal</option>
		<option>Norvegia</option>
		<option>Vincitore_2</option>
	</optgroup>
	<optgroup label="Girone J">
		<option>Argentina</option>
		<option>Austria</option>
		<option>Algeria</option>
		<option>Giordania</option>
	</optgroup>
	<optgroup label="Girone K">
		<option>Portogallo</option>
		<option>Colombia</option>
		<option>Uzbekistan</option>
		<option>Vincitore_1</option>
	</optgroup>
	<optgroup label="Girone L">
		<option>Inghilterra</option>
		<option>Croazia</option>
		<option>Panama</option>
		<option>Ghana</option>
	</optgroup>
</select>


</TD>
			<TD ALIGN=LEFT BGCOLOR="#CCFFCC">


<!-- M20 -->
<select name="question_19" class="pronostico" onchange="aggiornaPronostici(6,1)">
	<option selected>&nbsp;</option>
	<optgroup label="Girone A">
		<option>Messico</option>
		<option>Corea del Sud</option>
		<option>Sudafrica</option>
		<option>Vincitore_D</option>
	</optgroup>
	<optgroup label="Girone B">
		<option>Canada</option>
		<option>Svizzera</option>
		<option>Qatar</option>
		<option>Vincitore_A</option>
	</optgroup>
	<optgroup label="Girone C">
		<option>Brasile</option>
		<option>Marocco</option>
		<option>Scozia</option>
		<option>Haiti</option>
	</optgroup>
	<optgroup label="Girone D">
		<option>USA</option>
		<option>Australia</option>
		<option>Paraguay</option>
		<option>Vincitore_C</option>
	</optgroup>
	<optgroup label="Girone E">
		<option>Germania</option>
		<option>Ecuador</option>
		<option>Costa d'Avorio</option>
		<option>Curaçao</option>
	</optgroup>
	<optgroup label="Girone F">
		<option>Olanda</option>
		<option>Giappone</option>
		<option>Tunisia</option>
		<option>Vincitore_B</option>
	</optgroup>
	<optgroup label="Girone G">
		<option>Belgio</option>
		<option>Iran</option>
		<option>Egitto</option>
		<option>Nuova Zelanda</option>
	</optgroup>
	<optgroup label="Girone H">
		<option>Spagna</option>
		<option>Uruguay</option>
		<option>Arabia Saudita</option>
		<option>Capo Verde</option>
	</optgroup>
	<optgroup label="Girone I">
		<option>Francia</option>
		<option>Senegal</option>
		<option>Norvegia</option>
		<option>Vincitore_2</option>
	</optgroup>
	<optgroup label="Girone J">
		<option>Argentina</option>
		<option>Austria</option>
		<option>Algeria</option>
		<option>Giordania</option>
	</optgroup>
	<optgroup label="Girone K">
		<option>Portogallo</option>
		<option>Colombia</option>
		<option>Uzbekistan</option>
		<option>Vincitore_1</option>
	</optgroup>
	<optgroup label="Girone L">
		<option>Inghilterra</option>
		<option>Croazia</option>
		<option>Panama</option>
		<option>Ghana</option>
	</optgroup>
</select>


</TD>
		</TR>
		<TR>
			<TD HEIGHT=21 ALIGN=LEFT BGCOLOR="#CCFFCC">

<!-- M21 -->
<select name="question_20" class="pronostico" onchange="aggiornaPronostici(6,1)">
	<option selected>&nbsp;</option>
	<optgroup label="Girone A">
		<option>Messico</option>
		<option>Corea del Sud</option>
		<option>Sudafrica</option>
		<option>Vincitore_D</option>
	</optgroup>
	<optgroup label="Girone B">
		<option>Canada</option>
		<option>Svizzera</option>
		<option>Qatar</option>
		<option>Vincitore_A</option>
	</optgroup>
	<optgroup label="Girone C">
		<option>Brasile</option>
		<option>Marocco</option>
		<option>Scozia</option>
		<option>Haiti</option>
	</optgroup>
	<optgroup label="Girone D">
		<option>USA</option>
		<option>Australia</option>
		<option>Paraguay</option>
		<option>Vincitore_C</option>
	</optgroup>
	<optgroup label="Girone E">
		<option>Germania</option>
		<option>Ecuador</option>
		<option>Costa d'Avorio</option>
		<option>Curaçao</option>
	</optgroup>
	<optgroup label="Girone F">
		<option>Olanda</option>
		<option>Giappone</option>
		<option>Tunisia</option>
		<option>Vincitore_B</option>
	</optgroup>
	<optgroup label="Girone G">
		<option>Belgio</option>
		<option>Iran</option>
		<option>Egitto</option>
		<option>Nuova Zelanda</option>
	</optgroup>
	<optgroup label="Girone H">
		<option>Spagna</option>
		<option>Uruguay</option>
		<option>Arabia Saudita</option>
		<option>Capo Verde</option>
	</optgroup>
	<optgroup label="Girone I">
		<option>Francia</option>
		<option>Senegal</option>
		<option>Norvegia</option>
		<option>Vincitore_2</option>
	</optgroup>
	<optgroup label="Girone J">
		<option>Argentina</option>
		<option>Austria</option>
		<option>Algeria</option>
		<option>Giordania</option>
	</optgroup>
	<optgroup label="Girone K">
		<option>Portogallo</option>
		<option>Colombia</option>
		<option>Uzbekistan</option>
		<option>Vincitore_1</option>
	</optgroup>
	<optgroup label="Girone L">
		<option>Inghilterra</option>
		<option>Croazia</option>
		<option>Panama</option>
		<option>Ghana</option>
	</optgroup>
</select>


</TD>
			<TD ALIGN=LEFT BGCOLOR="#CCFFCC">

<!-- M22 -->
<select name="question_21" class="pronostico" onchange="aggiornaPronostici(6,1)">
	<option selected>&nbsp;</option>
	<optgroup label="Girone A">
		<option>Messico</option>
		<option>Corea del Sud</option>
		<option>Sudafrica</option>
		<option>Vincitore_D</option>
	</optgroup>
	<optgroup label="Girone B">
		<option>Canada</option>
		<option>Svizzera</option>
		<option>Qatar</option>
		<option>Vincitore_A</option>
	</optgroup>
	<optgroup label="Girone C">
		<option>Brasile</option>
		<option>Marocco</option>
		<option>Scozia</option>
		<option>Haiti</option>
	</optgroup>
	<optgroup label="Girone D">
		<option>USA</option>
		<option>Australia</option>
		<option>Paraguay</option>
		<option>Vincitore_C</option>
	</optgroup>
	<optgroup label="Girone E">
		<option>Germania</option>
		<option>Ecuador</option>
		<option>Costa d'Avorio</option>
		<option>Curaçao</option>
	</optgroup>
	<optgroup label="Girone F">
		<option>Olanda</option>
		<option>Giappone</option>
		<option>Tunisia</option>
		<option>Vincitore_B</option>
	</optgroup>
	<optgroup label="Girone G">
		<option>Belgio</option>
		<option>Iran</option>
		<option>Egitto</option>
		<option>Nuova Zelanda</option>
	</optgroup>
	<optgroup label="Girone H">
		<option>Spagna</option>
		<option>Uruguay</option>
		<option>Arabia Saudita</option>
		<option>Capo Verde</option>
	</optgroup>
	<optgroup label="Girone I">
		<option>Francia</option>
		<option>Senegal</option>
		<option>Norvegia</option>
		<option>Vincitore_2</option>
	</optgroup>
	<optgroup label="Girone J">
		<option>Argentina</option>
		<option>Austria</option>
		<option>Algeria</option>
		<option>Giordania</option>
	</optgroup>
	<optgroup label="Girone K">
		<option>Portogallo</option>
		<option>Colombia</option>
		<option>Uzbekistan</option>
		<option>Vincitore_1</option>
	</optgroup>
	<optgroup label="Girone L">
		<option>Inghilterra</option>
		<option>Croazia</option>
		<option>Panama</option>
		<option>Ghana</option>
	</optgroup>
</select>



</TD>
			<TD ALIGN=LEFT BGCOLOR="#CCFFCC">

<!-- M23 -->
<select name="question_22" class="pronostico" onchange="aggiornaPronostici(6,1)">
	<option selected>&nbsp;</option>
	<optgroup label="Girone A">
		<option>Messico</option>
		<option>Corea del Sud</option>
		<option>Sudafrica</option>
		<option>Vincitore_D</option>
	</optgroup>
	<optgroup label="Girone B">
		<option>Canada</option>
		<option>Svizzera</option>
		<option>Qatar</option>
		<option>Vincitore_A</option>
	</optgroup>
	<optgroup label="Girone C">
		<option>Brasile</option>
		<option>Marocco</option>
		<option>Scozia</option>
		<option>Haiti</option>
	</optgroup>
	<optgroup label="Girone D">
		<option>USA</option>
		<option>Australia</option>
		<option>Paraguay</option>
		<option>Vincitore_C</option>
	</optgroup>
	<optgroup label="Girone E">
		<option>Germania</option>
		<option>Ecuador</option>
		<option>Costa d'Avorio</option>
		<option>Curaçao</option>
	</optgroup>
	<optgroup label="Girone F">
		<option>Olanda</option>
		<option>Giappone</option>
		<option>Tunisia</option>
		<option>Vincitore_B</option>
	</optgroup>
	<optgroup label="Girone G">
		<option>Belgio</option>
		<option>Iran</option>
		<option>Egitto</option>
		<option>Nuova Zelanda</option>
	</optgroup>
	<optgroup label="Girone H">
		<option>Spagna</option>
		<option>Uruguay</option>
		<option>Arabia Saudita</option>
		<option>Capo Verde</option>
	</optgroup>
	<optgroup label="Girone I">
		<option>Francia</option>
		<option>Senegal</option>
		<option>Norvegia</option>
		<option>Vincitore_2</option>
	</optgroup>
	<optgroup label="Girone J">
		<option>Argentina</option>
		<option>Austria</option>
		<option>Algeria</option>
		<option>Giordania</option>
	</optgroup>
	<optgroup label="Girone K">
		<option>Portogallo</option>
		<option>Colombia</option>
		<option>Uzbekistan</option>
		<option>Vincitore_1</option>
	</optgroup>
	<optgroup label="Girone L">
		<option>Inghilterra</option>
		<option>Croazia</option>
		<option>Panama</option>
		<option>Ghana</option>
	</optgroup>
</select>





</TD>
			<TD ALIGN=LEFT BGCOLOR="#CCFFCC">

<!-- M24 -->
<select name="question_23" class="pronostico" onchange="aggiornaPronostici(6,1)">
	<option selected>&nbsp;</option>
	<optgroup label="Girone A">
		<option>Messico</option>
		<option>Corea del Sud</option>
		<option>Sudafrica</option>
		<option>Vincitore_D</option>
	</optgroup>
	<optgroup label="Girone B">
		<option>Canada</option>
		<option>Svizzera</option>
		<option>Qatar</option>
		<option>Vincitore_A</option>
	</optgroup>
	<optgroup label="Girone C">
		<option>Brasile</option>
		<option>Marocco</option>
		<option>Scozia</option>
		<option>Haiti</option>
	</optgroup>
	<optgroup label="Girone D">
		<option>USA</option>
		<option>Australia</option>
		<option>Paraguay</option>
		<option>Vincitore_C</option>
	</optgroup>
	<optgroup label="Girone E">
		<option>Germania</option>
		<option>Ecuador</option>
		<option>Costa d'Avorio</option>
		<option>Curaçao</option>
	</optgroup>
	<optgroup label="Girone F">
		<option>Olanda</option>
		<option>Giappone</option>
		<option>Tunisia</option>
		<option>Vincitore_B</option>
	</optgroup>
	<optgroup label="Girone G">
		<option>Belgio</option>
		<option>Iran</option>
		<option>Egitto</option>
		<option>Nuova Zelanda</option>
	</optgroup>
	<optgroup label="Girone H">
		<option>Spagna</option>
		<option>Uruguay</option>
		<option>Arabia Saudita</option>
		<option>Capo Verde</option>
	</optgroup>
	<optgroup label="Girone I">
		<option>Francia</option>
		<option>Senegal</option>
		<option>Norvegia</option>
		<option>Vincitore_2</option>
	</optgroup>
	<optgroup label="Girone J">
		<option>Argentina</option>
		<option>Austria</option>
		<option>Algeria</option>
		<option>Giordania</option>
	</optgroup>
	<optgroup label="Girone K">
		<option>Portogallo</option>
		<option>Colombia</option>
		<option>Uzbekistan</option>
		<option>Vincitore_1</option>
	</optgroup>
	<optgroup label="Girone L">
		<option>Inghilterra</option>
		<option>Croazia</option>
		<option>Panama</option>
		<option>Ghana</option>
	</optgroup>
</select>



</TD>
		</TR>


		<TR>
			<TD HEIGHT=21 ALIGN=LEFT BGCOLOR="#CCFFCC">

<!-- M25 -->
<select name="question_24" class="pronostico" onchange="aggiornaPronostici(6,1)">
	<option selected>&nbsp;</option>
	<optgroup label="Girone A">
		<option>Messico</option>
		<option>Corea del Sud</option>
		<option>Sudafrica</option>
		<option>Vincitore_D</option>
	</optgroup>
	<optgroup label="Girone B">
		<option>Canada</option>
		<option>Svizzera</option>
		<option>Qatar</option>
		<option>Vincitore_A</option>
	</optgroup>
	<optgroup label="Girone C">
		<option>Brasile</option>
		<option>Marocco</option>
		<option>Scozia</option>
		<option>Haiti</option>
	</optgroup>
	<optgroup label="Girone D">
		<option>USA</option>
		<option>Australia</option>
		<option>Paraguay</option>
		<option>Vincitore_C</option>
	</optgroup>
	<optgroup label="Girone E">
		<option>Germania</option>
		<option>Ecuador</option>
		<option>Costa d'Avorio</option>
		<option>Curaçao</option>
	</optgroup>
	<optgroup label="Girone F">
		<option>Olanda</option>
		<option>Giappone</option>
		<option>Tunisia</option>
		<option>Vincitore_B</option>
	</optgroup>
	<optgroup label="Girone G">
		<option>Belgio</option>
		<option>Iran</option>
		<option>Egitto</option>
		<option>Nuova Zelanda</option>
	</optgroup>
	<optgroup label="Girone H">
		<option>Spagna</option>
		<option>Uruguay</option>
		<option>Arabia Saudita</option>
		<option>Capo Verde</option>
	</optgroup>
	<optgroup label="Girone I">
		<option>Francia</option>
		<option>Senegal</option>
		<option>Norvegia</option>
		<option>Vincitore_2</option>
	</optgroup>
	<optgroup label="Girone J">
		<option>Argentina</option>
		<option>Austria</option>
		<option>Algeria</option>
		<option>Giordania</option>
	</optgroup>
	<optgroup label="Girone K">
		<option>Portogallo</option>
		<option>Colombia</option>
		<option>Uzbekistan</option>
		<option>Vincitore_1</option>
	</optgroup>
	<optgroup label="Girone L">
		<option>Inghilterra</option>
		<option>Croazia</option>
		<option>Panama</option>
		<option>Ghana</option>
	</optgroup>
</select>



</TD>
			<TD ALIGN=LEFT BGCOLOR="#CCFFCC">

<!-- M26 -->
<select name="question_25" class="pronostico" onchange="aggiornaPronostici(6,1)">
	<option selected>&nbsp;</option>
	<optgroup label="Girone A">
		<option>Messico</option>
		<option>Corea del Sud</option>
		<option>Sudafrica</option>
		<option>Vincitore_D</option>
	</optgroup>
	<optgroup label="Girone B">
		<option>Canada</option>
		<option>Svizzera</option>
		<option>Qatar</option>
		<option>Vincitore_A</option>
	</optgroup>
	<optgroup label="Girone C">
		<option>Brasile</option>
		<option>Marocco</option>
		<option>Scozia</option>
		<option>Haiti</option>
	</optgroup>
	<optgroup label="Girone D">
		<option>USA</option>
		<option>Australia</option>
		<option>Paraguay</option>
		<option>Vincitore_C</option>
	</optgroup>
	<optgroup label="Girone E">
		<option>Germania</option>
		<option>Ecuador</option>
		<option>Costa d'Avorio</option>
		<option>Curaçao</option>
	</optgroup>
	<optgroup label="Girone F">
		<option>Olanda</option>
		<option>Giappone</option>
		<option>Tunisia</option>
		<option>Vincitore_B</option>
	</optgroup>
	<optgroup label="Girone G">
		<option>Belgio</option>
		<option>Iran</option>
		<option>Egitto</option>
		<option>Nuova Zelanda</option>
	</optgroup>
	<optgroup label="Girone H">
		<option>Spagna</option>
		<option>Uruguay</option>
		<option>Arabia Saudita</option>
		<option>Capo Verde</option>
	</optgroup>
	<optgroup label="Girone I">
		<option>Francia</option>
		<option>Senegal</option>
		<option>Norvegia</option>
		<option>Vincitore_2</option>
	</optgroup>
	<optgroup label="Girone J">
		<option>Argentina</option>
		<option>Austria</option>
		<option>Algeria</option>
		<option>Giordania</option>
	</optgroup>
	<optgroup label="Girone K">
		<option>Portogallo</option>
		<option>Colombia</option>
		<option>Uzbekistan</option>
		<option>Vincitore_1</option>
	</optgroup>
	<optgroup label="Girone L">
		<option>Inghilterra</option>
		<option>Croazia</option>
		<option>Panama</option>
		<option>Ghana</option>
	</optgroup>
</select>


</TD>
			<TD ALIGN=LEFT BGCOLOR="#CCFFCC">

<!-- M27 -->
<select name="question_26" class="pronostico" onchange="aggiornaPronostici(6,1)">
	<option selected>&nbsp;</option>
	<optgroup label="Girone A">
		<option>Messico</option>
		<option>Corea del Sud</option>
		<option>Sudafrica</option>
		<option>Vincitore_D</option>
	</optgroup>
	<optgroup label="Girone B">
		<option>Canada</option>
		<option>Svizzera</option>
		<option>Qatar</option>
		<option>Vincitore_A</option>
	</optgroup>
	<optgroup label="Girone C">
		<option>Brasile</option>
		<option>Marocco</option>
		<option>Scozia</option>
		<option>Haiti</option>
	</optgroup>
	<optgroup label="Girone D">
		<option>USA</option>
		<option>Australia</option>
		<option>Paraguay</option>
		<option>Vincitore_C</option>
	</optgroup>
	<optgroup label="Girone E">
		<option>Germania</option>
		<option>Ecuador</option>
		<option>Costa d'Avorio</option>
		<option>Curaçao</option>
	</optgroup>
	<optgroup label="Girone F">
		<option>Olanda</option>
		<option>Giappone</option>
		<option>Tunisia</option>
		<option>Vincitore_B</option>
	</optgroup>
	<optgroup label="Girone G">
		<option>Belgio</option>
		<option>Iran</option>
		<option>Egitto</option>
		<option>Nuova Zelanda</option>
	</optgroup>
	<optgroup label="Girone H">
		<option>Spagna</option>
		<option>Uruguay</option>
		<option>Arabia Saudita</option>
		<option>Capo Verde</option>
	</optgroup>
	<optgroup label="Girone I">
		<option>Francia</option>
		<option>Senegal</option>
		<option>Norvegia</option>
		<option>Vincitore_2</option>
	</optgroup>
	<optgroup label="Girone J">
		<option>Argentina</option>
		<option>Austria</option>
		<option>Algeria</option>
		<option>Giordania</option>
	</optgroup>
	<optgroup label="Girone K">
		<option>Portogallo</option>
		<option>Colombia</option>
		<option>Uzbekistan</option>
		<option>Vincitore_1</option>
	</optgroup>
	<optgroup label="Girone L">
		<option>Inghilterra</option>
		<option>Croazia</option>
		<option>Panama</option>
		<option>Ghana</option>
	</optgroup>
</select>


</TD>
			<TD ALIGN=LEFT BGCOLOR="#CCFFCC">


<!-- M28 -->
<select name="question_27" class="pronostico" onchange="aggiornaPronostici(6,1)">
	<option selected>&nbsp;</option>
	<optgroup label="Girone A">
		<option>Messico</option>
		<option>Corea del Sud</option>
		<option>Sudafrica</option>
		<option>Vincitore_D</option>
	</optgroup>
	<optgroup label="Girone B">
		<option>Canada</option>
		<option>Svizzera</option>
		<option>Qatar</option>
		<option>Vincitore_A</option>
	</optgroup>
	<optgroup label="Girone C">
		<option>Brasile</option>
		<option>Marocco</option>
		<option>Scozia</option>
		<option>Haiti</option>
	</optgroup>
	<optgroup label="Girone D">
		<option>USA</option>
		<option>Australia</option>
		<option>Paraguay</option>
		<option>Vincitore_C</option>
	</optgroup>
	<optgroup label="Girone E">
		<option>Germania</option>
		<option>Ecuador</option>
		<option>Costa d'Avorio</option>
		<option>Curaçao</option>
	</optgroup>
	<optgroup label="Girone F">
		<option>Olanda</option>
		<option>Giappone</option>
		<option>Tunisia</option>
		<option>Vincitore_B</option>
	</optgroup>
	<optgroup label="Girone G">
		<option>Belgio</option>
		<option>Iran</option>
		<option>Egitto</option>
		<option>Nuova Zelanda</option>
	</optgroup>
	<optgroup label="Girone H">
		<option>Spagna</option>
		<option>Uruguay</option>
		<option>Arabia Saudita</option>
		<option>Capo Verde</option>
	</optgroup>
	<optgroup label="Girone I">
		<option>Francia</option>
		<option>Senegal</option>
		<option>Norvegia</option>
		<option>Vincitore_2</option>
	</optgroup>
	<optgroup label="Girone J">
		<option>Argentina</option>
		<option>Austria</option>
		<option>Algeria</option>
		<option>Giordania</option>
	</optgroup>
	<optgroup label="Girone K">
		<option>Portogallo</option>
		<option>Colombia</option>
		<option>Uzbekistan</option>
		<option>Vincitore_1</option>
	</optgroup>
	<optgroup label="Girone L">
		<option>Inghilterra</option>
		<option>Croazia</option>
		<option>Panama</option>
		<option>Ghana</option>
	</optgroup>
</select>


</TD>
		</TR>
		<TR>
			<TD HEIGHT=21 ALIGN=LEFT BGCOLOR="#CCFFCC">

<!-- M29 -->
<select name="question_28" class="pronostico" onchange="aggiornaPronostici(6,1)">
	<option selected>&nbsp;</option>
	<optgroup label="Girone A">
		<option>Messico</option>
		<option>Corea del Sud</option>
		<option>Sudafrica</option>
		<option>Vincitore_D</option>
	</optgroup>
	<optgroup label="Girone B">
		<option>Canada</option>
		<option>Svizzera</option>
		<option>Qatar</option>
		<option>Vincitore_A</option>
	</optgroup>
	<optgroup label="Girone C">
		<option>Brasile</option>
		<option>Marocco</option>
		<option>Scozia</option>
		<option>Haiti</option>
	</optgroup>
	<optgroup label="Girone D">
		<option>USA</option>
		<option>Australia</option>
		<option>Paraguay</option>
		<option>Vincitore_C</option>
	</optgroup>
	<optgroup label="Girone E">
		<option>Germania</option>
		<option>Ecuador</option>
		<option>Costa d'Avorio</option>
		<option>Curaçao</option>
	</optgroup>
	<optgroup label="Girone F">
		<option>Olanda</option>
		<option>Giappone</option>
		<option>Tunisia</option>
		<option>Vincitore_B</option>
	</optgroup>
	<optgroup label="Girone G">
		<option>Belgio</option>
		<option>Iran</option>
		<option>Egitto</option>
		<option>Nuova Zelanda</option>
	</optgroup>
	<optgroup label="Girone H">
		<option>Spagna</option>
		<option>Uruguay</option>
		<option>Arabia Saudita</option>
		<option>Capo Verde</option>
	</optgroup>
	<optgroup label="Girone I">
		<option>Francia</option>
		<option>Senegal</option>
		<option>Norvegia</option>
		<option>Vincitore_2</option>
	</optgroup>
	<optgroup label="Girone J">
		<option>Argentina</option>
		<option>Austria</option>
		<option>Algeria</option>
		<option>Giordania</option>
	</optgroup>
	<optgroup label="Girone K">
		<option>Portogallo</option>
		<option>Colombia</option>
		<option>Uzbekistan</option>
		<option>Vincitore_1</option>
	</optgroup>
	<optgroup label="Girone L">
		<option>Inghilterra</option>
		<option>Croazia</option>
		<option>Panama</option>
		<option>Ghana</option>
	</optgroup>
</select>


</TD>
			<TD ALIGN=LEFT BGCOLOR="#CCFFCC">

<!-- M30 -->
<select name="question_29" class="pronostico" onchange="aggiornaPronostici(6,1)">
	<option selected>&nbsp;</option>
	<optgroup label="Girone A">
		<option>Messico</option>
		<option>Corea del Sud</option>
		<option>Sudafrica</option>
		<option>Vincitore_D</option>
	</optgroup>
	<optgroup label="Girone B">
		<option>Canada</option>
		<option>Svizzera</option>
		<option>Qatar</option>
		<option>Vincitore_A</option>
	</optgroup>
	<optgroup label="Girone C">
		<option>Brasile</option>
		<option>Marocco</option>
		<option>Scozia</option>
		<option>Haiti</option>
	</optgroup>
	<optgroup label="Girone D">
		<option>USA</option>
		<option>Australia</option>
		<option>Paraguay</option>
		<option>Vincitore_C</option>
	</optgroup>
	<optgroup label="Girone E">
		<option>Germania</option>
		<option>Ecuador</option>
		<option>Costa d'Avorio</option>
		<option>Curaçao</option>
	</optgroup>
	<optgroup label="Girone F">
		<option>Olanda</option>
		<option>Giappone</option>
		<option>Tunisia</option>
		<option>Vincitore_B</option>
	</optgroup>
	<optgroup label="Girone G">
		<option>Belgio</option>
		<option>Iran</option>
		<option>Egitto</option>
		<option>Nuova Zelanda</option>
	</optgroup>
	<optgroup label="Girone H">
		<option>Spagna</option>
		<option>Uruguay</option>
		<option>Arabia Saudita</option>
		<option>Capo Verde</option>
	</optgroup>
	<optgroup label="Girone I">
		<option>Francia</option>
		<option>Senegal</option>
		<option>Norvegia</option>
		<option>Vincitore_2</option>
	</optgroup>
	<optgroup label="Girone J">
		<option>Argentina</option>
		<option>Austria</option>
		<option>Algeria</option>
		<option>Giordania</option>
	</optgroup>
	<optgroup label="Girone K">
		<option>Portogallo</option>
		<option>Colombia</option>
		<option>Uzbekistan</option>
		<option>Vincitore_1</option>
	</optgroup>
	<optgroup label="Girone L">
		<option>Inghilterra</option>
		<option>Croazia</option>
		<option>Panama</option>
		<option>Ghana</option>
	</optgroup>
</select>



</TD>
			<TD ALIGN=LEFT BGCOLOR="#CCFFCC">

<!-- M31 -->
<select name="question_30" class="pronostico" onchange="aggiornaPronostici(6,1)">
	<option selected>&nbsp;</option>
	<optgroup label="Girone A">
		<option>Messico</option>
		<option>Corea del Sud</option>
		<option>Sudafrica</option>
		<option>Vincitore_D</option>
	</optgroup>
	<optgroup label="Girone B">
		<option>Canada</option>
		<option>Svizzera</option>
		<option>Qatar</option>
		<option>Vincitore_A</option>
	</optgroup>
	<optgroup label="Girone C">
		<option>Brasile</option>
		<option>Marocco</option>
		<option>Scozia</option>
		<option>Haiti</option>
	</optgroup>
	<optgroup label="Girone D">
		<option>USA</option>
		<option>Australia</option>
		<option>Paraguay</option>
		<option>Vincitore_C</option>
	</optgroup>
	<optgroup label="Girone E">
		<option>Germania</option>
		<option>Ecuador</option>
		<option>Costa d'Avorio</option>
		<option>Curaçao</option>
	</optgroup>
	<optgroup label="Girone F">
		<option>Olanda</option>
		<option>Giappone</option>
		<option>Tunisia</option>
		<option>Vincitore_B</option>
	</optgroup>
	<optgroup label="Girone G">
		<option>Belgio</option>
		<option>Iran</option>
		<option>Egitto</option>
		<option>Nuova Zelanda</option>
	</optgroup>
	<optgroup label="Girone H">
		<option>Spagna</option>
		<option>Uruguay</option>
		<option>Arabia Saudita</option>
		<option>Capo Verde</option>
	</optgroup>
	<optgroup label="Girone I">
		<option>Francia</option>
		<option>Senegal</option>
		<option>Norvegia</option>
		<option>Vincitore_2</option>
	</optgroup>
	<optgroup label="Girone J">
		<option>Argentina</option>
		<option>Austria</option>
		<option>Algeria</option>
		<option>Giordania</option>
	</optgroup>
	<optgroup label="Girone K">
		<option>Portogallo</option>
		<option>Colombia</option>
		<option>Uzbekistan</option>
		<option>Vincitore_1</option>
	</optgroup>
	<optgroup label="Girone L">
		<option>Inghilterra</option>
		<option>Croazia</option>
		<option>Panama</option>
		<option>Ghana</option>
	</optgroup>
</select>





</TD>
			<TD ALIGN=LEFT BGCOLOR="#CCFFCC">

<!-- M32 -->
<select name="question_31" class="pronostico" onchange="aggiornaPronostici(6,1)">
	<option selected>&nbsp;</option>
	<optgroup label="Girone A">
		<option>Messico</option>
		<option>Corea del Sud</option>
		<option>Sudafrica</option>
		<option>Vincitore_D</option>
	</optgroup>
	<optgroup label="Girone B">
		<option>Canada</option>
		<option>Svizzera</option>
		<option>Qatar</option>
		<option>Vincitore_A</option>
	</optgroup>
	<optgroup label="Girone C">
		<option>Brasile</option>
		<option>Marocco</option>
		<option>Scozia</option>
		<option>Haiti</option>
	</optgroup>
	<optgroup label="Girone D">
		<option>USA</option>
		<option>Australia</option>
		<option>Paraguay</option>
		<option>Vincitore_C</option>
	</optgroup>
	<optgroup label="Girone E">
		<option>Germania</option>
		<option>Ecuador</option>
		<option>Costa d'Avorio</option>
		<option>Curaçao</option>
	</optgroup>
	<optgroup label="Girone F">
		<option>Olanda</option>
		<option>Giappone</option>
		<option>Tunisia</option>
		<option>Vincitore_B</option>
	</optgroup>
	<optgroup label="Girone G">
		<option>Belgio</option>
		<option>Iran</option>
		<option>Egitto</option>
		<option>Nuova Zelanda</option>
	</optgroup>
	<optgroup label="Girone H">
		<option>Spagna</option>
		<option>Uruguay</option>
		<option>Arabia Saudita</option>
		<option>Capo Verde</option>
	</optgroup>
	<optgroup label="Girone I">
		<option>Francia</option>
		<option>Senegal</option>
		<option>Norvegia</option>
		<option>Vincitore_2</option>
	</optgroup>
	<optgroup label="Girone J">
		<option>Argentina</option>
		<option>Austria</option>
		<option>Algeria</option>
		<option>Giordania</option>
	</optgroup>
	<optgroup label="Girone K">
		<option>Portogallo</option>
		<option>Colombia</option>
		<option>Uzbekistan</option>
		<option>Vincitore_1</option>
	</optgroup>
	<optgroup label="Girone L">
		<option>Inghilterra</option>
		<option>Croazia</option>
		<option>Panama</option>
		<option>Ghana</option>
	</optgroup>
</select>


</TD>
		</TR>



		<TR>
			<TD HEIGHT=16 ALIGN=LEFT><BR></TD>
			<TD ALIGN=LEFT><BR></TD>
			<TD ALIGN=LEFT><BR></TD>
			<TD ALIGN=LEFT><BR></TD>
		</TR>
		<TR>
			<TD HEIGHT=16 ALIGN=LEFT><BR></TD>
			<TD ALIGN=LEFT><BR></TD>
			<TD ALIGN=LEFT><BR></TD>
			<TD ALIGN=LEFT><BR></TD>
		</TR>




		<TR>
			<TD COLSPAN=4 HEIGHT=16 ALIGN=LEFT><I>saranno ammesse agli Ottavi (in ordine libero):</I></TD>
		</TR>
		<TR>
			<TD HEIGHT=21 ALIGN=LEFT BGCOLOR="#CCFFCC">

<!-- W1 -->
<select name="question_32" class="pronostico" onchange="aggiornaPronostici(5,0)">
	<option selected>&nbsp;</option>
	<optgroup label="Girone A">
		<option>Messico</option>
		<option>Corea del Sud</option>
		<option>Sudafrica</option>
		<option>Vincitore_D</option>
	</optgroup>
	<optgroup label="Girone B">
		<option>Canada</option>
		<option>Svizzera</option>
		<option>Qatar</option>
		<option>Vincitore_A</option>
	</optgroup>
	<optgroup label="Girone C">
		<option>Brasile</option>
		<option>Marocco</option>
		<option>Scozia</option>
		<option>Haiti</option>
	</optgroup>
	<optgroup label="Girone D">
		<option>USA</option>
		<option>Australia</option>
		<option>Paraguay</option>
		<option>Vincitore_C</option>
	</optgroup>
	<optgroup label="Girone E">
		<option>Germania</option>
		<option>Ecuador</option>
		<option>Costa d'Avorio</option>
		<option>Curaçao</option>
	</optgroup>
	<optgroup label="Girone F">
		<option>Olanda</option>
		<option>Giappone</option>
		<option>Tunisia</option>
		<option>Vincitore_B</option>
	</optgroup>
	<optgroup label="Girone G">
		<option>Belgio</option>
		<option>Iran</option>
		<option>Egitto</option>
		<option>Nuova Zelanda</option>
	</optgroup>
	<optgroup label="Girone H">
		<option>Spagna</option>
		<option>Uruguay</option>
		<option>Arabia Saudita</option>
		<option>Capo Verde</option>
	</optgroup>
	<optgroup label="Girone I">
		<option>Francia</option>
		<option>Senegal</option>
		<option>Norvegia</option>
		<option>Vincitore_2</option>
	</optgroup>
	<optgroup label="Girone J">
		<option>Argentina</option>
		<option>Austria</option>
		<option>Algeria</option>
		<option>Giordania</option>
	</optgroup>
	<optgroup label="Girone K">
		<option>Portogallo</option>
		<option>Colombia</option>
		<option>Uzbekistan</option>
		<option>Vincitore_1</option>
	</optgroup>
	<optgroup label="Girone L">
		<option>Inghilterra</option>
		<option>Croazia</option>
		<option>Panama</option>
		<option>Ghana</option>
	</optgroup>
</select>

</TD>
			<TD ALIGN=LEFT BGCOLOR="#CCFFCC">

<!-- W2 -->
<select name="question_33" class="pronostico" onchange="aggiornaPronostici(5,0)">
	<option selected>&nbsp;</option>
	<optgroup label="Girone A">
		<option>Messico</option>
		<option>Corea del Sud</option>
		<option>Sudafrica</option>
		<option>Vincitore_D</option>
	</optgroup>
	<optgroup label="Girone B">
		<option>Canada</option>
		<option>Svizzera</option>
		<option>Qatar</option>
		<option>Vincitore_A</option>
	</optgroup>
	<optgroup label="Girone C">
		<option>Brasile</option>
		<option>Marocco</option>
		<option>Scozia</option>
		<option>Haiti</option>
	</optgroup>
	<optgroup label="Girone D">
		<option>USA</option>
		<option>Australia</option>
		<option>Paraguay</option>
		<option>Vincitore_C</option>
	</optgroup>
	<optgroup label="Girone E">
		<option>Germania</option>
		<option>Ecuador</option>
		<option>Costa d'Avorio</option>
		<option>Curaçao</option>
	</optgroup>
	<optgroup label="Girone F">
		<option>Olanda</option>
		<option>Giappone</option>
		<option>Tunisia</option>
		<option>Vincitore_B</option>
	</optgroup>
	<optgroup label="Girone G">
		<option>Belgio</option>
		<option>Iran</option>
		<option>Egitto</option>
		<option>Nuova Zelanda</option>
	</optgroup>
	<optgroup label="Girone H">
		<option>Spagna</option>
		<option>Uruguay</option>
		<option>Arabia Saudita</option>
		<option>Capo Verde</option>
	</optgroup>
	<optgroup label="Girone I">
		<option>Francia</option>
		<option>Senegal</option>
		<option>Norvegia</option>
		<option>Vincitore_2</option>
	</optgroup>
	<optgroup label="Girone J">
		<option>Argentina</option>
		<option>Austria</option>
		<option>Algeria</option>
		<option>Giordania</option>
	</optgroup>
	<optgroup label="Girone K">
		<option>Portogallo</option>
		<option>Colombia</option>
		<option>Uzbekistan</option>
		<option>Vincitore_1</option>
	</optgroup>
	<optgroup label="Girone L">
		<option>Inghilterra</option>
		<option>Croazia</option>
		<option>Panama</option>
		<option>Ghana</option>
	</optgroup>
</select>


</TD>
			<TD ALIGN=LEFT BGCOLOR="#CCFFCC">

<!-- W3 -->
<select name="question_34" class="pronostico" onchange="aggiornaPronostici(5,0)">
	<option selected>&nbsp;</option>
	<optgroup label="Girone A">
		<option>Messico</option>
		<option>Corea del Sud</option>
		<option>Sudafrica</option>
		<option>Vincitore_D</option>
	</optgroup>
	<optgroup label="Girone B">
		<option>Canada</option>
		<option>Svizzera</option>
		<option>Qatar</option>
		<option>Vincitore_A</option>
	</optgroup>
	<optgroup label="Girone C">
		<option>Brasile</option>
		<option>Marocco</option>
		<option>Scozia</option>
		<option>Haiti</option>
	</optgroup>
	<optgroup label="Girone D">
		<option>USA</option>
		<option>Australia</option>
		<option>Paraguay</option>
		<option>Vincitore_C</option>
	</optgroup>
	<optgroup label="Girone E">
		<option>Germania</option>
		<option>Ecuador</option>
		<option>Costa d'Avorio</option>
		<option>Curaçao</option>
	</optgroup>
	<optgroup label="Girone F">
		<option>Olanda</option>
		<option>Giappone</option>
		<option>Tunisia</option>
		<option>Vincitore_B</option>
	</optgroup>
	<optgroup label="Girone G">
		<option>Belgio</option>
		<option>Iran</option>
		<option>Egitto</option>
		<option>Nuova Zelanda</option>
	</optgroup>
	<optgroup label="Girone H">
		<option>Spagna</option>
		<option>Uruguay</option>
		<option>Arabia Saudita</option>
		<option>Capo Verde</option>
	</optgroup>
	<optgroup label="Girone I">
		<option>Francia</option>
		<option>Senegal</option>
		<option>Norvegia</option>
		<option>Vincitore_2</option>
	</optgroup>
	<optgroup label="Girone J">
		<option>Argentina</option>
		<option>Austria</option>
		<option>Algeria</option>
		<option>Giordania</option>
	</optgroup>
	<optgroup label="Girone K">
		<option>Portogallo</option>
		<option>Colombia</option>
		<option>Uzbekistan</option>
		<option>Vincitore_1</option>
	</optgroup>
	<optgroup label="Girone L">
		<option>Inghilterra</option>
		<option>Croazia</option>
		<option>Panama</option>
		<option>Ghana</option>
	</optgroup>
</select>


</TD>
			<TD ALIGN=LEFT BGCOLOR="#CCFFCC">


<!-- W4 -->
<select name="question_35" class="pronostico" onchange="aggiornaPronostici(5,0)">
	<option selected>&nbsp;</option>
	<optgroup label="Girone A">
		<option>Messico</option>
		<option>Corea del Sud</option>
		<option>Sudafrica</option>
		<option>Vincitore_D</option>
	</optgroup>
	<optgroup label="Girone B">
		<option>Canada</option>
		<option>Svizzera</option>
		<option>Qatar</option>
		<option>Vincitore_A</option>
	</optgroup>
	<optgroup label="Girone C">
		<option>Brasile</option>
		<option>Marocco</option>
		<option>Scozia</option>
		<option>Haiti</option>
	</optgroup>
	<optgroup label="Girone D">
		<option>USA</option>
		<option>Australia</option>
		<option>Paraguay</option>
		<option>Vincitore_C</option>
	</optgroup>
	<optgroup label="Girone E">
		<option>Germania</option>
		<option>Ecuador</option>
		<option>Costa d'Avorio</option>
		<option>Curaçao</option>
	</optgroup>
	<optgroup label="Girone F">
		<option>Olanda</option>
		<option>Giappone</option>
		<option>Tunisia</option>
		<option>Vincitore_B</option>
	</optgroup>
	<optgroup label="Girone G">
		<option>Belgio</option>
		<option>Iran</option>
		<option>Egitto</option>
		<option>Nuova Zelanda</option>
	</optgroup>
	<optgroup label="Girone H">
		<option>Spagna</option>
		<option>Uruguay</option>
		<option>Arabia Saudita</option>
		<option>Capo Verde</option>
	</optgroup>
	<optgroup label="Girone I">
		<option>Francia</option>
		<option>Senegal</option>
		<option>Norvegia</option>
		<option>Vincitore_2</option>
	</optgroup>
	<optgroup label="Girone J">
		<option>Argentina</option>
		<option>Austria</option>
		<option>Algeria</option>
		<option>Giordania</option>
	</optgroup>
	<optgroup label="Girone K">
		<option>Portogallo</option>
		<option>Colombia</option>
		<option>Uzbekistan</option>
		<option>Vincitore_1</option>
	</optgroup>
	<optgroup label="Girone L">
		<option>Inghilterra</option>
		<option>Croazia</option>
		<option>Panama</option>
		<option>Ghana</option>
	</optgroup>
</select>


</TD>
		</TR>
		<TR>
			<TD HEIGHT=21 ALIGN=LEFT BGCOLOR="#CCFFCC">

<!-- W5 -->
<select name="question_36" class="pronostico" onchange="aggiornaPronostici(5,0)">
	<option selected>&nbsp;</option>
	<optgroup label="Girone A">
		<option>Messico</option>
		<option>Corea del Sud</option>
		<option>Sudafrica</option>
		<option>Vincitore_D</option>
	</optgroup>
	<optgroup label="Girone B">
		<option>Canada</option>
		<option>Svizzera</option>
		<option>Qatar</option>
		<option>Vincitore_A</option>
	</optgroup>
	<optgroup label="Girone C">
		<option>Brasile</option>
		<option>Marocco</option>
		<option>Scozia</option>
		<option>Haiti</option>
	</optgroup>
	<optgroup label="Girone D">
		<option>USA</option>
		<option>Australia</option>
		<option>Paraguay</option>
		<option>Vincitore_C</option>
	</optgroup>
	<optgroup label="Girone E">
		<option>Germania</option>
		<option>Ecuador</option>
		<option>Costa d'Avorio</option>
		<option>Curaçao</option>
	</optgroup>
	<optgroup label="Girone F">
		<option>Olanda</option>
		<option>Giappone</option>
		<option>Tunisia</option>
		<option>Vincitore_B</option>
	</optgroup>
	<optgroup label="Girone G">
		<option>Belgio</option>
		<option>Iran</option>
		<option>Egitto</option>
		<option>Nuova Zelanda</option>
	</optgroup>
	<optgroup label="Girone H">
		<option>Spagna</option>
		<option>Uruguay</option>
		<option>Arabia Saudita</option>
		<option>Capo Verde</option>
	</optgroup>
	<optgroup label="Girone I">
		<option>Francia</option>
		<option>Senegal</option>
		<option>Norvegia</option>
		<option>Vincitore_2</option>
	</optgroup>
	<optgroup label="Girone J">
		<option>Argentina</option>
		<option>Austria</option>
		<option>Algeria</option>
		<option>Giordania</option>
	</optgroup>
	<optgroup label="Girone K">
		<option>Portogallo</option>
		<option>Colombia</option>
		<option>Uzbekistan</option>
		<option>Vincitore_1</option>
	</optgroup>
	<optgroup label="Girone L">
		<option>Inghilterra</option>
		<option>Croazia</option>
		<option>Panama</option>
		<option>Ghana</option>
	</optgroup>
</select>


</TD>
			<TD ALIGN=LEFT BGCOLOR="#CCFFCC">

<!-- W6 -->
<select name="question_37" class="pronostico" onchange="aggiornaPronostici(5,0)">
	<option selected>&nbsp;</option>
	<optgroup label="Girone A">
		<option>Messico</option>
		<option>Corea del Sud</option>
		<option>Sudafrica</option>
		<option>Vincitore_D</option>
	</optgroup>
	<optgroup label="Girone B">
		<option>Canada</option>
		<option>Svizzera</option>
		<option>Qatar</option>
		<option>Vincitore_A</option>
	</optgroup>
	<optgroup label="Girone C">
		<option>Brasile</option>
		<option>Marocco</option>
		<option>Scozia</option>
		<option>Haiti</option>
	</optgroup>
	<optgroup label="Girone D">
		<option>USA</option>
		<option>Australia</option>
		<option>Paraguay</option>
		<option>Vincitore_C</option>
	</optgroup>
	<optgroup label="Girone E">
		<option>Germania</option>
		<option>Ecuador</option>
		<option>Costa d'Avorio</option>
		<option>Curaçao</option>
	</optgroup>
	<optgroup label="Girone F">
		<option>Olanda</option>
		<option>Giappone</option>
		<option>Tunisia</option>
		<option>Vincitore_B</option>
	</optgroup>
	<optgroup label="Girone G">
		<option>Belgio</option>
		<option>Iran</option>
		<option>Egitto</option>
		<option>Nuova Zelanda</option>
	</optgroup>
	<optgroup label="Girone H">
		<option>Spagna</option>
		<option>Uruguay</option>
		<option>Arabia Saudita</option>
		<option>Capo Verde</option>
	</optgroup>
	<optgroup label="Girone I">
		<option>Francia</option>
		<option>Senegal</option>
		<option>Norvegia</option>
		<option>Vincitore_2</option>
	</optgroup>
	<optgroup label="Girone J">
		<option>Argentina</option>
		<option>Austria</option>
		<option>Algeria</option>
		<option>Giordania</option>
	</optgroup>
	<optgroup label="Girone K">
		<option>Portogallo</option>
		<option>Colombia</option>
		<option>Uzbekistan</option>
		<option>Vincitore_1</option>
	</optgroup>
	<optgroup label="Girone L">
		<option>Inghilterra</option>
		<option>Croazia</option>
		<option>Panama</option>
		<option>Ghana</option>
	</optgroup>
</select>



</TD>
			<TD ALIGN=LEFT BGCOLOR="#CCFFCC">

<!-- W7 -->
<select name="question_38" class="pronostico" onchange="aggiornaPronostici(5,0)">
	<option selected>&nbsp;</option>
	<optgroup label="Girone A">
		<option>Messico</option>
		<option>Corea del Sud</option>
		<option>Sudafrica</option>
		<option>Vincitore_D</option>
	</optgroup>
	<optgroup label="Girone B">
		<option>Canada</option>
		<option>Svizzera</option>
		<option>Qatar</option>
		<option>Vincitore_A</option>
	</optgroup>
	<optgroup label="Girone C">
		<option>Brasile</option>
		<option>Marocco</option>
		<option>Scozia</option>
		<option>Haiti</option>
	</optgroup>
	<optgroup label="Girone D">
		<option>USA</option>
		<option>Australia</option>
		<option>Paraguay</option>
		<option>Vincitore_C</option>
	</optgroup>
	<optgroup label="Girone E">
		<option>Germania</option>
		<option>Ecuador</option>
		<option>Costa d'Avorio</option>
		<option>Curaçao</option>
	</optgroup>
	<optgroup label="Girone F">
		<option>Olanda</option>
		<option>Giappone</option>
		<option>Tunisia</option>
		<option>Vincitore_B</option>
	</optgroup>
	<optgroup label="Girone G">
		<option>Belgio</option>
		<option>Iran</option>
		<option>Egitto</option>
		<option>Nuova Zelanda</option>
	</optgroup>
	<optgroup label="Girone H">
		<option>Spagna</option>
		<option>Uruguay</option>
		<option>Arabia Saudita</option>
		<option>Capo Verde</option>
	</optgroup>
	<optgroup label="Girone I">
		<option>Francia</option>
		<option>Senegal</option>
		<option>Norvegia</option>
		<option>Vincitore_2</option>
	</optgroup>
	<optgroup label="Girone J">
		<option>Argentina</option>
		<option>Austria</option>
		<option>Algeria</option>
		<option>Giordania</option>
	</optgroup>
	<optgroup label="Girone K">
		<option>Portogallo</option>
		<option>Colombia</option>
		<option>Uzbekistan</option>
		<option>Vincitore_1</option>
	</optgroup>
	<optgroup label="Girone L">
		<option>Inghilterra</option>
		<option>Croazia</option>
		<option>Panama</option>
		<option>Ghana</option>
	</optgroup>
</select>





</TD>
			<TD ALIGN=LEFT BGCOLOR="#CCFFCC">

<!-- W8 -->
<select name="question_39" class="pronostico" onchange="aggiornaPronostici(5,0)">
	<option selected>&nbsp;</option>
	<optgroup label="Girone A">
		<option>Messico</option>
		<option>Corea del Sud</option>
		<option>Sudafrica</option>
		<option>Vincitore_D</option>
	</optgroup>
	<optgroup label="Girone B">
		<option>Canada</option>
		<option>Svizzera</option>
		<option>Qatar</option>
		<option>Vincitore_A</option>
	</optgroup>
	<optgroup label="Girone C">
		<option>Brasile</option>
		<option>Marocco</option>
		<option>Scozia</option>
		<option>Haiti</option>
	</optgroup>
	<optgroup label="Girone D">
		<option>USA</option>
		<option>Australia</option>
		<option>Paraguay</option>
		<option>Vincitore_C</option>
	</optgroup>
	<optgroup label="Girone E">
		<option>Germania</option>
		<option>Ecuador</option>
		<option>Costa d'Avorio</option>
		<option>Curaçao</option>
	</optgroup>
	<optgroup label="Girone F">
		<option>Olanda</option>
		<option>Giappone</option>
		<option>Tunisia</option>
		<option>Vincitore_B</option>
	</optgroup>
	<optgroup label="Girone G">
		<option>Belgio</option>
		<option>Iran</option>
		<option>Egitto</option>
		<option>Nuova Zelanda</option>
	</optgroup>
	<optgroup label="Girone H">
		<option>Spagna</option>
		<option>Uruguay</option>
		<option>Arabia Saudita</option>
		<option>Capo Verde</option>
	</optgroup>
	<optgroup label="Girone I">
		<option>Francia</option>
		<option>Senegal</option>
		<option>Norvegia</option>
		<option>Vincitore_2</option>
	</optgroup>
	<optgroup label="Girone J">
		<option>Argentina</option>
		<option>Austria</option>
		<option>Algeria</option>
		<option>Giordania</option>
	</optgroup>
	<optgroup label="Girone K">
		<option>Portogallo</option>
		<option>Colombia</option>
		<option>Uzbekistan</option>
		<option>Vincitore_1</option>
	</optgroup>
	<optgroup label="Girone L">
		<option>Inghilterra</option>
		<option>Croazia</option>
		<option>Panama</option>
		<option>Ghana</option>
	</optgroup>
</select>



</TD>
		</TR>


		<TR>
			<TD HEIGHT=21 ALIGN=LEFT BGCOLOR="#CCFFCC">

<!-- W9 -->
<select name="question_40" class="pronostico" onchange="aggiornaPronostici(5,0)">
	<option selected>&nbsp;</option>
	<optgroup label="Girone A">
		<option>Messico</option>
		<option>Corea del Sud</option>
		<option>Sudafrica</option>
		<option>Vincitore_D</option>
	</optgroup>
	<optgroup label="Girone B">
		<option>Canada</option>
		<option>Svizzera</option>
		<option>Qatar</option>
		<option>Vincitore_A</option>
	</optgroup>
	<optgroup label="Girone C">
		<option>Brasile</option>
		<option>Marocco</option>
		<option>Scozia</option>
		<option>Haiti</option>
	</optgroup>
	<optgroup label="Girone D">
		<option>USA</option>
		<option>Australia</option>
		<option>Paraguay</option>
		<option>Vincitore_C</option>
	</optgroup>
	<optgroup label="Girone E">
		<option>Germania</option>
		<option>Ecuador</option>
		<option>Costa d'Avorio</option>
		<option>Curaçao</option>
	</optgroup>
	<optgroup label="Girone F">
		<option>Olanda</option>
		<option>Giappone</option>
		<option>Tunisia</option>
		<option>Vincitore_B</option>
	</optgroup>
	<optgroup label="Girone G">
		<option>Belgio</option>
		<option>Iran</option>
		<option>Egitto</option>
		<option>Nuova Zelanda</option>
	</optgroup>
	<optgroup label="Girone H">
		<option>Spagna</option>
		<option>Uruguay</option>
		<option>Arabia Saudita</option>
		<option>Capo Verde</option>
	</optgroup>
	<optgroup label="Girone I">
		<option>Francia</option>
		<option>Senegal</option>
		<option>Norvegia</option>
		<option>Vincitore_2</option>
	</optgroup>
	<optgroup label="Girone J">
		<option>Argentina</option>
		<option>Austria</option>
		<option>Algeria</option>
		<option>Giordania</option>
	</optgroup>
	<optgroup label="Girone K">
		<option>Portogallo</option>
		<option>Colombia</option>
		<option>Uzbekistan</option>
		<option>Vincitore_1</option>
	</optgroup>
	<optgroup label="Girone L">
		<option>Inghilterra</option>
		<option>Croazia</option>
		<option>Panama</option>
		<option>Ghana</option>
	</optgroup>
</select>



</TD>
			<TD ALIGN=LEFT BGCOLOR="#CCFFCC">

<!-- W10 -->
<select name="question_41" class="pronostico" onchange="aggiornaPronostici(5,0)">
	<option selected>&nbsp;</option>
	<optgroup label="Girone A">
		<option>Messico</option>
		<option>Corea del Sud</option>
		<option>Sudafrica</option>
		<option>Vincitore_D</option>
	</optgroup>
	<optgroup label="Girone B">
		<option>Canada</option>
		<option>Svizzera</option>
		<option>Qatar</option>
		<option>Vincitore_A</option>
	</optgroup>
	<optgroup label="Girone C">
		<option>Brasile</option>
		<option>Marocco</option>
		<option>Scozia</option>
		<option>Haiti</option>
	</optgroup>
	<optgroup label="Girone D">
		<option>USA</option>
		<option>Australia</option>
		<option>Paraguay</option>
		<option>Vincitore_C</option>
	</optgroup>
	<optgroup label="Girone E">
		<option>Germania</option>
		<option>Ecuador</option>
		<option>Costa d'Avorio</option>
		<option>Curaçao</option>
	</optgroup>
	<optgroup label="Girone F">
		<option>Olanda</option>
		<option>Giappone</option>
		<option>Tunisia</option>
		<option>Vincitore_B</option>
	</optgroup>
	<optgroup label="Girone G">
		<option>Belgio</option>
		<option>Iran</option>
		<option>Egitto</option>
		<option>Nuova Zelanda</option>
	</optgroup>
	<optgroup label="Girone H">
		<option>Spagna</option>
		<option>Uruguay</option>
		<option>Arabia Saudita</option>
		<option>Capo Verde</option>
	</optgroup>
	<optgroup label="Girone I">
		<option>Francia</option>
		<option>Senegal</option>
		<option>Norvegia</option>
		<option>Vincitore_2</option>
	</optgroup>
	<optgroup label="Girone J">
		<option>Argentina</option>
		<option>Austria</option>
		<option>Algeria</option>
		<option>Giordania</option>
	</optgroup>
	<optgroup label="Girone K">
		<option>Portogallo</option>
		<option>Colombia</option>
		<option>Uzbekistan</option>
		<option>Vincitore_1</option>
	</optgroup>
	<optgroup label="Girone L">
		<option>Inghilterra</option>
		<option>Croazia</option>
		<option>Panama</option>
		<option>Ghana</option>
	</optgroup>
</select>


</TD>
			<TD ALIGN=LEFT BGCOLOR="#CCFFCC">

<!-- W11 -->
<select name="question_42" class="pronostico" onchange="aggiornaPronostici(5,0)">
	<option selected>&nbsp;</option>
	<optgroup label="Girone A">
		<option>Messico</option>
		<option>Corea del Sud</option>
		<option>Sudafrica</option>
		<option>Vincitore_D</option>
	</optgroup>
	<optgroup label="Girone B">
		<option>Canada</option>
		<option>Svizzera</option>
		<option>Qatar</option>
		<option>Vincitore_A</option>
	</optgroup>
	<optgroup label="Girone C">
		<option>Brasile</option>
		<option>Marocco</option>
		<option>Scozia</option>
		<option>Haiti</option>
	</optgroup>
	<optgroup label="Girone D">
		<option>USA</option>
		<option>Australia</option>
		<option>Paraguay</option>
		<option>Vincitore_C</option>
	</optgroup>
	<optgroup label="Girone E">
		<option>Germania</option>
		<option>Ecuador</option>
		<option>Costa d'Avorio</option>
		<option>Curaçao</option>
	</optgroup>
	<optgroup label="Girone F">
		<option>Olanda</option>
		<option>Giappone</option>
		<option>Tunisia</option>
		<option>Vincitore_B</option>
	</optgroup>
	<optgroup label="Girone G">
		<option>Belgio</option>
		<option>Iran</option>
		<option>Egitto</option>
		<option>Nuova Zelanda</option>
	</optgroup>
	<optgroup label="Girone H">
		<option>Spagna</option>
		<option>Uruguay</option>
		<option>Arabia Saudita</option>
		<option>Capo Verde</option>
	</optgroup>
	<optgroup label="Girone I">
		<option>Francia</option>
		<option>Senegal</option>
		<option>Norvegia</option>
		<option>Vincitore_2</option>
	</optgroup>
	<optgroup label="Girone J">
		<option>Argentina</option>
		<option>Austria</option>
		<option>Algeria</option>
		<option>Giordania</option>
	</optgroup>
	<optgroup label="Girone K">
		<option>Portogallo</option>
		<option>Colombia</option>
		<option>Uzbekistan</option>
		<option>Vincitore_1</option>
	</optgroup>
	<optgroup label="Girone L">
		<option>Inghilterra</option>
		<option>Croazia</option>
		<option>Panama</option>
		<option>Ghana</option>
	</optgroup>
</select>


</TD>
			<TD ALIGN=LEFT BGCOLOR="#CCFFCC">


<!-- W12 -->
<select name="question_43" class="pronostico" onchange="aggiornaPronostici(5,0)">
	<option selected>&nbsp;</option>
	<optgroup label="Girone A">
		<option>Messico</option>
		<option>Corea del Sud</option>
		<option>Sudafrica</option>
		<option>Vincitore_D</option>
	</optgroup>
	<optgroup label="Girone B">
		<option>Canada</option>
		<option>Svizzera</option>
		<option>Qatar</option>
		<option>Vincitore_A</option>
	</optgroup>
	<optgroup label="Girone C">
		<option>Brasile</option>
		<option>Marocco</option>
		<option>Scozia</option>
		<option>Haiti</option>
	</optgroup>
	<optgroup label="Girone D">
		<option>USA</option>
		<option>Australia</option>
		<option>Paraguay</option>
		<option>Vincitore_C</option>
	</optgroup>
	<optgroup label="Girone E">
		<option>Germania</option>
		<option>Ecuador</option>
		<option>Costa d'Avorio</option>
		<option>Curaçao</option>
	</optgroup>
	<optgroup label="Girone F">
		<option>Olanda</option>
		<option>Giappone</option>
		<option>Tunisia</option>
		<option>Vincitore_B</option>
	</optgroup>
	<optgroup label="Girone G">
		<option>Belgio</option>
		<option>Iran</option>
		<option>Egitto</option>
		<option>Nuova Zelanda</option>
	</optgroup>
	<optgroup label="Girone H">
		<option>Spagna</option>
		<option>Uruguay</option>
		<option>Arabia Saudita</option>
		<option>Capo Verde</option>
	</optgroup>
	<optgroup label="Girone I">
		<option>Francia</option>
		<option>Senegal</option>
		<option>Norvegia</option>
		<option>Vincitore_2</option>
	</optgroup>
	<optgroup label="Girone J">
		<option>Argentina</option>
		<option>Austria</option>
		<option>Algeria</option>
		<option>Giordania</option>
	</optgroup>
	<optgroup label="Girone K">
		<option>Portogallo</option>
		<option>Colombia</option>
		<option>Uzbekistan</option>
		<option>Vincitore_1</option>
	</optgroup>
	<optgroup label="Girone L">
		<option>Inghilterra</option>
		<option>Croazia</option>
		<option>Panama</option>
		<option>Ghana</option>
	</optgroup>
</select>


</TD>
		</TR>
		<TR>
			<TD HEIGHT=21 ALIGN=LEFT BGCOLOR="#CCFFCC">

<!-- W13 -->
<select name="question_44" class="pronostico" onchange="aggiornaPronostici(5,0)">
	<option selected>&nbsp;</option>
	<optgroup label="Girone A">
		<option>Messico</option>
		<option>Corea del Sud</option>
		<option>Sudafrica</option>
		<option>Vincitore_D</option>
	</optgroup>
	<optgroup label="Girone B">
		<option>Canada</option>
		<option>Svizzera</option>
		<option>Qatar</option>
		<option>Vincitore_A</option>
	</optgroup>
	<optgroup label="Girone C">
		<option>Brasile</option>
		<option>Marocco</option>
		<option>Scozia</option>
		<option>Haiti</option>
	</optgroup>
	<optgroup label="Girone D">
		<option>USA</option>
		<option>Australia</option>
		<option>Paraguay</option>
		<option>Vincitore_C</option>
	</optgroup>
	<optgroup label="Girone E">
		<option>Germania</option>
		<option>Ecuador</option>
		<option>Costa d'Avorio</option>
		<option>Curaçao</option>
	</optgroup>
	<optgroup label="Girone F">
		<option>Olanda</option>
		<option>Giappone</option>
		<option>Tunisia</option>
		<option>Vincitore_B</option>
	</optgroup>
	<optgroup label="Girone G">
		<option>Belgio</option>
		<option>Iran</option>
		<option>Egitto</option>
		<option>Nuova Zelanda</option>
	</optgroup>
	<optgroup label="Girone H">
		<option>Spagna</option>
		<option>Uruguay</option>
		<option>Arabia Saudita</option>
		<option>Capo Verde</option>
	</optgroup>
	<optgroup label="Girone I">
		<option>Francia</option>
		<option>Senegal</option>
		<option>Norvegia</option>
		<option>Vincitore_2</option>
	</optgroup>
	<optgroup label="Girone J">
		<option>Argentina</option>
		<option>Austria</option>
		<option>Algeria</option>
		<option>Giordania</option>
	</optgroup>
	<optgroup label="Girone K">
		<option>Portogallo</option>
		<option>Colombia</option>
		<option>Uzbekistan</option>
		<option>Vincitore_1</option>
	</optgroup>
	<optgroup label="Girone L">
		<option>Inghilterra</option>
		<option>Croazia</option>
		<option>Panama</option>
		<option>Ghana</option>
	</optgroup>
</select>


</TD>
			<TD ALIGN=LEFT BGCOLOR="#CCFFCC">

<!-- W14 -->
<select name="question_45" class="pronostico" onchange="aggiornaPronostici(5,0)">
	<option selected>&nbsp;</option>
	<optgroup label="Girone A">
		<option>Messico</option>
		<option>Corea del Sud</option>
		<option>Sudafrica</option>
		<option>Vincitore_D</option>
	</optgroup>
	<optgroup label="Girone B">
		<option>Canada</option>
		<option>Svizzera</option>
		<option>Qatar</option>
		<option>Vincitore_A</option>
	</optgroup>
	<optgroup label="Girone C">
		<option>Brasile</option>
		<option>Marocco</option>
		<option>Scozia</option>
		<option>Haiti</option>
	</optgroup>
	<optgroup label="Girone D">
		<option>USA</option>
		<option>Australia</option>
		<option>Paraguay</option>
		<option>Vincitore_C</option>
	</optgroup>
	<optgroup label="Girone E">
		<option>Germania</option>
		<option>Ecuador</option>
		<option>Costa d'Avorio</option>
		<option>Curaçao</option>
	</optgroup>
	<optgroup label="Girone F">
		<option>Olanda</option>
		<option>Giappone</option>
		<option>Tunisia</option>
		<option>Vincitore_B</option>
	</optgroup>
	<optgroup label="Girone G">
		<option>Belgio</option>
		<option>Iran</option>
		<option>Egitto</option>
		<option>Nuova Zelanda</option>
	</optgroup>
	<optgroup label="Girone H">
		<option>Spagna</option>
		<option>Uruguay</option>
		<option>Arabia Saudita</option>
		<option>Capo Verde</option>
	</optgroup>
	<optgroup label="Girone I">
		<option>Francia</option>
		<option>Senegal</option>
		<option>Norvegia</option>
		<option>Vincitore_2</option>
	</optgroup>
	<optgroup label="Girone J">
		<option>Argentina</option>
		<option>Austria</option>
		<option>Algeria</option>
		<option>Giordania</option>
	</optgroup>
	<optgroup label="Girone K">
		<option>Portogallo</option>
		<option>Colombia</option>
		<option>Uzbekistan</option>
		<option>Vincitore_1</option>
	</optgroup>
	<optgroup label="Girone L">
		<option>Inghilterra</option>
		<option>Croazia</option>
		<option>Panama</option>
		<option>Ghana</option>
	</optgroup>
</select>



</TD>
			<TD ALIGN=LEFT BGCOLOR="#CCFFCC">

<!-- W15 -->
<select name="question_46" class="pronostico" onchange="aggiornaPronostici(5,0)">
	<option selected>&nbsp;</option>
	<optgroup label="Girone A">
		<option>Messico</option>
		<option>Corea del Sud</option>
		<option>Sudafrica</option>
		<option>Vincitore_D</option>
	</optgroup>
	<optgroup label="Girone B">
		<option>Canada</option>
		<option>Svizzera</option>
		<option>Qatar</option>
		<option>Vincitore_A</option>
	</optgroup>
	<optgroup label="Girone C">
		<option>Brasile</option>
		<option>Marocco</option>
		<option>Scozia</option>
		<option>Haiti</option>
	</optgroup>
	<optgroup label="Girone D">
		<option>USA</option>
		<option>Australia</option>
		<option>Paraguay</option>
		<option>Vincitore_C</option>
	</optgroup>
	<optgroup label="Girone E">
		<option>Germania</option>
		<option>Ecuador</option>
		<option>Costa d'Avorio</option>
		<option>Curaçao</option>
	</optgroup>
	<optgroup label="Girone F">
		<option>Olanda</option>
		<option>Giappone</option>
		<option>Tunisia</option>
		<option>Vincitore_B</option>
	</optgroup>
	<optgroup label="Girone G">
		<option>Belgio</option>
		<option>Iran</option>
		<option>Egitto</option>
		<option>Nuova Zelanda</option>
	</optgroup>
	<optgroup label="Girone H">
		<option>Spagna</option>
		<option>Uruguay</option>
		<option>Arabia Saudita</option>
		<option>Capo Verde</option>
	</optgroup>
	<optgroup label="Girone I">
		<option>Francia</option>
		<option>Senegal</option>
		<option>Norvegia</option>
		<option>Vincitore_2</option>
	</optgroup>
	<optgroup label="Girone J">
		<option>Argentina</option>
		<option>Austria</option>
		<option>Algeria</option>
		<option>Giordania</option>
	</optgroup>
	<optgroup label="Girone K">
		<option>Portogallo</option>
		<option>Colombia</option>
		<option>Uzbekistan</option>
		<option>Vincitore_1</option>
	</optgroup>
	<optgroup label="Girone L">
		<option>Inghilterra</option>
		<option>Croazia</option>
		<option>Panama</option>
		<option>Ghana</option>
	</optgroup>
</select>





</TD>
			<TD ALIGN=LEFT BGCOLOR="#CCFFCC">

<!-- W16 -->
<select name="question_47" class="pronostico" onchange="aggiornaPronostici(5,0)">
	<option selected>&nbsp;</option>
	<optgroup label="Girone A">
		<option>Messico</option>
		<option>Corea del Sud</option>
		<option>Sudafrica</option>
		<option>Vincitore_D</option>
	</optgroup>
	<optgroup label="Girone B">
		<option>Canada</option>
		<option>Svizzera</option>
		<option>Qatar</option>
		<option>Vincitore_A</option>
	</optgroup>
	<optgroup label="Girone C">
		<option>Brasile</option>
		<option>Marocco</option>
		<option>Scozia</option>
		<option>Haiti</option>
	</optgroup>
	<optgroup label="Girone D">
		<option>USA</option>
		<option>Australia</option>
		<option>Paraguay</option>
		<option>Vincitore_C</option>
	</optgroup>
	<optgroup label="Girone E">
		<option>Germania</option>
		<option>Ecuador</option>
		<option>Costa d'Avorio</option>
		<option>Curaçao</option>
	</optgroup>
	<optgroup label="Girone F">
		<option>Olanda</option>
		<option>Giappone</option>
		<option>Tunisia</option>
		<option>Vincitore_B</option>
	</optgroup>
	<optgroup label="Girone G">
		<option>Belgio</option>
		<option>Iran</option>
		<option>Egitto</option>
		<option>Nuova Zelanda</option>
	</optgroup>
	<optgroup label="Girone H">
		<option>Spagna</option>
		<option>Uruguay</option>
		<option>Arabia Saudita</option>
		<option>Capo Verde</option>
	</optgroup>
	<optgroup label="Girone I">
		<option>Francia</option>
		<option>Senegal</option>
		<option>Norvegia</option>
		<option>Vincitore_2</option>
	</optgroup>
	<optgroup label="Girone J">
		<option>Argentina</option>
		<option>Austria</option>
		<option>Algeria</option>
		<option>Giordania</option>
	</optgroup>
	<optgroup label="Girone K">
		<option>Portogallo</option>
		<option>Colombia</option>
		<option>Uzbekistan</option>
		<option>Vincitore_1</option>
	</optgroup>
	<optgroup label="Girone L">
		<option>Inghilterra</option>
		<option>Croazia</option>
		<option>Panama</option>
		<option>Ghana</option>
	</optgroup>
</select>



</TD>
		</TR>




		<TR>
			<TD HEIGHT=16 ALIGN=LEFT><BR></TD>
			<TD ALIGN=LEFT><BR></TD>
			<TD ALIGN=LEFT><BR></TD>
			<TD ALIGN=LEFT><BR></TD>
		</TR>
		<TR>
			<TD HEIGHT=16 ALIGN=LEFT><BR></TD>
			<TD ALIGN=LEFT><BR></TD>
			<TD ALIGN=LEFT><BR></TD>
			<TD ALIGN=LEFT><BR></TD>
		</TR>


		<TR>
			<TD COLSPAN=4 HEIGHT=16 ALIGN=LEFT><I>saranno ammesse ai Quarti (in ordine libero):</I></TD>
		</TR>
		<TR>
			<TD HEIGHT=21 ALIGN=LEFT BGCOLOR="#CCFFCC">

<!-- Q1 -->
<select name="question_48" class="pronostico" onchange="aggiornaPronostici(4,0)">
	<option selected>&nbsp;</option>
	<optgroup label="Girone A">
		<option>Messico</option>
		<option>Corea del Sud</option>
		<option>Sudafrica</option>
		<option>Vincitore_D</option>
	</optgroup>
	<optgroup label="Girone B">
		<option>Canada</option>
		<option>Svizzera</option>
		<option>Qatar</option>
		<option>Vincitore_A</option>
	</optgroup>
	<optgroup label="Girone C">
		<option>Brasile</option>
		<option>Marocco</option>
		<option>Scozia</option>
		<option>Haiti</option>
	</optgroup>
	<optgroup label="Girone D">
		<option>USA</option>
		<option>Australia</option>
		<option>Paraguay</option>
		<option>Vincitore_C</option>
	</optgroup>
	<optgroup label="Girone E">
		<option>Germania</option>
		<option>Ecuador</option>
		<option>Costa d'Avorio</option>
		<option>Curaçao</option>
	</optgroup>
	<optgroup label="Girone F">
		<option>Olanda</option>
		<option>Giappone</option>
		<option>Tunisia</option>
		<option>Vincitore_B</option>
	</optgroup>
	<optgroup label="Girone G">
		<option>Belgio</option>
		<option>Iran</option>
		<option>Egitto</option>
		<option>Nuova Zelanda</option>
	</optgroup>
	<optgroup label="Girone H">
		<option>Spagna</option>
		<option>Uruguay</option>
		<option>Arabia Saudita</option>
		<option>Capo Verde</option>
	</optgroup>
	<optgroup label="Girone I">
		<option>Francia</option>
		<option>Senegal</option>
		<option>Norvegia</option>
		<option>Vincitore_2</option>
	</optgroup>
	<optgroup label="Girone J">
		<option>Argentina</option>
		<option>Austria</option>
		<option>Algeria</option>
		<option>Giordania</option>
	</optgroup>
	<optgroup label="Girone K">
		<option>Portogallo</option>
		<option>Colombia</option>
		<option>Uzbekistan</option>
		<option>Vincitore_1</option>
	</optgroup>
	<optgroup label="Girone L">
		<option>Inghilterra</option>
		<option>Croazia</option>
		<option>Panama</option>
		<option>Ghana</option>
	</optgroup>
</select>



</TD>
			<TD ALIGN=LEFT BGCOLOR="#CCFFCC">

<!-- Q2 -->
<select name="question_49" class="pronostico" onchange="aggiornaPronostici(4,0)">
	<option selected>&nbsp;</option>
	<optgroup label="Girone A">
		<option>Messico</option>
		<option>Corea del Sud</option>
		<option>Sudafrica</option>
		<option>Vincitore_D</option>
	</optgroup>
	<optgroup label="Girone B">
		<option>Canada</option>
		<option>Svizzera</option>
		<option>Qatar</option>
		<option>Vincitore_A</option>
	</optgroup>
	<optgroup label="Girone C">
		<option>Brasile</option>
		<option>Marocco</option>
		<option>Scozia</option>
		<option>Haiti</option>
	</optgroup>
	<optgroup label="Girone D">
		<option>USA</option>
		<option>Australia</option>
		<option>Paraguay</option>
		<option>Vincitore_C</option>
	</optgroup>
	<optgroup label="Girone E">
		<option>Germania</option>
		<option>Ecuador</option>
		<option>Costa d'Avorio</option>
		<option>Curaçao</option>
	</optgroup>
	<optgroup label="Girone F">
		<option>Olanda</option>
		<option>Giappone</option>
		<option>Tunisia</option>
		<option>Vincitore_B</option>
	</optgroup>
	<optgroup label="Girone G">
		<option>Belgio</option>
		<option>Iran</option>
		<option>Egitto</option>
		<option>Nuova Zelanda</option>
	</optgroup>
	<optgroup label="Girone H">
		<option>Spagna</option>
		<option>Uruguay</option>
		<option>Arabia Saudita</option>
		<option>Capo Verde</option>
	</optgroup>
	<optgroup label="Girone I">
		<option>Francia</option>
		<option>Senegal</option>
		<option>Norvegia</option>
		<option>Vincitore_2</option>
	</optgroup>
	<optgroup label="Girone J">
		<option>Argentina</option>
		<option>Austria</option>
		<option>Algeria</option>
		<option>Giordania</option>
	</optgroup>
	<optgroup label="Girone K">
		<option>Portogallo</option>
		<option>Colombia</option>
		<option>Uzbekistan</option>
		<option>Vincitore_1</option>
	</optgroup>
	<optgroup label="Girone L">
		<option>Inghilterra</option>
		<option>Croazia</option>
		<option>Panama</option>
		<option>Ghana</option>
	</optgroup>
</select>


</TD>
			<TD ALIGN=LEFT BGCOLOR="#CCFFCC">

<!-- Q3 -->
<select name="question_50" class="pronostico" onchange="aggiornaPronostici(4,0)">
	<option selected>&nbsp;</option>
	<optgroup label="Girone A">
		<option>Messico</option>
		<option>Corea del Sud</option>
		<option>Sudafrica</option>
		<option>Vincitore_D</option>
	</optgroup>
	<optgroup label="Girone B">
		<option>Canada</option>
		<option>Svizzera</option>
		<option>Qatar</option>
		<option>Vincitore_A</option>
	</optgroup>
	<optgroup label="Girone C">
		<option>Brasile</option>
		<option>Marocco</option>
		<option>Scozia</option>
		<option>Haiti</option>
	</optgroup>
	<optgroup label="Girone D">
		<option>USA</option>
		<option>Australia</option>
		<option>Paraguay</option>
		<option>Vincitore_C</option>
	</optgroup>
	<optgroup label="Girone E">
		<option>Germania</option>
		<option>Ecuador</option>
		<option>Costa d'Avorio</option>
		<option>Curaçao</option>
	</optgroup>
	<optgroup label="Girone F">
		<option>Olanda</option>
		<option>Giappone</option>
		<option>Tunisia</option>
		<option>Vincitore_B</option>
	</optgroup>
	<optgroup label="Girone G">
		<option>Belgio</option>
		<option>Iran</option>
		<option>Egitto</option>
		<option>Nuova Zelanda</option>
	</optgroup>
	<optgroup label="Girone H">
		<option>Spagna</option>
		<option>Uruguay</option>
		<option>Arabia Saudita</option>
		<option>Capo Verde</option>
	</optgroup>
	<optgroup label="Girone I">
		<option>Francia</option>
		<option>Senegal</option>
		<option>Norvegia</option>
		<option>Vincitore_2</option>
	</optgroup>
	<optgroup label="Girone J">
		<option>Argentina</option>
		<option>Austria</option>
		<option>Algeria</option>
		<option>Giordania</option>
	</optgroup>
	<optgroup label="Girone K">
		<option>Portogallo</option>
		<option>Colombia</option>
		<option>Uzbekistan</option>
		<option>Vincitore_1</option>
	</optgroup>
	<optgroup label="Girone L">
		<option>Inghilterra</option>
		<option>Croazia</option>
		<option>Panama</option>
		<option>Ghana</option>
	</optgroup>
</select>


</TD>
			<TD ALIGN=LEFT BGCOLOR="#CCFFCC">


<!-- Q4 -->
<select name="question_51" class="pronostico" onchange="aggiornaPronostici(4,0)">
	<option selected>&nbsp;</option>
	<optgroup label="Girone A">
		<option>Messico</option>
		<option>Corea del Sud</option>
		<option>Sudafrica</option>
		<option>Vincitore_D</option>
	</optgroup>
	<optgroup label="Girone B">
		<option>Canada</option>
		<option>Svizzera</option>
		<option>Qatar</option>
		<option>Vincitore_A</option>
	</optgroup>
	<optgroup label="Girone C">
		<option>Brasile</option>
		<option>Marocco</option>
		<option>Scozia</option>
		<option>Haiti</option>
	</optgroup>
	<optgroup label="Girone D">
		<option>USA</option>
		<option>Australia</option>
		<option>Paraguay</option>
		<option>Vincitore_C</option>
	</optgroup>
	<optgroup label="Girone E">
		<option>Germania</option>
		<option>Ecuador</option>
		<option>Costa d'Avorio</option>
		<option>Curaçao</option>
	</optgroup>
	<optgroup label="Girone F">
		<option>Olanda</option>
		<option>Giappone</option>
		<option>Tunisia</option>
		<option>Vincitore_B</option>
	</optgroup>
	<optgroup label="Girone G">
		<option>Belgio</option>
		<option>Iran</option>
		<option>Egitto</option>
		<option>Nuova Zelanda</option>
	</optgroup>
	<optgroup label="Girone H">
		<option>Spagna</option>
		<option>Uruguay</option>
		<option>Arabia Saudita</option>
		<option>Capo Verde</option>
	</optgroup>
	<optgroup label="Girone I">
		<option>Francia</option>
		<option>Senegal</option>
		<option>Norvegia</option>
		<option>Vincitore_2</option>
	</optgroup>
	<optgroup label="Girone J">
		<option>Argentina</option>
		<option>Austria</option>
		<option>Algeria</option>
		<option>Giordania</option>
	</optgroup>
	<optgroup label="Girone K">
		<option>Portogallo</option>
		<option>Colombia</option>
		<option>Uzbekistan</option>
		<option>Vincitore_1</option>
	</optgroup>
	<optgroup label="Girone L">
		<option>Inghilterra</option>
		<option>Croazia</option>
		<option>Panama</option>
		<option>Ghana</option>
	</optgroup>
</select>


</TD>
		</TR>
		<TR>
			<TD HEIGHT=21 ALIGN=LEFT BGCOLOR="#CCFFCC">

<!-- Q5 -->
<select name="question_52" class="pronostico" onchange="aggiornaPronostici(4,0)">
	<option selected>&nbsp;</option>
	<optgroup label="Girone A">
		<option>Messico</option>
		<option>Corea del Sud</option>
		<option>Sudafrica</option>
		<option>Vincitore_D</option>
	</optgroup>
	<optgroup label="Girone B">
		<option>Canada</option>
		<option>Svizzera</option>
		<option>Qatar</option>
		<option>Vincitore_A</option>
	</optgroup>
	<optgroup label="Girone C">
		<option>Brasile</option>
		<option>Marocco</option>
		<option>Scozia</option>
		<option>Haiti</option>
	</optgroup>
	<optgroup label="Girone D">
		<option>USA</option>
		<option>Australia</option>
		<option>Paraguay</option>
		<option>Vincitore_C</option>
	</optgroup>
	<optgroup label="Girone E">
		<option>Germania</option>
		<option>Ecuador</option>
		<option>Costa d'Avorio</option>
		<option>Curaçao</option>
	</optgroup>
	<optgroup label="Girone F">
		<option>Olanda</option>
		<option>Giappone</option>
		<option>Tunisia</option>
		<option>Vincitore_B</option>
	</optgroup>
	<optgroup label="Girone G">
		<option>Belgio</option>
		<option>Iran</option>
		<option>Egitto</option>
		<option>Nuova Zelanda</option>
	</optgroup>
	<optgroup label="Girone H">
		<option>Spagna</option>
		<option>Uruguay</option>
		<option>Arabia Saudita</option>
		<option>Capo Verde</option>
	</optgroup>
	<optgroup label="Girone I">
		<option>Francia</option>
		<option>Senegal</option>
		<option>Norvegia</option>
		<option>Vincitore_2</option>
	</optgroup>
	<optgroup label="Girone J">
		<option>Argentina</option>
		<option>Austria</option>
		<option>Algeria</option>
		<option>Giordania</option>
	</optgroup>
	<optgroup label="Girone K">
		<option>Portogallo</option>
		<option>Colombia</option>
		<option>Uzbekistan</option>
		<option>Vincitore_1</option>
	</optgroup>
	<optgroup label="Girone L">
		<option>Inghilterra</option>
		<option>Croazia</option>
		<option>Panama</option>
		<option>Ghana</option>
	</optgroup>
</select>


</TD>
			<TD ALIGN=LEFT BGCOLOR="#CCFFCC">

<!-- Q6 -->
<select name="question_53" class="pronostico" onchange="aggiornaPronostici(4,0)">
	<option selected>&nbsp;</option>
	<optgroup label="Girone A">
		<option>Messico</option>
		<option>Corea del Sud</option>
		<option>Sudafrica</option>
		<option>Vincitore_D</option>
	</optgroup>
	<optgroup label="Girone B">
		<option>Canada</option>
		<option>Svizzera</option>
		<option>Qatar</option>
		<option>Vincitore_A</option>
	</optgroup>
	<optgroup label="Girone C">
		<option>Brasile</option>
		<option>Marocco</option>
		<option>Scozia</option>
		<option>Haiti</option>
	</optgroup>
	<optgroup label="Girone D">
		<option>USA</option>
		<option>Australia</option>
		<option>Paraguay</option>
		<option>Vincitore_C</option>
	</optgroup>
	<optgroup label="Girone E">
		<option>Germania</option>
		<option>Ecuador</option>
		<option>Costa d'Avorio</option>
		<option>Curaçao</option>
	</optgroup>
	<optgroup label="Girone F">
		<option>Olanda</option>
		<option>Giappone</option>
		<option>Tunisia</option>
		<option>Vincitore_B</option>
	</optgroup>
	<optgroup label="Girone G">
		<option>Belgio</option>
		<option>Iran</option>
		<option>Egitto</option>
		<option>Nuova Zelanda</option>
	</optgroup>
	<optgroup label="Girone H">
		<option>Spagna</option>
		<option>Uruguay</option>
		<option>Arabia Saudita</option>
		<option>Capo Verde</option>
	</optgroup>
	<optgroup label="Girone I">
		<option>Francia</option>
		<option>Senegal</option>
		<option>Norvegia</option>
		<option>Vincitore_2</option>
	</optgroup>
	<optgroup label="Girone J">
		<option>Argentina</option>
		<option>Austria</option>
		<option>Algeria</option>
		<option>Giordania</option>
	</optgroup>
	<optgroup label="Girone K">
		<option>Portogallo</option>
		<option>Colombia</option>
		<option>Uzbekistan</option>
		<option>Vincitore_1</option>
	</optgroup>
	<optgroup label="Girone L">
		<option>Inghilterra</option>
		<option>Croazia</option>
		<option>Panama</option>
		<option>Ghana</option>
	</optgroup>
</select>



</TD>
			<TD ALIGN=LEFT BGCOLOR="#CCFFCC">

<!-- Q7 -->
<select name="question_54" class="pronostico" onchange="aggiornaPronostici(4,0)">
	<option selected>&nbsp;</option>
	<optgroup label="Girone A">
		<option>Messico</option>
		<option>Corea del Sud</option>
		<option>Sudafrica</option>
		<option>Vincitore_D</option>
	</optgroup>
	<optgroup label="Girone B">
		<option>Canada</option>
		<option>Svizzera</option>
		<option>Qatar</option>
		<option>Vincitore_A</option>
	</optgroup>
	<optgroup label="Girone C">
		<option>Brasile</option>
		<option>Marocco</option>
		<option>Scozia</option>
		<option>Haiti</option>
	</optgroup>
	<optgroup label="Girone D">
		<option>USA</option>
		<option>Australia</option>
		<option>Paraguay</option>
		<option>Vincitore_C</option>
	</optgroup>
	<optgroup label="Girone E">
		<option>Germania</option>
		<option>Ecuador</option>
		<option>Costa d'Avorio</option>
		<option>Curaçao</option>
	</optgroup>
	<optgroup label="Girone F">
		<option>Olanda</option>
		<option>Giappone</option>
		<option>Tunisia</option>
		<option>Vincitore_B</option>
	</optgroup>
	<optgroup label="Girone G">
		<option>Belgio</option>
		<option>Iran</option>
		<option>Egitto</option>
		<option>Nuova Zelanda</option>
	</optgroup>
	<optgroup label="Girone H">
		<option>Spagna</option>
		<option>Uruguay</option>
		<option>Arabia Saudita</option>
		<option>Capo Verde</option>
	</optgroup>
	<optgroup label="Girone I">
		<option>Francia</option>
		<option>Senegal</option>
		<option>Norvegia</option>
		<option>Vincitore_2</option>
	</optgroup>
	<optgroup label="Girone J">
		<option>Argentina</option>
		<option>Austria</option>
		<option>Algeria</option>
		<option>Giordania</option>
	</optgroup>
	<optgroup label="Girone K">
		<option>Portogallo</option>
		<option>Colombia</option>
		<option>Uzbekistan</option>
		<option>Vincitore_1</option>
	</optgroup>
	<optgroup label="Girone L">
		<option>Inghilterra</option>
		<option>Croazia</option>
		<option>Panama</option>
		<option>Ghana</option>
	</optgroup>
</select>





</TD>
			<TD ALIGN=LEFT BGCOLOR="#CCFFCC">

<!-- Q8 -->
<select name="question_55" class="pronostico" onchange="aggiornaPronostici(4,0)">
	<option selected>&nbsp;</option>
	<optgroup label="Girone A">
		<option>Messico</option>
		<option>Corea del Sud</option>
		<option>Sudafrica</option>
		<option>Vincitore_D</option>
	</optgroup>
	<optgroup label="Girone B">
		<option>Canada</option>
		<option>Svizzera</option>
		<option>Qatar</option>
		<option>Vincitore_A</option>
	</optgroup>
	<optgroup label="Girone C">
		<option>Brasile</option>
		<option>Marocco</option>
		<option>Scozia</option>
		<option>Haiti</option>
	</optgroup>
	<optgroup label="Girone D">
		<option>USA</option>
		<option>Australia</option>
		<option>Paraguay</option>
		<option>Vincitore_C</option>
	</optgroup>
	<optgroup label="Girone E">
		<option>Germania</option>
		<option>Ecuador</option>
		<option>Costa d'Avorio</option>
		<option>Curaçao</option>
	</optgroup>
	<optgroup label="Girone F">
		<option>Olanda</option>
		<option>Giappone</option>
		<option>Tunisia</option>
		<option>Vincitore_B</option>
	</optgroup>
	<optgroup label="Girone G">
		<option>Belgio</option>
		<option>Iran</option>
		<option>Egitto</option>
		<option>Nuova Zelanda</option>
	</optgroup>
	<optgroup label="Girone H">
		<option>Spagna</option>
		<option>Uruguay</option>
		<option>Arabia Saudita</option>
		<option>Capo Verde</option>
	</optgroup>
	<optgroup label="Girone I">
		<option>Francia</option>
		<option>Senegal</option>
		<option>Norvegia</option>
		<option>Vincitore_2</option>
	</optgroup>
	<optgroup label="Girone J">
		<option>Argentina</option>
		<option>Austria</option>
		<option>Algeria</option>
		<option>Giordania</option>
	</optgroup>
	<optgroup label="Girone K">
		<option>Portogallo</option>
		<option>Colombia</option>
		<option>Uzbekistan</option>
		<option>Vincitore_1</option>
	</optgroup>
	<optgroup label="Girone L">
		<option>Inghilterra</option>
		<option>Croazia</option>
		<option>Panama</option>
		<option>Ghana</option>
	</optgroup>
</select>



</TD>
		</TR>




		<TR>
			<TD HEIGHT=16 ALIGN=LEFT><BR></TD>
			<TD ALIGN=LEFT><BR></TD>
			<TD ALIGN=LEFT><BR></TD>
			<TD ALIGN=LEFT><BR></TD>
		</TR>
		<TR>
			<TD HEIGHT=16 ALIGN=LEFT><BR></TD>
			<TD ALIGN=LEFT><BR></TD>
			<TD ALIGN=LEFT><BR></TD>
			<TD ALIGN=LEFT><BR></TD>
		</TR>



		<TR>
			<TD COLSPAN=4 HEIGHT=16 ALIGN=LEFT><I>e quindi le 4 semifinaliste saranno (in ordine libero):</I></TD>
		</TR>
		<TR>
			<TD HEIGHT=21 ALIGN=LEFT BGCOLOR="#FFFF99">

<!-- S1 -->
<select name="question_56" class="pronostico" onchange="aggiornaPronostici(3,0)">
	<option selected>&nbsp;</option>
	<optgroup label="Girone A">
		<option>Messico</option>
		<option>Corea del Sud</option>
		<option>Sudafrica</option>
		<option>Vincitore_D</option>
	</optgroup>
	<optgroup label="Girone B">
		<option>Canada</option>
		<option>Svizzera</option>
		<option>Qatar</option>
		<option>Vincitore_A</option>
	</optgroup>
	<optgroup label="Girone C">
		<option>Brasile</option>
		<option>Marocco</option>
		<option>Scozia</option>
		<option>Haiti</option>
	</optgroup>
	<optgroup label="Girone D">
		<option>USA</option>
		<option>Australia</option>
		<option>Paraguay</option>
		<option>Vincitore_C</option>
	</optgroup>
	<optgroup label="Girone E">
		<option>Germania</option>
		<option>Ecuador</option>
		<option>Costa d'Avorio</option>
		<option>Curaçao</option>
	</optgroup>
	<optgroup label="Girone F">
		<option>Olanda</option>
		<option>Giappone</option>
		<option>Tunisia</option>
		<option>Vincitore_B</option>
	</optgroup>
	<optgroup label="Girone G">
		<option>Belgio</option>
		<option>Iran</option>
		<option>Egitto</option>
		<option>Nuova Zelanda</option>
	</optgroup>
	<optgroup label="Girone H">
		<option>Spagna</option>
		<option>Uruguay</option>
		<option>Arabia Saudita</option>
		<option>Capo Verde</option>
	</optgroup>
	<optgroup label="Girone I">
		<option>Francia</option>
		<option>Senegal</option>
		<option>Norvegia</option>
		<option>Vincitore_2</option>
	</optgroup>
	<optgroup label="Girone J">
		<option>Argentina</option>
		<option>Austria</option>
		<option>Algeria</option>
		<option>Giordania</option>
	</optgroup>
	<optgroup label="Girone K">
		<option>Portogallo</option>
		<option>Colombia</option>
		<option>Uzbekistan</option>
		<option>Vincitore_1</option>
	</optgroup>
	<optgroup label="Girone L">
		<option>Inghilterra</option>
		<option>Croazia</option>
		<option>Panama</option>
		<option>Ghana</option>
	</optgroup>
</select>


</TD>
			<TD ALIGN=LEFT BGCOLOR="#FFFF99">


<!-- S2 -->
<select name="question_57" class="pronostico" onchange="aggiornaPronostici(3,0)">
	<option selected>&nbsp;</option>
	<optgroup label="Girone A">
		<option>Messico</option>
		<option>Corea del Sud</option>
		<option>Sudafrica</option>
		<option>Vincitore_D</option>
	</optgroup>
	<optgroup label="Girone B">
		<option>Canada</option>
		<option>Svizzera</option>
		<option>Qatar</option>
		<option>Vincitore_A</option>
	</optgroup>
	<optgroup label="Girone C">
		<option>Brasile</option>
		<option>Marocco</option>
		<option>Scozia</option>
		<option>Haiti</option>
	</optgroup>
	<optgroup label="Girone D">
		<option>USA</option>
		<option>Australia</option>
		<option>Paraguay</option>
		<option>Vincitore_C</option>
	</optgroup>
	<optgroup label="Girone E">
		<option>Germania</option>
		<option>Ecuador</option>
		<option>Costa d'Avorio</option>
		<option>Curaçao</option>
	</optgroup>
	<optgroup label="Girone F">
		<option>Olanda</option>
		<option>Giappone</option>
		<option>Tunisia</option>
		<option>Vincitore_B</option>
	</optgroup>
	<optgroup label="Girone G">
		<option>Belgio</option>
		<option>Iran</option>
		<option>Egitto</option>
		<option>Nuova Zelanda</option>
	</optgroup>
	<optgroup label="Girone H">
		<option>Spagna</option>
		<option>Uruguay</option>
		<option>Arabia Saudita</option>
		<option>Capo Verde</option>
	</optgroup>
	<optgroup label="Girone I">
		<option>Francia</option>
		<option>Senegal</option>
		<option>Norvegia</option>
		<option>Vincitore_2</option>
	</optgroup>
	<optgroup label="Girone J">
		<option>Argentina</option>
		<option>Austria</option>
		<option>Algeria</option>
		<option>Giordania</option>
	</optgroup>
	<optgroup label="Girone K">
		<option>Portogallo</option>
		<option>Colombia</option>
		<option>Uzbekistan</option>
		<option>Vincitore_1</option>
	</optgroup>
	<optgroup label="Girone L">
		<option>Inghilterra</option>
		<option>Croazia</option>
		<option>Panama</option>
		<option>Ghana</option>
	</optgroup>
</select>


</TD>
			<TD ALIGN=LEFT BGCOLOR="#FFFF99">

<!-- S3 -->
<select name="question_58" class="pronostico" onchange="aggiornaPronostici(3,0)">
	<option selected>&nbsp;</option>
	<optgroup label="Girone A">
		<option>Messico</option>
		<option>Corea del Sud</option>
		<option>Sudafrica</option>
		<option>Vincitore_D</option>
	</optgroup>
	<optgroup label="Girone B">
		<option>Canada</option>
		<option>Svizzera</option>
		<option>Qatar</option>
		<option>Vincitore_A</option>
	</optgroup>
	<optgroup label="Girone C">
		<option>Brasile</option>
		<option>Marocco</option>
		<option>Scozia</option>
		<option>Haiti</option>
	</optgroup>
	<optgroup label="Girone D">
		<option>USA</option>
		<option>Australia</option>
		<option>Paraguay</option>
		<option>Vincitore_C</option>
	</optgroup>
	<optgroup label="Girone E">
		<option>Germania</option>
		<option>Ecuador</option>
		<option>Costa d'Avorio</option>
		<option>Curaçao</option>
	</optgroup>
	<optgroup label="Girone F">
		<option>Olanda</option>
		<option>Giappone</option>
		<option>Tunisia</option>
		<option>Vincitore_B</option>
	</optgroup>
	<optgroup label="Girone G">
		<option>Belgio</option>
		<option>Iran</option>
		<option>Egitto</option>
		<option>Nuova Zelanda</option>
	</optgroup>
	<optgroup label="Girone H">
		<option>Spagna</option>
		<option>Uruguay</option>
		<option>Arabia Saudita</option>
		<option>Capo Verde</option>
	</optgroup>
	<optgroup label="Girone I">
		<option>Francia</option>
		<option>Senegal</option>
		<option>Norvegia</option>
		<option>Vincitore_2</option>
	</optgroup>
	<optgroup label="Girone J">
		<option>Argentina</option>
		<option>Austria</option>
		<option>Algeria</option>
		<option>Giordania</option>
	</optgroup>
	<optgroup label="Girone K">
		<option>Portogallo</option>
		<option>Colombia</option>
		<option>Uzbekistan</option>
		<option>Vincitore_1</option>
	</optgroup>
	<optgroup label="Girone L">
		<option>Inghilterra</option>
		<option>Croazia</option>
		<option>Panama</option>
		<option>Ghana</option>
	</optgroup>
</select>


</TD>
			<TD ALIGN=LEFT BGCOLOR="#FFFF99">

<!-- S4 -->
<select name="question_59" class="pronostico" onchange="aggiornaPronostici(3,0)">
	<option selected>&nbsp;</option>
	<optgroup label="Girone A">
		<option>Messico</option>
		<option>Corea del Sud</option>
		<option>Sudafrica</option>
		<option>Vincitore_D</option>
	</optgroup>
	<optgroup label="Girone B">
		<option>Canada</option>
		<option>Svizzera</option>
		<option>Qatar</option>
		<option>Vincitore_A</option>
	</optgroup>
	<optgroup label="Girone C">
		<option>Brasile</option>
		<option>Marocco</option>
		<option>Scozia</option>
		<option>Haiti</option>
	</optgroup>
	<optgroup label="Girone D">
		<option>USA</option>
		<option>Australia</option>
		<option>Paraguay</option>
		<option>Vincitore_C</option>
	</optgroup>
	<optgroup label="Girone E">
		<option>Germania</option>
		<option>Ecuador</option>
		<option>Costa d'Avorio</option>
		<option>Curaçao</option>
	</optgroup>
	<optgroup label="Girone F">
		<option>Olanda</option>
		<option>Giappone</option>
		<option>Tunisia</option>
		<option>Vincitore_B</option>
	</optgroup>
	<optgroup label="Girone G">
		<option>Belgio</option>
		<option>Iran</option>
		<option>Egitto</option>
		<option>Nuova Zelanda</option>
	</optgroup>
	<optgroup label="Girone H">
		<option>Spagna</option>
		<option>Uruguay</option>
		<option>Arabia Saudita</option>
		<option>Capo Verde</option>
	</optgroup>
	<optgroup label="Girone I">
		<option>Francia</option>
		<option>Senegal</option>
		<option>Norvegia</option>
		<option>Vincitore_2</option>
	</optgroup>
	<optgroup label="Girone J">
		<option>Argentina</option>
		<option>Austria</option>
		<option>Algeria</option>
		<option>Giordania</option>
	</optgroup>
	<optgroup label="Girone K">
		<option>Portogallo</option>
		<option>Colombia</option>
		<option>Uzbekistan</option>
		<option>Vincitore_1</option>
	</optgroup>
	<optgroup label="Girone L">
		<option>Inghilterra</option>
		<option>Croazia</option>
		<option>Panama</option>
		<option>Ghana</option>
	</optgroup>
</select>



</TD>
		</TR>
		<TR>
			<TD HEIGHT=16 ALIGN=LEFT><BR></TD>
			<TD ALIGN=LEFT><BR></TD>
			<TD ALIGN=LEFT><BR></TD>
			<TD ALIGN=LEFT><BR></TD>
		</TR>
		<TR>
			<TD HEIGHT=18 ALIGN=LEFT><I>le 2 finaliste saranno:</I></TD>
			<TD ALIGN=LEFT><BR></TD>
			<TD ALIGN=LEFT><BR></TD>
			<TD COLSPAN=1 ALIGN=CENTER>Campione:</TD>
		</TR>
		<TR>
			<TD HEIGHT=25 ALIGN=CENTER BGCOLOR="#C0C0C0">

<!-- F1 -->
<select name="question_60" class="pronostico" onchange="aggiornaPronostici(2,0)">
	<option selected>&nbsp;</option>
	<optgroup label="Girone A">
		<option>Messico</option>
		<option>Corea del Sud</option>
		<option>Sudafrica</option>
		<option>Vincitore_D</option>
	</optgroup>
	<optgroup label="Girone B">
		<option>Canada</option>
		<option>Svizzera</option>
		<option>Qatar</option>
		<option>Vincitore_A</option>
	</optgroup>
	<optgroup label="Girone C">
		<option>Brasile</option>
		<option>Marocco</option>
		<option>Scozia</option>
		<option>Haiti</option>
	</optgroup>
	<optgroup label="Girone D">
		<option>USA</option>
		<option>Australia</option>
		<option>Paraguay</option>
		<option>Vincitore_C</option>
	</optgroup>
	<optgroup label="Girone E">
		<option>Germania</option>
		<option>Ecuador</option>
		<option>Costa d'Avorio</option>
		<option>Curaçao</option>
	</optgroup>
	<optgroup label="Girone F">
		<option>Olanda</option>
		<option>Giappone</option>
		<option>Tunisia</option>
		<option>Vincitore_B</option>
	</optgroup>
	<optgroup label="Girone G">
		<option>Belgio</option>
		<option>Iran</option>
		<option>Egitto</option>
		<option>Nuova Zelanda</option>
	</optgroup>
	<optgroup label="Girone H">
		<option>Spagna</option>
		<option>Uruguay</option>
		<option>Arabia Saudita</option>
		<option>Capo Verde</option>
	</optgroup>
	<optgroup label="Girone I">
		<option>Francia</option>
		<option>Senegal</option>
		<option>Norvegia</option>
		<option>Vincitore_2</option>
	</optgroup>
	<optgroup label="Girone J">
		<option>Argentina</option>
		<option>Austria</option>
		<option>Algeria</option>
		<option>Giordania</option>
	</optgroup>
	<optgroup label="Girone K">
		<option>Portogallo</option>
		<option>Colombia</option>
		<option>Uzbekistan</option>
		<option>Vincitore_1</option>
	</optgroup>
	<optgroup label="Girone L">
		<option>Inghilterra</option>
		<option>Croazia</option>
		<option>Panama</option>
		<option>Ghana</option>
	</optgroup>
</select>


</TD>
			<TD ALIGN=CENTER BGCOLOR="#C0C0C0">

<!-- F2 -->
<select name="question_61" class="pronostico" onchange="aggiornaPronostici(2,0)">
	<option selected>&nbsp;</option>
	<optgroup label="Girone A">
		<option>Messico</option>
		<option>Corea del Sud</option>
		<option>Sudafrica</option>
		<option>Vincitore_D</option>
	</optgroup>
	<optgroup label="Girone B">
		<option>Canada</option>
		<option>Svizzera</option>
		<option>Qatar</option>
		<option>Vincitore_A</option>
	</optgroup>
	<optgroup label="Girone C">
		<option>Brasile</option>
		<option>Marocco</option>
		<option>Scozia</option>
		<option>Haiti</option>
	</optgroup>
	<optgroup label="Girone D">
		<option>USA</option>
		<option>Australia</option>
		<option>Paraguay</option>
		<option>Vincitore_C</option>
	</optgroup>
	<optgroup label="Girone E">
		<option>Germania</option>
		<option>Ecuador</option>
		<option>Costa d'Avorio</option>
		<option>Curaçao</option>
	</optgroup>
	<optgroup label="Girone F">
		<option>Olanda</option>
		<option>Giappone</option>
		<option>Tunisia</option>
		<option>Vincitore_B</option>
	</optgroup>
	<optgroup label="Girone G">
		<option>Belgio</option>
		<option>Iran</option>
		<option>Egitto</option>
		<option>Nuova Zelanda</option>
	</optgroup>
	<optgroup label="Girone H">
		<option>Spagna</option>
		<option>Uruguay</option>
		<option>Arabia Saudita</option>
		<option>Capo Verde</option>
	</optgroup>
	<optgroup label="Girone I">
		<option>Francia</option>
		<option>Senegal</option>
		<option>Norvegia</option>
		<option>Vincitore_2</option>
	</optgroup>
	<optgroup label="Girone J">
		<option>Argentina</option>
		<option>Austria</option>
		<option>Algeria</option>
		<option>Giordania</option>
	</optgroup>
	<optgroup label="Girone K">
		<option>Portogallo</option>
		<option>Colombia</option>
		<option>Uzbekistan</option>
		<option>Vincitore_1</option>
	</optgroup>
	<optgroup label="Girone L">
		<option>Inghilterra</option>
		<option>Croazia</option>
		<option>Panama</option>
		<option>Ghana</option>
	</optgroup>
</select>



</TD>
			<TD ALIGN=CENTER><BR></TD>
			<TD COLSPAN=1 ALIGN=LEFT BGCOLOR="#FFFF00">

<!-- C -->
<select name="question_62" class="pronostico" onchange="aggiornaPronostici(1,0)">
	<option selected>&nbsp;</option>
	<optgroup label="Girone A">
		<option>Messico</option>
		<option>Corea del Sud</option>
		<option>Sudafrica</option>
		<option>Vincitore_D</option>
	</optgroup>
	<optgroup label="Girone B">
		<option>Canada</option>
		<option>Svizzera</option>
		<option>Qatar</option>
		<option>Vincitore_A</option>
	</optgroup>
	<optgroup label="Girone C">
		<option>Brasile</option>
		<option>Marocco</option>
		<option>Scozia</option>
		<option>Haiti</option>
	</optgroup>
	<optgroup label="Girone D">
		<option>USA</option>
		<option>Australia</option>
		<option>Paraguay</option>
		<option>Vincitore_C</option>
	</optgroup>
	<optgroup label="Girone E">
		<option>Germania</option>
		<option>Ecuador</option>
		<option>Costa d'Avorio</option>
		<option>Curaçao</option>
	</optgroup>
	<optgroup label="Girone F">
		<option>Olanda</option>
		<option>Giappone</option>
		<option>Tunisia</option>
		<option>Vincitore_B</option>
	</optgroup>
	<optgroup label="Girone G">
		<option>Belgio</option>
		<option>Iran</option>
		<option>Egitto</option>
		<option>Nuova Zelanda</option>
	</optgroup>
	<optgroup label="Girone H">
		<option>Spagna</option>
		<option>Uruguay</option>
		<option>Arabia Saudita</option>
		<option>Capo Verde</option>
	</optgroup>
	<optgroup label="Girone I">
		<option>Francia</option>
		<option>Senegal</option>
		<option>Norvegia</option>
		<option>Vincitore_2</option>
	</optgroup>
	<optgroup label="Girone J">
		<option>Argentina</option>
		<option>Austria</option>
		<option>Algeria</option>
		<option>Giordania</option>
	</optgroup>
	<optgroup label="Girone K">
		<option>Portogallo</option>
		<option>Colombia</option>
		<option>Uzbekistan</option>
		<option>Vincitore_1</option>
	</optgroup>
	<optgroup label="Girone L">
		<option>Inghilterra</option>
		<option>Croazia</option>
		<option>Panama</option>
		<option>Ghana</option>
	</optgroup>
</select>


</TD>
		</TR>
		<TR>
			<TD HEIGHT=16 ALIGN=LEFT><BR></TD>
			<TD ALIGN=LEFT><BR></TD>
			<TD ALIGN=LEFT><BR></TD>
			<TD ALIGN=LEFT><BR></TD>
		</TR>
		<TR>
			<TD COLSPAN=4 HEIGHT=20 ALIGN=LEFT><FONT SIZE=1><?php echo($lotteria['msg_custom'][0][0]); ?></FONT></TD>
			</TR>
			<TR>
			<TD COLSPAN=4 HEIGHT=20 ALIGN=LEFT><FONT SIZE=1>Il presente Studio/Sondaggio &egrave; proposto dall'ARS (Amatori Running Sele) a puro scopo ricreativo e di approfondimento del dibattito permanente sullo sport, presente nell'associazione Amatori R.S..</FONT></TD>
			</TR>
		<TR>
			<TD COLSPAN=4 HEIGHT=20 ALIGN=LEFT><FONT SIZE=1>Esso ovviamente non &egrave; esente da oneri di gestione (<I>che quindi vanno a cumularsi con gli altri costi relativi all'attivit&agrave; dell'Associazione&hellip;</I>). Saranno ben accette le collaborazioni (anche minime: 1 ora settimanale, 1 ora mensile, annuale o una tantum) e i contributi di idee.</FONT></TD>
			</TR>
	</TBODY>
</TABLE>


</td>













<!-- colonna vuota -->
<td>
	<br>
</td>












<!-- colonna destra: info varie -->
<!-- <td style="display:none;"> -->
<td>

<TABLE FRAME=VOID CELLSPACING=0 RULES=GROUPS BORDER=1 SUMMARY=right_column_table>
	<TBODY>
		<TR>
			<TD ALIGN=LEFT><BR></TD>
			<TD ALIGN=LEFT><BR></TD>
			<TD ALIGN=LEFT><BR></TD>
		</TR>
		<TR>
			<TD COLSPAN=3 ALIGN=CENTER BGCOLOR="#CCFFFF"><B>Gironi eliminatori (11 - 27 giugno 2026)</B></TD>
		</TR>
		<TR>
			<TD COLSPAN=3 ALIGN=LEFT BGCOLOR="#CCFFFF">La prima fase si articolera' in 72 partite (6 per ciascun girone) in 17 giorni:</TD>
		</TR>
		<TR>
			<TD COLSPAN=3 ALIGN=LEFT BGCOLOR="#CCFFFF">si giochera' in diverse fasce orarie (orari variabili a seconda dei fusi orari di Canada, Messico e USA)</TD>
		</TR>
		
		
		<TR>
			<TD ALIGN=LEFT><BR></TD>
			<TD ALIGN=LEFT><BR></TD>
			<TD ALIGN=LEFT><BR></TD>
		</TR>		
		
		
		<TR>
			<TD COLSPAN=3 ALIGN=CENTER BGCOLOR="#DDFFAA"><B>Sedicesimi di Finale (28 giugno - 3 luglio 2026)</B></TD>
		</TR>
		<TR>
			<TD ALIGN=LEFT BGCOLOR="#DDFFAA">28/06 h. 12:00 (21:00)</TD>
			<TD ALIGN=CENTER BGCOLOR="#DDFFAA">Los Angeles</TD>
			<TD ALIGN=CENTER BGCOLOR="#DDFFAA">2A-2B (O1 73)</TD>
		</TR>
		<TR>
			<TD ALIGN=LEFT BGCOLOR="#DDFFAA">29/06 h. 16:30 (22:30)</TD>
			<TD ALIGN=CENTER BGCOLOR="#DDFFAA">Boston</TD>
			<TD ALIGN=CENTER BGCOLOR="#DDFFAA">1E-3A/B/C/D/F (O2 74)</TD>
		</TR>
		<TR>
			<TD ALIGN=LEFT BGCOLOR="#DDFFAA">29/06 h. 19:00 (03:00 30/06)</TD>
			<TD ALIGN=CENTER BGCOLOR="#DDFFAA">Monterrey</TD>
			<TD ALIGN=CENTER BGCOLOR="#DDFFAA">1F-2C (O3 75)</TD>
		</TR>
		<TR>
			<TD ALIGN=LEFT BGCOLOR="#DDFFAA">29/06 h. 12:00 (19:00)</TD>
			<TD ALIGN=CENTER BGCOLOR="#DDFFAA">Houston</TD>
			<TD ALIGN=CENTER BGCOLOR="#DDFFAA">1C-2F (O4 76)</TD>
		</TR>
		<TR>
			<TD ALIGN=LEFT BGCOLOR="#DDFFAA">30/06 h. 17:00 (23:00)</TD>
			<TD ALIGN=CENTER BGCOLOR="#DDFFAA">New York/NJ</TD>
			<TD ALIGN=CENTER BGCOLOR="#DDFFAA">1I-3C/D/F/G/H (O5 77)</TD>
		</TR>
		<TR>
			<TD ALIGN=LEFT BGCOLOR="#DDFFAA">30/06 h. 12:00 (19:00)</TD>
			<TD ALIGN=CENTER BGCOLOR="#DDFFAA">Dallas</TD>
			<TD ALIGN=CENTER BGCOLOR="#DDFFAA">2E-2I (O6 78)</TD>
		</TR>
		<TR>
			<TD ALIGN=LEFT BGCOLOR="#DDFFAA">30/06 h. 19:00 (03:00 01/07)</TD>
			<TD ALIGN=CENTER BGCOLOR="#DDFFAA">Mexico City</TD>
			<TD ALIGN=CENTER BGCOLOR="#DDFFAA">1A-3C/E/F/H/I (O7 79)</TD>
		</TR>
		<TR>
			<TD ALIGN=LEFT BGCOLOR="#DDFFAA">01/07 h. 12:00 (18:00)</TD>
			<TD ALIGN=CENTER BGCOLOR="#DDFFAA">Atlanta</TD>
			<TD ALIGN=CENTER BGCOLOR="#DDFFAA">1L-3E/H/I/J/K (O8 80)</TD>
		</TR>
		<TR>
			<TD ALIGN=LEFT BGCOLOR="#DDFFAA">01/07 h. 17:00 (02:00 02/07)</TD>
			<TD ALIGN=CENTER BGCOLOR="#DDFFAA">San Francisco</TD>
			<TD ALIGN=CENTER BGCOLOR="#DDFFAA">1D-3B/E/F/I/J (O9 81)</TD>
		</TR>
		<TR>
			<TD ALIGN=LEFT BGCOLOR="#DDFFAA">01/07 h. 13:00 (22:00)</TD>
			<TD ALIGN=CENTER BGCOLOR="#DDFFAA">Seattle</TD>
			<TD ALIGN=CENTER BGCOLOR="#DDFFAA">1G-3A/E/H/I/J (O10 82)</TD>
		</TR>
		<TR>
			<TD ALIGN=LEFT BGCOLOR="#DDFFAA">02/07 h. 19:00 (01:00 03/07)</TD>
			<TD ALIGN=CENTER BGCOLOR="#DDFFAA">Toronto</TD>
			<TD ALIGN=CENTER BGCOLOR="#DDFFAA">2K-2L (O11 83)</TD>
		</TR>
		<TR>
			<TD ALIGN=LEFT BGCOLOR="#DDFFAA">02/07 h. 12:00 (21:00)</TD>
			<TD ALIGN=CENTER BGCOLOR="#DDFFAA">Los Angeles</TD>
			<TD ALIGN=CENTER BGCOLOR="#DDFFAA">1H-2J (O12 84)</TD>
		</TR>
		<TR>
			<TD ALIGN=LEFT BGCOLOR="#DDFFAA">02/07 h. 20:00 (05:00 03/07)</TD>
			<TD ALIGN=CENTER BGCOLOR="#DDFFAA">Vancouver</TD>
			<TD ALIGN=CENTER BGCOLOR="#DDFFAA">1B-3E/F/G/I/J (O13 85)</TD>
		</TR>
		<TR>
			<TD ALIGN=LEFT BGCOLOR="#DDFFAA">03/07 h. 18:00 (00:00 04/07)</TD>
			<TD ALIGN=CENTER BGCOLOR="#DDFFAA">Miami</TD>
			<TD ALIGN=CENTER BGCOLOR="#DDFFAA">1J-2H (O14 86)</TD>
		</TR>
		<TR>
			<TD ALIGN=LEFT BGCOLOR="#DDFFAA">03/07 h. 20:30 (03:30 04/07)</TD>
			<TD ALIGN=CENTER BGCOLOR="#DDFFAA">Kansas City</TD>
			<TD ALIGN=CENTER BGCOLOR="#DDFFAA">1K-3D/E/I/J/L (O15 87)</TD>
		</TR>
		<TR>
			<TD ALIGN=LEFT BGCOLOR="#DDFFAA">03/07 h. 13:00 (20:00)</TD>
			<TD ALIGN=CENTER BGCOLOR="#DDFFAA">Dallas</TD>
			<TD ALIGN=CENTER BGCOLOR="#DDFFAA">2D-2G (O16 88)</TD>
		</TR>
		
		
		
		<TR>
			<TD ALIGN=LEFT><BR></TD>
			<TD ALIGN=LEFT><BR></TD>
			<TD ALIGN=LEFT><BR></TD>
		</TR>
		
		
		
		<TR>
			<TD COLSPAN=3 ALIGN=CENTER BGCOLOR="#FFDDDD"><B>Ottavi di Finale (4 - 7 luglio 2026)</B></TD>
		</TR>
		<TR>
			<TD ALIGN=LEFT BGCOLOR="#FFDDDD">04/07 h. 17:00 (23:00)</TD>
			<TD ALIGN=CENTER BGCOLOR="#FFDDDD">Philadelphia</TD>
			<TD ALIGN=CENTER BGCOLOR="#FFDDDD">O2-O5 74-77 (Q1 89)</TD>
		</TR>
		<TR>
			<TD ALIGN=LEFT BGCOLOR="#FFDDDD">04/07 h. 12:00 (19:00)</TD>
			<TD ALIGN=CENTER BGCOLOR="#FFDDDD">Houston</TD>
			<TD ALIGN=CENTER BGCOLOR="#FFDDDD">O1-O4 73-76 (Q2 90)</TD>
		</TR>
		<TR>
			<TD ALIGN=LEFT BGCOLOR="#FFDDDD">05/07 h. 16:00 (22:00)</TD>
			<TD ALIGN=CENTER BGCOLOR="#FFDDDD">New York/NJ</TD>
			<TD ALIGN=CENTER BGCOLOR="#FFDDDD">O3-O6 75-78 (Q3 91)</TD>
		</TR>
		<TR>
			<TD ALIGN=LEFT BGCOLOR="#FFDDDD">05/07 h. 18:00 (02:00 06/07)</TD>
			<TD ALIGN=CENTER BGCOLOR="#FFDDDD">Mexico City</TD>
			<TD ALIGN=CENTER BGCOLOR="#FFDDDD">O7-O8 79-80 (Q4 92)</TD>
		</TR>
		<TR>
			<TD ALIGN=LEFT BGCOLOR="#FFDDDD">06/07 h. 14:00 (21:00)</TD>
			<TD ALIGN=CENTER BGCOLOR="#FFDDDD">Dallas</TD>
			<TD ALIGN=CENTER BGCOLOR="#FFDDDD">O12-O15 84-87 (Q5 93)</TD>
		</TR>
		<TR>
			<TD ALIGN=LEFT BGCOLOR="#FFDDDD">06/07 h. 17:00 (02:00 07/07)</TD>
			<TD ALIGN=CENTER BGCOLOR="#FFDDDD">Seattle</TD>
			<TD ALIGN=CENTER BGCOLOR="#FFDDDD">O11-O14 83-86 (Q6 94)</TD>
		</TR>
		<TR>
			<TD ALIGN=LEFT BGCOLOR="#FFDDDD">07/07 h. 12:00 (18:00)</TD>
			<TD ALIGN=CENTER BGCOLOR="#FFDDDD">Atlanta</TD>
			<TD ALIGN=CENTER BGCOLOR="#FFDDDD">O9-O10 81-82 (Q7 95)</TD>
		</TR>
		<TR>
			<TD ALIGN=LEFT BGCOLOR="#FFDDDD">07/07 h. 13:00 (22:00)</TD>
			<TD ALIGN=CENTER BGCOLOR="#FFDDDD">Vancouver</TD>
			<TD ALIGN=CENTER BGCOLOR="#FFDDDD">O13-O16 85-88 (Q8 96)</TD>
		</TR>		
		
		
		<TR>
			<TD ALIGN=LEFT><BR></TD>
			<TD ALIGN=LEFT><BR></TD>
			<TD ALIGN=LEFT><BR></TD>
		</TR>
		
		
		
		<TR>
			<TD COLSPAN=3 ALIGN=CENTER BGCOLOR="#CCFFCC"><B>Quarti di Finale (9 - 11 luglio 2026)</B></TD>
		</TR>
		<TR>
			<TD ALIGN=LEFT BGCOLOR="#CCFFCC">09/07 h. 16:00 (22:00)</TD>
			<TD ALIGN=CENTER BGCOLOR="#CCFFCC">Boston</TD>
			<TD ALIGN=CENTER BGCOLOR="#CCFFCC">Q1-Q2 89-90 (S1 97)</TD>
		</TR>
		<TR>
			<TD ALIGN=LEFT BGCOLOR="#CCFFCC">10/07 h. 12:00 (21:00)</TD>
			<TD ALIGN=CENTER BGCOLOR="#CCFFCC">Los Angeles</TD>
			<TD ALIGN=CENTER BGCOLOR="#CCFFCC">Q5-Q6 93-94 (S2 98)</TD>
		</TR>
		<TR>
			<TD ALIGN=LEFT BGCOLOR="#CCFFCC">11/07 h. 17:00 (23:00)</TD>
			<TD ALIGN=CENTER BGCOLOR="#CCFFCC">Miami</TD>
			<TD ALIGN=CENTER BGCOLOR="#CCFFCC">Q3-Q4 91-92 (S3 99)</TD>
		</TR>
		<TR>
			<TD ALIGN=LEFT BGCOLOR="#CCFFCC">11/07 h. 20:00 (03:00 12/07)</TD>
			<TD ALIGN=CENTER BGCOLOR="#CCFFCC">Kansas City</TD>
			<TD ALIGN=CENTER BGCOLOR="#CCFFCC">Q7-Q8 95-96 (S4 100)</TD>
		</TR>
		
		
		
		<TR>
			<TD ALIGN=LEFT><BR></TD>
			<TD ALIGN=LEFT><BR></TD>
			<TD ALIGN=LEFT><BR></TD>
		</TR>
		
		
		
		<TR>
			<TD COLSPAN=3 ALIGN=CENTER BGCOLOR="#FFFF99"><B>Semifinali (14 - 15 luglio 2026)</B></TD>
		</TR>
		<TR>
			<TD ALIGN=LEFT BGCOLOR="#FFFF99">14/07 h. 14:00 (21:00)</TD>
			<TD ALIGN=CENTER BGCOLOR="#FFFF99">Dallas</TD>
			<TD ALIGN=CENTER BGCOLOR="#FFFF99">S1-S2 97-98 (F1 101)</TD>
		</TR>
		<TR>
			<TD ALIGN=LEFT BGCOLOR="#FFFF99">15/07 h. 15:00 (21:00)</TD>
			<TD ALIGN=CENTER BGCOLOR="#FFFF99">Atlanta</TD>
			<TD ALIGN=CENTER BGCOLOR="#FFFF99">S3-S4 99-100 (F2 102)</TD>
		</TR>
		
		
		
		<TR>
			<TD ALIGN=LEFT><BR></TD>
			<TD ALIGN=LEFT><BR></TD>
			<TD ALIGN=LEFT><BR></TD>
		</TR>
		
		
		
		<TR>
			<TD COLSPAN=3 ALIGN=CENTER BGCOLOR="#C0C0C0"><B>Finale 1°-2° posto (19 luglio 2026)</B></TD>
		</TR>
		<TR>
			<TD ALIGN=LEFT BGCOLOR="#C0C0C0">19/07 h. 15:00 (21:00)</TD>
			<TD ALIGN=CENTER BGCOLOR="#C0C0C0">New York/NJ</TD>
			<TD ALIGN=CENTER BGCOLOR="#C0C0C0">F1-F2 101-102 (C 104)</TD>
		</TR>
		
		
		
		<TR>
			<TD ALIGN=LEFT><BR></TD>
			<TD ALIGN=LEFT><BR></TD>
			<TD ALIGN=LEFT><BR></TD>
		</TR>
		<TR>
			<TD ALIGN=LEFT><BR></TD>
			<TD ALIGN=LEFT><BR></TD>
			<TD ALIGN=LEFT><BR></TD>
		</TR>
		<TR>
			<TD ALIGN=LEFT><BR></TD>
			<TD ALIGN=LEFT><BR></TD>
			<TD ALIGN=LEFT><BR></TD>
		</TR>
		<TR>
			<TD ALIGN=LEFT><BR></TD>
			<TD ALIGN=LEFT><BR></TD>
			<TD ALIGN=LEFT><BR></TD>
		</TR>
		<TR>
			<TD ALIGN=LEFT><BR></TD>
			<TD ALIGN=LEFT><BR></TD>
			<TD ALIGN=LEFT><BR></TD>
		</TR>
		<TR>
			<TD ALIGN=LEFT><BR></TD>
			<TD ALIGN=LEFT><BR></TD>
			<TD ALIGN=LEFT><BR></TD>
		</TR>
		<TR>
			<TD ALIGN=LEFT><BR></TD>
			<TD ALIGN=LEFT><BR></TD>
			<TD ALIGN=LEFT><BR></TD>
		</TR>
		<TR>
			<TD ALIGN=LEFT><BR></TD>
			<TD ALIGN=LEFT><BR></TD>
			<TD ALIGN=LEFT><BR></TD>
		</TR>
	</TBODY>
</TABLE>

</td></tr></tbody></table>


<?php
if ($admin_mode) {
?>
<br>
Data di ricezione giocata (hh:mm gg/mm/aaaa):<input type="text" name="data_giocata"><br>
<br>
Chiave segreta:<input type="text" name="auth_token" value=""><br>
<?php
}
else
{
	echo "<input type=\"hidden\" name=\"auth_token\" value=\"$auth_token\"><br>";
}
?>
<input type="hidden" name="id_questions" value="<?php echo $id_questions ?>">
<!--input type="hidden" name="action" value="last_check"-->
<input type="hidden" name="action" value="save">
<br>
<?php
if (!$info_mode)
{
	echo "<input type=\"submit\" value=\"Gioca\" OnClick=\"return print_warning()\"/>";
}
?>


<?php
$file_log_questions = $root_path."custom/lotterie/".sprintf('lotteria_%03d_log.txt',$id_questions);
if ($flag_show_results && file_exists($file_log_questions))
{
?>
<table summary="link_pronostici">
 <tbody>
 <tr style='height:12.0pt'>
  <td colspan=10 style='height:12.0pt;'></td>
  <td></td>
  <td colspan=2></td>
 </tr>
 <tr style='height:12.0pt'>
  <td style='height:12.0pt'></td>
  <td class=xl49><a href="questions.php?id_questions=<?php echo $id_questions; ?>&amp;action=results">Visualizza i pronostici gi&agrave; salvati</a></td>
  <td colspan=8></td>
  <td></td>
  <td colspan=2></td>
 </tr>
 </tbody>
</table>
<?php
}
?>






<!--
//
// fine della pagina customizzata (fine del codice html customizzato (senza header ne' tag body))
//
-->
</form>
