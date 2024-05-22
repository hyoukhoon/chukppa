<?php
include $_SERVER["DOCUMENT_ROOT"]."/inc/header.php";
$multi="soccer";
$site_json=json_encode($site);
$LIMIT=$_GET['LIMIT']?$_GET['LIMIT']:25;
$page=$_GET['page']?$_GET['page']-1:0;
$start_page=($page)*$LIMIT;
$end_page=$LIMIT;
$ps=$LIMIT;//한페이지에 몇개를 표시할지
$sub_size=7;//아래에 나오는 페이징은 몇개를 할지
$today=date("Y-m-d");
//https://dreamgarden.oopy.io/cef8bddb-6020-4a50-9df7-8887c191c6e6 구글 로그인

//select * from xc_games where (hometeam='토트넘' or awayteam='토트넘' or hometeam='토트넘 (Eng)' or awayteam='토트넘 (Eng)');
//select * from xc_games where (hometeam='울버햄튼' or awayteam='울버햄튼' or hometeam='울버햄튼 (Eng)' or awayteam='울버햄튼 (Eng)');
//select * from xc_games where (hometeam='브렌트퍼드' or awayteam='브렌트퍼드' or hometeam='브렌트퍼드 (Eng)' or awayteam='브렌트퍼드 (Eng)');
//select * from xc_games where (hometeam='노팅엄' or awayteam='노팅엄' or hometeam='노팅엄 (Eng)' or awayteam='노팅엄 (Eng)');
//select * from xc_games where (hometeam='미튈란' or awayteam='미튈란' or hometeam='미튈란 (Den)' or awayteam='미튈란 (Den)');
//select * from xc_games where (hometeam='셀틱' or awayteam='셀틱' or hometeam='셀틱 (Sco)' or awayteam='셀틱 (Sco)');
//select * from xc_games where (hometeam='파리생제르망' or awayteam='파리생제르망' or hometeam='파리생제르망 (Fra)' or awayteam='파리생제르망 (Fra)');
//select * from xc_games where (hometeam='바이에른 뮌헨' or awayteam='바이에른 뮌헨' or hometeam='바이에른 뮌헨 (Ger)' or awayteam='바이에른 뮌헨 (Ger)');
//select * from xc_games where (hometeam='올림피아코스' or awayteam='올림피아코스' or hometeam='올림피아코스 (Gre)' or awayteam='올림피아코스 (Gre)');
//select * from xc_games where (hometeam='헨트' or awayteam='헨트' or hometeam='헨트 (Bel)' or awayteam='헨트 (Bel)');
//정우영

$query="select * from xc_games where gamedate>='".$today."' and (hometeam='토트넘' or awayteam='토트넘' or hometeam='토트넘 (Eng)' or awayteam='토트넘 (Eng)') order by gametime asc";
//$query="select * from xc_games where status='종료'";
 $result = $mysqli->query($query) or die("query error => ".$mysqli->error);
while($rs = $result->fetch_object()){
	$rsc1[]=$rs;
}

$query="select * from xc_games where gamedate>='".$today."' and (hometeam='울버햄튼' or awayteam='울버햄튼' or hometeam='울버햄튼 (Eng)' or awayteam='울버햄튼 (Eng)') order by gametime asc";
//$query="select * from xc_games where status='종료'";
 $result = $mysqli->query($query) or die("query error => ".$mysqli->error);
while($rs = $result->fetch_object()){
	$rsc2[]=$rs;
}

$query="select * from xc_games where gamedate>='".$today."' and (hometeam='바이에른 뮌헨' or awayteam='바이에른 뮌헨' or hometeam='바이에른 뮌헨 (Ger)' or awayteam='바이에른 뮌헨 (Ger)') order by gametime asc";
//$query="select * from xc_games where status='종료'";
 $result = $mysqli->query($query) or die("query error => ".$mysqli->error);
while($rs = $result->fetch_object()){
	$rsc3[]=$rs;
}

$query="select * from xc_games where gamedate>='".$today."' and (hometeam='파리생제르망' or awayteam='파리생제르망' or hometeam='파리생제르망 (Fra)' or awayteam='파리생제르망 (Fra)') order by gametime asc";
//$query="select * from xc_games where status='종료'";
 $result = $mysqli->query($query) or die("query error => ".$mysqli->error);
while($rs = $result->fetch_object()){
	$rsc4[]=$rs;
}

$query="select * from xc_games where gamedate>='".$today."' and (hometeam='노팅엄' or awayteam='노팅엄' or hometeam='노팅엄 (Eng)' or awayteam='노팅엄 (Eng)') order by gametime asc";
//$query="select * from xc_games where status='종료'";
 $result = $mysqli->query($query) or die("query error => ".$mysqli->error);
while($rs = $result->fetch_object()){
	$rsc5[]=$rs;
}

$query="select * from xc_games where gamedate>='".$today."' and (hometeam='미튈란' or awayteam='미튈란' or hometeam='미튈란 (Den)' or awayteam='미튈란 (Den)') order by gametime asc";
//$query="select * from xc_games where status='종료'";
 $result = $mysqli->query($query) or die("query error => ".$mysqli->error);
while($rs = $result->fetch_object()){
	$rsc6[]=$rs;
}

$query="select * from xc_games where gamedate>='".$today."' and (hometeam='올림피아코스' or awayteam='올림피아코스' or hometeam='올림피아코스 (Gre)' or awayteam='올림피아코스 (Gre)') order by gametime asc";
//$query="select * from xc_games where status='종료'";
 $result = $mysqli->query($query) or die("query error => ".$mysqli->error);
while($rs = $result->fetch_object()){
	$rsc7[]=$rs;
}

$query="select * from xc_games where gamedate>='".$today."' and (hometeam='셀틱' or awayteam='셀틱' or hometeam='셀틱 (Sco)' or awayteam='셀틱 (Sco)') order by gametime asc";
//$query="select * from xc_games where status='종료'";
 $result = $mysqli->query($query) or die("query error => ".$mysqli->error);
while($rs = $result->fetch_object()){
	$rsc8[]=$rs;
}

$query="select * from xc_games where gamedate>='".$today."' and (hometeam='헨트' or awayteam='헨트' or hometeam='헨트 (Bel)' or awayteam='헨트 (Bel)') order by gametime asc";
//$query="select * from xc_games where status='종료'";
 $result = $mysqli->query($query) or die("query error => ".$mysqli->error);
while($rs = $result->fetch_object()){
	$rsc9[]=$rs;
}

$query="select * from xc_games where gamedate>='".$today."' and (hometeam='브렌트퍼드' or awayteam='브렌트퍼드' or hometeam='브렌트퍼드 (Eng)' or awayteam='브렌트퍼드 (Eng)') order by gametime asc";
//$query="select * from xc_games where status='종료'";
 $result = $mysqli->query($query) or die("query error => ".$mysqli->error);
while($rs = $result->fetch_object()){
	$rsc10[]=$rs;
}

?>

  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="../plugins/fontawesome-free/css/all.min.css">
  <!-- fullCalendar -->
  <link rel="stylesheet" href="../plugins/fullcalendar/main.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="../dist/css/adminlte.min.css">
</head>
<main class="container">

	<div class="row">

    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">
        <div class="row">
          <!-- /.col -->
          <div class="col-md-12">
            <div class="card card-primary">
              <div class="card-body p-0">
                <!-- THE CALENDAR -->
                <div id="calendar"></div>
              </div>
              <!-- /.card-body -->
            </div>
            <!-- /.card -->
          </div>
          <!-- /.col -->
        </div>
        <!-- /.row -->
      </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->



<!-- jQuery -->
<script src="../plugins/jquery/jquery.min.js"></script>
<!-- Bootstrap -->
<script src="../plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- jQuery UI -->
<script src="../plugins/jquery-ui/jquery-ui.min.js"></script>
<!-- AdminLTE App -->
<script src="../dist/js/adminlte.min.js"></script>
<!-- fullCalendar 2.2.5 -->
<script src="../plugins/moment/moment.min.js"></script>
<script src="../plugins/fullcalendar/main.js"></script>
<!-- AdminLTE for demo purposes -->
<script src="../dist/js/demo.js"></script>
<!-- Page specific script -->
<script>
  $(function () {

	  

    /* initialize the calendar
     -----------------------------------------------------------------*/
    //Date for the calendar events (dummy data)
    var date = new Date()
    var d    = date.getDate(),
        m    = date.getMonth(),
        y    = date.getFullYear()

    var Calendar = FullCalendar.Calendar;
    var Draggable = FullCalendar.Draggable;


    var calendarEl = document.getElementById('calendar');

    // initialize the external events
    // -----------------------------------------------------------------


    var calendar = new Calendar(calendarEl, {
      themeSystem: 'bootstrap',
      //Random default events
      events: [
<?php
		foreach($rsc1 as $r1){
	  ?>
        {
          title          : '손흥민',
          start          : new Date('<?php echo date("Y,m,d",strtotime($r1->gamedate." ".$r1->gametime));?>'),
          allDay         : true,
          backgroundColor: false, //Success (green)
          borderColor    : false //Success (green)
        },
<?php }?>
<?php
		foreach($rsc2 as $r2){
	  ?>
        {
          title          : '황희찬',
          start          : new Date('<?php echo date("Y,m,d",strtotime($r2->gamedate." ".$r2->gametime));?>'),
          allDay         : true,
          backgroundColor: false, //Success (green)
          borderColor    : false //Success (green)
        },
<?php }?>
<?php
		foreach($rsc3 as $r3){
	  ?>
        {
          title          : '김민재',
          start          : new Date('<?php echo date("Y,m,d",strtotime($r3->gamedate." ".$r3->gametime));?>'),
          allDay         : true,
          backgroundColor: false, //Success (green)
          borderColor    : false //Success (green)
        },
<?php }?>
<?php
		foreach($rsc4 as $r4){
	  ?>
        {
          title          : '이강인',
          start          : new Date('<?php echo date("Y,m,d",strtotime($r4->gamedate." ".$r4->gametime));?>'),
          allDay         : true,
          backgroundColor: false, //Success (green)
          borderColor    : false //Success (green)
        },
<?php }?>
<?php
		foreach($rsc5 as $r5){
	  ?>
        {
          title          : '황의조',
          start          : new Date('<?php echo date("Y,m,d",strtotime($r5->gamedate." ".$r5->gametime));?>'),
          allDay         : true,
          backgroundColor: false, //Success (green)
          borderColor    : false //Success (green)
        },
<?php }?>
<?php
		foreach($rsc6 as $r6){
	  ?>
        {
          title          : '조규성',
          start          : new Date('<?php echo date("Y,m,d",strtotime($r6->gamedate." ".$r6->gametime));?>'),
          allDay         : true,
          backgroundColor: false, //Success (green)
          borderColor    : false //Success (green)
        },
<?php }?>
      ],
      editable  : true,
      droppable : true, // this allows things to be dropped onto the calendar !!!
      drop      : function(info) {
        // is the "remove after drop" checkbox checked?
        if (checkbox.checked) {
          // if so, remove the element from the "Draggable Events" list
          info.draggedEl.parentNode.removeChild(info.draggedEl);
        }
      }
    });

    calendar.render();
    // $('#calendar').fullCalendar()

    /* ADDING EVENTS */
    var currColor = '#3c8dbc' //Red by default
    // Color chooser button
    $('#color-chooser > li > a').click(function (e) {
      e.preventDefault()
      // Save color
      currColor = $(this).css('color')
      // Add color effect to button
      $('#add-new-event').css({
        'background-color': currColor,
        'border-color'    : currColor
      })
    })
    $('#add-new-event').click(function (e) {
      e.preventDefault()
      // Get value and make sure it is not null
      var val = $('#new-event').val()
      if (val.length == 0) {
        return
      }

      // Create events
      var event = $('<div />')
      event.css({
        'background-color': currColor,
        'border-color'    : currColor,
        'color'           : '#fff'
      }).addClass('external-event')
      event.text(val)
      $('#external-events').prepend(event)

      // Add draggable funtionality
      ini_events(event)

      // Remove event from text input
      $('#new-event').val('')
    })
  })

		  $('.btn-group').hide();
</script>
</main>
</html>
<?php
include $_SERVER["DOCUMENT_ROOT"]."/inc/footer.php";
?>
