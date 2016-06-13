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
					<li><a href="#tab1" class="default-tab">角色成员管理</a></li>
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
					<div id="role_manager_box" class="tab_test">
						<div id="role_manager_box">
							<form action="<?php echo U('Access/mAdminRoleList');?>" method="post">
								<div class="rm_tab_top_box">
									<p>
										<label>选择管理员角色</label>
										<select id="role_id_select" name="role_id" onchange="toogleUserList($(this))">
											<option value="0">请选择角色</option>
											<?php if(is_array($tree_list)): $i = 0; $__LIST__ = $tree_list;if( count($__LIST__)==0 ) : echo "$tree_list_empty" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i; if($vo['is_edit'] == 1): ?><option value="<?php echo ($vo["id"]); ?>" <?php if($vo["id"] == $id): ?>selected<?php endif; ?>><?php echo ($vo["html"]); echo ($vo["name"]); ?></option>
												<?php else: ?>
													<option value="<?php echo ($vo["id"]); ?>" disabled ><?php echo ($vo["html"]); echo ($vo["name"]); ?></option><?php endif; endforeach; endif; else: echo "$tree_list_empty" ;endif; ?>
										</select>
									</p>
								</div>
								<div class="rm_tab_left_box">
									<ul id="all_user_list">
										<?php if(is_array($user_list)): $i = 0; $__LIST__ = $user_list;if( count($__LIST__)==0 ) : echo "$user_list_empty" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><li class="user_list_<?php echo ($vo["aid"]); ?>" <?php if($v['is_visible'] == 0): ?>style="display:none;"<?php endif; ?>>
											<span class="li_left">
												<a class='li_username' href='javascript:void(0)'><?php echo ($vo["username"]); if($vo['r_name']): ?>-<?php echo ($vo["r_name"]); endif; ?></a>
											</span>
											<span class="li_right">
												<input type='checkbox' name='select_role_user' role_id='<?php echo ($vo["r_id"]); ?>' value='<?php echo ($vo["aid"]); ?>' />
											</span>
										</li><?php endforeach; endif; else: echo "$user_list_empty" ;endif; ?>
									</ul>
								</div>
								<div class="rm_tab_left_button">
									<p>
										<input type="button" class="button" onclick="addRoleUser()" value="添加" />
									</p>
								</div>
								<div class="rm_tab_right_box">
									<ul id="checked_user_list">
										<?php if(is_array($role_user_list)): $i = 0; $__LIST__ = $role_user_list;if( count($__LIST__)==0 ) : echo "$role_user_list_empty" ;else: foreach($__LIST__ as $key=>$v): $mod = ($i % 2 );++$i;?><li class="ru_list_<?php echo ($v["aid"]); ?>" <?php if($v['is_visible'] == 0): ?>style="display:none;"<?php endif; ?>>
											<span class="li_left">
												<a class='li_username' href='javascript:void(0)'><?php echo ($v["username"]); ?>-<?php echo ($v["r_name"]); ?></a>
											</span>
											<span class="li_right">
												<a class="ru_del_button" aid="<?php echo ($v["aid"]); ?>" role_id="<?php echo ($v["r_id"]); ?>" onclick="delRoleUser(<?php echo ($v["aid"]); ?>)" title="删除" href="javascript:void(0)"><img alt="删除" src="/opened/Public/Img/Admin/icons/cross.png"></a>
											</span>
										</li><?php endforeach; endif; else: echo "$role_user_list_empty" ;endif; ?>
									</ul>
								</div>
								<div class="clear"></div>
								<input type="hidden" name="rid" value="<?php echo ($id); ?>" />
							</form>
						</div>
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
		var role_id_obj = $("#role_id_select");
		toogleUserList(role_id_obj);
	});
	
	// 切换角色
	function toogleUserList(obj){
		var rid = obj.val();
		if(rid){
			$("#all_user_list li").each(function(){
				var _this = $(this);
				_this.css('display','block');
				if($(this).find("input[name='select_role_user']")){
					$(this).find("input[name='select_role_user']").attr("checked",false);
				}
				$("#checked_user_list li").each(function(){
					var tmp_u_id = $(this).attr("class");
					if($(this).find("a.ru_del_button")){
						var tmp_r_id = $(this).find("a.ru_del_button").attr("role_id");
						var tmp_aid = $(this).find("a.ru_del_button").attr("aid");
						if(tmp_r_id.indexOf(",") > 0){
							var tmp_arr = tmp_r_id.split(',');
							if($.inArray(rid,tmp_arr) > -1){
								$(this).show();
								if(_this.attr("class") == "user_list_"+tmp_aid){
									_this.css('display','none');
								}
							}else{
								$(this).hide();	
							}
						}else{
							if(tmp_r_id == rid){
								$(this).show();
								if(_this.attr("class") == "user_list_"+tmp_aid){
									// _this.hide();
									_this.css('display','none');
								}
							}else{
								$(this).hide();
							}
						}
					}
				});
			});
		}
	}
	
	// 添加成员
	function addRoleUser(){
		var msg = '';
		var rid = $("input[name='rid']").val();
		//alert(rid);
		if(rid){
			var role_id = $("#role_id_select").val();
			var role_name = $("#role_id_select option:selected").text();
			if(role_name && role_name != '请选择角色'){
				role_name = role_name.replace(/-/gm,'');
			}else{
				role_name = '';
			}
			if(role_id > 0){
				var checked_id = '';
				$("#all_user_list li input[type=checkbox]").each(function(){
					//var chk = $(this).find("[checked]");
					if(this.checked){
						if($(this).val()){
							checked_id += $(this).val()+"_";
						}
					}
				}); 
				if(checked_id.length){
					checked_id=checked_id.substring(0,checked_id.length-1);
					//alert(checked_id);
					var hash;
					var hash_obj = $("input[name='__hash__']");
					if(hash_obj.length){
						hash = hash_obj.val();
					}
					$.ajax({
						type: "POST",
						url: "<?php echo U('Access/ajax_add_role_user');?>",
						data: "rid="+ rid +"&role_id="+ role_id + "&aid="+ checked_id +"&__hash__="+ hash,
						dataType: "json",
						success: function(result){
							if(result.flag){
								var all_box = $("#all_user_list");
								var ul_box = $("#checked_user_list");
								var data_str = result.data;
								if(data_str.indexOf(',') > 0){
									var r_role_id = 0;
									var r_user_id = 0;
									if(ul_box.find('li.cu_list_0').length > 0){
										ul_box.find('li.cu_list_0').remove();
									}
									var arr_one = data_str.split(',');
									for(var m = 0;m < arr_one.length;m++){
										if(arr_one[m].indexOf("_") > 0){
											var arr = arr_one[m].split('_');
											if(arr.length > 1){
												r_user_id = parseInt(arr[0]);
												r_role_id = parseInt(arr[1]);
												all_box.find("li").each(function(){
													if($(this).find("input[name='select_role_user']") && $(this).find("input[name='select_role_user']").val() == r_user_id){
														var old_role_id;
														if($(this).find("input[name='select_role_user']").length > 0){
															old_role_id = $(this).find("input[name='select_role_user']").attr('role_id');
															r_role_id = old_role_id+','+r_role_id;
															$(this).find("input[name='select_role_user']").attr('role_id',r_role_id);
															$(this).find("input[name='select_role_user']").attr("checked",false);
														}
														ul_box.find("li").each(function(){
															if($(this).attr('class') == 'ru_list_'+r_user_id){
																$(this).remove();
															}
														});
														
														var u_name = '';
														var li_html = '';
														if($(this).find("a.li_username").length > 0){
															u_name = $(this).find("a.li_username").text();
															//u_name = (u_name.indexOf("-") > 0) ? u_name : u_name+"-" ;
															if(u_name.indexOf("-") > 0){
																u_name = u_name+","+role_name;
															}else{
																u_name = u_name+'-'+role_name;
															}
															$(this).find("a.li_username").text(u_name);
														}else{
															u_name = u_name+","+role_name;
															$(this).remove();
															var all_li_html = "<li class='user_list_"+r_user_id+"' style='display:none;'><span class='li_left'><a class='li_username' href='javascript:void(0)'>"+u_name+"</a></span><span class='li_right'><input type='checkbox' name='select_role_user' role_id='"+r_role_id+"' value='"+r_user_id+"' /></span></li>";
															all_box.append(all_li_html);
														}
														// alert(u_name);
														li_html = "<li class='ru_list_"+r_user_id+"'><span class='li_left'><a class='li_username' href='javascript:void(0)'>"+u_name+"</a></span><span class='li_right'><a class='ru_del_button' aid='"+r_user_id+"' role_id='"+r_role_id+"' onclick='delRoleUser("+r_user_id+")' title='删除' href='javascript:void(0)'><img alt='删除' src='/opened/Public/Img/Admin/icons/cross.png'></a></span></li>";
														$(this).hide();
														ul_box.append(li_html);
													}
												});
											}
										}
									}
								}else{
									// alert('单个');
									var r_user_id = 0;
									var r_role_id = 0;
									if(data_str.indexOf('_') > 0){
										var arr = data_str.split('_');
										if(arr.length > 1){
											r_user_id = parseInt(arr[0]);
											r_role_id = parseInt(arr[1]);
										}else{
											r_user_id = parseInt(checked_id);
											r_role_id = parseInt(role_id);
										}
									}else{
										r_user_id = parseInt(checked_id);
										r_role_id = parseInt(role_id);
									}
									if(ul_box.find('li.cu_list_0').length > 0){
										ul_box.find('li.cu_list_0').remove();
									}
									all_box.find("li").each(function(){
										if($(this).find("input[name='select_role_user']") && $(this).find("input[name='select_role_user']").val() == r_user_id){
											var old_role_id;
											if($(this).find("input[name='select_role_user']").length > 0){
												old_role_id = $(this).find("input[name='select_role_user']").attr('role_id');
												r_role_id = old_role_id+','+r_role_id;
												$(this).find("input[name='select_role_user']").attr('role_id',r_role_id);
												$(this).find("input[name='select_role_user']").attr("checked",false);
											}
											ul_box.find("li").each(function(){
												if($(this).attr('class') == 'ru_list_'+r_user_id){
													$(this).remove();
												}
											});
											
											var u_name = '';
											var li_html = '';
											if($(this).find("a.li_username").length > 0){
												u_name = $(this).find("a.li_username").text();
												//u_name = (u_name.indexOf("-") > 0) ? u_name : u_name+"-" ;
												if(u_name.indexOf("-") > 0){
													u_name = u_name+","+role_name;
												}else{
													u_name = u_name+'-'+role_name;
												}
												$(this).find("a.li_username").text(u_name);
											}else{
												u_name = u_name+","+role_name;
												$(this).remove();
												var all_li_html = "<li class='user_list_"+r_user_id+"' style='display:none;'><span class='li_left'><a class='li_username' href='javascript:void(0)'>"+u_name+"</a></span><span class='li_right'><input type='checkbox' name='select_role_user' role_id='"+r_role_id+"' value='"+r_user_id+"' /></span></li>";
												all_box.append(all_li_html);
											}
											// alert(u_name);
											li_html = "<li class='ru_list_"+r_user_id+"'><span class='li_left'><a class='li_username' href='javascript:void(0)'>"+u_name+"</a></span><span class='li_right'><a class='ru_del_button' aid='"+r_user_id+"' role_id='"+r_role_id+"' onclick='delRoleUser("+r_user_id+")' title='删除' href='javascript:void(0)'><img alt='删除' src='/opened/Public/Img/Admin/icons/cross.png'></a></span></li>";
											$(this).hide();
											ul_box.append(li_html);
										}
									});
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
					msg = "请先选择要添加的用户！";
					alert(msg);
				}
			}else{
				msg = "请先选择角色！";
				alert(msg);
			}
		}else{
			msg = "数据有误！请刷新重试！";
			alert(msg);
			history.back();
		}
	}
	
	// 删除成员
	function delRoleUser(id){
		var msg = '';
		var role_id = $("#role_id_select").val();
		var role_name = $("#role_id_select option:selected").text();
		if(role_name && role_name != '--请选择角色--'){
			role_name = role_name.replace(/-/gm,'');
		}else{
			role_name = '无';
		}
		if(role_id > 0){
			if(id){
				$.ajax({
					type: "POST",
					url: "<?php echo U('Access/ajax_del_role_user');?>",
					data: "id="+ id +"&role_id="+role_id,
					dataType: "json",
					success: function(result){
						//alert(result.flag);
						//alert(result.msg);
						if(result.flag){
							if(result.data && result.data.role_id && result.data.user_id){
								var aid = result.data.user_id;
								var role_id = result.data.role_id;
								var role_id_select = $("#role_id_select");
								var all_box = $("#all_user_list");
								var ul_box = $("#checked_user_list");
								all_box.find("li").each(function(){
									if($(this).attr('class') == 'user_list_'+aid){
										$(this).remove();
									}
								});
								if(all_box.find('li.user_list_0').length > 0){
									all_box.find('li.user_list_0').remove();
								}
								ul_box.find("li").each(function(){
									if($(this).attr('class') == 'ru_list_'+aid){
										//alert('找到一个');
										var u_name = '';
										if($(this).find("a.li_username").length > 0){
											u_name = $(this).find("a.li_username").text();
											if(u_name.indexOf(',') > 0){
												u_name = u_name.replace(','+role_name,'');
												u_name = u_name.replace(role_name+',','');
											}else{
												u_name = u_name.replace(role_name,'');
												if(u_name.length - u_name.indexOf('-') == 1){
													u_name=u_name.substring(0,u_name.length-1);
												}
											}
											$(this).find("a.li_username").text(u_name);
										}
										$(this).hide();
										var li_html = "<li class='user_list_"+aid+"'><span class='li_left'><a class='li_username' href='javascript:void(0)'>"+u_name+"</a></span><span class='li_right'><input type='checkbox' name='select_role_user' role_id='"+role_id+"' value='"+aid+"' /></span></li>";
										all_box.append(li_html);
									}
								});
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
		}else{
			msg = "请先选择角色！";
			alert(msg);
		}
	}
</script>