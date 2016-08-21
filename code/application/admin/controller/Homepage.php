<?php
namespace app\admin\controller;
use think\Controller;
use think\db;
use think\Request;

class Homepage extends Admin{
	public function basic_info(){
		if(Request::instance()->isPost()){
			$data=$_POST;
    		$configs=Db::table('geek_config')->where('group','1')->select();

    		foreach($configs as $config){
    			if(empty($data[$config['name']]) && $config['type'] == 2){
    				$data[$config['name']]=$this->file($config['name']);
    				if($data[$config['name']]==''){
    					$data[$config['name']]=$data['old'.$config['name']];
    				}
    				
    			}
    			unset($data['old'.$config['name']]);
    			$b=Db::table('geek_config')->where('name',$config['name'])->setField('value',$data[$config['name']]);
    			
    		}
			if($b!==false){
				$this->redirect('');
			}else{
				$this->error('操作失败');
			}
			
			
		}else{
			$list=Db::table('geek_config')->where('group','1')->select();
			//上传背景图
			$bg=Db::table('geek_config')->where('name','Upload_background_img')->value('value');
			$this->view->assign('bg',$bg);
			if($list){
				$this->view->assign('list',$list);
			}else{
				$this->view->assign('list',array());
			}
			
			return $this->view->fetch();
		}
		
	}
	
	public function solution(){
		$list = Db::table('geek_solution')->paginate(10);
		$this->view->assign('list',$list);
		$this->view->assign('page',$list->render());
		return $this->view->fetch();
	}
	
	public function hide(){
		$b = Db::table($_POST['table'])->where('id',$_POST['id'])->setField('is_show',$_POST['state']);
		if($b){
			return true;
		}else{
			return false;
		}
	}
	
	public function add_solution(){
		if(Request::instance()->isPost()){
			$data=$_POST;
			$data['creation_time']=time();
			$data['is_show']=Request::instance()->param('is_show',1);
			if(empty($data['id'])){
				unset($data['id']);
				$b=Db::table('geek_solution')->insert($data);
			}else{
				$b=Db::table('geek_solution')->update($data);
			}
			if($b){
				$this->success('操作成功','solution');
			}else{
				$this->error('操作失败','solution');
			}
		}else{
			/* Report all errors except E_NOTICE */
			error_reporting(E_ALL^E_NOTICE);
			$id=$this->request->param('id');
			$list=array();
			if($id>0){
				$list = Db::table('geek_solution')->find($id);
				$this->view->assign('list',$list);
			}else{
				$list['is_show']=0;
				$this->view->assign('list',$list);
			}
			return $this->view->fetch();
		}
	}
	
	public function del_solution(){
		$id=$this->request->param('id');
		if($id){
			$b=Db::table('geek_solution')->delete($id);
			if($b){
				$this->redirect('solution');
			}else{
				$this->error('操作失败','solution');
			}
		}
	}

}
