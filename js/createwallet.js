$(function() {
	$("#btn_wallet").click(
			function() {
				var pw = $("#joinPw").val();
				var repw = $("#joinPwRe").val();
		
				if (!pw) {
			
					alert('지갑 패스워드를 입력해주세요');
					$("#joinPw").focus();
					return;
				}else if(!repw){
					
					alert('지갑 패스워드를 입력해주세요');
					$("#joinPwRe").focus();
					return;
				}
				if (!confirm('지갑주소를 생성하시겠습니까?')) {
					return false;
				}
			
				var params = "pw=" + pw;
				//alert(params);
				$.ajax({
					type : 'post',
					url : 'createwallet_proc.php',
					data : params,
					dataType : 'html',
					success : function(data) {
						alert(data);
						// $('#section').css({"animation":
						// "center_right","animation-duration": "0.5s",
						// "animation-iteration-count": "1", "animation-fill-mode":
						// "both"});
						//alert(data);
						location.href = 'mypage.html';
					}
				});
			}
	);
});

function wallet_fail() {
	$('#section').css({
		"animation" : "center_left",
		"animation-duration" : "0.5s",
		"animation-iteration-count" : "1",
		"animation-fill-mode" : "both"
	});
}
