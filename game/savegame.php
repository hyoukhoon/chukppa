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
$slot=$_POST["slot"];
$itemcode=$_POST["itemcode"];
$arraygid=json_decode($jsongid);
$arraybetsgid=json_decode($jsonbetsgid);
$k=0;
foreach($arraybetsgid as $ab){
	$betsarray[$arraygid[$k]]=$ab;
	$k++;
}

$gids=implode(",",$arraygid);
if(count($arraygid)>20){
	$data=array("result"=>-1,"val"=>"최대 20개까지만 저장할 수 있습니다.."); 
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

$query="select * from xc_mygamelists where slot='".$slot."' and userid='".$_SESSION['loginValue']['SEMAIL']."'";
//echo $query."<br>";
$result = $mysqli->query($query) or die("query error => ".$mysqli->error);
$rs = $result->fetch_object();

if($rs->mid){
	$query="update xc_mygamelists set gid='".$gids."', lists='".$jsongid."', type='".$itemcode."' where mid=".$rs->mid." and userid='".$_SESSION['loginValue']['SEMAIL']."'";
	$co="수정";
}else{
	$query="INSERT INTO `python`.`xc_mygamelists`
				(`userid`,
				`slot`,
				`type`,
				`gid`,
				`lists`)
				VALUES
				('".$_SESSION['loginValue']['SEMAIL']."',
				'".$slot."',
				'".$itemcode."',
				'".$gids."',
				'".$jsongid."')";
	$co="등록";
}
$sql1=$mysqli->query($query) or die("55:".$mysqli->error);

if($sql1){
	$data=array("result"=>1,"msg"=>"저장 했습니다. \n마이페이지 > 관심경기 에서 확인할 수 있습니다."); 
 	echo json_encode($data);
 	exit;
}else{
	$data=array("result"=>-1,"msg"=>"실패했습니다. 다시 시도해주십시오."); 
 	echo json_encode($data);
 	exit;
}

?>
