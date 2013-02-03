<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 TRANSITIONAL//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>

<head>
%%%% begin siti_amici_cfg.txt titolo_pagina 1
  <title>%web_title%  - %field0%</title>
%%%% end
  <meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1">
  <meta name="GENERATOR" content="Quanta Plus">
  <meta name="description" content="elenco dei siti caposelesi, a cura dell'Associazione ARS Amatori Running Sele">
  <meta name="keywords" content="soci,Associazione,ARS,Amatori Running Sele, notizie, flash, bacheca">
  <style type="text/css">@import "%filename_css%";</style>
</head>
<body>


%%%% begin siti_amici_cfg.txt titolo_pagina 1
	<div class="titolo_tabella">%field0%</div><br>
%%%% end
<div align="center">

<table class="tabella">
  <tbody>
  <tr>
  <td>

<table>
 <thead><tr>
	<th valign="middle">Pos.</th>
	<th valign="middle">Logo</th>
	<th valign="middle">Sito</th>
	<th valign="middle">Curato da</th>
	<th valign="middle">Webmaster</th>
</tr></thead>
<tbody>

%%%% begin siti_amici_cfg.txt elenco_siti 7
    <tr style="background: rgb(255, 255, 255) none repeat scroll; vertical-align: middle; text-align: center; height:70px;">
	<td nowrap="nowrap"><div align="center">%field0%</div></td>
%%%% if %field4%!=''			<td nowrap="nowrap"><div align="center"><a href="%field3%"><img src="%field4%" width="150" height="60" border="0" alt="logo_%field2%"></a></div></td>
%%%% if %field4%==''			<td nowrap="nowrap"><div align="center">-</div></td>
	<td nowrap="nowrap"><div align="center">&nbsp;<a href="%field3%" style="text-decoration: underline;">%field2%</a>&nbsp;</div></td>
	<td nowrap="nowrap"><div align="center">%field5%</div></td>
	<td nowrap="nowrap"><div align="center"><div style="margin-left: 10pt;" align="left">%field6%</div></div></td>
    </tr>
%%%% end

</tbody>
</table>

  </td>
  </tr>

  </tbody>
</table>

%%%% begin siti_amici_cfg.txt testo_footer 1
	<small>%field0%</small>
%%%% end

</div>


%homepage_link%

