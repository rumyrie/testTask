<?php

namespace App\Controllers;
require_once(__DIR__ . '/../Core/Controller.php');
require_once(__DIR__ . '/../Models/Task_model.php');
require_once(__DIR__ . '/../Models/User_model.php');

use App\Models\Task_model;
use App\Core\Controller;
use App\Models\User_model;
use Twig\Environment;

class Main_Controller extends Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->inputRules = [
            'add' => [
                ['field' => 'userName', 'type' => 'string', 'required' => 'true'],
                ['field' => 'email', 'type' => 'string', 'required' => 'true'],
                ['field' => 'text', 'type' => 'string', 'required' => 'true']
            ],
            'edit' => [
                ['field' => 'userName', 'type' => 'string', 'required' => ''],
                ['field' => 'email', 'type' => 'string', 'required' => ''],
                ['field' => 'text', 'type' => 'string', 'required' => ''],
                ['field' => 'status_id', 'type' => 'string', 'required' => '']
            ],
            'delete' => [
                ['field' => 'uid', 'type' => 'integer', 'required' => 'true']
            ],
            'getList' => [
                ['field' => 'order', 'type' => 'string', 'required' => ''],
                ['field' => 'page', 'type' => 'integer', 'required' => '']
            ]
        ];
    }

    public function main()
    {
        $this->model = new Task_model();
        $result = $this->model->getList();

        echo $this->view->render('index.html', $result);
        return;
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
