<!-- 
inizio colonna centrale
-->
<?php
// variabili in input:
// $sezione	: argomento di index che specifica il tipo di pagina da visualizzare
// $art_id		: argomento di index che specifica l'id dell'articolo da visualizzare
	
	
	// se non specificato diversamente, la pagina richiesta e' la homepage
	if (empty($sezione))
	{
		$sezione = 'homepage';
	}

?>
<table class="frame_delimiter"><tbody>

<?php
	// individua la cartella relativa alla sezione scelta
	$art_file_data = get_articles_path($sezione);
	$path_articles 	= $art_file_data["path_articles"];	// cartella contenente gli articoli
	$online_file 	= $art_file_data["online_file"];	// file contenente l'elenco degli articoli online
	
	// verifica che la sezione esista
	if (!file_exists($online_file))
	{
		die("Sezione &quot;$sezione&quot; non disponibile!\n");
		return;
	}
	
	// la sezione esiste, gestiscila
	if (empty($art_id))
	{
		$art_list = get_online_articles($online_file); // carica l'elenco degli articoli da pubblicare
	}
	else
	{
		$art_list = array($art_id); 
	}
	
	// se c'e' almeno un articolo online...
	if (count($art_list) > 0)
	{
		for ($i = 0; $i < count($art_list); $i++)
		{
			$art_data = load_article($art_list[$i],$sezione); // carica l'articolo
			
			if (!empty($art_data)) // se l'articolo esiste...
			{
				// il primo articolo completo, gli altri riassunti
				if ($i > 0)
				{
					$mode = 'abstract';
					$link = "index.php?page=$sezione&amp;art_id=".$art_list[$i];
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
