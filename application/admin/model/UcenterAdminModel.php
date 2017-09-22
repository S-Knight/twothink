<?php

namespace app\admin\model;

use think\Model;

class UcenterAdminModel extends Model
{
    protected $insert = ['reg_ip', 'reg_time', 'updated_at'];
    protected $update = ['updated_at'];
    protected $table = 'geek_ucenter_admin';

    protected function setRegIpAttr()
    {
        return request()->ip();
    }

    protected function setRegTimeAttr()
    {
        return date('Y-m-d H:i:s');
    }

    protected function setUpdatedAtAttr()
    {
        return date('Y-m-d H:i:s');
    }
}