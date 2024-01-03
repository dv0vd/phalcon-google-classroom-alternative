<?php

use Phalcon\Mvc\Model;

class ChangeEmailVerifications extends Model
{
    public $id;
    public $user_id;
    public $email;
    public $token;
    public $expired;
}
