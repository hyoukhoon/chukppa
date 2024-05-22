<?php include $_SERVER["DOCUMENT_ROOT"]."/inc/header.php";
$query = "select memoid, memo_file from memo where userid='".$_SESSION['loginValue']['SEMAIL']."' and status=1 and memo_file != '' order by memoid desc limit 12";
$result = $mysqli->query($query) or die("query error => ".$mysqli->error);
while($rs = $result->fetch_object()){
	$rsc[]=$rs;
}
?>
<main class="container">
<!-- Gallery -->

<button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModal">
  이미지 선택
</button>

<!-- Modal -->
<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="exampleModalLabel">이미지 선택</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
		  <div class="row">
<?php
	  foreach($rsc as $r){
?>
			<div id="m_<?php echo $r->memoid;?>" class="col-sm border" style='width:110px;padding:5px;margin-right:10px;margin-bottom:10px;text-align:center;'><img src='/board/upImages/data/<?php echo $r->memo_file;?>' style='max-width:90px;max-height:90px;'></div>
<?php }?>
		</div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>
</div>

<!-- Gallery -->
 </main>
 <?php
include $_SERVER["DOCUMENT_ROOT"]."/inc/footer.php";
?>
