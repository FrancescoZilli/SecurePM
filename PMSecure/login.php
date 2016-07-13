<?php
	include('functions.php');
	session_start();

	if( isset($_POST["user"]) && isset($_POST["passwd"]) ){
		$username = $_POST['user'];
		$password = $_POST['passwd'];	
	}

	if( isset($_SESSION['nlog']) ) {
		$_SESSION['nlog']++;
	}

	login_user($username, $password);
?>

<!DOCTYPE html>
<html lang="en">

<head>
	<link href="./css/font-awesome.css" rel="stylesheet">
    <link href='./css/style.css' rel='stylesheet'>

	<title>Log in</title>
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

      <!-- Login form-->
      <div class="login">
        <h1>Secure PMs</h1>
        <form action="login.php" method="post">
            <input type="text" name="user" placeholder="Username" required="required" />
            <!-- <input type="date" name="bday" placeholder="Birthday" required="required" max="2000-01-02" onblur="dio" /> -->
            <input type="password" name="passwd" placeholder="Password" required="required" />
            <button type="submit" class="btn btn-primary btn-block btn-large">Let me in!</button>
        </form>
        <h4>Register <a href="./registration.html">here</a>!</h4>
      </div>
    </div>
    
    <script src="./js/jquery.js"></script>
    <script src="./js/index.js"></script>
</body>
</html>

