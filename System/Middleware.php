<?php

/**
 * middleware es un archivo que filtra las peticiones HTTP en un sistema, 
 * es un archivo adicional que va en el medio 
 * de la petición y de eso que se quiere ver como resultado final
 */

namespace System;

use System\Request;

class Middleware
{
    /**
     * filtra las peticiones HTTP en un sistema mediante el archivo Request.php
     */
    protected Request $request;

    public function __construct()
    {
        $this->request = new Request();
    }

    public function run(mixed $session, array $restrictions)
    {
        if ($session === false) {

            /**
             * compara los parametros capturados con el middleware creado para su acceso
             */
            $url = $this->request->getPath();
            $resp = array_search($url, $restrictions);

            //strstr estrae la cadena de texto de una parte de su busqueda
            $urlNotPermission = strstr($url, $restrictions[$resp], false);

            // if (is_int($resp)) {
            if ($urlNotPermission) {
                header("Location: /");
            }
        }
    }
}
