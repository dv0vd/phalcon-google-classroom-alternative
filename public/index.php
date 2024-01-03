<?php

use Phalcon\Di\FactoryDefault;
use Phalcon\Loader;
use Phalcon\Mvc\View;
use Phalcon\Mvc\Application;
use Phalcon\Url;
use Phalcon\Mvc\Router;
use Phalcon\Db\Adapter\Pdo\Mysql;
use Phalcon\Session\Manager;
use Phalcon\Session\Adapter\Stream;

// Define some absolute path constants to aid in locating resources
define('BASE_PATH', dirname(__DIR__));
define('APP_PATH', BASE_PATH . '/app');

// Register an autoloader
$loader = new Loader();

$loader->registerDirs(
    [
        APP_PATH . '/controllers/',
        APP_PATH . '/models/',
    ]
);

$loader->register();

$container = new FactoryDefault();

$container->set(
    'view',
    function () {
        $view = new View();
        $view->setViewsDir(APP_PATH . '/views/');
        return $view;
    }
);

$container->set(
    'url',
    function () {
        $url = new Url();
        $url->setBaseUri('/');
        return $url;
    }
);

$container->set(
    'db',
    function () {
        return new Mysql(
            [
                'host'     => 'localhost',
                'username' => 'root',
                'password' => '',
                'dbname'   => 'checkingboard',
            ]
        );
    }
);

$router = new Router(false);

$router->notFound(
   [
      'controller' => 'error',
      'action'     => 'notFound',
   ]
);

$router->add(
    '/',
    [
        'controller' => 'index',
        'action'     => 'index',
    ]
);

$router->add(
    '/support',
    [
        'controller' => 'index',
        'action'     => 'support',
    ]
);

$router->add(
    '/support/send',
    [
        'controller' => 'index',
        'action'     => 'supportSend',
    ]
);

$router->add(
    '/signup',
    [
        'controller' => 'signup',
        'action'     => 'signup',
    ]
);

$router->add(
    '/signup/resend',
    [
        'controller' => 'signup',
        'action'     => 'resend'
    ]
);

$router->add(
    '/signup/token/{token}',
    [
        'controller' => 'signup',
        'action'     => 'token'
    ]
);

$router->add(
    '/login',
    [
        'controller' => 'login',
        'action'     => 'login'
    ]
);

$router->add(
    '/login/reset',
    [
        'controller' => 'login',
        'action'     => 'reset'
    ]
);

$router->add(
    '/login/reset/{token}',
    [
        'controller' => 'login',
        'action'     => 'token'
    ]
);

$router->add(
    '/login/resend',
    [
        'controller' => 'login',
        'action'     => 'resend'
    ]
);

$router->add(
    '/login/newPassword',
    [
        'controller' => 'login',
        'action'     => 'newPassword'
    ]
);

$router->add(
    '/profile/courses',
    [
        'controller' => 'profile',
        'action'     => 'courses'
    ]
);

$router->add(
    '/profile/courses/create',
    [
        'controller' => 'profile',
        'action'     => 'createCourse'
    ]
);

$router->add(
    '/profile/courses/join',
    [
        'controller' => 'profile',
        'action'     => 'joinCourse'
    ]
);

$router->add(
    '/profile/course/{course_id}',
    [
        'controller' => 'course',
        'action'     => 'course'
    ]
);

$router->add(
    '/profile/course/changeNickname',
    [
        'controller' => 'course',
        'action'     => 'changeNickname'
    ]
);

$router->add(
    '/profile/course/leave',
    [
        'controller' => 'course',
        'action'     => 'leaveCourse'
    ]
);

$router->add(
    '/profile/course/changeName',
    [
        'controller' => 'course',
        'action'     => 'changeCourseName'
    ]
);

$router->add(
    '/profile/course/remove',
    [
        'controller' => 'course',
        'action'     => 'remove'
    ]
);

$router->add(
    '/profile/course/changePassword',
    [
        'controller' => 'course',
        'action'     => 'changePassword'
    ]
);

$router->add(
    '/profile/course/getMembers',
    [
        'controller' => 'course',
        'action'     => 'getMembers'
    ]
);

$router->add(
    '/profile/course/getMaterials',
    [
        'controller' => 'course',
        'action'     => 'getMaterials'
    ]
);

$router->add(
    '/profile/course/getTasks',
    [
        'controller' => 'course',
        'action'     => 'getTasks'
    ]
);

$router->add(
    '/profile/course/getMessages',
    [
        'controller' => 'course',
        'action'     => 'getMessages'
    ]
);

$router->add(
    '/profile/course/getMaterialData',
    [
        'controller' => 'course',
        'action'     => 'getMaterialData'
    ]
);

$router->add(
    '/profile/course/getTaskData',
    [
        'controller' => 'course',
        'action'     => 'getTaskData'
    ]
);

$router->add(
    '/profile/course/clearNewMembersNotifications',
    [
        'controller' => 'course',
        'action'     => 'clearNewMembersNotifications'
    ]
);

$router->add(
    '/profile/course/clearNewMessagesNotifications',
    [
        'controller' => 'course',
        'action'     => 'clearNewMessagesNotifications'
    ]
);

$router->add(
    '/profile/course/clearNewMessagesNotificationsUser',
    [
        'controller' => 'course',
        'action'     => 'clearNewMessagesNotificationsUser'
    ]
);

$router->add(
    '/profile/course/clearNewMaterialsNotifications',
    [
        'controller' => 'course',
        'action'     => 'clearNewMaterialsNotifications'
    ]
);

$router->add(
    '/profile/course/clearNewTasksNotifications',
    [
        'controller' => 'course',
        'action'     => 'clearNewTasksNotifications'
    ]
);

$router->add(
    '/profile/course/removeMember',
    [
        'controller' => 'course',
        'action'     => 'removeMember'
    ]
);

$router->add(
    '/profile/course/removeMaterial',
    [
        'controller' => 'course',
        'action'     => 'removeMaterial'
    ]
);

$router->add(
    '/profile/course/removeTask',
    [
        'controller' => 'course',
        'action'     => 'removeMaterial'
    ]
);

$router->add(
    '/profile/course/removeUploadedFile',
    [
        'controller' => 'course',
        'action'     => 'removeUploadedFile'
    ]
);

$router->add(
    '/profile/course/removeUploadedFileTask',
    [
        'controller' => 'course',
        'action'     => 'removeUploadedFileTask'
    ]
);

$router->add(
    '/profile/course/changeMemberRole',
    [
        'controller' => 'course',
        'action'     => 'changeMemberRole'
    ]
);

$router->add(
    '/profile/course/addMaterial',
    [
        'controller' => 'course',
        'action'     => 'addMaterial'
    ]
);

$router->add(
    '/profile/course/addTask',
    [
        'controller' => 'course',
        'action'     => 'addTask'
    ]
);

$router->add(
    '/profile/course/getAdminInfo',
    [
        'controller' => 'course',
        'action'     => 'getAdminInfo'
    ]
);

$router->add(
    '/profile/course/getUserInfo',
    [
        'controller' => 'course',
        'action'     => 'getUserInfo'
    ]
);

$router->add(
    '/profile/course/editMaterial',
    [
        'controller' => 'course',
        'action'     => 'editMaterial'
    ]
);

$router->add(
    '/profile/course/editTask',
    [
        'controller' => 'course',
        'action'     => 'editTask'
    ]
);

$router->add(
    '/profile/course/{course_id}/exportCourse',
    [
        'controller' => 'course',
        'action'     => 'exportCourse'
    ]
);

$router->add(
    '/profile/course/{course_id}/material/{material_id}',
    [
        'controller' => 'course',
        'action'     => 'material'
    ]
);

$router->add(
    '/profile/course/{course_id}/task/{task_id}',
    [
        'controller' => 'course',
        'action'     => 'task'
    ]
);

$router->add(
    '/profile/course/{course_id}/material/{material_id}/file/{file_id}',
    [
        'controller' => 'course',
        'action'     => 'materialFile'
    ]
);

$router->add(
    '/profile/course/task/sendMessage',
    [
        'controller' => 'course',
        'action'     => 'sendMessage'
    ]
);

$router->add(
    '/profile/course/task/sendMessageAdmin',
    [
        'controller' => 'course',
        'action'     => 'sendMessageAdmin'
    ]
);

$router->add(
    '/profile/course/task/returnUsersFiles',
    [
        'controller' => 'course',
        'action'     => 'returnUsersFiles'
    ]
);

$router->add(
    '/profile/course/task/uploadFilesForTask',
    [
        'controller' => 'course',
        'action'     => 'uploadFilesForTask'
    ]
);

$router->add(
    '/profile/course/task/scoreTask',
    [
        'controller' => 'course',
        'action'     => 'scoreTask'
    ]
);

$router->add(
    '/profile/course/task/cancelScoreTask',
    [
        'controller' => 'course',
        'action'     => 'cancelScoreTask'
    ]
);

$router->add(
    '/profile/course/{course_id}/task/{task_id}/file/{file_id}',
    [
        'controller' => 'course',
        'action'     => 'taskFile'
    ]
);

$router->add(
    '/profile/course/{course_id}/task/{task_id}/user/{user_id}/file/{file_id}',
    [
        'controller' => 'course',
        'action'     => 'downloadUserFile'
    ]
);

$router->add(
    '/profile/settings',
    [
        'controller' => 'profile',
        'action'     => 'settings'
    ]
);

$router->add(
    '/profile/logout',
    [
        'controller' => 'profile',
        'action'     => 'logout'
    ]
);

$router->add(
    '/profile/remove',
    [
        'controller' => 'profile',
        'action'     => 'remove'
    ]
);

$router->add(
    '/profile/changeEmail',
    [
        'controller' => 'profile',
        'action'     => 'changeEmail'
    ]
);

$router->add(
    '/profile/changeEmail/token/{token}',
    [
        'controller' => 'profile',
        'action'     => 'token'
    ]
);

$router->add(
    '/profile/changePassword',
    [
        'controller' => 'profile',
        'action'     => 'changePassword'
    ]
);



$container->set('router', $router);

$container->set(
    'session',
    function () {
        $session = new Manager();
        $files   = new Stream(
            [
                'savePath' => '/tmp',
            ]
        );
        $session->setAdapter($files);
        $session->start();
        return $session;
    }
);

$application = new Application($container);


try {

    // Handle the request
    $response = $application->handle(
        $_SERVER["REQUEST_URI"]
    );


    // var_dump($_SESSION);





    $response->send();
} catch (\Exception $e) {
    echo 'Exception: ', $e->getMessage();
}
