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
		$sender = $_SESSION['username'];
	}

	$message = $_POST['msg'];
	$dest = $_POST['to'];

	$date = date('d/m/Y H:m:s');

	$composedMess = "{".$sender."|||". $date . "|||" . $message . "}[|||]";

	//connect to server
	$conn = db_connect('spm_db');
	
	$lex_order = strcasecmp($sender, $dest);
	if( $lex_order < 0 ){
		//l'utente è in u_username
		$user1 = $sender;
		$user2 = $dest;
	} else {
		//l'utente è in u_friends
		$user1 = $dest;
		$user2 = $sender;
	}

	$stmt = $conn->prepare("UPDATE sc_friends SET u_chatlog = IFNULL (CONCAT( u_chatlog , ? ), ? ) WHERE u_username = ? AND u_friend = ?");
	$stmt->bind_param('ssss', $composedMess, $composedMess, $user1, $user2);

	
	$stmt->execute();
	$stmt->close();

	$composedMess = "{".$sender."|||". $date . "|||" . $message . "}";

	if( $lex_order < 0 ) {
		$stmt2 = $conn->prepare("UPDATE sc_friends SET u_lastu = ? WHERE u_username = ? AND u_friend = ?");
		$stmt2->bind_param('sss',  $composedMess, $user1, $user2);
		$stmt2->execute();
		$stmt2->close();
	} else {
		$stmt2 = $conn->prepare("UPDATE sc_friends SET u_lastf = ? WHERE u_username = ? AND u_friend = ?");
		$stmt2->bind_param('sss',  $composedMess, $user1, $user2);
		$stmt2->execute();
		$stmt2->close();

	}		

	echo $composedMess;

	$conn->close();
?>