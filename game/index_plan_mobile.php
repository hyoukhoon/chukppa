<?php
include $_SERVER["DOCUMENT_ROOT"]."/inc/header.php";

//ini_set('display_errors', 1);
//ini_set('display_startup_errors', 1);
//error_reporting(E_ALL);
$today=date("Y-m-d");

$query="select g.* from (select (select title from xc_soccerworld w where w.sid=d.pid) as country, d.title, d.ord from xc_soccerworld d where d.gubun='e' and d.ord>0) s
join xc_games2 g on g.country=s.country and s.title=g.league 
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

	<input type="radio" class="btn-check" name="weeks" id="teams" autocomplete="off" onclick="location.href='index_team.php'">
	<label class="btn btn-secondary" for="teams">팀별경기</label>
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
					?>
							<?php if($j){echo "<hr>";}?>
							<p class="card-text">
								<?php
								echo $p->gamedate." ".substr($p->gametime,0,5)." (".w_date(date("w",strtotime($p->gamedate))).")<br>";
								echo koreanteamtitle($p->hometeam)." - ".koreanteamtitle($p->awayteam);
								if($p->status=="RESULT"){
									echo "<br>";
									echo "[종료] ".$p->score;
								}else if($p->status=="LIVE"){
									echo " <font color='blue'>[".$p->playtime."'] ".$p->score."</font>";
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