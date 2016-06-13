<?php
	namespace Home\Model;
	use Think\Model;
	class LinkModel extends Model {
		//实际表名
		protected $trueTableName='td_link';		
		
		//查找一条友链信息
		public function getOneLink($id){
			return $this->where("link_id = $id")->find();
		}
		//查找指定数量的友情链接
		public function getLink($sum){
			return $this->query("select * from $this->trueTableName where status = 1 order by sort desc limit 0,$sum");
		}
	}
?>