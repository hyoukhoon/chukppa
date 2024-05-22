<?php session_start();
include $_SERVER["DOCUMENT_ROOT"]."/inc/dbcon.php";
//ini_set('display_errors', 1);
//ini_set('display_startup_errors', 1);
//error_reporting(E_ALL);

$returnCode = $_GET["code"]; 
$restAPIKey = "d4efbf028979714a5a4a7c5138e08997"; 
$callbacURI = urlencode("https://www.chukppa.com/member/authkakao.php"); // 본인의 Call Back URL을 입력해주세요
// API 요청 URL
$returnUrl = "https://kauth.kakao.com/oauth/token?grant_type=authorization_code&client_id=".$restAPIKey."&redirect_uri=".$callbacURI."&code=".$returnCode;

	$is_post = false;
  $ch = curl_init();
  curl_setopt($ch, CURLOPT_URL, $returnUrl);
  curl_setopt($ch, CURLOPT_POST, $is_post);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
  $headers = array();
  $response = curl_exec ($ch);
  $status_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
//  echo "status_code:".$status_code."<br>";
  curl_close ($ch);
  $res=json_decode($response);
//  echo "<pre>";
//	print_r($res);
//	exit;

$curl = 'curl -v -X GET https://kapi.kakao.com/v2/user/me -H "Authorization: Bearer '.$res->access_token.'"';
$info = shell_exec($curl);
$info_arr = json_decode($info, true);
//echo "<pre>";
//print_r($info_arr);
//exit;


	$email =  $info_arr["kakao_account"]["email"];
	$name =  $info_arr["kakao_account"]["profile"]["nickname"];

	$query3 = "select count(*) as cnt from xc_member m where ifnull(signtype,'chukppa')!='kakao' and  m.email='".$email."'";
	$result3 = $mysqli->query($query3) or die("query error => ".$mysqli->error);
	$rs3 = $result3->fetch_object();
	if($rs3->cnt){
		echo "<script>alert('".$email."은 이미 가입된 이메일입니다. 로그인 메뉴를 이용해 주십시오.');history.back();</script>";
		exit;
	}

	$query2 = "select count(*) as cnt from xc_member m where ifnull(signtype,'chukppa')='kakao' and  m.email='".$email."'";
	$result2 = $mysqli->query($query2) or die("query error => ".$mysqli->error);
	$rs2 = $result2->fetch_object();
	if($rs2->cnt){//이미 가입됨
		$query = "select *, (select count(*) from point_history p where p.userid=m.email and p.reason=1) as pcnt from xc_member m where m.email='".$email."'";
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
        VALUES('".$email."', '".$email."', '".$name."', 'kakao', now(), now(), '".$_SERVER["REMOTE_ADDR"]."')";
		$result=$mysqli->query($sql) or die($mysqli->error);
		$_SESSION['loginValue']['SEMAIL']= $email;
		$_SESSION['loginValue']['SUNAME']= $name;
		$_SESSION['loginValue']['PHOTO']= "/img/human_icon.png";
	}

	echo "<script>alert('카카오 계정으로 로그인 되었습니다.');location.href='/';</script>";


	
?>
