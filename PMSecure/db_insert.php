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
    <link href="./css/font-awesome.css" rel="stylesheet">
    <link href='./css/style.css' rel='stylesheet'>

  </head>
  <body>

    <!-- Menu -->
    <div class="menu">
      
      <!-- Menu icon -->
      <div class="icon-close">
        <img src="./images/close.png">
      </div>
      
      <ul>
        <li><a href="#">About</a></li>
        <li><a href="#">Help</a></li>
        <li><a href="#">Contact</a></li>
      </ul>

    </div>

    <!-- Main body -->
    <div class="jumbotron">

    	<div class="icon-menu">
			<i class="fa fa-bars"></i>
			Menu
	    </div>

		<?php
			if($username != "" && $password != "") {
				insert_user($name, $surname, $bday, $address, $username, $password);
        echo '<h1>'. check_user($username) . '</h1>';
			}
			else {
				echo "something wrong...try again";
				header( "refresh:3;url=registration.html" );
			}
		?>
    
    <script src="./js/jquery.js"></script>
    <script src="./js/index.js"></script>
  </body>
</html>