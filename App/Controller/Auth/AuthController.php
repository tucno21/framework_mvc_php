<?php

namespace App\Controller\Auth;

use System\Controller;
use App\Model\AuthModel;


class AuthController extends Controller
{
    public function __construct()
    {
        // enviar los datos de la sesion, y los parametros de la url para validar
        // $this->middleware($this->sessionGet('user'), ['/dashboard']);
    }

    public function login()
    {
        $result = $this->request()->isPost();

        if ($result) {
            $data = $this->request()->getInput();

            $valid = $this->validate($data, [
                'email' => 'required|email|not_unique:AuthModel,email',
                'password' => 'required|password_verify:AuthModel,email',
            ]);

            if ($valid !== true) {

                return view('auth/login', [
                    'err' =>  (object)$valid,
                    'data' => (object)$data,
                ]);
            } else {

                $user = AuthModel::select('id, email, name')->where('email', $data['email'])->first();

                $this->sessionSet('user', $user);

                return $this->redirect('dashboard');
            }
        }
        return view('auth/login');
    }

    public function register()
    {
        $result = $this->request()->isPost();

        if ($result) {
            $data = $this->request()->getInput();

            $valid = $this->validate($data, [
                'name' => 'required|text',
                'email' => 'required|email|unique:AuthModel,email',
                'password' => 'required|min:3|max:12|matches:password_confirm',
                'password_confirm' => 'required',
            ]);

            if ($valid !== true) {

                return $this->view('auth/register', [
                    'err' =>  (object)$valid,
                    'data' => (object)$data,
                ]);
            } else {

                AuthModel::create($data);
                return $this->redirect('login');
            }
        }
        return view('auth/register');
    }

    public function logout()
    {
        $this->sessionDestroy('user');
        return $this->redirect('/');
    }
}
