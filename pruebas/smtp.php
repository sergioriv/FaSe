<?php

use PHPMailer\PHPMailer\PHPMailer;
require '../vendor/autoload.php';

	$mail = new PHPMailer;
	$mail->isSMTP();
	$mail->SMTPDebug = 0;
	$mail->Host = 'shared10.hostgator.co';
	$mail->Port = 25;
	$mail->SMTPAuth = true;
	$mail->Username = "no-reply@mantiztechnology.com";
	$mail->Password = "MantizTech2018FaSe";
	$mail->setFrom('no-reply@mantiztechnology.com', 'FaSe');
	$mail->addReplyTo('info@mantiztechnology.com');
?>