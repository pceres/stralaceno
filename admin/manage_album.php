#!/usr/local/bin/php
<?php

require_once('../libreria.php');

# dichiara variabili
extract(indici());

// verifica che si stia arrivando a questa pagina da .../album.php
$referer = $_SERVER['HTTP_REFERER'];
if ( !isset($_SERVER['HTTP_REFERER']) | (strpos($referer,"http://".$_SERVER['HTTP_HOST'].$script_abs_path."album.php")!='0') )
{
	header("Location: ".$script_abs_path."admin/index.php");
	exit();
}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 TRANSITIONAL//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>

<head>
  <title>Gestione album</title>
  <meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1">
  <meta name="GENERATOR" content="Programmers Notepad">
  <style type="text/css">@import "<?php echo $filename_css ?>";</style>
</head>
<body>

<?php

$mode = $_REQUEST['task'];
$data = $_REQUEST['data'];
$password = $_REQUEST['password'];

log_action($album_dir,"Author:<".$_SERVER['REMOTE_ADDR'].">, Action: <$mode>, data: <$data>, ".date("l dS of F Y h:i:s A"));


$password_ok = $password_album;

if ($password != $password_ok)
{
	echo "<a href=\"$referer\">Torna indietro</a><br><br>\n";
	die("La password inserita non &egrave; corretta!<br>\n");
}

switch ($mode)
{
case 'cancel':
	echo("Cancellazione della foto ".$data.":<br><br>\n");

	// verifica che il file esiste
	if (!is_file($data))
	{
		echo "Il file $data non esiste!<br><br>\n";
		die("<a href=\"$referer\">Torna indietro</a><br>");
	}
	
	// verifica che la foto non sia visibile
	$elenco_foto = get_config_file($filename_albums,3); // carica il file di configurazione degli album
	preg_match_all('/[^\/]+$/',dirname($data),$album);
	$album = $album[0][0];
	$foto = basename($data);
	if (array_key_exists($album,$elenco_foto))
	{
		$album_data = $elenco_foto[$album];
		foreach ($album_data as $foto_data)
		{
			if ($foto_data[0]==$foto) // la foto e' visibile, esci
			{
				echo "La foto $foto e' ancora visibile nell'album \"$album\"!<br>\n";
				echo "Prima di cancellarla renderla invisibile modificando il file di configurazione degli album!<br><br>\n";
				die("<a href=\"$referer\">Torna indietro</a><br>");
			}
		}
	}
	
	// a questo punto sono sicuro che il file esiste
	if (unlink($data)) // cancello fisicamente il file
	{
		echo "La foto $data e' stata cancellata!<br>\n";
	}
	else
	{
		echo "La foto $data e' gia' stata cancellata!<br>\n";
	}

	// elimina l'eventuale thumbnail
	$thumb = substr($data,0,-4)."-thumb.".substr($data,-3);
	if (unlink($thumb)) // cancello fisicamente il file
	{
		echo "Il thumbnail $thumb e' stato cancellato!<br>\n";
	}

	break;
	
case 'cancel_album':

	$album = basename($data); // poiche' si accede direttamente al filesystem, elimina tutte le possibilita' di abusare  utilizzando nel path il '..'

	echo("Cancellazione dell'album \"".$album."\":<br><br>\n");

	if (!is_dir($album_dir.$album))
	{
		echo "La directory $album_dir"."$album non esiste!<br>\r";
		echo "Per cancellare ogni traccia dell'album, eliminare, dal file di configurazione degli album fotografici,";
		echo "la riga contenente \"[$data]\"<br><br>\n";
		die("<a href=\"$referer\">Torna indietro</a><br>");
	}
	
	// carica il file di configurazione degli album
	$elenco_foto = get_config_file($filename_albums,3);
	
	if (count($elenco_foto[$album]) > 0)
	{
		echo "L'album \"$album\" non e' vuoto, procedere prima alla cancellazione di tutte le foto in esso presenti!<br>\r";
		die("<a href=\"$referer\">Torna indietro</a><br>");
	}
	
	// a questo punto sono sicuro che il file esiste
	if (rmdir($album_dir.$album)) // cancello fisicamente la directory
	{
		echo "L'album \"$album\" e' stato cancellato!<br>\n";
		echo "Per cancellare ogni traccia dell'album, eliminare, dal file di configurazione degli album fotografici,";
		echo "la riga contenente \"[$album]\" e tutti i dati ad esso relativi<br><br>\n";
	}
	else
	{
		echo "Errore nella rimozione della directory \"$album\".";
		echo "Verificare che siano state cancellate tutte le foto in essa contenute<br>\n";
		die("<a href=\"$referer\">Torna indietro</a><br>");
	}

	break;

case 'create_album':

	$album = basename($data); // poiche' si accede direttamente al filesystem, elimina tutte le possibilita' di abusare  utilizzando nel path il '..'

	echo("Creazione dell'album \"".$album."\":<br><br>\n");

	// se la directory gia' esiste dai errore ed esci
	if (is_dir($album_dir.$album))
	{
		echo "La directory $album_dir"."$album gia' esiste!<br>\r";
		die("<a href=\"$referer\">Torna indietro</a><br>");
	}
	
	// a questo punto sono sicuro che la directory puo' essere creata: procedi
	if (mkdir($album_dir.$album)) // creo fisicamente la directory
	{
		echo "L'album $album e' stato creato!<br>\n";
		echo "Procedere ad aggiungere foto all'album dalla pagina amministrativa<br>\n";
	}
	else
	{
		echo "Errore nella creazione della directory \"$album\".";
		echo "Contattare l'amministratore<br>\n";
		die("<a href=\"$referer\">Torna indietro</a><br>");
	}

	// aggiungi la riga [nome_album] nel file di configurazione
	$tempfilename = $album_dir.'tempfile.txt';
	if ($handle=fopen($tempfilename,'w'))
	{
		$bulk = file($filename_albums);
		array_push($bulk,"\r\n","[$album]\r\n"); // aggiungi la riga col nome dell'album
		foreach ($bulk as $line)
		{
			fwrite($handle,$line);
		}
		fclose($handle);
		rename($tempfilename,$filename_albums);
	}
	else
	{
		echo "Non e' stato possibile modificare automaticamente il file di configurazione.";
		echo "Procedere manualmente, aggiungendo una riga con \"[$album]\".";
	}

	break;
	
default:
	echo "<a href=\"<?php echo $referer; ?>\">Torna indietro</a><br><br>\n";
	die("mode: \"".$mode."\", data: \"".$data."\"\n");
}

?>

<hr>
<a href="<?php echo $referer; ?>">Torna indietro</a>

</body>
</html>
 
