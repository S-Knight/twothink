<?php
namespace app\admin\controller;

use think\response\View;

class Login
{
    public function login()
    {
        $view = new \think\View();
        return $view->fetch();
    }
}
