<?php
/**
 * 扩展函数库
 * ============================================================================
 * 版权所有 2005-2080 Lessismore，并保留所有权利。
 * 网站地址: http://www.trydemo.net；
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和
 * 使用；不允许对程序代码以任何形式任何目的的再发布。
 * ============================================================================
 * $Author: lsq & Lessismore & D.Apache.Luo
 * $Id: UserActivity.class.php 2014-4-2 Lessismore $
*/

/**
 +------------------------------------------------------------------------------
 * Think扩展函数库 需要手动加载后调用或者放入项目函数库
 +------------------------------------------------------------------------------
 * @category   Think
 * @package  Common
 * @author   trydemo@126.com
 * @version  $Id$  2014-4-21
 +------------------------------------------------------------------------------
 */

//Add slashes to a string or array of strings. 2014-4-21
function td_slash( $value ) {
	if ( is_array( $value ) ) {
		foreach ( $value as $k => $v ) {
			if ( is_array( $v ) ) {
				$value[$k] = wp_slash( $v );
			} else {
				$value[$k] = addslashes( $v );
			}
		}
	} else {
		$value = addslashes( $value );
	}

	return $value;
}

/**
  +----------------------------------------------------------
 * 功能：检测一个字符串是否是邮件地址格式
  +----------------------------------------------------------
 * @param string $value    待检测字符串
  +----------------------------------------------------------
 * @return boolean  2014-4-21
  +----------------------------------------------------------
 */
function is_email($value) {
    return preg_match("/^[0-9a-zA-Z]+(?:[\_\.\-][a-z0-9\-]+)*@[a-zA-Z0-9]+(?:[-.][a-zA-Z0-9]+)*\.[a-zA-Z]+$/i", $value);
}

/**
  +----------------------------------------------------------
 * 功能：检测一个字符串是否是互联网地址格式
  +----------------------------------------------------------
 * @param string $value    待检测网址
  +----------------------------------------------------------
 * @return boolean  2014-4-21
  +----------------------------------------------------------
 */
function check_url($weburl){   
    return preg_match("/(http(s)*:\/\/)?[_a-zA-Z0-9\-]+(\.[_a-zA-Z0-9\-]+)+$/", $weburl);    
}

/**
  +----------------------------------------------------------
 * 功能：评论表情正则替换
  +----------------------------------------------------------
 * @param string $value    待检测评论
  +----------------------------------------------------------
 * @return str  2014-5-4
  +----------------------------------------------------------
 */
function convert_smilie($value){
	//cs001 :lol:  :twisted::dsfsdf:
	//123123 <img class="wp-smiley" alt=":roll:" src="http://127.0.0.1/test/wordpress/wp-content/themes/Loostrive/images/smilies/icon_rolleyes.gif"></img>
	//fwrites(WEB_ROOT . 'ajax.txt',$value);
	
	$Smilies = F("Smilies");
	if (false == $Smilies) {
		//循环遍历表情文件夹
		//fwrites(WEB_ROOT . 'ajax.txt','-------->convert_smilie开始工作！');
		$dir = WEB_ROOT.'/Public/Img/Common/smilies/';
		$smiley_arr = list_dir($dir);
		$smiley_arrs = array();
		if(is_array($smiley_arr) && count($smiley_arr)){
			foreach($smiley_arr AS $key=>$val){
				if($val){
					$s_arr = explode('.',$val);
					$s_arrs = explode('_',$s_arr[0]);
					$smiley_arrs[$key] = $s_arrs[1];
				}
			}
			F("Smilies", $smiley_arrs);
			if(count($smiley_arrs)){
				foreach($smiley_arrs AS $k=>$v){
					if($v){
						//开始匹配是否存在
						$p_num = preg_match("/\:".$v."\:/i",$value,$matches);
						if(preg_match("/\:".$v."\:/i",$value,$matches)){
							$pattern = "/\:(".$v.")\:/i";
							$value = preg_replace($pattern,"<img class='td-smiley' alt='\\1' src='".__ROOT__."/Public/Img/Common/smilies/icon_".strtolower('\\1').".gif' />",$value);
						}
					}
				}

				return $value;
			}else{
				return $value;
			}

		}else{
			F("Smilies", $smiley_arrs);
			return $value;
		}

		//$Smilies = $data;
	}else{
		$smiley_arrs = $Smilies;
		if(is_array($smiley_arrs) && count($smiley_arrs)){
			foreach($smiley_arrs AS $k=>$v){
				if($v){
					//开始匹配是否存在
					$p_num = preg_match("/\:".$v."\:/i",$value,$matches);
					if(preg_match("/\:".$v."\:/i",$value,$matches)){
						$pattern = "/\:(".$v.")\:/i";
						$value = preg_replace($pattern,"<img class='td-smiley' alt='\\1' src='".__ROOT__."/Public/Img/Common/smilies/icon_".strtolower('\\1').".gif' />",$value);
					}
				}
			}

			return $value;
		}else{
			return $value;
		}
	}
	/*
	//循环遍历表情文件夹
	//fwrites(WEB_ROOT . 'ajax.txt','-------->convert_smilie开始工作！');
	$dir = WEB_ROOT.'/Public/Img/Common/smilies/';
	$smiley_arr = list_dir($dir);
	$smiley_arrs = array();
	if(is_array($smiley_arr) && count($smiley_arr)){
		foreach($smiley_arr AS $key=>$val){
			if($val){
				$s_arr = explode('.',$val);
				$s_arrs = explode('_',$s_arr[0]);
				$smiley_arrs[$key] = $s_arrs[1];
			}
		}
		//fwrites(WEB_ROOT . 'ajax.txt',$smiley_arr);
		//fwrites(WEB_ROOT . 'ajax.txt','---------------->smiley_arrs');
		//fwrites(WEB_ROOT . 'ajax.txt',$smiley_arrs);

		if(count($smiley_arrs)){
			foreach($smiley_arrs AS $k=>$v){
				if($v){
					//开始匹配是否存在
					$p_num = preg_match("/\:".$v."\:/i",$value,$matches);
					if(preg_match("/\:".$v."\:/i",$value,$matches)){
						$pattern = "/\:(".$v.")\:/i";
						$value = preg_replace($pattern,"<img class='td-smiley' alt='\\1' src='".__ROOT__."/Public/Img/Common/smilies/icon_".strtolower('\\1').".gif' />",$value);
						//fwrites(WEB_ROOT . 'ajax.txt','--------------->正则匹配表情循环处理中。。。。。。');
						//fwrites(WEB_ROOT . 'ajax.txt',$value);
					}
				}
			}

			//$pattern = "/\:([\?\!a-zA-Z]{1,16})\:/i";
			//$result = preg_replace($pattern,"<img class='td-smiley' alt='\\1' //src='".__ROOT__."/Public/Img/Common/smilies/icon_".strtolower('\\1').".gif' />",$value);
			//fwrites(WEB_ROOT . 'ajax.txt','--------------->正则匹配表情成功结束！');
			//fwrites(WEB_ROOT . 'ajax.txt',$value);
			return $value;
		}else{
			return $value;
		}

	}else{
		return $value;
	}
	*/
 
	
}

/**
  +----------------------------------------------------------
 * 功能：给模版返回所有表情信息
  +----------------------------------------------------------
 * @param null  
  +----------------------------------------------------------
 * @return array()  2014-5-4
  +----------------------------------------------------------
 */
function get_smilies_arr(){
	$Smilies = F("Smilies");
	if (false == $Smilies) {
		//循环遍历表情文件夹
		//fwrites(WEB_ROOT . 'ajax.txt','-------->convert_smilie开始工作！');
		$dir = WEB_ROOT.'/Public/Img/Common/smilies/';
		$smiley_arr = list_dir($dir);
		$smiley_arrs = array();
		if(is_array($smiley_arr) && count($smiley_arr)){
			foreach($smiley_arr AS $key=>$val){
				if($val){
					$s_arr = explode('.',$val);
					$s_arrs = explode('_',$s_arr[0]);
					$smiley_arrs[$key] = strtolower($s_arrs[1]);
				}
			}
			F("Smilies", $smiley_arrs);
			$Smilies = $smiley_arrs;
		}else{
			F("Smilies", $smiley_arrs);
		}
	}
	
	return $Smilies;
}

/**
  +----------------------------------------------------------
 * 功能：获取置顶文件夹下的文件（不包含目录）
  +----------------------------------------------------------
 * @param string $dir    待检测目录
  +----------------------------------------------------------
 * @return str  2014-5-5
  +----------------------------------------------------------
 */
function list_dir($dir){
	$return_arr = array();
    if(is_dir($dir)){
        if ($dh = opendir($dir)){
            while (($file = readdir($dh)) !== false){
                if((is_dir($dir."/".$file)) && $file!="." && $file!=".."){
                    //list_dir($dir."/".$file."/");
                }else{
                    if($file!="." && $file!=".."){
						$return_arr[] = $file;
                    }
                }
            }
            closedir($dh);
		}
	}

	//fwrites(WEB_ROOT . 'ajax.txt',$return_arr);
	return $return_arr;
}

/**
  +----------------------------------------------------------
 * 功能：系统邮件发送函数
  +----------------------------------------------------------
 * @param string $to    接收邮件者邮箱
 * @param string $name  接收邮件者名称
 * @param string $subject 邮件主题
 * @param string $body    邮件内容
 * @param string $attachment 附件列表
  +----------------------------------------------------------
 * @return boolean
  +----------------------------------------------------------
 */
function send_mail($to, $name, $subject = '', $body = '', $attachment = null, $config = '') {
    $config = is_array($config) ? $config : C('SYSTEM_EMAIL');
    import('PHPMailer.phpmailer', VENDOR_PATH);         //从PHPMailer目录导class.phpmailer.php类文件
    $mail = new PHPMailer();                           //PHPMailer对象
    $mail->CharSet = 'UTF-8';                         //设定邮件编码，默认ISO-8859-1，如果发中文此项必须设置，否则乱码
    $mail->IsSMTP();                                   // 设定使用SMTP服务
//    $mail->IsHTML(true);
    $mail->SMTPDebug = 0;                             // 关闭SMTP调试功能 1 = errors and messages2 = messages only
    $mail->SMTPAuth = true;                           // 启用 SMTP 验证功能
    if ($config['smtp_port'] == 465)
        $mail->SMTPSecure = 'ssl';                    // 使用安全协议
    $mail->Host = $config['smtp_host'];                // SMTP 服务器
    $mail->Port = $config['smtp_port'];                // SMTP服务器的端口号
    $mail->Username = $config['smtp_user'];           // SMTP服务器用户名
    $mail->Password = $config['smtp_pass'];           // SMTP服务器密码
    $mail->SetFrom($config['from_email'], $config['from_name']);
    $replyEmail = $config['reply_email'] ? $config['reply_email'] : $config['reply_email'];
    $replyName = $config['reply_name'] ? $config['reply_name'] : $config['reply_name'];
    $mail->AddReplyTo($replyEmail, $replyName);
    $mail->Subject = $subject;
    $mail->MsgHTML($body);
    $mail->AddAddress($to, $name);
    if (is_array($attachment)) { // 添加附件
        foreach ($attachment as $file) {
            if (is_array($file)) {
                is_file($file['path']) && $mail->AddAttachment($file['path'], $file['name']);
            } else {
                is_file($file) && $mail->AddAttachment($file);
            }
        }
    } else {
        is_file($attachment) && $mail->AddAttachment($attachment);
    }
    return $mail->Send() ? true : $mail->ErrorInfo;
}

/**
  +----------------------------------------------------------
 * 功能：发送纯文本或者HTML格式的邮件
  +----------------------------------------------------------
 * @param string $to    接收邮件邮箱名
  +----------------------------------------------------------
 * @param string $title    邮件标题
  +----------------------------------------------------------
 * @param string $content    邮件内容
  +----------------------------------------------------------
 * @return true/false  2015/12/3
  +----------------------------------------------------------
 */
function sendMail($to,$title,$content){
    // 载入邮件发送类库
    Vendor('PHPMailer.PHPMailerAutoload');
 
    $mail = new PHPMailer;
	//$mail->SMTPDebug = 3;
    $mail->isSMTP();        //设置PHPMailer使用SMTP服务器发送Email
    $mail->Host = C('MAIL_HOST');   //指定SMTP服务器 可以是smtp.126.com, gmail, qq等服务器 自行查询
    $mail->SMTPAuth = C('MAIL_SMTPAUTH');
    $mail->CharSet= C('MAIL_CHARSET');     //设置字符集 防止乱码
    $mail->Username = C('MAIL_USERNAME');  //发送人的邮箱账户
    $mail->Password = C('MAIL_PASSWORD');   //发送人的邮箱密码
    $mail->Port = C('MAIL_PORT');   //SMTP服务器端口
 
    $mail->From = C('MAIL_FROM');            //发件人邮箱地址
    $mail->FromName = C('MAIL_FROMNAME');                //发件人名称
    $mail->addAddress($to);      // 收件人邮箱地址 此处可以发送多个
	$mail->addReplyTo(C('MAIL_REPLYTO'), $title.' 标题的邮件回复');  //回复地址（可填可不填）
	//$mail->addCC('cc@example.com');		//添加抄送
	//$mail->addBCC('bcc@example.com');     //添加秘密抄送

	//$mail->addAttachment('/var/tmp/file.tar.gz');         // 添加附件
	//$mail->addAttachment('/tmp/image.jpg', 'new.jpg');    // 添加图片附件
    $mail->WordWrap = 50;                                 // 换行字符数
    $mail->isHTML(C('MAIL_ISHTML'));                                  // 设置邮件格式为HTML

	$mail->Subject = $title;
	$mail->Body = $content;
	$mail->AltBody = "这是一个纯文本的且非营利的HTML电子邮件客户端"; //邮件正文不支持HTML的备用显示（可填可不填）

    if(!$mail->send()) {
        //echo '邮件发送失败.';
        //echo '错误信息: ' . $mail->ErrorInfo;
		return false;
    } else {
        // echo '邮件发送成功';
	    return true;
    }
}

/**
  +----------------------------------------------------------
 * 功能：剔除危险的字符信息
  +----------------------------------------------------------
 * @param string $val
  +----------------------------------------------------------
 * @return string 返回处理后的字符串
  +----------------------------------------------------------
 */
function remove_xss($val) {
    // remove all non-printable characters. CR(0a) and LF(0b) and TAB(9) are allowed
    // this prevents some character re-spacing such as <java\0script>
    // note that you have to handle splits with \n, \r, and \t later since they *are* allowed in some inputs
    $val = preg_replace('/([\x00-\x08,\x0b-\x0c,\x0e-\x19])/', '', $val);

    // straight replacements, the user should never need these since they're normal characters
    // this prevents like <IMG SRC=@avascript:alert('XSS')>
    $search = 'abcdefghijklmnopqrstuvwxyz';
    $search .= 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $search .= '1234567890!@#$%^&*()';
    $search .= '~`";:?+/={}[]-_|\'\\';
    for ($i = 0; $i < strlen($search); $i++) {
        // ;? matches the ;, which is optional
        // 0{0,7} matches any padded zeros, which are optional and go up to 8 chars
        // @ @ search for the hex values
        $val = preg_replace('/(&#[xX]0{0,8}' . dechex(ord($search[$i])) . ';?)/i', $search[$i], $val); // with a ;
        // @ @ 0{0,7} matches '0' zero to seven times
        $val = preg_replace('/(&#0{0,8}' . ord($search[$i]) . ';?)/', $search[$i], $val); // with a ;
    }

    // now the only remaining whitespace attacks are \t, \n, and \r
    $ra1 = array('javascript', 'vbscript', 'expression', 'applet', 'meta', 'xml', 'blink', 'link', 'style', 'script', 'embed', 'object', 'iframe', 'frame', 'frameset', 'ilayer', 'layer', 'bgsound', 'title', 'base');
    $ra2 = array('onabort', 'onactivate', 'onafterprint', 'onafterupdate', 'onbeforeactivate', 'onbeforecopy', 'onbeforecut', 'onbeforedeactivate', 'onbeforeeditfocus', 'onbeforepaste', 'onbeforeprint', 'onbeforeunload', 'onbeforeupdate', 'onblur', 'onbounce', 'oncellchange', 'onchange', 'onclick', 'oncontextmenu', 'oncontrolselect', 'oncopy', 'oncut', 'ondataavailable', 'ondatasetchanged', 'ondatasetcomplete', 'ondblclick', 'ondeactivate', 'ondrag', 'ondragend', 'ondragenter', 'ondragleave', 'ondragover', 'ondragstart', 'ondrop', 'onerror', 'onerrorupdate', 'onfilterchange', 'onfinish', 'onfocus', 'onfocusin', 'onfocusout', 'onhelp', 'onkeydown', 'onkeypress', 'onkeyup', 'onlayoutcomplete', 'onload', 'onlosecapture', 'onmousedown', 'onmouseenter', 'onmouseleave', 'onmousemove', 'onmouseout', 'onmouseover', 'onmouseup', 'onmousewheel', 'onmove', 'onmoveend', 'onmovestart', 'onpaste', 'onpropertychange', 'onreadystatechange', 'onreset', 'onresize', 'onresizeend', 'onresizestart', 'onrowenter', 'onrowexit', 'onrowsdelete', 'onrowsinserted', 'onscroll', 'onselect', 'onselectionchange', 'onselectstart', 'onstart', 'onstop', 'onsubmit', 'onunload');
    $ra = array_merge($ra1, $ra2);

    $found = true; // keep replacing as long as the previous round replaced something
    while ($found == true) {
        $val_before = $val;
        for ($i = 0; $i < sizeof($ra); $i++) {
            $pattern = '/';
            for ($j = 0; $j < strlen($ra[$i]); $j++) {
                if ($j > 0) {
                    $pattern .= '(';
                    $pattern .= '(&#[xX]0{0,8}([9ab]);)';
                    $pattern .= '|';
                    $pattern .= '|(&#0{0,8}([9|10|13]);)';
                    $pattern .= ')*';
                }
                $pattern .= $ra[$i][$j];
            }
            $pattern .= '/i';
            $replacement = substr($ra[$i], 0, 2) . '<x>' . substr($ra[$i], 2); // add in <> to nerf the tag
            $val = preg_replace($pattern, $replacement, $val); // filter out the hex tags
            if ($val_before == $val) {
                // no replacements were made, so exit the loop
                $found = false;
            }
        }
    }
    return $val;
}

/**
  +----------------------------------------------------------
 * 功能：计算文件大小
  +----------------------------------------------------------
 * @param int $bytes
  +----------------------------------------------------------
 * @return string 转换后的字符串
  +----------------------------------------------------------
 */
function byteFormat($bytes) {
    $sizetext = array(" B", " KB", " MB", " GB", " TB", " PB", " EB", " ZB", " YB");
    return round($bytes / pow(1024, ($i = floor(log($bytes, 1024)))), 2) . $sizetext[$i];
}

function checkCharset($string, $charset = "UTF-8") {
    if ($string == '')
        return;
    $check = preg_match('%^(?:
                                [\x09\x0A\x0D\x20-\x7E] # ASCII
                                | [\xC2-\xDF][\x80-\xBF] # non-overlong 2-byte
                                | \xE0[\xA0-\xBF][\x80-\xBF] # excluding overlongs
                                | [\xE1-\xEC\xEE\xEF][\x80-\xBF]{2} # straight 3-byte
                                | \xED[\x80-\x9F][\x80-\xBF] # excluding surrogates
                                | \xF0[\x90-\xBF][\x80-\xBF]{2} # planes 1-3
                                | [\xF1-\xF3][\x80-\xBF]{3} # planes 4-15
                                | \xF4[\x80-\x8F][\x80-\xBF]{2} # plane 16
                                )*$%xs', $string);

    return $charset == "UTF-8" ? ($check == 1 ? $string : iconv('gb2312', 'utf-8', $string)) : ($check == 0 ? $string : iconv('utf-8', 'gb2312', $string));
}



// 自动转换字符集 支持数组转换
function auto_charset($fContents, $from='gbk', $to='utf-8') {
    $from = strtoupper($from) == 'UTF8' ? 'utf-8' : $from;
    $to = strtoupper($to) == 'UTF8' ? 'utf-8' : $to;
    if (strtoupper($from) === strtoupper($to) || empty($fContents) || (is_scalar($fContents) && !is_string($fContents))) {
        //如果编码相同或者非字符串标量则不转换
        return $fContents;
    }
    if (is_string($fContents)) {
        if (function_exists('mb_convert_encoding')) {
            return mb_convert_encoding($fContents, $to, $from);
        } elseif (function_exists('iconv')) {
            return iconv($from, $to, $fContents);
        } else {
            return $fContents;
        }
    } elseif (is_array($fContents)) {
        foreach ($fContents as $key => $val) {
            $_key = auto_charset($key, $from, $to);
            $fContents[$_key] = auto_charset($val, $from, $to);
            if ($key != $_key)
                unset($fContents[$key]);
        }
        return $fContents;
    }
    else {
        return $fContents;
    }
}


?>