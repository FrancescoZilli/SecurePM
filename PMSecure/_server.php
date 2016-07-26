<?php
	include('functions.php');

	header('Content-Type: text/event-stream');
	header('Cache-Control: no-cache');

	session_start();

	if( isset($_SESSION['username']) && isset($_COOKIE['friend']) ){
		$user = $_SESSION['username'];
		$friend = $_COOKIE['friend'];
		$dbconn = db_connect();

		// check which column you should use to store your data
		$sql = "SELECT EXISTS(SELECT * FROM sc_friends WHERE u_username = '" . $user . "' AND u_friend = '" . $friend . "')";
		$query = mysql_query($sql);
		$exists = mysql_fetch_row($query);
		if( $exists[0] == 1 ) {
			//il destinatario è in u_friends
			$wheretoread = "u_lastf";
		} else {
			//il destinatario è in u_username
			$wheretoread = "u_lastu";
		}

		$sql = "SELECT " . $wheretoread . " FROM sc_friends WHERE (u_username = '". $user . "' AND u_friend = '". $friend."' ) OR (u_username = '". $friend . "' AND u_friend = '". $user."' )";
		$query = mysql_query($sql);
		$result = mysql_fetch_row($query);

		$list = parseMessage($result[0]);
		$time = parseTime($list[1]);

		if( $result[0] != $_COOKIE['last_sent'] && $list[0] != $user && $time != $_COOKIE['last_time'] ) {
			echo "data: {$result[0]}\n\n";
			flush();
		}

		/*if( $list[1] != $_COOKIE['last_sent'] && $list[0] != $user ) {
			echo "data: {$result[0]}\n\n";
			flush();
		}*/

		mysql_close($dbconn);
	}

	
	
?>