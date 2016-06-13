/** 前端用户登录注册退出专用jquery 2015/9/21 by:lsq **/
$(function(){
	//$
});

//ajax登录函数
function doUserLogin(){
	//alert('提交登录');
	var flag = true;
	var msg = '';
	var uname = $.trim($("#loginForm #uname").val());
	var upwd = $.trim($("#loginForm #upwd").val());
	var home_login_url = $("#loginForm input[name='actUrl']").val();
	var home_login_hash = '';
	
	if($("#loginForm input[name='__hash__']").length){
		home_login_hash = $("#loginForm input[name='__hash__']").val();
	}
	
	if(uname && upwd){
		if(uname.length < 2 || uname.length > 20){
			flag = false;
			msg += '用户名长度为2~20位！\n';
		}
		
		if(upwd.length < 6){
			flag = false;
			msg += '密码长度必须大于6位！\n';
		}
	}else{
		flag = false;
		msg += '用户名密码不能为空！\n';	
	}	
	
	if(flag){
		var r_login_box = $("#home_r_user_box #home_r_u_login_box");
		var r_u_info_box = $("#home_r_user_box #home_r_u_info_box");
		var avatar_obj = r_u_info_box.find("div.u_i_avatar img");
		//登录表单验证正确ajax提交请求
		if(home_login_url && home_login_url != "" && home_login_url != null && home_login_url != undefined){
			$.ajax({
				type:"post",
				url:home_login_url,
				data:"uname="+uname+"&pwd="+upwd+"&__hash__="+home_login_hash,
				dataType:"json",
				success:function(result){
					if(result.flag){
						//alert("登录成功");
						//alert(result.data.uid);
						if(result.data.uid && result.data.uid > 0){
							r_login_box.hide();
							r_u_info_box.show();
							var img_src = avatar_obj.attr("src");
							var new_img = '';
							if(result.data.avatar && result.data.avatar != undefined){
								if(img_src && img_src.lastIndexOf("/") > -1){
									new_img = img_src.substring(0,img_src.lastIndexOf("/")+1)+""+result.data.avatar;
								}
							}else{
								if(img_src && img_src.lastIndexOf("/") > -1){
									new_img = img_src.substring(0,img_src.lastIndexOf("/")+1)+"default.gif";
								}
							}
							if(new_img.length > 0){
								avatar_obj.attr("src",new_img);	
							}
							
							//判断是否文章详情页(刷新文章详情页用户信息)
							if($("#td_respond_box ").length > 0){
								var u_info_box = $("#td_respond_box ").find("div.u_info_box");
								if(u_info_box.length > 0){
									
									if(u_info_box.find("strong").length > 0){
										u_info_box.find("strong").text(result.data.username);
									}else{
										u_info_box.html("<span>欢迎回来</span><strong>"+result.data.username+"</strong><a id='toggle-comment-author-info' class='hide' href='javascript:toggleCommentAuthorInfo();'>[ 隐藏 ]</a>");
									}
									u_info_box.show();
									var tmp_u_info = $("#td_respond_box").find("div.tmp_u_info");
									if(tmp_u_info.length > 0){
										tmp_u_info.hide();
									}
								}
								var uname = $("#td_respond_box ").find("#uname");
								if(uname.length > 0 && result.data.username.length > 0){
									uname.attr("value",result.data.username);
								}
								var email = $("#td_respond_box ").find("#email");
								if(email.length > 0 && result.data.email.length > 0){
									email.attr("value",result.data.email);
								}
								var site = $("#td_respond_box ").find("#site");
								if(site.length > 0 && result.data.personal_website.length > 0){
									site.attr("value",result.data.personal_website);
								}
							}
							
						}else{
							r_login_box.hide();
							r_u_info_box.show();
							var img_src = avatar_obj.attr("src");
							if(img_src && img_src.lastIndexOf("/") > -1){
								var new_img = img_src.substring(0,img_src.lastIndexOf("/")+1)+"default.gif";
								if(new_img.length > 0){
									avatar_obj.attr("src",new_img);	
								}
							}
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

		}else{
			alert("请求出错，请刷新页面重试！");
		}
		
		
	}else{
		alert(msg);
	}
	
	return false;
}

//ajax注销函数
function doUserLogout(){
	var home_logout_url = $("#loginForm input[name='actAjaxUrl']").val();
	if(home_logout_url && home_logout_url != "" && home_logout_url != null && home_logout_url != undefined){
		$.ajax({
			type:"post",
			url:home_logout_url,
			data:"",
			dataType:"json",
			success:function(result){
				if(result.flag){
					alert(result.msg);
					var u_login_obj = $("#home_r_u_login_box");
					var u_info_obj = $("#home_r_u_info_box");
					if(u_info_obj.length > 0){
						u_info_obj.hide();
						if(u_login_obj.length > 0){
							u_login_obj.show();
						}
					}
					
					//判断是否文章详情页(刷新文章详情页用户信息)
					if($("#td_respond_box ").length > 0){
						var u_info_box = $("#td_respond_box ").find("div.u_info_box");
						if(u_info_box.length > 0){
							
							if(u_info_box.find("strong").length > 0){
								u_info_box.find("strong").text("");
							}else{
								u_info_box.html("<span>欢迎回来</span><strong></strong><a id='toggle-comment-author-info' href='javascript:toggleCommentAuthorInfo();'>[ 隐藏 ]</a>");
							}
							u_info_box.hide();
							var tmp_u_info = $("#td_respond_box").find("div.tmp_u_info");
							if(tmp_u_info.length > 0){
								tmp_u_info.show();
							}
						}	
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
	}else{
		alert("请求出错，请刷新页面重试！");
	}
}

//文章详情页隐藏用户信息
function toggleCommentAuthorInfo(){
	
}