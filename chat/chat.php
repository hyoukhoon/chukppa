<?php
$uname="거북이";
?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <title>WebSocket Chat</title>
  <style>
    /* Add your CSS styles here */
  </style>
</head>
<body>
  <div id="chat"></div>
  <input type="text" id="uname" value="<?php echo $uname;?>" placeholder="이름을 입력하세요.">
  <input type="text" id="message">
  <button id="send">Send</button>

  <script>
	function getParameterByName(name) {
		name = name.replace(/[\[]/, "\\[").replace(/[\]]/, "\\]");
		var regex = new RegExp("[\\?&]" + name + "=([^&#]*)"),
		results = regex.exec(location.search);
		return results === null ? "" : decodeURIComponent(results[1].replace(/\+/g, " "));
	}
    const chat = document.getElementById('chat');
	const uname = document.getElementById('uname');
    const messageInput = document.getElementById('message');
    const sendButton = document.getElementById('send');

    const socket = new WebSocket('ws://211.45.175.47:8080');

	function subscribe(channel) {
		socket.send(JSON.stringify({command: "subscribe", channel: channel, uname:'<?php echo $uname;?>'}));
	}

	function sendMessage(uname,msg) {
		socket.send(JSON.stringify({command: "message", uname:uname, message: msg}));
	}

	socket.onopen = function(e) {
	  console.log("Connection established!");
	  var channel = getParameterByName('channel');
	  subscribe(channel);
	};

    socket.onmessage = (event) => {
	  var rmsg=JSON.parse(event.data);
	  var li = document.createElement('li');
	  li.innerHTML = rmsg.uname + ' : ' + rmsg.msg;
      chat.appendChild(li);
    };

    sendButton.addEventListener('click', () => {
		const uname = document.getElementById('uname').value?document.getElementById('uname').value:'익명';
		const message = messageInput.value;
		console.log({
                        uname : uname,
						message : message
                    });
      sendMessage(uname,message);
      messageInput.value = '';
    });
  </script>
</body>
</html>
