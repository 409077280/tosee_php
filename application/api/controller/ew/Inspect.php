<?php
namespace app\api\controller\ew;

use app\api\model\ew\Xjxt as XjxtModel;
use app\api\controller\BaseController;

class Inspect extends BaseController {

    public function report(){
        $model = new XjxtModel;
        $requestData = $this->request->param();
        $data = $model->getTableList($requestData);
        return $this->formatResponseDate(0, '', $data);
    }

    public function report_detail($id){
        $model = new XjxtModel;
        $data = $model->getDetail($id);
        $data['uploadimg'] = explode("#",$data['uploadimg']);
        foreach ($data['uploadimg'] as &$value){
            $value = 'https://app.sztosee.cn/'. preg_replace('/./', '', $value, 1);
        }
        return $this->formatResponseDate(0, '', $data);
    }
}