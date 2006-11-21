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
	$admin_path_correction = "../";
}
else
{
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

$info_mode = $_REQUEST['info_mode'];					// azione da eseguire
$info_mode = sanitize_user_input($info_mode,'plain_text',array());	// verifica di sicurezza

?><!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 TRANSITIONAL//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>

<head>

<title><?php echo $web_title ?> - <?php echo $lotteria_nome ?></title>
<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1">
<meta name="GENERATOR" content="Kate">
<meta name="description" content="<?php echo $lotteria_nome; ?>">
<meta name="keywords" content="lotteria, questionario">
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
<body link=blue vlink=purple onLoad="document.forms['question_form']['question_19'].value='0';">

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

$num_domande = 2;

$archivio_domande = Array(
	Array(1,"Dov'e' la Mauta?"	,Array("In riva al fiume","In montagna","Alle sorgenti del Sele")			        ,"010"	),
	Array(2,"Dov'e' Tredogge?"	,Array("Alla foce del fiume","Vicino a Materdomini","In montagna","Alle sorgenti del Sele")	,"0001"	),
	Array(3,"Quando e' pscrai?"	,Array("Ieri","Oggi","Domani","Dopodomani")							,"0001"	),
	Array(4,"Chi era il sindaco di Caposele nel 2005?"  ,Array("Alfonso Merola","Giuseppe Melillo","Antonio Corona","Agostino Montanari")  ,"0100" ),
	Array(5,"Cosa significa la parola 'ngimma?"         ,Array("ginnastica","giorno","sopra","gomma")     ,"0010" ),
	Array(6,"Qual'è il Santo patrono di Caposele?"      ,Array("Lorenzo","Gerardo","Pasquale","Rocco")    ,"1000" ),
	Array(7,"Cosa significa la parola 'nbieri?"         ,Array("Carabinieri","Ieri","Bicchieri","Sotto")  ,"0001" ),
	Array(8,"Che sport praticava Manliuccio?"           ,Array("Tennis","Corsa","Calcio","Ciclismo")      ,"0010" ),
	Array(9,"Vagava per le strade di Caposele"         ,Array("Peppe il francese","Ciccio l'americano","Antonio l'africano","Gerardo l'austriaco"),"0001")
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
	if (!myWind || myWind.closed)
	{
		title_bg 	= "#ffffc0";	// sfondo titolo (giallino)
		odd_row_bg 	= "#e0ffe0";	// sfondo righe dispari (celestino)
		even_row_bg 	= "#e0e0ff";	// sfondo righe pari (verdino)
		
		titolo	= "ArsWeb";
		
		var win_width = 400;				// larghezza finestra
		var win_height = (100+50*answers.length);	// altezza finestra
		var win_left = Math.floor((screen.width-win_width)/2);
		var win_top = Math.floor((screen.height-win_height)/2);
		
		myWind = window.open("_blank","popup","width=" + win_width + ",height=" + win_height + ",top=" + win_top + ",left=" + win_left+", status=off, menubar=off, toolbar=off, scrollbar=off, resizable=off");
		
		myWind.document.write("<!DOCTYPE HTML PUBLIC \"-//W3C//DTD HTML 4.01 TRANSITIONAL//EN\" \"http://www.w3.org/TR/html4/loose.dtd\">\n");
		myWind.document.write("<html>\n");
		myWind.document.write("<head>\n");
		myWind.document.write("<title>"+titolo+"<"+"/title>\n");
		myWind.document.write("<style type=\"text/css\">@import \"<?php echo $admin_path_correction; ?>custom/config/style.css\";<"+"/style>\n");
		myWind.document.write("<"+"/head>\n");
		myWind.document.write("<body onLoad=\"self.focus();\">\n");
		myWind.document.write("<scr"+"ipt type=\"text/javascript\" src=\"<?php echo $javascript_library; ?>\"><"+"/script>");
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
		myWind.document.write("<INPUT TYPE=\"button\" NAME=\"storage\" VALUE=\"Rispondi\" ");
		myWind.document.write(" onClick=\"right_ans='"+right_ans+"';res = read_radio(self.document.forms['input']['radio']);if (!res) {alert('Rispondi prima!');return false;} ;res2=right_ans[res-1];if (res2<0) {res2=-res2;}; self.opener.document.forms['question_form']['"+tag_feedback+"'].value = self.opener.document.forms['question_form']['"+tag_feedback+"'].value+sprintf('%02d%02d%02d',"+question_id+",res,res2); self.window.close();self.opener.check_input(self.opener.document.forms['question_form']);\">\n");
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
	gironeA = new Array("Barcellona","Levski Sofia","Chelsea","Werder Brema");
	gironeB = new Array("Bayern Monaco","Spartak Mosca","Sporting Lisbona","Inter");
	gironeC = new Array("Galatasaray","Bordeaux","Psv Eindhoven","Liverpool");
	gironeD = new Array("Olympiakos Pireo","Valencia","Roma","Shakhtar Donetsk");
	gironeE = new Array("Dynamo Kiev","Steaua Bucarest","Lione","Real Madrid");
	gironeF = new Array("Fc Copenhagen","Benfica","Manchester United","Celtic Glasgow");
	gironeG = new Array("Amburgo","Arsenal","Porto","Cska Mosca");
	gironeH = new Array("Anderlecht","Lille","Milan","Aek Atene");
	
	gironi = new Array(gironeA,gironeB,gironeC,gironeD,gironeE,gironeF,gironeG,gironeH);
	
	
	num_per_girone = 1;
	vettore_gironi = new Array(0,0,0,0,0,0,0,0);
	gironi_errati = false;
	for (i = 0; i < list_Q.length; i++)
	{
		squadra = list_Q[i];
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
		msg = "Nei qualificati agli ottavi di finale non sono indicate "+num_per_girone+" squadre per ciascun girone:";
		for (i=0; i<=7; i++)
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
	//alert('Qualificati ('+(i+1)+'): '+squadra);
	
	// verifica la presenza all'interno del gruppo precedente
	ripetizioni = occurrencies(squadra,list_F);
	if (ripetizioni != 1)
	{
		alert('La squadra '+squadra+" compare come vincitrice, ma non e' presente tra quelle qualificate in finale!");
		return false;
	}
	
	
	// gestisci dati anagrafici
	auth_nome 	= list_o[0];
	auth_cognome 	= list_o[1];
	auth_nato 	= list_o[2];
	auth_provenienza= list_o[3];
	auth_caposelese = list_o[4];
	
	tag_feedback = 'question_19'; // nome del campo nascosto, con cui interagisce la popup window
	
	
	// verifica formato data (auth_nato)
	formato_data = /^[0-9]{2}\/[0-9]{2}\/[0-9]{4}$/;
	if (!auth_nato.match(formato_data))
	{
		alert("Il formato della data di nascita deve essere gg/mm/aaaa (es. 31/12/1974)!");
		return false;
	}
	
	// gestione Caposelesi
	switch (auth_provenienza.toUpperCase(auth_provenienza))
	{
	case 'CAPOSELE':
	case 'MATERDOMINI':
		num_domande = <?php echo $num_domande; ?>; // numero di domande da porre
		len_risposta = 6;// numero di caratteri per ciascuna risposta (2 per la domanda, 2 per la risposta, 2 per esito)
		if ( auth_caposelese.length < 1+num_domande*len_risposta )
		{
			// la prima volta, visualizza un messaggio
			if ( auth_caposelese.length == 1 )
			{
				alert('Ti faccio qualche domanda aggiuntiva per verificare che tu sia di '+auth_provenienza+'!\nPremi OK per cominciare...');
			}
			
			pos_domanda = (auth_caposelese.length-1)/len_risposta+1;
			dati_domanda = archivio_domande[pos_domanda-1];
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
				if (auth_caposelese[i*len_risposta+6] == '1')
				{
					right_answers++;
				}
			}
			
			if (right_answers == num_domande)
			{
				// ha risposto bene a tutte le domande
				auth_caposelese += ';Checked';
			}
			else
			{
				// ha risposto male almeno ad una domanda
				auth_caposelese += ';Unchecked';
			}
		}
	}
	
	auth_hidden = auth_nome+';'+auth_cognome+';'+auth_nato+';'+auth_provenienza
	
	// imposta campo auth_token
	tag = 'auth_token'; // nome del campo nascosto
	f[tag].value = auth_hidden;
	
	// imposta campo auth_caposelese
	tag = tag_feedback; // nome del campo nascosto
	f[tag].value = auth_caposelese;
	
	
	// gestione campi amministrativi
	tag = 'data_giocata';
	
	if (f[tag])
	{
		data_giocata = f[tag].value;
		
		// verifica formato data (auth_nato)
		formato_data = /^[0-9]{2}:[0-9]{2} [0-9]{2}\/[0-9]{2}\/[0-9]{4}$/;
		if (!data_giocata.match(formato_data))
		{
			alert("Il formato della data della giocata deve essere hh:mm gg/mm/aaaa (es. 12:00 31/12/1974)!");
			return false;
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


//-->
</SCRIPT>





<form name="question_form" action="<?php echo $action; ?>" method="post" OnSubmit="return check_input(this)">
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
 <tr style='height:14.25pt'>
  <td style='height:14.25pt'></td>
  <td class=xl34>nome</td>
  <td></td>
  <td class=xl34>cognome</td>
  <td></td>
  <td class=xl34>nato/a il</td>
  <td></td>
  <td class=xl34>provenienza</td>
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
  <td class=xl50>squadra #A1</td>
  <td></td>
  <td class=xl50>squadra #B1</td>
  <td></td>
  <td class=xl50>squadra #C1</td>
  <td></td>
  <td class=xl50>squadra #D1</td>
  <td colspan=2></td>
  <td class=xl57 style="background:#ffffc0;">Ottavi di finale</td>
  <td colspan=2></td>
 </tr>
 <tr style='height:10.95pt'>
  <td style='height:10.95pt'></td>
  <td class=xl51>squadra #A2</td>
  <td></td>
  <td class=xl51>squadra #B2</td>
  <td></td>
  <td class=xl51>squadra #C2</td>
  <td></td>
  <td class=xl51>squadra #D2</td>
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
  <td class=xl50>squadra #E1</td>
  <td></td>
  <td class=xl50>squadra #F1</td>
  <td></td>
  <td class=xl50>squadra #G1</td>
  <td></td>
  <td class=xl50>squadra #H1</td>
  <td></td>
  <td></td>
  <td class=xl58  style="background:#ffffc0;">ritorno 06-07/03/2007</td>
  <td colspan=2></td>
 </tr>
 <tr style='height:10.75pt'>
  <td style='height:10.75pt'></td>
  <td class=xl51>squadra #E2</td>
  <td></td>
  <td class=xl51>squadra #F2</td>
  <td></td>
  <td class=xl51>squadra #G2</td>
  <td></td>
  <td class=xl51>squadra #H2</td>
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
<option>Barcellona</option>
<option>Levski Sofia</option>
<option>Chelsea</option>
<option>Werder Brema</option>
<option>Bayern Monaco</option>
<option>Spartak Mosca</option>
<option>Sporting Lisbona</option>

<option>Inter</option>
<option>Galatasaray</option>
<option>Bordeaux</option>
<option>Psv Eindhoven</option>
<option>Liverpool</option>
<option>Olympiakos Pireo</option>
<option>Valencia</option>
<option>Roma</option>
<option>Shakhtar Donetsk</option>

<option>Dynamo Kiev</option>
<option>Steaua Bucarest</option>
<option>Lione</option>
<option>Real Madrid</option>
<option>Fc Copenhagen</option>
<option>Benfica</option>
<option>Manchester United</option>
<option>Celtic Glasgow</option>
<option>Amburgo</option>

<option>Arsenal</option>
<option>Porto</option>
<option>Cska Mosca</option>
<option>Anderlecht</option>
<option>Lille</option>
<option>Milan</option>
<option>Aek Atene</option>
</select>


</td>
  <td colspan=2 class=xl39>


<!-- #2 -->
<select name='question_01' style="width:100pt;">
<option>&nbsp;</option>
<option>Barcellona</option>
<option>Levski Sofia</option>
<option>Chelsea</option>
<option>Werder Brema</option>
<option>Bayern Monaco</option>
<option>Spartak Mosca</option>
<option>Sporting Lisbona</option>

<option>Inter</option>
<option>Galatasaray</option>
<option>Bordeaux</option>
<option>Psv Eindhoven</option>
<option>Liverpool</option>
<option>Olympiakos Pireo</option>
<option>Valencia</option>
<option>Roma</option>
<option>Shakhtar Donetsk</option>

<option>Dynamo Kiev</option>
<option>Steaua Bucarest</option>
<option>Lione</option>
<option>Real Madrid</option>
<option>Fc Copenhagen</option>
<option>Benfica</option>
<option>Manchester United</option>
<option>Celtic Glasgow</option>
<option>Amburgo</option>

<option>Arsenal</option>
<option>Porto</option>
<option>Cska Mosca</option>
<option>Anderlecht</option>
<option>Lille</option>
<option>Milan</option>
<option>Aek Atene</option>
</select>


</td>
  <td colspan=2 class=xl39>


<!-- #3 -->
<select name='question_02' style="width:100pt;">
<option>&nbsp;</option>
<option>Barcellona</option>
<option>Levski Sofia</option>
<option>Chelsea</option>
<option>Werder Brema</option>
<option>Bayern Monaco</option>
<option>Spartak Mosca</option>
<option>Sporting Lisbona</option>

<option>Inter</option>
<option>Galatasaray</option>
<option>Bordeaux</option>
<option>Psv Eindhoven</option>
<option>Liverpool</option>
<option>Olympiakos Pireo</option>
<option>Valencia</option>
<option>Roma</option>
<option>Shakhtar Donetsk</option>

<option>Dynamo Kiev</option>
<option>Steaua Bucarest</option>
<option>Lione</option>
<option>Real Madrid</option>
<option>Fc Copenhagen</option>
<option>Benfica</option>
<option>Manchester United</option>
<option>Celtic Glasgow</option>
<option>Amburgo</option>

<option>Arsenal</option>
<option>Porto</option>
<option>Cska Mosca</option>
<option>Anderlecht</option>
<option>Lille</option>
<option>Milan</option>
<option>Aek Atene</option>
</select>



</td>
  <td colspan=2 class=xl39>



<!-- #4 -->
<select name='question_03' style="width:100pt;">
<option>&nbsp;</option>
<option>Barcellona</option>
<option>Levski Sofia</option>
<option>Chelsea</option>
<option>Werder Brema</option>
<option>Bayern Monaco</option>
<option>Spartak Mosca</option>
<option>Sporting Lisbona</option>

<option>Inter</option>
<option>Galatasaray</option>
<option>Bordeaux</option>
<option>Psv Eindhoven</option>
<option>Liverpool</option>
<option>Olympiakos Pireo</option>
<option>Valencia</option>
<option>Roma</option>
<option>Shakhtar Donetsk</option>

<option>Dynamo Kiev</option>
<option>Steaua Bucarest</option>
<option>Lione</option>
<option>Real Madrid</option>
<option>Fc Copenhagen</option>
<option>Benfica</option>
<option>Manchester United</option>
<option>Celtic Glasgow</option>
<option>Amburgo</option>

<option>Arsenal</option>
<option>Porto</option>
<option>Cska Mosca</option>
<option>Anderlecht</option>
<option>Lille</option>
<option>Milan</option>
<option>Aek Atene</option>
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
<option>Barcellona</option>
<option>Levski Sofia</option>
<option>Chelsea</option>
<option>Werder Brema</option>
<option>Bayern Monaco</option>
<option>Spartak Mosca</option>
<option>Sporting Lisbona</option>

<option>Inter</option>
<option>Galatasaray</option>
<option>Bordeaux</option>
<option>Psv Eindhoven</option>
<option>Liverpool</option>
<option>Olympiakos Pireo</option>
<option>Valencia</option>
<option>Roma</option>
<option>Shakhtar Donetsk</option>

<option>Dynamo Kiev</option>
<option>Steaua Bucarest</option>
<option>Lione</option>
<option>Real Madrid</option>
<option>Fc Copenhagen</option>
<option>Benfica</option>
<option>Manchester United</option>
<option>Celtic Glasgow</option>
<option>Amburgo</option>

<option>Arsenal</option>
<option>Porto</option>
<option>Cska Mosca</option>
<option>Anderlecht</option>
<option>Lille</option>
<option>Milan</option>
<option>Aek Atene</option>
</select>



</td>
  <td colspan=2 class=xl39>



<!-- #6 -->
<select name='question_05' style="width:100pt;">
<option>&nbsp;</option>
<option>Barcellona</option>
<option>Levski Sofia</option>
<option>Chelsea</option>
<option>Werder Brema</option>
<option>Bayern Monaco</option>
<option>Spartak Mosca</option>
<option>Sporting Lisbona</option>

<option>Inter</option>
<option>Galatasaray</option>
<option>Bordeaux</option>
<option>Psv Eindhoven</option>
<option>Liverpool</option>
<option>Olympiakos Pireo</option>
<option>Valencia</option>
<option>Roma</option>
<option>Shakhtar Donetsk</option>

<option>Dynamo Kiev</option>
<option>Steaua Bucarest</option>
<option>Lione</option>
<option>Real Madrid</option>
<option>Fc Copenhagen</option>
<option>Benfica</option>
<option>Manchester United</option>
<option>Celtic Glasgow</option>
<option>Amburgo</option>

<option>Arsenal</option>
<option>Porto</option>
<option>Cska Mosca</option>
<option>Anderlecht</option>
<option>Lille</option>
<option>Milan</option>
<option>Aek Atene</option>
</select>



</td>
  <td colspan=2 class=xl39>



<!-- #7 -->
<select name='question_06' style="width:100pt;">
<option>&nbsp;</option>
<option>Barcellona</option>
<option>Levski Sofia</option>
<option>Chelsea</option>
<option>Werder Brema</option>
<option>Bayern Monaco</option>
<option>Spartak Mosca</option>
<option>Sporting Lisbona</option>

<option>Inter</option>
<option>Galatasaray</option>
<option>Bordeaux</option>
<option>Psv Eindhoven</option>
<option>Liverpool</option>
<option>Olympiakos Pireo</option>
<option>Valencia</option>
<option>Roma</option>
<option>Shakhtar Donetsk</option>

<option>Dynamo Kiev</option>
<option>Steaua Bucarest</option>
<option>Lione</option>
<option>Real Madrid</option>
<option>Fc Copenhagen</option>
<option>Benfica</option>
<option>Manchester United</option>
<option>Celtic Glasgow</option>
<option>Amburgo</option>

<option>Arsenal</option>
<option>Porto</option>
<option>Cska Mosca</option>
<option>Anderlecht</option>
<option>Lille</option>
<option>Milan</option>
<option>Aek Atene</option>
</select>



</td>
  <td colspan=2 class=xl39>



<!-- #8 -->
<select name='question_07' style="width:100pt;">
<option>&nbsp;</option>
<option>Barcellona</option>
<option>Levski Sofia</option>
<option>Chelsea</option>
<option>Werder Brema</option>
<option>Bayern Monaco</option>
<option>Spartak Mosca</option>
<option>Sporting Lisbona</option>

<option>Inter</option>
<option>Galatasaray</option>
<option>Bordeaux</option>
<option>Psv Eindhoven</option>
<option>Liverpool</option>
<option>Olympiakos Pireo</option>
<option>Valencia</option>
<option>Roma</option>
<option>Shakhtar Donetsk</option>

<option>Dynamo Kiev</option>
<option>Steaua Bucarest</option>
<option>Lione</option>
<option>Real Madrid</option>
<option>Fc Copenhagen</option>
<option>Benfica</option>
<option>Manchester United</option>
<option>Celtic Glasgow</option>
<option>Amburgo</option>

<option>Arsenal</option>
<option>Porto</option>
<option>Cska Mosca</option>
<option>Anderlecht</option>
<option>Lille</option>
<option>Milan</option>
<option>Aek Atene</option>
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
<option>Barcellona</option>
<option>Levski Sofia</option>
<option>Chelsea</option>
<option>Werder Brema</option>
<option>Bayern Monaco</option>
<option>Spartak Mosca</option>
<option>Sporting Lisbona</option>

<option>Inter</option>
<option>Galatasaray</option>
<option>Bordeaux</option>
<option>Psv Eindhoven</option>
<option>Liverpool</option>
<option>Olympiakos Pireo</option>
<option>Valencia</option>
<option>Roma</option>
<option>Shakhtar Donetsk</option>

<option>Dynamo Kiev</option>
<option>Steaua Bucarest</option>
<option>Lione</option>
<option>Real Madrid</option>
<option>Fc Copenhagen</option>
<option>Benfica</option>
<option>Manchester United</option>
<option>Celtic Glasgow</option>
<option>Amburgo</option>

<option>Arsenal</option>
<option>Porto</option>
<option>Cska Mosca</option>
<option>Anderlecht</option>
<option>Lille</option>
<option>Milan</option>
<option>Aek Atene</option>
</select>



</td>
  <td colspan=2 class=xl39>



<!-- #10 -->
<select name='question_09' style="width:100pt;">
<option>&nbsp;</option>
<option>Barcellona</option>
<option>Levski Sofia</option>
<option>Chelsea</option>
<option>Werder Brema</option>
<option>Bayern Monaco</option>
<option>Spartak Mosca</option>
<option>Sporting Lisbona</option>

<option>Inter</option>
<option>Galatasaray</option>
<option>Bordeaux</option>
<option>Psv Eindhoven</option>
<option>Liverpool</option>
<option>Olympiakos Pireo</option>
<option>Valencia</option>
<option>Roma</option>
<option>Shakhtar Donetsk</option>

<option>Dynamo Kiev</option>
<option>Steaua Bucarest</option>
<option>Lione</option>
<option>Real Madrid</option>
<option>Fc Copenhagen</option>
<option>Benfica</option>
<option>Manchester United</option>
<option>Celtic Glasgow</option>
<option>Amburgo</option>

<option>Arsenal</option>
<option>Porto</option>
<option>Cska Mosca</option>
<option>Anderlecht</option>
<option>Lille</option>
<option>Milan</option>
<option>Aek Atene</option>
</select>



</td>
  <td colspan=2 class=xl39>



<!-- #11 -->
<select name='question_10' style="width:100pt;">
<option>&nbsp;</option>
<option>Barcellona</option>
<option>Levski Sofia</option>
<option>Chelsea</option>
<option>Werder Brema</option>
<option>Bayern Monaco</option>
<option>Spartak Mosca</option>
<option>Sporting Lisbona</option>

<option>Inter</option>
<option>Galatasaray</option>
<option>Bordeaux</option>
<option>Psv Eindhoven</option>
<option>Liverpool</option>
<option>Olympiakos Pireo</option>
<option>Valencia</option>
<option>Roma</option>
<option>Shakhtar Donetsk</option>

<option>Dynamo Kiev</option>
<option>Steaua Bucarest</option>
<option>Lione</option>
<option>Real Madrid</option>
<option>Fc Copenhagen</option>
<option>Benfica</option>
<option>Manchester United</option>
<option>Celtic Glasgow</option>
<option>Amburgo</option>

<option>Arsenal</option>
<option>Porto</option>
<option>Cska Mosca</option>
<option>Anderlecht</option>
<option>Lille</option>
<option>Milan</option>
<option>Aek Atene</option>
</select>



</td>
  <td colspan=2 class=xl39>



<!-- #12 -->
<select name='question_11' style="width:100pt;">
<option>&nbsp;</option>
<option>Barcellona</option>
<option>Levski Sofia</option>
<option>Chelsea</option>
<option>Werder Brema</option>
<option>Bayern Monaco</option>
<option>Spartak Mosca</option>
<option>Sporting Lisbona</option>

<option>Inter</option>
<option>Galatasaray</option>
<option>Bordeaux</option>
<option>Psv Eindhoven</option>
<option>Liverpool</option>
<option>Olympiakos Pireo</option>
<option>Valencia</option>
<option>Roma</option>
<option>Shakhtar Donetsk</option>

<option>Dynamo Kiev</option>
<option>Steaua Bucarest</option>
<option>Lione</option>
<option>Real Madrid</option>
<option>Fc Copenhagen</option>
<option>Benfica</option>
<option>Manchester United</option>
<option>Celtic Glasgow</option>
<option>Amburgo</option>

<option>Arsenal</option>
<option>Porto</option>
<option>Cska Mosca</option>
<option>Anderlecht</option>
<option>Lille</option>
<option>Milan</option>
<option>Aek Atene</option>
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
<option>Barcellona</option>
<option>Levski Sofia</option>
<option>Chelsea</option>
<option>Werder Brema</option>
<option>Bayern Monaco</option>
<option>Spartak Mosca</option>
<option>Sporting Lisbona</option>

<option>Inter</option>
<option>Galatasaray</option>
<option>Bordeaux</option>
<option>Psv Eindhoven</option>
<option>Liverpool</option>
<option>Olympiakos Pireo</option>
<option>Valencia</option>
<option>Roma</option>
<option>Shakhtar Donetsk</option>

<option>Dynamo Kiev</option>
<option>Steaua Bucarest</option>
<option>Lione</option>
<option>Real Madrid</option>
<option>Fc Copenhagen</option>
<option>Benfica</option>
<option>Manchester United</option>
<option>Celtic Glasgow</option>
<option>Amburgo</option>

<option>Arsenal</option>
<option>Porto</option>
<option>Cska Mosca</option>
<option>Anderlecht</option>
<option>Lille</option>
<option>Milan</option>
<option>Aek Atene</option>
</select>



</td>
  <td colspan=2 class=xl39>



<!-- #14 -->
<select name='question_13' style="width:100pt;">
<option>&nbsp;</option>
<option>Barcellona</option>
<option>Levski Sofia</option>
<option>Chelsea</option>
<option>Werder Brema</option>
<option>Bayern Monaco</option>
<option>Spartak Mosca</option>
<option>Sporting Lisbona</option>

<option>Inter</option>
<option>Galatasaray</option>
<option>Bordeaux</option>
<option>Psv Eindhoven</option>
<option>Liverpool</option>
<option>Olympiakos Pireo</option>
<option>Valencia</option>
<option>Roma</option>
<option>Shakhtar Donetsk</option>

<option>Dynamo Kiev</option>
<option>Steaua Bucarest</option>
<option>Lione</option>
<option>Real Madrid</option>
<option>Fc Copenhagen</option>
<option>Benfica</option>
<option>Manchester United</option>
<option>Celtic Glasgow</option>
<option>Amburgo</option>

<option>Arsenal</option>
<option>Porto</option>
<option>Cska Mosca</option>
<option>Anderlecht</option>
<option>Lille</option>
<option>Milan</option>
<option>Aek Atene</option>
</select>



</td>
  <td>&nbsp;</td>
  <td colspan=2 class=xl39 style='border-top:none'>



<!-- #15 -->
<select name='question_14' style="width:100pt;">
<option>&nbsp;</option>
<option>Barcellona</option>
<option>Levski Sofia</option>
<option>Chelsea</option>
<option>Werder Brema</option>
<option>Bayern Monaco</option>
<option>Spartak Mosca</option>
<option>Sporting Lisbona</option>

<option>Inter</option>
<option>Galatasaray</option>
<option>Bordeaux</option>
<option>Psv Eindhoven</option>
<option>Liverpool</option>
<option>Olympiakos Pireo</option>
<option>Valencia</option>
<option>Roma</option>
<option>Shakhtar Donetsk</option>

<option>Dynamo Kiev</option>
<option>Steaua Bucarest</option>
<option>Lione</option>
<option>Real Madrid</option>
<option>Fc Copenhagen</option>
<option>Benfica</option>
<option>Manchester United</option>
<option>Celtic Glasgow</option>
<option>Amburgo</option>

<option>Arsenal</option>
<option>Porto</option>
<option>Cska Mosca</option>
<option>Anderlecht</option>
<option>Lille</option>
<option>Milan</option>
<option>Aek Atene</option>
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
		Data di ricezione giocata (hh:mm gg/mm/aaaa):
		<input type="edit" name="data_giocata" value="12:00 13/10/1974" class='x137'>
		</b>
	<br>
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
  <td class=xl26 colspan=7>Il
  presente studio/sondaggio è proposto dall'ARS Amatori Running Sele a puro
  scopo ricreativo e di approfondimento. </td>
  <td colspan=2></td>
  <td></td>
  <td colspan=2></td>
 </tr>
 <tr style='height:12.0pt'>
  <td style='height:12.0pt'></td>
  <td class=xl26 colspan=10>Lo spirito è quello di
  individuare il pronosticatore più bravo in base a criteri basati sul merito,
  ma non bisogna dimenticare che esiste una accentuata</td>
  <td colspan=2></td>
 </tr>
 <tr style='height:12.0pt'>
  <td style='height:12.0pt'></td>
  <td class=xl26 colspan=10>componente aleatoria
  insita nella stessa formula della Champions, che prevede il sorteggio
  integrale degli abbinamenti delle squadre nei quarti di finale.</td>
  <td colspan=2></td>
 </tr>
 <tr style='height:6.0pt'>
  <td style='height:6.0pt'></td>
  <td></td>
  <td colspan=8></td>
  <td></td>
  <td colspan=2></td>
 </tr>
 
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
  <td class=xl26 colspan=10>Tutte
  le giocate regolarmente effettuate, verranno ordinate in forma di classifica
  (disponibile sul sito www.ars.altervista.org), seguendo, nell'ordine, </td>
  <td colspan=2></td>
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
  <td class=xl47 colspan=10>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;- Il punteggio complessivo sarà
  calcolato attribuendo <font class=font14>4</font><font class=font7> punti per
  ognuno dei 15 pronostici formulati che si rivelino esatti </font><font
  class=font20>(punteggio massimo 60 punti);</font></td>
  <td colspan=2></td>
 </tr>
 <tr style='height:12.75pt'>
  <td style='height:12.75pt'></td>
  <td class=xl26 colspan=7>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;- Nel caso di passaggio del turno
  ottenuto avvalendosi della regola del goal in trasferta, i punti attribuiti
  saranno <font class=font14>3</font><font class=font7>;</font></td>
  <td colspan=2></td>
  <td></td>
  <td colspan=2></td>
 </tr>
 <tr style='height:12.75pt'>
  <td style='height:12.75pt'></td>
  <td class=xl26 colspan=7>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;- Nell'eventualità di passaggio del
  turno ai rigori, i punti saranno <font class=font14>2</font><font
  class=font7> sia per la vincente che per l'eliminata;</font></td>
  <td colspan=2></td>
  <td></td>
  <td colspan=2></td>
 </tr>
 <tr style='height:12.75pt'>
  <td style='height:12.75pt'></td>
  <td class=xl26 colspan=7>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;- Verrà infine riconosciuto <font
  class=font14>1</font><font class=font7> punto anche alla squadra indicata che
  viene eliminata per effetto della regola del goal in trasferta.</font></td>
  <td colspan=2></td>
  <td></td>
  <td colspan=2></td>
 </tr>
 <tr style='height:12.75pt'>
  <td style='height:12.75pt'></td>
  <td class=xl26 colspan=3>A parità di punteggio
  verranno considerati nell'ordine:</td>
  <td colspan=6></td>
  <td></td>
  <td colspan=2></td>
 </tr>
 <tr style='height:12.75pt'>
  <td style='height:12.75pt'></td>
  <td class=xl26 colspan=5>1. Il maggior numero di
  squadre indovinate a partire dalla squadra vincitrice;</td>
  <td colspan=4></td>
  <td></td>
  <td colspan=2></td>
 </tr>
 <tr style='height:12.75pt'>
  <td style='height:12.75pt'></td>
  <td class=xl26 colspan=10>2.
  La schedina che reca la data di giocata più antica. Per non danneggiare chi
  dovesse venire a conoscenza del sondaggio solo in un secondo momento, </td>
  <td colspan=2></td>
 </tr>
 <tr style='height:12.75pt'>
  <td style='height:12.75pt'></td>
  <td class=xl26 colspan=7>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;tutte le giocate effettuate entro le
  ore 24 del 01/01/2007, recheranno comunque la data di presentazione 1 gennaio
  2007</td>
  <td colspan=2></td>
  <td></td>
  <td colspan=2></td>
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
  <td class=xl48 colspan=3>Fine giocate: 31.01.2007</td>
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
 <tr style='height:12.75pt'>
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
 </tr>
 <tr style='height:12.75pt'>
  <td style='height:12.75pt'></td>
  <td></td>
  <td colspan=8></td>
  <td></td>
  <td colspan=2></td>
 </tr>
 <tr style='height:12.75pt'>
  <td style='height:12.75pt'></td>
  <td class=xl61 colspan=7>**
  Saranno gradite osservazioni, suggerimenti e collaborazioni (anche minime e
  saltuarie) per iniziative analoghe o di altro tipo.</td>
  <td colspan=2></td>
  <td></td>
  <td colspan=2></td>
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
