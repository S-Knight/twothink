<?php
namespace app\common\logic;

class CommonLogic extends Logic
{
    static public function mergeCate($arr,$pidName,$parent_id=0,$level=0){
        $res=array();
        foreach($arr as $v){
            if($v[$pidName]==$parent_id){
                $v['disabled']=false;
                foreach($arr as $v1){
                    if($v1[$pidName]==$v['id']){
                        $v['disabled']=true;
                        break;
                    }
                }

                $v['level']=$level;
                $res[]=$v;
                $res=array_merge($res,self::mergeCate($arr,$pidName,$v['id'],$level+1));
            }
        }
        return $res;
    }
}