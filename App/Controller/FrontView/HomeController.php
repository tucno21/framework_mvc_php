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
        // $user = AuthModel::all();

        $data = [
            "title" => date('Y-m-d H:i:s'),
            "email" => "carlos@carlos.com",
            "password" => ""
        ];


        $valid = $this->validate($data, [
            'title' => 'datetime',
            'email' => 'email|unique:AuthModel,email',
            'password' => 'required|min:6',
        ]);

        d($valid);

        return view('frontView/index', [
            'var' => 'es una variable',
        ]);
    }
}
