<?php
/**
 * 会员中心(前台用户单表模式暂时屏蔽)
 * ============================================================================
 * 版权所有 2015-2080 Lessismore，并保留所有权利。
 * 网站地址: http://www.trydemo.net；
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和
 * 使用；不允许对程序代码以任何形式任何目的的再发布。
 * ============================================================================
 * $Author: lsq & Lessismore & D.Apache.Luo
 * $Id: UserActivity.class.php 2016-1-13 Lessismore $
*/
namespace Admin\Controller;
use Think\Controller;
use Think\Model;
class UserController extends CommonController {
	
	protected  $Admin, $Role, $Node, $Access, $RoleUser;
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
		$this->Admin = D("Admin");
		$this->RoleUser = D("RoleUser");
		// $this->Role = D("Role");
		// $this->Node = D("Node");
		// $this->Access = D("Access");
	}
	
	/**
      +----------------------------------------------------------
     * 显示用户列表
      +----------------------------------------------------------
     */
	public function index(){
		$this->assign('act',strtolower(ACTION_NAME));
		// 分配导航栏当前位置
		$this->assign('navigation_bar','用户管理>所有用户');
		// 回收站关键字(默认为非回收站)
		$mTrash_act = 0;
		// 关键字搜索默认值
		$keyword = '';
		// 审核状态筛选默认值
		$type = 0;
		$where = 'is_del = 0';
		if(isset($_REQUEST['keyword'])){
			$keyword = $_REQUEST['keyword'];
			// 删除所有单引号
			if(stristr($keyword,'\'')){
				$keyword = str_replace('\'','',$keyword);
			}
			$new_keyword = htmlspecialchars(trim($keyword));
			$where .= " and username like '%$new_keyword%'";
		}
		
		if(isset($_REQUEST['dropdown_type'])){
			$type = intval(htmlspecialchars(trim($_REQUEST['dropdown_type'])));
			switch($type){
				case 1:
					$where .= " and status = 1";
					break;
				case 2:
					$where .= " and status = 0";
					break;
				default:
					break;
			}
		}

		$count = $this->Admin->join('as a RIGHT JOIN td_role_user as ru ON a.aid = ru.user_id')->where($where . ' and ru.role_id = '.$this->t_role_id)->count();
		$page = new \Think\Page($count,15);
		$show = $page->show();
		/*
		$list = $this->Admin->where($where)->order("last_login_time desc")->limit($page->firstRow.','.$page->listRows)->select();
		if(is_array($list) && count($list)){
			foreach ($list as $key=>$val){
				$list[$key]['key']=++$page->firstRow;
			}
		}
		*/
		
		$list = $this->Admin->field('a.aid,a.nickname,a.email,a.username,a.sex,a.avatar,a.mobile_number,a.status,a.create_time,a.last_login_time')->join('as a RIGHT JOIN td_role_user as ru ON a.aid = ru.user_id')->where($where . ' and ru.role_id = '.$this->t_role_id)->order("a.create_time desc")->limit($page->firstRow.','.$page->listRows)->select();
		if(is_array($list) && count($list)){
			foreach ($list as $key=>$val){
				$list[$key]['key']=++$page->firstRow;
			}
		}

		$this->assign('keyword',$keyword);
		$this->assign('type',$type);
		$this->assign('list_empty','<tr align="center"><td colspan="10" align="center"><span>前台用户列表为空！请先创建新用户！</span></td></tr>');
		$this->assign('list',$list);
		$this->assign('page',$show);
		$this->display();
	}

	/**
      +----------------------------------------------------------
     * ajax更改用户审核状态
      +----------------------------------------------------------
     */
	public function ajax_update_status(){
		$dataResult = array();
		$dataResult['flag'] = 1; // 默认为1表示无任何错误
		$dataResult['msg'] = '锁定状态更新成功！'; // ajax提示信息
		$dataResult['data'] = ''; // 返回数据、修改成功则返回、修改后的数据
		if (IS_AJAX) {
			$id = intval(trim($_REQUEST['id']));
			$data['aid'] = $id;
			$status = $this->Admin->where($data)->getField('status');
			$set = array();
			if($status == 1){
				$set = array('status'=>0);		
			}elseif($status == 0){
				$set = array('status'=>1);
			}else{
				$set = array('status'=>0);
			}
			$u_result = $this->Admin->where($data)->save($set);
			if($u_result){
				$dataResult['data'] = $set['status'];
				$this->ajaxReturn($dataResult,'JSON');
			}else{
				$dataResult['flag'] = 0; // 默认为1表示无任何错误
				$dataResult['msg'] = '锁定状态更新失败！请稍后重新操作！'; // ajax提示信息
				$dataResult['data'] = 'err_no'; // 返回数据、修改成功则返回、修改后的数据
				$this->ajaxReturn($dataResult,'JSON');
			}
		}else{
			$dataResult['flag'] = 0; // 默认为1表示无任何错误
			$dataResult['msg'] = '提交失败！请稍后重新提交！'; // ajax提示信息
			$dataResult['data'] = 'err_server';
			$this->ajaxReturn($dataResult,'JSON');
		}
	}
	
     /**
      +----------------------------------------------------------
     * 显示添加新用户界面
      +----------------------------------------------------------
     */
	public function mAdd(){
		if (IS_POST) {
			$data = array();
			$data['username'] = isset($_POST['username']) ? trim($_POST['username']) : '' ;
			$data['password'] = isset($_POST['password']) ? trim($_POST['password']) : '' ;
			$data['repwd'] = isset($_POST['repwd']) ? trim($_POST['repwd']) : '' ;
			$data['email'] = isset($_POST['email']) ? trim($_POST['email']) : '' ;
			$data['mobile_number'] = isset($_POST['mobile_number']) ? floatval($_POST['mobile_number']) : 0 ;
			$data['qq'] = isset($_POST['qq']) ? floatval($_POST['qq']) : 0 ;
			$data['nickname'] = isset($_POST['nickname']) ? trim($_POST['nickname']) : '' ;
			$data['sex'] = $_POST['sex'];
			$data['status'] = isset($_POST['status']) ? intval(trim($_POST['status'])) : 1 ;			
			if(!empty($_POST['remark'])) {
				if (get_magic_quotes_gpc()) {
					$data['remark'] = stripslashes($_POST['remark']);
				} else {
					$data['remark'] = $_POST['remark'];
				}
			}else{
				$data['remark'] = '';
			}
			$data['create_time'] = time();
			$data['update_time'] = $data['create_time'];
			$data['operator_id'] = session(C('USER_AUTH_KEY'));
			$data['__hash__'] = $_POST['__hash__'];
			// 检测初始数据是否符合规则(密码是否合格、邮箱是否添加、是否重名)
			if(!empty($data['username'])){
				if($data['password'] == $data['repwd']){
					$data['pwd'] = encrypt($data['password']);
					unset($data['password']);
					unset($data['repwd']);
					if(!empty($data['email'])){
						$pattern = '/^([a-zA-Z0-9]+[\._-]?[a-zA-Z0-9]+)+@[a-zA-Z0-9]+(\.[a-zA-Z0-9])+/i';  
						if(preg_match($pattern,$data['email'])){
							$result = $this->Admin->where("username='".$data['username']."'")->count();
							if(!$result){
								if (!empty($_FILES) && $_FILES['avatar']['size'] != 0){
									$config = array(
										'maxSize' => 3145728,
										'rootPath' => './Uploads/Common/avatar_big/',
										'savePath' => '',
										'saveName' => array('uniqid',''),
										'exts' => array('jpg', 'gif', 'png', 'jpeg'),
										'autoSub' => false,
										'subName' => array('date','Ymd'),
									);
									$upload = new \Think\Upload($config,'Local');// 实例化上传类
									$info = $upload->uploadOne($_FILES['avatar']);
									if($info){
										// 图片上传成功获取图片路径和名字
										$data['avatar'] = $info['savepath'].$info['savename'];
										// 检验数据
										$data = $this->Admin->create($data, 1); // 1是插入操作，0是更新操作
										$u_result = $this->Admin->add($data);
										// 生成小头像
										$image = new \Think\Image(); 
										$image->open('./Uploads/Common/avatar_big/'.$data['avatar']);
										$image->thumb(100, 100, \Think\Image::IMAGE_THUMB_FIXED)->save('./Uploads/Common/avatar_small/'.$data['avatar']);
										if (false !== $u_result){
											// 新增管理员成功，开始执行绑定前台用户角色（暂定绑死后期可以做维护表）
											if($this->RoleUser->add(array("role_id"=>$this->t_role_id,"user_id"=>$u_result))){
												$this->success("添加用户成功！", U('User/index'));
											}else{
												$this->success("警告:用户新增成功,前台角色绑定失败！", U('User/index'));
												// 此处抛出异常并记录异常日志！
												
											}
										} else {
											$this->error($this->Admin->getError(),U('User/mAdd'));
										}
									}else{
										$this->error($upload->getError(),U('User/mAdd'));
									}
								}else{
									// 检验数据
									$data = $this->Admin->create($data, 1); // 1是插入操作，0是更新操作
									$u_result = $this->Admin->add($data);
									if (false !== $u_result){
										// 新增管理员成功，开始执行绑定前台用户角色（暂定绑死后期可以做维护表）
										if($this->RoleUser->add(array("role_id"=>$this->t_role_id,"user_id"=>$u_result))){
											$this->success("添加用户成功！", U('User/index'));
										}else{
											$this->success("警告:用户新增成功,前台角色绑定失败！", U('User/index'));
											// 此处抛出异常并记录异常日志！
											
										}
									} else {
										$this->error($this->Admin->getError(),U('User/mAdd'));
									}
								}
							}else{
								$this->error('用户'.$data['username'].'已经存在',U('User/mAdd'));
							}
						}else{
							$this->error('邮箱格式不正确！',U('User/mAdd'));
						}  
					}else{
						$this->error('邮箱不能为空！',U('User/mAdd'));
					}
				}else{
					$this->error('两次密码不一致！',U('User/mAdd'));
				}
			}else{
				$this->error('用户名不能为空！',U('User/mAdd'));
			}	

		} else {
			// 分配导航栏当前位置
			$this->assign('navigation_bar','用户管理>添加新用户');
			$this->assign('act',strtolower(ACTION_NAME));
			// 初始化模版引擎
			$empty_info = array(
				'aid' => 0,
				'username' => '',
				'password' => '',
				'repwd' => '',
				'email' => '',
				'nickname' => '',
				'mobile_number' => '',
				'qq' => time(),
				'avatar' => '',
				'sex' => 0,
				'status' => 1,
				'remark' => '',
			);
			$this->assign('user_info', $empty_info);
			$this->display("User:add");
			
		}	
	}

	/**
      +----------------------------------------------------------
     * 显示编辑或者修改用户信息页面
      +----------------------------------------------------------
     */
	public function mEdit(){

		if (IS_POST) {
			$data = array();
			$data['aid'] = intval($_POST['id']) ? intval($_POST['id']) : 0 ;
			$username = isset($_POST['username']) ? trim($_POST['username']) : '' ;
			$data['password'] = isset($_POST['password']) ? trim($_POST['password']) : '' ;
			$data['repwd'] = isset($_POST['repwd']) ? trim($_POST['repwd']) : '' ;
			$data['email'] = isset($_POST['email']) ? trim($_POST['email']) : '' ;
			$data['nickname'] = isset($_POST['nickname']) ? trim($_POST['nickname']) : '' ;
			$data['mobile_number'] = isset($_POST['mobile_number']) ? floatval($_POST['mobile_number']) : 0 ;
			$data['qq'] = isset($_POST['qq']) ? floatval($_POST['qq']) : 0 ;
			$data['sex'] = isset($_POST['sex']) ? intval(trim($_POST['sex'])) : 0 ;
			$data['status'] = isset($_POST['status']) ? intval(trim($_POST['status'])) : 1 ;
			if(!empty($_POST['remark'])) {
				if (get_magic_quotes_gpc()) {
					$data['remark'] = stripslashes($_POST['remark']);
				} else {
					$data['remark'] = $_POST['remark'];
				}
			}else{
				$data['remark'] = '';
			}
			$data['update_time'] = time();
			$data['operator_id'] = session(C('USER_AUTH_KEY'));
			$data['__hash__'] = $_POST['__hash__'];

			// 检测初始数据是否符合规则(密码是否合格、邮箱是否添加、是否重名)
			if($data['aid']){
				if($data['password']){
					if($data['password'] == $data['repwd']){
						$data['pwd'] = encrypt($data['password']);
						unset($data['password']);
						unset($data['repwd']);
					}else{
						$this->error('两次密码不一致！',U('User/mAdd','id='.$data['aid']));
					}
				}else{
					// 密码未修改
					unset($data['password']);
					unset($data['repwd']);
				}

				if(!empty($data['email'])){
					$pattern = '/^([a-zA-Z0-9]+[\._-]?[a-zA-Z0-9]+)+@[a-zA-Z0-9]+(\.[a-zA-Z0-9])+/i';  
					if(!preg_match($pattern,$data['email'])){
						$this->error('邮箱格式不正确！',U('User/mAdd'));
					}  
				}else{
					unset($data['email']);
				}

				if(!$data['qq']){
					unset($data['qq']);
				}

				if(!$data['mobile_number']){
					unset($data['mobile_number']);
				}

				if(!$data['nickname']){
					unset($data['nickname']);
				}

				if(!$data['remark']){
					unset($data['remark']);
				}

				// 开始执行更新	
				$result = $this->Admin->where("aid='".$data['aid']."'")->find();
				if($result){
					if($result['avatar']){
						if(file_exists('./Uploads/Common/avatar_big/'.$result['avatar'])){
							@unlink('./Uploads/Common/avatar_big/'.$result['avatar']);
						}
						if(file_exists('./Uploads/Common/avatar_small/'.$result['avatar'])){
							@unlink('./Uploads/Common/avatar_small/'.$result['avatar']);
						}
					}
					if (!empty($_FILES) && $_FILES['avatar']['size'] != 0){
						$config = array(
							'maxSize' => 3145728,
							'rootPath' => './Uploads/Common/avatar_big/',
							'savePath' => '',
							'saveName' => array('uniqid',''),
							'exts' => array('jpg', 'gif', 'png', 'jpeg'),
							'autoSub' => false,
							'subName' => array('date','Ymd'),
						);
						$upload = new \Think\Upload($config,'Local');// 实例化上传类
						$info = $upload->uploadOne($_FILES['avatar']);
						if($info){
							// 图片上传成功获取图片路径和名字
							$data['avatar'] = $info['savepath'].$info['savename'];
							// 检验数据
							$data = $this->Admin->create($data, 0); // 1是插入操作，0是更新操作
							$u_result = $this->Admin->where("aid=" . $data['aid'])->save($data);
							// 生成小头像
							$image = new \Think\Image(); 
							$image->open('./Uploads/Common/avatar_big/'.$data['avatar']);
							$image->thumb(100, 100, \Think\Image::IMAGE_THUMB_FIXED)->save('./Uploads/Common/avatar_small/'.$data['avatar']);
							if(false !== $u_result){
								$this->success("编辑用户成功！", U('User/index'));
							} else {
								$this->error($this->Admin->getError(),U('User/mEdit','id='.$data['aid']));
							}
						}else{
							$this->error($upload->getError(),U('User/mEdit','id='.$data['aid']));
						}
					}else{
						// 检验数据
						$data = $this->Admin->create($data, 0); // 1是插入操作，0是更新操作
						$u_result = $this->Admin->where("aid=" . $data['aid'])->save($data);
						if(false !== $u_result){
							$this->success("编辑用户成功！", U('User/index'));
						} else {
							$this->error($this->Admin->getError(),U('User/mEdit','id='.$data['aid']));
						}
					}
				}else{
					$this->error('用户'.$data['nickname'].'已经不存在！',U('User/index'));
				}
			}else{
				$this->error('用户记录丢失！',U('User/index'));
			}
		} else {
			// 分配导航栏当前位置
			$this->assign('navigation_bar','用户管理>编辑用户信息');
			$this->assign('act',strtolower(ACTION_NAME));
			$id = isset($_GET['id']) && intval($_GET['id']) ? intval($_GET['id']) : $this->error('参数错误',U('User/index'));
			$user_info = $this->Admin->where('aid='.$id)->find();
			$this->assign('user_info', $user_info);
			$this->display("User:add");
		}
	}
	
	/**
      +----------------------------------------------------------
     * 将用户移入回收站
      +----------------------------------------------------------
     */
	public function ajax_join_recycle(){
		$dataResult = array();
		$dataResult['flag'] = 1; // 默认为1表示无任何错误
		$dataResult['msg'] = '移入回收站成功！'; // ajax提示信息
		$dataResult['data'] = ''; // 返回数据、修改成功则返回、修改后的数据
		if (IS_AJAX) {
			$id = intval($_POST['id']) ? intval($_POST['id']) : 0 ;
			if($id){
				$data['aid'] = $id;
				$is_del = $this->Admin->where($data)->getField('is_del');
				if($is_del == 0){
					$set = array();
					$set = array('is_del'=>1);
					$u_result = $this->Admin->where($data)->save($set);
					if($u_result){
						$dataResult['data'] = $set['is_del'];
						$this->ajaxReturn($dataResult,'JSON');
					}else{
						$dataResult['flag'] = 0; // 默认为1表示无任何错误
						$dataResult['msg'] = '移入回收站失败！请稍后重新操作！'; // ajax提示信息
						$dataResult['data'] = 'err_no'; // 返回数据、修改成功则返回、修改后的数据
						$this->ajaxReturn($dataResult,'JSON');
					}		
				}else{
					$dataResult['flag'] = 0; // 默认为1表示无任何错误
					$dataResult['msg'] = '重复操作！该用户已经被移入回收站！'; // ajax提示信息
					$dataResult['data'] = 'err_no'; // 返回数据、修改成功则返回、修改后的数据
					$this->ajaxReturn($dataResult,'JSON');
				}
				
			}else{
				$dataResult['flag'] = 0; // 默认为1表示无任何错误
				$dataResult['msg'] = '移入回收站失败！该用户已不存在！'; // ajax提示信息
				$dataResult['data'] = 'err_no'; // 返回数据、修改成功则返回、修改后的数据
				$this->ajaxReturn($dataResult,'JSON');
			}
		}else{
			$dataResult['flag'] = 0; // 默认为1表示无任何错误
			$dataResult['msg'] = '提交失败！请稍后重新提交！'; // ajax提示信息
			$dataResult['data'] = 'err_server';
			$this->ajaxReturn($dataResult,'JSON');
		}
	}
	
	/**
      +----------------------------------------------------------
     * 将用户从回收站还原
      +----------------------------------------------------------
     */
	public function ajax_restore_user(){
		$dataResult = array();
		$dataResult['flag'] = 1; // 默认为1表示无任何错误
		$dataResult['msg'] = '还原该用户成功！'; // ajax提示信息
		$dataResult['data'] = ''; // 返回数据、修改成功则返回、修改后的数据
		//fwrites(APP_PATH . 'Admin/ajax.txt','开始删除！');
		if (IS_AJAX) {
			$id = intval($_POST['id']) ? intval($_POST['id']) : 0 ;
			if($id){
				$data['aid'] = $id;
				$is_del = $this->Admin->where($data)->getField('is_del');
				if($is_del == 1){
					$set = array();
					$set = array('is_del'=>0);
					$u_result = $this->Admin->where($data)->save($set);
					if($u_result){
						$dataResult['data'] = $set['is_del'];
						$this->ajaxReturn($dataResult,'JSON');
					}else{
						$dataResult['flag'] = 0; // 默认为1表示无任何错误
						$dataResult['msg'] = '还原用户失败！请稍后重新操作！'; // ajax提示信息
						$dataResult['data'] = 'err_no'; // 返回数据、修改成功则返回、修改后的数据
						$this->ajaxReturn($dataResult,'JSON');
					}		
				}else{
					$dataResult['flag'] = 0; // 默认为1表示无任何错误
					$dataResult['msg'] = '重复操作！该用户已经被移出回收站！'; // ajax提示信息
					$dataResult['data'] = 'err_no'; // 返回数据、修改成功则返回、修改后的数据
					$this->ajaxReturn($dataResult,'JSON');
				}
				
			}else{
				$dataResult['flag'] = 0; // 默认为1表示无任何错误
				$dataResult['msg'] = '移出回收站失败！该用户已不存在！'; // ajax提示信息
				$dataResult['data'] = 'err_no'; // 返回数据、修改成功则返回、修改后的数据
				$this->ajaxReturn($dataResult,'JSON');
			}
		}else{
			$dataResult['flag'] = 0; // 默认为1表示无任何错误
			$dataResult['msg'] = '提交失败！请稍后重新提交！'; // ajax提示信息
			$dataResult['data'] = 'err_server';
			$this->ajaxReturn($dataResult,'JSON');
		}
	}

	/**
      +----------------------------------------------------------
     * ajax删除用户以及该用户绑定的角色信息
      +----------------------------------------------------------
     */
	public function ajax_del_user(){
		$dataResult = array();
		$dataResult['flag'] = 1; // 默认为1表示无任何错误
		$dataResult['msg'] = '删除成功！'; // ajax提示信息
		$dataResult['data'] = ''; // 返回数据、修改成功则返回、修改后的数据
		if (IS_AJAX) {
			$id = intval($_POST['id']) ? intval($_POST['id']) : 0 ;
			$a_result = $this->Admin->delete($id);
			if($a_result){
				// 删除用户绑定角色
				$this->RoleUser->where(array("user_id"=>$id))->delete();
				$dataResult['data'] = 'success'; // 返回数据、修改成功则返回、修改后的数据
				$this->ajaxReturn($dataResult,'JSON');
			}else{
				$dataResult['flag'] = 0; // 默认为1表示无任何错误
				$dataResult['msg'] = '删除失败！请稍后重新操作！'; // ajax提示信息
				$dataResult['data'] = 'err_no'; // 返回数据、修改成功则返回、修改后的数据
				$this->ajaxReturn($dataResult,'JSON');
			}
		}else{
			$dataResult['flag'] = 0; // 默认为1表示无任何错误
			$dataResult['msg'] = '提交失败！请稍后重新提交！'; // ajax提示信息
			$dataResult['data'] = 'err_server';
			$this->ajaxReturn($dataResult,'JSON');
		}
	}
	
	/**
      +----------------------------------------------------------
     * 用户回收站管理
      +----------------------------------------------------------
     */
	public function mTrash(){
		$this->assign('act',strtolower(ACTION_NAME));
		// 分配导航栏当前位置
		$this->assign('navigation_bar','用户管理>回收站管理');
		// 回收站关键字(默认为非回收站)
		$mTrash_act = 1;
		// 关键字搜索默认值
		$keyword = '';
		// 审核状态筛选默认值
		$type = 0;
		$where = 'is_del = 1';
		if(isset($_REQUEST['keyword'])){
			$keyword = $_REQUEST['keyword'];
			// 删除所有单引号
			if(stristr($keyword,'\'')){
				$keyword = str_replace('\'','',$keyword);
			}
			$new_keyword = htmlspecialchars(trim($keyword));
			$where .= " and username like '%$new_keyword%'";
		}
		
		if(isset($_REQUEST['dropdown_type'])){
			$type = intval(htmlspecialchars(trim($_REQUEST['dropdown_type'])));
			switch($type){
				case 1:
					$where .= " and status = 1";
					break;
				case 2:
					$where .= " and status = 0";
					break;
				default:
					break;
			}
		}

		$count = $this->Admin->join('as a RIGHT JOIN td_role_user as ru ON a.aid = ru.user_id')->where($where . ' and ru.role_id = '.$this->t_role_id)->count();
		$page = new \Think\Page($count,15);
		$show = $page->show();
		$list = $this->Admin->field('a.aid,a.nickname,a.email,a.username,a.sex,a.avatar,a.mobile_number,a.status,a.create_time,a.last_login_time')->join('as a RIGHT JOIN td_role_user as ru ON a.aid = ru.user_id')->where($where . ' and ru.role_id = '.$this->t_role_id)->order("a.create_time desc")->limit($page->firstRow.','.$page->listRows)->select();
		if(is_array($list) && count($list)){
			foreach ($list as $key=>$val){
				$list[$key]['key']=++$page->firstRow;
			}
		}
		$this->assign('keyword',$keyword);
		$this->assign('type',$type);
		$this->assign('list_empty','<tr align="center"><td colspan="10" align="center"><span>回收站列表为空！</span></td></tr>');
		$this->assign('list',$list);
		$this->assign('page',$show);
		$this->display('User:index');
	}

	/**
      +----------------------------------------------------------
     * 用户分析
      +----------------------------------------------------------
     */	
	public function mUserAnalyse(){
		// 分配导航栏当前位置
		$this->assign('navigation_bar','用户管理>用户分析');
		$this->assign('act',strtolower(ACTION_NAME));
		$this->display("User:analyse");
		
	}
	
	
}

