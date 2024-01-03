<?php

use Phalcon\Mvc\Model;

class Messages extends Model
{
  public $id;
  public $sender_id;
  public $text;
  public $date_time;
  public $task_id;
}
