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
        $data['status_id'] = ($data['status_id'] == "'0'") ? 2 : 1;


        $query = "
            update tasks
            set
                userName = '" . $data['userName'] . "',
                email = '" . $data['email'] . "',
                text = '" . $data['text'] . "',
                status_id = " . $data['status_id'] . "
            where `tasks`.`uid` = " . $data['uid'];

        $this->db->beginTransaction();
        $res = $this->db->prepare($query);
        if ($res->execute()) {
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

        if (!empty($data['page'])) {
            $params['offset'] = 3 * ($data['page'] - 1);
        }

        $order = '';
        if (!empty($data['order'])) {
            $order = $this->prepareOrderPart($data['order']);
        }

        $query = "
            select
                count(*) as total
            from `tasks`
        ";

        $total = $this->db->prepare($query);
        $total->execute();
        $total = $total->fetchAll(\PDO::FETCH_ASSOC);

        if (!isset($total[0]) || empty($total[0]['total'])) {
            $result['success'] = false;
            return $result;
        } else {
            $total = $total[0]['total'];
            $i = $total / 3;
            if ($total % 3)
                $i++;
            $total = $i;
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
                $result['total'] = $total;
                $result['order'] = $data['order'];
                $result['page'] = $data['page'];
            }
        } else {
            $result['success'] = false;
            $result['Error_Msg'] = $this->db->errorInfo();
        }

        return $result;
    }

    private function prepareOrderPart($data)
    {
        $parts = explode('_', $data);
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
                s.`status_id` {$parts[1]}";
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
                case when (`status_id` in (0,1))
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

    public function delete($data)
    {
        $result = [
            'success' => true
        ];

        $query = "
            delete from `tasks`
            where `uid` = " . $data['id'];

        $this->db->beginTransaction();
        $res = $this->db->prepare($query);
        if ($res->execute()) {
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
