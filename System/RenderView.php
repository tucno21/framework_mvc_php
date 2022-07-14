<?php

namespace System;

/**
 * renderizar las vistas
 */

class RenderView
{
    /**
     * invocar el objeto como Statico
     */
    public static RenderView $renderApp;

    public function __construct()
    {
        /**
         * enviar todo el objeto creado RenderView para ser invocado
         */
        self::$renderApp = $this;
    }


    /**
     * renderiza la vista desde el controlador
     * @param string nombre de la vista y archivo.php 
     * @param array valores o array enviados desde la vista
     */
    public function render($view, $data = [])
    {
        $content = $this->renderOnlyView($view, $data);
        /**
         * renderView.php donde se muestra toda la web
         */
        include_once APPDIR . '/View/renderView.php';
    }

    /**
     * buscar el nombre de la vista del controlador
     * enviar para que se renderice en renderView.php
     */
    protected function renderOnlyView($view,  $data)
    {
        foreach ($data as $key => $value) {
            $$key = $value;
        }
        // dd($data);

        $path = APPDIR . "/View/$view.php";

        if (file_exists($path)) {
            ob_start();
            include_once $path;
            return ob_get_clean();
        } else {
            echo "Upsss... No se encontro el archivo para renderizar, verifica que has creado el archivo o que el nombre sea correcto " . $path;
        }
    }
}
