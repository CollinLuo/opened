<?php
//                            _ooOoo_
//                           o8888888o
//                           88" . "88
//                           (| -_- |)
//                            O\ = /O
//                        ____/`---'\____
//                      .   ' \\| |// `.
//                       / \\||| : |||// \
//                     / _||||| -:- |||||- \
//                       | | \\\ - /// | |
//                     | \_| ''\---/'' | |
//                      \ .-\__ `-` ___/-. /
//                   ___`. .' /--.--\ `. . __
//                ."" '< `.___\_<|>_/___.' >'"".
//               | | : `- \`.;`\ _ /`;.`/ - ` : | |
//                 \ \ `-. \_ __\ /__ _/ .-` / /
//         ======`-.____`-.___\_____/___.-`____.-'======
//                            `=---='
//
//         .............................................
//                  佛祖保佑             永无BUG
/**
 * 公共免认证方法
 * ============================================================================
 * 版权所有 2015-2080 Lessismore，并保留所有权利。
 * 网站地址: http://www.trydemo.net；
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和
 * 使用；不允许对程序代码以任何形式任何目的的再发布。
 * ============================================================================
 * $Author: lsq & Lessismore & D.Apache.Luo
 * $Id: PublicController.class.php 2016-1-11 Lessismore $
*/
namespace Admin\Controller;
use Think\Controller;
use Think\Model;
class PublicController extends Controller {

    public $loginMarked;

    /**
      +----------------------------------------------------------
     * 初始化
      +----------------------------------------------------------
     */
    public function _initialize() {	
        header("Content-Type:text/html; charset=utf-8");
        header('Content-Type:application/json; charset=utf-8');
        $loginMarked = C("TOKEN");
        $this->loginMarked = md5($loginMarked['admin_marked']);
    }

    /**
      +----------------------------------------------------------
     * 验证token信息
      +----------------------------------------------------------
     */
    private function checkToken() {
        if (!M("Admin")->autoCheckToken($_POST)) {
            die(json_encode(array('status' => 0, 'info' => '令牌验证失败')));
        }
        unset($_POST[C("TOKEN_NAME")]);
    }

    public function index() {
		
        if (IS_POST) {
			//fwrites(APP_PATH . 'Admin/ajax.txt','@param(index)--->保存权限前Session_1：');
			//fwrites(APP_PATH . 'Admin/ajax.txt',$_SESSION);
            $this->checkToken();
			//fwrites(APP_PATH . 'Admin/ajax.txt','@param(index)--->保存权限前Session_2：');
			//fwrites(APP_PATH . 'Admin/ajax.txt',$_SESSION);
            $returnLoginInfo = D("Admin")->auth();
            //生成认证条件
            if ($returnLoginInfo['status'] == 1) {
                $map = array();
                // 支持使用绑定帐号登录
                $map['username'] = I('request.username');
                //import('ORG.Util.RBAC');
				$rbac = new \Org\Util\Rbac(); 
                $authInfo = $rbac::authenticate($map);
				//fwrites(APP_PATH . 'Admin/ajax.txt','@param(index)--->authInfo:');
				//fwrites(APP_PATH . 'Admin/ajax.txt',$authInfo);
                $_SESSION[C('USER_AUTH_KEY')] = $authInfo['aid'];
                $_SESSION['username'] = $authInfo['username'];
                if ($authInfo['username'] == C('ADMIN_AUTH_KEY')) {
                    $_SESSION[C('ADMIN_AUTH_KEY')] = true;
                }
				//fwrites(APP_PATH . 'Admin/ajax.txt','@param(index)--->保存权限前Session：');
				//fwrites(APP_PATH . 'Admin/aj2015/6/24ax.txt',$_SESSION);
                // 缓存访问权限
                $rbac::saveAccessList();
				//fwrites(APP_PATH . 'Admin/ajax.txt','@param(index)--->保存权限之后Session：');
				//fwrites(APP_PATH . 'Admin/ajax.txt',$_SESSION);
				//$this->redirect("Index/index");
				//p($_SESSION);
				$this->success($returnLoginInfo['info'],U('Index/index'));
            }else{
				$this->error($returnLoginInfo['info'],U('Index/index'));		
			}
			
        } else {
			
            if (isset($_COOKIE[$this->loginMarked])) {
                $this->redirect("Index/index");
            }

            $systemConfig = include APP_PATH . 'Common/Conf/systemConfig.php';
            $this->assign("site", $systemConfig);
			//p($systemConfig);
            $this->display("Login:login");
        }
    }

    public function loginOut() {
        setcookie("$this->loginMarked", NULL, -3600, "/");
        unset($_SESSION["$this->loginMarked"], $_COOKIE["$this->loginMarked"]);
        if (isset($_SESSION[C('USER_AUTH_KEY')])) {
            unset($_SESSION[C('USER_AUTH_KEY')]);
            unset($_SESSION);
            session_destroy();
        }
        $this->redirect("Index/index");	
    }

    public function findPwd() {
        $M = D("Admin");
        if (IS_POST) {
            $this->checkToken();
            echo json_encode(D("Admin")->findPwd());
        } else {
            setcookie("$this->loginMarked", NULL, -3600, "/");
            unset($_SESSION["$this->loginMarked"], $_COOKIE["$this->loginMarked"]);
            $cookie = $this->_get('code');
            $shell = substr($cookie, -32);
            $aid = (int) str_replace($shell, '', $cookie);
            $info = $M->where("`aid`='$aid'")->find();
            if ($info['status'] == 0) {
                $this->error("你的账号被禁用，有疑问联系管理员吧", __APP__);
            }
            if (md5($info['find_code']) == $shell) {
                $this->assign("info", $info);
                $_SESSION['aid'] = $aid;
                $systemConfig = include APP_PATH . 'Common/Conf/systemConfig.php';
                $this->assign("site", $systemConfig);
                $this->display("Common:findPwd");
            } else {
                $this->error("验证地址不存在或已失效", __APP__);
            }
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