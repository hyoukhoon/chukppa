<?php
include $_SERVER["DOCUMENT_ROOT"]."/inc/header.php";
if(!$_SESSION['loginValue']['SEMAIL']){
	echo "<script>alert('로그인 하십시오');location.href='/member/login.php?moveurl=/game/index_plan2.php';</script>";
 	exit;
 }


//ini_set('display_errors', 1);
//ini_set('display_startup_errors', 1);
//error_reporting(E_ALL);
$today=date("Y-m-d");


$query2="SELECT gmidgmts FROM python.xc_betman where gmId='G101' and saleStartDate<now() and saleEndDate>=now()";
$result2 = $mysqli->query($query2) or die("query error => ".$mysqli->error);
$rs2 = $result2->fetch_object();
$gmidgmts = $rs2->gmidgmts;

$query="select g.*, x.gid as xid, x.matchSeq, x.hometeamhistory, x.awayteamhistory from xc_games_fs g 
join xc_betman_games x on g.gid=x.xgid 
where x.gmidgmts='".$gmidgmts."' and x.handi=0 order by x.matchSeq asc";
$result = $mysqli->query($query) or die("query error => ".$mysqli->error);
while($rs = $result->fetch_object()){
	$rsp[]=$rs;
}


$query2="SELECT gmidgmts FROM python.xc_betman where gmId='G011' and saleStartDate<now() and saleEndDate>=now()";
$result2 = $mysqli->query($query2) or die("query error => ".$mysqli->error);
$rs2 = $result2->fetch_object();
$gmidgmts = $rs2->gmidgmts;

$query="select g.*, x.gid as xid, x.matchSeq, x.hometeamhistory, x.awayteamhistory from xc_games_fs g 
join xc_betman_games x on g.gid=x.xgid 
where x.gmidgmts='".$gmidgmts."' and x.handi=0 order by x.matchSeq asc";
$result = $mysqli->query($query) or die("query error => ".$mysqli->error);
while($rs = $result->fetch_object()){
	$rsp2[]=$rs;
}

?>
<style>
	.page-link a{color: black;}
</style>

<main class="container">
<div style="padding:10px;text-align:center;">
	<input type="radio" class="btn-check" name="weeks" id="korean" autocomplete="off" onclick="location.href='index_plan.php'">
	<label class="btn btn-secondary" for="korean">주요리그경기일정</label>

	<input type="radio" class="btn-check" name="weeks" id="league" autocomplete="off" checked>
	<label class="btn btn-secondary" style="background-color:#000;" for="league">관심경기등록</label>
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
<input type="hidden" name="gtype" id="gtype" value="N">
		<div class="col-lg-6">
			<div class="card mb-4">
				<div class="card-header" style="background-color: #000;color:#fff;">
					★ 승부식 경기 모음
				</div>
				<div class="card-body">
					<?php
					$j=0;
					foreach($rsp as $p){
					?>
							<?php if($j){echo "<hr>";}?>
							<p class="card-text">
								<?php
								echo $p->gamedate." ".substr($p->gametime,0,5)." (".w_date(date("w",strtotime($p->gamedate))).")<br>";
								echo "<input type='checkbox' style='vertical-align:baseline;' name='mygid[]' id='c_".$p->xid."' value='".$p->gid."'>&nbsp;"."[".$p->matchSeq."] ".$p->hometeambetman." - ".$p->awayteambetman;
								//echo "<br>";
								//echo historyview($p->hometeamhistory,1)." - ".historyview($p->awayteamhistory);
								?>
								
								<?php
								if($p->status=="RESULT"){
									echo "<br>";
									echo "[종료] ".$p->score;
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
							<div>
								<input type="checkbox" class="btn-check" name="bets_<?php echo $p->gid;?>[]" value="3" id="bets_<?php echo $p->xid;?>_1" onclick="ck('<?php echo $p->xid;?>','SC')">
											<label class="btn btn-outline-primary" for="bets_<?php echo $p->xid;?>_1">승</label>
								<input type="checkbox" class="btn-check" name="bets_<?php echo $p->gid;?>[]" value="1" id="bets_<?php echo $p->xid;?>_2" onclick="ck('<?php echo $p->xid;?>','SC')">
											<label class="btn btn-outline-primary" for="bets_<?php echo $p->xid;?>_2">무</label>
								<input type="checkbox" class="btn-check" name="bets_<?php echo $p->gid;?>[]" value="0" id="bets_<?php echo $p->xid;?>_3" onclick="ck('<?php echo $p->xid;?>','SC')">
											<label class="btn btn-outline-primary" for="bets_<?php echo $p->xid;?>_3">패</label>
								</div>
							</p>
					<?php 
							$j++;
					}
					?>
				</div>
				
			</div>
		</div>

<div class="col-lg-6">
			<div class="card mb-4">
		<div class="card-header" style="background-color: #000;color:#fff;">
					★ 승무패 경기 모음
				</div>
				<div class="card-body">
					<?php
					$j=0;
					foreach($rsp2 as $p2){
					?>
							<?php if($j){echo "<hr>";}?>
							<p class="card-text">
								<?php
								echo $p2->gamedate." ".substr($p2->gametime,0,5)." (".w_date(date("w",strtotime($p2->gamedate))).")<br>";
								echo "<input type='checkbox' class='form-check-input' style='vertical-align:baseline;' name='mygid[]' id='c_".$p2->xid."' value='".$p2->gid."'>&nbsp;".$p2->matchSeq."경기 : ".$p2->hometeambetman." - ".$p2->awayteambetman;
								//echo implode(",",gamehistory($p2->hometeam)).":".implode(",",gamehistory($p2->awayteam));
								//$hgh=gamehistory($p2->hometeam);
								//$agh=gamehistory($p2->awayteam);
								//echo "<br>";
								//echo historyview($p2->hometeamhistory,1)." - ".historyview($p2->awayteamhistory);
								?>
								
								<?php
								if($p2->status=="RESULT"){
									echo "<br>";
									echo "[종료] ".$p2->score;
								}else if($p2->status=="LIVE"){
									echo " <font color='blue'>[".$p2->playtime."'] ".$p2->score."</font>";
								}else if($p2->status=="POSTPONED"){
									echo " <font color='red'>[연기]</font>";
								}else if($p2->status=="SUSPENDED"){
									echo " <font color='red'>[순연]</font>";
								}else{
									$win="";$draw="";$lose="";$maxcnt=0;
									if($wts=betmangameis('WDL',$p2->gid)){
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
							<div>
								<input type="checkbox" class="btn-check" name="bets_<?php echo $p2->gid;?>[]" value="3" id="bets_<?php echo $p2->xid;?>_1" onclick="ck('<?php echo $p2->xid;?>','WDL')">
											<label class="btn btn-outline-primary" for="bets_<?php echo $p2->xid;?>_1">승</label>
								<input type="checkbox" class="btn-check" name="bets_<?php echo $p2->gid;?>[]" value="1" id="bets_<?php echo $p2->xid;?>_2" onclick="ck('<?php echo $p2->xid;?>','WDL')">
											<label class="btn btn-outline-primary" for="bets_<?php echo $p2->xid;?>_2">무</label>
								<input type="checkbox" class="btn-check" name="bets_<?php echo $p2->gid;?>[]" value="0" id="bets_<?php echo $p2->xid;?>_3" onclick="ck('<?php echo $p2->xid;?>','WDL')">
											<label class="btn btn-outline-primary" for="bets_<?php echo $p2->xid;?>_3">패</label>
								</div>
							</p>
					<?php 
							$j++;
					}
					?>
				</div>
	</div>
</div>

	<div style="text-align:center;">
		<button type="button" class="btn btn-dark" data-bs-toggle="modal" data-bs-target="#staticBackdrop">
		  관심 게임으로 저장
		</button>
	</div>
</div>

<!-- Modal -->
<div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="staticBackdropLabel">슬롯 선택</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body text-center">
			<input type="radio" class="btn-check" id="slot1" name="slot" value="1" autocomplete="off"  checked>
			<label class="btn btn-outline-primary" for="slot1">슬롯1</label>&nbsp;&nbsp;
			<input type="radio" class="btn-check" id="slot2" name="slot" value="2" autocomplete="off" >
			<label class="btn btn-outline-primary" for="slot2">슬롯2</label>&nbsp;&nbsp;
			<input type="radio" class="btn-check" id="slot3" name="slot" value="3" autocomplete="off" >
			<label class="btn btn-outline-primary" for="slot3">슬롯3</label>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">닫기</button>
        <button type="button" class="btn btn-primary" onclick="savegame()">저장</button>
      </div>
    </div>
  </div>
</div>

</main>
<br><br>
<br><br>
<script>
	function savegame(){
			var itemcode=$("#gtype").val();
			var arraygid = [];
			var betsgid = [];
			$("input[name='mygid[]']:checked").each(function(i) {
				arraygid.push($(this).val());
					var gid=$(this).val();
					var arrgid= [];
					$("input[name='bets_"+gid+"[]']:checked").each(function(){
						var chk = $(this).val();
						arrgid.push(chk);
					})
					betsgid.push(arrgid);
			});

			if(arraygid.length>20){
				alert('한 슬롯당 최대 20개까지만 저장할 수 있습니다.');
				return;
			}
			var jsongid = JSON.stringify(arraygid);
			var jsonbetsgid = JSON.stringify(betsgid);
			var slot = $("input[name='slot']:checked").val();
//			alert(jsongid);
//			alert(jsonbetsgid);
//			return;

			var data = {
				jsongid : jsongid,
				jsonbetsgid : jsonbetsgid,
				slot : slot,
				itemcode : itemcode
			};
			$.ajax({
					async : false ,
					type : 'post' ,
					url : 'savegame.php' ,
					data  : data ,
					dataType : 'json' ,
					error : function(e) {
						console.log(e);
					} ,
					success : function(data) {
						alert(data.msg);
						location.reload();
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

function ck(n,gtype){
	var itemcode=$("#gtype").val();

	if(itemcode=="N" || itemcode==gtype){

		$("#gtype").val(gtype);
		if($("#bets_"+n+"_1").is(':checked') == true || $("#bets_"+n+"_2").is(':checked') == true || $("#bets_"+n+"_3").is(':checked') == true){
			$("#c_"+n).prop('checked',true);
		}else{
			$("#c_"+n).prop('checked',false);
		}

	}else{
		alert('승부식이나 승무패 중에 하나를 선택해 주십시오.');
		return false;
	}
}

</script>
<?php
include $_SERVER["DOCUMENT_ROOT"]."/inc/footer.php";
?>