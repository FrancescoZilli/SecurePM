<?php

//connessione al database
function db_connect() {
	$db = mysql_connect('127.0.0.1', 'root', 'calcio') or die ('Impossibile connettersi al database'); 
	mysql_selectdb('spm_db') or die ('Errore selezione database'); //select database
	return $db;
}


//controllo presenza utente
function check_user($username) {

	$sql = "SELECT EXISTS(SELECT * FROM sc_users WHERE u_username = '" . $username . "')";
	$exists = mysql_query($sql);

	return $exists;
}



//inserimento utente
function insert_user($name, $surname, $bday, $address, $username, $password) {

	$salt = generate_salt();
	$hpassword = hash_password($password . $salt);
	$sql = "INSERT INTO sc_users (u_name, u_surname, u_username, u_password, u_address, u_birthday, u_salt) VALUES ('". $name ."', '". $surname ."', '". $username ."', '". $hpassword ."', '". $address ."', '". $bday ."', '". $salt ."')";

	$data = array(0, "");		// POSSIBILE CONVERTIRLO AD UNA STRINGA

	if(!mysql_query($sql)){  //stampo un errore
		if(mysql_errno() == 1062) {
			$data[0] = 1;
			$data[1] = "USERNAME e/o MAIL inserite sono già utilizzate e non più disponibili";			
		} else {
			$data[0] = 2;
		 	$data[1]  = "ERRORE nell'inserimento dell'utente; contattare admin";		 	
		}
		header( "refresh:4;url=registration.html" );
	}
	else{
		$data[1] = "Utente inserito con successo. Verrai reindirizzato all'area di login a momenti!";
		header( "refresh:3;url=index.php" );
	}

	return $data;		                                                            
}


// add friend to contact list
function add_friend($user1, $user2){
	$dbconn = db_connect();

	if(check_user($user2) && $user1 != $user2){
		$sql = "SELECT EXISTS(SELECT * FROM sc_friends WHERE ( u_username = '" . $user1 . "' AND u_friend = '". $user2 ."' ) OR ( u_username = '" . $user2 . "' AND u_friend = '". $user1 ."' ))";
		$exists = mysql_query($sql);
		$query_result = mysql_fetch_row($exists);

		if($query_result[0] == 0){
			$sql = "INSERT INTO sc_friends (u_username, u_friend) VALUES ('". $user1 ."', '". $user2 ."')";
		}

		if(!mysql_query($sql)){  //stampo un errore
			echo '<strong>Attenzione errore nella query:</strong> ' . $sql . "\n" . mysql_error() .'</div>';
		}
		else{
			echo '<div class="alert alert-success">
					<strong>Hai aggiunto amico con successo</strong>
				  </div>';
		}
		
		mysql_close($dbconn); 
	}	
}


// controllo se username e password corrispondono ad un utente
function login_user($username, $password) {
	
	$salt = retrieve_salt($username);
	$hpassword = hash_password($password . $salt);
	$sql = "SELECT EXISTS(SELECT * FROM sc_users WHERE u_username = '" . $username . "' AND u_password = '" . $hpassword . "')";

	$query = mysql_query($sql);
	$result = mysql_fetch_row($query);

	$r = ($result[0] == 1)? 1 : 0;

	return $r;
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
	$sql = "SELECT u_salt FROM sc_users WHERE u_username = '". $user . "' ";
	$query = mysql_query($sql);

	$salt = mysql_fetch_array($query);
	return $salt[0];
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

	return $ora;
}


/*
//stampo la lista degli utenti
function lista_utenti(){
	$risultato="";
	$dbconn=db_dbconnect();
	$sql="SELECT * FROM sc_users";
	$risposta = mysql_query($sql) or die("Errore nella query: " . $sql . "\n" . mysql_error());
	
	while ($riga = mysql_fetch_row($risposta)) {  //restituisce una riga della tabella sc_users altrimenti FALSE
	    $risultato[] = $riga;
	  	}
		mysql_close($dbconn);
	return $risultato;  //ritorno l'array risultato
}


//rimuovo un utente
function rimuovi_utente($user_id){
	$dbconn=db_dbconnect();
	$sql="DELETE FROM sc_users WHERE user_id = $user_id";
	$risposta=mysql_query($sql) or die("Errore nella query: " . $sql . "\n" . mysql_error());
    mysql_close($dbconn);
    header("Location: database_index.php");

}
*/




?>