/**
 * 文章详情专用js
 * URI: http://www/trydemo.net
 */
var i = 0, got = -1, len = document.getElementsByTagName('script').length;
while ( i <= len && got == -1){
	var js_url = document.getElementsByTagName('script')[i].src,
		got = js_url.indexOf('article.js'); i++ ;
}

var	td_url = js_url.substr(0, js_url.indexOf('Public')),
	td_public_url = js_url.substr(0, js_url.indexOf('Js')),
	td_self_url = window.location.href,
	pic_no = td_public_url + 'Img/Common/no.png',      // 错误 icon
	pic_yes = td_public_url + 'Img/Common/yes.png',     // 成功 icon
	ajax_php_url = js_url.replace('Public/Js/Home/article.js','Article/ajaxShowMoreSubComments');


$(document).ready(function (){
	//alert('article.js加载完成！');
	
	//使用前端表情转换
	var comments_box_obj = $("#comments_display_area");
	//alert($("#ar_smilies_arr_info").html());
	//alert(comments_box_obj.find('li.comment').length);
	if(comments_box_obj.find('li.comment').length > 0){
		var smilies_str = $("#ar_smilies_arr_info").val();
		var smilies_obj;
		try {
			smilies_obj = eval("(" + smilies_str	
					+ ")");
			//jQuery版本过低
			//var obj = $.parseJSON('{"name":"John"}');
		} catch (exception) {
			alert(exception);
			alert("数据有误!");
		}

		var i = 1;
		comments_box_obj.find('li.comment').each(function(){
			if(i < 100){
				//alert($(this).find('div.replied_content').text());
				var con_obj = $(this).find('div.replied_content');
				var con = con_obj.text();
				//正则匹配替换表情
				for(var m=0;m<smilies_obj.length;m++){
					var reg=new RegExp("\:("+smilies_obj[m]+")\:","gi");
					con= con.replace(reg,"<img class='td-smiley' alt='$1' src='"+root+"/Public/Img/Common/smilies/icon_$1.gif' />");
				}
				con_obj.html(con);
			}
			i++;
		});
	}
	
	
	
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
		html_len = (obj.innerHTML).length ? (obj.innerHTML).length : (obj.value).length;
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

//子回复分页
function seeMoreSubComments(obj,cid,page){
	//alert(obj.html());
	//alert(cid);
	//alert(page);
	//判断当前子评论总数	
	
	var show_more_url = $("#commentform input[name='actAjaxShowMoreUrl']").val();
	if(cid && parseInt(cid) > 0 && page && parseInt(page) > 0){
		$.ajax({
			type:"post",
			url:show_more_url,
			data:"cid="+parseInt(cid)+"&p="+parseInt(page),
			dataType:"json",
			success:function(result){
				if(result.flag){
					if(result.data.data && result.data.data.length > 0){
						alert("执行成功！");
						var c_area = $("#comments_display_area");
						var li_obj = obj.parent().parent().parent().parent();
						var tmp_node_arr = new Array();
						var tmp_subc_count = -1;
						//alert(li_obj.prev().html());
						c_area.find("li").each(function(){
							if($(this).length > 0 && $(this).attr("cbp") == parseInt(cid)){
								//alert($(this).html());
								if($(this).find("div.reply_hint").length > 0){
									//alert($(this).attr("id"));
									//alert($(this).index());
									tmp_node_arr.push($(this).index());
								}else{
									//alert($(this).attr("id"));
								}
								tmp_subc_count++;
							}
						});
						
						alert(tmp_node_arr.length);
						for(var i=0;i<result.data.data.length;i++){
							//alert(result.data.data[i].cid);
							var new_htm = "\n<li class='comment comment_children' id='li-comment-"+result.data.data[i].cid+"' cbp='"+cid+"' ></li>\n";
							var replay_box = "\n<div id='comment-"+result.data.data[i].cid+"' class='replied_box'></div>\n";
							var box_left = "\n<div class='replied_left'><img src='"+td_url+"Uploads/Common/avatar_small/no_face.png' class='photo' height='44' width='44' /></div>\n";
							var box_right = "<div class='replied_right'>\n<div class='replied_username'><strong>"+result.data.data[i].sub_marking+"</strong></div>\n<div class='replied_content'>"+result.data.data[i].comment_content+"</div>\n<div class='replied_time' style='margin-bottom:10px;'>\n<span>"+result.data.data[i].comment_edit_date+"</span>\n<span class='reply'>\n<a onclick='return addComment.moveForm(\"comment-"+result.data.data[i].cid+"\", \""+result.data.data[i].cid+"\", \""+result.data.data[i].comment_author_name+"\", \""+result.data.data[i].comment_parent+"\", \""+result.data.data[i].aid+"\",0);' href='"+td_self_url+"'>[回复]</a>\n</span>\n</div>\n</div>";
							var box_hint = "<div class='reply_hint'>\n<img alt='' style='vertical-align:middle;' src='"+pic_yes+"'></img><span>提交成功, 刷新页面之前可以<a href='' onclick='return addComment.moveForm(\"comment-"+result.data.data[i].cid+"\", \""+result.data.data[i].cid+"\", \""+result.data.data[i].comment_author_name+"\" , \""+result.data.data[i].comment_parent+"\", \""+result.data.data[i].aid+"\", 1);'>&nbsp;&nbsp;&nbsp;再编辑</a></span>\n</div>";
							var box_clear = "<div class='clear'></div>";
							
							//alert(tmp_node_arr.length);
							if(tmp_node_arr.length > 0){
								//如果有临时回复（判断重复重新排序）
								var show_obj = c_area.find("li[cbp='sm_"+cid+"']");
								if(show_obj.length > 0){
									if(result.data.remnant && parseInt(result.data.remnant) > 0){
										var new_show_html = "<p class='re_r_m_count'><span>还有"+result.data.remnant+"条回复，</span><a class='re_s_m_check' onclick='seeMoreSubComments($(this),2,"+result.data.nextPage+")' href='javascript:void(0);'>点击查看</a></p>";
										show_obj.find("div.replied_show_more_left").html(new_show_html);
									}else{
										show_obj.hide();
										//可选择不隐藏该模块、改成文字提示已经到最后一条子评论
									}
								}
								
								for(var m=0;m<tmp_node_arr.length;m++){
									var sub_tmp_obj = c_area.find("li").eq(tmp_node_arr[m]);
									if(sub_tmp_obj.length > 0){
										var tmp_id = sub_tmp_obj.attr("id");
										//alert(tmp_id);
										//alert(result.data.data[i].cid);
										if(tmp_id && tmp_id.length > 0 && tmp_id.indexOf("-") > 0){
											var old_id_arr = tmp_id.split("-");
											if(old_id_arr.length > 0){
												var old_id = old_id_arr.pop();
												if(old_id && parseInt(old_id) == result.data.data[i].cid){
													//alert(sub_tmp_obj.html());
													sub_tmp_obj.remove();
													var prev = c_area.children('li[cbp='+cid+']').last();
													prev.after(new_htm);
													$("#li-comment-"+result.data.data[i].cid).hide().append(replay_box);
													$("#comment-"+result.data.data[i].cid).append(box_left);
													$("#comment-"+result.data.data[i].cid).append(box_right);
													$("#comment-"+result.data.data[i].cid).append(box_hint);
													$("#comment-"+result.data.data[i].cid).append(box_clear);
													$("#li-comment-"+result.data.data[i].cid).fadeIn(3000);
												}else{
													var prev = c_area.children('li[cbp='+cid+']').last();
													prev.after(new_htm);
													$("#li-comment-"+result.data.data[i].cid).hide().append(replay_box);
													$("#comment-"+result.data.data[i].cid).append(box_left);
													$("#comment-"+result.data.data[i].cid).append(box_right);
													//$("#comment-"+result.data.data[i].cid).append(box_hint);
													$("#comment-"+result.data.data[i].cid).append(box_clear);
													$("#li-comment-"+result.data.data[i].cid).fadeIn(3000);
												}
												
											}else{
												var prev = c_area.children('li[cbp='+cid+']').last();
												prev.after(new_htm);
												$("#li-comment-"+result.data.data[i].cid).hide().append(replay_box);
												$("#comment-"+result.data.data[i].cid).append(box_left);
												$("#comment-"+result.data.data[i].cid).append(box_right);
												//$("#comment-"+result.data.data[i].cid).append(box_hint);
												$("#comment-"+result.data.data[i].cid).append(box_clear);
												$("#li-comment-"+result.data.data[i].cid).fadeIn(3000);
											}
										}else{
											var prev = c_area.children('li[cbp='+cid+']').last();
											prev.after(new_htm);
											$("#li-comment-"+result.data.data[i].cid).hide().append(replay_box);
											$("#comment-"+result.data.data[i].cid).append(box_left);
											$("#comment-"+result.data.data[i].cid).append(box_right);
											//$("#comment-"+result.data.data[i].cid).append(box_hint);
											$("#comment-"+result.data.data[i].cid).append(box_clear);
											$("#li-comment-"+result.data.data[i].cid).fadeIn(3000);
										}
									}else{
										var prev = c_area.children('li[cbp='+cid+']').last();
										prev.after(new_htm);
										$("#li-comment-"+result.data.data[i].cid).hide().append(replay_box);
										$("#comment-"+result.data.data[i].cid).append(box_left);
										$("#comment-"+result.data.data[i].cid).append(box_right);
										//$("#comment-"+result.data.data[i].cid).append(box_hint);
										$("#comment-"+result.data.data[i].cid).append(box_clear);
										$("#li-comment-"+result.data.data[i].cid).fadeIn(3000);
									}
								}

							}else{
								//不存在临时回复
								var show_obj = c_area.find("li[cbp='sm_"+cid+"']");
								if(show_obj.length > 0){
									if(result.data.remnant && parseInt(result.data.remnant) > 0){
										var new_show_html = "<p class='re_r_m_count'><span>还有"+result.data.remnant+"条回复，</span><a class='re_s_m_check' onclick='seeMoreSubComments($(this),2,"+result.data.nextPage+")' href='javascript:void(0);'>点击查看</a></p>";
										show_obj.find("div.replied_show_more_left").html(new_show_html);
									}else{
										show_obj.hide();
										//可选择不隐藏该模块、改成文字提示已经到最后一条子评论
									}
								}
								
								var prev = c_area.children('li[cbp='+cid+']').last();
								prev.after(new_htm);
								$("#li-comment-"+result.data.data[i].cid).hide().append(replay_box);
								$("#comment-"+result.data.data[i].cid).append(box_left);
								$("#comment-"+result.data.data[i].cid).append(box_right);
								//$("#comment-"+result.data.data[i].cid).append(box_hint);
								$("#comment-"+result.data.data[i].cid).append(box_clear);
								$("#li-comment-"+result.data.data[i].cid).fadeIn(3000);	
							}
						}

					}else{
						alert("已经到最后一条评论！");
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
	}
	
}

