<?php
namespace app\home\controller;

class Index extends Home{
    public function index() {
        $view = new \think\View();
        return $view->fetch('Index/index',[],['__HOME__'=>'/home/']);
    }
}