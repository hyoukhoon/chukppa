<?php 
//ini_set('display_errors', 1);
//ini_set('display_startup_errors', 1);
//error_reporting(E_ALL);

$unixtimetodate=strip_tags($_POST['unixtimetodate']);
$dt=date("Y-m-d H:i:s",$unixtimetodate);
$data=array("result"=>1,"val"=>$dt); 
echo json_encode($data);
exit;

 ?>