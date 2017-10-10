<?php
namespace app\admin\controller;
use app\common\model\ConfigModel;
use think\Request;
class System extends Admin{
    public function config(){
        if (Request::instance()->isPost()){
            return $this->addConfigPost();
        }else{
            $systemModel = new ConfigModel();
            $list = $systemModel->where('group',2)->order('sort')->select();
            $guanbizhandian = ConfigModel::where('name','GUANBIZHANDI')->value('value');
            $guanbiyuanyin = ConfigModel::where('name','GUANBIYUANYIN')->value('value');
            $this->view->assign('list', $list);
            $this->view->assign('guanbizhandian', $guanbizhandian);
            $this->view->assign('guanbiyuanyin', $guanbiyuanyin);
            return $this->view->fetch('System/config');
        }
    }

    public function addConfigPost()
    {
        $data = Request::instance()->post();
        $systemModel = new ConfigModel();
        $configs=$systemModel->where('group',2)->whereOr("name = 'GUANBIZHANDI' or name = 'GUANBIYUANYIN'")
            ->field('id,name,type')->select();
        $configList = [];
        foreach($configs as $config){
            //复选框特殊处理
            if($config['type'] == 5 && !in_array($config['name'],array_keys($data))){
                $value = '';
            }elseif ($config['type'] == 5 && in_array($config['name'],array_keys($data))){
                $value = implode(',',$data[$config['name']]);
            }else{
                $value = $data[$config['name']];
            }
            $configList[] = [
                'id'=>$config['id'],
                'name'=>$config['name'],
                'value'=>$value
            ];
        }

        if($systemModel->saveAll($configList) !== false){
            return array('success'=>true,"info"=>"操作成功");
        }else{
            return array('success'=>false,"info"=>"操作失败");
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
            $systemModel = new ConfigModel();
            $row = $systemModel->where(array('id'=>$id))->find();
            $this->view->assign('title', '编辑系统配置');
            $this->view->assign('id', $id);
            $this->view->assign('row', $row);
            return $this->view->fetch('System/edit');
        }

    }

    private function getRecords() {
        $records = array();
        $systemModel = new ConfigModel();
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
        $systemModel = new ConfigModel();
        $data = $_POST;
        /*校验：是否重复提交*/
        $check = $systemModel::get(['name' => $data['name']]);

        if ($check) {
            return array('success'=>false,"info"=>"该标识已存在");
        }
        $result = ConfigModel::create($data)->save();
        if($result !== false){
            return array('success'=>true,"info"=>"操作成功");
        }else{
            return array('success'=>false,"info"=>"操作失败");
        }
    }

    private function editPost() {
        $data = $_POST;
        $result = ConfigModel::update($data);

        if($result !== false){
            return array('success'=>true,"info"=>"操作成功");
        }else{
            return array('success'=>false,"info"=>"操作失败");
        }
    }

    public function delete($id){
        if (Request::instance()->isAjax()){
            $res = ConfigModel::destroy($id);
            if($res !== false){
                return array('success'=>true,"info"=>"操作成功");
            }else{
                return array('success'=>false,"info"=>"操作失败");
            }
        }
    }

}