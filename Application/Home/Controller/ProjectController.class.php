<?php
/**
 * 首页
 * ============================================================================
 * 版权所有 2005-2080 Lessismore，并保留所有权利。
 * 网站地址: http://www.trydemo.net；
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和
 * 使用；不允许对程序代码以任何形式任何目的的再发布。
 * ============================================================================
 * $Author: lsq & Lessismore & D.Apache.Luo $
 * $Intro: ProjectController.class.php 2016-4-13 Lessismore $
*/
namespace Home\Controller;
use Think\Controller;
use Think\Model;

class ProjectController extends CommonController {
	protected  $User, $RoleUser;
	// 前台用户角色ID
	protected $t_role_id = 7;
	/**
      +----------------------------------------------------------
     * 初始化
     * 如果 继承本类的类自身也需要初始化那么需要在使用本继承类的类里使用parent::_initialize();
      +----------------------------------------------------------
     */
	public function _initialize() {
		parent::_initialize();
		$this->User = D("Admin");
		$this->RoleUser = D("RoleUser");
	}
	
	/**
      +----------------------------------------------------------
     * 个人中心界面
      +----------------------------------------------------------
     */
	public function index(){
		// 检查是否登录
		if(!$this->checkLogin()){
			$this->redirect("Index/index");
		}
		
		$this->page_header();
		$this->assign('current_page','项目首页');
		$this->display("Project/index");
	}
	

	
}
?>