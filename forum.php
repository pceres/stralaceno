<?php

require_once('libreria.php');

# dichiara variabili
extract(indici());

/*
questa libreria esamina i cookies o i parametri http (eventualmente) inviati, e genera l'array $login con i campi
'username',	: login
'usergroups',	: lista dei gruppi di appartenenza (separati da virgola)
'status',		: stato del login: 'none','ok_form','ok_cookie','error_wrong_username','error_wrong_userpass','error_wrong_challenge','error_wrong_IP'
*/
require_once('login.php');

$action = $_REQUEST['action'];			// azione da eseguire
$data = $_REQUEST['data'];			// dati associati all'azione

// azione di default
if (empty($action))
{
	$action = "list_forums";
}

$file_forums = $root_path."custom/forums/forums.php";	// nome del file di configurazione dei forums

# formato file di configurazione 'forums.php'
$indice_forums_id = 0;
$indice_forums_caption = 1;
$indice_forums_description = 2;
$indice_forums_status = 3;
$indice_forums_read_groups = 4;
$indice_forums_write_groups = 5;
$indice_forums_auth_mode = 6;
$indice_forums_topics = 7;
$indice_forums_last_post = 8;


$file_forum_format = $root_path."custom/forums/forum_%d.php";	// formato nome del file di configurazione del forum x-esimo

# formato file di configurazione 'forum_X.php'
$indice_topic_id = 0;
$indice_topic_caption = 1;
$indice_topic_status = 2;
$indice_topic_read_groups = 3;
$indice_topic_write_groups = 4;
$indice_topic_author = 5;
$indice_topic_posts = 6;
$indice_topic_last_post = 7;


$file_topic_format = $root_path."custom/forums/topic_%d_%d.php";// formato nome del file di configurazione del topic j-esimo del forum i-esimo

# formato file di configurazione 'topic_I_J.php'
$indice_post_id = 0;
$indice_post_author = 1;
$indice_post_date = 2;
$indice_post_status = 3;


// carica file di configurazione dei forums
$forums = get_config_file($file_forums);

$footer_message = 'Servizio forum realizzato da : <a href="http://ars.altervista.org/" target="_blank">ArsWeb</a>';

while ($action)
{
	switch ($action)
	{
	case "list_forums":
		print_header("Elenco dei forums");
		
		echo '<br><center>'."\n";
		echo '<table border="0" cellpadding="5" cellspacing="1" class="tabella_forum"><tbody>'."\n";
		echo '<tr class="TSfondoMedio">';
		echo '<td>Forum</td><td><center>Discussioni</center></td><td><center>Ultimo Messaggio</center></td>';
		echo '</tr>'."\n";
		
		$showed = 0;
		foreach ($forums['elenco_forums'] as $forum_item)
		{
			$forum_id = $forum_item[$indice_forums_id];
			$forum_caption = $forum_item[$indice_forums_caption];
			$forum_description = $forum_item[$indice_forums_description];
			$forum_status = $forum_item[$indice_forums_status];
			$forum_read_groups = $forum_item[$indice_forums_read_groups];
			$forum_write_groups = $forum_item[$indice_forums_write_groups];
			$forum_auth_mode = $forum_item[$indice_forums_auth_mode];
			$forum_topics = $forum_item[$indice_forums_topics];
			$forum_last_post = split(',',$forum_item[$indice_forums_last_post]);
			
			if (count($forum_last_post)==2)
			{
				$topic_id = $forum_last_post[0];
				$post_id = $forum_last_post[1];
				$last_post = get_forum_post($forum_id,$topic_id,$post_id);
				
				$forum_last_post_author = $last_post[$indice_post_author];
				$forum_last_post_date = $last_post[$indice_post_date];
			}
			
			if ($forum_status !== 'hidden')
			{
				$showed = 1;

?>
	<tr class="TSfondoChiaro">
		
		<td class="TSfondoChiaro">
		<a class="s_link" title="Clicca per visualizzare i messaggi di questo Forum" href="forum.php?action=list_topics&amp;data=<?php echo $forum_id; ?>">
			<b><?php echo $forum_caption; ?></b></a>
			<?php
			switch ($forum_status)
			{
			case 'open':
				echo "(Aperto)";
				break;
			case 'closed':
				echo "(Chiuso)";
				break;
			default:
				die("forums_status sconosciuto: $forum_status");
			}
			?>
		<br><br>
		<p style="margin-left:3em;">
		<?php echo $forum_description; ?>
		<br><br>
		</td>
		
		<td><center>
		<?php
		if ($forum_topics > 0)
		{
			echo "$forum_topics\n";
		}
		else
		{
			echo "0\n";
		}
		?>
		</center></td>
		
		<td class="Small" nowrap="nowrap" valign="middle"><center>
		<?php 
		if (!empty($forum_last_post_author))
		{
			echo "$forum_last_post_date<br><br>di $forum_last_post_author";
		}
		else
		{
			echo "<br>";
		}
		?>
		</center></td>
	</tr>

<?php
			}
		}
		
		echo "</tbody></table>\n";
		
		if (!empty($footer_message))
		{
			echo '<br><font face="VERDANA" size="1"><b>'.$footer_message.'</b></font><br>'."\n";
		}
		
		if (empty($showed))
		{
			echo "<br>Attualmente non c'e' nessun forum disponibile!";
		}
		
		echo '</center>'."\n";
		
		print_footer();
		
		$action = '';
		break;
		
	case "list_topics":
		$id_forum = $data;	// i dati rappresentano il forum
		$forum_item = $forums['elenco_forums'][$id_forum];
		
		$forum_id = $forum_item[$indice_forums_id];
		$forum_caption = $forum_item[$indice_forums_caption];
		$forum_description = $forum_item[$indice_forums_description];
		$forum_status = $forum_item[$indice_forums_status];
		$forum_read_groups = $forum_item[$indice_forums_read_groups];
		$forum_write_groups = $forum_item[$indice_forums_write_groups];
		$forum_auth_mode = $forum_item[$indice_forums_auth_mode];
		$forum_topics = $forum_item[$indice_forums_topics];
		$forum_last_post = split(',',$forum_item[$indice_forums_last_post]);
		
		// verifica autorizzazioni in lettura
		$usergroups = $login['usergroups'];
		if (!group_match($usergroups,split(',',$forum_read_groups)))
		{
			error_message("Mi dispiace, non sei abilitato ad accedere a questo forum.");
			
			$action = '';
			continue;
		}
		
		$file_forum = sprintf($file_forum_format,$forum_id);
		$topics = get_config_file($file_forum);
		
		print_header($forum_caption);
		
		echo '<br><center>'."\n";

?>

<table border="0" cellpadding="0" cellspacing="1" width="728">
<tbody><tr><td align="right">
<!--<a href="forum.php?action=new_post&amp;data=<?php echo $forum_id; ?>,<?php echo $topic_id; ?>">
	<img src="<?php echo $site_abs_path ?>images/forum_rispondi.gif" border="0" alt="nuovo messaggio"></a>
&nbsp;-->
<a href="forum.php?action=new_topic&amp;data=<?php echo $forum_id; ?>">
	<img src="<?php echo $site_abs_path ?>images/forum_nuova_discussione.gif" border="0" alt="nuova discussione"></a>
</td></tr>
</tbody></table>


<?php



		echo '<table border="0" cellpadding="5" cellspacing="1" class="tabella_forum" width="728"><tbody>'."\n";

?>

<tr>
	<td colspan="4" class="titoloBlu" valign="middle">
	
	<a href="forum.php?action=list_forums" class="sfondoscuro">
		<?php echo $web_title; ?></a> �
		<?php echo $forum_caption; ?>
	</td>
</tr>

<?php
		echo '<tr class="TSfondoMedio">';
		echo '<td>Discussione</td><td><center>Aperta da</center></td><td><center>Messaggi</center></td><td><center>Ultimo Messaggio</center></td>';
		echo '</tr>'."\n";
		
		$showed = 0;
		foreach ($topics['elenco_topics'] as $topic_item)
		{
			$topic_id = $topic_item[$indice_topic_id];
			$topic_caption = $topic_item[$indice_topic_caption];
			$topic_status = $topic_item[$indice_topic_status];
			$topic_read_groups = $topic_item[$indice_topic_read_groups];
			$topic_write_groups = $topic_item[$indice_topic_write_groups];
			$topic_author = $topic_item[$indice_topic_author];
			$topic_posts = $topic_item[$indice_topic_posts];
			$topic_last_post = $topic_item[$indice_topic_last_post];
			
			if (strlen($topic_last_post.' ') > 1)
			{
				$last_post = get_forum_post($forum_id,$topic_id,$topic_last_post);
				
				$topic_last_post_author = $last_post[$indice_post_author];
				$topic_last_post_date = $last_post[$indice_post_date];
			}
			else
			{
				unset($topic_last_post_author);
				unset($topic_last_post_date);
			}
			
			if ($topic_status !== 'hidden')
			{
				$showed = 1;

?>
	<tr class="TSfondoChiaro">
		<td class="TSfondoChiaro">
		<a class="s_link" title="Clicca per visualizzare i messaggi di questa discussione" 
			href="forum.php?action=list_posts&amp;data=<?php echo $forum_id; ?>,<?php echo $topic_id; ?>">
			<b><?php echo $topic_caption; ?></b></a>
			<?php
			switch ($topic_status)
			{
			case 'open':
				echo "(Aperto)";
				break;
			case 'closed':
				echo "(Chiuso)";
				break;
			default:
				die("topic_status sconosciuto: $topic_status");
			}
			?>
		</td>
		
		<td><center>
		<?php
		echo $topic_author;
		?>
		</center></td>
		
		<td><center>
		<?php
		if ($topic_posts > 0)
		{
			echo "$topic_posts\n";
		}
		else
		{
			echo "0\n";
		}
		?>
		</center></td>
		<td class="Small" nowrap="nowrap" valign="middle"><center>
		<?php 
		if (strlen($topic_last_post_author.' ') > 1)
		{
			echo "$topic_last_post_date<br>di $topic_last_post_author ";
		}
		else
		{
			echo "<br>";
		}
		?>
		</center></td>
	</tr>

<?php
			}
		}
		
		echo "</tbody></table>\n";
		
		if (!empty($footer_message))
		{
			echo '<br><font face="VERDANA" size="1"><b>'.$footer_message.'</b></font><br>'."\n";
		}
		
		if (empty($showed))
		{
			echo "<br>Attualmente non ci sono discussioni disponibili!";
		}
		
		echo '</center>'."\n";
		
		print_footer();
		
		$action = '';
		break;
	case "list_posts":
		$coord_topic = split(',',$data);	// I,Y -> forum I, topic Y -> topic_I_Y.php
		
		$forum_id = $coord_topic[0];
		$topic_id = $coord_topic[1];
		
		// read forum info
		$forums = get_config_file($file_forums);
		$forum_item = $forums['elenco_forums'][$forum_id];
		
		$forum_id = $forum_item[$indice_forums_id];
		$forum_caption = $forum_item[$indice_forums_caption];
		$forum_description = $forum_item[$indice_forums_description];
		$forum_status = $forum_item[$indice_forums_status];
		$forum_read_groups = $forum_item[$indice_forums_read_groups];
		$forum_write_groups = $forum_item[$indice_forums_write_groups];
		$forum_auth_mode = $forum_item[$indice_forums_auth_mode];
		$forum_topics = $forum_item[$indice_forums_topics];
		$forum_last_post = split(',',$forum_item[$indice_forums_last_post]);

		// read topic info
		$file_forum = sprintf($file_forum_format,$forum_id);
		$topics = get_config_file($file_forum);
		$topic_item = $topics['elenco_topics'][$topic_id];
		
		$topic_id = $topic_item[$indice_topic_id];
		$topic_caption = $topic_item[$indice_topic_caption];
		$topic_status = $topic_item[$indice_topic_status];
		$topic_read_groups = $topic_item[$indice_topic_read_groups];
		$topic_write_groups = $topic_item[$indice_topic_write_groups];
		$topic_author = $topic_item[$indice_topic_author];
		$topic_posts = $topic_item[$indice_topic_posts];
		$topic_last_post = $topic_item[$indice_topic_last_post];
		
		// read posts info
		$file_topic = sprintf($file_topic_format,$forum_id,$topic_id);
		$posts = get_config_file($file_topic);
		
		// verifica autorizzazioni in lettura
		$usergroups = $login['usergroups'];
		if (!group_match($usergroups,split(',',$topic_read_groups)))
		{
			error_message("Mi dispiace, non sei abilitato ad accedere a questa discussione.");
			
			$action = '';
			continue;
		}
		
		print_header($topic_caption);




?>





<br>
<center>


<table border="0" cellpadding="0" cellspacing="1" width="100%">
<tbody><tr><td align="right">
<a href="forum.php?action=new_post&amp;data=<?php echo $forum_id; ?>,<?php echo $topic_id; ?>">
	<img src="<?php echo $site_abs_path ?>images/forum_rispondi.gif" border="0" alt="nuovo messaggio"></a>
&nbsp;
<a href="forum.php?action=new_topic&amp;data=<?php echo $forum_id; ?>">
	<img src="<?php echo $site_abs_path ?>images/forum_nuova_discussione.gif" border="0" alt="nuova discussione"></a>
</td></tr>
</tbody></table>


<table class="tabella" border="0" cellpadding="5" cellspacing="1" width="100%">

<tbody>


<tr>
	<td colspan="2" class="titoloBlu" valign="middle">
	
	<a href="forum.php?action=list_forums" class="sfondoscuro">
		<?php echo $web_title; ?></a> �
	<a href="forum.php?action=list_topics&amp;data=<?php echo $forum_id; ?>" class="sfondoscuro">
		<?php echo $forum_caption; ?></a> � 
		<?php echo $topic_caption; ?>
	
	</td>
</tr>


<?php

		$showed = 0;
// 		foreach ($posts['elenco_posts'] as $post_item)
		for ($indice_post = count($posts['elenco_posts'])-1; $indice_post >= 0; $indice_post--)
		{
  			$post_item = $posts['elenco_posts'][$indice_post];
			
			$post_id = $post_item[$indice_post_id];
			$post_author = $post_item[$indice_post_author];
			$post_date = $post_item[$indice_post_date];
			$post_status = $post_item[$indice_post_status];
			
			$post_text = $posts['post_text_'.$post_id];
			
			if ($post_status !== 'hidden')
			{
				$showed = 1;

?>


<tr class="TSfondoMedio">
	<td class="Small"><center><b><?php echo $post_author; ?></b></center></td>
	<td class="Small">
		<b>
<!--		<img src="caposele_data/icon_smile.gif" align="middle" hspace="5" alt="emoticon">-->
		
		Inviato il <?php echo $post_date; ?></b>
	</td>
</tr>

<tr class="TSfondoChiaro">
	<td nowrap="nowrap">&nbsp;</td>
	
	<td class="Small">
<?php

if ($post_status == 'visible')
{
	foreach ($post_text as $post_line)
	{
		echo $post_line[0]."<br>";
	}
}
elseif ($post_status == 'censored')
{
	echo "<i><b>Testo censurato!</b></i>";
}

?>
	
		<p align="right">
			<a href="forum.php?action=reply_post&amp;data=<?php echo $forum_id; ?>,<?php echo $topic_id; ?>,<?php echo $post_id; ?>">
				<img src="<?php echo $site_abs_path ?>images/forum_citazione.gif" border="0" alt="rispondi al messaggio">
			</a>
		</p>
	</td>
</tr>


<?php
			} // end if ($topic_status !== 'hidden')
		} // end foreach ($posts['elenco_posts'] as $post_item)

?>



</tbody></table>


<?php

		if (!empty($footer_message))
		{
			echo '<br><font face="VERDANA" size="1"><b>'.$footer_message.'</b></font><br>'."\n";
		}
		
		if (empty($showed))
		{
			echo "<br>Attualmente non ci sono discussioni disponibili!";
		}
		
		echo '</center>'."\n";




// 		print_r($posts);

		print_footer();
		
		$action = '';
		break;
	case "new_post":
		
		$coord_topic = split(',',$data);	// I,Y -> forum I, topic Y -> topic_I_Y.php
		
		$forum_id = $coord_topic[0];
		$topic_id = $coord_topic[1];
		
		// read forum info
		$forums = get_config_file($file_forums);
		$forum_item = $forums['elenco_forums'][$forum_id];
		
		$forum_id = $forum_item[$indice_forums_id];
		$forum_caption = $forum_item[$indice_forums_caption];
		$forum_description = $forum_item[$indice_forums_description];
		$forum_status = $forum_item[$indice_forums_status];
		$forum_read_groups = $forum_item[$indice_forums_read_groups];
		$forum_write_groups = $forum_item[$indice_forums_write_groups];
		$forum_auth_mode = $forum_item[$indice_forums_auth_mode];
		$forum_topics = $forum_item[$indice_forums_topics];
		$forum_last_post = split(',',$forum_item[$indice_forums_last_post]);
		
		// read topic info
		$file_forum = sprintf($file_forum_format,$forum_id);
		$topics = get_config_file($file_forum);
		$topic_item = $topics['elenco_topics'][$topic_id];
		
		$topic_id = $topic_item[$indice_topic_id];
		$topic_caption = $topic_item[$indice_topic_caption];
		$topic_status = $topic_item[$indice_topic_status];
		$topic_read_groups = $topic_item[$indice_topic_read_groups];
		$topic_write_groups = $topic_item[$indice_topic_write_groups];
		$topic_author = $topic_item[$indice_topic_author];
		$topic_posts = $topic_item[$indice_topic_posts];
		$topic_last_post = $topic_item[$indice_topic_last_post];
		
		// read posts info
		$file_topic = sprintf($file_topic_format,$forum_id,$topic_id);
		$posts = get_config_file($file_topic);
		
		// verifica autorizzazioni in lettura
		$usergroups = $login['usergroups'];
		if (!group_match($usergroups,split(',',$topic_write_groups)))
		{
			error_message("Mi dispiace, non sei abilitato a scrivere messaggi in questa discussione.");
			
			$action = '';
			continue;
		}
		
		// determina il tipo di autenticazione per l'autore del post
		switch ($forum_auth_mode)
		{
		case 'logged':
			$post_auth_token = $login['username'];	// loginname, se diverso da guest
			$post_auth_mode  = 'disabled';		// 'disabled' o ''
			break;
		case 'anonimous':
			$post_auth_token = '';
			$post_auth_mode  = '';
			break;
		default:
			die("Tipo di autenticazione sconosciuta: $forum_auth_mode");
			break;
		}
		
		$new_post_id = count($posts['elenco_posts']);	// id del post da salvare
		
		
		print_header($forums_caption);
		

?>

<br>
<center>
<form action="forum.php?action=write_post" method="post" name="forma">
	
	<input name="post_forum_id" value="<?php echo $forum_id; ?>" type="hidden">
	<input name="post_topic_id" value="<?php echo $topic_id; ?>" type="hidden">
<!--	<input name="post_id" value="<?php echo $new_post_id; ?>" type="hidden">-->
	
	<table class="tabella_forum" align="center" cellpadding="5" cellspacing="1" width="728">
	<tbody>
		<tr>
			<td colspan="2" class="titoloBlu" valign="middle">
				<a href="forum.php?action=list_forums" class="sfondoscuro">
					<?php echo $web_title; ?></a> �
				<a href="forum.php?action=list_topics&amp;data=<?php echo $forum_id; ?>" class="sfondoscuro">
					<?php echo $forum_caption; ?></a> �
					<?php echo $topic_caption; ?> �
				nuovo messaggio
			</td>
		</tr>
		
		<tr class="TSfondoChiaro">
			<td class="Small" align="left" nowrap="nowrap" valign="middle">
				<b>Nome o Nick</b>
			</td>
			<td valign="top">
				<input name="post_nick" value="<?php echo $post_auth_token; ?>" type="text" <?php echo $post_auth_mode; ?>>
			</td>
		</tr>
		
		<tr class="TSfondoChiaro">
			<td class="Small" align="left" valign="middle">
				<b>Email</b>
			</td>
			<td class="Small" valign="top">
				<input name="post_email" type="text">
				<!--<input name="notifica" value="si" type="checkbox">Avvisami quando qualcuno risponde-->
			</td>
		</tr>
		
		<tr class="TSfondoChiaro">
			<td class="Small" align="left" valign="middle">
				<b>Sito WEB</b>
			</td>
			<td valign="top">
				<input name="post_web" value="http://" size="40" type="text">
			</td>
		</tr>
		
<!--		<tr class="TSfondoChiaro">
			<td class="Small" align="left" valign="middle">
				<b>Titolo</b>
			</td>
			<td valign="top">
				<input name="post_titolo" size="40" value="" type="text">
			</td>
		</tr>-->
		
		<tr class="TSfondoChiaro">
			<td class="Small" align="left" valign="top">
				<b>Messaggio</b>
			</td>
			<td valign="top">
				<textarea cols="90" rows="13" name="post_testo">Qui il tuo messaggio</textarea>
			</td>
		</tr>
		
<!--		<tr class="TSfondoChiaro">
			<td class="Small" align="left" valign="top">
				<b>Faccina:</b>
			</td>
			<td align="center" valign="top">


<table align="center" border="0" cellpadding="0" cellspacing="0" width="100%"><tbody>
	<tr>
		<td align="center">
			<img src="forum_nuovo_mex.asp%7Egruppo%7E32676%7EID%7E6179%7Ethread%7E0%7Ewidth%7E_files/icon_smile_sad.gif" alt="smile_sad">
			<input name="post_simbolo" value="icon_smile_sad.gif" type="radio">
		</td>
		
		<td align="center">
			<img src="forum_nuovo_mex.asp%7Egruppo%7E32676%7EID%7E6179%7Ethread%7E0%7Ewidth%7E_files/icon_smile_angry.gif" alt="smile_angry">
			<input name="post_simbolo" value="icon_smile_angry.gif" type="radio">
		</td>
		
		<td align="center">
			<img src="forum_nuovo_mex.asp%7Egruppo%7E32676%7EID%7E6179%7Ethread%7E0%7Ewidth%7E_files/icon_smile_approve.gif" alt="smile_approve">
			<input name="post_simbolo" value="icon_smile_approve.gif" type="radio">
		</td>
		
		
		<td align="center">
			<img src="forum_nuovo_mex.asp%7Egruppo%7E32676%7EID%7E6179%7Ethread%7E0%7Ewidth%7E_files/icon_smile_big.gif" alt="smile_approve">
			<input name="post_simbolo" value="icon_smile_big.gif" type="radio">
		</td>
		
		<td align="center">
			<img src="forum_nuovo_mex.asp%7Egruppo%7E32676%7EID%7E6179%7Ethread%7E0%7Ewidth%7E_files/icon_smile_blackeye.gif" alt="smile_blackeye">
			<input name="post_simbolo" value="icon_smile_blackeye.gif" type="radio">
		</td>
		
		<td align="center">
			<img src="forum_nuovo_mex.asp%7Egruppo%7E32676%7EID%7E6179%7Ethread%7E0%7Ewidth%7E_files/icon_smile_blush.gif" alt="smile_blush">
			<input name="post_simbolo" value="icon_smile_blush.gif" type="radio">
		</td>
		
		<td align="center">
			<img src="forum_nuovo_mex.asp%7Egruppo%7E32676%7EID%7E6179%7Ethread%7E0%7Ewidth%7E_files/icon_smile_clown.gif" alt="smile_clown">
			<input name="post_simbolo" value="icon_smile_clown.gif" type="radio">
		</td>
		
		<td align="center">
			<img src="forum_nuovo_mex.asp%7Egruppo%7E32676%7EID%7E6179%7Ethread%7E0%7Ewidth%7E_files/icon_smile_cool.gif" alt="smile_cool">
			<input name="post_simbolo" value="icon_smile_cool.gif" type="radio">
		</td>
		
		<td align="center">
			<img src="forum_nuovo_mex.asp%7Egruppo%7E32676%7EID%7E6179%7Ethread%7E0%7Ewidth%7E_files/icon_smile_dead.gif" alt="smile_dead">
			<input name="post_simbolo" value="icon_smile_dead.gif" type="radio">
		</td>
	</tr>
	
	<tr>
		<td align="center">
			<img src="forum_nuovo_mex.asp%7Egruppo%7E32676%7EID%7E6179%7Ethread%7E0%7Ewidth%7E_files/icon_smile.gif" alt="smile">
			<input name="post_simbolo" value="icon_smile.gif" type="radio">
		</td>
		
		<td align="center">
			<img src="forum_nuovo_mex.asp%7Egruppo%7E32676%7EID%7E6179%7Ethread%7E0%7Ewidth%7E_files/icon_smile_dissapprove.gif" alt="smile_disapprove">
			<input name="post_simbolo" value="icon_smile_dissapprove.gif" type="radio">
		</td>
		
		<td align="center">
			<img src="forum_nuovo_mex.asp%7Egruppo%7E32676%7EID%7E6179%7Ethread%7E0%7Ewidth%7E_files/icon_smile_evil.gif" alt="smile_evil">
			<input name="post_simbolo" value="icon_smile_evil.gif" type="radio">
		</td>
			
		<td align="center">
			<img src="forum_nuovo_mex.asp%7Egruppo%7E32676%7EID%7E6179%7Ethread%7E0%7Ewidth%7E_files/icon_smile_kisses.gif" alt="smile_kisses">
			<input name="post_simbolo" value="icon_smile_kisses.gif" type="radio">
		</td>
		
		<td align="center">
		<img src="forum_nuovo_mex.asp%7Egruppo%7E32676%7EID%7E6179%7Ethread%7E0%7Ewidth%7E_files/icon_smile_question.gif" alt="smile_question">
		<input name="post_simbolo" value="icon_smile_question.gif" type="radio">
		</td>
		
		<td align="center">
			<img src="forum_nuovo_mex.asp%7Egruppo%7E32676%7EID%7E6179%7Ethread%7E0%7Ewidth%7E_files/icon_smile_shock.gif" alt="smile_shock">
			<input name="post_simbolo" value="icon_smile_shock.gif" type="radio">
		</td>
		
		<td align="center">
			<img src="forum_nuovo_mex.asp%7Egruppo%7E32676%7EID%7E6179%7Ethread%7E0%7Ewidth%7E_files/icon_smile_sleepy.gif" alt="smile_sleepy">
			<input name="post_simbolo" value="icon_smile_sleepy.gif" type="radio">
		</td>
		
		<td align="center">
			<img src="forum_nuovo_mex.asp%7Egruppo%7E32676%7EID%7E6179%7Ethread%7E0%7Ewidth%7E_files/icon_smile_tongue.gif" alt="smile_tongue">
			<input name="post_simbolo" value="icon_smile_tongue.gif" type="radio">
		</td>
	
		<td align="center">
			<img src="forum_nuovo_mex.asp%7Egruppo%7E32676%7EID%7E6179%7Ethread%7E0%7Ewidth%7E_files/icon_smile_wink.gif" alt="smile_wink">
			<input name="post_simbolo" value="icon_smile_wink.gif" type="radio">
		</td>
	</tr>
</tbody></table>


			</td>
		</tr>-->
		
		<tr>
			<td colspan="2" align="center" nowrap="nowrap">
				<br>
				<input name="post_azione1" value="INVIA" type="submit">
				<input name="post_azione2" value="ANNULLA" onclick="history.go(-1);" type="button">
				<br>
			</td>
		</tr>
	</tbody>
	</table>
	
</form>


<?php


		if (!empty($footer_message))
		{
			echo '<br><font face="VERDANA" size="1"><b>'.$footer_message.'</b></font><br>'."\n";
		}
		
		echo '</center>'."\n";


		print_footer();
		
		$action = '';
		break;
		
	case "write_post":
		
		$post_forum_id = $_REQUEST['post_forum_id'];
		$post_topic_id = $_REQUEST['post_topic_id'];
		$post_nick = $_REQUEST['post_nick'];
		$post_email = $_REQUEST['post_email'];
		$post_web = $_REQUEST['post_web'];
		$post_titolo = $_REQUEST['post_titolo'];
		$post_testo = $_REQUEST['post_testo'];
		$post_azione1 = $_REQUEST['post_azione1'];
		$post_azione2 = $_REQUEST['post_azione2'];
		
		
		$forum_id = $post_forum_id;
		$topic_id = $post_topic_id;
		
		// read forum info
		$forums = get_config_file($file_forums);
		$forum_item = $forums['elenco_forums'][$forum_id];
		
		$forum_id = $forum_item[$indice_forums_id];
		$forum_caption = $forum_item[$indice_forums_caption];
		$forum_description = $forum_item[$indice_forums_description];
		$forum_status = $forum_item[$indice_forums_status];
		$forum_read_groups = $forum_item[$indice_forums_read_groups];
		$forum_write_groups = $forum_item[$indice_forums_write_groups];
		$forum_auth_mode = $forum_item[$indice_forums_auth_mode];
		$forum_topics = $forum_item[$indice_forums_topics];
		$forum_last_post = split(',',$forum_item[$indice_forums_last_post]);
		
		// read topic info
		$file_forum = sprintf($file_forum_format,$forum_id);
		$topics = get_config_file($file_forum);
		$topic_item = $topics['elenco_topics'][$topic_id];
		
		$topic_id = $topic_item[$indice_topic_id];
		$topic_caption = $topic_item[$indice_topic_caption];
		$topic_status = $topic_item[$indice_topic_status];
		$topic_read_groups = $topic_item[$indice_topic_read_groups];
		$topic_write_groups = $topic_item[$indice_topic_write_groups];
		$topic_author = $topic_item[$indice_topic_author];
		$topic_posts = $topic_item[$indice_topic_posts];
		$topic_last_post = $topic_item[$indice_topic_last_post];
		
		// read posts info
		$file_topic = sprintf($file_topic_format,$forum_id,$topic_id);
		$posts = get_config_file($file_topic);
		
		$new_post_id = count($posts['elenco_posts']);
		
		
		// verifica autorizzazioni in lettura
		$usergroups = $login['usergroups'];
		if (!group_match($usergroups,split(',',$topic_write_groups)))
		{
			error_message("Mi dispiace, non sei abilitato a scrivere messaggi in questa discussione.");
			
			$action = '';
			continue;
		}
		
		// aggiorna le strutture in forums.php
		$forum_topics += 1;
		$forum_last_post = "$topic_id,$new_post_id";
		
		$forums['elenco_forums'][$forum_id][$indice_forums_topics] = $forum_topics;
		$forums['elenco_forums'][$forum_id][$indice_forums_last_post] = $forum_last_post;
		
		
		// aggiorna le strutture in forum_X.php
		$topic_posts += 1;
		$topic_last_post = $post_id;
		
		$topics['elenco_topics'][$topic_id][$indice_topic_posts] = $topic_posts;
		$topics['elenco_topics'][$topic_id][$indice_topic_las_post] = $topic_last_post;
		
		
		// aggiorna le strutture in topic_X_Y.php
		$post_id = $new_post_id;
		$post_author = $post_nick;
		$post_date = date('d/m/Y H.i.s');
		$post_status = 'visible';	// di default il post e' visibile
		
		$posts['elenco_posts'][$post_id][$indice_post_id] = $post_id;
		$posts['elenco_posts'][$post_id][$indice_post_author] = $post_author;
		$posts['elenco_posts'][$post_id][$indice_post_date] = $post_date;
		$posts['elenco_posts'][$post_id][$indice_post_status] = $post_status;
		
		$posts['post_text_'.$post_id] = array();
		foreach (split("\n",$post_testo) as $id_riga => $riga)
		{
			$ks = rtrim($riga);
			if (empty($ks))
			{
				$riga = "&nbsp;";
			}
			$posts['post_text_'.$post_id][$id_riga][0] = $riga;
		}
		
		
		// verifica che non si salvi piu' volte lo stesso messaggio
		$ultimo_testo = $posts['post_text_'.$post_id];
		$penultimo_testo = $posts['post_text_'.($post_id-1)];
		
		$stesso_messaggio = true;
		foreach ($ultimo_testo as $id_riga => $riga)
		{
			$flag = (rtrim($ultimo_testo[$id_riga][0]) == rtrim($penultimo_testo[$id_riga][0]));
			if (!$flag)
			{
				$stesso_messaggio = false;
			}
		}
		
		// se il messaggio e' nuovo, salvalo
		if (!$stesso_messaggio)
		{
			save_config_file($file_forums,$forums);
			save_config_file($file_forum,$topics);
			save_config_file($file_topic,$posts);
		}
		
		
		// visualizza pagina
		print_header($forums_caption);
		
		if ($stesso_messaggio)
		{
			die("Messaggio gi&agrave; salvato.");
		}
		else
		{
			echo "Il messaggio &egrave; stato salvato correttamente.<br>\n";
			echo "<hr>";
			echo "<div align=\"right\"><a class=\"txt_link\" href=\"forum.php?action=list_posts&amp;data=$forum_id,$topic_id\">Torna al forum</a></div>";
		}
		
		
		print_footer();
		
		$action = '';
		break;
	case "zzz_topics":
		
		print_header($forums_caption);
		
		print_footer();
		
		$action = '';
		break;
	default:
		die('Unknown action:'.$action);
	}
}




////////////////////////////////////////////////////////////////////////////////
function print_footer() {

# dichiara variabili
extract(indici());

echo $homepage_link;

# logga il contatto
$counter = count_page("forum",array("COUNT"=>1,"LOG"=>1),$filedir_counter); # abilita il contatore, senza visualizzare le cifre, e fai il log

?>

</body></html>


<?php
} // end function print_footer


function print_header($lotteria_nome) {

# dichiara variabili
extract(indici());

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 TRANSITIONAL//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>

<head>
  <title><?php echo $web_title ?> - <?php echo $lotteria_nome ?></title>
  <meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1">
  <meta name="GENERATOR" content="Kate">
  <meta name="description" content="Analisi statistica dei dati relativi a <?php echo $lotteria_nome; ?>">
  <meta name="keywords" content="statistiche, lotteria, questionario, sondaggio">
  <style type="text/css">@import "<?php echo $filename_css ?>";</style>
</head>

<body>

<?php
} // end function print_header


function get_forum_post($forum_id,$topic_id,$post_id)
{

$file_topic_format = $root_path."custom/forums/topic_%d_%d.php";// formato nome del file di configurazione del topic j-esimo del forum i-esimo

# formato file di configurazione 'topic_I_J.php'
$indice_post_id = 0;
$indice_post_author = 1;
$indice_post_date = 2;
$indice_post_status = 3;


$file_topic = sprintf($file_topic_format,$forum_id,$topic_id);

// carica file di configurazione del forum
$topic = get_config_file($file_topic);

$result = $topic['elenco_posts'][$post_id];

$result['post_text'] = $topic['post_text_'.$post_id];

return $result;
} // end function get_forum_post


function error_message($message)
{
print_header($forums_caption);
echo $message;
print_footer();
}

?>