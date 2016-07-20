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
	
	$dbconn = db_connect();
	$sql = "SELECT * FROM sc_friends WHERE u_username = '". $user . "' OR u_friend = '". $user."' ";
	$query = mysql_query($sql);
	$result = array();

	while( $item = mysql_fetch_array($query) ) {
		if($item[0]!=$user){
			array_push($result, $item[0]);
		}else{
			array_push($result, $item[1]);
		}
	}
	
	echo json_encode($result);
	
?>