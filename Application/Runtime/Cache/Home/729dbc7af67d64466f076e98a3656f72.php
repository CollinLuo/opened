<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"  lang="zh-CN">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<title><?php echo ($web_name); ?>-<?php echo ($description); ?></title>
		<meta name="keywords" content="<?php echo ($keywords); ?>"/>
		<meta name="description" content="<?php echo ($seo_description); ?>" />
		<meta name="baidu-site-verification" content="" />
		<!--css-->
		<link rel='stylesheet' id='twentytwelve-style-css'  href='/opened/Public/Css/Home/style.css' type='text/css' media='all' />
		<!-- js -->
		<!-- jQuery -->
		<script type="text/javascript" src="/opened/Public/Js/Common/jquery-1.9.0.min.js"></script>
		<script type="text/javascript" src="/opened/Public/Js/Common/jquery.bowser.js"></script>
		<script type="text/javascript" src="/opened/Public/Js/Common/ichart.1.2.min.js"></script>
		<script type="text/javascript" src="/opened/Public/Js/Home/article.js"></script>
		<script type="text/javascript" src="/opened/Public/Js/Home/comments-ajax.js"></script>
		<!--<script type="text/javascript" src="/opened/Public/Js/Common/jquery.jqChart.min.js"></script>-->
		<script type="text/javascript" src="/opened/Public/Js/Common/jqbar.js"></script>
		<!-- msdropdown -->
		<link rel="stylesheet" type="text/css" href="/opened/Public/Js/Common/msdropdown/msdropdown-dd.css" />
		<script type="text/javascript" src="/opened/Public/Js/Common/msdropdown/jquery.dd.js"></script>
		<!-- webuploader -->
		<link rel="stylesheet" type="text/css" href="/opened/Public/Js/Common/webuploader/webuploader.css" />
		<script type="text/javascript" src="/opened/Public/Js/Common/webuploader/webuploader.html5only.js"></script>
		
		<!-- 引入uploadify样式 -->
		<link rel="stylesheet" type="text/css" href="/opened/Public/Js/Common/uploadify/uploadify.css">
		<!-- 引入uploadify的js代码 -->
		<script src="/opened/Public/Js/Common/uploadify/jquery.uploadify.min.js"></script>
	</head>
	<body class="home blog custom-background custom-font-enabled single-author">
		<div id="page" class="container">
			<div class="c_index_header">
				<div class="ci_info">
					<div class="ci_i_name">
						<img class="ci_in_img" src="/opened/Uploads/Image/20160407/570619ba7e015.png" height="30px" width="30px" alt="" />
						<span class="ci_in_text"><?php echo ($info["name"]); ?></span>
					</div>
					<div class="ci_i_intro">
						<!----><div class="ci_ii_text"><?php echo ($info["remark"]); ?></div>
						<!--<div class="ci_ii_text"><textarea class="intro_edit_box" name="company_desc">股份制企业；位于：上海市青浦区连城路234号；电话021-34689234；主营警用品开发和销售；www.bize.com</textarea></div>-->
						<div class="ci_ii_btn">
							<a class="ci_iib_btn" href="javascript:void(0);" onclick="toggleEditDesc($(this))">编辑</a>
							<input type="hidden" name="check_flag" value="0" />
						</div>
					</div>
				</div>
				<div class="ci_user_list">
					<div class="ci_ua_list">
						<ul>
							<?php if(is_array($info['party_a'])): $i = 0; $__LIST__ = $info['party_a'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$partya): $mod = ($i % 2 );++$i;?><li>
								<div class="ci_avatar_box">
									<img src="/opened/Uploads/Common/avatar_big/<?php echo ($partya["avatar"]); ?>" width="50px" height="50px" alt="" />
									<div class="ci_avatar_intro">
										<div class="avatar_intro_text"><?php echo (mb_substr($partya["company_name"],0,4,'utf-8')); ?></div><div class="avatar_intro_text"><?php echo (mb_substr($partya["username"],0,8,'utf-8')); ?></div>
									</div>
								</div>
							</li><?php endforeach; endif; else: echo "" ;endif; ?>
							
							<li>
								<div class="ci_btn_box">
									<div class="ci_bb_btn">
										<a href="javascript:void(0);" onclick="showAddInput($(this),1);"><img src="/opened/Public/Img/Common/btn_user_add.png" width="29px" height="29px" alt="新增" /></a>
									</div>
								</div>
							</li>
							<li>
								<div class="ci_btn_box">
									<div class="ci_bb_btn">
										<a href="javascript:void(0);" onclick="showReduceInput($(this),1);"><img src="/opened/Public/Img/Common/btn_user_reduce.png" width="29px" height="29px" alt="删除" /></a>
									</div>
								</div>
							</li>
						</ul>
					</div>
					<div class="ci_ub_list">
						<ul>
							<?php if(is_array($info['party_b'])): $i = 0; $__LIST__ = $info['party_b'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$partyb): $mod = ($i % 2 );++$i;?><li>
								<div class="ci_avatar_box">
									<img src="/opened/Uploads/Common/avatar_big/<?php echo ($partyb["avatar"]); ?>" width="50px" height="50px" alt="" />
									<div class="ci_avatar_intro">
										<div class="avatar_intro_text"><?php echo (mb_substr($partyb["company_name"],0,4,'utf-8')); ?></div><div class="avatar_intro_text"><?php echo (mb_substr($partyb["username"],0,8,'utf-8')); ?></div>
									</div>
								</div>
							</li><?php endforeach; endif; else: echo "" ;endif; ?>
							
							<li>
								<div class="ci_btn_box">
									<div class="ci_bb_btn">
										<a href="javascript:void(0);" onclick="showAddInput($(this),2);"><img src="/opened/Public/Img/Common/btn_user_add.png" width="29px" height="29px" alt="新增" /></a>
									</div>
								</div>
							</li>
							<li>
								<div class="ci_btn_box">
									<div class="ci_bb_btn">
										<a href="javascript:void(0);" onclick="showReduceInput($(this),2);"><img src="/opened/Public/Img/Common/btn_user_reduce.png" width="29px" height="29px" alt="删除" /></a>
									</div>
								</div>
							</li>
						</ul>
					</div>
				</div>
				<div class="clear"></div>
			</div>
			<div class="c_index_middle">
				<div></div>
				<div class="cim_line_box">
					<div class="cim_line_box_header">
						<div class="cim_lh_one"><span class="cim_lh_num">79603.00</span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span class="cim_lh_btn"><a href="javascript:void;">详细</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="javascript:void;">充值</a></span></div>
						<div class="cim_lh_two"><span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;355100.00</span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span class="cim_lt_one">&nbsp;&nbsp;时间&nbsp;&nbsp;</span><span class="cim_lt_two">&nbsp;&nbsp;项目&nbsp;&nbsp;</span></div>
					</div>
					<div class="cim_line_box_content" id="canvasDiv"></div>
				</div>
				<div class="cim_histogram_box">
					<table width="900px" align="center" style="border-collapse:collapse;margin:auto;">
						<tr align="center" valign="center">
							<td width="15%" class="td_mid">
								<div class="cim_h_text">视觉识别系统修改</div>
								<div class="cim_h_time">16/03/05-16/07/11</div>
							</td>
							<td width="75%"  class="td_mid">
								<div id="cim_h_line_one"></div>
							</td>
							<td width="10%" class="td_mid">
								<div class="cim_h_money">19603.00</div>
							</td>
						</tr>
						<tr align="center" valign="center">
							<td width="15%" class="td_mid">
								<div class="cim_h_text">产品部官网建设</div>
								<div class="cim_h_time">16/03/05-16/05/11</div>
							</td>
							<td width="75%"  class="td_mid">
								<div id="cim_h_line_two"></div>
							</td>
							<td width="10%" class="td_mid">
								<div class="cim_h_money">7603.00</div>
							</td>
						</tr>
						<tr align="center" valign="center">
							<td width="15%" class="td_mid">
								<div class="cim_h_text">公司简介拍摄</div>
								<div class="cim_h_time">16/03/05-16/07/11</div>
							</td>
							<td width="75%"  class="td_mid">
								<div id="cim_h_line_three"></div>
							</td>
							<td width="10%" class="td_mid">
								<div class="cim_h_money">1603.00</div>
							</td>
						</tr>
					</table>
				</div>
				<div class="cim_project_box">
					<table width="900px" align="center" style="border-collapse:collapse;">
						<tr align="center" valign="bottom" style="border-bottom:4px solid #cecece;">
							<th align="center" width="20%"></th>
							<th align="center" width="15%">管理</th>
							<th align="center" width="15%">审批</th>
							<th align="center" width="3%"></th>
							<th align="center" width="13%">服务花费</th>
							<th align="center" width="13%">已经花费</th>
							<th align="center" style="text-align:left;" width="31%">满意度</th>
						</tr>
						<tr class="cim_pt_tr" valign="middle" align="center">
							<td align="center" class=""><span class="cim_p_text">产品部官网建设</span><br /><span class="cim_p_timerange">16/03/05-16/05/01</span></td>
							<td align="center"  valign="middle" class="cim_pt_td" style="display:table-cell; vertical-align:middle;margin:auto;">
								<select name="manager_select" class="cim_pt_select" onchange="showValue(this.value)">
									<option value="" data-description="amy" data-image="/opened/Uploads/Common/avatar_big/user100_1056.jpg"><span class="cm_pts_text">志强科技</span></option>	
									<option value="" data-description="小吴" data-image="/opened/Uploads/Common/avatar_big/user100_1056.jpg"><span class="cm_pts_text">志强科技</span></option>	
								</select>
							</td>
							<td align="center" valign="middle" class="cim_pt_td" style="display:table-cell; vertical-align:middle">
								<select class="cim_pt_select">
									<option value="" data-description="amy" data-image="/opened/Uploads/Common/avatar_big/user100_1056.jpg"><span class="cm_pts_text">志强科技</span></option>	
								</select>
							</td>
							<td align="center" valign="middle"><img src="/opened/Public/Img/Common/line_two.png" width="2px" height="38px"></td>
							<td class="cim_pt_scost" align="center" valign="middle" style="display:table-cell; vertical-align:middle"><img src="/opened/Uploads/Common/avatar_big/user100_1056.jpg" width="20px" height="20px" /><span>刘丽</span></td>
							<td align="center" valign="middle" style="display:table-cell;vertical-align:middle"><span>346.00</span></td>
							<td align="center" valign="middle" style="display:table-cell;vertical-align:middle;text-align:left;"><span>78</span></td>
						</tr>
						<tr class="cim_pt_tr" valign="middle" align="center">
							<td align="center" class=""><span class="cim_p_text">公司简介拍摄</span><br /><span class="cim_p_timerange">16/03/05-16/07/11</span></td>
							<td align="center"  valign="middle" class="cim_pt_td" style="display:table-cell; vertical-align:middle">
								<select name="manager_select" class="cim_pt_select" onchange="showValue(this.value)">
									<option value="" data-description="amy" data-image="/opened/Uploads/Common/avatar_big/user100_1041.jpg"><span class="cm_pts_text">志强科技</span></option>	
								</select>
							</td>
							<td align="center" valign="middle" class="cim_pt_td" style="display:table-cell; vertical-align:middle">
								<select class="cim_pt_select">
									<option value="" data-description="amy" data-image="/opened/Uploads/Common/avatar_big/user100_1041.jpg"><span class="cm_pts_text">志强科技</span></option>	
								</select>
							</td>
							
							<td align="center" valign="middle"><img src="/opened/Public/Img/Common/line_two.png" width="2px" height="38px"></td>
							<td class="cim_pt_scost" align="center" valign="middle" style="display:table-cell; vertical-align:middle"><img src="/opened/Uploads/Common/avatar_big/user100_1041.jpg" width="20px" height="20px" /><span>小张</span></td>
							<td align="center" valign="middle" style="display:table-cell;vertical-align:middle"><span>3867.00</span></td>
							<td align="left" valign="middle" style="display:table-cell;vertical-align:middle;text-align:left;"><span>96</span></td>
						</tr>

					</table>
				</div>
				<div class="clear"></div>
			</div>
			<div class="c_index_footer">
				<div class="ci_p_list">
					<ul>
						<li href="#file_box_1" pid="1">
							<div default_sign='0' class="file_img_box ci_pl_box">
								<img src="/opened/Uploads/Image/20160131/56adf3ab473c7.jpg" width="70px" height="70px" />
								<div class="ci_pl_intro">
									<span class="ci_pli_text">视觉识别系统</span><br /><span class="ci_pli_time">16/03/05-16/07/11</span>
								</div>
							</div>
						</li>
						<li href="#file_box_2" pid="2">
							<div  default_sign='1' class="file_img_box ci_pl_box_checked">
								<img src="/opened/Uploads/Image/20160131/56ae070e32c85.jpg" width="70px" height="70px" />
								<div class="ci_pl_intro">
									<span class="ci_pli_text">产品部官网建设</span><br /><span class="ci_pli_time">16/03/05-16/05/11</span>
								</div>
							</div>
							<div class="ci_checked_btn">
								<a href="javascript:void(0);">删除</a><span class="ci_checked_btn_text">/</span><a href="javascript:void(0);" onclick="showUploadBox($(this))">上传</a>
							</div>
						</li>
						<li class="clear"></li>
					</ul>
				</div>
				<div class="hide">
					<input type="file" id="fileselect" class="hide" class="form-control" name="fileselect[]" multiple="multiple"/>
					<input type="hide" id="upload_object_id" name="pid" value="2" />
				</div>
				<div class="ci_p_file_box">
					<form action="<?php echo U('Company/index');?>" id="file_form" method="post" enctype="multipart/form-data" onsubmit="return false">
					<div class="ci_pf_list">
						<table class="ci_pfl_table" width="900px" align="center" style="border-collapse:collapse;overflow:hidden;margin:auto;flow:left;">
							<tr id="file_box_1" class="file_box td_mid" width="900px" align="center" valign='middle'>
								<td width="90px" class="td_mid">
									<div class="ci_pfo_left">
										<span class="ci_pfo_text">视觉识别系统</span>
									</div>
								</td>
								<td width="810px" class="td_mid td_bg_one">
									<div class="ci_pfo_right">
										<ul>
											<li>
												<div class="ci_pfr_info">
													<?php switch($info["type"]): case "1": ?><a href="<?php echo U('Download/download','id='.$info['id']);?>"><img id="address_img" src="/opened/Public/Img/Common/file_icon_big/iconfont-doc.png" alt="点击下载该文件" /></a><?php break;?>
													<?php case "2": ?><a href="<?php echo U('Download/download','id='.$info['id']);?>"><img id="address_img" src="/opened/Public/Img/Common/file_icon_big/iconfont-xlsx.png" alt="点击下载该文件" /></a><?php break;?>
													<?php case "3": ?><a href="<?php echo U('Download/download','id='.$info['id']);?>"><img id="address_img" src="/opened/Public/Img/Common/file_icon_big/iconfont-txt.png" alt="点击下载该文件" /></a><?php break;?>
													<?php case "4": ?><a href="<?php echo U('Download/download','id='.$info['id']);?>"><img id="address_img" src="/opened/Public/Img/Common/file_icon_big/iconfont-filejpg.png" alt="点击下载该文件" /></a><?php break;?>
													<?php case "5": ?><a href="<?php echo U('Download/download','id='.$info['id']);?>"><img id="address_img" src="/opened/Public/Img/Common/file_icon_big/iconfont-filegif.png" alt="点击下载该文件" /></a><?php break;?>
													<?php case "6": ?><a href="<?php echo U('Download/download','id='.$info['id']);?>"><img id="address_img" src="/opened/Public/Img/Common/file_icon_big/iconfont-filepng.png" alt="点击下载该文件" /></a><?php break;?>
													<?php case "7": ?><a href="<?php echo U('Download/download','id='.$info['id']);?>"><img id="address_img" src="/opened/Public/Img/Common/file_icon_big/iconfont-filejpeg.png" alt="点击下载该文件" /></a><?php break;?>
													<?php case "8": ?><a href="<?php echo U('Download/download','id='.$info['id']);?>"><img id="address_img" src="/opened/Public/Img/Common/file_icon_big/iconfont-docx.png" alt="点击下载该文件" /></a><?php break;?>
													<?php case "9": ?><a href="<?php echo U('Download/download','id='.$info['id']);?>"><img id="address_img" src="/opened/Public/Img/Common/file_icon_big/iconfont-pdf.png" alt="点击下载该文件" /></a><?php break;?>
													<?php case "10": ?><a href="<?php echo U('Download/download','id='.$info['id']);?>"><img id="address_img" src="/opened/Public/Img/Common/file_icon_big/iconfont-rar.png" alt="点击下载该文件" /></a><?php break;?>
													<?php case "11": ?><a href="<?php echo U('Download/download','id='.$info['id']);?>"><img id="address_img" src="/opened/Public/Img/Common/file_icon_big/iconfont-zip.png" alt="点击下载该文件" /></a><?php break;?>
													<?php case "12": ?><a href="<?php echo U('Download/download','id='.$info['id']);?>"><img id="address_img" src="/opened/Public/Img/Common/file_icon_big/iconfont-xls.png" alt="点击下载该文件" /></a><?php break;?>
													<?php default: ?><a href="<?php echo U('Download/download','id='.$info['id']);?>"><img id="address_img" src="/opened/Public/Img/Common/file_icon_big/iconfont-weizhi.png" alt="点击下载该文件" /></a><?php endswitch;?>
													<span class="ci_pfr_text">Demo</span><span class="ci_pfr_time">12/07/16</span>
												</div>
												<div class="ci_pfr_check"><input type="checkbox" name="file_id" value="1" /></div>
											</li>
											<li>
												<div class="ci_pfr_info">
													<?php switch($info["type"]): case "1": ?><a href="<?php echo U('Download/download','id='.$info['id']);?>"><img id="address_img" src="/opened/Public/Img/Common/file_icon_big/iconfont-doc.png" alt="点击下载该文件" /></a><?php break;?>
													<?php case "2": ?><a href="<?php echo U('Download/download','id='.$info['id']);?>"><img id="address_img" src="/opened/Public/Img/Common/file_icon_big/iconfont-xlsx.png" alt="点击下载该文件" /></a><?php break;?>
													<?php case "3": ?><a href="<?php echo U('Download/download','id='.$info['id']);?>"><img id="address_img" src="/opened/Public/Img/Common/file_icon_big/iconfont-txt.png" alt="点击下载该文件" /></a><?php break;?>
													<?php case "4": ?><a href="<?php echo U('Download/download','id='.$info['id']);?>"><img id="address_img" src="/opened/Public/Img/Common/file_icon_big/iconfont-filejpg.png" alt="点击下载该文件" /></a><?php break;?>
													<?php case "5": ?><a href="<?php echo U('Download/download','id='.$info['id']);?>"><img id="address_img" src="/opened/Public/Img/Common/file_icon_big/iconfont-filegif.png" alt="点击下载该文件" /></a><?php break;?>
													<?php case "6": ?><a href="<?php echo U('Download/download','id='.$info['id']);?>"><img id="address_img" src="/opened/Public/Img/Common/file_icon_big/iconfont-filepng.png" alt="点击下载该文件" /></a><?php break;?>
													<?php case "7": ?><a href="<?php echo U('Download/download','id='.$info['id']);?>"><img id="address_img" src="/opened/Public/Img/Common/file_icon_big/iconfont-wbdiconsoftjpeg.png" alt="点击下载该文件" /></a><?php break;?>
													<?php case "8": ?><a href="<?php echo U('Download/download','id='.$info['id']);?>"><img id="address_img" src="/opened/Public/Img/Common/file_icon_big/iconfont-docx.png" alt="点击下载该文件" /></a><?php break;?>
													<?php case "9": ?><a href="<?php echo U('Download/download','id='.$info['id']);?>"><img id="address_img" src="/opened/Public/Img/Common/file_icon_big/iconfont-pdf.png" alt="点击下载该文件" /></a><?php break;?>
													<?php case "10": ?><a href="<?php echo U('Download/download','id='.$info['id']);?>"><img id="address_img" src="/opened/Public/Img/Common/file_icon_big/iconfont-rar.png" alt="点击下载该文件" /></a><?php break;?>
													<?php case "11": ?><a href="<?php echo U('Download/download','id='.$info['id']);?>"><img id="address_img" src="/opened/Public/Img/Common/file_icon_big/iconfont-zip.png" alt="点击下载该文件" /></a><?php break;?>
													<?php case "12": ?><a href="<?php echo U('Download/download','id='.$info['id']);?>"><img id="address_img" src="/opened/Public/Img/Common/file_icon_big/iconfont-xls.png" alt="点击下载该文件" /></a><?php break;?>
													<?php default: ?><a href="<?php echo U('Download/download','id='.$info['id']);?>"><img id="address_img" src="/opened/Public/Img/Common/file_icon_big/iconfont-weizhi.png" alt="点击下载该文件" /></a><?php endswitch;?>
													<span class="ci_pfr_text">会议纪要</span><span class="ci_pfr_time">12/07/16</span>
												</div>
												<div class="ci_pfr_check"><input type="checkbox" name="file_id" value="1" /></div>
											</li>
											<li>
												<div class="ci_pfr_info">
													<?php switch($info["type"]): case "1": ?><a href="<?php echo U('Download/download','id='.$info['id']);?>"><img id="address_img" src="/opened/Public/Img/Common/file_icon_big/iconfont-doc.png" alt="点击下载该文件" /></a><?php break;?>
													<?php case "2": ?><a href="<?php echo U('Download/download','id='.$info['id']);?>"><img id="address_img" src="/opened/Public/Img/Common/file_icon_big/iconfont-xls.png" alt="点击下载该文件" /></a><?php break;?>
													<?php case "3": ?><a href="<?php echo U('Download/download','id='.$info['id']);?>"><img id="address_img" src="/opened/Public/Img/Common/file_icon_big/iconfont-txt.png" alt="点击下载该文件" /></a><?php break;?>
													<?php case "4": ?><a href="<?php echo U('Download/download','id='.$info['id']);?>"><img id="address_img" src="/opened/Public/Img/Common/file_icon_big/iconfont-filejpg.png" alt="点击下载该文件" /></a><?php break;?>
													<?php case "5": ?><a href="<?php echo U('Download/download','id='.$info['id']);?>"><img id="address_img" src="/opened/Public/Img/Common/file_icon_big/iconfont-filegif.png" alt="点击下载该文件" /></a><?php break;?>
													<?php case "6": ?><a href="<?php echo U('Download/download','id='.$info['id']);?>"><img id="address_img" src="/opened/Public/Img/Common/file_icon_big/iconfont-filepng.png" alt="点击下载该文件" /></a><?php break;?>
													<?php case "7": ?><a href="<?php echo U('Download/download','id='.$info['id']);?>"><img id="address_img" src="/opened/Public/Img/Common/file_icon_big/iconfont-wbdiconsoftjpeg.png" alt="点击下载该文件" /></a><?php break;?>
													<?php case "8": ?><a href="<?php echo U('Download/download','id='.$info['id']);?>"><img id="address_img" src="/opened/Public/Img/Common/file_icon_big/iconfont-docx.png" alt="点击下载该文件" /></a><?php break;?>
													<?php case "9": ?><a href="<?php echo U('Download/download','id='.$info['id']);?>"><img id="address_img" src="/opened/Public/Img/Common/file_icon_big/iconfont-pdf.png" alt="点击下载该文件" /></a><?php break;?>
													<?php case "10": ?><a href="<?php echo U('Download/download','id='.$info['id']);?>"><img id="address_img" src="/opened/Public/Img/Common/file_icon_big/iconfont-rar.png" alt="点击下载该文件" /></a><?php break;?>
													<?php case "11": ?><a href="<?php echo U('Download/download','id='.$info['id']);?>"><img id="address_img" src="/opened/Public/Img/Common/file_icon_big/iconfont-zip.png" alt="点击下载该文件" /></a><?php break;?>
													<?php case "12": ?><a href="<?php echo U('Download/download','id='.$info['id']);?>"><img id="address_img" src="/opened/Public/Img/Common/file_icon_big/iconfont-xls.png" alt="点击下载该文件" /></a><?php break;?>
													<?php default: ?><a href="<?php echo U('Download/download','id='.$info['id']);?>"><img id="address_img" src="/opened/Public/Img/Common/file_icon_big/iconfont-weizhi.png" alt="点击下载该文件" /></a><?php endswitch;?>
													<span class="ci_pfr_text">会议纪要</span><span class="ci_pfr_time">12/07/16</span>
												</div>
												<div class="ci_pfr_check"><input type="checkbox" name="file_id" value="1" /></div>
											</li>
											<li>
												<div class="ci_pfr_info">
													<?php switch($info["type"]): case "1": ?><a href="<?php echo U('Download/download','id='.$info['id']);?>"><img id="address_img" src="/opened/Public/Img/Common/file_icon_big/iconfont-doc.png" alt="点击下载该文件" /></a><?php break;?>
													<?php case "2": ?><a href="<?php echo U('Download/download','id='.$info['id']);?>"><img id="address_img" src="/opened/Public/Img/Common/file_icon_big/iconfont-xls.png" alt="点击下载该文件" /></a><?php break;?>
													<?php case "3": ?><a href="<?php echo U('Download/download','id='.$info['id']);?>"><img id="address_img" src="/opened/Public/Img/Common/file_icon_big/iconfont-txt.png" alt="点击下载该文件" /></a><?php break;?>
													<?php case "4": ?><a href="<?php echo U('Download/download','id='.$info['id']);?>"><img id="address_img" src="/opened/Public/Img/Common/file_icon_big/iconfont-filejpg.png" alt="点击下载该文件" /></a><?php break;?>
													<?php case "5": ?><a href="<?php echo U('Download/download','id='.$info['id']);?>"><img id="address_img" src="/opened/Public/Img/Common/file_icon_big/iconfont-filegif.png" alt="点击下载该文件" /></a><?php break;?>
													<?php case "6": ?><a href="<?php echo U('Download/download','id='.$info['id']);?>"><img id="address_img" src="/opened/Public/Img/Common/file_icon_big/iconfont-filepng.png" alt="点击下载该文件" /></a><?php break;?>
													<?php case "7": ?><a href="<?php echo U('Download/download','id='.$info['id']);?>"><img id="address_img" src="/opened/Public/Img/Common/file_icon_big/iconfont-wbdiconsoftjpeg.png" alt="点击下载该文件" /></a><?php break;?>
													<?php default: ?><a href="<?php echo U('Download/download','id='.$info['id']);?>"><img id="address_img" src="/opened/Public/Img/Common/file_icon_big/iconfont-weizhi.png" alt="点击下载该文件" /></a><?php endswitch;?>
													<span class="ci_pfr_text">报价表</span><span class="ci_pfr_time">12/07/19</span>
												</div>
												<div class="ci_pfr_check"><input type="checkbox" name="file_id" value="1" /></div>
											</li>
											<li>
												<div class="ci_pfr_info">
													<?php switch($info["type"]): case "1": ?><a href="<?php echo U('Download/download','id='.$info['id']);?>"><img id="address_img" src="/opened/Public/Img/Common/file_icon_big/iconfont-doc.png" alt="点击下载该文件" /></a><?php break;?>
													<?php case "2": ?><a href="<?php echo U('Download/download','id='.$info['id']);?>"><img id="address_img" src="/opened/Public/Img/Common/file_icon_big/iconfont-xls.png" alt="点击下载该文件" /></a><?php break;?>
													<?php case "3": ?><a href="<?php echo U('Download/download','id='.$info['id']);?>"><img id="address_img" src="/opened/Public/Img/Common/file_icon_big/iconfont-txt.png" alt="点击下载该文件" /></a><?php break;?>
													<?php case "4": ?><a href="<?php echo U('Download/download','id='.$info['id']);?>"><img id="address_img" src="/opened/Public/Img/Common/file_icon_big/iconfont-filejpg.png" alt="点击下载该文件" /></a><?php break;?>
													<?php case "5": ?><a href="<?php echo U('Download/download','id='.$info['id']);?>"><img id="address_img" src="/opened/Public/Img/Common/file_icon_big/iconfont-filegif.png" alt="点击下载该文件" /></a><?php break;?>
													<?php case "6": ?><a href="<?php echo U('Download/download','id='.$info['id']);?>"><img id="address_img" src="/opened/Public/Img/Common/file_icon_big/iconfont-filepng.png" alt="点击下载该文件" /></a><?php break;?>
													<?php case "7": ?><a href="<?php echo U('Download/download','id='.$info['id']);?>"><img id="address_img" src="/opened/Public/Img/Common/file_icon_big/iconfont-wbdiconsoftjpeg.png" alt="点击下载该文件" /></a><?php break;?>
													<?php default: ?><a href="<?php echo U('Download/download','id='.$info['id']);?>"><img id="address_img" src="/opened/Public/Img/Common/file_icon_big/iconfont-weizhi.png" alt="点击下载该文件" /></a><?php endswitch;?>
													<span class="ci_pfr_text">残阳广告公司简介</span><span class="ci_pfr_time">12/07/22</span>
												</div>
												<div class="ci_pfr_check"><input type="checkbox" name="file_id" value="1" /></div>
											</li>
											<li>
												<div class="ci_pfr_info">
													<?php switch($info["type"]): case "1": ?><a href="<?php echo U('Download/download','id='.$info['id']);?>"><img id="address_img" src="/opened/Public/Img/Common/file_icon_big/iconfont-doc.png" alt="点击下载该文件" /></a><?php break;?>
													<?php case "2": ?><a href="<?php echo U('Download/download','id='.$info['id']);?>"><img id="address_img" src="/opened/Public/Img/Common/file_icon_big/iconfont-xls.png" alt="点击下载该文件" /></a><?php break;?>
													<?php case "3": ?><a href="<?php echo U('Download/download','id='.$info['id']);?>"><img id="address_img" src="/opened/Public/Img/Common/file_icon_big/iconfont-txt.png" alt="点击下载该文件" /></a><?php break;?>
													<?php case "4": ?><a href="<?php echo U('Download/download','id='.$info['id']);?>"><img id="address_img" src="/opened/Public/Img/Common/file_icon_big/iconfont-filejpg.png" alt="点击下载该文件" /></a><?php break;?>
													<?php case "5": ?><a href="<?php echo U('Download/download','id='.$info['id']);?>"><img id="address_img" src="/opened/Public/Img/Common/file_icon_big/iconfont-filegif.png" alt="点击下载该文件" /></a><?php break;?>
													<?php case "6": ?><a href="<?php echo U('Download/download','id='.$info['id']);?>"><img id="address_img" src="/opened/Public/Img/Common/file_icon_big/iconfont-filepng.png" alt="点击下载该文件" /></a><?php break;?>
													<?php case "7": ?><a href="<?php echo U('Download/download','id='.$info['id']);?>"><img id="address_img" src="/opened/Public/Img/Common/file_icon_big/iconfont-wbdiconsoftjpeg.png" alt="点击下载该文件" /></a><?php break;?>
													<?php default: ?><a href="<?php echo U('Download/download','id='.$info['id']);?>"><img id="address_img" src="/opened/Public/Img/Common/file_icon_big/iconfont-weizhi.png" alt="点击下载该文件" /></a><?php endswitch;?>
													<span class="ci_pfr_text">供应商</span><span class="ci_pfr_time">12/07/16</span>
												</div>
												<div class="ci_pfr_check"><input type="checkbox" name="file_id" value="1" /></div>
											</li>
											<li>
												<div class="ci_pfr_info">
													<?php switch($info["type"]): case "1": ?><a href="<?php echo U('Download/download','id='.$info['id']);?>"><img id="address_img" src="/opened/Public/Img/Common/file_icon_big/iconfont-doc.png" alt="点击下载该文件" /></a><?php break;?>
													<?php case "2": ?><a href="<?php echo U('Download/download','id='.$info['id']);?>"><img id="address_img" src="/opened/Public/Img/Common/file_icon_big/iconfont-xls.png" alt="点击下载该文件" /></a><?php break;?>
													<?php case "3": ?><a href="<?php echo U('Download/download','id='.$info['id']);?>"><img id="address_img" src="/opened/Public/Img/Common/file_icon_big/iconfont-txt.png" alt="点击下载该文件" /></a><?php break;?>
													<?php case "4": ?><a href="<?php echo U('Download/download','id='.$info['id']);?>"><img id="address_img" src="/opened/Public/Img/Common/file_icon_big/iconfont-filejpg.png" alt="点击下载该文件" /></a><?php break;?>
													<?php case "5": ?><a href="<?php echo U('Download/download','id='.$info['id']);?>"><img id="address_img" src="/opened/Public/Img/Common/file_icon_big/iconfont-filegif.png" alt="点击下载该文件" /></a><?php break;?>
													<?php case "6": ?><a href="<?php echo U('Download/download','id='.$info['id']);?>"><img id="address_img" src="/opened/Public/Img/Common/file_icon_big/iconfont-filepng.png" alt="点击下载该文件" /></a><?php break;?>
													<?php case "7": ?><a href="<?php echo U('Download/download','id='.$info['id']);?>"><img id="address_img" src="/opened/Public/Img/Common/file_icon_big/iconfont-wbdiconsoftjpeg.png" alt="点击下载该文件" /></a><?php break;?>
													<?php default: ?><a href="<?php echo U('Download/download','id='.$info['id']);?>"><img id="address_img" src="/opened/Public/Img/Common/file_icon_big/iconfont-weizhi.png" alt="点击下载该文件" /></a><?php endswitch;?>
													<span class="ci_pfr_text">供应商</span><span class="ci_pfr_time">12/07/16</span>
												</div>
												<div class="ci_pfr_check"><input type="checkbox" name="file_id" value="1" /></div>
											</li>
										</ul>
									</div>
								</td>
							</tr>
							<tr id="file_box_2" class="file_box default_show_tr td_mid" width="900px" align="center" valign='middle'>
								<td width="90px" class="td_mid">
									<div class="ci_pfo_left">
										<span class="ci_pfo_text">产品部官网建设</span>
									</div>
								</td>
								<td width="810px" class="td_mid td_bg_one">
									<div class="ci_pfo_right">
										<ul>
											<li>
												<div class="ci_pfr_info">
													<?php switch($info["type"]): case "1": ?><a href="<?php echo U('Download/download','id='.$info['id']);?>"><img id="address_img" src="/opened/Public/Img/Common/file_icon_big/iconfont-doc.png" alt="点击下载该文件" /></a><?php break;?>
													<?php case "2": ?><a href="<?php echo U('Download/download','id='.$info['id']);?>"><img id="address_img" src="/opened/Public/Img/Common/file_icon_big/iconfont-xlsx.png" alt="点击下载该文件" /></a><?php break;?>
													<?php case "3": ?><a href="<?php echo U('Download/download','id='.$info['id']);?>"><img id="address_img" src="/opened/Public/Img/Common/file_icon_big/iconfont-txt.png" alt="点击下载该文件" /></a><?php break;?>
													<?php case "4": ?><a href="<?php echo U('Download/download','id='.$info['id']);?>"><img id="address_img" src="/opened/Public/Img/Common/file_icon_big/iconfont-filejpg.png" alt="点击下载该文件" /></a><?php break;?>
													<?php case "5": ?><a href="<?php echo U('Download/download','id='.$info['id']);?>"><img id="address_img" src="/opened/Public/Img/Common/file_icon_big/iconfont-filegif.png" alt="点击下载该文件" /></a><?php break;?>
													<?php case "6": ?><a href="<?php echo U('Download/download','id='.$info['id']);?>"><img id="address_img" src="/opened/Public/Img/Common/file_icon_big/iconfont-filepng.png" alt="点击下载该文件" /></a><?php break;?>
													<?php case "7": ?><a href="<?php echo U('Download/download','id='.$info['id']);?>"><img id="address_img" src="/opened/Public/Img/Common/file_icon_big/iconfont-wbdiconsoftjpeg.png" alt="点击下载该文件" /></a><?php break;?>
													<?php case "8": ?><a href="<?php echo U('Download/download','id='.$info['id']);?>"><img id="address_img" src="/opened/Public/Img/Common/file_icon_big/iconfont-docx.png" alt="点击下载该文件" /></a><?php break;?>
													<?php case "9": ?><a href="<?php echo U('Download/download','id='.$info['id']);?>"><img id="address_img" src="/opened/Public/Img/Common/file_icon_big/iconfont-pdf.png" alt="点击下载该文件" /></a><?php break;?>
													<?php case "10": ?><a href="<?php echo U('Download/download','id='.$info['id']);?>"><img id="address_img" src="/opened/Public/Img/Common/file_icon_big/iconfont-rar.png" alt="点击下载该文件" /></a><?php break;?>
													<?php case "11": ?><a href="<?php echo U('Download/download','id='.$info['id']);?>"><img id="address_img" src="/opened/Public/Img/Common/file_icon_big/iconfont-zip.png" alt="点击下载该文件" /></a><?php break;?>
													<?php case "12": ?><a href="<?php echo U('Download/download','id='.$info['id']);?>"><img id="address_img" src="/opened/Public/Img/Common/file_icon_big/iconfont-xls.png" alt="点击下载该文件" /></a><?php break;?>
													<?php default: ?><a href="<?php echo U('Download/download','id='.$info['id']);?>"><img id="address_img" src="/opened/Public/Img/Common/file_icon_big/iconfont-weizhi.png" alt="点击下载该文件" /></a><?php endswitch;?>
													<span class="ci_pfr_text">残阳广告公司简介</span><br /><span class="ci_pfr_time">12/07/16</span>
												</div>
												<div class="ci_pfr_check"><input type="checkbox" name="file_id" value="1" /></div>
											</li>
											<li>
												<div class="ci_pfr_info">
													<?php switch($info["type"]): case "1": ?><a href="<?php echo U('Download/download','id='.$info['id']);?>"><img id="address_img" src="/opened/Public/Img/Common/file_icon_big/iconfont-doc.png" alt="点击下载该文件" /></a><?php break;?>
													<?php case "2": ?><a href="<?php echo U('Download/download','id='.$info['id']);?>"><img id="address_img" src="/opened/Public/Img/Common/file_icon_big/iconfont-xlsx.png" alt="点击下载该文件" /></a><?php break;?>
													<?php case "3": ?><a href="<?php echo U('Download/download','id='.$info['id']);?>"><img id="address_img" src="/opened/Public/Img/Common/file_icon_big/iconfont-txt.png" alt="点击下载该文件" /></a><?php break;?>
													<?php case "4": ?><a href="<?php echo U('Download/download','id='.$info['id']);?>"><img id="address_img" src="/opened/Public/Img/Common/file_icon_big/iconfont-filejpg.png" alt="点击下载该文件" /></a><?php break;?>
													<?php case "5": ?><a href="<?php echo U('Download/download','id='.$info['id']);?>"><img id="address_img" src="/opened/Public/Img/Common/file_icon_big/iconfont-filegif.png" alt="点击下载该文件" /></a><?php break;?>
													<?php case "6": ?><a href="<?php echo U('Download/download','id='.$info['id']);?>"><img id="address_img" src="/opened/Public/Img/Common/file_icon_big/iconfont-filepng.png" alt="点击下载该文件" /></a><?php break;?>
													<?php case "7": ?><a href="<?php echo U('Download/download','id='.$info['id']);?>"><img id="address_img" src="/opened/Public/Img/Common/file_icon_big/iconfont-wbdiconsoftjpeg.png" alt="点击下载该文件" /></a><?php break;?>
													<?php case "8": ?><a href="<?php echo U('Download/download','id='.$info['id']);?>"><img id="address_img" src="/opened/Public/Img/Common/file_icon_big/iconfont-docx.png" alt="点击下载该文件" /></a><?php break;?>
													<?php case "9": ?><a href="<?php echo U('Download/download','id='.$info['id']);?>"><img id="address_img" src="/opened/Public/Img/Common/file_icon_big/iconfont-pdf.png" alt="点击下载该文件" /></a><?php break;?>
													<?php case "10": ?><a href="<?php echo U('Download/download','id='.$info['id']);?>"><img id="address_img" src="/opened/Public/Img/Common/file_icon_big/iconfont-rar.png" alt="点击下载该文件" /></a><?php break;?>
													<?php case "11": ?><a href="<?php echo U('Download/download','id='.$info['id']);?>"><img id="address_img" src="/opened/Public/Img/Common/file_icon_big/iconfont-zip.png" alt="点击下载该文件" /></a><?php break;?>
													<?php case "12": ?><a href="<?php echo U('Download/download','id='.$info['id']);?>"><img id="address_img" src="/opened/Public/Img/Common/file_icon_big/iconfont-xls.png" alt="点击下载该文件" /></a><?php break;?>
													<?php default: ?><a href="<?php echo U('Download/download','id='.$info['id']);?>"><img id="address_img" src="/opened/Public/Img/Common/file_icon_big/iconfont-weizhi.png" alt="点击下载该文件" /></a><?php endswitch;?>
													<span class="ci_pfr_text">供应商</span><span class="ci_pfr_time">12/07/16</span>
												</div>
												<div class="ci_pfr_check"><input type="checkbox" name="file_id" value="1" /></div>
											</li>
											<li>
												<div class="ci_pfr_info">
													<?php switch($info["type"]): case "1": ?><a href="<?php echo U('Download/download','id='.$info['id']);?>"><img id="address_img" src="/opened/Public/Img/Common/file_icon_big/iconfont-doc.png" alt="点击下载该文件" /></a><?php break;?>
													<?php case "2": ?><a href="<?php echo U('Download/download','id='.$info['id']);?>"><img id="address_img" src="/opened/Public/Img/Common/file_icon_big/iconfont-xls.png" alt="点击下载该文件" /></a><?php break;?>
													<?php case "3": ?><a href="<?php echo U('Download/download','id='.$info['id']);?>"><img id="address_img" src="/opened/Public/Img/Common/file_icon_big/iconfont-txt.png" alt="点击下载该文件" /></a><?php break;?>
													<?php case "4": ?><a href="<?php echo U('Download/download','id='.$info['id']);?>"><img id="address_img" src="/opened/Public/Img/Common/file_icon_big/iconfont-filejpg.png" alt="点击下载该文件" /></a><?php break;?>
													<?php case "5": ?><a href="<?php echo U('Download/download','id='.$info['id']);?>"><img id="address_img" src="/opened/Public/Img/Common/file_icon_big/iconfont-filegif.png" alt="点击下载该文件" /></a><?php break;?>
													<?php case "6": ?><a href="<?php echo U('Download/download','id='.$info['id']);?>"><img id="address_img" src="/opened/Public/Img/Common/file_icon_big/iconfont-filepng.png" alt="点击下载该文件" /></a><?php break;?>
													<?php case "7": ?><a href="<?php echo U('Download/download','id='.$info['id']);?>"><img id="address_img" src="/opened/Public/Img/Common/file_icon_big/iconfont-wbdiconsoftjpeg.png" alt="点击下载该文件" /></a><?php break;?>
													<?php default: ?><a href="<?php echo U('Download/download','id='.$info['id']);?>"><img id="address_img" src="/opened/Public/Img/Common/file_icon_big/iconfont-weizhi.png" alt="点击下载该文件" /></a><?php endswitch;?>
													<span class="ci_pfr_text">会议纪要</span><span class="ci_pfr_time">12/07/16</span>
												</div>
												<div class="ci_pfr_check"><input type="checkbox" name="file_id" value="1" /></div>
											</li>
											<li>
												<div class="ci_pfr_info">
													<?php switch($info["type"]): case "1": ?><a href="<?php echo U('Download/download','id='.$info['id']);?>"><img id="address_img" src="/opened/Public/Img/Common/file_icon_big/iconfont-doc.png" alt="点击下载该文件" /></a><?php break;?>
													<?php case "2": ?><a href="<?php echo U('Download/download','id='.$info['id']);?>"><img id="address_img" src="/opened/Public/Img/Common/file_icon_big/iconfont-xls.png" alt="点击下载该文件" /></a><?php break;?>
													<?php case "3": ?><a href="<?php echo U('Download/download','id='.$info['id']);?>"><img id="address_img" src="/opened/Public/Img/Common/file_icon_big/iconfont-txt.png" alt="点击下载该文件" /></a><?php break;?>
													<?php case "4": ?><a href="<?php echo U('Download/download','id='.$info['id']);?>"><img id="address_img" src="/opened/Public/Img/Common/file_icon_big/iconfont-filejpg.png" alt="点击下载该文件" /></a><?php break;?>
													<?php case "5": ?><a href="<?php echo U('Download/download','id='.$info['id']);?>"><img id="address_img" src="/opened/Public/Img/Common/file_icon_big/iconfont-filegif.png" alt="点击下载该文件" /></a><?php break;?>
													<?php case "6": ?><a href="<?php echo U('Download/download','id='.$info['id']);?>"><img id="address_img" src="/opened/Public/Img/Common/file_icon_big/iconfont-filepng.png" alt="点击下载该文件" /></a><?php break;?>
													<?php case "7": ?><a href="<?php echo U('Download/download','id='.$info['id']);?>"><img id="address_img" src="/opened/Public/Img/Common/file_icon_big/iconfont-wbdiconsoftjpeg.png" alt="点击下载该文件" /></a><?php break;?>
													<?php default: ?><a href="<?php echo U('Download/download','id='.$info['id']);?>"><img id="address_img" src="/opened/Public/Img/Common/file_icon_big/iconfont-weizhi.png" alt="点击下载该文件" /></a><?php endswitch;?>
													<span class="ci_pfr_text">会议纪要</span><span class="ci_pfr_time">12/07/16</span>
												</div>
												<div class="ci_pfr_check"><input type="checkbox" name="file_id" value="1" /></div>
											</li>
											<li>
												<div class="ci_pfr_info">
													<?php switch($info["type"]): case "1": ?><a href="<?php echo U('Download/download','id='.$info['id']);?>"><img id="address_img" src="/opened/Public/Img/Common/file_icon_big/iconfont-doc.png" alt="点击下载该文件" /></a><?php break;?>
													<?php case "2": ?><a href="<?php echo U('Download/download','id='.$info['id']);?>"><img id="address_img" src="/opened/Public/Img/Common/file_icon_big/iconfont-xls.png" alt="点击下载该文件" /></a><?php break;?>
													<?php case "3": ?><a href="<?php echo U('Download/download','id='.$info['id']);?>"><img id="address_img" src="/opened/Public/Img/Common/file_icon_big/iconfont-txt.png" alt="点击下载该文件" /></a><?php break;?>
													<?php case "4": ?><a href="<?php echo U('Download/download','id='.$info['id']);?>"><img id="address_img" src="/opened/Public/Img/Common/file_icon_big/iconfont-filejpg.png" alt="点击下载该文件" /></a><?php break;?>
													<?php case "5": ?><a href="<?php echo U('Download/download','id='.$info['id']);?>"><img id="address_img" src="/opened/Public/Img/Common/file_icon_big/iconfont-filegif.png" alt="点击下载该文件" /></a><?php break;?>
													<?php case "6": ?><a href="<?php echo U('Download/download','id='.$info['id']);?>"><img id="address_img" src="/opened/Public/Img/Common/file_icon_big/iconfont-filepng.png" alt="点击下载该文件" /></a><?php break;?>
													<?php case "7": ?><a href="<?php echo U('Download/download','id='.$info['id']);?>"><img id="address_img" src="/opened/Public/Img/Common/file_icon_big/iconfont-wbdiconsoftjpeg.png" alt="点击下载该文件" /></a><?php break;?>
													<?php default: ?><a href="<?php echo U('Download/download','id='.$info['id']);?>"><img id="address_img" src="/opened/Public/Img/Common/file_icon_big/iconfont-weizhi.png" alt="点击下载该文件" /></a><?php endswitch;?>
													<span class="ci_pfr_text">报价表</span><span class="ci_pfr_time">12/07/19</span>
												</div>
												<div class="ci_pfr_check"><input type="checkbox" name="file_id" value="1" /></div>
											</li>
											<li>
												<div class="ci_pfr_info">
													<?php switch($info["type"]): case "1": ?><a href="<?php echo U('Download/download','id='.$info['id']);?>"><img id="address_img" src="/opened/Public/Img/Common/file_icon_big/iconfont-doc.png" alt="点击下载该文件" /></a><?php break;?>
													<?php case "2": ?><a href="<?php echo U('Download/download','id='.$info['id']);?>"><img id="address_img" src="/opened/Public/Img/Common/file_icon_big/iconfont-xls.png" alt="点击下载该文件" /></a><?php break;?>
													<?php case "3": ?><a href="<?php echo U('Download/download','id='.$info['id']);?>"><img id="address_img" src="/opened/Public/Img/Common/file_icon_big/iconfont-txt.png" alt="点击下载该文件" /></a><?php break;?>
													<?php case "4": ?><a href="<?php echo U('Download/download','id='.$info['id']);?>"><img id="address_img" src="/opened/Public/Img/Common/file_icon_big/iconfont-filejpg.png" alt="点击下载该文件" /></a><?php break;?>
													<?php case "5": ?><a href="<?php echo U('Download/download','id='.$info['id']);?>"><img id="address_img" src="/opened/Public/Img/Common/file_icon_big/iconfont-filegif.png" alt="点击下载该文件" /></a><?php break;?>
													<?php case "6": ?><a href="<?php echo U('Download/download','id='.$info['id']);?>"><img id="address_img" src="/opened/Public/Img/Common/file_icon_big/iconfont-filepng.png" alt="点击下载该文件" /></a><?php break;?>
													<?php case "7": ?><a href="<?php echo U('Download/download','id='.$info['id']);?>"><img id="address_img" src="/opened/Public/Img/Common/file_icon_big/iconfont-wbdiconsoftjpeg.png" alt="点击下载该文件" /></a><?php break;?>
													<?php default: ?><a href="<?php echo U('Download/download','id='.$info['id']);?>"><img id="address_img" src="/opened/Public/Img/Common/file_icon_big/iconfont-weizhi.png" alt="点击下载该文件" /></a><?php endswitch;?>
													<span class="ci_pfr_text">残阳广告公司简介</span><span class="ci_pfr_time">12/07/22</span>
												</div>
												<div class="ci_pfr_check"><input type="checkbox" name="file_id" value="1" /></div>
											</li>
											<li>
												<div class="ci_pfr_info">
													<?php switch($info["type"]): case "1": ?><a href="<?php echo U('Download/download','id='.$info['id']);?>"><img id="address_img" src="/opened/Public/Img/Common/file_icon_big/iconfont-doc.png" alt="点击下载该文件" /></a><?php break;?>
													<?php case "2": ?><a href="<?php echo U('Download/download','id='.$info['id']);?>"><img id="address_img" src="/opened/Public/Img/Common/file_icon_big/iconfont-xls.png" alt="点击下载该文件" /></a><?php break;?>
													<?php case "3": ?><a href="<?php echo U('Download/download','id='.$info['id']);?>"><img id="address_img" src="/opened/Public/Img/Common/file_icon_big/iconfont-txt.png" alt="点击下载该文件" /></a><?php break;?>
													<?php case "4": ?><a href="<?php echo U('Download/download','id='.$info['id']);?>"><img id="address_img" src="/opened/Public/Img/Common/file_icon_big/iconfont-filejpg.png" alt="点击下载该文件" /></a><?php break;?>
													<?php case "5": ?><a href="<?php echo U('Download/download','id='.$info['id']);?>"><img id="address_img" src="/opened/Public/Img/Common/file_icon_big/iconfont-filegif.png" alt="点击下载该文件" /></a><?php break;?>
													<?php case "6": ?><a href="<?php echo U('Download/download','id='.$info['id']);?>"><img id="address_img" src="/opened/Public/Img/Common/file_icon_big/iconfont-filepng.png" alt="点击下载该文件" /></a><?php break;?>
													<?php case "7": ?><a href="<?php echo U('Download/download','id='.$info['id']);?>"><img id="address_img" src="/opened/Public/Img/Common/file_icon_big/iconfont-wbdiconsoftjpeg.png" alt="点击下载该文件" /></a><?php break;?>
													<?php default: ?><a href="<?php echo U('Download/download','id='.$info['id']);?>"><img id="address_img" src="/opened/Public/Img/Common/file_icon_big/iconfont-weizhi.png" alt="点击下载该文件" /></a><?php endswitch;?>
													<span class="ci_pfr_text">供应商</span><span class="ci_pfr_time">12/07/16</span>
												</div>
												<div class="ci_pfr_check"><input type="checkbox" name="file_id" value="1" /></div>
											</li>
											<li>
												<div class="ci_pfr_info">
													<?php switch($info["type"]): case "1": ?><a href="<?php echo U('Download/download','id='.$info['id']);?>"><img id="address_img" src="/opened/Public/Img/Common/file_icon_big/iconfont-doc.png" alt="点击下载该文件" /></a><?php break;?>
													<?php case "2": ?><a href="<?php echo U('Download/download','id='.$info['id']);?>"><img id="address_img" src="/opened/Public/Img/Common/file_icon_big/iconfont-xls.png" alt="点击下载该文件" /></a><?php break;?>
													<?php case "3": ?><a href="<?php echo U('Download/download','id='.$info['id']);?>"><img id="address_img" src="/opened/Public/Img/Common/file_icon_big/iconfont-txt.png" alt="点击下载该文件" /></a><?php break;?>
													<?php case "4": ?><a href="<?php echo U('Download/download','id='.$info['id']);?>"><img id="address_img" src="/opened/Public/Img/Common/file_icon_big/iconfont-filejpg.png" alt="点击下载该文件" /></a><?php break;?>
													<?php case "5": ?><a href="<?php echo U('Download/download','id='.$info['id']);?>"><img id="address_img" src="/opened/Public/Img/Common/file_icon_big/iconfont-filegif.png" alt="点击下载该文件" /></a><?php break;?>
													<?php case "6": ?><a href="<?php echo U('Download/download','id='.$info['id']);?>"><img id="address_img" src="/opened/Public/Img/Common/file_icon_big/iconfont-filepng.png" alt="点击下载该文件" /></a><?php break;?>
													<?php case "7": ?><a href="<?php echo U('Download/download','id='.$info['id']);?>"><img id="address_img" src="/opened/Public/Img/Common/file_icon_big/iconfont-wbdiconsoftjpeg.png" alt="点击下载该文件" /></a><?php break;?>
													<?php default: ?><a href="<?php echo U('Download/download','id='.$info['id']);?>"><img id="address_img" src="/opened/Public/Img/Common/file_icon_big/iconfont-weizhi.png" alt="点击下载该文件" /></a><?php endswitch;?>
													<span class="ci_pfr_text">供应商</span><span class="ci_pfr_time">12/07/16</span>
												</div>
												<div class="ci_pfr_check"><input type="checkbox" name="file_id" value="1" /></div>
											</li>
										</ul>
									</div>
								</td>
							</tr>
						</table>
					</div>
					</form>
					<div class="ci_pf_comment"></div>
				</div>
				
				<div class="ci_p_com_box">
					<div class="comment_box_div">
						<ol class="ucm_c_list" id="comments_display_area">
							<li class="comment" id="li-comment-<?php echo ($vo["cid"]); ?>" cbp="<?php echo ($vo["cid"]); ?>">
								<div id="comment-<?php echo ($vo["cid"]); ?>" class="replied_box">
									<div class="replied_left">
										<img src='/opened/Uploads/Common/avatar_small/user100_1161.jpg' class='photo' height='44' width='44'/>
									</div>
									<div class="replied_right">
										<div class="replied_username">
											<strong><a><b>阳光_小赵&nbsp;:</b></a></strong>
										</div>
										<div class="replied_content">您好，这是一条评论。您好，这是一条评论。您好，这是一条评论。您好，这是一条评论。您好，这是一条评论。您好，这是一条评论。您好，这是一条评论。您好，这是一条评论。您好，这是一条评论。您好，这是一条评论。您好，这是一条评论。 </div>
										<div class="replied_time" style="margin-bottom:10px;">
											<span>2015-01-12 22:30:01<!--<?php echo (date('Y-m-d H:i:s',$vo["comment_edit_date"])); ?>--></span>
											<span class="reply">
												<a onclick="return addComment.moveForm('comment-<?php echo ($vo["cid"]); ?>', '<?php echo ($vo["cid"]); ?>', '<?php echo ($vo["comment_author_name"]); ?>', '<?php echo ($vo["comment_top_parent"]); ?>', '<?php echo ($article_info["id"]); ?>', 0)" href="/opened/index.php?m=&c=Company&a=index&id=1">[回复]</a>
											</span>
										</div>
									</div>
									<div class="clear"></div>
								</div>
							</li>
							<li class="comment" id="li-comment-2" cbp="<?php echo ($vo["cid"]); ?>">
								<div id="comment-<?php echo ($vo["cid"]); ?>" class="replied_box">
									<div class="replied_left">
										<img src='/opened/Uploads/Common/avatar_small/user100_1461.jpg' class='photo' height='44' width='44'/>
									</div>
									<div class="replied_right">
										<div class="replied_username">
											<strong><a><b>刘丽&nbsp;:</b></a></strong>
										</div>
										<div class="replied_content">请各位领导发表对该案例的看法。</div>
										<div class="replied_time" style="margin-bottom:10px;">
											<span>2015-10-12 22:30:01<!--<?php echo (date('Y-m-d H:i:s',$vo["comment_edit_date"])); ?>--></span>
											<span class="reply">
												<a onclick="return addComment.moveForm('comment-<?php echo ($vo["cid"]); ?>', '<?php echo ($vo["cid"]); ?>', '<?php echo ($vo["comment_author_name"]); ?>', '<?php echo ($vo["comment_top_parent"]); ?>', '<?php echo ($article_info["id"]); ?>', 0)" href="/opened/index.php?m=&c=Company&a=index&id=1">[回复]</a>
											</span>
										</div>
									</div>
									<div class="clear"></div>
								</div>
							</li>
							<li class="comment" id="li-comment-3" cbp="<?php echo ($vo["cid"]); ?>">
								<div id="comment-<?php echo ($vo["cid"]); ?>" class="replied_box_right">
									<div class="replied_left">
										<img src='/opened/Uploads/Common/avatar_small/user100_1586.jpg' class='photo' height='44' width='44'/>
									</div>
									<div class="replied_right">
										<div class="replied_username">
											<strong><a><b>ceshi04&nbsp;:</b></a></strong>
										</div>
										<div class="replied_content">这是本人回复的评论测试！！！！</div>
										<div class="replied_time" style="margin-bottom:10px;">
											<span>2016-04-12 10:30:01<!--<?php echo (date('Y-m-d H:i:s',$vo["comment_edit_date"])); ?>--></span>
											<span class="reply">
												<a onclick="return addComment.moveForm('comment-<?php echo ($vo["cid"]); ?>', '<?php echo ($vo["cid"]); ?>', '<?php echo ($vo["comment_author_name"]); ?>', '<?php echo ($vo["comment_top_parent"]); ?>', '<?php echo ($article_info["id"]); ?>', 0)" href="/opened/index.php?m=&c=Company&a=index&id=1">[回复]</a>
											</span>
										</div>
									</div>
									<div class="clear"></div>
								</div>
							</li>
						</ol>
						
						
						<div id="td_respond_box">
							<div id="respond">
								<div class="respond_box"> <!--完美解决ie6textarea兼容问题-->
									<div class="cancel-comment-reply">
										<small><a rel="nofollow" id="cancel-comment-reply-link" href="/opened/index.php?m=&c=Company&a=index&id=1" style="display:none;">点击这里取消编辑</a></small>
									</div>
									<form action="<?php echo U('Company/ajaxComment');?>" method="post" id="commentform">
										<div class="comt-box">
											<fieldset><textarea name="comment_content" id="c_comment" class="comt-area" tabindex="4" cols="50" rows="5" onkeyup="keyups.onstr(this)"></textarea></fieldset>
											<div id="c_loading" style="display:none;"><img src="/opened/Public/Img/Common/loading.gif" style="vertical-align:middle;" alt="" /> 正在提交, 请稍候...</div>
											<div style="display:none;" id="c_error"><img src="/opened/Public/Img/Common/no.png" style="vertical-align:middle;" alt="" /><span></span></div>
											<div class="comt-ctrl">
												<div class="comt_s_box">
													<span id="com_r_hint" class="comt-num"><font>还能输入<em>240</em>个字</font></span>
													<input class="comt-submit" name="submit" id="submit" tabindex="5" value="发布评论" type="submit">
													<input name="correlation_id" value="<?php echo ($info["cid"]); ?>" id="c_correlation_id" type="hidden" />
													<input name="comment_parent" id="comment_parent" value="0" type="hidden" />
													<input name="comment_parent_author_name" id="comment_parent_author_name" value="" type="hidden" />
													<input name="c_best_parent" id="c_best_parent" value="0" type="hidden" />
													<input name="act_option" id="act_option" value="0" type="hidden" />
													<!-- 暂未启用 2014-5-12 <input name="c_edit_id" id="c_edit_id" value="0" type="hidden" />-->
												</div>
											</div>
										</div>
									</form>
								</div>
							</div>
						</div>
					</div>
				</div>
				
			</div>

			<div class="clear"></div>
			<div class="page_footer">
				<div class="nav"></div>
				<div class="f_middle_info">
					<ul class="info_menu">
						<li class="info_stmenu">
							<span class="text_span">北京 <b class="font_big">4007004941</b></span>
						</li>
						<li class="info_stmenu">
							<span class="text_span">上海 <b class="font_big">55310556*2801</b></span>
						</li>
						<li class="info_stmenu border_left">
							<span class="text_span font_medium">谁是LightBP?</span>
						</li>
						<li class="info_stmenu border_left border_right">
							<span class="text_span font_medium">网站地图</span>
						</li>
						<li class="info_stmenu">
							<span><a href="javascript:void(0);" target="_blank" class="info_stmenu_a">沪ICP备06046803</a>&nbsp;&nbsp;&nbsp;&nbsp;Copyright©2006<br><span class="font_little">北京环球看点广告传媒有限公司保留所有权利</span></span>
						</li>
						<li class="info_stmenu">
							<span class="li_img"><a ><img src="/opened/Public/Img/Common/logo_little.png" alt="Opened"></a></span>
						</li>
						<li class="li_background_set ">
							<span class="text_span font_big"><?php if($login_mark == 1): ?><a href="<?php echo U('User/logout');?>" class="" alt="退出">退出</a>/<a href="<?php echo U('Project/index');?>" class="" alt="帮助">帮助</a><?php else: ?><a href="<?php echo U('User/loginAct');?>" class="" alt="登录">登录</a>/<a href="<?php echo U('User/registerAct');?>" class="" alt="注册">注册</a><?php endif; ?></span>
						</li>
					</ul>
				</div>
				<div class="c_footer_info" style="margin-top:0;">
					<div class="site-info" style="text-align:center;">
						<span><?php echo ($beian); ?><script type="text/javascript">var cnzz_protocol = (("https:" == document.location.protocol) ? " https://" : " http://");document.write(unescape("%3Cspan id='cnzz_stat_icon_1000244962'%3E%3C/span%3E%3Cscript src='" + cnzz_protocol + "s22.cnzz.com/z_stat.php%3Fid%3D1000244962%26show%3Dpic' type='text/javascript'%3E%3C/script%3E"));</script></span>
						<p class="author"><a href="" target="_blank" rel="external">Theme By D.Apache.Luo</a></p>
					</div>
				</div>			
			</div>
			
		</div>
	</body>
</html>
<script type="text/javascript">
	var root = "/opened";
	$(document).ready(function(e) {	
		var flow=[];
		for(var i=0;i<12;i++){
			flow.push(Math.floor(Math.random()*(2000+((i%6)*2000)))+2000);
		}
		
		var data = [
					{
						name : 'PV',
						value:flow,
						color:'#ec4646',
						line_width:2
					}
				 ];
		
		var labels = ["1月","2月","3月","4月","5月","6月","7月","8月","9月","10月","11月","12月"];
		var chart = new iChart.LineBasic2D({
			render : 'canvasDiv',
			data: data,
			align:'center',
			title : {
				text:'',
				font : '微软雅黑',
				fontsize:24,
				color:'#b4b4b4'
			},
			subtitle : {
				text:'',
				font : '微软雅黑',
				color:'#b4b4b4'
			},
			footnote : {
				text:' ',
				font : '微软雅黑',
				fontsize:11,
				fontweight:600,
				padding:'0 28',
				color:'#b4b4b4'
			},
			border : {
				enable:false,
				color:'#cccccc',
				width:1,
				redius:5
			},
			width :900,
			height :400,
			
			shadow:true,
			shadow_color : '#202020',
			shadow_blur : 8,
			shadow_offsetx : 0,
			shadow_offsety : 0,
			background_color:'#ffffff',
			tip:{
				enable:true,
				shadow:true,
				listeners:{
					 //tip:提示框对象、name:数据名称、value:数据值、text:当前文本、i:数据点的索引
					parseText:function(tip,name,value,text,i){
						return "<span style='color:#005268;font-size:12px;'>"+labels[i]+":项目花费:<br/>"+
						"</span><span style='color:#005268;font-size:20px;'>"+value+".00元</span>";
					}
				}
			},
			crosshair:{
				enable:true,
				line_color:'#ec4646'
			},
			sub_option : {
				smooth : true,
				label:false,
				hollow:false,
				hollow_inside:false,
				point_size:8
			},
			coordinate:{
				width:750,
				height:320,
				striped_factor : 0.18,
				grid_color:'#e6f0f4',
				axis:{
					enable:true,
					color:'#cccccc',
					width:[0,0,2,2]
				},
				scale:[{
					 position:'left',	
					 start_scale:0,
					 end_scale:20000,
					 scale_space:2000,
					 scale_size:2,
					 scale_enable : false,
					 label : {color:'#9d987a',font : '微软雅黑',fontsize:11,fontweight:600},
					 scale_color:'#9f9f9f'
				},{
					 position:'bottom',	
					 label : {color:'#9d987a',font : '微软雅黑',fontsize:11,fontweight:600},
					 scale_enable : false,
					 labels:labels
				}]
			}
		});
		//利用自定义组件构造左侧说明文本
		chart.plugin(new iChart.Custom({
				drawFn:function(){
					//计算位置
					var coo = chart.getCoordinate(),
						x = coo.get('originx'),
						y = coo.get('originy'),
						w = coo.width,
						h = coo.height;
					//在左上侧的位置，渲染一个单位的文字
					chart.target.textAlign('start')
					.textBaseline('bottom')
					.textFont('600 11px 微软雅黑')
					.fillText('项目花费(元)',x-40,y-12,false,'#9d987a')
					.textBaseline('top')
					.fillText('(时间)',x+w+12,y+h+10,false,'#9d987a');
					
				}
		}));
		//开始画图
		chart.draw();

		/*
		$('#jqChart').jqChart({
			title: { text: '' },
			axes: [
				{
					location: 'left',//y轴位置，取值：left,right
					minimum: 10,//y轴刻度最小值
					maximum: 100,//y轴刻度最大值
					interval: 10//刻度间距
				}
			],
			series: [
				//数据1开始
				{
					type: 'line',//图表类型，取值：column 柱形图，line 线形图
					title:'北京',//标题
					data: [['一月', 70], ['二月', 40], ['三月', 55], ['四月', 50], ['五月', 60], ['六月', 40]]//数据内容，格式[[x轴标题,数值1],[x轴标题,数值2],......]
				},		
			]
		});
		*/
		$('#cim_h_line_one').jqbar({ label: '', value: 70, barColor: '#3a89c9' });
		$('#cim_h_line_two').jqbar({ label: '', value: 20, barColor: '#3a89c9' });
		$('#cim_h_line_three').jqbar({ label: '', value: 10, barColor: '#3a89c9' });
		try {
			$(".cim_project_box select").msDropDown();
		} catch(e) {
			//console.log(e);	
		}
		
		$('.ci_p_file_box .file_box').hide();
		$('.ci_p_file_box tr.default_show_tr').show();
		$(".ci_p_list ul li div.file_img_box").on('click',function(){
			var current_obj = $(this).parent();
			$(".ci_p_list ul li").each(function(){	
				if($(this).attr('class') != 'clear' && $(this).find("div:first").attr('default_sign') == '1'){
					$(this).find("div:first-child").attr('default_sign','0');
					$(this).find("div:first-child").attr('class','file_img_box ci_pl_box');
					if($(this).find("div:last-child") && $(this).find("div:last").attr('class') == 'ci_checked_btn'){
						$(this).find("div:last").remove();
					}
				}
			});

			if(current_obj.find("div:first-child").length > 0){
				current_obj.find("div:first-child").attr('default_sign','1');
				current_obj.find("div:first-child").attr('class','file_img_box ci_pl_box_checked');
				var html = "<div class='ci_checked_btn'><a href='javascript:void(0);'>删除</a><span class='ci_checked_btn_text'>/</span><a href='javascript:void(0);'  onclick='showUploadBox($(this))'>上传</a></div>";
				current_obj.append(html);
				$("#upload_object_id").attr('value',current_obj.attr('pid'));
			}
			
			var currentTab = $(this).parent().attr('href');
			$(currentTab).siblings().hide();
			$(currentTab).show();
			$(".ci_pf_list .comment_box").show();
			return false; 
		});			
		
		//选择文件
		$('#fileselect').change(function(event) {			
			event.preventDefault();
			var n=event.target.files.length;		        
			var file;		        
			for (var i = 0; i < n; i++) {
				file=event.target.files[i];
				html5up(file);
			};						        
		});	
		//上传操作
		function html5up(file){
			var pid = $("#upload_object_id").val();
			/* Act on the event */
			var form_data=new FormData();				
			form_data.append('timestamp',"<?php echo ($check_time); ?>");
			form_data.append('token','<?php echo md5("unique_salt".$check_time);?>');
			form_data.append("Filedata",file);						
			form_data.append("pid",pid);						
			$.ajax({
				url: '<?php echo U("Company/upload",null,false);?>',
				type: 'POST',
				// dataType: 'default: Intelligent Guess (Other values: xml, json, script, or html)',
				processData: false,
				contentType: false,
				data: form_data,
			})
			.done(function(data) {					
				if (!data.flag) {
					alert(data.msg);
					return false;
				};		            
				put_img(data.data.id,data.data.name,data.data.type,data.data.download_url,data.data.add_time);//添加图片到img_box
				console.log("success");
			})
			.fail(function() {
				console.log("error");
			})
			.always(function() {
				console.log("complete");
			});
		
		}	
		//uploadify上传操作		
		$('#file_upload').uploadify({
			'formData'     : {
				'timestamp' : "<?php echo ($check_time); ?>",//当前时间戳由后台传过来的
				'token'     : '<?php echo md5("unique_salt".$check_time);?>'//用md5生成token
			},
			'swf'      : '/opened/Public/Js/Common/uploadify/uploadify.swf',//引用uploadify.swf
			'uploader' : '<?php echo U("Company/upload",null,false);?>',//后台上传文件处理方法
			'buttonText':'选择文件',
			'multi'           : true,//允许多文件上传
			'fileTypeExts' : '*.gif; *.jpg; *.png;*.doc;*.pdf;*.rar;*.zip;*.docx;*.xlsx;*.txt;',//限制文件格式
			'fileSizeLimit' : '5MB',//限制文件大小

			'onUploadSuccess' : function(file, data, response) {
				var data = $.parseJSON(data);	
				if (!data['flag']) {
					alert(data['msg']);
					return false;
				};		            
				put_img(data.data.id,data.data.name,data.data.type,data.data.download_url,data.data.add_time);//添加图片到img_box
			}
		});
		


		//添加图片到img_box
		function put_img(id,name,type,url,time){
			/* 老版的uploadfy
			//var img_url="/opened"+savepath+savename;
			// alert(img_url);
			var new_img='<div class="col-md-3">';					
				new_img+='<img src="'+img_url+'" alt="test" class="img">';
				new_img+='<div class="img_ctrl">';
				new_img+='<span class="glyphicon glyphicon-cloud-download btn" data-toggle="tooltip" data-placement="bottom" title="下载链接地址" aria-hidden="true"></span>';
				new_img+='<span class="glyphicon glyphicon-trash btn" data-toggle="tooltip" data-placement="bottom" title="删除文件" aria-hidden="true"></span>';
				new_img+='<span class="glyphicon glyphicon-tag btn" data-toggle="tooltip" data-placement="bottom" title="修改标题" aria-hidden="true"></span>';
				new_img+='</div>';
				new_img+='<div class="img_name">';
				new_img+='<form action="" class="form-inline">';
				new_img+='<input type="text" class="form-control input-sm savename" value="'+savename+'">';
				new_img+='<button class="btn btn-primary btn-sm save inline-block">保存</button>';
				new_img+='</form>';
				new_img+='</div>';
				new_img+='</div>';
			*/
			var b_img = "/opened/Public/Img/Common/file_icon_big/iconfont-weizhi.png";
			switch(type){
				case 1:
					b_img = "/opened/Public/Img/Common/file_icon_big/iconfont-doc.png";
					break;
				case 2:
					b_img = "/opened/Public/Img/Common/file_icon_big/iconfont-xlsx.png";
					break;
				case 3:
					b_img = "/opened/Public/Img/Common/file_icon_big/iconfont-txt.png";
					break;
				case 4:
					b_img = "/opened/Public/Img/Common/file_icon_big/iconfont-filejpg.png";
					break;
				case 5:
					b_img = "/opened/Public/Img/Common/file_icon_big/iconfont-filegif.png";
					break;
				case 6:
					b_img = "/opened/Public/Img/Common/file_icon_big/iconfont-filepng.png";
					break;
				case 7:
					b_img = "/opened/Public/Img/Common/file_icon_big/iconfont-wbdiconsoftjpeg.png";
					break;
				case 8:
					b_img = "/opened/Public/Img/Common/file_icon_big/iconfont-docx.png";
					break;
				case 9:
					b_img = "/opened/Public/Img/Common/file_icon_big/iconfont-pdf.png";
					break;
				case 10:
					b_img = "/opened/Public/Img/Common/file_icon_big/iconfont-rar.png";
					break;
				case 11:
					b_img = "/opened/Public/Img/Common/file_icon_big/iconfont-zip.png";
					break;
				case 12:
					b_img = "/opened/Public/Img/Common/file_icon_big/iconfont-xls.png";
					break;
				default:
					b_img = "/opened/Public/Img/Common/file_icon_big/iconfont-weizhi.png";
			}
			var new_img = '<li><div class="ci_pfr_info"><a href='+url+'><img id="address_img" src="'+b_img+'" alt="点击下载该文件" /></a><span class="ci_pfr_text">'+name+'</span><span class="ci_pfr_time">'+time+'</span></div><div class="ci_pfr_check"><input type="checkbox" name="file_id" value="1" /></div></li>';
			var pid = $("#upload_object_id").val();
			var td_file_box_obj = $("#file_box_"+pid+" div.ci_pfo_right ul");
			if(td_file_box_obj.length > 0){
				td_file_box_obj.append(new_img);
			}else{
				alert("未查找到指定文件位置！");
			}
		}

		/*
		//复制图片地址
		$('.glyphicon-cloud-download').livequery(function(){
			$(this).zclip({
				path:'/opened/Public/js/jquery.zclip/ZeroClipboard.swf',
				copy:function(){
					return "http://"+"<?php echo ($_SERVER['HTTP_HOST']); ?>"+$(this).parents('div.col-md-3').find('.img').attr('src');						
				},
				afterCopy:function(){
					alert('成功复制图片地址');
				},
			});
		});
		*/
	
		/*
		//调出修改标题input
		$(document).on('click','.glyphicon-tag',function(event){
			var div=$(this).parents('div.col-md-3');
			var img_name=div.find('.img_name');
			img_name.fadeToggle();
		});

		//点击保存按钮
		$(document).on('click','.save',function(event){
			event.preventDefault();
			var img_name=$(this).parents('.img_name');
			var savename=img_name.find('.savename');
			var new_name=savename.val();
			// alert(new_name);
			var img=$(this).parents('div.col-md-3').find('.img');
			var img_url=img.attr('src');
			// alert(img_url);
			$.ajax({
				url: '<?php echo U("Company/changeName",null,false);?>',
				type: 'POST',
				// dataType: 'default: Intelligent Guess (Other values: xml, json, script, or html)',
				data: {url:img_url,name: new_name},
				success:function(data){
					// var data = $.parseJSON(data);
					// alert(data);return false;
					if (!data['flag']) {
						alert(data['msg']);
						return false;
					};
					savename.val(data['savename']);
					img.attr('src',"/opened"+data['savepath']);
					img_name.fadeOut('slow');
				}
			});

		});
		*/
		
		//图片删除
		$(document).on('click', '.glyphicon-trash', function(event) {
			event.preventDefault();
			/* Act on the event */
			var div=$(this).parents('div.col-md-3');
			var img_url=div.find('.img').attr('src');
			// alert(img_url);
			$.ajax({
				url: '<?php echo U("Company/del",null,false);?>',
				type: 'POST',
				// dataType: 'default: Intelligent Guess (Other values: xml, json, script, or html)',
				data: {url: img_url},
				success:function(data){
					if (!data['flag']) {
						alert(data['msg']);
						return false;
					}else{
						alert(data['msg']);
						div.fadeOut(2000);
						div.remove();
					};
				}
			});			
			
		});
		
		/*
		//图片放大预览
		$(document).on('click', '.img', function(event) {
			event.preventDefault();
			/* Act on the event *//*
			var img_url=$(this).attr('src');
			$('#modal').modal('show');
			$('#img_show').attr('src',img_url);
		});
		*/
		
		/*
		//点击modal自动隐藏
		$('#modal').click(function(event) {
			$(this).modal('hide');
		});
		*/
		/* 文件上传控件 end by lsq 160418 */
		
	});

	function toggleEditDesc(obj){
		var check_flag = $(".ci_i_intro input[name='check_flag']").val();
		var box_obj = $(".ci_i_intro .ci_ii_text");
		if(box_obj.length > 0 && check_flag != 1){
			var div_y = box_obj.height();
			var text = box_obj.text();
			var eidt_html = "<textarea class='intro_edit_box' name='company_desc' style='height:"+div_y+"px'>"+text+"</textarea>";
			box_obj.html(eidt_html);
			$(".ci_i_intro input[name='check_flag']").val("1");
			obj.text("保存");
		}else{
			var text = box_obj.find("textarea").val();
			box_obj.text(text);
			$(".ci_i_intro input[name='check_flag']").val("0");
			obj.text("编辑");
		}
	}

	function showAddInput(obj,type){
		var p_obj = obj.parent();
		var new_html = '';
		var new_obj;
		var js_obj;
		if(type == 1){
			new_html = "<input class='ci_bb_input' type='text' name='add_user_a_box' size='15' onblur='hideAddInput($(this),1)' /><span class='ci_bb_yq'><a href='javascript:void(0);'>邀请</a></span>";
			js_obj = 'hideAddInput($(this),1)';
		}else{
			new_html = "<input class='ci_bb_input' type='text' name='add_user_b_box' size='15' onblur='hideAddInput($(this),2)' /><span class='ci_bb_yq'><a href='javascript:void(0);'>邀请</a></span>";
			js_obj = 'hideAddInput($(this),2)';
		}
		p_obj.removeClass('ci_bb_btn');
		p_obj.addClass('ci_bb_box');
		p_obj.append(new_html);
		new_obj = p_obj.find("input.ci_bb_input");
		if(new_obj.length > 0){
			new_obj.focus();
		}
		obj.attr('onclick',js_obj);
	}
	
	function hideAddInput(obj,type){
		var p_obj = obj.parent();
		var del_one = p_obj.find(".ci_bb_input");
		var del_two = p_obj.find(".ci_bb_yq");
		if(del_one.length > 0){
			del_one.remove();
		}
		if(del_two.length > 0){
			del_two.remove();
		}
		p_obj.removeClass('ci_bb_box');
		p_obj.addClass('ci_bb_btn');
		if(type == 1){
			obj.attr('onclick','showAddInput($(this),1)');
		}else{
			obj.attr('onclick','showAddInput($(this),2)');
		}
	}
	
	function showReduceInput(obj,type){
		var p_obj = obj.parent();
		var new_html = '';
		var new_obj;
		var js_obj;
		if(type == 1){
			new_html = "<input class='ci_bb_input' type='text' name='reduce_user_a_box' size='15' onblur='hideAddInput($(this),1)' /><span class='ci_bb_yq'><a href='javascript:void(0);'>移除</a></span>";
			js_obj = 'hideReduceInput($(this),1)';
		}else{
			new_html = "<input class='ci_bb_input' type='text' name='reduce_user_b_box' size='15' onblur='hideAddInput($(this),2)' /><span class='ci_bb_yq'><a href='javascript:void(0);'>移除</a></span>";
			js_obj = 'hideReduceInput($(this),2)';
		}
		p_obj.removeClass('ci_bb_btn');
		p_obj.addClass('ci_bb_box');
		p_obj.append(new_html);
		new_obj = p_obj.find("input.ci_bb_input");
		if(new_obj.length > 0){
			new_obj.focus();
		}
		obj.attr('onclick',js_obj);
	}
	
	function hideReduceInput(obj,type){
		var p_obj = obj.parent();
		var del_one = p_obj.find(".ci_bb_input");
		var del_two = p_obj.find(".ci_bb_yq");
		if(del_one.length > 0){
			del_one.remove();
		}
		if(del_two.length > 0){
			del_two.remove();
		}
		p_obj.removeClass('ci_bb_box');
		p_obj.addClass('ci_bb_btn');
		if(type == 1){
			obj.attr('onclick','showReduceInput($(this),1)');
		}else{
			obj.attr('onclick','showReduceInput($(this),2)');
		}
	}
	
	/* 文件上传控件 */
	function showUploadBox(obj){		
		$("#fileselect").click();
	}

</script>