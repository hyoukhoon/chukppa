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
  <form class="row g-3 needs-validation" method="get" action="chatroom.php" onsubmit="return sendform();">

    <h1 class="h3 mb-3 fw-normal">대화방 입장</h1>
    <span>대화방을 선택하고 입장을 클릭하세요.</span>
    <div class="form-floating">
      <select name="channelid">
		<option value="testroom">대화방1</option>
	</select>
    </div>
        
    <button class="w-100 btn btn-lg btn-dark" type="submit">입장</button>
  </form>
<div style="padding:20px;">

</div>
</div>
</main>

<script>
	function sendform(){
		return true;
	}


 </script>
    
  </body>

<?php
include $_SERVER["DOCUMENT_ROOT"]."/inc/footer.php";
?>
