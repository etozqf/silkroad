
-- 自由列表改用自己的计划任务处理
ALTER TABLE `cmstop_freelist` ADD COLUMN `nextpublish`  int UNSIGNED NULL DEFAULT NULL COMMENT '下次生成时间' AFTER `published`;


-- url长度加至255
ALTER TABLE `cmstop_admin_log` CHANGE `url` `url` VARCHAR( 255 ) NOT NULL;
ALTER TABLE `cmstop_app` CHANGE `url` `url` VARCHAR( 255 ) NOT NULL;
ALTER TABLE `cmstop_category` CHANGE `url` `url` VARCHAR( 255 ) NOT NULL;
ALTER TABLE `cmstop_menu` CHANGE `url` `url` VARCHAR( 255 ) NOT NULL;
ALTER TABLE `cmstop_mymenu` CHANGE `url` `url` VARCHAR( 255 ) NOT NULL;
ALTER TABLE `cmstop_picture_group` CHANGE `url` `url` VARCHAR( 255 ) NOT NULL;
ALTER TABLE `cmstop_psn` CHANGE `url` `url` VARCHAR( 255 ) NOT NULL;
ALTER TABLE `cmstop_magazine` CHANGE `url` `url` VARCHAR( 255 ) NULL DEFAULT '';
ALTER TABLE `cmstop_magazine_edition` CHANGE `url` `url` VARCHAR( 255 ) NULL DEFAULT '';
ALTER TABLE `cmstop_paper` CHANGE `url` `url` VARCHAR( 255 ) NULL DEFAULT 'javascript:;' ;
ALTER TABLE `cmstop_paper_edition` CHANGE `url` `url` VARCHAR( 255 ) NULL DEFAULT 'javascript:;' ;
ALTER TABLE `cmstop_paper_edition_page` CHANGE `url` `url` VARCHAR( 255 ) NULL DEFAULT 'javascript:;';
ALTER TABLE `cmstop_content` MODIFY COLUMN `title`  varchar(200) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL AFTER `modelid`;
ALTER TABLE `cmstop_content` MODIFY COLUMN `thumb`  varchar(200) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL AFTER `color`;
ALTER TABLE `cmstop_content` MODIFY COLUMN `url`  varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL AFTER `topicid`;

-- 删除扩展字段方案管理
DELETE FROM `cmstop_menu` WHERE `menuid` = 131;

-- 修改sourceid太小
ALTER TABLE `cmstop_content` MODIFY COLUMN `sourceid`  mediumint(8) UNSIGNED NULL DEFAULT NULL AFTER `tags`;
ALTER TABLE `cmstop_source` MODIFY COLUMN `sourceid`  mediumint(8) UNSIGNED NOT NULL AUTO_INCREMENT FIRST ;

-- 删除我的版本功能
DELETE FROM `cmstop_menu` WHERE `menuid` = 133;

-- 删除百度新闻源功能
DELETE FROM `cmstop_setting` WHERE `app` = 'system' AND `var` = 'baidunews';
DELETE FROM `cmstop_cron` WHERE `cronid` = 15;
DELETE FROM `cmstop_menu` WHERE `menuid` = 41;
UPDATE `cmstop_menu` SET `childids` = REPLACE(`childids`, ',41,', ',') WHERE `menuid` = 8;

-- 增加link模型表
CREATE TABLE IF NOT EXISTS `cmstop_link` (
  `contentid` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `description` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`contentid`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;
 
-- 增加subtitle字段到content表
ALTER TABLE `cmstop_content` ADD `subtitle` VARCHAR( 120 ) NULL DEFAULT NULL AFTER `title` ;

-- 增加水印表(新水印方案)
DROP TABLE IF EXISTS `cmstop_watermark`;
CREATE TABLE IF NOT EXISTS `cmstop_watermark` (
  `watermarkid` smallint(3) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `image` varchar(255) NOT NULL,
  `ext` char(3) NOT NULL,
  `minwidth` smallint(3) unsigned NOT NULL,
  `minheight` smallint(3) unsigned NOT NULL,
  `position` tinyint(1) unsigned NOT NULL,
  `trans` smallint(3) unsigned NOT NULL,
  `quality` smallint(3) NOT NULL,
  `disable` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`watermarkid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- 增加 email 字段到 admin 表
ALTER TABLE `cmstop_admin` ADD `email` VARCHAR( 100 ) NULL DEFAULT NULL AFTER `birthday`;

-- 修改 member 表 email 字段长度为 100
ALTER TABLE `cmstop_member` MODIFY COLUMN `email` VARCHAR(100) NOT NULL;

-- 增加 status_old 字段到 content 表，记录移除操作前内容的状态以备恢复（从回收站中恢复）
ALTER TABLE `cmstop_content` ADD `status_old` tinyint(1) unsigned NOT NULL default '0' AFTER `status`;

-- 为附件表添加描述字段
ALTER TABLE `cmstop_attachment` ADD `description` VARCHAR( 255 ) NULL DEFAULT NULL AFTER `alias` ;

-- 增加手机版应用
INSERT INTO `cmstop_app` (`app`, `name`, `description`, `url`, `version`, `author`, `author_url`, `author_email`, `install_time`, `update_time`, `disabled`) VALUES
('mobile', '手机版', '', '', '1.0.0', 'cmstop', 'http://www.cmstop.com/', 'webmaster@cmstop.com', 1274811314, 1274811314, 0);

-- 增加手机版菜单
INSERT INTO `cmstop_menu` (`menuid`, `parentid`, `parentids`, `childids`, `name`, `url`, `target`, `sort`) VALUES
(0,'5','5',NULL,'手机版','?app=mobile&controller=setting&action=index',NULL,8);

-- 增加手机版默认设置
INSERT INTO `cmstop_setting` (`app`, `var`, `value`) VALUES
('mobile', 'open', '1'),
('mobile', 'open_base64', '1'),
('mobile', 'webname', 'CmsTop Mobile'),
('mobile', 'logo', '/ui/images/cmstop_logo.png'),
('mobile', 'weight', '60'),
('mobile', 'modelids', 'array (\n  0 => \'1\',\n  1 => \'2\',\n  2 => \'4\',\n)'),
('mobile', 'catids', 'array ()'),
('mobile', 'index_banner_section', ''),
('mobile', 'index_recommend_section', ''),
('mobile', 'index_recommend_size', '10'),
('mobile', 'index_recommend_type', '0'),
('mobile', 'index_recommend_weight', '60'),
('mobile', 'index_weight', '80'),
('mobile', 'list_weight', '60'),
('mobile', 'list_pagesize', '20'),
('mobile', 'comment_pagesize', '10'),
('mobile', 'comment_days', '30'),
('mobile', 'cache', '0'),
('mobile', 'image_banner_height', '180'),
('mobile', 'image_banner_width', '480'),
('mobile', 'image_list_height', '50'),
('mobile', 'image_list_width', '50'),
('mobile', 'image_content_big_height', '480'),
('mobile', 'image_content_big_width', '320'),
('mobile', 'image_content_small_height', '180'),
('mobile', 'image_content_small_width', '240'),
('mobile', 'image_picture_list_height', '100'),
('mobile', 'image_picture_list_width', '150'),
('mobile', 'image_picture_show_height', '480'),
('mobile', 'image_picture_show_width', '320');

-- 增加函数用于简化判断是否为手机版可播放视频
set global log_bin_trust_function_creators=TRUE;
delimiter ||
DROP FUNCTION IF EXISTS IS_MOBILE||
CREATE FUNCTION IS_MOBILE( x VARCHAR(255)) RETURNS TINYINT(1)
BEGIN
     DECLARE result TINYINT(1) DEFAULT 0;
     IF x IS NULL THEN
          RETURN result;
     ELSEIF LENGTH(x) = 1 THEN
          RETURN result;
     END IF;
     SET x = LCASE(x);
     IF LEFT(x,9) = '[ctvideo]' THEN
          SET result = 1;
     ELSEIF RIGHT(x,4) = '.mp4' THEN
          SET result = 1;
     END IF;
     RETURN result;
END;
||
delimiter ;

-- 增加 rule 字段到 cron 表，用来支持 crontab 语法的运行周期设置
ALTER TABLE `cmstop_cron` ADD `rule` varchar(100) DEFAULT NULL AFTER `minute`;

-- 修复一个计划任务的拼写错误
UPDATE `cmstop_cron` SET `name` = '网站首页' WHERE `cronid` = 25;

-- 添加自由列表的统一触发计划任务（之前是每添加一个自由列表就增加一个计划任务）
INSERT INTO `cmstop_cron` (`cronid`, `type`, `name`, `app`, `param`, `controller`, `action`, `lastrun`, `nextrun`, `mode`, `time`, `starttime`, `interval`, `times`, `already`, `endtime`, `day`, `weekday`, `hour`, `minute`, `disabled`, `hidden`) VALUES
(26, 'system', '自由列表定时更新', 'freelist', '', 'freelist', 'cron', 1293779128, 1293811860, 2, 0, 0, 1, 0, 0, 0, NULL, NULL, NULL, NULL, 0, 0);

-- 活动报名增加IP限制字段，以小时为单位
ALTER TABLE `cmstop_activity` ADD COLUMN `mininterval`  tinyint UNSIGNED NULL DEFAULT 0 AFTER `signstoped`;

-- 专题增加更多链接
ALTER TABLE `cmstop_special`
	ADD COLUMN `morelist_template` varchar(100)  COLLATE utf8_general_ci NULL COMMENT '更多列表模板' after `mode`,
	ADD COLUMN `morelist_pagesize` smallint(5) unsigned   NULL DEFAULT '50' COMMENT '更多列表分页数' after `morelist_template`,
	ADD COLUMN `morelist_maxpage` mediumint(8) unsigned   NULL DEFAULT '100' COMMENT '更多列表最多显示多少页' after `morelist_pagesize`, COMMENT='';

ALTER TABLE `cmstop_widget`
	ADD COLUMN `setting` longtext NULL after `data`;

-- 增加发稿设置菜单
INSERT INTO `cmstop_menu` (`menuid`, `parentid`, `parentids`, `childids`, `name`, `url`, `target`, `sort`) VALUES (NULL, '8', '8', NULL, '发稿设置', '?app=system&controller=setting&action=content', NULL, '2');

-- category 表增加 pagesize 列表页分页大小字段
ALTER TABLE `cmstop_category` ADD COLUMN `pagesize` smallint(5) unsigned NOT NULL DEFAULT '20' AFTER `urlrule_show`;

-- 增加队列和队列日志表
DROP TABLE IF EXISTS `cmstop_queue`;
CREATE TABLE `cmstop_queue`(
	`queueid` int(10) unsigned NOT NULL auto_increment COMMENT '队列ID',
	`engine` varchar(30) COLLATE utf8_bin NOT NULL COMMENT '队列引擎',
	`arguments` text COLLATE utf8_bin NULL COMMENT '执行参数',
	`nextrun` int(10) unsigned NOT NULL COMMENT '执行时间',
	`created` int(10) unsigned NOT NULL COMMENT '创建时间',
	`createdby` mediumint(8) unsigned NOT NULL COMMENT '创建人',
	`started` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '开始时间',
	`ended` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '结束时间',
	`result` text COLLATE utf8_bin NULL COMMENT '执行结果',
	`times` smallint(1) unsigned NOT NULL COMMENT '尝试次数',
	`status` tinyint(3) unsigned NOT NULL DEFAULT '1' COMMENT '状态',
	`delete` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '执行后删除',
	PRIMARY KEY (`queueid`) ,
	KEY `status`(`queueid`,`status`)
) ENGINE=InnoDB DEFAULT CHARSET='utf8';

DROP TABLE IF EXISTS `cmstop_queue_log`;
CREATE TABLE `cmstop_queue_log`(
	`logid` int(10) unsigned NOT NULL auto_increment,
	`queueid` int(10) unsigned NOT NULL,
	`action` varchar(30) COLLATE utf8_bin NOT NULL,
	`arguments` text COLLATE utf8_bin NULL,
	`result` text COLLATE utf8_bin NULL,
	`message` text COLLATE utf8_bin NULL,
	PRIMARY KEY (`logid`)
) ENGINE=InnoDB DEFAULT CHARSET='utf8';

-- 增加图片编辑器设置菜单
INSERT INTO `cmstop_menu` (`menuid`, `parentid`, `parentids`, `childids`, `name`, `url`, `target`, `sort`) VALUES
(0,8,'8',NULL,'图片编辑器','?app=system&controller=imgeditor&action=setting',NULL,4);

-- 删除草稿功能
DROP TABLE IF EXISTS `cmstop_article_draft`;
DELETE FROM `cmstop_menu` WHERE `menuid` = 132;

-- 添加删除旧日志的计划任务
INSERT INTO `cmstop_cron` (`type`, `name`, `app`, `param`, `controller`, `action`, `lastrun`, `nextrun`, `mode`, `time`, `starttime`, `interval`, `times`, `already`, `endtime`, `day`, `weekday`, `hour`, `minute`, `disabled`, `hidden`) VALUES
('system', '区块日志定时删除', 'page', '', 'section', 'clear_early_log', 0, 1334211120, 2, 0, 0, 1440, 0, 0, 0, NULL, NULL, NULL, NULL, 0, 0),
('system', '计划任务日志定时删除', 'system', '', 'cron', 'clear_early_log', 0, 1334126400, 2, 0, 0, 1440, 0, 0, 0, NULL, NULL, NULL, NULL, 0, 0);

-- 删除编辑器设置中的无用数据
DELETE FROM `cmstop_setting` WHERE `app` = 'editor' AND `var` = 'watermark';

-- 删除数据库管理功能
DELETE FROM `cmstop_menu` WHERE `menuid` = 73;
DELETE FROM `cmstop_menu` WHERE `menuid` = 74;
DELETE FROM `cmstop_menu` WHERE `menuid` = 75;
DELETE FROM `cmstop_menu` WHERE `menuid` = 76;

-- 删除财务管理
DELETE FROM `cmstop_menu` WHERE `menuid` = 125;
DELETE FROM `cmstop_menu` WHERE `menuid` = 126;
DELETE FROM `cmstop_menu` WHERE `menuid` = 127;
DELETE FROM `cmstop_menu` WHERE `menuid` = 128;
DELETE FROM `cmstop_menu` WHERE `menuid` = 129;

-- 增加CDN功能
INSERT INTO `cmstop_app` (`app`, `name`, `description`, `url`, `version`, `author`, `author_url`, `author_email`, `install_time`, `update_time`, `disabled`) VALUES
('cdn', 'CDN管理', '', '', '1.0.0', 'cmstop', 'http://www.cmstop.com/', 'webmaster@cmstop.com', 1274811314, 1274811314, 0);

CREATE TABLE `cmstop_cdn`(
  `cdnid` smallint(5) NOT NULL AUTO_INCREMENT,
  `name` varchar(20) NOT NULL,
  `tid` smallint(5) NOT NULL,
  PRIMARY KEY (`cdnid`)
) ENGINE=InnoDB DEFAULT CHARSET='utf8';

CREATE TABLE `cmstop_cdn_parameter`(
  `id`  smallint(5) NOT NULL AUTO_INCREMENT,
  `cdnid` smallint(5) NOT NULL,
  `key` varchar(20) NOT NULL,
  `value` varchar(200) NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET='utf8';

CREATE TABLE `cmstop_cdn_rules`(
  `id`  smallint(5) NOT NULL AUTO_INCREMENT,
  `cdnid` smallint(5) NOT NULL,
  `path` varchar(100) NULL DEFAULT NULL,
  `url` varchar(100) NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET='utf8';

CREATE TABLE `cmstop_cdn_type`(
  `tid` smallint(5) NOT NULL AUTO_INCREMENT,
  `name` varchar(20) NOT NULL,
  `parameter` varchar(200) NOT NULL,
  `type` varchar(20) NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 1,
  PRIMARY KEY (`tid`)
) ENGINE=InnoDB DEFAULT CHARSET='utf8';

INSERT INTO `cmstop_cdn_type` VALUES ('1', '蓝汛(ChinaCache)', '{\"user\":\"\\u7528\\u6237\\u540d\",\"pswd\":\"\\u5bc6\\u7801\"}', 'chinacache', '1');
INSERT INTO `cmstop_cdn_type` VALUES ('2', '网宿科技', '{\"user\":\"\\u7528\\u6237\\u540d\",\"pswd\":\"\\u5bc6\\u7801\"}', 'wscp', '1');

INSERT INTO `cmstop_menu` (`menuid`, `parentid`, `parentids`, `childids`, `name`, `url`, `target`, `sort`) VALUES
(0,'5','5',NULL,'CDN管理','?app=cdn&controller=cdn&action=index',NULL,0);
SET @menuid = LAST_INSERT_ID();
INSERT INTO `cmstop_menu` (`menuid`, `parentid`, `parentids`, `childids`, `name`, `url`, `target`, `sort`) VALUES
(0,@menuid,@menuid,NULL,'接口配置','?app=cdn&controller=setting&action=index',NULL,0);
SET @menuids = LAST_INSERT_ID();
UPDATE `cmstop_menu` SET `childids`=@menuids WHERE `menuid`=@menuid;

-- 增加专题计划任务
INSERT INTO `cmstop_cron` VALUES (0, 'system', '专题自动刷新', 'special', '', 'online', 'cron', '1334212297', '1334212740', '2', '0', '0', '1', '0', '1', '0', NULL, NULL, NULL, NULL, NULL, 0, 0);

-- 更新默认管理员 CmsTop 的创建时间
UPDATE `cmstop_admin` SET `created` = 1291791172, `createdby` = 1 WHERE `userid` = 1;

-- 对内容表增加topicid索引
ALTER TABLE `cmstop_content` ADD INDEX `topicid` USING BTREE (`topicid`);

-- 增加杂志发布点配置
INSERT INTO `cmstop_setting` (`app`, `var`, `value`) VALUES ('magazine', 'path', '{PSN:1}/magazine');

-- 增加报纸发布点配置
INSERT INTO `cmstop_setting` (`app`, `var`, `value`) VALUES ('paper', 'path', '{PSN:1}/paper');

-- 删除支付功能相关数据
DROP TABLE IF EXISTS `cmstop_pay_account`;
DROP TABLE IF EXISTS `cmstop_pay_charge`;
DROP TABLE IF EXISTS `cmstop_pay_payment`;
DROP TABLE IF EXISTS `cmstop_pay_platform`;
DELETE FROM `cmstop_app` WHERE `app` = 'pay';

-- 增加专题视频直播模块
INSERT INTO `cmstop_widget_engine` (`engineid`, `name`, `description`, `version`, `author`, `updateurl`, `installed`, `disabled`) VALUES 
(20, 'live', '视频直播', '1.0.0', 'cmstop', 'http://update.cmstop.com', NULL, 0);

-- 删除内容模型菜单
DELETE FROM `cmstop_menu` WHERE `menuid` = 37;
UPDATE `cmstop_menu` SET `childids` = REPLACE(`childids`, ',37,', ',') WHERE `menuid` = 8;