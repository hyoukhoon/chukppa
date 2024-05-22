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
$type=$_POST['type'];

$que="select * from xc_msg where num = ".$msgid;
$result = $mysqli->query($que) or die("2:".$mysqli->error);
$rs = $result->fetch_object();

if($type=="receive"){
	if($rs->touserid!=$_SESSION['loginValue']['SEMAIL']){
		$data=array("result"=>-1,"val"=>"본인에게 온 쪽지가 아닙니다."); 
		echo json_encode($data);
		exit;
	}else{
		$upq="update xc_msg set status=0 where num=".$msgid;//삭제시 status=0
		$sql1=$mysqli->query($upq) or die("3:".$mysqli->error);	

		$data=array("result"=>1); 
		echo json_encode($data);
		exit;
	}
}else if($type=="send"){
	if($rs->fromuserid!=$_SESSION['loginValue']['SEMAIL']){
		$data=array("result"=>-1,"val"=>"본인이 보낸 쪽지가 아닙니다."); 
		echo json_encode($data);
		exit;
	}

	if($rs->status!=1){
		$data=array("result"=>-1,"val"=>"이미 상대방이 읽었기때문에 삭제할 수 없습니다."); 
		echo json_encode($data);
		exit;
	}else{
		$upq="update xc_msg set status=0 where num=".$msgid;//삭제시 status=0
		$sql1=$mysqli->query($upq) or die("3:".$mysqli->error);	

		$data=array("result"=>1); 
		echo json_encode($data);
		exit;
	}

}




 ?>