<?php
session_start();

require_once __DIR__ . '/core/Database.php';
require_once __DIR__ . '/core/Controller.php';
require_once __DIR__ . '/core/Router.php';

$router = new Router();

$router->add('GET', '/', 'HomeController', 'index');
$router->add('GET', '/register', 'AuthController', 'register');
$router->add('POST', '/register', 'AuthController', 'registerPost');
$router->add('GET', '/verify', 'AuthController', 'verify');
$router->add('GET', '/login', 'AuthController', 'login');
$router->add('POST', '/login', 'AuthController', 'loginPost');
$router->add('GET', '/logout', 'AuthController', 'logout');

$router->dispatch($_SERVER['REQUEST_URI'], $_SERVER['REQUEST_METHOD']);
