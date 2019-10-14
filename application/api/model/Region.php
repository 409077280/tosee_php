<?php
namespace app\api\model;

class Region extends BaseModel{
    protected $name = 'region';
    protected $pk = 'platform_id';

    public function getProvinces(){
        return $this->where(['level' => 1])->select();
    }

    public function getCitys($pid){
        return $this->where(['pid' => $pid])->select();
    }

    public function getRegions($pid){
        return $this->where(['pid' => $pid])->select();
    }

}

