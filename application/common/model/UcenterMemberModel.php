<?php
namespace app\common\model;

use think\Model;

class UcenterMemberModel extends Model
{
    protected $insert = ['reg_ip','reg_time','update_time'];
    protected $update = ['update_time'];
    protected $table = 'geek_ucenter_member';

    protected function setRegIpAttr($value)
    {
        return request()->ip();
    }

    protected function setRegTimeAttr($value)
    {
        return time();
    }

    protected function setUpdateTimeAttr($value)
    {
        return time();
    }
}