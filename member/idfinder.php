<?php 
include $_SERVER["DOCUMENT_ROOT"]."/inc/header.php";
?>
<body class="text-center">
    
<main class="container">

  <div class="my-3 p-3 bg-body rounded shadow-sm">
  <form class="row g-3 needs-validation" method="post" name="idfinder">

    <div style="text-align:center;margin:10px;">
		<input type="radio" class="btn-check" name="ctype"  value="id" id="success-outlined" autocomplete="off" checked onclick="changetype('id')">
		<label class="btn btn-outline-primary" for="success-outlined">아이디 찾기</label>
		<input type="radio" class="btn-check" name="ctype" value="pass" id="danger-outlined" autocomplete="off" onclick="changetype('pass')">
		<label class="btn btn-outline-danger" for="danger-outlined">비밀번호 찾기</label>
	</div>
	<div class="col-12" id="usernameid" style="display:block;">
            <label for="validationCustom01" class="form-label">이름</label>
            <input type="text" class="form-control" id="username" name="username" placeholder="" required>
    </div>
    <div class="form-floating" id="email" style="display:none;">
	      <input type="email" class="form-control" id="userid" name="userid" placeholder="name@example.com">
		  <label for="floatingInput">Email address</label>
    </div>
    <button class="w-100 btn btn-lg btn-dark" type="button" id="findbutton">확인</button>
  </form>

	<div class="alert alert-primary" role="alert" id="idresult" style="padding:20px;margin-top:20px;display:none;">
	  고객님의 아이디는 <a href="#" class="alert-link" id="resultemail"></a> 입니다.
	</div>

	<div style="padding:20px;">
		<a href="/member/login.php"><button class="w-100 btn btn-lg btn-dark" type="button">로그인</button></a>
</div>
</div>
</main>
</body>

<script>

function changetype(ctype){
	if(ctype=="id"){
		$("#usernameid").show();
		$("#email").hide();
	}else if(ctype=="pass"){
		$("#usernameid").show();
		$("#email").show();
	}else{
		$("#usernameid").show();
		$("#email").hide();
	}
}

$("#findbutton").click(function () {
	var ctype=$("input[name='ctype']:checked").val();
	var username=$("#username").val();
	if(ctype=="id"){
		var email="none";
		if(!username){
			alert('이름을 입력하세요.');
			return false;
		}
	}else if(ctype=="pass"){
		var email=$("#userid").val();
		if(!username){
			alert('이름을 입력하세요.');
			return false;
		}
		if(!email){
			alert('이메일을 입력하세요.');
			return false;
		}
	}
	var data = {
				ctype : ctype,
				username : username,
				email : email
			};

	$.ajax({
		async : false ,
		type : 'post' ,
		url : '/member/idfind_ok.php' ,
		data  : data ,
		dataType : 'json' ,
		error : function() {} ,
		success : function(return_data) {
		  if(return_data.result==-1){
			alert(return_data.val);
			return;
		  }else if(return_data.result==1){
			  if(ctype=="id"){
					$("#idresult").show();
					$("#resultemail").text(return_data.val);
			  }else if(ctype=="pass"){
					$("#idresult").show();
					$("#idresult").text("문의하신 이메일로 신규 비밀번호를 발송해 드렸습니다. \n메일이 안오면 조금 더 기다려주시고 그래도 안오면 스팸함을 확인해보십시오.");
			  }	
		  }else{
			alert('관리자에게 문의하십시오.');
			return;
		  }
		}
	});

});


</script>

<?php
include $_SERVER["DOCUMENT_ROOT"]."/inc/footer.php";
?>
