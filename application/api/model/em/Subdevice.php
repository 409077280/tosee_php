<?php
namespace app\api\model\em;

use app\api\model\BaseModel;
use think\Db;

class Subdevice extends BaseModel{
    protected $name = 'em_sub_devices';
    protected $pk = 'id';

    protected $tableName = "tx_em_sub_devices";

    public function getSubdevices($requestData){
        $model = Db::table($this->tableName);
        if (isset($requestData['info'])){
            $model->where('title', 'like',"%{$requestData['info']}%");
        }
        if (isset($requestData['status'])){
            //$model->where('clientid', '=',$requestData['status']);
        }
        if (isset($requestData['deviceid'])){
            $model->where('deviceid', '=',$requestData['deviceid']);
        }
        if (isset($requestData['userid'])){
            $model->where('userid', '=',$requestData['userid']);
        }
        if (isset($requestData['limit']) && isset($requestData['page'])){
            $data = $model->paginate($requestData['limit'], false, [
                'page' => $requestData['page'],
            ]);
            //echo $model->getLastSql();
            return $data;
        }
        return $model->select();
    }

    public function daleteSubsByDeviceId($deviceId){
        $model = Db::table($this->tableName);
        $result = $model->where('deviceid', '=', $deviceId)->delete();
        return $result;
    }

}

