<?php
namespace Home\Model;
use Think\Model;
class AdminModel extends Model {
	protected $tableName = "admin";
    protected $_map = array(
		'name' => 'username', //把表单中的name映射到数据表的username字段
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
		
		$M = D("Admin");
		if ($M->where("`email`='" . $data['username'] . "'")->count() >= 1) {
			$info = $M->where("`email`='" . $data["username"] . "'")->find();
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

    public function findPwd() {
        $datas = $_POST;
        $M = D("Admin");
        if ($_SESSION['verify'] != md5($_POST['verify_code'])) {
            die(json_encode(array('status' => 0, 'info' => "验证码错误啦，再输入吧")));
        }
		//        $this->check_verify_code();
        if (trim($datas['pwd']) == '') {
            return array('status' => 0, 'info' => "密码不能为空");
        }
        if (trim($datas['pwd']) != trim($datas['pwd1'])) {
            return array('status' => 0, 'info' => "两次密码不一致");
        }
        $data['aid'] = $_SESSION['aid'];
        $data['pwd'] = md5(C("AUTH_CODE") . md5($datas['pwd']));
        $data['find_code'] = NULL;
        if ($M->save($data)) {
            return array('status' => 1, 'info' => "你的密码已经成功重置", 'url' => U('Access/index'));
        } else {
            return array('status' => 0, 'info' => "密码重置失败");
        }
    }

	// 根据用户名获取一条用户信息
	public function getOneUserByName($username){
		return $this->field("aid,username,email,nickname,sex,birthday,avatar,mobile_number,qq,personal_website,description,remark,last_login_time,last_login_ip,create_time,login_count,login_error_log")->where("username='$username' and is_del = 0 and status = 1")->find();
	}

	// 根据用户id获取一条信息
	public function getOneUserById($id){
		return $this->field("aid,username,email,nickname,sex,birthday,avatar,mobile_number,qq,personal_website,description,remark,last_login_time,last_login_ip,create_time,login_count,login_error_log")->where("aid=$id and is_del = 0 and status = 1")->find();
	}

	/*
    /**
     * 删除角色
     * @param type $roleid 角色ID
     * @return boolean
     
    public function delete_role($roleid) {
        if (!$roleid) {
            return false;
        }
        $status = $this->where(array("id" => $roleid))->delete();
        if ($status !== false) {
            //删除access中的授权信息
            D("Access")->where(array("role_id" => $roleid))->delete();
        }
        return $status;
    }

    //删除操作时删除缓存
    public function _after_delete($data, $options) {
        parent::_after_delete($data, $options);
    }
	*/

}
?>