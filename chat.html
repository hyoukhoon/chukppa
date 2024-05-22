<!DOCTYPE>
<html>
<head>
<style>
        #container {
            width: 400px;
     border: 1px dotted #000;
     padding: 10px;
     height: 328px;
        }

        #chatBox {

            border: 1px solid #000;

     width: 400px;

     height: 300px;

            margin-bottom: 5px;

        }

        #chat li {

            padding: 5px 0px;

        }

        #name {

     width: 78px;

        }

        #msg {

     width: 256px;

        }



    </style>

<script src="https://cdnjs.cloudflare.com/ajax/libs/socket.io/4.7.2/socket.io.min.js"></script>

<script type="text/javascript">

function getParameterByName(name) {
	name = name.replace(/[\[]/, "\\[").replace(/[\]]/, "\\]");
	var regex = new RegExp("[\\?&]" + name + "=([^&#]*)"),
	results = regex.exec(location.search);
	return results === null ? "" : decodeURIComponent(results[1].replace(/\+/g, " "));
}


window.onload = function(){
var socket = io.connect();
var room = getParameterByName('room');
	if(socket != null && socket != undefined){

		socket.on('connect', function() {
			console.log('connect');
			/* 이름을 입력받고 */
			var name = prompt('Insert you name.', '');

			/* 이름이 빈칸인 경우 */
			if(!name) {
				name = '익명';
			}

			/* 서버에 새로운 유저가 왔다고 알림 */
			socket.emit('newUser', {
                        name : name,
						room : room
                    });
		});


                var welcome = document.createElement('li');
                welcome.innerHTML = '<system> Start Chatting';
                document.getElementById('chat').appendChild(welcome);
                socket.on('rMsg', function(data){
                    var li = document.createElement('li');
                    li.innerHTML = data.name + ' : ' + data.msg;
                    document.getElementById('chat').appendChild(li);
                });

                document.getElementById('submit').onclick = function(){
                    var val = document.getElementById('msg').value;
                    var name = document.getElementById('name').value;
                    socket.emit('sMsg', {
                        name : name,
						msg : val,
						room : room
                    });
                    document.getElementById('msg').value = '';
                };

            }


};

    </script>

</head>

<body>

<div id="container">
    <div id="chatBox">
        <ul id="chat"></ul>
    </div>
    <input type="text" id="name"/>
    <input type="text" id="msg"/>
    <button id="submit">Chat</button>

</div>

</body>

</html>