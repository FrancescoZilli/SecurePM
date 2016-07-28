<?php

  session_start();

  if( !isset($_SESSION['#login']) && !isset($_SESSION['username']) ) {
    $_SESSION['#login'] = 1;
    $_SESSION['username'] = "";
  }
  
?>

<!DOCTYPE html>
<html>
  <head>
    <link href='./css/style.css' rel='stylesheet'>

    <title>Secure Push Messaging</title>
  </head>
  <body>
  
    <!-- Login form-->
    <div class="login">
      <h1>Secure PMs</h1>
      <form action="login.php" method="post">
          <input type="text" name="user" placeholder="Username" required="required" />
          <!-- <input type="date" name="bday" placeholder="Birthday" required="required" max="2000-01-02" onblur="dio" /> -->
          <input type="password" name="passwd" placeholder="Password" required="required" />
          <button type="submit" class="btn btn-primary btn-block btn-large">Let me in!</button>
      </form>
      <h4>No account? Register <a href="./registration.html">here</a>!</h4>
    </div>
    
    <script src="./js/jquery.js"></script>
  </body>
</html>