SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET FOREIGN_KEY_CHECKS=0;

USE `cmstop`;
CREATE TABLE `cmstop_admin_weight`(
  `userid` mediumint(8) unsigned NOT NULL,
  `weight` tinyint(2) unsigned NOT NULL,
  PRIMARY KEY (`userid`) 
) ENGINE=InnoDB DEFAULT CHARSET='utf8';

CREATE TABLE `cmstop_article_draft`(
  `draftid` int(10) unsigned NOT NULL auto_increment,
  `title` varchar(80) NOT NULL,
  `data` longtext,
  `created` int(10) unsigned NOT NULL,
  `createdby` mediumint(8) unsigned NOT NULL,
  PRIMARY KEY (`draftid`),
  KEY `createdby`(`createdby`,`created`) 
) ENGINE=InnoDB DEFAULT CHARSET='utf8';

CREATE TABLE `cmstop_article_version`(
  `versionid` int(10) unsigned NOT NULL auto_increment,
  `contentid` mediumint(8) unsigned NOT NULL,
  `title` varchar(80) NOT NULL,
  `data` longtext,
  `created` int(10) unsigned NOT NULL,
  `createdby` mediumint(8) unsigned NOT NULL,
  PRIMARY KEY (`versionid`),
  KEY `contentid`(`contentid`,`created`) 
) ENGINE=InnoDB DEFAULT CHARSET='utf8';

CREATE TABLE `cmstop_category_field`(
  `projectid` tinyint(3) unsigned NOT NULL,
  `catid` smallint(5) unsigned NOT NULL,
  PRIMARY KEY (`projectid`,`catid`),
  KEY `fid`(`projectid`),
  KEY `catid`(`catid`) 
) ENGINE=InnoDB DEFAULT CHARSET='utf8';

DROP TABLE `cmstop_comment`;
CREATE TABLE `cmstop_comment` (
  `commentid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `topicid` int(10) unsigned NOT NULL,
  `followid` int(10) unsigned DEFAULT NULL,
  `content` mediumtext,
  `ip` varchar(15) NOT NULL,
  `created` int(10) unsigned NOT NULL,
  `createdby` mediumint(8) unsigned DEFAULT NULL,
  `status` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `supports` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `reports` smallint(5) unsigned NOT NULL DEFAULT '0',
  `sensitive` tinyint(1) NOT NULL DEFAULT '0',
  `istop` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `anonymous` tinyint(1) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`commentid`),
  UNIQUE KEY `createdby` (`createdby`,`created`),
  KEY `topicid` (`topicid`,`istop`,`created`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `cmstop_comment_report`(
  `reportid` int(10) unsigned NOT NULL auto_increment,
  `commentid` int(10) unsigned NOT NULL,
  `ip` char(15) NOT NULL,
  `created` int(10) unsigned NOT NULL,
  `createdby` mediumint(8) unsigned NULL,
  PRIMARY KEY (`reportid`),
  KEY `commentid`(`commentid`,`createdby`) 
) ENGINE=InnoDB DEFAULT CHARSET='utf8';


CREATE TABLE `cmstop_comment_support`(
  `supportid` int(10) unsigned NOT NULL auto_increment,
  `commentid` int(10) unsigned NOT NULL,
  `ip` char(15) NOT NULL,
  `created` int(10) unsigned NOT NULL,
  `createdby` mediumint(8) unsigned NULL,
  PRIMARY KEY (`supportid`),
  KEY `commentid`(`commentid`,`createdby`) 
) ENGINE=InnoDB DEFAULT CHARSET='utf8';


CREATE TABLE `cmstop_comment_topic`(
  `topicid` int(10) unsigned NOT NULL auto_increment,
  `title` varchar(80) NOT NULL,
  `url` varchar(255) NOT NULL,
  `description` varchar(255) NOT NULL,
  `thumb` varchar(100) NOT NULL,
  `disabled` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `created` int(10) unsigned NOT NULL,
  `createdby` mediumint(8) unsigned NOT NULL,
  `updated` int(10) unsigned NULL,
  `updatedby` mediumint(8) unsigned NULL,
  `url_md5` varchar(32) NOT NULL,
  `comments` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `comments_pend` smallint(5) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`topicid`),
  KEY `url_md5`(`url_md5`) 
) ENGINE=InnoDB DEFAULT CHARSET='utf8';



CREATE TABLE `cmstop_content_meta`(
  `contentid` mediumint(8) unsigned NOT NULL,
  `data` text,
  PRIMARY KEY (`contentid`) 
) ENGINE=InnoDB DEFAULT CHARSET='utf8';

CREATE TABLE `cmstop_field`(
  `fieldid` tinyint(3) unsigned NOT NULL auto_increment,
  `field` varchar(10) NOT NULL,
  `projectid` tinyint(3) NOT NULL,
  `setting` text,
  `sort` int(10) unsigned NOT NULL,
  `created` int(10) unsigned NOT NULL,
  `createdby` mediumint(8) unsigned NOT NULL,
  PRIMARY KEY (`fieldid`),
  KEY `tid`(`field`) 
) ENGINE=InnoDB DEFAULT CHARSET='utf8';


CREATE TABLE `cmstop_field_project`(
  `projectid` tinyint(3) unsigned NOT NULL auto_increment,
  `name` varchar(30) NOT NULL,
  `description` varchar(255) NOT NULL,
  PRIMARY KEY (`projectid`) 
) ENGINE=InnoDB DEFAULT CHARSET='utf8';


CREATE TABLE `cmstop_freelist`(
  `flid` smallint(5) unsigned NOT NULL auto_increment,
  `gid` smallint(5) unsigned NULL,
  `name` varchar(30) NOT NULL,
  `path` varchar(100) NOT NULL,
  `type` tinyint(3) unsigned NOT NULL,
  `template` varchar(100) default NULL,
  `maxpage` tinyint(3) unsigned NOT NULL,
  `frequency` smallint(5) unsigned NOT NULL,
  `autopublish` tinyint(3) unsigned NOT NULL,
  `title` varchar(100) NOT NULL,
  `keywords` varchar(255) NOT NULL,
  `description` text,
  `filterules` text,
  `created` int(10) unsigned NOT NULL,
  `createdby` mediumint(8) unsigned NOT NULL,
  `modified` int(10) unsigned NULL,
  `modifiedby` mediumint(8) unsigned NULL,
  `published` int(10) unsigned NULL,
  PRIMARY KEY (`flid`),
  KEY `gid`(`gid`) 
) ENGINE=InnoDB DEFAULT CHARSET='utf8';

CREATE TABLE `cmstop_freelist_group`(
  `gid` smallint(5) unsigned NOT NULL auto_increment,
  `name` varchar(20) NOT NULL,
  PRIMARY KEY (`gid`) 
) ENGINE=InnoDB DEFAULT CHARSET='utf8';

CREATE TABLE `cmstop_member_bind`(
  `userid` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `bindid` char(25) default NULL,
  PRIMARY KEY (`userid`) 
) ENGINE=InnoDB DEFAULT CHARSET='utf8';


CREATE TABLE `cmstop_pay_account`(
  `userid` mediumint(8) unsigned NOT NULL,
  `balance` decimal(10,2) unsigned NOT NULL DEFAULT '0.00',
  `expense` decimal(10,2) unsigned NOT NULL DEFAULT '0.00',
  `updated` int(10) unsigned NULL,
  `updatedby` mediumint(8) unsigned NULL,
  `ip` char(15) default NULL,
  PRIMARY KEY (`userid`) 
) ENGINE=InnoDB DEFAULT CHARSET='utf8';

CREATE TABLE `cmstop_pay_charge`(
  `chargeid` int(10) unsigned NOT NULL auto_increment,
  `orderno` char(32) NOT NULL,
  `amount` decimal(10,2) unsigned NOT NULL DEFAULT '0.00',
  `apiid` tinyint(3) unsigned NOT NULL,
  `created` int(10) unsigned NOT NULL,
  `createdby` mediumint(8) unsigned NOT NULL,
  `createip` char(15) NOT NULL,
  `inputed` int(10) unsigned NULL,
  `inputedby` mediumint(8) unsigned NULL,
  `inputip` char(15) NOT NULL,
  `status` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `memo` varchar(255) NOT NULL,
  PRIMARY KEY (`chargeid`),
  KEY `createdby`(`createdby`,`created`),
  KEY `status`(`status`,`created`) 
) ENGINE=InnoDB DEFAULT CHARSET='utf8';

CREATE TABLE `cmstop_pay_payment`(
  `paymentid` int(10) unsigned NOT NULL auto_increment,
  `amount` decimal(8,2) unsigned NOT NULL DEFAULT '0.00',
  `title` varchar(255) NOT NULL,
  `url` varchar(255) default NULL,
  `created` int(10) unsigned NOT NULL,
  `createdby` mediumint(8) unsigned NOT NULL,
  `ip` char(15) NOT NULL,
  PRIMARY KEY (`paymentid`),
  KEY `createdby`(`createdby`,`created`) 
) ENGINE=InnoDB DEFAULT CHARSET='utf8';

CREATE TABLE `cmstop_pay_platform`(
  `apiid` tinyint(3) unsigned NOT NULL auto_increment,
  `name` varchar(50) NOT NULL,
  `logo` varchar(255) default NULL,
  `description` varchar(255) default NULL,
  `url` varchar(255) default NULL,
  `payfee` decimal(2,2) unsigned NOT NULL DEFAULT '0.00',
  `setting` text,
  `sort` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `disabled` tinyint(1) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`apiid`) 
) ENGINE=InnoDB DEFAULT CHARSET='utf8';

CREATE TABLE `cmstop_section_url`(
  `sectionid` smallint(5) unsigned NOT NULL,
  `url` char(32) NOT NULL,
  KEY `url`(`url`)
) ENGINE=InnoDB DEFAULT CHARSET='utf8';

INSERT INTO `cmstop_app` (`app`,`name`,`description`,`url`,`version`,`author`,`author_url`,`author_email`,`install_time`,`update_time`,`disabled`) VALUES
  ('field','扩展字段',' ',' ','1.0.0','cmstop','http://www.cmstop.com/','webmaster@cmstop.com',1274811314,1274811314,0),
  ('freelist','自由列表',' ','','1.0.0','cmstop','http://www.cmstop.com','webmaster@cmstop.com',1274811314,1274811314,0),
  ('pay','在线支付','在线支付','','1.0.0','cmstop','http://www.cmstop.com','webmaster@cmstop.com',1274811314,1274811314,0);

INSERT INTO `cmstop_menu` (`menuid`,`parentid`,`parentids`,`childids`,`name`,`url`,`target`,`sort`) VALUES
  (121,5,'5','122,123','自由列表','?app=freelist&controller=freelist&action=index',NULL,15),
  (122,121,'5,121',NULL,'列表管理','?app=freelist&controller=freelist&action=index',NULL,0),
  (123,121,'5,121',NULL,'分组管理','?app=freelist&controller=group&action=index',NULL,0),
  (124,84,'5,84',NULL,'话题管理','?app=comment&controller=comment&action=topic',NULL,1),
  (125,5,'5','126,127,128,129','财务管理','?app=pay&controller=charge',NULL,0),
  (126,125,'5,125',NULL,'充值记录','?app=pay&controller=charge',NULL,0),
  (127,125,'5,125',NULL,'消费记录','?app=pay&controller=payment',NULL,0),
  (128,125,'5,125',NULL,'会员账户','?app=pay&controller=account',NULL,0),
  (129,125,'5,125',NULL,'支付平台','?app=pay&controller=platform',NULL,0),
  (130,5,'5','131','扩展字段','?app=field&controller=project&action=index',NULL,0),
  (131,130,'5,130',NULL,'方案管理','?app=field&controller=project&action=index',NULL,0),
  (132,1,'1',NULL,'我的草稿','?app=system&controller=my&action=draft',NULL,0),
  (133,1,'1',NULL,'我的版本','?app=system&controller=my&action=version',NULL,0);
UPDATE `cmstop_menu` SET `childids` = '132,133,22,25,28,24,23,26,21,27' WHERE `cmstop_menu`.`menuid` =1;
UPDATE `cmstop_menu` SET `childids` = '81,125,130,80,82,117,84,88,91,95,99,83,79,78,77,102,121,126,127,128,129,131,119,120,124,85,86,87,89,90,92,93,94,96,97,98,100,101,104,103,122,123' WHERE `cmstop_menu`.`menuid` =5;
UPDATE `cmstop_menu` SET `childids` = '124,85,86,87' WHERE `cmstop_menu`.`menuid` =84;

ALTER TABLE `cmstop_interview` 
  ADD COLUMN `visitorchat` tinyint(1) unsigned NOT NULL default '0' after `allowchat`;

ALTER TABLE `cmstop_category` 
  ADD COLUMN `allowcomment` tinyint(1) unsigned NOT NULL default '0' after `enablecontribute`;

ALTER TABLE `cmstop_content` 
  ADD COLUMN `topicid` int(10) unsigned default NULL after `spaceid`;

ALTER TABLE `cmstop_member_group` 
  DROP COLUMN `tabview`;

ALTER TABLE `cmstop_comment_report`
  ADD CONSTRAINT `cmstop_comment_report_ibfk_1` FOREIGN KEY (`commentid`) REFERENCES `cmstop_comment` (`commentid`) ON DELETE CASCADE;

ALTER TABLE `cmstop_freelist`
  ADD CONSTRAINT `cmstop_freelist_ibfk_1` FOREIGN KEY (`gid`) REFERENCES `cmstop_freelist_group` (`gid`) ON DELETE SET NULL;

ALTER TABLE `cmstop_member_bind`
  ADD CONSTRAINT `cmstop_member_bind_ibfk_1` FOREIGN KEY (`userid`) REFERENCES `cmstop_member` (`userid`) ON DELETE CASCADE;

ALTER TABLE `cmstop_interview` ADD `template` VARCHAR( 100 ) DEFAULT NULL;
ALTER TABLE `cmstop_survey` ADD `template` VARCHAR( 100 ) DEFAULT NULL;

ALTER TABLE `cmstop_widget` 
  ADD COLUMN `skin` text after `data`;

-- 增加视频管理与配置菜单
INSERT INTO cmstop_menu VALUES ('134', '5', '5', '135,136', '视频管理', '?app=video&controller=vms&action=index', null, '0');
INSERT INTO cmstop_menu VALUES ('135', '134', '5,134', null, '视频管理', '?app=video&controller=vms&action=index', null, '1');
INSERT INTO cmstop_menu VALUES ('136', '134', '5,134', null, '接口配置', '?app=video&controller=vms&action=setting', null, '2');

-- 增加匿名回复字段
ALTER TABLE `cmstop_comment` ADD `anonymous` tinyint(1) unsigned NOT NULL DEFAULT '0';

-- 修正digg记录的外键绑定
ALTER TABLE `cmstop_digg` DROP FOREIGN KEY `cmstop_digg_ibfk_1`;
ALTER TABLE `cmstop_digg` ADD CONSTRAINT `cmstop_digg_ibfk_1` FOREIGN KEY (`contentid`) REFERENCES `cmstop_content` (`contentid`) ON DELETE CASCADE;
ALTER TABLE `cmstop_digg_log` DROP FOREIGN KEY `cmstop_digg_log_ibfk_1`;
ALTER TABLE `cmstop_digg_log` ADD CONSTRAINT `cmstop_digg_log_ibfk_1` FOREIGN KEY (`contentid`) REFERENCES `cmstop_content` (`contentid`) ON DELETE CASCADE;

-- 修正freelist功能无法使用
ALTER TABLE `cmstop_freelist` 
	CHANGE `flid` `flid` smallint(5) unsigned   NOT NULL auto_increment COMMENT '自由列表id' first, 
	CHANGE `gid` `gid` smallint(5) unsigned   NULL COMMENT '自由列表名称' after `flid`, 
	CHANGE `name` `name` varchar(30)  COLLATE utf8_general_ci NOT NULL COMMENT '列表页名称' after `gid`, 
	ADD COLUMN `filename` varchar(30)  COLLATE utf8_general_ci NOT NULL COMMENT '文件名称' after `name`, 
	CHANGE `path` `path` varchar(100)  COLLATE utf8_general_ci NOT NULL COMMENT '网址' after `filename`, 
	CHANGE `type` `type` tinyint(3) unsigned   NOT NULL COMMENT '列表类型' after `path`, 
	CHANGE `template` `template` varchar(100)  COLLATE utf8_general_ci NULL COMMENT '页面模版' after `type`, 
	CHANGE `maxpage` `maxpage` tinyint(3) unsigned   NOT NULL COMMENT '最大生成页数' after `template`, 
	ADD COLUMN `pagesize` tinyint(3) unsigned   NOT NULL DEFAULT '20' COMMENT '分页每页条数' after `maxpage`, 
	CHANGE `frequency` `frequency` smallint(5) unsigned   NOT NULL COMMENT '生成频率' after `pagesize`, 
	CHANGE `autopublish` `autopublish` tinyint(3) unsigned   NOT NULL COMMENT '手动更新' after `frequency`, 
	CHANGE `title` `title` varchar(100)  COLLATE utf8_general_ci NOT NULL COMMENT '页面标题' after `autopublish`, 
	CHANGE `keywords` `keywords` varchar(255)  COLLATE utf8_general_ci NOT NULL COMMENT '关键字' after `title`, 
	CHANGE `description` `description` text  COLLATE utf8_general_ci NOT NULL COMMENT '描述' after `keywords`, 
	CHANGE `filterules` `filterules` text  COLLATE utf8_general_ci NOT NULL COMMENT '筛选器规则json数据' after `description`, 
	CHANGE `created` `created` int(10) unsigned   NOT NULL COMMENT '创建时间' after `filterules`, 
	CHANGE `createdby` `createdby` mediumint(8) unsigned   NOT NULL COMMENT '创建者' after `created`, 
	CHANGE `modified` `modified` int(10) unsigned   NULL COMMENT '修改时间' after `createdby`, 
	CHANGE `modifiedby` `modifiedby` mediumint(8) unsigned   NULL COMMENT '修改者' after `modified`, 
	CHANGE `published` `published` int(10) unsigned   NULL COMMENT '生成时间' after `modifiedby`, COMMENT='';
