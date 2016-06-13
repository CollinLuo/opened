<?php
/**
 * 博客管理
 * ============================================================================
 * 版权所有 2005-2080 Lessismore，并保留所有权利。
 * 网站地址: http://www.trydemo.net；
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和
 * 使用；不允许对程序代码以任何形式任何目的的再发布。
 * ============================================================================
 * $Author: lsq & Lessismore & D.Apache.Luo
 * $Id: BlogController.class.php 2014-5-15 Lessismore $
*/
namespace Admin\Controller;
use Think\Controller;
use Think\Model;
class BlogController extends CommonController {
	
	// 实际表名
	protected $t_n_article='td_article';
	protected  $Article,$Comments;

	/**
      +----------------------------------------------------------
     * 初始化
     * 如果 继承本类的类自身也需要初始化那么需要在使用本继承类的类里使用parent::_initialize();
      +----------------------------------------------------------
     */
	public function _initialize() {
		parent::_initialize();
		$this->Article = D("Article");
		$this->Comments = D("Comments");
	}

	/**
      +----------------------------------------------------------
     * 显示博文列表
      +----------------------------------------------------------
     */
	public function index(){
		//分配导航栏当前位置
		$this->assign('navigation_bar','博客管理>所有文章');
		//回收站关键字(默认为非回收站)
		$mTrash_act = 0;
		//关键字搜索默认值
		$keyword = '';
		//评论状态筛选默认值
		$type = 0;
		$where = 'is_del = 0';
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
					$where .= " and is_lock = 1";
					break;
				case 2:
					$where .= " and is_lock = 0";
					break;
				default:
					break;
			}
		}
	
		$count = $this->Article->where($where)->count();
		$pageNum = 20;
		$page = new \Think\Page($count,$pageNum);
		$show = $page->show();		
		$list = $this->Article->join('as ar left join td_category as ca on ca.id = ar.cid')->field('ar.id,ar.post_author_id,ar.post_author_name,ar.title,ar.cid,ar.clicks,ar.release_time,ar.last_mod_time,ar.post_author_ip,ar.comment_status,ar.is_lock,ar.is_del,ca.name as cname')->where($where)->order("ar.release_time desc")->limit($page->firstRow.','.$page->listRows)->select();
		if(is_array($list) && count($list)){
			foreach ($list as $key=>$val){
				$list[$key]['key']=++$page->firstRow;
			}
		}

		$this->assign('mTrash_act',$mTrash_act);
		$this->assign('keyword',$keyword);
		$this->assign('type',$type);
		$this->assign('list_empty','<tr align="center"><td colspan="10" align="center"><span>文章列表为空！请先创建新文章！</span></td></tr>');
		$this->assign('list',$list);
		$this->assign('page',$show);
		$this->display();
	}

	/**
      +----------------------------------------------------------
     * 添加新博文
      +----------------------------------------------------------
     */
	public function mAdd(){
		if(IS_POST){
			$data = array();
			$data['post_author_id'] = $_SESSION['my_info']['aid'];
			$data['post_author_name'] = $_SESSION['my_info']['username'];
			$data['post_author_ip'] = get_client_ip();
			$data['title'] = isset($_POST['title']) ? trim($_POST['title']) : '' ;
			if (!empty($_POST['content'])) {
				if (get_magic_quotes_gpc()) {
					$data['content'] = stripslashes($_POST['content']);
				} else {
					$data['content'] = $_POST['content'];
				}
			}else{
				$data['content'] = '';
			}
			$data['seo_title'] = isset($_POST['seo_title']) ? trim($_POST['seo_title']) : '' ;
			$data['keywords'] = isset($_POST['keywords']) ? trim($_POST['keywords']) : '' ;
			$data['author_name'] = isset($_POST['author_name']) ? trim($_POST['author_name']) : '' ;
			$data['cid'] = intval($_POST['category']) ? intval($_POST['category']) : 0 ;
			$data['source'] = isset($_POST['source']) ? trim($_POST['source']) : '' ;
			$data['is_hot'] = intval($_POST['is_hot']) ? intval($_POST['is_hot']) : 0 ;
			$data['is_top'] = intval($_POST['is_top']) ? intval($_POST['is_top']) : 0 ;
			$data['is_skip'] = intval($_POST['is_skip']) ? intval($_POST['is_skip']) : 0 ;
			$data['is_recommend'] = intval($_POST['is_recommend']) ? intval($_POST['is_recommend']) : 0 ;
			$data['comment_status'] = intval($_POST['comment_status']) ? intval($_POST['comment_status']) : 0 ;
			$data['description'] = isset($_POST['description']) ? trim($_POST['description']) : '' ;
			$data['seo_desc'] = isset($_POST['seo_desc']) ? trim($_POST['seo_desc']) : '' ;
			$data['release_time'] = date("Y-m-d H:i:s");
			$data['last_mod_time'] = date("Y-m-d H:i:s");
			$data['__hash__'] = $_POST['__hash__'];

			//检测初始数据是否符合规则
			if($data['post_author_id'] && $data['post_author_name']){
				if(!empty($data['title'])){
					$result = $this->Article->where("title='".$data['title']."'")->count();
					if(!$result){
						//检验数据
						$data = $this->Article->create($data, 1); //1是插入操作，0是更新操作
						if ($this->Article->add($data)){
							$this->success("添加文章成功！", U('Blog/index'));
						} else {
							$this->error($this->Article->getError(),U('Blog/mAdd'));
						}
					}else{
						$this->error('文章'.$data['title'].'已经存在',U('Blog/mAdd'));
					}
				}else{
					$this->error('文章标题不能为空！',U('Blog/mAdd'));
				}
			}else{
				$this->error('请先登录！',U('Public/index'));
			}

		} else {
			//分配导航栏当前位置
			$this->assign('navigation_bar','博客管理>添加文章');
			$this->assign('act',$Think.ACTION_NAME);
			$cat_mod = D("Category");
			$list = $cat_mod->select();
			//调用公用函数库自动生成权限列表树
			//import('Common.Functions.arrayHelper',WEB_ROOT,'.php'); //导入框架外部自定制函数库(数组处理)
			$arrayHelper = new \Org\Util\ArrayHelper();
			$tree_list = $arrayHelper::toTree($list, 'id', 'pid', 'children');
			$this->assign('catList',$tree_list);
			$this->assign('list_empty','<option value="0">栏目列表为空！请先创建栏目</option>');
			$this->display("Blog:add");
		}
	}
	

	/**
      +----------------------------------------------------------
     * 编辑
      +----------------------------------------------------------
     */
	public function mEdit(){
		if(IS_POST){
			$data = array();
			$data['id'] = intval($_POST['id']) ? intval($_POST['id']) : 0 ;
			$data['post_author_id'] = $_SESSION['my_info']['aid'];
			$data['post_author_name'] = $_SESSION['my_info']['username'];
			$data['post_author_ip'] = get_client_ip();
			$data['title'] = isset($_POST['title']) ? trim($_POST['title']) : '' ;
			if (!empty($_POST['content'])) {
				if (get_magic_quotes_gpc()) {
					$data['content'] = stripslashes($_POST['content']);
				} else {
					$data['content'] = $_POST['content'];
				}
			}else{
				$data['content'] = '';
			}
			$data['seo_title'] = isset($_POST['seo_title']) ? trim($_POST['seo_title']) : '' ;
			$data['keywords'] = isset($_POST['keywords']) ? trim($_POST['keywords']) : '' ;
			$data['author_name'] = isset($_POST['author_name']) ? trim($_POST['author_name']) : '' ;
			$data['cid'] = intval($_POST['category']) ? intval($_POST['category']) : 0 ;
			$data['source'] = isset($_POST['source']) ? trim($_POST['source']) : '' ;
			$data['is_hot'] = intval($_POST['is_hot']) ? intval($_POST['is_hot']) : 0 ;
			$data['is_top'] = intval($_POST['is_top']) ? intval($_POST['is_top']) : 0 ;
			$data['is_skip'] = intval($_POST['is_skip']) ? intval($_POST['is_skip']) : 0 ;
			$data['is_recommend'] = intval($_POST['is_recommend']) ? intval($_POST['is_recommend']) : 0 ;
			$data['comment_status'] = intval($_POST['comment_status']) ? intval($_POST['comment_status']) : 0 ;
			$data['description'] = isset($_POST['description']) ? trim($_POST['description']) : '' ;
			$data['seo_desc'] = isset($_POST['seo_desc']) ? trim($_POST['seo_desc']) : '' ;
			$data['last_mod_time'] = date("Y-m-d H:i:s");
			$data['__hash__'] = $_POST['__hash__'];

			//fwrites(WEB_ROOT . 'ajax.txt',"这是edit请求！");
			//fwrites(WEB_ROOT . 'ajax.txt',$data);

			//检测初始数据是否符合规则
			if($data['post_author_id'] && $data['post_author_name']){
				if($data['id']){
					if(!empty($data['title'])){
						$result = $this->Article->where("title='".$data['title']."'")->count();
						//fwrites(WEB_ROOT . 'ajax.txt',$result);
						if(intval($result) < 2){
							//检验数据
							$data = $this->Article->create($data, 0); //1是插入操作，0是更新操作
							$a_result = $this->Article->where("id=" . $data['id'])->save($data);
							if(false !== $a_result){
								$this->success("编辑文章成功！", U('Blog/index'));
							} else {
								$this->error($this->Article->getError(),U('Blog/mEdit','id='.$data['id']));
							}
						}else{
							$this->error('文章'.$data['title'].'已经存在',U('Blog/mEdit','id='.$data['id']));
						}
					}else{
						$this->error('文章标题不能为空！',U('Blog/mEdit','id='.$data['id']));
					}

				}else{
					$this->error('文章记录丢失！',U('Blog/index'));
				}
			}else{
				$this->error('请先登录！',U('Public/index'));
			}

		} else {
			
			//分配导航栏当前位置
			$this->assign('navigation_bar','博客管理>编辑文章');
			$this->assign('act',$Think.ACTION_NAME);
			
			if( isset($_GET['id']) ){
				$id = isset($_GET['id']) && intval($_GET['id']) ? intval($_GET['id']) : $this->error('参数错误',U('Blog/index'));
			}
			$art_info = $this->Article->where('id='.$id)->find();
			$this->assign('info', $art_info);

			$cat_mod = M("Category");
			$list = $cat_mod->select();
			//调用公用函数库自动生成权限列表树
			//import('Common.Functions.arrayHelper',WEB_ROOT,'.php'); //导入框架外部自定制函数库(数组处理)
			$arrayHelper = new \Org\Util\ArrayHelper();
			$tree_list = $arrayHelper::toTree($list, 'id', 'pid', 'children');
			$this->assign('catList',$tree_list);
			$this->assign('list_empty','<option value="0">栏目列表为空！请先创建栏目</option>');
			
			$this->display("Blog:add");
		}
	}
	
	/**
      +----------------------------------------------------------
     * ajax更改文章评论状态
      +----------------------------------------------------------
     */
	public function ajax_update_cstatus(){
		$dataResult = array();
		$dataResult['flag'] = 1; //默认为1表示无任何错误
		$dataResult['msg'] = '评论状态更新成功！'; //ajax提示信息
		$dataResult['data'] = ''; //返回数据、修改成功则返回、修改后的数据
		if (IS_AJAX) {
			$id = intval(trim($_REQUEST['id']));
			$data['id'] = $id;
			$comment_status = $this->Article->where($data)->getField('comment_status');
			$set = array();
			if($comment_status == 1){
				$set = array('comment_status'=>0);		
			}elseif($comment_status == 0){
				$set = array('comment_status'=>1);
			}else{
				$set = array('comment_status'=>0);
			}
			$u_result = $this->Article->where($data)->save($set);
			if($u_result){
				$dataResult['data'] = $set['comment_status'];
				$this->ajaxReturn($dataResult,'JSON');
			}else{
				$dataResult['flag'] = 0; //默认为1表示无任何错误
				$dataResult['msg'] = '评论状态更新失败！请稍后重新操作！'; //ajax提示信息
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
     * ajax更改文章锁定状态
      +----------------------------------------------------------
     */
	public function ajax_update_status(){
		$dataResult = array();
		$dataResult['flag'] = 1; //默认为1表示无任何错误
		$dataResult['msg'] = '锁定状态更新成功！'; //ajax提示信息
		$dataResult['data'] = ''; //返回数据、修改成功则返回、修改后的数据
		if (IS_AJAX) {
			$id = intval(trim($_REQUEST['id']));
			$data['id'] = $id;
			$is_lock = $this->Article->where($data)->getField('is_lock');
			$set = array();
			if($is_lock == 1){
				$set = array('is_lock'=>0);		
			}elseif($is_lock == 0){
				$set = array('is_lock'=>1);
			}else{
				$set = array('is_lock'=>0);
			}
			$u_result = $this->Article->where($data)->save($set);
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
     * 将文章移入回收站
      +----------------------------------------------------------
     */
	public function ajax_join_recycle(){
		$dataResult = array();
		$dataResult['flag'] = 1; //默认为1表示无任何错误
		$dataResult['msg'] = '移入回收站成功！'; //ajax提示信息
		$dataResult['data'] = ''; //返回数据、修改成功则返回、修改后的数据
		if (IS_AJAX) {
			$id = intval($_POST['id']) ? intval($_POST['id']) : 0 ;
			if($id){
				$data['id'] = $id;
				$is_del = $this->Article->where($data)->getField('is_del');
				if($is_del == 0){
					$set = array();
					$set = array('is_del'=>1);
					$u_result = $this->Article->where($data)->save($set);
					if($u_result){
						$dataResult['data'] = $set['is_del'];
						$this->ajaxReturn($dataResult,'JSON');
					}else{
						$dataResult['flag'] = 0; //默认为1表示无任何错误
						$dataResult['msg'] = '移入回收站失败！请稍后重新操作！'; //ajax提示信息
						$dataResult['data'] = 'err_no'; //返回数据、修改成功则返回、修改后的数据
						$this->ajaxReturn($dataResult,'JSON');
					}		
				}else{
					$dataResult['flag'] = 0; //默认为1表示无任何错误
					$dataResult['msg'] = '重复操作！该文章已经被移入回收站！'; //ajax提示信息
					$dataResult['data'] = 'err_no'; //返回数据、修改成功则返回、修改后的数据
					$this->ajaxReturn($dataResult,'JSON');
				}
				
			}else{
				$dataResult['flag'] = 0; //默认为1表示无任何错误
				$dataResult['msg'] = '移入回收站失败！该文章已不存在！'; //ajax提示信息
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
     * 将文章从回收站还原
      +----------------------------------------------------------
     */
	public function ajax_restore_article(){
		$dataResult = array();
		$dataResult['flag'] = 1; //默认为1表示无任何错误
		$dataResult['msg'] = '还原该文章成功！'; //ajax提示信息
		$dataResult['data'] = ''; //返回数据、修改成功则返回、修改后的数据
		//fwrites(APP_PATH . 'Admin/ajax.txt','开始删除！');
		if (IS_AJAX) {
			$id = intval($_POST['id']) ? intval($_POST['id']) : 0 ;
			if($id){
				$data['id'] = $id;
				$is_del = $this->Article->where($data)->getField('is_del');
				if($is_del == 1){
					$set = array();
					$set = array('is_del'=>0);
					$u_result = $this->Article->where($data)->save($set);
					if($u_result){
						$dataResult['data'] = $set['is_del'];
						$this->ajaxReturn($dataResult,'JSON');
					}else{
						$dataResult['flag'] = 0; //默认为1表示无任何错误
						$dataResult['msg'] = '还原文章失败！请稍后重新操作！'; //ajax提示信息
						$dataResult['data'] = 'err_no'; //返回数据、修改成功则返回、修改后的数据
						$this->ajaxReturn($dataResult,'JSON');
					}		
				}else{
					$dataResult['flag'] = 0; //默认为1表示无任何错误
					$dataResult['msg'] = '重复操作！该文章已经被移出回收站！'; //ajax提示信息
					$dataResult['data'] = 'err_no'; //返回数据、修改成功则返回、修改后的数据
					$this->ajaxReturn($dataResult,'JSON');
				}
				
			}else{
				$dataResult['flag'] = 0; //默认为1表示无任何错误
				$dataResult['msg'] = '移出回收站失败！该文章已不存在！'; //ajax提示信息
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
     * ajax删除文章以及该文章下的所有的评论 2014-7-2
      +----------------------------------------------------------
     */
	public function ajax_del_article(){
		$dataResult = array();
		$dataResult['flag'] = 1; //默认为1表示无任何错误
		$dataResult['msg'] = '删除成功！'; //ajax提示信息
		$dataResult['data'] = ''; //返回数据、修改成功则返回、修改后的数据
		if (IS_AJAX) {
			//$art_info = $this->Article->where('id='.$id)->find();
			$id = intval($_POST['id']) ? intval($_POST['id']) : 0 ;
			//fwrites(APP_PATH . 'Admin/ajax.txt',$id);
			$a_result = $this->Article->delete($id);
			if($a_result){
				//删除用户评论
				$this->Comments->where(array("aid"=>$id))->delete();
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
	
	/**
      +----------------------------------------------------------
     * 博文评论
      +----------------------------------------------------------
     */
	 public function mComments(){
		 //分配导航栏当前位置
		$this->assign('navigation_bar','博客管理>评论列表');
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
			$where .= " and atitle like '%$new_keyword%'";
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
		$this->display("Blog:comments");
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
			$data['id'] = $id;
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
     * 回收站管理
      +----------------------------------------------------------
     */
	 public function mTrash(){
		//分配导航栏当前位置
		$this->assign('navigation_bar','博客管理>回收站管理');
		//回收站关键字(默认为非回收站)
		$mTrash_act = 1;
		//关键字搜索默认值
		$keyword = '';
		//评论状态筛选默认值
		$type = 0;
		$where = 'is_del = 1';
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
					$where .= " and is_lock = 1";
					break;
				case 2:
					$where .= " and is_lock = 0";
					break;
				default:
					break;
			}
		}
	
		$count = $this->Article->where($where)->count();
		$pageNum = 20;
		$page = new \Think\Page($count,$pageNum);
		$show = $page->show();		
		$list = $this->Article->join('as ar left join td_category as ca on ca.id = ar.cid')->field('ar.id,ar.post_author_id,ar.post_author_name,ar.title,ar.cid,ar.clicks,ar.release_time,ar.last_mod_time,ar.post_author_ip,ar.comment_status,ar.is_lock,ar.is_del,ca.name as cname')->where($where)->order("ar.release_time desc")->limit($page->firstRow.','.$page->listRows)->select();
		if(is_array($list) && count($list)){
			foreach ($list as $key=>$val){
				$list[$key]['key']=++$page->firstRow;
			}
		}

		$this->assign('mTrash_act',$mTrash_act);
		$this->assign('keyword',$keyword);
		$this->assign('type',$type);
		$this->assign('list_empty','<tr align="center"><td colspan="10" align="center"><span>回收站列表为空！</span></td></tr>');
		$this->assign('list',$list);
		$this->assign('page',$show);
		$this->display("Blog:index");
	 }
	 
	 
}
?>