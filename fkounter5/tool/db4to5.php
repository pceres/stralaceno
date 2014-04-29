<?php /* db4to5.php
                                        __                      PHP Script    _    vs 5.0
                                       / _| __ _ _ __   /\ /\___  _   _ _ __ | |_ ___ _ __
                                      | |_ / _` | '_ \ / //_/ _ \| | | | '_ \| __/ _ \ '__|
                                      |  _| (_| | | | / __ \ (_) | |_| | | | | ||  __/ |
                                      |_|  \__,_|_| |_\/  \/\___/ \__,_|_| |_|\__\___|_|

                                              fanatiko <fankounter@libero.it>, ITALY
 Documentazione di riferimento
############################################################################################
 license.txt - le condizioni di utilizzo, modifica e redistribuzione per l'utente finale
  manual.txt - la guida alla configurazione, all'installazione e all'uso dello script
    faqs.txt - le risposte alle domande più comuni, sui problemi e sulle funzionalità
 history.txt - la progressione delle versioni, i miglioramenti apportati e i bugs eliminati

 Descrizione del file
############################################################################################
 Tool di conversione di un file di archivio versione 4 ad un file di database versione 5.

      */

############################################################################################
# IMPOSTAZIONI DI ESECUZIONE
############################################################################################

error_reporting(0);

############################################################################################
# CONFIGURAZIONE
############################################################################################

define("SOURCE_FOLDER","./");
define("SOURCE_FILES","_*_archive.php");

define("TARGET_FOLDER","data5/");
define("TARGET_FILES","data_*.php");

define("EOL","\r\n");

############################################################################################
# SIGLE E STATI
############################################################################################

$inf__country = array(
 "ac" => "Isola dell'Ascensione",
 "ad" => "Andorra",
 "ae" => "Emirati Arabi Uniti",
 "af" => "Afghanistan",
 "ag" => "Antigua e Barbuda",
 "ai" => "Anguilla",
 "al" => "Albania",
 "am" => "Armenia",
 "an" => "Antille Olandesi",
 "ao" => "Angola",
 "aq" => "Antartide",
 "ar" => "Argentina",
 "as" => "Isole Samoa Americane",
 "at" => "Austria",
 "au" => "Australia",
 "aw" => "Aruba",
 "az" => "Azerbaigian",
 "ba" => "Bosnia Erzegovina",
 "bb" => "Isole Barbados",
 "bd" => "Bangladesh",
 "be" => "Belgio",
 "bf" => "Burkina Faso",
 "bg" => "Bulgaria",
 "bh" => "Bahrein",
 "bi" => "Burundi",
 "bj" => "Benin",
 "bm" => "Isole Bermuda",
 "bn" => "Brunei Darussalam",
 "bo" => "Bolivia",
 "br" => "Brasile",
 "bs" => "Isole Bahamas ",
 "bt" => "Bhutan",
 "bv" => "Isola Bouvet",
 "bw" => "Botswana",
 "by" => "Bielorussia",
 "bz" => "Belize",
 "ca" => "Canada",
 "cc" => "Isole Cocos",
 "cd" => "Repubblica Democratica del Congo",
 "cf" => "Repubblica Centrafricana",
 "cg" => "Repubblica del Congo",
 "ch" => "Svizzera",
 "ci" => "Costa d'Avorio",
 "ck" => "Isole Cook",
 "cl" => "Cile",
 "cm" => "Camerun",
 "cn" => "Cina",
 "co" => "Colombia",
 "cr" => "Costa Rica",
 "cu" => "Cuba",
 "cv" => "Capo Verde",
 "cx" => "Christmas Island",
 "cy" => "Cipro",
 "cz" => "Repubblica Ceca",
 "de" => "Germania",
 "dj" => "Gibuti",
 "dk" => "Danimarca",
 "dm" => "Dominica",
 "do" => "Repubblica Dominicana",
 "dz" => "Algeria",
 "ec" => "Ecuador",
 "ee" => "Estonia",
 "eg" => "Egitto",
 "eh" => "Sahara Occidentale",
 "er" => "Eritrea",
 "es" => "Spagna",
 "et" => "Etiopia",
 "fi" => "Finlandia",
 "fj" => "Isole Figi",
 "fk" => "Isole Falkland",
 "fm" => "Micronesia",
 "fo" => "Isole Faroer",
 "fr" => "Francia",
 "ga" => "Gabon",
 "gd" => "Grenada",
 "ge" => "Georgia",
 "gf" => "Guyana Francese",
 "gg" => "Guernsey",
 "gh" => "Ghana",
 "gi" => "Gibilterra",
 "gl" => "Groenlandia",
 "gm" => "Gambia",
 "gn" => "Guinea",
 "gp" => "Guadalupa",
 "gq" => "Guinea Equatoriale",
 "gr" => "Grecia",
 "gs" => "Isole Georgia Meridionale e Sandwich Meridionale",
 "gt" => "Guatemala",
 "gu" => "Guam",
 "gw" => "Guinea Bissau",
 "gy" => "Guyana",
 "hk" => "Hong Kong",
 "hm" => "Isole Heard e McDonald",
 "hn" => "Honduras",
 "hr" => "Croazia",
 "ht" => "Haiti",
 "hu" => "Ungheria",
 "id" => "Indonesia",
 "ie" => "Irlanda",
 "il" => "Israele",
 "im" => "Isola di Man",
 "in" => "India",
 "io" => "Territori Britannici nell'Oceano Indiano",
 "iq" => "Iraq",
 "ir" => "Iran",
 "is" => "Islanda",
 "it" => "Italia",
 "je" => "Jersey",
 "jm" => "Giamaica",
 "jo" => "Giordania",
 "jp" => "Giappone",
 "ke" => "Kenya",
 "kg" => "Kirghistan",
 "kh" => "Cambogia",
 "ki" => "Kiribati",
 "km" => "Comoros",
 "kn" => "Saint Kitts e Nevis",
 "kp" => "Corea del Nord",
 "kr" => "Corea del Sud",
 "kw" => "Kuwait",
 "ky" => "Isole Caiman",
 "kz" => "Kazakistan",
 "la" => "Laos",
 "lb" => "Libano",
 "lc" => "Saint Lucia",
 "li" => "Liechtenstein",
 "lk" => "Sri Lanka",
 "lr" => "Liberia",
 "ls" => "Lesotho",
 "lt" => "Lituania",
 "lu" => "Lussemburgo",
 "lv" => "Lettonia",
 "ly" => "Libia",
 "ma" => "Morocco",
 "mc" => "Monaco",
 "dm" => "Moldavia",
 "mg" => "Madagascar",
 "mh" => "Isole Marshall",
 "mk" => "Macedonia",
 "ml" => "Mali",
 "mm" => "Myanmar",
 "mn" => "Mongolia",
 "mo" => "Macao",
 "mp" => "Isole Marianne settentrionali",
 "mq" => "Martinica",
 "mr" => "Mauritania",
 "ms" => "Montserrat",
 "mt" => "Malta",
 "mu" => "Isole Mauritius",
 "mv" => "Isole Maldive",
 "mw" => "Malawi",
 "mx" => "Messico",
 "my" => "Malaysia",
 "mz" => "Mozambico",
 "na" => "Namibia",
 "nc" => "Nuova Caledonia",
 "ne" => "Niger",
 "nf" => "Isole Norfolk",
 "ng" => "Nigeria",
 "ni" => "Nicaragua",
 "nl" => "Olanda",
 "no" => "Norvegia",
 "np" => "Nepal",
 "nr" => "Nauru",
 "nu" => "Isola Niue",
 "nz" => "Nuova Zelanda",
 "om" => "Oman",
 "pa" => "Panama",
 "pe" => "Perù",
 "pf" => "Polinesia Francese",
 "pg" => "Papua - Nuova Guinea",
 "ph" => "Filippine",
 "pk" => "Pakistan",
 "pl" => "Polonia",
 "pm" => "Saint Pierre e Miquelon",
 "pn" => "Isola Pitcairn",
 "pr" => "Portorico",
 "ps" => "Territori Palestinesi",
 "pt" => "Portogallo",
 "pw" => "Palau",
 "py" => "Paraguay",
 "qa" => "Qatar",
 "re" => "Isole Reunion",
 "ro" => "Romania",
 "ru" => "Russia",
 "rw" => "Ruanda",
 "sa" => "Arabia Saudita",
 "sb" => "Isole Salomone",
 "sc" => "Isole Seychelles",
 "sd" => "Sudan",
 "se" => "Svezia",
 "sg" => "Singapore",
 "sh" => "Saint Helena",
 "si" => "Slovenia",
 "sj" => "Isole Svalbard e Jan Mayen",
 "sk" => "Slovacchia",
 "sl" => "Sierra Leone",
 "sm" => "San Marino",
 "sn" => "Senegal",
 "so" => "Somalia",
 "sr" => "Suriname",
 "st" => "Sao Tomè e Principe",
 "sv" => "El Salvador",
 "sy" => "Siria",
 "sz" => "Swaziland",
 "tc" => "Isole Turks e Caicos",
 "td" => "Ciad",
 "tf" => "Territorio Meridionale Francese",
 "tg" => "Togo",
 "th" => "Thailandia",
 "tj" => "Tagikistan",
 "tk" => "Isole Tokelau",
 "tm" => "Turkmenistan",
 "tn" => "Tunisia",
 "to" => "Isole Tonga",
 "tp" => "Timor Orientale",
 "tr" => "Turchia",
 "tt" => "Trinidad e Tobago",
 "tv" => "Tuvalu",
 "tw" => "Taiwan",
 "tz" => "Tanzania",
 "ua" => "Ucraina",
 "ug" => "Uganda",
 "uk" => "Regno Unito",
 "um" => "Isole Minori degli Stati Uniti",
 "us" => "Stati Uniti",
 "uy" => "Uruguay",
 "uz" => "Uzbekistan",
 "va" => "Città del Vaticano",
 "vc" => "Saint Vincent e Grenadine",
 "ve" => "Venezuela",
 "vg" => "Isole Vergini Britanniche",
 "vi" => "Isole Vergini Statunitensi",
 "vn" => "Vietnam",
 "vu" => "Vanuatu",
 "wf" => "Isole Wallis e Futuna",
 "ws" => "Samoa",
 "ye" => "Yemen",
 "yt" => "Mayotte",
 "yu" => "Yugoslavia",
 "za" => "Sud Africa",
 "zm" => "Zambia",
 "zw" => "Zimbabwe"
);

############################################################################################
# MAIN
############################################################################################

$__db4s=_ls_(SOURCE_FOLDER,SOURCE_FILES);

echo"<!DOCTYPE html PUBLIC \"-//W3C//DTD HTML 4.01 Transitional//EN\">";
echo EOL.EOL;
echo"<!-- db4to5 tool //-->";
echo EOL.EOL;
echo"<html>";
echo"<head>";
echo"<title>db4to5 tool</title>";
echo"<meta name=\"description\" content=\"\" />";
echo"<meta name=\"keywords\" content=\"\" />";
echo"<meta http-equiv=\"content-type\" content=\"text/html; charset=iso-8859-1\" />";
echo"<base target=\"_top\" />";
echo"<style>body{background-color:rgb(255,255,255);}p{font-family:Verdana;font-size:15px;}</style>";
echo"</head>";
echo"<body>";
echo"<h2>2002 - fanKounter - Free PHP Script<br />Tool di conversione db4 a db5</h2>";
echo"<p>Trovati *".count($__db4s)."* db4 da convertire nella cartella '".SOURCE_FOLDER."'.</p>";
echo"<hr />";

foreach($__db4s as $__db4){
 $__id=_getidbyname_($__db4);

// Inizializzazione strutture dati db4

 $dat__counting=0;
 $dat__calendar=array();
 $dat__day=array();
 $dat__time=array();
 $dat__country=array();
 $dat__browser=array();
 $dat__os=array();
 $dat__location=array();
 $dat__referrer=array();
 $dat__engine=array();
 $dat__enkey=array();
 $dat__entry=array();

// Lettura di un db4

 require(SOURCE_FOLDER.$__db4);

// Conversione dati in formato db5

 $dat__counter=$dat__counting;
 $dat__started=array("timestamp"=>$dat__entry[0]["timestamp"],"counter"=>($dat__entry[0]["entry"]-1));
 $dat__cuttime=0;

 $__country=array_flip($inf__country);
 $__data=$dat__country;
 $dat__country=array("#?"=>0);

 foreach($__data as $__item=>$__hits)
  if(array_key_exists($__item,$__country))
   $dat__country[$__country[$__item]]=$__hits;
  else
   $dat__country["#?"]+=$__hits;

 if(array_key_exists("?",$dat__browser)){
  $dat__browser["#?"]=$dat__browser["?"];
  unset($dat__browser["?"]);
 }
 else
  $dat__browser["#?"]=0;

 if(array_key_exists("?",$dat__os)){
  $dat__os["#?"]=$dat__os["?"];
  unset($dat__os["?"]);
 }
 else
  $dat__os["#?"]=0;

 $dat__provider=array("#?"=>0,"#!"=>0);

 foreach($dat__entry as $__elem)
  _update_((preg_match("/\.(([a-z]|[a-z][a-z\d\-]*[a-z\d])\.[a-z]{2,4})$/i",$__elem["host"],$__res))?strtolower($__res[1]):"#?",$dat__provider,TRUE);

 $dat__provider["#?"]+=$dat__counter-$dat__started["counter"]-array_sum($dat__provider);

 if(array_key_exists("?",$dat__location)){
  $dat__location["#?"]=$dat__location["?"];
  unset($dat__location["?"]);
 }
 else
  $dat__location["#?"]=0;

 $dat__location["#!"]=0;

 if(array_key_exists("?",$dat__referrer)){
  $dat__referrer["#?"]=$dat__referrer["?"];
  unset($dat__referrer["?"]);
 }
 else
  $dat__referrer["#?"]=0;

 $dat__referrer["#!"]=0;
 $dat__enkey["#!"]=0;

 unset($dat__entry[0]);
 $__entry=$dat__entry;
 $dat__entry=array();

 foreach($__entry as $__elem)
  $dat__entry[(int)$__elem["entry"]]=array(
   "ts"=>$__elem["timestamp"],
   "ip"=>($__elem["ip"]!=="?")?$__elem["ip"]:"",
   "host"=>($__elem["host"]!=="?")?$__elem["host"]:"",
   "age"=>($__elem["browser"]!=="?")?$__elem["browser"]:"",
   "os"=>($__elem["os"]!=="?")?$__elem["os"]:"",
   "loc"=>($__elem["location"]!=="?")?$__elem["location"]:"",
   "ref"=>(($__elem["referrer"]!=="?")&&($__elem["referrer"]!==""))?$__elem["referrer"]:"",
   "eng"=>($__elem["engine"]!=="")?$__elem["engine"]:"",
   "enk"=>($__elem["enkeys"]!=="")?$__elem["enkeys"]:""
  );

// Riordino dei dati

 arsort($dat__country,SORT_NUMERIC);
 arsort($dat__browser,SORT_NUMERIC);
 arsort($dat__os,SORT_NUMERIC);
 arsort($dat__provider,SORT_NUMERIC);
 arsort($dat__location,SORT_NUMERIC);
 arsort($dat__referrer,SORT_NUMERIC);
 arsort($dat__engine,SORT_NUMERIC);
 arsort($dat__enkey,SORT_NUMERIC);
 ksort($dat__entry,SORT_NUMERIC);

// Creazione del buffer db5

 $__data="";
 $__data.="<?php".EOL.EOL;
 $__data.="# The database file for '".$__id."' counter.".EOL;
 $__data.="# This file was created by db4to5 tool on ".date("d.m.Y, H:i",time()).".".EOL.EOL;
 $__data.="\$dat__counter=".$dat__counter.";".EOL;
 $__data.=_datastore_($dat__started,"dat__started");
 $__data.="\$dat__cuttime=".$dat__cuttime.";".EOL;

 foreach($dat__calendar as $__year=>$__month){
  $__data.="\$dat__calendar[\"".$__year."\"]=array(";

  for($__m=0,$__fm=TRUE;$__m<count($__month);$__m++){
   $__data.=(($__fm)?"":",")."array(".implode(",",$__month[$__m]).")";
   $__fm=FALSE;
  }

  $__data.=");".EOL;
 }

 $__data.=_datastore_($dat__day,"dat__day",TRUE,FALSE);
 $__data.=_datastore_($dat__time,"dat__time",TRUE,FALSE);
 $__data.=_datastore_($dat__country,"dat__country");
 $__data.=_datastore_($dat__browser,"dat__browser");
 $__data.=_datastore_($dat__os,"dat__os");
 $__data.=_datastore_($dat__provider,"dat__provider");
 $__data.=_datastore_($dat__location,"dat__location");
 $__data.=_datastore_($dat__referrer,"dat__referrer");
 $__data.=_datastore_($dat__engine,"dat__engine");
 $__data.=_datastore_($dat__enkey,"dat__enkey");

 foreach($dat__entry as $__entry=>$__elem)
  $__data.=_datastore_($__elem,"dat__entry[".$__entry."]",FALSE);

 $__data.=EOL."?>";

// Salvataggio del db5

 _mkdir_(TARGET_FOLDER);
 _fcreate_(TARGET_FOLDER._filename_(TARGET_FILES,$__id),$__data);

 echo"<p>* ".SOURCE_FOLDER._filename_(SOURCE_FILES,$__id)." =&gt; ".TARGET_FOLDER._filename_(TARGET_FILES,$__id)."</p>";
}

echo"<hr />";
echo"<p>Operazioni concluse.</p>";
echo"</body>";
echo"</html>";
echo EOL.EOL;
exit();

############################################################################################
# FUNZIONI LOCALI
############################################################################################

function _file_get_contents_($__name,$__unused=0){
 settype($__name,"string");

 return implode("",(file_exists($__name))?file($__name):array());
}

function _mkdir_($__name,$__mode=0777){
 settype($__name,"string");

 clearstatcache();

 if(is_dir($__name))
  return;
 elseif(mkdir($__name,$__mode)){
  clearstatcache();

  if(is_dir($__name))
   return;
 }

 exit("System Error: _mkdir_(".$__name.",".$__mode.").");
}

function _fdel_($__name){
 settype($__name,"string");

 clearstatcache();

 if(!file_exists($__name))
  return;
 elseif(unlink($__name)){
  clearstatcache();

  if(!file_exists($__name))
   return;
 }

 exit("System Error: _fdel_(".$__name.").");
}

function _fcreate_($__name,$__content){
 settype($__name,"string");
 settype($__content,"string");

 if(($__fid=fopen($__name,"wb"))!==FALSE){
  if(fwrite($__fid,$__content)===strlen($__content)){
   fflush($__fid);
   fclose($__fid);
   clearstatcache();

   if(file_exists($__name))
    if(file_get_contents($__name)===$__content)
     return;
  }

  @fclose($__fid);
 }

 _fdel_($__name);
 exit("System Error: _fcreate_(".$__name.",...).");
}

function _ls_($__dir="./",$__pattern="*.*"){
 settype($__dir,"string");
 settype($__pattern,"string");

 clearstatcache();

 $__ls=array();
 $__regexp=preg_replace("/\\x5C\\x3F/",".",preg_replace("/\\x5C\\x2A/",".*",preg_quote($__pattern,"/")));

 if(!is_dir($__dir))
  return $__ls;
 elseif(($__dir_id=opendir($__dir))!==FALSE){
  while(($__file=readdir($__dir_id))!==FALSE)
   if(preg_match("/^".$__regexp."$/",$__file))
    array_push($__ls,$__file);

  closedir($__dir_id);
  sort($__ls,SORT_STRING);
  return $__ls;
 }

 exit("System Error: _ls_(".$__dir.",".$__pattern.").");
}

function _filename_($__template,$__replace){
 settype($__template,"string");
 settype($__replace,"string");

 return preg_replace("/\\x2A/",$__replace,$__template);
}

function _getidbyname_($__name){
 return preg_replace("/^".preg_replace("/\\x5C\\x2A/","(.*)",preg_quote(SOURCE_FILES,"/"))."$/","\\1",$__name);
}

function _update_($__elem,&$__data,$__create=FALSE){
 settype($__elem,"string");
 settype($__data,"array");
 settype($__create,"boolean");

 if(array_key_exists($__elem,$__data))
  ++$__data[$__elem];
 elseif($__create)
  $__data[$__elem]=1;

 return;
}

function _datastore_($__data_arr,$__var_name,$__val_numeric=TRUE,$__safe_key=TRUE){
 settype($__data_arr,"array");
 settype($__var_name,"string");
 settype($__val_numeric,"boolean");
 settype($__safe_key,"boolean");

 $__the_first=TRUE;
 $__buffer="\$".$__var_name."=array(";

 foreach($__data_arr as $__key=>$__val){
  $__key=preg_replace("/[\\x00-\x1F\x22\x24\\x5C]/","",$__key);
  $__val=preg_replace("/[\\x00-\x1F\x22\x24\\x5C]/","",$__val);
  $__buffer.=(($__the_first)?"":",").(($__safe_key)?("\"".$__key."\"=>"):"").(($__val_numeric)?$__val:("\"".$__val."\""));
  $__the_first=FALSE;
 }

 $__buffer.=");".EOL;
 return $__buffer;
}

############################################################################################

?>
