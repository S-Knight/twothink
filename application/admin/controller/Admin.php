<?php
namespace app\admin\controller;

use app\admin\model\MenuModel;
use app\admin\model\AdminRoleModel;
use app\admin\logic\AdminLogic;
use think\Controller;

abstract class Admin extends Controller
{
    protected $view = null;
    protected $beforeActionList = ['pervilegeVilidate', 'writeActionLog'];

    protected function pervilegeVilidate()
    {
        $module = $this->request->module();
        $controller = $this->request->controller();
        $action = $this->request->action();
        $code = $module . "." . $controller . "." . $action;
        $admin = session('admin');
        $perms = AdminRoleModel::where('id', $admin['role_id'])->value('perms');
        $permsArr = explode(',', $perms);
        if (!in_array($code, $permsArr)) {
            $this->error('您没有权限进行此操作');
        }
        
    }

    protected function writeActionLog()
    {
        //写后台操作日志
        AdminLogic::addActionLog();
    }

    protected function _initialize()
    {
        if (!$this->isLogin()) {
            $this->redirect('Account/login');
        }

        $topMenus = MenuModel::all([
            'pid' => 0,
            'hide' => 0
        ]);
        foreach ($topMenus as $topMenu) {
            $topMenu['child'] = MenuModel::all([
                'pid' => $topMenu['id'],
                'hide' => 0
            ]);
        }

        $this->assign("admin", session('admin'));
        $this->assign("topMenus", $topMenus);
        $this->assign("template", getConfig('TEMPLATE_NUM'));
    }

    /**
     * 判断当前用户是否登录
     * @return bool
     */
    private function isLogin()
    {
        $user = session('admin');

        return $user ? true : false;
    }
}
