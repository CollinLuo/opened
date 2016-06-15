/*
Navicat MySQL Data Transfer

Source Server         : localhost
Source Server Version : 50540
Source Host           : localhost:3306
Source Database       : opened

Target Server Type    : MYSQL
Target Server Version : 50540
File Encoding         : 65001

Date: 2016-06-15 17:16:25
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for td_access
-- ----------------------------
DROP TABLE IF EXISTS `td_access`;
CREATE TABLE `td_access` (
  `role_id` smallint(6) unsigned NOT NULL DEFAULT '0',
  `node_id` smallint(6) unsigned NOT NULL DEFAULT '0',
  `level` tinyint(1) NOT NULL DEFAULT '1' COMMENT '权限等级区分默认为1表示项目，2表示控制，3表示操作',
  `pid` smallint(6) DEFAULT '0',
  `module` varchar(50) DEFAULT '',
  KEY `groupId` (`role_id`),
  KEY `nodeId` (`node_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='权限分配表';

-- ----------------------------
-- Records of td_access
-- ----------------------------
INSERT INTO `td_access` VALUES ('2', '9', '3', '2', null);
INSERT INTO `td_access` VALUES ('2', '8', '3', '2', null);
INSERT INTO `td_access` VALUES ('2', '7', '3', '2', null);
INSERT INTO `td_access` VALUES ('2', '6', '3', '2', null);
INSERT INTO `td_access` VALUES ('2', '2', '2', '1', null);
INSERT INTO `td_access` VALUES ('2', '1', '1', '0', '');
INSERT INTO `td_access` VALUES ('15', '9', '3', '2', 'main');
INSERT INTO `td_access` VALUES ('15', '8', '3', '2', 'nav');
INSERT INTO `td_access` VALUES ('15', '7', '3', '2', 'left');
INSERT INTO `td_access` VALUES ('15', '6', '3', '2', 'index');
INSERT INTO `td_access` VALUES ('15', '2', '2', '1', 'Index');
INSERT INTO `td_access` VALUES ('15', '3', '2', '1', 'User');
INSERT INTO `td_access` VALUES ('15', '10', '3', '3', 'index');
INSERT INTO `td_access` VALUES ('15', '11', '3', '3', 'mAdd');
INSERT INTO `td_access` VALUES ('15', '12', '3', '3', 'mEdit');
INSERT INTO `td_access` VALUES ('15', '13', '3', '3', 'mUserAnalyse');
INSERT INTO `td_access` VALUES ('15', '14', '3', '3', 'ajax_del_users');
INSERT INTO `td_access` VALUES ('15', '15', '3', '3', 'ajax_update_status');

-- ----------------------------
-- Table structure for td_ad
-- ----------------------------
DROP TABLE IF EXISTS `td_ad`;
CREATE TABLE `td_ad` (
  `ad_id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `position_id` smallint(5) unsigned NOT NULL DEFAULT '0',
  `ad_type` tinyint(4) unsigned NOT NULL DEFAULT '0' COMMENT '广告类型(默认为0表示站内广告，1表示站外广告)',
  `ad_name` varchar(60) NOT NULL,
  `ad_link` varchar(255) NOT NULL,
  `ad_image` varchar(100) NOT NULL COMMENT '广告图片',
  `ad_image_url` varchar(100) NOT NULL COMMENT '外链广告图片地址',
  `start_time` int(11) NOT NULL DEFAULT '0',
  `end_time` int(11) NOT NULL DEFAULT '0',
  `link_man` varchar(60) NOT NULL COMMENT '广告关联人名称',
  `link_email` varchar(60) NOT NULL,
  `link_phone` varchar(60) NOT NULL,
  `click_count` mediumint(8) unsigned NOT NULL DEFAULT '0' COMMENT '点击次数',
  `status` tinyint(4) unsigned NOT NULL DEFAULT '0' COMMENT '默认为0表示不可用，1表示可用',
  `area_code` varchar(255) NOT NULL COMMENT '广告区域码',
  `ad_detail` text NOT NULL COMMENT '广告详细描述',
  `article_binding` varchar(255) NOT NULL COMMENT '绑定单篇或者多篇文章、文章id用，隔开',
  `discount` varchar(255) NOT NULL COMMENT '折扣活动（暂时预留）',
  `create_time` int(11) NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(11) NOT NULL DEFAULT '0' COMMENT '上次更新时间',
  PRIMARY KEY (`ad_id`),
  KEY `position_id` (`position_id`),
  KEY `enabled` (`status`)
) ENGINE=MyISAM AUTO_INCREMENT=274 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of td_ad
-- ----------------------------
INSERT INTO `td_ad` VALUES ('271', '286', '0', 'test01', 'www.baidu.com', '20151223/567a34f9bea2b.png', '', '1450800000', '1450800000', '', '', '', '0', '1', 'index-one-1', '', '', '', '1450849529', '1450849529');
INSERT INTO `td_ad` VALUES ('272', '284', '0', 'test02', '', '20151225/567cd5b0a9733.jpg', '', '1450972800', '1450972800', '', '', '', '0', '1', 'index-one-2', '', '', '', '1451021744', '1451021744');
INSERT INTO `td_ad` VALUES ('273', '0', '1', 'test04', '', '', 'www.baidu.com', '1450972800', '1450972800', '', '', '', '0', '1', '', '', '', '', '1451021903', '1451021903');

-- ----------------------------
-- Table structure for td_admin
-- ----------------------------
DROP TABLE IF EXISTS `td_admin`;
CREATE TABLE `td_admin` (
  `aid` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(50) NOT NULL DEFAULT '' COMMENT '用户名',
  `email` varchar(50) NOT NULL DEFAULT '' COMMENT '账户绑定邮箱',
  `pwd` char(32) NOT NULL DEFAULT '' COMMENT '登录密码',
  `nickname` varchar(50) NOT NULL DEFAULT '' COMMENT '昵称',
  `sex` tinyint(4) NOT NULL DEFAULT '0' COMMENT '用户性别默认为0表示保密、1表示男、2表示女',
  `birthday` int(11) NOT NULL DEFAULT '0' COMMENT '管理员生日',
  `avatar` varchar(255) NOT NULL DEFAULT '' COMMENT '管理员头像',
  `mobile_number` varchar(54) NOT NULL DEFAULT '' COMMENT '管理员手机号',
  `qq` varchar(18) NOT NULL DEFAULT '0' COMMENT 'QQ号码',
  `personal_website` varchar(255) NOT NULL DEFAULT '' COMMENT '个人站点（评论时默认互粉站点）',
  `status` tinyint(4) NOT NULL DEFAULT '1' COMMENT '账号状态默认为0表示未审核、1表示已审核',
  `is_del` tinyint(4) NOT NULL DEFAULT '0' COMMENT '该用户是否被管理员删除(0表示正常状态，1表示已删除)',
  `description` varchar(255) NOT NULL DEFAULT '' COMMENT '职位描述',
  `remark` text NOT NULL COMMENT '备注信息、简介',
  `find_code` char(5) DEFAULT '' COMMENT '找回账号验证码',
  `last_login_time` int(11) DEFAULT '0' COMMENT '上次登录时间',
  `last_login_ip` varchar(40) DEFAULT '0' COMMENT '上次登录ip',
  `create_time` int(11) NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(11) NOT NULL DEFAULT '0' COMMENT '更新时间',
  `operator_id` int(11) NOT NULL DEFAULT '0' COMMENT '操作员id',
  `login_count` int(11) NOT NULL DEFAULT '0' COMMENT '用户登录总次数',
  `login_error_log` tinyint(4) NOT NULL DEFAULT '0' COMMENT '登录错误次数log记录（5次锁定）',
  PRIMARY KEY (`aid`)
) ENGINE=MyISAM AUTO_INCREMENT=89020 DEFAULT CHARSET=utf8 COMMENT='网站后台管理员表';

-- ----------------------------
-- Records of td_admin
-- ----------------------------
INSERT INTO `td_admin` VALUES ('88995', 'admin', 'qiangshiluo@126.com', '1230b619ba1871e726a8d760bdbc78dc', 'Admin', '1', '0', 'user100_1686.jpg', '1', '0', '', '1', '0', '', '我其实是站长 哈哈~~', '', '1386662743', '0', '1452702387', '1452702387', '0', '0', '0');
INSERT INTO `td_admin` VALUES ('88997', 'demo01', 'demo01@demo.com', '1230b619ba1871e726a8d760bdbc78dc', 'demo01', '0', '0', 'user100_1956.jpg', '0', '0', '', '1', '0', '', '', '', '0', '0', '1442563484', '1454248086', '88995', '0', '0');
INSERT INTO `td_admin` VALUES ('88998', 'demo02', 'demo02@demo.com', '1230b619ba1871e726a8d760bdbc78dc', 'demo02', '0', '0', 'user100_1161.jpg', '0', '0', '', '0', '1', '', 'wo shi demo02', '', '0', '0', '1442564334', '1452702387', '88995', '0', '0');
INSERT INTO `td_admin` VALUES ('88999', 'Cursor', 'cursor@demo.com', '1230b619ba1871e726a8d760bdbc78dc', 'Cursor', '1', '0', 'user100_1176.jpg', '0', '329786101', '', '1', '0', '', '测试前台用户添加！', '', '0', '0', '1452695898', '1458006344', '88995', '0', '0');
INSERT INTO `td_admin` VALUES ('89000', 'shengxianda', 'shengxianda@eputai.com', '1230b619ba1871e726a8d760bdbc78dc', '阿达', '1', '0', 'user100_1231.jpg', '1377885566', '329786102', '', '1', '0', '', '主管测试帐号', '', '0', '0', '1452696142', '1453701060', '88995', '0', '0');
INSERT INTO `td_admin` VALUES ('89002', 'ceshi02', 'ceshi02@demo.com', '1230b619ba1871e726a8d760bdbc78dc', 'ceshi02', '2', '0', 'user100_1271.jpg', '0', '1452703300', '', '0', '1', '', 'ce', '', '0', '0', '1452707486', '1452703323', '88995', '0', '0');
INSERT INTO `td_admin` VALUES ('89003', 'ceshi01', 'trydemo01@163.com', '8291370568bdfb405a627d4bb3f9ce88', 'ceshi01', '1', '0', 'user100_1301.jpg', '18600632990', '329786101', '', '1', '0', '', '相信每个人踏入广告业时，都怀揣着成功的梦想。这个行业有挑战，在没有客户积累，没有营销经验，不懂广告知识的情况下，做客户很困难。但也有着无限的机遇，只要每一个都仔细做，就一定有发展机会。有的人早早放弃了，毕竟天生的广告高手是少数；有的人凭借执着的信念，坚持不懈，用心书写着成功，梦想的坚持，源于坚持，更源于将心贯穿到每一个细节。\r\n\r\n相信每个人踏入广告业时，都怀揣着成功的梦想。这个行业有挑战，在没有客户积累，没有营销经验，不懂广告知识的情况下，做客户很困难。但也有着无限的机遇，只要每一个都仔细做，就一定有发展机会。有的人早早放弃了，毕竟天生的广告高手是少数；有的人凭借执着的信念，坚持不懈，用心书写着成功，梦想的坚持，源于坚持，更源于将心贯穿到每一个细节。\r\n相信每个人踏入广告业时，都怀揣着成功的梦想。这个行业有挑战，在没有客户积累，没有营销经验，不懂广告知识的情况下，做客户很困难。但也有着无限的机遇，只要每一个都仔细做，就一定有发展机会。有的人早早放弃了，毕竟天生的广告高手是少数；有的人凭借执着的信念，坚持不懈，用心书写着成功，梦想的坚持，源于坚持，更源于将心贯穿到每一个细节。\r\n相信每个人踏入广告业时，都怀揣着成功的梦想。这个行业有挑战，在没有客户积累，没有营销经验，不懂广告知识的情况下，做客户很困难。但也有着无限的机遇，只要每一个都仔细做，就一定有发展机会。有的人早早放弃了，毕竟天生的广告高手是少数；有的人凭借执着的信念，坚持不懈，用心书写着成功，梦想的坚持，源于坚持，更源于将心贯穿到每一个细节。\r\n相信每个人踏入广告业时，都怀揣着成功的梦想。这个行业有挑战，在没有客户积累，没有营销经验，不懂广告知识的情况下，做客户很困难。但也有着无限的机遇，只要每一个都仔细做，就一定有发展机会。有的人早早放弃了，毕竟天生的广告高手是少数；有的人凭借执着的信念，坚持不懈，用心书写着成功，梦想的坚持，源于坚持，更源于将心贯穿到每一个细节。', '', '0', '0', '1452868938', '1461139021', '88995', '0', '0');
INSERT INTO `td_admin` VALUES ('89004', 'ceshi03', 'trydemo03@163.com', 'd8a16b4e7c0aa4ff72b6ec9e29b694b0', 'ceshi03', '0', '0', 'user100_1336.jpg', '0', '0', '', '1', '0', '', '', '', '0', '0', '1452869049', '1453657974', '88995', '0', '0');
INSERT INTO `td_admin` VALUES ('89005', 'ceshi04', 'trydemo04@163.com', '1230b619ba1871e726a8d760bdbc78dc', 'ceshi04', '2', '0', 'user100_1586.jpg', '18600632904', '329786104', '', '1', '0', '小米公司创意总监', '相信每个人踏入广告业时，都怀揣着成功的梦想。这个行业有挑战，在没有客户积累，没有营销经验，不懂广告知识的情况下，做客户很困难。但也有着无限的机遇，只要每一个都仔细做，就一定有发展机会。有的人早早放弃了，毕竟天生的广告高手是少数；有的人凭借执着的信念，坚持不懈，用心书写着成功，梦想的坚持，源于坚持，更源于将心贯穿到每一个细节。<br /><br />相信每个人踏入广告业时，都怀揣着成功的梦想。这个行业有挑战，在没有客户积累，没有营销经验，不懂广告知识的情况下，做客户很困难。但也有着无限的机遇，只要每一个都仔细做，就一定有发展机会。有的人早早放弃了，毕竟天生的广告高手是少数；有的人凭借执着的信念，坚持不懈，用心书写着成功，梦想的坚持，源于坚持，更源于将心贯穿到每一个细节。<br />相信每个人踏入广告业时，都怀揣着成功的梦想。这个行业有挑战，在没有客户积累，没有营销经验，不懂广告知识的情况下，做客户很困难。但也有着无限的机遇，只要每一个都仔细做，就一定有发展机会。有的人早早放弃了，毕竟天生的广告高手是少数；有的人凭借执着的信念，坚持不懈，用心书写着成功，梦想的坚持，源于坚持，更源于将心贯穿到每一个细节。<br />相信每个人踏入广告业时，都怀揣着成功的梦想。这个行业有挑战，在没有客户积累，没有营销经验，不懂广告知识的情况下，做客户很困难。但也有着无限的机遇，只要每一个都仔细做，就一定有发展机会。有的人早早放弃了，毕竟天生的广告高手是少数；有的人凭借执着的信念，坚持不懈，用心书写着成功，梦想的坚持，源于坚持，更源于将心贯穿到每一个细节。<br />相信每个人踏入广告业时，都怀揣着成功的梦想。这个行业有挑战，在没有客户积累，没有营销经验，不懂广告知识的情况下，做客户很困难。但也有着无限的机遇，只要每一个都仔细做，就一定有发展机会。有的人早早放弃了，毕竟天生的广告高手是少数；有的人凭借执着的信念，坚持不懈，用心书写着成功，梦想的坚持，源于坚持，更源于将心贯穿到每一个细节。', '', '0', '0', '1452892388', '1461142020', '88995', '0', '0');
INSERT INTO `td_admin` VALUES ('89006', 'ceshi05', 'trydemo05@163.com', '1230b619ba1871e726a8d760bdbc78dc', 'ceshi05', '0', '0', 'user100_1461.jpg', '0', '0', '', '1', '0', '', '', '', '0', '0', '1453096104', '1453657987', '88995', '0', '0');
INSERT INTO `td_admin` VALUES ('89007', 'ceshi08', 'trydemo08@163.com', '1230b619ba1871e726a8d760bdbc78dc', 'ceshi08', '0', '0', 'user100_1491.jpg', '0', '0', '', '1', '0', '', '', '', '0', '0', '1453274195', '1453274195', '88995', '0', '0');
INSERT INTO `td_admin` VALUES ('89008', 'ceshi06', 'trydemo06@163.com', '1230b619ba1871e726a8d760bdbc78dc', 'ceshi06', '0', '0', 'user100_1471.jpg', '0', '0', '', '1', '0', '', '', '', '0', '0', '1453277310', '1453658004', '88995', '0', '0');
INSERT INTO `td_admin` VALUES ('89009', 'ceshi09', 'trydemo09@163.com', '1230b619ba1871e726a8d760bdbc78dc', 'ceshi09', '1', '0', 'user100_1536.jpg', '13977886954', '123456789', '', '1', '0', '', '9', '', '0', '0', '1453279543', '1453700879', '88995', '0', '0');
INSERT INTO `td_admin` VALUES ('89010', 'ceshi07', 'trydemo07@163.com', '1230b619ba1871e726a8d760bdbc78dc', 'ceshi07', '2', '0', 'user100_1231.jpg', '13865431245', '987654321', '', '1', '0', '', '7', '', '0', '0', '1453279647', '1453700999', '88995', '0', '0');
INSERT INTO `td_admin` VALUES ('89011', 'ceshi010', 'trydemo@163.com', '1230b619ba1871e726a8d760bdbc78dc', 'ceshi010', '0', '0', 'user100_1921.jpg', '0', '0', '', '1', '0', '', '', '', '0', '0', '1453280130', '1453658017', '88995', '0', '0');
INSERT INTO `td_admin` VALUES ('89012', 'ceshi011', 'ceshi011@demo.com', '1230b619ba1871e726a8d760bdbc78dc', 'ceshi011', '1', '0', 'user100_1921.jpg', '18600632997', '1458030707', '', '1', '0', '', 'ceshi011', '', '0', '0', '1458030784', '1458030784', '88995', '0', '0');
INSERT INTO `td_admin` VALUES ('89013', 'ceshi012', 'ceshi012@demo.com', '1230b619ba1871e726a8d760bdbc78dc', 'ceshi012', '0', '0', 'user100_1921.jpg', '0', '1458030894', '', '0', '0', '', '', '', '0', '0', '1458030944', '1458030944', '88995', '0', '0');
INSERT INTO `td_admin` VALUES ('89014', 'ceshi013', 'ceshi013@demo.com', '1230b619ba1871e726a8d760bdbc78dc', 'ceshi013', '0', '0', '56e9006c70747.png', '0', '111223464', '', '0', '0', '', '', '', '0', '0', '1458031063', '1458110572', '88995', '0', '0');
INSERT INTO `td_admin` VALUES ('89015', 'ceshi014', 'ceshi014@demo.com', '1230b619ba1871e726a8d760bdbc78dc', 'ceshi014', '1', '0', '56e7cab914702.png', '0', '2222', '', '1', '0', '', 'ceshi014', '', '0', '0', '1458031289', '1458031289', '88995', '0', '0');
INSERT INTO `td_admin` VALUES ('89016', 'ceshi015', 'ceshi015@demo.com', '1230b619ba1871e726a8d760bdbc78dc', 'ceshi015', '1', '0', '56e7ccc9a0571.png', '0', '3333', '', '1', '0', '', 'ceshi015', '', '0', '0', '1458031817', '1458031817', '88995', '0', '0');
INSERT INTO `td_admin` VALUES ('89017', 'ceshi016', 'ceshi016@demo.com', '1230b619ba1871e726a8d760bdbc78dc', 'ceshi016', '1', '0', '56e7cdba831ea.png', '0', '4444', '', '1', '0', '', '016', '', '0', '0', '1458032058', '1458032058', '88995', '0', '0');
INSERT INTO `td_admin` VALUES ('89018', 'ceshi017', 'ceshi017@demo.com', '1230b619ba1871e726a8d760bdbc78dc', 'ceshi017', '2', '0', '56e900618813d.png', '0', '5555', '', '1', '0', '', '017', '', '0', '0', '1458032124', '1458110561', '88995', '0', '0');
INSERT INTO `td_admin` VALUES ('89019', 'ceshi018', 'ceshi018@demo.com', '1230b619ba1871e726a8d760bdbc78dc', '', '2', '0', '56e7ce2435f0f.png', '0', '666', '', '1', '0', '', '018', '', '0', '0', '1458032164', '1458032164', '88995', '0', '0');

-- ----------------------------
-- Table structure for td_admin_category
-- ----------------------------
DROP TABLE IF EXISTS `td_admin_category`;
CREATE TABLE `td_admin_category` (
  `cid` int(5) NOT NULL AUTO_INCREMENT,
  `pid` int(5) DEFAULT NULL COMMENT 'parentCategory上级分类',
  `name` varchar(20) DEFAULT NULL COMMENT '分类名称',
  PRIMARY KEY (`cid`)
) ENGINE=MyISAM AUTO_INCREMENT=49 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='新闻分类表';

-- ----------------------------
-- Records of td_admin_category
-- ----------------------------
INSERT INTO `td_admin_category` VALUES ('24', '22', '理财资讯');
INSERT INTO `td_admin_category` VALUES ('14', '13', '私募动态');
INSERT INTO `td_admin_category` VALUES ('1', '0', '信托计划');
INSERT INTO `td_admin_category` VALUES ('23', '22', '行业动态');
INSERT INTO `td_admin_category` VALUES ('8', '6', '募资资讯');
INSERT INTO `td_admin_category` VALUES ('2', '1', '行业新闻');
INSERT INTO `td_admin_category` VALUES ('9', '6', '上市资讯');
INSERT INTO `td_admin_category` VALUES ('6', '0', 'PE');
INSERT INTO `td_admin_category` VALUES ('21', '18', '债券公告');
INSERT INTO `td_admin_category` VALUES ('15', '13', '私募人物');
INSERT INTO `td_admin_category` VALUES ('16', '13', '私募视点');
INSERT INTO `td_admin_category` VALUES ('26', '22', '监管动态');
INSERT INTO `td_admin_category` VALUES ('13', '0', '阳光私募');
INSERT INTO `td_admin_category` VALUES ('17', '13', '私募研究');
INSERT INTO `td_admin_category` VALUES ('10', '6', '大佬语录');
INSERT INTO `td_admin_category` VALUES ('12', '6', '投资人生');
INSERT INTO `td_admin_category` VALUES ('27', '0', '券商');
INSERT INTO `td_admin_category` VALUES ('4', '1', '信托渠道');
INSERT INTO `td_admin_category` VALUES ('20', '18', '债市研究');
INSERT INTO `td_admin_category` VALUES ('18', '0', '债券');
INSERT INTO `td_admin_category` VALUES ('25', '22', '观点评论');
INSERT INTO `td_admin_category` VALUES ('5', '1', '行业研究');
INSERT INTO `td_admin_category` VALUES ('11', '6', '投资人物');
INSERT INTO `td_admin_category` VALUES ('28', '27', '行业动态');
INSERT INTO `td_admin_category` VALUES ('30', '27', '行业研究');
INSERT INTO `td_admin_category` VALUES ('22', '0', '银行');
INSERT INTO `td_admin_category` VALUES ('3', '1', '机构动态');
INSERT INTO `td_admin_category` VALUES ('7', '6', '行业动态');
INSERT INTO `td_admin_category` VALUES ('29', '27', '研究动态');
INSERT INTO `td_admin_category` VALUES ('19', '18', '债券要闻');
INSERT INTO `td_admin_category` VALUES ('31', '6', '收购并购');

-- ----------------------------
-- Table structure for td_ad_position
-- ----------------------------
DROP TABLE IF EXISTS `td_ad_position`;
CREATE TABLE `td_ad_position` (
  `position_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `position_name` varchar(60) NOT NULL COMMENT '广告位名称',
  `ad_width` smallint(5) unsigned NOT NULL DEFAULT '0' COMMENT '广告位限制宽度(单位像素）',
  `ad_height` smallint(5) unsigned NOT NULL DEFAULT '0' COMMENT '广告位限制高度(单位像素）',
  `area_code` varchar(255) NOT NULL COMMENT '广告位区域码',
  `position_desc` varchar(255) NOT NULL COMMENT '广告位描述',
  `create_time` int(11) NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(11) NOT NULL DEFAULT '0' COMMENT '上次更新时间',
  PRIMARY KEY (`position_id`)
) ENGINE=MyISAM AUTO_INCREMENT=288 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of td_ad_position
-- ----------------------------
INSERT INTO `td_ad_position` VALUES ('284', '测试广告位02', '800', '200', 'index-one-2', '这仅仅是一个测试！！！', '1449718509', '1449718509');
INSERT INTO `td_ad_position` VALUES ('286', '测试广告位01', '800', '150', 'index-one-1', '测试', '1449730636', '1449730636');
INSERT INTO `td_ad_position` VALUES ('287', '测试广告位05', '850', '180', 'index-one-4', 'test4444444444444', '1449730903', '1449731933');

-- ----------------------------
-- Table structure for td_article
-- ----------------------------
DROP TABLE IF EXISTS `td_article`;
CREATE TABLE `td_article` (
  `id` int(9) unsigned NOT NULL AUTO_INCREMENT COMMENT '博客或者文章id',
  `post_author_id` int(11) NOT NULL DEFAULT '0' COMMENT '发布人id',
  `post_author_name` varchar(50) NOT NULL COMMENT '发布人名称（后台操作人名称）',
  `title` varchar(50) NOT NULL COMMENT '博客或者文章标题',
  `seo_title` varchar(50) NOT NULL COMMENT 'seo标题',
  `seo_desc` varchar(255) NOT NULL COMMENT 'seo描述',
  `thumb` varchar(100) NOT NULL COMMENT '缩略图',
  `description` varchar(1000) NOT NULL COMMENT '描述',
  `author_name` varchar(50) NOT NULL COMMENT '文章作者（可手动填写）',
  `content` text NOT NULL COMMENT '主要内容',
  `cid` mediumint(8) unsigned NOT NULL COMMENT '所属栏目',
  `is_hot` tinyint(4) NOT NULL DEFAULT '0' COMMENT '是否是热门',
  `is_top` tinyint(4) NOT NULL DEFAULT '0' COMMENT '是否是头条',
  `is_skip` tinyint(4) NOT NULL DEFAULT '0' COMMENT '是否跳转',
  `is_recommend` tinyint(4) NOT NULL DEFAULT '0' COMMENT '是否是推荐',
  `flag` varchar(20) NOT NULL COMMENT '自定义属性',
  `clicks` int(9) unsigned NOT NULL COMMENT '点击量',
  `source` varchar(20) NOT NULL COMMENT '文章来源',
  `keywords` varchar(20) NOT NULL COMMENT '关键词',
  `release_time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '发布时间',
  `last_mod_time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '最后修改时间',
  `post_author_ip` varchar(15) NOT NULL COMMENT '发布人ip',
  `comment_status` tinyint(4) NOT NULL DEFAULT '1' COMMENT '是否允许评论、默认状态为1表示开启状态、0表示关闭评论',
  `is_lock` tinyint(4) NOT NULL DEFAULT '0' COMMENT '是否锁定、默认为0表示未锁定、1表示锁定',
  `is_del` tinyint(4) NOT NULL DEFAULT '0' COMMENT '是否删除（放入回收站）、默认为0表示正常使用、1表示已放入回收站',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=18 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Records of td_article
-- ----------------------------
INSERT INTO `td_article` VALUES ('1', '1', 'admin', 'Thinkphp框架在大png图片生成缩略图时的小bug', '', '', '', '<p>今天,一朋友跟我说他在上传大png图片时,生成缩略图有问题,如果是小png图片或是其它格式的图片却是正常的,<br /></p><p>顿觉很奇怪,于是下班后在家里试了下,确实程序会报错：</p>', '', '<p>今天,一朋友跟我说他在上传大png图片时,生成缩略图有问题,如果是小png图片或是其它格式的图片却是正常的,<br /></p><div>顿觉很奇怪,于是下班后在家里试了下,确实程序会报错：<br /><br /><img style=\"width:900px;height:49px;float:none;\" src=\"/ueditor/php/upload/20140112/13895335525892.jpg\" title=\"psb.jpg\" border=\"0\" height=\"49\" hspace=\"0\" vspace=\"0\" width=\"900\" /></div><div><br /></div><div>提示代码199行 有错 </div><div><br /></div><div>如是就根据提示 定位到了 199行 &nbsp;$srcImg = $createFun($image);</div><div><br /></div><div>$createFun是&#39;imagecreatefrompng&#39;,</div><div><br /></div><div>上述代码 也就是 &nbsp; $srcImg =imagecreatefrompng($image);时 出现了错误 </div><div><br /></div><div>报错 的大致意思是(查了下google翻译)说是：在分配19k的内存时 ,8M的内存 已经用完了 ,好奇怪</div><div><br /></div><div>难道是imagecreatefrompng()函数的问题 ,</div><div><br /></div><div><p>如是立马写了下 测试程序 demo.php</p><pre class=\"brush:php;toolbar:false;\">header(\'Content-Type:image/png\');\r\n$url=\"images/cover.png\";\r\n$img=imagecreatefrompng($url);\r\nimagepng($img);\r\nimagedestroy($img);</pre><p>意思是从images目录下 加载 cover.png图片 然后 在浏览器中输出,</p><p>结果出现错误,说是图像本身有错,无法显示 <br /></p></div><div><br /><img src=\"/ueditor/php/upload/20140112/13895336111903.jpg\" title=\"psbjing.jpg\" /></div><div><br /></div><div>然后 我有试了张png小图 ,</div><div>结果显示正常 ,<br /><br /> <img src=\"/ueditor/php/upload/20140112/13895336285701.jpg\" title=\"psingkb.jpg\" /></div><div><br /></div><div>那原因 就很明显了,就是png加载大的png图片时会出现错误,并不是thinkphp框架的问题(本测试用的是thinkphp2.1版本)</div><div><br /></div><div>如是就在百度上 搜imagecreatefrompng加载 大图出错</div><div><br /></div><div>imagecreatefrompng() 在失败时返回一个空字符串，并且输出一条错误信息，不幸地在浏览器中显示为断链接。</div><div><br /></div><div>说是 加载失败会返回空 但是为何失败 </div><div>却没有说 </div><div><br /></div><div>继续往下看 好像解释了为何加载出错的原因 ,但是一大推英文 又看不懂 </div><div><br /></div><div>大致意思好像是说 ：</div><div><br /></div><div>imagecreatefrompng在加载大png图片是 会变态 的消耗内存,</div><div><br /></div><div>结果也不知道是哪位朋友还提供了问题的 解决方案 </div><div><br /></div><div>The approach I used to solve the problem is:</div><div><br /></div><div>1-The following value works for me:</div><div>$required_memory = Round($width * $height * $size[&#39;bits&#39;]);</div><div><br /></div><div>2-Use somthing like:</div><div>$new_limit=memory_get_usage() + $required_memory;</div><div>ini_set(&quot;memory_limit&quot;, $new_limit);</div><div><br /></div><div>4-ini_restore (&quot;memory_limit&quot;);</div><div><br /></div><div>php.ini 默认的内存 是8M </div><div><br /></div><div>加载png图片时 需要的 内存 是 &nbsp;Round($width * $height * $size[&#39;bits&#39;]);</div><div><br /></div><div>我计算了下加载cover.png 时需要的内存 ：4900*3600*8=141120000,</div><div><br /></div><div>141M尼玛这么大 难怪 会 加载不了呢</div><div><br /></div><div>照着这个方法 试了下 果然 解决了问题,</div><div><br /></div><div>在thinkphp框架的 Image.class.php代码$srcImg = $createFun($image);的前面 加上 <br /><br /><pre class=\"brush:php;toolbar:false;\">//考虑png大图内存不足的问题\r\n  \r\n$imgArr=getimagesize($image); \r\n$required_memory = $imgArr[0] * $imgArr[1] * $imgArr[\'bits\'];\r\n$new_limit=memory_get_usage() + $required_memory+200000000;\r\nini_set(\"memory_limit\", $new_limit);</pre></div><div>让程序根据加载图片的大小,自动设置需要的内存,结束之后 </div><div>在thumb函数的结尾 在设置为默认的8M</div><div><pre class=\"brush:php;toolbar:false;\">ini_restore (\"memory_limit\")</pre>至此,问题解决,也算是Thinkphp框架的一个小bug</div><p><br /></p>', '7', '0', '0', '0', '0', '', '15', 'TryDemo个人博客', 'ThinkPHP,框架', '2014-01-12 22:30:01', '2014-01-13 22:04:16', '127.0.0.1', '1', '0', '0');
INSERT INTO `td_article` VALUES ('2', '1', 'admin', 'PHP单例模式浅析', '', '', '', '<div><span style=\"font-size:16px\">首先我们要明确单例模式这个概念，那么什么是单例模式呢?</span></div><div><span style=\"font-size:16px\">单例模式顾名思义，就是只有一个实例。</span></div><div><span style=\"font-size:16px\">作为对象的创建模式， 单例模式确保某一个类只有一个实例，而且自行实例化并向整个系统提供这个实例，</span></div><div><span style=\"font-size:16px\">这个类我们称之为单例类。</span></div>', '', '<div id=\"blogDetailDiv\" style=\"font-size:14px;color:#000000;\"><div class=\"blog_details_20120222\"><div><div><span style=\"font-size:16px\">首先我们要明确单例模式这个概念，那么什么是单例模式呢?</span></div><div><span style=\"font-size:16px\">单例模式顾名思义，就是只有一个实例。</span></div><div><span style=\"font-size:16px\">作为对象的创建模式， 单例模式确保某一个类只有一个实例，而且自行实例化并向整个系统提供这个实例，</span></div><div><span style=\"font-size:16px\">这个类我们称之为单例类。</span></div><div><br /></div><div><span style=\"font-size:16px\">单例模式的要点有三个：</span></div><div><span style=\"font-size:16px\"> &nbsp; &nbsp;它们必须拥有一个构造函数，并且必须被标记为private</span></div><div><span style=\"font-size:16px\"> &nbsp; &nbsp;它们拥有一个保存类的实例的静态成员变量</span></div><div><span style=\"font-size:16px\"> &nbsp; &nbsp;它们拥有一个访问这个实例的公共的静态方法</span></div><div><br /></div><div><span style=\"font-size:16px\">和普通类不同的是，单例类不能在其他类中直接实例化。单例类只能被其自身实例化。要获得这样的一种结果， __construct()方法必须被标记为private。如果试图用private构造函数构造一个类，就会得到一个可访问性级别的错误。</span></div><div><span style=\"font-size:16px\">要让单例类起作用，就必须使其为其他类提供一个实例，用它调用各种方法。单例类不会创建实例副本，而是会向单例类内部存储的实例返回一个引用。结果是单例类不会重复占用内存和系统资源，从而让应用程序的其它部分更好地使用这些资源。作为这一模式的一部分，必须创建一个空的私有__clone()方法，以防止对象被复制或克隆。</span></div><div><p><span style=\"font-size:16px\">返回实例引用的这个方法通常被命名为getInstance()。这个方法必须是静态的，而且如果它还没有实例化，就必须进行实例化。getInstance() 方法通过使用 instanceof 操作符和self 关键字，可以检测到类是否已经被实例化。</span></p><pre class=\"brush:html;toolbar:false;\">header(\"Content-type:text/html;charset=utf-8\");\r\n//单例测试类\r\nclass Test {\r\n    private $unique;\r\n    static private $instance;//静态属性保存该类实例\r\n      \r\n    private function __construct(){//构造方法私有(防止外界调用)\r\n        $this-&gt;unique=rand(0,20000);\r\n    }\r\n    static public function getInstance(){//静态方法提供对外接口(获取实例)\r\n        if(!self::$instance instanceof self){\r\n            self::$instance=new self();\r\n        }\r\n        return self::$instance;\r\n    }\r\n    private function __clone(){}//私有克隆方法,防止外界直接克隆该实例\r\n     \r\n}\r\n$test=Test::getInstance();\r\n$test2=Test::getInstance();\r\n     \r\nprint_r($test); \r\nprint_r($test2);\r\n     \r\nif($test===$test2){\r\n    echo \'相等!\';\r\n}else{\r\n    echo \'不相等!\';\r\n}</pre><p>结果：<br /></p><span style=\"font-size:16px\"></span><span style=\"font-size:16px\"></span><p><img src=\"/ueditor/php/upload/20140112/13895353281735.jpg\" style=\"float:none;\" title=\"psb.jpgngjan.jpg\" /></p><span style=\"font-size:16px\"> </span></div></div></div></div>', '1', '0', '0', '0', '0', '', '713', 'TryDemo个人博客', 'PHP,单例模式', '2014-01-12 22:34:29', '2014-01-13 22:04:39', '127.0.0.1', '1', '0', '0');
INSERT INTO `td_article` VALUES ('14', '88995', 'admin', 'PHP版单点登陆实现方案', 'PHP版单点登陆实现方案', 'PHP版单点登陆实现方案', '', '本文主要介绍了利用webservice,session,cookie技术，来进行通用的单点登录系统的分析与设计。具体实现语言为PHP。单点 登录，英文名为Single Sign On，简称为 SSO，是目前企业，网络业务的用户综合处理的重要组成部分。而SSO的定义，是在多个应用系统中，用户只需要登陆一次就可以访问所有相互信任的应用系统。', 'TryDemo', '<p style=\"text-indent:0px;color:#000000;font-family:微软雅黑, 宋体, Arial;font-size:15px;font-style:normal;font-weight:normal;text-align:start;background-color:#FFFFFF;\">\r\n	摘要：\r\n</p>\r\n<p style=\"text-indent:0px;color:#000000;font-family:微软雅黑, 宋体, Arial;font-size:15px;font-style:normal;font-weight:normal;text-align:start;background-color:#FFFFFF;\">\r\n	本文主要介绍了利用webservice,session,cookie技术，来进行通用的单点登录系统的分析与设计。具体实现语言为PHP。单点 登录，英文名为Single Sign On，简称为 SSO，是目前企业，网络业务的用户综合处理的重要组成部分。而SSO的定义，是在多个应用系统中，用户只需要登陆一次就可以访问所有相互信任的应用系统。\r\n</p>\r\n<p style=\"text-indent:0px;color:#000000;font-family:微软雅黑, 宋体, Arial;font-size:15px;font-style:normal;font-weight:normal;text-align:start;background-color:#FFFFFF;\">\r\n	动机：\r\n</p>\r\n<p style=\"text-indent:0px;color:#000000;font-family:微软雅黑, 宋体, Arial;font-size:15px;font-style:normal;font-weight:normal;text-align:start;background-color:#FFFFFF;\">\r\n	用过ucenter的全站登录方式的朋友，应该都知道这是典型的观察者模式的解决方案。用户中心作为subject, 其所属observer的注册和删除统一在ucenter的后台进行。而各个子应用站点都对应一个observer。每次用户中心的登录动作，都会触发 js脚本回调w3c标准的子站登录接口(api/uc.php)。\r\n</p>\r\n<p style=\"text-indent:0px;color:#000000;font-family:微软雅黑, 宋体, Arial;font-size:15px;font-style:normal;font-weight:normal;text-align:start;background-color:#FFFFFF;\">\r\n	这种方式的缺点，本人认为主要是两点：1. 子站点过多时，回调接口相应增多，这个在分布子站的量的限制上，如何控制来使登录效率不会太低，不好把握; 2. 当某个子站回调接口出现问题时，默认的登录过程会卡住(可以限制登录程序的执行时间，但相应出现问题子站后面的子站的回调接口就调不到了。\r\n</p>\r\n<p style=\"text-indent:0px;color:#000000;font-family:微软雅黑, 宋体, Arial;font-size:15px;font-style:normal;font-weight:normal;text-align:start;background-color:#FFFFFF;\">\r\n	基于以上问题，在实际开发过程中，本人设计了另一套单点登录系统。\r\n</p>\r\n<p style=\"text-indent:0px;color:#000000;font-family:微软雅黑, 宋体, Arial;font-size:15px;font-style:normal;font-weight:normal;text-align:start;background-color:#FFFFFF;\">\r\n	一. 登陆原理说明\r\n</p>\r\n<p style=\"text-indent:0px;color:#000000;font-family:微软雅黑, 宋体, Arial;font-size:15px;font-style:normal;font-weight:normal;text-align:start;background-color:#FFFFFF;\">\r\n	单点登录的技术实现机制：当用户第一次访问应用系统1的时候，因为还没有登录，会被引导到认证系统中进行登录;根据用户提供的登录信息，认证系统进行身份效验，如果通过效验，应该返回给用户一个认证的凭据--ticket;用户再访问别的应用的时候，就会将这个ticket带上，作为自己认证的凭据，应用系统接受到请求之后会把ticket送到认证系统进行效验，检查ticket的合法性。如果通过效验，用户就可以在不用再次登录的情况下访问应用系统2和应用系统3了。\r\n</p>\r\n<p style=\"text-indent:0px;color:#000000;font-family:微软雅黑, 宋体, Arial;font-size:15px;font-style:normal;font-weight:normal;text-align:start;background-color:#FFFFFF;\">\r\n	可以看出，要实现SSO，需要以下主要的功能：\r\n</p>\r\n<p style=\"text-indent:0px;color:#000000;font-family:微软雅黑, 宋体, Arial;font-size:15px;font-style:normal;font-weight:normal;text-align:start;background-color:#FFFFFF;\">\r\n	a) 所有应用系统共享一个身份认证系统;\r\n</p>\r\n<p style=\"text-indent:0px;color:#000000;font-family:微软雅黑, 宋体, Arial;font-size:15px;font-style:normal;font-weight:normal;text-align:start;background-color:#FFFFFF;\">\r\n	b) 所有应用系统能够识别和提取ticket信息;\r\n</p>\r\n<p style=\"text-indent:0px;color:#000000;font-family:微软雅黑, 宋体, Arial;font-size:15px;font-style:normal;font-weight:normal;text-align:start;background-color:#FFFFFF;\">\r\n	c) 应用系统能够识别已经登录过的用户，能自动判断当前用户是否登录过，从而完成单点登录的功能\r\n</p>\r\n<p style=\"text-indent:0px;color:#000000;font-family:微软雅黑, 宋体, Arial;font-size:15px;font-style:normal;font-weight:normal;text-align:start;background-color:#FFFFFF;\">\r\n	基于以上基本原则，本人用php语言设计了一套单点登录系统的程序，目前已投入正式生成服务器运行。本系统程序，将ticket信息以全系统唯一的 session id作为媒介，从而获取当前在线用户的全站信息(登陆状态信息及其他需要处理的用户全站信息)。\r\n</p>\r\n<p style=\"text-indent:0px;color:#000000;font-family:微软雅黑, 宋体, Arial;font-size:15px;font-style:normal;font-weight:normal;text-align:start;background-color:#FFFFFF;\">\r\n	二. 过程说明：\r\n</p>\r\n<p style=\"text-indent:0px;color:#000000;font-family:微软雅黑, 宋体, Arial;font-size:15px;font-style:normal;font-weight:normal;text-align:start;background-color:#FFFFFF;\">\r\n	登陆流程:\r\n</p>\r\n<p style=\"text-indent:0px;color:#000000;font-family:微软雅黑, 宋体, Arial;font-size:15px;font-style:normal;font-weight:normal;text-align:start;background-color:#FFFFFF;\">\r\n	1. 第一次登陆某个站：\r\n</p>\r\n<p style=\"text-indent:0px;color:#000000;font-family:微软雅黑, 宋体, Arial;font-size:15px;font-style:normal;font-weight:normal;text-align:start;background-color:#FFFFFF;\">\r\n	a) 用户输入用户名+密码,向用户验证中心发送登录请求\r\n</p>\r\n<p style=\"text-indent:0px;color:#000000;font-family:微软雅黑, 宋体, Arial;font-size:15px;font-style:normal;font-weight:normal;text-align:start;background-color:#FFFFFF;\">\r\n	b) 当前登录站点，通过webservice请求,用户验证中心验证用户名，密码的合法性。如果验证通过，则生成ticket，用于标识当前会话的用户，并将当前登陆子站的站点标识符记录到用户中心，最后\r\n</p>\r\n<p style=\"text-indent:0px;color:#000000;font-family:微软雅黑, 宋体, Arial;font-size:15px;font-style:normal;font-weight:normal;text-align:start;background-color:#FFFFFF;\">\r\n	c) 将获取的用户数据和ticket返回给子站。如果验证不通过，则返回相应的错误状态码。\r\n</p>\r\n<p style=\"text-indent:0px;color:#000000;font-family:微软雅黑, 宋体, Arial;font-size:15px;font-style:normal;font-weight:normal;text-align:start;background-color:#FFFFFF;\">\r\n	d) 根据上一步的webservice请求返回的结果，当前子站对用户进行登陆处理：如状态码表示成功的话，则当前站点通过本站cookie保存ticket，并本站记录用户的登录状态。状态码表示失败的话，则给用户相应的登录失败提示。\r\n</p>\r\n<p style=\"text-indent:0px;color:#000000;font-family:微软雅黑, 宋体, Arial;font-size:15px;font-style:normal;font-weight:normal;text-align:start;background-color:#FFFFFF;\">\r\n	2. 登陆状态下，用户转到另一子：\r\n</p>\r\n<p style=\"text-indent:0px;color:#000000;font-family:微软雅黑, 宋体, Arial;font-size:15px;font-style:normal;font-weight:normal;text-align:start;background-color:#FFFFFF;\">\r\n	a) 通过本站cookie或session验证用户的登录状态：如验证通过，进入正常本站处理程序;否则户中心验证用户的登录状态(发送ticket到用户验证中心)，如验证通过，则对返回的用户信息进行本地的登录处理，否则表明用户未登录。\r\n</p>\r\n<p style=\"text-indent:0px;color:#000000;font-family:微软雅黑, 宋体, Arial;font-size:15px;font-style:normal;font-weight:normal;text-align:start;background-color:#FFFFFF;\">\r\n	登出流程\r\n</p>\r\n<p style=\"text-indent:0px;color:#000000;font-family:微软雅黑, 宋体, Arial;font-size:15px;font-style:normal;font-weight:normal;text-align:start;background-color:#FFFFFF;\">\r\n	a) 当前登出站清除用户本站的登录状态 和 本地保存的用户全站唯一的随机id\r\n</p>\r\n<p style=\"text-indent:0px;color:#000000;font-family:微软雅黑, 宋体, Arial;font-size:15px;font-style:normal;font-weight:normal;text-align:start;background-color:#FFFFFF;\">\r\n	b) 通过webservice接口，清除全站记录的全站唯一的随机id。webservice接口会返回，登出其他已登录子站的javascript代码，本站输出此代码。\r\n</p>\r\n<p style=\"text-indent:0px;color:#000000;font-family:微软雅黑, 宋体, Arial;font-size:15px;font-style:normal;font-weight:normal;text-align:start;background-color:#FFFFFF;\">\r\n	c) js代码访问相应站W3C标准的登出脚本\r\n</p>\r\n<p style=\"text-indent:0px;color:#000000;font-family:微软雅黑, 宋体, Arial;font-size:15px;font-style:normal;font-weight:normal;text-align:start;background-color:#FFFFFF;\">\r\n	三. 代码说明：\r\n</p>\r\n<p style=\"text-indent:0px;color:#000000;font-family:微软雅黑, 宋体, Arial;font-size:15px;font-style:normal;font-weight:normal;text-align:start;background-color:#FFFFFF;\">\r\n	本文所涉及到相关代码，已打包上传，如有兴趣，可在本文最后下载链接处点击下载。\r\n</p>\r\n<p style=\"text-indent:0px;color:#000000;font-family:微软雅黑, 宋体, Arial;font-size:15px;font-style:normal;font-weight:normal;text-align:start;background-color:#FFFFFF;\">\r\n	1. 登陆流程：\r\n</p>\r\n<p style=\"text-indent:0px;color:#000000;font-family:微软雅黑, 宋体, Arial;font-size:15px;font-style:normal;font-weight:normal;text-align:start;background-color:#FFFFFF;\">\r\n	用户从打开浏览器开始，第一个登陆的子站点，必须调用UClientSSO::loginSSO()方法。该方法返回全站唯一的随机id用于标识该用户。该随机id在UClientSSO::loginSSO()中已通过本站cookie保存，即该子站点保留了用户已登陆标识的存根于本站。\r\n</p>\r\n<p style=\"text-indent:0px;color:#000000;font-family:微软雅黑, 宋体, Arial;font-size:15px;font-style:normal;font-weight:normal;text-align:start;background-color:#FFFFFF;\">\r\n	&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; a) UClientSSO::loginSSO()方法如下：\r\n</p>\r\n<p style=\"text-indent:0px;color:#000000;font-family:微软雅黑, 宋体, Arial;font-size:15px;font-style:normal;font-weight:normal;text-align:start;background-color:#FFFFFF;\">\r\n	<br />\r\n</p>\r\n<pre class=\"prettyprint lang-php\">1 &lt;?php\r\n  2 /**\r\n  3  * 用户验证中心 登陆用户处理\r\n  4  *\r\n  5  * @param string $username      - 用户名\r\n  6  * @param string $password      - 用户原始密码\r\n  7  * @param boolean $remember     - 是否永久记住登陆账号\r\n  8  * @param boolean $alreadyEnc   - 传入的密码是否已经经过simpleEncPass加密过\r\n  9  *\r\n 10  * @return array   - integer $return[\'status\'] 大于 0:返回用户 ID，表示用户登录成功\r\n 11  *                                                -1:用户不存在，或者被删除\r\n 12  *                                                -2:密码错\r\n 13  *                                                                                                  -11：验证码错误\r\n 14  *                                          string $return[\'username\']     : 用户名\r\n 15  *                                          string $return[\'password\']     : 密码\r\n 16  *                                          string $return[\'email\']        : Email\r\n 17  */\r\n 18 \r\n 19 static public function loginSSO($username, $password, $remember=false, $alreadyEnc=false) {\r\n 20 self::_init();\r\n 21 self::_removeLocalSid();\r\n 22 $ret = array();\r\n 23 \r\n 24 //\r\n 25 //1. 处理传入webservice接口的参数\r\n 26 //\r\n 27 $_params  = array(\r\n 28                 \'username\' =&gt; $username,\r\n 29                 \'password\' =&gt; $alreadyEnc ? trim($password) : self::simpleEncPass(trim($password)),\r\n 30                 \'ip\'       =&gt; self::onlineip(),\r\n 31                 \'siteFlag\' =&gt; self::$site,\r\n 32                 \'remember\' =&gt; $remember\r\n 33 );\r\n 34 $_params[\'checksum\'] = self::_getCheckSum($_params[\'username\'] . $_params[\'password\'] .\r\n 35 $_params[\'ip\'] . $_params[\'siteFlag\'] . $_params[\'remember\']);\r\n 36 \r\n 37 //\r\n 38 // 2.调用webservice接口，进行登陆处理\r\n 39 //\r\n 40 $aRet = self::_callSoap(\'loginUCenter\', $_params);\r\n 41 \r\n 42 if (intval($aRet[\'resultFlag\']) &gt; 0 &amp;&amp; $aRet[\'sessID\']) {\r\n 43 //成功登陆\r\n 44 //设置本地session id\r\n 45 self::_setLocalSid($aRet[\'sessID\']);\r\n 46 \r\n 47 //设置用户中心的统一session id脚本路径\r\n 48 self::$_synloginScript = urldecode($aRet[\'script\']);\r\n 49 \r\n 50 $ret = $aRet[\'userinfo\'];\r\n 51 } else {\r\n 52 \r\n 53 $ret[\'status\'] = $aRet[\'resultFlag\'];\r\n 54 }\r\n 55 \r\n 56 return $ret;\r\n 57 }//end of function                   [/php]\r\n 58 \r\n 59 b) 用户验证中心的webservice服务程序，接收到登陆验证请求后，调用UCenter::loginUCenter()方法来处理登陆请求。\r\n 60 [php]/**\r\n 61 * 用户验证中心 登陆用户处理\r\n 62 *\r\n 63 * @param string $username\r\n 64 * @param string $password\r\n 65 * @param string $ip\r\n 66 * @param string $checksum\r\n 67 * @return array\r\n 68 */\r\n 69 static public function loginUCenter($username, $password, $ip, $siteFlag, $remember=false) {\r\n 70 self::_init();\r\n 71 session_start();\r\n 72 $ret = array();\r\n 73 $arr_login_res     = login_user($username, $password, $ip);\r\n 74 $res_login         = $arr_login_res[\'status\'];                //\r\n 75 $ret[\'resultFlag\'] = $res_login;\r\n 76 \r\n 77 if ($res_login &lt; 1) {\r\n 78 //登陆失败\r\n 79 } else {\r\n 80 \r\n 81 //登陆成功\r\n 82 $_SESSION[self::$_ucSessKey] = $arr_login_res;\r\n 83 \r\n 84 $_SESSION[self::$_ucSessKey][\'salt\'] =\r\n 85 self::_getUserPassSalt($_SESSION[self::$_ucSessKey][\'username\'], $_SESSION[self::$_ucSessKey][\'password\']);\r\n 86 \r\n 87 $ret[\'userinfo\'] = $_SESSION[self::$_ucSessKey];\r\n 88 $ret[\'sessID\']   = session_id();        //生成全站的唯一session id，作为ticket全站通行\r\n 89 \r\n 90 //\r\n 91 //合作中心站回调登陆接口(设置用户中心的统一session id)\r\n 92 //\r\n 93 self::_createCoSitesInfo();\r\n 94 $uinfo = array();\r\n 95 $_timestamp = time();\r\n 96 $_rawCode = array(\r\n 97                         \'action\' =&gt; \'setSid\',\r\n 98                         \'sid\'    =&gt; $ret[\'sessID\'],\r\n 99                         \'time\'   =&gt; $_timestamp,\r\n100 );\r\n101 if ($remember) {\r\n102 $uinfo = array(\r\n103                                 \'remember\' =&gt; 1,\r\n104                                 \'username\' =&gt; $username,\r\n105                                 \'password\' =&gt; $password\r\n106 );\r\n107 }\r\n108 \r\n109 $ret[\'script\'] = \'\';\r\n110 $_rawStr = http_build_query(array_merge($_rawCode, $uinfo));\r\n111 \r\n112 //\r\n113 // 合作站点的全域cookie设置脚本地址\r\n114 //\r\n115 foreach ((array)self::$_coSitesInfo as $_siteInfo) {\r\n116 $_code = self::authcode($_rawStr, \'ENCODE\', $_siteInfo[\'key\']);\r\n117 $_src = $_siteInfo[\'url\'] . \'?code=\' . $_code . \'&amp;time=\' . $_timestamp;\r\n118 $ret[\'script\'] .= urlencode(\'\');\r\n119 }\r\n120 \r\n121 //\r\n122 // 记住已登陆战\r\n123 //\r\n124 self::registerLoggedSite($siteFlag, $ret[\'sessID\']);\r\n125 \r\n126 unset($ret[\'userinfo\'][\'salt\']);\r\n127 }\r\n128 \r\n129 return $ret;\r\n130 }\r\n131 \r\n132 ?&gt;</pre>\r\n<p>\r\n	<br />\r\n</p>\r\n<p style=\"text-indent:0px;color:#000000;font-family:微软雅黑, 宋体, Arial;font-size:15px;font-style:normal;font-weight:normal;text-align:start;background-color:#FFFFFF;\">\r\n	2. 本站登陆成功后，进行本地化的用户登陆处理，其后验证用户是否登陆只在本地验证。（本地存取登陆用户状态的信息，请设置为关闭浏览器就退出）\r\n</p>\r\n<p style=\"text-indent:0px;color:#000000;font-family:微软雅黑, 宋体, Arial;font-size:15px;font-style:normal;font-weight:normal;text-align:start;background-color:#FFFFFF;\">\r\n	3. 当检测用户登陆状态时，请先调用本地的验证处理，若本地验证不通过，再调用UClientSSO::checkUserLogin()方法到用户中心检测用户的登陆状态。\r\n</p>\r\n<p style=\"text-indent:0px;color:#000000;font-family:微软雅黑, 宋体, Arial;font-size:15px;font-style:normal;font-weight:normal;text-align:start;background-color:#FFFFFF;\">\r\n	a) UClientSSO::checkUserLogin()方法如下：\r\n</p>\r\n<pre class=\"prettyprint lang-php\">&lt;?php\r\n/**\r\n* 全站单点登出\r\n*  - 通过webservice请求注销掉用户的全站唯一标识\r\n*\r\n* @return integer    1: 成功\r\n*                                     -11：验证码错误\r\n*/\r\npublic static function logoutSSO(){\r\n        self::_init();\r\n        $_sessId = self::_getLocalSid();\r\n\r\n        //\r\n        //本站没有登陆的话，不让同步登出其他站\r\n        //\r\n        if (empty($_sessId)) {\r\n                self::_initSess(true);\r\n                return false;\r\n        }\r\n        $_params  = array(\r\n                \'sessId\'   =&gt; $_sessId,\r\n                \'siteFlag\' =&gt; self::$site,\r\n                \'checksum\' =&gt; md5($_sessId . self::$site . self::$_mcComunicationKey)\r\n        );\r\n\r\n        $aRet = self::_callSoap(\'logoutUCenter\', $_params);\r\n        if (intval($aRet[\'resultFlag\']) &gt; 0) {\r\n                //成功登出\r\n                self::_removeLocalSid();                //移除本站记录的sid存根\r\n                self::$_synlogoutScript = urldecode($aRet[\'script\']);\r\n                $ret = 1;\r\n        } else {\r\n                $ret = $aRet[\'resultFlag\'];\r\n        }\r\n        return intval($ret);\r\n}                   [/php]\r\n\r\n        b) 用户验证中心的webservice服务程序，接收到全站登出请求后，调用UCenter::loginUCenter()方法来处理登陆请求：\r\n[php]/**\r\n* 登出全站处理\r\n*\r\n* @param string - 全站唯一session id，用做ticket\r\n* @return boolean\r\n*/\r\nstatic public function logoutUCenter($sessId) {\r\n        self::_init();\r\n        session_id(trim($sessId));\r\n        session_start();\r\n\r\n        $_SESSION = array();\r\n        return empty($_SESSION) ? true : false;\r\n}\r\n?&gt;</pre>\r\n<p>\r\n	四. 代码部署：&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <br />\r\n&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 1. 用户验证中心设置&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <br />\r\n&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; a) 用户验证中心向分站提供的webservice服务接口文件，即UserSvc.php部署在hostname/webapps/port/ UserSvc.php中。查看wsdl内容，请访问<a href=\"https://hostname/port/\">https://hostname/port/</a> UserSvc.php?wsdl<br />\r\n&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;\r\n b) 用户中心用户单点服务类文件为UCenterSSO.class.php，文件路径为在hostname/webapps/include \r\n/UCenterSSO.class.php。该文件为用户单点登陆处理 的服务端类，被hostname/webapps/port/ \r\nUserSvc.php调用。用于获取用户的登陆信息，是否单点登陆的状态信息，单点登出处理等。<br />\r\n&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; c) 用户验证中心通过W3C标准，利用cookie方式记录，删除全站统一的用户唯一随机id 的脚本文件为hostname/webapps/port/cookie_mgr.php.<br />\r\n&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <br />\r\n&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 2. 子站点设置&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <br />\r\n&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; a) 各子站点请将，UClientSSO.class.php部署在用户中心服务客户端目录下。部署好后，请修改最后一行的UClientSSO::setSite(\'1\'); 参数值为用户验证中心统一分配给各站的标识id.<br />\r\n&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; b) 在部署的用户中心服务客户端包下的api目录下下，请将logout_sso.php脚本转移到此处，并编写进行本站登出的处理脚本。<br />\r\n&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; c) 在子站点验证用户登陆状态的代码部分，额外增加到用户中心的单点登陆验证的处理。<br />\r\n&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 即在首先通过本站验证用户的登陆状态，如果未通过验证，则去用户中心验证。验证操作要调用UClientSSO::checkUserLogin();接口，接口含义请查看代码注释。<br />\r\n&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; d) 在分站的登出处理脚本中，通过UClientSSO::getSynlogoutScript();获取script串输出即可。\r\n</p>\r\n<p>\r\n	五. 扩展功能:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <br />\r\n&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 1. 记录跟踪所有在线用户<br />\r\n&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 因为所有用户的登录都要经过用户验证中心，所有用户的ticket都在验证中心生成，可以将用户和该ticket(session id)在内存表中建立一个映射表。得到所有在线用户的记录表。<br />\r\n&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 后期如有必要跟踪用户状态来实现其他功能，只要跟踪这个映射表就可以了。其他功能可以为: 获取在线用户列表，判断用户在线状态，获取在线用户人数等。\r\n</p>\r\n<p>\r\n	&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 2. 特殊统计处理<br />\r\n&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 因为整个系统登录登出要经过用户验证中心，所以可以针对用户的特殊统计进行处理。如用户每天的登录次数，登陆时间，登陆状态失效时间，各时段的在线用户人数走势等。\r\n</p>\r\n<p>\r\n	六. 其他事项：&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <br />\r\n&nbsp;&nbsp;&nbsp;&nbsp; 1. 本站登陆状态有效时间问题：&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <br />\r\n&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 全站要求用户登陆状态在关闭浏览器时就失效。要求各分站对session或cookie的处理方式按照如下进行：<br />\r\n&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; a) Session方式记录用户登陆状态的站点<br />\r\n&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 请在站点公用脚本开始处，添加一下代码\r\n</p>\r\n<pre class=\"prettyprint lang-php\">1 &lt;?php\r\n2 session_write_close();\r\n3 ini_set(\'session.auto_start\', 0);                    //关闭session自动启动\r\n4 ini_set(\'session.cookie_lifetime\', 0);            //设置session在浏览器关闭时失效\r\n5 ini_set(\'session.gc_maxlifetime\', 3600);  //session在浏览器未关闭时的持续存活时间     \r\n6 ?&gt;</pre>\r\n&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; b) cookie方式记录用户登陆状态的站点<br />\r\n&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 请在设置用户登陆状态的cookie时，设置cookie有效时间为null.<br />', '1', '0', '0', '0', '1', '', '10', 'http://www.cnblogs.c', 'PHP版单点登陆实现方案', '2015-11-25 14:46:21', '2015-11-26 10:08:11', '0.0.0.0', '1', '0', '0');
INSERT INTO `td_article` VALUES ('16', '88995', 'admin', '测试文章', '测试文章', '测试文章', '', '测试文章', '测试文章', '测试文章', '1', '0', '0', '0', '1', '', '0', '测试文章', '测试文章', '2015-11-26 10:04:19', '2015-11-26 10:04:19', '0.0.0.0', '1', '0', '0');
INSERT INTO `td_article` VALUES ('17', '88995', 'admin', 'test', 'test', 'test', '', 'test', 'test', 'test', '1', '0', '0', '0', '0', '', '1', 'test', 'test', '2015-11-27 09:28:57', '2015-11-27 09:28:57', '0.0.0.0', '1', '1', '1');

-- ----------------------------
-- Table structure for td_auth_code
-- ----------------------------
DROP TABLE IF EXISTS `td_auth_code`;
CREATE TABLE `td_auth_code` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'id',
  `type` tinyint(4) NOT NULL DEFAULT '0' COMMENT '发送类型（默认是0，1表示邮件，2表示短信，3表示QQ）',
  `content` varchar(255) NOT NULL COMMENT '内容',
  `mobile_number` varchar(18) NOT NULL DEFAULT '' COMMENT '手机号码',
  `email` varchar(255) NOT NULL COMMENT '邮箱',
  `qq` int(11) NOT NULL DEFAULT '0' COMMENT 'QQ号码',
  `ext` varchar(255) NOT NULL DEFAULT '' COMMENT '扩展码',
  `timer` int(11) NOT NULL DEFAULT '0' COMMENT '定时发送时间',
  `code_charset` varchar(20) NOT NULL DEFAULT '' COMMENT '编码格式',
  `interface_url` varchar(255) NOT NULL DEFAULT '' COMMENT '接口地址',
  `receipt_status` varchar(255) NOT NULL DEFAULT '' COMMENT '回执',
  `create_time` int(11) NOT NULL DEFAULT '0' COMMENT '创建时间',
  `failure_time` int(11) NOT NULL DEFAULT '0' COMMENT '失效时长（单位秒）',
  `is_del` tinyint(4) NOT NULL DEFAULT '0' COMMENT '是否删除(默认是0未删除，1是删除)',
  `user_id` int(11) NOT NULL DEFAULT '0' COMMENT '操作人ID',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=17 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of td_auth_code
-- ----------------------------
INSERT INTO `td_auth_code` VALUES ('1', '1', 'pv8t', '0', 'trydemo@163.com', '0', '0', '0', 'utf8', '', '', '1452889951', '0', '0', '0');
INSERT INTO `td_auth_code` VALUES ('2', '1', 'lmu7', '0', 'trydemo@163.com', '0', '0', '0', 'utf8', '', '', '1452890140', '600', '0', '0');
INSERT INTO `td_auth_code` VALUES ('3', '1', '0g63', '0', 'trydemo@163.com', '0', '0', '0', 'utf8', '', '', '1452891842', '600', '0', '0');
INSERT INTO `td_auth_code` VALUES ('4', '1', 'ra2f', '0', 'trydemo@163.com', '0', '0', '0', 'utf8', '', '', '1452891961', '600', '0', '0');
INSERT INTO `td_auth_code` VALUES ('5', '1', 'o5ju', '0', 'trydemo@163.com', '0', '0', '0', 'utf8', '', '', '1452892028', '600', '0', '0');
INSERT INTO `td_auth_code` VALUES ('6', '1', '4rmh', '0', 'trydemo@163.com', '0', '0', '0', 'utf8', '', '', '1452892058', '600', '0', '0');
INSERT INTO `td_auth_code` VALUES ('7', '1', 'k3q7', '0', 'trydemo@163.com', '0', '0', '0', 'utf8', '', '', '1452892181', '600', '0', '0');
INSERT INTO `td_auth_code` VALUES ('8', '1', 'vxjf', '0', 'trydemo@163.com', '0', '0', '0', 'utf8', '', '', '1452892377', '600', '0', '0');
INSERT INTO `td_auth_code` VALUES ('9', '1', 'ufed', '0', 'trydemo04@163.com', '0', '0', '0', 'utf8', '', '', '1452900110', '600', '0', '0');
INSERT INTO `td_auth_code` VALUES ('10', '1', '9vzs', '0', 'trydemo04@163.com', '0', '0', '0', 'utf8', '', '', '1452900113', '600', '0', '0');
INSERT INTO `td_auth_code` VALUES ('11', '1', 'w58c', '0', 'trydemo04@163.com', '0', '0', '0', 'utf8', '', '', '1452900113', '600', '0', '0');
INSERT INTO `td_auth_code` VALUES ('12', '1', 't4ew', '0', 'trydemo04@163.com', '0', '0', '0', 'utf8', '', '', '1452900114', '600', '0', '0');
INSERT INTO `td_auth_code` VALUES ('13', '1', '69hw', '0', 'trydemo04@163.com', '0', '0', '0', 'utf8', '', '', '1452900114', '600', '0', '0');
INSERT INTO `td_auth_code` VALUES ('14', '1', 'iqzh', '0', 'trydemo04@163.com', '0', '0', '0', 'utf8', '', '', '1452900114', '600', '0', '0');
INSERT INTO `td_auth_code` VALUES ('15', '1', 'uz7j', '0', 'trydemo@163.com', '0', '0', '0', 'utf8', '', '', '1452900491', '600', '0', '0');
INSERT INTO `td_auth_code` VALUES ('16', '1', '1iq5', '0', 'trydemo@163.com', '0', '0', '0', 'utf8', '', '', '1453096087', '600', '0', '0');

-- ----------------------------
-- Table structure for td_business
-- ----------------------------
DROP TABLE IF EXISTS `td_business`;
CREATE TABLE `td_business` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL DEFAULT '' COMMENT '业务名称',
  `pid` int(11) NOT NULL DEFAULT '0' COMMENT '项目编号',
  `completeness` tinyint(4) NOT NULL DEFAULT '0' COMMENT '完成度(纯数字0~100)',
  `appraise` varchar(255) NOT NULL DEFAULT '' COMMENT '评价',
  `cost` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '花费',
  `spend_time` int(11) NOT NULL DEFAULT '0' COMMENT '耗时（小时数）',
  `p_bid` int(11) NOT NULL DEFAULT '0' COMMENT '父业务',
  `evaluate_one` tinyint(4) NOT NULL DEFAULT '100' COMMENT '0~100之间的数值',
  `evaluate_two` tinyint(4) NOT NULL DEFAULT '100' COMMENT '0~100之间的数值',
  `evaluate_three` tinyint(4) NOT NULL DEFAULT '100' COMMENT '0~100之间的数值',
  `total_evaluate` tinyint(4) NOT NULL DEFAULT '100' COMMENT '0~100之间的数值（前三个评价的平均值）',
  `create_time` int(11) NOT NULL DEFAULT '0' COMMENT '创建时间（时间戳）',
  `update_time` int(11) NOT NULL DEFAULT '0' COMMENT '上次更新时间',
  `total_time` int(11) NOT NULL DEFAULT '0' COMMENT '耗时',
  `start_time` int(11) NOT NULL DEFAULT '0' COMMENT '业务开始时间',
  `end_time` int(11) NOT NULL DEFAULT '0' COMMENT '业务结束时间',
  `is_grade` tinyint(4) NOT NULL DEFAULT '0' COMMENT '是否能打分，默认为0不能打分，1表示能打分',
  `status` tinyint(4) NOT NULL DEFAULT '0' COMMENT '是否激活默认为0表示未激活1表示激活',
  `remark` varchar(255) NOT NULL DEFAULT '' COMMENT '备注',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=8 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of td_business
-- ----------------------------
INSERT INTO `td_business` VALUES ('1', '测试业务01', '1', '0', '', '99.00', '0', '0', '100', '100', '100', '100', '1456207310', '0', '0', '1456156800', '1456156800', '1', '1', '备注001');
INSERT INTO `td_business` VALUES ('2', '测试业务02', '2', '0', '', '99988.00', '0', '0', '100', '100', '100', '100', '1456208522', '0', '0', '1456156800', '1455638400', '1', '1', '2');
INSERT INTO `td_business` VALUES ('3', '测试业务03', '1', '0', '111', '333.00', '0', '1', '100', '100', '100', '100', '1456208599', '0', '0', '1456156800', '1456156800', '1', '1', '222');
INSERT INTO `td_business` VALUES ('4', '测试业务04', '1', '0', '评价测试01', '88.77', '5', '3', '100', '100', '100', '100', '1456213514', '1458285100', '0', '1456156800', '1456156800', '1', '1', '备注测试01');
INSERT INTO `td_business` VALUES ('5', '视觉识别业务一', '3', '0', '', '2800.00', '20', '0', '100', '100', '100', '100', '1461566916', '1461566916', '0', '1461513600', '1461513600', '1', '1', '');
INSERT INTO `td_business` VALUES ('6', '视觉识别业务二', '3', '0', '', '1400.00', '10', '0', '100', '100', '100', '100', '1461566950', '1461566950', '0', '1461513600', '1461600000', '1', '1', '');
INSERT INTO `td_business` VALUES ('7', '视觉识别业务三', '3', '0', '', '4200.00', '30', '0', '100', '100', '100', '100', '1461566988', '1461566988', '0', '1461513600', '1461686400', '1', '1', '');

-- ----------------------------
-- Table structure for td_business_money
-- ----------------------------
DROP TABLE IF EXISTS `td_business_money`;
CREATE TABLE `td_business_money` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `bid` int(11) NOT NULL DEFAULT '0' COMMENT '业务ID',
  `aid` int(11) NOT NULL DEFAULT '0' COMMENT '分配的用户ID',
  `cost` decimal(10,0) NOT NULL DEFAULT '0' COMMENT '业务花费',
  `name` varchar(255) NOT NULL DEFAULT '' COMMENT '名称',
  `spend_time` int(11) NOT NULL DEFAULT '0' COMMENT '耗时（小时数）',
  `evaluate_one` tinyint(4) NOT NULL DEFAULT '100' COMMENT '0~100之间的数值',
  `evaluate_two` tinyint(4) NOT NULL DEFAULT '100' COMMENT '0~100之间的数值',
  `evaluate_three` tinyint(4) NOT NULL DEFAULT '100' COMMENT '0~100之间的数值',
  `total_evaluate` tinyint(4) NOT NULL DEFAULT '100' COMMENT '0~100之间的数值（前三个评价的平均值）',
  `create_time` int(11) NOT NULL,
  `update_time` int(11) NOT NULL DEFAULT '0' COMMENT '上次更新是时间',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of td_business_money
-- ----------------------------

-- ----------------------------
-- Table structure for td_business_user
-- ----------------------------
DROP TABLE IF EXISTS `td_business_user`;
CREATE TABLE `td_business_user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `bid` int(11) NOT NULL DEFAULT '0' COMMENT '业务费用ID',
  `aid` int(11) NOT NULL DEFAULT '0' COMMENT '用户ID',
  `spend_time` int(11) NOT NULL DEFAULT '0' COMMENT '耗时（小时数）',
  `cost` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '花费',
  `remark` varchar(255) NOT NULL DEFAULT '' COMMENT '描述、备注',
  `create_time` int(11) NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(11) NOT NULL DEFAULT '0' COMMENT '上次更新时间',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=26 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of td_business_user
-- ----------------------------
INSERT INTO `td_business_user` VALUES ('17', '4', '89007', '99', '0.00', '', '0', '1458522254');
INSERT INTO `td_business_user` VALUES ('16', '4', '89000', '98', '0.00', '', '0', '1458522254');
INSERT INTO `td_business_user` VALUES ('15', '4', '89011', '2', '0.00', '', '0', '1458522254');
INSERT INTO `td_business_user` VALUES ('14', '4', '89006', '1', '0.00', '', '0', '1458522254');
INSERT INTO `td_business_user` VALUES ('13', '3', '89006', '55', '0.00', '', '1458499789', '1458499789');
INSERT INTO `td_business_user` VALUES ('18', '2', '89011', '666', '0.00', '', '1458522465', '1458522465');
INSERT INTO `td_business_user` VALUES ('24', '5', '88997', '12', '0.00', '', '1461817640', '1461817640');
INSERT INTO `td_business_user` VALUES ('23', '5', '89011', '8', '0.00', '', '1461817640', '1461817640');
INSERT INTO `td_business_user` VALUES ('25', '5', '89012', '1', '0.00', '', '1461817640', '1461817640');

-- ----------------------------
-- Table structure for td_category
-- ----------------------------
DROP TABLE IF EXISTS `td_category`;
CREATE TABLE `td_category` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT COMMENT 'id',
  `name` varchar(20) NOT NULL COMMENT '栏目名称',
  `keywords` varchar(100) NOT NULL COMMENT '栏目关键词描述',
  `intro` varchar(250) NOT NULL COMMENT '栏目简介',
  `body` text NOT NULL COMMENT '栏目内容',
  `pid` mediumint(8) unsigned NOT NULL DEFAULT '0' COMMENT '父级栏目',
  `sort` mediumint(8) unsigned NOT NULL DEFAULT '50' COMMENT '排序',
  `model` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '栏目模型',
  `is_navigation` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否导航菜单',
  `outside_the_chain` varchar(500) NOT NULL COMMENT '外部链接地址',
  `attr` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '栏目属性',
  `indextpl` varchar(100) NOT NULL COMMENT '封面模板',
  `listtpl` varchar(100) NOT NULL COMMENT '列表模板',
  `articletpl` varchar(100) NOT NULL COMMENT '内容模板',
  `add_time` datetime NOT NULL COMMENT '添加时间',
  `edit_time` datetime NOT NULL COMMENT '修改时间',
  `is_open` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否启用',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=14 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Records of td_category
-- ----------------------------
INSERT INTO `td_category` VALUES ('1', 'PHP教程', 'PHP教程,PHP实例教程,PHP代码', 'PHP教程栏目主要放一些php语法以及php语言方面的博文!', '', '0', '1', '1', '1', '', '1', 'index_article.html', 'category_index.html', 'article_index.html', '2014-01-08 21:01:46', '0000-00-00 00:00:00', '0');
INSERT INTO `td_category` VALUES ('2', '框架开发', 'PHP框架,PHP主流框架,ThinkPHP框架,Yii框架', '框架开发主要放一些php流行框架的一些使用方法与思路的博文!', '', '0', '22', '1', '1', '', '1', 'index_article.html', 'category_index.html', 'article_index.html', '2014-01-08 21:03:06', '0000-00-00 00:00:00', '1');
INSERT INTO `td_category` VALUES ('3', '开源系统', '74CMS二次开发,DedeCMS二次开发,Ecshop二次开发', '开源系统主要放当前php主流的开源程序74CMS,DedeCMS,Ecshop二次开发博文!', '', '0', '3', '1', '0', '', '1', 'index_article.html', 'category_index.html', 'article_index.html', '2014-01-08 21:04:08', '0000-00-00 00:00:00', '0');
INSERT INTO `td_category` VALUES ('4', '资源共享', '资源共享,PHP代码资源', '资源共享主要放一些PHP开发资料,源码等!', '', '0', '4', '1', '1', '', '1', 'index_article.html', 'category_index.html', 'article_index.html', '2014-01-08 21:06:15', '0000-00-00 00:00:00', '0');
INSERT INTO `td_category` VALUES ('5', 'Web前端', 'Web前端,Javascript,CSS,HTML5', 'Web前端主要放一些web前端Javascript,CSS,HTML5开发的博文!', '', '0', '5', '1', '1', '', '1', 'index_article.html', 'category_index.html', 'article_index.html', '2014-01-08 21:08:16', '0000-00-00 00:00:00', '0');
INSERT INTO `td_category` VALUES ('6', '模板引擎', '模板引擎,PHP模板引擎,Smarty,Template_lite', '模板引擎主要放置一些PHP主流模板引擎如,Smarty,Template_lite的使用博文!', '', '0', '6', '1', '1', '', '1', 'index_article.html', 'category_index.html', 'article_index.html', '2014-01-08 21:09:42', '0000-00-00 00:00:00', '0');
INSERT INTO `td_category` VALUES ('7', 'ThinkPHP框架', 'ThinkPHP,框架,ThinkPHP框架', 'ThinkPHP框架相关技术博文!', '', '2', '227777', '1', '0', '', '1', 'index_article.html', 'category_index.html', 'article_index.html', '2014-01-08 21:13:03', '0000-00-00 00:00:00', '1');
INSERT INTO `td_category` VALUES ('8', 'Yii框架', 'Yii,框架,Yii框架', 'Yii框架相关技术博文!', '', '2', '2222', '1', '0', '', '1', 'index_article.html', 'category_index.html', 'article_index.html', '2014-01-08 21:13:41', '0000-00-00 00:00:00', '0');
INSERT INTO `td_category` VALUES ('9', '织梦CMS', '织梦CMS,dedecms模板,dedecms标签,dedecms二次开发', '织梦CMS,dedecms模板,dedecms标签,dedecms二次开发相关技术文章!', '', '3', '9', '1', '0', '', '1', 'index_article.html', 'category_index.html', 'article_index.html', '2014-01-08 21:15:51', '0000-00-00 00:00:00', '0');
INSERT INTO `td_category` VALUES ('10', 'Ecshop商城系统', 'Ecshop商城系统,Ecshop,ecshop模板,ecshop二次开发', 'Ecshop,ecshop模板,ecshop标签,ecshop二次开发相关技术文章!', '', '3', '10', '1', '0', '', '1', 'index_article.html', 'category_index.html', 'article_index.html', '2014-01-08 21:17:06', '0000-00-00 00:00:00', '0');
INSERT INTO `td_category` VALUES ('11', '74CMS招聘系统', '74CMS,74CMS招聘系统,74CMS模板,74CMS标签,74CMS二次开发', '74CMS,74CMS模板,74CMS标签,74CMS二次开发相关技术文章', '', '3', '11', '1', '0', '', '1', 'index_article.html', 'category_index.html', 'article_index.html', '2014-01-08 21:18:13', '0000-00-00 00:00:00', '0');
INSERT INTO `td_category` VALUES ('12', '关于博客', '关于博客,张仿松PHP博客,博客网站', '关于TryDemo个人博客,个人网站的介绍!', '<p> &nbsp; &nbsp; &nbsp; 记录一些工作中常见php问题的解决方案,内容多数为原创,少部分整理自网络!如需转载,请注明出处,谢谢!</p><p> &nbsp; &nbsp; &nbsp; 该博客程序是博主基于ThinkPHP开源框架,仿WordPress官方发布的twentytwelve主题制作的,如有喜欢这套轻型博客程序的可以私密我,博主可以免费奉送源码额!</p><p><br /></p><p> &nbsp; &nbsp; &nbsp; 关于友链,希望可以交换原创性较多的个人博客网站,营销推广类勿扰!</p><p> &nbsp; &nbsp; &nbsp; 交换友链的可以联系QQ：845573796<br /></p><p><br /></p>', '0', '12', '1', '1', '', '0', 'about.html', 'category_index.html', 'article_index.html', '2014-01-09 20:49:23', '0000-00-00 00:00:00', '0');
INSERT INTO `td_category` VALUES ('13', 'Smarty模板引擎', 'Smarty模板引擎,Smarty,模板引擎', 'Smarty是一个使用PHP写出来的模板引擎，是目前业界最著名的PHP模板引擎之一，它成功分离了应用程序的逻辑与表现。', '', '6', '13', '1', '0', '', '1', 'index_article.html', 'category_index.html', 'article_index.html', '2014-01-21 23:31:51', '0000-00-00 00:00:00', '0');

-- ----------------------------
-- Table structure for td_comments
-- ----------------------------
DROP TABLE IF EXISTS `td_comments`;
CREATE TABLE `td_comments` (
  `cid` bigint(20) unsigned NOT NULL AUTO_INCREMENT COMMENT '评论id',
  `aid` int(9) unsigned NOT NULL DEFAULT '0' COMMENT '文章id',
  `uid` int(9) NOT NULL DEFAULT '0' COMMENT '评论人ID，临时评论默认为0',
  `type` tinyint(4) NOT NULL DEFAULT '0' COMMENT '默认为0表示个人中心评论，1表示公司评论，2表示项目评论',
  `correlation_id` int(11) NOT NULL DEFAULT '0' COMMENT '根据type类型关联id',
  `title` varchar(255) NOT NULL DEFAULT '' COMMENT '评论的标题',
  `comment_author_name` tinytext NOT NULL,
  `comment_author_avatar` varchar(150) NOT NULL DEFAULT '' COMMENT '评论人头像',
  `comment_author_email` varchar(100) NOT NULL DEFAULT '' COMMENT '评论人邮箱',
  `comment_author_url` varchar(200) NOT NULL DEFAULT '' COMMENT '评论互粉站点',
  `comment_author_ip` varchar(100) NOT NULL DEFAULT '' COMMENT '评论时ip地址',
  `comment_add_date` int(11) NOT NULL DEFAULT '0',
  `comment_edit_date` int(11) NOT NULL DEFAULT '0',
  `comment_content` text NOT NULL,
  `comment_karma` int(11) NOT NULL DEFAULT '0',
  `comment_approved` tinyint(4) NOT NULL DEFAULT '0' COMMENT '是否通过审核，默认为0表示未通过审核，1表示已通过审核',
  `comment_agent` varchar(255) NOT NULL DEFAULT '' COMMENT '评论时使用的浏览器',
  `comment_type` tinyint(4) NOT NULL DEFAULT '0' COMMENT '评论类型默认为0评论，1表示系统记录',
  `comment_top_parent` bigint(20) NOT NULL DEFAULT '0' COMMENT '顶级评论id',
  `comment_parent` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '父级评论id',
  `is_lock` tinyint(4) NOT NULL DEFAULT '0' COMMENT '是否锁定',
  `is_show` tinyint(4) NOT NULL DEFAULT '0' COMMENT '0表示不显示，1表示显示',
  PRIMARY KEY (`cid`),
  KEY `comment_post_ID` (`aid`),
  KEY `comment_approved_date_gmt` (`comment_approved`,`comment_edit_date`),
  KEY `comment_date_gmt` (`comment_edit_date`),
  KEY `comment_parent` (`comment_parent`)
) ENGINE=MyISAM AUTO_INCREMENT=387 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Records of td_comments
-- ----------------------------
INSERT INTO `td_comments` VALUES ('374', '0', '89005', '1', '9', '北京小米科技有限责任公司', 'ceshi04', 'user100_1586.jpg', 'trydemo04@163.com', '', '0.0.0.0', '1461688881', '1461688911', '11', '0', '0', 'Mozilla/5.0 (Windows NT 10.0; WOW64; rv:45.0) Gecko/20100101 Firefox/45.0', '0', '0', '0', '0', '0');
INSERT INTO `td_comments` VALUES ('375', '0', '89005', '1', '9', '北京小米科技有限责任公司', 'ceshi04', 'user100_1586.jpg', 'trydemo04@163.com', '', '0.0.0.0', '1461689074', '1461689074', '2', '0', '0', 'Mozilla/5.0 (Windows NT 10.0; WOW64; rv:45.0) Gecko/20100101 Firefox/45.0', '0', '0', '0', '0', '0');
INSERT INTO `td_comments` VALUES ('376', '0', '89005', '1', '9', '北京小米科技有限责任公司', 'ceshi04', 'user100_1586.jpg', 'trydemo04@163.com', '', '0.0.0.0', '1461689318', '1461689440', '3333', '0', '0', 'Mozilla/5.0 (Windows NT 10.0; WOW64; rv:45.0) Gecko/20100101 Firefox/45.0', '0', '0', '0', '0', '0');
INSERT INTO `td_comments` VALUES ('377', '0', '89005', '1', '9', '北京小米科技有限责任公司', 'ceshi04', 'user100_1586.jpg', 'trydemo04@163.com', '', '0.0.0.0', '1461689457', '1461689457', '4', '0', '0', 'Mozilla/5.0 (Windows NT 10.0; WOW64; rv:45.0) Gecko/20100101 Firefox/45.0', '0', '0', '0', '0', '0');
INSERT INTO `td_comments` VALUES ('378', '0', '89005', '1', '9', '北京小米科技有限责任公司', 'ceshi04', 'user100_1586.jpg', 'trydemo04@163.com', '', '0.0.0.0', '1461689751', '1461689771', '111', '0', '0', 'Mozilla/5.0 (Windows NT 10.0; WOW64; rv:45.0) Gecko/20100101 Firefox/45.0', '0', '0', '0', '0', '0');
INSERT INTO `td_comments` VALUES ('379', '0', '89005', '1', '9', '北京小米科技有限责任公司', 'ceshi04', 'user100_1586.jpg', 'trydemo04@163.com', '', '0.0.0.0', '1461689838', '1461689838', '11111', '0', '0', 'Mozilla/5.0 (Windows NT 10.0; WOW64; rv:45.0) Gecko/20100101 Firefox/45.0', '0', '0', '0', '0', '0');
INSERT INTO `td_comments` VALUES ('380', '0', '89005', '1', '9', '北京小米科技有限责任公司', 'ceshi04', 'user100_1586.jpg', 'trydemo04@163.com', '', '0.0.0.0', '1461689946', '1461689946', '2222222', '0', '0', 'Mozilla/5.0 (Windows NT 10.0; WOW64; rv:45.0) Gecko/20100101 Firefox/45.0', '0', '0', '0', '0', '0');
INSERT INTO `td_comments` VALUES ('381', '0', '89005', '1', '9', '北京小米科技有限责任公司', 'ceshi04', 'user100_1586.jpg', 'trydemo04@163.com', '', '0.0.0.0', '1461690106', '1461690106', '12123123', '0', '0', 'Mozilla/5.0 (Windows NT 10.0; WOW64; rv:45.0) Gecko/20100101 Firefox/45.0', '0', '0', '0', '0', '0');
INSERT INTO `td_comments` VALUES ('382', '0', '89005', '1', '9', '北京小米科技有限责任公司', 'ceshi04', 'user100_1586.jpg', 'trydemo04@163.com', '', '0.0.0.0', '1461816111', '1461816111', 'ddddddddddddd', '0', '0', 'Mozilla/5.0 (Windows NT 10.0; WOW64; rv:45.0) Gecko/20100101 Firefox/45.0', '0', '0', '0', '0', '0');
INSERT INTO `td_comments` VALUES ('383', '0', '89005', '1', '9', '北京小米科技有限责任公司', 'ceshi04', 'user100_1586.jpg', 'trydemo04@163.com', '', '0.0.0.0', '1461818467', '1461818467', 'dfsdfsdfdfsdfdsf', '0', '0', 'Mozilla/5.0 (Windows NT 10.0; WOW64; rv:45.0) Gecko/20100101 Firefox/45.0', '0', '0', '0', '0', '0');
INSERT INTO `td_comments` VALUES ('384', '0', '89005', '1', '9', '北京小米科技有限责任公司', 'ceshi04', 'user100_1586.jpg', 'trydemo04@163.com', '', '0.0.0.0', '1464745476', '1464745476', '1212123123123', '0', '0', 'Mozilla/5.0 (Windows NT 10.0; WOW64; rv:46.0) Gecko/20100101 Firefox/46.0', '0', '0', '0', '0', '0');
INSERT INTO `td_comments` VALUES ('385', '0', '89005', '1', '9', '北京小米科技有限责任公司', 'ceshi04', 'user100_1586.jpg', 'trydemo04@163.com', '', '0.0.0.0', '1464745765', '1464745792', '123123123123', '0', '0', 'Mozilla/5.0 (Windows NT 10.0; WOW64; rv:46.0) Gecko/20100101 Firefox/46.0', '0', '0', '0', '0', '0');
INSERT INTO `td_comments` VALUES ('386', '0', '89005', '1', '2', '中邮世纪', 'ceshi04', 'user100_1586.jpg', 'trydemo04@163.com', '', '0.0.0.0', '1464771158', '1464771158', '1231231', '0', '0', 'Mozilla/5.0 (Windows NT 10.0; WOW64; rv:46.0) Gecko/20100101 Firefox/46.0', '0', '1', '1', '0', '0');

-- ----------------------------
-- Table structure for td_company
-- ----------------------------
DROP TABLE IF EXISTS `td_company`;
CREATE TABLE `td_company` (
  `cid` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL DEFAULT '' COMMENT '公司名称',
  `email` varchar(50) NOT NULL DEFAULT '' COMMENT '公司邮箱',
  `phone` varchar(18) NOT NULL DEFAULT '' COMMENT '公司手机',
  `address` varchar(255) NOT NULL DEFAULT '' COMMENT '公司地址',
  `website` varchar(255) NOT NULL DEFAULT '' COMMENT '公司官网',
  `company_amounts` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '账户总金额',
  `business_license` varchar(255) NOT NULL DEFAULT '' COMMENT '营业执照',
  `company_logo` varchar(255) NOT NULL DEFAULT '' COMMENT '公司logo',
  `aid` int(11) NOT NULL DEFAULT '0' COMMENT '绑定公司管理员编号',
  `remark` text NOT NULL COMMENT '备注',
  `type` tinyint(4) NOT NULL DEFAULT '0' COMMENT '默认为0表示甲方公司，1表示乙方公司',
  `status` tinyint(4) NOT NULL DEFAULT '0' COMMENT '公司状态默认为0表示未审核、1表示已审核',
  `is_del` tinyint(4) NOT NULL DEFAULT '0' COMMENT '该公司是否被管理员删除(0表示正常状态，1表示已删除)',
  `create_time` int(11) NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(11) NOT NULL DEFAULT '0' COMMENT '上次更新时间',
  PRIMARY KEY (`cid`)
) ENGINE=MyISAM AUTO_INCREMENT=12 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of td_company
-- ----------------------------
INSERT INTO `td_company` VALUES ('1', '北京环球看点广告传媒有限公司', 'demo@demo.com', '010-88888888', '丰台区六里桥北里', 'www.baidu.com', '8888888.88', '20160421/5718359a7e962.jpg', '20160421/5718359a7f8f5.png', '89003', '百度（Nasdaq：BIDU）是全球最大的中文搜索引擎、最大的中文网站。2000年1月由李彦宏创立于北京中关村，致力于向人们提供“简单，可依赖”的信息获取方式。“百度”二字源于中国宋朝词人辛弃疾的《青玉案·元夕》词句“众里寻他千百度”，象征着百度对中文信息检索技术的执著追求。<br /><br />2015年1月24日，百度创始人、董事长兼CEO李彦宏在百度2014年会暨十五周年庆典上发表的主题演讲中表示，十五年来，百度坚持相信技术的力量，始终把简单可依赖的文化和人才成长机制当成最宝贵的财富，他号召百度全体员工，向连接人与服务的战略目标发起进攻[1]  。2015年11月18日，百度与中信银行发起设立百信银行。', '1', '1', '0', '1453640162', '1461204378');
INSERT INTO `td_company` VALUES ('2', '中邮世纪', 'ceshi01@demo.com', '010-88888888', '丰台区六里桥北里', 'www.baidu.com', '0.00', '20160124/56a4e4f7890e9.jpg', '', '89000', '2', '0', '1', '0', '1453642115', '1453647095');
INSERT INTO `td_company` VALUES ('4', '邮电器材集团', 'ceshi01@demo.com', '010-88888888', '丰台区六里桥北里', 'www.eputai.com', '12345678.66', '20160125/56a5b9020ab30.jpg', '', '88999', '邮电器材集团', '0', '1', '0', '1453701378', '1453708153');
INSERT INTO `td_company` VALUES ('5', '测试公司', 'test02@test.com', '010-88888888', '丰台区六里桥北里', 'www.baidu.com', '653.67', '20160125/56a5ba68969b2.jpg', '', '89000', '测试公司', '0', '1', '0', '1453701736', '1453710478');
INSERT INTO `td_company` VALUES ('9', '北京小米科技有限责任公司', 'ceshi01@demo.com', '010-88888888', '北京市海淀区清河中街68号', 'www.mi.com', '99999999.99', '20160131/56ae0fda87225.jpg', '20160420/571733e732e8e.png', '89003', '北京小米科技有限责任公司成立2010年4月，是一家专注于智能产品自主研发的移动互联网公司。“为发烧而生”是小米的产品概念。小米公司首创了用互联网模式开发手机操作系统、发烧友参与开发改进的模式。\r\n2014年12月14日晚，美的集团发出公告称，已与小米科技签署战略合作协议，小米12.7亿元入股美的集团。2015年9月22日，小米在北京发布了新品小米4c，这款新品由小米4i升级而来，配备5英寸显示屏，搭载骁龙808处理器，号称安卓小王子。\r\n2016年，小米预计推出旗下首款笔记本电脑，代工厂为英业达。', '0', '1', '0', '1454247898', '1461138501');
INSERT INTO `td_company` VALUES ('10', 'LightBP', 'LightBP@LightBP.com', '010-77777777', '北京市丰台区六里桥北里', 'opened.com.cn', '9999999.00', '20160407/570619e05f8e5.jpg', '20160407/570619ba7e015.png', '89003', 'LightBP', '0', '1', '0', '1460017594', '1460017632');
INSERT INTO `td_company` VALUES ('11', '测试公司002', 'test02@test.com', '010-88888888', '丰台区六里桥北里', 'www.baidu.com', '0.00', '20160407/57061a0a658e5.jpg', '20160407/57061a0a669c4.png', '89003', '', '0', '1', '0', '1460017674', '1460017674');

-- ----------------------------
-- Table structure for td_company_user
-- ----------------------------
DROP TABLE IF EXISTS `td_company_user`;
CREATE TABLE `td_company_user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `cid` int(11) NOT NULL DEFAULT '0' COMMENT '关联公司表id',
  `aid` int(11) NOT NULL DEFAULT '0' COMMENT '关联用户表id',
  `role_id` smallint(6) NOT NULL DEFAULT '0' COMMENT '角色id',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=121 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of td_company_user
-- ----------------------------
INSERT INTO `td_company_user` VALUES ('67', '2', '89009', '11');
INSERT INTO `td_company_user` VALUES ('66', '4', '89004', '11');
INSERT INTO `td_company_user` VALUES ('65', '4', '89008', '11');
INSERT INTO `td_company_user` VALUES ('64', '4', '89011', '12');
INSERT INTO `td_company_user` VALUES ('68', '2', '89010', '11');
INSERT INTO `td_company_user` VALUES ('69', '2', '89004', '11');
INSERT INTO `td_company_user` VALUES ('70', '2', '89008', '11');
INSERT INTO `td_company_user` VALUES ('73', '5', '89011', '12');
INSERT INTO `td_company_user` VALUES ('72', '1', '89006', '12');
INSERT INTO `td_company_user` VALUES ('74', '5', '89006', '12');
INSERT INTO `td_company_user` VALUES ('96', '9', '89010', '11');
INSERT INTO `td_company_user` VALUES ('97', '9', '89004', '11');
INSERT INTO `td_company_user` VALUES ('106', '2', '88997', '12');
INSERT INTO `td_company_user` VALUES ('99', '9', '89005', '11');
INSERT INTO `td_company_user` VALUES ('105', '2', '89011', '12');
INSERT INTO `td_company_user` VALUES ('104', '9', '89011', '12');
INSERT INTO `td_company_user` VALUES ('107', '2', '89012', '12');
INSERT INTO `td_company_user` VALUES ('108', '2', '89000', '12');
INSERT INTO `td_company_user` VALUES ('109', '2', '89007', '12');
INSERT INTO `td_company_user` VALUES ('110', '2', '89015', '12');
INSERT INTO `td_company_user` VALUES ('111', '11', '89011', '12');
INSERT INTO `td_company_user` VALUES ('112', '11', '88997', '12');
INSERT INTO `td_company_user` VALUES ('113', '1', '89011', '12');
INSERT INTO `td_company_user` VALUES ('114', '1', '88997', '12');
INSERT INTO `td_company_user` VALUES ('115', '1', '89012', '12');
INSERT INTO `td_company_user` VALUES ('116', '1', '89000', '12');
INSERT INTO `td_company_user` VALUES ('117', '1', '89007', '12');
INSERT INTO `td_company_user` VALUES ('118', '9', '89007', '12');
INSERT INTO `td_company_user` VALUES ('119', '9', '89015', '12');
INSERT INTO `td_company_user` VALUES ('120', '1', '89005', '12');

-- ----------------------------
-- Table structure for td_config
-- ----------------------------
DROP TABLE IF EXISTS `td_config`;
CREATE TABLE `td_config` (
  `id` smallint(8) unsigned NOT NULL AUTO_INCREMENT,
  `varname` varchar(20) NOT NULL DEFAULT '',
  `info` varchar(100) NOT NULL DEFAULT '',
  `groupid` tinyint(3) unsigned NOT NULL DEFAULT '1',
  `value` text NOT NULL,
  `type` tinyint(1) unsigned NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  KEY `varname` (`varname`)
) ENGINE=MyISAM AUTO_INCREMENT=45 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Records of td_config
-- ----------------------------
INSERT INTO `td_config` VALUES ('1', 'sitename', '网站名称', '2', 'TryDemo个人博客', '2');
INSERT INTO `td_config` VALUES ('2', 'siteurl', '网站网址', '2', '/trydemo/', '2');
INSERT INTO `td_config` VALUES ('3', 'sitefileurl', '附件地址', '2', '', '2');
INSERT INTO `td_config` VALUES ('4', 'siteemail', '站点邮箱', '2', 'trydemo@126.com', '2');
INSERT INTO `td_config` VALUES ('6', 'sitedescription', '网站介绍', '2', 'PHP开源系统DedeCMS,Ecshop商城', '2');
INSERT INTO `td_config` VALUES ('43', 'siteseodescription', '网站SEO版介绍', '2', 'TryDemo博客是一个关注PHP网站建设,PHP开源系统DedeCMS,Ecshop商城,74CMS招聘系统二次开发的技术博客,提供一个互联网从业者的学习成果和工作经验总结。', '2');
INSERT INTO `td_config` VALUES ('7', 'sitekeywords', '网站关键字', '2', '个人博客,个人PHP网站,Android开发,DedeCMS二次开发,Ecshop二次开发', '2');
INSERT INTO `td_config` VALUES ('8', 'uploadmaxsize', '允许上传附件大小', '2', '20240', '1');
INSERT INTO `td_config` VALUES ('9', 'uploadallowext', '允许上传附件类型', '2', 'jpg|jpeg|gif|bmp|png|doc|docx|xls|xlsx|ppt|pptx|pdf|txt|rar|zip|swf', '1');
INSERT INTO `td_config` VALUES ('10', 'qtuploadmaxsize', '前台允许上传附件大小', '2', '200', '1');
INSERT INTO `td_config` VALUES ('11', 'qtuploadallowext', '前台允许上传附件类型', '2', 'jpg|jpeg|gif', '1');
INSERT INTO `td_config` VALUES ('12', 'watermarkenable', '是否开启图片水印', '2', '1', '1');
INSERT INTO `td_config` VALUES ('13', 'watermarkminwidth', '水印-宽', '2', '300', '1');
INSERT INTO `td_config` VALUES ('14', 'watermarkminheight', '水印-高', '2', '100', '1');
INSERT INTO `td_config` VALUES ('15', 'watermarkimg', '水印图片', '2', '/statics/images/mark_bai.png', '1');
INSERT INTO `td_config` VALUES ('16', 'watermarkpct', '水印透明度', '2', '80', '1');
INSERT INTO `td_config` VALUES ('17', 'watermarkquality', 'JPEG 水印质量', '2', '85', '1');
INSERT INTO `td_config` VALUES ('18', 'watermarkpos', '水印位置', '2', '7', '1');
INSERT INTO `td_config` VALUES ('44', 'a_reply_interval', '文章连续回复时间间隔', '2', '15', '1');

-- ----------------------------
-- Table structure for td_file
-- ----------------------------
DROP TABLE IF EXISTS `td_file`;
CREATE TABLE `td_file` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `pid` int(11) NOT NULL DEFAULT '0' COMMENT '关联项目id',
  `name` varchar(255) NOT NULL DEFAULT '' COMMENT '文件名称',
  `address` varchar(255) NOT NULL DEFAULT '' COMMENT '文件地址',
  `type` tinyint(4) NOT NULL DEFAULT '0' COMMENT '默认为0表示没有实体文件，1表示doc,2表示excel,3表示txt,4表示jpg,5表示gif,6表示png,7表示jpeg，8表示docx,9表示pdf,10表示rar,11表示zip,12表示xls',
  `download_count` int(11) NOT NULL DEFAULT '0' COMMENT '文件下载次数',
  `status` tinyint(4) NOT NULL DEFAULT '0' COMMENT '默认为0表示未激活，1表示激活',
  `is_del` tinyint(4) NOT NULL DEFAULT '0' COMMENT '该文件是否被管理员删除(0表示正常状态，1表示已删除)',
  `create_time` int(11) NOT NULL DEFAULT '0' COMMENT '创建时间（时间戳）',
  `update_time` int(11) NOT NULL DEFAULT '0' COMMENT '上次更新时间（时间戳）',
  `remark` varchar(255) NOT NULL DEFAULT '' COMMENT '文件描述',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=37 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of td_file
-- ----------------------------
INSERT INTO `td_file` VALUES ('2', '1', '图片02.jpg', '20160223/56cc231381739.jpg', '4', '0', '1', '0', '1456218899', '1456218899', '备注02');
INSERT INTO `td_file` VALUES ('3', '1', '图片.jpg', '20160223/56cc233c3427d.jpg', '4', '0', '1', '0', '1456218940', '1456218940', '111');
INSERT INTO `td_file` VALUES ('4', '1', 'test.jpg', '20160223/56cc258c82d14.jpg', '4', '0', '0', '0', '1456219532', '1456219532', '1');
INSERT INTO `td_file` VALUES ('5', '1', 'arr2.txt', '20160223/56cc278a2afd2.txt', '3', '0', '0', '0', '1456220042', '1456220042', '3');
INSERT INTO `td_file` VALUES ('6', '1', 'arr1.txt', '20160223/56cc27a316dbc.txt', '3', '7', '0', '0', '1456220067', '1456220067', '3');
INSERT INTO `td_file` VALUES ('7', '2', '', '', '0', '0', '1', '0', '1460971190', '1460971190', '');
INSERT INTO `td_file` VALUES ('8', '2', '', '', '0', '0', '1', '0', '1460971645', '1460971645', '');
INSERT INTO `td_file` VALUES ('9', '2', '', '', '0', '0', '1', '0', '1460971651', '1460971651', '');
INSERT INTO `td_file` VALUES ('10', '2', '', '', '0', '0', '1', '0', '1460971769', '1460971769', '');
INSERT INTO `td_file` VALUES ('11', '2', '', '', '0', '0', '1', '0', '1461027416', '1461027416', '');
INSERT INTO `td_file` VALUES ('12', '2', '', '', '0', '0', '1', '0', '1461027421', '1461027421', '');
INSERT INTO `td_file` VALUES ('13', '2', '', '', '0', '0', '1', '0', '1461027557', '1461027557', '');
INSERT INTO `td_file` VALUES ('14', '2', '', '', '4', '0', '1', '0', '1461027637', '1461027637', '');
INSERT INTO `td_file` VALUES ('15', '2', '', '', '4', '0', '1', '0', '1461027742', '1461027742', '');
INSERT INTO `td_file` VALUES ('16', '2', '', '', '4', '0', '1', '0', '1461027779', '1461027779', '');
INSERT INTO `td_file` VALUES ('17', '2', '', '', '4', '0', '1', '0', '1461028017', '1461028017', '');
INSERT INTO `td_file` VALUES ('18', '2', '', '', '1', '0', '1', '0', '1461029481', '1461029481', '');
INSERT INTO `td_file` VALUES ('19', '2', '', '', '11', '0', '1', '0', '1461029538', '1461029538', '');
INSERT INTO `td_file` VALUES ('20', '2', '', '', '4', '0', '1', '0', '1461029768', '1461029768', '');
INSERT INTO `td_file` VALUES ('21', '2', '', '', '4', '0', '1', '0', '1461030012', '1461030012', '');
INSERT INTO `td_file` VALUES ('22', '2', '', '', '4', '0', '1', '0', '1461032714', '1461032714', '');
INSERT INTO `td_file` VALUES ('23', '2', '', '', '4', '0', '1', '0', '1461032880', '1461032880', '');
INSERT INTO `td_file` VALUES ('24', '2', '', '', '4', '0', '1', '0', '1461032963', '1461032963', '');
INSERT INTO `td_file` VALUES ('25', '1', '', '', '8', '0', '1', '0', '1461032968', '1461032968', '');
INSERT INTO `td_file` VALUES ('26', '1', '', '', '2', '0', '1', '0', '1461033921', '1461033921', '');
INSERT INTO `td_file` VALUES ('27', '2', '', '', '11', '0', '1', '0', '1461033930', '1461033930', '');
INSERT INTO `td_file` VALUES ('28', '2', '', '', '4', '0', '1', '0', '1461033939', '1461033939', '');
INSERT INTO `td_file` VALUES ('29', '2', '', '', '11', '0', '1', '0', '1461033956', '1461033956', '');
INSERT INTO `td_file` VALUES ('30', '2', '', '固定地址统计.xlsx', '2', '0', '1', '0', '1461034160', '1461034160', '');
INSERT INTO `td_file` VALUES ('31', '2', '固定地址统计.xlsx', '20160419/', '2', '0', '1', '0', '1461034201', '1461034201', '');
INSERT INTO `td_file` VALUES ('32', '2', '固定地址统计.xlsx', '20160419/57159d0f92099.xlsx', '2', '0', '1', '0', '1461034255', '1461034255', '');
INSERT INTO `td_file` VALUES ('33', '2', '固定地址统计.xlsx', '20160419/57159dbf35f0c.xlsx', '2', '0', '1', '0', '1461034431', '1461034431', '');
INSERT INTO `td_file` VALUES ('34', '2', 'c_logo_mi.png', '20160421/57189107bb300.png', '6', '0', '1', '0', '1461227783', '1461227783', '');
INSERT INTO `td_file` VALUES ('35', '2', '测试机统计表_old_before160420.xlsx', '20160421/5718910e92df3.xlsx', '2', '1', '1', '0', '1461227790', '1461227790', '');
INSERT INTO `td_file` VALUES ('36', '2', '2016-4-27测试问题.docx', '20160428/57218b4e3c4c4.docx', '8', '0', '1', '0', '1461816142', '1461816142', '');

-- ----------------------------
-- Table structure for td_file_log
-- ----------------------------
DROP TABLE IF EXISTS `td_file_log`;
CREATE TABLE `td_file_log` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `fid` int(11) NOT NULL DEFAULT '0' COMMENT '文件ID',
  `aid` int(11) NOT NULL DEFAULT '0' COMMENT '用户ID',
  `fname` varchar(255) NOT NULL DEFAULT '' COMMENT '阅读时文件名称',
  `status` tinyint(4) NOT NULL DEFAULT '0' COMMENT '默认为0表示未读，1表示已读',
  `create_time` int(11) NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(11) NOT NULL DEFAULT '0' COMMENT '上次更新时间',
  `read_time` int(11) NOT NULL DEFAULT '0' COMMENT '第一次打开文件时间',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of td_file_log
-- ----------------------------

-- ----------------------------
-- Table structure for td_link
-- ----------------------------
DROP TABLE IF EXISTS `td_link`;
CREATE TABLE `td_link` (
  `link_id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT COMMENT 'id',
  `link_name` varchar(60) NOT NULL DEFAULT '' COMMENT '链接名称',
  `link_url` varchar(255) NOT NULL DEFAULT '' COMMENT '链接地址',
  `link_type` tinyint(4) unsigned NOT NULL DEFAULT '0' COMMENT '链接类型默认为0表示通过后台添加，1表示通过前台添加',
  `link_logo` varchar(255) NOT NULL DEFAULT '' COMMENT '友情链接logo图片地址',
  `link_image` varchar(255) NOT NULL DEFAULT '' COMMENT '友链图片地址',
  `link_description` varchar(255) NOT NULL DEFAULT '' COMMENT '友链描述',
  `link_owner` int(9) NOT NULL DEFAULT '0' COMMENT '添加人id(admin表user表g），根据link_type判断，默认为0表示后台用户添加，1表示前台用户添加',
  `link_rating` tinyint(4) NOT NULL DEFAULT '0' COMMENT '友链等级评定',
  `status` tinyint(4) unsigned NOT NULL DEFAULT '0' COMMENT '审核状态默认为0不可用，1表示可用',
  `sort` mediumint(8) unsigned NOT NULL DEFAULT '0' COMMENT '排序',
  `create_time` int(11) NOT NULL DEFAULT '0' COMMENT '添加时间',
  `update_time` int(11) NOT NULL DEFAULT '0' COMMENT '上次更新时间',
  `link_rss` varchar(255) NOT NULL DEFAULT '' COMMENT 'rss',
  `is_del` tinyint(4) NOT NULL DEFAULT '0' COMMENT '默认为0表示未删除，1表示删除',
  PRIMARY KEY (`link_id`)
) ENGINE=MyISAM AUTO_INCREMENT=11 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Records of td_link
-- ----------------------------
INSERT INTO `td_link` VALUES ('1', 'Opened', 'http://opened.com.cn/', '0', '', '', '', '88995', '0', '1', '0', '2014', '0', '', '1');
INSERT INTO `td_link` VALUES ('2', '百度', 'http://www.baidu.com', '0', '', '', '', '88995', '0', '1', '0', '2014', '0', '', '0');
INSERT INTO `td_link` VALUES ('7', 'test01', 'www.admin10000.com', '0', '20160302/56d65985354ca.jpg', '20160302/56d659852e679.png', '33', '88995', '0', '1', '22', '1456888197', '1456888197', '', '0');
INSERT INTO `td_link` VALUES ('9', 'test02', 'www.admin10000.com', '0', '20160302/56d677ccb7eaa.jpg', '20160302/56d677ccb596f.jpg', '测试专用', '88995', '0', '1', '1', '1456895948', '1456895948', '', '0');
INSERT INTO `td_link` VALUES ('10', 'test03', 'www.v2ex.com', '0', '20160302/56d6786a0112b.png', '20160302/56d6786a00347.jpg', '这仅仅是一个测试', '88995', '0', '1', '99', '1456896105', '1456896105', '', '0');

-- ----------------------------
-- Table structure for td_meeting_minute
-- ----------------------------
DROP TABLE IF EXISTS `td_meeting_minute`;
CREATE TABLE `td_meeting_minute` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `pid` int(11) NOT NULL DEFAULT '0' COMMENT '项目编号',
  `aid` int(11) NOT NULL DEFAULT '0' COMMENT '员工编号',
  `meeting_topic` varchar(255) NOT NULL DEFAULT '' COMMENT '会议主题',
  `address` varchar(255) NOT NULL DEFAULT '' COMMENT '会议地点',
  `meeting_noter` varchar(255) NOT NULL DEFAULT '' COMMENT '会议记录人',
  `meeting_organizers` varchar(255) NOT NULL DEFAULT '' COMMENT '会议参与者',
  `executor` varchar(255) NOT NULL DEFAULT '' COMMENT '执行人',
  `content` text NOT NULL COMMENT '会议内容',
  `remark` varchar(255) NOT NULL DEFAULT '' COMMENT '会议备注',
  `start_time` int(11) NOT NULL DEFAULT '0' COMMENT '时间（时间戳格式）',
  `end_time` int(11) NOT NULL DEFAULT '0' COMMENT '结束时间',
  `status` tinyint(4) NOT NULL DEFAULT '0' COMMENT '默认为0表示未通过审核，1表示通过审核',
  `create_time` int(11) NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(11) NOT NULL DEFAULT '0' COMMENT '上次更新时间',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of td_meeting_minute
-- ----------------------------
INSERT INTO `td_meeting_minute` VALUES ('1', '2', '88995', '测试会议001', '北京市丰台区六里桥北里', '刘德华', '刘德华，尼莫，530', '530', '1、会议内容第一项\r\n2、会议内容第二项', '这里是备注哟！', '1458544929', '1458544929', '1', '1458544943', '1458546339');

-- ----------------------------
-- Table structure for td_member
-- ----------------------------
DROP TABLE IF EXISTS `td_member`;
CREATE TABLE `td_member` (
  `uid` int(11) NOT NULL AUTO_INCREMENT,
  `weibo_uid` varchar(15) DEFAULT NULL COMMENT '对应的新浪微博uid',
  `tencent_uid` varchar(20) DEFAULT NULL COMMENT '腾讯微博UID',
  `email` varchar(100) DEFAULT NULL COMMENT '邮箱地址',
  `nickname` varchar(20) DEFAULT NULL COMMENT '用户昵称',
  `pwd` char(32) DEFAULT NULL COMMENT '密码',
  `reg_date` int(10) DEFAULT NULL,
  `reg_ip` char(15) DEFAULT NULL COMMENT '注册IP地址',
  `verify_status` int(1) DEFAULT '0' COMMENT '电子邮件验证标示 0未验证，1已验证',
  `verify_code` varchar(32) DEFAULT NULL COMMENT '电子邮件验证随机码',
  `verify_time` int(10) DEFAULT NULL COMMENT '邮箱验证时间',
  `verify_exp_time` int(10) DEFAULT NULL COMMENT '验证邮件过期时间',
  `find_fwd_code` varchar(32) DEFAULT NULL COMMENT '找回密码验证随机码',
  `find_pwd_time` int(10) DEFAULT NULL COMMENT '找回密码申请提交时间',
  `find_pwd_exp_time` int(10) DEFAULT NULL COMMENT '找回密码验证随机码过期时间',
  `avatar` varchar(100) DEFAULT NULL COMMENT '用户头像',
  `birthday` int(10) DEFAULT NULL COMMENT '用户生日',
  `sex` int(1) DEFAULT NULL COMMENT '0女1男',
  `address` varchar(50) DEFAULT NULL COMMENT '地址',
  `province` varchar(100) DEFAULT NULL COMMENT '省份',
  `city` varchar(100) DEFAULT NULL COMMENT '城市',
  `intr` varchar(500) DEFAULT NULL COMMENT '个人介绍',
  `mobile` varchar(11) DEFAULT NULL COMMENT '手机号码',
  `phone` varchar(30) DEFAULT NULL COMMENT '电话',
  `fax` varchar(30) DEFAULT NULL,
  `qq` int(15) DEFAULT NULL,
  `msn` varchar(100) DEFAULT NULL,
  `login_ip` varchar(15) DEFAULT NULL COMMENT '登录ip',
  `login_time` int(10) DEFAULT NULL COMMENT '登录时间',
  PRIMARY KEY (`uid`)
) ENGINE=MyISAM AUTO_INCREMENT=350 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='网站前台会员表';

-- ----------------------------
-- Records of td_member
-- ----------------------------

-- ----------------------------
-- Table structure for td_news
-- ----------------------------
DROP TABLE IF EXISTS `td_news`;
CREATE TABLE `td_news` (
  `id` mediumint(8) NOT NULL AUTO_INCREMENT,
  `cid` smallint(3) DEFAULT NULL COMMENT '所在分类',
  `title` varchar(200) DEFAULT NULL COMMENT '新闻标题',
  `keywords` varchar(50) DEFAULT NULL COMMENT '文章关键字',
  `description` mediumtext COMMENT '文章描述',
  `status` tinyint(1) DEFAULT NULL,
  `summary` varchar(255) DEFAULT NULL COMMENT '文章摘要',
  `published` int(10) DEFAULT NULL,
  `update_time` int(10) DEFAULT NULL,
  `content` text,
  `aid` smallint(3) DEFAULT NULL COMMENT '发布者UID',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='新闻表';

-- ----------------------------
-- Records of td_news
-- ----------------------------
INSERT INTO `td_news` VALUES ('2', '9', '二季度房地产信托兑付超千亿 PE伺机接盘 ', '', '随着安信信托、中信信托等多家信托公司曝出房地产信托计划的兑付风险，部分房地产投资私募基金(PE)已在其中看到了机会。', '0', '<div class=\"content\"><p>随着安信<a href=\"http://licai.so/Trust/\" target=\"_blank\">信托</a>、<a href=\"http://licai.so/Trust/agency-1061/\" target=\"_blank\">中信信托</a>等多家信托公司曝出房地产信托计划的兑付风险，部分房地产投资<a href=\"http...', '1363141340', '0', '<div class=\"content\"><p>随着安信<a href=\"http://licai.so/Trust/\" target=\"_blank\">信托</a>、<a href=\"http://licai.so/Trust/agency-1061/\" target=\"_blank\">中信信托</a>等多家信托公司曝出房地产信托计划的兑付风险，部分房地产投资<a href=\"http://licai.so/Simu/\" target=\"_blank\">私募</a>基金(<a href=\"http://licai.so/Pe/\" target=\"_blank\">PE</a>)已在其中看到了机会。</p><br /><p>《每日经济新闻》记者了解到，2013年房地产信托兑付压力远超2012年，其中，兑付顶峰将出现在2013年第二季度，届时全国房地产信托兑付的总规模将超过1000亿元。通过信托融资的中小房地产企业将不得不面对资金上的窘境，而这对资金充裕的PE来说，无疑将是一次“捡馅饼”的机会。</p><br /><p>风险频现挑战“刚性兑付”/</p><br /><p>上周五(3月8日)，据《21世纪经济报道》称，<a href=\"http://licai.so/Trust/agency-1002/\" target=\"_blank\">安信信托</a>因为“昆山·联邦国际资产收益财产权信托”的融资方昆山纯高投资开发有限公司无法支付到期本金，已对纯高公司进行了起诉。</p><br /><p>不过，安信信托在当日发布澄清公告称，2009年9月24日，安信信托发起并设立了总规模为人民币62700万元的“昆山-联邦国际”资产收益财产权信托。但是到2012年9月18日，昆山纯高投资开发有限公司作为信托交易文件项下借款人未能按时足额偿还信托借款。</p><br /><p>为此，安信信托已向上海市第二中级人民法院提起金融借款纠纷诉讼。并且“根据信托文件约定，信托期限已自动延长，最长至2013年9月24日。”</p><br /><p>安信信托董办工作人员称，目前信托计划已经延期了,但是公司确实已进行了部分兑付。</p><br /><p>一位信托行业人士表示，项目出了问题，信托公司一般都会先托着，但如果真的出现较大的问题，这样做就会有很大风险。</p><br /><p>事实上，信托行业的“刚性兑付”此前就已经遇到了挑战。今年1月份，中信信托关于三峡全通的贷款资金兑付问题就已引起业界震动。</p><br /><p>资料显示，中信信托于2011年12月28日发起设立，“中信制造三峡全通贷款集合资金信托计划”分4次募集信托本金共计人民币13.335亿元，为三峡全通发放流动资金贷款。</p><br /><p>三峡全通公司应当于2013年1月14日和16日分别偿还贷款本息11855万元和47247万元。中信信托称，“截至2013年1月28日，本信托计划信托专户仍未收到该两期应收本息及违约金。”因此公司决定存续的优先级信托受益权的到期日延期3个月。</p><br /><p>而中信信托方面已表示，将不会去进行刚性兑付。业界认为该事件可能成信托业首个打破刚性兑付 “行规”的案例。</p><br /><p>二季度迎新一波兑付潮/</p><br /><p>虽然信托行业已经度过了此前预期兑付风险较大的2012年。但是到了2013年，房地产信托仍然面临较大的兑付压力。</p><br /><p>据北京恒天财富相关数据统计，2013年房地产信托面临兑付本息达2800亿元，远超过2012年的1759亿元。其中，兑付顶峰将出现在2013年第二季度，届时全国房地产信托兑付的总规模将达到1301亿元。</p><br /><p>另据新时代证券发布的研报，根据每月的成立规模与月平均期限测算，2013年房地产信托到期兑付规模将达2847.9亿元，其中二季度达1247.6亿元。</p><br /><p>上海一家信托公司项目经理接受《每日经济新闻》记者采访时表示，“在房地产信托计划的兑付中，中小房地产企业的压力要大得多。他们的融资原本就比大型的房地产企业要难，风险也相对要高一些。”</p><br /><p><a href=\"http://licai.so/Simu/200287/\" target=\"_blank\">诺亚财富</a>研究部李要深则对《每日经济新闻》记者表示，目前总体来说，房地产信托没有太大的问题，相比前两年，规模和占比已经下降很多，处在一个相对安全的范围，并且房地产信托一般都有较好的抵押物。</p><br /><p>事实上，今年以来，房地产信托发展速度仍然较快。用益信托数据显示，2月份共成立房地产信托52款，募集资金162.95亿元，占总成立规模的33.98%，高于上个月29.49%的占比，较去年23%左右的占比更是显著增加。</p><br /><p>PE伺机而动</p><br /><p>对资金充裕的PE来说，房地产信托接盘的时机也可能就在今年。</p><br /><p>“房地产公司现在都缺钱，尤其是中小房地产企业，更是困难。从目前的角度来看，这块的投资价值逐渐显现出来了。”某股权投资基金相关人士称，PE投资接盘的条件主要还是看具体的项目。</p><br /><p>“从实际情况看，房地产信托有兑付风险的项目眼下还不多，只是根据趋势判断，今年的投资将有很大的操作空间，也就是找一些缺资金、项目优质的企业合作。”上述股权投资基金人士表示。</p><br /><p>据《每日经济新闻》记者不完全统计，在即将到期的房地产信托项目中，北京、上海等一线城市的项目数量有限，而鄂尔多斯、青岛等二线城市项目则多一些。</p><br /><p>上述股权投资基金人士介绍，与房地产企业的合作，模式是多种多样的。“最简单的是折价收购整个项目，然而分拆出售，但是这对PE公司的资金实力和运作的要求很高。另外，不同PE主体的参与模式也不一样。<a href=\"http://licai.so/Jgzl/\" target=\"_blank\">金融机构</a>发起的地产基金主要是做债权，和信托公司联合发起信托型基金，这是一种‘类信托’的融资模式;大型房地产企业则更愿意做股权融资，进行大鱼吃小鱼的行业整合。”</p><br /><p>此前有消息称，万科、金地、华润、复兴为代表的房地产集团都在旗下设立PE投资公司，通过股权融资扩大行业版图。</p><br /><p>不过，上述股权投资基金人士也表示，“房地产信托的兑付风险都依靠PE来接盘肯定是不现实的，目前PE的实力也达不到。但是，PE对一些优质项目的兴趣比较大，也是一支不可忽视的力量。”</p></div>', '1');
INSERT INTO `td_news` VALUES ('3', '14', '银监会拟引入银行理财业务和机构准入制度', '银行理财', '银行理财业务的迅猛增长，倒逼监管的步步升级。', '1', '银行理财业务的迅猛增长，倒逼监管的步步升级。记者从业内获得的最新统计数据显示，截至2012年末，各银行共存续理财产品32152款，理财资金账面余额7.1万亿元，比2011年末增长约55%。年初以来，银监会已将理财业务列为今年的重点监管工作。消息人士透露，对理财产品加大监管主要表现在两方面：一是将派出机构对银行理财产品销售活动进行专项检查；另一方面，将“资金池”操作模式作为现场检查的重点，“要求商业…', '1363141499', '1363148135', '银行理财业务的迅猛增长，倒逼监管的步步升级。<p>记者从业内获得的最新统计数据显示，截至2012年末，各银行共存续理财产品32152款，理财资金账面余额7.1万亿元，比2011年末增长约55%。</p><p>年初以来，银监会已将理财业务列为今年的重点监管工作。消息人士透露，对理财产品加大监管主要表现在两方面：一是将派出机构对银行理财产品销售活动进行专项检查；另一方面，将“资金池”操作模式作为现场检查的重点，“要求商业银行在2-4月份首先对‘资金池’类理财产品进行自查整改。”</p><p>随着理财业务的过快发展，监管部门对于理财业务参与机构的风险管理能力、资产管理能力等方面表现出担忧，特别是城商行和农村合作<a href=\"http://licai.so/Jgzl/\" target=\"_blank\">金融机构</a>。消息人士称，因此，监管部门正在酝酿开展理财业务的机构准入和业务准入制度。</p><p><strong> 严禁银行理财输血地方融资平台</strong></p><p>银行理财业务自2005年发端，至今经历了七年发展期。但时至今日仍有部分银行对理财业务的发展缺乏明确的战略定位，并未真正树立起“代客理财”的理念。</p><p>银行每季度末为冲规模大量发行期限短、收益高的理财产品，表明部分银行仅将理财业务当作其自营业务的附属，当存款规模紧张时，就通过发行保本、高收益产品争揽存款；当贷款规模紧张时，就通过理财实现贷款规模表外化，把银行理财作为“高息揽储”和“变相放贷”的工具。</p><p>记者了解到，监管部门因此要求商业银行董事会及高管层要对理财业务进行清晰的战略定位，避免理财业务沦为其他业务的调节工具和手段。</p><p>此前，部分银行将理财业务视为“变相放贷”的工具，通过规避银信合作监管规定的方式来开展项目融资，如以银证、银保、银基合作的方式，投资于票据资产或其他非标准化债券类资产。</p><p>记者获得的数据显示，截至2012年末，项目融资类理财产品余额同比增长了53%，占全部理财产品投资余额的30%，超过2万亿元。</p><p>前述消息人士透露，为了控制去年以来迅猛增长的银证、银保、银基合作等通道类业务所蕴含的风险，监管部门要求商业银行开展此类业务全程确保合规，这包括，首先要界定好投资过程中的法律关系；其次要在尽职调查的基础上合理安排交易结构和投资条款；第三，要求产品说明书要按照“解包还原”的原则充分披露；第四，要对最终投资标的的资产进行实质性管理和控制；最后还要求目标客户必须满足合格投资者的相关要求。</p><p>对于理财产品销售过程中的不规范行为，监管部门将针对这一环节进行专项检查，并计划要求银行通过投资者教育的门户网站来公示预期收益率和实际收益率的对比情况。</p><p>理财资金投向方面也要严格把关。银监会强调商业银行应严格限制资金通过各类渠道进入地方政府融资平台、“两高一剩”企业、商业房地产开发项目等限制性行业和领域。“特别强调要防止地方政府融资平台绕道通过银行理财进行直接或间接融资。”消息人士称。</p><p>银监会公布的数据显示，截至2012年末，政府融资平台贷款为93035亿元。</p><p><strong>中小机构冒进 监管层酝酿准入制度</strong></p><p>去年以来，中小金融机构特别是城商行和农村合作金融机构大量参与理财市场更加激进。记者获悉，大型银行和股份制银行在理财业务的市场份额已从2011年的88%，下降至2012年的83%。</p><p>理财业务发展过快而参与机构良莠不齐，引发监管部门的担忧。同时，部分机构还存在风险管理能力不足、业务开展不够审慎的问题。</p><p>如根据银率网的统计数据显示，今年2月份共有22款理财产品未达到预期收益率，其中有15款均为南洋商业银行所发行的产品。</p><p>而且，部分中小银行由于缺乏自主的产品设计能力，在与券商、基金、资产管理公司合作时，缺乏对产品风险和收益的实际控制权，极易沦为合作方的资金募集通道，一旦出现风险只能被动接受。</p><p>消息人士透露，对于此类风险管控能力较低、资产管理能力和专业素质还不足的中小金融机构，银监会将对其能够从事多大规模的理财业务，进行严格把关和密切监测。制定一套开展理财业务的机构准入和业务准入制度也纳入监管部门的计划中。</p><p>值得注意的是，一些创新型理财产品，如股权类投资、股票基金类投资和另类投资等，监管部门考虑到其高风险和结构复杂性，其发行将会受到严控。“特别是中小银行金融机构发行此类理财产品时，将需要逐笔上报银监会，加强合规性审查。”</p><p>此外，监管部门还注意到，部分银行存在将理财产品持有的资产与其他理财产品持有的资产，或银行自营业务资产，通过非公允的市场价格进行交易的违规行为。更有银行将一些较高收益率的理财产品销售给特定关系人，涉嫌利益输送。</p><p>银行理财业务存在的问题引起多部委的注意。记者获悉，去年，中纪委和监察部国家预防腐败局办公室也曾就此问题与银监会进行过专门的探讨，对于银行理财产品设计和交易中可能存在的腐败问题，中纪委、监察部和银监会都将进一步密切关注。</p>', '1');

-- ----------------------------
-- Table structure for td_node
-- ----------------------------
DROP TABLE IF EXISTS `td_node`;
CREATE TABLE `td_node` (
  `id` smallint(6) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(20) NOT NULL DEFAULT '',
  `title` varchar(50) NOT NULL DEFAULT '',
  `status` tinyint(1) NOT NULL DEFAULT '0',
  `type` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否是菜单项',
  `remark` varchar(255) DEFAULT '' COMMENT '备注',
  `sort` smallint(6) unsigned NOT NULL DEFAULT '0' COMMENT '排序',
  `pid` smallint(6) unsigned NOT NULL DEFAULT '0' COMMENT '父级节点id',
  `level` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '权限等级区分默认为1表示项目，2表示控制，3表示操作',
  PRIMARY KEY (`id`),
  KEY `level` (`level`),
  KEY `pid` (`pid`),
  KEY `status` (`status`),
  KEY `name` (`name`)
) ENGINE=MyISAM AUTO_INCREMENT=46 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='权限节点表';

-- ----------------------------
-- Records of td_node
-- ----------------------------
INSERT INTO `td_node` VALUES ('22', 'ajax_del_role', '删除管理员角色', '1', '0', null, '7', '4', '3');
INSERT INTO `td_node` VALUES ('21', 'ajax_add_role', '新增管理员角色', '1', '0', null, '6', '4', '3');
INSERT INTO `td_node` VALUES ('20', 'mRoleEdit', '管理员角色信息编辑', '1', '0', null, '5', '4', '3');
INSERT INTO `td_node` VALUES ('19', 'mRoleMember', '管理员角色成员管理', '1', '0', null, '4', '4', '3');
INSERT INTO `td_node` VALUES ('18', 'mRoleAuthorization', '管理员角色权限设置', '1', '0', null, '3', '4', '3');
INSERT INTO `td_node` VALUES ('17', 'mAdminRoleList', '管理员角色列表', '1', '0', null, '2', '4', '3');
INSERT INTO `td_node` VALUES ('16', 'index', '权限管理默认页', '1', '0', '管理员列表', '1', '4', '3');
INSERT INTO `td_node` VALUES ('15', 'ajax_update_status', '异步更改审核状态', '1', '0', null, '6', '3', '3');
INSERT INTO `td_node` VALUES ('14', 'ajax_del_users', '删除用户', '1', '0', null, '5', '3', '3');
INSERT INTO `td_node` VALUES ('13', 'mUserAnalyse', '用户分析', '1', '0', null, '4', '3', '3');
INSERT INTO `td_node` VALUES ('12', 'mEdit', '编辑用户信息', '1', '0', null, '3', '3', '3');
INSERT INTO `td_node` VALUES ('11', 'mAdd', '添加新用户', '1', '0', null, '2', '3', '3');
INSERT INTO `td_node` VALUES ('9', 'main', '后台管理main分帧布局', '1', '0', null, '4', '2', '3');
INSERT INTO `td_node` VALUES ('8', 'nav', '后台管理nav分帧布局', '1', '0', null, '3', '2', '3');
INSERT INTO `td_node` VALUES ('10', 'index', '用户管理默认页', '1', '0', null, '1', '3', '3');
INSERT INTO `td_node` VALUES ('7', 'left', '后台管理left分帧布局', '1', '0', null, '2', '2', '3');
INSERT INTO `td_node` VALUES ('6', 'index', '后台管理默认页', '1', '0', '加载后台系统分帧布局', '1', '2', '3');
INSERT INTO `td_node` VALUES ('5', 'System', '系统管理', '1', '0', null, '9', '1', '2');
INSERT INTO `td_node` VALUES ('4', 'Access', '权限管理', '1', '0', null, '4', '1', '2');
INSERT INTO `td_node` VALUES ('3', 'User', '用户管理', '1', '0', null, '3', '1', '2');
INSERT INTO `td_node` VALUES ('2', 'Index', '后台管理首页', '1', '0', null, '2', '1', '2');
INSERT INTO `td_node` VALUES ('1', 'Admin', '后台管理', '1', '0', '网站后台管理项目', '1', '0', '1');

-- ----------------------------
-- Table structure for td_operate_log
-- ----------------------------
DROP TABLE IF EXISTS `td_operate_log`;
CREATE TABLE `td_operate_log` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `pid` int(11) NOT NULL DEFAULT '0' COMMENT '项目编号',
  `aid` int(11) NOT NULL DEFAULT '0',
  `type` tinyint(4) NOT NULL DEFAULT '0' COMMENT '0表示无状态1创建项目 2创建文件3编辑文件4删除文件5创建预算6修改预算7审批费用8创建业务9审批费用10完成项目',
  `remark` varchar(255) NOT NULL DEFAULT '' COMMENT '备注',
  `create_time` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of td_operate_log
-- ----------------------------

-- ----------------------------
-- Table structure for td_project
-- ----------------------------
DROP TABLE IF EXISTS `td_project`;
CREATE TABLE `td_project` (
  `pid` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL DEFAULT '' COMMENT '项目名称',
  `cid` int(11) NOT NULL DEFAULT '0' COMMENT '公司ID',
  `appraise` varchar(255) NOT NULL DEFAULT '' COMMENT '项目评价',
  `cost` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '项目花费',
  `create_time` int(11) NOT NULL DEFAULT '0' COMMENT '项目创建时间',
  `update_time` int(11) NOT NULL DEFAULT '0' COMMENT '项目更新时间',
  `status` tinyint(4) NOT NULL DEFAULT '0' COMMENT '默认为0表示未激活，1表示激活状态',
  `cover_image` varchar(255) NOT NULL DEFAULT '' COMMENT '项目封面',
  `is_end` tinyint(4) NOT NULL DEFAULT '0' COMMENT '项目执行状态0表示未开始，1表示开始，2表示结束',
  `remark` varchar(255) NOT NULL DEFAULT '' COMMENT '项目备注',
  PRIMARY KEY (`pid`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of td_project
-- ----------------------------
INSERT INTO `td_project` VALUES ('1', '测试项目一号', '2', '', '999.00', '1454240683', '1464748486', '1', '20160131/56adf3ab473c7.jpg', '2', '测试项目一号');
INSERT INTO `td_project` VALUES ('2', '测试项目二号', '9', '', '666111.00', '1454245108', '1454290737', '1', '20160131/56ae070e32c85.jpg', '1', '测试项目二号111');
INSERT INTO `td_project` VALUES ('3', '视觉识别系统修改', '9', '', '19603.00', '1461566741', '1464748476', '1', '20160425/571dbd15eb805.jpg', '2', '视觉识别系统修改');

-- ----------------------------
-- Table structure for td_project_money
-- ----------------------------
DROP TABLE IF EXISTS `td_project_money`;
CREATE TABLE `td_project_money` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `cid` int(11) NOT NULL DEFAULT '0' COMMENT '公司ID',
  `aid` int(11) NOT NULL DEFAULT '0' COMMENT '用户ID',
  `type` tinyint(4) NOT NULL DEFAULT '0' COMMENT '默认为0表示增加预算，1表示减少预算',
  `amount` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '项目花费',
  `status` tinyint(4) NOT NULL DEFAULT '0' COMMENT '0表示未通过审批，1表示通过审批',
  `create_time` int(11) NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(11) NOT NULL DEFAULT '0' COMMENT '上次更新时间',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of td_project_money
-- ----------------------------

-- ----------------------------
-- Table structure for td_project_user
-- ----------------------------
DROP TABLE IF EXISTS `td_project_user`;
CREATE TABLE `td_project_user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `pid` int(11) NOT NULL DEFAULT '0' COMMENT '项目ID',
  `aid` int(11) NOT NULL DEFAULT '0' COMMENT '用户ID',
  `role_id` smallint(6) NOT NULL DEFAULT '0' COMMENT '角色ID',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=25 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of td_project_user
-- ----------------------------
INSERT INTO `td_project_user` VALUES ('1', '2', '89009', '11');
INSERT INTO `td_project_user` VALUES ('2', '2', '89010', '11');
INSERT INTO `td_project_user` VALUES ('6', '2', '89008', '11');
INSERT INTO `td_project_user` VALUES ('4', '2', '89011', '12');
INSERT INTO `td_project_user` VALUES ('7', '1', '89009', '11');
INSERT INTO `td_project_user` VALUES ('8', '1', '89010', '11');
INSERT INTO `td_project_user` VALUES ('9', '1', '89004', '11');
INSERT INTO `td_project_user` VALUES ('10', '1', '89008', '11');
INSERT INTO `td_project_user` VALUES ('11', '1', '89006', '12');
INSERT INTO `td_project_user` VALUES ('12', '1', '89011', '12');
INSERT INTO `td_project_user` VALUES ('13', '1', '88997', '12');
INSERT INTO `td_project_user` VALUES ('14', '1', '89012', '12');
INSERT INTO `td_project_user` VALUES ('15', '1', '89000', '12');
INSERT INTO `td_project_user` VALUES ('16', '1', '89007', '12');
INSERT INTO `td_project_user` VALUES ('23', '3', '89005', '12');
INSERT INTO `td_project_user` VALUES ('18', '3', '89004', '11');
INSERT INTO `td_project_user` VALUES ('19', '3', '89011', '12');
INSERT INTO `td_project_user` VALUES ('20', '3', '88997', '12');
INSERT INTO `td_project_user` VALUES ('21', '3', '89012', '12');
INSERT INTO `td_project_user` VALUES ('22', '3', '89000', '12');
INSERT INTO `td_project_user` VALUES ('24', '1', '89005', '12');

-- ----------------------------
-- Table structure for td_role
-- ----------------------------
DROP TABLE IF EXISTS `td_role`;
CREATE TABLE `td_role` (
  `id` smallint(6) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(20) NOT NULL DEFAULT '',
  `pid` smallint(6) NOT NULL DEFAULT '0',
  `status` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `remark` varchar(255) NOT NULL DEFAULT '',
  `update_time` int(11) NOT NULL DEFAULT '0' COMMENT '更新时间',
  `create_time` int(11) NOT NULL DEFAULT '0' COMMENT '创建时间',
  `operator_id` int(11) NOT NULL DEFAULT '0' COMMENT '操作管理员id',
  PRIMARY KEY (`id`),
  KEY `pid` (`pid`),
  KEY `status` (`status`)
) ENGINE=MyISAM AUTO_INCREMENT=22 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='权限角色表';

-- ----------------------------
-- Records of td_role
-- ----------------------------
INSERT INTO `td_role` VALUES ('1', '站长', '0', '1', '系统内置超级管理员组，不受权限分配账号限制', '0', '0', '88995');
INSERT INTO `td_role` VALUES ('2', '管理员', '1', '1', '拥有系统仅此于超级管理员的权限', '1452836757', '1452836757', '88995');
INSERT INTO `td_role` VALUES ('3', '测试管理员01', '1', '1', '拥有所有操作的读权限，无增加、删除、修改的权限', '1452836757', '1452836757', '88995');
INSERT INTO `td_role` VALUES ('4', '测试管理员02', '1', '1', '测试管理员02', '1452836757', '1452836757', '88995');
INSERT INTO `td_role` VALUES ('5', '测试三级管理员001', '3', '1', '测试三级管理员001', '1435633820', '1435633592', '88995');
INSERT INTO `td_role` VALUES ('6', '测试三级管理员002', '2', '1', '测试三级管理员002描述', '1438071116', '1436170269', '88995');
INSERT INTO `td_role` VALUES ('7', '前台用户', '1', '1', '分配为前台用户角色', '1452692298', '1452692298', '88995');
INSERT INTO `td_role` VALUES ('8', '后台用户', '1', '1', '分配为后台角色', '1452692362', '1452692362', '88995');
INSERT INTO `td_role` VALUES ('9', '财务', '1', '1', '财务角色', '1452692390', '1452692390', '88995');
INSERT INTO `td_role` VALUES ('10', '主管', '1', '1', '主管', '1452692413', '1452692413', '88995');
INSERT INTO `td_role` VALUES ('11', '甲方', '7', '1', '甲方', '1453649074', '1453649074', '88995');
INSERT INTO `td_role` VALUES ('12', '乙方', '7', '1', '乙方', '1453649094', '1453649094', '88995');
INSERT INTO `td_role` VALUES ('15', '公司管理员', '8', '1', '公司管理员，有登录后台管理公司权限', '1454233622', '1454233622', '88995');
INSERT INTO `td_role` VALUES ('13', '甲方公司审批', '11', '1', '甲方公司审批角色', '1458006133', '1454233517', '88995');
INSERT INTO `td_role` VALUES ('14', '普通用户', '7', '1', '普通用户', '1454246920', '1454246920', '88995');
INSERT INTO `td_role` VALUES ('20', '甲方公司主管', '11', '1', '甲方公司主管角色', '1458006115', '1458006081', '88995');
INSERT INTO `td_role` VALUES ('21', '乙方公司主管', '12', '1', '乙方公司主管', '1458006102', '1458006102', '88995');

-- ----------------------------
-- Table structure for td_role_user
-- ----------------------------
DROP TABLE IF EXISTS `td_role_user`;
CREATE TABLE `td_role_user` (
  `role_id` mediumint(9) unsigned DEFAULT NULL,
  `user_id` char(32) DEFAULT NULL,
  KEY `group_id` (`role_id`) USING BTREE,
  KEY `user_id` (`user_id`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='用户角色表';

-- ----------------------------
-- Records of td_role_user
-- ----------------------------
INSERT INTO `td_role_user` VALUES ('1', '88995');
INSERT INTO `td_role_user` VALUES ('2', '88997');
INSERT INTO `td_role_user` VALUES ('6', '88998');
INSERT INTO `td_role_user` VALUES ('11', '89006');
INSERT INTO `td_role_user` VALUES ('12', '89011');
INSERT INTO `td_role_user` VALUES ('8', '88997');
INSERT INTO `td_role_user` VALUES ('15', '88997');
INSERT INTO `td_role_user` VALUES ('11', '89010');
INSERT INTO `td_role_user` VALUES ('7', '89002');
INSERT INTO `td_role_user` VALUES ('7', '89003');
INSERT INTO `td_role_user` VALUES ('11', '89004');
INSERT INTO `td_role_user` VALUES ('7', '89005');
INSERT INTO `td_role_user` VALUES ('21', '89005');
INSERT INTO `td_role_user` VALUES ('7', '89007');
INSERT INTO `td_role_user` VALUES ('11', '89003');
INSERT INTO `td_role_user` VALUES ('15', '89008');
INSERT INTO `td_role_user` VALUES ('7', '89004');
INSERT INTO `td_role_user` VALUES ('13', '89004');
INSERT INTO `td_role_user` VALUES ('15', '89003');
INSERT INTO `td_role_user` VALUES ('7', '89011');
INSERT INTO `td_role_user` VALUES ('11', '88999');
INSERT INTO `td_role_user` VALUES ('7', '89006');
INSERT INTO `td_role_user` VALUES ('7', '88999');
INSERT INTO `td_role_user` VALUES ('7', '89000');
INSERT INTO `td_role_user` VALUES ('7', '89009');
INSERT INTO `td_role_user` VALUES ('7', '89010');
INSERT INTO `td_role_user` VALUES ('8', '89008');
INSERT INTO `td_role_user` VALUES ('7', '89012');
INSERT INTO `td_role_user` VALUES ('7', '89013');
INSERT INTO `td_role_user` VALUES ('7', '89014');
INSERT INTO `td_role_user` VALUES ('7', '89016');
INSERT INTO `td_role_user` VALUES ('7', '89017');
INSERT INTO `td_role_user` VALUES ('7', '89018');
INSERT INTO `td_role_user` VALUES ('12', '88997');
INSERT INTO `td_role_user` VALUES ('12', '89012');
INSERT INTO `td_role_user` VALUES ('12', '89000');
INSERT INTO `td_role_user` VALUES ('12', '89007');
INSERT INTO `td_role_user` VALUES ('12', '89015');
INSERT INTO `td_role_user` VALUES ('12', '89005');
INSERT INTO `td_role_user` VALUES ('21', '89003');

-- ----------------------------
-- Table structure for td_system
-- ----------------------------
DROP TABLE IF EXISTS `td_system`;
CREATE TABLE `td_system` (
  `id` smallint(6) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL DEFAULT '' COMMENT '配置项名称',
  `value` varchar(255) NOT NULL DEFAULT '' COMMENT '配置项值',
  `status` tinyint(4) NOT NULL DEFAULT '0' COMMENT '默认为0表示不可用，1表示可用',
  `remark` varchar(255) NOT NULL DEFAULT '' COMMENT '配置项备注说明',
  `create_time` int(11) NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(11) NOT NULL DEFAULT '0' COMMENT '上次更新时间',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=8 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of td_system
-- ----------------------------
INSERT INTO `td_system` VALUES ('1', 'webname', '定位,标志设计,广告,公关,网络推广,全案-OPENED中小企业品牌助手', '1', '定位,标志设计,广告,公关,网络推广,全案-OPENED中小企业品牌助手', '1456370722', '1456370722');
INSERT INTO `td_system` VALUES ('2', 'keywords', '定位,标志设计,广告,公关,网络推广,全案', '1', '定位,标志设计,广告,公关,网络推广,全案', '1456370722', '1456370722');
INSERT INTO `td_system` VALUES ('3', 'description', '定位,标志设计,广告,公关,网络推广,全案-按满意度付费', '1', '定位,标志设计,广告,公关,网络推广,全案-按满意度付费', '1456370722', '1456370722');
INSERT INTO `td_system` VALUES ('4', 'seo_description', '定位,标志设计,广告,公关,网络推广,全案', '1', '定位,标志设计,广告,公关,网络推广,全案', '1456370722', '1456370722');
INSERT INTO `td_system` VALUES ('5', 'beian', '沪ICP备06046803', '1', '', '1456370722', '1456370722');
INSERT INTO `td_system` VALUES ('6', 'author', 'Lessismore', '1', '', '1456370722', '1456370722');
INSERT INTO `td_system` VALUES ('7', 'website', 'http://workshop.zhiliaoshu.com.cn', '1', '', '1456370722', '1456370722');

-- ----------------------------
-- Table structure for td_tag
-- ----------------------------
DROP TABLE IF EXISTS `td_tag`;
CREATE TABLE `td_tag` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT COMMENT 'id',
  `tagname` varchar(20) NOT NULL COMMENT '标签名称',
  `counts` mediumint(8) unsigned NOT NULL DEFAULT '1' COMMENT '点击量',
  `date` datetime NOT NULL COMMENT '添加时间',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=15 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Records of td_tag
-- ----------------------------
INSERT INTO `td_tag` VALUES ('1', 'ThinkPHP', '28', '2014-01-12 21:33:59');
INSERT INTO `td_tag` VALUES ('2', '框架', '9', '2014-01-12 21:33:59');
INSERT INTO `td_tag` VALUES ('3', 'PHP', '13', '2014-01-12 22:02:44');
INSERT INTO `td_tag` VALUES ('4', '单例模式', '11', '2014-01-12 22:02:44');
INSERT INTO `td_tag` VALUES ('5', '易宝', '9', '2014-01-12 22:55:50');
INSERT INTO `td_tag` VALUES ('6', '网上支付', '9', '2014-01-12 22:55:50');
INSERT INTO `td_tag` VALUES ('7', '接口', '12', '2014-01-12 22:55:50');
INSERT INTO `td_tag` VALUES ('8', '图形函数', '11', '2014-01-13 21:32:57');
INSERT INTO `td_tag` VALUES ('9', '74CMS', '165', '2014-01-16 00:22:45');
INSERT INTO `td_tag` VALUES ('10', '二次开发', '12', '2014-01-16 00:22:45');
INSERT INTO `td_tag` VALUES ('11', 'Ecshop', '7', '2014-01-17 22:57:34');
INSERT INTO `td_tag` VALUES ('12', '系统变量', '10', '2014-01-17 22:57:34');
INSERT INTO `td_tag` VALUES ('13', 'Smarty3', '3', '2014-01-21 23:39:43');
INSERT INTO `td_tag` VALUES ('14', '模板引擎', '3', '2014-01-21 23:39:43');

-- ----------------------------
-- Table structure for td_user
-- ----------------------------
DROP TABLE IF EXISTS `td_user`;
CREATE TABLE `td_user` (
  `uid` int(11) NOT NULL AUTO_INCREMENT,
  `nickname` varchar(20) NOT NULL DEFAULT '' COMMENT '昵称',
  `email` varchar(50) NOT NULL DEFAULT '' COMMENT '账户绑定邮箱',
  `username` varchar(50) NOT NULL DEFAULT '' COMMENT '用户名',
  `pwd` char(32) NOT NULL DEFAULT '' COMMENT '登录密码',
  `sex` tinyint(4) NOT NULL DEFAULT '0' COMMENT '管理员性别',
  `birthday` int(11) NOT NULL DEFAULT '0' COMMENT '会员生日',
  `avatar` varchar(255) NOT NULL DEFAULT '' COMMENT '用户头像',
  `mobile_number` varchar(54) NOT NULL DEFAULT '0' COMMENT '手机号',
  `qq` varchar(18) NOT NULL DEFAULT '0' COMMENT 'QQ号码',
  `personal_website` varchar(255) NOT NULL DEFAULT '' COMMENT '个人站点（评论时默认互粉站点）',
  `personal_website_clicks` int(11) NOT NULL DEFAULT '0' COMMENT '个人站点被点击次数',
  `status` tinyint(4) NOT NULL DEFAULT '1' COMMENT '账号状态',
  `is_del` tinyint(4) NOT NULL DEFAULT '0' COMMENT '该用户是否被管理员删除(0表示正常状态，1表示已删除)',
  `remark` varchar(255) NOT NULL DEFAULT '' COMMENT '备注信息',
  `find_code` char(5) DEFAULT '' COMMENT '找回账号验证码',
  `last_login_time` int(11) NOT NULL DEFAULT '0' COMMENT '上次登录时间',
  `last_login_ip` varchar(40) NOT NULL DEFAULT '0' COMMENT '上次登录ip',
  `create_time` int(11) NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(11) NOT NULL DEFAULT '0' COMMENT '更新时间',
  `operator_id` int(11) NOT NULL DEFAULT '0' COMMENT '操作员id',
  `login_count` int(11) NOT NULL DEFAULT '0' COMMENT '用户登录总次数',
  `login_error_log` tinyint(4) NOT NULL DEFAULT '0' COMMENT '登录错误次数log记录（5次锁定）',
  PRIMARY KEY (`uid`)
) ENGINE=MyISAM AUTO_INCREMENT=49 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='网站后台管理员表';

-- ----------------------------
-- Records of td_user
-- ----------------------------
INSERT INTO `td_user` VALUES ('1', '熊猫爱减肥', 'llgdesign@eputai.com', 'llgdesign', '1230b619ba1871e726a8d760bdbc78dc', '1', '0', 'user100_1686.jpg', '15001022995', '0', '', '0', '1', '0', '', '', '1393486000', '10.61.20.1', '1393486635', '1393486635', '0', '15', '0');
INSERT INTO `td_user` VALUES ('2', 'Cursor', 'Cursor@demo.com', 'cursor', '1230b619ba1871e726a8d760bdbc78dc', '1', '0', 'user100_1956.jpg', '18600632991', '0', '', '0', '0', '0', '', '', '1393476000', '127.0.0.1', '1393486735', '1393486735', '0', '10', '0');
INSERT INTO `td_user` VALUES ('3', 'Demo', 'qiangshiluo@126.com', 'demo', '1230b619ba1871e726a8d760bdbc78dc', '1', '0', 'user100_1526.jpg', '18600632991', '0', '', '0', '1', '0', '', '', '1393486989', '127.0.0.1', '1393456989', '1393486989', '0', '50', '0');
INSERT INTO `td_user` VALUES ('4', '陈玉秀', 'chenyuxiu@putai.com', 'chenyuxiu', '1230b619ba1871e726a8d760bdbc78dc', '2', '0', 'user100_1691.jpg', '15811546186', '0', '', '0', '1', '0', '', '', '1393811325', '127.0.0.1', '1393811325', '1393811325', '0', '0', '0');
INSERT INTO `td_user` VALUES ('5', '张悦', 'zhangyue@eputai.com', 'zhangyue', '1230b619ba1871e726a8d760bdbc78dc', '1', '0', 'user100_1581.jpg', '13683203643', '0', '', '0', '0', '0', '', '', '1393810325', '127.0.0.1', '1393810325', '1393810325', '0', '0', '0');
INSERT INTO `td_user` VALUES ('6', '张营', 'zhangying@eputai.com', 'zhangying', '1230b619ba1871e726a8d760bdbc78dc', '1', '0', 'user100_1566.jpg', '13810010535', '0', '', '0', '0', '0', '', '', '1391138395', '127.0.0.1', '1391138395', '1391138395', '0', '0', '0');
INSERT INTO `td_user` VALUES ('7', '阿达', 'shengxianda@eputai.com', 'shengxianda', '1230b619ba1871e726a8d760bdbc78dc', '1', '0', 'user100_1521.jpg', '13811601796', '0', '', '0', '0', '0', '', '', '1391138395', '127.0.0.1', '1391138395', '1391138395', '0', '0', '0');
INSERT INTO `td_user` VALUES ('8', '张少华', 'zhangshaohua@eputai.com', 'zhangshaohua', '1230b619ba1871e726a8d760bdbc78dc', '1', '0', 'user100_1336.jpg', '13466640706', '0', '', '0', '0', '0', '', '', '1391138395', '127.0.0.1', '1391138395', '1391138395', '0', '0', '0');
INSERT INTO `td_user` VALUES ('9', 'demo001', 'demo001@demo.com', 'demo001', '1230b619ba1871e726a8d760bdbc78dc', '0', '0', 'user100_1906.jpg', '0', '0', '', '0', '1', '0', '测试demo001', '', '0', '0', '1391138395', '1391138395', '0', '0', '0');
INSERT INTO `td_user` VALUES ('10', 'demo002', 'demo002@demo.com', 'demo002', '1230b619ba1871e726a8d760bdbc78dc', '0', '0', 'user100_1801.jpg', '0', '0', '', '0', '1', '0', '', '', '0', '0', '1394160770', '1445246339', '0', '0', '0');
INSERT INTO `td_user` VALUES ('11', '山里来的野娃子', 'wushanlin@demo.com', 'wushanlin', '1230b619ba1871e726a8d760bdbc78dc', '1', '0', 'user100_1831.jpg', '1887887887', '88995', '', '0', '1', '0', '请叫我野娃儿！！！', '', '0', '0', '1445246339', '1445246339', '0', '0', '0');
INSERT INTO `td_user` VALUES ('46', 'ceshi01', 'qiangshiluo@126.com', 'ceshi01', 'b926497293040911c160ba4b2f185288', '0', '0', 'no_face.png', '0', '0', '', '0', '1', '0', '', '', '0', '0', '1449130864', '1449132688', '0', '0', '0');
INSERT INTO `td_user` VALUES ('47', 'ceshi02', 'qiangshiluo@126.com', 'ceshi02', '737b137c5b768459520bb216d5f990af', '0', '0', 'no_face.png', '0', '0', '', '0', '1', '0', '', '', '0', '0', '1449130944', '1449130944', '0', '0', '0');
INSERT INTO `td_user` VALUES ('48', 'ceshi03', 'qiangshiluo@126.com', 'ceshi03', '7506915fa7f585ab0808f1a345183c4f', '0', '0', 'no_face.png', '0', '0', '', '0', '1', '0', '', '', '0', '0', '1449132733', '1449132733', '0', '0', '0');
