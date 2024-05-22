<?php
include $_SERVER["DOCUMENT_ROOT"]."/inc/dbcon.php";

$que="select * from xc_cboard order by num asc";
$result = $mysqli->query($que) or die("22:".$mysqli->error);
while($rs = $result->fetch_object()){

	$uid = "soccer_".$rs->num;
	$dates = date("Y/m/d H:i:s", strtotime($rs->reg_date));
	$url = "/board/view.php?num=".$rs->num;
	$data = '{
		"username": "'.$rs->name.'",
		"multi": "soccer",
		"thumbnail": "'.$rs->thumb.'",
		"subject": "'.$rs->subject.'",
		"url":  "'.$url.'",
		"site_num": '.$rs->num.',
		"userid":"'.$rs->email.'",
		"site_reg_date": "'.$dates.'",
		"site_cnt": '.$rs->cnt.',
		"uid": "'.$uid.'"
	}';
	$url="localhost:9200/chukppa/_doc/".$uid;
//	$rs=elaCurl($url,$data);
	echo $data."<br>";
}

?>