<?php

namespace App\Controllers;
require(__DIR__ . '/../Core/Controller.php');
require(__DIR__ . '/../Models/Task_model.php');

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
            return isset($data['Error_Msg']) ? $data['Error_Msg'] : false;
        }

        return $this->model->add($data);
    }

    public function edit($data = [])
    {
        $data = $this->parseInputData($data);
        if ($this->ErrorData($data)) {
            return isset($data['Error_Msg']) ? $data['Error_Msg'] : false;
        }

        return $this->model->edit($data);
    }

    public function delete($data = [])
    {
        $data = $this->parseInputData($data);
        if ($this->ErrorData($data)) {
            return isset($data['Error_Msg']) ? $data['Error_Msg'] : false;
        }

        return $this->model->delete($data);
    }

    public function getList($data = [])
    {
        $data = $this->parseInputData($data);
        if ($this->ErrorData($data)) {
            return isset($data['Error_Msg']) ? $data['Error_Msg'] : false;
        }

        return $this->model->getList($data);
    }
}
