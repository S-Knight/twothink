<?php

namespace app\home\controller;

use think\Controller;

class Home extends Controller
{
    protected function _initialize()
    {
        if (!is_file(DATA_PATH . 'install.lock')) {
            $this->redirect('install/Index/index');
        }

        if (getConfig('GUANBIZHANDIAN')) {
            $this->error(getConfig('GUANBIYUANYIN'), null, '', 300);
        }
    }
}