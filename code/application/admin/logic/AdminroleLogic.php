<?php

namespace app\admin\logic;
use app\admin\model\PrivilegeModel;
class AdminroleLogic extends Logic{
    static public function getPrivilege()
    {
        $privilegeModel = new PrivilegeModel();
        $moudles = $privilegeModel->group('moudle')->select();

        $moudleArr = [];
        foreach ($moudles as $moudle){

            $moudleArr[$moudle['moudle']]['text'] = $moudle['moudle'];
            $controllers = $privilegeModel->where('moudle',$moudle['moudle'])->group('controller')
                ->select();
            foreach ($controllers as $controller){
                $functions = $privilegeModel->where('controller',$controller['controller'])->select();

                //降维
                $functionArr = [];
                foreach ($functions as $function){
                    $functionArr['text'] = $controller['controller'];
                    $functionArr[$function['function']] = $function['name'];
                }
                $moudleArr[$moudle['moudle']]['child'][$controller['controller']] = $functionArr;
            }

        }
        return $moudleArr;
    }
    
}