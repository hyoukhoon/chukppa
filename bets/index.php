<?php
include $_SERVER["DOCUMENT_ROOT"]."/inc/header.php";

//ini_set('display_errors', 1);
//ini_set('display_startup_errors', 1);
//error_reporting(E_ALL);
$now=date("Y-m-d H:i:s");
$multi=$_GET['multi']??"korean";

$query="select * from xc_bets where enddate>='".$now."' and isdisp=1 order by enddate asc";
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

<?php
	foreach ($rsp as $r) {
?>
<div class="col-sm-6 mb-3 mb-sm-0">
	<div class="card">
		<div class="card-header">★ <?php echo stripslashes($r->title);?></div>
		<div class="card-body">
			<p class="card-text">- 참여 가능 기간 <br>
			시작 : <?php echo date("Y년m월d일 H시i분",strtotime($r->startdate));?>부터<br>
			종료 : <?php echo date("Y년m월d일 H시i분",strtotime($r->enddate));?>까지
			</p>
			<a href="bets.php?bid=<?php echo $r->bid;?>" class="btn btn-dark" style="float:right;">참여하기</a>
		</div>
	</div>
</div>
<?php }?>
		

</div>
</main>
<br><br>
<br><br>

<?php
include $_SERVER["DOCUMENT_ROOT"]."/inc/footer.php";
?>