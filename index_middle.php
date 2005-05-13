<!-- 
inizio colonna centrale
-->
<table class="frame_delimiter"><tbody>	

<?php

	$art_list = get_online_articles($article_online_file); // carica l'elenco degli articoli da pubblicare

	if (count($art_list) > 0)
	{
		for ($i = 0; $i < count($art_list); $i++)
		{
			$art_data = load_article($art_list[$i]); // carica l'articolo
			
			if (!empty($art_data)) // se l'articolo esiste...
			{
				show_article($art_data);	// visualizza l'articolo
			}
		}	
	}
	else
	{
		$art_data["titolo"]="Nessun articolo!";
		$art_data["autore"]="";
		$art_data["testo"]=array("Per pubblicare un articolo, se sei amministratore vai alla pagina admin/index.php");
		show_article($art_data);	// visualizza l'articolo
	}

	// incrementa il contatore per la homepage
	$counter = count_page("homepage",array("COUNT"=>1,"LOG"=>1),$filedir_counter); # abilita il contatore, senza visualizzare le cifre, e fai il log
?>

</tbody></table>
<!-- 
fine colonna centrale
-->
