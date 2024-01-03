<?php

use Phalcon\Mvc\Model;

class CoursesUsers extends Model
{
    public $id;
    public $nickname;
    public $role_id;
    public $user_id;
    public $course_id;
}
