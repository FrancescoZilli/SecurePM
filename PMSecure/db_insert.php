<?php
include('functions.php');

	$name = $_POST['name'];
	$surname = $_POST['surname'];
	$bday = $_POST['bday'];
	$address = $_POST['address'];
	$username = $_POST['username'];
	$password = $_POST['password'];


?>

<html>
  <head>
    <link href='./css/style.css' rel='stylesheet'>

  </head>
  <body>


		<?php
			if($username != "" && $password != "") {    
        $dbconn = db_connect();
				$ins_result = insert_user($name, $surname, $bday, $address, $username, $password);
        mysql_close($dbconn);
			}
			else {
				echo "Completare tutti i campi prima di confermare il form";
				header( "refresh:3;url=registration.html" );
			}

      echo '<h1>' . $ins_result[1] . '</h1>';
		?>
    
    <script src="./js/jquery.js"></script>
  </body>
</html>