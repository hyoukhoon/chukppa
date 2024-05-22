<?php 
namespace MyApp;
use Ratchet\MessageComponentInterface;
use Ratchet\ConnectionInterface;

class Chat implements MessageComponentInterface {
    protected $clients;

    public function __construct() {
        $this->clients = new \SplObjectStorage;
		$this->subscriptions = [];
        $this->users = [];
		$this->usernames = [];
		$this->channelcnt = [];
    }

    public function onOpen(ConnectionInterface $conn)
    {
        $this->clients->attach($conn);
        $this->users[$conn->resourceId] = $conn;
		echo "New connection! ({$conn->resourceId})\n";
    }

	public function onMessage(ConnectionInterface $conn, $msg)
    {
		$numRecv = count($this->clients) - 1;
        echo sprintf('Connection %d sending message "%s" to %d other connection%s' . "\n"
            , $conn->resourceId, $msg, $numRecv, $numRecv == 1 ? '' : 's');

        $data = json_decode($msg);
		$nowtime = date("Y-m-d H:i:s");
        switch ($data->command) {
            case "subscribe":
                $this->subscriptions[$conn->resourceId] = $data->channel;
				$this->usernames[$conn->resourceId] = $data->uname;
				$this->channelcnt = array_count_values($this->subscriptions);
				$target = $this->subscriptions[$conn->resourceId];
				$chatdata = '{"room": "'.$data->channel.'","userid": "'.$data->userid.'","uname": "'.$data->uname.'","msg": "IN","regdate": "'.date("Y-m-d H:i:s").'","ip": "'.$data->ip.'"}';
//				echo sprintf($chatdata);
				$rs=$this->savechat($chatdata);
				foreach ($this->subscriptions as $id=>$channel) {
                        if ($channel == $target && $id != $conn->resourceId) {
							$sendmessage='{"nowtime" : "'.$nowtime.'","uname" : "SYSTEM","photo":"/img/system.png","msg" : "'.$data->uname.'님이 대화방에 들어오셨습니다. 현재 인원은 '.$this->channelcnt[$data->channel].'명 입니다."}';
                            $this->users[$id]->send($sendmessage);
                        }
						if ($channel == $target && $id == $conn->resourceId) {
							$sendmessage='{"nowtime" : "'.$nowtime.'","uname" : "SYSTEM","photo":"/img/system.png","msg" : "'.$channel.' 대화방에 입장하셨습니다. 현재 인원은 '.$this->channelcnt[$data->channel].'명 입니다."}';
                            $this->users[$id]->send($sendmessage);
                        }
                    }
                break;
            case "message":
				$sendmessage='{"nowtime" : "'.$nowtime.'","uname" : "'.$data->uname.'","photo":"'.$data->photo.'","msg" : "'.$data->message.'"}';
				$chatdata = '{"room": "'.$data->channel.'","userid": "'.$data->userid.'","uname": "'.$data->uname.'","msg": "'.$data->message.'","regdate": "'.date("Y-m-d H:i:s").'","ip": "'.$data->ip.'"}';
				$rs=$this->savechat($chatdata);
                if (isset($this->subscriptions[$conn->resourceId])) {
                    $target = $this->subscriptions[$conn->resourceId];
                    foreach ($this->subscriptions as $id=>$channel) {
						if ($channel == $target) {//같은 채널에 모두에게 보냄
                            $this->users[$id]->send($sendmessage);
                        }
                    }
                }
        }
    }

	public function onClose(ConnectionInterface $conn)
    {
		$nowtime = date("Y-m-d H:i:s");
		$chatdata = '{"room": "'.$this->subscriptions[$conn->resourceId].'","userid": "'.$data->userid.'","uname": "'.$this->usernames[$conn->resourceId].'","msg": "OUT","regdate": "'.date("Y-m-d H:i:s").'"}';
		$rs=$this->savechat($chatdata);
		if (isset($this->subscriptions[$conn->resourceId])) {
                    $target = $this->subscriptions[$conn->resourceId];
                    foreach ($this->subscriptions as $id=>$channel) {
                        if ($channel == $target && $id != $conn->resourceId) { //나 말고 다른 상대에게만 보냄
							$sendmessage='{"nowtime" : "'.$nowtime.'","uname" : "SYSTEM","photo":"/img/system.png","msg" : "'.$this->usernames[$conn->resourceId].'님이 대화방에서 나가셨습니다. 현재 인원은 '.($this->channelcnt[$target]-1).'명 입니다."}';
                            $this->users[$id]->send($sendmessage);
                        }
                    }
         }

        $this->clients->detach($conn);
        unset($this->users[$conn->resourceId]);
        unset($this->subscriptions[$conn->resourceId]);
		echo "Connection {$conn->resourceId} has disconnected\n";
    }

    public function onError(ConnectionInterface $conn, \Exception $e)
    {
        echo "An error has occurred: {$e->getMessage()}\n";
        $conn->close();
    }

	public function savechat($data){
		$url="localhost:9200/chat/_doc/";
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_USERPWD, "elastic:soon06051007");
		curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
		curl_setopt($ch, CURLOPT_POST, true);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		return curl_exec($ch);
	}

}




?>