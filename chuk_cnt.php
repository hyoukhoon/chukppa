<?php include "/var/www/chukppa/inc/dbcon.php";

//ini_set('display_errors', 1);
//ini_set('display_startup_errors', 1);
//error_reporting(E_ALL);

$query="select * from (
				SELECT * FROM xc_cboard where isdisp=1 order by num desc limit 40
				) cb order by rand() limit 10";
$result = $mysqli->query($query) or die("query error => ".$mysqli->error);
while($rs = $result->fetch_object()){
		$query2="update xc_cboard set cnt=cnt+1 where num=".$rs->num;
		$sql=$mysqli->query($query2) or die("3:".$mysqli->error);
		$uid = "soccer_".$rs->num;
		//엘라스틱
		$query3="select cnt from xc_cboard where num=".$rs->num;
		$result2 = $mysqli->query($query3) or die("query error => ".$mysqli->error);
		$rs2 = $result2->fetch_object();
		$data='{
		  "doc": {
			"site_cnt": '.$rs2->cnt.'
		  }
		}';

		$url="localhost:9200/chukppa/_update/".$uid;
		$resultela=elaCurl($url,$data);
//		echo $rs->subject."<br>";
}



exit;



?>