<?php
// +----------------------------------------------------------------------
// | TryDemo [ This is just a demo! ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2012 http://www.trydemo.net All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: lsq <qiangshiluo@126.com> 2013-12-10
// +----------------------------------------------------------------------

// 检测PHP环境
if(version_compare(PHP_VERSION,'5.3.0','<'))  die('require PHP > 5.3.0 !');
ini_set('date.timezone', 'Asia/Shanghai');
define('SITE_PATH', getcwd());//网站当前路径
define("WEB_ROOT", dirname(__FILE__) . "/");

// 开启调试模式 建议开发阶段开启 部署阶段注释或者设为false
define('APP_DEBUG',true);
// 定义应用目录
define('APP_PATH','./Application/');
// 绑定访问Admin模块
define('BIND_MODULE','Admin');
// 绑定访问Index控制器
//define('BIND_CONTROLLER','Index');   参考前台入口配置
// 引入ThinkPHP入口文件
require './ThinkPHP/ThinkPHP.php';