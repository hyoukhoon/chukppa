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
$slot=$_GET['slot']??1;

$arraylists=array();
$query="select * from xc_mygamelists where slot='".$slot."' and userid='".$_SESSION['loginValue']['SEMAIL']."'";
$result = $mysqli->query($query) or die("query error => ".$mysqli->error);
$rs = $result->fetch_object();
if($rs->lists){
	$arraylists=json_decode($rs->lists);
	//echo "<pre>";
	//print_r($arraylists);

	$gid="(".$rs->gid.")";
	$ord="(gid, ".$rs->gid.")";
	$query2="select g.* from xc_games_fs g 
	join xc_betman_games x on g.gid=x.xgid 
	where x.handi=0 and x.itemCode='".$rs->type."' and g.gid in ".$gid." order by x.matchSeq";
	//echo $query2;
	$result2 = $mysqli->query($query2) or die("query error => ".$mysqli->error);
	while($rs2 = $result2->fetch_object()){
		$rsp[]=$rs2;
	}

}

?>
<style>
	.page-link a{color: black;}
</style>

<main class="container">

<div class="row" style="justify-content:center;text-align: -webkit-center;<?php if(isMobile()){?>margin-top:20px;<?php }?>">
<form method="post" action="mygames_save.php">
<input type="hidden" name="mid" value="<?php echo $rs->mid;?>">
<div class="alert text-center">
	<input type="radio" class="btn-check" id="slot1" name="slot" value="1"  <?php if($slot==1){echo "checked";}?> onclick="mvp(1);">
	<label class="btn btn-outline-primary" for="slot1">슬롯1</label>&nbsp;&nbsp;
	<input type="radio" class="btn-check" id="slot2" name="slot" value="2"  <?php if($slot==2){echo "checked";}?> onclick="mvp(2);">
	<label class="btn btn-outline-primary" for="slot2">슬롯2</label>&nbsp;&nbsp;
	<input type="radio" class="btn-check" id="slot3" name="slot" value="3"  <?php if($slot==3){echo "checked";}?> onclick="mvp(3);">
	<label class="btn btn-outline-primary" for="slot3">슬롯3</label>
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
					<?php 
					if($r->status=="LIVE"){
						echo $r->playtime."' [".$r->score."]";
					}else if($r->status=="RESULT"){
						echo "종료 [".$r->score."]";
					}else{
						echo $r->gamedate." ".substr($r->gametime,0,5)." (".w_date(date("w",strtotime($r->gamedate))).")";
					}
						?>
				</div>
				<div class="card-body text-center">
					<input type="checkbox" class="btn-check" name="bets_<?php echo $r->gid;?>[]" value="3" id="bets_<?php echo $r->gid;?>_1" autocomplete="off" <?php if(in_array('3',$arraylists->{$r->gid})){echo "checked";}?>>
					<label class="btn btn-outline-primary" style="width:30%;<?php if($r->gameresult=="승"){?>border: 1px solid #ff1493;color:#ff1493<?php }?>" for="bets_<?php echo $r->gid;?>_1">승<?php if($r->gameresult=="승"){?><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-check-lg" viewBox="0 0 16 16">
  <path d="M12.736 3.97a.733.733 0 0 1 1.047 0c.286.289.29.756.01 1.05L7.88 12.01a.733.733 0 0 1-1.065.02L3.217 8.384a.757.757 0 0 1 0-1.06.733.733 0 0 1 1.047 0l3.052 3.093 5.4-6.425a.247.247 0 0 1 .02-.022Z"/>
</svg><?php }?></label>
					<input type="checkbox" class="btn-check" name="bets_<?php echo $r->gid;?>[]" value="1" id="bets_<?php echo $r->gid;?>_2" autocomplete="off" <?php if(in_array('1',$arraylists->{$r->gid})){echo "checked";}?>>
					<label class="btn btn-outline-primary" style="width:30%;<?php if($r->gameresult=="무"){?>border: 1px solid #ff1493;color:#ff1493<?php }?>" for="bets_<?php echo $r->gid;?>_2">무<?php if($r->gameresult=="무"){?><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-check-lg" viewBox="0 0 16 16">
  <path d="M12.736 3.97a.733.733 0 0 1 1.047 0c.286.289.29.756.01 1.05L7.88 12.01a.733.733 0 0 1-1.065.02L3.217 8.384a.757.757 0 0 1 0-1.06.733.733 0 0 1 1.047 0l3.052 3.093 5.4-6.425a.247.247 0 0 1 .02-.022Z"/>
</svg><?php }?></label>
					<input type="checkbox" class="btn-check" name="bets_<?php echo $r->gid;?>[]" value="0" id="bets_<?php echo $r->gid;?>_3" autocomplete="off" <?php if(in_array('0',$arraylists->{$r->gid})){echo "checked";}?>>
					<label class="btn btn-outline-primary" style="width:30%;<?php if($r->gameresult=="패"){?>border: 1px solid #ff1493;color:#ff1493<?php }?>" for="bets_<?php echo $r->gid;?>_3">패<?php if($r->gameresult=="패"){?><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-check-lg" viewBox="0 0 16 16">
  <path d="M12.736 3.97a.733.733 0 0 1 1.047 0c.286.289.29.756.01 1.05L7.88 12.01a.733.733 0 0 1-1.065.02L3.217 8.384a.757.757 0 0 1 0-1.06.733.733 0 0 1 1.047 0l3.052 3.093 5.4-6.425a.247.247 0 0 1 .02-.022Z"/>
</svg><?php }?></label>
				</div>
			</div>
		</div>
<?php }?>
		<?php
		if(!$rs->lists){
		?>
				<div class="d-grid gap-2 col-6 mx-auto">
					저장된 관심 게임이 없습니다.<br>
					<p><a href="/game/index_plan2.php"><button class="btn btn-primary" type="button">관심 게임 저장하러 가기</button></a></p>

				</div>
		<?php }?>
		<div class="d-grid gap-2 col-6 mx-auto">
		  <button class="btn btn-dark" type="submit">승부 예측 수정</button>
		</div>
</form>
</div>
</main>
<br><br>
<br><br>
<script>
	function submits(){
		var totalcnt=$('input:checkbox:checked').length;
		var slot=$("input[name='slot']:checked").val();
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

function mvp(n){
	location.href='/member/mygames.php?slot='+n;
}
</script>
<?php
include $_SERVER["DOCUMENT_ROOT"]."/inc/footer.php";
?>