<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Documento sin título</title>
</head>
	<?php
		$json =  file_get_contents('http://localhost/api.esfinge/?id=14', 0, null, null);
		
		$json = json_decode($json);
		
	?>
<body>
</body>
</html>