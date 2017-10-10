<?php

namespace app\admin\model;

use think\Model;

class MenuModel extends Model
{
    protected $createTime = 'created_at';
    protected $updateTime = 'updated_at';
    protected $autoWriteTimestamp = 'datetime';
    protected $table = 'geek_menu';
}