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
          				<li><a href="#tab1" class="default-tab"><?php if($act == 'medit'): ?>编辑<?php else: ?>添加<?php endif; ?>项目</a></li>
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
						<?php if($act == 'madd'): ?><form action="<?php echo U('Project/mAdd');?>" id="company_form" method="post" enctype="multipart/form-data" onsubmit="return addValidate()">
						<?php elseif($act == 'medit'): ?>
						<form action="<?php echo U('Project/mEdit');?>" id="company_form" method="post" enctype="multipart/form-data" onsubmit="return addValidate()">
						<?php else: ?>
						<form action="<?php echo U('Project/index');?>" id="company_form" method="post" enctype="multipart/form-data" onsubmit="return addValidate()"><?php endif; ?>
							<fieldset>
								<p>
									<label>项目名称:</label>
									<input class="text-input small-input" type="text" id="project_name" onblur="checkName($(this))" name="project_name" value="<?php echo ($info["name"]); ?>" />
									<span id="project_name_msg" check_flag="1" class="input-notification attention png_bg">请输入正确的项目名称！</span>
									<!-- Classes for input-notification: success, error, information, attention, optional -->
									<br />
									<small>请输入合适的项目名称！可以使用中文，但禁止除[@][.]以外的特殊符号</small> 
								</p>
								<p>
									<label>项目预算:</label>
									<input class="text-input small-input" type="text" id="cost" name="cost" value="<?php echo ($info["cost"]); ?>" />
									<span class="input-notification optional png_bg">选填项</span>
									<!-- Classes for input-notification: success, error, information, attention, optional -->
									<br />
									<small>请输入金额数额！注意：精确到小数点后两位！最大值为99999999.99！</small> 
								</p>
								<p>
									<label>项目封面:</label>
									<?php if($act == 'madd'): ?><input class="text-input small-input" type="file" id="cover_image" name="cover_image" value="<?php echo ($info["cover_image"]); ?>" />
									<span id="cover_image_msg" class="input-notification optional png_bg">选填项</span>
									<!-- Classes for input-notification: success, error, information, attention, optional -->
									<br />
									<small>请上传项目封面！</small>
									<?php elseif($act == 'medit'): ?>
									<if condition="$info['cover_image']">
									<img id="cover_image_img" src="/opened/Uploads/Image/<?php echo ($info["cover_image"]); ?>" alt="项目封面" />
									<br />
									<input class="text-input small-input" type="file" id="cover_image" name="cover_image" value="<?php echo ($info["cover_image"]); ?>" />
									<br />
									<small>重新选择会覆盖原有的图片！</small>
									<?php else: ?>
									<input class="text-input small-input" type="file" id="cover_image" name="cover_image" value="<?php echo ($info["cover_image"]); ?>" />
									<span id="cover_image_msg" class="input-notification optional png_bg">选填项</span>
									<!-- Classes for input-notification: success, error, information, attention, optional -->
									<br />
									<small>请上传项目封面！</small><?php endif; ?> 
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
								<?php if($act == 'medit'): ?><p>
              						<label>项目进度:</label>
              						<select id="is_end" name="is_end" class="text-input small-input" >
										<option value="0">--未开始--</option>
										<option value="1">--已开始--</option>
										<option value="2">--已结束--</option>
              						</select>
									<span id="aid_msg" class="input-notification optional png_bg">选填项</span>
              						<br />
									<small>请选择项目进度！</small> 
            					</p><?php endif; ?>
								<p>
									<label>项目备注:</label>
									<textarea class="text-input textarea wysiwyg" name="remark" cols="79" rows="15"><?php echo ($info["remark"]); ?></textarea>
								</p>
								<p>
									
									<?php if($act == 'madd'): ?><input type="submit" value="添加" />
									<?php elseif($act == 'medit'): ?>
										<input type="hidden" name='id' value="<?php echo ($info["pid"]); ?>" />	
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
	$(function(){
		/*JQuery 限制文本框只能输入数字和小数点*/
		var max_amounts = 99999999.99;
		$("#cost").keyup(function(){
			var num = $("#cost").val();
			//alert(num);
			if(num && parseFloat(num) > max_amounts){
				$(this).val(max_amounts);  
			}else{
				$(this).val($(this).val().replace(/[^0-9.]/g,''));
			}  
		}).bind("paste",function(){  //CTR+V事件处理    
			$(this).val($(this).val().replace(/[^0-9.]/g,''));     
		}).css("ime-mode", "disabled"); //CSS设置输入法不可用    
	});
	
	function checkName(obj){
		var ra_name = $('#project_name');
		var ad_name_msg = $("#project_name_msg");
		var name = $.trim(ra_name.val());
		var id_obj = $("input[name='id']");
		var id = 0;
		var msg = '';

		//alert(id_obj.length);
		if(id_obj.length){
			id = id_obj.val();
			if(id && parseInt(id) > 0){
				if(!name.length){
					msg = '项目名称不能为空！\n';
					ad_name_msg.attr('check_flag',2);
					ad_name_msg.show();
					ad_name_msg.removeClass("success");
					ad_name_msg.addClass("error");
					ad_name_msg.text(msg);

				}else{
					//ajax判断重名
					$.ajax({
						type:"post",
						url:"<?php echo U('Project/ajax_check_edit_name');?>",
						data:"id="+id+"&name="+name,
						dataType:"json",
						success:function(result){
							if(result.flag){
								ad_name_msg.show();
								ad_name_msg.attr('check_flag',1);
								ad_name_msg.removeClass("error");
								ad_name_msg.addClass("success");
								ad_name_msg.text(result.msg);
							}else{
								ad_name_msg.show();
								ad_name_msg.attr('check_flag',3);
								ad_name_msg.removeClass("success");
								ad_name_msg.addClass("error");
								ad_name_msg.text(result.msg);
							}
						},
						error: function(XMLHttpRequest, textStatus, errorThrown) {
							//alert(XMLHttpRequest.status);
							//alert(XMLHttpRequest.readyState);
							//alert(textStatus);
							ad_name_msg.show();
							ad_name_msg.attr('check_flag',4);
							ad_name_msg.removeClass("success");
							ad_name_msg.addClass("error");
							ad_name_msg.text("请求错误！请刷新重试！");
						}
					});
				}
			
			}else{
				msg = '项目信息有误！刷新页面！';
				ad_name_msg.attr('check_flag',5);
				ad_name_msg.show();
				ad_name_msg.removeClass("success");
				ad_name_msg.addClass("error");
				ad_name_msg.text(msg);
			}
		}else{
			if(!name.length){
				msg = '项目名称不能为空！';
				ad_name_msg.attr('check_flag',2);
				ad_name_msg.show();
				ad_name_msg.removeClass("success");
				ad_name_msg.addClass("error");
				ad_name_msg.text(msg);

			}else{
				//ajax判断重名
				$.ajax({
					type:"post",
					url:"<?php echo U('Project/ajax_check_name');?>",
					data:"name="+name,
					dataType:"json",
					success:function(result){
						if(result.flag){
							ad_name_msg.show();
							ad_name_msg.attr('check_flag',1);
							ad_name_msg.removeClass("error");
							ad_name_msg.addClass("success");
							ad_name_msg.text(result.msg);
						}else{
							ad_name_msg.show();
							ad_name_msg.attr('check_flag',3);
							ad_name_msg.removeClass("success");
							ad_name_msg.addClass("error");
							ad_name_msg.text(result.msg);
						}
					},
					error: function(XMLHttpRequest, textStatus, errorThrown) {
						//alert(XMLHttpRequest.status);
						//alert(XMLHttpRequest.readyState);
						//alert(textStatus);
						ad_name_msg.show();
						ad_name_msg.attr('check_flag',4);
						ad_name_msg.removeClass("success");
						ad_name_msg.addClass("error");
						ad_name_msg.text("请求错误！请刷新重试！");
					}
				});
			}
		}
	}

	//表单提交验证
	function addValidate(){
		var flag = true;
		var msg = '';
		var name_tmp_msg = '';
		var name = $.trim($('#project_name').val());
		var obj = $("#project_name_msg");
		var act = $("input[name='act']");
		//var ad_type = $("#ad_type").val();
		
		if(!name.length){
			msg += '项目名称不能为空！\n';
			name_tmp_msg = '项目名称不能为空！';
			flag = false;
		}else{
			var name_check_flag = obj.attr('check_flag');
			if(name_check_flag != 1){
				flag = false;
				if(name_check_flag == 2){
					msg += '项目名称不能为空！\n';
					name_tmp_msg = '项目名称不能为空！';
				}else if(name_check_flag == 3){
					var check_msg = obj.text();
					if(check_msg.length > 0){
						msg += check_msg;
					}else{
						msg += '项目名称不合法！\n';
						name_tmp_msg = '项目名称不合法！';
					}
				}else if(name_check_flag == 5){
					msg = '数据有误！请刷新页面重试！';
					name_tmp_msg = '数据有误！请刷新页面重试！';
				}else{
					msg += '未知错误！';
					name_tmp_msg = '未知错误！';
				}
			}
		}

		if(!flag){
			obj.show();
			obj.removeClass("success");
			obj.addClass("error");
			obj.text(name_tmp_msg);
			alert(msg);

			//调用屏幕抖动效果
			addLoadEvent(function(){ var p=new Array(15,30,15,0,-15,-30,-15,0);p=p.concat(p.concat(p));var i=document.forms[0].id;g(i).position='relative';shake(i,p,20);});
		}
		//return false;
		return flag;
	}
</script>