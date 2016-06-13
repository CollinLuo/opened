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
	<script type="text/javascript" src="/opened/Public/Js/Home/comments-ajax.js"></script>
	<!-- End .shortcut-buttons-set -->
	<div id="main_box">
		<div class="content-box">
      		<!-- Start Content Box -->
      			<div class="content-box-header">
				<h3><?php echo ($navigation_bar); ?></h3>
        			<ul class="content-box-tabs">
          				<li><a href="#tab1" class="default-tab"><?php if($act == 'medit'): ?>编辑<?php else: ?>添加<?php endif; ?>文件</a></li>
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
					<div class="c_input_box">
						<?php if($act == 'madd'): ?><form action="<?php echo U('Bbs/mAdd');?>" id="comment_form" method="post" enctype="multipart/form-data" onsubmit="return addValidate()">
						<?php elseif($act == 'medit'): ?>
						<form action="<?php echo U('Bbs/mEdit');?>" id="comment_form" method="post" enctype="multipart/form-data" onsubmit="return addValidate()">
						<?php else: ?>
						<form action="<?php echo U('Bbs/mComments');?>" id="comment_form" method="post" enctype="multipart/form-data" onsubmit="return addValidate()"><?php endif; ?>
							<fieldset>
								<p>
									<label>标题:</label>
									<input class="text-input small-input" type="text" id="title" name="title" value="<?php echo ($info["title"]); ?>" disabled />
								</p>
								<p>
              						<label>是否审核:</label>
									<?php if($act == 'medit' and $info["comment_approved"] == 0): ?><input type="radio" name="comment_approved" value="0" checked="checked" />不通过&nbsp;&nbsp;&nbsp;&nbsp;
              						<input type="radio" name="comment_approved" value="1" />通过
									<?php elseif($act == 'medit' and $info["comment_approved"] == 1): ?>
									<input type="radio" name="comment_approved" value="0" />不通过&nbsp;&nbsp;&nbsp;&nbsp;
              						<input type="radio" name="comment_approved" value="1" checked="checked" />通过
									<?php else: ?>
              						<input type="radio" name="comment_approved" value="0" checked="checked" />不通过&nbsp;&nbsp;&nbsp;&nbsp;
              						<input type="radio" name="comment_approved" value="1" />通过<?php endif; ?>
								</p>
								<p>
              						<label>是否锁定:</label>
									<?php if($act == 'medit' and $info["is_lock"] == 0): ?><input type="radio" name="is_lock" value="0" checked="checked" />不锁定&nbsp;&nbsp;&nbsp;&nbsp;
              						<input type="radio" name="is_lock" value="1" />锁定
									<?php elseif($act == 'medit' and $info["is_lock"] == 1): ?>
									<input type="radio" name="is_lock" value="0" />不锁定&nbsp;&nbsp;&nbsp;&nbsp;
              						<input type="radio" name="is_lock" value="1" checked="checked" />锁定
									<?php else: ?>
              						<input type="radio" name="is_lock" value="0" checked="checked" />不锁定&nbsp;&nbsp;&nbsp;&nbsp;
              						<input type="radio" name="is_lock" value="1" />锁定<?php endif; ?>
								</p>
								<p>
									<label>评论内容:</label>
									<!--<textarea class="text-input textarea wysiwyg" id="comment_content" name="comment_content" cols="79" rows="10"><?php echo ($info["comment_content"]); ?></textarea>-->
									<div class="comt-box">
										<fieldset><textarea name="comment_content" id="c_comment" class="comt-area" tabindex="4" cols="50" rows="5" onkeyup="keyups.onstr(this)"><?php echo ($info["comment_content"]); ?></textarea></fieldset>
										<div id="c_loading" style="display:none;"><img src="/opened/Public/Img/Common/loading.gif" style="vertical-align:middle;" alt="" /> 正在提交, 请稍候...</div>
										<div style="display:none;" id="c_error"><img src="/opened/Public/Img/Common/no.png" style="vertical-align:middle;" alt="" /><span></span></div>
										<div class="comt-ctrl">
											<div class="comt-addsmilies">
												<a href="javascript:void(0);">表情</a>
												<input id='ar_smilies_arr_info' type='hidden' name='smilies_arr' value='' />
											</div>
											<div class="comt-smilies">
												<a title="mrgreen" href="javascript:grin(':mrgreen:')"><img src="/opened/Public/Img/Common/smilies/icon_mrgreen.gif"></a><a title="razz" href="javascript:grin(':razz:')"><img src="/opened/Public/Img/Common/smilies/icon_razz.gif"></a><a title="sad" href="javascript:grin(':sad:')"><img src="/opened/Public/Img/Common/smilies/icon_sad.gif"></a><a title="smile" href="javascript:grin(':smile:')"><img src="/opened/Public/Img/Common/smilies/icon_smile.gif"></a><a title="redface" href="javascript:grin(':redface:')"><img src="/opened/Public/Img/Common/smilies/icon_redface.gif"></a><a title="biggrin" href="javascript:grin(':biggrin:')"><img src="/opened/Public/Img/Common/smilies/icon_biggrin.gif"></a><a title="eek" href="javascript:grin(':eek:')"><img src="/opened/Public/Img/Common/smilies/icon_surprised.gif"></a><a title="confused" href="javascript:grin(':confused:')"><img src="/opened/Public/Img/Common/smilies/icon_confused.gif"></a><a title="cool" href="javascript:grin(':cool:')"><img src="/opened/Public/Img/Common/smilies/icon_cool.gif"></a><a title="lol" href="javascript:grin(':lol:')"><img src="/opened/Public/Img/Common/smilies/icon_lol.gif"></a><a title="mad" href="javascript:grin(':mad:')"><img src="/opened/Public/Img/Common/smilies/icon_mad.gif"></a><a title="twisted" href="javascript:grin(':twisted:')"><img src="/opened/Public/Img/Common/smilies/icon_twisted.gif"></a><a title="rolleyes" href="javascript:grin(':rolleyes:')"><img src="/opened/Public/Img/Common/smilies/icon_rolleyes.gif"></a><a title="wink" href="javascript:grin(':wink:')"><img src="/opened/Public/Img/Common/smilies/icon_wink.gif"></a><a title="idea" href="javascript:grin(':idea:')"><img src="/opened/Public/Img/Common/smilies/icon_idea.gif"></a><a title="arrow" href="javascript:grin(':arrow:')"><img src="/opened/Public/Img/Common/smilies/icon_arrow.gif"></a><a title="neutral" href="javascript:grin(':neutral:')"><img src="/opened/Public/Img/Common/smilies/icon_neutral.gif"></a><a title="cry" href="javascript:grin(':cry:')"><img src="/opened/Public/Img/Common/smilies/icon_cry.gif"></a><a title="question" href="javascript:grin(':question:')"><img src="/opened/Public/Img/Common/smilies/icon_question.gif"></a><a title="evil" href="javascript:grin(':evil:')"><img src="/opened/Public/Img/Common/smilies/icon_evil.gif"></a><a title="shock" href="javascript:grin(':shock:')"><img src="/opened/Public/Img/Common/smilies/icon_eek.gif"></a><a title="exclaim" href="javascript:grin(':exclaim:')"><img src="/opened/Public/Img/Common/smilies/icon_exclaim.gif"></a>
											</div>
											<div class="comt_s_box">
												<span id="com_r_hint" class="comt-num"><font>还能输入<em>240</em>个字</font></span>
												<input name="comment_parent" id="comment_parent" value="0" type="hidden" />
												<input name="comment_parent_author_name" id="comment_parent_author_name" value="" type="hidden" />
												<input name="c_best_parent" id="c_best_parent" value="0" type="hidden" />
												<input name="act_option" id="act_option" value="0" type="hidden" />
												<!-- 暂未启用 2014-5-12 <input name="c_edit_id" id="c_edit_id" value="0" type="hidden" />-->
											</div>
										</div>
									</div>
									
									
								</p>
								<p>
									<?php if($act == 'madd'): ?><input type="submit" value="添加" />
									<?php elseif($act == 'medit'): ?>
										<input type="hidden" name='id' value="<?php echo ($info["cid"]); ?>" />	
										<input name="submit" type="submit" value="修改" /><?php endif; ?>
									<input type="hidden" name='act' value="<?php echo ($act); ?>" />
									<input name="reset" type="reset" value="重置" />
								</p>
							</fieldset>
							<div class="clear"></div>
							<!-- End .clear -->
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
      	&#169; Copyright 2013 Your Company | Powered by <a href="http://www.trydemo.net">D.Apache.Luo</a> | <a href="#">Top</a> </small> </div>
    	<!-- End #footer -->
  </div>
  <!-- End #main-content -->
</div>
</div>
</body>
</html>

<script type="text/javascript">
	var root = "/opened";
	$(document).ready(function (){
		$('.comt-addsmilies').click(function(){
			$('.comt-smilies').toggle();
		});
		$('.comt-smilies a').click(function(){
			$(this).parent().hide();
		});
	});
	
	//文章详情页发表评论字数限制
	var keyups = {
		"onstr":function (obj){
			var tishi = document.getElementById('com_r_hint');
			html_len = (obj.value).length ? (obj.value).length : (obj.innerHTML).length;
			var max = 240;
			var minstr = parseInt(html_len);
			var det = max - minstr;
			if(det<0){
				//tishi.innerHTML ="<font color='red'>已超过<em>" + det*(-1) + "</em>字.</font>";
				tishi.innerHTML ="<font color='red'>您输入的评论字数已超过上限!请您文明发言!</font>";
				obj.innerHTML = obj.innerHTML.substr(0,240);
				obj.value = obj.value.substr(0,240);
			}else{
				//tishi.innerHTML = "<em>已输入" + minstr + "字,还可以输入" + det + "字.</em>";
				tishi.innerHTML = "<font>还能输入<em>" + det + "</em>字.</font>";
			}
		}
	}
	
	//使用表情
	function grin(tag) {
		var myField;
		tag = ' ' + tag + ' ';
		if (document.getElementById('c_comment') && document.getElementById('c_comment').type == 'textarea') {
			myField = document.getElementById('c_comment');
		} else {
			return false;
		}
		if (document.selection) {
			myField.focus();
			sel = document.selection.createRange();
			sel.text = tag;
			myField.focus();
		}
		else if (myField.selectionStart || myField.selectionStart == '0') {
			var startPos = myField.selectionStart;
			var endPos = myField.selectionEnd;
			var cursorPos = endPos;
			myField.value = myField.value.substring(0, startPos)
						  + tag
						  + myField.value.substring(endPos, myField.value.length);
			cursorPos += tag.length;
			myField.focus();
			myField.selectionStart = cursorPos;
			myField.selectionEnd = cursorPos;
		}
		else {
			myField.value += tag;
			myField.focus();
		}
		//重新计算字数上限
		keyups.onstr(myField);
	}

	//表单提交验证
	function addValidate(){
		var flag = true;
		var msg = '';
		var act = $("input[name='act']");
		var comment_content = $("#c_comment").val();
		if(comment_content.length > 0){
			if(comment_content.length > 240){
				$("#c_comment").html($("#c_comment").html().substr(0,240));
				$("#c_comment").val($("#c_comment").val().substr(0,240));
			}
		}else{
			flag = false;
			msg += '评论内容不能为空！\n';
		}
		if(!flag){
			alert(msg);
			//调用屏幕抖动效果
			addLoadEvent(function(){ var p=new Array(15,30,15,0,-15,-30,-15,0);p=p.concat(p.concat(p));var i=document.forms[0].id;g(i).position='relative';shake(i,p,20);});
		}
		//return false;
		return flag;
	}
</script>