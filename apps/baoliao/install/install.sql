SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for `cmstop_baoliao`
-- ----------------------------
DROP TABLE IF EXISTS `cmstop_baoliao`;
CREATE TABLE `cmstop_baoliao` (
  `baoliaoid` mediumint(8) unsigned NOT NULL auto_increment,
  `title` varchar(120) NOT NULL,
  `content` mediumtext,
  `image` varchar(500) default NULL,
  `video` varchar(500) default NULL,
  `topicid` mediumint(8) unsigned default NULL,
  `pv` mediumint(8) unsigned NOT NULL default '0',
  `userid` mediumint(8) unsigned default NULL,
  `name` varchar(40) default NULL,
  `email` varchar(255) default NULL,
  `phone` varchar(20) default NULL,
  `qq` varchar(255) default NULL,
  `address` varchar(200) default NULL,
  `createtime` int(10) unsigned NOT NULL,
  `ip` int(10) NOT NULL,
  `reply` tinyint(1) unsigned NOT NULL default '0',
  `replytext` mediumtext,
  `replytime` int(10) unsigned NOT NULL default '10',
  `related` varchar(500) default NULL,
  PRIMARY KEY  (`baoliaoid`),
  KEY `topicid` (`topicid`),
  KEY `reply` (`reply`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

INSERT INTO `cmstop_cron` (`cronid`, `type`, `name`, `app`, `param`, `controller`, `action`, `lastrun`, `nextrun`, `mode`, `time`, `starttime`, `interval`, `times`, `already`, `endtime`, `day`, `weekday`, `hour`, `minute`, `rule`, `disabled`, `hidden`) VALUES 
(NULL, 'system', '报料统计', 'baoliao', '', 'baoliao', 'cron_pv', 1348932032, 1348932660, 2, 0, 0, 10, 0, 3, 0, '', '', '3', '0', NULL, 0, 0);


INSERT INTO `cmstop_setting` (`app`, `var`, `value`) VALUES
('baoliao', 'admin', '管理员'),
('baoliao', 'allowedcomment', '0'),
('baoliao', 'hasseccode', '0'),
('baoliao', 'item', '["name","email","phone"]'),
('baoliao', 'max_picnum', '3'),
('baoliao', 'must_item', '["name","email","phone"]'),
('baoliao', 'notice', '0'),
('baoliao', 'notice_content', '尊敬的用户{username}，您好：
您在《{site}》网站上有关“{title}”的报料已经回复，请点击链接查看详情。{url}'),
('baoliao', 'notice_title', '报料回复通知'),
('baoliao', 'onlymember', '0'),
('baoliao', 'onlyreply', '1'),
('baoliao', 'postto', '');


SET FOREIGN_KEY_CHECKS=1;