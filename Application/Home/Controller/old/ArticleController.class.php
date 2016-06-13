<?php
/**
 * 文章页
 * ============================================================================
 * 版权所有 2005-2080 Lessismore，并保留所有权利。
 * 网站地址: http://www.trydemo.net；
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和
 * 使用；不允许对程序代码以任何形式任何目的的再发布。
 * ============================================================================
 * $Author: lsq & Lessismore & D.Apache.Luo $
 * $Intro: ArticleController.class.php 2014-4-2 Lessismore $
*/
namespace Home\Controller;
use Think\Controller;
use Think\Model;
class ArticleController extends CommonController{
	
	// 实际表名
	protected $t_n_comments='td_comments';
	protected  $Article, $Comments, $User, $Test;

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
		$this->User = D("User");
		$this->Test = 999;
	}

	/**
      +----------------------------------------------------------
     * 博文内容页
      +----------------------------------------------------------
     */
	public function index(){

		$id = $_GET['id']+0;
		$oneArticle = $this->Article->getOneArticle($id);
		if(empty($oneArticle)){
			$this->error('找不到该条博文信息!');
		}
		
		$this->page_header();
		$this->page_banner();
		$this->page_navigation_bar();
		$this->page_right();
		$this->page_footer();
		Vendor('extend');
		
		//此处用户区别用户登陆后退出方式
		$this->assign("is_ajax_logout",1);
		//p($oneArticle['relase_time']);
		//p($oneArticle);
		
		$oneArticle['tags']=empty($oneArticle['keywords']) ? $oneArticle['keywords'] : explode(',',$oneArticle['keywords']);
		$oneArticle['relase_time']=date('Y年m月d日',strtotime($oneArticle['release_time']));
		$oneArticle['relase_time_two']=date('Y-m-d',strtotime($oneArticle['release_time']));
		
		// 获取博文评论(分页)
		$where = 'is_lock = 0 and comment_top_parent = 0';
		if(isset($id)){
			$where .= " and aid = $id";
		}
	
		$count = $this->Comments->where($where)->count();
		$pageNum = 10;
		$cPageNum = 5; //每个顶级评论默认显示子评论总数
		$page = new \Think\Page($count,$pageNum);
		$show = $page->show();
		$this->assign('page',$show);
		$this->assign("count",$count);
		
		$data = $this->Comments->field("cid,aid,atitle,comment_author_name,comment_author_avatar,comment_author_email,comment_author_url,comment_author_ip,comment_add_date,comment_edit_date,comment_content,comment_karma,comment_approved,comment_agent,comment_type,comment_top_parent,comment_parent")->where($where)->order("comment_add_date asc")->limit($page->firstRow.','.$page->listRows)->select();
		
		if($data && count($data)){
			foreach($data as $key=>$value){
				if($value['cid']){
					$c_count = $this->Comments->where("is_lock = 0 and comment_top_parent = ".$value['cid'])->count();
					if($c_count){
						$tmp_data = $this->Comments->field("cid,aid,uid,atitle,comment_author_name,comment_author_avatar,comment_author_email,comment_author_url,comment_author_ip,comment_add_date,comment_edit_date,comment_content,comment_karma,comment_approved,comment_agent,comment_type,comment_top_parent,comment_parent")->where("is_lock = 0 and comment_top_parent = ".$value['cid'])->order("comment_add_date asc")->limit($cPageNum)->select();
						$data[$key]['subset_count'] = $c_count;
						if($tmp_data && count($tmp_data)){
							foreach($tmp_data as $ke=>$val){
								if($val['comment_top_parent'] == $value['cid']){
									if($val['comment_author_name'] == $value['comment_author_name']){
										$tmp_data[$ke]['sub_marking'] = "<a><b>".$val['comment_author_name']."&nbsp;&nbsp;</b></a><font color='orange'>(补充)</font><a><b>:</b></a>";
									}else{
										$tmp_data[$ke]['sub_marking'] = "<a><b>".$val['comment_author_name']."</b></a><span>&nbsp;&nbsp;回复&nbsp;&nbsp;</span><a><b>".$value['comment_author_name']."&nbsp;:</b></a>";
									}
								}
							}
							$data[$key]['subset_reply'] = $tmp_data;
						}else{
							$data[$key]['subset_count'] = 0;
							$data[$key]['subset_reply'] = array();
						}
					}else{
						$data[$key]['subset_count'] = 0;
						$data[$key]['subset_reply'] = array();
					}
				}
			}
		}

		$oneArticle['comment'] = $data;
		//p($oneArticle);
		$this->assign('article_info',$oneArticle);
		
		// 分配最新的表情信息供前端js使用
		$smilies_arr = get_smilies_arr();
		if($smilies_arr && count($smilies_arr)){
			$this->assign('smilies_arr',json_encode($smilies_arr));
		}else{
			$this->assign('smilies_arr',$smilies_arr);
		}
		
		// 更新浏览数量
		// $this->Article->execute("update td_article set click=click+1 where id=".$oneArticle['id']);
		$this->Article->where("id='".$oneArticle['id']."'")->setInc('clicks');
		$cat_mod = D("Category");
		$oneCat = $cat_mod->getOneCategory($oneArticle['cid']);
		$this->assign('category_info',$oneCat);
		// 上一篇下一篇文章
		$pre = $this->Article->getPreOrNext($oneArticle['id'],$oneCat['id'],-1);
		$pre = empty($pre) ? '前方施工！请掉头..' : "<a href='__APP__/Article/index/id/".$pre['id']."'>".$pre['title']."</a>" ;
		$this->assign('pre',$pre);
		$next = $this->Article->getPreOrNext($oneArticle['id'],$oneCat['id'],1);
		$next = empty($next) ? '已经是最后一篇文章啦..' : "<a href='__APP__/Article/index/id/".$next['id']."'>".$next['title']."</a>" ;
		$this->assign('next',$next);
		
		// 分配用户信息
		$this->assign("user_info",$this->getUserInfo());
		
		$file_tpl=str_replace('.html','',$oneCat['articletpl']);
		$this->display("Article:$file_tpl");
	}
	
	/**
      +----------------------------------------------------------
     * ajax发布文章评论
      +----------------------------------------------------------
     */
	public function ajaxComment(){
		$dataResult = array();
		$dataResult['flag'] = 1; //默认为1表示无任何错误
		$dataResult['msg'] = '发布成功！'; //ajax提示信息
		$dataResult['data'] = ''; //返回数据、修改成功则返回、修改后的数据
		
		//判断是否是ajax请求
		if(IS_AJAX){
			//判断前台用户是否已经登录
			$uid = 0;
			
			$username = isset($_POST['uname']) ? htmlspecialchars(trim($_POST['uname'])) : '' ;
			$email = isset($_POST['email']) ? htmlspecialchars(trim($_POST['email'])) : '' ;
			$site = isset($_POST['site']) ? htmlspecialchars(trim($_POST['site'])) : '' ;
			$content = isset($_POST['comment_content']) ? htmlspecialchars($_POST['comment_content']) : '' ;
			$edit_type = isset($_POST['c_edit_id']) ? intval(htmlspecialchars($_POST['c_edit_id'])) : 0 ;

			//fwrites(APP_PATH . 'Home/ajax.txt','检测是否是编辑模式------------------>$edit_type');
			//fwrites(APP_PATH . 'Home/ajax.txt',$email);
			//fwrites(APP_PATH . 'Home/ajax.txt',$edit_type);
			//开始reply后台数据验证
			$user_info = $this->getUserInfo();
			if($user_info && !empty($user_info)){
				if(!$content){
					$dataResult['flag'] = 0; //默认为1表示无任何错误
					$dataResult['msg'] = '评论内容不能为空!'; //ajax提示信息
					$dataResult['data'] = 'err_content';
					$this->ajaxReturn($dataResult,'JSON');
				}
				
				$username = $user_info['username'];
				$email = $user_info['email'];
				$site = $user_info['personal_website'];
			}else{
				Vendor('extend');
				if(!$username){
					$dataResult['flag'] = 0; //默认为1表示无任何错误
					$dataResult['msg'] = '用户姓名信息不能为空!'; //ajax提示信息
					$dataResult['data'] = 'err_uname';
					$this->ajaxReturn($dataResult,'JSON');
				}elseif(!is_email($email)){					
					$dataResult['flag'] = 0; //默认为1表示无任何错误
					$dataResult['msg'] = '邮箱格式不正确!'; //ajax提示信息
					$dataResult['data'] = 'err_email';
					$this->ajaxReturn($dataResult,'JSON');
				}elseif($site && !check_url($site)){					
					$dataResult['flag'] = 0; //默认为1表示无任何错误
					$dataResult['msg'] = '互粉站点格式不正确!'; //ajax提示信息
					$dataResult['data'] = 'err_site';
					$this->ajaxReturn($dataResult,'JSON');
				}elseif(!$content){
					$dataResult['flag'] = 0; //默认为1表示无任何错误
					$dataResult['msg'] = '评论内容不能为空!'; //ajax提示信息
					$dataResult['data'] = 'err_content';
					$this->ajaxReturn($dataResult,'JSON');
				}
			}
			$id = $_POST['aid']+0;
			$oneArticle = $this->Article->getOneArticle($id);
			if(!$id || empty($oneArticle)){	
				$dataResult['flag'] = 0; //默认为1表示无任何错误
				$dataResult['msg'] = '找不到该条博文信息!'; //ajax提示信息
				$dataResult['data'] = 'redirect_index';
			}

			$data = array();
			if($edit_type){
				//编辑模式
				$data=array(
					'comment_edit_date'=>date('Y-m-d H:i:s'),
					'comment_content'=>$content,
					'comment_agent'=>isset( $_SERVER['HTTP_USER_AGENT'] ) ? substr( $_SERVER['HTTP_USER_AGENT'], 0, 254 ) : '',
					'comment_author_ip'=>get_client_ip(),
				);

			}else{
				//新增模式
				$data=array(
					'aid'=>$id,
					'uid'=>intval($user_info['uid']),
					'atitle'=>$oneArticle['title'],
					'comment_author_name'=>$username,
					'comment_author_email'=>$email,
					'comment_author_avatar'=>$user_info['avatar'],
					'comment_author_url'=>$site,
					'comment_add_date'=>date('Y-m-d H:i:s'),
					'comment_edit_date'=>date('Y-m-d H:i:s'),
					'comment_content'=>$content,
					'comment_top_parent'=>intval(htmlspecialchars($_POST['c_best_parent'])),
					'comment_parent'=>intval(htmlspecialchars($_POST['comment_parent'])),
					'comment_agent'=>isset( $_SERVER['HTTP_USER_AGENT'] ) ? substr( $_SERVER['HTTP_USER_AGENT'], 0, 254 ) : '',
					'comment_author_ip'=>get_client_ip(),
				);
				if(!$data['comment_top_parent'] && $data['comment_parent']){
					$data['comment_top_parent'] = $data['comment_parent'];
				}
			}
			//开始数据库验证
			$mod = M();
			if(!$edit_type){
				//增加：非编辑模式下检查是否是重复评论
				if($uid){
					$sql_for_repeat = "SELECT cid FROM $this->t_n_comments WHERE aid = $id AND uid = $uid AND comment_content = '" . $data['comment_content'] . "' LIMIT 1";
				}else{
					$sql_for_repeat = "SELECT cid FROM $this->t_n_comments WHERE aid = $id AND comment_author_name = '". $username ."' AND comment_author_email = '$email' AND comment_content = '" . $data['comment_content'] . "' LIMIT 1";
				}
				
				//fwrites(APP_PATH.'Home/ajax.txt','@param(ajaxComment)---sql_for_repeat:'.$sql_for_repeat);

				$cid = $mod->query($sql_for_repeat);	
				if($cid){
					$dataResult['flag'] = 0; //默认为1表示无任何错误
					$dataResult['msg'] = '看起来这条评论你以前说过!'; //ajax提示信息
					$dataResult['data'] = 'err_repeat';
					$this->ajaxReturn($dataResult,'JSON');
				}
			}
			//增加：检查是否评论过快
			$interval_time = intval(CONFIG_A_REPLY_INTERVAL);	
			if($interval_time){
				if($uid){
					$sql_for_quick = "SELECT comment_add_date FROM $this->t_n_comments WHERE aid = $id AND uid = $uid LIMIT 1";
				}else{
					$sql_for_quick = "SELECT comment_add_date FROM $this->t_n_comments WHERE aid = $id AND comment_author_name = $username AND comment_author_email = '$email' ORDER BY comment_add_date DESC  LIMIT 1";
				}
				$add_time = $mod->query($sql_for_quick);
				if($add_time){
					if(is_array($add_time) && count($add_time)){
						$int_time = strtotime($add_time['0']['comment_add_date']);
					}else{
						$int_time = strtotime($add_time);
					}
					//读取配置最快回复间隔时间
					//$Config['a_reply_interval'];
					if((time()-$int_time) < $interval_time){
						$dataResult['flag'] = 0; //默认为1表示无任何错误
						$dataResult['msg'] = '评论过快!服务器快要崩溃呐！'; //ajax提示信息
						$dataResult['data'] = 'err_quick';
						$this->ajaxReturn($dataResult,'JSON');
					}
				}
			}

			if($edit_type){
				//编辑模式
				$new_cid = $this->Comments->where("cid=".$edit_type)->save($data);
				//fwrites(APP_PATH . 'home/ajax.txt','------------------>这事编辑模式');
				//fwrites(APP_PATH . 'home/ajax.txt',$new_cid);
			}else{
				//新增模式
				$new_cid = $this->Comments->data($data)->add();
			}

			//fwrites(APP_PATH . 'Home/ajax.txt','看看是否添加新评论成功------------->new_cid');
			//fwrites(APP_PATH . 'Home/ajax.txt',$new_cid);
			if($new_cid){
				$return_data = array();
				if($edit_type){
					$c_data = $this->Comments->getInfoById($edit_type);
					$return_data = 	count($c_data) > 0 ? $c_data[0] : '' ;
					$return_data['update_cid'] = $edit_type;
				}else{
					$c_data = $this->Comments->getInfoById($new_cid);
					$return_data = 	count($c_data) > 0 ? $c_data[0] : '' ;
				}
				if($user_info && !empty($user_info)){
					Vendor('extend');	
				}
				
				$return_data['comment_content'] = convert_smilie($return_data['comment_content']);
				if($return_data['comment_top_parent']){
					$p_c_author_name = isset($_POST['comment_parent_author_name']) ? htmlspecialchars($_POST['comment_parent_author_name']) : '' ;
					if($return_data['comment_author_name'] == $p_c_author_name){
						$return_data['sub_marking'] = "<a><b>".$return_data['comment_author_name']."&nbsp;&nbsp;</b></a><font color='orange'>(补充)</font><a><b>:</b></a>";
					}else{
						$return_data['sub_marking'] = "<a><b>".$return_data['comment_author_name']."</b></a><span>&nbsp;&nbsp;回复&nbsp;&nbsp;</span><a><b>".$p_c_author_name."&nbsp;:</b></a>";
					}
				}else{
					$return_data['sub_marking'] = '这个人很懒,他什么都没有留下...(顶级评论无作用)';	
				}

				//fwrites(APP_PATH . 'Home/ajax.txt','评论成功、开始返回最新数据------------->$return_data');
				//fwrites(APP_PATH . 'Home/ajax.txt',$return_data);
				$dataResult['data'] = $return_data;
				$this->ajaxReturn($dataResult,'JSON');

			}else{
				//fwrites(APP_PATH . 'Home/ajax.txt','评论失败------------->c_data');
				$dataResult['flag'] = 0; //默认为1表示无任何错误
				$dataResult['msg'] = '提交失败！请稍后重新提交！'; //ajax提示信息
				$dataResult['data'] = 'err_server';
				$this->ajaxReturn($dataResult,'JSON');
			}
			
			$dataResult['flag'] = 0; //默认为1表示无任何错误
			$dataResult['msg'] = '提交失败！请稍后重新提交！'; //ajax提示信息
			$dataResult['data'] = 'err_server';
			$this->ajaxReturn($dataResult,'JSON');
		}else{
			$dataResult['flag'] = 0; //默认为1表示无任何错误
			$dataResult['msg'] = '提交失败！请稍后重新提交！'; //ajax提示信息
			$dataResult['data'] = 'err_server';
			$this->ajaxReturn($dataResult,'JSON');
		}
	}
	
	/**
      +----------------------------------------------------------
     * ajax查询更多子评论
      +----------------------------------------------------------
     */
	public function ajaxShowMoreSubComments(){
		$dataResult = array();
		$dataResult['flag'] = 1; //默认为1表示无任何错误
		$dataResult['msg'] = '查询成功！'; //ajax提示信息
		$dataResult['data'] = ''; //返回数据、修改成功则返回、修改后的数据
		//fwrites(APP_PATH . 'Home/ajax.txt','@param(ajaxShowMoreSubComments)---start!');
		//fwrites(APP_PATH . 'Home/ajax.txt',$_POST);
		$cid = isset($_POST['cid']) ? intval(htmlspecialchars($_POST['cid'])) : 0 ;
		$page = isset($_POST['p']) ? intval(htmlspecialchars($_POST['p'])) : 1 ;
		
		//判断是否是ajax请求
		if(IS_AJAX){
			if($cid){
				$p_arr = $this->Comments->where("cid = ".$cid)->find();
				$c_count = $this->Comments->where("is_lock = 0 and comment_top_parent = ".$cid)->count();
				$cPageNum = 5; //每个顶级评论默认显示子评论总数
				$firstRow = $cPageNum * ($page - 1);
				
				if($c_count){
					$tmp_data = $this->Comments->field("cid,aid,uid,atitle,comment_author_name,comment_author_avatar,comment_author_email,comment_author_url,comment_author_ip,comment_add_date,comment_edit_date,comment_content,comment_karma,comment_approved,comment_agent,comment_type,comment_top_parent,comment_parent")->where("is_lock = 0 and comment_top_parent = ".$cid)->order("comment_add_date asc")->limit($firstRow.','.$cPageNum)->select();
					//$data[$key]['subset_count'] = $c_count;
					if($tmp_data && count($tmp_data)){
						$arr = array();
						$arr['count'] = $c_count;
						$arr['nowPage'] = $page;
						$arr['nextPage'] = $page+1;
						$arr['remnant'] = $c_count - $page*$cPageNum > 0 ? $c_count - $page*$cPageNum : 0 ;
						Vendor('extend');
						foreach($tmp_data as $ke=>$val){
							$tmp_data[$ke]['comment_content'] = convert_smilie($tmp_data[$ke]['comment_content']);
							if($val['comment_top_parent'] == $cid){
								if($val['comment_author_name'] == $p_arr['comment_author_name']){
									$tmp_data[$ke]['sub_marking'] = "<a><b>".$val['comment_author_name']."&nbsp;&nbsp;</b></a><font color='orange'>(补充)</font><a><b>:</b></a>";
								}else{
									$tmp_data[$ke]['sub_marking'] = "<a><b>".$val['comment_author_name']."</b></a><span>&nbsp;&nbsp;回复&nbsp;&nbsp;</span><a><b>".$p_arr['comment_author_name']."&nbsp;:</b></a>";
								}
							}
						}
						$arr['data'] = $tmp_data;
						$dataResult['data'] = $arr;
						//fwrites(APP_PATH."Home/ajax.txt","@param(ajaxShowMoreSubComments)---data:");
						//fwrites(APP_PATH."Home/ajax.txt",$dataResult['data']);
						
					}else{
						$dataResult['flag'] = 0; //默认为1表示无任何错误
						$dataResult['msg'] = '已经到最后一条子评论！'; //ajax提示信息
						$dataResult['data'] = 'err_empty';	
					}

				}else{
					$dataResult['flag'] = 0; //默认为1表示无任何错误
					$dataResult['msg'] = '已经到最后一条子评论！'; //ajax提示信息
					$dataResult['data'] = 'err_empty';	
				}
				
			}else{
				$dataResult['flag'] = 0; //默认为1表示无任何错误
				$dataResult['msg'] = '参数错误！请刷新重试！'; //ajax提示信息
				$dataResult['data'] = 'err_server';
				//$this->ajaxReturn($dataResult,'JSON');
			}

			$this->ajaxReturn($dataResult,'JSON');
		}else{
			$dataResult['flag'] = 0; //默认为1表示无任何错误
			$dataResult['msg'] = '提交失败！请稍后重新提交！'; //ajax提示信息
			$dataResult['data'] = 'err_server';
			$this->ajaxReturn($dataResult,'JSON');
		}
	}

}
?>