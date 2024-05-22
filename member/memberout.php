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
  <form class="row g-3 needs-validation" method="post" action="memberout_ok.php" onsubmit="return sendform();">

    <h1 class="h3 mb-3 fw-normal">탈퇴하기</h1>

    <div class="form-floating">
      <input type="password" class="form-control" id="passwd" name="passwd" required>
      <label for="floatingInput">비밀번호</label>
    </div>


    <button class="w-100 btn btn-lg btn-dark" type="submit">탈퇴하기</button>
  </form>
<br>
<div class="alert alert-dark" role="alert">
<svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" class="bi bi-exclamation-triangle-fill flex-shrink-0 me-2" viewBox="0 0 16 16" role="img" aria-label="Warning:">
    <path d="M8.982 1.566a1.13 1.13 0 0 0-1.96 0L.165 13.233c-.457.778.091 1.767.98 1.767h13.713c.889 0 1.438-.99.98-1.767L8.982 1.566zM8 5c.535 0 .954.462.9.995l-.35 3.507a.552.552 0 0 1-1.1 0L7.1 5.995A.905.905 0 0 1 8 5zm.002 6a1 1 0 1 1 0 2 1 1 0 0 1 0-2z"/>
  </svg>
  탈퇴하시면 회원정보는 더이상 복구할 수 없으며 같은 아이디로 다시 가입할 수 없습니다.
<br>
  <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" class="bi bi-exclamation-triangle-fill flex-shrink-0 me-2" viewBox="0 0 16 16" role="img" aria-label="Warning:">
    <path d="M8.982 1.566a1.13 1.13 0 0 0-1.96 0L.165 13.233c-.457.778.091 1.767.98 1.767h13.713c.889 0 1.438-.99.98-1.767L8.982 1.566zM8 5c.535 0 .954.462.9.995l-.35 3.507a.552.552 0 0 1-1.1 0L7.1 5.995A.905.905 0 0 1 8 5zm.002 6a1 1 0 1 1 0 2 1 1 0 0 1 0-2z"/>
  </svg>
  탈퇴해도 회원님이 작성하신 게시글이나 댓글은 삭제되지 않습니다. 삭제를 원하시면 탈퇴하시기전에 직접 삭제하셔야합니다.
</div>
  
</div>


</main>

<script>
	function sendform(){
		var passwd=$("#passwd").val();
		if(!passwd){
			alert('비밀번호를 입력하세요.');
			return false;
		}

		return true;
	}

 </script>
    
  </body>

<?php
include $_SERVER["DOCUMENT_ROOT"]."/inc/footer.php";
?>
