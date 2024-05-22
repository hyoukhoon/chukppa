const elasticsearch = require("elasticsearch");
const client = new elasticsearch.Client({
  //프로토콜이 https이고 elasticsearch에 id, password가 있다면
  hosts: ["http://elastic:soon06051007@localhost:9200"]
});

async function test() {
	const time = Date.now();
	var eid=time+Math.floor(Math.random() * 1000000);
  try {
    const rs =  await client.create({
		index: 'chat',
		id: eid,
		body: {"room": "room1", "uname":"testmane", "msg":eid+"test hahaha"}
    });
    console.log(rs);
  } catch (err) {
    console.error(err);
  }
}
test();