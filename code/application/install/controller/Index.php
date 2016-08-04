<?php
namespace app\install\controller;

use think\Controller;

class Index extends Controller{
    public function index() {
        return $this->fetch('index');
    }

    //安装完成
    public function complete() {
        $step = session('step');

        if (!$step) {
            $this->redirect('index');
        } elseif ($step != 3) {
            $this->redirect("Install/step{$step}");
        }

        // 写入安装锁定文件
        file_put_contents(DATA_PATH . 'install.lock', date('Y-m-d H:i:s'));

        session('step', NULL);
        session('error', NULL);
        session('update', NULL);
        return $this->fetch();
    }
}
