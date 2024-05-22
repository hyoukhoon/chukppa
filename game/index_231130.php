<?php
include $_SERVER["DOCUMENT_ROOT"]."/inc/header.php";

//ini_set('display_errors', 1);
//ini_set('display_startup_errors', 1);
//error_reporting(E_ALL);
$today=date("Y-m-d");
$multi=$_GET['multi']??"korean";

$query="select * from xc_games2 where gamedate>='".$today."' and gubun=1 and status!='취소' order by gamedate asc, gametime asc";
$result = $mysqli->query($query) or die("query error => ".$mysqli->error);
while($rs = $result->fetch_object()){
	$rsp[]=$rs;
}

$query="SELECT teamname,player FROM python.koreanteam order by ord asc";
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


$query="SELECT country FROM xc_games2 group by country order by country";
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
	for($d=0;$d<=7;$d++){
		$date=date("Y-m-d", strtotime('+'.$d.' days'));
?>
		<div style="<?php if(isMobile()){?>width:200px;<?php }else{?>width:165px;<?php }?>" class="col-lg-2">
			<div class="card mb-4">
				<div class="card-header" style="background-color: #000;color:#fff;text-align:center;">
					<?php echo date("m",strtotime($date))."월".date("d",strtotime($date))."일 (".w_date(date("w",strtotime($date))).")";?>
				</div>
				<div class="card-body">
					<?php
						$pla=0;
						foreach($rsp as $p){
							if($p->gamedate==$date){
								foreach($ks as $k){
									if($p->hometeam==$k->teamname or $p->awayteam==$k->teamname){
										$pla++;
										echo $k->player."(".substr($p->gametime,0,5).")<br>";
									}
								}
							}
						}
						if(!$pla){
							echo "없음";
						}
					?>
				</div>
			</div>
		</div>
<?php }?>
<br>
<div style="text-align:center;">
<script async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js?client=ca-pub-3926380236997271"
     crossorigin="anonymous"></script>
<!-- 축메인 -->
<ins class="adsbygoogle"
     style="display:block"
     data-ad-client="ca-pub-3926380236997271"
     data-ad-slot="2829164776"
     data-ad-format="auto"
     data-full-width-responsive="true"></ins>
<script>
     (adsbygoogle = window.adsbygoogle || []).push({});
</script>
</div>
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
						if($p->hometeam==$k->teamname or $p->awayteam==$k->teamname){
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
									echo "[종료] ".$p->score;
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
						if(empty($j)){echo $date."까지 경기 없음";}
					?>
				</div>
			</div>
		</div>
<?php }?>
		

<p style="text-align:center;">
※ 위 일정은 실제 중계 일정과 다를 수 있습니다.
</p>
</div>
</main>
<br><br>
<br><br>

<?php
include $_SERVER["DOCUMENT_ROOT"]."/inc/footer.php";
?>