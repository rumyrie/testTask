<?php

namespace App\Middleware;

class Database
{
    public function __construct()
    {
        return new \PDO("mysql:host=sql301.epizy.com;dbname=epiz_24443724_taskMg", 'epiz_24443724', 'ue62JTeb0Ll12E');
    }
}
