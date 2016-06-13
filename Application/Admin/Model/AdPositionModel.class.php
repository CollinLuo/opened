<?php
namespace Admin\Model;
use Think\Model;
class AdPositionModel extends Model {
	protected $tableName = "ad_position";
	
	// 获取所有的广告位
	public function getAllAdPosition(){
		return $this->field('position_id,position_name,ad_width,ad_height,area_code,position_desc,create_time,update_time')->order('position_id asc')->select();
	}
	
}

?>