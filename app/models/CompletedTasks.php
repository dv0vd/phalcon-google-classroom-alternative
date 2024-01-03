<?php

use Phalcon\Mvc\Model;

class CompletedTasks extends Model
{
    public $id;
    public $user_id;
    public $task_id;
    public $score;
}
