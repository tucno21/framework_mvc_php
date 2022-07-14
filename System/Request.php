<?php

namespace System;

/**
 * captura el metodo web GET Y POST
 */
class Request
{
    /**
     * captura y tipo de HTTP y convierte en minuscula
     * envia el tipo de HTTP a la funcion searchRoutes
     */
    public function methodWeb()
    {
        return strtolower($_SERVER['REQUEST_METHOD']);
    }


    /**
     * captura los parametros despues de la url principal
     * y envia el parametro a la funcion searchRoutes
     */
    public function getPath()
    {
        $path = $_SERVER['REQUEST_URI'];
        //captura solo antes del '?'
        $position = strpos($path, '?');
        if ($position === false) {
            return $path;
        }
        return $path = substr($path, 0, $position);
    }
}
