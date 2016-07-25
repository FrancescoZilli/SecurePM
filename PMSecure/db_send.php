<?php 
	include('functions.php');

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

	$date = date('d/m/Y H:m');

	$composedMess = "{".$sender."|||". $date . "|||" . $message . "}";


	
	$dbconn = db_connect();
	$update = "UPDATE sc_friends SET u_chatlog = IFNULL (CONCAT( u_chatlog , '".$composedMess."[|||]' ), '".$composedMess."[|||]' ) WHERE (u_username = '". $sender . "' AND u_friend = '". $dest ."') OR (u_username = '". $dest . "' AND u_friend = '". $sender ."')"  ;

	$query = mysql_query($update);
	echo $composedMess;
?>