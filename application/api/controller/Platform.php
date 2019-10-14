<?php
namespace app\api\controller;

use app\api\model\Platform as PlatformModel;
use think\App;

class Platform extends BaseController {
    public function __construct(App $app = null)
    {
        parent::__construct($app);
        // TODO:: check super manager.
    }

    public function save() {
        $model = new PlatformModel;
        $data = $this->request->post();
        $data['create_time'] = time();
        $data['update_time'] = $data['create_time'];
        if(isset($data['platform_id']) && $data['platform_id'] != 0){
            $mod = PlatformModel::get($data['platform_id']);
            $result = $mod->save($data);
            if ($result){
                return $this->formatResponseDate(0, '修改成功', []);
            }
            return $this->formatResponseDate(1, '修改失败', []);
        }
        unset($data['platform_id']);
        $data['update_time'] = $data['create_time'];
        $result = $model->save($data);
        if ($result){
            return $this->formatResponseDate(0, '添加成功', []);
        }
        return $this->formatResponseDate(1, '添加失败' , []);
    }

    public function delete(){

    }

    public function get(){

    }

    public function get_list($page, $limit){
        $model = new PlatformModel;
        $data = $model->getList($page, $limit);
        return $this->formatResponseDate(0, '', $data);
    }

    public function get_platforms(){
        $model = new PlatformModel;
        $data = $model->getAll();
        return $this->formatResponseDate(0, '', $data);
    }
}