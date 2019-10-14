<?php
namespace app\api\controller\em;

use app\api\controller\BaseController;
use app\api\model\em\Subdevice as SubdeviceModel;

class Subdevice extends BaseController{

    public function get_list(){
        if (empty($this->request->param('deviceid'))){
            return $this->formatResponseDate(1, '未找到设备ID' , []);
        }
        $model = new SubdeviceModel;
        $requestData = $this->request->param();
        $data = $model->getSubdevices($requestData);
        return $this->formatResponseDate(0, '', $data);
    }

    public function save_subdevice(){
        if ($this->request->method() != "PUT"){
            return $this->formatResponseDate(1, '错误的请求方式' , []);
        }
        $requestData = $data = $this->request->put();
        if (empty($requestData['id'])){
            return $this->formatResponseDate(1, '未找到设备编号' , []);
        }
        $requestData['updatetime'] = time();
    }
}