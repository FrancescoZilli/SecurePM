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

	$date = date('d/m/Y H:m:s');

	$composedMess = "{".$sender."|||". $date . "|||" . $message . "}";
	
	$dbconn = db_connect();

	// check which column you should use to store your data
	$sql = "SELECT EXISTS(SELECT * FROM sc_friends WHERE u_username = '" . $sender . "' AND u_friend = '" . $dest . "')";
	$query = mysql_query($sql);
	$exists = mysql_fetch_row($query);
	if( $exists[0] == 1 ) {
		//l'utente è in u_username
		$wheretowrite = "u_lastu";
	} else {
		//l'utente è in u_friends
		$wheretowrite = "u_lastf";
	}

	$update = "UPDATE sc_friends SET u_chatlog = IFNULL (CONCAT( u_chatlog , '".$composedMess."[|||]' ), '".$composedMess."[|||]' ) WHERE (u_username = '". $sender . "' AND u_friend = '". $dest ."') OR (u_username = '". $dest . "' AND u_friend = '". $sender ."')" ;
	$query = mysql_query($update);

	// second query
	$insert_lstmsg = "UPDATE sc_friends SET " . $wheretowrite . " = '".$composedMess."' WHERE (u_username = '". $sender . "' AND u_friend = '". $dest ."') OR (u_username = '". $dest . "' AND u_friend = '". $sender ."')" ;
	$query = mysql_query($insert_lstmsg);

	echo $composedMess;

	mysql_close($dbconn);

	//sleep(5);
?>