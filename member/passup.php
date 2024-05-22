<?php 
include $_SERVER["DOCUMENT_ROOT"]."/inc/header.php";

if(!$_SESSION['loginValue']['SEMAIL']){
	echo "<script>alert('로그인 하십시오');location.href='/member/login.php'</script>";
 	exit;
 }
?>
<body class="text-center">
    
<main class="container">

  <div class="my-3 p-3 bg-body rounded shadow-sm">
  <form class="row g-3 needs-validation" method="post" action="passup_ok.php" onsubmit="return sendform();">

    <h1 class="h3 mb-3 fw-normal">비밀번호변경</h1>

    <div class="form-floating">
      <input type="password" class="form-control" id="prepasswd" name="prepasswd" required>
      <label for="floatingInput">현재비밀번호</label>
    </div>
        <div class="col-12">
            <label for="validationCustom02" class="form-label">비밀번호(8자이상, 영문자, 숫자, 특수문자 포함)</label>
            <input type="password" class="form-control" id="passwd" name="passwd" placeholder="" required>
        </div>
		<div class="col-12">
            <label for="validationCustom02" class="form-label">비밀번호확인</label>
            <input type="password" class="form-control" id="repasswd" name="repasswd" placeholder="" required>
        </div>

    <button class="w-100 btn btn-lg btn-dark" type="submit">변경</button>
  </form>
<div style="padding:20px;">

	<a href="/member/memberout.php"><button class="w-100 btn btn-lg btn-dark" type="button">탈퇴하기</button></a>

</div>
</div>
</main>

<script>
	function sendform(){
		var passwd=$("#passwd").val();
		var repasswd=$("#repasswd").val();
		if(passwd!=repasswd){
			alert('비밀번호와 비밀번호확인의 값이 다릅니다.');
			return false;
		}

		if(!CheckPass(passwd)){
			alert('비밀번호 생성규칙에 위반됩니다.');
			return false;
		}

		return true;
	}

function CheckPass(str){
        var reg1 = /^[a-z0-9!~@#$%^&*()?+=\/]{8,100}$/; 
        var reg2 = /[a-z]/g;    
        var reg3 = /[0-9]/g;
        var reg4 = /[!~@#$%^&*()?+=\/]/g;
        return(reg1.test(str) &&  reg2.test(str) && reg3.test(str) && reg4.test(str));
    };

 </script>
    
  </body>

<?php
include $_SERVER["DOCUMENT_ROOT"]."/inc/footer.php";
?>
