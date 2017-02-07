<?php

namespace app\admin\logic;
use think\request;
use app\admin\model\ActionLogModel;
use app\admin\model\ActionModel;
class AdminLogic extends Logic
{
    static public function addActionLog($remark = '')
    {
        $module = request()->module();
        $controller = request()->controller();
        $action = request()->action();
        $code = $module . "." . $controller . "." . $action;
        $actionRow = ActionModel::get(['code'=>$code]);
        $admin = session('admin');
        $data = [
            'uid' => $admin['id'],
            'action_id' => $actionRow['id'],
            'action_name' => $actionRow['name'],
            'moudle' => $module,
            'controller' => $controller,
            'function' => $action,
            'code' => $code,
            'remark' => $remark,
        ];
        ActionLogModel::create($data)->save();
    }
}