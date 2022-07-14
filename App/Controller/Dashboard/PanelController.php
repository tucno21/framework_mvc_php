<?php

namespace App\Controller\Dashboard;

use System\Controller;
use App\Model\AuthModel;


class PanelController extends Controller
{
    // protected $authModel;

    public function __construct()
    {
        // enviar los datos de la sesion, y los parametros de la url para validar
        $this->middleware($this->sessionGet('user'), ['/dashboard']);
    }

    public function index()
    {
        $users = AuthModel::all();
        return view('dashboard/index', [
            'users' => $users,
        ]);
    }

    public function edit()
    {
        return view('dashboard/edit', []);
    }
}
