<?php

namespace App\Controllers;
require(__DIR__ . '/../Core/Controller.php');
require(__DIR__ . '/../Models/Task_model.php');

use App\Core\Controller;

class Task_Controller extends Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->inputRules = [
            'add' => [
                ['field' => 'userName', 'type' => 'string', 'required' => 'true'],
                ['field' => 'email', 'type' => 'string', 'required' => 'true'],
                ['field' => 'task', 'type' => 'string', 'required' => 'true']
            ],
            'edit' => [
                ['field' => 'userName', 'type' => 'string', 'required' => ''],
                ['field' => 'email', 'type' => 'string', 'required' => ''],
                ['field' => 'task', 'type' => 'string', 'required' => ''],
                ['field' => 'status_id', 'type' => 'string', 'required' => '']
            ],
            'delete' => [
                ['field' => 'uid', 'type' => 'integer', 'required' => 'true']
            ],
        ];
    }

    public function add($data = [])
    {
        $data = $this->parseInputData($data);
        if ($this->ErrorData($data)) {
            return isset($data['Error_Msg']) ? $data['Error_Msg'] : false;
        }

        $this->model->add($data);
    }

    public function edit($data = [])
    {
        $data = $this->parseInputData($data);
        if ($this->ErrorData($data)) {
            return isset($data['Error_Msg']) ? $data['Error_Msg'] : false;
        }

        $this->model->edit($data);
    }

    public function delete($data = [])
    {
        $data = $this->parseInputData($data);
        if ($this->ErrorData($data)) {
            return isset($data['Error_Msg']) ? $data['Error_Msg'] : false;
        }

        $this->model->delete($data);
    }
}
