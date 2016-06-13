<?php
namespace Admin\Model;
use Think\Model;
class RoleUserModel extends Model {
	protected $tableName = 'role_user';
	
	/**
      +----------------------------------------------------------
     * 获取管理员角色详细信息
      +----------------------------------------------------------
     */
	public function getRoleInfoById($id){
		$M = M();
		$table = array('role'=>C('RBAC_ROLE_TABLE'),'user'=>C('RBAC_USER_TABLE'),'access'=>C('RBAC_ACCESS_TABLE'),'node'=>C('RBAC_NODE_TABLE'));
		$sql = "Select ru.role_id,ru.user_id,u.aid,u.nickname,u.username,u.status as u_status,r.id,r.pid,r.status as r_status,r.name from ".$table['user']." as ru,".$table['role']." as r,".C('DB_PREFIX')."admin as u where ru.role_id = r.id and ru.user_id = u.aid and ru.user_id = $id";
		return $M->query($sql);
	}

}

?>
