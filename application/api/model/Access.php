<?php
namespace app\api\model;

use think\Db;

class Access extends BaseModel{
    protected $name = 'access';
    protected $pk = 'access_id';

    protected $tableName = "tx_access";

    public function getList($platform_id){
        return $this->where(['platform_id' => $platform_id])
            ->order('sort')
            ->select();
    }


    public function getAllMenu($platform_id){
        return $this->where(['platform_id' => $platform_id, 'type' => 0])->order('sort')->select();
    }

    public function deleteAccess($access_id)
    {
        // If the access have children, so it can't delete.
        $result = $this->where(['parent_id' => $access_id])->find();
        if ($result){
            var_dump($result);
            return false;
        }
        $record = $this->where(['access_id' => $access_id])->delete();
        if ($record == 0){
            return false;
        }
        return true;
    }
}

