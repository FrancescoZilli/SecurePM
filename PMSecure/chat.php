<?php
  include('functions.php');

  session_start();

  if( isset($_SESSION['username']) ) {
    $user = $_SESSION['username'];
  }  

  if( !isset($_SESSION['username']) ||  $_SESSION['username'] == "" ) {
    echo "<div class='sessionerror'> INVALID SESSION </div>";
  }

  
?>

<!DOCTYPE html>
<html>
  <head>
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

    // Event Listener for the Enter key
    $(document).ready(function(){
        $('#userText').keypress(function(e){
          if(e.keyCode==13)
            $('#send').click();
        });
    });
    

    // LOAD YOUR FRIENDS LIST
    function askForFriends() {
      document.getElementById("composer").style.visibility = "hidden";
      $.ajax({
        type:    "POST",
        url:     "./db_friends.php",
        dataType: "json",
        cache: false,
        success: function(json) {

          for( var i=0; i<json.length; i++ ) {
            if( !friendlist.includes(json[i]) )
              friendlist.push(json[i]);
          }

          displayFriends();

          // get last friend you have chatted with or empty chat
          var last_friend = getLastFriend();
          if( last_friend != "" ) {            
            selectFriend(last_friend);
          } else {
            $('#topbar').css('height', '0%');
            $('#composer').css('height', '0%');
            $('#log').css('height', '100%');
          }          
        },
        error: function(jqXHR, textStatus, errorThrown) {
          alert("Error, status = " + textStatus + ", " + "error thrown: " + errorThrown);
        }
      });
    }


    // SEND MESSAGE TO SERVER (will redirect to your friend)
    function sendContent(){
      var message = document.getElementById("userText").value;
      document.getElementById("userText").value = "";

      //parse message before sending
      message = parseInput(message);
      
      if( message != "" && message != " " ) {
        $.ajax({
          url: './db_send.php',
          type: 'POST',
          dataType: 'text',
          data: {msg: message, to: destinatario}, 
          cache: false,
          success: function(text){
            var buffer = parseMessage(text);
            console.log("SENT: " + buffer);
            var textarea = document.getElementById('log');            
            textarea.innerHTML += createBubble(buffer, "bubble-right");
            textarea.scrollTop = textarea.scrollHeight; 
          },
          error: function(jqXHR, textStatus, errorThrown) {
                alert("Error, status = " + textStatus + ", " + "error thrown: " + errorThrown);
          }
        });
      }

    }



    // SELECT FRIEND YOU WANT TO CHAT WITH
    function selectFriend(user){

      destinatario = user;
      document.getElementById("composer").style.visibility = "visible";
      $('#topbar').css('height', '5%');
      $('#composer').css('height', '10%');
      $('#log').css('height', '85%');
      document.getElementById("userText").value = "";
      document.getElementById("topbar").innerHTML = destinatario;
      document.getElementById("log").innerHTML = "";
      
      
      document.cookie = "friend=" + destinatario;
      loadConversation(destinatario); //select here previous conversations with user
      openConnection();

    }


    // OPEN A CONNECTION TO CHECK IF NEW MESSAGES ARRIVE
    function openConnection() {
      if(typeof(EventSource) !== "undefined") {
        var source = new EventSource("_server.php");
        source.onmessage = function(event) {
            console.log("GOT: " + event.data);
            document.cookie = "last_sent=" + event.data;  //ultimo messaggio caricato
            var buffer = parseMessage(event.data);
            document.cookie = "last_time=" + parseTime(buffer[1]);  //ora dell'ultimo messaggio caricato
            var textarea = document.getElementById('log');
            textarea.innerHTML += createBubble(buffer, "bubble-left");
            textarea.scrollTop = textarea.scrollHeight;
        };
      } else {
          document.getElementById("log").innerHTML = "Sorry, your browser does not support server-sent events...";
      }          
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
            askForFriends();    
          },
          error: function(jqXHR, textStatus, errorThrown) {
                alert("Error, status = " + textStatus + ", " + "error thrown: " + errorThrown);
          }
        });

      }

    }


    // LOAD THE CONVERSATION YOU HAD WITH YOUR FRIEND
    function loadConversation(user) {
      $.ajax({
          url: './db_retrievelogs.php',
          type: 'POST',
          dataType: 'text',
          data: {friend: user}, //must be set and should be
          cache: false,
          success: function(text){
            var msg_lst = parseMessageList(text);
            for(var i=0; i<msg_lst.length-1; i++) { //-1 since last one will be an empty string
              var buffer = parseMessage(msg_lst[i]);

              if( buffer[0] != user)
                document.getElementById('log').innerHTML += createBubble(buffer, "bubble-right");
              else
                document.getElementById('log').innerHTML += createBubble(buffer, "bubble-left");
            }
            var textarea = document.getElementById('log');
            textarea.scrollTop = textarea.scrollHeight; 
            $('#log').css('opacity', '1');      
          },
          error: function(jqXHR, textStatus, errorThrown) {
                alert("Error, status = " + textStatus + ", " + "error thrown: " + errorThrown);
          }
        });
    }


    //---------------------SUBFUNCTIONS-----------------------------------------------------------------------------------------------------------------
    // SUBFUNCTION: parse exchanged messages with server
    function parseMessage(text) {
      var buf = text.substring(1, text.length-1).split("|||");
      return buf;
    }

    function parseMessageList(text) {
      var lst = text.split("[|||]");
      return lst;
    }

    function parseTime(date) {
      var tmp = date.split(" ");
      var time = tmp[1];
      return time;
    }


    //SUBFUNCTION: display array of friends in a list of <li>    
    function displayFriends() {      
      document.getElementById('friendlist').innerHTML = "";
      document.getElementById('friend_to_add').value = "";
      friendlist.sort();
      for( var i=0; i<friendlist.length; i++ ) {
        var item = '<li class="friend-li" onclick = "selectFriend(' + "'" + friendlist[i] + "'" + ')"> ' + friendlist[i] + '</li>';
        document.getElementById('friendlist').innerHTML += item;
      }

    }


    // SUBFUNCTION: get, if existing, the friend cookie
    function getLastFriend() {
      var friend = "";  //returns null if cookie friend is not set
      var list = document.cookie.split("; ");
      for(var i=0; i<list.length; i++) {
        if( list[i].includes("friend") )
          friend = list[i].substring(7);
      }

      return friend;
    }


    // SUBFUNCTION: parse input text before sending to the server
    function parseInput(text) {
      text = text.replace(/(?:\r\n|\r|\n)/g, ' '); //replace newlines with spaces
      text = text.replace(/</g, '&lt;');
      text = text.replace(/>/g, '&gt;');

      return text;
    }


    // create a message bubble
    function createBubble(buf, cls) {
      var tmp = buf[1].split(" ");
      var day = tmp[0].substring(0,5);
      var hour = tmp[1].substring(0,5);
      var time = day + " " + hour;

      var date = '<div class="bubble-small">' + time + '</div>';
      var final = '<div class="' + cls + '">' + buf[2] + '<br>' + date + '</div>';
      
      return final;
    }

  </script>

  

  <div id="sidebar">

    <div id="user"> <?php echo $user ?> </div>
    <a href="./logout.php"><div id="logout">log out m8</div></a>
    <br>
    <p class="title">friend-list</p>
    <ul class="friends" id="friendlist"></ul>
    <br>
    <div id="add_friend">
      <input type="text"  value="" id="friend_to_add"/>
      <div id="add_button" onclick="addFriend()">add friend</div>
    </div>


  </div>

  <div id="primary">
  
    <div id= "topbar"></div>

    <div id="log"></div>

    <div id="composer">
        <textarea id="userText"></textarea>
        <button id="send" onclick="sendContent()">Send</button>
    </div>
    
    <script src="./js/jquery.js"></script>

  </body>
</html>