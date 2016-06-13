<?php
/**
 * 项目管理
 * ============================================================================
 * 版权所有 2015-2080 Lessismore，并保留所有权利。
 * 网站地址: http://www.trydemo.net；
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和
 * 使用；不允许对程序代码以任何形式任何目的的再发布。
 * ============================================================================
 * $Author: lsq & Lessismore & D.Apache.Luo
 * $Id: FinanceController.class.php 2016-1-28 Lessismore $
*/
namespace Admin\Controller;
use Think\Controller;
use Think\Model;
class ProjectController extends CommonController {
	
	protected  $Project, $ProjectUser, $Company, $Admin, $RoleUser, $Role;
	// 主管角色ID
	protected $t_role_id = 10;
	// 前台角色
	protected $t_role_q_id = 7;
	
	/**
      +----------------------------------------------------------
     * 初始化
     * 如果 继承本类的类自身也需要初始化那么需要在使用本继承类的类里使用parent::_initialize();
      +----------------------------------------------------------
     */
	public function _initialize() {
		parent::_initialize();
		$this->Project = D("Project");
		$this->ProjectUser = D("ProjectUser");
		$this->Company = D("Company");
		$this->Admin = D("Admin");
		$this->Role = D("Role");
		$this->RoleUser = D("RoleUser");
	}
	
	/**
      +----------------------------------------------------------
     * 显示项目列表
      +----------------------------------------------------------
     */
	public function index(){
		$this->assign('act',strtolower(ACTION_NAME));
		// 分配导航栏当前位置
		$this->assign('navigation_bar','项目管理>项目列表');
		
		// 回收站关键字(默认为非回收站)
		$mTrash_act = 0;
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
			$where .= " and p.name like '%$new_keyword%'";
		}
		
		if(isset($_REQUEST['dropdown_type'])){
			$type = intval(htmlspecialchars(trim($_REQUEST['dropdown_type'])));
			switch($type){
				case 1:
					$where .= " and p.status = 1";
					break;
				case 2:
					$where .= " and p.status = 0";
					break;
				default:
					break;
			}
		}
		
		$count = $this->Project->join('as p LEFT JOIN td_company as c ON c.cid = p.cid')->where($where)->count();
		$page = new \Think\Page($count,5);
		$show = $page->show();
		
		$list = $this->Project->field('p.pid,p.name,p.cid,p.appraise,p.cost,p.create_time,p.update_time,p.status,p.cover_image,p.is_end,p.remark,c.name as c_name')->join('as p LEFT JOIN td_company as c ON c.cid = p.cid')->where($where)->order("p.create_time desc")->limit($page->firstRow.','.$page->listRows)->select();
		if(is_array($list) && count($list)){
			foreach ($list as $key=>$val){
				$list[$key]['key']=++$page->firstRow;
			}
		}

		$this->assign('keyword',$keyword);
		$this->assign('type',$type);
		$this->assign('list_empty','<tr align="center"><td colspan="10" align="center"><span>项目列表为空！请先创建新项目！</span></td></tr>');
		$this->assign('list',$list);
		$this->assign('page',$show);
		$this->display();
	}

	/**
      +----------------------------------------------------------
     * ajax更改项目审核状态
      +----------------------------------------------------------
     */
	public function ajax_update_status(){
		$dataResult = array();
		$dataResult['flag'] = 1; // 默认为1表示无任何错误
		$dataResult['msg'] = '项目激活状态更新成功！'; // ajax提示信息
		$dataResult['data'] = ''; // 返回数据、修改成功则返回、修改后的数据
		if (IS_AJAX) {
			$id = intval(trim($_REQUEST['id']));
			$data['pid'] = $id;
			$status = $this->Project->where($data)->getField('status');
			$set = array();
			if($status == 1){
				$set = array('status'=>0);		
			}elseif($status == 0){
				$set = array('status'=>1);
			}else{
				$set = array('status'=>0);
			}
			$u_result = $this->Project->where($data)->save($set);
			if($u_result){
				$dataResult['data'] = $set['status'];
				$this->ajaxReturn($dataResult,'JSON');
			}else{
				$dataResult['flag'] = 0; // 默认为1表示无任何错误
				$dataResult['msg'] = '项目激活状态更新失败！请稍后重新操作！'; // ajax提示信息
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
     * 显示添加新项目界面
      +----------------------------------------------------------
     */
	public function mAdd(){
		if (IS_POST) {
			$data = array();
			$data['name'] = isset($_POST['project_name']) ? trim($_POST['project_name']) : '' ;
			$data['cost'] = isset($_POST['cost']) ? round(floatval($_POST['cost']),2) : 0.00;
			$data['status'] = isset($_POST['status']) ? intval(trim($_POST['status'])) : 0 ;
			$data['is_end'] = 0;
			$data['remark'] = isset($_POST['remark']) ? trim($_POST['remark']) : '' ;
			$data['create_time'] = time();
			$data['update_time'] = $data['create_time'];
			$data['__hash__'] = $_POST['__hash__'];
			
			//获取当前用户所属公司
			$data['cid'] = 0;

			//检测初始数据是否符合规则
			if(!empty($data['name'])){
				$result = $this->Project->where("name='".$data['name']."'")->count();
				if(!$result){
					if (!empty($_FILES) && $_FILES['cover_image']['size'] != 0){
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
						$info = $upload->uploadOne($_FILES['cover_image']);
						if($info){
							// 图片上传成功获取图片路径和名字
							$data['cover_image'] = $info['savepath'].$info['savename'];
							// 检验数据
							$data = $this->Project->create($data, 1); //1是插入操作，0是更新操作
							if ($this->Project->add($data)){
								$this->success("添加项目成功！", U('Project/index'));
							} else {
								$this->error($this->Project->getError(),U('Project/mAdd'));
							}
						}else{
							$this->error($upload->getError(),U('Project/mAdd'));
						}
					}else{
						//检验数据
						$data = $this->Project->create($data, 1); //1是插入操作，0是更新操作
						if ($this->Project->add($data)){
							$this->success("添加项目成功！", U('Project/index'));
						} else {
							$this->error($this->Project->getError(),U('Project/mAdd'));
						}
					}
				}else{
					$this->error('项目名称'.$data['name'].'已经存在',U('Project/mAdd'));
				}
			}else{
				$this->error('项目名称不能为空！',U('Project/mAdd'));
			}	
		} else {
			// 分配导航栏当前位置
			$this->assign('navigation_bar','项目管理>添加新项目');
			$this->assign('act',strtolower(ACTION_NAME));
			// 初始化模版引擎
			$empty_info = array(
				'pid' => 0,
				'name' => '',
				'cid' => 0,
				'appraise' => '',
				'cost' => '0.00',
				'status' => 0,
				'cover_image' => '',
				'is_end' => 0,
				'remark' => '',
			);
			$this->assign('info', $empty_info);
			$this->display("Project:add");
			
		}	
	}
	
	/**
      +----------------------------------------------------------
     * ajax检测项目名称是否重复（新增模式）
      +----------------------------------------------------------
     */
	public function ajax_check_name(){
		//fwrites(APP_PATH . 'Admin/ajax.txt',"@param(ajax_check_name)---这是ajax请求！");
		$dataResult = array();
		$dataResult['flag'] = 1; //默认为1表示无任何错误
		$dataResult['msg'] = '项目名称不重复！'; //ajax提示信息
		$dataResult['data'] = ''; //返回数据、修改成功则返回、修改后的数据

		if(IS_AJAX){
			if(isset($_POST['name']) && !empty($_POST['name'])){
				$name = $_POST['name'];
				//删除所有单引号
				if(stristr($name,'\'')){
					$name = str_replace('\'','',$name);
				}
				$name = htmlspecialchars(trim($name));
				$result = $this->Project->where("name = '$name'")->count();
				if(!$result){
					$dataResult['flag'] = 1; //默认为1表示无任何错误
					$dataResult['msg'] = '项目名称输入正确！'; //ajax提示信息
					$dataResult['data'] = '';	
				}else{
					$dataResult['flag'] = 0; //默认为1表示无任何错误
					$dataResult['msg'] = '项目名称重复！'; //ajax提示信息
					$dataResult['data'] = '';	
				}
			}else{
				$dataResult['flag'] = 0; //默认为1表示无任何错误
				$dataResult['msg'] = '项目名称不能为空！'; //ajax提示信息
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
		$dataResult['msg'] = '项目名称不重复！'; //ajax提示信息
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
				$now_c = $this->Project->where("pid = $id")->find();
				if(count($now_c) ){
					if(!empty($now_c['name']) && $now_c['name'] == $name){
						$dataResult['flag'] = 1; //默认为1表示无任何错误
						$dataResult['msg'] = '项目名称输入正确！'; //ajax提示信息
						$dataResult['data'] = '';
					}else{
						$result = $this->Project->where("name = '$name'")->count();
						if(!$result){
							$dataResult['flag'] = 1; //默认为1表示无任何错误
							$dataResult['msg'] = '项目名称输入正确！'; //ajax提示信息
							$dataResult['data'] = '';
							//fwrites(APP_PATH . 'Admin/ajax.txt',"名字合法！");	
						}else{
							$dataResult['flag'] = 0; //默认为1表示无任何错误
							$dataResult['msg'] = '项目名称重复！'; //ajax提示信息
							$dataResult['data'] = '';	
						}
					}
				}else{
					$dataResult['flag'] = 0; //默认为1表示无任何错误
					$dataResult['msg'] = '该项目已经不存在！'; //ajax提示信息
					$dataResult['data'] = '';
				}
			}else{
				$dataResult['flag'] = 0; //默认为1表示无任何错误
				$dataResult['msg'] = '项目名字不能为空！'; //ajax提示信息
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
     * 显示编辑或者修改项目信息页面
      +----------------------------------------------------------
     */
	public function mEdit(){

		if (IS_POST) {
			$data = array();
			$data['pid'] = intval($_POST['id']) ? intval($_POST['id']) : 0 ;
			$data['name'] = isset($_POST['project_name']) ? trim($_POST['project_name']) : '' ;
			$data['cost'] = isset($_POST['cost']) ? round(floatval($_POST['cost']),2) : 0.00;
			$data['status'] = isset($_POST['status']) ? intval(trim($_POST['status'])) : 0 ;
			$data['is_end'] = isset($_POST['is_end']) ? intval(trim($_POST['is_end'])) : 0 ;
			$data['remark'] = isset($_POST['remark']) ? trim($_POST['remark']) : '' ;
			$data['update_time'] = time();
			$data['__hash__'] = $_POST['__hash__'];
			
			//检测初始数据是否符合规则
			if($data['pid']){
				if(!empty($data['name'])){
					$result = $this->Project->where("pid='".$data['pid']."'")->find();
					if(count($result)){
						$name_count = $this->Project->where("name='".$data['name']."'")->count();
						$tmp_bool = true;
						if($result['name'] == $data['name'] && $name_count != 1){
							$tmp_bool = false;
						}elseif($result['name'] != $data['name'] && $name_count != 0){
							$tmp_bool = false;
						}
						if($tmp_bool){
							$img_info;
							if (!empty($_FILES) && $_FILES['cover_image']['size'] != 0){
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
								$img_info = $upload->uploadOne($_FILES['cover_image']);
								if($img_info){
									// 图片上传成功获取图片路径和名字
									$data['cover_image'] = $img_info['savepath'].$img_info['savename'];
									//检验数据
									$data = $this->Project->create($data, 0); //1是插入操作，0是更新操作
									$a_result = $this->Project->where("pid=" . $data['pid'])->save($data);
									if ($a_result){
										$this->success("编辑项目成功！", U('Project/index'));
									} else {
										$this->error($this->Project->getError(),U('Project/mEdit','id='.$data['pid']));
									}
								}else{
									$this->error($upload->getError(),U('Project/mEdit','id='.$data['pid']));
								}
							}else{
								//检验数据
								$data = $this->Project->create($data, 0); //1是插入操作，0是更新操作
								$a_result = $this->Project->where("pid=" . $data['pid'])->save($data);
								if ($a_result){
									$this->success("编辑项目成功！", U('Project/index'));
								} else {
									$this->error($this->Project->getError(),U('Project/mEdit','id='.$data['pid']));
								}
							}
						}else{
							$this->error('项目名称重复！',U('Project/mEdit','id='.$data['pid']));
						}	
					}else{
						$this->error('项目'.$data['name'].'已经不存在！',U('Project/index'));
					}
				}else{
					$this->error('项目名称不能为空！',U('Project/mAdd','id='.$data['pid']));
				}				
			}else{
				$this->error('数据有误！',U('Project/index'));
			}

		} else {
			// 分配导航栏当前位置
			$this->assign('navigation_bar','项目管理>编辑项目信息');
			$this->assign('act',strtolower(ACTION_NAME));
			$id = isset($_GET['id']) && intval($_GET['id']) ? intval($_GET['id']) : $this->error('参数错误',U('Project/index'));
			$project_info = $this->Project->where('pid='.$id)->find();
			//p($project_info);
			$this->assign('info', $project_info);
			$this->display("Project:add");
		}
	}

	/**
      +----------------------------------------------------------
     * 项目成员管理
      +----------------------------------------------------------
     */
	public function mProjectMember(){
		$this->assign('navigation_bar','项目管理>项目成员管理');
		$this->assign('act',strtolower(ACTION_NAME));
		$id = isset($_GET['id']) && intval($_GET['id']) ? intval($_GET['id']) : $this->error('参数错误',U('Access/index'));
		$this->assign('id',$id);
		// 获取当前用户权限
		$s_id = session(C('USER_AUTH_KEY'));
		if(empty($s_id)){
			$this->error("帐号异常，请重新登录", U("Public/index"));
		}
		
		// 获取前台角色子分类
		$q_role_list = $this->Role->field('id,name,pid,status,remark,update_time,create_time,operator_id')->where('pid = '.$this->t_role_q_id)->select();
		$this->assign('q_role_list',$q_role_list);
		$this->assign('q_role_list_empty','<option value="0">暂无数据</option>');
		
		// 获取当前项目用户列表
		$project_user_list = $this->Admin->field('a.aid,a.username,a.avatar,a.mobile_number,pu.role_id,pu.id')->join('as a RIGHT JOIN td_project_user as pu ON a.aid = pu.aid')->where('pu.pid = '.$id)->select();
		$this->assign('project_user_list',$project_user_list);
		$this->assign('company_user_list_empty',"<li class='cu_list_0'><span><a class='li_username' href='javascript:void(0)'>暂无数据</a>&nbsp;&nbsp;&nbsp;<a class='cu_del_button' role_id='0'  href='javascript:void(0)'></span></li>");
		// p($project_user_list);
		// 获取前台用户及其子集列表
		$tmp_sql = '';
		if(count($q_role_list)){
			foreach($q_role_list as $key=>$value){
				$tmp_sql .= "UNION SELECT a.aid,a.username,a.avatar,a.mobile_number,ru.role_id FROM td_admin as a RIGHT JOIN td_role_user as ru ON a.aid = ru.user_id where a.status = 1 and a.is_del = 0 and ru.role_id = ".$value['id']." ";
			}
		}
		//$sql = "SELECT a.aid,a.username,a.avatar,a.mobile_number,ru.role_id FROM td_admin as a RIGHT JOIN td_role_user as ru ON a.aid = ru.user_id where a.status = 1 and a.is_del = 0 and ru.role_id = ".$this->t_role_q_id." ".$tmp_sql;
		$user_arr = array();
		if($tmp_sql && strlen($tmp_sql) > 5){
			$sql = substr($tmp_sql,5);
			$M = M();
			$all_user_list = $M->query($sql);
			if(count($project_user_list)){
				if(count($all_user_list)){
					foreach($project_user_list as $key=>$value){
						foreach($all_user_list as $ke=>$val){
							if($val['aid'] == $value['aid']){
								unset($all_user_list[$ke]);
							}
						}
					}
					$user_arr = $all_user_list;
				}else{
					// 数据有误可清除该项目下的所有用户
					
				}
			}else{
				$user_arr = $all_user_list;
			}
		}
		//p($user_arr);
		$this->assign('user_list',$user_arr);
		$this->assign('user_list_empty',"<li class='user_list_0'><span><a class='li_username' href='javascript:void(0)'>暂无数据</a>&nbsp;&nbsp;&nbsp;<input type='checkbox' name='select_role_user' role_id='0' value='0' /></span></li>");
		$this->display("Project:projectMember");
	}
	
	/**
      +----------------------------------------------------------
     * ajax添加项目成员
      +----------------------------------------------------------
     */
	public function ajax_add_project_user(){
		$dataResult = array();
		$dataResult['flag'] = 1; // 默认为1表示无任何错误
		$dataResult['msg'] = '添加成员成功！'; // ajax提示信息
		$dataResult['data'] = ''; // 返回数据、修改成功则返回、修改后的数据
		if (IS_AJAX) {
			C('TOKEN_ON',false);
			// fwrites(APP_PATH . 'Admin/ajax.txt','@param---ajax_add_project_user start');
			// fwrites(APP_PATH . 'Admin/ajax.txt',$_POST);
			$data = array();
			$data['pid'] = isset($_POST['pid']) ? intval($_POST['pid']) : 0 ;
			$data['role_id'] = isset($_POST['role_id']) ? intval($_POST['role_id']) : 0 ;
			$data['__hash__'] = $_POST['__hash__'];
			$aid = isset($_POST['aid']) ? trim($_POST['aid']) : 0 ;
			// fwrites(APP_PATH . 'Admin/ajax.txt',$aid);
			if($data['pid'] && $aid && $data['role_id']){
				if(strpos($aid,'_')){
					// 多个
					$tmp_msg = '';
					$tmp_id = '';
					$tmp_flag = true;
					$arr = explode('_',$aid);
					// fwrites(APP_PATH . 'Admin/ajax.txt',$arr);
					foreach($arr as $key=>$value){
						$data['aid'] = intval($value);
						$result = $this->ProjectUser->where("pid = ".$data['pid']." and aid = ".$data['aid']." and role_id = ".$data['role_id'])->count();
						if(!$result){
							// 检验数据
							$data = $this->ProjectUser->create($data, 1); // 1是插入操作，0是更新操作
							$cu_result = $this->ProjectUser->add($data);
							if($cu_result){
								// fwrites(APP_PATH . 'Admin/ajax.txt',$cu_result);
								$dataResult['data'] = $cu_result;
								$tmp_msg .= $data['aid']."添加成功！\n";
								$tmp_id .= $cu_result."_".$data['aid'].",";
							}else{
								$tmp_flag = false;
								$tmp_msg .= $data['aid']."添加失败！".$this->ProjectUser->getError()."\n";
								$tmp_id .= "0_".$data['aid'].",";
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
					$data['aid'] = intval($aid);
					$result = $this->ProjectUser->where("pid = ".$data['pid']." and aid = ".$data['aid']." and role_id = ".$data['role_id'])->count();
					if(!$result){
						// 检验数据
						$data = $this->ProjectUser->create($data, 1); // 1是插入操作，0是更新操作
						$cu_result = $this->ProjectUser->add($data);
						if($cu_result){
							// fwrites(APP_PATH . 'Admin/ajax.txt',$dataResult);
							$dataResult['data'] = $cu_result."_".$data['aid'];
							$this->ajaxReturn($dataResult,'JSON');
						}else{
							$dataResult['flag'] = 0; //默认为1表示无任何错误
							$dataResult['msg'] = '添加失败！'.$this->ProjectUser->getError(); //ajax提示信息
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
				$dataResult['msg'] = '添加项目成员失败！请刷新重试！'; // ajax提示信息
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
     * ajax删除项目成员
      +----------------------------------------------------------
     */
	public function ajax_del_project_user(){
		$dataResult = array();
		$dataResult['flag'] = 1; // 默认为1表示无任何错误
		$dataResult['msg'] = '删除项目成员成功！'; // ajax提示信息
		$dataResult['data'] = ''; // 返回数据、修改成功则返回、修改后的数据
		if (IS_AJAX) {
			C('TOKEN_ON',false);
			// fwrites(APP_PATH . 'Admin/ajax.txt','@param---ajax_del_company_user start！');
			// fwrites(APP_PATH . 'Admin/ajax.txt',$_POST);
			$data = array();
			$data['id'] = isset($_POST['id']) ? intval($_POST['id']) : 0 ;
			if($data['id']){
				$cu_info = $this->ProjectUser->where($data)->find();
				if($cu_info){
					$a_result = $this->ProjectUser->delete($data['id']);
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
					$dataResult['msg'] = '操作失败！该用户已经被移出项目！'; // ajax提示信息
					$dataResult['data'] = 'err_no'; // 返回数据、修改成功则返回、修改后的数据
					$this->ajaxReturn($dataResult,'JSON');
				}
				
			}else{
				$dataResult['flag'] = 0; // 默认为1表示无任何错误
				$dataResult['msg'] = '删除项目成员失败！请刷新重试！'; // ajax提示信息
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
     * 会议记录管理
      +----------------------------------------------------------
     */
	public function mMeetingManager(){
		
		// 分配导航栏当前位置
		$this->assign('navigation_bar','项目管理>会议记录管理');
		$this->assign('act',strtolower(ACTION_NAME));
		$id = isset($_REQUEST['id']) && intval($_REQUEST['id']) ? intval($_REQUEST['id']) : $this->error('参数错误',U('Project/index'));
		$tab = isset($_GET['tab']) && intval($_GET['tab']) ? intval($_GET['tab']) : 0;
		if($tab){
			$this->assign('is_show_add',1);
		}else{
			$this->assign('is_show_add',0);
		}
		$this->assign('pid',$id);
		$mm_mod = D('MeetingMinute'); 
		// 回收站关键字(默认为非回收站)
		$mTrash_act = 0;
		// 关键字搜索默认值
		$keyword = '';
		// 审核状态筛选默认值
		$type = 0;
		$where = 'm.pid = '.$id;
		if(isset($_REQUEST['keyword'])){
			$keyword = $_REQUEST['keyword'];
			// 删除所有单引号
			if(stristr($keyword,'\'')){
				$keyword = str_replace('\'','',$keyword);
			}
			$new_keyword = htmlspecialchars(trim($keyword));
			$where .= " and m.meeting_topic like '%$new_keyword%'";
		}
		if(isset($_REQUEST['dropdown_type'])){
			$type = intval(htmlspecialchars(trim($_REQUEST['dropdown_type'])));
			switch($type){
				case 1:
					$where .= " and m.status = 1";
					break;
				case 2:
					$where .= " and m.status = 0";
					break;
				default:
					break;
			}
		}
		
		$count = $mm_mod->join('as m LEFT JOIN td_project as p ON p.pid = m.pid')->where($where)->count();
		$page = new \Think\Page($count,15);
		$show = $page->show();
		$list = $mm_mod->field('m.id,m.pid,m.aid,m.meeting_topic,m.address,m.meeting_noter,m.meeting_organizers,m.executor,m.content,m.remark,m.start_time,m.end_time,m.status,m.create_time,m.update_time,p.name as p_name')->join('as m LEFT JOIN td_project as p ON p.pid = m.pid')->where($where)->order("m.create_time desc")->limit($page->firstRow.','.$page->listRows)->select();
		if(is_array($list) && count($list)){
			foreach ($list as $key=>$val){
				$list[$key]['key']=++$page->firstRow;
			}
		}
		
		$this->assign('keyword',$keyword);
		$this->assign('type',$type);
		$this->assign('list_empty','<tr align="center"><td colspan="10" align="center"><span>会议记录列表为空！请先创建新会议记录！</span></td></tr>');
		$this->assign('list',$list);
		$this->assign('page',$show);
		
		// 初始化模版引擎
		$empty_info = array(
			'id' => 0,
			'pid' => 0,
			'aid' => 0,
			'meeting_topic' => '',
			'address' => '',
			'meeting_noter' => '',
			'meeting_organizers' => '',
			'executor' => '',
			'content' => '',
			'remark' => '',
			'start_time' => date('Y-m-d H:i:s', time()),
			'end_time' => date('Y-m-d H:i:s', time()),
			'status' => 0,
		);
		$this->assign('info', $empty_info);
		
		if (IS_POST) {
			// fwrites(APP_PATH . 'Admin/ajax.txt','--------------------------------------------------->');
			// fwrites(APP_PATH . 'Admin/ajax.txt',$_POST);
			$data = array();
			$data['meeting_topic'] = isset($_POST['meeting_topic']) ? trim($_POST['meeting_topic']) : '' ;
			$data['pid'] = isset($_POST['pid']) ? intval($_POST['pid']) : 0 ;
			$data['aid'] = session(C('USER_AUTH_KEY'));
			$data['address'] = isset($_POST['address']) ? trim($_POST['address']) : '' ;
			$data['meeting_noter'] = isset($_POST['meeting_noter']) ? trim($_POST['meeting_noter']) : '' ;
			$data['meeting_organizers'] = isset($_POST['meeting_organizers']) ? trim($_POST['meeting_organizers']) : '' ;
			$data['executor'] = isset($_POST['executor']) ? trim($_POST['executor']) : '' ;
			$data['meeting_organizers'] = isset($_POST['meeting_organizers']) ? trim($_POST['meeting_organizers']) : '' ;
			$start_time = isset($_POST['start_time']) ? trim($_POST['start_time']) : '' ;
			$end_time = isset($_POST['end_time']) ? trim($_POST['end_time']) : '' ;
			$data['start_time'] = empty($start_time) ? 0 : strtotime($start_time) ;
			$data['end_time'] = empty($end_time) ? 0 : strtotime($end_time) ;
			$data['status'] = isset($_POST['status']) ? intval(trim($_POST['status'])) : 0 ;
			$data['content'] = isset($_POST['content']) ? trim($_POST['content']) : '' ;
			$data['remark'] = isset($_POST['remark']) ? trim($_POST['remark']) : '' ;
			$data['create_time'] = time();
			$data['update_time'] = $data['create_time'];
			$data['__hash__'] = $_POST['__hash__'];
			
			// fwrites(APP_PATH . 'Admin/ajax.txt',$data);
			//检测初始数据是否符合规则
			if($data['pid']){
				if(!empty($data['meeting_topic'])){
					$mm_mod = D('MeetingMinute');
					// 检验数据
					$data = $mm_mod->create($data, 1); //1是插入操作，0是更新操作
					if ($mm_mod->add($data)){
						$this->success("添加会议记录成功！", U('Project/mMeetingManager','id='.$data['pid'].'&tab=0'));
					} else {
						$this->error($mm_mod->getError(),U('Project/mMeetingManager','id='.$data['pid'].'&tab=1'));
					}
				}else{
					$this->error('会议记录主题不能为空！',U('Project/mMeetingManager','id='.$data['pid'].'&tab=1'));
				}	
			}else{
				$this->error('请求非法！',U('Project/index'));
			}
		} else {
			$this->display("Project:meetingManage");	
		}	
	}
	
	/**
      +----------------------------------------------------------
     * ajax更改审核状态
      +----------------------------------------------------------
     */
	public function ajax_update_mm_status(){
		$dataResult = array();
		$dataResult['flag'] = 1; // 默认为1表示无任何错误
		$dataResult['msg'] = '会议记录审核状态更新成功！'; // ajax提示信息
		$dataResult['data'] = ''; // 返回数据、修改成功则返回、修改后的数据
		if (IS_AJAX) {
			$id = intval(trim($_REQUEST['id']));
			$data['id'] = $id;
			$mm_mod = D('MeetingMinute');
			$status = $mm_mod->where($data)->getField('status');
			$set = array();
			if($status == 1){
				$set = array('status'=>0);		
			}elseif($status == 0){
				$set = array('status'=>1);
			}else{
				$set = array('status'=>0);
			}
			$u_result = $mm_mod->where($data)->save($set);
			if($u_result){
				$dataResult['data'] = $set['status'];
				$this->ajaxReturn($dataResult,'JSON');
			}else{
				$dataResult['flag'] = 0; // 默认为1表示无任何错误
				$dataResult['msg'] = '会议记录审核状态更新失败！请稍后重新操作！'; // ajax提示信息
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
     * 会议记录编辑
      +----------------------------------------------------------
     */
	public function mMeetingEdit(){
		if (IS_POST) {
			// fwrites(APP_PATH . 'Admin/ajax.txt','@param---mMeetingEdit start!');
			// fwrites(APP_PATH . 'Admin/ajax.txt','--------------------------------------------------->');
			// fwrites(APP_PATH . 'Admin/ajax.txt',$_POST);
			$data = array();
			$data['id'] = isset($_POST['id']) ? intval($_POST['id']) : 0 ;
			$data['meeting_topic'] = isset($_POST['meeting_topic']) ? trim($_POST['meeting_topic']) : '' ;
			$data['pid'] = isset($_POST['pid']) ? intval($_POST['pid']) : 0 ;
			$data['aid'] = session(C('USER_AUTH_KEY'));
			$data['address'] = isset($_POST['address']) ? trim($_POST['address']) : '' ;
			$data['meeting_noter'] = isset($_POST['meeting_noter']) ? trim($_POST['meeting_noter']) : '' ;
			$data['meeting_organizers'] = isset($_POST['meeting_organizers']) ? trim($_POST['meeting_organizers']) : '' ;
			$data['executor'] = isset($_POST['executor']) ? trim($_POST['executor']) : '' ;
			$data['meeting_organizers'] = isset($_POST['meeting_organizers']) ? trim($_POST['meeting_organizers']) : '' ;
			$start_time = isset($_POST['start_time']) ? trim($_POST['start_time']) : '' ;
			$end_time = isset($_POST['end_time']) ? trim($_POST['end_time']) : '' ;
			$data['start_time'] = empty($start_time) ? 0 : strtotime($start_time) ;
			$data['end_time'] = empty($end_time) ? 0 : strtotime($end_time) ;
			$data['status'] = isset($_POST['status']) ? intval(trim($_POST['status'])) : 0 ;
			$data['content'] = isset($_POST['content']) ? trim($_POST['content']) : '' ;
			$data['remark'] = isset($_POST['remark']) ? trim($_POST['remark']) : '' ;
			$data['update_time'] = time();
			$data['__hash__'] = $_POST['__hash__'];
			
			// fwrites(APP_PATH . 'Admin/ajax.txt',$data);
			//检测初始数据是否符合规则
			if($data['id']){
				if($data['pid']){
					if(!empty($data['meeting_topic'])){
						$mm_mod = D('MeetingMinute');
						// 检验数据
						$data = $mm_mod->create($data, 0); //1是插入操作，0是更新操作
						$a_result = $mm_mod->where("id=" . $data['id'])->save($data);
						if ($a_result){
							$this->success("编辑会议记录成功！", U('Project/mMeetingManager','id='.$data['pid'].'&tab=0'));
						} else {
							$this->error($mm_mod->getError(),U('Project/mMeetingEdit','id='.$data['id']));
						}
					}else{
						$this->error('会议记录主题不能为空！',U('Project/mMeetingEdit','id='.$data['pid']));
					}	
				}else{
					$this->error('请求非法！',U('Project/index'));
				}
			}else{
				$this->error('数据有误！',U('Project/index'));
			}
		} else {
			// 分配导航栏当前位置
			$this->assign('navigation_bar','项目管理>编辑会议记录');
			$this->assign('act',strtolower(ACTION_NAME));
			$id = isset($_REQUEST['id']) && intval($_REQUEST['id']) ? intval($_REQUEST['id']) : $this->error('参数错误',U('Project/index'));
			$mm_mod = D('MeetingMinute');
			$mm_info = $mm_mod->where('id='.$id)->find();
			$mm_info['start_time'] = date('Y-m-d H:i:s',$mm_info['start_time']);
			$mm_info['end_time'] = date('Y-m-d H:i:s',$mm_info['end_time']);
			$this->assign('info', $mm_info);
			$this->display("Project:meetingEdit");	
		}	
	}
	
	/**
      +----------------------------------------------------------
     * ajax删除会议记录
      +----------------------------------------------------------
     */
	public function ajax_del_log(){
		$dataResult = array();
		$dataResult['flag'] = 1; // 默认为1表示无任何错误
		$dataResult['msg'] = '删除成功！'; // ajax提示信息
		$dataResult['data'] = ''; // 返回数据、修改成功则返回、修改后的数据
		if (IS_AJAX) {
			$data['id'] = intval($_POST['id']) ? intval($_POST['id']) : 0 ;
			$mm_mod = D('MeetingMinute');
			$f_info = $mm_mod->where($data)->find();
			if($f_info){
				$a_result = $mm_mod->delete($data['id']);
				if($a_result){
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
				$dataResult['msg'] = '删除失败！该会议记录已经被删除！'; // ajax提示信息
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

