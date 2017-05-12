<?php
namespace app\admin\controller;
use app\common\model\UcenterMemberModel;
use app\common\model\MemberModel;
use app\admin\logic\AccountLogic;
use app\admin\logic\UcenterMemberLogic;
use \PHPExcel;
use \PHPExcel_IOFactory;
use \PHPExcel_Reader_Excel5;
use think\Db;
use think\Request;
class UcenterMember extends Admin{

    public function index(){
        if(request()->isAjax()){
            return $this->getRecords();
        }else{
            $this->assign('title','前台用户列表');
            return $this->fetch('UcenterMember/index');
        }
    }

    public function add() {
        if(request()->isPost()){
            return $this->addPost();
        }else{
            $this->view->assign('title', '添加网站用户');
            return $this->view->fetch('UcenterMember/add');
        }
    }

    /*功能：添加配置*/
    private function addPost() {
        $data = $_POST;
        /*校验：是否重复提交*/
        $checkUsername = UcenterMemberLogic::checkUsername($data['username']);
        if ($checkUsername) {
            return array('success'=>false,"info"=>"该用户名已存在");
        }
        $checkEmail = UcenterMemberLogic::checkEmail($data['email']);
        if ($checkEmail) {
            return array('success'=>false,"info"=>"该邮箱已存在");
        }
        $checkMobile = UcenterMemberLogic::checkMobile($data['mobile']);
        if ($checkMobile) {
            return array('success'=>false,"info"=>"该手机号已存在");
        }

        $data['salt'] = md5(date('Y-m-d H:i:s'));
        $data['password'] = AccountLogic::encodePassword($data['password'], $data['salt']);
        $UcenterMemberModel = new UcenterMemberModel();
        $result = $UcenterMemberModel->allowField(true)->save($data);
        $uid = $UcenterMemberModel->getLastInsID();
        MemberModel::create([
            'uid' => $uid,
            'real_name' => $data['real_name'],
        ]);
        if($result !== false){
            return array('success'=>true,"info"=>"操作成功");
        }else{
            return array('success'=>false,"info"=>"操作失败");
        }
    }

    public function edit($id) {
        if(request()->isPost()){
            return $this->editPost();
        }else{
            $row = UcenterMemberModel::get($id);
            $member = MemberModel::get(['uid'=>$id]);
            $this->assign('title', '编辑网站用户信息');
            $this->assign('row', $row);
            $this->assign('member', $member);
            return $this->view->fetch('UcenterMember/edit');
        }
    }

    private function editPost() {
        $UcenterMemberModel = new UcenterMemberModel();
        $result = $UcenterMemberModel->allowField(true)->save($_POST,['id'=>$_POST['id']]);
        $MemberModel = new MemberModel();
        $MemberModel->allowField(true)->save($_POST,['uid'=>$_POST['id']]);

        if($result === false){
            return array('success'=>false,"info"=>"操作失败");
        }else{
            return array('success'=>true,"info"=>"操作成功");
        }
    }

    private function getRecords() {
        $records = array();
        $ucenterMemberModel = new UcenterMemberModel();
        $start = input('post.start', 0);
        $length = input('post.length', 20);

        $columns = input('post.columns/a');
        $orderColumns = input('post.order/a');
        $orders = [];
        foreach ($orderColumns as $orderColumn) {
            $orders[$columns[$orderColumn['column']]['data']] = $orderColumn['dir'];
        }

        $condition = [];
        $username = input('post.username', '');
        if ($username) {
            $condition['username'] = array('like', "%$username%");
        }
        $status = input('post.status', '');
        if ($status != '') {
            $condition['status'] = $status;
        }

        $startTime = strtotime(input('start_time'));
        $endTime = strtotime(input('end_time'));
        if(!$startTime && $endTime){
            $condition['reg_time'] = ['<=',$endTime];
        }elseif ($startTime && !$endTime){
            $condition['reg_time'] = ['>=',$startTime];
        }elseif ($startTime && $endTime){
            $condition['reg_time'] = ['between',[$startTime,$endTime]];
        }

        $records["data"] = $ucenterMemberModel
            ->where($condition)->limit($start,$length)->order($orders)->select();
        $records["recordsTotal"] = $ucenterMemberModel->where($condition)->count();
        $records["recordsFiltered"] = $records["recordsTotal"];
        $records['draw'] = input('post.draw', 1);
        foreach ($records["data"] as &$row) {
            $row['selectDOM'] = '<label class="mt-checkbox mt-checkbox-single mt-checkbox-outline"><input name="id[]" type="checkbox" class="checkboxes" value="' . $row['id'] . '"/><span></span></label>';
            $row['status'] = $row['status'] == 0 ? '禁用' : '启用';
            $row['last_login_time'] = date('Y-m-d H:i:s',$row['last_login_time']);
           
        }
        return $records;
    }

    public function delete($id){
        if (Request::instance()->isAjax()){
            $res = UcenterMemberModel::destroy($id);
            if($res){
                return array('success'=>true,"info"=>"操作成功");
            }else{
                return array('success'=>false,"info"=>"操作失败");
            }
        }
    }

    public function export()
    {
        ini_set('max_execution_time','300');
        $excel = new \PHPExcel();
        //A B C D E F G H I J K L M N O P Q R S T U V W X Y Z
        $letter = array('A');
        $tableheader = array('用户名');

        for($i = 0;$i < count($tableheader);$i++) {
            $excel->getActiveSheet()->setCellValue("$letter[$i]1","$tableheader[$i]");
        }

        $ucenterMemberModel = new UcenterMemberModel();
        $condition = [];

        $username = input('post.username', '');
        if ($username) {
            $condition['username'] = array('like', "%$username%");
        }
        $status = input('post.status', '');
        if ($status != '') {
            $condition['status'] = $status;
        }

        $startTime = strtotime(input('start_time'));
        $endTime = strtotime(input('end_time'));
        if(!$startTime && $endTime){
            $condition['reg_time'] = ['<=',$endTime];
        }elseif ($startTime && !$endTime){
            $condition['reg_time'] = ['>=',$startTime];
        }elseif ($startTime && $endTime){
            $condition['reg_time'] = ['between',[$startTime,$endTime]];
        }

        $data = $ucenterMemberModel->where($condition)
            ->field('username')
            ->order('reg_time desc')->select();
        if(count($data) < 1){
            die("<script>alert('信息少于1条，无法导出');history.go(-1)</script>");
        }
        //填充表格信息
        for ($i = 2;$i <= count($data) + 1;$i++) {
            $j = 0;
            foreach ($data[$i - 2]->toArray() as $key=>$value) {
                $excel->getActiveSheet()->setCellValue("$letter[$j]$i","$value");
                $j++;
            }
        };

        $write = new \PHPExcel_Writer_Excel5($excel);
        $fileName = '用户'.date('Y-m-d H:i:s').'.xls';
        header("Pragma: public");
        header("Expires: 0");
        header("Cache-Control:must-revalidate, post-check=0, pre-check=0");
        header("Content-Type:application/force-download");
        header("Content-Type:application/vnd.ms-execl");
        header("Content-Type:application/octet-stream");
        header("Content-Type:application/download");
        header('Content-Disposition:attachment;filename="'. $fileName .'"');
        header("Content-Transfer-Encoding:binary");
        $write->save('php://output');
    }


    public function import()
    {
        ini_set('max_execution_time','300');
        $file = request()->file('Filedata');
        $info = $file->move(ROOT_PATH . 'public' . DS . 'ucenterMember');
        if($info){
            $rjson['success'] = true;
            $imgUrl = $info -> getSaveName();
            $fileName = str_replace('\\','/','public/ucenterMember/'.$imgUrl);
            $fileName = ROOT_PATH . $fileName;
            chmod($fileName,0777);
            $importMySqlInfo = $this->importMySql($fileName);
            $rjson['info'] = $importMySqlInfo;
        }else{
            $rjson['success'] = false;
            $rjson['info'] = $file -> getError();
        }
        return $rjson;
    }

    protected function importMySql($fileName)
    {

        $objReader = PHPExcel_IOFactory::createReader('Excel5');
        $filename = $fileName;
        $objPHPExcel = $objReader->load($filename);
        $sheet = $objPHPExcel->getSheet(0);
        $highestRow = $sheet->getHighestRow();
        $highestColumn = $sheet->getHighestColumn();
        $k = 0;
        $time = time();
        $list = [];
        for($j=2;$j<=$highestRow;$j++)                        //从第二行开始读取数据
        {
            $str="";
            for($k='A';$k<=$highestColumn;$k++)
            {
                $str .=$objPHPExcel->getActiveSheet()->getCell("$k$j")->getValue().'|*|';//读取单元格
            }
            $str=mb_convert_encoding($str,'utf8','auto');//根据自己编码修改
            $strs = explode("|*|",$str);

            $list[] = [
                'username'              =>  $strs[0],
                'reg_time'        =>  $time,
            ];
            /*$sql = "insert into geek_sample (name,sample_class,province,city,county,longitude,latitude,content,contacts,contact_phone,contact_position,contact_mobile,can_evaluate,year,is_main,created_at) values ('{$strs[0]}','{$strs[1]}','{$strs[2]}','{$strs[3]}','{$strs[4]}','{$strs[5]}','{$strs[6]}','{$strs[7]}','{$strs[8]}','{$strs[9]}','{$strs[10]}','{$strs[11]}','{$strs[12]}','{$strs[13]}','{$strs[14]}','{$time}')";
            Db::query($sql);*/
        }
        $ucenterMemberModel = new UcenterMemberModel();
        $ucenterMemberModel->saveAll($list);
        return '导入成功';
    }

}