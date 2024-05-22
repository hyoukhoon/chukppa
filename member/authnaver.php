<?php session_start();
include $_SERVER["DOCUMENT_ROOT"]."/inc/dbcon.php";
//ini_set('display_errors', 1);
//ini_set('display_startup_errors', 1);
//error_reporting(E_ALL);
$client_id = "gWeNFii9g_E70clnmtNO";
  $client_secret = "i2I0IxRtM7";
  $code = $_GET["code"];;
  $state = $_GET["state"];;
  $redirectURI = urlencode("https://www.zzarbang.com/member/authnaver.php");
  $url = "https://nid.naver.com/oauth2.0/token?grant_type=authorization_code&client_id=".$client_id."&client_secret=".$client_secret."&redirect_uri=".$redirectURI."&code=".$code."&state=".$state;
  $is_post = false;
  $ch = curl_init();
  curl_setopt($ch, CURLOPT_URL, $url);
  curl_setopt($ch, CURLOPT_POST, $is_post);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
  $headers = array();
  $response = curl_exec ($ch);
  $status_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
//  echo "status_code:".$status_code."<br>";
  curl_close ($ch);
  if($status_code == 200) {
    //echo $response;
	$url="https://openapi.naver.com/v1/nid/me";
	$res=json_decode($response);

	$headers = array(
    'Content-Type:application/json; charset=utf-8;',
    'Authorization: Bearer '. $res->access_token
	);

    $ch = curl_init(); // 리소스 초기화
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $response);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
  
    $output = curl_exec($ch); // 데이터 요청 후 수신
	$rs=json_decode($output, JSON_UNESCAPED_UNICODE);
//	json_encode($arrJson, JSON_UNESCAPED_UNICODE);
    curl_close($ch);  // 리소스 해제
	//echo "<pre>";
	//print_r($rs);

	$email =  $rs["response"]["email"];
	$name =  $rs["response"]["nickname"]??$rs["response"]["name"];

	$query3 = "select count(*) as cnt from member m where ifnull(signtype,'zzarbang')!='naver' and  m.email='".$email."'";
	$result3 = $mysqli->query($query3) or die("query error => ".$mysqli->error);
	$rs3 = $result3->fetch_object();
	if($rs3->cnt){
		echo "<script>alert('".$email."은 이미 가입된 이메일입니다. 로그인 메뉴를 이용해 주십시오.');history.back();</script>";
		exit;
	}

	$query2 = "select count(*) as cnt from member m where ifnull(signtype,'zzarbang')='naver' and  m.email='".$email."'";
	$result2 = $mysqli->query($query2) or die("query error => ".$mysqli->error);
	$rs2 = $result2->fetch_object();
	if($rs2->cnt){//이미 가입됨
		$query = "select *, (select count(*) from point_history p where p.userid=m.email and p.reason=1) as pcnt from member m where m.email='".$email."'";
		$result = $mysqli->query($query) or die("query error => ".$mysqli->error);
		$rs = $result->fetch_object();

		if(!$rs->pcnt){
			$reason=1;//최초로그인
			$mylevel=points_regist($email,$reason);
		}
		$upq="update member set lastLogin=now(), loginIp='".$_SERVER["REMOTE_ADDR"]."' where email='".$email."'";
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

		$sql="INSERT INTO member
        (uid, email, nickname, signtype, regDate, lastLogin, loginIp)
        VALUES('".$email."', '".$email."', '".$name."', 'naver', now(), now(), '".$_SERVER["REMOTE_ADDR"]."')";
		$result=$mysqli->query($sql) or die($mysqli->error);
		$_SESSION['loginValue']['SEMAIL']= $email;
		$_SESSION['loginValue']['SUNAME']= $name;
		$_SESSION['loginValue']['PHOTO']= "/img/human_icon.png";
	}

	echo "<script>alert('네이버 계정으로 로그인 되었습니다.');location.href='/';</script>";

  } else {
    echo "Error 내용:".$response;
  }

	
?>
