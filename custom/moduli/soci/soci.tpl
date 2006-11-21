<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 TRANSITIONAL//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>

<head>
  <title>%web_title% - Associazione ARS Amatori Running Sele - elenco dei soci</title>
  <meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1">
  <meta name="GENERATOR" content="Quanta Plus">
  <meta name="description" content="elenco dei soci Associazione ARS Amatori Running Sele">
  <meta name="keywords" content="soci,Associazione,ARS,Amatori Running Sele">
  <style type="text/css">@import "%filename_css%";</style>
</head>
<body>

<script type="text/javascript">
<!-- 

function assemble_fields(id_elenco,separator)
{
elenco = Array;
elenco[1] = Array(
%%%% begin soci_cfg.txt elenco_soci 9
%%%% if %field1%!='' '%field1%',
%%%% end
'');

elenco[2] = Array(
%%%% begin soci_cfg.txt elenco_soci 9
%%%% if %field4%!='' '%field1%',
%%%% end
'');

ks='';
for (i = 0; i < elenco[id_elenco].length-1; i++) 
{
	if (i>0)
	{
		ks += separator;
	}
	ks += elenco[id_elenco][i];
}

return ks;
}

//-->
</script>




<table width="100%" align="center" style="background-color:rgb(255, 255, 255);">
  <tbody>
	<tr>
		<td align="center">
			<img src="%file_root%custom/images/logo.gif" alt="logo_ArsWeb">
		</td>
	</tr>
  </tbody>
</table>



<hr>


<div align="justify" class="txt_normal">



<p align=center>
<b>
ARS Amatori Running Sele - Elenco dei soci
</b>


<br>

<!--nominativo::email::data_iscrizione::tipo_socio::carica::foto::note-->
<div align="center">
<table class="tabella" style="font-size:1.7em;"><tbody><tr><td>

<table>
	<!--caption>I soci ARS:</caption-->
	<thead><tr>
		<th>Nominativo</th>
		<th>E-mail</th>
		<th>Data di associazione</th>
		<th>Tipo socio</th>
		<th>Carica</th>
		<th>Note</th>
	</tr></thead>
	<tbody>
%%%% begin soci_cfg.txt elenco_soci 9
	<tr>
		<td nowrap style='padding-right:0.5em;'>
%%%% if %field5%!=''			<a href="../../../show_photo.php?id_photo=%field5%&amp;album=%field6%" style="text-decoration:underline">%field0%</a>
%%%% if %field5%==''			%field0%
		</td>
		<td style='padding-right:0.5em;'>
			%field1%
		</td>
		<td align="center" style='padding-right:0.5em;'>
			%field2%
		</td>
		<td style='padding-right:1.0em;'>
			%field3%
		</td>
		<td style='padding-right:0.5em;'>
			%field4%
		</td>
		<td style='padding-right:0.5em;'>
			%field7%
		</td> 

	</tr>
%%%% end
	</tbody>
</table>

</td></tr></tbody></table>

</div>
<br>
%%%% begin pregfas_cfg.txt data_aggiornamento 1
	<i>Registro aggiornato al %field0%</i>
%%%% end

<hr>

<br>
<b>Brevi news:</b>


<table><tbody>
%%%% begin soci_cfg.txt elenco_news 2
    <tr>
	<td valign="top" nowrap>- <i>%field0%</i>: </td>
	<td>%field1%<br></td>
    </tr>
	
%%%% end
</tbody></table>


<hr>

<br>
<b>
<script type="text/javascript">
<!-- 

document.write('<a  href="mailto:');
document.write(assemble_fields(1,";"));
document.write('?subject=Comunicato ai soci ARS" title="Comunicato ai soci ARS">Scrivi una mail a tutti i soci<');
document.write('/a>');

//-->
</script>
</b>


<br>
<b>
<script type="text/javascript">
<!-- 

document.write('<a  href="mailto:');
document.write(assemble_fields(2,";"));
document.write('?subject=Comunicato ai componenti del consiglio direttivo ARS" title="Comunicato ai componenti del consiglio direttivo ARS">Scrivi una mail ai componenti del consiglio direttivo ARS<');
document.write('/a>');

//-->
</script>
</b>

</div>



%homepage_link%


</body>
</html>
