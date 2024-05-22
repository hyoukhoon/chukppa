<?php include "/var/www/chukppa/inc/dbcon.php";
//ini_set('display_errors', 1);
//ini_set('display_startup_errors', 1);
//error_reporting(E_ALL);

//https://www.goal.com/api/live-scores/refresh?edition=kr&date=2023-08-19&tzoffset=540
//$today_game="gs_20230818.html";
//$today_game="https://www.goal.com/api/live-scores/refresh?edition=kr&date=2024-01-25&tzoffset=540";
$today_game="https://www.chukppa.com/women.html";
$game=file_get_contents($today_game);
//echo $game;
//exit;
$gr=json_decode($game);


//echo "<pre>";
//print_r($gr);
//exit;

foreach($gr->liveScores as $g){
	foreach($g->matches as $m){
	//echo "[".$g->competition->area->name."] "."[".$g->competition->name."] (".date("Y-m-d H:i", strtotime($m->startDate)).")".$m->teamA->short.":".$m->teamB->short."<br>";
		if($m->teamA->short and $m->teamB->short and stripos($m->round->name,'omen')==false){
			echo "women=>".$m->round->name." // ".stripos($m->round->name,'women')."<br>";
			$gscore=$m->score->teamA.":".$m->score->teamB;
			$gpenalty=$m->penalty->teamA.":".$m->penalty->teamB;
			if($m->status=="RESULT"){
				if($m->score->teamA>$m->score->teamB){
					$gameresult="승";
				}else if($m->score->teamA<$m->score->teamB){
					$gameresult="패";
				}else if($m->score->teamA==$m->score->teamB){
					$gameresult="무";
				}else{
					$gameresult="N";
				}
			}else{
				$gameresult="N";
			}
			$guid=$g->competition->area->name.$g->competition->name.date("Y-m-d", strtotime($m->startDate)).$m->teamA->full.$m->teamB->full;
			$guid=hash("sha256", $guid);
			$m->teamA->short=koreantitle($m->teamA->short);
			$m->teamB->short=koreantitle($m->teamB->short);
			$gubun=is_koreanteam($m->teamA->full,$m->teamB->full);
			$query="insert into xc_games2 (`country`,`league`,`gamedate`,`gametime`,`hometeam`,`awayteam`,`hometeamfull`,`awayteamfull`,`status`,`score`,`penalty`,`regdate`,`uid`,`gubun`) values ('".$g->competition->area->name."','".addslashes($g->competition->name)."','".date("Y-m-d", strtotime($m->startDate))."','".date("H:i:s", strtotime($m->startDate))."','".addslashes($m->teamA->short)."','".addslashes($m->teamB->short)."','".addslashes($m->teamA->full)."','".addslashes($m->teamB->full)."','".$m->status."','".$gscore."','".$gpenalty."',now(),'".$guid."',".$gubun.") ON DUPLICATE KEY UPDATE status='".$m->status."', score='".$gscore."', penalty='".$gpenalty."', gameresult='".$gameresult."', playtime='".$m->period->minute."', regdate=now(), hometeam='".addslashes($m->teamA->short)."',awayteam='".addslashes($m->teamB->short)."'";
//			echo $query."<br>";
			$data = '{
				"country": "'.$g->competition->area->name.'",
				"league": "'.$g->competition->name.'",
				"gamedate": "'.date("Y-m-d", strtotime($m->startDate)).'",
				"gametime": "'.date("H:i:s", strtotime($m->startDate)).'",
				"hometeam":  "'.$m->teamA->short.'",
				"awayteam": "'.$m->teamB->short.'",
				"hometeamfull":  "'.$m->teamA->full.'",
				"awayteamfull": "'.$m->teamB->full.'",
				"status":"'.$m->status.'",
				"score": "'.$gscore.'",
				"penalty": "'.$gpenalty.'",
				"regdate": "'.date("Y-m-d H:i:s").'",
				"gubun": '.$gubun.'
			}';
			$url="localhost:9200/xc_games2/_doc/".$guid;
		//$sql=$mysqli->query($query) or die("3:".$mysqli->error);
		//$rs=elaCurl($url,$data);
		}
	}
}

?>