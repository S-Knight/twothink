<?php

namespace app\common\logic;

use app\common\model\ConfigModel;

class ConfigLogic extends Logic
{
    static public function getConfig($name)
    {
        $systemConfigModel = new ConfigModel();

        return $systemConfigModel->where(['name' => $name])->value('value');
    }

    static public function setConfig($name, $value)
    {
        $systemConfigModel = new ConfigModel();

        return $systemConfigModel->save(['value' => $value], ['name' => $name]);
    }
}