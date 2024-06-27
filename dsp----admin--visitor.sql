/*
Navicat MySQL Data Transfer

Source Server         : localhost_3306
Source Server Version : 50726
Source Host           : localhost:3306
Source Database       : yuanma-dsp

Target Server Type    : MYSQL
Target Server Version : 50726
File Encoding         : 65001

Date: 2021-12-11 15:39:35
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for `cmf_admin_log`
-- ----------------------------
DROP TABLE IF EXISTS `cmf_admin_log`;
CREATE TABLE `cmf_admin_log` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `adminid` int(11) NOT NULL COMMENT '管理员ID',
  `admin` varchar(255) NOT NULL COMMENT '管理员',
  `action` text NOT NULL COMMENT '操作内容',
  `ip` bigint(20) NOT NULL COMMENT 'IP地址',
  `addtime` int(11) NOT NULL COMMENT '时间',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Records of cmf_admin_log
-- ----------------------------

-- ----------------------------
-- Table structure for `cmf_admin_menu`
-- ----------------------------
DROP TABLE IF EXISTS `cmf_admin_menu`;
CREATE TABLE `cmf_admin_menu` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `parent_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '父菜单id',
  `type` tinyint(3) unsigned NOT NULL DEFAULT '1' COMMENT '菜单类型;1:有界面可访问菜单,2:无界面可访问菜单,0:只作为菜单',
  `status` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '状态;1:显示,0:不显示',
  `list_order` float NOT NULL DEFAULT '10000' COMMENT '排序',
  `app` varchar(40) CHARACTER SET utf8 NOT NULL DEFAULT '' COMMENT '应用名',
  `controller` varchar(30) CHARACTER SET utf8 NOT NULL DEFAULT '' COMMENT '控制器名',
  `action` varchar(30) CHARACTER SET utf8 NOT NULL DEFAULT '' COMMENT '操作名称',
  `param` varchar(50) CHARACTER SET utf8 NOT NULL DEFAULT '' COMMENT '额外参数',
  `name` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '菜单名称',
  `icon` varchar(20) CHARACTER SET utf8 NOT NULL DEFAULT '' COMMENT '菜单图标',
  `remark` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '备注',
  PRIMARY KEY (`id`) USING BTREE,
  KEY `status` (`status`) USING BTREE,
  KEY `parent_id` (`parent_id`) USING BTREE,
  KEY `controller` (`controller`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=430 DEFAULT CHARSET=utf8mb4 ROW_FORMAT=COMPACT COMMENT='后台菜单表';

-- ----------------------------
-- Records of cmf_admin_menu
-- ----------------------------
INSERT INTO `cmf_admin_menu` VALUES ('6', '0', '0', '1', '1', 'admin', 'Setting', 'default', '', '设置', 'cogs', '系统设置入口');
INSERT INTO `cmf_admin_menu` VALUES ('20', '6', '1', '0', '10000', 'admin', 'Menu', 'index', '', '后台菜单', '', '后台菜单管理');
INSERT INTO `cmf_admin_menu` VALUES ('21', '20', '1', '0', '10000', 'admin', 'Menu', 'lists', '', '所有菜单', '', '后台所有菜单列表');
INSERT INTO `cmf_admin_menu` VALUES ('22', '20', '1', '0', '10000', 'admin', 'Menu', 'add', '', '后台菜单添加', '', '后台菜单添加');
INSERT INTO `cmf_admin_menu` VALUES ('23', '20', '2', '0', '10000', 'admin', 'Menu', 'addPost', '', '后台菜单添加提交保存', '', '后台菜单添加提交保存');
INSERT INTO `cmf_admin_menu` VALUES ('24', '20', '1', '0', '10000', 'admin', 'Menu', 'edit', '', '后台菜单编辑', '', '后台菜单编辑');
INSERT INTO `cmf_admin_menu` VALUES ('25', '20', '2', '0', '10000', 'admin', 'Menu', 'editPost', '', '后台菜单编辑提交保存', '', '后台菜单编辑提交保存');
INSERT INTO `cmf_admin_menu` VALUES ('26', '20', '2', '0', '10000', 'admin', 'Menu', 'delete', '', '后台菜单删除', '', '后台菜单删除');
INSERT INTO `cmf_admin_menu` VALUES ('27', '20', '2', '0', '10000', 'admin', 'Menu', 'listOrder', '', '后台菜单排序', '', '后台菜单排序');
INSERT INTO `cmf_admin_menu` VALUES ('28', '20', '1', '0', '10000', 'admin', 'Menu', 'getActions', '', '导入新后台菜单', '', '导入新后台菜单');
INSERT INTO `cmf_admin_menu` VALUES ('49', '110', '0', '1', '10000', 'admin', 'User', 'default', '', '管理组', '', '管理组');
INSERT INTO `cmf_admin_menu` VALUES ('50', '49', '1', '1', '10000', 'admin', 'Rbac', 'index', '', '角色管理', '', '角色管理');
INSERT INTO `cmf_admin_menu` VALUES ('51', '50', '1', '0', '10000', 'admin', 'Rbac', 'roleAdd', '', '添加角色', '', '添加角色');
INSERT INTO `cmf_admin_menu` VALUES ('52', '50', '2', '0', '10000', 'admin', 'Rbac', 'roleAddPost', '', '添加角色提交', '', '添加角色提交');
INSERT INTO `cmf_admin_menu` VALUES ('53', '50', '1', '0', '10000', 'admin', 'Rbac', 'roleEdit', '', '编辑角色', '', '编辑角色');
INSERT INTO `cmf_admin_menu` VALUES ('54', '50', '2', '0', '10000', 'admin', 'Rbac', 'roleEditPost', '', '编辑角色提交', '', '编辑角色提交');
INSERT INTO `cmf_admin_menu` VALUES ('55', '50', '2', '0', '10000', 'admin', 'Rbac', 'roleDelete', '', '删除角色', '', '删除角色');
INSERT INTO `cmf_admin_menu` VALUES ('56', '50', '1', '0', '10000', 'admin', 'Rbac', 'authorize', '', '设置角色权限', '', '设置角色权限');
INSERT INTO `cmf_admin_menu` VALUES ('57', '50', '2', '0', '10000', 'admin', 'Rbac', 'authorizePost', '', '角色授权提交', '', '角色授权提交');
INSERT INTO `cmf_admin_menu` VALUES ('58', '0', '1', '0', '10000', 'admin', 'RecycleBin', 'index', '', '回收站', '', '回收站');
INSERT INTO `cmf_admin_menu` VALUES ('59', '58', '2', '0', '10000', 'admin', 'RecycleBin', 'restore', '', '回收站还原', '', '回收站还原');
INSERT INTO `cmf_admin_menu` VALUES ('60', '58', '2', '0', '10000', 'admin', 'RecycleBin', 'delete', '', '回收站彻底删除', '', '回收站彻底删除');
INSERT INTO `cmf_admin_menu` VALUES ('71', '6', '1', '1', '0', 'admin', 'Setting', 'site', '', '网站信息', '', '网站信息');
INSERT INTO `cmf_admin_menu` VALUES ('72', '71', '2', '0', '10000', 'admin', 'Setting', 'sitePost', '', '网站信息设置提交', '', '网站信息设置提交');
INSERT INTO `cmf_admin_menu` VALUES ('75', '6', '1', '1', '10000', 'admin', 'Setting', 'upload', '', '上传设置', '', '上传设置');
INSERT INTO `cmf_admin_menu` VALUES ('76', '75', '2', '0', '10000', 'admin', 'Setting', 'uploadPost', '', '上传设置提交', '', '上传设置提交');
INSERT INTO `cmf_admin_menu` VALUES ('77', '6', '1', '1', '10000', 'admin', 'Setting', 'clearCache', '', '清除缓存', '', '清除缓存');
INSERT INTO `cmf_admin_menu` VALUES ('110', '0', '0', '1', '2', 'user', 'AdminIndex', 'default', '', '用户管理', 'group', '用户管理');
INSERT INTO `cmf_admin_menu` VALUES ('111', '49', '1', '1', '10000', 'admin', 'User', 'index', '', '管理员', '', '管理员管理');
INSERT INTO `cmf_admin_menu` VALUES ('112', '111', '1', '0', '10000', 'admin', 'User', 'add', '', '管理员添加', '', '管理员添加');
INSERT INTO `cmf_admin_menu` VALUES ('113', '111', '2', '0', '10000', 'admin', 'User', 'addPost', '', '管理员添加提交', '', '管理员添加提交');
INSERT INTO `cmf_admin_menu` VALUES ('114', '111', '1', '0', '10000', 'admin', 'User', 'edit', '', '管理员编辑', '', '管理员编辑');
INSERT INTO `cmf_admin_menu` VALUES ('115', '111', '2', '0', '10000', 'admin', 'User', 'editPost', '', '管理员编辑提交', '', '管理员编辑提交');
INSERT INTO `cmf_admin_menu` VALUES ('116', '111', '1', '0', '10000', 'admin', 'User', 'userInfo', '', '个人信息', '', '管理员个人信息修改');
INSERT INTO `cmf_admin_menu` VALUES ('117', '111', '2', '0', '10000', 'admin', 'User', 'userInfoPost', '', '管理员个人信息修改提交', '', '管理员个人信息修改提交');
INSERT INTO `cmf_admin_menu` VALUES ('118', '111', '2', '0', '10000', 'admin', 'User', 'delete', '', '管理员删除', '', '管理员删除');
INSERT INTO `cmf_admin_menu` VALUES ('119', '111', '2', '0', '10000', 'admin', 'User', 'ban', '', '停用管理员', '', '停用管理员');
INSERT INTO `cmf_admin_menu` VALUES ('120', '111', '2', '0', '10000', 'admin', 'User', 'cancelBan', '', '启用管理员', '', '启用管理员');
INSERT INTO `cmf_admin_menu` VALUES ('121', '0', '1', '0', '10000', 'user', 'AdminAsset', 'index', '', '资源管理', 'file', '资源管理列表');
INSERT INTO `cmf_admin_menu` VALUES ('122', '121', '2', '0', '10000', 'user', 'AdminAsset', 'delete', '', '删除文件', '', '删除文件');
INSERT INTO `cmf_admin_menu` VALUES ('123', '110', '0', '1', '10000', 'user', 'AdminIndex', 'default1', '', '用户组', '', '用户组');
INSERT INTO `cmf_admin_menu` VALUES ('124', '123', '1', '1', '10000', 'user', 'AdminIndex', 'index', '', '本站用户', '', '本站用户');
INSERT INTO `cmf_admin_menu` VALUES ('125', '124', '2', '0', '8', 'user', 'AdminIndex', 'ban', '', '本站用户拉黑', '', '本站用户拉黑');
INSERT INTO `cmf_admin_menu` VALUES ('126', '124', '2', '0', '9', 'user', 'AdminIndex', 'cancelBan', '', '本站用户启用', '', '本站用户启用');
INSERT INTO `cmf_admin_menu` VALUES ('133', '6', '1', '1', '10', 'admin', 'Setting', 'configpri', '', '私密设置', '', '');
INSERT INTO `cmf_admin_menu` VALUES ('134', '6', '1', '1', '0', 'admin', 'Guide', 'set', '', '引导页', '', '');
INSERT INTO `cmf_admin_menu` VALUES ('135', '134', '1', '0', '10000', 'admin', 'Guide', 'set_post', '', '设置提交', '', '');
INSERT INTO `cmf_admin_menu` VALUES ('136', '134', '1', '0', '10000', 'admin', 'Guide', 'index', '', '管理', '', '');
INSERT INTO `cmf_admin_menu` VALUES ('137', '136', '1', '0', '10000', 'admin', 'Guide', 'add', '', '添加', '', '');
INSERT INTO `cmf_admin_menu` VALUES ('138', '136', '1', '0', '10000', 'admin', 'Guide', 'add_post', '', '添加提交', '', '');
INSERT INTO `cmf_admin_menu` VALUES ('139', '136', '1', '0', '10000', 'admin', 'Guide', 'edit', '', '编辑', '', '');
INSERT INTO `cmf_admin_menu` VALUES ('140', '136', '1', '0', '10000', 'admin', 'Guide', 'edit_post', '', '编辑提交', '', '');
INSERT INTO `cmf_admin_menu` VALUES ('141', '136', '1', '0', '10000', 'admin', 'Guide', 'listorders', '', '排序', '', '');
INSERT INTO `cmf_admin_menu` VALUES ('142', '136', '1', '0', '10000', 'admin', 'Guide', 'del', '', '删除', '', '');
INSERT INTO `cmf_admin_menu` VALUES ('143', '123', '1', '1', '10000', 'admin', 'Userauth', 'index', '', '身份认证', '', '');
INSERT INTO `cmf_admin_menu` VALUES ('144', '0', '1', '1', '3', 'admin', 'Report', 'default', '', '用户举报', 'street-view', '');
INSERT INTO `cmf_admin_menu` VALUES ('145', '144', '1', '1', '10000', 'admin', 'Report', 'classify', '', '举报分类', '', '');
INSERT INTO `cmf_admin_menu` VALUES ('146', '145', '1', '0', '10000', 'admin', 'Report', 'classify_add', '', '举报分类添加', '', '');
INSERT INTO `cmf_admin_menu` VALUES ('147', '145', '1', '0', '10000', 'admin', 'Report', 'classify_add_post', '', '用户举报分类添加保存', '', '');
INSERT INTO `cmf_admin_menu` VALUES ('148', '145', '1', '0', '10000', 'admin', 'Report', 'classify_edit', '', '举报分类编辑', '', '');
INSERT INTO `cmf_admin_menu` VALUES ('149', '145', '1', '0', '10000', 'admin', 'Report', 'classify_edit_post', '', '举报分类编辑保存', '', '');
INSERT INTO `cmf_admin_menu` VALUES ('150', '145', '1', '0', '10000', 'admin', 'Report', 'classify_listorders', '', '举报分类排序', '', '');
INSERT INTO `cmf_admin_menu` VALUES ('151', '145', '1', '0', '10000', 'admin', 'Report', 'classify_del', '', '举报分类删除', '', '');
INSERT INTO `cmf_admin_menu` VALUES ('152', '144', '1', '1', '10000', 'admin', 'Report', 'index', '', '举报列表', '', '');
INSERT INTO `cmf_admin_menu` VALUES ('153', '152', '1', '0', '10000', 'admin', 'Report', 'ban', '', '禁用用户', '', '');
INSERT INTO `cmf_admin_menu` VALUES ('154', '152', '1', '0', '10000', 'admin', 'Report', 'ban_video', '', '下架视频', '', '');
INSERT INTO `cmf_admin_menu` VALUES ('155', '152', '1', '0', '10000', 'admin', 'Report', 'setstatus', '', '标记处理', '', '');
INSERT INTO `cmf_admin_menu` VALUES ('156', '152', '1', '0', '10000', 'admin', 'Report', 'ban_all', '', '标记处理+禁用用户+下架视频 ', '', '');
INSERT INTO `cmf_admin_menu` VALUES ('157', '152', '1', '0', '10000', 'admin', 'Report', 'del', '', '用户举报删除', '', '');
INSERT INTO `cmf_admin_menu` VALUES ('158', '0', '1', '1', '4', 'admin', 'Pushmessage', 'default', '', '官方通知', 'bell', '');
INSERT INTO `cmf_admin_menu` VALUES ('159', '158', '1', '1', '10000', 'admin', 'Pushmessage', 'add', '', '通知添加', '', '');
INSERT INTO `cmf_admin_menu` VALUES ('160', '159', '1', '0', '10000', 'admin', 'Pushmessage', 'add_post', '', '推送添加保存', '', '');
INSERT INTO `cmf_admin_menu` VALUES ('161', '158', '1', '1', '10000', 'admin', 'Pushmessage', 'index', '', '通知记录', '', '');
INSERT INTO `cmf_admin_menu` VALUES ('162', '161', '1', '0', '10000', 'admin', 'Pushmessage', 'push', '', '记录推送', '', '');
INSERT INTO `cmf_admin_menu` VALUES ('163', '161', '1', '0', '10000', 'admin', 'Pushmessage', 'del', '', '推送记录删除', '', '');
INSERT INTO `cmf_admin_menu` VALUES ('164', '0', '1', '1', '5', 'admin', 'Advert', 'default', '', '广告管理', '', '');
INSERT INTO `cmf_admin_menu` VALUES ('165', '164', '1', '1', '10000', 'admin', 'Advert', 'add', '', '广告添加', '', '');
INSERT INTO `cmf_admin_menu` VALUES ('166', '164', '1', '1', '10000', 'admin', 'Advert', 'index', '', '广告列表', '', '');
INSERT INTO `cmf_admin_menu` VALUES ('167', '164', '1', '0', '10000', 'admin', 'Advert', 'edit', '', '广告编辑', '', '');
INSERT INTO `cmf_admin_menu` VALUES ('168', '167', '1', '0', '10000', 'admin', 'Advert', 'edit_post', '', '编辑保存', '', '');
INSERT INTO `cmf_admin_menu` VALUES ('169', '164', '1', '0', '10000', 'admin', 'Advert', 'del', '', '广告删除', '', '');
INSERT INTO `cmf_admin_menu` VALUES ('170', '164', '1', '1', '10000', 'admin', 'Advert', 'lowervideo', '', '下架广告列表', '', '');
INSERT INTO `cmf_admin_menu` VALUES ('171', '166', '1', '0', '10000', 'admin', 'Advert', 'setXiajia', '', '下架', '', '');
INSERT INTO `cmf_admin_menu` VALUES ('172', '170', '1', '0', '10000', 'admin', 'Advert', 'set_shangjia', '', '上架', '', '');
INSERT INTO `cmf_admin_menu` VALUES ('173', '164', '1', '0', '10000', 'admin', 'Advert', 'video_listen', '', '观看', '', '');
INSERT INTO `cmf_admin_menu` VALUES ('174', '0', '1', '1', '6', 'admin', 'Music', 'default', '', '音乐管理', 'music', '');
INSERT INTO `cmf_admin_menu` VALUES ('175', '174', '1', '1', '10000', 'admin', 'Music', 'classify', '', '音乐分类', '', '');
INSERT INTO `cmf_admin_menu` VALUES ('176', '175', '1', '0', '10000', 'admin', 'Music', 'classify_canceldel', '', '分类取消删除', '', '');
INSERT INTO `cmf_admin_menu` VALUES ('177', '175', '1', '0', '10000', 'admin', 'Music', 'classify_del', '', '分类删除', '', '');
INSERT INTO `cmf_admin_menu` VALUES ('178', '175', '1', '0', '10000', 'admin', 'Music', 'classify_edit', '', '分类编辑', '', '');
INSERT INTO `cmf_admin_menu` VALUES ('179', '178', '1', '0', '10000', 'admin', 'Music', 'classify_edit_post', '', '编辑提交', '', '');
INSERT INTO `cmf_admin_menu` VALUES ('180', '175', '1', '0', '10000', 'admin', 'Music', 'classify_add', '', '分类添加', '', '');
INSERT INTO `cmf_admin_menu` VALUES ('181', '180', '1', '0', '10000', 'admin', 'Music', 'classify_add_post', '', '分类添加保存', '', '');
INSERT INTO `cmf_admin_menu` VALUES ('182', '174', '1', '1', '10000', 'admin', 'Music', 'index', '', '背景音乐列表', '', '');
INSERT INTO `cmf_admin_menu` VALUES ('183', '182', '1', '0', '10000', 'admin', 'Music', 'music_del', '', '删除', '', '');
INSERT INTO `cmf_admin_menu` VALUES ('184', '182', '1', '0', '10000', 'admin', 'Music', 'music_canceldel', '', '取消删除', '', '');
INSERT INTO `cmf_admin_menu` VALUES ('185', '182', '1', '0', '10000', 'admin', 'Music', 'music_listen', '', '试听', '', '');
INSERT INTO `cmf_admin_menu` VALUES ('186', '182', '1', '0', '10000', 'admin', 'Music', 'music_edit', '', '编辑', '', '');
INSERT INTO `cmf_admin_menu` VALUES ('187', '186', '1', '0', '10000', 'admin', 'Music', 'music_edit_post', '', '编辑提交', '', '');
INSERT INTO `cmf_admin_menu` VALUES ('188', '182', '1', '0', '10000', 'admin', 'Music', 'music_add', '', '添加', '', '');
INSERT INTO `cmf_admin_menu` VALUES ('189', '188', '1', '0', '10000', 'admin', 'Music', 'music_add_post', '', '添加保存', '', '');
INSERT INTO `cmf_admin_menu` VALUES ('190', '0', '1', '1', '7', 'admin', 'Video', 'defaults', '', '视频管理', 'file-movie-o', '');
INSERT INTO `cmf_admin_menu` VALUES ('191', '190', '1', '1', '1', 'admin', 'Label', 'index', '', '话题标签', '', '');
INSERT INTO `cmf_admin_menu` VALUES ('192', '191', '1', '0', '10000', 'admin', 'Label', 'add', '', '添加', '', '');
INSERT INTO `cmf_admin_menu` VALUES ('193', '192', '1', '0', '10000', 'admin', 'Label', 'add_post', '', '添加提交', '', '');
INSERT INTO `cmf_admin_menu` VALUES ('194', '191', '1', '0', '10000', 'admin', 'Label', 'edit', '', '编辑', '', '');
INSERT INTO `cmf_admin_menu` VALUES ('195', '194', '1', '0', '10000', 'admin', 'Label', 'edit_post', '', '编辑提交', '', '');
INSERT INTO `cmf_admin_menu` VALUES ('196', '191', '1', '0', '10000', 'admin', 'Label', 'listsorders', '', '排序', '', '');
INSERT INTO `cmf_admin_menu` VALUES ('197', '191', '1', '0', '10000', 'admin', 'Label', 'del', '', '删除', '', '');
INSERT INTO `cmf_admin_menu` VALUES ('198', '190', '1', '1', '3', 'admin', 'Video', 'add', '', '视频添加', '', '');
INSERT INTO `cmf_admin_menu` VALUES ('199', '190', '1', '1', '4', 'admin', 'Video', 'passindex', '', '审核通过列表', '', '');
INSERT INTO `cmf_admin_menu` VALUES ('200', '190', '1', '1', '6', 'admin', 'Video', 'nopassindex', '', '未通过列表', '', '');
INSERT INTO `cmf_admin_menu` VALUES ('201', '190', '1', '1', '7', 'admin', 'Video', 'lowervideo', '', '下架列表', '', '');
INSERT INTO `cmf_admin_menu` VALUES ('202', '201', '1', '0', '10000', 'admin', 'Video', 'set_shangjia', '', '上架', '', '');
INSERT INTO `cmf_admin_menu` VALUES ('203', '190', '1', '1', '8', 'admin', 'Popular', 'index', '', '上热门', '', '');
INSERT INTO `cmf_admin_menu` VALUES ('204', '190', '1', '1', '9', 'admin', 'Video', 'reportset', '', '举报类型', '', '');
INSERT INTO `cmf_admin_menu` VALUES ('205', '204', '1', '0', '1', 'admin', 'Video', 'add_report', '', '添加类型', '', '');
INSERT INTO `cmf_admin_menu` VALUES ('206', '205', '1', '0', '10000', 'admin', 'Video', 'add_reportpost', '', '添加提交', '', '');
INSERT INTO `cmf_admin_menu` VALUES ('207', '204', '1', '0', '2', 'admin', 'Video', 'edit_report', '', '编辑类型', '', '');
INSERT INTO `cmf_admin_menu` VALUES ('208', '207', '1', '0', '10000', 'admin', 'Video', 'edit_reportpost', '', '编辑提交', '', '');
INSERT INTO `cmf_admin_menu` VALUES ('209', '204', '1', '0', '3', 'admin', 'Video', 'del_report', '', '删除类型', '', '');
INSERT INTO `cmf_admin_menu` VALUES ('210', '204', '1', '0', '4', 'admin', 'Video', 'listsordersset', '', '类型排序', '', '');
INSERT INTO `cmf_admin_menu` VALUES ('211', '190', '1', '1', '10', 'admin', 'Video', 'reportlist', '', '举报列表', '', '');
INSERT INTO `cmf_admin_menu` VALUES ('212', '211', '1', '0', '1', 'admin', 'Video', 'setstatus', '', '标记处理', '', '');
INSERT INTO `cmf_admin_menu` VALUES ('213', '211', '1', '0', '2', 'admin', 'Video', 'report_del', '', '删除', '', '');
INSERT INTO `cmf_admin_menu` VALUES ('214', '190', '1', '1', '5', 'admin', 'Video', 'index', '', '等待审核列表', '', '');
INSERT INTO `cmf_admin_menu` VALUES ('215', '198', '1', '0', '10000', 'admin', 'Video', 'add_post', '', '添加提交', '', '');
INSERT INTO `cmf_admin_menu` VALUES ('216', '190', '1', '0', '11', 'admin', 'Video', 'edit', '', '视频编辑', '', '');
INSERT INTO `cmf_admin_menu` VALUES ('217', '216', '1', '0', '10000', 'admin', 'Video', 'edit_post', '', '编辑提交', '', '');
INSERT INTO `cmf_admin_menu` VALUES ('218', '190', '1', '0', '12', 'admin', 'Video', 'del', '', '删除', '', '');
INSERT INTO `cmf_admin_menu` VALUES ('219', '190', '1', '0', '13', 'admin', 'Video', 'video_listen', '', '视频观看', '', '');
INSERT INTO `cmf_admin_menu` VALUES ('220', '199', '1', '0', '10000', 'admin', 'Video', 'setXiajia', '', '下架', '', '');
INSERT INTO `cmf_admin_menu` VALUES ('221', '190', '1', '0', '14', 'admin', 'Video', 'commentlists', '', '评论列表', '', '');
INSERT INTO `cmf_admin_menu` VALUES ('222', '0', '1', '1', '8', 'admin', 'Shopapply', 'default', '', '店铺管理', '', '');
INSERT INTO `cmf_admin_menu` VALUES ('223', '222', '1', '1', '3', 'admin', 'Shopapply', 'index', '', '店铺申请', '', '');
INSERT INTO `cmf_admin_menu` VALUES ('224', '223', '1', '0', '10000', 'admin', 'Shopapply', 'edit', '', '编辑', '', '');
INSERT INTO `cmf_admin_menu` VALUES ('225', '224', '1', '0', '10000', 'admin', 'Shopapply', 'edit_post', '', '编辑提交', '', '');
INSERT INTO `cmf_admin_menu` VALUES ('226', '223', '1', '0', '10000', 'admin', 'Shopapply', 'del', '', '删除', '', '');
INSERT INTO `cmf_admin_menu` VALUES ('227', '222', '1', '1', '4', 'admin', 'Shopgoods', 'index', '', '商品列表', '', '');
INSERT INTO `cmf_admin_menu` VALUES ('228', '227', '1', '0', '10000', 'admin', 'Shopgoods', 'del', '', '删除', '', '');
INSERT INTO `cmf_admin_menu` VALUES ('229', '0', '1', '1', '12', 'admin', 'Content', 'default', '', '内容管理', 'th', '');
INSERT INTO `cmf_admin_menu` VALUES ('230', '229', '1', '1', '1', 'admin', 'Feedback', 'index', '', '用户反馈', '', '');
INSERT INTO `cmf_admin_menu` VALUES ('231', '230', '1', '0', '1', 'admin', 'Feedback', 'setstatus', '', '标记处理', '', '');
INSERT INTO `cmf_admin_menu` VALUES ('232', '230', '1', '0', '2', 'admin', 'Feedback', 'del', '', '删除', '', '');
INSERT INTO `cmf_admin_menu` VALUES ('233', '229', '1', '1', '2', 'admin', 'Adminpost', 'index', '', '文章管理', '', '');
INSERT INTO `cmf_admin_menu` VALUES ('234', '233', '1', '0', '1', 'admin', 'Adminpost', 'add', '', '添加', '', '');
INSERT INTO `cmf_admin_menu` VALUES ('235', '233', '1', '0', '2', 'admin', 'Adminpost', 'addPost', '', '添加提交', '', '');
INSERT INTO `cmf_admin_menu` VALUES ('236', '233', '1', '0', '3', 'admin', 'Adminpost', 'edit', '', '编辑', '', '');
INSERT INTO `cmf_admin_menu` VALUES ('237', '233', '1', '0', '4', 'admin', 'Adminpost', 'editPost', '', '编辑提交', '', '');
INSERT INTO `cmf_admin_menu` VALUES ('238', '233', '1', '0', '5', 'admin', 'Adminpost', 'del', '', '删除', '', '');
INSERT INTO `cmf_admin_menu` VALUES ('241', '233', '1', '0', '8', 'admin', 'Adminpost', 'deletes', '', '批量删除', '', '');
INSERT INTO `cmf_admin_menu` VALUES ('242', '229', '1', '1', '3', 'admin', 'Adminpage', 'index', '', '页面管理', '', '');
INSERT INTO `cmf_admin_menu` VALUES ('244', '233', '1', '0', '9', 'admin', 'Adminpost', 'listordersset', '', '排序', '', '');
INSERT INTO `cmf_admin_menu` VALUES ('246', '242', '1', '0', '3', 'admin', 'Adminpage', 'edit', '', '编辑页面', '', '');
INSERT INTO `cmf_admin_menu` VALUES ('247', '246', '1', '0', '10000', 'admin', 'Adminpage', 'editPost', '', '提交编辑', '', '');
INSERT INTO `cmf_admin_menu` VALUES ('250', '229', '1', '1', '4', 'admin', 'Adminterm', 'index', '', '分类管理', '', '');
INSERT INTO `cmf_admin_menu` VALUES ('253', '250', '1', '0', '10000', 'admin', 'Adminterm', 'edit', '', '编辑', '', '');
INSERT INTO `cmf_admin_menu` VALUES ('254', '253', '1', '0', '10000', 'admin', 'Adminterm', 'edit_post', '', '提交', '', '');
INSERT INTO `cmf_admin_menu` VALUES ('257', '0', '1', '1', '10', 'admin', 'Agent', 'default', '', '分销管理', '', '');
INSERT INTO `cmf_admin_menu` VALUES ('258', '257', '1', '1', '10000', 'admin', 'Agent', 'index', '', '分销关系', '', '');
INSERT INTO `cmf_admin_menu` VALUES ('259', '257', '1', '1', '10000', 'admin', 'Agent', 'index2', '', '分销收益', '', '');
INSERT INTO `cmf_admin_menu` VALUES ('260', '0', '1', '1', '11', 'admin', 'Cash', 'default', '', '财务管理', '', '');
INSERT INTO `cmf_admin_menu` VALUES ('261', '260', '1', '1', '1', 'admin', 'Cash', 'index', '', '提现管理', '', '');
INSERT INTO `cmf_admin_menu` VALUES ('262', '261', '1', '0', '10000', 'admin', 'Cash', 'edit', '', '编辑', '', '');
INSERT INTO `cmf_admin_menu` VALUES ('263', '262', '1', '0', '10000', 'admin', 'Cash', 'edit_post', '', '编辑提交', '', '');
INSERT INTO `cmf_admin_menu` VALUES ('264', '260', '1', '1', '2', 'admin', 'Manual', 'index', '', '手动充值', '', '');
INSERT INTO `cmf_admin_menu` VALUES ('265', '264', '1', '0', '10000', 'admin', 'Manual', 'add', '', '添加', '', '');
INSERT INTO `cmf_admin_menu` VALUES ('266', '265', '1', '0', '10000', 'admin', 'Manual', 'add_post', '', '添加提交', '', '');
INSERT INTO `cmf_admin_menu` VALUES ('267', '190', '1', '1', '2', 'Admin', 'Videoclass', 'index', '', '视频分类', '', '');
INSERT INTO `cmf_admin_menu` VALUES ('268', '0', '1', '1', '9', 'Admin', 'Live', 'default', '', '直播管理', '', '');
INSERT INTO `cmf_admin_menu` VALUES ('269', '268', '1', '1', '2', 'Admin', 'Liveban', 'index', '', '禁播列表', '', '');
INSERT INTO `cmf_admin_menu` VALUES ('270', '269', '1', '0', '10000', 'Admin', 'Liveban', 'del', '', '删除', '', '');
INSERT INTO `cmf_admin_menu` VALUES ('271', '268', '1', '1', '3', 'Admin', 'Liveshut', 'index', '', '禁言列表', '', '');
INSERT INTO `cmf_admin_menu` VALUES ('272', '271', '1', '0', '10000', 'Admin', 'Liveshut', 'del', '', '删除', '', '');
INSERT INTO `cmf_admin_menu` VALUES ('273', '268', '1', '1', '4', 'Admin', 'Livekick', 'index', '', '踢人列表', '', '');
INSERT INTO `cmf_admin_menu` VALUES ('274', '273', '1', '0', '10000', 'Admin', 'Livekick', 'del', '', '删除', '', '');
INSERT INTO `cmf_admin_menu` VALUES ('275', '268', '1', '1', '5', 'Admin', 'Liveing', 'index', '', '直播列表', '', '');
INSERT INTO `cmf_admin_menu` VALUES ('276', '275', '1', '0', '10000', 'Admin', 'Liveing', 'add', '', '添加视频', '', '');
INSERT INTO `cmf_admin_menu` VALUES ('277', '276', '1', '0', '10000', 'Admin', 'Liveing', 'add_post', '', '添加保存', '', '');
INSERT INTO `cmf_admin_menu` VALUES ('278', '275', '1', '0', '10000', 'Admin', 'Liveing', 'edit', '', '视频编辑', '', '');
INSERT INTO `cmf_admin_menu` VALUES ('279', '278', '1', '0', '10000', 'Admin', 'Liveing', 'edit_post', '', '视频编辑提交', '', '');
INSERT INTO `cmf_admin_menu` VALUES ('280', '275', '1', '0', '10000', 'Admin', 'Liveing', 'del', '', '视频删除', '', '');
INSERT INTO `cmf_admin_menu` VALUES ('281', '268', '1', '1', '6', 'Admin', 'Monitor', 'index', '', '直播监控', '', '');
INSERT INTO `cmf_admin_menu` VALUES ('282', '281', '1', '0', '10000', 'Admin', 'Monitor', 'stopRoom', '', '关播', '', '');
INSERT INTO `cmf_admin_menu` VALUES ('283', '281', '1', '0', '10000', 'Admin', 'Monitor', 'full', '', '监控大屏', '', '');
INSERT INTO `cmf_admin_menu` VALUES ('284', '268', '1', '1', '7', 'Admin', 'Gift', 'index', '', '礼物列表', '', '');
INSERT INTO `cmf_admin_menu` VALUES ('285', '268', '1', '1', '8', 'Admin', 'Livereport', 'default', '', '直播间举报管理', '', '');
INSERT INTO `cmf_admin_menu` VALUES ('286', '268', '1', '1', '9', 'Admin', 'Live', 'index', '', '直播记录', '', '');
INSERT INTO `cmf_admin_menu` VALUES ('287', '284', '1', '0', '10000', 'Admin', 'Gift', 'add', '', '礼物添加', '', '');
INSERT INTO `cmf_admin_menu` VALUES ('288', '287', '1', '0', '10000', 'Admin', 'Gift', 'add_post', '', '添加保存', '', '');
INSERT INTO `cmf_admin_menu` VALUES ('289', '284', '1', '0', '10000', 'Admin', 'Gift', 'edit', '', '礼物修改', '', '');
INSERT INTO `cmf_admin_menu` VALUES ('290', '289', '1', '0', '10000', 'Admin', 'Gift', 'edit_post', '', '编辑保存', '', '');
INSERT INTO `cmf_admin_menu` VALUES ('291', '284', '1', '0', '10000', 'Admin', 'Gift', 'del', '', '礼物删除', '', '');
INSERT INTO `cmf_admin_menu` VALUES ('292', '284', '1', '0', '10000', 'Admin', 'Gift', 'listorders', '', '礼物排序', '', '');
INSERT INTO `cmf_admin_menu` VALUES ('293', '285', '1', '1', '10000', 'Admin', 'Reportcat', 'index', '', '直播间举报类型', '', '');
INSERT INTO `cmf_admin_menu` VALUES ('294', '285', '1', '1', '10000', 'Admin', 'Livereport', 'index', '', '直播间举报列表', '', '');
INSERT INTO `cmf_admin_menu` VALUES ('295', '293', '1', '0', '10000', 'Admin', 'Reportcat', 'add', '', '举报类型添加', '', '');
INSERT INTO `cmf_admin_menu` VALUES ('296', '293', '1', '0', '10000', 'Admin', 'Reportcat', 'add_post', '', '添加保存', '', '');
INSERT INTO `cmf_admin_menu` VALUES ('297', '293', '1', '0', '10000', 'Admin', 'Reportcat', 'edit', '', '举报类型编辑', '', '');
INSERT INTO `cmf_admin_menu` VALUES ('298', '293', '1', '0', '10000', 'Admin', 'Reportcat', 'edit_post', '', '编辑保存', '', '');
INSERT INTO `cmf_admin_menu` VALUES ('299', '293', '1', '0', '10000', 'Admin', 'Reportcat', 'listorders', '', '排序', '', '');
INSERT INTO `cmf_admin_menu` VALUES ('300', '293', '1', '0', '10000', 'Admin', 'Reportcat', 'del', '', '删除', '', '');
INSERT INTO `cmf_admin_menu` VALUES ('301', '294', '1', '0', '10000', 'Admin', 'Livereport', 'setstatus', '', '标记处理', '', '');
INSERT INTO `cmf_admin_menu` VALUES ('302', '260', '1', '1', '3', 'Admin', 'Chargerules', 'index', '', '钻石充值规则', '', '');
INSERT INTO `cmf_admin_menu` VALUES ('303', '302', '1', '0', '1', 'Admin', 'Chargerules', 'add', '', '添加钻石充值规则', '', '');
INSERT INTO `cmf_admin_menu` VALUES ('304', '303', '1', '0', '10000', 'Admin', 'Chargerules', 'add_post', '', '添加保存', '', '');
INSERT INTO `cmf_admin_menu` VALUES ('305', '302', '1', '0', '2', 'Admin', 'Chargerules', 'edit', '', '编辑钻石充值规则', '', '');
INSERT INTO `cmf_admin_menu` VALUES ('306', '305', '1', '0', '10000', 'Admin', 'Chargerules', 'edit_post', '', '编辑提交', '', '');
INSERT INTO `cmf_admin_menu` VALUES ('307', '302', '1', '0', '3', 'Admin', 'Chargerules', 'listorderset', '', '排序', '', '');
INSERT INTO `cmf_admin_menu` VALUES ('308', '302', '1', '0', '4', 'Admin', 'Chargerules', 'del', '', '删除', '', '');
INSERT INTO `cmf_admin_menu` VALUES ('309', '260', '1', '1', '4', 'Admin', 'Vipchargerules', 'index', '', 'VIP充值规则', '', '');
INSERT INTO `cmf_admin_menu` VALUES ('310', '309', '1', '0', '1', 'Admin', 'Vipchargerules', 'add', '', '添加', '', '');
INSERT INTO `cmf_admin_menu` VALUES ('311', '310', '1', '0', '10000', 'Admin', 'Vipchargerules', 'add_post', '', '添加保存', '', '');
INSERT INTO `cmf_admin_menu` VALUES ('312', '309', '1', '0', '2', 'Admin', 'Vipchargerules', 'edit', '', '编辑', '', '');
INSERT INTO `cmf_admin_menu` VALUES ('313', '312', '1', '0', '10000', 'Admin', 'Vipchargerules', 'edit_post', '', '编辑保存', '', '');
INSERT INTO `cmf_admin_menu` VALUES ('314', '309', '1', '0', '3', 'Admin', 'Vipchargerules', 'listorderset', '', '排序', '', '');
INSERT INTO `cmf_admin_menu` VALUES ('315', '309', '1', '0', '4', 'Admin', 'Vipchargerules', 'del', '', '删除', '', '');
INSERT INTO `cmf_admin_menu` VALUES ('316', '260', '1', '1', '5', 'Admin', 'Charge', 'index', '', '钻石充值记录', '', '');
INSERT INTO `cmf_admin_menu` VALUES ('317', '316', '1', '0', '1', 'Admin', 'Charge', 'export', '', '导出', '', '');
INSERT INTO `cmf_admin_menu` VALUES ('318', '316', '1', '0', '2', 'Admin', 'Charge', 'setPay', '', '确认支付', '', '');
INSERT INTO `cmf_admin_menu` VALUES ('319', '260', '1', '1', '6', 'Admin', 'Vipcharge', 'index', '', 'VIP充值记录', '', '');
INSERT INTO `cmf_admin_menu` VALUES ('320', '319', '1', '0', '2', 'Admin', 'Vipcharge', 'export', '', '导出', '', '');
INSERT INTO `cmf_admin_menu` VALUES ('321', '261', '1', '0', '10000', 'Admin', 'Cash', 'export', '', '导出提现记录', '', '');
INSERT INTO `cmf_admin_menu` VALUES ('322', '260', '1', '1', '7', 'Admin', 'Votesrecord', 'index', '', '收入记录', '', '');
INSERT INTO `cmf_admin_menu` VALUES ('323', '143', '1', '0', '10000', 'Admin', 'Userauth', 'edit', '', '编辑', '', '');
INSERT INTO `cmf_admin_menu` VALUES ('324', '143', '1', '0', '10000', 'Admin', 'Userauth', 'edit_post', '', '编辑保存', '', '');
INSERT INTO `cmf_admin_menu` VALUES ('325', '143', '1', '0', '10000', 'Admin', 'Userauth', 'del', '', '删除身份认证', '', '');
INSERT INTO `cmf_admin_menu` VALUES ('326', '124', '1', '0', '1', 'User', 'AdminIndex', 'super', '', '设置超管', '', '');
INSERT INTO `cmf_admin_menu` VALUES ('327', '124', '1', '0', '2', 'User', 'AdminIndex', 'cancelsuper', '', '取消超管', '', '');
INSERT INTO `cmf_admin_menu` VALUES ('328', '124', '1', '0', '3', 'User', 'AdminIndex', 'setvip', '', '设置vip', '', '');
INSERT INTO `cmf_admin_menu` VALUES ('329', '124', '1', '0', '4', 'User', 'AdminIndex', 'setvip_post', '', '设置vip提交', '', '');
INSERT INTO `cmf_admin_menu` VALUES ('330', '124', '1', '0', '5', 'User', 'AdminIndex', 'edit', '', '编辑', '', '');
INSERT INTO `cmf_admin_menu` VALUES ('331', '124', '1', '0', '6', 'User', 'AdminIndex', 'editPost', '', '编辑提交', '', '');
INSERT INTO `cmf_admin_menu` VALUES ('332', '124', '1', '0', '7', 'User', 'AdminIndex', 'del', '', '删除', '', '');
INSERT INTO `cmf_admin_menu` VALUES ('333', '165', '1', '0', '10000', 'Admin', 'Advert', 'add_post', '', '广告添加保存', '', '');
INSERT INTO `cmf_admin_menu` VALUES ('334', '267', '1', '0', '10000', 'Admin', 'Videoclass', 'add', '', '视频分类添加', '', '');
INSERT INTO `cmf_admin_menu` VALUES ('335', '334', '1', '0', '10000', 'Admin', 'Videoclass', 'add_post', '', '添加保存', '', '');
INSERT INTO `cmf_admin_menu` VALUES ('336', '267', '1', '0', '10000', 'Admin', 'Videoclass', 'edit', '', '编辑分类', '', '');
INSERT INTO `cmf_admin_menu` VALUES ('337', '336', '1', '0', '10000', 'Admin', 'Videoclass', 'edit_post', '', '编辑保存', '', '');
INSERT INTO `cmf_admin_menu` VALUES ('338', '267', '1', '0', '10000', 'Admin', 'Videoclass', 'del', '', '删除视频分类', '', '');
INSERT INTO `cmf_admin_menu` VALUES ('339', '267', '1', '0', '10000', 'Admin', 'Videoclass', 'listordersset', '', '视频分类排序', '', '');
INSERT INTO `cmf_admin_menu` VALUES ('340', '319', '1', '0', '1', 'Admin', 'Vipcharge', 'setPay', '', '确认支付', '', '');
INSERT INTO `cmf_admin_menu` VALUES ('341', '322', '1', '0', '1', 'Admin', 'Votesrecord', 'export', '', '导出收入记录', '', '');
INSERT INTO `cmf_admin_menu` VALUES ('342', '260', '1', '1', '10000', 'Admin', 'Coinrecord', 'index', '', '钻石消费记录', '', '');
INSERT INTO `cmf_admin_menu` VALUES ('343', '133', '1', '0', '10000', 'Admin', 'Setting', 'configpriPost', '', '私密设置保存', '', '');
INSERT INTO `cmf_admin_menu` VALUES ('344', '227', '1', '0', '10000', 'admin', 'Shopgoods', 'setstatus', '', '上架或下架', '', '');
INSERT INTO `cmf_admin_menu` VALUES ('345', '227', '1', '0', '10000', 'admin', 'Shopgoods', 'edit', '', '审核或详情', '', '');
INSERT INTO `cmf_admin_menu` VALUES ('346', '345', '1', '0', '10000', 'admin', 'Shopgoods', 'editPost', '', '提交', '', '');
INSERT INTO `cmf_admin_menu` VALUES ('347', '342', '1', '0', '10000', 'admin', 'Coinrecord', 'export', '', '导出', '', '');
INSERT INTO `cmf_admin_menu` VALUES ('348', '0', '1', '1', '10000', 'admin', 'Turntable', 'default', '', '大转盘', '', '');
INSERT INTO `cmf_admin_menu` VALUES ('349', '348', '1', '1', '10000', 'admin', 'Turntablecon', 'index', '', '价格管理', '', '');
INSERT INTO `cmf_admin_menu` VALUES ('350', '348', '1', '1', '10000', 'admin', 'Turntable', 'index', '', '奖品管理', '', '');
INSERT INTO `cmf_admin_menu` VALUES ('351', '348', '1', '1', '10000', 'admin', 'Turntable', 'index2', '', '转盘记录', '', '');
INSERT INTO `cmf_admin_menu` VALUES ('352', '348', '1', '1', '10000', 'admin', 'Turntable', 'index3', '', '线下奖品', '', '');
INSERT INTO `cmf_admin_menu` VALUES ('353', '349', '1', '0', '10000', 'admin', 'Turntablecon', 'edit', '', '编辑', '', '');
INSERT INTO `cmf_admin_menu` VALUES ('354', '349', '1', '0', '10000', 'admin', 'Turntablecon', 'editPost', '', '编辑提交', '', '');
INSERT INTO `cmf_admin_menu` VALUES ('355', '349', '1', '0', '10000', 'admin', 'Turntablecon', 'listOrder', '', '排序', '', '');
INSERT INTO `cmf_admin_menu` VALUES ('356', '350', '1', '0', '10000', 'admin', 'Turntable', 'edit', '', '编辑', '', '');
INSERT INTO `cmf_admin_menu` VALUES ('357', '350', '1', '0', '10000', 'admin', 'Turntable', 'editPost', '', '编辑提交', '', '');
INSERT INTO `cmf_admin_menu` VALUES ('358', '352', '1', '0', '10000', 'admin', 'Turntable', 'setstatus', '', '处理', '', '');
INSERT INTO `cmf_admin_menu` VALUES ('359', '0', '1', '1', '10000', 'admin', 'Guard', 'index', '', '守护管理', '', '');
INSERT INTO `cmf_admin_menu` VALUES ('360', '359', '1', '0', '10000', 'admin', 'Guard', 'edit', '', '编辑', '', '');
INSERT INTO `cmf_admin_menu` VALUES ('361', '359', '1', '0', '10000', 'admin', 'Guard', 'editPost', '', '编辑提交', '', '');
INSERT INTO `cmf_admin_menu` VALUES ('362', '190', '1', '0', '10000', 'admin', 'Video', 'setBatchXiajia', '', '批量下架', '', '');
INSERT INTO `cmf_admin_menu` VALUES ('363', '190', '1', '0', '10000', 'admin', 'Video', 'setBatchDel', '', '批量删除', '', '');
INSERT INTO `cmf_admin_menu` VALUES ('364', '190', '1', '0', '10000', 'admin', 'Video', 'setBatchStatus', '', '批量通过/拒绝', '', '');
INSERT INTO `cmf_admin_menu` VALUES ('365', '222', '1', '1', '1', 'Admin', 'Goodsclass', 'index', '', '商品分类列表', '', '');
INSERT INTO `cmf_admin_menu` VALUES ('366', '365', '1', '0', '10000', 'Admin', 'Goodsclass', 'add', '', '商品分类添加', '', '');
INSERT INTO `cmf_admin_menu` VALUES ('367', '366', '1', '0', '10000', 'Admin', 'Goodsclass', 'addPost', '', '商品分类添加保存', '', '');
INSERT INTO `cmf_admin_menu` VALUES ('368', '365', '1', '0', '10000', 'Admin', 'Goodsclass', 'edit', '', '商品分类编辑', '', '');
INSERT INTO `cmf_admin_menu` VALUES ('369', '368', '1', '0', '10000', 'Admin', 'Goodsclass', 'editPost', '', '商品分类编辑保存', '', '');
INSERT INTO `cmf_admin_menu` VALUES ('370', '365', '1', '0', '10000', 'Admin', 'Goodsclass', 'del', '', '商品分类删除', '', '');
INSERT INTO `cmf_admin_menu` VALUES ('371', '222', '1', '1', '2', 'Admin', 'shopbond', 'index', '', '保证金', '', '');
INSERT INTO `cmf_admin_menu` VALUES ('372', '371', '1', '0', '10000', 'Admin', 'shopbond', 'setstatus', '', '保证金处理', '', '');
INSERT INTO `cmf_admin_menu` VALUES ('373', '222', '1', '1', '5', 'Admin', 'Buyeraddress', 'index', '', '收货地址管理', '', '');
INSERT INTO `cmf_admin_menu` VALUES ('374', '373', '1', '0', '10000', 'Admin', 'Buyeraddress', 'del', '', '收货地址删除', '', '');
INSERT INTO `cmf_admin_menu` VALUES ('375', '222', '1', '1', '6', 'Admin', 'Express', 'index', '', '物流公司列表', '', '');
INSERT INTO `cmf_admin_menu` VALUES ('376', '375', '1', '0', '10000', 'Admin', 'Express', 'add', '', '物流公司添加', '', '');
INSERT INTO `cmf_admin_menu` VALUES ('377', '376', '1', '0', '10000', 'Admin', 'Express', 'add_post', '', '物流公司添加保存', '', '');
INSERT INTO `cmf_admin_menu` VALUES ('378', '375', '1', '0', '10000', 'Admin', 'Express', 'edit', '', '物流公司编辑', '', '');
INSERT INTO `cmf_admin_menu` VALUES ('379', '378', '1', '0', '10000', 'Admin', 'Express', 'edit_post', '', '物流公司编辑保存', '', '');
INSERT INTO `cmf_admin_menu` VALUES ('380', '375', '1', '0', '10000', 'Admin', 'Express', 'del', '', '物流公司删除', '', '');
INSERT INTO `cmf_admin_menu` VALUES ('381', '375', '1', '0', '10000', 'Admin', 'Express', 'listOrder', '', '物流公司排序', '', '');
INSERT INTO `cmf_admin_menu` VALUES ('382', '222', '1', '1', '7', 'Admin', 'Refundreason', 'index', '', '买家申请退款原因', '', '');
INSERT INTO `cmf_admin_menu` VALUES ('383', '382', '1', '0', '10000', 'Admin', 'Refundreason', 'add', '', '添加退款原因', '', '');
INSERT INTO `cmf_admin_menu` VALUES ('384', '383', '1', '0', '10000', 'Admin', 'Refundreason', 'add_post', '', '退款原因添加保存', '', '');
INSERT INTO `cmf_admin_menu` VALUES ('385', '382', '1', '0', '10000', 'Admin', 'Refundreason', 'edit', '', '编辑退款原因', '', '');
INSERT INTO `cmf_admin_menu` VALUES ('386', '385', '1', '0', '10000', 'Admin', 'Refundreason', 'edit_post', '', '退款原因编辑保存', '', '');
INSERT INTO `cmf_admin_menu` VALUES ('387', '382', '1', '0', '10000', 'Admin', 'Refundreason', 'del', '', '删除退款原因', '', '');
INSERT INTO `cmf_admin_menu` VALUES ('388', '382', '1', '0', '10000', 'Admin', 'Refundreason', 'listOrder', '', '排序', '', '');
INSERT INTO `cmf_admin_menu` VALUES ('389', '222', '1', '1', '8', 'Admin', 'Refusereason', 'index', '', '卖家拒绝退款原因', '', '');
INSERT INTO `cmf_admin_menu` VALUES ('390', '389', '1', '0', '10000', 'Admin', 'Refusereason', 'add', '', '拒绝退款原因添加', '', '');
INSERT INTO `cmf_admin_menu` VALUES ('391', '390', '1', '0', '10000', 'Admin', 'Refusereason', 'add_post', '', '拒绝退款原因添加保存', '', '');
INSERT INTO `cmf_admin_menu` VALUES ('392', '389', '1', '0', '10000', 'Admin', 'Refusereason', 'edit', '', '拒绝退款原因编辑', '', '');
INSERT INTO `cmf_admin_menu` VALUES ('393', '389', '1', '0', '10000', 'Admin', 'Refusereason', 'edit_post', '', '拒绝退款原因编辑保存', '', '');
INSERT INTO `cmf_admin_menu` VALUES ('394', '382', '1', '0', '10000', 'Admin', 'Refusereason', 'del', '', '拒绝退款原因删除', '', '');
INSERT INTO `cmf_admin_menu` VALUES ('395', '389', '1', '0', '10000', 'Admin', 'Refusereason', 'listOrder', '', '拒绝退款原因排序', '', '');
INSERT INTO `cmf_admin_menu` VALUES ('396', '222', '1', '1', '9', 'Admin', 'Platformreason', 'index', '', '退款平台介入原因', '', '');
INSERT INTO `cmf_admin_menu` VALUES ('397', '396', '1', '0', '10000', 'Admin', 'Platformreason', 'add', '', '添加平台介入原因', '', '');
INSERT INTO `cmf_admin_menu` VALUES ('398', '397', '1', '0', '10000', 'Admin', 'Platformreason', 'add_post', '', '平台介入原因添加保存', '', '');
INSERT INTO `cmf_admin_menu` VALUES ('399', '396', '1', '0', '10000', 'Admin', 'Platformreason', 'edot', '', '编辑平台介入原因', '', '');
INSERT INTO `cmf_admin_menu` VALUES ('400', '399', '1', '0', '10000', 'Admin', 'Platformreason', 'edit_post', '', '平台介入原因添加保存', '', '');
INSERT INTO `cmf_admin_menu` VALUES ('401', '396', '1', '0', '10000', 'Admin', 'Platformreason', 'listOrder', '', '平台介入原因排序', '', '');
INSERT INTO `cmf_admin_menu` VALUES ('402', '396', '1', '0', '10000', 'Admin', 'Platformreason', 'del', '', '删除平台介入原因', '', '');
INSERT INTO `cmf_admin_menu` VALUES ('403', '222', '1', '1', '10', 'Admin', 'Goodsorder', 'index', '', '商品订单列表', '', '');
INSERT INTO `cmf_admin_menu` VALUES ('404', '403', '1', '0', '10000', 'Admin', 'Goodsorder', 'info', '', '订单详情', '', '');
INSERT INTO `cmf_admin_menu` VALUES ('405', '403', '1', '0', '10000', 'Admin', 'Goodsorder', 'setexpress', '', '填写物流', '', '');
INSERT INTO `cmf_admin_menu` VALUES ('406', '405', '1', '0', '10000', 'Admin', 'Goodsorder', 'setexpresspost', '', '填写物流提交', '', '');
INSERT INTO `cmf_admin_menu` VALUES ('407', '222', '1', '1', '10000', 'Admin', 'Refundlist', 'index', '', '退款列表', '', '');
INSERT INTO `cmf_admin_menu` VALUES ('408', '407', '1', '0', '10000', 'Admin', 'Refundlist', 'edit', '', '编辑', '', '');
INSERT INTO `cmf_admin_menu` VALUES ('409', '408', '1', '0', '10000', 'Admin', 'Refundlist', 'edit_post', '', '编辑提交', '', '');
INSERT INTO `cmf_admin_menu` VALUES ('410', '407', '1', '0', '10000', 'Admin', 'refundlist', 'platformedit', '', '平台自营处理退款', '', '');
INSERT INTO `cmf_admin_menu` VALUES ('411', '222', '1', '1', '10000', 'Admin', 'Shopcash', 'index', '', '提现记录', '', '');
INSERT INTO `cmf_admin_menu` VALUES ('412', '411', '1', '0', '10000', 'Admin', 'Shopcash', 'edit', '', '编辑提现记录', '', '');
INSERT INTO `cmf_admin_menu` VALUES ('413', '412', '1', '0', '10000', 'Admin', 'Shopcash', 'editPost', '', '编辑提交', '', '');
INSERT INTO `cmf_admin_menu` VALUES ('414', '411', '1', '0', '10000', 'Admin', 'Shopcash', 'export', '', '导出提现记录', '', '');
INSERT INTO `cmf_admin_menu` VALUES ('415', '222', '1', '1', '10000', 'Admin', 'Balance', 'index', '', '余额手动充值', '', '');
INSERT INTO `cmf_admin_menu` VALUES ('416', '415', '1', '0', '10000', 'Admin', 'Balance', 'add', '', '充值添加', '', '');
INSERT INTO `cmf_admin_menu` VALUES ('417', '416', '1', '0', '10000', 'Admin', 'Balance', 'addPost', '', '余额充值保存', '', '');
INSERT INTO `cmf_admin_menu` VALUES ('418', '415', '1', '0', '10000', 'Admin', 'Balance', 'export', '', '余额充值记录导出', '', '');
INSERT INTO `cmf_admin_menu` VALUES ('419', '222', '1', '1', '10000', 'Admin', 'Shopcategory', 'index', '', ' 经营类目申请列表', '', '');
INSERT INTO `cmf_admin_menu` VALUES ('420', '419', '1', '0', '10000', 'Admin', 'Shopcategory', 'edit', '', '编辑经营类目申请', '', '');
INSERT INTO `cmf_admin_menu` VALUES ('421', '420', '1', '0', '10000', 'Admin', 'Shopcategory', 'editPost', '', ' 编辑提交', '', '');
INSERT INTO `cmf_admin_menu` VALUES ('422', '419', '1', '0', '10000', 'Admin', 'Shopcategory', 'del', '', '删除类目申请记录', '', '');
INSERT INTO `cmf_admin_menu` VALUES ('423', '268', '1', '1', '1', 'Admin', 'Liveclass', 'index', '', '直播分类', '', '');
INSERT INTO `cmf_admin_menu` VALUES ('424', '423', '1', '0', '10000', 'Admin', 'Liveclass', 'add', '', '添加直播分类', '', '');
INSERT INTO `cmf_admin_menu` VALUES ('425', '424', '1', '0', '10000', 'Admin', 'Liveclass', 'addPost', '', '直播分类添加保存', '', '');
INSERT INTO `cmf_admin_menu` VALUES ('426', '423', '1', '0', '10000', 'Admin', 'Liveclass', 'edit', '', '编辑直播分类', '', '');
INSERT INTO `cmf_admin_menu` VALUES ('427', '426', '1', '0', '10000', 'Admin', 'Liveclass', 'editPost', '', '直播分类编辑提交', '', '');
INSERT INTO `cmf_admin_menu` VALUES ('428', '423', '1', '0', '10000', 'Admin', 'Liveclass', 'del', '', '删除直播分类', '', '');
INSERT INTO `cmf_admin_menu` VALUES ('429', '423', '1', '0', '10000', 'Admin', 'Liveclass', 'listOrder', '', '直播分类排序', '', '');

-- ----------------------------
-- Table structure for `cmf_admin_push`
-- ----------------------------
DROP TABLE IF EXISTS `cmf_admin_push`;
CREATE TABLE `cmf_admin_push` (
  `id` int(12) NOT NULL AUTO_INCREMENT COMMENT '自增ID',
  `title` varchar(255) DEFAULT '' COMMENT '标题',
  `synopsis` varchar(255) DEFAULT '' COMMENT '简介',
  `type` tinyint(1) DEFAULT '1' COMMENT '消息类型 1文本类型 2 链接类型',
  `content` longtext COMMENT '内容',
  `url` varchar(255) DEFAULT '' COMMENT '外链地址',
  `admin` varchar(255) DEFAULT '' COMMENT '发布者账号',
  `addtime` int(12) DEFAULT '0' COMMENT '发布时间',
  `ip` varchar(255) DEFAULT '' COMMENT 'IP地址',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 ROW_FORMAT=COMPACT;

-- ----------------------------
-- Records of cmf_admin_push
-- ----------------------------

-- ----------------------------
-- Table structure for `cmf_agent`
-- ----------------------------
DROP TABLE IF EXISTS `cmf_agent`;
CREATE TABLE `cmf_agent` (
  `uid` bigint(20) NOT NULL DEFAULT '0' COMMENT '用户ID',
  `one` bigint(20) NOT NULL DEFAULT '0' COMMENT '上一级',
  `two` bigint(20) NOT NULL DEFAULT '0' COMMENT '上二级',
  `three` bigint(20) NOT NULL DEFAULT '0' COMMENT '上三级',
  `addtime` int(11) NOT NULL DEFAULT '0' COMMENT '时间',
  PRIMARY KEY (`uid`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 ROW_FORMAT=COMPACT;

-- ----------------------------
-- Records of cmf_agent
-- ----------------------------

-- ----------------------------
-- Table structure for `cmf_agent_profit`
-- ----------------------------
DROP TABLE IF EXISTS `cmf_agent_profit`;
CREATE TABLE `cmf_agent_profit` (
  `uid` bigint(20) NOT NULL DEFAULT '0' COMMENT '用户ID',
  `one` bigint(20) NOT NULL DEFAULT '0' COMMENT '贡献上一级',
  `two` bigint(20) NOT NULL DEFAULT '0' COMMENT '贡献上二级',
  `three` bigint(20) NOT NULL DEFAULT '0' COMMENT '贡献上三级',
  `one_p` bigint(20) NOT NULL DEFAULT '0' COMMENT '下一级收益',
  `two_p` bigint(20) NOT NULL DEFAULT '0' COMMENT '下二级收益',
  `three_p` bigint(20) NOT NULL DEFAULT '0' COMMENT '下三级收益',
  PRIMARY KEY (`uid`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 ROW_FORMAT=COMPACT;

-- ----------------------------
-- Records of cmf_agent_profit
-- ----------------------------

-- ----------------------------
-- Table structure for `cmf_apply_goods_class`
-- ----------------------------
DROP TABLE IF EXISTS `cmf_apply_goods_class`;
CREATE TABLE `cmf_apply_goods_class` (
  `id` int(12) NOT NULL AUTO_INCREMENT,
  `uid` int(12) NOT NULL DEFAULT '0' COMMENT '用户id',
  `goods_classid` varchar(255) NOT NULL COMMENT '商品一级分类ID',
  `reason` text COMMENT '审核说明',
  `addtime` int(12) NOT NULL DEFAULT '0' COMMENT '提交时间',
  `uptime` int(12) NOT NULL DEFAULT '0' COMMENT '更新时间',
  `status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '状态 0 处理中 1 成功 2 失败',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Records of cmf_apply_goods_class
-- ----------------------------

-- ----------------------------
-- Table structure for `cmf_asset`
-- ----------------------------
DROP TABLE IF EXISTS `cmf_asset`;
CREATE TABLE `cmf_asset` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '用户id',
  `file_size` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '文件大小,单位B',
  `create_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '上传时间',
  `status` tinyint(3) unsigned NOT NULL DEFAULT '1' COMMENT '状态;1:可用,0:不可用',
  `download_times` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '下载次数',
  `file_key` varchar(64) CHARACTER SET utf8 NOT NULL DEFAULT '' COMMENT '文件惟一码',
  `filename` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '文件名',
  `file_path` varchar(100) CHARACTER SET utf8 NOT NULL DEFAULT '' COMMENT '文件路径,相对于upload目录,可以为url',
  `file_md5` varchar(32) CHARACTER SET utf8 NOT NULL DEFAULT '' COMMENT '文件md5值',
  `file_sha1` varchar(40) CHARACTER SET utf8 NOT NULL DEFAULT '',
  `suffix` varchar(10) NOT NULL DEFAULT '' COMMENT '文件后缀名,不包括点',
  `more` text COMMENT '其它详细信息,JSON格式',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 ROW_FORMAT=COMPACT COMMENT='资源表';

-- ----------------------------
-- Records of cmf_asset
-- ----------------------------

-- ----------------------------
-- Table structure for `cmf_auth_access`
-- ----------------------------
DROP TABLE IF EXISTS `cmf_auth_access`;
CREATE TABLE `cmf_auth_access` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `role_id` int(10) unsigned NOT NULL COMMENT '角色',
  `rule_name` varchar(100) NOT NULL DEFAULT '' COMMENT '规则唯一英文标识,全小写',
  `type` varchar(30) NOT NULL DEFAULT '' COMMENT '权限规则分类,请加应用前缀,如admin_',
  PRIMARY KEY (`id`) USING BTREE,
  KEY `role_id` (`role_id`) USING BTREE,
  KEY `rule_name` (`rule_name`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT COMMENT='权限授权表';

-- ----------------------------
-- Records of cmf_auth_access
-- ----------------------------

-- ----------------------------
-- Table structure for `cmf_auth_rule`
-- ----------------------------
DROP TABLE IF EXISTS `cmf_auth_rule`;
CREATE TABLE `cmf_auth_rule` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '规则id,自增主键',
  `status` tinyint(3) unsigned NOT NULL DEFAULT '1' COMMENT '是否有效(0:无效,1:有效)',
  `app` varchar(40) NOT NULL DEFAULT '' COMMENT '规则所属app',
  `type` varchar(30) NOT NULL DEFAULT '' COMMENT '权限规则分类，请加应用前缀,如admin_',
  `name` varchar(100) NOT NULL DEFAULT '' COMMENT '规则唯一英文标识,全小写',
  `param` varchar(100) NOT NULL DEFAULT '' COMMENT '额外url参数',
  `title` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '规则描述',
  `condition` varchar(200) NOT NULL DEFAULT '' COMMENT '规则附加条件',
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE KEY `name` (`name`) USING BTREE,
  KEY `module` (`app`,`status`,`type`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=430 DEFAULT CHARSET=utf8mb4 ROW_FORMAT=COMPACT COMMENT='权限规则表';

-- ----------------------------
-- Records of cmf_auth_rule
-- ----------------------------
INSERT INTO `cmf_auth_rule` VALUES ('1', '1', 'admin', 'admin_url', 'admin/Hook/index', '', '钩子管理', '');
INSERT INTO `cmf_auth_rule` VALUES ('2', '1', 'admin', 'admin_url', 'admin/Hook/plugins', '', '钩子插件管理', '');
INSERT INTO `cmf_auth_rule` VALUES ('3', '1', 'admin', 'admin_url', 'admin/Hook/pluginListOrder', '', '钩子插件排序', '');
INSERT INTO `cmf_auth_rule` VALUES ('4', '1', 'admin', 'admin_url', 'admin/Hook/sync', '', '同步钩子', '');
INSERT INTO `cmf_auth_rule` VALUES ('5', '1', 'admin', 'admin_url', 'admin/Link/index', '', '友情链接', '');
INSERT INTO `cmf_auth_rule` VALUES ('6', '1', 'admin', 'admin_url', 'admin/Link/add', '', '添加友情链接', '');
INSERT INTO `cmf_auth_rule` VALUES ('7', '1', 'admin', 'admin_url', 'admin/Link/addPost', '', '添加友情链接提交保存', '');
INSERT INTO `cmf_auth_rule` VALUES ('8', '1', 'admin', 'admin_url', 'admin/Link/edit', '', '编辑友情链接', '');
INSERT INTO `cmf_auth_rule` VALUES ('9', '1', 'admin', 'admin_url', 'admin/Link/editPost', '', '编辑友情链接提交保存', '');
INSERT INTO `cmf_auth_rule` VALUES ('10', '1', 'admin', 'admin_url', 'admin/Link/delete', '', '删除友情链接', '');
INSERT INTO `cmf_auth_rule` VALUES ('11', '1', 'admin', 'admin_url', 'admin/Link/listOrder', '', '友情链接排序', '');
INSERT INTO `cmf_auth_rule` VALUES ('12', '1', 'admin', 'admin_url', 'admin/Link/toggle', '', '友情链接显示隐藏', '');
INSERT INTO `cmf_auth_rule` VALUES ('13', '1', 'admin', 'admin_url', 'admin/Mailer/index', '', '邮箱配置', '');
INSERT INTO `cmf_auth_rule` VALUES ('14', '1', 'admin', 'admin_url', 'admin/Mailer/indexPost', '', '邮箱配置提交保存', '');
INSERT INTO `cmf_auth_rule` VALUES ('15', '1', 'admin', 'admin_url', 'admin/Mailer/template', '', '邮件模板', '');
INSERT INTO `cmf_auth_rule` VALUES ('16', '1', 'admin', 'admin_url', 'admin/Mailer/templatePost', '', '邮件模板提交', '');
INSERT INTO `cmf_auth_rule` VALUES ('17', '1', 'admin', 'admin_url', 'admin/Mailer/test', '', '邮件发送测试', '');
INSERT INTO `cmf_auth_rule` VALUES ('18', '1', 'admin', 'admin_url', 'admin/Menu/index', '', '后台菜单', '');
INSERT INTO `cmf_auth_rule` VALUES ('19', '1', 'admin', 'admin_url', 'admin/Menu/lists', '', '所有菜单', '');
INSERT INTO `cmf_auth_rule` VALUES ('20', '1', 'admin', 'admin_url', 'admin/Menu/add', '', '后台菜单添加', '');
INSERT INTO `cmf_auth_rule` VALUES ('21', '1', 'admin', 'admin_url', 'admin/Menu/addPost', '', '后台菜单添加提交保存', '');
INSERT INTO `cmf_auth_rule` VALUES ('22', '1', 'admin', 'admin_url', 'admin/Menu/edit', '', '后台菜单编辑', '');
INSERT INTO `cmf_auth_rule` VALUES ('23', '1', 'admin', 'admin_url', 'admin/Menu/editPost', '', '后台菜单编辑提交保存', '');
INSERT INTO `cmf_auth_rule` VALUES ('24', '1', 'admin', 'admin_url', 'admin/Menu/delete', '', '后台菜单删除', '');
INSERT INTO `cmf_auth_rule` VALUES ('25', '1', 'admin', 'admin_url', 'admin/Menu/listOrder', '', '后台菜单排序', '');
INSERT INTO `cmf_auth_rule` VALUES ('26', '1', 'admin', 'admin_url', 'admin/Menu/getActions', '', '导入新后台菜单', '');
INSERT INTO `cmf_auth_rule` VALUES ('27', '1', 'admin', 'admin_url', 'admin/Nav/index', '', '导航管理', '');
INSERT INTO `cmf_auth_rule` VALUES ('28', '1', 'admin', 'admin_url', 'admin/Nav/add', '', '添加导航', '');
INSERT INTO `cmf_auth_rule` VALUES ('29', '1', 'admin', 'admin_url', 'admin/Nav/addPost', '', '添加导航提交保存', '');
INSERT INTO `cmf_auth_rule` VALUES ('30', '1', 'admin', 'admin_url', 'admin/Nav/edit', '', '编辑导航', '');
INSERT INTO `cmf_auth_rule` VALUES ('31', '1', 'admin', 'admin_url', 'admin/Nav/editPost', '', '编辑导航提交保存', '');
INSERT INTO `cmf_auth_rule` VALUES ('32', '1', 'admin', 'admin_url', 'admin/Nav/delete', '', '删除导航', '');
INSERT INTO `cmf_auth_rule` VALUES ('33', '1', 'admin', 'admin_url', 'admin/NavMenu/index', '', '导航菜单', '');
INSERT INTO `cmf_auth_rule` VALUES ('34', '1', 'admin', 'admin_url', 'admin/NavMenu/add', '', '添加导航菜单', '');
INSERT INTO `cmf_auth_rule` VALUES ('35', '1', 'admin', 'admin_url', 'admin/NavMenu/addPost', '', '添加导航菜单提交保存', '');
INSERT INTO `cmf_auth_rule` VALUES ('36', '1', 'admin', 'admin_url', 'admin/NavMenu/edit', '', '编辑导航菜单', '');
INSERT INTO `cmf_auth_rule` VALUES ('37', '1', 'admin', 'admin_url', 'admin/NavMenu/editPost', '', '编辑导航菜单提交保存', '');
INSERT INTO `cmf_auth_rule` VALUES ('38', '1', 'admin', 'admin_url', 'admin/NavMenu/delete', '', '删除导航菜单', '');
INSERT INTO `cmf_auth_rule` VALUES ('39', '1', 'admin', 'admin_url', 'admin/NavMenu/listOrder', '', '导航菜单排序', '');
INSERT INTO `cmf_auth_rule` VALUES ('40', '1', 'admin', 'admin_url', 'admin/Plugin/default', '', '插件中心', '');
INSERT INTO `cmf_auth_rule` VALUES ('41', '1', 'admin', 'admin_url', 'admin/Plugin/index', '', '插件列表', '');
INSERT INTO `cmf_auth_rule` VALUES ('42', '1', 'admin', 'admin_url', 'admin/Plugin/toggle', '', '插件启用禁用', '');
INSERT INTO `cmf_auth_rule` VALUES ('43', '1', 'admin', 'admin_url', 'admin/Plugin/setting', '', '插件设置', '');
INSERT INTO `cmf_auth_rule` VALUES ('44', '1', 'admin', 'admin_url', 'admin/Plugin/settingPost', '', '插件设置提交', '');
INSERT INTO `cmf_auth_rule` VALUES ('45', '1', 'admin', 'admin_url', 'admin/Plugin/install', '', '插件安装', '');
INSERT INTO `cmf_auth_rule` VALUES ('46', '1', 'admin', 'admin_url', 'admin/Plugin/update', '', '插件更新', '');
INSERT INTO `cmf_auth_rule` VALUES ('47', '1', 'admin', 'admin_url', 'admin/Plugin/uninstall', '', '卸载插件', '');
INSERT INTO `cmf_auth_rule` VALUES ('48', '1', 'admin', 'admin_url', 'admin/Rbac/index', '', '角色管理', '');
INSERT INTO `cmf_auth_rule` VALUES ('49', '1', 'admin', 'admin_url', 'admin/Rbac/roleAdd', '', '添加角色', '');
INSERT INTO `cmf_auth_rule` VALUES ('50', '1', 'admin', 'admin_url', 'admin/Rbac/roleAddPost', '', '添加角色提交', '');
INSERT INTO `cmf_auth_rule` VALUES ('51', '1', 'admin', 'admin_url', 'admin/Rbac/roleEdit', '', '编辑角色', '');
INSERT INTO `cmf_auth_rule` VALUES ('52', '1', 'admin', 'admin_url', 'admin/Rbac/roleEditPost', '', '编辑角色提交', '');
INSERT INTO `cmf_auth_rule` VALUES ('53', '1', 'admin', 'admin_url', 'admin/Rbac/roleDelete', '', '删除角色', '');
INSERT INTO `cmf_auth_rule` VALUES ('54', '1', 'admin', 'admin_url', 'admin/Rbac/authorize', '', '设置角色权限', '');
INSERT INTO `cmf_auth_rule` VALUES ('55', '1', 'admin', 'admin_url', 'admin/Rbac/authorizePost', '', '角色授权提交', '');
INSERT INTO `cmf_auth_rule` VALUES ('56', '1', 'admin', 'admin_url', 'admin/RecycleBin/index', '', '回收站', '');
INSERT INTO `cmf_auth_rule` VALUES ('57', '1', 'admin', 'admin_url', 'admin/RecycleBin/restore', '', '回收站还原', '');
INSERT INTO `cmf_auth_rule` VALUES ('58', '1', 'admin', 'admin_url', 'admin/RecycleBin/delete', '', '回收站彻底删除', '');
INSERT INTO `cmf_auth_rule` VALUES ('59', '1', 'admin', 'admin_url', 'admin/Route/index', '', 'URL美化', '');
INSERT INTO `cmf_auth_rule` VALUES ('60', '1', 'admin', 'admin_url', 'admin/Route/add', '', '添加路由规则', '');
INSERT INTO `cmf_auth_rule` VALUES ('61', '1', 'admin', 'admin_url', 'admin/Route/addPost', '', '添加路由规则提交', '');
INSERT INTO `cmf_auth_rule` VALUES ('62', '1', 'admin', 'admin_url', 'admin/Route/edit', '', '路由规则编辑', '');
INSERT INTO `cmf_auth_rule` VALUES ('63', '1', 'admin', 'admin_url', 'admin/Route/editPost', '', '路由规则编辑提交', '');
INSERT INTO `cmf_auth_rule` VALUES ('64', '1', 'admin', 'admin_url', 'admin/Route/delete', '', '路由规则删除', '');
INSERT INTO `cmf_auth_rule` VALUES ('65', '1', 'admin', 'admin_url', 'admin/Route/ban', '', '路由规则禁用', '');
INSERT INTO `cmf_auth_rule` VALUES ('66', '1', 'admin', 'admin_url', 'admin/Route/open', '', '路由规则启用', '');
INSERT INTO `cmf_auth_rule` VALUES ('67', '1', 'admin', 'admin_url', 'admin/Route/listOrder', '', '路由规则排序', '');
INSERT INTO `cmf_auth_rule` VALUES ('68', '1', 'admin', 'admin_url', 'admin/Route/select', '', '选择URL', '');
INSERT INTO `cmf_auth_rule` VALUES ('69', '1', 'admin', 'admin_url', 'admin/Setting/default', '', '设置', '');
INSERT INTO `cmf_auth_rule` VALUES ('70', '1', 'admin', 'admin_url', 'admin/Setting/site', '', '网站信息', '');
INSERT INTO `cmf_auth_rule` VALUES ('71', '1', 'admin', 'admin_url', 'admin/Setting/sitePost', '', '网站信息设置提交', '');
INSERT INTO `cmf_auth_rule` VALUES ('72', '1', 'admin', 'admin_url', 'admin/Setting/password', '', '密码修改', '');
INSERT INTO `cmf_auth_rule` VALUES ('73', '1', 'admin', 'admin_url', 'admin/Setting/passwordPost', '', '密码修改提交', '');
INSERT INTO `cmf_auth_rule` VALUES ('74', '1', 'admin', 'admin_url', 'admin/Setting/upload', '', '上传设置', '');
INSERT INTO `cmf_auth_rule` VALUES ('75', '1', 'admin', 'admin_url', 'admin/Setting/uploadPost', '', '上传设置提交', '');
INSERT INTO `cmf_auth_rule` VALUES ('76', '1', 'admin', 'admin_url', 'admin/Setting/clearCache', '', '清除缓存', '');
INSERT INTO `cmf_auth_rule` VALUES ('77', '1', 'admin', 'admin_url', 'admin/Slide/index', '', '幻灯片管理', '');
INSERT INTO `cmf_auth_rule` VALUES ('78', '1', 'admin', 'admin_url', 'admin/Slide/add', '', '添加幻灯片', '');
INSERT INTO `cmf_auth_rule` VALUES ('79', '1', 'admin', 'admin_url', 'admin/Slide/addPost', '', '添加幻灯片提交', '');
INSERT INTO `cmf_auth_rule` VALUES ('80', '1', 'admin', 'admin_url', 'admin/Slide/edit', '', '编辑幻灯片', '');
INSERT INTO `cmf_auth_rule` VALUES ('81', '1', 'admin', 'admin_url', 'admin/Slide/editPost', '', '编辑幻灯片提交', '');
INSERT INTO `cmf_auth_rule` VALUES ('82', '1', 'admin', 'admin_url', 'admin/Slide/delete', '', '删除幻灯片', '');
INSERT INTO `cmf_auth_rule` VALUES ('83', '1', 'admin', 'admin_url', 'admin/SlideItem/index', '', '幻灯片页面列表', '');
INSERT INTO `cmf_auth_rule` VALUES ('84', '1', 'admin', 'admin_url', 'admin/SlideItem/add', '', '幻灯片页面添加', '');
INSERT INTO `cmf_auth_rule` VALUES ('85', '1', 'admin', 'admin_url', 'admin/SlideItem/addPost', '', '幻灯片页面添加提交', '');
INSERT INTO `cmf_auth_rule` VALUES ('86', '1', 'admin', 'admin_url', 'admin/SlideItem/edit', '', '幻灯片页面编辑', '');
INSERT INTO `cmf_auth_rule` VALUES ('87', '1', 'admin', 'admin_url', 'admin/SlideItem/editPost', '', '幻灯片页面编辑提交', '');
INSERT INTO `cmf_auth_rule` VALUES ('88', '1', 'admin', 'admin_url', 'admin/SlideItem/delete', '', '幻灯片页面删除', '');
INSERT INTO `cmf_auth_rule` VALUES ('89', '1', 'admin', 'admin_url', 'admin/SlideItem/ban', '', '幻灯片页面隐藏', '');
INSERT INTO `cmf_auth_rule` VALUES ('90', '1', 'admin', 'admin_url', 'admin/SlideItem/cancelBan', '', '幻灯片页面显示', '');
INSERT INTO `cmf_auth_rule` VALUES ('91', '1', 'admin', 'admin_url', 'admin/SlideItem/listOrder', '', '幻灯片页面排序', '');
INSERT INTO `cmf_auth_rule` VALUES ('92', '1', 'admin', 'admin_url', 'admin/Storage/index', '', '文件存储', '');
INSERT INTO `cmf_auth_rule` VALUES ('93', '1', 'admin', 'admin_url', 'admin/Storage/settingPost', '', '文件存储设置提交', '');
INSERT INTO `cmf_auth_rule` VALUES ('94', '1', 'admin', 'admin_url', 'admin/Theme/index', '', '模板管理', '');
INSERT INTO `cmf_auth_rule` VALUES ('95', '1', 'admin', 'admin_url', 'admin/Theme/install', '', '安装模板', '');
INSERT INTO `cmf_auth_rule` VALUES ('96', '1', 'admin', 'admin_url', 'admin/Theme/uninstall', '', '卸载模板', '');
INSERT INTO `cmf_auth_rule` VALUES ('97', '1', 'admin', 'admin_url', 'admin/Theme/installTheme', '', '模板安装', '');
INSERT INTO `cmf_auth_rule` VALUES ('98', '1', 'admin', 'admin_url', 'admin/Theme/update', '', '模板更新', '');
INSERT INTO `cmf_auth_rule` VALUES ('99', '1', 'admin', 'admin_url', 'admin/Theme/active', '', '启用模板', '');
INSERT INTO `cmf_auth_rule` VALUES ('100', '1', 'admin', 'admin_url', 'admin/Theme/files', '', '模板文件列表', '');
INSERT INTO `cmf_auth_rule` VALUES ('101', '1', 'admin', 'admin_url', 'admin/Theme/fileSetting', '', '模板文件设置', '');
INSERT INTO `cmf_auth_rule` VALUES ('102', '1', 'admin', 'admin_url', 'admin/Theme/fileArrayData', '', '模板文件数组数据列表', '');
INSERT INTO `cmf_auth_rule` VALUES ('103', '1', 'admin', 'admin_url', 'admin/Theme/fileArrayDataEdit', '', '模板文件数组数据添加编辑', '');
INSERT INTO `cmf_auth_rule` VALUES ('104', '1', 'admin', 'admin_url', 'admin/Theme/fileArrayDataEditPost', '', '模板文件数组数据添加编辑提交保存', '');
INSERT INTO `cmf_auth_rule` VALUES ('105', '1', 'admin', 'admin_url', 'admin/Theme/fileArrayDataDelete', '', '模板文件数组数据删除', '');
INSERT INTO `cmf_auth_rule` VALUES ('106', '1', 'admin', 'admin_url', 'admin/Theme/settingPost', '', '模板文件编辑提交保存', '');
INSERT INTO `cmf_auth_rule` VALUES ('107', '1', 'admin', 'admin_url', 'admin/Theme/dataSource', '', '模板文件设置数据源', '');
INSERT INTO `cmf_auth_rule` VALUES ('108', '1', 'admin', 'admin_url', 'admin/Theme/design', '', '模板设计', '');
INSERT INTO `cmf_auth_rule` VALUES ('109', '1', 'admin', 'admin_url', 'admin/User/default', '', '管理组', '');
INSERT INTO `cmf_auth_rule` VALUES ('110', '1', 'admin', 'admin_url', 'admin/User/index', '', '管理员', '');
INSERT INTO `cmf_auth_rule` VALUES ('111', '1', 'admin', 'admin_url', 'admin/User/add', '', '管理员添加', '');
INSERT INTO `cmf_auth_rule` VALUES ('112', '1', 'admin', 'admin_url', 'admin/User/addPost', '', '管理员添加提交', '');
INSERT INTO `cmf_auth_rule` VALUES ('113', '1', 'admin', 'admin_url', 'admin/User/edit', '', '管理员编辑', '');
INSERT INTO `cmf_auth_rule` VALUES ('114', '1', 'admin', 'admin_url', 'admin/User/editPost', '', '管理员编辑提交', '');
INSERT INTO `cmf_auth_rule` VALUES ('115', '1', 'admin', 'admin_url', 'admin/User/userInfo', '', '个人信息', '');
INSERT INTO `cmf_auth_rule` VALUES ('116', '1', 'admin', 'admin_url', 'admin/User/userInfoPost', '', '管理员个人信息修改提交', '');
INSERT INTO `cmf_auth_rule` VALUES ('117', '1', 'admin', 'admin_url', 'admin/User/delete', '', '管理员删除', '');
INSERT INTO `cmf_auth_rule` VALUES ('118', '1', 'admin', 'admin_url', 'admin/User/ban', '', '停用管理员', '');
INSERT INTO `cmf_auth_rule` VALUES ('119', '1', 'admin', 'admin_url', 'admin/User/cancelBan', '', '启用管理员', '');
INSERT INTO `cmf_auth_rule` VALUES ('120', '1', 'user', 'admin_url', 'user/AdminAsset/index', '', '资源管理', '');
INSERT INTO `cmf_auth_rule` VALUES ('121', '1', 'user', 'admin_url', 'user/AdminAsset/delete', '', '删除文件', '');
INSERT INTO `cmf_auth_rule` VALUES ('122', '1', 'user', 'admin_url', 'user/AdminIndex/default', '', '用户管理', '');
INSERT INTO `cmf_auth_rule` VALUES ('123', '1', 'user', 'admin_url', 'user/AdminIndex/default1', '', '用户组', '');
INSERT INTO `cmf_auth_rule` VALUES ('124', '1', 'user', 'admin_url', 'user/AdminIndex/index', '', '本站用户', '');
INSERT INTO `cmf_auth_rule` VALUES ('125', '1', 'user', 'admin_url', 'user/AdminIndex/ban', '', '本站用户拉黑', '');
INSERT INTO `cmf_auth_rule` VALUES ('126', '1', 'user', 'admin_url', 'user/AdminIndex/cancelBan', '', '本站用户启用', '');
INSERT INTO `cmf_auth_rule` VALUES ('127', '1', 'user', 'admin_url', 'user/AdminOauth/index', '', '第三方用户', '');
INSERT INTO `cmf_auth_rule` VALUES ('128', '1', 'user', 'admin_url', 'user/AdminOauth/delete', '', '删除第三方用户绑定', '');
INSERT INTO `cmf_auth_rule` VALUES ('129', '1', 'user', 'admin_url', 'user/AdminUserAction/index', '', '用户操作管理', '');
INSERT INTO `cmf_auth_rule` VALUES ('130', '1', 'user', 'admin_url', 'user/AdminUserAction/edit', '', '编辑用户操作', '');
INSERT INTO `cmf_auth_rule` VALUES ('131', '1', 'user', 'admin_url', 'user/AdminUserAction/editPost', '', '编辑用户操作提交', '');
INSERT INTO `cmf_auth_rule` VALUES ('132', '1', 'user', 'admin_url', 'user/AdminUserAction/sync', '', '同步用户操作', '');
INSERT INTO `cmf_auth_rule` VALUES ('133', '1', 'admin', 'admin_url', 'admin/Setting/configpri', '', '私密设置', '');
INSERT INTO `cmf_auth_rule` VALUES ('134', '1', 'Admin', 'admin_url', 'Admin/Guide/set', '', '引导页', '');
INSERT INTO `cmf_auth_rule` VALUES ('135', '1', 'Admin', 'admin_url', 'Admin/Guide/set_post', '', '设置提交', '');
INSERT INTO `cmf_auth_rule` VALUES ('136', '1', 'Admin', 'admin_url', 'Admin/Guide/index', '', '管理', '');
INSERT INTO `cmf_auth_rule` VALUES ('137', '1', 'Admin', 'admin_url', 'Admin/Guide/add', '', '添加', '');
INSERT INTO `cmf_auth_rule` VALUES ('138', '1', 'Admin', 'admin_url', 'Admin/Guide/add_post', '', '添加提交', '');
INSERT INTO `cmf_auth_rule` VALUES ('139', '1', 'admin', 'admin_url', 'admin/Guide/edit', '', '编辑', '');
INSERT INTO `cmf_auth_rule` VALUES ('140', '1', 'admin', 'admin_url', 'admin/Guide/edit_post', '', '编辑提交', '');
INSERT INTO `cmf_auth_rule` VALUES ('141', '1', 'admin', 'admin_url', 'admin/Guide/listorders', '', '排序', '');
INSERT INTO `cmf_auth_rule` VALUES ('142', '1', 'admin', 'admin_url', 'admin/Guide/del', '', '删除', '');
INSERT INTO `cmf_auth_rule` VALUES ('143', '1', 'admin', 'admin_url', 'admin/Userauth/index', '', '身份认证', '');
INSERT INTO `cmf_auth_rule` VALUES ('144', '1', 'admin', 'admin_url', 'admin/Report/default', '', '用户举报', '');
INSERT INTO `cmf_auth_rule` VALUES ('145', '1', 'admin', 'admin_url', 'admin/Report/classify', '', '举报分类', '');
INSERT INTO `cmf_auth_rule` VALUES ('146', '1', 'admin', 'admin_url', 'admin/Report/classify_add', '', '举报分类添加', '');
INSERT INTO `cmf_auth_rule` VALUES ('147', '1', 'admin', 'admin_url', 'admin/Report/classify_add_post', '', '用户举报分类添加保存', '');
INSERT INTO `cmf_auth_rule` VALUES ('148', '1', 'admin', 'admin_url', 'admin/Report/classify_edit', '', '举报分类编辑', '');
INSERT INTO `cmf_auth_rule` VALUES ('149', '1', 'admin', 'admin_url', 'admin/Report/classify_edit_post', '', '举报分类编辑保存', '');
INSERT INTO `cmf_auth_rule` VALUES ('150', '1', 'admin', 'admin_url', 'admin/Report/classify_listorders', '', '举报分类排序', '');
INSERT INTO `cmf_auth_rule` VALUES ('151', '1', 'admin', 'admin_url', 'admin/Report/classify_del', '', '举报分类删除', '');
INSERT INTO `cmf_auth_rule` VALUES ('152', '1', 'admin', 'admin_url', 'admin/Report/index', '', '举报列表', '');
INSERT INTO `cmf_auth_rule` VALUES ('153', '1', 'admin', 'admin_url', 'admin/Report/ban', '', '禁用用户', '');
INSERT INTO `cmf_auth_rule` VALUES ('154', '1', 'admin', 'admin_url', 'admin/Report/ban_video', '', '下架视频', '');
INSERT INTO `cmf_auth_rule` VALUES ('155', '1', 'admin', 'admin_url', 'admin/Report/setstatus', '', '标记处理', '');
INSERT INTO `cmf_auth_rule` VALUES ('156', '1', 'admin', 'admin_url', 'admin/Report/ban_all', '', '标记处理+禁用用户+下架视频 ', '');
INSERT INTO `cmf_auth_rule` VALUES ('157', '1', 'admin', 'admin_url', 'admin/Report/del', '', '用户举报删除', '');
INSERT INTO `cmf_auth_rule` VALUES ('158', '1', 'admin', 'admin_url', 'admin/PushMessage/default', '', '官方通知', '');
INSERT INTO `cmf_auth_rule` VALUES ('159', '1', 'admin', 'admin_url', 'admin/PushMessage/add', '', '通知添加', '');
INSERT INTO `cmf_auth_rule` VALUES ('160', '1', 'admin', 'admin_url', 'admin/PushMessage/add_post', '', '推送添加保存', '');
INSERT INTO `cmf_auth_rule` VALUES ('161', '1', 'admin', 'admin_url', 'admin/PushMessage/index', '', '通知记录', '');
INSERT INTO `cmf_auth_rule` VALUES ('162', '1', 'admin', 'admin_url', 'admin/PushMessage/push', '', '记录推送', '');
INSERT INTO `cmf_auth_rule` VALUES ('163', '1', 'admin', 'admin_url', 'admin/PushMessage/del', '', '推送记录删除', '');
INSERT INTO `cmf_auth_rule` VALUES ('164', '1', 'admin', 'admin_url', 'admin/Advert/default', '', '广告管理', '');
INSERT INTO `cmf_auth_rule` VALUES ('165', '1', 'admin', 'admin_url', 'admin/Advert/add', '', '广告添加', '');
INSERT INTO `cmf_auth_rule` VALUES ('166', '1', 'admin', 'admin_url', 'admin/Advert/index', '', '广告列表', '');
INSERT INTO `cmf_auth_rule` VALUES ('167', '1', 'admin', 'admin_url', 'admin/Advert/edit', '', '广告编辑', '');
INSERT INTO `cmf_auth_rule` VALUES ('168', '1', 'admin', 'admin_url', 'admin/Advert/edit_post', '', '编辑保存', '');
INSERT INTO `cmf_auth_rule` VALUES ('169', '1', 'admin', 'admin_url', 'admin/Advert/del', '', '广告删除', '');
INSERT INTO `cmf_auth_rule` VALUES ('170', '1', 'admin', 'admin_url', 'admin/Advert/lowervideo', '', '下架广告列表', '');
INSERT INTO `cmf_auth_rule` VALUES ('171', '1', 'admin', 'admin_url', 'admin/Advert/setXiajia', '', '下架', '');
INSERT INTO `cmf_auth_rule` VALUES ('172', '1', 'admin', 'admin_url', 'admin/Advert/set_shangjia', '', '上架', '');
INSERT INTO `cmf_auth_rule` VALUES ('173', '1', 'admin', 'admin_url', 'admin/Advert/video_listen', '', '观看', '');
INSERT INTO `cmf_auth_rule` VALUES ('174', '1', 'admin', 'admin_url', 'admin/Music/default', '', '音乐管理', '');
INSERT INTO `cmf_auth_rule` VALUES ('175', '1', 'admin', 'admin_url', 'admin/Music/classify', '', '音乐分类', '');
INSERT INTO `cmf_auth_rule` VALUES ('176', '1', 'admin', 'admin_url', 'admin/Music/classify_canceldel', '', '分类取消删除', '');
INSERT INTO `cmf_auth_rule` VALUES ('177', '1', 'admin', 'admin_url', 'admin/Music/classify_del', '', '分类删除', '');
INSERT INTO `cmf_auth_rule` VALUES ('178', '1', 'admin', 'admin_url', 'admin/Music/classify_edit', '', '分类编辑', '');
INSERT INTO `cmf_auth_rule` VALUES ('179', '1', 'admin', 'admin_url', 'admin/Music/classify_edit_post', '', '编辑提交', '');
INSERT INTO `cmf_auth_rule` VALUES ('180', '1', 'admin', 'admin_url', 'admin/Music/classify_add', '', '分类添加', '');
INSERT INTO `cmf_auth_rule` VALUES ('181', '1', 'admin', 'admin_url', 'admin/Music/classify_add_post', '', '分类添加保存', '');
INSERT INTO `cmf_auth_rule` VALUES ('182', '1', 'admin', 'admin_url', 'admin/Music/index', '', '背景音乐列表', '');
INSERT INTO `cmf_auth_rule` VALUES ('183', '1', 'admin', 'admin_url', 'admin/Music/music_del', '', '删除', '');
INSERT INTO `cmf_auth_rule` VALUES ('184', '1', 'admin', 'admin_url', 'admin/Music/music_canceldel', '', '取消删除', '');
INSERT INTO `cmf_auth_rule` VALUES ('185', '1', 'admin', 'admin_url', 'admin/Music/music_listen', '', '试听', '');
INSERT INTO `cmf_auth_rule` VALUES ('186', '1', 'admin', 'admin_url', 'admin/Music/music_edit', '', '编辑', '');
INSERT INTO `cmf_auth_rule` VALUES ('187', '1', 'admin', 'admin_url', 'admin/Music/music_edit_post', '', '编辑提交', '');
INSERT INTO `cmf_auth_rule` VALUES ('188', '1', 'admin', 'admin_url', 'admin/Music/music_add', '', '添加', '');
INSERT INTO `cmf_auth_rule` VALUES ('189', '1', 'admin', 'admin_url', 'admin/Music/music_add_post', '', '添加保存', '');
INSERT INTO `cmf_auth_rule` VALUES ('190', '1', 'admin', 'admin_url', 'admin/Video/defaults', '', '视频管理', '');
INSERT INTO `cmf_auth_rule` VALUES ('191', '1', 'admin', 'admin_url', 'admin/Label/index', '', '话题标签', '');
INSERT INTO `cmf_auth_rule` VALUES ('192', '1', 'admin', 'admin_url', 'admin/Label/add', '', '添加', '');
INSERT INTO `cmf_auth_rule` VALUES ('193', '1', 'admin', 'admin_url', 'admin/Label/add_post', '', '添加提交', '');
INSERT INTO `cmf_auth_rule` VALUES ('194', '1', 'admin', 'admin_url', 'admin/Label/edit', '', '编辑', '');
INSERT INTO `cmf_auth_rule` VALUES ('195', '1', 'admin', 'admin_url', 'admin/Label/edit_post', '', '编辑提交', '');
INSERT INTO `cmf_auth_rule` VALUES ('196', '1', 'admin', 'admin_url', 'admin/Label/listsorders', '', '排序', '');
INSERT INTO `cmf_auth_rule` VALUES ('197', '1', 'admin', 'admin_url', 'admin/Label/del', '', '删除', '');
INSERT INTO `cmf_auth_rule` VALUES ('198', '1', 'admin', 'admin_url', 'admin/Video/add', '', '视频添加', '');
INSERT INTO `cmf_auth_rule` VALUES ('199', '1', 'admin', 'admin_url', 'admin/Video/passindex', '', '审核通过列表', '');
INSERT INTO `cmf_auth_rule` VALUES ('200', '1', 'admin', 'admin_url', 'admin/Video/nopassindex', '', '未通过列表', '');
INSERT INTO `cmf_auth_rule` VALUES ('201', '1', 'admin', 'admin_url', 'admin/Video/lowervideo', '', '下架列表', '');
INSERT INTO `cmf_auth_rule` VALUES ('202', '1', 'admin', 'admin_url', 'admin/Video/set_shangjia', '', '上架', '');
INSERT INTO `cmf_auth_rule` VALUES ('203', '1', 'admin', 'admin_url', 'admin/Popular/index', '', '上热门', '');
INSERT INTO `cmf_auth_rule` VALUES ('204', '1', 'admin', 'admin_url', 'admin/Video/reportset', '', '举报类型', '');
INSERT INTO `cmf_auth_rule` VALUES ('205', '1', 'admin', 'admin_url', 'admin/Video/add_report', '', '添加类型', '');
INSERT INTO `cmf_auth_rule` VALUES ('206', '1', 'admin', 'admin_url', 'admin/Video/add_reportpost', '', '添加提交', '');
INSERT INTO `cmf_auth_rule` VALUES ('207', '1', 'admin', 'admin_url', 'admin/Video/edit_report', '', '编辑类型', '');
INSERT INTO `cmf_auth_rule` VALUES ('208', '1', 'admin', 'admin_url', 'admin/Video/edit_reportpost', '', '编辑提交', '');
INSERT INTO `cmf_auth_rule` VALUES ('209', '1', 'admin', 'admin_url', 'admin/Video/del_report', '', '删除类型', '');
INSERT INTO `cmf_auth_rule` VALUES ('210', '1', 'admin', 'admin_url', 'admin/Video/listsordersset', '', '类型排序', '');
INSERT INTO `cmf_auth_rule` VALUES ('211', '1', 'admin', 'admin_url', 'admin/Video/reportlist', '', '举报列表', '');
INSERT INTO `cmf_auth_rule` VALUES ('212', '1', 'admin', 'admin_url', 'admin/Video/setstatus', '', '标记处理', '');
INSERT INTO `cmf_auth_rule` VALUES ('213', '1', 'admin', 'admin_url', 'admin/Video/report_del', '', '删除', '');
INSERT INTO `cmf_auth_rule` VALUES ('214', '1', 'admin', 'admin_url', 'admin/Video/index', '', '等待审核列表', '');
INSERT INTO `cmf_auth_rule` VALUES ('215', '1', 'admin', 'admin_url', 'admin/Video/add_post', '', '添加提交', '');
INSERT INTO `cmf_auth_rule` VALUES ('216', '1', 'admin', 'admin_url', 'admin/Video/edit', '', '视频编辑', '');
INSERT INTO `cmf_auth_rule` VALUES ('217', '1', 'admin', 'admin_url', 'admin/Video/edit_post', '', '编辑提交', '');
INSERT INTO `cmf_auth_rule` VALUES ('218', '1', 'admin', 'admin_url', 'admin/Video/del', '', '删除', '');
INSERT INTO `cmf_auth_rule` VALUES ('219', '1', 'admin', 'admin_url', 'admin/Video/video_listen', '', '视频观看', '');
INSERT INTO `cmf_auth_rule` VALUES ('220', '1', 'admin', 'admin_url', 'admin/Video/setXiajia', '', '下架', '');
INSERT INTO `cmf_auth_rule` VALUES ('221', '1', 'admin', 'admin_url', 'admin/Video/commentlists', '', '评论列表', '');
INSERT INTO `cmf_auth_rule` VALUES ('222', '1', 'admin', 'admin_url', 'admin/Shopapply/default', '', '店铺管理', '');
INSERT INTO `cmf_auth_rule` VALUES ('223', '1', 'admin', 'admin_url', 'admin/Shopapply/index', '', '店铺申请', '');
INSERT INTO `cmf_auth_rule` VALUES ('224', '1', 'admin', 'admin_url', 'admin/Shopapply/edit', '', '编辑', '');
INSERT INTO `cmf_auth_rule` VALUES ('225', '1', 'admin', 'admin_url', 'admin/Shopapply/edit_post', '', '编辑提交', '');
INSERT INTO `cmf_auth_rule` VALUES ('226', '1', 'admin', 'admin_url', 'admin/Shopapply/del', '', '删除', '');
INSERT INTO `cmf_auth_rule` VALUES ('227', '1', 'admin', 'admin_url', 'admin/Shopgoods/index', '', '商品列表', '');
INSERT INTO `cmf_auth_rule` VALUES ('228', '1', 'admin', 'admin_url', 'admin/Shopgoods/del', '', '删除', '');
INSERT INTO `cmf_auth_rule` VALUES ('229', '1', 'admin', 'admin_url', 'admin/Content/default', '', '内容管理', '');
INSERT INTO `cmf_auth_rule` VALUES ('230', '1', 'admin', 'admin_url', 'admin/Feedback/index', '', '用户反馈', '');
INSERT INTO `cmf_auth_rule` VALUES ('231', '1', 'admin', 'admin_url', 'admin/Feedback/setstatus', '', '标记处理', '');
INSERT INTO `cmf_auth_rule` VALUES ('232', '1', 'admin', 'admin_url', 'admin/Feedback/del', '', '删除', '');
INSERT INTO `cmf_auth_rule` VALUES ('233', '1', 'admin', 'admin_url', 'admin/Adminpost/index', '', '文章管理', '');
INSERT INTO `cmf_auth_rule` VALUES ('234', '1', 'admin', 'admin_url', 'admin/Adminpost/add', '', '添加', '');
INSERT INTO `cmf_auth_rule` VALUES ('235', '1', 'admin', 'admin_url', 'admin/Adminpost/addPost', '', '添加提交', '');
INSERT INTO `cmf_auth_rule` VALUES ('236', '1', 'admin', 'admin_url', 'admin/Adminpost/edit', '', '编辑', '');
INSERT INTO `cmf_auth_rule` VALUES ('237', '1', 'admin', 'admin_url', 'admin/Adminpost/editPost', '', '编辑提交', '');
INSERT INTO `cmf_auth_rule` VALUES ('238', '1', 'admin', 'admin_url', 'admin/Adminpost/del', '', '删除', '');
INSERT INTO `cmf_auth_rule` VALUES ('239', '1', 'admin', 'admin_url', 'admin/Adminpost/check', '', '审核', '');
INSERT INTO `cmf_auth_rule` VALUES ('240', '1', 'admin', 'admin_url', 'admin/Adminpost/tops', '', '置顶', '');
INSERT INTO `cmf_auth_rule` VALUES ('241', '1', 'admin', 'admin_url', 'admin/Adminpost/deletes', '', '批量删除', '');
INSERT INTO `cmf_auth_rule` VALUES ('242', '1', 'admin', 'admin_url', 'admin/Adminpage/index', '', '页面管理', '');
INSERT INTO `cmf_auth_rule` VALUES ('243', '1', 'admin', 'admin_url', 'admin/Adminpage/listordersset', '', '页面排序', '');
INSERT INTO `cmf_auth_rule` VALUES ('244', '1', 'admin', 'admin_url', 'admin/Adminpost/listordersset', '', '排序', '');
INSERT INTO `cmf_auth_rule` VALUES ('245', '1', 'admin', 'admin_url', 'admin/Adminpage/del', '', '删除页面', '');
INSERT INTO `cmf_auth_rule` VALUES ('246', '1', 'admin', 'admin_url', 'admin/Adminpage/edit', '', '编辑页面', '');
INSERT INTO `cmf_auth_rule` VALUES ('247', '1', 'admin', 'admin_url', 'admin/Adminpage/editPost', '', '提交编辑', '');
INSERT INTO `cmf_auth_rule` VALUES ('248', '1', 'admin', 'admin_url', 'admin/Adminpage/add', '', '添加页面', '');
INSERT INTO `cmf_auth_rule` VALUES ('249', '1', 'admin', 'admin_url', 'admin/Adminpage/addPost', '', '提交添加', '');
INSERT INTO `cmf_auth_rule` VALUES ('250', '1', 'admin', 'admin_url', 'admin/Adminterm/index', '', '分类管理', '');
INSERT INTO `cmf_auth_rule` VALUES ('251', '1', 'admin', 'admin_url', 'admin/Adminterm/add', '', '添加', '');
INSERT INTO `cmf_auth_rule` VALUES ('252', '1', 'admin', 'admin_url', 'admin/Adminterm/addPost', '', '提交', '');
INSERT INTO `cmf_auth_rule` VALUES ('253', '1', 'admin', 'admin_url', 'admin/Adminterm/edit', '', '编辑', '');
INSERT INTO `cmf_auth_rule` VALUES ('254', '1', 'admin', 'admin_url', 'admin/Adminterm/edit_post', '', '提交', '');
INSERT INTO `cmf_auth_rule` VALUES ('255', '1', 'admin', 'admin_url', 'admin/Adminterm/listOrder', '', '排序', '');
INSERT INTO `cmf_auth_rule` VALUES ('256', '1', 'admin', 'admin_url', 'admin/Adminterm/del', '', '删除', '');
INSERT INTO `cmf_auth_rule` VALUES ('257', '1', 'admin', 'admin_url', 'admin/Agent/default', '', '分销管理', '');
INSERT INTO `cmf_auth_rule` VALUES ('258', '1', 'admin', 'admin_url', 'admin/Agent/index', '', '分销关系', '');
INSERT INTO `cmf_auth_rule` VALUES ('259', '1', 'admin', 'admin_url', 'admin/Agent/index2', '', '分销收益', '');
INSERT INTO `cmf_auth_rule` VALUES ('260', '1', 'admin', 'admin_url', 'admin/Cash/default', '', '财务管理', '');
INSERT INTO `cmf_auth_rule` VALUES ('261', '1', 'admin', 'admin_url', 'admin/Cash/index', '', '提现管理', '');
INSERT INTO `cmf_auth_rule` VALUES ('262', '1', 'admin', 'admin_url', 'admin/Cash/edit', '', '编辑', '');
INSERT INTO `cmf_auth_rule` VALUES ('263', '1', 'admin', 'admin_url', 'admin/Cash/edit_post', '', '编辑提交', '');
INSERT INTO `cmf_auth_rule` VALUES ('264', '1', 'admin', 'admin_url', 'admin/Manual/index', '', '手动充值', '');
INSERT INTO `cmf_auth_rule` VALUES ('265', '1', 'admin', 'admin_url', 'admin/Manual/add', '', '添加', '');
INSERT INTO `cmf_auth_rule` VALUES ('266', '1', 'admin', 'admin_url', 'admin/Manual/add_post', '', '添加提交', '');
INSERT INTO `cmf_auth_rule` VALUES ('267', '1', 'Admin', 'admin_url', 'Admin/Videoclass/index', '', '视频分类', '');
INSERT INTO `cmf_auth_rule` VALUES ('268', '1', 'Admin', 'admin_url', 'Admin/Live/default', '', '直播管理', '');
INSERT INTO `cmf_auth_rule` VALUES ('269', '1', 'Admin', 'admin_url', 'Admin/Liveban/index', '', '禁播列表', '');
INSERT INTO `cmf_auth_rule` VALUES ('270', '1', 'Admin', 'admin_url', 'Admin/Liveban/del', '', '删除', '');
INSERT INTO `cmf_auth_rule` VALUES ('271', '1', 'Admin', 'admin_url', 'Admin/Liveshut/index', '', '禁言列表', '');
INSERT INTO `cmf_auth_rule` VALUES ('272', '1', 'Admin', 'admin_url', 'Admin/Liveshut/del', '', '删除', '');
INSERT INTO `cmf_auth_rule` VALUES ('273', '1', 'Admin', 'admin_url', 'Admin/Livekick/index', '', '踢人列表', '');
INSERT INTO `cmf_auth_rule` VALUES ('274', '1', 'Admin', 'admin_url', 'Admin/Livekick/del', '', '删除', '');
INSERT INTO `cmf_auth_rule` VALUES ('275', '1', 'Admin', 'admin_url', 'Admin/Liveing/index', '', '直播列表', '');
INSERT INTO `cmf_auth_rule` VALUES ('276', '1', 'Admin', 'admin_url', 'Admin/Liveing/add', '', '添加视频', '');
INSERT INTO `cmf_auth_rule` VALUES ('277', '1', 'Admin', 'admin_url', 'Admin/Liveing/add_post', '', '添加保存', '');
INSERT INTO `cmf_auth_rule` VALUES ('278', '1', 'Admin', 'admin_url', 'Admin/Liveing/edit', '', '视频编辑', '');
INSERT INTO `cmf_auth_rule` VALUES ('279', '1', 'Admin', 'admin_url', 'Admin/Liveing/edit_post', '', '视频编辑提交', '');
INSERT INTO `cmf_auth_rule` VALUES ('280', '1', 'Admin', 'admin_url', 'Admin/Liveing/del', '', '视频删除', '');
INSERT INTO `cmf_auth_rule` VALUES ('281', '1', 'Admin', 'admin_url', 'Admin/Monitor/index', '', '直播监控', '');
INSERT INTO `cmf_auth_rule` VALUES ('282', '1', 'Admin', 'admin_url', 'Admin/Monitor/stopRoom', '', '关播', '');
INSERT INTO `cmf_auth_rule` VALUES ('283', '1', 'Admin', 'admin_url', 'Admin/Monitor/full', '', '监控大屏', '');
INSERT INTO `cmf_auth_rule` VALUES ('284', '1', 'Admin', 'admin_url', 'Admin/Gift/index', '', '礼物列表', '');
INSERT INTO `cmf_auth_rule` VALUES ('285', '1', 'Admin', 'admin_url', 'Admin/Livereport/default', '', '直播间举报管理', '');
INSERT INTO `cmf_auth_rule` VALUES ('286', '1', 'Admin', 'admin_url', 'Admin/Live/index', '', '直播记录', '');
INSERT INTO `cmf_auth_rule` VALUES ('287', '1', 'Admin', 'admin_url', 'Admin/Gift/add', '', '礼物添加', '');
INSERT INTO `cmf_auth_rule` VALUES ('288', '1', 'Admin', 'admin_url', 'Admin/Gift/add_post', '', '添加保存', '');
INSERT INTO `cmf_auth_rule` VALUES ('289', '1', 'Admin', 'admin_url', 'Admin/Gift/edit', '', '礼物修改', '');
INSERT INTO `cmf_auth_rule` VALUES ('290', '1', 'Admin', 'admin_url', 'Admin/Gift/edit_post', '', '编辑保存', '');
INSERT INTO `cmf_auth_rule` VALUES ('291', '1', 'Admin', 'admin_url', 'Admin/Gift/del', '', '礼物删除', '');
INSERT INTO `cmf_auth_rule` VALUES ('292', '1', 'Admin', 'admin_url', 'Admin/Gift/listorders', '', '礼物排序', '');
INSERT INTO `cmf_auth_rule` VALUES ('293', '1', 'Admin', 'admin_url', 'Admin/Reportcat/index', '', '直播间举报类型', '');
INSERT INTO `cmf_auth_rule` VALUES ('294', '1', 'Admin', 'admin_url', 'Admin/Livereport/index', '', '直播间举报列表', '');
INSERT INTO `cmf_auth_rule` VALUES ('295', '1', 'Admin', 'admin_url', 'Admin/Reportcat/add', '', '举报类型添加', '');
INSERT INTO `cmf_auth_rule` VALUES ('296', '1', 'Admin', 'admin_url', 'Admin/Reportcat/add_post', '', '添加保存', '');
INSERT INTO `cmf_auth_rule` VALUES ('297', '1', 'Admin', 'admin_url', 'Admin/Reportcat/edit', '', '举报类型编辑', '');
INSERT INTO `cmf_auth_rule` VALUES ('298', '1', 'Admin', 'admin_url', 'Admin/Reportcat/edit_post', '', '编辑保存', '');
INSERT INTO `cmf_auth_rule` VALUES ('299', '1', 'Admin', 'admin_url', 'Admin/Reportcat/listorders', '', '排序', '');
INSERT INTO `cmf_auth_rule` VALUES ('300', '1', 'Admin', 'admin_url', 'Admin/Reportcat/del', '', '删除', '');
INSERT INTO `cmf_auth_rule` VALUES ('301', '1', 'Admin', 'admin_url', 'Admin/Livereport/setstatus', '', '标记处理', '');
INSERT INTO `cmf_auth_rule` VALUES ('302', '1', 'Admin', 'admin_url', 'Admin/Chargerules/index', '', '钻石充值规则', '');
INSERT INTO `cmf_auth_rule` VALUES ('303', '1', 'Admin', 'admin_url', 'Admin/Chargerules/add', '', '添加钻石充值规则', '');
INSERT INTO `cmf_auth_rule` VALUES ('304', '1', 'Admin', 'admin_url', 'Admin/Chargerules/add_post', '', '添加保存', '');
INSERT INTO `cmf_auth_rule` VALUES ('305', '1', 'Admin', 'admin_url', 'Admin/Chargerules/edit', '', '编辑钻石充值规则', '');
INSERT INTO `cmf_auth_rule` VALUES ('306', '1', 'Admin', 'admin_url', 'Admin/Chargerules/edit_post', '', '编辑提交', '');
INSERT INTO `cmf_auth_rule` VALUES ('307', '1', 'Admin', 'admin_url', 'Admin/Chargerules/listorderset', '', '排序', '');
INSERT INTO `cmf_auth_rule` VALUES ('308', '1', 'Admin', 'admin_url', 'Admin/Chargerules/del', '', '删除', '');
INSERT INTO `cmf_auth_rule` VALUES ('309', '1', 'Admin', 'admin_url', 'Admin/Vipchargerules/index', '', 'VIP充值规则', '');
INSERT INTO `cmf_auth_rule` VALUES ('310', '1', 'Admin', 'admin_url', 'Admin/Vipchargerules/add', '', '添加', '');
INSERT INTO `cmf_auth_rule` VALUES ('311', '1', 'Admin', 'admin_url', 'Admin/Vipchargerules/add_post', '', '添加保存', '');
INSERT INTO `cmf_auth_rule` VALUES ('312', '1', 'Admin', 'admin_url', 'Admin/Vipchargerules/edit', '', '编辑', '');
INSERT INTO `cmf_auth_rule` VALUES ('313', '1', 'Admin', 'admin_url', 'Admin/Vipchargerules/edit_post', '', '编辑保存', '');
INSERT INTO `cmf_auth_rule` VALUES ('314', '1', 'Admin', 'admin_url', 'Admin/Vipchargerules/listorderset', '', '排序', '');
INSERT INTO `cmf_auth_rule` VALUES ('315', '1', 'Admin', 'admin_url', 'Admin/Vipchargerules/del', '', '删除', '');
INSERT INTO `cmf_auth_rule` VALUES ('316', '1', 'Admin', 'admin_url', 'Admin/Charge/index', '', '钻石充值记录', '');
INSERT INTO `cmf_auth_rule` VALUES ('317', '1', 'Admin', 'admin_url', 'Admin/Charge/export', '', '导出', '');
INSERT INTO `cmf_auth_rule` VALUES ('318', '1', 'Admin', 'admin_url', 'Admin/Charge/setPay', '', '确认支付', '');
INSERT INTO `cmf_auth_rule` VALUES ('319', '1', 'Admin', 'admin_url', 'Admin/Vipcharge/index', '', 'VIP充值记录', '');
INSERT INTO `cmf_auth_rule` VALUES ('320', '1', 'Admin', 'admin_url', 'Admin/Vipcharge/export', '', '导出', '');
INSERT INTO `cmf_auth_rule` VALUES ('321', '1', 'Admin', 'admin_url', 'Admin/Cash/export', '', '导出提现记录', '');
INSERT INTO `cmf_auth_rule` VALUES ('322', '1', 'Admin', 'admin_url', 'Admin/Votesrecord/index', '', '收入记录', '');
INSERT INTO `cmf_auth_rule` VALUES ('323', '1', 'Admin', 'admin_url', 'Admin/Userauth/edit', '', '编辑', '');
INSERT INTO `cmf_auth_rule` VALUES ('324', '1', 'Admin', 'admin_url', 'Admin/Userauth/edit_post', '', '编辑保存', '');
INSERT INTO `cmf_auth_rule` VALUES ('325', '1', 'Admin', 'admin_url', 'Admin/Userauth/del', '', '删除身份认证', '');
INSERT INTO `cmf_auth_rule` VALUES ('326', '1', 'User', 'admin_url', 'User/AdminIndex/super', '', '设置超管', '');
INSERT INTO `cmf_auth_rule` VALUES ('327', '1', 'User', 'admin_url', 'User/AdminIndex/cancelsuper', '', '取消超管', '');
INSERT INTO `cmf_auth_rule` VALUES ('328', '1', 'User', 'admin_url', 'User/AdminIndex/setvip', '', '设置vip', '');
INSERT INTO `cmf_auth_rule` VALUES ('329', '1', 'User', 'admin_url', 'User/AdminIndex/setvip_post', '', '设置vip提交', '');
INSERT INTO `cmf_auth_rule` VALUES ('330', '1', 'User', 'admin_url', 'User/AdminIndex/edit', '', '编辑', '');
INSERT INTO `cmf_auth_rule` VALUES ('331', '1', 'User', 'admin_url', 'User/AdminIndex/editPost', '', '编辑提交', '');
INSERT INTO `cmf_auth_rule` VALUES ('332', '1', 'User', 'admin_url', 'User/AdminIndex/del', '', '删除', '');
INSERT INTO `cmf_auth_rule` VALUES ('333', '1', 'Admin', 'admin_url', 'Admin/Advert/add_post', '', '广告添加保存', '');
INSERT INTO `cmf_auth_rule` VALUES ('334', '1', 'Admin', 'admin_url', 'Admin/Videoclass/add', '', '视频分类添加', '');
INSERT INTO `cmf_auth_rule` VALUES ('335', '1', 'Admin', 'admin_url', 'Admin/Videoclass/add_post', '', '添加保存', '');
INSERT INTO `cmf_auth_rule` VALUES ('336', '1', 'Admin', 'admin_url', 'Admin/Videoclass/edit', '', '编辑分类', '');
INSERT INTO `cmf_auth_rule` VALUES ('337', '1', 'Admin', 'admin_url', 'Admin/Videoclass/edit_post', '', '编辑保存', '');
INSERT INTO `cmf_auth_rule` VALUES ('338', '1', 'Admin', 'admin_url', 'Admin/Videoclass/del', '', '删除视频分类', '');
INSERT INTO `cmf_auth_rule` VALUES ('339', '1', 'Admin', 'admin_url', 'Admin/Videoclass/listordersset', '', '视频分类排序', '');
INSERT INTO `cmf_auth_rule` VALUES ('340', '1', 'Admin', 'admin_url', 'Admin/Vipcharge/setPay', '', '确认支付', '');
INSERT INTO `cmf_auth_rule` VALUES ('341', '1', 'Admin', 'admin_url', 'Admin/Votesrecord/export', '', '导出收入记录', '');
INSERT INTO `cmf_auth_rule` VALUES ('342', '1', 'Admin', 'admin_url', 'Admin/Coinrecord/index', '', '钻石消费记录', '');
INSERT INTO `cmf_auth_rule` VALUES ('343', '1', 'Admin', 'admin_url', 'Admin/Setting/configpriPost', '', '私密设置保存', '');
INSERT INTO `cmf_auth_rule` VALUES ('344', '1', 'admin', 'admin_url', 'admin/shopgoods/setstatus', '', '上架或下架', '');
INSERT INTO `cmf_auth_rule` VALUES ('345', '1', 'admin', 'admin_url', 'admin/Shopgoods/edit', '', '审核或详情', '');
INSERT INTO `cmf_auth_rule` VALUES ('346', '1', 'admin', 'admin_url', 'admin/Shopgoods/editPost', '', '提交', '');
INSERT INTO `cmf_auth_rule` VALUES ('347', '1', 'admin', 'admin_url', 'admin/Coinrecord/export', '', '导出', '');
INSERT INTO `cmf_auth_rule` VALUES ('348', '1', 'admin', 'admin_url', 'admin/Turntable/default', '', '大转盘', '');
INSERT INTO `cmf_auth_rule` VALUES ('349', '1', 'admin', 'admin_url', 'admin/Turntablecon/index', '', '价格管理', '');
INSERT INTO `cmf_auth_rule` VALUES ('350', '1', 'admin', 'admin_url', 'admin/Turntable/index', '', '奖品管理', '');
INSERT INTO `cmf_auth_rule` VALUES ('351', '1', 'admin', 'admin_url', 'admin/Turntable/index2', '', '转盘记录', '');
INSERT INTO `cmf_auth_rule` VALUES ('352', '1', 'admin', 'admin_url', 'admin/Turntable/index3', '', '线下奖品', '');
INSERT INTO `cmf_auth_rule` VALUES ('353', '1', 'admin', 'admin_url', 'admin/Turntablecon/edit', '', '编辑', '');
INSERT INTO `cmf_auth_rule` VALUES ('354', '1', 'admin', 'admin_url', 'admin/Turntablecon/editPost', '', '编辑提交', '');
INSERT INTO `cmf_auth_rule` VALUES ('355', '1', 'admin', 'admin_url', 'admin/Turntablecon/listOrder', '', '排序', '');
INSERT INTO `cmf_auth_rule` VALUES ('356', '1', 'admin', 'admin_url', 'admin/Turntable/edit', '', '编辑', '');
INSERT INTO `cmf_auth_rule` VALUES ('357', '1', 'admin', 'admin_url', 'admin/Turntable/editPost', '', '编辑提交', '');
INSERT INTO `cmf_auth_rule` VALUES ('358', '1', 'admin', 'admin_url', 'admin/Turntable/setstatus', '', '处理', '');
INSERT INTO `cmf_auth_rule` VALUES ('359', '1', 'admin', 'admin_url', 'admin/Guard/index', '', '守护管理', '');
INSERT INTO `cmf_auth_rule` VALUES ('360', '1', 'Admin', 'admin_url', 'Admin/Guard/edit', '', '编辑', '');
INSERT INTO `cmf_auth_rule` VALUES ('361', '1', 'admin', 'admin_url', 'admin/Guard/editPost', '', '编辑提交', '');
INSERT INTO `cmf_auth_rule` VALUES ('362', '1', 'admin', 'admin_url', 'admin/Video/setBatchXiajia', '', '批量下架', '');
INSERT INTO `cmf_auth_rule` VALUES ('363', '1', 'admin', 'admin_url', 'admin/Video/setBatchDel', '', '批量删除', '');
INSERT INTO `cmf_auth_rule` VALUES ('364', '1', 'admin', 'admin_url', 'admin/Video/setBatchStatus', '', '批量通过/拒绝', '');
INSERT INTO `cmf_auth_rule` VALUES ('365', '1', 'Admin', 'admin_url', 'Admin/Goodsclass/index', '', '商品分类列表', '');
INSERT INTO `cmf_auth_rule` VALUES ('366', '1', 'Admin', 'admin_url', 'Admin/Goodsclass/add', '', '商品分类添加', '');
INSERT INTO `cmf_auth_rule` VALUES ('367', '1', 'Admin', 'admin_url', 'Admin/Goodsclass/addPost', '', '商品分类添加保存', '');
INSERT INTO `cmf_auth_rule` VALUES ('368', '1', 'Admin', 'admin_url', 'Admin/Goodsclass/edit', '', '商品分类编辑', '');
INSERT INTO `cmf_auth_rule` VALUES ('369', '1', 'Admin', 'admin_url', 'Admin/Goodsclass/editPost', '', '商品分类编辑保存', '');
INSERT INTO `cmf_auth_rule` VALUES ('370', '1', 'Admin', 'admin_url', 'Admin/Goodsclass/del', '', '商品分类删除', '');
INSERT INTO `cmf_auth_rule` VALUES ('371', '1', 'Admin', 'admin_url', 'Admin/shopbond/index', '', '保证金', '');
INSERT INTO `cmf_auth_rule` VALUES ('372', '1', 'Admin', 'admin_url', 'Admin/shopbond/setstatus', '', '保证金处理', '');
INSERT INTO `cmf_auth_rule` VALUES ('373', '1', 'Admin', 'admin_url', 'Admin/Buyeraddress/index', '', '收货地址管理', '');
INSERT INTO `cmf_auth_rule` VALUES ('374', '1', 'Admin', 'admin_url', 'Admin/Buyeraddress/del', '', '收货地址删除', '');
INSERT INTO `cmf_auth_rule` VALUES ('375', '1', 'Admin', 'admin_url', 'Admin/Express/index', '', '物流公司列表', '');
INSERT INTO `cmf_auth_rule` VALUES ('376', '1', 'Admin', 'admin_url', 'Admin/Express/add', '', '物流公司添加', '');
INSERT INTO `cmf_auth_rule` VALUES ('377', '1', 'Admin', 'admin_url', 'Admin/Express/add_post', '', '物流公司添加保存', '');
INSERT INTO `cmf_auth_rule` VALUES ('378', '1', 'Admin', 'admin_url', 'Admin/Express/edit', '', '物流公司编辑', '');
INSERT INTO `cmf_auth_rule` VALUES ('379', '1', 'Admin', 'admin_url', 'Admin/Express/edit_post', '', '物流公司编辑保存', '');
INSERT INTO `cmf_auth_rule` VALUES ('380', '1', 'Admin', 'admin_url', 'Admin/Express/del', '', '物流公司删除', '');
INSERT INTO `cmf_auth_rule` VALUES ('381', '1', 'Admin', 'admin_url', 'Admin/Express/listOrder', '', '物流公司排序', '');
INSERT INTO `cmf_auth_rule` VALUES ('382', '1', 'Admin', 'admin_url', 'Admin/Refundreason/index', '', '买家申请退款原因', '');
INSERT INTO `cmf_auth_rule` VALUES ('383', '1', 'Admin', 'admin_url', 'Admin/Refundreason/add', '', '添加退款原因', '');
INSERT INTO `cmf_auth_rule` VALUES ('384', '1', 'Admin', 'admin_url', 'Admin/Refundreason/add_post', '', '退款原因添加保存', '');
INSERT INTO `cmf_auth_rule` VALUES ('385', '1', 'Admin', 'admin_url', 'Admin/Refundreason/edit', '', '编辑退款原因', '');
INSERT INTO `cmf_auth_rule` VALUES ('386', '1', 'Admin', 'admin_url', 'Admin/Refundreason/edit_post', '', '退款原因编辑保存', '');
INSERT INTO `cmf_auth_rule` VALUES ('387', '1', 'Admin', 'admin_url', 'Admin/Refundreason/del', '', '删除退款原因', '');
INSERT INTO `cmf_auth_rule` VALUES ('388', '1', 'Admin', 'admin_url', 'Admin/Refundreason/listOrder', '', '排序', '');
INSERT INTO `cmf_auth_rule` VALUES ('389', '1', 'Admin', 'admin_url', 'Admin/Refusereason/index', '', '卖家拒绝退款原因', '');
INSERT INTO `cmf_auth_rule` VALUES ('390', '1', 'Admin', 'admin_url', 'Admin/Refusereason/add', '', '拒绝退款原因添加', '');
INSERT INTO `cmf_auth_rule` VALUES ('391', '1', 'Admin', 'admin_url', 'Admin/Refusereason/add_post', '', '拒绝退款原因添加保存', '');
INSERT INTO `cmf_auth_rule` VALUES ('392', '1', 'Admin', 'admin_url', 'Admin/Refusereason/edit', '', '拒绝退款原因编辑', '');
INSERT INTO `cmf_auth_rule` VALUES ('393', '1', 'Admin', 'admin_url', 'Admin/Refusereason/edit_post', '', '拒绝退款原因编辑保存', '');
INSERT INTO `cmf_auth_rule` VALUES ('394', '1', 'Admin', 'admin_url', 'Admin/Refusereason/del', '', '拒绝退款原因删除', '');
INSERT INTO `cmf_auth_rule` VALUES ('395', '1', 'Admin', 'admin_url', 'Admin/Refusereason/listOrder', '', '拒绝退款原因排序', '');
INSERT INTO `cmf_auth_rule` VALUES ('396', '1', 'Admin', 'admin_url', 'Admin/Platformreason/index', '', '退款平台介入原因', '');
INSERT INTO `cmf_auth_rule` VALUES ('397', '1', 'Admin', 'admin_url', 'Admin/Platformreason/add', '', '添加平台介入原因', '');
INSERT INTO `cmf_auth_rule` VALUES ('398', '1', 'Admin', 'admin_url', 'Admin/Platformreason/add_post', '', '平台介入原因添加保存', '');
INSERT INTO `cmf_auth_rule` VALUES ('399', '1', 'Admin', 'admin_url', 'Admin/Platformreason/edot', '', '编辑平台介入原因', '');
INSERT INTO `cmf_auth_rule` VALUES ('400', '1', 'Admin', 'admin_url', 'Admin/Platformreason/edit_post', '', '平台介入原因添加保存', '');
INSERT INTO `cmf_auth_rule` VALUES ('401', '1', 'Admin', 'admin_url', 'Admin/Platformreason/listOrder', '', '平台介入原因排序', '');
INSERT INTO `cmf_auth_rule` VALUES ('402', '1', 'Admin', 'admin_url', 'Admin/Platformreason/del', '', '删除平台介入原因', '');
INSERT INTO `cmf_auth_rule` VALUES ('403', '1', 'Admin', 'admin_url', 'Admin/Goodsorder/index', '', '商品订单列表', '');
INSERT INTO `cmf_auth_rule` VALUES ('404', '1', 'Admin', 'admin_url', 'Admin/Goodsorder/info', '', '订单详情', '');
INSERT INTO `cmf_auth_rule` VALUES ('405', '1', 'Admin', 'admin_url', 'Admin/Goodsorder/setexpress', '', '填写物流', '');
INSERT INTO `cmf_auth_rule` VALUES ('406', '1', 'Admin', 'admin_url', 'Admin/Goodsorder/setexpresspost', '', '填写物流提交', '');
INSERT INTO `cmf_auth_rule` VALUES ('407', '1', 'Admin', 'admin_url', 'Admin/Refundlist/index', '', '退款列表', '');
INSERT INTO `cmf_auth_rule` VALUES ('408', '1', 'Admin', 'admin_url', 'Admin/Refundlist/edit', '', '编辑', '');
INSERT INTO `cmf_auth_rule` VALUES ('409', '1', 'Admin', 'admin_url', 'Admin/Refundlist/edit_post', '', '编辑提交', '');
INSERT INTO `cmf_auth_rule` VALUES ('410', '1', 'Admin', 'admin_url', 'Admin/refundlist/platformedit', '', '平台自营处理退款', '');
INSERT INTO `cmf_auth_rule` VALUES ('411', '1', 'Admin', 'admin_url', 'Admin/Shopcash/index', '', '提现记录', '');
INSERT INTO `cmf_auth_rule` VALUES ('412', '1', 'Admin', 'admin_url', 'Admin/Shopcash/edit', '', '编辑提现记录', '');
INSERT INTO `cmf_auth_rule` VALUES ('413', '1', 'Admin', 'admin_url', 'Admin/Shopcash/editPost', '', '编辑提交', '');
INSERT INTO `cmf_auth_rule` VALUES ('414', '1', 'Admin', 'admin_url', 'Admin/Shopcash/export', '', '导出提现记录', '');
INSERT INTO `cmf_auth_rule` VALUES ('415', '1', 'Admin', 'admin_url', 'Admin/Balance/index', '', '余额手动充值', '');
INSERT INTO `cmf_auth_rule` VALUES ('416', '1', 'Admin', 'admin_url', 'Admin/Balance/add', '', '充值添加', '');
INSERT INTO `cmf_auth_rule` VALUES ('417', '1', 'Admin', 'admin_url', 'Admin/Balance/addPost', '', '余额充值保存', '');
INSERT INTO `cmf_auth_rule` VALUES ('418', '1', 'Admin', 'admin_url', 'Admin/Balance/export', '', '余额充值记录导出', '');
INSERT INTO `cmf_auth_rule` VALUES ('419', '1', 'Admin', 'admin_url', 'Admin/Shopcategory/index', '', ' 经营类目申请列表', '');
INSERT INTO `cmf_auth_rule` VALUES ('420', '1', 'Admin', 'admin_url', 'Admin/Shopcategory/edit', '', '编辑经营类目申请', '');
INSERT INTO `cmf_auth_rule` VALUES ('421', '1', 'Admin', 'admin_url', 'Admin/Shopcategory/editPost', '', ' 编辑提交', '');
INSERT INTO `cmf_auth_rule` VALUES ('422', '1', 'Admin', 'admin_url', 'Admin/Shopcategory/del', '', '删除类目申请记录', '');
INSERT INTO `cmf_auth_rule` VALUES ('423', '1', 'Admin', 'admin_url', 'Admin/Liveclass/index', '', '直播分类', '');
INSERT INTO `cmf_auth_rule` VALUES ('424', '1', 'Admin', 'admin_url', 'Admin/Liveclass/add', '', '添加直播分类', '');
INSERT INTO `cmf_auth_rule` VALUES ('425', '1', 'Admin', 'admin_url', 'Admin/Liveclass/addPost', '', '直播分类添加保存', '');
INSERT INTO `cmf_auth_rule` VALUES ('426', '1', 'Admin', 'admin_url', 'Admin/Liveclass/edit', '', '编辑直播分类', '');
INSERT INTO `cmf_auth_rule` VALUES ('427', '1', 'Admin', 'admin_url', 'Admin/Liveclass/editPost', '', '直播分类编辑提交', '');
INSERT INTO `cmf_auth_rule` VALUES ('428', '1', 'Admin', 'admin_url', 'Admin/Liveclass/del', '', '删除直播分类', '');
INSERT INTO `cmf_auth_rule` VALUES ('429', '1', 'Admin', 'admin_url', 'Admin/Liveclass/listOrder', '', '直播分类排序', '');

-- ----------------------------
-- Table structure for `cmf_backpack`
-- ----------------------------
DROP TABLE IF EXISTS `cmf_backpack`;
CREATE TABLE `cmf_backpack` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `uid` bigint(20) NOT NULL DEFAULT '0' COMMENT '用户ID',
  `giftid` int(11) NOT NULL DEFAULT '0' COMMENT '礼物ID',
  `nums` int(11) NOT NULL DEFAULT '0' COMMENT '数量',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Records of cmf_backpack
-- ----------------------------

-- ----------------------------
-- Table structure for `cmf_balance_charge_admin`
-- ----------------------------
DROP TABLE IF EXISTS `cmf_balance_charge_admin`;
CREATE TABLE `cmf_balance_charge_admin` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `touid` int(11) NOT NULL DEFAULT '0' COMMENT '充值对象ID',
  `balance` decimal(20,2) NOT NULL DEFAULT '0.00' COMMENT '金额',
  `addtime` int(11) NOT NULL DEFAULT '0' COMMENT '添加时间',
  `admin` varchar(255) NOT NULL DEFAULT '' COMMENT '管理员',
  `ip` varchar(255) NOT NULL DEFAULT '' COMMENT 'IP',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Records of cmf_balance_charge_admin
-- ----------------------------

-- ----------------------------
-- Table structure for `cmf_charge_rules`
-- ----------------------------
DROP TABLE IF EXISTS `cmf_charge_rules`;
CREATE TABLE `cmf_charge_rules` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL DEFAULT '' COMMENT '名称',
  `coin` int(11) NOT NULL DEFAULT '0' COMMENT '钻石数',
  `coin_ios` int(11) NOT NULL DEFAULT '0' COMMENT '苹果钻石数',
  `money` decimal(11,2) NOT NULL DEFAULT '0.00' COMMENT '金额',
  `product_id` varchar(50) NOT NULL DEFAULT '' COMMENT '苹果项目ID',
  `give` int(11) NOT NULL DEFAULT '0' COMMENT '赠送钻石数',
  `orderno` int(11) NOT NULL DEFAULT '0' COMMENT '序号',
  `addtime` int(11) NOT NULL DEFAULT '0' COMMENT '添加时间',
  `coin_paypal` int(11) NOT NULL DEFAULT '0' COMMENT 'paypal支付获得的钻石数',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Records of cmf_charge_rules
-- ----------------------------
INSERT INTO `cmf_charge_rules` VALUES ('1', '999999钻石', '999999', '999999', '0.01', 'coin_600', '0', '0', '1572672557', '999999');
INSERT INTO `cmf_charge_rules` VALUES ('2', '3000钻石', '3000', '3000', '30.00', 'coin_3000', '0', '1', '1572672657', '3000');
INSERT INTO `cmf_charge_rules` VALUES ('3', '9800钻石', '9800', '9800', '98.00', 'coin_9800', '200', '3', '1572672757', '9800');
INSERT INTO `cmf_charge_rules` VALUES ('4', '38800钻石', '38800', '38800', '388.00', 'coin_38800', '500', '4', '1572672857', '38800');
INSERT INTO `cmf_charge_rules` VALUES ('5', '58800钻石', '58800', '58800', '588.00', 'coin_58800', '1200', '5', '1572673167', '58800');

-- ----------------------------
-- Table structure for `cmf_feedback`
-- ----------------------------
DROP TABLE IF EXISTS `cmf_feedback`;
CREATE TABLE `cmf_feedback` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(11) DEFAULT '0',
  `title` varchar(255) CHARACTER SET utf8 DEFAULT '',
  `version` varchar(50) CHARACTER SET utf8 DEFAULT '',
  `model` varchar(50) CHARACTER SET utf8 DEFAULT '',
  `content` text,
  `addtime` int(11) DEFAULT NULL,
  `status` tinyint(1) DEFAULT '0',
  `uptime` int(11) DEFAULT '0',
  `thumb` varchar(255) CHARACTER SET utf8 DEFAULT '' COMMENT '图片',
  `contact_msg` varchar(255) DEFAULT '' COMMENT '联系方式',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 ROW_FORMAT=COMPACT;

-- ----------------------------
-- Records of cmf_feedback
-- ----------------------------

-- ----------------------------
-- Table structure for `cmf_getcode_limit_ip`
-- ----------------------------
DROP TABLE IF EXISTS `cmf_getcode_limit_ip`;
CREATE TABLE `cmf_getcode_limit_ip` (
  `ip` bigint(20) NOT NULL DEFAULT '0' COMMENT 'ip地址',
  `date` varchar(30) NOT NULL DEFAULT '' COMMENT '时间',
  `times` tinyint(4) NOT NULL DEFAULT '3' COMMENT '次数',
  PRIMARY KEY (`ip`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Records of cmf_getcode_limit_ip
-- ----------------------------

-- ----------------------------
-- Table structure for `cmf_gift`
-- ----------------------------
DROP TABLE IF EXISTS `cmf_gift`;
CREATE TABLE `cmf_gift` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `type` tinyint(1) NOT NULL DEFAULT '1' COMMENT '类型,0普通礼物，1豪华礼物，2手绘礼物',
  `mark` tinyint(1) NOT NULL DEFAULT '0' COMMENT '标识，0普通，2守护',
  `sid` int(11) NOT NULL DEFAULT '0' COMMENT '分类ID',
  `giftname` varchar(50) NOT NULL DEFAULT '' COMMENT '名称',
  `needcoin` int(11) NOT NULL DEFAULT '0' COMMENT '价格',
  `gifticon_mini` varchar(255) NOT NULL DEFAULT '' COMMENT '小图',
  `gifticon` varchar(255) NOT NULL DEFAULT '' COMMENT '图片',
  `orderno` int(3) NOT NULL DEFAULT '0' COMMENT '序号',
  `addtime` int(11) NOT NULL DEFAULT '0' COMMENT '添加时间',
  `swftype` tinyint(1) NOT NULL DEFAULT '0' COMMENT '动画类型，0gif,1svga',
  `swf` varchar(255) NOT NULL DEFAULT '' COMMENT '动画链接',
  `swftime` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '动画时长',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=78 DEFAULT CHARSET=utf8mb4 ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Records of cmf_gift
-- ----------------------------
INSERT INTO `cmf_gift` VALUES ('1', '1', '0', '0', '爱神丘比特', '60', '', 'gift_1.png%@%cloudtype=1', '22', '1459210850', '1', 'gift_gif_1.svga%@%cloudtype=1', '6.30');
INSERT INTO `cmf_gift` VALUES ('2', '1', '0', '0', '爱心飞机', '25', '', 'gift_2.png%@%cloudtype=1', '14', '1516001943', '1', 'gift_gif_2.svga%@%cloudtype=1', '7.50');
INSERT INTO `cmf_gift` VALUES ('3', '1', '0', '0', '告白气球', '10', '', 'gift_3.png%@%cloudtype=1', '13', '1516002284', '1', 'gift_gif_3.svga%@%cloudtype=1', '4.10');
INSERT INTO `cmf_gift` VALUES ('4', '1', '0', '0', '流星雨', '999', '', 'gift_4.png%@%cloudtype=1', '12', '1516002357', '1', 'gift_gif_4.svga%@%cloudtype=1', '6.00');
INSERT INTO `cmf_gift` VALUES ('5', '1', '0', '0', '玫瑰花束', '20', '', 'gift_5.png%@%cloudtype=1', '11', '1516002391', '1', 'gift_gif_5.svga%@%cloudtype=1', '4.10');
INSERT INTO `cmf_gift` VALUES ('6', '1', '0', '0', '梦幻城堡', '12', '', 'gift_6.png%@%cloudtype=1', '10', '1516002459', '1', 'gift_gif_6.svga%@%cloudtype=1', '7.60');
INSERT INTO `cmf_gift` VALUES ('7', '1', '0', '0', '跑车', '100', '', 'gift_7.png%@%cloudtype=1', '9', '1516002489', '1', 'gift_gif_7.svga%@%cloudtype=1', '3.50');
INSERT INTO `cmf_gift` VALUES ('8', '1', '0', '0', '鹊桥相会', '30', '', 'gift_8.png%@%cloudtype=1', '8', '1516002532', '1', 'gift_gif_8.svga%@%cloudtype=1', '7.50');
INSERT INTO `cmf_gift` VALUES ('9', '1', '0', '0', '旋转木马', '5', '', 'gift_9.png%@%cloudtype=1', '7', '1516002591', '1', 'gift_gif_9.svga%@%cloudtype=1', '5.00');
INSERT INTO `cmf_gift` VALUES ('10', '1', '0', '0', '游轮', '50', '', 'gift_10.png%@%cloudtype=1', '16', '1525512864', '1', 'gift_gif_10.svga%@%cloudtype=1', '4.90');
INSERT INTO `cmf_gift` VALUES ('11', '2', '0', '0', '百合', '1', '', 'gift_11.png%@%cloudtype=1', '1', '1525512917', '0', '', '0.00');
INSERT INTO `cmf_gift` VALUES ('12', '0', '2', '0', '棒棒糖', '1', '', 'gift_12.png%@%cloudtype=1', '2', '1525512954', '0', '', '0.00');
INSERT INTO `cmf_gift` VALUES ('13', '0', '0', '0', '藏宝箱', '3', '', 'gift_13.png%@%cloudtype=1', '400', '1525513174', '0', '', '0.00');
INSERT INTO `cmf_gift` VALUES ('14', '0', '0', '0', '蛋糕', '2', '', 'gift_14.png%@%cloudtype=1', '15', '1525513203', '0', '', '0.00');
INSERT INTO `cmf_gift` VALUES ('15', '0', '0', '0', '粉丝牌', '80', '', 'gift_15.png%@%cloudtype=1', '23', '1542963809', '0', '', '0.00');
INSERT INTO `cmf_gift` VALUES ('16', '0', '0', '0', '干杯', '99', '', 'gift_16.png%@%cloudtype=1', '25', '1542964002', '0', '', '0.00');
INSERT INTO `cmf_gift` VALUES ('17', '0', '0', '0', '皇冠', '15', '', 'gift_17.png%@%cloudtype=1', '24', '1542964120', '0', '', '0.00');
INSERT INTO `cmf_gift` VALUES ('18', '0', '0', '0', '金话筒', '8888', '', 'gift_18.png%@%cloudtype=1', '27', '1542964496', '0', '', '0.00');
INSERT INTO `cmf_gift` VALUES ('19', '0', '0', '0', '玫瑰', '8888', '', 'gift_19.png%@%cloudtype=1', '26', '1542964631', '0', '', '0.00');
INSERT INTO `cmf_gift` VALUES ('20', '0', '0', '0', '魔法棒', '6666', '', 'gift_20.png%@%cloudtype=1', '28', '1542965479', '0', '', '0.00');
INSERT INTO `cmf_gift` VALUES ('21', '0', '0', '0', '情书', '33440', '', 'gift_21.png%@%cloudtype=1', '29', '1542965672', '0', '', '0.00');
INSERT INTO `cmf_gift` VALUES ('22', '0', '0', '0', '圣诞袜子', '1000', '', 'gift_22.png%@%cloudtype=1', '30', '1547631679', '0', '', '0.00');
INSERT INTO `cmf_gift` VALUES ('23', '0', '0', '0', '水晶球', '25', '', 'gift_23.png%@%cloudtype=1', '31', '1547713779', '0', '', '0.00');
INSERT INTO `cmf_gift` VALUES ('24', '0', '0', '0', '甜心巧克力', '20', '', 'gift_24.png%@%cloudtype=1', '18', '1547714056', '0', '', '0.00');
INSERT INTO `cmf_gift` VALUES ('25', '0', '0', '0', '幸运福袋', '30', '', 'gift_25.png%@%cloudtype=1', '19', '1547714262', '0', '', '0.00');
INSERT INTO `cmf_gift` VALUES ('26', '0', '0', '0', '樱花奶茶', '60', '', 'gift_26.png%@%cloudtype=1', '20', '1547714386', '0', '', '0.00');
INSERT INTO `cmf_gift` VALUES ('27', '2', '0', '0', '招财猫', '100', '', 'gift_27.png%@%cloudtype=1', '17', '1547779394', '0', '', '0.00');
INSERT INTO `cmf_gift` VALUES ('28', '0', '0', '0', '钻戒', '20', '', 'gift_28.png%@%cloudtype=1', '100', '1578386860', '0', '', '0.00');
INSERT INTO `cmf_gift` VALUES ('62', '0', '0', '0', '四叶草', '30', '', 'gift_62.png%@%cloudtype=1', '200', '1578386860', '0', '', '0.00');
INSERT INTO `cmf_gift` VALUES ('74', '0', '0', '0', '星星', '40', '', 'gift_74.png%@%cloudtype=1', '300', '1578386860', '0', '', '0.00');
INSERT INTO `cmf_gift` VALUES ('75', '1', '0', '0', '百变小丑', '1000', '', 'gift_29.png%@%cloudtype=1', '32', '1578386860', '1', 'gift_gif_29.svga%@%cloudtype=1', '5.00');
INSERT INTO `cmf_gift` VALUES ('76', '1', '0', '0', '钞票枪', '1000', '', 'gift_30.png%@%cloudtype=1', '33', '1578386860', '1', 'gift_gif_30.svga%@%cloudtype=1', '2.90');
INSERT INTO `cmf_gift` VALUES ('77', '1', '0', '0', '星际火箭', '1000', '', 'gift_31.png%@%cloudtype=1', '34', '1578386860', '1', 'gift_gif_31.svga%@%cloudtype=1', '3.70');

-- ----------------------------
-- Table structure for `cmf_guard`
-- ----------------------------
DROP TABLE IF EXISTS `cmf_guard`;
CREATE TABLE `cmf_guard` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL DEFAULT '' COMMENT '守护名称',
  `type` tinyint(1) NOT NULL DEFAULT '0' COMMENT '守护类型，1普通2尊贵',
  `coin` int(11) NOT NULL DEFAULT '0' COMMENT '价格',
  `length_type` tinyint(1) NOT NULL DEFAULT '0' COMMENT '时长类型，0天，1月，2年',
  `length` int(11) NOT NULL DEFAULT '0' COMMENT '时长',
  `length_time` int(11) NOT NULL DEFAULT '0' COMMENT '时长秒数',
  `addtime` int(11) NOT NULL DEFAULT '0' COMMENT '添加时间',
  `uptime` int(11) NOT NULL DEFAULT '0' COMMENT '更新时间',
  `list_order` int(11) NOT NULL DEFAULT '9999' COMMENT '序号',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Records of cmf_guard
-- ----------------------------
INSERT INTO `cmf_guard` VALUES ('1', '7天体验', '1', '30', '0', '7', '604800', '1540862056', '1603760311', '0');
INSERT INTO `cmf_guard` VALUES ('2', '1个月', '1', '100', '1', '1', '2592000', '1540862139', '1603760320', '1');
INSERT INTO `cmf_guard` VALUES ('3', '尊贵守护全年', '2', '1200', '2', '1', '31536000', '1540862377', '1603760328', '2');

-- ----------------------------
-- Table structure for `cmf_guard_user`
-- ----------------------------
DROP TABLE IF EXISTS `cmf_guard_user`;
CREATE TABLE `cmf_guard_user` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(11) NOT NULL DEFAULT '0' COMMENT '用户ID',
  `liveuid` int(11) NOT NULL DEFAULT '0' COMMENT '主播ID',
  `type` tinyint(1) NOT NULL DEFAULT '0' COMMENT '守护类型,1普通守护，2尊贵守护',
  `endtime` int(11) NOT NULL DEFAULT '0' COMMENT '到期时间',
  `addtime` int(11) NOT NULL DEFAULT '0' COMMENT '添加时间',
  PRIMARY KEY (`id`) USING BTREE,
  KEY `liveuid_index` (`liveuid`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Records of cmf_guard_user
-- ----------------------------

-- ----------------------------
-- Table structure for `cmf_guide`
-- ----------------------------
DROP TABLE IF EXISTS `cmf_guide`;
CREATE TABLE `cmf_guide` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `thumb` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '图片/视频链接',
  `href` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '跳转链接',
  `type` tinyint(1) NOT NULL DEFAULT '0' COMMENT '类型',
  `orderno` int(11) NOT NULL DEFAULT '10000' COMMENT '序号',
  `addtime` int(11) NOT NULL DEFAULT '0' COMMENT '添加时间',
  `uptime` int(11) NOT NULL DEFAULT '0' COMMENT '更新时间',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=COMPACT;

-- ----------------------------
-- Records of cmf_guide
-- ----------------------------

-- ----------------------------
-- Table structure for `cmf_hook`
-- ----------------------------
DROP TABLE IF EXISTS `cmf_hook`;
CREATE TABLE `cmf_hook` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `type` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '钩子类型(1:系统钩子;2:应用钩子;3:模板钩子;4:后台模板钩子)',
  `once` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '是否只允许一个插件运行(0:多个;1:一个)',
  `name` varchar(50) NOT NULL DEFAULT '' COMMENT '钩子名称',
  `hook` varchar(50) NOT NULL DEFAULT '' COMMENT '钩子',
  `app` varchar(15) NOT NULL DEFAULT '' COMMENT '应用名(只有应用钩子才用)',
  `description` varchar(255) NOT NULL DEFAULT '' COMMENT '描述',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=72 DEFAULT CHARSET=utf8mb4 ROW_FORMAT=COMPACT COMMENT='系统钩子表';

-- ----------------------------
-- Records of cmf_hook
-- ----------------------------
INSERT INTO `cmf_hook` VALUES ('2', '1', '0', '应用开始', 'app_begin', 'cmf', '应用开始');
INSERT INTO `cmf_hook` VALUES ('3', '1', '0', '模块初始化', 'module_init', 'cmf', '模块初始化');
INSERT INTO `cmf_hook` VALUES ('4', '1', '0', '控制器开始', 'action_begin', 'cmf', '控制器开始');
INSERT INTO `cmf_hook` VALUES ('5', '1', '0', '视图输出过滤', 'view_filter', 'cmf', '视图输出过滤');
INSERT INTO `cmf_hook` VALUES ('6', '1', '0', '应用结束', 'app_end', 'cmf', '应用结束');
INSERT INTO `cmf_hook` VALUES ('7', '1', '0', '日志write方法', 'log_write', 'cmf', '日志write方法');
INSERT INTO `cmf_hook` VALUES ('8', '1', '0', '输出结束', 'response_end', 'cmf', '输出结束');
INSERT INTO `cmf_hook` VALUES ('9', '1', '0', '后台控制器初始化', 'admin_init', 'cmf', '后台控制器初始化');
INSERT INTO `cmf_hook` VALUES ('10', '1', '0', '前台控制器初始化', 'home_init', 'cmf', '前台控制器初始化');
INSERT INTO `cmf_hook` VALUES ('11', '1', '1', '发送手机验证码', 'send_mobile_verification_code', 'cmf', '发送手机验证码');
INSERT INTO `cmf_hook` VALUES ('12', '3', '0', '模板 body标签开始', 'body_start', '', '模板 body标签开始');
INSERT INTO `cmf_hook` VALUES ('13', '3', '0', '模板 head标签结束前', 'before_head_end', '', '模板 head标签结束前');
INSERT INTO `cmf_hook` VALUES ('14', '3', '0', '模板底部开始', 'footer_start', '', '模板底部开始');
INSERT INTO `cmf_hook` VALUES ('15', '3', '0', '模板底部开始之前', 'before_footer', '', '模板底部开始之前');
INSERT INTO `cmf_hook` VALUES ('16', '3', '0', '模板底部结束之前', 'before_footer_end', '', '模板底部结束之前');
INSERT INTO `cmf_hook` VALUES ('17', '3', '0', '模板 body 标签结束之前', 'before_body_end', '', '模板 body 标签结束之前');
INSERT INTO `cmf_hook` VALUES ('18', '3', '0', '模板左边栏开始', 'left_sidebar_start', '', '模板左边栏开始');
INSERT INTO `cmf_hook` VALUES ('19', '3', '0', '模板左边栏结束之前', 'before_left_sidebar_end', '', '模板左边栏结束之前');
INSERT INTO `cmf_hook` VALUES ('20', '3', '0', '模板右边栏开始', 'right_sidebar_start', '', '模板右边栏开始');
INSERT INTO `cmf_hook` VALUES ('21', '3', '0', '模板右边栏结束之前', 'before_right_sidebar_end', '', '模板右边栏结束之前');
INSERT INTO `cmf_hook` VALUES ('22', '3', '1', '评论区', 'comment', '', '评论区');
INSERT INTO `cmf_hook` VALUES ('23', '3', '1', '留言区', 'guestbook', '', '留言区');
INSERT INTO `cmf_hook` VALUES ('24', '2', '0', '后台首页仪表盘', 'admin_dashboard', 'admin', '后台首页仪表盘');
INSERT INTO `cmf_hook` VALUES ('25', '4', '0', '后台模板 head标签结束前', 'admin_before_head_end', '', '后台模板 head标签结束前');
INSERT INTO `cmf_hook` VALUES ('26', '4', '0', '后台模板 body 标签结束之前', 'admin_before_body_end', '', '后台模板 body 标签结束之前');
INSERT INTO `cmf_hook` VALUES ('27', '2', '0', '后台登录页面', 'admin_login', 'admin', '后台登录页面');
INSERT INTO `cmf_hook` VALUES ('28', '1', '1', '前台模板切换', 'switch_theme', 'cmf', '前台模板切换');
INSERT INTO `cmf_hook` VALUES ('29', '3', '0', '主要内容之后', 'after_content', '', '主要内容之后');
INSERT INTO `cmf_hook` VALUES ('32', '2', '1', '获取上传界面', 'fetch_upload_view', 'user', '获取上传界面');
INSERT INTO `cmf_hook` VALUES ('33', '3', '0', '主要内容之前', 'before_content', 'cmf', '主要内容之前');
INSERT INTO `cmf_hook` VALUES ('34', '1', '0', '日志写入完成', 'log_write_done', 'cmf', '日志写入完成');
INSERT INTO `cmf_hook` VALUES ('35', '1', '1', '后台模板切换', 'switch_admin_theme', 'cmf', '后台模板切换');
INSERT INTO `cmf_hook` VALUES ('36', '1', '1', '验证码图片', 'captcha_image', 'cmf', '验证码图片');
INSERT INTO `cmf_hook` VALUES ('37', '2', '1', '后台模板设计界面', 'admin_theme_design_view', 'admin', '后台模板设计界面');
INSERT INTO `cmf_hook` VALUES ('38', '2', '1', '后台设置网站信息界面', 'admin_setting_site_view', 'admin', '后台设置网站信息界面');
INSERT INTO `cmf_hook` VALUES ('39', '2', '1', '后台清除缓存界面', 'admin_setting_clear_cache_view', 'admin', '后台清除缓存界面');
INSERT INTO `cmf_hook` VALUES ('40', '2', '1', '后台导航管理界面', 'admin_nav_index_view', 'admin', '后台导航管理界面');
INSERT INTO `cmf_hook` VALUES ('41', '2', '1', '后台友情链接管理界面', 'admin_link_index_view', 'admin', '后台友情链接管理界面');
INSERT INTO `cmf_hook` VALUES ('42', '2', '1', '后台幻灯片管理界面', 'admin_slide_index_view', 'admin', '后台幻灯片管理界面');
INSERT INTO `cmf_hook` VALUES ('43', '2', '1', '后台管理员列表界面', 'admin_user_index_view', 'admin', '后台管理员列表界面');
INSERT INTO `cmf_hook` VALUES ('44', '2', '1', '后台角色管理界面', 'admin_rbac_index_view', 'admin', '后台角色管理界面');
INSERT INTO `cmf_hook` VALUES ('49', '2', '1', '用户管理本站用户列表界面', 'user_admin_index_view', 'user', '用户管理本站用户列表界面');
INSERT INTO `cmf_hook` VALUES ('50', '2', '1', '资源管理列表界面', 'user_admin_asset_index_view', 'user', '资源管理列表界面');
INSERT INTO `cmf_hook` VALUES ('51', '2', '1', '用户管理第三方用户列表界面', 'user_admin_oauth_index_view', 'user', '用户管理第三方用户列表界面');
INSERT INTO `cmf_hook` VALUES ('52', '2', '1', '后台首页界面', 'admin_index_index_view', 'admin', '后台首页界面');
INSERT INTO `cmf_hook` VALUES ('53', '2', '1', '后台回收站界面', 'admin_recycle_bin_index_view', 'admin', '后台回收站界面');
INSERT INTO `cmf_hook` VALUES ('54', '2', '1', '后台菜单管理界面', 'admin_menu_index_view', 'admin', '后台菜单管理界面');
INSERT INTO `cmf_hook` VALUES ('55', '2', '1', '后台自定义登录是否开启钩子', 'admin_custom_login_open', 'admin', '后台自定义登录是否开启钩子');
INSERT INTO `cmf_hook` VALUES ('64', '2', '1', '后台幻灯片页面列表界面', 'admin_slide_item_index_view', 'admin', '后台幻灯片页面列表界面');
INSERT INTO `cmf_hook` VALUES ('65', '2', '1', '后台幻灯片页面添加界面', 'admin_slide_item_add_view', 'admin', '后台幻灯片页面添加界面');
INSERT INTO `cmf_hook` VALUES ('66', '2', '1', '后台幻灯片页面编辑界面', 'admin_slide_item_edit_view', 'admin', '后台幻灯片页面编辑界面');
INSERT INTO `cmf_hook` VALUES ('67', '2', '1', '后台管理员添加界面', 'admin_user_add_view', 'admin', '后台管理员添加界面');
INSERT INTO `cmf_hook` VALUES ('68', '2', '1', '后台管理员编辑界面', 'admin_user_edit_view', 'admin', '后台管理员编辑界面');
INSERT INTO `cmf_hook` VALUES ('69', '2', '1', '后台角色添加界面', 'admin_rbac_role_add_view', 'admin', '后台角色添加界面');
INSERT INTO `cmf_hook` VALUES ('70', '2', '1', '后台角色编辑界面', 'admin_rbac_role_edit_view', 'admin', '后台角色编辑界面');
INSERT INTO `cmf_hook` VALUES ('71', '2', '1', '后台角色授权界面', 'admin_rbac_authorize_view', 'admin', '后台角色授权界面');

-- ----------------------------
-- Table structure for `cmf_hook_plugin`
-- ----------------------------
DROP TABLE IF EXISTS `cmf_hook_plugin`;
CREATE TABLE `cmf_hook_plugin` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `list_order` float NOT NULL DEFAULT '10000' COMMENT '排序',
  `status` tinyint(4) NOT NULL DEFAULT '1' COMMENT '状态(0:禁用,1:启用)',
  `hook` varchar(50) NOT NULL DEFAULT '' COMMENT '钩子名',
  `plugin` varchar(50) NOT NULL DEFAULT '' COMMENT '插件',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 ROW_FORMAT=COMPACT COMMENT='系统钩子插件表';

-- ----------------------------
-- Records of cmf_hook_plugin
-- ----------------------------

-- ----------------------------
-- Table structure for `cmf_label`
-- ----------------------------
DROP TABLE IF EXISTS `cmf_label`;
CREATE TABLE `cmf_label` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL DEFAULT '' COMMENT '标题',
  `thumb` varchar(255) NOT NULL DEFAULT '' COMMENT '封面',
  `des` varchar(255) NOT NULL DEFAULT '' COMMENT '说明',
  `orderno` int(11) NOT NULL DEFAULT '10000' COMMENT '序号',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb4 ROW_FORMAT=COMPACT;

-- ----------------------------
-- Records of cmf_label
-- ----------------------------
INSERT INTO `cmf_label` VALUES ('1', '家常菜', 'label11.png%@%cloudtype=1', '家常菜描述', '9');
INSERT INTO `cmf_label` VALUES ('2', '萌宠', 'label10.png%@%cloudtype=1', '宠物描述', '8');
INSERT INTO `cmf_label` VALUES ('4', '唱歌', 'label9.png%@%cloudtype=1', '一起来唱歌吧~', '7');
INSERT INTO `cmf_label` VALUES ('5', '小姐姐', 'label8.png%@%cloudtype=1', '小姐姐', '6');
INSERT INTO `cmf_label` VALUES ('6', '明星', 'label7.png%@%cloudtype=1', '明星', '5');
INSERT INTO `cmf_label` VALUES ('7', '欧巴', 'label6.png%@%cloudtype=1', '欧巴', '4');
INSERT INTO `cmf_label` VALUES ('8', '小清新', 'label5.png%@%cloudtype=1', '小清新', '3');
INSERT INTO `cmf_label` VALUES ('9', '韩国', 'label4.png%@%cloudtype=1', '韩国', '2');
INSERT INTO `cmf_label` VALUES ('10', '乡村', 'label3.png%@%cloudtype=1', '乡村', '1');
INSERT INTO `cmf_label` VALUES ('13', '运动', 'label1.png%@%cloudtype=1', '一起来运动吧~', '10');

-- ----------------------------
-- Table structure for `cmf_link`
-- ----------------------------
DROP TABLE IF EXISTS `cmf_link`;
CREATE TABLE `cmf_link` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `status` tinyint(3) unsigned NOT NULL DEFAULT '1' COMMENT '状态;1:显示;0:不显示',
  `rating` int(11) NOT NULL DEFAULT '0' COMMENT '友情链接评级',
  `list_order` float NOT NULL DEFAULT '10000' COMMENT '排序',
  `description` varchar(255) NOT NULL DEFAULT '' COMMENT '友情链接描述',
  `url` varchar(255) NOT NULL DEFAULT '' COMMENT '友情链接地址',
  `name` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '友情链接名称',
  `image` varchar(100) NOT NULL DEFAULT '' COMMENT '友情链接图标',
  `target` varchar(10) NOT NULL DEFAULT '' COMMENT '友情链接打开方式',
  `rel` varchar(50) NOT NULL DEFAULT '' COMMENT '链接与网站的关系',
  PRIMARY KEY (`id`) USING BTREE,
  KEY `status` (`status`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 ROW_FORMAT=COMPACT COMMENT='友情链接表';

-- ----------------------------
-- Records of cmf_link
-- ----------------------------
INSERT INTO `cmf_link` VALUES ('1', '1', '1', '8', 'thinkcmf官网', 'http://www.thinkcmf.com', 'ThinkCMF', '', '_blank', '');

-- ----------------------------
-- Table structure for `cmf_live_class`
-- ----------------------------
DROP TABLE IF EXISTS `cmf_live_class`;
CREATE TABLE `cmf_live_class` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL DEFAULT '' COMMENT '分类名',
  `des` varchar(255) NOT NULL DEFAULT '' COMMENT '描述',
  `list_order` int(11) NOT NULL DEFAULT '9999' COMMENT '序号',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Records of cmf_live_class
-- ----------------------------
INSERT INTO `cmf_live_class` VALUES ('1', '音乐', '流行、摇滚、说唱、民族等，线上最强演唱会', '13');
INSERT INTO `cmf_live_class` VALUES ('2', '舞蹈', '现代舞、钢管舞、肚皮舞等，谈恋爱不如跳舞', '14');
INSERT INTO `cmf_live_class` VALUES ('3', '户外', '街头、野外任你选择，健身、旅行任你畅玩', '2');
INSERT INTO `cmf_live_class` VALUES ('4', '校园', '学生党分享校园乐事', '4');
INSERT INTO `cmf_live_class` VALUES ('5', '交友', '单身男女聚集地，线上交友趣事多', '5');
INSERT INTO `cmf_live_class` VALUES ('6', '喊麦', '欧美有RAP，我们有MC', '6');
INSERT INTO `cmf_live_class` VALUES ('7', '游戏', '是时候展示你真正的技术了', '7');
INSERT INTO `cmf_live_class` VALUES ('8', '直播购', '买买买，分享最美好的东西', '1');
INSERT INTO `cmf_live_class` VALUES ('9', '美食', '吃货？主厨？唯美食不可辜负', '9');
INSERT INTO `cmf_live_class` VALUES ('10', '才艺', '手工艺、魔术、画画、化妆等，艺术高手在民间', '10');
INSERT INTO `cmf_live_class` VALUES ('11', '男神', '前方有一大波迷妹即将赶到', '11');
INSERT INTO `cmf_live_class` VALUES ('12', '女神', '高颜值，好身材就要秀出来', '12');

-- ----------------------------
-- Table structure for `cmf_nav`
-- ----------------------------
DROP TABLE IF EXISTS `cmf_nav`;
CREATE TABLE `cmf_nav` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `is_main` tinyint(3) unsigned NOT NULL DEFAULT '1' COMMENT '是否为主导航;1:是;0:不是',
  `name` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '导航位置名称',
  `remark` varchar(255) CHARACTER SET utf8 NOT NULL DEFAULT '' COMMENT '备注',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 ROW_FORMAT=COMPACT COMMENT='前台导航位置表';

-- ----------------------------
-- Records of cmf_nav
-- ----------------------------
INSERT INTO `cmf_nav` VALUES ('1', '1', '主导航', '主导航');
INSERT INTO `cmf_nav` VALUES ('2', '0', '底部导航', '');

-- ----------------------------
-- Table structure for `cmf_nav_menu`
-- ----------------------------
DROP TABLE IF EXISTS `cmf_nav_menu`;
CREATE TABLE `cmf_nav_menu` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nav_id` int(11) NOT NULL COMMENT '导航 id',
  `parent_id` int(11) NOT NULL COMMENT '父 id',
  `status` tinyint(3) unsigned NOT NULL DEFAULT '1' COMMENT '状态;1:显示;0:隐藏',
  `list_order` float NOT NULL DEFAULT '10000' COMMENT '排序',
  `name` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '菜单名称',
  `target` varchar(10) NOT NULL DEFAULT '' COMMENT '打开方式',
  `href` varchar(100) NOT NULL DEFAULT '' COMMENT '链接',
  `icon` varchar(20) NOT NULL DEFAULT '' COMMENT '图标',
  `path` varchar(255) NOT NULL DEFAULT '' COMMENT '层级关系',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 ROW_FORMAT=COMPACT COMMENT='前台导航菜单表';

-- ----------------------------
-- Records of cmf_nav_menu
-- ----------------------------
INSERT INTO `cmf_nav_menu` VALUES ('1', '1', '0', '1', '0', '首页', '', 'home', '', '0-1');

-- ----------------------------
-- Table structure for `cmf_option`
-- ----------------------------
DROP TABLE IF EXISTS `cmf_option`;
CREATE TABLE `cmf_option` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `autoload` tinyint(3) unsigned NOT NULL DEFAULT '1' COMMENT '是否自动加载;1:自动加载;0:不自动加载',
  `option_name` varchar(64) NOT NULL DEFAULT '' COMMENT '配置名',
  `option_value` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci COMMENT '配置值',
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE KEY `option_name` (`option_name`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 ROW_FORMAT=COMPACT COMMENT='全站配置表';

-- ----------------------------
-- Records of cmf_option
-- ----------------------------
INSERT INTO `cmf_option` VALUES ('1', '1', 'site_info', '{\"site_name\":\"\",\"site_seo_title\":\"\",\"site_seo_keywords\":\"\",\"site_seo_description\":\"\",\"site_icp\":\"\",\"site_gwa\":\"\",\"site_admin_email\":\"\",\"site_analytics\":\"\",\"app_name\":\"\",\"sitename\":\"\",\"site\":\"\",\"name_votes\":\"\\u4e91\\u7968\",\"qq\":\"\",\"mobile\":\"\",\"apk_ver\":\"\",\"apk_url\":\"\",\"apk_des\":\"\\u7248\\u672c\\u4f18\\u5316\\uff0c\\u8bf7\\u53ca\\u65f6\\u66f4\\u65b0\",\"ipa_ver\":\"\",\"ios_shelves\":\"1\",\"ipa_url\":\"\",\"ipa_des\":\"\\u7248\\u672c\\u4f18\\u5316\\uff0c\\u8bf7\\u53ca\\u65f6\\u66f4\\u65b0\",\"app_android\":\"\",\"app_ios\":\"\",\"video_share_title\":\"\\u5206\\u4eab\\u4e86{username}\\u7684\\u7cbe\\u5f69\\u89c6\\u9891\\uff01\",\"video_share_des\":\"\\u6211\\u770b\\u5230\\u4e00\\u4e2a\\u5f88\\u597d\\u770b\\u7684\\u89c6\\u9891\\uff0c\\u5feb\\u6765\\u8ddf\\u6211\\u4e00\\u8d77\\u56f4\\u89c2\\u5427\",\"agent_share_title\":\"\\u9080\\u8bf7\\u597d\\u53cb\\u5206\\u4eab\\u6807\\u9898\",\"agent_share_des\":\"\\u9080\\u8bf7\\u597d\\u53cb\\u5206\\u4eab\\u8bdd\\u672f\",\"copyright\":\"\",\"watermark\":\"\",\"qr_url\":\"\",\"login_type\":\"qq,wx\",\"share_type\":\"wx,wchat,qzone,qq\",\"name_coin\":\"\\u4e91\\u5e01\",\"share_title\":\"\\u5206\\u4eab\\u4e86{username}\\u7684\\u7cbe\\u5f69\\u76f4\\u64ad\\uff01\",\"share_des\":\"\\u6211\\u770b\\u5230\\u4e00\\u4e2a\\u5f88\\u597d\\u770b\\u7684\\u76f4\\u64ad\\uff0c\\u5feb\\u6765\\u8ddf\\u6211\\u4e00\\u8d77\\u56f4\\u89c2\\u5427\",\"sprout_key\":\"\",\"sprout_key_ios\":\"\",\"skin_whiting\":\"5\",\"skin_smooth\":\"5\",\"skin_tenderness\":\"5\",\"eye_brow\":\"10\",\"big_eye\":\"10\",\"eye_length\":\"10\",\"eye_corner\":\"10\",\"eye_alat\":\"10\",\"face_lift\":\"10\",\"face_shave\":\"10\",\"mouse_lift\":\"10\",\"nose_lift\":\"10\",\"chin_lift\":\"10\",\"forehead_lift\":\"10\",\"lengthen_noseLift\":\"10\",\"login_alert_title\":\"\\u670d\\u52a1\\u534f\\u8bae\\u548c\\u9690\\u79c1\\u653f\\u7b56\",\"login_alert_content\":\"\\u8bf7\\u60a8\\u52a1\\u5fc5\\u4ed4\\u7ec6\\u9605\\u8bfb\\uff0c\\u5145\\u5206\\u7406\\u89e3\\u670d\\u52a1\\u534f\\u8bae\\u548c\\u9690\\u79c1\\u653f\\u7b56\\u5404\\u6761\\u6b3e\\uff0c\\u5305\\u62ec\\u4f46\\u4e0d\\u9650\\u4e8e\\u4e3a\\u4e86\\u5411\\u60a8\\u63d0\\u4f9b\\u5373\\u65f6\\u901a\\u8baf\\uff0c\\u5185\\u5bb9\\u5206\\u4eab\\u7b49\\u670d\\u52a1\\uff0c\\u6211\\u4eec\\u9700\\u8981\\u6536\\u96c6\\u60a8\\u8bbe\\u5907\\u4fe1\\u606f\\u548c\\u4e2a\\u4eba\\u4fe1\\u606f\\uff0c\\u60a8\\u53ef\\u4ee5\\u5728\\u8bbe\\u7f6e\\u4e2d\\u67e5\\u770b\\uff0c\\u7ba1\\u7406\\u60a8\\u7684\\u6388\\u6743\\u3002\\u60a8\\u53ef\\u9605\\u8bfb\\u300a\\u9690\\u79c1\\u653f\\u7b56\\u300b\\u548c\\u300a\\u670d\\u52a1\\u534f\\u8bae\\u300b\\u4e86\\u89e3\\u8be6\\u7ec6\\u4fe1\\u606f\\uff0c\\u5982\\u60a8\\u540c\\u610f\\uff0c\\u8bf7\\u70b9\\u51fb\\u540c\\u610f\\u63a5\\u53d7\\u6211\\u4eec\\u7684\\u670d\\u52a1\\u3002\",\"login_clause_title\":\"\\u767b\\u5f55\\u5373\\u4ee3\\u8868\\u540c\\u610f\\u300a\\u9690\\u79c1\\u653f\\u7b56\\u300b\\u548c\\u300a\\u670d\\u52a1\\u534f\\u8bae\\u300b\",\"login_private_title\":\"\\u300a\\u9690\\u79c1\\u653f\\u7b56\\u300b\",\"login_private_url\":\"\\/portal\\/page\\/index?id=26\",\"login_service_title\":\"\\u300a\\u670d\\u52a1\\u534f\\u8bae\\u300b\",\"login_service_url\":\"\\/portal\\/page\\/index?id=38\",\"qr_url_ios\":\"\",\"company_name\":\"\",\"company_desc\":\"\",\"wx_siteurl\":\"http:\\/\\/x.com\\/wxshare\\/Share\\/show?roomnum=\",\"brightness\":\"50\",\"recommend_classname\":\"\\u63a8\\u8350\",\"copyright_url\":\"\"}');
INSERT INTO `cmf_option` VALUES ('2', '1', 'cmf_settings', '{\"open_registration\":\"0\",\"banned_usernames\":\"\"}');
INSERT INTO `cmf_option` VALUES ('3', '1', 'cdn_settings', '{\"cdn_static_root\":\"\"}');
INSERT INTO `cmf_option` VALUES ('4', '1', 'admin_settings', '{\"admin_password\":\"\",\"admin_theme\":\"admin_simpleboot3\",\"admin_style\":\"flatadmin\"}');
INSERT INTO `cmf_option` VALUES ('5', '1', 'configpri', '{\"cache_switch\":\"1\",\"cache_time\":\"60\",\"auth_islimit\":\"0\",\"private_letter_switch\":\"1\",\"private_letter_nums\":\"5\",\"video_audit_switch\":\"0\",\"sendcode_switch\":\"0\",\"ccp_sid\":\"\",\"ccp_token\":\"\",\"ccp_appid\":\"\",\"ccp_tempid\":\"\",\"iplimit_switch\":\"0\",\"iplimit_times\":\"5\",\"jpush_key\":\"\",\"jpush_secret\":\"\",\"video_showtype\":\"0\",\"ad_video_switch\":\"1\",\"video_ad_num\":\"5\",\"comment_weight\":\"10000\",\"like_weight\":\"10000\",\"share_weight\":\"10000\",\"show_val\":\"10000\",\"hour_minus_val\":\"1\",\"um_apikey\":\"\",\"um_apisecurity\":\"\",\"um_appkey_android\":\"\",\"um_appkey_ios\":\"\",\"shop_fans\":\"0\",\"shop_videos\":\"0\",\"show_switch\":\"1\",\"agent_reward\":\"10\",\"agent_v_l\":\"1\",\"agent_v_a\":\"30\",\"agent_a\":\"20\",\"aliapp_switch\":\"1\",\"aliapp_partner\":\"\",\"aliapp_seller_id\":\"\",\"aliapp_key\":\"\",\"wx_switch\":\"1\",\"wx_appid\":\"\",\"wx_appsecret\":\"\",\"wx_mchid\":\"\",\"wx_key\":\"\",\"cash_rate\":\"100\",\"cash_min\":\"10\",\"popular_interval\":\"3\",\"popular_base\":\"10\",\"popular_tips\":\"\\u652f\\u4ed8\\u540e\\u89c6\\u9891\\u5c06\\u8fdb\\u5165\\u6295\\u653e\\u9636\\u6bb5\\uff0c\\u5982\\u679c\\u6295\\u653e\\u8fc7\\u7a0b\\u4e2d\\u9047\\u5230\\u5982\\u89c6\\u9891\\u5220\\u9664\\/\\u4eba\\u5de5\\u4e3e\\u62a5\\uff0c\\u6216\\u6295\\u653e\\u65f6\\u95f4\\u7ed3\\u675f\\u672a\\u8fbe\\u5230\\u9884\\u8ba1\\u64ad\\u653e\\u91cf\\u65f6\\uff0c\\u5269\\u4f59\\u672a\\u6d88\\u8017\\u7684\\u91d1\\u989d\\u5c06\\u9000\\u56de\\u5230\\u60a8\\u7684\\u63a8\\u5e7f\\u8d26\\u6237\\u4f59\\u989d\\u4e2d\\uff0c\\u53ef\\u4f9b\\u4e0b\\u6b21\\u8d2d\\u4e70\\u4f7f\\u7528\\u3002\",\"sprout_key\":\"\",\"login_type\":\"\",\"share_type\":\"\",\"qiniu_accesskey\":\"\",\"qiniu_secretkey\":\"\",\"qiniu_bucket\":\"\",\"qiniu_domain\":\"\",\"qiniu_domain_url\":\"\",\"txcloud_appid\":\"\",\"txcloud_secret_id\":\"\",\"txcloud_secret_key\":\"\",\"txcloud_region\":\"\",\"txcloud_bucket\":\"\",\"tximgfolder\":\"\",\"txvideofolder\":\"\",\"txuserimgfolder\":\"\",\"cloudtype\":\"1\",\"tx_domain_url\":\"\",\"\'cache_switch\'\":\"1\",\"same_device_ip_regnums\":\"10\",\"jpush_sandbox\":\"0\",\"jpush_switch\":\"1\",\"ad_video_loop\":\"1\",\"agent_switch\":\"1\",\"agent_must\":\"0\",\"ios_switch\":\"0\",\"vip_aliapp_switch\":\"1\",\"vip_ios_switch\":\"0\",\"vip_wx_switch\":\"1\",\"vip_coin_switch\":\"1\",\"vip_switch\":\"1\",\"nonvip_upload_nums\":\"5\",\"watch_video_type\":\"1\",\"nonvip_watch_nums\":\"5\",\"tx_private_signature\":\"0\",\"live_videos\":\"0\",\"live_fans\":\"0\",\"tx_appid\":\"\",\"tx_bizid\":\"\",\"tx_push_key\":\"\",\"tx_api_key\":\"\",\"tx_push\":\"\",\"tx_pull\":\"\",\"userlist_time\":\"15\",\"chatserver\":\"\",\"cdn_switch\":\"1\",\"sensitive_words\":\"\\u6bdb\\u6cfd\\u4e1c,\\u6c5f\\u6cfd\\u6c11,\\u6731\\u9555\\u57fa,\\u674e\\u9e4f,\\u9093\\u5c0f\\u5e73,\\u80e1\\u9526\\u6d9b,\\u6e29\\u5bb6\\u5b9d,\\u4e60\\u8fd1\\u5e73,\\u674e\\u514b\\u5f3a,\\u8584\\u7199\\u6765,\\u5468\\u6c38\\u5eb7,\\u50bb\\u903c,\\u6eda\\u4f60\\u5988,\\u654f\\u611f\\u8bcd\\r\\n\",\"aliapp_key_ios\":\"\",\"login_wx_appid\":\"\",\"login_wx_appsecret\":\"\",\"qiniu_zone\":\"z0\",\"qiniu_protocol\":\"http\",\"code_switch\":\"1\",\"aly_keydi\":\"\",\"aly_secret\":\"\",\"aly_signName\":\"\",\"aly_templateCode\":\"\",\"aly_keyid\":\"\",\"goods_switch\":\"1\",\"reg_reward\":\"0\",\"tx_acc_key\":\"\",\"watch_live_term\":\"6\",\"watch_live_coin\":\"10\",\"watch_video_term\":\"6\",\"watch_video_coin\":\"10\",\"open_live_term\":\"1\",\"open_live_coin\":\"10\",\"award_live_term\":\"500\",\"award_live_coin\":\"500\",\"share_live_term\":\"1\",\"share_live_coin\":\"20\",\"cash_take\":\"25\",\"cash_start\":\"1\",\"cash_end\":\"31\",\"cash_max_times\":\"0\",\"live_txcloud_secret_id\":\"\",\"live_txcloud_secret_key\":\"\",\"aliapp_key_android\":\"\",\"shop_aliapp_switch\":\"1\",\"shop_wx_switch\":\"1\",\"shop_balance_switch\":\"1\",\"shop_system_name\":\"\",\"shop_bond\":\"100\",\"show_category_switch\":\"1\",\"shoporder_percent\":\"10\",\"shop_certificate_desc\":\"\\u4ee5\\u4e0b\\u8425\\u4e1a\\u6267\\u7167\\u4fe1\\u606f\\u6765\\u6e90\\u4e8e\\u4e70\\u5bb6\\u81ea\\u884c\\u7533\\u62a5\\u6216\\u5de5\\u5546\\u7cfb\\u7edf\\u6570\\u636e\\uff0c\\u5177\\u4f53\\u4ee5\\u5de5\\u5546\\u90e8\\u95e8\\u767b\\u8bb0\\u4e3a\\u51c6\\uff0c\\u7ecf\\u8425\\u8005\\u9700\\u8981\\u786e\\u4fdd\\u4fe1\\u606f\\u771f\\u5b9e\\u6709\\u6548\\uff0c\\u5e73\\u53f0\\u4e5f\\u5c06\\u5b9a\\u671f\\u6838\\u67e5\\u3002\\u5982\\u4e0e\\u5b9e\\u9645\\u4e0d\\u7b26\\uff0c\\u4e3a\\u907f\\u514d\\u8fdd\\u89c4\\uff0c\\u8bf7\\u8054\\u7cfb\\u5f53\\u5730\\u5de5\\u5546\\u90e8\\u95e8\\u6216\\u5e73\\u53f0\\u5ba2\\u670d\\u66f4\\u65b0\\u3002\",\"shop_payment_time\":\"3\",\"shop_shipment_time\":\"1\",\"shop_receive_time\":\"2\",\"shop_refund_time\":\"0\",\"shop_refund_finish_time\":\"1\",\"shop_receive_refund_time\":\"1\",\"shop_settlement_time\":\"1\",\"balance_cash_min\":\"1\",\"balance_cash_start\":\"1\",\"balance_cash_end\":\"31\",\"balance_cash_max_times\":\"0\",\"express_id_dev\":\"\",\"express_appkey_dev\":\"\",\"express_id\":\"\",\"express_appkey\":\"\",\"express_type\":\"0\",\"aly_sendcode_type\":\"1\",\"aly_hw_signName\":\"\",\"aly_hw_templateCode\":\"\",\"braintree_paypal_environment\":\"0\",\"braintree_merchantid_sandbox\":\"\",\"braintree_publickey_sandbox\":\"\",\"braintree_privatekey_sandbox\":\"\",\"braintree_merchantid_product\":\"\",\"braintree_publickey_product\":\"\",\"braintree_privatekey_product\":\"\",\"shop_braintree_paypal_switch\":\"1\",\"aws_bucket\":\"\",\"aws_region\":\"\",\"aws_hosturl\":\"\",\"aws_identitypoolid\":\"\",\"openinstall_switch\":\"0\",\"openinstall_appkey\":\"\",\"braintree_paypal_switch\":\"1\",\"vip_braintree_paypal_switch\":\"1\"}');
INSERT INTO `cmf_option` VALUES ('6', '1', 'guide', '{\"switch\":\"1\",\"type\":\"0\",\"time\":\"2\"}');
INSERT INTO `cmf_option` VALUES ('7', '1', 'storage', '{\"storages\":[],\"type\":\"Local\"}');
INSERT INTO `cmf_option` VALUES ('8', '1', 'upload_setting', '{\"max_files\":\"20\",\"chunk_size\":\"512\",\"file_types\":{\"image\":{\"upload_max_filesize\":\"10240\",\"extensions\":\"jpg,jpeg,png,gif,bmp4,svga\"},\"video\":{\"upload_max_filesize\":\"10240\",\"extensions\":\"mp4,m3u8\"},\"audio\":{\"upload_max_filesize\":\"10240\",\"extensions\":\"mp3\"},\"file\":{\"upload_max_filesize\":\"10240\",\"extensions\":\"txt,pdf,doc,docx,xls,xlsx,ppt,pptx,zip,rar,svga\"}}}');

-- ----------------------------
-- Table structure for `cmf_plugin`
-- ----------------------------
DROP TABLE IF EXISTS `cmf_plugin`;
CREATE TABLE `cmf_plugin` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '自增id',
  `type` tinyint(3) unsigned NOT NULL DEFAULT '1' COMMENT '插件类型;1:网站;8:微信',
  `has_admin` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '是否有后台管理,0:没有;1:有',
  `status` tinyint(3) unsigned NOT NULL DEFAULT '1' COMMENT '状态;1:开启;0:禁用',
  `create_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '插件安装时间',
  `name` varchar(50) NOT NULL DEFAULT '' COMMENT '插件标识名,英文字母(惟一)',
  `title` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '插件名称',
  `demo_url` varchar(50) NOT NULL DEFAULT '' COMMENT '演示地址，带协议',
  `hooks` varchar(255) NOT NULL DEFAULT '' COMMENT '实现的钩子;以“,”分隔',
  `author` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '插件作者',
  `author_url` varchar(50) NOT NULL DEFAULT '' COMMENT '作者网站链接',
  `version` varchar(20) NOT NULL DEFAULT '' COMMENT '插件版本号',
  `description` varchar(255) NOT NULL COMMENT '插件描述',
  `config` text COMMENT '插件配置',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 ROW_FORMAT=COMPACT COMMENT='插件表';

-- ----------------------------
-- Records of cmf_plugin
-- ----------------------------

-- ----------------------------
-- Table structure for `cmf_plugins`
-- ----------------------------
DROP TABLE IF EXISTS `cmf_plugins`;
CREATE TABLE `cmf_plugins` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '自增id',
  `name` varchar(50) NOT NULL COMMENT '插件名，英文',
  `title` varchar(50) NOT NULL DEFAULT '' COMMENT '插件名称',
  `description` text COMMENT '插件描述',
  `type` tinyint(2) DEFAULT '0' COMMENT '插件类型, 1:网站；8;微信',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '状态；1开启；',
  `config` text COMMENT '插件配置',
  `hooks` varchar(255) DEFAULT NULL COMMENT '实现的钩子;以“，”分隔',
  `has_admin` tinyint(2) DEFAULT '0' COMMENT '插件是否有后台管理界面',
  `author` varchar(50) DEFAULT '' COMMENT '插件作者',
  `version` varchar(20) DEFAULT '' COMMENT '插件版本号',
  `createtime` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '插件安装时间',
  `listorder` smallint(6) NOT NULL DEFAULT '0' COMMENT '排序',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Records of cmf_plugins
-- ----------------------------
INSERT INTO `cmf_plugins` VALUES ('2', 'Demo', '插件演示', '插件演示', '0', '1', '{\"text\":\"hello,ThinkCMF!\",\"password\":\"\",\"select\":\"1\",\"checkbox\":[\"1\"],\"radio\":\"1\",\"textarea\":\"\\u8fd9\\u91cc\\u662f\\u4f60\\u8981\\u586b\\u5199\\u7684\\u5185\\u5bb9\'\"}', 'footer', '1', 'ThinkCMF', '1.0', '0', '0');

-- ----------------------------
-- Table structure for `cmf_popular_orders`
-- ----------------------------
DROP TABLE IF EXISTS `cmf_popular_orders`;
CREATE TABLE `cmf_popular_orders` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(11) NOT NULL DEFAULT '0' COMMENT '用户ID',
  `videoid` int(11) NOT NULL DEFAULT '0' COMMENT '视频ID',
  `money` decimal(20,2) NOT NULL DEFAULT '0.00' COMMENT '价格',
  `length` int(11) NOT NULL COMMENT '时长（小时）',
  `nums` int(11) NOT NULL DEFAULT '0' COMMENT '播放量',
  `type` tinyint(1) NOT NULL DEFAULT '0' COMMENT '类型,0余额1支付宝2微信3苹果',
  `ambient` tinyint(1) NOT NULL DEFAULT '0' COMMENT '环境',
  `addtime` int(11) NOT NULL DEFAULT '0' COMMENT '时间',
  `status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '状态，0未支付，1已支付',
  `orderno` varchar(255) NOT NULL DEFAULT '' COMMENT '商户订单号',
  `trade_no` varchar(255) NOT NULL DEFAULT '' COMMENT '商户流水号',
  `touid` bigint(20) NOT NULL DEFAULT '0' COMMENT '视频发布者ID',
  `refund_status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '退款状态 0 待退款 1 已退款',
  `end_nums` int(11) NOT NULL DEFAULT '0' COMMENT '订单结束后剩余播放量',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 ROW_FORMAT=COMPACT;

-- ----------------------------
-- Records of cmf_popular_orders
-- ----------------------------

-- ----------------------------
-- Table structure for `cmf_posts`
-- ----------------------------
DROP TABLE IF EXISTS `cmf_posts`;
CREATE TABLE `cmf_posts` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `post_author` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '发表者id',
  `post_keywords` varchar(150) CHARACTER SET utf8mb4 NOT NULL DEFAULT '' COMMENT 'seo keywords',
  `post_source` varchar(150) CHARACTER SET utf8mb4 NOT NULL DEFAULT '' COMMENT '转载文章的来源',
  `post_content` longtext CHARACTER SET utf8mb4 COMMENT 'post内容',
  `post_title` varchar(255) CHARACTER SET utf8mb4 NOT NULL DEFAULT '' COMMENT 'post标题',
  `post_excerpt` varchar(255) CHARACTER SET utf8mb4 NOT NULL DEFAULT '' COMMENT 'post摘要',
  `post_status` int(2) NOT NULL DEFAULT '1' COMMENT 'post状态，1已审核，0未审核,3删除',
  `post_parent` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT 'post的父级post id,表示post层级关系',
  `smeta` text CHARACTER SET utf8mb4 COMMENT 'post的扩展字段，保存相关扩展属性，如缩略图；格式为json',
  `post_hits` int(11) NOT NULL DEFAULT '0' COMMENT 'post点击数，查看数',
  `istop` tinyint(1) NOT NULL DEFAULT '0' COMMENT '置顶 1置顶； 0不置顶',
  `recommended` tinyint(1) NOT NULL DEFAULT '0' COMMENT '推荐 1推荐 0不推荐',
  `termid` bigint(20) NOT NULL DEFAULT '0' COMMENT '分类id',
  `post_relevant` varchar(255) CHARACTER SET utf8mb4 NOT NULL DEFAULT '' COMMENT '相关阅读',
  `path` varchar(255) NOT NULL DEFAULT '' COMMENT '分类路径',
  `topval` int(11) NOT NULL DEFAULT '0' COMMENT '置顶值',
  `orderno` int(11) NOT NULL DEFAULT '0' COMMENT '序号',
  `post_type` tinyint(1) NOT NULL DEFAULT '0' COMMENT '文章类型 0文章 1单页',
  `post_date` int(11) NOT NULL DEFAULT '0',
  `post_modified` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`) USING BTREE,
  KEY `type_status_date` (`post_status`,`id`) USING BTREE,
  KEY `post_parent` (`post_parent`) USING BTREE,
  KEY `post_author` (`post_author`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=161 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='Portal文章表';

-- ----------------------------
-- Records of cmf_posts
-- ----------------------------
INSERT INTO `cmf_posts` VALUES ('143', '1', '', '', '<p>服务和隐私条款</p><p><br/></p>', '服务和隐私条款', '', '1', '0', null, '0', '0', '0', '11', '', '', '0', '2', '0', '1575281412', '0');
INSERT INTO `cmf_posts` VALUES ('26', '1', '', '', '<p>隐私政策</p>', '隐私政策', '', '1', '0', null, '0', '0', '0', '0', '', '', '0', '0', '1', '1575353776', '0');
INSERT INTO `cmf_posts` VALUES ('38', '1', '', '', '<p>服务协议</p>', '服务协议', '', '1', '0', null, '0', '0', '0', '0', '', '', '0', '0', '1', '1579138709', '0');
INSERT INTO `cmf_posts` VALUES ('146', '1', '', '', '<p><span style=\"border: 1px solid rgb(0, 0, 0);\"></span>最新版短视频可查看当前APP版本的</p>', '短视频介绍', '', '1', '0', null, '0', '0', '0', '13', '', '', '0', '3', '0', '1576892226', '0');
INSERT INTO `cmf_posts` VALUES ('37', '1', '', '', '<p>用户充值协议（后台-内容管理-页面管理中设置）</p>', '用户充值协议', '', '1', '0', null, '0', '0', '0', '0', '', '', '0', '0', '1', '1579137709', '0');
INSERT INTO `cmf_posts` VALUES ('148', '1', '', '', '<p>常见问题1</p>', '常见问题1', '', '1', '0', null, '0', '0', '0', '13', '', '', '0', '4', '0', '1579137934', '0');
INSERT INTO `cmf_posts` VALUES ('149', '1', '', '', '<p>常见问题2</p>', '常见问题2', '', '1', '0', null, '0', '0', '0', '13', '', '', '0', '5', '0', '1579137947', '0');
INSERT INTO `cmf_posts` VALUES ('150', '1', '', '', '<p>用户协议部分</p>', '用户协议', '', '1', '0', null, '0', '0', '0', '11', '', '', '0', '1', '0', '1579137963', '0');
INSERT INTO `cmf_posts` VALUES ('151', '1', '', '', '<p style=\"white-space: normal;\">注销账号是不可恢复的操作，你应自行备份账号相关的信息和数据，操作之前，请确认与账号相关的所有服务均已妥善处理。</p><p style=\"white-space: normal;\">注销账号后，你将无法再使用本账号或找回你添加的任何内容信息，包括但不限于：</p><p style=\"white-space: normal;\">（1）你将无法登录、使用本账号，你的朋友将无法通过本账号联系你。</p><p style=\"white-space: normal;\">（2）你账号的个人资料和历史信息（包含昵称、头像、作品、消息记录、粉丝、关注等）都将无法找回。</p><p style=\"white-space: normal;\">（3）与账号相关的所有功能或服务都将无法继续使用。</p><p><br/></p>', '注销账号', '', '1', '0', null, '0', '0', '0', '0', '', '', '0', '0', '1', '1592806927', '0');
INSERT INTO `cmf_posts` VALUES ('152', '1', '', '', '<p><span style=\"color: rgb(44, 62, 80); font-family: -apple-system, system-ui, BlinkMacSystemFont, &quot;Segoe UI&quot;, Roboto, &quot;Helvetica Neue&quot;, Arial, &quot;PingFang SC&quot;, &quot;Hiragino Sans GB&quot;, &quot;Microsoft YaHei&quot;, sans-serif; font-size: 14px; background-color: rgb(255, 255, 255);\">转盘规则</span></p>', '转盘规则', '', '1', '0', null, '0', '0', '0', '0', '', '', '0', '0', '1', '1603967375', '0');

-- ----------------------------
-- Table structure for `cmf_praise_messages`
-- ----------------------------
DROP TABLE IF EXISTS `cmf_praise_messages`;
CREATE TABLE `cmf_praise_messages` (
  `id` int(12) NOT NULL AUTO_INCREMENT COMMENT '自增ID',
  `uid` int(12) DEFAULT '0' COMMENT '用户id',
  `touid` int(12) DEFAULT '0' COMMENT '被赞用户id',
  `obj_id` int(12) DEFAULT '0' COMMENT '被操作对象id',
  `type` tinyint(1) DEFAULT '0' COMMENT '被操作类型 0评论 1视频',
  `addtime` int(12) DEFAULT '0' COMMENT '操作时间',
  `video_thumb` varchar(255) DEFAULT '' COMMENT '视频封面',
  `videoid` int(12) DEFAULT '0' COMMENT '视频id',
  `status` tinyint(1) DEFAULT '1' COMMENT '是否显示 0否 1 是  （根据视频是否下架决定）',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 ROW_FORMAT=COMPACT;

-- ----------------------------
-- Records of cmf_praise_messages
-- ----------------------------

-- ----------------------------
-- Table structure for `cmf_recycle_bin`
-- ----------------------------
DROP TABLE IF EXISTS `cmf_recycle_bin`;
CREATE TABLE `cmf_recycle_bin` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `object_id` int(11) DEFAULT '0' COMMENT '删除内容 id',
  `create_time` int(10) unsigned DEFAULT '0' COMMENT '创建时间',
  `table_name` varchar(60) DEFAULT '' COMMENT '删除内容所在表名',
  `name` varchar(255) DEFAULT '' COMMENT '删除内容名称',
  `user_id` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '用户id',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 ROW_FORMAT=COMPACT COMMENT=' 回收站';

-- ----------------------------
-- Records of cmf_recycle_bin
-- ----------------------------

-- ----------------------------
-- Table structure for `cmf_role`
-- ----------------------------
DROP TABLE IF EXISTS `cmf_role`;
CREATE TABLE `cmf_role` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '角色名称',
  `parent_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '父角色ID',
  `status` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '状态;0:禁用;1:正常',
  `remark` varchar(255) NOT NULL DEFAULT '' COMMENT '备注',
  `create_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  `list_order` float NOT NULL DEFAULT '0' COMMENT '排序',
  PRIMARY KEY (`id`) USING BTREE,
  KEY `parent_id` (`parent_id`) USING BTREE,
  KEY `status` (`status`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 ROW_FORMAT=COMPACT COMMENT='角色表';

-- ----------------------------
-- Records of cmf_role
-- ----------------------------
INSERT INTO `cmf_role` VALUES ('1', '超级管理员', '0', '1', '拥有网站最高管理员权限！', '1329633709', '1329633709', '0');

-- ----------------------------
-- Table structure for `cmf_role_user`
-- ----------------------------
DROP TABLE IF EXISTS `cmf_role_user`;
CREATE TABLE `cmf_role_user` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `role_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '角色 id',
  `user_id` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '用户id',
  PRIMARY KEY (`id`) USING BTREE,
  KEY `role_id` (`role_id`) USING BTREE,
  KEY `user_id` (`user_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT COMMENT='用户角色对应表';

-- ----------------------------
-- Records of cmf_role_user
-- ----------------------------

-- ----------------------------
-- Table structure for `cmf_route`
-- ----------------------------
DROP TABLE IF EXISTS `cmf_route`;
CREATE TABLE `cmf_route` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '路由id',
  `list_order` float NOT NULL DEFAULT '10000' COMMENT '排序',
  `status` tinyint(2) NOT NULL DEFAULT '1' COMMENT '状态;1:启用,0:不启用',
  `type` tinyint(4) NOT NULL DEFAULT '1' COMMENT 'URL规则类型;1:用户自定义;2:别名添加',
  `full_url` varchar(255) NOT NULL DEFAULT '' COMMENT '完整url',
  `url` varchar(255) NOT NULL DEFAULT '' COMMENT '实际显示的url',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 ROW_FORMAT=COMPACT COMMENT='url路由表';

-- ----------------------------
-- Records of cmf_route
-- ----------------------------

-- ----------------------------
-- Table structure for `cmf_seller_goods_class`
-- ----------------------------
DROP TABLE IF EXISTS `cmf_seller_goods_class`;
CREATE TABLE `cmf_seller_goods_class` (
  `uid` bigint(11) NOT NULL DEFAULT '0' COMMENT '用户id',
  `goods_classid` int(11) NOT NULL DEFAULT '0' COMMENT '商品一级分类id',
  `status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否显示 0 否 1 是'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Records of cmf_seller_goods_class
-- ----------------------------

-- ----------------------------
-- Table structure for `cmf_seller_platform_goods`
-- ----------------------------
DROP TABLE IF EXISTS `cmf_seller_platform_goods`;
CREATE TABLE `cmf_seller_platform_goods` (
  `uid` bigint(20) NOT NULL DEFAULT '0' COMMENT '用户id',
  `goodsid` bigint(20) NOT NULL DEFAULT '0' COMMENT '平台自营商品ID',
  `addtime` int(11) NOT NULL DEFAULT '0' COMMENT '添加时间',
  `status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '商品售卖状态 0 下架 1 上架',
  `issale` tinyint(1) NOT NULL DEFAULT '0' COMMENT '商品是否直播间在售 0 否 1 是',
  `live_isshow` tinyint(1) NOT NULL DEFAULT '0' COMMENT '直播间是否展示商品简介 0 否 1 是 默认0',
  KEY `uid_goodsid` (`uid`,`goodsid`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Records of cmf_seller_platform_goods
-- ----------------------------

-- ----------------------------
-- Table structure for `cmf_shop_address`
-- ----------------------------
DROP TABLE IF EXISTS `cmf_shop_address`;
CREATE TABLE `cmf_shop_address` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT COMMENT '自增id',
  `uid` int(11) NOT NULL DEFAULT '0' COMMENT '用户id',
  `name` varchar(255) NOT NULL DEFAULT '' COMMENT '姓名',
  `country` varchar(255) NOT NULL DEFAULT '' COMMENT '国家',
  `province` varchar(255) NOT NULL DEFAULT '' COMMENT '省份',
  `city` varchar(255) NOT NULL DEFAULT '' COMMENT '市',
  `area` varchar(255) NOT NULL DEFAULT '' COMMENT '区',
  `address` varchar(255) NOT NULL DEFAULT '' COMMENT '详细地址',
  `phone` varchar(255) NOT NULL DEFAULT '' COMMENT '电话',
  `country_code` int(11) NOT NULL DEFAULT '86' COMMENT '国家代号',
  `is_default` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否为默认地址 0 否 1 是',
  `addtime` int(11) NOT NULL DEFAULT '0' COMMENT '添加时间',
  `edittime` int(11) NOT NULL DEFAULT '0' COMMENT '修改时间',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Records of cmf_shop_address
-- ----------------------------

-- ----------------------------
-- Table structure for `cmf_shop_apply`
-- ----------------------------
DROP TABLE IF EXISTS `cmf_shop_apply`;
CREATE TABLE `cmf_shop_apply` (
  `uid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '用户ID',
  `name` varchar(255) NOT NULL DEFAULT '' COMMENT '名称',
  `thumb` varchar(255) NOT NULL DEFAULT '' COMMENT '封面',
  `des` varchar(255) NOT NULL DEFAULT '' COMMENT '简介',
  `username` varchar(255) NOT NULL DEFAULT '' COMMENT '联系人姓名',
  `cardno` varchar(255) NOT NULL DEFAULT '' COMMENT '身份证号码',
  `contact` varchar(255) NOT NULL DEFAULT '' COMMENT '联系人',
  `country_code` int(11) NOT NULL DEFAULT '86' COMMENT '国家代号',
  `phone` varchar(255) NOT NULL DEFAULT '' COMMENT '电话',
  `province` varchar(255) NOT NULL DEFAULT '' COMMENT '省份',
  `city` varchar(255) NOT NULL DEFAULT '' COMMENT '市',
  `area` varchar(255) NOT NULL DEFAULT '' COMMENT '地区',
  `address` varchar(255) NOT NULL DEFAULT '' COMMENT '详细地址',
  `service_phone` varchar(255) NOT NULL DEFAULT '' COMMENT '客服电话',
  `receiver` varchar(255) NOT NULL DEFAULT '' COMMENT '退货收货人',
  `receiver_phone` varchar(255) NOT NULL DEFAULT '' COMMENT '退货人联系电话',
  `receiver_province` varchar(255) NOT NULL DEFAULT '' COMMENT '退货人省份',
  `receiver_city` varchar(255) NOT NULL DEFAULT '' COMMENT '退货人市',
  `receiver_area` varchar(255) NOT NULL COMMENT '退货人地区',
  `receiver_address` varchar(255) NOT NULL COMMENT '退货人详细地址',
  `license` varchar(255) NOT NULL DEFAULT '' COMMENT '许可证',
  `certificate` varchar(255) NOT NULL DEFAULT '' COMMENT '营业执照',
  `other` varchar(255) NOT NULL COMMENT '其他证件',
  `addtime` int(11) NOT NULL DEFAULT '0' COMMENT '申请时间',
  `uptime` int(11) NOT NULL DEFAULT '0' COMMENT '更新时间',
  `status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '状态，0审核中1通过2拒绝',
  `reason` varchar(255) NOT NULL DEFAULT '' COMMENT '原因',
  `order_percent` int(11) NOT NULL DEFAULT '0' COMMENT '订单分成比例',
  `sale_nums` bigint(11) NOT NULL DEFAULT '0' COMMENT '店铺总销量',
  `quality_points` float(11,1) NOT NULL DEFAULT '0.0' COMMENT '店铺商品质量(商品描述)平均分',
  `service_points` float(11,1) NOT NULL DEFAULT '0.0' COMMENT '店铺服务态度平均分',
  `express_points` float(11,1) NOT NULL DEFAULT '0.0' COMMENT '物流服务平均分',
  `shipment_overdue_num` int(11) NOT NULL DEFAULT '0' COMMENT '店铺逾期发货次数',
  PRIMARY KEY (`uid`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Records of cmf_shop_apply
-- ----------------------------

-- ----------------------------
-- Table structure for `cmf_shop_bond`
-- ----------------------------
DROP TABLE IF EXISTS `cmf_shop_bond`;
CREATE TABLE `cmf_shop_bond` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `uid` bigint(20) NOT NULL DEFAULT '0' COMMENT '用户ID',
  `bond` int(11) NOT NULL DEFAULT '0' COMMENT '保证金',
  `status` tinyint(4) NOT NULL DEFAULT '0' COMMENT '状态，0已退回1已支付,-1已扣除',
  `addtime` bigint(20) NOT NULL DEFAULT '0' COMMENT '支付时间',
  `uptime` bigint(20) NOT NULL DEFAULT '0' COMMENT '更新时间',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Records of cmf_shop_bond
-- ----------------------------

-- ----------------------------
-- Table structure for `cmf_shop_express`
-- ----------------------------
DROP TABLE IF EXISTS `cmf_shop_express`;
CREATE TABLE `cmf_shop_express` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '自增ID',
  `express_name` varchar(255) NOT NULL DEFAULT '' COMMENT '快递公司电话',
  `express_phone` varchar(255) NOT NULL DEFAULT '' COMMENT '快递公司客服电话',
  `express_thumb` varchar(255) NOT NULL DEFAULT '' COMMENT '快递公司图标',
  `express_status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '是否显示 0 否 1 是',
  `express_code` varchar(255) NOT NULL DEFAULT '' COMMENT '快递公司对应三方平台的编码',
  `addtime` int(11) NOT NULL DEFAULT '0' COMMENT '添加时间',
  `edittime` int(11) NOT NULL DEFAULT '0' COMMENT '编辑时间',
  `list_order` int(11) NOT NULL DEFAULT '0' COMMENT '排序号',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Records of cmf_shop_express
-- ----------------------------
INSERT INTO `cmf_shop_express` VALUES ('1', '顺丰速运', '95338', 'express_1.png%@%cloudtype=1', '1', 'SF', '1583898216', '1609664210', '1');
INSERT INTO `cmf_shop_express` VALUES ('2', '韵达速递', '95546', 'express_2.png%@%cloudtype=1', '1', 'YD', '1583905367', '1616984583', '2');
INSERT INTO `cmf_shop_express` VALUES ('3', '中通快递', '95311', 'express_3.png%@%cloudtype=1', '1', 'ZTO', '1583905579', '0', '3');
INSERT INTO `cmf_shop_express` VALUES ('4', '圆通速递', '95554', 'express_4.png%@%cloudtype=1', '1', 'YTO', '1583905611', '1586230191', '4');
INSERT INTO `cmf_shop_express` VALUES ('5', '申通快递', '95543', 'express_5.png%@%cloudtype=1', '1', 'STO', '1583905650', '0', '5');
INSERT INTO `cmf_shop_express` VALUES ('6', '中国邮政', '11183', 'express_6.png%@%cloudtype=1', '1', 'YZPY', '1583905722', '0', '6');
INSERT INTO `cmf_shop_express` VALUES ('7', '百世快递', '95320', 'express_7.png%@%cloudtype=1', '1', 'HTKY', '1583905749', '0', '7');
INSERT INTO `cmf_shop_express` VALUES ('8', '宅急送', '400-6789-000', 'express_8.png%@%cloudtype=1', '1', 'ZJS', '1583905771', '0', '8');

-- ----------------------------
-- Table structure for `cmf_shop_goods`
-- ----------------------------
DROP TABLE IF EXISTS `cmf_shop_goods`;
CREATE TABLE `cmf_shop_goods` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `uid` bigint(20) NOT NULL DEFAULT '0' COMMENT '用户ID',
  `name` varchar(255) NOT NULL DEFAULT '' COMMENT '名称',
  `one_classid` int(11) NOT NULL DEFAULT '0' COMMENT '商品一级分类',
  `two_classid` int(11) NOT NULL DEFAULT '0' COMMENT '商品二级分类',
  `three_classid` int(11) NOT NULL DEFAULT '0' COMMENT '商品三级分类',
  `video_url` varchar(255) NOT NULL DEFAULT '' COMMENT '商品视频地址',
  `video_thumb` varchar(255) NOT NULL DEFAULT '' COMMENT '商品视频封面',
  `thumbs` text NOT NULL COMMENT '封面',
  `content` longtext NOT NULL COMMENT '商品文字内容',
  `pictures` text NOT NULL COMMENT '商品内容图集',
  `specs` longtext NOT NULL COMMENT '商品规格',
  `postage` int(11) NOT NULL DEFAULT '0' COMMENT '邮费',
  `addtime` bigint(20) NOT NULL DEFAULT '0' COMMENT '时间',
  `uptime` bigint(20) NOT NULL DEFAULT '0' COMMENT '更新时间',
  `hits` int(11) NOT NULL DEFAULT '0' COMMENT '点击数',
  `status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '状态，0审核中-1商家下架1通过-2管理员下架 2拒绝',
  `isrecom` tinyint(1) NOT NULL DEFAULT '0' COMMENT '推荐，0否1是',
  `sale_nums` int(11) NOT NULL DEFAULT '0' COMMENT '总销量',
  `refuse_reason` varchar(255) NOT NULL DEFAULT '' COMMENT '商品拒绝原因',
  `issale` tinyint(1) NOT NULL DEFAULT '0' COMMENT '商品是否在直播间销售 0 否 1 是(针对用户自己发布的商品)',
  `type` tinyint(1) NOT NULL DEFAULT '0' COMMENT '商品类型 0 站内商品 1 站外商品 2平台自营',
  `original_price` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '站外商品原价',
  `present_price` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '站外商品现价',
  `goods_desc` varchar(255) NOT NULL DEFAULT '' COMMENT '站外商品简介',
  `href` varchar(500) NOT NULL DEFAULT '' COMMENT '站外商品链接',
  `live_isshow` tinyint(1) NOT NULL DEFAULT '0' COMMENT '直播间是否展示商品简介 0 否 1 是 默认0',
  `low_price` decimal(20,2) NOT NULL DEFAULT '0.00' COMMENT '站外商品最低价',
  `admin_id` bigint(20) NOT NULL DEFAULT '0' COMMENT '发布自营商品的管理员id',
  `commission` decimal(20,2) NOT NULL DEFAULT '0.00' COMMENT '主播代卖平台商品的佣金',
  `share_income` decimal(20,2) NOT NULL DEFAULT '0.00' COMMENT '分享给其他用户购买后获得的佣金',
  PRIMARY KEY (`id`) USING BTREE,
  KEY `uid_status` (`uid`,`status`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Records of cmf_shop_goods
-- ----------------------------

-- ----------------------------
-- Table structure for `cmf_shop_goods_class`
-- ----------------------------
DROP TABLE IF EXISTS `cmf_shop_goods_class`;
CREATE TABLE `cmf_shop_goods_class` (
  `gc_id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '商品分类ID',
  `gc_name` varchar(255) NOT NULL DEFAULT '' COMMENT '商品分类名称',
  `gc_parentid` int(11) NOT NULL DEFAULT '0' COMMENT '上级分类ID',
  `gc_one_id` int(11) NOT NULL COMMENT '所属一级分类ID',
  `gc_sort` int(11) NOT NULL DEFAULT '0' COMMENT '商品分类排序号',
  `gc_isshow` tinyint(1) NOT NULL COMMENT '是否展示 0 否 1 是',
  `gc_addtime` int(11) NOT NULL DEFAULT '0' COMMENT '商品分类添加时间',
  `gc_edittime` int(11) NOT NULL DEFAULT '0' COMMENT '商品分类修改时间',
  `gc_grade` tinyint(1) NOT NULL DEFAULT '0' COMMENT '商品分类等级',
  `gc_icon` varchar(255) NOT NULL COMMENT '商品分类图标',
  PRIMARY KEY (`gc_id`) USING BTREE,
  KEY `list1` (`gc_parentid`,`gc_isshow`) USING BTREE,
  KEY `gc_parentid` (`gc_parentid`) USING BTREE,
  KEY `gc_grade` (`gc_grade`) USING BTREE,
  KEY `list2` (`gc_one_id`,`gc_grade`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=218 DEFAULT CHARSET=utf8mb4 ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Records of cmf_shop_goods_class
-- ----------------------------
INSERT INTO `cmf_shop_goods_class` VALUES ('1', '手机/数码/电脑办公', '0', '0', '1', '1', '1581417338', '1616206398', '1', '');
INSERT INTO `cmf_shop_goods_class` VALUES ('2', '手机', '1', '1', '12234', '1', '1581418030', '0', '2', 'shop_two_class_1.png%@%cloudtype=1');
INSERT INTO `cmf_shop_goods_class` VALUES ('3', '华为', '2', '1', '1', '1', '1581419247', '0', '3', '');
INSERT INTO `cmf_shop_goods_class` VALUES ('4', '苹果', '2', '1', '2423', '1', '1581419261', '0', '3', '');
INSERT INTO `cmf_shop_goods_class` VALUES ('5', '小米', '2', '1', '3', '1', '1581419272', '0', '3', '');
INSERT INTO `cmf_shop_goods_class` VALUES ('6', 'OPPO', '2', '1', '4', '1', '1581419284', '0', '3', '');
INSERT INTO `cmf_shop_goods_class` VALUES ('7', 'vivo', '2', '1', '5', '1', '1581419312', '0', '3', '');
INSERT INTO `cmf_shop_goods_class` VALUES ('8', '数码', '1', '1', '223423', '1', '1581420086', '1581581595', '2', 'shop_two_class_2.png%@%cloudtype=1');
INSERT INTO `cmf_shop_goods_class` VALUES ('9', '佳能', '8', '1', '1234', '1', '1581420123', '1581581595', '3', '');
INSERT INTO `cmf_shop_goods_class` VALUES ('11', '索尼', '8', '1', '3234', '1', '1581420243', '1581581595', '3', '');
INSERT INTO `cmf_shop_goods_class` VALUES ('12', '三星', '2', '1', '6', '1', '1581559545', '1581580638', '3', '');
INSERT INTO `cmf_shop_goods_class` VALUES ('13', '电脑办公', '1', '1', '1', '1', '1581559571', '1616983499', '2', 'shop_two_class_3.png%@%cloudtype=1');
INSERT INTO `cmf_shop_goods_class` VALUES ('14', '华硕', '13', '1', '1', '1', '1581559693', '1616983499', '3', '');
INSERT INTO `cmf_shop_goods_class` VALUES ('15', '戴尔', '13', '1', '1', '1', '1581559874', '1616983499', '3', '');
INSERT INTO `cmf_shop_goods_class` VALUES ('16', '惠普', '13', '1', '1', '1', '1581559886', '1616983499', '3', '');
INSERT INTO `cmf_shop_goods_class` VALUES ('17', '宏碁', '13', '1', '1', '1', '1581559897', '1616983499', '3', '');
INSERT INTO `cmf_shop_goods_class` VALUES ('18', '联想', '13', '1', '1', '1', '1581559911', '1616983499', '3', '');
INSERT INTO `cmf_shop_goods_class` VALUES ('19', '家具/家饰/家纺', '0', '0', '2', '1', '1582271415', '0', '1', '');
INSERT INTO `cmf_shop_goods_class` VALUES ('20', '家具', '19', '19', '1', '1', '1582271459', '0', '2', 'shop_two_class_4.png%@%cloudtype=1');
INSERT INTO `cmf_shop_goods_class` VALUES ('21', '布艺软饰', '19', '19', '2', '1', '1582271471', '0', '2', 'shop_two_class_5.png%@%cloudtype=1');
INSERT INTO `cmf_shop_goods_class` VALUES ('22', '床上用品', '19', '19', '3', '1', '1582271491', '0', '2', 'shop_two_class_6.png%@%cloudtype=1');
INSERT INTO `cmf_shop_goods_class` VALUES ('23', '沙发', '20', '19', '1', '1', '1582271560', '0', '3', '');
INSERT INTO `cmf_shop_goods_class` VALUES ('24', '床', '20', '19', '2', '1', '1582271574', '0', '3', '');
INSERT INTO `cmf_shop_goods_class` VALUES ('25', '电视柜', '20', '19', '3', '1', '1582271588', '0', '3', '');
INSERT INTO `cmf_shop_goods_class` VALUES ('26', '鞋柜', '20', '19', '4', '1', '1582271607', '0', '3', '');
INSERT INTO `cmf_shop_goods_class` VALUES ('27', '窗帘', '21', '19', '1', '1', '1582272244', '0', '3', '');
INSERT INTO `cmf_shop_goods_class` VALUES ('28', '地毯', '21', '19', '2', '1', '1582272254', '0', '3', '');
INSERT INTO `cmf_shop_goods_class` VALUES ('29', '桌布', '21', '19', '3', '1', '1582272265', '0', '3', '');
INSERT INTO `cmf_shop_goods_class` VALUES ('30', '沙发垫', '21', '19', '4', '1', '1582272281', '0', '3', '');
INSERT INTO `cmf_shop_goods_class` VALUES ('31', '四件套', '22', '19', '1', '1', '1582272322', '0', '3', '');
INSERT INTO `cmf_shop_goods_class` VALUES ('32', '空调被', '22', '19', '2', '1', '1582272331', '0', '3', '');
INSERT INTO `cmf_shop_goods_class` VALUES ('33', '夏凉被', '22', '19', '3', '1', '1582272341', '0', '3', '');
INSERT INTO `cmf_shop_goods_class` VALUES ('34', '枕头', '22', '19', '4', '1', '1582272378', '0', '3', '');
INSERT INTO `cmf_shop_goods_class` VALUES ('35', '竹席', '22', '19', '5', '1', '1582272404', '0', '3', '');
INSERT INTO `cmf_shop_goods_class` VALUES ('36', '美食/生鲜/零食', '0', '0', '3', '1', '1582272626', '0', '1', '');
INSERT INTO `cmf_shop_goods_class` VALUES ('37', '美食', '36', '36', '1', '1', '1582272696', '0', '2', 'shop_two_class_7.png%@%cloudtype=1');
INSERT INTO `cmf_shop_goods_class` VALUES ('38', '生鲜', '36', '36', '2', '1', '1582272705', '0', '2', 'shop_two_class_8.png%@%cloudtype=1');
INSERT INTO `cmf_shop_goods_class` VALUES ('39', '零食', '36', '36', '3', '1', '1582272715', '0', '2', 'shop_two_class_9.png%@%cloudtype=1');
INSERT INTO `cmf_shop_goods_class` VALUES ('40', '牛奶', '37', '36', '1', '1', '1582272837', '0', '3', '');
INSERT INTO `cmf_shop_goods_class` VALUES ('41', '红茶', '37', '36', '2', '1', '1582272847', '0', '3', '');
INSERT INTO `cmf_shop_goods_class` VALUES ('42', '绿茶', '37', '36', '2', '1', '1582272857', '0', '3', '');
INSERT INTO `cmf_shop_goods_class` VALUES ('43', '黑茶', '37', '36', '3', '1', '1582272868', '0', '3', '');
INSERT INTO `cmf_shop_goods_class` VALUES ('44', '荔枝', '38', '36', '1', '1', '1582272950', '0', '3', '');
INSERT INTO `cmf_shop_goods_class` VALUES ('45', '芒果', '38', '36', '2', '1', '1582272959', '0', '3', '');
INSERT INTO `cmf_shop_goods_class` VALUES ('46', '樱桃', '38', '36', '3', '1', '1582272968', '0', '3', '');
INSERT INTO `cmf_shop_goods_class` VALUES ('47', '小龙虾', '38', '36', '4', '1', '1582272994', '0', '3', '');
INSERT INTO `cmf_shop_goods_class` VALUES ('48', '三文鱼', '38', '36', '5', '1', '1582273003', '0', '3', '');
INSERT INTO `cmf_shop_goods_class` VALUES ('49', '零食大礼包', '39', '36', '1', '1', '1582273055', '0', '3', '');
INSERT INTO `cmf_shop_goods_class` VALUES ('50', '面包', '39', '36', '2', '1', '1582273064', '0', '3', '');
INSERT INTO `cmf_shop_goods_class` VALUES ('51', '巧克力', '39', '36', '3', '1', '1582273093', '0', '3', '');
INSERT INTO `cmf_shop_goods_class` VALUES ('52', '鱼干', '39', '36', '4', '1', '1582273115', '0', '3', '');
INSERT INTO `cmf_shop_goods_class` VALUES ('53', '女鞋/箱包/钟表/珠宝', '0', '0', '4', '1', '1582772109', '0', '1', '');
INSERT INTO `cmf_shop_goods_class` VALUES ('54', '精品女鞋', '53', '53', '1', '1', '1582772122', '1582772266', '2', 'shop_two_class_10.png%@%cloudtype=1');
INSERT INTO `cmf_shop_goods_class` VALUES ('55', '潮流女包', '53', '53', '2', '1', '1582772155', '0', '2', 'shop_two_class_11.png%@%cloudtype=1');
INSERT INTO `cmf_shop_goods_class` VALUES ('56', '精品男包', '53', '53', '3', '1', '1582772177', '0', '2', 'shop_two_class_12.png%@%cloudtype=1');
INSERT INTO `cmf_shop_goods_class` VALUES ('57', '功能箱包', '53', '53', '4', '1', '1582772204', '0', '2', 'shop_two_class_13.png%@%cloudtype=1');
INSERT INTO `cmf_shop_goods_class` VALUES ('58', '钟表', '53', '53', '5', '1', '1582772232', '0', '2', 'shop_two_class_14.png%@%cloudtype=1');
INSERT INTO `cmf_shop_goods_class` VALUES ('59', '珠宝首饰', '53', '53', '6', '1', '1582772248', '0', '2', 'shop_two_class_15.png%@%cloudtype=1');
INSERT INTO `cmf_shop_goods_class` VALUES ('60', '马丁靴', '54', '53', '1', '1', '1582772311', '0', '3', '');
INSERT INTO `cmf_shop_goods_class` VALUES ('61', '高跟鞋', '54', '53', '2', '1', '1582772323', '0', '3', '');
INSERT INTO `cmf_shop_goods_class` VALUES ('62', '帆布鞋', '54', '53', '3', '1', '1582772346', '0', '3', '');
INSERT INTO `cmf_shop_goods_class` VALUES ('63', '松糕鞋', '54', '53', '4', '1', '1582772416', '0', '3', '');
INSERT INTO `cmf_shop_goods_class` VALUES ('64', '真皮包', '55', '53', '1', '1', '1582772438', '0', '3', '');
INSERT INTO `cmf_shop_goods_class` VALUES ('65', '单肩包', '55', '53', '2', '1', '1582772449', '0', '3', '');
INSERT INTO `cmf_shop_goods_class` VALUES ('66', '斜挎包', '55', '53', '3', '1', '1582772460', '0', '3', '');
INSERT INTO `cmf_shop_goods_class` VALUES ('67', '钱包', '55', '53', '4', '1', '1582772479', '0', '3', '');
INSERT INTO `cmf_shop_goods_class` VALUES ('68', '手拿包', '55', '53', '5', '1', '1582772488', '0', '3', '');
INSERT INTO `cmf_shop_goods_class` VALUES ('69', '钥匙包', '55', '53', '6', '1', '1582772505', '0', '3', '');
INSERT INTO `cmf_shop_goods_class` VALUES ('70', '男士钱包', '56', '53', '1', '1', '1582772539', '0', '3', '');
INSERT INTO `cmf_shop_goods_class` VALUES ('71', '双肩包', '56', '53', '2', '1', '1582772568', '0', '3', '');
INSERT INTO `cmf_shop_goods_class` VALUES ('72', '单肩/斜挎包', '56', '53', '3', '1', '1582772590', '0', '3', '');
INSERT INTO `cmf_shop_goods_class` VALUES ('73', '商务公文包', '56', '53', '4', '1', '1582772614', '0', '3', '');
INSERT INTO `cmf_shop_goods_class` VALUES ('74', '拉杆箱', '57', '53', '1', '1', '1582772654', '0', '3', '');
INSERT INTO `cmf_shop_goods_class` VALUES ('75', '旅行包', '57', '53', '2', '1', '1582772664', '0', '3', '');
INSERT INTO `cmf_shop_goods_class` VALUES ('76', '电脑包', '57', '53', '3', '1', '1582772674', '0', '3', '');
INSERT INTO `cmf_shop_goods_class` VALUES ('77', '登山包', '57', '53', '4', '1', '1582772699', '0', '3', '');
INSERT INTO `cmf_shop_goods_class` VALUES ('78', '休闲运动包', '57', '53', '5', '1', '1582772722', '0', '3', '');
INSERT INTO `cmf_shop_goods_class` VALUES ('79', '天梭', '58', '53', '1', '1', '1582772745', '0', '3', '');
INSERT INTO `cmf_shop_goods_class` VALUES ('80', '浪琴', '58', '53', '2', '1', '1582772760', '0', '3', '');
INSERT INTO `cmf_shop_goods_class` VALUES ('81', '欧米茄', '58', '53', '3', '1', '1582772770', '0', '3', '');
INSERT INTO `cmf_shop_goods_class` VALUES ('82', '卡西欧', '58', '53', '4', '1', '1582772790', '0', '3', '');
INSERT INTO `cmf_shop_goods_class` VALUES ('83', '天王', '58', '53', '5', '1', '1582772810', '0', '3', '');
INSERT INTO `cmf_shop_goods_class` VALUES ('84', '闹钟', '58', '53', '6', '1', '1582772828', '0', '3', '');
INSERT INTO `cmf_shop_goods_class` VALUES ('85', '挂钟', '58', '53', '7', '1', '1582772838', '0', '3', '');
INSERT INTO `cmf_shop_goods_class` VALUES ('86', '座钟', '58', '53', '8', '1', '1582772852', '0', '3', '');
INSERT INTO `cmf_shop_goods_class` VALUES ('87', '钟表配件', '58', '53', '9', '1', '1582772870', '0', '3', '');
INSERT INTO `cmf_shop_goods_class` VALUES ('88', '黄金', '59', '53', '1', '1', '1582772908', '0', '3', '');
INSERT INTO `cmf_shop_goods_class` VALUES ('89', '钻石', '59', '53', '2', '1', '1582772917', '0', '3', '');
INSERT INTO `cmf_shop_goods_class` VALUES ('90', '翡翠玉石', '59', '53', '3', '1', '1582772928', '0', '3', '');
INSERT INTO `cmf_shop_goods_class` VALUES ('91', '水晶玛瑙', '59', '53', '4', '1', '1582772950', '0', '3', '');
INSERT INTO `cmf_shop_goods_class` VALUES ('92', '手串/把件', '59', '53', '5', '1', '1582772978', '0', '3', '');
INSERT INTO `cmf_shop_goods_class` VALUES ('93', '银饰', '59', '53', '6', '1', '1582773002', '0', '3', '');
INSERT INTO `cmf_shop_goods_class` VALUES ('94', '珍珠', '59', '53', '7', '1', '1582773012', '0', '3', '');
INSERT INTO `cmf_shop_goods_class` VALUES ('95', '汽车/汽车用品', '0', '0', '5', '1', '1582773070', '0', '1', '');
INSERT INTO `cmf_shop_goods_class` VALUES ('96', '汽车装饰', '95', '95', '1', '1', '1582773104', '0', '2', 'shop_two_class_16.png%@%cloudtype=1');
INSERT INTO `cmf_shop_goods_class` VALUES ('97', '车载电器', '95', '95', '2', '1', '1582773118', '0', '2', 'shop_two_class_17.png%@%cloudtype=1');
INSERT INTO `cmf_shop_goods_class` VALUES ('98', '汽车美容', '95', '95', '3', '1', '1582773147', '0', '2', 'shop_two_class_18.png%@%cloudtype=1');
INSERT INTO `cmf_shop_goods_class` VALUES ('99', '随车用品', '95', '95', '4', '1', '1582773181', '0', '2', 'shop_two_class_19.png%@%cloudtype=1');
INSERT INTO `cmf_shop_goods_class` VALUES ('100', '坐垫套装', '96', '95', '1', '1', '1582773212', '0', '3', '');
INSERT INTO `cmf_shop_goods_class` VALUES ('101', '脚垫', '96', '95', '2', '1', '1582773223', '0', '3', '');
INSERT INTO `cmf_shop_goods_class` VALUES ('102', '方向盘套', '96', '95', '3', '1', '1582773246', '0', '3', '');
INSERT INTO `cmf_shop_goods_class` VALUES ('103', '装饰灯', '96', '95', '4', '1', '1582773279', '0', '3', '');
INSERT INTO `cmf_shop_goods_class` VALUES ('104', '车衣', '96', '95', '5', '1', '1582773301', '0', '3', '');
INSERT INTO `cmf_shop_goods_class` VALUES ('105', '雨刮器', '96', '95', '6', '1', '1582773313', '0', '3', '');
INSERT INTO `cmf_shop_goods_class` VALUES ('106', '雨眉', '96', '95', '7', '1', '1582773323', '0', '3', '');
INSERT INTO `cmf_shop_goods_class` VALUES ('107', '行车记录仪', '97', '95', '1', '1', '1582773354', '0', '3', '');
INSERT INTO `cmf_shop_goods_class` VALUES ('108', '车载充电器', '97', '95', '2', '1', '1582773367', '0', '3', '');
INSERT INTO `cmf_shop_goods_class` VALUES ('109', '倒车雷达', '97', '95', '3', '1', '1582773398', '0', '3', '');
INSERT INTO `cmf_shop_goods_class` VALUES ('110', '车载吸尘器', '97', '95', '4', '1', '1582773429', '0', '3', '');
INSERT INTO `cmf_shop_goods_class` VALUES ('111', '应急电源', '97', '95', '5', '1', '1582773454', '0', '3', '');
INSERT INTO `cmf_shop_goods_class` VALUES ('112', '车载电器配件', '97', '95', '6', '1', '1582773472', '0', '3', '');
INSERT INTO `cmf_shop_goods_class` VALUES ('113', '洗车机', '98', '95', '1', '1', '1582773497', '0', '3', '');
INSERT INTO `cmf_shop_goods_class` VALUES ('114', '洗车水枪', '98', '95', '2', '1', '1582773508', '0', '3', '');
INSERT INTO `cmf_shop_goods_class` VALUES ('115', '玻璃水', '98', '95', '3', '1', '1582773519', '0', '3', '');
INSERT INTO `cmf_shop_goods_class` VALUES ('116', '车蜡', '98', '95', '4', '1', '1582773539', '0', '3', '');
INSERT INTO `cmf_shop_goods_class` VALUES ('117', '汽车贴膜', '98', '95', '5', '1', '1582773549', '0', '3', '');
INSERT INTO `cmf_shop_goods_class` VALUES ('118', '底盘装甲', '98', '95', '5', '1', '1582773569', '0', '3', '');
INSERT INTO `cmf_shop_goods_class` VALUES ('119', '补漆笔', '98', '95', '6', '1', '1582773587', '0', '3', '');
INSERT INTO `cmf_shop_goods_class` VALUES ('120', '汽车美容配件', '98', '95', '7', '1', '1582773611', '0', '3', '');
INSERT INTO `cmf_shop_goods_class` VALUES ('121', '灭火器', '99', '95', '1', '1', '1582773638', '0', '3', '');
INSERT INTO `cmf_shop_goods_class` VALUES ('122', '保温杯', '99', '95', '2', '1', '1582773647', '0', '3', '');
INSERT INTO `cmf_shop_goods_class` VALUES ('123', '充气泵', '99', '95', '3', '1', '1582773673', '0', '3', '');
INSERT INTO `cmf_shop_goods_class` VALUES ('124', '车载床', '99', '95', '4', '1', '1582773682', '0', '3', '');
INSERT INTO `cmf_shop_goods_class` VALUES ('125', '储物箱', '99', '95', '5', '1', '1582773706', '0', '3', '');
INSERT INTO `cmf_shop_goods_class` VALUES ('126', '母婴/玩具', '0', '0', '6', '1', '1582773775', '0', '1', '');
INSERT INTO `cmf_shop_goods_class` VALUES ('127', '奶粉', '126', '126', '1', '1', '1582773803', '0', '2', 'shop_two_class_20.png%@%cloudtype=1');
INSERT INTO `cmf_shop_goods_class` VALUES ('128', '营养辅食', '126', '126', '2', '1', '1582773816', '0', '2', 'shop_two_class_21.png%@%cloudtype=1');
INSERT INTO `cmf_shop_goods_class` VALUES ('129', '尿不湿', '126', '126', '3', '1', '1582773833', '0', '2', 'shop_two_class_22.png%@%cloudtype=1');
INSERT INTO `cmf_shop_goods_class` VALUES ('130', '喂养用品', '126', '126', '4', '1', '1582773846', '0', '2', 'shop_two_class_23.png%@%cloudtype=1');
INSERT INTO `cmf_shop_goods_class` VALUES ('131', '母婴洗护用品', '126', '126', '5', '1', '1582773874', '0', '2', 'shop_two_class_24.png%@%cloudtype=1');
INSERT INTO `cmf_shop_goods_class` VALUES ('132', '寝居服饰', '126', '126', '5', '1', '1582773907', '0', '2', 'shop_two_class_25.png%@%cloudtype=1');
INSERT INTO `cmf_shop_goods_class` VALUES ('133', '妈妈专区', '126', '126', '6', '1', '1582773924', '0', '2', 'shop_two_class_26.png%@%cloudtype=1');
INSERT INTO `cmf_shop_goods_class` VALUES ('134', '童车童床', '126', '126', '7', '1', '1582773943', '0', '2', 'shop_two_class_27.png%@%cloudtype=1');
INSERT INTO `cmf_shop_goods_class` VALUES ('135', '玩具', '126', '126', '8', '1', '1582773954', '0', '2', 'shop_two_class_28.png%@%cloudtype=1');
INSERT INTO `cmf_shop_goods_class` VALUES ('136', '1段奶粉', '127', '126', '1', '1', '1582773979', '0', '3', '');
INSERT INTO `cmf_shop_goods_class` VALUES ('137', '2段奶粉', '127', '126', '2', '1', '1582773991', '0', '3', '');
INSERT INTO `cmf_shop_goods_class` VALUES ('138', '3段奶粉', '127', '126', '3', '1', '1582774002', '0', '3', '');
INSERT INTO `cmf_shop_goods_class` VALUES ('139', '4段奶粉', '127', '126', '4', '1', '1582774017', '0', '3', '');
INSERT INTO `cmf_shop_goods_class` VALUES ('140', '特殊配方奶粉', '127', '126', '5', '1', '1582774052', '0', '3', '');
INSERT INTO `cmf_shop_goods_class` VALUES ('141', '米粉/菜粉', '128', '126', '1', '1', '1582774085', '0', '3', '');
INSERT INTO `cmf_shop_goods_class` VALUES ('142', '面条/粥', '128', '126', '2', '1', '1582774099', '0', '3', '');
INSERT INTO `cmf_shop_goods_class` VALUES ('143', '果泥/果汁', '128', '126', '3', '1', '1582774138', '0', '3', '');
INSERT INTO `cmf_shop_goods_class` VALUES ('144', '宝宝零食', '128', '126', '4', '1', '1582774157', '0', '3', '');
INSERT INTO `cmf_shop_goods_class` VALUES ('145', 'NB', '129', '126', '1', '1', '1582774204', '0', '3', '');
INSERT INTO `cmf_shop_goods_class` VALUES ('146', 'S', '129', '126', '2', '1', '1582774213', '0', '3', '');
INSERT INTO `cmf_shop_goods_class` VALUES ('147', 'M', '129', '126', '3', '1', '1582774227', '0', '3', '');
INSERT INTO `cmf_shop_goods_class` VALUES ('148', 'L', '129', '126', '4', '1', '1582774246', '0', '3', '');
INSERT INTO `cmf_shop_goods_class` VALUES ('149', 'XL', '129', '126', '5', '1', '1582774263', '0', '3', '');
INSERT INTO `cmf_shop_goods_class` VALUES ('150', '拉拉裤', '129', '126', '6', '1', '1582774276', '0', '3', '');
INSERT INTO `cmf_shop_goods_class` VALUES ('151', '奶瓶奶嘴', '130', '126', '1', '1', '1582774305', '0', '3', '');
INSERT INTO `cmf_shop_goods_class` VALUES ('152', '吸奶器', '130', '126', '2', '1', '1582774316', '0', '3', '');
INSERT INTO `cmf_shop_goods_class` VALUES ('153', '辅食料理机', '130', '126', '3', '1', '1582774332', '0', '3', '');
INSERT INTO `cmf_shop_goods_class` VALUES ('154', '儿童餐具', '130', '126', '4', '1', '1582774350', '0', '3', '');
INSERT INTO `cmf_shop_goods_class` VALUES ('155', '水壶/水杯', '130', '126', '6', '1', '1582774368', '0', '3', '');
INSERT INTO `cmf_shop_goods_class` VALUES ('156', '牙胶安抚', '130', '126', '7', '1', '1582774396', '0', '3', '');
INSERT INTO `cmf_shop_goods_class` VALUES ('157', '宝宝护肤', '131', '126', '1', '1', '1582774430', '0', '3', '');
INSERT INTO `cmf_shop_goods_class` VALUES ('158', '日常护理', '131', '126', '2', '1', '1582774444', '0', '3', '');
INSERT INTO `cmf_shop_goods_class` VALUES ('159', '洗发沐浴', '131', '126', '3', '1', '1582774459', '0', '3', '');
INSERT INTO `cmf_shop_goods_class` VALUES ('160', '驱蚊防晒', '131', '126', '4', '1', '1582774475', '0', '3', '');
INSERT INTO `cmf_shop_goods_class` VALUES ('161', '理发器', '131', '126', '5', '1', '1582774489', '0', '3', '');
INSERT INTO `cmf_shop_goods_class` VALUES ('162', '洗澡用具', '131', '126', '6', '1', '1582774506', '0', '3', '');
INSERT INTO `cmf_shop_goods_class` VALUES ('163', '婴童睡袋/抱被', '132', '126', '1', '1', '1582774553', '0', '3', '');
INSERT INTO `cmf_shop_goods_class` VALUES ('164', '婴童隔尿垫/巾', '132', '126', '2', '1', '1582774570', '0', '3', '');
INSERT INTO `cmf_shop_goods_class` VALUES ('165', '婴童浴巾/浴衣', '132', '126', '3', '1', '1582774584', '0', '3', '');
INSERT INTO `cmf_shop_goods_class` VALUES ('166', '婴童毛巾/口水巾', '132', '126', '4', '1', '1582774597', '0', '3', '');
INSERT INTO `cmf_shop_goods_class` VALUES ('167', '婴童布尿裤/尿布', '132', '126', '5', '1', '1582774613', '0', '3', '');
INSERT INTO `cmf_shop_goods_class` VALUES ('168', '婴儿内衣', '132', '126', '6', '1', '1582774644', '0', '3', '');
INSERT INTO `cmf_shop_goods_class` VALUES ('169', '爬行垫', '132', '126', '7', '1', '1582774660', '0', '3', '');
INSERT INTO `cmf_shop_goods_class` VALUES ('170', '孕妈装', '133', '126', '1', '1', '1582774710', '0', '3', '');
INSERT INTO `cmf_shop_goods_class` VALUES ('171', '孕妇护肤', '133', '126', '2', '1', '1582774727', '0', '3', '');
INSERT INTO `cmf_shop_goods_class` VALUES ('172', '孕妇内衣', '133', '126', '3', '1', '1582774764', '0', '3', '');
INSERT INTO `cmf_shop_goods_class` VALUES ('173', '防溢乳垫', '133', '126', '4', '1', '1582774788', '0', '3', '');
INSERT INTO `cmf_shop_goods_class` VALUES ('174', '婴儿推车', '134', '126', '1', '1', '1582774839', '0', '3', '');
INSERT INTO `cmf_shop_goods_class` VALUES ('175', '婴儿床', '134', '126', '2', '1', '1582774850', '0', '3', '');
INSERT INTO `cmf_shop_goods_class` VALUES ('176', '餐椅', '134', '126', '3', '1', '1582774871', '0', '3', '');
INSERT INTO `cmf_shop_goods_class` VALUES ('177', '学步车', '134', '126', '4', '1', '1582774882', '0', '3', '');
INSERT INTO `cmf_shop_goods_class` VALUES ('178', '积木', '135', '126', '1', '1', '1582774927', '0', '3', '');
INSERT INTO `cmf_shop_goods_class` VALUES ('179', '芭比娃娃', '135', '126', '2', '1', '1582774937', '0', '3', '');
INSERT INTO `cmf_shop_goods_class` VALUES ('180', '毛绒玩具', '135', '126', '3', '1', '1582774967', '0', '3', '');
INSERT INTO `cmf_shop_goods_class` VALUES ('181', '益智玩具', '135', '126', '4', '1', '1582774984', '0', '3', '');
INSERT INTO `cmf_shop_goods_class` VALUES ('182', '服装/男装/女装', '0', '0', '7', '1', '1585703754', '1585703923', '1', '');
INSERT INTO `cmf_shop_goods_class` VALUES ('183', '女装', '182', '182', '1', '1', '1585703810', '1585703948', '2', 'shop_two_class_29.png%@%cloudtype=1');
INSERT INTO `cmf_shop_goods_class` VALUES ('184', '卫衣', '183', '182', '1', '1', '1585703834', '1585703967', '3', '');
INSERT INTO `cmf_shop_goods_class` VALUES ('185', '休闲裤', '183', '182', '2', '1', '1585703850', '1585703997', '3', '');
INSERT INTO `cmf_shop_goods_class` VALUES ('186', '男装', '182', '182', '2', '1', '1585704024', '0', '2', 'shop_two_class_30.png%@%cloudtype=1');
INSERT INTO `cmf_shop_goods_class` VALUES ('187', '运动服', '186', '182', '1', '1', '1585704052', '0', '3', '');
INSERT INTO `cmf_shop_goods_class` VALUES ('188', '西装', '186', '182', '2', '1', '1585704064', '0', '3', '');
INSERT INTO `cmf_shop_goods_class` VALUES ('189', '衬衫', '186', '182', '3', '1', '1585704100', '0', '3', '');
INSERT INTO `cmf_shop_goods_class` VALUES ('190', '连衣裙', '183', '182', '3', '1', '1585704113', '0', '3', '');
INSERT INTO `cmf_shop_goods_class` VALUES ('191', 'T恤', '183', '182', '4', '1', '1585704128', '0', '3', '');
INSERT INTO `cmf_shop_goods_class` VALUES ('192', '时尚套装', '183', '182', '5', '1', '1585704146', '0', '3', '');
INSERT INTO `cmf_shop_goods_class` VALUES ('193', '医药', '0', '0', '8', '1', '1585705240', '0', '1', '');
INSERT INTO `cmf_shop_goods_class` VALUES ('194', '眼药水', '193', '193', '1', '1', '1585705254', '0', '2', 'shop_two_class_31.png%@%cloudtype=1');
INSERT INTO `cmf_shop_goods_class` VALUES ('195', '口罩', '193', '193', '2', '1', '1585705266', '0', '2', 'shop_two_class_32.png%@%cloudtype=1');
INSERT INTO `cmf_shop_goods_class` VALUES ('196', 'KN95', '195', '193', '1', '1', '1585709911', '1585721825', '3', '');
INSERT INTO `cmf_shop_goods_class` VALUES ('197', '普通一次医用口罩', '195', '193', '2', '1', '1585709936', '0', '3', '');
INSERT INTO `cmf_shop_goods_class` VALUES ('198', '抗疲劳', '194', '193', '1', '1', '1585709951', '1585721805', '3', '');
INSERT INTO `cmf_shop_goods_class` VALUES ('199', '防近视', '194', '193', '2', '1', '1585709974', '0', '3', '');
INSERT INTO `cmf_shop_goods_class` VALUES ('200', '游戏 / 动漫 / 影视', '0', '0', '9', '1', '1585901648', '0', '1', '');
INSERT INTO `cmf_shop_goods_class` VALUES ('201', '游戏', '200', '200', '1', '1', '1585901690', '1601012289', '2', 'shop_two_class_33.png%@%cloudtype=1');
INSERT INTO `cmf_shop_goods_class` VALUES ('202', '动漫周边', '200', '200', '2', '1', '1585901704', '0', '2', 'shop_two_class_34.png%@%cloudtype=1');
INSERT INTO `cmf_shop_goods_class` VALUES ('203', '热门影视周边', '200', '200', '3', '1', '1585901720', '0', '2', 'shop_two_class_35.png%@%cloudtype=1');
INSERT INTO `cmf_shop_goods_class` VALUES ('204', 'DNF', '201', '200', '1', '1', '1585901741', '1601012289', '3', '');
INSERT INTO `cmf_shop_goods_class` VALUES ('205', '梦幻西游', '201', '200', '2', '1', '1585901748', '1601012289', '3', '');
INSERT INTO `cmf_shop_goods_class` VALUES ('206', ' 魔兽', '201', '200', '3', '1', '1585901759', '1601012289', '3', '');
INSERT INTO `cmf_shop_goods_class` VALUES ('207', 'LOL', '201', '200', '4', '1', '1585901770', '1601012289', '3', '');
INSERT INTO `cmf_shop_goods_class` VALUES ('208', '坦克世界', '201', '200', '5', '1', '1585901783', '1601012289', '3', '');
INSERT INTO `cmf_shop_goods_class` VALUES ('209', '剑网3', '201', '200', '6', '1', '1585901797', '1601012289', '3', '');
INSERT INTO `cmf_shop_goods_class` VALUES ('211', 'DOTA2', '201', '200', '7', '1', '1585901826', '1601012289', '3', '');
INSERT INTO `cmf_shop_goods_class` VALUES ('212', '笔记本', '0', '0', '1', '1', '1599286060', '0', '1', '');
INSERT INTO `cmf_shop_goods_class` VALUES ('217', '英雄', '13', '1', '4', '0', '1605600136', '1616983499', '3', '');

-- ----------------------------
-- Table structure for `cmf_shop_order`
-- ----------------------------
DROP TABLE IF EXISTS `cmf_shop_order`;
CREATE TABLE `cmf_shop_order` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT COMMENT '订单ID',
  `uid` bigint(20) NOT NULL DEFAULT '0' COMMENT '购买者ID',
  `shop_uid` bigint(20) NOT NULL DEFAULT '0' COMMENT '卖家用户ID',
  `goodsid` bigint(20) NOT NULL DEFAULT '0' COMMENT '商品id',
  `goods_name` varchar(255) NOT NULL DEFAULT '' COMMENT '商品名称',
  `spec_id` int(11) NOT NULL DEFAULT '0' COMMENT '商品规格ID',
  `spec_name` varchar(255) NOT NULL DEFAULT '' COMMENT '规格名称',
  `spec_thumb` varchar(255) NOT NULL DEFAULT '' COMMENT '规格封面',
  `nums` int(11) NOT NULL DEFAULT '0' COMMENT '购买数量',
  `price` decimal(11,2) NOT NULL DEFAULT '0.00' COMMENT '商品单价',
  `total` decimal(11,2) NOT NULL DEFAULT '0.00' COMMENT '商品总价（包含邮费）',
  `username` varchar(255) NOT NULL DEFAULT '' COMMENT '购买者姓名',
  `phone` varchar(255) NOT NULL DEFAULT '' COMMENT '购买者联系电话',
  `country` varchar(255) NOT NULL DEFAULT '' COMMENT '国家',
  `country_code` int(11) NOT NULL DEFAULT '0' COMMENT '国家代号',
  `province` varchar(255) NOT NULL DEFAULT '' COMMENT '购买者省份',
  `city` varchar(255) NOT NULL DEFAULT '' COMMENT '购买者市',
  `area` varchar(255) NOT NULL DEFAULT '' COMMENT '购买者地区',
  `address` varchar(255) NOT NULL DEFAULT '' COMMENT '购买者详细地址',
  `postage` decimal(11,2) NOT NULL DEFAULT '0.00' COMMENT '邮费',
  `orderno` varchar(255) NOT NULL DEFAULT '' COMMENT '订单编号',
  `type` tinyint(1) NOT NULL DEFAULT '0' COMMENT '订单类型 1 支付宝 2 微信 3 余额 4 微信小程序 5 paypal 6 braintree_paypal',
  `trade_no` varchar(255) NOT NULL DEFAULT '' COMMENT '三方订单号',
  `status` tinyint(2) NOT NULL DEFAULT '0' COMMENT '订单状态  -1 已关闭  0 待付款 1 待发货 2 待收货 3 待评价 4 已评价 5 退款',
  `addtime` int(11) NOT NULL DEFAULT '0' COMMENT '订单添加时间',
  `cancel_time` int(11) NOT NULL DEFAULT '0' COMMENT '订单取消时间',
  `paytime` int(11) NOT NULL DEFAULT '0' COMMENT '订单付款时间',
  `shipment_time` int(11) NOT NULL DEFAULT '0' COMMENT '订单发货时间',
  `receive_time` int(11) NOT NULL DEFAULT '0' COMMENT '订单收货时间',
  `evaluate_time` int(11) NOT NULL DEFAULT '0' COMMENT '订单评价时间',
  `settlement_time` int(11) NOT NULL DEFAULT '0' COMMENT '订单结算时间（款项打给卖家）',
  `is_append_evaluate` tinyint(1) NOT NULL DEFAULT '1' COMMENT '是否可追加评价',
  `order_percent` int(11) NOT NULL DEFAULT '0' COMMENT '订单抽成比例',
  `refund_starttime` int(11) NOT NULL DEFAULT '0' COMMENT '订单发起退款时间',
  `refund_endtime` int(11) NOT NULL DEFAULT '0' COMMENT '订单退款处理结束时间',
  `refund_status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '退款处理结果 -2取消申请 -1 失败 0 处理中 1 成功 ',
  `refund_shop_result` tinyint(1) NOT NULL DEFAULT '0' COMMENT '退款时卖家处理结果 0 未处理 -1 拒绝 1 同意',
  `express_name` varchar(255) NOT NULL DEFAULT '' COMMENT '物流公司名称',
  `express_phone` varchar(255) NOT NULL DEFAULT '' COMMENT '物流公司电话',
  `express_thumb` varchar(255) NOT NULL DEFAULT '' COMMENT '物流公司图标',
  `express_code` varchar(255) NOT NULL DEFAULT '' COMMENT '快递公司对应三方平台的编码',
  `express_number` varchar(255) NOT NULL DEFAULT '' COMMENT '物流单号',
  `isdel` tinyint(1) NOT NULL DEFAULT '0' COMMENT '订单是否删除 0 否 -1 买家删除 -2 卖家删除 1 买家卖家都删除',
  `message` varchar(255) NOT NULL DEFAULT '' COMMENT '买家留言内容',
  `commission` decimal(20,2) NOT NULL DEFAULT '0.00' COMMENT '平台自营商品设置的代售佣金',
  `liveuid` bigint(20) NOT NULL DEFAULT '0' COMMENT '代售平台商品的主播ID',
  `admin_id` bigint(20) NOT NULL DEFAULT '0' COMMENT '发布自营商品的管理员id',
  `shareuid` bigint(20) NOT NULL DEFAULT '0' COMMENT '分享商品的用户ID',
  `share_income` decimal(20,2) NOT NULL DEFAULT '0.00' COMMENT '用户购买分享者分享的商品后，分享用户获得的佣金',
  PRIMARY KEY (`id`) USING BTREE,
  KEY `id_uid` (`id`,`uid`) USING BTREE,
  KEY `shopuid_status` (`shop_uid`,`status`) USING BTREE,
  KEY `shopuid_status_refundstatus` (`shop_uid`,`status`,`refund_status`) USING BTREE,
  KEY `id_shopuid` (`id`,`shop_uid`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Records of cmf_shop_order
-- ----------------------------

-- ----------------------------
-- Table structure for `cmf_shop_order_comments`
-- ----------------------------
DROP TABLE IF EXISTS `cmf_shop_order_comments`;
CREATE TABLE `cmf_shop_order_comments` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `uid` bigint(20) NOT NULL DEFAULT '0' COMMENT '用户ID',
  `orderid` bigint(20) NOT NULL DEFAULT '0' COMMENT '商品订单ID',
  `goodsid` bigint(20) NOT NULL COMMENT '商品ID',
  `shop_uid` bigint(20) NOT NULL DEFAULT '0' COMMENT '店铺用户id',
  `content` varchar(255) NOT NULL DEFAULT '' COMMENT '文字内容',
  `thumbs` text NOT NULL COMMENT '评论图片列表',
  `video_thumb` varchar(255) NOT NULL DEFAULT '' COMMENT '视频封面',
  `video_url` varchar(255) NOT NULL DEFAULT '' COMMENT '视频地址',
  `is_anonym` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否匿名 0否 1是',
  `quality_points` tinyint(1) NOT NULL DEFAULT '0' COMMENT '商品描述评分',
  `service_points` tinyint(1) NOT NULL DEFAULT '0' COMMENT '服务态度评分',
  `express_points` tinyint(1) NOT NULL DEFAULT '0' COMMENT '物流速度评分',
  `addtime` int(11) NOT NULL DEFAULT '0' COMMENT '添加时间',
  `is_append` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否是追评0 否 1 是',
  PRIMARY KEY (`id`) USING BTREE,
  KEY `goodsid_isappend` (`goodsid`,`is_append`) USING BTREE,
  KEY `uid_orderid` (`uid`,`orderid`,`is_append`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Records of cmf_shop_order_comments
-- ----------------------------

-- ----------------------------
-- Table structure for `cmf_shop_order_message`
-- ----------------------------
DROP TABLE IF EXISTS `cmf_shop_order_message`;
CREATE TABLE `cmf_shop_order_message` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `title` text NOT NULL COMMENT '消息内容',
  `orderid` bigint(20) NOT NULL DEFAULT '0',
  `uid` bigint(20) NOT NULL DEFAULT '0' COMMENT '接受消息用户ID',
  `addtime` int(11) NOT NULL DEFAULT '0' COMMENT '添加时间',
  `type` tinyint(1) NOT NULL DEFAULT '0' COMMENT '用户身份 0买家 1卖家',
  `is_commission` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否订单结算消息 0 否 1 是',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Records of cmf_shop_order_message
-- ----------------------------

-- ----------------------------
-- Table structure for `cmf_shop_order_refund`
-- ----------------------------
DROP TABLE IF EXISTS `cmf_shop_order_refund`;
CREATE TABLE `cmf_shop_order_refund` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `uid` bigint(20) NOT NULL DEFAULT '0' COMMENT '买家id',
  `orderid` bigint(20) NOT NULL DEFAULT '0' COMMENT '订单ID',
  `goodsid` bigint(20) NOT NULL DEFAULT '0' COMMENT '商品ID',
  `shop_uid` bigint(20) NOT NULL DEFAULT '0' COMMENT '商家ID',
  `reason` varchar(255) NOT NULL DEFAULT '' COMMENT '退款原因',
  `content` varchar(300) NOT NULL DEFAULT '' COMMENT '退款说明',
  `thumb` varchar(255) NOT NULL DEFAULT '' COMMENT '退款图片（废弃）',
  `type` tinyint(1) NOT NULL DEFAULT '0' COMMENT '退款类型 0 仅退款 1退货退款',
  `addtime` int(11) NOT NULL DEFAULT '0' COMMENT '申请时间',
  `edittime` int(11) NOT NULL DEFAULT '0' COMMENT '修改时间',
  `shop_process_time` int(11) NOT NULL DEFAULT '0' COMMENT '店铺处理时间',
  `shop_result` tinyint(1) NOT NULL DEFAULT '0' COMMENT '店铺处理结果 -1 拒绝 0 处理中 1 同意',
  `shop_process_num` tinyint(1) NOT NULL DEFAULT '0' COMMENT '店铺驳回次数',
  `platform_process_time` int(11) NOT NULL DEFAULT '0' COMMENT '平台处理时间',
  `platform_result` tinyint(1) NOT NULL DEFAULT '0' COMMENT '平台处理结果 -1 拒绝 0 处理中 1 同意',
  `admin` varchar(255) NOT NULL DEFAULT '' COMMENT '平台处理账号',
  `ip` bigint(20) NOT NULL DEFAULT '0' COMMENT '平台账号ip',
  `status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '退款处理状态 0 处理中 -1 买家已取消 1 已完成 ',
  `is_platform_interpose` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否平台介入 0 否 1 是',
  `system_process_time` int(11) NOT NULL DEFAULT '0' COMMENT '系统自动处理时间',
  `platform_interpose_reason` varchar(255) NOT NULL DEFAULT '' COMMENT '申请平台介入的理由',
  `platform_interpose_desc` varchar(255) NOT NULL DEFAULT '' COMMENT '申请平台介入的详细原因',
  `platform_interpose_thumb` varchar(255) NOT NULL DEFAULT '' COMMENT '申请平台介入的图片举证',
  PRIMARY KEY (`id`) USING BTREE,
  KEY `uid_orderid` (`uid`,`orderid`) USING BTREE,
  KEY `orderid_shopuid` (`orderid`,`shop_uid`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Records of cmf_shop_order_refund
-- ----------------------------

-- ----------------------------
-- Table structure for `cmf_shop_order_refund_list`
-- ----------------------------
DROP TABLE IF EXISTS `cmf_shop_order_refund_list`;
CREATE TABLE `cmf_shop_order_refund_list` (
  `orderid` bigint(11) NOT NULL DEFAULT '0' COMMENT '订单ID',
  `type` tinyint(1) NOT NULL DEFAULT '0' COMMENT '处理方 1 买家 2 卖家 3 平台 4 系统',
  `addtime` int(11) NOT NULL DEFAULT '0' COMMENT '处理时间',
  `desc` varchar(255) NOT NULL DEFAULT '' COMMENT '处理说明',
  `handle_desc` varchar(300) NOT NULL DEFAULT '' COMMENT '处理备注说明',
  `refuse_reason` varchar(255) NOT NULL DEFAULT '' COMMENT '卖家拒绝理由'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Records of cmf_shop_order_refund_list
-- ----------------------------

-- ----------------------------
-- Table structure for `cmf_shop_platform_reason`
-- ----------------------------
DROP TABLE IF EXISTS `cmf_shop_platform_reason`;
CREATE TABLE `cmf_shop_platform_reason` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL DEFAULT '' COMMENT '原因名称',
  `list_order` int(11) NOT NULL DEFAULT '0' COMMENT '排序号',
  `status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '状态 0不显示 1 显示',
  `addtime` int(11) NOT NULL DEFAULT '0' COMMENT '添加时间',
  `edittime` int(11) NOT NULL DEFAULT '0' COMMENT '修改时间',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Records of cmf_shop_platform_reason
-- ----------------------------
INSERT INTO `cmf_shop_platform_reason` VALUES ('1', '卖家未履行约定', '1', '1', '1584774096', '1616752695');
INSERT INTO `cmf_shop_platform_reason` VALUES ('2', '商品质量存在问题', '2', '1', '1584774114', '0');
INSERT INTO `cmf_shop_platform_reason` VALUES ('3', '卖家态度蛮横无理', '3', '1', '1584774131', '0');
INSERT INTO `cmf_shop_platform_reason` VALUES ('4', '其它', '4', '1', '1589785536', '0');

-- ----------------------------
-- Table structure for `cmf_shop_points`
-- ----------------------------
DROP TABLE IF EXISTS `cmf_shop_points`;
CREATE TABLE `cmf_shop_points` (
  `shop_uid` bigint(20) NOT NULL DEFAULT '0' COMMENT '店铺用户ID',
  `evaluate_total` bigint(20) NOT NULL DEFAULT '0' COMMENT '评价总数',
  `quality_points_total` int(11) NOT NULL DEFAULT '0' COMMENT '店铺商品质量(商品描述)总分',
  `service_points_total` int(11) NOT NULL DEFAULT '0' COMMENT '店铺服务态度总分',
  `express_points_total` int(11) NOT NULL DEFAULT '0' COMMENT '物流服务总分'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Records of cmf_shop_points
-- ----------------------------

-- ----------------------------
-- Table structure for `cmf_shop_refund_reason`
-- ----------------------------
DROP TABLE IF EXISTS `cmf_shop_refund_reason`;
CREATE TABLE `cmf_shop_refund_reason` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL DEFAULT '' COMMENT '原因名称',
  `list_order` int(11) NOT NULL DEFAULT '0' COMMENT '排序号',
  `status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '状态 0不显示 1 显示',
  `addtime` int(11) NOT NULL DEFAULT '0' COMMENT '添加时间',
  `edittime` int(11) NOT NULL DEFAULT '0' COMMENT '修改时间',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Records of cmf_shop_refund_reason
-- ----------------------------
INSERT INTO `cmf_shop_refund_reason` VALUES ('1', '拍错/拍多/不想要了', '1', '1', '1584430567', '1584432392');
INSERT INTO `cmf_shop_refund_reason` VALUES ('2', '卖家未按约定时间发货', '2', '1', '1584430600', '0');
INSERT INTO `cmf_shop_refund_reason` VALUES ('3', '其他', '4', '1', '1584431428', '0');
INSERT INTO `cmf_shop_refund_reason` VALUES ('4', '存在质量问题', '3', '1', '1586829690', '1616984700');
INSERT INTO `cmf_shop_refund_reason` VALUES ('5', '7天无理由退款', '0', '1', '1586829705', '0');

-- ----------------------------
-- Table structure for `cmf_shop_refuse_reason`
-- ----------------------------
DROP TABLE IF EXISTS `cmf_shop_refuse_reason`;
CREATE TABLE `cmf_shop_refuse_reason` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL DEFAULT '' COMMENT '原因名称',
  `list_order` int(11) NOT NULL DEFAULT '0' COMMENT '排序号',
  `status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '状态 0不显示 1 显示',
  `addtime` int(11) NOT NULL DEFAULT '0' COMMENT '添加时间',
  `edittime` int(11) NOT NULL DEFAULT '0' COMMENT '修改时间',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Records of cmf_shop_refuse_reason
-- ----------------------------
INSERT INTO `cmf_shop_refuse_reason` VALUES ('1', '买家未举证/举证无效', '1', '1', '1584698435', '1584699310');
INSERT INTO `cmf_shop_refuse_reason` VALUES ('2', '收到退货后再退款', '2', '1', '1584698538', '1617022186');
INSERT INTO `cmf_shop_refuse_reason` VALUES ('3', '已发货,请买家承担运费', '3', '1', '1584698558', '0');
INSERT INTO `cmf_shop_refuse_reason` VALUES ('4', '商品损坏', '0', '1', '1586829756', '0');

-- ----------------------------
-- Table structure for `cmf_slide`
-- ----------------------------
DROP TABLE IF EXISTS `cmf_slide`;
CREATE TABLE `cmf_slide` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `status` tinyint(3) unsigned NOT NULL DEFAULT '1' COMMENT '状态,1:显示,0不显示',
  `delete_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '删除时间',
  `name` varchar(50) CHARACTER SET utf8 NOT NULL DEFAULT '' COMMENT '幻灯片分类',
  `remark` varchar(255) CHARACTER SET utf8 NOT NULL DEFAULT '' COMMENT '分类备注',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 ROW_FORMAT=COMPACT COMMENT='幻灯片表';

-- ----------------------------
-- Records of cmf_slide
-- ----------------------------

-- ----------------------------
-- Table structure for `cmf_slide_item`
-- ----------------------------
DROP TABLE IF EXISTS `cmf_slide_item`;
CREATE TABLE `cmf_slide_item` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `slide_id` int(11) NOT NULL DEFAULT '0' COMMENT '幻灯片id',
  `status` tinyint(3) unsigned NOT NULL DEFAULT '1' COMMENT '状态,1:显示;0:隐藏',
  `list_order` float NOT NULL DEFAULT '10000' COMMENT '排序',
  `title` varchar(50) NOT NULL DEFAULT '' COMMENT '幻灯片名称',
  `image` varchar(255) CHARACTER SET utf8 NOT NULL DEFAULT '' COMMENT '幻灯片图片',
  `url` varchar(255) CHARACTER SET utf8 NOT NULL DEFAULT '' COMMENT '幻灯片链接',
  `target` varchar(10) NOT NULL DEFAULT '' COMMENT '友情链接打开方式',
  `description` varchar(255) CHARACTER SET utf8 NOT NULL COMMENT '幻灯片描述',
  `content` text CHARACTER SET utf8 COMMENT '幻灯片内容',
  `more` text COMMENT '扩展信息',
  PRIMARY KEY (`id`) USING BTREE,
  KEY `slide_id` (`slide_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 ROW_FORMAT=COMPACT COMMENT='幻灯片子项表';

-- ----------------------------
-- Records of cmf_slide_item
-- ----------------------------

-- ----------------------------
-- Table structure for `cmf_system_push`
-- ----------------------------
DROP TABLE IF EXISTS `cmf_system_push`;
CREATE TABLE `cmf_system_push` (
  `id` int(12) NOT NULL AUTO_INCREMENT COMMENT '自增ID',
  `uid` int(12) DEFAULT '0' COMMENT '用户id',
  `title` varchar(255) DEFAULT '' COMMENT '标题',
  `content` varchar(255) DEFAULT '' COMMENT '内容',
  `admin` varchar(255) DEFAULT '' COMMENT '发布者账号',
  `addtime` int(12) DEFAULT '0' COMMENT '发布时间',
  `ip` varchar(255) DEFAULT '' COMMENT 'IP地址',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 ROW_FORMAT=COMPACT;

-- ----------------------------
-- Records of cmf_system_push
-- ----------------------------

-- ----------------------------
-- Table structure for `cmf_terms`
-- ----------------------------
DROP TABLE IF EXISTS `cmf_terms`;
CREATE TABLE `cmf_terms` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT COMMENT '分类id',
  `name` varchar(200) DEFAULT NULL COMMENT '分类名称',
  `count` bigint(20) DEFAULT '0' COMMENT '分类文章数',
  `seo_title` varchar(500) DEFAULT NULL,
  `seo_keywords` varchar(500) DEFAULT NULL,
  `seo_description` varchar(500) DEFAULT NULL,
  `listorder` int(5) NOT NULL DEFAULT '0' COMMENT '排序',
  `parentid` int(11) NOT NULL DEFAULT '0',
  `path` varchar(255) NOT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=14 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='Portal 文章分类表';

-- ----------------------------
-- Records of cmf_terms
-- ----------------------------
INSERT INTO `cmf_terms` VALUES ('11', '关于我们', '0', '关于我们', '', '', '0', '0', '');
INSERT INTO `cmf_terms` VALUES ('13', '常见问题', '0', '常见问题', '', '', '0', '0', '');

-- ----------------------------
-- Table structure for `cmf_theme`
-- ----------------------------
DROP TABLE IF EXISTS `cmf_theme`;
CREATE TABLE `cmf_theme` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `create_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '安装时间',
  `update_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '最后升级时间',
  `status` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '模板状态,1:正在使用;0:未使用',
  `is_compiled` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '是否为已编译模板',
  `theme` varchar(20) NOT NULL DEFAULT '' COMMENT '主题目录名，用于主题的维一标识',
  `name` varchar(20) NOT NULL DEFAULT '' COMMENT '主题名称',
  `version` varchar(20) NOT NULL DEFAULT '' COMMENT '主题版本号',
  `demo_url` varchar(50) NOT NULL DEFAULT '' COMMENT '演示地址，带协议',
  `thumbnail` varchar(100) NOT NULL DEFAULT '' COMMENT '缩略图',
  `author` varchar(20) NOT NULL DEFAULT '' COMMENT '主题作者',
  `author_url` varchar(50) NOT NULL DEFAULT '' COMMENT '作者网站链接',
  `lang` varchar(10) NOT NULL DEFAULT '' COMMENT '支持语言',
  `keywords` varchar(50) NOT NULL DEFAULT '' COMMENT '主题关键字',
  `description` varchar(100) NOT NULL DEFAULT '' COMMENT '主题描述',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

-- ----------------------------
-- Records of cmf_theme
-- ----------------------------
INSERT INTO `cmf_theme` VALUES ('1', '0', '0', '0', '0', 'default', 'default', '1.0.0', 'http://demo.thinkcmf.com', '', 'ThinkCMF', 'http://www.thinkcmf.com', 'zh-cn', 'ThinkCMF默认模板', 'ThinkCMF默认模板');

-- ----------------------------
-- Table structure for `cmf_theme_file`
-- ----------------------------
DROP TABLE IF EXISTS `cmf_theme_file`;
CREATE TABLE `cmf_theme_file` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `is_public` tinyint(4) NOT NULL DEFAULT '0' COMMENT '是否公共的模板文件',
  `list_order` float NOT NULL DEFAULT '10000' COMMENT '排序',
  `theme` varchar(20) NOT NULL DEFAULT '' COMMENT '模板名称',
  `name` varchar(20) NOT NULL DEFAULT '' COMMENT '模板文件名',
  `action` varchar(50) NOT NULL DEFAULT '' COMMENT '操作',
  `file` varchar(50) NOT NULL DEFAULT '' COMMENT '模板文件，相对于模板根目录，如Portal/index.html',
  `description` varchar(100) NOT NULL DEFAULT '' COMMENT '模板文件描述',
  `more` text COMMENT '模板更多配置,用户自己后台设置的',
  `config_more` text COMMENT '模板更多配置,来源模板的配置文件',
  `draft_more` text COMMENT '模板更多配置,用户临时保存的配置',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

-- ----------------------------
-- Records of cmf_theme_file
-- ----------------------------

-- ----------------------------
-- Table structure for `cmf_tourist_video_watchlists`
-- ----------------------------
DROP TABLE IF EXISTS `cmf_tourist_video_watchlists`;
CREATE TABLE `cmf_tourist_video_watchlists` (
  `mobileid` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '手机唯一识别码',
  `video_ids` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '视频id列表',
  KEY `mobileid` (`mobileid`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Records of cmf_tourist_video_watchlists
-- ----------------------------

-- ----------------------------
-- Table structure for `cmf_tourist_video_watchtime`
-- ----------------------------
DROP TABLE IF EXISTS `cmf_tourist_video_watchtime`;
CREATE TABLE `cmf_tourist_video_watchtime` (
  `mobileid` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '手机唯一识别码',
  `video_watchtime` int(11) NOT NULL DEFAULT '0' COMMENT '视频最新观看时间',
  KEY `mobileid` (`mobileid`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Records of cmf_tourist_video_watchtime
-- ----------------------------

-- ----------------------------
-- Table structure for `cmf_turntable`
-- ----------------------------
DROP TABLE IF EXISTS `cmf_turntable`;
CREATE TABLE `cmf_turntable` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `type` tinyint(1) NOT NULL DEFAULT '0' COMMENT '类型，0无奖1钻石2礼物',
  `type_val` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '类型值',
  `thumb` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '图片',
  `rate` decimal(10,3) NOT NULL DEFAULT '0.000' COMMENT '中奖率',
  `uptime` bigint(20) NOT NULL DEFAULT '0' COMMENT '时间',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Records of cmf_turntable
-- ----------------------------
INSERT INTO `cmf_turntable` VALUES ('1', '2', '11', '', '10.000', '1603414978');
INSERT INTO `cmf_turntable` VALUES ('2', '2', '28', '', '10.000', '1603415016');
INSERT INTO `cmf_turntable` VALUES ('3', '1', '5000', '', '10.000', '1599879630');
INSERT INTO `cmf_turntable` VALUES ('4', '2', '24', '', '10.000', '1599889501');
INSERT INTO `cmf_turntable` VALUES ('5', '0', '0', '', '0.000', '1599879278');
INSERT INTO `cmf_turntable` VALUES ('6', '3', '水晶鞋', '', '10.000', '1603424731');
INSERT INTO `cmf_turntable` VALUES ('7', '1', '1000', '', '10.000', '1602318027');
INSERT INTO `cmf_turntable` VALUES ('8', '2', '75', '', '10.000', '1599889609');

-- ----------------------------
-- Table structure for `cmf_turntable_con`
-- ----------------------------
DROP TABLE IF EXISTS `cmf_turntable_con`;
CREATE TABLE `cmf_turntable_con` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `times` int(11) NOT NULL DEFAULT '0' COMMENT '次数',
  `coin` int(11) NOT NULL DEFAULT '0' COMMENT '价格',
  `list_order` int(11) NOT NULL DEFAULT '9999' COMMENT '序号',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Records of cmf_turntable_con
-- ----------------------------
INSERT INTO `cmf_turntable_con` VALUES ('1', '1', '10', '1');
INSERT INTO `cmf_turntable_con` VALUES ('2', '10', '100', '2');
INSERT INTO `cmf_turntable_con` VALUES ('3', '100', '1000', '3');

-- ----------------------------
-- Table structure for `cmf_turntable_log`
-- ----------------------------
DROP TABLE IF EXISTS `cmf_turntable_log`;
CREATE TABLE `cmf_turntable_log` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `uid` bigint(20) NOT NULL DEFAULT '0' COMMENT '用户ID',
  `liveuid` bigint(20) NOT NULL DEFAULT '0' COMMENT '主播ID',
  `showid` bigint(20) NOT NULL DEFAULT '0' COMMENT '直播标识',
  `coin` int(11) NOT NULL DEFAULT '0' COMMENT '价格',
  `nums` int(11) NOT NULL DEFAULT '0' COMMENT '数量',
  `addtime` bigint(20) NOT NULL DEFAULT '0' COMMENT '时间',
  `iswin` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否中奖',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Records of cmf_turntable_log
-- ----------------------------

-- ----------------------------
-- Table structure for `cmf_turntable_win`
-- ----------------------------
DROP TABLE IF EXISTS `cmf_turntable_win`;
CREATE TABLE `cmf_turntable_win` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `logid` bigint(20) NOT NULL DEFAULT '0' COMMENT '转盘记录ID',
  `uid` bigint(20) NOT NULL DEFAULT '0' COMMENT '用户ID',
  `type` tinyint(1) NOT NULL DEFAULT '0' COMMENT '类型，0无奖1钻石2礼物',
  `type_val` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '0' COMMENT '类型值',
  `thumb` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '图片',
  `nums` int(11) NOT NULL DEFAULT '0' COMMENT '数量',
  `addtime` bigint(20) NOT NULL DEFAULT '0' COMMENT '时间',
  `status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '处理状态，0未处理1已处理',
  `uptime` bigint(20) NOT NULL DEFAULT '0' COMMENT '处理时间',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Records of cmf_turntable_win
-- ----------------------------

-- ----------------------------
-- Table structure for `cmf_user`
-- ----------------------------
DROP TABLE IF EXISTS `cmf_user`;
CREATE TABLE `cmf_user` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_login` varchar(60) CHARACTER SET utf8 NOT NULL DEFAULT '' COMMENT '用户名',
  `user_pass` varchar(64) CHARACTER SET utf8 NOT NULL DEFAULT '' COMMENT '登录密码；sp_password加密',
  `user_nicename` varchar(50) NOT NULL DEFAULT '' COMMENT '用户美名',
  `user_email` varchar(100) CHARACTER SET utf8 NOT NULL DEFAULT '' COMMENT '登录邮箱',
  `user_url` varchar(100) CHARACTER SET utf8 NOT NULL DEFAULT '' COMMENT '用户个人网站',
  `avatar` varchar(255) CHARACTER SET utf8 NOT NULL DEFAULT '' COMMENT '用户头像，相对于upload/avatar目录',
  `avatar_thumb` varchar(255) CHARACTER SET utf8 NOT NULL DEFAULT '',
  `sex` smallint(1) NOT NULL DEFAULT '2' COMMENT '性别；0：保密，1：男；2：女',
  `age` int(10) NOT NULL DEFAULT '-1' COMMENT '年龄',
  `birthday` varchar(50) CHARACTER SET utf8 NOT NULL DEFAULT '' COMMENT '生日',
  `signature` varchar(255) NOT NULL DEFAULT '' COMMENT '个性签名',
  `last_login_ip` varchar(16) CHARACTER SET utf8 NOT NULL DEFAULT '' COMMENT '最后登录ip',
  `last_login_time` int(11) NOT NULL DEFAULT '0' COMMENT '最后登录时间',
  `create_time` int(11) NOT NULL DEFAULT '0' COMMENT '注册时间',
  `user_activation_key` varchar(60) CHARACTER SET utf8 NOT NULL DEFAULT '' COMMENT '激活码',
  `user_status` int(11) NOT NULL DEFAULT '1' COMMENT '用户状态 0：禁用； 1：正常 ；2：未验证 3：已注销',
  `score` int(11) NOT NULL DEFAULT '0' COMMENT '用户积分',
  `user_type` smallint(1) NOT NULL DEFAULT '1' COMMENT '用户类型，1:admin ;2:会员',
  `coin` int(20) unsigned NOT NULL DEFAULT '0' COMMENT '金币',
  `mobile` varchar(20) CHARACTER SET utf8 NOT NULL DEFAULT '' COMMENT '手机号',
  `weixin` varchar(255) CHARACTER SET utf8 NOT NULL DEFAULT '',
  `province` varchar(255) CHARACTER SET utf8 NOT NULL DEFAULT '' COMMENT '省',
  `city` varchar(255) CHARACTER SET utf8 NOT NULL DEFAULT '' COMMENT '市',
  `area` varchar(255) CHARACTER SET utf8 NOT NULL DEFAULT '' COMMENT '区',
  `isrecommend` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0 未推荐 1 推荐',
  `openid` varchar(255) CHARACTER SET utf8 NOT NULL DEFAULT '',
  `login_type` varchar(20) CHARACTER SET utf8 NOT NULL DEFAULT 'phone',
  `isauth` tinyint(1) NOT NULL DEFAULT '0',
  `code` varchar(255) CHARACTER SET utf8 NOT NULL DEFAULT '' COMMENT '邀请码',
  `is_ad` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否为广告视频发布者0 否 1是',
  `source` varchar(255) NOT NULL DEFAULT 'pc' COMMENT '注册来源android/ios/pc',
  `votes` bigint(11) NOT NULL DEFAULT '0' COMMENT '收益',
  `vip_endtime` int(11) NOT NULL DEFAULT '0' COMMENT 'vip到期时间 0表示未开通vip',
  `mobileid` varchar(255) NOT NULL DEFAULT '' COMMENT '手机唯一识别码',
  `ip` bigint(20) NOT NULL DEFAULT '0' COMMENT 'ip地址ip2long 格式化',
  `votestotal` bigint(20) NOT NULL DEFAULT '0' COMMENT '映票总数',
  `issuper` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否是超管 0 否 1 是',
  `consumption` bigint(20) NOT NULL DEFAULT '0' COMMENT '消费总额',
  `balance` decimal(20,2) NOT NULL DEFAULT '0.00' COMMENT '余额',
  `balance_total` decimal(20,2) NOT NULL DEFAULT '0.00' COMMENT '总余额',
  `balance_consumption` decimal(20,2) NOT NULL DEFAULT '0.00' COMMENT '用户商城累计消费人民币',
  `recommend_time` int(11) NOT NULL DEFAULT '0' COMMENT '推荐时间',
  `country_code` int(11) NOT NULL DEFAULT '86' COMMENT '国家代号',
  `is_firstlogin` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否第一次登陆 0 否 1 是',
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE KEY `user_login` (`user_login`) USING BTREE,
  KEY `user_login_key` (`user_login`) USING BTREE,
  KEY `index_ishot_isrecommend` (`isrecommend`) USING BTREE,
  KEY `user_nicename` (`user_nicename`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=20530 DEFAULT CHARSET=utf8mb4 ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Records of cmf_user
-- ----------------------------
INSERT INTO `cmf_user` VALUES ('1', 'admin', '###f283504ac9cdbb3a0ae187704498794b', 'admin', 'admin@qq.com', '', '', '', '1', '0', '', '', '123.135.139.240', '1639183454', '1457311672', '', '1', '0', '1', '0', '', '', '', '', '', '0', '', 'phone', '0', '', '0', 'pc', '0', '0', '', '0', '0', '0', '0', '0.00', '0.00', '0.00', '0', '86', '0');

-- ----------------------------
-- Table structure for `cmf_user_action`
-- ----------------------------
DROP TABLE IF EXISTS `cmf_user_action`;
CREATE TABLE `cmf_user_action` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `score` int(11) NOT NULL DEFAULT '0' COMMENT '更改积分，可以为负',
  `coin` int(11) NOT NULL DEFAULT '0' COMMENT '更改金币，可以为负',
  `reward_number` int(11) NOT NULL DEFAULT '0' COMMENT '奖励次数',
  `cycle_type` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '周期类型;0:不限;1:按天;2:按小时;3:永久',
  `cycle_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '周期时间值',
  `name` varchar(50) NOT NULL DEFAULT '' COMMENT '用户操作名称',
  `action` varchar(50) NOT NULL DEFAULT '' COMMENT '用户操作名称',
  `app` varchar(50) NOT NULL DEFAULT '' COMMENT '操作所在应用名或插件名等',
  `url` text COMMENT '执行操作的url',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 ROW_FORMAT=COMPACT COMMENT='用户操作表';

-- ----------------------------
-- Records of cmf_user_action
-- ----------------------------
INSERT INTO `cmf_user_action` VALUES ('1', '1', '1', '1', '2', '1', '用户登录', 'login', 'user', '');

-- ----------------------------
-- Table structure for `cmf_user_action_log`
-- ----------------------------
DROP TABLE IF EXISTS `cmf_user_action_log`;
CREATE TABLE `cmf_user_action_log` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '用户id',
  `count` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '访问次数',
  `last_visit_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '最后访问时间',
  `object` varchar(100) NOT NULL DEFAULT '' COMMENT '访问对象的id,格式:不带前缀的表名+id;如posts1表示xx_posts表里id为1的记录',
  `action` varchar(50) NOT NULL DEFAULT '' COMMENT '操作名称;格式:应用名+控制器+操作名,也可自己定义格式只要不发生冲突且惟一;',
  `ip` varchar(15) NOT NULL DEFAULT '' COMMENT '用户ip',
  PRIMARY KEY (`id`) USING BTREE,
  KEY `user_object_action` (`user_id`,`object`,`action`) USING BTREE,
  KEY `user_object_action_ip` (`user_id`,`object`,`action`,`ip`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 ROW_FORMAT=COMPACT COMMENT='访问记录表';

-- ----------------------------
-- Records of cmf_user_action_log
-- ----------------------------

-- ----------------------------
-- Table structure for `cmf_user_attention`
-- ----------------------------
DROP TABLE IF EXISTS `cmf_user_attention`;
CREATE TABLE `cmf_user_attention` (
  `uid` int(12) NOT NULL DEFAULT '0',
  `touid` int(12) NOT NULL DEFAULT '0',
  `addtime` int(12) DEFAULT '0' COMMENT '添加时间',
  KEY `uid_touid_index` (`uid`,`touid`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Records of cmf_user_attention
-- ----------------------------

-- ----------------------------
-- Table structure for `cmf_user_attention_messages`
-- ----------------------------
DROP TABLE IF EXISTS `cmf_user_attention_messages`;
CREATE TABLE `cmf_user_attention_messages` (
  `id` int(12) NOT NULL AUTO_INCREMENT COMMENT '自增ID',
  `uid` int(12) DEFAULT NULL,
  `touid` int(12) DEFAULT '0' COMMENT '被关注用户ID',
  `addtime` int(12) DEFAULT '0' COMMENT '更新时间',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 ROW_FORMAT=COMPACT;

-- ----------------------------
-- Records of cmf_user_attention_messages
-- ----------------------------

-- ----------------------------
-- Table structure for `cmf_user_auth`
-- ----------------------------
DROP TABLE IF EXISTS `cmf_user_auth`;
CREATE TABLE `cmf_user_auth` (
  `uid` int(11) unsigned NOT NULL,
  `real_name` varchar(50) NOT NULL DEFAULT '' COMMENT '真实姓名',
  `mobile` varchar(50) NOT NULL DEFAULT '' COMMENT '联系电话',
  `cer_no` varchar(50) NOT NULL DEFAULT '' COMMENT '身份证号',
  `front_view` varchar(255) NOT NULL DEFAULT '' COMMENT '证件正面',
  `back_view` varchar(255) NOT NULL DEFAULT '' COMMENT '证件反面',
  `handset_view` varchar(255) NOT NULL DEFAULT '' COMMENT '手持证件正面照',
  `reason` text COMMENT '原因',
  `addtime` int(12) NOT NULL DEFAULT '0' COMMENT '提交时间',
  `uptime` int(12) NOT NULL DEFAULT '0' COMMENT '更新时间',
  `status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '状态，0审核中1通过2拒绝',
  PRIMARY KEY (`uid`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Records of cmf_user_auth
-- ----------------------------

-- ----------------------------
-- Table structure for `cmf_user_balance_cashrecord`
-- ----------------------------
DROP TABLE IF EXISTS `cmf_user_balance_cashrecord`;
CREATE TABLE `cmf_user_balance_cashrecord` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(11) NOT NULL DEFAULT '0' COMMENT '用户ID',
  `money` decimal(20,2) NOT NULL DEFAULT '0.00' COMMENT '提现金额',
  `orderno` varchar(50) NOT NULL DEFAULT '' COMMENT '订单号',
  `trade_no` varchar(100) NOT NULL DEFAULT '' COMMENT '三方订单号',
  `status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '状态，0审核中，1审核通过，2审核拒绝',
  `addtime` int(11) NOT NULL DEFAULT '0' COMMENT '申请时间',
  `uptime` int(11) NOT NULL DEFAULT '0' COMMENT '更新时间',
  `type` tinyint(1) NOT NULL DEFAULT '0' COMMENT '账号类型 1 支付宝 2 微信 3 银行卡',
  `account_bank` varchar(255) NOT NULL DEFAULT '' COMMENT '银行名称',
  `account` varchar(255) NOT NULL DEFAULT '' COMMENT '帐号',
  `name` varchar(255) NOT NULL DEFAULT '' COMMENT '姓名',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Records of cmf_user_balance_cashrecord
-- ----------------------------

-- ----------------------------
-- Table structure for `cmf_user_balance_record`
-- ----------------------------
DROP TABLE IF EXISTS `cmf_user_balance_record`;
CREATE TABLE `cmf_user_balance_record` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `uid` bigint(20) NOT NULL COMMENT '用户id',
  `touid` bigint(20) NOT NULL COMMENT '对方用户id',
  `balance` decimal(11,2) NOT NULL DEFAULT '0.00' COMMENT '操作的余额数',
  `type` tinyint(1) NOT NULL DEFAULT '0' COMMENT '收支类型,0支出1收入',
  `action` tinyint(1) NOT NULL DEFAULT '0' COMMENT '收支行为 1 买家使用余额付款 2 系统自动结算货款给卖家  3 卖家超时未发货,退款给买家 4 买家发起退款，卖家超时未处理,系统自动退款 5买家发起退款，卖家同意 6 买家发起退款，平台介入后同意 7 用户使用余额购买付费项目  8 付费项目收入 9 代售平台商品佣金 10 分享商品给其他用户购买后获得佣金',
  `orderid` bigint(20) NOT NULL DEFAULT '0' COMMENT '对应的订单ID',
  `addtime` int(11) NOT NULL DEFAULT '0' COMMENT '添加时间',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Records of cmf_user_balance_record
-- ----------------------------

-- ----------------------------
-- Table structure for `cmf_user_beauty_params`
-- ----------------------------
DROP TABLE IF EXISTS `cmf_user_beauty_params`;
CREATE TABLE `cmf_user_beauty_params` (
  `uid` bigint(20) NOT NULL COMMENT '用户id',
  `params` varchar(500) NOT NULL DEFAULT '' COMMENT '美颜参数json字符串',
  PRIMARY KEY (`uid`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Records of cmf_user_beauty_params
-- ----------------------------

-- ----------------------------
-- Table structure for `cmf_user_black`
-- ----------------------------
DROP TABLE IF EXISTS `cmf_user_black`;
CREATE TABLE `cmf_user_black` (
  `uid` int(12) DEFAULT '0',
  `touid` int(12) DEFAULT '0',
  `addtime` int(12) DEFAULT '0' COMMENT '添加时间',
  KEY `uid_touid_index` (`uid`,`touid`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

-- ----------------------------
-- Records of cmf_user_black
-- ----------------------------

-- ----------------------------
-- Table structure for `cmf_user_cashrecord`
-- ----------------------------
DROP TABLE IF EXISTS `cmf_user_cashrecord`;
CREATE TABLE `cmf_user_cashrecord` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(11) NOT NULL DEFAULT '0' COMMENT '用户ID',
  `money` decimal(20,2) NOT NULL DEFAULT '0.00' COMMENT '实际提现金额',
  `votes` int(20) NOT NULL DEFAULT '0' COMMENT '提现映票数',
  `orderno` varchar(50) NOT NULL DEFAULT '' COMMENT '订单号',
  `trade_no` varchar(100) NOT NULL DEFAULT '' COMMENT '三方订单号',
  `status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '状态，0审核中，1审核通过，2审核拒绝',
  `addtime` int(11) NOT NULL DEFAULT '0' COMMENT '申请时间',
  `uptime` int(11) NOT NULL DEFAULT '0' COMMENT '更新时间',
  `type` tinyint(1) NOT NULL DEFAULT '0' COMMENT '账号类型',
  `account_bank` varchar(255) NOT NULL DEFAULT '' COMMENT '银行名称',
  `account` varchar(255) NOT NULL DEFAULT '' COMMENT '帐号',
  `name` varchar(255) NOT NULL DEFAULT '' COMMENT '姓名',
  `cash_money` decimal(20,2) NOT NULL DEFAULT '0.00' COMMENT '用户填写的提现金额',
  `cash_take` int(11) NOT NULL DEFAULT '0' COMMENT '提现抽成比例',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Records of cmf_user_cashrecord
-- ----------------------------

-- ----------------------------
-- Table structure for `cmf_user_cash_account`
-- ----------------------------
DROP TABLE IF EXISTS `cmf_user_cash_account`;
CREATE TABLE `cmf_user_cash_account` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) NOT NULL DEFAULT '0' COMMENT '用户ID',
  `type` tinyint(1) NOT NULL DEFAULT '0' COMMENT '类型，1表示支付宝，2表示微信，3表示银行卡',
  `account_bank` varchar(255) NOT NULL DEFAULT '' COMMENT '银行名称',
  `name` varchar(255) NOT NULL DEFAULT '' COMMENT '姓名',
  `account` varchar(255) NOT NULL DEFAULT '' COMMENT '账号',
  `addtime` int(11) NOT NULL DEFAULT '0' COMMENT '添加时间',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 ROW_FORMAT=COMPACT;

-- ----------------------------
-- Records of cmf_user_cash_account
-- ----------------------------

-- ----------------------------
-- Table structure for `cmf_user_charge`
-- ----------------------------
DROP TABLE IF EXISTS `cmf_user_charge`;
CREATE TABLE `cmf_user_charge` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(11) NOT NULL DEFAULT '0' COMMENT '用户ID',
  `touid` int(11) NOT NULL DEFAULT '0' COMMENT '充值对象ID',
  `money` decimal(11,2) NOT NULL DEFAULT '0.00' COMMENT '金额',
  `coin` int(11) NOT NULL DEFAULT '0' COMMENT '钻石数',
  `coin_give` int(11) NOT NULL DEFAULT '0' COMMENT '赠送钻石数',
  `orderno` varchar(50) NOT NULL DEFAULT '' COMMENT '商家订单号',
  `trade_no` varchar(100) NOT NULL DEFAULT '' COMMENT '三方平台订单号',
  `status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '状态',
  `addtime` int(11) NOT NULL DEFAULT '0' COMMENT '添加时间',
  `type` tinyint(1) NOT NULL DEFAULT '1' COMMENT '支付类型 1 支付宝 2 微信 3 ios 4 微信小程序 5 paypal 6 braintree_paypal',
  `ambient` tinyint(1) NOT NULL DEFAULT '0' COMMENT '支付环境',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Records of cmf_user_charge
-- ----------------------------

-- ----------------------------
-- Table structure for `cmf_user_charge_admin`
-- ----------------------------
DROP TABLE IF EXISTS `cmf_user_charge_admin`;
CREATE TABLE `cmf_user_charge_admin` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `touid` int(11) NOT NULL DEFAULT '0' COMMENT '充值对象ID',
  `coin` int(20) NOT NULL DEFAULT '0' COMMENT '钻石数',
  `addtime` int(11) NOT NULL DEFAULT '0' COMMENT '添加时间',
  `admin` varchar(255) NOT NULL DEFAULT '' COMMENT '管理员',
  `ip` varchar(255) NOT NULL DEFAULT '' COMMENT 'IP',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 ROW_FORMAT=COMPACT;

-- ----------------------------
-- Records of cmf_user_charge_admin
-- ----------------------------

-- ----------------------------
-- Table structure for `cmf_user_coinrecord`
-- ----------------------------
DROP TABLE IF EXISTS `cmf_user_coinrecord`;
CREATE TABLE `cmf_user_coinrecord` (
  `id` int(12) NOT NULL AUTO_INCREMENT,
  `type` varchar(20) NOT NULL DEFAULT '' COMMENT '收支类型 expend支出; income收入',
  `action` varchar(20) NOT NULL DEFAULT '' COMMENT '收支行为 buyvip 购买vip; watchvideo 观看收费视频; uppop 视频上热门 pop_refund 上热门视频指定时间内没达到播放量退费 sendgift 直播间送礼物 video_sendgift 视频送礼物; yurntable_game 转盘游戏; turntable_wins转盘中奖; open_guard开通守护; daily_tasks每日任务; reg_reward注册奖励',
  `uid` int(11) NOT NULL DEFAULT '0' COMMENT '用户ID',
  `touid` int(11) NOT NULL DEFAULT '0' COMMENT '对方ID',
  `giftid` int(11) NOT NULL DEFAULT '0' COMMENT '行为对应ID',
  `giftcount` int(11) NOT NULL DEFAULT '0' COMMENT '数量',
  `totalcoin` int(11) NOT NULL DEFAULT '0' COMMENT '总价',
  `showid` int(12) NOT NULL DEFAULT '0' COMMENT '直播标识',
  `addtime` int(12) NOT NULL DEFAULT '0' COMMENT '添加时间',
  `videoid` int(11) NOT NULL DEFAULT '0' COMMENT '视频id',
  PRIMARY KEY (`id`) USING BTREE,
  KEY `action_uid_addtime` (`action`,`uid`,`addtime`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Records of cmf_user_coinrecord
-- ----------------------------

-- ----------------------------
-- Table structure for `cmf_user_daily_tasks`
-- ----------------------------
DROP TABLE IF EXISTS `cmf_user_daily_tasks`;
CREATE TABLE `cmf_user_daily_tasks` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(12) NOT NULL DEFAULT '0' COMMENT '用户uid',
  `type` tinyint(1) NOT NULL DEFAULT '1' COMMENT '任务类型 1观看直播, 2观看视频, 3直播奖励, 4打赏奖励, 5分享奖励',
  `target` int(11) NOT NULL DEFAULT '0' COMMENT '目标',
  `schedule` int(11) NOT NULL DEFAULT '0' COMMENT '当前进度',
  `reward` int(5) NOT NULL DEFAULT '0' COMMENT '奖励钻石数量',
  `addtime` int(12) NOT NULL DEFAULT '0' COMMENT '生成时间',
  `uptime` int(12) NOT NULL DEFAULT '0' COMMENT '更新时间',
  `state` tinyint(1) DEFAULT '0' COMMENT '状态 0未达成  1可领取  2已领取',
  PRIMARY KEY (`id`) USING BTREE,
  KEY `uid` (`uid`,`type`) USING BTREE,
  KEY `uid_2` (`uid`) USING BTREE,
  KEY `uid_3` (`uid`,`type`,`addtime`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 ROW_FORMAT=DYNAMIC COMMENT='每日任务';

-- ----------------------------
-- Records of cmf_user_daily_tasks
-- ----------------------------

-- ----------------------------
-- Table structure for `cmf_user_goods_collect`
-- ----------------------------
DROP TABLE IF EXISTS `cmf_user_goods_collect`;
CREATE TABLE `cmf_user_goods_collect` (
  `uid` int(12) NOT NULL DEFAULT '0' COMMENT '用户ID',
  `goodsid` int(12) NOT NULL COMMENT '商品id',
  `goodsuid` int(12) NOT NULL COMMENT '商品所有者用户id',
  `addtime` int(12) NOT NULL COMMENT '时间'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Records of cmf_user_goods_collect
-- ----------------------------

-- ----------------------------
-- Table structure for `cmf_user_goods_visit`
-- ----------------------------
DROP TABLE IF EXISTS `cmf_user_goods_visit`;
CREATE TABLE `cmf_user_goods_visit` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '自增id',
  `uid` bigint(11) NOT NULL DEFAULT '0' COMMENT '用户id',
  `goodsid` int(11) NOT NULL DEFAULT '0' COMMENT '商品id',
  `addtime` int(11) NOT NULL DEFAULT '0' COMMENT '添加时间',
  `time_format` varchar(50) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Records of cmf_user_goods_visit
-- ----------------------------

-- ----------------------------
-- Table structure for `cmf_user_live`
-- ----------------------------
DROP TABLE IF EXISTS `cmf_user_live`;
CREATE TABLE `cmf_user_live` (
  `uid` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '用户ID',
  `showid` int(11) NOT NULL DEFAULT '0' COMMENT '直播标识',
  `islive` tinyint(1) NOT NULL DEFAULT '0' COMMENT '直播状态',
  `starttime` int(11) NOT NULL DEFAULT '0' COMMENT '开播时间',
  `title` varchar(255) NOT NULL DEFAULT '' COMMENT '标题',
  `province` varchar(255) NOT NULL DEFAULT '' COMMENT '省份',
  `city` varchar(255) NOT NULL DEFAULT '' COMMENT '城市',
  `stream` varchar(255) NOT NULL DEFAULT '' COMMENT '流名',
  `thumb` varchar(255) NOT NULL DEFAULT '' COMMENT '封面图',
  `pull` varchar(255) NOT NULL DEFAULT '' COMMENT '播流地址',
  `isvideo` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否假视频',
  `deviceinfo` varchar(255) NOT NULL DEFAULT '' COMMENT '设备信息',
  `isoff` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否断流 0 否 1 是',
  `offtime` int(11) NOT NULL DEFAULT '0' COMMENT '断流时间',
  `isshop` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否开启店铺',
  `ismic` tinyint(1) NOT NULL DEFAULT '0' COMMENT '连麦开关，0是关，1是开',
  `pkuid` int(11) NOT NULL DEFAULT '0' COMMENT 'PK对方ID',
  `pkstream` varchar(255) NOT NULL DEFAULT '' COMMENT 'pk对方的流名',
  `isrecommend` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否为推荐主播（user表操作，该处同步）0 否 1 是',
  `recommend_time` int(11) NOT NULL DEFAULT '0' COMMENT '推荐时间',
  `liveclassid` int(11) NOT NULL DEFAULT '0' COMMENT '直播分类id',
  PRIMARY KEY (`uid`) USING BTREE,
  KEY `index_islive_starttime` (`islive`,`starttime`) USING BTREE,
  KEY `index_uid_stream` (`uid`,`stream`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Records of cmf_user_live
-- ----------------------------

-- ----------------------------
-- Table structure for `cmf_user_livemanager`
-- ----------------------------
DROP TABLE IF EXISTS `cmf_user_livemanager`;
CREATE TABLE `cmf_user_livemanager` (
  `uid` int(12) NOT NULL DEFAULT '0' COMMENT '用户ID',
  `liveuid` int(12) NOT NULL DEFAULT '0' COMMENT '主播ID',
  KEY `uid_touid_index` (`liveuid`,`uid`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Records of cmf_user_livemanager
-- ----------------------------

-- ----------------------------
-- Table structure for `cmf_user_liverecord`
-- ----------------------------
DROP TABLE IF EXISTS `cmf_user_liverecord`;
CREATE TABLE `cmf_user_liverecord` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(11) NOT NULL DEFAULT '0' COMMENT '用户ID',
  `showid` int(11) NOT NULL DEFAULT '0' COMMENT '直播标识',
  `nums` int(11) NOT NULL DEFAULT '0' COMMENT '关播时人数',
  `starttime` int(11) NOT NULL DEFAULT '0' COMMENT '开始时间',
  `endtime` int(11) NOT NULL DEFAULT '0' COMMENT '结束时间',
  `title` varchar(255) NOT NULL DEFAULT '' COMMENT '标题',
  `province` varchar(255) NOT NULL DEFAULT '' COMMENT '省份',
  `city` varchar(255) NOT NULL DEFAULT '' COMMENT '城市',
  `stream` varchar(255) NOT NULL DEFAULT '' COMMENT '流名',
  `thumb` varchar(255) NOT NULL DEFAULT '' COMMENT '封面图',
  `votes` varchar(255) NOT NULL DEFAULT '0' COMMENT '本次直播收益',
  `time` varchar(255) NOT NULL DEFAULT '' COMMENT '格式日期',
  `video_url` varchar(255) NOT NULL DEFAULT '' COMMENT '回放地址',
  PRIMARY KEY (`id`) USING BTREE,
  KEY `index_uid_start` (`uid`,`starttime`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Records of cmf_user_liverecord
-- ----------------------------

-- ----------------------------
-- Table structure for `cmf_user_live_ban`
-- ----------------------------
DROP TABLE IF EXISTS `cmf_user_live_ban`;
CREATE TABLE `cmf_user_live_ban` (
  `uid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '用户ID',
  `superid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '超管ID',
  `addtime` int(11) NOT NULL DEFAULT '0' COMMENT '添加时间',
  PRIMARY KEY (`uid`) USING BTREE,
  KEY `uid_index` (`uid`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Records of cmf_user_live_ban
-- ----------------------------

-- ----------------------------
-- Table structure for `cmf_user_live_kick`
-- ----------------------------
DROP TABLE IF EXISTS `cmf_user_live_kick`;
CREATE TABLE `cmf_user_live_kick` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '用户ID',
  `liveuid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '主播ID',
  `addtime` int(11) NOT NULL DEFAULT '0' COMMENT '添加时间',
  `actionid` int(11) NOT NULL DEFAULT '0' COMMENT '操作用户ID',
  PRIMARY KEY (`id`) USING BTREE,
  KEY `uid_liveuid_index` (`uid`,`liveuid`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Records of cmf_user_live_kick
-- ----------------------------

-- ----------------------------
-- Table structure for `cmf_user_live_report`
-- ----------------------------
DROP TABLE IF EXISTS `cmf_user_live_report`;
CREATE TABLE `cmf_user_live_report` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(11) NOT NULL DEFAULT '0' COMMENT '用户id',
  `touid` int(11) NOT NULL DEFAULT '0' COMMENT '被举报用户id',
  `content` varchar(255) NOT NULL DEFAULT '' COMMENT '举报内容',
  `status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '处理状态 0 未处理 1已标记处理',
  `addtime` int(11) NOT NULL DEFAULT '0' COMMENT '添加时间',
  `updatetime` int(11) NOT NULL DEFAULT '0' COMMENT '修改时间',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Records of cmf_user_live_report
-- ----------------------------

-- ----------------------------
-- Table structure for `cmf_user_live_report_classify`
-- ----------------------------
DROP TABLE IF EXISTS `cmf_user_live_report_classify`;
CREATE TABLE `cmf_user_live_report_classify` (
  `id` int(12) NOT NULL AUTO_INCREMENT COMMENT '自增id',
  `title` varchar(255) NOT NULL DEFAULT '' COMMENT '分类名称',
  `orderno` int(12) NOT NULL DEFAULT '0' COMMENT '排序号',
  `isdel` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否删除',
  `addtime` int(12) NOT NULL DEFAULT '0' COMMENT '添加时间',
  `updatetime` int(12) NOT NULL DEFAULT '0' COMMENT '修改时间',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=24 DEFAULT CHARSET=utf8mb4 ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Records of cmf_user_live_report_classify
-- ----------------------------
INSERT INTO `cmf_user_live_report_classify` VALUES ('1', '发布垃圾广告、售卖假货等', '4', '0', '1528448412', '0');
INSERT INTO `cmf_user_live_report_classify` VALUES ('2', '色情低俗', '9', '0', '1528507131', '1532673373');
INSERT INTO `cmf_user_live_report_classify` VALUES ('3', '政治敏感', '12', '0', '1528699743', '0');
INSERT INTO `cmf_user_live_report_classify` VALUES ('4', '违法犯罪', '1', '0', '1530090922', '1603877028');
INSERT INTO `cmf_user_live_report_classify` VALUES ('5', '冒充官员', '14', '0', '1531358655', '0');
INSERT INTO `cmf_user_live_report_classify` VALUES ('6', '盗用TA人作品', '2', '0', '1531358676', '1589354433');
INSERT INTO `cmf_user_live_report_classify` VALUES ('7', '疑似自我伤害', '3', '0', '1531358690', '0');
INSERT INTO `cmf_user_live_report_classify` VALUES ('17', '侵犯隐私', '5', '0', '1532673227', '0');
INSERT INTO `cmf_user_live_report_classify` VALUES ('18', '造谣传谣、涉嫌欺诈', '11', '0', '1532673245', '0');
INSERT INTO `cmf_user_live_report_classify` VALUES ('19', '侮辱谩骂', '6', '0', '1532673268', '0');
INSERT INTO `cmf_user_live_report_classify` VALUES ('20', '头像、昵称、签名违规', '7', '0', '1532673300', '0');
INSERT INTO `cmf_user_live_report_classify` VALUES ('21', '其他', '8', '0', '1533028050', '0');
INSERT INTO `cmf_user_live_report_classify` VALUES ('22', '冒犯官员', '10', '0', '1536300202', '0');
INSERT INTO `cmf_user_live_report_classify` VALUES ('23', '违法直播', '13', '0', '1536300272', '1536300318');

-- ----------------------------
-- Table structure for `cmf_user_live_shut`
-- ----------------------------
DROP TABLE IF EXISTS `cmf_user_live_shut`;
CREATE TABLE `cmf_user_live_shut` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '用户ID',
  `liveuid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '主播ID',
  `addtime` int(11) NOT NULL DEFAULT '0' COMMENT '添加时间',
  `showid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '禁言类型，0永久 1 本场',
  `actionid` int(11) NOT NULL DEFAULT '0' COMMENT '操作者ID',
  PRIMARY KEY (`id`) USING BTREE,
  KEY `uid_liveuid_index` (`uid`,`liveuid`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Records of cmf_user_live_shut
-- ----------------------------

-- ----------------------------
-- Table structure for `cmf_user_login_attempt`
-- ----------------------------
DROP TABLE IF EXISTS `cmf_user_login_attempt`;
CREATE TABLE `cmf_user_login_attempt` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `login_attempts` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '尝试次数',
  `attempt_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '尝试登录时间',
  `locked_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '锁定时间',
  `ip` varchar(15) NOT NULL DEFAULT '' COMMENT '用户 ip',
  `account` varchar(100) NOT NULL DEFAULT '' COMMENT '用户账号,手机号,邮箱或用户名',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 ROW_FORMAT=COMPACT COMMENT='用户登录尝试表';

-- ----------------------------
-- Records of cmf_user_login_attempt
-- ----------------------------

-- ----------------------------
-- Table structure for `cmf_user_music`
-- ----------------------------
DROP TABLE IF EXISTS `cmf_user_music`;
CREATE TABLE `cmf_user_music` (
  `id` int(12) NOT NULL AUTO_INCREMENT COMMENT '自增id',
  `title` varchar(255) DEFAULT '' COMMENT '音乐名称',
  `author` varchar(255) DEFAULT '' COMMENT '演唱者',
  `uploader` int(255) DEFAULT '0' COMMENT '上传者ID',
  `upload_type` tinyint(1) DEFAULT '0' COMMENT '上传类型  1管理员上传 2 用户上传',
  `img_url` varchar(255) DEFAULT '' COMMENT '封面地址',
  `length` varchar(255) DEFAULT '' COMMENT '音乐长度',
  `file_url` varchar(255) DEFAULT '' COMMENT '文件地址',
  `use_nums` int(12) DEFAULT '0' COMMENT '被使用次数',
  `isdel` tinyint(1) DEFAULT '0' COMMENT '是否被删除 0否 1是',
  `addtime` int(12) DEFAULT '0' COMMENT '添加时间',
  `updatetime` int(12) DEFAULT '0' COMMENT '更新时间',
  `classify_id` int(12) DEFAULT '0' COMMENT '音乐分类ID',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 ROW_FORMAT=COMPACT;

-- ----------------------------
-- Records of cmf_user_music
-- ----------------------------
INSERT INTO `cmf_user_music` VALUES ('1', 'Panama', 'Matteo', '1', '1', 'music_thumb_1.jpg%@%cloudtype=1', '00:31', 'music_1.mp3%@%cloudtype=1', '121', '0', '1528535261', '1536733965', '1');
INSERT INTO `cmf_user_music` VALUES ('3', 'California (On My Mind)', 'Stewart Mac', '1', '1', 'music_thumb_3.jpg%@%cloudtype=1', '00:29', 'music_3.mp3%@%cloudtype=1', '281', '0', '1529747678', '1572228456', '1');
INSERT INTO `cmf_user_music` VALUES ('4', '最美的期待', '周笔畅', '1', '1', 'music_thumb_4.png%@%cloudtype=1', '00:22', 'music_4.mp3%@%cloudtype=1', '105', '0', '1530090995', '1592876572', '4');
INSERT INTO `cmf_user_music` VALUES ('5', 'Friendshipss', 'Pascal Letoublon', '1', '1', 'music_thumb_5.jpg%@%cloudtype=1', '00:40', 'music_5.mp3%@%cloudtype=1', '1027', '0', '1530095432', '1604904095', '2');
INSERT INTO `cmf_user_music` VALUES ('6', 'Time', 'MKJ', '1', '1', 'music_thumb_6.png%@%cloudtype=1', '00:17', 'music_6.mp3%@%cloudtype=1', '60', '0', '1530096156', '1571628745', '1');
INSERT INTO `cmf_user_music` VALUES ('7', 'Seve', 'Tez Cadey', '1', '1', 'music_thumb_7.png%@%cloudtype=1', '00:31', 'music_7.mp3%@%cloudtype=1', '187', '0', '1530096728', '1571628727', '1');
INSERT INTO `cmf_user_music` VALUES ('8', 'Ce Frumoasa E Iubirea', 'Giulia Anghelescu', '1', '1', 'music_thumb_8.png%@%cloudtype=1', '00:49', 'music_8.mp3%@%cloudtype=1', '242', '0', '1531382523', '1571628720', '1');
INSERT INTO `cmf_user_music` VALUES ('9', '夜空的寂静', '赵海洋', '1', '1', 'music_thumb_9.jpg%@%cloudtype=1', '00:39', 'music_9.mp3%@%cloudtype=1', '125', '0', '1531383448', '1571628740', '14');
INSERT INTO `cmf_user_music` VALUES ('10', '清晨的美好', '张宇桦', '1', '1', 'music_thumb_10.jpg%@%cloudtype=1', '00:37', 'music_10.mp3%@%cloudtype=1', '237', '0', '1531384116', '1571628724', '14');
INSERT INTO `cmf_user_music` VALUES ('11', 'Because of You', 'Kelly Clarkson', '1', '1', 'music_thumb_11.jpg%@%cloudtype=1', '03:40', 'music_11.mp3%@%cloudtype=1', '127', '0', '1535004946', '1571628736', '1');
INSERT INTO `cmf_user_music` VALUES ('12', '你笑起来真好看', '李昕融', '1', '1', 'music_thumb_12.jpg%@%cloudtype=1', '00:21', 'music_12.mp3%@%cloudtype=1', '229', '0', '1578902365', '0', '2');

-- ----------------------------
-- Table structure for `cmf_user_music_classify`
-- ----------------------------
DROP TABLE IF EXISTS `cmf_user_music_classify`;
CREATE TABLE `cmf_user_music_classify` (
  `id` int(12) NOT NULL AUTO_INCREMENT COMMENT '自增id',
  `title` varchar(255) DEFAULT '' COMMENT '分类名称',
  `orderno` int(12) DEFAULT '0' COMMENT '排序号',
  `isdel` tinyint(1) DEFAULT '0' COMMENT '是否删除',
  `addtime` int(12) DEFAULT '0' COMMENT '添加时间',
  `updatetime` int(12) DEFAULT '0' COMMENT '修改时间',
  `img_url` varchar(255) DEFAULT '' COMMENT '分类图标地址',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8mb4 ROW_FORMAT=COMPACT;

-- ----------------------------
-- Records of cmf_user_music_classify
-- ----------------------------
INSERT INTO `cmf_user_music_classify` VALUES ('1', '欧美', '7', '0', '1528448412', '0', 'music_class_1.png%@%cloudtype=1');
INSERT INTO `cmf_user_music_classify` VALUES ('2', '热歌', '3', '0', '1528507131', '0', 'music_class_2.png%@%cloudtype=1');
INSERT INTO `cmf_user_music_classify` VALUES ('3', '新歌', '2', '0', '1528699743', '0', 'music_class_3.png%@%cloudtype=1');
INSERT INTO `cmf_user_music_classify` VALUES ('4', '经典', '3', '0', '1530090922', '0', 'music_class_4.png%@%cloudtype=1');
INSERT INTO `cmf_user_music_classify` VALUES ('5', '流行', '4', '0', '1531358655', '0', 'music_class_5.png%@%cloudtype=1');
INSERT INTO `cmf_user_music_classify` VALUES ('6', '国风', '5', '0', '1531358676', '0', 'music_class_6.png%@%cloudtype=1');
INSERT INTO `cmf_user_music_classify` VALUES ('7', '电音', '6', '0', '1531358690', '0', 'music_class_7.png%@%cloudtype=1');
INSERT INTO `cmf_user_music_classify` VALUES ('8', '搞笑', '8', '0', '1531358704', '0', 'music_class_8.png%@%cloudtype=1');
INSERT INTO `cmf_user_music_classify` VALUES ('9', '舞蹈', '9', '0', '1531358719', '0', 'music_class_9.png%@%cloudtype=1');
INSERT INTO `cmf_user_music_classify` VALUES ('10', '运动', '10', '0', '1531358731', '0', 'music_class_10.png%@%cloudtype=1');
INSERT INTO `cmf_user_music_classify` VALUES ('11', '说唱', '11', '0', '1531358744', '0', 'music_class_11.png%@%cloudtype=1');
INSERT INTO `cmf_user_music_classify` VALUES ('12', '影视', '12', '0', '1531358757', '0', 'music_class_12.png%@%cloudtype=1');
INSERT INTO `cmf_user_music_classify` VALUES ('13', '游戏', '13', '0', '1531358769', '0', 'music_class_13.png%@%cloudtype=1');
INSERT INTO `cmf_user_music_classify` VALUES ('14', '纯音乐', '14', '0', '1531358782', '0', 'music_class_14.png%@%cloudtype=1');
INSERT INTO `cmf_user_music_classify` VALUES ('15', '原创', '15', '0', '1531358800', '0', 'music_class_15.png%@%cloudtype=1');

-- ----------------------------
-- Table structure for `cmf_user_music_collection`
-- ----------------------------
DROP TABLE IF EXISTS `cmf_user_music_collection`;
CREATE TABLE `cmf_user_music_collection` (
  `id` int(12) NOT NULL AUTO_INCREMENT COMMENT '自增id',
  `uid` int(12) DEFAULT '0' COMMENT '用户id',
  `music_id` int(12) DEFAULT '0' COMMENT '音乐id',
  `addtime` int(12) DEFAULT '0' COMMENT '添加时间',
  `updatetime` int(12) DEFAULT '0' COMMENT '更新时间',
  `status` tinyint(1) DEFAULT '1' COMMENT '音乐收藏状态 1收藏 0 取消收藏',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 ROW_FORMAT=COMPACT;

-- ----------------------------
-- Records of cmf_user_music_collection
-- ----------------------------

-- ----------------------------
-- Table structure for `cmf_user_pushid`
-- ----------------------------
DROP TABLE IF EXISTS `cmf_user_pushid`;
CREATE TABLE `cmf_user_pushid` (
  `uid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '用户ID',
  `pushid` varchar(255) NOT NULL DEFAULT '' COMMENT '用户对应极光registration_id',
  PRIMARY KEY (`uid`) USING BTREE,
  KEY `uid` (`uid`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Records of cmf_user_pushid
-- ----------------------------

-- ----------------------------
-- Table structure for `cmf_user_report`
-- ----------------------------
DROP TABLE IF EXISTS `cmf_user_report`;
CREATE TABLE `cmf_user_report` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(11) DEFAULT '0',
  `touid` int(11) DEFAULT '0',
  `content` varchar(255) DEFAULT '',
  `status` tinyint(1) DEFAULT '0',
  `addtime` int(11) DEFAULT '0',
  `uptime` int(11) DEFAULT '0',
  `thumb` varchar(255) CHARACTER SET utf8 DEFAULT '' COMMENT '图片地址',
  `contact_msg` varchar(255) DEFAULT '' COMMENT '联系方式',
  `classify` varchar(255) CHARACTER SET utf8 DEFAULT '' COMMENT '举报类型',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 ROW_FORMAT=COMPACT;

-- ----------------------------
-- Records of cmf_user_report
-- ----------------------------

-- ----------------------------
-- Table structure for `cmf_user_report_classify`
-- ----------------------------
DROP TABLE IF EXISTS `cmf_user_report_classify`;
CREATE TABLE `cmf_user_report_classify` (
  `id` int(12) NOT NULL AUTO_INCREMENT COMMENT '自增id',
  `title` varchar(255) DEFAULT '' COMMENT '分类名称',
  `orderno` int(12) DEFAULT '0' COMMENT '排序号',
  `isdel` tinyint(1) DEFAULT '0' COMMENT '是否删除',
  `addtime` int(12) DEFAULT '0' COMMENT '添加时间',
  `updatetime` int(12) DEFAULT '0' COMMENT '修改时间',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=24 DEFAULT CHARSET=utf8mb4 ROW_FORMAT=COMPACT;

-- ----------------------------
-- Records of cmf_user_report_classify
-- ----------------------------
INSERT INTO `cmf_user_report_classify` VALUES ('1', '发布垃圾广告、售卖假货', '4', '0', '1528448412', '1589250866');
INSERT INTO `cmf_user_report_classify` VALUES ('2', '色情低俗', '9', '0', '1528507131', '1532673373');
INSERT INTO `cmf_user_report_classify` VALUES ('3', '政治敏感', '12', '0', '1528699743', '0');
INSERT INTO `cmf_user_report_classify` VALUES ('4', '违法犯罪', '1', '0', '1530090922', '1600502731');
INSERT INTO `cmf_user_report_classify` VALUES ('5', '冒充官员', '14', '0', '1531358655', '0');
INSERT INTO `cmf_user_report_classify` VALUES ('6', '盗用TA人作品', '2', '0', '1531358676', '1583976058');
INSERT INTO `cmf_user_report_classify` VALUES ('7', '疑似自我伤害', '3', '0', '1531358690', '0');
INSERT INTO `cmf_user_report_classify` VALUES ('17', '侵犯隐私', '5', '0', '1532673227', '0');
INSERT INTO `cmf_user_report_classify` VALUES ('18', '造谣传谣、涉嫌欺诈', '11', '0', '1532673245', '0');
INSERT INTO `cmf_user_report_classify` VALUES ('19', '侮辱谩骂', '6', '0', '1532673268', '0');
INSERT INTO `cmf_user_report_classify` VALUES ('20', '头像、昵称、签名违规', '7', '0', '1532673300', '0');
INSERT INTO `cmf_user_report_classify` VALUES ('21', '其他', '8', '0', '1533028050', '0');
INSERT INTO `cmf_user_report_classify` VALUES ('22', '冒犯官员', '10', '0', '1536300202', '0');
INSERT INTO `cmf_user_report_classify` VALUES ('23', '违法直播', '13', '0', '1536300272', '1536300318');

-- ----------------------------
-- Table structure for `cmf_user_super`
-- ----------------------------
DROP TABLE IF EXISTS `cmf_user_super`;
CREATE TABLE `cmf_user_super` (
  `uid` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '用户id',
  `addtime` int(11) NOT NULL DEFAULT '0' COMMENT '添加时间',
  PRIMARY KEY (`uid`) USING BTREE,
  KEY `uid` (`uid`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Records of cmf_user_super
-- ----------------------------

-- ----------------------------
-- Table structure for `cmf_user_token`
-- ----------------------------
DROP TABLE IF EXISTS `cmf_user_token`;
CREATE TABLE `cmf_user_token` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) NOT NULL DEFAULT '0' COMMENT '用户id',
  `expire_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT ' 过期时间',
  `create_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `token` varchar(64) NOT NULL DEFAULT '' COMMENT 'token',
  `device_type` varchar(10) NOT NULL DEFAULT '' COMMENT '设备类型;mobile,android,iphone,ipad,web,pc,mac,wxapp',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 ROW_FORMAT=COMPACT COMMENT='用户客户端登录 token 表';

-- ----------------------------
-- Records of cmf_user_token
-- ----------------------------

-- ----------------------------
-- Table structure for `cmf_user_video`
-- ----------------------------
DROP TABLE IF EXISTS `cmf_user_video`;
CREATE TABLE `cmf_user_video` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(11) NOT NULL DEFAULT '0' COMMENT '用户ID',
  `title` varchar(255) NOT NULL DEFAULT '' COMMENT '标题',
  `thumb` varchar(255) NOT NULL DEFAULT '' COMMENT '封面图片',
  `thumb_s` varchar(255) NOT NULL DEFAULT '' COMMENT '封面小图',
  `href` varchar(255) NOT NULL DEFAULT '' COMMENT '视频地址',
  `href_w` varchar(255) NOT NULL DEFAULT '' COMMENT '水印视频',
  `likes` int(11) NOT NULL DEFAULT '0' COMMENT '点赞数',
  `views` int(11) NOT NULL DEFAULT '1' COMMENT '浏览数（涉及到推荐排序机制，所以默认为1）',
  `comments` int(11) NOT NULL DEFAULT '0' COMMENT '评论数',
  `steps` int(11) NOT NULL DEFAULT '0' COMMENT '踩总数',
  `shares` int(11) NOT NULL DEFAULT '0' COMMENT '分享数量',
  `addtime` int(11) NOT NULL DEFAULT '0' COMMENT '发布时间',
  `lat` varchar(255) NOT NULL DEFAULT '' COMMENT '维度',
  `lng` varchar(255) NOT NULL DEFAULT '' COMMENT '经度',
  `city` varchar(255) NOT NULL DEFAULT '' COMMENT '城市',
  `isdel` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否删除 1删除（下架）0不下架',
  `status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '视频状态 0未审核 1通过 2拒绝',
  `music_id` int(12) NOT NULL DEFAULT '0' COMMENT '背景音乐ID',
  `xiajia_reason` varchar(255) NOT NULL DEFAULT '' COMMENT '下架原因',
  `show_val` int(12) NOT NULL DEFAULT '0' COMMENT '曝光值',
  `nopass_time` int(12) NOT NULL DEFAULT '0' COMMENT '审核不通过时间（第一次审核不通过时更改此值，用于判断是否发送极光IM）',
  `watch_ok` int(12) NOT NULL DEFAULT '1' COMMENT '视频完整看完次数(涉及到推荐排序机制，所以默认为1)',
  `is_ad` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否为广告视频 0 否 1 是',
  `ad_endtime` bigint(20) NOT NULL DEFAULT '0' COMMENT '广告显示到期时间',
  `ad_url` varchar(255) NOT NULL DEFAULT '' COMMENT '广告外链',
  `orderno` int(12) NOT NULL DEFAULT '0' COMMENT '权重值，数字越大越靠前',
  `labelid` int(11) NOT NULL DEFAULT '0' COMMENT '标签ID',
  `isgoods` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否商品视频，0否1是',
  `ispay` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否支付，0否1是',
  `p_nums` int(11) NOT NULL DEFAULT '0' COMMENT '热门次数',
  `p_expire` int(11) NOT NULL DEFAULT '0' COMMENT '热门到期时间',
  `p_add` int(11) NOT NULL DEFAULT '0' COMMENT '热门购买时间',
  `classid` int(11) NOT NULL DEFAULT '0' COMMENT '视频分类id',
  `coin` int(11) NOT NULL DEFAULT '0' COMMENT '需花费钻石数',
  `goodsid` int(11) NOT NULL DEFAULT '0' COMMENT '绑定商品id',
  `anyway` varchar(10) NOT NULL DEFAULT '1.1' COMMENT '横竖屏(封面-高/宽)，大于1表示竖屏,小于1表示横屏',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Records of cmf_user_video
-- ----------------------------

-- ----------------------------
-- Table structure for `cmf_user_video_class`
-- ----------------------------
DROP TABLE IF EXISTS `cmf_user_video_class`;
CREATE TABLE `cmf_user_video_class` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '自增id',
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '分类名称',
  `status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '显示状态 0 不显示 1显示',
  `orderno` int(11) NOT NULL DEFAULT '0' COMMENT '排序号',
  `addtime` int(11) NOT NULL DEFAULT '0' COMMENT '添加时间',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=20 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Records of cmf_user_video_class
-- ----------------------------
INSERT INTO `cmf_user_video_class` VALUES ('3', '旅游攻略', '1', '3', '1573196196');
INSERT INTO `cmf_user_video_class` VALUES ('4', '游戏', '1', '4', '1573197217');
INSERT INTO `cmf_user_video_class` VALUES ('5', '电影推荐', '1', '5', '1573197367');
INSERT INTO `cmf_user_video_class` VALUES ('6', '足球', '1', '6', '1573197375');
INSERT INTO `cmf_user_video_class` VALUES ('7', '美女', '1', '7', '1573197390');
INSERT INTO `cmf_user_video_class` VALUES ('8', '衣服', '1', '8', '1573197407');
INSERT INTO `cmf_user_video_class` VALUES ('10', '美食', '1', '9', '1573197444');
INSERT INTO `cmf_user_video_class` VALUES ('13', '校园', '1', '1', '1576476513');
INSERT INTO `cmf_user_video_class` VALUES ('17', '汽车', '1', '10', '1583978238');
INSERT INTO `cmf_user_video_class` VALUES ('18', '宠物', '1', '2', '1583978327');
INSERT INTO `cmf_user_video_class` VALUES ('19', '购物', '0', '0', '1638974498');

-- ----------------------------
-- Table structure for `cmf_user_video_comments`
-- ----------------------------
DROP TABLE IF EXISTS `cmf_user_video_comments`;
CREATE TABLE `cmf_user_video_comments` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `uid` int(10) NOT NULL DEFAULT '0' COMMENT '用户id',
  `touid` int(10) NOT NULL DEFAULT '0' COMMENT '被评论用户id',
  `videoid` int(10) NOT NULL DEFAULT '0' COMMENT '视频id',
  `commentid` int(10) NOT NULL DEFAULT '0' COMMENT '评论信息id',
  `parentid` int(10) NOT NULL DEFAULT '0' COMMENT '上级id',
  `content` text COMMENT '评论内容',
  `likes` int(11) NOT NULL DEFAULT '0' COMMENT '点赞数',
  `addtime` int(10) NOT NULL DEFAULT '0' COMMENT '添加时间',
  `at_info` varchar(255) NOT NULL DEFAULT '' COMMENT '评论时被@用户的信息（json串）',
  `type` tinyint(1) NOT NULL DEFAULT '0' COMMENT '类型，0文字1语音',
  `voice` varchar(255) NOT NULL DEFAULT '' COMMENT '语音链接',
  `length` int(11) NOT NULL DEFAULT '0' COMMENT '语音时长',
  PRIMARY KEY (`id`) USING BTREE,
  KEY `id_index` (`id`) USING BTREE,
  KEY `uid_touid_index` (`uid`,`touid`) USING BTREE,
  KEY `videoid_index` (`videoid`) USING BTREE,
  KEY `commentid_index` (`commentid`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Records of cmf_user_video_comments
-- ----------------------------

-- ----------------------------
-- Table structure for `cmf_user_video_comments_at_messages`
-- ----------------------------
DROP TABLE IF EXISTS `cmf_user_video_comments_at_messages`;
CREATE TABLE `cmf_user_video_comments_at_messages` (
  `id` int(12) NOT NULL AUTO_INCREMENT COMMENT '自增ID',
  `uid` int(12) NOT NULL DEFAULT '0' COMMENT '用户id',
  `videoid` int(12) NOT NULL DEFAULT '0' COMMENT '视频id',
  `touid` int(12) NOT NULL DEFAULT '0' COMMENT '被@的用户id',
  `addtime` int(12) NOT NULL DEFAULT '0',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '是否显示 0否 1 是  （根据视频是否下架决定）',
  `type` tinyint(1) NOT NULL DEFAULT '0' COMMENT '消息类型 0视频评论@用户 1 帮他人视频上热门  默认0',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 ROW_FORMAT=COMPACT;

-- ----------------------------
-- Records of cmf_user_video_comments_at_messages
-- ----------------------------

-- ----------------------------
-- Table structure for `cmf_user_video_comments_like`
-- ----------------------------
DROP TABLE IF EXISTS `cmf_user_video_comments_like`;
CREATE TABLE `cmf_user_video_comments_like` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `uid` int(10) DEFAULT '0',
  `commentid` int(10) DEFAULT '0',
  `addtime` int(10) DEFAULT '0',
  `touid` int(12) DEFAULT '0' COMMENT '被喜欢的评论者id',
  `videoid` int(12) DEFAULT '0' COMMENT '评论所属视频id',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

-- ----------------------------
-- Records of cmf_user_video_comments_like
-- ----------------------------

-- ----------------------------
-- Table structure for `cmf_user_video_comments_messages`
-- ----------------------------
DROP TABLE IF EXISTS `cmf_user_video_comments_messages`;
CREATE TABLE `cmf_user_video_comments_messages` (
  `id` int(12) NOT NULL AUTO_INCREMENT COMMENT '自增ID',
  `uid` int(12) DEFAULT '0' COMMENT '用户id',
  `videoid` int(12) DEFAULT '0' COMMENT '视频id',
  `touid` int(12) DEFAULT '0' COMMENT '被@的用户id',
  `addtime` int(12) DEFAULT '0',
  `content` varchar(255) DEFAULT '' COMMENT '评论内容',
  `status` tinyint(1) DEFAULT '1' COMMENT '是否显示 0否 1 是  （根据视频是否下架决定）',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 ROW_FORMAT=COMPACT;

-- ----------------------------
-- Records of cmf_user_video_comments_messages
-- ----------------------------

-- ----------------------------
-- Table structure for `cmf_user_video_like`
-- ----------------------------
DROP TABLE IF EXISTS `cmf_user_video_like`;
CREATE TABLE `cmf_user_video_like` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `uid` int(10) DEFAULT '0',
  `videoid` int(10) DEFAULT '0',
  `addtime` int(10) DEFAULT '0',
  `status` tinyint(1) DEFAULT '1' COMMENT '视频是否被删除或被拒绝 0被删除或被拒绝 1 正常',
  PRIMARY KEY (`id`) USING BTREE,
  KEY `uid_videoid` (`uid`,`videoid`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

-- ----------------------------
-- Records of cmf_user_video_like
-- ----------------------------

-- ----------------------------
-- Table structure for `cmf_user_video_paylists`
-- ----------------------------
DROP TABLE IF EXISTS `cmf_user_video_paylists`;
CREATE TABLE `cmf_user_video_paylists` (
  `uid` int(11) NOT NULL DEFAULT '0' COMMENT '用户id',
  `video_ids` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '视频id',
  PRIMARY KEY (`uid`) USING BTREE,
  KEY `uid` (`uid`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Records of cmf_user_video_paylists
-- ----------------------------

-- ----------------------------
-- Table structure for `cmf_user_video_report`
-- ----------------------------
DROP TABLE IF EXISTS `cmf_user_video_report`;
CREATE TABLE `cmf_user_video_report` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(11) DEFAULT '0',
  `touid` int(11) DEFAULT '0',
  `videoid` int(11) DEFAULT '0' COMMENT '视频ID',
  `content` varchar(255) DEFAULT '',
  `status` tinyint(1) DEFAULT '0' COMMENT '0处理中 1已处理  2审核失败',
  `addtime` int(11) DEFAULT '0',
  `uptime` int(11) DEFAULT '0',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 ROW_FORMAT=COMPACT;

-- ----------------------------
-- Records of cmf_user_video_report
-- ----------------------------

-- ----------------------------
-- Table structure for `cmf_user_video_report_classify`
-- ----------------------------
DROP TABLE IF EXISTS `cmf_user_video_report_classify`;
CREATE TABLE `cmf_user_video_report_classify` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `orderno` int(10) DEFAULT '0' COMMENT '排序',
  `name` varchar(255) CHARACTER SET utf8 DEFAULT '' COMMENT '举报类型名称',
  `addtime` int(10) DEFAULT '0' COMMENT '添加时间',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 ROW_FORMAT=COMPACT;

-- ----------------------------
-- Records of cmf_user_video_report_classify
-- ----------------------------
INSERT INTO `cmf_user_video_report_classify` VALUES ('1', '8', '骗取点击', '1513579351');
INSERT INTO `cmf_user_video_report_classify` VALUES ('2', '2', '低俗色情', '1513579668');
INSERT INTO `cmf_user_video_report_classify` VALUES ('3', '3', '侮辱谩骂', '1513579677');
INSERT INTO `cmf_user_video_report_classify` VALUES ('4', '4', '盗用TA人作品', '1513579689');
INSERT INTO `cmf_user_video_report_classify` VALUES ('5', '5', '引人不适', '1513579700');
INSERT INTO `cmf_user_video_report_classify` VALUES ('10', '6', '任性打抱不平，就爱举报', '1521191977');

-- ----------------------------
-- Table structure for `cmf_user_video_view`
-- ----------------------------
DROP TABLE IF EXISTS `cmf_user_video_view`;
CREATE TABLE `cmf_user_video_view` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `uid` int(10) DEFAULT '0',
  `videoid` int(10) DEFAULT '0',
  `addtime` int(10) DEFAULT '0',
  `status` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

-- ----------------------------
-- Records of cmf_user_video_view
-- ----------------------------

-- ----------------------------
-- Table structure for `cmf_user_video_watchlists`
-- ----------------------------
DROP TABLE IF EXISTS `cmf_user_video_watchlists`;
CREATE TABLE `cmf_user_video_watchlists` (
  `uid` int(255) NOT NULL DEFAULT '0' COMMENT '用户id',
  `video_ids` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '视频id列表',
  PRIMARY KEY (`uid`) USING BTREE,
  KEY `uid` (`uid`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Records of cmf_user_video_watchlists
-- ----------------------------

-- ----------------------------
-- Table structure for `cmf_user_video_watchtime`
-- ----------------------------
DROP TABLE IF EXISTS `cmf_user_video_watchtime`;
CREATE TABLE `cmf_user_video_watchtime` (
  `uid` int(11) NOT NULL DEFAULT '0' COMMENT '用户id',
  `video_watchtime` int(11) NOT NULL DEFAULT '0' COMMENT '视频最新观看时间',
  PRIMARY KEY (`uid`) USING BTREE,
  KEY `uid` (`uid`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Records of cmf_user_video_watchtime
-- ----------------------------

-- ----------------------------
-- Table structure for `cmf_user_vip_charge`
-- ----------------------------
DROP TABLE IF EXISTS `cmf_user_vip_charge`;
CREATE TABLE `cmf_user_vip_charge` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(11) NOT NULL DEFAULT '0' COMMENT '用户ID',
  `touid` int(11) NOT NULL DEFAULT '0' COMMENT '充值对象ID',
  `money` decimal(11,2) NOT NULL DEFAULT '0.00' COMMENT '金额',
  `days` int(11) NOT NULL DEFAULT '0' COMMENT 'vip天数',
  `orderno` varchar(50) NOT NULL DEFAULT '' COMMENT '商家订单号',
  `trade_no` varchar(100) NOT NULL DEFAULT '' COMMENT '三方平台订单号',
  `status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '状态',
  `addtime` int(11) NOT NULL DEFAULT '0' COMMENT '添加时间',
  `type` tinyint(1) NOT NULL DEFAULT '1' COMMENT '支付类型 1 支付宝 2 微信 3 ios 4 余额 5微信小程序 6 paypal 7 braintree_paypal',
  `ambient` tinyint(1) NOT NULL DEFAULT '0' COMMENT '支付环境',
  `coin` int(11) NOT NULL DEFAULT '0' COMMENT '充值vip支付钻石数',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Records of cmf_user_vip_charge
-- ----------------------------

-- ----------------------------
-- Table structure for `cmf_view_reward`
-- ----------------------------
DROP TABLE IF EXISTS `cmf_view_reward`;
CREATE TABLE `cmf_view_reward` (
  `uid` int(11) NOT NULL DEFAULT '0' COMMENT '用户ID',
  `length` int(11) NOT NULL DEFAULT '0' COMMENT '时长',
  `addtime` int(11) NOT NULL DEFAULT '0' COMMENT '时间',
  `status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '状态，0未领取',
  PRIMARY KEY (`uid`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 ROW_FORMAT=COMPACT;

-- ----------------------------
-- Records of cmf_view_reward
-- ----------------------------

-- ----------------------------
-- Table structure for `cmf_vip_charge_rules`
-- ----------------------------
DROP TABLE IF EXISTS `cmf_vip_charge_rules`;
CREATE TABLE `cmf_vip_charge_rules` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '自增id',
  `name` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT 'vip充值规则名称',
  `money` decimal(11,2) NOT NULL DEFAULT '0.00' COMMENT '安卓充值金额',
  `days` int(11) NOT NULL DEFAULT '0' COMMENT '充值vip天数（单位：天）',
  `coin` int(11) NOT NULL DEFAULT '0' COMMENT '充值vip需要花费钻石数',
  `orderno` int(11) NOT NULL DEFAULT '0' COMMENT '排序号',
  `addtime` int(11) NOT NULL DEFAULT '0' COMMENT '添加时间',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Records of cmf_vip_charge_rules
-- ----------------------------
INSERT INTO `cmf_vip_charge_rules` VALUES ('1', '1个月', '0.02', '30', '800', '0', '1572679626');
INSERT INTO `cmf_vip_charge_rules` VALUES ('2', '3个月', '40.00', '90', '2000', '1', '1572679882');
INSERT INTO `cmf_vip_charge_rules` VALUES ('3', '6个月', '80.00', '180', '4000', '3', '1576202378');
INSERT INTO `cmf_vip_charge_rules` VALUES ('4', '12个月', '138.00', '365', '8000', '4', '1576202427');

-- ----------------------------
-- Table structure for `cmf_votes_record`
-- ----------------------------
DROP TABLE IF EXISTS `cmf_votes_record`;
CREATE TABLE `cmf_votes_record` (
  `id` bigint(10) unsigned NOT NULL AUTO_INCREMENT,
  `action` int(11) NOT NULL DEFAULT '0' COMMENT '行为，1是邀请，2是观看视频 3 收费视频收入 4 视频送礼物 5 直播间送礼物 6开通守护',
  `uid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '用户ID',
  `touid` int(11) NOT NULL DEFAULT '0' COMMENT '上级用户ID',
  `votes` bigint(20) NOT NULL DEFAULT '0' COMMENT '金币',
  `touid_votes` bigint(20) NOT NULL DEFAULT '0' COMMENT '上级金币',
  `addtime` int(11) NOT NULL DEFAULT '0' COMMENT '时间',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Records of cmf_votes_record
-- ----------------------------
