<?php
namespace app\admin\controller;
use think\Controller;
use think\db;
use think\Request;

class System extends Admin{
	public function configure(){
		$group = Request::instance()->param('group');
		if(!empty($group)){
			$data = Db::table('geek_config')->where('group',$group)->order('id desc')->paginate(8);
		}else{
			$data = Db::table('geek_config')->order('id')->paginate(8);
		}
		
		$this->view->assign('data',$data);
		$this->view->assign('page',$data->render());
		return $this->view->fetch();
	}
	
	public function add_configure(){
		if(Request::instance()->isPost()){
			$data=$_POST;
			$data['create_time']=time();
			
			if($data['id']){
				$b=Db::table('geek_config')->update($data);
				
			}else{
				unset($data['id']);
				$b=Db::table('geek_config')->insert($data);
				
			}
			if($b){
				$this->success('操作成功','configure');
			}else{
				$this->error('操作失败','configure');
			}	
		}else{
			/* Report all errors except E_NOTICE */
			error_reporting(E_ALL^E_NOTICE);
			$id=$this->request->param('id');
			$list=array();
			if($id>0){
				$list = Db::table('geek_config')->find($id);
				$this->view->assign('list',$list);
			}else{
				$this->view->assign('list',$list);
			}
			return $this->view->fetch();
		}

	}

	public function del_configure(){
		$id=$this->request->param('id');
		if($id){
			$b=Db::table('geek_config')->delete($id);
			if($b){
				$this->redirect('configure');
			}else{
				$this->error('操作失败','configure');
			}
		}
	}

	public function basic(){
		if(Request::instance()->isPost()){
			$data=$_POST;
			$bg=$this->file('Upload_background_img');
			
			if(empty($bg)){
				$bg=$data['oldbg'];
			}
		
			$b=Db::table('geek_config')->where('name','Upload_background_img')->update(['value'=>$bg]);
			if($b!==false){
				$this->redirect('basic');
			}else{
				$this->error('操作失败','basic');
			}
		}else{
			$bg=Db::table('geek_config')->where('name','Upload_background_img')->find();
			$this->view->assign('bg',$bg);
			return $this->view->fetch();
		}
		
		
	}
}