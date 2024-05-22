<?php
include $_SERVER["DOCUMENT_ROOT"]."/inc/dbcon.php";

$selcn=$_POST['selcn'];

$query="select * from xc_soccerworld where gubun='e' and pid=".$selcn."  order by ord desc, title";
$result = $mysqli->query($query) or die("query error => ".$mysqli->error);
while($rs = $result->fetch_object()){
	$rsp[]=$rs;
}

$data="<option value=''>리그선택</option>";
foreach($rsp as $r){
	if($r){
		$data.="<option value='".$r->sid."'>".changename($r->title)."</option>";
	}
}

echo $data;

?>