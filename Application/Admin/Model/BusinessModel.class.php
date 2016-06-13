<?php
namespace Admin\Model;
use Think\Model;
class BusinessModel extends Model {
	protected $tableName = "business";
	
	// 获取所有的业务
	public function getAllBusiness(){
		return $this->field('id,name,pid,completeness,appraise,cost,p_bid,create_time,total_time,is_grade,status,remark')->order('id asc')->select();
	}
	
}

?>