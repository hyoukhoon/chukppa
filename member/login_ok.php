<?php ob_start();
session_start();
include $_SERVER["DOCUMENT_ROOT"]."/inc/dbcon.php";

//ini_set('display_errors', 1);
//ini_set('display_startup_errors', 1);
//error_reporting(E_ALL);

$userid=strip_tags($_POST["userid"]);
$passwd=strip_tags($_POST["passwd"]);
$passwd=hash('sha512',$passwd);
$savelogin=strip_tags($_POST["savelogin"]);
$moveurl=$_POST['moveurl']??"/";

$query = "select *, (select count(*) from xc_point_history p where p.userid=m.email and p.reason=1) as pcnt from xc_member m where m.email='".$userid."' and m.passwd='".$passwd."'";
$result = $mysqli->query($query) or die("query error => ".$mysqli->error);
$rs = $result->fetch_object();

if($rs){

	if($rs->wronglogin>=5){
		echo "<script>alert('비밀번호를 5회이상 틀렸습니다. 아이디/비밀번호 찾기 메뉴를 이용해 비밀번호를 변경해 주세요.');location.href='/member/idfinder.php'</script>";
		exit;
	}

	if($rs->ismember==0){
		echo "<script>alert('탈퇴한 회원입니다. 로그인할 수 없습니다.');location.href='/'</script>";
		exit;
	}

	if($rs->ismember==-1){
		echo "<script>alert('로그인할 수 없는 아이디입니다. 관리자에게 문의하십시오.');location.href='/'</script>";
		exit;
	}

	if($rs->resetpass){
		$_SESSION['loginValue']['SEMAIL']= $rs->email;
		echo "<script>alert('비밀번호가 초기화 되었으므로 변경하셔야합니다.');location.href='/member/passup.php';</script>";
	    exit;
	}
	if(!$rs->pcnt){
		$reason=1;//최초로그인
		$mylevel=points_regist($userid,$reason);
	}
	$upq="update xc_member set lastLogin=now(), loginIp='".$_SERVER["REMOTE_ADDR"]."' where email='".$userid."'";
	$sql1=$mysqli->query($upq) or die("3:".$mysqli->error);

    $_SESSION['loginValue']['SEMAIL']= $rs->email;
    $_SESSION['loginValue']['SUNAME']= $rs->nickName;
	if($rs->isAuth){
		$_SESSION['loginValue']['SUAUTH']= $rs->isAuth;
	}
	if($rs->photo){
		$_SESSION['loginValue']['PHOTO']= "/board/upImages/thumb/".$rs->photo;
	}else{
		$_SESSION['loginValue']['PHOTO']= "/img/human_icon.png";
	}

	if($savelogin){
		setcookie("semail", $rs->email, time() + (86400*7), "/");
		setcookie("suname", $rs->nickName, time() + (86400*7), "/");
		setcookie("suphoto", $_SESSION['loginValue']['PHOTO'], time() + (86400*7), "/");
	}

    echo "<script>alert('로그인 되었습니다.');location.href='".$moveurl."';</script>";
    exit;

}else{

	$query2 = "select num, wronglogin from xc_member m where m.email='".$userid."'";
	$result2 = $mysqli->query($query2) or die("query error => ".$mysqli->error);
	$rs2 = $result2->fetch_object();

	if($rs2->wronglogin>=5){
		echo "<script>alert('비밀번호를 5회이상 틀렸습니다. 아이디/비밀번호 찾기 메뉴를 이용해 비밀번호를 변경해 주세요.');location.href='/member/idfinder.php'</script>";
		exit;
	}else{
		if($rs2->num){
			$upq="update xc_member set wronglogin=wronglogin+1 where email='".$userid."'";
			$sql1=$mysqli->query($upq) or die("3:".$mysqli->error);
		}
		echo "<script>alert('아이디나 암호가 틀렸습니다. 다시한번 확인해주십시오.');history.back();</script>";
		exit;
	}
}


?>