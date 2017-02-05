<?php
namespace app\admin\controller;

use app\admin\model\MenuModel;
use app\admin\model\UcenterAdminModel;
use think\Controller;
use think\Session;
use think\db;

class Admin extends Controller{
    protected $view = NULL;

    public function _initialize() {
        $this->isLogin();

        $firstMenus = MenuModel::all([
            'pid'=>0,
            'hide' => 0
        ]);
        foreach ($firstMenus as $firstMenu){
            $firstMenu['child'] = MenuModel::all([
                'pid'=>$firstMenu['id'],
                'hide' => 0
            ]);
        }
        //基本信息
        $row['copyright'] = Db::table('geek_config')
            ->where('name', 'Copyright_information')
            ->value('value');
        $row['alias'] = Db::table('geek_config')
            ->where('name', 'alias')
            ->value('value');
        $this->assign("row", $row);
        $this->assign("admin", session('admin'));
        $this->assign("firstMenus", $firstMenus);
        $this->assign("username", session("user"));
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
