<?php
namespace app\api\controller\em;

use app\api\model\em\Device as DeviceModel;
use app\api\controller\BaseController;

class Device extends BaseController {

    public function devices_list(){
        $model = new DeviceModel;
        $requestData = $this->request->param();
        $data = $model->getDevices($requestData);
        return $this->formatResponseDate(0, '', $data);
    }

    public function save_device(){
        $model = new DeviceModel();
        if ($this->request->method() != "POST"){
            return $this->formatResponseDate(1, '错误的请求方式' , []);
        }
        $data = $this->request->post();
        if (empty($data['deviceid'])){
            return $this->formatResponseDate(1, '设备ID未填写' , []);
        }
        // Assert id is exist or none.
        if(isset($data['id']) && (int)$data['id'] != 0){
            //  update
            $mod = DeviceModel::get($data['id']);
            // Assert same device id.
            if ($mod['deviceid'] == $data['deviceid']){
                if ($model->searchSameDevice($data['deviceid'], 1) == false){
                    return $this->formatResponseDate(1, '当前设备已存在' , []);
                }
            } else {
                if ($model->searchSameDevice($data['deviceid']) == false){
                    return $this->formatResponseDate(1, '当前设备已存在' , []);
                }
            }
            $data['updatetime'] = time();
            $result = $mod->save($data);
            if ($result){
                return $this->formatResponseDate(0, '修改成功', []);
            }
            return $this->formatResponseDate(1, '修改失败', []);
        }
        // insert
        if ($model->searchSameDevice($data['deviceid']) == false){
            return $this->formatResponseDate(1, '设备id已存在' , []);
        }
        unset($data['id']);
        $data['createtime'] = time();
        $data['updatetime'] = $data['createtime'];
        $result = $model->save($data);
        if ($result){
            return $this->formatResponseDate(0, '添加成功', []);
        }
        return $this->formatResponseDate(1, '添加失败' , []);
    }

    public function dalete_device(){
        if ($this->request->method() != "DELETE"){
            return $this->formatResponseDate(1, '错误的请求方式' , []);
        }
        $model = new DeviceModel();
        if (empty($this->request->param('id'))){
            return $this->formatResponseDate(1, '没有设备编号' , []);
        }
        $id = $this->request->param('id');
        $row = DeviceModel::get($id);
        $result = $model->deleteDevice($row['deviceid']);
        if ($result){
            return $this->formatResponseDate(0, '删除成功', []);
        }
        return $this->formatResponseDate(1, '删除失败: '. $model->getError() , []);
    }



}