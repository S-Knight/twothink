<?php
namespace app\admin\controller;

use app\admin\model\Menu;
use think\Controller;
use think\Session;
use think\db;

class Admin extends Controller
{
	protected  $view = 	null;
	
   public function _initialize()
   {
       $this->isLogin();
       
       /* Report all errors except E_NOTICE */
       error_reporting(E_ALL^E_NOTICE);
       $this->isLogin();
       $menuModel = new \app\admin\model\Menu();
       $parentMenus =$menuModel->where('is_show',1)->where('pid', 0)->order('sort')->select();
        
       $menus = [];
       foreach ($parentMenus as $parentMenu) {
       	$menu = [];
       	$menu['title'] = $parentMenu->title;
       	$menu['id'] = $parentMenu->id;
       	 
       	$menu['childMenus'] = [];
       	$childMenus =$menuModel->where('is_show',1)->where('pid', $parentMenu->id)->order('sort')->select();
       	 
       	foreach ($childMenus as $childMenu){
       		$menu['childMenus'][] = $childMenu->toArray();
       	}
       	 
       	$menu['childMenus'] = $childMenus;
       
       	$menus[] = $menu;
       }
 
       //网站主页
       $home_index=Db::table('geek_menu')->where(['is_show'=>1,'title'=>'网站主页'])->find();
        
       //基本信息
       $row['copyright']=Db::table('geek_config')->where('name','Copyright_information')->value('value');
       $row['alias']=Db::table('geek_config')->where('name','alias')->value('value');
       $this->view = new \think\View();
       $this->view->assign("row", $row);
       $this->view->assign("home_index", $home_index);
       $this->view->assign("menus", $menus);
       $this->view->assign("username",session("user"));

   }

    /**
     *判断是否登录
     */
    public function isLogin(){
        session_start();
        $user = Session::get('user');
        if(!$user){
           $this->redirect('Login/login');
        }
    }

    public function menuDisplay()
    {
        $menuModel = new Menu();

        $firstMenus = $menuModel -> where(['pid'=>0,'hid'=>0])->order('sort')->select();

        foreach ($firstMenus as &$firstMenu)
        {
            $secondMenus = $menuModel -> where(['pid'=>$firstMenu['id'],'hid'=>0])->order('sort')->select();

            foreach ($secondMenus as &$secondMenu)
            {
                $thirdMenus = $menuModel -> where(['pid'=>$secondMenu['id'],'hid'=>0])->order('sort')->select();
                $secondMenu['childmenu'] = $thirdMenus;
            }
            $firstMenu['childmenu'] = $secondMenus;
        }
    }
    
    public function file($filename){
    	$file = request()->file($filename);
    	if(!empty($file)){
    		$info = $file->move(ROOT_PATH . 'public' . DS . 'uploads');
    		if($info){
    			return $row='/uploads/'.($info->getSaveName());
    		}else{
    			$this->error($file->getError());
    		}
    	}
    }
}
