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


    public function isGet()
    {
        return $this->methodWeb() === 'get';
    }

    public function isPost()
    {
        return $this->methodWeb() === 'post';
    }

    /**
     * captura los datos de la url mediante el metodo GET y post
     */
    public function getInput()
    {
        $data = [];
        if ($this->isGet()) {
            foreach ($_GET as $key => $value) {
                $data[$key] = filter_input(INPUT_GET, $key, FILTER_SANITIZE_SPECIAL_CHARS);
            }
        }
        if ($this->isPost()) {
            foreach ($_POST as $key => $value) {
                $data[$key] = filter_input(INPUT_POST, $key, FILTER_SANITIZE_SPECIAL_CHARS);
            }
        }

        if (!empty($_FILES)) {
            $data = array_merge($data, $_FILES);
        }

        return $data;
    }
}
