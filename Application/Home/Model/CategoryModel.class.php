<?php
namespace Home\Model;
use Think\Model;
class CategoryModel extends Model {
	protected $_auto=array(
		array('date','getCTime','1','callback')
	);

	//获取栏目新增时间
	public function getCTime(){
		return date('Y-m-d H:i:s');
	}
	
	//获取菜单导航
	public function getAllCat($limit = 0){
		if(!isset($limit) || empty($limit) || !$limit){
			return $this->where("is_navigation=1")->order("sort asc")->select();
		}else{
			return $this->limit("0,$limit")->where("is_navigation=1")->order("sort asc")->select();
		}
	}
	public function getAllCat2(){
		return $this->order("sort asc")->select();
	}

	//根据栏目模型选取栏目
	public function getAllCatByModel($model){
		return $this->where("model=$model")->select();
	}

	//获得栏目家谱树
	public function getCatTree($navlist,$id=0,$level=0){
		$tree=array();
		if(!empty($navlist)){
			foreach($navlist as $value){
				if($value['pid']==$id){
					$value['level']=$level;
					array_push($tree,$value);
					$tree=array_merge($tree,$this->getCatTree($navlist,$value['id'],$level+1));
				}
			}
		}
		return $tree;
	}

	//根据id查找栏目详情
	public function getOneCategory($id){		
		return $this->where("id=$id")->find();
	}

	//根据栏目id查找其上级家谱树
	public function getFamily($navlist,$id=0,$level=0){
		
		$tree=array();
		foreach($navlist as $value){
			if($value['id']==$id && $id>0){
				$value['level']=$level;
				array_push($tree,$value);
				$tree=array_merge($tree,$this->getFamily($navlist,$value['pid'],$level+1));
			}
		}
		return $tree;
	}

	//根据栏目id查询其所有的子孙栏目
	public function getSonsById($navlist,$id,$level=0){
		$tree=array();	
		foreach($navlist as $value){			
			if($value['pid']==$id){
				$value['level']=$level;
				array_push($tree,$value);
				$tree=array_merge($tree,$this->getSonsById($navlist,$value['id'],$level+1));
			}
		}		
		return $tree;
	}
	
	//根据id查找一栏目的子栏目
	public function getSons($id,$limit = 0){
		if(isset($limit) && $limit != ''){
			return $this->where("pid=$id")->limit("0,$limit")->select();
		}else{
			return $this->where("pid=$id")->select();
		}			
	}
	
	//获取头部导航主栏目
	public function getMainCat($limit){
		return $this->where("pid=0")->limit("0,$limit")->select();
	}

	//根据栏目id查询其子孙栏目id
	public function getCatIdStr($id){
		$idArr=array();
		//当前栏目
		$oneCat=$this->getOneCat($id);
		//子栏目
		$navSons=$this->getSonsById($this->getAllCat2(),$id);		
		if(!empty($navSons)){
			$oneCat=array_merge($navSons,$oneCat);
		}
		
		foreach($oneCat as $value){
			array_push($idArr,$value['id']);
		}
		if(count($idArr)){
			return implode(',',$idArr);
		}else{
			return false;
		}	
	}

	//根据id查找栏目是否存在(####以二维数组形式返回)
	public function getOneCat($id){
		$id = $id+0;
		if(!is_numeric($id)){
			return false;
		}
		return $this->where("id=$id")->select();	
	}

	//根据id找到一栏目的同级栏目
	public function getLevelCat($id){
		return $this->where("pid=$id")->select();
	}
	
	//根据栏目的id字符串查找栏目
	public function getSomeCat($idStr){
		return $this->where("id in ($idStr)")->select();
	}
}
?>