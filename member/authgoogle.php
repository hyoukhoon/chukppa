<?php session_start();
include $_SERVER["DOCUMENT_ROOT"]."/inc/dbcon.php";
//ini_set('display_errors', 1);
//ini_set('display_startup_errors', 1);
//error_reporting(E_ALL);
require_once $_SERVER["DOCUMENT_ROOT"].'/vendor/autoload.php';
 
// init configuration 
$clientID = '1050609388209-rns7cd3ceb1qi6cgk7mnavgs406qo1av.apps.googleusercontent.com';
$clientSecret = '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';
$redirectUri = 'https://www.chukppa.com/member/authgoogle.php';
  
// create Client Request to access Google API 
$client = new Google_Client();
$client->setClientId($clientID);
$client->setClientSecret($clientSecret);
$client->setRedirectUri($redirectUri);
$client->addScope("email");
$client->addScope("profile");
 
// authenticate code from Google OAuth Flow 
if (isset($_GET['code'])) {
  $token = $client->fetchAccessTokenWithAuthCode($_GET['code']);
  $client->setAccessToken($token['access_token']);
  
  // get profile info 
  $google_oauth = new Google_Service_Oauth2($client);
  $google_account_info = $google_oauth->userinfo->get();
  $email =  $google_account_info->email;
  $name =  $google_account_info->name;

	$query3 = "select count(*) as cnt from xc_member m where ifnull(signtype,'chukppa')!='google' and  m.email='".$email."'";
	$result3 = $mysqli->query($query3) or die("query error => ".$mysqli->error);
	$rs3 = $result3->fetch_object();
	if($rs3->cnt){
		echo "<script>alert('".$email."은 이미 가입된 이메일입니다. 로그인 메뉴를 이용해 주십시오.');history.back();</script>";
		exit;
	}

	$query2 = "select count(*) as cnt from xc_member m where ifnull(signtype,'chukppa')='google' and  m.email='".$email."'";
	$result2 = $mysqli->query($query2) or die("query error => ".$mysqli->error);
	$rs2 = $result2->fetch_object();
	if($rs2->cnt){//이미 가입됨
		$query = "select *, (select count(*) from xc_point_history p where p.userid=m.email and p.reason=1) as pcnt from xc_member m where m.email='".$email."'";
		$result = $mysqli->query($query) or die("query error => ".$mysqli->error);
		$rs = $result->fetch_object();

		if(!$rs->pcnt){
			$reason=1;//최초로그인
			$mylevel=points_regist($email,$reason);
		}
		$upq="update xc_member set lastLogin=now(), loginIp='".$_SERVER["REMOTE_ADDR"]."' where email='".$email."'";
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
	}else{

		$sql="INSERT INTO xc_member
        (uid, email, nickname, signtype, regDate, lastLogin, loginIp)
        VALUES('".$email."', '".$email."', '".$name."', 'google', now(), now(), '".$_SERVER["REMOTE_ADDR"]."')";
		$result=$mysqli->query($sql) or die($mysqli->error);
		$_SESSION['loginValue']['SEMAIL']= $email;
		$_SESSION['loginValue']['SUNAME']= $name;
		$_SESSION['loginValue']['PHOTO']= "/img/human_icon.png";
	}

	echo "<script>alert('구글계정으로 로그인 되었습니다.');location.href='/';</script>";
 
  // now you can use this profile info to create account in your website and make user logged in. 
} else {
  //echo "<a href='".$client->createAuthUrl()."'>Google Login</a>";
//	echo $client->createAuthUrl();
	header("Location: ".$client->createAuthUrl());
}
?>