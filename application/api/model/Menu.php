<?php
namespace app\api\model;

class Menu extends BaseModel{
    protected $name = 'menu';
    protected $pk = 'menu_id';

    public function addPlatform(){

    }

    public function deletePlatform(){

    }

    public function updatePlatform(){

    }

    public function getAll($platform_id){
        $allMenu = $this->where(['platform_id' => $platform_id])->select();
        return $allMenu;
    }


}

