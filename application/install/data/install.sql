/*
Navicat MySQL Data Transfer

Source Server         : igeekspace
Source Server Version : 50628
Source Host           : 112.74.81.72:3306
Source Database       : twothink

Target Server Type    : MYSQL
Target Server Version : 50628
File Encoding         : 65001

Date: 2017-02-05 14:17:43
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for geek_action
-- ----------------------------
DROP TABLE IF EXISTS `geek_action`;
CREATE TABLE `geek_action` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT '' COMMENT '权限名称',
  `moudle` varchar(255) DEFAULT '' COMMENT '模块',
  `controller` varchar(255) DEFAULT '' COMMENT '控制器',
  `function` varchar(255) DEFAULT '' COMMENT '方法',
  `code` varchar(255) DEFAULT '' COMMENT '代码',
  `remark` text COMMENT '权限说明',
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=122 DEFAULT CHARSET=utf8mb4 COMMENT='用户操作表';

-- ----------------------------
-- Records of geek_action
-- ----------------------------
INSERT INTO `geek_action` (`id`, `name`, `moudle`, `controller`, `function`, `code`, `remark`, `created_at`, `updated_at`) VALUES ('3', '操作管理', 'admin', 'Privilege', '*', 'Privilege', '1', '2017-02-09 09:45:48', '2017-02-09 09:45:52');
INSERT INTO `geek_action` (`id`, `name`, `moudle`, `controller`, `function`, `code`, `remark`, `created_at`, `updated_at`) VALUES ('4', '菜单管理-浏览', 'admin', 'Menu', 'index', 'admin.Menu.index', '2', '2017-02-06 08:55:54', '2017-02-06 08:55:54');
INSERT INTO `geek_action` (`id`, `name`, `moudle`, `controller`, `function`, `code`, `remark`, `created_at`, `updated_at`) VALUES ('5', '系统管理', 'admin', 'System', '*', 'System', '1', '2017-02-06 08:58:15', '2017-02-06 08:58:15');
INSERT INTO `geek_action` (`id`, `name`, `moudle`, `controller`, `function`, `code`, `remark`, `created_at`, `updated_at`) VALUES ('6', '系统配置-浏览', 'admin', 'System', 'index', 'admin.System.index', '', '2017-02-06 08:56:00', '2017-02-06 08:56:00');
INSERT INTO `geek_action` (`id`, `name`, `moudle`, `controller`, `function`, `code`, `remark`, `created_at`, `updated_at`) VALUES ('7', '权限管理-浏览', 'admin', 'Privilege', 'index', 'admin.Privilege.index', NULL, '2017-02-06 08:56:10', '2017-02-06 08:56:10');
INSERT INTO `geek_action` (`id`, `name`, `moudle`, `controller`, `function`, `code`, `remark`, `created_at`, `updated_at`) VALUES ('9', '用户组管理-浏览', 'admin', 'AdminRole', 'index', 'admin.AdminRole.index', NULL, '2017-02-07 11:33:59', '2017-02-07 11:33:59');
INSERT INTO `geek_action` (`id`, `name`, `moudle`, `controller`, `function`, `code`, `remark`, `created_at`, `updated_at`) VALUES ('10', '菜单管理', 'admin', 'Menu', '*', 'Menu', '2', '2017-02-06 08:58:22', '2017-02-06 08:58:22');
INSERT INTO `geek_action` (`id`, `name`, `moudle`, `controller`, `function`, `code`, `remark`, `created_at`, `updated_at`) VALUES ('11', '用户组管理', 'admin', 'Adminrole', '*', 'Adminrole', '', '2017-02-06 08:58:26', '2017-02-06 08:58:26');
INSERT INTO `geek_action` (`id`, `name`, `moudle`, `controller`, `function`, `code`, `remark`, `created_at`, `updated_at`) VALUES ('12', '系统设置-编辑', 'admin', 'System', 'config', 'admin.System.config', '', '2017-02-06 09:45:41', '2017-02-06 09:45:43');
INSERT INTO `geek_action` (`id`, `name`, `moudle`, `controller`, `function`, `code`, `remark`, `created_at`, `updated_at`) VALUES ('13', '后台用户列表-浏览', 'admin', 'UcenterAdmin', 'index', 'admin.UcenterAdmin.index', '', '2017-02-07 11:48:37', '2017-02-07 11:48:37');
INSERT INTO `geek_action` (`id`, `name`, `moudle`, `controller`, `function`, `code`, `remark`, `created_at`, `updated_at`) VALUES ('14', '后台用户管理', 'admin', 'UcenterAdmin', '*', 'UcenterAdmin', '', '2017-02-07 11:59:37', '2017-02-07 11:59:37');
INSERT INTO `geek_action` (`id`, `name`, `moudle`, `controller`, `function`, `code`, `remark`, `created_at`, `updated_at`) VALUES ('15', '前台用户管理', 'admin', 'UcenterMember', '*', 'admin.UcenterMember', '', '2017-02-07 11:48:50', '2017-02-07 11:48:50');
INSERT INTO `geek_action` (`id`, `name`, `moudle`, `controller`, `function`, `code`, `remark`, `created_at`, `updated_at`) VALUES ('16', '前台用户管理-浏览', 'admin', 'UcenterMember', 'index', 'admin.UcenterMember.index', '', '2017-02-07 11:49:14', '2017-02-07 11:49:14');
INSERT INTO `geek_action` (`id`, `name`, `moudle`, `controller`, `function`, `code`, `remark`, `created_at`, `updated_at`) VALUES ('18', '后台首页-浏览', 'admin', 'Index', 'index', 'admin.Index.index', '', '2017-02-07 10:55:16', '2017-02-07 10:55:19');
INSERT INTO `geek_action` (`id`, `name`, `moudle`, `controller`, `function`, `code`, `remark`, `created_at`, `updated_at`) VALUES ('19', '后台首页', 'admin', 'Index', '*', 'admin.Index', NULL, '2017-02-07 11:03:27', '2017-02-07 11:03:27');
INSERT INTO `geek_action` (`id`, `name`, `moudle`, `controller`, `function`, `code`, `remark`, `created_at`, `updated_at`) VALUES ('20', '菜单管理-添加', 'admin', 'Menu', 'add', 'admin.Menu.add', '2', '2017-02-06 08:55:54', '2017-02-06 08:55:54');
INSERT INTO `geek_action` (`id`, `name`, `moudle`, `controller`, `function`, `code`, `remark`, `created_at`, `updated_at`) VALUES ('21', '菜单管理-编辑', 'admin', 'Menu', 'edit', 'admin.Menu.edit', '2', '2017-02-06 08:55:54', '2017-02-06 08:55:54');
INSERT INTO `geek_action` (`id`, `name`, `moudle`, `controller`, `function`, `code`, `remark`, `created_at`, `updated_at`) VALUES ('22', '菜单管理-删除', 'admin', 'Menu', 'delete', 'admin.Menu.delete', '2', '2017-02-07 11:13:12', '2017-02-07 11:13:12');
INSERT INTO `geek_action` (`id`, `name`, `moudle`, `controller`, `function`, `code`, `remark`, `created_at`, `updated_at`) VALUES ('23', '系统配置-添加', 'admin', 'System', 'add', 'admin.System.add', '', '2017-02-06 08:56:00', '2017-02-06 08:56:00');
INSERT INTO `geek_action` (`id`, `name`, `moudle`, `controller`, `function`, `code`, `remark`, `created_at`, `updated_at`) VALUES ('24', '系统配置-编辑', 'admin', 'System', 'edit', 'admin.System.edit', '', '2017-02-06 08:56:00', '2017-02-06 08:56:00');
INSERT INTO `geek_action` (`id`, `name`, `moudle`, `controller`, `function`, `code`, `remark`, `created_at`, `updated_at`) VALUES ('25', '系统配置-删除', 'admin', 'System', 'delete', 'admin.System.delete', '', '2017-02-07 11:13:17', '2017-02-07 11:13:17');
INSERT INTO `geek_action` (`id`, `name`, `moudle`, `controller`, `function`, `code`, `remark`, `created_at`, `updated_at`) VALUES ('26', '权限管理-添加', 'admin', 'Privilege', 'add', 'admin.Privilege.add', '', '2017-02-06 08:56:10', '2017-02-06 08:56:10');
INSERT INTO `geek_action` (`id`, `name`, `moudle`, `controller`, `function`, `code`, `remark`, `created_at`, `updated_at`) VALUES ('27', '权限管理-编辑', 'admin', 'Privilege', 'edit', 'admin.Privilege.edit', '', '2017-02-06 08:56:10', '2017-02-06 08:56:10');
INSERT INTO `geek_action` (`id`, `name`, `moudle`, `controller`, `function`, `code`, `remark`, `created_at`, `updated_at`) VALUES ('28', '权限管理-删除', 'admin', 'Privilege', 'delete', 'admin.Privilege.delete', '', '2017-02-07 11:13:28', '2017-02-07 11:13:28');
INSERT INTO `geek_action` (`id`, `name`, `moudle`, `controller`, `function`, `code`, `remark`, `created_at`, `updated_at`) VALUES ('29', '用户组管理-添加', 'admin', 'AdminRole', 'add', 'admin.AdminRole.add', '', '2017-02-07 11:30:16', '2017-02-07 11:30:16');
INSERT INTO `geek_action` (`id`, `name`, `moudle`, `controller`, `function`, `code`, `remark`, `created_at`, `updated_at`) VALUES ('30', '用户组管理-编辑', 'admin', 'AdminRole', 'edit', 'admin.AdminRole.edit', '', '2017-02-07 11:19:35', '2017-02-07 11:19:35');
INSERT INTO `geek_action` (`id`, `name`, `moudle`, `controller`, `function`, `code`, `remark`, `created_at`, `updated_at`) VALUES ('31', '用户组管理-删除', 'admin', 'AdminRole', 'delete', 'admin.AdminRole.delete', '', '2017-02-07 11:19:39', '2017-02-07 11:19:39');
INSERT INTO `geek_action` (`id`, `name`, `moudle`, `controller`, `function`, `code`, `remark`, `created_at`, `updated_at`) VALUES ('32', '后台用户管理-添加', 'admin', 'UcenterAdmin', 'add', 'admin.UcenterAdmin.add', '', '2017-02-07 11:48:29', '2017-02-07 11:48:29');
INSERT INTO `geek_action` (`id`, `name`, `moudle`, `controller`, `function`, `code`, `remark`, `created_at`, `updated_at`) VALUES ('33', '后台用户管理-编辑', 'admin', 'UcenterAdmin', 'edit', 'admin.UcenterAdmin.edit', '', '2017-02-07 11:50:16', '2017-02-07 11:50:16');
INSERT INTO `geek_action` (`id`, `name`, `moudle`, `controller`, `function`, `code`, `remark`, `created_at`, `updated_at`) VALUES ('34', '后台用户管理-删除', 'admin', 'UcenterAdmin', 'delete', 'admin.UcenterAdmin.delete', '', '2017-02-07 11:48:29', '2017-02-07 11:48:29');
INSERT INTO `geek_action` (`id`, `name`, `moudle`, `controller`, `function`, `code`, `remark`, `created_at`, `updated_at`) VALUES ('35', '前台用户管理-添加', 'admin', 'UcenterMember', 'add', 'admin.UcenterMember.add', '', '2017-02-07 11:49:14', '2017-02-07 11:49:14');
INSERT INTO `geek_action` (`id`, `name`, `moudle`, `controller`, `function`, `code`, `remark`, `created_at`, `updated_at`) VALUES ('36', '前台用户管理-编辑', 'admin', 'UcenterMember', 'edit', 'admin.UcenterMember.edit', '', '2017-02-07 11:49:14', '2017-02-07 11:49:14');
INSERT INTO `geek_action` (`id`, `name`, `moudle`, `controller`, `function`, `code`, `remark`, `created_at`, `updated_at`) VALUES ('37', '前台用户管理-删除', 'admin', 'UcenterMember', 'delete', 'admin.UcenterMember.delete', '', '2017-02-07 11:49:14', '2017-02-07 11:49:14');
INSERT INTO `geek_action` (`id`, `name`, `moudle`, `controller`, `function`, `code`, `remark`, `created_at`, `updated_at`) VALUES ('44', '操作日志列表-浏览', 'admin', 'ActionLog', 'index', 'admin.ActionLog.index', '', '2017-02-08 19:14:35', '2017-02-08 19:14:38');
INSERT INTO `geek_action` (`id`, `name`, `moudle`, `controller`, `function`, `code`, `remark`, `created_at`, `updated_at`) VALUES ('45', '操作日志列表', 'admin', 'ActionLog', '*', 'admin.ActionLog.*', '', '2017-02-08 19:16:26', '2017-02-08 19:16:29');
INSERT INTO `geek_action` (`id`, `name`, `moudle`, `controller`, `function`, `code`, `remark`, `created_at`, `updated_at`) VALUES ('46', '操作日志列表-删除', 'admin', 'ActionLog', 'delete', 'admin.ActionLog.delete', '', '2017-02-08 19:16:59', '2017-02-08 19:17:02');

-- ----------------------------
-- Table structure for geek_action_log
-- ----------------------------
DROP TABLE IF EXISTS `geek_action_log`;
CREATE TABLE `geek_action_log` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(11) DEFAULT NULL,
  `action_id` int(11) DEFAULT NULL,
  `action_name` varchar(255) DEFAULT NULL,
  `moudle` varchar(255) DEFAULT NULL,
  `controller` varchar(255) DEFAULT NULL,
  `function` varchar(255) DEFAULT NULL,
  `code` varchar(255) DEFAULT NULL,
  `remark` varchar(255) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2359 DEFAULT CHARSET=utf8mb4 COMMENT='用户操作日志表';

-- ----------------------------
-- Records of geek_action_log
-- ----------------------------


-- ----------------------------
-- Table structure for geek_admin_role
-- ----------------------------
DROP TABLE IF EXISTS `geek_admin_role`;
CREATE TABLE `geek_admin_role` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `perms` text,
  `status` tinyint(4) DEFAULT '1' COMMENT '1启用,0禁用',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 COMMENT='后台用户角色表';

-- ----------------------------
-- Records of geek_admin_role
-- ----------------------------
INSERT INTO `geek_admin_role` (`id`, `name`, `perms`, `status`) VALUES ('1', '超级管理员', 'Privilege,admin.Privilege.index,admin.Privilege.add,admin.Privilege.edit,admin.Privilege.delete,System,admin.System.index,admin.System.config,admin.System.add,admin.System.edit,admin.System.delete,Product,admin.Product.index,admin.Product.add,admin.Product.edit,admin.Product.delete,Menu,admin.Menu.index,admin.Menu.add,admin.Menu.edit,admin.Menu.delete,Adminrole,admin.AdminRole.index,admin.AdminRole.add,admin.AdminRole.edit,admin.AdminRole.delete,UcenterAdmin,admin.UcenterAdmin.index,admin.UcenterAdmin.add,admin.UcenterAdmin.edit,admin.UcenterAdmin.delete,admin.UcenterMember,admin.UcenterMember.index,admin.UcenterMember.add,admin.UcenterMember.edit,admin.UcenterMember.delete,admin.UcenterMember.recharge,admin.UcenterMember.relationNetwork,admin.UcenterMember.rewardDetailed,admin.UcenterMember.pointDetailed,admin.UcenterMember.buyProduct,admin.Index,admin.Index.index,admin.Problem.*,admin.Problem.index,admin.Problem.edit,admin.Problem.delete,admin.Problem.add,admin.ActionLog.*,admin.ActionLog.index,admin.ActionLog.delete,admin.Order.*,admin.Order.index,admin.FinanceCharge.*,admin.FinanceCharge.index,admin.Withdraw.*,admin.Withdraw.index,admin.Withdraw.changeWithdraw,admin.LevelReward.*,admin.LevelReward.index,admin.LevelReward.edit,admin.LevelReward.delete,admin.LevelReward.add,admin.Multiple.*,admin.Multiple.index,admin.Multiple.edit,admin.Multiple.delete,admin.Multiple.add,admin.PointProductClass.*,admin.PointProductClass.index,admin.PointProductClass.edit,admin.PointProductClass.delete,admin.PointProductClass.add,admin.PointProduct.*,admin.PointProduct.index,admin.PointProduct.edit,admin.PointProduct.delete,admin.PointProduct.add,admin.StaticReward.*,admin.StaticReward.index,admin.StaticReward.edit,admin.StaticReward.delete,admin.StaticReward.add,admin.RecommendReward.*,admin.RecommendReward.index,admin.RecommendReward.edit,admin.RecommendReward.delete,admin.RecommendReward.add,admin.SinglePage.*,admin.SinglePage.index,admin.NewsClass.*,admin.NewsClass.index,admin.NewsClass.edit,admin.NewsClass.delete,admin.NewsClass.add,admin.News.*,admin.News.newClassList,admin.News.index,admin.News.edit,admin.News.delete,admin.News.add,admin.ProblemClass.*,admin.ProblemClass.index,admin.ProblemClass.edit,admin.ProblemClass.delete,admin.ProblemClass.add,admin.PointOrder.*,admin.PointOrder.index,admin.Admin.*', '1');

-- ----------------------------
-- Table structure for geek_config
-- ----------------------------
DROP TABLE IF EXISTS `geek_config`;
CREATE TABLE `geek_config` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '配置ID',
  `name` varchar(100) NOT NULL DEFAULT '' COMMENT '配置名称',
  `type` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '配置类型',
  `title` varchar(50) NOT NULL DEFAULT '' COMMENT '配置说明',
  `group` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '配置分组',
  `extra` varchar(255) NOT NULL DEFAULT '' COMMENT '配置值',
  `created_at` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '创建时间',
  `updated_at` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '更新时间',
  `status` tinyint(4) NOT NULL DEFAULT '0' COMMENT '状态',
  `value` text COMMENT '配置值',
  `sort` smallint(3) unsigned NOT NULL DEFAULT '0' COMMENT '排序',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_name` (`name`) USING BTREE,
  KEY `type` (`type`) USING BTREE,
  KEY `group` (`group`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=22 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='配置表';

-- ----------------------------
-- Records of geek_config
-- ----------------------------



-- ----------------------------
-- Table structure for geek_member
-- ----------------------------
DROP TABLE IF EXISTS `geek_member`;
CREATE TABLE `geek_member` (
  `uid` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '用户ID',
  `nickname` char(32) NOT NULL DEFAULT '' COMMENT '昵称',
  `real_name` varchar(255) DEFAULT '' COMMENT '真实姓名',
  `sex` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '性别,0未设置,1男,2女',
  `birthday` date NOT NULL DEFAULT '0000-00-00' COMMENT '生日',
  `qq` char(10) NOT NULL DEFAULT '' COMMENT 'qq号',
  `login` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '登录次数',
  `reg_ip` varchar(20) NOT NULL DEFAULT '0' COMMENT '注册IP',
  `reg_time` int(11) unsigned DEFAULT NULL COMMENT '注册时间',
  `last_login_ip` varchar(20) NOT NULL DEFAULT '0' COMMENT '最后登录IP',
  `last_login_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '最后登录时间',
  `status` tinyint(4) NOT NULL DEFAULT '0' COMMENT '会员状态',
  `signature` text COMMENT '个性签名',
  PRIMARY KEY (`uid`),
  KEY `status` (`status`) USING BTREE,
  KEY `name` (`nickname`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=25 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='用户资料表';

-- ----------------------------
-- Records of geek_member
-- ----------------------------



-- ----------------------------
-- Table structure for geek_menu
-- ----------------------------
DROP TABLE IF EXISTS `geek_menu`;
CREATE TABLE `geek_menu` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '文档ID',
  `title` varchar(50) NOT NULL DEFAULT '' COMMENT '标题',
  `pid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '上级分类ID',
  `sort` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '排序（同级有效）',
  `url` varchar(255) NOT NULL DEFAULT '' COMMENT '链接地址',
  `hide` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否隐藏',
  `remark` varchar(255) NOT NULL DEFAULT '' COMMENT '菜单说明',
  `is_dev` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否仅开发者模式可见,0所有模式都可见,1仅开发者模式可见',
  `privilege_code` varchar(255) DEFAULT '' COMMENT '关联权限代码',
  `icon` varchar(20) NOT NULL COMMENT '导航图标',
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `pid` (`pid`)
) ENGINE=MyISAM AUTO_INCREMENT=10135 DEFAULT CHARSET=utf8 COMMENT='菜单表';

-- ----------------------------
-- Records of geek_menu
-- ----------------------------
INSERT INTO `geek_menu` (`id`, `title`, `pid`, `sort`, `url`, `hide`, `remark`, `is_dev`, `privilege_code`, `icon`, `created_at`, `updated_at`) VALUES ('10073', '权限管理', '0', '1', '', '0', '', '0', 'Privilege', '1', '2017-02-05 09:05:32', '2017-02-06 08:59:18');
INSERT INTO `geek_menu` (`id`, `title`, `pid`, `sort`, `url`, `hide`, `remark`, `is_dev`, `privilege_code`, `icon`, `created_at`, `updated_at`) VALUES ('10072', '菜单管理', '10071', '1', '/admin/Menu/index', '0', '菜单的增删查改', '0', 'admin.Menu.index', '1', '2017-02-05 09:04:31', '2017-02-05 09:04:31');
INSERT INTO `geek_menu` (`id`, `title`, `pid`, `sort`, `url`, `hide`, `remark`, `is_dev`, `privilege_code`, `icon`, `created_at`, `updated_at`) VALUES ('10071', '系统管理', '0', '1', '', '0', '1', '0', 'System', '1', '2017-02-05 09:03:15', '2017-02-06 08:59:21');
INSERT INTO `geek_menu` (`id`, `title`, `pid`, `sort`, `url`, `hide`, `remark`, `is_dev`, `privilege_code`, `icon`, `created_at`, `updated_at`) VALUES ('10074', '操作管理', '10073', '1', '/admin/Privilege/index', '0', '权限的增删查改', '0', 'admin.Privilege.index', '1', '2017-02-05 09:07:03', '2017-02-07 09:56:03');
INSERT INTO `geek_menu` (`id`, `title`, `pid`, `sort`, `url`, `hide`, `remark`, `is_dev`, `privilege_code`, `icon`, `created_at`, `updated_at`) VALUES ('10076', '用户组管理', '10073', '1', '/admin/AdminRole/index', '0', '1', '0', 'admin.AdminRole.index', '1', '2017-02-05 19:32:44', '2017-02-07 11:37:51');
INSERT INTO `geek_menu` (`id`, `title`, `pid`, `sort`, `url`, `hide`, `remark`, `is_dev`, `privilege_code`, `icon`, `created_at`, `updated_at`) VALUES ('10077', '系统配置', '10071', '2', '/admin/System/index', '0', '1', '0', 'admin.System.index', '1', '2017-02-05 20:51:27', '2017-02-06 09:00:58');
INSERT INTO `geek_menu` (`id`, `title`, `pid`, `sort`, `url`, `hide`, `remark`, `is_dev`, `privilege_code`, `icon`, `created_at`, `updated_at`) VALUES ('10078', '系统设置', '10071', '3', '/admin/System/config', '0', '', '0', 'admin.System.config', '1', '2017-02-06 09:26:43', '2017-02-06 09:26:43');
INSERT INTO `geek_menu` (`id`, `title`, `pid`, `sort`, `url`, `hide`, `remark`, `is_dev`, `privilege_code`, `icon`, `created_at`, `updated_at`) VALUES ('10081', '用户管理', '0', '1', '', '0', '', '0', 'UcenterAdmin', '1', '2017-02-06 14:06:15', '2017-02-07 11:58:38');
INSERT INTO `geek_menu` (`id`, `title`, `pid`, `sort`, `url`, `hide`, `remark`, `is_dev`, `privilege_code`, `icon`, `created_at`, `updated_at`) VALUES ('10082', '后台用户管理', '10081', '1', '/admin/UcenterAdmin/index', '0', '', '0', 'admin.UcenterAdmin.index', '1', '2017-02-06 14:07:39', '2017-02-07 11:48:02');
INSERT INTO `geek_menu` (`id`, `title`, `pid`, `sort`, `url`, `hide`, `remark`, `is_dev`, `privilege_code`, `icon`, `created_at`, `updated_at`) VALUES ('10084', '会员管理', '10081', '2', '/admin/UcenterMember/index', '0', '', '0', 'admin.UcenterMember.index', '1', '2017-02-06 15:19:13', '2017-02-10 17:14:32');

-- ----------------------------
-- Table structure for geek_ucenter_admin
-- ----------------------------
DROP TABLE IF EXISTS `geek_ucenter_admin`;
CREATE TABLE `geek_ucenter_admin` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '用户ID',
  `username` varchar(32) NOT NULL COMMENT '用户名',
  `password` char(32) NOT NULL COMMENT '密码',
  `salt` char(32) NOT NULL DEFAULT '',
  `email` char(32) NOT NULL DEFAULT '' COMMENT '用户邮箱',
  `mobile` char(15) NOT NULL COMMENT '用户手机',
  `status` tinyint(4) DEFAULT '1' COMMENT '用户状态:''-1删除,0禁用,1启用''',
  `reg_ip` varchar(20) NOT NULL DEFAULT '0' COMMENT '注册IP',
  `last_login_ip` char(15) NOT NULL DEFAULT '0' COMMENT '最后登录IP',
  `reg_time` datetime DEFAULT NULL COMMENT '注册时间',
  `last_login_time` datetime DEFAULT NULL COMMENT '最后登录时间',
  `updated_at` datetime DEFAULT NULL COMMENT '更新时间',
  `role_id` int(11) DEFAULT NULL COMMENT '角色ID',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=8 DEFAULT CHARSET=utf8 COMMENT='后台用户表';

-- ----------------------------
-- Records of geek_ucenter_admin
-- ----------------------------


-- ----------------------------
-- Table structure for geek_ucenter_member
-- ----------------------------
DROP TABLE IF EXISTS `geek_ucenter_member`;
CREATE TABLE `geek_ucenter_member` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '用户ID',
  `username` char(32) NOT NULL COMMENT '用户名',
  `password` char(32) NOT NULL COMMENT '密码',
  `email` char(32) NOT NULL COMMENT '用户邮箱',
  `mobile` char(15) NOT NULL COMMENT '用户手机',
  `reg_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '注册时间',
  `reg_ip` varchar(255) NOT NULL DEFAULT '0' COMMENT '注册IP',
  `last_login_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '最后登录时间',
  `last_login_ip` varchar(255) NOT NULL DEFAULT '0' COMMENT '最后登录IP',
  `update_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  `status` tinyint(4) DEFAULT '0' COMMENT '用户状态',
  `type` tinyint(4) NOT NULL COMMENT '1为用户名注册，2为邮箱注册，3为手机注册',
  `salt` char(32) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  KEY `status` (`status`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=25 DEFAULT CHARSET=utf8 ROW_FORMAT=FIXED COMMENT='网站用户表';

