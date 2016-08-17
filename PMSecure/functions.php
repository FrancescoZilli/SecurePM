<?php


//connessione al database
function db_connect($dbname) {
	$servername = "localhost";
	$username = "root";
	$password = "calcio";

	// Create connection
	$conn = new mysqli($servername, $username, $password, $dbname);
	if ($conn->connect_error) {
	    die("Connection failed: " . $conn->connect_error);
	}

	return $conn;
}


//controllo presenza utente
function check_user($username) {

	$conn = db_connect('spm_db');

	$stmt = $conn->prepare("SELECT EXISTS(SELECT * FROM sc_users WHERE u_username = ?)");
	$stmt->bind_param("s", $username);
	$stmt->execute();
	$stmt->bind_result($res);

	if( $stmt->fetch() ) {
		$exists = $res;
	}

	$stmt->close();
	$conn->close();

	return $exists;

}



//inserimento utente
function insert_user($name, $surname, $bday, $address, $username, $password) {

	$salt = generate_salt();
	$hpassword = hash_password($password . $salt);
	$data = "";
	$conn = db_connect('spm_db');

	$stmt = $conn->prepare("INSERT INTO sc_users (u_name, u_surname, u_username, u_password, u_address, u_birthday, u_salt) VALUES (?, ?, ?, ?, ?, ?, ?)");
	$stmt->bind_param('sssssss', $name, $surname, $username , $hpassword, $address, $bday, $salt );
	$success = $stmt->execute();

	if(!$success){  //stampo un errore
		if($conn->errno == 1062) {
			$data = "USERNAME e/o MAIL inserite sono già utilizzate e non più disponibili";			
		} else {
		 	$data  = "ERRORE nell'inserimento dell'utente; contattare admin";		 	
		}
		header( "refresh:4;url=registration.html" );
	}
	else{
		$data = "Utente inserito con successo. Verrai reindirizzato all'area di login a momenti!";
		header( "refresh:3;url=index.php" );
	}

	$stmt->close();
	$conn->close();
	return $data;		                                                            
}


// add friend to contact list
function add_friend($user1, $user2){
	$conn = db_connect('spm_db');

	if(check_user($user2) && $user1 != $user2){
		if(strcasecmp($user1, $user2) < 0){

			$stmt = $conn->prepare("SELECT EXISTS(SELECT * FROM sc_friends WHERE u_username = ? AND u_friend = ?)" );
			$stmt->bind_param('ss', $user1, $user2);
			$stmt->execute();
			$stmt->bind_result($exists);
			 
			if( $stmt->fetch() ) {
				$query_result = $exists;
			}
			$stmt->close();

			if($query_result == 0){
				$sql = $conn->prepare("INSERT INTO sc_friends (u_username, u_friend) VALUES (?, ?)");
				$sql->bind_param('ss', $user1, $user2);
				$success = $sql->execute();
				$sql->close();
			}
			
		} else {

			$stmt = $conn->prepare("SELECT EXISTS(SELECT * FROM sc_friends WHERE u_username = ? AND u_friend = ?)" );
			$stmt->bind_param('ss', $user2, $user1);
			$stmt->execute();
			$stmt->bind_result($exists);
			if( $stmt->fetch() ) {
				$query_result = $exists;
			}
			$stmt->close();
			
			if($query_result == 0){

				$sql = $conn->prepare("INSERT INTO sc_friends (u_username, u_friend) VALUES (?, ?)");
				$sql->bind_param('ss', $user2, $user1);
				$success = $sql->execute();
				$sql->close();
			}

		}

		if(!$success){  //stampo un errore
			echo 'Query error: ' . $sql . "\n" . $conn->error();
		}
		$conn->close();
	}	
}


// controllo se username e password corrispondono ad un utente
function login_user($username, $password) {
	$conn = db_connect('spm_db');
	$salt = retrieve_salt($username);
	$hpassword = hash_password($password . $salt);
	$stmt = $conn->prepare("SELECT EXISTS(SELECT * FROM sc_users WHERE u_username = ? AND u_password = ?)");
	$stmt->bind_param('ss', $username, $hpassword);
	$stmt->execute();
	$stmt->bind_result($exists);
	if( $stmt->fetch() )
		$result = $exists;

	$stmt->close();
	$conn->close();

	return $exists;
}


// clear the your friend's last message when logging out
function clear_message($usr, $fnd) {
	$conn = db_connect('spm_db');

	$lex_order = strcasecmp($usr, $fnd);
	if( $lex_order < 0 ){
		//l'utente è in u_username
		$query = $conn->prepare("UPDATE sc_friends SET u_lastf = '' WHERE u_username = ? AND u_friend = ?");
		$query->bind_param('ss', $usr, $fnd);
	} else {
		//l'utente è in u_friends
		$query = $conn->prepare("UPDATE sc_friends SET u_lastu = '' WHERE u_username = ? AND u_friend = ?");
		$query->bind_param('ss', $fnd, $usr);
	}

	$query->execute();
	$query->close();
}


// hashing della password
function hash_password($password) {
	return sha1($password);
}


// creating unique salt for each user
function generate_salt() {
    $salt = "";
    $alphabet = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789"; // LENGTH IS HARDSCRIPTED DUE TO " Uninitialized string offset " ERROR

    for( $i=0; $i < 10; $i++ ) {
    	$rnd = mt_rand(0, 61);
        $salt .= $alphabet[$rnd];
    }

    return $salt;
}


// retrieve salt from database, given the username
function retrieve_salt($user) {
	$salt = ""; // if undefined, empty string is returned
	
	$conn = db_connect('spm_db');
	$stmt = $conn->prepare("SELECT u_salt FROM sc_users WHERE u_username = ?");
	$stmt->bind_param('s', $user);
	$stmt->execute();
	$stmt->bind_result($res);

	if( $stmt->fetch() )
		$salt = $res;

	$stmt->close();
	$conn->close();

	return $salt;
}


// split message in useful values
function parseMessage($string) {
	$len = strlen($string) - 2;
	$inner_msg = substr($string, 1, $len);
	list ($nome, $tempo, $msg) = split("[|]{3}", $inner_msg);

	$result = array();
	array_push($result, $nome);
	array_push($result, $tempo);

	return $result;
}

// split date in useful values
function parseTime($date) {
	list ($giorno, $ora) = split(" ", $date);
	$len = strlen($ora) - 3;
 	$ora = substr($ora, 0, $len);
	return $ora;
}

// approximately tells you which is first (no days since cookies last less)
// returns 1 if $t1 is greater than $t2 ($t1 comes after $t2)
function compareTimes($t1, $t2) {
	$h1 = (int) substr($t1, 0, 2);
	$m1 = (int) substr($t1, 3, 2);
	$h2 = (int) substr($t2, 0, 2);
	$m2 = (int) substr($t2, 3, 2);

	$res = 0;

	if( $h1 > $h2 )
		$res = 1;
	else if( $h1 == $h2 && $m1 >= $m2 )
		$res = 1;
	else
		$res = 0;

	return $res;

}


?>