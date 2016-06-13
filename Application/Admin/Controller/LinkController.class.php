<?php
/**
 * 友情链接管理
 * ============================================================================
 * 版权所有 2015-2080 Lessismore，并保留所有权利。
 * 网站地址: http://www.trydemo.net；
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和
 * 使用；不允许对程序代码以任何形式任何目的的再发布。
 * ============================================================================
 * $Author: lsq & Lessismore & D.Apache.Luo
 * $Id: LinkController.class.php 2016-2-26 Lessismore $
*/
namespace Admin\Controller;
use Think\Controller;
use Think\Model;
class LinkController extends CommonController {
	
	protected  $LinkManage, $Admin;
	
	/**
      +----------------------------------------------------------
     * 初始化
     * 如果 继承本类的类自身也需要初始化那么需要在使用本继承类的类里使用parent::_initialize();
      +----------------------------------------------------------
     */
	public function _initialize() {
		parent::_initialize();
		$this->LinkManage = D("Link");
		$this->Admin = D("Admin");
	}
	
	/**
      +----------------------------------------------------------
     * 显示友情链接列表
      +----------------------------------------------------------
     */
	public function index(){
		$this->assign('act',strtolower(ACTION_NAME));
		// 分配导航栏当前位置
		$this->assign('navigation_bar','门户管理>友情链接管理');
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
			$where .= " and link_name like '%$new_keyword%'";
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

		$count = $this->LinkManage->where($where)->count();
		$page = new \Think\Page($count,10);
		$show = $page->show();
		
		$list = $this->LinkManage->field('link_id,link_name,link_url,link_type,link_logo,link_image,link_description,link_owner,link_rating,status,sort,create_time,update_time,link_rss,is_del')->where($where)->order("create_time desc")->limit($page->firstRow.','.$page->listRows)->select();
		if(is_array($list) && count($list)){
			foreach ($list as $key=>$val){
				$list[$key]['key']=++$page->firstRow;
				if($val['link_owner'] > 0){
					$list[$key]['link_owner_name'] = $this->Admin->where("aid = ".$val['link_owner'])->getField("username");
				}else{
					$list[$key]['link_owner_name'] = '未知用户';
				}
			}
		}

		$this->assign('keyword',$keyword);
		$this->assign('type',$type);
		$this->assign('list_empty','<tr align="center"><td colspan="10" align="center"><span>友情链接列表为空！请先创建新友情链接！</span></td></tr>');
		$this->assign('list',$list);
		$this->assign('page',$show);
		$this->display();
	}

	/**
      +----------------------------------------------------------
     * ajax更改友情链接审核状态
      +----------------------------------------------------------
     */
	public function ajax_update_status(){
		$dataResult = array();
		$dataResult['flag'] = 1; // 默认为1表示无任何错误
		$dataResult['msg'] = '锁定状态更新成功！'; // ajax提示信息
		$dataResult['data'] = ''; // 返回数据、修改成功则返回、修改后的数据
		if (IS_AJAX) {
			$id = intval(trim($_REQUEST['id']));
			$data['link_id'] = $id;
			$status = $this->LinkManage->where($data)->getField('status');
			$set = array();
			if($status == 1){
				$set = array('status'=>0);		
			}elseif($status == 0){
				$set = array('status'=>1);
			}else{
				$set = array('status'=>0);
			}
			$u_result = $this->LinkManage->where($data)->save($set);
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
     * 显示添加新友情链接界面
      +----------------------------------------------------------
     */
	public function mAdd(){
		if (IS_POST) {
			$data = array();
			$data['link_name'] = isset($_POST['link_name']) ? trim($_POST['link_name']) : '' ;
			$data['link_url'] = isset($_POST['link_url']) ? trim($_POST['link_url']) : '' ;
			$data['link_type'] = 0;
			$data['link_owner'] = session(C('USER_AUTH_KEY'));
			$data['sort'] = isset($_POST['sort']) ? intval($_POST['sort']) : 0;
			$data['status'] = isset($_POST['status']) ? intval(trim($_POST['status'])) : 1 ;
			$data['link_description'] = isset($_POST['link_description']) ? trim($_POST['link_description']) : '' ;
			$data['create_time'] = time();
			$data['update_time'] = $data['create_time'];
			$data['__hash__'] = $_POST['__hash__'];
			if($data['sort'] > 999){
				$data['sort'] = 999;
			}
			// fwrites(APP_PATH . 'Admin/ajax.txt',$data);
			$test = is_url($data['link_url']);
			// fwrites(APP_PATH . 'Admin/ajax.txt',$test);
			//检测初始数据是否符合规则
			if($data['link_owner']){
				if(!empty($data['link_name'])){
					$result = $this->LinkManage->where("link_name='".$data['link_name']."'")->count();
					if(!$result){
						if(!empty($data['link_url'])){  
							if(is_url($data['link_url'])){
								if (!empty($_FILES) && $_FILES['link_image']['size'] != 0){
									$config = array(
										'maxSize' => 3145728,
										'rootPath' => './Uploads/Link/Image/',
										'savePath' => '',
										'saveName' => array('uniqid',''),
										'exts' => array('jpg', 'gif', 'png', 'jpeg'),
										'autoSub' => true,
										'subName' => array('date','Ymd'),
									);
									$upload = new \Think\Upload($config,'Local');// 实例化上传类
									$info = $upload->uploadOne($_FILES['link_image']);
									if($info){
										// 图片上传成功获取图片路径和名字
										$data['link_image'] = $info['savepath'].$info['savename'];
										if(!empty($_FILES) && $_FILES['link_logo']['size'] != 0){
											$config = array(
												'maxSize' => 3145728,
												'rootPath' => './Uploads/Link/Logo/',
												'savePath' => '',
												'saveName' => array('uniqid',''),
												'exts' => array('jpg', 'gif', 'png', 'jpeg'),
												'autoSub' => true,
												'subName' => array('date','Ymd'),
											);
											$upload_obj = new \Think\Upload($config,'Local');// 实例化上传类
											$new_info = $upload_obj->uploadOne($_FILES['link_logo']);
											if($new_info){
												$data['link_logo'] = $new_info['savepath'].$new_info['savename'];
												// 检验数据
												$data = $this->LinkManage->create($data, 1); //1是插入操作，0是更新操作
												if ($this->LinkManage->add($data)){
													$this->success("添加友情链接成功！", U('Link/index'));
												} else {
													$this->error($this->LinkManage->getError(),U('Link/mAdd'));
												}
											}else{
												$this->error($upload_obj->getError(),U('Link/mAdd'));
											}
										}else{
											$this->error('友情链接Logo不能为空！',U('Link/mAdd'));
										}
									}else{
										$this->error($upload->getError(),U('Link/mAdd'));
									}
								}else{
									$this->error('友情链接图片不能为空！',U('Link/mAdd'));
								}				
							}else{
								$this->error('友情链接地址格式不正确！',U('Link/mAdd'));
							}
						}else{
							$this->error('友情链接地址不能为空！',U('Link/mAdd'));
						}
					}else{
						$this->error('友情链接名称'.$data['link_name'].'已经存在',U('Link/mAdd'));
					}
				}else{
					$this->error('友情链接名称不能为空！',U('Link/mAdd'));
				}	
			}else{
				$this->error('请先登录！',U('Public/index'));
			}
		} else {
			// 分配导航栏当前位置
			$this->assign('navigation_bar','门户管理>添加新友情链接');
			$this->assign('act',strtolower(ACTION_NAME));

			// 初始化模版引擎
			$empty_info = array(
				'link_id' => 0,
				'link_name' => '',
				'link_url' => '',
				'link_type' => 0,
				'link_logo' => '',
				'link_image' => '',
				'link_description' => '',
				'link_owner' => 0,
				'link_rating' => 0,
				'status' => 0,
				'sort' => 0,
				'link_rss' => '',
			);
			$this->assign('info', $empty_info);
			$this->display("Link:add");
			
		}	
	}
	
	/**
      +----------------------------------------------------------
     * ajax检测友情链接名称是否重复（新增模式）
      +----------------------------------------------------------
     */
	public function ajax_check_name(){
		//fwrites(APP_PATH . 'Admin/ajax.txt',"@param(ajax_check_name)---这是ajax请求！");
		$dataResult = array();
		$dataResult['flag'] = 1; //默认为1表示无任何错误
		$dataResult['msg'] = '友情链接名称不重复！'; //ajax提示信息
		$dataResult['data'] = ''; //返回数据、修改成功则返回、修改后的数据

		if(IS_AJAX){
			if(isset($_POST['name']) && !empty($_POST['name'])){
				$name = $_POST['name'];
				//删除所有单引号
				if(stristr($name,'\'')){
					$name = str_replace('\'','',$name);
				}
				$name = htmlspecialchars(trim($name));
				$result = $this->LinkManage->where("link_name = '$name'")->count();
				if(!$result){
					$dataResult['flag'] = 1; //默认为1表示无任何错误
					$dataResult['msg'] = '友情链接名称输入正确！'; //ajax提示信息
					$dataResult['data'] = '';	
				}else{
					$dataResult['flag'] = 0; //默认为1表示无任何错误
					$dataResult['msg'] = '友情链接名称重复！'; //ajax提示信息
					$dataResult['data'] = '';	
				}
			}else{
				$dataResult['flag'] = 0; //默认为1表示无任何错误
				$dataResult['msg'] = '友情链接名称不能为空！'; //ajax提示信息
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
		$dataResult['msg'] = '友情链接名称不重复！'; //ajax提示信息
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
				$now_c = $this->LinkManage->where("link_id = $id")->find();
				if(count($now_c) ){
					if(!empty($now_c['link_name']) && $now_c['link_name'] == $name){
						$dataResult['flag'] = 1; //默认为1表示无任何错误
						$dataResult['msg'] = '友情链接名称输入正确！'; //ajax提示信息
						$dataResult['data'] = '';
					}else{
						$result = $this->Company->where("name = '$name'")->count();
						if(!$result){
							$dataResult['flag'] = 1; //默认为1表示无任何错误
							$dataResult['msg'] = '友情链接名称输入正确！'; //ajax提示信息
							$dataResult['data'] = '';
							//fwrites(APP_PATH . 'Admin/ajax.txt',"名字合法！");	
						}else{
							$dataResult['flag'] = 0; //默认为1表示无任何错误
							$dataResult['msg'] = '友情链接名称重复！'; //ajax提示信息
							$dataResult['data'] = '';	
						}
					}
				}else{
					$dataResult['flag'] = 0; //默认为1表示无任何错误
					$dataResult['msg'] = '该友情链接已经不存在！'; //ajax提示信息
					$dataResult['data'] = '';
				}
			}else{
				$dataResult['flag'] = 0; //默认为1表示无任何错误
				$dataResult['msg'] = '友情链接名字不能为空！'; //ajax提示信息
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
     * 显示编辑或者修改友情链接信息页面
      +----------------------------------------------------------
     */
	public function mEdit(){

		if (IS_POST) {	
			$data = array();
			$data['link_id'] = intval($_POST['id']) ? intval($_POST['id']) : 0 ;
			$data['link_name'] = isset($_POST['link_name']) ? trim($_POST['link_name']) : '' ;
			$data['link_url'] = isset($_POST['link_url']) ? trim($_POST['link_url']) : '' ;
			$data['link_type'] = 0;
			$data['link_owner'] = session(C('USER_AUTH_KEY'));
			$data['sort'] = isset($_POST['sort']) ? intval($_POST['sort']) : 0;
			$data['status'] = isset($_POST['status']) ? intval(trim($_POST['status'])) : 1 ;
			$data['link_description'] = isset($_POST['link_description']) ? trim($_POST['link_description']) : '' ;
			$data['update_time'] = time();
			$data['__hash__'] = $_POST['__hash__'];
			if($data['sort'] > 999){
				$data['sort'] = 999;
			}
			
			// fwrites(APP_PATH . 'Admin/ajax.txt',$data);
			// fwrites(APP_PATH . 'Admin/ajax.txt',is_url($data['link_url']));
			//检测初始数据是否符合规则
			if($data['link_owner']){
				if(!empty($data['link_name'])){
					$result = $this->LinkManage->where("link_name='".$data['link_name']."'")->count();
					if(!$result){
						if(!empty($data['link_url'])){  
							if(is_url($data['link_url'])){
								$result = $this->LinkManage->where("link_id='".$data['link_id']."'")->find();
								if(count($result)){
									$name_count = $this->LinkManage->where("link_name='".$data['name']."'")->count();
									$tmp_bool = true;
									if($result['name'] == $data['name'] && $name_count != 1){
										$tmp_bool = false;
									}elseif($result['name'] != $data['name'] && $name_count != 0){
										$tmp_bool = false;
									}
									if($tmp_bool){
										if (!empty($_FILES) && $_FILES['link_image']['size'] != 0){
											$config = array(
												'maxSize' => 3145728,
												'rootPath' => './Uploads/Link/Image/',
												'savePath' => '',
												'saveName' => array('uniqid',''),
												'exts' => array('jpg', 'gif', 'png', 'jpeg'),
												'autoSub' => true,
												'subName' => array('date','Ymd'),
											);
											$upload = new \Think\Upload($config,'Local');// 实例化上传类
											$info = $upload->uploadOne($_FILES['link_image']);
											if($info){
												// 图片上传成功获取图片路径和名字
												$data['link_image'] = $info['savepath'].$info['savename'];
												if(!empty($_FILES) && $_FILES['link_logo']['size'] != 0){
													$config = array(
														'maxSize' => 3145728,
														'rootPath' => './Uploads/Link/Logo/',
														'savePath' => '',
														'saveName' => array('uniqid',''),
														'exts' => array('jpg', 'gif', 'png', 'jpeg'),
														'autoSub' => true,
														'subName' => array('date','Ymd'),
													);
													$upload_obj = new \Think\Upload($config,'Local');// 实例化上传类
													$new_info = $upload_obj->uploadOne($_FILES['link_logo']);
													if($new_info){
														$data['link_logo'] = $new_info['savepath'].$new_info['savename'];
														//检验数据
														$data = $this->LinkManage->create($data, 0); //1是插入操作，0是更新操作
														$a_result = $this->LinkManage->where("link_id=" . $data['link_id'])->save($data);
														if ($a_result){
															$this->success("编辑友情链接成功！", U('Link/index'));
														} else {
															$this->error($this->LinkManage->getError(),U('Link/mEdit','id='.$data['link_id']));
														}
													}else{
														$this->error($upload_obj->getError(),U('Link/mEdit','id='.$data['link_id']));
													}
												}else{
													$this->error('友情链接Logo不能为空！',U('Link/mEdit','id='.$data['link_id']));
												}
											}else{
												$this->error($upload->getError(),U('Link/mEdit','id='.$data['link_id']));
											}
										}else{
											$this->error('友情链接图片不能为空！',U('Link/mEdit','id='.$data['link_id']));
										}				
									}else{
										$this->error('友情链接名称重复！',U('Link/mEdit','id='.$data['link_id']));
									}
								}else{
									$this->error('友情链接'.$data['link_name'].'已经不存在！',U('Link/index'));
								}
							}else{
								$this->error('友情链接地址格式不正确！',U('Link/mEdit','id='.$data['link_id']));
							}
						}else{
							$this->error('友情链接地址不能为空！',U('Link/mEdit','id='.$data['link_id']));
						}
					}else{
						$this->error('友情链接名称'.$data['link_name'].'已经存在',U('Link/mEdit','id='.$data['link_id']));
					}
				}else{
					$this->error('友情链接名称不能为空！',U('Link/mEdit','id='.$data['link_id']));
				}	
			}else{
				$this->error('请先登录！',U('Public/index'));
			}
		} else {
			// 分配导航栏当前位置
			$this->assign('navigation_bar','门户管理>编辑友情链接');
			$this->assign('act',strtolower(ACTION_NAME));
			$id = isset($_GET['id']) && intval($_GET['id']) ? intval($_GET['id']) : $this->error('参数错误',U('Link/index'));
			$link_info = $this->LinkManage->where('link_id='.$id)->find();
			if($link_info && $link_info['link_id']){
				$this->assign('info', $link_info);
			}else{
				$this->error('该友情链接已不存在！',U('Link/index'));
			}
			$this->display("Link:add");
		}
	}
	
	/**
      +----------------------------------------------------------
     * 将友情链接移入回收站
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
				$data['link_id'] = $id;
				$is_del = $this->LinkManage->where($data)->getField('is_del');
				if($is_del == 0){
					$set = array();
					$set = array('is_del'=>1);
					$u_result = $this->LinkManage->where($data)->save($set);
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
					$dataResult['msg'] = '重复操作！该友情链接已经被移入回收站！'; // ajax提示信息
					$dataResult['data'] = 'err_no'; // 返回数据、修改成功则返回、修改后的数据
					$this->ajaxReturn($dataResult,'JSON');
				}
				
			}else{
				$dataResult['flag'] = 0; // 默认为1表示无任何错误
				$dataResult['msg'] = '移入回收站失败！该友情链接已不存在！'; // ajax提示信息
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
     * 将友情链接从回收站还原
      +----------------------------------------------------------
     */
	public function ajax_restore_link(){
		$dataResult = array();
		$dataResult['flag'] = 1; // 默认为1表示无任何错误
		$dataResult['msg'] = '还原该友情链接成功！'; // ajax提示信息
		$dataResult['data'] = ''; // 返回数据、修改成功则返回、修改后的数据
		//fwrites(APP_PATH . 'Company/ajax.txt','开始删除！');
		if (IS_AJAX) {
			$id = intval($_POST['id']) ? intval($_POST['id']) : 0 ;
			if($id){
				$data['link_id'] = $id;
				$is_del = $this->LinkManage->where($data)->getField('is_del');
				if($is_del == 1){
					$set = array();
					$set = array('is_del'=>0);
					$u_result = $this->LinkManage->where($data)->save($set);
					if($u_result){
						$dataResult['data'] = $set['is_del'];
						$this->ajaxReturn($dataResult,'JSON');
					}else{
						$dataResult['flag'] = 0; // 默认为1表示无任何错误
						$dataResult['msg'] = '还原友情链接失败！请稍后重新操作！'; // ajax提示信息
						$dataResult['data'] = 'err_no'; // 返回数据、修改成功则返回、修改后的数据
						$this->ajaxReturn($dataResult,'JSON');
					}		
				}else{
					$dataResult['flag'] = 0; // 默认为1表示无任何错误
					$dataResult['msg'] = '重复操作！该友情链接已经被移出回收站！'; // ajax提示信息
					$dataResult['data'] = 'err_no'; // 返回数据、修改成功则返回、修改后的数据
					$this->ajaxReturn($dataResult,'JSON');
				}	
			}else{
				$dataResult['flag'] = 0; // 默认为1表示无任何错误
				$dataResult['msg'] = '移出回收站失败！该友情链接已不存在！'; // ajax提示信息
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
     * ajax删除友情链接
      +----------------------------------------------------------
     */
	public function ajax_del_link(){
		$dataResult = array();
		$dataResult['flag'] = 1; // 默认为1表示无任何错误
		$dataResult['msg'] = '删除成功！'; // ajax提示信息
		$dataResult['data'] = ''; // 返回数据、修改成功则返回、修改后的数据
		if (IS_AJAX) {
			$data['link_id'] = intval($_POST['id']) ? intval($_POST['id']) : 0 ;
			$c_info = $this->LinkManage->where($data)->find();
			if($c_info){
				$a_result = $this->LinkManage->delete($data['link_id']);
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
				$dataResult['msg'] = '删除失败！该友情链接已经被删除！'; // ajax提示信息
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
     * 友情链接回收站管理
      +----------------------------------------------------------
     */
	public function mTrash(){
		$this->assign('act',strtolower(ACTION_NAME));
		// 分配导航栏当前位置
		$this->assign('navigation_bar','门户管理>回收站管理');
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
			$where .= " and link_name like '%$new_keyword%'";
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

		$count = $this->LinkManage->where($where)->count();
		$page = new \Think\Page($count,10);
		$show = $page->show();
		
		$list = $this->LinkManage->field('link_id,link_name,link_url,link_type,link_logo,link_image,link_description,link_owner,link_rating,status,sort,create_time,update_time,link_rss,is_del')->where($where)->order("create_time desc")->limit($page->firstRow.','.$page->listRows)->select();
		if(is_array($list) && count($list)){
			foreach ($list as $key=>$val){
				$list[$key]['key']=++$page->firstRow;
				if($val['link_owner'] > 0){
					$list[$key]['link_owner_name'] = $this->Admin->where("aid = ".$val['link_owner'])->getField("username");
				}else{
					$list[$key]['link_owner_name'] = '未知用户';
				}
			}
		}
		$this->assign('keyword',$keyword);
		$this->assign('type',$type);
		$this->assign('list_empty','<tr align="center"><td colspan="10" align="center"><span>回收站列表为空！</span></td></tr>');
		$this->assign('list',$list);
		$this->assign('page',$show);
		$this->display('Link:index');
	}

	
}

