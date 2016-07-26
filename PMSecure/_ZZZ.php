<?php
	include('functions.php');

	$str1 = "{marco|||12:33||||uezza}";
	$buf = parseMessage($str1);

	
	echo "NOME: " . $buf[0];
	
?>