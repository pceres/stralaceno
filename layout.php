<?php
// PHP script per la gestione del layout prima pagina
//
// variabili in input:
//
// $archivio 			: archivio tempi (generato da load_data())
// $login				: dati utente connesso e relativi gruppi di appartenenza
// $sezione				: argomento di index che specifica il tipo di pagina da visualizzare

// carica layout colonna sinistra
$filename_layout_left = $config_dir.'layout_left.txt';
$elenco_layout_left = get_config_file($filename_layout_left,6);

// carica layout colonna destra
$filename_layout_right = $config_dir.'layout_right.txt';
$elenco_layout_right = get_config_file($filename_layout_right,6);

//
// carica i dati necessari ai vari moduli:
//

$layout_data = array(); // dati da passare ai moduli;

// proprieta' dei moduli da visualizzare:
$layout_data['moduli_left'] = '';		// proprieta' dei moduli da visualizzare nella colonna sinistra
foreach ($elenco_layout_left['elenco_moduli'] as $proprieta_modulo)
{
	$layout_data['moduli_left'][$proprieta_modulo[0]] = array_slice($proprieta_modulo,1);
}
unset($elenco_layout_left['elenco_moduli']);
$layout_data['moduli_right'] = '';		// proprieta' dei moduli da visualizzare nella colonna sinistra
foreach ($elenco_layout_right['elenco_moduli'] as $proprieta_modulo)
{
	$layout_data['moduli_right'][$proprieta_modulo[0]] = array_slice($proprieta_modulo,1);
}
unset($elenco_layout_right['elenco_moduli']);

// aggiungi eventuali script
$flag_script_Login = 0;
$flag_script_droplist_XXX = 0;
foreach (array_merge($elenco_layout_left,$elenco_layout_right) as $nome_modulo => $dati_modulo)
{
	// gestisci blocchi
	switch ($nome_modulo)
	{
	case 'Login':
		if (!$flag_script_Login)
		{
			echo "<script type=\"text/javaScript\" src=\"".$site_abs_path."admin/md5.js\"></script>\n";
			$flag_script_Login = 1;
		}
		break; // Login
		
	default:
		//
	}
	
	// gestisci sottoblocchi
	foreach ($dati_modulo as $item_data)
	{
		//echo $item_data[$indice_layout_name]."<br>\n";
		switch ($item_data[$indice_layout_name])
		{
		case 'droplist_atleti':
		case 'droplist_edizioni':
			if (!$flag_script_droplist_XXX)
			{
?>

<script type="text/javascript">
<!-- 

function valida(pform,tipo,check_null)
{
// serve a prendere il valore selezionato nella droplist, e ad inviarlo tramite il form alla relativa pagina

	var valore = "";

	if (tipo == 'anno') // tipo='anno'
	{
	  valore = pform.anno.value;
	}
	else // tipo='nome'
	{
		if (tipo == 'id_nome')
		{
			valore = pform.id_nome.value;
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

//-->
</script>

<?php
				$flag_script_droplist_XXX = 1;
			} // if !$flag_script_droplist_XXX
			break; // droplist_XXX
			
		default:
			//
		}
		
	}
}



$layout_data['archivio'] = $archivio; // archivio tempi;

//$layout_data['user'] = array('user'=>$username,'usergroups'=>$usergroups,'status'=>$login['status']); // utente;
$layout_data['user'] = $login; // utente;

// Links::
$link_list = get_link_list($filename_links); 
$layout_data['Links'] = $link_list;

// ultime_edizioni::
// edizioni disponibili
$elenco_anni = array();
for ($ii = 1; $ii < count($archivio); $ii++) {
	array_push($elenco_anni,$archivio[$ii][$indice_anno]);
}
$elenco_anni = array_unique($elenco_anni); # elimina gli anni duplicati
$elenco_anni = array_reverse($elenco_anni); # inverti l'ordine
// carica elenco delle foto disponibili
$elenco_foto = get_config_file($filename_albums,4);
$layout_data['ultime_edizioni'] = array('elenco_anni'=>$elenco_anni,'elenco_foto'=>$elenco_foto);

// Articoli::
$art_file_data 	= get_articles_path($sezione);
$path_articles 	= $art_file_data["path_articles"];	// cartella contenente gli articoli
$online_file 	= $art_file_data["online_file"];	// file contenente l'elenco degli articoli online
$elenco_articoli 	= get_online_articles($online_file); 	// carica l'elenco degli articoli da pubblicare
$articolo_corrente 	= $art_id;				// (eventuale) id dell'articolo visualizzato da solo
$layout_data['Articoli']= array('elenco_articoli'=>$elenco_articoli,'tipo_pagina'=>$sezione,'articolo_corrente'=>$articolo_corrente);

// droplist_edizioni::
$layout_data['droplist_edizioni'] = $elenco_anni; // edizioni disponibili (gia' caricate per il modulo ultime_edizioni)

// droplist_atleti::
$elenco_nomi = array();
$elenco_cognomi = array();
$elenco_id = array();
for ($i = 1; $i < count($archivio); $i++)
{
	$prestazione = $archivio[$i];
	$nome = $prestazione[$indice_nome];
	
	if (!in_array($nome,$elenco_nomi))
	{
		# estrai il cognome (escludi il nome all'inizio)
		$lista = split(" ",$nome);
		$cognome = "";
		for ($ii = 1; $ii < count($lista); $ii++)
		{
			$cognome .= " ".$lista[$ii];
		}
		array_push($elenco_nomi,$nome);
		array_push($elenco_cognomi,$cognome);
		array_push($elenco_id,$prestazione[$indice_id]);
	}
}
array_multisort($elenco_cognomi,SORT_ASC, SORT_STRING,$elenco_nomi,SORT_ASC, SORT_STRING, $elenco_id,SORT_ASC, SORT_NUMERIC);
$layout_data['droplist_atleti'] = array('elenco_nomi'=>$elenco_nomi,'elenco_id'=>$elenco_id); 


function is_visible_layout_block($layout_block,&$layout_data,$nome_layout) 
{
	$block_property = $layout_data[$nome_layout][$layout_block];
	
	$enabled_groups = split(',',$block_property[0]);
	$usergroups = $layout_data['user']['usergroups'];
	
	if (!group_match($usergroups,$enabled_groups))
	{
		return false;
	}
	
	switch ($layout_block)
	{
	case 'Links':
		if (count($layout_data[$layout_block])==0) // se non ci sono link da mostrare, e' inutile il riquadro dei link
		{
			return false;
		}
		break;
		
	case 'Articoli':
		if ($layout_data[$layout_block]['tipo_pagina']=='') // in prima pagina non ci sono argomenti, e' inutile il riquadro degli articoli
		{
			return false;
		}
		break;
		
	default:
		return true;
	}
	return true;
}



function show_layout_block_item(&$layout_block,&$layout_item,&$layout_data)
{

# dichiara variabili
extract(indici());

// vettore che associa al nome del modulo di tipo 'tempi' la rispettiva pagina php
$list_tempi_page = array('albo_d_oro' => 'filtro7.php','classifica_MF' => 'filtro9.php',
	'classifica_F' => 'filtro10.php','classifica_partecipazioni' => 'filtro11.php',
	'grafico_tempi'=>'filtro8.php','archivio_storico'=>'filtro6.php');

$item_name = $layout_item[$indice_layout_name];
$item_caption = $layout_item[$indice_layout_caption];
$item_type = $layout_item[$indice_layout_type];
$item_data = $layout_item[$indice_layout_data];
$item_disabled = $layout_item[$indice_layout_msg_disabled];
$item_enabled_groups = split(',',$layout_item[$indice_layout_enabled_groups]);

$usergroups = $layout_data['user']['usergroups'];

if (!group_match($usergroups,$item_enabled_groups))
{
	return false;
}
	
// determina l'eventuale link
if ($item_type != 'modulo')
{
	switch ($item_type)
	{
	case 'raw':
		$item_link = $layout_item[$indice_layout_data][0];
		$item_name = $layout_item[$indice_layout_data][1];
		$item_caption = $layout_item[$indice_layout_data][2];
		break;
	case 'external_link':
		$item_link = $layout_item[$indice_layout_data];
		
		// nel file di configurazione &amp; viene sostituito da "&", ma nella pagina html ci vuole "&amp;" per validare come W3C
		$item_link = str_replace("&","&amp;",$item_link);
		
		$item_name = $layout_item[$indice_layout_name];
		$item_caption = $layout_item[$indice_layout_caption];
		break;
	case 'separatore':
		echo "<tr><td colspan=\"2\"><hr></td></tr>";
		return;
		break;
	case 'tempi':
		if (!isset($list_tempi_page[$item_name]))
		{
			die('modulo tempi non riconosciuto: '.$item_name);
		}
		$item_link = $list_tempi_page[$item_name];
		$item_name = $layout_item[$indice_layout_name];
		$item_caption = $layout_item[$indice_layout_caption];
		
		if (count($layout_data['archivio']) <= 1)
		{
			$item_disabled = "Non c\'e\' ancora nessuna edizione in archivio!";
		}
		
		break;
	case 'modulo_custom':
		$item_link = "custom/moduli/$item_name/$item_name.php";
		$item_name = $layout_item[$indice_layout_name];
		$item_caption = $layout_item[$indice_layout_caption];
		
		// verifica che la pagina esista
		$filename = $root_path."custom/moduli/$item_name/$item_name.php";
		if (!file_exists($filename))
		{
			$item_disabled = 'Pagina in allestimento!';
		}
		
		break;
	default:
		die("Modulo nel layout non riconosciuto: $item_type.");
	}
	
	if (strlen($item_caption)>40)
	{
		$wrap_mode = '';
	}
	else
	{
		$wrap_mode = ' nowrap';
	}

	// inizio codice html:
	echo "\t\t\t<!-- inizio sottoblocco $item_name -->\n";

	if ($item_disabled == '')
	{
		// sottoblocco abilitato
?>
			<tr style="vertical-align: baseline">
				<td>&#8250;&nbsp;</td>
				<td align="left" width="100%" <?php echo $wrap_mode; ?>>
					<a href="<?php echo $item_link ?>" name="<?php echo $item_name ?>" class="txt_link"><?php echo $item_caption ?></a>
				</td>
			</tr>
			
<?php
	}
	else
	{
		// sottoblocco disabilitato
?>
			<tr style="vertical-align: baseline">
				<td>&#8250;&nbsp;</td>
				<td<?php echo $wrap_mode; ?>>
					<a class="disabled" onClick="alert('<?php echo $item_disabled; ?>')" name="<?php echo $item_name ?>"><?php echo $item_caption ?></a>
				</td>
			</tr>
			
<?php 
	}
}
else
{
	switch ($item_name)
	{
	case "ultime_edizioni":
		// dati esterni:
		$block_item_data = $layout_data[$item_name];
		$elenco_anni = $block_item_data['elenco_anni']; // elenco edizioni disponibili
		$elenco_foto = $block_item_data['elenco_foto']; // elenco album disponibili
		$max_last_editions = $item_data; // numero massimo di edizioni da visualizzare
		
		$numero_anni = min(count($elenco_anni),$max_last_editions); # numero delle ultime edizioni da visualizzare
		
		if ($numero_anni == 0)
		{
			return; // non ci sono edizioni da visualizzare, non mostrare proprio il sottoblocco
		}
		
?>
			<tr style="vertical-align: baseline"><td>&#8250;&nbsp;</td><td>
				<span class="txt_link">Ultime edizioni:</span><br>
<?php
		for ($i = 0; $i < $numero_anni; $i++) {
			echo "\t\t\t\t&nbsp;&nbsp;&nbsp;&nbsp;<a href=\"filtro4.php?anno=$elenco_anni[$i]\" class=\"txt_link\">Edizione $elenco_anni[$i]</a>\n";
			
			if (count($elenco_foto[$elenco_anni[$i]]) > 0)
			{
				echo "\t\t\t\t<span style=\"white-space: nowrap;\"><a class=\"txt_link\" href=\"album.php?anno=".$elenco_anni[$i]."\"> \n";
				echo "\t\t\t\t   <img src=\"".$site_abs_path."images/fotocamera-small.gif\" width=\"15\" border=\"0\" alt=\"foto_".$elenco_anni[$i]."\">\n";
				echo "\t\t\t\t</a></span>";
			}
			echo "\t\t\t\t<br>\n\n";
		}
?>
			</td></tr>
			
<?php
		break;
		
	case "droplist_edizioni":
		// dati esterni:
		$elenco_anni = $layout_data[$item_name]; // elenco edizioni disponibili
		
		if (count($elenco_anni) == 0)
		{
			return; // non ci sono edizioni da visualizzare, non mostrare proprio il sottoblocco
		}

?>
			<!-- inizio sottoblocco droplist_edizioni -->
			<tr style="vertical-align: baseline"><td>&#8250;&nbsp;</td><td>
				<div style="display: inline;"><form action="filtro4.php" method="GET" name="form_anno" style="display: inline; margin: 0;">
				<span class="txt_link">Archivio storico annuale (tutti i risultati di un anno):</span>
				<select name="anno" onChange="valida(this.form,'anno')">
					<option value="0">&nbsp;</option>
<?php	
			for ($i = 0; $i < count($elenco_anni); $i++) {
				echo "\t\t\t\t\t\t<option value=\"".$elenco_anni[$i]."\">".$elenco_anni[$i]."</option>\n";
				}
?>	
				</select>
				</form></div>
			</td></tr>
			
<?php
		
		break;
		
	case "droplist_atleti":
		// dati esterni:
		$block_item_data = $layout_data[$item_name];
		$elenco_nomi = $block_item_data['elenco_nomi']; // elenco atleti disponibili
		$elenco_id = $block_item_data['elenco_id']; // elenco id corrispondenti agli atleti disponibili
		
		if (count($elenco_nomi) == 0)
		{
			return; // non ci sono edizioni da visualizzare, non mostrare proprio il sottoblocco
		}

?>
			<!-- inizio sottoblocco droplist_atleti -->
			<tr style="vertical-align: baseline"><td>&#8250;&nbsp;</td><td style="max-width: 100px;">
				<div style="display: inline;"><form action="filtro2.php" method="GET" name="form_atleta" style="display: inline; margin: 0;">
				<span class="txt_link">Archivio storico personale:</span><br>
				<select name="id_nome" onChange="valida(this.form,'id_nome')">
					<option value="0">&nbsp;</option>
<?php
		
		for ($i = 0; $i < count($elenco_nomi); $i++) 
		{
			echo "\t\t\t\t\t<option value=\"".$elenco_id[$i]."\">".$elenco_nomi[$i]."</option>\n";
		}
		
?>
				</select>
				</form></div>
			</td></tr>
			
<?php

		
		break;
		
	default:
		die("Modulo non riconosciuto: $item_name.");
	}
}


} // end function show_layout_block_item



function show_layout_block(&$layout_block,&$layout_items, &$layout_data) 
{ 

# dichiara variabili
extract(indici());

echo "\t<!-- inizio blocco $layout_block -->\n";

$layout_type = $layout_block;
if (strpos($layout_type,"Delimitazione")===0)
{
	$layout_type = "Delimitazione";
}

switch ($layout_type)
{
case 'Delimitazione':
?>
	</tbody></table>
	<br style="line-height: 5px;">
	<table class="frame_delimiter" width="100%"><tbody>
<?php
	break;
	
case 'Amministrazione':
	echo "\t<tr><td><table class=\"column_group\"><tbody>\n\n";
	
	$layout_item_array = array("admin/index.php","interfaccia_amministrativa","Interfaccia amministrativa");
	$virtual_item = array($indice_layout_type => "raw",$indice_layout_data => $layout_item_array);
	show_layout_block_item($layout_block,$virtual_item,$layout_data);
	echo "\t</tbody></table></td></tr>\n";
	
	break;

case 'Utente':
	// dati esterni
	$user = $layout_data['user']['username'];
	$usergroups = $layout_data['user']['usergroups'];
?>
	<tr><td><table class="column_group"><tbody>
			<tr><td colspan="2">
				<span class="titolo_colonna">Spazio utente</span>
			</td></tr>
			<tr style="vertical-align: baseline">
				<td>&nbsp;</td>
				<td width="100%">
					<div class="txt_link">
						<?php
if (count($usergroups)<1)
{
	$user_msg = "Ciao $user, non appartieni a nessun gruppo.";
}
elseif (count($usergroups)==1)
{
	$user_msg = "Ciao $user, appartieni al gruppo ".$usergroups[0].".";
}
else
{
	$user_msg = "Ciao $user, appartieni ai gruppi $usergroups[0]";
	for ($i = 1; $i<count($usergroups); $i++)
	{
		$user_msg .= ", ".$usergroups[$i];
	}
	$user_msg .= ".";
}
echo $user_msg;
?>
					</div>
				</td>
			</tr>
			
<?php
		$layout_item_array = array("index.php?login_action=logout","logout","Logout");
		$virtual_item = array($indice_layout_type => "raw",$indice_layout_data => $layout_item_array);
		show_layout_block_item($layout_block,$virtual_item,$layout_data);
?>
	</tbody></table></td></tr>
<?php
	
	break;

case 'Login':
	// dati esterni
	$username = $layout_data['user']['username'];
	$usergroups = $layout_data['user']['usergroups'];
	$login_status = $layout_data['user']['status'];

	$challenge = '';
	$challenge_id = '';
	get_challenge($challenge_id,$challenge);
?>

	<tr><td><table class="column_group"><tbody>
			<tr><td colspan="2">
				<span class="titolo_colonna">Login</span>
			</td></tr>
			<tr style="vertical-align: baseline">
				<td>&nbsp;</td>
				<td width="100%">
					<div class="txt_link">
						
<?php
if (($username === 'guest') | (!in_array($login_status,array('none','ok_form','ok_cookie'))))
{

	if (!in_array($login_status,array('none','ok_form','ok_cookie')))
	{
		$login_msg = "";
		switch ($login_status)
		{
		case 'none':
		case 'ok_form':
		case 'ok_cookie':
			// non visualizza niente
			break;
			
		case 'error_wrong_username':
			$login_msg = "Username errato!";
			break;
			
		case 'error_wrong_userpass':
			$login_msg = "Password errata!";
			break;
			
		case 'error_wrong_challenge':
		case 'error_wrong_IP':
			die("Problemi! Contattare l'amministratore!");
			break;
			
		default:
			die('Todo: messaggio sconosciuto!');
		}
		echo "<div align=\"center\"><i>$login_msg</i></div><br>";
	}
?>
						<form action="index.php" method="post" onSubmit="cripta_campo_del_form_con_challenge(this,'userpass','<?php echo $challenge ?>')">
							
							user:<br><input name="username" type="text" />
							<br>
							password:<br><input name="userpass" type="password" />
							<br>
							<input name="action" type="hidden" value="login" />
							<input name="challenge" type="hidden" value="<?php echo $challenge ?>" />
							<input name="challenge_id" type="hidden" value="<?php echo $challenge_id ?>" />
							<input type="submit" value="Login" />
						</form>
						
						<!--form action="index.php" method="post">
							<input name="action" type="hidden" value="logout" />
							<input type="submit" value="Logout" />
						</form-->
						
					</div>
				</td>
			</tr>	
	</tbody></table></td></tr>
<?php
	} // end visualizzazione form login
	else
	{
?>
						<form action="index.php" method="post">
							<input name="action" type="hidden" value="logout" />
							<input type="submit" value="Logout" />
						</form>

<?php
	}
	break;

default:
	
?>
	<tr><td>
	  <table class="column_group"><tbody><tr><td>
	  
		<span class="titolo_colonna"><?php echo $layout_block; ?></span>
		 <table cellpadding="0" cellspacing="0">
		  <tbody>
		  
<?php 
		switch ($layout_block)
		{
		case "Articoli":
			// dati esterni:
			$elenco_articoli 	= $layout_data[$layout_block]['elenco_articoli']; // elenco articoli disponibili
			$sezione 		= $layout_data[$layout_block]['tipo_pagina']; // sezione degli articoli
			$articolo_corrente	= $layout_data[$layout_block]['articolo_corrente']; // (eventuale) articolo visualizzato da solo
			
			$layout_item_array = array("index.php","homepage","Torna alla homepage");
			$virtual_item = array($indice_layout_type => "raw",$indice_layout_data => $layout_item_array);
			show_layout_block_item($layout_block,$virtual_item,$layout_data);
			
			if ( ($sezione !== 'homepage') & (!empty($articolo_corrente)) )
			{
				$layout_item_array = array("index.php?page=$sezione","sez_$sezione","Torna alla sezione &quot;$sezione&quot;");
				$virtual_item = array($indice_layout_type => "raw",$indice_layout_data => $layout_item_array);
				show_layout_block_item($layout_block,$virtual_item,$layout_data);
			}
			
			for ($i = 0; $i < count($elenco_articoli); $i++)
			{
				$id = $elenco_articoli[$i];
				$art_data = load_article($id,$sezione); // carica l'articolo
				
				$layout_item_array = array("index.php?page=$sezione&amp;art_id=$id","articolo_$id","&nbsp;-&nbsp;".$art_data['titolo']);
				$virtual_item = array($indice_layout_type => "raw",$indice_layout_data => $layout_item_array);
				show_layout_block_item($layout_block,$virtual_item,$layout_data);
			} // end for  
			break;
			
		case 'Links':
			$link_list = $layout_data[$layout_block];
			for ($i = 0; $i < count($link_list); $i++)
			{ 
				$layout_item_array = array($link_list[$i][0],"link_$i",$link_list[$i][1]);
				$virtual_item = array($indice_layout_type => "raw",$indice_layout_data => $layout_item_array);
				show_layout_block_item($layout_block,$virtual_item,$layout_data);
			} // for  
			break;
			
		default:
			foreach($layout_items as $layout_item)
			{
				switch ($layout_block)
				{
					
				default:
					show_layout_block_item($layout_block,$layout_item,$layout_data);
				} // end switch
			} // end foreach $list_items
		} // end switch $layout_block
?>
			
		  </tbody>
		 </table>
		
	  </td></tr></tbody></table>
	</td></tr>
	
<?php
} // end switch $ layout_block

} // end function show_layout_block

?>
