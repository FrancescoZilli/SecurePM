<?php
	include('functions.php');

	header('Content-Type: text/event-stream');
	header('Cache-Control: no-cache');

	session_start();

	if( isset($_SESSION['username']) && isset($_COOKIE['friend']) ){
		$user = $_SESSION['username'];
		$friend = $_COOKIE['friend'];



		$conn = db_connect('spm_db');

		$lex_order = strcasecmp($user, $friend);  //marco, luca -> 1
		if( $lex_order < 0 ) {

			//l'utente è in u_username
			$user1 = $user;
			$user2 = $friend;

			$query = $conn->prepare("SELECT u_lastf FROM sc_friends WHERE u_username = ? AND u_friend = ? ");
			$query->bind_param('ss', $user1, $user2);			
			$query->execute();
			
			/* bind variables to prepared statement's result */
		    $query->bind_result($col);

		    /* fetch values */
		    if($query->fetch()) {
		    	$response = (string) $col;
		    }

		} else {
			//l'utente è in u_friends
			$user1 = $friend;
			$user2 = $user;

			$query = $conn->prepare("SELECT u_lastu FROM sc_friends WHERE u_username = ? AND u_friend = ? ");
			$query->bind_param('ss', $user1, $user2);			
			$query->execute();
			
			/* bind variables to prepared statement */
		    $query->bind_result($col);

		    /* fetch values */
		    if ($query->fetch()) {
		    	$response = (string) $col;
		    }
		}

		
		$query->close();
		$conn->close();

		$list = parseMessage($response);
		$time = parseTime($list[1]);

		// tecnicamente list[0] != user non dovrebbe servire più! ----> delete lastf / lastu when logging out
		if( $response != $_COOKIE['last_sent'] && $list[0] != $user && $time != $_COOKIE['last_time'] ) {
			echo "data: {$response}\n\n";
			flush();
		}
	}

	
	
?>