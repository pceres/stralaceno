<?php

require_once('../../../libreria.php');

# dichiara variabili
extract(indici());
?>

<?php
// determina il nome del modulo, ed il path assoluto
$filename = $_SERVER['SCRIPT_FILENAME'];						// path assoluto e nome dello script in esecuzione
$module_endpath = substr($filename,strpos($filename,'custom/moduli/')+14);	// path della cartella contenente i moduli (custom/moduli/)
$module_name = substr($module_endpath,0,strrpos($module_endpath,'/'));		// nome del modulo in esecuzione
$module_abs_path = $modules_dir."$module_name/";				// path completo allo script in esecuzione

show_template($module_abs_path,$module_name.".tpl");

# logga il contatto
$counter = count_page("modulo_$module_name",array("COUNT"=>1,"LOG"=>1),$filedir_counter); # abilita il contatore, senza visualizzare le cifre, e fai il log
?>

