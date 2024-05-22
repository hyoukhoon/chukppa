<?php
include $_SERVER["DOCUMENT_ROOT"]."/inc/header.php";

//ini_set('display_errors', 1);
//ini_set('display_startup_errors', 1);
//error_reporting(E_ALL);
$today=date("Y-m-d");

$query="select g.* from (select (select title from xc_soccerworld w where w.sid=d.pid) as country, d.title, d.ord from xc_soccerworld d where d.gubun='e' and d.ord>1) s
join xc_games_fs g on g.country=s.country and s.title=g.league 
where g.gamedate>='".$today."'
order by ord desc, gamedate asc, gametime asc";
$result = $mysqli->query($query) or die("query error => ".$mysqli->error);
while($rs = $result->fetch_object()){
	$rsp[$rs->league][]=$rs;
}


?>
<style>
	.page-link a{color: black;}
</style>

<main class="container">
<div style="padding:10px;text-align:center;">
	<input type="radio" class="btn-check" name="weeks" id="korean" autocomplete="off" onclick="location.href='index.php'">
	<label class="btn btn-secondary" for="korean">해외파경기일정</label>

	<input type="radio" class="btn-check" name="weeks" id="league" autocomplete="off" checked>
	<label class="btn btn-secondary" style="background-color:#000;" for="league">주요리그경기일정</label>

	<!-- input type="radio" class="btn-check" name="weeks" id="teams" autocomplete="off" onclick="location.href='index_team.php'">
	<label class="btn btn-secondary" for="teams">팀별경기</label-->
</div>
<div class="row" style="justify-content:center;<?php if(isMobile()){?>margin-top:20px;<?php }?>">
<div align="center">
<script async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js?client=ca-pub-3926380236997271"
     crossorigin="anonymous"></script>
<!-- 축_주간주요경기일정 -->
<ins class="adsbygoogle"
     style="display:block"
     data-ad-client="ca-pub-3926380236997271"
     data-ad-slot="4385789025"
     data-ad-format="auto"
     data-full-width-responsive="true"></ins>
<script>
     (adsbygoogle = window.adsbygoogle || []).push({});
</script>
</div>
<div>&nbsp;</div>
<?php
	foreach ($rsp as $key => $val) {
?>
		<div class="col-lg-6">
			<div class="card mb-4">
				<div class="card-header" style="background-color: #000;color:#fff;">
					★ <?php echo changename($key);?>
					<span style="float:right;cursor:pointer;" onclick="opengame('<?php echo hash("sha256",$key);?>')" id="lg_<?php echo hash("sha256",$key);?>">▼펼치기</span>
				</div>
				<div class="card-body" style="display:none;" id="ga_<?php echo hash("sha256",$key);?>">
					<?php
					$j=0;
					foreach($val as $p){
						if($p->gameresult=="무" and $p->penalty!=":"){
							$resultscore=$p->score." (".$p->penalty.")";
						}else{
							$resultscore=$p->score;
						}
					?>
							<?php if($j){echo "<hr>";}?>
							<p class="card-text">
								<?php
								echo $p->gamedate." ".substr($p->gametime,0,5)." (".w_date(date("w",strtotime($p->gamedate))).")<br>";
								echo koreanteamtitle($p->hometeam)." - ".koreanteamtitle($p->awayteam);
								if($p->status=="RESULT"){
									echo "<br>";
									echo "[종료] ".$resultscore;
								}else if($p->status=="LIVE"){
									echo "<br>";
									echo " <font color='blue'>[".$p->playtime."'] ".$p->score."</font>";
								}else if($p->status=="POSTPONED"){
									echo "<br>";
									echo " <font color='red'>[연기]</font>";
								}else if($p->status=="SUSPENDED"){
									echo "<br>";
									echo " <font color='red'>[순연]</font>";
								}else{
									$win="";$draw="";$lose="";$maxcnt=0;
									if($wts=betmangameis('SC',$p->gid)){
										echo "<br>";
										$tot=$wts[0]+$wts[1]+$wts[2];
										$maxcnt=max($wts);
										$win = round(($wts[0]/$tot)*100)."%";
										if($maxcnt==$wts[0]){$win="<font color='red'><b>".$win."</b></font>";}
										$draw = round(($wts[1]/$tot)*100)."%";
										if($maxcnt==$wts[1]){$draw="<font color='red'><b>".$draw."</b></font>";}
										$lose = round(($wts[2]/$tot)*100)."%";
										if($maxcnt==$wts[2]){$lose="<font color='red'><b>".$lose."</b></font>";}
										echo "(승:".$win." / 무:".$draw." / 패:".$lose.")";
									}
								}
							?>
							</p>
					<?php 
							$j++;
					}
					?>
				</div>
			</div>
		</div>
<?php }?>
		
<?php

$query2="SELECT gmidgmts FROM python.xc_betman where gmId='G101' and saleStartDate<now() and saleEndDate>=now()";
$result2 = $mysqli->query($query2) or die("query error => ".$mysqli->error);
$rs2 = $result2->fetch_object();
$gmidgmts = $rs2->gmidgmts;
$gmidgmts1 = $rs2->gmidgmts;

$query="select g.*, x.matchSeq, x.hometeamhistory, x.awayteamhistory from xc_games_fs g 
join xc_betman_games x on g.gid=x.xgid 
where x.gmidgmts='".$gmidgmts."' and x.handi=0 order by x.matchSeq asc";
$result = $mysqli->query($query) or die("query error => ".$mysqli->error);
while($rs = $result->fetch_object()){
	$rsa[]=$rs;
}
$key="승부식";

$query2="SELECT gmidgmts FROM python.xc_betman where gmId='G011' and saleStartDate<now() and saleEndDate>=now()";
$result2 = $mysqli->query($query2) or die("query error => ".$mysqli->error);
$rs2 = $result2->fetch_object();
$gmidgmts = $rs2->gmidgmts;
$gmidgmts2 = $rs2->gmidgmts;

$query="select g.*, x.matchSeq, x.hometeamhistory, x.awayteamhistory from xc_games_fs g 
join xc_betman_games x on g.gid=x.xgid 
where x.gmidgmts='".$gmidgmts."' and x.handi=0 order by x.matchSeq asc";
$result = $mysqli->query($query) or die("query error => ".$mysqli->error);
while($rs = $result->fetch_object()){
	$rsp2[]=$rs;
}


if($gmidgmts1){
?>
<div class="col-lg-6">
			<div class="card mb-4">
				<div class="card-header" style="background-color: #000;color:#fff;">
					★ 승부식 경기 모음
					<span style="float:right;cursor:pointer;" onclick="opengame('<?php echo hash("sha256",$key);?>')" id="lg_<?php echo hash("sha256",$key);?>">▼펼치기</span>
				</div>
				<div class="card-body" style="display:none;" id="ga_<?php echo hash("sha256",$key);?>">
					<?php
					$j=0;
					foreach($rsa as $p){
						if($p->gameresult=="무" and $p->penalty!=":"){
							$resultscore=$p->score." (".$p->penalty.")";
						}else{
							$resultscore=$p->score;
						}
					?>
							<?php if($j){echo "<hr>";}?>
							<p class="card-text">
								<?php
								echo $p->gamedate." ".substr($p->gametime,0,5)." (".w_date(date("w",strtotime($p->gamedate))).")<br>";
								echo "[".$p->matchSeq."] ".koreanteamtitle($p->hometeam)." - ".koreanteamtitle($p->awayteam);
								//echo "<br>";
								//echo historyview($p->hometeamhistory,1)." - ".historyview($p->awayteamhistory);
								if($p->status=="RESULT"){
									echo "<br>";
									echo "[종료] ".$resultscore;
								}else if($p->status=="LIVE"){
									echo "<br>";
									echo " <font color='blue'>[".$p->playtime."'] ".$p->score."</font>";
								}else if($p->status=="POSTPONED"){
									echo "<br>";
									echo " <font color='red'>[연기]</font>";
								}else if($p->status=="SUSPENDED"){
									echo "<br>";
									echo " <font color='red'>[순연]</font>";
								}else{
									$win="";$draw="";$lose="";$maxcnt=0;
									if($wts=betmangameis('SC',$p->gid)){
										echo "<br>";
										$tot=$wts[0]+$wts[1]+$wts[2];
										$maxcnt=max($wts);
										$win = round(($wts[0]/$tot)*100)."%";
										if($maxcnt==$wts[0]){$win="<font color='red'><b>".$win."</b></font>";}
										$draw = round(($wts[1]/$tot)*100)."%";
										if($maxcnt==$wts[1]){$draw="<font color='red'><b>".$draw."</b></font>";}
										$lose = round(($wts[2]/$tot)*100)."%";
										if($maxcnt==$wts[2]){$lose="<font color='red'><b>".$lose."</b></font>";}
										echo "(승:".$win." / 무:".$draw." / 패:".$lose.")";
									}
								}
							?>
							</p>
					<?php 
							$j++;
					}
					?>
					<?php
					if(!$j){
					?>
						<p class="card-text">경기 없음</p>
					<?php }?>
				</div>
			</div>
		</div>
<?php
}
if($gmidgmts2){
$key="승무패";
?>
<div class="col-lg-6">
			<div class="card mb-4">
				<div class="card-header" style="background-color: #000;color:#fff;">
					★ 승무패 경기 모음
					<span style="float:right;cursor:pointer;" onclick="opengame('<?php echo hash("sha256",$key);?>')" id="lg_<?php echo hash("sha256",$key);?>">▼펼치기</span>
				</div>
				<div class="card-body" style="display:none;" id="ga_<?php echo hash("sha256",$key);?>">
					<?php
					$j=0;
					foreach($rsp2 as $p){
						if($p->gameresult=="무" and $p->penalty!=":"){
							$resultscore=$p->score." (".$p->penalty.")";
						}else{
							$resultscore=$p->score;
						}
					?>
							<?php if($j){echo "<hr>";}?>
							<p class="card-text">
								<?php
								echo $p->gamedate." ".substr($p->gametime,0,5)." (".w_date(date("w",strtotime($p->gamedate))).")<br>";
								echo "[".$p->matchSeq."경기] ".koreanteamtitle($p->hometeam)." - ".koreanteamtitle($p->awayteam);
								//$hgh=gamehistory($p->hometeam);
								//$agh=gamehistory($p->awayteam);
								//echo "<br>";
								//echo historyview($p->hometeamhistory,1)." - ".historyview($p->awayteamhistory);
								if($p->status=="RESULT"){
									echo "<br>";
									echo "[종료] ".$resultscore;
								}else if($p->status=="LIVE"){
									echo "<br>";
									echo " <font color='blue'>[".$p->playtime."'] ".$p->score."</font>";
								}else if($p->status=="POSTPONED"){
									echo "<br>";
									echo " <font color='red'>[연기]</font>";
								}else if($p->status=="SUSPENDED"){
									echo "<br>";
									echo " <font color='red'>[순연]</font>";
								}else{
									$win="";$draw="";$lose="";$maxcnt=0;
									if($wts=betmangameis('WDL',$p->gid)){
										echo "<br>";
										$tot=$wts[0]+$wts[1]+$wts[2];
										$maxcnt=max($wts);
										$win = round(($wts[0]/$tot)*100)."%";
										if($maxcnt==$wts[0]){$win="<font color='red'><b>".$win."</b></font>";}
										$draw = round(($wts[1]/$tot)*100)."%";
										if($maxcnt==$wts[1]){$draw="<font color='red'><b>".$draw."</b></font>";}
										$lose = round(($wts[2]/$tot)*100)."%";
										if($maxcnt==$wts[2]){$lose="<font color='red'><b>".$lose."</b></font>";}
										echo "(승:".$win." / 무:".$draw." / 패:".$lose.")";
									}
								}
							?>
							</p>
					<?php 
							$j++;
					}
					?>
				</div>
			</div>
		</div>
<?php }?>
	<div style="text-align:center;">
		<a href="/game/index_plan2.php"><button type="button" class="btn btn-dark">관심 경기 등록 하기</button></a>
	</div>

</div>
</main>
<br><br>
<br><br>
<script>
	function selcountry(t,n){
			var selcn=$(t).val();//선택국가

			var data = {
				selcn : selcn
			};
			$.ajax({
					async : false ,
					type : 'post' ,
					url : 'selcn.php' ,
					data  : data ,
					dataType : 'html' ,
					error : function(e) {
						console.log(e);
					} ,
					success : function(data) {
						$("#league").html(data);
						$("#team").html("<option selected>리그선택</option>");
					}
			});
		
	}

	function selleague(t,n){
			var sellg=$(t).val();//선택리그
			var selcn=$("#country option:selected").val();
			var data = {
				sellg : sellg,
				selcn : selcn
			};
			$.ajax({
					async : false ,
					type : 'post' ,
					url : 'sellg.php' ,
					data  : data ,
					dataType : 'html' ,
					error : function(e) {
						console.log(e);
					} ,
					success : function(data) {
						$("#team").html(data);
					}
			});
		
	}

	function selteam(t,n){
			var seltm=$(t).val();//선택팀
			var sellg=$("#league option:selected").val();
			var selcn=$("#country option:selected").val();

			var data = {
				sellg : sellg,
				selcn : selcn,
				seltm : seltm
			};
			$.ajax({
					async : false ,
					type : 'post' ,
					url : 'selteam.php' ,
					data  : data ,
					dataType : 'html' ,
					error : function(e) {
						console.log(e);
					} ,
					success : function(data) {
						$("#searchresult").html(data);
						$("body").scrollTop($("#searchteam")[0].scrollHeight);
					}
			});
	}

	function opengame(n){
		var st=$("#lg_"+n).text();
		if(st=="▼펼치기"){
			$("#ga_"+n).show();
			$("#lg_"+n).text('▲닫기');
		}else if(st=="▲닫기"){
			$("#ga_"+n).hide();
			$("#lg_"+n).text('▼펼치기');
		}
	}
</script>
<?php
include $_SERVER["DOCUMENT_ROOT"]."/inc/footer.php";
?>