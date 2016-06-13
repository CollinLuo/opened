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
					<?php if($is_show_add == 1): ?><li><a href="#tab1">会议记录列表</a></li>
					<li><a href="#tab2" class="default-tab">添加会议记录</a></li>
					<?php else: ?>
					<li><a href="#tab1" class="default-tab">会议记录列表</a></li>
					<li><a href="#tab2">添加会议记录</a></li><?php endif; ?>
				</ul>
				<div class="clear"></div>
			</div>
			<!-- End .content-box-header -->
			<div class="content-box-content">	
				<!--Start #tab1-->
				<div class="tab-content <?php if($is_show_add == 0): ?>default-tab<?php endif; ?>" id="tab1">
					<form action="<?php echo U('Project/mMeetingManager');?>" method="post" name="meeting_minute_form">
					<div class="notification attention png_bg">
						<div class="close">
						<a href="#"><img src="/opened/Public/Img/Admin/icons/cross_grey_small.png" title="Close this notification" alt="close" /></a>
						</div>
						<div> 这是一个公告栏。顺便一提,你可以关闭此通知。<font color="#ff5b6f"><span id="outmess" class="tip-msg error-msg sucess-msg">
	<?php echo ($clue_message); ?>		
</span></font></div>
    				</div>
					<div class="search-box-table">
						<table width="100%" cellspacing="0" class="search-form">
							<tbody>
								<tr>
									<td>
										<div class="explain-col">
											<span>输入要查找的会议主题名称:&nbsp;<input name="keyword" type="text" style="width:140px;"  value="<?php echo ($keyword); ?>" />&nbsp;&nbsp;</span>&nbsp;&nbsp;
											<span><input type="submit" class="button" value="搜索会议记录"></span>
										</div>
									</td>
								</tr>
							</tbody>
						</table>
					</div>
					<div class="tab_test">
						<table>
							<thead>
								<tr>
									<th align="center" width="10%">编号</th>
									<th align="center" width="5%">
										<input class="check-all" name="list_checkbox_id[]" type="checkbox" value="" onclick="javascript:fanselect()" />	
									</th>
									<th align="center" width="20%">项目名称</th>
									<th align="center" width="30%">会议主题</th>
									<th align="center" width="10%">创建时间</th>
									<th align="center" width="10%">审核</th>
									<th align="center" width="15%">操作</th>
								</tr>
							</thead>
							
							<tbody>
								<?php if(is_array($list)): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "$list_empty" ;else: foreach($__LIST__ as $key=>$val): $mod = ($i % 2 );++$i;?><tr align="center" id="tr_meeting_<?php echo ($val["id"]); ?>" class="{if $val.status eq 0}bg_fuscous{/if}">
									<td align="center"><?php echo ($val["id"]); ?></td>
									<td align="center">
										<input type="checkbox" name="list_checkbox_id[]" value="<?php echo ($val["id"]); ?>">
									</td>
									<td align="center"><?php echo ($val["p_name"]); ?></td>
									<td align="center"><?php echo ($val["meeting_topic"]); ?></td>
									<td align="center"><?php echo (date("Y-m-d H:i:s",$val["create_time"])); ?></td>
									
									<td align="center" onclick="toggleStatus(<?php echo ($val["id"]); ?>)" id="status_<?php echo ($val["id"]); ?>"><img src="/opened/Public/Img/Common/status_<?php echo ($val["status"]); ?>.gif" alt="<?php if($val["status"] == 1): ?>已审核<?php else: ?>未审核<?php endif; ?>" /></td>
									<td>
										<!-- Icons -->
										<a href="<?php echo U('Project/mMeetingEdit','id='.$val['id']);?>" title="编辑"><img src="/opened/Public/Img/Admin/icons/pencil.png" alt="编辑" /></a> <a href="javascript:delLog(<?php echo ($val['id']); ?>)" title="彻底删除"><img src="/opened/Public/Img/Admin/icons/cross.png" alt="彻底删除" /></a>
									</td>
								</tr><?php endforeach; endif; else: echo "$list_empty" ;endif; ?>
							</tbody>
							
							<tfoot>
								<tr>
									<td colspan="10">
										<div class="bulk-actions align-left">
											<select name="dropdown_type">
												<option value="0">Choose an action...</option>
												<option value="1" <?php if($type == 1): ?>selected<?php endif; ?>>已审核</option>
												<option value="2" <?php if($type == 2): ?>selected<?php endif; ?>>未审核</option>
											</select>
											<a href="javascript:select()">全选</a>/<a href="javascript:noselect()">全不选</a>
											<a class="button" href="javascript:void(0);" onclick='$("form[name=project_form]").submit()'>筛选</a> 
											<a class="button" href="javascript:void(0);" onclick="">删除</a>
										</div>
										<!-- Start 翻页 -->
										<div class="pagination"><?php echo ($page); ?></div>
										<!-- End 翻页 -->
										<div class="clear"></div>
									</td>
								</tr>
							</tfoot>
						</table>	
					</div>
					</form>
				</div> 	
				<!--End #tab1-->
				<!--Start #tab2-->
				<div class="tab-content <?php if($is_show_add == 1): ?>default-tab<?php endif; ?>" id="tab2">
          			<form action="<?php echo U('Project/mMeetingManager','id='.$pid);?>" id="meeting_minute_form_add" method="post" onsubmit="return addValidate()">
						<fieldset>
							<p>
								<label>会议主题:</label>
								<input class="text-input small-input" type="text" id="meeting_topic" onblur="checkName($(this))" name="meeting_topic" value="<?php echo ($info["meeting_topic"]); ?>" />
								<span id="meeting_topic_msg" check_flag="1" class="input-notification attention png_bg">请输入合适的会议主题！</span>
								<!-- Classes for input-notification: success, error, information, attention -->
								<br />
								<small>请输入合适的会议主题！可以使用中文，但禁止除[@][.]以外的特殊符号</small> 
							</p>
							<p>
								<label>会议地址:</label>
								<input class="text-input small-input" type="text" id="address" name="address" value="<?php echo ($info["address"]); ?>" />
								<span class="input-notification optional png_bg">选填项</span>
								<!-- Classes for input-notification: success, error, information, attention, optional -->
								<br />
								<small>请输入会议地址！</small> 
							</p>
							<p>
								<label>会议记录人:</label>
								<input class="text-input small-input" type="text" id="meeting_noter" name="meeting_noter" value="<?php echo ($info["meeting_noter"]); ?>" />
								<span class="input-notification optional png_bg">选填项</span>
								<!-- Classes for input-notification: success, error, information, attention, optional -->
								<br />
								<small>请输入会议记录人！</small> 
							</p>
							<p>
								<label>会议参与者:</label>
								<input class="text-input small-input" type="text" id="meeting_organizers" name="meeting_organizers" value="<?php echo ($info["meeting_organizers"]); ?>" />
								<span class="input-notification optional png_bg">选填项</span>
								<!-- Classes for input-notification: success, error, information, attention, optional -->
								<br />
								<small>请输入会议参与者！</small> 
							</p>
							<p>
								<label>会议执行者:</label>
								<input class="text-input small-input" type="text" id="executor" name="executor" value="<?php echo ($info["executor"]); ?>" />
								<span class="input-notification optional png_bg">选填项</span>
								<!-- Classes for input-notification: success, error, information, attention, optional -->
								<br />
								<small>请输入会议执行者！</small> 
							</p>
							<p>
								<label>开始时间:</label>
								<input class="input-date-selection small-input" type="text" id="start_time" name="start_time" value="<?php echo ($info["start_time"]); ?>" onclick="WdatePicker({lang:'zh-cn',dateFmt:'yyyy-MM-dd HH:mm:ss'})" />
								<span class="input-notification optional png_bg">选填项</span>
								<!-- Classes for input-notification: success, error, information, attention, optional -->
								<br />
								<small>请选择开始时间！</small> 
							</p>
							<p>
								<label>结束时间:</label>
								<input class="small-input input-date-selection" type="text" id="end_time" name="end_time" value="<?php echo ($info["end_time"]); ?>"  onclick="WdatePicker({lang:'zh-cn',dateFmt:'yyyy-MM-dd HH:mm:ss'})" />
								<span class="input-notification optional png_bg">选填项</span>
								<!-- Classes for input-notification: success, error, information, attention, optional -->
								<br />
								<small>请选择结束时间！</small> 
							</p>
							<p>
								<label>是否审核通过:</label>
								<input type="radio" name="status" value="0" />禁用&nbsp;&nbsp;&nbsp;&nbsp;
								<input type="radio" name="status" value="1" checked="checked" />启用 
							</p>
							<p>
								<label>会议内容:</label>
								<textarea class="text-input textarea wysiwyg" name="content" cols="79" rows="10"><?php echo ($info["content"]); ?></textarea>
							</p>
							<p>
								<label>会议备注:</label>
								<textarea class="text-input textarea wysiwyg" name="remark" cols="79" rows="5"><?php echo ($info["remark"]); ?></textarea>
							</p>
							<p>
								<input type="hidden" name="pid" value="<?php echo ($pid); ?>">
								<input class="button" type="submit" value="提交" />
								<input name="reset" type="reset" value="重置" />
							</p>
						</fieldset>
						<div class="clear"></div>
						<!-- End .clear -->
          			</form>
        		</div>
        		<!-- End #tab2 -->
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

	//角色审核状态ajax切换
	function toggleStatus(id){		
		if(id){
			$.ajax({
				type: "POST",
				url: "<?php echo U('Project/ajax_update_mm_status');?>",
				data: "id="+ id,
				dataType: "json",
				success: function(result){
					if(result.flag){
						$("#status_"+id+" img").attr('src','/opened/Public/Img/Common/status_'+result.data+'.gif');
						if(result.data == 1){
							$("#status_"+id+" img").attr('alt','已审核');
						}else{
							$("#status_"+id+" img").attr('alt','未审核');
						}
					}else{
						alert(result.msg);
					}
				},
				error: function(XMLHttpRequest, textStatus, errorThrown) {
                    alert(XMLHttpRequest.status);
                    //alert(XMLHttpRequest.readyState);
                    //alert(textStatus);
					//console.log(data);
                }
			});
		}else{
			alert("请求失败！");
		}
	}

	function checkName(obj){
		var mm_name = $('#meeting_topic');
		var mm_name_msg = $("#meeting_topic_msg");
		var name = $.trim(mm_name.val());
		var msg = '';
		if(!name.length){
			msg = '会议主题不能为空！\n';
			mm_name_msg.show();
			mm_name_msg.removeClass("error");
			mm_name_msg.addClass("success");
			mm_name_msg.text(msg);
		}else{
			mm_name_msg.show();
			mm_name_msg.removeClass("error");
			mm_name_msg.addClass("success");
			mm_name_msg.text('会议主题输入正确！');
		}
	}
	
	//删除会议记录
	// ajax彻底删除文件
	function delLog(id){
		if(id){
			if(confirm("你确定要彻底删除这个记录吗?")){
				$.ajax({
					type: "POST",
					url: "<?php echo U('Project/ajax_del_log');?>",
					data: "id="+ id,
					dataType: "json",
					success: function(result){
						if(result.flag){
							if(result.data && result.data == 'err_warning'){
								$("#tr_meeting_"+id).remove();
								alert(result.msg);
							}else{
								$("#tr_meeting_"+id).remove();
							}
						}else{
							alert(result.msg);
						}
					},
					error: function(XMLHttpRequest, textStatus, errorThrown) {
						//alert(XMLHttpRequest.status);
						//alert(XMLHttpRequest.readyState);
						//alert(textStatus);
						alert("请求错误！请刷新重试！");
					}
				});
			}	
		}else{
			alert("请求失败！");
		}
	}

	//表单提交验证
	function addValidate(){
		var flag = true;
		var msg = '';
		var name = $.trim($('#meeting_topic').val());
		var obj = $("#meeting_topic_msg");
		
		if(!name.length){
			msg += '会议主题不能为空！\n';
			flag = false;
			obj.show();
			obj.removeClass("success");
			obj.addClass("error");
			obj.text(msg);
		}

		if(!flag){
			alert(msg);
			//调用屏幕抖动效果
			addLoadEvent(function(){ var p=new Array(15,30,15,0,-15,-30,-15,0);p=p.concat(p.concat(p));var i=document.forms[0].id;g(i).position='relative';shake(i,p,20);});
		}
		return flag;
	}
	
</script>