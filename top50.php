<?php
include "./inc/dbcon.php";
// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL);

$today=date("Y-m-d");
$today_date=date("Y-m-d H:i:s");
$ord=$_GET['ord'];
$orderby=$_GET['orderby']??1;
$sword=$_GET['sword'];
$gubun=$_GET['gubun'];




if($_COOKIE['saveCookieSite']){
	$site_json=$_COOKIE['saveCookieSite'];
}

if($_GET['site_json']){
	$site_json=urldecode($_GET['site_json']);
}


if($_REQUEST['site']){
	$site=$_REQUEST['site'];
}else{
	$site=array("ddanzi","clien","etoland","ppomppu","todayhumor","utdae","bobae","theqoo","slrclub","ruliweb");
}

if(!$site_json){
	$site_json=json_encode($site);
}


$que3="select keywords from top100 where reg_date='$today'";
$result3 = $mysqli->query($que3) or die("2:".$mysqli->error);
$rs3 = $result3->fetch_array();
$words=json_decode(urldecode($rs3[0]));



function site_name_is($n){

		switch($n) {
			
			case "ddanzi":$rs="<font color='#ff0000'>딴지</font>";
			break;
			case "82cook":$rs="<font color='#ff8c00'>빨리쿡</font>";
			break;
			case "clien":$rs="<font color='#558000'>클리앙</font>";
			break;
			case "etoland":$rs="<font color='#008000'>이토</font>";
			break;
			case "mpark":$rs="<font color='#800080'>엠팍</font>";
			break;
			case "ppomppu":$rs="<font color='#4b0082'>뽐뿌</font>";
			break;
			case "todayhumor":$rs="<font color='#0000ff'>오유</font>";
			break;
			case "utdae":$rs="<font color='#ff1100'>웃대</font>";
			break;
			case "bobae":$rs="<font color='#ff8c55'>보배</font>";
			break;
			case "inven":$rs="<font color='#000000'>인벤</font>";
			break;
			case "theqoo":$rs="<font color='#aa8000'>더쿠</font>";
			break;
			case "slrclub":$rs="<font color='#008055'>slrclub</font>";
			break;
			case "ruliweb":$rs="<font color='#bb0055'>루리웹</font>";
			break;
			case "fmkorea":$rs="<font color='#bbaa55'>펨코</font>";
			break;
			case "hnhn":$rs="<font color='#bbcc55'>하늘</font>";
			break;
			case "dorosi":$rs="<font color='#ccee77'>도로시</font>";
			break;
			case "thezam":$rs="<font color='#dd11ee'>더잠</font>";
			break;
			case "bbongbra":$rs="<font color='#ee33aa'>뽕브라</font>";
			break;

		}

		return $rs;

	}

//echo "isM:".isMobile();

?>
<!DOCTYPE html>
<html lang="en">

  <head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="국내 인기 커뮤니티의 각 게시판들을 한곳에 모아두었습니다. 조회수가 많은 인기글들을 확인할 수 있습니다.">
    <meta name="author" content="">
    <meta name="naver-site-verification" content="3560e8c5dcae0af03827b8b75f82faada6fdcf4f"/>

    <title>국내 인기 커뮤니티 게시판을 한곳에 - 에보</title>

    <!-- Bootstrap core CSS -->
    <link href="vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="css/4-col-portfolio.css" rel="stylesheet">
	<style>
		.float1 { float: left; padding: 10px;}
	</style>
	<style type="text/css">
		a:link{color: black;}
		a:visited{color: silver;}
</style>




  </head>

  <body>

    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top">
      <div class="container">
        <a class="navbar-brand" href="/">ozzal</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarResponsive" aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation">
          <span class="navbar-toggler-icon"></span>
        </button>
        <?php include $_SERVER['DOCUMENT_ROOT']."/inc/tab_menu.php";?>
      </div>
    </nav>
<script>

function sitePr(){
	a=document.site_search;
	a.submit();
}

	function search_words(words){
				a=document.site_search;
				a.sword.value=words
				a.submit();
			}
</script>
    <!-- Page Content -->
    <div class="container">

		<div style="padding-bottom:10px;padding-top:10px;text-align:center;display:-webkit-inline-box">
			<?php
			$style_type=array("btn-outline-primary","btn-outline-secondary","btn-outline-success","btn-outline-danger","btn-outline-warning","btn-outline-info","btn-outline-dark");
			$t=1;
			foreach($words as $w){
				$n=array_rand($style_type);
				$st=$style_type[$n];
				if(strlen($w[0])>4 and $t<21 and $w[0]!="이유" and $w[0]!="사람" and $w[0]!="우리" and $w[0]!="여자" and $w[0]!="남자" and $w[0]!="유머" and $w[0]!="관련" and $w[0]!="오늘" and $w[0]!="이번" and $w[0]!="이거" and $w[0]!="답글" and $w[0]!="비추력" and $w[0]!="추천" and $w[0]!="비추" and $w[0]!="때문" and $w[0]!="하나" and $w[0]!="근황" and $w[0]!="나라"){
					echo "<button type=\"button\" class=\"btn ".$st."\" onclick=\"search_words('".$w[0]."')\"  style=\"margin:3px;\">".$t.".".$w[0]."</button>&nbsp;&nbsp;";
					 $t++;
				}
			}
			?>
		</div>

		<!-- <div style="text-align:center;">
			<a href="https://play.google.com/store/apps/details?id=com.khh.gorchg.propick" target="_blank"><img src="/img/propick.png" style="max-width:340px;"></a>
		</div> -->

		
      <div class="row">
		<div >

		</div>
	  <table class="table table-sm">
		  <thead class="thead-light">
			<tr>
			  <th scope="col">조회</th>
			  <th scope="col">제목</th>
			  <th scope="col" style="width:60px;">출처</th>
			</tr>
		  </thead>
		  <tbody>
		  <!-- <tr style="line-height:30px;cursor:pointer;" >
			  <th scope="row">1</th>
			  <td><a href="http://www.mallpro.kr" target="_blank">해외구매대행 타오바오 상품 자동 수집 솔루션</a></td>
			  <td><a href="http://www.mallpro.kr" target="_blank">몰프로</a></td>
			</tr>  -->

<?php

$json='
{
    "query": { 
      "bool": { 
        "must": [
          { "match": { "site_name" : "clien"}},
          { "match": { "subject" : "수종" }}
        ],
        "filter": [ 
          { "term":  { "subject": "today" }},
          { "range": { "site_reg_date": { "gte": "2020/09/25" }}}
        ]
      }
    }
  }
';

$json='
{
    "query": {
        "match": {
            "site_name": "clien",
            "subject": "수종"
        }
    },
    "size": 50,
    "from": 0,
    "sort": {
        "site_reg_date":"desc"
    }
}
';

$json='
{
    "query": {
      "multi_match" : {
        "query":    "mbc", 
        "fields": [ "subject", "site_name" ] 
      }
    }
  }
';

$json='
{
    "query": {
      "bool": {
        "should": [
          { "match": { "subject":          "mbc" }},
          { "match": { "site_name": "todayhumor" }}
        ]
      }
    }
  }
';

$json='
{
    "query": {
      "bool": {
        "must": [
          {
            "match": {
              "site_name": "todayhumor"
            }
          },
          {
            "match": {
              "subject": "mbc"
            }
          }
        ]
      }
    }
  }
';


$json='
{
    "query": {
      "bool": {
        "must": [
          {
            "match": {
              "site_name": "todayhumor"
            }
          },
          {
            "match": {
              "subject": "mbc"
            }
          }
        ]
      }
    }
  }
';

$json='
{
    "query": {
      "bool": {
        "must": [
          {
            "match_all": {}
          }
        ]
      }
    }
  }
';

$LIMIT=$_GET['LIMIT']?$_GET['LIMIT']:50;
$page=$_GET['page']?$_GET['page']-1:0;
$start_page=($page)*$LIMIT;
$end_page=$LIMIT;
$ps=$LIMIT;//한페이지에 몇개를 표시할지
$sub_size=7;//아래에 나오는 페이징은 몇개를 할지

$todate=date("Y/m/d", strtotime('-1 year'))." 00:00:00";
$ord='{"site_cnt":"desc"}';
$todate=date("Y/m/d", strtotime($day." -7 day"))." 00:00:00";





if($sword){

	$json='
	{
		"query": {
				"query_string" : {
					"fields" : ["subject", "url"],
					"query" : "*'.$sword.'*"
				}
		 },
		"size": '.$LIMIT.',
		"from": '.$start_page.',
		"sort": '.$ord.'
	}
	';
}else{

	$json='
	{
		"query": { 
            "bool": { 
                "filter": [ 
                    { 
                        "terms":  { 
                                "site_name": '.$site_json.' 
                                }
                    },
                    { 
                        "range": { 
                            "site_cnt": { "gt": "0" }
                            }
                    } 
                ]'.$gubunCode.',
                "must_not": [ 
                    { 
                        "range": { 
                            "site_reg_date": { "lt": "'.$todate.'" } 
                        } 
                    },
					{ 
                        "term": { 
                            "multi": "review" 
                        } 
                    }
                ]
            }
        },
		"size": '.$LIMIT.',
		"from": '.$start_page.',
		"sort": '.$ord.'
	}
	';

}



//$url="182.162.21.6:9200/eve/_search?pretty";
$url="http://localhost:9200/eve/_search?pretty";

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
//    echo "<pre>";
//    print_r($output->hits->total->value);
  
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

		if(isMobile()){

			if($p->_source->site_name=="etoland"){
				$url=str_replace("/bbs/","/plugin/mobile/",$p->_source->url);
			}else if($p->_source->site_name=="ruliweb"){
				$url=str_replace("bbs.ruliweb","m.ruliweb",$p->_source->url);
			}else if($p->_source->site_name=="utdae"){
				$url=str_replace("web.humoruniv","m.humoruniv",$p->_source->url);
			}else{
				$url=$p->_source->url;
			}
		}else{
			$url=$p->_source->url;
		}

		
?>
			<tr style="line-height:30px;cursor:pointer;" >
			  <th><a href="<?=$url?>" target="_blank"><?=$p->_source->site_cnt?></a></th>
			  <td><a href="<?=$url?>" target="_blank"><?=$p->_source->subject?><?php if($p->_source->memo_cnt){?>[<?php echo $p->_source->memo_cnt;?>]<?php }?></a></td>
			  <td><a href="<?=$url?>" target="_blank"><?php echo site_name_is($p->_source->site_name);?></a></td>
			</tr>

<?php
	}

	$site_json=urlencode($site_json);
	$page=$page+1;
?>

  </tbody>
</table>
<script>
function move_page(url,num){
		window.open('link.php?url='+url+'&num='+num, '_blank');
  }
</script>
      </div>
      <!-- /.row -->
	  

<div id="clicks" style="text-align:center;"></div>
    </div>
    <!-- /.container -->

    <!-- Footer -->
    <footer class="py-5 bg-dark">
      <div class="container">
        <p class="m-0 text-center text-white">Copyleft &copy; evvo 2018</p>
		<p class="m-0 text-center text-white"><a href="mailto:partenon@hanmail.net">Contact Us</a></p>
		<!-- <p class="m-0 text-center text-white"><a href="#">Donate ethereum : 0x648e36442071d5d599267e3e1e5fa0cfc00b61a7</a></p> -->
      </div>
      <!-- /.container -->
    </footer>

    <!-- Bootstrap core JavaScript -->
    <script src="vendor/jquery/jquery.min.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

  </body>

</html>

<script>
function saveSetting(){

	if(!confirm('브라우저가 바뀌거나 쿠키가 삭제되면 설정은 초기화됩니다.\n 사이트를 저장하시겠습니까?')){
		return;
	}

	var total_cnt=0;
	var checkArray=new Array();
		/*
		if(!$("input[name='site[]']:checked").val()) {
			alert('최소한 하나의 사이트를 선택하십시오.');
			return;
		}
*/
		$('input:checkbox[name="site[]"]').each(function() {
			if(this.checked){//checked 처리된 항목의 값
				checkArray[total_cnt]=this.value;//배열로 저장
				total_cnt++;
			}
		});

		var jsonCheck = JSON.stringify(checkArray);//json으로 바꿈
		console.log(jsonCheck);
//		return;


		var params = "siteJson="+jsonCheck;
//		console.log(params);

		$.ajax({
			  type: 'post'
			, url: 'siteSave.php'
			,data : params
			, dataType : 'html'
			, success: function(data) {
				//alert(data);
				if(data==1){
					alert('저장했습니다.');
				}else{
					alert('다시 시도해주십시오.');
					return;
				}
			  }
		});	
}

</script>