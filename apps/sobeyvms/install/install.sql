INSERT INTO `cmstop_cron` (`cronid`, `type`, `name`, `app`, `param`, `controller`, `action`, `lastrun`, `nextrun`, `mode`, `time`, `starttime`, `interval`, `times`, `already`, `endtime`, `day`, `weekday`, `hour`, `minute`, `rule`, `disabled`, `hidden`) VALUES
(null, 'system', '索贝VMS定时抓取', 'sobeyvms', '', 'index', 'cron_push', NULL, 1389853200, 2, 0, 0, 30, 0, 1, 0, NULL, NULL, NULL, NULL, '', 0, 0),
(null, 'system', '索贝VMS自动同步', 'sobeyvms', '', 'index', 'cron_sync', NULL, 1389851760, 2, 0, 0, 5, 0, 1, 0, NULL, NULL, NULL, NULL, '', 0, 0);

CREATE TABLE IF NOT EXISTS `cmstop_sobeyvms` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `catalogId` int(10) unsigned NOT NULL,
  `videoId` varchar(32) NOT NULL,
  `catid` int(10) unsigned NOT NULL,
  `published` int(10) unsigned NOT NULL,
  `title` varchar(200) NOT NULL,
  `vodAddress` text NOT NULL,
  `playerCodeList` text NOT NULL,
  `state` enum('waited','captured','failed') NOT NULL DEFAULT 'waited',
  `referenceid` int(10) unsigned NOT NULL DEFAULT '0',
  `exception` varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  UNIQUE KEY `videoId` (`videoId`),
  KEY `catid` (`catalogId`),
  KEY `state` (`state`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;