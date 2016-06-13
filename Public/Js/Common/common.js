/**************前后台公用js效果&2014-9-4**************/

//登录注册界面屏幕抖动效果
addLoadEvent = function(func){
	if(typeof jQuery!="undefined")jQuery(document).ready(func);
};
function s(id,pos){g(id).left=pos+'px';}
function g(id){return document.getElementById(id).style;}
function shake(id,a,d){
	c=a.shift();s(id,c);if(a.length>0){setTimeout(function(){shake(id,a,d);},d);}else{try{g(id).position='static';wp_attempt_focus();}catch(e){}}
}