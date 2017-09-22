<?php

namespace app\common\model;

use think\Model;

class MemberModel extends Model
{
    protected $insert = ['reg_ip', 'reg_time'];
    protected $table = 'geek_member';

    protected function setRegIpAttr()
    {
        return request()->ip();
    }

    protected function setRegTimeAttr()
    {
        return time();
    }
}