<?php
namespace app\api\controller\ew;

use app\api\model\ew\Xjxt as XjxtModel;
use app\api\controller\BaseController;

class Board extends BaseController {

    // Default display 10 piece data.
    public function inspect_data()
    {
        $inspect = new Inspect;
        return $inspect->report();
    }

    // Default display all of the data.
    public function abnormal_graph(){
        $inspect = new Statistical;
        return $inspect->abnormal();
    }

    public function get_region_data(){
        $inspect = new Statistical;
        $record = XjxtModel::select()->toArray();
        $data = [];
        for ($i = 0; $i < count($record); $i++){
            $item = [];
            for ($j = 0; $j < count($record); $j++) {
                if ($record[$i]['id'] == $record[$j]['id']){
                    if ($record[$i]['area'] == $record[$j]['area']) {
                        $item[] = $record[$j];
                    }
                    continue;
                }
                if ($record[$i]['area'] == $record[$j]['area']) {
                    $item[] = $record[$j];
                    array_splice($record,$j,1);
                    $j--;
                }
            }
            if (count($item) != 0){
                $formatData = $inspect->inspectTimes($item);
                $formatData['regionName'] = $item[0]['area'];
                $data[] = $formatData;
            }
        }
        return $this->formatResponseDate(0, '', $data);
    }

    public function get_total_times(){
        $data = (new XjxtModel())->getTotalAbnormalTimes(0, time() *1000);
        return $this->formatResponseDate(0, '', $data);
    }

}