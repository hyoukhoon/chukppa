<?php session_start();
include $_SERVER["DOCUMENT_ROOT"]."/inc/dbcon.php";
if(!$_SESSION['loginValue']['SEMAIL']){
	$data=array("result"=>-1,"val"=>"로그인하십시오."); 
 	echo json_encode($data);
 	exit;
 }

//ini_set('display_errors', 1);
//ini_set('display_startup_errors', 1);
//error_reporting(E_ALL);
$now=date("Y-m-d H:i:s");
$savedate=date("Y-m-d");
$jsongid=$_POST["jsongid"];
$jsonbetsgid=$_POST["jsonbetsgid"];
$betid=$_POST["betid"];
$itemcode=$_POST["itemcode"];
$arraygid=json_decode($jsongid);
$arraybetsgid=json_decode($jsonbetsgid);
$allarr=count($arraybetsgid, COUNT_RECURSIVE)-10;
if($allarr>13){
	$data=array("result"=>-1,"val"=>"전체 승부 예측은 13개까지만 선택할 수 있습니다."); 
 	echo json_encode($data);
 	exit;
}
$k=0;
foreach($arraybetsgid as $ab){
	$betsarray[$arraygid[$k]]=$ab;
	$k++;
}

$gids=implode(",",$arraygid);

if(current_points($_SESSION['loginValue']['SEMAIL'])<10){//현재 포인트 확인
	$data=array("result"=>-1,"val"=>"게임에 참여하기 위해서는 최소 10포인트가 필요합니다."); 
 	echo json_encode($data);
 	exit;
}

$gid="(".$gids.")";
$arraynewgid=array();
$query2="select gid from xc_games_fs where gid in ".$gid." order by gamedate asc, gametime asc, hometeam asc";
$result2 = $mysqli->query($query2) or die("query error => ".$mysqli->error);
while($rs2 = $result2->fetch_object()){
	$arraynewgid[]=$rs2->gid;
}
$gids=implode(",",$arraynewgid);

foreach($arraynewgid as $g){
	$arraylists[$g]=$betsarray[$g];
}

$jsongid=json_encode($arraylists);

$query="select mid from xc_mybetslists where betid='".$betid."' and userid='".$_SESSION['loginValue']['SEMAIL']."'";
//echo $query."<br>";
$result = $mysqli->query($query) or die("query error => ".$mysqli->error);
$rs = $result->fetch_object();

if($rs->mid){
	$data=array("result"=>-1,"val"=>"이번 승부예측은 이미 참여하셨습니다. 다음 승부예측을 기다려 주십시오."); 
 	echo json_encode($data);
 	exit;
}

	$query="INSERT INTO `python`.`xc_mybetslists`
				(`userid`,
				`betid`,
				`type`,
				`gid`,
				`lists`)
				VALUES
				('".$_SESSION['loginValue']['SEMAIL']."',
				'".$betid."',
				'".$itemcode."',
				'".$gids."',
				'".$jsongid."')";
$sql1=$mysqli->query($query) or die("55:".$mysqli->error);
$site_num = $mysqli -> insert_id;
if($sql1){
	points_regist($_SESSION['loginValue']['SEMAIL'], 8, $site_num);
	goals_regist($_SESSION['loginValue']['SEMAIL'], 1, $site_num);
	$data=array("result"=>1,"val"=>"등록 했습니다. 감사합니다."); 
 	echo json_encode($data);
 	exit;
}else{
	$data=array("result"=>-1,"val"=>"실패했습니다. 다시 시도해주십시오."); 
 	echo json_encode($data);
 	exit;
}

?>
