#!/usr/local/bin/php
<?php

$ping=1;


if ($ping)
{
define("IMG_DATA","R0lGODdhYgAmAPcAAAAAAAAAVQAAqgAA/wAkAAAkVQAkqgAk/wBJAABJVQBJqgBJ/wBtAABtVQBtqgBt/wCSAACSVQCSqgCS/wC2AAC2VQC2qgC2/wDbAADbVQDbqgDb/wD/AAD/VQD/qgD//yQAACQAVSQAqiQA/yQkACQkVSQkqiQk/yRJACRJVSRJqiRJ/yRtACRtVSRtqiRt/ySSACSSVSSSqiSS/yS2ACS2VSS2qiS2/yTbACTbVSTbqiTb/yT/ACT/VST/qiT//0kAAEkAVUkAqkkA/0kkAEkkVUkkqkkk/0lJAElJVUlJqklJ/0ltAEltVUltqklt/0mSAEmSVUmSqkmS/0m2AEm2VUm2qkm2/0nbAEnbVUnbqknb/0n/AEn/VUn/qkn//20AAG0AVW0Aqm0A/20kAG0kVW0kqm0k/21JAG1JVW1Jqm1J/21tAG1tVW1tqm1t/22SAG2SVW2Sqm2S/222AG22VW22qm22/23bAG3bVW3bqm3b/23/AG3/VW3/qm3//5IAAJIAVZIAqpIA/5IkAJIkVZIkqpIk/5JJAJJJVZJJqpJJ/5JtAJJtVZJtqpJt/5KSAJKSVZKSqpKS/5K2AJK2VZK2qpK2/5LbAJLbVZLbqpLb/5L/AJL/VZL/qpL//7YAALYAVbYAqrYA/7YkALYkVbYkqrYk/7ZJALZJVbZJqrZJ/7ZtALZtVbZtqrZt/7aSALaSVbaSqraS/7a2ALa2Vba2qra2/7bbALbbVbbbqrbb/7b/ALb/Vbb/qrb//9sAANsAVdsAqtsA/9skANskVdskqtsk/9tJANtJVdtJqttJ/9ttANttVdttqttt/9uSANuSVduSqtuS/9u2ANu2Vdu2qtu2/9vbANvbVdvbqtvb/9v/ANv/Vdv/qtv///8AAP8AVf8Aqv8A//8kAP8kVf8kqv8k//9JAP9JVf9Jqv9J//9tAP9tVf9tqv9t//+SAP+SVf+Sqv+S//+2AP+2Vf+2qv+2///bAP/bVf/bqv/b////AP//Vf//qv///yH5BAAAAAAALAAAAABiACYAAAj/ABUkEEhwoMGCCA8qTMhwocOGEB9KhGjL0i2LtjJqtHWxo0VLG29lFEmS48iTl2ylNLkRpMuKGl9+3GiyZMmUOFXqTKlAga1/QIMKHUq0qNGjSJMqXRr0Vs+f/wb2nNqTqdWrWLMGtfUUaAKbHBVoHUu27D+uPoH6lCOFrRxLCR4qZUcXKDukd80Gzct3aF6hTtP+U2DJbVu4J0VyTfqXbt3Hjv/ddcy3sd3Lkv9K3jwZc+StXQfbkkOatNNbpUlzldrT4F7NfYV23ruZM+fOsW3jtl37bOi1qbmODu4TrFjZjzFnnh0ZdvLkvXdPhv539S21ttpqt5VgdFu2Todr/7+VgPbl2H2Z1569vv153eb/Ov2KPbWc09rBd7dPHnnl9c0t959d6mXml3kGfgZUYFB9VZob9ymAGmkQcnfLd6aVp9eGWFkHFEcfXaQRWCRmxOGJTJGXFkEKGNCaQAO5SFUCBtA4UAFTsZYjaxLFBWNBPr7o0EEJGeTQPwyeJdIltzCJUU01OenRkiblxCRLSzbZEUe3dEmlljaF2CVIME1JU0ok+UYfimy2SZSHbsbZZpJy1skhnXbmSRZaUOnpZ4eh/SnoUtytOeihR813HaKMEnVJoI1GymeklPomWKWMqtgnpofCyWmncS366aCajnpoqaYKimqqfhYqKqt5KkkKq5/kGTprndxdequcuVZUopYhBitiTiRi9CtNNCmmLJQlYcnssyKBJGRBOCZQQFw1WhvXtdoqUG222cYlrpE/TmQuueZGRFBAADs="); // StralacenoWeb
//define("IMG_DATA","iVBORw0KGgoAAAANSUhEUgAAAGIAAAAmCAIAAACwDtkjAAAACXBIWXMAAA7DAAAOwwHHb6hkAAABOklEQVR4nO2Xyw2DMBBEOVAapaQSGkgJlBApx5SRdnJwDkSO490143j5CM3THjAfL3mMEen6YWQtVtcP4+3xZBXqqykQA0VTIXUu/eZCTn5NUyw53BJdk5W69mZzFzy5mRRHQVWrx9R0ud7Tms9rTBmeo0iqyTdEK2pqSVl6SRCrL5szXpWuOGtCOZRdrF7gnVRrakmZ9TOso8F+H+EzgDuto56awJRlSVEfo6opiJeUrybrTtJ2PprU/QVNVWmS2+5pKjzO3TTVLro4QxyuoekQacqyra7T7FD5uylbKbKLNW1hW96nqcnq9EeFs5Br2v3v5QFL0eQYpdOUoomoUBMENUFQEwQ1QVATBDVBUBMENUFQEwQ1QVATBDVBUBMENUFQEwQ1QVATBDVBUBMENUH8aGIV6qOJtVhvitqQTVHOp7kAAAAASUVORK5CYII="); // fanKounter
 define("TODAY_TXT_YX","4,17");
 define("TODAY_VAL_YX","39,14");
 define("TOTAL_TXT_YX","4,27");
 define("TOTAL_VAL_YX","39,24");
 define("TODAY_TXT_SIZE",1);
 define("TODAY_VAL_SIZE",3);
 define("TOTAL_TXT_SIZE",1);
 define("TOTAL_VAL_SIZE",3);
 define("TODAY_TXT_COLOR","0x14,0x14,0x50");
 define("TODAY_VAL_COLOR","0x3C,0x3C,0x50");
 define("TOTAL_TXT_COLOR","0x14,0x14,0x50");
 define("TOTAL_VAL_COLOR","0x3C,0x3C,0x50");

$__img=imagecreatefromstring(base64_decode(IMG_DATA));
//$__img=imagecreatefrompng('http://localhost/work/temp.png');

 #eval("\$__col1=imagecolorallocate(\$__img,".TODAY_TXT_COLOR.");");
 #eval("\$__col2=imagecolorallocate(\$__img,".TODAY_VAL_COLOR.");");
 #eval("\$__col3=imagecolorallocate(\$__img,".TOTAL_TXT_COLOR.");");
 #eval("\$__col4=imagecolorallocate(\$__img,".TOTAL_VAL_COLOR.");");

 $__txtpad=max(strlen(LAN_TODAY),strlen(LAN_TOTAL));

 #eval("imagestring(\$__img,".TODAY_TXT_SIZE.",".TODAY_TXT_YX.",\"".str_pad(LAN_TODAY,$__txtpad," ",STR_PAD_LEFT)."\",\$__col1);");
 #eval("imagestring(\$__img,".TODAY_VAL_SIZE.",".TODAY_VAL_YX.",\"".str_pad(TODAY_VAL,8," ",STR_PAD_LEFT)."\",\$__col2);");
 #eval("imagestring(\$__img,".TOTAL_TXT_SIZE.",".TOTAL_TXT_YX.",\"".str_pad(LAN_TOTAL,$__txtpad," ",STR_PAD_LEFT)."\",\$__col3);");
 #eval("imagestring(\$__img,".TOTAL_VAL_SIZE.",".TOTAL_VAL_YX.",\"".str_pad(TOTAL_VAL,8," ",STR_PAD_LEFT)."\",\$__col4);");

 header("Content-type: image/png");
 imagepng($__img);
 imagedestroy($__img);
// return;
}

if (1-$ping)
{
	$filename = 'http://localhost/work/temp.gif';

	$file = file($filename);
	$ks='';
	foreach ($file as $line)
	{
		$ks .= $line;
	}
	echo base64_encode($ks);
}

?>
