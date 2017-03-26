<?php
namespace app\install\controller;

use think\Controller;
use think\Db;

class Install extends Controller{
    protected function _initialize() {
        if (is_file(DATA_PATH . 'install.lock')) {
            $this->showError('已经成功安装，请不要重复安装!');
        }
    }

    public function step1() {
        session('error', false);

        //环境检测
        $env = check_env();

        //目录文件读写检测
        if (IS_WRITE) {
            $dirfile = check_dirfile();
            $this->assign('dirfile', $dirfile);
        }

        //函数检测
        $func = check_func();

        session('step', 1);

        $this->assign('env', $env);
        $this->assign('func', $func);

        return $this->fetch('Install/step1');
    }

    //安装第二步，创建数据库
    public function step2() {
        session('error') && $this->showError('环境检测没有通过，请调整环境后重试！');
        session('step', 2);

        return $this->fetch('Install/step2');
    }

    public function setDatabase($db = NULL, $admin = NULL) {
        //检测管理员信息
        if (!is_array($admin) || empty($admin[0]) || empty($admin[1])) {
            $this->showError('请填写完整管理员信息');
        } else if ($admin[1] != $admin[2]) {
            $this->showError('确认密码和密码不一致');
        } else {
            $info = array();
            list($info['username'], $info['password'], $info['repassword'], $info['email'])
                = $admin;
            //缓存管理员信息
            session('admin_info', $info);
        }

        //检测数据库配置
        if (!is_array($db) || empty($db[0]) || empty($db[1]) || empty($db[2]) || empty($db[3])) {
            $this->showError('请填写完整的数据库配置');
        } else {
            $dbConfig = array();
            list($dbConfig['type'], $dbConfig['hostname'], $dbConfig['database'], $dbConfig['username'], $dbConfig['password'],
                $dbConfig['hostport'], $dbConfig['prefix']) = $db;

            session('dataConfig', $dbConfig);

            //创建数据库
            $dbname = $dbConfig['database'];
            unset($dbConfig['database']);

            $db = Db::connect($dbConfig);

            $sql = "CREATE DATABASE IF NOT EXISTS `{$dbname}` DEFAULT CHARACTER SET utf8mb4";

            try {
                $db->execute($sql);
            } catch (\Think\Exception $e) {
                if (strpos($e->getMessage(), 'getaddrinfo failed') !== false) {
                    $this->showError('数据库服务器（数据库服务器IP） 填写错误。', '很遗憾，创建数据库失败，失败原因');// 提示信息
                }
                if (strpos($e->getMessage(), 'Access denied for user') !== false) {
                    $this->showError('数据库用户名或密码 填写错误。', '很遗憾，创建数据库失败，失败原因');// 提示信息
                } else {
                    $this->showError($e->getMessage());// 提示信息
                }
            }
            session('step', 2);
        }

        //跳转到数据库安装页面
        $this->redirect('step3');
    }

    //安装第三步，安装数据表，创建配置文件
    public function step3(){
        echo  $this->fetch('Install/step3');

        //连接数据库
        $dbconfig = session('dataConfig');
        $db = Db::connect($dbconfig);
        //创建数据表

        create_tables($db, $dbconfig['prefix']);
        //注册创始人帐号
        $admin = session('admin_info');
        register_administrator($db, $dbconfig['prefix'], $admin);

        //创建配置文件
        $conf   =   write_config($dbconfig);
        session('config_file',$conf);

        if(session('error')){
            show_msg(session('error'));
        } else {
            session('step', 3);

            echo "<script type=\"text/javascript\">setTimeout(function(){location.href='".url('Index/complete')."'},5000)</script>";
            ob_flush();
            flush();
        }

        if(session('error')){
            show_msg(session('error'));
        } else {
            session('step', 3);

            echo "<script type=\"text/javascript\">setTimeout(function(){location.href='".url('install/Index/complete')."'},5000)</script>";
            ob_flush();
            flush();
        }
    }

    public function showError($info,$title='很遗憾，安装失败，失败原因'){
        $this->assign('info',$info);// 提示信息
        $this->assign('title',$title);
        return $this->fetch('Install/error');
    }
}