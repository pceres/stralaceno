<!-- 
inizio colonna centrale
-->
<table class="frame_delimiter"><tbody>	

	<?php

	$art_list = get_online_articles($article_online_file); // carica l'elenco degli articoli da pubblicare
	
	for ($i = 0; $i < count($art_list); $i++)
	{
		$art_data = load_article($art_list[$i]); // carica l'articolo
		
		if (!empty($art_data)) // se l'articolo esiste...
		{
			publish_article($art_data);	// visualizza l'articolo
		}
	}	
	?>

</tbody></table>
<!-- 
fine colonna centrale
-->
