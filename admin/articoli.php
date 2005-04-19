#!/usr/local/bin/php
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 TRANSITIONAL//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
  <title>Gestione articoli</title>
  <meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1">
  <meta name="GENERATOR" content="Quanta Plus">
  <style type="text/css">@import "/work/stralaceno2/css/stralaceno.css";</style>
</head>

<body class="admin">

<?php
include '../libreria.php';
$art_id=get_article_list($articles_dir); // carica l'elenco degli articoli disponibili ($articles_dir e' relativo alla radice)
$art_online_id=get_online_articles($article_online_file); // carica l'elenco degli articoli online ($article_online_file e' relativo alla radice)

$art_bulk = array();
for ($i = 0; $i < count($art_id); $i++)
{
	chdir('..');
	$art_data = load_article($art_id[$i]);
	chdir('admin');
	
	$art_bulk[$i] = $art_data;
}
?>

<table class="admin" align="center">
	<caption>Situazione attuale articoli</caption>
	<thead>
		<th>Id</th>
		<th>Posizione in prima pagina</th>
		<th>Titolo</th>
		<th>Autore</th>
	</thead>		
	<tbody>
<?php

$art_online_pos = array_flip($art_online_id);

$list1 = array();
for ($i = 0; $i<count($art_id); $i++)
{
	$item1 = $art_online_pos[$art_id[$i]];
	if (count($item1) == 0)
	{
		$item1 = 10000+$art_id[$i];
	}

	array_push($list1,$item1);
}

$list2 = $art_id;

array_multisort($list1,$list2,$art_id);
array_multisort($list1,$list2,$art_bulk);
array_multisort($list1,$list2,$art_online_id);

for ($i = 0; $i < count($art_id); $i++)
{
	echo "\t\t<tr>\n";
	
	echo "\t\t\t<td>$art_id[$i]</td>\n";
		
	if (in_array($art_id[$i],$art_online_id))
	{
		$posiz = ($art_online_pos[$art_id[$i]]+1)."&ordf;";
	}
	else
	{
		$posiz = "-";
	}
	echo "\t\t\t<td>$posiz</td>\n";

	echo "<td>".$art_bulk[$art_id[$i]-1]['titolo']."</td>";

	echo "<td>".$art_bulk[$art_id[$i]-1]['autore']."</td>";

	echo "\t\t</tr>\n";
}
?>
	</tbody>
</table>

<hr>
<p>Pubblica un nuovo articolo:</p>

<?php 
$id = max($art_id)+1; // determina il primo id disponibile
$art_filename = "art_$id.txt";
?>
<form enctype="multipart/form-data" action="upload_article.php" method="post">
<input type="hidden" name="MAX_FILE_SIZE" value="30000">
<input type="hidden" name="id_articolo" value="<?php echo $id; ?>">
<?php echo "<input type=\"hidden\" name=\"filename\" value=\"$art_filename\">"; ?>
Nuovo file (id <?php echo $id; ?>) da caricare: <input name="userfile" type="file">
<br>
Titolo: <input name="title" type="edit">
<br>
Autore: <input name="author" type="edit">
<br>
Password: <input name="password" type="password">
<br>
<input type="submit" value="Invia File">
</form>

<?php
# logga il contatto
$counter = count_page("admin_articoli",array("COUNT"=>1,"LOG"=>1),$filedir_counter); # abilita il contatore, senza visualizzare le cifre, e fai il log
?>

</body>
</html>
