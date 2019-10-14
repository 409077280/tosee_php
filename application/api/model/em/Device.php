<?php
namespace app\api\model\em;

use app\api\model\BaseModel;
use think\Db;

class Device extends BaseModel{
    protected $name = 'em_devices';
    protected $pk = 'id';

    protected $tableName = "tx_em_devices";

    /** Get devices by many Options
     * @param $info
     * @param $status
     * @param $parentid
     * @param $userid
     * @return array|\PDOStatement|string|\think\Collection
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function getDevices($requestData){
        $model = Db::table($this->tableName);
        if (isset($requestData['info']) && $requestData['info'] != ''){
            $info = $requestData['info'];
            $model->where("`title` LIKE '%{$info}%' OR `deviceid` LIKE '%{$info}%'");
        }
        if (isset($requestData['status']) && $requestData['status'] != ''){
            switch ((int)$requestData['status']){
                case 0:
                    $model->where('clientid', 'not null');
                    break;
                case 1:
                    $model->where('clientid', 'null');
                    break;
                default: ;
            }
        }
        if (isset($requestData['parentid'])){
            $model->where('parentid', '=',$requestData['parentid']);
        }
        if (isset($requestData['userid'])){
            $model->where('userid', '=',$requestData['userid']);
        }
        if (isset($requestData['limit']) && isset($requestData['page'])){
            $data = $model->paginate($requestData['limit'], false, [
                'page' => $requestData['page'],
            ]);
            return $data;
        }
        $data = $model->select();
        return $data;
    }

    /** Determine if it is greater than 0
     * @param $deviceId
     * @return bool
     */
    public function searchSameDevice($deviceId, $count = 0){
        $model = $model = Db::table($this->tableName);
        $result = $model->where('deviceid', '=', $deviceId)->count();
        if ($result > $count){
            return false;
        }
        return true;
    }

    public function deleteDevice($deviceId){
        $model = $model = Db::table($this->tableName);
        Db::startTrans();
        try {
            $model->where('deviceid', '=', $deviceId)->delete();
            (new Subdevice)->daleteSubsByDeviceId($deviceId);
            $model->commit();
            return true;
        } catch (\Exception $e) {
            $this->error = $e->getMessage();
            $model->rollback();
            return false;
        }
    }
}

