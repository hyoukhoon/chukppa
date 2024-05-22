$(function(){
	$("#btn_login").click(function(){
		var email=$("#joinEmail").val();
		var passwd=$("#joinPw").val();
		var params = "email="+email+"&passwd="+passwd;

		if(!email){
			alert('Enter your Email');
			return;
		}else if(!passwd){
			alert('Enter your Password');
			return;
		}

		$.ajax({
			  type: 'post'
			, url: '/html/login_proc.php'
			,data : params
			, dataType : 'json'
			, success: function(data) {
				if(data.result==1){
					alert('Welcome to the Coinz!');
					location.href='/html/trading-order.html';
				}else if(data.result==-1){
					alert(data.val);
				}else{
					alert('try again');
				}
			  }
		});
	});
});