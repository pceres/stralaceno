<?php

require_once('../../../libreria.php');

#
# analisi dei parametri passati alla pagina
#

# pagina da visualizzare; per ora puo' valere:
# 	'' 		: pagina di default, con tutti gli articoli in colonna centrale
#	'<sezione>'	: viene visualizzato un solo articolo, indicato dal suo id attraverso la variabile aggiuntiva 'art_id'
$sezione = $_REQUEST['page']; // contenuto da visualizzare in colonna centrale
$sezione = sanitize_user_input($sezione,'plain_text',Array());

# dichiara variabili
extract(indici($sezione));
?>

<?php
// determina il nome del modulo, ed il path assoluto
$filename = $_SERVER[SCRIPT_FILENAME];						// path assoluto e nome dello script in esecuzione
$module_endpath = substr($filename,strpos($filename,'custom/moduli/')+14);	// path della cartella contenente i moduli (custom/moduli/)
$module_name = substr($module_endpath,0,strrpos($module_endpath,'/'));		// nome del modulo in esecuzione
$module_abs_path = $modules_dir."$module_name/";				// path completo allo script in esecuzione

show_template($module_abs_path,$module_name.".tpl",$sezione);

# logga il contatto
$counter = count_page("modulo_$module_name",array("COUNT"=>1,"LOG"=>1),$filedir_counter); # abilita il contatore, senza visualizzare le cifre, e fai il log
?>

