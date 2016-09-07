<?php
	include('functions.php');

	error_reporting(0);
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
	$db_response = "";
	
	$friend_exists = check_user($friend);

	if($friend_exists == 1) {
		//friend is registered on database
		add_friend($user, $friend);
		$db_response = "Adding friend...will be in your contact list now";
	} 
	else {
		$db_response = "Cannot add friends not registered on the website";
	}
	
	echo $db_response;
	
?>