<?php
/**
 * 论坛管理
 * ============================================================================
 * 版权所有 2005-2080 Lessismore，并保留所有权利。
 * 网站地址: http://www.trydemo.net；
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和
 * 使用；不允许对程序代码以任何形式任何目的的再发布。
 * ============================================================================
 * $Author: lsq & Lessismore & D.Apache.Luo
 * $Id: BbsController.class.php 2016-3-2 Lessismore $
*/
namespace Admin\Controller;
use Think\Controller;
use Think\Model;
class BbsController extends CommonController {
	
	protected $Comments;

	/**
      +----------------------------------------------------------
     * 初始化
     * 如果 继承本类的类自身也需要初始化那么需要在使用本继承类的类里使用parent::_initialize();
      +----------------------------------------------------------
     */
	public function _initialize() {
		parent::_initialize();
		$this->Comments = D("Comments");
	}
	
	/**
      +----------------------------------------------------------
     * 评论管理
      +----------------------------------------------------------
     */
	 public function mComments(){
		 //分配导航栏当前位置
		$this->assign('navigation_bar','论坛管理>评论管理');
		$this->assign('act',strtolower(ACTION_NAME));
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
			$where .= " and title like '%$new_keyword%'";
		}
		
		if(isset($_REQUEST['dropdown_type'])){
			$type = intval(htmlspecialchars(trim($_REQUEST['dropdown_type'])));
			switch($type){
				case 1:
					$where .= " and comment_approved = 1";
					break;
				case 2:
					$where .= " and comment_approved = 0";
					break;
				case 3:
					$where .= " and is_lock = 1";
					break;
				case 4:
					$where .= " and is_lock = 0";
					break;
				default:
					break;
			}
		}
		
		$count = $this->Comments->where($where)->count();
		$pageNum = 20;
		$page = new \Think\Page($count,$pageNum);
		$show = $page->show();
		$list = $this->Comments->where($where)->order("cid desc")->limit($page->firstRow.','.$page->listRows)->select();
		foreach ($list as $key=>$val){
			$list[$key]['key']=++$page->firstRow;
		}
		
		$this->assign('keyword',$keyword);
		$this->assign('type',$type);
		$this->assign('list',$list);
		$this->assign('page',$show);
		$this->display("Bbs:comments");
	 }
	 
	 /**
      +----------------------------------------------------------
     * ajax更改博文评论锁定状态
      +----------------------------------------------------------
     */
	 public function ajax_update_lock(){
		 $dataResult = array();
		$dataResult['flag'] = 1; //默认为1表示无任何错误
		$dataResult['msg'] = '锁定状态更新成功！'; //ajax提示信息
		$dataResult['data'] = ''; //返回数据、修改成功则返回、修改后的数据
		if (IS_AJAX) {
			$id = intval(trim($_REQUEST['id']));
			$data['cid'] = $id;
			$is_lock = $this->Comments->where($data)->getField('is_lock');
			$set = array();
			if($is_lock == 1){
				$set = array('is_lock'=>0);		
			}elseif($is_lock == 0){
				$set = array('is_lock'=>1);
			}else{
				$set = array('is_lock'=>0);
			}
			$u_result = $this->Comments->where($data)->save($set);	
			if($u_result){
				$dataResult['data'] = $set['is_lock'];
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
     * ajax更改博文评论审核状态
      +----------------------------------------------------------
     */
	 public function ajax_update_approved(){
		 $dataResult = array();
		$dataResult['flag'] = 1; //默认为1表示无任何错误
		$dataResult['msg'] = '审核状态更新成功！'; //ajax提示信息
		$dataResult['data'] = ''; //返回数据、修改成功则返回、修改后的数据
		if (IS_AJAX) {
			$id = intval(trim($_REQUEST['id']));
			$data['cid'] = $id;
			$comment_approved = $this->Comments->where($data)->getField('comment_approved');
			$set = array();
			if($comment_approved == 1){
				$set = array('comment_approved'=>0);		
			}elseif($comment_approved == 0){
				$set = array('comment_approved'=>1);
			}else{
				$set = array('comment_approved'=>0);
			}
			$u_result = $this->Comments->where($data)->save($set);
			if($u_result){
				$dataResult['data'] = $set['comment_approved'];
				$this->ajaxReturn($dataResult,'JSON');				
			}else{
				$dataResult['flag'] = 0; //默认为1表示无任何错误
				$dataResult['msg'] = '审核状态更新失败！请稍后重新操作！'; //ajax提示信息
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
     * ajax删除评论
      +----------------------------------------------------------
     */
	 public function ajax_del_comment(){
		 $dataResult = array();
		$dataResult['flag'] = 1; //默认为1表示无任何错误
		$dataResult['msg'] = '删除评论成功！'; //ajax提示信息
		$dataResult['data'] = ''; //返回数据、修改成功则返回、修改后的数据
		//fwrites(APP_PATH . 'Admin/ajax.txt','开始删除！');
		if (IS_AJAX) {
			$id = intval($_POST['id']) ? intval($_POST['id']) : 0 ;
			if($id){
				$c_result = $this->Comments->delete($id);
				if($c_result){
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
				$dataResult['msg'] = '删除失败！该文章已不存在！'; //ajax提示信息
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
     * 显示编辑或者修改评论页面
      +----------------------------------------------------------
     */
	public function mEdit(){

		if (IS_POST) {
			$data = array();
			$data['cid'] = intval($_POST['id']) ? intval($_POST['id']) : 0 ;
			$data['comment_approved'] = isset($_POST['comment_approved']) ? intval(trim($_POST['comment_approved'])) : 0 ;
			$data['is_lock'] = isset($_POST['is_lock']) ? intval(trim($_POST['is_lock'])) : 0 ;
			$data['comment_content'] = isset($_POST['comment_content']) ? $_POST['comment_content'] : '' ;
			$data['comment_edit_date'] = date('Y-m-d H:i:s',time());
			$data['__hash__'] = $_POST['__hash__'];
			if(mb_strlen($data['comment_content']) > 240){
				$data['comment_content'] = mb_substr($data['comment_content'],0,240);
			}
			
			//检测初始数据是否符合规则
			if($data['cid']){
				if($data['comment_content']){
					//检验数据
					$data = $this->Comments->create($data, 0); //1是插入操作，0是更新操作
					$a_result = $this->Comments->where("cid=" . $data['cid'])->save($data);
					if ($a_result){
						$this->success("编辑评论成功！", U('Bbs/mComments'));
					} else {
						$this->error($this->Comments->getError(),U('Bbs/mEdit','id='.$data['cid']));
					}
				}else{
					$this->error('评论内容不能为空！',U('Bbs/mEdit','id='.$data['cid']));
				}
			}else{
				$this->error('数据有误！',U('Bbs/mEdit','id='.$data['cid']));
			}
		} else {
			// 分配导航栏当前位置
			$this->assign('navigation_bar','论坛管理>编辑评论信息');
			$this->assign('act',strtolower(ACTION_NAME));
			$id = isset($_GET['id']) && intval($_GET['id']) ? intval($_GET['id']) : $this->error('参数错误',U('File/index'));
			$comment_info = $this->Comments->where('cid='.$id)->find();
			$this->assign('info', $comment_info);
			$this->display("Bbs:add");
		}
	}
	 
}
?>