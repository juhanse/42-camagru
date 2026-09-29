<?php
session_start();

require_once __DIR__ . '/core/Database.php';
require_once __DIR__ . '/core/Controller.php';
require_once __DIR__ . '/core/Router.php';
require_once __DIR__ . '/controllers/HomeController.php';
require_once __DIR__ . '/controllers/ProfileController.php';
require_once __DIR__ . '/controllers/StudioController.php';
require_once __DIR__ . '/controllers/AdminController.php';

$router = new Router();

$router->add('GET', '/', 'HomeController', 'index');
$router->add('POST', '/like', 'HomeController', 'like');
$router->add('POST', '/comment', 'HomeController', 'comment');
$router->add('POST', '/report', 'HomeController', 'report');

$router->add('GET', '/register', 'AuthController', 'register');
$router->add('POST', '/register', 'AuthController', 'registerPost');
$router->add('GET', '/verify', 'AuthController', 'verify');

$router->add('GET', '/login', 'AuthController', 'login');
$router->add('POST', '/login', 'AuthController', 'loginPost');
$router->add('GET', '/logout', 'AuthController', 'logout');

$router->add('GET', '/forgot', 'AuthController', 'forgot');
$router->add('POST', '/forgot', 'AuthController', 'forgotPost');
$router->add('GET', '/reset', 'AuthController', 'reset');
$router->add('POST', '/reset', 'AuthController', 'resetPost');

$router->add('GET', '/profile', 'ProfileController', 'index');
$router->add('POST', '/profile', 'ProfileController', 'update');

$router->add('GET', '/studio', 'StudioController', 'index');
$router->add('POST', '/studio/save', 'StudioController', 'save');
$router->add('POST', '/studio/delete', 'StudioController', 'delete');

$router->add('GET', '/admin', 'AdminController', 'index');
$router->add('POST', '/admin/ignore', 'AdminController', 'ignore');
$router->add('POST', '/admin/delete', 'AdminController', 'delete');

$router->dispatch($_SERVER['REQUEST_URI'], $_SERVER['REQUEST_METHOD']);
