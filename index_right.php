<?php

$colore_bordo_right = '#FFFFFF';

$open_border = "\n<tr><td><table width=\"95%\" border=\"0\" cellspacing=\"0\" align=\"center\" bgcolor=\"$colore_bordo_right\"><tbody><tr><td>\n<table width=\"100%\" border=\"0\" cellspacing=\"\" cellpadding=\"5\" bgcolor=\"$colore_sfondo\"><tbody><tr><td>\n";
$close_border = "</td></tr></tbody></table>\n</td></tr></tbody></table></td></tr>\n\n";

?>

<table width="95%" border="0" cellspacing="0" align="center" bgcolor="#336699"><tbody><tr><td>
	<table width="100%" border="0" cellspacing="" cellpadding="5" bgcolor="#ffffff"><tbody>
	


		<?php echo $open_border?>
		<strong>Approfondimenti:</strong>

			<br>›&nbsp;<a href="filtro6.php" name="Archivio storico per tempi">Archivio storico (tutti i risultati ordinati per tempi)</a>
			<br>›&nbsp;<a href="filtro8.php" name="grafico tempi">Grafico andamento tempi negli anni</a>


			<form action="filtro2.php" method="POST">
			›&nbsp;Archivio storico personale:
			<select name="nome">
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
				echo "<option value=\"".$elenco_i[$i]."\">".$elenco_nomi[$i]."</option>\n";
				}
	
			?>	
			</select>
			<input type="submit" value="Mostra prestazioni personali">
			</form>



			<form action="filtro4.php" method="POST">
			›&nbsp;Archivio storico annuale (tutti i risultati di un anno):
			<select name="anno">
			<?php
			$elenco_anni = array();	# elenco degli anni in archivio
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
				echo "<option value=\"".$elenco_anni[$i]."\">".$elenco_anni[$i]."</option>\n";
				}
			?>	
			</select>
			<input type="submit" value="Mostra prestazioni dell'anno">
			</form>

			›&nbsp;<a name="personaggi">I personaggi</a></li>
			<br>›&nbsp;<a name="organizzatori">Gli organizzatori</a></li>
			<br>›&nbsp;<a name="classifica partecipazioni">Classifica partecipazioni</a></li>

		<?php echo $close_border?>



	</td></tr></tbody></table>
</td></tr></tbody></table>