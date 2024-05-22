<?php session_start();
include $_SERVER['DOCUMENT_ROOT']."/inc/dbcon.php";

// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL);

 if(!$_SESSION['loginValue']['SEMAIL']){
 	$data=array("result"=>-1,"val"=>"로그인하십시오."); 
 	echo json_encode($data);
 	exit;
 }

$msgid=$_POST['msgid'];

$que="select * from xc_msg where num = ".$msgid;
$result = $mysqli->query($que) or die("2:".$mysqli->error);
$rs = $result->fetch_object();
$blockid = $rs->fromuserid;
$msg=$rs->msg;
if(!$rs->num){
	$data=array("result"=>-1,"val"=>"메세지가 없습니다. 다시 확인해 주십시오."); 
 	echo json_encode($data);
 	exit;
}

$query="INSERT INTO `python`.`xc_msg_block`
				(`userid`,
				`blockid`,
				`msg`)
				VALUES
				('".$_SESSION['loginValue']['SEMAIL']."',
				'".$blockid."',
				'".$msg."')";
				$sql1=$mysqli->query($query) or die("3:".$mysqli->error);

$upq="update xc_msg set status=0 where num=".$msgid;
$sql1=$mysqli->query($upq) or die("3:".$mysqli->error);	

$data=array("result"=>1,"num"=>$num, "val"=>"성공"); 
echo json_encode($data);
exit;

 ?>