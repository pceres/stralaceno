<?php

function grafico($width,$heigth,$border,$input_file,$output_file) {
if ($width == NULL) {
	$width = 230*2;
	}

if ($heigth == NULL) {
	$heigth = 230*2;
	}

if ($border == NULL) {
	$border = 30;
	}

if ($output_file == NULL) {
	$output_to_file = 0; // 1 --> scrive su file l'immagine; 0 --> crea l'immagine e l'invia al browser
	}
else {
	$output_to_file = 1;
	$filename_output = $output_file;
	}

if ($input_file == NULL) {
	$filename_input='grafico.txt';
	}
else {
	$filename_input = $input_file;
	}


        $min_x_label="10";
        $max_x_label="130";
        $min_y_label="1289";
        $max_y_label="1291";
        $border=max($border,strlen($max_y_label)*9,strlen($max_x_label)*9); //ingrandisce i bordi per rendere visibili gli estremi
        $x_label="x";
        $y_label="y";
        $title="grafico";
        $passo_griglia_x=0.1;
        $passo_griglia_y=0.1;
        $has_axes=1;
        $has_legend=1;

        $img_handle = ImageCreate ($width, $heigth) or die ("Cannot Create image");
		
        $back_color = ImageColorAllocate ($img_handle, 255, 255, 255);
        $color_black=ImageColorAllocate ($img_handle, 0, 0, 0);
        $color_red=ImageColorAllocate ($img_handle, 255, 0, 0);
        $color_green=ImageColorAllocate ($img_handle, 0, 200, 0);
        $color_blue=ImageColorAllocate ($img_handle, 0, 0, 255);
        $color_yellow=ImageColorAllocate ($img_handle, 200, 200, 0);
        $color_pink=ImageColorAllocate ($img_handle, 255, 160, 160);
        $txt_color = ImageColorAllocate ($img_handle, 233, 114, 191);
        $line_color = ImageColorAllocate ($img_handle, 0, 0, 0);
        $dotted_style = array ($line_color,$line_color,$line_color,$back_color,$back_color,$back_color,$back_color,$back_color);
        $continuous_style = array ($line_color,$line_color);
        $axis_style = array ($line_color,$line_color); // solid line (2 pixels)
        $grid_style = array ($line_color,$line_color,$line_color,$back_color,$back_color,$back_color,$back_color,$back_color); // dashed line (3 black pixels, 5 background ones)
        $bulk=get_lines($filename_input);
        $count=0;
        $lines=array();
        $max_count=count($bulk);
        $min_x=1e6;
        $max_x=-1e6;
        $min_y=1e6;
        $max_y=-1e6;
        while ($count<$max_count)
        {
	$line=$bulk[$count++];
	switch ($line)
	 {
	 case "[width]":
		$newline=$bulk[$count++];
		$width=$newline;
	        	break;
	 case "[heigth]":
		$newline=$bulk[$count++];
		$heigth=$newline;
	        	break;
	 case "[title]":
		$newline=$bulk[$count++];
		$title=$newline;
	        	break;
	 case "[x_label]":
		$newline=$bulk[$count++];
		$x_label=$newline;
	        	break;
	 case "[y_label]":
		$newline=$bulk[$count++];
		$y_label=$newline;
	        	break;
	 case "[min_x_label]":
		$newline=$bulk[$count++];
		$min_x_label=$newline;
	        	break;
	 case "[max_x_label]":
		$newline=$bulk[$count++];
		$max_x_label=$newline;
	        	break;
	 case "[min_y_label]":
		$newline=$bulk[$count++];
		$min_y_label=$newline;
	        	break;
	 case "[max_y_label]":
		$newline=$bulk[$count++];
		$max_y_label=$newline;
	        	break;
	 case "[passo_griglia_x]":
		$newline=$bulk[$count++];
		$passo_griglia_x=$newline;
	        	break;
	 case "[passo_griglia_y]":
		$newline=$bulk[$count++];
		$passo_griglia_y=$newline;
	        	break;
	 case "[has_legend]":
		$newline=$bulk[$count++];
		$has_legend=$newline;
	        	break;
	 case "[has_grid]":
		$newline=$bulk[$count++];
		$has_grid=$newline;
	        	break;
	 case "[has_axes]":
		$newline=$bulk[$count++];
		$has_axes=$newline;
	        	break;
	//i parametri sottostanti sono relativi ad ogni singola spezzata
	 case "[label]":
		$newline=$bulk[$count++];
		$label=$newline;
	        	break;
	 case "[line_thickness]":
		$newline=$bulk[$count++];
		$line_thickness=$newline;
	        	break;
	 case "[line_style]":
		$newline=$bulk[$count++];
		switch ($newline)
		 {
		case "continuous":
			$style=array($line_color,$line_color);
	        		break;
		case "dashed":
			$style=array($line_color,$line_color,$line_color,$line_color,$back_color,$back_color,$back_color,$back_color,$back_color);
	        		break;
		case "dotted":
			$style=array($line_color,$line_color,$back_color,$back_color,$back_color,$back_color,$back_color);
	        		break;
		case "dash-dotted":
			$style=array($line_color,$line_color,$line_color,$line_color,$back_color,$back_color,$back_color,$back_color,$back_color,$line_color,$back_color,$back_color,$back_color,$back_color,$back_color);
	        		break;
		}
		$line_style=$style;
	        	break;
	 case "[line_color]":
		$newline=$bulk[$count++];
		switch ($newline)
		{
		case "black":
			$color=$color_black;
	        		break;
		case "red":
			$color=$color_red;
	        		break;
		case "green":
			$color=$color_green;
	        		break;
		case "blue":
			$color=$color_blue;
	        		break;
		case "yellow":
			$color=$color_yellow;
	        		break;
		case "pink":
			$color=$color_pink;
	        		break;
		}
		$line_color=$color;
	        	break;
	 case "[(x,y)]":
		$newline=$bulk[$count++];
		$ok=1;
		$vx=array();
		$vy=array();
		while ($ok)
		{
			//print ":$newline ($newline[0])";
			$arr=preg_split("~\t~",$newline);
			$x=$arr[0];
			$y=$arr[1];

			if ($x<$min_x) $min_x=$x;
			if ($x>$max_x) $max_x=$x;
			if ($y<$min_y) $min_y=$y;
			if ($y>$max_y) $max_y=$y;
			array_push($vx,$x);
			array_push($vy,$y);
			$newline=$bulk[$count++];
			$ok=(($count<=$max_count) and ($newline[0]<>"["));
		}
		$count--;
		$line=array("x"=>$vx,"y"=>$vy,"color"=>$line_color,"line_style"=>$line_style,"line_thickness"=>$line_thickness,"label"=>$label );
		array_push($lines,$line);

		# default per prossima eventuale linea
		unset($line_thickness); 
		unset($line_style); 
		unset($line_color); 
	        	break;
	}
        }
        $border=max($border,strlen($max_y_label)*9,strlen($max_x_label)*9); //ingrandisce i bordi per rendere visibili gli estremi
        $k0x=-$min_x_label/($max_x_label-$min_x_label);
        $k1x=1/($max_x_label-$min_x_label);
        $k0y=-$min_y_label/($max_y_label-$min_y_label);
        $k1y=1/($max_y_label-$min_y_label);
if ($has_grid==1)
{
        imagesetstyle ($img_handle, $grid_style); // linea tratteggiata
        for ($i=0;$i<=1;$i+=$passo_griglia_x)
        {
	myLine($img_handle,$i,0,$i,1,$width,$heigth,$border,IMG_COLOR_STYLED);
        }
        for ($i=0;$i<=1;$i+=$passo_griglia_y)
        {
	myLine($img_handle,0,$i,1,$i,$width,$heigth,$border,IMG_COLOR_STYLED);
        }
}
if ($has_axes==1) // disegna assi?
{
//griglia x
ImageString ($img_handle, 31, $width- $border-strlen($max_x_label)*9,$heigth- $border, $max_x_label, $txt_color);
ImageString ($img_handle, 31, 1*$border,$heigth- $border,  $min_x_label, $txt_color);
//griglia y
ImageString ($img_handle, 31, $border-strlen($max_y_label)*9,$border, $max_y_label, $txt_color);
ImageString ($img_handle, 31, $border-strlen($max_y_label)*9,$heigth- $border-1.5*9, $min_y_label, $txt_color);

//asse ascisse
        imagesetstyle ($img_handle, $axis_style); // linea continua
        ImageLine($img_handle,$width- $border,$heigth- $border, $border,$heigth- $border,$color_black); // lower x-axis
        ImageLine($img_handle,$width- $border*1.2,$heigth- $border*0.8, $width- $border,$heigth- $border,$color_black);
        ImageLine($img_handle,$width- $border*1.2,$heigth- $border*1.2, $width- $border,$heigth- $border,$color_black);
        ImageString ($img_handle, 31, $width- $border+9-9*strlen($x_label),$heigth- $border+9,  $x_label, $txt_color);
//asse ordinate
        ImageLine($img_handle, $border,$heigth- $border, $border, $border,$color_black);
        ImageLine($img_handle, $border,$border, $border*0.8, $border*1.2,$color_black);
        ImageLine($img_handle, $border, $border, $border*1.2, $border*1.2,$color_black);
        ImageString ($img_handle, 31, $border-(strlen($y_label)*0+2)*9,$border-2*9,  $y_label, $txt_color);
// titolo
        ImageString ($img_handle, 31, ($width-strlen($title)*9)/2, $border*0.1,  $title, $txt_color);
}
if ($has_legend==1) //disegna legenda?
{
	// disegna legenda
	$pos=0;
	foreach ($lines as $line)
	{
		$label=$line["label"];
		if ($label != "NULL") { # per non avere l'etichetta, indicare "NULL"
			$style=$line["line_style"];
			if (array_key_exists("line_thickness",$line)) {
				$line_thickness = $line["line_thickness"];
				}
			else {
				$line_thickness = 1;
				}
			$mark_size=20;
			ImageString ($img_handle, 31, $width- $border-strlen($label)*9-$mark_size,$border+13*$pos,  $label, $txt_color);
			imagesetstyle ($img_handle,$style);
			imagesetthickness($img_handle,$line_thickness);
			imageLine($img_handle,$width- $border-20+3,$border+13*$pos+8,$width- $border-20-3+$mark_size,$border+13*$pos+8, IMG_COLOR_STYLED);
			$pos++;
			}
	}
}

        // plotta linee in $linee
        foreach ($lines as $line)
	{
	        	$vx=$line["x"];
	        	$vy=$line["y"];
	        	$style=$line["line_style"];
				if (array_key_exists("line_thickness",$line)) {
					$line_thickness = $line["line_thickness"];
					}
				else {
					$line_thickness = 1;
					}
				imagesetthickness($img_handle,$line_thickness);
	        	imagesetstyle ($img_handle, $style);
	        	for ($i=0;$i<count($vx)-1;$i++)
	        	{
			myLine($img_handle,$vx[$i]*$k1x+$k0x,$vy[$i]*$k1y+$k0y,$vx[$i+1]*$k1x+$k0x,$vy[$i+1]*$k1y+$k0y,$width,$heigth,$border,IMG_COLOR_STYLED);
	        	}
	}
if ($output_to_file==0)
{
	header ("Content-type: image/PNG");
	header("Cache-Control: no-cache, must-revalidate"); // HTTP/1.1
	header("Expires: Sat, 26 Jul 1997 05:00:00 GMT");   // Date in the past
	
	ImagePNG($img_handle);
}
else
{
	ImagePNG($img_handle,$filename_output);
	// print "Immagine creata!";
}
ImageDestroy($img_handle);
#exit();
} # fine funzione grafico(...)
?> 

<?php
function myLine($id,$x1,$y1,$x2,$y2,$width,$heigth,$border,$color)
{
	imageLine($id,$border+$x1*($width-2*$border),$heigth-$y1*($heigth-2*$border)-$border,$border+$x2*($width-2*$border),$heigth-$y2*($heigth-2*$border)-$border,$color);
}


function set_dotted($img_hadle,$line_color,$back_color)
{
	/* Draw a dashed line, 5 red pixels, 5 white pixels */
	$style = array ($line_color,$line_color,$line_color,$line_color,$line_color,$back_color,$back_color,$back_color,$back_color,$back_color);
	//imagesetstyle ($img_handle, $style);
}


//############################
function get_lines($filename){
$fh=fopen($filename,"r");
$arr=array();
while (!feof($fh))
	{
	$ks=trim(fgets($fh));
	if ((strlen($ks)>0) and (substr($ks,0,1)<>'#'))
		{
		array_push($arr,$ks);
		}
	}
return $arr;
}

?>


