<?php
namespace app\api\model\em;

use app\api\model\BaseModel;
use think\Db;

class User extends BaseModel{
    protected $name = 'xjxt_content_list';
    protected $pk = 'id';

    protected $tableName = "tx_xjxt_content_list";
}

