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

        foreach ($topMenus as $key => $topMenu) {
            $topMenus[$key]['child'] = $menuModel->where([
                'pid' => $topMenu['id'],
                'hide' => 0
            ])->order('sort')->select();
            /*
             * 遍历一下当前菜单的二级菜单，判断当前登录用户是否有二级菜单的相关权限
             * 如果没有任何二级菜单的权限，则过滤掉该一级菜单
             */
            $show = false;
            foreach ($topMenus[$key]['child'] as $ckey => $child) {
                if (checkRolePerm($child['privilege_code'],
                    session('admin')['role_id'])) {
                    //结果为真显示列表集
                    $show = true;
                } else {
                    //结果为假把对象值设置为空对象,就不会显示列表集
                    $child[$ckey] = null;
                }
            }
            if (!$show) {
                unset($topMenus[$key]);
            }
        }
        $this->assign("admin", session('admin'));
        $this->assign("title", '后台管理系统');
        $this->assign("topMenus", $topMenus);
    }

    /**
     * 判断当前用户是否登录
     * @return bool
     */
    protected function isLogin()
    {
        $user = session('admin');

        return $user ? true : false;
    }

    /**
     * 获取当前登录用户id
     * @return bool
     */
    protected function getLoginUid()
    {
        if (!$this->isLogin()) {
            return false;
        }

        return session('admin')['id'];
    }
}
