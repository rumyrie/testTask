<?php

namespace App\Controllers;
require_once(__DIR__ . '/../Core/Controller.php');
require_once(__DIR__ . '/../Models/Task_model.php');
require_once(__DIR__ . '/../Models/User_model.php');

use App\Models\Task_model;
use App\Core\Controller;

class Main_Controller extends Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->inputRules = [
            'main' => [
                ['field' => 'page', 'type' => 'integer', 'required' => ''],
                ['field' => 'order', 'type' => 'string', 'required' => '']
            ]
        ];
    }

    public function main($data = [])
    {
        $this->model = new Task_model();
        $data = $this->parseInputData($data);
        if ($this->ErrorData($data)) {
            echo $this->view->render('index.html', $data);
            return;
        }

        if (!empty($data['order'])) {
            $_SESSION['order'] = $data['order'];
        }
        if (empty($data['page']) && empty($data['order'])) {
            unset($_SESSION['order']);
        }
        if (!empty($_SESSION['order']) && empty($data['order'])) {
            $data['order'] = $_SESSION['order'];
        }

        $result = $this->model->getList($data);

        if (isset($_SESSION['login'])) {
            $result['uid'] = true;
        }
        if ($this->isAdmin()) {
            $result['edit'] = true;
        }
        if (isset($_SESSION['Msg'])) {
            $result['Msg'] = $_SESSION['Msg'];
            unset($_SESSION['Msg']);
        }
        if (!empty($_SESSION['order'])) {
            $result['order'] = $_SESSION['order'];
        }

        echo $this->view->render('index.html', $result);
        return;
    }

    public function addTask()
    {
        echo $this->view->render('newTask.html');
    }

    public function login()
    {
        echo $this->view->render('auth.html');
        return;
    }

    public function register()
    {
        echo $this->view->render('auth.html', ['register' => true]);
        return;
    }
}
