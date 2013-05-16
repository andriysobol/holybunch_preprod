<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">

<html>
<head>
	<title>Untitled</title>
</head>

<body>

<?php
$filePath = 'C:/Apache24/htdocs/holybunch_prep/www/wp-content/uploads/2013/04'; // infected test file
$db = '"C:/ProgramData/.clamwin/db"';
$cmd = "clamscan --database=$db $filePath";
$output = shell_exec("%ProgramFiles%\Windows NT\Accessories\WORDPAD.EXE");
echo $output;
?>

</body>
</html>
