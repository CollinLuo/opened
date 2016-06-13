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

$config_arr = @include APP_PATH . 'Common/Conf/systemConfig.php';
$DB_PREFIX = $config_arr['DB_PREFIX'];
if(empty($DB_PREFIX)){
	$DB_PREFIX = "td_";
}

return array(	
	'DEFAULT_THEME'=>'default',
	'APP_STATUS' => '', // 应用状态配置 自动加载该状态对应的配置文件（位于Application/Common/Conf/*.php）
    //以下是RBAC认证配置信息
    'USER_AUTH_ON' => true,
    'USER_AUTH_TYPE' => 2, // 默认认证类型 1 登录认证 2 实时认证
    'USER_AUTH_KEY' => 'td_authId', // 用户认证SESSION标记
    'USER_AUTH_MODEL' => 'Admin', // 默认验证数据表模型
    'AUTH_PWD_ENCODER' => 'md5', // 用户认证密码加密方式encrypt
	'ADMIN_AUTH_KEY' => 'admin', //设置超级管理员
    'USER_AUTH_GATEWAY' => 'Admin/Public/index', // 默认认证网关
    'NOT_AUTH_MODULE' => 'Public', // 默认无需认证模块
    'REQUIRE_AUTH_MODULE' => '', // 默认需要认证模块
    'NOT_AUTH_ACTION' => '', // 默认无需认证操作
    'REQUIRE_AUTH_ACTION' => '', // 默认需要认证操作
    'GUEST_AUTH_ON' => false, // 是否开启游客授权访问
    'GUEST_AUTH_ID' => 0, // 游客的用户ID
	'SHOW_RUN_TIME'         =>false,             // 运行时间显示
	'SHOW_ADV_TIME'         =>false,             // 显示详细的运行时间           
	'SHOW_DB_TIMES'         =>false,             // 显示数据库查询和写入次数     
	'SHOW_CACHE_TIMES'      =>false,             // 显示缓存操作次数             
	'SHOW_USE_MEM'          =>false,             // 显示内存开销
    'RBAC_ROLE_TABLE' => $DB_PREFIX . 'role',
    'RBAC_USER_TABLE' => $DB_PREFIX . 'role_user',
    'RBAC_ACCESS_TABLE' => $DB_PREFIX . 'access',
    'RBAC_NODE_TABLE' => $DB_PREFIX . 'node',
    /*
     * 系统备份数据库时每个sql分卷大小，单位字节
     */
    'sqlFileSize' => 5242880, //该值不可太大，否则会导致内存溢出备份、恢复失败，合理大小在512K~10M间，建议5M一卷
    //10M=1024*1024*10=10485760
    //5M=5*1024*1024=5242880
	'SHOW_PAGE_TRACE'=> true, //显示页面Trace信息
	'URL_MODEL' => 0,
	'HTML_CACHE_ON' => false,
	'ACTION_CACHE_ON'  => false,  // 默认关闭Action 缓存
	'DB_FIELD_CACHE' => false,  //默认关闭数据表字段缓存
);