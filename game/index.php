<?php
include $_SERVER["DOCUMENT_ROOT"]."/inc/header.php";

//ini_set('display_errors', 1);
//ini_set('display_startup_errors', 1);
//error_reporting(E_ALL);
$today=date("Y-m-d");
$to_date=date("Y-m-d", strtotime('+7 days'));
$multi=$_GET['multi']??"korean";

$query="select * from xc_games_fs where gamedate>='".$today."' and gubun=1 and status!='취소' order by gamedate asc, gametime asc";
$result = $mysqli->query($query) or die("query error => ".$mysqli->error);
while($rs = $result->fetch_object()){
	$rsp[]=$rs;
}

$query="SELECT teamname,player,teamnamefull FROM python.koreanteam where isuse=1 order by ord asc";
$result = $mysqli->query($query) or die("query error => ".$mysqli->error);
while($rs = $result->fetch_object()){
	$ks[]=$rs;
}

/*
$query="SELECT teamname,player FROM python.koreanteam order by num asc";
$result = $mysqli->query($query) or die("query error => ".$mysqli->error);
while($rs = $result->fetch_object()){
	$ks2[$rs->player][]=$rs->teamname;
}


$query="SELECT country FROM xc_games_fs group by country order by country";
$result = $mysqli->query($query) or die("query error => ".$mysqli->error);
while($rs = $result->fetch_object()){
	$cs[]=$rs;//나라
}

*/
?>
<style>
	.page-link a{color: black;}
</style>

<main class="container">
<div style="padding:10px;text-align:center;">
	<input type="radio" class="btn-check" name="weeks" id="korean" autocomplete="off" checked>
	<label class="btn btn-secondary" style="background-color:#000;" for="korean">해외파경기일정</label>

	<input type="radio" class="btn-check" name="weeks" id="league" autocomplete="off" onclick="location.href='index_plan.php'">
	<label class="btn btn-secondary" for="league">주요리그경기일정</label>

	<!-- input type="radio" class="btn-check" name="weeks" id="teams" autocomplete="off" onclick="location.href='index_team.php'">
	<label class="btn btn-secondary" for="teams">팀별경기</label-->
</div>
<div class="row" style="justify-content:center;<?php if(isMobile()){?>margin-top:20px;<?php }?>">
<?php
	foreach ($ks as $k) {
?>
		<div class="col-lg-6">
			<div class="card mb-4">
				<div class="card-header" style="background-color: #000;color:#fff;">
					★ <?php echo $k->player;?>
				</div>
				<div class="card-body">
					<?php
					$j=0;
					foreach($rsp as $p){
						if($p->hometeamfull==$k->teamnamefull or $p->awayteamfull==$k->teamnamefull){
							if($p->gameresult=="무" and $p->penalty!=":"){
								$resultscore=$p->score." (".$p->penalty.")";
							}else{
								$resultscore=$p->score;
							}
					?>
							<?php if($j){echo "<hr>";}?>
							<p class="card-text">
								<?php
								$tv=tvonline($p->country, $p->league);
								echo "▶ ".$p->league;
								echo " - ".$p->gamedate." ".substr($p->gametime,0,5)." (".w_date(date("w",strtotime($p->gamedate))).")<br>";
								echo koreanteamtitle($p->hometeam,1)." - ".koreanteamtitle($p->awayteam,1);
								if($p->status!="RESULT"){
									echo " (".$tv[0]." / ".$tv[1].")";
								}
								if($p->status=="RESULT"){
									echo "<br>";
									echo "[종료] ".$resultscore;
								}else if($p->status=="LIVE"){
									echo " <font color='blue'>[".$p->playtime."] ".$p->score."</font>";
								}else if($p->status=="POSTPONED"){
									echo " <font color='red'>[연기]</font>";
								}else if($p->status=="SUSPENDED"){
									echo " <font color='red'>[순연]</font>";
								}
							?>
							</p>
					<?php 
							$j++;
						}
					}?>
					<?php
						if(empty($j)){echo $to_date."까지 경기 없음";}
					?>
				</div>
			</div>
		</div>
<?php }?>
		

<p style="text-align:center;">
※ 위 일정은 실제 중계 일정과 다를 수 있습니다. 일정은 매일 업데이트 됩니다.
</p>
</div>
</main>
<br><br>
<br><br>

<?php
include $_SERVER["DOCUMENT_ROOT"]."/inc/footer.php";
?>