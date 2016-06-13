//TryDemo个人博客前台专用jquery
jQuery(document).ready(function() {
	//Rss订阅
	jQuery('.icon1,.icon2,.icon3,.icon4,.icon5,.icon6').wrapInner('<span class="hover"></span>').css('textIndent', '0').each(function() {
		jQuery('span.hover').css('opacity', 0).hover(function() {
			jQuery(this).stop().fadeTo(350, 1)
		},
		function() {
			jQuery(this).stop().fadeTo(350, 0)
		})
	})
	//顶部快捷导航栏
	$('#nav-menu .menu > li').hover(function () {
		$(this).find('.children').animate({
		  opacity: 'show',
		  height: 'show'
		}, 300);
		$(this).find('.xialaguang').addClass('navhover');
	}, function () {
		$('.children').stop(true, true).slideUp(100);
		$('.xialaguang').removeClass('navhover');
	}).slice(-3, -1).find('.children').addClass('sleft');

});


