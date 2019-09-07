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
        //$x = [
        //    $request->getController(),
        //    $request->getMethod(),
        //    $request->getParams(),
        //    $_GET,
        //    $_POST
        //];
        //file_put_contents('1.log', json_encode($x));
        Router::Route($request);
    }
}
