<?php
namespace app\admin\controller;
use think\response\View;
use think\Db;
use think\Request;
class User extends Admin{
	public function information(){
		$list = Db::table('geek_ucenter_information')->order('login_time desc')->paginate(10);
		$this->view->assign('list',$list);
		$this->view->assign('page',$list->render());
		return $this->view->fetch();
	}
	
	public function add_information(){
		if(Request::instance()->isPost()){

			$data=$_POST;
			$data['salt']=getRandStr();
			$data['password']=md5(md5('123456').$data['salt']);
			if($data['id']){
				$b=Db::table('geek_ucenter_information')->where('username',$data['username'])->update($data);
			}else{
				unset($data['id']);
				$b=Db::table('geek_ucenter_information')->insert($data);
			}
			if($b){
				$this->success('操作成功','information');
			}else{
				$this->error('操作失败','information');
			}
			
		}else{
			/* Report all errors except E_NOTICE */
				
			error_reporting(E_ALL^E_NOTICE);
			$id=$this->request->param('id');
			$list=array();
			if($id>0){
				$list = Db::table('geek_ucenter_information')->find($id);
				$this->view->assign('list',$list);
			}else{
				$this->view->assign('list',$list);
			}
			return $this->view->fetch();
		}
	}
	
	public function del_information(){
		$id=$this->request->param('id');
		if($id){
			$b=Db::table('geek_ucenter_information')->delete($id);
			if($b){
				$this->redirect('information');
			}else{
				$this->error('操作失败','information');
			}
		}
	}
	
	public function reset_password(){
		$id=$this->request->param('id');
		if($id){
			$salt=getRandStr();
			$new_password=md5(md5('123456').$salt);
			$b=Db::table('geek_ucenter_information')->where('id',$id)->update(['password'=>$new_password,'salt'=>$salt]);
			if($b){
				return true;
			}else{
				return false;
			}
		}
	}
}
