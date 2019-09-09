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

        $this->db->beginTransaction();
        $query = "
            insert into `tasks` (`userName`, `email`, `text`, `status_id`) values
            (:userName, :email, :text, 0);
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
}
