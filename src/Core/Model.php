<?php

namespace App\Core;
require(__DIR__ . '/../Middleware/Database.php');
use App\Middleware\Database;

abstract class Model
{
    protected $db;

    public function __construct()
    {
        $this->db = new Database();
    }
}