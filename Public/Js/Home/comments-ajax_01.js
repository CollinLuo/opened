/**
 * 博文评论专用js
 * URI: http://www/trydemo.net
 */


var i = 0, got = -1, len = document.getElementsByTagName('script').length;
while ( i <= len && got == -1){
	var js_url = document.getElementsByTagName('script')[i].src,
		got = js_url.indexOf('comments-ajax.js'); i++ ;
}
var edit_mode = '1', // 再编辑模式 ( '1'=开; '0'=不开 )
	ajax_php_url = js_url.replace('Public/Js/Home/comments-ajax.js','Article/ajaxComment'),
	pic_sb = '__ROOT__/Public/Img/Common/loading.gif', // 提交 icon
	pic_no = '__ROOT__/Public/Img/Common/no.png',      // 错误 icon
	pic_ys = '__ROOT__/Public/Img/Common/yes.png',     // 成功 icon
	txt1 = '<div id="c_loading"><img src="' + pic_sb + '" style="vertical-align:middle;" alt=""/> 正在提交, 请稍候...</div>',
	txt2 = '<div id="c_error">#</div>',
	txt3 = '"><img src="' + pic_ys + '" style="vertical-align:middle;" alt=""/> 提交成功',
	edt1 = ', 刷新页面之前可以<a rel="nofollow" class="comment-reply-link" href="#edit" onclick=\'return addComment.moveForm("',
	edt2 = ')\'>再编辑</a>',
	cancel_edit = '取消编辑', num = 1, comm_array=[]; comm_array.push('');


	jQuery(document).ready(function() {
		$comments = $('#comments-title'); 
		$cancel = $('#cancel-comment-reply-link'); 
		var cancel_text = $cancel.text();
		$submit = $('#commentform #submit'); 
		$submit.attr('disabled', false);
		$('#c_comment').after( txt1 + txt2 ); 
		$('#c_loading').hide(); 
		$('#c_error').hide();
		$body = (window.opera) ? (document.compatMode == "CSS1Compat" ? $('html') : $('body')) : $('html,body');

		/** submit */
		$('#commentform').submit(function() {
			$('#c_loading').show();
			$("#c_loading img").attr("src",root+"/Public/Img/Common/loading.gif");
			$('#c_loading').slideDown();
			$submit.attr('disabled', true).fadeTo('slow', 0.5);
		
			//alert($(this).serialize());
			//alert($(this).attr('method'));
			/** Ajax **/
			$.ajax({
				url: ajax_php_url,
				data: $(this).serialize(),
				type: $(this).attr('method'),
				error: function(result) {
						//alert('回传数据失败！');
						$('#c_loading').slideUp();
						$('#c_error').slideDown().html('<img src="' + pic_no + '" style="vertical-align:middle;" alt=""/> ' + request.responseText);
						setTimeout(function() {$submit.attr('disabled', false).fadeTo('slow', 1); $('#c_error').slideUp();}, 3000);
				},
				success: function(result) {
					
					//alert('回传数据成功！');
					//alert(result.status);
					//alert(result.info);
				
					//alert(result.data.cid);
					//alert(result.data.comment_content);
					
					if(result.status == 1){
						//alert(111);
						//开始显示
						//$('#c_loading').hide();
						comm_array.push(result.data.comment_content);
						$('textarea').each(function() {this.value = ''});
						


					}else{
						//alert(222);
						//$('#c_loading').hide();
					}

					
					/*
					var t = addComment, cancel = t.I('cancel-comment-reply-link'), temp = t.I('wp-temp-form-div'), respond = t.I(t.respondId), post = t.I('comment_post_ID').value, parent = t.I('comment_parent').value;
					

					// comments
					if ( ! edit && $comments.length ) {
						n = parseInt($comments.text().match(/\d+/));
						$comments.text($comments.text().replace( n, n + 1 ));
					}

					// show comment
					new_htm = '" id="new_comm_' + num + '"></';
					new_htm = ( parent == '0' ) ? ('\n<ol style="clear:both;" class="commentlist' + new_htm + 'ol>') : ('\n<ul class="children' + new_htm + 'ul>');

					ok_htm = '\n<span id="success_' + num + txt3;
					if ( edit_mode == '1' ) {
						div_ = (document.body.innerHTML.indexOf('div-comment-') == -1) ? '' : ((document.body.innerHTML.indexOf('li-comment-') == -1) ? 'div-' : '');
						ok_htm = ok_htm.concat(edt1, div_, 'comment-', parent, '", "', parent, '", "respond", "', post, '", ', num, edt2);
					}
					ok_htm += '</span><span></span>\n';

					$('#respond').before(new_htm);
					$('#new_comm_' + num).hide().append(result);
					$('#new_comm_' + num + ' li').append(ok_htm);
					$('#new_comm_' + num).fadeIn(4000);

					$body.animate( { scrollTop: $('#new_comm_' + num).offset().top - 200}, 900);
					countdown(); num++ ; edit = ''; $('*').remove('#edit_id');
					cancel.style.display = 'none';
					cancel.onclick = null;
					t.I('comment_parent').value = '0';
					if ( temp && respond ) {
						temp.parentNode.insertBefore(respond, temp);
						temp.parentNode.removeChild(temp)
					}
						*/

					countdown();
				}
			}); 
			return false;
		}); 

	/** comment-reply.dev.js 
	addComment = {
		moveForm : function(commId, parentId, respondId, postId, num) {
			var t = this, div, comm = t.I(commId), respond = t.I(respondId), cancel = t.I('cancel-comment-reply-link'), parent = t.I('comment_parent'), post = t.I('comment_post_ID');
			if ( edit ) exit_prev_edit();
			num ? (
				t.I('comment').value = comm_array[num],
				edit = t.I('new_comm_' + num).innerHTML.match(/(comment-)(\d+)/)[2],
				$new_sucs = $('#success_' + num ), $new_sucs.hide(),
				$new_comm = $('#new_comm_' + num ), $new_comm.hide(),
				$cancel.text(cancel_edit)
			) : $cancel.text(cancel_text);

			t.respondId = respondId;
			postId = postId || false;

			if ( !t.I('wp-temp-form-div') ) {
				div = document.createElement('div');
				div.id = 'wp-temp-form-div';
				div.style.display = 'none';
				respond.parentNode.insertBefore(div, respond)
			}

			!comm ? ( 
				temp = t.I('wp-temp-form-div'),
				t.I('comment_parent').value = '0',
				temp.parentNode.insertBefore(respond, temp),
				temp.parentNode.removeChild(temp)
			) : comm.parentNode.insertBefore(respond, comm.nextSibling);

			$body.animate( { scrollTop: $('#respond').offset().top - 180 }, 400);

			if ( post && postId ) post.value = postId;
			parent.value = parentId;
			cancel.style.display = '';

			cancel.onclick = function() {
				if ( edit ) exit_prev_edit();
				var t = addComment, temp = t.I('wp-temp-form-div'), respond = t.I(t.respondId);

				t.I('comment_parent').value = '0';
				if ( temp && respond ) {
					temp.parentNode.insertBefore(respond, temp);
					temp.parentNode.removeChild(temp);
				}
				this.style.display = 'none';
				this.onclick = null;
				return false;
			};

			try { t.I('comment').focus(); }
			catch(e) {}

			return false;
		},

		I : function(e) {
			return document.getElementById(e);
		}
	}; // end addComment
*/

function exit_prev_edit() {
	$new_comm.show(); $new_sucs.show();
	$('textarea').each(function() {this.value = ''});
	edit = '';
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