<?php /* counter.js.php
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
 Modulo che genera codice JavaScript da includere nelle pagine WEB da monitorare.

      */

############################################################################################
# CONFIGURAZIONE
############################################################################################

require("cnf.inc.php");

############################################################################################
# IMPOSTAZIONI DI ESECUZIONE
############################################################################################

//error_reporting(0); //!!!
// import_request_variables("gpc","par__"); // removed since PHP 5.4.0
extract($_REQUEST, EXTR_PREFIX_ALL|EXTR_REFS, 'par_');

############################################################################################
# PARAMETRI IN INPUT
############################################################################################

$par__id=(isset($par__id)&&preg_match("/^[a-z\d]+$/i",$par__id))?$par__id:FALSE;
$par__mode=(isset($par__mode)&&preg_match("/^(graphic|text|hidden)$/i",$par__mode))?strtolower($par__mode):"graphic";
$par__mode=(($par__mode==="graphic")&&(!extension_loaded("gd")))?"text":$par__mode;

############################################################################################
# DEFINIZIONE DELLE VARIABILI DI SUPPORTO
############################################################################################

if(defined("SCRIPT_PATH"))
 $aux__script_path=SCRIPT_PATH;
elseif(array_key_exists("HTTP_HOST",$_SERVER)&&array_key_exists("SERVER_PORT",$_SERVER)&&array_key_exists("PHP_SELF",$_SERVER))
 $aux__script_path="http://".$_SERVER["HTTP_HOST"].(($_SERVER["SERVER_PORT"]==="80")?"":(":".$_SERVER["SERVER_PORT"])).preg_replace("/".preg_quote(basename($_SERVER["PHP_SELF"]),"/")."$/","",$_SERVER["PHP_SELF"]);
else
 $aux__script_path=FALSE;

$fn = TEMP_FOLDER.preg_replace("/\\x2A/",$par__id,ACCESS_FILES);
if ($fh = fopen($fn,"w")) {
  fclose($fh);
  $alt_txt = "fanKounter";
} else {
  $alt_txt = "Error! Please check permissions for file $fn, and write permission in subfolders of custom/contatori/: temp, back, data";
}


$aux__counter_href=$aux__script_path."counter.php"."?id=".$par__id."&mode=".$par__mode."&referrer='+escape(_referrer)+'";
$aux__stats_href=$aux__script_path."stats.php"."?id=".$par__id;
$aux__jscode=($par__mode==="graphic")?("<a href=\'".$aux__stats_href."\'><img src=\'".$aux__counter_href."\' width=\'98\' height=\'38\' alt=\'$alt_txt\' style=\'border:0px;\' /></a>"):("<script type=\'text/javascript\' language=\'javascript\' src=\'".$aux__counter_href."\'></script>");

############################################################################################
# GENERAZIONE DI CODICE JAVASCRIPT
############################################################################################

$__jsfile=<<<JSFILE
/* This file was created by fanKounter */

try
{
 var _referrer=top.document.referrer;
}
catch(_err)
{
 var _referrer=self.document.referrer;
}

document.write('$aux__jscode');
JSFILE;

############################################################################################
# OUTPUT
############################################################################################

header("Content-type: text/javascript");
header("Cache-Control: no-store, no-cache, must-revalidate");
header("Pragma: no-cache");
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
header("Last-Modified: ".gmdate("D, d M Y H:i:s")." GMT");
header("Cache-Control: post-check=0, pre-check=0");

echo $__jsfile;
exit();

############################################################################################

?>
