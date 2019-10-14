<?php

namespace app\api\controller\ew;

use app\api\model\ew\Xjxt as XjxtModel;
use app\api\model\ew\User as UserModel;
use app\api\controller\BaseController;

class Statistical extends BaseController
{

    public function abnormal()
    {
        $request = $this->request->param();
        $start_time = 0;
        $end_time = time() * 1000;
        if (isset($request['start_time'])) {
            $start_time = $request['start_time'];
        }
        if (isset($request['end_time'])) {
            $end_time = $request['end_time'];
        }
        $model = new XjxtModel;
        $timeData = $model->getDataBytime($start_time, $end_time);
        $data = $this->abnormalTypeTimes($timeData);
        return $this->formatResponseDate(0, '', $data);
    }

    public function inspector($page = 1, $limit = 10, $user_info = null)
    {
        $userModel = new UserModel;
        $model = new XjxtModel;
        $data = $userModel->tableData($page, $limit, $user_info)->toArray();
        foreach ($data['data'] as &$value) {
            $xjxtData = $model->getAbnormalList($value['id']);
            $value['recordTimes'] = $this->inspectTimes($xjxtData);
        }
        return $this->formatResponseDate(0, '', $data);
    }

    public function inspector_grap($page = 1, $limit = 10)
    {
        $userModel = new UserModel;
        $model = new XjxtModel;
        $users = $model->getNewDataOfUser()->toArray();
        if (!isset($users)){
            return $this->formatResponseDate(1, '暂无可用数据', []);
        }
        $userIds = [];
        foreach ($users as $user){
            $userIds[] = $user['userid'];
        }
        $userIds = array_unique($userIds);
        $userIds = array_slice($userIds, 0, 10);
        $usersInfo = $userModel->getUsersById($userIds)->toArray();
        foreach ($usersInfo as &$value) {
            $xjxtData = $model->getAbnormalList($value['id']);
//            if($value['id'] =='7'){
//                var_dump($xjxtData);
//                die();
//            }

            $value['recordTimes'] = $this->inspectTimes($xjxtData);
        }
        return $this->formatResponseDate(0, '', $usersInfo);
    }

    public function inspectTimes($xjxtData)
    {
        $totalTimes = 0;
        $normalTimes = 0;
        $abnormalTimes = 0;
        $abnormalPercent = 0;
        if (isset($xjxtData)){
            foreach ($xjxtData as $value) {
                if (isset($value['lhx_voltage']) && $value['lhx_voltage'] > 198 && $value['lhx_voltage'] < 236) {
                    $normalTimes++;
                } else {
                    $abnormalTimes++;
                }
                $totalTimes++;
                if (isset($value['dhx_voltage']) && $value['dhx_voltage'] > 198 && $value['dhx_voltage'] < 236) {
                    $normalTimes++;
                } else {
                    $abnormalTimes++;
                }
                $totalTimes++;
                if (isset($value['ldx_voltage']) && $value['ldx_voltage'] < 5) {
                    $normalTimes++;
                } else {
                    $abnormalTimes++;
                }
                $totalTimes++;
                if (isset($value['lhxx']) && $value['lhxx'] == 0) {
                    $normalTimes++;
                } else {
                    $abnormalTimes++;
                }
                $totalTimes++;
                if (isset($value['gnd']) && $value['gnd'] == 0) {
                    $normalTimes++;
                } else {
                    $abnormalTimes++;
                }
                $totalTimes++;
                if (isset($value['leakage']) && $value['leakage'] < 5) {
                    $normalTimes++;
                } else {
                    $abnormalTimes++;
                }
                $totalTimes++;
            }
        }
        if ($totalTimes != 0) {
            $abnormalPercent = number_format($abnormalTimes / $totalTimes  * 100, 2);
        }
        return [
            'totalTimes' => $totalTimes,
            'normalTimes' => $normalTimes,
            'abnormalTimes' => $abnormalTimes,
            'abnormalPercent' => $abnormalPercent,
        ];
    }

    public function abnormalTypeTimes($xjxtData)
    {
        $lhx_voltage = $this->typeStatisticalFormat('lhx_voltage', '零火线电压');
        $ldx_voltage = $this->typeStatisticalFormat('ldx_voltage','零地线电压');
        $dhx_voltage = $this->typeStatisticalFormat('dhx_voltage','地火线电压');
        $lhxx = $this->typeStatisticalFormat('lhxx','零火线序');
        $gnd = $this->typeStatisticalFormat('gnd','地线接地');
        $power = $this->typeStatisticalFormat('power','功率');
        $current = $this->typeStatisticalFormat('current','电流');
        $leakage = $this->typeStatisticalFormat('leakage','漏电电流');
        $temp = $this->typeStatisticalFormat('temp','温度');
        foreach ($xjxtData as $value) {
            if (isset($value['lhx_voltage']) && $value['lhx_voltage'] > 198 && $value['lhx_voltage'] < 236) {
                $lhx_voltage['normal_times']++;
            } else {
                $lhx_voltage['abnomal_times']++;
            }
            $lhx_voltage['total_times']++;
            if (isset($value['dhx_voltage']) && $value['dhx_voltage'] > 198 && $value['dhx_voltage'] < 236) {
                $dhx_voltage['normal_times']++;
            } else {
                $dhx_voltage['abnomal_times']++;
            }
            $dhx_voltage['total_times']++;
            if (isset($value['ldx_voltage']) && $value['ldx_voltage'] < 5) {
                $ldx_voltage['normal_times']++;
            } else {
                $ldx_voltage['abnomal_times']++;
            }
            $ldx_voltage['total_times']++;
            if (isset($value['lhxx']) && $value['lhxx'] == 0) {
                $lhxx['normal_times']++;
            } else {
                $lhxx['abnomal_times']++;
            }
            $lhxx['total_times']++;
            if (isset($value['gnd']) && $value['gnd'] == 0) {
                $gnd['normal_times']++;
            } else {
                $gnd['abnomal_times']++;
            }
            $gnd['total_times']++;
            if (isset($value['leakage']) && $value['leakage'] < 5) {
                $leakage['normal_times']++;
            } else {
                $leakage['abnomal_times']++;
            }
            $leakage['total_times']++;
        }
        $lhx_voltage['abnormal_percent'] = $this->abnormalPercent($lhx_voltage);
        $ldx_voltage['abnormal_percent'] = $this->abnormalPercent($ldx_voltage);
        $dhx_voltage['abnormal_percent'] = $this->abnormalPercent($dhx_voltage);
        $lhxx['abnormal_percent'] = $this->abnormalPercent($lhxx);
        $gnd['abnormal_percent'] = $this->abnormalPercent($gnd);
        $leakage['abnormal_percent'] = $this->abnormalPercent($leakage);
        $temp['total_times'] = $current['total_times'] = $power['total_times'] = count($xjxtData);
        $temp['normal_times'] = $current['normal_times'] = $power['normal_times'] = count($xjxtData);
        return [
            $lhx_voltage,
            $ldx_voltage,
            $dhx_voltage,
            $lhxx,
            $gnd,
            $power,
            $current,
            $leakage,
            $temp,
        ];
    }

    private function typeStatisticalFormat($type, $type_name)
    {
        return [
            'type' => $type,
            'type_name' => $type_name,
            'total_times' => 0,
            'normal_times' => 0,
            'abnomal_times' => 0,
            'abnormal_percent' => 0,
        ];
    }

    private function abnormalPercent($data){
        if ($data['total_times'] != 0) {
            return number_format($data['abnomal_times'] / $data['total_times']  * 100, 2);
        }
        return 0;
    }
}
