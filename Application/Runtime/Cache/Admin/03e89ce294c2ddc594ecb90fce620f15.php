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
					
				<!--Start #tab2-->
				<div class="tab-content default-tab" id="tab1">
					<form action="<?php echo U('Bbs/mComments');?>" name="comments_form" method="post">
					<!-- This is the target div. id must match the href of this div's tab -->
					<div class="notification attention png_bg">
						<div class="close">
						<a href="javascript:void(0);"><img src="/opened/Public/Img/Admin/icons/cross_grey_small.png" title="Close this notification" alt="close" /></a>
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
											<span>输入要查找的标题:&nbsp;<input name="keyword" type="text" style="width:140px;"  value="<?php echo ($keyword); ?>" />&nbsp;&nbsp;</span>&nbsp;&nbsp;
											<span><input type="submit" class="button" value="搜索评论"></span>
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
									<th align="center" width="15%">标题</th>
									<th align="center" width="10%">评论人名称</th>
									<th align="center" width="35%">评论内容</th>
									<th align="center" width="10%">评论时间</th>
									<th align="center" width="5%">是否审核</th>
									<th align="center" width="5%">是否锁定</th>
									<th align="center" width="10%">操作</th>
								</tr>
							</thead>

							<tbody>
								<?php if(is_array($list)): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "$list_empty" ;else: foreach($__LIST__ as $key=>$val): $mod = ($i % 2 );++$i;?><tr align="center" id="tr_ar_co_<?php echo ($val["cid"]); ?>" class="{if $val.is_lock eq 0}bg_fuscous{/if}">
									<td align="center"><?php echo ($val["cid"]); ?></td>
									<td align="center">
										<input type="checkbox" name="list_checkbox_id[]" value="<?php echo ($val["uid"]); ?>">
									</td>
									<td align="center"><?php echo ((isset($val["title"]) && ($val["title"] !== ""))?($val["title"]):""); ?></td>
									<td align="center"><?php echo ($val["comment_author_name"]); ?></td>
									<td align="center"><?php echo (substr($val["comment_content"],0,30)); ?></td>
									<td align="center"><?php echo ($val["comment_add_date"]); ?></td>
									<td align="center" onclick="toggleApprovedStatus(<?php echo ($val["cid"]); ?>)" id="approved_<?php echo ($val["cid"]); ?>"><img src="/opened/Public/Img/Common/status_<?php echo ($val["comment_approved"]); ?>.gif" /></td>
									<td align="center" onclick="toggleLockStatus(<?php echo ($val["cid"]); ?>)" id="lock_<?php echo ($val["cid"]); ?>"><img src="/opened/Public/Img/Common/status_<?php echo ($val["is_lock"]); ?>.gif" /></td>
									<td>
										<!-- Icons -->
										<!--<a href="<?php echo U('Blog/mCommentEdit','id='.$val['cid']);?>" title="编辑"><img src="/opened/Public/Img/Admin/icons/pencil.png" alt="编辑" /></a>--><a href="<?php echo U('Bbs/mEdit','id='.$val['cid']);?>" title="编辑"><img src="/opened/Public/Img/Admin/icons/pencil.png" alt="编辑" /></a> <a href="javascript:delComment(<?php echo ($val['cid']); ?>)" title="删除"><img src="/opened/Public/Img/Admin/icons/cross.png" alt="删除" /></a><!--<a href="#" title="编辑 Meta"><img src="/opened/Public/Img/Admin/icons/hammer_screwdriver.png" alt="编辑 Meta" /></a>--> 
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
												<option value="3" <?php if($type == 3): ?>selected<?php endif; ?>>已锁定</option>
												<option value="4" <?php if($type == 4): ?>selected<?php endif; ?>>未锁定</option>
											</select>
											<a href="javascript:select()">全选</a>/<a href="javascript:noselect()">全不选</a>
											<a class="button" href="javascript:void(0);" onclick='$("form[name=comments_form]").submit()'>筛选</a> 
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
				<!--End #tab2-->

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
	//文章评论锁定状态ajax切换
	function toggleLockStatus(id){
		if(id){
			$.ajax({
				type: "POST",
				url: "<?php echo U('Bbs/ajax_update_lock');?>",
				data: "id="+ id,
				dataType: "json",
				success: function(result){
					if(result.flag){
						$("#lock_"+id+" img").attr('src','/opened/Public/Img/Common/status_'+result.data+'.gif');
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
		}else{
			alert("请求失败！");
		}
	}
	
	//文章评论审批状态ajax切换
	function toggleApprovedStatus(id){
		if(id){
			$.ajax({
				type: "POST",
				url: "<?php echo U('Bbs/ajax_update_approved');?>",
				data: "id="+ id,
				dataType: "json",
				success: function(result){
					if(result.flag){
						$("#approved_"+id+" img").attr('src','/opened/Public/Img/Common/status_'+result.data+'.gif');
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
		}else{
			alert("请求失败！");
		}
	}

	//ajax删除评论
	function delComment(id){
		if(id){
			if(confirm('你确定要彻底删除这条评论吗?')){
				$.ajax({
					type: "POST",
					url: "<?php echo U('Bbs/ajax_del_comment');?>",
					data: "id="+ id,
					dataType: "json",
					success: function(result){
						//alerl('删除成功！');
						if(result.flag){
							$("#tr_ar_co_"+id).remove();
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