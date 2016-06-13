<?php
/**
 * 文件管理
 * ============================================================================
 * 版权所有 2015-2080 Lessismore，并保留所有权利。
 * 网站地址: http://www.trydemo.net；
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和
 * 使用；不允许对程序代码以任何形式任何目的的再发布。
 * ============================================================================
 * $Author: lsq & Lessismore & D.Apache.Luo
 * $Id: FileController.class.php 2016-2-23 Lessismore $
*/
namespace Admin\Controller;
use Think\Controller;
use Think\Model;
class FileController extends CommonController {
	
	protected  $Project, $ProjectUser, $Company, $Admin, $RoleUser, $Role, $FileManage;
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
		$this->FileManage = D("File");
	}
	
	/**
      +----------------------------------------------------------
     * 显示文件列表
      +----------------------------------------------------------
     */
	public function index(){
		$this->assign('act',strtolower(ACTION_NAME));
		// 分配导航栏当前位置
		$this->assign('navigation_bar','文件管理>文件列表');
		
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
			$where .= " and f.name like '%$new_keyword%'";
		}
		
		if(isset($_REQUEST['dropdown_type'])){
			$type = intval(htmlspecialchars(trim($_REQUEST['dropdown_type'])));
			switch($type){
				case 1:
					$where .= " and f.status = 1";
					break;
				case 2:
					$where .= " and f.status = 0";
					break;
				default:
					break;
			}
		}
		
		$count = $this->FileManage->join('as f LEFT JOIN td_project as p ON p.pid = f.pid')->where($where)->count();
		$page = new \Think\Page($count,15);
		$show = $page->show();
		
		$list = $this->FileManage->field('f.id,f.name,f.pid,f.address,f.type,f.download_count,f.status,f.create_time,f.update_time,f.remark,p.name as p_name')->join('as f LEFT JOIN td_project as p ON p.pid = f.pid')->where($where)->order("f.create_time desc")->limit($page->firstRow.','.$page->listRows)->select();
		if(is_array($list) && count($list)){
			foreach ($list as $key=>$val){
				$list[$key]['key']=++$page->firstRow;
			}
		}
		

		$this->assign('keyword',$keyword);
		$this->assign('type',$type);
		$this->assign('list_empty','<tr align="center"><td colspan="10" align="center"><span>文件列表为空！请先创建新文件！</span></td></tr>');
		$this->assign('list',$list);
		$this->assign('page',$show);
		$this->display();
	}

	/**
      +----------------------------------------------------------
     * ajax更改文件审核状态
      +----------------------------------------------------------
     */
	public function ajax_update_status(){
		$dataResult = array();
		$dataResult['flag'] = 1; // 默认为1表示无任何错误
		$dataResult['msg'] = '文件激活状态更新成功！'; // ajax提示信息
		$dataResult['data'] = ''; // 返回数据、修改成功则返回、修改后的数据
		if (IS_AJAX) {
			$id = intval(trim($_REQUEST['id']));
			$data['id'] = $id;
			$status = $this->FileManage->where($data)->getField('status');
			$set = array();
			if($status == 1){
				$set = array('status'=>0);		
			}elseif($status == 0){
				$set = array('status'=>1);
			}else{
				$set = array('status'=>0);
			}
			$u_result = $this->FileManage->where($data)->save($set);
			if($u_result){
				$dataResult['data'] = $set['status'];
				$this->ajaxReturn($dataResult,'JSON');
			}else{
				$dataResult['flag'] = 0; // 默认为1表示无任何错误
				$dataResult['msg'] = '文件激活状态更新失败！请稍后重新操作！'; // ajax提示信息
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
     * 显示添加新文件界面
      +----------------------------------------------------------
     */
	public function mAdd(){
		if (IS_POST) {
			$data = array();
			$data['pid'] = isset($_POST['pid']) ? intval($_POST['pid']) : 0 ;
			$data['status'] = isset($_POST['status']) ? intval(trim($_POST['status'])) : 0 ;
			$data['type'] = 0;
			$data['is_del'] = 0;
			$data['remark'] = isset($_POST['remark']) ? trim($_POST['remark']) : '' ;
			$data['create_time'] = time();
			$data['update_time'] = $data['create_time'];
			$data['__hash__'] = $_POST['__hash__'];
			// p($_POST);
			//检测初始数据是否符合规则
			if($data['pid']){
				// p($_FILES);
				if (!empty($_FILES) && $_FILES['address']['size'] != 0){
					$data['name'] = $_FILES['address']['name'];
					if(!empty($data['name'])){
						$config = array(
							'maxSize' => 3145728,
							'rootPath' => './Uploads/YunFile/',
							'savePath' => '',
							'saveName' => array('uniqid',''),
							'exts' => array('jpg', 'gif', 'png', 'jpeg','xlsx','pdf','doc','docx','pdf','rar','zip'),
							'autoSub' => true,
							'subName' => array('date','Ymd'),
						);
						$upload = new \Think\Upload($config,'Local');// 实例化上传类
						$info = $upload->uploadOne($_FILES['address']);
						// p($info);
						if($info){
							// 图片上传成功获取图片路径和名字
							$data['address'] = $info['savepath'].$info['savename'];
							$type_name = $info['ext'];
							// 默认为0表示没有实体文件，1表示doc,2表示excel,3表示txt,4表示jpg,5表示gif,6表示png,7表示jpeg
							switch($type_name){
								case "doc":
									$data['type'] = 1;
									break;
								case "xlsx":
									$data['type'] = 2;
									break;
								case "txt":
									$data['type'] = 3;
									break;
								case "jpg":
									$data['type'] = 4;
									break;
								case "gif":
									$data['type'] = 5;
									break;
								case "png":
									$data['type'] = 6;
									break;
								case "jpeg":
									$data['type'] = 7;
									break;
								case "docx":
									$data['type'] = 8;
									break;
								case "pdf":
									$data['type'] = 9;
									break;
								case "rar":
									$data['type'] = 10;
									break;
								case "zip":
									$data['type'] = 11;
									break;
								default:
									$data['type'] = 0;
							}
							// 检验数据
							$data = $this->FileManage->create($data, 1); //1是插入操作，0是更新操作
							if ($this->FileManage->add($data)){
								$this->success("添加文件成功！", U('File/index'));
							} else {
								$this->error($this->FileManage->getError(),U('File/mAdd'));
							}
						}else{
							$this->error($upload->getError(),U('File/mAdd'));
						}
					}else{
						$this->error('文件名称不能为空！',U('File/mAdd'));
					}
				}else{
					$this->error('上传文件不能为空！',U('File/mAdd'));
				}	
			}else{
				$this->error('请先选择所属项目！',U('File/mAdd'));
			}
					
		} else {
			// 分配导航栏当前位置
			$this->assign('navigation_bar','文件管理>添加新文件');
			$this->assign('act',strtolower(ACTION_NAME));
			//获取所有项目及该项目的所有文件
			$pro_list = $this->Project->field('pid,name,cid,appraise,cost,create_time,update_time,status,cover_image,is_end,remark')->where('status = 1')->select();
			$this->assign('p_arr',$pro_list);
			
			// 初始化模版引擎
			$empty_info = array(
				'id' => 0,
				'pid' => 0,
				'name' => '',
				'address' => '',
				'type' => 0,
				'status' => 0,
				'is_del' => 0,
				'remark' => '',
			);
			$this->assign('info', $empty_info);
			$this->display("File:add");
			
		}	
	}
	
	/**
      +----------------------------------------------------------
     * ajax检测文件名称是否重复（新增模式）
      +----------------------------------------------------------
     */
	public function ajax_check_name(){
		//fwrites(APP_PATH . 'Admin/ajax.txt',"@param(ajax_check_name)---这是ajax请求！");
		$dataResult = array();
		$dataResult['flag'] = 1; //默认为1表示无任何错误
		$dataResult['msg'] = '文件名称不重复！'; //ajax提示信息
		$dataResult['data'] = ''; //返回数据、修改成功则返回、修改后的数据

		if(IS_AJAX){
			if(isset($_POST['name']) && !empty($_POST['name'])){
				$name = $_POST['name'];
				//删除所有单引号
				if(stristr($name,'\'')){
					$name = str_replace('\'','',$name);
				}
				$name = htmlspecialchars(trim($name));
				$result = $this->FileManage->where("name = '$name'")->count();
				if(!$result){
					$dataResult['flag'] = 1; //默认为1表示无任何错误
					$dataResult['msg'] = '文件名称输入正确！'; //ajax提示信息
					$dataResult['data'] = '';	
				}else{
					$dataResult['flag'] = 0; //默认为1表示无任何错误
					$dataResult['msg'] = '文件名称重复！'; //ajax提示信息
					$dataResult['data'] = '';	
				}
			}else{
				$dataResult['flag'] = 0; //默认为1表示无任何错误
				$dataResult['msg'] = '文件名称不能为空！'; //ajax提示信息
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
		$dataResult['msg'] = '文件名称不重复！'; //ajax提示信息
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
				$now_c = $this->FileManage->where("id = $id")->find();
				if(count($now_c) ){
					if(!empty($now_c['name']) && $now_c['name'] == $name){
						$dataResult['flag'] = 1; //默认为1表示无任何错误
						$dataResult['msg'] = '文件名称输入正确！'; //ajax提示信息
						$dataResult['data'] = '';
					}else{
						$result = $this->FileManage->where("name = '$name'")->count();
						if(!$result){
							$dataResult['flag'] = 1; //默认为1表示无任何错误
							$dataResult['msg'] = '文件名称输入正确！'; //ajax提示信息
							$dataResult['data'] = '';
							//fwrites(APP_PATH . 'Admin/ajax.txt',"名字合法！");	
						}else{
							$dataResult['flag'] = 0; //默认为1表示无任何错误
							$dataResult['msg'] = '文件名称重复！'; //ajax提示信息
							$dataResult['data'] = '';	
						}
					}
				}else{
					$dataResult['flag'] = 0; //默认为1表示无任何错误
					$dataResult['msg'] = '该文件已经不存在！'; //ajax提示信息
					$dataResult['data'] = '';
				}
			}else{
				$dataResult['flag'] = 0; //默认为1表示无任何错误
				$dataResult['msg'] = '文件名字不能为空！'; //ajax提示信息
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
     * 显示编辑或者修改文件信息页面
      +----------------------------------------------------------
     */
	public function mEdit(){

		if (IS_POST) {
			$data = array();
			$data['id'] = intval($_POST['id']) ? intval($_POST['id']) : 0 ;
			$data['pid'] = isset($_POST['pid']) ? intval($_POST['pid']) : 0 ;
			$data['status'] = isset($_POST['status']) ? intval(trim($_POST['status'])) : 0 ;
			$data['remark'] = isset($_POST['remark']) ? trim($_POST['remark']) : '' ;
			$data['update_time'] = time();
			$data['__hash__'] = $_POST['__hash__'];
			// p($_POST);
			//检测初始数据是否符合规则
			if($data['id']){
				if($data['pid']){
					// p($_FILES);
					if (!empty($_FILES) && $_FILES['address']['size'] != 0){
						$data['name'] = $_FILES['address']['name'];
						if(!empty($data['name'])){
							$config = array(
								'maxSize' => 3145728,
								'rootPath' => './Uploads/YunFile/',
								'savePath' => '',
								'saveName' => array('uniqid',''),
								'exts' => array('jpg', 'gif', 'png', 'jpeg', 'doc', 'xls', 'txt'),
								'autoSub' => true,
								'subName' => array('date','Ymd'),
							);
							$upload = new \Think\Upload($config,'Local');// 实例化上传类
							$info = $upload->uploadOne($_FILES['address']);
							// p($info);
							if($info){
								// 图片上传成功获取图片路径和名字
								$data['address'] = $info['savepath'].$info['savename'];
								$type_name = $info['ext'];
								// 默认为0表示没有实体文件，1表示doc,2表示excel,3表示txt,4表示jpg,5表示gif,6表示png,7表示jpeg
								switch($type_name){
									case "doc":
										$data['type'] = 1;
										break;
									case "xlsx":
										$data['type'] = 2;
										break;
									case "txt":
										$data['type'] = 3;
										break;
									case "jpg":
										$data['type'] = 4;
										break;
									case "gif":
										$data['type'] = 5;
										break;
									case "png":
										$data['type'] = 6;
										break;
									case "jpeg":
										$data['type'] = 7;
										break;
									default:
										$data['type'] = 0;
								}
								// 检验数据
								$data = $this->FileManage->create($data, 1); //1是插入操作，0是更新操作
								if ($this->FileManage->add($data)){
									$this->success("添加文件成功！", U('File/index'));
								} else {
									$this->error($this->FileManage->getError(),U('File/mEdit','id='.$data['id']));
								}
							}else{
								$this->error($upload->getError(),U('File/mEdit','id='.$data['id']));
							}
						}else{
							$this->error('文件名称不能为空！',U('File/mEdit','id='.$data['id']));
						}
					}else{
						//检验数据
						$data = $this->FileManage->create($data, 0); //1是插入操作，0是更新操作
						$a_result = $this->FileManage->where("pid=" . $data['pid'])->save($data);
						if ($a_result){
							$this->success("编辑项目成功！", U('File/index'));
						} else {
							$this->error($this->FileManage->getError(),U('File/mEdit','id='.$data['id']));
						}
					}	
				}else{
					$this->error('请先选择所属项目！',U('File/mEdit','id='.$data['id']));
				}
				
			}else{
				$this->error('数据有误！',U('File/mEdit','id='.$data['id']));
			}

		} else {
			// 分配导航栏当前位置
			$this->assign('navigation_bar','文件管理>编辑文件信息');
			$this->assign('act',strtolower(ACTION_NAME));
			$id = isset($_GET['id']) && intval($_GET['id']) ? intval($_GET['id']) : $this->error('参数错误',U('File/index'));
			$file_info = $this->FileManage->where('id='.$id)->find();
			// p($file_info);
			$this->assign('info', $file_info);
			//获取所有项目及该项目的所有文件
			$pro_list = $this->Project->field('pid,name,cid,appraise,cost,create_time,update_time,status,cover_image,is_end,remark')->where('status = 1')->select();
			$this->assign('p_arr',$pro_list);
			$this->display("File:add");
		}
	}
	
	/**
      +----------------------------------------------------------
     * 将文件移入回收站
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
				$data['id'] = $id;
				$is_del = $this->FileManage->where($data)->getField('is_del');
				if($is_del == 0){
					$set = array();
					$set = array('is_del'=>1);
					$u_result = $this->FileManage->where($data)->save($set);
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
					$dataResult['msg'] = '重复操作！该文件已经被移入回收站！'; // ajax提示信息
					$dataResult['data'] = 'err_no'; // 返回数据、修改成功则返回、修改后的数据
					$this->ajaxReturn($dataResult,'JSON');
				}
				
			}else{
				$dataResult['flag'] = 0; // 默认为1表示无任何错误
				$dataResult['msg'] = '移入回收站失败！该文件已不存在！'; // ajax提示信息
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
     * 将文件从回收站还原
      +----------------------------------------------------------
     */
	public function ajax_restore_file(){
		$dataResult = array();
		$dataResult['flag'] = 1; // 默认为1表示无任何错误
		$dataResult['msg'] = '还原该文件成功！'; // ajax提示信息
		$dataResult['data'] = ''; // 返回数据、修改成功则返回、修改后的数据
		//fwrites(APP_PATH . 'Company/ajax.txt','开始删除！');
		if (IS_AJAX) {
			$id = intval($_POST['id']) ? intval($_POST['id']) : 0 ;
			if($id){
				$data['id'] = $id;
				$is_del = $this->FileManage->where($data)->getField('is_del');
				if($is_del == 1){
					$set = array();
					$set = array('is_del'=>0);
					$u_result = $this->FileManage->where($data)->save($set);
					if($u_result){
						$dataResult['data'] = $set['is_del'];
						$this->ajaxReturn($dataResult,'JSON');
					}else{
						$dataResult['flag'] = 0; // 默认为1表示无任何错误
						$dataResult['msg'] = '还原文件失败！请稍后重新操作！'; // ajax提示信息
						$dataResult['data'] = 'err_no'; // 返回数据、修改成功则返回、修改后的数据
						$this->ajaxReturn($dataResult,'JSON');
					}		
				}else{
					$dataResult['flag'] = 0; // 默认为1表示无任何错误
					$dataResult['msg'] = '重复操作！该文件已经被移出回收站！'; // ajax提示信息
					$dataResult['data'] = 'err_no'; // 返回数据、修改成功则返回、修改后的数据
					$this->ajaxReturn($dataResult,'JSON');
				}	
			}else{
				$dataResult['flag'] = 0; // 默认为1表示无任何错误
				$dataResult['msg'] = '移出回收站失败！该文件已不存在！'; // ajax提示信息
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
     * ajax删除文件
      +----------------------------------------------------------
     */
	public function ajax_del_file(){
		$dataResult = array();
		$dataResult['flag'] = 1; // 默认为1表示无任何错误
		$dataResult['msg'] = '删除成功！'; // ajax提示信息
		$dataResult['data'] = ''; // 返回数据、修改成功则返回、修改后的数据
		if (IS_AJAX) {
			$data['id'] = intval($_POST['id']) ? intval($_POST['id']) : 0 ;
			$f_info = $this->FileManage->where($data)->find();
			if($f_info){
				$a_result = $this->FileManage->delete($data['id']);
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
				$dataResult['msg'] = '删除失败！该文件已经被删除！'; // ajax提示信息
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
     * 文件回收站
      +----------------------------------------------------------
     */
	public function mTrash(){
		$this->assign('act',strtolower(ACTION_NAME));
		// 分配导航栏当前位置
		$this->assign('navigation_bar','文件管理>回收站管理');
		
		// 回收站关键字(默认为非回收站)
		$mTrash_act = 0;
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
			$where .= " and f.name like '%$new_keyword%'";
		}
		
		if(isset($_REQUEST['dropdown_type'])){
			$type = intval(htmlspecialchars(trim($_REQUEST['dropdown_type'])));
			switch($type){
				case 1:
					$where .= " and f.status = 1";
					break;
				case 2:
					$where .= " and f.status = 0";
					break;
				default:
					break;
			}
		}
		
		$count = $this->FileManage->join('as f LEFT JOIN td_project as p ON p.pid = f.pid')->where($where)->count();
		$page = new \Think\Page($count,15);
		$show = $page->show();
		
		$list = $this->FileManage->field('f.id,f.name,f.pid,f.address,f.type,f.download_count,f.status,f.create_time,f.update_time,f.remark,p.name as p_name')->join('as f LEFT JOIN td_project as p ON p.pid = f.pid')->where($where)->order("f.create_time desc")->limit($page->firstRow.','.$page->listRows)->select();
		if(is_array($list) && count($list)){
			foreach ($list as $key=>$val){
				$list[$key]['key']=++$page->firstRow;
			}
		}
		

		$this->assign('keyword',$keyword);
		$this->assign('type',$type);
		$this->assign('list_empty','<tr align="center"><td colspan="10" align="center"><span>文件列表为空！请先创建新文件！</span></td></tr>');
		$this->assign('list',$list);
		$this->assign('page',$show);
		$this->display("File:index");
	}
	

}