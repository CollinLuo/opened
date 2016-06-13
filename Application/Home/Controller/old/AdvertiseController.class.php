<?php
	/**
	 * 广告页
	 * ============================================================================
	 * 版权所有 2005-2080 Lessismore，并保留所有权利。
	 * 网站地址: http://www.trydemo.net；
	 * ----------------------------------------------------------------------------
	 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和
	 * 使用；不允许对程序代码以任何形式任何目的的再发布。
	 * ============================================================================
	 * $Author: lsq & Lessismore & D.Apache.Luo
	 * $Id: UserActivity.class.php 2014-4-2 Lessismore $
	*/
	namespace Home\Controller;
	use Think\Controller;
	use Think\Model;
	class AdvertiseController extends Controller {
		//php动态生成js广告调用代码
		public function getAd(){
			header("content-type:text/html;charset=utf-8");
			
			$id=$_GET['id']+0;
			$model=D("advertise");
			$oneAd=$model->where("id=$id")->find();
			
			if(empty($oneAd)){
				echo "alert('找不到广告!');";
				exit;
			}
			$linkurl=$oneAd['linkurl'];
			$url=__ROOT__.'/Public/Uploads/'.$oneAd['url'];
			
			echo "document.write(\"<a href='$linkurl'><img src='$url'/></a>\");";
		}
	}
?>