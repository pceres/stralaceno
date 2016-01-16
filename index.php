<?php

require_once('libreria.php');

/*
questa libreria esamina i cookies o i parametri http (eventualmente) inviati, e genera l'array $login con i campi
'username',	: login
'usergroups',	: lista dei gruppi di appartenenza (separati da virgola)
'status',		: stato del login: 'none','ok_form','ok_cookie','error_wrong_username','error_wrong_userpass','error_wrong_challenge','error_wrong_IP'
*/
require_once('login.php');


#
# analisi dei parametri passati alla pagina
#

# pagina da visualizzare; per ora puo' valere:
# 	'' 		: pagina di default, con tutti gli articoli in colonna centrale
#	'<sezione>'	: viene visualizzato un solo articolo, indicato dal suo id attraverso la variabile aggiuntiva 'art_id'
$sezione = $_REQUEST['page']; // contenuto da visualizzare in colonna centrale
$sezione = sanitize_user_input($sezione,'plain_text',Array());

$art_id = $_REQUEST['art_id']; // id dell'articolo da visualizzare
$art_id = sanitize_user_input($art_id,'number',Array("number_type"=>"int"));


# dichiara variabili
extract(indici($sezione));


# carica i dati relativi a tutte le edizioni, che devono essere disponibili per i moduli nelle colonne sinistra e destra
if (file_exists($filename_tempi))
{
	$archivio = load_data($filename_tempi,$num_colonne_prestazioni);
}

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 TRANSITIONAL//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
  <title><?php echo $web_title ?></title>
  <meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1">
  <meta name="generator" content="Quanta Plus">
  <meta name="description" content="<?php echo $web_description ?>">
  <meta name="keywords" content="<?php echo $web_keywords ?>">
  <style type="text/css">@import "<?php echo $filename_css ?>";</style>
  <!--link href="<?php echo $site_abs_path ?>custom/images/logo_small.gif" rel="SHORTCUT ICON"-->
  
  <!-- Begin Cookie Consent plugin by Silktide - http://silktide.com/cookieconsent -->
  <script type="text/javascript">
      window.cookieconsent_options = {"message":"This website uses cookies to ensure you get the best experience on our website","dismiss":"Got it!","learnMore":"More info","link":"custom/templates/cookie_policy.html","theme":"dark-top"};
  </script>

  <script type="text/javascript">

  !function(){if(!window.hasCookieConsent){window.hasCookieConsent=!0;var e="cookieconsent_options",t="update_cookieconsent_options",n="cookieconsent_dismissed",i="//cdnjs.cloudflare.com/ajax/libs/cookieconsent2/1.0.9/";if(!(document.cookie.indexOf(n)>-1)){"function"!=typeof String.prototype.trim&&(String.prototype.trim=function(){return this.replace(/^\s+|\s+$/g,"")});var o,s={isArray:function(e){var t=Object.prototype.toString.call(e);return"[object Array]"==t},isObject:function(e){return"[object Object]"==Object.prototype.toString.call(e)},each:function(e,t,n,i){if(s.isObject(e)&&!i)for(var o in e)e.hasOwnProperty(o)&&t.call(n,e[o],o,e);else for(var r=0,c=e.length;c>r;r++)t.call(n,e[r],r,e)},merge:function(e,t){e&&s.each(t,function(t,n){s.isObject(t)&&s.isObject(e[n])?s.merge(e[n],t):e[n]=t})},bind:function(e,t){return function(){return e.apply(t,arguments)}},queryObject:function(e,t){var n,i=0,o=e;for(t=t.split(".");(n=t[i++])&&o.hasOwnProperty(n)&&(o=o[n]);)if(i===t.length)return o;return null},setCookie:function(e,t,n,i,
o){n=n||365;var s=new Date;s.setDate(s.getDate()+n);var r=[e+"="+t,"expires="+s.toUTCString(),"path="+o||"/"];i&&r.push("domain="+i),document.cookie=r.join(";")},addEventListener:function(e,t,n){e.addEventListener?e.addEventListener(t,n):e.attachEvent("on"+t,n)}},r=function(){var e="data-cc-event",t="data-cc-if",n=function(e,t,i){return s.isArray(t)?s.each(t,function(t){n(e,t,i)}):void(e.addEventListener?e.addEventListener(t,i):e.attachEvent("on"+t,i))},i=function(e,t){return e.replace(/\{\{(.*?)\}\}/g,function(e,n){for(var i,o=n.split("||");token=o.shift();){if(token=token.trim(),'"'===token[0])return token.slice(1,token.length-1);if(i=s.queryObject(t,token))return i}return""})},o=function(e){var t=document.createElement("div");return t.innerHTML=e,t.children[0]},r=function(e,t,n){var i=e.parentNode.querySelectorAll("["+t+"]");s.each(i,function(e){var i=e.getAttribute(t);n(e,i)},window,!0)},c=function(t,i){r(t,e,function(e,t){var o=t.split(":"),r=s.queryObject(i,o[1]);n(e,o[0],s.bind(r,i))})},a=function(e,n)
{r(e,t,function(e,t){var i=s.queryObject(n,t);i||e.parentNode.removeChild(e)})};return{build:function(e,t){s.isArray(e)&&(e=e.join("")),e=i(e,t);var n=o(e);return c(n,t),a(n,t),n}}}(),c={options:{message:"This website uses cookies to ensure you get the best experience on our website. ",dismiss:"Got it!",learnMore:"More info",link:null,container:null,theme:"light-floating",domain:null,path:"/",expiryDays:365,markup:['<div class="cc_banner-wrapper {{containerClasses}}">','<div class="cc_banner cc_container cc_container--open">','<a href="#null" data-cc-event="click:dismiss" class="cc_btn cc_btn_accept_all">{{options.dismiss}}</a>','<p class="cc_message">{{options.message}} <a data-cc-if="options.link" class="cc_more_info" href="{{options.link || "#null"}}">{{options.learnMore}}</a></p>','<a class="cc_logo" target="_blank" href="http://silktide.com/cookieconsent">Cookie Consent plugin for the EU cookie law</a>',"</div>","</div>"]},init:function(){var t=window[e];t&&this.setOptions(t),this.setContainer(),this.
options.theme?this.loadTheme(this.render):this.render()},setOptionsOnTheFly:function(e){this.setOptions(e),this.render()},setOptions:function(e){s.merge(this.options,e)},setContainer:function(){this.container=this.options.container?document.querySelector(this.options.container):document.body,this.containerClasses="",navigator.appVersion.indexOf("MSIE 8")>-1&&(this.containerClasses+=" cc_ie8")},loadTheme:function(e){var t=this.options.theme;-1===t.indexOf(".css")&&(t=i+t+".css");var n=document.createElement("link");n.rel="stylesheet",n.type="text/css",n.href=t;var o=!1;n.onload=s.bind(function(){!o&&e&&(e.call(this),o=!0)},this),document.getElementsByTagName("head")[0].appendChild(n)},render:function(){this.element&&this.element.parentNode&&(this.element.parentNode.removeChild(this.element),delete this.element),this.element=r.build(this.options.markup,this),this.container.firstChild?this.container.insertBefore(this.element,this.container.firstChild):this.container.appendChild(this.element)},dismiss:function(e)
{e.preventDefault&&e.preventDefault(),e.returnValue=!1,this.setDismissedCookie(),this.container.removeChild(this.element)},setDismissedCookie:function(){s.setCookie(n,"yes",this.options.expiryDays,this.options.domain,this.options.path)}},a=!1;(o=function(){a||"complete"!=document.readyState||(c.init(),a=!0,window[t]=s.bind(c.setOptionsOnTheFly,c))})(),s.addEventListener(document,"readystatechange",o)}}}();

  </script>
  <!-- End Cookie Consent plugin -->

</head>
<body class="homepage" onLoad="azzera_input()">

<script type="text/javascript">
<!-- 

function azzera_input()
{
/*
Questa funzione, da richiamare in seguito all'evento onLoad del tag <body>, azzera tutte le eventuali precedenti
selezioni di qualsiasi campo select all'interno del documento.
*/
	for (i = 0; i < document.forms.length; i++) {
		for (ii = 0; ii < document.forms[i].elements.length; i++) {
		   //alert(document.forms[i].name+' '+document.forms[i].elements[ii].name+' '+document.forms[i].elements[ii].type);
		   if (document.forms[i].elements[ii].type == 'select-one') {
			   document.forms[i].elements[ii].value = 0;
		   }
		}
	}
}
//-->
</script>


<?php

require_once('layout.php');	// funzioni necessarie a stampare i layout

?>

<table cellpadding="2" cellspacing="2" border="0" style="text-align: left; width: 100%;">
  <tbody>
    <tr>
      <td style="vertical-align: top; text-align: center;">
	  
<?php
# includi l'intestazione
include($filename_header);
?>

      </td>
    </tr>
    <tr>
      <td style="vertical-align: top;">
      <table cellpadding="2" cellspacing="2" border="0" style="text-align: left; width: 100%;">
        <tbody>

          <tr>
            <td class="left_column">
			
<?php
# includi la barra a sinistra
include("index_left.php");
?>

            </td>
            <td class="middle_column">
			
<?php
# includi il corpo centrale
include("index_middle.php")
?>

            </td>
            <td class="right_column">
			
<?php
# includi la barra a destra
include("index_right.php")
?>

            </td>
          </tr>

        </tbody>
      </table>
      </td>
    </tr>
    <tr>
      <td style="vertical-align: top; text-align: center;">
	  
	  
<?php
# includi il footer
include("custom/templates/footer.php")	  
?>
	  
	  <br>
      </td>
    </tr>
  </tbody>
</table>

</body>
</html>
