<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 TRANSITIONAL//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>

<head>
  <title>%web_title% - Associazione ARS Amatori Running Sele - flash news </title>
  <meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1">
  <meta name="GENERATOR" content="Quanta Plus">
  <meta name="description" content="elenco dei soci Associazione ARS Amatori Running Sele">
  <meta name="keywords" content="soci,Associazione,ARS,Amatori Running Sele, FC Caposele, 3 categoria, calcio">
  <style type="text/css">@import "%filename_css%";</style>
</head>
<body>


<table width="100%" align="center" style="background-color:rgb(255, 255, 255);">
  <tbody>
	<tr>
		<td align="center">
			<img src="%file_root%custom/album/varie/logo.jpg" alt="logo %web_title%">
		</td>
	</tr>
  </tbody>
</table>



<hr>


<div align="justify" class="txt_normal">


<h2 align="center">Campionato di III categoria</h2>
<br>

<div align="center">


<!-- 
	inizio tabella risultati giornata x 
-->
%%%% begin classifica_campionato_cfg.txt dati_giornata_visibile 1
	%field0%
%%%% end

<table class="tabella" style="font-size:13.0pt;">
<tbody><tr><td align="left"><table>

<tbody>


%%%% begin classifica_campionato_cfg.txt giornata_visibile 4
<!-- match # %field0% -->
<tr>
	<td> &nbsp;%field1%</td>
	<td> &nbsp;-&nbsp; </td>
	<td>%field2%</td>
	<td>%field3%</td>
</tr>
%%%% end


%%%% begin classifica_campionato_cfg.txt note_giornata_visibile 2
%%%% if %field0%=='1'	<tr><td colspan=3>&nbsp;</td></tr>
<!-- nota # %field0% -->
<tr>
	<td colspan=3>%field1%</td>
</tr>
%%%% end

</tbody>
</table></td></tr></tbody></table>
<!-- 
	fine tabella risultati giornata x 
-->


</div>


<br>
<div align="center">


<!-- 
	inizio tabella classifica
-->
%%%% begin classifica_campionato_cfg.txt dati_classifica_visibile 1
	%field0%
%%%% end

<table class="tabella" style="font-size:13.0pt;">
<tbody><tr><td><table>
<thead><tr>
	<th>&nbsp;</th>
	<th>&nbsp;</th>
	<th>punti</th>
	<th title="partite giocate">g</th>
	<th title="partite vinte">v</th>
	<th title="partite pareggiate">n</th>
	<th title="partite perse">p</th>
	<th title="reti fatte">rf</th>
	<th title="reti subite">rs</th>
	<th title="differenza reti">dr</th>
	<th title="media inglese">mi</th>
</tr></thead>
<tbody align="center">

%%%% begin classifica_campionato_cfg.txt classifica_visibile 11
<!-- # pos. %field0% in classifica -->
<tr>
	<td>%field0%</td>
	<td align="left">&nbsp;%field1%</td>
	<td>%field2%</td>
	<td>%field3%</td>
	<td>%field4%</td>
	<td>%field5%</td>
	<td>%field6%</td>
	<td>%field7%</td>
	<td>%field8%</td>
	<td>%field9%</td>
	<td>%field10%</td>
</tr>
%%%% end

</tbody>
</table></td></tr></tbody></table>
<!-- 
	fine tabella classifica
-->


</div>

</div>

%homepage_link%

</body>
</html>
