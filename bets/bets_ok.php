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
$bid=$_POST["bid"];
$sid=$_POST["sid"];

$query="select * from xc_bets where isdisp=1 and enddate>='".$now."' and bid=".$bid;
//echo $query."<br>";
$result = $mysqli->query($query) or die("query error => ".$mysqli->error);
$rs = $result->fetch_object();
if(!$rs->bid){
	echo "<script>alert('참여할 수 없는 이벤트입니다.');location.href='/bets/';</script>";
	exit;
}

$arraygid=explode(",",$rs->gid);
$arraylists=array();

foreach($arraygid as $g){
	$arraylists[]=$_POST['bets_'.$g];
}

$lists=implode(",",$arraylists);

if($sid){
	$query="update xc_betslists set lists='".$lists."' where sid=".$sid." and userid='".$_SESSION['loginValue']['SEMAIL']."'";
	$co="수정";
}else{
	$query="INSERT INTO `python`.`xc_betslists`
				(`bid`,
				`gid`,
				`userid`,
				`lists`)
				VALUES
				(".$bid.",
				'".$rs->gid."',
				'".$_SESSION['loginValue']['SEMAIL']."',
				'".$lists."')";
	$co="등록";
}
$sql1=$mysqli->query($query) or die("55:".$mysqli->error);
if($sql1){
	echo "<script>alert('감사합니다. ".$co."했습니다.');location.href='/bets/';</script>";
	exit;
}

?>
