<?php
	/**
	 * 搜索页
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
	class SearchController extends CommonController {
		private $page=null;
		
		public function index(){
			header("content-type:text/html;charset=utf-8");
			
			if(empty($_GET['keywords'])){
				echo "<script type='text/javascript'>alert('请输入查询关键词!');history.back();</script>";
				exit;
			}
			
			$this->header();
			$this->footer();
			
			//处理左侧分类
			$nav=new NavModel();
			$leftNav=$nav->getAllCat();
			$this->assign('leftNav',$leftNav);
			
			$keywords=trim($_GET['keywords']);
			$this->assign('keywords',$keywords);
			
			$articleList=$this->getSearchPage($keywords,10);

			$this->assign('articleList',$articleList);
			
			$this->assign('page',$this->page->show());
			
			$this->display('Public:search');

		}
		private function getSearchPage($keywords,$limit){
			import("ORG.Util.Page");
			
			$where="title like '%$keywords%'";
			
			if(isset($_GET['reid'])){
				$nav=new NavModel();
				$navIdStr=$nav->getCatIdStr($_GET['reid']);
				
				$where.=" and reid in ($navIdStr)";
			}
		
			$article=new ArticleModel();
			$articleSum=$article->where($where)->count();
			
			if(!isset($limit)){
				$limit=15;
			}
			//select a.id,a.title,c.id,c.name,c.model from wq_article as a left join wq_nav as c on a.reid=c.id where a.title like '%二%' and c.model=1
			$this->page=new Page($articleSum,$limit);
					
			return $article->where($where)->order('id desc')->limit($this->page->firstRow.','.$this->page->listRows)->select();				
		}
	}
?>