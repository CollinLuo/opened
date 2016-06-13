<?php
/**
 * 首页
 * ============================================================================
 * 版权所有 2005-2080 Lessismore，并保留所有权利。
 * 网站地址: http://www.trydemo.net；
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和
 * 使用；不允许对程序代码以任何形式任何目的的再发布。
 * ============================================================================
 * $Author: lsq & Lessismore & D.Apache.Luo $
 * $Intro: UserActivity.class.php 2014-4-2 Lessismore $
*/
namespace Home\Controller;
use Think\Controller;
use Think\Model;

class UserController extends CommonController {
	protected  $User, $RoleUser, $Company, $CompanyUser, $Project;
	// 前台用户角色ID
	protected $t_role_id = 7;
	/**
      +----------------------------------------------------------
     * 初始化
     * 如果 继承本类的类自身也需要初始化那么需要在使用本继承类的类里使用parent::_initialize();
      +----------------------------------------------------------
     */
	public function _initialize() {
		parent::_initialize();
		$this->User = D("Admin");
		$this->RoleUser = D("RoleUser");
		$this->Company = D('Admin/Company');
		$this->CompanyUser = D('Admin/CompanyUser');
		$this->Project = D("Admin/Company");
	}
	
	/**
      +----------------------------------------------------------
     * 个人中心界面
      +----------------------------------------------------------
     */
	public function index(){
		/*用户中心暂不开放
		$user_id = $_SESSION['user_id'];
		$user = new UserModel();
		$oneUser = $user->getOneUserById($user_id);
		
		if(!is_array($oneUser)){
			$this->redirect('/User/login');
		}
		
		$this->header();
		$this->footer();
		
		//处理用户性别的显示
		if($oneUser['sex']=='0'){
			$oneUser['sex']='<input type="radio" checked="checked" name="sex" value="0"/>&nbsp;男　&nbsp;<input type="radio" name="sex" value="1"/>&nbsp;女';
		}else{
			$oneUser['sex']='<input type="radio" name="sex" value="0"/>&nbsp;男　&nbsp;<input checked="checked" type="radio" name="sex" value="1"/>&nbsp;女';
		}
		
		$this->assign('oneUser',$oneUser);
		$this->display('User:index');
		*/
		$this->error('此页面未找到!');
		exit;
	}
	
	/**
      +----------------------------------------------------------
     * 会员信息修改
      +----------------------------------------------------------
     */
	public function modify(){
		/*暂不开放
		$this->checkLogin();
		
		$user_id=$_SESSION['user_id'];
		$member=new UserModel();
		$user_name=$member->where("id=$user_id")->getField('username');
		if($user_name!=$_POST['username']){
			$this->error('用户名禁止修改!');
			exit;
		}

		//会员修改处理
		if(!$member->create()){
			$this->error($member->getError());
		}
		
		if(empty($member->password)){
			$member->password=$oneUser['password'];
		}else{
			$member->password=md5($oneUser->password);
		}
					
		if($member->where("id=$user_id")->save()){
			$this->success('信息修改成功!');
		}else{
			$this->error('信息修改失败,请联系管理员!');
		}
		*/
		$this->error('此页面未找到!');
		exit;
	}
	
	/**
      +----------------------------------------------------------
     * 注册界面
      +----------------------------------------------------------
     */
	public function registerAct(){
		if(IS_POST){
			//p("@param---registerAct:注册开始！");
			//p(sendMail('qiangshiluo@126.com','这仅仅是一个测试11111！','这仅仅是一个测试123123231312312313123！'));
			//exit();
			fwrites(APP_PATH . 'Home/ajax.txt',$_POST);
			if($this->User->autoCheckToken($_POST)){
				Vendor('extend');
				//注册用户名默认值
				$r_username = '';
				//注册密码默认值
				$r_pwd = '';
				//注册邮箱默认值
				$r_email = '';
				if(isset($_POST['user_name']) && !empty($_POST['user_name'])){
					$username = $_POST['user_name'];
					//删除所有单引号
					if(stristr($username,'\'')){
						$username = str_replace('\'','',$username);
					}
					$r_username = htmlspecialchars(trim($username));
					//判断用户名是否重复
					$result = $this->User->where("username='".$r_username."'")->count();
					if($result){
						$this->error("用户名 $r_username 已经存在！",U('User/registerAct'));
					}
				}else{
					$this->error("用户名不能为空！",U('User/registerAct'));
				}
				
				$r_pwd = trim($_POST['user_pwd']);
				if(isset($_POST['user_pwd']) && strlen($r_pwd) > 5){
					if($_POST['user_pwd'] != $_POST['user_repwd']){
						$this->error("两次密码不一致！",U('User/registerAct'));
					}
				}else{
					$this->error("密码长度不能小于6位数！",U('User/registerAct'));
				}
				
				
				if(isset($_POST['user_email'])){
					$email = $_POST['user_email'];
					//删除所有单引号
					if(stristr($email,'\'')){
						$email = str_replace('\'','',$email);
					}
					$r_email = htmlspecialchars(trim($email));
					if(is_email($r_email)){
						$result = $this->User->where("email='".$r_email."'")->count();
						if($result){
							$this->error("邮箱 $r_email 已经存在！",U('User/registerAct'));
						}
					}else{
						$this->error("邮箱格式不正确！",U('User/registerAct'));
					}
				}else{
					$this->error("注册邮箱不能为空！",U('User/registerAct'));
				}
				
				// 检查验证码 
				if(isset($_POST['verify_code']) && strlen(trim($_POST['verify_code'])) == 4){
					$auth_code_mod = D(AuthCode);
					$tmp_code = $auth_code_mod->where("email = '".$r_email."'")->order("create_time desc")->limit(1)->select();
					//p($tmp_code);
					//fwrites(APP_PATH . 'Home/ajax.txt',$tmp_code);
					if(count($tmp_code) && $tmp_code[0]['content'] == trim($_POST['verify_code'])){
						if(time()-$tmp_code[0]['create_time'] > $tmp_code[0]['failure_time']){
							$this->error("验证码已过期！",U('User/registerAct'));
						}
					}else{
						$this->error("验证码错误！",U('User/registerAct'));
					}
				}else{
					$this->error("请输入4位验证码！",U('User/registerAct'));
				}

				$new_pass = $r_pwd;
				$mail_title = "Opened-注册成功";
				$mail_content = "<b>$r_username</b>,您好：<br />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;欢迎您注册Opened,请妥善保管您的登录信息：<br />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;用户名：$r_username &nbsp;&nbsp;&nbsp;邮箱：$r_email &nbsp;&nbsp;&nbsp;密码：$new_pass <br />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href='http://workshop.zhiliaoshu.com.cn".U('User/loginAct/')."'>登录Opened</a>&nbsp;&nbsp;&nbsp;<a href='http://workshop.zhiliaoshu.com.cn".U('Home/index')."'>返回Opened</a>";
				//此处可以做邮件备份

				$data = array();
				$data['username'] = $r_username;
				$data['nickname'] = $r_username;
				$data['email'] = $r_email;
				$data['pwd'] = encrypt($new_pass);
				$data['create_time'] = time();
				$data['update_time'] = $data['create_time'];
				$data['avatar'] = 'no_face.png';
				$data['__hash__'] = $_POST['__hash__'];
				$new_uid = $this->User->data($data)->add();
				if($new_uid){
					// 注册成功，开始执行绑定前台用户角色（暂定绑死后期可以做维护表）
					if($this->RoleUser->add(array("role_id"=>$this->t_role_id,"user_id"=>$new_uid))){
						if(sendMail($r_email,$mail_title,$mail_content)){
							$this->success("邮件发送成功！请查看后登录...",U('User/loginAct'));
						}else{
							$this->error("邮件服务器故障啦！博主赶紧帮您把新密码奉上：".$new_pass,U('User/loginAct'),5);
						}
					}else{
						$mail_title = "Opened-注册异常";
						$mail_content = "<b>$r_username</b>,您好：<br />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;欢迎您注册Opened,由于网络原因绑定登录权限出错，但您注册的帐号已经录入成功，暂时无法正常登录网站，请保管好您的相关注册资料并联系客服处理：<br />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;用户名：$r_username &nbsp;&nbsp;&nbsp;邮箱：$r_email &nbsp;&nbsp;&nbsp;密码：$new_pass <br />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href='http://workshop.zhiliaoshu.com.cn".U('User/loginAct/')."'>登录Opened</a>&nbsp;&nbsp;&nbsp;<a href='http://workshop.zhiliaoshu.com.cn".U('Index/index')."'>返回Opened</a>";
						
						if(sendMail($r_email,$mail_title,$mail_content)){
							$this->success("邮件发送成功！请查看后登录...",U('User/loginAct'));
						}else{
							$this->error("邮件服务器故障啦！博主赶紧帮您把新密码奉上：".$new_pass,U('User/loginAct'),5);
						}
						//$this->success("警告:用户新增成功,前台角色绑定失败！", U('User/index'));
						// 此处抛出异常并记录异常日志！
					}
					
				}else{
					$this->error("注册失败！请刷新重试！",U('User/registerAct'));
				}
			}else{
				$this->error("令牌验证失败！",U('User/registerAct'));
			}
		} else {
            if (isset($_COOKIE[$this->loginMarked])) {
                $this->redirect("Index/index");
            }
			$this->page_header();
			$this->assign('current_page','用户注册');
            $this->display('User:user_register');
        }
	}

	/**
      +----------------------------------------------------------
     * 发送邮件验证码
      +----------------------------------------------------------
	 * @param email 邮箱
      +----------------------------------------------------------
     */
	 public function ajax_send_verifycode(){
		$dataResult = array();
		$dataResult['flag'] = 1; // 默认为1表示无任何错误
		$dataResult['msg'] = '验证码发送成功！'; // ajax提示信息
		$dataResult['data'] = ''; // 返回数据、修改成功则返回、修改后的数据
		//fwrites(APP_PATH . 'Home/ajax.txt',ACTION_NAME." start!");
		//fwrites(APP_PATH . 'Home/ajax.txt',$_POST);
		if (IS_AJAX) {
			Vendor('extend');
			if(isset($_POST['email']) && !empty($_POST['email'])){
				$email = $_POST['email'];
				//删除所有单引号
				if(stristr($email,'\'')){
					$email = str_replace('\'','',$email);
				}
				$email = htmlspecialchars(trim($email));
				//fwrites(APP_PATH . 'Home/ajax.txt',$email);
				$pattern = '/^([a-zA-Z0-9]+[\._-]?[a-zA-Z0-9]+)+@[a-zA-Z0-9]+(\.[a-zA-Z0-9])+/i';  
				if(preg_match($pattern,$email)){
					$result = $this->User->where("email='".$email."'")->count();
					//fwrites(APP_PATH . 'Home/ajax.txt',$result);
					if(!$result){
						//发送邮件
						$randStr = str_shuffle('ABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890');
						$new_code = strtolower(substr($randStr,0,4));
						$mail_title = "Opened平台-注册";
						$mail_content = "尊敬的Opened新用户,您好：<br />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;欢迎您注册Opened平台,注册验证码为：<br />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;验证码：$new_code <br />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href='http://www.trydemo.net".U('User/loginAct')."'>登录Opened</a>&nbsp;&nbsp;&nbsp;<a href='http://www.trydemo.net".U('Index/index')."'>返回首页</a>";
						
						$auth_code_mod = D("AuthCode");
						$data = array();
						$data['id'] = '';
						$data['type'] = 1;
						$data['content'] = $new_code;
						$data['mobile_number'] = '0';
						$data['email'] = $email;
						$data['qq'] = 0;
						$data['ext']  = '0';
						$data['timer'] = 0;
						$data['code_charset'] = 'utf8';
						$data['interface_url'] = '';
						$data['receipt_status'] = '';
						$data['create_time'] = time();
						$data['failure_time'] = 10*60;
						$data['is_del'] = 0;
						$data['user_id'] = 0;
						$result = $auth_code_mod->add($data);
						//fwrites(APP_PATH . 'Home/ajax.txt',$result);
						if($result){
							if(sendMail($email,$mail_title,$mail_content)){
								//$this->success("邮件发送成功！请查看后登录...",U('User/loginAct'));
								$dataResult['flag'] = 1; //默认为1表示无任何错误
								$dataResult['msg'] = "验证码发送成功！请登录邮箱查看！"; //ajax提示信息
								$dataResult['data'] = '';
							}else{
								//fwrites(APP_PATH . 'Home/ajax.txt','邮件发送失败了！');
								//$this->error("邮件服务器故障啦！博主赶紧帮您把新密码奉上：".$new_pass,U('User/loginAct'),5);
								$dataResult['flag'] = 1; //默认为1表示无任何错误
								$dataResult['msg'] = "邮件服务器故障啦！博主赶紧帮您把新密码奉上：".$new_pass; //ajax提示信息
								$dataResult['data'] = '';
							}

						}else{
							$dataResult['flag'] = 0; //默认为1表示无任何错误
							$dataResult['msg'] = '服务器故障啦！请刷新重试！'; //ajax提示信息
							$dataResult['data'] = '';
						}
					}else{
						$dataResult['flag'] = 0; //默认为1表示无任何错误
						$dataResult['msg'] = '该邮箱已经被注册！'; //ajax提示信息
						$dataResult['data'] = '';
					}
				}else{
					$dataResult['flag'] = 0; //默认为1表示无任何错误
					$dataResult['msg'] = '邮箱格式不正确！'; //ajax提示信息
					$dataResult['data'] = '';
				}
			}else{
				$dataResult['flag'] = 0; //默认为1表示无任何错误
				$dataResult['msg'] = '名字不能为空！'; //ajax提示信息
				$dataResult['data'] = '';
			}
		}else{
			$dataResult['flag'] = 0; // 默认为1表示无任何错误
			$dataResult['msg'] = '提交失败！请稍后重新提交！'; // ajax提示信息
			$dataResult['data'] = 'err_server';
		}
		$this->ajaxReturn($dataResult,'JSON');
	}
	
	/**
      +----------------------------------------------------------
     * 登录界面
      +----------------------------------------------------------
     */
	public function loginAct(){
		if(IS_POST){
			//p("@param---registerAct:登录开始！");
			//p($_POST);
			if($this->User->autoCheckToken($_POST)){
				//p($_POST);
				//登录帐号默认值
				$l_email = '';
				//登录密码默认值
				$l_pwd = '';
				if(isset($_POST['user_email']) && !empty($_POST['user_email'])){
					$user_email = $_POST['user_email'];
					//删除所有单引号
					if(stristr($user_email,'\'')){
						$user_email = str_replace('\'','',$user_email);
					}
					$l_email = htmlspecialchars(trim($user_email));
				}else{
					$this->error("登录邮箱不能为空！",U('User/loginAct'));
				}
				if(isset($_POST['pwd']) && !empty($_POST['pwd'])){
					$pwd = $_POST['pwd'];
					//删除所有单引号
					if(stristr($pwd,'\'')){
						$pwd = str_replace('\'','',$pwd);
					}
					$l_pwd = htmlspecialchars(trim($pwd));
				}else{
					$this->error("密码不能为空！",U('User/loginAct'));
				}
				
				// 检查验证码  
				$verify = I('param.verify_code',''); 
				if(!check_verify($verify)){  
					$this->error("亲，验证码输错了哦！",U('User/loginAct'));  
				} 
				
				$data = array();
				$data['username'] = $l_email;
				$data['pwd'] = $l_pwd;
				$data['__hash__'] = $_POST['__hash__'];
				$returnLoginInfo = $this->User->auth($data);
				//生成认证条件
				if ($returnLoginInfo['status'] == 1) {
					$this->redirect('User/uCenter');
				}else{
					//p($returnLoginInfo);
					$this->error('登录失败！请刷新重试！',U('User/loginAct'));
				}
			}else{
				$this->error('令牌验证失败！',U('User/loginAct'));
			}
			
		} else {
            if (isset($_COOKIE[$this->loginMarked])) {
                $this->redirect("Index/index");
            }
			$this->page_header();
			$this->assign('current_page','用户登录');
			$this->display('User:user_login');
        }
	}
	
	/**
      +----------------------------------------------------------
     * 找回密码
      +----------------------------------------------------------
	 * @param username 用户名
      +----------------------------------------------------------
	 * @param email 邮箱
      +----------------------------------------------------------
     */
	public function findPwdAct(){
		if(IS_POST){
			//p("@param---registerAct:找回密码开始！");
			//p($_POST);
			//p(sendMail('qiangshiluo@126.com','这仅仅是一个测试11111！','这仅仅是一个测试123123231312312313123！'));
			//exit();
			if($this->User->autoCheckToken($_POST)){
				Vendor('extend');
				//注册用户名默认值
				$f_username = '';
				//注册邮箱默认值
				$f_email = '';
				if(isset($_POST['user_name']) && !empty($_POST['user_name'])){
					$username = $_POST['user_name'];
					//删除所有单引号
					if(stristr($username,'\'')){
						$username = str_replace('\'','',$username);
					}
					$f_username = htmlspecialchars(trim($username));
					
				}else{
					$this->error("用户名不能为空！",U('User/findPwdAct'));
				}
				if(isset($_POST['user_email'])){
					$email = $_POST['user_email'];
					//删除所有单引号
					if(stristr($email,'\'')){
						$email = str_replace('\'','',$email);
					}
					$f_email = htmlspecialchars(trim($email));
					if(!is_email($f_email)){
						$this->error("邮箱格式不正确！",U('User/findPwdAct'));
					}
				}else{
					$this->error("邮箱信息不能为空！",U('User/findPwdAct'));
				}
				
				//判断用户名是否重复
				$result = $this->User->where("username='".$f_username."' and email = '".$f_email."'")->count();
				if($result){
					$randStr = str_shuffle('ABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890');
					$new_pass = strtolower(substr($randStr,0,6));
					$mail_title = "Opened平台-找回密码";
					$mail_content = "<b>$f_username</b>,您好：<br />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;萌萌哒的管理员马不停蹄的就帮您找回了密码,请妥善保管您的登录信息：<br />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;用户名：$f_username &nbsp;&nbsp;&nbsp;密码：$new_pass <br />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href='http://www.trydemo.net".U('User/loginAct/')."'>登录Opened</a>&nbsp;&nbsp;&nbsp;<a href='http://www.trydemo.net".U('Index/index')."'>返回首页</a>";
					//$mail_content = "这仅仅是一个测试";
					//此处可以做邮件备份
					$data = array();
					$data['pwd'] = encrypt($new_pass);
					$data['update_time'] = time();
					//$data['__hash__'] = $_POST['__hash__'];
					$save_result = $this->User->where("username='".$f_username."' and email = '".$f_email."'")->save($data);
					if($save_result){
						if(sendMail($f_email,$mail_title,$mail_content)){
							$this->success("邮件发送成功！请查看后登录...",U('User/loginAct'));
						}else{
							
							$this->error("邮件服务器故障啦！博主赶紧帮您把新密码奉上：".$new_pass,U('User/loginAct'),5);
						}
					}else{
						$this->error("找回密码失败！请刷新重试！",U('User/findPwdAct'));
					}
				}else{
					$this->error("用户名或邮箱信息有误！",U('User/findPwdAct'));
				}
			}else{
				$this->error("令牌验证失败！",U('User/findPwdAct'));
			}
		} else {
            if (isset($_COOKIE[$this->loginMarked])) {
                $this->redirect("Index/index");
            }
			$this->page_header();
			$this->assign('current_page','找回密码');
            $this->display('User:user_find_pass');
        }
	}
	
	/**
      +----------------------------------------------------------
     * ajax登录处理
      +----------------------------------------------------------
     */
	public function ajaxLogin(){
		$dataResult = array();
		$dataResult['flag'] = 1; //默认为1表示无任何错误
		$dataResult['msg'] = '登录成功！'; //ajax提示信息
		$dataResult['data'] = ''; //返回数据、修改成功则返回、修改后的数据
		if (IS_AJAX) {
			//fwrites(APP_PATH . 'Home/ajax.txt',ACTION_NAME." start!");
			$data = array();
			$data['username'] = isset($_POST['uname']) ? htmlspecialchars(trim($_POST['uname'])) : '' ;
			$data['pwd'] = isset($_POST['pwd']) ? htmlspecialchars(trim($_POST['pwd'])) : '' ;
			$data['__hash__'] = $_POST['__hash__'];
			//fwrites(APP_PATH . 'Home/ajax.txt',$data);	
			
			if($this->User->autoCheckToken($data)){
				//$send_mail_title = '这是邮件标题';
				//$send_mail_content = '欢迎注册TryDemo个人博客！';
				//生成初始密码(自定义规则)
				//import('Common.Functions.common',WEB_ROOT,'.php'); //导入框架外部自定制函数库(数组处理)
				//$data['password'] = Common::get_password();			

				//检测初始数据是否符合规则(邮箱是否添加、是否重名)
				if(!empty($data['username']) || !empty($data['password'])){
					$returnLoginInfo = $this->User->auth($data);
					//fwrites(APP_PATH . 'Home/ajax.txt',"验证登录返回信息returnLoginInfo:".$returnLoginInfo);
					//fwrites(APP_PATH . 'Home/ajax.txt',$returnLoginInfo);
					//生成认证条件
					if ($returnLoginInfo['status'] == 1) {
						//登录成功并获取当前用户信息
						//fwrites(APP_PATH . 'Home/ajax.txt','用户登录成功！');
						//fwrites(APP_PATH . 'Home/ajax.txt',session('?user_info'));
						//fwrites(APP_PATH . 'Home/ajax.txt',session("user_info"));
						//fwrites(APP_PATH . 'Home/ajax.txt','==============================================');
						//fwrites(APP_PATH . 'Home/ajax.txt',$_SESSION);
	
						$dataResult['flag'] = 1; //默认为1表示无任何错误
						$dataResult['msg'] = $returnLoginInfo['info']; //ajax提示信息
						//fwrites(APP_PATH . 'Home/ajax.txt','==============================================ajax返回详情！');
						$test = $this->getUserInfoBySession();
						//fwrites(APP_PATH . 'Home/ajax.txt',$test);
						//登陆成功之后直接调用session
						$dataResult['data'] = $this->getUserInfoBySession();
					}else{
						$dataResult['flag'] = 0; //默认为1表示无任何错误
						$dataResult['msg'] = $returnLoginInfo['info']; //ajax提示信息
						$dataResult['data'] = '';
					}
				}else{
					$dataResult['flag'] = 0; //默认为1表示无任何错误
					$dataResult['msg'] = '用户名密码不能为空！'; //ajax提示信息
					$dataResult['data'] = '';
				}
				
			}else{
				$dataResult['flag'] = 0; //默认为1表示无任何错误
				$dataResult['msg'] = '令牌有误！请刷新重试！'; //ajax提示信息
				$dataResult['data'] = '';
			}
			

		} else {
			$dataResult['flag'] = 0; //默认为1表示无任何错误
			$dataResult['msg'] = '请求非法！'; //ajax提示信息
			$dataResult['data'] = '';
		}
		$this->ajaxReturn($dataResult,'JSON');

	}
	
	/**
      +----------------------------------------------------------
     * 登出操作
      +----------------------------------------------------------
     */
	public function logout(){
		header("content-type:text/html;charset=utf-8");
		
		setcookie("$this->loginMarked", NULL, -3600, "/");
        unset($_SESSION["$this->loginMarked"], $_COOKIE["$this->loginMarked"]);
        if (isset($_SESSION[$this->userInfoMarked])) {
            unset($_SESSION[$this->userInfoMarked]);
            unset($_SESSION);
            session_destroy();
        }
		
		echo "<script type='text/javascript'>alert('您已经成功退出登录!');</script>";
		$this->redirect("Index/index");
	}
	
	/**
      +----------------------------------------------------------
     * 个人中心
      +----------------------------------------------------------
     */
	public function uCenter(){
		// 检查是否登录
		if(!$this->checkLogin()){
			$this->redirect("Index/index");
		}
		$this->page_header();
		$this->assign('current_page','个人中心');
		$c_user_info = $this->getUserInfo();
		$user_info = $this->User->getOneUserById($c_user_info['aid']);
		// p($user_info);
		$this->assign('user_info',$user_info);
		// p($user_info);
		$company_list = $this->CompanyUser->field("cu.id,cu.cid,cu.aid,cu.role_id,c.name,c.email,c.phone,c.address,c.website,c.company_logo,c.remark")->join('as cu LEFT JOIN td_company as c ON c.cid = cu.cid')->where("cu.aid = ".$user_info['aid']." and c.is_del = 0 and c.status = 1")->order('cu.id,c.create_time asc')->select();
		// p($company_list);
		$this->assign('company_list',$company_list);
		$this->display("User/user_center");
	}
	
	/**
      +----------------------------------------------------------
     * 生成验证码
      +----------------------------------------------------------
     */
	 public function creatVerifyCode(){
		$Verify = new \Think\Verify();  
		$Verify->fontSize = 18;  
		$Verify->length   = 4;  
		$Verify->useNoise = false;  
		$Verify->codeSet = '0123456789';  
		$Verify->imageW = 130;  
		$Verify->imageH = 30;  
		$Verify->expire = 600;  
		$Verify->entry(); 
	}
	
	/**
      +----------------------------------------------------------
     * ajax发布项目评论
      +----------------------------------------------------------
     */
	public function ajaxComment(){
		$dataResult = array();
		$dataResult['flag'] = 1; //默认为1表示无任何错误
		$dataResult['msg'] = '发布成功！'; //ajax提示信息
		$dataResult['data'] = ''; //返回数据、修改成功则返回、修改后的数据
		fwrites(APP_PATH . 'Home/ajax.txt',"@param(url)---ajaxComment start!");
		//判断是否是ajax请求
		if(IS_AJAX){
			//判断前台用户是否已经登录
			$uid = 0;	
			$username = '';
			$email = '';
			$site = '';
			$correlation_id = isset($_POST['correlation_id']) ? htmlspecialchars($_POST['correlation_id']) : 0 ;
			$content = isset($_POST['comment_content']) ? htmlspecialchars($_POST['comment_content']) : '' ;
			$edit_type = isset($_POST['c_edit_id']) ? intval(htmlspecialchars($_POST['c_edit_id'])) : 0 ;

			fwrites(APP_PATH . 'Home/ajax.txt',$_POST);
			fwrites(APP_PATH . 'Home/ajax.txt','检测是否是编辑模式------------------>$edit_type:'.$edit_type);
			fwrites(APP_PATH . 'Home/ajax.txt',$edit_type);
			//开始reply后台数据验证
			$user_info = $this->getUserInfo();
			// fwrites(APP_PATH . 'Home/ajax.txt','user_info---------------------->');
			// fwrites(APP_PATH . 'Home/ajax.txt',$user_info);
			if($user_info && !empty($user_info)){
				if(!$content){
					$dataResult['flag'] = 0; //默认为1表示无任何错误
					$dataResult['msg'] = '评论内容不能为空!'; //ajax提示信息
					$dataResult['data'] = 'err_content';
					$this->ajaxReturn($dataResult,'JSON');
				}
				$uid = intval($user_info['aid']);
				$username = $user_info['username'];
				$email = $user_info['email'];
				$site = $user_info['personal_website'];
			}else{				
				$dataResult['flag'] = 0; //默认为1表示无任何错误
				$dataResult['msg'] = '请刷新重新登录!'; //ajax提示信息
				$dataResult['data'] = 'err_login';
				$this->ajaxReturn($dataResult,'JSON');
			}
			$id = $correlation_id+0;
			$oneProject = $this->Project->where("pid = $id")->find();
			// fwrites(APP_PATH . 'Home/ajax.txt','oneProject---------------------->');
			// fwrites(APP_PATH . 'Home/ajax.txt',$oneProject);
			if(!$id || empty($oneProject)){	
				$dataResult['flag'] = 0; //默认为1表示无任何错误
				$dataResult['msg'] = '找不到该公司信息!'; //ajax提示信息
				$dataResult['data'] = 'redirect_index';
			}

			$data = array();
			if($edit_type){
				//编辑模式
				$data=array(
					'comment_edit_date'=>time(),
					'comment_content'=>$content,
					'comment_agent'=>isset( $_SERVER['HTTP_USER_AGENT'] ) ? substr( $_SERVER['HTTP_USER_AGENT'], 0, 254 ) : '',
					'comment_author_ip'=>get_client_ip(),
				);

			}else{
				//新增模式
				$data=array(
					'aid'=>0,
					'uid'=>intval($user_info['aid']),
					'type'=>2,
					'correlation_id'=>$correlation_id,
					'title'=>$oneProject['name'],
					'comment_author_name'=>$username,
					'comment_author_email'=>$email,
					'comment_author_avatar'=>$user_info['avatar'],
					'comment_author_url'=>$site,
					'comment_add_date'=>time(),
					'comment_edit_date'=>time(),
					'comment_content'=>$content,
					'comment_top_parent'=>intval(htmlspecialchars($_POST['c_best_parent'])),
					'comment_parent'=>intval(htmlspecialchars($_POST['comment_parent'])),
					'comment_agent'=>isset( $_SERVER['HTTP_USER_AGENT'] ) ? substr( $_SERVER['HTTP_USER_AGENT'], 0, 254 ) : '',
					'comment_type'=>0,
					'comment_author_ip'=>get_client_ip(),
				);
				if(!$data['comment_top_parent'] && $data['comment_parent']){
					$data['comment_top_parent'] = $data['comment_parent'];
				}
			}
			//开始数据库验证
			$mod = M();
			if(!$edit_type){
				//增加：非编辑模式下检查是否是重复评论
				if($uid){
					$sql_for_repeat = "SELECT cid FROM $this->t_n_comments WHERE correlation_id = $id AND type = 1 AND uid = $uid AND comment_content = '" . $data['comment_content'] . "' LIMIT 1";
				}else{
					$sql_for_repeat = "SELECT cid FROM $this->t_n_comments WHERE correlation_id = $id AND type = 1 AND comment_author_name = '". $username ."' AND comment_author_email = '$email' AND comment_content = '" . $data['comment_content'] . "' LIMIT 1";
				}
				
				//fwrites(APP_PATH.'Home/ajax.txt','@param(ajaxComment)---sql_for_repeat:'.$sql_for_repeat);

				$cid = $mod->query($sql_for_repeat);	
				if($cid){
					$dataResult['flag'] = 0; //默认为1表示无任何错误
					$dataResult['msg'] = '看起来这条评论你以前说过!'; //ajax提示信息
					$dataResult['data'] = 'err_repeat';
					$this->ajaxReturn($dataResult,'JSON');
				}
			}
			//增加：检查是否评论过快
			$interval_time = intval(CONFIG_A_REPLY_INTERVAL);	
			if($interval_time){
				if($uid){
					$sql_for_quick = "SELECT comment_add_date FROM $this->t_n_comments WHERE correlation_id = $id AND type = 1 AND uid = $uid LIMIT 1";
				}else{
					$sql_for_quick = "SELECT comment_add_date FROM $this->t_n_comments WHERE correlation_id = $id AND type = 1 AND comment_author_name = $username AND comment_author_email = '$email' ORDER BY comment_add_date DESC  LIMIT 1";
				}
				$add_time = $mod->query($sql_for_quick);
				if($add_time){
					if(is_array($add_time) && count($add_time)){
						$int_time = $add_time['0']['comment_add_date'];
					}else{
						$int_time = $add_time;
					}
					//读取配置最快回复间隔时间
					//$Config['a_reply_interval'];
					if((time()-$int_time) < $interval_time){
						$dataResult['flag'] = 0; //默认为1表示无任何错误
						$dataResult['msg'] = '评论过快!服务器快要崩溃呐！'; //ajax提示信息
						$dataResult['data'] = 'err_quick';
						$this->ajaxReturn($dataResult,'JSON');
					}
				}
			}

			if($edit_type){
				//编辑模式
				$new_cid = $this->Comments->where("cid=".$edit_type)->save($data);
				//fwrites(APP_PATH . 'home/ajax.txt','------------------>这事编辑模式');
				//fwrites(APP_PATH . 'home/ajax.txt',$new_cid);
			}else{
				//新增模式
				$new_cid = $this->Comments->data($data)->add();
			}

			//fwrites(APP_PATH . 'Home/ajax.txt','看看是否添加新评论成功------------->new_cid');
			//fwrites(APP_PATH . 'Home/ajax.txt',$new_cid);
			if($new_cid){
				$return_data = array();
				if($edit_type){
					$c_data = $this->Comments->getInfoById($edit_type);
					$return_data = 	count($c_data) > 0 ? $c_data[0] : '' ;
					$return_data['update_cid'] = $edit_type;
				}else{
					$c_data = $this->Comments->getInfoById($new_cid);
					$return_data = 	count($c_data) > 0 ? $c_data[0] : '' ;
				}
				if($user_info && !empty($user_info)){
					Vendor('extend');	
				}
				$return_data['comment_add_date'] = date('Y-m-d H:i:s',$return_data['comment_add_date']);
				$return_data['comment_edit_date'] = date('Y-m-d H:i:s',$return_data['comment_edit_date']);
				$return_data['comment_content'] = convert_smilie($return_data['comment_content']);
				if($return_data['comment_top_parent']){
					$p_c_author_name = isset($_POST['comment_parent_author_name']) ? htmlspecialchars($_POST['comment_parent_author_name']) : '' ;
					if($return_data['comment_author_name'] == $p_c_author_name){
						$return_data['sub_marking'] = "<a><b>".$return_data['comment_author_name']."&nbsp;&nbsp;</b></a><font color='orange'>(补充)</font><a><b>:</b></a>";
					}else{
						$return_data['sub_marking'] = "<a><b>".$return_data['comment_author_name']."</b></a><span>&nbsp;&nbsp;回复&nbsp;&nbsp;</span><a><b>".$p_c_author_name."&nbsp;:</b></a>";
					}
				}else{
					$return_data['sub_marking'] = '这个人很懒,他什么都没有留下...(顶级评论无作用)';	
				}

				//fwrites(APP_PATH . 'Home/ajax.txt','评论成功、开始返回最新数据------------->$return_data');
				//fwrites(APP_PATH . 'Home/ajax.txt',$return_data);
				$dataResult['data'] = $return_data;
				$this->ajaxReturn($dataResult,'JSON');

			}else{
				//fwrites(APP_PATH . 'Home/ajax.txt','评论失败------------->c_data');
				$dataResult['flag'] = 0; //默认为1表示无任何错误
				$dataResult['msg'] = '提交失败！请稍后重新提交！'; //ajax提示信息
				$dataResult['data'] = 'err_server';
				$this->ajaxReturn($dataResult,'JSON');
			}
			
			$dataResult['flag'] = 0; //默认为1表示无任何错误
			$dataResult['msg'] = '提交失败！请稍后重新提交！'; //ajax提示信息
			$dataResult['data'] = 'err_server';
			$this->ajaxReturn($dataResult,'JSON');
		}else{
			$dataResult['flag'] = 0; //默认为1表示无任何错误
			$dataResult['msg'] = '提交失败！请稍后重新提交！'; //ajax提示信息
			$dataResult['data'] = 'err_server';
			$this->ajaxReturn($dataResult,'JSON');
		}
	}
	
}
?>