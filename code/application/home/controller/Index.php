<?php
namespace app\home\controller;
use think\Controller;
use think\Db;
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/8/11
 * Time: 15:27
 */
class Index extends Controller
{
		public function index(){

		    	$view = new \think\View();
		    	return $view->fetch();
		}
}