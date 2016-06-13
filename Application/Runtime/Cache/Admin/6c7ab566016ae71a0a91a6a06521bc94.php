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
          				<li><a href="#tab1" class="default-tab"><?php if($act == 'medit'): ?>编辑<?php else: ?>添加<?php endif; ?>公司</a></li>
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
						<?php if($act == 'madd'): ?><form action="<?php echo U('Finance/mAdd');?>" id="company_form" method="post" enctype="multipart/form-data" onsubmit="return addValidate()">
						<?php elseif($act == 'medit'): ?>
						<form action="<?php echo U('Finance/mEdit');?>" id="company_form" method="post" enctype="multipart/form-data" onsubmit="return addValidate()">
						<?php else: ?>
						<form action="<?php echo U('Finance/index');?>" id="company_form" method="post" enctype="multipart/form-data" onsubmit="return addValidate()"><?php endif; ?>
							<fieldset>
								<p>
									<label>公司名称:</label>
									<input class="text-input small-input" type="text" id="company_name" onblur="checkName($(this))" name="company_name" value="<?php echo ($info["name"]); ?>" />
									<span id="company_name_msg" check_flag="1" class="input-notification attention png_bg">请输入正确的公司名称！</span>
									<!-- Classes for input-notification: success, error, information, attention, optional -->
									<br />
									<small>请输入合适的公司名称！可以使用中文，但禁止除[@][.]以外的特殊符号</small> 
								</p>
								<p>
              						<label>负责人:</label>
              						<select id="aid" name="aid" class="text-input small-input" onchange="$('#aid').val($(this).find('option:selected').attr('aid'))">
										<option value="0" aid="">--请选择负责人--</option>
										<?php if(is_array($admin_list)): $i = 0; $__LIST__ = $admin_list;if( count($__LIST__)==0 ) : echo "$admin_list_empty" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><option value="<?php echo ($vo["aid"]); ?>" aid="<?php echo ($vo["aid"]); ?>" <?php if($vo["aid"] == $info['aid']): ?>selected<?php endif; ?>><?php echo ($vo["username"]); ?></option><?php endforeach; endif; else: echo "$admin_list_empty" ;endif; ?>
              						</select>
									<span id="aid_msg" class="input-notification attention png_bg">请选择负责人！</span>
									<input type="hidden" id="position_area_code" name='area_code' value="" />	
              						<br />
									<small>请选择负责人！</small> 
            					</p>
								<p>
									<label>公司邮箱:</label>
									<input class="text-input small-input" type="text" id="email" name="email" onblur="checkEmail($(this))" value="<?php echo ($info["email"]); ?>" />
									<span id="email_msg" class="input-notification attention png_bg">请输入正确的公司邮箱！</span>
									<!-- Classes for input-notification: success, error, information, attention, optional -->
									<br />
									<small>请输入公司邮箱！</small> 
								</p>
								<p>
									<label>公司电话:</label>
									<input class="text-input small-input" type="text" id="phone" name="phone" value="<?php echo ($info["phone"]); ?>" />
									<span class="input-notification optional png_bg">选填项</span>
									<!-- Classes for input-notification: success, error, information, attention, optional -->
									<br />
									<small>请输入公司电话！</small> 
								</p>
								<p>
									<label>公司地址:</label>
									<input class="text-input small-input" type="text" id="address" name="address" value="<?php echo ($info["address"]); ?>" />
									<span class="input-notification optional png_bg">选填项</span>
									<!-- Classes for input-notification: success, error, information, attention, optional -->
									<br />
									<small>请输入公司公司地址！</small> 
								</p>
								<p>
									<label>公司官网:</label>
									<input class="text-input small-input" type="text" id="website" name="website" value="<?php echo ($info["website"]); ?>" />
									<span class="input-notification optional png_bg">选填项</span>
									<!-- Classes for input-notification: success, error, information, attention, optional -->
									<br />
									<small>请输入公司官网！</small> 
								</p>
								<p>
									<label>账户金额:</label>
									<input class="text-input small-input" type="text" id="company_amounts" name="company_amounts" value="<?php echo ($info["company_amounts"]); ?>" />
									<span class="input-notification optional png_bg">选填项</span>
									<!-- Classes for input-notification: success, error, information, attention, optional -->
									<br />
									<small>请输入金额数额！注意：精确到小数点后两位！最大值为99999999.99！</small> 
								</p>
								<p>
									<label>营业执照:</label>
									<?php if($act == 'madd'): ?><input class="text-input small-input" type="file" id="business_license" name="business_license" value="<?php echo ($info["business_license"]); ?>" />
									<span id="business_license_msg" class="input-notification attention png_bg">请上传营业执照！</span>
									<!-- Classes for input-notification: success, error, information, attention, optional -->
									<br />
									<small>请上传营业执照！</small>
									<?php elseif($act == 'medit'): ?>
									<?php if($info['business_license']): ?><img id="business_license_img" src="/opened/Uploads/Image/<?php echo ($info["business_license"]); ?>" alt="营业执照" />
									<br />
									<input class="text-input small-input" type="file" id="business_license" name="business_license" value="<?php echo ($info["business_license"]); ?>" />
									<br />
									<small>重新选择会覆盖原有的图片！</small>
									<?php else: ?>
									<input class="text-input small-input" type="file" id="business_license" name="business_license" value="<?php echo ($info["business_license"]); ?>" />
									<span id="business_license_msg" class="input-notification attention png_bg">请上传营业执照！</span>
									<!-- Classes for input-notification: success, error, information, attention, optional -->
									<br />
									<small>请上传营业执照！</small><?php endif; endif; ?>
								</p>
								<p>
									<label>公司Logo:</label>
									<?php if($act == 'madd'): ?><input class="text-input small-input" type="file" id="company_logo" name="company_logo" value="<?php echo ($info["company_logo"]); ?>" />
									<span id="company_logo_msg" class="input-notification optional png_bg">请上传公司Logo！</span>
									<!-- Classes for input-notification: success, error, information, attention, optional -->
									<br />
									<small>请上传公司Logo！</small>
									<?php elseif($act == 'medit'): ?>
									<?php if($info['company_logo']): ?><img id="business_license_img" src="/opened/Uploads/Image/<?php echo ($info["company_logo"]); ?>" alt="公司Logo" />
									<br />
									<input class="text-input small-input" type="file" id="company_logo" name="company_logo" value="<?php echo ($info["company_logo"]); ?>" />
									<br />
									<small>重新选择会覆盖原有的图片！</small>
									<?php else: ?>
									<input class="text-input small-input" type="file" id="company_logo" name="company_logo" value="<?php echo ($info["company_logo"]); ?>" />
									<span id="business_license_msg" class="input-notification optional png_bg">请上传公司Logo！</span>
									<!-- Classes for input-notification: success, error, information, attention, optional -->
									<br />
									<small>请上传公司Logo！</small><?php endif; endif; ?>
								</p>
								<p>
              						<label>是否启用:</label>
									<?php if($act == 'medit' and $info["status"] == 0): ?><input type="radio" name="status" value="0" checked="checked" />禁用&nbsp;&nbsp;&nbsp;&nbsp;
              							<input type="radio" name="status" value="1" />启用
									<?php elseif($act == 'medit' and $info["status"] == 1): ?>	
										<input type="radio" name="status" value="0" />禁用&nbsp;&nbsp;&nbsp;&nbsp;
              							<input type="radio" name="status" value="1" checked="checked" />启用
									<?php else: ?>
										<input type="radio" name="status" value="0" />禁用&nbsp;&nbsp;&nbsp;&nbsp;
										<input type="radio" name="status" value="1" checked="checked" />启用<?php endif; ?>
								</p>
								<p>
									<label>公司简介:</label>
									<textarea class="text-input textarea wysiwyg" name="remark" cols="79" rows="15"><?php echo ($info["remark"]); ?></textarea>
								</p>
								<p>
									
									<?php if($act == 'madd'): ?><input type="submit" value="添加" />
									<?php elseif($act == 'medit'): ?>
										<input type="hidden" name='id' value="<?php echo ($info["cid"]); ?>" />	
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
		$("#company_amounts").keyup(function(){
			var num = $("#company_amounts").val();
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
		var ra_name = $('#company_name');
		var ad_name_msg = $("#company_name_msg");
		var name = $.trim(ra_name.val());
		var id_obj = $("input[name='id']");
		var id = 0;
		var msg = '';

		//alert(id_obj.length);
		if(id_obj.length){
			id = id_obj.val();
			if(id && parseInt(id) > 0){
				if(!name.length){
					msg = '公司名称不能为空！\n';
					ad_name_msg.attr('check_flag',2);
					ad_name_msg.show();
					ad_name_msg.removeClass("success");
					ad_name_msg.addClass("error");
					ad_name_msg.text(msg);

				}else{
					//ajax判断重名
					$.ajax({
						type:"post",
						url:"<?php echo U('Finance/ajax_check_edit_name');?>",
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
				msg = '公司信息有误！刷新页面！';
				ad_name_msg.attr('check_flag',5);
				ad_name_msg.show();
				ad_name_msg.removeClass("success");
				ad_name_msg.addClass("error");
				ad_name_msg.text(msg);
			}
		}else{
			if(!name.length){
				msg = '公司名称不能为空！';
				ad_name_msg.attr('check_flag',2);
				ad_name_msg.show();
				ad_name_msg.removeClass("success");
				ad_name_msg.addClass("error");
				ad_name_msg.text(msg);

			}else{
				//ajax判断重名
				$.ajax({
					type:"post",
					url:"<?php echo U('Finance/ajax_check_name');?>",
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
	
	function checkEmail(obj){
		var email = obj.val();
		var email_msg = $("#email_msg");
		var msg = '公司邮箱输入正确！';
		var regu = "^(([0-9a-zA-Z]+)|([0-9a-zA-Z]+[_.0-9a-zA-Z-]*[0-9a-zA-Z-]+))@([a-zA-Z0-9-]+[.])+([a-zA-Z]|net|NET|asia|ASIA|com|COM|gov|GOV|mil|MIL|org|ORG|edu|EDU|int|INT|cn|CN|cc|CC)$";
		var re = new RegExp(regu);
		if(!email.length){
			msg = '公司邮箱不能为空！';
			email_msg.show();
			email_msg.removeClass("success");
			email_msg.addClass("error");
			email_msg.text(msg);
		}else if(email.search(re) == -1){
			msg = '公司邮箱格式不正确！';
			email_msg.show();
			email_msg.removeClass("success");
			email_msg.addClass("error");
			email_msg.text(msg);
		}else{
			email_msg.show();
			email_msg.removeClass("error");
			email_msg.addClass("success");
			email_msg.text(msg);
		}
	}

	//表单提交验证
	function addValidate(){
		var flag = true;
		var msg = '';
		var name_tmp_msg = '';
		var name = $.trim($('#company_name').val());
		var obj = $("#company_name_msg");
		var aid = $.trim($('#aid').val());
		var aid_msg = $('#aid_msg');
		var email = $("#email").val();
		var email_msg = $("#email_msg");
		var act = $("input[name='act']");
		//var ad_type = $("#ad_type").val();
		
		if(!name.length){
			msg += '公司名称不能为空！\n';
			name_tmp_msg = '公司名称不能为空！';
			flag = false;
		}else{
			var name_check_flag = obj.attr('check_flag');
			if(name_check_flag != 1){
				flag = false;
				if(name_check_flag == 2){
					msg += '公司名称不能为空！\n';
					name_tmp_msg = '公司名称不能为空！';
				}else if(name_check_flag == 3){
					var check_msg = obj.text();
					if(check_msg.length > 0){
						msg += check_msg;
					}else{
						msg += '公司名称不合法！\n';
						name_tmp_msg = '公司名称不合法！';
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
		}
			
		var regu = "^(([0-9a-zA-Z]+)|([0-9a-zA-Z]+[_.0-9a-zA-Z-]*[0-9a-zA-Z-]+))@([a-zA-Z0-9-]+[.])+([a-zA-Z]|net|NET|asia|ASIA|com|COM|gov|GOV|mil|MIL|org|ORG|edu|EDU|int|INT|cn|CN|cc|CC)$";
		var re = new RegExp(regu);
		if(!email.length){	
			flag = false;
			msg += '公司邮箱不能为空！\n';
			email_msg = '公司邮箱不能为空！';
		}else if(email.search(re) == -1){
			flag = false;
			msg += '公司邮箱格式不正确！\n';
			email_msg = '公司邮箱格式不正确！';
		}

		if(act.length && act == 'madd'){
			var business_license_msg = $('#business_license_msg');
			var business_license_val = $.trim($('#business_license').val());
			if(!business_license_val.length){
				flag = false;
				var tmp_msg = "营业执照不能为空！";
				msg += tmp_msg+'\n';
				business_license_msg.show();
				business_license_msg.removeClass("attention");
				business_license_msg.addClass("error");
				business_license_msg.text(tmp_msg);
			}
		}else{
			var business_license_img = $("#business_license_img");
			if(!business_license_img.length){
				var business_license_msg = $('#business_license_msg');
				var business_license_val = $.trim($('#business_license').val());
				if(!business_license_val.length){
					flag = false;
					var tmp_msg = "营业执照不能为空！";
					msg += tmp_msg+'\n';
					business_license_msg.show();
					business_license_msg.removeClass("attention");
					business_license_msg.addClass("error");
					business_license_msg.text(tmp_msg);
				}
			}
		}

		if(aid && aid < 1){
			flag = false;
			var tmp_msg = '请选择公司负责人！';
			msg += tmp_msg+'\n';
			aid_msg.show();
			aid_msg.removeClass("attention");
			aid_msg.addClass("error");
			aid_msg.text(tmp_msg);
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