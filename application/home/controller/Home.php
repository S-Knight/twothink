<?php
namespace app\home\controller;
use think\Controller;

class Home extends Controller{
    protected $beforeActionList = ['checkDb','checkSite'];

    protected function checkDb ()
    {
        if (!is_file(DATA_PATH . 'install.lock')) {
            $this->redirect('install/Index/index');
        }
    }

    protected function checkSite ()
    {
        if(getConfig('GUANBIZHANDI')){
            $this->redirect('home/Error/index');
        }
    }
}