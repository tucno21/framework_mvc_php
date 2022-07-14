<?php

/**
 * controlador general
 */

namespace System;

use System\Request;
use System\Session;
use System\Middleware;
use System\RenderView;


class Controller
{
    /**
     * enviar controlador para renderizar la vista
     * se debe invocar desde controlodares extendidos
     */
    protected function view(string $view, array $data = [])
    {
        return RenderView::$renderApp->render($view, $data);
    }

    /**
     * redireccionar a una url
     */
    protected function redirect(string $url)
    {
        if ($url == '/') {
            header("Location: $url");
        } else {
            header("Location: /$url");
        }
    }

    /**
     * controlador que inicia la sesion
     */

    protected function session()
    {
        return new Session();
    }

    /**
     * enviar nombre y datos para generar la sesion
     */
    protected function sessionSet(string $key, mixed $data)
    {
        return $this->session()->set($key, $data);
    }

    /**
     * obtener datos de la sesion
     */
    protected function sessionGet(string $key)
    {
        return $this->session()->get($key);
    }

    /**
     * remover datos de la sesion
     */
    protected function sessionDestroy(string  $key)
    {
        return $this->session()->remove($key);
    }

    /**
     * un middleware que verifica si el usuario de tu aplicación está autenticado
     */
    protected function middleware(mixed $session, array $middleware)
    {
        $mw = new Middleware();
        $mw->run($session, $middleware);
    }

    protected function request()
    {
        return new Request();
    }
}
