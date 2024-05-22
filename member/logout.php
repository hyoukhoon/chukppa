<?php ob_start();
session_start();
session_destroy();
setcookie("semail", "", time() + 86400, "/");
setcookie("suname", "", time() + 86400, "/");

echo "<script>alert('로그아웃 되었습니다.');location.href='/ozzal.php';</script>";
exit;
?>