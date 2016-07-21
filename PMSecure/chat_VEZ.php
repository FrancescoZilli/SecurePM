<?php
  include('functions.php');

  session_start();

  if( isset($_SESSION['username']) ) {
    $user = $_SESSION['username'];
    //echo $user;
  }  

  
?>

<!DOCTYPE html>
<html>
  <head>
    <link href='./css/style.css' rel='stylesheet'>
    <link href='./css/chat.css' rel='stylesheet'>

    <title>Secure Push Messaging</title>
  </head>

  <body>

  <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
  <script language="javascript">

    // We'll run the AJAX query when the page loads.
    window.onload = askForFriends;

    function askForFriends() {
      $.ajax({
        type:    "POST",
        url:     "./db_friends.php",
        dataType: "json",
        cache: false,
        success: function(json) {
          var list = document.getElementById('friendlist');     // VERIFY ARRAY LENGTH TO SEE IF SOMEONE HAS NO FRIENDS

          for( var i=0; i<json.length; i++ ) {
            var item = document.createElement('li');
            item.appendChild( document.createTextNode(json[i]) );
            console.log(item);
            list.appendChild(item);
          }
        },
        error: function(jqXHR, textStatus, errorThrown) {
              alert("Error, status = " + textStatus + ", " + "error thrown: " + errorThrown);
        }
      });
      console.log(document.getElementById('friendlist'));
    }
  </script>

  <div id="sidebar">

    <div id="user"> <?php echo $user ?> </div>

    <div>Friends</div>
    <ul class="friends" id="friendlist"></ul>

  </div>

  <div id="primary">
    <div id= "topbar">
      <div id="top_name"> ASDRUBALE </div>
    </div>

    <div id="log">

        <span class="long-content"></span>

    </div>

    <div id="composer">
        <textarea> </textarea>
        <button>Send</button>
    </div>
    
    <script src="./js/jquery.js"></script>

  </body>
</html>