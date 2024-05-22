<?php
include $_SERVER["DOCUMENT_ROOT"]."/inc/dbcon.php";

$userid=strip_tags($_POST["userid"]);
$username=strip_tags($_POST["username"]);
$agree=strip_tags($_POST["agree"]);
//$email=strip_tags($_POST["email"]);
$passwd=strip_tags($_POST["passwd"]);
$repasswd=strip_tags($_POST["repasswd"]);

if(!$userid){
	echo "<script>alert('아이디는 필수값입니다.');history.back();</script>";
    exit;
}

if(!$username){
	echo "<script>alert('닉네임은 필수값입니다.');history.back();</script>";
    exit;
}

if(!$passwd){
	echo "<script>alert('패스워드는 필수값입니다.');history.back();</script>";
    exit;
}


if($passwd!=$repasswd){
	echo "<script>alert('비밀번호와 비밀번호확인이 다릅니다. 다시한번 확인해주세요.');history.back();</script>";
    exit;
}

if(!$agree){
	echo "<script>alert('약관에 동의하지 않으면 가입할 수 없습니다.');history.back();</script>";
    exit;
}


$result=passwdCheck($passwd);
if ($result[0] == false)
{
    echo "<script>alert('".$result[1]."');history.back();</script>";
    exit;
}

$passwd=hash('sha512',$passwd);

$query = "select count(*) as cnt from xc_member m where m.email='".$userid."'";
$result = $mysqli->query($query) or die("query error => ".$mysqli->error);
$rs = $result->fetch_object();
if($rs->cnt){
	echo "<script>alert('이미 가입된 이메일입니다. 아이디/비밀번호찾기를 이용해 주십시오.');history.back();</script>";
    exit;
}


$sql="INSERT INTO xc_member
        (uid, email, nickname, passwd, regDate, lastLogin, loginIp)
        VALUES('".$userid."', '".$userid."', '".$username."', '".$passwd."', now(), now(), '".$_SERVER["REMOTE_ADDR"]."')";
if($userid){
	$result=$mysqli->query($sql) or die($mysqli->error);
}


if($result){

    echo "<script>alert('가입을 환영합니다.');location.href='/member/login.php';</script>";
    exit;
}else{
    echo "<script>alert('회원가입에 실패했습니다.');history.back();</script>";
    exit;
}


?>