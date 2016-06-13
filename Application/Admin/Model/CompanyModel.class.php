<?php
namespace Admin\Model;
use Think\Model;
class CompanyModel extends Model {
	protected $tableName = "company";
	
	// 获取所有的公司
	public function getAllCompany(){
		return $this->field('cid,name,email,phone,address,business_license,aid,remark,status,is_del,create_time,update_time')->order('cid asc')->select();
	}
	
}

?>