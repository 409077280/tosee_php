<?php
namespace app\api\model;


/* 后台用户表 */
class Manager extends BaseModel{
    protected $name = 'manager';
    protected $pk = 'manager_id';

    public function addUser(){

    }

    public function deleteUser(){

    }

    public function updateUser(){

    }

    public function getInfo($moblie, $password){
        $password = md5(md5($password).'tosee');
        return $this->where(['mobile' => $moblie, 'password' => $password])->find();
    }


}

