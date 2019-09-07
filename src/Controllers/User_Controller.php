<?php

namespace App\Controllers;
require(__DIR__ . '/../Core/Controller.php');
require(__DIR__ . '/../Models/User_model.php');

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
                ['field' => 'userName', 'type' => 'string', 'required' => 'true'],
                ['field' => 'login', 'type' => 'string', 'required' => 'true'],
                ['field' => 'password', 'type' => 'string', 'required' => 'true']
            ]
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
}
