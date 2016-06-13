<?php
/**
 * 财务管理&公司管理
 * ============================================================================
 * 版权所有 2015-2080 Lessismore，并保留所有权利。
 * 网站地址: http://www.trydemo.net；
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和
 * 使用；不允许对程序代码以任何形式任何目的的再发布。
 * ============================================================================
 * $Author: lsq & Lessismore & D.Apache.Luo
 * $Id: FinanceController.class.php 2016-1-22 Lessismore $
*/
namespace Admin\Controller;
use Think\Controller;
use Think\Model;
class FinanceController extends CommonController {
	
	protected  $Company, $CompanyUser, $Admin, $RoleUser, $Role;
	// 主管角色ID
	protected $t_role_id = 15;
	// 前台角色
	protected $t_role_q_id = 7;
	// 乙方
	protected $t_role_b_id = 12;
	/**
      +----------------------------------------------------------
     * 初始化
     * 如果 继承本类的类自身也需要初始化那么需要在使用本继承类的类里使用parent::_initialize();
      +----------------------------------------------------------
     */
	public function _initialize() {
		parent::_initialize();
		$this->Company = D("Company");
		$this->CompanyUser = D("CompanyUser");
		$this->Admin = D("Admin");
		$this->Role = D("Role");
		$this->RoleUser = D("RoleUser");
	}
	
	/**
      +----------------------------------------------------------
     * 显示公司列表
      +----------------------------------------------------------
     */
	public function index(){
		$this->assign('act',strtolower(ACTION_NAME));
		// 分配导航栏当前位置
		$this->assign('navigation_bar','财务管理>公司管理');
		// 回收站关键字(默认为非回收站)
		$mTrash_act = 0;
		// 关键字搜索默认值
		$keyword = '';
		// 审核状态筛选默认值
		$type = 0;
		$where = 'c.is_del = 0';
		if(isset($_REQUEST['keyword'])){
			$keyword = $_REQUEST['keyword'];
			// 删除所有单引号
			if(stristr($keyword,'\'')){
				$keyword = str_replace('\'','',$keyword);
			}
			$new_keyword = htmlspecialchars(trim($keyword));
			$where .= " and c.name like '%$new_keyword%'";
		}
		
		if(isset($_REQUEST['dropdown_type'])){
			$type = intval(htmlspecialchars(trim($_REQUEST['dropdown_type'])));
			switch($type){
				case 1:
					$where .= " and c.status = 1";
					break;
				case 2:
					$where .= " and c.status = 0";
					break;
				default:
					break;
			}
		}

		$count = $this->Company->join('as c LEFT JOIN td_admin as a ON a.aid = c.aid')->where($where)->count();
		$page = new \Think\Page($count,15);
		$show = $page->show();
		/*
		$list = $this->Company->where($where)->order("last_login_time desc")->limit($page->firstRow.','.$page->listRows)->select();
		if(is_array($list) && count($list)){
			foreach ($list as $key=>$val){
				$list[$key]['key']=++$page->firstRow;
			}
		}
		*/
		$list = $this->Company->field('c.cid,c.name,c.email,c.phone,c.address,c.business_license,c.company_amounts,c.aid,c.remark,c.type,c.status,c.is_del,c.create_time,c.update_time,a.username as a_username')->join('as c LEFT JOIN td_admin as a ON a.aid = c.aid')->where($where)->order("c.create_time desc")->limit($page->firstRow.','.$page->listRows)->select();
		if(is_array($list) && count($list)){
			foreach ($list as $key=>$val){
				$list[$key]['key']=++$page->firstRow;
			}
		}
		$this->assign('keyword',$keyword);
		$this->assign('type',$type);
		$this->assign('list_empty','<tr align="center"><td colspan="10" align="center"><span>公司列表为空！请先创建新公司！</span></td></tr>');
		$this->assign('list',$list);
		$this->assign('page',$show);
		$this->display();
	}

	/**
      +----------------------------------------------------------
     * ajax更改公司审核状态
      +----------------------------------------------------------
     */
	public function ajax_update_status(){
		$dataResult = array();
		$dataResult['flag'] = 1; // 默认为1表示无任何错误
		$dataResult['msg'] = '锁定状态更新成功！'; // ajax提示信息
		$dataResult['data'] = ''; // 返回数据、修改成功则返回、修改后的数据
		if (IS_AJAX) {
			$id = intval(trim($_REQUEST['id']));
			$data['cid'] = $id;
			$status = $this->Company->where($data)->getField('status');
			$set = array();
			if($status == 1){
				$set = array('status'=>0);		
			}elseif($status == 0){
				$set = array('status'=>1);
			}else{
				$set = array('status'=>0);
			}
			$u_result = $this->Company->where($data)->save($set);
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
     * 显示添加新公司界面
      +----------------------------------------------------------
     */
	public function mAdd(){
		if (IS_POST) {
			$data = array();
			$data['name'] = isset($_POST['company_name']) ? trim($_POST['company_name']) : '' ;
			$data['email'] = isset($_POST['email']) ? trim($_POST['email']) : '' ;
			$data['phone'] = isset($_POST['phone']) ? trim($_POST['phone']) : '' ;
			$data['address'] = isset($_POST['address']) ? trim($_POST['address']) : '' ;
			$data['website'] = isset($_POST['website']) ? trim($_POST['website']) : '' ;
			$data['company_amounts'] = isset($_POST['company_amounts']) ? round(floatval($_POST['company_amounts']),2) : 0.00;
			$data['aid'] = isset($_POST['aid']) ? intval($_POST['aid']) : 0 ;
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
			$data['__hash__'] = $_POST['__hash__'];

			//检测初始数据是否符合规则
			if(!empty($data['name'])){
				$result = $this->Company->where("name='".$data['name']."'")->count();
				if(!$result){
					if(!empty($data['email'])){
						$pattern = '/^([a-zA-Z0-9]+[\._-]?[a-zA-Z0-9]+)+@[a-zA-Z0-9]+(\.[a-zA-Z0-9])+/i';  
						if(preg_match($pattern,$data['email'])){
							if (!empty($_FILES)){
								$config = array(
									'maxSize' => 3145728,
									'rootPath' => './Uploads/Image/',
									'savePath' => '',
									'saveName' => array('uniqid',''),
									'exts' => array('jpg', 'gif', 'png', 'jpeg'),
									'autoSub' => true,
									'subName' => array('date','Ymd'),
								);
								$upload = new \Think\Upload($config,'Local');// 实例化上传类
								$info = $upload->uploadOne($_FILES['business_license']);
								if($info){
									// 图片上传成功获取图片路径和名字
									$data['business_license'] = $info['savepath'].$info['savename'];
									if(!empty($_FILES) && $_FILES['company_logo']['size'] != 0){
										$config = array(
											'maxSize' => 3145728,
											'rootPath' => './Uploads/Image/',
											'savePath' => '',
											'saveName' => array('uniqid',''),
											'exts' => array('jpg', 'gif', 'png', 'jpeg'),
											'autoSub' => true,
											'subName' => array('date','Ymd'),
										);
										$upload_obj = new \Think\Upload($config,'Local');// 实例化上传类
										$new_info = $upload_obj->uploadOne($_FILES['company_logo']);
										if($new_info){
											$data['company_logo'] = $new_info['savepath'].$new_info['savename'];
											// 检验数据
											$data = $this->Company->create($data, 1); //1是插入操作，0是更新操作
											if ($this->Company->add($data)){
												$this->success("添加公司成功！", U('Finance/index'));
											} else {
												$this->error($this->Company->getError(),U('Finance/mAdd'));
											}
										}else{
											$this->error($upload_obj->getError(),U('Finance/mAdd'));
										}
									}else{
										// 检验数据
										$data = $this->Company->create($data, 1); //1是插入操作，0是更新操作
										if ($this->Company->add($data)){
											$this->success("添加公司成功！", U('Finance/index'));
										} else {
											$this->error($this->Company->getError(),U('Finance/mAdd'));
										}
									}
								}else{
									$this->error($upload->getError(),U('Finance/mAdd'));
								}
							}else{
								$this->error('营业执照不能为空！',U('Finance/mAdd'));
							}				
						}else{
							$this->error('邮箱格式不正确！',U('Finance/mAdd'));
						}
					}else{
						$this->error('邮箱不能为空！',U('Finance/mAdd'));
					}
				}else{
					$this->error('公司名称'.$data['name'].'已经存在',U('Finance/mAdd'));
				}
			}else{
				$this->error('公司名称不能为空！',U('Finance/mAdd'));
			}	

		} else {
			// 分配导航栏当前位置
			$this->assign('navigation_bar','财务管理>添加新公司');
			$this->assign('act',strtolower(ACTION_NAME));
			// p(ACTION_NAME);
		    // 获取所有的后台公司管理角色名单
			$list = $this->Admin->field('a.aid,a.username,a.avatar')->join('as a RIGHT JOIN td_role_user as ru ON a.aid = ru.user_id')->where('a.status = 1 and is_del = 0 and ru.role_id = '.$this->t_role_id)->order("a.create_time desc")->select();
			//p($list);
			$this->assign('admin_list', $list);
			$this->assign('admin_list_empty','<option value="0">暂无数据</option>');
			// 初始化模版引擎
			$empty_info = array(
				'cid' => 0,
				'name' => '',
				'email' => '',
				'phone' => '',
				'address' => '',
				'website' => '',
				'company_amounts' => '0.00',
				'business_license' => '',
				'aid' => 0,
				'remark' => '',
				'status' => 0,
			);
			$this->assign('info', $empty_info);
			$this->display("Finance:add");
			
		}	
	}
	
	/**
      +----------------------------------------------------------
     * ajax检测公司名称是否重复（新增模式）
      +----------------------------------------------------------
     */
	public function ajax_check_name(){
		//fwrites(APP_PATH . 'Admin/ajax.txt',"@param(ajax_check_name)---这是ajax请求！");
		$dataResult = array();
		$dataResult['flag'] = 1; //默认为1表示无任何错误
		$dataResult['msg'] = '公司名称不重复！'; //ajax提示信息
		$dataResult['data'] = ''; //返回数据、修改成功则返回、修改后的数据

		if(IS_AJAX){
			if(isset($_POST['name']) && !empty($_POST['name'])){
				$name = $_POST['name'];
				//删除所有单引号
				if(stristr($name,'\'')){
					$name = str_replace('\'','',$name);
				}
				$name = htmlspecialchars(trim($name));
				$result = $this->Company->where("name = '$name'")->count();
				if(!$result){
					$dataResult['flag'] = 1; //默认为1表示无任何错误
					$dataResult['msg'] = '公司名称输入正确！'; //ajax提示信息
					$dataResult['data'] = '';	
				}else{
					$dataResult['flag'] = 0; //默认为1表示无任何错误
					$dataResult['msg'] = '公司名称重复！'; //ajax提示信息
					$dataResult['data'] = '';	
				}
			}else{
				$dataResult['flag'] = 0; //默认为1表示无任何错误
				$dataResult['msg'] = '公司名称不能为空！'; //ajax提示信息
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
     * ajax检测广告名称是否重复（编辑模式）
      +----------------------------------------------------------
     */
	public function ajax_check_edit_name(){
		//fwrites(APP_PATH . 'Admin/ajax.txt',"@param(ajax_check_name)---这是ajax请求！");
		$dataResult = array();
		$dataResult['flag'] = 1; //默认为1表示无任何错误
		$dataResult['msg'] = '公司名称不重复！'; //ajax提示信息
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
				$now_c = $this->Company->where("cid = $id")->find();
				if(count($now_c) ){
					if(!empty($now_c['name']) && $now_c['name'] == $name){
						$dataResult['flag'] = 1; //默认为1表示无任何错误
						$dataResult['msg'] = '公司名称输入正确！'; //ajax提示信息
						$dataResult['data'] = '';
					}else{
						$result = $this->Company->where("name = '$name'")->count();
						if(!$result){
							$dataResult['flag'] = 1; //默认为1表示无任何错误
							$dataResult['msg'] = '公司名称输入正确！'; //ajax提示信息
							$dataResult['data'] = '';
							//fwrites(APP_PATH . 'Admin/ajax.txt',"名字合法！");	
						}else{
							$dataResult['flag'] = 0; //默认为1表示无任何错误
							$dataResult['msg'] = '公司名称重复！'; //ajax提示信息
							$dataResult['data'] = '';	
						}
					}
				}else{
					$dataResult['flag'] = 0; //默认为1表示无任何错误
					$dataResult['msg'] = '该公司已经不存在！'; //ajax提示信息
					$dataResult['data'] = '';
				}
			}else{
				$dataResult['flag'] = 0; //默认为1表示无任何错误
				$dataResult['msg'] = '公司名字不能为空！'; //ajax提示信息
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
     * 显示编辑或者修改公司信息页面
      +----------------------------------------------------------
     */
	public function mEdit(){

		if (IS_POST) {	
			$data = array();
			$data['cid'] = intval($_POST['id']) ? intval($_POST['id']) : 0 ;
			$data['name'] = isset($_POST['company_name']) ? trim($_POST['company_name']) : '' ;
			$data['email'] = isset($_POST['email']) ? trim($_POST['email']) : '' ;
			$data['phone'] = isset($_POST['phone']) ? trim($_POST['phone']) : '' ;
			$data['address'] = isset($_POST['address']) ? trim($_POST['address']) : '' ;
			$data['website'] = isset($_POST['website']) ? trim($_POST['website']) : '' ;
			$data['company_amounts'] = isset($_POST['company_amounts']) ? round(floatval($_POST['company_amounts']),2) : 0.00;
			$data['aid'] = isset($_POST['aid']) ? intval($_POST['aid']) : 0 ;
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
			$data['__hash__'] = $_POST['__hash__'];
			//检测初始数据是否符合规则
			if($data['cid']){
				if(!empty($data['name'])){
						if(!empty($data['email'])){
							$pattern = '/^([a-zA-Z0-9]+[\._-]?[a-zA-Z0-9]+)+@[a-zA-Z0-9]+(\.[a-zA-Z0-9])+/i';  
							if(preg_match($pattern,$data['email'])){
								$result = $this->Company->where("cid='".$data['cid']."'")->find();
								if(count($result)){
									$name_count = $this->Company->where("name='".$data['name']."'")->count();
									$tmp_bool = true;
									if($result['name'] == $data['name'] && $name_count != 1){
										$tmp_bool = false;
									}elseif($result['name'] != $data['name'] && $name_count != 0){
										$tmp_bool = false;
									}
									if($tmp_bool){
										// p($_FILES);
										// fwrites(APP_PATH . 'Company/ajax.txt',$_FILES);
										$img_info;
										if (!empty($_FILES) && $_FILES['business_license']['size'] != 0){
											$config = array(
												'maxSize' => 3145728,
												'rootPath' => './Uploads/Image/',
												'savePath' => '',
												'saveName' => array('uniqid',''),
												'exts' => array('jpg', 'gif', 'png', 'jpeg'),
												'autoSub' => true,
												'subName' => array('date','Ymd'),
											);
											$upload = new \Think\Upload($config,'Local');// 实例化上传类
											$img_info = $upload->uploadOne($_FILES['business_license']);
											if($img_info){
												// 图片上传成功获取图片路径和名字
												$data['business_license'] = $img_info['savepath'].$img_info['savename'];
												if(!empty($_FILES) && $_FILES['company_logo']['size'] != 0){
													$config = array(
														'maxSize' => 3145728,
														'rootPath' => './Uploads/Image/',
														'savePath' => '',
														'saveName' => array('uniqid',''),
														'exts' => array('jpg', 'gif', 'png', 'jpeg'),
														'autoSub' => true,
														'subName' => array('date','Ymd'),
													);
													$upload_obj = new \Think\Upload($config,'Local');// 实例化上传类
													$new_info = $upload_obj->uploadOne($_FILES['company_logo']);
													if($new_info){
														$data['company_logo'] = $new_info['savepath'].$new_info['savename'];
														//检验数据
														$data = $this->Company->create($data, 0); //1是插入操作，0是更新操作
														$a_result = $this->Company->where("cid=" . $data['cid'])->save($data);
														if ($a_result){
															$this->success("编辑公司成功！", U('Finance/index'));
														} else {
															$this->error($this->Company->getError(),U('Finance/mEdit','id='.$data['cid']));
														}
													}else{
														$this->error($upload_obj->getError(),U('Finance/mEdit','id='.$data['cid']));
													}
												}else{
													//检验数据
													$data = $this->Company->create($data, 0); //1是插入操作，0是更新操作
													$a_result = $this->Company->where("cid=" . $data['cid'])->save($data);
													if ($a_result){
														$this->success("编辑公司成功！", U('Finance/index'));
													} else {
														$this->error($this->Company->getError(),U('Finance/mEdit','id='.$data['cid']));
													}
												}
											}else{
												$this->error($upload->getError(),U('Finance/mEdit','id='.$data['cid']));
											}
										}else{
											if(!empty($_FILES) && $_FILES['company_logo']['size'] != 0){
												$config = array(
													'maxSize' => 3145728,
													'rootPath' => './Uploads/Image/',
													'savePath' => '',
													'saveName' => array('uniqid',''),
													'exts' => array('jpg', 'gif', 'png', 'jpeg'),
													'autoSub' => true,
													'subName' => array('date','Ymd'),
												);
												$upload_obj = new \Think\Upload($config,'Local');// 实例化上传类
												$new_info = $upload_obj->uploadOne($_FILES['company_logo']);
												if($new_info){
													$data['company_logo'] = $new_info['savepath'].$new_info['savename'];
													//检验数据
													$data = $this->Company->create($data, 0); //1是插入操作，0是更新操作
													$a_result = $this->Company->where("cid=" . $data['cid'])->save($data);
													if ($a_result){
														$this->success("编辑公司成功！", U('Finance/index'));
													} else {
														$this->error($this->Company->getError(),U('Finance/mEdit','id='.$data['cid']));
													}
												}else{
													$this->error($upload_obj->getError(),U('Finance/mEdit','id='.$data['cid']));
												}
											}else{
												//检验数据
												$data = $this->Company->create($data, 0); //1是插入操作，0是更新操作
												$a_result = $this->Company->where("cid=" . $data['cid'])->save($data);
												if ($a_result){
													$this->success("编辑公司成功！", U('Finance/index'));
												} else {
													$this->error($this->Company->getError(),U('Finance/mEdit','id='.$data['cid']));
												}
											}
										}
									}else{
										$this->error('公司名称重复！',U('Finance/mEdit','id='.$data['cid']));
									}	
								}else{
									$this->error('公司'.$data['name'].'已经不存在！',U('Finance/index'));
								}
							}else{
								$this->error('邮箱格式不正确！',U('Finance/mEdit','id='.$data['cid']));
							}
						}else{
							$this->error('邮箱不能为空！',U('Finance/mEdit','id='.$data['cid']));
						}
				}else{
					$this->error('公司名称不能为空！',U('Finance/mEdit','id='.$data['cid']));
				}				
			}else{
				$this->error('数据有误！',U('Finance/index'));
			}

		} else {
			// 分配导航栏当前位置
			$this->assign('navigation_bar','公司管理>编辑公司信息');
			$this->assign('act',strtolower(ACTION_NAME));
			$id = isset($_GET['id']) && intval($_GET['id']) ? intval($_GET['id']) : $this->error('参数错误',U('Finance/index'));
			$company_info = $this->Company->where('cid='.$id)->find();
			$this->assign('info', $company_info);
			// 获取所有的主管角色名单
			$list = $this->Admin->field('a.aid,a.username,a.avatar')->join('as a RIGHT JOIN td_role_user as ru ON a.aid = ru.user_id')->where('a.status = 1 and is_del = 0 and ru.role_id = '.$this->t_role_id)->order("a.create_time desc")->select();
			$this->assign('admin_list', $list);
			$this->assign('admin_list_empty','<option value="0">暂无数据</option>');
			$this->display("Finance:add");
		}
	}
	
	/**
      +----------------------------------------------------------
     * 将公司移入回收站
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
				$data['cid'] = $id;
				$is_del = $this->Company->where($data)->getField('is_del');
				if($is_del == 0){
					$set = array();
					$set = array('is_del'=>1);
					$u_result = $this->Company->where($data)->save($set);
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
					$dataResult['msg'] = '重复操作！该公司已经被移入回收站！'; // ajax提示信息
					$dataResult['data'] = 'err_no'; // 返回数据、修改成功则返回、修改后的数据
					$this->ajaxReturn($dataResult,'JSON');
				}
				
			}else{
				$dataResult['flag'] = 0; // 默认为1表示无任何错误
				$dataResult['msg'] = '移入回收站失败！该公司已不存在！'; // ajax提示信息
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
     * 将公司从回收站还原
      +----------------------------------------------------------
     */
	public function ajax_restore_company(){
		$dataResult = array();
		$dataResult['flag'] = 1; // 默认为1表示无任何错误
		$dataResult['msg'] = '还原该公司成功！'; // ajax提示信息
		$dataResult['data'] = ''; // 返回数据、修改成功则返回、修改后的数据
		//fwrites(APP_PATH . 'Company/ajax.txt','开始删除！');
		if (IS_AJAX) {
			$id = intval($_POST['id']) ? intval($_POST['id']) : 0 ;
			if($id){
				$data['cid'] = $id;
				$is_del = $this->Company->where($data)->getField('is_del');
				if($is_del == 1){
					$set = array();
					$set = array('is_del'=>0);
					$u_result = $this->Company->where($data)->save($set);
					if($u_result){
						$dataResult['data'] = $set['is_del'];
						$this->ajaxReturn($dataResult,'JSON');
					}else{
						$dataResult['flag'] = 0; // 默认为1表示无任何错误
						$dataResult['msg'] = '还原公司失败！请稍后重新操作！'; // ajax提示信息
						$dataResult['data'] = 'err_no'; // 返回数据、修改成功则返回、修改后的数据
						$this->ajaxReturn($dataResult,'JSON');
					}		
				}else{
					$dataResult['flag'] = 0; // 默认为1表示无任何错误
					$dataResult['msg'] = '重复操作！该公司已经被移出回收站！'; // ajax提示信息
					$dataResult['data'] = 'err_no'; // 返回数据、修改成功则返回、修改后的数据
					$this->ajaxReturn($dataResult,'JSON');
				}	
			}else{
				$dataResult['flag'] = 0; // 默认为1表示无任何错误
				$dataResult['msg'] = '移出回收站失败！该公司已不存在！'; // ajax提示信息
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
     * ajax删除公司
      +----------------------------------------------------------
     */
	public function ajax_del_company(){
		$dataResult = array();
		$dataResult['flag'] = 1; // 默认为1表示无任何错误
		$dataResult['msg'] = '删除成功！'; // ajax提示信息
		$dataResult['data'] = ''; // 返回数据、修改成功则返回、修改后的数据
		if (IS_AJAX) {
			$data['id'] = intval($_POST['id']) ? intval($_POST['id']) : 0 ;
			$c_info = $this->Company->where($data)->find();
			if($c_info){
				$a_result = $this->Company->delete($data['id']);
				if($a_result){
					// 如果有绑定记录
					if($this->CompanyUser->where(array("cid"=>$data['id']))->count()){
						// 删除公司绑定人员
						$del_count = $this->CompanyUser->where(array("cid"=>$data['id']))->delete();
						if($del_count){
							$dataResult['data'] = 'success'; // 返回数据、修改成功则返回、修改后的数据
							$this->ajaxReturn($dataResult,'JSON');
						}else{
							$dataResult['msg'] = '删除公司成功！但清除该公司下绑定成员记录失败！'; // ajax提示信息
							$dataResult['data'] = 'err_warning'; // 返回数据、修改成功则返回、修改后的数据
							//此处记录日志
							$this->ajaxReturn($dataResult,'JSON');
						}
					}else{
						$dataResult['data'] = 'success'; // 返回数据、修改成功则返回、修改后的数据
						$this->ajaxReturn($dataResult,'JSON');
					}
				}else{
					$dataResult['flag'] = 0; // 默认为1表示无任何错误
					$dataResult['msg'] = '删除失败！请稍后重新操作！'; // ajax提示信息
					$dataResult['data'] = 'err_no'; // 返回数据、修改成功则返回、修改后的数据
					$this->ajaxReturn($dataResult,'JSON');
				}
			}else{
				$dataResult['flag'] = 0; // 默认为1表示无任何错误
				$dataResult['msg'] = '删除失败！该公司已经被删除！'; // ajax提示信息
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
     * 公司回收站管理
      +----------------------------------------------------------
     */
	public function mTrash(){
		$this->assign('act',strtolower(ACTION_NAME));
		// 分配导航栏当前位置
		$this->assign('navigation_bar','财务管理>回收站管理');
		// 回收站关键字(默认为非回收站)
		$mTrash_act = 1;
		// 关键字搜索默认值
		$keyword = '';
		// 审核状态筛选默认值
		$type = 0;
		$where = 'c.is_del = 1';
		if(isset($_REQUEST['keyword'])){
			$keyword = $_REQUEST['keyword'];
			// 删除所有单引号
			if(stristr($keyword,'\'')){
				$keyword = str_replace('\'','',$keyword);
			}
			$new_keyword = htmlspecialchars(trim($keyword));
			$where .= " and c.name like '%$new_keyword%'";
		}
		
		if(isset($_REQUEST['dropdown_type'])){
			$type = intval(htmlspecialchars(trim($_REQUEST['dropdown_type'])));
			switch($type){
				case 1:
					$where .= " and c.status = 1";
					break;
				case 2:
					$where .= " and c.status = 0";
					break;
				default:
					break;
			}
		}

		$count = $this->Company->join('as c LEFT JOIN td_admin as a ON a.aid = c.aid')->where($where)->count();
		$page = new \Think\Page($count,15);
		$show = $page->show();
		$list = $this->Company->field('c.cid,c.name,c.email,c.phone,c.address,c.business_license,c.company_amounts,c.aid,c.remark,c.type,c.status,c.is_del,c.create_time,c.update_time,a.username as a_username')->join('as c LEFT JOIN td_admin as a ON a.aid = c.aid')->where($where)->order("c.create_time desc")->limit($page->firstRow.','.$page->listRows)->select();
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
		$this->display('Finance:index');
	}

	/**
      +----------------------------------------------------------
     * 公司成员管理
      +----------------------------------------------------------
     */
	public function mCompanyMember(){
		$this->assign('navigation_bar','财务管理>公司成员管理');
		$this->assign('act',strtolower(ACTION_NAME));
		$id = isset($_GET['id']) && intval($_GET['id']) ? intval($_GET['id']) : $this->error('参数错误',U('Access/index'));
		$this->assign('id',$id);
		// 获取当前用户权限
		$s_id = session(C('USER_AUTH_KEY'));
		if(empty($s_id)){
			$this->error("帐号异常，请重新登录", U("Public/index"));
		}
		// 配置可以删除角色
		$del_role_arr = array();
		array_push($del_role_arr,$this->t_role_b_id);
		$this->assign('del_role_arr',$del_role_arr);
		// 获取前台角色子分类
		// $q_role_list = $this->Role->field('id,name,pid,status,remark,update_time,create_time,operator_id')->where('pid = '.$this->t_role_q_id)->select();
		$q_role_list = $this->Role->field('id,name,pid,status,remark,update_time,create_time,operator_id')->where('id = '.$this->t_role_b_id)->select();
		$this->assign('q_role_list',$q_role_list);
		$this->assign('q_role_list_empty','<option value="0">暂无数据</option>');
		// p($q_role_list);
		// 获取当前公司用户列表
		$company_user_list = $this->Admin->field('a.aid,a.username,a.avatar,a.mobile_number,cu.role_id,cu.id,r.name as r_name,r.pid as r_pid')->join('as a RIGHT JOIN td_company_user as cu ON a.aid = cu.aid')->join('LEFT JOIN td_role as r ON r.id = cu.role_id')->where('cu.cid = '.$id)->select();
		$this->assign('company_user_list',$company_user_list);
		$this->assign('company_user_list_empty',"<li class='cu_list_0_0'><span><a class='li_username' href='javascript:void(0)'>暂无数据</a>&nbsp;&nbsp;&nbsp;<a class='cu_del_button' role_id='0'  href='javascript:void(0)'></span></li>");
		// p($company_user_list);
		// 获取前台用户及其子集列表
		$tmp_sql = '';
		if(count($q_role_list)){
			foreach($q_role_list as $key=>$value){
				$tmp_sql .= "UNION SELECT a.aid,a.username,a.avatar,a.mobile_number,ru.role_id,r.name as r_name,r.pid as r_pid FROM td_admin as a RIGHT JOIN td_role_user as ru ON a.aid = ru.user_id LEFT JOIN td_role as r ON r.id = ru.role_id where a.status = 1 and a.is_del = 0 and ru.role_id = ".$value['id']." ";
			}
		}
		//$sql = "SELECT a.aid,a.username,a.avatar,a.mobile_number,ru.role_id FROM td_admin as a RIGHT JOIN td_role_user as ru ON a.aid = ru.user_id where a.status = 1 and a.is_del = 0 and ru.role_id = ".$this->t_role_q_id." ".$tmp_sql;
		$user_arr = array();
		if($tmp_sql && strlen($tmp_sql) > 5){
			$sql = substr($tmp_sql,5);
			$M = M();
			$all_user_list = $M->query($sql);
			if(count($company_user_list)){
				if(count($all_user_list)){
					foreach($company_user_list as $key=>$value){
						foreach($all_user_list as $ke=>$val){
							if($val['aid'] == $value['aid']){
								unset($all_user_list[$ke]);
							}
						}
					}
					$user_arr = $all_user_list;
				}else{
					// 数据有误可清除该公司下的所有用户
					
				}
			}else{
				$user_arr = $all_user_list;
			}
		}
		//p($user_arr);
		$this->assign('user_list',$user_arr);
		$this->assign('user_list_empty',"<li class='user_list_0_0'><span><a class='li_username' href='javascript:void(0)'>暂无数据</a>&nbsp;&nbsp;&nbsp;<input type='checkbox' name='select_role_user' role_id='0' value='0' /></span></li>");
		$this->display("Finance:companyMember");
	}
	
	/**
      +----------------------------------------------------------
     * ajax搜索复合范围的用户
      +----------------------------------------------------------
     */
	public function ajax_search_user(){
		$dataResult = array();
		$dataResult['flag'] = 1; // 默认为1表示无任何错误
		$dataResult['msg'] = '搜索用户成功!'; // ajax提示信息
		$dataResult['data'] = ''; // 返回数据、修改成功则返回、修改后的数据
		// fwrites(APP_PATH . 'Admin/ajax.txt','@param---ajax_search_user start');
		// fwrites(APP_PATH . 'Admin/ajax.txt',$_POST);
		if (IS_AJAX) {
			// 获取当前用户权限
			$s_id = session(C('USER_AUTH_KEY'));
			if(empty($s_id)){
				$dataResult['flag'] = 0; // 默认为1表示无任何错误
				$dataResult['msg'] = '请先登录！'; // ajax提示信息
				$dataResult['data'] = 'err_login';
			}else{
				$id = isset($_POST['id']) && intval($_POST['id']) ? intval($_POST['id']) : 0 ;
				$name = isset($_POST['name']) ? trim($_POST['name']) : '' ;
				// fwrites(APP_PATH . 'Admin/ajax.txt',"id:".$id);
				if($id){
					// 获取前台角色子分类
					// $q_role_list = $this->Role->field('id,name,pid,status,remark,update_time,create_time,operator_id')->where('pid = '.$this->t_role_q_id)->select();
					$q_role_list = $this->Role->field('id,name,pid,status,remark,update_time,create_time,operator_id')->where('id = '.$this->t_role_b_id)->select();
					// 获取当前公司用户列表
					$company_user_list = $this->Admin->field('a.aid,a.username,a.avatar,a.mobile_number,cu.role_id,cu.id')->join('as a RIGHT JOIN td_company_user as cu ON a.aid = cu.aid')->where('cu.cid = '.$id)->select();
					$tmp_sql = '';
					if($name){
						// fwrites(APP_PATH . 'Admin/ajax.txt','22222222222222222222222222');
						// 获取前台用户及其子集列表
						if(count($q_role_list)){
							foreach($q_role_list as $key=>$value){
								$tmp_sql .= "UNION SELECT a.aid,a.username,a.avatar,a.mobile_number,ru.role_id FROM td_admin as a RIGHT JOIN td_role_user as ru ON a.aid = ru.user_id where a.status = 1 and a.is_del = 0 and ru.role_id = ".$value['id']." and a.username like '%$name%' ";
							}
						}
					}else{
						// fwrites(APP_PATH . 'Admin/ajax.txt','3333333333333333333333333');
						// 获取所有前台用户及其子集列表
						if(count($q_role_list)){
							foreach($q_role_list as $key=>$value){
								$tmp_sql .= "UNION SELECT a.aid,a.username,a.avatar,a.mobile_number,ru.role_id FROM td_admin as a RIGHT JOIN td_role_user as ru ON a.aid = ru.user_id where a.status = 1 and a.is_del = 0 and ru.role_id = ".$value['id']." ";
							}
						}
					}
					$user_arr = array();
					if($tmp_sql && strlen($tmp_sql) > 5){
						$sql = substr($tmp_sql,5);
						$M = M();
						$all_user_list = $M->query($sql);
						// fwrites(APP_PATH . 'Admin/ajax.txt',$all_user_list);
						if(count($company_user_list)){
							if(count($all_user_list)){
								foreach($company_user_list as $key=>$value){
									foreach($all_user_list as $ke=>$val){
										if($val['aid'] == $value['aid']){
											unset($all_user_list[$ke]);
										}
									}
								}
								$user_arr = $all_user_list;
							}
						}else{
							$user_arr = $all_user_list;
						}
					}
					if(count($q_role_list) && count($user_arr)){
						foreach($user_arr as $key=>$value){
							$user_arr[$key]['new_name'] = $value['username'];
							foreach($q_role_list as $ke=>$val){
								if($val['id'] == $value['role_id']){
									$user_arr[$key]['new_name'] = $value['username'].'-'.$val['name'];
								}
							}
						}
					}
					// 重新排序
					$result_arr = array();
					if(count($user_arr)){
						foreach($user_arr as $key=>$value){
							$result_arr[] = $value;
						}
					}
					// fwrites(APP_PATH . 'Admin/ajax.txt',$result_arr);
					$dataResult['data'] = $result_arr;					
				}else{
					$dataResult['flag'] = 0; // 默认为1表示无任何错误
					$dataResult['msg'] = '参数错误！请刷新重试！'; // ajax提示信息
					$dataResult['data'] = 'err_no'; // 返回数据、修改成功则返回、修改后的数据
				}
			}
		}else{
			$dataResult['flag'] = 0; // 默认为1表示无任何错误
			$dataResult['msg'] = '提交失败！请稍后重新提交！'; // ajax提示信息
			$dataResult['data'] = 'err_server';
		}
		// fwrites(APP_PATH . 'Admin/ajax.txt',$dataResult);
		$this->ajaxReturn($dataResult,'JSON');
	}
	
	/**
      +----------------------------------------------------------
     * ajax添加公司成员
      +----------------------------------------------------------
     */
	public function ajax_add_company_user(){
		$dataResult = array();
		$dataResult['flag'] = 1; // 默认为1表示无任何错误
		$dataResult['msg'] = '添加成员成功！'; // ajax提示信息
		$dataResult['data'] = ''; // 返回数据、修改成功则返回、修改后的数据
		if (IS_AJAX) {
			C('TOKEN_ON',false);
			// fwrites(APP_PATH . 'Admin/ajax.txt','@param---ajax_add_company_user start');
			// fwrites(APP_PATH . 'Admin/ajax.txt',$_POST);
			$data = array();
			$data['cid'] = isset($_POST['cid']) ? intval($_POST['cid']) : 0 ;
			$checked_id = isset($_POST['checked_id']) ? trim($_POST['checked_id']) : 0 ;
			$data['__hash__'] = $_POST['__hash__'];
			// fwrites(APP_PATH . 'Admin/ajax.txt',$checked_id);
			if($data['cid'] && $checked_id){
				if(strpos($checked_id,',')){
					// 多个
					$tmp_msg = '';
					$tmp_id = '';
					$tmp_flag = true;
					$arr = explode(',',$checked_id);
					// fwrites(APP_PATH . 'Admin/ajax.txt',$arr);
					foreach($arr as $key=>$value){
						if(strpos($value,'_')){
							$sub_arr = explode('_',$value);
							$data['aid'] = $sub_arr[0];
							$data['role_id'] = $sub_arr[1];
						}else{
							$data['aid'] = intval($value);
							$data['role_id'] = 0;
						}
						$result = $this->CompanyUser->where("cid = ".$data['cid']." and aid = ".$data['aid']." and role_id = ".$data['role_id'])->count();
						if(!$result){
							// 检验数据
							$data = $this->CompanyUser->create($data, 1); // 1是插入操作，0是更新操作
							$cu_result = $this->CompanyUser->add($data);
							if($cu_result){
								// fwrites(APP_PATH . 'Admin/ajax.txt',$cu_result);
								$dataResult['data'] = $cu_result;
								$tmp_msg .= $data['aid']."添加成功！\n";
								$tmp_id .= $cu_result."_".$data['aid']."_".$data['role_id'].",";
							}else{
								$tmp_flag = false;
								$tmp_msg .= $data['aid']."添加失败！".$this->CompanyUser->getError()."\n";
								$tmp_id .= "0_".$data['aid']."_".$data['role_id'].",";
							}
						}else{
							$tmp_flag = false;
							$tmp_msg .= $data['aid']."添加失败！重复添加！\n";
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
					if(strpos($checked_id,'_')){
						$sub_arr = explode('_',$checked_id);
						$data['aid'] = $sub_arr[0];
						$data['role_id'] = $sub_arr[1];
					}else{
						$data['aid'] = intval($value);
						$data['role_id'] = 0;
					}
					$result = $this->CompanyUser->where("cid = ".$data['cid']." and aid = ".$data['aid']." and role_id = ".$data['role_id'])->count();
					if(!$result){
						// 检验数据
						$data = $this->CompanyUser->create($data, 1); // 1是插入操作，0是更新操作
						$cu_result = $this->CompanyUser->add($data);
						if($cu_result){
							// fwrites(APP_PATH . 'Admin/ajax.txt',$dataResult);
							$dataResult['data'] = $cu_result."_".$data['aid']."_".$data['role_id'];
							$this->ajaxReturn($dataResult,'JSON');
						}else{
							$dataResult['flag'] = 0; //默认为1表示无任何错误
							$dataResult['msg'] = '添加失败！'.$this->CompanyUser->getError(); //ajax提示信息
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
				$dataResult['msg'] = '添加公司成员失败！请刷新重试！'; // ajax提示信息
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
     * ajax删除公司成员
      +----------------------------------------------------------
     */
	public function ajax_del_company_user(){
		$dataResult = array();
		$dataResult['flag'] = 1; // 默认为1表示无任何错误
		$dataResult['msg'] = '删除公司成员成功！'; // ajax提示信息
		$dataResult['data'] = ''; // 返回数据、修改成功则返回、修改后的数据
		if (IS_AJAX) {
			C('TOKEN_ON',false);
			// fwrites(APP_PATH . 'Admin/ajax.txt','@param---ajax_del_company_user start！');
			// fwrites(APP_PATH . 'Admin/ajax.txt',$_POST);
			$data = array();
			$data['id'] = isset($_POST['id']) ? intval($_POST['id']) : 0 ;
			if($data['id']){
				$cu_info = $this->CompanyUser->where($data)->find();
				if($cu_info){
					$a_result = $this->CompanyUser->delete($data['id']);
					if($a_result){
						$dataResult['data'] = $cu_info; // 返回数据、修改成功则返回、修改后的数据
						$this->ajaxReturn($dataResult,'JSON');
					}else{
						$dataResult['flag'] = 0; // 默认为1表示无任何错误
						$dataResult['msg'] = '删除失败！请稍后重新操作！'; // ajax提示信息
						$dataResult['data'] = 'err_no'; // 返回数据、修改成功则返回、修改后的数据
						$this->ajaxReturn($dataResult,'JSON');
					}
				}else{
					$dataResult['flag'] = 0; // 默认为1表示无任何错误
					$dataResult['msg'] = '操作失败！该用户已经被移出公司！'; // ajax提示信息
					$dataResult['data'] = 'err_no'; // 返回数据、修改成功则返回、修改后的数据
					$this->ajaxReturn($dataResult,'JSON');
				}
				
			}else{
				$dataResult['flag'] = 0; // 默认为1表示无任何错误
				$dataResult['msg'] = '删除公司成员失败！请刷新重试！'; // ajax提示信息
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
	
	
}

