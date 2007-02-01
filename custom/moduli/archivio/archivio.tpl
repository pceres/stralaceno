<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 TRANSITIONAL//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>

<head>
  <title>%web_title% - Associazione ARS Amatori Running Sele - flash news </title>
  <meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1">
  <meta name="GENERATOR" content="Quanta Plus">
  <meta name="description" content="archivio degli articoli ed album del sito ARS Amatori Running Sele">
  <meta name="keywords" content="archivio,album,articoli,Associazione,ARS,Amatori Running Sele">
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

<br>
<b>Album fotografici disponibili:</b>

<table><tbody>
%%%% begin archivio_cfg.txt elenco_album 3
    <tr>
	<td valign="top" nowrap>- <a href="../../../album.php?anno=%field0%">%field1%</a></td>
	<td>: </td>
	<td>%field2%<br></td>
    </tr>
	
%%%% end
</tbody></table>


<br>
<b>Archivio articoli:</b>

<table><tbody>
%%%% begin archivio_cfg.txt elenco_articoli 4
    <tr>
	<td>%field1%<br></td>

	<td valign="top" nowrap>- <a href="../../../index.php?page=%field1%&amp;art_id=%field0%">%field2%</a></td>
%%%% if %field3%!=''	<td>: </td>	<td>%field3%<br></td>
    </tr>
	
%%%% end
</tbody></table>


<br>
<b>Archivio altre pagine:</b>

<table><tbody>
%%%% begin archivio_cfg.txt elenco_pagine 2
    <tr>
	<td valign="top" nowrap>- <a href="%field0%">%field1%</a></td>
    </tr>
	
%%%% end
</tbody></table>


</div>



%homepage_link%


</body>
</html>
