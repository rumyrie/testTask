<?php

namespace App\Models;
require_once(__DIR__ . '/../Core/Model.php');

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

        $query = "
            insert into `tasks` (`userName`, `email`, `text`, `status_id`) values
            (:userName, :email, :text, 0);
        ";

        $this->db->beginTransaction();
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

    public function saveEdited($data)
    {
        $result = [
            'success' => true
        ];
        $data['status_id'] = ($data['status_id'] == 0) ? 2 : 3;

        $query = "
            update `tasks`
            set
                `username` = '" . $data['username'] . "',
                `email` = '" . $data['email'] . "',
                `text` = '" . $data['text'] . "',
                `status_id` = '" . $data['status_id'] . "'
            where `uid` = " . $data['uid'];

        $this->db->beginTransaction();
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

    public function getList($data = [])
    {
        $result = [
            'success' => true,
            'data' => []
        ];

        $params['offset'] = 0;

        if (isset($data['page'])) {
            $params['offset'] = 3 * $data['page'];
        }

        $order = '';
        if (isset($data['order'])) {
            $order = $this->prepareOrderPart($data);
        }

        $query = "
            select
                `t`.`uid`,
                `t`.`userName`,
                `t`.`email`,
                `t`.`text`,
                `s`.`status_text`  
            from `tasks` t
                left join `statuses` s on s.`status_id` = t.`status_id`
            {$order}
            limit 3 offset {$params['offset']}
        ";
        $res = $this->db->prepare($query);
        if ($res->execute()) {
            $res = $res->fetchAll(\PDO::FETCH_ASSOC);
            if (empty($res)) {
                $result['success'] = false;
            } else {
                $result['data'] = $res;
            }
        } else {
            $result['success'] = false;
            $result['Error_Msg'] = $this->db->errorInfo();
        }

        return $result;
    }

    private function prepareOrderPart($data)
    {
        $parts = explode('/', $data);
        switch ($parts[0]) {
            case 'userName':
                $order = "order by
                `userName` {$parts[1]}";
                break;
            case 'email':
                $order = "order by
                `email` {$parts[1]}";
                break;
            case 'status':
                $order = "order by
                case when (s.`status_id` in (0, 3))
                    then 1
                    else 0
                end {$parts[1]}";
                break;
            default:
                $order = "order by
                `uid` desc";
                break;
        }

        return $order;
    }

    public function get($data)
    {
        $query = "
            select
                `uid`,
                `userName`,
                `email`,
                `text`,
                case when (`status_id` in (0,2))
                    then 'new'
                    else 'completed'
                end as status_id
            from `tasks`
            where `uid` = " . $data['id'];

        $res = $this->db->prepare($query);
        if ($res->execute()) {
            $res = $res->fetchAll(\PDO::FETCH_ASSOC);
            if (empty($res)) {
                $result['success'] = false;
            } else {
                $result['data'] = $res;
            }
        } else {
            $result['success'] = false;
            $result['Error_Msg'] = $this->db->errorInfo();
        }

        return $result;
    }
}
