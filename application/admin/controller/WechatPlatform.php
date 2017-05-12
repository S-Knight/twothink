<?php

namespace app\admin\controller;

use app\common\logic\WeixinPlatformLogic;
use think\Controller;

class WechatPlatform extends Controller
{
    public function auth()
    {
        $weixinPlatformLogic = new WeixinPlatformLogic();
        $authLoginUrl = $weixinPlatformLogic->getAuthLoginUrl();

        $this->assign('authLoginUrl', $authLoginUrl);

        return $this->fetch('WechatPlatform/auth');
    }

    public function authReturn()
    {
        $weixinPlatformLogic = new WeixinPlatformLogic();

        $_GPC['auth_code'] = input('param.auth_code');
        if (empty($_GPC['auth_code'])) {
            $this->error('授权登录失败，请重试', 'admin/Index/index');
        }
        $auth_info = $weixinPlatformLogic->getAuthInfo($_GPC['auth_code']);
        $auth_appid = $auth_info['authorization_info']['authorizer_appid'];
        $account_info = $weixinPlatformLogic->getAccountInfo($auth_appid);
        if (!empty(input('param.test'))) {
            echo "此为测试平台接入返回结果：<br/> 公众号名称：{$account_info['authorizer_info']['nick_name']} <br/> 接入状态：成功";
            exit;
        }

        $this->success('授权登录成功', 'admin/Index/index');
    }
}
