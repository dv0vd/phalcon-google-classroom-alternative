<?php

use Phalcon\Mvc\Controller;
use Phalcon\Validation;
use Phalcon\Validation\Validator\PresenceOf;
use Phalcon\Validation\Validator\StringLength\Min;
use Phalcon\Validation\Validator\Email;
use Phalcon\Validation\Validator\Confirmation;

class LoginController extends BaseController {

  public function newPasswordAction() {
    if(!$this -> session -> has('password_token')) {
      $this -> response -> redirect('/login');
    }
    $this -> view -> title = 'Новый пароль';
    $this -> view -> description = "Новый пароль";
    $request = $this -> request;
    if($request -> isAjax() && $request -> isPost() && $this -> security -> checkToken()) {
      $resetPassword = ResetPasswords::findFirstByToken($this -> session -> get('password_token'));
      $this -> session -> remove('password_token');
      if($resetPassword == null) {
        $this -> session -> set('expired', true);
        $result = array();
        $message = ['result' => 'error', 'redirect' => '/login/reset'];
        array_push($result, $message);
        return json_encode($result);
      }
      $user = Users::findFirstById($resetPassword -> user_id);
      $user -> password = $this -> security -> hash($request -> getPost('password'));
      $user -> update();
      $this -> session -> set('reset_password', 'success');
      $resetPassword -> delete();
      $result = array();
      $message = ['result' => 'success', 'redirect' => '/login'];
      array_push($result, $message);
      return json_encode($result);
    }
  }

  public function resendAction() {
    $this -> clearResetPasswords();
    if($this -> session -> has('user_id')) {
      $this -> response -> redirect('/profile/courses');
      return;
    }
    $request = $this -> request;
    if ($request -> isPost() && $request -> isAjax() && $this -> security -> checkToken()) {
      $result = $this -> getToken();
      $user = Users::findFirstByEmail($request -> getPost('email'));
      $res = $this -> sendConfirmationLinkAction($user);
      array_push($result, $res);
      return json_encode($result);
    } else {
      $this -> response -> redirect('/login');
      return;
    }
  }

  public function tokenAction($token){
    $this -> clearResetPasswords();
    if($this -> session -> has('user_id')) {
      $this -> response -> redirect('/profile/courses');
    }
    $resetPassword = ResetPasswords::findFirstByToken($token);
    if($resetPassword == null) {
      $this -> session -> set('expired', true);
      $this -> response -> redirect('/login/reset');
      return;
    }
    $this -> session -> set('password_token', $token );
    $this -> response -> redirect('/login/newPassword');
    return;
  }

  private function sendConfirmationLinkAction($user) {
      $user_id = $user -> id;
      $email = $user -> email;
      $resetPassword = ResetPasswords::findFirstByUserId($user_id);
      if($resetPassword == null) {
        $result = ['result' => 'error', 'message' => 'Упс... Срок действия ссылки закончился, повторите попытку'];
        return $result;
      }
      $link = $this -> request -> getServer('SERVER_NAME').'/login/reset/'.$resetPassword -> token;
      $subject = 'Сброс пароля';
      $body = "<b><p>Здравствуйте!</p></b><p>Для сброса пароля на сайте CHECKINGBOARD перейдите по <a target='_blank' href='$link'>ссылке</a>.</p>
        <p>Также Вы можете вставить ссылку в адресную строку Вашего браузера:</p><p><u><i>$link</i></u></p>
        <small>Это автоматическое письмо, отвечать на него не нужно. Если Вы получили письмо по ошибке, то просто удалите его.</small>";
      return $this -> sendEmail($subject, $body, $email);
  }

  private function clearResetPasswords($user_id = 0){
    if($user_id != 0) {
      $resetPasswords = ResetPasswords::find("user_id = '$user_id' or expired < '".date('Y-m-d H:i:s')."'");
    } else {
      $resetPasswords = ResetPasswords::find("expired < '".date('Y-m-d H:i:s')."'");
    }
    foreach ($resetPasswords as $resetPassword) {
      $resetPassword->delete();
    }
  }

  public function resetAction() {
    if($this -> session -> has('user_id')) {
      $this -> response -> redirect('/profile/courses');
    }
    $this -> view -> title = 'Сброс пароля';
    $this -> view -> description = "Сброс пароля";
    $request = $this -> request;
    if($request -> isAjax() && $request -> isPost() && $this -> security -> checkToken()) {
      $result = $this -> getToken();
      $messages = $this -> validation(1);
      if (count($messages)) {
        foreach ($messages as $message) {
          array_push($result, $message);
        }
        return json_encode($result);
      }
      $user = Users::findFirstByEmail($request -> getPost('email'));
      if($user == null) {
        $message = ['result' => 'error', 'message' => "Пользователь с такой электронной почтой не найден!"];
        array_push($result, $message);
        return json_encode($result);
      }
      $this -> clearResetPasswords($user -> id);
      $resetPassword = new ResetPasswords();
      $resetPassword -> user_id = $user -> id;
      $resetPassword -> token = hash('sha256',$user -> email.time(), false);
      $resetPassword -> expired = date('Y-m-d H:i:s', time() + 60*60);
      $resetPassword -> save();
      $res = $this -> sendConfirmationLinkAction($user);
      array_push($result, $res);
      return json_encode($result);
    }
  }

  private function validation($type) {
    $validation = new Validation();
    switch($type) {
      case 0:
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
        break;
      case 1:
        $validation -> add(
          'email', new PresenceOf([
              'message' => 'Введите электронную почту!',
          ])
        );
        $validation -> add(
          'email', new Email([
              'message' => 'Введите корректную электронную почту!',
          ])
        );
        break;
      case 2:
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
        break;
    }
    return $validation -> validate($_POST);
  }

  public function loginAction(){
    if($this -> session -> has('user_id')) {
      $this -> response -> redirect('/profile/courses');
      return;
    }
    $this -> view -> title = 'Вход';
    $this -> view -> description = "Вход";
    $request = $this -> request;
    if($request -> isAjax() && $request -> isPost() && $this -> security -> checkToken()) {
      $result = $this -> getToken();
      $email = $request -> getPost('email');
      $messages = $this -> validation(0);
      if (count($messages)) {
        foreach ($messages as $message) {
          array_push($result, $message);
        }
        return json_encode($result);
      }
      $user = Users::findFirstByEmail($email);
      if($user == null) {
        $message = ['result' => 'error', 'message' => "Пользователь с такой электронной почтой не найден!"];
        array_push($result, $message);
        return json_encode($result);
      }
      $password = $request -> getPost('password');
      if (!$this -> security -> checkHash($password, $user -> password)) {
        $message = ['result' => 'error', 'message' => "Неверный пароль!"];
        array_push($result, $message);
        return json_encode($result);
      } else {
        $this -> session -> set('user_id', $user -> id);
        $this -> session -> set('user_password', $user -> password);
        $message = ['result' => 'success', 'redirect' => '/profile/courses', 'user_id' => $user -> id];
        array_push($result, $message);
        return json_encode($result);
      }
    }
  }

}
