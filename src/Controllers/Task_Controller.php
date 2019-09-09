<?php

namespace App\Controllers;
require_once(__DIR__ . '/../Core/Controller.php');
require_once(__DIR__ . '/../Models/Task_model.php');

use App\Core\Controller;
use App\Models\Task_model;

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
            'saveEdited' => [
                ['field' => 'uid', 'type' => 'integer', 'required' => 'true'],
                ['field' => 'userName', 'type' => 'string', 'required' => 'true'],
                ['field' => 'email', 'type' => 'string', 'required' => 'true'],
                ['field' => 'text', 'type' => 'string', 'required' => 'true'],
                ['field' => 'status_id', 'type' => 'integer', 'required' => 'true']
            ],
            'edit' => [
                ['field' => 'id', 'type' => 'integer', 'required' => 'true']
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
            echo $this->view->render('newTask.html', $data);
            return;
        }

        $result = $this->model->add($data);
        if ($this->ErrorData($result)) {
            echo $this->view->render('newTask.html', $result);
            return;
        }

        $_SESSION['Msg'] = 'New task was added';
        header("Location: http://testtaskmanager.epizy.com");
        return;
    }

    public function edit($data = [])
    {
        $data = $this->parseInputData($data);
        if ($this->ErrorData($data)) {
            $_SESSION['Error_Msg'] = $data['Error_Msg'];
            header("Location: http://testtaskmanager.epizy.com");
            return;
        }

        $result = $this->model->get($data);
        if ($this->ErrorData($result)) {
            $_SESSION['Error_Msg'] = $result['Error_Msg'];
            header("Location: http://testtaskmanager.epizy.com");
            return;
        }
        $result = $result['data'][0];
        $result['edit'] = true;


        echo $this->view->render('newTask.html', $result);
        return;
    }

    public function saveEdited($data = [])
    {
        if (!$this->isAdmin()) {
            header("Location: http://testtaskmanager.epizy.com/Main/login");
            return;
        }

        $data = $this->parseInputData($data);
        if ($this->ErrorData($data)) {
            echo $this->view->render('newTask.html', $data);
            return;
        }

        $result = $this->model->saveEdited($data);
        if ($this->ErrorData($result)) {
            echo $this->view->render('newTask.html', $result);
            return;
        }

        $_SESSION['Msg'] = 'The task was edited';
        header("Location: http://testtaskmanager.epizy.com");
        return;
    }
}
