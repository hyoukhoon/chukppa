<?php 
include $_SERVER["DOCUMENT_ROOT"]."/inc/header.php";

$client_id = "gWeNFii9g_E70clnmtNO";
$redirectURI = urlencode("https://www.zzarbang.com/member/authnaver.php");
$state = "RAMDOM_STATE";
$apiURL = "https://nid.naver.com/oauth2.0/authorize?response_type=code&client_id=".$client_id."&redirect_uri=".$redirectURI."&state=".$state;

$restapikey = "d4efbf028979714a5a4a7c5138e08997"; //rest api key 입력
$callbackUrl = "https://www.chukppa.com/member/authkakao.php"; //call back URL 입력
$kakaoUrl = "https://kauth.kakao.com/oauth/authorize?response_type=code&client_id=".$restapikey."&redirect_uri=".$callbackUrl;
 
?>
<body class="text-center">
    
<main class="container">

  <div class="my-3 p-3 bg-body rounded shadow-sm">
  <form class="row g-3 needs-validation" method="post" action="login_ok.php">
	<input type="hidden" name="moveurl" value="<?php echo $_SERVER['HTTP_REFERER'];?>">
    <h1 class="h3 mb-3 fw-normal">로그인</h1>

    <div class="form-floating">
      <input type="email" class="form-control" id="userid" name="userid" placeholder="name@example.com">
      <label for="floatingInput">Email address</label>
    </div>
    <div class="form-floating">
      <input type="password" class="form-control" id="passwd" name="passwd" placeholder="Password">
      <label for="floatingPassword">Password</label>
    </div>
	<div class="form-floating">
      <input type="checkbox" id="savelogin" name="savelogin" value="1">&nbsp;로그인기억(사이트를 이용하지 않을시 반드시 로그아웃 하십시오.)
    </div>


    <button class="w-100 btn btn-lg btn-dark" type="submit">로그인</button>
  </form>

<div style="padding:20px;margin:auto;">
	<!-- <div class="sns-login-box">
		<a href="javascript:;" class="google g_id_signin" data-type="icon" data-shape="pill" data-size="large">GOOGLE</a>
	</div> -->
	<div class="g_id_signin"
         data-type="standard"
         data-size="large"
         data-theme="outline"
         data-text="sign_in_with"
         data-shape="rectangular"
         data-logo_alignment="center">
      </div>
	<script src="https://accounts.google.com/gsi/client" async defer></script>
	<div id="g_id_onload"
			 data-client_id="1050609388209-rns7cd3ceb1qi6cgk7mnavgs406qo1av.apps.googleusercontent.com"
			 data-ux_mode="redirect"
			 data-login_uri="https://www.chukppa.com/member/authgoogle.php"
			 data-auto_prompt="false">
		</div>
</div>
<div style="padding:20px;text-align:center;">
	<a href="<?=$kakaoUrl?>"> <img src="/img/kakao_login.png" title="카카오톡 로그인" alt="" /></a>
</div>
<div style="padding:20px;">

	<a href="/member/signup.php"><button class="w-100 btn btn-lg btn-dark" type="button">회원가입</button></a>

</div>

<div style="padding:20px;">

	<a href="/member/idfinder.php"><button class="w-100 btn btn-lg btn-dark" type="button">아이디/비밀번호 찾기</button></a>

</div>

</div>
</main>


    
  </body>

<?php
include $_SERVER["DOCUMENT_ROOT"]."/inc/footer.php";
?>
