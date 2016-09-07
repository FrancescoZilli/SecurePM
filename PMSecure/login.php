<?php
	include('functions.php');
    error_reporting(0);
	session_start();

    //porzione di codice che recupera le informazioni in input del form di login 

	if( isset($_POST["user"]) && isset($_POST["passwd"]) && isset($_POST["bday"]) ){
		$username = $_POST['user'];
		$password = $_POST['passwd'];
        $birthday = $_POST["bday"];
        //ego memorizza il nome in input, usato per controlli successivi
        setcookie('ego', $username, time()+(60*60*24)); // dura un giorno
	}

    //controllo del numero di tentativi di accesso 
	if( isset($_SESSION['#login']) ) {
        
		$_SESSION['#login']++;
        $_SESSION['username'] = $username;
        
        $login_correct = login_user($username, $password, $birthday);
        
        if( $_SESSION['#login'] > 3 && $login_correct == 1 && $_COOKIE['captcha'] == "n") {
            $login_correct = 0;
        }

	} else {
        $_SESSION['#login'] = 0;
    }

	
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <link href='./css/style.css' rel='stylesheet'>

    <script type="text/javascript">
      var onloadCallback = function() {
        grecaptcha.render('reCAPTCHA', {
          'sitekey' : '6LeIxAcTAAAAAJcZVRqyHh71UMIEGNQ_MXjiZKhI',
          'theme' : 'dark',
        });
      };
    </script>

	<title>Log in</title>
</head>

<body>
    <!-- GOOGLE'S reCAPTCHA -->
    <script src="https://www.google.com/recaptcha/api.js?onload=onloadCallback&render=explicit"
        async defer>
    </script>

  <!-- Login form-->
  <div class="login">
    <h1>Secure PMs</h1>
    <form action="login.php" method="post">
        <input type="text" name="user" placeholder="Username" required="required" />
        <input type="date" name="bday" placeholder="Birthday" required="required" max="2000-01-02"  /> 
        <input type="password" name="passwd" placeholder="Password" required="required" />
        <?php
        	if( $_SESSION['#login'] > 3 && $login_correct == 0) {
                echo '<div id="reCAPTCHA"></div>';
        	}
        ?>
        <button type="submit" class="btn btn-primary btn-block btn-large" onclick="getCaptchaValue()">Let me in!</button>
    </form>

    <?php
        if( isset($login_correct) ) {
        	if( $login_correct == 0 ) {
        		echo '<h4>Invalid LOGIN. Attempt #' . $_SESSION['#login'] . '</h4>';
        	} else {
            	echo '<h4>Hold on! Redirecting to chat...</h4>';
            	unset($_SESSION['#login']); // distruggo #login, non serve più
                unset($_COOKIE['captcha']);
                header( "refresh:0;url=chat.php" ); // redirect to chat
        	}
        }
    ?>

    <h4>Register <a href="./registration.html">here</a>!</h4>
  </div>
    
  <script src="./js/jquery.js"></script>

  <script type="text/javascript">
  //funzione che restituisce il valore del captcha e lo memorizza in un cookie 
      function getCaptchaValue() {
        var googleResponse = $('#g-recaptcha-response').val();

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

