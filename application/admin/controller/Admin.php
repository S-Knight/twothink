<?php

namespace app\admin\controller;

use app\admin\logic\PrivilegeLogic;
use app\admin\model\MenuModel;
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
        if (!PrivilegeLogic::checkRolePerm($code, $admin['role_id'])) {
            $this->error('您没有权限进行此操作');
        }
    }

    /**
     *写后台操作日志
     */
    protected function writeActionLog()
    {
        AdminLogic::addActionLog();
    }

    protected function _initialize()
    {
        if (!$this->isLogin()) {
            $this->redirect('Account/login');
        }

        $menuModel = new MenuModel();
        $topMenus = $menuModel->where([
            'pid' => 0,
            'hide' => 0
        ])->order('sort')->select();
        foreach ($topMenus as $topMenu) {
            $topMenu['child'] = $menuModel->where([
                'pid' => $topMenu['id'],
                'hide' => 0
            ])->order('sort')->select();
        }

        $this->assign("admin", session('admin'));
        $this->assign("title", '后台管理系统');
        $this->assign("topMenus", $topMenus);
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
