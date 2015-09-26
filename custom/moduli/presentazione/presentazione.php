<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 TRANSITIONAL//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<?php

require_once('../../../libreria.php');

# dichiara variabili
extract(indici());
?>
<head>
  <title>Stralaceno Web - Presentazione della Stralaceno</title>
  <meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1">
  <meta name="GENERATOR" content="Quanta Plus">
  <meta name="description" content="Presentazione della Stralaceno">
  <meta name="keywords" content="Stralaceno, Laceno, Caposele, Atletica, Sport">  
  <style type="text/css">@import "<?php echo $filename_css ?>";</style>  
</head>
<body>

<table style="background-color:rgb(255, 255, 255);">
  <tbody>
	<tr>

		<td width="100%">
		<img src="<?php echo $site_abs_path?>custom/album/varie/logo.jpg" alt="logo <?php echo $web_title; ?>">
		</td>
		
		<td>
		<object	type="application/x-shockwave-flash" data="<?php echo $site_abs_path ?>custom/images/logoflash300x70.swf" width="300" height="70">
			<param name="movie" value="<?php echo $site_abs_path ?>custom/images/logoflash300x70.swf" />
		</object>
		</td>
	</tr>
  </tbody>
</table>





<div style="text-align: center;"><a class="txt_link" 
 href="<?php echo $site_abs_path ?>custom/moduli/presentazione/present.swf">Visualizza presentazione FLASH (<?php echo ceil(filesize($root_path."custom/moduli/presentazione/present.swf")/1024); ?> kbytes)</a>
</div>

<hr>

<div align="justify" class="txt_normal">
<p>Dal 1987, nel suggestivo paesaggio dell'altopiano di Laceno, ogni anno gli sportivi di Caposele si sfidano 
tradizionalmente in una corsa di mezzofondo.</p>
<p>La manifestazione &egrave; di carattere amatoriale, ma non per questo &egrave; meno vivo il sano senso di competizione tra i 
partecipanti, i quali, sebbene animati da differenti motivazioni individuali, acquisiscono tutti pari dignit&agrave; nel 
momento in cui accettano, per Sport, di vivere un momento di intenso impegno fisico. Il che per alcuni si traduce nel 
correre per una migliore posizione in classifica, per altri in una semplice verifica del proprio stato di forma fisica, 
per altri ancora, non meno rispettabili, nel tentativo di raggiungimento del traguardo entro il tempo massimo 
prestabilito.</p>
<p>La corsa &egrave; riservata a chi ha la cittadinanza (<i>sportiva</i>) nel municipio di Caposele. Questo non deve essere inteso 
come un retrivo atteggiamento di chiusura nei confronti degli sportivi di altre localit&agrave;, i quali sono sempre i 
benvenuti a tutte le manifestazioni che si svolgono a Caposele. Tuttavia si &egrave; stabilito di apporre -per una volta- 
questo tipo di limitazione allo scopo di realizzare una competizione dal sapore autenticamente stra-paesano, nella 
quale i protagonisti, essendo persone appartenenti ad una comunit&agrave;, costituiscano un gruppo tendenzialmente omogeneo 
per condizionamento ambientale, sia culturale che derivante dalle opportunit&agrave; concrete di pratica sportiva. Questa 
omogeneit&agrave; ha incoraggiato la partecipazione di molti ed ha aiutato alcuni a scoprire l'esistenza del vero Sport. E 
infatti la "Stralaceno" vuole sostanzialmente essere una piccola occasione per promuovere un'attivit&agrave; sportiva 
autentica che si rivolga a beneficio anche dei comuni cittadini non specializzati o non dotati in ambito sportivo, che 
pure hanno il diritto di nutrire la legittima ambizione a vivere una propria dimensione agonistica, compatibile con i
 propri limiti.</p>
<p>Meglio comunque lasciare ad altri il compito di individuare gli eventuali benefici che manifestazioni del genere, 
se sono correttamente realizzate ed interpretate, possono portare alla collettivit&agrave; sul piano sociale e culturale. 
Sollecitiamo invece vivamente i visitatori del sito a non lesinare suggerimenti e critiche, dando cos&igrave; il loro 
contributo ad una manifestazione che forse oggi rappresenta anche un momento di aggregazione e di celebrazione di una 
identit&agrave; comunale non sempre facili da ritrovare in altri contesti.</p>
</div>

<?php echo $homepage_link ?>

<?php
# logga il contatto
$counter = count_page("modulo_presentazione",array("COUNT"=>1,"LOG"=>1),$filedir_counter); # abilita il contatore, senza visualizzare le cifre, e fai il log
?>

</body>
</html>
