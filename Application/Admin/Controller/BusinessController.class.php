<?php
/**
 * 业务管理
 * ============================================================================
 * 版权所有 2015-2080 Lessismore，并保留所有权利。
 * 网站地址: http://www.trydemo.net；
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和
 * 使用；不允许对程序代码以任何形式任何目的的再发布。
 * ============================================================================
 * $Author: lsq & Lessismore & D.Apache.Luo
 * $Id: BusinessController.class.php 2016-2-22 Lessismore $
*/
namespace Admin\Controller;
use Think\Controller;
use Think\Model;
class BusinessController extends CommonController {
	
	protected  $Project, $ProjectUser, $Company, $Admin, $RoleUser, $Role, $Business;
	// 主管角色ID
	protected $t_role_id = 10;
	// 前台角色
	protected $t_role_q_id = 7;
	// 乙方角色
	protected $t_role_b_id = 12;
	
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
		$this->Business = D("Business");
	}
	
	/**
      +----------------------------------------------------------
     * 显示业务列表
      +----------------------------------------------------------
     */
	public function index(){
		$this->assign('act',strtolower(ACTION_NAME));
		// 分配导航栏当前位置
		$this->assign('navigation_bar','业务管理>业务列表');
		
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
			$where .= " and b.name like '%$new_keyword%'";
		}
		
		if(isset($_REQUEST['dropdown_type'])){
			$type = intval(htmlspecialchars(trim($_REQUEST['dropdown_type'])));
			switch($type){
				case 1:
					$where .= " and b.status = 1";
					break;
				case 2:
					$where .= " and b.status = 0";
					break;
				default:
					break;
			}
		}
		
		$count = $this->Business->join('as b LEFT JOIN td_project as p ON p.pid = b.pid')->where($where)->count();
		$page = new \Think\Page($count,15);
		$show = $page->show();
		
		$list = $this->Business->field('b.id,b.name,b.pid,b.completeness,b.appraise,b.cost,b.p_bid,b.create_time,b.total_time,b.start_time,b.end_time,b.is_grade,b.status,b.remark,p.name as p_name')->join('as b LEFT JOIN td_project as p ON p.pid = b.pid')->where($where)->order("b.create_time desc")->limit($page->firstRow.','.$page->listRows)->select();
		if(is_array($list) && count($list)){
			foreach ($list as $key=>$val){
				$list[$key]['key']=++$page->firstRow;
			}
		}
		

		$this->assign('keyword',$keyword);
		$this->assign('type',$type);
		$this->assign('list_empty','<tr align="center"><td colspan="10" align="center"><span>业务列表为空！请先创建新业务！</span></td></tr>');
		$this->assign('list',$list);
		$this->assign('page',$show);
		$this->display();
	}

	/**
      +----------------------------------------------------------
     * ajax更改业务审核状态
      +----------------------------------------------------------
     */
	public function ajax_update_status(){
		$dataResult = array();
		$dataResult['flag'] = 1; // 默认为1表示无任何错误
		$dataResult['msg'] = '业务激活状态更新成功！'; // ajax提示信息
		$dataResult['data'] = ''; // 返回数据、修改成功则返回、修改后的数据
		if (IS_AJAX) {
			$id = intval(trim($_REQUEST['id']));
			$data['id'] = $id;
			$status = $this->Business->where($data)->getField('status');
			$set = array();
			if($status == 1){
				$set = array('status'=>0);		
			}elseif($status == 0){
				$set = array('status'=>1);
			}else{
				$set = array('status'=>0);
			}
			$u_result = $this->Business->where($data)->save($set);
			if($u_result){
				$dataResult['data'] = $set['status'];
				$this->ajaxReturn($dataResult,'JSON');
			}else{
				$dataResult['flag'] = 0; // 默认为1表示无任何错误
				$dataResult['msg'] = '业务激活状态更新失败！请稍后重新操作！'; // ajax提示信息
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
     * 显示添加新业务界面
      +----------------------------------------------------------
     */
	public function mAdd(){
		if (IS_POST) {
			$data = array();
			$data['name'] = isset($_POST['business_name']) ? trim($_POST['business_name']) : '' ;
			$data['pid'] = isset($_POST['pid']) ? intval($_POST['pid']) : 0 ;
			$data['p_bid'] = isset($_POST['p_bid']) ? intval($_POST['p_bid']) : 0 ;
			$start_time = isset($_POST['start_time']) ? trim($_POST['start_time']) : '' ;
			$end_time = isset($_POST['end_time']) ? trim($_POST['end_time']) : '' ;
			$data['start_time'] = empty($start_time) ? 0 : strtotime($start_time) ;
			$data['end_time'] = empty($end_time) ? 0 : strtotime($end_time) ;
			$data['cost'] = isset($_POST['cost']) ? round(floatval($_POST['cost']),2) : 0.00;
			$data['spend_time'] = isset($_POST['spend_time']) ? intval($_POST['spend_time']) : 0;
			$data['is_grade'] = isset($_POST['is_grade']) ? intval(trim($_POST['is_grade'])) : 0 ;
			$data['status'] = isset($_POST['status']) ? intval(trim($_POST['status'])) : 0 ;
			$data['appraise'] = isset($_POST['appraise']) ? trim($_POST['appraise']) : '' ;
			$data['remark'] = isset($_POST['remark']) ? trim($_POST['remark']) : '' ;
			$data['completeness'] = 0;
			$data['create_time'] = time();
			$data['update_time'] = $data['create_time'];
			$data['total_time'] = 0;
			$data['__hash__'] = $_POST['__hash__'];

			//检测初始数据是否符合规则
			if(!empty($data['name'])){
				$result = $this->Business->where("name='".$data['name']."'")->count();
				if(!$result){
					if($data['pid']){
						$flag = true;
						if($data['p_bid'] != 0){
							$tmp_result = $this->Business->field('name')->where("id = ".$data['p_bid']." and pid =".$data['pid'])->find();
							if(!$tmp_result){
								$flag = false;
							}
						}
						if(flag){
							// 检验数据
							$data = $this->Business->create($data, 1); //1是插入操作，0是更新操作
							if ($this->Business->add($data)){
								$this->success("添加业务成功！", U('Business/index'));
							} else {
								$this->error($this->Business->getError(),U('Business/mAdd'));
							}
						}else{
							$this->error('选择的所属项目和父级业务关系有误！',U('Business/mAdd'));
						}	
					}else{
						$this->error('请先选择所属项目！',U('Business/mAdd'));
					}
				}else{
					$this->error('业务名称'.$data['name'].'已经存在',U('Business/mAdd'));
				}
			}else{
				$this->error('业务名称不能为空！',U('Business/mAdd'));
			}	
		} else {
			// 分配导航栏当前位置
			$this->assign('navigation_bar','业务管理>添加新业务');
			$this->assign('act',strtolower(ACTION_NAME));
			//获取所有项目及该项目的所有业务
			$pro_list = $this->Project->field('pid,name,cid,appraise,cost,create_time,update_time,status,cover_image,is_end,remark')->where('status = 1')->select();
			//p($pro_list);
			if(count($pro_list)){
				$arrayHelper = new \Org\Util\ArrayHelper();
				foreach($pro_list as $key=>$value){
					$b_list = $this->Business->field('id,name,pid,completeness,appraise,cost,spend_time,p_bid,create_time,total_time,start_time,end_time,is_grade,status,remark')->where("status = 1 and pid = ".$value['pid'])->select();
					if(count($b_list)){
						$tree = $arrayHelper::toTree($b_list, 'id', 'p_bid', 'children');
						$pro_list[$key]['business_arr'] = $arrayHelper::treeToHtml($tree,"children",0);
					}else{
						$pro_list[$key]['business_arr'] = array();
					}
				}
			}else{
				$pro_list[0]['business_arr'] = array();
			}
			$this->assign('pb_arr',$pro_list);
			// p($pro_list);
			
			// 初始化模版引擎
			$empty_info = array(
				'id' => 0,
				'name' => '',
				'pid' => 0,
				'completeness' => 0,
				'appraise' => '',
				'cost' => '0.00',
				'p_bid' => 0,
				'start_time' => date('Y-m-d', time()),
				'end_time' => date('Y-m-d', time()),
				'spend_time' => 0,
				'is_grade' => 0,
				'status' => 0,
				'remark' => '',
			);
			$this->assign('info', $empty_info);
			$this->display("Business:add");
			
		}	
	}
	
	/**
      +----------------------------------------------------------
     * ajax检测业务名称是否重复（新增模式）
      +----------------------------------------------------------
     */
	public function ajax_check_name(){
		//fwrites(APP_PATH . 'Admin/ajax.txt',"@param(ajax_check_name)---这是ajax请求！");
		$dataResult = array();
		$dataResult['flag'] = 1; //默认为1表示无任何错误
		$dataResult['msg'] = '业务名称不重复！'; //ajax提示信息
		$dataResult['data'] = ''; //返回数据、修改成功则返回、修改后的数据

		if(IS_AJAX){
			if(isset($_POST['name']) && !empty($_POST['name'])){
				$name = $_POST['name'];
				//删除所有单引号
				if(stristr($name,'\'')){
					$name = str_replace('\'','',$name);
				}
				$name = htmlspecialchars(trim($name));
				$result = $this->Business->where("name = '$name'")->count();
				if(!$result){
					$dataResult['flag'] = 1; //默认为1表示无任何错误
					$dataResult['msg'] = '业务名称输入正确！'; //ajax提示信息
					$dataResult['data'] = '';	
				}else{
					$dataResult['flag'] = 0; //默认为1表示无任何错误
					$dataResult['msg'] = '业务名称重复！'; //ajax提示信息
					$dataResult['data'] = '';	
				}
			}else{
				$dataResult['flag'] = 0; //默认为1表示无任何错误
				$dataResult['msg'] = '业务名称不能为空！'; //ajax提示信息
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
		$dataResult['msg'] = '业务名称不重复！'; //ajax提示信息
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
				$now_c = $this->Business->where("id = $id")->find();
				if(count($now_c) ){
					if(!empty($now_c['name']) && $now_c['name'] == $name){
						$dataResult['flag'] = 1; //默认为1表示无任何错误
						$dataResult['msg'] = '业务名称输入正确！'; //ajax提示信息
						$dataResult['data'] = '';
					}else{
						$result = $this->Business->where("name = '$name'")->count();
						if(!$result){
							$dataResult['flag'] = 1; //默认为1表示无任何错误
							$dataResult['msg'] = '业务名称输入正确！'; //ajax提示信息
							$dataResult['data'] = '';
							//fwrites(APP_PATH . 'Admin/ajax.txt',"名字合法！");	
						}else{
							$dataResult['flag'] = 0; //默认为1表示无任何错误
							$dataResult['msg'] = '业务名称重复！'; //ajax提示信息
							$dataResult['data'] = '';	
						}
					}
				}else{
					$dataResult['flag'] = 0; //默认为1表示无任何错误
					$dataResult['msg'] = '该业务已经不存在！'; //ajax提示信息
					$dataResult['data'] = '';
				}
			}else{
				$dataResult['flag'] = 0; //默认为1表示无任何错误
				$dataResult['msg'] = '业务名字不能为空！'; //ajax提示信息
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
     * 显示编辑或者修改业务信息页面
      +----------------------------------------------------------
     */
	public function mEdit(){

		if (IS_POST) {
			$data = array();
			$data['id'] = intval($_POST['id']) ? intval($_POST['id']) : 0 ;
			$data['name'] = isset($_POST['business_name']) ? trim($_POST['business_name']) : '' ;
			$data['pid'] = isset($_POST['pid']) ? intval($_POST['pid']) : 0 ;
			$data['p_bid'] = isset($_POST['p_bid']) ? intval($_POST['p_bid']) : 0 ;
			$start_time = isset($_POST['start_time']) ? trim($_POST['start_time']) : '' ;
			$end_time = isset($_POST['end_time']) ? trim($_POST['end_time']) : '' ;
			$data['start_time'] = empty($start_time) ? 0 : strtotime($start_time) ;
			$data['end_time'] = empty($end_time) ? 0 : strtotime($end_time) ;
			$data['cost'] = isset($_POST['cost']) ? round(floatval($_POST['cost']),2) : 0.00;
			$data['spend_time'] = isset($_POST['spend_time']) ? intval($_POST['spend_time']) : 0;	
			$data['is_grade'] = isset($_POST['is_grade']) ? intval(trim($_POST['is_grade'])) : 0 ;
			$data['status'] = isset($_POST['status']) ? intval(trim($_POST['status'])) : 0 ;
			$data['appraise'] = isset($_POST['appraise']) ? trim($_POST['appraise']) : '' ;
			$data['remark'] = isset($_POST['remark']) ? trim($_POST['remark']) : '' ;
			$data['completeness'] = isset($_POST['completeness']) ? intval(trim($_POST['completeness'])) : 0 ;
			$data['update_time'] = time();
			$data['__hash__'] = $_POST['__hash__'];
			
			//检测初始数据是否符合规则
			if($data['id']){
				if(!empty($data['name'])){
					$result = $this->Business->where("id='".$data['id']."'")->find();
					if(count($result)){
						$name_count = $this->Business->where("name='".$data['name']."'")->count();
						$tmp_bool = true;
						if($result['name'] == $data['name'] && $name_count != 1){
							$tmp_bool = false;
						}elseif($result['name'] != $data['name'] && $name_count != 0){
							$tmp_bool = false;
						}
						if($tmp_bool){
							if($data['pid']){
								$flag = true;
								if($data['p_bid'] != 0){
									$tmp_result = $this->Business->field('name')->where("id = ".$data['p_bid']." and pid =".$data['pid'])->find();
									if(!$tmp_result){
										$flag = false;
									}
								}
								if(flag){
									//检验数据
									$data = $this->Business->create($data, 0); //1是插入操作，0是更新操作
									$a_result = $this->Business->where("id=" . $data['id'])->save($data);
									if ($a_result){
										$this->success("编辑业务成功！", U('Business/index'));
									} else {
										$this->error($this->Business->getError(),U('Business/mEdit','id='.$data['id']));
									}
								}else{
									$this->error('选择的所属项目和父级业务关系有误！',U('Business/mEdit','id='.$data['id']));
								}	
							}else{
								$this->error('请先选择所属项目！',U('Business/mEdit','id='.$data['id']));
							}
						}else{
							$this->error('业务名称重复！',U('Business/mEdit','id='.$data['id']));
						}	
					}else{
						$this->error('业务'.$data['name'].'已经不存在！',U('Business/index'));
					}
				}else{
					$this->error('业务名称不能为空！',U('Business/mAdd','id='.$data['id']));
				}				
			}else{
				$this->error('数据有误！',U('Business/index'));
			}

		} else {
			// 分配导航栏当前位置
			$this->assign('navigation_bar','业务管理>编辑业务信息');
			$this->assign('act',strtolower(ACTION_NAME));
			$id = isset($_GET['id']) && intval($_GET['id']) ? intval($_GET['id']) : $this->error('参数错误',U('Business/index'));
			$business_info = $this->Business->where('id='.$id)->find();
			$business_info['start_time'] = date('Y-m-d',$business_info['start_time']);
			$business_info['end_time'] = date('Y-m-d',$business_info['end_time']);
			$this->assign('info', $business_info);
			//获取所有项目及该项目的所有业务
			$pro_list = $this->Project->field('pid,name,cid,appraise,cost,create_time,update_time,status,cover_image,is_end,remark')->where('status = 1')->select();
			if(count($pro_list)){
				$arrayHelper = new \Org\Util\ArrayHelper();
				foreach($pro_list as $key=>$value){
					$b_list = $this->Business->field('id,name,pid,completeness,appraise,cost,spend_time,p_bid,create_time,total_time,start_time,end_time,is_grade,status,remark')->where("status = 1 and pid = ".$value['pid'])->select();
					if(count($b_list)){
						$tree = $arrayHelper::toTree($b_list, 'id', 'p_bid', 'children');
						$pro_list[$key]['business_arr'] = $arrayHelper::treeToHtml($tree,"children",0);
					}else{
						$pro_list[$key]['business_arr'] = array();
					}
				}
			}else{
				$pro_list[0]['business_arr'] = array();
			}
			$this->assign('pb_arr',$pro_list);
			$this->display("Business:add");
		}
	}

	/**
      +----------------------------------------------------------
     * 业务明细管理界面
      +----------------------------------------------------------
     */
	public function mBusinessDetails(){

		if (IS_POST) {
			C('TOKEN_ON',false);
			$data = array();
			$data['bid'] = isset($_POST['bid']) ? intval($_POST['bid']) : 0 ;
			$data['update_time'] = time();
			$data['create_time'] = $data['update_time'];
			$act = isset($_POST['act']) ? trim($_POST['act']) : 'madd' ;
			$data['__hash__'] = $_POST['__hash__'];
			$user_str = isset($_POST['user_str']) ? trim($_POST['user_str']) : '';
			// fwrites(APP_PATH . 'Admin/ajax.txt','---------------------------------------------->');
			// fwrites(APP_PATH . 'Admin/ajax.txt',$_POST);
			//p($data);

			if($data['bid']){
				//89006_33,88997_0,89000_6
				
				if($user_str){
					
					if(strpos($user_str,',')){
						// p(11111);
						$arr = explode(",",$user_str);
						if(count($arr)){
							$tmp_flag = true;
							$tmp_msg = '';
							$bu_mod = D('BusinessUser');
							$bu_mod->where("bid = ".$data['bid'])->delete();
							foreach($arr as $key=>$value){
								// fwrites(APP_PATH . 'Admin/ajax.txt',$value);
								if($value && $value != '0_0' && strpos($value,'_')){
									$tmp_arr = explode('_',$value);
									if(count($tmp_arr) && $tmp_arr[0] && $tmp_arr[1]){
										$data['aid'] = intval($tmp_arr[0]);
										$data['spend_time'] = intval($tmp_arr[1]);
										if($data['spend_time'] > 87600){
											$data['spend_time'] = 87600;
										}
										// fwrites(APP_PATH . 'Admin/ajax.txt',$data);
										$bu_mod->where("bid = ".$data['bid']." and aid = ".$data['aid'])->delete();
										$data = $bu_mod->create($data, 1); //1是插入操作，0是更新操作
										$result = $bu_mod->add($data);
										if($result){
											$tmp_msg .= $data['aid']."该用户业务详情添加成功！\n";
										}else{
											$tmp_flag = false;
											$tmp_msg .= $data['aid']."该用户业务详情添加失败！".$bu_mod->getError()."\n";
										}
									}
								}
							}
							if(tmp_flag){
								$this->success($tmp_msg,U('Business/index'));
							}else{
								$this->error($tmp_msg,U('Business/mBusinessDetails','id='.$data['bid']));
							}
						}else{
							$this->error('业务详情不能为空！',U('Business/mBusinessDetails','id='.$data['bid']));
						}
					}else{
						// 单个
						// p(22222);
						if(strpos($user_str,'_')){
							$tmp_arr = explode('_',$user_str);
							if(count($tmp_arr)){
								$bu_mod = D('BusinessUser');
								$bu_mod->where("bid = ".$data['bid'])->delete();
								if($tmp_arr[0] && $tmp_arr[1]){
									$data['aid'] = intval($tmp_arr[0]);
									$data['spend_time'] = intval($tmp_arr[1]);
									if($data['spend_time'] > 87600){
										$data['spend_time'] = 87600;
									}
									$check_num = $bu_mod->where("bid = ".$data['bid']." and aid = ".$data['aid'])->count();
									$data = $bu_mod->create($data, 1); //1是插入操作，0是更新操作
									if ($bu_mod->add($data)){
										$this->success("业务详情修改成功！", U('Business/index'));
									} else {
										$this->error($bu_mod->getError(),U('Business/mBusinessDetails','id='.$data['bid']));
									}
								}else{
									$this->success("业务详情修改成功！", U('Business/index'));
								}
							}else{
								$this->error('业务详情不能为空！',U('Business/mBusinessDetails','id='.$data['bid']));
							}
						}else{
							$this->error('数据有误！',U('Business/mBusinessDetails','id='.$data['bid']));
						}
					}
				}else{
					$this->error('业务详情不能为空！',U('Business/mBusinessDetails','id='.$data['bid']));
				}				
			}else{
				$this->error('数据有误！',U('Business/index'));
			}
			
		} else {
			// 分配导航栏当前位置
			$this->assign('navigation_bar','业务管理>业务明细管理');
			$this->assign('act',strtolower(ACTION_NAME));
			$this->assign('act_a','madd');
			$this->assign('act_e','medit');

			//p(strtolower(ACTION_NAME));
			$id = isset($_GET['id']) && intval($_GET['id']) ? intval($_GET['id']) : $this->error('参数错误',U('Business/index'));
			$business_info = $this->Business->field('b.id,b.name,b.pid,b.completeness,b.appraise,b.cost,b.spend_time,b.p_bid,b.create_time,b.total_time,b.start_time,b.end_time,b.is_grade,b.status,b.remark,p.name as p_name')->join('as b LEFT JOIN td_project as p ON p.pid = b.pid')->where('b.id='.$id)->find();
			$business_info['start_time'] = date('Y-m-d',$business_info['start_time']);
			$business_info['end_time'] = date('Y-m-d',$business_info['end_time']);
			$this->assign('info', $business_info);
			
			// 获取当前公司可分配任务的用户列表
			$company_user_list = $this->Admin->field('a.aid,a.username,a.avatar,a.mobile_number,pu.role_id,pu.id,r.name as r_name,r.pid as r_pid,c.name as c_name')->join('as a RIGHT JOIN td_project_user as pu ON a.aid = pu.aid')->join('LEFT JOIN td_project as p ON p.pid = pu.pid')->join('LEFT JOIN td_company as c ON c.cid = p.cid')->join('LEFT JOIN td_role as r ON r.id = pu.role_id')->where('r.id = '.$this->t_role_b_id.' and pu.pid = '.$business_info['pid'])->select();
			$this->assign("company_user_list",$company_user_list);
			
			$bu_mod = D("BusinessUser");
			$m_list = $bu_mod->field('id,bid,aid,spend_time,remark,create_time,update_time')->where("bid=".$id)->select();
			$user_str = '';
			if(count($m_list)){
				foreach($m_list as $key=>$value){
					if($value['id']){
						$tmp_aid = $m_list[$key]['aid'];
						$user_str .= $m_list[$key]['id'].'_'.$m_list[$key]['aid'].'_'.$m_list[$key]['spend_time'].',';	
					}
				}
				if($user_str){
					$user_str = substr($user_str,0,strlen($user_str)-1);
				}
			}
			$this->assign('user_str',$user_str);
			$this->display("Business:businessDetails");
		}
	}
	
}

