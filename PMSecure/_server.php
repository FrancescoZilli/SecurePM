<?php
	include('functions.php');

	header('Content-Type: text/event-stream');
	header('Cache-Control: no-cache');

	session_start();

	if( isset($_SESSION['username']) && isset($_COOKIE['friend']) ){
		$user = $_SESSION['username'];
		$friend = $_COOKIE['friend'];

		$dbconn = db_connect();
		$sql = "SELECT u_lastmsg FROM sc_friends WHERE (u_username = '". $user . "' AND u_friend = '". $friend."' ) OR (u_username = '". $friend . "' AND u_friend = '". $user."' )";
		$query = mysql_query($sql);
		$result = mysql_fetch_row($query);
	}

	$list = parseMessage($result[0]);

	if( $result[0] != $_COOKIE['last_sent'] && $list[0] != $user ) {
		echo "data: {$result[0]}\n\n";
		flush();
	}
	
?>