<?php
use think\db;
//获取指定长度随机字符串
function getRandStr($len=6){
	$arr=array_merge(range(0,9),range("a","z"),range("A","Z"));
	shuffle($arr);//将数组打乱
	$subarr=array_slice($arr, 0,$len);
	return implode(",", $subarr);
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

function checkRolePerm($perm,$id)
{
	return \app\admin\logic\PrivilegeLogic::checkRolePerm($perm, $id);
}
