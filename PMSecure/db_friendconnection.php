<?php
	include('functions.php');

	session_start();

	header('Content-Type: text/event-stream');
	header('Cache-Control: no-cache');

	if(isset($_SESSION['username'])){
		$user = $_SESSION['username'];
	}
	$friend = "luca"; //$_POST['friend'];
	$last_sent = "";


	$dbconn = db_connect();
	$sql = "SELECT u_lastmsg FROM sc_friends WHERE (u_username = '". $user . "' AND u_friend = '". $friend."' ) OR (u_username = '". $friend . "' AND u_friend = '". $user."' )";
	$query = mysql_query($sql);
	$result = mysql_fetch_row($query);

	echo $result[0];
	flush();

	/*if( $result[0] != $last_sent) {
		$last_sent = $result[0];
		echo $last_sent;
		flush();
	}*/
		
?>