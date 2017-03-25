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
            $configs=$systemModel->where('group',2)->whereOr("name = 'GUANBIZHANDI' or name = 'GUANBIYUANYIN'")
                ->select();
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
            $list = $systemModel->where('group',2)->order('sort')->select();
            $guanbizhandian = SystemModel::where('name','GUANBIZHANDI')->value('value');
            $guanbiyuanyin = SystemModel::where('name','GUANBIYUANYIN')->value('value');
            $this->view->assign('title', '系统设置');
            $this->view->assign('list', $list);
            $this->view->assign('guanbizhandian', $guanbizhandian);
            $this->view->assign('guanbiyuanyin', $guanbiyuanyin);
            return $this->view->fetch('System/config');
        }

    }

    public function index(){
        if(request()->isAjax()){
            return $this->getRecords();
        }else{
            $this->view->assign('title', '系统配置列表');
            return $this->view->fetch('System/index');
        }
    }

    public function add() {
        if(request()->isPost()){
            return $this->addPost();
        }else{
            $this->view->assign('title', '添加系统配置');
            return $this->view->fetch('System/add');
        }
    }

    public function edit($id) {
        if(request()->isPost()){
            return $this->editPost();
        }else{
            $systemModel = new SystemModel();
            $row = $systemModel->where(array('id'=>$id))->find();
            $this->view->assign('title', '编辑系统配置');
            $this->view->assign('id', $id);
            $this->view->assign('row', $row);
            return $this->view->fetch('System/edit');
        }

    }

    private function getRecords() {
        $records = array();
        $systemModel = new SystemModel();
        $start = input('post.start', 0);
        $length = input('post.length', 20);
        
        $columns = input('post.columns/a');
        $orderColumns = input('post.order/a');
        $orders = [];
        foreach ($orderColumns as $orderColumn) {
            $orders[$columns[$orderColumn['column']]['data']] = $orderColumn['dir'];
        }
        $condition = [];
        $name = input('post.name', '');
        if ($name) {
            $condition['name'] = array('like', "%$name%");
        }
        $title = input('post.title', '');
        if ($title) {
            $condition['title'] = array('like', "%$title%");
        }
        $records["data"] = $systemModel->where($condition)->limit($start,$length)->order($orders)->select();
        $records["recordsTotal"] = $systemModel->where($condition)->count();
        $records["recordsFiltered"] = $records["recordsTotal"];
        $records['draw'] = input('post.draw', 1);
        foreach ($records["data"] as &$row) {
            $row['selectDOM'] = '<label class="mt-checkbox mt-checkbox-single mt-checkbox-outline"><input name="id[]" type="checkbox" class="checkboxes" value="' . $row['id'] . '"/><span></span></label>';
            $row['type'] = systemGroup($row['type'],2);
            $row['group'] = systemGroup($row['group'],1);
        }
        return $records;
    }

    /*功能：添加配置*/
    private function addPost() {
        $systemModel = new SystemModel();
        $data = $_POST;
        /*校验：是否重复提交*/
        $check = $systemModel::get(['name' => $data['name']]);

        if ($check) {
            return array('success'=>false,"info"=>"该标识已存在");
        }
        $result = SystemModel::create($data)->save();
        if($result !== false){
            return array('success'=>true,"info"=>"操作成功");
        }else{
            return array('success'=>false,"info"=>"操作失败");
        }
    }

    private function editPost() {
        $data = $_POST;
        $result = SystemModel::update($data);

        if($result !== false){
            return array('success'=>true,"info"=>"操作成功");
        }else{
            return array('success'=>false,"info"=>"操作失败");
        }
    }

    public function delete($id){
        if (Request::instance()->isAjax()){
            $res = SystemModel::destroy($id);
            if($res){
                return array('success'=>true,"info"=>"操作成功");
            }else{
                return array('success'=>false,"info"=>"操作失败");
            }
        }
    }

}