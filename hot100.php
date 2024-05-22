<?php
include $_SERVER["DOCUMENT_ROOT"]."/inc/header.php";
$type=$_GET['type'];
if($type=="daily"){
	$title="Daily Hot";
}else if($type=="weekly"){
	$title="Weekly Hot";
}else{
	$title="Daily Hot";
}
?>


<main class="container">

  <div class="my-3 p-3 bg-body rounded shadow-sm">
    <h6 class="border-bottom pb-2 mb-0"><span class="material-symbols-outlined" style="vertical-align: text-bottom;">workspace_premium</span><?php echo $title;?> 100</h6>

<?php
$orderby=2;
//$todate=date("Y/m/d", strtotime('-1 year'))." 00:00:00";
$todate=date("Y/m/d")." 23:59:59";
if($type=="daily"){
//	$fromdate=date("Y/m/d")." 00:00:00";
	$fromdate=date("Y/m/d H:i:s", strtotime('-24 hours'));
	$size=30;
}else if($type=="weekly"){
	$fromdate=date("Y/m/d", strtotime('-7 days'))." 00:00:00";
	$size=100;
}else{
	$fromdate=date("Y/m/d", strtotime('-1 days'))." 00:00:00";
	$size=100;
}
$rangewhere = '{"lt": "'.$todate.'"}';
if($orderby==1){
    $ord='{"site_reg_date":"desc"}';
	$todate=date("Y/m/d", strtotime('+7 days'))." 00:00:00";
	$rangewhere = '{"lt": "'.$todate.'"}';
}else if($orderby==2){
    $ord='{"site_cnt":"desc", "site_reg_date":"desc"}';
	$rangewhere = '{ "gt": "'.$fromdate.'" , "lt": "'.$todate.'"}';
}

if($gubun){
	$gubunCode=',
				"must": [
					{ "term": { "multi" : "'.$gubun.'" }}
				]';
}

if($gubun){
	$gubunCode=', { "term": { "multi" : "'.$gubun.'" }}';
}

if($sword){

	$json='
	{
		"query": {
				"query_string" : {
					"fields" : ["subject", "url"],
					"query" : "*'.$sword.'*"
				}
		 },
		"size": 5,
		"from": 0,
		"sort": '.$ord.'
	}
	';
}else{

	$json='
	{
		"query": { 
            "bool": { 
                "filter": [ 
                    { 
                        "terms":  { 
                                "multi": ["ozzal"]
                                }
                    },
                    { 
                        "range": { 
                            "site_cnt": { "gt": "0" }
                            }
                    } 
                ],
                "must": [ 
                    { 
                        "range": { 
                            "site_reg_date":'.$rangewhere .'
                        } 
                    }'.$gubunCode.'
                ]
            }
        },
		"size": '.$size.',
		"from": 0,
		"sort": '.$ord.'
	}
	';

}

//echo $json;

//$url="182.162.21.6:9200/eve/_search?pretty";
$url="http://localhost:9200/ozzal/_search?pretty";

  $ch = curl_init(); // 리소스 초기화
  curl_setopt($ch, CURLOPT_URL, $url);
  curl_setopt($ch, CURLOPT_USERPWD, "elastic:soon06051007");
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
  curl_setopt($ch, CURLOPT_POST, true);
  curl_setopt($ch, CURLOPT_POSTFIELDS, $json);
  curl_setopt($ch, CURLOPT_HTTPHEADER, array(
    'Content-Type: application/json'
  ));
 
  $output = curl_exec($ch); // 데이터 요청 후 수신
  $output=json_decode($output);
  curl_close($ch);  // 리소스 해제
//  print_r($output);
//    echo "<pre>";
//    print_r($output->hits->hits);
  
	foreach($output->hits->hits as $p){

//	print_r($p);
		
?>
<a href="<?php echo $p->_source->url;?>">
    <div class="d-flex text-muted pt-3">
<?php
		if(!empty($p->_source->thumbnail)){
?>
	  <?php if($p->_source->thumbnail=="mp4"){?>
			<svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" fill="currentColor" class="bi bi-caret-right-square-fill flex-shrink-0 me-2 rounded" viewBox="0 0 16 16">
	  <path d="M0 2a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v12a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V2zm5.5 10a.5.5 0 0 0 .832.374l4.5-4a.5.5 0 0 0 0-.748l-4.5-4A.5.5 0 0 0 5.5 4v8z"/>
	</svg>
	<?php }else if($p->_source->thumbnail=="img"){?>
			<svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" fill="currentColor" class="bi bi-image flex-shrink-0 me-2 rounded" viewBox="0 0 16 16">
			  <path d="M6.002 5.5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0z"/>
			  <path d="M2.002 1a2 2 0 0 0-2 2v10a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V3a2 2 0 0 0-2-2h-12zm12 1a1 1 0 0 1 1 1v6.5l-3.777-1.947a.5.5 0 0 0-.577.093l-3.71 3.71-2.66-1.772a.5.5 0 0 0-.63.062L1.002 12V3a1 1 0 0 1 1-1h12z"/>
			</svg>
	</svg>
	<?php }else{?>
	  <!-- <img src="/board/upImages/thumb/<?php echo $p->_source->thumbnail;?>" width="40" height="40" class="bd-placeholder-img flex-shrink-0 me-2 rounded"> -->
	  <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" fill="currentColor" class="bi bi-image flex-shrink-0 me-2 rounded" viewBox="0 0 16 16">
			  <path d="M6.002 5.5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0z"/>
			  <path d="M2.002 1a2 2 0 0 0-2 2v10a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V3a2 2 0 0 0-2-2h-12zm12 1a1 1 0 0 1 1 1v6.5l-3.777-1.947a.5.5 0 0 0-.577.093l-3.71 3.71-2.66-1.772a.5.5 0 0 0-.63.062L1.002 12V3a1 1 0 0 1 1-1h12z"/>
			</svg>
	<?php }?>	 
<?php }else{?>
	<svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" fill="currentColor" class="bi bi-exclamation-square-fill flex-shrink-0 me-2 rounded" viewBox="0 0 16 16">
		  <path d="M2 0a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V2a2 2 0 0 0-2-2H2zm6 4c.535 0 .954.462.9.995l-.35 3.507a.552.552 0 0 1-1.1 0L7.1 4.995A.905.905 0 0 1 8 4zm.002 6a1 1 0 1 1 0 2 1 1 0 0 1 0-2z"/>
		</svg>
<?php }?>
	  <div class="pb-3 mb-0 small lh-sm border-bottom w-100">
        <div class="d-flex justify-content-between">
        <strong class="text-gray-dark"><?php echo mb_substr($p->_source->subject, 0, 24, 'utf-8');?><?php if($p->_source->memo_cnt){?>&nbsp;[<?php echo $p->_source->memo_cnt;?>]<?php }?>
		<?php
		
		$wd=strtotime($p->_source->site_reg_date)+86400;
		if(time()<=$wd){ 
//echo "<span style='color:red;'>N</span>";
//			echo '<span class="material-symbols-outlined">bolt</span>';
//			echo '<img src="/img/new1.png" width="25">';
echo '<svg role="img" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" width="14" height="14" style="vertical-align: top;"><title>New</title><path d="M1.6 0S0 0 0 1.6v20.8S0 24 1.6 24h20.8s1.6 0 1.6-1.6V1.6S24 0 22.4 0zm3.415 5.6h4.78l4.425 6.458V5.6h4.765v12.8h-4.78L9.78 11.943V18.4H5.015Z"/></svg>';
		}
?>
		</strong>
		<a href="#"><?=$p->_source->site_cnt?></a>
		</div>
		<span class="d-block"><?php echo $p->_source->username;?> / <?php echo $p->_source->site_reg_date;?></span>
		</div>
    </div></a>
<?php
	}

	$page=$page+1;
?>

    <!-- <small class="d-block text-end mt-3">
      <a href="#">All updates</a>
    </small> -->
  </div>
<br><br><br>
  
</main>


    
<?php
include $_SERVER["DOCUMENT_ROOT"]."/inc/footer.php";
?>