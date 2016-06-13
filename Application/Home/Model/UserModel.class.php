<?php
namespace Home\Model;
use Think\Model;
class UserModel extends Model {
	//实际表名
	protected $trueTableName='td_user';
	
	protected $_auto=array(
		array('last_login_time','getCTime','1','callback'),
		array('last_login_ip','getCIp','1','callback')
	);
	//自动验证
	protected $_validate=array(	
		array('username','require','请填写用户名'),
		array('username','2,30','用户名长度不正确!',2,'length')
	);

	//登陆认证
	public function auth($data) {
		//fwrites(APP_PATH . 'Home/ajax.txt','@url(UserModel-auth)---前端用户名密码验证开始！');

		//暂未使用验证码暂时停用-----后期可以使用后台接口动态开启验证码验证
		/*
		if ($_SESSION['verify'] != md5($_POST['verify_code'])) {
			die(json_encode(array('status' => 0, 'info' => "验证码错误啦，再输入吧")));
		}
		*/
		
		$M = D("User");
		if ($M->where("`username`='" . $data['username'] . "'")->count() >= 1) {
			$info = $M->where("`username`='" . $data["username"] . "'")->find();
			if ($info['status'] == 0) {
				return array('status' => 0, 'info' => "你的账号被禁用，有疑问联系管理员吧");
			}
			

			//fwrites(APP_PATH . 'Home/ajax.txt','-----数据库密码如下');
			//fwrites(APP_PATH . 'Home/ajax.txt',$info['pwd']);
			//fwrites(APP_PATH . 'Home/ajax.txt','-----post密码测试');
			//fwrites(APP_PATH . 'Home/ajax.txt',$data['pwd']);
			//fwrites(APP_PATH . 'Home/ajax.txt','-----post密码加密测试');
			//fwrites(APP_PATH . 'Home/ajax.txt',encrypt($data['pwd']));
			//fwrites(APP_PATH . 'Home/ajax.txt',"@param(auth)---info");
			//fwrites(APP_PATH . 'Home/ajax.txt',$info);
			if ($info['pwd'] == encrypt($data['pwd'])) {
				//fwrites(APP_PATH . 'Home/ajax.txt','帐号密码正确！登录成功！');
				$loginMarked = C("TOKEN");
				//fwrites(APP_PATH . 'Home/ajax.txt',$loginMarked);
				$loginMarked = md5($loginMarked['home_marked']);
				//fwrites(APP_PATH . 'Home/ajax.txt',"@param(auth)---loginMarked:".$loginMarked);
				$shell = $info['uid'] . md5($info['pwd'] . C('AUTH_CODE'));
				$_SESSION[$loginMarked] = "$shell";
				$shell.= "_" . time();
				//fwrites(APP_PATH . 'Home/ajax.txt','@url(UserModel-auth)---shell:');
				//fwrites(APP_PATH . 'Home/ajax.txt',$shell);
				setcookie($loginMarked, "$shell", 0, "/");
				$_SESSION['user_info'] = $info;
				//fwrites(APP_PATH . 'Home/ajax.txt','@url(UserModel-auth)---COOKIE:');
				//fwrites(APP_PATH . 'Home/ajax.txt',$_COOKIE);
				//fwrites(APP_PATH . 'Home/ajax.txt','@url(UserModel-auth)---_SESSION:');
				//fwrites(APP_PATH . 'Home/ajax.txt',$_SESSION);
				return array('status' => 1, 'info' => "登录成功!", 'url' => U("Home/index"));
			} else {
				return array('status' => 0, 'info' => "账号或密码错误!");
			}
		} else {
			return array('status' => 0, 'info' => "不存在帐号为：" . $data["username"] . '的账号！');
		}
	}

	// 获取创建时间
	public function getCTime(){
		return date('Y-m-d H:i:s');
	}

	// 获取客户端IP
	public function getCIp(){
		//Load('extend');
		return get_client_ip();
	}	

	// 根据用户名获取一条用户信息
	public function getOneUserByName($username){
		return $this->where("username='$username'")->find();
	}

	// 根据用户id获取一条信息
	public function getOneUserById($id){
		return $this->field("aid,nickname,email,username,sex,birthday,avatar,mobile_number,qq,personal_website,remark,last_login_time,last_login_ip,create_time,login_count,login_error_log")->where("id=$id and is_del = 0 and status = 1")->find();
	}
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
	}
?>