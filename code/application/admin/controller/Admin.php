<?php
namespace app\admin\controller;

use app\admin\model\Menu;
use think\Controller;
use think\Session;

class Admin extends Controller
{
   public function _initialize()
   {
       $this->isLogin();
   }

    /**
     *判断是否登录
     */
    public function isLogin(){
        session_start();
        $user = Session::get('user');
        if(!$user){
           $this->redirect('Login/login');
        }
    }

    public function menuDisplay()
    {
        $menuModel = new Menu();

        $firstMenus = $menuModel -> where(['pid'=>0,'hid'=>0])->order('sort')->select();

        foreach ($firstMenus as &$firstMenu)
        {
            $secondMenus = $menuModel -> where(['pid'=>$firstMenu['id'],'hid'=>0])->order('sort')->select();

            foreach ($secondMenus as &$secondMenu)
            {
                $thirdMenus = $menuModel -> where(['pid'=>$secondMenu['id'],'hid'=>0])->order('sort')->select();
                $secondMenu['childmenu'] = $thirdMenus;
            }
            $firstMenu['childmenu'] = $secondMenus;
        }
    }
}
