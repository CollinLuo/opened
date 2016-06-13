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
					<li><a href="#tab1" class="default-tab">Table</a></li>
				</ul>
				<div class="clear"></div>
			</div>
			<!-- End .content-box-header -->
      		<div class="content-box-content">
					
				<!--Start #tab1-->
				<div class="tab-content default-tab" id="tab1">
					<?php if($act == 'mtrash'): ?><form action="<?php echo U('Business/mTrash');?>" method="post" name="business_form">
					<?php else: ?>
					<form action="<?php echo U('Business/index');?>" method="post" name="business_form"><?php endif; ?>
					<!-- This is the target div. id must match the href of this div's tab -->
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
											<span>输入要查找的文件名称:&nbsp;<input name="keyword" type="text" style="width:140px;"  value="<?php echo ($keyword); ?>" />&nbsp;&nbsp;</span>&nbsp;&nbsp;
											<span><input type="submit" class="button" value="搜索文件"></span>
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
									<th align="center" width="5%">编号</th>
									<th align="center" width="5%">
										<input class="check-all" name="list_checkbox_id[]" type="checkbox" value="" onclick="javascript:fanselect()" />	
									</th>
									<th align="center" width="25%">文件名称</th>
									<th align="center" width="25%">所属项目</th>
									<th align="center" width="10%">创建时间</th>
									<th align="center" width="10%">更新时间</th>
									<th align="center" width="10%">是否激活</th>
									<th align="center" width="10%">操作</th>
								</tr>
							</thead>
							<tbody>
								<?php if(is_array($list)): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "$list_empty" ;else: foreach($__LIST__ as $key=>$val): $mod = ($i % 2 );++$i;?><tr align="center" id="tr_file_<?php echo ($val["id"]); ?>" class="{if $val.status eq 0}bg_fuscous{/if}">
									<td align="center"><?php echo ($val["id"]); ?></td>
									<td align="center">
										<input type="checkbox" name="list_checkbox_id[]" value="<?php echo ($val["id"]); ?>">
									</td>
									<td align="center"><?php echo ((isset($val["name"]) && ($val["name"] !== ""))?($val["name"]):"未知文件"); ?></td>
									<td align="center"><?php echo ((isset($val["p_name"]) && ($val["p_name"] !== ""))?($val["p_name"]):"未知项目"); ?></td>
									<td align="center"><?php echo (date("Y-m-d H:i:s",$val["create_time"])); ?></td>
									<td align="center"><?php echo (date("Y-m-d H:i:s",$val["update_time"])); ?></td>
									<td align="center" onclick="toggleStatus(<?php echo ($val["id"]); ?>)" id="status_<?php echo ($val["id"]); ?>"><img src="/opened/Public/Img/Common/status_<?php echo ($val["status"]); ?>.gif" alt="<?php if($val["status"] == 1): ?>已激活<?php else: ?>未激活<?php endif; ?>" /></td>
									<td>
										<!-- Icons -->
										<a href="<?php echo U('File/mEdit','id='.$val['id']);?>" title="编辑"><img src="/opened/Public/Img/Admin/icons/pencil.png" alt="编辑" /> <?php if($act == 'index'): ?><a href="javascript:joinRecycle(<?php echo ($val['id']); ?>)" title="删除"><img src="/opened/Public/Img/Admin/icons/recycle.png" alt="删除" /></a><?php endif; ?> <?php if($act == 'mtrash'): ?><a href="javascript:restoreFile(<?php echo ($val['id']); ?>)" title="还原"><img src="/opened/Public/Img/Admin/icons/restore.png" alt="还原" /></a> <a href="javascript:delFile(<?php echo ($val['id']); ?>)" title="彻底删除"><img src="/opened/Public/Img/Admin/icons/cross.png" alt="彻底删除" /></a><?php endif; ?> 
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
											<a class="button" href="javascript:void(0);" onclick='$("form[name=business_form]").submit()'>筛选</a> 
											<a class="button" href="javascript:void(0);" onclick="">删除</a>
										</div>
										<!-- Start 翻页 -->
										<div class="pagination"><?php echo ($page); ?></div>
										<!-- End 翻页 -->
										<div class="clear"></div>
									</td>
								</tr>
							</tfoot>
							</form>

						</table>	
					</div>
					</form>
				</div> 	
				<!--End #tab1-->

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
	// 文件激活状态ajax切换
	function toggleStatus(id){
		if(id){
			$.ajax({
				type: "POST",
				url: "<?php echo U('File/ajax_update_status');?>",
				data: "id="+ id,
				dataType: "json",
				success: function(result){
					if(result.flag){
						$("#status_"+id+" img").attr('src','/opened/Public/Img/Common/status_'+result.data+'.gif');
						if(result.data == 1){
							$("#status_"+id+" img").attr('alt','已激活');
						}else{
							$("#status_"+id+" img").attr('alt','未激活');
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
	
	// ajax将文件移入回收站
	function joinRecycle(id){
		if(id){
			if(confirm("你确定要删除这个文件吗?")){
				$.ajax({
					type: "POST",
					url: "<?php echo U('File/ajax_join_recycle');?>",
					data: "id="+ id,
					dataType: "json",
					success: function(result){	
						if(result.flag){
							$("#tr_file_"+id).remove();
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
	
	// 从回收站还原文件
	function restoreFile(id){
		if(id){
			if(confirm("你确定要将这个文件还原吗?")){
				$.ajax({
					type: "POST",
					url: "<?php echo U('File/ajax_restore_file');?>",
					data: "id="+ id,
					dataType: "json",
					success: function(result){
						if(result.flag){
							$("#tr_file_"+id).remove();
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
	
	// ajax彻底删除文件
	function delFile(id){
		if(id){
			if(confirm("你确定要彻底删除这个文件吗?")){
				$.ajax({
					type: "POST",
					url: "<?php echo U('File/ajax_del_file');?>",
					data: "id="+ id,
					dataType: "json",
					success: function(result){
						if(result.flag){
							if(result.data && result.data == 'err_warning'){
								$("#tr_file_"+id).remove();
								alert(result.msg);
							}else{
								$("#tr_file_"+id).remove();
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
	
</script>