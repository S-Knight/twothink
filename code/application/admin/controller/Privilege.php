<?php
namespace app\admin\controller;
use app\admin\model\PrivilegeModel;
use app\admin\logic\PrivilegeLogic;
use think\Request;

class Privilege extends Admin
{
    public function index()
    {
        if(request()->isAjax()){
            return $this->getRecords();
        }else{
            $this->assign('title','权限列表');
            return $this->fetch('Privilege/index');
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
        $condition = [];
        $privilegeModel = new PrivilegeModel();
        $records["data"] = $privilegeModel->where($condition)->order($orders)->limit($start,$length)->select();
        $records["recordsFiltered"] = $records["recordsTotal"] = $privilegeModel->where($condition)->count();

        foreach ($records["data"] as $row) {
            $row['selectDOM'] = '<label class="mt-checkbox mt-checkbox-single mt-checkbox-outline"><input name="id[]" type="checkbox" class="checkboxes" value="' . $row['id'] . '"/><span></span></label>';

        }
        return $records;
    }

    public function add()
    {
        return $this->fetch('Privilege/add');
    }

    public function getControllerAjax()
    {
        return PrivilegeLogic::getController(input('moudle'));
    }

    public function getFunctionAjax()
    {

        $functions = PrivilegeLogic::getFunction(input('moudle'),input('controller'));
        return $this->getfunctionArr($functions,input('controller'));
    }

    protected function getfunctionArr($functions,$controller)
    {
        $functionArr = [];
        foreach ($functions as $function){
            $classDirArr = explode('\\',$function->class);
            if(array_pop($classDirArr) == $controller){
                $functionArr[] = $function->name;
            }
        }
        return $functionArr;
    }

    public function addPost()
    {
        if(request()->isPost()){
            $data = input('post.');
            $res = PrivilegeModel::create($data)->save();
            if(!$res){
                return ['status'=>'n','info'=>'权限添加失败'];
            }
            return ['status'=>'y','info'=>'权限添加成功'];
        }
    }

    public function edit($id)
    {
        $row = PrivilegeModel::get($id);
        $functions = PrivilegeLogic::getFunction($row['moudle'],$row['controller']);
        $this->assign('row',$row);
        $this->assign('controllers',PrivilegeLogic::getController($row['moudle']));
        $this->assign('functions',$this->getfunctionArr($functions,$row['controller']));
        return $this->fetch('Privilege/edit');
    }

    public function editPost()
    {
        if(request()->isPost()){
            $data = input('post.');
            $res = PrivilegeModel::update($data);
            if($res === false){
                return ['status'=>'n','info'=>'权限编辑失败'];
            }
            return ['status'=>'y','info'=>'权限编辑成功'];
        }
    }

    public function delete($id){
        if (Request::instance()->isAjax()){
            $res = PrivilegeModel::destroy($id);
            if($res){
                return array('status'=>'y',"info"=>"操作成功");
            }else{
                return array('status'=>'n',"info"=>"操作失败");
            }
        }
    }
}