<!doctype html>
<html lang="en" class="h-100">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Unix timestamp 변환">
    <meta name="author" content="Unix timestamp 변환">
    <meta name="generator" content="Hugo 0.108.0">
    <title>Unix timestamp 변환</title>
	<script src="https://code.jquery.com/jquery-latest.min.js"></script>
    <link rel="canonical" href="https://getbootstrap.com/docs/5.3/examples/cover/">
	<link href="../assets/dist/css/bootstrap.min.css" rel="stylesheet">
<!-- Google tag (gtag.js) -->
<script async src="https://www.googletagmanager.com/gtag/js?id=G-JJNC7Z4Y9S"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());

  gtag('config', 'G-JJNC7Z4Y9S');
</script>
		<style>
		  .bd-placeholder-img {
			font-size: 1.125rem;
			text-anchor: middle;
			-webkit-user-select: none;
			-moz-user-select: none;
			user-select: none;
		  }

		  @media (min-width: 768px) {
			.bd-placeholder-img-lg {
			  font-size: 3.5rem;
			}
		  }

		  .b-example-divider {
			height: 3rem;
			background-color: rgba(0, 0, 0, .1);
			border: solid rgba(0, 0, 0, .15);
			border-width: 1px 0;
			box-shadow: inset 0 .5em 1.5em rgba(0, 0, 0, .1), inset 0 .125em .5em rgba(0, 0, 0, .15);
		  }

		  .b-example-vr {
			flex-shrink: 0;
			width: 1.5rem;
			height: 100vh;
		  }

		  .bi {
			vertical-align: -.125em;
			fill: currentColor;
		  }

		  .nav-scroller {
			position: relative;
			z-index: 2;
			height: 2.75rem;
			overflow-y: hidden;
		  }

		  .nav-scroller .nav {
			display: flex;
			flex-wrap: nowrap;
			padding-bottom: 1rem;
			margin-top: -1px;
			overflow-x: auto;
			text-align: center;
			white-space: nowrap;
			-webkit-overflow-scrolling: touch;
		  }
		</style>

    
    <!-- Custom styles for this template -->
    <link href="cover.css" rel="stylesheet">
	<script async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js?client=ca-pub-3926380236997271"
     crossorigin="anonymous"></script>
  </head>
  <body class="d-flex h-100 text-center text-bg-dark">
    
<div class="cover-container d-flex w-100 h-100 p-3 mx-auto flex-column">
  <header class="mb-auto">
    <div>
      <h3 class="float-md-start mb-0">UNIXTIMESTAMP</h3>
      <nav class="nav nav-masthead justify-content-center float-md-end">
        <a class="nav-link fw-bold py-1 px-0" href="/">Home</a>
        <a class="nav-link fw-bold py-1 px-0 active" aria-current="page" href="/unixtime/">Unixtime</a>
		<a class="nav-link fw-bold py-1 px-0" href="/convert/convert.php">Mp4toWebp</a>
      </nav>
    </div>
  </header>
  <main class="px-3">
	<div>
		<h1>UNIXTIMESTAMP NOW</h1>
		<input class="form-control form-control-lg" type="text" id="unixtimearea" style="width:480px;margin:auto;" aria-label=".form-control-lg example" value="<?php echo time();?>">
	</div>
	<br><br>
	<div>
		<h1>Date to UNIXTIMESTAMP</h1>
		<table border=0 align="center">
		<tr>
			<td>
					<input class="form-control form-control-lg" type="text" style="width:160px;" id="cyears" aria-label=".form-control-lg example" value="<?php echo date("Y");?>">
			</td>
			<td>
				<select class="form-select" aria-label="Default select example" style="width:160px;padding:10px;" id="cmonths">
				  <option>Select Month</option>
						<?php 
							for($k=1;$k<=12;$k++){?>
							<option value="<?php echo $k;?>" <?php if($k==date("m")){echo "selected";}?>><?php echo $k;?>월</option>
						<?php }?>
				</select>
			</td>
			<td>
				<select class="form-select" aria-label="Default select example" style="width:160px;padding:10px;" id="cdays">
				  <option>Select Day</option>
						<?php 
							for($k=1;$k<=31;$k++){?>
							<option value="<?php echo $k;?>" <?php if($k==date("d")){echo "selected";}?>><?php echo $k;?>일</option>
						<?php }?>
				</select>
			</td>
		</tr>
		<tr>
			<td>
				<select class="form-select" aria-label="Default select example" style="width:160px;padding:10px;" id="chours">
				  <option>Select Hour</option>
						<?php 
							for($k=0;$k<=23;$k++){?>
							<option value="<?php echo $k;?>" <?php if($k==date("H")){echo "selected";}?>><?php echo $k;?>시</option>
						<?php }?>
				</select>
			</td>
			<td>
				<select class="form-select" aria-label="Default select example" style="width:160px;padding:10px;" id="cminutes">
				  <option>Select Minute</option>
						<?php 
							for($k=0;$k<=59;$k++){?>
							<option value="<?php echo $k;?>" <?php if($k==date("i")){echo "selected";}?>><?php echo $k;?>분</option>
						<?php }?>
				</select>
			</td>
			<td>
				<select class="form-select" aria-label="Default select example" style="width:160px;padding:10px;" id="cseconds">
				  <option>Select Second</option>
						<?php 
							for($k=0;$k<=59;$k++){?>
							<option value="<?php echo $k;?>" <?php if($k==date("s")){echo "selected";}?>><?php echo $k;?>초</option>
						<?php }?>
				</select>
			</td>
			<td>
		</tr>
		</table>
		<div style="margin-top:20px;display:none;" id="caldiv">
			<input class="form-control form-control-lg" type="text" id="caltime" style="width:480px;margin:auto;" aria-label=".form-control-lg example" value="">
		</div>
		<div style="margin-top:20px;">
			<button type="button" class="btn btn-warning" id="calc">Calculate</button>
		</div>
	</div>
	<br><br>
	<div>
		<h1>UNIXTIMESTAMP To Date </h1>
		<input class="form-control form-control-lg" type="text" id="unixtimetodate" style="width:480px;margin:auto;" aria-label=".form-control-lg example">
	</div>
	<div style="margin-top:20px;display:none;" id="caldatediv">
		<input class="form-control form-control-lg" type="text" id="caldate" style="width:480px;margin:auto;" aria-label=".form-control-lg example" value="">
	</div>
	<div style="margin-top:20px;">
		<button type="button" class="btn btn-warning" id="calcdate">Calculate</button>
	</div>
  </main>

  <footer class="mt-auto text-white-50">
    <p>Copyleft ZZarbang</p>
  </footer>
</div>

   <script>
	$(function() {
		timer = setInterval( function () {
				var nt=Math.floor((new Date()).getTime() / 1000);
				$("#unixtimearea").val(nt);
			}, 1000);
	});

	$("#calc").click(function () {

		var cyears=$("#cyears").val();
		var cmonths=$("#cmonths option:selected").val();
		var cdays=$("#cdays option:selected").val();
		var chours=$("#chours option:selected").val();
		var cminutes=$("#cminutes option:selected").val();
		var cseconds=$("#cseconds option:selected").val();

		if(!cyears || !cmonths || !cdays || !chours || !cminutes || !cseconds){
			alert('필수값이 누락됐습니다.');
			return false;
		}

		var data = {
				cyears : cyears,
				cmonths : cmonths,
				cdays : cdays,
				chours : chours,
				cminutes : cminutes,
				cseconds : cseconds
			};

		$.ajax({
			async : false ,
			type : 'post' ,
			url : '/unixtime/cal.php' ,
			data  : data ,
			dataType : 'json' ,
			error : function() {} ,
			success : function(return_data) {
			  if(return_data.result==-1){
				alert(return_data.val);
				return;
			  }else if(return_data.result==1){
					$("#caltime").val(return_data.val);
					$("#caldiv").show();
			  }
			}
		});

	});

	$("#calcdate").click(function () {

		var unixtimetodate=$("#unixtimetodate").val();

		if(!unixtimetodate){
			alert('필수값이 누락됐습니다.');
			return false;
		}

		var data = {
				unixtimetodate : unixtimetodate
			};

		$.ajax({
			async : false ,
			type : 'post' ,
			url : '/unixtime/caldate.php' ,
			data  : data ,
			dataType : 'json' ,
			error : function() {} ,
			success : function(return_data) {
			  if(return_data.result==-1){
				alert(return_data.val);
				return;
			  }else if(return_data.result==1){
					$("#caldate").val(return_data.val);
					$("#caldatediv").show();
			  }
			}
		});

	});
   </script>
  </body>
</html>
