<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"  lang="zh-CN">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<title><?php echo ($web_name); ?> › <?php echo ($current_page); ?></title>
		<meta name="robots" content="noindex,nofollow">
		<meta name="keywords" content="<?php echo ($keywords); ?>"/>
		<meta name="description" content="<?php echo ($seo_description); ?>" />
		<meta name="baidu-site-verification" content="" />
		<!--css-->
		<link rel='stylesheet' id='twentytwelve-style-css'  href='/opened/Public/Css/Common/common.css' type='text/css' media='all' />
		<!-- js -->
		<!-- jQuery -->
		<script type="text/javascript" src="/opened/Public/Js/Common/jquery-1.9.0.min.js"></script>
		<script type="text/javascript" src="/opened/Public/Js/Common/common.js"></script> 
		
	</head>
	<body class="login login-action-register" youdao="bind">
		<div id="u_login_box">
			<h1><a href="<?php echo U('Home/index');?>" title="<?php echo ($web_name); ?>">Opened</a></h1>
			<p class="message register">在这里注册</p>

			<form name="registerform" id="registerform" action="<?php echo U('User/registerAct');?>" method="post" onsubmit="return registerValidate()">
				<p>
					<label for="user_login">用户名<br>
					<input type="text" name="user_name" id="user_name" class="input" value="" size="20"></label>
				</p>
				<p>
					<label for="user_pwd">登录密码<br>
					<input type="password" name="user_pwd" id="user_pwd" class="input" value="" size="25"></label>
				</p>
				<p>
					<label for="user_repwd">确认密码<br>
					<input type="password" name="user_repwd" id="user_repwd" class="input" value="" size="25"></label>
				</p>
				<p>
					<label for="user_email">电子邮件<br>
					<input type="text" name="user_email" id="user_email" class="input" value="" size="25"></label>
				</p>
				<p>
					<label for="verify_code">验证码<br>
					<div class="box_verify_code">
						<input type="text" name="verify_code" id="verify_code" class="input input_verify_code" value="" size="10">
						<input type="button" name="btn_send_verify_code" id="btn_send_verify_code" class="button button-primary button-large btn_verify_code" value="发送验证码" onclick="sendVerifyCode(this)">
					</div>
					</label>
					
				</p>
				<p id="reg_passmail">验证码将通过电子邮件发送给您。</p>
				<br class="clear">
				<input type="hidden" name="redirect_to" value="<?php echo U('User/registerAct');?>">
				<p class="submit"><input type="submit" name="tp-submit" id="tp-submit" class="button button-primary button-large" value="注册"></p>
			</form>
			<p id="nav">
				<a href="<?php echo U('User/loginAct');?>">登录</a> |
				<a href="<?php echo U('User/findPwdAct');?>" title="找回密码">忘记密码？</a>
			</p>
			<p id="backtoblog"><a href="<?php echo U('Home/index');?>" title="不知道自己在哪？">← 回到Opened</a></p>
		</div>
		<div class="clear"></div>
		<script type="text/javascript">
			$(function(){ 
				wp_attempt_focus();
				var v = getCookieValue("secondsremained");//获取cookie值
				if(v>0){
					settime($("#btn_send_verify_code"));//开始倒计时
				}
			});
			
			function wp_attempt_focus(){
				setTimeout( function(){ 
					try{
						d = document.getElementById('user_name');
						d.focus();
						d.select();
					} catch(e){
					
					}
				}, 200);
			}
			
			//邮件发送验证码
			function sendVerifyCode(obj){
				//检测邮箱是否合法
				var user_email = $.trim($('#user_email').val());
				var regu = "^(([0-9a-zA-Z]+)|([0-9a-zA-Z]+[_.0-9a-zA-Z-]*[0-9a-zA-Z-]+))@([a-zA-Z0-9-]+[.])+([a-zA-Z]|net|NET|asia|ASIA|com|COM|gov|GOV|mil|MIL|org|ORG|edu|EDU|int|INT|cn|CN|cc|CC)$";
				var re = new RegExp(regu);
				if(user_email.length){
					if(user_email.search(re) == -1){
						alert("邮箱格式错误！");
						addLoadEvent(function(){ var p=new Array(15,30,15,0,-15,-30,-15,0);p=p.concat(p.concat(p));var i=document.forms[0].id;g(i).position='relative';shake(i,p,20);});
					}else{
						//发送邮件
						$.ajax({
							type: "POST",
							url: "<?php echo U('User/ajax_send_verifycode');?>",
							data: "email="+ user_email,
							dataType: "json",
							success: function(result){
								if(result.flag){
									alert(result.msg);
									//添加cookie记录,有效时间60s
									addCookie("secondsremained",60,60);
									//开始倒计时
									settime(obj);
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
					alert("邮箱不能为空！");
					//调用屏幕抖动效果
					addLoadEvent(function(){ var p=new Array(15,30,15,0,-15,-30,-15,0);p=p.concat(p.concat(p));var i=document.forms[0].id;g(i).position='relative';shake(i,p,20);});
				}
			}
			
			//当前剩余秒数
			var countdown=60; 
			function settime(obj){
				if (countdown == 0) { 
					obj.removeAttribute("disabled");    
					obj.value = "获取验证码"; 
					countdown = 60; 
					return;
				} else { 
					obj.setAttribute("disabled", true); 
					obj.value="重新发送(" + countdown + ")"; 
					countdown--; 
					editCookie("secondsremained",countdown,countdown+1);
				} 
				setTimeout(function(){ 
					settime(obj) 
				},1000);
			}
			
			//发送验证码时添加cookie
			function addCookie(name,value,expiresHours){ 
				var cookieString=name+"="+escape(value); 
				//判断是否设置过期时间,0代表关闭浏览器时失效
				if(expiresHours>0){ 
					var date=new Date(); 
					date.setTime(date.getTime()+expiresHours*1000); 
					cookieString=cookieString+";expires=" + date.toUTCString(); 
				} 
					document.cookie=cookieString; 
			} 
			//修改cookie的值
			function editCookie(name,value,expiresHours){ 
				var cookieString=name+"="+escape(value); 
				if(expiresHours>0){ 
					var date=new Date(); 
					date.setTime(date.getTime()+expiresHours*1000); //单位是毫秒
					cookieString=cookieString+";expires=" + date.toGMTString(); 
				} 
					document.cookie=cookieString; 
			} 
			//根据名字获取cookie的值
			function getCookieValue(name){ 
				var strCookie=document.cookie; 
				var arrCookie=strCookie.split("; "); 
				for(var i=0;i<arrCookie.length;i++){ 
					var arr=arrCookie[i].split("="); 
					if(arr[0]==name){
						return unescape(arr[1]);
						break;
					}else{
						return ""; 
						break;
					} 
				}    
			}
			
			//表单提交验证
			function registerValidate(){
				var flag = true;
				var msg = '';
				var user_name = $.trim($('#user_name').val());
				var user_pwd = $.trim($('#user_pwd').val());
				var user_repwd = $.trim($('#user_repwd').val());
				var user_email = $.trim($('#user_email').val());
				var verify_code = $.trim($('#verify_code').val());
				
				if(!user_name.length){
					msg += '用户名不能为空！\n';
					flag = false;
				}
				
				if(!user_pwd.length || user_pwd.length < 6){
					msg += '密码不能小于6位数！\n';
					flag = false;
				}else{
					if(!user_repwd.length || user_repwd.length < 6){
						msg += '重复密码不能小于6位数！\n';
						flag = false;
					}else{
						if(user_repwd != user_pwd){
							msg += '两次密码不一致！\n';
							flag = false;
						}
					}
				}
				
				var regu = "^(([0-9a-zA-Z]+)|([0-9a-zA-Z]+[_.0-9a-zA-Z-]*[0-9a-zA-Z-]+))@([a-zA-Z0-9-]+[.])+([a-zA-Z]|net|NET|asia|ASIA|com|COM|gov|GOV|mil|MIL|org|ORG|edu|EDU|int|INT|cn|CN|cc|CC)$";
				var re = new RegExp(regu);
				if(!user_email.length){
					msg += '邮箱不能为空！\n';
					flag = false;
				}else if(user_email.search(re) == -1){
					msg += '邮箱格式错误！\n';
					flag = false;
				}
				
				if(!verify_code.length || verify_code.length != 4){
					msg += '请输入4位长度验证码！\n';
					flag = false;
				}

				if(!flag){
					alert(msg);
					//调用屏幕抖动效果
					addLoadEvent(function(){ var p=new Array(15,30,15,0,-15,-30,-15,0);p=p.concat(p.concat(p));var i=document.forms[0].id;g(i).position='relative';shake(i,p,20);});
				}
				return flag;
			
			}	


		</script>
	</body>
</html>