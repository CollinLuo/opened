<?php
namespace Admin\Model;
use Think\Model;
class UserModel extends Model {
	protected $tableName = "user";
    protected $_map = array(
		'id' => 'uid', //把表单中的id映射到数据表的uid字段
		'name' => 'username', //把表单中的name映射到数据表的username字段
	);

}

?>
