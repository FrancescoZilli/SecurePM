<?php
	include('functions.php');
	session_start();

  if( !isset($login_correct) ) {
    $login_correct = 0;
  }

	if( isset($_POST["user"]) && isset($_POST["passwd"]) ){
		$username = $_POST['user'];
		$password = $_POST['passwd'];	

    if( $_COOKIE['ego'] == $username )  // make sure right person il logging out
      $login_correct = login_user($username, $password);

    if( $login_correct == 1 ) {
      $friend = $_COOKIE['friend'];
      clear_message($username, $friend);
    }
	}


	if( isset($_SESSION['#logout']) ) {
		$_SESSION['#logout']++;
	} else {
    $_SESSION['#logout'] = 1;
  }
  
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <link href='./css/style.css' rel='stylesheet'>

	<title>Log out</title>
</head>

<body>
  <!-- <input type="date" name="bday" placeholder="Birthday" required="required" max="2000-01-02" onblur="dio" /> -->

  <!-- New login form -->
  <div class="login">
    <?php 
      if( $login_correct == 0 ) {
        echo '<h1>One more thing...</h1>';
        echo '<form action="logout.php" method="post">';
        echo '<input type="text" name="user" placeholder="Username" required="required" />';
        echo '<input type="password" name="passwd" placeholder="Password" required="required" />';
        if( $_SESSION['#logout'] > 3 ) {
          echo '<img src="http://www.captcha.net/images/recaptcha-example.gif" />';
        }
        echo'<button type="submit" class="btn btn-primary btn-block btn-large">Let me in!</button> </form>';
      } else {
        echo '<h1>See you soon!';
        echo '<h4>Redirecting to main page...</h4>';
        $_SESSION['#logout'] = 0; //ripristino contatore
        session_destroy();
        header( "refresh:3;url=index.php" );
      }
    ?>
  </div>

  <script src="./js/jquery.js"></script>

  <script type="text/javascript">
      function getCaptchaValue() {
        var googleResponse = jQuery('#g-recaptcha-response').val();

        // expire-time of cookie
        var d = new Date();
        var sec = 10; 
        d.setTime(d.getTime() + (sec*1000)); // expires in seconds
        var expires = "expires="+ d.toUTCString();

        // captcha response creates a cookie
        if (!googleResponse) {
            document.cookie = "captcha=n;" + expires;
        } else {
            document.cookie = "captcha=y;" + expires;
        }
      }
  </script>

</body>
</html>

