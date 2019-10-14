<?php
namespace app\api\model\ew;

use app\api\model\BaseModel;
use think\Db;

class Xjxt extends BaseModel{
    protected $name = 'xjxt_content_list';
    protected $pk = 'id';

    protected $tableName = "tx_xjxt_content_list";

    public function getTableList($requestData){
        $model = Db::table($this->tableName)->alias('xjxt');
        if(isset($requestData['userid'])){
            $model->where(['xjxt.userid' => $requestData['userid']]);
        }
        if(isset($requestData['type']) && isset($requestData['start_time']) && isset($requestData['end_time'])){
            switch ($requestData['type']){
                case 'lhx_voltage':
                    $model->whereNotBetween( "xjxt.{$requestData['type']}", '198, 236'); break;
                case 'dhx_voltage':
                    $model->whereNotBetween( "xjxt.{$requestData['type']}", '198, 236'); break;
                case 'ldx_voltage':
                    $model->where( "xjxt.{$requestData['type']}", '>=', 5); break;
                case 'lhxx':
                    $model->where( "xjxt.{$requestData['type']}", '=', 1); break;
                case 'gnd':
                    $model->where( "xjxt.{$requestData['type']}", '=', 1); break;
                case 'leakage':
                    $model->where( "xjxt.{$requestData['type']}", '>=', 5); break;
                default: ;
            }
            $startTime = (int)($requestData['start_time'] / 1000);
            $endTime = (int)($requestData['end_time'] / 1000);
            $model->whereBetween( "xjxt.updatetime", "{$startTime}, {$endTime}");
        }
            $data = $model
                ->field([
                    'xjxt.*',
                    'user.nickname',
                ])
                ->join('tx_xjxt_user user', 'xjxt.userid = user.id', 'LEFT')
                ->order('xjxt.updatetime', 'DESC')->paginate($requestData['limit'], false, [
                    'page' => $requestData['page'],
                ]);
        //var_dump($model->getLastSql());
        return $data;

    }

    public function getDetail($id){
        return $model = Db::table($this->tableName)
            ->alias('xjxt')
            ->field([
                'xjxt.*',
                'user.nickname',
                'user.gongdan',
            ])
            ->join('tx_xjxt_user user', 'xjxt.userid = user.id', 'LEFT')
            ->where(['xjxt.id' => $id])
            ->find();
    }

    // Get abnormal times of users.
    public function getAbnormalList($userid){
        $data = $this->where(['userid' => $userid])->select();
        return $data;
    }

    // Get data for a while.
    public function getDataBytime($start_time, $end_time){
        $start_time = $start_time / 1000;
        $end_time = $end_time / 1000;
        $data = $this->whereBetween('updatetime',"{$start_time}, {$end_time}")->select();
        return $data;
    }

    public function getNewDataOfUser($limit = 100){
        $data = $this->field('userid')
            ->order('id', 'desc')
            ->limit($limit)
            ->select();
        return $data;
    }

    public function getTotalAbnormalTimes($startTime, $endTime){
        if (!isset($startTime) || !isset($endTime)){
            $startTime = 0;
            $endTime = time();
        }
        $startTime = $startTime / 1000;
        $endTime = $endTime / 1000;
        $total = self::whereBetween('updatetime',"{$startTime}, {$endTime}")
            ->count(0);
        $normal = self::whereBetween('lhx_voltage','198, 236')
            ->whereBetween('dhx_voltage','198, 236')
            ->where('ldx_voltage', '<', '5')
            ->where('lhxx', '=', '0')
            ->where('gnd','=', '0')
            ->where('leakage', '<', '5')
            ->whereBetween('updatetime',"{$startTime}, {$endTime}")
            ->count();
        $abnormal = $total - $normal;
        $abnormalPercent = 0;
        if ($total){
            $abnormalPercent = number_format($abnormal / $total  * 100, 2);
        }
        return [
            'total' => $total,
            'normal'=> $normal,
            'abnormal' =>  $abnormal,
            'abnormalPercent' => $abnormalPercent,
        ];
    }
}

