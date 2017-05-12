<?php
namespace app\admin\controller;
use app\common\model\UcenterAdminModel;
use app\admin\model\AdminRoleModel;
use app\admin\logic\AccountLogic;
use think\Request;
class UcenterAdmin extends Admin{

    public function index(){
        if(request()->isAjax()){
            return $this->getRecords();
        }else{
            $this->assign('title','后台用户列表');
            return $this->fetch('UcenterAdmin/index');
        }
    }

    public function add() {
        if(request()->isPost()){
            return $this->addPost();
        }else{
            $this->assign('perms',AdminRoleModel::all(['status'=>1]));
            $this->view->assign('title', '添加管理员');
            return $this->view->fetch('UcenterAdmin/add');
        }
    }

    /*功能：添加配置*/
    private function addPost() {
        $ucenterAdminModel = new UcenterAdminModel();
        $data = $_POST;
        /*校验：是否重复提交*/
        $check = $ucenterAdminModel::get(['username' => $data['username']]);
        $data['salt'] = md5(date('Y-m-d H:i:s'));
        $data['password'] = AccountLogic::encodePassword($data['password'], $data['salt']);
        if ($check) {
            return array('success'=>false,"info"=>"该管理员已存在");
        }

        $result = $ucenterAdminModel::create($data)->save();
        if($result !== false){
            return array('success'=>true,"info"=>"操作成功");
        }else{
            return array('success'=>false,"info"=>"操作失败");
        }
    }

    public function edit($id) {
        if(request()->isPost()){
            return $this->editPost();
        }else{
            $ucenterAdminModel = new UcenterAdminModel();
            $row = $ucenterAdminModel->where(array('id'=>$id))->find();
            $this->assign('title', '编辑后台用户信息');
            $this->assign('perms',AdminRoleModel::all(['status'=>1]));
            $this->assign('row', $row);
            return $this->view->fetch('UcenterAdmin/edit');
        }
    }

    private function editPost() {
        $data = $_POST;
        $result = UcenterAdminModel::update($data);

        if($result !== false){
            return array('success'=>true,"info"=>"操作成功");
        }else{
            return array('success'=>false,"info"=>"操作失败");
        }
    }

    private function getRecords() {
        $records = array();
        $ucenterAdminModel = new UcenterAdminModel();
        $start = input('post.start', 0);
        $length = input('post.length', 20);

        $columns = input('post.columns/a');
        $orderColumns = input('post.order/a');
        $orders = [];
        foreach ($orderColumns as $orderColumn) {
            $orders[$columns[$orderColumn['column']]['data']] = $orderColumn['dir'];
        }

        $condition = [];
        $username = input('post.username', '');
        if ($username) {
            $condition['username'] = array('like', "%$username%");
        }
        $status = input('post.status', '');
        if ($status != '') {
            $condition['status'] = $status;
        }

        $records["data"] = $ucenterAdminModel->where($condition)->limit($start,$length)->order($orders)->select();
        $records["recordsTotal"] = $ucenterAdminModel->where($condition)->count();
        $records["recordsFiltered"] = $records["recordsTotal"];
        $records['draw'] = input('post.draw', 1);
        foreach ($records["data"] as &$row) {
            $row['selectDOM'] = '<label class="mt-checkbox mt-checkbox-single mt-checkbox-outline"><input name="id[]" type="checkbox" class="checkboxes" value="' . $row['id'] . '"/><span></span></label>';
            $row['status'] = $row['status'] == 0 ? '禁用' : '启用';
            $row['roleName'] = AdminRoleModel::where('id',$row['role_id'])->value('name');
        }
        return $records;
    }



    public function delete($id){
        if (Request::instance()->isAjax()){
            $res = UcenterAdminModel::destroy($id);
            if($res){
                return array('success'=>true,"info"=>"操作成功");
            }else{
                return array('success'=>false,"info"=>"操作失败");
            }
        }
    }

}