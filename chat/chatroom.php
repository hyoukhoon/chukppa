<?php 
include $_SERVER["DOCUMENT_ROOT"]."/inc/header.php";
if(!$_SESSION['loginValue']['SEMAIL']){
	echo "<script>alert('로그인 하십시오');location.href='/member/login.php'</script>";
 	exit;
 }
$userid=$_SESSION['loginValue']['SEMAIL'];
$uname=$_SESSION['loginValue']['SUNAME'];
$channelid=$_GET['channelid'];
$photo=$_SESSION['loginValue']['PHOTO'];
//https://www.chukppa.com/chat/chatroom.php?channel=test1&uname=손흥민
?>
<!doctype html>
<html lang="kr">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Bootstrap demo</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
</head>

<style>
body{margin-top:20px;}

.chat-online {
    color: #34ce57
}

.chat-offline {
    color: #e4606d
}

.chat-messages {
    display: flex;
    flex-direction: column;
    max-height: 800px;
    overflow-y: scroll
}

.chat-message-left,
.chat-message-right {
    display: flex;
    flex-shrink: 0
}

.chat-message-left {
    margin-right: auto
}

.chat-message-right {
    flex-direction: row-reverse;
    margin-left: auto
}
.py-3 {
    padding-top: 1rem!important;
    padding-bottom: 1rem!important;
}
.px-4 {
    padding-right: 1.5rem!important;
    padding-left: 1.5rem!important;
}
.flex-grow-0 {
    flex-grow: 0!important;
}
.border-top {
    border-top: 1px solid #dee2e6!important;
}
</style>



<main class="content">
    <div class="container p-0">

		<h1 class="h3 mb-3">Messages</h1>


				<div class="col-12 col-lg-7 col-xl-9">

					<div class="position-relative">
						<div class="chat-messages p-4" id="chat">

						</div>
					</div>

					<div class="flex-grow-0 py-3 px-4 border-top">
						<div class="input-group">
							<input type="hidden" id="uname" value="<?php echo $uname;?>">
							<input type="text" class="form-control" placeholder="Type your message" id="message">
							<button class="btn btn-primary" id="send">Send</button>
						</div>
					</div>

				</div>

	</div>
</main>
<?php
include $_SERVER["DOCUMENT_ROOT"]."/inc/footer.php";
?>
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

//    const socket = new WebSocket('ws://chukppa.com:8080');
	const socket = new WebSocket('wss://www.chukppa.com/wss2/:8080');

	function subscribe(channel) {
		socket.send(JSON.stringify({command: "subscribe", channel: channel, userid:"<?php echo $userid;?>", uname:"<?php echo $uname;?>", ip:"<?php echo $_SERVER['REMOTE_ADDR']?>"}));
	}

	function sendMessage(uname,msg) {
		var channel = '<?php echo $channelid;?>';
		socket.send(JSON.stringify({command: "message", channel: channel, userid:"<?php echo $userid;?>", uname:"<?php echo $uname;?>", photo:"<?php echo $photo;?>", message: msg, ip:"<?php echo $_SERVER['REMOTE_ADDR']?>"}));
	}

	socket.onopen = function(e) {
	  console.log("Connection established!");
	  //var channel = getParameterByName('channel');
	  var channel = '<?php echo $channelid;?>';
	  subscribe(channel);
	};

    socket.onmessage = (event) => {//채팅내용을 받아서 뿌려줌
		const myname = document.getElementById('uname').value
	  var rmsg=JSON.parse(event.data);
	  //var li = document.createElement('li');
	  //li.innerHTML = rmsg.uname + ' : ' + rmsg.msg;
	  const addhtml = document.createElement("div");
	  if(myname!=rmsg.uname){
			addhtml.innerHTML='<div class="chat-message-left pb-4"><div><img src="'+rmsg.photo+'" class="rounded-circle mr-1" alt="'+rmsg.uname+'" width="40" height="40"><div class="text-muted small text-nowrap mt-2">'+rmsg.nowtime+'</div></div><div class="flex-shrink-1 bg-light rounded py-2 px-3 ml-3"><div class="font-weight-bold mb-1">'+rmsg.uname+'</div>'+rmsg.msg+'</div></div>';
	  }else{
			addhtml.innerHTML='<div class="chat-message-right pb-4"><div><img src="'+rmsg.photo+'" class="rounded-circle mr-1" alt="'+rmsg.uname+'" width="40" height="40"><div class="text-muted small text-nowrap mt-2">'+rmsg.nowtime+'</div></div><div class="flex-shrink-1 bg-light rounded py-2 px-3 ml-3">'+rmsg.msg+'</div></div>';
	  }
      chat.append(addhtml);
    };

    sendButton.addEventListener('click', () => {//채팅을 서버에 보냄
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