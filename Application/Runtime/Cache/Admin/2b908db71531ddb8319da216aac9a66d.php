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
          				<li><a href="#tab1" class="default-tab">管理业务明细</a></li>
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
						<?php if($act == 'mbusinessdetails'): ?><form action="<?php echo U('Business/mBusinessDetails');?>" id="company_form" method="post" enctype="multipart/form-data" onsubmit="return addValidate()">
						<?php else: ?>
						<form action="<?php echo U('Business/index');?>" id="company_form" method="post" enctype="multipart/form-data" onsubmit="return addValidate()"><?php endif; ?>
							<fieldset>
								<p>
									<span class="ud_width">业务名称:</span>
									<span><?php echo ($info["name"]); ?></span>
								</p>
								<p>
              						<span class="ud_width">所属项目:</span>
									<span><?php echo ($info["p_name"]); ?></span>
            					</p>
								<p>
              						<span class="ud_width">开始时间:</span>
									<span><?php echo ($info["start_time"]); ?></span>
            					</p>
								<p>
									<span class="ud_width">结束时间:</span>
									<span><?php echo ($info["end_time"]); ?></span>
            					</p>
								<p>
									<span class="ud_width">业务预算:</span>
									<span><?php echo ($info["cost"]); ?></span>
								</p>
								<p>
									<span class="ud_width">业务耗时:</span>
									<span><?php echo ($info["spend_time"]); ?></span>
								</p>
								<p>
									<span class="ud_width">允许打分:</span>
									<span><?php if($info['is_grade'] == 0): ?>不允许<?php else: ?>允许<?php endif; ?></span>
								</p>
								<p>
									<span class="ud_width">是否激活:</span>
									<span><?php if($info['status'] == 0): ?>未激活<?php else: ?>已激活<?php endif; ?></span>
								</p>
								<?php if($act == 'medit'): ?><p>
									<span class="ud_width">业务完成度:</span>
									<span><?php echo ($info["completeness"]); ?>%</span>
            					</p><?php endif; ?>
								<p>
									<span class="ud_width">业务评价:</span>
									<span><?php echo ($info["appraise"]); ?></span>
								</p>
								<p>
									<span class="ud_width">业务备注:</span>
									<span><?php echo ($info["remark"]); ?></span>
								</p>
								<br />
								<div class="details_box">
									<p class="box_content_top">
										<span>选择用户:</span>
										<select name="pid" class="text-input" onchange="changeUser($(this))">
											<option value="0" pid="">请选择指定用户</option>
											<?php if(is_array($company_user_list)): $i = 0; $__LIST__ = $company_user_list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><option value="<?php echo ($vo["aid"]); ?>" aid="<?php echo ($vo["aid"]); ?>"><?php echo ($vo["username"]); ?></option><?php endforeach; endif; else: echo "" ;endif; ?>
										</select>
										&nbsp;&nbsp;&nbsp;
										<span>业务耗时:</span>
										<input class="text-input number-input" type="text" onkeyup="checkNum($(this))" name="spend_time" value="0" />
										&nbsp;&nbsp;&nbsp;
										<a class="add_user" href="javascript:void(0);" onclick="addNewUser($(this))">继续添加</a>
									</p>
								</div>								
								<p>
									<input type="hidden" name='bid' value="<?php echo ($info["id"]); ?>" />
									<?php if(empty($user_str)): ?><input type="hidden" name='act' value="<?php echo ($act_a); ?>" />
									<?php else: ?>
									<input type="hidden" name='act' value="<?php echo ($act_e); ?>" /><?php endif; ?>
									
									<input type="hidden" id="user_str" name="user_str" value="<?php echo ($user_str); ?>" />
									<input type="submit" value="提交" />
									<input name="button" type="button" onclick="<?php echo U('Business/index');?>" value="取消" />
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

		initUser();

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
		}).bind("paste",function(){  // CTR+V事件处理    
			$(this).val($(this).val().replace(/[^0-9.]/g,''));     
		}).css("ime-mode", "disabled"); // CSS设置输入法不可用 
		
		var is_edit_obj = $("input[name='id']");
		if(is_edit_obj.length > 0){
			var min_c_amounts = 0;
			var max_c_amounts = 100;
			$("#completeness").keyup(function(){
				var c_num = $("#completeness").val();
				//alert(c_num);
				if(c_num && parseFloat(c_num) > max_c_amounts){
					$(this).val(max_c_amounts);  
				}else if(c_num && parseFloat(c_num) < min_c_amounts){
					$(this).val(min_c_amounts); 
				}else{
					$(this).val($(this).val().replace(/[^0-9.]/g,''));
				}  
			}).bind("paste",function(){  //CTR+V事件处理    
				$(this).val($(this).val().replace(/[^0-9.]/g,''));     
			}).css("ime-mode", "disabled"); //CSS设置输入法不可用
		}
		
	});
	
	function initUser(){
		var user_str = $("#user_str").val();
		var p_box = $("form .details_box");
		p_box.children().each(function(){
			if($(this).attr('class') != 'box_content_top'){
				$(this).remove();
			}
		});
		if(user_str && user_str != ''){
			if(user_str.indexOf(',') > 0){
				var arr_one = user_str.split(',');
				var box_content = p_box.find('p.box_content_top').html();
				var reduce_icon = "<a class='reduce_user' href='javascript:void(0);' onclick='reduceOldUser($(this))'>删除</a>";
				var new_box = "<p lock_mark='current_object_lock' class='box_content'>"
					+ box_content + "" + reduce_icon + "</p>"
				for(var m = 0;m < arr_one.length;m++){
					//alert(arr_one[m]);
					if(arr_one[m] && arr_one[m].indexOf('_')){
						var arr_two = arr_one[m].split('_');
						//alert(arr_two.length);
						if(arr_two && arr_two.length > 1){
							if(m > 0){
								p_box.append(new_box);
								p_box.children().each(function(){
									if($(this).attr('lock_mark') == 'current_object_lock'){
										//alert(arr_two[1]+"_"+arr_two[2]);
										$(this).find("select[name='pid']").val(arr_two[1]);
										$(this).find("input[name='spend_time']").val(arr_two[2]);
										$(this).removeAttr("lock_mark");
									}
								});
							}else{
								p_box.find('p.box_content_top').find("select[name='pid']").val(arr_two[1]);
								p_box.find('p.box_content_top').find("input[name='spend_time']").val(arr_two[2]);
							}
							
						}
					}
				}
			}else{
				if(user_str.indexOf('_') > 0){
					var arr_one = user_str.split('_');
					var top_obj = p_box.find("p.box_content_top");
					if(top_obj.length > 0 && arr_one.length > 1){
						top_obj.find("select[name='pid']").val(arr_one[1]);
						top_obj.find("input[name='spend_time']").val(arr_one[2]);
					}
				}else{
					var top_obj = p_box.find("p.box_content_top");
					if(top_obj.length > 0){
						top_obj.find("select[name='pid']").val('0');
						top_obj.find("input[name='spend_time']").val('0');
					}
				}
			}
		}
	}
	
	function changeUser(obj){
		//锁定当前select
		obj.attr("lock_mark","current_object_lock");
		var check_id = obj.val();
		var p_box = obj.parent().parent();
		//检测选中项是否重复
		var top_box = $("form .details_box");
		var sel_list = top_box.find("select[name='pid']");

		if (sel_list.size()) {
			sel_list.each(function(i) {
				//根据标识排除当前select对象
				if (!$(this).attr("lock_mark")
						|| $(this).attr("lock_mark") == undefined) {
					if ($(this).val() && typeof ($(this).val()) != undefined
							&& $(this).val() != 0) {
						if ($(this).val() == check_id) {
							obj.val(0);
							alert("您已经选择过该用户！");
						}
					}
				}
			});
		}
		//清除锁定
		obj.removeAttr("lock_mark");
	}
	
	function checkNum(obj){
		var max_time_num = 87600;  // 最长耗时10年
		var num = obj.val();
		if(num && parseInt(num) > max_time_num){
			obj.val(max_time_num);  
		}else{
			obj.val(parseInt(obj.val().replace(/[^0-9]/g,'')));
		} 
		obj.bind("paste",function(){  // CTR+V事件处理    
			$(this).val($(this).val().replace(/[^0-9]/g,''));     
		}).css("ime-mode", "disabled"); // CSS设置输入法不可用 
	}
	
	function addNewUser(obj){
		var p_box = $("form .details_box");
		var reduce_icon = "<a class='reduce_user' href='javascript:void(0);' onclick='reduceOldUser($(this))'>删除</a>";		
		var box_content = obj.parent().html();
		var str = "";

		//判断复制的对象是否已经有删除
		if (typeof (obj.parent().find("a.reduce_user")) == undefined
				|| obj.parent().find("a.reduce_user").html() == null) {
			str = "<p class='box_content'>"
					+ box_content + "" + reduce_icon + "</p>";
		} else {
			str = "<p class='box_content'>"
					+ box_content + "</p>";
		}
		p_box.append(str);	
	}
	
	function reduceOldUser(obj){
		if (obj.parent().parent()
				&& obj.parent().parent() != undefined) {
			if (obj.parent().parent().children() != undefined
					&& obj.parent().parent().children().length > 1) {
				obj.parent().remove();
			}
		} else {
			obj.parent().remove();
		}
	}
	
	//表单提交验证
	function addValidate(){
		var flag = true;
		var msg = '';
		//给隐藏域赋值
		var user_str = "";
		var top_box = $("form .details_box");
		var r_str = $("#user_str");
		var check_user_val = 0;
		top_box.children().each(function(){
			if (typeof ($(this).find("select[name='pid']")) != undefined && $(this).find("select[name='pid']").val() > 0) {
				var s_val = $(this).find("select[name='pid']").val();
				var temp_str = "";
				temp_str += s_val+ "_";
				var st_obj = $(this).find("input[name='spend_time']");
				if (typeof (st_obj) != undefined && st_obj.html() != null && st_obj.length > 0) {
					st_val = st_obj.val();
					if (st_val && parseInt(st_val) > 0) {
						 temp_str += parseInt(st_val)+",";
						 check_user_val += 1;
					} else {
						temp_str += "0,";
					}
				} else {
					temp_str += "0,";
				}

				user_str += temp_str;
			}
		});
		if(user_str.length > 0 && user_str.indexOf(',') > 0){
			user_str = user_str.substring(0,user_str.length-1);
		}
		r_str.val(user_str);
		//校验数据是否正确
		if(check_user_val < 1){
			flag = false;
			msg = "未完整添加任意一项详情！";
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