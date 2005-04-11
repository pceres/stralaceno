<!-- 
inizio colonna centrale
-->
<table class="frame_delimiter"><tbody>	


	<?php
	//include 'libreria.php';

	$art_id=get_article_list($articles_dir);
	$art_list = array(2,3,1);
	
	for ($i = 0; $i <= count($art_list); $i++)
	{
		if (in_array($art_list[$i],$art_id))
		{
			$art_data = load_article($art_list[$i]); // carica l'articolo
			publish_article($art_data);	// visualizza l'articolo
		}
	}
	
	?>

</tbody></table>
<!-- 
fine colonna centrale
-->
