<?php

use System\Route;
use App\Controller\Auth\AuthController;
use App\Controller\FrontView\HomeController;
use App\Controller\Dashboard\PanelController;

/**
 * coneccion con el archivo autoload de la aplicacion
 */
require_once dirname(__DIR__) . '/System/Autoload.php';

//  FrontView
Route::get('/', [HomeController::class, 'index']);

// autenticacion
Route::get('/login', [AuthController::class, 'login']);
Route::post('/login', [AuthController::class, 'login']);
Route::get('/register', [AuthController::class, 'register']);
Route::post('/register', [AuthController::class, 'register']);
Route::get('/logout', [AuthController::class, 'logout']);

// backend
Route::get('/dashboard', [PanelController::class, 'index']);
Route::get('/dashboard/edit', [PanelController::class, 'edit']);

/**
 * ejecuta la busqueda de rutas para los controladores
 */
Route::run();
