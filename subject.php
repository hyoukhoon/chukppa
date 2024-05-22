<?php
// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL);

$today=date("Y-m-d");
$today_date=date("Y-m-d H:i:s");
$ord=$_GET['ord'];
$orderby=$_GET['orderby']??1;
$sword=$_GET['sword'];
$gubun=$_GET['gubun'];


$LIMIT=$_GET['LIMIT']?$_GET['LIMIT']:50;
$page=$_GET['page']?$_GET['page']:0;
$start_page=($page)*$LIMIT;
$end_page=$LIMIT;
$ps=$LIMIT;//한페이지에 몇개를 표시할지
$sub_size=7;//아래에 나오는 페이징은 몇개를 할지

if($orderby==1){
    $ord='{"site_reg_date":"desc"}';
//	$todate=date("Y/m/d")." 00:00:00";
}else if($orderby==2){
    $ord='{"site_cnt":"desc"}';
	$todate=date("Y/m/d")." 00:00:00";
}

//$fromdate=date("Y/m/d H:i:s", strtotime("-24 hours"));
$fromdate=date("Y/m/d")." 00:00:00";
$todate=date("Y/m/d")." 23:59:59";

$subject="";
$pageSize=1000;

for($i=0;$i<=50;$i++){

	$page=$i*$pageSize;

	$json='
	{
	   "query" :{
		  "bool" : {             
			  "must" : [{"range": {"site_reg_date": {"gte": "'.$fromdate.'","lte": "'.$todate.'"}}}]
		  }
		},
		"size": '.$pageSize.',	
		"from": '.$page.',	
		"sort": {"site_reg_date":"desc"}
	 }
	';

	//echo $json."<br>";

	
	$url="http://localhost:9200/ebo/_search?pretty";

	  $ch = curl_init(); // 리소스 초기화
	  curl_setopt($ch, CURLOPT_URL, $url);
	  curl_setopt($ch, CURLOPT_USERPWD, "elastic:soon06051007");
	  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	  curl_setopt($ch, CURLOPT_POST, true);
	  curl_setopt($ch, CURLOPT_POSTFIELDS, $json);
	  curl_setopt($ch, CURLOPT_HTTPHEADER, array(
		'Content-Type: application/json'
	  ));
	 
	  $output = curl_exec($ch); // 데이터 요청 후 수신
	  $output=json_decode($output);
	  curl_close($ch);  // 리소스 해제
	  $total=$output->hits->total->value;

	  if($total){

		foreach($output->hits->hits as $p){

			$subject.=$p->_source->subject." ";
			//$subject.=$p->_source->site_reg_date." ";

		}

	  }

}

//echo $subject;


$myfile = fopen("/home/ebo/.local/lib/python3.8/site-packages/konlpy/data/corpus/kolaw/subject.txt", "w") or die("Unable to open file!");
fwrite($myfile, $subject);
fclose($myfile);


?>
