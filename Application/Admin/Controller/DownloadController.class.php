<?php
/**
 * 文件下载
 * ============================================================================
 * 版权所有 2015-2080 Lessismore，并保留所有权利。
 * 网站地址: http://www.trydemo.net；
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和
 * 使用；不允许对程序代码以任何形式任何目的的再发布。
 * ============================================================================
 * $Author: lsq & Lessismore & D.Apache.Luo
 * $Id: DownloadController.class.php 2016-2-25 Lessismore $
*/
namespace Admin\Controller;
use Think\Controller;
use Think\Model;
class DownloadController extends CommonController {
	protected   $FileManage;
	/**
      +----------------------------------------------------------
     * 初始化
     * 如果 继承本类的类自身也需要初始化那么需要在使用本继承类的类里使用parent::_initialize();
      +----------------------------------------------------------
     */
	public function _initialize() {
		// parent::_initialize();
		$this->FileManage = D("File");
	}
	
	/**
      +----------------------------------------------------------
     * 文件下载
      +----------------------------------------------------------
     */
	public function download(){
		$id = I('get.id',0,'intval');
		if(!$id){
			$this->error("参数错误！",U('File/index'));
		}
		$info = $this->FileManage->field('id,name,pid,address,type,download_count,status,create_time,update_time,remark')->where('id='.$id)->find();
		if($info){
			/*********************************
			$systemConfig = include APP_PATH . 'Common/Conf/systemConfig.php';
			if (!$systemConfig['SITE_INFO']['website']) {
                $urlDomain = urlDomain(get_url()); //当前页面地址域名
            } else {
                $urlDomain = urlDomain($systemConfig['SITE_INFO']['website']);
            }
			$fileurl = substr($urlDomain,0,strlen($urlDomain)-1).''.__ROOT__.'/Uploads/YunFile/'.$info['address'];
			//不管附件地址是远程地址，还是不带域名的地址，都进行替换
            $fileurl = str_replace($urlDomain, "", $fileurl);
			**********************************/
			// $fileurl = __ROOT__.'/Uploads/YunFile/'.$info['address'];
			$fileurl = $_SERVER['DOCUMENT_ROOT'].''.__ROOT__.'/Uploads/YunFile/'.$info['address'];
			if (is_file($fileurl)) {
				// 下载次数累加
				$result = $this->FileManage->where('id='.$id)->setInc('download_count');
				$this->downfiles($fileurl, $info['name']);
			} else {
				$this->error("需要下载的文件不存在！",U('File/index'));
			}
		}else{
			$this->error("未查找到该文件！请刷新重试！",U('File/index'));
		}

	}
	
	/**
      +----------------------------------------------------------
     * 下载方法
      +----------------------------------------------------------
     */
	protected function downfiles($file, $basename) {
        //获取用户客户端UA，用来处理中文文件名
        $ua = $_SERVER["HTTP_USER_AGENT"];
        //从下载文件地址中获取的后缀
        $fileExt = fileext(basename($file));
        //下载文件名后缀
        $baseNameFileExt = fileext($basename);
        if (preg_match("/MSIE/", $ua)) {
            $filename = iconv("UTF-8", "GB2312//IGNORE", $baseNameFileExt ? $basename : ($basename . "." . $fileExt));
        } else {
            $filename = $baseNameFileExt ? $basename : ($basename . "." . $fileExt);
        }
        header("Content-type: application/octet-stream");
        $encoded_filename = urlencode($filename);
        $encoded_filename = str_replace("+", "%20", $encoded_filename);
        if (preg_match("/MSIE/", $ua)) {
            header('Content-Disposition: attachment; filename="' . $encoded_filename . '"');
        } else if (preg_match("/Firefox/", $ua)) {
            header("Content-Disposition: attachment; filename*=\"utf8''" . $filename . '"');
        } else {
            header('Content-Disposition: attachment; filename="' . $filename . '"');
        }
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        header("Content-Length: " . filesize($file));
        readfile($file);
    }
	
	
}

