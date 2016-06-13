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
 * $Id: AdPositionController.class.php 2015-12-10 Lessismore $
*/
namespace Admin\Controller;
use Think\Controller;
use Think\Model;
class AdPositionController extends CommonController {
	
	// 实际表名
	protected $t_n_article='td_ad_position';
	protected  $AdPosition;

	/**
      +----------------------------------------------------------
     * 初始化
     * 如果 继承本类的类自身也需要初始化那么需要在使用本继承类的类里使用parent::_initialize();
      +----------------------------------------------------------
     */
	public function _initialize() {
		parent::_initialize();
		$this->AdPosition = D("AdPosition");
	}

	/**
      +----------------------------------------------------------
     * 显示广告位列表
      +----------------------------------------------------------
     */
	public function index(){
		//分配导航栏当前位置
		$this->assign('navigation_bar','门户管理>广告位管理');
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
			$where .= " and position_name like '%$new_keyword%'";
		}
	
		$count = $this->AdPosition->where($where)->count();
		$pageNum = 20;
		$page = new \Think\Page($count,$pageNum);
		$show = $page->show();		
		$list = $this->AdPosition->field('position_id,position_name,ad_width,ad_height,area_code,position_desc,create_time,update_time')->where($where)->order("create_time desc")->limit($page->firstRow.','.$page->listRows)->select();
		if(is_array($list) && count($list)){
			foreach ($list as $key=>$val){
				$list[$key]['key']=++$page->firstRow;
			}
		}

		$this->assign('keyword',$keyword);
		$this->assign('list_empty','<tr align="center"><td colspan="10" align="center"><span>广告位列表为空！请先创建新广告位！</span></td></tr>');
		$this->assign('list',$list);
		$this->assign('page',$show);
		$this->display();
	}

	/**
      +----------------------------------------------------------
     * 添加新广告位
      +----------------------------------------------------------
     */
	public function mAdd(){
		if(IS_POST){
			$data = array();
			$data['position_name'] = isset($_POST['position_name']) ? trim($_POST['position_name']) : '' ;
			$data['ad_width'] = isset($_POST['ad_width']) ? intval($_POST['ad_width']) : 0 ;
			$data['ad_height'] = isset($_POST['ad_height']) ? intval($_POST['ad_height']) : 0 ;;
			$data['position_desc'] = isset($_POST['position_desc']) ? trim($_POST['position_desc']) : '' ;
			$area_code_page = isset($_POST['area_code_page']) ? trim($_POST['area_code_page']) : '' ;
			$area_code_module = isset($_POST['area_code_module']) ? trim($_POST['area_code_module']) : '' ;
			$area_code_sort = isset($_POST['area_code_sort']) ? trim($_POST['area_code_sort']) : 0 ;
			$data['create_time'] = time();
			$data['update_time'] = $data['create_time'];
			$data['__hash__'] = $_POST['__hash__'];
			//检测初始数据是否符合规则
			if(empty($area_code_page) || empty($area_code_module) || $area_code_sort){
				$data['area_code'] = $area_code_page."-".$area_code_module."-".$area_code_sort;
				if(!empty($data['position_name'])){
					$result = $this->AdPosition->where("position_name='".$data['position_name']."'")->count();
					if(!$result){
						//检验数据
						$data = $this->AdPosition->create($data, 1); //1是插入操作，0是更新操作
						if ($this->AdPosition->add($data)){
							$this->success("添加广告位成功！", U('AdPosition:index'));
						} else {
							$this->error($this->AdPosition->getError(),U('AdPosition:index'));
						}
					}else{
						$this->error('广告位名称'.$data['position_name'].'已经存在',U('AdPosition:index'));
					}
				}else{
					$this->error('广告位名称不能为空！',U('AdPosition:index'));
				}
			}else{
				$this->error('广告位区域码不能为空！',U('AdPosition:index'));
			}

		} else {
			// 分配导航栏当前位置
			$this->assign('navigation_bar','门户管理>广告位管理');
			$this->assign('act',ACTION_NAME);
			// 关键字搜索默认值
			$keyword = '';
			// 评论状态筛选默认值
			$type = 0;
			$where = '1 = 1';
			if(isset($_REQUEST['keyword'])){
				$keyword = $_REQUEST['keyword'];
				//删除所有单引号
				if(stristr($keyword,'\'')){
					$keyword = str_replace('\'','',$keyword);
				}
				$new_keyword = htmlspecialchars(trim($keyword));
				$where .= " and position_name like '%$new_keyword%'";
			}
		
			$count = $this->AdPosition->where($where)->count();
			$pageNum = 20;
			$page = new \Think\Page($count,$pageNum);
			$show = $page->show();		
			$list = $this->AdPosition->field('position_id,position_name,ad_width,ad_height,area_code,position_desc,create_time,update_time')->where($where)->order("create_time desc")->limit($page->firstRow.','.$page->listRows)->select();
			if(is_array($list) && count($list)){
				foreach ($list as $key=>$val){
					$list[$key]['key']=++$page->firstRow;
				}
			}

			$this->assign('keyword',$keyword);
			$this->assign('list_empty','<tr align="center"><td colspan="10" align="center"><span>广告位列表为空！请先创建新广告位！</span></td></tr>');
			$this->assign('list',$list);
			$this->assign('page',$show);
			$this->display("AdPosition:index");
		}
	}
	
	/**
      +----------------------------------------------------------
     * ajax检测广告位名称是否重复（新增模式）
      +----------------------------------------------------------
     */
	public function ajax_check_name(){
		//fwrites(APP_PATH . 'Admin/ajax.txt',"@param(ajax_check_name)---这是ajax请求！");
		$dataResult = array();
		$dataResult['flag'] = 1; //默认为1表示无任何错误
		$dataResult['msg'] = '广告位名称不重复！'; //ajax提示信息
		$dataResult['data'] = ''; //返回数据、修改成功则返回、修改后的数据

		if(IS_AJAX){
			if(isset($_POST['name']) && !empty($_POST['name'])){
				$name = $_POST['name'];
				//删除所有单引号
				if(stristr($name,'\'')){
					$name = str_replace('\'','',$name);
				}
				$name = htmlspecialchars(trim($name));
				$result = $this->AdPosition->where("position_name = '$name'")->count();
				if(!$result){
					$dataResult['flag'] = 1; //默认为1表示无任何错误
					$dataResult['msg'] = '广告位名称输入正确！'; //ajax提示信息
					$dataResult['data'] = '';
					//fwrites(APP_PATH . 'Admin/ajax.txt',"名字合法！");	
				}else{
					$dataResult['flag'] = 0; //默认为1表示无任何错误
					$dataResult['msg'] = '广告位名称重复！'; //ajax提示信息
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
     * ajax检测广告位名称是否重复（编辑模式）
      +----------------------------------------------------------
     */
	public function ajax_check_edit_name(){
		//fwrites(APP_PATH . 'Admin/ajax.txt',"@param(ajax_check_name)---这是ajax请求！");
		$dataResult = array();
		$dataResult['flag'] = 1; //默认为1表示无任何错误
		$dataResult['msg'] = '广告位名称不重复！'; //ajax提示信息
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
				$now_adp = $this->AdPosition->where("position_id = $id")->find();
				if(count($now_adp) ){
					if(!empty($now_adp['position_name']) && $now_adp['position_name'] == $name){
						$dataResult['flag'] = 1; //默认为1表示无任何错误
						$dataResult['msg'] = '广告位名称输入正确！'; //ajax提示信息
						$dataResult['data'] = '';
					}else{
						$result = $this->AdPosition->where("position_name = '$name'")->count();
						if(!$result){
							$dataResult['flag'] = 1; //默认为1表示无任何错误
							$dataResult['msg'] = '广告位名称输入正确！'; //ajax提示信息
							$dataResult['data'] = '';
							//fwrites(APP_PATH . 'Admin/ajax.txt',"名字合法！");	
						}else{
							$dataResult['flag'] = 0; //默认为1表示无任何错误
							$dataResult['msg'] = '广告位名称重复！'; //ajax提示信息
							$dataResult['data'] = '';	
						}
					}
				}else{
					$dataResult['flag'] = 0; //默认为1表示无任何错误
					$dataResult['msg'] = '该广告位已经不存在！'; //ajax提示信息
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
						$result = $this->AdPosition->where("position_name='".$data['position_name']."'")->count();
						//fwrites(WEB_ROOT . 'ajax.txt',$result);
						if(intval($result) < 2){
							//检验数据
							$data = $this->AdPosition->create($data, 0); //1是插入操作，0是更新操作
							$a_result = $this->AdPosition->where("position_id=" . $data['position_id'])->save($data);
							if(false !== $a_result){
								$this->success("编辑广告位成功！", U('AdPosition/index'));
							} else {
								$this->error($this->Article->getError(),U('AdPosition/mEdit','id='.$data['position_id']));
							}
						}else{
							$this->error('广告位名称'.$data['position_name'].'已经存在',U('AdPosition/mEdit','id='.$data['position_id']));
						}
					}else{
						$this->error('广告位名称不能为空！',U('AdPosition/mEdit','id='.$data['position_id']));
					}

				}else{
					$this->error('数据有误！',U('AdPosition/index'));
				}
			}else{
				$this->error('广告位区域码不能为空！',U('AdPosition/mEdit','id='.$data['position_id']));
			}
		} else {
			if( isset($_GET['id']) ){
				$id = isset($_GET['id']) && intval($_GET['id']) ? intval($_GET['id']) : $this->error('参数错误',U('AdPostion/index'));
			}
			//分配导航栏当前位置
			$this->assign('navigation_bar','门户管理>编辑广告位');
			$this->assign('act',ACTION_NAME);
			$adp_info = $this->AdPosition->where('position_id='.$id)->find();
			if($adp_info && count($adp_info) && $adp_info['area_code']){
				$tmp_area_code = $adp_info['area_code'];
				$arr = explode('-',$tmp_area_code);
				if(count($arr)){
					$adp_info['area_code_page'] = $arr[0];
					$adp_info['area_code_module'] = $arr[1];
					$adp_info['area_code_sort'] = $arr[2];
				}
			}
			$this->assign('info', $adp_info);	
			$this->display("AdPosition:add");
		}
	}
	
	/**
      +----------------------------------------------------------
     * ajax删除广告位
      +----------------------------------------------------------
     */
	public function ajax_del_position(){
		$dataResult = array();
		$dataResult['flag'] = 1; //默认为1表示无任何错误
		$dataResult['msg'] = '删除成功！'; //ajax提示信息
		$dataResult['data'] = ''; //返回数据、修改成功则返回、修改后的数据
		if (IS_AJAX) {
			//$art_info = $this->Article->where('id='.$id)->find();
			$id = intval($_POST['id']) ? intval($_POST['id']) : 0 ;
			//fwrites(APP_PATH . 'Admin/ajax.txt',$id);
			$a_result = $this->AdPosition->delete($id);
			if($a_result){
				$dataResult['data'] = 'success'; //返回数据、修改成功则返回、修改后的数据
				$this->ajaxReturn($dataResult,'JSON');
			}else{
				$dataResult['flag'] = 0; //默认为1表示无任何错误
				$dataResult['msg'] = '删除失败！请稍后重新操作！'; //ajax提示信息
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
	 
}
?>