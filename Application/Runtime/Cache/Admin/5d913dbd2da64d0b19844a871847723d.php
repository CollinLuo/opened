<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<title><?php echo ($site["SITE_INFO"]["name"]); ?></title>
		<meta name="author" content="<?php echo ($site["SITE_INFO"]["author"]); ?>" />
		<meta name="keywords" content="<?php echo ($site["SITE_INFO"]["keywords"]); ?>" />
		<!--                       CSS                       -->
		<!-- Reset Stylesheet -->
		<link rel="stylesheet" href="/opened/Public/Css/Admin/font.css" type="text/css" media="screen" />
		<link rel="stylesheet" href="/opened/Public/Css/Admin/reset.css" type="text/css" media="screen" />
		<!-- Main Stylesheet -->
		<link rel="stylesheet" href="/opened/Public/Css/Admin/style.css" type="text/css" media="screen" />
		<!-- Invalid Stylesheet. This makes stuff look pretty. Remove it if you want the CSS completely valid -->
		<!--                       Javascripts                       -->
		<!-- jQuery -->
		<script type="text/javascript" src="/opened/Public/Min/?f=/opened/Public/Js/Common/jquery-1.9.0.min.js"></script>
		<!--<script type="text/javascript" src="/opened/Public/js/Admin/jquery-1.3.2.min.js"></script>-->
		<!-- jQuery Configuration -->
		<script type="text/javascript" src="/opened/Public/Js/Admin/simpla.jquery.configuration.js"></script>
		<script type="text/javascript" src="/opened/Public/Js/Admin/main.js"></script>


	</head>
<body id="login">
<div id="login-wrapper" class="png_bg">
  <div id="login-top">
    <h1>Opened Admin</h1>
    <!-- Logo (221px width) -->
    <a href="http://www.trydemo.net"><img id="logo" src="/opened/Public/Img/Admin/logo.png" alt="Opened Admin logo" /></a> </div>
  <!-- End #logn-top -->
  <div id="login-content">
	  <form action="<?php echo U('Public/index');?>" method="post" onsubmit="return checkForm()">
      <div class="notification information png_bg">
        <div> 点击"记住选项".下次登录无须密码轻松登录. </div>
      </div>
      <p>
        <label>用户名:</label>
        <input id="username" class="text-input" type="text" name="username" />
      </p>
      <div class="clear"></div>
      <p>
        <label>密&nbsp;&nbsp;&nbsp;码:</label>
        <input id="pwd" class="text-input" type="password" name="pwd" />
      </p>
      <div class="clear"></div>
      <p id="remember-password">
        <input type="checkbox" value="1" />
        记住选项 </p>
      <div class="clear"></div>
      <p>
        <input class="button submit" type="submit" value="登录" />
      </p>
	  
    </form>
  </div>
  <!-- End #login-content -->
</div>
<!-- End #login-wrapper -->
</body>
</html>


<script type="text/javascript">

	//登录表单提交
	function checkForm(){
		//暂不开放密码找回默认置为登录模式
		$("#op_type").val("1");
		var flag = true;
		var msg = '';
		if($("#username").val()==''||$("#pwd").val()==''){
			flag = false;
			msg += '填写完整方可登录！\n';
		}
		if(!flag){
			alert(msg);
			return false;
		}
		return true;
	}
		
</script>