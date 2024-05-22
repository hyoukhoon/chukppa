<?php
//Import PHPMailer classes into the global namespace
//These must be at the top of your script, not inside a function
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require $_SERVER["DOCUMENT_ROOT"].'/phpmailer/src/Exception.php';
require $_SERVER["DOCUMENT_ROOT"].'/phpmailer/src/PHPMailer.php';
require $_SERVER["DOCUMENT_ROOT"].'/phpmailer/src/SMTP.php';

//Create an instance; passing `true` enables exceptions
$mail = new PHPMailer(true);

try {
    //Server settings
//    $mail->SMTPDebug = SMTP::DEBUG_SERVER;                      //Enable verbose debug output
	$mail->Charset = 'UTF-8'; 
    $mail->isSMTP();                                            //Send using SMTP
    $mail->Host       = 'smtp-relay.sendinblue.com';                     //Set the SMTP server to send through smtp.naver.com
    $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
    $mail->Username   = 'hyoukhoon@gmail.com';                     //SMTP username
    $mail->Password   = 'wngdstXE0R8mybGN';                               //SMTP password
//    $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;            //Enable implicit TLS encryption
    $mail->Port       = 587;                                    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS` 465

    //Recipients
    $mail->setFrom('hyoukhoon@gmail.com');
    $mail->addAddress('handofgod@naver.com');     //Add a recipient
//    $mail->addAddress('ellen@example.com');               //Name is optional
    $mail->addReplyTo('hyoukhoon@gmail.com');
//    $mail->addCC('cc@example.com');
//    $mail->addBCC('bcc@example.com');

    //Attachments
//    $mail->addAttachment('');         //Add attachments
//    $mail->addAttachment('/tmp/image.jpg', 'new.jpg');    //Optional name

    //Content
    $mail->isHTML(true);                                  //Set email format to HTML
	$subject='문의하신 아이디 보내드립다.';
    $mail->Subject = "=?UTF-8?B?".base64_encode($subject)."?="."\r\n"; 
    $mail->Body    = '안녕하세요. 문의하신 아이디입니다.<br> 아이디는 partenon@daum.net입니다.';
    $mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

    $mail->send();
    echo 'Message has been sent';
} catch (Exception $e) {
    echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
}