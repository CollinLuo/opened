<?php
// +----------------------------------------------------------------------
// | TryDemo [ This is just a demo! ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2012 http://www.trydemo.net All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: lsq <qiangshiluo@126.com> 2013-12-11
// +----------------------------------------------------------------------

return array(
	'DEFAULT_THEME'=>'default',
	'APP_STATUS' => '', // 应用状态配置 自动加载该状态对应的配置文件（位于Application/Common/Conf/*.php）
	'DEFAULT_CONTROLLER'=>'Index',
	'SHOW_PAGE_TRACE'=> true, //显示页面Trace信息
	'URL_MODEL' => 0,
	'HTML_CACHE_ON' => false,
	'ACTION_CACHE_ON'  => false,  // 默认关闭Action 缓存
	'DB_FIELD_CACHE' => false,  //默认关闭数据表字段缓存
);
?>