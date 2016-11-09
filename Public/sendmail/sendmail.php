<?php
require("class.phpmailer.php");
function sendmail($to,$subject,$body,$username){
	
	$mail  = new PHPMailer();
	$mail->IsSMTP();                 // 启用SMTP
	$mail->Host = "smtp.qq.com";        //SMTP服务器smtp.qq.com
	$mail->SMTPAuth = true;                //开启SMTP认证
	$mail->Username = "smtptui@eqiseo.com";      // SMTP用户名 1152299037@qq.com
	$mail->Password = "Smtptui236";         // SMTP密码 ugee87537115

	$mail->From = "smtptui@eqiseo.com";         //发件人地址 mailservice@linghit.com
	$mail->FromName = "乐游文化传播";              //发件人
	$mail->AddAddress($to, $username);        //添加收件人
	$mail->AddReplyTo($to, $username);
	$mail->CharSet = "UTF-8"; // 这里指定字符集！
	$mail->Encoding = "base64"; 

	$mail->WordWrap = 50;                    //设置每行字符长度
	
	$mail->IsHTML(true);                 // 是否HTML格式邮件

	$mail->Subject = $subject;      //邮件主题
	$mail->Body    = $body;        //邮件内容
	$mail->AltBody ="text/html"; 
	$mail->IsSMTP();
	return $mail->Send();
}

?>
