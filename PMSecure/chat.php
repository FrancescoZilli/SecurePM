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
    var destinatario = "";

    function askForFriends() {
      document.getElementById("composer").style.visibility = "hidden";
      $.ajax({
        type:    "POST",
        url:     "./db_friends.php",
        dataType: "json",
        cache: false,
        success: function(json) {
          console.log("gotit");    // VERIFY ARRAY LENGTH TO SEE IF SOMEONE HAS NO FRIENDS

          for( var i=0; i<json.length; i++ ) {
            var item = '<li onclick = "selectFriend('+ json[i]+ ')"> ' + json[i] + '</li>';
            console.log(item);
            document.getElementById('friendlist').innerHTML += item;
          }
          
        },
        error: function(jqXHR, textStatus, errorThrown) {
              alert("Error, status = " + textStatus + ", " + "error thrown: " + errorThrown);
        }
      });
    }

    function sendContent(dest){
      var message = document.getElementById("userText").value;
      $.ajax({
        url: './db_send.php',
        type: 'POST',
        dataType: 'text',
        data: {msg: message, to: destinatario},
        cache: false,
        success: function(text){

          var buffer = text.substring(1, text.length-1).split("|||");
          document.getElementById('log').innerHTML += '<span class="comment">'+ buffer[0] + " - " + buffer[1] + " : " + buffer[2] +'</span>';
        },
        error: function(jqXHR, textStatus, errorThrown) {
              alert("Error, status = " + textStatus + ", " + "error thrown: " + errorThrown);
        }
      });
    }

    function selectFriend(user){
      destinatario = user;
      document.getElementById("composer").style.visibility = "visible";
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

    </div>

    <div id="composer">
        <textarea id="userText"> </textarea>
        <button>Send</button>
    </div>
    
    <script src="./js/jquery.js"></script>

  </body>
</html>