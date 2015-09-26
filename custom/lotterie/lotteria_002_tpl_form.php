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
<meta name="keywords" content="lotteria, questionario, Champions League 06 07, sondaggio, classifica">
<style type="text/css">@import "<?php echo $filename_css ?>";</style>


<!--
// 
// 2) Inserire qui sotto gli stili della pagina html customizzata
//
-->
<style type="text/css">

.font7
{
	color:windowtext;
	font-size:7.5pt;
	font-weight:400;
	font-style:normal;
	text-decoration:none;
	font-family:Arial;
}

.font8
{
	color:windowtext;
	font-size:11.0pt;
	font-weight:400;
	font-style:normal;
	text-decoration:none;
	font-family:"Arial Narrow", sans-serif;
}

.font9
{
	color:windowtext;
	font-size:11.0pt;
	font-weight:700;
	font-style:normal;
	text-decoration:none;
	font-family:"Arial Narrow", sans-serif;
}

.font14
{
	color:windowtext;
	font-size:7.5pt;
	font-weight:700;
	font-style:normal;
	text-decoration:none;
	font-family:Arial, sans-serif;
}

.font20
{
	color:windowtext;
	font-size:7.5pt;
	font-weight:400;
	font-style:italic;
	text-decoration:none;
	font-family:Arial, sans-serif;
}

td
{
	padding-top:1px;
	padding-right:1px;
	padding-left:1px;
	color:windowtext;
	font-size:10.0pt;
	font-weight:400;
	font-style:normal;
	text-decoration:none;
	font-family:Arial;
	text-align:general;
	vertical-align:bottom;
	border:none;
	white-space:nowrap;
}

.xl25 /* titolo scheda */
{
	font-size:14.0pt;
	text-align:center;
}

.xl26 /* corpo regolamento */
{
	font-size:7.5pt;
}

.xl27 /* titolo per le 8,4,2 squadre ammesse */
{
	font-size:11.0pt;
	font-family:"Arial Narrow", sans-serif;
}

.xl34 /* caption campi edit */
{
	font-size:8.0pt;
	text-align:left;
}

.xl35 /* campo inserimento edit */
{
	font-size:14.0pt;
	border-top:none;
	border-right:none;
	border-bottom:.5pt dashed windowtext;
	border-left:none;
}

.xl36 /* titolo per le 16 squadre ammesse ottavi */
{
	font-size:11.0pt;
	font-family:"Arial Narrow", sans-serif;
	text-align:center;
}

.x137 /* proprieta' campi edit */
{
	width:90pt;
}

.xl39 /* campo inserimento select */
{
	border-top:.5pt solid windowtext;
	border-right:.5pt solid windowtext;
	border-bottom:.5pt solid windowtext;
	border-left:.5pt solid windowtext;
	background: #ffffff;
}

.xl47 /* corpo regolamento */
{
	font-size:7.5pt;
}

.xl48 /* inizio/fine giocate */
{
	font-size:9.0pt;
	font-weight:700;
	font-family:Arial, sans-serif;
}

.xl49 /* titolo regolamento */
{
	font-size:8.0pt;
	font-weight:700;
	font-family:Arial, sans-serif;
}

.xl50 /* squadra #X1 */
{
	border-top:.5pt solid windowtext;
	border-right:.5pt solid windowtext;
	border-bottom:.5pt dotted windowtext;
	text-align:center;
	font-size:9.0pt;
	border-left:.5pt solid windowtext;
	background:#CCFFCC;
}

.xl51 /* squadra #X2 */
{
	border-top:.5pt hairline windowtext;
	border-right:.5pt solid windowtext;
	border-bottom:.5pt solid windowtext;
	border-left:.5pt solid windowtext;
	text-align:center;
	font-size:9.0pt;
	background:#CCFFCC;
}

.xl57 /* ottavi, quarti, semifinali */
{
	font-size:8.0pt;
	font-weight:700;
	text-align:center;
	border-top:.5pt dotted windowtext;
	border-right:.5pt dotted windowtext;
	border-bottom:none;
	border-left:.5pt dotted windowtext;
}

.xl58 /* ritorno */
{
	font-size:8.0pt;
	text-align:left;
	border-top:none;
	border-right:.5pt dotted windowtext;
	border-bottom:.5pt dotted windowtext;
	border-left:.5pt dotted windowtext;
}

.xl59 /* andata */
{
	font-size:8.0pt;
	text-align:left;
	border-top:none;
	border-right:.5pt dotted windowtext;
	border-bottom:none;
	border-left:.5pt dotted windowtext;
}

.xl60 /* finale */
{
	font-size:8.0pt;
	font-weight:700;
	text-align:center;
	border:.5pt dotted windowtext;
}

.xl61 /* nota a pie' di pagine */
{
	font-size:7.0pt;
	font-style:italic;
	font-family:Arial, sans-serif;
}

.xl66 /* campione */
{
	font-weight:700;
	font-family:Arial, sans-serif;
	text-align:center;
	border-top:none;
	border-right:none;
	border-bottom:.5pt solid windowtext;
	border-left:none;
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
<body link=blue vlink=purple onLoad="document.forms['question_form']['question_19'].value='0';
if (document.forms['question_form']['data_giocata']) {document.forms['question_form']['data_giocata'].value='';}
if (document.forms['question_form']['temp_silaritudine']) {document.forms['question_form']['temp_silaritudine'].value='No';}">

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

$num_domande = 3;		// numero domande da porre
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

// 	Array(11,"Nel rispetto della tradizione, nell'anno 1974 a Caposele non e' nato nessun bambino con il nome di:"          ,
// 			Array("Giuseppe","Rocco","Antonio","Aniello")							,"0001"),

// 	Array(12,"Dov'e' Tredogge?"	,
// 			Array("Alla foce del fiume","Vicino a Materdomini","In montagna","Alle sorgenti del Sele")	,"0001"	),

// 	Array(13,"Chi era il sindaco di Caposele nel 2005?"  ,
// 			Array("Alfonso Merola","Giuseppe Melillo","Antonio Corona","Agostino Montanari")  		,"0100" ),

// 	Array(14,"Che sport praticava Manliuccio?"           ,
// 			Array("Tennis","Corsa","Calcio","Ciclismo")      						,"0010" ),

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
//alert('5: '+myWind);
//	if (!myWind || myWind.closed)
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
		
//alert('5_2: '+win_top);
		myWind = window.open("","finestra_"+question_id,"width=" + win_width + ",height=" + win_height + ",top=" + win_top + ",left=" + win_left+", status=off, menubar=off, toolbar=off, scrollbar=off, resizable=off");
//alert('6: '+myWind.closed);
		
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
		myWind.document.write("<br><hr>\n");
		myWind.document.write("<INPUT TYPE=\"button\" NAME=\"storage\" VALUE=\"Rispondi\" \n");
		myWind.document.write(" onClick=\"right_ans='"+right_ans+"'; res = read_radio(self.document.forms['input']['radio']);\nif (!res)  {alert('Rispondi prima!');return false;} \nres2=right_ans.substring(res-1,res); \nif (res2<0) {res2=-res2;} \nnew_value=formatta("+question_id+")+formatta(res)+formatta(res2);\nself.opener.document.forms['question_form']['"+tag_feedback+"'].value = self.opener.document.forms['question_form']['"+tag_feedback+"'].value+new_value;  \nself.window.close(); \n \nself.opener.check_input(self.opener.document.forms['question_form']);\" \n>\n");
		myWind.document.write("<"+"/FORM>\n");
		myWind.document.write("<"+"/body><"+"/html>\n");
		myWind.document.close();
		myWind.focus();
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
// alert('1: '+f['question_19'].value);
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
	list_Q = new Array;
	list_S = new Array;
	list_F = new Array;
	list_C = new Array;
	list_o = new Array;
	for (i = 0; i < list.length; i++)
	{
		
		if (i < 8)
		{
			list_Q[list_Q.length] = list[i];
		}
		else if (i < 12)
		{
			list_S[list_S.length] = list[i];
		}
		else if (i < 14)
		{
			list_F[list_F.length] = list[i];
		}
		else if (i < 15)
		{
			list_C[0] = list[i];
		}
		else
		{
			list_o[list_o.length] = list[i];
		}
	}
	
	// verifica correttezza 8 squadre ammesse quarti di finale
/*	gironeA = new Array("Chelsea","Barcellona");
	gironeB = new Array("Bayern Monaco","Inter");
	gironeC = new Array("Liverpool","Psv Eindhoven");
	gironeD = new Array("Valencia","Roma");
	gironeE = new Array("Lione","Real Madrid");
	gironeF = new Array("Manchester United","Celtic Glasgow");
	gironeG = new Array("Arsenal","Porto");
	gironeH = new Array("Milan","Lilla");*/
	gironeA = new Array("Chelsea","Porto");
	gironeB = new Array("Arsenal","Psv Eindhoven");
	gironeC = new Array("Lione","Roma");
	gironeD = new Array("Bayern Monaco","Real Madrid");
	gironeE = new Array("Milan","Celtic Glasgow");
	gironeF = new Array("Manchester United","Lilla");
	gironeG = new Array("Liverpool","Barcellona");
	gironeH = new Array("Valencia","Inter");
	
	gironi = new Array(gironeA,gironeB,gironeC,gironeD,gironeE,gironeF,gironeG,gironeH);
	
	
	num_per_girone = 1;
	vettore_gironi = new Array(0,0,0,0,0,0,0,0);
	gironi_errati = false;
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
			alert('Nei qualificati ai quarti di finale la squadra '+squadra+' compare '+ripetizioni+' volte!');
			return false;
		}
		
	}
	
	// visualizza messaggio d'errore nel caso non siano indicate 2 squadre per girone
	if (gironi_errati)
	{
		msg = "Nei qualificati agli ottavi di finale non viene indicata "+num_per_girone+" squadra/e per ciascuno scontro:";
		for (i=0; i<=7; i++)
		{
			msg += "\n    Scontro "+String.fromCharCode(i+65)+': '+vettore_gironi[i]; 
			
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
	
	
	// verifica correttezza 4 squadre ammesse semifinale
	for (i = 0; i < list_S.length; i++)
	{
		squadra = list_S[i];
// continue; // !!!
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

	tag_feedback = 'question_19'; // nome del campo nascosto, con cui interagisce la popup window


	// verifica formato data (auth_nato)
	formato_data = /^[0-9]{2}\/[0-9]{2}\/[0-9]{4}$/;
	if (!auth_nato.match(formato_data))
	{
		alert("Il formato della data di nascita deve essere gg/mm/aaaa (es. 31/12/1974)!");
		return false;
	}
	
// alert('3: '+auth_provenienza.toUpperCase(auth_provenienza));

	// gestione Caposelesi
	if (!<?php echo $admin_mode; ?>)
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



<table border=0 cellpadding=0 cellspacing=0 width=806 style='border-collapse: collapse;table-layout:fixed;width:605pt'>
 <col width=12 style='width:9pt'>
 <col width=124 style='width:93pt'>
 <col width=12 style='width:9pt'>
 <col width=124 style='width:93pt'>
 <col width=12 style='width:9pt'>
 <col width=124 style='width:93pt'>
 <col width=12 style='width:9pt'>
 <col width=124 style='width:93pt'>
 <col width=12 span=2 style='width:9pt'>
 <col width=110 style='width:95pt'>
 <col width=64 span=2 style='width:48pt'>
 <tr style='height:18.0pt'>
  <td width=12 style='height:18.0pt;width:9pt'></td>
  <td colspan=10 class=xl25 width=666 style='width:500pt'>Champions League 2006/07 - scheda per il sondaggio -</td>
  <td class=xl25 width=64 style='width:48pt'></td>
  <td class=xl25 width=64 style='width:48pt'></td>
 </tr>
 <tr style='height:3.75pt'>
  <td style='height:3.75pt'></td>
  <td colspan=12 class=xl25></td>
 </tr>
<?php
//
// 5) visualizzazione del messaggio di stato della lotteria
//
if (!empty($messaggio_stato_sondaggio)) {
?>
 <tr style='height:18.0pt'>
  <td width=12 style='height:18.0pt;width:9pt'></td>
  <td colspan=10 class=xl27 width=666 style='width:500pt' align='center'><?php echo $messaggio_stato_sondaggio; ?></td>
  <td class=xl25 width=64 style='width:48pt'></td>
  <td class=xl25 width=64 style='width:48pt'></td>
 </tr>
 <tr style='height:3.75pt'>
  <td style='height:3.75pt'></td>
  <td colspan=12 class=xl25></td>
 </tr>
<?php
}
?>
 <tr style='height:14.25pt'>
  <td style='height:14.25pt'></td>
  <td class=xl34>nome</td>
  <td></td>
  <td class=xl34>cognome</td>
  <td></td>
  <td class=xl34>nato/a il</td>
  <td></td>
  <td class=xl34>comune di origine</td>
  <td colspan=5></td>
 </tr>
 <tr style='height:14.25pt' align='left'>
  <td style='height:14.25pt'></td>
  <td class=xl35>



<!-- #16 -->
<input name='question_15' type='edit' value='' class='x137'>


</td>
  <td></td>
  <td class=xl35>



<!-- #17 -->
<input name='question_16' type='edit' value='' class='x137'>



</td>
  <td></td>
  <td class=xl35>



<!-- #18 -->
<input name='question_17' type='edit' value='' class='x137'>



</td>
  <td></td>
  <td class=xl35>



<!-- #19 -->
<input name='question_18' type='edit' value='' class='x137'>

<!-- #20 -->
<input name='question_19' type='hidden' value='0' class='x137'>


</td>
  <td colspan=5 class=xl25></td>
 </tr>
 <tr style='height:7.5pt'>
  <td style='height:7.5pt'></td>
  <td colspan=7></td>
  <td></td>
  <td></td>
  <td></td>
  <td colspan=2></td>
 </tr>
 <tr style='height:16.5pt'>
  <td style='height:16.5pt'></td>
  <td colspan=7 class=xl36>Le 16 squadre ammesse agli ottavi di finale</td>
  <td></td>
  <td></td>
  <td></td>
  <td colspan=2></td>
 </tr>
 <tr style='height:5.25pt'>
  <td style='height:5.25pt'></td>
  <td colspan=8></td>
  <td></td>
  <td></td>
  <td colspan=2></td>
 </tr>
 <tr style='height:10.95pt'>
  <td style='height:10.95pt'></td>
  <td class=xl50>Porto G2</td>
  <td></td>
  <td class=xl50>Psv Eindhoven C2</td>
  <td></td>
  <td class=xl50>Roma D2</td>
  <td></td>
  <td class=xl50>Real Madrid E2</td>
  <td colspan=2></td>
  <td class=xl57 style="background:#ffffc0;">Ottavi di finale</td>
  <td colspan=2></td>
 </tr>
 <tr style='height:10.95pt'>
  <td style='height:10.95pt'></td>
  <td class=xl51>Chelsea A1</td>
  <td></td>
  <td class=xl51>Arsenal G1</td>
  <td></td>
  <td class=xl51>Lione E1</td>
  <td></td>
  <td class=xl51>Bayern Monaco B1</td>
  <td></td>
  <td></td>
  <td rowspan=2 class=xl59  style="background:#ffffc0;">andata 20-21/02/2007</td>
  <td colspan=2></td>
 </tr>
 <tr style='height:5.25pt'>
  <td colspan=3 style='height:5.25pt;'></td>
  <td colspan=2></td>
  <td colspan=4></td>
  <td></td>
  <td colspan=2></td>
 </tr>
 <tr style='height:10.95pt'>
  <td style='height:10.95pt'></td>
  <td class=xl50>Celtic Glasgow F2</td>
  <td></td>
  <td class=xl50>Lilla H2</td>
  <td></td>
  <td class=xl50>Barcellona A2</td>
  <td></td>
  <td class=xl50>Inter B2</td>
  <td></td>
  <td></td>
  <td class=xl58  style="background:#ffffc0;">ritorno 06-07/03/2007</td>
  <td colspan=2></td>
 </tr>
 <tr style='height:10.75pt'>
  <td style='height:10.75pt'></td>
  <td class=xl51>Milan H1</td>
  <td></td>
  <td class=xl51>Manch. United F1</td>
  <td></td>
  <td class=xl51>Liverpool C1</td>
  <td></td>
  <td class=xl51>Valencia D1</td>
  <td></td>
  <td></td>
  <td></td>
  <td colspan=2></td>
 </tr>
 <tr style='height:5.25pt'>
  <td colspan=10 style='height:5.25pt;'></td>
  <td></td>
  <td colspan=2></td>
 </tr>
 <tr style='height:3.75pt'>
  <td colspan=10 style='height:3.75pt;'></td>
  <td></td>
  <td colspan=2></td>
 </tr>
 <tr style='height:15.95pt'>
  <td style='height:15.95pt'></td>
  <td class=xl27 colspan=7>tra queste 16 squadre
  saranno ammesse ai <font class=font9>quarti di finale</font><font
  class=font8> le seguenti 8 (in ordine libero):</font></td>
  <td colspan=2></td>
  <td></td>
  <td colspan=2></td>
 </tr>
 <tr style='height:15.95pt'>
  <td style='height:15.95pt'></td>
  <td colspan=2 class=xl39>



<!-- #1 -->
<select name='question_00' style="width:100pt;">
<option>&nbsp;</option>
<option value="Porto">Porto</option>
<option value="Chelsea">Chelsea</option>
<option value="Psv Eindhoven">Psv Eindhoven</option>
<option value="Arsenal">Arsenal</option>

<option value="Roma">Roma</option>
<option value="Lione">Lione</option>
<option value="Real Madrid">Real Madrid</option>
<option value="Bayern Monaco">Bayern Monaco</option>

<option value="Celtic Glasgow">Celtic Glasgow</option>
<option value="Milan">Milan</option>
<option value="Lilla">Lilla</option>
<option value="Manchester United">Manchester United</option>

<option value="Barcellona">Barcellona</option>
<option value="Liverpool">Liverpool</option>
<option value="Valencia">Valencia</option>
<option value="Inter">Inter</option>
</select>


</td>
  <td colspan=2 class=xl39>


<!-- #2 -->
<select name='question_01' style="width:100pt;">
<option>&nbsp;</option>
<option value="Porto">Porto</option>
<option value="Chelsea">Chelsea</option>
<option value="Psv Eindhoven">Psv Eindhoven</option>
<option value="Arsenal">Arsenal</option>

<option value="Roma">Roma</option>
<option value="Lione">Lione</option>
<option value="Real Madrid">Real Madrid</option>
<option value="Bayern Monaco">Bayern Monaco</option>

<option value="Celtic Glasgow">Celtic Glasgow</option>
<option value="Milan">Milan</option>
<option value="Lilla">Lilla</option>
<option value="Manchester United">Manchester United</option>

<option value="Barcellona">Barcellona</option>
<option value="Liverpool">Liverpool</option>
<option value="Valencia">Valencia</option>
<option value="Inter">Inter</option>
</select>


</td>
  <td colspan=2 class=xl39>


<!-- #3 -->
<select name='question_02' style="width:100pt;">
<option>&nbsp;</option>
<option value="Porto">Porto</option>
<option value="Chelsea">Chelsea</option>
<option value="Psv Eindhoven">Psv Eindhoven</option>
<option value="Arsenal">Arsenal</option>

<option value="Roma">Roma</option>
<option value="Lione">Lione</option>
<option value="Real Madrid">Real Madrid</option>
<option value="Bayern Monaco">Bayern Monaco</option>

<option value="Celtic Glasgow">Celtic Glasgow</option>
<option value="Milan">Milan</option>
<option value="Lilla">Lilla</option>
<option value="Manchester United">Manchester United</option>

<option value="Barcellona">Barcellona</option>
<option value="Liverpool">Liverpool</option>
<option value="Valencia">Valencia</option>
<option value="Inter">Inter</option>
</select>



</td>
  <td colspan=2 class=xl39>



<!-- #4 -->
<select name='question_03' style="width:100pt;">
<option>&nbsp;</option>
<option value="Porto">Porto</option>
<option value="Chelsea">Chelsea</option>
<option value="Psv Eindhoven">Psv Eindhoven</option>
<option value="Arsenal">Arsenal</option>

<option value="Roma">Roma</option>
<option value="Lione">Lione</option>
<option value="Real Madrid">Real Madrid</option>
<option value="Bayern Monaco">Bayern Monaco</option>

<option value="Celtic Glasgow">Celtic Glasgow</option>
<option value="Milan">Milan</option>
<option value="Lilla">Lilla</option>
<option value="Manchester United">Manchester United</option>

<option value="Barcellona">Barcellona</option>
<option value="Liverpool">Liverpool</option>
<option value="Valencia">Valencia</option>
<option value="Inter">Inter</option>
</select>



</td>
  <td></td>
  <td class=xl57 style="background:#ffffc0;">Quarti di finale</td>
  <td colspan=2></td>
 </tr>
 <tr style='height:15.95pt'>
  <td style='height:15.95pt'></td>
  <td colspan=2 class=xl39>



<!-- #5 -->
<select name='question_04' style="width:100pt;">
<option>&nbsp;</option>
<option value="Porto">Porto</option>
<option value="Chelsea">Chelsea</option>
<option value="Psv Eindhoven">Psv Eindhoven</option>
<option value="Arsenal">Arsenal</option>

<option value="Roma">Roma</option>
<option value="Lione">Lione</option>
<option value="Real Madrid">Real Madrid</option>
<option value="Bayern Monaco">Bayern Monaco</option>

<option value="Celtic Glasgow">Celtic Glasgow</option>
<option value="Milan">Milan</option>
<option value="Lilla">Lilla</option>
<option value="Manchester United">Manchester United</option>

<option value="Barcellona">Barcellona</option>
<option value="Liverpool">Liverpool</option>
<option value="Valencia">Valencia</option>
<option value="Inter">Inter</option>
</select>



</td>
  <td colspan=2 class=xl39>



<!-- #6 -->
<select name='question_05' style="width:100pt;">
<option>&nbsp;</option>
<option value="Porto">Porto</option>
<option value="Chelsea">Chelsea</option>
<option value="Psv Eindhoven">Psv Eindhoven</option>
<option value="Arsenal">Arsenal</option>

<option value="Roma">Roma</option>
<option value="Lione">Lione</option>
<option value="Real Madrid">Real Madrid</option>
<option value="Bayern Monaco">Bayern Monaco</option>

<option value="Celtic Glasgow">Celtic Glasgow</option>
<option value="Milan">Milan</option>
<option value="Lilla">Lilla</option>
<option value="Manchester United">Manchester United</option>

<option value="Barcellona">Barcellona</option>
<option value="Liverpool">Liverpool</option>
<option value="Valencia">Valencia</option>
<option value="Inter">Inter</option>
</select>



</td>
  <td colspan=2 class=xl39>



<!-- #7 -->
<select name='question_06' style="width:100pt;">
<option>&nbsp;</option>
<option value="Porto">Porto</option>
<option value="Chelsea">Chelsea</option>
<option value="Psv Eindhoven">Psv Eindhoven</option>
<option value="Arsenal">Arsenal</option>

<option value="Roma">Roma</option>
<option value="Lione">Lione</option>
<option value="Real Madrid">Real Madrid</option>
<option value="Bayern Monaco">Bayern Monaco</option>

<option value="Celtic Glasgow">Celtic Glasgow</option>
<option value="Milan">Milan</option>
<option value="Lilla">Lilla</option>
<option value="Manchester United">Manchester United</option>

<option value="Barcellona">Barcellona</option>
<option value="Liverpool">Liverpool</option>
<option value="Valencia">Valencia</option>
<option value="Inter">Inter</option>
</select>



</td>
  <td colspan=2 class=xl39>



<!-- #8 -->
<select name='question_07' style="width:100pt;">
<option>&nbsp;</option>
<option value="Porto">Porto</option>
<option value="Chelsea">Chelsea</option>
<option value="Psv Eindhoven">Psv Eindhoven</option>
<option value="Arsenal">Arsenal</option>

<option value="Roma">Roma</option>
<option value="Lione">Lione</option>
<option value="Real Madrid">Real Madrid</option>
<option value="Bayern Monaco">Bayern Monaco</option>

<option value="Celtic Glasgow">Celtic Glasgow</option>
<option value="Milan">Milan</option>
<option value="Lilla">Lilla</option>
<option value="Manchester United">Manchester United</option>

<option value="Barcellona">Barcellona</option>
<option value="Liverpool">Liverpool</option>
<option value="Valencia">Valencia</option>
<option value="Inter">Inter</option>
</select>



</td>
  <td></td>
  <td class=xl59 style="background:#ffffc0;">andata 03-04/04/2007</td>
  <td colspan=2></td>
 </tr>
 <tr style='height:12.0pt'>
  <td colspan=10 style='height:12.0pt;'></td>
  <td class=xl58 style="background:#ffffc0;">ritorno 10-11/04/2007</td>
  <td colspan=2></td>
 </tr>
 <tr style='height:15.95pt'>
  <td style='height:15.95pt'></td>
  <td class=xl27 colspan=5>di cui saranno ammesse
  alle <font class=font9>semifinali</font><font class=font8> le seguenti 4 (in
  ordine libero):</font></td>
  <td colspan=4></td>
  <td></td>
  <td colspan=2></td>
 </tr>
 <tr style='height:15.95pt'>
  <td style='height:15.95pt'></td>
  <td colspan=2 class=xl39>



<!-- #9 -->
<select name='question_08' style="width:100pt;">
<option>&nbsp;</option>
<option value="Porto">Porto</option>
<option value="Chelsea">Chelsea</option>
<option value="Psv Eindhoven">Psv Eindhoven</option>
<option value="Arsenal">Arsenal</option>

<option value="Roma">Roma</option>
<option value="Lione">Lione</option>
<option value="Real Madrid">Real Madrid</option>
<option value="Bayern Monaco">Bayern Monaco</option>

<option value="Celtic Glasgow">Celtic Glasgow</option>
<option value="Milan">Milan</option>
<option value="Lilla">Lilla</option>
<option value="Manchester United">Manchester United</option>

<option value="Barcellona">Barcellona</option>
<option value="Liverpool">Liverpool</option>
<option value="Valencia">Valencia</option>
<option value="Inter">Inter</option>
</select>



</td>
  <td colspan=2 class=xl39>



<!-- #10 -->
<select name='question_09' style="width:100pt;">
<option>&nbsp;</option>
<option value="Porto">Porto</option>
<option value="Chelsea">Chelsea</option>
<option value="Psv Eindhoven">Psv Eindhoven</option>
<option value="Arsenal">Arsenal</option>

<option value="Roma">Roma</option>
<option value="Lione">Lione</option>
<option value="Real Madrid">Real Madrid</option>
<option value="Bayern Monaco">Bayern Monaco</option>

<option value="Celtic Glasgow">Celtic Glasgow</option>
<option value="Milan">Milan</option>
<option value="Lilla">Lilla</option>
<option value="Manchester United">Manchester United</option>

<option value="Barcellona">Barcellona</option>
<option value="Liverpool">Liverpool</option>
<option value="Valencia">Valencia</option>
<option value="Inter">Inter</option>
</select>



</td>
  <td colspan=2 class=xl39>



<!-- #11 -->
<select name='question_10' style="width:100pt;">
<option>&nbsp;</option>
<option value="Porto">Porto</option>
<option value="Chelsea">Chelsea</option>
<option value="Psv Eindhoven">Psv Eindhoven</option>
<option value="Arsenal">Arsenal</option>

<option value="Roma">Roma</option>
<option value="Lione">Lione</option>
<option value="Real Madrid">Real Madrid</option>
<option value="Bayern Monaco">Bayern Monaco</option>

<option value="Celtic Glasgow">Celtic Glasgow</option>
<option value="Milan">Milan</option>
<option value="Lilla">Lilla</option>
<option value="Manchester United">Manchester United</option>

<option value="Barcellona">Barcellona</option>
<option value="Liverpool">Liverpool</option>
<option value="Valencia">Valencia</option>
<option value="Inter">Inter</option>
</select>



</td>
  <td colspan=2 class=xl39>



<!-- #12 -->
<select name='question_11' style="width:100pt;">
<option>&nbsp;</option>
<option value="Porto">Porto</option>
<option value="Chelsea">Chelsea</option>
<option value="Psv Eindhoven">Psv Eindhoven</option>
<option value="Arsenal">Arsenal</option>

<option value="Roma">Roma</option>
<option value="Lione">Lione</option>
<option value="Real Madrid">Real Madrid</option>
<option value="Bayern Monaco">Bayern Monaco</option>

<option value="Celtic Glasgow">Celtic Glasgow</option>
<option value="Milan">Milan</option>
<option value="Lilla">Lilla</option>
<option value="Manchester United">Manchester United</option>

<option value="Barcellona">Barcellona</option>
<option value="Liverpool">Liverpool</option>
<option value="Valencia">Valencia</option>
<option value="Inter">Inter</option>
</select>



</td>
  <td></td>
  <td class=xl57 style="background:#ffffc0;">Semifinali</td>
  <td colspan=2></td>
 </tr>
 <tr style='height:13.5pt'>
  <td colspan=10 style='height:13.5pt;'></td>
  <td class=xl59 style="background:#ffffc0;">andata 24-25/04/2007</td>
  <td colspan=2></td>
 </tr>
 <tr style='height:12.0pt'>
  <td colspan=10 style='height:12.0pt;'></td>
  <td class=xl58 style="background:#ffffc0;">ritorno 01-02/05/2007</td>
  <td colspan=2></td>
 </tr>
 <tr style='height:15.95pt'>
  <td style='height:15.95pt'></td>
  <td class=xl27 colspan=2>le 2 <font class=font9>finaliste</font><font
  class=font8> saranno:</font></td>
  <td colspan=3></td>
  <td colspan=2 class=xl66>Squadra Campione</td>
  <td colspan=2></td>
  <td></td>
  <td colspan=2></td>
 </tr>
 <tr style='height:15.95pt'>
  <td style='height:15.95pt'></td>
  <td colspan=2 class=xl39>



<!-- #13 -->
<select name='question_12' style="width:100pt;">
<option>&nbsp;</option>
<option value="Porto">Porto</option>
<option value="Chelsea">Chelsea</option>
<option value="Psv Eindhoven">Psv Eindhoven</option>
<option value="Arsenal">Arsenal</option>

<option value="Roma">Roma</option>
<option value="Lione">Lione</option>
<option value="Real Madrid">Real Madrid</option>
<option value="Bayern Monaco">Bayern Monaco</option>

<option value="Celtic Glasgow">Celtic Glasgow</option>
<option value="Milan">Milan</option>
<option value="Lilla">Lilla</option>
<option value="Manchester United">Manchester United</option>

<option value="Barcellona">Barcellona</option>
<option value="Liverpool">Liverpool</option>
<option value="Valencia">Valencia</option>
<option value="Inter">Inter</option>
</select>



</td>
  <td colspan=2 class=xl39>



<!-- #14 -->
<select name='question_13' style="width:100pt;">
<option>&nbsp;</option>
<option value="Porto">Porto</option>
<option value="Chelsea">Chelsea</option>
<option value="Psv Eindhoven">Psv Eindhoven</option>
<option value="Arsenal">Arsenal</option>

<option value="Roma">Roma</option>
<option value="Lione">Lione</option>
<option value="Real Madrid">Real Madrid</option>
<option value="Bayern Monaco">Bayern Monaco</option>

<option value="Celtic Glasgow">Celtic Glasgow</option>
<option value="Milan">Milan</option>
<option value="Lilla">Lilla</option>
<option value="Manchester United">Manchester United</option>

<option value="Barcellona">Barcellona</option>
<option value="Liverpool">Liverpool</option>
<option value="Valencia">Valencia</option>
<option value="Inter">Inter</option>
</select>



</td>
  <td>&nbsp;</td>
  <td colspan=2 class=xl39 style='border-top:none'>



<!-- #15 -->
<select name='question_14' style="width:100pt;">
<option>&nbsp;</option>
<option value="Porto">Porto</option>
<option value="Chelsea">Chelsea</option>
<option value="Psv Eindhoven">Psv Eindhoven</option>
<option value="Arsenal">Arsenal</option>

<option value="Roma">Roma</option>
<option value="Lione">Lione</option>
<option value="Real Madrid">Real Madrid</option>
<option value="Bayern Monaco">Bayern Monaco</option>

<option value="Celtic Glasgow">Celtic Glasgow</option>
<option value="Milan">Milan</option>
<option value="Lilla">Lilla</option>
<option value="Manchester United">Manchester United</option>

<option value="Barcellona">Barcellona</option>
<option value="Liverpool">Liverpool</option>
<option value="Valencia">Valencia</option>
<option value="Inter">Inter</option>
</select>



</td>
  <td colspan=2></td>
  <td class=xl60 style="background:#ffffc0;">Finale 23/05/2007</td>
  <td colspan=2></td>
 </tr>
 <tr style='height:10.5pt'>
  <td colspan=10 style='height:10.5pt;'></td>
  <td></td>
  <td colspan=2></td>
 </tr>





<?php
//
// modalita' amministrativa (aggiungi campo con data della giocata
//
if ($admin_mode) {
?>
<!-- gestione modalita' amministrativa -->
<tr>
	<td>&nbsp;</td>
	<td colspan=10 align=left>
		<b>
<!--		Data di ricezione giocata (hh:mm gg/mm/aaaa):
		<input type="edit" name="data_giocata" value="24:00 01/01/2006" class='x137'>-->
		Data di ricezione giocata (gg/mm/aaaa):
		<input type="edit" name="data_giocata" value="20/12/2006" class='x137'>
		</b>
	<br>
		<b>
		Caposelese?
		<input type="edit" name="temp_silaritudine" value="No" class='x137' disabled><input type="button" value="cambia" onClick="toggle_caposelese(document.forms['question_form'],'question_19');">
		</b>
	<br>
	</td>
</tr>
<?php
}
// fine modalita' amministrativa (aggiungi campo con data della giocata
?>



<?php
//
// modalita' info oppure giocata-abilitata
//
if (!$info_mode)
{
?>
<!-- tasto invia -->
<tr>
	<td>&nbsp;</td>
	<td colspan=3 align=center>
		<input type='submit' value='Gioca'>
	</td>
</tr>
<?php
}
// fine modalita' info oppure giocata-abilitata
?>




 <tr style='height:10.5pt'>
  <td colspan=10 style='height:10.5pt;'></td>
  <td></td>
  <td colspan=2></td>
 </tr>


 <tr style='height:12.0pt'>
  <td style='height:12.0pt'></td>
  <td class=xl26 colspan=12>
	Il presente studio/sondaggio è proposto dall'ARS Amatori Running Sele a puro
  scopo ricreativo e di approfondimento. 
  </td>
 </tr>
 <tr style='height:12.0pt'>
  <td style='height:12.0pt'></td>
  <td class=xl26 colspan=12>
	Lo spirito è quello di
  individuare il pronosticatore più bravo in base a criteri basati sul merito,
  ma non bisogna dimenticare che esiste una accentuata
  </td>
 </tr>
 <tr style='height:12.0pt'>
  <td style='height:12.0pt'></td>
  <td class=xl26 colspan=12>
	componente aleatoria
  insita nella stessa formula della Champions, che prevede il sorteggio
  integrale degli abbinamenti delle squadre nei quarti di finale.
  </td>
 </tr>
 <tr style='height:6.0pt'>
  <td style='height:6.0pt'></td>
  <td></td>
  <td colspan=8></td>
  <td></td>
  <td colspan=2></td>
 </tr>

<?php
$file_log = $root_path."custom/lotterie/".sprintf('lotteria_%03d_log.txt',$id_questions);
if ($flag_show_results && file_exists($file_log))
{
?>
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
<?php
}
?>

 <tr style='height:12.0pt'>
  <td colspan=10 style='height:12.0pt;'></td>
  <td></td>
  <td colspan=2></td>
 </tr>
 <tr style='height:12.0pt'>
  <td style='height:12.0pt'></td>
  <td class=xl49>Regolamento</td>
  <td colspan=8></td>
  <td></td>
  <td colspan=2></td>
 </tr>
 <tr style='height:12.75pt'>
  <td style='height:12.75pt'></td>
  <td class=xl26 colspan=12>
	Tutte le giocate regolarmente effettuate, verranno ordinate in forma di classifica
  (disponibile sul sito www.ars.altervista.org), seguendo, nell'ordine,
  </td>
 </tr>
 <tr style='height:12.75pt'>
  <td style='height:12.75pt'></td>
  <td class=xl26> i seguenti criteri:</td>
  <td colspan=8></td>
  <td></td>
  <td colspan=2></td>
 </tr>
 <tr style='height:12.75pt'>
  <td style='height:12.75pt'></td>
  <td class=xl26 colspan=12>
	&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;- Il punteggio complessivo sarà
  calcolato attribuendo <font class=font14>4</font><font class=font7> punti per
  ognuno dei 15 pronostici formulati che si rivelino esatti </font><font
  class=font20>(punteggio massimo 60 punti);</font>
  </td>
 </tr>
 <tr style='height:12.75pt'>
  <td style='height:12.75pt'></td>
  <td class=xl26 colspan=12>
	&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;- Nel caso di passaggio del turno
  ottenuto avvalendosi della regola del goal in trasferta, i punti attribuiti
  saranno <font class=font14>3</font><font class=font7>;</font>
  </td>
 </tr>
 <tr style='height:12.75pt'>
  <td style='height:12.75pt'></td>
  <td class=xl26 colspan=12>
	&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;- Nell'eventualità di passaggio del
  turno ai rigori, i punti saranno <font class=font14>2</font><font
  class=font7> sia per la vincente che per l'eliminata;</font>
  </td>
 </tr>
 <tr style='height:12.75pt'>
  <td style='height:12.75pt'></td>
  <td class=xl26 colspan=12>
	&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;- Verrà infine riconosciuto <font
  class=font14>1</font><font class=font7> punto anche alla squadra indicata che
  viene eliminata per effetto della regola del goal in trasferta.</font>
  </td>
 </tr>
 <tr style='height:12.75pt'>
  <td style='height:12.75pt'></td>
  <td class=xl26 colspan=12>
	A parità di punteggio verranno considerati nell'ordine:</td>
 </tr>
 <tr style='height:12.75pt'>
  <td style='height:12.75pt'></td>
  <td class=xl26 colspan=12>
	1. Il maggior numero di squadre indovinate a partire dalla squadra vincitrice;
  </td>
 </tr>
 <tr style='height:12.75pt'>
  <td style='height:12.75pt'></td>
  <td class=xl26 colspan=12>
	2. La schedina che reca la data di giocata più antica. Per non danneggiare chi
  dovesse venire a conoscenza del sondaggio solo in un secondo momento,
  </td>
 </tr>
 <tr style='height:12.75pt'>
  <td style='height:12.75pt'></td>
  <td class=xl26 colspan=12>
	&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;tutte le giocate effettuate entro le
  ore 24 del 01/01/2007, recheranno comunque la data di presentazione 1 gennaio
  2007
  </td>
 </tr>
 <tr style='height:12.75pt'>
  <td style='height:12.75pt'></td>
  <td class=xl26></td>
  <td colspan=8></td>
  <td></td>
  <td colspan=2></td>
 </tr>
 <tr style='height:12.75pt'>
  <td colspan=10 style='height:12.75pt;'></td>
  <td></td>
  <td colspan=2></td>
 </tr>
 <tr style='height:12.75pt'>
  <td style='height:12.75pt'></td>
  <td class=xl48 colspan=3>Inizio giocate:
  20.12.2006</td>
  <td colspan=6></td>
  <td></td>
  <td colspan=2></td>
 </tr>
 <tr style='height:12.75pt'>
  <td style='height:12.75pt'></td>
  <td class=xl48 colspan=3>Fine giocate: 18.02.2007</td>
  <td colspan=6></td>
  <td></td>
  <td colspan=2></td>
 </tr>
 <tr style='height:12.75pt'>
  <td style='height:12.75pt'></td>
  <td></td>
  <td colspan=8></td>
  <td></td>
  <td colspan=2></td>
 </tr>
 <tr style='height:12.75pt'>
  <td colspan=10 style='height:12.75pt;'></td>
  <td></td>
  <td colspan=2></td>
 </tr>
<!-- <tr style='height:12.75pt'>
  <td style='height:12.75pt'></td>
  <td class=xl61 colspan=10>*Ricordiamo cortesemente
  a chi si avvale delle nostre iniziative che, anche per questo sondaggio, è
  prevista una eventuale contribuzione simbolica di euro 1</td>
  <td colspan=2></td>
 </tr>
 <tr style='height:12.75pt'>
  <td style='height:12.75pt'></td>
  <td class=xl61 colspan=5>&nbsp;&nbsp;(non fosse altro per l'impostazione e la
  stampa della presente scheda).</td>
  <td colspan=4></td>
  <td></td>
  <td colspan=2></td>
 </tr>
 <tr style='height:12.75pt'>
  <td style='height:12.75pt'></td>
  <td class=xl61 colspan=9>&nbsp;&nbsp;Ovviamente per chi gioca online,
  l'eventuale contributo potrà essere recapitato a uno dei soci Amatori R.S.
  alla prima occasione utile.</td>
  <td></td>
  <td colspan=2></td>
 </tr>-->
<!-- <tr style='height:12.75pt'>
  <td style='height:12.75pt'></td>
  <td></td>
  <td colspan=8></td>
  <td></td>
  <td colspan=2></td>
 </tr>-->
 <tr style='height:12.75pt'>
  <td style='height:12.75pt'></td>
  <td class=xl61 colspan=12>
  * Saranno gradite osservazioni, suggerimenti e collaborazioni (anche minime e
  saltuarie) per iniziative analoghe o di altro tipo.
  </td>
 </tr>
 <tr style='height:12.75pt'>
  <td colspan=10 style='height:12.75pt;'></td>
  <td></td>
  <td colspan=2></td>
 </tr>
</table>

<input type="hidden" name="auth_token" value="<?php echo $auth_token; ?>">
<input type="hidden" name="id_questions" value="<?php echo $id_questions; ?>">
<!--input type="hidden" name="action" value="last_check"-->
<input type="hidden" name="action" value="save">
<br>



<!--
//
// fine della pagina customizzata (fine del codice html customizzato (senza header ne' tag body))
//
-->
</form>
