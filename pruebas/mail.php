<?php include'smtp.php';

// the message
$msg = "First line of text\nSecond line of text";

// use wordwrap() if lines are longer than 70 characters
//$msg = wordwrap($msg,70);

// send email
		$mail->AddAddress("sergioa.rivcif@gmail.com");
		$mail->Subject = utf8_decode('Prueba correo | FaSe');
		$mail->msgHTML(utf8_decode($msg));

		if($mail->send()){ 
			echo "correo enviado";
		} else {
			echo "error al enviar correo";
		}
?>