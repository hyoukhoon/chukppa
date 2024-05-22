<?php
include $_SERVER["DOCUMENT_ROOT"]."/inc/dbcon.php";

$selcn=$_POST['selcn'];
$sellg=$_POST['sellg'];
$seltm=$_POST['seltm'];
$today=date("Y-m-d");

$query="select * from xc_games2 where gamedate>='".$today."' and (hometeam='".$seltm."' or awayteam='".$seltm."') order by gamedate asc, gametime asc";
$result = $mysqli->query($query) or die("query error => ".$mysqli->error);
while($rs = $result->fetch_object()){
	$rsp[]=$rs;
}

$result="";
foreach($rsp as $p){
	if($p){
		$result.="<div class=\"col-lg-6\" style=\"margin-top:20px;\" id=\"searchteam\">
			<div class=\"card mb-4\">
				<div class=\"card-header\" style=\"background-color: #000;color:#fff;\">
					▶ ".changename($p->league)."
				</div>
				<div class=\"card-body\">
							<p class=\"card-text\">".$p->gamedate." ".substr($p->gametime,0,5)." (".w_date(date("w",strtotime($p->gamedate))).")<br>".$p->hometeam." - ".$p->awayteam;

								if($p->status=="RESULT"){
									$result.="<br>";
									$result.="[종료] ".$p->score;
								}else if($p->status=="LIVE"){
									$result.=" <font color='blue'>[".$p->playtime."] ".$p->score."</font>";
								}else if($p->status=="POSTPONED"){
									$result.=" <font color='red'>[연기]</font>";
								}else if($p->status=="SUSPENDED"){
									$result.=" <font color='red'>[순연]</font>";
								}
			$result.="
							</p>
				</div>
			</div>
		</div>";
	}
}

echo $result;

?>