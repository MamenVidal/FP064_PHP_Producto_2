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

// añado declaración de rutas para todos
$router->addRoute('dashboard', 'Index', 'dashboard');
$router->addRoute('loginPost', 'User', 'loginPost');
$router->addRoute('registerPost', 'User', 'registerPost');
$router->addRoute('logout', 'User', 'logout');
$router->addRoute('user-edit', 'User', 'userEdit');
$router->addRoute('user-save', 'User', 'userSave');

// acciones administrador
$router->addRoute('acto-edit', 'Admin', 'actoEdit');
$router->addRoute('acto-save', 'Admin', 'actoSave');

// acciones calendario
$router->addRoute('calendario', 'Calendario', 'index');
$router->addRoute('inscripcion', 'Calendario', 'inscripcion');
$router->addRoute('desuscripcion', 'Calendario', 'desuscripcion');

// resuelvo la ruta al controlador y acción
$router->dispatch();