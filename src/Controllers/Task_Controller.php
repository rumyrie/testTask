<?php

namespace App\Controllers;
require_once(__DIR__ . '/../Core/Controller.php');
require_once(__DIR__ . '/../Models/Task_model.php');

use App\Core\Controller;
use App\Models\Task_model;
use Twig;

class Task_Controller extends Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->model = new Task_model();
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

    public function add($data = [])
    {
        $data = $this->parseInputData($data);
        if ($this->ErrorData($data)) {
            echo $this->view->render('index.html', $data);
            return;
        }

        $result = $this->model->add($data);
        echo $this->view->render('index.html', $result);

        return;
    }

    public function edit($data = [])
    {
        $data = $this->parseInputData($data);
        if ($this->ErrorData($data)) {
            echo $this->view->render('index.html', $data);
            return;
        }

        $result = $this->model->edit($data);
    }

    public function delete($data = [])
    {
        $data = $this->parseInputData($data);
        if ($this->ErrorData($data)) {
            echo $this->view->render('index.html', $data);
            return;
        }

        $result = $this->model->delete($data);
    }

    public function getList($data = [])
    {
        $data = $this->parseInputData($data);
        if ($this->ErrorData($data)) {
            echo $this->view->render('index.html', $data);
            return;
        }

        $result = $this->model->getList($data);
    }
}
