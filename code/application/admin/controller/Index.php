<?php
namespace app\admin\controller;

use think\response\View;

class Index extends Admin
{
    public function index()
    {
      $view = new \think\View();
      return $view->fetch();
    }
}
