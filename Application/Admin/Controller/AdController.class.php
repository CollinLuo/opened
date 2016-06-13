<?php
/**
 * 广告位管理
 * ============================================================================
 * 版权所有 2005-2080 Lessismore，并保留所有权利。
 * 网站地址: http://www.trydemo.net；
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和
 * 使用；不允许对程序代码以任何形式任何目的的再发布。
 * ============================================================================
 * $Author: lsq & Lessismore & D.Apache.Luo
 * $Id: AdController.class.php 2015-12-10 Lessismore $
*/
namespace Admin\Controller;
use Think\Controller;
use Think\Model;
class AdController extends CommonController {
	
	// 实际表名
	protected $t_n_article='td_ad';
	protected  $Ad ,$AdPosition;

	/**
      +----------------------------------------------------------
     * 初始化
     * 如果 继承本类的类自身也需要初始化那么需要在使用本继承类的类里使用parent::_initialize();
      +----------------------------------------------------------
     */
	public function _initialize() {
		parent::_initialize();
		$this->Ad = D("Ad");
		$this->AdPosition = D("AdPosition");
	}

	/**
      +----------------------------------------------------------
     * 显示广告位列表
      +----------------------------------------------------------
     */
	public function index(){
		//分配导航栏当前位置
		$this->assign('navigation_bar','门户管理>所有广告');
		//关键字搜索默认值
		$keyword = '';
		//评论状态筛选默认值
		$type = 0;
		$where = '1 = 1';
		if(isset($_REQUEST['keyword'])){
			$keyword = $_REQUEST['keyword'];
			//删除所有单引号
			if(stristr($keyword,'\'')){
				$keyword = str_replace('\'','',$keyword);
			}
			$new_keyword = htmlspecialchars(trim($keyword));
			$where .= " and ad_name like '%$new_keyword%'";
		}
	
		$count = $this->Ad->where($where)->count();
		$pageNum = 20;
		$page = new \Think\Page($count,$pageNum);
		$show = $page->show();		
		$list = $this->Ad->field('ad_id,position_id,ad_type,ad_name,ad_link,ad_image,start_time,end_time,link_man,link_email,link_phone,click_count,status,area_code,ad_detail')->where($where)->order("create_time desc")->limit($page->firstRow.','.$page->listRows)->select();
		if(is_array($list) && count($list)){
			foreach ($list as $key=>$val){
				$list[$key]['key']=++$page->firstRow;
			}
		}

		$this->assign('keyword',$keyword);
		$this->assign('list_empty','<tr align="center"><td colspan="10" align="center"><span>广告列表为空！请先创建新广告！</span></td></tr>');
		$this->assign('list',$list);
		$this->assign('page',$show);
		$this->display();
	}
	
	/**
      +----------------------------------------------------------
     * ajax更改广告锁定状态
      +----------------------------------------------------------
     */
	public function ajax_update_status(){
		$dataResult = array();
		$dataResult['flag'] = 1; //默认为1表示无任何错误
		$dataResult['msg'] = '锁定状态更新成功！'; //ajax提示信息
		$dataResult['data'] = ''; //返回数据、修改成功则返回、修改后的数据
		if (IS_AJAX) {
			$id = intval(trim($_REQUEST['id']));
			$data['ad_id'] = $id;
			$status = $this->Ad->where($data)->getField('status');
			$set = array();
			if($status == 1){
				$set = array('status'=>0);		
			}elseif($status == 0){
				$set = array('status'=>1);
			}else{
				$set = array('status'=>0);
			}
			$u_result = $this->Ad->where($data)->save($set);
			if($u_result){
				$dataResult['data'] = $set['status'];
				$this->ajaxReturn($dataResult,'JSON');
			}else{
				$dataResult['flag'] = 0; //默认为1表示无任何错误
				$dataResult['msg'] = '锁定状态更新失败！请稍后重新操作！'; //ajax提示信息
				$dataResult['data'] = 'err_no'; //返回数据、修改成功则返回、修改后的数据
				$this->ajaxReturn($dataResult,'JSON');
			}
		}else{
			$dataResult['flag'] = 0; //默认为1表示无任何错误
			$dataResult['msg'] = '提交失败！请稍后重新提交！'; //ajax提示信息
			$dataResult['data'] = 'err_server';
			$this->ajaxReturn($dataResult,'JSON');
		}
	}

	/**
      +----------------------------------------------------------
     * 添加新广告
      +----------------------------------------------------------
     */
	public function mAdd(){
		if(IS_POST){
			$data = array();
			$data['position_id'] = isset($_POST['position_id']) ? intval($_POST['position_id']) : 0 ;
			$data['ad_type'] = isset($_POST['ad_type']) ? intval($_POST['ad_type']) : 0 ;
			$data['ad_name'] = isset($_POST['ad_name']) ? trim($_POST['ad_name']) : '' ;
			$data['ad_link'] = isset($_POST['ad_link']) ? trim($_POST['ad_link']) : '' ;
			$start_time = isset($_POST['start_time']) ? trim($_POST['start_time']) : '' ;
			$end_time = isset($_POST['end_time']) ? trim($_POST['end_time']) : '' ;
			$data['start_time'] = empty($start_time) ? 0 : strtotime($start_time) ;
			$data['end_time'] = empty($end_time) ? 0 : strtotime($end_time) ;
			$data['ad_image_url'] = isset($_POST['ad_image_url']) ? trim($_POST['ad_image_url']) : '' ;
			$data['link_man'] = isset($_POST['link_man']) ? trim($_POST['link_man']) : '' ;
			$data['link_email'] = isset($_POST['link_email']) ? trim($_POST['link_email']) : '' ;
			$data['link_phone'] = isset($_POST['link_phone']) ? trim($_POST['link_phone']) : '' ;
			$data['status'] = isset($_POST['status']) ? intval($_POST['status']) : 0 ;
			$data['area_code'] = isset($_POST['area_code']) ? trim($_POST['area_code']) : '' ;
			$data['ad_detail'] = isset($_POST['ad_detail']) ? trim($_POST['ad_detail']) : '' ;
			$data['create_time'] = time();
			$data['update_time'] = $data['create_time'];
			$data['__hash__'] = $_POST['__hash__'];
			
			if($data['position_id'] && !$data['area_code']){
				$data['area_code'] = $this->AdPosition->where("position_id = ".$data['position_id'])->getField('area_code');
			}
			
			//检测初始数据是否符合规则
			if(!empty($data['ad_name'])){
				$result = $this->Ad->where("ad_name='".$data['ad_name']."'")->count();
				if(!$result){
					if(intval($data['ad_type']) > 0){
						if(!empty($data['ad_image_url'])){
							// 检验数据
							$data = $this->Ad->create($data, 1); //1是插入操作，0是更新操作
							if ($this->Ad->add($data)){
								$this->success("添加广告成功！", U('Ad:index'));
							} else {
								$this->error($this->Ad->getError(),U('Ad:mAdd'));
							}
						}else{
							$this->error('站外图片地址不能为空！',U('Ad:mAdd'));
						}
							
					}else{
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
							$info = $upload->uploadOne($_FILES['ad_image']);
							if($info){
								// 图片上传成功获取图片路径和名字
								$data['ad_image'] = $info['savepath'].$info['savename'];
								// 检验数据
								$data = $this->Ad->create($data, 1); //1是插入操作，0是更新操作
								if ($this->Ad->add($data)){
									$this->success("添加广告成功！", U('Ad:index'));
								} else {
									$this->error($this->Ad->getError(),U('Ad:mAdd'));
								}
							}else{
								$this->error($upload->getError(),U('Ad:mAdd'));
							}
						}else{
							$this->error('上传图片不能为空！',U('Ad:mAdd'));
						}
					}	
					
				}else{
					$this->error('广告名称'.$data['ad_name'].'已经存在',U('Ad:mAdd'));
				}
			}else{
				$this->error('广告名称不能为空！',U('Ad:mAdd'));
			}

		} else {
			// 分配导航栏当前位置
			$this->assign('navigation_bar','门户管理>添加广告位');
			$this->assign('act',ACTION_NAME);
			//p(ACTION_NAME);
			// 获取所有的广告位
			$adp_arr = $this->AdPosition->getAllAdPosition();
			$this->assign('adp_arr',$adp_arr);
			$this->assign('adp_arr_empty','<option value="0">暂无数据</option>');
			// 初始化模版引擎
			$empty_info = array(
				'ad_id' => 0,
				'position_id' => 0,
				'ad_type' => 0,
				'ad_name' => '',
				'ad_link' => '',
				'ad_image' => '',
				'ad_image_url' => '',
				'start_time' => date('Y-m-d', time()),
				'end_time' => date('Y-m-d', time()),
				'link_man' => '',
				'link_email' => '',
				'link_phone' => '',
				'status' => 0,
				'area_code' => '',
				'ad_detail' => '',
			);
			$this->assign('info', $empty_info);	
			$this->display("Ad:add");
		}
	}
	
	/**
      +----------------------------------------------------------
     * ajax检测广告名称是否重复（新增模式）
      +----------------------------------------------------------
     */
	public function ajax_check_name(){
		//fwrites(APP_PATH . 'Admin/ajax.txt',"@param(ajax_check_name)---这是ajax请求！");
		$dataResult = array();
		$dataResult['flag'] = 1; //默认为1表示无任何错误
		$dataResult['msg'] = '广告名称不重复！'; //ajax提示信息
		$dataResult['data'] = ''; //返回数据、修改成功则返回、修改后的数据

		if(IS_AJAX){
			if(isset($_POST['name']) && !empty($_POST['name'])){
				$name = $_POST['name'];
				//删除所有单引号
				if(stristr($name,'\'')){
					$name = str_replace('\'','',$name);
				}
				$name = htmlspecialchars(trim($name));
				$result = $this->Ad->where("ad_name = '$name'")->count();
				if(!$result){
					$dataResult['flag'] = 1; //默认为1表示无任何错误
					$dataResult['msg'] = '广告名称输入正确！'; //ajax提示信息
					$dataResult['data'] = '';	
				}else{
					$dataResult['flag'] = 0; //默认为1表示无任何错误
					$dataResult['msg'] = '广告名称重复！'; //ajax提示信息
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
     * ajax检测广告名称是否重复（编辑模式）
      +----------------------------------------------------------
     */
	public function ajax_check_edit_name(){
		//fwrites(APP_PATH . 'Admin/ajax.txt',"@param(ajax_check_name)---这是ajax请求！");
		$dataResult = array();
		$dataResult['flag'] = 1; //默认为1表示无任何错误
		$dataResult['msg'] = '广告名称不重复！'; //ajax提示信息
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
				$now_ad = $this->Ad->where("ad_id = $id")->find();
				if(count($now_ad) ){
					if(!empty($now_ad['ad_name']) && $now_ad['ad_name'] == $name){
						$dataResult['flag'] = 1; //默认为1表示无任何错误
						$dataResult['msg'] = '广告名称输入正确！'; //ajax提示信息
						$dataResult['data'] = '';
					}else{
						$result = $this->Ad->where("ad_name = '$name'")->count();
						if(!$result){
							$dataResult['flag'] = 1; //默认为1表示无任何错误
							$dataResult['msg'] = '广告名称输入正确！'; //ajax提示信息
							$dataResult['data'] = '';
							//fwrites(APP_PATH . 'Admin/ajax.txt',"名字合法！");	
						}else{
							$dataResult['flag'] = 0; //默认为1表示无任何错误
							$dataResult['msg'] = '广告名称重复！'; //ajax提示信息
							$dataResult['data'] = '';	
						}
					}
				}else{
					$dataResult['flag'] = 0; //默认为1表示无任何错误
					$dataResult['msg'] = '该广告已经不存在！'; //ajax提示信息
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
     * 编辑
      +----------------------------------------------------------
     */
	public function mEdit(){
		if(IS_POST){
			$data = array();
			$data['position_id'] = intval($_POST['id']) ? intval($_POST['id']) : 0 ;
			$data['position_name'] = isset($_POST['position_name']) ? trim($_POST['position_name']) : '' ;
			$data['ad_width'] = isset($_POST['ad_width']) ? intval($_POST['ad_width']) : 0 ;
			$data['ad_height'] = isset($_POST['ad_height']) ? intval($_POST['ad_height']) : 0 ;;
			$data['position_desc'] = isset($_POST['position_desc']) ? trim($_POST['position_desc']) : '' ;
			$area_code_page = isset($_POST['area_code_page']) ? trim($_POST['area_code_page']) : '' ;
			$area_code_module = isset($_POST['area_code_module']) ? trim($_POST['area_code_module']) : '' ;
			$area_code_sort = isset($_POST['area_code_sort']) ? trim($_POST['area_code_sort']) : 0 ;
			$data['update_time'] = time();
			$data['__hash__'] = $_POST['__hash__'];
			//检测初始数据是否符合规则
			if(empty($area_code_page) || empty($area_code_module) || $area_code_sort){
				$data['area_code'] = $area_code_page."-".$area_code_module."-".$area_code_sort;
				if($data['position_id']){
					if(!empty($data['position_name'])){
						$result = $this->Ad->where("position_name='".$data['position_name']."'")->count();
						//fwrites(WEB_ROOT . 'ajax.txt',$result);
						if(intval($result) < 2){
							//检验数据
							$data = $this->Ad->create($data, 0); //1是插入操作，0是更新操作
							$a_result = $this->Ad->where("position_id=" . $data['position_id'])->save($data);
							if(false !== $a_result){
								$this->success("编辑广告位成功！", U('Ad/index'));
							} else {
								$this->error($this->Article->getError(),U('Ad/mEdit','id='.$data['position_id']));
							}
						}else{
							$this->error('广告位名称'.$data['position_name'].'已经存在',U('Ad/mEdit','id='.$data['position_id']));
						}
					}else{
						$this->error('广告位名称不能为空！',U('Ad/mEdit','id='.$data['position_id']));
					}

				}else{
					$this->error('数据有误！',U('Ad/index'));
				}
			}else{
				$this->error('广告位区域码不能为空！',U('Ad/mEdit','id='.$data['position_id']));
			}
		} else {
			if( isset($_GET['id']) ){
				$id = isset($_GET['id']) && intval($_GET['id']) ? intval($_GET['id']) : $this->error('参数错误',U('AdPostion/index'));
			}
			//分配导航栏当前位置
			$this->assign('navigation_bar','门户管理>编辑广告');
			$this->assign('act',ACTION_NAME);
			$ad_info = $this->Ad->where('ad_id='.$id)->find();
	
			$this->assign('info', $ad_info);	
			$this->display("Ad:add");
		}
	}

}
?>