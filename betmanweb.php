<?php include "/var/www/chukppa/inc/dbcon.php";
//ini_set('display_errors', 1);
//ini_set('display_startup_errors', 1);
//error_reporting(E_ALL);

$query2="SELECT gmTs FROM python.xc_betman where gmId='G101' and saleStartDate<now() and saleEndDate>=now()";
$result2 = $mysqli->query($query2) or die("query error => ".$mysqli->error);
$rs2 = $result2->fetch_object();
$gTs = $rs2->gmTs;

$datefile="betsweb_".date("Ymd").".html";
//if($gTs){//진행중인 승부식이 있으면

	//$today_game="https://www.betman.co.kr/comm/live/rinfo/G101%7C".$gTs;
	$today_game="https://www.chukppa.com/cdata/".$datefile;
	$game=file_get_contents($today_game);
//	echo $game;
//	exit;
	$gr=json_decode($game);

//	echo "<pre>";
//	print_r($gr);
//	exit;

	//print_r($gr->liveInfo[0]->currentLottery);

	$gmId = $gr->liveInfo[0]->currentLottery->gmId;
	$gameName = $gr->liveInfo[0]->currentLottery->gameName;
	$gmTs = $gr->liveInfo[0]->currentLottery->gmTs;
	$gmOsidTsYear = $gr->liveInfo[0]->currentLottery->gmOsidTsYear;
	$gmOsidTs = $gr->liveInfo[0]->currentLottery->gmOsidTs;
	$gameResultDate = date("Y-m-d H:i:s",$gr->liveInfo[0]->currentLottery->gameResultDate/1000);
	$payoStartDate = date("Y-m-d H:i:s",$gr->liveInfo[0]->currentLottery->payoStartDate/1000);
	$payoEndDate = date("Y-m-d H:i:s",$gr->liveInfo[0]->currentLottery->payoEndDate/1000);
	$saleStartDate = date("Y-m-d H:i:s",$gr->liveInfo[0]->currentLottery->saleStartDate/1000);
	$saleEndDate = date("Y-m-d H:i:s",$gr->liveInfo[0]->currentLottery->saleEndDate/1000);
	$saleStatus = $gr->liveInfo[0]->currentLottery->saleStatus;
	$gmidgmts = $gmId.$gmTs;

	$query="INSERT INTO `python`.`xc_betman`
					(`gmId`,
					`gameName`,
					`gmTs`,
					`gmOsidTsYear`,
					`gmOsidTs`,
					`gameResultDate`,
					`payoStartDate`,
					`payoEndDate`,
					`saleStartDate`,
					`saleEndDate`,
					`saleStatus`,
					`gmidgmts`)
					VALUES
					('".$gmId."',
					'".$gameName."',
					'".$gmTs."',
					'".$gmOsidTsYear."',
					'".$gmOsidTs."',
					'".$gameResultDate."',
					'".$payoStartDate."',
					'".$payoEndDate."',
					'".$saleStartDate."',
					'".$saleEndDate."',
					'".$saleStatus."',
					'".$gmidgmts."') ON DUPLICATE KEY UPDATE 
					saleStatus = '".$saleStatus."',
					gameName = '".$gameName."',
					gmOsidTsYear = '".$gmOsidTsYear."',
					gmOsidTs = '".$gmOsidTs."',
					gameResultDate = '".$gameResultDate."',
					payoStartDate = '".$payoStartDate."',
					payoEndDate = '".$payoEndDate."',
					saleEndDate = '".$saleEndDate."',
					updatetime = now()";
	//echo $query;
	$sql=$mysqli->query($query) or die("3:".$mysqli->error);

	/*

	[0] => itemCode
	[1] => itemName
	[2] => gameName
	[3] => gameDate
	[4] => endDate
	[5] => unsetEndDate
	[6] => leagueCode
	[7] => leagueName
	[8] => domastic
	[9] => managedLeague
	[10] => meetStadiumFullName
	[11] => matchSeq
	[12] => homeId
	[13] => awayId
	[14] => homeName
	[15] => awayName
	[16] => winAllot
	[17] => drawAllot
	[18] => loseAllot
	[19] => handi
	[20] => winHandi
	[21] => drawHandi
	[22] => loseHandi
	[23] => neutral
	[24] => noticeNo
	[25] => gameReject
	[26] => buyReject
	[27] => protoStatus
	[28] => gameResult
	[29] => gameSubject
	[30] => live
	[31] => sgl
	[32] => unsetSchedule
	[33] => mchScore


	*/



	foreach($gr->liveInfo[0]->compSchedules->datas as $r){
		if($r[0]=="SC"){
	//		print_r($r);

			$itemCode = $r[0];
			$itemName = $r[1];
			$gameName = $r[2];
			$gameDate = date("Y-m-d H:i:s",$r[3]/1000);
			$endDate = date("Y-m-d H:i:s",$r[4]/1000);
			$unsetEndDate = $r[5];
			$leagueCode = $r[6];
			$leagueName = $r[7];
			$domastic = $r[8];
			$managedLeague = $r[9];
			$meetStadiumFullName = $r[10];
			$matchSeq = $r[11];
			$homeId = $r[12];
			$awayId = $r[13];
			$homeName = $r[14];
			$awayName = $r[15];
			$winAllot = $r[16];
			$drawAllot = $r[17];
			$loseAllot = $r[18];
			$handi = $r[19];
			$winHandi = $r[20];
			$drawHandi = $r[21];
			$loseHandi = $r[22];
			$neutral = $r[23];
			$noticeNo = $r[24];
			$gameReject = $r[25];
			$buyReject = $r[26];
			$protoStatus = $r[27];
			$gameResult = $r[28];
			$gameSubject = $r[29];
			$live = $r[30];
			$sgl = $r[31];
			$unsetSchedule = $r[32];
			$mchScore = $r[33];
			$guid = $gmidgmts.$matchSeq;
			//$uid=hash("sha256", $guid);
			$uid=$guid;

			$gd=explode(" ",$gameDate);
			$xgid=0;
			$query3="SELECT gid FROM xc_games2  where gamedate='".$gd[0]."' and gametime='".$gd[1]."' and hometeambetman='".$homeName."' and awayteambetman='".$awayName."'";
			//echo $query3."<br>";
			$result3 = $mysqli->query($query3) or die("query error => ".$mysqli->error);
			$cs = $result3->fetch_object();
			if($cs->gid){
				$xgid = $cs->gid;
			}

			$query2="INSERT INTO `python`.`xc_betman_games`
							(`itemCode`,
							`itemName`,
							`gameName`,
							`gameDate`,
							`endDate`,
							`unsetEndDate`,
							`leagueCode`,
							`leagueName`,
							`domastic`,
							`managedLeague`,
							`meetStadiumFullName`,
							`matchSeq`,
							`homeId`,
							`awayId`,
							`homeName`,
							`awayName`,
							`winAllot`,
							`drawAllot`,
							`loseAllot`,
							`handi`,
							`winHandi`,
							`drawHandi`,
							`loseHandi`,
							`neutral`,
							`noticeNo`,
							`gameReject`,
							`buyReject`,
							`protoStatus`,
							`gameResult`,
							`gameSubject`,
							`live`,
							`sgl`,
							`unsetSchedule`,
							`mchScore`,
							`xgid`,
							`gmidgmts`,
							`uid`)
							VALUES
							('".$itemCode."',
							'".$itemName."',
							'".$gameName."',
							'".$gameDate."',
							'".$endDate."',
							'".$unsetEndDate."',
							'".$leagueCode."',
							'".$leagueName."',
							'".$domastic."',
							'".$managedLeague."',
							'".$meetStadiumFullName."',
							'".$matchSeq."',
							'".$homeId."',
							'".$awayId."',
							'".$homeName."',
							'".$awayName."',
							'".$winAllot."',
							'".$drawAllot."',
							'".$loseAllot."',
							'".$handi."',
							'".$winHandi."',
							'".$drawHandi."',
							'".$loseHandi."',
							'".$neutral."',
							'".$noticeNo."',
							'".$gameReject."',
							'".$buyReject."',
							'".$protoStatus."',
							'".$gameResult."',
							'".$gameSubject."',
							'".$live."',
							'".$sgl."',
							'".$unsetSchedule."',
							'".$mchScore."',
							'".$xgid."',
							'".$gmidgmts."',
							'".$uid."')  ON DUPLICATE KEY UPDATE 
							meetStadiumFullName = '".$meetStadiumFullName."',
							homeId = '".$homeId."',
							awayId = '".$awayId."',
							homeName = '".$homeName."',
							awayName = '".$awayName."',
							gameDate = '".$gameDate."',
							endDate = '".$endDate."',
							winAllot = '".$winAllot."',
							drawAllot = '".$drawAllot."',
							loseAllot = '".$loseAllot."',
							handi = '".$handi."',
							winHandi = '".$winHandi."',
							drawHandi = '".$drawHandi."',
							loseHandi = '".$loseHandi."',
							protoStatus = '".$protoStatus."',
							gameResult = '".$gameResult."',
							live = '".$live."',
							mchScore = '".$mchScore."',
							updatetime = now()";
	//echo $query2."<br>";
			$sql=$mysqli->query($query2) or die("3:".$mysqli->error);
		}
	}

	foreach($gr->liveInfo[0]->voteStatus as $v){
			$uid=$gmidgmts.$v->GM_SEQ;
			$que="update xc_betman_games set w_bet_cnt=".$v->W_BET_CNT.", d_bet_cnt=".$v->D_BET_CNT.", l_bet_cnt=".$v->L_BET_CNT." where uid='".$uid."'";
	//		echo $que."<br>";
			$sql=$mysqli->query($que) or die("3:".$mysqli->error);
	}

//}
echo "ok";
?>