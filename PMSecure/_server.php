<?php
	include('functions.php');

	header('Content-Type: text/event-stream');
	header('Cache-Control: no-cache');

	error_reporting(0);
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

		// clear message after fetching it
		if( $lex_order < 0 ){
			//l'utente è in u_username
			$query2 = $conn->prepare("UPDATE sc_friends SET u_lastf = '' WHERE u_username = ? AND u_friend = ?");
			$query2->bind_param('ss', $user, $friend);
		} else {
			//l'utente è in u_friends
			$query2 = $conn->prepare("UPDATE sc_friends SET u_lastu = '' WHERE u_username = ? AND u_friend = ?");
			$query2->bind_param('ss', $friend, $user);
		}

		$query2->execute();
		$query2->close();
		
		$conn->close();

		$list = parseMessage($response);
		$time = parseTime($list[1]);

		if( $response != $_COOKIE['last_sent'] && compareTimes($time, $_COOKIE['last_time']) == 1  ) {
			echo "data: {$response}\n\n";
			flush();
		}	

		
	}

	
	
?>