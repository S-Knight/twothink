<?php
namespace app\common\logic;

class Logic{
        protected function addOpentime($startTime,$endTime,$serial)
        {
            $fcsdOpentimeModel = new \app\common\model\BjscpksLotteryOpentime();
            $startTime = '2016-12-21 09:07:30';
            $endTime = '2016-12-21 09:12:29';
            $serial = 593194;
            $res = [];
            for($i=0;$i<178;$i++)
            {
                $data['serial'] = $serial + $i;
                $data['start_time'] = date("Y-m-d H:i:s",strtotime($startTime) + ($i) * 5 * 60 );
                $data['end_time'] = date("Y-m-d H:i:s",strtotime($endTime ) + ($i) * 5 * 60 );
                $data['created_at'] = date('Y-m-d H:i:s');
                $res[] = $data;
            }
            $fcsdOpentimeModel->insertAll($res);
        }


    protected function addconfig($startTime,$endTime,$serial)
    {
        $configModel = new \app\common\model\config();
        $res = [];
        for($i=0;$i<22;$i++)
        {
            $data['agent_id'] = 1;
            $data['title'] = "六合彩生肖尾数赔率";
            $data['name'] = 'LHCBB_HONGDAN'.($i);
            $data['value'] = 2;
            $data['type'] = 1;
            $data['group'] = 37;
            $data['sort'] = 1;
            $data['created_at'] = date('Y-m-d H:i:s');
            $res[] = $data;
        }
        $configModel->insertAll($res);
    }
}