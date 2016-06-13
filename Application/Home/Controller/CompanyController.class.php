<?php
/**
 * 公司首页
 * ============================================================================
 * 版权所有 2005-2080 Lessismore，并保留所有权利。
 * 网站地址: http://www.trydemo.net；
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和
 * 使用；不允许对程序代码以任何形式任何目的的再发布。
 * ============================================================================
 * $Author: lsq & Lessismore & D.Apache.Luo $
 * $Intro: CompanyController.class.php 2016-4-7 Lessismore $
*/
namespace Home\Controller;
use Think\Controller;
use Think\Model;

class CompanyController extends CommonController {
	protected  $Company, $CompanyUser, $Role, $RoleUser, $FileManage, $Comments, $User;
	// 甲方用户角色ID
	protected $t_role_a_id = 11;
	protected $t_role_a_manage_id = 20;
	protected $t_role_b_id = 12;
	protected $t_role_b_manage_id = 21;
	// 实际表名
	protected $t_n_comments='td_comments';
	/**
      +----------------------------------------------------------
     * 初始化
     * 如果 继承本类的类自身也需要初始化那么需要在使用本继承类的类里使用parent::_initialize();
      +----------------------------------------------------------
     */
	public function _initialize() {
		parent::_initialize();
		$this->Company = D("Admin/Company");
		$this->CompanyUser = D("Admin/CompanyUser");
		$this->Role = D("Admin/Role");
		$this->RoleUser = D("Admin/RoleUser");
		$this->FileManage = D("File");
		$this->Comments = D("Comments");
		$this->User = D("Admin");
	}
	
	/**
      +----------------------------------------------------------
     * 公司首页
      +----------------------------------------------------------
     */
	public function index(){
		$id = isset($_GET['id']) && intval($_GET['id']) ? intval($_GET['id']) : $this->error('参数错误！',U('User/uCenter'));
		// 检查是否登录
		if(!$this->checkLogin()){
			$this->redirect("Index/index");
		}
		$this->page_header();
		$this->check_time = time();
		$this->assign('current_page','公司首页');
		$c_user_info = $this->getUserInfo();
		$user_info = $this->User->getOneUserById($c_user_info['aid']);
		$this->assign('user_info',$user_info);
		$com_info = $this->Company->field("cid,name,email,phone,address,website,company_amounts,business_license,company_logo,aid,remark,type,create_time")->where('cid='.$id.' and status = 1 and is_del = 0')->find();
		if($com_info && count($com_info)){
			$com_u_list = $this->CompanyUser->field("cu.id,cu.cid,cu.aid,cu.role_id,a.username,a.email,a.nickname,a.sex,a.avatar,a.description")->join("as cu LEFT JOIN td_admin as a ON a.aid = cu.aid")->where("cu.cid = $id and a.status = 1 and a.is_del = 0")->select();
			
			if($com_u_list && count($com_u_list)){
				$com_b_info = $this->Company->field("cid,name")->where("type = 1")->find();
				foreach($com_u_list as $key=>$value){
					if($value['role_id'] == $this->t_role_a_id){
						$com_u_list[$key]['company_name'] = $com_info['name'];
						$com_info['party_a'][] = $com_u_list[$key];
					}else if($value['role_id'] == $this->t_role_b_id){
						$com_u_list[$key]['company_name'] = $com_b_info['name'];
						$com_info['party_b'][] = $com_u_list[$key];
					}else{
						$p_role_id = $this->Role->where("id = ".$value['role_id'])->getField("pid");
						
						if($p_role_id == $this->t_role_a_id){
							$com_u_list[$key]['company_name'] = $com_info['name'];
							$com_info['party_a'][] = $com_u_list[$key];
						}else if($p_role_id == $this->t_role_b_id){
							$com_u_list[$key]['company_name'] = $com_b_info['name'];
							$com_info['party_b'][] = $com_u_list[$key];
						}
					}
				}
			}
			
		}else{
			$this->error('该公司已经不存在！',U('User/uCenter'));
		}
		// 初始化员工操作权限
		$access_check_sign = 0;
		if($com_info['aid']){
			$role_u_list = $this->RoleUser->field("role_id")->where("user_id = ".$com_info['aid'])->select();
			if($role_u_list && count($role_u_list)){
				foreach($role_u_list as $key=>$value){
					if($value == $this->t_role_a_manage_id){
						$access_check_sign = 1;
					}else if($value == $this->t_role_b_manage_id){
						$access_check_sign = 2;
					}
				}
			}
		}
		// p($com_info);
		// p($access_check_sign);
		$this->assign("access_check_sign",$access_check_sign);
		$this->assign('info', $com_info);
		
		$this->display("Company/index");
	}
	
	//上传操作
    public function upload(){
		$dataResult = array();
		$dataResult['flag'] = 1; //默认为1表示无任何错误
		$dataResult['msg'] = '友情链接名称不重复！'; //ajax提示信息
		$dataResult['data'] = ''; //返回数据、修改成功则返回、修改后的数据

		if(IS_AJAX){
			C('TOKEN_ON',false);
			$token = I('post.token');
			$timestamp = I('post.timestamp');
			$verifyToken = md5('unique_salt' . $timestamp);
			$data['pid'] = isset($_POST['pid']) ? intval($_POST['pid']) : 0 ;
			$data['status'] = 1;
			$data['type'] = 0;
			$data['is_del'] = 0;
			$data['remark'] = '';
			$data['create_time'] = time();
			$data['update_time'] = $data['create_time'];
			
			// fwrites(APP_PATH . 'Home/ajax.txt',"@url(upload)--------------- start!");
			// fwrites(APP_PATH . 'Home/ajax.txt',$_POST);
			// fwrites(APP_PATH . 'Home/ajax.txt','token:'.$token);
			// fwrites(APP_PATH . 'Home/ajax.txt','timestamp:'.$timestamp);
			// fwrites(APP_PATH . 'Home/ajax.txt','verifyToken:'.$verifyToken);
			// fwrites(APP_PATH . 'Home/ajax.txt',$_FILES);
			if (!empty($_FILES) && $token == $verifyToken) { 
				//上传参数配置
				$config=array(
					'maxSize'    => 3145728,// 设置附件上传大小
					'rootPath'   => './Uploads/YunFile/',// 设置附件上传根目录
					'savePath'   => '',// 设置附件上传（子）目录
					'saveName'   => array('uniqid',''),//保存名称
					'exts'       => array('jpg', 'gif', 'png', 'jpeg','xlsx','pdf','doc','docx','pdf','rar','zip'),// 设置附件上传类型
					'autoSub'    => true,//自动使用子目录保存上传文件 默认为true
					'subName'    => array('date','Ymd'),//子目录创建方式，采用数组或者字符串方式定义
					);
				//$upload = new \Think\Upload($config);// 实例化上传类
				$upload = new \Think\Upload($config,'Local');// 实例化上传类
				// 上传文件 
				$info = $upload->upload();
				if(!$info) {
					// 上传错误提示错误信息
					$dataResult['flag'] = 0; //默认为1表示无任何错误
					$dataResult['msg'] = $upload->getError(); //ajax提示信息
					$dataResult['data'] = '';
				}else{
					// 上传成功
					fwrites(APP_PATH . 'Home/ajax.txt',"文件上传成功！");
					fwrites(APP_PATH . 'Home/ajax.txt',$info);
					/*
					//添加水印和缩略图
					$img_url = $config['rootPath'].$info['Filedata']['savepath'].$info['Filedata']['savename'];//图片地址
					$img = new \Think\Image();
					$img->open($img_url)->text('Opened','./Public/Font/glyphicons-halflings-regular.ttf',20,'#000000',\Think\Image::IMAGE_WATER_SOUTHEAST)->save($img_url);
					fwrites(APP_PATH . 'Home/ajax.txt','文字水印添加完了！！！');
					*/
					/*
					$tumb_url=$config['rootPath'].$info['Filedata']['savepath'].'tb_'.$info['Filedata']['savename'];//缩略图地址
					$img->open($img_url)->thumb(50, 50, \Think\Image::IMAGE_THUMB_FIXED)->save($tumb_url);
					fwrites(APP_PATH . 'Home/ajax.txt','文字缩略图添加完了！！！');
					*/
					$data['name'] = $info['Filedata']['name'];
					$data['address'] = $info['Filedata']['savepath'].$info['Filedata']['savename'];
					$type_name = $info['Filedata']['ext'];
					fwrites(APP_PATH . 'Home/ajax.txt',"type_name:".$type_name);
					// 默认为0表示没有实体文件，1表示doc,2表示excel,3表示txt,4表示jpg,5表示gif,6表示png,7表示jpeg
					switch($type_name){
						case "doc":
							$data['type'] = 1;
							break;
						case "xlsx":
							$data['type'] = 2;
							break;
						case "txt":
							$data['type'] = 3;
							break;
						case "jpg":
							$data['type'] = 4;
							break;
						case "gif":
							$data['type'] = 5;
							break;
						case "png":
							$data['type'] = 6;
							break;
						case "jpeg":
							$data['type'] = 7;
							break;
						case "docx":
							$data['type'] = 8;
							break;
						case "pdf":
							$data['type'] = 9;
							break;
						case "rar":
							$data['type'] = 10;
							break;
						case "zip":
							$data['type'] = 11;
							break;
						case "xls":
							$data['type'] = 12;
							break;
						default:
							$data['type'] = 0;
					}
					// 检验数据
					$data = $this->FileManage->create($data, 1); //1是插入操作，0是更新操作
					$result = $this->FileManage->add($data);
					if ($result){
						$data['id'] = $result;
						$data['download_url'] = U('Download/download','id='.$data['id']);
						$data['name'] = mb_substr($info['Filedata']['name'], 0, 4, 'utf-8');//保存名称
						$data['full_name'] = $info['Filedata']['name'];
						$data['add_time'] = substr(date('Y/m/d',$data['create_time']),2);
						$data['savepath']=$config['rootPath'].$info['Filedata']['savepath'];//保存路径
						$dataResult['data'] = $data;
					} else {
						//删除已经上传的文件
						delDirAndFile($data['address']);
						$dataResult['flag'] = 0; //默认为1表示无任何错误
						$dataResult['msg'] = '文件上传失败！'; //ajax提示信息
						$dataResult['data'] = '';
					}
				}
			}else{
				$dataResult['flag'] = 0; //默认为1表示无任何错误
				$dataResult['msg'] = '参数错误！'; //ajax提示信息
				$dataResult['data'] = '';
			}	
		}else{
			$dataResult['flag'] = 0; //默认为1表示无任何错误
			$dataResult['msg'] = '请求非法！'; //ajax提示信息
			$dataResult['data'] = '';
		}
		// fwrites(APP_PATH . 'Home/ajax.txt',$dataResult);
		$this->ajaxReturn($dataResult,'JSON');
		
		
    	
    }

    //删除图片
    public function del(){
    	if (!IS_AJAX) {    		
			exit('需要AJAX提交信息');
    	}
    	$img_url=I('post.url');        
        $len=strlen(__ROOT__);
        $img_url=substr($img_url, $len);
        
        //获取缩略图地址        
        $tb_img_url=get_tb_img_url($img_url);
        // echo $tb_img_url;die;
    	if (@unlink($img_url) && @unlink($tb_img_url)) {
    		$data=array(
    			'status'=>1,
    			'info'=>':)成功删除图片',
			);
    	}else{
    		$data=array(
    			'status'=>0,
    			'info'=>':(删除图片失败',
			);
    	}
    	$this->ajaxReturn($data);
    }

    //修改名称
    public function changeName(){
    	if (!IS_AJAX) {    		
			exit('需要AJAX提交信息');
    	}

    	$old_url=I('post.url');                 //原来的地址
        $len=strlen(__ROOT__);
        $old_url=substr($old_url, $len);    //除去前缀,改成 ./Uploads/...
    	$name=I('post.name');                   //新的名称不包含后缀
        $info=pathinfo($old_url);               //获取图片的信息        
        $path=$info['dirname'].'/';             //目录信息
        $ext=$info['extension'];   	            //后缀信息
    	$new_url=$path.$name.'.'.$ext;    	    //生成新的图片地址信息
        
        $old_tb=get_tb_img_url($old_url);
        $new_tb=get_tb_img_url($new_url);
        //检查是否有同名文件;目标文件是否存在
        if(!file_exists($old_url)){
            $data=array(
                'status'=>0,
                'info'=>'原文件不存在!',
            );
        }elseif(file_exists($new_url)){
            $data=array(
                'status'=>0,
                'info'=>'新的命名已存在,起冲突,请改换别名!',
            );
        }else{
            $res=@rename($old_url, $new_url);//修改名称
            $res_tb=@rename($old_tb,$new_tb);//修改缩略图名称
            if ($res) {
                $data=array(
                    'status'=>1,
                    'info'=>':)成功修改图片标题',
                    'savepath'=>$new_url,
                    'savename'=>$name,
                );
            }else{
                $data=array(
                    'status'=>0,
                    'info'=>':(修改失败',
                );
            }
        }
    	
    	$this->ajaxReturn($data);
    }
	
	/**
      +----------------------------------------------------------
     * ajax发布公司评论
      +----------------------------------------------------------
     */
	public function ajaxComment(){
		$dataResult = array();
		$dataResult['flag'] = 1; //默认为1表示无任何错误
		$dataResult['msg'] = '发布成功！'; //ajax提示信息
		$dataResult['data'] = ''; //返回数据、修改成功则返回、修改后的数据
		fwrites(APP_PATH . 'Home/ajax.txt',"@param(url)---ajaxComment start!");
		//判断是否是ajax请求
		if(IS_AJAX){
			//判断前台用户是否已经登录
			$uid = 0;	
			$username = '';
			$email = '';
			$site = '';
			$correlation_id = isset($_POST['correlation_id']) ? htmlspecialchars($_POST['correlation_id']) : 0 ;
			$content = isset($_POST['comment_content']) ? htmlspecialchars($_POST['comment_content']) : '' ;
			$edit_type = isset($_POST['c_edit_id']) ? intval(htmlspecialchars($_POST['c_edit_id'])) : 0 ;

			// fwrites(APP_PATH . 'Home/ajax.txt',$_POST);
			// fwrites(APP_PATH . 'Home/ajax.txt','检测是否是编辑模式------------------>$edit_type:'.$edit_type);
			// fwrites(APP_PATH . 'Home/ajax.txt',$edit_type);
			//开始reply后台数据验证
			$user_info = $this->getUserInfo();
			// fwrites(APP_PATH . 'Home/ajax.txt','user_info---------------------->');
			// fwrites(APP_PATH . 'Home/ajax.txt',$user_info);
			if($user_info && !empty($user_info)){
				if(!$content){
					$dataResult['flag'] = 0; //默认为1表示无任何错误
					$dataResult['msg'] = '评论内容不能为空!'; //ajax提示信息
					$dataResult['data'] = 'err_content';
					$this->ajaxReturn($dataResult,'JSON');
				}
				$uid = intval($user_info['aid']);
				$username = $user_info['username'];
				$email = $user_info['email'];
				$site = $user_info['personal_website'];
			}else{				
				$dataResult['flag'] = 0; //默认为1表示无任何错误
				$dataResult['msg'] = '请刷新重新登录!'; //ajax提示信息
				$dataResult['data'] = 'err_login';
				$this->ajaxReturn($dataResult,'JSON');
			}
			$id = $correlation_id+0;
			$oneCompany = $this->Company->where("cid = $id")->find();
			// fwrites(APP_PATH . 'Home/ajax.txt','oneCompany---------------------->');
			// fwrites(APP_PATH . 'Home/ajax.txt',$oneCompany);
			if(!$id || empty($oneCompany)){	
				$dataResult['flag'] = 0; //默认为1表示无任何错误
				$dataResult['msg'] = '找不到该公司信息!'; //ajax提示信息
				$dataResult['data'] = 'redirect_index';
			}

			$data = array();
			if($edit_type){
				//编辑模式
				$data=array(
					'comment_edit_date'=>time(),
					'comment_content'=>$content,
					'comment_agent'=>isset( $_SERVER['HTTP_USER_AGENT'] ) ? substr( $_SERVER['HTTP_USER_AGENT'], 0, 254 ) : '',
					'comment_author_ip'=>get_client_ip(),
				);

			}else{
				//新增模式
				$data=array(
					'aid'=>0,
					'uid'=>intval($user_info['aid']),
					'type'=>1,
					'correlation_id'=>$correlation_id,
					'title'=>$oneCompany['name'],
					'comment_author_name'=>$username,
					'comment_author_email'=>$email,
					'comment_author_avatar'=>$user_info['avatar'],
					'comment_author_url'=>$site,
					'comment_add_date'=>time(),
					'comment_edit_date'=>time(),
					'comment_content'=>$content,
					'comment_top_parent'=>intval(htmlspecialchars($_POST['c_best_parent'])),
					'comment_parent'=>intval(htmlspecialchars($_POST['comment_parent'])),
					'comment_agent'=>isset( $_SERVER['HTTP_USER_AGENT'] ) ? substr( $_SERVER['HTTP_USER_AGENT'], 0, 254 ) : '',
					'comment_type'=>0,
					'comment_author_ip'=>get_client_ip(),
				);
				if(!$data['comment_top_parent'] && $data['comment_parent']){
					$data['comment_top_parent'] = $data['comment_parent'];
				}
			}
			//开始数据库验证
			$mod = M();
			if(!$edit_type){
				//增加：非编辑模式下检查是否是重复评论
				if($uid){
					$sql_for_repeat = "SELECT cid FROM $this->t_n_comments WHERE correlation_id = $id AND type = 1 AND uid = $uid AND comment_content = '" . $data['comment_content'] . "' LIMIT 1";
				}else{
					$sql_for_repeat = "SELECT cid FROM $this->t_n_comments WHERE correlation_id = $id AND type = 1 AND comment_author_name = '". $username ."' AND comment_author_email = '$email' AND comment_content = '" . $data['comment_content'] . "' LIMIT 1";
				}
				
				//fwrites(APP_PATH.'Home/ajax.txt','@param(ajaxComment)---sql_for_repeat:'.$sql_for_repeat);

				$cid = $mod->query($sql_for_repeat);	
				if($cid){
					$dataResult['flag'] = 0; //默认为1表示无任何错误
					$dataResult['msg'] = '看起来这条评论你以前说过!'; //ajax提示信息
					$dataResult['data'] = 'err_repeat';
					$this->ajaxReturn($dataResult,'JSON');
				}
			}
			//增加：检查是否评论过快
			$interval_time = intval(CONFIG_A_REPLY_INTERVAL);	
			if($interval_time){
				if($uid){
					$sql_for_quick = "SELECT comment_add_date FROM $this->t_n_comments WHERE correlation_id = $id AND type = 1 AND uid = $uid LIMIT 1";
				}else{
					$sql_for_quick = "SELECT comment_add_date FROM $this->t_n_comments WHERE correlation_id = $id AND type = 1 AND comment_author_name = $username AND comment_author_email = '$email' ORDER BY comment_add_date DESC  LIMIT 1";
				}
				$add_time = $mod->query($sql_for_quick);
				if($add_time){
					if(is_array($add_time) && count($add_time)){
						$int_time = $add_time['0']['comment_add_date'];
					}else{
						$int_time = $add_time;
					}
					//读取配置最快回复间隔时间
					//$Config['a_reply_interval'];
					if((time()-$int_time) < $interval_time){
						$dataResult['flag'] = 0; //默认为1表示无任何错误
						$dataResult['msg'] = '评论过快!服务器快要崩溃呐！'; //ajax提示信息
						$dataResult['data'] = 'err_quick';
						$this->ajaxReturn($dataResult,'JSON');
					}
				}
			}

			if($edit_type){
				//编辑模式
				$new_cid = $this->Comments->where("cid=".$edit_type)->save($data);
				//fwrites(APP_PATH . 'home/ajax.txt','------------------>这事编辑模式');
				//fwrites(APP_PATH . 'home/ajax.txt',$new_cid);
			}else{
				//新增模式
				$new_cid = $this->Comments->data($data)->add();
			}

			//fwrites(APP_PATH . 'Home/ajax.txt','看看是否添加新评论成功------------->new_cid');
			//fwrites(APP_PATH . 'Home/ajax.txt',$new_cid);
			if($new_cid){
				$return_data = array();
				if($edit_type){
					$c_data = $this->Comments->getInfoById($edit_type);
					$return_data = 	count($c_data) > 0 ? $c_data[0] : '' ;
					$return_data['update_cid'] = $edit_type;
				}else{
					$c_data = $this->Comments->getInfoById($new_cid);
					$return_data = 	count($c_data) > 0 ? $c_data[0] : '' ;
				}
				if($user_info && !empty($user_info)){
					Vendor('extend');	
				}
				$return_data['comment_add_date'] = date('Y-m-d H:i:s',$return_data['comment_add_date']);
				$return_data['comment_edit_date'] = date('Y-m-d H:i:s',$return_data['comment_edit_date']);
				$return_data['comment_content'] = convert_smilie($return_data['comment_content']);
				if($return_data['comment_top_parent']){
					$p_c_author_name = isset($_POST['comment_parent_author_name']) ? htmlspecialchars($_POST['comment_parent_author_name']) : '' ;
					if($return_data['comment_author_name'] == $p_c_author_name){
						$return_data['sub_marking'] = "<a><b>".$return_data['comment_author_name']."&nbsp;&nbsp;</b></a><font color='orange'>(补充)</font><a><b>:</b></a>";
					}else{
						$return_data['sub_marking'] = "<a><b>".$return_data['comment_author_name']."</b></a><span>&nbsp;&nbsp;回复&nbsp;&nbsp;</span><a><b>".$p_c_author_name."&nbsp;:</b></a>";
					}
				}else{
					$return_data['sub_marking'] = '这个人很懒,他什么都没有留下...(顶级评论无作用)';	
				}

				//fwrites(APP_PATH . 'Home/ajax.txt','评论成功、开始返回最新数据------------->$return_data');
				//fwrites(APP_PATH . 'Home/ajax.txt',$return_data);
				$dataResult['data'] = $return_data;
				$this->ajaxReturn($dataResult,'JSON');

			}else{
				//fwrites(APP_PATH . 'Home/ajax.txt','评论失败------------->c_data');
				$dataResult['flag'] = 0; //默认为1表示无任何错误
				$dataResult['msg'] = '提交失败！请稍后重新提交！'; //ajax提示信息
				$dataResult['data'] = 'err_server';
				$this->ajaxReturn($dataResult,'JSON');
			}
			
			$dataResult['flag'] = 0; //默认为1表示无任何错误
			$dataResult['msg'] = '提交失败！请稍后重新提交！'; //ajax提示信息
			$dataResult['data'] = 'err_server';
			$this->ajaxReturn($dataResult,'JSON');
		}else{
			$dataResult['flag'] = 0; //默认为1表示无任何错误
			$dataResult['msg'] = '提交失败！请稍后重新提交！'; //ajax提示信息
			$dataResult['data'] = 'err_server';
			$this->ajaxReturn($dataResult,'JSON');
		}
	}
	
}
?>