<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>友情提示</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<meta http-equiv='Refresh' content='<?php echo ($waitSecond); ?>;URL=<?php echo ($jumpUrl); ?>'>
</head>
<body>
<table border="0" align="center" cellpadding="5" cellspacing="1" style="font-size:14px;color:#333333;margin-top:100px;background:#70afd3">
<tr style="background:url(/opened/Public/Img/Common/bg/top_bg.gif);color:#FFFFFF">
    <th><?php echo ($msgTitle); ?></th>
</tr>
<tr><td height="100" style="font-size:12px; background:#FFFFFF">
    <div style="font-size:14px;font-weight:bold;margin:10px;"><?php if($status == 1): echo ($message); else: echo ($error); endif; ?></div>
    <div style="margin:10px;">系统将在&nbsp;<span id="countDownSec" style="color:blue;font-weight:bold"><?php echo ($waitSecond); ?></span>&nbsp;秒后自动跳转,如果不想等待,直接 <a href="<?php echo ($jumpUrl); ?>" style="color:#069;">点击这里</a></div>
</td></tr>
</table>
</body>
</html>

<script language="javascript">
	//自动计时跳转
	var set = setInterval("numbers_p()",1000);
	function numbers_p(numbers){
		var numbers = parseInt(document.getElementById("countDownSec").innerHTML);
		if(numbers > 1){
			numbers--;
			document.getElementById("countDownSec").innerHTML = numbers;
		}else{
			clearInterval(set);
		}
	}
</script>