<?php
include $_SERVER["DOCUMENT_ROOT"]."/inc/header.php";
if(!$_SESSION['loginValue']['SEMAIL']){
	echo "<script>alert('로그인 하십시오');location.href='/member/login.php';</script>";
 	exit;
 }
//ini_set('display_errors', 1);
//ini_set('display_startup_errors', 1);
//error_reporting(E_ALL);
$now=date("Y-m-d H:i:s");

$bid=$_GET["bid"];

$query="select * from xc_bets where isdisp=1 and enddate>='".$now."' and bid=".$bid;
//echo $query."<br>";
$result = $mysqli->query($query) or die("query error => ".$mysqli->error);
$rs = $result->fetch_object();
if(!$rs->bid){
	echo "<script>alert('참여할 수 없는 이벤트입니다.');history.back();</script>";
	exit;
}

$gid="(".$rs->gid.")";
$ord="(gid, ".$rs->gid.")";
$query2="select * from xc_games2 where gid in ".$gid." order by FIELD".$ord;
$result2 = $mysqli->query($query2) or die("query error => ".$mysqli->error);
while($rs2 = $result2->fetch_object()){
	$rsp[]=$rs2;
}

$query3="select * from xc_betslists where bid=".$bid." and userid='".$_SESSION['loginValue']['SEMAIL']."' and gid='".$rs->gid."'";
$result3 = $mysqli->query($query3) or die("query error => ".$mysqli->error);
$rs3 = $result3->fetch_object();

//print_r($mbl);
$ab=explode(",",$rs3->lists);//입찰한 내역 배열
if($rs3->sid){
	$kw="수정";
}else{
	$kw="등록";
}

?>
<style>
	.page-link a{color: black;}
</style>

<main class="container">

<div class="row" style="justify-content:center;<?php if(isMobile()){?>margin-top:20px;<?php }?>">
<form method="post" action="bets_ok.php" onsubmit="return submits();">
<input type="hidden" name="bid" value="<?php echo $bid;?>">
<input type="hidden" name="sid" value="<?php echo $rs3->sid;?>">
<div class="alert alert-primary" role="alert" style="color:#000;">
  <h4 class="alert-heading">★ <?php echo stripslashes($rs->title);?></h4>
  <hr>
	<p class="card-text">- 참여 가능 기간 <br>
			시작 : <?php echo date("Y년m월d일 H시i분",strtotime($rs->startdate));?>부터<br>
			종료 : <?php echo date("Y년m월d일 H시i분",strtotime($rs->enddate));?>까지
	</p>
</div>
<?php
	$j=1;
	foreach ($rsp as $r) {
		$k=$j-1;
?>
		<div class="col-lg-6">
			<div class="card mb-4">
				<div class="card-header">
					<h5><?php echo $j++;?>.<?php echo koreanteamtitle($r->hometeam);?> : <?php echo koreanteamtitle($r->awayteam);?></h5>
					- <?php echo $r->gamedate." ".substr($r->gametime,0,5)." (".w_date(date("w",strtotime($r->gamedate))).")";?>
				</div>
				<div class="card-body text-center">
					<input type="radio" class="btn-check" name="bets_<?php echo $r->gid;?>" value="승" id="bets_<?php echo $r->gid;?>_1" autocomplete="off" <?php if($ab[$k]=="승"){echo "checked";}?>>
					<label class="btn btn-outline-primary" style="width:30%" for="bets_<?php echo $r->gid;?>_1">승</label>
					<input type="radio" class="btn-check" name="bets_<?php echo $r->gid;?>" value="무" id="bets_<?php echo $r->gid;?>_2" autocomplete="off" <?php if($ab[$k]=="무"){echo "checked";}?>>
					<label class="btn btn-outline-primary" style="width:30%" for="bets_<?php echo $r->gid;?>_2">무</label>
					<input type="radio" class="btn-check" name="bets_<?php echo $r->gid;?>" value="패" id="bets_<?php echo $r->gid;?>_3" autocomplete="off" <?php if($ab[$k]=="패"){echo "checked";}?>>
					<label class="btn btn-outline-primary" style="width:30%" for="bets_<?php echo $r->gid;?>_3">패</label>
				</div>
			</div>
		</div>
<?php }?>
		
		<div class="d-grid gap-2 col-6 mx-auto">
		  <button class="btn btn-dark" type="submit">승부 예측 <?php echo $kw;?> 하기</button>
		</div>
</form>
</div>
</main>
<br><br>
<br><br>
<script>
	function submits(){
		var totalcnt=$('input:radio:checked').length;
		if(totalcnt<<?php echo $j-1;?>){
			alert('선택하지 않은 게임이 있습니다.');
			return false;
		}else{
			if(confirm('선택하신 예상대로 참여하시겠습니까?')){
				return true;
			}else{
				return false;
			}
		}
}
</script>
<?php
include $_SERVER["DOCUMENT_ROOT"]."/inc/footer.php";
?>