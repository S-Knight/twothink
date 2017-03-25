<?php
namespace app\admin\controller;

use think\Controller;
use think\Request;
class Upload extends Controller
{
	/**
	 *图片上传
	 */
	public function uploadify(){
		$rjson = array();
	
		// 获取表单上传文件 例如上传了001.jpg
		$file = request()->file('Filedata');
	
		// 移动到框架应用根目录/public/uploads/ 目录下
		$info = $file->move(ROOT_PATH . 'public' . DS . 'uploads');
		if($info){
			$rjson['success'] = true;
			$imgUrl = $info -> getSaveName();
			$rjson['data']['savePath'] = '/uploads/'.$imgUrl;
		}else{
			$rjson['success'] = false;
			$rjson['data'] = $file -> getError();
		}
		return json_encode($rjson);
	}

	/**
	 * base64编码图片上传
	 * @param $imgresult
	 * @return string
	 */
	public function baseUpload($imgresult)
	{
		$url = explode(',' ,$imgresult);
		$img = base64_decode($url[1]);
		$savePath = date('Ymd');
		$filename = md5(date('YmdHis').rand(1000,9999));
		$path = ROOT_PATH . 'public' . DS . 'uploads/'.$savePath;
		if(!is_dir($path)){
			mkdir($path);
		}
		file_put_contents($path."/".$filename.'.jpg', $img);//返回的是字节数
		$url = '/uploads/' . $savePath."/".$filename.'.jpg';
		return $url;
	}
}