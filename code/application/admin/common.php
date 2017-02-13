<?php
use think\db;
//获取指定长度随机字符串
function getRandStr($len=6){
	$arr=array_merge(range(0,9),range("a","z"),range("A","Z"));
	shuffle($arr);//将数组打乱
	$subarr=array_slice($arr, 0,$len);
	return implode(",", $subarr);
}



//所属分类
function superior($pid,$table='geek_menu'){
	$row=Db::table($table)->where('id',$pid)->value('title');
	if($row){
		return $row;
	}else{
		return '顶级栏目';
	}
}

//配置设置的分组和类型
function systemGroup($number,$type){
	$value='';
	if($type==1){
		switch ($number)
		{
			case 0:
				$value='不分组';
				break;
			case 1:
				$value='基本';
				break;
			case 2:
				$value='系统';
				break;
			case 3:
				$value='邮件';
				break;
		}
	}else{
		switch ($number)
		{
			case 1:
				$value='文本';
				break;
			case 2:
				$value='上传';
				break;
			case 3:
				$value='富文本';
				break;
			case 4:
				$value='单选';
				break;
			case 5:
				$value='多选';
				break;
		}
	}
	return $value;
}

function richText($name){
	$a = <<<TexT
	<link rel="stylesheet" href="/static/kindeditor-4.1.10/themes/default/default.css" />
	<script charset="utf-8" src="/static/kindeditor-4.1.10/kindeditor-min.js"></script>
	<script charset="utf-8" src="/static/kindeditor-4.1.10/lang/zh_CN.js"></script>
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
	return $a;
}
function checkRolePerm($perm,$id)
{
	return \app\admin\logic\PrivilegeLogic::checkRolePerm($perm, $id);
}

function newRichText($name){
	$a = <<<TexT
<script src="/admin/template1/global/plugins/jquery.min.js" type="text/javascript"></script>
<script type="text/javascript" src="/static/ueditor/ueditor.config.js"></script>
<script type="text/javascript" src="/static/ueditor/ueditor.all.min.js"></script>
<script type="text/javascript" src="/static/ueditor/lang/zh-cn/zh-cn.js"></script>
<script type="text/javascript">
    var ueditoroption = {
        'autoClearinitialContent': false,
        'toolbars': [['fullscreen', 'source', 'preview', '|', 'bold', 'italic', 'underline', 'strikethrough', 'forecolor', 'backcolor', '|',
            'justifyleft', 'justifycenter', 'justifyright', '|', 'insertorderedlist', 'insertunorderedlist', 'blockquote', 'emotion', 'insertvideo',
            'link', 'removeformat', '|', 'rowspacingtop', 'rowspacingbottom', 'lineheight', 'indent', 'paragraph', 'fontsize', '|',
            'inserttable', 'deletetable', 'insertparagraphbeforetable', 'insertrow', 'deleterow', 'insertcol', 'deletecol',
            'mergecells', 'mergeright', 'mergedown', 'splittocells', 'splittorows', 'splittocols', '|', 'anchor', 'map', 'print', 'drafts']],
        'elementPathEnabled': false,
        'initialFrameHeight': 200,
        'focus': false,
        'maximumWords': 9999999999999
    };
    var opts = {
        type: 'image',
        direct: false,
        multi: true,
        tabs: {
            'upload': 'active',
            'browser': '',
            'crawler': ''
        },
        path: '',
        dest_dir: '',
        global: false,
        thumb: false,
        width: 0
    };
    UE.registerUI('myinsertimage', function (editor, uiName) {
        editor.registerCommand(uiName, {
            execCommand: function () {
                require(['fileUploader'], function (uploader) {
                    uploader.show(function (imgs) {
                        if (imgs.length == 0) {
                            return;
                        } else if (imgs.length == 1) {
                            editor.execCommand('insertimage', {
                                'src': imgs[0]['url'],
                                '_src': imgs[0]['attachment'],
                                'width': '100%',
                                'alt': imgs[0].filename
                            });
                        } else {
                            var imglist = [];
                            for (i in imgs) {
                                imglist.push({
                                    'src': imgs[i]['url'],
                                    '_src': imgs[i]['attachment'],
                                    'width': '100%',
                                    'alt': imgs[i].filename
                                });
                            }
                            editor.execCommand('insertimage', imglist);
                        }
                    }, opts);
                });
            }
        });
        var btn = new UE.ui.Button({
            name: '插入图片',
            title: '插入图片',
            cssRules: 'background-position: -726px -77px',
            onclick: function () {
                editor.execCommand(uiName);
            }
        });
        editor.addListener('selectionchange', function () {
            var state = editor.queryCommandState(uiName);
            if (state == -1) {
                btn.setDisabled(true);
                btn.setChecked(false);
            } else {
                btn.setDisabled(false);
                btn.setChecked(state);
            }
        });
        return btn;
    }, 19);

    $(function () {
        var ue = UE.getEditor("$name", ueditoroption);
        $("#$name").data('editor', ue);
        $("#$name").parents('form').submit(function () {
            if (ue.queryCommandState('source')) {
                ue.execCommand('source');
            }
        });
    });
</script>
        
TexT;
	return $a;
}

function upload($filename,$value,$loadLib = true, $width=260, $height=200, $url='/index.php/admin/Upload/uploadify'){
	if(empty($value)){
		$value='/uploads/empty.png';
	}
	$lib = <<<LIB
	<script src="/admin/template1/global/plugins/jquery.min.js" type="text/javascript"></script>
	<link href="/static/uploadify/uploadify.css" rel="stylesheet" type="text/css" />
	<script type="text/javascript" src="/static/uploadify/jquery.uploadify.min.js"></script>
LIB;

	$html = '';
	if($loadLib){
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
			'buttonText': '点击上传',
			'multi': false,//只能传一个文件
			'width':'260',
			'removeTimeout':0,//完成后移除弹出框的时间间隔,
			'fileDataName' : 'Filedata',
			'fileTypeExts': '*.jpg; *.jpeg; *.png;',
			'swf': "/static/uploadify/uploadify.swf",
			'button_image_url': "",
			'uploader': "{$url}",
			'onUploadSuccess': function (file, data, response) {
				var rs = JSON.parse(data);
				if (rs.success == true) {
					$('#{$filename}img').attr('src', rs.data.savePath);
					$('#{$filename}url').val(rs.data.savePath);
				}
			}
		});
	</script>
TexT;

	return $html;
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
