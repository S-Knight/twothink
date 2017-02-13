<?php

namespace app\admin\logic;
use app\common\model\UcenterMemberModel;
class UcenterMemberLogic extends Logic
{
    static public function checkUsername($username)
    {
        $check = UcenterMemberModel::get(['username'=>$username]);
        return $check ? true : false;
    }

    static public function checkEmail($email)
    {
        $check = UcenterMemberModel::get(['email'=>$email]);
        return $check ? true : false;
    }

    static public function checkMobile($mobile)
    {
        $check = UcenterMemberModel::get(['mobile'=>$mobile]);
        return $check ? true : false;
    }
}