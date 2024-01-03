<?php

use Phalcon\Mvc\Controller;
use Phalcon\Validation;
use Phalcon\Validation\Validator\Email;
use Phalcon\Validation\Validator\Digit;
use Phalcon\Validation\Validator\StringLength\Min;
use Phalcon\Validation\Validator\Confirmation;
use Phalcon\Validation\Validator\PresenceOf;

class ProfileController extends BaseController {

  public function joinCourseAction() {
    if(!$this -> session -> has('user_id')) {
      $this -> response -> redirect('/profile/courses');
      return;
    }
    $request = $this -> request;
    if ($request -> isPost() && $request -> isAjax() && $this -> security -> checkToken() && $this -> checkUserPassword()) {
      $result = $this -> getToken();
      $messages = $this -> validation(3);
      if (count($messages)) {
        foreach ($messages as $message) {
          array_push($result, $message);
        }
        return json_encode($result);
      }
      $course_id = $request -> getPost('code');
      $user_id = $this -> session -> get('user_id');
      $course = Courses::findFirstById($course_id);
      if ($course == null) {
        $message = ['result' => 'error', 'message' => 'Курса с таким кодом не существует!'];
        array_push($result, $message);
        return json_encode($result);
      }
      $courses_users = CoursesUsers::find("course_id = $course_id and user_id = $user_id");
      if(count($courses_users) > 0) {
        $message = ['result' => 'error', 'message' => 'Вы уже присоединены к данному курсу!'];
        array_push($result, $message);
        return json_encode($result);
      }
      if (!$this -> security -> checkHash($request -> getPost('passwordCourse'), $course -> password)) {
        $message = ['result' => 'error', 'message' => 'Неверный пароль!'];
        array_push($result, $message);
        return json_encode($result);
      }
      $role = Roles::findFirstByRole('user');
      $course_user = new CoursesUsers();
      $course_user -> role_id = $role -> id;
      $course_user -> nickname = $request -> getPost('nickname');;
      $course_user -> user_id = $this -> session -> get('user_id');
      $course_user -> course_id = $course_id;
      $course_user -> save();
      $role = Roles::findFirstByRole('user');
      $role_id = $role -> id;
      $courses_users = CoursesUsers::find("course_id = $course_id and role_id != $role_id");
      foreach($courses_users as $course_user) {
        $course_user_id = $course_user -> user_id;
        $nemMemberNotifications = NewMembersNotifications::findFirst("course_id = $course_id and user_id = $course_user_id");
        if($nemMemberNotifications == null) {
          $nemMemberNotifications = new NewMembersNotifications();
          $nemMemberNotifications -> course_id = $course_id;
          $nemMemberNotifications -> user_id = $course_user -> user_id;
          $nemMemberNotifications -> save();
        }
      }
      $message = ['result' => 'success', 'redirect' => "/profile/course/$course_id"];
      array_push($result, $message);
      return json_encode($result);
    } else {
      $this -> response -> redirect ('/profile/courses');
      return;
    }
  }

  public function createCourseAction() {
    if(!$this -> session -> has('user_id')) {
      $this -> response -> redirect('/profile/courses');
      return;
    }
    $request = $this -> request;
    if ($request -> isPost() && $request -> isAjax() && $this -> security -> checkToken() && $this -> checkUserPassword()) {
      $result = $this -> getToken();
      $messages = $this -> validation(2);
      if (count($messages)) {
        foreach ($messages as $message) {
          array_push($result, $message);
        }
        return json_encode($result);
      }
      $course = new Courses();
      $course -> name = $request -> getPost('name');
      $course -> password = $this -> security -> hash($request -> getPost('password'));
      $course -> save();
      $course_id = $course -> id;
      $role = Roles::findFirstByRole('owner');
      $course_user = new CoursesUsers();
      $course_user -> role_id = $role -> id;
      $course_user -> nickname = $request -> getPost('nickname');;
      $course_user -> user_id = $this -> session -> get('user_id');
      $course_user -> course_id = $course_id;
      $course_user -> save();
      $message = ['result' => 'success', 'redirect' => "/profile/course/$course_id"];
      array_push($result, $message);
      return json_encode($result);
    } else {
      $this -> response -> redirect ('/profile/courses');
      return;
    }
  }

  public function changePasswordAction() {
    // if(!$this -> session -> has('user_id')) {
    //   $this -> response -> redirect('/profile/courses');
    //   return;
    // }
    $request = $this -> request;
    if ($request -> isPost() && $request -> isAjax() && $this -> security -> checkToken() && $this -> checkUserPassword()) {
      $result = $this -> getToken();
      $messages = $this -> validation(1);
      if (count($messages)) {
        foreach ($messages as $message) {
          array_push($result, $message);
        }
        return json_encode($result);
      }
      $user = Users::findFirstById($this -> session -> get('user_id'));
      if (!$this -> security -> checkHash($request -> getPost('oldPassword'), $user -> password)) {
        $message = ['result' => 'error', 'message' => 'Старый пароль неверный!'];
        array_push($result, $message);
        return json_encode($result);
      } else {
        $user -> password = $this -> security -> hash($request -> getPost('newPassword'));
        $this -> session -> set('user_password',   $user -> password);
        $user -> update();
        $message = ['result' => 'success'];
        array_push($result, $message);
        return json_encode($result);
      }
    } else {
      $this -> response -> redirect ('/profile/courses');
      return;
    }
  }

  public function coursesAction() {
    // if(!$this -> session -> has('user_id') || !$this -> checkUserPassword()) {
    //   $this -> response -> redirect ('/login');
    //   return;
    // }
    if(!$this -> checkUserPassword()) {
      $this -> response -> redirect ('/login');
      return;
    }
    $this -> view -> title = 'Мои курсы';
    $this -> view -> description = "Мои курсы";
    $user_id = $this -> session -> get('user_id');
    $role = Roles::findFirstByRole('user');
    $role_id = $role -> id;
    $courses_attend = CoursesUsers::find(['conditions' => "user_id = $user_id and role_id = $role_id"]);
    $this -> view -> courses_attend = $courses_attend;
    $role = Roles::findFirstByRole('admin');
    $role_admin_id = $role -> id;
    $role = Roles::findFirstByRole('owner');
    $role_owner_id = $role -> id;
    $courses_teach = CoursesUsers::find(['conditions' => "user_id = $user_id and (role_id = $role_admin_id or role_id = $role_owner_id)"]);
    $this -> view -> courses_teach = $courses_teach;
    // $this -> view -> newMembersNotifications = NewMembersNotifications::find();
    // $this -> view -> newMaterialsNotifications = NewMaterialsNotifications::find();
  }

  public function settingsAction() {
    // if(!$this -> session -> has('user_id') || !$this -> checkUserPassword()) {
    //   $this -> response -> redirect ('/login');
    //   return;
    // }
    if(!$this -> checkUserPassword()) {
      $this -> response -> redirect ('/login');
      return;
    }
    $this -> view -> title = 'Настройки профиля';
    $this -> view -> description = "Настройки профиля";
  }

  public function logoutAction() {
    if(!$this -> session -> has('user_id')) {
      $this -> response -> redirect('/profile/courses');
      return;
    }
    $request = $this -> request;
    if ($request -> isPost() && $request -> isAjax() && $this -> security -> checkToken()) {
      $this -> session -> destroy();
      $this -> session -> start();
      $message = ['result' => 'success', 'redirect' => '/login'];
      $result = array();
      array_push($result, $message);
      return json_encode($result);
    } else {
      $this -> response -> redirect ('/profile/courses');
      return;
    }
  }

  public function removeAction() {
    if(!$this -> session -> has('user_id')) {
      $this -> response -> redirect('/profile/courses');
      return;
    }
    $request = $this -> request;
    if ($request -> isPost() && $request -> isAjax() && $this -> security -> checkToken() && $this -> checkUserPassword()) {
      $user_id = $this -> session -> get('user_id');
      $role = Roles::findFirstByRole('owner');
      $role_id = $role -> id;
      $courses_users = CoursesUsers::find("user_id = $user_id and role_id = $role_id");
      foreach($courses_users as $course_user) {
        $course_user -> delete();
      }
      $user = Users::findFirstById($user_id);
      $user -> delete();
      $this -> session -> destroy();
      $this -> session -> start();
      $message = ['result' => 'success', 'redirect' => '/'];
      $result = array();
      array_push($result, $message);
      return json_encode($result);
    } else {
      $this -> response -> redirect ('/profile/courses');
      return;
    }
  }

  public function tokenAction($token){
    // if(!$this -> session -> has('user_id') || !$this -> checkUserPassword()) {
    //   $this -> response -> redirect('/profile/courses');
    //   return;
    $this -> clearChangeEmailVerifications($this -> session -> get('user_id'));

    // }
    if(!$this -> checkUserPassword()) {
      $this -> response -> redirect('/profile/courses');
      return;
    }
    $result = $this -> getToken();
    $changeEmailVerifications = ChangeEmailVerifications::findFirstByToken($token);
    if($changeEmailVerifications == null) {
      $this -> session -> set('expired', true);
      $this -> response -> redirect('/profile/settings');
      return;
    }
    $user = Users::findFirstById($this -> session -> get('user_id'));
    $user -> email = $changeEmailVerifications -> email;
    $user -> update();
    $changeEmailVerifications -> delete();
    $this -> response -> redirect('/profile/settings');
    return;
  }

  private function clearChangeEmailVerifications($user_id = 0){
    if($user_id != 0) {
      $changeEmailVerifications = ChangeEmailVerifications::find("user_id = '$user_id' or expired < '".date('Y-m-d H:i:s')."'");
    } else {
      $changeEmailVerifications = ChangeEmailVerifications::find("expired < '".date('Y-m-d H:i:s')."'");
    }
    foreach ($changeEmailVerifications as $changeEmailVerification) {
      $changeEmailVerification->delete();
    }
  }

  private function sendConfirmationLinkAction() {
    $email = $this -> request -> getPost('email');
    $changeEmailVerification = ChangeEmailVerifications::findFirstByUserId($this -> session -> get('user_id'));
    if($changeEmailVerification == null) {
      $result = ['result' => 'error', 'message' => 'Упс... Срок действия ссылки закончился, повторите попытку'];
      return $result;
    }
    $link = $this -> request -> getServer('SERVER_NAME').'/profile/changeEmail/token/'.$changeEmailVerification -> token;
    $subject = 'Изменение электронной почты';
    $body = "<b><p>Здравствуйте!</p></b><p>Для завершения регистрации на сайте CHECKINGBOARD перейдите по <a target='_blank' href='$link'>ссылке</a>.</p>
      <p>Также Вы можете вставить ссылку в адресную строку Вашего браузера:</p><p><u><i>$link</i></u></p>
      <small>Это автоматическое письмо, отвечать на него не нужно. Если Вы получили письмо по ошибке, то просто удалите его.</small>";
    return $this -> sendEmail($subject, $body, $email);
  }

  public function changeEmailAction() {
    if(!$this -> session -> has('user_id')) {
      $this -> response -> redirect('/profile/courses');
      return;
    }
    $request = $this -> request;
    if ($request -> isPost() && $request -> isAjax() && $this -> security -> checkToken() && $this -> checkUserPassword()) {
      $result = $this -> getToken();
      $messages = $this -> validation(0);
      if (count($messages)) {
        foreach ($messages as $message) {
          array_push($result, $message);
        }
        return json_encode($result);
      }
      $user = Users::findFirstByEmail($request -> getPost('email'));
      if($user != null) {
        $message = ['result' => 'error', 'message' => "Пользователь с такой электронной почтой уже зарегистрирован!"];
        array_push($result, $message);
        return json_encode($result);
      }
      $user = Users::findFirstById($this -> session -> get('user_id'));
      $this -> clearChangeEmailVerifications($user -> id);
      $chengeEmailVerification = new ChangeEmailVerifications();
      $chengeEmailVerification -> user_id = $user -> id;
      $chengeEmailVerification -> email = $request -> getPost('email');
      $chengeEmailVerification -> token = hash('sha256',$request -> getPost('email').time(), false);
      $chengeEmailVerification -> expired = date('Y-m-d H:i:s', time() + 60*60);
      $chengeEmailVerification -> save();
      $res = $this -> sendConfirmationLinkAction();
      array_push($result, $res);
      return json_encode($result);
    } else {
      $this -> response -> redirect ('/profile/settings');
      return;
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
          'email', new Email([
              'message' => 'Введите корректную электронную почту!',
          ])
        );
        break;
      case 1:
        $validation -> add(
          'oldPassword', new PresenceOf([
              'message' => 'Введите старый пароль!',
          ])
        );
        $validation -> add(
          'newPassword', new PresenceOf([
              'message' => 'Введите новый пароль!',
          ])
        );
        $validation -> add(
          'confirmPassword', new PresenceOf([
              'message' => 'Введите подтверждение пароля!',
          ])
        );
        $validation -> add(
          "oldPassword", new Min([
              "min"     => 8,
              "message" => "Длина старого пароля должна быть минимум 8 символов!",
              "included" => false
          ])
        );
        $validation -> add(
          "newPassword", new Min([
              "min"     => 8,
              "message" => "Длина нового пароля должна быть минимум 8 символов!",
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
          "newPassword", new Confirmation([
              "message" => "Подтверждение пароля не совпадает с новым паролем!",
              "with"    => "confirmPassword",
          ])
        );
        break;
      case 2:
        $validation -> add(
          'name', new PresenceOf([
              'message' => 'Введите название курса!',
          ])
        );
        $validation -> add(
          'nickname', new PresenceOf([
              'message' => 'Введите Ваше отображаемое имя!',
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
      case 3:
        $validation -> add(
          'code', new PresenceOf([
              'message' => 'Введите код курса!',
          ])
        );
        $validation -> add(
          'nickname', new PresenceOf([
              'message' => 'Введите Ваше отображаемое имя!',
          ])
        );
        $validation -> add(
          'passwordCourse', new PresenceOf([
              'message' => 'Введите пароль доступа к курсу!',
          ])
        );
        $validation -> add(
          "passwordCourse", new Min([
              "min"     => 8,
              "message" => "Длина пароля должна быть минимум 8 символов!",
              "included" => false
          ])
        );
        $validation -> add(
          "code", new Digit([
              "message" => "Код курса - положительное число!",
          ])
        );
        break;
    }
    return $validation -> validate($_POST);
  }

}
