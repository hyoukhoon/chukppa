<?php include "/var/www/chukppa/inc/dbcon.php";

//ini_set('display_errors', 1);
//ini_set('display_startup_errors', 1);
//error_reporting(E_ALL);
/*
$query="SELECT * FROM xc_soccerworld order by sid asc";
$result = $mysqli->query($query) or die("query error => ".$mysqli->error);
while($rs = $result->fetch_object()){
	$uid=$rs->pid.$rs->title.$rs->gubun;
	$uid=hash("sha256", $uid);
	$query2="update xc_soccerworld set uid='".$uid."' where sid=".$rs->sid;
//	echo $query2."<br>";
	$sql=$mysqli->query($query2) or die("3:".$mysqli->error);
}

exit;
*/
$query="SELECT country FROM xc_games group by country order by country";//국가 가져오기
$result = $mysqli->query($query) or die("query error => ".$mysqli->error);
while($rs = $result->fetch_object()){
		$uid=$rs->country.'c';
		$uid=hash("sha256", $uid);
		$query2="insert into xc_soccerworld (title,gubun,uid) values ('".addslashes($rs->country)."','c','".$uid."') ON DUPLICATE KEY UPDATE gubun='c'";
		$sql=$mysqli->query($query2) or die("3:".$mysqli->error);
}


$query="SELECT * FROM xc_soccerworld where gubun='c'";
$result = $mysqli->query($query) or die("query error => ".$mysqli->error);
while($rs = $result->fetch_object()){
		$query3="select league from xc_games where country='".$rs->title."' group by league order by league asc";//국가별 리그 가져옴
		$result3 = $mysqli->query($query3) or die("query error => ".$mysqli->error);
		while($rs3 = $result3->fetch_object()){
				$uid=$rs->sid.$rs3->league.'e';
				$uid=hash("sha256", $uid);
				$query2="insert into xc_soccerworld (pid,title,gubun,uid) values (".$rs->sid.",'".addslashes($rs3->league)."','e', '".$uid."') ON DUPLICATE KEY UPDATE gubun='e'";
//				echo $query2."<br>";
				$sql=$mysqli->query($query2) or die("3:".$mysqli->error);
		}
}

$query="SELECT sid,title FROM xc_soccerworld where gubun='c'";
$result = $mysqli->query($query) or die("query error => ".$mysqli->error);
while($rs = $result->fetch_object()){
	$query2="select sid,title from xc_soccerworld where gubun='e' and pid='".$rs->sid."'";
	$result2 = $mysqli->query($query2) or die("query error => ".$mysqli->error);
	while($rs2 = $result2->fetch_object()){
		$query3="select hometeam from xc_games where country='".addslashes($rs->title)."' and league='".addslashes($rs2->title)."' group by hometeam order by hometeam asc";
		$result3 = $mysqli->query($query3) or die("query error => ".$mysqli->error);
		while($rs3 = $result3->fetch_object()){
				$uid=$rs2->sid.$rs3->hometeam.'t';
				$uid=hash("sha256", $uid);
				$query5="insert into xc_soccerworld (pid,title,gubun,uid) values (".$rs2->sid.",'".addslashes($rs3->hometeam)."','t', '".$uid."') ON DUPLICATE KEY UPDATE gubun='t'";
				$sql=$mysqli->query($query5) or die("3:".$mysqli->error);

		}
	}
}

exit;



?>