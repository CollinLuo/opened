<?php
namespace Admin\Model;
use Think\Model;
class FileModel extends Model {
	protected $tableName = "file";
	
	// 获取所有的文件
	public function getAllFile(){
		return $this->field('id,pid,name,address,type,download_count,status,create_time,update_time,remark')->order('id asc')->select();
	}
	
}

?>