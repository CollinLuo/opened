<?php
/**
 * 权限管理
 * ============================================================================
 * 版权所有 2015-2080 Lessismore，并保留所有权利。
 * 网站地址: http://www.trydemo.net；
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和
 * 使用；不允许对程序代码以任何形式任何目的的再发布。
 * ============================================================================
 * $Author: lsq & Lessismore & D.Apache.Luo
 * $Id: AccessController.class.php 2016-1-12 Lessismore $
*/
namespace Admin\Controller;
use Think\Controller;
use Think\Model;
class AccessController extends CommonController {

	protected $Admin, $Role, $Node, $Access, $RoleUser;
	protected $superAdminId = 88995;

	public function _initialize() {
		parent::_initialize();
		$this->Admin = D("Admin");
		$this->Role = D("Role");
		$this->RoleUser = D("RoleUser");
		$this->Node = D("Node");
		$this->Access = D("Access");
	}

	 /**
      +----------------------------------------------------------
     * 验证token信息
      +----------------------------------------------------------
     */
    private function checkTokens(){
        if (!$this->Role->autoCheckToken($_POST)) {
            die(json_encode(array('status' => 0, 'info' => '令牌验证失败')));
        }
        unset($_POST[C("TOKEN_NAME")]);
    }
	
	/**
      +----------------------------------------------------------
     * 用户列表页
      +----------------------------------------------------------
     */
	public function index(){
		$this->assign('act',strtolower(ACTION_NAME));
		//分配导航栏当前位置
		$this->assign('navigation_bar','权限管理>所有用户');
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
	
		$count = $this->Admin->where($where)->count();
		$page = new \Think\Page($count,15);
		$show = $page->show();
		$list = $this->Admin->field('a.aid,a.nickname,a.email,a.username,a.sex,a.birthday,a.avatar,a.mobile_number,a.qq,a.personal_website,a.status,a.remark,a.last_login_time,a.last_login_ip,a.create_time,r.id as r_id,r.name as r_name,r.pid as r_pid,r.status as r_status,r.remark as r_remark')->join('as a LEFT JOIN td_role_user as ru on a.aid = ru.user_id')->join('LEFT JOIN td_role as r ON ru.role_id = r.id')->where($where)->order("last_login_time desc")->limit($page->firstRow.','.$page->listRows)->select();
		foreach ($list as $key=>$val){
			$list[$key]['key']=++$page->firstRow;
		}
		//p($list);
		$this->assign('keyword',$keyword);
		$this->assign('type',$type);
		$this->assign('list_empty','<tr align="center"><td colspan="10" align="center"><span>用户列表为空！请先创建新用户！</span></td></tr>');
		$this->assign('list',$list);
		$this->assign('page',$show);
		$this->display();
	}

	/**
      +----------------------------------------------------------
     * 显示新增管理员页面
      +----------------------------------------------------------
     */
	public function mAdd(){

		if (IS_POST) {
			$data = array();
			//$data['aid'] = intval($_POST['id']) ? intval($_POST['id']) : 0 ;
			$data['username'] = isset($_POST['username']) ? trim($_POST['username']) : '' ;
			$data['password'] = isset($_POST['password']) ? trim($_POST['password']) : '' ;
			$data['repwd'] = isset($_POST['repwd']) ? trim($_POST['repwd']) : '' ;
			$role_id = isset($_POST['role_id']) ? intval($_POST['role_id']) : 0 ;
			$data['email'] = isset($_POST['email']) ? trim($_POST['email']) : '' ;
			$data['nickname'] = isset($_POST['nickname']) ? trim($_POST['nickname']) : '' ;
			$data['mobile_number'] = isset($_POST['mobile_number']) ? floatval($_POST['mobile_number']) : 0 ;
			$data['qq'] = isset($_POST['qq']) ? floatval($_POST['qq']) : 0 ;
			$data['sex'] = $_POST['sex'];
			$data['status'] = isset($_POST['status']) ? intval(trim($_POST['status'])) : 1 ;
			$data['remark'] = isset($_POST['remark']) ? trim($_POST['remark']) : '' ;
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
							
							//开始执行新增	
							$result = $this->Admin->where("username='".$data['username']."'")->count();
							if(!$result){
								//检验数据
								$data = $this->Admin->create($data, 1); //1是插入操作，0是更新操作
								$u_result = $this->Admin->add($data);
								if(false !== $u_result){
									//新增管理员成功，开始执行绑定角色
									if($this->RoleUser->add(array("role_id"=>$role_id,"user_id"=>$u_result))){
										$this->success("新增管理员成功！", U('Access/index'));
									}else{
										$this->success("警告:管理员新增成功,管理员角色绑定失败！", U('Access/index'));
										//此处抛出异常并记录异常日志！
										
									}
								} else {
									$this->error($this->Admin->getError(),U('Access/mAdd'));
								}

							}else{
								$this->error('管理员'.$data['nickname'].'已经存在！',U('Access/index'));
							}
						}else{
							$this->error('邮箱格式不正确！',U('Access/mAdd'));
						}  
					}else{
						$this->error('邮箱不能为空！',U('Access/mAdd'));
					}
				}else{
					$this->error('两次密码不一致！',U('Access/mAdd'));
				}
			}else{
				$this->error('用户名不能为空！',U('Access/mAdd'));
			}

		} else {
			//分配导航栏当前位置
			$this->assign('navigation_bar','权限管理>新增用户信息');
			$this->assign('act',strtolower(ACTION_NAME));
			$list = $this->Role->select();
			//获取当前用户权限
			$s_id = session(C('USER_AUTH_KEY'));
			if(empty($s_id)){
				$this->error("帐号异常，请重新登录", U("Public/index"));
			}

			$role_user_info = $this->RoleUser->getRoleInfoById($s_id);
			$tree_list = array();
			//显示当前管理员等级往下递减的所有角色可选
			$arrayHelper = new \Org\Util\ArrayHelper();
			$tree = $arrayHelper::toTree($list, 'id', 'pid', 'children');
			$tree_list = $arrayHelper::treeToHtmlForAdd($tree,"children",empty($role_user_info[0]['id'])?0:intval($role_user_info[0]['id']));
			$this->assign('tree_list',$tree_list);
			$this->assign('tree_list_empty','<option value="0">暂无数据</option>');
			//p($tree_list);
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
				'sex' => 0,
				'status' => 1,
				'remark' => '',
			);
			$this->assign('user_info', $empty_info);
			$this->display("Access:adminEdit");
		}
	}

	/**
      +----------------------------------------------------------
     * 显示修改管理员信息页面
      +----------------------------------------------------------
     */
	public function mEdit(){

		if (IS_POST) {
			$data = array();
			$data['aid'] = intval($_POST['id']) ? intval($_POST['id']) : 0 ;
			$username = isset($_POST['username']) ? trim($_POST['username']) : '' ;
			$data['password'] = isset($_POST['password']) ? trim($_POST['password']) : '' ;
			$data['repwd'] = isset($_POST['repwd']) ? trim($_POST['repwd']) : '' ;
			$role_id = isset($_POST['role_id']) ? intval($_POST['role_id']) : 0 ;
			$data['email'] = isset($_POST['email']) ? trim($_POST['email']) : '' ;
			$data['nickname'] = isset($_POST['nickname']) ? trim($_POST['nickname']) : '' ;
			$data['mobile_number'] = isset($_POST['mobile_number']) ? floatval($_POST['mobile_number']) : 0 ;
			$data['qq'] = isset($_POST['qq']) ? floatval($_POST['qq']) : 0 ;
			$data['sex'] = isset($_POST['sex']) ? intval(trim($_POST['sex'])) : 0 ;
			$data['status'] = isset($_POST['status']) ? intval(trim($_POST['status'])) : 1 ;
			$data['remark'] = isset($_POST['remark']) ? trim($_POST['remark']) : '' ;
			$data['update_time'] = time();
			$data['operator_id'] = session(C('USER_AUTH_KEY'));
			$data['__hash__'] = $_POST['__hash__'];

			//检测初始数据是否符合规则(密码是否合格、邮箱是否添加、是否重名)
			if($data['aid']){
				if($data['password']){
					if($data['password'] == $data['repwd']){
						$data['pwd'] = encrypt($data['password']);
						unset($data['password']);
						unset($data['repwd']);
					}else{
						$this->error('两次密码不一致！',U('Access/mEdit','id='.$data['aid']));
					}
				}else{
					//密码未修改
					unset($data['password']);
					unset($data['repwd']);
				}

				if(!empty($data['email'])){
					$pattern = '/^([a-zA-Z0-9]+[\._-]?[a-zA-Z0-9]+)+@[a-zA-Z0-9]+(\.[a-zA-Z0-9])+/i';  
					if(!preg_match($pattern,$data['email'])){
						$this->error('邮箱格式不正确！',U('Access/mEdit'));
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

				//开始执行更新	
				$result = $this->Admin->where("aid='".$data['aid']."'")->count();
				if($result){
					//检验数据
					$data = $this->Admin->create($data, 0); //1是插入操作，0是更新操作
					$u_result = $this->Admin->where("aid=" . $data['aid'])->save($data);
					if(false !== $u_result){
						//编辑管理员成功，开始角色变更
						$this->RoleUser->where("user_id = ".$data['aid'])->delete();
						if($this->RoleUser->add(array("role_id"=>$role_id,"user_id"=>$data['aid']))){
							$this->success("编辑用户成功！", U('Access/index'));
						}else{
							$this->success("警告:管理员新增成功,管理员角色绑定失败！", U('Access/index'));
							//此处抛出异常并记录异常日志！
							
						}						
					} else {
						$this->error($this->Admin->getError(),U('Access/mEdit','id='.$data['aid']));
					}

				}else{
					$this->error('用户'.$data['nickname'].'已经不存在！',U('Access/index'));
				}

			}else{
				$this->error('用户记录丢失！',U('Access/index'));
			}

		} else {
			// 分配导航栏当前位置
			$this->assign('navigation_bar','权限管理>编辑用户信息');
			$this->assign('act',strtolower(ACTION_NAME));
			$id = isset($_GET['id']) && intval($_GET['id']) ? intval($_GET['id']) : $this->error('参数错误！',U('Access/index'));
			// $user_info = $this->Admin->where('aid='.$id)->find();
			$user_info = $this->Admin->field('a.aid,a.nickname,a.email,a.username,a.sex,a.birthday,a.avatar,a.mobile_number,a.qq,a.personal_website,a.status,a.remark,a.last_login_time,a.last_login_ip,a.create_time,r.id as r_id,r.name as r_name,r.pid as r_pid,r.status as r_status,r.remark as r_remark')->join('as a LEFT JOIN td_role_user as ru on a.aid = ru.user_id')->join('LEFT JOIN td_role as r ON ru.role_id = r.id')->where('aid='.$id)->find();
			$this->assign('user_info', $user_info);
			// 获取当前用户权限
			$s_id = session(C('USER_AUTH_KEY'));
			if(empty($s_id)){
				$this->error("帐号异常，请重新登录", U("Public/index"));
			}
			$list = $this->Role->select();
			$role_user_info = $this->RoleUser->getRoleInfoById($s_id);
			$tree_list = array();
			// 显示当前管理员等级往下递减的所有角色
			$arrayHelper = new \Org\Util\ArrayHelper();
			$tree = $arrayHelper::toTree($list, 'id', 'pid', 'children');
			$tree_list = $arrayHelper::treeToHtmlForAdd($tree,"children",empty($role_user_info[0]['id'])?0:intval($role_user_info[0]['id']));
			// p($tree_list);
			$this->assign('tree_list',$tree_list);
			$this->assign('list_empty','<option value="0">暂无数据</option>');
			$this->display("Access:adminEdit");
		}
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
	public function ajax_restore_admin(){
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
	public function ajax_del_admin(){
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
		$this->assign('navigation_bar','权限管理>回收站管理');
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

		$count = $this->Admin->where($where)->count();
		$page = new \Think\Page($count,15);
		$show = $page->show();
		$list = $this->Admin->field('a.aid,a.nickname,a.email,a.username,a.sex,a.birthday,a.avatar,a.mobile_number,a.qq,a.personal_website,a.status,a.remark,a.last_login_time,a.last_login_ip,a.create_time,r.id as r_id,r.name as r_name,r.pid as r_pid,r.status as r_status,r.remark as r_remark')->join('as a LEFT JOIN td_role_user as ru on a.aid = ru.user_id')->join('LEFT JOIN td_role as r ON ru.role_id = r.id')->where($where)->order("last_login_time desc")->limit($page->firstRow.','.$page->listRows)->select();
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
     * 角色列表
      +----------------------------------------------------------
     */
	public function mAdminRoleList(){
		$this->assign('navigation_bar','权限管理>角色管理');
		$this->assign('act',strtolower(ACTION_NAME));
		// 关键字搜索默认值
		$keyword = '';
		// 审核状态筛选默认值
		$type = 0;		
		$where = '1 = 1';
		if(isset($_REQUEST['keyword'])){
			$keyword = $_REQUEST['keyword'];
			// 删除所有单引号
			if(stristr($keyword,'\'')){
				$keyword = str_replace('\'','',$keyword);
			}
			$new_keyword = htmlspecialchars(trim($keyword));
			$where .= " and name like '%$new_keyword%'";
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
	
		$count = $this->Role->where($where)->count();
		$page = new \Think\Page($count,20);
		$show = $page->show();
		$list = $this->Role->where($where)->order(array("id" => "desc"))->limit($page->firstRow.','.$page->listRows)->select();
		foreach ($list as $key=>$val){
			$list[$key]['key']=++$page->firstRow;
		}
		$this->assign('keyword',$keyword);
		$this->assign('type',$type);
		$this->assign('role_list',$list);
		$this->assign('page',$show);
		$this->assign('list_empty','<tr align="center"><td colspan="6" align="center"><span>角色列表为空！请先创建新角色！</span></td></tr>');
		
		// 新增页可选角色列表树
		$all_list = $this->Role->select();
		//获取当前用户权限
		$s_id = session(C('USER_AUTH_KEY'));
		if(empty($s_id)){
			$this->error("帐号异常，请重新登录", U("Public/index"));
		}
		$role_user_info = $this->RoleUser->getRoleInfoById($s_id);
		$tree_list = array();
		//显示当前管理员等级往下递减的所有角色可选
		$arrayHelper = new \Org\Util\ArrayHelper();
		$tree = $arrayHelper::toTree($all_list, 'id', 'pid', 'children');
		$tree_list = $arrayHelper::treeToHtmlForRoleAdd($tree,"children",empty($role_user_info[0]['pid'])?0:intval($role_user_info[0]['pid']));
		$this->assign('tree_list',$tree_list);
		$this->assign('tree_list_empty','<option value="0">暂无数据</option>');
		
		//p($tree_list);
		$this->display("Access:roleManage");
	}
	
	/**
      +----------------------------------------------------------
     * ajax更新角色审核状态
      +----------------------------------------------------------
     */
	public function ajax_update_role_status(){
		$dataResult = array();
		$dataResult['flag'] = 1; // 默认为1表示无任何错误
		$dataResult['msg'] = '审核状态更新成功！'; // ajax提示信息
		$dataResult['data'] = ''; // 返回数据、修改成功则返回、修改后的数据
		if (IS_AJAX) {
			$id = intval(trim($_REQUEST['id']));
			$data['aid'] = $id;
			$status = $this->Role->where($data)->getField('status');
			$set = array();
			if($status == 1){
				$set = array('status'=>0);		
			}elseif($status == 0){
				$set = array('status'=>1);
			}else{
				$set = array('status'=>0);
			}
			$u_result = $this->Role->where($data)->save($set);
			if($u_result){
				$dataResult['data'] = $set['status'];
				$this->ajaxReturn($dataResult,'JSON');
			}else{
				$dataResult['flag'] = 0; // 默认为1表示无任何错误
				$dataResult['msg'] = '审核状态更新失败！请稍后重新操作！'; // ajax提示信息
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
     * ajax新增角色页面
      +----------------------------------------------------------
     */
	public function mAdminRoleAdd(){

		if (IS_POST) {
			$data = array();
			$data['name'] = isset($_POST['name']) ? trim($_POST['name']) : '' ;
			$data['pid'] = isset($_POST['role_pid']) ? intval($_POST['role_pid']) : 0 ;
			$data['status'] = isset($_POST['status']) ? intval(trim($_POST['status'])) : 1 ;
			$data['remark'] = isset($_POST['remark']) ? trim($_POST['remark']) : '' ;
			$data['create_time'] = time();
			$data['update_time'] = time();
			$data['operator_id'] = empty($_SESSION['my_info']) ? 0 : $_SESSION['my_info']['aid'] ;
			$data['__hash__'] = $_POST['__hash__'];
			
			//检测初始数据是否符合规则
			if($data['operator_id']){
				if(!empty($data['name'])){
					$result = $this->Role->where("name='".$data['name']."'")->count();
					if(!$result){
						//检验数据
						$data = $this->Role->create($data, 1); //1是插入操作，0是更新操作
						if ($this->Role->add($data)){
							$this->success("添加角色成功！", U('Access/mAdminRoleList'));
						} else {
							$this->error($user_mod->getError(),U('Access/mAdminRoleAdd'));
						}

					}else{
						$this->error('角色'.$data['name'].'已经存在',U('Access/mAdminRoleAdd'));
					}
				}else{
					$this->error('角色名称不能为空！',U('Access/mAdminRoleAdd'));
				}
			}else{
				$this->error('请先登录！',U('Public/index'));
			}

		}else{
			//$this->error("请求异常，请重新登录！", U("Public/loginOut"));
			//分配导航栏当前位置
			$this->assign('navigation_bar','权限管理>管理员角色管理');
			$this->assign('act',strtolower(ACTION_NAME));
			if(isset($_REQUEST['keyword'])){
				$keyword = $_REQUEST['keyword'];
				$where.=" and name like '%$keyword%'";
			}
		
			$count = $this->Role->where($where)->count();
			$page = new \Think\Page($count,15);
			$show = $page->show();
			$list = $this->Role->where($where)->order(array("id" => "desc"))->limit($page->firstRow.','.$page->listRows)->select();
			foreach ($list as $key=>$val){
				$list[$key]['key']=++$page->firstRow;
			}

			
			$this->assign('keyword',$keyword);
			$this->assign('role_list',$list);
			$this->assign('page',$show);
			$this->assign('list_empty','<tr align="center"><td colspan="6" align="center"><span>角色列表为空！请先创建新角色！</span></td></tr>');

			//新增页
			$all_list = $this->Role->where($where)->order(array("id"=>"desc"))->select();
			$arrayHelper = new \Org\Util\ArrayHelper();
			$tree = $arrayHelper::toTree($all_list, 'id', 'pid', 'children');
			$tree_list = $arrayHelper::treeToHtml($tree,"children",empty($role_user_info[0]['id'])?0:intval($role_user_info[0]['id']));
			$this->assign('tree_list',$tree_list);
			$this->assign('tree_list_empty','<option value="0">暂无数据</option>');
			
			$this->display("Access:roleAdd");
		}
	}
	
	/**
      +----------------------------------------------------------
     * ajax检测角色名字
      +----------------------------------------------------------
     */
	public function ajax_check_role_name_old_160115(){
		//fwrites(APP_PATH . 'Admin/ajax.txt',"@param(ajax_check_name)---这是ajax请求！");
		$dataResult = array();
		$dataResult['flag'] = 1; //默认为1表示无任何错误
		$dataResult['msg'] = '角色名称输入正确！'; //ajax提示信息
		$dataResult['data'] = ''; //返回数据、修改成功则返回、修改后的数据

		if(IS_AJAX){
			$name = isset($_POST['name']) ? trim($_POST['name']) : '';
			if(!empty($name)){
				$result = $this->Role->where("name = '$name'")->count();
				if(!$result){
					$dataResult['flag'] = 1; //默认为1表示无任何错误
					$dataResult['msg'] = '角色名称输入正确！'; //ajax提示信息
					$dataResult['data'] = '';
					//fwrites(APP_PATH . 'Admin/ajax.txt',"名字合法！");	
				}else{
					$dataResult['flag'] = 0; //默认为1表示无任何错误
					$dataResult['msg'] = '名字重复！'; //ajax提示信息
					$dataResult['data'] = '';
					//fwrites(APP_PATH . 'Admin/ajax.txt',"名字重复！");	
				}

			}else{
				$dataResult['flag'] = 0; //默认为1表示无任何错误
				$dataResult['msg'] = '名字不能为空！'; //ajax提示信息
				$dataResult['data'] = '';
			}
		}else{
			$dataResult['flag'] = 0; //默认为1表示无任何错误
			$dataResult['msg'] = '请求非法！'; //ajax提示信息
			$dataResult['data'] = '';
		}	
		$this->ajaxReturn($dataResult,'JSON');
	}
	
	/**
      +----------------------------------------------------------
     * ajax检测广告位名称是否重复（新增模式）
      +----------------------------------------------------------
     */
	public function ajax_check_role_name(){
		//fwrites(APP_PATH . 'Admin/ajax.txt',"@param(ajax_check_name)---这是ajax请求！");
		$dataResult = array();
		$dataResult['flag'] = 1; //默认为1表示无任何错误
		$dataResult['msg'] = '角色名称不重复！'; //ajax提示信息
		$dataResult['data'] = ''; //返回数据、修改成功则返回、修改后的数据

		if(IS_AJAX){
			if(isset($_POST['name']) && !empty($_POST['name'])){
				$name = $_POST['name'];
				//删除所有单引号
				if(stristr($name,'\'')){
					$name = str_replace('\'','',$name);
				}
				$name = htmlspecialchars(trim($name));
				$result = $this->Role->where("name = '$name'")->count();
				if(!$result){
					$dataResult['flag'] = 1; //默认为1表示无任何错误
					$dataResult['msg'] = '角色名称输入正确！'; //ajax提示信息
					$dataResult['data'] = '';
					//fwrites(APP_PATH . 'Admin/ajax.txt',"名字合法！");	
				}else{
					$dataResult['flag'] = 0; //默认为1表示无任何错误
					$dataResult['msg'] = '角色名称重复！'; //ajax提示信息
					$dataResult['data'] = '';	
				}
			}else{
				$dataResult['flag'] = 0; //默认为1表示无任何错误
				$dataResult['msg'] = '名字不能为空！'; //ajax提示信息
				$dataResult['data'] = '';
			}
		}else{
			$dataResult['flag'] = 0; //默认为1表示无任何错误
			$dataResult['msg'] = '请求非法！'; //ajax提示信息
			$dataResult['data'] = '';
		}
		$this->ajaxReturn($dataResult,'JSON');
	}
	
	/**
      +----------------------------------------------------------
     * ajax检测角色名是否重复（编辑模式）
      +----------------------------------------------------------
     */
	public function ajax_check_edit_role_name(){
		//fwrites(APP_PATH . 'Admin/ajax.txt',"@param(ajax_check_name)---这是ajax请求！");
		$dataResult = array();
		$dataResult['flag'] = 1; //默认为1表示无任何错误
		$dataResult['msg'] = '角色名称不重复！'; //ajax提示信息
		$dataResult['data'] = ''; //返回数据、修改成功则返回、修改后的数据

		if(IS_AJAX){
			if(isset($_POST['name']) && !empty($_POST['name']) && isset($_POST['id']) && !empty($_POST['id'])){
				$id = intval($_POST['id']);
				$name = $_POST['name'];
				//删除所有单引号
				if(stristr($name,'\'')){
					$name = str_replace('\'','',$name);
				}
				$name = htmlspecialchars(trim($name));
				$now_adp = $this->Role->where("id = $id")->find();
				if(count($now_adp) ){
					if(!empty($now_adp['position_name']) && $now_adp['position_name'] == $name){
						$dataResult['flag'] = 1; //默认为1表示无任何错误
						$dataResult['msg'] = '角色名称输入正确！'; //ajax提示信息
						$dataResult['data'] = '';
					}else{
						$result = $this->Role->where("name = '$name'")->count();
						if(!$result){
							$dataResult['flag'] = 1; //默认为1表示无任何错误
							$dataResult['msg'] = '角色名称输入正确！'; //ajax提示信息
							$dataResult['data'] = '';
							//fwrites(APP_PATH . 'Admin/ajax.txt',"名字合法！");	
						}else{
							$dataResult['flag'] = 0; //默认为1表示无任何错误
							$dataResult['msg'] = '角色名称重复！'; //ajax提示信息
							$dataResult['data'] = '';	
						}
					}
				}else{
					$dataResult['flag'] = 0; //默认为1表示无任何错误
					$dataResult['msg'] = '该角色已经不存在！'; //ajax提示信息
					$dataResult['data'] = '';
				}
			}else{
				$dataResult['flag'] = 0; //默认为1表示无任何错误
				$dataResult['msg'] = '名字不能为空！'; //ajax提示信息
				$dataResult['data'] = '';
			}
		}else{
			$dataResult['flag'] = 0; //默认为1表示无任何错误
			$dataResult['msg'] = '请求非法！'; //ajax提示信息
			$dataResult['data'] = '';
		}
		$this->ajaxReturn($dataResult,'JSON');
	}

	/**
      +----------------------------------------------------------
     * ajax显示修改角色信息编辑页面
      +----------------------------------------------------------
     */
	public function mAdminRoleEdit(){

		if (IS_POST) {
			$data = array();
			$data['id'] = intval($_POST['id']) ? intval($_POST['id']) : 0 ;
			$data['name'] = isset($_POST['name']) ? trim($_POST['name']) : '' ;
			$data['pid'] = isset($_POST['role_pid']) ? intval($_POST['role_pid']) : 0 ;
			$data['status'] = isset($_POST['status']) ? intval(trim($_POST['status'])) : 1 ;
			$data['remark'] = isset($_POST['remark']) ? trim($_POST['remark']) : '' ;
			$data['update_time'] = time();
			$data['operator_id'] = empty($_SESSION['my_info']) ? 0 : $_SESSION['my_info']['aid'] ;
			$data['__hash__'] = $_POST['__hash__'];
			
			//检测初始数据是否符合规则
			if($data['id']){

				if(!$data['name']){
					unset($data['nickname']);
				}

				if(!$data['remark']){
					unset($data['remark']);
				}

				//开始执行更新	
				$result = $this->Role->where("id='".$data['id']."'")->count();
				if($result){
					//检验数据
					$data = $this->Role->create($data, 0); //1是插入操作，0是更新操作
					$r_result = $this->Role->where("id=" . $data['id'])->save($data);
					if(false != $r_result){
						$this->success("编辑角色信息成功！", U('Access/mAdminRoleList'));
					} else {
						$this->error($this->Role->getError(),U('Access/roleEdit','id='.$data['id']));
					}

				}else{
					$this->error('角色'.$data['name'].'已经不存在！',U('Access/mAdminRoleList'));
				}

			}else{
				$this->error('数据有误！',U('Access/mAdminRoleList'));
			}

		} else {
			//分配导航栏当前位置
			$this->assign('navigation_bar','角色管理>编辑角色信息');
			$this->assign('act',strtolower(ACTION_NAME));
			$id = isset($_GET['id']) && intval($_GET['id']) ? intval($_GET['id']) : $this->error('参数错误',U('Access/index'));
			$role_info = $this->Role->where('id='.$id)->find();
			$this->assign('role_info', $role_info);
			$list = $this->Role->select();
			//获取当前用户权限
			$s_id = session(C('USER_AUTH_KEY'));
			if(empty($s_id)){
				$this->error("帐号异常，请重新登录", U("Public/index"));
			}

			$role_user_info = $this->RoleUser->getRoleInfoById($s_id);
			$tree_list = array();
			//站长可以编辑自己否
			if($role_info['pid'] == 0){
				$role_info['level'] = 0;
				$role_info['html'] = '';
				$role_info['is_edit'] = 0;
				$tree_list[] = $role_info;
			}else{
				$arrayHelper = new \Org\Util\ArrayHelper();
				$tree = $arrayHelper::toTree($list, 'id', 'pid', 'children');
				$tree_list = $arrayHelper::treeToHtml($tree,"children",empty($role_user_info[0]['id'])?0:intval($role_user_info[0]['id']));
			}
			
			$this->assign('tree_list',$tree_list);
			$this->assign('tree_list_empty','<option value="0">暂无数据</option>');
			$this->display("Access:roleEdit");
		}
	}

	/**
      +----------------------------------------------------------
     * 角色成员管理
      +----------------------------------------------------------
     */
	public function mAdminRoleMember(){
		$this->assign('navigation_bar','角色管理>角色成员管理');
		$this->assign('act',strtolower(ACTION_NAME));
		$id = isset($_GET['id']) && intval($_GET['id']) ? intval($_GET['id']) : $this->error('参数错误',U('Access/index'));
		$this->assign('id',$id);
		// 获取当前用户权限
		$s_id = session(C('USER_AUTH_KEY'));
		if(empty($s_id)){	
			$this->error("帐号异常，请重新登录", U("Public/index"));
		}
		
		$list = $this->Role->select();
		$role_user_info = $this->RoleUser->getRoleInfoById($s_id);
		$tree_list = array();
		// 显示当前管理员等级往下递减的所有角色
		$arrayHelper = new \Org\Util\ArrayHelper();
		$tree = $arrayHelper::toTree($list, 'id', 'pid', 'children');
		$tree_list = $arrayHelper::treeToHtmlForAdd($tree,"children",empty($role_user_info[0]['id'])?0:intval($role_user_info[0]['id']));
		$this->assign('tree_list',$tree_list);
		$this->assign('list_empty','<option value="0">暂无数据</option>');
		
		// 获取当前角色用户列表
		$role_user_list = $this->Admin->field('a.aid,a.username,a.avatar,a.mobile_number,ru.role_id,ru.user_id')->join('as a RIGHT JOIN td_role_user as ru ON a.aid = ru.user_id')->where('ru.role_id = '.$id)->select();
		// 获取所有可选角色及其绑定用户
		$tmp_sql = '';
		if(count($tree_list)){
			foreach($tree_list as $key=>$value){
				if($value['is_edit'] == 1){
					$tmp_sql .= "UNION SELECT a.aid,a.username,a.avatar,a.mobile_number,ru.role_id as r_id,r.name as r_name,r.pid as r_pid,r.status as r_status FROM td_admin as a RIGHT JOIN td_role_user as ru ON a.aid = ru.user_id LEFT JOIN td_role as r on r.id = ru.role_id where a.status = 1 and a.is_del = 0 and ru.role_id = ".$value['id']." ";					
				}
			}
		}
		$role_user_arr = array();
		if($tmp_sql && strlen($tmp_sql) > 5){
			$sql = substr($tmp_sql,5);
			$M = M();
			$all_user_list = $M->query($sql);
			// p($all_user_list);
			$all_user_list = $arrayHelper::new_array_unique('aid',$all_user_list,array('r_id','r_name','r_pid','r_status'));
			// p($all_user_list);
			// p('----------------------------------------------------------');
			// p($role_user_list);
			if(count($role_user_list)){
				if(count($all_user_list)){
					foreach($all_user_list as $ke=>$val){
						$all_user_list[$ke]['is_visible'] = 0;
						foreach($role_user_list as $key=>$value){
							if($val['aid'] == $value['aid']){
								//unset($all_user_list[$ke]);
								$all_user_list[$ke]['is_visible'] = 1;
							}
						}
					}
					$role_user_arr = $all_user_list;
				}
			}else{
				$role_user_arr = $all_user_list;
			}
		}
		$this->assign('role_user_list',$role_user_arr);
		$this->assign('role_user_list_empty',"<li class='ru_list_0'><span><a class='li_username' href='javascript:void(0)'>暂无数据</a>&nbsp;&nbsp;&nbsp;<a class='cu_del_button' aid='0' role_id='0'  href='javascript:void(0)'></span></li>");
		
		// 获取所有可选用户
		$tmp_list = $this->Admin->field('a.aid,a.nickname,a.email,a.username,a.sex,a.birthday,a.avatar,a.mobile_number,a.qq,a.personal_website,a.status,a.remark,a.last_login_time,a.last_login_ip,a.create_time,r.id as r_id,r.name as r_name,r.pid as r_pid,r.status as r_status')->join('as a LEFT JOIN td_role_user as ru on a.aid = ru.user_id')->join('LEFT JOIN td_role as r ON ru.role_id = r.id')->where("a.status = 1 and a.is_del = 0")->order("a.last_login_time desc")->select();
		$user_arr = array();
		if($tmp_list && count($tmp_list)){
			foreach($tmp_list as $ke=>$val){
				$tmp_list[$ke]['is_visible'] = 1;
				if(count($role_user_list)){
					foreach($role_user_list as $key=>$value){
						if($val['aid'] == $value['aid']){
							// unset($tmp_list[$ke]);
							$tmp_list[$ke]['is_visible'] = 0;
						}
					}
				}
				// 删除超管
				if($val['aid'] == $this->superAdminId){
					unset($tmp_list[$ke]);
				}
			}
			$user_arr = $tmp_list;
		}else{
			$user_arr = $tmp_list;
		}
		$user_arr = $arrayHelper::new_array_unique('aid',$user_arr,array('r_id','r_name','r_pid','r_status'));
		// p($user_arr);
		$this->assign('user_list',$user_arr);
		$this->assign('user_list_empty',"<li class='user_list_0'><span><a class='li_username' href='javascript:void(0)'>暂无数据</a>&nbsp;&nbsp;&nbsp;<input type='checkbox' name='select_role_user' role_id='0' value='0' /></span></li>");

		$this->display("Access:roleMember");
	}

	/**
      +----------------------------------------------------------
     * ajax添加成员
      +----------------------------------------------------------
     */
	public function ajax_add_role_user(){
		$dataResult = array();
		$dataResult['flag'] = 1; // 默认为1表示无任何错误
		$dataResult['msg'] = '添加成员成功！'; // ajax提示信息
		$dataResult['data'] = ''; // 返回数据、修改成功则返回、修改后的数据
		if (IS_AJAX) {
			C('TOKEN_ON',false);
			// fwrites(APP_PATH . 'Admin/ajax.txt','@param---ajax_add_role_user start');
			// fwrites(APP_PATH . 'Admin/ajax.txt',$_POST);
			$data = array();
			$data['rid'] = isset($_POST['rid']) ? intval($_POST['rid']) : 0 ;
			$data['role_id'] = isset($_POST['role_id']) ? intval($_POST['role_id']) : 0 ;
			$aid = isset($_POST['aid']) ? trim($_POST['aid']) : 0 ;
			$data['__hash__'] = $_POST['__hash__'];
			if($data['rid'] && $aid && $data['role_id']){
				if(strpos($aid,'_')){
					// 多个
					$tmp_msg = '';
					$tmp_id = '';
					$tmp_flag = true;
					$arr = explode('_',$aid);
					foreach($arr as $key=>$value){
						$data['user_id'] = intval($value);
						$result = $this->RoleUser->where("role_id = ".$data['role_id']." and user_id = ".$data['user_id'])->count();
						if(!$result){
							// 检验数据
							$data = $this->RoleUser->create($data, 1); // 1是插入操作，0是更新操作
							$ru_result = $this->RoleUser->add($data);
							if($ru_result){
								// fwrites(APP_PATH . 'Admin/ajax.txt',$ru_result);
								$dataResult['data'] = $ru_result;
								$tmp_msg .= $data['user_id']."添加成功！\n";
								$tmp_id .= $data['user_id']."_".$data['role_id'].",";
							}else{
								$tmp_flag = false;
								$tmp_msg .= $data['user_id']."添加失败！".$this->RoleUser->getError()."\n";
								$tmp_id .= "0_".$data['user_id'].",";
							}
							
						}else{
							$tmp_flag = false;
							$tmp_msg .= $data['user_id']."添加失败！重复添加！\n";
							$tmp_id .= "0,";
						}
					}
					if($tmp_flag){
						if($tmp_id && strpos($tmp_id,',')){
							$dataResult['data'] = substr($tmp_id,0,-1);
						}else{
							$dataResult['msg'] = '网络错误！请刷新重试！';
							$dataResult['data'] = '';
						}
					}else{
						$dataResult['flag'] = 0; //默认为1表示无任何错误
						$dataResult['msg'] = $tmp_msg; //ajax提示信息
						$dataResult['data'] = '';
					}
					$this->ajaxReturn($dataResult,'JSON');
					
				}else{
					// 单个
					// fwrites(APP_PATH . 'Admin/ajax.txt','----------------单个');
					$data['user_id'] = intval($aid);
					// fwrites(APP_PATH . 'Admin/ajax.txt',$data);
					$result = $this->RoleUser->where("role_id = ".$data['role_id']." and user_id = ".$data['user_id'])->count();
					if(!$result){
						// 检验数据
						$data = $this->RoleUser->create($data, 1); // 1是插入操作，0是更新操作
						$ru_result = $this->RoleUser->add($data);
						if($ru_result){
							// fwrites(APP_PATH . 'Admin/ajax.txt',$dataResult);
							$dataResult['data'] = $data['user_id']."_".$data['role_id'];
							$this->ajaxReturn($dataResult,'JSON');
						}else{
							$dataResult['flag'] = 0; //默认为1表示无任何错误
							$dataResult['msg'] = '添加失败！'.$this->RoleUser->getError(); //ajax提示信息
							$dataResult['data'] = '';
							$this->ajaxReturn($dataResult,'JSON');
						}
					}else{
						$dataResult['flag'] = 0; //默认为1表示无任何错误
						$dataResult['msg'] = '重复添加该成员！'; //ajax提示信息
						$dataResult['data'] = '';
						$this->ajaxReturn($dataResult,'JSON');
					}
				}
			}else{
				$dataResult['flag'] = 0; // 默认为1表示无任何错误
				$dataResult['msg'] = '添加成员失败！请刷新重试！'; // ajax提示信息
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
     * ajax删除成员
      +----------------------------------------------------------
     */
	public function ajax_del_role_user(){
		$dataResult = array();
		$dataResult['flag'] = 1; // 默认为1表示无任何错误
		$dataResult['msg'] = '删除成员成功！'; // ajax提示信息
		$dataResult['data'] = ''; // 返回数据、修改成功则返回、修改后的数据
		if (IS_AJAX) {
			C('TOKEN_ON',false);
			fwrites(APP_PATH . 'Admin/ajax.txt',$_POST);
			$data = array();
			$data['user_id'] = isset($_POST['id']) ? intval($_POST['id']) : 0 ;
			$data['role_id'] = isset($_POST['role_id']) ? intval($_POST['role_id']) : 0 ;
			fwrites(APP_PATH . 'Admin/ajax.txt',$data);
			fwrites(APP_PATH . 'Admin/ajax.txt',$ru_info);
			if($data['user_id']){
				$ru_info = $this->RoleUser->where($data)->find();
				fwrites(APP_PATH . 'Admin/ajax.txt',$ru_info);
				if($ru_info){
					$a_result = $this->RoleUser->where("role_id = ".$data['role_id']." and user_id = ".$data['user_id'])->delete();
					if($a_result){
						fwrites(APP_PATH . 'Admin/ajax.txt','角色删除成功！');
						fwrites(APP_PATH . 'Admin/ajax.txt',$ru_info);
						$dataResult['data'] = $ru_info; // 返回数据、修改成功则返回、修改后的数据
						$this->ajaxReturn($dataResult,'JSON');
					}else{
						$dataResult['flag'] = 0; // 默认为1表示无任何错误
						$dataResult['msg'] = '删除失败！请稍后重新操作！'; // ajax提示信息
						$dataResult['data'] = 'err_no'; // 返回数据、修改成功则返回、修改后的数据
						$this->ajaxReturn($dataResult,'JSON');
					}
				}else{
					$dataResult['flag'] = 0; // 默认为1表示无任何错误
					$dataResult['msg'] = '操作失败！该用户已经被移出！'; // ajax提示信息
					$dataResult['data'] = 'err_no'; // 返回数据、修改成功则返回、修改后的数据
					$this->ajaxReturn($dataResult,'JSON');
				}
				
			}else{
				$dataResult['flag'] = 0; // 默认为1表示无任何错误
				$dataResult['msg'] = '删除成员失败！请刷新重试！'; // ajax提示信息
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
     * 登录日志
      +----------------------------------------------------------
     */
	public function mLoginLog(){
		$this->display("Access:loginLog");
	}

	/**
      +----------------------------------------------------------
     * 管理员角色权限设置
      +----------------------------------------------------------
     */
	public function mRoleAuthorization(){
		
		if(IS_POST){
			//提交角色权限更改
			// p("@param---url(mRoleAuthorization)---start!");
			// p($_POST);
			$data = array();
			$data['role_id'] = isset($_POST['id']) ? intval($_POST['id']) : 0 ;
			$node_arr = isset($_POST['action_code']) ? (is_array($_POST['action_code']) ? array_unique($_POST['action_code']) : array(intval($_POST['action_code']))) : array() ;
			// p($node_arr);
			if($data['role_id'] > 0){
				if(count($node_arr)){
					if (!$this->Access->autoCheckToken($data)){
						$del_num = $this->Access->where($data)->delete();
						if($del_num === false){
							$this->error('数据出错啦！！！请尝试刷新重新编辑！',U('Access/mRoleAuthorization','id='.$data['role_id']));
						}else{
							// 执行插入
							$tmp_msg = '';
							$tmp_flag = true;
							foreach($node_arr as $key=>$value){
								$tmp_node_info = $this->Node->where("id=".$value)->field('id,name,title,status,type,pid,level')->find();
								if(!empty($tmp_node_info)){
									$data['node_id'] = intval($value);
									$data['level'] = $tmp_node_info['level'];
									$data['pid'] = $tmp_node_info['pid'];
									$data['module'] = $tmp_node_info['name'];
									$n_result = $this->Access->field('role_id,node_id,level,pid,module')->data($data)->add();
									if($n_result){
										$tmp_msg .= "权限节点 ".$value."添加成功！\n";
									}else{
										$tmp_flag = false;
										$tmp_msg .= "权限节点 ".$value." 添加失败！".$this->Access->getError()."\n";
									}
								}else{
									$tmp_flag = false;
									$tmp_msg .= "权限节点 ".$value." 添加失败！未查找到对应节点信息！".$this->Node->getError()."\n";
								}
							}
							if($tmp_flag){
								$this->success("编辑角色权限成功！\n".$tmp_msg, U('Access/mAdminRoleList'),15);
							}else{
								$this->error("编辑角色权限失败！\n".$tmp_msg,U('Access/mRoleAuthorization','id='.$data['role_id']),15);
							}
						}
					}else{
						$this->error($this->Access->getError(),U('Access/mRoleAuthorization'),'id='.$data['role_id']);
					}
					
				}else{
					// 清空所有权限
					if (!$this->Access->autoCheckToken($data)){
						$del_num = $this->Access->where($data)->delete();
						if($del_num == 0 || $del_num > 0){
							$this->success("编辑角色权限成功！", U('Access/mAdminRoleList'));
						}else{
							$this->error($this->Access->getError(),U('Access/mAdminRoleList'));
						}
					}else{
						$this->error($this->Access->getError(),U('Access/mAdminRoleList'));
					}
				}
			}else{
				$this->error('数据有误！',U('Access/mAdminRoleList'));
			}	
		}else{

			$roleid = I('get.id', 0, 'intval');
			if (!$roleid) {
				$this->error("参数错误！");
			}
			$this->assign('navigation_bar','权限管理>角色权限设置');
			$this->assign('act',strtolower(ACTION_NAME));
			$this->assign('id',$roleid);
			//获取权限列表树
			$node_list = $this->Node->where(array("status" => "1"))->field("id,name,title,status,type,remark,sort,pid,level")->order("pid asc,level asc,sort asc")->select();
			$access_list = $this->Access->where(array("role_id" => $roleid))->field("role_id,node_id,level,pid,module")->select();
			//判断是否是已有权限
			if(is_array($node_list) && count($node_list)){
				foreach($node_list AS $key=>$value){
					$node_list[$key]['is_executive_power'] = 0;
					if(is_array($access_list) && count($access_list)){
						foreach($access_list AS $ke=>$val){
							if($val['node_id'] == $value['id'] && $val['pid'] == $value['pid'] && $val['level'] == $value['level']){
								$node_list[$key]['is_executive_power'] = 1;	
							}
						}
					}
				}
			}

			//调用公用函数库自动生成权限列表树
			//import('Common.Functions.arrayHelper',WEB_ROOT,'.php'); //导入框架外部自定制函数库(数组处理)
			$arrayHelper = new \Org\Util\ArrayHelper();
			$tree_list = $arrayHelper::toTree($node_list, 'id', 'pid', 'childrens');
			$this->assign('list',$tree_list);

			$this->assign('list_empty','<tr align="center"><td colspan="2" align="center"><span>管理员权限列表为空！请先创建操作节点表！</span></td></tr>');
			$this->display("Access:roleAuthorization");
		}

	}

	//管理员角色成员管理
	public function mRoleMember(){
		$this->assign('navigation_bar','权限管理>管理员角色成员管理');
		
		$this->display("Access:roleMember");
	}

	//管理员角色成员管理
	public function mRoleEdit(){
		$this->assign('navigation_bar','权限管理>编辑管理员角色信息');
		
		$this->display("Access:roleEdit");
	}

	//管理员角色成员管理
	public function ajax_del_role(){

	}

	public function mMyInfo(){
		$this->display("Access:roleMember");
	}
	

}

?>