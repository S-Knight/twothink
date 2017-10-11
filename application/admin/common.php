<?php
use think\db;

//获取指定长度随机字符串
function getRandStr($len = 6)
{
    $arr = array_merge(range(0, 9), range("a", "z"), range("A", "Z"));
    shuffle($arr);//将数组打乱
    $subarr = array_slice($arr, 0, $len);

    return implode(",", $subarr);
}

//所属分类
function superior($pid, $table = 'horui_menu')
{
    $row = Db::table($table)->where('id', $pid)->value('title');
    if ($row) {
        return $row;
    } else {
        return '顶级栏目';
    }
}

//配置设置的分组和类型
function systemGroup($number, $type)
{
    $value = '';
    if ($type == 1) {
        switch ($number) {
            case 0:
                $value = '不分组';
                break;
            case 1:
                $value = '基本';
                break;
            case 2:
                $value = '系统';
                break;
            case 3:
                $value = '邮件';
                break;
        }
    } else {
        switch ($number) {
            case 1:
                $value = '文本';
                break;
            case 2:
                $value = '上传';
                break;
            case 3:
                $value = '富文本';
                break;
            case 4:
                $value = '单选';
                break;
            case 5:
                $value = '多选';
                break;
            case 6:
                $value = '多行文本框';
                break;
        }
    }

    return $value;
}

function richText($name)
{
    $html = <<<TexT
	<link rel="stylesheet" href="/static/kindeditor/themes/default/default.css" />
	<script charset="utf-8" src="/static/kindeditor/kindeditor-all-min.js"></script>
	<script charset="utf-8" src="/static/kindeditor/lang/zh-CN.js"></script>
	<script type="text/javascript">
		var editor;
		KindEditor.ready(function(K) {
			editor = K.create('textarea[name="{$name}"]', {
				resizeType : 1,
				allowPreviewEmoticons : false,
				allowImageUpload : true,
			});
		});
	</script>

TexT;

    return $html;
}

function checkRolePerm($perm, $id)
{
    return \app\admin\logic\PrivilegeLogic::checkRolePerm($perm, $id);
}

function newRichText($name, $value = '')
{
    $a = <<<TexT
<script id="{$name}" name='{$name}' type="text/plain" style='width:700px;'></script>
<script type="text/javascript">
    var ue{$name} = UE.getEditor('{$name}');
    ue{$name}.ready(function() {
    
    	ue{$name}.setContent('{$value}');

	});
    
    function createEditor() {
        enableBtn();
        UE.getEditor('{$name}');
    }
    function enableBtn() {
        var div = document.getElementById('btns');
        var btns = UE.dom.domUtils.getElementsByTagName(div, "button");
        for (var i = 0, btn; btn = btns[i++];) {
            UE.dom.domUtils.removeAttributes(btn, ["disabled"]);
        }
    }
    
    createEditor()
</script>
TexT;

    return $a;
}

function upload(
    $filename,
    $value = '',
    $loadLib = true,
    $width = 'auto',
    $height = 'auto',
    $url = '/index.php/admin/Upload/uploadify'
) {
    if (!$value) {
        $value = '/uploads/empty.png';
    }
    $lib = <<<LIB
	<script src="/admin/template1/global/plugins/jquery.min.js" type="text/javascript"></script>
	<link href="/static/uploadify/uploadify.css" rel="stylesheet" type="text/css" />
	<script type="text/javascript" src="/static/uploadify/jquery.uploadify.min.js"></script>
LIB;

    $html = '';
    if ($loadLib) {
        $html .= $lib;
    }

    $html .= <<<TexT
	<div style="width: {$width}px;height: {$height}px;">
		<img id="{$filename}img" style="width: {$width}px;height: {$height}px;" border=0 src='{$value}'/>
	</div>
	<input name="{$filename}" type="file"  for="img" id="{$filename}"/>
	<input id="{$filename}url" name="{$filename}" type="hidden" value="{$value}"/>
	<script type="text/javascript">
		$("#{$filename}").uploadify({
			'buttonText': '上传',
			'multi': false,//只能传一个文件
			'width':'100',
			'removeTimeout':0,//完成后移除弹出框的时间间隔,
			'fileDataName' : 'Filedata',
			'fileTypeExts': '*.jpg; *.jpeg; *.png;',
			'swf': "/static/uploadify/uploadify.swf",
			'button_image_url': "",
			'uploader': "{$url}",
			'onUploadSuccess': function (file, data, response) {
				var rs = JSON.parse(data);
				if (rs.success === true) {
					$('#{$filename}img').attr('src', rs.data.savePath);
					$('#{$filename}url').val(rs.data.savePath);
				}
			}
		});
	</script>
TexT;

    return $html;
}

function tpl_form_field_image($name, $value = '', $default = '')
{
    if (empty($default)) {
        $default = '/uploads/nopic.jpg';
    }
    $val = $default;
    if (!empty($value)) {
        $val = $value;
    }

    $s = '';

    $s .= '
		<div class="input-group">
			<input type="text" name="' . $name . '" value="' . $value . '" class="form-control inputimgs" autocomplete="off">
			<span class="input-group-btn" style="position:relative;">
				<button class="btn btn-default" type="button" onclick="openBrowse(this)">选择图片</button>
				<input class="btn btn-default" type="file" onchange="change(this,0)"  value="选择图片" style="display:none"/>
			</span>
		</div>
		<div class="input-group" style="margin-top:.5em;">
			<img src="' . $val . '" class="img-responsive img-thumbnail imgs"  width="150" />
			<em class="close" style="position:absolute; top: 0px; right: -14px;" title="删除这张图片" onclick="deleteImage(this)">×</em>
		</div>';

    $s .= "
		<script>
			function deleteImage(obj){
				$(obj).prev().attr('src','/uploads/nopic.jpg');
				$(obj).parent().prev().children('.inputimgs').val('');
			}
			
			 function openBrowse(obj){   
				var ie=navigator.appName==\"Microsoft Internet Explorer\" ? true : false;   
				var file = $(obj).next();
				file = file[0];
				if(ie){   
					file.click();   
				}else{  
					var a=document.createEvent(\"MouseEvents\");//FF的处理   
					a.initEvent(\"click\", true, true);    
					file.dispatchEvent(a);   
				}   
    		}   
		</script>";

    return $s;
}

function chechStatic($model, $data)
{
    //       $staticModel=new StaticRewardModel();
    $where = "(max>='{$data['max']}' and '{$data['max']}'>min ) or (min<='{$data['min']}' and '{$data['min']}'<max )";
    $where .= "or (min>='{$data['min']}' and max <='{$data['max']}' )";
    $check = $model->where($where)->select();
    $num = count($check);
    if ($num == 0) {
        return "no";
    } else if ($num == 1) {
        if (isset($data['id'])) {
            if ($check[0]['id'] == $data['id']) {
                return "no";
            } else {
                return "yes";
            }
        } else {
            return "yes";
        }
    } else {
        return "yes";
    }
}

function sizecount($size)
{
    if ($size >= 1073741824) {
        $size = round($size / 1073741824 * 100) / 100 . ' GB';
    } elseif ($size >= 1048576) {
        $size = round($size / 1048576 * 100) / 100 . ' MB';
    } elseif ($size >= 1024) {
        $size = round($size / 1024 * 100) / 100 . ' KB';
    } else {
        $size = $size . ' Bytes';
    }

    return $size;
}

function db_table_schema($db, $tablename = '')
{
    $result = $db->fetch("SHOW TABLE STATUS LIKE '" . trim($db->tablename($tablename),
            '`') . "'");
    if (empty($result)) {
        return array();
    }
    $ret['tablename'] = $result['Name'];
    $ret['charset'] = $result['Collation'];
    $ret['engine'] = $result['Engine'];
    $ret['increment'] = $result['Auto_increment'];
    $result = $db->fetchall("SHOW FULL COLUMNS FROM " . $db->tablename($tablename));
    foreach ($result as $value) {
        $temp = array();
        $type = explode(" ", $value['Type'], 2);
        $temp['name'] = $value['Field'];
        $pieces = explode('(', $type[0], 2);
        $temp['type'] = $pieces[0];
        $temp['length'] = rtrim($pieces[1], ')');
        $temp['null'] = $value['Null'] != 'NO';
        $temp['signed'] = empty($type[1]);
        $temp['increment'] = $value['Extra'] == 'auto_increment';
        $ret['fields'][$value['Field']] = $temp;
    }
    $result = $db->fetchall("SHOW INDEX FROM " . $db->tablename($tablename));
    foreach ($result as $value) {
        $ret['indexes'][$value['Key_name']]['name'] = $value['Key_name'];
        $ret['indexes'][$value['Key_name']]['type'] = ($value['Key_name'] == 'PRIMARY') ? 'primary' : ($value['Non_unique'] == 0 ? 'unique' : 'index');
        $ret['indexes'][$value['Key_name']]['fields'][] = $value['Column_name'];
    }

    return $ret;
}

/**
 * 格式化字节大小
 * @param  number $size 字节数
 * @param  string $delimiter 数字和单位分隔符
 * @return string            格式化后的带单位的大小
 * @author yanchao
 */
function format_bytes($size, $delimiter = '')
{
    $units = array('B', 'KB', 'MB', 'GB', 'TB', 'PB');
    for ($i = 0; $size >= 1024 && $i < 5; $i++) {
        $size /= 1024;
    }

    return round($size, 2) . $delimiter . $units[$i];
}
