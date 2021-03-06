<?php

// header('Content-type:application/json;charset=utf-8');

error_reporting(~E_NOTICE);
ini_set("log_errors", 1);
ini_set("error_log", "/logs/error.log");

require 'PHPMailerAutoload.php';

$name 	 = $_POST['name'] ?: '';
$email 	 = $_POST['email'];
$phone   = $_POST['phone'];
$subj 	 = $_POST['subj' ?: ''];
$message = $_POST['message'] ?: '';


//we don't need mail with no contacts
if (empty($email) && empty($phone)) {
  echo json_encode([
    "message" => "Нет контактных данных",
    "status" => "error",
  ], JSON_UNESCAPED_UNICODE);

  return;
}


$mail = new PHPMailer;

$mail->CharSet = "UTF-8";

//$mail->SMTPDebug = 3;                               // Enable verbose debug output

//$mail->isSMTP();                                      // Set mailer to use SMTP
$mail->Host = 'smtp1.example.com;smtp2.example.com';  // Specify main and backup SMTP servers
$mail->SMTPAuth = false;                               // Enable SMTP authentication
$mail->Username = 'user@example.com';                 // SMTP username
$mail->Password = 'secret';                           // SMTP password
$mail->SMTPSecure = 'tls';                            // Enable TLS encryption, `ssl` also accepted
$mail->Port = 587;                                    // TCP port to connect to

$mail->setFrom('noreply@pmurussia.com', 'Project mu Russia');
$mail->addAddress('april504@yandex.ru');
$mail->addAddress('avtomag.1000@mail.ru');
$mail->addAddress('sukhobokov1000size@gmail.com');
$mail->addAddress('info@pmurussia.com'); // Add a recipient


// $mail->addAddress('example@example.com');
// $mail->addReplyTo($email, $name);

$mail->isHTML(true);                                  // Set email format to HTML

ob_start();
require('./template.php');
$template = ob_get_clean();

$mail->Subject = 'Сообщение с pmurussia.com: '.$subj;
$mail->Body    = $template;

$mail->AltBody = "Новое сообщение!\n
                  От: $name\n
                  Email: $email\n
                  Телефон: $phone\n
                  Тема: $subj\n
                  Сообщение:\n$message";

$res = [];

if(!$mail->send()) {
    echo json_encode([
      "message" => 'Произошла ошибка. Попробуйте позже, а лучше позвоните нам<br><br>Mailer Error: ' . $mail->ErrorInfo,
      "status" => "error",
    ], JSON_UNESCAPED_UNICODE);
} else {
    echo json_encode([
      "message" => "Спасибо, мы скоро свяжемся с вами",
      "status" => "ok",
    ], JSON_UNESCAPED_UNICODE);
}
