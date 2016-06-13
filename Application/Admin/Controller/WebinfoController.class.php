<?php
/**
 * 本类设置项目一些常用信息
 * ============================================================================
 * 版权所有 2005-2080 Lessismore，并保留所有权利。
 * 网站地址: http://www.trydemo.net；
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和
 * 使用；不允许对程序代码以任何形式任何目的的再发布。
 * ============================================================================
 * $Author: lsq & Lessismore & D.Apache.Luo
 * $Id: UserActivity.class.php 2014-2-27 Lessismore $
*/

namespace Admin\Controller;
use Think\Controller;
use Think\Model;
class WebinfoController extends CommonController {

    /**
      +----------------------------------------------------------
     * 配置网站信息
      +----------------------------------------------------------
     */
    public function index() {
        $this->checkSystemConfig();
    }

    /**
      +----------------------------------------------------------
     * 配置网站邮箱信息
      +----------------------------------------------------------
     */
    public function setEmailConfig() {
        $this->checkSystemConfig("SYSTEM_EMAIL");
    }

    /**
      +----------------------------------------------------------
     * 配置网站信息
      +----------------------------------------------------------
     */
    public function setSafeConfig() {
        $this->checkSystemConfig("TOKEN");
    }

    /**
      +----------------------------------------------------------
     * 网站配置信息保存操作等
      +----------------------------------------------------------
     */
    private function checkSystemConfig($obj = "SITE_INFO") {
        if (IS_POST) {
            $this->checkToken();
            $config = WEB_ROOT . "Common/systemConfig.php";
            $config = file_exists($config) ? include "$config" : array();
            $config = is_array($config) ? $config : array();
            $config = array_merge($config, array("$obj" => $_POST));
            $str = $obj == "SITE_INFO" ? "网站配置信息" : $obj == "SYSTEM_EMAIL" ? "系统邮箱配置" : "安全设置";
            if (F("systemConfig", $config, WEB_ROOT . "Common/")) {
                delDirAndFile(WEB_CACHE_PATH . "Runtime/Admin/~runtime.php");
                if ($obj == "TOKEN") {
                    unset($_SESSION, $_COOKIE);
                    echo json_encode(array('status' => 1, 'info' => $str . '已更新，你需要重新登录', 'url' => __APP__ . '?' . time()));
                } else {
                    echo json_encode(array('status' => 1, 'info' => $str . '已更新'));
                }
            } else {
                echo json_encode(array('status' => 0, 'info' => $str . '失败，请检查', 'url' => __ACTION__));
            }
        } else {
            $this->display();
        }
    }

    /**
      +----------------------------------------------------------
     * 测试邮件账号是否配置正确
      +----------------------------------------------------------
     */
    public function testEmailConfig() {
        C('TOKEN_ON', false);
        $return = send_mail($_POST['test_email'], "", "测试配置是否正确", "这是一封测试邮件，如果收到了说明配置没有问题", "", $_POST);
        if ($return == 1) {
            echo json_encode(array('status' => 1, 'info' => "测试邮件已经发往你的邮箱" . $_POST['test_email'] . "中，请注意查收"));
        } else {
            echo json_encode(array('status' => 0, 'info' => "$return"));
        }
    }

}

?>