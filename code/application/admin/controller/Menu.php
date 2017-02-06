<?php
namespace app\admin\controller;

use app\admin\model\MenuModel;
use app\common\logic\CommonLogic;
use think\Request;

class Menu extends Admin
{
    public function index()
    {
        if (request()->isAjax()) {
            return $this->getRecords();
        } else {
            $this->assign('title', '菜单列表');

            return $this->fetch('Menu/index');
        }
    }

    private function getRecords()
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
        $condition = [];
        $menuModel = new MenuModel();

        $pid = input('param.pid', 0);
        $condition['pid'] = $pid;

        $records["data"] = $menuModel->where($condition)
                                     ->order($orders)
                                     ->limit($start, $length)
                                     ->select();
        $records["recordsFiltered"] = $records["recordsTotal"] = $menuModel->where($condition)
                                                                           ->count();

        foreach ($records["data"] as $row) {
            $row['selectDOM'] = '<label class="mt-checkbox mt-checkbox-single mt-checkbox-outline"><input name="id[]" type="checkbox" class="checkboxes" value="' . $row['id'] . '"/><span></span></label>';
            $row['hideText'] = $row['hide'] == 0 ? '显示' : '隐藏';
            $row['isDevText'] = $row['is_dev'] == 0 ? '否' : '是';
        }

        return $records;
    }

    public function add()
    {
        if (request()->isPost()) {
            $this->addPost();
        } else {
            $menus = MenuModel::all();
            $list = CommonLogic::mergeCate($menus, 'pid');
            $this->assign('list', $list);

            return $this->fetch('Menu/add');
        }
    }

    private function addPost()
    {
        $res = MenuModel::create(input('post.'))->save();
        if (!$res) {
            return ['status' => 'n', 'info' => '菜单添加失败'];
        }

        return ['status' => 'y', 'info' => '菜单添加成功'];
    }

    public function edit($id)
    {
        if (request()->isPost()) {
            $this->editPost();
        } else {
            $menus = MenuModel::all();
            $list = CommonLogic::mergeCate($menus, 'pid');
            $this->assign('list', $list);
            $this->assign('row', MenuModel::get($id));

            return $this->fetch('Menu/edit');
        }
    }

    private function editPost()
    {
        if (request()->isPost()) {
            $res = MenuModel::update(input('post.'));
            if ($res === false) {
                return ['success' => false, 'info' => '菜单修改失败'];
            }

            return ['success' => true, 'info' => '菜单修改成功'];
        }
    }

    public function delete($id)
    {
        if (Request::instance()->isAjax()) {
            $res = MenuModel::destroy($id);
            if ($res) {
                return array('success' => true, "info" => "操作成功");
            } else {
                return array('success' => false, "info" => "操作失败");
            }
        }
    }
}
