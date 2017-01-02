<?php
namespace app\admin\controller;

use app\admin\model\Menu;
use think\Controller;
use think\Session;
use think\db;

class Admin extends Controller{
    protected $view = NULL;

    public function _initialize() {
        $this->isLogin();

        $menuModel = new \app\admin\model\Menu();
        $parentMenus = $menuModel->where('hide', 0)
            ->where('pid', 0)
            ->order('sort')
            ->select();

        $menus = [];
        foreach ($parentMenus as $parentMenu) {
            $menu = [];
            $menu['title'] = $parentMenu->title;
            $menu['id'] = $parentMenu->id;

            $menu['childMenus'] = [];
            $childMenus = $menuModel->where(['hide' => 0])
                ->where('pid', $parentMenu->id)
                ->order('sort')
                ->select();

            foreach ($childMenus as $childMenu) {
                $menu['childMenus'][] = $childMenu->toArray();
            }

            $menu['childMenus'] = $childMenus;

            $menus[] = $menu;
        }

        //顶部菜单
        $menu_top = Db::table('geek_menu')
            ->where(['hide' => 0])
            ->order('sort')
            ->select();

        //基本信息
        $row['copyright'] = Db::table('geek_config')
            ->where('name', 'Copyright_information')
            ->value('value');
        $row['alias'] = Db::table('geek_config')
            ->where('name', 'alias')
            ->value('value');
        $this->view = new \think\View();
        $this->view->assign("row", $row);
        $this->view->assign("menu_top", $menu_top);

        $this->view->assign("menus", $menus);
        $this->view->assign("username", session("user"));
    }

    /**
     *判断是否登录
     */
    public function isLogin() {
        $user = Session::get('user');
        if (!$user) {
            $this->redirect('Account/login');
        }
    }

    public function menuDisplay() {
        $menuModel = new Menu();

        $firstMenus = $menuModel->where(['pid' => 0, 'hid' => 0])
            ->order('sort')
            ->select();

        foreach ($firstMenus as &$firstMenu) {
            $secondMenus = $menuModel->where([
                'pid' => $firstMenu['id'],
                'hid' => 0
            ])->order('sort')->select();

            foreach ($secondMenus as &$secondMenu) {
                $thirdMenus = $menuModel->where([
                    'pid' => $secondMenu['id'],
                    'hid' => 0
                ])->order('sort')->select();
                $secondMenu['childmenu'] = $thirdMenus;
            }
            $firstMenu['childmenu'] = $secondMenus;
        }
    }

    public function file($filename) {
        $file = request()->file($filename);
        if (!empty($file)) {
            $info = $file->move(ROOT_PATH . 'public' . DS . 'uploads');
            if ($info) {
                return $row = '/uploads/' . ($info->getSaveName());
            } else {
                $this->error($file->getError());
            }
        }
    }
}
