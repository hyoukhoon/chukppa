<?php include "/var/www/chukppa/inc/dbcon.php";
//ini_set('display_errors', 1);
//ini_set('display_startup_errors', 1);
//error_reporting(E_ALL);

//https://www.goal.com/api/live-scores/refresh?edition=kr&date=2023-09-18&tzoffset=540
//$today_game="gs_20230818.html";
for($k=101;$k<=120;$k++){
	$dt=date("Y-m-d", strtotime('-'.$k.' days'));
//	echo $dt."<br>";

	$today_game="https://www.goal.com/api/live-scores/refresh?edition=kr&date=".$dt."&tzoffset=540";
	$game=file_get_contents($today_game);
	$gr=json_decode($game);

	foreach($gr->liveScores as $g){
		foreach($g->matches as $m){
	//		echo "[".$g->competition->area->name."] "."[".$g->competition->name."] (".date("Y-m-d H:i", strtotime($m->startDate)).")".$m->teamA->short.":".$m->teamB->short."<br>";
			if($m->teamA->short and $m->teamB->short and stripos($m->round->name,'omen')==false){
				$gscore=$m->score->teamA.":".$m->score->teamB;
				$guid=$g->competition->area->name.$g->competition->name.date("Y-m-d", strtotime($m->startDate)).$m->teamA->full.$m->teamB->full;
				$guid=hash("sha256", $guid);
				$query="update xc_games2 set hometeamid='".$hometeamid."',awayteamid='".$awayteamid."' where uid='".$guid."'";
				//echo $query."<br>";
				$data = '{
					"country": "'.$g->competition->area->name.'",
					"league": "'.$g->competition->name.'",
					"roundname": "'.$m->round->name.'",
					"gamedate": "'.date("Y-m-d", strtotime($m->startDate)).'",
					"gametime": "'.date("H:i:s", strtotime($m->startDate)).'",
					"hometeam":  "'.$m->teamA->short.'",
					"awayteam": "'.$m->teamB->short.'",
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
				//$rs=elaCurl($url,$data);
			}
		}
	}

}

//echo "<pre>";
//print_r($gr);
?>