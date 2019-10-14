<?php
namespace app\api\controller\ew;

use app\api\model\ew\Xjxt as XjxtModel;
use app\api\controller\BaseController;

class Index extends BaseController {

    // Default display 2 days of the data.
    public function abnormal_history($date_type = 'day') {
        $now = [
            'start' => 0,
            'end' => 0,
        ];
        $last = $now;
        switch ($date_type){
            case 'day':
                $now['start'] = strtotime(date("Y-m-d",time())) * 1000;
                $now['end'] = time() * 1000;
                $last['start'] = (strtotime(date("Y-m-d",time())) - 3600 * 24) * 1000;
                $last['end'] = strtotime(date("Y-m-d",time())) * 1000;
                break;
            case 'week':
                //TODO:
                break;
            case 'month':
                //TODO:
                ;break;
        }
        $model = new XjxtModel;
        $dataNow = $model->getTotalAbnormalTimes($now['start'], $now['end']);
        $dataLast = $model->getTotalAbnormalTimes($last['start'], $last['end']);
        $changePercent = 0;
        $changeType = 'none';
        if($dataNow['abnormalPercent'] > $dataLast['abnormalPercent']){
            $tem = $dataNow['abnormalPercent'] - $dataLast['abnormalPercent'];
            if ($dataLast['abnormalPercent'] != 0){
                $changePercent = number_format($tem / $dataLast['abnormalPercent'] * 100, 2);
            }
            $changeType = 'up';
        } elseif ($dataNow['abnormalPercent'] < $dataLast['abnormalPercent']){
            $tem = $dataLast['abnormalPercent'] - $dataNow['abnormalPercent'];
            if ($dataLast['abnormalPercent'] != 0){
                $changePercent = number_format($tem / $dataLast['abnormalPercent'] * 100, 2);
            }
            $changeType = 'down';
        }
        $data = [
            'now' => $dataNow,
            'last'=> $dataLast,
            'changePercent' => $changePercent,
            'changeType' => $changeType,
        ];
        return $this->formatResponseDate(0, '', $data);
    }

    // Default display 10 piece data.
    public function inspect_graph()
    {
        $inspect = new Statistical;
        return $inspect->inspector_grap();
    }

    // Default display all of the data.
    public function abnormal_graph(){
        $inspect = new Statistical;
        return $inspect->abnormal();
    }

}