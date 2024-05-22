<?php session_start();
include $_SERVER["DOCUMENT_ROOT"]."/inc/dbcon.php";
if(!$_SESSION['loginValue']['SEMAIL']){
	echo "<script>alert('로그인 하십시오');location.href='/member/login.php';</script>";
 	exit;
 }

//ini_set('display_errors', 1);
//ini_set('display_startup_errors', 1);
//error_reporting(E_ALL);
$now=date("Y-m-d H:i:s");
$mid=$_POST["mid"];
$slot=$_POST["slot"];

$query="select * from xc_mygamelists where userid='".$_SESSION['loginValue']['SEMAIL']."' and slot=".$slot;
//echo $query."<br>";
$result = $mysqli->query($query) or die("query error => ".$mysqli->error);
$rs = $result->fetch_object();
if(!$rs->mid){
	echo "<script>alert('없는 데이터입니다.');location.href='/member/mygames.php';</script>";
	exit;
}

$arraygid=explode(",",$rs->gid);
$arraylists=array();

foreach($arraygid as $g){
	$arraylists[$g]=$_REQUEST['bets_'.$g];
}

$jsonlists=json_encode($arraylists);


	$query="update xc_mygamelists set lists='".$jsonlists."' where userid='".$_SESSION['loginValue']['SEMAIL']."' and slot=".$slot;
	$co="수정";

$sql1=$mysqli->query($query) or die("55:".$mysqli->error);
if($sql1){
	echo "<script>alert('저장했습니다.');location.href='/member/mygames.php?slot=".$slot."';</script>";
	exit;
}

?>
