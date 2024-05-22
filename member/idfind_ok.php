<?php session_start();
include $_SERVER['DOCUMENT_ROOT']."/inc/dbcon.php";

//ini_set('display_errors', 1);
//ini_set('display_startup_errors', 1);
//error_reporting(E_ALL);

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require $_SERVER["DOCUMENT_ROOT"].'/phpmailer/src/Exception.php';
require $_SERVER["DOCUMENT_ROOT"].'/phpmailer/src/PHPMailer.php';
require $_SERVER["DOCUMENT_ROOT"].'/phpmailer/src/SMTP.php';

$username=strip_tags($_POST["username"]);
$email=strip_tags($_POST["email"]);
$ctype=strip_tags($_POST["ctype"]);

if(empty($username)){
	$data=array("result"=>-1,"val"=>"이름을 입력하세요."); 
 	echo json_encode($data);
 	exit;
}

if($ctype=="id"){

	$que="select * from member where nickName='".$username."'";
	$result = $mysqli->query($que) or die("2:".$mysqli->error);
	$rs = $result->fetch_object();
	if(!$rs->num){
		$data=array("result"=>-1,"val"=>"입력하신 이름의 아이디는 없습니다."); 
		echo json_encode($data);
		exit;
	}else{
		$un=explode("@",$rs->email);
		$userid=preg_replace('/(?<=.{1})./u','*',$un[0])."@".$un[1];
		$data=array("result"=>1,"val"=>$userid); 
		echo json_encode($data);
		exit;
	}

}else if($ctype=="pass"){

	$pass=passmaker(10);
	$passwd=hash('sha512',$pass);

	$que="select count(*) as cnt from member where nickName='".$username."' and email='".$email."'";
	$result = $mysqli->query($que) or die("query error => ".$mysqli->error);
	$rs = $result->fetch_object();
	if(!$rs->cnt){
		$data=array("result"=>-1,"val"=>"입력하신 계정의 회원 정보가 없습니다. 이름과 이메일을 다시 확인해주세요."); 
		echo json_encode($data);
		exit;
	}

	$que="select count(*) as cnt from sendmaillist where left(regdate,10)='".date("Y-m-d")."' and email='".$email."'";
	$result = $mysqli->query($que) or die("query error => ".$mysqli->error);
	$rs = $result->fetch_object();
	if($rs->cnt>=1){
		$data=array("result"=>-1,"val"=>"시스템 사정상 이메일은 하루에 한번만 발송됩니다. 내일 다시 이용해 주십시오."); 
		echo json_encode($data);
		exit;
	}

	$mail = new PHPMailer(true);
	$mail->Charset = 'UTF-8'; 
    $mail->isSMTP();                                            //Send using SMTP
    $mail->Host       = 'smtp-relay.sendinblue.com';                     //Set the SMTP server to send through smtp.naver.com
    $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
    $mail->Username   = 'hyoukhoon@gmail.com';                     //SMTP username
    $mail->Password   = 'wngdstXE0R8mybGN';                               //SMTP password
//    $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;            //Enable implicit TLS encryption
    $mail->Port       = 587;                                    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS` 465

    //Recipients
    $mail->setFrom($adminmail);
    $mail->addAddress($email);     //Add a recipient
    $mail->addReplyTo($adminmail);

    $mail->isHTML(true);                                  //Set email format to HTML
	$subject="[짜르]문의하신 비밀번호 안내드립니다.";
	$contents = "<link href=\"https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css\" rel=\"stylesheet\" integrity=\"sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM\" crossorigin=\"anonymous\">
<div class=\"card\">
  <h5 class=\"card-header\">짜르</h5>
  <div class=\"card-body\">
    <h5 class=\"card-title\">아이디/비밀번호 찾기</h5>
    <p class=\"card-text\">변경된 비밀번호는 다음과 같습니다. : ".$pass."</p>
    <a href=\"#\" class=\"btn btn-primary\">바로가기</a>
  </div>
</div>";
    $mail->Subject = "=?UTF-8?B?".base64_encode($subject)."?="."\r\n"; 
    $mail->Body    = $contents;
    $mail->AltBody = 'HTML을 지원하는 브라우저를 이용해주십시오.';
    if($mail->send()){

		$upq="update member set resetpass=1, wronglogin=0, passwd='".$passwd."' where email='".$email."'";
		$sql1=$mysqli->query($upq) or die("3:".$mysqli->error);

		$query="INSERT INTO `python`.`sendmaillist`
						(`email`,
						`ctype`)
						VALUES
						('$email',
						'$ctype')";
		$sql1=$mysqli->query($query) or die("3:".$mysqli->error);

		$data=array("result"=>1,"num"=>$num, "val"=>"성공"); 
		echo json_encode($data);
		exit;
	}else{
		$data=array("result"=>-1,"val"=>"죄송합니다. 현재 이메일을 발송할 수 없습니다. 다음에 다시 시도해주십시오."); 
		echo json_encode($data);
		exit;
	}

}else{
	$data=array("result"=>-1,"val"=>"필수값이 누락됐습니다."); 
 	echo json_encode($data);
 	exit;
}

 ?>