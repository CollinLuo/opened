/**
 * 文章详情专用js
 * URI: http://www/trydemo.net
 */
/*
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
*/
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
