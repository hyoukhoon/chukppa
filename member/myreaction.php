<?php
include $_SERVER["DOCUMENT_ROOT"]."/inc/header.php";
if(!$_SESSION['loginValue']['SEMAIL']){
	echo "<script>alert('로그인 하십시오');location.href='/member/login.php'</script>";
 	exit;
 }

$LIMIT=$_GET['LIMIT']?$_GET['LIMIT']:20;
$page=$_GET['page']?$_GET['page']-1:0;
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
    <h6 class="border-bottom pb-2 mb-0"><span class="material-symbols-outlined" style="vertical-align: text-bottom;">transcribe</span>&nbsp;내글 반응</h6>

<?php
		$sql = "select * from xc_reaction r
		join xc_cboard c on r.bid=c.num
		where r.cnt>0 and r.userid='".$_SESSION['loginValue']['SEMAIL']."'";
        $order = " order by r.num desc";
        if($page < 1) $page = 1;
        $startLimit = ($page-1)*$LIMIT;//쿼리의 limit 시작 부분
        $limit = " limit $startLimit, $LIMIT";
        $query = $sql.$order.$limit;
//		echo $query;
        $result = $mysqli->query($query) or die("query error => ".$mysqli->error);
        while($rs = $result->fetch_object()){
            $rsc[]=$rs;
        }
//	print_r($rsc);
//	exit;

		$sqlcnt = "select count(*) from xc_reaction r
		join xc_cboard c on r.bid=c.num
		where r.cnt>0 and r.userid='".$_SESSION['loginValue']['SEMAIL']."'";
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

		<svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" fill="currentColor" class="bi bi-chat-left-text flex-shrink-0 me-2 rounded" viewBox="0 0 16 16">
		  <path d="M14 1a1 1 0 0 1 1 1v8a1 1 0 0 1-1 1H4.414A2 2 0 0 0 3 11.586l-2 2V2a1 1 0 0 1 1-1h12zM2 0a2 2 0 0 0-2 2v12.793a.5.5 0 0 0 .854.353l2.853-2.853A1 1 0 0 1 4.414 12H14a2 2 0 0 0 2-2V2a2 2 0 0 0-2-2H2z"/>
		  <path d="M3 3.5a.5.5 0 0 1 .5-.5h9a.5.5 0 0 1 0 1h-9a.5.5 0 0 1-.5-.5zM3 6a.5.5 0 0 1 .5-.5h9a.5.5 0 0 1 0 1h-9A.5.5 0 0 1 3 6zm0 2.5a.5.5 0 0 1 .5-.5h5a.5.5 0 0 1 0 1h-5a.5.5 0 0 1-.5-.5z"/>
		</svg>

	  <div class="pb-3 mb-0 small lh-sm border-bottom w-100">
        <div class="d-flex justify-content-between">
        <strong class="text-gray-dark">
			<?php
		if($r->retype=="board"){
				echo "[게시판]";
		  }else if($r->retype=="memo"){
				echo "[댓글]";
		  }else{
				echo "[게시판]";
		  }
	  ?>
		<?=$r->subject?>
		</strong>
		</div>
		<?php echo $r->regdate;?></span>
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
          <a class="page-link" href="<?=$_SERVER['PHP_SELF']?>?mode=<?=$mode?>&page=<?=$p_f_no?>&f_no=<?=$p_f_no?>&gubun=<?=$gubun?>&s_key=<?=$s_key?>&sword=<?=$sword?>&orderby=<?=$orderby?>" aria-label="Previous">
            <span aria-hidden="true">&laquo;</span>
            <span class="sr-only">Prev</span>
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

  </div>
<br><br><br>
  
</main>


    
<?php
include $_SERVER["DOCUMENT_ROOT"]."/inc/footer.php";
?>