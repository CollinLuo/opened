<?php
namespace Home\Model;
use Think\Model;
class CommentsModel extends Model {
	protected $trueTableName = 'td_comments';
	
	//根据评论id查找评论详细
	public function getInfoById($id){
		$data = $this->field("cid,aid,uid,type,correlation_id,title,comment_author_name,comment_author_avatar,comment_author_email,comment_author_url,comment_author_ip,comment_add_date,comment_edit_date,comment_content,comment_karma,comment_approved,comment_agent,comment_type,comment_top_parent,comment_parent,is_lock")->where("cid=$id")->limit(1)->select();
		return $data;
	}

	// 根据文章id删除所有评论

}
?>