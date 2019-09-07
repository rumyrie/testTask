<?php

namespace App\Core;

abstract class Controller
{
    protected $inputRules;
    protected $model;

    public function __construct()
    {
        $this->inputRules = [];
    }

    /**
     * Checks whether errors were encountered during parsing of data
    */
    protected function ErrorData($data)
    {
        if (!$data || isset($data['Error_Msg'])) {
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
            if (is_numeric($value)) {
                $data[$key] = intval($value);
            }
        }

        if ($this->inputRules[$method]) {
            foreach ($this->inputRules[$method] as $rule) {
                if ($rule['required']) {
                    if (empty($data[$rule['field']])) {
                        return [
                            'Error_Msg' => 'Variable ' . $rule['field'] . ' is not set'
                        ];
                    }
                }
                if (isset($data[$rule['field']]) && gettype($data[$rule['field']]) != $rule['type']) {
                    return [
                        'Error_Msg' => 'Variable ' . $rule['field'] . ' has the wrong type: '
                            . gettype($data[$rule['field']]) . ' instead of ' . $rule['type']
                    ];
                } else {
                    $result[$rule['field']] = $data[$rule['field']];
                }
            }
        }

        return $result;
    }

    public function isAdmin()
    {
        return true;
    }
}
