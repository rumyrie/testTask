<?php

namespace App\Middleware;

class Request
{
    private $controller = null;
    private $method = null;
    private $params = [];

    public function __construct()
    {
        $requestURI = $_SERVER['REQUEST_URI'];
        $request = $this->parseRequest($requestURI);

        $this->controller = $request['controller'];
        $this->method = $request['method'];

        switch ($_SERVER['REQUEST_METHOD']) {
            case 'POST' :
                $this->params = $_POST;
                break;
            case 'GET' :
                $data = $this->prepareData($requestURI);
                $this->params = $data;
                break;
        }
    }

    private function parseRequest(string $url)
    {
        $request = [];
        $urlParts = explode('/', $url);

        //in case of GET requests data is passed as url params
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            $urlParts[2] = explode('?', $urlParts[2])[0];
        }

        $request['controller'] = isset($urlParts[1]) ? $urlParts[1] : null;
        $request['method'] = isset($urlParts[2]) ? $urlParts[2] : null;

        return $request;
    }

    public function prepareData(string $url)
    {
        $result = [];
        $data = explode('/', $url);
        if (isset($data[3])) {
            $result['page'] = $data[3];
        }
        if (isset($data[4])) {
            $result['order'] = $data[4];
        }

        return $result;
    }

    public function getController()
    {
        return $this->controller;
    }

    public function getMethod()
    {
        return $this->method;
    }

    public function getParams()
    {
        return $this->params;
    }
}
