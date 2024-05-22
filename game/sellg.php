<?php
include $_SERVER["DOCUMENT_ROOT"]."/inc/dbcon.php";

$selcn=$_POST['selcn'];
$sellg=$_POST['sellg'];
$today=date("Y-m-d");

$query="select * from xc_soccerworld where gubun='t' and pid=".$sellg."  order by ord desc, title";
$result = $mysqli->query($query) or die("query error => ".$mysqli->error);
while($rs = $result->fetch_object()){
	$rsp[]=$rs;
}

$result="<option value=''>팀선택</option>";
foreach($rsp as $r){
	if($r){
		$result.="<option value='".$r->title."'>".changename($r->title)."</option>";
	}
}

echo $result;

?>