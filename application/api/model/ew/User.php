<?php
namespace app\api\model\ew;

use app\api\model\BaseModel;
use think\Db;

/* 检查员表 */
class User extends BaseModel{
    protected $name = 'xjxt_user';
    protected $pk = 'id';
    protected $tableName = "tx_xjxt_user";

    public function tableData($page, $limit, $user_info)
    {
        $model = Db::table($this->tableName);
        if ($user_info){
            $model->where('nickname', 'like', "%$user_info%");
            $model->whereOr('gongdan', 'like', "%$user_info%");
        }
        $data = $model
            ->order('id', 'DESC')
            ->paginate($limit, false, [
            'page' => $page,
        ]);
        //echo $model->getLastSql();
        return $data;
    }

    public function getUsersById($userIds){
        $data =  $this->whereIn('id', $userIds)->select();
        //echo $this->getLastSql();
        return $data;
    }


}

