<?php

namespace app\admin\logic;

use app\admin\model\UcenterAdminModel;

class AccountLogic extends Logic
{
    private $error;

    public function checkLogin($username, $password)
    {
        $member = UcenterAdminModel::get(['username' => $username]);
        if (!$member || $member['status'] == -1) {
            $this->error = '用户不存在';
            return false;
        }

        $encodePassword = self::encodePassword($password, $member['salt']);
        if ($member['password'] !== $encodePassword) {
            $this->error = '密码错误';
            return false;
        }

        if ($member['status'] == 0) {
            $this->error = '该用户已被禁用';
            return false;
        }

        $ucenterAdminModel = new UcenterAdminModel();
        $ucenterAdminModel->save([
            'last_login_time' => date('Y-m-d h:i:s'),
            'last_login_ip' => get_client_ip()
        ], ['id' => $member['id']]);
        session('admin', $member->toArray());
        return true;
    }

    public function getError()
    {
        return $this->error;
    }

    /**
     * 功能:对用户输入的明文密码进行加密(还记不记得csdn的杯具)
     * @param string $password 明文密码
     * @param string $salt 混淆字符串（盐）,防止简单的MD5加密被逆向破解
     * @return string   加密后的密码
     */
    static public function encodePassword($password, $salt)
    {
        $encryptd_password = md5(md5($password) . $salt);

        return substr($encryptd_password, 0, 32);
    }
}