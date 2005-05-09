<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 TRANSITIONAL//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
  <title>Gestione articoli</title>
  <meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1">
  <META http-equiv="Content-Script-Type" content="text/javascript">
  <meta name="GENERATOR" content="Quanta Plus">
  <style type="text/css">@import "../css/stralaceno.css";</style>  
</head>

<body class="admin">

<SCRIPT type="text/javascript">
<!-- 

function get_list(target)
{
	var box;
	
	if (target == 'second')
	{
		box = document.forms["form_elenco_online"].second;
	}
	else
	{
		box = document.forms["form_elenco_online"].first;
	}
	
	
	var ks_text = "";
	var ks_value = "";
	for (var i = 0; i<box.options.length; i++)
	{
		if (i > 0)
		{
			ks_text += "::";
			ks_value += "::";
		}
		ks_text += box.options[i].text;
		ks_value += box.options[i].value;
	}
	
	//alert(ks_text+"\r\n"+ks_value);
	return ks_value;	

}


function populate(target)
{
	var box1; // origine
	var box2; // destinazione
	
	
	if (target == 'second')
	{
		box1 = document.forms["form_elenco_online"].first;
		box2 = document.forms["form_elenco_online"].second;
	}
	else
	{
		box2 = document.forms["form_elenco_online"].first;
		box1 = document.forms["form_elenco_online"].second;
	}
	
	var number = box1.options[box1.selectedIndex].value;
	if (!number) return;

	// copia valore in nuovo box
	box2.options[box2.options.length++] = new Option(box1.options[box1.selectedIndex].text,box1.options[box1.selectedIndex].value);
	
	// cancella valore da vecchio box
	for (var i = box1.selectedIndex; i<box1.options.length-1; i++)
	{
		box1.options[i].text = box1.options[i+1].text;
		box1.options[i].value = box1.options[i+1].value;
	}
	box1.options[box1.options.length-1] = null;
	box1.selectedIndex = -1;
}


function do_action(action,data)
{
	switch (action)
	{
	case "elenco_online":
		document.forms["form_elenco_online"].password.value=document.forms["form_upload"].password.value;
		document.forms["form_elenco_online"].task.value='set_online_articles';
		document.forms["form_elenco_online"].data.value=get_list('second');
		
		top.location.href = 'manage_articles.php';
		break;
	case "cancel":
		var msg="Vuoi davvero cancellare l'articolo con ID "+data+" ?";
		if (!confirm(msg))
		{
			return false;
		}
		document.forms["form_data"].password.value=document.forms["form_upload"].password.value;
		document.forms["form_data"].task.value='cancel';
		document.forms["form_data"].data.value=data;
		document.forms["form_data"].submit();
		
		break;
	case "edit":
		document.forms["form_data"].password.value=document.forms["form_upload"].password.value;
		document.forms["form_data"].task.value='edit';
		document.forms["form_data"].data.value=data;
		document.forms["form_data"].submit();
		
		break;
	}
}

function move_up_down(direction)
{
	var box = document.forms["form_elenco_online"].second;
	var pos = box.selectedIndex;
	var ok;
	
	if (pos >= 0)
	{
		if (direction == 'up')
		{
			ok = (pos > 0);
			step = -1;
		}
		else
		{
			ok = (pos < box.options.length);
			step = 1;
		}
		
		if (ok)
		{
			box_new = new Option(box.options[pos+step].text,box.options[pos+step].value);
			
			box.options[pos+step].text = box.options[pos].text;
			box.options[pos+step].value = box.options[pos].value;
			
			box.options[pos].text = box_new.text;
			box.options[pos].value = box_new.value;
			
			box.selectedIndex = pos+step;
		}
	}
	else
	{
		alert("Devi prima selezionare un articolo nella colonna destra!");
	}

}

//-->
</SCRIPT>


<?php

require_once('../libreria.php');

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

if (count($art_id) > 0)
{
?>

<!-- Form fittizio per il passaggio dei dati -->
<form name="form_data" action="manage_articles.php" method="post">
	<input name="password" type="hidden">
	<input name="task" type="hidden">
	<input name="data" type="hidden">
</form>

<center>

<!-- 
gestione articoli disponibili
-->
<table class="admin" align="center">
	<caption>Situazione attuale articoli</caption>

	<thead><tr>
		<th>Id</th>
		<th>Posizione in prima pagina</th>
		<th>Titolo</th>
		<th>Autore</th>
		<th>Cancella</th>
		<th>Modifica</th>
	</tr></thead>		

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
	$id = $art_id[$i]; // id dell'articolo visualizzato sulla riga
	
	echo "\t\t<tr>\n";
	
	echo "\t\t\t<td>$id</td>\n";
		
	if (in_array($id,$art_online_id))
	{
		$posiz = ($art_online_pos[$id]+1)."&ordf;";
	}
	else
	{
		$posiz = "-";
	}
	echo "\t\t\t<td>$posiz</td>\n";

	echo "\t\t\t<td>".$art_bulk[$i]['titolo']."</td>\n";

	echo "\t\t\t<td>".$art_bulk[$i]['autore']."</td>\n";

	echo "\t\t\t<td><input type=\"checkbox\" onClick=\"return do_action('cancel',$id)\"></td>\n";

	echo "\t\t\t<td><input type=\"checkbox\" onClick=\"return do_action('edit',$id)\"></td>\n";
	
	echo "\t\t</tr>\n";
}
?>
	</tbody>
</table>


<hr>

<!-- 
gestione articoli online
-->
<form name="form_elenco_online" action="manage_articles.php" method="post">

<table class="admin" style="border-collapse: collapse;" align="center">
	<caption>Gestione articoli in prima pagina</caption>
	
	<thead><tr>
		<th>Articoli ancora disponibili</th>
		<th colspan="2">Ordine articoli in prima pagina</th>
	</tr></thead> 
	
	<tbody>
	<tr>
		<td> 
			<select SIZE=<?php echo count($art_id)+1; ?> NAME="first" onDblClick="populate('second')" style="width:20em;">
<?php 
			for ($i = 0; $i < count($art_id); $i++) 
			{
				$id = $art_id[$i]; // id dell'articolo visualizzato sulla riga
				if (!in_array($id,$art_online_id))
					echo "\t\t\t\t<option value=\"".$id."\"> (id ".$id.") ".$art_bulk[$i]['titolo']."</option>\n";
			}
			?>
			</select>
			<br>
			Clicca su un articolo per pubblicarlo 
		</td>
		
		<td>
			<select SIZE=<?php echo count($art_id)+1; ?> NAME="second" onDblClick="populate('first')" style="width:20em;">
<?php 
				for ($i = 0; $i < count($art_id); $i++) 
				{
					$id = $art_id[$i]; // id dell'articolo visualizzato sulla riga
					if (in_array($id,$art_online_id))
						echo "\t\t\t\t<option value=\"".$id."\"> (id ".$id.") ".$art_bulk[$i]['titolo']."</option>\n";
				} 
?>			</select>
			<br>
			Clicca su un articolo per toglierlo dalla prima pagina
		</td>
		
		<td align="center">
			<input name="Sposta su" value="Sposta su" onClick="move_up_down('up')" type="button"> <br>
			<input name="Sposta giu'" value="Sposta giu'" onClick="move_up_down('down')" type="button"><br>
			<br>
			<input name="Applica" value="Applica" onClick="return do_action('elenco_online',1)" type="submit">
			
			<input name="password" type="hidden">
			<input name="task" type="hidden">
			<input name="data" type="hidden">
		</td>
	</tr>
</tbody>
</table>

</form>

<?php
}
else
{
?>
Non ci sono attualmente articoli sul sito!
<?php
}
?>

</center>

<hr>
<p>Pubblica un nuovo articolo:</p>

<?php 
$id = max($art_id)+1; // determina il primo id disponibile
$art_filename = "art_$id.txt";
?>
<form name="form_upload" enctype="multipart/form-data" action="upload_article.php" method="post">
	<input type="hidden" name="MAX_FILE_SIZE" value="30000">
	<input type="hidden" name="id_articolo" value="<?php echo $id; ?>">
<?php echo "\t<input type=\"hidden\" name=\"filename\" value=\"$art_filename\">\n"; ?>
	Nuovo articolo (id <?php echo $id; ?>) da caricare: <input name="userfile" type="file"><br>
	Titolo: <input name="title" type="edit"><br>
	Autore: <input name="author" type="edit"><br>
	Password: <input name="password" type="password"><br>
	<input type="Submit" value="Invia File" onClick="return confirm('Confermi la pubblicazione dell\'articolo?')">
</form>

<?php
# logga il contatto
$counter = count_page("admin_articoli",array("COUNT"=>1,"LOG"=>1),$filedir_counter); # abilita il contatore, senza visualizzare le cifre, e fai il log
?>

</body>
</html>
