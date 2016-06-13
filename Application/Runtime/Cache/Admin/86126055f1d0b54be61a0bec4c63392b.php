<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<title><?php echo ($site["SITE_INFO"]["name"]); ?></title>
		<meta name="author" content="<?php echo ($site["SITE_INFO"]["author"]); ?>" />
		<meta name="keywords" content="<?php echo ($site["SITE_INFO"]["keywords"]); ?>" />
		<!--                       CSS                       -->
		<!-- Reset Stylesheet -->
		<link rel="stylesheet" href="/opened/Public/Css/Admin/common.css" type="text/css" media="screen" />
		<link rel="stylesheet" href="/opened/Public/Css/Admin/font.css" type="text/css" media="screen" />
		<link rel="stylesheet" href="/opened/Public/Css/Admin/reset.css" type="text/css" media="screen" />
		
		<!-- Main Stylesheet -->
		<link rel="stylesheet" href="/opened/Public/Css/Admin/style.css" type="text/css" media="screen" />
		<!-- Invalid Stylesheet. This makes stuff look pretty. Remove it if you want the CSS completely valid -->
		<!--                       Javascripts                       -->
		<!-- jQuery -->
		<script type="text/javascript" src="/opened/Public/Js/Common/jquery-1.9.0.min.js"></script>
		<!-- jQuery Configuration -->
		<script type="text/javascript" src="/opened/Public/Js/Common/common.js"></script>
		<script type="text/javascript" src="/opened/Public/Js/Admin/simpla.jquery.configuration.js"></script>
		<script type="text/javascript" src="/opened/Public/Js/Admin/main.js"></script>
	</head>

<body>
	<script type="text/javascript" src="/opened/Public/Js/Common/My97DatePicker/WdatePicker.js"></script>
	<!-- End .shortcut-buttons-set -->
	<div id="main_box">
		<div class="content-box">
      		<!-- Start Content Box -->
      			<div class="content-box-header">
				<h3><?php echo ($navigation_bar); ?></h3>
        			<ul class="content-box-tabs">
          				<li><a href="#tab1" class="default-tab"><?php if($act == 'medit'): ?>编辑<?php else: ?>添加<?php endif; ?>文件</a></li>
        			</ul>
        			<div class="clear"></div>
      			</div>
      			<!-- End .content-box-header -->
      			<div class="content-box-content">
					
				<!--Start #tab2-->
				<div class="tab-content default-tab" id="tab1">
					<!-- This is the target div. id must match the href of this div's tab -->
					<div class="notification attention png_bg">
						<div class="close">
						<a href="#"><img src="/opened/Public/Img/Admin/icons/cross_grey_small.png" title="Close this notification" alt="close" /></a>
						</div>
						<div> 这是一个公告栏。顺便一提,你可以关闭此通知。<font color="#ff5b6f"><span id="outmess" class="tip-msg error-msg sucess-msg">
	<?php echo ($clue_message); ?>		
</span></font></div>
    				</div>
					<div class="c_input_box">
						<?php if($act == 'madd'): ?><form action="<?php echo U('File/mAdd');?>" id="file_form" method="post" enctype="multipart/form-data" onsubmit="return addValidate()">
						<?php elseif($act == 'medit'): ?>
						<form action="<?php echo U('File/mEdit');?>" id="file_form" method="post" enctype="multipart/form-data" onsubmit="return addValidate()">
						<?php else: ?>
						<form action="<?php echo U('File/index');?>" id="file_form" method="post" enctype="multipart/form-data" onsubmit="return addValidate()"><?php endif; ?>
							<fieldset>
								<p>
              						<label>所属项目:</label>
              						<select id="pid" name="pid" class="text-input small-input">
										<option value="0" pid="">请选择所属项目</option>
										<?php if(is_array($p_arr)): $i = 0; $__LIST__ = $p_arr;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><option value="<?php echo ($vo["pid"]); ?>" aid="<?php echo ($vo["pid"]); ?>" <?php if($vo['pid'] == $info['pid']): ?>selected<?php endif; ?>><?php echo ($vo["name"]); ?></option><?php endforeach; endif; else: echo "" ;endif; ?>
              						</select>
									<span id="pid_msg" class="input-notification attention png_bg">请选择所属项目！</span>
              						<br />
									<small>请选择所属项目！</small> 
            					</p>
								<p>
									<label>上传文件:</label>
									<?php if($act == 'madd'): ?><input class="text-input small-input" type="file" id="address" name="address" value="<?php echo ($info["address"]); ?>" />
									<span id="address_msg" class="input-notification attention png_bg">请上传文件！</span>
									<!-- Classes for input-notification: success, error, information, attention, optional -->
									<br />
									<small>请上传文件！</small>
									<?php elseif($act == 'medit'): ?>
									<?php if($info['address']): ?><div style="text-align:center;width:200px;">
										<?php switch($info["type"]): case "1": ?><a href="<?php echo U('Download/download','id='.$info['id']);?>"><img id="address_img" src="/opened/Public/Img/Common/file_icon_big/iconfont-doc.png" alt="点击下载该文件" /></a><?php break;?>
										<?php case "2": ?><a href="<?php echo U('Download/download','id='.$info['id']);?>"><img id="address_img" src="/opened/Public/Img/Common/file_icon_big/iconfont-xls.png" alt="点击下载该文件" /></a><?php break;?>
										<?php case "3": ?><a href="<?php echo U('Download/download','id='.$info['id']);?>"><img id="address_img" src="/opened/Public/Img/Common/file_icon_big/iconfont-txt.png" alt="点击下载该文件" /></a><?php break;?>
										<?php case "4": ?><a href="<?php echo U('Download/download','id='.$info['id']);?>"><img id="address_img" src="/opened/Public/Img/Common/file_icon_big/iconfont-filejpg.png" alt="点击下载该文件" /></a><?php break;?>
										<?php case "5": ?><a href="<?php echo U('Download/download','id='.$info['id']);?>"><img id="address_img" src="/opened/Public/Img/Common/file_icon_big/iconfont-filegif.png" alt="点击下载该文件" /></a><?php break;?>
										<?php case "6": ?><a href="<?php echo U('Download/download','id='.$info['id']);?>"><img id="address_img" src="/opened/Public/Img/Common/file_icon_big/iconfont-filepng.png" alt="点击下载该文件" /></a><?php break;?>
										<?php case "7": ?><a href="<?php echo U('Download/download','id='.$info['id']);?>"><img id="address_img" src="/opened/Public/Img/Common/file_icon_big/iconfont-wbdiconsoftjpeg.png" alt="点击下载该文件" /></a><?php break;?>
										<?php default: ?><a href="<?php echo U('Download/download','id='.$info['id']);?>"><img id="address_img" src="/opened/Public/Img/Common/file_icon_big/iconfont-weizhi2.png" alt="点击下载该文件" /></a><?php endswitch;?>
										<br />
										<span><?php echo ($info["name"]); ?></span>
									</div>
									<br />
									<input class="text-input small-input" type="file" id="address" name="address" value="<?php echo ($info["address"]); ?>" />
									<br />
									<small>重新选择会覆盖原有的文件！</small>
									<?php else: ?>
									<input class="text-input small-input" type="file" id="address" name="address" value="<?php echo ($info["address"]); ?>" />
									<span id="address_msg" class="input-notification attention png_bg">请上传文件！</span>
									<!-- Classes for input-notification: success, error, information, attention, optional -->
									<br />
									<small>请上传文件！</small><?php endif; endif; ?>
								</p>
								<p>
              						<label>是否激活:</label>
									<?php if($act == 'medit' and $info["status"] == 0): ?><input type="radio" name="status" value="0" checked="checked" />不激活&nbsp;&nbsp;&nbsp;&nbsp;
              						<input type="radio" name="status" value="1" />激活
									<?php elseif($act == 'medit' and $info["status"] == 1): ?>
									<input type="radio" name="status" value="0" />不激活&nbsp;&nbsp;&nbsp;&nbsp;
              						<input type="radio" name="status" value="1" checked="checked" />激活
									<?php else: ?>
              						<input type="radio" name="status" value="0" checked="checked" />不激活&nbsp;&nbsp;&nbsp;&nbsp;
              						<input type="radio" name="status" value="1" />激活<?php endif; ?>
								</p>
								<p>
									<label>文件备注:</label>
									<textarea class="text-input textarea wysiwyg" name="remark" cols="79" rows="10"><?php echo ($info["remark"]); ?></textarea>
								</p>
								<p>
									<?php if($act == 'madd'): ?><input type="submit" value="添加" />
									<?php elseif($act == 'medit'): ?>
										<input type="hidden" name='id' value="<?php echo ($info["id"]); ?>" />	
										<input name="submit" type="submit" value="修改" /><?php endif; ?>
									<input type="hidden" name='act' value="<?php echo ($act); ?>" />
									<input name="reset" type="reset" value="重置" />
								</p>
							</fieldset>
							<div class="clear"></div>
							<!-- End .clear -->
						</form>
					</div>
				</div> 	
      		</div>
      		<!-- End .content-box-content -->
    	</div>
    	<!-- End .content-box -->
    	<div class="clear"></div>
    	
    	<div id="footer"> <small>
      	<!-- Remove this notice or replace it with whatever you want -->
      	&#169; Copyright 2013 Your Company | Powered by <a href="http://www.trydemo.net">D.Apache.Luo</a> | <a href="#">Top</a> </small> </div>
    	<!-- End #footer -->
  </div>
  <!-- End #main-content -->
</div>
</div>
</body>
</html>

<script type="text/javascript">


	//表单提交验证
	function addValidate(){
		var flag = true;
		var msg = '';
		var pid = parseInt($('#pid').val());
		var pid_tmp_msg = '';
		var pid_msg = $("#pid_msg");
		var act = $("input[name='act']");
		
		if(pid < 1){
			msg += '未选择所属项目！\n';
			pid_tmp_msg = '未选择所属项目！';
			pid_msg.show();
			pid_msg.removeClass("success");
			pid_msg.addClass("error");
			pid_msg.text(pid_tmp_msg);
			flag = false;
		}
		
		if(act.length && act == 'madd'){
			var address_msg = $('#address_msg');
			var address_val = $.trim($('#address').val());
			if(!address_val.length){
				flag = false;
				var tmp_msg = "上传文件不能为空！";
				msg += tmp_msg+'\n';
				address_msg.show();
				address_msg.removeClass("attention");
				address_msg.addClass("error");
				address_msg.text(tmp_msg);
			}
		}else{
			var address_img = $("#address_img");
			if(!address_img.length){
				var address_msg = $('#address_msg');
				var address_val = $.trim($('#address').val());
				if(!address_val.length){
					flag = false;
					var tmp_msg = "上传文件不能为空！";
					msg += tmp_msg+'\n';
					address_msg.show();
					address_msg.removeClass("attention");
					address_msg.addClass("error");
					address_msg.text(tmp_msg);
				}
			}
		}

		if(!flag){
			alert(msg);
			//调用屏幕抖动效果
			addLoadEvent(function(){ var p=new Array(15,30,15,0,-15,-30,-15,0);p=p.concat(p.concat(p));var i=document.forms[0].id;g(i).position='relative';shake(i,p,20);});
		}
		//return false;
		return flag;
	}
</script>