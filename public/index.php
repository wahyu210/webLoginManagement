<?php
// echo "hello php mvc";
// $path = '/index';
// if(isset($_SERVER['PATH_INFO'])){
//     $path = $_SERVER['PATH_INFO'];
// }
// echo $path;
// require __DIR__ . '/../app/view' . $path . '.php';

require_once __DIR__ . '/../vendor/autoload.php';
use MochamadWahyu\Phpmvc\App\Router;
use MochamadWahyu\Phpmvc\Config\Database;
use MochamadWahyu\Phpmvc\Controller\HomeController;
use MochamadWahyu\Phpmvc\Controller\UserController;



Database::getConnection('prod');

//HomeController
Router::add('GET', '/', HomeController::class, 'index',[] );
// Router::add('POST', '/', HomeController::class, 'index',[] );
// Router::add('GET', '/about', HomeController::class, 'about');
//UserController
Router::add('GET','/users/register',UserController::class,'register',[]);
Router::add('POST', '/users/register', UserController::class, 'postRegister', []);
Router::add('GET', '/users/login', UserController::class, 'login',[]);
Router::add('POST',  '/users/login', UserController::class, 'postLogin', []);
Router::run();
// echo "Routing is working fine";
