<?php

/**
 * Show errors (only during develop)
 */
error_reporting(E_ALL);
ini_set("display_errors", 1);

use \Core\Router;

/**
 * Composer
 */
require dirname(__DIR__) . '/vendor/autoload.php';

/**
 * Router
 */
$router = new Router();

// añado declaración de rutas
$router->addRoute('dashboard', 'Index', 'dashboard');
$router->addRoute('loginPost', 'User', 'loginPost');
$router->addRoute('registerPost', 'User', 'registerPost');
$router->addRoute('logout', 'User', 'logout');

// resuelvo la ruta al controlador y acción
$router->dispatch($_SERVER['REQUEST_URI']);