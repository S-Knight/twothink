<?php
namespace app\admin\controller;
use think\Controller;
use think\db;
use think\Request;

class Menu extends Admin
{
    public function index()
    {
    	$id = Request::instance()->param('id',0);
    	//dump($id);die;
    	$data = Db::table('geek_menu')->where('pid',$id)->order('sort')->paginate(10);
    	$this->view->assign('data',$data);
    	$this->view->assign('page',$data->render());
    	return $this->view->fetch();
    }

    public function add()
    {
        $pid = Request::instance()->param('pid');
        $menuModel = new \app\admin\model\Menu();
        if (Request::instance()->isPost()) {
            $data = Request::instance()->post(); // 获取经过过滤的全部post变量
			//dump($data);die;
            $add = Db::table('geek_menu')->insert($data);
            if ($add) {
                $this->success('数据提交成功','/Admin/Menu/index');
            } else {
                $this->error('提交失败');
            }
        } else {
      
             $list = Db::table('geek_menu')->where('pid',0)->field('id,title')->select();
            $this->view->assign('list', $list);
            $this->view->assign('pid',$pid);
            return $this->view->fetch();
        }
    }

    public function edit($id)
    {
        $menuModel = new \app\admin\model\Menu();
        if (Request::instance()->isPost())
        {
            $postid = $_POST['id'];
            $save = $menuModel->allowField(true)->save($_POST,['id' => $postid]);
            if ($save)
            {
                $this -> success('数据更新成功','/Admin/Menu/index');
            }
            else
            {
                $this -> error('数据更新失败');
            }
        }
        else
        {
            $update = $menuModel->where(['id' => $id])->find();
            $data = $update->toArray();
            $parentMenu = $menuModel -> where(['id'=>$id])->value('pid');
            $list = Db::table('geek_menu')->where('pid',0)->field('id,title')->select();


            
            $this->view->assign('list', $list);
            $this->view->assign('data', $data);
            $this->view->assign('menupid',$parentMenu);
            return $this->view->fetch();
        }
    }

    public function delete($id)
    {
		
        $menuModel = new \app\admin\model\Menu();
        $delete = $menuModel -> destroy($id);
		$pid=$this->request->param('pid');
        if ($delete)
        {
            $this->redirect('/Admin/Menu/index',['id'=>$pid]);
        }
        else
        {
            $this->error('删除失败');
        }
    }
    
    public function hide(){
    	$b = Db::table('geek_menu')->where('id',$_POST['id'])->setField('is_show',$_POST['state']);
    	if($b){
    		return true;
    	}else{
    		return false;
    	}
    }
}
