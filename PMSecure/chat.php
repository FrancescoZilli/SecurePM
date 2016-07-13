<?php

  session_start();

  if( !isset($_SESSION['nlog']) ) {
    $_SESSION['nlog'] = 0;
  }
  
?>

<!DOCTYPE html>
<html>
  <head>
    <link href="./css/font-awesome.css" rel="stylesheet">
    <link href='./css/style.css' rel='stylesheet'>

    <title>Secure Push Messaging</title>
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
        <h1>Chat HERE</h1>
      </div>
    </div>
    
    <script src="./js/jquery.js"></script>
    <script src="./js/index.js"></script>
  </body>
</html>