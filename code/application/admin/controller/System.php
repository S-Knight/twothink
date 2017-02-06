<?php
namespace app\admin\controller;
use app\admin\model\SystemModel;
use think\db;
use think\Request;
use think\Session;
class System extends Admin{
    public function config(){
        if (Request::instance()->isPost()){
            $data = Request::instance()->post();
            $systemModel = new SystemModel();
            $configs=$systemModel->select();
            foreach($configs as $config){
                $b=$systemModel->where('name',$config['name'])->setField('value',$data[$config['name']]);
            }

            if($b !== false){
                return array('status'=>'y',"info"=>"操作成功");
            }else{
                return array('status'=>'n',"info"=>"操作失败");
            }
        }else{
            $systemModel = new SystemModel();
            $list = $systemModel->order('sort')->select();
            $this->view->assign('title', '系统设置');
            $this->view->assign('list', $list);
            return $this->view->fetch();
        }

    }

    public function index(){
        $this->view->assign('title', '系统配置列表');
        return $this->view->fetch();
    }

    public function add() {
        $this->view->assign('title', '添加系统配置');
        return $this->view->fetch();
    }

    public function edit($id) {
        $systemModel = new SystemModel();
        $row = $systemModel->where(array('id'=>$id))->find();
        $this->view->assign('title', '编辑系统配置');
        $this->view->assign('id', $id);
        $this->view->assign('row', $row);
        return $this->view->fetch();
    }

    public function getRecords() {
        $records = array();
        $systemModel = new SystemModel();
        $start = input('post.start', 0);
        $length = input('post.length', 20);
        $records["data"] = $systemModel->limit($start,$length)->order('created_at desc')->select();
        $records["recordsTotal"] = $systemModel->count();
        $records["recordsFiltered"] = $records["recordsTotal"];
        $records['draw'] = input('post.draw', 1);
        foreach ($records["data"] as &$row) {
            $row['selectDOM'] = '<label class="mt-checkbox mt-checkbox-single mt-checkbox-outline"><input name="id[]" type="checkbox" class="checkboxes" value="' . $row['id'] . '"/><span></span></label>';
        }
        return $records;
    }

    /*功能：添加配置*/
    public function addPost() {
        $systemModel = new SystemModel();
        $data = $_POST;
        /*校验：是否重复提交*/
        $check = $systemModel::get(['name' => $data['name']]);

        if ($check) {
            return array('status'=>'n',"info"=>"该标识已存在");
        }
        $result = SystemModel::create($data)->save();
        if($result !== false){
            return array('status'=>'y',"info"=>"操作成功");
        }else{
            return array('status'=>'n',"info"=>"操作失败");
        }
    }

    public function editPost() {
        $data = $_POST;
        $result = SystemModel::update($data);

        if($result !== false){
            return array('status'=>'y',"info"=>"操作成功");
        }else{
            return array('status'=>'n',"info"=>"操作失败");
        }
    }

    public function delete($id){
        if (Request::instance()->isAjax()){
            $res = SystemModel::destroy($id);
            if($res){
                return array('status'=>'y',"info"=>"操作成功");
            }else{
                return array('status'=>'n',"info"=>"操作失败");
            }
        }
    }

}