<?php
include $_SERVER["DOCUMENT_ROOT"]."/inc/header.php";
if(!$_SESSION['loginValue']['SEMAIL']){
	echo "<script>alert('로그인 하십시오');location.href='/member/login.php?moveurl=/game/bet.php';</script>";
 	exit;
 }


//ini_set('display_errors', 1);
//ini_set('display_startup_errors', 1);
//error_reporting(E_ALL);
$today=date("Y-m-d");


$query2="SELECT gid, bid as betid FROM python.xc_bets where isdisp=1 and startdate<now() and enddate>=now()";
$result2 = $mysqli->query($query2) or die("query error => ".$mysqli->error);
$rs2 = $result2->fetch_object();
$gid = $rs2->gid;
$betid = $rs2->betid;

$query="select * from xc_games_fs where gid in (".$gid.") order by gamedate asc, gametime asc, gid asc";
$result = $mysqli->query($query) or die("query error => ".$mysqli->error);
while($rs = $result->fetch_object()){
	$rsp[]=$rs;
}


?>
<style>
	.page-link a{color: black;}
</style>

<main class="container">
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
					★ 해외파 경기 승부 맞추기
				</div>
				<div class="card-body">
					<?php
					$j=1;
					foreach($rsp as $p){
					?>
							<?php if($j){echo "<hr>";}?>
							<p class="card-text">
								<?php
								echo $p->gamedate." ".substr($p->gametime,0,5)." (".w_date(date("w",strtotime($p->gamedate))).")<br>";
								echo "<input type='checkbox' class='form-check-input' style='vertical-align:baseline;' name='mygid[]' id='c_".$p->gid."' value='".$p->gid."'>&nbsp;".$j."경기 : ".koreanteamtitle($p->hometeam)." - ".koreanteamtitle($p->awayteam);
								//echo implode(",",gamehistory($p->hometeam)).":".implode(",",gamehistory($p->awayteam));
								//$hgh=gamehistory($p->hometeam);
								//$agh=gamehistory($p->awayteam);
								//echo "<br>";
								//echo historyview($p->hometeamhistory,1)." - ".historyview($p->awayteamhistory);
								?>
							<div style="text-align:center;">
								<input type="checkbox" class="btn-check" name="bets_<?php echo $p->gid;?>[]" value="3" id="bets_<?php echo $p->gid;?>_1" onclick="ck('<?php echo $p->gid;?>')">
											<label class="btn btn-outline-primary" style="width:100px;" for="bets_<?php echo $p->gid;?>_1">승</label>
								<input type="checkbox" class="btn-check" name="bets_<?php echo $p->gid;?>[]" value="1" id="bets_<?php echo $p->gid;?>_2" onclick="ck('<?php echo $p->gid;?>')">
											<label class="btn btn-outline-primary" style="width:100px;" for="bets_<?php echo $p->gid;?>_2">무</label>
								<input type="checkbox" class="btn-check" name="bets_<?php echo $p->gid;?>[]" value="0" id="bets_<?php echo $p->gid;?>_3" onclick="ck('<?php echo $p->gid;?>')">
											<label class="btn btn-outline-primary" style="width:100px;" for="bets_<?php echo $p->gid;?>_3">패</label>
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
		<button type="button" class="btn btn-dark" onclick="savegame()">
		  등록하기
		</button>
	</div>
</div>


</main>
<br><br>
<br><br>
<script>
	function savegame(){
			var gidcnt='<?php echo $j-1;?>';
			var acnt=$("input[name='mygid[]']:checked").length;
			if(acnt<gidcnt){
				alert('선택하지 않은 경기가 있습니다. 확인해 주십시오.');
				return false;
			}

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

			var jsongid = JSON.stringify(arraygid);
			var jsonbetsgid = JSON.stringify(betsgid);

//			alert(jsongid);
//			alert(jsonbetsgid);
//			return;

			var data = {
				jsongid : jsongid,
				jsonbetsgid : jsonbetsgid,
				betid : '<?php echo $betid?>'
			};
			$.ajax({
					async : false ,
					type : 'post' ,
					url : 'bet_savegame.php' ,
					data  : data ,
					dataType : 'json' ,
					error : function(e) {
						console.log(e);
					} ,
					success : function(data) {
						alert(data.val);
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

function ck(n){

		var allcnt=$("input:checkbox[class='btn-check']:checked").length;
		if(allcnt>13){
			alert('전체 승부 예측은 13개까지만 선택할 수 있습니다.');
			event.preventDefault();
			return false;
		}

		if($("#bets_"+n+"_1").is(':checked') == true || $("#bets_"+n+"_2").is(':checked') == true || $("#bets_"+n+"_3").is(':checked') == true){
			$("#c_"+n).prop('checked',true);
		}else{
			$("#c_"+n).prop('checked',false);
		}


}

</script>
<?php
include $_SERVER["DOCUMENT_ROOT"]."/inc/footer.php";
?>