<?php

namespace app\common\model;

use think\Model;

class ConfigModel extends Model
{
    protected $createTime = 'created_at';
    protected $updateTime = 'updated_at';
    protected $autoWriteTimestamp = 'datetime';
    protected $table = 'geek_config';
}