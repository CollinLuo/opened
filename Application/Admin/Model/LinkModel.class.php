<?php
namespace Admin\Model;
use Think\Model;
class LinkModel extends Model {
	protected $tableName = "link";
	
	// 获取所有的友情链接
	public function getAllLink(){
		return $this->field('link_id,link_name,link_url,link_type,link_logo,link_image,link_description,link_owner,link_rating,status,sort,create_time,update_time,link_rss,is_del')->order('link_id asc')->select();
	}
	
}

?>