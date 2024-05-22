<?php
include $_SERVER["DOCUMENT_ROOT"]."/inc/header.php";

//ini_set('display_errors', 1);
//ini_set('display_startup_errors', 1);
//error_reporting(E_ALL);
$today=date("Y-m-d");
$query="select gamedate from xc_games where gamedate>='".$today."' group by gamedate order by gamedate asc";
$result = $mysqli->query($query) or die("query error => ".$mysqli->error);
while($rs = $result->fetch_object()){
	$rsp[]=$rs;
}

$query="select * from xc_games where gamedate>='".$today."' and (hometeam='토트넘' or awayteam='토트넘' or hometeam='토트넘 (Eng)' or awayteam='토트넘 (Eng)') order by gametime asc";
$result = $mysqli->query($query) or die("query error => ".$mysqli->error);
while($rs = $result->fetch_object()){
	$rsc1[]=$rs;
}

$query="select * from xc_games where gamedate>='".$today."' and (hometeam='울버햄튼' or awayteam='울버햄튼' or hometeam='울버햄튼 (Eng)' or awayteam='울버햄튼 (Eng)') order by gametime asc";
$result = $mysqli->query($query) or die("query error => ".$mysqli->error);
while($rs = $result->fetch_object()){
	$rsc2[]=$rs;
}

$query="select * from xc_games where gamedate>='".$today."' and (hometeam='바이에른 뮌헨' or awayteam='바이에른 뮌헨' or hometeam='바이에른 뮌헨 (Ger)' or awayteam='바이에른 뮌헨 (Ger)') order by gametime asc";
$result = $mysqli->query($query) or die("query error => ".$mysqli->error);
while($rs = $result->fetch_object()){
	$rsc3[]=$rs;
}

$query="select * from xc_games where gamedate>='".$today."' and (hometeam='파리생제르망' or awayteam='파리생제르망' or hometeam='파리생제르망 (Fra)' or awayteam='파리생제르망 (Fra)') order by gametime asc";
$result = $mysqli->query($query) or die("query error => ".$mysqli->error);
while($rs = $result->fetch_object()){
	$rsc4[]=$rs;
}

$query="select * from xc_games where gamedate>='".$today."' and (hometeam='노팅엄' or awayteam='노팅엄' or hometeam='노팅엄 (Eng)' or awayteam='노팅엄 (Eng)') order by gametime asc";
$result = $mysqli->query($query) or die("query error => ".$mysqli->error);
while($rs = $result->fetch_object()){
	$rsc5[]=$rs;
}

$query="select * from xc_games where gamedate>='".$today."' and (hometeam='미튈란' or awayteam='미튈란' or hometeam='미튈란 (Den)' or awayteam='미튈란 (Den)') order by gametime asc";
$result = $mysqli->query($query) or die("query error => ".$mysqli->error);
while($rs = $result->fetch_object()){
	$rsc6[]=$rs;
}

$query="select * from xc_games where gamedate>='".$today."' and (hometeam='올림피아코스' or awayteam='올림피아코스' or hometeam='올림피아코스 (Gre)' or awayteam='올림피아코스 (Gre)') order by gametime asc";
$result = $mysqli->query($query) or die("query error => ".$mysqli->error);
while($rs = $result->fetch_object()){
	$rsc7[]=$rs;
}

$query="select * from xc_games where gamedate>='".$today."' and (hometeam='셀틱' or awayteam='셀틱' or hometeam='셀틱 (Sco)' or awayteam='셀틱 (Sco)') order by gametime asc";
$result = $mysqli->query($query) or die("query error => ".$mysqli->error);
while($rs = $result->fetch_object()){
	$rsc8[]=$rs;
}

$query="select * from xc_games where gamedate>='".$today."' and (hometeam='헨트' or awayteam='헨트' or hometeam='헨트 (Bel)' or awayteam='헨트 (Bel)') order by gametime asc";
$result = $mysqli->query($query) or die("query error => ".$mysqli->error);
while($rs = $result->fetch_object()){
	$rsc9[]=$rs;
}

$query="select * from xc_games where gamedate>='".$today."' and (hometeam='브렌트퍼드' or awayteam='브렌트퍼드' or hometeam='브렌트퍼드 (Eng)' or awayteam='브렌트퍼드 (Eng)') order by gametime asc";
$result = $mysqli->query($query) or die("query error => ".$mysqli->error);
while($rs = $result->fetch_object()){
	$rsc10[]=$rs;
}

$query="select * from xc_games where gamedate>='".$today."' and (hometeam='마인츠' or awayteam='마인츠' or hometeam='마인츠 (Ger)' or awayteam='마인츠 (Ger)') order by gametime asc";
$result = $mysqli->query($query) or die("query error => ".$mysqli->error);
while($rs = $result->fetch_object()){
	$rsc11[]=$rs;
}

$query="select * from xc_games where gamedate>='".$today."' and (hometeam='VfB 슈투트가르트' or awayteam='VfB 슈투트가르트' or hometeam='VfB 슈투트가르트 (Ger)' or awayteam='VfB 슈투트가르트 (Ger)') order by gametime asc";
$result = $mysqli->query($query) or die("query error => ".$mysqli->error);
while($rs = $result->fetch_object()){
	$rsc12[]=$rs;
}


?>
<style>
	.page-link a{color: black;}
</style>

<main class="container">

<div class="row">

<?php 
	foreach($rsp as $p){
?>
		<div style="width:130px;" class="col-lg-2" <?php
if(isMobile()){
		?>style="padding-top:20px;"
		<?php }?>
		>
			<div class="card mb-4">
				<div class="card-header" style="background-color: #000;color:#fff;">
					<?php echo date("d",strtotime($p->gamedate))."일 (".w_date(date("w",strtotime($p->gamedate))).")";?>
				</div>
				<div class="card-body">
					<?php
						foreach($rsc1 as $r1){
							if($r1->gamedate==$p->gamedate){
								echo "★ 손흥민";
							}
						}
					?>
					<?php
						foreach($rsc2 as $r2){
							if($r2->gamedate==$p->gamedate){
								echo "★ 황희찬";
							}
						}
					?>
						<?php
						foreach($rsc3 as $r3){
							if($r3->gamedate==$p->gamedate){
								echo "★ 김민재";
							}
						}
					?>
					<?php
						foreach($rsc4 as $r4){
							if($r4->gamedate==$p->gamedate){
								echo "★ 이강인";
							}
						}
					?>
					<?php
						foreach($rsc5 as $r5){
							if($r5->gamedate==$p->gamedate){
								echo "★ 황의조";
							}
						}
					?>
					<?php
						foreach($rsc6 as $r6){
							if($r6->gamedate==$p->gamedate){
								echo "★ 조규성";
							}
						}
					?>
					<?php
						foreach($rsc7 as $r7){
							if($r7->gamedate==$p->gamedate){
								echo "★ 황인범";
							}
						}
					?>
					<?php
						foreach($rsc8 as $r8){
							if($r8->gamedate==$p->gamedate){
								echo "★ 양현준";
							}
						}
					?>
					<?php
						foreach($rsc9 as $r9){
							if($r9->gamedate==$p->gamedate){
								echo "★ 홍현석";
							}
						}
					?>
					<?php
						foreach($rsc10 as $r10){
							if($r10->gamedate==$p->gamedate){
								echo "★ 박지수";
							}
						}
					?>
					<?php
						foreach($rsc11 as $r11){
							if($r11->gamedate==$p->gamedate){
								echo "★ 이재성";
							}
						}
					?>
					<?php
						foreach($rsc12 as $r12){
							if($r12->gamedate==$p->gamedate){
								echo "★ 정우영";
							}
						}
					?>

				</div>
			</div>
		</div>
<?php }?>



<div class="col-lg-6" <?php
if(isMobile()){
		?>style="padding-top:20px;"
		<?php }?>
		>
			
			<div class="card mb-4">

				<div class="card-header" style="background-color: #000;color:#fff;">
					★ 손흥민
				</div>
				<div class="card-body">
					<?php
					$kk=0;
					foreach($rsc1 as $r1){
					?>
					<?php if($kk){echo "<hr>";}?>
					<p class="card-text">
						<?php
						echo "▶ ".$r1->league;
						echo " - ".$r1->gamedate." ".substr($r1->gametime,0,5)." (".w_date(date("w",strtotime($r1->gamedate))).")<br>";
						echo $r1->hometeam." - ".$r1->awayteam;
						if($r1->status=="종료"){
							echo "<br>";
							echo "[종료] ".$r1->score;
						}else if($r1->status=="진행중"){
							echo " <font color='blue'>[".$r1->playtime."] ".$r1->score."</font>";
						}else if($r1->status=="연기"){
							echo " <font color='red'>[연기]</font>";
						}else if($r1->status=="취소"){
							echo " <font color='red'>[취소]</font>";
						}
					?>
					</p>
					<?php 
					$kk++;
					}?>
				</div>
			</div>
		</div>

		<div class="col-lg-6">
			<div class="card mb-4">
				<div class="card-header" style="background-color: #000;color:#fff;">
					★ 황희찬
				</div>
				<div class="card-body">
					<?php
					$kk=0;
					foreach($rsc2 as $r2){
					?>
					<?php if($kk){echo "<hr>";}?>
					<p class="card-text">
						<?php
						echo "▶ ".$r2->league;
						echo " - ".$r2->gamedate." ".substr($r2->gametime,0,5)." (".w_date(date("w",strtotime($r2->gamedate))).")<br>";
						echo $r2->hometeam." - ".$r2->awayteam;
						if($r2->status=="종료"){
							echo "<br>";
							echo "[종료] ".$r2->score;
						}else if($r2->status=="진행중"){
							echo " <font color='blue'>[".$r2->playtime."] ".$r2->score."</font>";
						}else if($r2->status=="연기"){
							echo " <font color='red'>[연기]</font>";
						}else if($r2->status=="취소"){
							echo " <font color='red'>[취소]</font>";
						}
					?>
					</p>
					<?php 
					$kk++;
					}?>
				</div>
			</div>
		</div>

		<div class="col-lg-6">
			<div class="card mb-4">
				<div class="card-header" style="background-color: #000;color:#fff;">
					★ 김민재
				</div>
				<div class="card-body">
					<?php
					$kk=0;
					foreach($rsc3 as $r3){
					?>
					<?php if($kk){echo "<hr>";}?>
					<p class="card-text">
						<?php
						echo "▶ ".$r3->league;
						echo " - ".$r3->gamedate." ".substr($r3->gametime,0,5)." (".w_date(date("w",strtotime($r3->gamedate))).")<br>";
						echo $r3->hometeam." - ".$r3->awayteam;
						if($r3->status=="종료"){
							echo "<br>";
							echo "[종료] ".$r3->score;
						}else if($r3->status=="진행중"){
							echo " <font color='blue'>[".$r3->playtime."] ".$r3->score."</font>";
						}else if($r3->status=="연기"){
							echo " <font color='red'>[연기]</font>";
						}else if($r3->status=="취소"){
							echo " <font color='red'>[취소]</font>";
						}
					?>
					</p>
					<?php 
					$kk++;
					}?>
				</div>
			</div>
		</div>

		<div class="col-lg-6">
			<div class="card mb-4">
				<div class="card-header" style="background-color: #000;color:#fff;">
					★ 이강인
				</div>
				<div class="card-body">
					<?php
					$kk=0;
					foreach($rsc4 as $r4){
					?>
					<?php if($kk){echo "<hr>";}?>
					<p class="card-text">
						<?php
						echo "▶ ".$r4->league;
						echo " - ".$r4->gamedate." ".substr($r4->gametime,0,5)." (".w_date(date("w",strtotime($r4->gamedate))).")<br>";
						echo $r4->hometeam." - ".$r4->awayteam;
						if($r4->status=="종료"){
							echo "<br>";
							echo "[종료] ".$r4->score;
						}else if($r4->status=="진행중"){
							echo " <font color='blue'>[".$r4->playtime."] ".$r4->score."</font>";
						}else if($r4->status=="연기"){
							echo " <font color='red'>[연기]</font>";
						}else if($r4->status=="취소"){
							echo " <font color='red'>[취소]</font>";
						}
					?>
					</p>
					<?php 
					$kk++;
					}?>
				</div>
			</div>
		</div>

		<div class="col-lg-6">
			<div class="card mb-4">
				<div class="card-header" style="background-color: #000;color:#fff;">
					★ 황의조
				</div>
				<div class="card-body">
					<?php
					$kk=0;
					foreach($rsc5 as $r5){
					?>
					<?php if($kk){echo "<hr>";}?>
					<p class="card-text">
						<?php
						echo "▶ ".$r5->league;
						echo " - ".$r5->gamedate." ".substr($r5->gametime,0,5)." (".w_date(date("w",strtotime($r5->gamedate))).")<br>";
						echo $r5->hometeam." - ".$r5->awayteam;
						if($r5->status=="종료"){
							echo "<br>";
							echo "[종료] ".$r5->score;
						}else if($r5->status=="진행중"){
							echo " <font color='blue'>[".$r5->playtime."] ".$r5->score."</font>";
						}else if($r5->status=="연기"){
							echo " <font color='red'>[연기]</font>";
						}else if($r5->status=="취소"){
							echo " <font color='red'>[취소]</font>";
						}
					?>
					</p>
					<?php 
					$kk++;
					}?>
				</div>
			</div>
		</div>

		<div class="col-lg-6">
			<div class="card mb-4">
				<div class="card-header" style="background-color: #000;color:#fff;">
					★ 조규성
				</div>
				<div class="card-body">
					<?php
					$kk=0;
					foreach($rsc6 as $r6){
					?>
					<?php if($kk){echo "<hr>";}?>
					<p class="card-text">
						<?php
						echo "▶ ".$r6->league;
						echo " - ".$r6->gamedate." ".substr($r6->gametime,0,5)." (".w_date(date("w",strtotime($r6->gamedate))).")<br>";
						echo $r6->hometeam." - ".$r6->awayteam;
						if($r6->status=="종료"){
							echo "<br>";
							echo "[종료] ".$r6->score;
						}else if($r6->status=="진행중"){
							echo " <font color='blue'>[".$r6->playtime."] ".$r6->score."</font>";
						}else if($r6->status=="연기"){
							echo " <font color='red'>[연기]</font>";
						}else if($r6->status=="취소"){
							echo " <font color='red'>[취소]</font>";
						}
					?>
					</p>
					<?php 
					$kk++;
					}?>
				</div>
			</div>
		</div>

		<div class="col-lg-6">
			<div class="card mb-4">
				<div class="card-header" style="background-color: #000;color:#fff;">
					★ 황인범
				</div>
				<div class="card-body">
					<?php
					$kk=0;
					foreach($rsc7 as $r7){
					?>
					<?php if($kk){echo "<hr>";}?>
					<p class="card-text">
						<?php
						echo "▶ ".$r7->league;
						echo " - ".$r7->gamedate." ".substr($r7->gametime,0,5)." (".w_date(date("w",strtotime($r7->gamedate))).")<br>";
						echo $r7->hometeam." - ".$r7->awayteam;
						if($r7->status=="종료"){
							echo "<br>";
							echo "[종료] ".$r7->score;
						}else if($r7->status=="진행중"){
							echo " <font color='blue'>[".$r7->playtime."] ".$r7->score."</font>";
						}else if($r7->status=="연기"){
							echo " <font color='red'>[연기]</font>";
						}else if($r7->status=="취소"){
							echo " <font color='red'>[취소]</font>";
						}
					?>
					</p>
					<?php 
					$kk++;
					}?>
				</div>
			</div>
		</div>

		<div class="col-lg-6">
			<div class="card mb-4">
				<div class="card-header" style="background-color: #000;color:#fff;">
					★ 양현준, 오현규, 권혁규
				</div>
				<div class="card-body">
					<?php
					$kk=0;
					foreach($rsc8 as $r8){
					?>
					<?php if($kk){echo "<hr>";}?>
					<p class="card-text">
						<?php
						echo "▶ ".$r8->league;
						echo " - ".$r8->gamedate." ".substr($r8->gametime,0,5)." (".w_date(date("w",strtotime($r8->gamedate))).")<br>";
						echo $r8->hometeam." - ".$r8->awayteam;
						if($r8->status=="종료"){
							echo "<br>";
							echo "[종료] ".$r8->score;
						}else if($r8->status=="진행중"){
							echo " <font color='blue'>[".$r8->playtime."] ".$r8->score."</font>";
						}else if($r8->status=="연기"){
							echo " <font color='red'>[연기]</font>";
						}else if($r8->status=="취소"){
							echo " <font color='red'>[취소]</font>";
						}
					?>
					</p>
					<?php 
					$kk++;
					}?>
				</div>
			</div>
		</div>

		<div class="col-lg-6">
			<div class="card mb-4">
				<div class="card-header" style="background-color: #000;color:#fff;">
					★ 홍현석
				</div>
				<div class="card-body">
					<?php
					$kk=0;
					foreach($rsc9 as $r9){
					?>
					<?php if($kk){echo "<hr>";}?>
					<p class="card-text">
						<?php
						echo "▶ ".$r9->league;
						echo " - ".$r9->gamedate." ".substr($r9->gametime,0,5)." (".w_date(date("w",strtotime($r9->gamedate))).")<br>";
						echo $r9->hometeam." - ".$r9->awayteam;
						if($r9->status=="종료"){
							echo "<br>";
							echo "[종료] ".$r9->score;
						}else if($r9->status=="진행중"){
							echo " <font color='blue'>[".$r9->playtime."] ".$r9->score."</font>";
						}else if($r9->status=="연기"){
							echo " <font color='red'>[연기]</font>";
						}else if($r9->status=="취소"){
							echo " <font color='red'>[취소]</font>";
						}
					?>
					</p>
					<?php 
					$kk++;
					}?>
				</div>
			</div>
		</div>

		<div class="col-lg-6">
			<div class="card mb-4">
				<div class="card-header" style="background-color: #000;color:#fff;">
					★ 김지수
				</div>
				<div class="card-body">
					<?php
					$kk=0;
					foreach($rsc10 as $r10){
					?>
					<?php if($kk){echo "<hr>";}?>
					<p class="card-text">
						<?php
						echo "▶ ".$r10->league;
						echo " - ".$r10->gamedate." ".substr($r10->gametime,0,5)." (".w_date(date("w",strtotime($r10->gamedate))).")<br>";
						echo $r10->hometeam." - ".$r10->awayteam;
						if($r10->status=="종료"){
							echo "<br>";
							echo "[종료] ".$r10->score;
						}else if($r10->status=="진행중"){
							echo " <font color='blue'>[".$r10->playtime."] ".$r10->score."</font>";
						}else if($r10->status=="연기"){
							echo " <font color='red'>[연기]</font>";
						}else if($r10->status=="취소"){
							echo " <font color='red'>[취소]</font>";
						}
					?>
					</p>
					<?php 
					$kk++;
					}?>
				</div>
			</div>
		</div>

		<div class="col-lg-6">
			<div class="card mb-4">
				<div class="card-header" style="background-color: #000;color:#fff;">
					★ 이재성
				</div>
				<div class="card-body">
					<?php
					$kk=0;
					foreach($rsc11 as $r11){
					?>
					<?php if($kk){echo "<hr>";}?>
					<p class="card-text">
						<?php
						echo "▶ ".$r11->league;
						echo " - ".$r11->gamedate." ".substr($r11->gametime,0,5)." (".w_date(date("w",strtotime($r11->gamedate))).")<br>";
						echo $r11->hometeam." - ".$r11->awayteam;
						if($r11->status=="종료"){
							echo "<br>";
							echo "[종료] ".$r11->score;
						}else if($r11->status=="진행중"){
							echo " <font color='blue'>[".$r11->playtime."] ".$r11->score."</font>";
						}else if($r11->status=="연기"){
							echo " <font color='red'>[연기]</font>";
						}else if($r11->status=="취소"){
							echo " <font color='red'>[취소]</font>";
						}
					?>
					</p>
					<?php 
					$kk++;
					}?>
				</div>
			</div>
		</div>

		<div class="col-lg-6">
			<div class="card mb-4">
				<div class="card-header" style="background-color: #000;color:#fff;">
					★ 정우영
				</div>
				<div class="card-body">
					<?php
					$kk=0;
					foreach($rsc12 as $r12){
					?>
					<?php if($kk){echo "<hr>";}?>
					<p class="card-text">
						<?php
						echo "▶ ".$r12->league;
						echo " - ".$r12->gamedate." ".substr($r12->gametime,0,5)." (".w_date(date("w",strtotime($r12->gamedate))).")<br>";
						echo $r12->hometeam." - ".$r12->awayteam;
						if($r12->status=="종료"){
							echo "<br>";
							echo "[종료] ".$r12->score;
						}else if($r12->status=="진행중"){
							echo " <font color='blue'>[".$r12->playtime."] ".$r12->score."</font>";
						}else if($r12->status=="연기"){
							echo " <font color='red'>[연기]</font>";
						}else if($r12->status=="취소"){
							echo " <font color='red'>[취소]</font>";
						}
					?>
					</p>
					<?php 
					$kk++;
					}?>
				</div>
			</div>
		</div>


</div>
</main>
<br><br>
<br><br>
<?php
include $_SERVER["DOCUMENT_ROOT"]."/inc/footer.php";
?>