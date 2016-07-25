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
    var friendlist = []; //create an array of friends to avoid double-listing them

    // LOAD YOUR FRIENDS LIST
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
            if( !friendlist.includes(json[i]) )
              friendlist.push(json[i]);
          }

          displayFriends();
          
        },
        error: function(jqXHR, textStatus, errorThrown) {
              alert("Error, status = " + textStatus + ", " + "error thrown: " + errorThrown);
        }
      });
    }

    function displayFriends() {      
      document.getElementById('friendlist').innerHTML = "";
      document.getElementById('friend_to_add').value = "";
      friendlist.sort();
      for( var i=0; i<friendlist.length; i++ ) {
        var item = '<li onclick = "selectFriend(' + "'" + friendlist[i] + "'" + ')"> ' + friendlist[i] + '</li>';
        document.getElementById('friendlist').innerHTML += item;
      }

    }


    // SEND MESSAGE TO SERVER (will redirect to your friend)
    function sendContent(dest){
      var message = document.getElementById("userText").value;
      console.log(message);
      document.getElementById("userText").value = "";
      $.ajax({
        url: './db_send.php',
        type: 'POST',
        dataType: 'text',
        data: {msg: message, to: destinatario}, //DEST O DESTINATARIO?! ASSOCIAZIONE DA ESEGUIRE
        cache: false,
        success: function(text){
          var buffer = parseMessage(text);
          document.getElementById('log').innerHTML += '<span class="comment">'+ buffer[0] + " - " + buffer[1] + " : " + buffer[2] +'</span><br>';
        },
        error: function(jqXHR, textStatus, errorThrown) {
              alert("Error, status = " + textStatus + ", " + "error thrown: " + errorThrown);
        }
      });
    }


    // SELECT FRIEND YOU WANT TO CHAT WITH
    function selectFriend(user){
      destinatario = user;
      document.getElementById("composer").style.visibility = "visible";
      document.getElementById("top_name").innerHTML = destinatario;
      document.getElementById("log").innerHTML = "";
      //select here previous conversations with user
      loadConversation();
    }


    // ADD USER TO YOUR FRIEND LIST
    function addFriend() {
      var friendtoadd = document.getElementById("friend_to_add").value;

      if(friendtoadd != "") {

        $.ajax({
          url: './db_addfriend.php',
          type: 'POST',
          dataType: 'text',
          data: {friend: friendtoadd},
          cache: false,
          success: function(text){
            alert(text);
            askForFriends();    // Needs a FIX!!!! --> friendlist array
          },
          error: function(jqXHR, textStatus, errorThrown) {
                alert("Error, status = " + textStatus + ", " + "error thrown: " + errorThrown);
          }
        });

      }

    }


    // LOAD THE CONVERSATION YOU HAD WITH YOUR FRIEND
    function loadConversation() {
      $.ajax({
          url: './db_retrievelogs.php',
          type: 'POST',
          dataType: 'text',
          data: {friend: destinatario}, //must be set and should be
          cache: false,
          success: function(text){
            console.log(text);
            var buffer = parseMessage(text);
            document.getElementById('log').innerHTML += '<span class="comment">'+ buffer[0] + " - " + buffer[1] + " : " + buffer[2] +'</span><br>';
          },
          error: function(jqXHR, textStatus, errorThrown) {
                alert("Error, status = " + textStatus + ", " + "error thrown: " + errorThrown);
          }
        });
    }


    // SUBFUNCTION: parse exchanged messages with server
    function parseMessage(text) {
      var buf = text.substring(1, text.length-1).split("|||");
      return buf;
    }


    // WILL BE DEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEELEEEEEEEEEEEEEEEETED
    function prova() {      
      alert("value");
    }

  </script>

  <div id="sidebar">

    <div id="user"> <?php echo $user ?> </div>

    <div>
      <h4>Friends</h4>
    </div>
    <ul class="friends" id="friendlist"></ul>

    <div id="add_friend">
      <input type="text"  value="" id="friend_to_add"/>
      <input type="submit" value="add friend" onclick="addFriend()" /> 
    </div>

  </div>

  <div id="primary">
    <div id= "topbar">
      <div id="top_name"></div>
    </div>

    <div id="log">

    </div>

    <div id="composer">
        <textarea id="userText"> </textarea>
        <button onclick="sendContent(destinatario)">Send</button>
    </div>
    
    <script src="./js/jquery.js"></script>

  </body>
</html>