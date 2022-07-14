<?php

namespace App\Controller\FrontView;

use System\Controller;
use App\Model\AuthModel;

/**
 * controlador de la web
 */
class HomeController extends Controller
{

    public function index()
    {
        $user = AuthModel::all();
        d($user);

        return view('frontView/index', [
            'var' => 'es una variable',
        ]);
    }
}
