<?php

use Phalcon\Mvc\Controller;
use Phalcon\Validation;
use Phalcon\Validation\Validator\PresenceOf;
use Phalcon\Validation\Validator\StringLength\Min;
use Phalcon\Validation\Validator\Email;
use Phalcon\Validation\Validator\Confirmation;

class SignupController extends BaseController {

  public function tokenAction($token){
    $this -> clearEmailVerifications();
    if($this -> session -> has('user_id')) {
      $this -> response -> redirect('/profile/courses');
      return;
    }
    $emailVerification = EmailVerifications::findFirstByToken($token);
    if($emailVerification == null) {
      $this -> session -> set('expired', true);
      $this -> response -> redirect('/signup');
      return;
    }
    $user = new Users();
    $user -> email = $emailVerification -> email;
    $user -> password = $emailVerification -> password;
    $user -> save();
    $emailVerification -> delete();
    $this -> session -> set('complete', true);
    $this -> response -> redirect('/login');
    return;
  }

  public function resendAction() {
    $this -> clearEmailVerifications($request -> getPost('email'));
    if($this -> session -> has('user_id')) {
      $this -> response -> redirect('/profile/courses');
      return;
    }
    $request = $this -> request;
    if ($request -> isPost() && $request -> isAjax() && $this -> security -> checkToken()) {
      $result = $this -> getToken();
      $res = $this -> sendConfirmationLinkAction();
      array_push($result, $res);
      return json_encode($result);
    } else {
      $this -> response -> redirect('/signup');
      return;

      // return $this -> response -> redirect('/signup');
    }
  }

  private function sendConfirmationLinkAction() {
      $email = $this -> request -> getPost('email');
      $emailVerifications = EmailVerifications::findFirstByEmail($email);
      if($emailVerifications == null) {
        $result = ['result' => 'error', 'message' => 'Упс... Срок действия ссылки закончился, повторите попытку'];
        return $result;
      }
      $link = $this -> request -> getServer('SERVER_NAME').'/signup/token/'.$emailVerifications -> token;
      $subject = 'Подтверждение регистрации';
      $body = "<b><p>Здравствуйте!</p></b><p>Для завершения регистрации на сайте CHECKINGBOARD перейдите по <a target='_blank' href='$link'>ссылке</a>.</p>
        <p>Также Вы можете вставить ссылку в адресную строку Вашего браузера:</p><p><u><i>$link</i></u></p>
        <small>Это автоматическое письмо, отвечать на него не нужно. Если Вы получили письмо по ошибке, то просто удалите его.</small>";
      return $this -> sendEmail($subject, $body, $email);
  }

  private function clearEmailVerifications($email = null){
    if($email != null) {
      $emailVerifications = EmailVerifications::find("email = '$email' or expired < '".date('Y-m-d H:i:s')."'");
    } else {
      $emailVerifications = EmailVerifications::find("expired < '".date('Y-m-d H:i:s')."'");
    }
    foreach ($emailVerifications as $emailVerification) {
      $emailVerification->delete();
    }
  }

  private function validation() {
    $validation = new Validation();
    $validation -> add(
      'email', new PresenceOf([
          'message' => 'Введите электронную почту!',
      ])
    );
    $validation -> add(
      'password', new PresenceOf([
          'message' => 'Введите пароль!',
      ])
    );
    $validation -> add(
      'confirmPassword', new PresenceOf([
          'message' => 'Введите подтверждение пароля!',
      ])
    );
    $validation -> add(
      'email', new Email([
          'message' => 'Введите корректную электронную почту!',
      ])
    );
    $validation -> add(
      "password", new Min([
          "min"     => 8,
          "message" => "Длина пароля должна быть минимум 8 символов!",
          "included" => false
      ])
    );
    $validation -> add(
      "confirmPassword", new Min([
          "min"     => 8,
          "message" => "Длина подтверждения пароля должна быть минимум 8 символов!",
          "included" => false
      ])
    );
    $validation -> add(
      "password", new Confirmation([
          "message" => "Пароли не совпадают!",
          "with"    => "confirmPassword",
      ])
    );
    return $validation -> validate($_POST);
  }

  public function signupAction(){
    if($this -> session -> has('user_id')) {
      $this -> response -> redirect('/profile/courses');
      return;
    }
    $this -> view -> title = 'Регистрация';
    $this -> view -> description = "Регистрация";
    $request = $this -> request;
    if ($request -> isPost() && $request -> isAjax() && $this->security->checkToken()) {
      $result = $this -> getToken();
      $messages = $this -> validation();
      if (count($messages)) {
        foreach ($messages as $message) {
          array_push($result, $message);
        }
        return json_encode($result);
      }
      $this -> clearEmailVerifications($request -> getPost('email'));
      if(Users::findFirstByEmail($request -> getPost('email'))) {
        $message = ['result' => 'error', 'message' => 'Пользователь с таким адресом электронной почты уже зарегистрирован!'];
        array_push($result, $message);
        return json_encode($result);
      }
      $emailVerification = new EmailVerifications();
      $emailVerification -> email = $request -> getPost('email');
      $emailVerification -> token = hash('sha256',$request -> getPost('email').time(), false);
      $emailVerification -> expired = date('Y-m-d H:i:s', time() + 60*60);
      $emailVerification -> password = $this -> security -> hash($request -> getPost('password'));
      $emailVerification -> save();
      $res = $this -> sendConfirmationLinkAction();
      array_push($result, $res);
      return json_encode($result);
    }
  }

}
