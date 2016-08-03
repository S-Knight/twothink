<?php
namespace app\admin\controller;

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
        }else{
            echo 'Hellp';exit();
        }
    }
}
