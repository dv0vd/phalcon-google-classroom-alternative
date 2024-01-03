<?php

use Phalcon\Mvc\Model;

class ResetPasswords extends Model
{
    public $id;
    public $user_id;
    public $token;
    public $expired;
}
