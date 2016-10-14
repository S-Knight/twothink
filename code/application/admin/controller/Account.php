<?php
namespace app\admin\controller;

use think\Controller;
use think\Session;
use think\Db;

class Account extends Controller{
    /**
     * 显示登录页面
     * @return mixed
     */
    public function login() {
        return $this->fetch('Account/login');
    }

    /**
     * 校验验证码
     * @return array
     */
    public function checkVerify() {
        $rJson = [];
        $verify = input('post.verify');
        if (!captcha_check($verify)) {
            $rJson['success'] = false;
        } else {
            $rJson['success'] = true;
        }

        return $rJson;
    }

    public function checkLogin() {
        $rJson = [];
        $username = input('post.username');
        $password = input('post.password');

        $condition = array(
            'username' => $username,
        );
        $result = Db::table('geek_ucenter_member')->where($condition)->find();

        if ($result && $result['password'] == md5($password)) {
            Session::set('user', $username);
            $rJson['success'] = true;
        } else {
            $rJson['success'] = false;
        }

        return $rJson;
    }

    /**
     * 检测用户是否登录
     * @return integer
     * @author yahao
     */
    public function is_login() {
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
    public function logout() {
        Session::destroy();
        $this->success('退出系统成功', 'login');
    }
}
