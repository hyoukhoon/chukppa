<?php include "/var/www/chukppa/inc/dbcon.php";
//ini_set('display_errors', 1);
//ini_set('display_startup_errors', 1);
//error_reporting(E_ALL);

//https://www.goal.com/api/live-scores/refresh?edition=kr&date=2023-09-18&tzoffset=540
//$today_game="gs_20230818.html";
for($k=1;$k<=7;$k++){
	$today_game="https://www.goal.com/api/live-scores/refresh?edition=kr&date=".date("Y-m-d", strtotime('+'.$k.' days'))."&tzoffset=540";
	$game=file_get_contents($today_game);
	$gr=json_decode($game);

	foreach($gr->liveScores as $g){
		foreach($g->matches as $m){
	//		echo "[".$g->competition->area->name."] "."[".$g->competition->name."] (".date("Y-m-d H:i", strtotime($m->startDate)).")".$m->teamA->short.":".$m->teamB->short."<br>";
			if($m->teamA->short and $m->teamB->short and stripos($m->round->name,'omen')==false){
				$gscore=$m->score->teamA.":".$m->score->teamB;
				$guid=$g->competition->area->name.$g->competition->name.date("Y-m-d", strtotime($m->startDate)).$m->teamA->full.$m->teamB->full;
				$guid=hash("sha256", $guid);
				$hometeamid=$m->teamA->id;
				$awayteamid=$m->teamB->id;
				$m->teamA->short=koreantitle($m->teamA->short);
				$m->teamB->short=koreantitle($m->teamB->short);
				$hometeambetman = betmantitle($m->teamA->short);
				$awayteambetman = betmantitle($m->teamB->short);
				$gubun=is_koreanteam($m->teamA->full,$m->teamB->full);
				$query="insert into xc_games2 (`country`,`league`,`roundname`,`gamedate`,`gametime`,`hometeam`,`awayteam`,`hometeamid`,`awayteamid`,`hometeamfull`,`awayteamfull`,`hometeambetman`,`awayteambetman`,`status`,`score`,`regdate`,`uid`,`gubun`) values ('".$g->competition->area->name."','".addslashes($g->competition->name)."','".addslashes($m->round->name)."','".date("Y-m-d", strtotime($m->startDate))."','".date("H:i:s", strtotime($m->startDate))."','".addslashes($m->teamA->short)."','".addslashes($m->teamB->short)."','".$hometeamid."','".$awayteamid."','".addslashes($m->teamA->full)."','".addslashes($m->teamB->full)."','".addslashes($hometeambetman)."','".addslashes($awayteambetman)."','".$m->status."','".$gscore."',now(),'".$guid."',".$gubun.") ON DUPLICATE KEY UPDATE status='".$m->status."', score='".$gscore."', playtime='".$m->period->minute."', regdate=now(), hometeam='".addslashes($m->teamA->short)."',awayteam='".addslashes($m->teamB->short)."', hometeambetman='".addslashes($hometeambetman)."',awayteambetman='".addslashes($awayteambetman)."'";
//				echo $query."<br>";
				$data = '{
					"country": "'.$g->competition->area->name.'",
					"league": "'.$g->competition->name.'",
					"roundname": "'.$m->round->name.'",
					"gamedate": "'.date("Y-m-d", strtotime($m->startDate)).'",
					"gametime": "'.date("H:i:s", strtotime($m->startDate)).'",
					"hometeam":  "'.$m->teamA->short.'",
					"awayteam": "'.$m->teamB->short.'",
					"hometeamid":  "'.$m->teamA->id.'",
					"awayteamid": "'.$m->teamB->id.'",
					"hometeamfull":  "'.$m->teamA->full.'",
					"awayteamfull": "'.$m->teamB->full.'",
					"hometeambetman": "'.$hometeambetman.'",
					"awayteambetman": "'.$awayteambetman.'",
					"status":"'.$m->status.'",
					"score": "'.$gscore.'",
					"regdate": "'.date("Y-m-d H:i:s").'",
					"gubun": '.$gubun.'
				}';
				$url="localhost:9200/xc_games2/_doc/".$guid;
				$sql=$mysqli->query($query) or die("3:".$mysqli->error);
				$rs=elaCurl($url,$data);
			}
		}
	}

}

//echo "<pre>";
//print_r($gr);
?>