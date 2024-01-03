<?php

use Phalcon\Mvc\Controller;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

class BaseController extends Controller {

  protected function checkUserCourse($course_id) {
    if (!$this -> session -> has('user_id')) {
      return false;
    }
    $user_id = $this -> session -> get('user_id');
    $courses_users = CoursesUsers::findFirst("user_id = $user_id and course_id = $course_id");
    if($courses_users == null) return false;
    else return true;
  }

  protected function checkUserPassword() {
    if (!$this -> session -> has('user_password') || !$this -> session -> has('user_id')) {
      $this -> session -> destroy();
      $this -> session -> start();
      return false;
    }
    $user_password = $this -> session -> get('user_password');
    $user = Users::findFirstById($this -> session -> get('user_id'));
    if($user_password == $user -> password) return true;
    else {
      $this -> session -> destroy();
      $this -> session -> start();
      return false;
    }
  }

  protected function getToken() {
    $result = array();
    $token = ['tokenKey' => $this->security->getTokenKey(), 'token' => $this->security->getToken()];
    array_push($result, $token);
    return $result;
  }

  protected function sendEmail($subject, $body, $email) {
    require_once(APP_PATH . '/libraries/PHPMailer/Exception.php');
    require_once(APP_PATH . '/libraries/PHPMailer/PHPMailer.php');
    require_once(APP_PATH . '/libraries/PHPMailer/SMTP.php');
    $mail = new PHPMailer(true);
    try {
      $mail->isSMTP();
      $mail->Host       = 'ssl://smtp.mail.ru';
      $mail->SMTPAuth   = true;
      $mail->Username   = 'checkingboard@bk.ru';
      $mail->Password   = 'webdevelopment';
      $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
      $mail->Port       = 465;
      $mail->setFrom('checkingboard@bk.ru', 'CHECKINGBOARD');
      $mail->addAddress($email);
      $mail->isHTML(true);
      $mail->CharSet = 'UTF-8';
      $mail->Subject = $subject;
      $mail->Body    = $body;
      $mail->send();
      $result = ['result' => 'success', 'email' => $email];
      return $result;
    } catch (Exception $e) {
      $result = ['result' => 'error', 'message' => "Message could not be sent. Mailer Error: {$mail->ErrorInfo}"];
      return $result;
    }
  }

}
