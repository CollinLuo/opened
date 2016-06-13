<?php
// +----------------------------------------------------------------------
// | TryDemo [ This is just a demo! ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2066 http://www.trydemo.net All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: lsq <qiangshiluo@126.com> 2015-06-12
// +----------------------------------------------------------------------
if (!defined('THINK_PATH'))
    exit();
return array(       
	// ThinkPHP3.2.1或以上版本 
	'view_filter' => array('Behavior\TokenBuildBehavior'),
);
