<?php include'config.php'; include'smtp.php';
$descripcion = '
				<p><b>'.$_POST['tema'].'</b></p>
				<p>'.nl2br(trim($_POST['descripcion'])).'</p>
';

//Set who the message is to be sent to
$mail->AddBCC('fabiojara@gmail.com');
$mail->AddBCC('fabiojara@live.com');
$mail->AddBCC('sergioa.rivcif@gmail.com');
$mail->AddBCC('sergioa_rivcif@hotmail.es');
//Set the subject line
$mail->Subject = 'Nueva Sugerencia | FaSe';
//Read an HTML message body from an external file, convert referenced images to embedded,
//convert HTML into a basic plain-text alternative body
$mail->msgHTML(utf8_decode($descripcion));
//Replace the plain text body with one created manually
//$mail->AltBody = 'This is a plain-text message body';
//Attach an image file
//$mail->addAttachment('images/phpmailer_mini.png');
//send the message, check for errors

if($mail->send()){ $_SESSION['consultoriosExito']=16; } else { $_SESSION['consultoriosExito']=1; }
?>