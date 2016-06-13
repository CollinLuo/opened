<?php
/**
 * 公共方法
 * ============================================================================
 * 版权所有 2015-2080 Lessismore，并保留所有权利。
 * 网站地址: http://www.trydemo.net；
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和
 * 使用；不允许对程序代码以任何形式任何目的的再发布。
 * ============================================================================
 * $Author: lsq & Lessismore & D.Apache.Luo
 * $Id: CommonController.class.php 2016-1-11 Lessismore $
*/
namespace Admin\Controller;
use Think\Controller;
use Think\Model;
class CommonController extends Controller {

    public $loginMarked;
	public $clue_message;

    /**
      +----------------------------------------------------------
     * 初始化
     * 如果 继承本类的类自身也需要初始化那么需要在使用本继承类的类里使用parent::_initialize();
      +----------------------------------------------------------
     */
    public function _initialize() {
        header("Content-Type:text/html; charset=utf-8");
        header('Content-Type:application/json; charset=utf-8');
        $systemConfig = include APP_PATH . 'Common/Conf/systemConfig.php';
		$systemConfig['WEB_ROOT'] = WEB_ROOT;
        if (empty($systemConfig['TOKEN']['admin_marked'])) {
			$systemConfig['TOKEN']['home_marked'] = "19930316";
            $systemConfig['TOKEN']['home_timeout'] = 3600;
            $systemConfig['TOKEN']['admin_marked'] = "QQ:329786100";
            $systemConfig['TOKEN']['admin_timeout'] = 3600;
            $systemConfig['TOKEN']['member_marked'] = "http://www.trydemo.net";
            $systemConfig['TOKEN']['member_timeout'] = 3600;
            F("systemConfig", $systemConfig, APP_PATH . "Common/Conf/");
            if (is_dir(WEB_ROOT . "install/")) {
                delDirAndFile(WEB_ROOT . "install/", TRUE);
            }
        }
		//p($_SESSION);
		
        $this->loginMarked = md5($systemConfig['TOKEN']['admin_marked']);
        $this->checkLogin();
		
	
        // 用户权限检查
        if (C('USER_AUTH_ON') && !in_array(MODULE_NAME, explode(',', C('NOT_AUTH_MODULE')))) {
            //import('ORG.Util.RBAC');
			$rbac = new \Org\Util\Rbac(); 
			//p(RBAC::AccessDecision());
			//http://localhost/opened/td_admin_system.php/
			//fwrites(WEB_ROOT . 'Admin/ajax.txt','-----Common004');
			
            if (!$rbac::AccessDecision()) {
                //检查认证识别号
				
				//fwrites(WEB_ROOT . 'Admin/ajax.txt','-----Common005');
                if (!$_SESSION [C('USER_AUTH_KEY')]) {
                    //跳转到认证网关
                    $this->redirect(C('USER_AUTH_GATEWAY'));
					//redirect(PHP_FILE . C('USER_AUTH_GATEWAY'));
                }
				//p('99999999999');
				//p(C('RBAC_ERROR_PAGE'));
				//echo C('RBAC_ERROR_PAGE');
                // 没有权限 抛出错误
                if (C('RBAC_ERROR_PAGE')) {
                    // 定义权限错误页面
                    $this->redirect(C('RBAC_ERROR_PAGE'));
                } else {
                    if (C('GUEST_AUTH_ON')) {
						
                        $this->assign('jumpUrl', C('USER_AUTH_GATEWAY'));
                    }
                    // 提示错误信息
                    //echo L('_VALID_ACCESS_');
                    $this->error(L('_VALID_ACCESS_'));
                }
            }
        }
		
		//fwrites(WEB_ROOT . 'Admin/ajax.txt','-----Common007');
        //$this->assign("menu", $this->show_menu());
        //$this->assign("sub_menu", $this->show_sub_menu());
        $this->assign("my_info", $_SESSION['my_info']);
        $this->assign("site", $systemConfig);
		$this->clue_message = '';
		$this->assign("clue_message",$this->clue_message);
		//p($_SESSION);
        //$this->getQRCode();
    }

    protected function getQRCode($url = NULL) {
        if (IS_POST) {
            $this->assign("QRcodeUrl", "");
        } else {
//            $url = empty($url) ? C('WEB_ROOT') . $_SERVER['REQUEST_URI'] : $url;
            $url = empty($url) ? C('WEB_ROOT') . U(MODULE_NAME . '/' . ACTION_NAME) : $url;
            import('QRCode');
            $QRCode = new QRCode('', 80);
            $QRCodeUrl = $QRCode->getUrl($url);
            $this->assign("QRcodeUrl", $QRCodeUrl);
        }
    }

    public function checkLogin() {
        if (isset($_COOKIE[$this->loginMarked])) {
            $cookie = explode("_", $_COOKIE[$this->loginMarked]);
            $timeout = C("TOKEN");
            if (time() > (end($cookie) + $timeout['admin_timeout'])) {
                setcookie("$this->loginMarked", NULL, -3600, "/");
                unset($_SESSION[$this->loginMarked], $_COOKIE[$this->loginMarked]);
                $this->error("登录超时，请重新登录", U("Public/index"));
            } else {
                if ($cookie[0] == $_SESSION[$this->loginMarked]) {
                    setcookie("$this->loginMarked", $cookie[0] . "_" . time(), 0, "/");
                } else {
                    setcookie("$this->loginMarked", NULL, -3600, "/");
                    unset($_SESSION[$this->loginMarked], $_COOKIE[$this->loginMarked]);
                    $this->error("帐号异常，请重新登录", U("Public/index"));
                }
            }
        } else {
            $this->redirect("Public/index");
        }
        return TRUE;
    }

    /**
      +----------------------------------------------------------
     * 验证token信息
      +----------------------------------------------------------
     */
    protected function checkToken() {
        if (IS_POST) {
            if (!M("Admin")->autoCheckToken($_POST)) {
                die(json_encode(array('status' => 0, 'info' => '令牌验证失败')));
            }
            unset($_POST[C("TOKEN_NAME")]);
        }
    }

    /**
      +----------------------------------------------------------
     * 显示一级菜单
      +----------------------------------------------------------
     */
    private function show_menu() {
        $cache = C('admin_big_menu');
        $count = count($cache);
        $i = 1;
        $menu = "";
        foreach ($cache as $url => $name) {
            if ($i == 1) {
                $css = $url == MODULE_NAME || !$cache[MODULE_NAME] ? "fisrt_current" : "fisrt";
                $menu.='<li class="' . $css . '"><span><a href="' . U($url . '/index') . '">' . $name . '</a></span></li>';
            } else if ($i == $count) {
                $css = $url == MODULE_NAME ? "end_current" : "end";
                $menu.='<li class="' . $css . '"><span><a href="' . U($url . '/index') . '">' . $name . '</a></span></li>';
            } else {
                $css = $url == MODULE_NAME ? "current" : "";
                $menu.='<li class="' . $css . '"><span><a href="' . U($url . '/index') . '">' . $name . '</a></span></li>';
            }
            $i++;
        }
        return $menu;
    }

    /**
      +----------------------------------------------------------
     * 显示二级菜单
      +----------------------------------------------------------
     */
    private function show_sub_menu() {
        $big = MODULE_NAME == "Index" ? "Common" : MODULE_NAME;
        $cache = C('admin_sub_menu');
        $sub_menu = array();
        if ($cache[$big]) {
            $cache = $cache[$big];
            foreach ($cache as $url => $title) {
                $url = $big == "Common" ? $url : "$big/$url";
                $sub_menu[] = array('url' => U("$url"), 'title' => $title);
            }
            return $sub_menu;
        } else {
            return $sub_menu[] = array('url' => '#', 'title' => "该菜单组不存在");
        }
    }

	//Author:lsq 2013-12-11
	//后台登陆页验证码生成
	public function verify_code() {
		$w = isset($_GET['w']) ? (int) $_GET['w'] : 50;
		$h = isset($_GET['h']) ? (int) $_GET['h'] : 30;
		import("ORG.Util.Image");
		Image::buildImageVerify(4, 1, 'png', $w, $h);
	}

	
}