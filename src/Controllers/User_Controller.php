<?php

namespace App\Controllers;
require_once(__DIR__ . '/../Core/Controller.php');
require_once(__DIR__ . '/../Models/User_model.php');
require_once(__DIR__ . '/../Models/Task_model.php');

use App\Core\Controller;
use App\Models\User_model;

class User_Controller extends Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->model = new User_model();
        $this->inputRules = [
            'add' => [
                ['field' => 'login', 'type' => 'string', 'required' => 'true'],
                ['field' => 'password', 'type' => 'string', 'required' => 'true']
            ],
            'login' => [
                ['field' => 'login', 'type' => 'string', 'required' => 'true'],
                ['field' => 'password', 'type' => 'string', 'required' => 'true']
            ]
        ];
    }

    public function add($data = [])
    {
        $data = $this->parseInputData($data);
        if ($this->ErrorData($data)) {
            echo $this->view->render('auth.html', [
                'register' => true,
                'Error_Msg' => isset($data['Error_Msg']) ? $data['Error_Msg'] : 'Incorrect data'
            ]);
            return;
        }

        $user = $this->model->checkUser($data);
        if ($this->ErrorData($user)) {
            $user['register'] = true;
            echo $this->view->render('auth.html', $user);
            return;
        }
        if (!empty($user['data'])) {
            echo $this->view->render('auth.html', [
                'register' => true,
                'Error_Msg' => 'User ' . $data['login'] . ' already exists'
            ]);
            return;
        }

        $result = $this->model->add($data);
        if ($result['success']) {
            $this->login($data);
        } else {
            $result['register'] = true;
            echo $this->view->render('auth.html', $result);
        }
        return;
    }

    public function login($data = [])
    {
        $data = $this->parseInputData($data);
        if ($this->ErrorData($data)) {
            echo $this->view->render('auth.html', [
                'Error_Msg' => isset($data['Error_Msg']) ? $data['Error_Msg'] : 'Incorrect data'
            ]);
            return;
        }

        $user = $this->model->checkUser($data);
        if ($this->ErrorData($user)) {
            echo $this->view->render('auth.html', $user);
            return;
        }

        if (empty($user['data'])) {
            echo $this->view->render('auth.html', [
                'Error_Msg' => 'User ' . $data['login'] . ' does not exist'
            ]);
            return;
        }

        $result = $this->model->login($data);
        if ($result['success']) {
            header("Location: http://testtaskmanager.epizy.com");
            return;
        } else {
            echo $this->view->render('auth.html', $result);
        }
        return;
    }

    public function logout()
    {
        if (isset($_COOKIE['session'])) {
            $this->model->logout();
            unset($_COOKIE['session']);
        }
        session_destroy();

        header("Location: http://testtaskmanager.epizy.com");
        return;
    }
}
