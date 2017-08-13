ALTER TABLE `cmstop_content` MODIFY COLUMN `thumb` char(100);

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
