<?php
/**
 * 会员中心(前台用户单表模式暂时屏蔽)
 * ============================================================================
 * 版权所有 2005-2080 Lessismore，并保留所有权利。
 * 网站地址: http://www.trydemo.net；
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和
 * 使用；不允许对程序代码以任何形式任何目的的再发布。
 * ============================================================================
 * $Author: lsq & Lessismore & D.Apache.Luo
 * $Id: UserActivity.class.php 2014-2-27 Lessismore $
*/
namespace Admin\Controller;
use Think\Controller;
use Think\Model;
class UserController extends CommonController {
	public function index(){
		//分配导航栏当前位置
		$this->assign('navigation_bar','会员管理>所有会员');
		$user_mod = D("User");
		//import("ORG.Util.Page");
		$where='is_del = 0';
		if(isset($_REQUEST['keyword'])){
			$keyword = $_REQUEST['keyword'];
			$where.=" and username like '%$keyword%'";
		}
	
		$count = $user_mod->where($where)->count();
		$page = new \Think\Page($count,5);
		$show = $page->show();
		$list=$user_mod->where($where)->order("last_login_time desc")->limit($page->firstRow.','.$page->listRows)->select();
		if(is_array($list) && count($list)){
			foreach ($list as $key=>$val){
				$list[$key]['key']=++$page->firstRow;
			}
		}
		//$this->assign('keyword',$keyword);
		$this->assign('list',$list);
		$this->assign('page',$show);
		$this->display();
	}

	//ajax更改用户审核状态
	public function ajax_update_status(){
		$id = intval(trim($_REQUEST['id']));
		$user_mod = D('User');
		$data['uid'] = $id;
		$arr = $user_mod->field('status')->where($data)->find();
		$set = array();
		if($arr['status'] == 1){
			$set = array('status'=>0);		
		}elseif($arr['status'] == 0){
			$set = array('status'=>1);
		}else{
			$set = array('status'=>0);
		}

		$user_mod->where($data)->save($set);
		$val = $user_mod->field('status')->where($data)->find();
		$this->ajaxReturn($val['status']);
	}
	
	//显示添加新用户界面
	public function mAdd(){
		$user_mod = D('user');
		if (IS_POST) {
			$data = array();
			$data['username'] = isset($_POST['username']) ? trim($_POST['username']) : '' ;
			$data['password'] = isset($_POST['password']) ? trim($_POST['password']) : '' ;
			$data['repwd'] = isset($_POST['repwd']) ? trim($_POST['repwd']) : '' ;
			$data['email'] = isset($_POST['email']) ? trim($_POST['email']) : '' ;
			$data['mobile_number'] = intval($_POST['mobile_number']) ? intval($_POST['mobile_number']) : 0 ;
			$data['qq'] = intval($_POST['qq']) ? intval($_POST['qq']) : 0 ;
			$data['nickname'] = isset($_POST['nickname']) ? trim($_POST['nickname']) : '' ;
			$data['sex'] = $_POST['sex'];
			$data['status'] = isset($_POST['status']) ? intval(trim($_POST['status'])) : 1 ;
			$data['remark'] = isset($_POST['remark']) ? trim($_POST['remark']) : '' ;
			$data['create_time'] = time();
			$data['__hash__'] = $_POST['__hash__'];
			//检测初始数据是否符合规则(密码是否合格、邮箱是否添加、是否重名)
			if($data['password'] == $data['repwd']){
				$data['pwd'] = encrypt($data['password']);
				unset($data['password']);
				unset($data['repwd']);
				if(!empty($data['email'])){
					$pattern = '/^([a-zA-Z0-9]+[\._-]?[a-zA-Z0-9]+)+@[a-zA-Z0-9]+(\.[a-zA-Z0-9])+/i';  
					if(preg_match($pattern,$data['email'])){
						
						$result = $user_mod->where("username='".$data['username']."'")->count();
						if(!$result){
							//检验数据
							$data = $user_mod->create($data, 1); //1是插入操作，0是更新操作
							if ($user_mod->add($data)){
								$this->success("添加用户成功！", U('User/index'));
							} else {
								$this->error($user_mod->getError(),U('User/mAdd'));
							}

						}else{
							$this->error('用户'.$data['username'].'已经存在',U('User/mAdd'));
						}
					}else{
						$this->error('邮箱格式不正确！',U('User/mAdd'));
					}  
				}else{
					$this->error('邮箱不能为空！',U('User/mAdd'));
				}
			}else{
				$this->error('两次密码不一致！',U('User/mAdd'));
			}

		} else {
			//分配导航栏当前位置
			$this->assign('navigation_bar','会员管理>添加新会员');
			$this->assign('act',$Think.ACTION_NAME);
			$this->display("User:add");
		}	
	}

	//显示编辑或者修改用户信息页面
	public function mEdit(){

		if (IS_POST) {
			$data = array();
			$data['uid'] = intval($_POST['id']) ? intval($_POST['id']) : 0 ;
			$username = isset($_POST['username']) ? trim($_POST['username']) : '' ;
			$data['password'] = isset($_POST['password']) ? trim($_POST['password']) : '' ;
			$data['repwd'] = isset($_POST['repwd']) ? trim($_POST['repwd']) : '' ;
			$data['email'] = isset($_POST['email']) ? trim($_POST['email']) : '' ;
			$data['nickname'] = isset($_POST['nickname']) ? trim($_POST['nickname']) : '' ;
			$data['mobile_number'] = intval($_POST['mobile_number']) ? intval($_POST['mobile_number']) : 0 ;
			$data['qq'] = intval($_POST['q']) ? intval($_POST['qq']) : 0 ;
			$data['sex'] = $_POST['sex'];
			$data['status'] = isset($_POST['status']) ? intval(trim($_POST['status'])) : 1 ;
			$data['remark'] = isset($_POST['remark']) ? trim($_POST['remark']) : '' ;
			$data['create_time'] = time();
			$data['__hash__'] = $_POST['__hash__'];

			//检测初始数据是否符合规则(密码是否合格、邮箱是否添加、是否重名)
			if($data['uid']){
				if($data['password']){
					if($data['password'] == $data['repwd']){
						$data['pwd'] = encrypt($data['password']);
						unset($data['password']);
						unset($data['repwd']);
					}else{
						$this->error('两次密码不一致！',U('User/mAdd','id='.$data['uid']));
					}
				}else{
					//密码未修改
					unset($data['password']);
					unset($data['repwd']);
				}

				if(!empty($data['email'])){
					$pattern = '/^([a-zA-Z0-9]+[\._-]?[a-zA-Z0-9]+)+@[a-zA-Z0-9]+(\.[a-zA-Z0-9])+/i';  
					if(!preg_match($pattern,$data['email'])){
						$this->error('邮箱格式不正确！',U('User/mAdd'));
					}  
				}else{
					unset($data['email']);
				}

				if(!$data['qq']){
					unset($data['qq']);
				}

				if(!$data['mobile_number']){
					unset($data['mobile_number']);
				}

				if(!$data['nickname']){
					unset($data['nickname']);
				}

				if(!$data['remark']){
					unset($data['remark']);
				}

				//开始执行更新
				$user_mod = D('user');	
				$result = $user_mod->where("uid='".$data['uid']."'")->count();
				if($result){
					//检验数据
					$data = $user_mod->create($data, 0); //1是插入操作，0是更新操作
					$u_result = $user_mod->where("uid=" . $data['uid'])->save($data);
					if(false !== $u_result){
						$this->success("编辑用户成功！", U('User/index'));
					} else {
						$this->error($user_mod->getError(),U('User/mEdit','id='.$data['uid']));
					}

				}else{
					$this->error('用户'.$data['nickname'].'已经不存在！',U('User/index'));
				}

			}else{
				$this->error('用户记录丢失！',U('User/index'));
			}

		} else {
			//分配导航栏当前位置
			$this->assign('navigation_bar','会员管理>编辑用户信息');
			$this->assign('act',$Think.ACTION_NAME);
			$id = isset($_GET['id']) && intval($_GET['id']) ? intval($_GET['id']) : $this->error('参数错误',U('User/index'));
			$user_mod = D('User');
			$user_info = $user_mod->where('uid='.$id)->find();
			$this->assign('user_info', $user_info);
			$this->display("User:add");
		}
	}

	//删除
	public function ajax_del_users(){
		/*
		$user=D("user");
		$id=!empty($_POST["id"])?$_POST["id"]:$_GET["id"];
		if($user->delete($id)){
			//删除用户评论
			D("comment")->delete(array("uid"=>$id));
			//删除用户相关购物车信息
			//D("car")->delete(array("uid"=>$id));
			//删除用户相关订单信息
			//D("orderfg")->delete(array("uid"=>$id));
			$this->redirect("index","page/{$_GET['page']}");
			$this->mess("删除用户 <b>{$_POST["username"]}</b> 成功,可以继续操作！. ", true);
		}else{
			//$this->error("删除失败!",3,"user/index/page/{$_GET["page"]}");
		}
		*/
	}

	//用户分析	
	public function mUserAnalyse(){
		//分配导航栏当前位置
		$this->assign('navigation_bar','会员管理>用户分析');
		$this->assign('act',$Think.ACTION_NAME);

		$this->display("User:analyse");
	}
}

