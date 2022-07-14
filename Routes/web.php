<?php

use System\Route;
use App\Controller\FrontView\HomeController;

/**
 * coneccion con el archivo autoload de la aplicacion
 */
require_once dirname(__DIR__) . '/System/Autoload.php';

//  FrontView
Route::get('/', [HomeController::class, 'index']);


/**
 * ejecuta la busqueda de rutas para los controladores
 */
Route::run();
