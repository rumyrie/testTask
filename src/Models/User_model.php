<?php

namespace App\Models;
require(__DIR__ . '/../Core/Model.php');

use App\Core\Model;

class User_model extends Model
{
    public function __construct()
    {
        parent::__construct();
    }

    public function add($data)
    {
        $result = [
            'success' => true
        ];

        $this->db->beginTransaction();
        $query = "
            insert into `users` (`login`, `password`) values
            (:login, :password);
        ";
        $res = $this->db->prepare($query);

        if ($res->execute($data)) {
            $this->db->commit();
            return $result;
        } else {
            $this->db->rollBack();
            $result['success'] = false;
            $result['Error_Msg'] = $this->db->errorInfo();
            return $result;
        }
    }
}
