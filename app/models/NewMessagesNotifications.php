<?php

use Phalcon\Mvc\Model;

class NewMessagesNotifications extends Model
{
    public $id;
    public $task_id;
    public $sender_id;
    public $receptionist_id;
}
