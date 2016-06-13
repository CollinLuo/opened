<?php
namespace Admin\Model;
use Think\Model;
class CompanyUserModel extends Model {
	protected $tableName = "company_user";
	
	// 获取所有公司用户
	public function getAllCompanyUser(){
		return $this->field('id,cid,aid,role_id')->order('id asc')->select();
	}
	
}

?>