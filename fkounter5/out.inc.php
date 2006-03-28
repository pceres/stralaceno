<?php /* out.inc.php
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
 Modulo importato per la stampa del contatore.

      */

############################################################################################
# BLOCCO ESECUZIONE STANDALONE
############################################################################################

if(!defined("STANDALONE"))
 exit();

############################################################################################
# CONTEGGIO DEGLI ACCESSI
############################################################################################

define("TOTAL_VAL",$dat__counter);
define("TODAY_VAL",$aux__calendar->_get_hits_($aux__now,"d"));

############################################################################################
# NESSUNA CACHE
############################################################################################

header("Expires: ".gmdate("D, d M Y H:i:s")." GMT");
header("Last-Modified: ".gmdate("D, d M Y H:i:s")." GMT");
header("Cache-Control: no-store, no-cache, must-revalidate");
header("Cache-Control: post-check=0, pre-check=0",FALSE);
header("Pragma: no-cache");

############################################################################################
# CONTATORE GRAFICO
############################################################################################

if($par__mode==="graphic"){
 if(file_exists("lan.inc.php")){require_once("lan.inc.php");}
 if(!defined("LAN_TODAY"))define("LAN_TODAY","OGGI");
 if(!defined("LAN_TOTAL"))define("LAN_TOTAL","TOTALE");

define("IMG_DATA","R0lGODlhYgAmAIQQABQUUDw8UBQUUDw8UAAkVQAkqgBJVQBJqkmSqm1tqm2SqpK2qpK2/7a2qra2//9tAP///////////////////////////////////////////////////////////////yH+FUNyZWF0ZWQgd2l0aCBUaGUgR0lNUAAsAAAAAGIAJgAABf7gYYjkaJboqabs6rbwK8PN4thNrjd3by87R05I5A2PjEbSuAM6a7rnb2csFpNYpTZ5ODQg4LB4TC6bwd10OM1Wt9mK8OLgkCPOjsM47wWP3npngmRugXyAbQhtcWCKDGEIC2dzYw1dXxAGVjyBg54QXWiBbGtvpV2McweSEA53ZwqvYZZ9oA2xuAsGL59ioWB5or+giAevB4wIyowKrK7KdY3N0K2XaAu4CM0GR0KWvcLRqMTF5XELCK5xrmCxNsrAy+h3tJheCvj4eQ75+JZ/XUyUOTQqVDFfgOJEggAPHwR00ShVY6VATz00t/pZyphv46ZOCNsQIyeJFCE4Ev6hpWNoTNkvdGFiQfgnroG2mw244YqVJ+NNBwbK0GGoSI8pcolOtUyGjJExB1ChtmPEcJ2Imv0U7LvJU2c/oCffEByGSBibaBVXgqIqZuHDoXwwacqXQCsdugpyqssHtq1JYEg7lXtDNW3MVVHXaEXHiCYYHj9u6NhEOceYv2bVKA1cMNpbzxAqGmN0yG2rEV9IHCgQUMQI1mwMFJA9gkAagLcBytjlugTv1i5UBHdRrY8RBg6Q46hSRbkPIUmgbylyPHkPHlGlW7dig4jkKpG95YjOY+ZVcOjTq19/xjH79/DjD7Qmv7599XE/AQAQZn9//mDsB+B9BJJx0SAA8umXIAQLBljgg5XQJ8iCAzJIRoUQFpiTJp5QyKCCH/aXYYZ5cIiggx5a6OCIDzIg4YQDChigfx9iyKJ8B96o43s57ugjekDV8uOQn7hH5JHt7RKNAEwyCYGTTzYpQJRgTBmGlEiyFyQmVXb55JVeTmmll1lqiZoYVqYJ5pdfjhmlm2WCsyWaYa6JpZtwxgnOhqCpySabauKp53ol9nmloIC+KWaTg8qpZKNl5iQkpERKWkNl1kWmqWRZUIYDplRQ4c2ozFFHKqhFAAFcCbYZQMAus7m6y6uyHtBqrLHuoqsJvMbgK2/A/uprCAA7"); // ArsWeb

 define("TODAY_TXT_YX","4,17");
 define("TODAY_VAL_YX","39,14");
 define("TOTAL_TXT_YX","4,27");
 define("TOTAL_VAL_YX","39,24");
 define("TODAY_TXT_SIZE",1);
 define("TODAY_VAL_SIZE",3);
 define("TOTAL_TXT_SIZE",1);
 define("TOTAL_VAL_SIZE",3);
 define("TODAY_TXT_COLOR","0x14,0x14,0x50");
 define("TODAY_VAL_COLOR","0x3C,0x3C,0x50");
 define("TOTAL_TXT_COLOR","0x14,0x14,0x50");
 define("TOTAL_VAL_COLOR","0x3C,0x3C,0x50");

 $__img=imagecreatefromstring(base64_decode(IMG_DATA));

 eval("\$__col1=imagecolorallocate(\$__img,".TODAY_TXT_COLOR.");");
 eval("\$__col2=imagecolorallocate(\$__img,".TODAY_VAL_COLOR.");");
 eval("\$__col3=imagecolorallocate(\$__img,".TOTAL_TXT_COLOR.");");
 eval("\$__col4=imagecolorallocate(\$__img,".TOTAL_VAL_COLOR.");");

 $__txtpad=max(strlen(LAN_TODAY),strlen(LAN_TOTAL));

 eval("imagestring(\$__img,".TODAY_TXT_SIZE.",".TODAY_TXT_YX.",\"".str_pad(LAN_TODAY,$__txtpad," ",STR_PAD_LEFT)."\",\$__col1);");
 eval("imagestring(\$__img,".TODAY_VAL_SIZE.",".TODAY_VAL_YX.",\"".str_pad(TODAY_VAL,8," ",STR_PAD_LEFT)."\",\$__col2);");
 eval("imagestring(\$__img,".TOTAL_TXT_SIZE.",".TOTAL_TXT_YX.",\"".str_pad(LAN_TOTAL,$__txtpad," ",STR_PAD_LEFT)."\",\$__col3);");
 eval("imagestring(\$__img,".TOTAL_VAL_SIZE.",".TOTAL_VAL_YX.",\"".str_pad(TOTAL_VAL,8," ",STR_PAD_LEFT)."\",\$__col4);");

 header("Content-type: image/png");
 imagepng($__img);
 imagedestroy($__img);
 return;
}

############################################################################################
# CONTATORE TESTUALE/NASCOSTO
############################################################################################

header("Content-type: text/javascript");
echo"/* This file was created by fanKounter */".EOL;

if($par__mode==="text")
 echo"document.write('".TOTAL_VAL."');".EOL;

return;

############################################################################################

?>
