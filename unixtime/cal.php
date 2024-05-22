<?php 
//ini_set('display_errors', 1);
//ini_set('display_startup_errors', 1);
//error_reporting(E_ALL);

$cyears=strip_tags($_POST['cyears']);
$cmonths=strip_tags($_POST['cmonths']);
$cdays=strip_tags($_POST['cdays']);
$chours=strip_tags($_POST['chours']);
$cminutes=strip_tags($_POST['cminutes']);
$cseconds=strip_tags($_POST['cseconds']);
if(strlen($cmonths)==1)$cmonths="0".$cmonths;
if(strlen($cdays)==1)$cdays="0".$cdays;
if(strlen($chours)==1)$chours="0".$chours;
if(strlen($cminutes)==1)$cminutes="0".$cminutes;
if(strlen($cseconds)==1)$cseconds="0".$cseconds;

$ctime=$cyears."-".$cmonths."-".$cdays." ".$chours.":".$cminutes.":".$cseconds;
$date = new DateTime($ctime, new DateTimeZone('Asia/Seoul'));


$data=array("result"=>1,"val"=>$date->getTimestamp()); 
echo json_encode($data);
exit;

 ?>