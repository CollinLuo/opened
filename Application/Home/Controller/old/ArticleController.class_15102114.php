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
	 * $Author: lsq & Lessismore & D.Apache.Luo
	 * $Id: UserActivity.class.php 2014-4-2 Lessismore $
	*/
	namespace Home\Controller;
	use Think\Controller;
	use Think\Model;
	class ArticleController extends CommonController{
		
		//实际表名
		protected $t_n_comments='td_comments';
		
		//博文内容页
		public function index(){

			$id = $_GET['id']+0;
			$article_mod = D("Article");
			$oneArticle = $article_mod->getOneArticle($id);
			if(empty($oneArticle)){
				$this->error('找不到该条博文信息!');
			}
			
			$this->page_header();
			$this->page_banner();
			$this->page_navigation_bar();
			$this->page_right();
			$this->page_footer();
			Vendor('extend');
			$oneArticle['tags']=empty($oneArticle['keywords']) ? $oneArticle['keywords']:explode(',',$oneArticle['keywords']);
			$oneArticle['relase_time']=date('Y年m月d日',strtotime($oneArticle['relase_time']));
			$oneArticle['relase_time_two']=date('Y-m-d',strtotime($oneArticle['relase_time']));
			
			//获取博文评论
			$oneArticle['comment'] = $article_mod->getComments($id);
			
			if(is_array($oneArticle['comment']) && !empty($oneArticle['comment'])){
				$this->assign('count',count($oneArticle['comment']));
			}
			
			//表情处理以及回复阶层处理
			$c_arr = array();
			if(is_array($oneArticle['comment']) && count($oneArticle['comment'])){
				
				$new_c_arr = array();
				foreach($oneArticle['comment'] AS $k=>$v){
					// 暂时屏蔽后台表情转换 lsq 151020
					//$v['comment_content'] = convert_smilie($v['comment_content']); //表情转换
					$new_c_arr[$v['cid']] = $v;
					if(!$v['comment_top_parent']){
						$c_arr[$v['cid']] = $v;
					}
				}	
				$oneArticle['comment'] = $new_c_arr;
				//p(count($c_arr));
				if(count($c_arr)){
					foreach($c_arr AS $k=>$v){
						foreach($oneArticle['comment'] AS $ke=>$val){
							if($val['comment_top_parent'] == $v['cid']){
								if($val['comment_author_name'] == $oneArticle['comment'][$val['comment_parent']]['comment_author_name']){
									$val['sub_marking'] = "<a><b>".$val['comment_author_name']."&nbsp;&nbsp;</b></a><font color='orange'>(补充)</font><a><b>:</b></a>";
								}else{
									$val['sub_marking'] = "<a><b>".$val['comment_author_name']."</b></a><span>&nbsp;&nbsp;回复&nbsp;&nbsp;</span><a><b>".$oneArticle['comment'][$val['comment_parent']]['comment_author_name']."&nbsp;:</b></a>";
								}
								$c_arr[$k]['subset_reply'][] = $val;
								
							}
						}
					}
				}
			}
			$oneArticle['comment'] = $c_arr;
			//p($oneArticle['comment']);
			$smilies_arr = get_smilies_arr();
			if($smilies_arr && count($smilies_arr)){
				//$smilies_str = implode(',',$smilies_arr);
				//$this->assign('smilies_arr',$smilies_str);
				$this->assign('smilies_arr',json_encode($smilies_arr));
			}else{
				$this->assign('smilies_arr',$smilies_arr);
			}
			//p($smilies_arr);
			
			$this->assign('article_info',$oneArticle);
			//更新浏览数量
			//$article_mod->execute("update td_article set click=click+1 where id=".$oneArticle['id']);
			$article_mod->where("id='".$oneArticle['id']."'")->setInc('clicks');
			
			$cat_mod=D("Category");;
			$oneCat=$cat_mod->getOneCategory($oneArticle['cid']);
			$this->assign('category_info',$oneCat);
			//上一篇下一篇文章
			$pre=$article_mod->getPreOrNext($oneArticle['id'],-1);
			if(empty($pre)){
				$pre='没有了';
			}else{
				$pre="<a href='__APP__/Article/index/id/".$pre['id']."'>".$pre['title']."</a>";
			}
			$this->assign('pre',$pre);
			
			$next=$article_mod->getPreOrNext($oneArticle['id'],1);
			if(empty($next)){
				$next='没有了';
			}else{
				$next="<a href='__APP__/Article/index/id/".$next['id']."'>".$next['title']."</a>";
			}

			$this->assign('next',$next);
			$file_tpl=str_replace('.html','',$oneCat['articletpl']);
			$this->display("Article:$file_tpl");
		}
		
		//ajax发布博文评论
		public function ajaxComment(){
			
			$dataResult = array();
			$dataResult['flag'] = 1; //默认为1表示无任何错误
			$dataResult['msg'] = '发布成功！'; //ajax提示信息
			$dataResult['data'] = ''; //返回数据、修改成功则返回、修改后的数据
			
			//判断是否是ajax请求
			if(IS_AJAX){
				//fwrites(WEB_ROOT . 'ajax.txt',"这是ajax请求！");
				$ajax_err = 1; //默认为1表示无任何错误
				$ajax_msg = '发布成功！'; //ajax提示信息
				Vendor('extend');
				fwrites(APP_PATH . 'Home/ajax.txt',$_POST);
				//fwrites(WEB_ROOT . 'ajax.txt',$_SESSION);
				//判断前台用户是否已经登录
				$uid = 0;
				
				$username = isset($_POST['uname']) ? htmlspecialchars(trim($_POST['uname'])) : '' ;
				$email = isset($_POST['email']) ? htmlspecialchars(trim($_POST['email'])) : '' ;
				$site = isset($_POST['site']) ? htmlspecialchars(trim($_POST['site'])) : '' ;
				$content = isset($_POST['comment_content']) ? htmlspecialchars($_POST['comment_content']) : '' ;
				$edit_type = isset($_POST['c_edit_id']) ? intval(htmlspecialchars($_POST['c_edit_id'])) : 0 ;

				fwrites(APP_PATH . 'Home/ajax.txt','检测是否是编辑模式------------------>$edit_type');
				fwrites(APP_PATH . 'Home/ajax.txt',$email);
				fwrites(APP_PATH . 'Home/ajax.txt',$edit_type);
				//开始reply后台数据验证
				//检测用户名
				//p('------------->检测内存过大！！');
				//exit;
				if(!$username){
					$ajax_err = 0;
					$ajax_msg = '';
					//$this->ajaxReturn('err_uname',$ajax_msg,$ajax_err);
					
					$dataResult['flag'] = 0; //默认为1表示无任何错误
					$dataResult['msg'] = '用户姓名信息不能为空!'; //ajax提示信息
					$dataResult['data'] = 'err_uname';
					$this->ajaxReturn($dataResult,'JSON');
					
				}elseif(!is_email($email)){
					$ajax_err = 0;
					$ajax_msg = '邮箱格式不正确!';
					//$this->ajaxReturn('err_email',$ajax_msg,$ajax_err);
					
					$dataResult['flag'] = 0; //默认为1表示无任何错误
					$dataResult['msg'] = '邮箱格式不正确!'; //ajax提示信息
					$dataResult['data'] = 'err_email';
					$this->ajaxReturn($dataResult,'JSON');
				}elseif($site && !check_url($site)){
					$ajax_err = 0;
					$ajax_msg = '互粉站点格式不正确!';
					//$this->ajaxReturn('err_site',$ajax_msg,$ajax_err);
					
					$dataResult['flag'] = 0; //默认为1表示无任何错误
					$dataResult['msg'] = '互粉站点格式不正确!'; //ajax提示信息
					$dataResult['data'] = 'err_site';
					$this->ajaxReturn($dataResult,'JSON');
				}elseif(!$content){
					$ajax_err = 0;
					$ajax_msg = '评论内容不能为空!';
					//$this->ajaxReturn('err_content',$ajax_msg,$ajax_err);
					
					$dataResult['flag'] = 0; //默认为1表示无任何错误
					$dataResult['msg'] = '评论内容不能为空!'; //ajax提示信息
					$dataResult['data'] = 'err_content';
					$this->ajaxReturn($dataResult,'JSON');
				}

				$id = $_POST['aid']+0;
				$article_mod = D("Article");
				$oneArticle = $article_mod->getOneArticle($id);
				if(!$id || empty($oneArticle)){
					$ajax_err = 0;
					$ajax_msg = '找不到该条博文信息!';
					//$this->ajaxReturn('redirect_index',$ajax_msg,$ajax_err);
					
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
						'atitle'=>$oneArticle['title'],
						'comment_author_name'=>$username,
						'comment_author_email'=>$email,
						'comment_author_avatar'=>'user100_1831.jpg',
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
						$data['comment_top_parent'] = 	$data['comment_parent'];
					}
				}

				//开始数据库验证
				$mod = M();
				if(!$edit_type){
					//增加：非编辑模式下检查是否是重复评论
					if($uid){
						$sql_for_repeat = "SELECT cid FROM $this->t_n_comments WHERE aid = $id AND comment_author_id = $uid AND comment_content = '" . $data['comment_content'] . "' LIMIT 1";
					}else{
						$sql_for_repeat = "SELECT cid FROM $this->t_n_comments WHERE aid = $id AND comment_author_name = '". $username ."' AND comment_author_email = '$email' AND comment_content = '" . $data['comment_content'] . "' LIMIT 1";
					}
					
					fwrites(APP_PATH.'Home/ajax.txt','@param(ajaxComment)---sql_for_repeat:'.$sql_for_repeat);

					$cid = $mod->query($sql_for_repeat);	
					if($cid){
						$ajax_err = 0;
						$ajax_msg = '看起来这条评论你以前说过!';
						//$this->ajaxReturn('err_repeat',$ajax_msg,$ajax_err);
						
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
						$sql_for_quick = "SELECT comment_add_date FROM $this->t_n_comments WHERE aid = $id AND comment_author_id = $uid LIMIT 1";
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
							$ajax_err = 0;
							$ajax_msg = '评论过快!服务器快要崩溃呐！';
							//$this->ajaxReturn('err_quick',$ajax_msg,$ajax_err);
							
							$dataResult['flag'] = 0; //默认为1表示无任何错误
							$dataResult['msg'] = '评论过快!服务器快要崩溃呐！'; //ajax提示信息
							$dataResult['data'] = 'err_quick';
							$this->ajaxReturn($dataResult,'JSON');
						}
					}
					//fwrites(WEB_ROOT . 'ajax.txt',CONFIG_A_REPLY_INTERVAL);
				}
				
				//fwrites(WEB_ROOT . 'ajax.txt','------------------>数据库操作');
				$c_mod = D("Comments");
				if($edit_type){
					//编辑模式
					$new_cid = $c_mod->where("cid=".$edit_type)->save($data);
					//fwrites(APP_PATH . 'home/ajax.txt','------------------>这事编辑模式');
					//fwrites(APP_PATH . 'home/ajax.txt',$new_cid);
				}else{
					//新增模式
					$new_cid = $c_mod->data($data)->add();
					//fwrites(APP_PATH . 'home/ajax.txt','------------------>这事新增模式');
					//fwrites(APP_PATH . 'home/ajax.txt',$new_cid);
				}

				
				fwrites(APP_PATH . 'Home/ajax.txt','看看是否添加新评论成功------------->new_cid');
				fwrites(APP_PATH . 'Home/ajax.txt',$new_cid);
				if($new_cid){
					$return_data = array();
					if($edit_type){
						$c_data = $c_mod->getInfoById($edit_type);
						$return_data = 	count($c_data) > 0 ? $c_data[0] : '' ;
						$return_data['update_cid'] = $edit_type;
					}else{
						$c_data = $c_mod->getInfoById($new_cid);
						$return_data = 	count($c_data) > 0 ? $c_data[0] : '' ;
						
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
					
					fwrites(APP_PATH . 'Home/ajax.txt','评论成功、开始返回最新数据------------->$return_data');
					fwrites(APP_PATH . 'Home/ajax.txt',$return_data);
					fwrites(APP_PATH . 'Home/ajax.txt',$ajax_msg);
					fwrites(APP_PATH . 'Home/ajax.txt',$ajax_err);
					//$this->ajaxReturn($return_data,$ajax_msg,$ajax_err);
					
				
					$dataResult['data'] = $return_data;
					$this->ajaxReturn($dataResult,'JSON');

				}else{
					//fwrites(APP_PATH . 'Home/ajax.txt','评论失败------------->c_data');
					$ajax_err = 0;
					$ajax_msg = '提交失败！请稍后重新提交！';
					//$this->ajaxReturn('err_server',$ajax_msg,$ajax_err);
					
					$dataResult['flag'] = 0; //默认为1表示无任何错误
					$dataResult['msg'] = '提交失败！请稍后重新提交！'; //ajax提示信息
					$dataResult['data'] = 'err_server';
					$this->ajaxReturn($dataResult,'JSON');
				}

				
				$ajax_err = 0;
				$ajax_msg = '提交失败！请稍后重新提交！';
				//$this->ajaxReturn('err_server',$ajax_msg,$ajax_err);
				
				$dataResult['flag'] = 0; //默认为1表示无任何错误
				$dataResult['msg'] = '提交失败！请稍后重新提交！'; //ajax提示信息
				$dataResult['data'] = 'err_server';
				$this->ajaxReturn($dataResult,'JSON');

			}else{

				$ajax_err = 0;
				$ajax_msg = '提交失败！请稍后重新提交！';
				//$this->ajaxReturn('err_server',$ajax_msg,$ajax_err);
				
				$dataResult['flag'] = 0; //默认为1表示无任何错误
				$dataResult['msg'] = '提交失败！请稍后重新提交！'; //ajax提示信息
				$dataResult['data'] = 'err_server';
				$this->ajaxReturn($dataResult,'JSON');
			}
			
			//$this->ajaxReturn($dataResult,'JSON');
			
		}

	}
?>