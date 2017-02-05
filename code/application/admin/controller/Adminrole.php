<?php
namespace app\admin\controller;
use app\admin\model\AdminRoleModel;
use app\admin\model\PrivilegeModel;
use app\admin\logic\AdminroleLogic;
use think\Request;

class Adminrole extends Admin
{
    public function index()
    {
        if(request()->isAjax()){
            return $this->getRecords();
        }else{
            $this->assign('title','用户组列表');
            return $this->fetch('Adminrole/index');
        }
    }

    protected function getRecords()
    {
        $records = array();
        $records["data"] = array();
        $records['draw'] = input('post.draw', 1);
        $start = input('post.start', 0);
        $length = input('post.length', 20);
        $columns = input('post.columns/a');
        $orderColumns = input('post.order/a');
        $orders = [];
        foreach ($orderColumns as $orderColumn){
            $orders[$columns[$orderColumn['column']]['data']] = $orderColumn['dir'];
        }
        $adminRoleModel = new AdminRoleModel();
        $records["data"] = $adminRoleModel->order($orders)->limit($start,$length)->select();
        $records["recordsFiltered"] = $records["recordsTotal"] = $adminRoleModel->count();

        foreach ($records["data"] as $row) {
            $row['selectDOM'] = '<label class="mt-checkbox mt-checkbox-single mt-checkbox-outline"><input name="id[]" type="checkbox" class="checkboxes" value="' . $row['id'] . '"/><span></span></label>';
            $row['statusText'] = $row['status'] == 0 ? '禁用' : '启用';
        }
        return $records;
    }

    public function add()
    {
        $this->assign('privileges',AdminroleLogic::getPrivilege());
        return $this->fetch('Adminrole/add');
    }

    public function addPost()
    {
        if(request()->isPost()){

            $data = input('post.');
            $data['perms'] = implode(',', $data['perms']);
            $check = AdminRoleModel::get(['name' => $data['name']]);
            if ($check) {
                return array('status'=>'n',"info"=>"该用户组已添加，请勿重复提交数据");
            }
            $res = AdminRoleModel::create($data)->save();
            if($res === false){
                return ['status'=>'n','info'=>'用户组添加失败'];
            }
            return ['status'=>'y','info'=>'用户组添加成功'];
        }
    }

    public function edit($id)
    {
        $this->assign('privileges',AdminroleLogic::getPrivilege());
        $this->assign('row',AdminRoleModel::get($id));
        $this->assign('id',$id);
        return $this->fetch('Adminrole/edit');
    }

    public function editPost()
    {
        if(request()->isPost()){
            $data = input('post.');
            $data['perms'] = implode(',', $data['perms']);
            $check = AdminRoleModel::get(['name' => $data['name'],'id'=>['neq',$data['id']]]);
            if ($check) {
                return array('status'=>'n',"info"=>"该用户组已存在");
            }
            $res = AdminRoleModel::update($data);
            if($res === false){
                return ['status'=>'n','info'=>'用户组修改失败'];
            }
            return ['status'=>'y','info'=>'用户组修改成功'];
        }
    }

    public function delete($id){
        if (Request::instance()->isAjax()){
            $res = AdminRoleModel::destroy($id);
            if($res){
                return array('status'=>'y',"info"=>"操作成功");
            }else{
                return array('status'=>'n',"info"=>"操作失败");
            }
        }
    }

}
