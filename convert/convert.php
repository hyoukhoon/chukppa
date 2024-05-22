<?php
include $_SERVER["DOCUMENT_ROOT"]."/inc/header.php";
if(!$_SESSION['loginValue']['SEMAIL']){
	echo "<script>alert('로그인 하십시오');location.href='/member/login.php'</script>";
 	exit;
 }
?>
<main class="container">	
	
	<div class="card mb-4" style="margin:10px;">
		<div class="card-header"><span class="material-symbols-outlined" style="vertical-align: text-bottom;">change_circle</span>미디어 변환</div>
		<div class="card-body">
			<div class="alert alert-secondary" role="alert">
					* 10MB 이하의 파일만 변환할 수 있습니다.<br>
					* 등록한 파일은 예고없이 삭제될 수 있습니다.<br>
					* 파일변환에 대한 책임을 지지 않습니다.<br>
			</div>
			<form method="post" action="convert_ok.php" enctype="multipart/form-data">
			<div style="text-align:center;margin:10px;">
				<input type="radio" class="btn-check" name="ctype"  value="webp" id="success-outlined" autocomplete="off" checked>
				<label class="btn btn-outline-primary" for="success-outlined">mp4 to webp</label>
				<input type="radio" class="btn-check" name="ctype" value="mp4" id="danger-outlined" autocomplete="off">
				<label class="btn btn-outline-danger" for="danger-outlined">gif to mp4</label>
			</div>
			<div style="text-align:center;margin:10px;" id="filelist">
				<button type="button" class="btn btn-warning" id="convert_file" style="width:220px;height:60px;">변환 파일 선택</button>
				<input type="file" name="upfile" accept=".mp4" id="upfile" style="display:none;">
			</div>
			<div style="text-align:center;margin:10px;display:none;">
				<input type="submit" class="btn btn-dark" value="변환" style="width:220px;height:40px;">
			</div>
			</form>
			<div class="alert alert-info" role="alert" id="msg" style="display:none;">
					 파일을 변환중입니다. 잠시만 기다려주십시오.
			</div>
			<div style="text-align:center;margin:10px;display:none;" id="convertfile">
				
			</div>
		</div>
	</div>
</main>
<script>
$("#convert_file").click(function () {
	var ctype=$("input[name='ctype']:checked").val();
	if(ctype=="webp"){
		$('#upfile').attr("accept", ".mp4");
	}else if(ctype=="mp4"){
		$('#upfile').attr("accept", ".gif");
	}
	$('#upfile').click();
});

$("#upfile").change(function(){
	var formData = new FormData();
	var files = $('#upfile').prop('files');
	attachFile(files[0]);
	$("#msg").show();
});   

function attachFile(file) {
    var formData = new FormData();
	var ctype=$("input[name='ctype']:checked").val();
    formData.append("file", file);
	formData.append("ctype", ctype);
    $.ajax({
        url: '/convert/convert.json.php',
        data: formData,
        cache: false,
        contentType: false,
        processData: false,
		dataType : 'json' ,
        type: 'POST',
        success: function (return_data) {
//			console.log(JSON.stringify(return_data));
			if(return_data.result=='size'){
				alert('업로드 용량은 10메가 제한입니다.');
				return;
			}else if(return_data.result=='image'){
				alert('webp 파일이나 mp4파일만 업로드 할 수 있습니다.');
				return;
			}else{
                if(return_data.ctype=="webp"){
					var html = "<img src=\"/cdata/"+return_data.savename+"\" style=\"max-width:100%;\">";
				}else if(return_data.ctype=="mp4"){
					var html = "<video controls><source src=\"/cdata/"+return_data.savename+"\" type=\"video/mp4\"></video>";
				}
				$("#msg").hide();
                $("#convertfile").append(html);
				$("#convertfile").show();
				
			}
        }
		, beforeSend: function () {
              var width = 0;
              var height = 0;
              var left = 0;
              var top = 0;
              width = 50;
              height = 50;

			  top = ( $(window).height() - height ) / 2 + $(window).scrollTop();
              left = ( $(window).width() - width ) / 2 + $(window).scrollLeft();

              if($("#div_ajax_load_image").length != 0) {
                     $("#div_ajax_load_image").css({
                            "top": top+"px",
                            "left": left+"px"
                     });
                     $("#div_ajax_load_image").show();
              }
              else {
                     $('body').append('<div id="div_ajax_load_image" style="position:absolute; top:' + top + 'px; left:' + left + 'px; width:' + width + 'px; height:' + height + 'px; z-index:9999;" class="spinner-border" role="status"><span class="visually-hidden">Loading...</span></div>');
              }

       }
	    , complete: function () {
                     $("#div_ajax_load_image").hide();
       }
    });

}

</script>
<?php
include $_SERVER["DOCUMENT_ROOT"]."/inc/footer.php";
?>