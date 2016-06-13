<?php
namespace Home\Model;
use Think\Model;
class ArticleModel extends Model {
	// 实际表名
	protected $trueTableName='td_article';
	
	protected $_auto=array(
		array('release_time','getCTime','1','callback'),
		array('last_mod_time','getCTime','1','callback'),
		array('post_author_ip','getCIp','1','callback'),
	);

	// 获取文章创建时间
	public function getCTime(){
		return date('Y-m-d H:i:s');
	}

	// 获取客户端IP
	public function getCIp(){
		//Vendor('extend');
		return get_client_ip();
	}

	public function getAllArticle(){
		return $this->order('id asc')->select();
	}

	// 根据文章id获取一篇文章信息
	public function getOneArticle($id){
		return $this->where("id=$id")->find();
	}

	// 根据栏目id获取一定数量的文章列表
	public function getArticlesByCat($id,$sum){
		return $this->query("select * from $this->trueTableName where cid=$id and is_lock = 0 and is_del = 0 order by release_time desc limit 0,$sum");
	}

	// 根据栏目和属性获取一条新闻
	public function getOneArticleByFlag($flag,$id){
		$nav=new CategoryModel();
		$navIdStr=$nav->getCatIdStr($id);
		
		return $this->query("select * from $this->trueTableName where cid in($navIdStr) and flag like '%$flag%' order by release_time desc limit 0,1");
	}

	// 根据栏目和属性获取多条产品
	public function getArticleByFlag($flag,$id,$limit){
		return $this->query("select * from $this->trueTableName where cid in($id) and flag and is_lock = 0 and is_del = 0 like '%$flag%' order by release_time desc limit 0,$limit");
	}

	// 随机获取产品
	public function getRandArticleByFlag($id,$limit){
		return $this->query("select * from $this->trueTableName where cid in($id) and is_lock = 0 and is_del = 0 order by rand() desc limit 0,$limit");
	}
	
	// 获取最新产品
	public function getNewArticleByFlag($id,$limit){
		return $this->query("select * from $this->trueTableName where cid in($id) and is_lock = 0 and is_del = 0 order by release_time desc limit 0,$limit");
	}
	
	// 上一篇下一篇文章
	public function getPreOrNext($id,$cid,$flag){
		if($flag > 0){
			return $this->where("cid = $cid and id > $id and is_lock = 0 and is_del = 0")->order("id asc")->find();
		}else{
			return $this->where("cid = $cid and id < $id and is_lock = 0 and is_del = 0")->order("id desc")->find();
		}
	}

	// 根据栏目id查询子孙栏目的指定条数的文章
	public function getSonsArticle($id,$limit){
		$nav=new CategoryModel();
		$catIdStr=$nav->getCatIdStr($id);
		return $this->where("cid in ($catIdStr) and is_lock = 0 and is_del = 0")->limit("0,$limit")->order("release_time desc")->select();
	}

	// 根据栏目id查询子孙栏目的指定条数的文章（带属性）
	public function getSonsFlagArticle($id,$flag,$limit){
		$nav=new CategoryModel();
		$catIdStr=$nav->getCatIdStr($id);
		
		return $this->where("cid in ($catIdStr) and flag like '$flag' and is_lock = 0 and is_del = 0")->limit("0,$limit")->select();
	}
	
	// 获取最近更新的文章
	public function getRecent($limit=10){
		if($limit){
			$sql="select a.id,a.title,a.description,a.release_time,a.keywords,a.cid,c.name as c_name from td_article as a left join td_category as c on a.cid=c.id and a.is_lock = 0 and a.is_del = 0 order by a.release_time desc limit 0,$limit";
			return $this->query($sql);
		}else{
			return false;
		}
	}
	
	// 获取文档归档
	public function getMonthList(){
		$sql="select count(*) as num,substring(release_time,1,7) as pdate from td_article where is_lock = 0 and is_del = 0 group by pdate order by pdate desc";
		return $this->query($sql);
	}

	// 获取文档评论
	public function getComments($id){
		
		$sql="select cid,aid,uid,atitle,comment_author_name,comment_author_avatar,comment_author_email,comment_author_url,comment_author_ip,comment_add_date,comment_edit_date,comment_content,comment_karma,comment_approved,comment_agent,comment_type,comment_top_parent,comment_parent from td_comments where is_lock=0 and aid=$id order by comment_add_date ASC";
		return $this->query($sql);
	}

	// 根据文章id获取最顶级评论（上限为10条）
	public function getCommentsByPage($aid,$page){
		$data = $this->field("cid,aid,uid,atitle,comment_author_name,comment_author_avatar,comment_author_email,comment_author_url,comment_author_ip,comment_add_date,comment_edit_date,comment_content,comment_karma,comment_approved,comment_agent,comment_type,comment_top_parent,comment_parent")->where("is_lock=0 and aid=$aid")->limit(1)->select();
		//fwrites(WEB_ROOT . 'ajax.txt','------------------------->model $data');
		//fwrites(WEB_ROOT . 'ajax.txt',$data);
		return $data;
	}

}
?>