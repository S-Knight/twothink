<?php
namespace app\admin\controller;

use think\response\View;
use think\controller;
use think\Session;

class Admin
{
   public function __construct()
   {
       $this->loginDetection();
   }

    /**
     *登录状态检测
     */
    public function loginDetection(){

        session_start();

        $user = Session::get('user');
        if(!$user){
           $this->redirect('Login/login');
        }else{
            echo 'Hellp';exit();
        }
    }



}
