SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET FOREIGN_KEY_CHECKS=0;

-- 增加专题开放端口
DROP TABLE IF EXISTS `cmstop_picker`;
CREATE TABLE IF NOT EXISTS `cmstop_port` (
  `portid` smallint(6) NOT NULL AUTO_INCREMENT,
  `port` varchar(20) NOT NULL,
  `name` varchar(100) NOT NULL,
  `url` varchar(255) NOT NULL,
  `authkey` varchar(100) DEFAULT NULL,
  `disabled` tinyint(3) unsigned DEFAULT NULL,
  PRIMARY KEY (`portid`),
  UNIQUE KEY `port` (`port`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- 更新 tag 的长度为 255
ALTER TABLE `cmstop_tag` CHANGE `tag` `tag` VARCHAR(255) NOT NULL;

-- 增加专题组图模块
INSERT INTO `cmstop_widget_engine` (`engineid`, `name`, `description`, `version`, `author`, `updateurl`, `installed`, `disabled`) VALUES
(21, 'picture_group', '组图', '1.0.0', 'cmstop', 'http://update.cmstop.com', NULL, 0);

-- 增加用以标识专题共享模块分类的字段
ALTER TABLE `cmstop_widget` ADD COLUMN `folder` tinyint(1) unsigned NOT NULL DEFAULT 1 after `description`;

-- 投票选项增加链接与图片字段
ALTER TABLE `cmstop_vote_option` ADD `link` VARCHAR( 255 ) NULL AFTER `name` , ADD `thumb` VARCHAR( 255 ) NULL AFTER `link`;

-- 增加开放接口数据表
CREATE TABLE IF NOT EXISTS `cmstop_openaca` (
  `acaid` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `parentid` smallint(5) unsigned DEFAULT NULL,
  `app` varchar(15) NOT NULL,
  `controller` varchar(30) DEFAULT NULL,
  `action` varchar(255) DEFAULT NULL,
  `name` varchar(30) NOT NULL,
  PRIMARY KEY (`acaid`),
  UNIQUE KEY `app` (`app`,`controller`,`action`),
  KEY `parentid` (`parentid`)
) ENGINE=InnoDB AUTO_INCREMENT=20 DEFAULT CHARSET=utf8;

INSERT INTO `cmstop_openaca` VALUES ('1', null, 'system', null, null, '系统');
INSERT INTO `cmstop_openaca` VALUES ('2', '1', 'system', 'category', null, '栏目');
INSERT INTO `cmstop_openaca` VALUES ('3', '2', 'system', 'category', 'ls', '读取栏目列表');
INSERT INTO `cmstop_openaca` VALUES ('4', null, 'page', null, null, '页面');
INSERT INTO `cmstop_openaca` VALUES ('5', '4', 'page', 'page', null, '页面');
INSERT INTO `cmstop_openaca` VALUES ('6', '5', 'page', 'page', 'ls', '读取页面列表');
INSERT INTO `cmstop_openaca` VALUES ('7', '4', 'page', 'section', '', '区块');
INSERT INTO `cmstop_openaca` VALUES ('8', '7', 'page', 'section', 'ls', '读取区块列表');
INSERT INTO `cmstop_openaca` VALUES ('9', '7', 'page', 'section', 'get', '读取区块内容');
INSERT INTO `cmstop_openaca` VALUES ('10', '7', 'page', 'section', 'gethtml', '读取区块HTML');
INSERT INTO `cmstop_openaca` VALUES ('11', '1', 'system', 'content', null, '内容');
INSERT INTO `cmstop_openaca` VALUES ('12', '11', 'system', 'content', 'ls', '读取内容列表');
INSERT INTO `cmstop_openaca` VALUES ('13', null, 'article', null, null, '文章');
INSERT INTO `cmstop_openaca` VALUES ('14', '13', 'article', 'article', null, '文章');
INSERT INTO `cmstop_openaca` VALUES ('15', '14', 'article', 'article', 'get', '读取文章内容');
INSERT INTO `cmstop_openaca` VALUES ('16', '14', 'article', 'article', 'add', '添加文章内容');
INSERT INTO `cmstop_openaca` VALUES ('17', null, 'video', null, null, '视频');
INSERT INTO `cmstop_openaca` VALUES ('18', '17', 'video', 'video', null, '视频');
INSERT INTO `cmstop_openaca` VALUES ('19', '18', 'video', 'video', 'get', '读取视频内容');
INSERT INTO `cmstop_openaca` VALUES ('20', '18', 'video', 'video', 'add', '添加视频内容');

CREATE TABLE IF NOT EXISTS `cmstop_openaca_user` (
  `userid` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `acaid` smallint(5) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`userid`,`acaid`),
  KEY `acaid` (`acaid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='开放接口用户权限分配';

CREATE TABLE IF NOT EXISTS `cmstop_openauth` (
  `userid` int(10) unsigned NOT NULL COMMENT '用户编号',
  `username` varchar(50) NOT NULL DEFAULT '' COMMENT '用户名，冗余保存',
  `auth_key` varchar(32) NOT NULL DEFAULT '' COMMENT '授权公钥',
  `auth_secret` varchar(32) NOT NULL DEFAULT '' COMMENT '授权私钥',
  `disabled` tinyint(1) unsigned DEFAULT '0' COMMENT '是否禁用',
  `remarks` varchar(255) DEFAULT '' COMMENT '备注',
  PRIMARY KEY (`userid`),
  UNIQUE KEY `auth_key` (`auth_key`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='开放接口授权信息';

-- 增加开放接口菜单
INSERT INTO `cmstop_menu` (`menuid`, `parentid`, `parentids`, `childids`, `name`, `url`, `target`, `sort`) VALUES (NULL, '5', '5', NULL, '接口授权管理', '?app=system&controller=openauth&action=index', NULL, '0');
SET @aca_menuid = LAST_INSERT_ID();
INSERT INTO `cmstop_menu` (`menuid`, `parentid`, `parentids`, `childids`, `name`, `url`, `target`, `sort`) VALUES (NULL, @aca_menuid, CONCAT('5',',',@aca_menuid), NULL, '接口权限', '?app=system&controller=openaca&action=index', NULL, '0');
SET @aca_menuid_child = LAST_INSERT_ID();
UPDATE `cmstop_menu` SET `childids`=CONCAT('',@aca_menuid_child) WHERE `menuid`=@aca_menuid;

-- 内容表记录转发时间
ALTER TABLE `cmstop_content` ADD `tweeted` INT( 10 ) UNSIGNED NOT NULL DEFAULT '0' AFTER `score` ;

-- 删除旧版转发菜单
DELETE FROM `cmstop_menu` WHERE `cmstop_menu`.`menuid` = 112 LIMIT 1;
UPDATE `cmstop_menu` SET `childids` = REPLACE(`childids`, '112,', '') WHERE `cmstop_menu`.`menuid` =8 LIMIT 1;

-- 添加挂件相关表
CREATE TABLE IF NOT EXISTS `cmstop_addon` (
  `addonid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `engine` varchar(20) NOT NULL,
  `contentid` mediumint(8) unsigned DEFAULT NULL COMMENT '附属内容ID',
  `data` longtext,
  `created` int(10) unsigned NOT NULL,
  `createdby` mediumint(8) unsigned NOT NULL,
  `updated` int(10) unsigned DEFAULT NULL,
  `updatedby` mediumint(8) unsigned DEFAULT NULL,
  `published` int(10) unsigned DEFAULT NULL,
  `publishedby` mediumint(8) unsigned DEFAULT NULL,
  PRIMARY KEY (`addonid`),
  KEY `contentid` (`contentid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `cmstop_addon_engine` (
  `engineid` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(20) NOT NULL,
  `description` varchar(100) NOT NULL,
  `place` varchar(50) NOT NULL,
  `version` varchar(20) NOT NULL,
  `author` varchar(255) NOT NULL,
  `updateurl` varchar(255) DEFAULT NULL,
  `installed` int(10) unsigned DEFAULT NULL,
  `disabled` tinyint(1) unsigned DEFAULT NULL,
  `sort` mediumint(8) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`engineid`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

INSERT INTO `cmstop_addon_engine` (`engineid`, `name`, `description`, `place`, `version`, `author`, `updateurl`, `installed`, `disabled`, `sort`) VALUES
(1, 'picture_group', '组图', 'A2', '', '', NULL, 1341457059, 0, 1),
(2, 'video', '视频', 'A2', '', '', NULL, 1341457402, 0, 7),
(3, 'vote', '投票', 'A5', '', '', NULL, 1341457572, 0, 6),
(4, 'special', '专题', 'A5', '', '', NULL, 1341458574, 0, 5),
(5, 'survey', '调查', 'A5', '', '', NULL, 1341458665, 0, 8),
(6, 'activity', '活动', 'A5', '', '', NULL, 1341458678, 0, 4),
(7, 'weibo', '微博', 'A5', '', '', NULL, 1341458784, 0, 3),
(8, 'map', '百度地图', 'A5', '', '', NULL, 1341478362, 0, 2);

CREATE TABLE IF NOT EXISTS `cmstop_content_addon` (
  `contentid` mediumint(8) unsigned NOT NULL,
  `engine` varchar(20) NOT NULL,
  `addonid` int(10) unsigned NOT NULL,
  `place` varchar(50) NOT NULL,
  PRIMARY KEY (`contentid`,`addonid`),
  KEY `addonid` (`addonid`),
  KEY `contentid` (`contentid`,`place`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

ALTER TABLE `cmstop_addon`
  ADD CONSTRAINT `cmstop_addon_ibfk_1` FOREIGN KEY (`contentid`) REFERENCES `cmstop_content` (`contentid`) ON DELETE SET NULL;

ALTER TABLE `cmstop_content_addon`
  ADD CONSTRAINT `cmstop_content_addon_ibfk_1` FOREIGN KEY (`contentid`) REFERENCES `cmstop_content` (`contentid`) ON DELETE CASCADE,
  ADD CONSTRAINT `cmstop_content_addon_ibfk_2` FOREIGN KEY (`addonid`) REFERENCES `cmstop_addon` (`addonid`) ON DELETE CASCADE;

INSERT INTO `cmstop_app` (`app`, `name`, `description`, `url`, `version`, `author`, `author_url`, `author_email`, `install_time`, `update_time`, `disabled`) VALUES
('addon', '内容挂件', '', '', '1.0.0', 'cmstop', 'http://www.cmstop.com/', 'webmaster@cmstop.com', 1274811314, 1274811314, 0);

-- 云平台
CREATE TABLE IF NOT EXISTS `cmstop_api` (
  `apiid` smallint(3) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(32) NOT NULL,
  `interface` varchar(40) NOT NULL,
  `description` varchar(200) DEFAULT '',
  `icon` varchar(255) NOT NULL,
  `authorize` varchar(500) NOT NULL DEFAULT '{}',
  `islogin` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `isshare` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `state` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `sort` smallint(3) unsigned NOT NULL,
  PRIMARY KEY (`apiid`),
  KEY `sort` (`sort`),
  KEY `islogin` (`islogin`),
  KEY `isshare` (`isshare`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;


CREATE TABLE IF NOT EXISTS `cmstop_member_api` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `apiid` smallint(3) unsigned NOT NULL,
  `userid` mediumint(8) unsigned NOT NULL,
  `authkey` varchar(200) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `userid` (`apiid`,`userid`),
  KEY `apiid` (`apiid`,`authkey`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;

INSERT INTO `cmstop_api` (`name`, `interface`, `description`, `icon`, `authorize`, `islogin`, `isshare`, `state`, `sort`) VALUES
('新浪微博', 'sina', '', 'http://img.silkroad.news.cn/templates/default/img/sina.gif', '{"client_id":"","client_secret":""}', 1, 0, 1, 1);

INSERT INTO `cmstop_api` (`name`, `interface`, `description`, `icon`, `authorize`, `islogin`, `isshare`, `state`, `sort`) VALUES
('QQ互联', 'qq', '', 'http://img.silkroad.news.cn/templates/default/img/qzone.gif', '{"client_id":"","client_secret":""}', 1, 0, 1, 2);

INSERT INTO `cmstop_app` (`app`, `name`, `description`, `url`, `version`, `author`, `author_url`, `author_email`, `install_time`, `update_time`, `disabled`) VALUES ('cloud', '云平台', '云平台', '', '', 'CmsTop', 'http://www.cmstop.com/', 'webmaster@cmstop.com', 1274811314, 1274811314, 0);

INSERT INTO `cmstop_menu` (`menuid`, `parentid`, `parentids`, `childids`, `name`, `url`, `target`, `sort`) VALUES
(NULL, 5, '5', NULL, '云平台', '?app=cloud&controller=cloud&action=index', NULL, 0);

-- 恢复百度新闻源功能
INSERT INTO `cmstop_cron` (`cronid`, `type`, `name`, `app`, `param`, `controller`, `action`, `lastrun`, `nextrun`, `mode`, `time`, `starttime`, `interval`, `times`, `already`, `endtime`, `day`, `weekday`, `hour`, `minute`, `rule`, `disabled`, `hidden`) VALUES
('15', 'system', '百度新闻源更新', 'system', '', 'baidunews', 'xml', '1293779128', '1293782760', '2', '0', '0', '5', '0', '0', '0', '', '0,1,2,3,4,5,6', '9', '10', NULL, 0, 0);
INSERT INTO `cmstop_menu` (`menuid`, `parentid`, `parentids`, `childids`, `name`, `url`, `target`, `sort`) VALUES
(41, 8, '8', NULL, '百度新闻源', '?app=system&controller=baidunews', NULL, 15);
UPDATE `cmstop_menu` SET `childids` = REPLACE(`childids`, ',40,', ',40,41,') WHERE `menuid` = 8;
INSERT INTO `cmstop_setting` (`app`, `var`, `value`) VALUES
('system', 'baidunews', 'array (\n  ''open'' => ''1'',\n  ''url'' => ''{PSN:1}/xml/baidunews.xml'',\n  ''category'' => \n  array (\n    0 => ''1'',\n    1 => ''2'',\n    2 => ''3'',\n    3 => ''4'',\n    4 => ''5'',\n    5 => ''6'',\n  ),\n  ''article'' => ''1'',\n  ''picture'' => ''1'',\n  ''number'' => ''100'',\n  ''frequency'' => ''10'',\n  ''webname'' => ''http://www.silkroad.news.cn/'',\n  ''adminemail'' => ''admin@cmstop.com'',\n  ''updatetime'' => ''10'',\n)');

-- 增加视频专辑功能
CREATE TABLE IF NOT EXISTS `cmstop_videolist` (
  `listid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `listname` varchar(100) NOT NULL DEFAULT '',
  `sorttype` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '0 正序 1 倒序',
  `videonum` int(10) unsigned NOT NULL DEFAULT '0',
  `created` int(10) unsigned NOT NULL DEFAULT '0',
  `createdby` int(10) unsigned NOT NULL DEFAULT '0',
  `modified` int(10) unsigned NOT NULL DEFAULT '0',
  `modifiedby` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`listid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `cmstop_videolist_data` (
  `contentid` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `listid` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `sort` mediumint(8) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`contentid`),
  KEY `listid` (`listid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

ALTER TABLE `cmstop_video` ADD COLUMN `listid` mediumint(8) unsigned NOT NULL DEFAULT '0';

-- 投票增加展示方式
ALTER TABLE `cmstop_vote`
  ADD `display` varchar(15) NOT NULL DEFAULT 'list';

-- 链接模型增加引用内容ID字段
ALTER TABLE `cmstop_link`
  ADD `referenceid` MEDIUMINT UNSIGNED NULL DEFAULT NULL AFTER `contentid`,
  ADD INDEX (`referenceid`);
ALTER TABLE `cmstop_link`
  ADD CONSTRAINT `cmstop_link_ibfk_2` FOREIGN KEY (`referenceid`) REFERENCES `cmstop_content` (`contentid`) ON DELETE SET NULL;

-- 页面表增加标志位
ALTER TABLE `cmstop_page`
  ADD `status` TINYINT UNSIGNED NOT NULL DEFAULT '1' AFTER `sort`;

-- 区块表增加标志位
ALTER TABLE `cmstop_section`
  ADD `status` TINYINT UNSIGNED NOT NULL DEFAULT '1';

-- 扩展用户-接口表
ALTER TABLE `cmstop_member_api` ADD `access_token` VARCHAR( 32 ) NOT NULL DEFAULT '',
ADD `expires_in` INT( 10 ) UNSIGNED NOT NULL ;

INSERT INTO `cmstop_menu` (`menuid`, `parentid`, `parentids`, `childids`, `name`, `url`, `target`, `sort`) VALUES (NULL, 5, '5', NULL, '专题', '?app=special&controller=setting&action=index', NULL, 0);
SET @special_menuid = LAST_INSERT_ID();
SET @special_menuids = CONCAT('5', ',', @special_menuid);
INSERT INTO `cmstop_menu` (`menuid`, `parentid`, `parentids`, `childids`, `name`, `url`, `target`, `sort`) VALUES
(NULL, @special_menuid, @special_menuids, NULL, '方案分类', '?app=special&controller=setting&action=schemeTypes', NULL, 1),
(NULL, @special_menuid, @special_menuids, NULL, '方案管理', '?app=special&controller=setting&action=scheme', NULL, 2),
(NULL, @special_menuid, @special_menuids, NULL, '模版管理', '?app=special&controller=setting&action=template', NULL, 3);

ALTER TABLE `cmstop_widget` ADD `thumb` VARCHAR(200) NULL AFTER `folder`;
ALTER TABLE `cmstop_special` ADD `lastupdated` INT(10) unsigned NULL AFTER `mode`;
ALTER TABLE `cmstop_special_page` ADD `path` varchar(100) DEFAULT NULL AFTER `file`;
ALTER TABLE `cmstop_special_page` ADD `version` varchar(10) DEFAULT '1.0' AFTER `template`;

CREATE TABLE IF NOT EXISTS `cmstop_special_scheme_type` (
  `typeid` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `sort` smallint(5) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`typeid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO `cmstop_special_scheme_type` (`typeid`, `name`, `sort`) VALUES
(1, '节日', 1),
(2, '事件', 2),
(3, '会议', 3),
(4, '活动', 4),
(5, '人物', 5),
(6, '话题', 6),
(7, '迷你专题', 7);

CREATE TABLE IF NOT EXISTS `cmstop_special_scheme` (
  `entry` varchar(32) NOT NULL,
  `typeid` smallint(5) unsigned DEFAULT NULL,
  `name` varchar(100) NOT NULL,
  `thumb` varchar(100) DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  `reserved` tinyint(1) unsigned DEFAULT NULL,
  `created` int(10) unsigned NOT NULL,
  `createdby` mediumint(8) unsigned NOT NULL,
  `updated` int(10) unsigned DEFAULT NULL,
  `updatedby` mediumint(8) unsigned DEFAULT NULL,
  PRIMARY KEY (`entry`),
  KEY `typeid` (`typeid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO `cmstop_special_scheme` (`entry`, `typeid`, `name`, `thumb`, `description`, `reserved`, `created`, `createdby`) VALUES
('00000', NULL, '空白方案', 'thumb.png', NULL, 1, 4000000000, 0),
('1000000001', 1, '春节方案', 'thumb.jpg', '春节专题方案，应用时可根据网站需要自行修改', 1, UNIX_TIMESTAMP(), 0),
('1000000002', 1, '上元方案', 'thumb.jpg', '上元专题，应用时可根据网站需要自行修改', 1, UNIX_TIMESTAMP(), 0),
('1000000003', 1, '端午方案', 'thumb.jpg', '端午专题方案，应用时可根据网站需要自行修改', 1, UNIX_TIMESTAMP(), 0),
('1000000004', 1, '中秋方案', 'thumb.jpg', '中秋专题方案，应用时可根据网站需要自行修改', 1, UNIX_TIMESTAMP(), 0),
('1000000005', 2, '简版事件方案', 'thumb.jpg', '适用于突发事件，并且新闻背景资料不多的情况', 1, UNIX_TIMESTAMP(), 0),
('1000000006', 2, '事件方案', 'thumb.png', '常用专题方案，可以根据事件题材选择不同的事件风格', 1, UNIX_TIMESTAMP(), 0),
('1000000007', 3, '政务方案', 'thumb.png', '政治会议专题专用方案', 1, UNIX_TIMESTAMP(), 0),
('1000000008', 3, '行业方案', 'thumb.jpg', '用于行业会议专题的方案', 1, UNIX_TIMESTAMP(), 0),
('1000000009', 4, '评选方案', 'thumb.png', '适合优秀人物，十大事件类的专题', 1, UNIX_TIMESTAMP(), 0),
('1000000010', 5, '人物方案', 'thumb.jpg', '人物专题方案，可以看做今日话题专题方案的另一种表现形式', 1, UNIX_TIMESTAMP(), 0),
('1000000011', 6, '图说方案', 'thumb.jpg', '适合利用高清组图讲述一件事情的专题', 1, UNIX_TIMESTAMP(), 0),
('1000000012', 6, '辩论方案', 'thumb.jpg', '辩论专题专用，特别设计了正反方辩论', 1, UNIX_TIMESTAMP(), 0),
('1000000013', 6, '今日话题', 'thumb.jpg', '针对一个事件或者一个人进行深度剖析解读', 1, UNIX_TIMESTAMP(), 0),
('1000000014', 7, '内容引导方案', 'thumb.jpg', '放在文章内容页，增强网站粘度，吸引用户点击', 1, UNIX_TIMESTAMP(), 0);

CREATE TABLE IF NOT EXISTS `cmstop_special_template` (
  `entry` varchar(32) NOT NULL,
  `name` varchar(100) NOT NULL,
  `thumb` varchar(100) DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  `created` int(10) unsigned NOT NULL,
  `createdby` mediumint(8) unsigned NOT NULL,
  `updated` int(10) unsigned DEFAULT NULL,
  `updatedby` mediumint(8) unsigned DEFAULT NULL,
  PRIMARY KEY (`entry`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

ALTER TABLE `cmstop_special_scheme`
  ADD CONSTRAINT `cmstop_special_scheme_ibfk_1` FOREIGN KEY (`typeid`) REFERENCES `cmstop_special_scheme_type` (`typeid`) ON DELETE SET NULL;

-- 手动、推送区块增加记录字段设置，推送区块增加是否审核标志
ALTER TABLE `cmstop_section`
  ADD `check` TINYINT(1) UNSIGNED NOT NULL DEFAULT '0' AFTER `frequency`,
  ADD `fields` TEXT NULL AFTER `check`;
ALTER TABLE `cmstop_section`
  CHANGE `type` `type` ENUM('auto','hand','push','html','feed','json','rpc') NOT NULL DEFAULT 'auto';

ALTER TABLE `cmstop_section_recommend`
  ADD `data` TEXT NULL DEFAULT NULL AFTER `sectionid`,
  ADD `published` int(10) unsigned NOT NULL AFTER `data`,
  ADD `status` tinyint(3) unsigned NOT NULL DEFAULT '0' AFTER `recommendedby`,
  ADD `sort` smallint(5) unsigned NOT NULL DEFAULT '0' AFTER `status`,
  ADD `istop` tinyint(3) unsigned NOT NULL DEFAULT '0' AFTER `sort`;
ALTER TABLE `cmstop_section_recommend`
  CHANGE `contentid` `contentid` MEDIUMINT( 8 ) UNSIGNED NULL DEFAULT NULL;

-- 增加第三方vms接口
CREATE TABLE IF NOT EXISTS `cmstop_video_thirdparty` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(50) DEFAULT '',
  `authkey` char(32) DEFAULT '',
  `apiurl` varchar(200) DEFAULT '',
  `apitype` varchar(20) DEFAULT '',
  `status` tinyint(1) unsigned DEFAULT '0',
  `sort` tinyint(4) unsigned DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- 页面增加定时删除区块历史和区块日志任务
INSERT INTO `cmstop_cron` (`type`, `name`, `app`, `param`, `controller`, `action`, `lastrun`, `nextrun`, `mode`, `time`, `starttime`, `interval`, `times`, `already`, `endtime`, `day`, `weekday`, `hour`, `minute`, `rule`, `disabled`, `hidden`) VALUES
('system', '页面定时删除区块历史数据', 'page', '', 'page', 'cron_remove_history', '1334212297', '1334212740', '3', '0', '0', '0', '0', '0', '0', NULL, NULL, NULL, NULL, NULL, 0, 0),
('system', '页面定时删除区块操作记录', 'page', '', 'page', 'cron_remove_history', '1334212297', '1334212740', '3', '0', '0', '0', '0', '0', '0', NULL, NULL, NULL, NULL, NULL, 0, 0);

-- 访谈增加区分视频和直播
ALTER TABLE `cmstop_interview` ADD COLUMN `live`  varchar(255) NULL DEFAULT '' AFTER `video`;
ALTER TABLE `cmstop_interview` MODIFY COLUMN `mode`  enum('text','video','live') CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT 'text' AFTER `compere`;

-- 页面推送内容表增加是否删除的标志位
ALTER TABLE `cmstop_section_recommend` ADD `isdeleted` tinyint(3) unsigned NOT NULL DEFAULT '0';

-- 活动增加更换背景图
ALTER TABLE `cmstop_activity` ADD COLUMN `bgimg` varchar(255) NULL DEFAULT '';

-- 调查增加更换背景图
ALTER TABLE `cmstop_survey` ADD COLUMN `bgimg` varchar(255) NULL DEFAULT '';

-- 名称变更
UPDATE `cmstop_menu` SET `name` = '敏感词监控' WHERE `cmstop_menu`.`menuid` =34 LIMIT 1 ;

-- 投票增加高宽设置字段
ALTER TABLE `cmstop_vote`
  ADD `thumb_width` SMALLINT UNSIGNED NOT NULL DEFAULT '0',
  ADD `thumb_height` SMALLINT UNSIGNED NOT NULL DEFAULT '0';

-- 微博相关
CREATE TABLE IF NOT EXISTS `cmstop_weibo` (
  `weiboid` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `type` enum('sina','tencent') NOT NULL,
  `name` varchar(80) NOT NULL DEFAULT '',
  `openid` varchar(32) DEFAULT NULL,
  `access_token` varchar(32) NOT NULL,
  `expires` int(10) unsigned NOT NULL,
  `url` varchar(255) DEFAULT NULL,
  `state` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `sort` smallint(5) unsigned NOT NULL,
  PRIMARY KEY (`weiboid`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;
INSERT INTO `cmstop_menu` (`menuid`, `parentid`, `parentids`, `childids`, `name`, `url`, `target`, `sort`) VALUES
(NULL, 5, '5', '151', '微博转发', '?app=weibo&controller=weibo&action=index', NULL, 0);
SET @aca_menuid = LAST_INSERT_ID();
INSERT INTO `cmstop_menu` (`menuid`, `parentid`, `parentids`, `childids`, `name`, `url`, `target`, `sort`) VALUES
(NULL, @aca_menuid, CONCAT('5',',',@aca_menuid), NULL, '微博账号设置', '?app=weibo&controller=weibo&action=account', NULL, 0);

-- 电子报相关
CREATE TABLE IF NOT EXISTS `cmstop_epaper` (
  `epid` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(40) NOT NULL,
  `type` tinyint(1) NOT NULL DEFAULT '0',
  `sort` smallint(5) unsigned NOT NULL,
  `state` tinyint(1) NOT NULL DEFAULT '1',
  `charset` varchar(10) NOT NULL DEFAULT 'UTF-8',
  `get_url_type` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `epaper_rule` varchar(255) NOT NULL DEFAULT '',
  `edition_cycle` varchar(10) NOT NULL DEFAULT '1D',
  `first_time` int(10) unsigned NOT NULL,
  `epaper_limit` smallint(3) NOT NULL DEFAULT '10',
  `list_start` varchar(255) NOT NULL DEFAULT '',
  `list_end` varchar(255) NOT NULL DEFAULT '',
  `list_rule` varchar(255) NOT NULL DEFAULT '',
  `content_start` varchar(255) NOT NULL DEFAULT '',
  `content_end` varchar(255) NOT NULL DEFAULT '',
  `content_rule` varchar(255) NOT NULL DEFAULT '',
  `content_scope_start` varchar(255) NOT NULL DEFAULT '',
  `content_scope_end` varchar(255) NOT NULL DEFAULT '',
  `content_title_start` varchar(255) NOT NULL DEFAULT '',
  `content_title_end` varchar(255) NOT NULL DEFAULT '',
  `content_article_start` varchar(255) NOT NULL DEFAULT '',
  `content_article_end` varchar(255) NOT NULL DEFAULT '',
  `content_author_start` varchar(255) NOT NULL DEFAULT '',
  `content_author_end` varchar(255) NOT NULL DEFAULT '',
  `allow_tags` varchar(100) NOT NULL DEFAULT 'a,b,p,br,img,span,strong',
  `default_catid` mediumint(8) NULL DEFAULT NULL,
  `import_list` text,
  `allowed_auto` text,
  `default_state` tinyint(1) unsigned NOT NULL DEFAULT '3',
  PRIMARY KEY (`epid`),
  KEY `sort` (`sort`),
  UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `cmstop_epaper_content` (
  `spiderid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `guid` char(32) NOT NULL,
  `epaperid` smallint(3) unsigned NOT NULL,
  `editionid` int(10) unsigned NOT NULL,
  `catid` int(8) unsigned DEFAULT NULL,
  `contentid` int(10) unsigned DEFAULT NULL,
  `title` varchar(120) NOT NULL DEFAULT '',
  `source` varchar(40) NOT NULL DEFAULT '',
  `url` varchar(255) NOT NULL,
  `status` enum('viewed','spiden','cron','new') NOT NULL,
  `spiden` int(10) unsigned DEFAULT NULL,
  `spidenby` mediumint(8) unsigned DEFAULT NULL,
  PRIMARY KEY (`spiderid`),
  KEY `epaperid` (`epaperid`),
  KEY `epaperid_2` (`epaperid`,`editionid`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `cmstop_epaper_cron` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `epaperid` smallint(3) unsigned NOT NULL,
  `editionid` int(11) NOT NULL,
  `total` mediumint(8) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO `cmstop_epaper` (`epid`, `name`, `type`, `sort`, `state`, `charset`, `get_url_type`, `epaper_rule`, `edition_cycle`, `first_time`, `epaper_limit`, `list_start`, `list_end`, `list_rule`, `content_start`, `content_end`, `content_rule`, `content_scope_start`, `content_scope_end`, `content_title_start`, `content_title_end`, `content_article_start`, `content_article_end`, `content_author_start`, `content_author_end`, `allow_tags`, `default_catid`, `import_list`, `allowed_auto`, `default_state`) VALUES
(1, '证劵时报', 1, 0, 1, 'UTF-8', 0, 'http://epaper.stcn.com/paper/zqsb/html/(Y)-(M)/(D)/node_2.htm', '1D ', 1338739200, 10, '<!-------bmdh版面导航------>\n<div id=bmdh>', '</div>\n<!-------bmdh版面导航END------>', 'http://epaper.stcn.com/paper/zqsb/html/(*)/node_(*).htm', '<!-------bmdh版面导航END------>\n<!-- -------------------------标题导航-------------->', '<!-- -------------------------标题导航 END -------------->', 'http://epaper.stcn.com/paper/zqsb/html/(*)/content_(*).htm', '<!-- =========================================标题开始 ====================================== --->', '<!-- ===========================文章内容end ========================================= --', '</div> <h2>', '</h2>', '<div id="mainCon">', '</P></founder-content>', '作者：', '</div>', 'a,b,p,br,img,span,strong', 4, '[0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0]', '["0","1","2","3","4","5","6","7","8","9","10","11","12","13","14","15","16","17","18","19","20","21","22","23","24","25","26","27","28","29","30","31","32","33","34","35","36","37","38","39","40","41","42","43","44","45","46","47"]', 3);
INSERT INTO `cmstop_cron` (`cronid`, `type`, `name`, `app`, `param`, `controller`, `action`, `lastrun`, `nextrun`, `mode`, `time`, `starttime`, `interval`, `times`, `already`, `endtime`, `day`, `weekday`, `hour`, `minute`, `rule`, `disabled`, `hidden`) VALUES
(NULL, 'system', '数字报抓取', 'epaper', NULL, 'import', 'cron', 1347853504, 1347854160, 2, NULL, NULL, 10, 0, 3, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0);
INSERT INTO `cmstop_menu` (`menuid`, `parentid`, `parentids`, `childids`, `name`, `url`, `target`, `sort`) VALUES
(NULL, 5, '5', NULL, '数字报抓取', '?app=epaper&controller=epaper&action=import&id=1', NULL, 0);
SET @aca_menuid = LAST_INSERT_ID();
INSERT INTO `cmstop_menu` (`menuid`, `parentid`, `parentids`, `childids`, `name`, `url`, `target`, `sort`) VALUES
(NULL, @aca_menuid, CONCAT('5',',',@aca_menuid), NULL, '规则管理', '?app=epaper&controller=epaper&action=index', NULL, 1);
SET @aca_menuid_child = LAST_INSERT_ID();
UPDATE `cmstop_menu` SET `childids`=CONCAT('',@aca_menuid_child) WHERE `menuid`=@aca_menuid;

-- 栏目表增加名称拼音和缩写
ALTER TABLE `cmstop_category`
  ADD `pinyin` VARCHAR( 120 ) NULL AFTER `name` ,
  ADD `abbr` VARCHAR( 20 ) NULL AFTER `pinyin` ,
  ADD INDEX ( `name`, `pinyin` , `abbr` );

-- 页面区块增加生成列表功能
ALTER TABLE `cmstop_section`
  ADD `list_enabled` TINYINT( 1 ) UNSIGNED NOT NULL DEFAULT '0',
  ADD `list_template` VARCHAR( 100 ) NULL DEFAULT NULL ,
  ADD `list_pagesize` SMALLINT UNSIGNED NULL ,
  ADD `list_pages` SMALLINT UNSIGNED NOT NULL DEFAULT 10;

-- 修改 aca 的 action 字段为 text
ALTER TABLE `cmstop_aca` DROP INDEX `app`;
ALTER TABLE `cmstop_aca` CHANGE `action` `action` TEXT NULL DEFAULT NULL;

-- 投票增加页面背景图
ALTER TABLE `cmstop_vote` ADD `bgimg` VARCHAR( 255 ) NULL DEFAULT NULL;