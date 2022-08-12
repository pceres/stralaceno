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

/*
Libreria per la generazione ed il check di captcha
*/
define("CAPTCHA_INVERSE", 0);    // black background
define("CAPTCHA_NEW_URLS", 0);   // no auto-disabling/hiding for the demo
require_once('captcha/captcha.php');



$action	= $_REQUEST['action'];	// azione da eseguire
$action	= sanitize_user_input($action,'plain_text',array());	// verifica di sicurezza

$data 	= $_REQUEST['data'];		// dati associati all'azione
$data 	= sanitize_user_input($data,'plain_text',array());	// verifica di sicurezza

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

$default_post_testo = ''; //'Qui il tuo messaggio';

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
			$forum_last_post = explode(',',$forum_item[$indice_forums_last_post]);
			
			if (count($forum_last_post)==2)
			{
				$topic_id = $forum_last_post[0];
				$post_id = $forum_last_post[1];
				$last_post = get_forum_post($forum_id,$topic_id,$post_id);
				
				$forum_last_post_author = $last_post[$indice_post_author];
				$forum_last_post_date = $last_post[$indice_post_date];
			}
			else
			{
				$forum_last_post_author = '';
				$forum_last_post_date = '';
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
			echo "<a href=\"forum.php?action=list_posts&amp;data=$forum_id,$topic_id\">$forum_last_post_date<br><br>di $forum_last_post_author</a>";
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
		$forum_last_post = explode(',',$forum_item[$indice_forums_last_post]);
		
		// verifica autorizzazioni in lettura
		$username = $login['username'];
		$usergroups = $login['usergroups'];
		if (!group_match($usergroups,explode(',',$forum_read_groups)))
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
		<?php echo $web_title; ?></a> &#187;
		<?php echo $forum_caption; ?>
	</td>
</tr>

<?php
		echo '<tr class="TSfondoMedio">';
		echo '<td>Discussione</td><td><center>Aperta da</center></td><td><center>Messaggi</center></td><td><center>Ultimo Messaggio</center></td>';
		echo '</tr>'."\n";
		
		
		// sort topics by last published post
		$v_data = array();
		foreach ($topics['elenco_topics'] as $topic_item)
		{
			$topic_id = $topic_item[$indice_topic_id];
			$topic_last_post = $topic_item[$indice_topic_last_post];
			
			if (strlen($topic_last_post.' ') > 1)
			{
				$last_post = get_forum_post($forum_id,$topic_id,$topic_last_post);
				
				$topic_last_post_date = $last_post[$indice_post_date];
			}
			else
			{
				unset($topic_last_post_date);
			}
			
			// passa dal formato '21/09/2006 22.03.22' ad un singolo numero
			
			$giorno = substr($topic_last_post_date,0,2);
			$mese = substr($topic_last_post_date,3,2);
			$anno = substr($topic_last_post_date,6,4);
			$ore = substr($topic_last_post_date,11,2);
			$minuti = substr($topic_last_post_date,14,2);
			$secondi = substr($topic_last_post_date,17,2);
			
			$datenum = mktime($ore,$minuti,$secondi,$mese,$giorno,$anno);
			
			array_push($v_data,$datenum);
		}
		
		$topics_ordinati = $topics['elenco_topics'];
		array_multisort($v_data,SORT_DESC,$topics_ordinati);
		
		$showed = 0;
		foreach ($topics_ordinati as $topic_item)
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
		$coord_topic = explode(',',$data);	// I,Y -> forum I, topic Y -> topic_I_Y.php
		
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
		$forum_last_post = explode(',',$forum_item[$indice_forums_last_post]);
		
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
		$username = $login['username'];
		$usergroups = $login['usergroups'];
		if (!group_match($usergroups,explode(',',$topic_read_groups)))
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
<tbody>
<tr><td align="right">
<a href="forum.php?action=new_post&amp;data=<?php echo $forum_id; ?>,<?php echo $topic_id; ?>">
	<img src="<?php echo $site_abs_path ?>images/forum_rispondi.gif" border="0" alt="nuovo messaggio"></a>

<!-- commento il tasto per aprire un nuovo topic: la sua posizione qui potrebbe ingannare -->
<!--&nbsp;
<a href="forum.php?action=new_topic&amp;data=<?php echo $forum_id; ?>">
	<img src="<?php echo $site_abs_path ?>images/forum_nuova_discussione.gif" border="0" alt="nuova discussione"></a>
	-->
</td></tr>
</tbody></table>


<table class="tabella" border="0" cellpadding="5" cellspacing="1" width="100%">

<tbody>


<tr>
	<td colspan="2" class="titoloBlu" valign="middle">
	
	<a href="forum.php?action=list_forums" class="sfondoscuro">
		<?php echo $web_title; ?></a> &#187;
	<a href="forum.php?action=list_topics&amp;data=<?php echo $forum_id; ?>" class="sfondoscuro">
		<?php echo $forum_caption; ?></a> &#187; 
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
		<a name="post_id_<?php echo $post_id; ?>"></a>
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
		
		
		print_footer();
		
		$action = '';
		break;
		
		
		
		
	case "new_post":
/*	
	Input:	
		$action_data["new_topic_flag"] = true; --> si tratta di un nuovo topic, $post_type = 'new_topic_post', chiedi anche il titolo
		$action_data["reply_post"] = true; --> si tratta di un reply ad un altrp post, $post_type = 'reply_post'
		altrimenti $post_type = 'new_post'
		
		$reply_data = array(0=>forum_id,1=>topic_id,2=>post_id)
*/
		
		if ($action_data["new_topic_flag"])
		{
			$post_type = 'new_topic_post';
			$new_post_msg = 'nuova discussione';
		}
		elseif ($action_data["reply_post_flag"])
		{
			$post_type = 'reply_post';
			$new_post_msg = 'rispondi al messaggio';
		}
		else
		{
			$post_type = 'new_post';
			$new_post_msg = 'nuovo messaggio';
		}
		
		
		$coord_topic = explode(',',$data);	// I,Y -> forum I, topic Y -> topic_I_Y.php
		
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
		$forum_last_post = explode(',',$forum_item[$indice_forums_last_post]);
		
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
		$username = $login['username'];
		$usergroups = $login['usergroups'];
		if (!group_match($usergroups,explode(',',$topic_write_groups)))
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
	<input name="post_type" value="<?php echo $post_type; ?>" type="hidden">
	
	<table class="tabella_forum" align="center" cellpadding="5" cellspacing="1" width="728">
	<tbody>
		<tr>
			<td colspan="2" class="titoloBlu" valign="middle">
				<a href="forum.php?action=list_forums" class="sfondoscuro">
					<?php echo $web_title; ?></a> &#187;
				<a href="forum.php?action=list_topics&amp;data=<?php echo $forum_id; ?>" class="sfondoscuro">
					<?php echo $forum_caption; ?></a> &#187;
					<?php if (!empty($topic_caption)) {echo "$topic_caption &#187;"; } ?>
				<?php echo $new_post_msg; ?>
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
		
		<tr class="TSfondoChiaro">
			<td class="Small" align="left" valign="middle">
				<b title="Serve per evitare lo spam!">Captcha</b>
			</td>
			<td valign="top">
				<?php
				// output CAPTCHA img + input box
				echo captcha::form("&rarr;&nbsp;");
?>
			</td>
		</tr>
		
<?php
if ($post_type === "new_topic_post")
{
?>
		<tr class="TSfondoChiaro">
			<td class="Small" align="left" valign="middle">
				<b>Titolo</b>
			</td>
			<td valign="top">
				<input name="post_titolo" size="40" value="" type="text">
			</td>
		</tr>
<?php
} // end if ($post_type === "new_topic_post")
?>



<?php
if ($post_type === "reply_post")
{
	$post_id = $reply_data[2];
// 	$post = get_forum_post($reply_data[0],$reply_data[1],$reply_data[2]);
// 	
// 	$post_id = $post[$indice_post_id];
// 	$post_author = $post[$indice_post_author];
// 	$post_date = $post[$indice_post_date];
// 	$post_status = $post[$indice_post_status];

	$post_text = $posts['post_text_'.$post_id];

	$bulk_post_text = '';
	$count_post_text = 0;
	foreach ($post_text as $line)
	{
		$linea = $line[0];
		
		$count_post_text += strlen($linea);
		$bulk_post_text .= $linea."\n";
	}
	if ($count_post_text > 800)
	{
		$count_post_text = "<font color=\"red\">$count_post_text</font>";
	}

?>
		<tr class="TSfondoChiaro">
			<td class="small" align="left" valign="top"><b>Citazione<br></b>
				<br>
				<center>(max 800 caratteri)
					<br><br>Attuali 
					<div id="cit"><?php echo $count_post_text; ?></div>
					caratteri.
				</center>
			</td>
			<td valign="top">
				<textarea cols="90" rows="10" name="post_citazione" onkeyup="contacar(this);" onchange="contacar(this);"><?php 
					foreach ($post_text as $line)
					{
						echo $line[0]."\n";
					}
				?></textarea>
			</td>
<?php
} // end if ($post_type === "reply_post")
?>


		
		<tr class="TSfondoChiaro">
			<td class="Small" align="left" valign="top">
				<b>Messaggio</b>
			</td>
			<td valign="top">
				<textarea cols="90" rows="13" name="post_testo"><?php echo $default_post_testo; ?></textarea>
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
		
		$post_type = $_POST['post_type'];	// 'new_post','reply_post','new_topic_post'
		
		$post_forum_id = $_POST['post_forum_id'];
		$post_topic_id = $_POST['post_topic_id'];
		$post_nick = sanitize_user_input($_POST['post_nick'],'plain_text',array());
		$post_email = $_POST['post_email'];
		$post_web = $_POST['post_web'];
		$post_titolo = sanitize_user_input($_POST['post_titolo'],'plain_text',array());
		$post_citazione = sanitize_user_input($_POST['post_citazione'],'plain_text',array());
		$post_testo = sanitize_user_input($_POST['post_testo'],'simple_formatted_html',array());
		$post_azione1 = $_POST['post_azione1'];
		$post_azione2 = $_POST['post_azione2'];
		
		// integra la citazione nel messaggio del testo:
		if ($post_type === 'reply_post')
		{
			$list_post_citazione = preg_split("~\n~",$post_citazione);
			$ks_citazione = '';
			foreach ($list_post_citazione as $linea_citazione)
			{
				$ks_citazione .= "&gt;$linea_citazione<br>";
			}
			$post_testo = "$ks_citazione<br>$post_testo";
		}
		
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
		$forum_last_post = explode(',',$forum_item[$indice_forums_last_post]);
		
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
		
		// individua gruppi abilitati in scrittura
		switch ($post_type)
		{
		case 'new_topic_post':
			$write_groups = $forum_write_groups;
			$write_error_msg = "aprire nuove discussioni in questo forum";
			break;
 		case 'reply_post':
		case 'new_post':
			$write_groups = $topic_write_groups;
			$write_error_msg = "scrivere messaggi in questa discussione";
			break;
		}
		
		// verifica autorizzazioni in scrittura
		$username = $login['username'];
		$usergroups = $login['usergroups'];
		if (!group_match($usergroups,explode(',',$write_groups)))
		{
			$write_post_error = "Mi dispiace, non sei abilitato a $write_error_msg.";
		}
		
		// validazione dei campi
		if ( (strlen($post_forum_id.' ')==1) | ((strlen($post_topic_id.' ')==1) & ($post_type !== 'new_topic_post')) )
		{
			// per un nuovo post o una reply, ci vuole sia forum che topic id (solo forum id per new_topic_post)
			$write_post_error = "Errore. ($post_forum_id,$post_topic_id)";
		}
		if (strlen($post_nick.' ')==1)
		{
			// il campo nick non puo' essere vuoto
			$write_post_error = "Il campo Nome/Nick non pu&ograve; essere lasciato vuoto!";
		}
		if ( (strlen($post_titolo.' ')==1) & ($post_type == 'new_topic_post') )
		{
			// per un new_topic_post il titolo non puo' essere vuoto
			$write_post_error = "Il campo Titolo non pu&ograve; essere lasciato vuoto!";
		}
		if ($post_testo===$default_post_testo)
		{
			// il campo testo non puo' essere vuoto
			$write_post_error = "Non hai scritto il messaggio!";
		}
		$titolo_ultimo_topic = $topics['elenco_topics'][count($topics['elenco_topics'])-1][$indice_topic_caption];
		if ( ($post_type == 'new_topic_post') & ($titolo_ultimo_topic === $post_titolo) )
		{
			// una discussione con quel titolo esiste gia'
			$write_post_error = "Gi&agrave esiste una discussione con quel titolo!";
		}
		
		if (!captcha::solved())
		{
			// Non e' stato risulto il captcha! Possibile spambot
			$write_post_error = "<p>Mi dispiace, per pubblicare il post devi indicare correttamente il <a href=\"http://it.wikipedia.org/wiki/Captcha\">captcha</a>!<br><br>".
			"	Questo accorgimento si &egrave; reso necessario per controbattere ai recenti attacchi di spam, ce ne scusiamo con gli utenti.<br><br>\n".
			"Se vuoi riprovare ad inserire il codice di nuovo, torna indietro con il browser e riprova.<br>\n".
			"Per rendere il codice piu' leggibile, clicca piu' volte sull'immagine.<br>\n".
			"Se vuoi provare con un nuovo codice, torna indietro e ricarica la pagina<br></p>\n";
		}
		
		// se c'e' un errore, visualizza il messaggio ed esci
		if (!empty($write_post_error))
		{
			// il campo testo non puo' essere vuoto
			error_message($write_post_error);
			
			$action = '';
			continue;
		}
		
		switch ($post_type)
		{
		case 'new_topic_post':
			// il topic non esiste, crealo
			$topic_id = count($topics['elenco_topics']);
			$file_topic = sprintf($file_topic_format,$forum_id,$topic_id);
			
			// crea riga relativa al topic
			$topics['elenco_topics'][$topic_id] = array(
				$indice_topic_id => $topic_id,
				$indice_topic_caption => $post_titolo,
				$indice_topic_status => 'open',
				$indice_topic_read_groups => $forum_read_groups,
				$indice_topic_write_groups => $forum_write_groups,
				$indice_topic_author => $post_nick,
				$indice_topic_posts => 0,
				$indice_topic_last_post => ''
				);
			
			$new_post_id = 0; // il post da scrivere e' il primo
			$post_id = $new_post_id;
			
			// item di help 
			$posts = array();
			$posts['help_config'] = array(
			array('Formato [elenco_posts]:'),
			array("	<post_id>","<post_author>","<post_date>","<post_status>"),
			array(" 	<post_id>	: numero incrementale che identifica il post all'interno del topic"),
			array("	<post_caption>	: titolo del post"),
			array("	<post_status>	: stato del post:"),
			array("			  visible	: il post e' visibile"),
			array("			  censored	: il topic e' censurato"),
			array("			  hidden	: il topic e' invisibile"),
			array("Formato [post_text_X]:"),
			array("E' il testo del post, puo' essere su piu' righe, nessuna delle quali vuote")
			);
			
			break;
 		case 'reply_post':
		case 'new_post':
			// il topic gia' esiste, basta contare quanti post contiene gia'
			$new_post_id = count($posts['elenco_posts']);
			break;
		}
		
		
		// aggiorna le strutture in forums.php
		$forum_topics += 1;
		$forum_last_post = "$topic_id,$new_post_id";
		
		$forums['elenco_forums'][$forum_id][$indice_forums_topics] = $forum_topics;
		$forums['elenco_forums'][$forum_id][$indice_forums_last_post] = $forum_last_post;
		
		
		// aggiorna le strutture in forum_X.php
		$topic_posts += 1;
		$topic_last_post = $new_post_id;
		
		$topics['elenco_topics'][$topic_id][$indice_topic_posts] = $topic_posts;
		$topics['elenco_topics'][$topic_id][$indice_topic_last_post] = $topic_last_post;
		
		
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
		foreach (preg_split("~\n~",$post_testo) as $id_riga => $riga)
		{
			$ks = rtrim($riga);
			if ($ks == "\r")
			{
				die("got it!");
			}
			if (empty($ks))
			{
				$ks = "&nbsp;";
			}
			$posts['post_text_'.$post_id][$id_riga][0] = $ks;
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
		

$file_templog = $root_path."custom/forums/templog.txt";
		$templog = get_config_file($file_templog);
$temp = $templog["logdata"];
if (empty($temp))
{
	$temp = array();
	$temp[0] = array("email","web");
}

array_push($temp,array(0=>$post_email,1=>$post_web));
$templog["logdata"] = $temp;
save_config_file($file_templog,$templog);

// timido tentativo di arginare uno spammer!
$lista_mail_spammer = Array("@i\.ua","@uniid\.info","@fmaks\.info","@idlyn\.info");
foreach($lista_mail_spammer as $mail_spammer)
{
	if (preg_match("~$mail_spammer~",$post_email))
	{
		$stesso_messaggio = true;
		// hgjhddfh@i.ua
	}
}

			
		// se il messaggio e' nuovo, salvalo
		if (!$stesso_messaggio)
		{
			save_config_file($file_forums,$forums);
			save_config_file($file_forum,$topics);
			save_config_file($file_topic,$posts);
			
			// prepara i dati per il log dei nuovi contenuti
			$post_testo_feed = str_replace(array("\r\n", "\n"  , "\r"),array("<br>", "<br>", ""  ),$post_testo);
			$item['title'] 		= 'Nuovo messaggio sul forum: '.$topics['elenco_topics'][$topic_id][$indice_topic_caption]." (msg. $post_id)";
			$item['description'] 	= $post_testo_feed;
			$item['link'] 		= "forum.php?action=list_posts&amp;data=$forum_id,$topic_id&amp;post_id=$post_id";
			$item['guid'] 		= "$forum_id,$topic_id,$post_id";
			$item['category'] 	= "forum";
			
			$date_unix = substr($post_date,3,2)."/".substr($post_date,0,2)."/".substr($post_date,6,4)." ".substr($post_date,11,2).":".substr($post_date,14,2).":".substr($post_date,17,2);
			$item['pubDate'] 	= gmdate('D, j M Y G:i:s +0000',strtotime($date_unix));
			$item['author'] 	= $post_author;
			$item['username']	= $username;
			$item['read_allowed']	= $topics['elenco_topics'][$topic_id][$indice_topic_read_groups];	// everyone allowed to see the feed
			
			log_new_content('forum',$item);
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
		
		
		
		
	case "new_topic":
		
		$action_data["new_topic_flag"] = true;
		
		$action = 'new_post';
		continue;
		
		
		
		
	case "reply_post":
		
		$reply_data = explode(',',$data);
		$action_data["reply_post_flag"] = true;
		
		$action = 'new_post';
		continue;
		
		
		
		
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

<script type="text/javascript">
<!--
function contacar(ptext)
{
cit = document.getElementById('cit'); // handle al div con id 'cit'

quanti=ptext.value.length;
if (quanti>800)
	cit.innerHTML='<font color=red>' + quanti+ '</'+'font>';
else
	cit.innerHTML=quanti;
}
//-->
</script>

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
