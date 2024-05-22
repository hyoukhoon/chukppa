<?php session_start();

if(!$_SESSION['loginValue']['SEMAIL']){
	$data=array("result"=>-1,"val"=>"로그인하십시오."); 
 	echo json_encode($data);
 	exit;
 }

$ctype = $_POST["ctype"];
$ext = substr(strrchr($_FILES['file']['name'],"."),1);
$ext = strtolower($ext);
$name = "oz_".time().substr(rand(),0,4);
$filename = $name.'.'.$ext;
$destination = $_SERVER["DOCUMENT_ROOT"].'/cdata/'.$filename;
$location =  $_FILES["file"]["tmp_name"];

if($_FILES['file']['size']>20480000){//10메가
			$return_data = array("result"=>"size");
			echo json_encode($return_data);
			exit;
}


if($_FILES['file']['type']!='video/mp4' and $_FILES['file']['type']!='image/gif'){//이미지가 아니면, 다른 type은		and로 추가
	$return_data = array("result"=>"image");
	echo json_encode($return_data);
	exit;
}

try{
	move_uploaded_file($location,$destination);
	exec("ffprobe -v error -select_streams v:0 -show_entries stream=width,height -of default=nw=1:nk=1 ".$destination, $output, $err);
//	echo "<pre>";
//	print_r($output);
//	exit;
	$width=$output[0];
	$height=$output[1];
	if($width>$height){
		$scalewidth = 640;
		$scaleheight = 480;
	}else{
		$scalewidth = 480;
		$scaleheight = 640;
	}
	if($ctype=="webp"){
//		exec("ffmpeg -i ".$destination." -vcodec libwebp -filter:v fps=15 -lossless 0  -compression_level 3 -q:v 70 -loop 0 -preset picture -an  ".$_SERVER["DOCUMENT_ROOT"].'/cdata/'.$name.".webp");
		exec("ffmpeg -i ".$destination." -vf 'fps=10,scale=".$scalewidth.":-1:flags=lanczos' -vcodec libwebp -lossless 0 -compression_level 6 -q:v 50 -loop 0 -preset picture -an -vsync 0  ".$_SERVER["DOCUMENT_ROOT"].'/cdata/'.$name.".webp");
	}else if($ctype=="gif"){
		exec("ffmpeg -i ".$destination." -filter_complex 'fps=10,scale=".$scalewidth.":-1:flags=lanczos,split [o1] [o2];[o1] palettegen [p]; [o2] fifo [o3];[o3] [p] paletteuse' ".$_SERVER["DOCUMENT_ROOT"].'/cdata/'.$name.".gif");
	}else if($ctype=="mp4"){
//		$trans="ffmpeg -i ".$destination." -movflags faststart -pix_fmt yuv420p -vf scale=".$width.":".$height." ".$_SERVER["DOCUMENT_ROOT"].'/cdata/'.$name.".mp4";
		$trans="ffmpeg -i ".$destination." -movflags faststart -pix_fmt yuv420p -vf \"scale=trunc(iw/2)*2:trunc(ih/2)*2\" ".$_SERVER["DOCUMENT_ROOT"].'/cdata/'.$name.".mp4";
//		$trans="ffmpeg -i ".$destination." -profile:v baseline -pix_fmt yuv420p -vf \"scale=trunc(iw/2)*2:trunc(ih/2)*2\" ".$_SERVER["DOCUMENT_ROOT"].'/cdata/'.$name.".mp4";
//		echo $trans;
		exec($trans);
	}
        
}catch (dml_exception $e) {
	$return_data = array("result"=>"error");
	echo json_encode($return_data);
	exit;
}

		$savefile=$name.".".$ctype;
		$return_data = array("result"=>"success", "savename"=>$savefile, "ctype"=>$ctype, "fn"=>$name);
		echo json_encode($return_data);
		exit;
?>
