<?php
namespace app\home\controller;
use think\Controller;
use think\Db;

class Index extends Controller{
    public function index() {
        if (!is_file(DATA_PATH . 'install.lock')) {
            $this->redirect('install/Index/index');
        }

        $view = new \think\View();

        return $view->fetch('Index/index',[],['__HOME__'=>'/home/']);
    }
}