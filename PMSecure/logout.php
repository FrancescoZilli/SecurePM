<?php
	include('functions.php');
	session_start();

	if( isset($_POST["user"]) && isset($_POST["passwd"]) ){
		$username = $_POST['user'];
		$password = $_POST['passwd'];	

    $dbconn = db_connect();
    $login_correct = login_user($username, $password);
    mysql_close($dbconn); 
	}


	if( isset($_SESSION['nlog']) ) {
		$_SESSION['nlog']++;
	} else {
    $_SESSION['nlog'] = 0;
  }

  if( !isset($login_correct) ) {
    $login_correct = 0;
  }
  
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <link href='./css/style.css' rel='stylesheet'>

	<title>Log out</title>
</head>

<body>

  <!-- Login form-->
  <div class="login">
    <h1>Hope to see you soon...</h1>
    <form action="logout.php" method="post">
        <input type="text" name="user" placeholder="Username" required="required" />
        <!-- <input type="date" name="bday" placeholder="Birthday" required="required" max="2000-01-02" onblur="dio" /> -->
        <input type="password" name="passwd" placeholder="Password" required="required" />
        <?php
        	if( $_SESSION['nlog'] > 3 && $login_correct == 0) {
        		echo '<img src="http://www.captcha.net/images/recaptcha-example.gif" />';
        	}
        ?>
        <button type="submit" class="btn btn-primary btn-block btn-large">Let me in!</button>
    </form>
    <?php
    	if( $login_correct == 0) {
    		echo '<h4>Invalid login. Attempt #' . $_SESSION['nlog'] . '</h4>';
    	} else {
    		echo '<h4>Hold on! Redirecting to main page...</h4>';
    		$_SESSION['nlog'] = 0; //ripristino contatore
        session_destroy();
        header( "refresh:3;url=index.php" );
    	}
    ?>
  </div>
    
  <script src="./js/jquery.js"></script>

</body>
</html>

