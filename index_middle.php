<!-- 
inizio colonna centrale
-->
<?php
	$pagina = $_REQUEST['page']; // contenuto da visualizzare in colonna centrale
?>
<table class="frame_delimiter" width="100%"><tbody>	

<?php

	if (empty($pagina))
	{
		$art_list = get_online_articles($article_online_file); // carica l'elenco degli articoli da pubblicare
	}
	elseif ($pagina==='articolo')
	{
		$id = $_REQUEST['art_id']; // id dell'articolo da visualizzare
		$art_list = array($id); 
	}
	else
	{
		echo "Contenuto $pagina non disponibile!\n";
		return;
	}

	if (count($art_list) > 0)
	{
		for ($i = 0; $i < count($art_list); $i++)
		{
			$art_data = load_article($art_list[$i]); // carica l'articolo
			
			if (!empty($art_data)) // se l'articolo esiste...
			{
				// il primo articolo completo, gli altri riassunti
				if ($i > 0)
				{
					$mode = 'abstract';
					$link = $script_abs_path."index.php?page=articolo&amp;art_id=".$art_list[$i];
				}
				else
				{
					$mode = 'full';
					$link = ''; // inutile in modalita' full
				}
				
				show_article($art_data,$mode,$link);	// visualizza l'articolo
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
	$_COOKIE['login']['username'] = $login['username'];
	$counter = count_page("homepage",array("COUNT"=>1,"LOG"=>1),$filedir_counter); # abilita il contatore, senza visualizzare le cifre, e fai il log
?>

</tbody></table>
<!-- 
fine colonna centrale
-->
