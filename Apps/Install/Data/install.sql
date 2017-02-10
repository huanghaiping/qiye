/*
MySQL Data Transfer
Source Host: localhost
Source Database: empty_qiye
Target Host: localhost
Target Database: empty_qiye
Date: 2016/9/1 10:39:37
*/

SET FOREIGN_KEY_CHECKS=0;
-- ----------------------------
-- Table structure for db_ad
-- ----------------------------
DROP TABLE IF EXISTS `db_ad`;
CREATE TABLE `db_ad` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `adname` varchar(50) NOT NULL DEFAULT '',
  `adid` varchar(30) NOT NULL DEFAULT '',
  `title` varchar(255) NOT NULL DEFAULT '',
  `typeid` smallint(10) NOT NULL DEFAULT '0',
  `normbody` text,
  `url` varchar(255) NOT NULL DEFAULT '',
  `count` int(11) NOT NULL DEFAULT '0',
  `imgurl` varchar(255) NOT NULL DEFAULT '',
  `lang` char(10) NOT NULL DEFAULT 'cn',
  `ctime` varchar(10) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for db_admin
-- ----------------------------
DROP TABLE IF EXISTS `db_admin`;
CREATE TABLE `db_admin` (
  `user_id` int(11) NOT NULL AUTO_INCREMENT,
  `role_id` int(11) NOT NULL DEFAULT '0',
  `nickname` varchar(50) NOT NULL DEFAULT '',
  `email` varchar(50) NOT NULL DEFAULT '',
  `pwd` varchar(64) NOT NULL DEFAULT '',
  `status` smallint(5) NOT NULL DEFAULT '0',
  `remark` varchar(255) NOT NULL DEFAULT '',
  `logintime` int(10) NOT NULL DEFAULT '0',
  `ip` varchar(255) NOT NULL DEFAULT '',
  `addtime` int(10) NOT NULL DEFAULT '0',
  PRIMARY KEY (`user_id`),
  KEY `role_id` (`role_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='网站后台管理员表';

-- ----------------------------
-- Table structure for db_admin_access
-- ----------------------------
DROP TABLE IF EXISTS `db_admin_access`;
CREATE TABLE `db_admin_access` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `role_id` int(11) NOT NULL DEFAULT '0',
  `node_id` int(11) NOT NULL DEFAULT '0',
  `level` smallint(5) NOT NULL DEFAULT '1',
  `pid` int(11) NOT NULL DEFAULT '0',
  `module` varchar(20) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  KEY `role_id` (`role_id`),
  KEY `node_id` (`node_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='权限分配表';

-- ----------------------------
-- Table structure for db_admin_node
-- ----------------------------
DROP TABLE IF EXISTS `db_admin_node`;
CREATE TABLE `db_admin_node` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL DEFAULT '',
  `title` varchar(50) NOT NULL DEFAULT '',
  `status` smallint(5) NOT NULL DEFAULT '1',
  `remark` varchar(128) NOT NULL DEFAULT '',
  `sort` int(11) NOT NULL DEFAULT '0',
  `pid` int(11) NOT NULL DEFAULT '0',
  `level` tinyint(5) NOT NULL DEFAULT '1',
  `module` varchar(30) NOT NULL DEFAULT '',
  `module_id` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `name` (`name`),
  KEY `pid` (`pid`),
  KEY `level` (`level`),
  KEY `status` (`status`)
) ENGINE=MyISAM AUTO_INCREMENT=252 DEFAULT CHARSET=utf8 COMMENT='权限节点表';

-- ----------------------------
-- Table structure for db_admin_role
-- ----------------------------
DROP TABLE IF EXISTS `db_admin_role`;
CREATE TABLE `db_admin_role` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL DEFAULT '',
  `pid` int(11) NOT NULL DEFAULT '0',
  `status` smallint(5) NOT NULL DEFAULT '0',
  `remark` varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COMMENT='权限角色表';

-- ----------------------------
-- Table structure for db_area
-- ----------------------------
DROP TABLE IF EXISTS `db_area`;
CREATE TABLE `db_area` (
  `id` smallint(6) unsigned NOT NULL AUTO_INCREMENT COMMENT '地区id',
  `parent_id` smallint(6) unsigned NOT NULL DEFAULT '0' COMMENT '地区父id',
  `area_name` varchar(120) NOT NULL DEFAULT '' COMMENT '地区名称',
  `area_type` tinyint(1) NOT NULL DEFAULT '2' COMMENT '地区类型 0:country,1:province,2:city,3:district',
  `listorder` int(11) DEFAULT NULL,
  `recommend` tinyint(4) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `parent_id` (`parent_id`),
  KEY `area_type` (`area_type`)
) ENGINE=MyISAM AUTO_INCREMENT=3412 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for db_block
-- ----------------------------
DROP TABLE IF EXISTS `db_block`;
CREATE TABLE `db_block` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `pos` char(30) NOT NULL DEFAULT '',
  `name` varchar(30) NOT NULL DEFAULT '',
  `lang` varchar(10) NOT NULL DEFAULT 'cn',
  `link` varchar(255) NOT NULL DEFAULT '',
  `content` text,
  PRIMARY KEY (`id`),
  KEY `pos` (`pos`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for db_cart
-- ----------------------------
DROP TABLE IF EXISTS `db_cart`;
CREATE TABLE `db_cart` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `session_id` char(32) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL DEFAULT '',
  `moduleid` mediumint(8) NOT NULL DEFAULT '0',
  `goods_id` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `goods_name` varchar(120) NOT NULL DEFAULT '',
  `market_price` decimal(10,2) unsigned NOT NULL DEFAULT '0.00',
  `goods_price` decimal(10,2) NOT NULL DEFAULT '0.00',
  `promotion_price` decimal(10,2) NOT NULL DEFAULT '0.00',
  `goods_number` smallint(5) unsigned NOT NULL DEFAULT '0',
  `total_price` decimal(10,2) NOT NULL DEFAULT '0.00',
  `goods_attr` text,
  `ctime` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `session_id` (`session_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for db_field
-- ----------------------------
DROP TABLE IF EXISTS `db_field`;
CREATE TABLE `db_field` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `moduleid` tinyint(3) NOT NULL DEFAULT '0',
  `field` varchar(20) NOT NULL DEFAULT '',
  `name` varchar(50) NOT NULL DEFAULT '',
  `tips` varchar(150) NOT NULL DEFAULT '',
  `type` varchar(20) NOT NULL DEFAULT '',
  `type_txt` varchar(128) NOT NULL DEFAULT '',
  `setup` mediumtext,
  `required` tinyint(1) NOT NULL DEFAULT '0',
  `issystem` tinyint(1) NOT NULL DEFAULT '0',
  `listorder` int(10) NOT NULL DEFAULT '0',
  `status` tinyint(1) NOT NULL DEFAULT '0',
  `minlength` int(10) NOT NULL DEFAULT '0',
  `maxlength` int(10) NOT NULL DEFAULT '0',
  `pattern` varchar(128) NOT NULL DEFAULT '',
  `ispost` tinyint(1) NOT NULL DEFAULT '1',
  `validate` varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  KEY `moduleid` (`moduleid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for db_guestbook
-- ----------------------------
DROP TABLE IF EXISTS `db_guestbook`;
CREATE TABLE `db_guestbook` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL DEFAULT '',
  `username` varchar(128) NOT NULL DEFAULT '',
  `email` varchar(64) NOT NULL DEFAULT '',
  `content` text,
  `status` tinyint(1) NOT NULL DEFAULT '0',
  `lang` char(10) NOT NULL DEFAULT 'cn',
  `ctime` int(10) NOT NULL  DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for db_kefu
-- ----------------------------
DROP TABLE IF EXISTS `db_kefu`;
CREATE TABLE `db_kefu` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL DEFAULT '',
  `typeid` tinyint(2) NOT NULL  DEFAULT '0',
  `lang` varchar(10) NOT NULL  DEFAULT 'cn',
  `status` tinyint(1) NOT NULL DEFAULT '0',
  `listorder` int(10) NOT NULL  DEFAULT '0',
  `content` varchar(500) NOT NULL DEFAULT '',
  `logo` varchar(255) NOT NULL DEFAULT '',
  `pay_config` text,
  `ctime` int(10) NOT NULL  DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `typeid` (`typeid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for db_lang
-- ----------------------------
DROP TABLE IF EXISTS `db_lang`;
CREATE TABLE `db_lang` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `name` varchar(30) NOT NULL DEFAULT '',
  `mark` varchar(30) NOT NULL DEFAULT '',
  `flag` varchar(100) NOT NULL DEFAULT '',
  `status` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `path` varchar(30) NOT NULL DEFAULT '',
  `domain` varchar(30) NOT NULL DEFAULT '',
  `listorder` smallint(5) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=10 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for db_lang_param
-- ----------------------------
DROP TABLE IF EXISTS `db_lang_param`;
CREATE TABLE `db_lang_param` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `lang_id` tinyint(1) NOT NULL  DEFAULT '0',
  `mark` char(10) NOT NULL DEFAULT '',
  `field` varchar(128) NOT NULL DEFAULT '',
  `value` varchar(500) NOT NULL DEFAULT '',
  `alisa` varchar(255) NOT NULL DEFAULT '',
  `ctime` int(11) NOT NULL   DEFAULT '0' ,
  PRIMARY KEY (`id`),
  KEY `lang_id` (`lang_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for db_link
-- ----------------------------
DROP TABLE IF EXISTS `db_link`;
CREATE TABLE `db_link` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL DEFAULT '',
  `siteurl` varchar(150) NOT NULL DEFAULT '',
  `linktype` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `logo` varchar(80) NOT NULL DEFAULT '',
  `status` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `listorder` int(10) unsigned NOT NULL DEFAULT '0',
  `lang` char(10) NOT NULL DEFAULT 'cn',
  `createtime` int(11) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for db_login_api
-- ----------------------------
DROP TABLE IF EXISTS `db_login_api`;
CREATE TABLE `db_login_api` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(128) NOT NULL DEFAULT '',
  `typename` char(32) NOT NULL DEFAULT '',
  `appkey` varchar(128) NOT NULL DEFAULT '',
  `appsecret` varchar(128) NOT NULL DEFAULT '',
  `status` tinyint(1) NOT NULL DEFAULT '0',
  `listorder` int(10) NOT NULL DEFAULT '0',
  `description` varchar(255) NOT NULL DEFAULT '',
  `ctime` int(11) NOT NULL  DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `typename` (`typename`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for db_menu
-- ----------------------------
DROP TABLE IF EXISTS `db_menu`;
CREATE TABLE `db_menu` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `parent_id` int(11) NOT NULL DEFAULT '0',
  `typeid` tinyint(1) NOT NULL DEFAULT '0',
  `title` varchar(128) NOT NULL DEFAULT '',
  `en_title` varchar(128) NOT NULL DEFAULT '',
  `model_name` varchar(50) NOT NULL DEFAULT '',
  `param` varchar(128) NOT NULL DEFAULT '',
  `thumb` varchar(255) NOT NULL DEFAULT '',
  `route` varchar(128) NOT NULL DEFAULT '',
  `site_title` varchar(255) NOT NULL DEFAULT '',
  `site_keyword` varchar(255) NOT NULL DEFAULT '',
  `site_description` varchar(255) NOT NULL DEFAULT '',
  `status` smallint(5) NOT NULL DEFAULT '1',
  `position` tinyint(1) unsigned zerofill NOT NULL DEFAULT '0',
  `sort` int(11) NOT NULL DEFAULT '50',
  `pagesize` int(10) NOT NULL  DEFAULT '0',
  `lang` char(10) NOT NULL DEFAULT 'cn',
  `template_list` varchar(128) NOT NULL DEFAULT '',
  `template_show` varchar(128) NOT NULL DEFAULT '',
  `listtype` tinyint(1) NOT NULL DEFAULT '0',
  `ctime` int(10) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `model_name` (`model_name`),
  KEY `parent_id` (`parent_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for db_menu_info
-- ----------------------------
DROP TABLE IF EXISTS `db_menu_info`;
CREATE TABLE `db_menu_info` (
  `menu_id` int(11) NOT NULL,
  `content` mediumtext,
  PRIMARY KEY (`menu_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for db_module
-- ----------------------------
DROP TABLE IF EXISTS `db_module`;
CREATE TABLE `db_module` (
  `id` tinyint(3) NOT NULL AUTO_INCREMENT,
  `title` varchar(100) NOT NULL DEFAULT '',
  `name` varchar(50) NOT NULL DEFAULT '',
  `controller_name` varchar(128) NOT NULL DEFAULT '',
  `description` varchar(200) NOT NULL DEFAULT '',
  `listfields` varchar(255) NOT NULL DEFAULT '',
  `template` varchar(128) NOT NULL DEFAULT '',
  `status` tinyint(1) NOT NULL DEFAULT '0',
  `ctime` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `controller_name` (`controller_name`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for db_order
-- ----------------------------
DROP TABLE IF EXISTS `db_order`;
CREATE TABLE `db_order` (
  `order_id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `order_sn` varchar(20) NOT NULL DEFAULT '',
  `user_id` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `order_status` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `pay_status` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `consignee` varchar(60) NOT NULL DEFAULT '',
  `location` varchar(128) NOT NULL DEFAULT '',
  `province` smallint(5) unsigned NOT NULL DEFAULT '0',
  `city` smallint(5) unsigned NOT NULL DEFAULT '0',
  `district` smallint(5) unsigned NOT NULL DEFAULT '0',
  `address` varchar(255) NOT NULL DEFAULT '',
  `tel` varchar(60) NOT NULL DEFAULT '',
  `mobile` varchar(60) NOT NULL DEFAULT '',
  `email` varchar(60) NOT NULL DEFAULT '',
  `pay_id` char(10) NOT NULL DEFAULT '0',
  `pay_name` varchar(120) NOT NULL DEFAULT '',
  `goods_amount` decimal(10,2) NOT NULL DEFAULT '0.00',
  `pay_fee` decimal(10,2) NOT NULL DEFAULT '0.00',
  `order_amount` decimal(10,2) NOT NULL DEFAULT '0.00',
  `order_mark` varchar(255) NOT NULL DEFAULT '',
  `add_time` int(10) unsigned NOT NULL DEFAULT '0',
  `pay_time` int(10) unsigned NOT NULL DEFAULT '0',
  `cancel_time` int(10) NOT NULL DEFAULT '0',
  `reason` varchar(255) NOT NULL DEFAULT '',
  `lang` char(10) NOT NULL DEFAULT 'cn',
  PRIMARY KEY (`order_id`),
  UNIQUE KEY `order_sn` (`order_sn`),
  KEY `user_id` (`user_id`),
  KEY `order_status` (`order_status`),
  KEY `pay_status` (`pay_status`),
  KEY `pay_id` (`pay_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for db_order_goods
-- ----------------------------
DROP TABLE IF EXISTS `db_order_goods`;
CREATE TABLE `db_order_goods` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `order_id` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `moduleid` mediumint(8) NOT NULL  DEFAULT '0',
  `goods_id` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `goods_name` varchar(120) NOT NULL DEFAULT '',
  `goods_number` smallint(5) unsigned NOT NULL DEFAULT '1',
  `market_price` decimal(10,2) NOT NULL DEFAULT '0.00',
  `goods_price` decimal(10,2) NOT NULL DEFAULT '0.00',
  `promotion_price` decimal(10,2) NOT NULL DEFAULT '0.00',
  `total_price` decimal(10,2) NOT NULL DEFAULT '0.00',
  `goods_attr` text,
  PRIMARY KEY (`id`),
  KEY `order_id` (`order_id`),
  KEY `goods_id` (`goods_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for db_pay_api
-- ----------------------------
DROP TABLE IF EXISTS `db_pay_api`;
CREATE TABLE `db_pay_api` (
  `id` tinyint(3) unsigned NOT NULL AUTO_INCREMENT,
  `pay_code` varchar(20) NOT NULL DEFAULT '',
  `pay_name` varchar(120) NOT NULL DEFAULT '',
  `pay_fee` varchar(10) NOT NULL DEFAULT '0',
  `pay_desc` text,
  `pay_config` text,
  `status` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `is_cod` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `is_online` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `listorder` int(50) NOT NULL  DEFAULT '0',
  `jumpurl` varchar(255) NOT NULL DEFAULT '',
  `ctime` int(11) NOT NULL  DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `pay_code` (`pay_code`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for db_posid
-- ----------------------------
DROP TABLE IF EXISTS `db_posid`;
CREATE TABLE `db_posid` (
  `id` tinyint(2) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(40) NOT NULL DEFAULT '',
  `catId` tinyint(1) NOT NULL DEFAULT '0',
  `listorder` tinyint(2) unsigned NOT NULL DEFAULT '0',
  `ctime` int(10) NOT NULL  DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for db_site
-- ----------------------------
DROP TABLE IF EXISTS `db_site`;
CREATE TABLE `db_site` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `groupid` tinyint(1) NOT NULL  DEFAULT '0',
  `varname` varchar(32) NOT NULL DEFAULT '',
  `info` varchar(65) NOT NULL DEFAULT '',
  `value` text,
  `lang` char(10) NOT NULL DEFAULT '',
  `input_type` char(20) NOT NULL DEFAULT '',
  `mark` varchar(255) NOT NULL DEFAULT '',
  `html_text` varchar(255) NOT NULL DEFAULT '',
  `ctime` int(10) NOT NULL  DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `varname` (`varname`)
) ENGINE=MyISAM AUTO_INCREMENT=29 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for db_slide
-- ----------------------------
DROP TABLE IF EXISTS `db_slide`;
CREATE TABLE `db_slide` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `typeid` int(11) NOT NULL DEFAULT '0',
  `title` varchar(255) NOT NULL DEFAULT '',
  `height` int(11) NOT NULL DEFAULT '0',
  `width` int(11) NOT NULL DEFAULT '0',
  `linkurl` varchar(255) NOT NULL DEFAULT '',
  `picurl` varchar(255) NOT NULL DEFAULT '',
  `sortslide` int(11) NOT NULL DEFAULT '0',
  `product_title` varchar(255) NOT NULL DEFAULT '',
  `product_description` varchar(255) NOT NULL DEFAULT '',
  `ctime` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `typeid` (`typeid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for db_template
-- ----------------------------
DROP TABLE IF EXISTS `db_template`;
CREATE TABLE `db_template` (
  `id` int(10) NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `temp_title` varchar(255) NOT NULL DEFAULT '' COMMENT '节点名称',
  `temp_key` varchar(128) NOT NULL DEFAULT '' COMMENT '应用名称',
  `content_key` text COMMENT '内容key',
  `title_key` varchar(255) NOT NULL DEFAULT '' COMMENT '标题key',
  `send_email` tinyint(2) NOT NULL DEFAULT '1' COMMENT '是否发送邮件',
  `send_message` tinyint(2) NOT NULL DEFAULT '1' COMMENT '是否发送消息',
  `tip_message` varchar(255) NOT NULL DEFAULT '',
  `type` tinyint(2) NOT NULL DEFAULT '1' COMMENT '信息类型：1 表示用户发送的 2表示是系统发送的',
  `lang` char(10) NOT NULL DEFAULT 'cn',
  PRIMARY KEY (`id`),
  UNIQUE KEY `temp_key` (`temp_key`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for db_upgrade
-- ----------------------------
DROP TABLE IF EXISTS `db_upgrade`;
CREATE TABLE `db_upgrade` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `token` varchar(64) NOT NULL DEFAULT '',
  `website` varchar(64) NOT NULL DEFAULT '',
  `soft_name` varchar(64) NOT NULL DEFAULT '',
  `soft_version` varchar(32) NOT NULL DEFAULT '',
  `uptime` int(10) NOT NULL  DEFAULT '0',
  `status` tinyint(1) NOT NULL DEFAULT '0',
  `up_name` varchar(64) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for db_user
-- ----------------------------
DROP TABLE IF EXISTS `db_user`;
CREATE TABLE `db_user` (
  `uid` int(11) NOT NULL AUTO_INCREMENT,
  `email` varchar(128) NOT NULL DEFAULT '',
  `mobile` varchar(32) NOT NULL DEFAULT '',
  `nickname` varchar(128) NOT NULL DEFAULT '',
  `password` varchar(64) NOT NULL DEFAULT '',
  `status` tinyint(2) NOT NULL DEFAULT '0',
  `mtype` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0web,1ios,2android',
  `usertype` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0web,1qq,2sina',
  `faceurl` varchar(128) NOT NULL DEFAULT '',
  `money` float(10,2) NOT NULL DEFAULT '0.00',
  `group_id` tinyint(1) NOT NULL DEFAULT '0',
  `buy_num` smallint(5) NOT NULL DEFAULT '0',
  `login_time` int(10) NOT NULL DEFAULT '0',
  PRIMARY KEY (`uid`),
  KEY `nickname` (`nickname`),
  KEY `email` (`email`),
  KEY `mobile` (`mobile`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for db_user_address
-- ----------------------------
DROP TABLE IF EXISTS `db_user_address`;
CREATE TABLE `db_user_address` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) NOT NULL,
  `consignee` varchar(128) NOT NULL DEFAULT '',
  `province` smallint(5) NOT NULL DEFAULT '0',
  `city` smallint(5) NOT NULL DEFAULT '0',
  `county` smallint(5) NOT NULL DEFAULT '0',
  `location` varchar(128) NOT NULL DEFAULT '',
  `address` varchar(255) NOT NULL DEFAULT '',
  `mobile` varchar(64) NOT NULL DEFAULT '',
  `telphone` varchar(64) DEFAULT NULL,
  `email` varchar(64) NOT NULL DEFAULT '',
  `is_default` tinyint(1) DEFAULT '0',
  `ctime` int(11) NOT NULL  DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `uid` (`uid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for db_user_group
-- ----------------------------
DROP TABLE IF EXISTS `db_user_group`;
CREATE TABLE `db_user_group` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL DEFAULT '',
  `discount` varchar(5) NOT NULL DEFAULT '0.00',
  `buy_num` int(11) NOT NULL  DEFAULT '0',
  `sortby` int(11) NOT NULL  DEFAULT '0',
  `status` tinyint(1) NOT NULL DEFAULT '0',
  `lang` char(10) NOT NULL DEFAULT 'cn',
  `ctime` int(11) NOT NULL  DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for db_user_info
-- ----------------------------
DROP TABLE IF EXISTS `db_user_info`;
CREATE TABLE `db_user_info` (
  `mid` int(11) NOT NULL,
  `sex` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0 保密，1男，2女',
  `loation` varchar(128) NOT NULL DEFAULT '',
  `province` int(11) NOT NULL  DEFAULT '0',
  `city` int(11) NOT NULL  DEFAULT '0',
  `area` int(11) NOT NULL  DEFAULT '0',
  `constellation` tinyint(5) NOT NULL  DEFAULT '0',
  `userip` varchar(32) NOT NULL DEFAULT '',
  `is_email` tinyint(1) NOT NULL DEFAULT '0',
  `is_tel` tinyint(1) NOT NULL DEFAULT '0',
  `truename` varchar(255) NOT NULL DEFAULT '',
  `company` varchar(255) NOT NULL DEFAULT '',
  `position` varchar(255) NOT NULL DEFAULT '',
  `good_skills` varchar(255) NOT NULL DEFAULT '',
  `description` varchar(500) NOT NULL DEFAULT '',
  `reg_time` int(10) NOT NULL  DEFAULT '0',
  `update_time` int(10) NOT NULL DEFAULT '0',
  PRIMARY KEY (`mid`),
  UNIQUE KEY `mid` (`mid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for db_user_open
-- ----------------------------
DROP TABLE IF EXISTS `db_user_open`;
CREATE TABLE `db_user_open` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `member_id` int(11) NOT NULL,
  `type` smallint(5) NOT NULL  DEFAULT '0',
  `openid` varchar(128) NOT NULL DEFAULT '',
  `access_token` varchar(128) NOT NULL DEFAULT '',
  `username` varchar(50) NOT NULL DEFAULT '',
  `create_date` int(10) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `openid` (`openid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for db_verify
-- ----------------------------
DROP TABLE IF EXISTS `db_verify`;
CREATE TABLE `db_verify` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `mobile` varchar(20) NOT NULL DEFAULT '',
  `type` smallint(5) NOT NULL DEFAULT '1',
  `verify` int(6) NOT NULL  DEFAULT '0',
  `status` tinyint(2) NOT NULL DEFAULT '0',
  `return_status` varchar(128) NOT NULL DEFAULT '',
  `userip` varchar(64) NOT NULL DEFAULT '',
  `ctime` int(10) NOT NULL  DEFAULT '0',
  `check_time` int(10) NOT NULL  DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `mobile` (`mobile`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for db_wechat
-- ----------------------------
DROP TABLE IF EXISTS `db_wechat`;
CREATE TABLE `db_wechat` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `token` varchar(128) DEFAULT NULL,
  `name` varchar(128) NOT NULL DEFAULT '',
  `originid` varchar(128) NOT NULL DEFAULT '',
  `wechat_name` varchar(128) NOT NULL DEFAULT '',
  `wechat_type` tinyint(1) NOT NULL DEFAULT '1' COMMENT '1 订阅号,2认证订阅号/普通服务号,3认证服务号',
  `wechat_thumb` varchar(255) NOT NULL DEFAULT '',
  `wechat_token` varchar(255) NOT NULL DEFAULT '',
  `appid` varchar(128) NOT NULL DEFAULT '',
  `appsecret` varchar(128) NOT NULL DEFAULT '',
  `encodingaeskey` varchar(128) NOT NULL DEFAULT '',
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `ctime` int(11) NOT NULL  DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `token` (`token`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for db_wechat_card
-- ----------------------------
DROP TABLE IF EXISTS `db_wechat_card`;
CREATE TABLE `db_wechat_card` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `wechat_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL DEFAULT '',
  `prifx` varchar(128) NOT NULL DEFAULT '',
  `card_num` varchar(128) NOT NULL DEFAULT '',
  `content` text,
  `address` varchar(255) NOT NULL DEFAULT '',
  `dianhua` varchar(128) NOT NULL DEFAULT '',
  `website` varchar(255) NOT NULL DEFAULT '',
  `ctime` int(11) NOT NULL  DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `wechat_id` (`wechat_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for db_wechat_menu
-- ----------------------------
DROP TABLE IF EXISTS `db_wechat_menu`;
CREATE TABLE `db_wechat_menu` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `wechat_id` int(11) NOT NULL,
  `parent_id` int(11) NOT NULL,
  `menu_name` varchar(255) NOT NULL DEFAULT '',
  `wechat_event` varchar(128) NOT NULL DEFAULT '',
  `replay_keyword` varchar(255) NOT NULL DEFAULT '',
  `url` varchar(255) NOT NULL DEFAULT '',
  `sort` int(10) NOT NULL DEFAULT '0',
  `status` tinyint(4) NOT NULL DEFAULT '1',
  `media_id` varchar(255) NOT NULL DEFAULT '',
  `ctime` int(10) NOT NULL  DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `wechat_id` (`wechat_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for db_wechat_page
-- ----------------------------
DROP TABLE IF EXISTS `db_wechat_page`;
CREATE TABLE `db_wechat_page` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `wechat_id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL DEFAULT '',
  `thumb` varchar(255) NOT NULL DEFAULT '',
  `content` varchar(500) NOT NULL DEFAULT '',
  `copyright` varchar(255) NOT NULL DEFAULT '',
  `ctime` int(11) NOT NULL  DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for db_wechat_reply
-- ----------------------------
DROP TABLE IF EXISTS `db_wechat_reply`;
CREATE TABLE `db_wechat_reply` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `reply_id` tinyint(1) NOT NULL DEFAULT '0' COMMENT '1图文,2多图,3文本',
  `wechat_id` int(11) NOT NULL  DEFAULT '0',
  `keyword` varchar(255) NOT NULL DEFAULT '',
  `keyword_type` tinyint(1) NOT NULL  DEFAULT '0',
  `title` varchar(255) NOT NULL DEFAULT '',
  `thumb` varchar(255) NOT NULL DEFAULT '',
  `description` varchar(255) NOT NULL DEFAULT '',
  `content` text,
  `url` varchar(255) NOT NULL DEFAULT '',
  `view_count` int(10) NOT NULL DEFAULT '0',
  `mult_ids` varchar(255) NOT NULL DEFAULT '',
  `listorder` int(10) NOT NULL DEFAULT '0',
  `ctime` int(10) NOT NULL  DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `wechat_id` (`wechat_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for db_wechat_welcome
-- ----------------------------
DROP TABLE IF EXISTS `db_wechat_welcome`;
CREATE TABLE `db_wechat_welcome` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `wechat_id` int(10) NOT NULL,
  `welcome_type` tinyint(1) NOT NULL  DEFAULT '0',
  `title` varchar(255) NOT NULL DEFAULT '',
  `thumb` varchar(255) NOT NULL DEFAULT '',
  `content` varchar(500) NOT NULL DEFAULT '',
  `url` varchar(255) NOT NULL DEFAULT '',
  `ctime` int(10) NOT NULL  DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `wechat_id` (`wechat_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for db_search_keyword
-- ----------------------------
CREATE TABLE `db_search_keyword` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `keyword` varchar(255) NOT NULL,
  `num` int(11) NOT NULL DEFAULT '0',
  `lang` char(10) NOT NULL,
  `jumpurl` varchar(255) NOT NULL DEFAULT '',
  `ctime` int(10) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `keyword` (`keyword`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records 
-- ----------------------------
INSERT INTO `db_admin_node` VALUES ('1', 'admin', '系统设置', '1', '网站后台管理项目', '10', '0', '1', '', '0');
INSERT INTO `db_admin_node` VALUES ('2', 'Index', '后台首页显示', '1', '管理首页', '1', '44', '2', 'main', '0');
INSERT INTO `db_admin_node` VALUES ('3', 'index', '默认后台首页', '1', '默认页', '50', '2', '3', '', '0');
INSERT INTO `db_admin_node` VALUES ('4', 'main', '综合提醒', '1', '查看服务器信息', '3', '2', '3', '', '0');
INSERT INTO `db_admin_node` VALUES ('5', 'Admin', '管理员管理', '1', '权限管理', '28', '86', '2', '', '0');
INSERT INTO `db_admin_node` VALUES ('6', 'index', '管理员列表', '1', '管理员的首页列表', '100', '5', '3', '', '0');
INSERT INTO `db_admin_node` VALUES ('7', 'add', '添加管理员', '1', '添加管理员用户', '10', '5', '3', '', '0');
INSERT INTO `db_admin_node` VALUES ('8', 'saveAdd', '保存添加的管理员数据', '1', '保存添加的管理员数据', '11', '5', '3', '', '0');
INSERT INTO `db_admin_node` VALUES ('9', 'edit', '修改管理员信息', '1', '修改管理员信息', '12', '5', '3', '', '0');
INSERT INTO `db_admin_node` VALUES ('10', 'saveEdit', '保存修改的管理员信息', '1', '保存修改的管理员信息', '13', '5', '3', '', '0');
INSERT INTO `db_admin_node` VALUES ('11', 'delete', '删除管理员信息', '1', '删除管理员信息', '14', '5', '3', '', '0');
INSERT INTO `db_admin_node` VALUES ('12', 'AdminRole', '角色管理', '1', '角色的管理的增删改查，管理员权限的分配', '15', '86', '2', '', '0');
INSERT INTO `db_admin_node` VALUES ('13', 'index', '角色列表查看', '1', '角色列表查看', '15', '12', '3', '', '0');
INSERT INTO `db_admin_node` VALUES ('14', 'add', '添加角色', '1', '添加管理员角色', '16', '12', '3', '', '0');
INSERT INTO `db_admin_node` VALUES ('15', 'saveAdd', '保存添加的角色', '1', '新增角色保存的数据方法', '17', '12', '3', '', '0');
INSERT INTO `db_admin_node` VALUES ('16', 'saveEdit', '保存修改的角色', '1', '保存修改的角色', '18', '12', '3', '', '0');
INSERT INTO `db_admin_node` VALUES ('17', 'edit', '修改角色', '1', '修改角色', '19', '12', '3', '', '0');
INSERT INTO `db_admin_node` VALUES ('18', 'delete', '删除角色', '1', '删除角色', '20', '12', '3', '', '0');
INSERT INTO `db_admin_node` VALUES ('19', 'opRoleStatus', '便捷开启禁用角色', '1', '便捷开启禁用角色', '21', '12', '3', '', '0');
INSERT INTO `db_admin_node` VALUES ('20', 'changRole', '角色权限分配', '1', '角色权限分配', '22', '12', '3', '', '0');
INSERT INTO `db_admin_node` VALUES ('21', 'saveChangeRole', '保存角色权限信息', '1', '保存角色权限信息', '23', '12', '3', '', '0');
INSERT INTO `db_admin_node` VALUES ('22', 'Node', '后台系统菜单', '1', '节点管理之增删改查', '24', '44', '2', '', '0');
INSERT INTO `db_admin_node` VALUES ('23', 'index', '查看节点列表', '1', '查看节点列表', '25', '22', '3', '', '0');
INSERT INTO `db_admin_node` VALUES ('24', 'add', '添加节点', '1', '添加节点', '25', '22', '3', '', '0');
INSERT INTO `db_admin_node` VALUES ('25', 'saveAdd', '保存节点信息', '1', '保存添加的节点信息', '26', '22', '3', '', '0');
INSERT INTO `db_admin_node` VALUES ('26', 'edit', '修改节点', '1', '修改节点', '27', '22', '3', '', '0');
INSERT INTO `db_admin_node` VALUES ('27', 'opSort', '更改节点排序', '1', '更改节点排序', '28', '22', '3', '', '0');
INSERT INTO `db_admin_node` VALUES ('28', 'opNodeStatus', '便捷开启禁用节点', '1', '便捷开启禁用节点，ajax的形式', '29', '22', '3', '', '0');
INSERT INTO `db_admin_node` VALUES ('29', 'Site', '系统参数配置', '1', '设置网站的标题、关键字、描述、统计代码', '10', '44', '2', '', '0');
INSERT INTO `db_admin_node` VALUES ('30', 'SysData', '数据库备份', '1', '数据管理', '30', '122', '2', '', '0');
INSERT INTO `db_admin_node` VALUES ('31', 'index', '查看数据库表结构信息', '1', '查看数据库表结构信息', '31', '30', '3', '', '0');
INSERT INTO `db_admin_node` VALUES ('32', 'backup', '备份数据库', '1', '备份数据库', '32', '30', '3', '', '0');
INSERT INTO `db_admin_node` VALUES ('33', 'restore', '查看已备份SQL文件', '1', '', '33', '123', '3', '', '0');
INSERT INTO `db_admin_node` VALUES ('34', 'restoreData', '执行数据库还原操作', '1', '执行数据库还原操作', '34', '123', '3', '', '0');
INSERT INTO `db_admin_node` VALUES ('35', 'delSqlFiles', '删除已备份数据库文件', '1', '删除已备份数据库文件', '35', '30', '3', '', '0');
INSERT INTO `db_admin_node` VALUES ('36', 'zipSql', '打包SQL文件', '1', '打包SQL文件', '36', '30', '3', '', '0');
INSERT INTO `db_admin_node` VALUES ('37', 'zipList', '查看已打包SQL文件', '1', '查看已打包SQL文件', '36', '30', '3', '', '0');
INSERT INTO `db_admin_node` VALUES ('38', 'unzipSqlfile', '解压缩ZIP文件', '1', '解压缩ZIP文件', '38', '30', '3', '', '0');
INSERT INTO `db_admin_node` VALUES ('39', 'delZipFiles', '删除zip压缩文件', '1', '删除zip压缩文件', '39', '30', '3', '', '0');
INSERT INTO `db_admin_node` VALUES ('40', 'downFile', '下载备份的SQL,ZIP文件', '1', '下载备份的SQL,ZIP文件', '40', '30', '3', '', '0');
INSERT INTO `db_admin_node` VALUES ('41', 'repair', '数据库优化修复', '1', '数据库优化修复', '41', '30', '3', '', '0');
INSERT INTO `db_admin_node` VALUES ('42', 'Ad', '扩展管理', '1', '模块管理', '20', '0', '1', '', '0');
INSERT INTO `db_admin_node` VALUES ('43', 'Public', '清除缓存', '1', '', '45', '102', '2', 'cache', '0');
INSERT INTO `db_admin_node` VALUES ('44', 'Admin', '系统设置管理', '1', '系统栏目管理', '0', '1', '4', '', '0');
INSERT INTO `db_admin_node` VALUES ('45', 'index', '站点信息', '1', '', '0', '29', '3', '', '0');
INSERT INTO `db_admin_node` VALUES ('46', 'Lang', '语言列表管理', '1', '', '50', '44', '2', '', '0');
INSERT INTO `db_admin_node` VALUES ('47', 'Index', '查看多语言', '1', '', '0', '46', '3', '', '0');
INSERT INTO `db_admin_node` VALUES ('48', 'add', '添加语言', '1', '', '0', '46', '3', '', '0');
INSERT INTO `db_admin_node` VALUES ('49', 'edit', '修改语言', '1', '', '0', '46', '3', '', '0');
INSERT INTO `db_admin_node` VALUES ('50', 'delCategory', '删除语言', '1', '', '0', '46', '3', '', '0');
INSERT INTO `db_admin_node` VALUES ('51', 'setlang', '设置语言', '1', '', '0', '46', '3', '', '0');
INSERT INTO `db_admin_node` VALUES ('52', 'Area', '地区联动菜单', '1', '', '60', '60', '2', '', '0');
INSERT INTO `db_admin_node` VALUES ('53', 'index', '查看联动菜单列表', '1', '', '0', '52', '3', '', '0');
INSERT INTO `db_admin_node` VALUES ('54', 'edit', '修改联动菜单', '1', '', '0', '52', '3', '', '0');
INSERT INTO `db_admin_node` VALUES ('55', 'foreverdelete', ' 删除联动菜单', '1', '', '0', '52', '3', '', '0');
INSERT INTO `db_admin_node` VALUES ('56', 'add', '添加联动菜单', '1', '', '0', '52', '3', '', '0');
INSERT INTO `db_admin_node` VALUES ('57', 'Site', '清除系统缓存', '1', '', '100', '44', '2', 'cleancache', '0');
INSERT INTO `db_admin_node` VALUES ('58', 'cleancache', '清除缓存', '1', '', '0', '57', '3', '', '0');
INSERT INTO `db_admin_node` VALUES ('59', 'Product', '内容管理', '1', '', '30', '0', '1', '', '0');
INSERT INTO `db_admin_node` VALUES ('60', 'Ad', '模块栏目', '1', '', '0', '42', '4', '', '0');
INSERT INTO `db_admin_node` VALUES ('61', 'Ad', '常用广告管理', '1', '', '0', '60', '2', '', '0');
INSERT INTO `db_admin_node` VALUES ('62', 'index', '查看广告列表', '1', '', '0', '61', '3', '', '0');
INSERT INTO `db_admin_node` VALUES ('63', 'addAd', '添加广告', '1', '', '0', '61', '3', '', '0');
INSERT INTO `db_admin_node` VALUES ('64', 'edit', '修改广告', '1', '', '0', '61', '3', '', '0');
INSERT INTO `db_admin_node` VALUES ('65', 'del', '删除广告', '1', '', '0', '61', '3', '', '0');
INSERT INTO `db_admin_node` VALUES ('66', 'slideList', '幻灯片广告列表', '1', '', '0', '61', '3', '', '0');
INSERT INTO `db_admin_node` VALUES ('67', 'addSlide', '上传幻灯片', '1', '', '0', '61', '3', '', '0');
INSERT INTO `db_admin_node` VALUES ('68', 'editslide', '修改幻灯片', '1', '', '0', '61', '3', '', '0');
INSERT INTO `db_admin_node` VALUES ('69', 'delslide', '删除幻灯片', '1', '', '0', '61', '3', '', '0');
INSERT INTO `db_admin_node` VALUES ('70', 'Block', '碎片列表管理', '1', '', '20', '60', '2', '', '0');
INSERT INTO `db_admin_node` VALUES ('71', 'index', '查看碎片', '1', '', '0', '70', '3', '', '0');
INSERT INTO `db_admin_node` VALUES ('72', 'add', '添加碎片', '1', '', '0', '70', '3', '', '0');
INSERT INTO `db_admin_node` VALUES ('73', 'edit', '修改碎片', '1', '', '0', '70', '3', '', '0');
INSERT INTO `db_admin_node` VALUES ('74', 'delete', '删除碎片', '1', '', '0', '70', '3', '', '0');
INSERT INTO `db_admin_node` VALUES ('75', 'Link', '友情链接管理', '1', '', '30', '60', '2', '', '0');
INSERT INTO `db_admin_node` VALUES ('76', 'index', '查看友链', '1', '', '0', '75', '3', '', '0');
INSERT INTO `db_admin_node` VALUES ('77', 'add', '添加友链', '1', '', '0', '75', '3', '', '0');
INSERT INTO `db_admin_node` VALUES ('78', 'edit', '修改友链', '1', '', '0', '75', '3', '', '0');
INSERT INTO `db_admin_node` VALUES ('79', 'del', '删除友链', '1', '', '0', '75', '3', '', '0');
INSERT INTO `db_admin_node` VALUES ('80', 'Menu', '内容管理', '1', '', '0', '59', '4', '', '0');
INSERT INTO `db_admin_node` VALUES ('81', 'Menu', '栏目列表管理', '1', '', '0', '80', '2', '', '0');
INSERT INTO `db_admin_node` VALUES ('86', 'Admin', '权限模块', '1', '', '10', '1', '4', '', '0');
INSERT INTO `db_admin_node` VALUES ('87', 'Module', '模型列表管理', '1', '', '10000', '80', '2', '', '0');
INSERT INTO `db_admin_node` VALUES ('88', 'Index', '栏目列表', '1', '', '0', '81', '3', '', '0');
INSERT INTO `db_admin_node` VALUES ('89', 'add', '添加菜单', '1', '', '0', '81', '3', '', '0');
INSERT INTO `db_admin_node` VALUES ('90', 'edit', '修改菜单', '1', '', '0', '81', '3', '', '0');
INSERT INTO `db_admin_node` VALUES ('91', 'del', '删除菜单', '1', '', '0', '81', '3', '', '0');
INSERT INTO `db_admin_node` VALUES ('92', 'index', '查看模型', '1', '', '0', '87', '3', '', '0');
INSERT INTO `db_admin_node` VALUES ('93', 'add', '添加模型', '1', '', '0', '87', '3', '', '0');
INSERT INTO `db_admin_node` VALUES ('94', 'edit', '修改模型', '1', '', '0', '87', '3', '', '0');
INSERT INTO `db_admin_node` VALUES ('95', 'field', '查看表字段信息', '1', '', '0', '87', '3', '', '0');
INSERT INTO `db_admin_node` VALUES ('96', 'del', '删除模型', '1', '', '0', '87', '3', '', '0');
INSERT INTO `db_admin_node` VALUES ('97', 'Posid', '推荐列表管理', '1', '', '50', '60', '2', '', '0');
INSERT INTO `db_admin_node` VALUES ('113', 'Guestbook', '留言列表管理', '1', '', '60', '60', '2', '', '0');
INSERT INTO `db_admin_node` VALUES ('114', 'index', '查看留言', '1', '', '0', '113', '3', '', '0');
INSERT INTO `db_admin_node` VALUES ('115', 'delAll', '删除留言', '1', '', '0', '113', '3', '', '0');
INSERT INTO `db_admin_node` VALUES ('116', 'updateStatus', '审核留言', '1', '', '0', '113', '3', '', '0');
INSERT INTO `db_admin_node` VALUES ('117', 'index', '推荐位管理', '1', '', '0', '97', '3', '', '0');
INSERT INTO `db_admin_node` VALUES ('118', 'add', '添加推荐位', '1', '', '0', '97', '3', '', '0');
INSERT INTO `db_admin_node` VALUES ('119', 'edit', '修改推荐位', '1', '', '0', '97', '3', '', '0');
INSERT INTO `db_admin_node` VALUES ('120', 'del', '删除推荐位', '1', '', '0', '97', '3', '', '0');
INSERT INTO `db_admin_node` VALUES ('122', 'SysData', '数据库模块', '1', '', '0', '1', '4', '', '0');
INSERT INTO `db_admin_node` VALUES ('123', 'SysData', '数据库还原', '1', '', '40', '122', '2', 'restore', '0');
INSERT INTO `db_admin_node` VALUES ('150', 'addparam', '添加语言参数', '1', '', '0', '46', '3', '', '0');
INSERT INTO `db_admin_node` VALUES ('169', 'User', '用户管理', '1', '', '40', '0', '1', '', '0');
INSERT INTO `db_admin_node` VALUES ('170', 'User', '会员模块', '1', '', '0', '169', '4', '', '0');
INSERT INTO `db_admin_node` VALUES ('171', 'User', '会员列表管理', '1', '', '10', '170', '2', '', '0');
INSERT INTO `db_admin_node` VALUES ('172', 'Template', '消息模板管理', '1', '', '20', '170', '2', '', '0');
INSERT INTO `db_admin_node` VALUES ('173', 'Wechat', '微信管理', '1', '', '50', '0', '1', '', '0');
INSERT INTO `db_admin_node` VALUES ('174', 'Wechat', '账号管理', '1', '', '10', '173', '4', '', '0');
INSERT INTO `db_admin_node` VALUES ('175', 'Wechat', '公众号管理', '1', '', '10', '174', '2', '', '0');
INSERT INTO `db_admin_node` VALUES ('176', 'Wechat', '欢迎语', '1', '', '20', '174', '2', 'welcome', '0');
INSERT INTO `db_admin_node` VALUES ('177', 'Wechat', '微信宣传页', '1', '', '30', '174', '2', 'page', '0');
INSERT INTO `db_admin_node` VALUES ('178', 'Wechat', '自定义菜单', '1', '', '40', '174', '2', 'menu', '0');
INSERT INTO `db_admin_node` VALUES ('179', 'Wechat', '微信用户中心', '1', '', '50', '174', '2', 'user', '0');
INSERT INTO `db_admin_node` VALUES ('180', 'Wechat', '自定义回复', '1', '', '60', '174', '2', 'reply', '0');
INSERT INTO `db_admin_node` VALUES ('181', 'Card', '基础功能', '0', '', '20', '173', '4', '', '0');
INSERT INTO `db_admin_node` VALUES ('182', 'Wechat', '会员卡', '1', '', '0', '181', '2', 'card', '0');
INSERT INTO `db_admin_node` VALUES ('183', 'Site', '系统模板选择', '1', '', '60', '44', '2', 'templte', '0');
INSERT INTO `db_admin_node` VALUES ('184', 'updateStatus', '便携式修改语言状态', '1', '', '0', '46', '3', '', '0');
INSERT INTO `db_admin_node` VALUES ('185', 'templte', '设置系统模板', '1', '', '0', '183', '3', '', '0');
INSERT INTO `db_admin_node` VALUES ('186', 'field_add', '添加表字段', '1', '', '0', '87', '3', '', '0');
INSERT INTO `db_admin_node` VALUES ('187', 'field_edit', '修改表字段', '1', '', '0', '87', '3', '', '0');
INSERT INTO `db_admin_node` VALUES ('188', 'delete', '删除表字段', '1', '', '0', '87', '3', '', '0');
INSERT INTO `db_admin_node` VALUES ('189', 'listorder', '更新字段排序', '1', '', '0', '87', '3', '', '0');
INSERT INTO `db_admin_node` VALUES ('190', 'listorder', '更新字段排序', '1', '', '0', '87', '3', '', '0');
INSERT INTO `db_admin_node` VALUES ('191', 'index', '查看会员列表', '1', '', '0', '171', '3', '', '0');
INSERT INTO `db_admin_node` VALUES ('192', 'updateStaus', '便携式更改会员状态', '1', '', '0', '171', '3', '', '0');
INSERT INTO `db_admin_node` VALUES ('193', 'edit', '修改用户资料', '1', '', '0', '171', '3', '', '0');
INSERT INTO `db_admin_node` VALUES ('194', 'index', '消息的模板列表', '1', '', '0', '172', '3', '', '0');
INSERT INTO `db_admin_node` VALUES ('195', 'add', '添加消息模板', '1', '', '0', '172', '3', '', '0');
INSERT INTO `db_admin_node` VALUES ('196', 'del', '删除消息模板', '1', '', '0', '172', '3', '', '0');
INSERT INTO `db_admin_node` VALUES ('197', 'page', '查看微信宣传页', '1', '', '0', '177', '3', '', '0');
INSERT INTO `db_admin_node` VALUES ('198', 'page_add', '添加宣传页', '1', '', '0', '177', '3', '', '0');
INSERT INTO `db_admin_node` VALUES ('199', 'page_edit', '修改宣传页', '1', '', '0', '177', '3', '', '0');
INSERT INTO `db_admin_node` VALUES ('200', 'page_del', '删除宣传页', '1', '', '0', '177', '3', '', '0');
INSERT INTO `db_admin_node` VALUES ('201', 'welcome', '查看欢迎语', '1', '', '0', '176', '3', '', '0');
INSERT INTO `db_admin_node` VALUES ('202', 'welcome_add', '添加欢迎语', '1', '', '0', '176', '3', '', '0');
INSERT INTO `db_admin_node` VALUES ('203', 'welcome_edit', '修改欢迎语', '1', '', '0', '176', '3', '', '0');
INSERT INTO `db_admin_node` VALUES ('204', 'welcome_del', '删除欢迎语', '1', '', '0', '176', '3', '', '0');
INSERT INTO `db_admin_node` VALUES ('205', 'index', '公众号管理页面', '1', '', '0', '175', '3', '', '0');
INSERT INTO `db_admin_node` VALUES ('206', 'add', '添加微信公众号', '1', '', '0', '175', '3', '', '0');
INSERT INTO `db_admin_node` VALUES ('207', 'edit', '修改微信公众号', '1', '', '0', '175', '3', '', '0');
INSERT INTO `db_admin_node` VALUES ('208', 'del', '删除微信公众号', '1', '', '0', '175', '3', '', '0');
INSERT INTO `db_admin_node` VALUES ('209', 'setting', '接口配置', '1', '', '0', '175', '3', '', '0');
INSERT INTO `db_admin_node` VALUES ('210', 'menu', '微信自定义菜单', '1', '', '0', '178', '3', '', '0');
INSERT INTO `db_admin_node` VALUES ('211', 'menu_add', '添加菜单', '1', '', '0', '178', '3', '', '0');
INSERT INTO `db_admin_node` VALUES ('212', 'menu_edit', '修改菜单', '1', '', '0', '178', '3', '', '0');
INSERT INTO `db_admin_node` VALUES ('213', 'menu_del', '删除菜单', '1', '', '0', '178', '3', '', '0');
INSERT INTO `db_admin_node` VALUES ('214', 'reply', '微信自定义回复', '1', '', '0', '180', '3', '', '0');
INSERT INTO `db_admin_node` VALUES ('215', 'reply_add', '添加回复', '1', '', '0', '180', '3', '', '0');
INSERT INTO `db_admin_node` VALUES ('216', 'reply_edit', '修改回复', '1', '', '0', '180', '3', '', '0');
INSERT INTO `db_admin_node` VALUES ('217', 'reply_del', '删除回复', '1', '', '0', '180', '3', '', '0');
INSERT INTO `db_admin_node` VALUES ('218', 'updateStatus', '更改菜单状态排序', '1', '', '0', '81', '3', '', '0');
INSERT INTO `db_admin_node` VALUES ('219', 'LoginApi', '登录接口管理', '1', '', '70', '60', '2', '', '0');
INSERT INTO `db_admin_node` VALUES ('220', 'PayApi', '支付接口管理', '1', '', '80', '60', '2', '', '0');
INSERT INTO `db_admin_node` VALUES ('221', 'UserGroup', '会员组管理', '1', '', '15', '170', '2', '', '0');
INSERT INTO `db_admin_node` VALUES ('222', 'index', '查看登录列表', '1', '', '0', '219', '3', '', '0');
INSERT INTO `db_admin_node` VALUES ('223', 'add', '添加接口', '1', '', '0', '219', '3', '', '0');
INSERT INTO `db_admin_node` VALUES ('224', 'edit', '修改接口', '1', '', '0', '219', '3', '', '0');
INSERT INTO `db_admin_node` VALUES ('225', 'del', '删除接口', '1', '', '0', '219', '3', '', '0');
INSERT INTO `db_admin_node` VALUES ('226', 'index', '支付列表', '1', '', '0', '220', '3', '', '0');
INSERT INTO `db_admin_node` VALUES ('227', 'add', '添加支付接口', '1', '', '0', '220', '3', '', '0');
INSERT INTO `db_admin_node` VALUES ('228', 'edit', '修改支付接口', '1', '', '0', '220', '3', '', '0');
INSERT INTO `db_admin_node` VALUES ('229', 'del', '删除支付接口', '1', '', '0', '220', '3', '', '0');
INSERT INTO `db_admin_node` VALUES ('230', 'index', '会员组列表', '1', '', '0', '221', '3', '', '0');
INSERT INTO `db_admin_node` VALUES ('231', 'add', '添加会员组', '1', '', '0', '221', '3', '', '0');
INSERT INTO `db_admin_node` VALUES ('232', 'edit', '修改会员组', '1', '', '0', '221', '3', '', '0');
INSERT INTO `db_admin_node` VALUES ('233', 'del', '删除会员组', '1', '', '0', '221', '3', '', '0');
INSERT INTO `db_admin_node` VALUES ('234', 'order', '订单模块', '1', '', '20', '169', '4', '', '0');
INSERT INTO `db_admin_node` VALUES ('235', 'Order', '订单管理', '1', '', '0', '234', '2', '', '0');
INSERT INTO `db_admin_node` VALUES ('236', 'index', '查看订单列表', '1', '', '0', '235', '3', '', '0');
INSERT INTO `db_admin_node` VALUES ('237', 'detail', '查看订单详情', '1', '', '0', '235', '3', '', '0');
INSERT INTO `db_admin_node` VALUES ('238', 'pay', '设置订单为已付款', '1', '', '0', '235', '3', '', '0');
INSERT INTO `db_admin_node` VALUES ('239', 'delorder', '删除订单', '1', '', '0', '235', '3', '', '0');
INSERT INTO `db_admin_node` VALUES ('240', 'LoginApi', '邮件配置接口', '1', '', '90', '60', '2', 'email', '0');
INSERT INTO `db_admin_node` VALUES ('241', 'email', '邮件配置', '1', '', '0', '240', '3', '', '0');
INSERT INTO `db_admin_node` VALUES ('242', 'Kefu', '客服系统管理', '1', '', '100', '60', '2', '', '0');
INSERT INTO `db_admin_node` VALUES ('243', 'index', '查看客服系统', '1', '', '0', '242', '3', '', '0');
INSERT INTO `db_admin_node` VALUES ('244', 'add', '添加客服', '1', '', '0', '242', '3', '', '0');
INSERT INTO `db_admin_node` VALUES ('245', 'edit', '修改客服', '1', '', '0', '242', '3', '', '0');
INSERT INTO `db_admin_node` VALUES ('246', 'del', '删除客服', '1', '', '0', '242', '3', '', '0');
INSERT INTO `db_admin_node` VALUES ('247', 'updateStatus', '更改友链状态', '1', '', '0', '75', '3', '', '0');
INSERT INTO `db_admin_node` VALUES ('248', 'Sitemap', 'sitemap生成', '1', '', '70', '44', '2', '', '0');
INSERT INTO `db_admin_node` VALUES ('249', 'index', '生成sitemap地图', '1', '', '0', '248', '3', '', '0');
INSERT INTO `db_admin_node` VALUES ('250', 'addcommon', '营销链接', '1', '', '0', '75', '3', '', '0');
INSERT INTO `db_admin_node` VALUES ('251', 'upgrade', '系统升级管理', '1', '', '0', '2', '3', '', '0');
INSERT INTO `db_admin_node` VALUES ('252', 'Site', '图片水印管理', '1', '', '80', '44', '2', 'watermark', '0');
INSERT INTO `db_admin_node` VALUES ('253', 'Block', '搜索词语管理', '1', '', '110', '60', '2', 'seachkeyword', '0');
INSERT INTO `db_admin_node` VALUES ('254', 'seachkeyword', '关键词列表', '1', '', '0', '253', '3', '', '0');
INSERT INTO `db_admin_node` VALUES ('255', 'watermark', '水印设置', '1', '', '0', '252', '3', '', '0');
INSERT INTO `db_admin_node` VALUES ('256', 'addkeyword', '添加关键词', '1', '', '0', '253', '3', '', '0');
INSERT INTO `db_admin_node` VALUES ('257', 'editkeyword', '修改关键词', '1', '', '0', '253', '3', '', '0');
INSERT INTO `db_admin_node` VALUES ('258', 'delkeyword', '删除关键词', '1', '', '0', '253', '3', '', '0');
INSERT INTO `db_admin_role` VALUES ('1', '超级管理员', '0', '1', '系统内置超级管理员组，不受权限分配账号限制');
INSERT INTO `db_admin_role` VALUES ('2', '普通管理员', '1', '1', '普通管理员');
INSERT INTO `db_area` VALUES ('1', '0', '中国', '0', '0', '0');
INSERT INTO `db_area` VALUES ('2', '1', '北京', '1', '0', '1');
INSERT INTO `db_area` VALUES ('3', '1', '安徽', '1', '0', '0');
INSERT INTO `db_area` VALUES ('4', '1', '福建', '1', '0', '0');
INSERT INTO `db_area` VALUES ('5', '1', '甘肃', '1', '0', '0');
INSERT INTO `db_area` VALUES ('6', '1', '广东', '1', '0', '0');
INSERT INTO `db_area` VALUES ('7', '1', '广西', '1', '0', '0');
INSERT INTO `db_area` VALUES ('8', '1', '贵州', '1', '0', '0');
INSERT INTO `db_area` VALUES ('9', '1', '海南', '1', '0', '0');
INSERT INTO `db_area` VALUES ('10', '1', '河北', '1', '0', '0');
INSERT INTO `db_area` VALUES ('11', '1', '河南', '1', '0', '0');
INSERT INTO `db_area` VALUES ('12', '1', '黑龙江', '1', '0', '0');
INSERT INTO `db_area` VALUES ('13', '1', '湖北', '1', '0', '0');
INSERT INTO `db_area` VALUES ('14', '1', '湖南', '1', '0', '0');
INSERT INTO `db_area` VALUES ('15', '1', '吉林', '1', '0', '0');
INSERT INTO `db_area` VALUES ('16', '1', '江苏', '1', '0', '0');
INSERT INTO `db_area` VALUES ('17', '1', '江西', '1', '0', '0');
INSERT INTO `db_area` VALUES ('18', '1', '辽宁', '1', '0', '0');
INSERT INTO `db_area` VALUES ('19', '1', '内蒙古', '1', '0', '0');
INSERT INTO `db_area` VALUES ('20', '1', '宁夏', '1', '0', '0');
INSERT INTO `db_area` VALUES ('21', '1', '青海', '1', '0', '0');
INSERT INTO `db_area` VALUES ('22', '1', '山东', '1', '0', '0');
INSERT INTO `db_area` VALUES ('23', '1', '山西', '1', '0', '0');
INSERT INTO `db_area` VALUES ('24', '1', '陕西', '1', '0', '0');
INSERT INTO `db_area` VALUES ('25', '1', '上海', '1', '0', '1');
INSERT INTO `db_area` VALUES ('26', '1', '四川', '1', '0', '0');
INSERT INTO `db_area` VALUES ('27', '1', '天津', '1', '0', '1');
INSERT INTO `db_area` VALUES ('28', '1', '西藏', '1', '0', '0');
INSERT INTO `db_area` VALUES ('29', '1', '新疆', '1', '0', '0');
INSERT INTO `db_area` VALUES ('30', '1', '云南', '1', '0', '0');
INSERT INTO `db_area` VALUES ('31', '1', '浙江', '1', '0', '0');
INSERT INTO `db_area` VALUES ('32', '1', '重庆', '1', '0', '1');
INSERT INTO `db_area` VALUES ('33', '1', '香港', '1', '0', '1');
INSERT INTO `db_area` VALUES ('34', '1', '澳门', '1', '0', '0');
INSERT INTO `db_area` VALUES ('35', '1', '台湾', '1', '0', '1');
INSERT INTO `db_area` VALUES ('36', '3', '安庆', '2', '0', '0');
INSERT INTO `db_area` VALUES ('37', '3', '蚌埠', '2', '0', '0');
INSERT INTO `db_area` VALUES ('38', '3', '巢湖', '2', '0', '0');
INSERT INTO `db_area` VALUES ('39', '3', '池州', '2', '0', '0');
INSERT INTO `db_area` VALUES ('40', '3', '滁州', '2', '0', '0');
INSERT INTO `db_area` VALUES ('41', '3', '阜阳', '2', '0', '0');
INSERT INTO `db_area` VALUES ('42', '3', '淮北', '2', '0', '0');
INSERT INTO `db_area` VALUES ('43', '3', '淮南', '2', '0', '0');
INSERT INTO `db_area` VALUES ('44', '3', '黄山', '2', '0', '0');
INSERT INTO `db_area` VALUES ('45', '3', '六安', '2', '0', '0');
INSERT INTO `db_area` VALUES ('46', '3', '马鞍山', '2', '0', '0');
INSERT INTO `db_area` VALUES ('47', '3', '宿州', '2', '0', '0');
INSERT INTO `db_area` VALUES ('48', '3', '铜陵', '2', '0', '0');
INSERT INTO `db_area` VALUES ('49', '3', '芜湖', '2', '0', '0');
INSERT INTO `db_area` VALUES ('50', '3', '宣城', '2', '0', '0');
INSERT INTO `db_area` VALUES ('51', '3', '亳州', '2', '0', '0');
INSERT INTO `db_area` VALUES ('53', '4', '福州', '2', '0', '0');
INSERT INTO `db_area` VALUES ('54', '4', '龙岩', '2', '0', '0');
INSERT INTO `db_area` VALUES ('55', '4', '南平', '2', '0', '0');
INSERT INTO `db_area` VALUES ('56', '4', '宁德', '2', '0', '0');
INSERT INTO `db_area` VALUES ('57', '4', '莆田', '2', '0', '0');
INSERT INTO `db_area` VALUES ('58', '4', '泉州', '2', '0', '0');
INSERT INTO `db_area` VALUES ('59', '4', '三明', '2', '0', '0');
INSERT INTO `db_area` VALUES ('60', '4', '厦门', '2', '0', '0');
INSERT INTO `db_area` VALUES ('61', '4', '漳州', '2', '0', '0');
INSERT INTO `db_area` VALUES ('62', '5', '兰州', '2', '0', '0');
INSERT INTO `db_area` VALUES ('63', '5', '白银', '2', '0', '0');
INSERT INTO `db_area` VALUES ('64', '5', '定西', '2', '0', '0');
INSERT INTO `db_area` VALUES ('65', '5', '甘南', '2', '0', '0');
INSERT INTO `db_area` VALUES ('66', '5', '嘉峪关', '2', '0', '0');
INSERT INTO `db_area` VALUES ('67', '5', '金昌', '2', '0', '0');
INSERT INTO `db_area` VALUES ('68', '5', '酒泉', '2', '0', '0');
INSERT INTO `db_area` VALUES ('69', '5', '临夏', '2', '0', '0');
INSERT INTO `db_area` VALUES ('70', '5', '陇南', '2', '0', '0');
INSERT INTO `db_area` VALUES ('71', '5', '平凉', '2', '0', '0');
INSERT INTO `db_area` VALUES ('72', '5', '庆阳', '2', '0', '0');
INSERT INTO `db_area` VALUES ('73', '5', '天水', '2', '0', '0');
INSERT INTO `db_area` VALUES ('74', '5', '武威', '2', '0', '0');
INSERT INTO `db_area` VALUES ('75', '5', '张掖', '2', '0', '0');
INSERT INTO `db_area` VALUES ('76', '6', '广州', '2', '0', '1');
INSERT INTO `db_area` VALUES ('77', '6', '深圳', '2', '0', '0');
INSERT INTO `db_area` VALUES ('78', '6', '潮州', '2', '0', '0');
INSERT INTO `db_area` VALUES ('79', '6', '东莞', '2', '0', '0');
INSERT INTO `db_area` VALUES ('80', '6', '佛山', '2', '0', '0');
INSERT INTO `db_area` VALUES ('81', '6', '河源', '2', '0', '0');
INSERT INTO `db_area` VALUES ('82', '6', '惠州', '2', '0', '0');
INSERT INTO `db_area` VALUES ('83', '6', '江门', '2', '0', '0');
INSERT INTO `db_area` VALUES ('84', '6', '揭阳', '2', '0', '0');
INSERT INTO `db_area` VALUES ('85', '6', '茂名', '2', '0', '0');
INSERT INTO `db_area` VALUES ('86', '6', '梅州', '2', '0', '0');
INSERT INTO `db_area` VALUES ('87', '6', '清远', '2', '0', '0');
INSERT INTO `db_area` VALUES ('88', '6', '汕头', '2', '0', '0');
INSERT INTO `db_area` VALUES ('89', '6', '汕尾', '2', '0', '0');
INSERT INTO `db_area` VALUES ('90', '6', '韶关', '2', '0', '0');
INSERT INTO `db_area` VALUES ('91', '6', '阳江', '2', '0', '0');
INSERT INTO `db_area` VALUES ('92', '6', '云浮', '2', '0', '0');
INSERT INTO `db_area` VALUES ('93', '6', '湛江', '2', '0', '0');
INSERT INTO `db_area` VALUES ('94', '6', '肇庆', '2', '0', '0');
INSERT INTO `db_area` VALUES ('95', '6', '中山', '2', '0', '0');
INSERT INTO `db_area` VALUES ('96', '6', '珠海', '2', '0', '0');
INSERT INTO `db_area` VALUES ('97', '7', '南宁', '2', '0', '0');
INSERT INTO `db_area` VALUES ('98', '7', '桂林', '2', '0', '0');
INSERT INTO `db_area` VALUES ('99', '7', '百色', '2', '0', '0');
INSERT INTO `db_area` VALUES ('100', '7', '北海', '2', '0', '0');
INSERT INTO `db_area` VALUES ('101', '7', '崇左', '2', '0', '0');
INSERT INTO `db_area` VALUES ('102', '7', '防城港', '2', '0', '0');
INSERT INTO `db_area` VALUES ('103', '7', '贵港', '2', '0', '0');
INSERT INTO `db_area` VALUES ('104', '7', '河池', '2', '0', '0');
INSERT INTO `db_area` VALUES ('105', '7', '贺州', '2', '0', '0');
INSERT INTO `db_area` VALUES ('106', '7', '来宾', '2', '0', '0');
INSERT INTO `db_area` VALUES ('107', '7', '柳州', '2', '0', '0');
INSERT INTO `db_area` VALUES ('108', '7', '钦州', '2', '0', '0');
INSERT INTO `db_area` VALUES ('109', '7', '梧州', '2', '0', '0');
INSERT INTO `db_area` VALUES ('110', '7', '玉林', '2', '0', '0');
INSERT INTO `db_area` VALUES ('111', '8', '贵阳', '2', '0', '0');
INSERT INTO `db_area` VALUES ('112', '8', '安顺', '2', '0', '0');
INSERT INTO `db_area` VALUES ('113', '8', '毕节', '2', '0', '0');
INSERT INTO `db_area` VALUES ('114', '8', '六盘水', '2', '0', '0');
INSERT INTO `db_area` VALUES ('115', '8', '黔东南', '2', '0', '0');
INSERT INTO `db_area` VALUES ('116', '8', '黔南', '2', '0', '0');
INSERT INTO `db_area` VALUES ('117', '8', '黔西南', '2', '0', '0');
INSERT INTO `db_area` VALUES ('118', '8', '铜仁', '2', '0', '0');
INSERT INTO `db_area` VALUES ('119', '8', '遵义', '2', '0', '0');
INSERT INTO `db_area` VALUES ('120', '9', '海口', '2', '0', '0');
INSERT INTO `db_area` VALUES ('121', '9', '三亚', '2', '0', '0');
INSERT INTO `db_area` VALUES ('122', '9', '白沙', '2', '0', '0');
INSERT INTO `db_area` VALUES ('123', '9', '保亭', '2', '0', '0');
INSERT INTO `db_area` VALUES ('124', '9', '昌江', '2', '0', '0');
INSERT INTO `db_area` VALUES ('125', '9', '澄迈县', '2', '0', '0');
INSERT INTO `db_area` VALUES ('126', '9', '定安县', '2', '0', '0');
INSERT INTO `db_area` VALUES ('127', '9', '东方', '2', '0', '0');
INSERT INTO `db_area` VALUES ('128', '9', '乐东', '2', '0', '0');
INSERT INTO `db_area` VALUES ('129', '9', '临高县', '2', '0', '0');
INSERT INTO `db_area` VALUES ('130', '9', '陵水', '2', '0', '0');
INSERT INTO `db_area` VALUES ('131', '9', '琼海', '2', '0', '0');
INSERT INTO `db_area` VALUES ('132', '9', '琼中', '2', '0', '0');
INSERT INTO `db_area` VALUES ('133', '9', '屯昌县', '2', '0', '0');
INSERT INTO `db_area` VALUES ('134', '9', '万宁', '2', '0', '0');
INSERT INTO `db_area` VALUES ('135', '9', '文昌', '2', '0', '0');
INSERT INTO `db_area` VALUES ('136', '9', '五指山', '2', '0', '0');
INSERT INTO `db_area` VALUES ('137', '9', '儋州', '2', '0', '0');
INSERT INTO `db_area` VALUES ('138', '10', '石家庄', '2', '0', '0');
INSERT INTO `db_area` VALUES ('139', '10', '保定', '2', '0', '0');
INSERT INTO `db_area` VALUES ('140', '10', '沧州', '2', '0', '0');
INSERT INTO `db_area` VALUES ('141', '10', '承德', '2', '0', '0');
INSERT INTO `db_area` VALUES ('142', '10', '邯郸', '2', '0', '0');
INSERT INTO `db_area` VALUES ('143', '10', '衡水', '2', '0', '0');
INSERT INTO `db_area` VALUES ('144', '10', '廊坊', '2', '0', '0');
INSERT INTO `db_area` VALUES ('145', '10', '秦皇岛', '2', '0', '0');
INSERT INTO `db_area` VALUES ('146', '10', '唐山', '2', '0', '0');
INSERT INTO `db_area` VALUES ('147', '10', '邢台', '2', '0', '0');
INSERT INTO `db_area` VALUES ('148', '10', '张家口', '2', '0', '0');
INSERT INTO `db_area` VALUES ('149', '11', '郑州', '2', '0', '0');
INSERT INTO `db_area` VALUES ('150', '11', '洛阳', '2', '0', '0');
INSERT INTO `db_area` VALUES ('151', '11', '开封', '2', '0', '0');
INSERT INTO `db_area` VALUES ('152', '11', '安阳', '2', '0', '0');
INSERT INTO `db_area` VALUES ('153', '11', '鹤壁', '2', '0', '0');
INSERT INTO `db_area` VALUES ('154', '11', '济源', '2', '0', '0');
INSERT INTO `db_area` VALUES ('155', '11', '焦作', '2', '0', '0');
INSERT INTO `db_area` VALUES ('156', '11', '南阳', '2', '0', '0');
INSERT INTO `db_area` VALUES ('157', '11', '平顶山', '2', '0', '0');
INSERT INTO `db_area` VALUES ('158', '11', '三门峡', '2', '0', '0');
INSERT INTO `db_area` VALUES ('159', '11', '商丘', '2', '0', '0');
INSERT INTO `db_area` VALUES ('160', '11', '新乡', '2', '0', '0');
INSERT INTO `db_area` VALUES ('161', '11', '信阳', '2', '0', '0');
INSERT INTO `db_area` VALUES ('162', '11', '许昌', '2', '0', '0');
INSERT INTO `db_area` VALUES ('163', '11', '周口', '2', '0', '0');
INSERT INTO `db_area` VALUES ('164', '11', '驻马店', '2', '0', '0');
INSERT INTO `db_area` VALUES ('165', '11', '漯河', '2', '0', '0');
INSERT INTO `db_area` VALUES ('166', '11', '濮阳', '2', '0', '0');
INSERT INTO `db_area` VALUES ('167', '12', '哈尔滨', '2', '0', '0');
INSERT INTO `db_area` VALUES ('168', '12', '大庆', '2', '0', '0');
INSERT INTO `db_area` VALUES ('169', '12', '大兴安岭', '2', '0', '0');
INSERT INTO `db_area` VALUES ('170', '12', '鹤岗', '2', '0', '0');
INSERT INTO `db_area` VALUES ('171', '12', '黑河', '2', '0', '0');
INSERT INTO `db_area` VALUES ('172', '12', '鸡西', '2', '0', '0');
INSERT INTO `db_area` VALUES ('173', '12', '佳木斯', '2', '0', '0');
INSERT INTO `db_area` VALUES ('174', '12', '牡丹江', '2', '0', '0');
INSERT INTO `db_area` VALUES ('175', '12', '七台河', '2', '0', '0');
INSERT INTO `db_area` VALUES ('176', '12', '齐齐哈尔', '2', '0', '0');
INSERT INTO `db_area` VALUES ('177', '12', '双鸭山', '2', '0', '0');
INSERT INTO `db_area` VALUES ('178', '12', '绥化', '2', '0', '0');
INSERT INTO `db_area` VALUES ('179', '12', '伊春', '2', '0', '0');
INSERT INTO `db_area` VALUES ('180', '13', '武汉', '2', '0', '0');
INSERT INTO `db_area` VALUES ('181', '13', '仙桃', '2', '0', '0');
INSERT INTO `db_area` VALUES ('182', '13', '鄂州', '2', '0', '0');
INSERT INTO `db_area` VALUES ('183', '13', '黄冈', '2', '0', '0');
INSERT INTO `db_area` VALUES ('184', '13', '黄石', '2', '0', '0');
INSERT INTO `db_area` VALUES ('185', '13', '荆门', '2', '0', '0');
INSERT INTO `db_area` VALUES ('186', '13', '荆州', '2', '0', '0');
INSERT INTO `db_area` VALUES ('187', '13', '潜江', '2', '0', '0');
INSERT INTO `db_area` VALUES ('188', '13', '神农架林区', '2', '0', '0');
INSERT INTO `db_area` VALUES ('189', '13', '十堰', '2', '0', '0');
INSERT INTO `db_area` VALUES ('190', '13', '随州', '2', '0', '0');
INSERT INTO `db_area` VALUES ('191', '13', '天门', '2', '0', '0');
INSERT INTO `db_area` VALUES ('192', '13', '咸宁', '2', '0', '0');
INSERT INTO `db_area` VALUES ('193', '13', '襄樊', '2', '0', '0');
INSERT INTO `db_area` VALUES ('194', '13', '孝感', '2', '0', '0');
INSERT INTO `db_area` VALUES ('195', '13', '宜昌', '2', '0', '0');
INSERT INTO `db_area` VALUES ('196', '13', '恩施', '2', '0', '0');
INSERT INTO `db_area` VALUES ('197', '14', '长沙', '2', '0', '0');
INSERT INTO `db_area` VALUES ('198', '14', '张家界', '2', '0', '0');
INSERT INTO `db_area` VALUES ('199', '14', '常德', '2', '0', '0');
INSERT INTO `db_area` VALUES ('200', '14', '郴州', '2', '0', '0');
INSERT INTO `db_area` VALUES ('201', '14', '衡阳', '2', '0', '0');
INSERT INTO `db_area` VALUES ('202', '14', '怀化', '2', '0', '0');
INSERT INTO `db_area` VALUES ('203', '14', '娄底', '2', '0', '0');
INSERT INTO `db_area` VALUES ('204', '14', '邵阳', '2', '0', '0');
INSERT INTO `db_area` VALUES ('205', '14', '湘潭', '2', '0', '0');
INSERT INTO `db_area` VALUES ('206', '14', '湘西', '2', '0', '0');
INSERT INTO `db_area` VALUES ('207', '14', '益阳', '2', '0', '0');
INSERT INTO `db_area` VALUES ('208', '14', '永州', '2', '0', '0');
INSERT INTO `db_area` VALUES ('209', '14', '岳阳', '2', '0', '0');
INSERT INTO `db_area` VALUES ('210', '14', '株洲', '2', '0', '0');
INSERT INTO `db_area` VALUES ('211', '15', '长春', '2', '0', '0');
INSERT INTO `db_area` VALUES ('212', '15', '吉林', '2', '0', '0');
INSERT INTO `db_area` VALUES ('213', '15', '白城', '2', '0', '0');
INSERT INTO `db_area` VALUES ('214', '15', '白山', '2', '0', '0');
INSERT INTO `db_area` VALUES ('215', '15', '辽源', '2', '0', '0');
INSERT INTO `db_area` VALUES ('216', '15', '四平', '2', '0', '0');
INSERT INTO `db_area` VALUES ('217', '15', '松原', '2', '0', '0');
INSERT INTO `db_area` VALUES ('218', '15', '通化', '2', '0', '0');
INSERT INTO `db_area` VALUES ('219', '15', '延边', '2', '0', '0');
INSERT INTO `db_area` VALUES ('220', '16', '南京', '2', '0', '0');
INSERT INTO `db_area` VALUES ('221', '16', '苏州', '2', '0', '0');
INSERT INTO `db_area` VALUES ('222', '16', '无锡', '2', '0', '0');
INSERT INTO `db_area` VALUES ('223', '16', '常州', '2', '0', '0');
INSERT INTO `db_area` VALUES ('224', '16', '淮安', '2', '0', '0');
INSERT INTO `db_area` VALUES ('225', '16', '连云港', '2', '0', '0');
INSERT INTO `db_area` VALUES ('226', '16', '南通', '2', '0', '0');
INSERT INTO `db_area` VALUES ('227', '16', '宿迁', '2', '0', '0');
INSERT INTO `db_area` VALUES ('228', '16', '泰州', '2', '0', '0');
INSERT INTO `db_area` VALUES ('229', '16', '徐州', '2', '0', '0');
INSERT INTO `db_area` VALUES ('230', '16', '盐城', '2', '0', '0');
INSERT INTO `db_area` VALUES ('231', '16', '扬州', '2', '0', '0');
INSERT INTO `db_area` VALUES ('232', '16', '镇江', '2', '0', '0');
INSERT INTO `db_area` VALUES ('233', '17', '南昌', '2', '0', '0');
INSERT INTO `db_area` VALUES ('234', '17', '抚州', '2', '0', '0');
INSERT INTO `db_area` VALUES ('235', '17', '赣州', '2', '0', '0');
INSERT INTO `db_area` VALUES ('236', '17', '吉安', '2', '0', '0');
INSERT INTO `db_area` VALUES ('237', '17', '景德镇', '2', '0', '0');
INSERT INTO `db_area` VALUES ('238', '17', '九江', '2', '0', '0');
INSERT INTO `db_area` VALUES ('239', '17', '萍乡', '2', '0', '0');
INSERT INTO `db_area` VALUES ('240', '17', '上饶', '2', '0', '0');
INSERT INTO `db_area` VALUES ('241', '17', '新余', '2', '0', '0');
INSERT INTO `db_area` VALUES ('242', '17', '宜春', '2', '0', '0');
INSERT INTO `db_area` VALUES ('243', '17', '鹰潭', '2', '0', '0');
INSERT INTO `db_area` VALUES ('244', '18', '沈阳', '2', '0', '0');
INSERT INTO `db_area` VALUES ('245', '18', '大连', '2', '0', '0');
INSERT INTO `db_area` VALUES ('246', '18', '鞍山', '2', '0', '0');
INSERT INTO `db_area` VALUES ('247', '18', '本溪', '2', '0', '0');
INSERT INTO `db_area` VALUES ('248', '18', '朝阳', '2', '0', '0');
INSERT INTO `db_area` VALUES ('249', '18', '丹东', '2', '0', '0');
INSERT INTO `db_area` VALUES ('250', '18', '抚顺', '2', '0', '0');
INSERT INTO `db_area` VALUES ('251', '18', '阜新', '2', '0', '0');
INSERT INTO `db_area` VALUES ('252', '18', '葫芦岛', '2', '0', '0');
INSERT INTO `db_area` VALUES ('253', '18', '锦州', '2', '0', '0');
INSERT INTO `db_area` VALUES ('254', '18', '辽阳', '2', '0', '0');
INSERT INTO `db_area` VALUES ('255', '18', '盘锦', '2', '0', '0');
INSERT INTO `db_area` VALUES ('256', '18', '铁岭', '2', '0', '0');
INSERT INTO `db_area` VALUES ('257', '18', '营口', '2', '0', '0');
INSERT INTO `db_area` VALUES ('258', '19', '呼和浩特', '2', '0', '0');
INSERT INTO `db_area` VALUES ('259', '19', '阿拉善盟', '2', '0', '0');
INSERT INTO `db_area` VALUES ('260', '19', '巴彦淖尔盟', '2', '0', '0');
INSERT INTO `db_area` VALUES ('261', '19', '包头', '2', '0', '0');
INSERT INTO `db_area` VALUES ('262', '19', '赤峰', '2', '0', '0');
INSERT INTO `db_area` VALUES ('263', '19', '鄂尔多斯', '2', '0', '0');
INSERT INTO `db_area` VALUES ('264', '19', '呼伦贝尔', '2', '0', '0');
INSERT INTO `db_area` VALUES ('265', '19', '通辽', '2', '0', '0');
INSERT INTO `db_area` VALUES ('266', '19', '乌海', '2', '0', '0');
INSERT INTO `db_area` VALUES ('267', '19', '乌兰察布市', '2', '0', '0');
INSERT INTO `db_area` VALUES ('268', '19', '锡林郭勒盟', '2', '0', '0');
INSERT INTO `db_area` VALUES ('269', '19', '兴安盟', '2', '0', '0');
INSERT INTO `db_area` VALUES ('270', '20', '银川', '2', '0', '0');
INSERT INTO `db_area` VALUES ('271', '20', '固原', '2', '0', '0');
INSERT INTO `db_area` VALUES ('272', '20', '石嘴山', '2', '0', '0');
INSERT INTO `db_area` VALUES ('273', '20', '吴忠', '2', '0', '0');
INSERT INTO `db_area` VALUES ('274', '20', '中卫', '2', '0', '0');
INSERT INTO `db_area` VALUES ('275', '21', '西宁', '2', '0', '0');
INSERT INTO `db_area` VALUES ('276', '21', '果洛', '2', '0', '0');
INSERT INTO `db_area` VALUES ('277', '21', '海北', '2', '0', '0');
INSERT INTO `db_area` VALUES ('278', '21', '海东', '2', '0', '0');
INSERT INTO `db_area` VALUES ('279', '21', '海南', '2', '0', '0');
INSERT INTO `db_area` VALUES ('280', '21', '海西', '2', '0', '0');
INSERT INTO `db_area` VALUES ('281', '21', '黄南', '2', '0', '0');
INSERT INTO `db_area` VALUES ('282', '21', '玉树', '2', '0', '0');
INSERT INTO `db_area` VALUES ('283', '22', '济南', '2', '0', '0');
INSERT INTO `db_area` VALUES ('284', '22', '青岛', '2', '0', '0');
INSERT INTO `db_area` VALUES ('285', '22', '滨州', '2', '0', '0');
INSERT INTO `db_area` VALUES ('286', '22', '德州', '2', '0', '0');
INSERT INTO `db_area` VALUES ('287', '22', '东营', '2', '0', '0');
INSERT INTO `db_area` VALUES ('288', '22', '菏泽', '2', '0', '0');
INSERT INTO `db_area` VALUES ('289', '22', '济宁', '2', '0', '0');
INSERT INTO `db_area` VALUES ('290', '22', '莱芜', '2', '0', '0');
INSERT INTO `db_area` VALUES ('291', '22', '聊城', '2', '0', '0');
INSERT INTO `db_area` VALUES ('292', '22', '临沂', '2', '0', '0');
INSERT INTO `db_area` VALUES ('293', '22', '日照', '2', '0', '0');
INSERT INTO `db_area` VALUES ('294', '22', '泰安', '2', '0', '0');
INSERT INTO `db_area` VALUES ('295', '22', '威海', '2', '0', '0');
INSERT INTO `db_area` VALUES ('296', '22', '潍坊', '2', '0', '0');
INSERT INTO `db_area` VALUES ('297', '22', '烟台', '2', '0', '0');
INSERT INTO `db_area` VALUES ('298', '22', '枣庄', '2', '0', '0');
INSERT INTO `db_area` VALUES ('299', '22', '淄博', '2', '0', '0');
INSERT INTO `db_area` VALUES ('300', '23', '太原', '2', '0', '0');
INSERT INTO `db_area` VALUES ('301', '23', '长治', '2', '0', '0');
INSERT INTO `db_area` VALUES ('302', '23', '大同', '2', '0', '0');
INSERT INTO `db_area` VALUES ('303', '23', '晋城', '2', '0', '0');
INSERT INTO `db_area` VALUES ('304', '23', '晋中', '2', '0', '0');
INSERT INTO `db_area` VALUES ('305', '23', '临汾', '2', '0', '0');
INSERT INTO `db_area` VALUES ('306', '23', '吕梁', '2', '2', '0');
INSERT INTO `db_area` VALUES ('307', '23', '朔州', '2', '0', '0');
INSERT INTO `db_area` VALUES ('308', '23', '忻州', '2', '0', '0');
INSERT INTO `db_area` VALUES ('309', '23', '阳泉', '2', '0', '0');
INSERT INTO `db_area` VALUES ('310', '23', '运城', '2', '0', '0');
INSERT INTO `db_area` VALUES ('311', '24', '西安', '2', '0', '0');
INSERT INTO `db_area` VALUES ('312', '24', '安康', '2', '0', '0');
INSERT INTO `db_area` VALUES ('313', '24', '宝鸡', '2', '0', '0');
INSERT INTO `db_area` VALUES ('314', '24', '汉中', '2', '0', '0');
INSERT INTO `db_area` VALUES ('315', '24', '商洛', '2', '0', '0');
INSERT INTO `db_area` VALUES ('316', '24', '铜川', '2', '0', '0');
INSERT INTO `db_area` VALUES ('317', '24', '渭南', '2', '0', '0');
INSERT INTO `db_area` VALUES ('318', '24', '咸阳', '2', '0', '0');
INSERT INTO `db_area` VALUES ('319', '24', '延安', '2', '0', '0');
INSERT INTO `db_area` VALUES ('320', '24', '榆林', '2', '0', '0');
INSERT INTO `db_area` VALUES ('322', '26', '成都', '2', '0', '0');
INSERT INTO `db_area` VALUES ('323', '26', '绵阳', '2', '0', '0');
INSERT INTO `db_area` VALUES ('324', '26', '阿坝', '2', '0', '0');
INSERT INTO `db_area` VALUES ('325', '26', '巴中', '2', '0', '0');
INSERT INTO `db_area` VALUES ('326', '26', '达州', '2', '0', '0');
INSERT INTO `db_area` VALUES ('327', '26', '德阳', '2', '0', '0');
INSERT INTO `db_area` VALUES ('328', '26', '甘孜', '2', '0', '0');
INSERT INTO `db_area` VALUES ('329', '26', '广安', '2', '0', '0');
INSERT INTO `db_area` VALUES ('330', '26', '广元', '2', '0', '0');
INSERT INTO `db_area` VALUES ('331', '26', '乐山', '2', '0', '0');
INSERT INTO `db_area` VALUES ('332', '26', '凉山', '2', '0', '0');
INSERT INTO `db_area` VALUES ('333', '26', '眉山', '2', '0', '0');
INSERT INTO `db_area` VALUES ('334', '26', '南充', '2', '0', '0');
INSERT INTO `db_area` VALUES ('335', '26', '内江', '2', '0', '0');
INSERT INTO `db_area` VALUES ('336', '26', '攀枝花', '2', '0', '0');
INSERT INTO `db_area` VALUES ('337', '26', '遂宁', '2', '0', '0');
INSERT INTO `db_area` VALUES ('338', '26', '雅安', '2', '0', '0');
INSERT INTO `db_area` VALUES ('339', '26', '宜宾', '2', '0', '0');
INSERT INTO `db_area` VALUES ('340', '26', '资阳', '2', '0', '0');
INSERT INTO `db_area` VALUES ('341', '26', '自贡', '2', '0', '0');
INSERT INTO `db_area` VALUES ('342', '26', '泸州', '2', '0', '0');
INSERT INTO `db_area` VALUES ('344', '28', '拉萨', '2', '0', '0');
INSERT INTO `db_area` VALUES ('345', '28', '阿里', '2', '0', '0');
INSERT INTO `db_area` VALUES ('346', '28', '昌都', '2', '0', '0');
INSERT INTO `db_area` VALUES ('347', '28', '林芝', '2', '0', '0');
INSERT INTO `db_area` VALUES ('348', '28', '那曲', '2', '0', '0');
INSERT INTO `db_area` VALUES ('349', '28', '日喀则', '2', '0', '0');
INSERT INTO `db_area` VALUES ('350', '28', '山南', '2', '0', '0');
INSERT INTO `db_area` VALUES ('351', '29', '乌鲁木齐', '2', '0', '0');
INSERT INTO `db_area` VALUES ('352', '29', '阿克苏', '2', '0', '0');
INSERT INTO `db_area` VALUES ('353', '29', '阿拉尔', '2', '0', '0');
INSERT INTO `db_area` VALUES ('354', '29', '巴音郭楞', '2', '0', '0');
INSERT INTO `db_area` VALUES ('355', '29', '博尔塔拉', '2', '0', '0');
INSERT INTO `db_area` VALUES ('356', '29', '昌吉', '2', '0', '0');
INSERT INTO `db_area` VALUES ('357', '29', '哈密', '2', '0', '0');
INSERT INTO `db_area` VALUES ('358', '29', '和田', '2', '0', '0');
INSERT INTO `db_area` VALUES ('359', '29', '喀什', '2', '0', '0');
INSERT INTO `db_area` VALUES ('360', '29', '克拉玛依', '2', '0', '0');
INSERT INTO `db_area` VALUES ('361', '29', '克孜勒苏', '2', '0', '0');
INSERT INTO `db_area` VALUES ('362', '29', '石河子', '2', '0', '0');
INSERT INTO `db_area` VALUES ('363', '29', '图木舒克', '2', '0', '0');
INSERT INTO `db_area` VALUES ('364', '29', '吐鲁番', '2', '0', '0');
INSERT INTO `db_area` VALUES ('365', '29', '五家渠', '2', '0', '0');
INSERT INTO `db_area` VALUES ('366', '29', '伊犁', '2', '0', '0');
INSERT INTO `db_area` VALUES ('367', '30', '昆明', '2', '0', '0');
INSERT INTO `db_area` VALUES ('368', '30', '怒江', '2', '0', '0');
INSERT INTO `db_area` VALUES ('369', '30', '普洱', '2', '0', '0');
INSERT INTO `db_area` VALUES ('370', '30', '丽江', '2', '0', '0');
INSERT INTO `db_area` VALUES ('371', '30', '保山', '2', '0', '0');
INSERT INTO `db_area` VALUES ('372', '30', '楚雄', '2', '0', '0');
INSERT INTO `db_area` VALUES ('373', '30', '大理', '2', '0', '0');
INSERT INTO `db_area` VALUES ('374', '30', '德宏', '2', '0', '0');
INSERT INTO `db_area` VALUES ('375', '30', '迪庆', '2', '0', '0');
INSERT INTO `db_area` VALUES ('376', '30', '红河', '2', '0', '0');
INSERT INTO `db_area` VALUES ('377', '30', '临沧', '2', '0', '0');
INSERT INTO `db_area` VALUES ('378', '30', '曲靖', '2', '0', '0');
INSERT INTO `db_area` VALUES ('379', '30', '文山', '2', '0', '0');
INSERT INTO `db_area` VALUES ('380', '30', '西双版纳', '2', '0', '0');
INSERT INTO `db_area` VALUES ('381', '30', '玉溪', '2', '0', '0');
INSERT INTO `db_area` VALUES ('382', '30', '昭通', '2', '0', '0');
INSERT INTO `db_area` VALUES ('383', '31', '杭州', '2', '0', '0');
INSERT INTO `db_area` VALUES ('384', '31', '湖州', '2', '0', '0');
INSERT INTO `db_area` VALUES ('385', '31', '嘉兴', '2', '0', '0');
INSERT INTO `db_area` VALUES ('386', '31', '金华', '2', '0', '0');
INSERT INTO `db_area` VALUES ('387', '31', '丽水', '2', '0', '0');
INSERT INTO `db_area` VALUES ('388', '31', '宁波', '2', '0', '0');
INSERT INTO `db_area` VALUES ('389', '31', '绍兴', '2', '0', '0');
INSERT INTO `db_area` VALUES ('390', '31', '台州', '2', '0', '0');
INSERT INTO `db_area` VALUES ('391', '31', '温州', '2', '0', '0');
INSERT INTO `db_area` VALUES ('392', '31', '舟山', '2', '0', '0');
INSERT INTO `db_area` VALUES ('393', '31', '衢州', '2', '0', '0');
INSERT INTO `db_area` VALUES ('396', '34', '澳门', '2', '0', '0');
INSERT INTO `db_area` VALUES ('398', '36', '迎江区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('399', '36', '大观区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('400', '36', '宜秀区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('401', '36', '桐城市', '3', '0', '0');
INSERT INTO `db_area` VALUES ('402', '36', '怀宁县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('403', '36', '枞阳县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('404', '36', '潜山县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('405', '36', '太湖县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('406', '36', '宿松县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('407', '36', '望江县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('408', '36', '岳西县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('409', '37', '中市区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('410', '37', '东市区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('411', '37', '西市区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('412', '37', '郊区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('413', '37', '怀远县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('414', '37', '五河县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('415', '37', '固镇县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('416', '38', '居巢区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('417', '38', '庐江县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('418', '38', '无为县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('419', '38', '含山县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('420', '38', '和县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('421', '39', '贵池区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('422', '39', '东至县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('423', '39', '石台县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('424', '39', '青阳县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('425', '40', '琅琊区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('426', '40', '南谯区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('427', '40', '天长市', '3', '0', '0');
INSERT INTO `db_area` VALUES ('428', '40', '明光市', '3', '0', '0');
INSERT INTO `db_area` VALUES ('429', '40', '来安县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('430', '40', '全椒县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('431', '40', '定远县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('432', '40', '凤阳县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('433', '41', '蚌山区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('434', '41', '龙子湖区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('435', '41', '禹会区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('436', '41', '淮上区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('437', '41', '颍州区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('438', '41', '颍东区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('439', '41', '颍泉区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('440', '41', '界首市', '3', '0', '0');
INSERT INTO `db_area` VALUES ('441', '41', '临泉县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('442', '41', '太和县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('443', '41', '阜南县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('444', '41', '颖上县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('445', '42', '相山区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('446', '42', '杜集区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('447', '42', '烈山区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('448', '42', '濉溪县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('449', '43', '田家庵区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('450', '43', '大通区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('451', '43', '谢家集区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('452', '43', '八公山区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('453', '43', '潘集区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('454', '43', '凤台县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('455', '44', '屯溪区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('456', '44', '黄山区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('457', '44', '徽州区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('458', '44', '歙县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('459', '44', '休宁县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('460', '44', '黟县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('461', '44', '祁门县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('462', '45', '金安区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('463', '45', '裕安区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('464', '45', '寿县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('465', '45', '霍邱县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('466', '45', '舒城县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('467', '45', '金寨县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('468', '45', '霍山县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('469', '46', '雨山区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('470', '46', '花山区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('471', '46', '金家庄区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('472', '46', '当涂县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('473', '47', '埇桥区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('474', '47', '砀山县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('475', '47', '萧县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('476', '47', '灵璧县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('477', '47', '泗县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('478', '48', '铜官山区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('479', '48', '狮子山区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('480', '48', '郊区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('481', '48', '铜陵县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('482', '49', '镜湖区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('483', '49', '弋江区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('484', '49', '鸠江区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('485', '49', '三山区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('486', '49', '芜湖县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('487', '49', '繁昌县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('488', '49', '南陵县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('489', '50', '宣州区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('490', '50', '宁国市', '3', '0', '0');
INSERT INTO `db_area` VALUES ('491', '50', '郎溪县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('492', '50', '广德县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('493', '50', '泾县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('494', '50', '绩溪县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('495', '50', '旌德县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('496', '51', '涡阳县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('497', '51', '蒙城县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('498', '51', '利辛县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('499', '51', '谯城区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('500', '2', '东城区', '2', '0', '0');
INSERT INTO `db_area` VALUES ('501', '2', '西城区', '2', '0', '0');
INSERT INTO `db_area` VALUES ('502', '2', '海淀区', '2', '0', '0');
INSERT INTO `db_area` VALUES ('503', '2', '朝阳区', '2', '0', '0');
INSERT INTO `db_area` VALUES ('504', '2', '崇文区', '2', '0', '0');
INSERT INTO `db_area` VALUES ('505', '2', '宣武区', '2', '0', '0');
INSERT INTO `db_area` VALUES ('506', '2', '丰台区', '2', '0', '0');
INSERT INTO `db_area` VALUES ('507', '2', '石景山区', '2', '0', '0');
INSERT INTO `db_area` VALUES ('508', '2', '房山区', '2', '0', '0');
INSERT INTO `db_area` VALUES ('509', '2', '门头沟区', '2', '0', '0');
INSERT INTO `db_area` VALUES ('510', '2', '通州区', '2', '0', '0');
INSERT INTO `db_area` VALUES ('511', '2', '顺义区', '2', '0', '0');
INSERT INTO `db_area` VALUES ('512', '2', '昌平区', '2', '0', '0');
INSERT INTO `db_area` VALUES ('513', '2', '怀柔区', '2', '0', '0');
INSERT INTO `db_area` VALUES ('514', '2', '平谷区', '2', '0', '0');
INSERT INTO `db_area` VALUES ('515', '2', '大兴区', '2', '0', '0');
INSERT INTO `db_area` VALUES ('516', '2', '密云县', '2', '0', '0');
INSERT INTO `db_area` VALUES ('517', '2', '延庆县', '2', '0', '0');
INSERT INTO `db_area` VALUES ('518', '53', '鼓楼区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('519', '53', '台江区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('520', '53', '仓山区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('521', '53', '马尾区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('522', '53', '晋安区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('523', '53', '福清市', '3', '0', '0');
INSERT INTO `db_area` VALUES ('524', '53', '长乐市', '3', '0', '0');
INSERT INTO `db_area` VALUES ('525', '53', '闽侯县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('526', '53', '连江县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('527', '53', '罗源县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('528', '53', '闽清县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('529', '53', '永泰县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('530', '53', '平潭县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('531', '54', '新罗区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('532', '54', '漳平市', '3', '0', '0');
INSERT INTO `db_area` VALUES ('533', '54', '长汀县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('534', '54', '永定县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('535', '54', '上杭县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('536', '54', '武平县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('537', '54', '连城县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('538', '55', '延平区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('539', '55', '邵武市', '3', '0', '0');
INSERT INTO `db_area` VALUES ('540', '55', '武夷山市', '3', '0', '0');
INSERT INTO `db_area` VALUES ('541', '55', '建瓯市', '3', '0', '0');
INSERT INTO `db_area` VALUES ('542', '55', '建阳市', '3', '0', '0');
INSERT INTO `db_area` VALUES ('543', '55', '顺昌县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('544', '55', '浦城县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('545', '55', '光泽县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('546', '55', '松溪县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('547', '55', '政和县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('548', '56', '蕉城区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('549', '56', '福安市', '3', '0', '0');
INSERT INTO `db_area` VALUES ('550', '56', '福鼎市', '3', '0', '0');
INSERT INTO `db_area` VALUES ('551', '56', '霞浦县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('552', '56', '古田县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('553', '56', '屏南县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('554', '56', '寿宁县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('555', '56', '周宁县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('556', '56', '柘荣县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('557', '57', '城厢区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('558', '57', '涵江区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('559', '57', '荔城区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('560', '57', '秀屿区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('561', '57', '仙游县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('562', '58', '鲤城区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('563', '58', '丰泽区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('564', '58', '洛江区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('565', '58', '清濛开发区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('566', '58', '泉港区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('567', '58', '石狮市', '3', '0', '0');
INSERT INTO `db_area` VALUES ('568', '58', '晋江市', '3', '0', '0');
INSERT INTO `db_area` VALUES ('569', '58', '南安市', '3', '0', '0');
INSERT INTO `db_area` VALUES ('570', '58', '惠安县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('571', '58', '安溪县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('572', '58', '永春县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('573', '58', '德化县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('574', '58', '金门县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('575', '59', '梅列区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('576', '59', '三元区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('577', '59', '永安市', '3', '0', '0');
INSERT INTO `db_area` VALUES ('578', '59', '明溪县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('579', '59', '清流县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('580', '59', '宁化县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('581', '59', '大田县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('582', '59', '尤溪县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('583', '59', '沙县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('584', '59', '将乐县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('585', '59', '泰宁县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('586', '59', '建宁县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('587', '60', '思明区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('588', '60', '海沧区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('589', '60', '湖里区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('590', '60', '集美区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('591', '60', '同安区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('592', '60', '翔安区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('593', '61', '芗城区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('594', '61', '龙文区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('595', '61', '龙海市', '3', '0', '0');
INSERT INTO `db_area` VALUES ('596', '61', '云霄县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('597', '61', '漳浦县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('598', '61', '诏安县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('599', '61', '长泰县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('600', '61', '东山县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('601', '61', '南靖县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('602', '61', '平和县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('603', '61', '华安县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('604', '62', '皋兰县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('605', '62', '城关区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('606', '62', '七里河区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('607', '62', '西固区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('608', '62', '安宁区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('609', '62', '红古区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('610', '62', '永登县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('611', '62', '榆中县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('612', '63', '白银区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('613', '63', '平川区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('614', '63', '会宁县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('615', '63', '景泰县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('616', '63', '靖远县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('617', '64', '临洮县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('618', '64', '陇西县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('619', '64', '通渭县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('620', '64', '渭源县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('621', '64', '漳县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('622', '64', '岷县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('623', '64', '安定区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('624', '64', '安定区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('625', '65', '合作市', '3', '0', '0');
INSERT INTO `db_area` VALUES ('626', '65', '临潭县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('627', '65', '卓尼县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('628', '65', '舟曲县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('629', '65', '迭部县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('630', '65', '玛曲县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('631', '65', '碌曲县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('632', '65', '夏河县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('633', '66', '嘉峪关市', '3', '0', '0');
INSERT INTO `db_area` VALUES ('634', '67', '金川区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('635', '67', '永昌县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('636', '68', '肃州区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('637', '68', '玉门市', '3', '0', '0');
INSERT INTO `db_area` VALUES ('638', '68', '敦煌市', '3', '0', '0');
INSERT INTO `db_area` VALUES ('639', '68', '金塔县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('640', '68', '瓜州县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('641', '68', '肃北', '3', '0', '0');
INSERT INTO `db_area` VALUES ('642', '68', '阿克塞', '3', '0', '0');
INSERT INTO `db_area` VALUES ('643', '69', '临夏市', '3', '0', '0');
INSERT INTO `db_area` VALUES ('644', '69', '临夏县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('645', '69', '康乐县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('646', '69', '永靖县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('647', '69', '广河县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('648', '69', '和政县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('649', '69', '东乡族自治县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('650', '69', '积石山', '3', '0', '0');
INSERT INTO `db_area` VALUES ('651', '70', '成县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('652', '70', '徽县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('653', '70', '康县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('654', '70', '礼县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('655', '70', '两当县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('656', '70', '文县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('657', '70', '西和县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('658', '70', '宕昌县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('659', '70', '武都区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('660', '71', '崇信县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('661', '71', '华亭县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('662', '71', '静宁县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('663', '71', '灵台县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('664', '71', '崆峒区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('665', '71', '庄浪县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('666', '71', '泾川县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('667', '72', '合水县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('668', '72', '华池县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('669', '72', '环县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('670', '72', '宁县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('671', '72', '庆城县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('672', '72', '西峰区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('673', '72', '镇原县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('674', '72', '正宁县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('675', '73', '甘谷县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('676', '73', '秦安县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('677', '73', '清水县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('678', '73', '秦州区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('679', '73', '麦积区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('680', '73', '武山县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('681', '73', '张家川', '3', '0', '0');
INSERT INTO `db_area` VALUES ('682', '74', '古浪县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('683', '74', '民勤县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('684', '74', '天祝', '3', '0', '0');
INSERT INTO `db_area` VALUES ('685', '74', '凉州区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('686', '75', '高台县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('687', '75', '临泽县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('688', '75', '民乐县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('689', '75', '山丹县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('690', '75', '肃南', '3', '0', '0');
INSERT INTO `db_area` VALUES ('691', '75', '甘州区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('692', '76', '从化市', '3', '0', '0');
INSERT INTO `db_area` VALUES ('693', '76', '天河区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('694', '76', '东山区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('695', '76', '白云区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('696', '76', '海珠区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('697', '76', '荔湾区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('698', '76', '越秀区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('699', '76', '黄埔区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('700', '76', '番禺区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('701', '76', '花都区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('702', '76', '增城区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('703', '76', '从化区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('704', '76', '市郊', '3', '0', '0');
INSERT INTO `db_area` VALUES ('705', '77', '福田区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('706', '77', '罗湖区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('707', '77', '南山区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('708', '77', '宝安区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('709', '77', '龙岗区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('710', '77', '盐田区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('711', '78', '湘桥区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('712', '78', '潮安县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('713', '78', '饶平县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('714', '79', '南城区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('715', '79', '东城区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('716', '79', '万江区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('717', '79', '莞城区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('718', '79', '石龙镇', '3', '0', '0');
INSERT INTO `db_area` VALUES ('719', '79', '虎门镇', '3', '0', '0');
INSERT INTO `db_area` VALUES ('720', '79', '麻涌镇', '3', '0', '0');
INSERT INTO `db_area` VALUES ('721', '79', '道滘镇', '3', '0', '0');
INSERT INTO `db_area` VALUES ('722', '79', '石碣镇', '3', '0', '0');
INSERT INTO `db_area` VALUES ('723', '79', '沙田镇', '3', '0', '0');
INSERT INTO `db_area` VALUES ('724', '79', '望牛墩镇', '3', '0', '0');
INSERT INTO `db_area` VALUES ('725', '79', '洪梅镇', '3', '0', '0');
INSERT INTO `db_area` VALUES ('726', '79', '茶山镇', '3', '0', '0');
INSERT INTO `db_area` VALUES ('727', '79', '寮步镇', '3', '0', '0');
INSERT INTO `db_area` VALUES ('728', '79', '大岭山镇', '3', '0', '0');
INSERT INTO `db_area` VALUES ('729', '79', '大朗镇', '3', '0', '0');
INSERT INTO `db_area` VALUES ('730', '79', '黄江镇', '3', '0', '0');
INSERT INTO `db_area` VALUES ('731', '79', '樟木头', '3', '0', '0');
INSERT INTO `db_area` VALUES ('732', '79', '凤岗镇', '3', '0', '0');
INSERT INTO `db_area` VALUES ('733', '79', '塘厦镇', '3', '0', '0');
INSERT INTO `db_area` VALUES ('734', '79', '谢岗镇', '3', '0', '0');
INSERT INTO `db_area` VALUES ('735', '79', '厚街镇', '3', '0', '0');
INSERT INTO `db_area` VALUES ('736', '79', '清溪镇', '3', '0', '0');
INSERT INTO `db_area` VALUES ('737', '79', '常平镇', '3', '0', '0');
INSERT INTO `db_area` VALUES ('738', '79', '桥头镇', '3', '0', '0');
INSERT INTO `db_area` VALUES ('739', '79', '横沥镇', '3', '0', '0');
INSERT INTO `db_area` VALUES ('740', '79', '东坑镇', '3', '0', '0');
INSERT INTO `db_area` VALUES ('741', '79', '企石镇', '3', '0', '0');
INSERT INTO `db_area` VALUES ('742', '79', '石排镇', '3', '0', '0');
INSERT INTO `db_area` VALUES ('743', '79', '长安镇', '3', '0', '0');
INSERT INTO `db_area` VALUES ('744', '79', '中堂镇', '3', '0', '0');
INSERT INTO `db_area` VALUES ('745', '79', '高埗镇', '3', '0', '0');
INSERT INTO `db_area` VALUES ('746', '80', '禅城区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('747', '80', '南海区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('748', '80', '顺德区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('749', '80', '三水区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('750', '80', '高明区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('751', '81', '东源县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('752', '81', '和平县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('753', '81', '源城区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('754', '81', '连平县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('755', '81', '龙川县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('756', '81', '紫金县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('757', '82', '惠阳区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('758', '82', '惠城区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('759', '82', '大亚湾', '3', '0', '0');
INSERT INTO `db_area` VALUES ('760', '82', '博罗县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('761', '82', '惠东县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('762', '82', '龙门县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('763', '83', '江海区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('764', '83', '蓬江区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('765', '83', '新会区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('766', '83', '台山市', '3', '0', '0');
INSERT INTO `db_area` VALUES ('767', '83', '开平市', '3', '0', '0');
INSERT INTO `db_area` VALUES ('768', '83', '鹤山市', '3', '0', '0');
INSERT INTO `db_area` VALUES ('769', '83', '恩平市', '3', '0', '0');
INSERT INTO `db_area` VALUES ('770', '84', '榕城区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('771', '84', '普宁市', '3', '0', '0');
INSERT INTO `db_area` VALUES ('772', '84', '揭东县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('773', '84', '揭西县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('774', '84', '惠来县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('775', '85', '茂南区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('776', '85', '茂港区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('777', '85', '高州市', '3', '0', '0');
INSERT INTO `db_area` VALUES ('778', '85', '化州市', '3', '0', '0');
INSERT INTO `db_area` VALUES ('779', '85', '信宜市', '3', '0', '0');
INSERT INTO `db_area` VALUES ('780', '85', '电白县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('781', '86', '梅县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('782', '86', '梅江区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('783', '86', '兴宁市', '3', '0', '0');
INSERT INTO `db_area` VALUES ('784', '86', '大埔县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('785', '86', '丰顺县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('786', '86', '五华县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('787', '86', '平远县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('788', '86', '蕉岭县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('789', '87', '清城区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('790', '87', '英德市', '3', '0', '0');
INSERT INTO `db_area` VALUES ('791', '87', '连州市', '3', '0', '0');
INSERT INTO `db_area` VALUES ('792', '87', '佛冈县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('793', '87', '阳山县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('794', '87', '清新县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('795', '87', '连山', '3', '0', '0');
INSERT INTO `db_area` VALUES ('796', '87', '连南', '3', '0', '0');
INSERT INTO `db_area` VALUES ('797', '88', '南澳县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('798', '88', '潮阳区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('799', '88', '澄海区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('800', '88', '龙湖区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('801', '88', '金平区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('802', '88', '濠江区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('803', '88', '潮南区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('804', '89', '城区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('805', '89', '陆丰市', '3', '0', '0');
INSERT INTO `db_area` VALUES ('806', '89', '海丰县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('807', '89', '陆河县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('808', '90', '曲江县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('809', '90', '浈江区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('810', '90', '武江区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('811', '90', '曲江区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('812', '90', '乐昌市', '3', '0', '0');
INSERT INTO `db_area` VALUES ('813', '90', '南雄市', '3', '0', '0');
INSERT INTO `db_area` VALUES ('814', '90', '始兴县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('815', '90', '仁化县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('816', '90', '翁源县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('817', '90', '新丰县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('818', '90', '乳源', '3', '0', '0');
INSERT INTO `db_area` VALUES ('819', '91', '江城区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('820', '91', '阳春市', '3', '0', '0');
INSERT INTO `db_area` VALUES ('821', '91', '阳西县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('822', '91', '阳东县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('823', '92', '云城区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('824', '92', '罗定市', '3', '0', '0');
INSERT INTO `db_area` VALUES ('825', '92', '新兴县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('826', '92', '郁南县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('827', '92', '云安县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('828', '93', '赤坎区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('829', '93', '霞山区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('830', '93', '坡头区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('831', '93', '麻章区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('832', '93', '廉江市', '3', '0', '0');
INSERT INTO `db_area` VALUES ('833', '93', '雷州市', '3', '0', '0');
INSERT INTO `db_area` VALUES ('834', '93', '吴川市', '3', '0', '0');
INSERT INTO `db_area` VALUES ('835', '93', '遂溪县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('836', '93', '徐闻县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('837', '94', '肇庆市', '3', '0', '0');
INSERT INTO `db_area` VALUES ('838', '94', '高要市', '3', '0', '0');
INSERT INTO `db_area` VALUES ('839', '94', '四会市', '3', '0', '0');
INSERT INTO `db_area` VALUES ('840', '94', '广宁县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('841', '94', '怀集县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('842', '94', '封开县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('843', '94', '德庆县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('844', '95', '石岐街道', '3', '0', '0');
INSERT INTO `db_area` VALUES ('845', '95', '东区街道', '3', '0', '0');
INSERT INTO `db_area` VALUES ('846', '95', '西区街道', '3', '0', '0');
INSERT INTO `db_area` VALUES ('847', '95', '环城街道', '3', '0', '0');
INSERT INTO `db_area` VALUES ('848', '95', '中山港街道', '3', '0', '0');
INSERT INTO `db_area` VALUES ('849', '95', '五桂山街道', '3', '0', '0');
INSERT INTO `db_area` VALUES ('850', '96', '香洲区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('851', '96', '斗门区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('852', '96', '金湾区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('853', '97', '邕宁区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('854', '97', '青秀区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('855', '97', '兴宁区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('856', '97', '良庆区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('857', '97', '西乡塘区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('858', '97', '江南区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('859', '97', '武鸣县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('860', '97', '隆安县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('861', '97', '马山县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('862', '97', '上林县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('863', '97', '宾阳县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('864', '97', '横县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('865', '98', '秀峰区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('866', '98', '叠彩区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('867', '98', '象山区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('868', '98', '七星区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('869', '98', '雁山区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('870', '98', '阳朔县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('871', '98', '临桂县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('872', '98', '灵川县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('873', '98', '全州县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('874', '98', '平乐县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('875', '98', '兴安县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('876', '98', '灌阳县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('877', '98', '荔浦县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('878', '98', '资源县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('879', '98', '永福县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('880', '98', '龙胜', '3', '0', '0');
INSERT INTO `db_area` VALUES ('881', '98', '恭城', '3', '0', '0');
INSERT INTO `db_area` VALUES ('882', '99', '右江区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('883', '99', '凌云县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('884', '99', '平果县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('885', '99', '西林县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('886', '99', '乐业县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('887', '99', '德保县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('888', '99', '田林县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('889', '99', '田阳县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('890', '99', '靖西县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('891', '99', '田东县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('892', '99', '那坡县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('893', '99', '隆林', '3', '0', '0');
INSERT INTO `db_area` VALUES ('894', '100', '海城区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('895', '100', '银海区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('896', '100', '铁山港区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('897', '100', '合浦县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('898', '101', '江州区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('899', '101', '凭祥市', '3', '0', '0');
INSERT INTO `db_area` VALUES ('900', '101', '宁明县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('901', '101', '扶绥县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('902', '101', '龙州县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('903', '101', '大新县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('904', '101', '天等县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('905', '102', '港口区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('906', '102', '防城区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('907', '102', '东兴市', '3', '0', '0');
INSERT INTO `db_area` VALUES ('908', '102', '上思县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('909', '103', '港北区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('910', '103', '港南区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('911', '103', '覃塘区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('912', '103', '桂平市', '3', '0', '0');
INSERT INTO `db_area` VALUES ('913', '103', '平南县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('914', '104', '金城江区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('915', '104', '宜州市', '3', '0', '0');
INSERT INTO `db_area` VALUES ('916', '104', '天峨县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('917', '104', '凤山县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('918', '104', '南丹县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('919', '104', '东兰县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('920', '104', '都安', '3', '0', '0');
INSERT INTO `db_area` VALUES ('921', '104', '罗城', '3', '0', '0');
INSERT INTO `db_area` VALUES ('922', '104', '巴马', '3', '0', '0');
INSERT INTO `db_area` VALUES ('923', '104', '环江', '3', '0', '0');
INSERT INTO `db_area` VALUES ('924', '104', '大化', '3', '0', '0');
INSERT INTO `db_area` VALUES ('925', '105', '八步区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('926', '105', '钟山县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('927', '105', '昭平县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('928', '105', '富川', '3', '0', '0');
INSERT INTO `db_area` VALUES ('929', '106', '兴宾区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('930', '106', '合山市', '3', '0', '0');
INSERT INTO `db_area` VALUES ('931', '106', '象州县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('932', '106', '武宣县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('933', '106', '忻城县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('934', '106', '金秀', '3', '0', '0');
INSERT INTO `db_area` VALUES ('935', '107', '城中区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('936', '107', '鱼峰区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('937', '107', '柳北区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('938', '107', '柳南区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('939', '107', '柳江县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('940', '107', '柳城县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('941', '107', '鹿寨县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('942', '107', '融安县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('943', '107', '融水', '3', '0', '0');
INSERT INTO `db_area` VALUES ('944', '107', '三江', '3', '0', '0');
INSERT INTO `db_area` VALUES ('945', '108', '钦南区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('946', '108', '钦北区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('947', '108', '灵山县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('948', '108', '浦北县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('949', '109', '万秀区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('950', '109', '蝶山区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('951', '109', '长洲区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('952', '109', '岑溪市', '3', '0', '0');
INSERT INTO `db_area` VALUES ('953', '109', '苍梧县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('954', '109', '藤县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('955', '109', '蒙山县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('956', '110', '玉州区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('957', '110', '北流市', '3', '0', '0');
INSERT INTO `db_area` VALUES ('958', '110', '容县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('959', '110', '陆川县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('960', '110', '博白县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('961', '110', '兴业县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('962', '111', '南明区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('963', '111', '云岩区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('964', '111', '花溪区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('965', '111', '乌当区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('966', '111', '白云区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('967', '111', '小河区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('968', '111', '金阳新区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('969', '111', '新天园区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('970', '111', '清镇市', '3', '0', '0');
INSERT INTO `db_area` VALUES ('971', '111', '开阳县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('972', '111', '修文县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('973', '111', '息烽县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('974', '112', '西秀区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('975', '112', '关岭', '3', '0', '0');
INSERT INTO `db_area` VALUES ('976', '112', '镇宁', '3', '0', '0');
INSERT INTO `db_area` VALUES ('977', '112', '紫云', '3', '0', '0');
INSERT INTO `db_area` VALUES ('978', '112', '平坝县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('979', '112', '普定县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('980', '113', '毕节市', '3', '0', '0');
INSERT INTO `db_area` VALUES ('981', '113', '大方县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('982', '113', '黔西县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('983', '113', '金沙县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('984', '113', '织金县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('985', '113', '纳雍县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('986', '113', '赫章县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('987', '113', '威宁', '3', '0', '0');
INSERT INTO `db_area` VALUES ('988', '114', '钟山区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('989', '114', '六枝特区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('990', '114', '水城县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('991', '114', '盘县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('992', '115', '凯里市', '3', '0', '0');
INSERT INTO `db_area` VALUES ('993', '115', '黄平县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('994', '115', '施秉县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('995', '115', '三穗县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('996', '115', '镇远县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('997', '115', '岑巩县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('998', '115', '天柱县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('999', '115', '锦屏县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1000', '115', '剑河县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1001', '115', '台江县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1002', '115', '黎平县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1003', '115', '榕江县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1004', '115', '从江县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1005', '115', '雷山县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1006', '115', '麻江县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1007', '115', '丹寨县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1008', '116', '都匀市', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1009', '116', '福泉市', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1010', '116', '荔波县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1011', '116', '贵定县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1012', '116', '瓮安县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1013', '116', '独山县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1014', '116', '平塘县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1015', '116', '罗甸县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1016', '116', '长顺县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1017', '116', '龙里县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1018', '116', '惠水县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1019', '116', '三都', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1020', '117', '兴义市', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1021', '117', '兴仁县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1022', '117', '普安县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1023', '117', '晴隆县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1024', '117', '贞丰县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1025', '117', '望谟县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1026', '117', '册亨县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1027', '117', '安龙县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1028', '118', '铜仁市', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1029', '118', '江口县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1030', '118', '石阡县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1031', '118', '思南县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1032', '118', '德江县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1033', '118', '玉屏', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1034', '118', '印江', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1035', '118', '沿河', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1036', '118', '松桃', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1037', '118', '万山特区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1038', '119', '红花岗区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1039', '119', '务川县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1040', '119', '道真县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1041', '119', '汇川区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1042', '119', '赤水市', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1043', '119', '仁怀市', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1044', '119', '遵义县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1045', '119', '桐梓县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1046', '119', '绥阳县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1047', '119', '正安县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1048', '119', '凤冈县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1049', '119', '湄潭县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1050', '119', '余庆县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1051', '119', '习水县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1052', '119', '道真', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1053', '119', '务川', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1054', '120', '秀英区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1055', '120', '龙华区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1056', '120', '琼山区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1057', '120', '美兰区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1058', '137', '市区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1059', '137', '洋浦开发区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1060', '137', '那大镇', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1061', '137', '王五镇', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1062', '137', '雅星镇', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1063', '137', '大成镇', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1064', '137', '中和镇', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1065', '137', '峨蔓镇', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1066', '137', '南丰镇', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1067', '137', '白马井镇', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1068', '137', '兰洋镇', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1069', '137', '和庆镇', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1070', '137', '海头镇', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1071', '137', '排浦镇', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1072', '137', '东成镇', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1073', '137', '光村镇', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1074', '137', '木棠镇', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1075', '137', '新州镇', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1076', '137', '三都镇', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1077', '137', '其他', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1078', '138', '长安区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1079', '138', '桥东区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1080', '138', '桥西区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1081', '138', '新华区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1082', '138', '裕华区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1083', '138', '井陉矿区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1084', '138', '高新区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1085', '138', '辛集市', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1086', '138', '藁城市', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1087', '138', '晋州市', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1088', '138', '新乐市', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1089', '138', '鹿泉市', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1090', '138', '井陉县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1091', '138', '正定县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1092', '138', '栾城县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1093', '138', '行唐县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1094', '138', '灵寿县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1095', '138', '高邑县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1096', '138', '深泽县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1097', '138', '赞皇县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1098', '138', '无极县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1099', '138', '平山县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1100', '138', '元氏县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1101', '138', '赵县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1102', '139', '新市区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1103', '139', '南市区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1104', '139', '北市区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1105', '139', '涿州市', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1106', '139', '定州市', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1107', '139', '安国市', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1108', '139', '高碑店市', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1109', '139', '满城县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1110', '139', '清苑县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1111', '139', '涞水县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1112', '139', '阜平县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1113', '139', '徐水县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1114', '139', '定兴县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1115', '139', '唐县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1116', '139', '高阳县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1117', '139', '容城县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1118', '139', '涞源县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1119', '139', '望都县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1120', '139', '安新县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1121', '139', '易县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1122', '139', '曲阳县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1123', '139', '蠡县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1124', '139', '顺平县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1125', '139', '博野县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1126', '139', '雄县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1127', '140', '运河区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1128', '140', '新华区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1129', '140', '泊头市', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1130', '140', '任丘市', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1131', '140', '黄骅市', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1132', '140', '河间市', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1133', '140', '沧县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1134', '140', '青县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1135', '140', '东光县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1136', '140', '海兴县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1137', '140', '盐山县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1138', '140', '肃宁县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1139', '140', '南皮县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1140', '140', '吴桥县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1141', '140', '献县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1142', '140', '孟村', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1143', '141', '双桥区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1144', '141', '双滦区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1145', '141', '鹰手营子矿区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1146', '141', '承德县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1147', '141', '兴隆县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1148', '141', '平泉县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1149', '141', '滦平县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1150', '141', '隆化县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1151', '141', '丰宁', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1152', '141', '宽城', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1153', '141', '围场', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1154', '142', '从台区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1155', '142', '复兴区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1156', '142', '邯山区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1157', '142', '峰峰矿区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1158', '142', '武安市', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1159', '142', '邯郸县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1160', '142', '临漳县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1161', '142', '成安县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1162', '142', '大名县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1163', '142', '涉县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1164', '142', '磁县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1165', '142', '肥乡县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1166', '142', '永年县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1167', '142', '邱县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1168', '142', '鸡泽县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1169', '142', '广平县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1170', '142', '馆陶县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1171', '142', '魏县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1172', '142', '曲周县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1173', '143', '桃城区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1174', '143', '冀州市', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1175', '143', '深州市', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1176', '143', '枣强县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1177', '143', '武邑县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1178', '143', '武强县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1179', '143', '饶阳县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1180', '143', '安平县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1181', '143', '故城县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1182', '143', '景县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1183', '143', '阜城县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1184', '144', '安次区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1185', '144', '广阳区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1186', '144', '霸州市', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1187', '144', '三河市', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1188', '144', '固安县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1189', '144', '永清县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1190', '144', '香河县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1191', '144', '大城县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1192', '144', '文安县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1193', '144', '大厂', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1194', '145', '海港区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1195', '145', '山海关区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1196', '145', '北戴河区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1197', '145', '昌黎县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1198', '145', '抚宁县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1199', '145', '卢龙县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1200', '145', '青龙', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1201', '146', '路北区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1202', '146', '路南区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1203', '146', '古冶区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1204', '146', '开平区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1205', '146', '丰南区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1206', '146', '丰润区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1207', '146', '遵化市', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1208', '146', '迁安市', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1209', '146', '滦县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1210', '146', '滦南县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1211', '146', '乐亭县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1212', '146', '迁西县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1213', '146', '玉田县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1214', '146', '唐海县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1215', '147', '桥东区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1216', '147', '桥西区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1217', '147', '南宫市', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1218', '147', '沙河市', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1219', '147', '邢台县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1220', '147', '临城县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1221', '147', '内丘县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1222', '147', '柏乡县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1223', '147', '隆尧县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1224', '147', '任县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1225', '147', '南和县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1226', '147', '宁晋县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1227', '147', '巨鹿县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1228', '147', '新河县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1229', '147', '广宗县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1230', '147', '平乡县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1231', '147', '威县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1232', '147', '清河县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1233', '147', '临西县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1234', '148', '桥西区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1235', '148', '桥东区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1236', '148', '宣化区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1237', '148', '下花园区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1238', '148', '宣化县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1239', '148', '张北县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1240', '148', '康保县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1241', '148', '沽源县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1242', '148', '尚义县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1243', '148', '蔚县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1244', '148', '阳原县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1245', '148', '怀安县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1246', '148', '万全县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1247', '148', '怀来县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1248', '148', '涿鹿县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1249', '148', '赤城县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1250', '148', '崇礼县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1251', '149', '金水区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1252', '149', '邙山区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1253', '149', '二七区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1254', '149', '管城区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1255', '149', '中原区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1256', '149', '上街区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1257', '149', '惠济区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1258', '149', '郑东新区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1259', '149', '经济技术开发区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1260', '149', '高新开发区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1261', '149', '出口加工区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1262', '149', '巩义市', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1263', '149', '荥阳市', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1264', '149', '新密市', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1265', '149', '新郑市', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1266', '149', '登封市', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1267', '149', '中牟县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1268', '150', '西工区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1269', '150', '老城区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1270', '150', '涧西区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1271', '150', '瀍河回族区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1272', '150', '洛龙区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1273', '150', '吉利区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1274', '150', '偃师市', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1275', '150', '孟津县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1276', '150', '新安县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1277', '150', '栾川县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1278', '150', '嵩县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1279', '150', '汝阳县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1280', '150', '宜阳县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1281', '150', '洛宁县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1282', '150', '伊川县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1283', '151', '鼓楼区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1284', '151', '龙亭区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1285', '151', '顺河回族区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1286', '151', '金明区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1287', '151', '禹王台区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1288', '151', '杞县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1289', '151', '通许县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1290', '151', '尉氏县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1291', '151', '开封县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1292', '151', '兰考县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1293', '152', '北关区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1294', '152', '文峰区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1295', '152', '殷都区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1296', '152', '龙安区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1297', '152', '林州市', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1298', '152', '安阳县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1299', '152', '汤阴县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1300', '152', '滑县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1301', '152', '内黄县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1302', '153', '淇滨区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1303', '153', '山城区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1304', '153', '鹤山区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1305', '153', '浚县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1306', '153', '淇县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1307', '154', '济源市', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1308', '155', '解放区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1309', '155', '中站区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1310', '155', '马村区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1311', '155', '山阳区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1312', '155', '沁阳市', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1313', '155', '孟州市', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1314', '155', '修武县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1315', '155', '博爱县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1316', '155', '武陟县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1317', '155', '温县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1318', '156', '卧龙区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1319', '156', '宛城区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1320', '156', '邓州市', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1321', '156', '南召县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1322', '156', '方城县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1323', '156', '西峡县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1324', '156', '镇平县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1325', '156', '内乡县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1326', '156', '淅川县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1327', '156', '社旗县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1328', '156', '唐河县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1329', '156', '新野县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1330', '156', '桐柏县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1331', '157', '新华区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1332', '157', '卫东区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1333', '157', '湛河区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1334', '157', '石龙区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1335', '157', '舞钢市', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1336', '157', '汝州市', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1337', '157', '宝丰县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1338', '157', '叶县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1339', '157', '鲁山县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1340', '157', '郏县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1341', '158', '湖滨区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1342', '158', '义马市', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1343', '158', '灵宝市', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1344', '158', '渑池县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1345', '158', '陕县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1346', '158', '卢氏县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1347', '159', '梁园区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1348', '159', '睢阳区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1349', '159', '永城市', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1350', '159', '民权县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1351', '159', '睢县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1352', '159', '宁陵县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1353', '159', '虞城县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1354', '159', '柘城县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1355', '159', '夏邑县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1356', '160', '卫滨区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1357', '160', '红旗区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1358', '160', '凤泉区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1359', '160', '牧野区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1360', '160', '卫辉市', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1361', '160', '辉县市', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1362', '160', '新乡县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1363', '160', '获嘉县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1364', '160', '原阳县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1365', '160', '延津县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1366', '160', '封丘县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1367', '160', '长垣县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1368', '161', '浉河区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1369', '161', '平桥区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1370', '161', '罗山县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1371', '161', '光山县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1372', '161', '新县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1373', '161', '商城县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1374', '161', '固始县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1375', '161', '潢川县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1376', '161', '淮滨县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1377', '161', '息县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1378', '162', '魏都区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1379', '162', '禹州市', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1380', '162', '长葛市', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1381', '162', '许昌县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1382', '162', '鄢陵县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1383', '162', '襄城县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1384', '163', '川汇区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1385', '163', '项城市', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1386', '163', '扶沟县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1387', '163', '西华县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1388', '163', '商水县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1389', '163', '沈丘县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1390', '163', '郸城县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1391', '163', '淮阳县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1392', '163', '太康县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1393', '163', '鹿邑县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1394', '164', '驿城区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1395', '164', '西平县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1396', '164', '上蔡县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1397', '164', '平舆县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1398', '164', '正阳县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1399', '164', '确山县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1400', '164', '泌阳县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1401', '164', '汝南县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1402', '164', '遂平县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1403', '164', '新蔡县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1404', '165', '郾城区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1405', '165', '源汇区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1406', '165', '召陵区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1407', '165', '舞阳县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1408', '165', '临颍县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1409', '166', '华龙区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1410', '166', '清丰县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1411', '166', '南乐县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1412', '166', '范县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1413', '166', '台前县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1414', '166', '濮阳县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1415', '167', '道里区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1416', '167', '南岗区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1417', '167', '动力区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1418', '167', '平房区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1419', '167', '香坊区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1420', '167', '太平区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1421', '167', '道外区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1422', '167', '阿城区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1423', '167', '呼兰区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1424', '167', '松北区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1425', '167', '尚志市', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1426', '167', '双城市', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1427', '167', '五常市', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1428', '167', '方正县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1429', '167', '宾县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1430', '167', '依兰县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1431', '167', '巴彦县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1432', '167', '通河县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1433', '167', '木兰县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1434', '167', '延寿县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1435', '168', '萨尔图区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1436', '168', '红岗区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1437', '168', '龙凤区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1438', '168', '让胡路区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1439', '168', '大同区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1440', '168', '肇州县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1441', '168', '肇源县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1442', '168', '林甸县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1443', '168', '杜尔伯特', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1444', '169', '呼玛县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1445', '169', '漠河县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1446', '169', '塔河县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1447', '170', '兴山区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1448', '170', '工农区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1449', '170', '南山区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1450', '170', '兴安区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1451', '170', '向阳区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1452', '170', '东山区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1453', '170', '萝北县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1454', '170', '绥滨县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1455', '171', '爱辉区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1456', '171', '五大连池市', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1457', '171', '北安市', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1458', '171', '嫩江县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1459', '171', '逊克县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1460', '171', '孙吴县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1461', '172', '鸡冠区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1462', '172', '恒山区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1463', '172', '城子河区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1464', '172', '滴道区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1465', '172', '梨树区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1466', '172', '虎林市', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1467', '172', '密山市', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1468', '172', '鸡东县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1469', '173', '前进区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1470', '173', '郊区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1471', '173', '向阳区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1472', '173', '东风区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1473', '173', '同江市', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1474', '173', '富锦市', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1475', '173', '桦南县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1476', '173', '桦川县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1477', '173', '汤原县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1478', '173', '抚远县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1479', '174', '爱民区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1480', '174', '东安区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1481', '174', '阳明区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1482', '174', '西安区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1483', '174', '绥芬河市', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1484', '174', '海林市', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1485', '174', '宁安市', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1486', '174', '穆棱市', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1487', '174', '东宁县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1488', '174', '林口县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1489', '175', '桃山区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1490', '175', '新兴区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1491', '175', '茄子河区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1492', '175', '勃利县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1493', '176', '龙沙区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1494', '176', '昂昂溪区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1495', '176', '铁峰区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1496', '176', '建华区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1497', '176', '富拉尔基区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1498', '176', '碾子山区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1499', '176', '梅里斯达斡尔区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1500', '176', '讷河市', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1501', '176', '龙江县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1502', '176', '依安县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1503', '176', '泰来县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1504', '176', '甘南县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1505', '176', '富裕县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1506', '176', '克山县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1507', '176', '克东县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1508', '176', '拜泉县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1509', '177', '尖山区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1510', '177', '岭东区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1511', '177', '四方台区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1512', '177', '宝山区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1513', '177', '集贤县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1514', '177', '友谊县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1515', '177', '宝清县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1516', '177', '饶河县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1517', '178', '北林区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1518', '178', '安达市', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1519', '178', '肇东市', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1520', '178', '海伦市', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1521', '178', '望奎县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1522', '178', '兰西县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1523', '178', '青冈县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1524', '178', '庆安县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1525', '178', '明水县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1526', '178', '绥棱县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1527', '179', '伊春区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1528', '179', '带岭区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1529', '179', '南岔区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1530', '179', '金山屯区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1531', '179', '西林区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1532', '179', '美溪区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1533', '179', '乌马河区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1534', '179', '翠峦区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1535', '179', '友好区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1536', '179', '上甘岭区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1537', '179', '五营区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1538', '179', '红星区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1539', '179', '新青区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1540', '179', '汤旺河区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1541', '179', '乌伊岭区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1542', '179', '铁力市', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1543', '179', '嘉荫县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1544', '180', '江岸区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1545', '180', '武昌区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1546', '180', '江汉区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1547', '180', '硚口区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1548', '180', '汉阳区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1549', '180', '青山区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1550', '180', '洪山区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1551', '180', '东西湖区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1552', '180', '汉南区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1553', '180', '蔡甸区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1554', '180', '江夏区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1555', '180', '黄陂区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1556', '180', '新洲区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1557', '180', '经济开发区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1558', '181', '仙桃市', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1559', '182', '鄂城区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1560', '182', '华容区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1561', '182', '梁子湖区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1562', '183', '黄州区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1563', '183', '麻城市', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1564', '183', '武穴市', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1565', '183', '团风县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1566', '183', '红安县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1567', '183', '罗田县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1568', '183', '英山县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1569', '183', '浠水县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1570', '183', '蕲春县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1571', '183', '黄梅县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1572', '184', '黄石港区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1573', '184', '西塞山区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1574', '184', '下陆区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1575', '184', '铁山区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1576', '184', '大冶市', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1577', '184', '阳新县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1578', '185', '东宝区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1579', '185', '掇刀区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1580', '185', '钟祥市', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1581', '185', '京山县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1582', '185', '沙洋县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1583', '186', '沙市区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1584', '186', '荆州区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1585', '186', '石首市', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1586', '186', '洪湖市', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1587', '186', '松滋市', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1588', '186', '公安县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1589', '186', '监利县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1590', '186', '江陵县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1591', '187', '潜江市', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1592', '188', '神农架林区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1593', '189', '张湾区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1594', '189', '茅箭区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1595', '189', '丹江口市', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1596', '189', '郧县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1597', '189', '郧西县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1598', '189', '竹山县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1599', '189', '竹溪县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1600', '189', '房县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1601', '190', '曾都区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1602', '190', '广水市', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1603', '191', '天门市', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1604', '192', '咸安区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1605', '192', '赤壁市', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1606', '192', '嘉鱼县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1607', '192', '通城县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1608', '192', '崇阳县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1609', '192', '通山县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1610', '193', '襄城区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1611', '193', '樊城区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1612', '193', '襄阳区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1613', '193', '老河口市', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1614', '193', '枣阳市', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1615', '193', '宜城市', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1616', '193', '南漳县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1617', '193', '谷城县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1618', '193', '保康县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1619', '194', '孝南区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1620', '194', '应城市', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1621', '194', '安陆市', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1622', '194', '汉川市', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1623', '194', '孝昌县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1624', '194', '大悟县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1625', '194', '云梦县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1626', '195', '长阳', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1627', '195', '五峰', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1628', '195', '西陵区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1629', '195', '伍家岗区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1630', '195', '点军区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1631', '195', '猇亭区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1632', '195', '夷陵区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1633', '195', '宜都市', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1634', '195', '当阳市', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1635', '195', '枝江市', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1636', '195', '远安县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1637', '195', '兴山县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1638', '195', '秭归县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1639', '196', '恩施市', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1640', '196', '利川市', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1641', '196', '建始县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1642', '196', '巴东县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1643', '196', '宣恩县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1644', '196', '咸丰县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1645', '196', '来凤县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1646', '196', '鹤峰县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1647', '197', '岳麓区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1648', '197', '芙蓉区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1649', '197', '天心区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1650', '197', '开福区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1651', '197', '雨花区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1652', '197', '开发区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1653', '197', '浏阳市', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1654', '197', '长沙县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1655', '197', '望城县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1656', '197', '宁乡县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1657', '198', '永定区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1658', '198', '武陵源区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1659', '198', '慈利县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1660', '198', '桑植县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1661', '199', '武陵区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1662', '199', '鼎城区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1663', '199', '津市市', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1664', '199', '安乡县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1665', '199', '汉寿县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1666', '199', '澧县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1667', '199', '临澧县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1668', '199', '桃源县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1669', '199', '石门县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1670', '200', '北湖区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1671', '200', '苏仙区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1672', '200', '资兴市', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1673', '200', '桂阳县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1674', '200', '宜章县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1675', '200', '永兴县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1676', '200', '嘉禾县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1677', '200', '临武县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1678', '200', '汝城县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1679', '200', '桂东县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1680', '200', '安仁县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1681', '201', '雁峰区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1682', '201', '珠晖区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1683', '201', '石鼓区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1684', '201', '蒸湘区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1685', '201', '南岳区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1686', '201', '耒阳市', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1687', '201', '常宁市', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1688', '201', '衡阳县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1689', '201', '衡南县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1690', '201', '衡山县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1691', '201', '衡东县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1692', '201', '祁东县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1693', '202', '鹤城区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1694', '202', '靖州', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1695', '202', '麻阳', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1696', '202', '通道', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1697', '202', '新晃', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1698', '202', '芷江', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1699', '202', '沅陵县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1700', '202', '辰溪县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1701', '202', '溆浦县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1702', '202', '中方县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1703', '202', '会同县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1704', '202', '洪江市', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1705', '203', '娄星区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1706', '203', '冷水江市', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1707', '203', '涟源市', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1708', '203', '双峰县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1709', '203', '新化县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1710', '204', '城步', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1711', '204', '双清区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1712', '204', '大祥区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1713', '204', '北塔区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1714', '204', '武冈市', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1715', '204', '邵东县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1716', '204', '新邵县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1717', '204', '邵阳县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1718', '204', '隆回县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1719', '204', '洞口县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1720', '204', '绥宁县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1721', '204', '新宁县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1722', '205', '岳塘区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1723', '205', '雨湖区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1724', '205', '湘乡市', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1725', '205', '韶山市', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1726', '205', '湘潭县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1727', '206', '吉首市', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1728', '206', '泸溪县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1729', '206', '凤凰县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1730', '206', '花垣县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1731', '206', '保靖县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1732', '206', '古丈县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1733', '206', '永顺县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1734', '206', '龙山县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1735', '207', '赫山区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1736', '207', '资阳区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1737', '207', '沅江市', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1738', '207', '南县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1739', '207', '桃江县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1740', '207', '安化县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1741', '208', '江华', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1742', '208', '冷水滩区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1743', '208', '零陵区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1744', '208', '祁阳县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1745', '208', '东安县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1746', '208', '双牌县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1747', '208', '道县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1748', '208', '江永县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1749', '208', '宁远县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1750', '208', '蓝山县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1751', '208', '新田县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1752', '209', '岳阳楼区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1753', '209', '君山区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1754', '209', '云溪区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1755', '209', '汨罗市', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1756', '209', '临湘市', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1757', '209', '岳阳县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1758', '209', '华容县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1759', '209', '湘阴县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1760', '209', '平江县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1761', '210', '天元区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1762', '210', '荷塘区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1763', '210', '芦淞区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1764', '210', '石峰区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1765', '210', '醴陵市', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1766', '210', '株洲县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1767', '210', '攸县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1768', '210', '茶陵县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1769', '210', '炎陵县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1770', '211', '朝阳区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1771', '211', '宽城区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1772', '211', '二道区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1773', '211', '南关区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1774', '211', '绿园区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1775', '211', '双阳区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1776', '211', '净月潭开发区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1777', '211', '高新技术开发区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1778', '211', '经济技术开发区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1779', '211', '汽车产业开发区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1780', '211', '德惠市', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1781', '211', '九台市', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1782', '211', '榆树市', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1783', '211', '农安县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1784', '212', '船营区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1785', '212', '昌邑区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1786', '212', '龙潭区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1787', '212', '丰满区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1788', '212', '蛟河市', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1789', '212', '桦甸市', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1790', '212', '舒兰市', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1791', '212', '磐石市', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1792', '212', '永吉县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1793', '213', '洮北区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1794', '213', '洮南市', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1795', '213', '大安市', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1796', '213', '镇赉县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1797', '213', '通榆县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1798', '214', '江源区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1799', '214', '八道江区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1800', '214', '长白', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1801', '214', '临江市', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1802', '214', '抚松县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1803', '214', '靖宇县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1804', '215', '龙山区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1805', '215', '西安区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1806', '215', '东丰县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1807', '215', '东辽县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1808', '216', '铁西区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1809', '216', '铁东区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1810', '216', '伊通', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1811', '216', '公主岭市', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1812', '216', '双辽市', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1813', '216', '梨树县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1814', '217', '前郭尔罗斯', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1815', '217', '宁江区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1816', '217', '长岭县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1817', '217', '乾安县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1818', '217', '扶余县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1819', '218', '东昌区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1820', '218', '二道江区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1821', '218', '梅河口市', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1822', '218', '集安市', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1823', '218', '通化县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1824', '218', '辉南县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1825', '218', '柳河县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1826', '219', '延吉市', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1827', '219', '图们市', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1828', '219', '敦化市', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1829', '219', '珲春市', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1830', '219', '龙井市', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1831', '219', '和龙市', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1832', '219', '安图县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1833', '219', '汪清县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1834', '220', '玄武区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1835', '220', '鼓楼区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1836', '220', '白下区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1837', '220', '建邺区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1838', '220', '秦淮区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1839', '220', '雨花台区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1840', '220', '下关区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1841', '220', '栖霞区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1842', '220', '浦口区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1843', '220', '江宁区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1844', '220', '六合区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1845', '220', '溧水县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1846', '220', '高淳县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1847', '221', '沧浪区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1848', '221', '金阊区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1849', '221', '平江区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1850', '221', '虎丘区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1851', '221', '吴中区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1852', '221', '相城区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1853', '221', '园区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1854', '221', '新区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1855', '221', '常熟市', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1856', '221', '张家港市', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1857', '221', '玉山镇', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1858', '221', '巴城镇', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1859', '221', '周市镇', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1860', '221', '陆家镇', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1861', '221', '花桥镇', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1862', '221', '淀山湖镇', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1863', '221', '张浦镇', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1864', '221', '周庄镇', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1865', '221', '千灯镇', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1866', '221', '锦溪镇', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1867', '221', '开发区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1868', '221', '吴江市', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1869', '221', '太仓市', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1870', '222', '崇安区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1871', '222', '北塘区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1872', '222', '南长区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1873', '222', '锡山区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1874', '222', '惠山区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1875', '222', '滨湖区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1876', '222', '新区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1877', '222', '江阴市', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1878', '222', '宜兴市', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1879', '223', '天宁区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1880', '223', '钟楼区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1881', '223', '戚墅堰区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1882', '223', '郊区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1883', '223', '新北区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1884', '223', '武进区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1885', '223', '溧阳市', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1886', '223', '金坛市', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1887', '224', '清河区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1888', '224', '清浦区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1889', '224', '楚州区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1890', '224', '淮阴区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1891', '224', '涟水县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1892', '224', '洪泽县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1893', '224', '盱眙县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1894', '224', '金湖县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1895', '225', '新浦区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1896', '225', '连云区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1897', '225', '海州区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1898', '225', '赣榆县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1899', '225', '东海县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1900', '225', '灌云县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1901', '225', '灌南县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1902', '226', '崇川区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1903', '226', '港闸区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1904', '226', '经济开发区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1905', '226', '启东市', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1906', '226', '如皋市', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1907', '226', '通州市', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1908', '226', '海门市', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1909', '226', '海安县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1910', '226', '如东县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1911', '227', '宿城区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1912', '227', '宿豫区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1913', '227', '宿豫县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1914', '227', '沭阳县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1915', '227', '泗阳县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1916', '227', '泗洪县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1917', '228', '海陵区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1918', '228', '高港区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1919', '228', '兴化市', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1920', '228', '靖江市', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1921', '228', '泰兴市', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1922', '228', '姜堰市', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1923', '229', '云龙区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1924', '229', '鼓楼区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1925', '229', '九里区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1926', '229', '贾汪区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1927', '229', '泉山区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1928', '229', '新沂市', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1929', '229', '邳州市', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1930', '229', '丰县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1931', '229', '沛县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1932', '229', '铜山县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1933', '229', '睢宁县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1934', '230', '城区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1935', '230', '亭湖区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1936', '230', '盐都区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1937', '230', '盐都县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1938', '230', '东台市', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1939', '230', '大丰市', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1940', '230', '响水县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1941', '230', '滨海县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1942', '230', '阜宁县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1943', '230', '射阳县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1944', '230', '建湖县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1945', '231', '广陵区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1946', '231', '维扬区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1947', '231', '邗江区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1948', '231', '仪征市', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1949', '231', '高邮市', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1950', '231', '江都市', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1951', '231', '宝应县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1952', '232', '京口区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1953', '232', '润州区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1954', '232', '丹徒区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1955', '232', '丹阳市', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1956', '232', '扬中市', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1957', '232', '句容市', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1958', '233', '东湖区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1959', '233', '西湖区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1960', '233', '青云谱区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1961', '233', '湾里区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1962', '233', '青山湖区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1963', '233', '红谷滩新区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1964', '233', '昌北区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1965', '233', '高新区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1966', '233', '南昌县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1967', '233', '新建县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1968', '233', '安义县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1969', '233', '进贤县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1970', '234', '临川区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1971', '234', '南城县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1972', '234', '黎川县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1973', '234', '南丰县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1974', '234', '崇仁县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1975', '234', '乐安县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1976', '234', '宜黄县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1977', '234', '金溪县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1978', '234', '资溪县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1979', '234', '东乡县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1980', '234', '广昌县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1981', '235', '章贡区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1982', '235', '于都县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1983', '235', '瑞金市', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1984', '235', '南康市', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1985', '235', '赣县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1986', '235', '信丰县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1987', '235', '大余县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1988', '235', '上犹县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1989', '235', '崇义县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1990', '235', '安远县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1991', '235', '龙南县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1992', '235', '定南县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1993', '235', '全南县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1994', '235', '宁都县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1995', '235', '兴国县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1996', '235', '会昌县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1997', '235', '寻乌县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1998', '235', '石城县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('1999', '236', '安福县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('2000', '236', '吉州区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('2001', '236', '青原区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('2002', '236', '井冈山市', '3', '0', '0');
INSERT INTO `db_area` VALUES ('2003', '236', '吉安县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('2004', '236', '吉水县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('2005', '236', '峡江县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('2006', '236', '新干县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('2007', '236', '永丰县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('2008', '236', '泰和县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('2009', '236', '遂川县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('2010', '236', '万安县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('2011', '236', '永新县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('2012', '237', '珠山区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('2013', '237', '昌江区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('2014', '237', '乐平市', '3', '0', '0');
INSERT INTO `db_area` VALUES ('2015', '237', '浮梁县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('2016', '238', '浔阳区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('2017', '238', '庐山区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('2018', '238', '瑞昌市', '3', '0', '0');
INSERT INTO `db_area` VALUES ('2019', '238', '九江县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('2020', '238', '武宁县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('2021', '238', '修水县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('2022', '238', '永修县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('2023', '238', '德安县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('2024', '238', '星子县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('2025', '238', '都昌县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('2026', '238', '湖口县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('2027', '238', '彭泽县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('2028', '239', '安源区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('2029', '239', '湘东区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('2030', '239', '莲花县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('2031', '239', '芦溪县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('2032', '239', '上栗县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('2033', '240', '信州区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('2034', '240', '德兴市', '3', '0', '0');
INSERT INTO `db_area` VALUES ('2035', '240', '上饶县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('2036', '240', '广丰县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('2037', '240', '玉山县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('2038', '240', '铅山县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('2039', '240', '横峰县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('2040', '240', '弋阳县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('2041', '240', '余干县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('2042', '240', '波阳县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('2043', '240', '万年县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('2044', '240', '婺源县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('2045', '241', '渝水区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('2046', '241', '分宜县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('2047', '242', '袁州区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('2048', '242', '丰城市', '3', '0', '0');
INSERT INTO `db_area` VALUES ('2049', '242', '樟树市', '3', '0', '0');
INSERT INTO `db_area` VALUES ('2050', '242', '高安市', '3', '0', '0');
INSERT INTO `db_area` VALUES ('2051', '242', '奉新县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('2052', '242', '万载县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('2053', '242', '上高县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('2054', '242', '宜丰县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('2055', '242', '靖安县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('2056', '242', '铜鼓县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('2057', '243', '月湖区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('2058', '243', '贵溪市', '3', '0', '0');
INSERT INTO `db_area` VALUES ('2059', '243', '余江县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('2060', '244', '沈河区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('2061', '244', '皇姑区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('2062', '244', '和平区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('2063', '244', '大东区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('2064', '244', '铁西区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('2065', '244', '苏家屯区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('2066', '244', '东陵区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('2067', '244', '沈北新区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('2068', '244', '于洪区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('2069', '244', '浑南新区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('2070', '244', '新民市', '3', '0', '0');
INSERT INTO `db_area` VALUES ('2071', '244', '辽中县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('2072', '244', '康平县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('2073', '244', '法库县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('2074', '245', '西岗区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('2075', '245', '中山区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('2076', '245', '沙河口区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('2077', '245', '甘井子区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('2078', '245', '旅顺口区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('2079', '245', '金州区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('2080', '245', '开发区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('2081', '245', '瓦房店市', '3', '0', '0');
INSERT INTO `db_area` VALUES ('2082', '245', '普兰店市', '3', '0', '0');
INSERT INTO `db_area` VALUES ('2083', '245', '庄河市', '3', '0', '0');
INSERT INTO `db_area` VALUES ('2084', '245', '长海县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('2085', '246', '铁东区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('2086', '246', '铁西区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('2087', '246', '立山区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('2088', '246', '千山区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('2089', '246', '岫岩', '3', '0', '0');
INSERT INTO `db_area` VALUES ('2090', '246', '海城市', '3', '0', '0');
INSERT INTO `db_area` VALUES ('2091', '246', '台安县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('2092', '247', '本溪', '3', '0', '0');
INSERT INTO `db_area` VALUES ('2093', '247', '平山区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('2094', '247', '明山区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('2095', '247', '溪湖区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('2096', '247', '南芬区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('2097', '247', '桓仁', '3', '0', '0');
INSERT INTO `db_area` VALUES ('2098', '248', '双塔区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('2099', '248', '龙城区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('2100', '248', '喀喇沁左翼蒙古族自治县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('2101', '248', '北票市', '3', '0', '0');
INSERT INTO `db_area` VALUES ('2102', '248', '凌源市', '3', '0', '0');
INSERT INTO `db_area` VALUES ('2103', '248', '朝阳县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('2104', '248', '建平县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('2105', '249', '振兴区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('2106', '249', '元宝区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('2107', '249', '振安区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('2108', '249', '宽甸', '3', '0', '0');
INSERT INTO `db_area` VALUES ('2109', '249', '东港市', '3', '0', '0');
INSERT INTO `db_area` VALUES ('2110', '249', '凤城市', '3', '0', '0');
INSERT INTO `db_area` VALUES ('2111', '250', '顺城区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('2112', '250', '新抚区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('2113', '250', '东洲区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('2114', '250', '望花区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('2115', '250', '清原', '3', '0', '0');
INSERT INTO `db_area` VALUES ('2116', '250', '新宾', '3', '0', '0');
INSERT INTO `db_area` VALUES ('2117', '250', '抚顺县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('2118', '251', '阜新', '3', '0', '0');
INSERT INTO `db_area` VALUES ('2119', '251', '海州区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('2120', '251', '新邱区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('2121', '251', '太平区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('2122', '251', '清河门区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('2123', '251', '细河区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('2124', '251', '彰武县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('2125', '252', '龙港区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('2126', '252', '南票区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('2127', '252', '连山区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('2128', '252', '兴城市', '3', '0', '0');
INSERT INTO `db_area` VALUES ('2129', '252', '绥中县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('2130', '252', '建昌县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('2131', '253', '太和区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('2132', '253', '古塔区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('2133', '253', '凌河区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('2134', '253', '凌海市', '3', '0', '0');
INSERT INTO `db_area` VALUES ('2135', '253', '北镇市', '3', '0', '0');
INSERT INTO `db_area` VALUES ('2136', '253', '黑山县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('2137', '253', '义县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('2138', '254', '白塔区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('2139', '254', '文圣区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('2140', '254', '宏伟区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('2141', '254', '太子河区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('2142', '254', '弓长岭区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('2143', '254', '灯塔市', '3', '0', '0');
INSERT INTO `db_area` VALUES ('2144', '254', '辽阳县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('2145', '255', '双台子区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('2146', '255', '兴隆台区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('2147', '255', '大洼县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('2148', '255', '盘山县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('2149', '256', '银州区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('2150', '256', '清河区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('2151', '256', '调兵山市', '3', '0', '0');
INSERT INTO `db_area` VALUES ('2152', '256', '开原市', '3', '0', '0');
INSERT INTO `db_area` VALUES ('2153', '256', '铁岭县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('2154', '256', '西丰县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('2155', '256', '昌图县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('2156', '257', '站前区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('2157', '257', '西市区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('2158', '257', '鲅鱼圈区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('2159', '257', '老边区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('2160', '257', '盖州市', '3', '0', '0');
INSERT INTO `db_area` VALUES ('2161', '257', '大石桥市', '3', '0', '0');
INSERT INTO `db_area` VALUES ('2162', '258', '回民区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('2163', '258', '玉泉区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('2164', '258', '新城区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('2165', '258', '赛罕区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('2166', '258', '清水河县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('2167', '258', '土默特左旗', '3', '0', '0');
INSERT INTO `db_area` VALUES ('2168', '258', '托克托县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('2169', '258', '和林格尔县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('2170', '258', '武川县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('2171', '259', '阿拉善左旗', '3', '0', '0');
INSERT INTO `db_area` VALUES ('2172', '259', '阿拉善右旗', '3', '0', '0');
INSERT INTO `db_area` VALUES ('2173', '259', '额济纳旗', '3', '0', '0');
INSERT INTO `db_area` VALUES ('2174', '260', '临河区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('2175', '260', '五原县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('2176', '260', '磴口县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('2177', '260', '乌拉特前旗', '3', '0', '0');
INSERT INTO `db_area` VALUES ('2178', '260', '乌拉特中旗', '3', '0', '0');
INSERT INTO `db_area` VALUES ('2179', '260', '乌拉特后旗', '3', '0', '0');
INSERT INTO `db_area` VALUES ('2180', '260', '杭锦后旗', '3', '0', '0');
INSERT INTO `db_area` VALUES ('2181', '261', '昆都仑区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('2182', '261', '青山区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('2183', '261', '东河区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('2184', '261', '九原区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('2185', '261', '石拐区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('2186', '261', '白云矿区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('2187', '261', '土默特右旗', '3', '0', '0');
INSERT INTO `db_area` VALUES ('2188', '261', '固阳县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('2189', '261', '达尔罕茂明安联合旗', '3', '0', '0');
INSERT INTO `db_area` VALUES ('2190', '262', '红山区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('2191', '262', '元宝山区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('2192', '262', '松山区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('2193', '262', '阿鲁科尔沁旗', '3', '0', '0');
INSERT INTO `db_area` VALUES ('2194', '262', '巴林左旗', '3', '0', '0');
INSERT INTO `db_area` VALUES ('2195', '262', '巴林右旗', '3', '0', '0');
INSERT INTO `db_area` VALUES ('2196', '262', '林西县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('2197', '262', '克什克腾旗', '3', '0', '0');
INSERT INTO `db_area` VALUES ('2198', '262', '翁牛特旗', '3', '0', '0');
INSERT INTO `db_area` VALUES ('2199', '262', '喀喇沁旗', '3', '0', '0');
INSERT INTO `db_area` VALUES ('2200', '262', '宁城县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('2201', '262', '敖汉旗', '3', '0', '0');
INSERT INTO `db_area` VALUES ('2202', '263', '东胜区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('2203', '263', '达拉特旗', '3', '0', '0');
INSERT INTO `db_area` VALUES ('2204', '263', '准格尔旗', '3', '0', '0');
INSERT INTO `db_area` VALUES ('2205', '263', '鄂托克前旗', '3', '0', '0');
INSERT INTO `db_area` VALUES ('2206', '263', '鄂托克旗', '3', '0', '0');
INSERT INTO `db_area` VALUES ('2207', '263', '杭锦旗', '3', '0', '0');
INSERT INTO `db_area` VALUES ('2208', '263', '乌审旗', '3', '0', '0');
INSERT INTO `db_area` VALUES ('2209', '263', '伊金霍洛旗', '3', '0', '0');
INSERT INTO `db_area` VALUES ('2210', '264', '海拉尔区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('2211', '264', '莫力达瓦', '3', '0', '0');
INSERT INTO `db_area` VALUES ('2212', '264', '满洲里市', '3', '0', '0');
INSERT INTO `db_area` VALUES ('2213', '264', '牙克石市', '3', '0', '0');
INSERT INTO `db_area` VALUES ('2214', '264', '扎兰屯市', '3', '0', '0');
INSERT INTO `db_area` VALUES ('2215', '264', '额尔古纳市', '3', '0', '0');
INSERT INTO `db_area` VALUES ('2216', '264', '根河市', '3', '0', '0');
INSERT INTO `db_area` VALUES ('2217', '264', '阿荣旗', '3', '0', '0');
INSERT INTO `db_area` VALUES ('2218', '264', '鄂伦春自治旗', '3', '0', '0');
INSERT INTO `db_area` VALUES ('2219', '264', '鄂温克族自治旗', '3', '0', '0');
INSERT INTO `db_area` VALUES ('2220', '264', '陈巴尔虎旗', '3', '0', '0');
INSERT INTO `db_area` VALUES ('2221', '264', '新巴尔虎左旗', '3', '0', '0');
INSERT INTO `db_area` VALUES ('2222', '264', '新巴尔虎右旗', '3', '0', '0');
INSERT INTO `db_area` VALUES ('2223', '265', '科尔沁区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('2224', '265', '霍林郭勒市', '3', '0', '0');
INSERT INTO `db_area` VALUES ('2225', '265', '科尔沁左翼中旗', '3', '0', '0');
INSERT INTO `db_area` VALUES ('2226', '265', '科尔沁左翼后旗', '3', '0', '0');
INSERT INTO `db_area` VALUES ('2227', '265', '开鲁县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('2228', '265', '库伦旗', '3', '0', '0');
INSERT INTO `db_area` VALUES ('2229', '265', '奈曼旗', '3', '0', '0');
INSERT INTO `db_area` VALUES ('2230', '265', '扎鲁特旗', '3', '0', '0');
INSERT INTO `db_area` VALUES ('2231', '266', '海勃湾区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('2232', '266', '乌达区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('2233', '266', '海南区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('2234', '267', '化德县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('2235', '267', '集宁区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('2236', '267', '丰镇市', '3', '0', '0');
INSERT INTO `db_area` VALUES ('2237', '267', '卓资县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('2238', '267', '商都县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('2239', '267', '兴和县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('2240', '267', '凉城县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('2241', '267', '察哈尔右翼前旗', '3', '0', '0');
INSERT INTO `db_area` VALUES ('2242', '267', '察哈尔右翼中旗', '3', '0', '0');
INSERT INTO `db_area` VALUES ('2243', '267', '察哈尔右翼后旗', '3', '0', '0');
INSERT INTO `db_area` VALUES ('2244', '267', '四子王旗', '3', '0', '0');
INSERT INTO `db_area` VALUES ('2245', '268', '二连浩特市', '3', '0', '0');
INSERT INTO `db_area` VALUES ('2246', '268', '锡林浩特市', '3', '0', '0');
INSERT INTO `db_area` VALUES ('2247', '268', '阿巴嘎旗', '3', '0', '0');
INSERT INTO `db_area` VALUES ('2248', '268', '苏尼特左旗', '3', '0', '0');
INSERT INTO `db_area` VALUES ('2249', '268', '苏尼特右旗', '3', '0', '0');
INSERT INTO `db_area` VALUES ('2250', '268', '东乌珠穆沁旗', '3', '0', '0');
INSERT INTO `db_area` VALUES ('2251', '268', '西乌珠穆沁旗', '3', '0', '0');
INSERT INTO `db_area` VALUES ('2252', '268', '太仆寺旗', '3', '0', '0');
INSERT INTO `db_area` VALUES ('2253', '268', '镶黄旗', '3', '0', '0');
INSERT INTO `db_area` VALUES ('2254', '268', '正镶白旗', '3', '0', '0');
INSERT INTO `db_area` VALUES ('2255', '268', '正蓝旗', '3', '0', '0');
INSERT INTO `db_area` VALUES ('2256', '268', '多伦县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('2257', '269', '乌兰浩特市', '3', '0', '0');
INSERT INTO `db_area` VALUES ('2258', '269', '阿尔山市', '3', '0', '0');
INSERT INTO `db_area` VALUES ('2259', '269', '科尔沁右翼前旗', '3', '0', '0');
INSERT INTO `db_area` VALUES ('2260', '269', '科尔沁右翼中旗', '3', '0', '0');
INSERT INTO `db_area` VALUES ('2261', '269', '扎赉特旗', '3', '0', '0');
INSERT INTO `db_area` VALUES ('2262', '269', '突泉县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('2263', '270', '西夏区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('2264', '270', '金凤区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('2265', '270', '兴庆区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('2266', '270', '灵武市', '3', '0', '0');
INSERT INTO `db_area` VALUES ('2267', '270', '永宁县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('2268', '270', '贺兰县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('2269', '271', '原州区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('2270', '271', '海原县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('2271', '271', '西吉县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('2272', '271', '隆德县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('2273', '271', '泾源县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('2274', '271', '彭阳县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('2275', '272', '惠农县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('2276', '272', '大武口区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('2277', '272', '惠农区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('2278', '272', '陶乐县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('2279', '272', '平罗县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('2280', '273', '利通区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('2281', '273', '中卫县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('2282', '273', '青铜峡市', '3', '0', '0');
INSERT INTO `db_area` VALUES ('2283', '273', '中宁县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('2284', '273', '盐池县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('2285', '273', '同心县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('2286', '274', '沙坡头区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('2287', '274', '海原县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('2288', '274', '中宁县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('2289', '275', '城中区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('2290', '275', '城东区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('2291', '275', '城西区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('2292', '275', '城北区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('2293', '275', '湟中县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('2294', '275', '湟源县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('2295', '275', '大通', '3', '0', '0');
INSERT INTO `db_area` VALUES ('2296', '276', '玛沁县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('2297', '276', '班玛县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('2298', '276', '甘德县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('2299', '276', '达日县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('2300', '276', '久治县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('2301', '276', '玛多县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('2302', '277', '海晏县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('2303', '277', '祁连县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('2304', '277', '刚察县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('2305', '277', '门源', '3', '0', '0');
INSERT INTO `db_area` VALUES ('2306', '278', '平安县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('2307', '278', '乐都县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('2308', '278', '民和', '3', '0', '0');
INSERT INTO `db_area` VALUES ('2309', '278', '互助', '3', '0', '0');
INSERT INTO `db_area` VALUES ('2310', '278', '化隆', '3', '0', '0');
INSERT INTO `db_area` VALUES ('2311', '278', '循化', '3', '0', '0');
INSERT INTO `db_area` VALUES ('2312', '279', '共和县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('2313', '279', '同德县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('2314', '279', '贵德县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('2315', '279', '兴海县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('2316', '279', '贵南县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('2317', '280', '德令哈市', '3', '0', '0');
INSERT INTO `db_area` VALUES ('2318', '280', '格尔木市', '3', '0', '0');
INSERT INTO `db_area` VALUES ('2319', '280', '乌兰县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('2320', '280', '都兰县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('2321', '280', '天峻县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('2322', '281', '同仁县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('2323', '281', '尖扎县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('2324', '281', '泽库县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('2325', '281', '河南蒙古族自治县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('2326', '282', '玉树县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('2327', '282', '杂多县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('2328', '282', '称多县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('2329', '282', '治多县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('2330', '282', '囊谦县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('2331', '282', '曲麻莱县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('2332', '283', '市中区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('2333', '283', '历下区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('2334', '283', '天桥区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('2335', '283', '槐荫区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('2336', '283', '历城区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('2337', '283', '长清区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('2338', '283', '章丘市', '3', '0', '0');
INSERT INTO `db_area` VALUES ('2339', '283', '平阴县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('2340', '283', '济阳县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('2341', '283', '商河县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('2342', '284', '市南区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('2343', '284', '市北区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('2344', '284', '城阳区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('2345', '284', '四方区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('2346', '284', '李沧区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('2347', '284', '黄岛区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('2348', '284', '崂山区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('2349', '284', '胶州市', '3', '0', '0');
INSERT INTO `db_area` VALUES ('2350', '284', '即墨市', '3', '0', '0');
INSERT INTO `db_area` VALUES ('2351', '284', '平度市', '3', '0', '0');
INSERT INTO `db_area` VALUES ('2352', '284', '胶南市', '3', '0', '0');
INSERT INTO `db_area` VALUES ('2353', '284', '莱西市', '3', '0', '0');
INSERT INTO `db_area` VALUES ('2354', '285', '滨城区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('2355', '285', '惠民县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('2356', '285', '阳信县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('2357', '285', '无棣县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('2358', '285', '沾化县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('2359', '285', '博兴县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('2360', '285', '邹平县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('2361', '286', '德城区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('2362', '286', '陵县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('2363', '286', '乐陵市', '3', '0', '0');
INSERT INTO `db_area` VALUES ('2364', '286', '禹城市', '3', '0', '0');
INSERT INTO `db_area` VALUES ('2365', '286', '宁津县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('2366', '286', '庆云县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('2367', '286', '临邑县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('2368', '286', '齐河县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('2369', '286', '平原县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('2370', '286', '夏津县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('2371', '286', '武城县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('2372', '287', '东营区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('2373', '287', '河口区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('2374', '287', '垦利县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('2375', '287', '利津县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('2376', '287', '广饶县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('2377', '288', '牡丹区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('2378', '288', '曹县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('2379', '288', '单县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('2380', '288', '成武县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('2381', '288', '巨野县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('2382', '288', '郓城县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('2383', '288', '鄄城县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('2384', '288', '定陶县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('2385', '288', '东明县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('2386', '289', '市中区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('2387', '289', '任城区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('2388', '289', '曲阜市', '3', '0', '0');
INSERT INTO `db_area` VALUES ('2389', '289', '兖州市', '3', '0', '0');
INSERT INTO `db_area` VALUES ('2390', '289', '邹城市', '3', '0', '0');
INSERT INTO `db_area` VALUES ('2391', '289', '微山县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('2392', '289', '鱼台县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('2393', '289', '金乡县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('2394', '289', '嘉祥县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('2395', '289', '汶上县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('2396', '289', '泗水县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('2397', '289', '梁山县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('2398', '290', '莱城区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('2399', '290', '钢城区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('2400', '291', '东昌府区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('2401', '291', '临清市', '3', '0', '0');
INSERT INTO `db_area` VALUES ('2402', '291', '阳谷县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('2403', '291', '莘县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('2404', '291', '茌平县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('2405', '291', '东阿县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('2406', '291', '冠县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('2407', '291', '高唐县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('2408', '292', '兰山区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('2409', '292', '罗庄区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('2410', '292', '河东区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('2411', '292', '沂南县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('2412', '292', '郯城县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('2413', '292', '沂水县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('2414', '292', '苍山县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('2415', '292', '费县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('2416', '292', '平邑县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('2417', '292', '莒南县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('2418', '292', '蒙阴县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('2419', '292', '临沭县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('2420', '293', '东港区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('2421', '293', '岚山区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('2422', '293', '五莲县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('2423', '293', '莒县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('2424', '294', '泰山区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('2425', '294', '岱岳区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('2426', '294', '新泰市', '3', '0', '0');
INSERT INTO `db_area` VALUES ('2427', '294', '肥城市', '3', '0', '0');
INSERT INTO `db_area` VALUES ('2428', '294', '宁阳县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('2429', '294', '东平县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('2430', '295', '荣成市', '3', '0', '0');
INSERT INTO `db_area` VALUES ('2431', '295', '乳山市', '3', '0', '0');
INSERT INTO `db_area` VALUES ('2432', '295', '环翠区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('2433', '295', '文登市', '3', '0', '0');
INSERT INTO `db_area` VALUES ('2434', '296', '潍城区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('2435', '296', '寒亭区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('2436', '296', '坊子区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('2437', '296', '奎文区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('2438', '296', '青州市', '3', '0', '0');
INSERT INTO `db_area` VALUES ('2439', '296', '诸城市', '3', '0', '0');
INSERT INTO `db_area` VALUES ('2440', '296', '寿光市', '3', '0', '0');
INSERT INTO `db_area` VALUES ('2441', '296', '安丘市', '3', '0', '0');
INSERT INTO `db_area` VALUES ('2442', '296', '高密市', '3', '0', '0');
INSERT INTO `db_area` VALUES ('2443', '296', '昌邑市', '3', '0', '0');
INSERT INTO `db_area` VALUES ('2444', '296', '临朐县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('2445', '296', '昌乐县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('2446', '297', '芝罘区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('2447', '297', '福山区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('2448', '297', '牟平区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('2449', '297', '莱山区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('2450', '297', '开发区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('2451', '297', '龙口市', '3', '0', '0');
INSERT INTO `db_area` VALUES ('2452', '297', '莱阳市', '3', '0', '0');
INSERT INTO `db_area` VALUES ('2453', '297', '莱州市', '3', '0', '0');
INSERT INTO `db_area` VALUES ('2454', '297', '蓬莱市', '3', '0', '0');
INSERT INTO `db_area` VALUES ('2455', '297', '招远市', '3', '0', '0');
INSERT INTO `db_area` VALUES ('2456', '297', '栖霞市', '3', '0', '0');
INSERT INTO `db_area` VALUES ('2457', '297', '海阳市', '3', '0', '0');
INSERT INTO `db_area` VALUES ('2458', '297', '长岛县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('2459', '298', '市中区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('2460', '298', '山亭区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('2461', '298', '峄城区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('2462', '298', '台儿庄区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('2463', '298', '薛城区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('2464', '298', '滕州市', '3', '0', '0');
INSERT INTO `db_area` VALUES ('2465', '299', '张店区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('2466', '299', '临淄区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('2467', '299', '淄川区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('2468', '299', '博山区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('2469', '299', '周村区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('2470', '299', '桓台县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('2471', '299', '高青县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('2472', '299', '沂源县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('2473', '300', '杏花岭区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('2474', '300', '小店区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('2475', '300', '迎泽区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('2476', '300', '尖草坪区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('2477', '300', '万柏林区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('2478', '300', '晋源区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('2479', '300', '高新开发区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('2480', '300', '民营经济开发区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('2481', '300', '经济技术开发区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('2482', '300', '清徐县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('2483', '300', '阳曲县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('2484', '300', '娄烦县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('2485', '300', '古交市', '3', '0', '0');
INSERT INTO `db_area` VALUES ('2486', '301', '城区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('2487', '301', '郊区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('2488', '301', '沁县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('2489', '301', '潞城市', '3', '0', '0');
INSERT INTO `db_area` VALUES ('2490', '301', '长治县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('2491', '301', '襄垣县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('2492', '301', '屯留县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('2493', '301', '平顺县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('2494', '301', '黎城县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('2495', '301', '壶关县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('2496', '301', '长子县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('2497', '301', '武乡县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('2498', '301', '沁源县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('2499', '302', '城区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('2500', '302', '矿区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('2501', '302', '南郊区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('2502', '302', '新荣区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('2503', '302', '阳高县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('2504', '302', '天镇县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('2505', '302', '广灵县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('2506', '302', '灵丘县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('2507', '302', '浑源县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('2508', '302', '左云县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('2509', '302', '大同县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('2510', '303', '城区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('2511', '303', '高平市', '3', '0', '0');
INSERT INTO `db_area` VALUES ('2512', '303', '沁水县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('2513', '303', '阳城县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('2514', '303', '陵川县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('2515', '303', '泽州县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('2516', '304', '榆次区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('2517', '304', '介休市', '3', '0', '0');
INSERT INTO `db_area` VALUES ('2518', '304', '榆社县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('2519', '304', '左权县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('2520', '304', '和顺县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('2521', '304', '昔阳县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('2522', '304', '寿阳县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('2523', '304', '太谷县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('2524', '304', '祁县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('2525', '304', '平遥县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('2526', '304', '灵石县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('2527', '305', '尧都区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('2528', '305', '侯马市', '3', '0', '0');
INSERT INTO `db_area` VALUES ('2529', '305', '霍州市', '3', '0', '0');
INSERT INTO `db_area` VALUES ('2530', '305', '曲沃县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('2531', '305', '翼城县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('2532', '305', '襄汾县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('2533', '305', '洪洞县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('2534', '305', '吉县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('2535', '305', '安泽县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('2536', '305', '浮山县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('2537', '305', '古县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('2538', '305', '乡宁县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('2539', '305', '大宁县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('2540', '305', '隰县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('2541', '305', '永和县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('2542', '305', '蒲县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('2543', '305', '汾西县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('2544', '306', '离石市', '3', '0', '0');
INSERT INTO `db_area` VALUES ('2545', '306', '离石区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('2546', '306', '孝义市', '3', '0', '0');
INSERT INTO `db_area` VALUES ('2547', '306', '汾阳市', '3', '0', '0');
INSERT INTO `db_area` VALUES ('2548', '306', '文水县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('2549', '306', '交城县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('2550', '306', '兴县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('2551', '306', '临县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('2552', '306', '柳林县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('2553', '306', '石楼县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('2554', '306', '岚县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('2555', '306', '方山县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('2556', '306', '中阳县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('2557', '306', '交口县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('2558', '307', '朔城区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('2559', '307', '平鲁区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('2560', '307', '山阴县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('2561', '307', '应县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('2562', '307', '右玉县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('2563', '307', '怀仁县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('2564', '308', '忻府区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('2565', '308', '原平市', '3', '0', '0');
INSERT INTO `db_area` VALUES ('2566', '308', '定襄县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('2567', '308', '五台县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('2568', '308', '代县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('2569', '308', '繁峙县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('2570', '308', '宁武县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('2571', '308', '静乐县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('2572', '308', '神池县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('2573', '308', '五寨县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('2574', '308', '岢岚县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('2575', '308', '河曲县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('2576', '308', '保德县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('2577', '308', '偏关县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('2578', '309', '城区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('2579', '309', '矿区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('2580', '309', '郊区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('2581', '309', '平定县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('2582', '309', '盂县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('2583', '310', '盐湖区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('2584', '310', '永济市', '3', '0', '0');
INSERT INTO `db_area` VALUES ('2585', '310', '河津市', '3', '0', '0');
INSERT INTO `db_area` VALUES ('2586', '310', '临猗县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('2587', '310', '万荣县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('2588', '310', '闻喜县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('2589', '310', '稷山县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('2590', '310', '新绛县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('2591', '310', '绛县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('2592', '310', '垣曲县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('2593', '310', '夏县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('2594', '310', '平陆县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('2595', '310', '芮城县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('2596', '311', '莲湖区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('2597', '311', '新城区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('2598', '311', '碑林区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('2599', '311', '雁塔区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('2600', '311', '灞桥区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('2601', '311', '未央区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('2602', '311', '阎良区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('2603', '311', '临潼区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('2604', '311', '长安区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('2605', '311', '蓝田县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('2606', '311', '周至县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('2607', '311', '户县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('2608', '311', '高陵县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('2609', '312', '汉滨区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('2610', '312', '汉阴县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('2611', '312', '石泉县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('2612', '312', '宁陕县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('2613', '312', '紫阳县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('2614', '312', '岚皋县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('2615', '312', '平利县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('2616', '312', '镇坪县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('2617', '312', '旬阳县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('2618', '312', '白河县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('2619', '313', '陈仓区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('2620', '313', '渭滨区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('2621', '313', '金台区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('2622', '313', '凤翔县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('2623', '313', '岐山县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('2624', '313', '扶风县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('2625', '313', '眉县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('2626', '313', '陇县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('2627', '313', '千阳县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('2628', '313', '麟游县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('2629', '313', '凤县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('2630', '313', '太白县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('2631', '314', '汉台区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('2632', '314', '南郑县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('2633', '314', '城固县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('2634', '314', '洋县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('2635', '314', '西乡县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('2636', '314', '勉县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('2637', '314', '宁强县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('2638', '314', '略阳县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('2639', '314', '镇巴县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('2640', '314', '留坝县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('2641', '314', '佛坪县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('2642', '315', '商州区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('2643', '315', '洛南县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('2644', '315', '丹凤县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('2645', '315', '商南县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('2646', '315', '山阳县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('2647', '315', '镇安县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('2648', '315', '柞水县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('2649', '316', '耀州区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('2650', '316', '王益区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('2651', '316', '印台区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('2652', '316', '宜君县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('2653', '317', '临渭区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('2654', '317', '韩城市', '3', '0', '0');
INSERT INTO `db_area` VALUES ('2655', '317', '华阴市', '3', '0', '0');
INSERT INTO `db_area` VALUES ('2656', '317', '华县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('2657', '317', '潼关县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('2658', '317', '大荔县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('2659', '317', '合阳县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('2660', '317', '澄城县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('2661', '317', '蒲城县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('2662', '317', '白水县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('2663', '317', '富平县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('2664', '318', '秦都区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('2665', '318', '渭城区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('2666', '318', '杨陵区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('2667', '318', '兴平市', '3', '0', '0');
INSERT INTO `db_area` VALUES ('2668', '318', '三原县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('2669', '318', '泾阳县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('2670', '318', '乾县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('2671', '318', '礼泉县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('2672', '318', '永寿县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('2673', '318', '彬县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('2674', '318', '长武县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('2675', '318', '旬邑县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('2676', '318', '淳化县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('2677', '318', '武功县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('2678', '319', '吴起县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('2679', '319', '宝塔区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('2680', '319', '延长县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('2681', '319', '延川县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('2682', '319', '子长县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('2683', '319', '安塞县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('2684', '319', '志丹县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('2685', '319', '甘泉县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('2686', '319', '富县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('2687', '319', '洛川县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('2688', '319', '宜川县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('2689', '319', '黄龙县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('2690', '319', '黄陵县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('2691', '320', '榆阳区', '3', '0', '0');
INSERT INTO `db_area` VALUES ('2692', '320', '神木县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('2693', '320', '府谷县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('2694', '320', '横山县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('2695', '320', '靖边县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('2696', '320', '定边县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('2697', '320', '绥德县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('2698', '320', '米脂县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('2699', '320', '佳县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('2700', '320', '吴堡县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('2701', '320', '清涧县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('2702', '320', '子洲县', '3', '0', '0');
INSERT INTO `db_area` VALUES ('2703', '25', '长宁区', '2', '0', '0');
INSERT INTO `db_lang` VALUES ('1', '中文简体', 'cn', '', '0', '', '', '120');
INSERT INTO `db_lang` VALUES ('2', 'English', 'en', '', '0', '', '', '110');
INSERT INTO `db_site` VALUES ('1', '1', 'SITE_URL', '网站网址', 'http://www.qiye.com', 'cn', 'text', '', '', '1464156070');
INSERT INTO `db_site` VALUES ('2', '1', 'SITE_URL', '网站网址', 'http://www.qiye.com', 'en', 'text', '', '', '1464156070');
INSERT INTO `db_site` VALUES ('3', '1', 'SITE_NAME', '网站名称', '网站管理系统', 'cn', 'text', '', '', '1464156096');
INSERT INTO `db_site` VALUES ('4', '1', 'SITE_NAME', '网站名称', 'CMS', 'en', 'text', '', '', '1464156096');
INSERT INTO `db_site` VALUES ('5', '1', 'SITE_LOGO', '网站LOGO', '/Public/images/logo.png', 'cn', 'file', '', '', '1464156115');
INSERT INTO `db_site` VALUES ('6', '1', 'SITE_LOGO', '网站LOGO', '/Public/images/logo.png', 'en', 'file', '', '', '1464156115');
INSERT INTO `db_site` VALUES ('7', '1', 'SITE_TITLE', '网站标题', '网站管理系统标题', 'cn', 'text', '', '', '1464156129');
INSERT INTO `db_site` VALUES ('8', '1', 'SITE_TITLE', '网站标题', 'SITE CMS', 'en', 'text', '', '', '1464156129');
INSERT INTO `db_site` VALUES ('9', '1', 'SITE_KEYWORDS', '站点关键词', '', 'cn', 'text', '', '', '1464156145');
INSERT INTO `db_site` VALUES ('10', '1', 'SITE_KEYWORDS', '站点关键词', '', 'en', 'text', '', '', '1464156145');
INSERT INTO `db_site` VALUES ('11', '1', 'SITE_DESCRIPTION', '站点描述', '', 'cn', 'textarea', '', '', '1464156164');
INSERT INTO `db_site` VALUES ('12', '1', 'SITE_DESCRIPTION', '站点描述', '', 'en', 'textarea', '', '', '1464156164');
INSERT INTO `db_site` VALUES ('13', '3', 'DEFAULT_THEME', '默认模板', 'default', 'cn', 'text', '', '', '1465283836');
INSERT INTO `db_site` VALUES ('14', '3', 'DEFAULT_THEME', '默认模板', 'default', 'en', 'text', '', '', '1465283836');
INSERT INTO `db_site` VALUES ('15', '3', 'KEFU_OPEN', '是否开启客服', '1', 'en', 'radio', '', '1|开启,2|关闭', '1469003772');
INSERT INTO `db_site` VALUES ('16', '3', 'KEFU_OPEN', '是否开启客服', '1', 'cn', 'radio', '', '1|开启,2|关闭', '1469003772');
INSERT INTO `db_site` VALUES ('17', '3', 'PAGE_LISTROWS', '默认分页数', '20', 'cn', 'text', '', '', '1470368689');
INSERT INTO `db_site` VALUES ('18', '3', 'PAGE_LISTROWS', '默认分页数', '20', 'en', 'text', '', '', '1470368689');
INSERT INTO `db_site` VALUES ('19', '3', 'DEFAULT_LANG', '默认语言', 'cn', 'cn', 'text', '语言标识', '', '1470384841');
INSERT INTO `db_site` VALUES ('20', '3', 'DEFAULT_LANG', '默认语言', 'cn', 'en', 'text', '语言标识', '', '1470384841');
INSERT INTO `db_site` VALUES ('21', '3', 'LANG_SWITCH_ON', '开启多语言', '1', 'cn', 'select', '', '1|开启,0|关闭', '1470389633');
INSERT INTO `db_site` VALUES ('22', '3', 'LANG_SWITCH_ON', '开启多语言', '1', 'en', 'select', '', '1|开启,0|关闭', '1470389633');
INSERT INTO `db_site` VALUES ('23', '3', 'DOMAIN_SWITCH_ON', '域名自动切换', '1', 'cn', 'select', '', '1|开启,0|关闭', '1470390443');
INSERT INTO `db_site` VALUES ('24', '3', 'DOMAIN_SWITCH_ON', '域名自动切换', '1', 'en', 'select', '', '1|开启,0|关闭', '1470390443');
INSERT INTO `db_site` VALUES ('25', '3', 'BROWSER_SWITCH_ON', '浏览器识别', '0', 'cn', 'select', '开启后域名自动切换失效，会根据浏览器语言识别', '1|开启,0|关闭', '1470390653');
INSERT INTO `db_site` VALUES ('26', '3', 'BROWSER_SWITCH_ON', '浏览器识别', '0', 'en', 'select', '开启后域名自动切换失效，会根据浏览器语言识别', '1|开启,0|关闭', '1470390653');
INSERT INTO `db_site` VALUES ('27', '3', 'URL_MODEL', 'URL模式', '0', 'cn', 'select', '重写模式下需要配置url重写规则,并且服务器需要开启伪静态设置。', '0|普通模式,1|PATHINFO模式,2|REWRITE模式,3|兼容模式', '1471508507');
INSERT INTO `db_site` VALUES ('28', '3', 'URL_MODEL', 'URL模式', '0', 'en', 'select', '重写模式下需要配置url重写规则,并且服务器需要开启伪静态设置。', '0|普通模式,1|PATHINFO模式,2|REWRITE模式,3|兼容模式', '1471508507');
