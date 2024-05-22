var http = require('http');
var fs = require('fs');
var socketio = require('socket.io');

const elasticsearch = require("elasticsearch");
const client = new elasticsearch.Client({
  hosts: ["http://elastic:soon06051007@localhost:9200"]
});

async function msgwrite(room, uname, msg) {
	const time = Date.now();
	var eid=time+Math.floor(Math.random() * 1000000);
	  try {
		const rs =  await client.create({
			index: 'chat',
			id: eid,
			body: {"room": room, "uname":uname, "msg":msg}
		});
		console.log(rs);
	  } catch (err) {
		console.error(err);
	  }
}

async function room_cnt(roomid) {
  try {
    const rs =  await client.search({
      index: 'chat',
      body: {
		  "query": {
			"query_string": {
			  "query": "room:'"+roomid+"' and msg:'roomcnt'"
			}
		  }
		}
    });
    return rs;
  } catch (err) {
    console.error(err);
  }
}

var server = http.createServer(function(req, res){
        fs.readFile('chat.php', 'utf8', function(err, data){
        res.writeHead(200, {'Content-Type':'text/html'});
        res.end(data);
    });
}).listen(8888, function(){
    console.log('Running ~~~~~~~~~~~~');
});

var io = socketio(server);
var chat=io.sockets.on('connection', function(socket){

	 /* 새로운 유저가 접속했을 경우 다른 소켓에게도 알려줌 */
    socket.on('newUser', function(data) {
        console.log(data.name + ' 님이 접속하였습니다.');
		var name = data.name;
		var room = data.room;
		/* 소켓에 이름 저장해두기 */
        socket.name = name;
		socket.room = room;
		socket.join(room);
		/* 모든 소켓에게 전송 */
        chat.to(room).emit('rMsg', {type: 'connect', name: 'SERVER', msg: name + '님이 접속하였습니다.'})
    });


	socket.on('sMsg', function(data){
		console.log(data);
		var room = data.room;
		msgwrite(room, data.name, data.msg);
		socket.join(room);
		chat.to(room).emit('rMsg', data);
    });

	socket.on('disconnect', function () {
		console.log(socket.name + ' 님이 대화방을 나가셨습니다.');
		socket.join(socket.room);
		chat.to(socket.room).emit('rMsg', {type: 'disconnect', name: 'SERVER', msg: socket.name + '님이 대화방을 나가셨습니다.'})
    })
});




//io.sockets.in(room_id).emit('msgAlert',data);//자신포함 전체 룸안의 유저
//socket.broadcast.to(room_id).emit('msgAlert',data); //자신 제외 룸안의 유저
//socket.in(room_id).emit('msgAlert',data); //broadcast 동일하게 가능 자신 제외 룸안의 유저
//io.of('namespace').in(room_id).emit('msgAlert', data) //of 지정된 name space의 유저의 룸
