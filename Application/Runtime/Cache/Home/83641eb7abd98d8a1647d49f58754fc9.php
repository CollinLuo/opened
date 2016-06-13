<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"  lang="zh-CN">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<title><?php echo ($web_name); ?>-<?php echo ($description); ?></title>
		<meta name="keywords" content="<?php echo ($keywords); ?>"/>
		<meta name="description" content="<?php echo ($seo_description); ?>" />
		<meta name="baidu-site-verification" content="" />
		<!--css-->
		<link rel='stylesheet' id='twentytwelve-style-css'  href='/opened/Public/Css/Home/style.css' type='text/css' media='all' />
		<link rel='stylesheet' href='/opened/Public/Css/Common/gantt/style.css' type='text/css' media='all' />
		<!-- js -->
		<!-- jQuery -->
		<script type="text/javascript" src="/opened/Public/Js/Common/jquery-1.9.0.min.js"></script>
		<script type="text/javascript" src="/opened/Public/Js/Common/jquery.bowser.js"></script>
		<script type="text/javascript" src="/opened/Public/Js/Home/article.js"></script>
		<script type="text/javascript" src="/opened/Public/Js/Home/comments-ajax.js"></script>
		<script type="text/javascript" src="/opened/Public/Js/Common/gantt/jquery.fn.gantt.js"></script>
		<script type="text/javascript" src="/opened/Public/Js/Common/gantt/bootstrap-tooltip.js"></script>
		<script type="text/javascript" src="/opened/Public/Js/Common/gantt/bootstrap-popover.js"></script>
		<script type="text/javascript" src="/opened/Public/Js/Common/gantt/prettify.js"></script>
	</head>
	<body class="home blog custom-background custom-font-enabled single-author">
		<div id="page" class="container">
			<div class="u_center_header">
				<div class="uc_h_left">
					<img src="/opened/Uploads/Common/avatar_big/<?php echo ($user_info["avatar"]); ?>" alt="" width="310px" height="370px">
					<div class="avatar_intro"><span class="avatar_intro_text"><?php echo ($user_info["description"]); ?>&nbsp;<?php echo ($user_info["email"]); ?></span></div>
				</div>
				
				<div class="uc_h_right">
					<div class="uchr_info">
						<div class="uchr_i_name"><?php echo ($user_info["username"]); ?></div>
						<div class="uchr_i_other">
							<?php if(is_array($company_list)): $i = 0; $__LIST__ = $company_list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$val): $mod = ($i % 2 );++$i;?><span class=""><notempety name="val['avatar']"><img src="/opened/Uploads/Image/<?php echo ($val["company_logo"]); ?>" width="20px" height="20px"></notempty>&nbsp;<a href="<?php echo U('Company/index','id='.$val['cid']);?>"><?php echo ($val["name"]); ?></a></span>
							&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php endforeach; endif; else: echo "" ;endif; ?>
						</div>
					</div>
					<div class="uchr_desc">
						<div class="uchr_d_title">用心服务客户</div>
						<div class="uchr_d_con"><?php echo ($user_info["remark"]); ?></div>
					</div>
				</div>
			</div>
			<div class="clear"></div>
			<div class="u_center_middle">
				<div class="ucm_comment_p_box">
					<div class="ucm_comment_box">
						<div class="ucm_cl_set">
							<span><a href="javascript:void(0);"><img src="/opened/Public/Img/Common/icon_set.png" width="20" height="20" onclick="changeCommentSet()" /></a></span>&nbsp;&nbsp;&nbsp;
							<span><a href="javascript:void(0);"><img src="/opened/Public/Img/Common/icon_save.png" width="20" height="20" onclick="saveCommentSet()" /></a></span>
						</div>
						<ol class="ucm_c_list">
							<li class="ucm_comment_sli" id="" is_show="1" style="display:block;">
								<div class="ucm_cl_comment">
									<!--<div class="ucm_c_title"><span>在：</span>对于一位新手来说，猜猜我下一句会说什么呢。。。</div>-->
									<div class="ucm_c_title">
										<div class="ucm_t_left">
											<span>在：</span>对于一位新手来说，猜猜我下一句会说什么呢。。。
										</div>
										<div class="ucm_t_right hide">
											<span><a href="javascript:void(0);" onclick="toogleShow($(this),0)">[隐藏]</a></span>
											<span class="hide"><a href="javascript:void(0);" onclick="toogleShow($(this),1)">[显示]</a></span>
										</div>
									</div>
									<div class="ucm_c_reply">
										<span>对：</span><span class="ucm_cr_username">hd750_1[32楼]</span><span class="ucm_cr_date">2015-10-20/11:38</span><span>回复：</span>
									</div>
									<div class="ucm_c_content">在我们的合伙人备忘录中，用于奖励、培训更好的工作环境等投资占获利的64%。高于其它所有的总和。在OPENED,我们以人为先，让每一个成员真正能够对公司运营方式发表意见，并分享利润。<br /><br />
									<img src="/opened/Uploads/Image/20160131/56ae070e32c85.jpg" width="60px" height="40px" />&nbsp;&nbsp;&nbsp;<img src="/opened/Uploads/Image/20160131/56ae070e32c85.jpg" width="60px" height="40px" />&nbsp;&nbsp;&nbsp;<img src="/opened/Uploads/Image/20160131/56ae070e32c85.jpg" width="60px" height="40px" />&nbsp;&nbsp;&nbsp;
									</div>
								</div>
							</li>
							<li class="ucm_comment_sli" id="" is_show="0"  style="display:none;">
								<div class="ucm_cl_comment">
									<div class="ucm_c_title">
										<div class="ucm_t_left">
											<span>在：</span>对于一位新手来说，猜猜我下一句会说什么呢。。。
										</div>
										<div class="ucm_t_right hide">
											<span class="hide"><a href="javascript:void(0);" onclick="toogleShow($(this),0)">[隐藏]</a></span>
											<span><a href="javascript:void(0);" onclick="toogleShow($(this),1)">[显示]</a></span>
										</div>
									</div>
									<div class="ucm_c_reply">
										<span>对：</span><span class="ucm_cr_username">hd750_1[32楼]</span><span class="ucm_cr_date">2015-10-20/11:55</span><span>回复：</span>
									</div>
									<div class="ucm_c_content">
									小伙子。你说的很对！
									</div>
								</div>
							</li>
						</ol>
					</div>
				</div>
				<div class="ucm_project_box">
					<ul>
						<li class="upb_li_header"><span class="ucm_p_header">他的<br />工作</span></li>
						<li class="ubp_li_3 upb_desc_checked" pid="3">
							<a href="javascript:void(0);">
								<div class="upb_img"><img src="/opened/Uploads/Image/20160131/56adf3ab473c7.jpg" width="40px" height="40px" /></div>
								<div class="upb_desc">
									<div class="upb_d_title">
										<img src="/opened/Uploads/Image/20160407/570619ba7e015.png" width="14px" height="14px">视觉识别系统修改
									</div>
									<div class="upb_d_con">千里之行，始于足下</div	>
								</div>
							</a>
							<a href="javascript:void(0);">
								<div class="upb_qipao_img">
									3
								</div>
							</a>
						</li>
						<li class="ubp_li_1" pid="1">
							<a href="javascript:void(0);">
								<div class="upb_img"><img src="/opened/Uploads/Image/20160131/56ae070e32c85.jpg" width="40px" height="40px" /></div>
								<div class="upb_desc">
									<div class="upb_d_title"><img src="/opened/Uploads/Image/20160407/570619ba7e015.png" width="14px" height="14px">测试项目一号</div><div class="upb_d_con">千里之行，始于足下</div	>
								</div>
							</a>
						</li>
					</ul>
				</div>
				<!-- hidden by lsq 160530 -->
				
				<div class="ucm_project_list">
					<div class="p_list_box" id="p_list_box_3">
						<div class="gantt"></div>
						<div class="p_user_list">
							<ul>
								<li class="ubp_li_3 upb_desc_checked" pid="3">
									<a href="javascript:void(0);">
										<div class="upb_img"><img src="/opened/Uploads/Common/avatar_big/user100_1041.jpg" width="30px" height="30px" /></div>
										<div class="upb_desc">
											<div class="upb_d_title">志强科技</div><div class="upb_d_con">amy</div	>
										</div>
									</a>
								</li>
								<li class="ubp_li_1" pid="3">
									<a href="javascript:void(0);">
										<div class="upb_img"><img src="/opened/Uploads/Common/avatar_small/user100_1161.jpg" width="30px" height="30px" /></div>
										<div class="upb_desc">
											<div class="upb_d_title">灿阳广告</div><div class="upb_d_con">小赵</div	>
										</div>
									</a>
								</li>
							</ul>
						</div>
						<div class="ci_p_com_box">
							<div class="comment_box_div" style="margin-top:0px;">
							<ol class="ucm_c_list" id="comments_display_area">
								<li class="comment" id="li-comment-1" cbp="1">
									<div id="comment-1" class="replied_box">
										<div class="replied_left">
											<img src='/opened/Uploads/Common/avatar_small/user100_1161.jpg' class='photo' height='44' width='44'/>
										</div>
										<div class="replied_right">
											<div class="replied_username">
												<strong><a><b>阳光_小赵&nbsp;:</b></a></strong>
											</div>
											<div class="replied_content">您好，这是一条评论。您好，这是一条评论。您好，这是一条评论。您好，这是一条评论。您好，这是一条评论。您好，这是一条评论。您好，这是一条评论。您好，这是一条评论。您好，这是一条评论。您好，这是一条评论。您好，这是一条评论。 </div>
											<div class="replied_time" style="margin-bottom:10px;">
												<span>2015-01-12 22:30:01<!--<?php echo (date('Y-m-d H:i:s',$vo["comment_edit_date"])); ?>--></span>
												<span class="reply">
													<a onclick="return addComment.moveForm('comment-1', '1', 'ceshi04', '0', '2', 0)" href="/opened/index.php?m=&c=User&a=uCenter">[回复]</a>
												</span>
											</div>
										</div>
										<div class="clear"></div>
									</div>
								</li>
								<li class="comment" id="li-comment-2" cbp="2">
									<div id="comment-2" class="replied_box">
										<div class="replied_left">
											<img src='/opened/Uploads/Common/avatar_small/user100_1461.jpg' class='photo' height='44' width='44'/>
										</div>
										<div class="replied_right">
											<div class="replied_username">
												<strong><a><b>刘丽&nbsp;:</b></a></strong>
											</div>
											<div class="replied_content">请各位领导发表对该案例的看法。</div>
											<div class="replied_time" style="margin-bottom:10px;">
												<span>2015-10-12 22:30:01<!--<?php echo (date('Y-m-d H:i:s',$vo["comment_edit_date"])); ?>--></span>
												<span class="reply">
													<a onclick="return addComment.moveForm('comment-2', '2', 'ceshi04', '0', '2', 0)" href="/opened/index.php?m=&c=User&a=uCenter">[回复]</a>
												</span>
											</div>
										</div>
										<div class="clear"></div>
									</div>
								</li>
								<li class="comment" id="li-comment-cid" cbp="<?php echo ($vo["cid"]); ?>">
									<div id="comment-<?php echo ($vo["cid"]); ?>" class="replied_box_right">
										<div class="replied_left">
											<img src='/opened/Uploads/Common/avatar_small/user100_1586.jpg' class='photo' height='44' width='44'/>
										</div>
										<div class="replied_right">
											<div class="replied_username">
												<strong><a><b>ceshi04&nbsp;:</b></a></strong>
											</div>
											<div class="replied_content">这是本人回复的评论测试！！！！</div>
											<div class="replied_time" style="margin-bottom:10px;">
												<span>2016-04-12 10:30:01<!--<?php echo (date('Y-m-d H:i:s',$vo["comment_edit_date"])); ?>--></span>
												<span class="reply">
													<a onclick="return addComment.moveForm('comment-<?php echo ($vo["cid"]); ?>', '<?php echo ($vo["cid"]); ?>', '<?php echo ($vo["comment_author_name"]); ?>', '<?php echo ($vo["comment_top_parent"]); ?>', '<?php echo ($article_info["id"]); ?>', 0)" href="/opened/index.php?m=&c=User&a=uCenter">[回复]</a>
												</span>
											</div>
											
										</div>
										<div class="clear"></div>
									</div>
								</li>
							</ol>
							<div id="td_respond_box">
								<div id="respond">
									<div class="respond_box"> <!--完美解决ie6textarea兼容问题-->
										<div class="cancel-comment-reply">
											<small><a rel="nofollow" id="cancel-comment-reply-link" href="/opened/index.php?m=&c=User&a=uCenter" style="display:none;">点击这里取消回复或者编辑</a></small>
										</div>
										<form action="<?php echo U('User/ajaxComment');?>" method="post" id="commentform">
											<div class="comt-box">
												<fieldset><textarea name="comment_content" id="c_comment" class="comt-area" tabindex="4" cols="50" rows="5" onkeyup="keyups.onstr(this)"></textarea></fieldset>
												<div id="c_loading" style="display:none;"><img src="/opened/Public/Img/Common/loading.gif" style="vertical-align:middle;" alt="" /> 正在提交, 请稍候...</div>
												<div style="display:none;" id="c_error"><img src="/opened/Public/Img/Common/no.png" style="vertical-align:middle;" alt="" /><span></span></div>
												<div class="comt-ctrl">
													<div class="comt_s_box">
														<span id="com_r_hint" class="comt-num"><font>还能输入<em>240</em>个字</font></span>
														<input class="comt-submit" name="submit" id="submit" tabindex="5" value="发布评论" type="submit">
														<input name="correlation_id" value="<?php echo ($info["cid"]); ?>" id="c_correlation_id" type="hidden" />
														<input name="comment_parent" id="comment_parent" value="0" type="hidden" />
														<input name="comment_parent_author_name" id="comment_parent_author_name" value="" type="hidden" />
														<input name="c_best_parent" id="c_best_parent" value="0" type="hidden" />
														<input name="act_option" id="act_option" value="0" type="hidden" />
														<!-- 暂未启用 2014-5-12 <input name="c_edit_id" id="c_edit_id" value="0" type="hidden" />-->
													</div>
												</div>
											</div>
										</form>
									</div>
								</div>
							</div>
						</div>
						</div>
					</div>
					<div class="p_list_box" id="p_list_box_1">
						<div class="gantt2"></div>
						<div class="p_user_list">
							<ul>
								<li class="ubp_li_3 upb_desc_checked" pid="1">
									<a href="javascript:void(0);">
										<div class="upb_img"><img src="/opened/Uploads/Common/avatar_big/user100_1041.jpg" width="30px" height="30px" /></div>
										<div class="upb_desc">
											<div class="upb_d_title">志强科技</div><div class="upb_d_con">amy</div	>
										</div>
									</a>
								</li>
								<li class="ubp_li_1" pid="1">
									<a href="javascript:void(0);">
										<div class="upb_img"><img src="/opened/Uploads/Common/avatar_small/user100_1921.jpg" width="30px" height="30px" /></div>
										<div class="upb_desc">
											<div class="upb_d_title">北京环球</div><div class="upb_d_con">ceshi10</div	>
										</div>
									</a>
								</li>
								<li class="ubp_li_1" pid="1">
									<a href="javascript:void(0);">
										<div class="upb_img"><img src="/opened/Uploads/Common/avatar_small/user100_1491.jpg" width="30px" height="30px" /></div>
										<div class="upb_desc">
											<div class="upb_d_title">北京环球</div><div class="upb_d_con">ceshi08</div	>
										</div>
									</a>
								</li>
							</ul>
						</div>
						<div class="ci_p_com_box">
							<div class="comment_box_div" style="margin-top:0px;">
							<ol class="ucm_c_list" id="comments_display_area">
							<li class="comment" id="li-comment-4" cbp="4">
								<div id="comment-4" class="replied_box">
									<div class="replied_left">
										<img src='/opened/Uploads/Common/avatar_small/user100_1161.jpg' class='photo' height='44' width='44'/>
									</div>
									<div class="replied_right">
										<div class="replied_username">
											<strong><a><b>黑暗_小吴&nbsp;:</b></a></strong>
										</div>
										<div class="replied_content">六岁那年，一天早晨，我叔叔匆匆来我家抱我出去，边走边从口袋掏颗糖给我，说等下帮他做点事。走到条街道，叔叔放下我说，看到前面那小孩了吗，我点点头说看到了。叔叔：刚刚那小孩打你堂弟，你去把他给我打了，我们大人不好下手。 </div>
										<div class="replied_time" style="margin-bottom:10px;">
											<span>2015-01-12 22:30:01<!--<?php echo (date('Y-m-d H:i:s',$vo["comment_edit_date"])); ?>--></span>
											<span class="reply">
												<a onclick="return addComment.moveForm('comment-4', '4', 'ceshi04', '0', '2', 0)" href="/opened/index.php?m=&c=User&a=uCenter">[回复]</a>
											</span>
										</div>
									</div>
									<div class="clear"></div>
								</div>
							</li>
							<li class="comment" id="li-comment-5" cbp="5">
								<div id="comment-5" class="replied_box">
									<div class="replied_left">
										<img src='/opened/Uploads/Common/avatar_small/user100_1231.jpg' class='photo' height='44' width='44'/>
									</div>
									<div class="replied_right">
										<div class="replied_username">
											<strong><a><b>刘学&nbsp;:</b></a></strong>
										</div>
										<div class="replied_content">有一种落差是，你配不上自己的野心，也辜负了所有苦难。</div>
										<div class="replied_time" style="margin-bottom:10px;">
											<span>2015-10-12 22:30:01<!--<?php echo (date('Y-m-d H:i:s',$vo["comment_edit_date"])); ?>--></span>
											<span class="reply">
												<a onclick="return addComment.moveForm('comment-5', '5', 'ceshi04', '0', '2', 0)" href="/opened/index.php?m=&c=User&a=uCenter">[回复]</a>
											</span>
										</div>
									</div>
									<div class="clear"></div>
								</div>
							</li>
							<li class="comment" id="li-comment-cid" cbp="<?php echo ($vo["cid"]); ?>">
								<div id="comment-<?php echo ($vo["cid"]); ?>" class="replied_box_right">
									<div class="replied_left">
										<img src='/opened/Uploads/Common/avatar_small/user100_1336.jpg' class='photo' height='44' width='44'/>
									</div>
									<div class="replied_right">
										<div class="replied_username">
											<strong><a><b>ceshi04&nbsp;:</b></a></strong>
										</div>
										<div class="replied_content">这是本人回复的评论测试！！！！</div>
										<div class="replied_time" style="margin-bottom:10px;">
											<span>2016-04-12 10:30:01<!--<?php echo (date('Y-m-d H:i:s',$vo["comment_edit_date"])); ?>--></span>
											<span class="reply">
												<a onclick="return addComment.moveForm('comment-<?php echo ($vo["cid"]); ?>', '<?php echo ($vo["cid"]); ?>', '<?php echo ($vo["comment_author_name"]); ?>', '<?php echo ($vo["comment_top_parent"]); ?>', '<?php echo ($article_info["id"]); ?>', 0)" href="/opened/index.php?m=&c=User&a=uCenter">[回复]</a>
											</span>
										</div>
										
									</div>
									<div class="clear"></div>
								</div>
							</li>
						</ol>
						
						
						
						</div>
						</div>
					</div>
				</div>
				
				<div class="clear"></div>
			</div>
			
			<div class="clear"></div>
			<div class="page_footer">
				<div class="nav"></div>
				<div class="f_middle_info">
					<ul class="info_menu">
						<li class="info_stmenu">
							<span class="text_span">北京 <b class="font_big">4007004941</b></span>
						</li>
						<li class="info_stmenu">
							<span class="text_span">上海 <b class="font_big">55310556*2801</b></span>
						</li>
						<li class="info_stmenu border_left">
							<span class="text_span font_medium">谁是LightBP?</span>
						</li>
						<li class="info_stmenu border_left border_right">
							<span class="text_span font_medium">网站地图</span>
						</li>
						<li class="info_stmenu">
							<span><a href="javascript:void(0);" target="_blank" class="info_stmenu_a">沪ICP备06046803</a>&nbsp;&nbsp;&nbsp;&nbsp;Copyright©2006<br><span class="font_little">北京环球看点广告传媒有限公司保留所有权利</span></span>
						</li>
						<li class="info_stmenu">
							<span class="li_img"><a ><img src="/opened/Public/Img/Common/logo_little.png" alt="Opened"></a></span>
						</li>
						<li class="li_background_set ">
							<span class="text_span font_big"><?php if($login_mark == 1): ?><a href="<?php echo U('User/logout');?>" class="" alt="退出">退出</a>/<a href="<?php echo U('Company/index');?>" class="" alt="帮助">帮助</a><?php else: ?><a href="<?php echo U('User/loginAct');?>" class="" alt="登录">登录</a>/<a href="<?php echo U('User/registerAct');?>" class="" alt="注册">注册</a><?php endif; ?></span>
						</li>
					</ul>
				</div>
				<div class="c_footer_info" style="margin-top:0;">
					<div class="site-info" style="text-align:center;">
						<span><?php echo ($beian); ?><script type="text/javascript">/*var cnzz_protocol = (("https:" == document.location.protocol) ? " https://" : " http://");document.write(unescape("%3Cspan id='cnzz_stat_icon_1000244962'%3E%3C/span%3E%3Cscript src='" + cnzz_protocol + "s22.cnzz.com/z_stat.php%3Fid%3D1000244962%26show%3Dpic' type='text/javascript'%3E%3C/script%3E"));*/</script></span>
						<p class="author"><a href="" target="_blank" rel="external">Theme By D.Apache.Luo</a></p>
					</div>
				</div>			
			</div>
			
		</div>
	</body>
</html>
<script type="text/javascript">
$(document).ready(function(e){
	"use strict";
	$(".gantt").gantt({
		source: [{
			name: "产品部官网建设",
			desc: "16/1/15-16/6/20",
			values: [{
				id: "t01",
				from: "/Date(1320192000000)/",
				to: "/Date(0)/",
				customClass: "ganttRed"
			}]
		},{
			name: " ",
			desc: "论坛",
			values: [{
				id: "t02",
				from: "/Date(1320192000000)/",
				to: "/Date(1322401600000)/",
				customClass: "ganttRed",
				dep: "t01"
			}]
		},{
			name: "公司简介",
			desc: "16/1/15-16/6/20",
			values: [{
				from: "/Date(1323802400000)/",
				to: "/Date(0)/",
				customClass: "ganttGreen"
			}]
		}
		,{
			name: " ",
			desc: "文案采编",
			values: [{
				from: "/Date(1323802400000)/",
				to: "/Date(1325685200000)/",
				customClass: "ganttGreen"
			}]
		}],
		navigate: "scroll",
		scale: "weeks",
		maxScale: "months",
		minScale: "days",
		itemsPerPage: 10,
		onItemClick: function(data) {
			alert("Item clicked - show some details");
		},
		onAddClick: function(dt, rowId) {
			alert("Empty space clicked - add an item!");
		},
		onRender: function() {
			if (window.console && typeof console.log === "function") {
				console.log("chart rendered");
			}
		}
	});

	$(".gantt").popover({
		selector: ".bar",
		title: "项目",
		content: "And I'm the content of said popover.",
		trigger: "hover"
	});
	//prettyPrint();
	
	$(".gantt2").gantt({
		source: [{
			name: "产品部官网建设",
			desc: "16/1/15-16/6/20",
			values: [{
				id: "t01",
				from: "/Date(1320192000000)/",
				to: "/Date(0)/",
				customClass: "ganttRed"
			}]
		},{
			name: " ",
			desc: "论坛",
			values: [{
				id: "t02",
				from: "/Date(1320192000000)/",
				to: "/Date(1322401600000)/",
				customClass: "ganttRed",
				dep: "t01"
			}]
		},{
			name: "公司简介001",
			desc: "16/1/15-16/6/20",
			values: [{
				from: "/Date(1320192000000)/",
				to: "/Date(1322401600000)/",
				customClass: "ganttGreen"
			}]
		},{
			name: "公司简介002",
			desc: "16/1/15-16/6/20",
			values: [{
				from: "/Date(1320192000000)/",
				to: "/Date(1322401600000)/",
				customClass: "ganttGreen"
			}]
		},{
			name: "公司简介003",
			desc: "16/1/15-16/6/20",
			values: [{
				from: "/Date(1320192000000)/",
				to: "/Date(1322401600000)/",
				customClass: "ganttGreen"
			}]
		},{
			name: "公司简介",
			desc: "16/1/15-16/6/20",
			values: [{
				from: "/Date(1320192000000)/",
				to: "/Date(1322401600000)/",
				customClass: "ganttGreen"
			}]
		},{
			name: " ",
			desc: "文案采编",
			values: [{
				from: "/Date(1323802400000)/",
				to: "/Date(1325685200000)/",
				customClass: "ganttGreen"
			}]
		}],
		navigate: "scroll",
		scale: "weeks",
		maxScale: "months",
		minScale: "days",
		itemsPerPage: 10,
		onItemClick: function(data) {
			alert("Item clicked - show some details");
		},
		onAddClick: function(dt, rowId) {
			alert("Empty space clicked - add an item!");
		},
		onRender: function() {
			if (window.console && typeof console.log === "function") {
				console.log("chart rendered");
			}
		}
	});

	$(".gantt2").popover({
		selector: ".bar",
		title: "项目",
		content: "And I'm the content of said popover.",
		trigger: "hover"
	});

	prettyPrint();

	/*	
	"use strict";
	$(".gantt").gantt({
		source: [{
			name: "Sprint 0",
			desc: "Analysis",
			values: [{
				from: "/Date(1320192000000)/",
				to: "/Date(1322401600000)/",
				label: "Requirement Gathering", 
				customClass: "ganttRed"
			}]
		},{
			name: " ",
			desc: "Scoping",
			values: [{
				from: "/Date(1322611200000)/",
				to: "/Date(1323302400000)/",
				label: "Scoping", 
				customClass: "ganttRed"
			}]
		},{
			name: "Sprint 1",
			desc: "Development",
			values: [{
				from: "/Date(1323802400000)/",
				to: "/Date(1325685200000)/",
				label: "Development", 
				customClass: "ganttGreen"
			}]
		},{
			name: " ",
			desc: "Showcasing",
			values: [{
				from: "/Date(1325685200000)/",
				to: "/Date(1325695200000)/",
				label: "Showcasing", 
				customClass: "ganttBlue"
			}]
		},{
			name: "Sprint 2",
			desc: "Development",
			values: [{
				from: "/Date(1326785200000)/",
				to: "/Date(1325785200000)/",
				label: "Development", 
				customClass: "ganttGreen"
			}]
		},{
			name: " ",
			desc: "Showcasing",
			values: [{
				from: "/Date(1328785200000)/",
				to: "/Date(1328905200000)/",
				label: "Showcasing", 
				customClass: "ganttBlue"
			}]
		},{
			name: "Release Stage",
			desc: "Training",
			values: [{
				from: "/Date(1330011200000)/",
				to: "/Date(1336611200000)/",
				label: "Training", 
				customClass: "ganttOrange"
			}]
		},{
			name: " ",
			desc: "Deployment",
			values: [{
				from: "/Date(1336611200000)/",
				to: "/Date(1338711200000)/",
				label: "Deployment", 
				customClass: "ganttOrange"
			}]
		},{
			name: " ",
			desc: "Warranty Period",
			values: [{
				from: "/Date(1336611200000)/",
				to: "/Date(1349711200000)/",
				label: "Warranty Period", 
				customClass: "ganttOrange"
			}]
		}],
		navigate: "scroll",
		scale: "weeks",
		maxScale: "months",
		minScale: "days",
		itemsPerPage: 10,
		onItemClick: function(data) {
			alert("Item clicked - show some details");
		},
		onAddClick: function(dt, rowId) {
			alert("Empty space clicked - add an item!");
		},
		onRender: function() {
			if (window.console && typeof console.log === "function") {
				console.log("chart rendered");
			}
		}
	});

	$(".gantt").popover({
		selector: ".bar",
		title: "I'm a popover",
		content: "And I'm the content of said popover."
	});

	prettyPrint();
	*/
	
	// 项目切换
	$(".ucm_project_box ul li").each(function (){
		if($(this).attr("class") != "upb_li_header"){
			$(this).click(function (){
				$(this).addClass("upb_desc_checked").siblings().removeClass("upb_desc_checked");
				var pid = $(this).attr("pid");
				$(".ucm_project_list").find("div.p_list_box").each(function (){
					if("p_list_box_"+pid == $(this).attr("id")){
						//alert($(this).html());
						var now_obj = $(this).find("ol.ucm_c_list");
						var com_box  = $("#td_respond_box");
						if(now_obj.length > 0){
							com_box.insertAfter(now_obj);
						}
						//$(this).css("display","block").siblings().css("display","none");
						$(this).siblings().css("display","none").end().fadeIn("slow").css("display","block");
					}
				});
			});
		}
	});
});

$(window).load(function (){
	// 所有界面以及执行效果执行完成之后执行
	initPage();
});

function initPage(){
	var init_obj = 0;
	$(".ucm_project_box ul li").each(function (){
		if($(this).attr("class") != "upb_li_header"){
			if($(this).hasClass("upb_desc_checked")){
				init_obj = $(this).attr("pid");
			}
		}
	});
	var check_sign = false;
	$(".ucm_project_list").find("div.p_list_box").each(function (i){
		if(init_obj != 0){
			if("p_list_box_"+init_obj == $(this).attr("id")){
				$(this).css("display","block").siblings().css("display","none");
				check_sign = true;
			}
		}else{
			if(i == 0){
				$(this).css("display","block").siblings().css("display","none");
				check_sign = true;
			}	
		}
	});
	if(!check_sign){
		$(".ucm_project_list").find("div.p_list_box").first().css("display","block").siblings().css("display","none");
	}
}

function changeCommentSet(){
	$(".ucm_comment_box ol li").each(function (){
		$(this).css("display","block");
		if($(this).find("div.ucm_t_right").length > 0){
			$(this).find("div.ucm_t_right").removeClass("hide");
		}
	});
}

function saveCommentSet(){
	$(".ucm_comment_box ol li").each(function (){
		if($(this).find("div.ucm_t_right").length > 0){
			$(this).find("div.ucm_t_right").addClass("hide");
		}
		if($(this).attr("is_show") == 1){
			$(this).css("display","block");
		}else{
			$(this).css("display","none");
		}
	});
}

function toogleShow(obj,type){
	var li_box  = obj.parent().parent().parent().parent().parent();
	//alert(li_box.length);
	//alert(li_box.attr("class"));
	if(li_box.length > 0 && li_box.attr("class") == "ucm_comment_sli"){
		if(parseInt(type) == 1){
			li_box.attr("is_show",1);
		}else{
			li_box.attr("is_show",0);
		}
		obj.parent().addClass("hide");
		obj.parent().siblings().removeClass("hide");
	}
}

</script>