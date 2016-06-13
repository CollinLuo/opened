<?php
namespace Admin\Model;
use Think\Model;
class ProjectModel extends Model {
	protected $tableName = "project";
	
	// 获取所有的项目
	public function getAllProject(){
		return $this->field('pid,name,cid,appraise,cost,create_time,update_time,status,cover_image,is_end,remark')->order('pid asc')->select();
	}
	
}

?>