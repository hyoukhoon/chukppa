<?php
include $_SERVER["DOCUMENT_ROOT"]."/inc/header.php";
$multi="soccer";
$site_json=json_encode($site);
$LIMIT=$_GET['LIMIT']?$_GET['LIMIT']:25;
$page=$_GET['page']?$_GET['page']-1:0;
$start_page=($page)*$LIMIT;
$end_page=$LIMIT;
$ps=$LIMIT;//한페이지에 몇개를 표시할지
$sub_size=7;//아래에 나오는 페이징은 몇개를 할지
//https://dreamgarden.oopy.io/cef8bddb-6020-4a50-9df7-8887c191c6e6 구글 로그인

$today=date("Y-m-d");
$k=0;
$query="select g.hometeam, g.awayteam, g.gamedate, g.gametime, g.country, g.league, k.player from xc_games_fs g
join koreanteam k on (g.hometeam=k.teamname or g.awayteam=k.teamname)
where g.gamedate>='".$today."' and g.gubun=1 and g.status!='취소' and k.isuse=1 order by rand() limit 6";
$result = $mysqli->query($query) or die("query error => ".$mysqli->error);
while($rs = $result->fetch_object()){
	if($k<3){
		$rsp[]=$rs;
	}else{
		$rsp2[]=$rs;
	}
	$k++;
}

?>
<style>
	.page-link a{color: black;}
</style>
<script src="/js/jquery.textroll.js"></script>

<main class="container">

<?php
$orderby=2;
$fromdate=date("Y/m/d H:i:s", strtotime('-3 days'));
//$fromdate=date("Y/m/d")." 00:00:00";
$todate=date("Y/m/d", strtotime('+1 days'))." 00:00:00";
$rangewhere = '{"lt": "'.$todate.'"}';
if($orderby==1){
    $ord='{"site_reg_date":"desc"}';
	$todate=date("Y/m/d", strtotime('+7 days'))." 00:00:00";
	$rangewhere = '{"lt": "'.$todate.'"}';
}else if($orderby==2){
    $ord='{"site_cnt":"desc", "site_reg_date":"desc"}';
	$rangewhere = '{ "gt": "'.$fromdate.'" , "lt": "'.$todate.'"}';
}

if($gubun){
	$gubunCode=',
				"must": [
					{ "term": { "multi" : "'.$gubun.'" }}
				]';
}

if($gubun){
	$gubunCode=', { "term": { "multi" : "'.$gubun.'" }}';
}


	$json='
	{
		"query": { 
            "bool": { 
                "filter": [ 
                    { 
                        "terms":  { 
                                "multi": ["'.$multi.'"]
                                }
                    },
                    { 
                        "range": { 
                            "site_cnt": { "gt": "0" }
                            }
                    } 
                ],
                "must": [ 
                    { 
                        "range": { 
                            "site_reg_date":'.$rangewhere .'
                        } 
                    }'.$gubunCode.'
                ]
            }
        },
		"size": 5,
		"from": 0,
		"sort": '.$ord.'
	}
	';
//echo $json;

$url="http://localhost:9200/chukppa/_search?pretty";

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
  ?>

	<div class="row" <?php
if(isMobile()){
		?>style="padding-top:20px;"
		<?php }?>>

<?php
if(isMobile()){

	$query="select g.hometeam, g.awayteam, g.gamedate, g.gametime, g.country, g.league, k.player from xc_games_fs g
	join koreanteam k on (g.hometeam=k.teamname or g.awayteam=k.teamname)
	where g.gamedate>='".$today."' and g.gubun=1 and g.status!='취소' and k.isuse=1 order by g.gamedate asc, g.gametime asc";
	$result = $mysqli->query($query) or die("query error => ".$mysqli->error);
	while($rs = $result->fetch_object()){
		$rsp[]=$rs;
	}

		?>
	<div id="carouselExampleAutoplaying" class="carousel slide" data-bs-ride="carousel" style="margin-bottom:20px;">
      <div class="carousel-inner">
		
<?php
	$gk=0;
	foreach ($rsp as $p) {
?>
		<div class="carousel-item <?php if(!$gk){echo "active";}?>">

						<div class="card">
						  <div class="card-header" style="background-color: #000;color:#fff;">
								★ <?php echo $p->player;?>
						  </div>
						  <div class="card-body">
							<p class="card-text">
								<?php
												$tv=tvonline($p->country, $p->league);
												echo "▶ ".$p->league;
												echo " - ".$p->gamedate." ".substr($p->gametime,0,5)." (".w_date(date("w",strtotime($p->gamedate))).")";
												echo " <br>(TV:".$tv[0]." / ON:".$tv[1].")";
								?>
							</p>
							<h5 class="card-title"><?php echo koreanteamtitle($p->hometeam,1)." - ".koreanteamtitle($p->awayteam,1);?></h5>
							<a href="/game/" class="btn btn-dark" style="float:right;">더보기</a>
						  </div>
						</div>

						

        </div>
<?php 
	$gk++;
		}?>
      </div>
      <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleAutoplaying" data-bs-slide="prev">
        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
        <span class="visually-hidden">Previous</span>
      </button>
      <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleAutoplaying" data-bs-slide="next">
        <span class="carousel-control-next-icon" aria-hidden="true"></span>
        <span class="visually-hidden">Next</span>
      </button>
    </div>
<?php 
}else{?>


		<div class="textroll" style="margin-bottom:10px;">

		<span>
			<div class="row row-cols-1 row-cols-md-3 g-4" style="line-height: 22px;width:1315px;">
		<?php
			$gk=0;
			foreach ($rsp as $p) {
		?>
							<div class="col">
								<div class="card">
								  <div class="card-header" style="background-color: #000;color:#fff;">
										★ <?php echo $p->player;?>
								  </div>
								  <div class="card-body">
									<p class="card-text">
										<?php
														$tv=tvonline($p->country, $p->league);
														echo "▶ ".$p->league;
														echo " - ".$p->gamedate." ".substr($p->gametime,0,5)." (".w_date(date("w",strtotime($p->gamedate))).")";
														echo " <br>(TV:".$tv[0]." / ON:".$tv[1].")";
										?>
									</p>
									<h5 class="card-title"><?php echo koreanteamtitle($p->hometeam,1)." - ".koreanteamtitle($p->awayteam,1);?></h5>
									<a href="/game/" class="btn btn-dark btn-sm" style="float:right;">더보기</a>
								  </div>
								</div>
							</div>
		<?php 
			$gk++;
				}?>
			</div>
		</span>

		<span>
			<div class="row row-cols-1 row-cols-md-3 g-4" style="line-height: 22px;width:1315px;">
		<?php
			$gk=0;
			foreach ($rsp2 as $p2) {
		?>
							<div class="col">
								<div class="card">
								  <div class="card-header" style="background-color: #000;color:#fff;">
										★ <?php echo $p2->player;?>
								  </div>
								  <div class="card-body">
									<p class="card-text">
										<?php
														$tv=tvonline($p2->country, $p2->league);
														echo "▶ ".$p2->league;
														echo " - ".$p2->gamedate." ".substr($p2->gametime,0,5)." (".w_date(date("w",strtotime($p2->gamedate))).")";
														echo " <br>(TV:".$tv[0]." / ON:".$tv[1].")";
										?>
									</p>
									<h5 class="card-title"><?php echo koreanteamtitle($p2->hometeam,1)." - ".koreanteamtitle($p2->awayteam,1);?></h5>
									<a href="/game/" class="btn btn-dark btn-sm" style="float:right;">더보기</a>
								  </div>
								</div>
							</div>
		<?php 
			$gk++;
				}?>
			</div>
		</span>

		</div>

<?php }?>

		<div class="col-lg-6">
			<div class="card mb-4">
				<div class="card-header" style="background-color: #000;color:#fff;">
					<span class="material-symbols-outlined" style="vertical-align: text-bottom;">sports_soccer</span> 축게HOT
				</div>
				<div class="card-body" style="padding-top: 5px;">
					<?php
$orderby=2;
$fromdate=date("Y/m/d H:i:s", strtotime('-3 days'));
//$fromdate=date("Y/m/d")." 00:00:00";
$todate=date("Y/m/d", strtotime('+1 days'))." 00:00:00";
$rangewhere = '{"lt": "'.$todate.'"}';
if($orderby==1){
    $ord='{"site_reg_date":"desc"}';
	$todate=date("Y/m/d", strtotime('+7 days'))." 00:00:00";
	$rangewhere = '{"lt": "'.$todate.'"}';
}else if($orderby==2){
    $ord='{"site_cnt":"desc", "site_reg_date":"desc"}';
	$rangewhere = '{ "gt": "'.$fromdate.'" , "lt": "'.$todate.'"}';
}

if($gubun){
	$gubunCode=',
				"must": [
					{ "term": { "multi" : "'.$gubun.'" }}
				]';
}

if($gubun){
	$gubunCode=', { "term": { "multi" : "'.$gubun.'" }}';
}


	$json='
	{
		"query": { 
            "bool": { 
                "filter": [ 
                    { 
                        "terms":  { 
                                "multi": ["'.$multi.'"]
                                }
                    },
                    { 
                        "range": { 
                            "site_cnt": { "gt": "0" }
                            }
                    } 
                ],
                "must": [ 
                    { 
                        "range": { 
                            "site_reg_date":'.$rangewhere .'
                        } 
                    }'.$gubunCode.'
                ]
            }
        },
		"size": 5,
		"from": 0,
		"sort": '.$ord.'
	}
	';
//echo $json;

$url="http://localhost:9200/chukppa/_search?pretty";

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
//  print_r($output);
//    echo "<pre>";
//    print_r($output->hits->hits);
  
	foreach($output->hits->hits as $p){

//	print_r($p);
		$sec = time() - strtotime($p->_source->site_reg_date);
		if ($sec < 60) {
			$dispdates = $sec."초 전";
		} else if ($sec > 60 && $sec < 3600) {
			$f = floor($sec / 60);
			$dispdates = $f."분 전";
		} else if ($sec > 3600 && $sec < 86400) {
			$f = floor($sec / 3600);
			$dispdates = $f."시간 전";
		} else {
			$dispdates = date("Y-m-d",strtotime($p->_source->site_reg_date));
		}
?>
<a class="page_link" href="<?php echo $p->_source->url;?>&page=<?php echo $page+1;?>">
    <div class="d-flex text-muted pt-2">
	  <div class="pb-2 mb-0 small lh-sm border-bottom w-100" onclick="moveurl('<?php echo $p->_source->url;?>&page=<?php echo $page+1;?>')">
        <div class="d-flex justify-content-between">
        <strong class="text-dark"><?php echo mb_substr($p->_source->subject, 0, 40, 'utf-8');?>
<?php if($p->_source->thumbnail=="mp4"){?>
		<svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="currentColor" class="bi bi-caret-right-square-fill flex-shrink-0" viewBox="0 0 16 16">
		  <path d="M0 2a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v12a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V2zm5.5 10a.5.5 0 0 0 .832.374l4.5-4a.5.5 0 0 0 0-.748l-4.5-4A.5.5 0 0 0 5.5 4v8z"/>
		</svg>
<?php }else if($p->_source->thumbnail!=""){?>
			<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-image flex-shrink-0" viewBox="0 0 16 16">
			  <path d="M6.002 5.5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0z"/>
			  <path d="M2.002 1a2 2 0 0 0-2 2v10a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V3a2 2 0 0 0-2-2h-12zm12 1a1 1 0 0 1 1 1v6.5l-3.777-1.947a.5.5 0 0 0-.577.093l-3.71 3.71-2.66-1.772a.5.5 0 0 0-.63.062L1.002 12V3a1 1 0 0 1 1-1h12z"/>
			</svg>
<?php }?>
<?php if($p->_source->memo_cnt){?>[<?php echo $p->_source->memo_cnt;?>]<?php }?></strong>
		<!-- <a href="#"><?=$p->_source->site_cnt?></a> -->
		</div>
		<span class="d-block"><?php echo $p->_source->username;?> / <?php echo $dispdates;?></span>
		</div>
    </div></a>
<?php
	}

?>
				<div style="text-align:right;padding-top:5px;">
						<a href="/board/index.php?multi=soccer">▶ 더보기</a>
				</div>
				</div>

			</div>
		</div>
		<div class="col-lg-6">
			<div class="card mb-4">
				<div class="card-header" style="background-color: #000;color:#fff;">
					<span class="material-symbols-outlined" style="vertical-align: text-bottom;">public</span> 자게HOT
				</div>
				<div class="card-body" style="padding-top: 5px;">
				
					<?php
$orderby=2;
$multi="free";
$fromdate=date("Y/m/d H:i:s", strtotime('-5 days'));
//$fromdate=date("Y/m/d")." 00:00:00";
$todate=date("Y/m/d", strtotime('+1 days'))." 00:00:00";
$rangewhere = '{"lt": "'.$todate.'"}';
if($orderby==1){
    $ord='{"site_reg_date":"desc"}';
	$todate=date("Y/m/d", strtotime('+7 days'))." 00:00:00";
	$rangewhere = '{"lt": "'.$todate.'"}';
}else if($orderby==2){
    $ord='{"site_cnt":"desc", "site_reg_date":"desc"}';
	$rangewhere = '{ "gt": "'.$fromdate.'" , "lt": "'.$todate.'"}';
}

if($gubun){
	$gubunCode=',
				"must": [
					{ "term": { "multi" : "'.$gubun.'" }}
				]';
}

if($gubun){
	$gubunCode=', { "term": { "multi" : "'.$gubun.'" }}';
}


	$json='
	{
		"query": { 
            "bool": { 
                "filter": [ 
                    { 
                        "terms":  { 
                                "multi": ["'.$multi.'"]
                                }
                    },
                    { 
                        "range": { 
                            "site_cnt": { "gt": "0" }
                            }
                    } 
                ],
                "must": [ 
                    { 
                        "range": { 
                            "site_reg_date":'.$rangewhere .'
                        } 
                    }'.$gubunCode.'
                ]
            }
        },
		"size": 5,
		"from": 0,
		"sort": '.$ord.'
	}
	';


//echo $json;

//$url="182.162.21.6:9200/eve/_search?pretty";
$url="http://localhost:9200/chukppa/_search?pretty";

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
//  print_r($output);
//    echo "<pre>";
//    print_r($output->hits->hits);
  
	foreach($output->hits->hits as $p){

//	print_r($p);
		$sec = time() - strtotime($p->_source->site_reg_date);
		if ($sec < 60) {
			$dispdates = $sec."초 전";
		} else if ($sec > 60 && $sec < 3600) {
			$f = floor($sec / 60);
			$dispdates = $f."분 전";
		} else if ($sec > 3600 && $sec < 86400) {
			$f = floor($sec / 3600);
			$dispdates = $f."시간 전";
		} else {
			$dispdates = date("Y-m-d",strtotime($p->_source->site_reg_date));
		}
?>
<a class="page_link" href="<?php echo $p->_source->url;?>&page=<?php echo $page+1;?>">
    <div class="d-flex text-muted pt-2">
	
	  <div class="pb-2 mb-0 small lh-sm border-bottom w-100" onclick="moveurl('<?php echo $p->_source->url;?>&page=<?php echo $page+1;?>')">
        <div class="d-flex justify-content-between">
        <strong class="text-dark"><?php echo mb_substr($p->_source->subject, 0, 40, 'utf-8');?>
<?php if($p->_source->thumbnail=="mp4"){?>
		<svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="currentColor" class="bi bi-caret-right-square-fill flex-shrink-0" viewBox="0 0 16 16">
		  <path d="M0 2a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v12a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V2zm5.5 10a.5.5 0 0 0 .832.374l4.5-4a.5.5 0 0 0 0-.748l-4.5-4A.5.5 0 0 0 5.5 4v8z"/>
		</svg>
<?php }else if($p->_source->thumbnail!=""){?>
			<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-image flex-shrink-0" viewBox="0 0 16 16">
			  <path d="M6.002 5.5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0z"/>
			  <path d="M2.002 1a2 2 0 0 0-2 2v10a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V3a2 2 0 0 0-2-2h-12zm12 1a1 1 0 0 1 1 1v6.5l-3.777-1.947a.5.5 0 0 0-.577.093l-3.71 3.71-2.66-1.772a.5.5 0 0 0-.63.062L1.002 12V3a1 1 0 0 1 1-1h12z"/>
			</svg>
<?php }?>
<?php if($p->_source->memo_cnt){?>[<?php echo $p->_source->memo_cnt;?>]<?php }?>
		</strong>
		<!-- <a href="#"><?=$p->_source->site_cnt?></a> -->
		</div>
		<span class="d-block"><?php echo $p->_source->username;?> / <?php echo $dispdates;?></span>
		</div>
    </div></a>
<?php
	}

?>
				<div style="text-align:right;padding-top:5px;">
						<a href="/board/index.php?multi=free">▶ 더보기</a>
				</div>
				</div>
			</div>
		</div>
	</div>
<!-- 상단 HOT 끝-->
<div style="text-align:center;">
	<script async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js?client=ca-pub-3926380236997271"
		 crossorigin="anonymous"></script>
	<!-- 축_메인 -->
	<ins class="adsbygoogle"
		 style="display:block"
		 data-ad-client="ca-pub-3926380236997271"
		 data-ad-slot="3918885165"
		 data-ad-format="auto"
		 data-full-width-responsive="true"></ins>
	<script>
		 (adsbygoogle = window.adsbygoogle || []).push({});
	</script>
</div>

  <div class="p-3 bg-body rounded shadow-sm">
    <h6 class="border-bottom pb-2 mb-0"><span class="material-symbols-outlined" style="vertical-align: text-bottom;">book</span> ALL </h6>
<?php
$fromdate=date("Y/m/d")." 00:00:00";
$todate=date("Y/m/d", strtotime('+1 days'))." 00:00:00";
$ord='{"site_reg_date":"desc","site_cnt":"desc"}';
$rangewhere = '{"lt": "'.$todate.'"}';

	$json='
	{
		"query": { 
            "bool": { 
                "filter": [ 
                    { 
                        "range": { 
                            "site_cnt": { "gt": "0" }
                            }
                    } 
                ],
                "must": [ 
                    { 
                        "range": { 
                            "site_reg_date":'.$rangewhere .'
                        } 
                    }'.$gubunCode.'
                ]
            }
        },
		"size": '.$LIMIT.',
		"from": '.$start_page.',
		"sort": '.$ord.'
	}
	';
//echo $json;

$url="http://localhost:9200/chukppa/_search?pretty";

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
//  print_r($output);
//    echo "<pre>";
//    print_r($output->hits->hits);
  
  $total=$output->hits->total->value;
  //echo "total=>".$total;

  $total_page=ceil($total/$ps);//몇페이지
  $f_no=$_GET['f_no']?$_GET['f_no']:1;//첫페이지
  if($f_no<1)$f_no=1;
  $l_no=$f_no+$sub_size-1;//마지막페이지
  if($l_no>$total_page)$l_no=$total_page;
  $n_f_no=$f_no+$sub_size;//다음첫페이지
  $p_f_no=$f_no-$sub_size;//이전첫페이지
  $no=$total-($page)*$ps;//번호매기기


	foreach($output->hits->hits as $p){

//	print_r($p);
		$sec = time() - strtotime($p->_source->site_reg_date);
		if ($sec < 60) {
			$dispdates = $sec."초 전";
		} else if ($sec > 60 && $sec < 3600) {
			$f = floor($sec / 60);
			$dispdates = $f."분 전";
		} else if ($sec > 3600 && $sec < 86400) {
			$f = floor($sec / 3600);
			$dispdates = $f."시간 전";
		} else {
			$dispdates = date("Y-m-d",strtotime($p->_source->site_reg_date));
		}
?>

<a class="page_link" href="<?php echo $p->_source->url;?>&page=<?php echo $page+1;?>">
    <div class="d-flex text-muted pt-2">
	  <div class="pb-2 mb-0 small lh-sm border-bottom w-100" onclick="moveurl('<?php echo $p->_source->url;?>&page=<?php echo $page+1;?>')">
        <div class="d-flex justify-content-between">
        <strong class="text-dark"><?php echo mb_substr($p->_source->subject, 0, 40, 'utf-8');?>
<?php if($p->_source->thumbnail=="mp4"){?>
		<svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="currentColor" class="bi bi-caret-right-square-fill flex-shrink-0" viewBox="0 0 16 16">
		  <path d="M0 2a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v12a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V2zm5.5 10a.5.5 0 0 0 .832.374l4.5-4a.5.5 0 0 0 0-.748l-4.5-4A.5.5 0 0 0 5.5 4v8z"/>
		</svg>
<?php }else if($p->_source->thumbnail!=""){?>
			<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-image flex-shrink-0" viewBox="0 0 16 16">
			  <path d="M6.002 5.5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0z"/>
			  <path d="M2.002 1a2 2 0 0 0-2 2v10a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V3a2 2 0 0 0-2-2h-12zm12 1a1 1 0 0 1 1 1v6.5l-3.777-1.947a.5.5 0 0 0-.577.093l-3.71 3.71-2.66-1.772a.5.5 0 0 0-.63.062L1.002 12V3a1 1 0 0 1 1-1h12z"/>
			</svg>
<?php }?>
<?php if($p->_source->memo_cnt){?>[<?php echo $p->_source->memo_cnt;?>]<?php }?></strong>
		<?php
		
		$wd=strtotime($p->_source->site_reg_date)+86400;
		if(time()<=$wd){ 
//			echo "<span style='color:red;'>N</span>";
//			echo '<span class="material-symbols-outlined">bolt</span>';
//			echo '<img src="/img/new1.png" width="25">';
			echo '<svg role="img" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" width="14" height="14" class="bi bi-image flex-shrink-0"><title>New</title><path d="M1.6 0S0 0 0 1.6v20.8S0 24 1.6 24h20.8s1.6 0 1.6-1.6V1.6S24 0 22.4 0zm3.415 5.6h4.78l4.425 6.458V5.6h4.765v12.8h-4.78L9.78 11.943V18.4H5.015Z"/></svg>';
		}
?>
		</strong>
		<a href="#"><?=$p->_source->site_cnt?></a>
		</div>
		<span class="d-block"><?php echo $p->_source->username;?> / <?php echo $dispdates;?></span>
		</div>
    </div></a>
<?php
	}

	$page=$page+1;
?>

    <small class="d-block text-end mt-3">
      <ul class="pagination justify-content-center">
        <li class="page-item">
			<?php if($f_no!=1){?>
          <a class="page-link" href="<?=$_SERVER['PHP_SELF']?>?mode=<?=$mode?>&page=<?=$p_f_no?>&f_no=<?=$p_f_no?>&gubun=<?=$gubun?>&s_key=<?=$s_key?>&sword=<?=$sword?>&site_json=<?=$site_json?>&m2=<?=$m2?>&orderby=<?=$orderby?>" aria-label="Previous">
            <span aria-hidden="true">&laquo;</span>
            <span class="sr-only"></span>
          </a>
		  <?php }?>
        </li>
		<?php 
		for($i=$f_no;$i<=$l_no;$i++){?>
					<?php
					if($i==$page){?>
						<li class="page-item" style="text-decoration: underline;">
						  <a class="page-link" href="javascript:;"><b><?=$i?></b></a>
						</li>
					<?php
					} else {?>
						<li class="page-item">
						  <a class="page-link" href="<?=$PHP_SELF?>?mode=<?=$mode?>&page=<?=$i?>&f_no=<?=$f_no?>&gubun=<?=$gubun?>&s_key=<?=$s_key?>&sword=<?=$sword?>&site_json=<?=$site_json?>&m2=<?=$m2?>&orderby=<?=$orderby?>"><?=$i?></a>
						</li>
					<?php }?>
				<?php }?>
        
        <li class="page-item">
			<?php if($l_no<$total_page){?>
          <a class="page-link" href="<?=$_SERVER['PHP_SELF']?>?mode=<?=$mode?>&page=<?=$n_f_no?>&f_no=<?=$n_f_no?>&gubun=<?=$gubun?>&s_key=<?=$s_key?>&sword=<?=$sword?>&site_json=<?=$site_json?>&m2=<?=$m2?>&orderby=<?=$orderby?>" aria-label="Next">
            <span aria-hidden="true">&raquo;</span>
            <span class="sr-only"></span>
          </a>
		  <?php }?>
        </li>
      </ul>
    </small>
	<div style="text-align:center;padding:10px;" id="searchbutton">	
		<form method="get" action="/board/search.php" name="sform" onsubmit="return searchform();">
			<input type="text" name="sword" value="<?=$sword?>" id="sword"  style="width:200px;height:38px;">&nbsp;<button  type="submit" id="search" class="btn btn-dark">검색</button>
		</form>
	</div>
	<div style="text-align:center;padding:10px;display:none;" id="searching">	
	<button class="btn btn-dark" type="button" disabled >
	  <span class="spinner-grow spinner-grow-sm" role="status" aria-hidden="true"></span>
	  Loading...
	</button>
	</div>
<!-- <div style="text-align:left;margin:20px;">
<a href="/board/write.php" class="btn btn-dark">등록</a>
</div> -->
<br><br>
  </div>
</main>

<script>

$(".textroll").textroll({
  delay: 2000,
});

function searchform(){
	$("#searchbutton").hide();
	$("#searching").show();
	var sword=$("#sword").val();
	if(!sword){
		alert('검색어를 입력하세요.');
		$("#searchbutton").show();
		$("#searching").hide();
		return false;
	}

	return true;

}

	function moveurl(url){
		location.href=url;
}
</script>
    
<?php
include $_SERVER["DOCUMENT_ROOT"]."/inc/footer.php";
?>