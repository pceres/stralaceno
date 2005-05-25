<!-- 
inizio colonna destra
-->

<script type="text/javascript">
<!-- 
function valida(pform,tipo,check_null)
{
	var valore = "";

	if (tipo == 'anno')
	{
	  valore = pform.anno.value;
	}
	else
	{
		if (tipo == 'nome')
		{
			valore = pform.nome.value;
		}
	}

	if (valore  != "0")
	{
	  pform.submit();
	}
	else if (check_null == 1)
	{
	  alert("Devi scegliere prima!")
	}
}


function azzera_input()
{
/*
Questa funzione, da richiamare in seguito all'evento onLoad del tag <body>, azzera tutte le eventuali precedenti
selezioni di qualsiasi campo select all'interno del documento.
*/
	for (i = 0; i < document.forms.length; i++) {
		for (ii = 0; ii < document.forms[i].elements.length; i++) {
		   //alert(document.forms[i].name+' '+document.forms[i].elements[ii].name+' '+document.forms[i].elements[ii].type);
		   if (document.forms[i].elements[ii].type == 'select-one') {
			   document.forms[i].elements[ii].value = 0;
		   }
		}
	}
}

//-->
</script>

<table class="frame_delimiter"><tbody>	

	<tr><td>
	   <table class="column_group"><tbody><tr><td>

		<span class="titolo_colonna">Approfondimenti:</span>
		 <table cellpadding="0" cellspacing="0">
		  <tbody>
			
<?php if (count($archivio) > 0) { ?>
			<tr style="vertical-align: baseline"><td>&#8250;&nbsp;</td><td>
				<div style="display: inline;"><form action="filtro4.php" method="GET" name="form_anno" style="display: inline; margin: 0;">
				<span class="txt_link">Archivio storico annuale (tutti i risultati di un anno):</span>
				<select name="anno" onChange="valida(this.form,'anno')">
					<option value="0">&nbsp;</option>
<?php
			$elenco_anni = array();		# elenco degli anni in archivio
			$elenco_i = array();		# elenco delle i in corrispondenza delle quali trovare gli anni
			for ($i = 1; $i < count($archivio); $i++) {
				$prestazione = $archivio[$i];
				$anno = $prestazione[$indice_anno];
				if (!in_array($anno,$elenco_anni)) {
					array_push($elenco_anni,$anno);
					array_push($elenco_i,$i);
					}
				}
	
			array_multisort($elenco_anni,SORT_DESC, SORT_NUMERIC, $elenco_i,SORT_ASC, SORT_NUMERIC);
	
			for ($i = 0; $i < count($elenco_anni); $i++) {
				echo "\t\t\t\t\t<option value=\"".$elenco_anni[$i]."\">".$elenco_anni[$i]."</option>\n";
				}
?>	
				</select>
				</form></div>
			</td></tr>
<?php } // if (count($archivio) > 0) ?>



<?php if (count($archivio) > 0) { ?>
			<tr style="vertical-align: baseline"><td>&#8250;&nbsp;</td><td style="max-width: 100px;">
				<div style="display: inline;"><form action="filtro2.php" method="GET" name="form_atleta" style="display: inline; margin: 0;">
				<span class="txt_link">Archivio storico personale:</span><br>
				<select name="nome" onChange="valida(this.form,'nome')">
					<option value="0">&nbsp;</option>
<?php
			$elenco_nomi = array();
			$elenco_cognomi = array();
			$elenco_i = array();
			for ($i = 1; $i < count($archivio); $i++) {
				$prestazione = $archivio[$i];
				$nome = $prestazione[$indice_nome];
				if (!in_array($nome,$elenco_nomi)) {
		
					# estrai il cognome (escludi il nome all'inizio)
					$lista = split(" ",$nome);
					$cognome = "";
					for ($ii = 1; $ii < count($lista); $ii++) {
						$cognome .= " ".$lista[$ii];
						}
			
					array_push($elenco_nomi,$nome);
					array_push($elenco_cognomi,$cognome);
					array_push($elenco_i,$i);
					}
				}

			array_multisort($elenco_cognomi,SORT_ASC, SORT_STRING,$elenco_nomi,SORT_ASC, SORT_STRING, $elenco_i,SORT_ASC, SORT_NUMERIC);
	
			for ($i = 0; $i < count($elenco_nomi); $i++) {
				echo "\t\t\t\t\t<option value=\"".$elenco_i[$i]."\">".$elenco_nomi[$i]."</option>\n";
				}
?>	
				</select>
				</form></div>
			</td></tr>
<?php } // if (count($archivio) > 0) ?>

			<tr style="vertical-align: baseline"><td>&#8250;&nbsp;</td><td>
				<a href="filtro6.php" name="Archivio_storico_per_tempi" class="txt_link">Archivio storico (tutti i risultati ordinati per tempi)</a>
			</td></tr>
			
			<tr style="vertical-align: baseline"><td>&#8250;&nbsp;</td><td>
				<a class="disabled" onClick="alert('Pagina in allestimento!')" name="grafico_tempi" class="txt_link">Grafico andamento tempi negli anni</a>
				<!--img src="< ?php echo $site_abs_path?>images/work-in-progress.gif" alt="work in progress" width="25"-->
			</td></tr>
			
			<tr style="vertical-align: baseline"><td>&#8250;&nbsp;</td><td>
				<a class="disabled" onClick="alert('Pagina in allestimento!')" name="classifica_partecipazioni" class="txt_link">Classifica partecipazioni</a>
				<!--img src="< ?php echo $site_abs_path?>images/work-in-progress.gif" alt="work in progress" width="25"-->
			</td></tr>
			
			<tr style="vertical-align: baseline"><td>&#8250;&nbsp;</td><td>
				<a class="disabled" onClick="alert('Pagina in allestimento!')" name="personaggi" class="txt_link">I personaggi</a>
				<!--img src="< ?php echo $site_abs_path?>images/work-in-progress.gif" alt="work in progress" width="25"-->
			</td></tr>
			
		  </tbody>
		 </table>
	   
	</td></tr></tbody></table></td></tr>

</tbody></table>

	<div align="right" class="txt_normal"><i>
	Sei il visitatore n.
	<?php 
		$counter = count_page("homepage",array("COUNT"=>0,"LOG"=>0),$filedir_counter); # disabilita tutto, leggi solo il contatore
		echo $counter; 
	?>
	</i>
	</div>

<!-- 
fine colonna destra
-->
