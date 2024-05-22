<?php session_start();
include $_SERVER["DOCUMENT_ROOT"]."/inc/dbcon.php";

//ini_set('display_errors', 1);
//ini_set('display_startup_errors', 1);
//error_reporting(E_ALL);

$email=$_SESSION['loginValue']['SEMAIL'];
$passwd=strip_tags($_POST["passwd"]);

if(!$passwd){
	echo "<script>alert('비밀번호를 입력하세요.');history.back();</script>";
    exit;
}

$passwd=hash('sha512',$passwd);

$query = "select count(*) as cnt from member m where m.email='".$email."' and m.passwd='".$passwd."'";
$result = $mysqli->query($query) or die("query error => ".$mysqli->error);
$rs = $result->fetch_object();

if($rs->cnt){

		$upq="update member set ismember=0 where email='".$email."'";
		$sql1=$mysqli->query($upq) or die("3:".$mysqli->error);

		if($sql1){
			session_destroy();
			echo "<script>alert('탈퇴 처리했습니다. 그동안 이용해주셔서 감사합니다..');location.href='/member/login.php';</script>";
			exit;
		}else{
			echo "<script>alert('죄송합니다. 다음에 다시 시도해주십시오.');history.back();</script>";
			exit;
		}

}else{
		echo "<script>alert('비밀번호가 맞지 않습니다. 다시 확인해 주십시오.');history.back();</script>";
		exit;
}




?>