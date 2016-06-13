<?php
namespace Admin\Model;
use Think\Model;
class BusinessUserModel extends Model {
	protected $tableName = "business_user";
	
	// 获取所有的业务
	public function getAllBusinessUser(){
		return $this->field('id,bid,aid,spend_time,remark,create_time,update_time')->order('id asc')->select();
	}
	
}

?>