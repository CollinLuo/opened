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
          				<li><a href="#tab1" class="default-tab">权限设置</a></li>
        			</ul>
        			<div class="clear"></div>
      			</div>
      			<!-- End .content-box-header -->
      			<div class="content-box-content">
					
				<!--Start #tab2-->
				<div class="tab-content default-tab" id="tab1">
					<div class="notification attention png_bg">
						<div class="close">
						<a href="#"><img src="/opened/Public/Img/Admin/icons/cross_grey_small.png" title="Close this notification" alt="close" /></a>
						</div>
						<div> 这是一个公告栏。顺便一提,你可以关闭此通知。<font color="#ff5b6f"><span id="outmess" class="tip-msg error-msg sucess-msg">
	<?php echo ($clue_message); ?>		
</span></font></div>
    				</div>
					<div class="tab_list" id="node_box">
						<form class="node_box_form" action="<?php echo U('Access/mRoleAuthorization');?>" method="post">
						<table>
							<tbody>
								<?php if(is_array($list[0]['childrens'])): $i = 0; $__LIST__ = $list[0]['childrens'];if( count($__LIST__)==0 ) : echo "$list_empty" ;else: foreach($__LIST__ as $key=>$val): $mod = ($i % 2 );++$i;?><tr>
									<td width="18%" valign="top" class="first-cell">
										<div>
											<label class="font-bold" for="id_node_s_<?php echo ($val["id"]); ?>"><input name="action_code[]" type="checkbox" id="id_node_s_<?php echo ($val["id"]); ?>" value="<?php echo ($val["id"]); ?>"		onclick="partSelect('<?php echo ($val["id"]); ?>');" class="checkbox p_node_s_<?php echo ($val["id"]); ?>" <?php if($val["is_executive_power"] == 1): ?>checked='true'<?php endif; ?> />&nbsp;&nbsp;&nbsp;<?php echo ($val["title"]); ?>
											</label>
										</div>
									</td>
									<td>
										<?php if(is_array($val['childrens'])): $i = 0; $__LIST__ = $val['childrens'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$va): $mod = ($i % 2 );++$i;?><div style="width:200px;float:left;">
											<label for="id_node_s_<?php echo ($va["id"]); ?>"><input type="checkbox" name="action_code[]" value="<?php echo ($va["id"]); ?>" id="id_node_s_<?php echo ($va["id"]); ?>" class="checkbox node_s_<?php echo ($va["id"]); ?>" onclick="singleSelect('<?php echo ($va["id"]); ?>', '<?php echo ($val["id"]); ?>')" title="<?php echo ($va["name"]); ?>" <?php if($va["is_executive_power"] == 1): ?>checked='true'<?php endif; ?> />
											<?php echo ($va["title"]); ?></label>
											</div><?php endforeach; endif; else: echo "$list_empty" ;endif; ?>
									</td>
								</tr><?php endforeach; endif; else: echo "" ;endif; ?>
								<tr>
									<td align="center" colspan="2" class="td_center" >
										<input type="hidden" name="id" value="<?php echo ($id); ?>" />
										<input type="checkbox" name="checkall" value="allSelect" onclick="allSelect($(this));" class="checkbox" /><span class='node_s_all'>全选</span>
										&nbsp;&nbsp;&nbsp;&nbsp;
										<input type="submit" name="Submit" value="保存" class="button" />
									</td>
								</tr>						
							</tbody>	
						</table>
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
      	&#169; Copyright 2013 Your Company | Powered by <a href="http://www.trydemo.net">D.Apache.Luo</a> | <a href="#">Top</a> </small></div>
    	<!-- End #footer -->
  </div>
  <!-- End #main-content -->
</div>
</div>
</body>
</html>


<script type="text/javascript">
	//部分选中
	function partSelect(id){
		if(id){
			var c_box = $('#id_node_s_'+id).parent().parent().parent().next();
			if($('#id_node_s_'+id).prop('checked') == true){
				c_box.find('input[type=checkbox]').prop('checked',true);
			}else{	
				c_box.find('input[type=checkbox]').prop('checked',false);
			}	
		}		
	}

	//单个选中
	function singleSelect(id,pid){
		if(id && pid){
			if($("#id_node_s_"+id).is(':checked') == true){
				if($("#id_node_s_"+pid).is(':checked') == false){
					$("#id_node_s_"+pid).prop('checked',true);
				}
			}
		}
	}

	//全选
	function allSelect(obj){
		if(obj.prop('checked') == true){
			$('#node_box :checkbox').prop('checked',true);
			$('#node_box').find('span.node_s_all').text('全不选');
		}else{
			$('#node_box :checkbox').prop('checked',false);
			$('#node_box').find('span.node_s_all').text('全选');
		}
	}

</script>