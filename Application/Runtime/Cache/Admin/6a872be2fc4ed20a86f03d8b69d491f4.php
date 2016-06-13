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

<body style="background:#f0f0f0 url('/opened/Public/Img/Admin/bg-body.gif') top left repeat-y;">
	<div id="body-wrapper">
		<!-- Wrapper for the radial gradient background -->
		<div id="sidebar">
			<div id="sidebar-wrapper">
				<!-- Sidebar with logo and menu -->
				<h1 id="sidebar-title"><a href="javascript:void(0);">Simpla Admin</a></h1>
				<!-- Logo (221px wide) -->
				<a href="<?php echo U('Index/mIndex');?>"><img id="logo" src="/opened/Public/Img/Admin/logo.png" alt="Simpla Admin logo" /></a>
				<!-- Sidebar Profile links -->
				<div id="profile-links"> 您好, <a href="javascript:void(0);" title="进入个人中心"><?php echo (substr($_SESSION['my_info']['nickname'],0,9)); ?></a>,您有 <a href="#messages" rel="modal" title="3 Messages">3 Messages</a><br />
				<a href="<?php echo U('Index/index');?>"  target="_parent" title="返回主页">返回主页</a> | <a href="<?php echo U('Public/loginOut');?>"  target="_parent" title="退出">注销</a> 
				</div>
				<ul id="main-nav">
					<!-- Accordion Menu -->
					<!--
					<li><a href="#" class="nav-top-item current"> 仪表盘 </a> 
						<ul class="none-css-main-item">
							<li><a class="current" href="<?php echo U('Index/mIndex');?>" target="main">首页</a></li>
							<li><a href="<?php echo U('Index/mIndex');?>" target="main">更新</a></li>
						</ul>
					</li>
					-->

					<li> <a href="#" class="nav-top-item "> 门户管理 </a>
						<ul class="none-css-main-item">
							<!--
							<li><a href="javascript:void(0)" target="main">管理栏目（不做）</a></li>
							<li><a href="javascript:void(0)" target="main">添加栏目（不做）</a></li>
							<li><a href="javascript:void(0)" target="main">管理广告位（不做）</a></li>
							<li><a href="javascript:void(0)" target="main">添加广告（不做）</a></li>
							<li><a href="javascript:void(0)" target="main">管理广告（不做）</a></li>
							<li><a href="javascript:void(0);" target="main">添加公告（不做）</a></li>
							<li><a href="javascript:void(0);" target="main">管理公告（不做）</a></li>
							-->
							<li><a href="javascript:void(0)" target="main">添加通知</a></li>
							<li><a href="javascript:void(0)" target="main">通知管理</a></li>
							<li><a href="<?php echo U('Link/index');?>" target="main">管理友情链接</a></li>
							<li><a href="<?php echo U('Link/mAdd');?>" target="main">添加友情链接</a></li>
							<li><a href="<?php echo U('Link/mTrash');?>" target="main">回收站管理</a></li>
						</ul>
					</li>
					
					<li> <a href="#" class="nav-top-item"> 用户管理 </a>
						<ul class="none-css-main-item">
							<li><a href="<?php echo U('User/index');?>" target="main">前台用户列表</a></li>
							<li><a href="<?php echo U('User/mAdd');?>" target="main">添加新用户</a></li>
							<li><a href="<?php echo U('User/mTrash');?>" target="main">回收站管理</a></li>
							<!--<li><a href="<?php echo U('User/mUserAnalyse');?>"  target="main">用户分析</a></li>-->
						</ul>
					</li>

					<li> <a href="#" class="nav-top-item"> 权限管理 </a>
						<ul class="none-css-main-item">
							<li><a href="<?php echo U('Access/index');?>" target="main">所有用户</a></li>
							<li><a href="<?php echo U('Access/mAdd');?>" target="main">新增管理员</a></li>
							<li><a href="<?php echo U('Access/mAdminRoleList');?>" target="main">角色管理</a></li>
							<li><a href="<?php echo U('Access/mTrash');?>" target="main">回收站管理</a></li>
							<!--<li><a href="<?php echo U('Access/mLoginLog');?>" target="main">登录日志</a></li>-->
							<!--
							<li><a href="<?php echo U('Access/mMyInfo');?>" target="main">我的个人资料</a></li>
							-->
						</ul>
					</li>
					
					<li> <a href="#" class="nav-top-item"> 公司管理 </a>
						<ul class="none-css-main-item">
							<li><a href="<?php echo U('Finance/index');?>" target="main">公司管理</a></li>
							<li><a href="<?php echo U('Finance/mAdd');?>" target="main">添加公司</a></li>	
							<li><a href="<?php echo U('Finance/mTrash');?>" target="main">回收站管理</a></li>
						</ul>
					</li>
					
					<li> <a href="#" class="nav-top-item"> 项目管理 </a>
						<ul class="none-css-main-item">
							<li><a href="<?php echo U('Project/index');?>" target="main">管理项目</a></li>
							<li><a href="<?php echo U('Project/mAdd');?>" target="main">添加项目</a></li>
							<!--<li><a href="javascript:void(0)" target="main">群组管理</a></li>-->
							<!--<li><a href="<?php echo U('Project/mTrash');?>" target="main">回收站管理</a></li>-->
						</ul>
					</li>
					
					<li> <a href="#" class="nav-top-item"> 业务管理 </a>
						<ul class="none-css-main-item">
							<li><a href="<?php echo U('Business/index');?>" target="main">管理业务</a></li>
							<li><a href="<?php echo U('Business/mAdd');?>" target="main">添加业务</a></li>
						</ul>
					</li>

					<li> <a href="#" class="nav-top-item"> 文件管理 </a>
						<ul class="none-css-main-item">
							<li><a href="<?php echo U('File/index');?>" target="main">管理文件</a></li>
							<li><a href="<?php echo U('File/mAdd');?>" target="main">添加文件</a></li>
							<li><a href="<?php echo U('File/mTrash');?>" target="main">回收站管理</a></li>
						</ul>
					</li>
					
					<li> <a href="#" class="nav-top-item"> 论坛管理 </a>
						<ul class="none-css-main-item">
							<li><a href="<?php echo U('Bbs/mComments');?>" target="main">评论管理</a></li>
						</ul>
					</li>
						
					<li><a href="#" class="nav-top-item no-submenu"> 系统管理 </a> 
						<ul class="none-css-main-item">
							<li><a href="javascript:void(0)" target="main">系统信息</a></li>
							<li><a href="javascript:void(0)" target="main">基本设置</a></li>
							<li><a href="javascript:void(0)" target="main">更新缓存</a></li>
							<li><a href="javascript:void(0)" target="main">数据库优化</a></li>
							<li><a href="javascript:void(0)"  target="main">数据库备份</a></li>
							<li><a href="javascript:void(0)" target="main">关闭/开启门户</a></li>
						</ul>
					</li>

				</ul>
				<!-- End #main-nav -->
			</div>
		</div>
	</div>
</body>
</html>