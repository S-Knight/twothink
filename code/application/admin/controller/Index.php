<?php
namespace app\admin\controller;

use think\response\View;
use think\Db;
use think\Request;
use think\Session;
class Index extends Admin
{
    public function index()
    {	
      return $this->view->fetch();
    }
    
    public function updatePsd()
    {
    	$query = new \think\db\Query();
    	if(Request::instance()->isPost()){
    		$b=db('ucenter_member')->where('username',session('user'))->setField('password',md5($_POST['password']));
    		if($b){
    			$this->success('修改成功', 'index');
    		}else{
    			$this->error('修改失败','index');
    		}
    	}else{
    		$query->table('geek_ucenter_member')->where('username',session('user'));
    		$row=Db::find($query);
    		$this->view->assign('row',$row);
    		return $this->view->fetch();
    	};
    }
    
    public function password(){
    	$query = new \think\db\Query();
     	$query->table('geek_ucenter_member')->where('username',session('user'));
    	$row=Db::find($query);
    	if($row['password']==md5($_POST['password'])){
    		return true;
    	}else{
    		return false;
    	};
     	
    }
}
