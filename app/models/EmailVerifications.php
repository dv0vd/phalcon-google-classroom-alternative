<?php

use Phalcon\Mvc\Model;

class EmailVerifications extends Model
{
    public $id;
    public $email;
    public $password;
    public $token;
    public $expired;
}
