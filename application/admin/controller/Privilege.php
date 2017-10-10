<?php

namespace app\admin\controller;

use app\admin\model\ActionModel;
use app\admin\logic\PrivilegeLogic;
use think\Request;

class Privilege extends Admin
{
    protected $beforeActionList = [
        'pervilegeVilidate' => ['except' => 'getControllerAjax,getFunctionAjax'],
    ];

    public function index()
    {
        if (request()->isAjax()) {
            return $this->getRecords();
        } else {
            $this->assign('title', '操作列表');

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
        foreach ($orderColumns as $orderColumn) {
            $orders[$columns[$orderColumn['column']]['data']] = $orderColumn['dir'];
        }
        $condition = [];
        $code = input('post.code', '');
        if ($code) {
            $condition['code'] = $code;
        }
        $name = input('post.name', '');
        if ($name) {
            $condition['name'] = array('like', "%$name%");
        }

        $ActionModel = new ActionModel();
        $records["data"] = $ActionModel->where($condition)
            ->order($orders)
            ->limit($start, $length)
            ->select();
        $records["recordsFiltered"] = $records["recordsTotal"] = $ActionModel->where($condition)
            ->count();

        foreach ($records["data"] as $row) {
            $row['selectDOM'] = '<label class="mt-checkbox mt-checkbox-single mt-checkbox-outline"><input name="id[]" type="checkbox" class="checkboxes" value="' . $row['id'] . '"/><span></span></label>';
        }

        return $records;
    }

    public function add()
    {
        if (request()->isPost()) {
            return $this->addPost();
        } else {
            return $this->fetch('Privilege/add');
        }
    }

    public function getControllerAjax()
    {
        return PrivilegeLogic::getController(input('moudle'));
    }

    public function getFunctionAjax()
    {

        $functions = PrivilegeLogic::getFunction(input('moudle'),
            input('controller'));

        return $this->getfunctionArr($functions, input('controller'));
    }

    protected function getfunctionArr($functions, $controller)
    {
        $functionArr = [];
        foreach ($functions as $function) {
            $classDirArr = explode('\\', $function->class);
            if (array_pop($classDirArr) == $controller) {
                $functionArr[] = $function->name;
            }
        }
        $functionArr[] = '*';

        return $functionArr;
    }

    private function addPost()
    {
        $data = input('post.');
        $res = ActionModel::create($data)->save();
        if ($res === false) {
            return ['success' => false, 'info' => '操作添加失败'];
        }

        return ['success' => true, 'info' => '操作添加成功'];
    }

    public function edit($id)
    {
        if (request()->isPost()) {
            return $this->editPost();
        } else {
            $row = ActionModel::get($id);
            $functions = PrivilegeLogic::getFunction($row['moudle'],
                $row['controller']);
            $this->assign('row', $row);
            $this->assign('controllers',
                PrivilegeLogic::getController($row['moudle']));
            $this->assign('functions',
                $this->getfunctionArr($functions, $row['controller']));

            return $this->fetch('Privilege/edit');
        }
    }

    private function editPost()
    {
        $data = input('post.');
        $res = ActionModel::update($data);
        if ($res === false) {
            return ['success' => false, 'info' => '操作编辑失败'];
        }

        return ['success' => true, 'info' => '操作编辑成功'];
    }

    public function delete($id)
    {
        if (Request::instance()->isAjax()) {
            $res = ActionModel::destroy($id);
            if ($res !== false) {
                return array('success' => true, "info" => "操作成功");
            } else {
                return array('success' => false, "info" => "操作失败");
            }
        }
    }
}