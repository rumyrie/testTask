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

        $data['session_id'] = $this->generateRandomString();
        $query = "
            insert into `sessions` (`login`, `session_id`) values
            ('{$data['login']}', '{$data['session_id']}')
        ";

        $this->db->beginTransaction();
        $res = $this->db->prepare($query);
        if ($res->execute()) {
            $this->db->commit();
            session_start();
            $_SESSION['login'] = $data['login'];
        } else {
            $this->db->rollBack();
            $result['success'] = false;
            $result['Error_Msg'] = $this->db->errorInfo();
        }

        return $result;
    }

    private function generateRandomString($length = 10) {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }

    public function logout()
    {
        $query = "
            delete from `sessions`
            where `session_id` = '" . $_COOKIE['session'] . "'
        ";

        $this->db->beginTransaction();
        $res = $this->db->prepare($query);
        if ($res->execute()) {
            $this->db->commit();
        } else {
            $this->db->rollBack();
        }

        return;
    }

    public function isAdmin($login)
    {
        $query = "
            select
                case when (coalesce(`u`.isAdmin, 0) = 1)
                    then 1
                    else null
                end as isAdmin
            from `users` u
            where u.login = '" . $login . "'
        ";
        $res = $this->db->prepare($query);
        if ($res->execute()) {
            $res = $res->fetchAll(\PDO::FETCH_ASSOC);
            if (isset($res[0]) && $res[0]['isAdmin']) {
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }
}
