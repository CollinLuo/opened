//TryDemo个人博客后台添加文章页专用jquery 2014-5-19
jQuery(document).ready(function(){
	//alert(123);
	//alert($('#ar_tab_main').attr('style'));
	var page = 1;
	var max_page = 2; //最大页数
	$('#ar_tab_detail').attr('style','display:none');
	if($('#ar_tab_detail').attr('style') && $('#ar_tab_detail').css('display')=='none'){
		$('#ar_btn_prev').attr('disabled',true);
		$('#ar_btn_next').attr('disabled',false);
		page = 1;
	}else{
		if($('#ar_tab_main').attr('style') && $('#ar_tab_main').css('display')=='none'){
			$('#ar_btn_prev').attr('disabled',false);
			$('#ar_btn_next').attr('disabled',true);
			page = 2;
		}
	}
	
	$('#ar_btn_prev').click(function(){
		$('#ar_tab_form_box table').each(function(){
			if($(this).css('display') != 'none' && $(this).attr('id') == 'ar_tab_main'){
				page = 1;
			}else if($(this).css('display') != 'none' && $(this).attr('id') == 'ar_tab_detail'){
				page = 2;
			}
		});
		if(page != 1){
			toPage(page-1);
		}
	});
	$('#ar_btn_next').click(function(){
		
		$('#ar_tab_form_box table').each(function(){
			if($(this).css('display') != 'none' && $(this).attr('id') == 'ar_tab_main'){
				page = 1;
			}else if($(this).css('display') != 'none' && $(this).attr('id') == 'ar_tab_detail'){
				page = 2;
			}
		});
		if(page != max_page){
			toPage(page+1);
		}
		
	});

	//跳转指定页面
	function toPage(page){
		//alert(page);
		$('#ar_tab_form_box table').each(function(){
			if($(this).attr('id') != 'ar_tab_buttons'){
				$(this).css('display','none');
			}
		});

		if(parseInt(page) == 1){
			$('#ar_tab_main').fadeIn("slow");
			$('#ar_tab_main').css('display','');
		}else if(parseInt(page) == 2){
			$('#ar_tab_detail').fadeIn("slow");
			$('#ar_tab_detail').css('display','');
		}else{
			return false;
		}
		//首页和末页处理
		if(parseInt(page) == max_page){
			$('#ar_btn_next').attr('disabled',true);
		}else{
			$('#ar_btn_next').attr('disabled',false);
		}
		if(parseInt(page) == 1){
			$('#ar_btn_prev').attr('disabled',true);
		}else{
			$('#ar_btn_prev').attr('disabled',false);
		}

	}


	
});
	//表单提交验证
	function arValidate(){
		var flag = true;
		var msg = '';
		var data_tit = $("#ar_tab_form_box input[name='title']").val();
		if(!data_tit.length){
			msg += '文章标题不能为空！\n';
			flag = false;
		}

		if(data_tit.length != $.trim(data_tit).length){
			msg += '文章标题格式不正确！\n';
			flag = false;
		}
		if(!flag){
			alert(msg);
		}
		return flag;	
	}	