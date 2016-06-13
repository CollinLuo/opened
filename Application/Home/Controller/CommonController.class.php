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
 * 公共继承类
 * ============================================================================
 * 版权所有 20015-2080 Lessismore，并保留所有权利。
 * 网站地址: http://www.trydemo.net；
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和
 * 使用；不允许对程序代码以任何形式任何目的的再发布。
 * ============================================================================
 * $Author: lsq & Lessismore & D.Apache.Luo
 * $Id: UserActivity.class.php 2016-1-15 Lessismore $
*/

namespace Home\Controller;
use Think\Controller;
use Think\Model;
class CommonController extends Controller {
	public $loginMarked;
	protected $userInfoMarked = 'user_info';
	
	/**
      +----------------------------------------------------------
     * 初始化
     * 如果 继承本类的类自身也需要初始化那么需要在使用本继承类的类里使用parent::_initialize();
      +----------------------------------------------------------
     */
    public function _initialize() {
        header("Content-Type:text/html; charset=utf-8");
        header('Content-Type:application/json; charset=utf-8');
		$loginMarked = C("TOKEN");
		//p($loginMarked);
        $this->loginMarked = md5($loginMarked['home_marked']);
		//p(C(USER_AUTH_KEY));
		//p($loginMarked);
		//p($this->loginMarked);
		//fwrites(APP_PATH . 'Home/ajax.txt',"@url(CommonController-_initialize)--------------- start!");
		//fwrites(APP_PATH . 'Home/ajax.txt','loginMarked:');
		//fwrites(APP_PATH . 'Home/ajax.txt',$this->loginMarked);
		//fwrites(APP_PATH . 'Home/ajax.txt','_COOKIE:');
		//fwrites(APP_PATH . 'Home/ajax.txt',$_COOKIE);
		//fwrites(APP_PATH . 'Home/ajax.txt','_SESSION');
		//fwrites(APP_PATH . 'Home/ajax.txt',$_SESSION);
		//p($_SESSION);
	}
	
	/** 验证码 **/
	protected function getQRCode($url = NULL) {
        if (IS_POST) {
            $this->assign("QRcodeUrl", "");
        } else {
//          $url = empty($url) ? C('WEB_ROOT') . $_SERVER['REQUEST_URI'] : $url;
            $url = empty($url) ? C('WEB_ROOT') . U(MODULE_NAME . '/' . ACTION_NAME) : $url;
            import('QRCode');
            $QRCode = new QRCode('', 80);
            $QRCodeUrl = $QRCode->getUrl($url);
            $this->assign("QRcodeUrl", $QRCodeUrl);
        }
    }

	/** 检查是否登录(根据cookie手动检测获取登录信息、非后台单一入口) **/
    public function checkLogin() {
		//fwrites(APP_PATH . 'Home/ajax.txt',"checkLogin");
		//fwrites(APP_PATH . 'Home/ajax.txt',$this->loginMarked);
		//fwrites(APP_PATH . 'Home/ajax.txt','COOKIE:');
		//fwrites(APP_PATH . 'Home/ajax.txt',$_COOKIE);
		
        if (isset($_COOKIE[$this->loginMarked])) {
			//fwrites(APP_PATH . 'Home/ajax.txt',1);
            $cookie = explode("_", $_COOKIE[$this->loginMarked]);
			//fwrites(APP_PATH . 'Home/ajax.txt','_COOKIE[$this->loginMarked]:');
			//fwrites(APP_PATH . 'Home/ajax.txt',$cookie);
            $timeout = C("TOKEN");
            if (time() > (end($cookie) + $timeout['home_timeout'])) {
                setcookie("$this->loginMarked", NULL, -3600, "/");
                unset($_SESSION[$this->loginMarked], $_COOKIE[$this->loginMarked]);
                //$this->error("登录超时，请重新登录", U("Public/index"));
				//p("登录超时，请重新登录");
				//fwrites(APP_PATH . 'Home/ajax.txt',3);
				return FALSE;
            } else {
				//fwrites(APP_PATH . 'Home/ajax.txt','checkLogin---判断cookie是否存在---else(不存在)');
                if ($cookie[0] == $_SESSION[$this->loginMarked]) {
					//fwrites(APP_PATH . 'Home/ajax.txt','checkLogin---判断cookie是否存在---else(不存在)---设置时间为0');
                    setcookie("$this->loginMarked", $cookie[0] . "_" . time(), 0, "/");
                } else {
                    setcookie("$this->loginMarked", NULL, -3600, "/");
                    unset($_SESSION[$this->loginMarked], $_COOKIE[$this->loginMarked]);
                    //$this->error("帐号异常，请重新登录", U("Public/index"));
					//p("帐号异常，请重新登录");
					//fwrites(APP_PATH . 'Home/ajax.txt',4);
					//fwrites(APP_PATH . 'Home/ajax.txt','帐号异常，请重新登录');
					return FALSE;
                }
            }
        } else {
			//fwrites(APP_PATH . 'Home/ajax.txt',2);
            //$this->redirect("Home/index");
			return FALSE;
        }
		//fwrites(APP_PATH . 'Home/ajax.txt',9);
        return TRUE;
    }
	
	/** 获取当前已登录用户信息(注意:根据cookie判断) **/
	public function getUserInfo(){
		//fwrites(APP_PATH . 'Home/ajax.txt',"获取用户信息开始！");
		if($this->checkLogin()){
			//fwrites(APP_PATH . 'Home/ajax.txt',"@url(getUserInfo)---!!!用户在线！");
			//fwrites(APP_PATH . 'Home/ajax.txt',$this->userInfoMarked);
			return session($this->userInfoMarked);
			
		}else{
			//fwrites(APP_PATH . 'Home/ajax.txt',"@url(getUserInfo)---!!!用户不在线！");
			return false;
		}
	}
	
	/** 获取当前已登录用户信息(注意:根据session判断) **/
	public function getUserInfoBySession(){
		//fwrites(APP_PATH . 'Home/ajax.txt',"@url(getUserInfoBySession)---获取用户信息开始！");
		if(session("?".$this->userInfoMarked)){
			//fwrites(APP_PATH . 'Home/ajax.txt',"@url(getUserInfoBySession)---!!!用户在线！");
			//fwrites(APP_PATH . 'Home/ajax.txt',$this->userInfoMarked);
			return session($this->userInfoMarked);
			
		}else{
			//fwrites(APP_PATH . 'Home/ajax.txt',"@url(getUserInfoBySession)---!!!用户不在线！");
			return false;
		}
	}
	
	/** 退出登录 **/
	public function logout() {
        setcookie("$this->loginMarked", NULL, -3600, "/");
        unset($_SESSION["$this->loginMarked"], $_COOKIE["$this->loginMarked"]);
        if (isset($_SESSION[$this->userInfoMarked])) {
            unset($_SESSION[$this->userInfoMarked]);
            unset($_SESSION);
            session_destroy();
        }
        $this->redirect("Home/index");	
    }
	
	/** ajax退出登录 **/
	public function ajaxLogout() {
		$dataResult = array();
		$dataResult['flag'] = 1; //默认为1表示无任何错误
		$dataResult['msg'] = '注销成功！'; //ajax提示信息
		$dataResult['data'] = ''; //返回数据、修改成功则返回、修改后的数据
        setcookie("$this->loginMarked", NULL, -3600, "/");
        unset($_SESSION["$this->loginMarked"], $_COOKIE["$this->loginMarked"]);
        if (isset($_SESSION[$this->userInfoMarked])) {
            unset($_SESSION[$this->userInfoMarked]);
            unset($_SESSION);
            session_destroy();
        }
		$this->ajaxReturn($dataResult,'JSON');
    }

	//头部处理与文件包含
	public function page_header(){
		header("content-type:text/html;charset=utf-8");
		//网站系统信息基础信息分配
		$sys = $this->getSysInfo();
		$sys_info = array();
		if(count($sys)){
			foreach($sys as $key=>$value){
				switch($value['name']){
					case 'webname':
						$sys_info['webname'] = $sys[$key]['value'];	
						break;
					case 'keywords':
						$sys_info['keywords'] = $sys[$key]['value'];	
						break;
					case 'description':
						$sys_info['description'] = $sys[$key]['value'];	
						break;
					case 'seo_description':
						$sys_info['seo_description'] = $sys[$key]['value'];	
						break;
					case 'beian':
						$sys_info['beian'] = $sys[$key]['value'];	
						break;
				}
			}
		}
		$this->assign('web_name',$sys_info['webname']);
		$this->assign('keywords',$sys_info['keywords']);
		$this->assign('description',$sys_info['description']);
		$this->assign('seo_description',$sys_info['seo_description']);	
		$this->assign('beian',$sys_info['beian']);
		// 处理用户登录模块
		$user_info = array();
		if($this->checkLogin()){
			$this->assign("login_mark",1);
		}else{
			$this->assign("login_mark",0);
		}
		// 此处用户区别用户登陆后退出方式
		$this->assign("is_ajax_logout",0);
	}

	//导航栏处理与文件包含
	public function page_navigation_bar(){
		
		load('extend');
		//$this->assign('Public',APP_PUBLIC_PATH);
		$this->assign('Web_http_host',$_SERVER['HTTP_HOST']);
		
		//网站菜单
		$cat_mod = D("Category");
		$menu = $cat_mod->getAllCat();
		//p($menu);
		if(is_array($menu) && count($menu)){
			foreach($menu as $key=>$value){
				$menu[$key]['sons']=$cat_mod->getSons($value['id']);
			}
		}
		$this->assign('menu',$menu);
		
	}

	//页脚处理与文件包含
	public function page_footer(){
		$link = D("Link");
		//$link = M("\Home\Model\LinkModel");
		$linkList = $link->getLink(8);
		$this->assign('linkList',$linkList);			
	}

	//处理页面右侧信息
	public function page_right(){
		//import('ORG.Util.String'); Tmp false
		//10条最近更新博客
		$article_mod = D("Article");
		$articleList = $article_mod->getRecent();
		$this->assign('rightList',$articleList);
		//文章归档
		$monthList = $article_mod->getMonthList();
		if(is_array($monthList) && count($monthList)){
			foreach($monthList as $key=>$value){
				$monthList[$key]['pubdate']=date("Y年 m月",strtotime($value['pdate']));
			}
		}
		$this->assign('monthList',$monthList);

		//实例化空模型类
		$model = new Model();
		//$model = M();
		//热门标签
		$hotTag = $model->query("select tagname,counts from td_tag order by counts desc limit 0,20");
		//处理字体大小
		foreach($hotTag as $key=>$value){
			$num = floor(($value['counts']+1200)/100);
			$num = $num>30? 30:$num;
			$hotTag[$key]['size']=$num;
		}
		shuffle($hotTag);
		$this->assign('hotTag',$hotTag);
		
		//处理用户登录模块
		$user_info = array();
		if($this->checkLogin()){
			$this->assign("login_mark",1);
			$user_info = $this->getUserInfo();
		}else{
			$this->assign("login_mark",0);
		}
		
		//p($user_info);
		$this->assign("user_info",$user_info);
		//p($_SESSION);
		//近期评论
		$recentCommonet = $model->query("select cid,aid,uid,title,comment_author_name,comment_author_avatar,comment_author_email,comment_author_url,comment_author_ip,comment_add_date,comment_edit_date,comment_content,comment_karma,comment_approved,comment_agent,comment_type,comment_top_parent,comment_parent from td_comments where is_lock=0 order by comment_add_date desc limit 0,10");
		$this->assign('recentCommonet',$recentCommonet);
	}
	
	//获取网站信息
	public function getSysInfo(){
		return D('system')->field("id,name,value")->select();
	}
}	
?>