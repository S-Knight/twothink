<?php
namespace app\admin\controller;

use app\admin\model\MenuModel;
use think\Controller;

abstract class Admin extends Controller
{
    protected $view = null;

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
