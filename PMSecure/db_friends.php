<?php
	include('functions.php');

	session_start();

	// Prevent caching.
	header('Cache-Control: no-cache, must-revalidate');
	header('Expires: Mon, 01 Jan 1996 00:00:00 GMT');

	// The JSON standard MIME header.
	header('Content-type: application/json');

	if(isset($_SESSION['username'])){
		$user = $_SESSION['username'];
	}

	$conn = db_connect('spm_db');

	$stmt = $conn->prepare("SELECT u_username, u_friend FROM sc_friends WHERE u_username = ? OR u_friend = ?");
	$stmt->bind_param("ss", $user, $user);
	$stmt->execute();
	$stmt->bind_result($u1, $u2);

	$result = array();

	while ($stmt->fetch()) {
		if( $u1 != $user ) {
			array_push($result, $u1);
		}else{
			array_push($result, $u2);
		}
    }

	echo json_encode($result);
	
	$stmt->close();
	$conn->close();	
?>