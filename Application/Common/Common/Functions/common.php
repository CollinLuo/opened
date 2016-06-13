<?php
	class Common{
	/**
     * 前后台公用函数集、非框架系统函数
     *
     * 用法：
     * @code php
     * $arr = array('', 'test', '', '');
     * Common::sendMail($arr);
	 *
     * @author:lsq date:2014-9-3	
     */


	//                            _ooOoo_
	//                           o8888888o
	//                           88" . "88
	//                           (| -_- |)
	//                            O\ = /O
	//                        ____/`---'\____
	//                      .   ' \\| |// `.
	//                       / \\||| : |||// \
	//                     / _||||| -:- |||||- \
	//                       | | \\\ - /// | |
	//                     | \_| ''\---/'' | |
	//                      \ .-\__ `-` ___/-. /
	//                   ___`. .' /--.--\ `. . __
	//                ."" '< `.___\_<|>_/___.' >'"".
	//               | | : `- \`.;`\ _ /`;.`/ - ` : | |
	//                 \ \ `-. \_ __\ /__ _/ .-` / /
	//         ======`-.____`-.___\_____/___.-`____.-'======
	//                            `=---='
	//
	//         .............................................
	//                  佛祖保佑             永无BUG

	/*******邮件发送函数*******/
	function SendMail($address,$title,$message){
		import('ORG.Net.PHPMailer');
		$mail=new PHPMailer();
		// 设置PHPMailer使用SMTP服务器发送Email
		$mail->IsSMTP();
		// 设置邮件的字符编码，若不指定，则为'UTF-8'
		$mail->CharSet='UTF-8';
		// 添加收件人地址，可以多次使用来添加多个收件人
		$mail->AddAddress($address);
		// 设置邮件正文
		$mail->Body=$message;
		// 设置邮件头的From字段。
		$mail->From=C('MAIL_ADDRESS');
		// 设置发件人名字
		$mail->FromName='zyimm';
		// 设置邮件标题
		$mail->Subject=$title;
		// 设置SMTP服务器。
		$mail->Host=C('MAIL_SMTP');
		// 设置为“需要验证”
		$mail->SMTPAuth=true;
		// 设置用户名和密码。
		$mail->Username=C('MAIL_LOGINNAME');
		$mail->Password=C('MAIL_PASSWORD');
		// 发送邮件。
		return($mail->Send());
	}
	
	/*****密码生成&2014-9-3*****/
	function get_password( $length = 8 ){
		$str = substr(md5(time()), 0, $length);
		return $str;
	}

	}
?>