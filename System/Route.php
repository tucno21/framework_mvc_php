<?php

namespace System;

use System\Request;
use System\RenderView;
use System\ResponseHTTP;

/**
 * manejo de rutas GET y POST
 */

class Route
{
    /**
     * almacena las Array enviadas del archivo routes funcion GET o POST
     * array(2) {
     * ["/"]=>
     * string(22) "Desde la url principal"
     * ["/parametro"]=>
     * string(37) "Desde la url principal y su parametro" 
     * ["/parametro/aa"]=>
     * string(38) "Desde la url principal y dos parametro"}
     */
    protected static array $getRoutes = [];
    protected static array $postRoutes = [];

    /**
     * instanciando el objeto Request
     */
    public static Request $request;

    /**
     * instanciando el objeto responseHTTP
     */
    public static ResponseHTTP $responseHTTP;
    /**
     * instanciando el objeto RenderView
     */
    public static RenderView $renderView;

    /**
     * recibe el metodo  GET, el parametro de la url y el controlador o funcion
     * del archivo App/Config/Routes.php
     */
    public static function get($url, $controller)
    {
        self::$getRoutes[$url] = $controller;
    }

    /**
     * recibe el metodo  POST, el parametro de la url y el controlador o funcion
     * del archivo App/Config/Routes.php
     */
    public static function post($url, $controller)
    {
        self::$postRoutes[$url] = $controller;
    }

    /**
     * ejecuta la busqueda de rutas para los controladores
     */
    public static function run()
    {
        self::$request = new Request();
        self::$responseHTTP = new ResponseHTTP();
        self::$renderView = new RenderView();
        self::checkRoutes();
    }

    protected static function checkRoutes()
    {
        $callback = self::searchRoutes();

        /**
         * conprueba si el valor es un string get(clave, valor);
         * $routes->get('/', "Desde la url principal");
         */
        if (is_string($callback)) {
            echo 'es solo un string';
        }

        /**
         * cuando los parametros de la web no existe en el routes.php 
         * error 404
         */
        if ($callback == null) {
            self::$responseHTTP->setStatusCode(404);
            echo self::$renderView->render('error/404');
        }

        /**
         * comprueba si el valor es una array get(clave, valor);
         * get('/array', [new HomeController(), 'home']);
         * y llama al controlador
         */
        if (is_array($callback)) {
            //$callback = ["App\Controller\Auth\HomeController", "home"]
            $callback[0] = new $callback[0];
            //convierte el primer string en objeto class
            //$callback = [object(App\Controller\Auth\HomeController), "home"] 
            /**
             * llamar al controlador y ejecutar su contenido
             */
            return call_user_func($callback, new static);
        }

        /**
         * comprueba si el valor es una funcion u objeto get(clave, valor);
         * $routes->get('/funcion', function () { echo 'desde el funcion';});
         * $routes->get('/objeto', [HomeController::class, 'home']);
         * <?php
         * function barber($type)
         * {
         *     echo "You wanted a $type haircut, no problem\n";
         * }
         * call_user_func('barber', "mushroom");
         * call_user_func('barber', "shave");
         * ?>
         * RESULTADO
         * You wanted a mushroom haircut, no problem
         * You wanted a shave haircut, no problem
         * https://runebook.dev/es/docs/php/function.call-user-func
         */
        if (is_object($callback)) {
            /**
             * llamar al controlador y ejecutar su contenido
             * $this envia todo el objeto a la al controlador
             */
            return call_user_func($callback, new static);
        }
    }

    /**
     * busca la url en el array de getRoutes o postRoutes
     * @return string
     */
    protected static function searchRoutes()
    {
        /**
         * trae el HTTP GET y POST de la web
         */
        $methodUrl = self::$request->methodWeb();
        /**
         * trae el parametros de la web
         */
        $paramUrl = self::$request->getPath();

        /**
         * compara el el metodo get o post con la web Actual  
         * busca el parametro actual de la Web, en el array de getRoutes o postRoutes
         * si existe el parametro busca el controlador o funcion
         * y envia el valor "[HomeController::class, 'home']"
         */
        if ($methodUrl === 'get') {
            $callback = self::$getRoutes[$paramUrl] ?? null;
        } else {
            $callback = self::$postRoutes[$paramUrl] ?? null;
        }

        return $callback;
    }
}
