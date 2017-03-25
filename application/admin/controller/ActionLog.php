<?php
namespace app\admin\controller;
use app\admin\model\ActionLogModel;
use think\Request;
class ActionLog extends Admin
{
    public function index()
    {
        if (request()->isAjax()) {
            return $this->getRecords();
        } else {
            $this->assign('title', '操作日志列表');
            return $this->fetch('ActionLog/index');
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
        foreach ($orderColumns as $orderColumn) {
            $orders[$columns[$orderColumn['column']]['data']] = $orderColumn['dir'];
        }
        $username = input('post.username', '');
        if ($username) {
            $condition['u.username'] = array('like', "%$username%");
        }
        $action_name = input('post.action_name', '');
        if ($action_name) {
            $condition['a.action_name'] = $action_name;
        }
        $condition['a.created_at'] = [];
        $start_time = input('post.start_time', '');
        if ($start_time != '') {
            $condition['a.created_at'][] = array('>=', $start_time);
        }

        $end_time = input('post.end_time', '');
        if ($end_time != '') {
            $condition['a.created_at'][] = array('<=', $end_time);
        }

        if(!$condition['a.created_at']){
            unset($condition['a.created_at']);
        }
        $ActionLogModel = new ActionLogModel();
        $records["data"] = $ActionLogModel->alias('a')->join('geek_ucenter_admin u','a.uid = u.id')
           ->where($condition)->order($orders)->field('a.*,u.username')->limit($start,$length)->select();
        $records["recordsFiltered"] = $records["recordsTotal"] = $ActionLogModel->alias('a')
                ->join('geek_ucenter_admin u','a.uid = u.id')->where($condition)->count();

        foreach ($records["data"] as $row) {
            $row['selectDOM'] = '<label class="mt-checkbox mt-checkbox-single mt-checkbox-outline"><input name="id[]" type="checkbox" class="checkboxes" value="' . $row['id'] . '"/><span></span></label>';
        }
        return $records;
    }

    public function delete($id){
        if (Request::instance()->isAjax()){
            $res = ActionLogModel::destroy($id);
            if($res){
                return array('success'=>true,"info"=>"操作成功");
            }else{
                return array('success'=>false,"info"=>"操作失败");
            }
        }
    }
}