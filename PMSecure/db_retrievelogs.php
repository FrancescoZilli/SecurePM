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
	
	$lex_order = strcasecmp($user, $friend);  //marco, luca -> 1
	if( $lex_order < 0 ) {
		$user1 = $user;
		$user2 = $friend;
	} else {
		$user1 = $friend;
		$user2 = $user;
	}

	// connect to database
	$conn = db_connect('spm_db');
	$query = $conn->prepare("SELECT u_chatlog FROM sc_friends WHERE u_username = ? AND u_friend = ?");
	$query->bind_param('ss', $user1, $user2);
	$query->execute();
	
	// store result
	$query->bind_result($col);

	if( $query->fetch() ) {		
		$chatlog = (string) $col;
	}

	$query->close();
	$conn->close();
	
	// return chatlog to the client
	echo $chatlog;
	
?>