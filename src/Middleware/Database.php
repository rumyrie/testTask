<?php

namespace App\Middleware;
use PDO;

class Database
{
    private $con;

    public function __construct()
    {

    }

    public function getCon()
    {
        try {
            $this->con = new PDO("");
            $this->con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->con->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        } catch (\PDOException $e) {
            echo $e->getMessage();
        }

        return $this->con;
    }
}
