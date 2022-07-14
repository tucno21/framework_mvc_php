<?php

use System\RenderView;

/**
 * funciones principales para toda la aplicacion
 */

/**
 * debugear sin continuar con otros codigos de linea
 */
function dd($variable): string
{
    echo "<pre>";
    var_dump($variable);
    echo "</pre>";
    exit;
}

/**
 * debugear continuando las lineas de codigo
 */
function d($variable)
{
    echo "<pre>";
    var_dump($variable);
    echo "</pre>";
}

/**
 * ruta de documento public
 */
define('DIRPUBLIC', $_SERVER['DOCUMENT_ROOT']);

/**
 * ruta de la carpeta App/
 */
define('APPDIR', dirname(__DIR__) . '/App');


/**
 * funcion para extender partes layout o vista
 */
function extend($dirView)
{
    include APPDIR . '/View' . $dirView;
}


/**
 * url de la web Principal
 */
define('base_url', $baseURL);

/**
 * funcion url con parametros
 */
function base_url($parameters = null)
{
    return base_url . $parameters;
}

/**
 * Funcion para renderizar las vistas desde el controlador
 */
if (!function_exists('view')) {

    function view(string $name, array $data = [])
    {
        if (!empty($name)) {
            return RenderView::$renderApp->render($name, $data);
        } else {
            echo "Upsss... escriba una nombre o ruta/nombre para renderizar la vista";
        }
    }
}

/**
 * funcion para renderizar vistas como redireccionamiento a otra vista
 */
if (!function_exists('redirect')) {
    function redirect(string $name, array $data = [])
    {
        if (!empty($name)) {
            return RenderView::$renderApp->render($name, $data);
        } else {
            echo "Upsss... escriba una nombre o ruta/nombre para renderizar la vista";
        }
    }
}
