<?php

namespace app\admin\controller;

use app\admin\logic\AccountLogic;
use think\Controller;
use think\Session;

class Account extends Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->assign('template', '/admin/template1');
    }

    /**
     * 显示登录页面
     * @return mixed
     */
    public function login()
    {
        return $this->fetch('Account/login');
    }

    /**
     * 校验验证码
     * @return array
     */
    public function checkVerify()
    {
        $rJson = [];
        $verify = input('post.verify');
        if (!captcha_check($verify)) {
            $rJson['success'] = false;
        } else {
            $rJson['success'] = true;
        }

        return $rJson;
    }

    public function checkLogin()
    {

        $rJson = [];
        $username = input('post.username');
        $password = input('post.password');

        $accountLogic = new AccountLogic();
        if ($accountLogic->checkLogin($username, $password)) {
            $rJson['success'] = true;
        } else {
            $rJson['success'] = false;
            $rJson['data']['error'] = $accountLogic->getError();
        }

        return $rJson;
    }

    /**
     * 检测用户是否登录
     * @return integer
     * @author yahao
     */
    public function is_login()
    {
        $user = Session::get('user');
        if (empty($user)) {
            return true;
        } else {
            return false;
        }
    }

    /**
     *退出
     */
    public function logout()
    {
        Session::clear();
        $this->success('退出系统成功', 'login');
    }
}
