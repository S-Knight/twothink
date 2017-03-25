<?php
namespace app\admin\controller;
use app\common\model\UcenterMemberModel;
use app\common\model\MemberModel;
use app\common\model\MemberFinanceModel;
use app\admin\logic\AccountLogic;
use app\common\logic\CommonLogic;
use app\admin\logic\UcenterMemberLogic;
use think\Request;
class UcenterMember extends Admin{

    public function index(){
        if(request()->isAjax()){
            return $this->getRecords();
        }else{
            $this->assign('title','前台用户列表');
            return $this->fetch('UcenterMember/index');
        }
    }

    public function add() {
        if(request()->isPost()){
            return $this->addPost();
        }else{
            $this->view->assign('title', '添加网站用户');
            return $this->view->fetch('UcenterMember/add');
        }
    }

    /*功能：添加配置*/
    private function addPost() {
        $data = $_POST;
        /*校验：是否重复提交*/
        $checkUsername = UcenterMemberLogic::checkUsername($data['username']);
        if ($checkUsername) {
            return array('success'=>false,"info"=>"该用户名已存在");
        }
        $checkEmail = UcenterMemberLogic::checkEmail($data['email']);
        if ($checkEmail) {
            return array('success'=>false,"info"=>"该邮箱已存在");
        }
        $checkMobile = UcenterMemberLogic::checkMobile($data['mobile']);
        if ($checkMobile) {
            return array('success'=>false,"info"=>"该手机号已存在");
        }

        $data['salt'] = md5(date('Y-m-d H:i:s'));
        $data['password'] = AccountLogic::encodePassword($data['password'], $data['salt']);
        $UcenterMemberModel = new UcenterMemberModel();
        $result = $UcenterMemberModel->allowField(true)->save($data);
        $uid = $UcenterMemberModel->getLastInsID();
        MemberModel::create([
            'uid' => $uid,
            'real_name' => $data['real_name'],
        ]);
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
            $row = UcenterMemberModel::get($id);
            $member = MemberModel::get(['uid'=>$id]);
            $this->assign('title', '编辑网站用户信息');
            $this->assign('row', $row);
            $this->assign('member', $member);
            return $this->view->fetch('UcenterMember/edit');
        }
    }

    private function editPost() {
        $UcenterMemberModel = new UcenterMemberModel();
        $result = $UcenterMemberModel->allowField(true)->save($_POST,['id'=>$_POST['id']]);
        $MemberModel = new MemberModel();
        $MemberModel->allowField(true)->save($_POST,['uid'=>$_POST['id']]);

        if($result === false){
            return array('success'=>false,"info"=>"操作失败");
        }else{
            return array('success'=>true,"info"=>"操作成功");
        }
    }

    private function getRecords() {
        $records = array();
        $ucenterMemberModel = new UcenterMemberModel();
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

        $records["data"] = $ucenterMemberModel
            ->where($condition)->limit($start,$length)->order($orders)->select();
        $records["recordsTotal"] = $ucenterMemberModel->where($condition)->count();
        $records["recordsFiltered"] = $records["recordsTotal"];
        $records['draw'] = input('post.draw', 1);
        foreach ($records["data"] as &$row) {
            $row['selectDOM'] = '<label class="mt-checkbox mt-checkbox-single mt-checkbox-outline"><input name="id[]" type="checkbox" class="checkboxes" value="' . $row['id'] . '"/><span></span></label>';
            $row['status'] = $row['status'] == 0 ? '禁用' : '启用';
            $row['last_login_time'] = date('Y-m-d H:i:s',$row['last_login_time']);
           
        }
        return $records;
    }

    public function delete($id){
        if (Request::instance()->isAjax()){
            $res = UcenterMemberModel::destroy($id);
            if($res){
                return array('success'=>true,"info"=>"操作成功");
            }else{
                return array('success'=>false,"info"=>"操作失败");
            }
        }
    }

}