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
$router->addRoute('tipoacto-edit', 'Admin', 'tipoActoEdit');
$router->addRoute('tipoacto-save', 'Admin', 'tipoActoSave');
$router->addRoute('tipoacto-delete', 'Admin', 'tipoActoDelete');

$router->addRoute('ponente-list', 'Admin', 'ponenteList');
$router->addRoute('ponente-add', 'Admin', 'ponenteAdd');
$router->addRoute('ponente-save', 'Admin', 'ponenteSave');
$router->addRoute('ponente-remove', 'Admin', 'ponenteRemove');

$router->addRoute('inscritos-list', 'Admin', 'inscritosList');
$router->addRoute('inscritos-add', 'Admin', 'inscritosAdd');
$router->addRoute('inscritos-save', 'Admin', 'inscritosSave');
$router->addRoute('inscritos-remove', 'Admin', 'inscritosRemove');

$router->addRoute('acto-edit', 'Admin', 'actoEdit');
$router->addRoute('acto-save', 'Admin', 'actoSave');
$router->addRoute('acto-delete', 'Admin', 'actoDelete');

// acciones calendario
$router->addRoute('calendario', 'Calendario', 'index');
$router->addRoute('inscripcion', 'Calendario', 'inscripcion');
$router->addRoute('desuscripcion', 'Calendario', 'desuscripcion');

// resuelvo la ruta al controlador y acción
$router->dispatch();