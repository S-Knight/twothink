<?php
namespace app\admin\controller;
use think\Request;
use think\Db;

class Database extends Admin
{
    public function backup()
    {
        $this->assign('do','backup');
        return view('Database/backup');
    }

    public function optimize()
    {
        if(request()->isAjax()){
            $data = input('post.');
            foreach($data['select'] as $t) {
                if(!empty($ds[$t])) {
                    $sql = "OPTIMIZE TABLE {$t}";
                    Db::query($sql);
                }
            }
            return ['success'=> true,'info'=>'数据库优化成功'];
        }else{
            $sql = "SHOW TABLE STATUS LIKE 'geek_%'";
            $tables = Db::query($sql);
            $totalsize = 0;
            $ds = array();
            foreach($tables as $ss) {
             /*   if ($ss['Engine'] == 'InnoDB') {
                    continue;
                }*/
                if(!empty($ss) && !empty($ss['Data_free'])) {
                    $row = array();
                    $row['title'] = $ss['Name'];
                    $row['type'] = $ss['Engine'];
                    $row['rows'] = $ss['Rows'];
                    $row['data'] = sizecount($ss['Data_length']);
                    $row['index'] = sizecount($ss['Index_length']);
                    $row['free'] = sizecount($ss['Data_free']);
                    $ds[$row['title']] = $row;
                }
            }
            $this->assign('do','optimize');
            $this->assign('ds',$ds);
            return view('Database/optimize');
        }
    }
}