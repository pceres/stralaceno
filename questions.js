//
// script generici per la lettura dei form delle lotterie/sondaggi
//



//
// funzione printf da http://www.jsdir.com/staffscripts/script078.asp
//

function printf(format) {
   document.write(_spr(format, arguments));
}


function sprintf(format) {
   return _spr(format, arguments);
}


function _spr(format, args) {
   function isdigit(c) {
      return (c <= "9") && (c >= "0");
   }

   function rep(c, n) {
      var s = "";
      while (--n >= 0)
         s += c;
      return s;
   }

   var c;
   var i, ii, j = 1;
   var retstr = "";
   var space = "&nbsp;";
   
   
   for (i = 0; i < format.length; i++) {
      var buf = "";
      var segno = "";
      var expx = "";
      c = format.charAt(i);
      if (c == "\n") {
         c = "<br>";
      }
      if (c == "%") {
         i++;
         leftjust = false;
         if (format.charAt(i) == '-') {
            i++;
            leftjust = true;
         }
         padch = ((c = format.charAt(i)) == "0") ? "0" : space;
         if (c == "0")
            i++;
         field = 0;
         if (isdigit(c)) {
            field = parseInt(format.substring(i));
            i += String(field).length;
         }
   
         if ((c = format.charAt(i)) == '.') {
            digits = parseInt(format.substring(++i));
            i += String(digits).length;
            c = format.charAt(i);
         }
         else
            digits = 0;
   
         switch (c.toLowerCase()) {
            case "x":
               buf = args[j++].toString(16);
               break;
            case "e":
               expx = -1;
            case "d":
               if (args[j] < 0) {
                  args[j] = -args[j];
                  segno = "-";
                  field--;
               }
               if (expx != "") {
                  with (Math)
                     expx = floor(log(args[j]) / LN10);
                  args[j] /= Number("1E" + expx);
                  field -= String(expx).length + 2;
               }
               var x = args[j++];
               for (ii=0; ii < digits && x - Math.floor(x); ii++)
                  x *= 10;
               
               x = String(Math.round(x));
               
               x = rep("0", ii - x.length + 1) + x;
               
               buf += x.substring(0, x.length - ii);
               
               if (digits > 0)
                  buf += "." + x.substring(x.length - ii) + rep("0", digits - ii);
               if (expx != "") {
                  var expsign = (expx >= 0) ? "+" : "-";
                  expx = Math.abs(expx) + "";
                  buf += c + expsign + rep("0", 3 - expx.length) + expx;
               }
               break;
            case "o":
               buf = args[j++].toString(8);
               break;
            case "s":
               buf = args[j++];
               break;
            case "c":
               buf = args[j++].substring(0, 1);
               break;
            default:
               retstr += c;
         }
         field -= buf.length;
         if (!leftjust) {
            if (padch == space)
               retstr += rep(padch, field) + segno;
            else
               retstr += segno + rep("0", field);
         }
         retstr += buf;
         if (leftjust)
            retstr += rep(space, field);
      }
      else
         retstr += c;
   }
   return retstr;
}
//
// fine funzione printf
//


// Removes leading whitespaces
function LTrim( value ) {
	
	var re = /\s*((\S+\s*)*)/;
	return value.replace(re, "$1");
}

// Removes ending whitespaces
function RTrim( value ) {
	
	var re = /((\s*\S+)*)\s*/;
	return value.replace(re, "$1");
}

// Removes leading and ending whitespaces
function trim( value ) {
	
	return LTrim(RTrim(value));
}


function read_radio(radio_button_handle)
{
	for (var i = 0; i < radio_button_handle.length; i++)
	{
		if (radio_button_handle[i].checked)
		{
			return radio_button_handle[i].value;
		}
	}
	return "";
} // end function read_radio


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


function read_form_field(f,tag_id)
{
// questa funzione legge dal form f un campo dal nome question_xx, con xx indicato da tag_id

if (tag_id<10)
{
	tag = 'question_0'+tag_id;
}
else
{
	tag = 'question_'+tag_id;
}

if (f[tag])
{
	switch (f[tag].type)
	{
	case 'text':
		ks = f[tag].value;
		break;
	case 'hidden':
		ks = f[tag].value;
		break;
	case 'select-one':
		ks = f[tag].options[f[tag].selectedIndex].text;
		break;
	default:
		alert('Errore: non so gestire i campi di tipo ' + f[tag].type + ' (campo "' + tag + '")');
		return false;
	}
	
	return ks;
}

} // end function read_form_field


function read_form_fields(f)
{
/*
input:
f	: handle al form

restituisce un array con i valori dei campi del form del tipo question_xx. Se c'e' un errore, in uscita un array con un solo elemento: '%errore%'
*/
	list = new Array;
	for (ii = 0; ii < f.elements.length; ii++)
	{
		if (squadra = read_form_field(f,ii))
		{
			// verifica che non ci siano campi vuoti
			if ( (squadra.length <= 1) & (squadra[0] == ' ') )
			{
				alert("Attenzione: la giocata non e' regolare perche' c'e' almeno un campo vuoto! Correggere prima il problema");
				list = new Array('%errore%');
				return list;
			}
			else
			{
				// alert(ii+') '+tag+' ('+squadra+')');
				list[ii] = trim(squadra);
			}
		}
	}
	
	return list;
}


function get_girone(squadra,gironi)
{
/* restituisci il girone [0..7] di appartenenza della squadra (oppure -1)

input:
squadra		: stringa con il nome della squadra
gironi		: array di array di stringhe con i nomi delle squadre

es:
gironeA = new Array("Barcellona","Levski Sofia","Chelsea","Werder Brema");
gironeB = new Array("Bayern Monaco","Spartak Mosca","Sporting Lisbona","Inter");
gironeC = new Array("Galatasaray","Bordeaux","Psv Eindhoven","Liverpool");
gironeD = new Array("Olympiakos Pireo","Valencia","Roma","Shakhtar Donetsk");
gironeE = new Array("Dynamo Kiev","Steaua Bucarest","Lione","Real Madrid");
gironeF = new Array("Fc Copenhagen","Benfica","Manchester United","Celtic Glasgow");
gironeG = new Array("Amburgo","Arsenal","Porto","Cska Mosca");
gironeH = new Array("Anderlecht","Lille","Milan","Aek Atene");

gironi = new Array(gironeA,gironeB,gironeC,gironeD,gironeE,gironeF,gironeG,gironeH);

*/
	
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

// timCycle2 = setTimeout('performUpdate()', 15000); 

