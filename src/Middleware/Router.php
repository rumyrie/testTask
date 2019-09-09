<?php

namespace App\Middleware;
use App\Controllers;

class Router
{
    public static function Route(Request $request)
    {
        $method = 'main';
        if ($request->getController()) {
            $controller = $request->getController();
            $method = $request->getMethod();
        } else {
            $controller = 'Main';
        }

        $controller = self::getController($controller);

        //calls the required controller method
        call_user_func_array([$controller, $method], [$request->getParams()]);
    }

    /**
     * Returns an instance of the required controller
    */
    private static function getController(string $name)
    {
        $name = $name . '_Controller';
        $file = __DIR__ . '/../Controllers/' . $name . '.php';

        if (!require_once($file)) {
            $name = 'Main_Controller';
        }

        $name = "App\Controllers\\" . $name;
        return new $name();
    }
}
