<?php
namespace app\admin\controller;

use app\admin\model\Member;
use app\admin\model\UcenterMember;
use app\admin\model\UcMember;
use think\Controller;
use think\response\View;
use think\Session;

class Login extends Controller
{
    public function login()
    {
        $login_result = $this->is_login();
        if ($login_result) {
            redirect('Index/index');
        }
        $view = new \think\View();
        return $view->fetch();
    }


    public function check_verify()
    {
        $rJson = [];
        $verify = $_POST['verify'];
        if (!captcha_check($verify)) {
            $rJson['success'] = false;
        } else {
            $rJson['success'] = true;
        }
        echo json_encode($rJson);
    }


    public function check_login()
    {
        $rJson = [];
        $username = $_POST['username'];
        $password = $_POST['password'];

        $memberModel = new UcenterMember();
        $condition = array(
            'username' => $username,
            'password' => md5($password)
        );
        $result = $memberModel->where($condition)->find();
        if ($result) {
            session_start();
            Session::set('user', $username);
            $rJson['success'] = true;
        } else {
            $rJson['success'] = false;

        }
        echo json_encode($rJson);

    }


    /**
     * 检测用户是否登录
     * @return integer
     * @author yahao
     */
    function is_login()
    {
        $user = Session::get('user');
        if (empty($user)) {
            return true;
        } else {
            return false;
        }
    }


    public function login_destroy()
    {
        $login_result = $this->is_login();
        if ($login_result) {
            Session::destroy();
            $this->success('退出系统成功', 'login');
        } else {
            $this->redirect('login');
        }
    }


}
