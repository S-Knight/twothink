<?php

function checkRolePerm($perm, $id)
{
    return \app\admin\logic\PrivilegeLogic::checkRolePerm($perm, $id);
}

function kindeditor($name)
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
				allowImageUpload : true
			});
		});
	</script>
TexT;

    return $html;
}

function ueditor(
    $name,
    $value = '',
    $loadLib = true,
    $width = 800,
    $height = 400
) {
    $html = '';
    $lib = <<<LIB
    <script type="text/javascript" src="/static/ueditor/ueditor.config.js"></script>
    <script type="text/javascript" src="/static/ueditor/ueditor.all.min.js"></script>
LIB;

    if ($loadLib) {
        $html .= $lib;
    }

    $html .= <<<TexT
<script id="{$name}" name='{$name}' type="text/plain"></script>
<script type="text/javascript">
    var ue{$name} = UE.getEditor('{$name}', {
        initialFrameWidth: {$width},
        initialFrameHeight: {$height}
    });
    ue{$name}.ready(function() {
    	ue{$name}.setContent('{$value}');
	});
</script>
TexT;

    return $html;
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

/**
 * 格式化字节大小
 * @param  number $size 字节数
 * @param  string $delimiter 数字和单位分隔符
 * @return string            格式化后的带单位的大小
 * @author yanchao
 */
function format_bytes($size, $delimiter = '')
{
    $units = ['B', 'KB', 'MB', 'GB', 'TB', 'PB'];
    for ($i = 0; $size >= 1024 && $i < 5; $i++) {
        $size /= 1024;
    }

    return round($size, 2) . $delimiter . $units[$i];
}
