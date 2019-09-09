<?php
namespace App\Middleware;
require (__DIR__ . '/Request.php');
require (__DIR__ . '/Router.php');

class App
{
    /**
     */
    public function run()
    {
        $request = new Request();
        Router::Route($request);
    }
}
