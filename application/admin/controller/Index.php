<?php
namespace app\admin\controller;

use think\Db;
use think\Request;
use think\Cache;
use app\admin\logic\AccountLogic;
use app\admin\model\UcenterAdminModel;
use app\common\model\UcenterMemberModel;
class Index extends Admin
{
    public function index()
    {
        $memberCount = UcenterMemberModel::count();
        $aministratorsCount = UcenterAdminModel::count();
        $this->assign('memberCount',$memberCount);
        $this->assign('aministratorsCount',$aministratorsCount);
        return $this->view->fetch('Index/index');
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

    public function removeCacheAjax()
    {
        if(Cache::clear()){
            return '清除成功';
        }
        return '清除失败';
    }
}
