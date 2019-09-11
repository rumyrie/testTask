<?php

namespace App\Core;
require_once(__DIR__ . '/../Models/User_model.php');

use \Twig\Loader\FilesystemLoader;
use \Twig\Environment;
use App\Models\User_model;

abstract class Controller
{
    protected $inputRules;
    protected $model;
    protected $loader;
    protected $view;

    public function __construct()
    {
        $this->inputRules = [];
        $this->loader = new FilesystemLoader(__DIR__ . '/../Views/');
        $this->view = new Environment($this->loader);
    }

    /**
     * Checks whether errors were encountered during parsing of data
     */
    protected function ErrorData($data)
    {
        if (!$data || isset($data['Error_Msg']) || (isset($data['success']) && !$data['success'])) {
            return true;
        }

        return false;
    }

    protected function parseInputData($data)
    {
        if (!is_array($data)) {
            return ['Error_Msg' => 'Data was not passed properly'];
        }
        $method = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS)[1]['function'];
        $result = [];

        foreach ($data as $key => $value) {
            if (is_numeric($value) && $key !== 'password') {
                $data[$key] = intval($value);
            }
        }

        if ($this->inputRules[$method]) {
            foreach ($this->inputRules[$method] as $rule) {
                if ($rule['required']) {
                    if (empty($data[$rule['field']])) {
                        $data['Error_Msg'] = 'The required variable ' . $rule['field'] . ' was not passed';
                        return $data;
                    }
                }
                if (isset($data[$rule['field']]) && gettype($data[$rule['field']]) != $rule['type']) {
                    $data['Error_Msg'] = 'Variable ' . $rule['field'] . ' has the wrong type: '
                        . gettype($data[$rule['field']]) . ' instead of ' . $rule['type'];
                    return $data;
                } else {
                    $result[$rule['field']] = trim($data[$rule['field']]);
                }
            }
        }

        return $result;
    }

    public function isAdmin()
    {
        if (empty($_SESSION['login'])) {
            return false;
        }
        $login = $_SESSION['login'];
        $model = new User_model();
        return $model->isAdmin($login);
    }
}
