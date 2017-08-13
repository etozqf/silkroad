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
('新浪微博', 'sina', '', 'http://img.db.silkroad.news.cn/templates/default/img/sina.gif', '{"client_id":"","client_secret":""}', 1, 0, 1, 1);

INSERT INTO `cmstop_api` (`name`, `interface`, `description`, `icon`, `authorize`, `islogin`, `isshare`, `state`, `sort`) VALUES
('QQ互联', 'qq', '', 'http://img.db.silkroad.news.cn/templates/default/img/qzone.gif', '{"client_id":"","client_secret":""}', 1, 0, 1, 2);