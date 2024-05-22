<?php include "/var/www/chukppa/inc/dbcon.php";
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$data='{"gmId": "G011", "gmTs": "240004", "_sbmInfo": {"_sbmInfo": {"debugMode": "false"}}}';
$data = json_decode($data);
//print_r($data);
//exit;

$url = "https://www.betman.co.kr/buyPsblGame/gameInfoInq.do";
$ch = curl_init();  //curl 초기화
curl_setopt($ch, CURLOPT_URL, $url); //url 입력
curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);  //요청 결과를 문자열로 반환 
curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);   //connection timeout 10초 
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); //원격 서버의 인증서가 유효한지 검사 안함
 
$response = curl_exec($ch);
echo $response;
curl_close($ch);
$rs=json_decode($response, true);
echo "<pre>";
print_r($rs);

?>