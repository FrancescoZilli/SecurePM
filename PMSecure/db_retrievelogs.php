<?php
	include('functions.php');

	session_start();

	// Prevent caching.
	header('Cache-Control: no-cache, must-revalidate');
	header('Expires: Mon, 01 Jan 1996 00:00:00 GMT');

	// The JSON standard MIME header.
	header('Content-type: application/json');

	if(isset($_SESSION['username'])){
		$user = $_SESSION['username'];
	}

	$friend = $_POST['friend'];
	
	$dbconn = db_connect();
	$sql = "SELECT u_chatlog FROM sc_friends WHERE (u_username = '". $user . "' AND u_friend = '". $friend."' ) OR (u_username = '". $friend . "' AND u_friend = '". $user."' )";
	$query = mysql_query($sql);
	$result = array();

	while( $item = mysql_fetch_array($query) ) {
		array_push($result, $item);
	}
	
	echo ($result);
	
?>