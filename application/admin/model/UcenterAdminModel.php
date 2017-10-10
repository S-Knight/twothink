<?php

namespace app\admin\model;

use think\Model;

class UcenterAdminModel extends Model
{
    protected $insert = ['reg_ip'];
    protected $createTime = 'reg_time';
    protected $updateTime = 'updated_at';
    protected $autoWriteTimestamp = 'datetime';

    protected $table = 'geek_ucenter_admin';

    protected function setRegIpAttr()
    {
        return request()->ip();
    }

}