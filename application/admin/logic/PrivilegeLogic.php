<?php

namespace app\admin\logic;

use app\admin\model\AdminRoleModel;

class PrivilegeLogic extends Logic
{
    static public function getController($moudle)
    {
        $dir_list = scandir('../application/' . $moudle . '/controller');
        $files = [];
        foreach ($dir_list as $v) {
            if ($v == '.' || $v == '..') {
                continue;
            }
            $files[] = strstr($v, '.', true);
        }

        return $files;
    }

    static public function getFunction($moudle, $controller)
    {
        $classDir = 'app\\' . $moudle . '\controller\\' . $controller;
        $reflectionClass = new \ReflectionClass($classDir);
        $functions = $reflectionClass->getMethods(\ReflectionMethod::IS_PUBLIC);

        return $functions;
    }

    static public function checkRolePerm($perm, $roleId)
    {
        if ($perm == '') {
            return true;
        }

        $permModel = new AdminRoleModel();
        $perms = $permModel->where('id', $roleId)->value('perms');
        $permsArr = explode(',', $perms);
        if (in_array($perm, $permsArr)) {

            return true;
        }

        return false;
    }
}