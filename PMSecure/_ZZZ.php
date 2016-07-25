<?php
	include('functions.php');

	// Prevent caching.
	header('Cache-Control: no-cache, must-revalidate');
	header('Expires: Mon, 01 Jan 1996 00:00:00 GMT');

	// The JSON standard MIME header.
	header('Content-type: application/json');

	$result = strcasecmp('l', 'aaa');
	
	echo $result;
	
?>