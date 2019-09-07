<?php

namespace App\Models;
require(__DIR__ . '/../Core/Model.php');

use App\Core\Model;

class Task_model extends Model
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
            insert into `tasks` (`login`, `password`) values
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

    public function edit($data)
    {
        $result = [
            'success' => true
        ];
        $params = '';
        foreach ($data as $key => $value) {
            if ($key != 'uid') {
                $params .= "`{$key}` = :{$key}
                ";
            }
        }

        $this->db->beginTransaction();
        $query = "
            update `tasks`
            set
                {$params}
            where `uid` = :uid
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

    public function delete($data)
    {
        $result = [
            'success' => true
        ];

        $this->db->beginTransaction();
        $query = "
            delete from `tasks`
            where `uid` = :uid
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
