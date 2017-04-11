<?php
namespace app\home\controller;

class Index extends Home{
    public function index() {
        if (!is_file(DATA_PATH . 'install.lock')) {
            $this->redirect('install/Index/index');
        }
        $view = new \think\View();
        return $view->fetch('Index/index',[],['__HOME__'=>'/home/']);
    }
}