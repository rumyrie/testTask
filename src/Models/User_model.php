<?php

namespace App\Models;
require_once(__DIR__ . '/../Core/Model.php');

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
        } else {
            $this->db->rollBack();
            $result['success'] = false;
            $result['Error_Msg'] = $this->db->errorInfo();
        }

        return $result;
    }

    public function checkUser($data)
    {
        $result = [
            'success' => true
        ];

        if ($data['login']) {
            $where = 'where `login` = \'' . $data['login'] . '\'';
        } else {
            $result['success'] = false;
            $result['Error_Msg'] = 'No data was passed to check if user exists';

            return $result;
        }

        $query = "
            select
                `uid`,
                `login`,
                case when `isAdmin` = 1
                    then true
                    else false
                end as isAdmin
            from `users`
            " . $where;

        $res = $this->db->prepare($query);
        if ($res->execute()) {
            $res = $res->fetchAll(\PDO::FETCH_ASSOC);
            if (isset($res[0]))
                $result['data'] = $res[0];
        } else {
            $result['success'] = false;
            $result['Error_Msg'] = $this->db->errorInfo();
        }

        return $result;
    }

    public function login($data)
    {
        $result = [
            'success' => true
        ];

        $data['session_id'] = $data['login'] . '/' . md5(md5(trim($data['password'])));
        $query = "
            insert into `sessions` (`login`, `session`) values
            (:login, :session_id)
        ";
        $res = $this->db->prepare($query);

        if ($res->execute($data)) {
            $this->db->commit();
            setcookie('session', $data['session'],  time()+60*60*24*30);
        } else {
            $this->db->rollBack();
            $result['success'] = false;
            $result['Error_Msg'] = $this->db->errorInfo();
        }

        return $result;
    }

    public function checkAuth($data)
    {
        $result = [
            'success' => true
        ];

        $query = "
            select
                1
            from `sessions`
            where `login` = :login and `session` = :session
            ";

        $res = $this->db->prepare($query);
        if ($res->execute($data)) {
            $res = $res->fetchAll(\PDO::FETCH_ASSOC);
            if (isset($res[0])) {
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }
}
