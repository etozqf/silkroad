CREATE TABLE `cmstop_eventlive` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `bgimg` varchar(255) NOT NULL DEFAULT '',
  `introduction` varchar(300) NOT NULL DEFAULT '',
  `status` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '状态，0 正常，1 已关闭',
  PRIMARY KEY (`id`),
  KEY `id` (`id`,`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `cmstop_eventlive_member` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `liveid` int(11) DEFAULT NULL,
  `type` tinyint(1) NOT NULL DEFAULT '3' COMMENT '类型，1 主持人，2 直播员，3 嘉宾',
  `userid` int(11) unsigned DEFAULT NULL COMMENT '用户ID',
  `name` varchar(50) NOT NULL DEFAULT '' COMMENT '名称',
  `avatar` varchar(255) DEFAULT NULL COMMENT '头像',
  `biography` varchar(255) DEFAULT NULL COMMENT '简介',
  `phone` varchar(20) DEFAULT NULL COMMENT '电话',
  `email` varchar(255) DEFAULT NULL COMMENT '邮箱',
  `status` tinyint(1) unsigned DEFAULT '1' COMMENT '状态，0 正常，1 待邀请，2 邀请中，3 已删除',
  PRIMARY KEY (`id`),
  KEY `liveid` (`liveid`,`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `cmstop_eventlive_post` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `liveid` int(10) unsigned NOT NULL COMMENT '直播ID',
  `memberid` int(10) unsigned NOT NULL COMMENT '成员ID',
  `created` int(10) unsigned NOT NULL COMMENT '创建时间',
  `text` text NOT NULL COMMENT '正文',
  `image` varchar(255) NOT NULL DEFAULT '' COMMENT '图片',
  `video` text NOT NULL COMMENT '视频',
  `audio` varchar(255) NOT NULL DEFAULT '' COMMENT '音频',
  `audio_duration` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '音频时长',
  PRIMARY KEY (`id`),
  KEY `liveid` (`liveid`,`created`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `cmstop_eventlive_post_stat` (
  `postid` int(10) unsigned NOT NULL COMMENT '直播内容ID',
  `share` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '分享数',
  `support` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '支持数',
  PRIMARY KEY (`postid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `cmstop_eventlive_stat` (
  `liveid` int(10) unsigned NOT NULL COMMENT '直播ID',
  `share` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '分享数',
  `support` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '支持数',
  PRIMARY KEY (`liveid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `cmstop_mobile_classify` (
  `classifyid` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `modelid` tinyint(2) unsigned NOT NULL,
  `classname` varchar(30) NOT NULL,
  `disabled` tinyint(1) NOT NULL DEFAULT '0',
  `sort` int(10) unsigned NOT NULL,
  `created` int(10) unsigned NOT NULL,
  PRIMARY KEY (`classifyid`),
  KEY `modelid` (`modelid`),
  KEY `sort` (`sort`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

ALTER TABLE `cmstop_mobile_autofill` ADD  `lastpublished` int(10) unsigned NOT NULL DEFAULT '0';
ALTER TABLE `cmstop_mobile_autofill` ADD `modelid` varchar(20) NOT NULL;
ALTER TABLE `cmstop_mobile_content` ADD `classifyid`  smallint(5);
ALTER TABLE `cmstop_mobile_content` ADD COLUMN `qrcode` varchar(255) default NULL;
ALTER TABLE `cmstop_vote` ADD COLUMN `seccode_type` ENUM('normal','advanced') DEFAULT 'normal' NOT NULL;
ALTER TABLE `cmstop_admin` ADD COLUMN `password` varchar(32) DEFAULT '';
ALTER TABLE `cmstop_epaper` ADD COLUMN `content_source_end` varchar(255) NOT NULL DEFAULT '' ;
ALTER TABLE `cmstop_epaper` ADD COLUMN `content_source_start` varchar(255) NOT NULL DEFAULT '';
ALTER TABLE `cmstop_guestbook` ADD  `mobile` varchar(20) DEFAULT NULL;
ALTER TABLE `cmstop_spider_task` ADD COLUMN `titletags` varchar(255) DEFAULT NULL;
ALTER TABLE `cmstop_spider_task` ADD COLUMN `nottitletags` varchar(255) DEFAULT NULL;

-- 默认不更新新增广告图与尺寸
-- UPDATE `cmstop_setting` SET `value` = "array ( 'logo' => array ( '320*480' => 'http://m.silkroad.news.cn/templates/default/app/images/boot/320x480.png', '480*800' => 'http://m.silkroad.news.cn/templates/default/app/images/boot/480x800.png', '640*960' => 'http://m.silkroad.news.cn/templates/default/app/images/boot/640x960.png', '640*1136' => 'http://m.silkroad.news.cn/templates/default/app/images/boot/640x1136.png', '2048*1536' => 'http://m.silkroad.news.cn/templates/default/app/images/boot/2048x1536.png', '1536*2048' => 'http://m.silkroad.news.cn/templates/default/app/images/boot/1536x2048.png', '1536*768' => 'http://m.silkroad.news.cn/templates/default/app/images/boot/1024x768.png', '768*1024' => 'http://m.silkroad.news.cn/templates/default/app/images/boot/768x1024.png', '750*1334' => 'http://m.silkroad.news.cn/templates/default/app/images/boot/750x1334.png', '1242*2208' => 'http://m.silkroad.news.cn/templates/default/app/images/boot/1242x2208.png', ), )" WHERE `app` = 'mobile' AND `var` = 'bootstrap' LIMIT 1;
-- UPDATE `cmstop_mobile_style` SET `data` = '{ "nav":"#0a78cd", "button0":"#144B8F", "button1":"#0a78cd", "background":{ "320*480":"http://m.silkroad.news.cn/templates/default/app/images/background/00/320x480.png", "640*960":"http://m.silkroad.news.cn/templates/default/app/images/background/00/640x960.png", "640*1136":"http://m.silkroad.news.cn/templates/default/app/images/background/00/640x1136.png", "480*800":"http://m.silkroad.news.cn/templates/default/app/images/background/00/480x800.png", "750*1334":"http://m.silkroad.news.cn/templates/default/app/images/background/00/750x1334.png", "1242*2208":"http://m.silkroad.news.cn/templates/default/app/images/background/00/1242x2208.png" } }' WHERE `styleid` = 1;
-- UPDATE `cmstop_mobile_style` SET `data` = '{ "nav":"#38aa2f", "button0":"#0f7d05", "button1":"#38aa2f", "background":{ "320*480":"http://m.silkroad.news.cn/templates/default/app/images/background/09/320x480.png", "640*960":"http://m.silkroad.news.cn/templates/default/app/images/background/09/640x960.png", "640*1136":"http://m.silkroad.news.cn/templates/default/app/images/background/09/640x1136.png", "480*800":"http://m.silkroad.news.cn/templates/default/app/images/background/09/480x800.png", "750*1334":"http://m.silkroad.news.cn/templates/default/app/images/background/09/750x1334.png", "1242*2208":"http://m.silkroad.news.cn/templates/default/app/images/background/09/1242x2208.png" } }' WHERE `styleid` = 2;
-- UPDATE `cmstop_mobile_style` SET `data` = '{ "nav":"#2fbcab", "button0":"#00826e", "button1":"#2fbcab", "background":{ "320*480":"http://m.silkroad.news.cn/templates/default/app/images/background/02/320x480.png", "640*960":"http://m.silkroad.news.cn/templates/default/app/images/background/02/640x960.png", "640*1136":"http://m.silkroad.news.cn/templates/default/app/images/background/02/640x1136.png", "480*800":"http://m.silkroad.news.cn/templates/default/app/images/background/02/480x800.png", "750*1334":"http://m.silkroad.news.cn/templates/default/app/images/background/02/750x1334.png", "1242*2208":"http://m.silkroad.news.cn/templates/default/app/images/background/02/1242x2208.png" } }' WHERE `styleid` = 3;
-- UPDATE `cmstop_mobile_style` SET `data` = '{ "nav":"#ff4400", "button0":"#ff3300", "button1":"#ff4400", "background":{ "320*480":"http://m.silkroad.news.cn/templates/default/app/images/background/03/320x480.png", "640*960":"http://m.silkroad.news.cn/templates/default/app/images/background/03/640x960.png", "640*1136":"http://m.silkroad.news.cn/templates/default/app/images/background/03/640x1136.png", "480*800":"http://m.silkroad.news.cn/templates/default/app/images/background/03/480x800.png", "750*1334":"http://m.silkroad.news.cn/templates/default/app/images/background/03/750x1334.png", "1242*2208":"http://m.silkroad.news.cn/templates/default/app/images/background/03/1242x2208.png" } }' WHERE `styleid` = 4;
-- UPDATE `cmstop_mobile_style` SET `data` = '{ "nav":"#fabe00", "button0":"#cd8200", "button1":"#fabe00", "background":{ "320*480":"http://m.silkroad.news.cn/templates/default/app/images/background/04/320x480.png", "640*960":"http://m.silkroad.news.cn/templates/default/app/images/background/04/640x960.png", "640*1136":"http://m.silkroad.news.cn/templates/default/app/images/background/04/640x1136.png", "480*800":"http://m.silkroad.news.cn/templates/default/app/images/background/04/480x800.png", "750*1334":"http://m.silkroad.news.cn/templates/default/app/images/background/04/750x1334.png", "1242*2208":"http://m.silkroad.news.cn/templates/default/app/images/background/04/1242x2208.png" } }' WHERE `styleid` = 5;
-- UPDATE `cmstop_mobile_style` SET `data` = '{ "nav":"#303132", "button0":"#161717", "button1":"#303132", "background":{ "320*480":"http://m.silkroad.news.cn/templates/default/app/images/background/05/320x480.png", "640*960":"http://m.silkroad.news.cn/templates/default/app/images/background/05/640x960.png", "640*1136":"http://m.silkroad.news.cn/templates/default/app/images/background/05/640x1136.png", "480*800":"http://m.silkroad.news.cn/templates/default/app/images/background/05/480x800.png", "750*1334":"http://m.silkroad.news.cn/templates/default/app/images/background/05/750x1334.png", "1242*2208":"http://m.silkroad.news.cn/templates/default/app/images/background/05/1242x2208.png" } }' WHERE `styleid` = 6;
-- UPDATE `cmstop_mobile_style` SET `data` = '{ "nav":"#c80505", "button0":"#960505", "button1":"#c80505", "background":{ "320*480":"http://m.silkroad.news.cn/templates/default/app/images/background/07/320x480.png", "640*960":"http://m.silkroad.news.cn/templates/default/app/images/background/07/640x960.png", "640*1136":"http://m.silkroad.news.cn/templates/default/app/images/background/07/640x1136.png", "480*800":"http://m.silkroad.news.cn/templates/default/app/images/background/07/480x800.png", "750*1334":"http://m.silkroad.news.cn/templates/default/app/images/background/07/750x1334.png", "1242*2208":"http://m.silkroad.news.cn/templates/default/app/images/background/07/1242x2208.png" } }' WHERE `styleid` = 7;
-- UPDATE `cmstop_mobile_style` SET `data` = '{ "nav":"#682878", "button0":"#500e61", "button1":"#682878", "background":{ "320*480":"http://m.silkroad.news.cn/templates/default/app/images/background/08/320x480.png", "640*960":"http://m.silkroad.news.cn/templates/default/app/images/background/08/640x960.png", "640*1136":"http://m.silkroad.news.cn/templates/default/app/images/background/08/640x1136.png", "480*800":"http://m.silkroad.news.cn/templates/default/app/images/background/08/480x800.png", "750*1334":"http://m.silkroad.news.cn/templates/default/app/images/background/08/750x1334.png", "1242*2208":"http://m.silkroad.news.cn/templates/default/app/images/background/08/1242x2208.png" } }' WHERE `styleid` = 8;
-- UPDATE `cmstop_mobile_style` SET `data` = '{ "nav":"#ff7373", "button0":"#e7757e", "button1":"#ff7373", "background":{ "320*480":"http://m.silkroad.news.cn/templates/default/app/images/background/10/320x480.png", "640*960":"http://m.silkroad.news.cn/templates/default/app/images/background/10/640x960.png", "640*1136":"http://m.silkroad.news.cn/templates/default/app/images/background/10/640x1136.png", "480*800":"http://m.silkroad.news.cn/templates/default/app/images/background/10/480x800.png", "750*1334":"http://m.silkroad.news.cn/templates/default/app/images/background/10/750x1334.png", "1242*2208":"http://m.silkroad.news.cn/templates/default/app/images/background/10/1242x2208.png" } }' WHERE `styleid` = 9;

INSERT INTO `cmstop_menu` VALUES ('206', '173', '168,173', NULL, '分类管理', '?app=mobile&controller=setting&action=classify', NULL, '3');
INSERT INTO `cmstop_menu` VALUES ('182', '173', '168,173', NULL, '微信直播', '?app=mobile&controller=eventlive&action=wechat', NULL, '7');
INSERT INTO `cmstop_menu` (`menuid`, `parentid`, `parentids`, `childids`, `name`, `url`, `target`, `sort`) VALUES (null, null, null, null, '广告', 'http://adm.cmstop.cn/', null, '10');
DELETE FROM `cmstop_cron` WHERE `action` = 'cron_v56' LIMIT 1;

DELETE FROM `cmstop_app` WHERE `app` = 'weibo' LIMIT 1;
DELETE FROM `cmstop_menu` WHERE `url` LIKE '?app=weibo&%' LIMIT 2;