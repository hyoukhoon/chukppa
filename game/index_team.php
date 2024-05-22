<?php
include $_SERVER["DOCUMENT_ROOT"]."/inc/header.php";
//ini_set('display_errors', 1);
//ini_set('display_startup_errors', 1);
//error_reporting(E_ALL);
$today=date("Y-m-d");
$selcn=$_GET['selcn'];
$sellg=$_GET['sellg'];
$seltm=$_GET['seltm'];

?>
<style>
	.page-link a{color: black;}
</style>

<main class="container">
<div style="padding:10px;text-align:center;">
	<input type="radio" class="btn-check" name="weeks" id="korean" autocomplete="off" onclick="location.href='index.php'">
	<label class="btn btn-secondary" for="korean">해외파경기일정</label>

	<input type="radio" class="btn-check" name="weeks" id="weeksleague" autocomplete="off" onclick="location.href='index_plan.php'">
	<label class="btn btn-secondary" for="weeksleague">주요리그경기일정</label>

	<input type="radio" class="btn-check" name="weeks" id="weeksteams" autocomplete="off" checked>
	<label class="btn btn-secondary" style="background-color:#000;" for="weeksteams">팀별경기</label>
</div>
<div class="row" style="justify-content:center;<?php if(isMobile()){?>margin-top:20px;<?php }?>">

<!--div class="col-lg-6 mb-4">
	<div class="card bg-dark text-white shadow">
		<div class="card-body">
			<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-calendar2-day" viewBox="0 0 16 16">
			  <path d="M4.684 12.523v-2.3h2.261v-.61H4.684V7.801h2.464v-.61H4v5.332h.684zm3.296 0h.676V9.98c0-.554.227-1.007.953-1.007.125 0 .258.004.329.015v-.613a1.806 1.806 0 0 0-.254-.02c-.582 0-.891.32-1.012.567h-.02v-.504H7.98v4.105zm2.805-5.093c0 .238.192.425.43.425a.428.428 0 1 0 0-.855.426.426 0 0 0-.43.43zm.094 5.093h.672V8.418h-.672v4.105z"/>
			  <path d="M3.5 0a.5.5 0 0 1 .5.5V1h8V.5a.5.5 0 0 1 1 0V1h1a2 2 0 0 1 2 2v11a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V3a2 2 0 0 1 2-2h1V.5a.5.5 0 0 1 .5-.5zM2 2a1 1 0 0 0-1 1v11a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1V3a1 1 0 0 0-1-1H2z"/>
			  <path d="M2.5 4a.5.5 0 0 1 .5-.5h10a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5H3a.5.5 0 0 1-.5-.5V4z"/>
			</svg>&nbsp;팀별 경기 일정 검색
		</div>
	</div>
</div><p style="word-spacing: 5px;;"></p-->
<!-- 팀별 -->

<?php
$query="SELECT * FROM xc_soccerworld where gubun='c' order by ord desc, title";
$result = $mysqli->query($query) or die("query error => ".$mysqli->error);
while($rs = $result->fetch_object()){
	$cs[]=$rs;//나라
}

?>

		<span class="col">
			<select class="form-select" aria-label="Default select example" name="country" id="country" onchange="selcountry(this,1)">
			  <option selected>국가선택</option>
				<?php 
					foreach($cs as $c){
				?>
					  <option value="<?php echo $c->sid;?>"><?php echo $c->title;?></option>
				<?php }?>
			</select>
		</span>
		<span class="col">
			<select class="form-select" aria-label="Default select example" name="league" id="league" onchange="selleague(this,1)">
			  <option selected>국가선택</option>
			</select>
		</span>
		<span class="col">
			<select class="form-select" aria-label="Default select example" name="team" id="team" onchange="selteam(this,1)">
			  <option selected>국가선택</option>
			</select>
		</span>
		<span id="searchresult">
		</span>
		

</div>
</main>
<br><br>
<br><br>
<script>
	function selcountry(t,n){
			var selcn=$(t).val();//선택국가

			var data = {
				selcn : selcn
			};
			$.ajax({
					async : false ,
					type : 'post' ,
					url : 'selcn.php' ,
					data  : data ,
					dataType : 'html' ,
					error : function(e) {
						console.log(e);
					} ,
					success : function(data) {
						$("#league").html(data);
						$("#team").html("<option selected>리그선택</option>");
						$("#searchresult").html("");
					}
			});
		
	}

	function selleague(t,n){
			var sellg=$(t).val();//선택리그
			var selcn=$("#country option:selected").val();
			var data = {
				sellg : sellg,
				selcn : selcn
			};
			$.ajax({
					async : false ,
					type : 'post' ,
					url : 'sellg.php' ,
					data  : data ,
					dataType : 'html' ,
					error : function(e) {
						console.log(e);
					} ,
					success : function(data) {
						$("#team").html(data);
						$("#searchresult").html("");
					}
			});
		
	}

	function selteam(t,n){
			var seltm=$(t).val();//선택팀
			var sellg=$("#league option:selected").val();
			var selcn=$("#country option:selected").val();

			var data = {
				sellg : sellg,
				selcn : selcn,
				seltm : seltm
			};
			$.ajax({
					async : false ,
					type : 'post' ,
					url : 'selteam.php' ,
					data  : data ,
					dataType : 'html' ,
					error : function(e) {
						console.log(e);
					} ,
					success : function(data) {
						$("#searchresult").html(data);
					}
			});
	}

	
</script>
<?php
include $_SERVER["DOCUMENT_ROOT"]."/inc/footer.php";
?>