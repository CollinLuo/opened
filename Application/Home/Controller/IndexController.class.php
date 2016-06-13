<?php
/**
 * 首页
 * ============================================================================
 * 版权所有 20015-2080 Lessismore，并保留所有权利。
 * 网站地址: http://www.trydemo.net；
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和
 * 使用；不允许对程序代码以任何形式任何目的的再发布。
 * ============================================================================
 * $Author: lsq & Lessismore & D.Apache.Luo
 * $Id: UserActivity.class.php 2016-1-15 Lessismore $
*/
namespace Home\Controller;
use Think\Controller;
use Think\Model;
class IndexController extends CommonController{
	
	protected $page;
	protected $pageNum = 10; //默认每页显示总数
	protected $Article,$Category;

	public function _initialize() {
		parent::_initialize();
		$this->Article = D("Article");
		$this->Category = D("Category");
	}

	//首页
	public function index(){
		
		$this->page_header();
		$this->page_footer();
		$this->display("Index:index");
	}


}
?>
 