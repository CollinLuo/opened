<?php
/**
 * 栏目管理
 * ============================================================================
 * 版权所有 2005-2080 Lessismore，并保留所有权利。
 * 网站地址: http://www.trydemo.net；
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和
 * 使用；不允许对程序代码以任何形式任何目的的再发布。
 * ============================================================================
 * $Author: lsq & Lessismore & D.Apache.Luo
 * $Id: UserActivity.class.php 2014-5-19 Lessismore $
*/
namespace Admin\Controller;
use Think\Controller;
use Think\Model;
class CategoryController extends CommonController {

	public function index(){
		//分配导航栏当前位置
		$this->assign('navigation_bar','门户管理>所有栏目');
		$cat_mod = D('Category');
		$where = '';
		$count = $cat_mod->where($where)->count();
		$list = $cat_mod->select();
		
		//调用公用函数库自动生成权限列表树
		//import('Common.Common.arrayHelper',APP_PATH,'.php'); //导入框架外部自定制函数库(数组处理)!ThinkPHP3.2.3无法支持
		//$tree_list = ArrayHelper::toTree($list, 'id', 'pid', 'children');
		$arrayHelper = new \Org\Util\ArrayHelper();
		$tree_list = $arrayHelper::toTree($list, 'id', 'pid', 'children');
		//p($tree_list);
		$this->assign('list',$tree_list);
		$this->assign('list_empty','<tr align="center"><td colspan="7" align="center"><span>栏目列表为空！请先创建栏目！</span></td></tr>');
		$this->display('Category:cat_index');
	}
	
	//ajax修改排序
	public function ajax_update_sort(){
		if($this->isAjax()){
			/* ajax请求开始 */
			//fwrites(WEB_ROOT . 'ajax.txt',"这是ajax请求！");
			$ajax_err = 1; //默认为1表示无任何错误
			$ajax_msg = '修改成功！'; //ajax提示信息
			$ajax_data = ''; //返回数据、修改成功则返回、修改后的数据

			$id = intval(trim($_REQUEST['id']));
			$new_sort = intval(trim($_REQUEST['sort']));
			//fwrites(WEB_ROOT . 'ajax.txt',$id);
			//fwrites(WEB_ROOT . 'ajax.txt',$new_sort);
			$cat_mod = M('Category');
			$data['id'] = $id;
			$set['sort'] = $new_sort;
			$arr = $cat_mod->field('sort')->where($data)->find();
			//fwrites(WEB_ROOT . 'ajax.txt','---->$arr');
			//fwrites(WEB_ROOT . 'ajax.txt',$arr);
			if(!$arr || empty($arr) || $arr['sort'] == $new_sort){
				//fwrites(WEB_ROOT . 'ajax.txt',1111111);
				$ajax_err = 0;
				$ajax_msg = '修改失败！'; 
			}else{
				$u_num = $cat_mod->where($data)->save($set);
				//fwrites(WEB_ROOT . 'ajax.txt',$u_num);
				if($u_num){
					$ajax_err = 1;
					$ajax_msg = '修改成功！';
					$ajax_data = $new_sort;
				}else{
					$ajax_err = 0;
					$ajax_msg = '修改失败！'; 
				}
			}

			$this->ajaxReturn($ajax_data,$ajax_msg,$ajax_err);

		}else{
			/* 非ajax请求 */
		}
	}

	//ajax更改是否是导航状态
	public function ajax_update_navigation(){
		if($this->isAjax()){
			fwrites(WEB_ROOT . 'ajax.txt',"这是ajax请求！");
			$ajax_err = 1; //默认为1表示无任何错误
			$ajax_msg = '修改成功！'; //ajax提示信息
			$ajax_data = ''; //返回数据、修改成功则返回、修改后的数据

			$id = intval(trim($_REQUEST['id']));
			$cat_mod = M('Category');
			$data['id'] = $id;
			$arr = $cat_mod->field('is_navigation')->where($data)->find();
			fwrites(WEB_ROOT . 'ajax.txt',$data);
			fwrites(WEB_ROOT . 'ajax.txt','------------------>');
			fwrites(WEB_ROOT . 'ajax.txt',$arr);
			$set = array();
			if($arr['is_navigation'] == 1){
				$set = array('is_navigation'=>0);		
			}elseif($arr['is_navigation'] == 0){
				$set = array('is_navigation'=>1);
			}else{
				$set = array('is_navigation'=>0);
			}

			if(!$arr || empty($arr)){
				//fwrites(WEB_ROOT . 'ajax.txt',1111111);
				$ajax_err = 0;
				$ajax_msg = '修改失败！'; 
			}else{
				$u_num = $cat_mod->where($data)->save($set);
				if($u_num){
					$ajax_err = 1;
					$ajax_msg = '修改成功！';
					$ajax_data = $set['is_navigation'];
					fwrites(WEB_ROOT . 'ajax.txt',"修改成功！");
				}else{
					$ajax_err = 0;
					$ajax_msg = '修改失败！'; 
				}
			}
			$this->ajaxReturn($ajax_data,$ajax_msg,$ajax_err);
		}else{
			/* 非ajax请求 */
		}
	}

	//ajax更改是否启用状态
	public function ajax_update_open(){
		if($this->isAjax()){
			//fwrites(WEB_ROOT . 'ajax.txt',"这是ajax请求！");
			$ajax_err = 1; //默认为1表示无任何错误
			$ajax_msg = '修改成功！'; //ajax提示信息
			$ajax_data = ''; //返回数据、修改成功则返回、修改后的数据

			$id = intval(trim($_REQUEST['id']));
			$cat_mod = M('Category');
			$data['id'] = $id;
			$arr = $cat_mod->field('is_open')->where($data)->find();
			$set = array();
			if($arr['is_open'] == 1){
				$set = array('is_open'=>0);		
			}elseif($arr['is_open'] == 0){
				$set = array('is_open'=>1);
			}else{
				$set = array('is_open'=>0);
			}

			if(!$arr || empty($arr)){
				//fwrites(WEB_ROOT . 'ajax.txt',1111111);
				$ajax_err = 0;
				$ajax_msg = '修改失败！'; 
			}else{
				$u_num = $cat_mod->where($data)->save($set);
				if($u_num){
					$ajax_err = 1;
					$ajax_msg = '修改成功！';
					$ajax_data = $set['is_open'];
					fwrites(WEB_ROOT . 'ajax.txt',"修改成功！");
				}else{
					$ajax_err = 0;
					$ajax_msg = '修改失败！'; 
				}
			}
			$this->ajaxReturn($ajax_data,$ajax_msg,$ajax_err);
		}else{
			/* 非ajax请求 */
		}
	}

	public function mAdd(){
		$cat_mod = M('Category');
		if (IS_POST) {
			$data = array();
			$data['name'] = isset($_POST['name']) ? trim($_POST['name']) : '' ;
			$data['keywords'] = isset($_POST['keywords']) ? trim($_POST['keywords']) : '' ;
			$data['pid'] = isset($_POST['pid']) ? intval(trim($_POST['pid'])) : 0 ;
			$data['is_open'] = isset($_POST['is_open']) ? intval(trim($_POST['is_open'])) : 0 ;
			$data['indextpl'] = isset($_POST['indextpl']) ? trim($_POST['indextpl']) : '' ;
			$data['listtpl'] = isset($_POST['listtpl']) ? trim($_POST['listtpl']) : '' ;
			$data['articletpl'] = isset($_POST['articletpl']) ? trim($_POST['articletpl']) : '' ;
			$data['sort'] = isset($_POST['sort']) ? intval(trim($_POST['sort'])) : 50 ;
			$data['intro'] = isset($_POST['intro']) ? trim($_POST['intro']) : '' ;
			$data['add_time'] = date('Y-m-d H:i:s',time());
			$data['edit_time'] = date('Y-m-d H:i:s',time());
			$data['__hash__'] = $_POST['__hash__'];
			//fwrites(WEB_ROOT . 'ajax.txt',$data);
			if(!empty($data['name'])){
				$result = $cat_mod->where("name='".$data['name']."'")->count();
				//fwrites(WEB_ROOT . 'ajax.txt','----------->检测是否已经存在！');
				//fwrites(WEB_ROOT . 'ajax.txt',$result);
				if(!$result){
					//检验数据
					$data = $cat_mod->create($data, 1); //1是插入操作，0是更新操作
					if ($cat_mod->add($data)){
						$this->success("添加栏目成功！", U('Category/index'));
					} else {
						$this->error($cat_mod->getError(),U('Category/mAdd'));
					}

				}else{
					$this->error('栏目'.$data['name'].'已经存在',U('Category/mAdd'));
				}
				  
			}else{
				$this->error('新增栏目名称不能为空！',U('Category/mAdd'));
			}

		} else {
			//分配导航栏当前位置
			$this->assign('navigation_bar','门户管理>添加栏目');
			$this->assign('act',$Think.ACTION_NAME);
		
			//获取所有父级栏目
			$cat_mod = M('Category');
			$p_arr = $cat_mod->where('is_open=1 AND pid=0')->select();
			$this->assign('p_list',$p_arr);
			$this->display("Category:cat_add");
		}

	}

	//显示编辑或者修改栏目信息页面
	public function mEdit(){

		if (IS_POST) {
			$data = array();
			$data['id'] = intval($_POST['id']) ? intval($_POST['id']) : 0 ;
			$data['name'] = isset($_POST['name']) ? trim($_POST['name']) : '' ;
			$data['keywords'] = isset($_POST['keywords']) ? trim($_POST['keywords']) : '' ;
			$data['pid'] = isset($_POST['pid']) ? intval(trim($_POST['pid'])) : 0 ;
			$data['is_open'] = isset($_POST['is_open']) ? intval(trim($_POST['is_open'])) : 0 ;
			$data['indextpl'] = isset($_POST['indextpl']) ? trim($_POST['indextpl']) : '' ;
			$data['listtpl'] = isset($_POST['listtpl']) ? trim($_POST['listtpl']) : '' ;
			$data['articletpl'] = isset($_POST['articletpl']) ? trim($_POST['articletpl']) : '' ;
			$data['sort'] = isset($_POST['sort']) ? intval(trim($_POST['sort'])) : 50 ;
			$data['intro'] = isset($_POST['intro']) ? trim($_POST['intro']) : '' ;
			$data['add_time'] = date('Y-m-d H:i:s',time());
			$data['edit_time'] = date('Y-m-d H:i:s',time());
			$data['__hash__'] = $_POST['__hash__'];

			//检测初始数据是否符合规则(密码是否合格、邮箱是否添加、是否重名)
			if($data['id']){
				if(!$data['name']){
					unset($data['name']);
				}

				if(!$data['keywords']){
					unset($data['keywords']);
				}

				if(!$data['indextpl']){
					unset($data['indextpl']);
				}

				if(!$data['listtpl']){
					unset($data['listtpl']);
				}

				if(!$data['articletpl']){
					unset($data['articletpl']);
				}

				if(!$data['intro']){
					unset($data['intro']);
				}

				//开始执行更新
				$cat_mod = M('Category');	
				$result = $cat_mod->where("id='".$data['id']."'")->count();
				if($result){
					//检验数据
					$data = $cat_mod->create($data, 0); //1是插入操作，0是更新操作
					$u_result = $cat_mod->where("id=" . $data['id'])->save($data);
					if(false !== $u_result){
						$this->success("编辑栏目成功！", U('Category/index'));
					} else {
						$this->error($cat_mod->getError(),U('Category/mEdit','id='.$data['id']));
					}

				}else{
					$this->error('栏目'.$data['name'].'已经不存在！',U('Category/index'));
				}

			}else{
				$this->error('栏目记录丢失！',U('Category/index'));
			}

		} else {
			//分配导航栏当前位置
			$this->assign('navigation_bar','门户管理>编辑栏目信息');
			$this->assign('act',$Think.ACTION_NAME);
			
		
			if( isset($_GET['id']) ){
				$id = isset($_GET['id']) && intval($_GET['id']) ? intval($_GET['id']) : $this->error('参数错误',U('Category/index'));
			}
			$cat_mod = M('Category');
			$cat_info = $cat_mod->where('id='.$id)->find();
			//获取所有父级栏目
			$p_arr = $cat_mod->where('pid=0')->select();
			$this->assign('p_list',$p_arr);
			//p($cat_info);
			//p($p_arr);
			$this->assign('cat_info', $cat_info);
			$this->display("Category:cat_add");
		}
	}

	//删除
	public function ajax_del_users(){
	
	}

}
?>