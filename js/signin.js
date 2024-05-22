//signin.html
// 비번체크
var g_btn_mailsendchk;
var g_btn_verifychk
var g_pw_chk;
var g_chk_email = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
$(function() {
	$("#alert-success").hide();
	$("#alert-danger").hide();
	$("input").keyup(function() {
		var pwd1 = $("#joinPw").val();
		var pwd2 = $("#joinPwRe").val();

		if (pwd1 != "" || pwd2 != "") {
			if (pwd1 == pwd2) {
				$("#alert-success").show();
				$("#alert-danger").hide();
				$("#btn_submit").removeAttr("disabled");
				g_pw_chk = "0";
			} else {
				$("#alert-success").hide();
				$("#alert-danger").show();
				$("#btn_submit").attr("disabled", "disabled");
				g_pw_chk = "1";
			}
		}
	});
});

$(function() {
	$("#btn_submit").click(
			function() {
				var mtype = $('input:radio[name="memberType"]:checked').val();
				var username = $("#username").val();
				var email = $("#joinEmail").val();
				var confirmNum = $("#confirmNum").val();
				var cell_phone = $("#cellphone").val();
				var passwd = $("#joinPw").val();
				var passwd2 = $("#joinPwRe").val();
				var referee = $("#refer").val();
				
				if(!mtype){
					alert('Select your Member type');
					return;
			    } else if (!username) {
					alert('Enter your Name');
					$("#username").focus();
					return;
				} else if (!email) {
					alert('Enter your Email');
					$("#joinEmail").focus();
					return;
				} else if(!email_check(email)){
					alert("잘못된 형식의 이메일 주소입니다.");
					$("#email").focus();
					return;
				} else if (!confirmNum) {
					alert('Enter your confirm num');
					$("#confirmNum").focus();
					return;
				} else if (!cell_phone) {
					alert('Enter your cell phone');
					$("#cellphone").focus();
					return;
				}else if (!passwd) {
					alert('Enter your Password');
					$("#joinPw").focus();
					return;
				} else if (!passwd2) {
					alert('Enter your Repeat Password');
					$("#joinPwRe").focus();
					return;
				} else if (passwd.length < 8) {
					alert('Password must 8 digits or more');
					$("#joinPw").val("");
					$("#joinPwRe").val("");
					$("#joinPw").focus();
					return;
				} else if (passwd != passwd2) {
					alert('Check your Password');
					$("#joinPwRe").focus();
					return;
				}
				if (g_pw_chk == "1") {
					alert("비밀번호가 일치하지 않습니다.");
				}
				if(g_btn_mailsendchk != "Y"){
					alert("회원가입 인증을 위해 메일전송을 클릭해주세요");
					return;
				}
				
				var uid = email.split('@');
				var params = "mtype=" + mtype + "&email=" + email + "&passwd=" + passwd	+ "&referee=" + referee + "&uname=" + username + "&uid=" + uid[0] + "&cellphone=" + cell_phone;
				
				if ($("input:checkbox[id='chkAgree']").is(":checked") != true) {
					alert("Check Terms of Use & Privacy Policy");
					return;
				}

				/*
				 * if ($("input:checkbox[id='vcheck2']").is(":checked") != true) {
				 * alert("Check The Agree"); return; }
				 */
				alert(params);
				$.ajax({
					type : 'post',
					url : 'signin_proc.php',
					data : params,
					dataType : 'json',
					success : function(data) {
						if (data.result == 1) {
							alert('Registration done');
							location.href = 'createwallet.html';
						} else if (data.result == -1) {
							alert(data.val);
						} else {
							alert('try again');
						}

					}
				});

			});
});


$(function() {
	$("#btn_mailsend").click(
			function() {
				var tmp_mail = $("#joinEmail").val();
				var params = "tmp_email="+tmp_mail;
				var g_btn_mailsendchk = "Y";
				email_check(tmp_mail);
				
				if (!tmp_mail) {
					alert('Enter your mail');
					$("#joinEmail").focus();
					return;
				}else if(!email_check(tmp_mail)){
					alert("잘못된 형식의 이메일 주소입니다.");
					$("#joinEmail").focus();
					return;
				}
				
				alert("입력하신 이메일주소로 인증메일을 전송합니다. 확인버튼을 눌러주세요.");
				$.ajax({
					type : 'post',
					url : 'sendmail_proc.php',
					data : params,
					dataType : 'json',
					success : function(data) {
						if (data.result == 1) {
							alert('Send email to you.');
							//location.href = '#';
						} else if (data.result == -1) {
							alert(data.val);
						} else {
							alert('try again');
						}

					}
				});

			});
});

function email_check(email) {
    var regex=/^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
    return (email != '' && email != 'undefined' && regex.test(email));
}


