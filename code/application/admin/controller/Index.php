<?php
namespace app\admin\controller;

use think\Db;
use think\Request;
use app\admin\logic\AccountLogic;
use app\admin\model\UcenterAdminModel;
class Index extends Admin
{
    public function index()
    {
        return $this->view->fetch();
    }

    public function updatePsd()
    {
        if(Request::instance()->isAjax()){
            $admin = session('admin');
            $UcenterAdmin = UcenterAdminModel::get($admin['id']);
            $oldPsd = AccountLogic::encodePassword(input('pw'),$UcenterAdmin['salt']);
            if($oldPsd != $UcenterAdmin['password']){
                return ['success'=>false,'info'=>'原密码错误'];
            }
            $UcenterAdminModel = new UcenterAdminModel();
            $password = AccountLogic::encodePassword(input('pw2'),$UcenterAdmin['salt']);
            $b=$UcenterAdminModel->where('id',$UcenterAdmin['id'])->setField('password',$password);
            if($b !== false){
                return ['success'=>true,'info'=>'修改成功'];
            }else{
                return ['success'=>false,'info'=>'修改失败'];
            }
        }else{
            $this->view->assign('admin',session('admin'));
            return $this->view->fetch();
        };
    }
}
