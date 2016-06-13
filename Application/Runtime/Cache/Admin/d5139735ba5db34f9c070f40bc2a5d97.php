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
	<!-- End .shortcut-buttons-set -->
	<div id="main_box">
		<div class="content-box">
      		<!-- Start Content Box -->
      			<div class="content-box-header">
				<h3><?php echo ($navigation_bar); ?></h3>
        			<ul class="content-box-tabs">
          				<li><a href="#tab1" class="default-tab">角色列表</a></li>
						<li><a href="#tab2">添加角色</a></li>
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
					
					<div class="tab_test">
						<table>
							<thead>
								<tr>
									<th align="center" width="10%">编号</th>
									<th align="center" width="10%">角色名称</th>
									<th align="center" width="10%">创建时间</th>
									<th align="center" width="30%">备注</th>
									<th align="center" width="10%">审核</th>
									<th align="center" width="30%">操作</th>
								</tr>
							</thead>
							
							<tbody>
								<?php if(is_array($role_list)): $i = 0; $__LIST__ = $role_list;if( count($__LIST__)==0 ) : echo "$list_empty" ;else: foreach($__LIST__ as $key=>$val): $mod = ($i % 2 );++$i;?><tr align="center" class="{if $val.status eq 0}bg_fuscous{/if}">
									<td align="center"><?php echo ($val["id"]); ?></td>
									<td align="center"><?php echo ($val["name"]); ?></td>
									<td align="center"><?php echo (date("Y-m-d H:i:s",$val["create_time"])); ?></td>
									<td align="center"><?php echo ($val["remark"]); ?></td>
									<td align="center" onclick="toggleStatus(<?php echo ($val["id"]); ?>)" id="status_<?php echo ($val["id"]); ?>"><img src="/opened/Public/Img/Common/status_<?php echo ($val["status"]); ?>.gif" alt="<?php if($val["status"] == 1): ?>已审核<?php else: ?>未审核<?php endif; ?>" /></td>
									<td>
										<!-- Icons -->
										<a href="<?php echo U('Access/mRoleAuthorization','id='.$val['id']);?>" title="权限设置">权限设置</a>|<a href="<?php echo U('Access/mAdminRoleMember','id='.$val['id']);?>" title="成员管理">成员管理</a>|<a href="<?php echo U('Access/mAdminRoleEdit','id='.$val['id']);?>" title="编辑">编辑</a>|<a href="javascript:void(0);" title="删除">删除</a>
									</td>
								</tr><?php endforeach; endif; else: echo "$list_empty" ;endif; ?>
							</tbody>
							
							<tfoot>
								<tr>
									<td colspan="10">
										<!-- Start 翻页 -->
										<div class="pagination"><?php echo ($page); ?></div>
										<!-- End 翻页 -->
										<div class="clear"></div>
									</td>
								</tr>
							</tfoot>

						</table>	
					</div>
				</div> 	
				<!--End #tab2-->
				<div class="tab-content" id="tab2">
          			<form action="<?php echo U('Access/mAdminRoleAdd');?>" id="role_manager_form_add" method="post" onsubmit="return addValidate()">
            				<fieldset>
            					<p>
              						<label>角色名称:</label>
              						<input class="text-input small-input" type="text" id="role_name" onblur="checkName($(this))" name="name" />
              						<span id="role_add_input_name" check_flag="1" class="input-notification success hide png_bg">Successful message</span>
              						<!-- Classes for input-notification: success, error, information, attention -->
              						<br />
									<small>请输入合适的角色名称！可以使用中文，但禁止除[@][.]以外的特殊符号</small> 
								</p>
								<p>
              						<label>父级管理员:</label>
              						<select class="small-input" id="role_pid" name="role_pid">
										<?php if(is_array($tree_list)): $i = 0; $__LIST__ = $tree_list;if( count($__LIST__)==0 ) : echo "$tree_list_empty" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i; if($vo['is_edit'] == 1): ?><option value="<?php echo ($vo["id"]); ?>"><?php echo ($vo["html"]); echo ($vo["name"]); ?></option>
											<?php else: ?>
												<option value="<?php echo ($vo["id"]); ?>" disabled ><?php echo ($vo["html"]); echo ($vo["name"]); ?></option><?php endif; endforeach; endif; else: echo "$tree_list_empty" ;endif; ?>
									</select>
              						<!-- Classes for input-notification: success, error, information, attention -->
              						<br />
									<small>请选择合适的父级管理员！</small> 
								</p>
								<p>
              						<label>是否启用:</label>
              						<input type="radio" name="status" value="0" />禁用&nbsp;&nbsp;&nbsp;&nbsp;
              						<input type="radio" name="status" value="1" checked="checked" />启用 
								</p>
            					<p>
              						<label>角色描述:</label>
              						<textarea class="text-input textarea wysiwyg" name="remark" cols="79" rows="15"></textarea>
            					</p>
            					<p>
              						<input class="button" type="submit" value="提交" />
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
				url: "<?php echo U('Access/ajax_update_role_status');?>",
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

	function showMsg(obj){
		var ra_name_msg = $("#role_add_input_name");
	}

	function checkName(obj){
		//alert(obj.attr("name"));
		//alert('简单的小测试！');
		var ra_name = $('#role_name');
		var ra_name_msg = $("#role_add_input_name");
		var name = $.trim(ra_name.val());
		var msg = '';

		if(!name.length){
			msg = '角色名不能为空！\n';
			ra_name_msg.attr('check_flag',2);
			ra_name_msg.show();
			ra_name_msg.removeClass("error");
			ra_name_msg.addClass("success");
			ra_name_msg.text(msg);
			

		}else{
			//ajax判断重名
			$.ajax({
				type:"post",
				url:"<?php echo U('Access/ajax_check_role_name');?>",
				data:"name="+name,
				dataType:"json",
				success:function(result){
					if(result.flag){
						ra_name_msg.show();
						ra_name_msg.attr('check_flag',1);
						ra_name_msg.removeClass("error");
						ra_name_msg.addClass("success");
						ra_name_msg.text(result.msg);
					}else{
						ra_name_msg.show();
						ra_name_msg.attr('check_flag',3);
						ra_name_msg.removeClass("success");
						ra_name_msg.addClass("error");
						ra_name_msg.text(result.msg);
					}
				},
				error: function(XMLHttpRequest, textStatus, errorThrown) {
					//alert(XMLHttpRequest.status);
					//alert(XMLHttpRequest.readyState);
					//alert(textStatus);
					ra_name_msg.show();
					ra_name_msg.attr('check_flag',4);
					ra_name_msg.removeClass("success");
					ra_name_msg.addClass("error");
					ra_name_msg.text("请求错误！请刷新重试！");
				}
			});
		}

	}

	//表单提交验证
	function addValidate(){
		var flag = true;
		var msg = '';
		var name = $.trim($('#role_name').val());
		var obj = $("#role_add_input_name");
		
		if(!name.length){
			msg += '角色名不能为空！\n';
			flag = false;

		}else{
			//alert(123123123);
			var name_check_flag = obj.attr('check_flag');
			//alert(name_check_flag);
			if(name_check_flag != 1){
				flag = false;
				if(name_check_flag == 2){
					msg += '角色名不能为空！\n';
				}else if(name_check_flag == 3){
					var check_msg = obj.text();
					if(check_msg.length > 0){
						msg += check_msg;
					}else{
						msg += '角色名不合法！\n';
					}
				}else if(name_check_flag == 4){
					msg += '未知错误！';
				}else{
					msg += '未知错误！';
				}
			}
		}

		if(!flag){
			obj.show();
			obj.removeClass("success");
			obj.addClass("error");
			obj.text(msg);
			alert(msg);
			//调用屏幕抖动效果
			addLoadEvent(function(){ var p=new Array(15,30,15,0,-15,-30,-15,0);p=p.concat(p.concat(p));var i=document.forms[0].id;g(i).position='relative';shake(i,p,20);});
		}
		//return false;
		return flag;
	}
	
</script>