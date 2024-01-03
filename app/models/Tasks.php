<?php

use Phalcon\Mvc\Model;

class Tasks extends Model
{
    public $id;
    public $title;
    public $date_time;
    public $description;
    public $course_id;
    public $score;
}
