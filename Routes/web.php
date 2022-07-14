<?php

use System\Route;

/**
 * coneccion con el archivo autoload de la aplicacion
 */
require_once dirname(__DIR__) . '/System/Autoload.php';

Route::get('/', 'hola');


/**
 * ejecuta la busqueda de rutas para los controladores
 */
Route::run();
