<?php
namespace app\home\controller;
use think\Controller;
class Error extends Controller{
    public function index ()
    {
        $error = getConfig('GUANBIYUANYIN');
        $this->assign('error',$error);
        return $this->view->fetch('Error/index');
    }
}