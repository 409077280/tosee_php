<?php
namespace app\api\model;

class Platform extends BaseModel{
    protected $name = 'platform';
    protected $pk = 'platform_id';

    public function addPlatform(){

    }

    public function deletePlatform(){

    }

    public function updatePlatform(){

    }

    public function getList($page, $limit){
        return $this->paginate($limit, false, [
            'page' => $page,
        ]);
    }

    public function getAll(){
        return $this->where(['status' => 1])->select();
    }
}

