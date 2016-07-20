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
        data:    {"name":"ciao"},
        success: function(data) {
              alert('call back');
        },
        // vvv---- This is the new bit
        error:   function(jqXHR, textStatus, errorThrown) {
              alert("Error, status = " + textStatus + ", " +
                    "error thrown: " + errorThrown
              );
        }
      });
    }

/*
    function askForFriends() {
      console.log("dio");
        $.post(
        "./db_friends.php", // The server URL 
        {name: 'culo'},
        createFriendsList // The function to call on completion.
        );
    }
*/

    function createFriendsList(json) {
      console.log("gotit");
      var list = document.getElementById('friendlist');

      for( var i=1; i<json.length; i++ ) {
        var item = document.createElement('li');
        item.appendChild( document.createTextNode(json[i]) );
        list.appendChild(item);
      }
    }

    function show(json) {
      console.log(json);
        alert(json);
    }

  </script>

  <div id="sidebar">
    <div>Online</div>
      <ul class="friends" id="friendlist">
        
      </ul>
  </div>

  <div id="primary">
    <div id= "topbar">
      <div id="top_name">ASDRUBALE</div>
    </div>

    <div id="log">

        <span class="long-content">
          
        </span>

    </div>

    <div id="composer">
        <textarea>
          
        </textarea>

        <button>Send</button>
    </div>
    
    <script src="./js/jquery.js"></script>
    <script src="./js/index.js"></script>
  </body>
</html>