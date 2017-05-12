<?php

namespace app\common\logic;
use app\common\logic\ConfigLogic;
use think\Log;
use think\Request;

define('ACCOUNT_PLATFORM_API_ACCESSTOKEN',
    'https://api.weixin.qq.com/cgi-bin/component/api_component_token');
define('ACCOUNT_PLATFORM_API_PREAUTHCODE',
    'https://api.weixin.qq.com/cgi-bin/component/api_create_preauthcode?component_access_token=');
define('ACCOUNT_PLATFORM_API_LOGIN',
    'https://mp.weixin.qq.com/cgi-bin/componentloginpage?component_appid=%s&pre_auth_code=%s&redirect_uri=%s');
define('ACCOUNT_PLATFORM_API_QUERY_AUTH_INFO',
    'https://api.weixin.qq.com/cgi-bin/component/api_query_auth?component_access_token=');
define('ACCOUNT_PLATFORM_API_ACCOUNT_INFO',
    'https://api.weixin.qq.com/cgi-bin/component/api_get_authorizer_info?component_access_token=');
define('ACCOUNT_PLATFORM_API_REFRESH_AUTH_ACCESSTOKEN',
    'https://api.weixin.qq.com/cgi-bin/component/api_authorizer_token?component_access_token=');
define('ACCOUNT_PLATFORM_API_OAUTH_CODE',
    'https://open.weixin.qq.com/connect/oauth2/authorize?appid=%s&component_appid=%s&redirect_uri=%s&response_type=code&scope=snsapi_base&state=%s#wechat_redirect');
define('ACCOUNT_PLATFORM_API_OAUTH_USERINFO',
    'https://open.weixin.qq.com/connect/oauth2/authorize?appid=%s&redirect_uri=%s&response_type=code&scope=snsapi_userinfo&state=%s&component_appid=%s#wechat_redirect');
define('ACCOUNT_PLATFORM_API_OAUTH_INFO',
    'https://api.weixin.qq.com/sns/oauth2/component/access_token?appid=%s&component_appid=%s&code=%s&grant_type=authorization_code&component_access_token=');

class WeixinPlatformLogic extends Logic
{
    public $appid;
    public $appsecret;
    public $encodingaeskey;
    public $token;
    public $refreshtoken;
    public $account;

    function __construct($account = array())
    {
        $this->appid = ConfigLogic::getConfig('COMPONENT_APPID');
        $this->appsecret = ConfigLogic::getConfig('COMPONENT_APPSECRET');
        $this->token = ConfigLogic::getConfig('WECHAT_OAUTH_TOKEN');
        $this->encodingaeskey = ConfigLogic::getConfig('WECHAT_ENCODINGAESKEY');
        $this->account = $account;
        $this->account['account_appid'] = isset($this->account['key']) ? $this->account['key'] : '';
        $this->account['key'] = $this->appid;
    }

    function getComponentAccesstoken()
    {
        $accesstoken = cache('account:component:assesstoken');

        if (empty($accesstoken) || empty($accesstoken['value']) || $accesstoken['expire'] < time()) {

            $ticket = ConfigLogic::getConfig('WECHAT_OAUTH_TICKET');
            if (empty($ticket)) {
                exit('缺少接入平台关键数据，等待微信开放平台推送数据，请十分钟后再试或是检查“授权事件接收URL”是否写错（index.php?c=account&amp;a=auth&amp;do=ticket地址中的&amp;符号容易被替换成&amp;amp;）');
            }

            $data = array(
                'component_appid' => $this->appid,
                'component_appsecret' => $this->appsecret,
                'component_verify_ticket' => $ticket,
            );
            $response = $this->request(ACCOUNT_PLATFORM_API_ACCESSTOKEN, $data);

            if (is_error($response)) {
                $errormsg = $this->error_code($response['errno'],
                    $response['message']);

                exit($response['errno'] . $errormsg);
            }
            $accesstoken = array(
                'value' => $response['component_access_token'],
                'expire' => time() + intval($response['expires_in']),
            );

            cache('account:component:assesstoken', $accesstoken);
        }

        return $accesstoken['value'];
    }

    function getPreauthCode()
    {
        $component_accesstoken = $this->getComponentAccesstoken();
        if (is_error($component_accesstoken)) {
            return $component_accesstoken;
        }
        $data = array(
            'component_appid' => $this->appid
        );
        $response = $this->request(ACCOUNT_PLATFORM_API_PREAUTHCODE . $component_accesstoken,
            $data);

        if (is_error($response)) {
            return $response;
        }
        $preauthcode = array(
            'value' => $response['pre_auth_code'],
            'expire' => time() + intval($response['expires_in']),
        );

        return $preauthcode['value'];
    }

    public function getAuthInfo($code)
    {
        $component_accesstoken = $this->getComponentAccesstoken();
        if (is_error($component_accesstoken)) {
            return $component_accesstoken;
        }
        $post = array(
            'component_appid' => $this->appid,
            'authorization_code' => $code,
        );
        $response = $this->request(ACCOUNT_PLATFORM_API_QUERY_AUTH_INFO . $component_accesstoken,
            $post);
        if (is_error($response)) {
            return $response;
        }
        $this->setAuthRefreshToken($response['authorization_info']['authorizer_refresh_token']);

        return $response;
    }

    public function getAccountInfo($appid = '')
    {
        $component_accesstoken = $this->getComponentAccesstoken();
        if (is_error($component_accesstoken)) {
            return $component_accesstoken;
        }
        $appid = !empty($appid) ? $appid : $this->account['account_appid'];
        $post = array(
            'component_appid' => $this->appid,
            'authorizer_appid' => $appid,
        );
        $response = $this->request(ACCOUNT_PLATFORM_API_ACCOUNT_INFO . $component_accesstoken,
            $post);
        if (is_error($response)) {
            return $response;
        }

        return $response;
    }

    public function getAccessToken()
    {
        $cachename = 'account:auth:accesstoken:';
        $auth_accesstoken = cache($cachename);
        if (empty($auth_accesstoken) || empty($auth_accesstoken['value']) || $auth_accesstoken['expire'] < TIMESTAMP) {
            $component_accesstoken = $this->getComponentAccesstoken();
            if (is_error($component_accesstoken)) {
                return $component_accesstoken;
            }
            $this->refreshtoken = $this->getAuthRefreshToken();
            $data = array(
                'component_appid' => $this->appid,
                'authorizer_appid' => ConfigLogic::getConfig('WECHAT_APPID'),
                'authorizer_refresh_token' => $this->refreshtoken,
            );

            Log::write("invoke getAccessToken at " . date("Y-m-d H:i:s"));
            $response = $this->request(ACCOUNT_PLATFORM_API_REFRESH_AUTH_ACCESSTOKEN . $component_accesstoken,
                $data);
            if (is_error($response)) {
                return $response;
            }

            if ($response['authorizer_refresh_token'] != $this->refreshtoken) {
                $this->setAuthRefreshToken($response['authorizer_refresh_token']);
            }
            $auth_accesstoken = array(
                'value' => $response['authorizer_access_token'],
                'expire' => time() + intval($response['expires_in']),
            );
            cache($cachename,$auth_accesstoken);
        }
        return $auth_accesstoken['value'];
    }

    public function fetch_token()
    {
        return $this->getAccessToken();
    }

    public function getAuthLoginUrl()
    {
        $preauthcode = $this->getPreauthCode();
        if (is_error($preauthcode)) {
            $authurl = "javascript:alert('{$preauthcode['message']}');";
        } else {
            $request = Request::instance();
            $authurl = sprintf(ACCOUNT_PLATFORM_API_LOGIN, $this->appid,
                $preauthcode,
                urlencode($request->domain() . '/index.php/admin/WechatPlatform/authReturn'));
        }

        return $authurl;
    }

    public function getOauthCodeUrl($callback, $state = '')
    {
        return sprintf(ACCOUNT_PLATFORM_API_OAUTH_CODE,
            $this->account['account_appid'], $this->appid, $callback, $state);
    }

    public function getOauthUserInfoUrl($callback, $state = '')
    {
        return sprintf(ACCOUNT_PLATFORM_API_OAUTH_USERINFO,
            $this->account['account_appid'], $callback, $state, $this->appid);
    }

    public function getOauthInfo($code = '')
    {
        $component_accesstoken = $this->getComponentAccesstoken();
        if (is_error($component_accesstoken)) {
            return $component_accesstoken;
        }
        $apiurl = sprintf(ACCOUNT_PLATFORM_API_OAUTH_INFO . $component_accesstoken,
            $this->account['account_appid'], $this->appid, $code);
        $response = $this->request($apiurl);
        if (is_error($response)) {
            return $response;
        }

        return $response;
    }

    public function getJsApiTicket()
    {
        if (empty($js_ticket) || empty($js_ticket['value']) || $js_ticket['expire'] < time()) {
            $access_token = $this->getAccessToken();
            if (is_error($access_token)) {
                return $access_token;
            }
            $apiurl = "https://api.weixin.qq.com/cgi-bin/ticket/getticket?access_token={$access_token}&type=jsapi";
            $response = $this->request($apiurl);
            $js_ticket = array(
                'value' => $response['ticket'],
                'expire' => time() + $response['expires_in'] - 200,
            );
        }
        $this->account['jsapi_ticket'] = $js_ticket;

        return $js_ticket['value'];
    }

    public function getJssdkConfig()
    {
        global $_W;
        $jsapiTicket = $this->getJsApiTicket();
        if (is_error($jsapiTicket)) {
            $jsapiTicket = $jsapiTicket['message'];
        }
        $nonceStr = random(16);
        $timestamp = time();
        $url = $_W['siteurl'];
        $string1 = "jsapi_ticket={$jsapiTicket}&noncestr={$nonceStr}&timestamp={$timestamp}&url={$url}";
        $signature = sha1($string1);
        $config = array(
            "appId" => $this->account['account_appid'],
            "nonceStr" => $nonceStr,
            "timestamp" => "$timestamp",
            "signature" => $signature,
        );

        /* if (DEVELOPMENT) {
             $config['url'] = $url;
             $config['string1'] = $string1;
             $config['name'] = $this->account['name'];
         }*/

        return $config;
    }

    public function clearComponentQuota()
    {
        $apiurl = "https://api.weixin.qq.com/cgi-bin/component/clear_quota?component_access_token=" . $this->getComponentAccesstoken();
        $post = [];
        $post['component_appid'] =  ConfigLogic::getConfig('COMPONENT_APPID');

        $response = $this->request($apiurl, $post);

        if (is_error($response)) {
            return $response;
        }

        return $response;
    }

    private function request($url, $post = array())
    {
        $response = ihttp_request($url, json_encode($post));
        $response = json_decode($response['content'], true);
        if (empty($response) || !empty($response['errcode'])) {
            exit($response['errcode'] . $this->error_code($response['errcode'],
                    $response['errmsg']));
        }
        return $response;
    }

    private function getAuthRefreshToken()
    {
        $auth_refresh_token = ConfigLogic::getConfig('AUTHORIZER_REFRESH_TOKEN');

        return $auth_refresh_token;
    }

    private function setAuthRefreshToken($token)
    {
        ConfigLogic::setConfig('AUTHORIZER_REFRESH_TOKEN',$token);
    }

    public function error_code($code, $errmsg = '未知错误')
    {
        $errors = array(
            '-1' => '系统繁忙',
            '0' => '请求成功',
            '40001' => '获取access_token时AppSecret错误，或者access_token无效',
            '40002' => '不合法的凭证类型',
            '40003' => '不合法的OpenID',
            '40004' => '不合法的媒体文件类型',
            '40005' => '不合法的文件类型',
            '40006' => '不合法的文件大小',
            '40007' => '不合法的媒体文件id',
            '40008' => '不合法的消息类型',
            '40009' => '不合法的图片文件大小',
            '40010' => '不合法的语音文件大小',
            '40011' => '不合法的视频文件大小',
            '40012' => '不合法的缩略图文件大小',
            '40013' => '不合法的APPID',
            '40014' => '不合法的access_token',
            '40015' => '不合法的菜单类型',
            '40016' => '不合法的按钮个数',
            '40017' => '不合法的按钮个数',
            '40018' => '不合法的按钮名字长度',
            '40019' => '不合法的按钮KEY长度',
            '40020' => '不合法的按钮URL长度',
            '40021' => '不合法的菜单版本号',
            '40022' => '不合法的子菜单级数',
            '40023' => '不合法的子菜单按钮个数',
            '40024' => '不合法的子菜单按钮类型',
            '40025' => '不合法的子菜单按钮名字长度',
            '40026' => '不合法的子菜单按钮KEY长度',
            '40027' => '不合法的子菜单按钮URL长度',
            '40028' => '不合法的自定义菜单使用用户',
            '40029' => '不合法的oauth_code',
            '40030' => '不合法的refresh_token',
            '40031' => '不合法的openid列表',
            '40032' => '不合法的openid列表长度',
            '40033' => '不合法的请求字符，不能包含\uxxxx格式的字符',
            '40035' => '不合法的参数',
            '40038' => '不合法的请求格式',
            '40039' => '不合法的URL长度',
            '40050' => '不合法的分组id',
            '40051' => '分组名字不合法',
            '41001' => '缺少access_token参数',
            '41002' => '缺少appid参数',
            '41003' => '缺少refresh_token参数',
            '41004' => '缺少secret参数',
            '41005' => '缺少多媒体文件数据',
            '41006' => '缺少media_id参数',
            '41007' => '缺少子菜单数据',
            '41008' => '缺少oauth code',
            '41009' => '缺少openid',
            '42001' => 'access_token超时',
            '42002' => 'refresh_token超时',
            '42003' => 'oauth_code超时',
            '43001' => '需要GET请求',
            '43002' => '需要POST请求',
            '43003' => '需要HTTPS请求',
            '43004' => '需要接收者关注',
            '43005' => '需要好友关系',
            '44001' => '多媒体文件为空',
            '44002' => 'POST的数据包为空',
            '44003' => '图文消息内容为空',
            '44004' => '文本消息内容为空',
            '45001' => '多媒体文件大小超过限制',
            '45002' => '消息内容超过限制',
            '45003' => '标题字段超过限制',
            '45004' => '描述字段超过限制',
            '45005' => '链接字段超过限制',
            '45006' => '图片链接字段超过限制',
            '45007' => '语音播放时间超过限制',
            '45008' => '图文消息超过限制',
            '45009' => '接口调用超过限制',
            '45010' => '创建菜单个数超过限制',
            '45015' => '回复时间超过限制',
            '45016' => '系统分组，不允许修改',
            '45017' => '分组名字过长',
            '45018' => '分组数量超过上限',
            '45056' => '创建的标签数过多，请注意不能超过100个',
            '45057' => '该标签下粉丝数超过10w，不允许直接删除',
            '45058' => '不能修改0/1/2这三个系统默认保留的标签',
            '45059' => '有粉丝身上的标签数已经超过限制',
            '45157' => '标签名非法，请注意不能和其他标签重名',
            '45158' => '标签名长度超过30个字节',
            '45159' => '非法的标签',
            '46001' => '不存在媒体数据',
            '46002' => '不存在的菜单版本',
            '46003' => '不存在的菜单数据',
            '46004' => '不存在的用户',
            '47001' => '解析JSON/XML内容错误',
            '48001' => 'api功能未授权',
            '50001' => '用户未授权该api',
            '40070' => '基本信息baseinfo中填写的库存信息SKU不合法。',
            '41011' => '必填字段不完整或不合法，参考相应接口。',
            '40056' => '无效code，请确认code长度在20个字符以内，且处于非异常状态（转赠、删除）。',
            '43009' => '无自定义SN权限，请参考开发者必读中的流程开通权限。',
            '43010' => '无储值权限,请参考开发者必读中的流程开通权限。',
            '43011' => '无积分权限,请参考开发者必读中的流程开通权限。',
            '40078' => '无效卡券，未通过审核，已被置为失效。',
            '40079' => '基本信息base_info中填写的date_info不合法或核销卡券未到生效时间。',
            '45021' => '文本字段超过长度限制，请参考相应字段说明。',
            '40080' => '卡券扩展信息cardext不合法。',
            '40097' => '基本信息base_info中填写的参数不合法。',
            '49004' => '签名错误。',
            '43012' => '无自定义cell跳转外链权限，请参考开发者必读中的申请流程开通权限。',
            '40099' => '该code已被核销。',
            '61005' => '缺少接入平台关键数据，等待微信开放平台推送数据，请十分钟后再试或是检查“授权事件接收URL”是否写错（index.php?c=account&amp;a=auth&amp;do=ticket地址中的&amp;符号容易被替换成&amp;amp;）',
            '61023' => '请重新授权接入该公众号',
        );
        $code = strval($code);
        if ($code == '40001' || $code == '42001') {
            return '微信公众平台授权异常, 系统已修复这个错误, 请刷新页面重试.';
        }
        if ($errors[$code]) {
            return $errors[$code];
        } else {
            return $errmsg;
        }
    }
}