<?php

namespace App\Core;
require_once(__DIR__ . '/../Middleware/Database.php');
use App\Middleware\Database;

abstract class Model
{
    protected $db;

    public function __construct()
    {
        $this->db = new Database();
        $this->db = $this->db->getCon();
    }
}
