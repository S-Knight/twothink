<?php
use think\db;
//获取指定长度随机字符串
function getRandStr($len=6){
	$arr=array_merge(range(0,9),range("a","z"),range("A","Z"));
	shuffle($arr);//将数组打乱
	$subarr=array_slice($arr, 0,$len);
	return implode(",", $subarr);
}

//无极分类  找儿子  传父类ID
function mergeCate($arr,$parent_id=0,$level=0){
	$res=array();
	foreach($arr as $v){
		if($v['parent_id']==$parent_id){
			$v['disabled']=false;
			foreach($arr as $v1){
				if($v1['parent_id']==$v['cat_id']){
					$v['disabled']=true;
					break;
				}
			}
				
			$v['level']=$level;
			$res[]=$v;
			$res=array_merge($res,mergeCate($arr,$v['cat_id'],$level+1));
		}
	}
	return $res;
}

//所属分类
function superior($pid,$table='geek_menu'){
	$row=Db::table($table)->where('id',$pid)->value('title');
	if($row){
		return $row;
	}else{
		return '顶级栏目';
	}
}

//配置设置的分组和类型
//$type 1 为分组
//$type 2 为类型
function Grouping_type($number,$type){
	$value='';
	if($type==1){
		switch ($number)
		{
			case 0:
				$value='不分组';
				break;
			case 1:
				$value='基本';
				break;
			case 2:
				$value='内容';
				break;
			case 3:
				$value='用户';
				break;
			case 4:
				$value='系统';
				break;
			case 5:
				$value='邮件';
				break;
		}
	}else{
		switch ($number)
		{
			case 1:
				$value='文本';
				break;
			case 2:
				$value='上传';
				break;
			case 3:
				$value='多行文本';
				break;
		}
	}
	return $value;
}
