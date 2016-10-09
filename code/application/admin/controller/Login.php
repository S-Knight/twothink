<?php
namespace app\admin\controller;

use think\Controller;
use think\response\View;
use think\Session;
use think\Db;
class Login extends Controller
{
    public function login()
    {
    	//基本信息
    	//dump(md5(md5('123').'B,j,m,Y,G,9'));die;
    	$row['alias']=Db::table('geek_config')->where('name','alias')->value('value');
    	$row['copyright']=Db::table('geek_config')->where('name','Copyright_information')->value('value');
        $login_result = $this->is_login();
        if ($login_result) {
            redirect('menu/index');
        }
        $view = new \think\View();
        $view->assign("row", $row);
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
   
        $condition = array(
            'username' => $username,
        );
        $result = Db::table('geek_ucenter_information')->where($condition)->find();
        
        if ($result && $result['password']==md5(md5($password).$result['salt'])) {
            session_start();
            Session::set('user', $username);
            $login_number=$result['login_number']+1;
            $login_time=time();
            Db::table('geek_ucenter_information')->where($condition)->update(['login_number' => $login_number,'login_time'=>$login_time]);
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
