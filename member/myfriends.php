<?php
include $_SERVER["DOCUMENT_ROOT"]."/inc/header.php";
if(!$_SESSION['loginValue']['SEMAIL']){
	echo "<script>alert('로그인 하십시오');location.href='/member/login.php'</script>";
 	exit;
 }

$LIMIT=$_GET['LIMIT']?$_GET['LIMIT']:20;
$page=$_GET['page']?$_GET['page']:1;
$start_page=($page)*$LIMIT;
$end_page=$LIMIT;
$ps=$LIMIT;//한페이지에 몇개를 표시할지
$sub_size=7;//아래에 나오는 페이징은 몇개를 할지


$sword=strip_tags($_GET['sword']);
// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL);
?>

<main class="container">

  <div class="my-3 p-3 bg-body rounded shadow-sm">
    <h6 class="border-bottom pb-2 mb-0"><span class="material-symbols-outlined" style="vertical-align: text-bottom;">diversity_1</span>&nbsp;친구들 글모음</h6>

<?php
		$sql = "select *, c.num as bid from xc_cboard c
		join xc_friends f on c.email=f.friendsid
		where f.userid='".$_SESSION['loginValue']['SEMAIL']."'";
        $sql .= " and c.isdisp=1";
        if(!empty($sword)){
            $search_where = " and (c.subject like '%".$sword."%' or c.content like '%".$sword."%')";
        }
        $sql .= $search_where;
        $order = " order by c.num desc";
        if($page < 1) $page = 1;
        $startLimit = ($page-1)*$LIMIT;//쿼리의 limit 시작 부분
        $limit = " limit $startLimit, $LIMIT";
        $query = $sql.$order.$limit;
        $result = $mysqli->query($query) or die("query error => ".$mysqli->error);
        while($rs = $result->fetch_object()){
            $rsc[]=$rs;
        }
//	print_r($rsc);
//	exit;

		$sqlcnt = "select count(*) as cnt from xc_cboard c
		join xc_friends f on c.email=f.friendsid
		where f.userid='".$_SESSION['loginValue']['SEMAIL']."'";
        $sqlcnt .= " and c.isdisp=1";
        $sqlcnt .= $search_where;
        $countresult = $mysqli->query($sqlcnt) or die("query error => ".$mysqli->error);
        $rscnt = $countresult->fetch_object();
        $total = $rscnt->cnt;


  $total_page=ceil($total/$ps);//몇페이지

  $f_no=$_GET['f_no']?$_GET['f_no']:1;//첫페이지
  if($f_no<1)$f_no=1;
  $l_no=$f_no+$sub_size-1;//마지막페이지
  if($l_no>$total_page)$l_no=$total_page;
  $n_f_no=$f_no+$sub_size;//다음첫페이지
  $p_f_no=$f_no-$sub_size;//이전첫페이지
  $no=$total-($page)*$ps;//번호매기기


	foreach($rsc as $r){


		
?>
<a href="/board/view.php?num=<?php echo $r->bid;?>">
    <div class="d-flex text-muted pt-3">
	  <div class="pb-3 mb-0 small lh-sm border-bottom w-100">
        <div class="d-flex justify-content-between">
        <strong class="text-gray-dark"><?php echo mb_substr($r->subject, 0, 24, 'utf-8');?>
		<?php if($r->thumb=="mp4"){?>
				<svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="currentColor" class="bi bi-caret-right-square-fill flex-shrink-0" viewBox="0 0 16 16">
				  <path d="M0 2a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v12a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V2zm5.5 10a.5.5 0 0 0 .832.374l4.5-4a.5.5 0 0 0 0-.748l-4.5-4A.5.5 0 0 0 5.5 4v8z"/>
				</svg>
		<?php }else if($r->thumb!=""){?>
					<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-image flex-shrink-0" viewBox="0 0 16 16">
					  <path d="M6.002 5.5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0z"/>
					  <path d="M2.002 1a2 2 0 0 0-2 2v10a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V3a2 2 0 0 0-2-2h-12zm12 1a1 1 0 0 1 1 1v6.5l-3.777-1.947a.5.5 0 0 0-.577.093l-3.71 3.71-2.66-1.772a.5.5 0 0 0-.63.062L1.002 12V3a1 1 0 0 1 1-1h12z"/>
					</svg>
		<?php }?>
		<?php if($r->memo_cnt){?>&nbsp;[<?php echo $r->memo_cnt;?>]<?php }?>
		<?php
		
		$wd=strtotime($r->reg_date)+86400;
		if(time()<=$wd){ 
//			echo "<span style='color:red;'>N</span>";
////			echo '<span class="material-symbols-outlined">bolt</span>';
//			echo '<img src="/img/new1.png" width="25">';
		echo '<svg role="img" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" width="14" height="14" class="bi bi-image flex-shrink-0"><title>New</title><path d="M1.6 0S0 0 0 1.6v20.8S0 24 1.6 24h20.8s1.6 0 1.6-1.6V1.6S24 0 22.4 0zm3.415 5.6h4.78l4.425 6.458V5.6h4.765v12.8h-4.78L9.78 11.943V18.4H5.015Z"/></svg>';
		}
?>
		</strong>
		<a href="#"><?=$r->cnt?></a>
		</div>
		<span class="d-block"><?php echo $r->name;?> / <?php echo $r->reg_date;?></span>
		</div>
    </div></a>
<?php
	}

?>

    <small class="d-block text-end mt-3">
      <ul class="pagination justify-content-center">
        <li class="page-item">
			<?php if($f_no!=1){?>
          <a class="page-link" href="<?=$_SERVER['PHP_SELF']?>?mode=<?=$mode?>&page=<?=$p_f_no?>&f_no=<?=$p_f_no?>&gubun=<?=$gubun?>&s_key=<?=$s_key?>&sword=<?=$sword?>&orderby=<?=$orderby?>" aria-label="Previous">
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
						  <a class="page-link" href="<?=$PHP_SELF?>?mode=<?=$mode?>&page=<?=$i?>&f_no=<?=$f_no?>&gubun=<?=$gubun?>&s_key=<?=$s_key?>&sword=<?=$sword?>&orderby=<?=$orderby?>"><?=$i?></a>
						</li>
					<?php }?>
				<?php }?>
        
        <li class="page-item">
			<?php if($l_no<$total_page){?>
          <a class="page-link" href="<?=$_SERVER['PHP_SELF']?>?mode=<?=$mode?>&page=<?=$n_f_no?>&f_no=<?=$n_f_no?>&gubun=<?=$gubun?>&s_key=<?=$s_key?>&sword=<?=$sword?>&orderby=<?=$orderby?>" aria-label="Next">
            <span aria-hidden="true">&raquo;</span>
            <span class="sr-only">Next</span>
          </a>
		  <?php }?>
        </li>
      </ul>
    </small>
	<div style="text-align:center;">
		<form method="get" action="<?php echo $_SERVER["PHP_SELF"]?>" name="sform">
			<input type="text" name="sword" value="<?=$sword?>" id="sword" >&nbsp;<button  type="submit" id="search" class="btn btn-dark">검색</button><!-- &nbsp;<button  type="button" id="search2" class="btn btn-danger" onclick="location.href='/mobile/board/'">Reset</button> -->
		</form>
	</div>
  </div>
<br><br><br>
  
</main>


    
<?php
include $_SERVER["DOCUMENT_ROOT"]."/inc/footer.php";
?>