<?php
namespace Home\Model;
use Think\Model;
class AuthCodeModel extends Model {
	protected $tableName = "auth_code";

	protected $_validate = array(
		array('type',array(0,1,2,3),'值的范围不正确！',2,'in'), // 当值不为空的时候判断是否在一个范围内 
	);
}
?>