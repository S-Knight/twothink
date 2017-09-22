<?php

namespace app\common\model;

use think\Model;

class ConfigModel extends Model
{
    protected $insert = ['created_at'];
    protected $update = ['updated_at'];
    protected $table = 'geek_config';

    protected function setCreatedAtAttr()
    {
        return date('Y-m-d H:i:s');
    }

    protected function setUpdatedAtAttr()
    {
        return date('Y-m-d H:i:s');
    }
}