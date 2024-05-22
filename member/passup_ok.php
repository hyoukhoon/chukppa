<?php session_start();
include $_SERVER["DOCUMENT_ROOT"]."/inc/dbcon.php";

//ini_set('display_errors', 1);
//ini_set('display_startup_errors', 1);
//error_reporting(E_ALL);

$email=$_SESSION['loginValue']['SEMAIL'];
$prepasswd=strip_tags($_POST["prepasswd"]);
$passwd=strip_tags($_POST["passwd"]);
$repasswd=strip_tags($_POST["repasswd"]);

if($passwd!=$repasswd){
	echo "<script>alert('비밀번호와 비밀번호확인이 다릅니다. 다시한번 확인해주세요.');history.back();</script>";
    exit;
}

$result=passwdCheck($passwd);
if ($result[0] == false)
{
    echo "<script>alert('".$result[1]."');history.back();</script>";
    exit;
}

$passwd=hash('sha512',$passwd);
$prepasswd=hash('sha512',$prepasswd);

$query = "select count(*) as cnt from member m where m.email='".$email."' and m.passwd='".$prepasswd."'";
$result = $mysqli->query($query) or die("query error => ".$mysqli->error);
$rs = $result->fetch_object();

if($rs->cnt){

		$upq="update member set resetpass=0, passwd='".$passwd."' where email='".$email."'";
		$sql1=$mysqli->query($upq) or die("3:".$mysqli->error);

		if($sql1){
			echo "<script>alert('변경했습니다. 다시 로그인해 주십시오.');location.href='/member/login.php';</script>";
			exit;
		}else{
			echo "<script>alert('죄송합니다. 다음에 다시 시도해주십시오.');history.back();</script>";
			exit;
		}

}else{
		echo "<script>alert('현재 비밀번호가 맞지 않습니다. 다시 시도해 주십시오.');history.back();</script>";
		exit;
}




?>