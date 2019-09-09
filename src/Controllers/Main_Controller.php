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
    }

    public function main()
    {
        $this->model = new Task_model();
        $result = $this->model->getList();

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
