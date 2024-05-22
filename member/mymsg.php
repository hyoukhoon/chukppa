<?php
include $_SERVER["DOCUMENT_ROOT"]."/inc/header.php";
if(!$_SESSION['loginValue']['SEMAIL']){
	echo "<script>alert('로그인 하십시오');location.href='/member/login.php'</script>";
 	exit;
 }

$sql = "select m.*,u.nickName as fromname from xc_msg m
join xc_member u on m.fromuserid=u.email
where m.status>0 and m.touserid='".$_SESSION['loginValue']['SEMAIL']."'";
$order = " order by m.num desc";
$query = $sql.$order;
$result = $mysqli->query($query) or die("query error => ".$mysqli->error);
while($rs = $result->fetch_object()){
	$rsc[]=$rs;
}

$sql2 = "select m.*,u.nickName as toname from xc_msg m
join xc_member u on m.touserid=u.email
where m.status>0 and m.fromuserid='".$_SESSION['loginValue']['SEMAIL']."'";
$order2 = " order by m.num desc";
$query2 = $sql2.$order2;
$result2 = $mysqli->query($query2) or die("query error => ".$mysqli->error);
while($rs2 = $result2->fetch_object()){
	$rsc2[]=$rs2;
}

?>
<main>
<div class="my-3 p-3 bg-body rounded shadow-sm">

	<ul class="nav nav-tabs" id="myTab" role="tablist">
	  <li class="nav-item" role="presentation">
		<button class="nav-link active" id="home-tab" data-bs-toggle="tab" data-bs-target="#home-tab-pane" type="button" role="tab" aria-controls="home-tab-pane" aria-selected="true"><span class="material-symbols-outlined" style="vertical-align: text-bottom;">move_to_inbox</span> 받은 쪽지</button>
	  </li>
	  <li class="nav-item" role="presentation">
		<button class="nav-link" id="profile-tab" data-bs-toggle="tab" data-bs-target="#profile-tab-pane" type="button" role="tab" aria-controls="profile-tab-pane" aria-selected="false"><span class="material-symbols-outlined" style="vertical-align: text-bottom;">outbox</span>보낸 쪽지</button>
	  </li>
	</ul>
	<div class="tab-content" id="myTabContent">
	  <div class="tab-pane fade show active" id="home-tab-pane" role="tabpanel" aria-labelledby="home-tab" tabindex="0">
	  <?php 
		foreach($rsc as $r){
	  ?>
	  <a href="javascript:;" onclick="msgread('<?php echo $r->num?>', 'receive');">
				<div class="d-flex text-muted pt-3">
				<?php if($r->status==1){?>
				<svg xmlns="http://www.w3.org/2000/svg" style="color:red;" width="30" height="30" fill="currentColor" class="bi bi-envelope-heart flex-shrink-0 me-2 rounded" viewBox="0 0 16 16">
					  <path fill-rule="evenodd" d="M0 4a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v8a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V4Zm2-1a1 1 0 0 0-1 1v.217l3.235 1.94a2.76 2.76 0 0 0-.233 1.027L1 5.384v5.721l3.453-2.124c.146.277.329.556.55.835l-3.97 2.443A1 1 0 0 0 2 13h12a1 1 0 0 0 .966-.741l-3.968-2.442c.22-.28.403-.56.55-.836L15 11.105V5.383l-3.002 1.801a2.76 2.76 0 0 0-.233-1.026L15 4.217V4a1 1 0 0 0-1-1H2Zm6 2.993c1.664-1.711 5.825 1.283 0 5.132-5.825-3.85-1.664-6.843 0-5.132Z"/>
					</svg>
				<?php }else if($r->status==2){?>
					<svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" fill="currentColor" class="bi bi-envelope-open-heart flex-shrink-0 me-2 rounded" viewBox="0 0 16 16">
					  <path fill-rule="evenodd" d="M8.47 1.318a1 1 0 0 0-.94 0l-6 3.2A1 1 0 0 0 1 5.4v.817l3.235 1.94a2.76 2.76 0 0 0-.233 1.027L1 7.384v5.733l3.479-2.087c.15.275.335.553.558.83l-4.002 2.402A1 1 0 0 0 2 15h12a1 1 0 0 0 .965-.738l-4.002-2.401c.223-.278.408-.556.558-.831L15 13.117V7.383l-3.002 1.801a2.76 2.76 0 0 0-.233-1.026L15 6.217V5.4a1 1 0 0 0-.53-.882l-6-3.2ZM7.06.435a2 2 0 0 1 1.882 0l6 3.2A2 2 0 0 1 16 5.4V14a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V5.4a2 2 0 0 1 1.059-1.765l6-3.2ZM8 7.993c1.664-1.711 5.825 1.283 0 5.132-5.825-3.85-1.664-6.843 0-5.132Z"/>
					</svg>
				<?php }?>
				  <div class="pb-3 mb-0 small lh-sm border-bottom w-100">
					<div class="d-flex justify-content-between">
					<strong class="text-gray-dark"><b><?php echo $r->fromname;?></b>님으로 부터 온 쪽지입니다.</strong>
					<a href="javascript:;" onclick="msgdel('<?php echo $r->num;?>', 'receive');"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-trash3-fill" viewBox="0 0 16 16">
					  <path d="M11 1.5v1h3.5a.5.5 0 0 1 0 1h-.538l-.853 10.66A2 2 0 0 1 11.115 16h-6.23a2 2 0 0 1-1.994-1.84L2.038 3.5H1.5a.5.5 0 0 1 0-1H5v-1A1.5 1.5 0 0 1 6.5 0h3A1.5 1.5 0 0 1 11 1.5Zm-5 0v1h4v-1a.5.5 0 0 0-.5-.5h-3a.5.5 0 0 0-.5.5ZM4.5 5.029l.5 8.5a.5.5 0 1 0 .998-.06l-.5-8.5a.5.5 0 1 0-.998.06Zm6.53-.528a.5.5 0 0 0-.528.47l-.5 8.5a.5.5 0 0 0 .998.058l.5-8.5a.5.5 0 0 0-.47-.528ZM8 4.5a.5.5 0 0 0-.5.5v8.5a.5.5 0 0 0 1 0V5a.5.5 0 0 0-.5-.5Z"/>
					</svg></a>
					</div>
					<?php echo $r->regdate;?></span>
					</div>
				</div>
	</a>
		<?php }?>
	  </div>
	  <div class="tab-pane fade" id="profile-tab-pane" role="tabpanel" aria-labelledby="profile-tab" tabindex="0">
	  
			<?php 
		foreach($rsc2 as $r2){
	  ?>
	  <a href="javascript:;" onclick="msgread('<?php echo $r2->num?>', 'send');">
				<div class="d-flex text-muted pt-3">
				<?php if($r2->status==1){?>
				<svg xmlns="http://www.w3.org/2000/svg" style="color:red;" width="30" height="30" fill="currentColor" class="bi bi-envelope-heart flex-shrink-0 me-2 rounded" viewBox="0 0 16 16">
					  <path fill-rule="evenodd" d="M0 4a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v8a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V4Zm2-1a1 1 0 0 0-1 1v.217l3.235 1.94a2.76 2.76 0 0 0-.233 1.027L1 5.384v5.721l3.453-2.124c.146.277.329.556.55.835l-3.97 2.443A1 1 0 0 0 2 13h12a1 1 0 0 0 .966-.741l-3.968-2.442c.22-.28.403-.56.55-.836L15 11.105V5.383l-3.002 1.801a2.76 2.76 0 0 0-.233-1.026L15 4.217V4a1 1 0 0 0-1-1H2Zm6 2.993c1.664-1.711 5.825 1.283 0 5.132-5.825-3.85-1.664-6.843 0-5.132Z"/>
					</svg>
				<?php }else if($r2->status==2){?>
					<svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" fill="currentColor" class="bi bi-envelope-open-heart flex-shrink-0 me-2 rounded" viewBox="0 0 16 16">
					  <path fill-rule="evenodd" d="M8.47 1.318a1 1 0 0 0-.94 0l-6 3.2A1 1 0 0 0 1 5.4v.817l3.235 1.94a2.76 2.76 0 0 0-.233 1.027L1 7.384v5.733l3.479-2.087c.15.275.335.553.558.83l-4.002 2.402A1 1 0 0 0 2 15h12a1 1 0 0 0 .965-.738l-4.002-2.401c.223-.278.408-.556.558-.831L15 13.117V7.383l-3.002 1.801a2.76 2.76 0 0 0-.233-1.026L15 6.217V5.4a1 1 0 0 0-.53-.882l-6-3.2ZM7.06.435a2 2 0 0 1 1.882 0l6 3.2A2 2 0 0 1 16 5.4V14a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V5.4a2 2 0 0 1 1.059-1.765l6-3.2ZM8 7.993c1.664-1.711 5.825 1.283 0 5.132-5.825-3.85-1.664-6.843 0-5.132Z"/>
					</svg>
				<?php }?>
				  <div class="pb-3 mb-0 small lh-sm border-bottom w-100">
					<div class="d-flex justify-content-between">
					<strong class="text-gray-dark"><b><?php echo $r2->toname;?></b>님께 보낸 쪽지입니다.</strong>
					<a href="javascript:;" onclick="msgdel('<?php echo $r2->num;?>', 'send');"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-trash3-fill" viewBox="0 0 16 16">
					  <path d="M11 1.5v1h3.5a.5.5 0 0 1 0 1h-.538l-.853 10.66A2 2 0 0 1 11.115 16h-6.23a2 2 0 0 1-1.994-1.84L2.038 3.5H1.5a.5.5 0 0 1 0-1H5v-1A1.5 1.5 0 0 1 6.5 0h3A1.5 1.5 0 0 1 11 1.5Zm-5 0v1h4v-1a.5.5 0 0 0-.5-.5h-3a.5.5 0 0 0-.5.5ZM4.5 5.029l.5 8.5a.5.5 0 1 0 .998-.06l-.5-8.5a.5.5 0 1 0-.998.06Zm6.53-.528a.5.5 0 0 0-.528.47l-.5 8.5a.5.5 0 0 0 .998.058l.5-8.5a.5.5 0 0 0-.47-.528ZM8 4.5a.5.5 0 0 0-.5.5v8.5a.5.5 0 0 0 1 0V5a.5.5 0 0 0-.5-.5Z"/>
					</svg></a>
					</div>
					<?php echo $r2->regdate;?></span>
					</div>
				</div>
	</a>
		<?php }?>

	  </div>
	</div>

</div>
</main>
<div class="modal fade" id="msgviewModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
		<input type="hidden" id="msgid">
        <h1 class="modal-title fs-5" id="exampleModalLabel">쪽지 보기</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
          <div class="mb-3" id="msgviewarea">
          </div>
      </div>
      <div class="modal-footer">
		<button type="button" class="btn btn-danger" onclick="msgpolice();">신고및차단</button>
		<button type="button" class="btn btn-secondary" data-bs-dismiss="modal" id="msgcancel">닫기</button>
      </div>
    </div>
  </div>
</div>
<script>
function msgread(msgid, type){

	if(msgid){

		var data = {
				msgid : msgid,
				type : type
			};

		$.ajax({
			async : false ,
			type : 'post' ,
			url : '/member/msg_view.php' ,
			data  : data ,
			dataType : 'json' ,
			error : function() {} ,
			success : function(return_data) {
			  if(return_data.result==-1){
				alert(return_data.val);
				return;
			  }else if(return_data.result==1){
					$("#msgid").val(msgid);
					$("#msgviewarea").text(return_data.viewdata);
					$("#msgviewModal").modal("show");
			  }else{
				alert('관리자에게 문의하십시오.');
				return;
			  }
			}
		});

	}else{
		alert('필수값이 누락됐습니다.');
		return false;
	}

}


function msgdel(msgid, type){

	if(!confirm('삭제하시겠습니까?')){
		return false;
	}

	if(msgid){

		var data = {
				msgid : msgid,
				type : type
			};

		$.ajax({
			async : false ,
			type : 'post' ,
			url : '/member/msg_del.php' ,
			data  : data ,
			dataType : 'json' ,
			error : function() {} ,
			success : function(return_data) {
			  if(return_data.result==-1){
				alert(return_data.val);
				return;
			  }else if(return_data.result==1){
					alert('삭제했습니다.');
					location.reload();
			  }else{
				alert('관리자에게 문의하십시오.');
				return;
			  }
			}
		});

	}else{
		alert('필수값이 누락됐습니다.');
		return false;
	}

}

function msgpolice(){

	if(!confirm('차단하시겠습니까?')){
		return false;
	}

	var msgid=$("#msgid").val();

	if(msgid){

		var data = {
				msgid : msgid
			};

		$.ajax({
			async : false ,
			type : 'post' ,
			url : '/member/msg_block.php' ,
			data  : data ,
			dataType : 'json' ,
			error : function() {} ,
			success : function(return_data) {
			  if(return_data.result==-1){
				alert(return_data.val);
				return;
			  }else if(return_data.result==1){
					alert('차단했습니다.');
					location.reload();
			  }else{
				alert('관리자에게 문의하십시오.');
				return;
			  }
			}
		});

	}else{
		alert('필수값이 누락됐습니다.');
		return false;
	}

}
</script>
<?php
include $_SERVER["DOCUMENT_ROOT"]."/inc/footer.php";
?>