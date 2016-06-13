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
					<li><a href="#tab1" class="default-tab">添加公司成员</a></li>
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
										<label>请输入要匹配的用户名:</label>
										<input class="text-input third-input" type="text" id="search_name" name="search_name" value="" />
										<input type="button" id="search_name_btn" class="big-button" onclick="searchUser($(this))" value="搜索">
									</p>
								</div>
								<div class="rm_tab_left_box">
									<ul id="all_user_list">
										<?php if(is_array($user_list)): $i = 0; $__LIST__ = $user_list;if( count($__LIST__)==0 ) : echo "$user_list_empty" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><li class="user_list_<?php echo ($vo["aid"]); ?>_<?php echo ($vo["role_id"]); ?>">
											<span><a class='li_username' href='javascript:void(0)'><?php echo ($vo["username"]); if(!empty($vo['r_name'])): ?>-<?php echo ($vo["r_name"]); endif; ?></a>&nbsp;&nbsp;&nbsp;<input type='checkbox' name='select_role_user' role_id="<?php echo ((isset($vo["role_id"]) && ($vo["role_id"] !== ""))?($vo["role_id"]):0); ?>" value='<?php echo ((isset($vo["aid"]) && ($vo["aid"] !== ""))?($vo["aid"]):0); ?>' /></span>
										</li><?php endforeach; endif; else: echo "$user_list_empty" ;endif; ?>
									</ul>
								</div>
								<div class="rm_tab_left_button">
									<p>
										<input type="button" class="button" onclick="addCompanyUser()" value="添加" />
									</p>
								</div>
								<div class="rm_tab_right_box">
									<ul id="checked_user_list">
										<?php if(is_array($company_user_list)): $i = 0; $__LIST__ = $company_user_list;if( count($__LIST__)==0 ) : echo "$company_user_list_empty" ;else: foreach($__LIST__ as $key=>$v): $mod = ($i % 2 );++$i;?><li class="cu_list_<?php echo ($v["id"]); ?>_<?php echo ($v["role_id"]); ?>">
											<span><a class='li_username' href='javascript:void(0)'><?php echo ($v["username"]); if(!empty($v["r_name"])): ?>-<?php echo ($v["r_name"]); endif; ?></a><?php if(is_array($del_role_arr)): $i = 0; $__LIST__ = $del_role_arr;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$voo): $mod = ($i % 2 );++$i; if($voo == $v['role_id']): ?>&nbsp;&nbsp;&nbsp;<a class="cu_del_button" role_id="<?php echo ($v["role_id"]); ?>" onclick="delCompanyUser(<?php echo ($v["id"]); ?>)" title="删除" href="javascript:void(0)"><img alt="删除" src="/opened/Public/Img/Admin/icons/cross.png"></a><?php endif; endforeach; endif; else: echo "$company_user_list_empty" ;endif; ?></span>
										</li><?php endforeach; endif; else: echo "" ;endif; ?>
									</ul>
								</div>
								<div class="clear"></div>
								<input type="hidden" name="cid" value="<?php echo ($id); ?>" />
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
		var srarch_obj = $("#search_name_btn");
		searchUser(srarch_obj);
	});
	
	// 搜索用户
	function searchUser(obj){
		var cid = $("input[name='cid']").val();
		var name = $("#search_name").val();
		if(cid){
			if(typeof(name) != 'undefined'){
				$.ajax({
					type: "POST",
					url: "<?php echo U('Finance/ajax_search_user');?>",
					data: "id="+cid+"&name="+ name,
					dataType: "json",
					success: function(result){
						if(result.flag){
							var all_box = $("#all_user_list");
							var ul_box = $("#checked_user_list");
							all_box.empty();
							var data_str = result.data;
							if(data_str && data_str != ''){
								for(var m = 0;m < data_str.length;m++){
									var r_ru_id = data_str[m]['aid'];
									var u_name = data_str[m]['new_name'];
									var role_id = data_str[m]['role_id'];
									var li_html = "<li class='user_list_"+r_ru_id+"_"+role_id+"'><span><a class='li_username' href='javascript:void(0)'>"+u_name+"</a>&nbsp;&nbsp;&nbsp;<input type='checkbox' name='select_role_user' role_id='"+role_id+"' value='"+r_ru_id+"' /></span></li>";
									all_box.append(li_html);
								}
							}else{
								alert("未查找到类似用户！");
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
			}
		}else{
			msg = "数据有误！请刷新重试！";
			alert(msg);
			history.back();
		}
	}
	
	// 添加公司成员
	function addCompanyUser(){
		var msg = '';
		var cid = $("input[name='cid']").val();
		//alert(cid);
		if(cid){
			var checked_id = '';
			$("#all_user_list li input[type=checkbox]").each(function(){
				//var chk = $(this).find("[checked]");
				if(this.checked){
					if($(this).val()){
						checked_id += $(this).val()+"_"+$(this).attr("role_id")+",";
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
					url: "<?php echo U('Finance/ajax_add_company_user');?>",
					data: "cid="+ cid +"&checked_id="+ checked_id +"&__hash__="+ hash,
					dataType: "json",
					success: function(result){
						if(result.flag){
							var all_box = $("#all_user_list");
							var ul_box = $("#checked_user_list");
							var data_str = result.data;
							if(data_str.indexOf(',') > 0){
								//alert('多个');
								var r_aid = 0;
								var r_ru_id = 0;
								var arr_one = data_str.split(',');
								for(var m = 0;m < arr_one.length;m++){
									if(arr_one[m].indexOf("_") > 0){
										var arr = arr_one[m].split('_');
										if(arr.length > 1){
											r_ru_id = parseInt(arr[0]);
											r_aid = parseInt(arr[1]);
											var role_id = parseInt(arr[2]);
											all_box.find("li").each(function(){
												if($(this).find("input[name='select_role_user']") && $(this).find("input[name='select_role_user']").val() == r_aid && $(this).find("input[name='select_role_user']").attr("role_id") == role_id){
													var u_name = '';
													if($(this).find("a.li_username").length > 0){
														u_name = $(this).find("a.li_username").text();
													}
													var li_html = "<li class='cu_list_"+r_ru_id+"_"+role_id+"'><span><a class='li_username' href='javascript:void(0)'>"+u_name+"</a>&nbsp;&nbsp;&nbsp;<a class='cu_del_button' role_id='"+role_id+"' onclick='delCompanyUser("+r_ru_id+")' title='删除' href='javascript:void(0)'><img alt='删除' src='/opened/Public/Img/Admin/icons/cross.png'></a></span></li>";
													$(this).remove();
													if(ul_box.find('li.cu_list_0_0').length > 0){
														ul_box.find('li.cu_list_0_0').remove();
													}
													ul_box.append(li_html);
												}
											});
										}
									}
								}
								
							}else{
								var r_aid = 0;
								var r_ru_id = 0;
								var role_id = 0;
								if(checked_id.indexOf('_') > 0){
									var tmp_arr = checked_id.split('_');
									r_aid = parseInt(tmp_arr[0]);
								}else{
									r_aid = parseInt(checked_id);
								}
							
								if(data_str.indexOf('_') > 0){
									var arr = data_str.split('_');
									if(arr.length > 2){
										r_ru_id = parseInt(arr[0]);
										role_id = parseInt(arr[2]);
									}
								}else{
									r_ru_id = parseInt(data_str);
								}
								all_box.find("li").each(function(){
									if($(this).find("input[name='select_role_user']") && $(this).find("input[name='select_role_user']").val() == r_aid && $(this).find("input[name='select_role_user']").attr("role_id") == role_id){
										var u_name = '';
										if($(this).find("a.li_username").length > 0){
											u_name = $(this).find("a.li_username").text();
										}
										var li_html = "<li class='cu_list_"+r_ru_id+"_"+role_id+"'><span><a class='li_username' href='javascript:void(0)'>"+u_name+"</a>&nbsp;&nbsp;&nbsp;<a class='cu_del_button' role_id='"+role_id+"' onclick='delCompanyUser("+r_ru_id+")' title='删除' href='javascript:void(0)'><img alt='删除' src='/opened/Public/Img/Admin/icons/cross.png'></a></span></li>";
										$(this).remove();
										if(ul_box.find('li.cu_list_0_0').length > 0){
											ul_box.find('li.cu_list_0_0').remove();
										}
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
			msg = "数据有误！请刷新重试！";
			alert(msg);
			history.back();
		}
	}
	
	// 删除公司成员
	function delCompanyUser(id){
		if(id){
			$.ajax({
				type: "POST",
				url: "<?php echo U('Finance/ajax_del_company_user');?>",
				data: "id="+ id,
				dataType: "json",
				success: function(result){
					if(result.flag){
						if(result.data && result.data.id){
							var cu_id = result.data.id;
							var aid = result.data.aid;
							var role_id = result.data.role_id;
							var role_id_select = $("#role_id_select");
							var all_box = $("#all_user_list");
							var ul_box = $("#checked_user_list");
							ul_box.find("li").each(function(){
								if($(this).attr('class') == 'cu_list_'+cu_id+'_'+role_id){
									var u_name = '';
									if($(this).find("a.li_username").length > 0){
										u_name = $(this).find("a.li_username").text();
									}
									
									var li_html = "<li class='user_list_"+aid+"_"+role_id+"'><span><a class='li_username' href='javascript:void(0)'>"+u_name+"</a>&nbsp;&nbsp;&nbsp;<input type='checkbox' name='select_role_user' role_id='"+role_id+"' value='"+aid+"' /></span></li>";
									$(this).remove();
									role_id_select.val(0);
									all_box.find("li").each(function(){
										$(this).show();
									});
									if(all_box.find('li.user_list_0_0').length > 0){
										all_box.find('li.user_list_0_0').remove();
									}
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
	}
</script>