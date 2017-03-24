<?php
namespace app\admin\model;

use think\Model;

class SystemModel extends Model
{
    protected $insert = ['created_at'];
    protected $update = ['updated_at'];
    protected $table = 'geek_config';

    protected function setCreatedAtAttr($value)
    {
        return date('Y-m-d H:i:s');
    }

    protected function setUpdatedAtAttr($value)
    {
        return date('Y-m-d H:i:s');
    }
}