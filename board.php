<?php
include $_SERVER["DOCUMENT_ROOT"]."/inc/header.php";
$multi="ozzal";
$site_json=json_encode($site);
$LIMIT=$_GET['LIMIT']?$_GET['LIMIT']:20;
$page=$_GET['page']?$_GET['page']-1:0;
$start_page=($page)*$LIMIT;
$end_page=$LIMIT;
$ps=$LIMIT;//한페이지에 몇개를 표시할지
$sub_size=7;//아래에 나오는 페이징은 몇개를 할지
//https://dreamgarden.oopy.io/cef8bddb-6020-4a50-9df7-8887c191c6e6 구글 로그인
?>
<style>
	.page-link a{color: black;}
</style>

<main class="container">

  <div class="my-3 p-3 bg-body rounded shadow-sm">
    <h6 class="border-bottom pb-2 mb-0"><span class="material-symbols-outlined" style="vertical-align: text-bottom;">book</span> Today Zzal </h6>
<?php

//$todate=date("Y/m/d", strtotime('-1 year'))." 00:00:00";
$fromdate=date("Y/m/d")." 00:00:00";
$todate=date("Y/m/d", strtotime('+1 days'))." 00:00:00";
$rangewhere = '{"lt": "'.$todate.'"}';

$ord='{"reg_date":"desc","cnt":"desc"}';
$todate=date("Y/m/d", strtotime('+7 days'))." 00:00:00";
$rangewhere = '{"lt": "'.$todate.'"}';

if($gubun){
	$gubunCode=',
				"must": [
					{ "term": { "multi" : "'.$gubun.'" }}
				]';
}

if($gubun){
	$gubunCode=', { "term": { "multi" : "'.$gubun.'" }}';
}

//특정 게시물을 삭제할때
if($mt){
	$mustnot=', "must_not": [{ "terms":  { "email.keyword": ["bluecake@gmail.com","partenon@hanmail.net","postman@gmail.com"] } } ]';
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
                            "isdisp": { "gt": "0" }
                            }
                    } 
                ]'.$mustnot.'
            }
        },
		"size": '.$LIMIT.',
		"from": '.$start_page.',
		"sort": '.$ord.'
	}
	';


//echo $json;
//{ "query": { "bool": { "filter": [ { "terms": { "multi": ["ozzal"] } }, { "range": { "site_cnt": { "gt": "0" } } } ], "must": [ { "range": { "site_reg_date":{"lt": "2023/06/07 00:00:00"} } } ] } }, "size": 20, "from": 0, "sort": {"site_reg_date":"desc","site_cnt":"desc"} }

//$url="182.162.21.6:9200/eve/_search?pretty";
$url="http://localhost:9200/cboard/_search?pretty";

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
		
?>

    <a href="/board/view.php?num=<?php echo $p->_source->num;?>">
    <div class="d-flex text-muted pt-3">
<?php
		if(!empty($p->_source->thumb)){
?>
	  <?php if($p->_source->thumb=="mp4"){?>
			<svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" fill="currentColor" class="bi bi-caret-right-square-fill flex-shrink-0 me-2 rounded" viewBox="0 0 16 16">
			  <path d="M0 2a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v12a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V2zm5.5 10a.5.5 0 0 0 .832.374l4.5-4a.5.5 0 0 0 0-.748l-4.5-4A.5.5 0 0 0 5.5 4v8z"/>
			</svg>
	<?php }else if($p->_source->thumb=="img"){?>
			<svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" fill="currentColor" class="bi bi-image flex-shrink-0 me-2 rounded" viewBox="0 0 16 16">
			  <path d="M6.002 5.5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0z"/>
			  <path d="M2.002 1a2 2 0 0 0-2 2v10a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V3a2 2 0 0 0-2-2h-12zm12 1a1 1 0 0 1 1 1v6.5l-3.777-1.947a.5.5 0 0 0-.577.093l-3.71 3.71-2.66-1.772a.5.5 0 0 0-.63.062L1.002 12V3a1 1 0 0 1 1-1h12z"/>
			</svg>
	</svg>
	<?php }else{?>
	  <!-- <img src="/board/upImages/thumb/<?php echo $p->_source->thumb;?>" width="40" height="40" class="bd-placeholder-img flex-shrink-0 me-2 rounded"> -->
	  <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" fill="currentColor" class="bi bi-image flex-shrink-0 me-2 rounded" viewBox="0 0 16 16">
			  <path d="M6.002 5.5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0z"/>
			  <path d="M2.002 1a2 2 0 0 0-2 2v10a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V3a2 2 0 0 0-2-2h-12zm12 1a1 1 0 0 1 1 1v6.5l-3.777-1.947a.5.5 0 0 0-.577.093l-3.71 3.71-2.66-1.772a.5.5 0 0 0-.63.062L1.002 12V3a1 1 0 0 1 1-1h12z"/>
			</svg>
	</svg>
	<?php }?>	 
<?php }else{?>
	<svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" fill="currentColor" class="bi bi-exclamation-square-fill flex-shrink-0 me-2 rounded" viewBox="0 0 16 16">
		  <path d="M2 0a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V2a2 2 0 0 0-2-2H2zm6 4c.535 0 .954.462.9.995l-.35 3.507a.552.552 0 0 1-1.1 0L7.1 4.995A.905.905 0 0 1 8 4zm.002 6a1 1 0 1 1 0 2 1 1 0 0 1 0-2z"/>
		</svg>
<?php }?>
	  <div class="pb-3 mb-0 small lh-sm border-bottom w-100" onclick="moveurl('/board/view.php?num=<?php echo $p->_source->num;?>')">
        <div class="d-flex justify-content-between">
        <strong class="text-gray-dark"><?=$p->_source->subject?><?php if($p->_source->memo_cnt){?>&nbsp;[<?php echo $p->_source->memo_cnt;?>]<?php }?>
		<?php
		
		$wd=strtotime($p->_source->site_reg_date)+86400;
		if(time()<=$wd){ 
			echo "<span style='color:red;'>N</span>";
//			echo '<span class="material-symbols-outlined">bolt</span>';
//			echo '<img src="/img/new1.png" width="25">';
		}
?>
		</strong>
		<a href="#"><?=$p->_source->cnt?></a>
		</div>
		<span class="d-block"><?php echo $p->_source->name;?> / <?php echo $p->_source->edit_date;?></span>
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
            <span class="sr-only">Previous</span>
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
            <span class="sr-only">Next</span>
          </a>
		  <?php }?>
        </li>
      </ul>
    </small>
	<div style="text-align:center;padding:10px;">	
		<form method="get" action="search.php" name="sform">
			<input type="hidden" name="gubun" value="<?=$gubun?>">
			<input type="hidden" name="site_json" value="<?=$site_json?>">
			<input type="text" name="sword" value="<?=$sword?>" id="sword"  style="width:200px;height:38px;">&nbsp;<button  type="submit" id="search" class="btn btn-dark">검색</button>
		</form>
	</div>
<div style="text-align:left;margin:20px;">
<a href="/board/write.php" class="btn btn-dark">등록</a>
</div>
		
  </div>
</main>

<script>
	function moveurl(url){
		location.href=url;
}
</script>
    
<?php
include $_SERVER["DOCUMENT_ROOT"]."/inc/footer.php";
?>