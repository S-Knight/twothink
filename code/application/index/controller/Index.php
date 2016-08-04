<?php
namespace app\index\controller;

use think\Controller;

class Index extends Controller
{
    public function index()
    {
        if(!is_file(DATA_PATH . 'install.lock')){
            $this->redirect('install/Index/index');
        }
    }
}
