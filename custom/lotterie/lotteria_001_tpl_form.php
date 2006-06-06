<?php

/*/ input dall'esterno:
$admin_mode	: se vera vengono chieste anche la data e la password della giocata
$info_mode	: visualizza solo le domande
*/

// preparazione variabili per riagganciarsi alla struttura di salvataggio
$question_action = "save";	// da non modificare !!!
if ($admin_mode)
{
	$action = "../questions.php";	// da non modificare
}
else
{
	$action = "questions.php";	// da non modificare
}

$info_mode = $_REQUEST['info_mode'];


if ($info_mode)
{
	require_once('../../libreria.php');
}
elseif ($admin_mode)
{
	require_once('../libreria.php');
}
else
{
	require_once('libreria.php');
}


// nome dei campi
$question_tag_format = "question_%02d";	// da non modificare

$id_questions = 1;	// numero della lotteria corrente (usato in lotteria_XXX.txt)

// $helper_msg = 'Per aiutarti nella giocata, usa l\'utile strumento disponibile su Caposeleonline:file:///home/ceres/Desktop/tab-mond-06.htm';
// $data_msg = 'file:///home/ceres/Desktop/tab-mond-06.htm';

// print_r($lotteria["msg_custom"]);
$alert_msg = $lotteria["msg_custom"][0][0];
$helper_msg = $lotteria["msg_custom"][0][1];
$data_msg = $lotteria["msg_custom"][0][2];
?>


<!--
//
// inizio visualizzazione della form
//
-->
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 3.2//EN">

<HTML>
<HEAD>
	
	<META HTTP-EQUIV="CONTENT-TYPE" CONTENT="text/html; charset=iso-8859-1">
	<title><?php echo $web_title ?> - <?php echo $lotteria_nome ?></title>
	<META NAME="GENERATOR" CONTENT="OpenOffice.org 2.0  (Linux)">
	<META NAME="AUTHOR" CONTENT="Pasquale Ceres, Alessandro Russomanno">
	<META NAME="CREATED" CONTENT="20060407;15292000">
	<META NAME="CHANGED" CONTENT="20060407;16373600">
	
	<STYLE TYPE="text/css">
		BODY,DIV,TABLE,THEAD,TBODY,TFOOT,TR,TH,TD,P,INPUT { font-family:"Arial"; font-size:x-small; }
	</STYLE>
	
</HEAD>

<BODY TEXT="#000000" <?php
if (!empty($alert_msg))
{
	echo "OnLoad=\"alert('".str_replace("'","\\'",$alert_msg)."');\""; 
}
?>>
<!--BODY TEXT="#000000"-->

<!--
//
// Script per il check dei campi 
//
-->
<SCRIPT type=text/javascript>
<!-- 

function get_girone(squadra)
{
// restituisci il girone [0..7] di appartenenza della squadra (oppure -1)
	
	gironeA = new Array("Germania","Costa Rica","Polonia","Ecuador");
	gironeB = new Array("Inghilterra","Paraguay","Trinidad E Tobago","Svezia");
	gironeC = new Array("Argentina","Costa D'Avorio","Serbia Montenegro","Olanda");
	gironeD = new Array("Messico","Iran","Angola","Portogallo");
	gironeE = new Array("Stati Uniti","Repubblica Ceca","Italia","Ghana");
	gironeF = new Array("Australia","Giappone","Brasile","Croazia");
	gironeG = new Array("Corea Del Sud","Togo","Francia","Svizzera");
	gironeH = new Array("Spagna","Ucraina","Tunisia","Arabia Saudita");
	
	gironi = new Array(gironeA,gironeB,gironeC,gironeD,gironeE,gironeF,gironeG,gironeH);
	
	for (id_girone in gironi)
	{
		girone = gironi[id_girone];
		for (id_squadra in girone)
		{
			squadra_item = girone[id_squadra];
			if (squadra_item == squadra)
			{
				//alert(id_girone);
				return id_girone*1;
			}
		}
	}
	
	return -1;
}


function occurrencies(ago,pagliaio)
{
// conta quante volte e' stata data la stessa risposta
	count = 0;
	for (id in pagliaio)
	{
		if (pagliaio[id] == ago)
		{
			count++;
		}
	}
	return count;
}


function check_input(f)
{
// Questa funzione verifica la correttezza della giocata prima di inviare i dati per il salvataggio
	
	index_check_gironi_ok = 0;
	allow_errors = new Array; // 0 -> l'errore non permette il salvataggio; 1 -> viene visualizzato soltanto un warning
	
	allow_errors[index_check_gironi_ok] = 0; // mettere a 0 per impedire di giocare con un errore alla regola "due squadre per ciascun girone"
	
	list = new Array;
	for (ii = 0; ii < f.elements.length; ii++)
	{
		if (ii<10)
		{
			tag = 'question_0'+ii;
		}
		else
		{
			tag = 'question_'+ii;
		}
		
		if (f[tag])
		{
			squadra = f[tag].options[f[tag].selectedIndex].text;
			//alert(ii+') '+tag+' ('+squadra+')');
			
			list[ii] = squadra;
			
			// verifica che non ci siano campi vuoti
			if (squadra.length == 1)
			{
				alert("Attenzione: la giocata non e' regolare perche' c'e' almeno un campo vuoto! Correggere prima il problema");
				return false;
			}
		}
	}
	
	// verifica congruenza delle risposte
	risposte_ok = true;
	messaggio_errore = 'Messaggio di errore!';
	

	
	// ripartizione nelle diverse classi
	list_Q = new Array;
	list_W = new Array;
	list_S = new Array;
	list_F = new Array;
	list_C = new Array;
	for (i = 0; i < list.length; i++)
	{
		
		if (i < 16)
		{
			list_Q[list_Q.length] = list[i];
		}
		else if (i < 24)
		{
			list_W[list_W.length] = list[i];
		}
		else if (i < 28)
		{
			list_S[list_S.length] = list[i];
		}
		else if (i < 30)
		{
			list_F[list_F.length] = list[i];
		}
		else
		{
			list_C[0] = list[i];
		}
	}
	
	// verifica correttezza 16 squadre ammesse ottavi di finale
	vettore_gironi = new Array(0,0,0,0,0,0,0,0);
	gironi_errati = false;
	for (i = 0; i < list_Q.length; i++)
	{
		squadra = list_Q[i];
		//alert('Qualificati ('+(i+1)+'): '+squadra);
		
		girone = get_girone(squadra);
		if ((++vettore_gironi[girone]) > 2)
		{
			gironi_errati = true;
		}
		
		// verifica ripetizioni all'interno dello stesso gruppo
		ripetizioni = occurrencies(squadra,list_Q);
		if (ripetizioni > 1)
		{
			alert('Nei qualificati agli ottavi di finale la squadra '+squadra+' compare '+ripetizioni+' volte!');
			return false;
		}
		
	}
	
	// visualizza messaggio d'errore nel caso non siano indicate 2 squadre per girone
	if (gironi_errati)
	{
		msg = "Nei qualificati agli ottavi di finale non sono indicate 2 squadre per ciascun girone:";
		for (i=0; i<=7; i++)
		{
			msg += "\n    Girone "+String.fromCharCode(i+65)+': '+vettore_gironi[i]; 
			
			errore = vettore_gironi[i]-2;
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
		if ((!0<?php echo $admin_mode; ?>) & (!allow_errors[index_check_gironi_ok]))
		{
			return false;
		}
	}
	
	
	// verifica correttezza 8 squadre ammesse quarti di finale
	for (i = 0; i < list_W.length; i++)
	{
		squadra = list_W[i];
		//alert('Qualificati ('+(i+1)+'): '+squadra);
		
		// verifica ripetizioni all'interno dello stesso gruppo
		ripetizioni = occurrencies(squadra,list_W);
		if (ripetizioni > 1)
		{
			alert('Nei qualificati ai quarti di finale la squadra '+squadra+' compare '+ripetizioni+' volte!');
			return false;
		}
		
		// verifica la presenza all'interno del gruppo precedente
		ripetizioni = occurrencies(squadra,list_Q);
		if (ripetizioni != 1)
		{
			alert('La squadra '+squadra+" compare tra le squadre qualificate ai quarti, ma non e' presente tra quelle agli ottavi!");
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
		ripetizioni = occurrencies(squadra,list_W);
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
	
	
	
	if (!risposte_ok)
	{
		alert(messaggio_errore);
	}
	else
	{
		return confirm("Sei sicuro? Una volta confermato la giocata sara' definitiva.");
	}
	return risposte_ok;
	
}


//-->
</SCRIPT>


<!--div align="center" style="font-size:20px;">Sondaggio Mondiali di Calcio 2006 (32 domande)</div><br-->

<form name="question_form" action="<?php echo $action; ?>" method="post" OnSubmit="return check_input(this)">


<TABLE FRAME=VOID CELLSPACING=0 RULES=GROUPS BORDER=1>
	<COLGROUP><COL WIDTH=125><COL WIDTH=125><COL WIDTH=125><COL WIDTH=125><COL WIDTH=17><COL WIDTH=80><COL WIDTH=95><COL WIDTH=110></COLGROUP>
	<TBODY>
		<TR>
			<TD COLSPAN=8 WIDTH=801 HEIGHT=23 ALIGN=CENTER><FONT SIZE=4>I Mondiali di calcio 2006 in Germania - Scheda per il sondaggio -</FONT></TD>
			</TR>
<?php
if (!empty($nominativo)) { ?>
		<TR>
			<TD COLSPAN=4 HEIGHT=16 ALIGN=LEFT><b>Benvenuto <?php echo $nominativo ?></b></TD>
			<TD ALIGN=LEFT><BR></TD>
			<TD ALIGN=LEFT><BR></TD>
			<TD ALIGN=LEFT><BR></TD>
			<TD ALIGN=LEFT><BR></TD>
		</TR>
<?php
}
?>
		<TR>
			<TD COLSPAN=4 HEIGHT=16 ALIGN=LEFT>La composizione degli otto gironi eliminatori</TD>
			<TD ALIGN=LEFT><BR></TD>
			<TD ALIGN=LEFT><BR></TD>
			<TD ALIGN=LEFT><BR></TD>
			<TD ALIGN=LEFT><BR></TD>
		</TR>
		<TR>
			<TD HEIGHT=18 ALIGN=CENTER BGCOLOR="#FFFF99"><FONT FACE="Arial Narrow">Gruppo A</FONT></TD>
			<TD ALIGN=CENTER BGCOLOR="#FFFF99"><FONT FACE="Arial Narrow">Gruppo B</FONT></TD>
			<TD ALIGN=CENTER BGCOLOR="#FFFF99"><FONT FACE="Arial Narrow">Gruppo C</FONT></TD>
			<TD ALIGN=CENTER BGCOLOR="#FFFF99"><FONT FACE="Arial Narrow">Gruppo D</FONT></TD>
			<TD ALIGN=LEFT><BR></TD>
			<TD COLSPAN=3 ALIGN=CENTER BGCOLOR="#CCFFFF"><B>Gli abbinamenti per gli Ottavi di Finale</B></TD>
			</TR>
		<TR>
			<TD HEIGHT=18 ALIGN=LEFT BGCOLOR="#E6E6FF">Germania</TD>
			<TD ALIGN=LEFT BGCOLOR="#E6E6FF">Inghilterra</TD>
			<TD ALIGN=LEFT BGCOLOR="#E6E6FF">Argentina</TD>
			<TD ALIGN=LEFT BGCOLOR="#E6E6FF">Messico</TD>
			<TD ALIGN=LEFT><BR></TD>
			<TD ALIGN=CENTER BGCOLOR="#CCFFFF"><I>data</I></TD>
			<TD ALIGN=CENTER BGCOLOR="#CCFFFF"><I>citt&agrave;</I></TD>
			<TD ALIGN=CENTER BGCOLOR="#CCFFFF"><I>match</I></TD>
		</TR>
		<TR>
			<TD HEIGHT=18 ALIGN=LEFT BGCOLOR="#E6E6FF">Polonia</TD>
			<TD ALIGN=LEFT BGCOLOR="#E6E6FF">Trinidad e Tobago</TD>
			<TD ALIGN=LEFT BGCOLOR="#E6E6FF">Costa d'Avorio</TD>
			<TD ALIGN=LEFT BGCOLOR="#E6E6FF">Iran</TD>
			<TD ALIGN=LEFT><BR></TD>
			<TD ALIGN=LEFT BGCOLOR="#CCFFFF">24/06 h. 17</TD>
			<TD ALIGN=LEFT BGCOLOR="#CCFFFF">Munich</TD>
			<TD ALIGN=CENTER BGCOLOR="#CCFFFF">1gr.A-2gr.B (W1)</TD>
		</TR>
		<TR>
			<TD HEIGHT=18 ALIGN=LEFT BGCOLOR="#E6E6FF">Costarica</TD>
			<TD ALIGN=LEFT BGCOLOR="#E6E6FF">Paraguay</TD>
			<TD ALIGN=LEFT BGCOLOR="#E6E6FF">Serbia e Montenegro</TD>
			<TD ALIGN=LEFT BGCOLOR="#E6E6FF">Angola</TD>
			<TD ALIGN=LEFT><BR></TD>
			<TD ALIGN=LEFT BGCOLOR="#CCFFFF">24/06 h. 21</TD>
			<TD ALIGN=LEFT BGCOLOR="#CCFFFF">Leipzig</TD>
			<TD ALIGN=CENTER BGCOLOR="#CCFFFF">1gr.C-2gr.D (W2)</TD>
		</TR>
		<TR>
			<TD HEIGHT=18 ALIGN=LEFT BGCOLOR="#E6E6FF">Ecuador</TD>
			<TD ALIGN=LEFT BGCOLOR="#E6E6FF">Svezia</TD>
			<TD ALIGN=LEFT BGCOLOR="#E6E6FF">Olanda</TD>
			<TD ALIGN=LEFT BGCOLOR="#E6E6FF">Portogallo</TD>
			<TD ALIGN=LEFT><BR></TD>
			<TD ALIGN=LEFT BGCOLOR="#CCFFFF">25/06 h. 17</TD>
			<TD ALIGN=LEFT BGCOLOR="#CCFFFF">St&uuml;ttgart</TD>
			<TD ALIGN=CENTER BGCOLOR="#CCFFFF">1gr.B-2gr.A (W5)</TD>
		</TR>
		<TR>
			<TD HEIGHT=18 ALIGN=CENTER BGCOLOR="#FFFF99"><FONT FACE="Arial Narrow">Gruppo E</FONT></TD>
			<TD ALIGN=CENTER BGCOLOR="#FFFF99"><FONT FACE="Arial Narrow">Gruppo F</FONT></TD>
			<TD ALIGN=CENTER BGCOLOR="#FFFF99"><FONT FACE="Arial Narrow">Gruppo G</FONT></TD>
			<TD ALIGN=CENTER BGCOLOR="#FFFF99"><FONT FACE="Arial Narrow">Gruppo H</FONT></TD>
			<TD ALIGN=LEFT><BR></TD>
			<TD ALIGN=LEFT BGCOLOR="#CCFFFF">25/06 h. 21</TD>
			<TD ALIGN=LEFT BGCOLOR="#CCFFFF">N&uuml;rnberg</TD>
			<TD ALIGN=CENTER BGCOLOR="#CCFFFF">1gr.D-2gr.C (W6)</TD>
		</TR>
		<TR>
			<TD HEIGHT=18 ALIGN=LEFT BGCOLOR="#E6E6E6">Italia</TD>
			<TD ALIGN=LEFT BGCOLOR="#E6E6E6">Brasile</TD>
			<TD ALIGN=LEFT BGCOLOR="#E6E6E6">Francia</TD>
			<TD ALIGN=LEFT BGCOLOR="#E6E6E6">Spagna</TD>
			<TD ALIGN=LEFT><BR></TD>
			<TD ALIGN=LEFT BGCOLOR="#CCFFFF">26/06 h. 17</TD>
			<TD ALIGN=LEFT BGCOLOR="#CCFFFF">Kaiserslautern</TD>
			<TD ALIGN=CENTER BGCOLOR="#CCFFFF">1gr.E-2gr.F (W3)</TD>
		</TR>
		<TR>
			<TD HEIGHT=18 ALIGN=LEFT BGCOLOR="#E6E6E6">Ghana</TD>
			<TD ALIGN=LEFT BGCOLOR="#E6E6E6">Croazia</TD>
			<TD ALIGN=LEFT BGCOLOR="#E6E6E6">Svizzera</TD>
			<TD ALIGN=LEFT BGCOLOR="#E6E6E6">Ucraina</TD>
			<TD ALIGN=LEFT><BR></TD>
			<TD ALIGN=LEFT BGCOLOR="#CCFFFF">26/06 h. 21</TD>
			<TD ALIGN=LEFT BGCOLOR="#CCFFFF">Cologne</TD>
			<TD ALIGN=CENTER BGCOLOR="#CCFFFF">1gr.G-2gr.H (W4)</TD>
		</TR>
		<TR>
			<TD HEIGHT=18 ALIGN=LEFT BGCOLOR="#E6E6E6">USA</TD>
			<TD ALIGN=LEFT BGCOLOR="#E6E6E6">Australia</TD>
			<TD ALIGN=LEFT BGCOLOR="#E6E6E6">Corea del Sud</TD>
			<TD ALIGN=LEFT BGCOLOR="#E6E6E6">Tunisia</TD>
			<TD ALIGN=LEFT><BR></TD>
			<TD ALIGN=LEFT BGCOLOR="#CCFFFF">27/06 h. 17</TD>
			<TD ALIGN=LEFT BGCOLOR="#CCFFFF">Dortmund</TD>
			<TD ALIGN=CENTER BGCOLOR="#CCFFFF">1gr.F-2gr.E (W7)</TD>
		</TR>
		<TR>
			<TD HEIGHT=18 ALIGN=LEFT BGCOLOR="#E6E6E6">Rep. Ceca</TD>
			<TD ALIGN=LEFT BGCOLOR="#E6E6E6">Giappone</TD>
			<TD ALIGN=LEFT BGCOLOR="#E6E6E6">Togo</TD>
			<TD ALIGN=LEFT BGCOLOR="#E6E6E6">Arabia Saudita</TD>
			<TD ALIGN=LEFT><BR></TD>
			<TD ALIGN=LEFT BGCOLOR="#CCFFFF">27/06 h. 21</TD>
			<TD ALIGN=LEFT BGCOLOR="#CCFFFF">Hannover</TD>
			<TD ALIGN=CENTER BGCOLOR="#CCFFFF">1gr.H-2gr.G (W8)</TD>
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
			<!--TD HEIGHT=16 ALIGN=LEFT><BR></TD>
			<TD ALIGN=LEFT><BR>2</TD>
			<TD ALIGN=LEFT><BR>3</TD>
			<TD ALIGN=LEFT><BR>4</TD-->
			<TD ALIGN=LEFT><BR></TD>
			<TD ALIGN=LEFT><BR></TD>
			<TD ALIGN=LEFT><BR></TD>
			<TD ALIGN=LEFT><BR></TD>
		</TR>
		<TR>
			<TD HEIGHT=16 ALIGN=LEFT><BR></TD>
			<TD ALIGN=LEFT><BR></TD>
			<TD ALIGN=LEFT><BR></TD>
			<TD ALIGN=LEFT><BR></TD>
			<TD ALIGN=LEFT><BR></TD>
			<TD COLSPAN=3 ALIGN=CENTER BGCOLOR="#CCFFCC"><B>Gli abbinamenti per i Quarti di Finale</B></TD>
			</TR>
		<TR>
			<TD COLSPAN=4 HEIGHT=16 ALIGN=LEFT><I>tra queste 32 squadre passeranno il turno le seguenti 16 (in ordine libero):</I></TD>
			<TD ALIGN=LEFT><BR></TD>
			<TD ALIGN=CENTER BGCOLOR="#CCFFCC"><I>data</I></TD>
			<TD ALIGN=CENTER BGCOLOR="#CCFFCC"><I>citt&agrave;</I></TD>
			<TD ALIGN=CENTER BGCOLOR="#CCFFCC"><I>match</I></TD>
		</TR>
		<TR>
			<TD HEIGHT=21 ALIGN=LEFT BGCOLOR="#CCFFFF">


<!-- Q1 -->
<select name="question_00" >
<option selected>&nbsp;</option>
<option>Stati Uniti</option>
<option>Repubblica Ceca</option>
<option>Italia</option>
<option>Ghana</option>
<option>Australia</option>
<option>Giappone</option>
<option>Brasile</option>
<option>Croazia</option>
<option>Corea Del Sud</option>
<option>Togo</option>
<option>Francia</option>
<option>Svizzera</option>
<option>Spagna</option>
<option>Ucraina</option>
<option>Tunisia</option>
<option>Arabia Saudita</option>
<option>Germania</option>
<option>Costa Rica</option>
<option>Polonia</option>
<option>Ecuador</option>
<option>Inghilterra</option>
<option>Paraguay</option>
<option>Trinidad E Tobago</option>
<option>Svezia</option>
<option>Argentina</option>
<option>Costa D'Avorio</option>
<option>Serbia Montenegro</option>
<option>Olanda</option>
<option>Messico</option>
<option>Iran</option>
<option>Angola</option>
<option>Portogallo</option>
</select>



</TD>
			<TD ALIGN=LEFT BGCOLOR="#CCFFFF">



<!-- Q2 -->
<select name="question_01" >
<option selected>&nbsp;</option>
<option>Stati Uniti</option>
<option>Repubblica Ceca</option>
<option>Italia</option>
<option>Ghana</option>
<option>Australia</option>
<option>Giappone</option>
<option>Brasile</option>
<option>Croazia</option>
<option>Corea Del Sud</option>
<option>Togo</option>
<option>Francia</option>
<option>Svizzera</option>
<option>Spagna</option>
<option>Ucraina</option>
<option>Tunisia</option>
<option>Arabia Saudita</option>
<option>Germania</option>
<option>Costa Rica</option>
<option>Polonia</option>
<option>Ecuador</option>
<option>Inghilterra</option>
<option>Paraguay</option>
<option>Trinidad E Tobago</option>
<option>Svezia</option>
<option>Argentina</option>
<option>Costa D'Avorio</option>
<option>Serbia Montenegro</option>
<option>Olanda</option>
<option>Messico</option>
<option>Iran</option>
<option>Angola</option>
<option>Portogallo</option>
</select>


</TD>
			<TD ALIGN=LEFT BGCOLOR="#CCFFFF">


<!-- Q3 -->
<select name="question_02" >
<option selected>&nbsp;</option>
<option>Stati Uniti</option>
<option>Repubblica Ceca</option>
<option>Italia</option>
<option>Ghana</option>
<option>Australia</option>
<option>Giappone</option>
<option>Brasile</option>
<option>Croazia</option>
<option>Corea Del Sud</option>
<option>Togo</option>
<option>Francia</option>
<option>Svizzera</option>
<option>Spagna</option>
<option>Ucraina</option>
<option>Tunisia</option>
<option>Arabia Saudita</option>
<option>Germania</option>
<option>Costa Rica</option>
<option>Polonia</option>
<option>Ecuador</option>
<option>Inghilterra</option>
<option>Paraguay</option>
<option>Trinidad E Tobago</option>
<option>Svezia</option>
<option>Argentina</option>
<option>Costa D'Avorio</option>
<option>Serbia Montenegro</option>
<option>Olanda</option>
<option>Messico</option>
<option>Iran</option>
<option>Angola</option>
<option>Portogallo</option>
</select>


</TD>
			<TD ALIGN=LEFT BGCOLOR="#CCFFFF">


<!-- Q4 -->
<select name="question_03" >
<option selected>&nbsp;</option>
<option>Stati Uniti</option>
<option>Repubblica Ceca</option>
<option>Italia</option>
<option>Ghana</option>
<option>Australia</option>
<option>Giappone</option>
<option>Brasile</option>
<option>Croazia</option>
<option>Corea Del Sud</option>
<option>Togo</option>
<option>Francia</option>
<option>Svizzera</option>
<option>Spagna</option>
<option>Ucraina</option>
<option>Tunisia</option>
<option>Arabia Saudita</option>
<option>Germania</option>
<option>Costa Rica</option>
<option>Polonia</option>
<option>Ecuador</option>
<option>Inghilterra</option>
<option>Paraguay</option>
<option>Trinidad E Tobago</option>
<option>Svezia</option>
<option>Argentina</option>
<option>Costa D'Avorio</option>
<option>Serbia Montenegro</option>
<option>Olanda</option>
<option>Messico</option>
<option>Iran</option>
<option>Angola</option>
<option>Portogallo</option>
</select>



</TD>
			<TD ALIGN=LEFT ><BR></TD>
			<TD ALIGN=LEFT BGCOLOR="#CCFFCC">30/06 h. 17</TD>
			<TD ALIGN=LEFT BGCOLOR="#CCFFCC">Berlin</TD>
			<TD ALIGN=CENTER BGCOLOR="#CCFFCC">W1-W2 (S1)</TD>
		</TR>
		<TR>
			<TD HEIGHT=21 ALIGN=LEFT BGCOLOR="#CCFFFF">


<!-- Q5 -->
<select name="question_04" >
<option selected>&nbsp;</option>
<option>Stati Uniti</option>
<option>Repubblica Ceca</option>
<option>Italia</option>
<option>Ghana</option>
<option>Australia</option>
<option>Giappone</option>
<option>Brasile</option>
<option>Croazia</option>
<option>Corea Del Sud</option>
<option>Togo</option>
<option>Francia</option>
<option>Svizzera</option>
<option>Spagna</option>
<option>Ucraina</option>
<option>Tunisia</option>
<option>Arabia Saudita</option>
<option>Germania</option>
<option>Costa Rica</option>
<option>Polonia</option>
<option>Ecuador</option>
<option>Inghilterra</option>
<option>Paraguay</option>
<option>Trinidad E Tobago</option>
<option>Svezia</option>
<option>Argentina</option>
<option>Costa D'Avorio</option>
<option>Serbia Montenegro</option>
<option>Olanda</option>
<option>Messico</option>
<option>Iran</option>
<option>Angola</option>
<option>Portogallo</option>
</select>




</TD>
			<TD ALIGN=LEFT BGCOLOR="#CCFFFF">


<!-- Q6 -->
<select name="question_05" >
<option selected>&nbsp;</option>
<option>Stati Uniti</option>
<option>Repubblica Ceca</option>
<option>Italia</option>
<option>Ghana</option>
<option>Australia</option>
<option>Giappone</option>
<option>Brasile</option>
<option>Croazia</option>
<option>Corea Del Sud</option>
<option>Togo</option>
<option>Francia</option>
<option>Svizzera</option>
<option>Spagna</option>
<option>Ucraina</option>
<option>Tunisia</option>
<option>Arabia Saudita</option>
<option>Germania</option>
<option>Costa Rica</option>
<option>Polonia</option>
<option>Ecuador</option>
<option>Inghilterra</option>
<option>Paraguay</option>
<option>Trinidad E Tobago</option>
<option>Svezia</option>
<option>Argentina</option>
<option>Costa D'Avorio</option>
<option>Serbia Montenegro</option>
<option>Olanda</option>
<option>Messico</option>
<option>Iran</option>
<option>Angola</option>
<option>Portogallo</option>
</select>



</TD>
			<TD ALIGN=LEFT BGCOLOR="#CCFFFF">


<!-- Q7 -->
<select name="question_06" >
<option selected>&nbsp;</option>
<option>Stati Uniti</option>
<option>Repubblica Ceca</option>
<option>Italia</option>
<option>Ghana</option>
<option>Australia</option>
<option>Giappone</option>
<option>Brasile</option>
<option>Croazia</option>
<option>Corea Del Sud</option>
<option>Togo</option>
<option>Francia</option>
<option>Svizzera</option>
<option>Spagna</option>
<option>Ucraina</option>
<option>Tunisia</option>
<option>Arabia Saudita</option>
<option>Germania</option>
<option>Costa Rica</option>
<option>Polonia</option>
<option>Ecuador</option>
<option>Inghilterra</option>
<option>Paraguay</option>
<option>Trinidad E Tobago</option>
<option>Svezia</option>
<option>Argentina</option>
<option>Costa D'Avorio</option>
<option>Serbia Montenegro</option>
<option>Olanda</option>
<option>Messico</option>
<option>Iran</option>
<option>Angola</option>
<option>Portogallo</option>
</select>



</TD>
			<TD ALIGN=LEFT BGCOLOR="#CCFFFF">


<!-- Q8 -->
<select name="question_07" >
<option selected>&nbsp;</option>
<option>Stati Uniti</option>
<option>Repubblica Ceca</option>
<option>Italia</option>
<option>Ghana</option>
<option>Australia</option>
<option>Giappone</option>
<option>Brasile</option>
<option>Croazia</option>
<option>Corea Del Sud</option>
<option>Togo</option>
<option>Francia</option>
<option>Svizzera</option>
<option>Spagna</option>
<option>Ucraina</option>
<option>Tunisia</option>
<option>Arabia Saudita</option>
<option>Germania</option>
<option>Costa Rica</option>
<option>Polonia</option>
<option>Ecuador</option>
<option>Inghilterra</option>
<option>Paraguay</option>
<option>Trinidad E Tobago</option>
<option>Svezia</option>
<option>Argentina</option>
<option>Costa D'Avorio</option>
<option>Serbia Montenegro</option>
<option>Olanda</option>
<option>Messico</option>
<option>Iran</option>
<option>Angola</option>
<option>Portogallo</option>
</select>



</TD>
			<TD ALIGN=LEFT><BR></TD>
			<TD ALIGN=LEFT BGCOLOR="#CCFFCC">30/06 h. 21</TD>
			<TD ALIGN=LEFT BGCOLOR="#CCFFCC">Hamburg</TD>
			<TD ALIGN=CENTER BGCOLOR="#CCFFCC">W3-W4 (S2)</TD>
		</TR>
		<TR>
			<TD HEIGHT=21 ALIGN=LEFT BGCOLOR="#CCFFFF">


<!-- Q9 -->
<select name="question_08" >
<option selected>&nbsp;</option>
<option>Stati Uniti</option>
<option>Repubblica Ceca</option>
<option>Italia</option>
<option>Ghana</option>
<option>Australia</option>
<option>Giappone</option>
<option>Brasile</option>
<option>Croazia</option>
<option>Corea Del Sud</option>
<option>Togo</option>
<option>Francia</option>
<option>Svizzera</option>
<option>Spagna</option>
<option>Ucraina</option>
<option>Tunisia</option>
<option>Arabia Saudita</option>
<option>Germania</option>
<option>Costa Rica</option>
<option>Polonia</option>
<option>Ecuador</option>
<option>Inghilterra</option>
<option>Paraguay</option>
<option>Trinidad E Tobago</option>
<option>Svezia</option>
<option>Argentina</option>
<option>Costa D'Avorio</option>
<option>Serbia Montenegro</option>
<option>Olanda</option>
<option>Messico</option>
<option>Iran</option>
<option>Angola</option>
<option>Portogallo</option>
</select>



</TD>
			<TD ALIGN=LEFT BGCOLOR="#CCFFFF">


<!-- Q10 -->
<select name="question_09" >
<option selected>&nbsp;</option>
<option>Stati Uniti</option>
<option>Repubblica Ceca</option>
<option>Italia</option>
<option>Ghana</option>
<option>Australia</option>
<option>Giappone</option>
<option>Brasile</option>
<option>Croazia</option>
<option>Corea Del Sud</option>
<option>Togo</option>
<option>Francia</option>
<option>Svizzera</option>
<option>Spagna</option>
<option>Ucraina</option>
<option>Tunisia</option>
<option>Arabia Saudita</option>
<option>Germania</option>
<option>Costa Rica</option>
<option>Polonia</option>
<option>Ecuador</option>
<option>Inghilterra</option>
<option>Paraguay</option>
<option>Trinidad E Tobago</option>
<option>Svezia</option>
<option>Argentina</option>
<option>Costa D'Avorio</option>
<option>Serbia Montenegro</option>
<option>Olanda</option>
<option>Messico</option>
<option>Iran</option>
<option>Angola</option>
<option>Portogallo</option>
</select>



</TD>
			<TD ALIGN=LEFT BGCOLOR="#CCFFFF">


<!-- Q11 -->
<select name="question_10" >
<option selected>&nbsp;</option>
<option>Stati Uniti</option>
<option>Repubblica Ceca</option>
<option>Italia</option>
<option>Ghana</option>
<option>Australia</option>
<option>Giappone</option>
<option>Brasile</option>
<option>Croazia</option>
<option>Corea Del Sud</option>
<option>Togo</option>
<option>Francia</option>
<option>Svizzera</option>
<option>Spagna</option>
<option>Ucraina</option>
<option>Tunisia</option>
<option>Arabia Saudita</option>
<option>Germania</option>
<option>Costa Rica</option>
<option>Polonia</option>
<option>Ecuador</option>
<option>Inghilterra</option>
<option>Paraguay</option>
<option>Trinidad E Tobago</option>
<option>Svezia</option>
<option>Argentina</option>
<option>Costa D'Avorio</option>
<option>Serbia Montenegro</option>
<option>Olanda</option>
<option>Messico</option>
<option>Iran</option>
<option>Angola</option>
<option>Portogallo</option>
</select>


</TD>
			<TD ALIGN=LEFT BGCOLOR="#CCFFFF">


<!-- Q12 -->
<select name="question_11" >
<option selected>&nbsp;</option>
<option>Stati Uniti</option>
<option>Repubblica Ceca</option>
<option>Italia</option>
<option>Ghana</option>
<option>Australia</option>
<option>Giappone</option>
<option>Brasile</option>
<option>Croazia</option>
<option>Corea Del Sud</option>
<option>Togo</option>
<option>Francia</option>
<option>Svizzera</option>
<option>Spagna</option>
<option>Ucraina</option>
<option>Tunisia</option>
<option>Arabia Saudita</option>
<option>Germania</option>
<option>Costa Rica</option>
<option>Polonia</option>
<option>Ecuador</option>
<option>Inghilterra</option>
<option>Paraguay</option>
<option>Trinidad E Tobago</option>
<option>Svezia</option>
<option>Argentina</option>
<option>Costa D'Avorio</option>
<option>Serbia Montenegro</option>
<option>Olanda</option>
<option>Messico</option>
<option>Iran</option>
<option>Angola</option>
<option>Portogallo</option>
</select>



</TD>
			<TD ALIGN=LEFT><BR></TD>
			<TD ALIGN=LEFT BGCOLOR="#CCFFCC">01/07 h. 17</TD>
			<TD ALIGN=LEFT BGCOLOR="#CCFFCC">Gelsenkirchen</TD>
			<TD ALIGN=CENTER BGCOLOR="#CCFFCC">W5-W6 (S3)</TD>
		</TR>
		<TR>
			<TD HEIGHT=21 ALIGN=LEFT BGCOLOR="#CCFFFF">


<!-- Q13 -->
<select name="question_12" >
<option selected>&nbsp;</option>
<option>Stati Uniti</option>
<option>Repubblica Ceca</option>
<option>Italia</option>
<option>Ghana</option>
<option>Australia</option>
<option>Giappone</option>
<option>Brasile</option>
<option>Croazia</option>
<option>Corea Del Sud</option>
<option>Togo</option>
<option>Francia</option>
<option>Svizzera</option>
<option>Spagna</option>
<option>Ucraina</option>
<option>Tunisia</option>
<option>Arabia Saudita</option>
<option>Germania</option>
<option>Costa Rica</option>
<option>Polonia</option>
<option>Ecuador</option>
<option>Inghilterra</option>
<option>Paraguay</option>
<option>Trinidad E Tobago</option>
<option>Svezia</option>
<option>Argentina</option>
<option>Costa D'Avorio</option>
<option>Serbia Montenegro</option>
<option>Olanda</option>
<option>Messico</option>
<option>Iran</option>
<option>Angola</option>
<option>Portogallo</option>
</select>


</TD>
			<TD ALIGN=LEFT BGCOLOR="#CCFFFF">


<!-- Q14 -->
<select name="question_13" >
<option selected>&nbsp;</option>
<option>Stati Uniti</option>
<option>Repubblica Ceca</option>
<option>Italia</option>
<option>Ghana</option>
<option>Australia</option>
<option>Giappone</option>
<option>Brasile</option>
<option>Croazia</option>
<option>Corea Del Sud</option>
<option>Togo</option>
<option>Francia</option>
<option>Svizzera</option>
<option>Spagna</option>
<option>Ucraina</option>
<option>Tunisia</option>
<option>Arabia Saudita</option>
<option>Germania</option>
<option>Costa Rica</option>
<option>Polonia</option>
<option>Ecuador</option>
<option>Inghilterra</option>
<option>Paraguay</option>
<option>Trinidad E Tobago</option>
<option>Svezia</option>
<option>Argentina</option>
<option>Costa D'Avorio</option>
<option>Serbia Montenegro</option>
<option>Olanda</option>
<option>Messico</option>
<option>Iran</option>
<option>Angola</option>
<option>Portogallo</option>
</select>


</TD>
			<TD ALIGN=LEFT BGCOLOR="#CCFFFF">


<!-- Q15 -->
<select name="question_14" >
<option selected>&nbsp;</option>
<option>Stati Uniti</option>
<option>Repubblica Ceca</option>
<option>Italia</option>
<option>Ghana</option>
<option>Australia</option>
<option>Giappone</option>
<option>Brasile</option>
<option>Croazia</option>
<option>Corea Del Sud</option>
<option>Togo</option>
<option>Francia</option>
<option>Svizzera</option>
<option>Spagna</option>
<option>Ucraina</option>
<option>Tunisia</option>
<option>Arabia Saudita</option>
<option>Germania</option>
<option>Costa Rica</option>
<option>Polonia</option>
<option>Ecuador</option>
<option>Inghilterra</option>
<option>Paraguay</option>
<option>Trinidad E Tobago</option>
<option>Svezia</option>
<option>Argentina</option>
<option>Costa D'Avorio</option>
<option>Serbia Montenegro</option>
<option>Olanda</option>
<option>Messico</option>
<option>Iran</option>
<option>Angola</option>
<option>Portogallo</option>
</select>


</TD>
			<TD ALIGN=LEFT BGCOLOR="#CCFFFF">


<!-- Q16 -->
<select name="question_15" >
<option selected>&nbsp;</option>
<option>Stati Uniti</option>
<option>Repubblica Ceca</option>
<option>Italia</option>
<option>Ghana</option>
<option>Australia</option>
<option>Giappone</option>
<option>Brasile</option>
<option>Croazia</option>
<option>Corea Del Sud</option>
<option>Togo</option>
<option>Francia</option>
<option>Svizzera</option>
<option>Spagna</option>
<option>Ucraina</option>
<option>Tunisia</option>
<option>Arabia Saudita</option>
<option>Germania</option>
<option>Costa Rica</option>
<option>Polonia</option>
<option>Ecuador</option>
<option>Inghilterra</option>
<option>Paraguay</option>
<option>Trinidad E Tobago</option>
<option>Svezia</option>
<option>Argentina</option>
<option>Costa D'Avorio</option>
<option>Serbia Montenegro</option>
<option>Olanda</option>
<option>Messico</option>
<option>Iran</option>
<option>Angola</option>
<option>Portogallo</option>
</select>


</TD>
			<TD ALIGN=LEFT><BR></TD>
			<TD ALIGN=LEFT BGCOLOR="#CCFFCC">01/07 h. 21</TD>
			<TD ALIGN=LEFT BGCOLOR="#CCFFCC">Frankfurt</TD>
			<TD ALIGN=CENTER BGCOLOR="#CCFFCC">W7-W8 (S4)</TD>
		</TR>
		<TR>
			<TD HEIGHT=16 ALIGN=LEFT><BR></TD>
			<TD ALIGN=LEFT><BR></TD>
			<TD ALIGN=LEFT><BR></TD>
			<TD ALIGN=LEFT><BR></TD>
			<TD ALIGN=LEFT><BR></TD>
			<TD ALIGN=LEFT><BR></TD>
			<TD ALIGN=LEFT><BR></TD>
			<TD ALIGN=LEFT><BR></TD>
		</TR>
		<TR>
			<TD HEIGHT=16 ALIGN=LEFT><BR></TD>
			<TD ALIGN=LEFT><BR></TD>
			<TD ALIGN=LEFT><BR></TD>
			<TD ALIGN=LEFT><BR></TD>
			<TD ALIGN=LEFT><BR></TD>
			<TD COLSPAN=3 ALIGN=CENTER BGCOLOR="#FFFF99"><B>Gli abbinamenti per le Semifinali </B></TD>
			</TR>
		<TR>
			<TD COLSPAN=4 HEIGHT=16 ALIGN=LEFT><I>di cui saranno ammesse ai Quarti le seguenti otto (in ordine libero):</I></TD>
			<TD ALIGN=LEFT><BR></TD>
			<TD ALIGN=CENTER BGCOLOR="#FFFF99"><I>data</I></TD>
			<TD ALIGN=CENTER BGCOLOR="#FFFF99"><I>citt&agrave;</I></TD>
			<TD ALIGN=CENTER BGCOLOR="#FFFF99"><I>match</I></TD>
		</TR>
		<TR>
			<TD HEIGHT=21 ALIGN=LEFT BGCOLOR="#CCFFCC">

<!-- W1 -->
<select name="question_16" >
<option selected>&nbsp;</option>
<option>Stati Uniti</option>
<option>Repubblica Ceca</option>
<option>Italia</option>
<option>Ghana</option>
<option>Australia</option>
<option>Giappone</option>
<option>Brasile</option>
<option>Croazia</option>
<option>Corea Del Sud</option>
<option>Togo</option>
<option>Francia</option>
<option>Svizzera</option>
<option>Spagna</option>
<option>Ucraina</option>
<option>Tunisia</option>
<option>Arabia Saudita</option>
<option>Germania</option>
<option>Costa Rica</option>
<option>Polonia</option>
<option>Ecuador</option>
<option>Inghilterra</option>
<option>Paraguay</option>
<option>Trinidad E Tobago</option>
<option>Svezia</option>
<option>Argentina</option>
<option>Costa D'Avorio</option>
<option>Serbia Montenegro</option>
<option>Olanda</option>
<option>Messico</option>
<option>Iran</option>
<option>Angola</option>
<option>Portogallo</option>
</select>



</TD>
			<TD ALIGN=LEFT BGCOLOR="#CCFFCC">

<!-- W2 -->
<select name="question_17" >
<option selected>&nbsp;</option>
<option>Stati Uniti</option>
<option>Repubblica Ceca</option>
<option>Italia</option>
<option>Ghana</option>
<option>Australia</option>
<option>Giappone</option>
<option>Brasile</option>
<option>Croazia</option>
<option>Corea Del Sud</option>
<option>Togo</option>
<option>Francia</option>
<option>Svizzera</option>
<option>Spagna</option>
<option>Ucraina</option>
<option>Tunisia</option>
<option>Arabia Saudita</option>
<option>Germania</option>
<option>Costa Rica</option>
<option>Polonia</option>
<option>Ecuador</option>
<option>Inghilterra</option>
<option>Paraguay</option>
<option>Trinidad E Tobago</option>
<option>Svezia</option>
<option>Argentina</option>
<option>Costa D'Avorio</option>
<option>Serbia Montenegro</option>
<option>Olanda</option>
<option>Messico</option>
<option>Iran</option>
<option>Angola</option>
<option>Portogallo</option>
</select>


</TD>
			<TD ALIGN=LEFT BGCOLOR="#CCFFCC">

<!-- W3 -->
<select name="question_18" >
<option selected>&nbsp;</option>
<option>Stati Uniti</option>
<option>Repubblica Ceca</option>
<option>Italia</option>
<option>Ghana</option>
<option>Australia</option>
<option>Giappone</option>
<option>Brasile</option>
<option>Croazia</option>
<option>Corea Del Sud</option>
<option>Togo</option>
<option>Francia</option>
<option>Svizzera</option>
<option>Spagna</option>
<option>Ucraina</option>
<option>Tunisia</option>
<option>Arabia Saudita</option>
<option>Germania</option>
<option>Costa Rica</option>
<option>Polonia</option>
<option>Ecuador</option>
<option>Inghilterra</option>
<option>Paraguay</option>
<option>Trinidad E Tobago</option>
<option>Svezia</option>
<option>Argentina</option>
<option>Costa D'Avorio</option>
<option>Serbia Montenegro</option>
<option>Olanda</option>
<option>Messico</option>
<option>Iran</option>
<option>Angola</option>
<option>Portogallo</option>
</select>


</TD>
			<TD ALIGN=LEFT BGCOLOR="#CCFFCC">


<!-- W4 -->
<select name="question_19" >
<option selected>&nbsp;</option>
<option>Stati Uniti</option>
<option>Repubblica Ceca</option>
<option>Italia</option>
<option>Ghana</option>
<option>Australia</option>
<option>Giappone</option>
<option>Brasile</option>
<option>Croazia</option>
<option>Corea Del Sud</option>
<option>Togo</option>
<option>Francia</option>
<option>Svizzera</option>
<option>Spagna</option>
<option>Ucraina</option>
<option>Tunisia</option>
<option>Arabia Saudita</option>
<option>Germania</option>
<option>Costa Rica</option>
<option>Polonia</option>
<option>Ecuador</option>
<option>Inghilterra</option>
<option>Paraguay</option>
<option>Trinidad E Tobago</option>
<option>Svezia</option>
<option>Argentina</option>
<option>Costa D'Avorio</option>
<option>Serbia Montenegro</option>
<option>Olanda</option>
<option>Messico</option>
<option>Iran</option>
<option>Angola</option>
<option>Portogallo</option>
</select>


</TD>
			<TD ALIGN=LEFT><BR></TD>
			<TD ALIGN=LEFT BGCOLOR="#FFFF99">04/07 h. 21</TD>
			<TD ALIGN=LEFT BGCOLOR="#FFFF99">Dortmund</TD>
			<TD ALIGN=CENTER BGCOLOR="#FFFF99">S1-S2 (F1)</TD>
		</TR>
		<TR>
			<TD HEIGHT=21 ALIGN=LEFT BGCOLOR="#CCFFCC">

<!-- W5 -->
<select name="question_20" >
<option selected>&nbsp;</option>
<option>Stati Uniti</option>
<option>Repubblica Ceca</option>
<option>Italia</option>
<option>Ghana</option>
<option>Australia</option>
<option>Giappone</option>
<option>Brasile</option>
<option>Croazia</option>
<option>Corea Del Sud</option>
<option>Togo</option>
<option>Francia</option>
<option>Svizzera</option>
<option>Spagna</option>
<option>Ucraina</option>
<option>Tunisia</option>
<option>Arabia Saudita</option>
<option>Germania</option>
<option>Costa Rica</option>
<option>Polonia</option>
<option>Ecuador</option>
<option>Inghilterra</option>
<option>Paraguay</option>
<option>Trinidad E Tobago</option>
<option>Svezia</option>
<option>Argentina</option>
<option>Costa D'Avorio</option>
<option>Serbia Montenegro</option>
<option>Olanda</option>
<option>Messico</option>
<option>Iran</option>
<option>Angola</option>
<option>Portogallo</option>
</select>


</TD>
			<TD ALIGN=LEFT BGCOLOR="#CCFFCC">

<!-- W6 -->
<select name="question_21" >
<option selected>&nbsp;</option>
<option>Stati Uniti</option>
<option>Repubblica Ceca</option>
<option>Italia</option>
<option>Ghana</option>
<option>Australia</option>
<option>Giappone</option>
<option>Brasile</option>
<option>Croazia</option>
<option>Corea Del Sud</option>
<option>Togo</option>
<option>Francia</option>
<option>Svizzera</option>
<option>Spagna</option>
<option>Ucraina</option>
<option>Tunisia</option>
<option>Arabia Saudita</option>
<option>Germania</option>
<option>Costa Rica</option>
<option>Polonia</option>
<option>Ecuador</option>
<option>Inghilterra</option>
<option>Paraguay</option>
<option>Trinidad E Tobago</option>
<option>Svezia</option>
<option>Argentina</option>
<option>Costa D'Avorio</option>
<option>Serbia Montenegro</option>
<option>Olanda</option>
<option>Messico</option>
<option>Iran</option>
<option>Angola</option>
<option>Portogallo</option>
</select>



</TD>
			<TD ALIGN=LEFT BGCOLOR="#CCFFCC">

<!-- W7 -->
<select name="question_22" >
<option selected>&nbsp;</option>
<option>Stati Uniti</option>
<option>Repubblica Ceca</option>
<option>Italia</option>
<option>Ghana</option>
<option>Australia</option>
<option>Giappone</option>
<option>Brasile</option>
<option>Croazia</option>
<option>Corea Del Sud</option>
<option>Togo</option>
<option>Francia</option>
<option>Svizzera</option>
<option>Spagna</option>
<option>Ucraina</option>
<option>Tunisia</option>
<option>Arabia Saudita</option>
<option>Germania</option>
<option>Costa Rica</option>
<option>Polonia</option>
<option>Ecuador</option>
<option>Inghilterra</option>
<option>Paraguay</option>
<option>Trinidad E Tobago</option>
<option>Svezia</option>
<option>Argentina</option>
<option>Costa D'Avorio</option>
<option>Serbia Montenegro</option>
<option>Olanda</option>
<option>Messico</option>
<option>Iran</option>
<option>Angola</option>
<option>Portogallo</option>
</select>





</TD>
			<TD ALIGN=LEFT BGCOLOR="#CCFFCC">

<!-- W8 -->
<select name="question_23" >
<option selected>&nbsp;</option>
<option>Stati Uniti</option>
<option>Repubblica Ceca</option>
<option>Italia</option>
<option>Ghana</option>
<option>Australia</option>
<option>Giappone</option>
<option>Brasile</option>
<option>Croazia</option>
<option>Corea Del Sud</option>
<option>Togo</option>
<option>Francia</option>
<option>Svizzera</option>
<option>Spagna</option>
<option>Ucraina</option>
<option>Tunisia</option>
<option>Arabia Saudita</option>
<option>Germania</option>
<option>Costa Rica</option>
<option>Polonia</option>
<option>Ecuador</option>
<option>Inghilterra</option>
<option>Paraguay</option>
<option>Trinidad E Tobago</option>
<option>Svezia</option>
<option>Argentina</option>
<option>Costa D'Avorio</option>
<option>Serbia Montenegro</option>
<option>Olanda</option>
<option>Messico</option>
<option>Iran</option>
<option>Angola</option>
<option>Portogallo</option>
</select>



</TD>
			<TD ALIGN=LEFT><BR></TD>
			<TD ALIGN=LEFT BGCOLOR="#FFFF99">05/07 h. 21</TD>
			<TD ALIGN=LEFT BGCOLOR="#FFFF99">Munich</TD>
			<TD ALIGN=CENTER BGCOLOR="#FFFF99">S3-S4 (F2)</TD>
		</TR>
		<TR>
			<TD HEIGHT=16 ALIGN=LEFT><BR></TD>
			<TD ALIGN=LEFT><BR></TD>
			<TD ALIGN=LEFT><BR></TD>
			<TD ALIGN=LEFT><BR></TD>
			<TD ALIGN=LEFT><BR></TD>
			<TD ALIGN=LEFT><BR></TD>
			<TD ALIGN=LEFT><BR></TD>
			<TD ALIGN=LEFT><BR></TD>
		</TR>
		<TR>
			<TD HEIGHT=16 ALIGN=LEFT><BR></TD>
			<TD ALIGN=LEFT><BR></TD>
			<TD ALIGN=LEFT><BR></TD>
			<TD ALIGN=LEFT><BR></TD>
			<TD ALIGN=LEFT><BR></TD>
			<TD COLSPAN=2 ALIGN=CENTER BGCOLOR="#C0C0C0"><B>Finale 1&deg;-2&deg;posto</B></TD>
			<TD ALIGN=CENTER><B><BR></B></TD>
		</TR>
		<TR>
			<TD COLSPAN=4 HEIGHT=16 ALIGN=LEFT><I>e quindi le 4 semifinaliste saranno (in ordine libero):</I></TD>
			<TD ALIGN=LEFT><BR></TD>
			<TD ALIGN=CENTER BGCOLOR="#C0C0C0"><I>data</I></TD>
			<TD ALIGN=CENTER BGCOLOR="#C0C0C0"><I>citt&agrave;</I></TD>
			<TD ALIGN=CENTER><I><BR></I></TD>
		</TR>
		<TR>
			<TD HEIGHT=21 ALIGN=LEFT BGCOLOR="#FFFF99">

<!-- S1 -->
<select name="question_24" >
<option selected>&nbsp;</option>
<option>Stati Uniti</option>
<option>Repubblica Ceca</option>
<option>Italia</option>
<option>Ghana</option>
<option>Australia</option>
<option>Giappone</option>
<option>Brasile</option>
<option>Croazia</option>
<option>Corea Del Sud</option>
<option>Togo</option>
<option>Francia</option>
<option>Svizzera</option>
<option>Spagna</option>
<option>Ucraina</option>
<option>Tunisia</option>
<option>Arabia Saudita</option>
<option>Germania</option>
<option>Costa Rica</option>
<option>Polonia</option>
<option>Ecuador</option>
<option>Inghilterra</option>
<option>Paraguay</option>
<option>Trinidad E Tobago</option>
<option>Svezia</option>
<option>Argentina</option>
<option>Costa D'Avorio</option>
<option>Serbia Montenegro</option>
<option>Olanda</option>
<option>Messico</option>
<option>Iran</option>
<option>Angola</option>
<option>Portogallo</option>
</select>


</TD>
			<TD ALIGN=LEFT BGCOLOR="#FFFF99">


<!-- S2 -->
<select name="question_25" >
<option selected>&nbsp;</option>
<option>Stati Uniti</option>
<option>Repubblica Ceca</option>
<option>Italia</option>
<option>Ghana</option>
<option>Australia</option>
<option>Giappone</option>
<option>Brasile</option>
<option>Croazia</option>
<option>Corea Del Sud</option>
<option>Togo</option>
<option>Francia</option>
<option>Svizzera</option>
<option>Spagna</option>
<option>Ucraina</option>
<option>Tunisia</option>
<option>Arabia Saudita</option>
<option>Germania</option>
<option>Costa Rica</option>
<option>Polonia</option>
<option>Ecuador</option>
<option>Inghilterra</option>
<option>Paraguay</option>
<option>Trinidad E Tobago</option>
<option>Svezia</option>
<option>Argentina</option>
<option>Costa D'Avorio</option>
<option>Serbia Montenegro</option>
<option>Olanda</option>
<option>Messico</option>
<option>Iran</option>
<option>Angola</option>
<option>Portogallo</option>
</select>


</TD>
			<TD ALIGN=LEFT BGCOLOR="#FFFF99">

<!-- S3 -->
<select name="question_26" >
<option selected>&nbsp;</option>
<option>Stati Uniti</option>
<option>Repubblica Ceca</option>
<option>Italia</option>
<option>Ghana</option>
<option>Australia</option>
<option>Giappone</option>
<option>Brasile</option>
<option>Croazia</option>
<option>Corea Del Sud</option>
<option>Togo</option>
<option>Francia</option>
<option>Svizzera</option>
<option>Spagna</option>
<option>Ucraina</option>
<option>Tunisia</option>
<option>Arabia Saudita</option>
<option>Germania</option>
<option>Costa Rica</option>
<option>Polonia</option>
<option>Ecuador</option>
<option>Inghilterra</option>
<option>Paraguay</option>
<option>Trinidad E Tobago</option>
<option>Svezia</option>
<option>Argentina</option>
<option>Costa D'Avorio</option>
<option>Serbia Montenegro</option>
<option>Olanda</option>
<option>Messico</option>
<option>Iran</option>
<option>Angola</option>
<option>Portogallo</option>
</select>


</TD>
			<TD ALIGN=LEFT BGCOLOR="#FFFF99">

<!-- S4 -->
<select name="question_27" >
<option selected>&nbsp;</option>
<option>Stati Uniti</option>
<option>Repubblica Ceca</option>
<option>Italia</option>
<option>Ghana</option>
<option>Australia</option>
<option>Giappone</option>
<option>Brasile</option>
<option>Croazia</option>
<option>Corea Del Sud</option>
<option>Togo</option>
<option>Francia</option>
<option>Svizzera</option>
<option>Spagna</option>
<option>Ucraina</option>
<option>Tunisia</option>
<option>Arabia Saudita</option>
<option>Germania</option>
<option>Costa Rica</option>
<option>Polonia</option>
<option>Ecuador</option>
<option>Inghilterra</option>
<option>Paraguay</option>
<option>Trinidad E Tobago</option>
<option>Svezia</option>
<option>Argentina</option>
<option>Costa D'Avorio</option>
<option>Serbia Montenegro</option>
<option>Olanda</option>
<option>Messico</option>
<option>Iran</option>
<option>Angola</option>
<option>Portogallo</option>
</select>



</TD>
			<TD ALIGN=LEFT><BR></TD>
			<TD ALIGN=LEFT BGCOLOR="#C0C0C0">09/07 h. 20</TD>
			<TD ALIGN=LEFT BGCOLOR="#C0C0C0">Berlin</TD>
			<TD ALIGN=LEFT><BR></TD>
		</TR>
		<TR>
			<TD HEIGHT=16 ALIGN=LEFT><BR></TD>
			<TD ALIGN=LEFT><BR></TD>
			<TD ALIGN=LEFT><BR></TD>
			<TD ALIGN=LEFT><BR></TD>
			<TD ALIGN=LEFT><BR></TD>
			<TD ALIGN=LEFT><BR></TD>
			<TD ALIGN=LEFT><BR></TD>
			<TD ALIGN=LEFT><BR></TD>
		</TR>
		<TR>
			<TD HEIGHT=18 ALIGN=LEFT><I>le 2 finaliste saranno:</I></TD>
			<TD ALIGN=LEFT><BR></TD>
			<TD ALIGN=LEFT><BR></TD>
			<TD COLSPAN=1 ALIGN=CENTER>Campione del Mondo:</TD>
			<TD ALIGN=LEFT><BR></TD>
			<TD ALIGN=LEFT><BR></TD>
			<TD ALIGN=LEFT><BR></TD>
			<TD ALIGN=LEFT><BR></TD>
		</TR>
		<TR>
			<TD HEIGHT=25 ALIGN=CENTER BGCOLOR="#C0C0C0">

<!-- F1 -->
<select name="question_28" >
<option selected>&nbsp;</option>
<option>Stati Uniti</option>
<option>Repubblica Ceca</option>
<option>Italia</option>
<option>Ghana</option>
<option>Australia</option>
<option>Giappone</option>
<option>Brasile</option>
<option>Croazia</option>
<option>Corea Del Sud</option>
<option>Togo</option>
<option>Francia</option>
<option>Svizzera</option>
<option>Spagna</option>
<option>Ucraina</option>
<option>Tunisia</option>
<option>Arabia Saudita</option>
<option>Germania</option>
<option>Costa Rica</option>
<option>Polonia</option>
<option>Ecuador</option>
<option>Inghilterra</option>
<option>Paraguay</option>
<option>Trinidad E Tobago</option>
<option>Svezia</option>
<option>Argentina</option>
<option>Costa D'Avorio</option>
<option>Serbia Montenegro</option>
<option>Olanda</option>
<option>Messico</option>
<option>Iran</option>
<option>Angola</option>
<option>Portogallo</option>
</select>


</TD>
			<TD ALIGN=CENTER BGCOLOR="#C0C0C0">

<!-- F2 -->
<select name="question_29" >
<option selected>&nbsp;</option>
<option>Stati Uniti</option>
<option>Repubblica Ceca</option>
<option>Italia</option>
<option>Ghana</option>
<option>Australia</option>
<option>Giappone</option>
<option>Brasile</option>
<option>Croazia</option>
<option>Corea Del Sud</option>
<option>Togo</option>
<option>Francia</option>
<option>Svizzera</option>
<option>Spagna</option>
<option>Ucraina</option>
<option>Tunisia</option>
<option>Arabia Saudita</option>
<option>Germania</option>
<option>Costa Rica</option>
<option>Polonia</option>
<option>Ecuador</option>
<option>Inghilterra</option>
<option>Paraguay</option>
<option>Trinidad E Tobago</option>
<option>Svezia</option>
<option>Argentina</option>
<option>Costa D'Avorio</option>
<option>Serbia Montenegro</option>
<option>Olanda</option>
<option>Messico</option>
<option>Iran</option>
<option>Angola</option>
<option>Portogallo</option>
</select>



</TD>
			<TD ALIGN=CENTER><BR></TD>
			<TD COLSPAN=1 ALIGN=LEFT BGCOLOR="#FFFF00">

<!-- C -->
<select name="question_30" >
<option selected>&nbsp;</option>
<option>Stati Uniti</option>
<option>Repubblica Ceca</option>
<option>Italia</option>
<option>Ghana</option>
<option>Australia</option>
<option>Giappone</option>
<option>Brasile</option>
<option>Croazia</option>
<option>Corea Del Sud</option>
<option>Togo</option>
<option>Francia</option>
<option>Svizzera</option>
<option>Spagna</option>
<option>Ucraina</option>
<option>Tunisia</option>
<option>Arabia Saudita</option>
<option>Germania</option>
<option>Costa Rica</option>
<option>Polonia</option>
<option>Ecuador</option>
<option>Inghilterra</option>
<option>Paraguay</option>
<option>Trinidad E Tobago</option>
<option>Svezia</option>
<option>Argentina</option>
<option>Costa D'Avorio</option>
<option>Serbia Montenegro</option>
<option>Olanda</option>
<option>Messico</option>
<option>Iran</option>
<option>Angola</option>
<option>Portogallo</option>
</select>


</TD>
			<TD ALIGN=LEFT><BR></TD>
			<TD ALIGN=LEFT><BR></TD>
			<TD ALIGN=LEFT><BR></TD>
			<TD ALIGN=LEFT><BR></TD>
		</TR>
		<TR>
			<TD HEIGHT=16 ALIGN=LEFT><BR></TD>
			<TD ALIGN=LEFT><BR></TD>
			<TD ALIGN=LEFT><BR></TD>
			<TD ALIGN=LEFT><BR></TD>
			<TD ALIGN=LEFT><BR></TD>
			<TD ALIGN=LEFT><BR></TD>
			<TD ALIGN=LEFT><BR></TD>
			<TD ALIGN=LEFT><BR></TD>
		</TR>
		<TR>
			<TD COLSPAN=8 HEIGHT=20 ALIGN=LEFT><FONT SIZE=1>Il presente Studio/Sondaggio &egrave; proposto dall'ARS (Amatori Running Sele) a puro scopo ricreativo e di approfondimento.</FONT></TD>
			</TR>
		<TR>
			<TD COLSPAN=8 HEIGHT=20 ALIGN=LEFT><FONT SIZE=1>Esso ovviamente non &egrave; esente da costi di gestione (<I>che quindi vanno a cumularsi con gli altri costi relativi all'attivit&agrave; dell'Associazione che si prevedono</I></FONT></TD>
			</TR>
		<TR>
			<TD COLSPAN=8 HEIGHT=20 ALIGN=LEFT><FONT SIZE=1><I>quest'anno in aumento&hellip;</I>)</FONT></TD>
			</TR>
		<!--TR>
			<TD HEIGHT=16 ALIGN=LEFT><BR></TD>
			<TD ALIGN=LEFT><BR></TD>
			<TD ALIGN=LEFT><BR></TD>
			<TD ALIGN=LEFT><BR></TD>
			<TD ALIGN=LEFT><BR></TD>
			<TD ALIGN=LEFT><BR></TD>
			<TD ALIGN=LEFT><BR></TD>
			<TD ALIGN=LEFT><BR></TD>
		</TR-->
		<!--TR>
			<TD COLSPAN=2 HEIGHT=26 ALIGN=LEFT><FONT SIZE=3>Vota su http://ars.altervista.org</FONT></TD>
			<TD ALIGN=LEFT><FONT SIZE=3><BR></FONT></TD>
			<TD ALIGN=LEFT><FONT SIZE=3><BR></FONT></TD>
			<TD ALIGN=LEFT><FONT SIZE=3><BR></FONT></TD>
			<TD ALIGN=LEFT><FONT SIZE=3><BR></FONT></TD>
			<TD ALIGN=LEFT><FONT SIZE=3><BR></FONT></TD>
			<TD ALIGN=LEFT><FONT SIZE=3><BR></FONT></TD>
		</TR-->
	</TBODY>
</TABLE>

<?php
if ($admin_mode) {
?>
<br>
Data di ricezione giocata (hh:mm gg/mm/aaaa):<input type="edit" name="data_giocata"><br>
<br>
Chiave segreta:<input type="edit" name="auth_token" value=""><br>
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
</form>

<?php

# logga il contatto
$counter = count_page("questions",array("COUNT"=>1,"LOG"=>1),$filedir_counter); # abilita il contatore, senza visualizzare le cifre, e fai il log

?>