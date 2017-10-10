<?php

namespace app\admin\model;

use think\Model;

class ActionLogModel extends Model
{
    protected $createTime = 'created_at';
    protected $updateTime = 'updated_at';
    protected $autoWriteTimestamp = 'datetime';

    protected $table = 'geek_action_log';
}