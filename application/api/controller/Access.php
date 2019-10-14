<?php
namespace app\api\controller;

use app\api\model\Access as AccessModel;

class Access extends BaseController {

    public function save() {
        $model = new AccessModel();
        $data = $this->request->post();
        $data['create_time'] = time();
        $data['update_time'] = $data['create_time'];
        if (empty($data['platform_id']) || (int)$data['platform_id'] == 0){
            return $this->formatResponseDate(1, '操作不合法' , []);
        }
        if(isset($data['access_id']) && (int)$data['access_id'] != 0){
            $mod = AccessModel::get($data['access_id']);
            $result = $mod->save($data);
            if ($result){
                return $this->formatResponseDate(0, '修改成功', []);
            }
            return $this->formatResponseDate(1, '修改失败', []);
        }
        unset($data['access_id']);
        $data['update_time'] = $data['create_time'];
        $result = $model->save($data);
        if ($result){
            return $this->formatResponseDate(0, '添加成功', []);
        }
        return $this->formatResponseDate(1, '添加失败' , []);
    }

    public function delete($access_id){
        $model = new AccessModel();
        $data = $model->deleteAccess($access_id);
        if ($data){
            return $this->formatResponseDate(0, '刪除成功', []);
        }
        return $this->formatResponseDate(1, '刪除失败', []);
    }

    public function get_menus($platform_id){
        $model = new AccessModel();
        $data = $model->getAllMenu($platform_id);
        return $this->formatResponseDate(0, '', $data);
    }

    public function get_list($platform_id){
        $model = new AccessModel();
        $data = $model->getList($platform_id);
        return $this->formatResponseDate(0, '', $data);
    }

}