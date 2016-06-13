<?php
/**
 * 后台首页
 * ============================================================================
 * 版权所有 2015-2080 Lessismore，并保留所有权利。
 * 网站地址: http://www.trydemo.net；
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和
 * 使用；不允许对程序代码以任何形式任何目的的再发布。
 * ============================================================================
 * $Author: lsq & Lessismore & D.Apache.Luo
 * $Id: UserActivity.class.php 2016-1-11 Lessismore $
*/
namespace Admin\Controller;
use Think\Controller;
use Think\Model;
class IndexController extends CommonController {

    public function index() {
        //服务器信息
        if (function_exists('gd_info')) {
            $gd = gd_info();
            $gd = $gd['GD Version'];
        } else {
            $gd = "不支持";
        }
        $info = array(
            '操作系统' => PHP_OS,
            '主机名IP端口' => $_SERVER['SERVER_NAME'] . ' (' . $_SERVER['SERVER_ADDR'] . ':' . $_SERVER['SERVER_PORT'] . ')',
            '运行环境' => $_SERVER["SERVER_SOFTWARE"],
            'PHP运行方式' => php_sapi_name(),
            '程序目录' => WEB_ROOT,
            'MYSQL版本' => function_exists("mysql_close") ? mysql_get_client_info() : '不支持',
            'GD库版本' => $gd,
			//'MYSQL版本' => mysql_get_server_info(),
            '上传附件限制' => ini_get('upload_max_filesize'),
            '执行时间限制' => ini_get('max_execution_time') . "秒",
            '剩余空间' => round((@disk_free_space(".") / (1024 * 1024)), 2) . 'M',
            '服务器时间' => date("Y年n月j日 H:i:s"),
            '北京时间' => gmdate("Y年n月j日 H:i:s", time() + 8 * 3600),
            '采集函数检测' => ini_get('allow_url_fopen') ? '支持' : '不支持',
            'register_globals' => get_cfg_var("register_globals") == "1" ? "ON" : "OFF",
            'magic_quotes_gpc' => (1 === get_magic_quotes_gpc()) ? 'YES' : 'NO',
            'magic_quotes_runtime' => (1 === get_magic_quotes_runtime()) ? 'YES' : 'NO',
        );
        $this->assign('server_info', $info);
		//fwrites(WEB_ROOT . 'Admin/ajax.txt','-----已跳转Index');
		//fwrites(WEB_ROOT . 'Admin/ajax.txt',$info);
		//fwrites(WEB_ROOT . 'Admin/ajax.txt','----->如下是最新的SESSION信息');
		//fwrites(WEB_ROOT . 'Admin/ajax.txt',$_SESSION);
        $this->display("Index:index");
		
    }

	function left(){
		$this->display("Index:left");
	}

	function nav(){
		$this->display("Index:nav");
	}

	function main(){
		$this->display("Index:main");
	}

	//仪表盘主体内容模块
	public function mIndex() {
        $this->display("Index:main");
    }

    public function myInfo() {
        if (IS_POST) {
            $this->checkToken();
            echo json_encode(D("Index")->my_info($_POST));
        } else {
            $this->display();
        }
    }

    public function cache() {
//        $caches = array(
//            "allCache" => WEB_CACHE_PATH,
//            "allRunCache" => WEB_CACHE_PATH . "Runtime/",
//            "allAdminRunCache" => WEB_CACHE_PATH . "Runtime/Admin/",
//            "allHomeRunCache" => WEB_CACHE_PATH . "Runtime/Home/",
//            "allHomeRunCache" => WEB_CACHE_PATH . "Runtime/Home/",
//        );
        $caches = array(
            "HomeCache" => array("name" => "网站前台缓存文件", "path" => RUNTIME_PATH . "Cache/Home/"),
            "AdminCache" => array("name" => "网站后台缓存文件", "path" => RUNTIME_PATH . "Cache/Admin/"),
            "HomeData" => array("name" => "网站前台数据库字段缓存文件", "path" => RUNTIME_PATH . "Data/Home/"),
            "AdminData" => array("name" => "网站后台数据库字段缓存文件", "path" => RUNTIME_PATH . "Data/Admin/"),
            "HomeLog" => array("name" => "网站前台日志缓存文件", "path" => RUNTIME_PATH . "Logs/Home"),
            "AdminLog" => array("name" => "网站后台日志缓存文件", "path" => RUNTIME_PATH . "Logs/Admin/"),
            "HomeTemp" => array("name" => "网站前台临时缓存文件", "path" => RUNTIME_PATH . "Temp/Home"),
            "AdminTemp" => array("name" => "网站后台临时缓存文件", "path" => RUNTIME_PATH . "Temp/Admin/"),
            "MinFiles" => array("name" => "JS\CSS压缩缓存文件", "path" => RUNTIME_PATH . "MinFiles/")
        );
        if (IS_POST) {
            foreach ($_POST['cache'] as $path) {
                if (isset($caches[$path]))
                    delDirAndFile($caches[$path]['path']);
            }
//            pre($_POST);
//            $this->checkToken();
            echo json_encode(array("status"=>1,"info"=>"缓存文件已清除"));
        } else {
            $this->assign("caches", $caches);
            $this->display();
        }
    }

}