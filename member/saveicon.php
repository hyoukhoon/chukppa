<?php session_start();
include $_SERVER['DOCUMENT_ROOT']."/inc/dbcon.php";
 if(!$_SESSION['loginValue']['SEMAIL']){
 	$data=array("result"=>-1,"val"=>"로그인하십시오."); 
 	echo json_encode($data);
 	exit;
 }

$email=$_SESSION['loginValue']['SEMAIL'];
$multi="ozzal";
$gubun=$_POST['gubun'];

		if($_FILES['file']['size']>5242880){//5메가
			$return_data = array("result"=>"image");
			echo json_encode($return_data);
			exit;
		}
		//jfif
		if($_FILES['file']['type']!='image/jpeg' and $_FILES['file']['type']!='image/png'){//이미지가 아니면, 다른 type은		and로 추가
			$return_data = array("result"=>"image");
			echo json_encode($return_data);
			exit;
		}
		
		$filename = $_FILES["file"]["name"];
		$ext = pathinfo($filename,PATHINFO_EXTENSION);//확장자 구하기
        $name = "mm_".$now3.substr(rand(),0,5);
        $filename = $name.'.'.$ext;
		$destination = $_SERVER["DOCUMENT_ROOT"].'/board/upImages/data/'.$filename;
        $location =  $_FILES["file"]["tmp_name"];
		if(move_uploaded_file($location,$destination)){
			$imgpath=$_SERVER["DOCUMENT_ROOT"]."/board/upImages/data/";
			$thumbpath=$_SERVER["DOCUMENT_ROOT"]."/board/upImages/thumb/";
			$original_image = $filename;
			$width = 50;
			$height = 50;
			if(make_thumb($imgpath.$original_image, $thumbpath.'t_'.$original_image, $width, $height)){
				$thumbnail="t_".$original_image;
			}else{
				$thumbnail="";
			}
			$query="update xc_member set photo='".$thumbnail."' where email='".$_SESSION['loginValue']['SEMAIL']."'";
			$sql1=$mysqli->query($query) or die("55:".$mysqli->error);
		}
		


        $savefile="/board/upImages/thumb/".$thumbnail;
		$_SESSION['loginValue']['PHOTO']= $savefile;
		$return_data = array("result"=>"success", "savename"=>$savefile, "filename"=>$thumbnail, "fn"=>$name);
		echo json_encode($return_data);
		exit;

?>