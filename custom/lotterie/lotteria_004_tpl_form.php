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

$enable_check_Caposele = 1; // 1 --> fai il test di caposelitudine; 0 -> nessun test

//
// parte generica
//

if ($admin_mode)
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
$action = $admin_path_correction."questions.php";		// da non modificare
$javascript_library = $admin_path_correction."questions.js";	// da non modificare

// caricamento, se necessario, della libreria
if (strlen(strpos($_SERVER['SCRIPT_FILENAME'],"questions.php")) == 0)
{
	// se non si sta venendo da questions.php, carica la libreria
	$admin_mode = $_REQUEST['admin_mode'];					// azione da eseguire
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
<meta name="keywords" content="lotteria, questionario, Mondiali di Calcio 2010, sondaggio, classifica">
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
<body link=blue vlink=purple onLoad="document.forms['question_form']['question_35'].value='0';
if (document.forms['question_form']['data_giocata']) {document.forms['question_form']['data_giocata'].value='';}
if (document.forms['question_form']['temp_silaritudine']) {document.forms['question_form']['temp_silaritudine'].value='No';}"> <!-- inizializza alcuni campi sensibili -->

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
	Array(1,"Per chi si reca in gita alla Mauta è consigliabile:",
			Array("Portare il costume per un bagno nelle acque del Sele","Prolungare la gita fino alle rovine romane di Valle di Porco","Programmare un'escursione in cima al Calvello")					        ,"001"	),

	Array(2,"Quando e' pscrai?"	,
			Array("Ieri","Dopodomani","Oggi","Domani")							,"0100"	),

	Array(3,"Cosa significa la parola 'ngimma?"         ,
			Array("ginnastica","giorno","sopra","gomma")     						,"0010" ),

	Array(4,"Qual'è il Santo patrono di Caposele?"      ,
			Array("Lorenzo","Gerardo","Pasquale","Rocco")    						,"1000" ),

	Array(5,"Cosa significa la parola 'nbieri?"         ,
			Array("Carabinieri","Ieri","Bicchieri","Sotto")  						,"0001" ),

	Array(6,"Vagava per le strade di Caposele"          ,
			Array("Peppe il francese","Ciccio l'americano","Antonio l'africano","Gerardo l'austriaco")	,"0001"),

	Array(7,"Quale dei seguenti non è un quartiere di Caposele?"          ,
			Array("Portella","Pianello","Campo Piano","Castello","Sanita'")					,"00100"),

	Array(8,"In che mese si festeggia la Madonna della Sanità?" ,
			Array("Luglio","Agosto","Settembre","Ottobre")  						,"0100"),

	Array(9,"Da dove posso vedere la preta r' li cuorvi?"  ,
			Array("dalla Castagneta","da Pasano","da Persano","da San Giovanni")  				,"1000"),

	Array(10,"Dove vennero ubicate le scuole elementari e medie di Caposele dopo il terremoto del 1980?"  ,
			Array("ai Piani","a li fuossi","alla Sanità","al ponte") 					, "0010"),

	Array(11,"Nel rispetto della tradizione, nell'anno 1974 a Caposele non e' nato nessun bambino con il nome di:"          ,
			Array("Giuseppe","Rocco","Antonio","Aniello")							,"0001"),

	Array(12,"Che sport praticava Manliuccio?"           ,
			Array("Tennis","Corsa","Calcio","Ciclismo")      						,"0010" ),

//	Array(13,"Dov'e' Tredogge?"	,
//			Array("Alla foce del fiume","Vicino a Materdomini","In montagna","Vicino alle sorgenti del Sele")	,"0001"	),

//	Array(14,"Chi era il sindaco di Caposele nel 2005?"  ,
//			Array("Alfonso Merola","Giuseppe Melillo","Antonio Corona","Agostino Montanari")  		,"0100" ),

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
// alert('1: '+f['question_35'].value);
// Questa funzione verifica la correttezza della giocata prima di inviare i dati per il salvataggio
	
	// configurazione errori ammissibili
	index_check_gironi_ok = 0;
	allow_errors = new Array; // 0 -> l'errore non permette il salvataggio; 1 -> viene visualizzato soltanto un warning
	
	allow_errors[index_check_gironi_ok] = 0; // mettere a 0 per impedire di giocare con un errore alla regola "due squadre per ciascun girone"
	
	// leggi tutti i campi del tipo question_xx
	list = read_form_fields(f);
	if ( (list.length == 1) & (list[0] == '%errore%') )
	{
		return false;
	}
	
// alert('2: '+list.length);
	// verifica congruenza delle risposte
	risposte_ok = true;
	messaggio_errore = 'Messaggio di errore!';
	
	// ripartizione nelle diverse classi
	list_W = new Array;
	list_Q = new Array;
	list_S = new Array;
	list_F = new Array;
	list_C = new Array;
	list_o = new Array;
	
	for (i = 0; i < list.length; i++)
	{
		
		if (i < 16)
		{
			list_W[list_W.length] = list[i];
		}
// 		if (i < 8)
		else if (i < 24)
		{
			list_Q[list_Q.length] = list[i];
		}
// 		else if (i < 12)
		else if (i < 28)
		{
			list_S[list_S.length] = list[i];
		}
// 		else if (i < 14)
		else if (i < 30)
		{
			list_F[list_F.length] = list[i];
		}
// 		else if (i < 15)
		else if (i < 31)
		{
			list_C[0] = list[i];
		}
		else
		{
			list_o[list_o.length] = list[i];
		}
	}
	
	// verifica correttezza 8 squadre ammesse quarti di finale
	gironeA = new Array("Sudafrica","Messico","Uruguay","Francia");
	gironeB = new Array("Argentina","Nigeria","Corea del Sud","Grecia");
	gironeC = new Array("Inghilterra","USA","Algeria","Slovenia");
	gironeD = new Array("Germania","Australia","Serbia","Ghana");
	gironeE = new Array("Olanda","Danimarca","Camerun","Giappone");
	gironeF = new Array("Italia","Paraguay","Nuova Zelanda","Slovacchia");
	gironeG = new Array("Brasile","Corea del Nord","Costa D'Avorio","Portogallo");
	gironeH = new Array("Spagna","Svizzera","Honduras","Cile");
	
	
	gironi = new Array(gironeA,gironeB,gironeC,gironeD,gironeE,gironeF,gironeG,gironeH);
// 	gironi = new Array(gironeA,gironeB,gironeC,gironeD);
	
	
	num_per_girone = 2;
	
	vettore_gironi = new Array(0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0); // stesso numero dei qualificati alle eliminatorie
	gironi_errati = false;
	for (i = 0; i < list_W.length; i++)
	{
		squadra = list_W[i];
// continue; // !!!
		// alert('Qualificati ('+(i+1)+'): '+squadra);
		
		girone = get_girone(squadra,gironi);
		if ((++vettore_gironi[girone]) > num_per_girone)
		{
			gironi_errati = true;
		}
		
		// verifica ripetizioni all'interno dello stesso gruppo
		ripetizioni = occurrencies(squadra,list_W);
		if (ripetizioni > 1)
		{
			alert('Nei qualificati agli ottavi di finale la squadra '+squadra+' compare '+ripetizioni+' volte!');
			return false;
		}
		
	}
	
	
	// visualizza messaggio d'errore nel caso non siano indicate num_per_girone squadre per girone
	if (gironi_errati)
	{
		if (num_per_girone == 1)
		{
			msg = "Nei qualificati agli ottavi di finale non viene indicata 1 squadra per ciascuno girone:";
		}
		else
		{
			msg = "Nei qualificati agli ottavi di finale non vengono indicate "+num_per_girone+" squadre per ciascuno girone:";
		}
		
		for (i=0; i <= (gironi.length-1); i++)
		{ 
			msg += "\n    Girone "+String.fromCharCode(i+65)+': '+vettore_gironi[i]; 
			
			errore = vettore_gironi[i]-num_per_girone;
			if (errore > 0)
			{
				msg += " (eliminare "+errore+" squadra/e)";
			}
			if (errore < 0)
			{
				msg += " (aggiungere "+(-errore)+" squadra/e)";
			}
		}
		alert(msg);
		// se non sei in admin_mode, e non e' consentito violare la regola "2 squadre per girone", esci senza salvare
		if ((!0) & (!allow_errors[index_check_gironi_ok]))
		{
			return false;
		}
	}
	
	// verifica correttezza 8 squadre ammesse quarti di finale
	for (i = 0; i < list_Q.length; i++)
	{
		squadra = list_Q[i];
// continue; // !!!
		//alert('Qualificati ('+(i+1)+'): '+squadra);
		
		girone = get_girone(squadra,gironi);
		if ((++vettore_gironi[girone]) > num_per_girone)
		{
			gironi_errati = true;
		}
		
		// verifica ripetizioni all'interno dello stesso gruppo
		ripetizioni = occurrencies(squadra,list_Q);
		if (ripetizioni > 1)
		{
			alert('Nei qualificati alle semifinali la squadra '+squadra+' compare '+ripetizioni+' volte!');
			return false;
		}
		
		// verifica la presenza all'interno del gruppo precedente
		ripetizioni = occurrencies(squadra,list_W);
		if (ripetizioni > 1)
		{
			// alert('Nei qualificati ai quarti di finale la squadra '+squadra+' compare '+ripetizioni+' volte!');
			alert('La squadra '+squadra+" compare nei qualificati ai quarti, ma non e' presente tra quelle qualificate agli ottavi!");
			return false;
		}
		
	}
	
	// verifica correttezza 4 squadre ammesse semifinale
	for (i = 0; i < list_S.length; i++)
	{
		squadra = list_S[i];
		//alert('Qualificati ('+(i+1)+'): '+squadra);
		
		// verifica ripetizioni all'interno dello stesso gruppo
		ripetizioni = occurrencies(squadra,list_S);
		if (ripetizioni > 1)
		{
			alert('Nei qualificati alle semifinali la squadra '+squadra+' compare '+ripetizioni+' volte!');
			return false;
		}
		
		// verifica la presenza all'interno del gruppo precedente
		ripetizioni = occurrencies(squadra,list_Q);
		if (ripetizioni != 1)
		{
			alert('La squadra '+squadra+" compare nei qualificati alle semifinali, ma non e' presente tra quelle qualificate ai quarti!");
			return false;
		}
	}
	
	// verifica correttezza 2 squadre ammesse finale
	for (i = 0; i < list_F.length; i++)
	{
		squadra = list_F[i];
// continue; // !!!
		//alert('Qualificati ('+(i+1)+'): '+squadra);
		
		// verifica ripetizioni all'interno dello stesso gruppo
		ripetizioni = occurrencies(squadra,list_F);
		if (ripetizioni > 1)
		{
			alert('Nei qualificati alla finale la squadra '+squadra+' compare '+ripetizioni+' volte!');
			return false;
		}
		
		// verifica la presenza all'interno del gruppo precedente
		ripetizioni = occurrencies(squadra,list_S);
		if (ripetizioni != 1)
		{
			alert('La squadra '+squadra+" compare nei qualificati alla finale, ma non e' presente tra quelle qualificate in semifinale!");
			return false;
		}
	}
	
	// verifica correttezza squadra vincitrice
	squadra = list_C[0];
	ripetizioni = occurrencies(squadra,list_F);
	if (ripetizioni != 1)
// 	if (0) // !!!
	{
		alert('La squadra '+squadra+" e' indicata come vincitrice, ma non e' presente tra quelle qualificate in finale!");
		return false;
	}
// 	alert('Qualificati ('+(ripetizioni)+'): '+squadra);
	
	
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

	tag_feedback = 'question_35'; // nome del campo nascosto, con cui interagisce la popup window


	// verifica formato data (auth_nato)
	formato_data = /^[0-9]{2}\/[0-9]{2}\/[0-9]{4}$/;
	if (!auth_nato.match(formato_data))
	{
		alert("Il formato della data di nascita deve essere gg/mm/aaaa (es. 31/12/1974)!");
		return false;
	}
	
// alert('3: '+auth_provenienza.toUpperCase(auth_provenienza));

	// gestione Caposelesi
	if (!<?php echo $admin_mode; ?> && <?php echo $enable_check_Caposele; ?>)
	{
		switch (auth_provenienza.toUpperCase(auth_provenienza))
		{
		case 'CAPOSELE':
/*		case 'MATERDOMINI':
		case 'PORTELLA':
		case 'BUONINVENTRE':
		case 'PASANO':*/
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
						msg_allowed_errors = 'Il numero max. di errori consentito è: '+num_allowed_errors+'\r\n';
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
			<TD COLSPAN=4 WIDTH=801 HEIGHT=23 ALIGN=CENTER><FONT SIZE=4>Mondiali 2010 - Scheda per il sondaggio -</FONT></TD>
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

<!-- #16 -->
<input name='question_31' type='text' value=''>

	</td>


	<td>

<!-- #17 -->
<input name='question_32' type='text' value=''>

	</td>


	<td>

<!-- #18 -->
<input name='question_33' type='text' value=''>

	</td>


	<td>

<!-- #19 -->
<input name='question_34' type='text' value=''>

<!-- #20 -->
<input name='question_35' type='hidden' value='0'>

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
			<TD COLSPAN=4 HEIGHT=16 ALIGN=LEFT>La composizione degli otto gironi eliminatori</TD>
		</TR>
		<TR>
			<TD HEIGHT=18 ALIGN=LEFT BGCOLOR="#FFFF99"><FONT FACE="Arial Narrow">Gruppo A</FONT></TD>
			<TD ALIGN=LEFT BGCOLOR="#FFFF99"><FONT FACE="Arial Narrow">Gruppo B</FONT></TD>
			<TD ALIGN=LEFT BGCOLOR="#FFFF99"><FONT FACE="Arial Narrow">Gruppo C</FONT></TD>
			<TD ALIGN=LEFT BGCOLOR="#FFFF99"><FONT FACE="Arial Narrow">Gruppo D</FONT></TD>
			</TR>
		<TR>
			<TD HEIGHT=18 ALIGN=LEFT BGCOLOR="#E6E6FF">Sudafrica</TD>
			<TD ALIGN=LEFT BGCOLOR="#E6E6FF">Argentina</TD>
			<TD ALIGN=LEFT BGCOLOR="#E6E6FF">Inghilterra</TD>
			<TD ALIGN=LEFT BGCOLOR="#E6E6FF">Germania</TD>
		</TR>
		<TR>
			<TD HEIGHT=18 ALIGN=LEFT BGCOLOR="#E6E6FF">Messico</TD>
			<TD ALIGN=LEFT BGCOLOR="#E6E6FF">Nigeria</TD>
			<TD ALIGN=LEFT BGCOLOR="#E6E6FF">USA</TD>
			<TD ALIGN=LEFT BGCOLOR="#E6E6FF">Australia</TD>
		</TR>
		<TR>
			<TD HEIGHT=18 ALIGN=LEFT BGCOLOR="#E6E6FF">Uruguay</TD>
			<TD ALIGN=LEFT BGCOLOR="#E6E6FF">Corea del Sud</TD>
			<TD ALIGN=LEFT BGCOLOR="#E6E6FF">Algeria</TD>
			<TD ALIGN=LEFT BGCOLOR="#E6E6FF">Serbia</TD>
		</TR>
		<TR>
			<TD HEIGHT=18 ALIGN=LEFT BGCOLOR="#E6E6FF">Francia</TD>
			<TD ALIGN=LEFT BGCOLOR="#E6E6FF">Grecia</TD>
			<TD ALIGN=LEFT BGCOLOR="#E6E6FF">Slovenia</TD>
			<TD ALIGN=LEFT BGCOLOR="#E6E6FF">Ghana</TD>
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
			<TD HEIGHT=18 ALIGN=LEFT BGCOLOR="#E6E6FF">Olanda</TD>
			<TD ALIGN=LEFT BGCOLOR="#E6E6FF">Italia</TD>
			<TD ALIGN=LEFT BGCOLOR="#E6E6FF">Brasile</TD>
			<TD ALIGN=LEFT BGCOLOR="#E6E6FF">Spagna</TD>
		</TR>
		<TR>
			<TD HEIGHT=18 ALIGN=LEFT BGCOLOR="#E6E6FF">Danimarca</TD>
			<TD ALIGN=LEFT BGCOLOR="#E6E6FF">Paraguay</TD>
			<TD ALIGN=LEFT BGCOLOR="#E6E6FF">Corea del Nord</TD>
			<TD ALIGN=LEFT BGCOLOR="#E6E6FF">Svizzera</TD>
		</TR>
		<TR>
			<TD HEIGHT=18 ALIGN=LEFT BGCOLOR="#E6E6FF">Camerun</TD>
			<TD ALIGN=LEFT BGCOLOR="#E6E6FF">Nuova Zelanda</TD>
			<TD ALIGN=LEFT BGCOLOR="#E6E6FF">Costa D'Avorio</TD>
			<TD ALIGN=LEFT BGCOLOR="#E6E6FF">Honduras</TD>
		</TR>
		<TR>
			<TD HEIGHT=18 ALIGN=LEFT BGCOLOR="#E6E6FF">Giappone</TD>
			<TD ALIGN=LEFT BGCOLOR="#E6E6FF">Slovacchia </TD>
			<TD ALIGN=LEFT BGCOLOR="#E6E6FF">Portogallo</TD>
			<TD ALIGN=LEFT BGCOLOR="#E6E6FF">Cile</TD>
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
			<TD COLSPAN=4 HEIGHT=16 ALIGN=LEFT><I>tra queste 32 squadre saranno ammesse agli Ottavi le seguenti sedici (in ordine libero):</I></TD>
		</TR>
		<TR>
			<TD HEIGHT=21 ALIGN=LEFT BGCOLOR="#CCFFCC">

<!-- W1 -->
<select name="question_00" >
<option selected>&nbsp;</option>
<option>Sudafrica</option>
<option>Messico</option>
<option>Uruguay</option>
<option>Francia</option>
<option>Argentina</option>
<option>Nigeria</option>
<option>Corea del Sud</option>
<option>Grecia</option>
<option>Inghilterra</option>
<option>USA</option>
<option>Algeria</option>
<option>Slovenia</option>
<option>Germania</option>
<option>Australia</option>
<option>Serbia</option>
<option>Ghana</option>
<option>Olanda</option>
<option>Danimarca</option>
<option>Camerun</option>
<option>Giappone</option>
<option>Italia</option>
<option>Paraguay</option>
<option>Nuova Zelanda</option>
<option>Slovacchia</option>
<option>Brasile</option>
<option>Corea del Nord</option>
<option>Costa D'Avorio</option>
<option>Portogallo</option>
<option>Spagna</option>
<option>Svizzera</option>
<option>Honduras</option>
<option>Cile</option>
</select>

</TD>
			<TD ALIGN=LEFT BGCOLOR="#CCFFCC">

<!-- W2 -->
<select name="question_01" >
<option selected>&nbsp;</option>
<option>Sudafrica</option>
<option>Messico</option>
<option>Uruguay</option>
<option>Francia</option>
<option>Argentina</option>
<option>Nigeria</option>
<option>Corea del Sud</option>
<option>Grecia</option>
<option>Inghilterra</option>
<option>USA</option>
<option>Algeria</option>
<option>Slovenia</option>
<option>Germania</option>
<option>Australia</option>
<option>Serbia</option>
<option>Ghana</option>
<option>Olanda</option>
<option>Danimarca</option>
<option>Camerun</option>
<option>Giappone</option>
<option>Italia</option>
<option>Paraguay</option>
<option>Nuova Zelanda</option>
<option>Slovacchia</option>
<option>Brasile</option>
<option>Corea del Nord</option>
<option>Costa D'Avorio</option>
<option>Portogallo</option>
<option>Spagna</option>
<option>Svizzera</option>
<option>Honduras</option>
<option>Cile</option>
</select>


</TD>
			<TD ALIGN=LEFT BGCOLOR="#CCFFCC">

<!-- W3 -->
<select name="question_02" >
<option selected>&nbsp;</option>
<option>Sudafrica</option>
<option>Messico</option>
<option>Uruguay</option>
<option>Francia</option>
<option>Argentina</option>
<option>Nigeria</option>
<option>Corea del Sud</option>
<option>Grecia</option>
<option>Inghilterra</option>
<option>USA</option>
<option>Algeria</option>
<option>Slovenia</option>
<option>Germania</option>
<option>Australia</option>
<option>Serbia</option>
<option>Ghana</option>
<option>Olanda</option>
<option>Danimarca</option>
<option>Camerun</option>
<option>Giappone</option>
<option>Italia</option>
<option>Paraguay</option>
<option>Nuova Zelanda</option>
<option>Slovacchia</option>
<option>Brasile</option>
<option>Corea del Nord</option>
<option>Costa D'Avorio</option>
<option>Portogallo</option>
<option>Spagna</option>
<option>Svizzera</option>
<option>Honduras</option>
<option>Cile</option>
</select>


</TD>
			<TD ALIGN=LEFT BGCOLOR="#CCFFCC">


<!-- W4 -->
<select name="question_03" >
<option selected>&nbsp;</option>
<option>Sudafrica</option>
<option>Messico</option>
<option>Uruguay</option>
<option>Francia</option>
<option>Argentina</option>
<option>Nigeria</option>
<option>Corea del Sud</option>
<option>Grecia</option>
<option>Inghilterra</option>
<option>USA</option>
<option>Algeria</option>
<option>Slovenia</option>
<option>Germania</option>
<option>Australia</option>
<option>Serbia</option>
<option>Ghana</option>
<option>Olanda</option>
<option>Danimarca</option>
<option>Camerun</option>
<option>Giappone</option>
<option>Italia</option>
<option>Paraguay</option>
<option>Nuova Zelanda</option>
<option>Slovacchia</option>
<option>Brasile</option>
<option>Corea del Nord</option>
<option>Costa D'Avorio</option>
<option>Portogallo</option>
<option>Spagna</option>
<option>Svizzera</option>
<option>Honduras</option>
<option>Cile</option>
</select>


</TD>
		</TR>
		<TR>
			<TD HEIGHT=21 ALIGN=LEFT BGCOLOR="#CCFFCC">

<!-- W5 -->
<select name="question_04" >
<option selected>&nbsp;</option>
<option>Sudafrica</option>
<option>Messico</option>
<option>Uruguay</option>
<option>Francia</option>
<option>Argentina</option>
<option>Nigeria</option>
<option>Corea del Sud</option>
<option>Grecia</option>
<option>Inghilterra</option>
<option>USA</option>
<option>Algeria</option>
<option>Slovenia</option>
<option>Germania</option>
<option>Australia</option>
<option>Serbia</option>
<option>Ghana</option>
<option>Olanda</option>
<option>Danimarca</option>
<option>Camerun</option>
<option>Giappone</option>
<option>Italia</option>
<option>Paraguay</option>
<option>Nuova Zelanda</option>
<option>Slovacchia</option>
<option>Brasile</option>
<option>Corea del Nord</option>
<option>Costa D'Avorio</option>
<option>Portogallo</option>
<option>Spagna</option>
<option>Svizzera</option>
<option>Honduras</option>
<option>Cile</option>
</select>


</TD>
			<TD ALIGN=LEFT BGCOLOR="#CCFFCC">

<!-- W6 -->
<select name="question_05" >
<option selected>&nbsp;</option>
<option>Sudafrica</option>
<option>Messico</option>
<option>Uruguay</option>
<option>Francia</option>
<option>Argentina</option>
<option>Nigeria</option>
<option>Corea del Sud</option>
<option>Grecia</option>
<option>Inghilterra</option>
<option>USA</option>
<option>Algeria</option>
<option>Slovenia</option>
<option>Germania</option>
<option>Australia</option>
<option>Serbia</option>
<option>Ghana</option>
<option>Olanda</option>
<option>Danimarca</option>
<option>Camerun</option>
<option>Giappone</option>
<option>Italia</option>
<option>Paraguay</option>
<option>Nuova Zelanda</option>
<option>Slovacchia</option>
<option>Brasile</option>
<option>Corea del Nord</option>
<option>Costa D'Avorio</option>
<option>Portogallo</option>
<option>Spagna</option>
<option>Svizzera</option>
<option>Honduras</option>
<option>Cile</option>
</select>



</TD>
			<TD ALIGN=LEFT BGCOLOR="#CCFFCC">

<!-- W7 -->
<select name="question_06" >
<option selected>&nbsp;</option>
<option>Sudafrica</option>
<option>Messico</option>
<option>Uruguay</option>
<option>Francia</option>
<option>Argentina</option>
<option>Nigeria</option>
<option>Corea del Sud</option>
<option>Grecia</option>
<option>Inghilterra</option>
<option>USA</option>
<option>Algeria</option>
<option>Slovenia</option>
<option>Germania</option>
<option>Australia</option>
<option>Serbia</option>
<option>Ghana</option>
<option>Olanda</option>
<option>Danimarca</option>
<option>Camerun</option>
<option>Giappone</option>
<option>Italia</option>
<option>Paraguay</option>
<option>Nuova Zelanda</option>
<option>Slovacchia</option>
<option>Brasile</option>
<option>Corea del Nord</option>
<option>Costa D'Avorio</option>
<option>Portogallo</option>
<option>Spagna</option>
<option>Svizzera</option>
<option>Honduras</option>
<option>Cile</option>
</select>





</TD>
			<TD ALIGN=LEFT BGCOLOR="#CCFFCC">

<!-- W8 -->
<select name="question_07" >
<option selected>&nbsp;</option>
<option>Sudafrica</option>
<option>Messico</option>
<option>Uruguay</option>
<option>Francia</option>
<option>Argentina</option>
<option>Nigeria</option>
<option>Corea del Sud</option>
<option>Grecia</option>
<option>Inghilterra</option>
<option>USA</option>
<option>Algeria</option>
<option>Slovenia</option>
<option>Germania</option>
<option>Australia</option>
<option>Serbia</option>
<option>Ghana</option>
<option>Olanda</option>
<option>Danimarca</option>
<option>Camerun</option>
<option>Giappone</option>
<option>Italia</option>
<option>Paraguay</option>
<option>Nuova Zelanda</option>
<option>Slovacchia</option>
<option>Brasile</option>
<option>Corea del Nord</option>
<option>Costa D'Avorio</option>
<option>Portogallo</option>
<option>Spagna</option>
<option>Svizzera</option>
<option>Honduras</option>
<option>Cile</option>
</select>



</TD>
		</TR>


		<TR>
			<TD HEIGHT=21 ALIGN=LEFT BGCOLOR="#CCFFCC">

<!-- W9 -->
<select name="question_08" >
<option selected>&nbsp;</option>
<option>Sudafrica</option>
<option>Messico</option>
<option>Uruguay</option>
<option>Francia</option>
<option>Argentina</option>
<option>Nigeria</option>
<option>Corea del Sud</option>
<option>Grecia</option>
<option>Inghilterra</option>
<option>USA</option>
<option>Algeria</option>
<option>Slovenia</option>
<option>Germania</option>
<option>Australia</option>
<option>Serbia</option>
<option>Ghana</option>
<option>Olanda</option>
<option>Danimarca</option>
<option>Camerun</option>
<option>Giappone</option>
<option>Italia</option>
<option>Paraguay</option>
<option>Nuova Zelanda</option>
<option>Slovacchia</option>
<option>Brasile</option>
<option>Corea del Nord</option>
<option>Costa D'Avorio</option>
<option>Portogallo</option>
<option>Spagna</option>
<option>Svizzera</option>
<option>Honduras</option>
<option>Cile</option>
</select>



</TD>
			<TD ALIGN=LEFT BGCOLOR="#CCFFCC">

<!-- W10 -->
<select name="question_09" >
<option selected>&nbsp;</option>
<option>Sudafrica</option>
<option>Messico</option>
<option>Uruguay</option>
<option>Francia</option>
<option>Argentina</option>
<option>Nigeria</option>
<option>Corea del Sud</option>
<option>Grecia</option>
<option>Inghilterra</option>
<option>USA</option>
<option>Algeria</option>
<option>Slovenia</option>
<option>Germania</option>
<option>Australia</option>
<option>Serbia</option>
<option>Ghana</option>
<option>Olanda</option>
<option>Danimarca</option>
<option>Camerun</option>
<option>Giappone</option>
<option>Italia</option>
<option>Paraguay</option>
<option>Nuova Zelanda</option>
<option>Slovacchia</option>
<option>Brasile</option>
<option>Corea del Nord</option>
<option>Costa D'Avorio</option>
<option>Portogallo</option>
<option>Spagna</option>
<option>Svizzera</option>
<option>Honduras</option>
<option>Cile</option>
</select>


</TD>
			<TD ALIGN=LEFT BGCOLOR="#CCFFCC">

<!-- W11 -->
<select name="question_10" >
<option selected>&nbsp;</option>
<option>Sudafrica</option>
<option>Messico</option>
<option>Uruguay</option>
<option>Francia</option>
<option>Argentina</option>
<option>Nigeria</option>
<option>Corea del Sud</option>
<option>Grecia</option>
<option>Inghilterra</option>
<option>USA</option>
<option>Algeria</option>
<option>Slovenia</option>
<option>Germania</option>
<option>Australia</option>
<option>Serbia</option>
<option>Ghana</option>
<option>Olanda</option>
<option>Danimarca</option>
<option>Camerun</option>
<option>Giappone</option>
<option>Italia</option>
<option>Paraguay</option>
<option>Nuova Zelanda</option>
<option>Slovacchia</option>
<option>Brasile</option>
<option>Corea del Nord</option>
<option>Costa D'Avorio</option>
<option>Portogallo</option>
<option>Spagna</option>
<option>Svizzera</option>
<option>Honduras</option>
<option>Cile</option>
</select>


</TD>
			<TD ALIGN=LEFT BGCOLOR="#CCFFCC">


<!-- W12 -->
<select name="question_11" >
<option selected>&nbsp;</option>
<option>Sudafrica</option>
<option>Messico</option>
<option>Uruguay</option>
<option>Francia</option>
<option>Argentina</option>
<option>Nigeria</option>
<option>Corea del Sud</option>
<option>Grecia</option>
<option>Inghilterra</option>
<option>USA</option>
<option>Algeria</option>
<option>Slovenia</option>
<option>Germania</option>
<option>Australia</option>
<option>Serbia</option>
<option>Ghana</option>
<option>Olanda</option>
<option>Danimarca</option>
<option>Camerun</option>
<option>Giappone</option>
<option>Italia</option>
<option>Paraguay</option>
<option>Nuova Zelanda</option>
<option>Slovacchia</option>
<option>Brasile</option>
<option>Corea del Nord</option>
<option>Costa D'Avorio</option>
<option>Portogallo</option>
<option>Spagna</option>
<option>Svizzera</option>
<option>Honduras</option>
<option>Cile</option>
</select>


</TD>
		</TR>
		<TR>
			<TD HEIGHT=21 ALIGN=LEFT BGCOLOR="#CCFFCC">

<!-- W13 -->
<select name="question_12" >
<option selected>&nbsp;</option>
<option>Sudafrica</option>
<option>Messico</option>
<option>Uruguay</option>
<option>Francia</option>
<option>Argentina</option>
<option>Nigeria</option>
<option>Corea del Sud</option>
<option>Grecia</option>
<option>Inghilterra</option>
<option>USA</option>
<option>Algeria</option>
<option>Slovenia</option>
<option>Germania</option>
<option>Australia</option>
<option>Serbia</option>
<option>Ghana</option>
<option>Olanda</option>
<option>Danimarca</option>
<option>Camerun</option>
<option>Giappone</option>
<option>Italia</option>
<option>Paraguay</option>
<option>Nuova Zelanda</option>
<option>Slovacchia</option>
<option>Brasile</option>
<option>Corea del Nord</option>
<option>Costa D'Avorio</option>
<option>Portogallo</option>
<option>Spagna</option>
<option>Svizzera</option>
<option>Honduras</option>
<option>Cile</option>
</select>


</TD>
			<TD ALIGN=LEFT BGCOLOR="#CCFFCC">

<!-- W14 -->
<select name="question_13" >
<option selected>&nbsp;</option>
<option>Sudafrica</option>
<option>Messico</option>
<option>Uruguay</option>
<option>Francia</option>
<option>Argentina</option>
<option>Nigeria</option>
<option>Corea del Sud</option>
<option>Grecia</option>
<option>Inghilterra</option>
<option>USA</option>
<option>Algeria</option>
<option>Slovenia</option>
<option>Germania</option>
<option>Australia</option>
<option>Serbia</option>
<option>Ghana</option>
<option>Olanda</option>
<option>Danimarca</option>
<option>Camerun</option>
<option>Giappone</option>
<option>Italia</option>
<option>Paraguay</option>
<option>Nuova Zelanda</option>
<option>Slovacchia</option>
<option>Brasile</option>
<option>Corea del Nord</option>
<option>Costa D'Avorio</option>
<option>Portogallo</option>
<option>Spagna</option>
<option>Svizzera</option>
<option>Honduras</option>
<option>Cile</option>
</select>



</TD>
			<TD ALIGN=LEFT BGCOLOR="#CCFFCC">

<!-- W15 -->
<select name="question_14" >
<option selected>&nbsp;</option>
<option>Sudafrica</option>
<option>Messico</option>
<option>Uruguay</option>
<option>Francia</option>
<option>Argentina</option>
<option>Nigeria</option>
<option>Corea del Sud</option>
<option>Grecia</option>
<option>Inghilterra</option>
<option>USA</option>
<option>Algeria</option>
<option>Slovenia</option>
<option>Germania</option>
<option>Australia</option>
<option>Serbia</option>
<option>Ghana</option>
<option>Olanda</option>
<option>Danimarca</option>
<option>Camerun</option>
<option>Giappone</option>
<option>Italia</option>
<option>Paraguay</option>
<option>Nuova Zelanda</option>
<option>Slovacchia</option>
<option>Brasile</option>
<option>Corea del Nord</option>
<option>Costa D'Avorio</option>
<option>Portogallo</option>
<option>Spagna</option>
<option>Svizzera</option>
<option>Honduras</option>
<option>Cile</option>
</select>





</TD>
			<TD ALIGN=LEFT BGCOLOR="#CCFFCC">

<!-- W16 -->
<select name="question_15" >
<option selected>&nbsp;</option>
<option>Sudafrica</option>
<option>Messico</option>
<option>Uruguay</option>
<option>Francia</option>
<option>Argentina</option>
<option>Nigeria</option>
<option>Corea del Sud</option>
<option>Grecia</option>
<option>Inghilterra</option>
<option>USA</option>
<option>Algeria</option>
<option>Slovenia</option>
<option>Germania</option>
<option>Australia</option>
<option>Serbia</option>
<option>Ghana</option>
<option>Olanda</option>
<option>Danimarca</option>
<option>Camerun</option>
<option>Giappone</option>
<option>Italia</option>
<option>Paraguay</option>
<option>Nuova Zelanda</option>
<option>Slovacchia</option>
<option>Brasile</option>
<option>Corea del Nord</option>
<option>Costa D'Avorio</option>
<option>Portogallo</option>
<option>Spagna</option>
<option>Svizzera</option>
<option>Honduras</option>
<option>Cile</option>
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
			<TD COLSPAN=4 HEIGHT=16 ALIGN=LEFT><I>tra queste 16 squadre saranno ammesse ai Quarti le seguenti otto (in ordine libero):</I></TD>
		</TR>
		<TR>
			<TD HEIGHT=21 ALIGN=LEFT BGCOLOR="#CCFFCC">

<!-- Q1 -->
<select name="question_16" >
<option selected>&nbsp;</option>
<option>Sudafrica</option>
<option>Messico</option>
<option>Uruguay</option>
<option>Francia</option>
<option>Argentina</option>
<option>Nigeria</option>
<option>Corea del Sud</option>
<option>Grecia</option>
<option>Inghilterra</option>
<option>USA</option>
<option>Algeria</option>
<option>Slovenia</option>
<option>Germania</option>
<option>Australia</option>
<option>Serbia</option>
<option>Ghana</option>
<option>Olanda</option>
<option>Danimarca</option>
<option>Camerun</option>
<option>Giappone</option>
<option>Italia</option>
<option>Paraguay</option>
<option>Nuova Zelanda</option>
<option>Slovacchia</option>
<option>Brasile</option>
<option>Corea del Nord</option>
<option>Costa D'Avorio</option>
<option>Portogallo</option>
<option>Spagna</option>
<option>Svizzera</option>
<option>Honduras</option>
<option>Cile</option>
</select>



</TD>
			<TD ALIGN=LEFT BGCOLOR="#CCFFCC">

<!-- Q2 -->
<select name="question_17" >
<option selected>&nbsp;</option>
<option>Sudafrica</option>
<option>Messico</option>
<option>Uruguay</option>
<option>Francia</option>
<option>Argentina</option>
<option>Nigeria</option>
<option>Corea del Sud</option>
<option>Grecia</option>
<option>Inghilterra</option>
<option>USA</option>
<option>Algeria</option>
<option>Slovenia</option>
<option>Germania</option>
<option>Australia</option>
<option>Serbia</option>
<option>Ghana</option>
<option>Olanda</option>
<option>Danimarca</option>
<option>Camerun</option>
<option>Giappone</option>
<option>Italia</option>
<option>Paraguay</option>
<option>Nuova Zelanda</option>
<option>Slovacchia</option>
<option>Brasile</option>
<option>Corea del Nord</option>
<option>Costa D'Avorio</option>
<option>Portogallo</option>
<option>Spagna</option>
<option>Svizzera</option>
<option>Honduras</option>
<option>Cile</option>
</select>


</TD>
			<TD ALIGN=LEFT BGCOLOR="#CCFFCC">

<!-- Q3 -->
<select name="question_18" >
<option selected>&nbsp;</option>
<option>Sudafrica</option>
<option>Messico</option>
<option>Uruguay</option>
<option>Francia</option>
<option>Argentina</option>
<option>Nigeria</option>
<option>Corea del Sud</option>
<option>Grecia</option>
<option>Inghilterra</option>
<option>USA</option>
<option>Algeria</option>
<option>Slovenia</option>
<option>Germania</option>
<option>Australia</option>
<option>Serbia</option>
<option>Ghana</option>
<option>Olanda</option>
<option>Danimarca</option>
<option>Camerun</option>
<option>Giappone</option>
<option>Italia</option>
<option>Paraguay</option>
<option>Nuova Zelanda</option>
<option>Slovacchia</option>
<option>Brasile</option>
<option>Corea del Nord</option>
<option>Costa D'Avorio</option>
<option>Portogallo</option>
<option>Spagna</option>
<option>Svizzera</option>
<option>Honduras</option>
<option>Cile</option>
</select>


</TD>
			<TD ALIGN=LEFT BGCOLOR="#CCFFCC">


<!-- Q4 -->
<select name="question_19" >
<option selected>&nbsp;</option>
<option>Sudafrica</option>
<option>Messico</option>
<option>Uruguay</option>
<option>Francia</option>
<option>Argentina</option>
<option>Nigeria</option>
<option>Corea del Sud</option>
<option>Grecia</option>
<option>Inghilterra</option>
<option>USA</option>
<option>Algeria</option>
<option>Slovenia</option>
<option>Germania</option>
<option>Australia</option>
<option>Serbia</option>
<option>Ghana</option>
<option>Olanda</option>
<option>Danimarca</option>
<option>Camerun</option>
<option>Giappone</option>
<option>Italia</option>
<option>Paraguay</option>
<option>Nuova Zelanda</option>
<option>Slovacchia</option>
<option>Brasile</option>
<option>Corea del Nord</option>
<option>Costa D'Avorio</option>
<option>Portogallo</option>
<option>Spagna</option>
<option>Svizzera</option>
<option>Honduras</option>
<option>Cile</option>
</select>


</TD>
		</TR>
		<TR>
			<TD HEIGHT=21 ALIGN=LEFT BGCOLOR="#CCFFCC">

<!-- Q5 -->
<select name="question_20" >
<option selected>&nbsp;</option>
<option>Sudafrica</option>
<option>Messico</option>
<option>Uruguay</option>
<option>Francia</option>
<option>Argentina</option>
<option>Nigeria</option>
<option>Corea del Sud</option>
<option>Grecia</option>
<option>Inghilterra</option>
<option>USA</option>
<option>Algeria</option>
<option>Slovenia</option>
<option>Germania</option>
<option>Australia</option>
<option>Serbia</option>
<option>Ghana</option>
<option>Olanda</option>
<option>Danimarca</option>
<option>Camerun</option>
<option>Giappone</option>
<option>Italia</option>
<option>Paraguay</option>
<option>Nuova Zelanda</option>
<option>Slovacchia</option>
<option>Brasile</option>
<option>Corea del Nord</option>
<option>Costa D'Avorio</option>
<option>Portogallo</option>
<option>Spagna</option>
<option>Svizzera</option>
<option>Honduras</option>
<option>Cile</option>
</select>


</TD>
			<TD ALIGN=LEFT BGCOLOR="#CCFFCC">

<!-- Q6 -->
<select name="question_21" >
<option selected>&nbsp;</option>
<option>Sudafrica</option>
<option>Messico</option>
<option>Uruguay</option>
<option>Francia</option>
<option>Argentina</option>
<option>Nigeria</option>
<option>Corea del Sud</option>
<option>Grecia</option>
<option>Inghilterra</option>
<option>USA</option>
<option>Algeria</option>
<option>Slovenia</option>
<option>Germania</option>
<option>Australia</option>
<option>Serbia</option>
<option>Ghana</option>
<option>Olanda</option>
<option>Danimarca</option>
<option>Camerun</option>
<option>Giappone</option>
<option>Italia</option>
<option>Paraguay</option>
<option>Nuova Zelanda</option>
<option>Slovacchia</option>
<option>Brasile</option>
<option>Corea del Nord</option>
<option>Costa D'Avorio</option>
<option>Portogallo</option>
<option>Spagna</option>
<option>Svizzera</option>
<option>Honduras</option>
<option>Cile</option>
</select>



</TD>
			<TD ALIGN=LEFT BGCOLOR="#CCFFCC">

<!-- Q7 -->
<select name="question_22" >
<option selected>&nbsp;</option>
<option>Sudafrica</option>
<option>Messico</option>
<option>Uruguay</option>
<option>Francia</option>
<option>Argentina</option>
<option>Nigeria</option>
<option>Corea del Sud</option>
<option>Grecia</option>
<option>Inghilterra</option>
<option>USA</option>
<option>Algeria</option>
<option>Slovenia</option>
<option>Germania</option>
<option>Australia</option>
<option>Serbia</option>
<option>Ghana</option>
<option>Olanda</option>
<option>Danimarca</option>
<option>Camerun</option>
<option>Giappone</option>
<option>Italia</option>
<option>Paraguay</option>
<option>Nuova Zelanda</option>
<option>Slovacchia</option>
<option>Brasile</option>
<option>Corea del Nord</option>
<option>Costa D'Avorio</option>
<option>Portogallo</option>
<option>Spagna</option>
<option>Svizzera</option>
<option>Honduras</option>
<option>Cile</option>
</select>





</TD>
			<TD ALIGN=LEFT BGCOLOR="#CCFFCC">

<!-- Q8 -->
<select name="question_23" >
<option selected>&nbsp;</option>
<option>Sudafrica</option>
<option>Messico</option>
<option>Uruguay</option>
<option>Francia</option>
<option>Argentina</option>
<option>Nigeria</option>
<option>Corea del Sud</option>
<option>Grecia</option>
<option>Inghilterra</option>
<option>USA</option>
<option>Algeria</option>
<option>Slovenia</option>
<option>Germania</option>
<option>Australia</option>
<option>Serbia</option>
<option>Ghana</option>
<option>Olanda</option>
<option>Danimarca</option>
<option>Camerun</option>
<option>Giappone</option>
<option>Italia</option>
<option>Paraguay</option>
<option>Nuova Zelanda</option>
<option>Slovacchia</option>
<option>Brasile</option>
<option>Corea del Nord</option>
<option>Costa D'Avorio</option>
<option>Portogallo</option>
<option>Spagna</option>
<option>Svizzera</option>
<option>Honduras</option>
<option>Cile</option>
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
<select name="question_24" >
<option selected>&nbsp;</option>
<option>Sudafrica</option>
<option>Messico</option>
<option>Uruguay</option>
<option>Francia</option>
<option>Argentina</option>
<option>Nigeria</option>
<option>Corea del Sud</option>
<option>Grecia</option>
<option>Inghilterra</option>
<option>USA</option>
<option>Algeria</option>
<option>Slovenia</option>
<option>Germania</option>
<option>Australia</option>
<option>Serbia</option>
<option>Ghana</option>
<option>Olanda</option>
<option>Danimarca</option>
<option>Camerun</option>
<option>Giappone</option>
<option>Italia</option>
<option>Paraguay</option>
<option>Nuova Zelanda</option>
<option>Slovacchia</option>
<option>Brasile</option>
<option>Corea del Nord</option>
<option>Costa D'Avorio</option>
<option>Portogallo</option>
<option>Spagna</option>
<option>Svizzera</option>
<option>Honduras</option>
<option>Cile</option>
</select>


</TD>
			<TD ALIGN=LEFT BGCOLOR="#FFFF99">


<!-- S2 -->
<select name="question_25" >
<option selected>&nbsp;</option>
<option>Sudafrica</option>
<option>Messico</option>
<option>Uruguay</option>
<option>Francia</option>
<option>Argentina</option>
<option>Nigeria</option>
<option>Corea del Sud</option>
<option>Grecia</option>
<option>Inghilterra</option>
<option>USA</option>
<option>Algeria</option>
<option>Slovenia</option>
<option>Germania</option>
<option>Australia</option>
<option>Serbia</option>
<option>Ghana</option>
<option>Olanda</option>
<option>Danimarca</option>
<option>Camerun</option>
<option>Giappone</option>
<option>Italia</option>
<option>Paraguay</option>
<option>Nuova Zelanda</option>
<option>Slovacchia</option>
<option>Brasile</option>
<option>Corea del Nord</option>
<option>Costa D'Avorio</option>
<option>Portogallo</option>
<option>Spagna</option>
<option>Svizzera</option>
<option>Honduras</option>
<option>Cile</option>
</select>


</TD>
			<TD ALIGN=LEFT BGCOLOR="#FFFF99">

<!-- S3 -->
<select name="question_26" >
<option selected>&nbsp;</option>
<option>Sudafrica</option>
<option>Messico</option>
<option>Uruguay</option>
<option>Francia</option>
<option>Argentina</option>
<option>Nigeria</option>
<option>Corea del Sud</option>
<option>Grecia</option>
<option>Inghilterra</option>
<option>USA</option>
<option>Algeria</option>
<option>Slovenia</option>
<option>Germania</option>
<option>Australia</option>
<option>Serbia</option>
<option>Ghana</option>
<option>Olanda</option>
<option>Danimarca</option>
<option>Camerun</option>
<option>Giappone</option>
<option>Italia</option>
<option>Paraguay</option>
<option>Nuova Zelanda</option>
<option>Slovacchia</option>
<option>Brasile</option>
<option>Corea del Nord</option>
<option>Costa D'Avorio</option>
<option>Portogallo</option>
<option>Spagna</option>
<option>Svizzera</option>
<option>Honduras</option>
<option>Cile</option>
</select>


</TD>
			<TD ALIGN=LEFT BGCOLOR="#FFFF99">

<!-- S4 -->
<select name="question_27" >
<option selected>&nbsp;</option>
<option>Sudafrica</option>
<option>Messico</option>
<option>Uruguay</option>
<option>Francia</option>
<option>Argentina</option>
<option>Nigeria</option>
<option>Corea del Sud</option>
<option>Grecia</option>
<option>Inghilterra</option>
<option>USA</option>
<option>Algeria</option>
<option>Slovenia</option>
<option>Germania</option>
<option>Australia</option>
<option>Serbia</option>
<option>Ghana</option>
<option>Olanda</option>
<option>Danimarca</option>
<option>Camerun</option>
<option>Giappone</option>
<option>Italia</option>
<option>Paraguay</option>
<option>Nuova Zelanda</option>
<option>Slovacchia</option>
<option>Brasile</option>
<option>Corea del Nord</option>
<option>Costa D'Avorio</option>
<option>Portogallo</option>
<option>Spagna</option>
<option>Svizzera</option>
<option>Honduras</option>
<option>Cile</option>
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
			<TD COLSPAN=1 ALIGN=CENTER>Campione del mondo:</TD>
		</TR>
		<TR>
			<TD HEIGHT=25 ALIGN=CENTER BGCOLOR="#C0C0C0">

<!-- F1 -->
<select name="question_28" >
<option selected>&nbsp;</option>
<option>Sudafrica</option>
<option>Messico</option>
<option>Uruguay</option>
<option>Francia</option>
<option>Argentina</option>
<option>Nigeria</option>
<option>Corea del Sud</option>
<option>Grecia</option>
<option>Inghilterra</option>
<option>USA</option>
<option>Algeria</option>
<option>Slovenia</option>
<option>Germania</option>
<option>Australia</option>
<option>Serbia</option>
<option>Ghana</option>
<option>Olanda</option>
<option>Danimarca</option>
<option>Camerun</option>
<option>Giappone</option>
<option>Italia</option>
<option>Paraguay</option>
<option>Nuova Zelanda</option>
<option>Slovacchia</option>
<option>Brasile</option>
<option>Corea del Nord</option>
<option>Costa D'Avorio</option>
<option>Portogallo</option>
<option>Spagna</option>
<option>Svizzera</option>
<option>Honduras</option>
<option>Cile</option>
</select>


</TD>
			<TD ALIGN=CENTER BGCOLOR="#C0C0C0">

<!-- F2 -->
<select name="question_29" >
<option selected>&nbsp;</option>
<option>Sudafrica</option>
<option>Messico</option>
<option>Uruguay</option>
<option>Francia</option>
<option>Argentina</option>
<option>Nigeria</option>
<option>Corea del Sud</option>
<option>Grecia</option>
<option>Inghilterra</option>
<option>USA</option>
<option>Algeria</option>
<option>Slovenia</option>
<option>Germania</option>
<option>Australia</option>
<option>Serbia</option>
<option>Ghana</option>
<option>Olanda</option>
<option>Danimarca</option>
<option>Camerun</option>
<option>Giappone</option>
<option>Italia</option>
<option>Paraguay</option>
<option>Nuova Zelanda</option>
<option>Slovacchia</option>
<option>Brasile</option>
<option>Corea del Nord</option>
<option>Costa D'Avorio</option>
<option>Portogallo</option>
<option>Spagna</option>
<option>Svizzera</option>
<option>Honduras</option>
<option>Cile</option>
</select>



</TD>
			<TD ALIGN=CENTER><BR></TD>
			<TD COLSPAN=1 ALIGN=LEFT BGCOLOR="#FFFF00">

<!-- C -->
<select name="question_30" >
<option selected>&nbsp;</option>
<option>Sudafrica</option>
<option>Messico</option>
<option>Uruguay</option>
<option>Francia</option>
<option>Argentina</option>
<option>Nigeria</option>
<option>Corea del Sud</option>
<option>Grecia</option>
<option>Inghilterra</option>
<option>USA</option>
<option>Algeria</option>
<option>Slovenia</option>
<option>Germania</option>
<option>Australia</option>
<option>Serbia</option>
<option>Ghana</option>
<option>Olanda</option>
<option>Danimarca</option>
<option>Camerun</option>
<option>Giappone</option>
<option>Italia</option>
<option>Paraguay</option>
<option>Nuova Zelanda</option>
<option>Slovacchia</option>
<option>Brasile</option>
<option>Corea del Nord</option>
<option>Costa D'Avorio</option>
<option>Portogallo</option>
<option>Spagna</option>
<option>Svizzera</option>
<option>Honduras</option>
<option>Cile</option>
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
<!-- 	<COLGROUP><COL WIDTH=80><COL WIDTH=95><COL WIDTH=110></COLGROUP> -->
	<TBODY>
		<TR>
			<TD ALIGN=LEFT><BR></TD>
			<TD ALIGN=LEFT><BR></TD>
			<TD ALIGN=LEFT><BR></TD>
		</TR>
		<TR>
			<TD COLSPAN=3 ALIGN=CENTER BGCOLOR="#CCFFFF"><B>Gironi eliminatori (11 - 25 giugno 2010)</B></TD>
		</TR>
		<TR>
			<TD COLSPAN=3 ALIGN=LEFT BGCOLOR="#CCFFFF">La prima fase si articolera' in 48 partite (6 per ciascun girone) in 15 giorni:</TD>
		</TR>
		<TR>
			<TD COLSPAN=3 ALIGN=LEFT BGCOLOR="#CCFFFF">si giochera' alle ore 13.30, 16.00, e 20.30</TD>
		</TR>
		<TR>
			<TD ALIGN=LEFT><BR></TD>
			<TD ALIGN=LEFT><BR></TD>
			<TD ALIGN=LEFT><BR></TD>
		</TR>
		<TR>
			<TD COLSPAN=3 ALIGN=CENTER BGCOLOR="#FFDDDD"><B>Ottavi di Finale (26 - 29 giugno 2010)</B></TD>
		</TR>
		<TR>
			<TD ALIGN=LEFT BGCOLOR="#FFDDDD">26/06 h. 16:00</TD>
			<TD ALIGN=CENTER BGCOLOR="#FFDDDD">Port Elizabeth</TD>
			<TD ALIGN=CENTER BGCOLOR="#FFDDDD">A1-B2 (Q1)</TD>
		</TR>
		<TR>
			<TD ALIGN=LEFT BGCOLOR="#FFDDDD">26/06 h. 20:30</TD>
			<TD ALIGN=CENTER BGCOLOR="#FFDDDD">Rustenburg</TD>
			<TD ALIGN=CENTER BGCOLOR="#FFDDDD">C1-D2 (Q2)</TD>
		</TR>
		<TR>
			<TD ALIGN=LEFT BGCOLOR="#FFDDDD">27/06 h. 16:00</TD>
			<TD ALIGN=CENTER BGCOLOR="#FFDDDD">Bloemfontein</TD>
			<TD ALIGN=CENTER BGCOLOR="#FFDDDD">D1-C2 (Q3)</TD>
		</TR>
		<TR>
			<TD ALIGN=LEFT BGCOLOR="#FFDDDD">27/06 h. 20:30</TD>
			<TD ALIGN=CENTER BGCOLOR="#FFDDDD">Johannesburg</TD>
			<TD ALIGN=CENTER BGCOLOR="#FFDDDD">B1-A2 (Q4)</TD>
		</TR>
		<TR>
			<TD ALIGN=LEFT BGCOLOR="#FFDDDD">28/06 h. 16:00</TD>
			<TD ALIGN=CENTER BGCOLOR="#FFDDDD">Durban</TD>
			<TD ALIGN=CENTER BGCOLOR="#FFDDDD">E1-F2 (Q5)</TD>
		</TR>
		<TR>
			<TD ALIGN=LEFT BGCOLOR="#FFDDDD">28/06 h. 20:30</TD>
			<TD ALIGN=CENTER BGCOLOR="#FFDDDD">Johannesburg</TD>
			<TD ALIGN=CENTER BGCOLOR="#FFDDDD">G1-H2 (Q6)</TD>
		</TR>
		<TR>
			<TD ALIGN=LEFT BGCOLOR="#FFDDDD">29/06 h. 16:00</TD>
			<TD ALIGN=CENTER BGCOLOR="#FFDDDD">Pretoria</TD>
			<TD ALIGN=CENTER BGCOLOR="#FFDDDD">F1-E2 (Q7)</TD>
		</TR>
		<TR>
			<TD ALIGN=LEFT BGCOLOR="#FFDDDD">29/06 h. 20:30</TD>
			<TD ALIGN=CENTER BGCOLOR="#FFDDDD">Citta' del Capo</TD>
			<TD ALIGN=CENTER BGCOLOR="#FFDDDD">H1-G2 (Q8)</TD>
		</TR>
		<TR>
			<TD ALIGN=LEFT><BR></TD>
			<TD ALIGN=LEFT><BR></TD>
			<TD ALIGN=LEFT><BR></TD>
		</TR>
		<TR>
			<TD COLSPAN=3 ALIGN=CENTER BGCOLOR="#CCFFCC"><B>Quarti di Finale (2 - 3 luglio 2010)</B></TD>
		</TR>
		<TR>
			<TD ALIGN=LEFT BGCOLOR="#CCFFCC">02/07 h. 16:00</TD>
			<TD ALIGN=CENTER BGCOLOR="#CCFFCC">Port Elizabeth</TD>
			<TD ALIGN=CENTER BGCOLOR="#CCFFCC">Q5-Q6 (S1)</TD>
		</TR>
		<TR>
			<TD ALIGN=LEFT BGCOLOR="#CCFFCC">02/07 h. 20:30</TD>
			<TD ALIGN=CENTER BGCOLOR="#CCFFCC">Johannesburg</TD>
			<TD ALIGN=CENTER BGCOLOR="#CCFFCC">Q1-Q2 (S2)</TD>
		</TR>
		<TR>
			<TD ALIGN=LEFT BGCOLOR="#CCFFCC">03/07 h. 16:00</TD>
			<TD ALIGN=CENTER BGCOLOR="#CCFFCC">Citta' del Capo</TD>
			<TD ALIGN=CENTER BGCOLOR="#CCFFCC">Q4-Q3 (S3)</TD>
		</TR>
		<TR>
			<TD ALIGN=LEFT BGCOLOR="#CCFFCC">03/07 h. 20:30</TD>
			<TD ALIGN=CENTER BGCOLOR="#CCFFCC">Johannesburg</TD>
			<TD ALIGN=CENTER BGCOLOR="#CCFFCC">Q7-Q8 (S4)</TD>
		</TR>
		<TR>
			<TD ALIGN=LEFT><BR></TD>
			<TD ALIGN=LEFT><BR></TD>
			<TD ALIGN=LEFT><BR></TD>
		</TR>
		<TR>
			<TD COLSPAN=3 ALIGN=CENTER BGCOLOR="#FFFF99"><B>Semifinali (6 - 7 luglio 2010)</B></TD>
		</TR>
		<TR>
			<TD ALIGN=LEFT BGCOLOR="#FFFF99">06/07 h. 20:30</TD>
			<TD ALIGN=CENTER BGCOLOR="#FFFF99">Citta' del Capo</TD>
			<TD ALIGN=CENTER BGCOLOR="#FFFF99">S2-S1 (F1)</TD>
		</TR>
		<TR>
			<TD ALIGN=LEFT BGCOLOR="#FFFF99">07/07 h. 20:30</TD>
			<TD ALIGN=CENTER BGCOLOR="#FFFF99">Durban</TD>
			<TD ALIGN=CENTER BGCOLOR="#FFFF99">S3-S4 (F2)</TD>
		</TR>
		<TR>
			<TD ALIGN=LEFT><BR></TD>
			<TD ALIGN=LEFT><BR></TD>
			<TD ALIGN=LEFT><BR></TD>
		</TR>

		<TR>
			<TD COLSPAN=3 ALIGN=CENTER BGCOLOR="#C0C0C0"><B>Finale 1&deg;-2&deg;posto</B></TD>
		</TR>
		<TR>
			<TD ALIGN=LEFT BGCOLOR="#C0C0C0">11/07 h. 20:30</TD>
			<TD ALIGN=CENTER BGCOLOR="#C0C0C0">Johannesburg</TD>
			<TD ALIGN=CENTER BGCOLOR="#C0C0C0">F1-F2 (C)</TD>
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
