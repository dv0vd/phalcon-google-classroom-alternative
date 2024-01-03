<?php

use Phalcon\Mvc\Controller;
use Phalcon\Validation;
use Phalcon\Validation\Validator\PresenceOf;
use Phalcon\Validation\Validator\Email;

class IndexController extends BaseController {

  public function indexAction() {
    $this -> view -> title = 'Главная';
    $this -> view -> description = "Главная";
    $this -> view -> header_hidden = true;
  }

  public function supportAction() {
    $this -> view -> title = 'Поддержка';
    $this -> view -> description = "Поддержка";
  }

  private function validation() {
    $validation = new Validation();
    $validation -> add(
        'email', new Email([
            'message' => 'Введите корректную электронную почту!',
        ])
    );
    $validation -> add(
        'email', new PresenceOf([
            'message' => 'Введите почту!',
        ])
    );
    $validation -> add(
        'name', new PresenceOf([
            'message' => 'Введите имя!',
        ])
    );
    $validation -> add(
        'question', new PresenceOf([
            'message' => 'Введите вопрос!',
        ])
    );
    return $validation -> validate($_POST);
  }

  public function supportSendAction() {
    $request = $this -> request;
    if ($request -> isPost() && $request -> isAjax() && $this -> security -> checkToken()) {
      $result = array();
      $token = ['tokenKey' => $this->security->getTokenKey(), 'token' => $this->security->getToken()];
      array_push($result, $token);
      $messages = $this -> validation();
      if (count($messages)) {
          foreach ($messages as $message) {
            array_push($result, $message);
          }
          return json_encode($result);
      }
      $name = $request->getPost('name');
      $email = $request->getPost('email');
      $question = $request->getPost('question');
      $body = "<p>Имя: $name</p><p>Почта: $email</p><center><p><b>Вопрос</b></p></center><p>$question</p>";
      array_push($result, $this -> sendEmail('Поддержка', $body, 'checkingboard@bk.ru'));
      return json_encode($result);
    } else {
      $this -> response -> redirect('/support');
      return;
    }
  }
}
