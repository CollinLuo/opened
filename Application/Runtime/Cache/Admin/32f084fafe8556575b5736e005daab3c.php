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
				<div class="tab-content default-tab" id="tab1">
					<div class="notification attention png_bg">
						<div class="close">
						<a href="#"><img src="/opened/Public/Img/Admin/icons/cross_grey_small.png" title="Close this notification" alt="close" /></a>
						</div>
						<div> 这是一个公告栏。顺便一提,你可以关闭此通知。<font color="#ff5b6f"><span id="outmess" class="tip-msg error-msg sucess-msg">
	<?php echo ($clue_message); ?>		
</span></font></div>
    				</div>

					<div class="u_tab_form">
						<table align="center" width="100%">
							<?php if($act == 'madd'): ?><form action="<?php echo U('User/mAdd');?>"  name="user_form" enctype="multipart/form-data" method="post">
							<?php elseif($act == 'medit'): ?>
							<form action="<?php echo U('User/mEdit');?>" name="user_form" enctype="multipart/form-data" method="post">
							<?php else: ?>
							<form action="<?php echo U('User/index');?>" name="user_form" enctype="multipart/form-data" method="post"><?php endif; ?>
								<tr>
									<th><span class="ud_width">用户名:<font color="red">*</font></span></th>
									<td>
										<?php if($act == 'madd'): ?><input name="username" type="text" />&nbsp;&nbsp;可以使用中文，但禁止除[@][.]以外的特殊符号
										<?php else: ?>
											<input name="username" type="text"  value="<?php echo ($user_info["username"]); ?>" readonly='readonly' disabled="disabled" /><?php endif; ?>
									</td>
								</tr>
								<tr>
									<th><span class="ud_width">登录密码:<font color="red">*</font></span></th>
									<td><input type="password" name="password" value="" /></td>
								</tr>
								<tr>
									<th><span class="ud_width">确认密码:<font color="red">*</font></span></th>
									<td><input type="password" name="repwd"  value="" /></td>
								</tr>
								<tr>
									<th><span class="ud_width">电子邮件:<font color="red">*</font></span></th>
									<td><input type="text" name="email" value='<?php echo ($user_info["email"]); ?>' /><?php if($act == 'madd'): ?>&nbsp;&nbsp;请正确填写你的电子邮件地址<?php endif; ?></td>
								</tr>
								<tr>
									<th><span class="ud_width">昵称:</span></th>
									<td><input type="text" name="nickname" value='<?php echo ($user_info["nickname"]); ?>' /></td>
								</tr>
								<tr>
									<th><span class="ud_width">手机:</span></th>
									<td><input type="text" name="mobile_number" value='<?php echo ($user_info["mobile_number"]); ?>' /></td>
								</tr>
								<tr>
									<th><span class="ud_width">QQ:</span></th>
									<td>
										<?php if(($user_info["qq"] == 0) OR ($user_info["qq"] == '')): ?><input type="text" name="qq" value='' />
										<?php else: ?>
										<input type="text" name="qq" value='<?php echo ($user_info["qq"]); ?>' /><?php endif; ?>
									</td>
								</tr>
								<tr>
									<th><span class="ud_width">头像:</span></th>
									<td>
										<?php if($act == 'madd'): ?><input class="text-input small-input" type="file" id="avatar" name="avatar" value="<?php echo ($user_info["avatar"]); ?>" />
										<span id="avatar_msg" class="input-notification optional png_bg">请上传头像！</span>
										<!-- Classes for input-notification: success, error, information, attention, optional -->
										<br />
										<small>请上传头像！</small>
										<?php elseif($act == 'medit'): ?>
										<?php if($user_info['avatar']): ?><img id="avatar_img" src="/opened/Uploads/Common/avatar_small/<?php echo ($user_info["avatar"]); ?>" alt="头像" />
										<br />
										<input class="text-input small-input" type="file" id="avatar" name="avatar" value="<?php echo ($user_info["avatar"]); ?>" />
										<br />
										<small>重新选择会覆盖原有的图片！</small>
										<?php else: ?>
										<input class="text-input small-input" type="file" id="avatar" name="avatar" value="<?php echo ($user_info["avatar"]); ?>" />
										<span id="avatar_msg" class="input-notification optional png_bg">请上传头像！</span>
										<!-- Classes for input-notification: success, error, information, attention, optional -->
										<br />
										<small>请上传头像！</small><?php endif; endif; ?>									
									</td>
								</tr>
								<tr>
									<th><span class="ud_width">性别:</span></th>
									<td>
										<?php if($act == 'madd'): ?><input type="radio" name="sex" value="1"   />男
											<input type="radio" name="sex" value="2" />女
											<input type="radio" name="sex" value="0" checked />保密	
										<?php else: ?>
											<?php if($user_info["sex"] == 1): ?><input type="radio" name="sex" value="1" checked />男
												<input type="radio" name="sex" value="2" />女
												<input type="radio" name="sex" value="0" />保密	
											<?php elseif($user_info["sex"] == 2): ?>
												<input type="radio" name="sex" value="1" />男
												<input type="radio" name="sex" value="2" checked />女
												<input type="radio" name="sex" value="0" />保密
											<?php else: ?>
												<input type="radio" name="sex" value="1"   />男
												<input type="radio" name="sex" value="2" />女
												<input type="radio" name="sex" value="0" checked />保密<?php endif; endif; ?>

									</td>
								</tr>
								<tr>
									<th><span class="ud_width">是否禁用:</span></th>
									<td>
										<?php if($act == 'madd'): ?><input type="radio" name="status" value="1" />启用
											<input type="radio" name="status" value="0" checked="checked" />禁用
										<?php else: ?>
											<?php if($user_info["status"] == 1): ?><input type="radio" name="status" value="1" checked="checked" />启用
												<input type="radio" name="status" value="0" />禁用
											<?php else: ?>
												<input type="radio" name="status" value="1" />启用
												<input type="radio" name="status" value="0" checked="checked" />禁用<?php endif; endif; ?>
										
									</td>
								</tr>
								<tr>
									<th><span class="ud_width">备注:</span></th>
									<td>
										
										<textarea name="remark" rows="2" cols="20" id="remark" class="inputtext" style="height: 122px; width: 400px; margin-top: 0px; margin-bottom: 0px;"><?php echo ($user_info["remark"]); ?></textarea>
									</td>
								</tr>
								<tr>
									<td></td>
									<td>
										<?php if($act == 'madd'): ?><input type="submit" value="添加" />
										<?php elseif($act == 'medit'): ?>
											<input type="hidden" name='id' value="<?php echo ($user_info["aid"]); ?>" />	
											<input name="submit" type="submit" value="修改" /><?php endif; ?>
										<input name="reset" type="reset" value="重置" />
									</td>
								</tr>
							</form>	
						</table>		
					</div>
				</div>  
			<!-- End #tab1 -->	
			</div>
      		<!-- End .content-box-content -->
    	</div>
    	<!-- End .content-box -->	
    	<div class="clear"></div>
    	<div id="footer"> <small>
      	<!-- Remove this notice or replace it with whatever you want -->
      	&#169; Copyright 2013 Your Company | Powered by <a href="http://www.cheshop.com">D.Apache.Luo</a> | <a href="#">Top</a> </small> </div>
    	<!-- End #footer -->
  </div>
  <!-- End #main-content -->
</div>
</div>
</body>
</html>