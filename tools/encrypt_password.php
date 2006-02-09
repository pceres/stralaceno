#!/usr/local/bin/php 
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 TRANSITIONAL//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<?php

require_once('../libreria.php');

# dichiara variabili
extract(indici());
?>
<head>
  <title>Amministrazione</title>
  <meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1">
  <meta name="GENERATOR" content="Quanta Plus">
  <style type="text/css">@import "<?php echo $filename_css ?>";</style>
</head>

<body>

<?php

$password=$_REQUEST['password'];

if (!isset($password))
{
?>
	<form action="<?php echo $script_abs_path; ?>tools/encrypt_password.php">
		password da criptare:<input name="password" type="edit">
		<input type="submit" value="cripta">
	</form>	
<?php
}
else
{
	echo md5($password);
}

?>

</body>
</html>
