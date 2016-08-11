<?php
namespace app\admin\controller;

use think\response\View;

class AuthManager extends Admin
{
    public function index()
    {
        $view = new \think\View();
        return $view->fetch();
    }
}
