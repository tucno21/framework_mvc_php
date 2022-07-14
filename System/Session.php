<?php

namespace System;

/**
 * Crear sesiones mediante objetos usado en el controlador
 */

class Session
{
    public function __construct()
    {
        /**
         * verificar si existe una sesion
         * si no existe crea una
         */
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
    }

    /**
     * crear una sesion mediante array de la base de datos
     */
    public function set($key, $value)
    {
        $_SESSION[$key] = $value;
    }

    /**
     * obtener una sesion mediante array de la base de datos
     */
    public function get($key)
    {
        return $_SESSION[$key] ?? false;
    }


    /**
     * remover una sesion
     */
    public function remove($key)
    {
        unset($_SESSION[$key]);
    }
}
