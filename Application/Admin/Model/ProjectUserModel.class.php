<?php
namespace Admin\Model;
use Think\Model;
class ProjectUserModel extends Model {
	protected $tableName = "project_user";
	
	// 获取所有公司用户
	public function getAllProjectUser(){
		return $this->field('id,pid,aid,role_id')->order('id asc')->select();
	}
	
}

?>