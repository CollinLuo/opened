/**
 * 评论专用js
 * URI: http://www/trydemo.net
 */

	var i = 0, got = -1, len = document.getElementsByTagName('script').length;
	while ( i <= len && got == -1){
		var js_url = document.getElementsByTagName('script')[i].src,
			got = js_url.indexOf('comments-ajax.js'); i++ ;
	}

	var edit_mode = '1', // 再编辑模式 ( '1'=开; '0'=不开 )
		td_url = js_url.substr(0, js_url.indexOf('Public')),
		td_public_url = js_url.substr(0, js_url.indexOf('Js')),
		td_self_url = window.location.href,
		pic_no = td_public_url + 'Img/Common/no.png',      // 错误 icon
		pic_yes = td_public_url + 'Img/Common/yes.png',     // 成功 icon
		ajax_php_url = js_url.replace('Public/Js/Home/comments-ajax.js','index.php?m=&c=Company&a=ajaxComment');
	
	//JQ start
	jQuery(document).ready(function(){
		$submit = $('#commentform #submit'); 
		$submit.attr('disabled', false);
		$cda = $("#comments_display_area"); //获取评论显示区域
		$('#c_loading').hide(); 
		$('#c_error').hide();
		$body = (window.opera) ? (document.compatMode == "CSS1Compat" ? $('html') : $('body')) : $('html,body');
		var act_option = $("#commentform input[name='act_option']");
		if(act_option.length > 0){
			var act_o = act_option.val();
			switch(parseInt(act_o)){
				case 1:
					ajax_php_url = ajax_php_url.replace('ajaxComment','ajaxMessageBoardComment');
					break;
				case 2:
					ajax_php_url = ajax_php_url.replace('ajaxComment','ajaxLinkBoardComment');
					break;
			}
		}
		// alert(ajax_php_url);
		/** submit **/
		$('#commentform').submit(function() {
			$('#c_loading').show();
			$('#c_loading').slideDown();
			$submit.attr('disabled', true).fadeTo('slow', 0.5);
			/** Ajax **/
			$.ajax({
				url: ajax_php_url,
				data: $(this).serialize(),
				type: $(this).attr('method'),
				error: function(result) {
					// alert('回传数据失败！');
					$('#c_loading').slideUp();
					$('#c_error').slideDown().find('span').text(result.msg);
					setTimeout(function(){$submit.attr('disabled', false).fadeTo('slow', 1); $('#c_error').slideUp();}, 3000);
				},
				success: function(result) {
					if(result.flag == 1){
						//开始显示
						$('#c_loading').hide();
						//开始显示最新的评论
						if(result.data.cid){
							//alert('开始显示新的评论！');
							$('textarea').each(function() {this.value = ''});
							var num_id = parseInt(result.data.cid);
							var parent_id = parseInt(result.data.comment_parent);
							var best_p_id = parseInt(result.data.comment_top_parent);
							var p_demo, t = addComment, parent = t.I('comment_parent'), best_parent = t.I('c_best_parent'), reply_box = t.I('respond');

							if(!result.data.update_cid || typeof(result.data.update_cid)=="undefined" || result.data.update_cid==0){
								//回复或者新增模式
								var new_htm = "\n<li class='comment' id='li-comment-"+num_id+"' cbp='"+result.data.comment_top_parent+"' ></li>\n";
								var new_htms = "\n<li class='comment comment_children' id='li-comment-"+num_id+"' cbp='"+result.data.comment_top_parent+"' ></li>\n";
								var replay_box = "\n<div id='comment-"+num_id+"' class='replied_box_right'></div>\n";
								var box_left = "\n<div class='replied_left'><img src='"+td_url+"Uploads/Common/avatar_small/"+result.data.comment_author_avatar+"' class='photo' height='44' width='44' /></div>\n";
								var box_right = "<div class='replied_right'>\n<div class='replied_username'><strong><a><b>"+result.data.comment_author_name+"&nbsp;:</b></a></strong></div>\n<div class='replied_content'>"+result.data.comment_content+"</div>\n<div class='replied_time' style='margin-bottom:10px;'>\n<span>"+result.data.comment_edit_date+"</span>\n</div>\n</div>";
								var box_hint = "<div class='reply_hint'>\n<img alt='' style='vertical-align:middle;' src='"+pic_yes+"'></img><span>提交成功, 刷新页面之前可以<a href='' onclick='return addComment.moveForm(\"comment-"+result.data.cid+"\", \""+result.data.cid+"\", \""+result.data.comment_author_name+"\" , \""+result.data.comment_parent+"\", \""+result.data.correlation_id+"\", 1);'>&nbsp;&nbsp;&nbsp;再编辑</a></span>\n</div>";
								var box_clear = "<div class='clear'></div>";

								//判断提交类型（回复评论、直接评论、编辑未刷新评论）
								p_demo = $(reply_box).parent().attr('id');	  //此处注意DOM对象转换JQuery对象
								if(p_demo == 'li-comment-'+parent_id){
									//此处为回复或者编辑
									var box_rights = "<div class='replied_right'>\n<div class='replied_username'><strong>"+result.data.sub_marking+"</strong></div>\n<div class='replied_content'>"+result.data.comment_content+"</div>\n<div class='replied_time' style='margin-bottom:10px;'>\n<span>"+result.data.comment_edit_date+"</span>\n</div>\n</div>";
									var cbp = $(reply_box).parent().attr('cbp');
									//获取对应阶层最后一条回复节点
									//alert(cbp);
									var prev = $(reply_box).parent().parent().children('li[cbp='+cbp+']').last();
									prev.after(new_htms);
									reply_box_init();
									$("#li-comment-"+num_id).hide().append(replay_box);
									$("#comment-"+num_id).append(box_left);
									$("#comment-"+num_id).append(box_rights);
									$("#comment-"+num_id).append(box_hint);
									$("#comment-"+num_id).append(box_clear);
									$("#li-comment-"+num_id).fadeIn(4000);	
									
								}else{
									//此处为评论文章及顶级评论
									//alert('顶级评论');
									$cda.append(new_htm);
									$("#li-comment-"+num_id).hide().append(replay_box);
									$("#comment-"+num_id).append(box_left);
									$("#comment-"+num_id).append(box_right);
									$("#comment-"+num_id).append(box_hint);
									$("#comment-"+num_id).append(box_clear);
									$("#li-comment-"+num_id).fadeIn(4000);	
								}
								//alert(p_demo.attr('style'));
											
							}else{
								//编辑模式
								num_id = parseInt(result.data.update_cid);
								edit_box = t.I('comment-'+num_id);
								if(edit_box){
									$(edit_box).find('div[class=replied_content]').html(result.data.comment_content);
									$(edit_box).show();
								}else{
									//alert('编辑模式bug');
									/***正常模式下不会出现的bug***/
									window.location.reload();
								}
								reply_box_init();
							}
							
							$body.animate( { scrollTop: $("#li-comment-"+num_id).offset().top - 200}, 900);
						}else{
							$('#c_error').slideDown().find('span').text('系统错误！请尝试重新提交！');
							setTimeout(function() {$submit.attr('disabled', false).fadeTo('slow', 1); $('#c_error').slideUp();}, 3000);
						}
						
						//每次提交完成之后回复框体自动复位到初始状态
						countdown();
					}else{
						//评论失败
						$('#c_loading').slideUp();
						$('#c_error').slideDown().find('span').text(result.msg);
						setTimeout(function() {$submit.attr('disabled', false).fadeTo('slow', 1); $('#c_error').slideUp();}, 3000);
					}

				}
			});
			$('*').remove('#c_edit_id');
			return false;
		}); 


	//动态回复以及修改为刷新前评论内容
	addComment = {
		moveForm : function(commId, parentId, parentName, bestParentId, postId, type) {
			var t = this, div, bestPid, temp, comm = t.I(commId), respond = t.I('respond'), cancel = t.I('cancel-comment-reply-link'), parent = t.I('comment_parent'), post = t.I('c_correlation_id'), best_parent = t.I('c_best_parent'),parent_author = t.I('comment_parent_author_name');
			if(type == 1){
				//编辑模式
				//alert($(comm).find('div[class=replied_content]').html());
				var edit_content_html = $(comm).find('div[class=replied_content]').html();
				var edit_content_text = $(comm).find('div[class=replied_content]').text();
				var edit_contet = '';
				//alert(edit_content_text);
				if(edit_content_html == edit_content_text){
					edit_contet = edit_content_text;
				}else{

					var init_str = edit_content_html.replace(/\<img\s+class=\"td\-smiley\"\s+alt=\"([a-zA-Z0-9_]*)\"\s+src=\"([^\>]*)\"\>/ig,":$1:");

					//var demo = '请叫我小贵！ <img class="td-smiley" alt="sad" src="/trydemo/Public/Img/Common/smilies/icon_sad.gif">  <img class="td-smiley" alt="confused" src="/trydemo/Public/Img/Common/smilies/icon_confused.gif">  <img class="td-smiley" alt="twisted" src="/trydemo/Public/Img/Common/smilies/icon_twisted.gif">'; 
					//var demo1 = demo.replace(/\<img class=\"td\-smiley\" alt=\"(.*)\" src=\"[^\"]*\"\"\>/,"777");
					//var demo1 = demo.replace(/\<img class=\"td\-smiley\" alt=\"(\w*)\" src=\"([^\>]*)\"\>/ig,'666');
					//var demo1 = demo.replace(/\<img\s+class=\"td\-smiley\"\s+alt=\"([a-zA-Z0-9_]*)\"\s+src=\"([^\>]*)\"\>/ig,'666');
					//alert(demo1);

					edit_contet = init_str;

				}
				$('#c_best_parent').after('<input name="c_edit_id" id="c_edit_id" value="'+parentId+'" type="hidden" />');
				//$("#c_comment").val($(comm).find('div[class=replied_content]').text());
				$("#c_comment").val(edit_contet);
				//$(cacel).text('');
				$(comm).hide();
				
			}else{
				parent_author.value = parentName;
				parent.value = parentId;	//给隐藏域赋值
				best_parent.value = bestPid; 
			}

			if(!bestParentId || typeof(bestParentId)=="undefined" || bestParentId==0){
				bestPid = parseInt(parentId);
			}else{
				bestPid = parseInt(bestParentId);
			}
			if (!t.I('td-temp-form-div')){
				div = document.createElement('div');
				div.id = 'td-temp-form-div';
				div.style.display = 'none';
				respond.parentNode.insertBefore(div,respond)
			}

			!comm ? ( 
				temp = t.I('td-temp-form-div'),
				t.I('comment_parent').value = '0',
				temp.parentNode.insertBefore(respond, temp),
				temp.parentNode.removeChild(temp)
			) : comm.parentNode.insertBefore(respond, comm.nextSibling);
			$body.animate( { scrollTop: $('#respond').offset().top - 180 }, 400);
			if ( post && postId ) post.value = postId;
			cancel.style.display = '';

			cancel.onclick = function () {
				var t = addComment, temp = t.I('td-temp-form-div'), respond = t.I('respond');
				t.I('comment_parent').value = '0';
				if ( temp && respond ) {
					temp.parentNode.insertBefore(respond, temp);
					temp.parentNode.removeChild(temp);
				}
				if(type == 1){
					$(comm).show();
					$("#c_comment").val('');
				}

				this.style.display = 'none';
				this.onclick = null;
				return false;
			};

			try{t.I('c_comment').focus();}catch(e){}
			return false;
		},
		
		I : function(e) {
			return document.getElementById(e);
		}
	}; // end addComment

	//初始化回复框
	function reply_box_init(){
		var t = addComment, temp = t.I('td-temp-form-div'), respond = t.I('respond'), cancel = t.I('cancel-comment-reply-link');
		t.I('comment_parent').value = '0';
		t.I('c_best_parent').value = '0';
		t.I('comment_parent_author_name').value = '';
		if ( temp && respond ) {
			temp.parentNode.insertBefore(respond, temp);
			temp.parentNode.removeChild(temp);
		}
		cancel.style.display = 'none';
		cancel.onclick = null;
		return false;
	}

	var wait = 15, submit_val = $submit.val();
	function countdown() {
		if ( wait > 0 ) {
			$submit.val(wait); wait--; setTimeout(countdown, 1000);
		} else {
			$submit.val(submit_val).attr('disabled', false).fadeTo('slow', 1);
			wait = 15;
		}
	}
});
// end jQ