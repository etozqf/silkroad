/*!40101 SET NAMES utf8 */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

ALTER TABLE `cmstop_spider_task`
	ADD `titlecheck` tinyint(1) unsigned NOT NULL DEFAULT 0,
	ADD `cron` enum('0','1') NOT NULL DEFAULT '0',
	ADD `cron_frequency` smallint(5) unsigned NOT NULL DEFAULT '3600',
	ADD `cron_count` smallint(5) unsigned NOT NULL DEFAULT '0',
	ADD `cron_status` enum('1','3','6') NOT NULL DEFAULT '3',
	ADD `cron_next` int(10) unsigned NOT NULL DEFAULT '0',
	ADD `cron_last` int(10) unsigned NOT NULL;

INSERT INTO `cmstop_cron` (`cronid`, `type`, `name`, `app`, `param`, `controller`, `action`, `lastrun`, `nextrun`, `mode`, `time`, `starttime`, `interval`, `times`, `already`, `endtime`, `day`, `weekday`, `hour`, `minute`, `rule`, `disabled`, `hidden`) VALUES
(33, 'system', '文章定时采集', 'spider', '', 'cron', 'cron', NULL, 1352688180, 2, 0, 0, 10, 0, 1, 0, NULL, NULL, NULL, NULL, '', 0, 0);

-- 增加 kvdata 表用来存储一些非设置项的纯 key-value 数据
DROP TABLE IF EXISTS `cmstop_kvdata`;
CREATE TABLE `cmstop_kvdata` (
  `key` varchar(255) NOT NULL,
  `value` text,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- 活动和调查增加验证码
INSERT INTO `cmstop_setting` (`app`, `var`, `value`) VALUES
('activity', 'seccode', '1'),
('survey', 'seccode', '1');

-- 增加定时采集任务菜单
INSERT INTO `cmstop_menu` (`menuid`, `parentid`, `parentids`, `childids`, `name`, `url`, `target`, `sort`) VALUES
(NULL, 95, '5,95', NULL, '定时采集日志', '?app=spider&controller=cron&action=index', NULL, 3);

-- 编辑器增加前台上传图片大小限制
INSERT INTO `cmstop_setting` (`app`, `var`, `value`) VALUES
('editor', 'upload_max_filesize', '5');

-- 定时采集日志表
CREATE TABLE `cmstop_spider_cron_log` (
  `id` int(8) unsigned NOT NULL AUTO_INCREMENT,
  `taskid` int(8) unsigned NOT NULL,
  `start_time` int(10) unsigned NOT NULL DEFAULT '0',
  `end_time` int(10) unsigned NOT NULL DEFAULT '0',
  `total` smallint(5) unsigned NOT NULL DEFAULT '0',
  `failed` smallint(5) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `taskid` (`taskid`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

-- 活动自定义字段相关
DROP TABLE IF EXISTS `cmstop_activity_field`;
CREATE TABLE `cmstop_activity_field` (
  `fieldid` varchar(50) NOT NULL,
  `label` varchar(100) NOT NULL,
  `type` varchar(50) NOT NULL,
  `disabled` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `default` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `options` text COLLATE utf8_bin,
  `sort` smallint(6) NOT NULL,
  `system` tinyint(1) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`fieldid`),
  UNIQUE KEY `fieldid` (`fieldid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO `cmstop_activity_field` (`fieldid`, `label`, `type`, `disabled`, `default`, `options`, `sort`, `system`) VALUES
('address', '地址', 'text', 0, 0, '{"limit":"150","rule":"","regex":""}', 15, 0),
('aid', '附件', 'file', 0, 0, '{"sizelimit":"5","fileext":""}', 17, 0),
('company', '工作单位', 'text', 0, 0, '{"limit":"100","rule":"","regex":""}', 5, 0),
('email', 'Email', 'text', 0, 0, '{"limit":"","rule":"email","regex":""}', 11, 0),
('identity', '身份证号码', 'text', 0, 0, '{"limit":"","rule":"id","regex":""}', 4, 0),
('job', '职业', 'text', 0, 0, '{"limit":"100","rule":"","regex":""}', 6, 0),
('mobile', '手机号码', 'text', 0, 0, '{"limit":"","rule":"mobile","regex":""}', 10, 0),
('msn', 'MSN', 'text', 0, 0, '{"limit":"","rule":"email","regex":""}', 13, 0),
('name', '姓名', 'text', 0, 1, '{"limit":"","rule":"","regex":""}', 1, 1),
('note', '附言', 'textarea', 0, 1, '{"limit":"500","rule":"","regex":""}', 18, 1),
('photo', '照片', 'photo', 0, 0, '{"sizelimit":"5"}', 3, 0),
('qq', 'QQ', 'text', 0, 0, '{"limit":"","rule":"qq","regex":""}', 12, 0),
('sex', '性别', 'radio', 0, 0, '{"option":"男|1\\n女|0"}', 2, 0),
('site', '个人主页', 'text', 0, 0, '{"limit":"","rule":"url","regex":""}', 14, 0),
('telephone', '电话号码', 'text', 0, 0, '{"limit":"","rule":"telephone","regex":""}', 9, 0),
('zipcode', '邮政编码', 'text', 0, 0, '{"limit":"","rule":"zipcode","regex":""}', 16, 0);

ALTER TABLE `cmstop_activity_sign` ADD `data` TEXT NOT NULL AFTER `note`;

-- 增大开放授权表userid为int字段
ALTER TABLE `cmstop_openaca_user` MODIFY COLUMN `userid`  int(10) UNSIGNED NOT NULL DEFAULT 0 FIRST ;

-- 数字报采集增加failed标志
ALTER TABLE `cmstop_epaper_content` CHANGE `status` `status` ENUM( 'viewed', 'spiden', 'cron', 'failed', 'new' ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL ;

-- 评论话题允许匿名
ALTER TABLE `cmstop_comment_topic` CHANGE `createdby` `createdby` mediumint(8) unsigned NOT NULL DEFAULT '0';

-- 手机版数据表 start
INSERT INTO `cmstop_menu` (`menuid`, `parentid`, `parentids`, `childids`, `name`, `url`, `target`, `sort`) VALUES
(168, NULL, NULL, '169,170,171,172,173,174,175,176,186,177,178,187,179,181,183', '移动', '?app=mobile&controller=content', NULL, 6),
(169, 168, '168', NULL, '内容管理', '?app=mobile&controller=content@mobile/content/menu', NULL, 1),
(170, 168, '168', NULL, '消息推送', '?app=mobile&controller=push', NULL, 2),
(171, 168, '168', '174,175', '统计', '?app=mobile&controller=stat&action=content', NULL, 3),
(172, 168, '168', NULL, '意见反馈', '?app=mobile&controller=feedback', NULL, 5),
(173, 168, '168', '176,186,177,178,187,179,181,183', '设置', '?app=mobile&controller=setting', NULL, 6),
(174, 171, '168,171', NULL, '客户端统计', '?app=mobile&controller=stat&action=client', NULL, 1),
(175, 171, '168,171', NULL, '内容统计', '?app=mobile&controller=stat&action=content', NULL, 2),
(176, 173, '168,173', NULL, '系统设置', '?app=mobile&controller=setting&action=index', NULL, 1),
(177, 173, '168,173', NULL, '频道管理', '?app=mobile&controller=setting&action=category', NULL, 3),
(178, 173, '168,173', NULL, '应用管理', '?app=mobile&controller=setting&action=app', NULL, 5),
(179, 173, '168,173', NULL, '版本升级', '?app=mobile&controller=setting&action=version', NULL, 6),
(181, 173, '168,173', NULL, '应用推荐', '?app=mobile&controller=setting&action=moreapp', NULL, 7),
(183, 173, '168,173', NULL, 'API 设置', '?app=mobile&controller=setting&action=api', NULL, 8),
(184, 163, '6,163', NULL, '生成', '?app=system&controller=qrcode&action=index', NULL, 1),
(185, 163, '6,163', NULL, '统计', '?app=system&controller=qrcode&action=stat', NULL, 2),
(186, 173, '168,173', NULL, '显示设置', '?app=mobile&controller=setting&action=display', NULL, 2),
(187, 173, '168,173', NULL, '自动抓取', '?app=mobile&controller=autofill', NULL, 4);
DELETE FROM `cmstop_menu` WHERE `menuid` = 137;

INSERT INTO `cmstop_menu` (`menuid`, `parentid`, `parentids`, `childids`, `name`, `url`, `target`, `sort`) VALUES
(193, 168, '168', NULL, '广告管理', '?app=mobile&controller=ad&action=index', NULL, 4);

INSERT INTO `cmstop_cron` (`cronid`, `type`, `name`, `app`, `param`, `controller`, `action`, `lastrun`, `nextrun`, `mode`, `time`, `starttime`, `interval`, `times`, `already`, `endtime`, `day`, `weekday`, `hour`, `minute`, `rule`, `disabled`, `hidden`) VALUES
(null, 'system', '移动内容统计定时入库', 'mobile', '', 'stat', 'cron_stat', 1362061382, 1362062040, 2, 0, 0, 10, 0, 9, 0, NULL, NULL, NULL, NULL, '', 0, 0),
(null, 'system', '二维码统计定时入库', 'system', '', 'qrcode', 'cron_stat', 1362850551, 1362851160, 2, 0, 0, 10, 0, 18, 0, NULL, NULL, NULL, NULL, '', 0, 0),
(null, 'system', '移动版内容自动抓取', 'mobile', '', 'autofill', 'cron', 1363413839, 1363413900, 2, 0, 0, 1, 0, 2, 0, NULL, NULL, NULL, NULL, '', 0, 0);

DROP TABLE IF EXISTS `cmstop_mobile_activity`;
CREATE TABLE `cmstop_mobile_activity` (
  `contentid` mediumint(8) unsigned NOT NULL,
  `image` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`contentid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `cmstop_mobile_autofill`;
CREATE TABLE `cmstop_mobile_autofill` (
  `id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `catid` smallint(5) unsigned DEFAULT NULL,
  `port` varchar(255) NOT NULL,
  `options` text NOT NULL,
  `interval` int(10) unsigned NOT NULL,
  `nextrun` int(10) unsigned NOT NULL DEFAULT '0',
  `state` tinyint(3) unsigned NOT NULL,
  `disabled` tinyint(3) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `catid` (`catid`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `cmstop_mobile_autofill_log`;
CREATE TABLE `cmstop_mobile_autofill_log` (
  `catid` smallint(5) unsigned NOT NULL,
  `uuid` varchar(32) NOT NULL,
  `contentid` mediumint(8) unsigned DEFAULT NULL,
  PRIMARY KEY (`catid`,`uuid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `cmstop_mobile_addon`;
CREATE TABLE `cmstop_mobile_addon` (
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
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `cmstop_mobile_app`;
CREATE TABLE `cmstop_mobile_app` (
  `appid` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `iconurl` varchar(255) NOT NULL,
  `url` varchar(255) NOT NULL,
  `disabled` tinyint(1) unsigned NOT NULL,
  `builtin` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `system` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `menu` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `sort` mediumint(8) unsigned NOT NULL,
  PRIMARY KEY (`appid`),
  UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=100;

INSERT INTO `cmstop_mobile_app` (`appid`, `name`, `iconurl`, `url`, `disabled`, `builtin`, `system`, `menu`, `sort`) VALUES
(1, '新闻', 'http://m.silkroad.news.cn/templates/default/app/images/app_xw_btn@2x.png', 'app:news', 0, 1, 1, 1, 1),
(2, '图片', 'http://m.silkroad.news.cn/templates/default/app/images/app_tps_btn@2x.png', 'app:picture', 0, 1, 0, 1, 2),
(3, '视频', 'http://m.silkroad.news.cn/templates/default/app/images/app_sp_btn@2x.png', 'app:video', 0, 1, 0, 1, 3),
(4, '专题', 'http://m.silkroad.news.cn/templates/default/app/images/app_zt_btn@2x.png', 'app:special', 0, 1, 0, 0, 4),
(5, '微博', 'http://m.silkroad.news.cn/templates/default/app/images/app_wb_btn@2x.png', 'app:weibo', 0, 1, 0, 1, 5),
(6, '报料', 'http://m.silkroad.news.cn/templates/default/app/images/app_bl_btn@2x.png', 'app:baoliao', 1, 1, 0, 0, 6),
(7, '二维码', 'http://m.silkroad.news.cn/templates/default/app/images/app_ewm_btn@2x.png', 'app:qrcode', 0, 1, 0, 0, 7),
(8, '投票', 'http://m.silkroad.news.cn/templates/default/app/images/app_tp_btn@2x.png', 'app:vote', 0, 1, 0, 0, 8),
(9, '活动', 'http://m.silkroad.news.cn/templates/default/app/images/app_hd_btn@2x.png', 'app:activity', 0, 1, 0, 0, 9),
(10, '调查', 'http://m.silkroad.news.cn/templates/default/app/images/app_dc_btn@2x.png', 'app:survey', 0, 1, 0, 0, 10);

DROP TABLE IF EXISTS `cmstop_mobile_article`;
CREATE TABLE `cmstop_mobile_article` (
  `contentid` mediumint(8) unsigned NOT NULL,
  `tags` varchar(255) DEFAULT NULL,
  `content` text NOT NULL,
  PRIMARY KEY (`contentid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `cmstop_mobile_category`;
CREATE TABLE `cmstop_mobile_category` (
  `catid` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `catname` varchar(20) NOT NULL,
  `iconurl` varchar(255) NOT NULL,
  `headline` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `display_slider` tinyint(1) unsigned NOT NULL,
  `slider_size` tinyint(3) unsigned NOT NULL,
  `default_display` tinyint(1) unsigned NOT NULL,
  `disabled` tinyint(1) unsigned NOT NULL,
  `sort` int(10) unsigned NOT NULL,
  `sorttime` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`catid`),
  UNIQUE KEY `catname` (`catname`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

INSERT INTO `cmstop_mobile_category` (`catid`, `catname`, `iconurl`, `headline`, `display_slider`, `slider_size`, `default_display`, `disabled`, `sort`, `sorttime`) VALUES
(1, '新闻头条', 'http://m.silkroad.news.cn/templates/default/app/images/category/headline.png', 1, 1, 3, 1, 0, 1, 1364981105),
(2, '国际', 'http://m.silkroad.news.cn/templates/default/app/images/category/guoji.png', 0, 1, 3, 1, 0, 2, 1364481636),
(3, '科技', 'http://m.silkroad.news.cn/templates/default/app/images/category/tech.png', 0, 1, 3, 1, 0, 3, 1364981105),
(4, '军事', 'http://m.silkroad.news.cn/templates/default/app/images/category/mil.png', 0, 1, 3, 1, 0, 4, 1364867768),
(5, '娱乐', 'http://m.silkroad.news.cn/templates/default/app/images/category/ent.png', 0, 1, 3, 1, 0, 5, 1364469707),
(6, '体育', 'http://m.silkroad.news.cn/templates/default/app/images/category/sport.png', 0, 1, 3, 1, 0, 6, 1364277483),
(7, '汽车', 'http://m.silkroad.news.cn/templates/default/app/images/category/car.png', 0, 1, 3, 1, 0, 7, 1364971637),
(8, '房产', 'http://m.silkroad.news.cn/templates/default/app/images/category/house.png', 0, 1, 3, 1, 0, 8, 1364192996),
(9, '财经', 'http://m.silkroad.news.cn/templates/default/app/images/category/finance.png', 0, 1, 3, 1, 0, 9, 1364481636),
(10, '游戏', 'http://m.silkroad.news.cn/templates/default/app/images/category/game.png', 0, 1, 3, 1, 0, 10, 1364192053);

DROP TABLE IF EXISTS `cmstop_mobile_category_bind`;
CREATE TABLE `cmstop_mobile_category_bind` (
  `mobile_catid` smallint(5) unsigned NOT NULL,
  `catid` smallint(5) unsigned NOT NULL,
  KEY `mobile_catid` (`mobile_catid`),
  KEY `catid` (`catid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `cmstop_mobile_category_priv`;
CREATE TABLE `cmstop_mobile_category_priv` (
  `catid` smallint(5) unsigned NOT NULL DEFAULT '0',
  `userid` mediumint(8) unsigned NOT NULL DEFAULT '0',
  KEY `catid` (`catid`),
  KEY `userid` (`userid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `cmstop_mobile_content`;
CREATE TABLE `cmstop_mobile_content` (
  `contentid` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `modelid` tinyint(3) unsigned NOT NULL,
  `title` varchar(200) NOT NULL,
  `description` varchar(255) NOT NULL,
  `thumb` varchar(255) DEFAULT NULL,
  `thumbig` varchar(255) DEFAULT NULL,
  `thumb_slider` varchar(255) DEFAULT NULL,
  `source` varchar(40) DEFAULT NULL,
  `url` varchar(255) NOT NULL,
  `sorttime` int(10) unsigned NOT NULL,
  `status` tinyint(1) unsigned NOT NULL,
  `status_old` tinyint(1) unsigned DEFAULT NULL,
  `related` tinyint(1) unsigned NOT NULL,
  `referenceid` mediumint(8) unsigned DEFAULT NULL,
  `allowcomment` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `topicid` int(10) unsigned DEFAULT NULL,
  `stick` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `created` int(10) unsigned NOT NULL,
  `createdby` mediumint(8) unsigned NOT NULL,
  `updated` int(10) unsigned DEFAULT NULL,
  `updatedby` mediumint(8) unsigned DEFAULT NULL,
  `published` int(10) unsigned DEFAULT NULL,
  `publishedby` mediumint(8) unsigned DEFAULT NULL,
  `unpublished` int(10) unsigned DEFAULT NULL,
  `unpublishedby` mediumint(8) unsigned DEFAULT NULL,
  `checked` int(10) unsigned DEFAULT NULL,
  `checkedby` mediumint(8) unsigned DEFAULT NULL,
  `removed` int(10) unsigned DEFAULT NULL,
  `removedby` mediumint(8) unsigned DEFAULT NULL,
  `locked` int(10) unsigned DEFAULT NULL,
  `lockedby` mediumint(8) unsigned DEFAULT NULL,
  PRIMARY KEY (`contentid`),
  KEY `referenceid` (`referenceid`),
  KEY `topicid` (`topicid`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `cmstop_mobile_content_addon`;
CREATE TABLE `cmstop_mobile_content_addon` (
  `contentid` mediumint(8) unsigned NOT NULL,
  `engine` varchar(20) NOT NULL,
  `addonid` int(10) unsigned NOT NULL,
  `place` varchar(50) NOT NULL,
  PRIMARY KEY (`contentid`,`addonid`),
  KEY `addonid` (`addonid`),
  KEY `contentid` (`contentid`,`place`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `cmstop_mobile_content_category`;
CREATE TABLE `cmstop_mobile_content_category` (
  `contentid` mediumint(8) unsigned NOT NULL,
  `catid` smallint(5) unsigned NOT NULL,
  PRIMARY KEY (`contentid`,`catid`),
  KEY `catid` (`catid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `cmstop_mobile_content_log`;
CREATE TABLE `cmstop_mobile_content_log` (
  `logid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `contentid` mediumint(8) unsigned DEFAULT NULL,
  `action` varchar(50) NOT NULL,
  `title` varchar(200) NOT NULL,
  `url` varchar(255) DEFAULT NULL,
  `ip` varchar(255) NOT NULL,
  `created` int(10) unsigned NOT NULL,
  `createdby` mediumint(8) unsigned DEFAULT NULL,
  PRIMARY KEY (`logid`),
  KEY `contentid` (`contentid`),
  KEY `createdby` (`createdby`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `cmstop_mobile_content_related`;
CREATE TABLE `cmstop_mobile_content_related` (
  `relatedid` int(11) NOT NULL AUTO_INCREMENT,
  `contentid` mediumint(8) unsigned NOT NULL,
  `related_contentid` mediumint(8) unsigned NOT NULL,
  PRIMARY KEY (`relatedid`),
  KEY `contentid` (`contentid`),
  KEY `related_contentid` (`related_contentid`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `cmstop_mobile_content_stat`;
CREATE TABLE `cmstop_mobile_content_stat` (
  `contentid` mediumint(8) unsigned NOT NULL,
  `pv` int(10) unsigned NOT NULL,
  PRIMARY KEY (`contentid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `cmstop_mobile_content_stat_day`;
CREATE TABLE `cmstop_mobile_content_stat_day` (
  `contentid` mediumint(8) unsigned NOT NULL,
  `day` date NOT NULL,
  `pv` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`contentid`,`day`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `cmstop_mobile_feedback`;
CREATE TABLE `cmstop_mobile_feedback` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `email` varchar(100) NOT NULL DEFAULT '' COMMENT '联系邮箱',
  `content` varchar(512) NOT NULL DEFAULT '' COMMENT '意见内容',
  `app_version` varchar(100) NOT NULL DEFAULT '' COMMENT '客户端版本',
  `system_version` varchar(100) NOT NULL DEFAULT '' COMMENT '手机系统版本',
  `created` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `cmstop_mobile_link`;
CREATE TABLE `cmstop_mobile_link` (
  `contentid` mediumint(8) unsigned NOT NULL,
  `linkto` varchar(255) NOT NULL,
  PRIMARY KEY (`contentid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `cmstop_mobile_moreapp`;
CREATE TABLE `cmstop_mobile_moreapp` (
  `appid` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `iconurl` varchar(255) NOT NULL,
  `description` varchar(255) NOT NULL,
  `appstore_url` varchar(255) NOT NULL,
  `googleplay_url` varchar(255) NOT NULL,
  `sort` mediumint(8) unsigned NOT NULL,
  `pv` int(10) unsigned NOT NULL,
  PRIMARY KEY (`appid`),
  UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `cmstop_mobile_push_log`;
CREATE TABLE `cmstop_mobile_push_log` (
  `logid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) DEFAULT NULL,
  `content` varchar(255) NOT NULL,
  `contentid` mediumint(8) unsigned DEFAULT NULL,
  `modelid` tinyint(3) unsigned DEFAULT NULL,
  `devices` varchar(255) NOT NULL,
  `successed` smallint(1) unsigned NOT NULL,
  `created` int(10) unsigned NOT NULL,
  `createdby` mediumint(8) unsigned NOT NULL,
  `pushed` int(10) unsigned DEFAULT NULL,
  `pushedby` mediumint(8) unsigned DEFAULT NULL,
  PRIMARY KEY (`logid`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `cmstop_qrcode`;
CREATE TABLE `cmstop_qrcode` (
  `qrcodeid` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `short` varchar(100) NOT NULL,
  `str` varchar(255) NOT NULL,
  `path` varchar(255) NOT NULL,
  `note` text COLLATE utf8_bin,
  `type` varchar(50) DEFAULT NULL,
  `contentid` mediumint(9) unsigned DEFAULT NULL,
  `modelid` tinyint(4) unsigned DEFAULT NULL,
  `pv` int(10) unsigned NOT NULL DEFAULT '0',
  `created` int(10) unsigned NOT NULL,
  `createdby` mediumint(8) unsigned NOT NULL,
  PRIMARY KEY (`qrcodeid`),
  UNIQUE KEY `short` (`short`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `cmstop_qrcode_stat`;
CREATE TABLE `cmstop_qrcode_stat` (
  `qrcodeid` int(10) unsigned NOT NULL,
  `pv` int(10) unsigned NOT NULL,
  `platform` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`qrcodeid`,`platform`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `cmstop_mobile_slider`;
CREATE TABLE `cmstop_mobile_slider` (
  `contentid` mediumint(8) unsigned NOT NULL,
  `catid` smallint(5) unsigned NOT NULL,
  `title` varchar(200) NOT NULL,
  `thumb` varchar(255) NOT NULL,
  PRIMARY KEY (`contentid`,`catid`),
  KEY `catid` (`catid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `cmstop_mobile_special`;
CREATE TABLE `cmstop_mobile_special` (
  `contentid` mediumint(8) unsigned NOT NULL,
  `image` varchar(255) NOT NULL,
  PRIMARY KEY (`contentid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `cmstop_mobile_special_category`;
CREATE TABLE `cmstop_mobile_special_category` (
  `catid` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `contentid` mediumint(8) unsigned DEFAULT NULL,
  `name` varchar(255) NOT NULL,
  `size` smallint(5) unsigned NOT NULL DEFAULT '0',
  `sort` mediumint(8) unsigned NOT NULL,
  PRIMARY KEY (`catid`),
  KEY `contentid` (`contentid`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `cmstop_mobile_special_content`;
CREATE TABLE `cmstop_mobile_special_content` (
  `specialid` mediumint(8) unsigned NOT NULL,
  `catid` smallint(5) unsigned NOT NULL,
  `contentid` mediumint(8) unsigned NOT NULL,
  `sort` mediumint(8) unsigned NOT NULL,
  KEY `specialid` (`specialid`),
  KEY `catid` (`catid`),
  KEY `contentid` (`contentid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `cmstop_mobile_video`;
CREATE TABLE `cmstop_mobile_video` (
  `contentid` mediumint(8) unsigned NOT NULL,
  `video` varchar(255) NOT NULL,
  `playtime` smallint(5) unsigned NOT NULL,
  PRIMARY KEY (`contentid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

ALTER TABLE `cmstop_mobile_activity`
ADD CONSTRAINT `cmstop_mobile_activity_ibfk_1` FOREIGN KEY (`contentid`) REFERENCES `cmstop_mobile_content` (`contentid`) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE `cmstop_mobile_autofill`
ADD CONSTRAINT `cmstop_mobile_autofill_ibfk_1` FOREIGN KEY (`catid`) REFERENCES `cmstop_mobile_category` (`catid`) ON DELETE SET NULL ON UPDATE CASCADE;

ALTER TABLE `cmstop_mobile_autofill_log`
ADD CONSTRAINT `cmstop_mobile_autofill_log_ibfk_1` FOREIGN KEY (`catid`) REFERENCES `cmstop_mobile_category` (`catid`) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE `cmstop_mobile_article`
ADD CONSTRAINT `cmstop_mobile_article_ibfk_4` FOREIGN KEY (`contentid`) REFERENCES `cmstop_mobile_content` (`contentid`) ON DELETE CASCADE ON UPDATE NO ACTION;

ALTER TABLE `cmstop_mobile_category_bind`
ADD CONSTRAINT `cmstop_mobile_category_bind_ibfk_10` FOREIGN KEY (`catid`) REFERENCES `cmstop_category` (`catid`) ON DELETE CASCADE ON UPDATE NO ACTION,
ADD CONSTRAINT `cmstop_mobile_category_bind_ibfk_9` FOREIGN KEY (`mobile_catid`) REFERENCES `cmstop_mobile_category` (`catid`) ON DELETE CASCADE ON UPDATE NO ACTION;

ALTER TABLE `cmstop_mobile_category_priv`
ADD CONSTRAINT `cmstop_mobile_category_priv_ibfk_7` FOREIGN KEY (`catid`) REFERENCES `cmstop_mobile_category` (`catid`) ON DELETE CASCADE ON UPDATE NO ACTION,
ADD CONSTRAINT `cmstop_mobile_category_priv_ibfk_8` FOREIGN KEY (`userid`) REFERENCES `cmstop_member` (`userid`) ON DELETE CASCADE ON UPDATE NO ACTION;

ALTER TABLE `cmstop_mobile_content`
ADD CONSTRAINT `cmstop_mobile_content_ibfk_4` FOREIGN KEY (`topicid`) REFERENCES `cmstop_comment_topic` (`topicid`) ON DELETE SET NULL ON UPDATE CASCADE;

ALTER TABLE `cmstop_mobile_content_addon`
ADD CONSTRAINT `cmstop_mobile_content_addon_ibfk_1` FOREIGN KEY (`contentid`) REFERENCES `cmstop_mobile_content` (`contentid`) ON DELETE CASCADE ON UPDATE NO ACTION,
ADD CONSTRAINT `cmstop_mobile_content_addon_ibfk_2` FOREIGN KEY (`addonid`) REFERENCES `cmstop_mobile_addon` (`addonid`) ON DELETE CASCADE ON UPDATE NO ACTION;

ALTER TABLE `cmstop_mobile_content_category`
ADD CONSTRAINT `cmstop_mobile_content_category_ibfk_1` FOREIGN KEY (`contentid`) REFERENCES `cmstop_mobile_content` (`contentid`) ON DELETE CASCADE ON UPDATE NO ACTION,
ADD CONSTRAINT `cmstop_mobile_content_category_ibfk_2` FOREIGN KEY (`catid`) REFERENCES `cmstop_mobile_category` (`catid`) ON DELETE CASCADE ON UPDATE NO ACTION;

ALTER TABLE `cmstop_mobile_content_log`
ADD CONSTRAINT `cmstop_mobile_content_log_ibfk_5` FOREIGN KEY (`contentid`) REFERENCES `cmstop_mobile_content` (`contentid`) ON DELETE SET NULL ON UPDATE CASCADE,
ADD CONSTRAINT `cmstop_mobile_content_log_ibfk_6` FOREIGN KEY (`createdby`) REFERENCES `cmstop_member` (`userid`) ON DELETE SET NULL ON UPDATE CASCADE;

ALTER TABLE `cmstop_mobile_content_related`
ADD CONSTRAINT `cmstop_mobile_content_related_ibfk_3` FOREIGN KEY (`contentid`) REFERENCES `cmstop_mobile_content` (`contentid`) ON DELETE CASCADE ON UPDATE NO ACTION,
ADD CONSTRAINT `cmstop_mobile_content_related_ibfk_4` FOREIGN KEY (`related_contentid`) REFERENCES `cmstop_mobile_content` (`contentid`) ON DELETE CASCADE ON UPDATE NO ACTION;

ALTER TABLE `cmstop_mobile_content_stat`
ADD CONSTRAINT `cmstop_mobile_content_stat_ibfk_1` FOREIGN KEY (`contentid`) REFERENCES `cmstop_mobile_content` (`contentid`) ON DELETE CASCADE ON UPDATE NO ACTION;

ALTER TABLE `cmstop_mobile_content_stat_day`
ADD CONSTRAINT `cmstop_mobile_content_stat_day_ibfk_2` FOREIGN KEY (`contentid`) REFERENCES `cmstop_mobile_content` (`contentid`) ON DELETE CASCADE ON UPDATE NO ACTION;

ALTER TABLE `cmstop_mobile_link`
ADD CONSTRAINT `cmstop_mobile_link_ibfk_1` FOREIGN KEY (`contentid`) REFERENCES `cmstop_mobile_content` (`contentid`) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE `cmstop_qrcode_stat`
ADD CONSTRAINT `cmstop_qrcode_stat_ibfk_1` FOREIGN KEY (`qrcodeid`) REFERENCES `cmstop_qrcode` (`qrcodeid`) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE `cmstop_mobile_slider`
ADD CONSTRAINT `cmstop_mobile_slider_ibfk_3` FOREIGN KEY (`contentid`) REFERENCES `cmstop_mobile_content` (`contentid`) ON DELETE CASCADE ON UPDATE NO ACTION,
ADD CONSTRAINT `cmstop_mobile_slider_ibfk_4` FOREIGN KEY (`catid`) REFERENCES `cmstop_mobile_category` (`catid`) ON DELETE CASCADE ON UPDATE NO ACTION;

ALTER TABLE `cmstop_mobile_special`
ADD CONSTRAINT `cmstop_mobile_special_ibfk_4` FOREIGN KEY (`contentid`) REFERENCES `cmstop_mobile_content` (`contentid`) ON DELETE CASCADE ON UPDATE NO ACTION;

ALTER TABLE `cmstop_mobile_special_category`
ADD CONSTRAINT `cmstop_mobile_special_category_ibfk_3` FOREIGN KEY (`catid`) REFERENCES `cmstop_mobile_category` (`catid`) ON DELETE CASCADE ON UPDATE NO ACTION,
ADD CONSTRAINT `cmstop_mobile_special_category_ibfk_4` FOREIGN KEY (`contentid`) REFERENCES `cmstop_mobile_content` (`contentid`) ON DELETE CASCADE ON UPDATE NO ACTION;

ALTER TABLE `cmstop_mobile_special_content`
ADD CONSTRAINT `cmstop_mobile_special_content_ibfk_4` FOREIGN KEY (`specialid`) REFERENCES `cmstop_mobile_content` (`contentid`) ON DELETE CASCADE ON UPDATE NO ACTION,
ADD CONSTRAINT `cmstop_mobile_special_content_ibfk_6` FOREIGN KEY (`contentid`) REFERENCES `cmstop_mobile_content` (`contentid`) ON DELETE CASCADE ON UPDATE NO ACTION,
ADD CONSTRAINT `cmstop_mobile_special_content_ibfk_7` FOREIGN KEY (`catid`) REFERENCES `cmstop_mobile_special_category` (`catid`) ON DELETE CASCADE ON UPDATE NO ACTION;

ALTER TABLE `cmstop_mobile_video`
ADD CONSTRAINT `cmstop_mobile_video_ibfk_3` FOREIGN KEY (`contentid`) REFERENCES `cmstop_mobile_content` (`contentid`) ON DELETE CASCADE ON UPDATE CASCADE;

INSERT INTO `cmstop_setting` (`app`, `var`, `value`) VALUES
('mobile', 'aboutus', '关于我们'),
('mobile', 'android_url', ''),
('mobile', 'android_version', ''),
('mobile', 'android_version_description', ''),
('mobile', 'android_version_url', ''),
('mobile', 'api', 'array ()'),
('mobile', 'baoliao', 'array (\n  ''islogin'' => ''1'',\n  ''max_picsize'' => ''10'',\n  ''max_videosize'' => ''100'',\n)'),
('mobile', 'bootstrap', 'array (\n  ''logo'' => \n  array (\n    ''320*480'' => ''http://m.silkroad.news.cn/templates/default/app/images/boot/320x480.png'',\n    ''480*800'' => ''http://m.silkroad.news.cn/templates/default/app/images/boot/480x800.png'',\n    ''640*960'' => ''http://m.silkroad.news.cn/templates/default/app/images/boot/640x960.png'',\n    ''640*1136'' => ''http://m.silkroad.news.cn/templates/default/app/images/boot/640x1136.png'',\n  ),\n)'),
('mobile', 'category_version', '1'),
('mobile', 'comment', 'array (\n  ''open'' => ''1'',\n  ''islogin'' => ''1'',\n)'),
('mobile', 'content_description_length', '100'),
('mobile', 'content_title_length', '30'),
('mobile', 'disclaimer', '<section class="ui-wrap">\n		<div class="ui-disclaimer">\n			<p>就下列相关事宜的发生，思拓新闻不承担任何法律责任：</p>\n			\n			<p>a.思拓新闻根据法律规定或相关政府的要求提供您的个人信息；</p>\n			\n			<p>b.由于您将用户密码告知他人或与他人共享注册帐户，由此导致的任何个人信息的泄漏，或其他非因思拓新闻原因导致的个人信息的泄漏；</p>\n			\n			<p>c.任何第三方根据思拓新闻各服务条款及声明中所列明的情况使用您的个人信息，由此所产生的纠纷；</p>\n			\n			<p>d.任何由于黑客攻击、电脑病毒侵入或政府管制而造成的暂时性网站关闭；</p>\n			\n			<p>e.因不可抗力导致的任何后果；</p>\n			\n			<p>f.思拓新闻在各服务条款及声明中列明的使用方式或免责情形。</p>\n		</div>\n	</section>'),
('mobile', 'display', 'array (\n  ''thumb_align'' => ''right'',\n)'),
('mobile', 'iphone_url', ''),
('mobile', 'iphone_version', ''),
('mobile', 'iphone_version_description', ''),
('mobile', 'iphone_version_url', ''),
('mobile', 'server', 'array ()'),
('mobile', 'slider_default_num', '3'),
('mobile', 'square_version', '1'),
('mobile', 'weatherid', '101010100'),
('mobile', 'weather_version', '1'),
('mobile', 'weibo', 'array ()');

-- 手机版数据表 end

-- 微博表添加唯一字段
ALTER TABLE `cmstop_weibo` ADD UNIQUE KEY `type` (`type`,`openid`);

-- 投票默认开启验证码
REPLACE INTO `cmstop_setting` (`app`, `var`, `value`) VALUES ('vote', 'seccode', '1');

ALTER TABLE `cmstop_content` ADD `source_link` VARCHAR( 255 ) NULL DEFAULT NULL AFTER `sourceid`;

-- 组图增加展示方式设置
REPLACE INTO `cmstop_setting` (`app`, `var`, `value`) VALUES ('picture', 'refresh', '0');

-- CDN增加新接口
INSERT INTO `cmstop_cdn_type` (`tid`, `name`, `parameter`, `type`, `status`) VALUES
(NULL, '蓝汛(ChinaCache)_V4', '{"user":"\\u7528\\u6237\\u540d:","pswd":"\\u5bc6\\u7801:"}', 'chinacache_v4', 1);

-- 二维码菜单修改
INSERT INTO `cmstop_menu` (`menuid`, `parentid`, `parentids`, `childids`, `name`, `url`, `target`, `sort`) VALUES
(190, 6, '6', '191,192', '二维码', '?app=system&controller=qrcode&action=index', NULL, 3),
(191, 190, '6,190', NULL, '生成', '?app=system&controller=qrcode&action=generate', NULL, 1),
(192, 190, '6,190', NULL, '统计', '?app=system&controller=qrcode&action=stat', NULL, 2);

-- 增加评论来源表
CREATE TABLE `cmstop_comment_source` (
  `sourceid` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `identity` varchar(32) NOT NULL,
  `name` varchar(40) NOT NULL,
  `url` varchar(255) NOT NULL,
  `ico` varchar(255) DEFAULT NULL,
  `params` varchar(300) NOT NULL DEFAULT '[]',
  `state` tinyint(1) unsigned NOT NULL DEFAULT '1',
  PRIMARY KEY (`sourceid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;
ALTER TABLE `cmstop_comment` ADD `sourceid` SMALLINT UNSIGNED NULL DEFAULT NULL AFTER `topicid`;
ALTER TABLE `cmstop_comment` ADD `sourceinfo` VARCHAR( 300 ) NULL DEFAULT NULL ;
INSERT INTO `cmstop_comment_source` (`sourceid`, `identity`, `name`, `url`, `ico`, `params`, `state`) VALUES ('1', 'tencent_weibo', '腾讯微博', 'http://t.qq.com/', 'http://img.silkroad.news.cn/images/tencent.png', '[{"id":"verify_token","name":"腾讯微博令牌","value":""}]', 0);

-- API表增加字段记录灰色图标
ALTER TABLE `cmstop_api` ADD `icon_gray` VARCHAR( 255 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' AFTER `icon`;
UPDATE `cmstop_api` SET `icon_gray`='http://img.silkroad.news.cn/templates/default/img/qq_g.png' WHERE `interface`='tencent' LIMIT 1;
UPDATE `cmstop_api` SET `icon_gray`='http://img.silkroad.news.cn/templates/default/img/sina_g.gif' WHERE `interface`='sina' LIMIT 1;

-- 微博字段名称变更
ALTER TABLE `cmstop_weibo` CHANGE `type` `type` ENUM( 'sina_weibo', 'tencent_weibo' ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL ;
UPDATE `cmstop_weibo` SET `type` = 'tencent_weibo' WHERE `type` = 'tencent';
UPDATE `cmstop_weibo` SET `type` = 'sina_weibo' WHERE `type` = 'sina';
UPDATE `cmstop_api` SET `interface` = 'tencent_weibo' WHERE `interface` = 'tencent';
UPDATE `cmstop_api` SET `interface` = 'sina_weibo' WHERE `interface` = 'sina';

-- 属性表增加外键
ALTER TABLE `cmstop_property` ADD FOREIGN KEY ( `parentid` ) REFERENCES `cmstop`.`cmstop_property` ( `proid` ) ON DELETE CASCADE ON UPDATE CASCADE ;

-- 添加搜索云平台配置
INSERT INTO `cmstop_setting` (`app`, `var`, `value`) VALUES 
('cloud', 'spider_allowed', '1'),
('cloud', 'spider_address', 'http://api.cloud.cmstop.com:8001/spider/');

-- 手机版广告表
CREATE TABLE `cmstop_mobile_ad` (
  `adid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `identifier` varchar(40) NOT NULL,
  `data` varchar(200) NOT NULL DEFAULT '',
  PRIMARY KEY (`adid`),
  KEY `identifier` (`identifier`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;