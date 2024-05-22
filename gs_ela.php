<?php include "/var/www/chukppa/inc/dbcon.php";
//ini_set('display_errors', 1);
//ini_set('display_startup_errors', 1);
//error_reporting(E_ALL);

//https://www.goal.com/api/live-scores/refresh?edition=kr&date=2023-08-19&tzoffset=540
$today_game="gs_20230818.html";
//$today_game="https://www.goal.com/api/live-scores/refresh?edition=kr&date=".date("Y-m-d")."&tzoffset=540";
$game=file_get_contents($today_game);
$gr=json_decode($game);

foreach($gr->liveScores as $g){
	foreach($g->matches as $m){
//		echo "[".$g->competition->area->name."] "."[".$g->competition->name."] (".date("Y-m-d H:i", strtotime($m->startDate)).")".$m->teamA->short.":".$m->teamB->short."<br>";
		$gscore=$m->score->teamA.":".$m->score->teamB;
		$guid=$g->competition->area->name.$g->competition->name.date("Y-m-d", strtotime($m->startDate)).$m->teamA->short.$m->teamB->short;
		$guid=hash("sha256", $guid);
		$m->teamA->short=changename($m->teamA->short);
		$m->teamB->short=changename($m->teamB->short);
		$gubun=is_koreanteam($m->teamA->short,$m->teamB->short);
		$query="insert into xc_games (`country`,`league`,`gamedate`,`gametime`,`hometeam`,`awayteam`,`status`,`score`,`regdate`,`uid`,`gubun`) values ('".$g->competition->area->name."','".addslashes($g->competition->name)."','".date("Y-m-d", strtotime($m->startDate))."','".date("H:i:s", strtotime($m->startDate))."','".addslashes($m->teamA->short)."','".addslashes($m->teamB->short)."','".$m->status."','".$gscore."',now(),'".$guid."',".$gubun.") ON DUPLICATE KEY UPDATE status='".$m->status."', score='".$gscore."', playtime='".$m->period->minute."', regdate=now()";
//		echo $query."<br>";
		$data = '{
			"country": "'.$g->competition->area->name.'",
			"league": "'.$g->competition->name.'",
			"gamedate": "'.date("Y-m-d", strtotime($m->startDate)).'",
			"gametime": "'.date("H:i:s", strtotime($m->startDate)).'",
			"hometeam":  "'.$m->teamA->short.'",
			"awayteam": "'.$m->teamB->short.'",
			"status":"'.$m->status.'",
			"score": "'.$gscore.'",
			"regdate": "'.date("Y-m-d H:i:s", strtotime($m->startDate)).'",
			"gubun": '.$gubun.'
		}';
		$url="localhost:9200/xc_games/_doc/".$guid;
		echo $data."<br>";
		$rs=elaCurl($url,$data);
//		$sql=$mysqli->query($query) or die("3:".$mysqli->error);
	}
}

//echo "<pre>";
//print_r($gr);
?>