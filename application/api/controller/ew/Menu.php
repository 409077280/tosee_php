<?php
namespace app\api\controller\ew;

use app\api\model\Menu as MenuModel;
use app\api\controller\BaseController;

class Menu extends BaseController {

    public function save() {
        $model = new MenuModel();
        $data = $this->request->post();
        $data['create_time'] = time();
        $data['update_time'] = $data['create_time'];
        if(isset($data['menu_id']) && $data['menu_id'] != 0){
            $mod = MenuModel::get($data['menu_id']);
            $result = $mod->save($data);
            if ($result){
                return $this->formatResponseDate(0, '修改成功', []);
            }
            return $this->formatResponseDate(1, '修改失败', []);
        }
        unset($data['menu_id']);
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

    public function get_list($platform_id){
        $model = new MenuModel();
        $data = $model->getAll($platform_id);
        return $this->formatResponseDate(0, '', $data);
    }

}