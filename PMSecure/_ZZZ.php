<?php
	include('functions.php');

	// Prevent caching.
	header('Cache-Control: no-cache, must-revalidate');
	header('Expires: Mon, 01 Jan 1996 00:00:00 GMT');

	// The JSON standard MIME header.
	header('Content-type: application/json');

	$user = 'marco';
	
	$dbconn = db_connect();
	$sql = "SELECT u_salt FROM sc_users WHERE u_username = '". $user . "' ";
	$query = mysql_query($sql);

	$salt = mysql_fetch_array($query);
	
	echo date('d/m/Y');
	
?>