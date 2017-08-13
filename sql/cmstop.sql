SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET FOREIGN_KEY_CHECKS=0;
SET time_zone = "+00:00";

CREATE TABLE IF NOT EXISTS `cmstop_aca` (
  `acaid` smallint(5) unsigned NOT NULL auto_increment,
  `parentid` smallint(5) unsigned default NULL,
  `app` varchar(15) NOT NULL,
  `controller` varchar(30) default NULL,
  `action` text,
  `name` varchar(30) NOT NULL,
  PRIMARY KEY  (`acaid`),
  KEY `parentid` (`parentid`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1241 ;

INSERT INTO `cmstop_aca` (`acaid`, `parentid`, `app`, `controller`, `action`, `name`) VALUES
(1, NULL, 'system', NULL, NULL, '系统'),
(2, 1, 'system', 'aca', NULL, '权限'),
(3, 1, 'system', 'administrator', NULL, '管理员'),
(4, 1, 'system', 'adminlog', NULL, '操作日志'),
(5, 1, 'system', 'attachment', NULL, '附件管理'),
(16, 1, 'system', 'cache', NULL, '更新缓存'),
(17, 1, 'system', 'category', NULL, '栏目管理'),
(19, 17, 'system', 'category', 'add', '新建'),
(20, 17, 'system', 'category', 'edit', '编辑'),
(21, 17, 'system', 'category', 'delete', '删除'),
(22, 17, 'system', 'category', 'move', '移动'),
(23, 17, 'system', 'category', 'repair', '修复'),
(26, 1, 'system', 'category_priv', NULL, '栏目权限'),
(27, 26, 'system', 'category_priv', 'index', '浏览'),
(28, 26, 'system', 'category_priv', 'add', '添加'),
(29, 26, 'system', 'category_priv', 'delete', '删除'),
(30, 1, 'system', 'content', NULL, '内容管理'),
(31, 30, 'system', 'content', 'index,page,search,islock', '浏览'),
(32, 30, 'system', 'content', 'edit,miniedit', '编辑'),
(33, 30, 'system', 'content', 'delete', '删除至回收站'),
(34, 30, 'system', 'content', 'move', '移动'),
(35, 30, 'system', 'content', 'reference', '引用'),
(36, 30, 'system', 'content', 'clear', '彻底删除'),
(37, 1, 'system', 'content_log', NULL, '内容日志'),
(38, 37, 'system', 'content_log', 'index,page,search', '浏览'),
(39, 37, 'system', 'content_log', 'delete', '删除'),
(40, 1, 'system', 'content_note', NULL, '批注'),
(41, 40, 'system', 'content_note', 'index,page,search', '浏览'),
(42, 40, 'system', 'content_note', 'add', '添加'),
(43, 40, 'system', 'content_note', 'delete', '删除'),
(44, 1, 'system', 'content_version', NULL, '版本'),
(45, 44, 'system', 'content_version', 'index,view', '浏览'),
(46, 44, 'system', 'content_version', 'add', '添加'),
(47, 44, 'system', 'content_version', 'restore', '恢复'),
(48, 44, 'system', 'content_version', 'delete', '删除'),
(49, 1, 'system', 'cron', NULL, '计划任务'),
(51, 1, 'system', 'department', NULL, '部门'),
(52, 1, 'system', 'dsn', NULL, '数据源管理'),
(57, 1, 'system', 'file', NULL, '文件'),
(58, 1, 'system', 'filterword', NULL, '敏感词'),
(63, 1, 'system', 'html', NULL, '批量生成网页'),
(69, 1, 'system', 'ipbanned', NULL, 'IP 禁止'),
(70, 1, 'system', 'keylink', NULL, '关键词链接'),
(74, 1, 'system', 'menu', NULL, '菜单'),
(75, 1, 'system', 'model', NULL, '内容模型'),
(76, 1, 'system', 'port', NULL, '外部数据端口'),
(80, 1, 'system', 'property', NULL, '自定义属性'),
(87, 1, 'system', 'psn', NULL, '发布点'),
(96, 1, 'system', 'rank', NULL, '排行榜'),
(97, 1, 'system', 'related', NULL, '相关内容'),
(98, 1, 'system', 'role', NULL, '角色'),
(99, 1, 'system', 'score', NULL, '评分'),
(100, 99, 'system', 'score', 'index,edit,editor,score_edit', '评分'),
(101, 99, 'system', 'score', 'view,page', '查看'),
(102, 1, 'system', 'setting', NULL, '全局配置'),
(103, 1, 'system', 'sitemaps', NULL, '网站地图'),
(104, 1, 'system', 'source', NULL, '来源'),
(109, 1, 'system', 'stat', NULL, '统计'),
(110, 1, 'system', 'stat_examine', NULL, '编辑考核'),
(111, 1, 'system', 'tag', NULL, 'Tags'),
(116, 1, 'system', 'template', NULL, '模板管理'),
(136, 1, 'system', 'workflow', NULL, '工作流'),
(137, NULL, 'article', NULL, NULL, '文章'),
(138, 137, 'article', 'article', NULL, '文章'),
(139, 138, 'article', 'article', 'add,miniadd,related,thumb', '添加'),
(141, 138, 'article', 'article', 'view', '查看'),
(143, 138, 'article', 'article', 'copy', '复制'),
(145, 138, 'article', 'article', 'reference', '引用'),
(146, 138, 'article', 'article', 'move', '移动'),
(148, 137, 'article', 'html', NULL, '生成'),
(149, 137, 'article', 'setting', NULL, '设置'),
(150, NULL, 'picture', NULL, NULL, '组图'),
(151, 150, 'picture', 'html', NULL, '生成'),
(161, 150, 'picture', 'setting', NULL, '设置'),
(162, NULL, 'video', NULL, NULL, '视频'),
(163, 162, 'video', 'html', NULL, '生成'),
(164, 162, 'video', 'video', NULL, '管理'),
(165, 164, 'video', 'video', 'add,related', '添加'),
(166, 164, 'video', 'video', 'edit,miniedit,related', '编辑'),
(167, 164, 'video', 'video', 'view', '查看'),
(169, 164, 'video', 'video', 'reference', '引用'),
(170, 164, 'video', 'video', 'move', '移动'),
(171, 164, 'video', 'video', 'upload', '上传'),
(173, 162, 'video', 'vms', NULL, '视频库'),
(178, NULL, 'activity', NULL, NULL, '活动'),
(179, 178, 'activity', 'activity', NULL, '管理'),
(180, 179, 'activity', 'activity', 'add,related', '添加'),
(181, 179, 'activity', 'activity', 'edit,miniedit,related', '编辑'),
(182, 179, 'activity', 'activity', 'view', '查看'),
(183, 179, 'activity', 'activity', 'viewsigns', '查看报名'),
(185, 179, 'activity', 'activity', 'reference', '引用'),
(186, 179, 'activity', 'activity', 'move', '移动'),
(187, 179, 'activity', 'activity', 'stop,unstop', '开始/结束'),
(188, 178, 'activity', 'html', NULL, '生成'),
(190, 178, 'activity', 'sign', NULL, '报名管理'),
(191, 190, 'activity', 'sign', 'view,page', '查看'),
(192, 190, 'activity', 'sign', 'edit', '编辑'),
(193, 190, 'activity', 'sign', 'pass,unpass', '审核'),
(195, 190, 'activity', 'sign', 'delete', '删除'),
(196, 190, 'activity', 'sign', 'export', '导出'),
(197, NULL, 'vote', NULL, NULL, '投票'),
(198, 197, 'vote', 'html', NULL, '生成'),
(199, 197, 'vote', 'log', NULL, '投票记录'),
(200, 197, 'vote', 'vote', NULL, '管理'),
(201, 200, 'vote', 'vote', 'add,related,addlink,upload', '添加'),
(202, 200, 'vote', 'vote', 'edit,miniedit,related,addlink,upload', '编辑'),
(205, 200, 'vote', 'vote', 'reference', '引用'),
(206, 200, 'vote', 'vote', 'move', '移动'),
(207, NULL, 'survey', NULL, NULL, '调查问卷'),
(208, 207, 'survey', 'export', NULL, '导出'),
(209, 207, 'survey', 'html', NULL, '生成'),
(210, 207, 'survey', 'question', NULL, '表单设计'),
(217, 207, 'survey', 'report', NULL, '分析报告'),
(225, 207, 'survey', 'survey', NULL, '管理'),
(226, 225, 'survey', 'survey', 'index,page,search,view', '浏览'),
(227, 225, 'survey', 'survey', 'add,related', '添加'),
(228, 225, 'survey', 'survey', 'edit,miniedit,related', '编辑'),
(229, 225, 'survey', 'survey', 'data_clear', '清空调查记录'),
(231, 225, 'survey', 'survey', 'reference', '引用'),
(232, 225, 'survey', 'survey', 'move', '移动'),
(233, NULL, 'interview', NULL, NULL, '访谈'),
(234, 233, 'interview', 'chat', NULL, '文字实录'),
(239, 233, 'interview', 'html', NULL, '生成'),
(240, 233, 'interview', 'interview', NULL, '管理'),
(241, 240, 'interview', 'interview', 'index,page,search,view', '浏览'),
(242, 240, 'interview', 'interview', 'add,upload', '添加'),
(243, 240, 'interview', 'interview', 'edit,miniedit,state,upload', '编辑'),
(244, 240, 'interview', 'interview', 'review,view_review', '精彩观点'),
(245, 240, 'interview', 'interview', 'notice,view_notice', '滚动公告'),
(246, 240, 'interview', 'interview', 'picture,view_picture', '图片报道'),
(248, 240, 'interview', 'interview', 'reference', '引用'),
(249, 240, 'interview', 'interview', 'move', '移动'),
(251, 233, 'interview', 'question', NULL, '网友互动'),
(260, NULL, 'link', NULL, NULL, '链接'),
(261, 260, 'link', 'link', NULL, '管理'),
(262, 261, 'link', 'link', 'add,related', '添加'),
(263, 261, 'link', 'link', 'edit,related', '编辑'),
(264, 261, 'link', 'link', 'view', '查看'),
(266, 261, 'link', 'link', 'reference', '引用'),
(267, 261, 'link', 'link', 'move', '移动'),
(268, NULL, 'comment', NULL, NULL, '评论'),
(269, 268, 'comment', 'comment', NULL, '管理'),
(270, 269, 'comment', 'comment', 'index,page', '浏览'),
(271, 269, 'comment', 'comment', 'edit', '编辑'),
(272, 269, 'comment', 'comment', 'check', '通过'),
(273, 269, 'comment', 'comment', 'delete', '删除'),
(274, 269, 'comment', 'comment', 'ip_edit', '修改 IP'),
(275, 269, 'comment', 'comment', 'ip_disallow', 'IP 锁定'),
(276, 269, 'comment', 'comment', 'ip_delete', '删除指定 IP 所有评论'),
(277, 269, 'comment', 'comment', 'top', '评论置顶'),
(278, 269, 'comment', 'comment', 'canceltop', '取消置顶'),
(279, 269, 'comment', 'comment', 'topic,topic_page', '话题管理'),
(280, 269, 'comment', 'comment', 'topic_add', '添加话题'),
(281, 269, 'comment', 'comment', 'topic_enable', '话题开启'),
(282, 269, 'comment', 'comment', 'topic_disable', '话题关闭'),
(283, 269, 'comment', 'comment', 'topic_del', '删除话题'),
(284, 269, 'comment', 'comment', 'report,report_page', '举报评论管理'),
(285, 269, 'comment', 'comment', 'report_reset', '举报重置'),
(286, 269, 'comment', 'comment', 'sensitive,sensitive_page', '敏感评论'),
(287, 269, 'comment', 'comment', 'sensitive_reset', '敏感重置'),
(288, 268, 'comment', 'setting', NULL, '设置'),
(289, NULL, 'contribution', NULL, NULL, '投稿管理'),
(291, 289, 'contribution', 'index', NULL, '管理'),
(292, 291, 'contribution', 'index', 'index,page,view', '浏览'),
(293, 291, 'contribution', 'index', 'add', '添加'),
(294, 291, 'contribution', 'index', 'reject', '退稿'),
(295, 291, 'contribution', 'index', 'remove', '删除'),
(296, 291, 'contribution', 'index', 'delete', '彻底删除'),
(297, 291, 'contribution', 'index', 'clear', '清空回收站'),
(298, 289, 'contribution', 'setting', NULL, '设置'),
(299, NULL, 'digg', NULL, NULL, 'Digg'),
(300, 299, 'digg', 'digg', NULL, '排行榜'),
(301, 299, 'digg', 'setting', NULL, '设置'),
(302, NULL, 'editor', NULL, NULL, '编辑器设置'),
(312, NULL, 'field', NULL, NULL, '扩展字段管理'),
(322, NULL, 'freelist', NULL, NULL, '自由列表'),
(323, 322, 'freelist', 'freelist', NULL, '列表管理'),
(324, 323, 'freelist', 'freelist', 'index,page', '浏览'),
(325, 323, 'freelist', 'freelist', 'add', '基本设置'),
(326, 323, 'freelist', 'freelist', 'fadd', '配置筛选器'),
(327, 323, 'freelist', 'freelist', 'getview', '显示列表'),
(328, 323, 'freelist', 'freelist', 'delete', '批量删除'),
(329, 323, 'freelist', 'freelist', 'update', '批量更新'),
(330, 323, 'freelist', 'freelist', 'stop', '批量停止更新'),
(331, 322, 'freelist', 'group', NULL, '分组管理'),
(332, 331, 'freelist', 'group', 'index,page', '浏览'),
(333, 331, 'freelist', 'group', 'add', '添加'),
(334, 331, 'freelist', 'group', 'edit', '编辑'),
(335, 331, 'freelist', 'group', 'delete', '删除'),
(336, NULL, 'guestbook', NULL, NULL, '留言本'),
(337, 336, 'guestbook', 'guestbook', NULL, '留言管理'),
(338, 337, 'guestbook', 'guestbook', 'index,page', '浏览'),
(339, 337, 'guestbook', 'guestbook', 'reply', '回复'),
(340, 337, 'guestbook', 'guestbook', 'replyed', '已回复'),
(341, 337, 'guestbook', 'guestbook', 'send_email', '发送邮件'),
(342, 337, 'guestbook', 'guestbook', 'edit_content', '编辑内容'),
(343, 337, 'guestbook', 'guestbook', 'delete', '删除'),
(344, 336, 'guestbook', 'setting', NULL, '设置'),
(345, 336, 'guestbook', 'type', NULL, '类型管理'),
(346, 345, 'guestbook', 'type', 'index,page', '浏览'),
(347, 345, 'guestbook', 'type', 'add', '添加'),
(348, 345, 'guestbook', 'type', 'edit', '编辑'),
(349, 345, 'guestbook', 'type', 'delete', '删除'),
(350, NULL, 'history', NULL, NULL, '历史页面'),
(356, NULL, 'magazine', NULL, NULL, '电子杂志'),
(357, 356, 'magazine', 'content', NULL, '栏目内容管理'),
(358, 357, 'magazine', 'content', 'index,page', '浏览'),
(359, 357, 'magazine', 'content', 'delete', '删除'),
(360, 357, 'magazine', 'content', 'relate,getArticle,saveRelate', '关联文章'),
(361, 356, 'magazine', 'edition', NULL, '期号管理'),
(362, 361, 'magazine', 'edition', 'index,page', '浏览'),
(363, 361, 'magazine', 'edition', 'save', '新建期号'),
(364, 361, 'magazine', 'edition', 'delete', '删除期号'),
(365, 361, 'magazine', 'edition', 'disabled', '批量修改状态'),
(366, 356, 'magazine', 'magazine', NULL, '杂志管理'),
(367, 366, 'magazine', 'magazine', 'index,page', '浏览'),
(368, 366, 'magazine', 'magazine', 'save', '保存'),
(369, 366, 'magazine', 'magazine', 'delete', '删除'),
(370, 356, 'magazine', 'page', NULL, '栏目管理'),
(371, 370, 'magazine', 'page', 'index,page', '浏览'),
(372, 370, 'magazine', 'page', 'save', '保存'),
(373, 370, 'magazine', 'page', 'add', '添加'),
(374, 370, 'magazine', 'page', 'delete', '删除'),
(375, 370, 'magazine', 'page', 'publish', '发布'),
(376, NULL, 'member', NULL, NULL, '会员'),
(377, 376, 'member', 'audit', NULL, '审核用户'),
(378, 376, 'member', 'group', NULL, '用户组管理'),
(379, 378, 'member', 'group', 'index,page', '浏览'),
(380, 378, 'member', 'group', 'add', '添加'),
(381, 378, 'member', 'group', 'edit', '编辑'),
(382, 378, 'member', 'group', 'delete', '删除'),
(384, 376, 'member', 'index', NULL, '管理用户'),
(385, 384, 'member', 'index', 'index,page,search', '浏览'),
(386, 384, 'member', 'index', 'add', '添加'),
(387, 384, 'member', 'index', 'edit', '编辑'),
(388, 384, 'member', 'index', 'password', '修改密码'),
(389, 384, 'member', 'index', 'delete', '删除'),
(390, 384, 'member', 'index', 'remarks', '备注'),
(392, 384, 'member', 'index', 'avatar', '修改头像'),
(394, 384, 'member', 'index', 'sendmail', '发送邮件'),
(395, 384, 'member', 'index', 'show_unlock,locked_page', '查看锁定用户'),
(396, 384, 'member', 'index', 'unlock', '解锁用户'),
(397, 376, 'member', 'log', NULL, '登陆日志'),
(398, 397, 'member', 'log', 'index,page', '浏览'),
(399, 397, 'member', 'log', 'delete', '删除'),
(400, 376, 'member', 'setting', NULL, '设置'),
(405, NULL, 'mood', NULL, NULL, '心情'),
(406, 405, 'mood', 'data', NULL, '排行榜'),
(407, 405, 'mood', 'mood', NULL, '方案设置'),
(413, 405, 'mood', 'setting', NULL, '心情设置'),
(414, NULL, 'page', NULL, NULL, '页面'),
(419, 414, 'page', 'page', NULL, '页面管理'),
(421, 419, 'page', 'page', 'index,view,sectionlog,publish,visualedit,preview,logs,logs_page', '维护'),
(422, 419, 'page', 'page', 'manage,admin,property,sectionlog,publish,edit,visualedit,preview,add,bakup,bakSuggest,recover,exportTemplate,remove,restore,delete,logs,logs_page', '管理'),
(433, 414, 'page', 'page_priv', NULL, '页面权限'),
(434, 433, 'page', 'page_priv', 'index', '浏览'),
(435, 433, 'page', 'page_priv', 'add', '添加'),
(436, 433, 'page', 'page_priv', 'delete', '删除'),
(437, 414, 'page', 'section', NULL, '区块管理'),
(438, 437, 'page', 'section', 'edit,visual,preview,lock,unlock,unsave,delrow,uprow,downrow,addrow,additem,delitem,edititem,leftitem,rightitem,logpack,restorelog,clearlog,getlog,grap,publish,view,loadViewHtml,recommend,recommendItem,rejectRecommend,removeRecommend,get_section_info', '维护'),
(439, 437, 'page', 'section', 'property,visual,preview,logpack,getlog,add,publish,remove,restore,delete,move,copy,change_type,fill_data', '管理'),
(464, 414, 'page', 'section_priv', NULL, '区块权限'),
(465, 464, 'page', 'section_priv', 'index,ls', '浏览'),
(466, 464, 'page', 'section_priv', 'add', '添加'),
(467, 464, 'page', 'section_priv', 'delete', '删除'),
(468, NULL, 'paper', NULL, NULL, '电子报纸'),
(469, 468, 'paper', 'content', NULL, '版面内容管理'),
(470, 469, 'paper', 'content', 'index', '浏览'),
(471, 469, 'paper', 'content', 'relate', '关联文章'),
(472, 469, 'paper', 'content', 'getArticle', '获取文章'),
(473, 469, 'paper', 'content', 'saveMap', '保存热区'),
(474, 469, 'paper', 'content', 'delMap', '删除热区'),
(475, 469, 'paper', 'content', 'prevView', '预览'),
(476, 468, 'paper', 'edition', NULL, '期号管理'),
(477, 476, 'paper', 'edition', 'index,page', '浏览'),
(478, 476, 'paper', 'edition', 'save', '新建期号'),
(479, 476, 'paper', 'edition', 'prevView', '预览'),
(480, 476, 'paper', 'edition', 'delete', '删除'),
(481, 476, 'paper', 'edition', 'disabled', '批量修改状态'),
(482, 468, 'paper', 'page', NULL, '版面管理'),
(483, 482, 'paper', 'page', 'index,page', '浏览'),
(484, 482, 'paper', 'page', 'save', '保存'),
(485, 482, 'paper', 'page', 'add', '添加'),
(486, 482, 'paper', 'page', 'delete', '删除'),
(487, 482, 'paper', 'page', 'publish', '发布'),
(488, 482, 'paper', 'page', 'sleep', '休眠'),
(489, 482, 'paper', 'page', 'unpublish', '下线'),
(490, 468, 'paper', 'paper', NULL, '报纸管理'),
(491, 490, 'paper', 'paper', 'index,page', '浏览'),
(492, 490, 'paper', 'paper', 'save', '保存'),
(493, 490, 'paper', 'paper', 'delete', '删除'),
(504, NULL, 'push', NULL, NULL, '文章推送'),
(505, NULL, 'rss', NULL, NULL, 'RSS设置'),
(506, NULL, 'search', NULL, NULL, '搜索设置'),
(507, NULL, 'space', NULL, NULL, '个人专栏'),
(513, NULL, 'special', NULL, NULL, '专题'),
(514, 513, 'special', 'online', NULL, '页面设计'),
(516, 514, 'special', 'online', 'lock', '锁定'),
(517, 514, 'special', 'online', 'unlock', '解锁'),
(520, 514, 'special', 'online', 'addPage', '增加页面'),
(521, 514, 'special', 'online', 'setPage', '设置页面'),
(522, 514, 'special', 'online', 'copyPage', '拷贝页面'),
(523, 514, 'special', 'online', 'delPage', '删除页面'),
(526, 514, 'special', 'online', 'css', '风格'),
(529, 514, 'special', 'online', 'editTemplate', '编辑模板'),
(531, 514, 'special', 'online', 'getTemplate', '获取模板'),
(532, 514, 'special', 'online', 'getTheme', '获取风格中所有元素样式'),
(533, 514, 'special', 'online', 'setUI', '设置风格'),
(534, 514, 'special', 'online', 'delTheme', '删除风格'),
(535, 514, 'special', 'online', 'getWidget,getOneWidget', '获取模块'),
(536, 514, 'special', 'online', 'editWidget', '编辑模块'),
(537, 514, 'special', 'online', 'pubWidget', '发布模块'),
(538, 514, 'special', 'online', 'addWidget', '添加模块'),
(539, 514, 'special', 'online', 'shareWidget', '共享模块'),
(540, 514, 'special', 'online', 'unshareWidget', '取消共享模块'),
(542, 514, 'special', 'online', 'save', '保存'),
(543, 514, 'special', 'online', 'publish', '发布'),
(544, 514, 'special', 'online', 'offline', '下线'),
(545, 514, 'special', 'online', 'design', '设计'),
(547, 513, 'special', 'special', NULL, '专题管理'),
(548, 547, 'special', 'special', 'add', '添加'),
(549, 547, 'special', 'special', 'view', '查看'),
(550, 547, 'special', 'special', 'edit', '编辑'),
(551, 547, 'special', 'special', 'search', '搜索'),
(552, NULL, 'spider', NULL, NULL, '采集'),
(553, 552, 'spider', 'manager', NULL, '规则设置'),
(569, 552, 'spider', 'spider', NULL, '文章采集'),
(570, NULL, 'wap', NULL, NULL, 'WAP设置'),
(575, 179, 'activity', 'activity', 'remove', '删除'),
(578, 179, 'activity', 'activity', 'approve', '送审'),
(582, 179, 'activity', 'activity', 'publish', '发布'),
(583, 179, 'activity', 'activity', 'unpublish', '撤稿'),
(597, 240, 'interview', 'interview', 'remove', '删除'),
(604, 240, 'interview', 'interview', 'publish', '发布'),
(605, 240, 'interview', 'interview', 'unpublish', '撤稿'),
(619, 547, 'special', 'special', 'remove', '删除'),
(626, 547, 'special', 'special', 'publish', '发布'),
(627, 547, 'special', 'special', 'unpublish', '撤稿'),
(630, 225, 'survey', 'survey', 'remove', '删除'),
(633, 225, 'survey', 'survey', 'approve', '送审'),
(637, 225, 'survey', 'survey', 'publish', '发布'),
(638, 225, 'survey', 'survey', 'unpublish', '撤稿'),
(641, 164, 'video', 'video', 'remove', '删除'),
(644, 164, 'video', 'video', 'approve', '送审'),
(648, 164, 'video', 'video', 'publish', '发布'),
(649, 164, 'video', 'video', 'unpublish', '撤稿'),
(652, 200, 'vote', 'vote', 'remove', '删除'),
(659, 200, 'vote', 'vote', 'publish', '发布'),
(660, 200, 'vote', 'vote', 'unpublish', '撤稿'),
(662, 138, 'article', 'article', 'remove', '删除'),
(666, 138, 'article', 'article', 'approve', '送审'),
(670, 138, 'article', 'article', 'publish', '发布'),
(671, 138, 'article', 'article', 'unpublish', '撤稿'),
(672, NULL, 'mobile', NULL, NULL, '手机版'),
(675, NULL, 'cdn', NULL, NULL, 'CDN设置'),
(757, 261, 'link', 'link', 'remove', '删除'),
(760, 261, 'link', 'link', 'approve', '送审'),
(764, 261, 'link', 'link', 'publish', '发布'),
(765, 261, 'link', 'link', 'unpublish', '撤稿'),
(766, 356, 'magazine', 'setting', NULL, '设置'),
(767, 378, 'member', 'group', 'changegroup', '移动'),
(776, 468, 'paper', 'setting', NULL, '设置'),
(777, 150, 'picture', 'picture', NULL, '管理'),
(778, 777, 'picture', 'picture', 'add,related,remote,image', '添加'),
(779, 777, 'picture', 'picture', 'edit,miniedit,related,remote,image', '编辑'),
(780, 777, 'picture', 'picture', 'view', '查看'),
(782, 777, 'picture', 'picture', 'reference', '引用'),
(783, 777, 'picture', 'picture', 'move', '移动'),
(791, 777, 'picture', 'picture', 'remove', '删除'),
(794, 777, 'picture', 'picture', 'approve', '送审'),
(798, 777, 'picture', 'picture', 'publish', '发布'),
(799, 777, 'picture', 'picture', 'unpublish', '撤稿'),
(805, 514, 'special', 'online', 'editWidgetSetting', '编辑模块设置'),
(806, 514, 'special', 'online', 'useWidget', '使用模块'),
(812, 225, 'survey', 'survey', 'code', '获取调用代码'),
(815, 3, 'system', 'administrator', 'add,myadd', '添加'),
(816, 3, 'system', 'administrator', 'edit,myedit', '编辑'),
(817, 3, 'system', 'administrator', 'delete,mydelete', '删除'),
(818, 3, 'system', 'administrator', 'stat', '工作报表'),
(819, 3, 'system', 'administrator', 'clonepriv', '克隆权限'),
(820, 1, 'system', 'app', NULL, '扩展管理'),
(823, 30, 'system', 'content', 'compare', '对比标题'),
(839, 1, 'system', 'imgeditor', NULL, '图片编辑器设置'),
(857, 1, 'system', 'watermark', NULL, '水印方案'),
(866, 164, 'video', 'video', 'code', '获取调用代码'),
(868, 197, 'vote', 'log_data', NULL, '投票数据'),
(869, 200, 'vote', 'vote', 'view', '查看'),
(870, 200, 'vote', 'vote', 'code', '获取调用代码'),
(873, 547, 'special', 'special', 'move', '移动'),
(877, 173, 'video', 'vms', 'delete', '从回收站删除'),
(878, NULL, 'addon', NULL, NULL, '内容挂件'),
(879, 878, 'addon', 'addon', NULL, '挂件使用'),
(880, 878, 'addon', 'engine', NULL, '引擎管理'),
(881, 878, 'addon', 'setting', NULL, '挂件设置'),
(882, NULL, 'cloud', NULL, NULL, '云平台'),
(883, 882, 'cloud', 'bshare', NULL, 'BSahre 接口'),
(885, 882, 'cloud', 'cloud', NULL, 'CmsTop 云管理'),
(892, 882, 'cloud', 'thirdlogin', NULL, '合作网站账号绑定'),
(894, NULL, 'epaper', NULL, NULL, '数字报抓取'),
(895, 894, 'epaper', 'epaper', NULL, '数字报管理'),
(901, 894, 'epaper', 'import', NULL, '数字报抓取'),
(919, 414, 'page', 'section_recommend', NULL, '推荐内容管理'),
(930, 414, 'page', 'setting', NULL, '设置'),
(931, 514, 'special', 'online', 'getPages', '获取所有页面'),
(932, 514, 'special', 'online', 'createPage', '添加页面'),
(933, 514, 'special', 'online', 'renderWidget', '渲染模块'),
(934, 513, 'special', 'setting', NULL, '设置'),
(935, 934, 'special', 'setting', 'index', '设置'),
(936, 934, 'special', 'setting', 'template,pageTemplate,addTemplate,editTemplate,exportTemplate,delTemplate', '模板管理'),
(941, 934, 'special', 'setting', 'scheme,getSchemeTypes,schemeTypes,addSchemeType,editSchemeType,delSchemeType,pageScheme,addScheme,editScheme,exportScheme,delScheme', '方案管理'),
(954, 3, 'system', 'administrator', 'index,page,pagetree,suggest,name', '浏览'),
(955, 1, 'system', 'baidunews', NULL, '百度新闻源'),
(956, 17, 'system', 'category', 'index,path,reload,searchall', '浏览'),
(958, 1, 'system', 'openaca', NULL, '接口权限'),
(959, 1, 'system', 'openauth', NULL, '接口授权管理'),
(960, 1, 'system', 'port', NULL, '数据选择器'),
(967, 162, 'video', 'thirdparty', NULL, 'VMS'),
(968, 967, 'video', 'thirdparty', 'index,ls,getlist,selector,api,preview_video', '使用'),
(969, 967, 'video', 'thirdparty', 'add,edit,resort,delete', '设置'),
(974, 164, 'video', 'video', 'setting_ads', '广告设置'),
(975, 162, 'video', 'videolist', NULL, '视频专辑'),
(976, 975, 'video', 'videolist', 'index,selector,ls,getname', '浏览专辑'),
(978, 975, 'video', 'videolist', 'add', '添加专辑'),
(979, 975, 'video', 'videolist', 'edit', '修改专辑'),
(980, 975, 'video', 'videolist', 'delete', '删除专辑'),
(981, 975, 'video', 'videolist', 'html', '更新视频'),
(982, 975, 'video', 'videolist', 'video,video_ls', '视频浏览'),
(983, 975, 'video', 'videolist', 'video_add', '添加视频'),
(984, 975, 'video', 'videolist', 'video_delete', '删除视频'),
(985, 975, 'video', 'videolist', 'video_resort', '排序视频'),
(987, 173, 'video', 'vms', 'import', '批量导入'),
(990, NULL, 'weibo', NULL, NULL, '微博转发'),
(996, 990, 'weibo', 'weibo', NULL, '微博绑定与转发'),
(997, 996, 'weibo', 'weibo', 'index,addlink,get_content,upload,tweeted', '微博转发'),
(998, 996, 'weibo', 'weibo', 'account,ls,remove,set_state,sort', '账号绑定'),
(1004, 437, 'page', 'section', 'unlock,recommend,recommendItem,removeRecommend', '推荐'),
(1005, 934, 'special', 'setting', 'searchScheme,searchTemplate,addTemplate,getSchemeTypes', '页面设计'),
(1008, 179, 'activity', 'activity', 'delete', '从回收站删除'),
(1009, 179, 'activity', 'activity', 'clear', '清空回收站'),
(1010, 179, 'activity', 'activity', 'restore', '恢复'),
(1011, 179, 'activity', 'activity', 'restores', '恢复全部'),
(1012, 179, 'activity', 'activity', 'pass', '通过'),
(1013, 179, 'activity', 'activity', 'reject', '退稿'),
(1014, 178, 'activity', 'field', NULL, '字段管理'),
(1015, 178, 'activity', 'setting', NULL, '设置'),
(1016, 138, 'article', 'article', 'edit,miniedit,related', '编辑'),
(1017, 138, 'article', 'article', 'delete', '从回收站删除'),
(1018, 138, 'article', 'article', 'clear', '清空回收站'),
(1019, 138, 'article', 'article', 'restore', '恢复'),
(1020, 138, 'article', 'article', 'restores', '恢复全部'),
(1021, 138, 'article', 'article', 'pass', '通过'),
(1022, 138, 'article', 'article', 'reject', '退稿'),
(1023, NULL, 'baoliao', NULL, NULL, '报料'),
(1024, 1023, 'baoliao', 'baoliao', NULL, '管理'),
(1025, 1023, 'baoliao', 'setting', NULL, '设置'),
(1026, 675, 'cdn', 'cdn', NULL, '接口管理'),
(1027, 675, 'cdn', 'rules', NULL, '规则管理'),
(1028, 675, 'cdn', 'setting', NULL, '设置'),
(1029, 312, 'field', 'field', NULL, '字段管理'),
(1030, 312, 'field', 'project', NULL, '方案管理'),
(1031, 240, 'interview', 'interview', 'delete', '从回收站删除'),
(1032, 240, 'interview', 'interview', 'clear', '清空回收站'),
(1033, 240, 'interview', 'interview', 'restore', '恢复'),
(1034, 240, 'interview', 'interview', 'restores', '恢复全部'),
(1035, 240, 'interview', 'interview', 'pass', '通过'),
(1036, 240, 'interview', 'interview', 'reject', '退稿'),
(1037, 261, 'link', 'link', 'delete', '从回收站彻底删除'),
(1038, 261, 'link', 'link', 'clear', '清空回收站'),
(1039, 261, 'link', 'link', 'restore', '恢复'),
(1040, 261, 'link', 'link', 'restores', '恢复全部'),
(1041, 261, 'link', 'link', 'pass', '通过'),
(1042, 261, 'link', 'link', 'reject', '退稿'),
(1043, 672, 'mobile', 'activity', NULL, '活动'),
(1044, 1043, 'mobile', 'activity', 'add', '添加'),
(1045, 1043, 'mobile', 'activity', 'edit,view,quickedit', '编辑'),
(1046, 1043, 'mobile', 'activity', 'view', '查看'),
(1047, 1043, 'mobile', 'activity', 'publish', '发布'),
(1048, 1043, 'mobile', 'activity', 'unpublish', '撤稿'),
(1049, 1043, 'mobile', 'activity', 'pass', '通过'),
(1050, 1043, 'mobile', 'activity', 'remove', '删除'),
(1051, 1043, 'mobile', 'activity', 'restore', '恢复'),
(1052, 1043, 'mobile', 'activity', 'del', '从回收站删除'),
(1053, 1043, 'mobile', 'activity', 'clear', '清空回收站'),
(1054, 672, 'mobile', 'addon', NULL, '挂件使用'),
(1055, 672, 'mobile', 'article', NULL, '文章'),
(1056, 1055, 'mobile', 'article', 'add', '添加'),
(1057, 1055, 'mobile', 'article', 'edit,view,quickedit', '编辑'),
(1058, 1055, 'mobile', 'article', 'view', '查看'),
(1059, 1055, 'mobile', 'article', 'publish', '发布'),
(1060, 1055, 'mobile', 'article', 'unpublish', '撤稿'),
(1061, 1055, 'mobile', 'article', 'pass', '通过'),
(1062, 1055, 'mobile', 'article', 'remove', '删除'),
(1063, 1055, 'mobile', 'article', 'restore', '恢复'),
(1064, 1055, 'mobile', 'article', 'del', '从回收站删除'),
(1065, 1055, 'mobile', 'article', 'clear', '清空回收站'),
(1066, 672, 'mobile', 'autofill', NULL, '内容填充'),
(1067, 1066, 'mobile', 'autofill', 'index,page', '查看'),
(1068, 1066, 'mobile', 'autofill', 'add', '添加'),
(1069, 1066, 'mobile', 'autofill', 'edit,toggle', '编辑'),
(1070, 1066, 'mobile', 'autofill', 'delete', '删除'),
(1071, 672, 'mobile', 'content', NULL, '内容'),
(1072, 1071, 'mobile', 'content', 'index,page,search,log,log_page', '查看'),
(1073, 1071, 'mobile', 'content', 'log_delete', '删除日志'),
(1074, 1071, 'mobile', 'content', 'log_clear', '清空日志'),
(1075, 1071, 'mobile', 'content', 'stick,unstick', '固顶'),
(1076, 1071, 'mobile', 'content', 'bumpup', '置顶'),
(1077, 1071, 'mobile', 'content', 'slider_add,slider_edit,slider_remove', '设为幻灯片'),
(1078, 672, 'mobile', 'feedback', NULL, '意见反馈'),
(1079, 1078, 'mobile', 'feedback', 'index,page,view', '查看'),
(1080, 1078, 'mobile', 'feedback', 'delete', '删除'),
(1081, 672, 'mobile', 'link', NULL, '链接'),
(1082, 1081, 'mobile', 'link', 'add', '添加'),
(1083, 1081, 'mobile', 'link', 'edit,view,quickedit', '编辑'),
(1084, 1081, 'mobile', 'link', 'view', '查看'),
(1085, 1081, 'mobile', 'link', 'publish', '发布'),
(1086, 1081, 'mobile', 'link', 'unpublish', '撤稿'),
(1087, 1081, 'mobile', 'link', 'pass', '通过'),
(1088, 1081, 'mobile', 'link', 'remove', '删除'),
(1089, 1081, 'mobile', 'link', 'restore', '恢复'),
(1090, 1081, 'mobile', 'link', 'del', '从回收站删除'),
(1091, 1081, 'mobile', 'link', 'clear', '清空回收站'),
(1092, 672, 'mobile', 'picture', NULL, '组图'),
(1093, 1092, 'mobile', 'picture', 'add', '添加'),
(1094, 1092, 'mobile', 'picture', 'edit,view,quickedit', '编辑'),
(1095, 1092, 'mobile', 'picture', 'view', '查看'),
(1096, 1092, 'mobile', 'picture', 'publish', '发布'),
(1097, 1092, 'mobile', 'picture', 'unpublish', '撤稿'),
(1098, 1092, 'mobile', 'picture', 'pass', '通过'),
(1099, 1092, 'mobile', 'picture', 'remove', '删除'),
(1100, 1092, 'mobile', 'picture', 'restore', '恢复'),
(1101, 1092, 'mobile', 'picture', 'del', '从回收站删除'),
(1102, 1092, 'mobile', 'picture', 'clear', '清空回收站'),
(1103, 672, 'mobile', 'push', NULL, '推送'),
(1104, 672, 'mobile', 'setting', NULL, '设置'),
(1105, 1104, 'mobile', 'setting', 'index', '系统设置'),
(1106, 1104, 'mobile', 'setting', 'display', '显示设置'),
(1107, 1104, 'mobile', 'setting', 'category,category_page,category_add,category_edit,category_enable,category_disable,category_delete,category_updown,category_priv,category_priv_add,category_priv_delete', '频道管理'),
(1108, 1104, 'mobile', 'setting', 'app,app_list,app_add,app_edit,app_update,app_delete', '应用管理'),
(1109, 1104, 'mobile', 'setting', 'moreapp,moreapp_page,moreapp_add,moreapp_edit,moreapp_delete,moreapp_updown', '应用推荐'),
(1110, 1104, 'mobile', 'setting', 'version', '版本升级'),
(1111, 1104, 'mobile', 'setting', 'api', 'API 设置'),
(1112, 672, 'mobile', 'special', NULL, '专题'),
(1113, 1112, 'mobile', 'special', 'add', '添加'),
(1114, 1112, 'mobile', 'special', 'edit,view,quickedit', '编辑'),
(1115, 1112, 'mobile', 'special', 'view', '查看'),
(1116, 1112, 'mobile', 'special', 'recommend,saveRecommend,removeRecommend', '推送'),
(1117, 1112, 'mobile', 'special', 'publish', '发布'),
(1118, 1112, 'mobile', 'special', 'unpublish', '撤稿'),
(1119, 1112, 'mobile', 'special', 'pass', '通过'),
(1120, 1112, 'mobile', 'special', 'remove', '删除'),
(1121, 1112, 'mobile', 'special', 'restore', '恢复'),
(1122, 1112, 'mobile', 'special', 'del', '从回收站删除'),
(1123, 1112, 'mobile', 'special', 'clear', '清空回收站'),
(1124, 672, 'mobile', 'stat', NULL, '统计'),
(1125, 1124, 'mobile', 'stat', 'client,client_today,client_overview,client_trends,client_user,client_user_export,client_device', '客户端统计'),
(1126, 1124, 'mobile', 'stat', 'content,content_query', '内容统计'),
(1127, 672, 'mobile', 'survey', NULL, '调查'),
(1128, 1127, 'mobile', 'survey', 'add', '添加'),
(1129, 1127, 'mobile', 'survey', 'edit,view,quickedit', '编辑'),
(1130, 1127, 'mobile', 'survey', 'view', '查看'),
(1131, 1127, 'mobile', 'survey', 'publish', '发布'),
(1132, 1127, 'mobile', 'survey', 'unpublish', '撤稿'),
(1133, 1127, 'mobile', 'survey', 'pass', '通过'),
(1134, 1127, 'mobile', 'survey', 'remove', '删除'),
(1135, 1127, 'mobile', 'survey', 'restore', '恢复'),
(1136, 1127, 'mobile', 'survey', 'del', '从回收站删除'),
(1137, 1127, 'mobile', 'survey', 'clear', '清空回收站'),
(1138, 672, 'mobile', 'video', NULL, '视频'),
(1139, 1138, 'mobile', 'video', 'add', '添加'),
(1140, 1138, 'mobile', 'video', 'edit,view,quickedit', '编辑'),
(1141, 1138, 'mobile', 'video', 'view', '查看'),
(1142, 1138, 'mobile', 'video', 'publish', '发布'),
(1143, 1138, 'mobile', 'video', 'unpublish', '撤稿'),
(1144, 1138, 'mobile', 'video', 'pass', '通过'),
(1145, 1138, 'mobile', 'video', 'remove', '删除'),
(1146, 1138, 'mobile', 'video', 'restore', '恢复'),
(1147, 1138, 'mobile', 'video', 'del', '从回收站删除'),
(1148, 1138, 'mobile', 'video', 'clear', '清空回收站'),
(1149, 672, 'mobile', 'vote', NULL, '投票'),
(1150, 1149, 'mobile', 'vote', 'add', '添加'),
(1151, 1149, 'mobile', 'vote', 'edit,view,quickedit', '编辑'),
(1152, 1149, 'mobile', 'vote', 'view', '查看'),
(1153, 1149, 'mobile', 'vote', 'publish', '发布'),
(1154, 1149, 'mobile', 'vote', 'unpublish', '撤稿'),
(1155, 1149, 'mobile', 'vote', 'pass', '通过'),
(1156, 1149, 'mobile', 'vote', 'remove', '删除'),
(1157, 1149, 'mobile', 'vote', 'restore', '恢复'),
(1158, 1149, 'mobile', 'vote', 'del', '从回收站删除'),
(1159, 1149, 'mobile', 'vote', 'clear', '清空回收站'),
(1160, 672, 'mobile', 'weibo', NULL, '官方微博'),
(1161, 777, 'picture', 'picture', 'delete', '从回收站删除'),
(1162, 777, 'picture', 'picture', 'clear', '清空回收站'),
(1163, 777, 'picture', 'picture', 'restore', '恢复'),
(1164, 777, 'picture', 'picture', 'restores', '恢复全部'),
(1165, 777, 'picture', 'picture', 'pass', '通过'),
(1166, 777, 'picture', 'picture', 'reject', '退稿'),
(1167, 514, 'special', 'online', 'getWidgetByPage', '获取页面中所有模块'),
(1168, 514, 'special', 'online', 'saveScheme', '保存为方案'),
(1169, 513, 'special', 'resource', NULL, '专题资源'),
(1170, 1169, 'special', 'resource', 'subDir', '列子目录'),
(1171, 1169, 'special', 'resource', 'readDir', '读取目录'),
(1172, 1169, 'special', 'resource', 'createDir', '创建目录'),
(1173, 1169, 'special', 'resource', 'editFile', '编辑文件'),
(1174, 1169, 'special', 'resource', 'rename', '重命名'),
(1175, 1169, 'special', 'resource', 'remove', '删除'),
(1176, 1169, 'special', 'resource', 'getConfig', '读取图形编辑器配置'),
(1177, 1169, 'special', 'resource', 'readImage', '读取图片'),
(1178, 1169, 'special', 'resource', 'preview', '获取预览图'),
(1179, 1169, 'special', 'resource', 'saveImage', '保存图片'),
(1180, 1169, 'special', 'resource', 'upload', '上传文件'),
(1181, 547, 'special', 'special', 'recommend,saveRecommend', '推送'),
(1182, 547, 'special', 'special', 'removeRecommend', '删除推送内容'),
(1183, 547, 'special', 'special', 'delete', '从回收站删除'),
(1184, 547, 'special', 'special', 'clear', '清空回收站'),
(1185, 547, 'special', 'special', 'restore', '恢复'),
(1186, 547, 'special', 'special', 'restores', '恢复全部'),
(1187, 547, 'special', 'special', 'pass', '通过'),
(1188, 547, 'special', 'special', 'reject', '退稿'),
(1189, 552, 'spider', 'cron', NULL, '自动采集'),
(1190, 552, 'spider', 'setting', NULL, '采集设置'),
(1191, 207, 'survey', 'setting', NULL, '设置'),
(1192, 225, 'survey', 'survey', 'delete', '从回收站删除'),
(1193, 225, 'survey', 'survey', 'clear', '清空回收站'),
(1194, 225, 'survey', 'survey', 'restore', '恢复'),
(1195, 225, 'survey', 'survey', 'restores', '恢复全部'),
(1196, 225, 'survey', 'survey', 'pass', '通过'),
(1197, 225, 'survey', 'survey', 'reject', '退稿'),
(1198, 17, 'system', 'category', 'addchannel', '新建顶级栏目'),
(1199, 1, 'system', 'import', NULL, '数据导入'),
(1200, 1, 'system', 'qrcode', NULL, '二维码'),
(1201, 1200, 'system', 'qrcode', 'index,preview,generate,download', '使用'),
(1202, 1200, 'system', 'qrcode', 'stat,page,view,query,edit,delete', '统计'),
(1203, 1, 'system', 'toolbox', NULL, '网编工具箱'),
(1204, 162, 'video', 'search', NULL, '搜索'),
(1205, 164, 'video', 'video', 'delete', '从回收站删除'),
(1206, 164, 'video', 'video', 'clear', '清空回收站'),
(1207, 164, 'video', 'video', 'restore', '恢复'),
(1208, 164, 'video', 'video', 'restores', '恢复全部'),
(1209, 164, 'video', 'video', 'pass', '通过'),
(1210, 164, 'video', 'video', 'reject', '退稿'),
(1211, 173, 'video', 'vms', 'index,view,preview,edit,ls, delete, state, check, info, info_by_file, setinfo, delete, get_setting, set_setting, get_import, set_import', '使用'),
(1212, 173, 'video', 'vms', 'setting,setting_video', '设置'),
(1213, 173, 'video', 'vms', 'ls', '列表'),
(1214, 173, 'video', 'vms', 'info,info_by_file', '查看视频信息'),
(1215, 173, 'video', 'vms', 'setinfo', '设置视频信息'),
(1216, 197, 'vote', 'setting', NULL, '设置'),
(1217, 200, 'vote', 'vote', 'delete', '从回收站删除'),
(1218, 200, 'vote', 'vote', 'clear', '清空回收站'),
(1219, 200, 'vote', 'vote', 'restore', '恢复'),
(1220, 200, 'vote', 'vote', 'restores', '恢复全部'),
(1221, 200, 'vote', 'vote', 'pass', '通过'),
(1222, 200, 'vote', 'vote', 'reject', '退稿'),
(1223, 672, 'mobile', 'ad', NULL, '广告管理'),
(1224, NULL, 'safe', NULL, NULL, '系统安全'),
(1225, 1224, 'safe', 'domain', NULL, '域名安全'),
(1226, 1224, 'safe', 'log', NULL, '操作日志'),
(1227, 1224, 'safe', 'setting', NULL, '安全设置'),
(1228, 1224, 'safe', 'trojan', NULL, '木马扫描'),
(1229, 1224, 'safe', 'verify', NULL, '文件校验'),
(1230, 672, 'mobile', 'eventlive', NULL, '直播'),
(1231, 1230, 'mobile', 'eventlive', 'add,get_post,post', '添加'),
(1232, 1230, 'mobile', 'eventlive', 'edit,get_post,post', '编辑'),
(1233, 1230, 'mobile', 'eventlive', 'view', '查看'),
(1234, 1230, 'mobile', 'eventlive', 'publish', '发布'),
(1235, 1230, 'mobile', 'eventlive', 'unpublish', '撤稿'),
(1236, 1230, 'mobile', 'eventlive', 'pass', '通过'),
(1237, 1230, 'mobile', 'eventlive', 'remove', '删除'),
(1238, 1230, 'mobile', 'eventlive', 'restore', '恢复'),
(1239, 1230, 'mobile', 'eventlive', 'del', '从回收站删除'),
(1240, 1230, 'mobile', 'eventlive', 'clear', '清空回收站');

--
-- Table structure for table `cmstop_activity`
--

CREATE TABLE IF NOT EXISTS `cmstop_activity` (
  `contentid` mediumint(8) unsigned NOT NULL,
  `description` varchar(255) DEFAULT NULL,
  `content` mediumtext,
  `starttime` int(10) unsigned DEFAULT NULL,
  `endtime` int(10) unsigned DEFAULT NULL,
  `address` varchar(255) NOT NULL,
  `point` varchar(255) DEFAULT NULL,
  `type` varchar(50) NOT NULL,
  `maxpersons` smallint(5) unsigned NOT NULL DEFAULT '0',
  `gender` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `mailto` varchar(40) DEFAULT NULL,
  `signstart` int(10) unsigned DEFAULT NULL,
  `signend` int(10) unsigned DEFAULT NULL,
  `selected` varchar(255) NOT NULL,
  `required` varchar(255) NOT NULL,
  `displayed` varchar(255) NOT NULL,
  `total` smallint(5) unsigned NOT NULL DEFAULT '0',
  `checkeds` smallint(5) unsigned NOT NULL DEFAULT '0',
  `signstoped` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `mininterval` tinyint(4) unsigned DEFAULT '0',
  `bgimg` varchar(255) DEFAULT '',
  PRIMARY KEY (`contentid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `cmstop_activity_field`
--

CREATE TABLE IF NOT EXISTS `cmstop_activity_field` (
  `fieldid` varchar(50) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `label` varchar(100) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `type` varchar(50) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `disabled` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `default` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `options` text CHARACTER SET utf8 COLLATE utf8_bin,
  `sort` smallint(6) NOT NULL,
  `system` tinyint(1) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`fieldid`),
  UNIQUE KEY `fieldid` (`fieldid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `cmstop_activity_field`
--

INSERT INTO `cmstop_activity_field` (`fieldid`, `label`, `type`, `disabled`, `default`, `options`, `sort`, `system`) VALUES
('address', '地址', 'text', 0, 0, 0x7b226c696d6974223a22313530222c2272756c65223a22222c227265676578223a22227d, 15, 0),
('aid', '附件', 'file', 0, 0, 0x7b2273697a656c696d6974223a2235222c2266696c65657874223a22227d, 17, 0),
('company', '工作单位', 'text', 0, 0, 0x7b226c696d6974223a22313030222c2272756c65223a22222c227265676578223a22227d, 5, 0),
('email', 'Email', 'text', 0, 0, 0x7b226c696d6974223a22222c2272756c65223a22656d61696c222c227265676578223a22227d, 11, 0),
('identity', '身份证号码', 'text', 0, 0, 0x7b226c696d6974223a22222c2272756c65223a226964222c227265676578223a22227d, 4, 0),
('job', '职业', 'text', 0, 0, 0x7b226c696d6974223a22313030222c2272756c65223a22222c227265676578223a22227d, 6, 0),
('mobile', '手机号码', 'text', 0, 0, 0x7b226c696d6974223a22222c2272756c65223a226d6f62696c65222c227265676578223a22227d, 10, 0),
('msn', 'MSN', 'text', 0, 0, 0x7b226c696d6974223a22222c2272756c65223a22656d61696c222c227265676578223a22227d, 13, 0),
('name', '姓名', 'text', 0, 1, 0x7b226c696d6974223a22222c2272756c65223a22222c227265676578223a22227d, 1, 1),
('note', '附言', 'textarea', 0, 1, 0x7b226c696d6974223a22353030222c2272756c65223a22222c227265676578223a22227d, 18, 1),
('photo', '照片', 'photo', 0, 0, 0x7b2273697a656c696d6974223a2235227d, 3, 0),
('qq', 'QQ', 'text', 0, 0, 0x7b226c696d6974223a22222c2272756c65223a227171222c227265676578223a22227d, 12, 0),
('sex', '性别', 'radio', 0, 0, 0x7b226f7074696f6e223a22e794b77c315c6ee5a5b37c30227d, 2, 0),
('site', '个人主页', 'text', 0, 0, 0x7b226c696d6974223a22222c2272756c65223a2275726c222c227265676578223a22227d, 14, 0),
('telephone', '电话号码', 'text', 0, 0, 0x7b226c696d6974223a22222c2272756c65223a2274656c6570686f6e65222c227265676578223a22227d, 9, 0),
('zipcode', '邮政编码', 'text', 0, 0, 0x7b226c696d6974223a22222c2272756c65223a227a6970636f6465222c227265676578223a22227d, 16, 0);

-- --------------------------------------------------------

--
-- Table structure for table `cmstop_activity_sign`
--

CREATE TABLE IF NOT EXISTS `cmstop_activity_sign` (
  `signid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `contentid` mediumint(8) unsigned NOT NULL,
  `name` varchar(50) NOT NULL,
  `sex` tinyint(1) unsigned DEFAULT NULL,
  `photo` varchar(100) DEFAULT NULL,
  `identity` varchar(20) DEFAULT NULL,
  `company` varchar(100) DEFAULT NULL,
  `job` varchar(50) DEFAULT NULL,
  `telephone` varchar(15) DEFAULT NULL,
  `mobile` varchar(11) DEFAULT NULL,
  `email` varchar(40) DEFAULT NULL,
  `qq` varchar(15) DEFAULT NULL,
  `msn` varchar(40) DEFAULT NULL,
  `site` varchar(100) DEFAULT NULL,
  `address` varchar(100) DEFAULT NULL,
  `zipcode` varchar(6) DEFAULT NULL,
  `aid` int(10) unsigned NOT NULL DEFAULT '0',
  `note` mediumtext,
  `data` text NOT NULL,
  `created` int(10) unsigned NOT NULL,
  `createdby` mediumint(8) unsigned DEFAULT NULL,
  `ip` varchar(15) NOT NULL,
  `state` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `checked` int(10) unsigned DEFAULT NULL,
  `checkedby` mediumint(8) unsigned DEFAULT NULL,
  PRIMARY KEY (`signid`),
  KEY `contentid` (`contentid`,`state`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `cmstop_addon`
--

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `cmstop_addon_engine`
--

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
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=9 ;

--
-- Dumping data for table `cmstop_addon_engine`
--

INSERT INTO `cmstop_addon_engine` (`engineid`, `name`, `description`, `place`, `version`, `author`, `updateurl`, `installed`, `disabled`, `sort`) VALUES
(1, 'picture_group', '组图', 'A2', '', '', NULL, 1341457059, 0, 1),
(2, 'video', '视频', 'A2', '', '', NULL, 1341457402, 0, 2),
(3, 'vote', '投票', 'A5', '', '', NULL, 1341457572, 0, 3),
(4, 'special', '专题', 'A5', '', '', NULL, 1341458574, 0, 5),
(5, 'survey', '调查', 'A5', '', '', NULL, 1341458665, 0, 4),
(6, 'activity', '活动', 'A5', '', '', NULL, 1341458678, 0, 6),
(8, 'map', '百度地图', 'A5', '', '', NULL, 1341478362, 0, 8);

-- --------------------------------------------------------

--
-- Table structure for table `cmstop_admin`
--

CREATE TABLE IF NOT EXISTS `cmstop_admin` (
  `userid` mediumint(8) unsigned NOT NULL,
  `roleid` tinyint(3) unsigned DEFAULT NULL,
  `departmentid` tinyint(3) unsigned DEFAULT NULL,
  `name` char(20) NOT NULL,
  `sex` tinyint(1) unsigned DEFAULT NULL,
  `birthday` date DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `photo` varchar(100) DEFAULT NULL,
  `qq` char(15) DEFAULT NULL,
  `msn` char(40) DEFAULT NULL,
  `telephone` char(18) DEFAULT NULL,
  `mobile` char(11) DEFAULT NULL,
  `address` char(100) DEFAULT NULL,
  `zipcode` char(6) DEFAULT NULL,
  `created` int(10) unsigned NOT NULL,
  `createdby` mediumint(8) unsigned NOT NULL,
  `updated` int(10) unsigned DEFAULT NULL,
  `updatedby` mediumint(8) unsigned DEFAULT NULL,
  `disabled` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `pv` int(10) unsigned NOT NULL DEFAULT '0',
  `posts` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `comments` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `password` varchar(32) default '' COMMENT '密码',
  PRIMARY KEY (`userid`),
  KEY `roleid` (`roleid`),
  KEY `departmentid` (`departmentid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `cmstop_admin`
--

INSERT INTO `cmstop_admin` (`userid`, `roleid`, `departmentid`, `name`, `sex`, `birthday`, `email`, `photo`, `qq`, `msn`, `telephone`, `mobile`, `address`, `zipcode`, `created`, `createdby`, `updated`, `updatedby`, `disabled`, `pv`, `posts`, `comments`) VALUES
(1, 1, 2, 'CmsTop', 1, '0000-00-00', NULL, '', '', '', '', '', '', '', 1291791172, 1, 1291791172, 1, 0, 0, 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `cmstop_admin_log`
--

CREATE TABLE IF NOT EXISTS `cmstop_admin_log` (
  `logid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `aca` varchar(50) NOT NULL,
  `url` varchar(255) NOT NULL,
  `userid` mediumint(8) unsigned DEFAULT NULL,
  `ip` varchar(15) NOT NULL,
  `time` datetime NOT NULL,
  `dur` float DEFAULT NULL,
  PRIMARY KEY (`logid`),
  KEY `userid` (`userid`,`logid`),
  KEY `time` (`time`,`logid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `cmstop_admin_weight`
--

CREATE TABLE IF NOT EXISTS `cmstop_admin_weight` (
  `userid` mediumint(8) unsigned NOT NULL,
  `weight` tinyint(2) unsigned NOT NULL,
  PRIMARY KEY (`userid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `cmstop_api`
--

CREATE TABLE IF NOT EXISTS `cmstop_api` (
  `apiid` smallint(3) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(32) NOT NULL,
  `interface` varchar(40) NOT NULL,
  `description` varchar(200) DEFAULT '',
  `icon` varchar(255) NOT NULL,
  `icon_gray` varchar(255) NOT NULL DEFAULT '',
  `authorize` varchar(500) NOT NULL DEFAULT '{}',
  `islogin` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `isshare` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `state` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `sort` smallint(3) unsigned NOT NULL,
  PRIMARY KEY (`apiid`),
  KEY `sort` (`sort`),
  KEY `islogin` (`islogin`),
  KEY `isshare` (`isshare`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=4 ;

--
-- Dumping data for table `cmstop_api`
--

INSERT INTO `cmstop_api` (`apiid`, `name`, `interface`, `description`, `icon`, `icon_gray`, `authorize`, `islogin`, `isshare`, `state`, `sort`) VALUES
(1, '腾讯微博', 'tencent_weibo', '', 'http://img.silkroad.news.cn/templates/default/img/qq.png', 'http://img.silkroad.news.cn/templates/default/img/qq_g.png', '{"client_id":"","client_secret":""}', 1, 0, 1, 1),
(2, '新浪微博', 'sina_weibo', '', 'http://img.silkroad.news.cn/templates/default/img/sina.gif', 'http://img.silkroad.news.cn/templates/default/img/sina_g.gif', '{"client_id":"","client_secret":""}', 1, 0, 1, 2),
(3, 'QQ互联', 'qzone', '', 'http://img.silkroad.news.cn/templates/default/img/qzone.gif', '', '{"client_id":"","client_secret":""}', 1, 0, 1, 3);

-- --------------------------------------------------------

--
-- Table structure for table `cmstop_app`
--

CREATE TABLE IF NOT EXISTS `cmstop_app` (
  `app` varchar(15) NOT NULL,
  `name` varchar(20) NOT NULL,
  `description` text,
  `url` varchar(255) NOT NULL,
  `version` varchar(5) NOT NULL,
  `author` varchar(40) NOT NULL,
  `author_url` varchar(50) NOT NULL,
  `author_email` varchar(40) NOT NULL,
  `install_time` int(10) unsigned NOT NULL DEFAULT '0',
  `update_time` int(10) unsigned NOT NULL DEFAULT '0',
  `disabled` tinyint(1) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`app`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `cmstop_app`
--

INSERT INTO `cmstop_app` (`app`, `name`, `description`, `url`, `version`, `author`, `author_url`, `author_email`, `install_time`, `update_time`, `disabled`) VALUES
('activity', '活动', '', '', '1.0.0', 'cmstop', 'http://www.cmstop.com/', 'webmaster@cmstop.com', 1274811314, 1274811314, 0),
('addon', '内容挂件', '', '', '1.0.0', 'cmstop', 'http://www.cmstop.com/', 'webmaster@cmstop.com', 1274811314, 1274811314, 0),
('article', '文章', '', '', '1.0.0', 'cmstop', 'http://www.cmstop.com/', 'webmaster@cmstop.com', 1274811314, 1274811314, 0),
('cdn', 'CDN管理', '', '', '1.0.0', 'cmstop', 'http://www.cmstop.com/', 'webmaster@cmstop.com', 1274811314, 1274811314, 0),
('cloud', '云平台', '云平台', '', '', 'CmsTop', 'http://www.cmstop.com/', 'webmaster@cmstop.com', 1274811314, 1274811314, 0),
('comment', '评论', '', '', '1.0.0', 'cmstop', 'http://www.cmstop.com/', 'webmaster@cmstop.com', 1274811314, 1274811314, 0),
('contribution', '投稿', '', '', '1.0.0', 'cmstop', 'http://www.cmstop.com/', 'webmaster@cmstop.com', 1274811314, 1274811314, 0),
('digg', 'Digg', '', '', '1.0.0', 'cmstop', 'http://www.cmstop.com/', 'webmaster@cmstop.com', 1274811314, 1274811314, 0),
('editor', '编辑器', '', '', '1.0.0', 'cmstop', 'http://www.cmstop.com/', 'webmaster@cmstop.com', 1274811314, 1274811314, 0),
('epaper', '数字报抓取', '', '', '1.0.0', 'cmstop', 'http://www.cmstop.com/', 'webmaster@cmstop.com', 1274811314, 1274811314, 0),
('field', '扩展字段', ' ', ' ', '1.0.0', 'cmstop', 'http://www.cmstop.com/', 'webmaster@cmstop.com', 1274811314, 1274811314, 0),
('freelist', '自由列表', ' ', '', '1.0.0', 'cmstop', 'http://www.cmstop.com', 'webmaster@cmstop.com', 1274811314, 1274811314, 0),
('guestbook', '留言本', '', '', '1.0.0', 'cmstop', 'http://www.cmstop.com/', 'webmaster@cmstop.com', 1274811314, 1274811314, 0),
('history', '历史页面', '', '', '1.0.0', 'cmstop', 'http://www.cmstop.com/', 'webmaster@cmstop.com', 1274811314, 1274811314, 0),
('link', '链接', '', '', '1.0.0', 'cmstop', 'http://www.cmstop.com/', 'webmaster@cmstop.com', 1274811314, 1274811314, 0),
('magazine', '杂志', '', '', '1.0.0', 'cmstop', 'http://www.cmstop.com/', 'webmaster@cmstop.com', 1274811314, 1274811314, 0),
('member', '会员', '', '', '1.0.0', 'cmstop', 'http://www.cmstop.com/', 'webmaster@cmstop.com', 1274811314, 1274811314, 0),
('mobile', '手机版', '', '', '1.0.0', 'cmstop', 'http://www.cmstop.com/', 'webmaster@cmstop.com', 1274811314, 1274811314, 0),
('mood', '心情', '', '', '1.0.0', 'cmstop', 'http://www.cmstop.com/', 'webmaster@cmstop.com', 1274811314, 1274811314, 0),
('page', '页面', '', '', '1.0.0', 'cmstop', 'http://www.cmstop.com/', 'webmaster@cmstop.com', 1274811314, 1274811314, 0),
('paper', '报纸', '', '', '1.0.0', 'cmstop', 'http://www.cmstop.com/', 'webmaster@cmstop.com', 1274811314, 1274811314, 0),
('picture', '组图', '', '', '1.0.0', 'cmstop', 'http://www.cmstop.com/', 'webmaster@cmstop.com', 1274811314, 1274811314, 0),
('push', '文章推送', '', '', '1.0.0', 'cmstop', 'http://www.cmstop.com/', 'webmaster@cmstop.com', 1274811314, 1274811314, 0),
('rss', 'RSS', '', '', '1.0.0', 'cmstop', 'http://www.cmstop.com/', 'webmaster@cmstop.com', 1274811314, 1274811314, 0),
('safe', 'CmsTop安全', '', '', '1.0.0', 'cmstop', 'http://www.cmstop.com/', 'webmaster@cmstop.com', 1386907262, 1386907262, 0),
('scan', '安全检测', '', '', '1.0.0', 'cmstop', 'http://www.cmstop.com/', 'webmaster@cmstop.com', 1274811314, 1274811314, 0),
('search', '搜索', '', '', '1.0.0', 'cmstop', 'http://www.cmstop.com/', 'webmaster@cmstop.com', 1274811314, 1274811314, 0),
('space', '专栏', '', '', '1.0.0', 'cmstop', 'http://www.cmstop.com/', 'webmaster@cmstop.com', 1274811314, 1274811314, 0),
('special', '专题', '', '', '1.0.0', 'cmstop', 'http://www.cmstop.com/', 'webmaster@cmstop.com', 1274811314, 1274811314, 0),
('spider', '文章采集', '', '', '1.0.0', 'cmstop', 'http://www.cmstop.com/', 'webmaster@cmstop.com', 1274811314, 1274811314, 0),
('survey', '调查', '', '', '1.0.0', 'cmstop', 'http://www.cmstop.com/', 'webmaster@cmstop.com', 1274811314, 1274811314, 0),
('system', '系统', '', '', '1.0.0', 'cmstop', 'http://www.cmstop.com/', 'webmaster@cmstop.com', 1274811314, 1274811314, 0),
('video', '视频', '', '', '1.0.0', 'cmstop', 'http://www.cmstop.com/', 'webmaster@cmstop.com', 1274811314, 1274811314, 0),
('vote', '投票', '', '', '1.0.0', 'cmstop', 'http://www.cmstop.com/', 'webmaster@cmstop.com', 1274811314, 1274811314, 0),
('wap', 'WAP', '', '', '1.0.0', 'cmstop', 'http://www.cmstop.com/', 'webmaster@cmstop.com', 1274811314, 1274811314, 0),
('wechat', '微信', '', '', '1.0.0', 'cmstop', 'http://www.cmstop.com/', 'webmaster@cmstop.com', 1386140482, 1386140482, 0);

-- --------------------------------------------------------

--
-- Table structure for table `cmstop_article`
--

CREATE TABLE IF NOT EXISTS `cmstop_article` (
  `contentid` mediumint(8) unsigned NOT NULL,
  `subtitle` varchar(120) DEFAULT NULL,
  `author` varchar(50) DEFAULT NULL,
  `editor` varchar(15) DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  `content` mediumtext,
  `pagecount` tinyint(2) unsigned NOT NULL DEFAULT '0',
  `saveremoteimage` tinyint(1) unsigned NOT NULL DEFAULT '1',
  PRIMARY KEY (`contentid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `cmstop_article_version`
--

CREATE TABLE IF NOT EXISTS `cmstop_article_version` (
  `versionid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `contentid` mediumint(8) unsigned NOT NULL,
  `title` varchar(80) NOT NULL,
  `data` longtext,
  `created` int(10) unsigned NOT NULL,
  `createdby` mediumint(8) unsigned NOT NULL,
  PRIMARY KEY (`versionid`),
  KEY `contentid` (`contentid`,`created`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `cmstop_attachment`
--

CREATE TABLE IF NOT EXISTS `cmstop_attachment` (
  `aid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `contentid` mediumint(8) unsigned DEFAULT NULL,
  `filename` varchar(100) NOT NULL,
  `filepath` varchar(100) NOT NULL,
  `alias` varchar(50) DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  `filemime` varchar(50) NOT NULL,
  `fileext` varchar(10) NOT NULL,
  `filesize` int(10) unsigned NOT NULL DEFAULT '0',
  `isimage` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `thumb` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `downloads` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `created` int(10) unsigned NOT NULL DEFAULT '0',
  `createdby` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `ip` varchar(15) NOT NULL,
  `fid` smallint(5) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`aid`),
  KEY `fid` (`fid`,`aid`),
  KEY `createdby` (`createdby`,`aid`),
  KEY `contentid` (`contentid`,`aid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `cmstop_attachment_folder`
--

CREATE TABLE IF NOT EXISTS `cmstop_attachment_folder` (
  `fid` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `parent` smallint(5) unsigned NOT NULL DEFAULT '0',
  `name` varchar(200) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `created` int(10) unsigned NOT NULL,
  `createdby` mediumint(8) unsigned NOT NULL,
  PRIMARY KEY (`fid`),
  KEY `parent` (`parent`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `cmstop_attachment_folder_recent`
--

CREATE TABLE IF NOT EXISTS `cmstop_attachment_folder_recent` (
  `rid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` mediumint(8) unsigned NOT NULL,
  `fid` smallint(5) unsigned NOT NULL DEFAULT '0',
  `time` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`rid`),
  KEY `uid` (`uid`,`time`),
  KEY `fid` (`fid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=FIXED AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `cmstop_cache`
--

CREATE TABLE IF NOT EXISTS `cmstop_cache` (
  `tablename` varchar(30) NOT NULL,
  `primary` varchar(20) NOT NULL,
  `allcache` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `allfields` varchar(255) NOT NULL DEFAULT '*',
  `rowcache` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `rowfields` varchar(255) NOT NULL DEFAULT '*',
  PRIMARY KEY (`tablename`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

--
-- Dumping data for table `cmstop_cache`
--

INSERT INTO `cmstop_cache` (`tablename`, `primary`, `allcache`, `allfields`, `rowcache`, `rowfields`) VALUES
('admin', 'userid', 1, '*', 0, '*'),
('app', 'app', 1, 'app,name,disabled', 0, '*'),
('category', 'catid', 1, '*', 0, '*'),
('cron', 'cronid', 1, '*', 0, '*'),
('department', 'departmentid', 1, '*', 0, '*'),
('dsn', 'dsnid', 1, '*', 0, '*'),
('filterword', 'filterwordid', 1, '*', 0, '*'),
('ipbanned', 'ip', 1, '*', 0, '*'),
('keyword', 'id', 1, '*', 0, '*'),
('member_group', 'groupid', 1, '*', 0, '*'),
('menu', 'menuid', 1, '*', 0, '*'),
('model', 'modelid', 1, '*', 0, '*'),
('mood', 'moodid', 1, '*', 0, '*'),
('page', 'pageid', 1, '*', 0, '*'),
('psn', 'psnid', 1, '*', 0, '*'),
('role', 'roleid', 1, '*', 0, '*'),
('source', 'sourceid', 1, '*', 0, '*'),
('status', 'status', 1, 'status,name', 0, '*'),
('workflow', 'workflowid', 1, '*', 0, '*');

-- --------------------------------------------------------

--
-- Table structure for table `cmstop_category`
--

CREATE TABLE IF NOT EXISTS `cmstop_category` (
  `catid` smallint(5) unsigned NOT NULL auto_increment,
  `parentid` smallint(5) unsigned default NULL,
  `name` varchar(20) NOT NULL,
  `pinyin` varchar(120) default NULL,
  `abbr` varchar(20) default NULL,
  `alias` varchar(20) NOT NULL,
  `parentids` varchar(255) default NULL,
  `childids` text,
  `workflowid` tinyint(3) unsigned default NULL,
  `model` text,
  `template_index` varchar(100) NOT NULL,
  `template_list` varchar(100) NOT NULL,
  `template_date` varchar(100) NOT NULL,
  `path` varchar(100) NOT NULL,
  `url` varchar(255) NOT NULL,
  `iscreateindex` tinyint(1) unsigned NOT NULL default '1',
  `urlrule_index` varchar(100) NOT NULL,
  `urlrule_list` varchar(100) NOT NULL,
  `urlrule_show` varchar(100) NOT NULL,
  `pagesize` smallint(5) unsigned NOT NULL default '20',
  `enablecontribute` tinyint(1) unsigned NOT NULL default '0',
  `allowcomment` tinyint(1) unsigned NOT NULL default '0',
  `title` varchar(200) NOT NULL default '',
  `keywords` varchar(255) default NULL,
  `description` varchar(255) default NULL,
  `posts` mediumint(8) unsigned NOT NULL default '0',
  `comments` mediumint(8) unsigned NOT NULL default '0',
  `pv` int(10) unsigned NOT NULL default '0',
  `sort` tinyint(3) unsigned NOT NULL default '0',
  `disabled` tinyint(1) unsigned NOT NULL default '0',
  `htmlcreated` tinyint(1) unsigned NOT NULL default '1',
  `watermark` smallint(3) unsigned NOT NULL default '0',
  PRIMARY KEY  (`catid`),
  KEY `parentid` (`parentid`,`disabled`,`sort`),
  KEY `name` (`name`,`pinyin`,`abbr`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=21 ;

--
-- 转存表中的数据 `cmstop_category`
--

INSERT INTO `cmstop_category` (`catid`, `parentid`, `name`, `pinyin`, `abbr`, `alias`, `parentids`, `childids`, `workflowid`, `model`, `template_index`, `template_list`, `template_date`, `path`, `url`, `iscreateindex`, `urlrule_index`, `urlrule_list`, `urlrule_show`, `pagesize`, `enablecontribute`, `allowcomment`, `title`, `keywords`, `description`, `posts`, `comments`, `pv`, `sort`, `disabled`, `htmlcreated`, `watermark`) VALUES
(1, NULL, '思拓专区', 'situozhuanqu', 'stzq', 'cmstop', NULL, '7,8', 0, 'a:8:{i:1;a:2:{s:4:"show";s:1:"1";s:8:"template";s:17:"article/show.html";}i:2;a:2:{s:4:"show";s:1:"1";s:8:"template";s:17:"picture/show.html";}i:3;a:1:{s:4:"show";s:1:"1";}i:4;a:2:{s:4:"show";s:1:"1";s:8:"template";s:15:"video/show.html";}i:7;a:2:{s:4:"show";s:1:"1";s:8:"template";s:18:"activity/show.html";}i:8;a:2:{s:4:"show";s:1:"1";s:8:"template";s:14:"vote/show.html";}i:9;a:2:{s:4:"show";s:1:"1";s:8:"template";s:16:"survey/show.html";}i:10;a:1:{s:4:"show";s:1:"1";}}', 'system/category.html', 'system/list.html', '', '{PSN:1}', 'http://www.silkroad.news.cn/cmstop/', 1, '{$parentdir}/{$alias}/index.shtml', '{$parentdir}/{$alias}/{$model}{$page}.shtml', '{$year}/{$month}{$day}/{$contentid}{$page}.shtml', 0, 1, 0, '', '', '', 0, 0, 0, 0, 0, 0, 0),
(2, NULL, '新闻', 'xinwen', 'xw', 'news', NULL, '9,10', 0, 'a:8:{i:1;a:2:{s:4:"show";s:1:"1";s:8:"template";s:17:"article/show.html";}i:2;a:2:{s:4:"show";s:1:"1";s:8:"template";s:17:"picture/show.html";}i:3;a:1:{s:4:"show";s:1:"1";}i:4;a:2:{s:4:"show";s:1:"1";s:8:"template";s:15:"video/show.html";}i:7;a:2:{s:4:"show";s:1:"1";s:8:"template";s:18:"activity/show.html";}i:8;a:2:{s:4:"show";s:1:"1";s:8:"template";s:14:"vote/show.html";}i:9;a:2:{s:4:"show";s:1:"1";s:8:"template";s:16:"survey/show.html";}i:10;a:1:{s:4:"show";s:1:"1";}}', 'system/category.html', 'system/list.html', '', '{PSN:1}', 'http://www.silkroad.news.cn/news/', 1, '{$parentdir}/{$alias}/index.shtml', '{$parentdir}/{$alias}/{$model}{$page}.shtml', '{$year}/{$month}{$day}/{$contentid}{$page}.shtml', 0, 1, 1, '', '', '', 0, 0, 0, 0, 0, 0, 0),
(3, NULL, '娱乐', 'yule', 'yl', 'ent', NULL, '11,12,13', 0, 'a:8:{i:1;a:2:{s:4:"show";s:1:"1";s:8:"template";s:17:"article/show.html";}i:2;a:2:{s:4:"show";s:1:"1";s:8:"template";s:17:"picture/show.html";}i:3;a:1:{s:4:"show";s:1:"1";}i:4;a:2:{s:4:"show";s:1:"1";s:8:"template";s:15:"video/show.html";}i:7;a:2:{s:4:"show";s:1:"1";s:8:"template";s:18:"activity/show.html";}i:8;a:2:{s:4:"show";s:1:"1";s:8:"template";s:14:"vote/show.html";}i:9;a:2:{s:4:"show";s:1:"1";s:8:"template";s:16:"survey/show.html";}i:10;a:1:{s:4:"show";s:1:"1";}}', 'system/category.html', 'system/list.html', '', '{PSN:1}', 'http://www.silkroad.news.cn/ent/', 1, '{$parentdir}/{$alias}/index.shtml', '{$parentdir}/{$alias}/{$model}{$page}.shtml', '{$year}/{$month}{$day}/{$contentid}{$page}.shtml', 0, 1, 1, '', '', '', 0, 0, 0, 0, 0, 0, 0),
(4, NULL, '汽车', 'qiche', 'qc', 'auto', NULL, '14,15', 0, 'a:8:{i:1;a:2:{s:4:"show";s:1:"1";s:8:"template";s:17:"article/show.html";}i:2;a:2:{s:4:"show";s:1:"1";s:8:"template";s:17:"picture/show.html";}i:3;a:1:{s:4:"show";s:1:"1";}i:4;a:2:{s:4:"show";s:1:"1";s:8:"template";s:15:"video/show.html";}i:7;a:2:{s:4:"show";s:1:"1";s:8:"template";s:18:"activity/show.html";}i:8;a:2:{s:4:"show";s:1:"1";s:8:"template";s:14:"vote/show.html";}i:9;a:2:{s:4:"show";s:1:"1";s:8:"template";s:16:"survey/show.html";}i:10;a:1:{s:4:"show";s:1:"1";}}', 'system/category.html', 'system/list.html', '', '{PSN:1}', 'http://www.silkroad.news.cn/auto/', 1, '{$parentdir}/{$alias}/index.shtml', '{$parentdir}/{$alias}/{$model}{$page}.shtml', '{$year}/{$month}{$day}/{$contentid}{$page}.shtml', 0, 1, 1, '', '', '', 0, 0, 0, 0, 0, 0, 0),
(5, NULL, '房产', 'fangchan', 'fc', 'house', NULL, '16,17', 0, 'a:8:{i:1;a:2:{s:4:"show";s:1:"1";s:8:"template";s:17:"article/show.html";}i:2;a:2:{s:4:"show";s:1:"1";s:8:"template";s:17:"picture/show.html";}i:3;a:1:{s:4:"show";s:1:"1";}i:4;a:2:{s:4:"show";s:1:"1";s:8:"template";s:15:"video/show.html";}i:7;a:2:{s:4:"show";s:1:"1";s:8:"template";s:18:"activity/show.html";}i:8;a:2:{s:4:"show";s:1:"1";s:8:"template";s:14:"vote/show.html";}i:9;a:2:{s:4:"show";s:1:"1";s:8:"template";s:16:"survey/show.html";}i:10;a:1:{s:4:"show";s:1:"1";}}', 'system/category.html', 'system/list.html', '', '{PSN:1}', 'http://www.silkroad.news.cn/house/', 1, '{$parentdir}/{$alias}/index.shtml', '{$parentdir}/{$alias}/{$model}{$page}.shtml', '{$year}/{$month}{$day}/{$contentid}{$page}.shtml', 0, 1, 1, '', '', '', 0, 0, 0, 0, 0, 0, 0),
(6, NULL, '旅游', 'lvyou', 'ly', 'travel', NULL, '18,19', 0, 'a:8:{i:1;a:2:{s:4:"show";s:1:"1";s:8:"template";s:17:"article/show.html";}i:2;a:2:{s:4:"show";s:1:"1";s:8:"template";s:17:"picture/show.html";}i:3;a:1:{s:4:"show";s:1:"1";}i:4;a:2:{s:4:"show";s:1:"1";s:8:"template";s:19:"video/show_all.html";}i:7;a:2:{s:4:"show";s:1:"1";s:8:"template";s:18:"activity/show.html";}i:8;a:2:{s:4:"show";s:1:"1";s:8:"template";s:14:"vote/show.html";}i:9;a:2:{s:4:"show";s:1:"1";s:8:"template";s:16:"survey/show.html";}i:10;a:1:{s:4:"show";s:1:"1";}}', 'system/category.html', 'system/list.html', '', '{PSN:1}', 'http://www.silkroad.news.cn/travel/', 1, '{$parentdir}/{$alias}/index.shtml', '{$parentdir}/{$alias}/{$model}{$page}.shtml', '{$year}/{$month}{$day}/{$contentid}{$page}.shtml', 0, 1, 1, '', '', '', 0, 0, 0, 0, 0, 0, 0),
(7, 1, '行业动态', 'hangyedongtai', 'hydt', 'media', '1', NULL, 0, 'a:8:{i:1;a:2:{s:4:"show";s:1:"1";s:8:"template";s:17:"article/show.html";}i:2;a:2:{s:4:"show";s:1:"1";s:8:"template";s:17:"picture/show.html";}i:3;a:1:{s:4:"show";s:1:"1";}i:4;a:2:{s:4:"show";s:1:"1";s:8:"template";s:15:"video/show.html";}i:7;a:2:{s:4:"show";s:1:"1";s:8:"template";s:18:"activity/show.html";}i:8;a:2:{s:4:"show";s:1:"1";s:8:"template";s:14:"vote/show.html";}i:9;a:2:{s:4:"show";s:1:"1";s:8:"template";s:16:"survey/show.html";}i:10;a:1:{s:4:"show";s:1:"1";}}', 'system/category.html', 'system/list.html', '', '{PSN:1}', 'http://www.silkroad.news.cn/cmstop/media/', 1, '{$parentdir}/{$alias}/index.shtml', '{$parentdir}/{$alias}/{$model}{$page}.shtml', '{$year}/{$month}{$day}/{$contentid}{$page}.shtml', 0, 1, 1, '', '', '', 0, 0, 0, 0, 0, 0, 0),
(8, 1, '帮助教程', 'bangzhujiaocheng', 'bzjc', 'help', '1', NULL, 0, 'a:8:{i:1;a:2:{s:4:"show";s:1:"1";s:8:"template";s:17:"article/show.html";}i:2;a:2:{s:4:"show";s:1:"1";s:8:"template";s:17:"picture/show.html";}i:3;a:1:{s:4:"show";s:1:"1";}i:4;a:2:{s:4:"show";s:1:"1";s:8:"template";s:15:"video/show.html";}i:7;a:2:{s:4:"show";s:1:"1";s:8:"template";s:18:"activity/show.html";}i:8;a:2:{s:4:"show";s:1:"1";s:8:"template";s:14:"vote/show.html";}i:9;a:2:{s:4:"show";s:1:"1";s:8:"template";s:16:"survey/show.html";}i:10;a:1:{s:4:"show";s:1:"1";}}', 'system/category.html', 'system/list.html', '', '{PSN:1}', 'http://www.silkroad.news.cn/cmstop/help/', 1, '{$parentdir}/{$alias}/index.shtml', '{$parentdir}/{$alias}/{$model}{$page}.shtml', '{$year}/{$month}{$day}/{$contentid}{$page}.shtml', 0, 1, 1, '', '', '', 0, 0, 0, 0, 0, 0, 0),
(9, 2, '国内', 'guonei', 'gn', 'china', '2', NULL, 0, 'a:8:{i:1;a:2:{s:4:"show";s:1:"1";s:8:"template";s:17:"article/show.html";}i:2;a:2:{s:4:"show";s:1:"1";s:8:"template";s:17:"picture/show.html";}i:3;a:1:{s:4:"show";s:1:"1";}i:4;a:2:{s:4:"show";s:1:"1";s:8:"template";s:15:"video/show.html";}i:7;a:2:{s:4:"show";s:1:"1";s:8:"template";s:18:"activity/show.html";}i:8;a:2:{s:4:"show";s:1:"1";s:8:"template";s:14:"vote/show.html";}i:9;a:2:{s:4:"show";s:1:"1";s:8:"template";s:16:"survey/show.html";}i:10;a:1:{s:4:"show";s:1:"1";}}', 'system/category.html', 'system/list_pic.html', '', '{PSN:1}', 'http://www.silkroad.news.cn/news/china/', 1, '{$parentdir}/{$alias}/index.shtml', '{$parentdir}/{$alias}/{$model}{$page}.shtml', '{$year}/{$month}{$day}/{$contentid}{$page}.shtml', 0, 1, 1, '', '', '', 0, 0, 0, 0, 0, 0, 0),
(10, 2, '国际', 'guoji', 'gj', 'world', '2', NULL, 0, 'a:8:{i:1;a:2:{s:4:"show";s:1:"1";s:8:"template";s:17:"article/show.html";}i:2;a:2:{s:4:"show";s:1:"1";s:8:"template";s:17:"picture/show.html";}i:3;a:1:{s:4:"show";s:1:"1";}i:4;a:2:{s:4:"show";s:1:"1";s:8:"template";s:15:"video/show.html";}i:7;a:2:{s:4:"show";s:1:"1";s:8:"template";s:18:"activity/show.html";}i:8;a:2:{s:4:"show";s:1:"1";s:8:"template";s:14:"vote/show.html";}i:9;a:2:{s:4:"show";s:1:"1";s:8:"template";s:16:"survey/show.html";}i:10;a:1:{s:4:"show";s:1:"1";}}', 'system/category.html', 'system/list.html', '', '{PSN:1}', 'http://www.silkroad.news.cn/news/world/', 1, '{$parentdir}/{$alias}/index.shtml', '{$parentdir}/{$alias}/{$model}{$page}.shtml', '{$year}/{$month}{$day}/{$contentid}{$page}.shtml', 0, 1, 1, '', '', '', 0, 0, 0, 0, 0, 0, 0),
(11, 3, '娱乐动态', 'yuledongtai', 'yldt', 'yldt', '3', NULL, 0, 'a:8:{i:1;a:2:{s:4:"show";s:1:"1";s:8:"template";s:17:"article/show.html";}i:2;a:2:{s:4:"show";s:1:"1";s:8:"template";s:17:"picture/show.html";}i:3;a:1:{s:4:"show";s:1:"1";}i:4;a:2:{s:4:"show";s:1:"1";s:8:"template";s:15:"video/show.html";}i:7;a:2:{s:4:"show";s:1:"1";s:8:"template";s:18:"activity/show.html";}i:8;a:2:{s:4:"show";s:1:"1";s:8:"template";s:14:"vote/show.html";}i:9;a:2:{s:4:"show";s:1:"1";s:8:"template";s:16:"survey/show.html";}i:10;a:1:{s:4:"show";s:1:"1";}}', 'system/category.html', 'system/list_pic_text.html', '', '{PSN:1}', 'http://www.silkroad.news.cn/ent/yldt/', 1, '{$parentdir}/{$alias}/index.shtml', '{$parentdir}/{$alias}/{$model}{$page}.shtml', '{$year}/{$month}{$day}/{$contentid}{$page}.shtml', 0, 1, 1, '', '', '', 0, 0, 0, 0, 0, 0, 0),
(12, 3, '热剧剧情', 'rejujuqing', 'rjjq', 'rjjq', '3', NULL, 0, 'a:8:{i:1;a:2:{s:4:"show";s:1:"1";s:8:"template";s:17:"article/show.html";}i:2;a:2:{s:4:"show";s:1:"1";s:8:"template";s:17:"picture/show.html";}i:3;a:1:{s:4:"show";s:1:"1";}i:4;a:2:{s:4:"show";s:1:"1";s:8:"template";s:15:"video/show.html";}i:7;a:2:{s:4:"show";s:1:"1";s:8:"template";s:18:"activity/show.html";}i:8;a:2:{s:4:"show";s:1:"1";s:8:"template";s:14:"vote/show.html";}i:9;a:2:{s:4:"show";s:1:"1";s:8:"template";s:16:"survey/show.html";}i:10;a:1:{s:4:"show";s:1:"1";}}', 'system/category.html', 'system/list_pic.html', '', '{PSN:1}', 'http://www.silkroad.news.cn/ent/rjjq/', 1, '{$parentdir}/{$alias}/index.shtml', '{$parentdir}/{$alias}/{$model}{$page}.shtml', '{$year}/{$month}{$day}/{$contentid}{$page}.shtml', 0, 1, 1, '', '', '', 0, 0, 0, 0, 0, 0, 0),
(13, 3, '片花欣赏', 'pianhuaxinshang', 'phxs', 'phxs', '3', NULL, 0, 'a:8:{i:1;a:2:{s:4:"show";s:1:"1";s:8:"template";s:17:"article/show.html";}i:2;a:2:{s:4:"show";s:1:"1";s:8:"template";s:17:"picture/show.html";}i:3;a:1:{s:4:"show";s:1:"1";}i:4;a:2:{s:4:"show";s:1:"1";s:8:"template";s:15:"video/show.html";}i:7;a:2:{s:4:"show";s:1:"1";s:8:"template";s:18:"activity/show.html";}i:8;a:2:{s:4:"show";s:1:"1";s:8:"template";s:14:"vote/show.html";}i:9;a:2:{s:4:"show";s:1:"1";s:8:"template";s:16:"survey/show.html";}i:10;a:1:{s:4:"show";s:1:"1";}}', 'system/category.html', 'system/list.html', '', '{PSN:1}', 'http://www.silkroad.news.cn/ent/phxs/', 1, '{$parentdir}/{$alias}/index.shtml', '{$parentdir}/{$alias}/{$model}{$page}.shtml', '{$year}/{$month}{$day}/{$contentid}{$page}.shtml', 0, 1, 1, '', '', '', 0, 0, 0, 0, 0, 0, 0),
(14, 4, '行业资讯', 'hongyezixun', 'hyzx', 'hyzx', '4', NULL, 0, 'a:8:{i:1;a:2:{s:4:"show";s:1:"1";s:8:"template";s:17:"article/show.html";}i:2;a:2:{s:4:"show";s:1:"1";s:8:"template";s:17:"picture/show.html";}i:3;a:1:{s:4:"show";s:1:"1";}i:4;a:2:{s:4:"show";s:1:"1";s:8:"template";s:15:"video/show.html";}i:7;a:2:{s:4:"show";s:1:"1";s:8:"template";s:18:"activity/show.html";}i:8;a:2:{s:4:"show";s:1:"1";s:8:"template";s:14:"vote/show.html";}i:9;a:2:{s:4:"show";s:1:"1";s:8:"template";s:16:"survey/show.html";}i:10;a:1:{s:4:"show";s:1:"1";}}', 'system/category.html', 'system/list.html', '', '{PSN:1}', 'http://www.silkroad.news.cn/auto/hyzx/', 1, '{$parentdir}/{$alias}/index.shtml', '{$parentdir}/{$alias}/{$model}{$page}.shtml', '{$year}/{$month}{$day}/{$contentid}{$page}.shtml', 0, 1, 1, '', '', '', 0, 0, 0, 0, 0, 0, 0),
(15, 4, '导购试驾', 'daogoushijia', 'dgsj', 'dgsj', '4', NULL, 0, 'a:8:{i:1;a:2:{s:4:"show";s:1:"1";s:8:"template";s:17:"article/show.html";}i:2;a:2:{s:4:"show";s:1:"1";s:8:"template";s:17:"picture/show.html";}i:3;a:1:{s:4:"show";s:1:"1";}i:4;a:2:{s:4:"show";s:1:"1";s:8:"template";s:15:"video/show.html";}i:7;a:2:{s:4:"show";s:1:"1";s:8:"template";s:18:"activity/show.html";}i:8;a:2:{s:4:"show";s:1:"1";s:8:"template";s:14:"vote/show.html";}i:9;a:2:{s:4:"show";s:1:"1";s:8:"template";s:16:"survey/show.html";}i:10;a:1:{s:4:"show";s:1:"1";}}', 'system/category.html', 'system/list.html', '', '{PSN:1}', 'http://www.silkroad.news.cn/auto/dgsj/', 1, '{$parentdir}/{$alias}/index.shtml', '{$parentdir}/{$alias}/{$model}{$page}.shtml', '{$year}/{$month}{$day}/{$contentid}{$page}.shtml', 0, 1, 1, '', '', '', 0, 0, 0, 0, 0, 0, 0),
(16, 5, '房产动态', 'fangchandongtai', 'fcdt', 'fcdt', '5', NULL, 0, 'a:8:{i:1;a:2:{s:4:"show";s:1:"1";s:8:"template";s:17:"article/show.html";}i:2;a:2:{s:4:"show";s:1:"1";s:8:"template";s:17:"picture/show.html";}i:3;a:1:{s:4:"show";s:1:"1";}i:4;a:2:{s:4:"show";s:1:"1";s:8:"template";s:15:"video/show.html";}i:7;a:2:{s:4:"show";s:1:"1";s:8:"template";s:18:"activity/show.html";}i:8;a:2:{s:4:"show";s:1:"1";s:8:"template";s:14:"vote/show.html";}i:9;a:2:{s:4:"show";s:1:"1";s:8:"template";s:16:"survey/show.html";}i:10;a:1:{s:4:"show";s:1:"1";}}', 'system/category.html', 'system/list.html', '', '{PSN:1}', 'http://www.silkroad.news.cn/house/fcdt/', 1, '{$parentdir}/{$alias}/index.shtml', '{$parentdir}/{$alias}/{$model}{$page}.shtml', '{$year}/{$month}{$day}/{$contentid}{$page}.shtml', 0, 1, 1, '', '', '', 0, 0, 0, 0, 0, 0, 0),
(17, 5, '创意家居', 'chuangyijiaju', 'cyjj', 'cyjj', '5', NULL, 0, 'a:8:{i:1;a:2:{s:4:"show";s:1:"1";s:8:"template";s:17:"article/show.html";}i:2;a:2:{s:4:"show";s:1:"1";s:8:"template";s:17:"picture/show.html";}i:3;a:1:{s:4:"show";s:1:"1";}i:4;a:2:{s:4:"show";s:1:"1";s:8:"template";s:15:"video/show.html";}i:7;a:2:{s:4:"show";s:1:"1";s:8:"template";s:18:"activity/show.html";}i:8;a:2:{s:4:"show";s:1:"1";s:8:"template";s:14:"vote/show.html";}i:9;a:2:{s:4:"show";s:1:"1";s:8:"template";s:16:"survey/show.html";}i:10;a:1:{s:4:"show";s:1:"1";}}', 'system/category.html', 'system/list.html', '', '{PSN:1}', 'http://www.silkroad.news.cn/house/cyjj/', 1, '{$parentdir}/{$alias}/index.shtml', '{$parentdir}/{$alias}/{$model}{$page}.shtml', '{$year}/{$month}{$day}/{$contentid}{$page}.shtml', 0, 1, 1, '', '', '', 0, 0, 0, 0, 0, 0, 0),
(18, 6, '美食', 'meishi', 'ms', 'food', '6', NULL, 0, 'a:8:{i:1;a:2:{s:4:"show";s:1:"1";s:8:"template";s:17:"article/show.html";}i:2;a:2:{s:4:"show";s:1:"1";s:8:"template";s:17:"picture/show.html";}i:3;a:1:{s:4:"show";s:1:"1";}i:4;a:2:{s:4:"show";s:1:"1";s:8:"template";s:15:"video/show.html";}i:7;a:2:{s:4:"show";s:1:"1";s:8:"template";s:18:"activity/show.html";}i:8;a:2:{s:4:"show";s:1:"1";s:8:"template";s:14:"vote/show.html";}i:9;a:2:{s:4:"show";s:1:"1";s:8:"template";s:16:"survey/show.html";}i:10;a:1:{s:4:"show";s:1:"1";}}', 'system/category.html', 'system/list.html', '', '{PSN:1}', 'http://www.silkroad.news.cn/travel/food/', 1, '{$parentdir}/{$alias}/index.shtml', '{$parentdir}/{$alias}/{$model}{$page}.shtml', '{$year}/{$month}{$day}/{$contentid}{$page}.shtml', 0, 1, 1, '', '', '', 0, 0, 0, 0, 0, 0, 0),
(19, 6, '游记', 'youji', 'yj', 'memory', '6', NULL, 0, 'a:8:{i:1;a:2:{s:4:"show";s:1:"1";s:8:"template";s:17:"article/show.html";}i:2;a:2:{s:4:"show";s:1:"1";s:8:"template";s:17:"picture/show.html";}i:3;a:1:{s:4:"show";s:1:"1";}i:4;a:2:{s:4:"show";s:1:"1";s:8:"template";s:15:"video/show.html";}i:7;a:2:{s:4:"show";s:1:"1";s:8:"template";s:18:"activity/show.html";}i:8;a:2:{s:4:"show";s:1:"1";s:8:"template";s:14:"vote/show.html";}i:9;a:2:{s:4:"show";s:1:"1";s:8:"template";s:16:"survey/show.html";}i:10;a:1:{s:4:"show";s:1:"1";}}', 'system/category.html', 'system/list.html', '', '{PSN:1}', 'http://www.silkroad.news.cn/travel/memory/', 1, '{$parentdir}/{$alias}/index.shtml', '{$parentdir}/{$alias}/{$model}{$page}.shtml', '{$year}/{$month}{$day}/{$contentid}{$page}.shtml', 0, 1, 1, '', '', '', 0, 0, 0, 0, 0, 0, 0),
(20, NULL, '教育', 'jiaoyu', 'jy', 'jiaoyu', NULL, NULL, 0, 'a:8:{i:1;a:2:{s:4:"show";s:1:"1";s:8:"template";s:17:"article/show.html";}i:2;a:2:{s:4:"show";s:1:"1";s:8:"template";s:17:"picture/show.html";}i:3;a:1:{s:4:"show";s:1:"1";}i:4;a:2:{s:4:"show";s:1:"1";s:8:"template";s:15:"video/show.html";}i:7;a:2:{s:4:"show";s:1:"1";s:8:"template";s:18:"activity/show.html";}i:8;a:2:{s:4:"show";s:1:"1";s:8:"template";s:14:"vote/show.html";}i:9;a:2:{s:4:"show";s:1:"1";s:8:"template";s:16:"survey/show.html";}i:10;a:1:{s:4:"show";s:1:"1";}}', 'system/category.html', 'system/list.html', '', '{PSN:1}', 'http://www.silkroad.news.cn/jiaoyu/', 1, '{$parentdir}/{$alias}/index.shtml', '{$parentdir}/{$alias}/{$model}{$page}.shtml', '{$year}/{$month}{$day}/{$contentid}{$page}.shtml', 0, 1, 1, '', '', '', 0, 0, 0, 0, 0, 1, 0);

-- --------------------------------------------------------

--
-- Table structure for table `cmstop_category_field`
--

CREATE TABLE IF NOT EXISTS `cmstop_category_field` (
  `projectid` tinyint(3) unsigned NOT NULL,
  `catid` smallint(5) unsigned NOT NULL,
  PRIMARY KEY (`projectid`,`catid`),
  KEY `fid` (`projectid`),
  KEY `catid` (`catid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `cmstop_category_priv`
--

CREATE TABLE IF NOT EXISTS `cmstop_category_priv` (
  `catid` smallint(5) unsigned NOT NULL DEFAULT '0',
  `userid` mediumint(8) unsigned NOT NULL DEFAULT '0',
  KEY `catid` (`catid`),
  KEY `userid` (`userid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `cmstop_cdn`
--

CREATE TABLE IF NOT EXISTS `cmstop_cdn` (
  `cdnid` smallint(5) NOT NULL AUTO_INCREMENT,
  `name` varchar(20) NOT NULL,
  `tid` smallint(5) NOT NULL,
  PRIMARY KEY (`cdnid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `cmstop_cdn_parameter`
--

CREATE TABLE IF NOT EXISTS `cmstop_cdn_parameter` (
  `id` smallint(5) NOT NULL AUTO_INCREMENT,
  `cdnid` smallint(5) NOT NULL,
  `key` varchar(20) NOT NULL,
  `value` varchar(200) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `cdnid` (`cdnid`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `cmstop_cdn_rules`
--

CREATE TABLE IF NOT EXISTS `cmstop_cdn_rules` (
  `id` smallint(5) NOT NULL AUTO_INCREMENT,
  `cdnid` smallint(5) NOT NULL,
  `path` varchar(100) DEFAULT NULL,
  `url` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `cdnid` (`cdnid`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `cmstop_cdn_type`
--

CREATE TABLE IF NOT EXISTS `cmstop_cdn_type` (
  `tid` smallint(5) NOT NULL AUTO_INCREMENT,
  `name` varchar(20) NOT NULL,
  `parameter` varchar(200) NOT NULL,
  `type` varchar(20) NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`tid`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=4 ;

--
-- Dumping data for table `cmstop_cdn_type`
--

INSERT INTO `cmstop_cdn_type` (`tid`, `name`, `parameter`, `type`, `status`) VALUES
(1, '蓝汛(ChinaCache)', '{"user":"\\u7528\\u6237\\u540d","pswd":"\\u5bc6\\u7801"}', 'chinacache', 1),
(2, '网宿科技', '{"user":"\\u7528\\u6237\\u540d","pswd":"\\u5bc6\\u7801"}', 'wscp', 1),
(3, '蓝汛(ChinaCache)_V4', '{"user":"\\u7528\\u6237\\u540d:","pswd":"\\u5bc6\\u7801:"}', 'chinacache_v4', 1);

-- --------------------------------------------------------

--
-- Table structure for table `cmstop_comment`
--

CREATE TABLE IF NOT EXISTS `cmstop_comment` (
  `commentid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `topicid` int(10) unsigned NOT NULL,
  `sourceid` smallint(5) unsigned DEFAULT NULL,
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
  `sourceinfo` varchar(300) DEFAULT NULL,
  PRIMARY KEY (`commentid`),
  UNIQUE KEY `createdby` (`createdby`,`created`),
  KEY `topicid` (`topicid`,`istop`,`created`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `cmstop_comment_report`
--

CREATE TABLE IF NOT EXISTS `cmstop_comment_report` (
  `reportid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `commentid` int(10) unsigned NOT NULL,
  `ip` char(15) NOT NULL,
  `created` int(10) unsigned NOT NULL,
  `createdby` mediumint(8) unsigned DEFAULT NULL,
  PRIMARY KEY (`reportid`),
  KEY `commentid` (`commentid`,`createdby`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `cmstop_comment_source`
--

CREATE TABLE IF NOT EXISTS `cmstop_comment_source` (
  `sourceid` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `identity` varchar(32) NOT NULL,
  `name` varchar(40) NOT NULL,
  `url` varchar(255) NOT NULL,
  `ico` varchar(255) DEFAULT NULL,
  `params` varchar(300) NOT NULL DEFAULT '[]',
  `state` tinyint(1) unsigned NOT NULL DEFAULT '1',
  PRIMARY KEY (`sourceid`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `cmstop_comment_source`
--

INSERT INTO `cmstop_comment_source` (`sourceid`, `identity`, `name`, `url`, `ico`, `params`, `state`) VALUES
(1, 'tencent_weibo', '腾讯微博', 'http://t.qq.com/', 'http://img.silkroad.news.cn/images/tencent.png', '[{"id":"verify_token","name":"腾讯微博令牌","value":""}]', 0);

-- --------------------------------------------------------

--
-- Table structure for table `cmstop_comment_support`
--

CREATE TABLE IF NOT EXISTS `cmstop_comment_support` (
  `supportid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `commentid` int(10) unsigned NOT NULL,
  `ip` char(15) NOT NULL,
  `created` int(10) unsigned NOT NULL,
  `createdby` mediumint(8) unsigned DEFAULT NULL,
  PRIMARY KEY (`supportid`),
  KEY `commentid` (`commentid`,`createdby`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `cmstop_comment_topic`
--

CREATE TABLE IF NOT EXISTS `cmstop_comment_topic` (
  `topicid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(80) NOT NULL,
  `url` varchar(255) NOT NULL,
  `description` varchar(255) NOT NULL,
  `thumb` varchar(100) NOT NULL,
  `disabled` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `created` int(10) unsigned NOT NULL,
  `createdby` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `updated` int(10) unsigned DEFAULT NULL,
  `updatedby` mediumint(8) unsigned DEFAULT NULL,
  `url_md5` varchar(32) NOT NULL,
  `comments` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `comments_pend` smallint(5) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`topicid`),
  KEY `url_md5` (`url_md5`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `cmstop_content`
--

CREATE TABLE IF NOT EXISTS `cmstop_content` (
  `contentid` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `catid` smallint(5) unsigned NOT NULL,
  `modelid` tinyint(2) unsigned NOT NULL,
  `title` varchar(200) NOT NULL,
  `subtitle` varchar(120) DEFAULT NULL,
  `color` char(7) DEFAULT NULL,
  `thumb` varchar(200) DEFAULT NULL,
  `tags` char(60) DEFAULT NULL,
  `sourceid` mediumint(8) unsigned DEFAULT NULL,
  `source_title` varchar(255) DEFAULT NULL,
  `source_link` varchar(255) DEFAULT NULL,
  `topicid` int(10) unsigned DEFAULT NULL,
  `url` varchar(255) DEFAULT NULL,
  `weight` tinyint(3) unsigned NOT NULL DEFAULT '60',
  `status` tinyint(1) unsigned NOT NULL DEFAULT '6',
  `status_old` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `created` int(10) unsigned NOT NULL,
  `createdby` mediumint(8) unsigned NOT NULL,
  `published` int(10) unsigned DEFAULT NULL,
  `publishedby` mediumint(8) unsigned DEFAULT NULL,
  `unpublished` int(10) unsigned DEFAULT NULL,
  `unpublishedby` mediumint(8) unsigned DEFAULT NULL,
  `modified` int(10) unsigned DEFAULT NULL,
  `modifiedby` mediumint(8) unsigned DEFAULT NULL,
  `checked` int(10) unsigned DEFAULT NULL,
  `checkedby` mediumint(8) unsigned DEFAULT NULL,
  `locked` int(10) unsigned DEFAULT NULL,
  `lockedby` mediumint(8) unsigned DEFAULT NULL,
  `noted` int(10) unsigned DEFAULT NULL,
  `notedby` mediumint(8) unsigned DEFAULT NULL,
  `note` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `workflow_step` tinyint(1) unsigned DEFAULT NULL,
  `workflow_roleid` tinyint(3) unsigned DEFAULT NULL,
  `iscontribute` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `spaceid` mediumint(8) unsigned DEFAULT NULL,
  `related` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `pv` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `allowcomment` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `comments` smallint(5) unsigned NOT NULL DEFAULT '0',
  `score` tinyint(2) unsigned NOT NULL,
  `tweeted` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`contentid`),
  KEY `catid` (`status`, `catid`, `published`, `modelid`) USING BTREE,
  KEY `weight` (`weight`, `status`,`catid`, `published`) USING BTREE,
  KEY `createdby` (`createdby`, `status`, `published`) USING BTREE,
  KEY `status` (`status`, `published`, `modelid`) USING BTREE,
  KEY `topicid` (`topicid`),
  KEY `spaceid` (`spaceid`, `status`, `published`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `cmstop_content_addon`
--

CREATE TABLE IF NOT EXISTS `cmstop_content_addon` (
  `contentid` mediumint(8) unsigned NOT NULL,
  `engine` varchar(20) NOT NULL,
  `addonid` int(10) unsigned NOT NULL,
  `place` varchar(50) NOT NULL,
  PRIMARY KEY (`contentid`,`addonid`),
  KEY `addonid` (`addonid`),
  KEY `contentid` (`contentid`,`place`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `cmstop_content_log`
--

CREATE TABLE IF NOT EXISTS `cmstop_content_log` (
  `logid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `contentid` mediumint(8) unsigned NOT NULL,
  `action` varchar(10) NOT NULL,
  `created` int(10) unsigned NOT NULL,
  `createdby` mediumint(8) unsigned NOT NULL,
  `ip` varchar(15) NOT NULL,
  PRIMARY KEY (`logid`),
  KEY `createdby` (`createdby`),
  KEY `contentid` (`contentid`,`created`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `cmstop_content_meta`
--

CREATE TABLE IF NOT EXISTS `cmstop_content_meta` (
  `contentid` mediumint(8) unsigned NOT NULL,
  `data` text,
  PRIMARY KEY (`contentid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `cmstop_content_note`
--

CREATE TABLE IF NOT EXISTS `cmstop_content_note` (
  `noteid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `contentid` mediumint(8) unsigned NOT NULL,
  `note` varchar(255) NOT NULL,
  `created` int(10) unsigned NOT NULL,
  `createdby` mediumint(8) unsigned NOT NULL,
  PRIMARY KEY (`noteid`),
  KEY `createdby` (`createdby`),
  KEY `contentid` (`contentid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `cmstop_content_property`
--

CREATE TABLE IF NOT EXISTS `cmstop_content_property` (
  `contentid` mediumint(8) unsigned NOT NULL COMMENT '内容ID',
  `proid` smallint(5) unsigned NOT NULL COMMENT '属性ID',
  PRIMARY KEY (`proid`,`contentid`),
  KEY `contentid` (`contentid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `cmstop_content_tag`
--

CREATE TABLE IF NOT EXISTS `cmstop_content_tag` (
  `contentid` mediumint(8) unsigned NOT NULL,
  `tagid` int(10) unsigned NOT NULL,
  PRIMARY KEY (`tagid`,`contentid`),
  KEY `contentid` (`contentid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `cmstop_content_version`
--

CREATE TABLE IF NOT EXISTS `cmstop_content_version` (
  `versionid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `contentid` mediumint(8) unsigned NOT NULL,
  `name` varchar(50) NOT NULL,
  `data` longtext,
  `created` int(10) unsigned NOT NULL,
  `createdby` mediumint(8) unsigned NOT NULL,
  `ip` varchar(15) NOT NULL,
  PRIMARY KEY (`versionid`),
  KEY `contentid` (`contentid`),
  KEY `createdby` (`createdby`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `cmstop_contribution`
--

CREATE TABLE IF NOT EXISTS `cmstop_contribution` (
  `contributionid` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `title` char(80) NOT NULL,
  `content` mediumtext,
  `description` varchar(255) NOT NULL,
  `tags` char(60) DEFAULT NULL,
  `author` varchar(50) DEFAULT NULL,
  `sourcetype` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `sourcename` char(40) NOT NULL,
  `sourceurl` char(100) NOT NULL,
  `created` int(10) unsigned NOT NULL,
  `createdby` mediumint(8) unsigned DEFAULT NULL,
  `catid` smallint(5) unsigned NOT NULL,
  `contentid` mediumint(8) unsigned DEFAULT NULL,
  `status` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `creator` varchar(20) DEFAULT NULL,
  `email` char(40) NOT NULL,
  `isnotice` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `annotation` text NOT NULL,
  PRIMARY KEY (`contributionid`),
  KEY `status` (`status`,`created`),
  KEY `createdby` (`createdby`,`status`,`created`),
  KEY `catid` (`catid`,`status`,`created`),
  KEY `contentid` (`contentid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `cmstop_contribution_log`
--

CREATE TABLE IF NOT EXISTS `cmstop_contribution_log` (
  `logid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `contributionid` mediumint(8) unsigned NOT NULL,
  `status` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `action` varchar(10) NOT NULL,
  `created` int(10) unsigned NOT NULL,
  `createdby` mediumint(8) unsigned NOT NULL,
  `note` varchar(255) NOT NULL,
  PRIMARY KEY (`logid`),
  KEY `contributionid` (`contributionid`,`createdby`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `cmstop_cron`
--

CREATE TABLE IF NOT EXISTS `cmstop_cron` (
  `cronid` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `type` enum('system','special') DEFAULT 'system',
  `name` varchar(50) NOT NULL,
  `app` varchar(20) NOT NULL,
  `param` varchar(100) DEFAULT NULL,
  `controller` varchar(20) NOT NULL,
  `action` varchar(20) NOT NULL,
  `lastrun` int(10) unsigned DEFAULT NULL,
  `nextrun` int(10) unsigned DEFAULT NULL,
  `mode` tinyint(1) unsigned DEFAULT '1',
  `time` int(10) unsigned DEFAULT NULL,
  `starttime` int(10) unsigned DEFAULT NULL,
  `interval` smallint(5) unsigned DEFAULT NULL,
  `times` smallint(5) unsigned DEFAULT NULL,
  `already` smallint(5) unsigned NOT NULL DEFAULT '0',
  `endtime` int(10) unsigned DEFAULT NULL,
  `day` varchar(90) DEFAULT NULL,
  `weekday` varchar(15) DEFAULT NULL,
  `hour` varchar(64) DEFAULT NULL,
  `minute` varchar(20) DEFAULT NULL,
  `rule` varchar(100) DEFAULT NULL,
  `disabled` tinyint(1) unsigned DEFAULT '0',
  `hidden` tinyint(1) unsigned DEFAULT '0',
  PRIMARY KEY (`cronid`),
  KEY `nextrun` (`nextrun`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='计划任务管理' AUTO_INCREMENT=40 ;

--
-- Dumping data for table `cmstop_cron`
--

INSERT INTO `cmstop_cron` (`cronid`, `type`, `name`, `app`, `param`, `controller`, `action`, `lastrun`, `nextrun`, `mode`, `time`, `starttime`, `interval`, `times`, `already`, `endtime`, `day`, `weekday`, `hour`, `minute`, `rule`, `disabled`, `hidden`) VALUES
(1, 'system', '文章定时上下线', 'article', '', 'article', 'cron', 1348932032, 1348932120, 2, 0, 0, 1, 0, 4, 0, NULL, NULL, NULL, NULL, NULL, 0, 0),
(2, 'system', '组图定时上下线', 'picture', '', 'picture', 'cron', 1348932032, 1348932120, 2, 0, 0, 1, 0, 4, 0, NULL, NULL, NULL, NULL, NULL, 0, 0),
(3, 'system', '链接定时上下线', 'link', '', 'link', 'cron', 1348932032, 1348932120, 2, 0, 0, 1, 0, 4, 0, NULL, NULL, NULL, NULL, NULL, 0, 0),
(4, 'system', '视频定时上下线', 'video', '', 'video', 'cron', 1348932032, 1348932120, 2, 0, 0, 1, 0, 4, 0, NULL, NULL, NULL, NULL, NULL, 0, 0),
(6, 'system', '活动定时上下线', 'activity', '', 'activity', 'cron', 1348932032, 1348932120, 2, 0, 0, 1, 0, 4, 0, NULL, NULL, NULL, NULL, NULL, 0, 0),
(7, 'system', '投票定时上下线', 'vote', '', 'vote', 'cron', 1348932032, 1348932120, 2, 0, 0, 1, 0, 4, 0, NULL, NULL, NULL, NULL, NULL, 0, 0),
(8, 'system', '调查定时上下线', 'survey', '', 'survey', 'cron', 1348932032, 1348932120, 2, 0, 0, 1, 0, 4, 0, NULL, NULL, NULL, NULL, NULL, 0, 0),
(9, 'system', '专题定时上下线', 'special', '', 'special', 'cron', 1348932032, 1348932120, 2, 0, 0, 1, 0, 4, 0, NULL, NULL, NULL, NULL, NULL, 0, 0),
(10, 'system', '滚动新闻定时更新', 'system', '', 'html', 'roll_cron', 1348932032, 1348932360, 2, 0, 0, 5, 0, 4, 0, NULL, NULL, NULL, NULL, NULL, 0, 0),
(11, 'system', '栏目页定时更新', 'system', '', 'html', 'category_cron', 1348932032, 1348934460, 3, 0, 0, 0, 0, 0, 0, '', '', '0,4,8,12,16,20', '', NULL, 0, 0),
(12, 'system', '网站地图定时更新', 'system', '', 'html', 'map_cron', 1348895705, 1348947060, 3, 0, 0, 0, 0, 0, 0, '', '', '3', '30', NULL, 0, 0),
(13, 'system', '排行榜定时更新', 'system', '', 'html', 'rank_cron', 1348932032, 1348935660, 2, 0, 0, 60, 0, 4, 0, NULL, NULL, NULL, NULL, NULL, 0, 0),
(14, 'system', '热门标签定时更新', 'system', '', 'html', 'tags_cron', 1348932032, 1348935660, 2, 0, 0, 60, 0, 4, 0, NULL, NULL, NULL, NULL, NULL, 0, 0),
(15, 'system', '百度新闻源更新', 'system', '', 'baidunews', 'xml', 1348932032, 1348932360, 2, 0, 0, 5, 0, 4, 0, '', '0,1,2,3,4,5,6', '9', '10', NULL, 0, 0),
(16, 'system', 'Sitemaps 定时更新', 'system', '', 'sitemaps', 'xml', 1348932032, 1348933860, 2, 0, 0, 30, 0, 3, 0, NULL, NULL, NULL, NULL, NULL, 0, 0),
(18, 'system', '访问统计', 'system', '', 'stat', 'cron_pv', 1348932032, 1348932660, 2, 0, 0, 10, 0, 3, 0, '', '', '3', '0', NULL, 0, 0),
(20, 'system', '页面定时更新', 'page', '', 'page', 'cron', 1348932032, 1348932120, 2, 0, 0, 1, 0, 4, 0, NULL, NULL, NULL, NULL, NULL, 0, 0),
(21, 'system', '区块定时更新', 'page', '', 'section', 'cron', 1348932032, 1348932120, 2, 0, 0, 1, 0, 4, 0, NULL, NULL, NULL, NULL, NULL, 0, 0),
(22, 'system', '心情定时更新', 'mood', '', 'mood', 'publish', 1348932032, 1348935660, 2, 0, 0, 60, 0, 3, 0, NULL, NULL, NULL, NULL, NULL, 0, 0),
(23, 'system', 'Digg定时更新', 'history', 'hid=1', 'history', 'exec', 1348895541, 1348934460, 3, 0, 0, 60, 0, 0, 0, '', '', '', '', NULL, 0, 1),
(24, 'system', '首页历史页面', 'history', 'hid=1', 'history', 'exec', 1348932032, 1348966860, 3, 0, 0, NULL, 0, 0, 0, '', '', '9,11,13,15,17,19,21', '', NULL, 0, 0),
(25, 'system', '网站首面', 'history', 'hid=2', 'history', 'exec', 1348932032, 1348934460, 3, NULL, 0, NULL, NULL, 0, 0, '', '', '0,12,14', '', NULL, 0, 1),
(26, 'system', '自由列表定时更新', 'freelist', '', 'freelist', 'cron', 1348932032, 1348932120, 2, 0, 0, 1, 0, 4, 0, NULL, NULL, NULL, NULL, NULL, 0, 0),
(27, 'system', '区块日志定时删除', 'page', '', 'section', 'clear_early_log', 1348895716, 1348982160, 2, 0, 0, 1440, 0, 2, 0, NULL, NULL, NULL, NULL, NULL, 0, 0),
(28, 'system', '计划任务日志定时删除', 'system', '', 'cron', 'clear_early_log', 1348895716, 1348982160, 2, 0, 0, 1440, 0, 2, 0, NULL, NULL, NULL, NULL, NULL, 0, 0),
(29, 'system', '专题自动刷新', 'special', '', 'online', 'cron', 1348932032, 1348932120, 2, 0, 0, 1, 0, 5, 0, NULL, NULL, NULL, NULL, NULL, 0, 0),
(30, 'system', '页面定时删除区块历史数据', 'page', '', 'page', 'cron_remove_history', 1348895718, 1348934460, 3, 0, 0, 0, 0, 0, 0, '', '', '', '', NULL, 0, 0),
(31, 'system', '页面定时删除区块操作记录', 'page', '', 'page', 'cron_remove_log', 1348895718, 1348934460, 3, 0, 0, 0, 0, 0, 0, '', '', '', '', NULL, 0, 0),
(32, 'system', '数字报自动抓取', 'epaper', NULL, 'import', 'cron', 1348932032, 1348932660, 2, NULL, NULL, 10, 0, 6, NULL, NULL, NULL, NULL, NULL, NULL, 1, 0),
(33, 'system', '文章定时采集', 'spider', '', 'cron', 'cron', NULL, 1352688180, 2, 0, 0, 10, 0, 1, 0, NULL, NULL, NULL, NULL, '', 0, 0),
(34, 'system', '移动内容统计定时入库', 'mobile', '', 'stat', 'cron_stat', 1362061382, 1362062040, 2, 0, 0, 10, 0, 9, 0, NULL, NULL, NULL, NULL, '', 0, 0),
(35, 'system', '二维码统计定时入库', 'system', '', 'qrcode', 'cron_stat', 1362850551, 1362851160, 2, 0, 0, 10, 0, 18, 0, NULL, NULL, NULL, NULL, '', 0, 0),
(36, 'system', '移动版内容自动抓取', 'mobile', '', 'autofill', 'cron', 1363413839, 1363413900, 2, 0, 0, 1, 0, 2, 0, NULL, NULL, NULL, NULL, '', 0, 0),
(38, 'system', '操作日志定时清理', 'system', '', 'adminlog', 'cron', 1363413839, 1363413900, 2, 0, 0, 1440, 0, 2, 0, NULL, NULL, NULL, NULL, NULL, 0, 0),
(39, 'system', '投票定时更新', 'vote', '', 'vote', 'cron_adder', 1348932032, 1348932120, 2, 0, 0, 1, 0, 4, 0, NULL, NULL, NULL, NULL, NULL, 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `cmstop_cron_log`
--

CREATE TABLE IF NOT EXISTS `cmstop_cron_log` (
  `logid` mediumint(9) unsigned NOT NULL AUTO_INCREMENT,
  `cronid` smallint(5) unsigned DEFAULT NULL,
  `runtime` int(10) unsigned DEFAULT NULL,
  `fintime` varchar(13) NOT NULL DEFAULT '0',
  `expend` int(10) unsigned NOT NULL DEFAULT '0',
  `runSuccess` tinyint(1) unsigned DEFAULT '1',
  `info` varchar(255) DEFAULT NULL,
  `success` tinyint(1) unsigned DEFAULT '1',
  `error` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`logid`),
  KEY `cronid` (`cronid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `cmstop_department`
--

CREATE TABLE IF NOT EXISTS `cmstop_department` (
  `departmentid` tinyint(3) unsigned NOT NULL AUTO_INCREMENT,
  `parentid` tinyint(3) unsigned DEFAULT NULL,
  `parentids` varchar(255) DEFAULT NULL,
  `childids` text,
  `name` varchar(20) NOT NULL,
  `sort` tinyint(3) unsigned DEFAULT '0',
  `leaderid` tinyint(3) unsigned DEFAULT NULL,
  `posts` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `comments` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `pv` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`departmentid`),
  KEY `parentid` (`parentid`),
  KEY `leaderid` (`leaderid`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=6 ;

--
-- Dumping data for table `cmstop_department`
--

INSERT INTO `cmstop_department` (`departmentid`, `parentid`, `parentids`, `childids`, `name`, `sort`, `leaderid`, `posts`, `comments`, `pv`) VALUES
(1, NULL, NULL, '3,4,5', '编辑部', 0, 2, 0, 0, 0),
(2, NULL, NULL, NULL, '技术部', 0, 12, 0, 0, 0),
(3, 1, '1', NULL, '新闻频道', 0, 3, 0, 0, 0),
(4, 1, '1', NULL, '娱乐频道', 0, 3, 0, 0, 0),
(5, 1, '1', NULL, '科技频道', 0, 3, 0, 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `cmstop_department_role`
--

CREATE TABLE IF NOT EXISTS `cmstop_department_role` (
  `departmentid` tinyint(3) unsigned NOT NULL,
  `roleid` tinyint(3) unsigned NOT NULL,
  KEY `roleid` (`roleid`),
  KEY `departmentid` (`departmentid`,`roleid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `cmstop_department_role`
--

INSERT INTO `cmstop_department_role` (`departmentid`, `roleid`) VALUES
(1, 2),
(1, 9),
(1, 10),
(1, 11),
(2, 1),
(3, 3),
(3, 4),
(3, 5),
(3, 6),
(3, 7),
(4, 3),
(4, 4),
(4, 5),
(4, 6),
(4, 7),
(5, 3),
(5, 4),
(5, 5),
(5, 6),
(5, 7);

-- --------------------------------------------------------

--
-- Table structure for table `cmstop_digg`
--

CREATE TABLE IF NOT EXISTS `cmstop_digg` (
  `contentid` mediumint(8) unsigned NOT NULL,
  `supports` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `againsts` mediumint(8) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`contentid`),
  KEY `supports` (`supports`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `cmstop_digg_log`
--

CREATE TABLE IF NOT EXISTS `cmstop_digg_log` (
  `contentid` mediumint(8) unsigned NOT NULL,
  `flag` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `userid` mediumint(8) unsigned DEFAULT NULL,
  `username` char(15) DEFAULT NULL,
  `ip` char(15) NOT NULL,
  `datetime` int(10) unsigned NOT NULL,
  KEY `contentid` (`contentid`,`flag`,`datetime`),
  KEY `userid` (`userid`,`contentid`),
  KEY `ip` (`ip`,`contentid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `cmstop_dsn`
--

CREATE TABLE IF NOT EXISTS `cmstop_dsn` (
  `dsnid` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL DEFAULT '',
  `driver` varchar(10) NOT NULL,
  `host` varchar(100) NOT NULL DEFAULT '',
  `port` smallint(6) DEFAULT NULL,
  `username` varchar(20) NOT NULL DEFAULT '',
  `password` varchar(30) DEFAULT NULL,
  `dbname` varchar(20) NOT NULL,
  `pconnect` tinyint(1) NOT NULL DEFAULT '0',
  `charset` varchar(20) DEFAULT NULL,
  `created` int(10) NOT NULL DEFAULT '0',
  PRIMARY KEY (`dsnid`),
  UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `cmstop_editor_template`
--

CREATE TABLE IF NOT EXISTS `cmstop_editor_template` (
  `templateid` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(40) NOT NULL,
  `content` text,
  PRIMARY KEY (`templateid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `cmstop_epaper`
--

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
  `content_source_start` varchar(255) NOT NULL DEFAULT '',
  `content_source_end` varchar(255) NOT NULL DEFAULT '',
  `allow_tags` varchar(100) NOT NULL DEFAULT 'a,b,p,br,img,span,strong',
  `default_catid` mediumint(8) DEFAULT NULL,
  `import_list` text,
  `allowed_auto` text,
  `default_state` tinyint(1) unsigned NOT NULL DEFAULT '3',
  PRIMARY KEY (`epid`),
  KEY `sort` (`sort`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `cmstop_epaper`
--

INSERT INTO `cmstop_epaper` (`epid`, `name`, `type`, `sort`, `state`, `charset`, `get_url_type`, `epaper_rule`, `edition_cycle`, `first_time`, `epaper_limit`, `list_start`, `list_end`, `list_rule`, `content_start`, `content_end`, `content_rule`, `content_scope_start`, `content_scope_end`, `content_title_start`, `content_title_end`, `content_article_start`, `content_article_end`, `content_author_start`, `content_author_end`, `allow_tags`, `default_catid`, `import_list`, `allowed_auto`, `default_state`) VALUES
(1, '证劵时报', 1, 0, 1, 'UTF-8', 0, 'http://epaper.stcn.com/paper/zqsb/html/(Y)-(M)/(D)/node_2.htm', '1D ', 1338739200, 10, '<!-------bmdh版面导航------>\n<div id=bmdh>', '</div>\n<!-------bmdh版面导航END------>', 'http://epaper.stcn.com/paper/zqsb/html/(*)/node_(*).htm', '<!-------bmdh版面导航END------>\n<!-- -------------------------标题导航-------------->', '<!-- -------------------------标题导航 END -------------->', 'http://epaper.stcn.com/paper/zqsb/html/(*)/content_(*).htm', '<!-- =========================================标题开始 ====================================== --->', '<!-- ===========================文章内容end ========================================= --', '</div> <h2>', '</h2>', '<div id="mainCon">', '</P></founder-content>', '作者：', '</div>', 'a,b,p,br,img,span,strong', 4, '[0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0]', '["0","1","2","3","4","5","6","7","8","9","10","11","12","13","14","15","16","17","18","19","20","21","22","23","24","25","26","27","28","29","30","31","32","33","34","35","36","37","38","39","40","41","42","43","44","45","46","47"]', 3);

-- --------------------------------------------------------

--
-- Table structure for table `cmstop_epaper_content`
--

CREATE TABLE IF NOT EXISTS `cmstop_epaper_content` (
  `spiderid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `guid` char(32) NOT NULL,
  `epaperid` smallint(3) unsigned NOT NULL,
  `editionid` int(10) unsigned NOT NULL,
  `catid` int(8) unsigned DEFAULT NULL,
  `contentid` int(10) unsigned DEFAULT NULL,
  `title` varchar(120) NOT NULL DEFAULT '',
  `url` varchar(255) NOT NULL,
  `status` enum('viewed','spiden','cron','new','failed') NOT NULL,
  `spiden` int(10) unsigned DEFAULT NULL,
  `spidenby` mediumint(8) unsigned DEFAULT NULL,
  PRIMARY KEY (`spiderid`),
  KEY `epaperid` (`epaperid`),
  KEY `epaperid_2` (`epaperid`,`editionid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `cmstop_epaper_cron`
--

CREATE TABLE IF NOT EXISTS `cmstop_epaper_cron` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `epaperid` smallint(3) unsigned NOT NULL,
  `editionid` int(11) NOT NULL,
  `total` mediumint(8) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `cmstop_eventlive`
--

CREATE TABLE IF NOT EXISTS  `cmstop_eventlive` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `bgimg` varchar(255) NOT NULL DEFAULT '',
  `introduction` varchar(300) NOT NULL DEFAULT '',
  `status` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '状态，0 正常，1 已关闭',
  PRIMARY KEY (`id`),
  KEY `id` (`id`,`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `cmstop_eventlive_member`
--

CREATE TABLE IF NOT EXISTS `cmstop_eventlive_member` (
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

-- --------------------------------------------------------

--
-- Table structure for table `cmstop_eventlive_post`
--

CREATE TABLE IF NOT EXISTS `cmstop_eventlive_post` (
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

-- --------------------------------------------------------

--
-- Table structure for table `cmstop_eventlive_post_stat`
--

CREATE TABLE IF NOT EXISTS `cmstop_eventlive_post_stat` (
  `postid` int(10) unsigned NOT NULL COMMENT '直播内容ID',
  `share` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '分享数',
  `support` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '支持数',
  PRIMARY KEY (`postid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `cmstop_eventlive_stat`
--

CREATE TABLE IF NOT EXISTS `cmstop_eventlive_stat` (
  `liveid` int(10) unsigned NOT NULL COMMENT '直播ID',
  `share` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '分享数',
  `support` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '支持数',
  PRIMARY KEY (`liveid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `cmstop_field`
--

CREATE TABLE IF NOT EXISTS `cmstop_field` (
  `fieldid` tinyint(3) unsigned NOT NULL AUTO_INCREMENT,
  `field` varchar(10) NOT NULL,
  `projectid` tinyint(3) NOT NULL,
  `setting` text,
  `sort` int(10) unsigned NOT NULL,
  `created` int(10) unsigned NOT NULL,
  `createdby` mediumint(8) unsigned NOT NULL,
  PRIMARY KEY (`fieldid`),
  KEY `tid` (`field`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `cmstop_field_project`
--

CREATE TABLE IF NOT EXISTS `cmstop_field_project` (
  `projectid` tinyint(3) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(30) NOT NULL,
  `description` varchar(255) NOT NULL,
  PRIMARY KEY (`projectid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `cmstop_filterword`
--

CREATE TABLE IF NOT EXISTS `cmstop_filterword` (
  `filterwordid` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `pattern` varchar(100) NOT NULL,
  `replacement` varchar(100) NOT NULL,
  PRIMARY KEY (`filterwordid`),
  UNIQUE KEY `pattern` (`pattern`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `cmstop_freelist`
--

CREATE TABLE IF NOT EXISTS `cmstop_freelist` (
  `flid` smallint(5) unsigned NOT NULL AUTO_INCREMENT COMMENT '自由列表id',
  `gid` smallint(5) unsigned DEFAULT NULL COMMENT '自由列表名称',
  `name` varchar(30) NOT NULL COMMENT '列表页名称',
  `filename` varchar(30) NOT NULL COMMENT '文件名称',
  `path` varchar(100) NOT NULL COMMENT '网址',
  `type` tinyint(3) unsigned NOT NULL COMMENT '列表类型',
  `template` varchar(100) DEFAULT NULL COMMENT '页面模版',
  `maxpage` tinyint(3) unsigned NOT NULL COMMENT '最大生成页数',
  `pagesize` tinyint(3) unsigned NOT NULL DEFAULT '20' COMMENT '分页每页条数',
  `frequency` smallint(5) unsigned NOT NULL COMMENT '生成频率',
  `autopublish` tinyint(3) unsigned NOT NULL COMMENT '手动更新',
  `title` varchar(100) NOT NULL COMMENT '页面标题',
  `keywords` varchar(255) NOT NULL COMMENT '关键字',
  `description` text NOT NULL COMMENT '描述',
  `filterules` text NOT NULL COMMENT '筛选器规则json数据',
  `created` int(10) unsigned NOT NULL COMMENT '创建时间',
  `createdby` mediumint(8) unsigned NOT NULL COMMENT '创建者',
  `modified` int(10) unsigned DEFAULT NULL COMMENT '修改时间',
  `modifiedby` mediumint(8) unsigned DEFAULT NULL COMMENT '修改者',
  `published` int(10) unsigned DEFAULT NULL COMMENT '生成时间',
  `nextpublish` int(10) unsigned DEFAULT NULL COMMENT '下次生成时间',
  PRIMARY KEY (`flid`),
  KEY `gid` (`gid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `cmstop_freelist_group`
--

CREATE TABLE IF NOT EXISTS `cmstop_freelist_group` (
  `gid` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(20) NOT NULL,
  PRIMARY KEY (`gid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `cmstop_guestbook`
--

CREATE TABLE IF NOT EXISTS `cmstop_guestbook` (
  `gid` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `typeid` tinyint(2) unsigned NOT NULL,
  `title` varchar(100) NOT NULL,
  `content` mediumtext,
  `userid` mediumint(8) unsigned DEFAULT NULL,
  `username` varchar(15) DEFAULT NULL,
  `gender` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `email` varchar(40) DEFAULT NULL,
  `qq` varchar(15) DEFAULT NULL,
  `msn` varchar(40) DEFAULT NULL,
  `telephone` varchar(20) DEFAULT NULL,
  `mobile` varchar(20) DEFAULT NULL,
  `address` varchar(100) DEFAULT NULL,
  `homepage` varchar(25) DEFAULT NULL,
  `isview` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `ip` char(15) NOT NULL,
  `addtime` int(10) unsigned NOT NULL,
  `reply` mediumtext,
  `replyer` varchar(20) DEFAULT NULL,
  `replytime` int(10) unsigned DEFAULT NULL,
  PRIMARY KEY (`gid`),
  KEY `typeid` (`typeid`,`gid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `cmstop_guestbook_type`
--

CREATE TABLE IF NOT EXISTS `cmstop_guestbook_type` (
  `typeid` tinyint(2) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(20) NOT NULL,
  `count` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `sort` tinyint(2) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`typeid`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT AUTO_INCREMENT=6 ;

--
-- Dumping data for table `cmstop_guestbook_type`
--

INSERT INTO `cmstop_guestbook_type` (`typeid`, `name`, `count`, `sort`) VALUES
(1, '网页', 1, 0),
(2, '爆料', 2, 0),
(3, '投诉', 0, 0),
(4, '反馈', 0, 0),
(5, '建议', 6, 0);

-- --------------------------------------------------------

--
-- Table structure for table `cmstop_history`
--

CREATE TABLE IF NOT EXISTS `cmstop_history` (
  `hid` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `cronid` smallint(5) unsigned DEFAULT NULL,
  `alias` varchar(30) NOT NULL,
  `url` varchar(64) NOT NULL,
  `userid` mediumint(8) unsigned DEFAULT NULL,
  `addtime` int(10) unsigned DEFAULT NULL,
  PRIMARY KEY (`hid`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='历史页面,name,disabled,day,week等信息在计划任务表; ' AUTO_INCREMENT=3 ;

--
-- Dumping data for table `cmstop_history`
--

INSERT INTO `cmstop_history` (`hid`, `cronid`, `alias`, `url`, `userid`, `addtime`) VALUES
(1, 23, 'index', 'http://www.silkroad.news.cn/', 1, 1267583015),
(2, 25, 'index', 'http://www.silkroad.news.cn/', 1, 1288748972);

-- --------------------------------------------------------

--
-- Table structure for table `cmstop_interview`
--

CREATE TABLE IF NOT EXISTS `cmstop_interview` (
  `contentid` mediumint(8) unsigned NOT NULL,
  `number` smallint(5) unsigned DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  `address` varchar(100) DEFAULT NULL,
  `starttime` int(10) unsigned DEFAULT NULL,
  `endtime` int(10) unsigned DEFAULT NULL,
  `compere` varchar(20) DEFAULT NULL,
  `mode` enum('text','video','live') NOT NULL DEFAULT 'text',
  `photo` varchar(100) DEFAULT NULL,
  `video` varchar(255) DEFAULT NULL,
  `live` varchar(255) DEFAULT NULL,
  `template` varchar(100) DEFAULT NULL,
  `allowchat` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `visitorchat` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `ischeck` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `startchat` int(10) unsigned DEFAULT NULL,
  `endchat` int(10) unsigned DEFAULT NULL,
  `review` mediumtext,
  `editor` varchar(15) DEFAULT NULL,
  `notice` text,
  `picture` mediumint(8) unsigned DEFAULT NULL,
  `state` tinyint(1) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`contentid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `cmstop_interview_chat`
--

CREATE TABLE IF NOT EXISTS `cmstop_interview_chat` (
  `chatid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `contentid` mediumint(8) unsigned NOT NULL,
  `guestid` mediumint(8) unsigned DEFAULT NULL,
  `content` mediumtext,
  `created` int(10) unsigned NOT NULL,
  `createdby` mediumint(8) unsigned NOT NULL,
  `ip` varchar(15) NOT NULL,
  PRIMARY KEY (`chatid`),
  KEY `contentid` (`contentid`),
  KEY `guestid` (`guestid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `cmstop_interview_guest`
--

CREATE TABLE IF NOT EXISTS `cmstop_interview_guest` (
  `guestid` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `contentid` mediumint(8) unsigned NOT NULL,
  `name` varchar(30) NOT NULL,
  `color` varchar(7) DEFAULT NULL,
  `initial` varchar(1) NOT NULL,
  `photo` varchar(100) DEFAULT NULL,
  `aid` int(10) unsigned DEFAULT NULL,
  `resume` mediumtext,
  `url` varchar(200) NOT NULL,
  `sort` tinyint(1) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`guestid`),
  KEY `contentid` (`contentid`),
  KEY `initial` (`initial`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `cmstop_interview_question`
--

CREATE TABLE IF NOT EXISTS `cmstop_interview_question` (
  `questionid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `contentid` mediumint(8) unsigned NOT NULL,
  `nickname` varchar(20) NOT NULL,
  `content` mediumtext,
  `created` int(10) unsigned NOT NULL,
  `createdby` mediumint(8) unsigned DEFAULT NULL,
  `ip` varchar(15) NOT NULL,
  `iplocked` int(10) unsigned NOT NULL DEFAULT '0',
  `state` tinyint(1) unsigned NOT NULL DEFAULT '1',
  PRIMARY KEY (`questionid`),
  KEY `ip` (`ip`),
  KEY `contentid` (`contentid`,`state`,`questionid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `cmstop_ipbanned`
--

CREATE TABLE IF NOT EXISTS `cmstop_ipbanned` (
  `ip` char(15) NOT NULL,
  `expires` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`ip`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

-- --------------------------------------------------------

--
-- Table structure for table `cmstop_keyword`
--

CREATE TABLE IF NOT EXISTS `cmstop_keyword` (
  `id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `url` varchar(255) NOT NULL,
  `created` int(10) unsigned NOT NULL,
  `createdby` mediumint(8) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT AUTO_INCREMENT=2 ;

--
-- Dumping data for table `cmstop_keyword`
--

INSERT INTO `cmstop_keyword` (`id`, `name`, `url`, `created`, `createdby`) VALUES
(1, 'cmstop', 'http://www.cmstop.com', 1348917873, 1);

-- --------------------------------------------------------

--
-- Table structure for table `cmstop_kvdata`
--

CREATE TABLE IF NOT EXISTS `cmstop_kvdata` (
  `key` varchar(255) NOT NULL,
  `value` text,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `cmstop_link`
--

CREATE TABLE IF NOT EXISTS `cmstop_link` (
  `contentid` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `referenceid` mediumint(8) unsigned DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`contentid`),
  KEY `referenceid` (`referenceid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `cmstop_magazine`
--

CREATE TABLE IF NOT EXISTS `cmstop_magazine` (
  `mid` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(40) NOT NULL,
  `alias` varchar(40) NOT NULL,
  `logo` varchar(100) DEFAULT NULL,
  `pages` smallint(2) unsigned DEFAULT NULL,
  `template_list` varchar(100) NOT NULL,
  `template_content` varchar(100) NOT NULL,
  `type` varchar(10) DEFAULT NULL COMMENT 'eg:月刊|周刊',
  `publish` varchar(30) DEFAULT NULL COMMENT '发行时间文字描述',
  `created` int(10) unsigned NOT NULL,
  `createdby` mediumint(8) unsigned NOT NULL,
  `ip` varchar(15) NOT NULL,
  `url` varchar(255) DEFAULT '' COMMENT '官方网站',
  `memo` text,
  `default_year` smallint(4) unsigned DEFAULT NULL COMMENT '期使用的默认年份缓存',
  `disabled` tinyint(1) unsigned NOT NULL DEFAULT '1',
  PRIMARY KEY (`mid`),
  KEY `disabled` (`disabled`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `cmstop_magazine_content`
--

CREATE TABLE IF NOT EXISTS `cmstop_magazine_content` (
  `mapid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `pid` mediumint(8) unsigned NOT NULL,
  `eid` smallint(5) unsigned NOT NULL,
  `mid` smallint(5) unsigned NOT NULL,
  `pageno` tinyint(2) unsigned NOT NULL,
  `contentid` mediumint(8) unsigned NOT NULL,
  `sort` tinyint(2) unsigned NOT NULL DEFAULT '0',
  `pv` mediumint(8) unsigned DEFAULT '0',
  PRIMARY KEY (`mapid`),
  KEY `pep` (`pid`,`eid`,`pageno`),
  KEY `eid` (`eid`),
  KEY `mid` (`mid`),
  KEY `contentid` (`contentid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `cmstop_magazine_edition`
--

CREATE TABLE IF NOT EXISTS `cmstop_magazine_edition` (
  `eid` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `mid` smallint(5) unsigned NOT NULL,
  `title` varchar(60) DEFAULT NULL,
  `number` varchar(10) NOT NULL,
  `total_number` varchar(10) DEFAULT NULL,
  `year` smallint(4) unsigned DEFAULT NULL,
  `image` varchar(100) DEFAULT NULL,
  `pdf` varchar(100) DEFAULT NULL,
  `publish` int(10) unsigned NOT NULL,
  `created` int(10) unsigned NOT NULL,
  `createdby` mediumint(8) unsigned NOT NULL,
  `ip` varchar(15) NOT NULL,
  `url` varchar(255) DEFAULT '',
  `disabled` tinyint(1) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`eid`),
  KEY `mid` (`mid`,`disabled`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `cmstop_magazine_page`
--

CREATE TABLE IF NOT EXISTS `cmstop_magazine_page` (
  `pid` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `mid` smallint(5) unsigned NOT NULL,
  `eid` smallint(5) unsigned NOT NULL,
  `pageno` tinyint(2) unsigned NOT NULL,
  `name` varchar(30) NOT NULL,
  `editor` varchar(30) NOT NULL,
  `arteditor` varchar(30) NOT NULL,
  PRIMARY KEY (`pid`),
  KEY `eid` (`eid`,`pageno`),
  KEY `mid` (`mid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `cmstop_member`
--

CREATE TABLE IF NOT EXISTS `cmstop_member` (
  `userid` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `username` char(15) NOT NULL,
  `password` char(32) NOT NULL,
  `email` varchar(100) NOT NULL,
  `groupid` tinyint(3) unsigned NOT NULL DEFAULT '5',
  `avatar` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `regip` char(15) NOT NULL,
  `regtime` int(10) unsigned NOT NULL DEFAULT '0',
  `lastloginip` char(15) NOT NULL,
  `lastlogintime` int(10) unsigned NOT NULL DEFAULT '0',
  `logintimes` smallint(5) unsigned NOT NULL DEFAULT '0',
  `posts` smallint(5) unsigned NOT NULL DEFAULT '0',
  `comments` smallint(5) unsigned NOT NULL DEFAULT '0',
  `pv` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `credits` smallint(5) unsigned NOT NULL DEFAULT '0',
  `salt` char(6) DEFAULT NULL,
  `status` tinyint(1) unsigned NOT NULL DEFAULT '1',
  PRIMARY KEY (`userid`),
  UNIQUE KEY `username` (`username`),
  UNIQUE KEY `email` (`email`),
  KEY `groupid` (`groupid`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `cmstop_member`
--

INSERT INTO `cmstop_member` (`userid`, `username`, `password`, `email`, `groupid`, `avatar`, `regip`, `regtime`, `lastloginip`, `lastlogintime`, `logintimes`, `posts`, `comments`, `pv`, `credits`, `salt`, `status`) VALUES
(1, 'cmstop', '4d5b365421f983beb5c38e885ffbc074', 'webmaster@cmstop.com', 1, 1, '127.0.0.1', 1245739122, '192.168.1.181', 1348898287, 58, 0, 2, 0, 0, '6e7734', 1);

-- --------------------------------------------------------

--
-- Table structure for table `cmstop_member_api`
--

CREATE TABLE IF NOT EXISTS `cmstop_member_api` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `apiid` smallint(3) unsigned NOT NULL,
  `userid` mediumint(8) unsigned NOT NULL,
  `authkey` varchar(200) NOT NULL,
  `access_token` varchar(32) NOT NULL DEFAULT '',
  `expires_in` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `userid` (`apiid`,`userid`),
  KEY `apiid` (`apiid`,`authkey`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `cmstop_member_bind`
--

CREATE TABLE IF NOT EXISTS `cmstop_member_bind` (
  `userid` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `bindid` char(25) DEFAULT NULL,
  PRIMARY KEY (`userid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `cmstop_member_detail`
--

CREATE TABLE IF NOT EXISTS `cmstop_member_detail` (
  `userid` mediumint(8) unsigned NOT NULL,
  `name` varchar(30) NOT NULL,
  `sex` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `birthday` date DEFAULT NULL,
  `telephone` varchar(15) DEFAULT NULL,
  `mobile` varchar(11) DEFAULT NULL,
  `job` varchar(32) DEFAULT NULL,
  `address` varchar(100) DEFAULT NULL,
  `zipcode` varchar(6) DEFAULT NULL,
  `qq` varchar(15) DEFAULT NULL,
  `msn` varchar(40) DEFAULT NULL,
  `authstr` varchar(32) DEFAULT NULL,
  `remarks` varchar(255) DEFAULT NULL,
  `mobileauth` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`userid`),
  KEY `birthday` (`birthday`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `cmstop_member_detail`
--

INSERT INTO `cmstop_member_detail` (`userid`, `name`, `sex`, `birthday`, `telephone`, `mobile`, `job`, `address`, `zipcode`, `qq`, `msn`, `authstr`, `remarks`) VALUES
(1, 'cmstop.com', 1, '0000-00-00', '010-82145002', '', '', '北京市海淀区上地信息路科实大厦A座5层A1室', '100085', '98704222', 'webmaster@cmstop.com', '', '');

-- --------------------------------------------------------

--
-- Table structure for table `cmstop_member_group`
--

CREATE TABLE IF NOT EXISTS `cmstop_member_group` (
  `groupid` tinyint(3) unsigned NOT NULL AUTO_INCREMENT,
  `name` char(20) NOT NULL,
  `status` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `allowlogin` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `column` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `allowcontribute` tinyint(3) unsigned NOT NULL DEFAULT '1',
  `allowcomment` tinyint(3) unsigned NOT NULL DEFAULT '1',
  `issystem` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `sort` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `remarks` varchar(255) NOT NULL,
  PRIMARY KEY (`groupid`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=7 ;

--
-- Dumping data for table `cmstop_member_group`
--

INSERT INTO `cmstop_member_group` (`groupid`, `name`, `status`, `allowlogin`, `column`, `allowcontribute`, `allowcomment`, `issystem`, `sort`, `remarks`) VALUES
(1, '管理员', 1, 1, 0, 1, 1, 1, 0, ''),
(2, '游客', 1, 1, 0, 0, 0, 1, 0, ''),
(3, '待验证', 1, 1, 0, 0, 0, 1, 0, ''),
(4, '待审核', 1, 1, 0, 0, 0, 1, 0, ''),
(5, '禁用', 1, 0, 0, 0, 0, 1, 0, ''),
(6, '注册用户', 1, 1, 0, 1, 1, 1, 0, '');

-- --------------------------------------------------------

--
-- Table structure for table `cmstop_member_login_log`
--

CREATE TABLE IF NOT EXISTS `cmstop_member_login_log` (
  `logid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `username` char(15) NOT NULL,
  `ip` char(15) NOT NULL,
  `time` int(10) unsigned NOT NULL,
  `succeed` tinyint(1) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`logid`),
  KEY `username` (`username`,`succeed`,`time`),
  KEY `ip` (`ip`,`succeed`,`time`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `cmstop_menu`
--

CREATE TABLE IF NOT EXISTS `cmstop_menu` (
  `menuid` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `parentid` smallint(5) unsigned DEFAULT NULL,
  `parentids` varchar(255) DEFAULT NULL,
  `childids` text,
  `name` varchar(20) NOT NULL,
  `url` varchar(255) NOT NULL,
  `target` enum('_self','_blank','right') DEFAULT NULL,
  `sort` tinyint(3) unsigned DEFAULT '0',
  PRIMARY KEY (`menuid`),
  KEY `parentid` (`parentid`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=206 ;

--
-- Dumping data for table `cmstop_menu`
--

INSERT INTO `cmstop_menu` (`menuid`, `parentid`, `parentids`, `childids`, `name`, `url`, `target`, `sort`) VALUES
(1, null, null, '21,22,23,24,25,26,27,28', '我的', '?app=system&controller=index&action=right', null, '1'),
(2, null, null, null, '内容', '?app=system&controller=content&action=index', '_self', '2'),
(3, null, null, null, '页面', '?app=page&controller=page&action=index', null, '3'),
(4, null, null, '54,55,56,57,58,59', '会员', '?app=member&controller=index&action=index', '_self', '4'),
(5, null, null, '203,208,131,146,117,83,95,153,99,84,88,91,102,145,80,81,143,130,121,151,78,77,138,82,209,132,133,134,135,147,148,149,150,96,97,98,155,154,100,101,124,85,86,87,89,90,92,93,94,104,103,144,122,123,152,139', '扩展', '?app=system&controller=app&action=index', '_self', '5'),
(6, null, null, '45,46,47,49,53,196', '工具', '', '_self', '6'),
(7, null, null, '42,43', '模板', '?app=system&controller=template', null, '9'),
(8, null, null, '29,30,31,32,33,34,35,36,39,41,61,62,64,65,66,67,68,69,70,71,72,113,115,141,142', '设置', '', '_self', '11'),
(21, 1, '1', NULL, '个人资料', '?app=system&controller=my&action=profile', NULL, 7),
(22, 1, '1', NULL, '我的便笺', '?app=system&controller=my&action=note', NULL, 1),
(23, 1, '1', NULL, '工作报表', '?app=system&controller=my&action=stat', NULL, 5),
(24, 1, '1', NULL, '我的权限', '?app=system&controller=my&action=priv', NULL, 4),
(25, 1, '1', NULL, '我的内容', '?app=system&controller=my&action=content', NULL, 2),
(26, 1, '1', NULL, '常用操作', '?app=system&controller=my&action=menu', NULL, 6),
(27, 1, '1', NULL, '修改密码', '?app=system&controller=my&action=password', NULL, 8),
(28, 1, '1', NULL, '我的部门', '?app=system&controller=my&action=department', NULL, 3),
(29, 8, '8', NULL, '栏目', '?app=system&controller=category&action=index', NULL, 3),
(30, 8, '8', NULL, 'Tags', '?app=system&controller=tag&action=index', NULL, 15),
(31, 8, '8', NULL, '来源', '?app=system&controller=source&action=index', NULL, 14),
(32, 8, '8', NULL, '工作流', '?app=system&controller=workflow&action=index', NULL, 5),
(33, 8, '8', NULL, '菜单', '?app=system&controller=menu&action=index', NULL, 8),
(34, 8, '8', NULL, '敏感词', '?app=system&controller=filterword&action=index', NULL, 12),
(35, 8, '8', NULL, '关键词链接', '?app=system&controller=keylink&action=index', NULL, 13),
(36, 8, '8', NULL, '发布点', '?app=system&controller=psn&action=index', NULL, 6),
(39, 8, '8', NULL, '外部数据源', '?app=system&controller=dsn&action=index', NULL, 10),
(42, 7, '7', NULL, '新建模板', '?app=system&controller=template&action=add&path=root', NULL, 1),
(43, 7, '7', NULL, '管理模板', '?app=system&controller=template', NULL, 2),
(45, 6, '6', NULL, '更新缓存', '?app=system&controller=cache&action=update', NULL, 4),
(46, 6, '6', NULL, 'IP 禁止', '?app=system&controller=ipbanned&action=index', NULL, 5),
(47, 6, '6', NULL, '计划任务', '?app=system&controller=cron&action=index', NULL, 1),
(49, 6, '6', NULL, '附件管理', '?app=system&controller=attachment&action=index', NULL, 2),
(54, 4, '4', NULL, '管理用户', '?app=member&controller=index&action=index', NULL, 0),
(55, 4, '4', NULL, '用户组设置', '?app=member&controller=group&action=index', NULL, 2),
(56, 4, '4', NULL, '登录日志', '?app=member&controller=log&action=index', NULL, 3),
(57, 4, '4', NULL, '注册设置', '?app=member&controller=setting&action=index', NULL, 4),
(58, 4, '4', NULL, 'Ucenter设置', '?app=member&controller=setting&action=ucenter', NULL, 5),
(59, 4, '4', NULL, '审核用户', '?app=member&controller=audit&action=index', NULL, 1),
(61, 8, '8', '62,64,65,66,67,113', '全局', '?app=system&controller=setting&action=basic', NULL, 1),
(62, 61, '8,61', NULL, '站点信息', '?app=system&controller=setting&action=basic', NULL, 0),
(64, 61, '8,61', NULL, '性能优化', '?app=system&controller=setting&action=optimize', NULL, 0),
(65, 61, '8,61', NULL, '附件设置', '?app=system&controller=setting&action=attachment', NULL, 0),
(66, 61, '8,61', NULL, '邮件设置', '?app=system&controller=setting&action=mail', NULL, 0),
(67, 61, '8,61', NULL, 'SEO设置', '?app=system&controller=setting&action=seo', NULL, 0),
(68, 8, '8', '69,70,71,72', '权限', '?app=system&controller=administrator&action=index', NULL, 2),
(69, 68, '8,68', NULL, '管理员', '?app=system&controller=administrator&action=index', NULL, 0),
(70, 68, '8,68', NULL, '角色', '?app=system&controller=role&action=index', NULL, 0),
(71, 68, '8,68', NULL, '部门', '?app=system&controller=department&action=index', NULL, 0),
(72, 68, '8,68', NULL, '权限', '?app=system&controller=aca&action=index', NULL, 0),
(77, 5, '5', NULL, '搜索', '?app=search&controller=setting&action=index', NULL, 21),
(78, 5, '5', NULL, 'RSS', '?app=rss&controller=setting&action=index', NULL, 20),
(80, 5, '5', NULL, '杂志', '?app=magazine&controller=magazine&action=index', NULL, 12),
(81, 5, '5', NULL, '报纸', '?app=paper&controller=paper&action=index', NULL, 13),
(82, 5, '5', NULL, '历史页面', '?app=history&controller=history&action=index', NULL, 23),
(83, 5, '5', NULL, '专栏', '?app=space&controller=index&action=index', NULL, 4),
(84, 5, '5', '85,86,87,124', '评论', '?app=comment&controller=comment&action=index', NULL, 8),
(85, 84, '5,84', NULL, '举报评论', '?app=comment&controller=comment&action=report', NULL, 2),
(86, 84, '5,84', NULL, '敏感评论', '?app=comment&controller=comment&action=sensitive', NULL, 3),
(87, 84, '5,84', NULL, '设置', '?app=comment&controller=setting&action=index', NULL, 4),
(88, 5, '5', '89,90', 'Digg', '?app=digg&controller=digg&action=index', NULL, 9),
(89, 88, '5,88', NULL, '排行榜', '?app=digg&controller=digg&action=index', NULL, 0),
(90, 88, '5,88', NULL, '设置', '?app=digg&controller=setting&action=index', NULL, 0),
(91, 5, '5', '92,93,94', '心情', '?app=mood&controller=data&action=index&range=1', NULL, 10),
(92, 91, '5,91', NULL, '排行榜', '?app=mood&controller=data&action=index&range=1', NULL, 0),
(93, 91, '5,91', NULL, '方案', '?app=mood&controller=mood', NULL, 0),
(94, 91, '5,91', NULL, '设置', '?app=mood&controller=setting&action=index', NULL, 3),
(95, 5, '5', '96,97,98', '文章采集', '?app=spider&controller=spider', NULL, 5),
(96, 95, '5,95', NULL, '添加规则', '?app=spider&controller=manager&action=addrule', NULL, 0),
(97, 95, '5,95', NULL, '管理规则', '?app=spider&controller=manager&action=index', NULL, 1),
(98, 95, '5,95', NULL, '站点管理', '?app=spider&controller=manager&action=sites', NULL, 2),
(99, 5, '5', '100,101', '文章推送', '?app=push&controller=push', NULL, 7),
(100, 99, '5,99', NULL, '添加规则', '?app=push&controller=push&action=add', NULL, 0),
(101, 99, '5,99', NULL, '管理规则', '?app=push&controller=push&action=manager', NULL, 0),
(102, 5, '5', '103,104', '留言本', '?app=guestbook&controller=guestbook&action=index', NULL, 11),
(103, 102, '5,102', NULL, '设置', '?app=guestbook&controller=setting&action=index', NULL, 1),
(104, 102, '5,102', NULL, '类别', '?app=guestbook&controller=type&action=index', NULL, 0),
(105, null, null, '114,116,118', '统计', '?app=system&controller=stat&action=index', '_self', '8'),
(113, 61, '8,61', NULL, 'API设置', '?app=system&controller=setting&action=api', NULL, 1),
(114, 105, '105', NULL, '编辑考核', '?app=system&controller=stat_examine&action=index', NULL, 1),
(115, 8, '8', NULL, '自定义属性', '?app=system&controller=property&action=index', NULL, 4),
(116, 105, '105', NULL, '统计', '?app=system&controller=stat&action=index', NULL, 0),
(117, 5, '5', NULL, '投稿', '?app=contribution', NULL, 3),
(118, 105, '105', NULL, '排行榜', '?app=system&controller=rank&action=index', NULL, 2),
(121, 5, '5', '122,123', '自由列表', '?app=freelist&controller=freelist&action=index', NULL, 15),
(122, 121, '5,121', NULL, '列表管理', '?app=freelist&controller=freelist&action=index', NULL, 0),
(123, 121, '5,121', NULL, '分组管理', '?app=freelist&controller=group&action=index', NULL, 0),
(124, 84, '5,84', NULL, '话题管理', '?app=comment&controller=comment&action=topic', NULL, 1),
(130, 5, '5', NULL, '扩展字段', '?app=field&controller=project&action=index', NULL, 14),
(131, 5, '5', '132,133,134,135', '视频', '?app=video&controller=vms&action=index', NULL, 1),
(132, 131, '5,131', NULL, '视频管理', '?app=video&controller=vms&action=index', NULL, 1),
(133, 131, '5,131', NULL, '视频专辑', '?app=video&controller=videolist&action=index', NULL, 2),
(134, 131, '5,131', NULL, '广告管理', '?app=video&controller=video&action=setting_ads', NULL, 3),
(135, 131, '5,131', NULL, '接口配置', '?app=video&controller=vms&action=setting', NULL, 4),
(136, 61, '61', NULL, '发稿设置', '?app=system&controller=setting&action=content', NULL, 7),
(138, 5, '5', '139', 'CDN', '?app=cdn&controller=cdn&action=index', NULL, 22),
(139, 138, '5,138', NULL, '接口配置', '?app=cdn&controller=setting&action=index', NULL, 0),
(141, 8, '8', NULL, '图片编辑器', '?app=system&controller=imgeditor&action=setting', NULL, 9),
(142, 8, '8', NULL, '外部数据端口', '?app=system&controller=port&action=index', NULL, 11),
(143, 5, '5', '144', '开放接口', '?app=system&controller=openauth&action=index', NULL, 13),
(144, 143, '5,143', NULL, '接口权限', '?app=system&controller=openaca&action=index', NULL, 0),
(145, 5, '5', NULL, '云平台', '?app=cloud&controller=cloud&action=index', NULL, 11),
(146, 5, '5', '147,148,149,150', '专题', '?app=special&controller=setting&action=scheme', NULL, 2),
(147, 146, '5,146', NULL, '方案分类', '?app=special&controller=setting&action=schemeTypes', NULL, 1),
(148, 146, '5,146', NULL, '方案管理', '?app=special&controller=setting&action=scheme', NULL, 2),
(149, 146, '5,146', NULL, '模板管理', '?app=special&controller=setting&action=template', NULL, 3),
(150, 146, '5,146', NULL, '设置', '?app=special&controller=setting&action=index', NULL, 4),
(151, 5, '5', '152', '微博转发', '?app=weibo&controller=weibo&action=index', NULL, 16),
(152, 151, '5,151', NULL, '微博账号设置', '?app=weibo&controller=weibo&action=account', NULL, 0),
(153, 5, '5', '154', '数字报抓取', '?app=epaper&controller=epaper&action=import', NULL, 6),
(154, 153, '5,153', NULL, '规则管理', '?app=epaper&controller=epaper&action=index', NULL, 1),
(155, 95, '5,95', NULL, '定时采集日志', '?app=spider&controller=cron&action=index', NULL, 3),
(168, null, null, '169,170,210,171,193,172,173,174,175,176,186,177,187,207,178,179,181,182,183,194,204,195,205,206', '移动', '?app=mobile&controller=content', null, '7'),
(169, 168, '168', NULL, '内容管理', '?app=mobile&controller=content@mobile/content/menu', NULL, 1),
(170, 168, '168', NULL, '消息推送', '?app=mobile&controller=push', NULL, 2),
(171, 168, '168', '174,175', '统计', '?app=mobile&controller=stat&action=content', NULL, 3),
(172, 168, '168', NULL, '意见反馈', '?app=mobile&controller=feedback', NULL, 5),
(173, 168, '168', '176,186,177,178,187,179,181,182,183', '设置', '?app=mobile&controller=setting', NULL, 6),
(174, 171, '168,171', NULL, '客户端统计', '?app=mobile&controller=stat&action=client', NULL, 1),
(175, 171, '168,171', NULL, '内容统计', '?app=mobile&controller=stat&action=content', NULL, 2),
(176, 173, '168,173', NULL, '系统设置', '?app=mobile&controller=setting&action=index', NULL, 1),
(177, 173, '168,173', NULL, '频道管理', '?app=mobile&controller=setting&action=category', NULL, 3),
(178, 173, '168,173', NULL, '应用管理', '?app=mobile&controller=setting&action=app', NULL, 5),
(179, 173, '168,173', NULL, '版本升级', '?app=mobile&controller=setting&action=version', NULL, 6),
(181, 173, '168,173', NULL, '应用推荐', '?app=mobile&controller=setting&action=moreapp', NULL, 7),
(182, 173, '168,173', NULL, '微信直播', '?app=mobile&controller=eventlive&action=wechat', NULL, 7),
(183, 173, '168,173', NULL, 'API 设置', '?app=mobile&controller=setting&action=api', NULL, 8),
(184, 163, '6,163', NULL, '生成', '?app=system&controller=qrcode&action=index', NULL, 1),
(185, 163, '6,163', NULL, '统计', '?app=system&controller=qrcode&action=stat', NULL, 2),
(186, 173, '168,173', NULL, '显示设置', '?app=mobile&controller=setting&action=display', NULL, 2),
(187, 173, '168,173', NULL, '自动抓取', '?app=mobile&controller=autofill', NULL, 4),
(188, 173, '168,173', NULL, '分类管理', '?app=mobile&controller=setting&action=classify', NULL, 4),
(189, 173, '168,173', NULL, '静态页面', '?app=mobile&controller=setting&action=static_page', NULL, 9),
(190, 6, '6', '191,192', '二维码', '?app=system&controller=qrcode&action=index', NULL, 3),
(191, 190, '6,190', NULL, '生成', '?app=system&controller=qrcode&action=generate', NULL, 1),
(192, 190, '6,190', NULL, '统计', '?app=system&controller=qrcode&action=stat', NULL, 2),
(193, 168, '168', NULL, '广告管理', '?app=mobile&controller=ad&action=index', NULL, 4),
(194, 173, '168,173', NULL, '风格设置', '?app=mobile&controller=setting&action=style', NULL, 10),
(195, 5, '5', NULL, '微信', '?app=wechat', NULL, 0),
(196, 173, '168,173', '197, 198', '直播', '?app=mobile&controller=live&action=index', NULL, 9),
(197, 196, '168,173,196', NULL, '频道列表', '?app=mobile&controller=live&action=channel_index', NULL, 0),
(198, 196, '168,173,196', NULL, '节目管理', '?app=mobile&controller=live&action=program_index', NULL, 0),
(199, 6, '6', '200,201,202,203,204,205', '系统安全', '?app=safe&controller=setting&action=index', NULL, 6),
(200, 199, '6,199', NULL, '安全设置', '?app=safe&controller=setting&action=index', NULL, 0),
(201, 199, '6,199', NULL, '域名安全', '?app=safe&controller=domain&action=index', NULL, 1),
(202, 199, '6,199', NULL, '木马扫描', '?app=safe&controller=trojan&action=index', NULL, 2),
(203, 199, '6,199', NULL, '登录日志', '?app=safe&controller=log&action=index', NULL, 3),
(204, 199, '6,199', NULL, '操作日志', '?app=system&controller=adminlog&action=index', NULL, 4),
(205, 199, '6,199', NULL, '文件校验', '?app=safe&controller=verify&action=index', NULL, 5),
(211, null, null, null, '广告', 'http://adm.cmstop.cn/', null, '10');
-- --------------------------------------------------------

--
-- Table structure for table `cmstop_mobile_activity`
--

CREATE TABLE IF NOT EXISTS `cmstop_mobile_activity` (
  `contentid` mediumint(8) unsigned NOT NULL,
  `image` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`contentid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `cmstop_mobile_ad`
--

CREATE TABLE IF NOT EXISTS `cmstop_mobile_ad` (
  `adid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `identifier` varchar(40) NOT NULL,
  `data` varchar(200) NOT NULL DEFAULT '',
  PRIMARY KEY (`adid`),
  KEY `identifier` (`identifier`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `cmstop_mobile_addon`
--

CREATE TABLE IF NOT EXISTS `cmstop_mobile_addon` (
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `cmstop_mobile_app`
--

CREATE TABLE IF NOT EXISTS `cmstop_mobile_app` (
  `appid` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `iconurl` varchar(255) NOT NULL,
  `url` varchar(255) NOT NULL,
  `disabled` tinyint(1) unsigned NOT NULL,
  `builtin` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `system` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `menu` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `sort` mediumint(8) unsigned NOT NULL,
  `type` enum('mobile','pad') NOT NULL DEFAULT 'mobile',
  `version` varchar(30) NOT NULL DEFAULT '1.0',
  PRIMARY KEY (`appid`),
  UNIQUE KEY `name` (`name`,`type`,`version`),
  UNIQUE KEY `url` (`url`,`type`,`version`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=31 ;

--
-- Dumping data for table `cmstop_mobile_app`
--

INSERT INTO `cmstop_mobile_app` (`appid`, `name`, `iconurl`, `url`, `disabled`, `builtin`, `system`, `menu`, `sort`, `type`, `version`) VALUES
(11, '新闻', 'http://m.silkroad.news.cn/templates/default/app/images/icon/pad/1.0/app_xw_btn.png', 'app:news', 0, 1, 1, 1, 1, 'pad', '1.0'),
(12, '图片', 'http://m.silkroad.news.cn/templates/default/app/images/icon/pad/1.0/app_tps_btn.png', 'app:picture', 0, 1, 0, 1, 2, 'pad', '1.0'),
(13, '视频', 'http://m.silkroad.news.cn/templates/default/app/images/icon/pad/1.0/app_sp_btn.png', 'app:video', 0, 1, 0, 1, 3, 'pad', '1.0'),
(14, '专题', 'http://m.silkroad.news.cn/templates/default/app/images/icon/pad/1.0/app_zt_btn.png', 'app:special', 0, 1, 0, 0, 4, 'pad', '1.0'),
(16, '报料', 'http://m.silkroad.news.cn/templates/default/app/images/icon/pad/1.0/app_bl_btn.png', 'app:baoliao', 1, 1, 0, 0, 6, 'pad', '1.0'),
(17, '二维码', 'http://m.silkroad.news.cn/templates/default/app/images/icon/pad/1.0/app_ewm_btn.png', 'app:qrcode', 0, 1, 0, 0, 7, 'pad', '1.0'),
(18, '投票', 'http://m.silkroad.news.cn/templates/default/app/images/icon/pad/1.0/app_tp_btn.png', 'app:vote', 0, 1, 0, 0, 8, 'pad', '1.0'),
(19, '活动', 'http://m.silkroad.news.cn/templates/default/app/images/icon/pad/1.0/app_hd_btn.png', 'app:activity', 0, 1, 0, 0, 9, 'pad', '1.0'),
(20, '调查', 'http://m.silkroad.news.cn/templates/default/app/images/icon/pad/1.0/app_dc_btn.png', 'app:survey', 0, 1, 0, 0, 10, 'pad', '1.0'),
(21, '新闻', 'http://m.silkroad.news.cn/templates/default/app/images/icon/mobile/2.0/app_xw_btn.png', 'app:news', 0, 1, 1, 1, 1, 'mobile', '2.0'),
(22, '图片', 'http://m.silkroad.news.cn/templates/default/app/images/icon/mobile/2.0/app_tps_btn.png', 'app:picture', 0, 1, 0, 1, 2, 'mobile', '2.0'),
(23, '视频', 'http://m.silkroad.news.cn/templates/default/app/images/icon/mobile/2.0/app_sp_btn.png', 'app:video', 0, 1, 0, 1, 3, 'mobile', '2.0'),
(24, '专题', 'http://m.silkroad.news.cn/templates/default/app/images/icon/mobile/2.0/app_zt_btn.png', 'app:special', 0, 1, 0, 0, 4, 'mobile', '2.0'),
(26, '报料', 'http://m.silkroad.news.cn/templates/default/app/images/icon/mobile/2.0/app_bl_btn.png', 'app:baoliao', 1, 1, 0, 0, 6, 'mobile', '2.0'),
(27, '二维码', 'http://m.silkroad.news.cn/templates/default/app/images/icon/mobile/2.0/app_ewm_btn.png', 'app:qrcode', 0, 1, 0, 0, 7, 'mobile', '2.0'),
(28, '投票', 'http://m.silkroad.news.cn/templates/default/app/images/icon/mobile/2.0/app_tp_btn.png', 'app:vote', 0, 1, 0, 0, 8, 'mobile', '2.0'),
(29, '活动', 'http://m.silkroad.news.cn/templates/default/app/images/icon/mobile/2.0/app_hd_btn.png', 'app:activity', 0, 1, 0, 0, 9, 'mobile', '2.0'),
(30, '调查', 'http://m.silkroad.news.cn/templates/default/app/images/icon/mobile/2.0/app_dc_btn.png', 'app:survey', 0, 1, 0, 0, 10, 'mobile', '2.0'),
(31, '直播', 'http://m.silkroad.news.cn/templates/default/app/images/icon/mobile/2.0/app_zb.png', 'app:live', 0, 1, 0, 0, 10, 'mobile', '2.0');

-- --------------------------------------------------------

--
-- Table structure for table `cmstop_mobile_article`
--

CREATE TABLE IF NOT EXISTS `cmstop_mobile_article` (
  `contentid` mediumint(8) unsigned NOT NULL,
  `tags` varchar(255) DEFAULT NULL,
  `content` text NOT NULL,
  PRIMARY KEY (`contentid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `cmstop_mobile_autofill`
--

CREATE TABLE IF NOT EXISTS `cmstop_mobile_autofill` (
  `id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `catid` smallint(5) unsigned DEFAULT NULL,
  `port` varchar(255) NOT NULL,
  `options` text NOT NULL,
  `interval` int(10) unsigned NOT NULL,
  `nextrun` int(10) unsigned NOT NULL DEFAULT '0',
  `state` tinyint(3) unsigned NOT NULL,
  `disabled` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `lastpublished` int(10) unsigned NOT NULL DEFAULT '0',
  `modelid` varchar(20) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `catid` (`catid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `cmstop_mobile_autofill_error_log`
--

CREATE TABLE IF NOT EXISTS `cmstop_mobile_autofill_error_log` (
  `contentid` mediumint(8) NOT NULL DEFAULT '0',
  `uuid` varchar(32) DEFAULT NULL,
  `message` text,
  `time` int(10) NOT NULL,
  KEY `contentid` (`contentid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `cmstop_mobile_autofill_log`
--

CREATE TABLE IF NOT EXISTS `cmstop_mobile_autofill_log` (
  `catid` smallint(5) unsigned NOT NULL,
  `uuid` varchar(32) NOT NULL,
  `contentid` mediumint(8) unsigned DEFAULT NULL,
  PRIMARY KEY (`catid`,`uuid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `cmstop_mobile_category`
--

CREATE TABLE IF NOT EXISTS `cmstop_mobile_category` (
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
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=11 ;

--
-- Dumping data for table `cmstop_mobile_category`
--

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

-- --------------------------------------------------------

--
-- Table structure for table `cmstop_mobile_category_bind`
--

CREATE TABLE IF NOT EXISTS `cmstop_mobile_category_bind` (
  `mobile_catid` smallint(5) unsigned NOT NULL,
  `catid` smallint(5) unsigned NOT NULL,
  KEY `mobile_catid` (`mobile_catid`),
  KEY `catid` (`catid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `cmstop_mobile_category_priv`
--

CREATE TABLE IF NOT EXISTS `cmstop_mobile_category_priv` (
  `catid` smallint(5) unsigned NOT NULL DEFAULT '0',
  `userid` mediumint(8) unsigned NOT NULL DEFAULT '0',
  KEY `catid` (`catid`),
  KEY `userid` (`userid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `cmstop_mobile_classify`
--

CREATE TABLE IF NOT EXISTS `cmstop_mobile_classify` (
  `classifyid` smallint(5) unsigned NOT NULL auto_increment,
  `modelid` tinyint(2) unsigned NOT NULL,
  `classname` varchar(30) NOT NULL,
  `disabled` tinyint(1) NOT NULL default '0',
  `sort` int(10) unsigned NOT NULL,
  `created` int(10) unsigned NOT NULL,
  PRIMARY KEY  (`classifyid`),
  KEY `modelid` (`modelid`),
  KEY `sort` (`sort`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `cmstop_mobile_content`
--

CREATE TABLE IF NOT EXISTS `cmstop_mobile_content` (
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
  `qrcode` varchar(255) default NULL,
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
  `classifyid` smallint(5) default NULL,
  PRIMARY KEY (`contentid`),
  KEY `referenceid` (`referenceid`),
  KEY `topicid` (`topicid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `cmstop_mobile_content_addon`
--

CREATE TABLE IF NOT EXISTS `cmstop_mobile_content_addon` (
  `contentid` mediumint(8) unsigned NOT NULL,
  `engine` varchar(20) NOT NULL,
  `addonid` int(10) unsigned NOT NULL,
  `place` varchar(50) NOT NULL,
  PRIMARY KEY (`contentid`,`addonid`),
  KEY `addonid` (`addonid`),
  KEY `contentid` (`contentid`,`place`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `cmstop_mobile_content_category`
--

CREATE TABLE IF NOT EXISTS `cmstop_mobile_content_category` (
  `contentid` mediumint(8) unsigned NOT NULL,
  `catid` smallint(5) unsigned NOT NULL,
  PRIMARY KEY (`contentid`,`catid`),
  KEY `catid` (`catid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `cmstop_mobile_content_log`
--

CREATE TABLE IF NOT EXISTS `cmstop_mobile_content_log` (
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `cmstop_mobile_content_related`
--

CREATE TABLE IF NOT EXISTS `cmstop_mobile_content_related` (
  `relatedid` int(11) NOT NULL AUTO_INCREMENT,
  `contentid` mediumint(8) unsigned NOT NULL,
  `related_contentid` mediumint(8) unsigned NOT NULL,
  PRIMARY KEY (`relatedid`),
  KEY `contentid` (`contentid`),
  KEY `related_contentid` (`related_contentid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `cmstop_mobile_content_stat`
--

CREATE TABLE IF NOT EXISTS `cmstop_mobile_content_stat` (
  `contentid` mediumint(8) unsigned NOT NULL,
  `pv` int(10) unsigned NOT NULL,
  PRIMARY KEY (`contentid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `cmstop_mobile_content_stat_day`
--

CREATE TABLE IF NOT EXISTS `cmstop_mobile_content_stat_day` (
  `contentid` mediumint(8) unsigned NOT NULL,
  `day` date NOT NULL,
  `pv` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`contentid`,`day`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `cmstop_mobile_feedback`
--

CREATE TABLE IF NOT EXISTS `cmstop_mobile_feedback` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `email` varchar(100) NOT NULL DEFAULT '' COMMENT '联系邮箱',
  `content` varchar(512) NOT NULL DEFAULT '' COMMENT '意见内容',
  `app_version` varchar(100) NOT NULL DEFAULT '' COMMENT '客户端版本',
  `system_version` varchar(100) NOT NULL DEFAULT '' COMMENT '手机系统版本',
  `created` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `cmstop_mobile_link`
--

CREATE TABLE IF NOT EXISTS `cmstop_mobile_link` (
  `contentid` mediumint(8) unsigned NOT NULL,
  `linkto` varchar(255) NOT NULL,
  PRIMARY KEY (`contentid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `cmstop_mobile_live_channel`
--

CREATE TABLE IF NOT EXISTS `cmstop_mobile_live_channel` (
  `id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(40) NOT NULL,
  `created` int(10) unsigned NOT NULL,
  `thumb` varchar(255) NOT NULL DEFAULT '',
  `description` text NOT NULL,
  `sorttime` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `name` (`name`,`sorttime`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `cmstop_mobile_live_program`
--

CREATE TABLE IF NOT EXISTS `cmstop_mobile_live_program` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `channel_id` smallint(5) unsigned NOT NULL,
  `title` varchar(80) NOT NULL,
  `tags` varchar(255) NOT NULL DEFAULT '',
  `url` varchar(255) NOT NULL,
  `source` varchar(255) NOT NULL DEFAULT '',
  `editor` varchar(40) NOT NULL DEFAULT '',
  `description` text,
  `thumb` varchar(255) NOT NULL,
  `online` int(10) NOT NULL,
  `offline` int(10) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `channel_id` (`channel_id`,`title`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `cmstop_mobile_moreapp`
--

CREATE TABLE IF NOT EXISTS `cmstop_mobile_moreapp` (
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `cmstop_mobile_push_log`
--

CREATE TABLE IF NOT EXISTS `cmstop_mobile_push_log` (
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `cmstop_mobile_slider`
--

CREATE TABLE IF NOT EXISTS `cmstop_mobile_slider` (
  `contentid` mediumint(8) unsigned NOT NULL,
  `catid` smallint(5) unsigned NOT NULL,
  `title` varchar(200) NOT NULL,
  `thumb` varchar(255) NOT NULL,
  PRIMARY KEY (`contentid`,`catid`),
  KEY `catid` (`catid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `cmstop_mobile_special`
--

CREATE TABLE IF NOT EXISTS `cmstop_mobile_special` (
  `contentid` mediumint(8) unsigned NOT NULL,
  `image` varchar(255) NOT NULL,
  PRIMARY KEY (`contentid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `cmstop_mobile_special_category`
--

CREATE TABLE IF NOT EXISTS `cmstop_mobile_special_category` (
  `catid` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `contentid` mediumint(8) unsigned DEFAULT NULL,
  `name` varchar(255) NOT NULL,
  `size` smallint(5) unsigned NOT NULL DEFAULT '0',
  `sort` mediumint(8) unsigned NOT NULL,
  PRIMARY KEY (`catid`),
  KEY `contentid` (`contentid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `cmstop_mobile_special_content`
--

CREATE TABLE IF NOT EXISTS `cmstop_mobile_special_content` (
  `specialid` mediumint(8) unsigned NOT NULL,
  `catid` smallint(5) unsigned NOT NULL,
  `contentid` mediumint(8) unsigned NOT NULL,
  `sort` mediumint(8) unsigned NOT NULL,
  KEY `specialid` (`specialid`),
  KEY `catid` (`catid`),
  KEY `contentid` (`contentid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `cmstop_mobile_style`
--

CREATE TABLE IF NOT EXISTS `cmstop_mobile_style` (
  `styleid` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(40) NOT NULL,
  `data` text NOT NULL,
  `system` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`styleid`),
  KEY `system` (`system`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=11 ;

--
-- Dumping data for table `cmstop_mobile_style`
--

INSERT INTO `cmstop_mobile_style` (`styleid`, `name`, `data`, `system`) VALUES
(1, '经典蓝', '{ "nav":"#0a78cd", "button0":"#144B8F", "button1":"#0a78cd", "background":{ "320*480":"http://m.silkroad.news.cn/templates/default/app/images/background/00/320x480.png", "640*960":"http://m.silkroad.news.cn/templates/default/app/images/background/00/640x960.png", "640*1136":"http://m.silkroad.news.cn/templates/default/app/images/background/00/640x1136.png", "480*800":"http://m.silkroad.news.cn/templates/default/app/images/background/00/480x800.png", "750*1334":"http://m.silkroad.news.cn/templates/default/app/images/background/00/750x1334.png", "1242*2208":"http://m.silkroad.news.cn/templates/default/app/images/background/00/1242x2208.png" } }', 1),
(2, '希望绿', '{ "nav":"#38aa2f", "button0":"#0f7d05", "button1":"#38aa2f", "background":{ "320*480":"http://m.silkroad.news.cn/templates/default/app/images/background/09/320x480.png", "640*960":"http://m.silkroad.news.cn/templates/default/app/images/background/09/640x960.png", "640*1136":"http://m.silkroad.news.cn/templates/default/app/images/background/09/640x1136.png", "480*800":"http://m.silkroad.news.cn/templates/default/app/images/background/09/480x800.png", "750*1334":"http://m.silkroad.news.cn/templates/default/app/images/background/09/750x1334.png", "1242*2208":"http://m.silkroad.news.cn/templates/default/app/images/background/09/1242x2208.png" } }', 1),
(3, '清新绿', '{ "nav":"#2fbcab", "button0":"#00826e", "button1":"#2fbcab", "background":{ "320*480":"http://m.silkroad.news.cn/templates/default/app/images/background/02/320x480.png", "640*960":"http://m.silkroad.news.cn/templates/default/app/images/background/02/640x960.png", "640*1136":"http://m.silkroad.news.cn/templates/default/app/images/background/02/640x1136.png", "480*800":"http://m.silkroad.news.cn/templates/default/app/images/background/02/480x800.png", "750*1334":"http://m.silkroad.news.cn/templates/default/app/images/background/02/750x1334.png", "1242*2208":"http://m.silkroad.news.cn/templates/default/app/images/background/02/1242x2208.png" } }', 1),
(4, '活力橙', '{ "nav":"#ff4400", "button0":"#ff3300", "button1":"#ff4400", "background":{ "320*480":"http://m.silkroad.news.cn/templates/default/app/images/background/03/320x480.png", "640*960":"http://m.silkroad.news.cn/templates/default/app/images/background/03/640x960.png", "640*1136":"http://m.silkroad.news.cn/templates/default/app/images/background/03/640x1136.png", "480*800":"http://m.silkroad.news.cn/templates/default/app/images/background/03/480x800.png", "750*1334":"http://m.silkroad.news.cn/templates/default/app/images/background/03/750x1334.png", "1242*2208":"http://m.silkroad.news.cn/templates/default/app/images/background/03/1242x2208.png" } }', 1),
(5, '温暖黄', '{ "nav":"#fabe00", "button0":"#cd8200", "button1":"#fabe00", "background":{ "320*480":"http://m.silkroad.news.cn/templates/default/app/images/background/04/320x480.png", "640*960":"http://m.silkroad.news.cn/templates/default/app/images/background/04/640x960.png", "640*1136":"http://m.silkroad.news.cn/templates/default/app/images/background/04/640x1136.png", "480*800":"http://m.silkroad.news.cn/templates/default/app/images/background/04/480x800.png", "750*1334":"http://m.silkroad.news.cn/templates/default/app/images/background/04/750x1334.png", "1242*2208":"http://m.silkroad.news.cn/templates/default/app/images/background/04/1242x2208.png" } }', 1),
(6, '肃穆黑', '{ "nav":"#303132", "button0":"#161717", "button1":"#303132", "background":{ "320*480":"http://m.silkroad.news.cn/templates/default/app/images/background/05/320x480.png", "640*960":"http://m.silkroad.news.cn/templates/default/app/images/background/05/640x960.png", "640*1136":"http://m.silkroad.news.cn/templates/default/app/images/background/05/640x1136.png", "480*800":"http://m.silkroad.news.cn/templates/default/app/images/background/05/480x800.png", "750*1334":"http://m.silkroad.news.cn/templates/default/app/images/background/05/750x1334.png", "1242*2208":"http://m.silkroad.news.cn/templates/default/app/images/background/05/1242x2208.png" } }', 1),
(7, '尊享红', '{ "nav":"#c80505", "button0":"#960505", "button1":"#c80505", "background":{ "320*480":"http://m.silkroad.news.cn/templates/default/app/images/background/07/320x480.png", "640*960":"http://m.silkroad.news.cn/templates/default/app/images/background/07/640x960.png", "640*1136":"http://m.silkroad.news.cn/templates/default/app/images/background/07/640x1136.png", "480*800":"http://m.silkroad.news.cn/templates/default/app/images/background/07/480x800.png", "750*1334":"http://m.silkroad.news.cn/templates/default/app/images/background/07/750x1334.png", "1242*2208":"http://m.silkroad.news.cn/templates/default/app/images/background/07/1242x2208.png" } }', 1),
(8, '梦幻紫', '{ "nav":"#682878", "button0":"#500e61", "button1":"#682878", "background":{ "320*480":"http://m.silkroad.news.cn/templates/default/app/images/background/08/320x480.png", "640*960":"http://m.silkroad.news.cn/templates/default/app/images/background/08/640x960.png", "640*1136":"http://m.silkroad.news.cn/templates/default/app/images/background/08/640x1136.png", "480*800":"http://m.silkroad.news.cn/templates/default/app/images/background/08/480x800.png", "750*1334":"http://m.silkroad.news.cn/templates/default/app/images/background/08/750x1334.png", "1242*2208":"http://m.silkroad.news.cn/templates/default/app/images/background/08/1242x2208.png" } }', 1),
(9, '柔软红', '{ "nav":"#ff7373", "button0":"#e7757e", "button1":"#ff7373", "background":{ "320*480":"http://m.silkroad.news.cn/templates/default/app/images/background/10/320x480.png", "640*960":"http://m.silkroad.news.cn/templates/default/app/images/background/10/640x960.png", "640*1136":"http://m.silkroad.news.cn/templates/default/app/images/background/10/640x1136.png", "480*800":"http://m.silkroad.news.cn/templates/default/app/images/background/10/480x800.png", "750*1334":"http://m.silkroad.news.cn/templates/default/app/images/background/10/750x1334.png", "1242*2208":"http://m.silkroad.news.cn/templates/default/app/images/background/10/1242x2208.png" } }', 1);

-- --------------------------------------------------------

--
-- Table structure for table `cmstop_mobile_video`
--

CREATE TABLE IF NOT EXISTS `cmstop_mobile_video` (
  `contentid` mediumint(8) unsigned NOT NULL,
  `video` varchar(255) NOT NULL,
  `playtime` smallint(5) unsigned NOT NULL,
  PRIMARY KEY (`contentid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `cmstop_model`
--

CREATE TABLE IF NOT EXISTS `cmstop_model` (
  `modelid` tinyint(2) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(20) NOT NULL,
  `alias` varchar(15) NOT NULL,
  `description` varchar(255) NOT NULL,
  `template_list` varchar(100) NOT NULL,
  `template_show` varchar(100) NOT NULL,
  `posts` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `comments` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `pv` int(10) unsigned NOT NULL DEFAULT '0',
  `sort` tinyint(2) unsigned NOT NULL DEFAULT '0',
  `disabled` tinyint(1) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`modelid`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=11 ;

--
-- Dumping data for table `cmstop_model`
--

INSERT INTO `cmstop_model` (`modelid`, `name`, `alias`, `description`, `template_list`, `template_show`, `posts`, `comments`, `pv`, `sort`, `disabled`) VALUES
(1, '文章', 'article', '', 'article/list.html', 'article/show.html', 0, 0, 0, 0, 0),
(2, '组图', 'picture', '', 'picture/list.html', 'picture/show.html', 0, 0, 0, 0, 0),
(3, '链接', 'link', '', 'link/list.html', '', 0, 0, 0, 0, 0),
(4, '视频', 'video', '', 'video/list.html', 'video/show.html', 0, 0, 0, 0, 0),
(7, '活动', 'activity', '', 'activity/list.html', 'activity/show.html', 0, 0, 0, 0, 0),
(8, '投票', 'vote', '', 'vote/list.html', 'vote/show.html', 0, 0, 0, 0, 0),
(9, '调查', 'survey', '', 'survey/list.html', 'survey/show.html', 0, 0, 0, 0, 0),
(10, '专题', 'special', '', 'special/list.html', '', 0, 0, 0, 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `cmstop_mood`
--

CREATE TABLE IF NOT EXISTS `cmstop_mood` (
  `moodid` tinyint(2) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(20) NOT NULL,
  `image` varchar(100) NOT NULL,
  `sort` tinyint(2) unsigned NOT NULL,
  PRIMARY KEY (`moodid`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT AUTO_INCREMENT=7 ;

--
-- Dumping data for table `cmstop_mood`
--

INSERT INTO `cmstop_mood` (`moodid`, `name`, `image`, `sort`) VALUES
(1, '微笑', 'images/weixiao.png', 1),
(2, '难过', 'images/nanguo.png', 3),
(3, '流汗', 'images/liuhan.png', 2),
(4, '羡慕', 'images/xianmu.png', 4),
(5, '愤怒', 'images/fennu.png', 5),
(6, '流泪', 'images/liulei.png', 6);

-- --------------------------------------------------------

--
-- Table structure for table `cmstop_mood_data`
--

CREATE TABLE IF NOT EXISTS `cmstop_mood_data` (
  `contentid` mediumint(8) unsigned NOT NULL,
  `total` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `updatetime` int(10) unsigned NOT NULL DEFAULT '0',
  `m1` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `m2` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `m3` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `m4` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `m5` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `m6` mediumint(8) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`contentid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `cmstop_mymenu`
--

CREATE TABLE IF NOT EXISTS `cmstop_mymenu` (
  `id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(30) NOT NULL,
  `url` varchar(255) NOT NULL,
  `userid` mediumint(8) unsigned NOT NULL,
  `addtime` int(10) unsigned NOT NULL,
  `sort` tinyint(3) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `cmstop_note`
--

CREATE TABLE IF NOT EXISTS `cmstop_note` (
  `userid` mediumint(8) NOT NULL,
  `note` text,
  `lastmodified` int(10) NOT NULL,
  PRIMARY KEY (`userid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `cmstop_online`
--

CREATE TABLE IF NOT EXISTS `cmstop_online` (
  `onlineid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `userid` mediumint(8) unsigned DEFAULT NULL,
  `groupid` tinyint(3) unsigned DEFAULT NULL,
  `roleid` tinyint(3) unsigned DEFAULT NULL,
  `ip` int(10) unsigned NOT NULL,
  `time` int(10) unsigned NOT NULL,
  PRIMARY KEY (`onlineid`),
  KEY `time` (`time`,`ip`,`userid`)
) ENGINE=MEMORY DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `cmstop_openaca`
--

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
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=21 ;

--
-- Dumping data for table `cmstop_openaca`
--

INSERT INTO `cmstop_openaca` (`acaid`, `parentid`, `app`, `controller`, `action`, `name`) VALUES
(1, NULL, 'system', NULL, NULL, '系统'),
(2, 1, 'system', 'category', NULL, '栏目'),
(3, 2, 'system', 'category', 'ls', '读取栏目列表'),
(4, NULL, 'page', NULL, NULL, '页面'),
(5, 4, 'page', 'page', NULL, '页面'),
(6, 5, 'page', 'page', 'ls', '读取页面列表'),
(7, 4, 'page', 'section', '', '区块'),
(8, 7, 'page', 'section', 'ls', '读取区块列表'),
(9, 7, 'page', 'section', 'get', '读取区块内容'),
(10, 7, 'page', 'section', 'gethtml', '读取区块HTML'),
(11, 1, 'system', 'content', NULL, '内容'),
(12, 11, 'system', 'content', 'ls', '读取内容列表'),
(13, NULL, 'article', NULL, NULL, '文章'),
(14, 13, 'article', 'article', NULL, '文章'),
(15, 14, 'article', 'article', 'get', '读取文章内容'),
(16, 14, 'article', 'article', 'add', '添加文章内容'),
(17, NULL, 'video', NULL, NULL, '视频'),
(18, 17, 'video', 'video', NULL, '视频'),
(19, 18, 'video', 'video', 'get', '读取视频内容'),
(20, 18, 'video', 'video', 'add', '添加视频内容');

-- --------------------------------------------------------

--
-- Table structure for table `cmstop_openaca_user`
--

CREATE TABLE IF NOT EXISTS `cmstop_openaca_user` (
  `userid` int(10) unsigned NOT NULL DEFAULT '0',
  `acaid` smallint(5) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`userid`,`acaid`),
  KEY `acaid` (`acaid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='开放接口用户权限分配';

-- --------------------------------------------------------

--
-- Table structure for table `cmstop_openauth`
--

CREATE TABLE IF NOT EXISTS `cmstop_openauth` (
  `userid` int(10) unsigned NOT NULL COMMENT '用户编号',
  `username` varchar(50) NOT NULL DEFAULT '' COMMENT '用户名，冗余保存',
  `auth_key` varchar(32) NOT NULL DEFAULT '' COMMENT '授权公钥',
  `auth_secret` varchar(32) NOT NULL DEFAULT '' COMMENT '授权私钥',
  `disabled` tinyint(1) unsigned DEFAULT '0' COMMENT '是否禁用',
  `remarks` varchar(255) DEFAULT '' COMMENT '备注',
  PRIMARY KEY (`userid`),
  UNIQUE KEY `auth_key` (`auth_key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='开放接口授权信息';

-- --------------------------------------------------------

--
-- Table structure for table `cmstop_page`
--
CREATE TABLE IF NOT EXISTS `cmstop_page` (
  `pageid` smallint(5) unsigned NOT NULL auto_increment,
  `parentid` smallint(5) unsigned default NULL,
  `parentids` text,
  `childids` varchar(255) default NULL,
  `name` varchar(20) NOT NULL,
  `template` varchar(100) NOT NULL,
  `path` varchar(100) NOT NULL,
  `url` varchar(255) NOT NULL,
  `frequency` smallint(5) unsigned NOT NULL default '3600',
  `published` int(10) unsigned default NULL,
  `nextpublish` int(10) unsigned default NULL,
  `updated` int(10) unsigned default NULL,
  `updatedby` mediumint(8) unsigned default NULL,
  `created` int(10) unsigned default NULL,
  `createdby` mediumint(8) unsigned default NULL,
  `sort` tinyint(3) unsigned NOT NULL default '0',
  `status` tinyint(3) unsigned NOT NULL default '1',
  PRIMARY KEY  (`pageid`),
  KEY `parentid` (`parentid`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=37 ;

--
-- 转存表中的数据 `cmstop_page`
--

INSERT INTO `cmstop_page` (`pageid`, `parentid`, `parentids`, `childids`, `name`, `template`, `path`, `url`, `frequency`, `published`, `nextpublish`, `updated`, `updatedby`, `created`, `createdby`, `sort`, `status`) VALUES
(1, NULL, NULL, NULL, '网站首页', 'index.html', '{PSN:1}/index.shtml', 'http://www.silkroad.news.cn/', 300, 1426917236, 1426917536, NULL, NULL, 1342494720, 1, 1, 1),
(2, NULL, NULL, NULL, '公共区块', 'system/404.html', '{PSN:1}/include/404.shtml', 'http://www.silkroad.news.cn/include/404.shtml', 0, 1425895673, 1426916681, NULL, NULL, 1342496060, 1, 97, 1),
(3, NULL, NULL, '5,6,7,12,13,14,15,31,33,36,32', '广告页面', 'system/404.html', '{PSN:1}/404.shtml', 'http://www.silkroad.news.cn/404.shtml', 0, 1425895705, 1426916690, NULL, NULL, 1342505809, 1, 99, 1),
(4, NULL, NULL, NULL, '图片频道', 'picture/index.html', '{PSN:4}/index.shtml', 'http://photo.silkroad.news.cn/', 900, 1426915214, 1426917367, NULL, NULL, 1342506099, 1, 2, 1),
(5, 3, '3', '32', '全局广告', 'system/404.html', '{PSN:1}/include/404.shtml', 'http://www.silkroad.news.cn/include/404.shtml', 0, 1426731011, 1426916693, NULL, NULL, 1342506269, 1, 0, 1),
(6, 3, '3', NULL, '视频内容页', 'system/404.html', '{PSN:1}/404.shtml', 'http://www.silkroad.news.cn/404.shtml', 0, 1425895754, 1426916696, NULL, NULL, 1342509674, 1, 0, 1),
(7, 3, '3', NULL, '首页广告', 'system/404.html', '{PSN:1}/include/404.shtml', 'http://www.silkroad.news.cn/include/404.shtml', 0, 1425895500, 1426916699, NULL, NULL, 1342509874, 1, 0, 1),
(8, NULL, NULL, NULL, '视频频道', 'video/index.html', '{PSN:1}/video/index.shtml', 'http://www.silkroad.news.cn/video/', 900, 1426843260, 1426917381, NULL, NULL, 1342513624, 1, 3, 1),
(9, NULL, NULL, NULL, '访谈频道', 'interview/index.html', '{PSN:3}/index.shtml', 'http://talk.silkroad.news.cn/', 3600, 1426578888, 1426582488, NULL, NULL, 1342514434, 1, 0, 0),
(10, NULL, NULL, NULL, '专题频道', 'special/index.html', '{PSN:2}/index.shtml', 'http://special.silkroad.news.cn/', 900, 1426915471, 1426917466, NULL, NULL, 1342514497, 1, 5, 1),
(11, NULL, NULL, NULL, '文章内容', 'system/404.html', '{PSN:1}/include/404.shtml', 'http://www.silkroad.news.cn/include/404.shtml', 0, 1425895507, 1426916677, NULL, NULL, 1342516156, 1, 7, 1),
(12, 3, '3', NULL, '文章内容页', 'system/404.html', '{PSN:1}/include/404.shtml', 'http://www.silkroad.news.cn/include/404.shtml', 0, 1425895501, 1426916703, NULL, NULL, 1342516882, 1, 0, 1),
(13, 3, '3', NULL, '列表页广告', 'system/404.html', '{PSN:1}/include/404.shtml', 'http://www.silkroad.news.cn/include/404.shtml', 0, 1425895501, 1426916707, NULL, NULL, 1342517700, 1, 0, 1),
(14, 3, '3', NULL, '图片频道页', 'system/404.html', '{PSN:1}/include/404.shtml', 'http://www.silkroad.news.cn/include/404.shtml', 0, 1425895502, 1426916710, NULL, NULL, 1342581668, 1, 0, 1),
(15, 3, '3', NULL, '视频频道页', 'system/404.html', '{PSN:1}/include/404.shtml', 'http://www.silkroad.news.cn/include/404.shtml', 0, 1425895503, 1426916714, NULL, NULL, 1342583265, 1, 0, 1),
(16, NULL, NULL, '17,18,19', '关于我们', 'page/about.html', '{PSN:1}/about/index.shtml', 'http://www.silkroad.news.cn/about/', 3600, 1426060904, 1426920146, NULL, NULL, 1343098044, 1, 98, 1),
(17, 16, '16', NULL, '联系我们', 'page/contact.html', '{PSN:1}/about/contact.shtml', 'http://www.silkroad.news.cn/about/contact.shtml', 3600, 1426060912, 1426064512, NULL, NULL, 1343099173, 1, 0, 1),
(18, 16, '16', NULL, '加入我们', 'page/jobs.html', '{PSN:1}/about/jobs.shtml', 'http://www.silkroad.news.cn/about/jobs.shtml', 3600, 1426060924, 1426064524, NULL, NULL, 1343099210, 1, 0, 1),
(19, 16, '16', NULL, '版权声明', 'page/copyright.html', '{PSN:1}/about/copyright.shtml', 'http://www.silkroad.news.cn/about/copyright.shtml', 3600, 1426060932, 1426064532, NULL, NULL, 1343099842, 1, 0, 1),
(20, NULL, NULL, NULL, '手机触屏', 'page/mobile.html', '{PSN:1}/about/mobile.shtml', 'http://www.silkroad.news.cn/about/mobile.shtml', 0, 1426842750, 1426916640, NULL, NULL, 1268731277, 1, 6, 1),
(21, NULL, NULL, NULL, '投票', 'vote/vote_commment_first.html', '{PSN:1}/vote/vote_comment_first.shtml', 'http://www.silkroad.news.cn/vote/vote_comment_first.shtml', 3600, 1422942108, 1422945708, NULL, NULL, 1422941570, 1, 0, 0),
(22, NULL, NULL, NULL, '投票', 'vote/vote_comment_first.html', '{PSN:1}/vote/vote_comment_first.shtml', 'http://www.silkroad.news.cn/vote/vote_comment_first.shtml', 3600, 1422942564, 1422946164, NULL, NULL, 1422942357, 1, 0, 0),
(23, NULL, NULL, NULL, '投票', 'vote/vote_commment_first.html', '{PSN:1}/vote/vote_comment_first.shtml', 'http://www.silkroad.news.cn/vote/vote_comment_first.shtml', 3600, 1422944066, 1422947666, NULL, NULL, 1422943853, 1, 0, 0),
(24, NULL, NULL, NULL, '投票', 'vote/vote_commment_first.html', '{PSN:1}/vote/vote_comment_first.shtml', 'http://www.silkroad.news.cn/vote/vote_comment_first.shtml', 3600, NULL, 1422947456, NULL, NULL, 1422943856, 1, 0, 0),
(25, NULL, NULL, NULL, '投票', 'vote/vote_comment_first.html', '{PSN:1}/vote/vote_comment_first.shtml', 'http://www.silkroad.news.cn/vote/vote_comment_first.shtml', 3600, 1422945956, 1422949556, NULL, NULL, 1422944636, 1, 0, 0),
(26, NULL, NULL, NULL, '视频内容页', 'system/404.html', '{PSN:1}/404.shtml', 'http://www.silkroad.news.cn/404.shtml', 3600, NULL, 1422948688, NULL, NULL, 1422945088, 1, 0, 0),
(27, NULL, NULL, NULL, '标签页', 'system/tag.html', '{PSN:1}/tag/tag.shtml', 'http://www.silkroad.news.cn/tag/tag.shtml', 3600, 1423036266, 1423039866, NULL, NULL, 1423033520, 1, 0, 0),
(28, NULL, NULL, NULL, '标签页', 'system/tags.html', '{PSN:1}/tag/tags.shtml', 'http://www.silkroad.news.cn/tag/tags.shtml', 3600, 1423037600, 1423041200, NULL, NULL, 1423036393, 1, 0, 0),
(29, NULL, NULL, NULL, '投票页', 'vote/show.html', '{PSN:1}/vote/show.shtml', 'http://www.silkroad.news.cn/vote/show.shtml', 3600, 1423106255, 1423109855, NULL, NULL, 1423103789, 1, 0, 0),
(30, NULL, NULL, NULL, '投票页', 'vote/show_all.html', '{PSN:1}/vote/show_all.shtml', 'http://www.silkroad.news.cn/vote/show_all.shtml', 3600, 1423120920, 1423124520, NULL, NULL, 1423115012, 1, 0, 0),
(31, 3, '3', NULL, '专题频道页', 'system/404.html', '{PSN:1}/404.shtml', 'http://www.silkroad.news.cn/404.shtml', 0, 1425895503, 1426916717, NULL, NULL, 1423559477, 1, 0, 1),
(32, 5, '3,5', NULL, '访谈内容页', 'system/404.html', '{PSN:1}/include/404.shtml', 'http://www.silkroad.news.cn/include/404.shtml', 3600, NULL, 1423564755, NULL, NULL, 1423561155, 1, 0, 0),
(33, 3, '3', NULL, '访谈内容页', 'system/404.html', '{PSN:1}/include/404.shtml', 'http://www.silkroad.news.cn/include/404.shtml', 0, 1425895504, 1426916721, NULL, NULL, 1423561194, 1, 0, 1),
(34, NULL, NULL, NULL, '活动频道', 'activity/index.html', '{PSN:1}/activity/index.shtml', 'http://www.silkroad.news.cn/activity/', 900, 1426844578, 1426917570, NULL, NULL, 1425353176, 1, 4, 1),
(35, NULL, NULL, NULL, '杂志页', 'system/404.html', '{PSN:1}/include/404.shtml', 'http://www.silkroad.news.cn/include/404.shtml', 3600, NULL, 1425543560, NULL, NULL, 1425539960, 1, 0, 0),
(36, 3, '3', NULL, '杂志页', 'system/404.html', '{PSN:1}/include/404.shtml', 'http://www.silkroad.news.cn/include/404.shtml', 3600, 1425869112, 1425872712, NULL, NULL, 1425540130, 1, 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `cmstop_page_priv`
--

CREATE TABLE IF NOT EXISTS `cmstop_page_priv` (
  `pageid` smallint(5) unsigned NOT NULL DEFAULT '0',
  `userid` mediumint(8) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`pageid`,`userid`),
  KEY `userid` (`userid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `cmstop_page_stat`
--

CREATE TABLE IF NOT EXISTS `cmstop_page_stat` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `pageid` smallint(5) unsigned NOT NULL,
  `date` date NOT NULL,
  `pv` mediumint(8) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `pageid` (`pageid`,`date`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `cmstop_paper`
--

CREATE TABLE IF NOT EXISTS `cmstop_paper` (
  `paperid` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `alias` varchar(30) NOT NULL,
  `logo` varchar(100) DEFAULT NULL,
  `pages` smallint(2) unsigned DEFAULT NULL,
  `template_content` varchar(100) NOT NULL,
  `created` int(10) unsigned NOT NULL,
  `createdby` mediumint(8) unsigned NOT NULL,
  `ip` varchar(15) NOT NULL,
  `url` varchar(255) DEFAULT 'javascript:;',
  `disabled` tinyint(1) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`paperid`),
  KEY `disabled` (`disabled`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `cmstop_paper_content`
--

CREATE TABLE IF NOT EXISTS `cmstop_paper_content` (
  `mapid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `paperid` int(10) unsigned NOT NULL,
  `editionid` int(10) unsigned NOT NULL,
  `pageid` int(10) unsigned NOT NULL,
  `pageno` tinyint(2) unsigned NOT NULL,
  `contentid` mediumint(8) unsigned NOT NULL,
  `coords` varchar(30) NOT NULL,
  `sort` tinyint(2) unsigned NOT NULL DEFAULT '0',
  `pv` mediumint(9) unsigned DEFAULT '0',
  PRIMARY KEY (`mapid`),
  KEY `pageid` (`pageid`),
  KEY `paperid` (`paperid`,`editionid`,`pageno`),
  KEY `editionid` (`editionid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `cmstop_paper_edition`
--

CREATE TABLE IF NOT EXISTS `cmstop_paper_edition` (
  `editionid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `paperid` smallint(5) unsigned NOT NULL,
  `number` varchar(10) NOT NULL,
  `total_number` varchar(10) DEFAULT NULL,
  `date` int(10) unsigned NOT NULL,
  `created` int(10) unsigned NOT NULL,
  `createdby` mediumint(8) unsigned NOT NULL,
  `ip` varchar(15) NOT NULL,
  `url` varchar(255) DEFAULT 'javascript:;',
  `disabled` tinyint(1) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`editionid`),
  KEY `paperid` (`paperid`,`disabled`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `cmstop_paper_edition_page`
--

CREATE TABLE IF NOT EXISTS `cmstop_paper_edition_page` (
  `pageid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `paperid` int(10) unsigned NOT NULL,
  `editionid` int(10) unsigned NOT NULL,
  `pageno` tinyint(2) unsigned NOT NULL,
  `name` varchar(30) NOT NULL,
  `image` varchar(100) NOT NULL,
  `pdf` varchar(100) NOT NULL,
  `editor` varchar(30) NOT NULL,
  `arteditor` varchar(30) NOT NULL,
  `url` varchar(255) DEFAULT 'javascript:;',
  PRIMARY KEY (`pageid`),
  UNIQUE KEY `editionid` (`editionid`,`pageno`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `cmstop_picture`
--

CREATE TABLE IF NOT EXISTS `cmstop_picture` (
  `contentid` mediumint(8) unsigned NOT NULL,
  `editor` varchar(15) DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  `total` tinyint(3) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`contentid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `cmstop_picture_group`
--

CREATE TABLE IF NOT EXISTS `cmstop_picture_group` (
  `pictureid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `contentid` mediumint(8) unsigned NOT NULL,
  `aid` int(10) unsigned NOT NULL,
  `image` varchar(100) NOT NULL,
  `note` varchar(255) NOT NULL,
  `url` varchar(255) NOT NULL,
  `sort` tinyint(3) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`pictureid`),
  KEY `contentid` (`contentid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `cmstop_place`
--

CREATE TABLE IF NOT EXISTS `cmstop_place` (
  `placeid` int(10) unsigned NOT NULL,
  `pageid` mediumint(8) unsigned DEFAULT NULL,
  `name` varchar(100) NOT NULL,
  `description` text,
  PRIMARY KEY (`placeid`),
  KEY `pageid` (`pageid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `cmstop_place_data`
--

CREATE TABLE IF NOT EXISTS `cmstop_place_data` (
  `dataid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `placeid` int(10) unsigned NOT NULL,
  `contentid` mediumint(8) unsigned DEFAULT NULL,
  `title` varchar(255) NOT NULL,
  `color` varchar(7) DEFAULT NULL,
  `url` varchar(255) NOT NULL,
  `thumb` varchar(255) DEFAULT NULL,
  `description` text,
  `time` int(10) unsigned DEFAULT NULL,
  `sort` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `created` int(10) unsigned NOT NULL,
  `createdby` mediumint(8) unsigned NOT NULL,
  `status` tinyint(4) DEFAULT NULL,
  PRIMARY KEY (`dataid`),
  UNIQUE KEY `placeid_2` (`placeid`,`contentid`),
  KEY `contentid` (`contentid`),
  KEY `createdby` (`createdby`),
  KEY `placeid` (`placeid`,`sort`,`time`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `cmstop_port`
--

CREATE TABLE IF NOT EXISTS `cmstop_port` (
  `portid` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `port` varchar(20) NOT NULL,
  `name` varchar(100) NOT NULL,
  `url` varchar(255) NOT NULL,
  `authkey` varchar(100) DEFAULT NULL,
  `disabled` tinyint(3) unsigned DEFAULT NULL,
  PRIMARY KEY (`portid`),
  UNIQUE KEY `port` (`port`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `cmstop_property`
--

CREATE TABLE IF NOT EXISTS `cmstop_property` (
  `proid` smallint(5) unsigned NOT NULL AUTO_INCREMENT COMMENT '属性ID',
  `parentid` smallint(5) unsigned DEFAULT NULL,
  `name` varchar(30) NOT NULL,
  `description` varchar(255) DEFAULT NULL,
  `parentids` varchar(255) DEFAULT NULL,
  `childids` text,
  `sort` tinyint(2) unsigned NOT NULL DEFAULT '0',
  `disabled` tinyint(1) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`proid`),
  KEY `parentid` (`parentid`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='属性表' AUTO_INCREMENT=12 ;

--
-- Dumping data for table `cmstop_property`
--

INSERT INTO `cmstop_property` (`proid`, `parentid`, `name`, `description`, `parentids`, `childids`, `sort`, `disabled`) VALUES
(1, NULL, '地区', '', NULL, '2,3,4,5', 0, 0),
(2, 1, '北京', '', '1', NULL, 0, 0),
(3, 1, '上海', '', '1', NULL, 0, 0),
(4, 1, '天津', '', '1', NULL, 0, 0),
(5, 1, '重庆', '', '1', NULL, 0, 0),
(7, 6, '少儿', '', '6', NULL, 0, 0),
(8, 6, '少年', '', '6', NULL, 0, 0),
(9, 6, '青年', '', '6', NULL, 0, 0),
(10, 6, '中年', '', '6', NULL, 0, 0),
(11, 6, '老年', '', '6', NULL, 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `cmstop_psn`
--

CREATE TABLE IF NOT EXISTS `cmstop_psn` (
  `psnid` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(20) NOT NULL,
  `path` varchar(100) NOT NULL,
  `url` varchar(255) NOT NULL,
  `sort` tinyint(3) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`psnid`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=7 ;

--
-- Dumping data for table `cmstop_psn`
--

INSERT INTO `cmstop_psn` (`psnid`, `name`, `path`, `url`, `sort`) VALUES
(1, '网站首页', '', 'http://www.silkroad.news.cn/', 0),
(2, '专题', 'special/', 'http://special.silkroad.news.cn/', 0),
(3, '访谈', 'talk/', 'http://talk.silkroad.news.cn/', 0),
(4, '图片', 'photo/', 'http://photo.silkroad.news.cn/', 0),
(5, '视频', 'video/', 'http://video.silkroad.news.cn/', 0),
(6, '专栏', 'space/', 'http://space.silkroad.news.cn/', 6);

-- --------------------------------------------------------

--
-- Table structure for table `cmstop_push`
--

CREATE TABLE IF NOT EXISTS `cmstop_push` (
  `pushid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `guid` int(10) unsigned NOT NULL,
  `taskid` mediumint(8) unsigned NOT NULL,
  `contentid` mediumint(8) unsigned DEFAULT NULL,
  `status` enum('pushed','viewed','new') NOT NULL DEFAULT 'new',
  `pushed` int(10) unsigned DEFAULT NULL,
  `pushedby` mediumint(8) unsigned DEFAULT NULL,
  PRIMARY KEY (`pushid`),
  UNIQUE KEY `guid` (`guid`,`taskid`),
  KEY `taskid` (`taskid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `cmstop_push_rule`
--

CREATE TABLE IF NOT EXISTS `cmstop_push_rule` (
  `ruleid` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `dsnid` smallint(5) unsigned NOT NULL,
  `maintable` text,
  `jointable` text,
  `primary` varchar(255) DEFAULT NULL,
  `linkrule` varchar(255) DEFAULT NULL,
  `fields` text,
  `defaults` text,
  `condition` text,
  `plugin` varchar(20) DEFAULT NULL,
  `description` text,
  `created` int(10) unsigned NOT NULL,
  `createdby` mediumint(8) unsigned NOT NULL,
  `updated` int(10) unsigned DEFAULT NULL,
  `updatedby` mediumint(8) unsigned DEFAULT NULL,
  PRIMARY KEY (`ruleid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `cmstop_push_task`
--

CREATE TABLE IF NOT EXISTS `cmstop_push_task` (
  `taskid` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `ruleid` mediumint(8) unsigned NOT NULL,
  `extra_condition` text,
  `catid` smallint(5) unsigned NOT NULL,
  `title` varchar(255) NOT NULL,
  `created` int(10) unsigned NOT NULL,
  `createdby` mediumint(8) unsigned NOT NULL,
  `updated` int(10) unsigned DEFAULT NULL,
  `updatedby` mediumint(8) unsigned DEFAULT NULL,
  PRIMARY KEY (`taskid`),
  KEY `catid` (`catid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `cmstop_qrcode`
--

CREATE TABLE IF NOT EXISTS `cmstop_qrcode` (
  `qrcodeid` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `short` varchar(100) NOT NULL,
  `str` varchar(255) NOT NULL,
  `path` varchar(255) NOT NULL,
  `note` text CHARACTER SET utf8 COLLATE utf8_bin,
  `type` varchar(50) DEFAULT NULL,
  `contentid` mediumint(9) unsigned DEFAULT NULL,
  `modelid` tinyint(4) unsigned DEFAULT NULL,
  `pv` int(10) unsigned NOT NULL DEFAULT '0',
  `created` int(10) unsigned NOT NULL,
  `createdby` mediumint(8) unsigned NOT NULL,
  PRIMARY KEY (`qrcodeid`),
  UNIQUE KEY `short` (`short`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `cmstop_qrcode_stat`
--

CREATE TABLE IF NOT EXISTS `cmstop_qrcode_stat` (
  `qrcodeid` int(10) unsigned NOT NULL,
  `pv` int(10) unsigned NOT NULL,
  `platform` varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (`qrcodeid`,`platform`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `cmstop_queue`
--

CREATE TABLE IF NOT EXISTS `cmstop_queue` (
  `queueid` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '队列ID',
  `engine` varchar(30) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL COMMENT '队列引擎',
  `arguments` text CHARACTER SET utf8 COLLATE utf8_bin COMMENT '执行参数',
  `nextrun` int(10) unsigned NOT NULL COMMENT '执行时间',
  `created` int(10) unsigned NOT NULL COMMENT '创建时间',
  `createdby` mediumint(8) unsigned NOT NULL COMMENT '创建人',
  `started` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '开始时间',
  `ended` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '结束时间',
  `result` text CHARACTER SET utf8 COLLATE utf8_bin COMMENT '执行结果',
  `times` smallint(1) unsigned NOT NULL COMMENT '尝试次数',
  `status` tinyint(3) unsigned NOT NULL DEFAULT '1' COMMENT '状态',
  `delete` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '执行后删除',
  PRIMARY KEY (`queueid`),
  KEY `status` (`queueid`,`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `cmstop_queue_log`
--

CREATE TABLE IF NOT EXISTS `cmstop_queue_log` (
  `logid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `queueid` int(10) unsigned NOT NULL,
  `action` varchar(30) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `arguments` text CHARACTER SET utf8 COLLATE utf8_bin,
  `result` text CHARACTER SET utf8 COLLATE utf8_bin,
  `message` text CHARACTER SET utf8 COLLATE utf8_bin,
  PRIMARY KEY (`logid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `cmstop_related`
--

CREATE TABLE IF NOT EXISTS `cmstop_related` (
  `relatedid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `contentid` mediumint(8) unsigned DEFAULT NULL,
  `orign_contentid` varchar(40) DEFAULT NULL,
  `title` varchar(80) NOT NULL,
  `thumb` varchar(100) DEFAULT NULL,
  `url` varchar(255) NOT NULL,
  `time` date DEFAULT NULL,
  `sort` tinyint(2) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`relatedid`),
  KEY `contentid` (`contentid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `cmstop_role`
--

CREATE TABLE IF NOT EXISTS `cmstop_role` (
  `roleid` tinyint(3) unsigned NOT NULL AUTO_INCREMENT,
  `name` char(20) NOT NULL,
  PRIMARY KEY (`roleid`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=18 ;

--
-- Dumping data for table `cmstop_role`
--

INSERT INTO `cmstop_role` (`roleid`, `name`) VALUES
(1, '超级管理员'),
(2, '总编辑'),
(3, '频道主编'),
(4, '页面编辑'),
(5, '区块编辑'),
(6, '责任编辑'),
(7, '发稿编辑'),
(9, '会员管理员'),
(10, '报纸管理员'),
(11, '杂志管理员'),
(13, '评论管理员'),
(14, '微博管理员'),
(15, '报料管理员'),
(16, '移动管理员'),
(17, '移动编辑');

-- --------------------------------------------------------

--
-- Table structure for table `cmstop_role_aca`
--

CREATE TABLE IF NOT EXISTS `cmstop_role_aca` (
  `roleid` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `acaid` smallint(5) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`roleid`,`acaid`),
  KEY `acaid` (`acaid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `cmstop_role_aca`
--

INSERT INTO `cmstop_role_aca` (`roleid`, `acaid`) VALUES
(2, 5),
(2, 16),
(2, 20),
(2, 27),
(2, 30),
(3, 31),
(6, 31),
(7, 31),
(14, 31),
(15, 31),
(16, 31),
(3, 32),
(6, 32),
(7, 32),
(16, 32),
(3, 33),
(6, 33),
(16, 33),
(3, 34),
(6, 34),
(7, 34),
(16, 34),
(3, 35),
(6, 35),
(7, 35),
(16, 35),
(2, 37),
(3, 38),
(6, 38),
(7, 38),
(16, 38),
(2, 40),
(3, 40),
(16, 40),
(6, 41),
(7, 41),
(6, 42),
(2, 44),
(3, 44),
(16, 44),
(6, 45),
(7, 45),
(6, 46),
(7, 46),
(6, 47),
(7, 47),
(2, 58),
(3, 58),
(16, 58),
(2, 63),
(3, 63),
(6, 63),
(7, 63),
(16, 63),
(2, 70),
(3, 70),
(6, 70),
(7, 70),
(16, 70),
(2, 76),
(2, 80),
(2, 96),
(3, 96),
(16, 96),
(2, 97),
(3, 97),
(6, 97),
(7, 97),
(16, 97),
(2, 99),
(3, 99),
(16, 99),
(6, 101),
(7, 101),
(2, 104),
(3, 104),
(16, 104),
(2, 109),
(3, 109),
(16, 109),
(2, 110),
(3, 110),
(16, 110),
(2, 111),
(3, 111),
(16, 111),
(2, 136),
(2, 137),
(3, 139),
(6, 139),
(7, 139),
(16, 139),
(17, 139),
(3, 141),
(6, 141),
(7, 141),
(16, 141),
(17, 141),
(3, 143),
(6, 143),
(7, 143),
(16, 143),
(17, 143),
(3, 145),
(6, 145),
(7, 145),
(16, 145),
(17, 145),
(3, 146),
(6, 146),
(7, 146),
(16, 146),
(17, 146),
(3, 148),
(6, 148),
(7, 148),
(2, 150),
(3, 151),
(6, 151),
(7, 151),
(2, 163),
(3, 163),
(6, 163),
(7, 163),
(2, 164),
(3, 165),
(6, 165),
(7, 165),
(16, 165),
(17, 165),
(3, 166),
(6, 166),
(7, 166),
(16, 166),
(17, 166),
(3, 167),
(6, 167),
(7, 167),
(16, 167),
(17, 167),
(3, 169),
(6, 169),
(7, 169),
(16, 169),
(17, 169),
(3, 170),
(6, 170),
(7, 170),
(16, 170),
(17, 170),
(3, 171),
(6, 171),
(7, 171),
(16, 171),
(17, 171),
(2, 178),
(3, 180),
(6, 180),
(7, 180),
(16, 180),
(3, 181),
(6, 181),
(7, 181),
(16, 181),
(3, 182),
(6, 182),
(7, 182),
(16, 182),
(3, 183),
(6, 183),
(16, 183),
(3, 185),
(6, 185),
(7, 185),
(16, 185),
(6, 186),
(7, 186),
(3, 187),
(6, 187),
(7, 187),
(16, 187),
(3, 188),
(6, 188),
(7, 188),
(3, 190),
(6, 190),
(16, 190),
(2, 197),
(3, 198),
(6, 198),
(7, 198),
(16, 198),
(17, 198),
(3, 199),
(6, 199),
(7, 199),
(16, 199),
(17, 199),
(3, 201),
(6, 201),
(7, 201),
(16, 201),
(17, 201),
(3, 202),
(6, 202),
(7, 202),
(16, 202),
(17, 202),
(3, 205),
(6, 205),
(7, 205),
(16, 205),
(17, 205),
(3, 206),
(6, 206),
(7, 206),
(16, 206),
(17, 206),
(2, 207),
(3, 208),
(6, 208),
(3, 209),
(6, 209),
(7, 209),
(3, 210),
(6, 210),
(7, 210),
(3, 217),
(6, 217),
(3, 226),
(6, 226),
(7, 226),
(16, 226),
(17, 226),
(3, 227),
(6, 227),
(7, 227),
(16, 227),
(17, 227),
(3, 228),
(6, 228),
(7, 228),
(16, 228),
(17, 228),
(3, 229),
(6, 229),
(16, 229),
(17, 229),
(3, 231),
(6, 231),
(7, 231),
(16, 231),
(17, 231),
(3, 232),
(6, 232),
(7, 232),
(16, 232),
(17, 232),
(2, 233),
(3, 234),
(6, 234),
(3, 239),
(6, 239),
(7, 239),
(3, 241),
(6, 241),
(7, 241),
(3, 242),
(6, 242),
(7, 242),
(3, 243),
(6, 243),
(7, 243),
(3, 244),
(6, 244),
(7, 244),
(3, 245),
(6, 245),
(7, 245),
(3, 246),
(6, 246),
(7, 246),
(3, 248),
(6, 248),
(7, 248),
(3, 249),
(6, 249),
(7, 249),
(3, 251),
(6, 251),
(2, 260),
(3, 262),
(6, 262),
(7, 262),
(3, 263),
(6, 263),
(7, 263),
(3, 264),
(6, 264),
(7, 264),
(3, 266),
(6, 266),
(7, 266),
(3, 267),
(6, 267),
(7, 267),
(13, 268),
(2, 269),
(3, 269),
(6, 270),
(2, 289),
(3, 291),
(6, 291),
(2, 300),
(3, 300),
(6, 300),
(2, 302),
(2, 312),
(2, 322),
(3, 337),
(16, 337),
(17, 337),
(3, 345),
(16, 345),
(17, 345),
(2, 356),
(11, 356),
(9, 376),
(2, 406),
(3, 406),
(16, 406),
(17, 406),
(2, 414),
(3, 421),
(4, 421),
(5, 421),
(16, 421),
(17, 421),
(3, 433),
(4, 433),
(16, 433),
(17, 433),
(5, 434),
(3, 438),
(4, 438),
(5, 438),
(16, 438),
(17, 438),
(3, 464),
(4, 464),
(16, 464),
(17, 464),
(2, 468),
(10, 468),
(2, 504),
(3, 504),
(6, 504),
(2, 505),
(2, 507),
(3, 507),
(2, 513),
(3, 514),
(6, 514),
(7, 514),
(16, 514),
(17, 514),
(3, 548),
(6, 548),
(7, 548),
(16, 548),
(17, 548),
(3, 549),
(6, 549),
(7, 549),
(16, 549),
(17, 549),
(3, 550),
(6, 550),
(7, 550),
(16, 550),
(17, 550),
(3, 551),
(6, 551),
(7, 551),
(16, 551),
(17, 551),
(2, 552),
(3, 569),
(6, 569),
(7, 569),
(16, 569),
(17, 569),
(3, 575),
(6, 575),
(16, 575),
(3, 578),
(6, 578),
(7, 578),
(16, 578),
(3, 582),
(6, 582),
(16, 582),
(3, 583),
(6, 583),
(16, 583),
(3, 597),
(6, 597),
(3, 604),
(6, 604),
(3, 605),
(6, 605),
(3, 619),
(6, 619),
(7, 619),
(16, 619),
(17, 619),
(3, 626),
(6, 626),
(16, 626),
(17, 626),
(3, 627),
(6, 627),
(16, 627),
(17, 627),
(3, 630),
(6, 630),
(16, 630),
(17, 630),
(3, 633),
(6, 633),
(7, 633),
(16, 633),
(17, 633),
(3, 637),
(6, 637),
(16, 637),
(17, 637),
(3, 638),
(6, 638),
(16, 638),
(17, 638),
(3, 641),
(6, 641),
(16, 641),
(17, 641),
(3, 644),
(6, 644),
(7, 644),
(16, 644),
(17, 644),
(3, 648),
(6, 648),
(16, 648),
(17, 648),
(3, 649),
(6, 649),
(16, 649),
(17, 649),
(3, 652),
(6, 652),
(16, 652),
(17, 652),
(3, 659),
(6, 659),
(16, 659),
(17, 659),
(3, 660),
(6, 660),
(16, 660),
(17, 660),
(3, 662),
(6, 662),
(16, 662),
(17, 662),
(3, 666),
(6, 666),
(7, 666),
(16, 666),
(17, 666),
(3, 670),
(6, 670),
(16, 670),
(17, 670),
(3, 671),
(6, 671),
(16, 671),
(17, 671),
(16, 672),
(3, 757),
(6, 757),
(3, 760),
(6, 760),
(7, 760),
(3, 764),
(6, 764),
(3, 765),
(6, 765),
(7, 776),
(3, 778),
(6, 778),
(7, 778),
(16, 778),
(17, 778),
(3, 779),
(6, 779),
(7, 779),
(16, 779),
(17, 779),
(3, 780),
(6, 780),
(7, 780),
(16, 780),
(17, 780),
(3, 782),
(6, 782),
(7, 782),
(16, 782),
(17, 782),
(3, 783),
(6, 783),
(7, 783),
(16, 783),
(17, 783),
(3, 791),
(6, 791),
(16, 791),
(17, 791),
(3, 794),
(6, 794),
(7, 794),
(16, 794),
(17, 794),
(3, 798),
(6, 798),
(16, 798),
(17, 798),
(3, 799),
(6, 799),
(16, 799),
(17, 799),
(3, 812),
(6, 812),
(7, 812),
(16, 812),
(17, 812),
(3, 823),
(6, 823),
(7, 823),
(16, 823),
(2, 839),
(3, 866),
(6, 866),
(7, 866),
(16, 866),
(17, 866),
(3, 868),
(6, 868),
(7, 868),
(16, 868),
(17, 868),
(3, 869),
(6, 869),
(7, 869),
(16, 869),
(17, 869),
(3, 870),
(6, 870),
(7, 870),
(16, 870),
(17, 870),
(3, 873),
(6, 873),
(7, 873),
(16, 873),
(17, 873),
(3, 879),
(6, 879),
(7, 879),
(16, 879),
(17, 879),
(3, 894),
(6, 901),
(7, 901),
(3, 919),
(4, 919),
(5, 919),
(16, 919),
(17, 919),
(3, 968),
(6, 968),
(7, 968),
(16, 968),
(17, 968),
(3, 975),
(6, 975),
(7, 976),
(7, 978),
(7, 979),
(3, 987),
(6, 987),
(7, 987),
(16, 987),
(17, 987),
(14, 990),
(3, 997),
(16, 997),
(17, 997),
(3, 1004),
(4, 1004),
(5, 1004),
(6, 1004),
(7, 1004),
(16, 1004),
(17, 1004),
(3, 1005),
(6, 1005),
(7, 1005),
(16, 1005),
(17, 1005),
(15, 1023),
(17, 1044),
(17, 1045),
(17, 1046),
(17, 1047),
(17, 1048),
(17, 1049),
(17, 1050),
(17, 1051),
(17, 1054),
(17, 1056),
(17, 1057),
(17, 1058),
(17, 1059),
(17, 1060),
(17, 1061),
(17, 1062),
(17, 1063),
(17, 1066),
(17, 1071),
(17, 1082),
(17, 1083),
(17, 1084),
(17, 1085),
(17, 1086),
(17, 1087),
(17, 1088),
(17, 1089),
(17, 1093),
(17, 1094),
(17, 1095),
(17, 1096),
(17, 1097),
(17, 1098),
(17, 1099),
(17, 1100),
(17, 1103),
(17, 1113),
(17, 1114),
(17, 1115),
(17, 1116),
(17, 1117),
(17, 1118),
(17, 1119),
(17, 1120),
(17, 1121),
(17, 1128),
(17, 1129),
(17, 1130),
(17, 1131),
(17, 1132),
(17, 1133),
(17, 1134),
(17, 1135),
(17, 1139),
(17, 1140),
(17, 1141),
(17, 1142),
(17, 1143),
(17, 1144),
(17, 1145),
(17, 1146),
(17, 1150),
(17, 1151),
(17, 1152),
(17, 1153),
(17, 1154),
(17, 1155),
(17, 1156),
(17, 1157),
(16, 1200),
(17, 1200),
(16, 1203),
(17, 1203),
(2, 1241),
(3, 1241);

-- --------------------------------------------------------

--
-- Table structure for table `cmstop_safe_domain`
--

CREATE TABLE IF NOT EXISTS `cmstop_safe_domain` (
  `domainid` int(10) NOT NULL AUTO_INCREMENT,
  `domain` varchar(500) NOT NULL,
  `hostname` char(255) NOT NULL,
  `ip` char(100) NOT NULL,
  `name` char(100) NOT NULL DEFAULT '未知',
  `conf` varchar(500) CHARACTER SET utf8 COLLATE utf8_estonian_ci NOT NULL,
  `directory` varchar(500) NOT NULL,
  `opendir` varchar(500) NOT NULL,
  `php_exec` char(1) NOT NULL,
  `php_suffix` char(10) NOT NULL,
  `writable` char(1) NOT NULL,
  `readable` char(1) NOT NULL,
  `ssi` char(1) NOT NULL,
  `level` tinyint(1) NOT NULL,
  `type` tinyint(1) NOT NULL,
  `detecttime` int(10) NOT NULL,
  PRIMARY KEY (`domainid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `cmstop_safe_trojan_feature`
--

CREATE TABLE IF NOT EXISTS `cmstop_safe_trojan_feature` (
  `featureid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` char(100) NOT NULL,
  `code` varchar(1000) NOT NULL,
  `level` tinyint(1) unsigned NOT NULL,
  `description` char(255) NOT NULL,
  `source` char(200) NOT NULL,
  `discover` char(30) NOT NULL,
  `entrytime` int(10) unsigned NOT NULL,
  `status` tinyint(1) unsigned NOT NULL,
  PRIMARY KEY (`featureid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `cmstop_safe_trojan_version`
--

CREATE TABLE IF NOT EXISTS `cmstop_safe_trojan_version` (
  `maxid` int(10) unsigned NOT NULL,
  `updatetime` int(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `cmstop_safe_verify_file`
--

CREATE TABLE IF NOT EXISTS `cmstop_safe_verify_file` (
  `fileid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `path` varchar(1024) NOT NULL,
  `path_md5` char(32) NOT NULL,
  `md5` char(32) NOT NULL,
  `modified` int(10) unsigned NOT NULL,
  `created` int(10) unsigned NOT NULL,
  PRIMARY KEY (`fileid`),
  UNIQUE KEY `path_md5` (`path_md5`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `cmstop_score`
--

CREATE TABLE IF NOT EXISTS `cmstop_score` (
  `scoreid` mediumint(8) NOT NULL AUTO_INCREMENT,
  `userid` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `contentid` mediumint(8) DEFAULT NULL,
  `score` tinyint(2) unsigned NOT NULL,
  `comment` varchar(255) DEFAULT NULL,
  `created` int(10) unsigned NOT NULL,
  `createdby` mediumint(8) unsigned NOT NULL,
  PRIMARY KEY (`scoreid`),
  KEY `createdby` (`createdby`,`created`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `cmstop_search`
--

CREATE TABLE IF NOT EXISTS `cmstop_search` (
  `contentid` mediumint(8) unsigned NOT NULL,
  `content` mediumtext,
  PRIMARY KEY (`contentid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `cmstop_search_counter`
--

CREATE TABLE IF NOT EXISTS `cmstop_search_counter` (
  `counter_id` int(11) NOT NULL,
  `max_doc_id` int(11) NOT NULL,
  PRIMARY KEY (`counter_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

--
-- Dumping data for table `cmstop_search_counter`
--

INSERT INTO `cmstop_search_counter` (`counter_id`, `max_doc_id`) VALUES
(1, 0);

-- --------------------------------------------------------

--
-- Table structure for table `cmstop_section`
--
CREATE TABLE IF NOT EXISTS `cmstop_section` (
  `sectionid` smallint(5) unsigned NOT NULL auto_increment,
  `pageid` smallint(5) unsigned NOT NULL,
  `type` enum('auto','hand','push','html','feed','json','rpc') NOT NULL default 'auto',
  `name` varchar(30) NOT NULL,
  `origdata` text,
  `data` longtext,
  `url` varchar(255) default NULL,
  `method` varchar(20) default NULL,
  `args` varchar(255) default NULL,
  `template` text,
  `output` varchar(30) default 'html',
  `width` smallint(3) unsigned default NULL,
  `rows` tinyint(3) unsigned NOT NULL default '0',
  `frequency` smallint(5) unsigned NOT NULL default '0',
  `check` tinyint(1) unsigned NOT NULL default '0',
  `fields` text,
  `nextupdate` int(10) unsigned default NULL,
  `published` int(10) unsigned default NULL,
  `locked` int(10) unsigned default NULL,
  `lockedby` mediumint(8) unsigned default NULL,
  `updated` int(10) unsigned default NULL,
  `updatedby` mediumint(8) unsigned default NULL,
  `created` int(10) unsigned NOT NULL,
  `createdby` mediumint(8) unsigned NOT NULL,
  `description` text,
  `status` tinyint(3) unsigned NOT NULL default '1',
  `list_enabled` tinyint(1) unsigned NOT NULL default '0',
  `list_template` varchar(100) default NULL,
  `list_pagesize` smallint(5) unsigned default NULL,
  `list_pages` smallint(5) unsigned NOT NULL default '10',
  PRIMARY KEY  (`sectionid`),
  KEY `pageid` (`pageid`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=259 ;

--
-- 转存表中的数据 `cmstop_section`
--

INSERT INTO `cmstop_section` (`sectionid`, `pageid`, `type`, `name`, `origdata`, `data`, `url`, `method`, `args`, `template`, `output`, `width`, `rows`, `frequency`, `check`, `fields`, `nextupdate`, `published`, `locked`, `lockedby`, `updated`, `updatedby`, `created`, `createdby`, `description`, `status`, `list_enabled`, `list_template`, `list_pagesize`, `list_pages`) VALUES
(1, 2, 'auto', '导航链接', '<!-- 导航 -->\n   <div class="nav-wrapper">\n     <div class="border-radius">\n           <div class="main">\n                <nav class="nav">\n                 <ul>\n                      <li><a href="" title="" class="index">首页</a></li>\n                     <li class="hr"></li>\n                      <li><a href="" title="">思拓专区</a></li>\n                     <li class="hr"></li>\n                      <li><a href="" title="">新闻</a></li>\n                       <li class="hr"></li>\n                      <li><a href="" title="">娱乐</a></li>\n                       <li class="hr"></li>\n                      <li><a href="" title="">汽车</a></li>\n                       <li class="hr"></li>\n                      <li><a href="" title="">房产</a></li>\n                       <li class="hr"></li>\n                      <li><a href="" title="">旅游</a></li>\n                       <li class="hr"></li>\n                      <li><a href="" title="">专题</a></li>\n                       <li class="hr"></li>\n                      <li><a href="" title="">手机版</a></li>\n                      <li class="hr"></li>\n                      <li><a href="" title="">专栏</a></li>\n                       <li class="hr"></li>\n                      <li><a href="" title="">报纸</a></li>\n                       <li class="hr"></li>\n                      <li><a href="" title="">杂志</a></li>\n                       <li class="hr"></li>\n                      <li><a href="" title="">访谈</a></li>\n                       <li class="hr"></li>\n                      <li><a href="" title="">调查</a></li>\n                       <li class="hr"></li>\n                      <li><a href="" title="">滚动</a></li>\n                   </ul>\n             </nav>\n            </div>\n            <div class="left"></div>\n          <div class="right"></div>\n     </div>\n    </div><!-- @end 导航 -->', '<!-- 顶部导航 -->\n<nav class="m-header-nav">\n    <ul class="m-header-nav-list">\n        <li><a href="{WWW_URL}" title="首页">首页</a></li>\n        <li><a href="{table(''category'', 1, ''url'')}" title="思拓">思拓</a></li>\n        <li><a href="{table(''category'', 2, ''url'')}" title="新闻">新闻</a></li>\n        <li><a href="{table(''category'', 3, ''url'')}" title="娱乐">娱乐</a></li>\n        <li><a href="{table(''category'', 4, ''url'')}" title="汽车">汽车</a></li>\n        <li><a href="{table(''category'', 5, ''url'')}" title="房产">房产</a></li>\n        <li><a href="{table(''category'', 6, ''url'')}" title="旅游">旅游</a></li>\n        <li><a href="http://photo.silkroad.news.cn/" title="图片">图片</a></li>\n        <li><a href="http://video.silkroad.news.cn/" title="视频">视频</a></li>\n        <li><a href="{WWW_URL}activity/" title="活动">活动</a></li>\n        <li><a href="http://special.silkroad.news.cn/" title="专题">专题</a></li>\n        <li><a href="{WWW_URL}paper/" title="报纸">报纸</a></li>\n        <li><a href="{WWW_URL}magazine/" title="杂志">杂志</a></li>\n        <li><a href="{WWW_URL}about/mobile.shtml" title="手机">手机</a></li>\n    </ul>\n</nav>', NULL, NULL, NULL, '', 'html', NULL, 0, 0, 0, NULL, 1426749345, 1426749445, 0, 0, 1426749445, 1, 1342496210, 1, '网站导航链接区块', 1, 0, NULL, NULL, 10),
(2, 2, 'auto', '网站底部', '    <!-- footer -->\n   <footer class="foot">\n     <div class="foot-links">\n          <a href="" title="">关于我们 </a>\n         <span href="" title="">| </span>\n          <a href="" title="">联系我们 </a>\n         <span href="" title="">| </span>\n          <a href="" title="">加入我们 </a>\n         <span href="" title="">| </span>\n          <a href="" title="">版权声明</a>\n          <span href="" title="">| </span>\n          <a href="" title="">手机访问</a>\n          <span href="" title="">| </span>\n          <a href="" title="">网站地图</a>\n          <span href="" title="">| </span>\n          <a href="" title="">留言反馈 </a>\n         <span href="" title="">| </span>\n          <a href="" title="">我要投稿</a>\n      </div>\n        <p>客服电话：<span>010-62961030</span> | <span>010-82145002</span> | 客服QQ：10000 100304 <span>Email:cmstop@cmstop.com</span></p>\n        <p><a href="" title="">北京思拓合众科技有限公司</a> 版权所有：Copyright @ cmstop.com All Rights Reserved. 京ICP备09082107号 </p>\n  </footer>\n <!-- end footer -->', '<!-- 底部 -->\n    <footer class="m-footer">\n        <nav class="m-footer-nav">\n            <a href="{WWW_URL}" target="_blank">首页</a>\n            <em>|</em>\n            <a href="{WWW_URL}about/" target="_blank">关于我们</a>\n            <em>|</em>\n            <a href="{WWW_URL}about/copyright.shtml" target="_blank">免责声明</a>\n            <em>|</em>\n            <a href="{WWW_URL}about/jobs.shtml" target="_blank">工作机会</a>\n            <em>|</em>\n            <a href="{WWW_URL}about/contact.shtml" target="_blank">联系我们</a>\n            <em>|</em>\n            <a href="http://www.cmstop.com/help/" target="_blank">帮助中心</a>\n            <em>|</em>\n            <a href="{APP_URL}{url(''guestbook'')}" target="_blank">留言本</a>\n        </nav>\n        <div class="copyright"><a href="http://www.cmstop.com/" class="" title="北京思拓合众科技有限公司">北京思拓合众科技有限公司</a><span>版权所有：Copyright © cmstop.com All Rights Reserved.</span></div>\n    </footer>', NULL, NULL, NULL, '', 'html', NULL, 0, 0, 0, NULL, 1426581338, 1426581438, 0, 0, 1426581438, 1, 1342497678, 1, '网站底部区块', 1, 0, NULL, NULL, 10),
(4, 1, 'hand', '头条', '[null,null,null,null]', '[[{\"contentid\":false,\"icon\":\"blank\",\"iconsrc\":\"\",\"title\":\"\\u5f20\\u53ec\\u5fe0\\uff1a\\u89e3\\u51b3\\u5357\\u6d77\\u4e89\\u7aef\\u65f6\\u64e6\\u67aa\\u8d70\\u706b\\u5f88\\u6b63\\u5e38\",\"color\":\"\",\"url\":\"http:\\/\\/www.silkroad.news.cn\\/2012\\/1009\\/88.shtml\",\"subtitle\":\"\\u5f20\\u53ec\\u5fe0\\uff1a\\u89e3\",\"suburl\":\"\",\"thumb\":\"2012\\/1009\\/1349776343282.jpg\",\"description\":\"\\u8d8a\\u535719\\u65e5\\u8fde\\u7eed\\u7b2c\\u4e09\\u4e2a\\u5468\\u672b\\u53d1\\u751f\\u53cd\\u534e\\u6297\\u8bae\\uff0c\\u4e00\\u4e9b\\u793a\\u5a01\\u8005\\u558a\\u51fa\\u201c\\u6253\\u5012\\u4e2d\\u56fd\\u201d\\u4e4b\\u7c7b\\u7684\\u6fc0\\u70c8\\u53e3\\u53f7\\u3002\\u5728\\u5bf9\\u793a\\u5a01\\u7ba1\\u63a7\\u4e25\\u5bc6\\u7684\\u8d8a\\u5357\\uff0c\\u8fd9\\u79cd\\u8fde\\u7eed\\u516c\\u5f00\\u6297\\u8bae\\u88ab\\u8ba4\\u4e3a\\u975e\\u5e38\\u7f55\\u89c1\\u3002\\u4e0e\\u4e2d\\u56fd \\u5173\\u7cfb\\u7d27\\u5f20\\u4e4b\\u9645\\uff0c\\u8d8a\\u5357\\u53c8\\u5411\\u7f8e\\u56fd\\u9760\\u4e86\\u4e00\\u6b65\\uff0c\\u4e24\\u56fd17\\u65e5\\u53d1\\u8868\\u5171\\u540c\\u58f0\\u660e\\uff0c\\u547c\\u5401\\u5357\\u6d77\\u201c\\u822a\\u884c\\u81ea\\u7531\\u201d\\uff0c\\u5e76\\u63a2\\u8ba8\\u628a\\u53cc\\u65b9\\u5173\\u7cfb\\u63d0\\u5347\\u5230\\u201c\\u6218\\u7565\\u7ea7\\u201d\\u3002\",\"time\":1349776320},{\"contentid\":138,\"icon\":\"blank\",\"iconsrc\":\"\",\"title\":\"\\u65c5\\u6cd5\\u5e08\\u8425\\u5730\\u6d77\\u5c9bvs\",\"color\":\"\",\"url\":\"http:\\/\\/www.silkroad.news.cn\\/2015\\/0312\\/138.shtml\",\"subtitle\":\"\",\"suburl\":\"\",\"thumb\":\"http:\\/\\/upload.silkroad.news.cn\\/2015\\/0312\\/1426143336111.jpeg\",\"description\":\"\",\"time\":1426143350}]]', null, null, null, '<!--{loop $data $k $r}-->\n<!--{if $k == 0}-->\n<h1 class=\"h1\">\n<!--{loop $r $i $c}-->\n{if $i > 0}&nbsp;&nbsp;{/if}<a href=\"{$c[url]}\" target=\"_blank\" title=\"{$c[title]}\">{$c[title]}</a>\n<!--{/loop}-->\n</h1>\n<!--{/if}-->\n<!--{/loop}-->\n', 'html', null, '1', '0', '0', '{\"system_fields\":{\"contentid\":{\"checked\":\"1\",\"func\":\"intval\"},\"title\":{\"checked\":\"1\",\"required\":\"1\",\"min_length\":\"\",\"max_length\":\"\"},\"url\":{\"checked\":\"1\"},\"color\":{\"checked\":\"1\"},\"icon\":{\"checked\":\"1\"},\"iconsrc\":{\"checked\":\"1\"},\"subtitle\":{\"checked\":\"1\",\"min_length\":\"\",\"max_length\":\"\"},\"suburl\":{\"checked\":\"1\"},\"thumb\":{\"checked\":\"1\",\"width\":\"\",\"height\":\"\"},\"description\":{\"checked\":\"1\",\"min_length\":\"\",\"max_length\":\"\"},\"time\":{\"checked\":\"1\",\"func\":\"section_fields_strtotime\"}},\"custom_fields\":{\"text\":[],\"name\":[]}}', 1427160745, 1427160745, 0, 0, 1426745023, 1, 1342501900, 1, '此区块为一行多标题区块', 1, 1, 'section/list.html', 0, 10),
(5, 1, 'hand', '推荐列表', NULL, '[[],[],[],[],[],[],[],[],[],[],[],[],[],[],[]]', NULL, NULL, NULL, '<ul class="list list-point">\n    <!--{loop $data $k $r}-->\n    <!--{if $k==5 || $k==10}--><li class="item hr"><!--{else}--><li class="item"><!--{/if}--><em class="ico"></em><!--{loop $r $i $c}--><a href="{$c[url]}" title="{$c[title]}" class="title">{$c[title]}</a><!--{/loop}--></li>\n    <!--{/loop}-->\n</ul>', 'html', NULL, 15, 0, 0, NULL, 1423123360, 1423123360, 0, 0, 1423194286, 1, 1342502123, 1, '', 0, 0, NULL, NULL, 10),
(6, 1, 'hand', '幻灯右侧列表', '[null,null,null,null,null,null]', '[[{\"contentid\":153,\"icon\":\"\",\"iconsrc\":\"\",\"title\":\"\\u4e60\\u8fd1\\u5e73\\u5c06\\u51fa\\u5e2d\\u535a\\u9ccc\\u4e9a\\u6d32\\u8bba\\u575b \\u8bae\\u9898\\u6d89\\u53ca\\u516d\\u5927\\u9886\\u57df\",\"color\":\"\",\"url\":\"http:\\/\\/www.silkroad.news.cn\\/2015\\/0318\\/153.shtml\",\"subtitle\":\"\",\"suburl\":\"\",\"thumb\":\"\",\"description\":\"\",\"time\":1426691358}],[{\"contentid\":false,\"icon\":\"blank\",\"iconsrc\":\"\",\"title\":\"\\u4e2d\\u56fd\\u4e2d\\u5b66\\u751f\\u9996\\u90e8\\u60c5\\u611f\\u7cfb\\u5217\\u7247\\u300a\\u804a\\u5929\\u2161\\uff1a\\u6210\\u957f\\u5b63\\u300b\",\"color\":\"\",\"url\":\"http:\\/\\/www.silkroad.news.cn\\/2012\\/1009\\/61.shtml\",\"subtitle\":\"\\u4e2d\\u56fd\\u4e2d\\u5b66\\u751f\",\"suburl\":\"\",\"thumb\":\"2012\\/1009\\/1349755867505.jpg\",\"description\":\"\\u7531 \\u6df1\\u5733\\u5e02\\u7b2c\\u4e8c\\u804c\\u4e1a\\u6280\\u672f\\u5b66\\u6821 \\u51fa\\u54c1\\uff0c\\u552f\\u8bb0\\u5fc6\\u5f71\\u89c6\\u5de5\\u574a \\u6444\\u5236\\u7684\\u4e2d\\u56fd\\u4e2d\\u5b66\\u751f\\u9996\\u90e8\\u60c5\\u611f\\u7cfb\\u5217\\u7247\\u300a\\u804a\\u5929\\u2161\\uff1a\\u6210\\u957f\\u5b63\\u300b\\u3002\",\"time\":1349755680}],[{\"contentid\":false,\"icon\":\"blank\",\"iconsrc\":\"\",\"title\":\"\\u5317\\u4eac\\u5730\\u94c1\\u4fe1\\u606f\\u5c4f\\u73b0\\u201c\\u738b\\u9e4f\\u4f60\\u59b9\\u201d\",\"color\":\"\",\"url\":\"http:\\/\\/www.silkroad.news.cn\\/2012\\/1009\\/60.shtml\",\"subtitle\":\"\\u5317\\u4eac\\u5730\\u94c1\\u4fe1\",\"suburl\":\"\",\"thumb\":\"2012\\/1009\\/1349755393811.jpg\",\"description\":\"\\u5317\\u4eac\\u5730\\u94c1\\u4fe1\\u606f\\u5c4f\\u73b0\\u201c\\u738b\\u9e4f\\u4f60\\u59b9\\u201d \\u56de\\u5e94\\uff1a\\u7cfb\\u7edf\\u8c03\\u8bd5\",\"time\":1349755380},{\"contentid\":45,\"icon\":\"\",\"iconsrc\":\"\",\"title\":\"2012\\u7f8e\\u56fd\\u603b\\u7edf\\u5927\\u9009\",\"color\":\"\",\"url\":\"http:\\/\\/special.silkroad.news.cn\\/121008-3\",\"subtitle\":\"\",\"suburl\":\"\",\"thumb\":\"\",\"description\":\"\",\"time\":1350442140}],[{\"title\":\"\\u6cb3\\u5317\\u4fdd\\u5b9a\\u5c45\\u6c11\\u697c\\u7206\\u70b8\\u6848\\u5acc\\u7591\\u4eba\\u5728\\u5b89\\u5fbd\\u4e34\\u6cc9\\u53bf\\u6295\\u6848\",\"url\":\"http:\\/\\/www.silkroad.news.cn\\/2012\\/1008\\/2.shtml\",\"color\":\"\",\"subtitle\":\"\\u6cb3\\u5317\\u4fdd\\u5b9a\\u5c45\",\"suburl\":\"\",\"description\":\"\\u6cb3\\u5317\\u4fdd\\u5b9a\\u5c45\\u6c11\\u697c\\u7206\\u70b8\\u6848\\u5acc\\u7591\\u4eba\\u5728\\u5b89\\u5fbd\\u4e34\\u6cc9\\u53bf\\u6295\\u6848\",\"thumb\":\"2012\\/1008\\/1349698336780.jpg\",\"time\":\"1349683260\"}],[{\"title\":\" \\u6155\\u5bb9\\u96ea\\u6751\\u8c08\\u65b0\\u4e66\\u300a\\u4e2d\\u56fd\\uff0c\\u5c11\\u4e86\\u4e00\\u5473\\u836f\\u300b\",\"url\":\"http:\\/\\/www.silkroad.news.cn\\/2012\\/0926\\/81.shtml\",\"color\":\"\",\"subtitle\":\" \\u6155\\u5bb9\\u96ea\\u6751\",\"suburl\":\"\",\"description\":\"2009\\u5e74\\u5e95\\uff0c\\u6155\\u5bb9\\u96ea\\u6751\\u5367\\u5e95\\u8fdb\\u5165\\u4f20\\u9500\\u96c6\\u56e2\\uff0c\\u4e0a\\u6f14\\u4e00\\u51fa\\u771f\\u5b9e\\u7248\\u201c\\u65e0\\u95f4\\u9053\\u201d\\u3002\\u4ed6\\u6839\\u636e\\u8fd9\\u4e00\\u4eb2\\u8eab\\u7ecf\\u5386\\uff0c\\u5199\\u4f5c\\u63ed\\u9732\\u4f20\\u9500\\u7684\\u7eaa\\u5b9e\\u4f5c\\u54c1\\u300a\\u4e2d\\u56fd\\uff0c\\u5c11\\u4e86\\u4e00\\u5473\\u836f\\u300b\\u3002\\u5728\\u8fd9\\u672c\\u4e66\\u7684\\u5c01\\u9762\\u4e0a \\uff0c\\u8fd9\\u6837\\u5199\\u9053\\u201c\\u611a\\u8822\\u4e0d\\u662f\\u5929\\u751f\\u7684\\uff0c\\u800c\\u662f\\u4eba\\u5de5\\u5236\\u9020\\u51fa\\u6765\\u7684\\u3002\\u6211\\u6709\\u4e00\\u4e2a\\u5e0c\\u671b\\uff1a\\u8ba9\\u5e38\\u8bc6\\u5728\\u9633\\u5149\\u4e0b\\u884c\\u8d70\\uff0c\\u8ba9\\u8d2b\\u5f31\\u8005\\u4ece\\u82e6\\u96be\\u4e2d\\u8131\\u8eab\\uff0c\\u8ba9\\u90aa\\u6076\\u8fdc\\u79bb\\u6bcf\\u4e00\\u9897\\u5584\\u826f\\u7684\\u5fc3\\u3002\\u201d\\u60f3\\u77e5\\u9053\\u6155\\u5bb9\\u96ea\\u6751\\u7684\\u4e66\\u4e2d\\u7a76\\u7adf\\u5bf9\\u4f20\\u9500\\u6709\\u7740\\u600e\\u4e48\\u6837\\u7684\\u63cf\\u8ff0\\u5417\\uff1f\\u60f3\\u77e5\\u9053\\u5728\\u77ed\\u77ed\\u768423\\u5929\\u4e4b\\u4e2d\\uff0c\\u6155\\u5bb9\\u96ea\\u6751\\u5230\\u5e95\\u7ecf\\u5386\\u4e86\\u4ec0\\u4e48\\u5417\\uff1f\",\"thumb\":\"2012\\/0926\\/1348625253763.png\",\"time\":\"1348625288\"}],[{\"contentid\":152,\"icon\":\"\",\"iconsrc\":\"\",\"title\":\"\\u5fb7\\u56fd\\u7206\\u53d1\\u53cd\\u8d44\\u672c\\u4e3b\\u4e49\\u6e38\\u884c \\u793a\\u5a01\\u8005\\u70e7\\u6bc1\\u8b66\\u8f66\",\"color\":\"\",\"url\":\"http:\\/\\/www.silkroad.news.cn\\/2015\\/0318\\/152.shtml\",\"subtitle\":\"\",\"suburl\":\"\",\"thumb\":\"http:\\/\\/upload.silkroad.news.cn\\/2015\\/0318\\/1426691293712.jpg\",\"description\":\"\",\"time\":1426691306}]]', null, null, null, '<div class=\"fl-r news\">\n    <!--{loop $data $k $r}-->\n    <!--{if $k < 2}-->\n    <!--{if $k > 0}--><div class=\"hr10\"></div><!--{/if}-->\n    <!--{loop $r $i $c}-->\n    <div class=\"area\">\n        <h2 class=\"h2\"><a href=\"{$c[url]}\" target=\"_blank\" title=\"{$c[title]}\">{$c[title]}</a></h2>\n        <p>{str_natcut($c[description], 50, \'...\')}</p>\n        <div class=\"hr10\"></div>\n    </div>\n    <!--{/loop}-->\n    <!--{/if}-->\n    <!--{/loop}-->\n    <div class=\"hr10\"></div>\n    <ul class=\"list\">\n        <!--{loop $data $k $r}-->\n        <!--{if $k > 1}-->\n        <li>\n            <!--{loop $r $i $c}-->\n            <a href=\"{$c[url]}\" target=\"_blank\" title=\"{$c[title]}\">{$c[title]}</a>\n            <!--{/loop}-->\n        </li>\n        <!--{/if}-->\n        <!--{/loop}-->\n    </ul>\n</div>', 'html', null, '6', '0', '0', '{\"system_fields\":{\"contentid\":{\"checked\":\"1\",\"func\":\"intval\"},\"title\":{\"checked\":\"1\",\"required\":\"1\",\"min_length\":\"\",\"max_length\":\"\"},\"url\":{\"checked\":\"1\"},\"color\":{\"checked\":\"1\"},\"icon\":{\"checked\":\"1\"},\"iconsrc\":{\"checked\":\"1\"},\"subtitle\":{\"checked\":\"1\",\"min_length\":\"\",\"max_length\":\"\"},\"suburl\":{\"checked\":\"1\"},\"thumb\":{\"checked\":\"1\",\"width\":\"\",\"height\":\"\"},\"description\":{\"checked\":\"1\",\"min_length\":\"\",\"max_length\":\"\"},\"time\":{\"checked\":\"1\",\"func\":\"section_fields_strtotime\"}},\"custom_fields\":{\"text\":[],\"name\":[]}}', 1427176190, 1427176190, 0, 0, 1426693243, 1, 1342503498, 1, '前两行为带简介的文章，后四条为纯标题，后四条可以使用一行多标题', 1, 0, 'section/list.html', 0, 10),
(7, 1, 'push', '特色模型推荐', '[]', '[]', NULL, NULL, NULL, '<ul class="list list-point">\n    <!--{loop $data $k $r}-->\n    <?php\n    if($r[contentid]){\n        $modelid=intval(table(''content'',$r[contentid],''modelid''));\n        if($modelid){\n            $modelname=table(''model'',$modelid,''name'');\n        }else{\n            $modelname=''未知'';\n        }\n    }else{\n        $modelname=''未知'';\n    }\n    ?>\n    <li class="item"><a class="title">[{$modelname}]</a> <a href="{$r[url]}" target="_blank" class="title">{$r[title]}</a></li>\n    <!--{/loop}-->\n</ul>', 'html', NULL, 5, 0, 0, NULL, 1423123360, 1423123360, 0, 0, 1423194434, 1, 1342503862, 1, '特殊模型的数据推荐', 0, 0, 'section/list.html', 0, 10),
(8, 2, 'auto', '新闻排行', '<div class="m-head dotted-head">\n    <ul class="tab-head" id="tabmenu">\n        <li><a rel="tab" href="{APP_URL}rank.php" title="">浏览</a></li><!-- 当前样式为class="tabactive" -->\n        <li><a rel="tab" href="{APP_URL}rank.php" title="">评论</a></li>\n    </ul>\n    <em class="ico"></em>\n    <div class="title"><a href="{APP_URL}rank.php" title="" class="words">新闻排行</a></div>\n</div>\n<div class="m-main" id="tabcontent">\n    <div title="tab">\n        <ul class="list">\n            <!--{content published="30" orderby="`pv` DESC" size="10"}-->\n            <li class="item"><em class="ico{if $i < 3} n{($i+1)}{/if}">{($i+1)}</em><a href="{$r[url]}" title="{$r[title]}" class="title"{if $r[color]} style="color:{$r[color]}"{/if}>{str_natcut($r[title], 18, '''')}</a></li>\n            <!--{/content}-->\n        </ul>\n    </div>\n    <div title="tab">\n        <ul class="list">\n            <!--{content published="30" orderby="`comments` DESC" size="10"}-->\n            <li class="item"><em class="ico{if $i < 3} n{($i+1)}{/if}">{($i+1)}</em><a href="{$r[url]}" title="{$r[title]}" class="title"{if $r[color]} style="color:{$r[color]}"{/if}>{str_natcut($r[title], 18, '''')}</a></li>\n            <!--{/content}-->\n        </ul>\n    </div>\n</div>', '<div class="m-head dotted-head">\n    <ul class="tab-head" id="tabmenu">\n        <li><a rel="tab" href="{APP_URL}rank.php" target="_blank" title="">浏览</a></li><!-- 当前样式为class="tabactive" -->\n        <li><a rel="tab" href="{APP_URL}rank.php" target="_blank" title="">评论</a></li>\n    </ul>\n    <em class="ico"></em>\n    <div class="title"><a href="{APP_URL}rank.php" target="_blank" title="" class="words">新闻排行</a></div>\n</div>\n<div class="m-main" id="tabcontent">\n    <div title="tab">\n        <ul class="list">\n            <!--{content published="30" orderby="`pv` DESC" size="10"}-->\n            <li class="item"><em class="ico{if $i <= 3} n{$i}{/if}">{$i}</em><a href="{$r[url]}" target="_blank" title="{$r[title]}" class="title"{if $r[color]} style="color:{$r[color]}"{/if}>{str_natcut($r[title], 21, '''')}</a></li>\n            <!--{/content}-->\n        </ul>\n    </div>\n    <div title="tab">\n        <ul class="list">\n            <!--{content published="30" orderby="`comments` DESC" size="10"}-->\n            <li class="item"><em class="ico{if $i <= 3} n{$i}{/if}">{$i}</em><a href="{$r[url]}" target="_blank" title="{$r[title]}" class="title"{if $r[color]} style="color:{$r[color]}"{/if}>{str_natcut($r[title], 21, '''')}</a></li>\n            <!--{/content}-->\n        </ul>\n    </div>\n</div>', NULL, NULL, NULL, '', 'html', NULL, 0, 300, 0, NULL, 1426759787, 1426060661, 0, 0, 1426759487, 1, 1342504977, 1, 'tab 切换，各 10 条，标题长度 21 个汉字', 1, 0, NULL, NULL, 10),
(9, 5, 'auto', '顶通 1000 x 80', '', '<script type="text/javascript" id="adm-71">\n(function() {\n   window.ADMBlocks = window.ADMBlocks || [];\n   ADMBlocks.push({\n       id : ''71'',  // 广告位id\n       width : ''1000'',  // 宽\n       height : ''80'',  // 高\n       type : ''1''  // 类型\n   });\n   var h=document.getElementsByTagName(''head'')[0], s=document.createElement(''script'');\n   s.async=true; s.src=''http://ad.cmstop.cn/js/show.js'';\n   h && h.insertBefore(s,h.firstChild)\n})();\n</script>\n', NULL, NULL, NULL, '', 'html', NULL, 0, 300, 0, NULL, 1426747332, 1426747032, 0, 0, 1426747016, 1, 1342505894, 1, '规格 320 x 240', 1, 0, NULL, NULL, 10),
(10, 6, 'auto', '视频内容页头部广告', '', '<script type="text/javascript" id="adm-83">\n(function() {\n   window.ADMBlocks = window.ADMBlocks || [];\n   ADMBlocks.push({\n       id : ''83'',  // 广告位id\n       width : ''1000'',  // 宽\n       height : ''80'',  // 高\n       type : ''1''  // 类型\n   });\n   var h=document.getElementsByTagName(''head'')[0], s=document.createElement(''script'');\n   s.async=true; s.src=''http://ad.cmstop.cn/js/show.js'';\n   h && h.insertBefore(s,h.firstChild)\n})();\n</script>\n', NULL, NULL, NULL, '', 'html', NULL, 0, 0, 0, NULL, 1426731198, 1426731197, 0, 0, 1426731084, 1, 1342509810, 1, '', 1, 0, NULL, NULL, 10),
(11, 2, 'push', '推荐图片', '[]', '[{"contentid":5,"title":"\\u5317\\u4eac\\u9ad8\\u901f\\u5783\\u573e8\\u5929\\u8fbe\\u767e\\u5428 \\u5783\\u573e\\u591a\\u4e3a\\u5e9f\\u7eb8\\u77ff\\u6cc9\\u6c34\\u74f6","url":"http:\\/\\/www.silkroad.news.cn\\/2012\\/1010\\/5.shtml","color":"","subtitle":"\\u5317\\u4eac\\u9ad8\\u901f\\u5783","suburl":"","description":"\\u5317\\u4eac\\u9ad8\\u901f\\u5783\\u573e8\\u5929\\u8fbe\\u767e\\u5428 \\u5783\\u573e\\u591a\\u4e3a\\u5e9f\\u7eb8\\u77ff\\u6cc9\\u6c34\\u74f6","thumb":"2012\\/1008\\/1349698511700.jpg","time":"1349850060"},{"contentid":88,"title":"\\u5f20\\u53ec\\u5fe0\\uff1a\\u89e3\\u51b3\\u5357\\u6d77\\u4e89\\u7aef\\u65f6\\u64e6\\u67aa\\u8d70\\u706b\\u5f88\\u6b63\\u5e38","url":"http:\\/\\/www.silkroad.news.cn\\/2012\\/1009\\/88.shtml","color":"","subtitle":"\\u5f20\\u53ec\\u5fe0\\uff1a\\u89e3","suburl":"","description":"\\u8d8a\\u535719\\u65e5\\u8fde\\u7eed\\u7b2c\\u4e09\\u4e2a\\u5468\\u672b\\u53d1\\u751f\\u53cd\\u534e\\u6297\\u8bae\\uff0c\\u4e00\\u4e9b\\u793a\\u5a01\\u8005\\u558a\\u51fa\\u201c\\u6253\\u5012\\u4e2d\\u56fd\\u201d\\u4e4b\\u7c7b\\u7684\\u6fc0\\u70c8\\u53e3\\u53f7\\u3002\\u5728\\u5bf9\\u793a\\u5a01\\u7ba1\\u63a7\\u4e25\\u5bc6\\u7684\\u8d8a\\u5357\\uff0c\\u8fd9\\u79cd\\u8fde\\u7eed\\u516c\\u5f00\\u6297\\u8bae\\u88ab\\u8ba4\\u4e3a\\u975e\\u5e38\\u7f55\\u89c1\\u3002\\u4e0e\\u4e2d\\u56fd \\u5173\\u7cfb\\u7d27\\u5f20\\u4e4b\\u9645\\uff0c\\u8d8a\\u5357\\u53c8\\u5411\\u7f8e\\u56fd\\u9760\\u4e86\\u4e00\\u6b65\\uff0c\\u4e24\\u56fd17\\u65e5\\u53d1\\u8868\\u5171\\u540c\\u58f0\\u660e\\uff0c\\u547c\\u5401\\u5357\\u6d77\\u201c\\u822a\\u884c\\u81ea\\u7531\\u201d\\uff0c\\u5e76\\u63a2\\u8ba8\\u628a\\u53cc\\u65b9\\u5173\\u7cfb\\u63d0\\u5347\\u5230\\u201c\\u6218\\u7565\\u7ea7\\u201d\\u3002","thumb":"2012\\/1009\\/1349776343282.jpg","time":"1349776320"},{"contentid":37,"title":"\\u201c\\u56fd\\u5e86\\u4e00\\u59d0\\u201d\\u8303\\u51b0\\u51b0\\u534e\\u670d\\u4e09\\u8fde\\u51fb \\u5c3d\\u5c55\\u6cd5\\u5f0f\\u4f18\\u96c5","url":"http:\\/\\/www.silkroad.news.cn\\/2012\\/1008\\/37.shtml","color":"","subtitle":"\\u201c\\u56fd\\u5e86\\u4e00\\u59d0","suburl":"","description":"\\u201c\\u56fd\\u5e86\\u4e00\\u59d0\\u201d\\u8303\\u51b0\\u51b0\\u534e\\u670d\\u4e09\\u8fde\\u51fb \\u5c3d\\u5c55\\u6cd5\\u5f0f\\u4f18\\u96c5","thumb":"2012\\/1008\\/1349698412285.jpg","time":"1349681880"},{"contentid":81,"title":" \\u6155\\u5bb9\\u96ea\\u6751\\u8c08\\u65b0\\u4e66\\u300a\\u4e2d\\u56fd\\uff0c\\u5c11\\u4e86\\u4e00\\u5473\\u836f\\u300b","url":"http:\\/\\/www.silkroad.news.cn\\/2012\\/0926\\/81.shtml","color":"","subtitle":" \\u6155\\u5bb9\\u96ea\\u6751","suburl":"","description":"2009\\u5e74\\u5e95\\uff0c\\u6155\\u5bb9\\u96ea\\u6751\\u5367\\u5e95\\u8fdb\\u5165\\u4f20\\u9500\\u96c6\\u56e2\\uff0c\\u4e0a\\u6f14\\u4e00\\u51fa\\u771f\\u5b9e\\u7248\\u201c\\u65e0\\u95f4\\u9053\\u201d\\u3002\\u4ed6\\u6839\\u636e\\u8fd9\\u4e00\\u4eb2\\u8eab\\u7ecf\\u5386\\uff0c\\u5199\\u4f5c\\u63ed\\u9732\\u4f20\\u9500\\u7684\\u7eaa\\u5b9e\\u4f5c\\u54c1\\u300a\\u4e2d\\u56fd\\uff0c\\u5c11\\u4e86\\u4e00\\u5473\\u836f\\u300b\\u3002\\u5728\\u8fd9\\u672c\\u4e66\\u7684\\u5c01\\u9762\\u4e0a \\uff0c\\u8fd9\\u6837\\u5199\\u9053\\u201c\\u611a\\u8822\\u4e0d\\u662f\\u5929\\u751f\\u7684\\uff0c\\u800c\\u662f\\u4eba\\u5de5\\u5236\\u9020\\u51fa\\u6765\\u7684\\u3002\\u6211\\u6709\\u4e00\\u4e2a\\u5e0c\\u671b\\uff1a\\u8ba9\\u5e38\\u8bc6\\u5728\\u9633\\u5149\\u4e0b\\u884c\\u8d70\\uff0c\\u8ba9\\u8d2b\\u5f31\\u8005\\u4ece\\u82e6\\u96be\\u4e2d\\u8131\\u8eab\\uff0c\\u8ba9\\u90aa\\u6076\\u8fdc\\u79bb\\u6bcf\\u4e00\\u9897\\u5584\\u826f\\u7684\\u5fc3\\u3002\\u201d\\u60f3\\u77e5\\u9053\\u6155\\u5bb9\\u96ea\\u6751\\u7684\\u4e66\\u4e2d\\u7a76\\u7adf\\u5bf9\\u4f20\\u9500\\u6709\\u7740\\u600e\\u4e48\\u6837\\u7684\\u63cf\\u8ff0\\u5417\\uff1f\\u60f3\\u77e5\\u9053\\u5728\\u77ed\\u77ed\\u768423\\u5929\\u4e4b\\u4e2d\\uff0c\\u6155\\u5bb9\\u96ea\\u6751\\u5230\\u5e95\\u7ecf\\u5386\\u4e86\\u4ec0\\u4e48\\u5417\\uff1f","thumb":"2012\\/0926\\/1348625253763.png","time":"1348625288"}]', NULL, NULL, NULL, '<div class="m-title-a">\n    <h2 class="m-title-h2"><a href="{table(''page'', 4, ''url'')}" target="_blank">推荐图片</a></h2>\n</div>\n<div class="hr20"></div>\n<div class="commend-picture-inner">\n    <ul class="m-imagetitle">\n        <!--{loop $data $k $r}-->\n            <!--{if $k%2 == 0}-->\n            <li class="item odd">\n            <!--{else}-->\n            <li class="item">\n            <!--{/if}-->\n            <a href="{$r[url]}" target="_blank" title="{$r[title]}" class="thumbbox"><img src="{thumb($r[thumb], 140, 105, 1, null, 1)}" alt="" width="140" height="105"></a>\n            <a class="title" href="{$r[url]}" target="_blank" title="{$r[title]}"{if $r[color]} style="color:{$r[color]}"{/if}>{str_natcut($r[title], 22, '''')}</a>\n            </li>\n        <!--{/loop}-->\n    </ul>\n</div>', 'html', NULL, 4, 0, 0, '{"system_fields":{"contentid":{"checked":"1","func":"intval"},"title":{"checked":"1","required":"1","min_length":"","max_length":""},"url":{"checked":"1"},"color":{"checked":"1"},"icon":{"checked":"1"},"iconsrc":{"checked":"1"},"subtitle":{"checked":"1","min_length":"","max_length":""},"suburl":{"checked":"1"},"thumb":{"checked":"1","width":"","height":""},"description":{"checked":"1","min_length":"","max_length":""},"time":{"checked":"1","func":"section_fields_strtotime"}},"custom_fields":{"text":[],"name":[]}}', 1426060662, 1426060662, 0, 0, 1426060649, 1, 1342510513, 1, '图片 + 标题，4 条，标题长度 15 个汉字，图片规格 120 x 90', 1, 0, 'section/list.html', 0, 10),
(13, 7, 'auto', '顶部广告', '', '<script type="text/javascript" id="adm-72">\n(function() {\n   window.ADMBlocks = window.ADMBlocks || [];\n   ADMBlocks.push({\n       id : ''72'',  // 广告位id\n       width : ''1000'',  // 宽\n       height : ''100'',  // 高\n       type : ''1''  // 类型\n   });\n   var h=document.getElementsByTagName(''head'')[0], s=document.createElement(''script'');\n   s.async=true; s.src=''http://ad.cmstop.cn/js/show.js'';\n   h && h.insertBefore(s,h.firstChild)\n})();\n</script>\n', NULL, NULL, NULL, '', 'html', NULL, 0, 0, 0, NULL, 1426731202, 1426731202, 0, 0, 1426731112, 1, 1342513142, 1, '211*105', 1, 0, NULL, NULL, 10),
(14, 2, 'push', '推荐视频', '[]', '[{"contentid":65,"title":"\\u6c14\\u8d28\\u5973\\u795e\\u5b87\\u8f69\\u7ffb\\u5531\\u300a\\u4e0d\\u518d\\u8ba9\\u4f60\\u5b64\\u5355\\u300b","url":"http:\\/\\/www.silkroad.news.cn\\/2012\\/1009\\/65.shtml","color":"","subtitle":"\\u6c14\\u8d28\\u5973\\u795e\\u5b87","suburl":"","description":"\\u6f14\\u5531:\\u5b87\\u8f69\\n\\u526a\\u8f91&\\u8c03\\u8272:\\u5730\\u4e3b_L\\n\\u5b57\\u5e55:\\u5730\\u4e3b_L\\n\\u97f3\\u4e50\\u652f\\u6301:@\\u590d\\u4e50\\u73ed","thumb":"2012\\/1009\\/1349757234658.jpg","time":"1349757180"},{"contentid":61,"title":"\\u4e2d\\u56fd\\u4e2d\\u5b66\\u751f\\u9996\\u90e8\\u60c5\\u611f\\u7cfb\\u5217\\u7247\\u300a\\u804a\\u5929\\u2161\\uff1a\\u6210\\u957f\\u5b63\\u300b\\u9ad8\\u6e05\\u6b63\\u7247","url":"http:\\/\\/www.silkroad.news.cn\\/2012\\/1009\\/61.shtml","color":"","subtitle":"\\u4e2d\\u56fd\\u4e2d\\u5b66\\u751f","suburl":"","description":"\\u7531 \\u6df1\\u5733\\u5e02\\u7b2c\\u4e8c\\u804c\\u4e1a\\u6280\\u672f\\u5b66\\u6821 \\u51fa\\u54c1\\uff0c\\u552f\\u8bb0\\u5fc6\\u5f71\\u89c6\\u5de5\\u574a \\u6444\\u5236\\u7684\\u4e2d\\u56fd\\u4e2d\\u5b66\\u751f\\u9996\\u90e8\\u60c5\\u611f\\u7cfb\\u5217\\u7247\\u300a\\u804a\\u5929\\u2161\\uff1a\\u6210\\u957f\\u5b63\\u300b\\u3002","thumb":"2012\\/1009\\/1349755867505.jpg","time":"1349755680"},{"contentid":34,"title":"\\u97e9\\u4eba\\u6c14\\u56e2\\u56e2SJ\\u53c2\\u52a0\\u6d3b\\u52a8 \\u5c55\\u73b0\\u7cbe\\u5f69\\u821e\\u53f0(\\u56fe)","url":"http:\\/\\/www.silkroad.news.cn\\/2012\\/1008\\/34.shtml","color":"","subtitle":"\\u97e9\\u4eba\\u6c14\\u56e2\\u56e2","suburl":"","description":"\\u97e9\\u4eba\\u6c14\\u56e2\\u56e2SJ\\u53c2\\u52a0\\u6d3b\\u52a8 \\u5c55\\u73b0\\u7cbe\\u5f69\\u821e\\u53f0(\\u56fe)","thumb":"2012\\/1008\\/1349697676245.jpg","time":"1349687460"},{"contentid":28,"title":"\\u6c7d\\u8f66\\u6c19\\u6c14\\u706f\\u5de8\\u5934\\u96ea\\u83b1\\u7279\\u6253\\u54cd\\u5168\\u56fd\\u7ef4\\u6743\\u6218","url":"http:\\/\\/www.silkroad.news.cn\\/2012\\/1008\\/28.shtml","color":"","subtitle":"\\u6c7d\\u8f66\\u6c19\\u6c14\\u706f","suburl":"","description":"\\u6c7d\\u8f66\\u6c19\\u6c14\\u706f\\u5de8\\u5934\\u96ea\\u83b1\\u7279\\u6253\\u54cd\\u5168\\u56fd\\u7ef4\\u6743\\u6218","thumb":"2012\\/1008\\/1349698664723.jpg","time":"1349652960"}]', NULL, NULL, NULL, '<div class="m-title-a">\n    <h2 class="m-title-h2"><a>推荐视频</a></h2>\n</div>\n<div class="hr20"></div>\n<div class="commend-picture-inner">\n    <ul class="m-imagetitle">\n        <!--{loop $data $k $r}-->\n        <!--{if $k%2 == 0}-->\n            <li class="item odd js-overlay">\n        <!--{else}-->\n            <li class="item js-overlay">\n        <!--{/if}-->\n            <a href="{$r[url]}" target="_blank" title="{$r[title]}" class="thumb-link"><img src="{thumb($r[thumb], 140, 105, 1, null, 1)}" alt="" width="140" height="105">\n            <!--{if $r[contentid]}-->\n            <span class="time">{second_to_time(table(''video'', intval($r[''contentid'']), ''playtime''))}</span>\n            <!--{/if}-->\n            </a>\n            <a class="title" href="{$r[url]}" target="_blank" title="{$r[title]}"{if $r[color]} style="color:{$r[color]}"{/if}>{str_natcut($r[title], 22, '''')}</a>\n            <a href="{$r[url]}" class="overlay"><b class="overlay-play icon40x40"></b></a>\n        </li>\n        <!--{/loop}-->\n    </ul>\n</div>', 'html', NULL, 4, 0, 0, '{"system_fields":{"contentid":{"checked":"1","func":"intval"},"title":{"checked":"1","required":"1","min_length":"","max_length":""},"url":{"checked":"1"},"color":{"checked":"1"},"icon":{"checked":"1"},"iconsrc":{"checked":"1"},"subtitle":{"checked":"1","min_length":"","max_length":""},"suburl":{"checked":"1"},"thumb":{"checked":"1","width":"","height":""},"description":{"checked":"1","min_length":"","max_length":""},"time":{"checked":"1","func":"section_fields_strtotime"}},"custom_fields":{"text":[],"name":[]}}', 1426060663, 1426060663, 0, 0, 1426060649, 1, 1342513505, 1, '图片 + 标题，共 4 个，标题长度 15 个汉字，缩略图规格 120 x 77', 1, 0, 'section/list.html', 0, 10),
(15, 1, 'push', '头屏幻灯片', '[]', '[{"contentid":154,"icon":"blank","iconsrc":"","title":"\\u5317\\u5f71\\u827a\\u8003\\u590d\\u8bd5\\u5f00\\u59cb \\u7504\\u5b50\\u4e39\\u5f20\\u4e30\\u6bc5\\u4efb\\u8003\\u5b98","color":"","url":"http:\\/\\/www.silkroad.news.cn\\/2015\\/0319\\/154.shtml","subtitle":"","suburl":"","thumb":"http:\\/\\/upload.silkroad.news.cn\\/2015\\/0319\\/1426730704753.jpg","description":"","time":1426730700},{"contentid":8,"title":"\\u5317\\u4eac8\\u5929\\u63a5\\u5f85\\u6e38\\u5ba21312\\u4e07 \\u65c5\\u6e38\\u6536\\u5165\\u540c\\u6bd4\\u589e\\u8fd1\\u4e09\\u6210","url":"http:\\/\\/www.silkroad.news.cn\\/2012\\/1010\\/8.shtml","color":"","subtitle":"","suburl":"","description":"","thumb":"2012\\/1008\\/1349698524629.jpg","time":"1349850060"},{"contentid":83,"title":"\\u5f20\\u53ec\\u5fe0\\uff1a\\u4e2d\\u56fd30\\u591a\\u5e74\\u5fcd\\u8fb1\\u8d1f\\u91cd\\u88ab\\u8d8a\\u5357\\u8bef\\u89e3\\u4e3a\\u5bb3\\u6015","url":"http:\\/\\/www.silkroad.news.cn\\/2012\\/1009\\/83.shtml","color":"","subtitle":"","suburl":"","description":"","thumb":"2012\\/1009\\/1349775045950.jpg","time":"1349775060"},{"contentid":66,"title":"\\u4e0a\\u6d77\\u87cb\\u87c0\\u4ea4\\u6613\\u706b\\u7206 \\u4e00\\u53ea\\u87cb\\u87c0\\u80fd\\u5356\\u4e0a\\u4e07\\u5143","url":"http:\\/\\/www.silkroad.news.cn\\/2012\\/1009\\/66.shtml","color":"","subtitle":"","suburl":"","description":"","thumb":"2012\\/1009\\/1349757398203.jpg","time":"1349757360"}]', NULL, NULL, NULL, '<div class="fl-l pictures focus-images imagetab pos-r index-imagetab">\n    <div class="fPic">  \n        <!--{loop $data $k $r}-->\n        <div class="fcon">\n            <a target="_blank" href="{$r[url]}" title="{$r[title]}"><img src="{thumb($r[thumb], 540, 360)}" width="540" height="360"></a>\n            <div class="shadow"><a target="_blank" href="{$r[url]}" title="{$r[title]}">{str_natcut($r[title], 20, '''')}</a></div>\n        </div>\n        \n        <!--{/loop}-->\n    </div>\n    <div class="fbg">  \n    <div class="pointes"></div>\n    </div>  \n    <span class="prev hidden"></span>   \n    <span class="next hidden"></span>    \n</div>', 'html', NULL, 4, 0, 0, '{"system_fields":{"contentid":{"checked":"1","func":"intval"},"title":{"checked":"1","required":"1","min_length":"","max_length":""},"url":{"checked":"1"},"color":{"checked":"1"},"icon":{"checked":"1"},"iconsrc":{"checked":"1"},"subtitle":{"checked":"1","min_length":"","max_length":""},"suburl":{"checked":"1"},"thumb":{"checked":"1","width":"","height":""},"description":{"checked":"1","min_length":"","max_length":""},"time":{"checked":"1","func":"section_fields_strtotime"}},"custom_fields":{"text":[],"name":[]}}', 1426917237, 1426917236, 0, 0, 1426833506, 1, 1342514197, 1, '默认6行，图片大小为310*230', 1, 0, 'section/list.html', 0, 10),
(16, 1, 'push', '思拓专区推荐', '[]', '[]', NULL, NULL, NULL, '<!--{loop $data $k $r}-->\n<!--{if $k==0}-->\n<div class="h-pic clear">\n    <a href="{$r[url]}" title="{$r[title]}" ><img src="{thumb($r[thumb],58,58,1,null,1)}" class="thumb" alt="{$r[title]}" width="58" height="58" /></a>\n    <div class="texts">\n        <p class="title2"><a href="{$r[url]}" title="{$r[title]}" class="title">{$r[title]}</a></p>\n        <p class="summary">{str_natcut($r[description],15,''...'')}<a href="{$r[url]}" title="{$r[title]}" class="more">[详细]</a></p>\n    </div>\n</div>\n<div class="dotted"></div>\n<ul class="list list-point">\n<!--{else}-->\n    <li class="item"><em class="ico"></em><a href="{$r[url]}" class="title">{$r[title]}</a> </li>\n<!--{/if}-->\n<!--{/loop}-->\n</ul>\n<div class="dotted"></div>', 'html', NULL, 4, 0, 0, NULL, 1423123360, 1423123360, 0, 0, 1423194430, 1, 1342515547, 1, '4行 第一行为带图和描述', 0, 0, 'section/list.html', 0, 10),
(17, 1, 'html', '思拓专区帮助链接', '                   <ul class="list other-links">\n                     <li class="item"><em class="ico n1">1</em><a href="" title="" class="title">产品使用视频教程</a></li>\n                     <li class="item"><em class="ico n1">2</em><a href="" title="" class="title">模版制作视频教程</a></li>\n                     <li class="item"><em class="ico">3</em><a href="" title="" class="title">模版制作手册</a></li>\n                      <li class="item"><em class="ico">4</em><a href="" title="" class="title">二次开发技术手册</a></li>\n                    </ul>', '                   <ul class="list other-links">\n                     <li class="item"><em class="ico n1">1</em><a href="" title="" class="title">产品使用视频教程</a></li>\n                     <li class="item"><em class="ico n1">2</em><a href="" title="" class="title">模版制作视频教程</a></li>\n                     <li class="item"><em class="ico">3</em><a href="" title="" class="title">模版制作手册</a></li>\n                      <li class="item"><em class="ico">4</em><a href="" title="" class="title">二次开发技术手册</a></li>\n                    </ul>', NULL, NULL, NULL, '', 'html', 0, 0, 0, 0, NULL, 1423123360, 1423123360, 0, 0, 1423194423, 1, 1342515600, 1, '', 0, 0, NULL, NULL, 10),
(18, 7, 'auto', '中部广告', '', '<script type="text/javascript" id="adm-73">\n(function() {\n   window.ADMBlocks = window.ADMBlocks || [];\n   ADMBlocks.push({\n       id : ''73'',  // 广告位id\n       width : ''1000'',  // 宽\n       height : ''100'',  // 高\n       type : ''1''  // 类型\n   });\n   var h=document.getElementsByTagName(''head'')[0], s=document.createElement(''script'');\n   s.async=true; s.src=''http://ad.cmstop.cn/js/show.js'';\n   h && h.insertBefore(s,h.firstChild)\n})();\n</script>\n', NULL, NULL, NULL, '', 'html', NULL, 0, 300, 0, NULL, 1426731502, 1426731202, 0, 0, 1426731116, 1, 1342515746, 1, '960*80', 1, 0, NULL, NULL, 10),
(19, 11, 'push', '今日推荐', '[]', '[{"contentid":5,"title":"\\u5317\\u4eac\\u9ad8\\u901f\\u5783\\u573e8\\u5929\\u8fbe\\u767e\\u5428 \\u5783\\u573e\\u591a\\u4e3a\\u5e9f\\u7eb8\\u77ff\\u6cc9\\u6c34\\u74f6","url":"http:\\/\\/www.silkroad.news.cn\\/2012\\/1010\\/5.shtml","color":"","subtitle":"\\u5317\\u4eac\\u9ad8\\u901f\\u5783","suburl":"","description":"\\u5317\\u4eac\\u9ad8\\u901f\\u5783\\u573e8\\u5929\\u8fbe\\u767e\\u5428 \\u5783\\u573e\\u591a\\u4e3a\\u5e9f\\u7eb8\\u77ff\\u6cc9\\u6c34\\u74f6","thumb":"2012\\/1008\\/1349698511700.jpg","time":"1349850060"},{"contentid":20,"title":"\\u535a\\u4e16\\uff1a2020\\u5e74\\u5546\\u7528\\u8f66\\u6cb9\\u8017\\u6709\\u671b\\u518d\\u51cf\\u5c1115%","url":"http:\\/\\/www.silkroad.news.cn\\/2012\\/1010\\/20.shtml","color":"","subtitle":"\\u535a\\u4e16\\uff1a2020","suburl":"","description":"\\u535a\\u4e16\\uff1a2020\\u5e74\\u5546\\u7528\\u8f66\\u6cb9\\u8017\\u6709\\u671b\\u518d\\u51cf\\u5c1115%","thumb":"2012\\/1008\\/1349698585871.jpg","time":"1349850060"},{"contentid":91,"title":" \\u5317\\u4eac\\u5e02\\u533a17\\u65e5\\u62e5\\u5835\\u8def\\u6bb5\\u5cf0\\u503c\\u8d85140\\u6761\\u521b\\u7eaa\\u5f55","url":"http:\\/\\/www.silkroad.news.cn\\/2012\\/1009\\/91.shtml","color":"","subtitle":" \\u5317\\u4eac\\u5e02\\u533a1","suburl":"","description":"9\\u670817\\u65e5\\uff0c\\u5317\\u4eac\\u5e02\\u533a\\u665a\\u9ad8\\u5cf0\\u62e5\\u5835\\u8def\\u6bb5\\u5cf0\\u503c\\u8d85140\\u6761\\uff0c\\u5df2\\u7ecf\\u8d85\\u8fc7\\u4eca\\u5e74\\u5e74\\u521d\\u56e0\\u5927\\u96ea\\u9020\\u621090\\u4f59\\u6761\\u62e5\\u5835\\u8def\\u6bb5\\u5cf0\\u503c\\u7684\\u7eaa\\u5f55\\u3002","thumb":"2012\\/1009\\/1349778701818.jpg","time":"1349778720"},{"contentid":86,"title":"\\u5f20\\u53ec\\u5fe0\\uff1a\\u7f8e\\u56fd\\u901a\\u8fc7\\u4f0a\\u62c9\\u514b\\u6218\\u4e89\\u5f97\\u5230\\u7684\\u6bd4\\u5931\\u53bb\\u7684\\u591a","url":"http:\\/\\/www.silkroad.news.cn\\/2012\\/1009\\/86.shtml","color":"","subtitle":"\\u5f20\\u53ec\\u5fe0\\uff1a\\u7f8e","suburl":"","description":"12\\u670818\\u65e5\\u9a7b\\u4f0a\\u7f8e\\u519b\\u6700 \\u540e\\u4e00\\u6279\\u7f8e\\u519b\\u64a4\\u79bb\\u4f0a\\u62c9\\u514b\\uff0c\\u8fd9\\u8bc1\\u660e\\u5386\\u65f69\\u5e74\\u7684\\u4f0a\\u62c9\\u514b\\u6218\\u4e89\\u6b63\\u5f0f\\u5ba3\\u544a\\u7ed3\\u675f\\u4e86\\uff0c\\u7f8e\\u519b\\u8d70\\u540e\\u7a76\\u7adf\\u4f1a\\u7559\\u4e0b\\u4e00\\u4e2a\\u600e\\u4e48\\u6837\\u7684\\u4f0a\\u62c9\\u514b\\u5462\\uff1f\\u7f8e\\u519b\\u4e0b\\u4e00\\u4e2a\\u76ee\\u6807\\u53c8\\u6307\\u5411\\u8c01\\uff1f\\u4eca\\u5929\\u5c31\\u7f8e\\u519b\\u64a4\\u51fa\\u4f0a \\u62c9\\u514b\\u7684\\u8bdd\\u9898\\u91c7\\u8bbf\\u4e00\\u4e0b\\u8457\\u540d\\u7684\\u519b\\u4e8b\\u4e13\\u5bb6\\u5f20\\u53ec\\u5fe0\\u6559\\u6388","thumb":"2012\\/1009\\/1349775628390.jpg","time":"1349775600"},{"contentid":65,"title":"\\u6c14\\u8d28\\u5973\\u795e\\u5b87\\u8f69\\u7ffb\\u5531\\u300a\\u4e0d\\u518d\\u8ba9\\u4f60\\u5b64\\u5355\\u300b","url":"http:\\/\\/www.silkroad.news.cn\\/2012\\/1009\\/65.shtml","color":"","subtitle":"\\u6c14\\u8d28\\u5973\\u795e\\u5b87","suburl":"","description":"\\u6f14\\u5531:\\u5b87\\u8f69\\n\\u526a\\u8f91&\\u8c03\\u8272:\\u5730\\u4e3b_L\\n\\u5b57\\u5e55:\\u5730\\u4e3b_L\\n\\u97f3\\u4e50\\u652f\\u6301:@\\u590d\\u4e50\\u73ed","thumb":"2012\\/1009\\/1349757234658.jpg","time":"1349757180"},{"contentid":64,"title":"\\u4fc4\\u4ea5\\u4fc4\\u5dde\\u7acb\\u5927\\u5b66\\u519b\\u4e50\\u56e2\\u9707\\u64bc\\u8868","url":"http:\\/\\/www.silkroad.news.cn\\/2012\\/1009\\/64.shtml","color":"","subtitle":"\\u4fc4\\u4ea5\\u4fc4\\u5dde\\u7acb","suburl":"","description":"\\u4e00\\u5929\\u5185\\u5f15\\u53d1\\u767e\\u4e07\\u7f51\\u53cb\\u56f4\\u89c2\\uff01\\u7f8e\\u56fd\\u4fc4\\u4ea5\\u4fc4\\u5dde\\u7acb\\u5927\\u5b66\\u519b\\u4e50\\u56e2\\u9707\\u64bc\\u8868\\u6f14\\u81f4\\u656c\\u7535\\u5b50\\u6e38\\u620f\\uff01\\u7b2c6\\u5206\\u949f\\u7684\\u8868\\u6f14\\u7b80\\u76f4\\u592a\\u795e\\u4e86\\uff01\\u4ed6\\u4eec\\u592a\\u725bA\\u4e86\\uff01\\u89e6\\u52a8\\u4f60\\u7684\\u7ae5\\u5e74\\u56de\\u5fc6\\u4e86\\u5417\\uff1f\\u8fd9\\u4e2a\\u5fc5\\u987b\\u819c\\u62dc\\uff01\\uff01\\uff01","thumb":"2012\\/1009\\/1349757123242.jpg","time":"1349757120"},{"contentid":60,"title":"\\u5317\\u4eac\\u5730\\u94c1\\u4fe1\\u606f\\u5c4f\\u73b0\\u201c\\u738b\\u9e4f\\u4f60\\u59b9\\u201d \\u56de\\u5e94\\uff1a\\u7cfb\\u7edf\\u8c03\\u8bd5","url":"http:\\/\\/www.silkroad.news.cn\\/2012\\/1009\\/60.shtml","color":"","subtitle":"\\u5317\\u4eac\\u5730\\u94c1\\u4fe1","suburl":"","description":"\\u5317\\u4eac\\u5730\\u94c1\\u4fe1\\u606f\\u5c4f\\u73b0\\u201c\\u738b\\u9e4f\\u4f60\\u59b9\\u201d \\u56de\\u5e94\\uff1a\\u7cfb\\u7edf\\u8c03\\u8bd5","thumb":"2012\\/1009\\/1349755393811.jpg","time":"1349755380"},{"contentid":58,"title":"\\u83ab\\u8a00\\u6751\\u4e0a\\u6625\\u6811\\u6210\\u8bfa\\u8d1d\\u5c14\\u6587\\u5b66\\u5956\\u70ed\\u95e8 \\u4e13\\u5bb6\\u8d28\\u7591\\u7092\\u4f5c","url":"http:\\/\\/www.silkroad.news.cn\\/2012\\/1009\\/57.shtml","color":"","subtitle":"\\u83ab\\u8a00\\u6751\\u4e0a\\u6625","suburl":"","description":"\\u53a6\\u95e8\\u5927\\u5b66\\u4eba\\u6587\\u5b66\\u9662\\u9662\\u957f\\u5468\\u5b81\\u8ba4\\u4e3a\\uff0c\\u5f53\\u524d\\u5bf9\\u4e8e\\u83ab\\u8a00\\u83b7\\u5956\\u7684\\u5404\\u79cd\\u731c\\u6d4b\\u66f4\\u591a\\u662f\\u4e00\\u79cd\\u65b0\\u95fb\\u7092\\u4f5c\\uff0c\\u5bf9\\u9881\\u5956\\u672c\\u8eab\\u6ca1\\u6709\\u592a\\u5927\\u610f\\u4e49\\u3002","thumb":"2012\\/1009\\/1349751503718.jpg","time":"1349751840"},{"contentid":34,"title":"\\u97e9\\u4eba\\u6c14\\u56e2\\u56e2SJ\\u53c2\\u52a0\\u6d3b\\u52a8 \\u5c55\\u73b0\\u7cbe\\u5f69\\u821e\\u53f0(\\u56fe)","url":"http:\\/\\/www.silkroad.news.cn\\/2012\\/1008\\/34.shtml","color":"","subtitle":"\\u97e9\\u4eba\\u6c14\\u56e2\\u56e2","suburl":"","description":"\\u97e9\\u4eba\\u6c14\\u56e2\\u56e2SJ\\u53c2\\u52a0\\u6d3b\\u52a8 \\u5c55\\u73b0\\u7cbe\\u5f69\\u821e\\u53f0(\\u56fe)","thumb":"2012\\/1008\\/1349697676245.jpg","time":"1349687460"},{"contentid":37,"title":"\\u201c\\u56fd\\u5e86\\u4e00\\u59d0\\u201d\\u8303\\u51b0\\u51b0\\u534e\\u670d\\u4e09\\u8fde\\u51fb \\u5c3d\\u5c55\\u6cd5\\u5f0f\\u4f18\\u96c5","url":"http:\\/\\/www.silkroad.news.cn\\/2012\\/1008\\/37.shtml","color":"","subtitle":"\\u201c\\u56fd\\u5e86\\u4e00\\u59d0","suburl":"","description":"\\u201c\\u56fd\\u5e86\\u4e00\\u59d0\\u201d\\u8303\\u51b0\\u51b0\\u534e\\u670d\\u4e09\\u8fde\\u51fb \\u5c3d\\u5c55\\u6cd5\\u5f0f\\u4f18\\u96c5","thumb":"2012\\/1008\\/1349698412285.jpg","time":"1349681880"}]', NULL, NULL, NULL, '<!--{loop $data $k $r}-->\n<li class="m-accordion-item<!--{if $k<3}--> top<!--{/if}-->">\n    <a href="{$r[''url'']}" target="_blank" title="{$r[''title'']}" class="title" <!--{if $k == 0}-->style="display:none;"<!--{/if}-->>{str_natcut($r[''title''],20,'''')}</a>\n    <div class="m-accordion-thumb ov" <!--{if $k > 0}-->style="display:none;"<!--{/if}-->>\n        <a href="{$r[''url'']}" target="_blank" title="{$r[''title'']}"><img class="thumb fl-l" src="{thumb($r[thumb], 120, 90, 1, null, 1)}" width="120" height="90" alt="{$r[title]}" /></a><p><a href="{$r[''url'']}" target="_blank" title="{$r[''title'']}">{$r[''title'']}</a></p>\n    </div>\n</li>\n<!--{/loop}-->', 'html', NULL, 10, 0, 0, '{"system_fields":{"contentid":{"checked":"1","func":"intval"},"title":{"checked":"1","required":"1","min_length":"","max_length":""},"url":{"checked":"1"},"color":{"checked":"1"},"icon":{"checked":"1"},"iconsrc":{"checked":"1"},"subtitle":{"checked":"1","min_length":"","max_length":""},"suburl":{"checked":"1"},"thumb":{"checked":"1","width":"","height":""},"description":{"checked":"1","min_length":"","max_length":""},"time":{"checked":"1","func":"section_fields_strtotime"}},"custom_fields":{"text":[],"name":[]}}', 1426228714, 1426228714, 0, 0, 1426228711, 1, 1342516320, 1, '文章内容页今日推荐区块', 1, 0, 'section/list.html', 0, 10),
(20, 12, 'auto', '右侧 300x240', '', '<script type="text/javascript" id="adm-80">\n(function() {\n   window.ADMBlocks = window.ADMBlocks || [];\n   ADMBlocks.push({\n       id : ''80'',  // 广告位id\n       width : ''300'',  // 宽\n       height : ''240'',  // 高\n       type : ''1''  // 类型\n   });\n   var h=document.getElementsByTagName(''head'')[0], s=document.createElement(''script'');\n   s.async=true; s.src=''http://ad.cmstop.cn/js/show.js'';\n   h && h.insertBefore(s,h.firstChild)\n})();\n</script>\n', NULL, NULL, NULL, '', 'html', NULL, 0, 300, 0, NULL, 1426731506, 1426731206, 0, 0, 1426731125, 1, 1342517235, 1, '文章内容页广告右焦点300x240尺寸广告', 1, 0, NULL, NULL, 10),
(21, 13, 'auto', '右侧 300 x 240', '', '<script type="text/javascript" id="adm-78">\n(function() {\n   window.ADMBlocks = window.ADMBlocks || [];\n   ADMBlocks.push({\n       id : ''78'',  // 广告位id\n       width : ''300'',  // 宽\n       height : ''240'',  // 高\n       type : ''1''  // 类型\n   });\n   var h=document.getElementsByTagName(''head'')[0], s=document.createElement(''script'');\n   s.async=true; s.src=''http://ad.cmstop.cn/js/show.js'';\n   h && h.insertBefore(s,h.firstChild)\n})();\n</script>\n', NULL, NULL, NULL, '', 'html', NULL, 0, 300, 0, NULL, 1426731511, 1426731211, 0, 0, 1426731145, 1, 1342517842, 1, '列表页头部左侧广告，要求648x78', 1, 0, NULL, NULL, 10),
(22, 1, 'push', '新闻频道左侧图', '[]', '[{"contentid":148,"icon":"blank","iconsrc":"","title":"\\u4e8b\\u4ef6\\u4e13\\u9898","color":"","url":"http:\\/\\/special.silkroad.news.cn\\/150317-2","subtitle":"","suburl":"","thumb":"2015\\/0319\\/1426765953930.jpg","description":"","time":1426571145}]', NULL, NULL, NULL, '<!--{loop $data $k $r}-->\n<div class="fl-l picture pos-r"><a href="{$r[url]}" title="{$r[title]}" target="_blank"><img src="{thumb($r[thumb],320,240,1,null,1)}" width="320" height="240" alt=""></a><p class="text ie-rgba"><a href="{$r[url]}" title="{$r[title]}" target="_blank">{$r[title]}</a></p></div>\n<!--{/loop}-->', 'html', NULL, 1, 300, 0, '{"system_fields":{"contentid":{"checked":"1","func":"intval"},"title":{"checked":"1","required":"1","min_length":"","max_length":""},"url":{"checked":"1"},"color":{"checked":"1"},"icon":{"checked":"1"},"iconsrc":{"checked":"1"},"subtitle":{"checked":"1","min_length":"","max_length":""},"suburl":{"checked":"1"},"thumb":{"checked":"1","width":"","height":""},"description":{"checked":"1","min_length":"","max_length":""},"time":{"checked":"1","func":"section_fields_strtotime"}},"custom_fields":{"text":[],"name":[]}}', 1426917537, 1426917236, 0, 0, 1426832574, 1, 1342517911, 1, '1条 为图文表述', 1, 0, 'section/list.html', 0, 10),
(23, 13, 'auto', '右侧 300 x 90', '', '<script type="text/javascript" id="adm-79">\n(function() {\n   window.ADMBlocks = window.ADMBlocks || [];\n   ADMBlocks.push({\n       id : ''79'',  // 广告位id\n       width : ''300'',  // 宽\n       height : ''90'',  // 高\n       type : ''1''  // 类型\n   });\n   var h=document.getElementsByTagName(''head'')[0], s=document.createElement(''script'');\n   s.async=true; s.src=''http://ad.cmstop.cn/js/show.js'';\n   h && h.insertBefore(s,h.firstChild)\n})();\n</script>\n', NULL, NULL, NULL, '', 'html', NULL, 0, 300, 0, NULL, 1426731512, 1426731212, 0, 0, 1426731149, 1, 1342518127, 1, '列表页头部右侧广告位298*78', 1, 0, NULL, NULL, 10),
(24, 7, 'auto', '右侧广告', '', '<script type="text/javascript" id="adm-74">\n(function() {\n   window.ADMBlocks = window.ADMBlocks || [];\n   ADMBlocks.push({\n       id : ''74'',  // 广告位id\n       width : ''300'',  // 宽\n       height : ''200'',  // 高\n       type : ''1''  // 类型\n   });\n   var h=document.getElementsByTagName(''head'')[0], s=document.createElement(''script'');\n   s.async=true; s.src=''http://ad.cmstop.cn/js/show.js'';\n   h && h.insertBefore(s,h.firstChild)\n})();\n</script>\n', NULL, NULL, NULL, '', 'html', NULL, 0, 0, 0, NULL, 1426731203, 1426731203, 0, 0, 1426731119, 1, 1342518131, 1, '', 1, 0, NULL, NULL, 10);
INSERT INTO `cmstop_section` (`sectionid`, `pageid`, `type`, `name`, `origdata`, `data`, `url`, `method`, `args`, `template`, `output`, `width`, `rows`, `frequency`, `check`, `fields`, `nextupdate`, `published`, `locked`, `lockedby`, `updated`, `updatedby`, `created`, `createdby`, `description`, `status`, `list_enabled`, `list_template`, `list_pagesize`, `list_pages`) VALUES
(25, 1, 'push', '新闻频道列表', '[]', '[{"contentid":14,"title":"\\u67e5\\u97e6\\u65af\\u8d62\\u5f97\\u59d4\\u5185\\u745e\\u62c9\\u603b\\u7edf\\u5927\\u9009 \\u8fde\\u7eed\\u6267\\u653f\\u8fd114\\u5e74","url":"http:\\/\\/www.silkroad.news.cn\\/2012\\/1010\\/14.shtml","color":"","subtitle":"\\u67e5\\u97e6\\u65af\\u8d62\\u5f97","suburl":"","description":"\\u67e5\\u97e6\\u65af\\u8d62\\u5f97\\u59d4\\u5185\\u745e\\u62c9\\u603b\\u7edf\\u5927\\u9009 \\u8fde\\u7eed\\u6267\\u653f\\u8fd114\\u5e74","thumb":"2012\\/1008\\/1349698537197.jpg","time":"1349850101"},{"contentid":20,"title":"\\u535a\\u4e16\\uff1a2020\\u5e74\\u5546\\u7528\\u8f66\\u6cb9\\u8017\\u6709\\u671b\\u518d\\u51cf\\u5c1115%","url":"http:\\/\\/www.silkroad.news.cn\\/2012\\/1010\\/20.shtml","color":"","subtitle":"\\u535a\\u4e16\\uff1a2020","suburl":"","description":"\\u535a\\u4e16\\uff1a2020\\u5e74\\u5546\\u7528\\u8f66\\u6cb9\\u8017\\u6709\\u671b\\u518d\\u51cf\\u5c1115%","thumb":"2012\\/1008\\/1349698585871.jpg","time":"1349850060"},{"contentid":91,"title":" \\u5317\\u4eac\\u5e02\\u533a17\\u65e5\\u62e5\\u5835\\u8def\\u6bb5\\u5cf0\\u503c\\u8d85140\\u6761\\u521b\\u7eaa\\u5f55","url":"http:\\/\\/www.silkroad.news.cn\\/2012\\/1009\\/91.shtml","color":"","subtitle":" \\u5317\\u4eac\\u5e02\\u533a1","suburl":"","description":"9\\u670817\\u65e5\\uff0c\\u5317\\u4eac\\u5e02\\u533a\\u665a\\u9ad8\\u5cf0\\u62e5\\u5835\\u8def\\u6bb5\\u5cf0\\u503c\\u8d85140\\u6761\\uff0c\\u5df2\\u7ecf\\u8d85\\u8fc7\\u4eca\\u5e74\\u5e74\\u521d\\u56e0\\u5927\\u96ea\\u9020\\u621090\\u4f59\\u6761\\u62e5\\u5835\\u8def\\u6bb5\\u5cf0\\u503c\\u7684\\u7eaa\\u5f55\\u3002","thumb":"2012\\/1009\\/1349778701818.jpg","time":"1349778720"},{"contentid":83,"title":"\\u5f20\\u53ec\\u5fe0\\uff1a\\u4e2d\\u56fd30\\u591a\\u5e74\\u5fcd\\u8fb1\\u8d1f\\u91cd\\u88ab\\u8d8a\\u5357\\u8bef\\u89e3\\u4e3a\\u5bb3\\u6015","url":"http:\\/\\/www.silkroad.news.cn\\/2012\\/1009\\/83.shtml","color":"","subtitle":"\\u5f20\\u53ec\\u5fe0\\uff1a\\u4e2d","suburl":"","description":"\\u8fd1\\u671f\\u5357\\u6d77\\u4e89\\u7aef\\u7684\\u7d27\\u5f20\\u5c40\\u52bf\\u4e0d\\u65ad\\u5347\\u6e29\\uff0c\\u8d8a\\u5357\\u548c\\u7f8e\\u83f2\\u63a5\\u8fde\\u5728\\u5357\\u6d77\\u8fdb\\u884c\\u6f14\\u4e60\\uff0c\\u90a3\\u4e48\\u5357\\u6d77\\u4e89\\u7aef\\u7684\\u5347\\u7ea7\\u662f\\u5426\\u4f1a\\u5bfc\\u81f4\\u519b\\u4e8b\\u51b2\\u7a81\\u5462\\uff1f\\u4eca\\u5929\\u6211\\u4eec\\u5c31\\u6765\\u8bf7\\u6559\\u4e00\\u4e0b\\u519b\\u4e8b\\u4e13\\u5bb6\\u5f20\\u53ec\\u5fe0\\u6559\\u6388","thumb":"2012\\/1009\\/1349775045950.jpg","time":"1349775101"},{"contentid":36,"title":"\\u590f\\u5947\\u62c9\\u5927\\u809a\\u7167\\u4f53\\u6001\\u5300\\u79f0 \\u81ea\\u66dd\\u5c06\\u751f\\u7537\\u5b69(\\u56fe)","url":"http:\\/\\/www.silkroad.news.cn\\/2012\\/1008\\/36.shtml","color":"","subtitle":"\\u590f\\u5947\\u62c9\\u5927\\u809a","suburl":"","description":"\\u590f\\u5947\\u62c9\\u5927\\u809a\\u7167\\u4f53\\u6001\\u5300\\u79f0 \\u81ea\\u66dd\\u5c06\\u751f\\u7537\\u5b69(\\u56fe)","thumb":"2012\\/1008\\/1349698313297.jpg","time":"1349684640"},{"contentid":31,"title":"\\u5f20\\u7ff0\\u5e86\\u751f\\u90d1\\u723d\\u7d20\\u989c\\u73b0\\u8eab \\u6df1\\u60c5\\u6ce8\\u89c6\\u7537\\u65b9(\\u56fe)","url":"http:\\/\\/www.silkroad.news.cn\\/2012\\/1008\\/31.shtml","color":"","subtitle":"\\u5f20\\u7ff0\\u5e86\\u751f\\u90d1","suburl":"","description":"\\u5f20\\u7ff0\\u5e86\\u751f\\u90d1\\u723d\\u7d20\\u989c\\u73b0\\u8eab \\u6df1\\u60c5\\u6ce8\\u89c6\\u7537\\u65b9(\\u56fe)","thumb":"2012\\/1008\\/1349697611460.jpg","time":"1349691960"}]', NULL, NULL, NULL, '<!--{loop $data $k $r}-->\n<!--{if $k == 0}-->\n<h3 class="h3"><a href="{$r[url]}" title="{$r[title]}" target="_blank">{$r[title]}</a></h3>\n<p class="summary">{str_natcut($r[description], 45, ''...'')}</p>\n<div class="hr10"></div>\n<!--{/if}-->\n<!--{/loop}-->\n<ul class="ul">\n    <!--{loop $data $k $r}-->\n    <!--{if $k > 0}-->\n    <li><a href="{$r[url]}" title="{$r[title]}" target="_blank">{$r[title]}</a></li>\n    <!--{/if}-->\n    <!--{/loop}-->\n</ul>', 'html', NULL, 6, 300, 0, '{"system_fields":{"contentid":{"checked":"1","func":"intval"},"title":{"checked":"1","required":"1","min_length":"","max_length":""},"url":{"checked":"1"},"color":{"checked":"1"},"icon":{"checked":"1"},"iconsrc":{"checked":"1"},"subtitle":{"checked":"1","min_length":"","max_length":""},"suburl":{"checked":"1"},"thumb":{"checked":"1","width":"","height":""},"description":{"checked":"1","min_length":"","max_length":""},"time":{"checked":"1","func":"section_fields_strtotime"}},"custom_fields":{"text":[],"name":[]}}', 1426917537, 1426917236, 0, 0, 1426832328, 1, 1342518182, 1, '6行  第一行为带简介的内容', 1, 0, 'section/list.html', 0, 10),
(26, 1, 'push', '新闻频道视频推荐', '[]', '[]', NULL, NULL, NULL, '<div class="ov">\n<!--{loop $data $k $r}-->\n<div class="v-pic">\n<div class="thumb">\n<div class="opa"></div>\n<a href="{$r[url]}" class="ico"></a>\n<span class="info">12:00</span>\n<a href="{$r[url]}" title="{$r[title]}"><img src="{thumb($r[thumb],120,77,1,null,1)}" alt="" width="120" height="77"></a>\n</div>\n<div class="title"><a href="{$r[url]}" title="{$r[title]}">{$r[title]}</a></div>\n</div>\n<!--{/loop}-->\n</div>\n', 'html', NULL, 4, 300, 0, NULL, 1423123660, 1423123360, 0, 0, 1423204594, 1, 1342518291, 1, '', 0, 0, 'section/list.html', 0, 10),
(27, 1, 'push', '新闻频道右下图文推荐', '[]', '[]', NULL, NULL, NULL, '<!--{loop $data $k $r}-->\n<div class="h-pic clear">\n<a href="{$r[url]}" title="{$r[title]}" ><img src="{thumb($r[thumb],120,77,1,null,1)}" class="thumb" alt="{$r[title]}" width="80" height="80" /></a>\n<div class="texts">\n<p class="title2"><a href="{$r[url]}" title="{$r[title]}" class="title">{str_natcut($r[title],12,''...'')}</a></p>\n<p class="summary">{str_natcut($r[description],40,''...'')}<a href="{$r[url]}" title="{$r[title]}" class="more">[详细]</a></p>\n</div>\n</div>\n<!--{/loop}-->\n', 'html', NULL, 1, 300, 0, NULL, 1423123660, 1423123360, 0, 0, 1423204599, 1, 1342518363, 1, '权重85', 0, 0, 'section/list.html', 0, 10),
(29, 4, 'push', '幻灯片', '[]', '[{"contentid":71,"icon":"blank","iconsrc":"","title":"\\u5b9c\\u660c\\u4e07\\u4eba\\u76f8\\u4eb2\\u8282 \\u573a\\u9762\\u5341\\u5206\\u706b\\u7206","color":"","url":"http:\\/\\/www.silkroad.news.cn\\/2012\\/1017\\/71.shtml","subtitle":"\\u5b9c\\u660c\\u4e07\\u4eba\\u76f8","suburl":"","thumb":"2015\\/0313\\/1426228602981.jpg","description":"\\u5b9c\\u660c\\u4e07\\u4eba\\u76f8\\u4eb2\\u8282","time":1350442188},{"contentid":87,"icon":"blank","iconsrc":"","title":"\\u5f20\\u53ec\\u5fe0\\uff1a\\u6211\\u519b\\u8230\\u8247\\u7684\\u5c16\\u7aef\\u6280\\u672f\\u843d\\u540e\\u7f8e\\u56fd10-20\\u5e74","color":"","url":"http:\\/\\/www.silkroad.news.cn\\/2012\\/1009\\/87.shtml","subtitle":"\\u5f20\\u53ec\\u5fe0\\uff1a\\u6211","suburl":"","thumb":"2015\\/0316\\/1426493963534.jpg","description":"\\u4eca\\u5e74\\u768412\\u670826\\u65e5\\u662f \\u4e2d\\u56fd\\u4eba\\u6c11\\u6d77\\u519b\\u8d74\\u4e9a\\u4e01\\u6e7e\\u62a4\\u822a\\u6574\\u6574\\u4e09\\u5468\\u5e74\\u7eaa\\u5ff5\\u65e5\\uff0c\\u4e2d\\u56fd\\u6d77\\u519b\\u53ef\\u4ee5\\u8bf4\\u5411\\u4e16\\u754c\\u5c55\\u793a\\u4e86\\u6587\\u660e\\u4e4b\\u5e08\\uff0c\\u5a01\\u6b66\\u4e4b\\u5e08\\u7684\\u5149\\u8f89\\u7684\\u5f62\\u8c61\\u3002\\u56de\\u987e\\u8fd9\\u4e09\\u5e74\\u7684\\u62a4\\u822a\\u884c\\u52a8\\uff0c\\u4e2d\\u56fd\\u6d77\\u519b\\u7ecf\\u5386\\u4e86\\u54ea\\u4e9b\\u6ce2 \\u6298\\uff0c\\u6211\\u4eec\\u83b7\\u5f97\\u4e86\\u54ea\\u4e9b\\u7ecf\\u9a8c\\uff0c\\u53c8\\u68c0\\u9a8c\\u4e86\\u54ea\\u4e9b\\u4e2d\\u56fd\\u6d77\\u519b\\u7684\\u6b66\\u5668\\u88c5\\u5907\\u5462\\uff1f\\u4eca\\u5929\\u6211\\u4eec\\u5c31\\u8bf7\\u519b\\u4e8b\\u4e13\\u5bb6\\u5f20\\u53ec\\u5fe0\\u6559\\u6388\\u8ddf\\u60a8\\u4e00\\u8d77\\u6765\\u56de\\u987e\\u548c\\u89e3\\u8bfb\\u4e00\\u4e0b\\u6d77\\u519b\\u62a4\\u822a\\u4e09\\u5468\\u5e74\\u3002","time":1349775660},{"contentid":64,"title":"\\u4fc4\\u4ea5\\u4fc4\\u5dde\\u7acb\\u5927\\u5b66\\u519b\\u4e50\\u56e2\\u9707\\u64bc\\u8868","url":"http:\\/\\/www.silkroad.news.cn\\/2012\\/1009\\/64.shtml","color":"","subtitle":"\\u4fc4\\u4ea5\\u4fc4\\u5dde\\u7acb","suburl":"","description":"\\u4e00\\u5929\\u5185\\u5f15\\u53d1\\u767e\\u4e07\\u7f51\\u53cb\\u56f4\\u89c2\\uff01\\u7f8e\\u56fd\\u4fc4\\u4ea5\\u4fc4\\u5dde\\u7acb\\u5927\\u5b66\\u519b\\u4e50\\u56e2\\u9707\\u64bc\\u8868\\u6f14\\u81f4\\u656c\\u7535\\u5b50\\u6e38\\u620f\\uff01\\u7b2c6\\u5206\\u949f\\u7684\\u8868\\u6f14\\u7b80\\u76f4\\u592a\\u795e\\u4e86\\uff01\\u4ed6\\u4eec\\u592a\\u725bA\\u4e86\\uff01\\u89e6\\u52a8\\u4f60\\u7684\\u7ae5\\u5e74\\u56de\\u5fc6\\u4e86\\u5417\\uff1f\\u8fd9\\u4e2a\\u5fc5\\u987b\\u819c\\u62dc\\uff01\\uff01\\uff01","thumb":"2012\\/1009\\/1349757123242.jpg","time":"1349757120"},{"contentid":36,"title":"\\u590f\\u5947\\u62c9\\u5927\\u809a\\u7167\\u4f53\\u6001\\u5300\\u79f0 \\u81ea\\u66dd\\u5c06\\u751f\\u7537\\u5b69(\\u56fe)","url":"http:\\/\\/www.silkroad.news.cn\\/2012\\/1008\\/36.shtml","color":"","subtitle":"\\u590f\\u5947\\u62c9\\u5927\\u809a","suburl":"","description":"\\u590f\\u5947\\u62c9\\u5927\\u809a\\u7167\\u4f53\\u6001\\u5300\\u79f0 \\u81ea\\u66dd\\u5c06\\u751f\\u7537\\u5b69(\\u56fe)","thumb":"2012\\/1008\\/1349698313297.jpg","time":"1349684640"},{"contentid":73,"title":"\\u9ad8\\u7aef\\u8bbf\\u8c08\\uff5c\\u7a46\\u62c9\\u5229\\uff1a\\u6797\\u80af\\u5c06\\u5165\\u534e \\u672a\\u6765\\u6216\\u56fd\\u4ea7","url":"http:\\/\\/www.silkroad.news.cn\\/2012\\/0925\\/73.shtml","color":"","subtitle":"\\u9ad8\\u7aef\\u8bbf\\u8c08\\uff5c","suburl":"","description":"8\\u670828\\u65e5\\uff0c\\u5728\\u6797\\u80af\\u54c1\\u724c\\u7b2c\\u4e00\\u6b21\\u4eae\\u76f8\\u4e2d\\u56fd\\u4e4b\\u65f6\\uff0c\\u9762\\u5bf9\\u817e\\u8baf\\u6c7d\\u8f66\\uff0c\\u798f\\u7279\\u5168\\u7403CEO\\u7a46\\u62c9\\u5229\\u4e0d\\u4ec5\\u89e3\\u8bfb\\u4e86\\u798f\\u7279\\u5728\\u4e2d\\u56fd\\u7684\\u53d1\\u5c55\\u6218\\u7565\\uff0c\\u540c\\u65f6\\u5bf9\\u4e8e\\u4eba\\u624d\\u672c\\u571f\\u5316\\u7684\\u95ee\\u9898\\u4e5f\\u8bf4\\u51fa\\u4e86\\u81ea\\u5df1\\u7684\\u770b\\u6cd5\\u3002","thumb":"2012\\/0925\\/1348542108721.jpg","time":"1348542780"}]', NULL, NULL, NULL, '<div class="slide-css js-slide">\n    <div class="wrap">\n        <ul class="inner clearfix">\n        <!--{loop $data $k $r}-->\n            <li class="item">\n                <a href="{$r[url]}" target="_blank" title="{$r[title]}">\n                    <img src="{thumb($r[thumb],1000,600)}" class="thumb" alt="" />\n                </a>\n                <div class="text pos-a">\n                    <p>{$r[title]}</p>\n                </div>\n            </li>\n        <!--{/loop}-->\n        </ul>\n    </div>\n    <div class="helper">\n        <div class="mask-left"></div>\n        <div class="mask-right"></div>\n        <a href="#" class="prev icon40x40 icon-arrow-a-left hidden"></a>\n        <a href="#" class="next icon40x40 icon-arrow-a-right hidden"></a>\n    </div>\n</div>', 'html', NULL, 5, 0, 0, '{"system_fields":{"contentid":{"checked":"1","func":"intval"},"title":{"checked":"1","required":"1","min_length":"","max_length":""},"url":{"checked":"1"},"color":{"checked":"1"},"icon":{"checked":"1"},"iconsrc":{"checked":"1"},"subtitle":{"checked":"1","min_length":"","max_length":""},"suburl":{"checked":"1"},"thumb":{"checked":"1","width":"","height":""},"description":{"checked":"1","min_length":"","max_length":""},"time":{"checked":"1","func":"section_fields_strtotime"}},"custom_fields":{"text":[],"name":[]}}', 1426915215, 1426915214, 0, 0, 1426500131, 1, 1342579261, 1, '共 5 行，描述信息 150 汉字以内，大图规格 640 x 360', 1, 0, 'section/list.html', 0, 10),
(31, 14, 'auto', '内容页中部', '', '<script type="text/javascript" id="adm-87">\n(function() {\n   window.ADMBlocks = window.ADMBlocks || [];\n   ADMBlocks.push({\n       id : ''87'',  // 广告位id\n       width : ''660'',  // 宽\n       height : ''80'',  // 高\n       type : ''1''  // 类型\n   });\n   var h=document.getElementsByTagName(''head'')[0], s=document.createElement(''script'');\n   s.async=true; s.src=''http://ad.cmstop.cn/js/show.js'';\n   h && h.insertBefore(s,h.firstChild)\n})();\n</script>\n', NULL, NULL, NULL, '', 'html', NULL, 0, 300, 0, NULL, 1426731515, 1426731215, 0, 0, 1426731155, 1, 1342581700, 1, '', 1, 0, NULL, NULL, 10),
(33, 10, 'push', '幻灯片轮播', '[]', '[{"contentid":74,"title":" \\u5f20\\u53ec\\u5fe0\\uff1a\\u4e2d\\u56fd30\\u591a\\u5e74\\u5fcd\\u8fb1\\u8d1f\\u91cd\\u88ab\\u8d8a\\u5357\\u8bef\\u89e3\\u4e3a\\u5bb3\\u6015","url":"http:\\/\\/www.silkroad.news.cn\\/2012\\/1017\\/74.shtml","color":"","subtitle":" \\u5f20\\u53ec\\u5fe0\\uff1a","suburl":"","description":"\\u7279\\u522b\\u63a8\\u8350\\u817e\\u8baf\\u72ec\\u5bb6\\u680f\\u76ee\\uff1a\\u66f4\\u591a\\u7cbe\\u5f69\\u5c3d\\u5728\\u300a\\u53ec\\u5fe0\\u8bf4\\u519b\\u4e8b\\u300b\\n\\u8d8a\\u5357\\u4e0d\\u7ba1\\u662f\\u5728\\u7ecf\\u6d4e\\u4e0a\\u8fd8\\u662f\\u519b\\u4e8b\\u4e0a\\u8fdc\\u8fdc\\u843d\\u540e\\u4e8e\\u4e2d\\u56fd\\uff0c\\u90a3\\u5b83\\u4e3a\\u4ec0\\u4e48\\u8fd8\\u6709\\u81ea\\u4fe1\\u548c\\u80c6\\u91cf\\u5982\\u6b64\\u5f3a\\u786c\\u7684\\u5728\\u5357\\u6d77\\u5e26\\u5934\\u8ddf\\u4e2d\\u56fd\\u5bf9\\u6297\\u5462\\uff1f\\n\\u9996\\u5148\\u8d8a\\u5357\\u5b83\\u6709\\u8fd9\\u6837\\u7684\\u60c5\\u8282\\uff0c\\u5f3a\\u70c8\\u7684\\u6c11\\u65cf\\u4e3b\\u4e49\\u3002\\u4f60\\u770b\\u56fd\\u9645\\u4e0a\\u7684\\u5f88\\u591a\\u7684\\u95ee\\u9898\\uff0c\\u4f60\\u770b\\u5229\\u6bd4\\u4e9a\\u4e5f\\u597d\\uff0c\\u4f60\\u770b\\u963f\\u5bcc\\u6c57\\u4e5f\\u597d\\uff0c\\u4f60\\u770b\\u4f0a\\u62c9\\u514b\\u4e5f\\u597d\\uff0c\\u6bcf\\u4e00\\u4e2a\\u6c11\\u65cf\\u90fd\\u6709\\u6bcf\\u4e00\\u4e2a\\u6c11\\u65cf\\u7684\\u60c5\\u8282\\u3002","thumb":"2012\\/0925\\/1348546274802.jpg","time":"1350442140"},{"contentid":15,"title":"\\u4ee5\\u8272\\u5217\\u6218\\u673a\\u7a7a\\u88ad\\u52a0\\u6c99\\u5730\\u5e26\\u81f3\\u5c111\\u6b7b11\\u4f24(\\u56fe)","url":"http:\\/\\/www.silkroad.news.cn\\/2012\\/1010\\/15.shtml","color":"","subtitle":"\\u4ee5\\u8272\\u5217\\u6218\\u673a","suburl":"","description":"\\u4ee5\\u8272\\u5217\\u6218\\u673a\\u7a7a\\u88ad\\u52a0\\u6c99\\u5730\\u5e26\\u81f3\\u5c111\\u6b7b11\\u4f24(\\u56fe)","thumb":"2012\\/1008\\/1349698553263.jpg","time":"1349850060"},{"contentid":91,"title":" \\u5317\\u4eac\\u5e02\\u533a17\\u65e5\\u62e5\\u5835\\u8def\\u6bb5\\u5cf0\\u503c\\u8d85140\\u6761\\u521b\\u7eaa\\u5f55","url":"http:\\/\\/www.silkroad.news.cn\\/2012\\/1009\\/91.shtml","color":"","subtitle":" \\u5317\\u4eac\\u5e02\\u533a1","suburl":"","description":"9\\u670817\\u65e5\\uff0c\\u5317\\u4eac\\u5e02\\u533a\\u665a\\u9ad8\\u5cf0\\u62e5\\u5835\\u8def\\u6bb5\\u5cf0\\u503c\\u8d85140\\u6761\\uff0c\\u5df2\\u7ecf\\u8d85\\u8fc7\\u4eca\\u5e74\\u5e74\\u521d\\u56e0\\u5927\\u96ea\\u9020\\u621090\\u4f59\\u6761\\u62e5\\u5835\\u8def\\u6bb5\\u5cf0\\u503c\\u7684\\u7eaa\\u5f55\\u3002","thumb":"2012\\/1009\\/1349778701818.jpg","time":"1349778720"},{"contentid":57,"title":"\\u83ab\\u8a00\\u6751\\u4e0a\\u6625\\u6811\\u6210\\u8bfa\\u8d1d\\u5c14\\u6587\\u5b66\\u5956\\u70ed\\u95e8 \\u4e13\\u5bb6\\u8d28\\u7591\\u7092\\u4f5c","url":"http:\\/\\/www.silkroad.news.cn\\/2012\\/1009\\/57.shtml","color":"","subtitle":"\\u83ab\\u8a00\\u6751\\u4e0a\\u6625","suburl":"","description":"\\u53a6\\u95e8\\u5927\\u5b66\\u4eba\\u6587\\u5b66\\u9662\\u9662\\u957f\\u5468\\u5b81\\u8ba4\\u4e3a\\uff0c\\u5f53\\u524d\\u5bf9\\u4e8e\\u83ab\\u8a00\\u83b7\\u5956\\u7684\\u5404\\u79cd\\u731c\\u6d4b\\u66f4\\u591a\\u662f\\u4e00\\u79cd\\u65b0\\u95fb\\u7092\\u4f5c\\uff0c\\u5bf9\\u9881\\u5956\\u672c\\u8eab\\u6ca1\\u6709\\u592a\\u5927\\u610f\\u4e49\\u3002","thumb":"2012\\/1009\\/1349751503718.jpg","time":"1349751840"}]', NULL, NULL, NULL, '<!--{loop $data $k $r}-->\n<div class="fcon">\n    <a target="_blank" href="{$r[url]}" target="_blank"><img src="{thumb($r[thumb],660,380)}"></a>\n    <div class="shadow"><a target="_blank" href="{$r[url]}" target="_blank">{$r[title]}</a></div>\n</div>\n<!--{/loop}-->', 'html', NULL, 4, 0, 0, '{"system_fields":{"contentid":{"checked":"1","func":"intval"},"title":{"checked":"1","required":"1","min_length":"","max_length":""},"url":{"checked":"1"},"color":{"checked":"1"},"icon":{"checked":"1"},"iconsrc":{"checked":"1"},"subtitle":{"checked":"1","min_length":"","max_length":""},"suburl":{"checked":"1"},"thumb":{"checked":"1","width":"","height":""},"description":{"checked":"1","min_length":"","max_length":""},"time":{"checked":"1","func":"section_fields_strtotime"}},"custom_fields":{"text":[],"name":[]}}', 1426915472, 1426915471, 0, 0, 1426506594, 1, 1342586545, 1, '共 4 个专题，缩略图规格 200 x 100，标题长度 16 个汉字', 1, 0, 'section/list.html', 0, 10),
(34, 12, 'auto', '顶通 1000x80', '', '<script type="text/javascript" id="adm-81">\n(function() {\n   window.ADMBlocks = window.ADMBlocks || [];\n   ADMBlocks.push({\n       id : ''81'',  // 广告位id\n       width : ''1000'',  // 宽\n       height : ''80'',  // 高\n       type : ''1''  // 类型\n   });\n   var h=document.getElementsByTagName(''head'')[0], s=document.createElement(''script'');\n   s.async=true; s.src=''http://ad.cmstop.cn/js/show.js'';\n   h && h.insertBefore(s,h.firstChild)\n})();\n</script>\n', NULL, NULL, NULL, '', 'html', NULL, 0, 300, 0, NULL, 1426731507, 1426731207, 0, 0, 1426731129, 1, 1342594549, 1, '文章最终页顶通648x78尺寸广告', 1, 0, NULL, NULL, 10),
(35, 12, 'auto', '右侧 300x90', '', '<script type="text/javascript" id="adm-82">\n(function() {\n   window.ADMBlocks = window.ADMBlocks || [];\n   ADMBlocks.push({\n       id : ''82'',  // 广告位id\n       width : ''300'',  // 宽\n       height : ''90'',  // 高\n       type : ''1''  // 类型\n   });\n   var h=document.getElementsByTagName(''head'')[0], s=document.createElement(''script'');\n   s.async=true; s.src=''http://ad.cmstop.cn/js/show.js'';\n   h && h.insertBefore(s,h.firstChild)\n})();\n</script>\n', NULL, NULL, NULL, '', 'html', NULL, 0, 300, 0, NULL, 1426731508, 1426731208, 0, 0, 1426731133, 1, 1342595019, 1, '文章内容页顶通298x78尺寸广告', 1, 0, NULL, NULL, 10),
(36, 1, 'push', '娱乐频道推左侧图', '[]', '[{"contentid":61,"title":"\\u4e2d\\u56fd\\u4e2d\\u5b66\\u751f\\u9996\\u90e8\\u60c5\\u611f\\u7cfb\\u5217\\u7247\\u300a\\u804a\\u5929\\u2161\\uff1a\\u6210\\u957f\\u5b63\\u300b\\u9ad8\\u6e05\\u6b63\\u7247","url":"http:\\/\\/www.silkroad.news.cn\\/2012\\/1009\\/61.shtml","color":"","subtitle":"\\u4e2d\\u56fd\\u4e2d\\u5b66\\u751f","suburl":"","description":"\\u7531 \\u6df1\\u5733\\u5e02\\u7b2c\\u4e8c\\u804c\\u4e1a\\u6280\\u672f\\u5b66\\u6821 \\u51fa\\u54c1\\uff0c\\u552f\\u8bb0\\u5fc6\\u5f71\\u89c6\\u5de5\\u574a \\u6444\\u5236\\u7684\\u4e2d\\u56fd\\u4e2d\\u5b66\\u751f\\u9996\\u90e8\\u60c5\\u611f\\u7cfb\\u5217\\u7247\\u300a\\u804a\\u5929\\u2161\\uff1a\\u6210\\u957f\\u5b63\\u300b\\u3002","thumb":"2012\\/1009\\/1349755867505.jpg","time":"1349755680"}]', NULL, NULL, NULL, '<!--{loop $data $k $r}-->\n<div class="fl-l picture pos-r"><a href="{$r[url]}" target="_blank" title="{$r[title]}"><img src="{thumb($r[thumb], 320, 240)}" width="320" height="240" alt=""></a><p class="text ie-rgba"><a href="{$r[url]}" target="_blank" title="{$r[title]}">{$r[title]}</a></p></div>\n<!--{/loop}-->', 'html', NULL, 1, 300, 0, '{"system_fields":{"contentid":{"checked":"1","func":"intval"},"title":{"checked":"1","required":"1","min_length":"","max_length":""},"url":{"checked":"1"},"color":{"checked":"1"},"icon":{"checked":"1"},"iconsrc":{"checked":"1"},"subtitle":{"checked":"1","min_length":"","max_length":""},"suburl":{"checked":"1"},"thumb":{"checked":"1","width":"","height":""},"description":{"checked":"1","min_length":"","max_length":""},"time":{"checked":"1","func":"section_fields_strtotime"}},"custom_fields":{"text":[],"name":[]}}', 1426917537, 1426917236, 0, 0, 1426060456, 1, 1342601411, 1, '', 1, 0, 'section/list.html', 0, 10),
(37, 1, 'push', '娱乐频道列表', '[]', '[{"contentid":113,"icon":"blank","iconsrc":"","title":"\\u7f8e29\\u5c81\\u540c\\u6027\\u604b\\u534e\\u88d4\\u526f\\u5e02\\u957f\\u88ab\\u8fb1\\u9a82","color":"","url":"http:\\/\\/www.silkroad.news.cn\\/2012\\/1016\\/113.shtml","subtitle":"\\u7f8e29\\u5c81\\u540c\\u6027","suburl":"","thumb":"2014\\/0313\\/1394709814498.jpg","description":"\\u636e\\u7f8e\\u56fd\\u300a\\u4fa8\\u62a5\\u300b\\u7f51\\u7ad910\\u670815\\u65e5\\u63f4\\u5f15\\u5168\\u7f8e\\u5e7f\\u64ad\\u516c\\u53f8\\uff08NBC\\uff09\\u62a5\\u9053\\uff0c\\u7f8e\\u56fd\\u52a0\\u5dde\\u5357\\u6e7e\\u574e\\u8d1d\\u5c14\\u5e02\\u9996\\u4f4d\\u516c\\u5f00\\u540c\\u6027\\u604b\\u8eab\\u4efd\\u7684\\u534e\\u88d4\\u526f\\u5e02\\u957f\\u7f57\\u8fbe\\u4f26\\uff08Evan Low\\uff09\\u65e5\\u524d\\u906d\\u4e00\\u540d\\u4e0d\\u660e\\u8eab\\u4efd\\u5973\\u5b50\\u7684\\u8fb1\\u9a82\\uff0c\\u8be5\\u5973\\u5b50\\u58f0\\u79f0\\u8ba9\\u5176\\u201c\\u6eda\\u56de\\u4e2d\\u56fd\\u201d\\uff0c\\u5e76\\u62ff\\u624b\\u505a\\u51fa\\u624b\\u67aa\\u72b6\\uff0c\\u5bf9\\u7f57\\u8fbe\\u4f26\\u8fdb\\u884c\\u4eba\\u8eab\\u5a01\\u80c1","time":1350368880},{"contentid":13,"title":"\\u7f8e\\u56fd\\u516c\\u53f8\\u53d1\\u5c04\\u201c\\u9f99\\u201d\\u98de\\u8239 \\u9996\\u5411\\u56fd\\u9645\\u7a7a\\u95f4\\u7ad9\\u8fd0\\u8d27","url":"http:\\/\\/www.silkroad.news.cn\\/2012\\/1010\\/13.shtml","color":"","subtitle":"\\u7f8e\\u56fd\\u516c\\u53f8\\u53d1","suburl":"","description":"\\u7f8e\\u56fd\\u516c\\u53f8\\u53d1\\u5c04\\u201c\\u9f99\\u201d\\u98de\\u8239 \\u9996\\u5411\\u56fd\\u9645\\u7a7a\\u95f4\\u7ad9\\u8fd0\\u8d27","thumb":"2012\\/1008\\/1349698479695.jpg","time":"1349850060"},{"contentid":90,"title":" \\u5168\\u56fd141\\u4e07\\u4eba\\u53c2\\u52a0\\"\\u56fd\\u8003\\" \\u4e891.6\\u4e07\\u5c97\\u4f4d","url":"http:\\/\\/www.silkroad.news.cn\\/2012\\/1009\\/90.shtml","color":"","subtitle":" \\u5168\\u56fd141\\u4e07","suburl":"","description":"\\u56fd\\u5bb6\\u516c\\u52a1\\u5458\\u62db\\u5f55\\u516c\\u5171\\u79d1\\u76ee\\u7b14\\u8bd5\\u5f00\\u8003\\uff0c\\u4eca\\u5e74\\u8003\\u751f\\u8d85\\u8fc7140\\u4e07\\uff0c\\u5c06\\u7ade\\u4e89137\\u4e2a\\u62db\\u8003\\u5355\\u4f4d\\u76841.6\\u4e07\\u4f59\\u5c97\\u4f4d\\uff0c\\u5e73\\u5747\\u7ade\\u4e89\\u6bd4\\u8fbe\\u523088\\u6bd41\\u3002\\u636e\\u7edf\\u8ba1\\uff0c\\u4eca\\u5e74\\u662f\\u7b14\\u8bd5\\u8d44\\u683c\\u5ba1\\u6838\\u8003\\u751f\\u4eba\\u6570\\u8fde\\u7eed\\u7b2c3\\u5e74\\u7a81\\u7834\\u767e\\u4e07\\u4eba\\uff0c\\u800c2003\\u5e74\\u8fd9\\u4e00\\u6570\\u5b57\\u4ec5\\u4e3a8.7\\u4e07\\u3002","thumb":"2012\\/1009\\/1349778567239.jpg","time":"1349778540"},{"contentid":86,"title":"\\u5f20\\u53ec\\u5fe0\\uff1a\\u7f8e\\u56fd\\u901a\\u8fc7\\u4f0a\\u62c9\\u514b\\u6218\\u4e89\\u5f97\\u5230\\u7684\\u6bd4\\u5931\\u53bb\\u7684\\u591a","url":"http:\\/\\/www.silkroad.news.cn\\/2012\\/1009\\/86.shtml","color":"","subtitle":"\\u5f20\\u53ec\\u5fe0\\uff1a\\u7f8e","suburl":"","description":"12\\u670818\\u65e5\\u9a7b\\u4f0a\\u7f8e\\u519b\\u6700 \\u540e\\u4e00\\u6279\\u7f8e\\u519b\\u64a4\\u79bb\\u4f0a\\u62c9\\u514b\\uff0c\\u8fd9\\u8bc1\\u660e\\u5386\\u65f69\\u5e74\\u7684\\u4f0a\\u62c9\\u514b\\u6218\\u4e89\\u6b63\\u5f0f\\u5ba3\\u544a\\u7ed3\\u675f\\u4e86\\uff0c\\u7f8e\\u519b\\u8d70\\u540e\\u7a76\\u7adf\\u4f1a\\u7559\\u4e0b\\u4e00\\u4e2a\\u600e\\u4e48\\u6837\\u7684\\u4f0a\\u62c9\\u514b\\u5462\\uff1f\\u7f8e\\u519b\\u4e0b\\u4e00\\u4e2a\\u76ee\\u6807\\u53c8\\u6307\\u5411\\u8c01\\uff1f\\u4eca\\u5929\\u5c31\\u7f8e\\u519b\\u64a4\\u51fa\\u4f0a \\u62c9\\u514b\\u7684\\u8bdd\\u9898\\u91c7\\u8bbf\\u4e00\\u4e0b\\u8457\\u540d\\u7684\\u519b\\u4e8b\\u4e13\\u5bb6\\u5f20\\u53ec\\u5fe0\\u6559\\u6388","thumb":"2012\\/1009\\/1349775628390.jpg","time":"1349775600"},{"contentid":85,"title":"\\u5f20\\u53ec\\u5fe0\\uff1aF-35\\u4ee5\\u7edd\\u5bf9\\u4f18\\u52bf\\u6218\\u80dcF\\/A-18\\u4e0e\\u53f0\\u98ce\\u8d62\\u5f97\\u65e5\\u672c\\u8ba2\\u5355","url":"http:\\/\\/www.silkroad.news.cn\\/2012\\/1009\\/85.shtml","color":"","subtitle":"\\u5f20\\u53ec\\u5fe0\\uff1aF-","suburl":"","description":"\\u65e5\\u672c\\u653f\\u5e9c\\u4e8e20\\u65e5\\u6b63\\u5f0f\\u51b3\\u5b9a\\u5c06F-35\\u9009\\u4e3a\\u822a\\u7a7a\\u81ea\\u536b\\u961f\\u4e0b\\u4e00\\u4ee3\\u4e3b\\u529b\\u6218\\u673a(FX)\\uff0c\\u9632\\u536b\\u7701\\u5f53\\u5929\\u516c\\u5e03\\u4e86\\u5bf9\\u5404\\u8bc4\\u4f30\\u9879\\u76ee\\u7684\\u6253\\u5206\\u8bc4\\u5ba1\\u7ed3\\u679c\\u3002\\u9690\\u5f62\\u6027\\u4f18\\u5f02\\u7684\\u7b2c\\u4e94 \\u4ee3\\u6218\\u673aF-35\\u5728\\u6027\\u80fd\\u3001\\u7ecf\\u8d39\\u3001\\u4fdd\\u517b\\u7b49\\u5404\\u6307\\u6807\\u4e2d\\u5747\\u83b7\\u6700\\u9ad8\\u5206\\u3001\\u4ee5\\u201c\\u538b\\u5012\\u6027\\u4f18\\u52bf\\u201d\\u6218\\u80dc\\u4e86\\u7ade\\u4e89\\u5bf9\\u624bF\\/A-18\\u53ca\\u201c\\u53f0\\u98ce\\u201d\\u6218\\u673a\\u3002","thumb":"2012\\/1009\\/1349775448375.jpg","time":"1349775540"},{"contentid":9,"title":"2012\\u5e74\\u8bfa\\u8d1d\\u5c14\\u751f\\u7406\\u5b66\\u6216\\u533b\\u5b66\\u5956\\u63ed\\u6653","url":"http:\\/\\/www.silkroad.news.cn\\/2012\\/1008\\/9.shtml","color":"","subtitle":"2012\\u5e74\\u8bfa\\u8d1d","suburl":"","description":"2012\\u5e74\\u8bfa\\u8d1d\\u5c14\\u751f\\u7406\\u5b66\\u6216\\u533b\\u5b66\\u5956\\u63ed\\u6653","thumb":"2012\\/1008\\/1349697651183.jpg","time":"1349688840"}]', NULL, NULL, NULL, '<!--{loop $data $k $r}-->\n<!--{if $k==0}-->\n<h3 class="h3"><a href="{$r[url]}" target="_blank" title="{$r[title]}">{$r[title]}</a></h3>\n<p class="summary">{str_natcut($r[description], 45, ''...'')}</p>\n<div class="hr10"></div>\n<!--{/if}-->\n<!--{/loop}-->\n<ul class="ul">\n    <!--{loop $data $k $r}-->\n    <!--{if $k > 0}-->\n    <li><a href="{$r[url]}" target="_blank" title="{$r[title]}">{$r[title]}</a></li>\n    <!--{/if}-->\n    <!--{/loop}-->\n</ul>', 'html', NULL, 6, 300, 0, '{"system_fields":{"contentid":{"checked":"1","func":"intval"},"title":{"checked":"1","required":"1","min_length":"","max_length":""},"url":{"checked":"1"},"color":{"checked":"1"},"icon":{"checked":"1"},"iconsrc":{"checked":"1"},"subtitle":{"checked":"1","min_length":"","max_length":""},"suburl":{"checked":"1"},"thumb":{"checked":"1","width":"","height":""},"description":{"checked":"1","min_length":"","max_length":""},"time":{"checked":"1","func":"section_fields_strtotime"}},"custom_fields":{"text":[],"name":[]}}', 1426917537, 1426917236, 0, 0, 1426562754, 1, 1342601513, 1, '', 1, 0, 'section/list.html', 0, 10),
(38, 1, 'push', '娱乐频道片花欣赏', '[]', '[{"contentid":87,"title":"\\u5f20\\u53ec\\u5fe0\\uff1a\\u6211\\u519b\\u8230\\u8247\\u7684\\u5c16\\u7aef\\u6280\\u672f\\u843d\\u540e\\u7f8e\\u56fd10-20\\u5e74","url":"http:\\/\\/www.silkroad.news.cn\\/2012\\/1009\\/87.shtml","color":"","subtitle":"\\u5f20\\u53ec\\u5fe0\\uff1a\\u6211","suburl":"","description":"\\u4eca\\u5e74\\u768412\\u670826\\u65e5\\u662f \\u4e2d\\u56fd\\u4eba\\u6c11\\u6d77\\u519b\\u8d74\\u4e9a\\u4e01\\u6e7e\\u62a4\\u822a\\u6574\\u6574\\u4e09\\u5468\\u5e74\\u7eaa\\u5ff5\\u65e5\\uff0c\\u4e2d\\u56fd\\u6d77\\u519b\\u53ef\\u4ee5\\u8bf4\\u5411\\u4e16\\u754c\\u5c55\\u793a\\u4e86\\u6587\\u660e\\u4e4b\\u5e08\\uff0c\\u5a01\\u6b66\\u4e4b\\u5e08\\u7684\\u5149\\u8f89\\u7684\\u5f62\\u8c61\\u3002\\u56de\\u987e\\u8fd9\\u4e09\\u5e74\\u7684\\u62a4\\u822a\\u884c\\u52a8\\uff0c\\u4e2d\\u56fd\\u6d77\\u519b\\u7ecf\\u5386\\u4e86\\u54ea\\u4e9b\\u6ce2 \\u6298\\uff0c\\u6211\\u4eec\\u83b7\\u5f97\\u4e86\\u54ea\\u4e9b\\u7ecf\\u9a8c\\uff0c\\u53c8\\u68c0\\u9a8c\\u4e86\\u54ea\\u4e9b\\u4e2d\\u56fd\\u6d77\\u519b\\u7684\\u6b66\\u5668\\u88c5\\u5907\\u5462\\uff1f\\u4eca\\u5929\\u6211\\u4eec\\u5c31\\u8bf7\\u519b\\u4e8b\\u4e13\\u5bb6\\u5f20\\u53ec\\u5fe0\\u6559\\u6388\\u8ddf\\u60a8\\u4e00\\u8d77\\u6765\\u56de\\u987e\\u548c\\u89e3\\u8bfb\\u4e00\\u4e0b\\u6d77\\u519b\\u62a4\\u822a\\u4e09\\u5468\\u5e74\\u3002","thumb":"2012\\/1009\\/1349775718609.jpg","time":"1349775660"},{"contentid":79,"title":"\\u4e2d\\u56fd\\u4eba\\u5728\\u5229\\u6bd4\\u4e9a","url":"http:\\/\\/www.silkroad.news.cn\\/2012\\/0926\\/79.shtml","color":"","subtitle":"\\u4e2d\\u56fd\\u4eba\\u5728\\u5229","suburl":"","description":"\\u4e2d\\u56fd\\u77f3\\u6cb9\\u5229\\u6bd4\\u4e9a\\u9879\\u76ee\\u5458\\u5de5\\u505a\\u5ba2\\u817e\\u8baf","thumb":"2012\\/0926\\/1348623766912.png","time":"1348623840"}]', NULL, NULL, NULL, '<!--{loop $data $k $r}-->\n                      <div class="v-pic">\n                           <div class="thumb">\n                               <div class="opa"></div>\n                               <a href="{$r[url]}" class="ico"></a>\n                              <span class="info">12:00</span>\n                               <a href="{$r[url]}" title="{$r[title]}"><img src="{thumb($r[thumb],120,160,1,null,1)}" alt="{$r[title]}" width="120" height="160"></a>\n                            </div>\n                            <div class="title"><a href="{$r[url]}" title="{$r[title]}">{$r[title]}</a></div>\n                      </div>\n<!--{/loop}-->\n', 'html', NULL, 2, 300, 0, NULL, 1426917537, 1426917236, 0, 0, 1426060456, 1, 1342601578, 1, '', 1, 0, 'section/list.html', 0, 10),
(40, 1, 'push', '汽车频道左侧图', '[]', '[{"contentid":87,"title":"\\u5f20\\u53ec\\u5fe0\\uff1a\\u6211\\u519b\\u8230\\u8247\\u7684\\u5c16\\u7aef\\u6280\\u672f\\u843d\\u540e\\u7f8e\\u56fd10-20\\u5e74","url":"http:\\/\\/www.silkroad.news.cn\\/2012\\/1009\\/87.shtml","color":"","subtitle":"\\u5f20\\u53ec\\u5fe0\\uff1a\\u6211","suburl":"","description":"\\u4eca\\u5e74\\u768412\\u670826\\u65e5\\u662f \\u4e2d\\u56fd\\u4eba\\u6c11\\u6d77\\u519b\\u8d74\\u4e9a\\u4e01\\u6e7e\\u62a4\\u822a\\u6574\\u6574\\u4e09\\u5468\\u5e74\\u7eaa\\u5ff5\\u65e5\\uff0c\\u4e2d\\u56fd\\u6d77\\u519b\\u53ef\\u4ee5\\u8bf4\\u5411\\u4e16\\u754c\\u5c55\\u793a\\u4e86\\u6587\\u660e\\u4e4b\\u5e08\\uff0c\\u5a01\\u6b66\\u4e4b\\u5e08\\u7684\\u5149\\u8f89\\u7684\\u5f62\\u8c61\\u3002\\u56de\\u987e\\u8fd9\\u4e09\\u5e74\\u7684\\u62a4\\u822a\\u884c\\u52a8\\uff0c\\u4e2d\\u56fd\\u6d77\\u519b\\u7ecf\\u5386\\u4e86\\u54ea\\u4e9b\\u6ce2 \\u6298\\uff0c\\u6211\\u4eec\\u83b7\\u5f97\\u4e86\\u54ea\\u4e9b\\u7ecf\\u9a8c\\uff0c\\u53c8\\u68c0\\u9a8c\\u4e86\\u54ea\\u4e9b\\u4e2d\\u56fd\\u6d77\\u519b\\u7684\\u6b66\\u5668\\u88c5\\u5907\\u5462\\uff1f\\u4eca\\u5929\\u6211\\u4eec\\u5c31\\u8bf7\\u519b\\u4e8b\\u4e13\\u5bb6\\u5f20\\u53ec\\u5fe0\\u6559\\u6388\\u8ddf\\u60a8\\u4e00\\u8d77\\u6765\\u56de\\u987e\\u548c\\u89e3\\u8bfb\\u4e00\\u4e0b\\u6d77\\u519b\\u62a4\\u822a\\u4e09\\u5468\\u5e74\\u3002","thumb":"2012\\/1009\\/1349775718609.jpg","time":"1349775660"}]', NULL, NULL, NULL, '<!--{loop $data $k $r}-->\n<div class="fl-l picture pos-r"><a href="{$r[url]}" target="_blank" title="{$r[title]}"><img src="{thumb($r[thumb], 320, 240)}" width="320" height="240" alt=""></a><p class="text ie-rgba"><a href="{$r[url]}" target="_blank" title="{$r[title]}">{$r[title]}</a></p></div>\n<!--{/loop}-->', 'html', NULL, 1, 300, 0, '{"system_fields":{"contentid":{"checked":"1","func":"intval"},"title":{"checked":"1","required":"1","min_length":"","max_length":""},"url":{"checked":"1"},"color":{"checked":"1"},"icon":{"checked":"1"},"iconsrc":{"checked":"1"},"subtitle":{"checked":"1","min_length":"","max_length":""},"suburl":{"checked":"1"},"thumb":{"checked":"1","width":"","height":""},"description":{"checked":"1","min_length":"","max_length":""},"time":{"checked":"1","func":"section_fields_strtotime"}},"custom_fields":{"text":[],"name":[]}}', 1426917537, 1426917236, 0, 0, 1426060456, 1, 1342517911, 1, '权重为95。5条 第一条为图文描述', 1, 0, 'section/list.html', 0, 10),
(41, 9, 'html', '内容页_下期链接', '<a href="">这里文字多了处理起来肯定不爽怎么办</a>', '<a href="#">这里文字多了处理起来肯定不爽</a>', NULL, NULL, NULL, '', 'html', 0, 0, 0, 0, NULL, 1426060817, 1426060817, 0, 0, 1426060840, 1, 1342604596, 1, '这里只是一句话的标题链接', 0, 0, NULL, NULL, 10),
(42, 1, 'push', '汽车频道列表', '[]', '[{"contentid":18,"title":"\\u97e9\\u56fd\\u5bfc\\u5f39\\u5c04\\u7a0b\\u53ef\\u8fbe\\u4e2d\\u65e5\\u4fc4\\u9886\\u571f \\u53ef\\u80fd\\u52a0\\u5267\\u519b\\u5907\\u7ade\\u8d5b\\u3010\\u5206\\u9875\\u3011","url":"http:\\/\\/www.silkroad.news.cn\\/2012\\/1010\\/18.shtml","color":"","subtitle":"\\u97e9\\u56fd\\u5bfc\\u5f39\\u5c04","suburl":"","description":"\\u97e9\\u56fd\\u5bfc\\u5f39\\u5c04\\u7a0b\\u53ef\\u8fbe\\u4e2d\\u65e5\\u4fc4\\u9886\\u571f \\u53ef\\u80fd\\u52a0\\u5267\\u519b\\u5907\\u7ade\\u8d5b\\u3010\\u5206\\u9875\\u3011","thumb":"2012\\/1008\\/1349698491750.jpg","time":"1349850060"},{"contentid":86,"title":"\\u5f20\\u53ec\\u5fe0\\uff1a\\u7f8e\\u56fd\\u901a\\u8fc7\\u4f0a\\u62c9\\u514b\\u6218\\u4e89\\u5f97\\u5230\\u7684\\u6bd4\\u5931\\u53bb\\u7684\\u591a","url":"http:\\/\\/www.silkroad.news.cn\\/2012\\/1009\\/86.shtml","color":"","subtitle":"\\u5f20\\u53ec\\u5fe0\\uff1a\\u7f8e","suburl":"","description":"12\\u670818\\u65e5\\u9a7b\\u4f0a\\u7f8e\\u519b\\u6700 \\u540e\\u4e00\\u6279\\u7f8e\\u519b\\u64a4\\u79bb\\u4f0a\\u62c9\\u514b\\uff0c\\u8fd9\\u8bc1\\u660e\\u5386\\u65f69\\u5e74\\u7684\\u4f0a\\u62c9\\u514b\\u6218\\u4e89\\u6b63\\u5f0f\\u5ba3\\u544a\\u7ed3\\u675f\\u4e86\\uff0c\\u7f8e\\u519b\\u8d70\\u540e\\u7a76\\u7adf\\u4f1a\\u7559\\u4e0b\\u4e00\\u4e2a\\u600e\\u4e48\\u6837\\u7684\\u4f0a\\u62c9\\u514b\\u5462\\uff1f\\u7f8e\\u519b\\u4e0b\\u4e00\\u4e2a\\u76ee\\u6807\\u53c8\\u6307\\u5411\\u8c01\\uff1f\\u4eca\\u5929\\u5c31\\u7f8e\\u519b\\u64a4\\u51fa\\u4f0a \\u62c9\\u514b\\u7684\\u8bdd\\u9898\\u91c7\\u8bbf\\u4e00\\u4e0b\\u8457\\u540d\\u7684\\u519b\\u4e8b\\u4e13\\u5bb6\\u5f20\\u53ec\\u5fe0\\u6559\\u6388","thumb":"2012\\/1009\\/1349775628390.jpg","time":"1349775600"},{"contentid":70,"title":"\\u5317\\u4ed1\\u65b0\\u95fb","url":"http:\\/\\/www.silkroad.news.cn\\/2012\\/1009\\/70.shtml","color":"","subtitle":"\\u5317\\u4ed1\\u65b0\\u95fb","suburl":"","description":"\\u5317\\u4ed1\\u65b0\\u95fb","thumb":"http:\\/\\/media.cmstop.com\\/pic\\/20120516\\/1b757273f8ae0dd176f2d02c94f3de67-1.jpg","time":"1349769430"},{"contentid":61,"title":"\\u4e2d\\u56fd\\u4e2d\\u5b66\\u751f\\u9996\\u90e8\\u60c5\\u611f\\u7cfb\\u5217\\u7247\\u300a\\u804a\\u5929\\u2161\\uff1a\\u6210\\u957f\\u5b63\\u300b\\u9ad8\\u6e05\\u6b63\\u7247","url":"http:\\/\\/www.silkroad.news.cn\\/2012\\/1009\\/61.shtml","color":"","subtitle":"\\u4e2d\\u56fd\\u4e2d\\u5b66\\u751f","suburl":"","description":"\\u7531 \\u6df1\\u5733\\u5e02\\u7b2c\\u4e8c\\u804c\\u4e1a\\u6280\\u672f\\u5b66\\u6821 \\u51fa\\u54c1\\uff0c\\u552f\\u8bb0\\u5fc6\\u5f71\\u89c6\\u5de5\\u574a \\u6444\\u5236\\u7684\\u4e2d\\u56fd\\u4e2d\\u5b66\\u751f\\u9996\\u90e8\\u60c5\\u611f\\u7cfb\\u5217\\u7247\\u300a\\u804a\\u5929\\u2161\\uff1a\\u6210\\u957f\\u5b63\\u300b\\u3002","thumb":"2012\\/1009\\/1349755867505.jpg","time":"1349755680"},{"contentid":30,"title":"\\u7ae0\\u5b50\\u6021\\u81ea\\u5632\\u201c\\u88ab\\u7ed3\\u5a5a\\u201d \\u7f51\\u53cb\\u591a\\u9001\\u795d\\u798f\\u76fc\\u6210\\u771f","url":"http:\\/\\/www.silkroad.news.cn\\/2012\\/1008\\/30.shtml","color":"","subtitle":"\\u7ae0\\u5b50\\u6021\\u81ea\\u5632","suburl":"","description":"\\u7ae0\\u5b50\\u6021\\u81ea\\u5632\\u201c\\u88ab\\u7ed3\\u5a5a\\u201d \\u7f51\\u53cb\\u591a\\u9001\\u795d\\u798f\\u76fc\\u6210\\u771f","thumb":"2012\\/1008\\/1349697594823.jpg","time":"1349692920"},{"contentid":9,"title":"2012\\u5e74\\u8bfa\\u8d1d\\u5c14\\u751f\\u7406\\u5b66\\u6216\\u533b\\u5b66\\u5956\\u63ed\\u6653","url":"http:\\/\\/www.silkroad.news.cn\\/2012\\/1008\\/9.shtml","color":"","subtitle":"2012\\u5e74\\u8bfa\\u8d1d","suburl":"","description":"2012\\u5e74\\u8bfa\\u8d1d\\u5c14\\u751f\\u7406\\u5b66\\u6216\\u533b\\u5b66\\u5956\\u63ed\\u6653","thumb":"2012\\/1008\\/1349697651183.jpg","time":"1349688840"}]', NULL, NULL, NULL, '<!--{loop $data $k $r}-->\n<!--{if $k==0}-->\n<h3 class="h3"><a href="{$r[url]}" target="_blank" title="{$r[title]}">{$r[title]}</a></h3>\n<p class="summary">{str_natcut($r[description], 45, ''...'')}</p>\n<div class="hr10"></div>\n<!--{/if}-->\n<!--{/loop}-->\n<ul class="ul">\n    <!--{loop $data $k $r}-->\n    <!--{if $k > 0}-->\n    <li><a href="{$r[url]}" target="_blank" title="{$r[title]}">{$r[title]}</a></li>\n    <!--{/if}-->\n    <!--{/loop}-->\n</ul>', 'html', NULL, 6, 300, 0, '{"system_fields":{"contentid":{"checked":"1","func":"intval"},"title":{"checked":"1","required":"1","min_length":"","max_length":""},"url":{"checked":"1"},"color":{"checked":"1"},"icon":{"checked":"1"},"iconsrc":{"checked":"1"},"subtitle":{"checked":"1","min_length":"","max_length":""},"suburl":{"checked":"1"},"thumb":{"checked":"1","width":"","height":""},"description":{"checked":"1","min_length":"","max_length":""},"time":{"checked":"1","func":"section_fields_strtotime"}},"custom_fields":{"text":[],"name":[]}}', 1426917537, 1426917236, 0, 0, 1426508730, 1, 1342518182, 1, '权重60~90以上', 1, 0, 'section/list.html', 0, 10);
INSERT INTO `cmstop_section` (`sectionid`, `pageid`, `type`, `name`, `origdata`, `data`, `url`, `method`, `args`, `template`, `output`, `width`, `rows`, `frequency`, `check`, `fields`, `nextupdate`, `published`, `locked`, `lockedby`, `updated`, `updatedby`, `created`, `createdby`, `description`, `status`, `list_enabled`, `list_template`, `list_pagesize`, `list_pages`) VALUES
(43, 4, 'push', '推荐组图', '[]', '[{"contentid":71,"title":"\\u5b9c\\u660c\\u4e07\\u4eba\\u76f8\\u4eb2\\u8282 \\u573a\\u9762\\u5341\\u5206\\u706b\\u7206","url":"http:\\/\\/www.silkroad.news.cn\\/2012\\/1017\\/71.shtml","color":"","subtitle":"\\u5b9c\\u660c\\u4e07\\u4eba\\u76f8","suburl":"","description":"\\u5b9c\\u660c\\u4e07\\u4eba\\u76f8\\u4eb2\\u8282","thumb":"http:\\/\\/media.cmstop.com\\/pic\\/20120412\\/ce725921f968c5a3449b7f44a4a8d27c-1.jpg","time":"1350442188"},{"contentid":113,"title":"\\u7f8e29\\u5c81\\u540c\\u6027\\u604b\\u534e\\u88d4\\u526f\\u5e02\\u957f\\u88ab\\u8fb1\\u9a82\\u201c\\u6eda\\u56de\\u4e2d\\u56fd\\u201d\\u3010\\u5fae\\u535a\\u6302\\u4ef6\\u3011","url":"http:\\/\\/www.silkroad.news.cn\\/2012\\/1016\\/113.shtml","color":"","subtitle":"\\u7f8e29\\u5c81\\u540c\\u6027","suburl":"","description":"\\u636e\\u7f8e\\u56fd\\u300a\\u4fa8\\u62a5\\u300b\\u7f51\\u7ad910\\u670815\\u65e5\\u63f4\\u5f15\\u5168\\u7f8e\\u5e7f\\u64ad\\u516c\\u53f8\\uff08NBC\\uff09\\u62a5\\u9053\\uff0c\\u7f8e\\u56fd\\u52a0\\u5dde\\u5357\\u6e7e\\u574e\\u8d1d\\u5c14\\u5e02\\u9996\\u4f4d\\u516c\\u5f00\\u540c\\u6027\\u604b\\u8eab\\u4efd\\u7684\\u534e\\u88d4\\u526f\\u5e02\\u957f\\u7f57\\u8fbe\\u4f26\\uff08Evan Low\\uff09\\u65e5\\u524d\\u906d\\u4e00\\u540d\\u4e0d\\u660e\\u8eab\\u4efd\\u5973\\u5b50\\u7684\\u8fb1\\u9a82\\uff0c\\u8be5\\u5973\\u5b50\\u58f0\\u79f0\\u8ba9\\u5176\\u201c\\u6eda\\u56de\\u4e2d\\u56fd\\u201d\\uff0c\\u5e76\\u62ff\\u624b\\u505a\\u51fa\\u624b\\u67aa\\u72b6\\uff0c\\u5bf9\\u7f57\\u8fbe\\u4f26\\u8fdb\\u884c\\u4eba\\u8eab\\u5a01\\u80c1","thumb":"2014\\/0313\\/1394709814498.jpg","time":"1350368880"},{"contentid":14,"title":"\\u67e5\\u97e6\\u65af\\u8d62\\u5f97\\u59d4\\u5185\\u745e\\u62c9\\u603b\\u7edf\\u5927\\u9009 \\u8fde\\u7eed\\u6267\\u653f\\u8fd114\\u5e74","url":"http:\\/\\/www.silkroad.news.cn\\/2012\\/1010\\/14.shtml","color":"","subtitle":"\\u67e5\\u97e6\\u65af\\u8d62\\u5f97","suburl":"","description":"\\u67e5\\u97e6\\u65af\\u8d62\\u5f97\\u59d4\\u5185\\u745e\\u62c9\\u603b\\u7edf\\u5927\\u9009 \\u8fde\\u7eed\\u6267\\u653f\\u8fd114\\u5e74","thumb":"2012\\/1008\\/1349698537197.jpg","time":"1349850101"},{"contentid":16,"title":"\\u67e5\\u97e6\\u65af\\u8d62\\u5f97\\u59d4\\u5185\\u745e\\u62c9\\u603b\\u7edf\\u5927\\u9009 \\u5df2\\u8fde\\u7eed\\u6267\\u653f\\u8fd114\\u5e74","url":"http:\\/\\/www.silkroad.news.cn\\/2012\\/1010\\/16.shtml","color":"","subtitle":"\\u67e5\\u97e6\\u65af\\u8d62\\u5f97","suburl":"","description":"\\u67e5\\u97e6\\u65af\\u8d62\\u5f97\\u59d4\\u5185\\u745e\\u62c9\\u603b\\u7edf\\u5927\\u9009 \\u5df2\\u8fde\\u7eed\\u6267\\u653f\\u8fd114\\u5e74","thumb":"2012\\/1008\\/1349698606100.jpg","time":"1349850101"},{"contentid":5,"title":"\\u5317\\u4eac\\u9ad8\\u901f\\u5783\\u573e8\\u5929\\u8fbe\\u767e\\u5428 \\u5783\\u573e\\u591a\\u4e3a\\u5e9f\\u7eb8\\u77ff\\u6cc9\\u6c34\\u74f6","url":"http:\\/\\/www.silkroad.news.cn\\/2012\\/1010\\/5.shtml","color":"","subtitle":"\\u5317\\u4eac\\u9ad8\\u901f\\u5783","suburl":"","description":"\\u5317\\u4eac\\u9ad8\\u901f\\u5783\\u573e8\\u5929\\u8fbe\\u767e\\u5428 \\u5783\\u573e\\u591a\\u4e3a\\u5e9f\\u7eb8\\u77ff\\u6cc9\\u6c34\\u74f6","thumb":"2012\\/1008\\/1349698511700.jpg","time":"1349850060"},{"contentid":7,"title":"\\u53f0\\u6e7e\\u7834\\u4e07\\u4eba\\u8054\\u7f72\\u518d\\u8bae\\u58eb\\u5175\\u51a4\\u6b7b\\u6848\\u3010\\u89c6\\u9891\\u6302\\u4ef6\\u3011","url":"http:\\/\\/www.silkroad.news.cn\\/2012\\/1010\\/7.shtml","color":"","subtitle":"\\u53f0\\u6e7e\\u7834\\u4e07\\u4eba","suburl":"","description":"\\u53f0\\u6e7e\\u7834\\u4e07\\u4eba\\u8054\\u7f72\\u518d\\u8bae\\u58eb\\u5175\\u51a4\\u6b7b\\u6848\\u3010\\u89c6\\u9891\\u6302\\u4ef6\\u3011","thumb":"2012\\/1008\\/1349697830754.jpg","time":"1349850060"},{"contentid":18,"title":"\\u97e9\\u56fd\\u5bfc\\u5f39\\u5c04\\u7a0b\\u53ef\\u8fbe\\u4e2d\\u65e5\\u4fc4\\u9886\\u571f \\u53ef\\u80fd\\u52a0\\u5267\\u519b\\u5907\\u7ade\\u8d5b\\u3010\\u5206\\u9875\\u3011","url":"http:\\/\\/www.silkroad.news.cn\\/2012\\/1010\\/18.shtml","color":"","subtitle":"\\u97e9\\u56fd\\u5bfc\\u5f39\\u5c04","suburl":"","description":"\\u97e9\\u56fd\\u5bfc\\u5f39\\u5c04\\u7a0b\\u53ef\\u8fbe\\u4e2d\\u65e5\\u4fc4\\u9886\\u571f \\u53ef\\u80fd\\u52a0\\u5267\\u519b\\u5907\\u7ade\\u8d5b\\u3010\\u5206\\u9875\\u3011","thumb":"2012\\/1008\\/1349698491750.jpg","time":"1349850060"},{"contentid":91,"title":" \\u5317\\u4eac\\u5e02\\u533a17\\u65e5\\u62e5\\u5835\\u8def\\u6bb5\\u5cf0\\u503c\\u8d85140\\u6761\\u521b\\u7eaa\\u5f55","url":"http:\\/\\/www.silkroad.news.cn\\/2012\\/1009\\/91.shtml","color":"","subtitle":" \\u5317\\u4eac\\u5e02\\u533a1","suburl":"","description":"9\\u670817\\u65e5\\uff0c\\u5317\\u4eac\\u5e02\\u533a\\u665a\\u9ad8\\u5cf0\\u62e5\\u5835\\u8def\\u6bb5\\u5cf0\\u503c\\u8d85140\\u6761\\uff0c\\u5df2\\u7ecf\\u8d85\\u8fc7\\u4eca\\u5e74\\u5e74\\u521d\\u56e0\\u5927\\u96ea\\u9020\\u621090\\u4f59\\u6761\\u62e5\\u5835\\u8def\\u6bb5\\u5cf0\\u503c\\u7684\\u7eaa\\u5f55\\u3002","thumb":"2012\\/1009\\/1349778701818.jpg","time":"1349778720"},{"contentid":87,"title":"\\u5f20\\u53ec\\u5fe0\\uff1a\\u6211\\u519b\\u8230\\u8247\\u7684\\u5c16\\u7aef\\u6280\\u672f\\u843d\\u540e\\u7f8e\\u56fd10-20\\u5e74","url":"http:\\/\\/www.silkroad.news.cn\\/2012\\/1009\\/87.shtml","color":"","subtitle":"\\u5f20\\u53ec\\u5fe0\\uff1a\\u6211","suburl":"","description":"\\u4eca\\u5e74\\u768412\\u670826\\u65e5\\u662f \\u4e2d\\u56fd\\u4eba\\u6c11\\u6d77\\u519b\\u8d74\\u4e9a\\u4e01\\u6e7e\\u62a4\\u822a\\u6574\\u6574\\u4e09\\u5468\\u5e74\\u7eaa\\u5ff5\\u65e5\\uff0c\\u4e2d\\u56fd\\u6d77\\u519b\\u53ef\\u4ee5\\u8bf4\\u5411\\u4e16\\u754c\\u5c55\\u793a\\u4e86\\u6587\\u660e\\u4e4b\\u5e08\\uff0c\\u5a01\\u6b66\\u4e4b\\u5e08\\u7684\\u5149\\u8f89\\u7684\\u5f62\\u8c61\\u3002\\u56de\\u987e\\u8fd9\\u4e09\\u5e74\\u7684\\u62a4\\u822a\\u884c\\u52a8\\uff0c\\u4e2d\\u56fd\\u6d77\\u519b\\u7ecf\\u5386\\u4e86\\u54ea\\u4e9b\\u6ce2 \\u6298\\uff0c\\u6211\\u4eec\\u83b7\\u5f97\\u4e86\\u54ea\\u4e9b\\u7ecf\\u9a8c\\uff0c\\u53c8\\u68c0\\u9a8c\\u4e86\\u54ea\\u4e9b\\u4e2d\\u56fd\\u6d77\\u519b\\u7684\\u6b66\\u5668\\u88c5\\u5907\\u5462\\uff1f\\u4eca\\u5929\\u6211\\u4eec\\u5c31\\u8bf7\\u519b\\u4e8b\\u4e13\\u5bb6\\u5f20\\u53ec\\u5fe0\\u6559\\u6388\\u8ddf\\u60a8\\u4e00\\u8d77\\u6765\\u56de\\u987e\\u548c\\u89e3\\u8bfb\\u4e00\\u4e0b\\u6d77\\u519b\\u62a4\\u822a\\u4e09\\u5468\\u5e74\\u3002","thumb":"2012\\/1009\\/1349775718609.jpg","time":"1349775660"},{"contentid":83,"title":"\\u5f20\\u53ec\\u5fe0\\uff1a\\u4e2d\\u56fd30\\u591a\\u5e74\\u5fcd\\u8fb1\\u8d1f\\u91cd\\u88ab\\u8d8a\\u5357\\u8bef\\u89e3\\u4e3a\\u5bb3\\u6015","url":"http:\\/\\/www.silkroad.news.cn\\/2012\\/1009\\/83.shtml","color":"","subtitle":"\\u5f20\\u53ec\\u5fe0\\uff1a\\u4e2d","suburl":"","description":"\\u8fd1\\u671f\\u5357\\u6d77\\u4e89\\u7aef\\u7684\\u7d27\\u5f20\\u5c40\\u52bf\\u4e0d\\u65ad\\u5347\\u6e29\\uff0c\\u8d8a\\u5357\\u548c\\u7f8e\\u83f2\\u63a5\\u8fde\\u5728\\u5357\\u6d77\\u8fdb\\u884c\\u6f14\\u4e60\\uff0c\\u90a3\\u4e48\\u5357\\u6d77\\u4e89\\u7aef\\u7684\\u5347\\u7ea7\\u662f\\u5426\\u4f1a\\u5bfc\\u81f4\\u519b\\u4e8b\\u51b2\\u7a81\\u5462\\uff1f\\u4eca\\u5929\\u6211\\u4eec\\u5c31\\u6765\\u8bf7\\u6559\\u4e00\\u4e0b\\u519b\\u4e8b\\u4e13\\u5bb6\\u5f20\\u53ec\\u5fe0\\u6559\\u6388","thumb":"2012\\/1009\\/1349775045950.jpg","time":"1349775101"},{"contentid":70,"title":"\\u5317\\u4ed1\\u65b0\\u95fb","url":"http:\\/\\/www.silkroad.news.cn\\/2012\\/1009\\/70.shtml","color":"","subtitle":"\\u5317\\u4ed1\\u65b0\\u95fb","suburl":"","description":"\\u5317\\u4ed1\\u65b0\\u95fb","thumb":"http:\\/\\/media.cmstop.com\\/pic\\/20120516\\/1b757273f8ae0dd176f2d02c94f3de67-1.jpg","time":"1349769430"},{"contentid":69,"title":"\\u7f8e\\u4e3d\\u7684\\u5927\\u811a \\u5e9e\\u5927\\u7684\\u5e1d\\u4f01\\u9e45\\u738b\\u56fd \\u5feb\\u4e50\\u7684\\u4f01\\u9e45","url":"http:\\/\\/www.silkroad.news.cn\\/2012\\/1009\\/69.shtml","color":"","subtitle":"\\u7f8e\\u4e3d\\u7684\\u5927\\u811a","suburl":"","description":"\\u5728\\u51b0\\u5929\\u96ea\\u5730\\u7684\\u5357\\u6781\\u6df1\\u5904\\uff0c\\u6709\\u4e00\\u4e2a\\u5e9e\\u5927\\u7684\\u5e1d\\u4f01\\u9e45\\u738b\\u56fd\\uff0c\\u5728\\u90a3\\u91cc\\uff0c\\u6bcf\\u4e00\\u53ea\\u5e1d\\u4f01\\u9e45\\u90fd\\u5fc5\\u987b\\u9760\\u7f8e\\u5999\\u7684\\u6b4c\\u58f0\\u627e\\u5230\\u81ea\\u5df1\\u7684\\u5fc3\\u7075\\u4f34\\u4fa3\\u3002\\u5c0f\\u4f01\\u9e45\\u66fc\\u6ce2\\uff08\\u4f0a\\u5229\\u4e9a\\u00b7\\u4f0d\\u5fb7\\uff09\\u5374\\u4ece\\u751f\\u4e0b\\u6765\\u5c31\\u201c\\u4e0e\\u4f17\\u4e0d\\u540c\\u201d\\uff0c\\u4ed6\\u5929\\u751f\\u5c31\\u4e0d\\u662f\\u5531\\u6b4c\\u7684\\u6599\\u5b50\\uff0c\\u5374\\u9177\\u7231\\u8df3\\u8e22\\u8e0f\\u821e\\u3002\\u867d\\u7136\\u66fc\\u6ce2\\u7684\\u5988\\u5988\\u8bfa\\u739b\\u00b7\\u73cd\\uff08\\u59ae\\u53ef\\u00b7\\u57fa\\u5fb7\\u66fc\\uff09\\u8fd8\\u65f6\\u800c\\u89c9\\u5f97\\u4ed6\\u7684\\u7231\\u597d\\u8fd8\\u7b97\\u53ef\\u7231\\uff0c\\u53ef\\u7238\\u7238\\u66fc\\u83f2\\u65af\\uff08\\u4f11\\u00b7\\u6770\\u514b\\u66fc\\uff09\\u5374\\u8ba4\\u4e3a\\u8fd9\\u7b80\\u76f4\\u4e0d\\u662f\\u5e1d\\u4f01\\u9e45\\u6240\\u4e3a\\uff0c\\u738b\\u56fd\\u7684\\u4e25\\u5389\\u9996\\u9886\\u8bfa\\u4e9a\\u66f4\\u662f\\u770b\\u8fd9\\u4e2a\\u5e1d\\u4f01\\u9e45\\u91cc\\u7684\\u53e6\\u7c7b\\u4e0d\\u987a\\u773c\\u3002 ","thumb":"http:\\/\\/media.cmstop.com\\/pic\\/20120516\\/50e4760d38e7866e57f947f618f1920e-4.jpg","time":"1349769300"},{"contentid":67,"title":"\\u4e0d\\u8981\\u968f\\u4fbf\\u7814\\u7a76\\u4eba\\u5de5\\u667a\\u80fd","url":"http:\\/\\/www.silkroad.news.cn\\/2012\\/1009\\/67.shtml","color":"","subtitle":"\\u4e0d\\u8981\\u968f\\u4fbf\\u7814","suburl":"","description":"\\u4e0d\\u8981\\u968f\\u4fbf\\u7814\\u7a76\\u4eba\\u5de5\\u667a\\u80fd","thumb":"2012\\/1009\\/1349757544853.jpg","time":"1349757546"},{"contentid":65,"title":"\\u6c14\\u8d28\\u5973\\u795e\\u5b87\\u8f69\\u7ffb\\u5531\\u300a\\u4e0d\\u518d\\u8ba9\\u4f60\\u5b64\\u5355\\u300b","url":"http:\\/\\/www.silkroad.news.cn\\/2012\\/1009\\/65.shtml","color":"","subtitle":"\\u6c14\\u8d28\\u5973\\u795e\\u5b87","suburl":"","description":"\\u6f14\\u5531:\\u5b87\\u8f69\\n\\u526a\\u8f91&\\u8c03\\u8272:\\u5730\\u4e3b_L\\n\\u5b57\\u5e55:\\u5730\\u4e3b_L\\n\\u97f3\\u4e50\\u652f\\u6301:@\\u590d\\u4e50\\u73ed","thumb":"2012\\/1009\\/1349757234658.jpg","time":"1349757180"},{"contentid":60,"title":"\\u5317\\u4eac\\u5730\\u94c1\\u4fe1\\u606f\\u5c4f\\u73b0\\u201c\\u738b\\u9e4f\\u4f60\\u59b9\\u201d \\u56de\\u5e94\\uff1a\\u7cfb\\u7edf\\u8c03\\u8bd5","url":"http:\\/\\/www.silkroad.news.cn\\/2012\\/1009\\/60.shtml","color":"","subtitle":"\\u5317\\u4eac\\u5730\\u94c1\\u4fe1","suburl":"","description":"\\u5317\\u4eac\\u5730\\u94c1\\u4fe1\\u606f\\u5c4f\\u73b0\\u201c\\u738b\\u9e4f\\u4f60\\u59b9\\u201d \\u56de\\u5e94\\uff1a\\u7cfb\\u7edf\\u8c03\\u8bd5","thumb":"2012\\/1009\\/1349755393811.jpg","time":"1349755380"},{"contentid":59,"title":"\\u660e\\u5e741\\u67081\\u65e5\\u8d77\\u95ef\\u7ea2\\u706f\\u62636\\u5206 \\u56de\\u987e\\u95ef\\u7ea2\\u706f\\u9020\\u6210\\u7684\\u60e8\\u6848","url":"http:\\/\\/www.silkroad.news.cn\\/2012\\/1009\\/59.shtml","color":"","subtitle":"\\u660e\\u5e741\\u67081\\u65e5","suburl":"","description":"\\u660e\\u5e741\\u67081\\u65e5\\u8d77\\u95ef\\u7ea2\\u706f\\u62636\\u5206 \\u56de\\u987e\\u95ef\\u7ea2\\u706f\\u9020\\u6210\\u7684\\u60e8\\u6848","thumb":"2012\\/1009\\/1349755528603.jpg","time":"1349755080"},{"contentid":57,"title":"\\u83ab\\u8a00\\u6751\\u4e0a\\u6625\\u6811\\u6210\\u8bfa\\u8d1d\\u5c14\\u6587\\u5b66\\u5956\\u70ed\\u95e8 \\u4e13\\u5bb6\\u8d28\\u7591\\u7092\\u4f5c","url":"http:\\/\\/www.silkroad.news.cn\\/2012\\/1009\\/57.shtml","color":"","subtitle":"\\u83ab\\u8a00\\u6751\\u4e0a\\u6625","suburl":"","description":"\\u53a6\\u95e8\\u5927\\u5b66\\u4eba\\u6587\\u5b66\\u9662\\u9662\\u957f\\u5468\\u5b81\\u8ba4\\u4e3a\\uff0c\\u5f53\\u524d\\u5bf9\\u4e8e\\u83ab\\u8a00\\u83b7\\u5956\\u7684\\u5404\\u79cd\\u731c\\u6d4b\\u66f4\\u591a\\u662f\\u4e00\\u79cd\\u65b0\\u95fb\\u7092\\u4f5c\\uff0c\\u5bf9\\u9881\\u5956\\u672c\\u8eab\\u6ca1\\u6709\\u592a\\u5927\\u610f\\u4e49\\u3002","thumb":"2012\\/1009\\/1349751503718.jpg","time":"1349751840"},{"contentid":58,"title":"\\u83ab\\u8a00\\u6751\\u4e0a\\u6625\\u6811\\u6210\\u8bfa\\u8d1d\\u5c14\\u6587\\u5b66\\u5956\\u70ed\\u95e8 \\u4e13\\u5bb6\\u8d28\\u7591\\u7092\\u4f5c","url":"http:\\/\\/www.silkroad.news.cn\\/2012\\/1009\\/57.shtml","color":"","subtitle":"\\u83ab\\u8a00\\u6751\\u4e0a\\u6625","suburl":"","description":"\\u53a6\\u95e8\\u5927\\u5b66\\u4eba\\u6587\\u5b66\\u9662\\u9662\\u957f\\u5468\\u5b81\\u8ba4\\u4e3a\\uff0c\\u5f53\\u524d\\u5bf9\\u4e8e\\u83ab\\u8a00\\u83b7\\u5956\\u7684\\u5404\\u79cd\\u731c\\u6d4b\\u66f4\\u591a\\u662f\\u4e00\\u79cd\\u65b0\\u95fb\\u7092\\u4f5c\\uff0c\\u5bf9\\u9881\\u5956\\u672c\\u8eab\\u6ca1\\u6709\\u592a\\u5927\\u610f\\u4e49\\u3002","thumb":"2012\\/1009\\/1349751503718.jpg","time":"1349751840"},{"contentid":31,"title":"\\u5f20\\u7ff0\\u5e86\\u751f\\u90d1\\u723d\\u7d20\\u989c\\u73b0\\u8eab \\u6df1\\u60c5\\u6ce8\\u89c6\\u7537\\u65b9(\\u56fe)","url":"http:\\/\\/www.silkroad.news.cn\\/2012\\/1008\\/31.shtml","color":"","subtitle":"\\u5f20\\u7ff0\\u5e86\\u751f\\u90d1","suburl":"","description":"\\u5f20\\u7ff0\\u5e86\\u751f\\u90d1\\u723d\\u7d20\\u989c\\u73b0\\u8eab \\u6df1\\u60c5\\u6ce8\\u89c6\\u7537\\u65b9(\\u56fe)","thumb":"2012\\/1008\\/1349697611460.jpg","time":"1349691960"},{"contentid":32,"title":"\\u53f0\\u827a\\u4eba\\u7533\\u4e1c\\u9756\\u6d88\\u5931\\u6f14\\u827a\\u5708 \\u4f20\\u5176\\u53d7\\u4f24\\u660f\\u8ff7\\u4e0d\\u9192","url":"http:\\/\\/www.silkroad.news.cn\\/2012\\/1008\\/32.shtml","color":"","subtitle":"\\u53f0\\u827a\\u4eba\\u7533\\u4e1c","suburl":"","description":"\\u53f0\\u827a\\u4eba\\u7533\\u4e1c\\u9756\\u6d88\\u5931\\u6f14\\u827a\\u5708 \\u4f20\\u5176\\u53d7\\u4f24\\u660f\\u8ff7\\u4e0d\\u9192","thumb":"2012\\/1008\\/1349697624472.jpg","time":"1349691660"},{"contentid":34,"title":"\\u97e9\\u4eba\\u6c14\\u56e2\\u56e2SJ\\u53c2\\u52a0\\u6d3b\\u52a8 \\u5c55\\u73b0\\u7cbe\\u5f69\\u821e\\u53f0(\\u56fe)","url":"http:\\/\\/www.silkroad.news.cn\\/2012\\/1008\\/34.shtml","color":"","subtitle":"\\u97e9\\u4eba\\u6c14\\u56e2\\u56e2","suburl":"","description":"\\u97e9\\u4eba\\u6c14\\u56e2\\u56e2SJ\\u53c2\\u52a0\\u6d3b\\u52a8 \\u5c55\\u73b0\\u7cbe\\u5f69\\u821e\\u53f0(\\u56fe)","thumb":"2012\\/1008\\/1349697676245.jpg","time":"1349687460"},{"contentid":41,"title":"\\u56fa\\u5b89\\u6c38\\u5b9a\\u6cb3\\u5b54\\u96c0\\u82f1\\u56fd\\u5bab78\\u5e73\\u8d772-3\\u5c456300\\u5143","url":"http:\\/\\/www.silkroad.news.cn\\/2012\\/1008\\/41.shtml","color":"","subtitle":"\\u56fa\\u5b89\\u6c38\\u5b9a\\u6cb3","suburl":"","description":"\\u56fa\\u5b89\\u6c38\\u5b9a\\u6cb3\\u5b54\\u96c0\\u82f1\\u56fd\\u5bab78\\u5e73\\u8d772-3\\u5c456300\\u5143","thumb":"2012\\/1008\\/1349698398772.jpg","time":"1349682600"},{"contentid":81,"title":" \\u6155\\u5bb9\\u96ea\\u6751\\u8c08\\u65b0\\u4e66\\u300a\\u4e2d\\u56fd\\uff0c\\u5c11\\u4e86\\u4e00\\u5473\\u836f\\u300b","url":"http:\\/\\/www.silkroad.news.cn\\/2012\\/0926\\/81.shtml","color":"","subtitle":" \\u6155\\u5bb9\\u96ea\\u6751","suburl":"","description":"2009\\u5e74\\u5e95\\uff0c\\u6155\\u5bb9\\u96ea\\u6751\\u5367\\u5e95\\u8fdb\\u5165\\u4f20\\u9500\\u96c6\\u56e2\\uff0c\\u4e0a\\u6f14\\u4e00\\u51fa\\u771f\\u5b9e\\u7248\\u201c\\u65e0\\u95f4\\u9053\\u201d\\u3002\\u4ed6\\u6839\\u636e\\u8fd9\\u4e00\\u4eb2\\u8eab\\u7ecf\\u5386\\uff0c\\u5199\\u4f5c\\u63ed\\u9732\\u4f20\\u9500\\u7684\\u7eaa\\u5b9e\\u4f5c\\u54c1\\u300a\\u4e2d\\u56fd\\uff0c\\u5c11\\u4e86\\u4e00\\u5473\\u836f\\u300b\\u3002\\u5728\\u8fd9\\u672c\\u4e66\\u7684\\u5c01\\u9762\\u4e0a \\uff0c\\u8fd9\\u6837\\u5199\\u9053\\u201c\\u611a\\u8822\\u4e0d\\u662f\\u5929\\u751f\\u7684\\uff0c\\u800c\\u662f\\u4eba\\u5de5\\u5236\\u9020\\u51fa\\u6765\\u7684\\u3002\\u6211\\u6709\\u4e00\\u4e2a\\u5e0c\\u671b\\uff1a\\u8ba9\\u5e38\\u8bc6\\u5728\\u9633\\u5149\\u4e0b\\u884c\\u8d70\\uff0c\\u8ba9\\u8d2b\\u5f31\\u8005\\u4ece\\u82e6\\u96be\\u4e2d\\u8131\\u8eab\\uff0c\\u8ba9\\u90aa\\u6076\\u8fdc\\u79bb\\u6bcf\\u4e00\\u9897\\u5584\\u826f\\u7684\\u5fc3\\u3002\\u201d\\u60f3\\u77e5\\u9053\\u6155\\u5bb9\\u96ea\\u6751\\u7684\\u4e66\\u4e2d\\u7a76\\u7adf\\u5bf9\\u4f20\\u9500\\u6709\\u7740\\u600e\\u4e48\\u6837\\u7684\\u63cf\\u8ff0\\u5417\\uff1f\\u60f3\\u77e5\\u9053\\u5728\\u77ed\\u77ed\\u768423\\u5929\\u4e4b\\u4e2d\\uff0c\\u6155\\u5bb9\\u96ea\\u6751\\u5230\\u5e95\\u7ecf\\u5386\\u4e86\\u4ec0\\u4e48\\u5417\\uff1f","thumb":"2012\\/0926\\/1348625253763.png","time":"1348625288"},{"contentid":78,"title":"\\u5229\\u6bd4\\u4e9a\\u653f\\u5e9c\\u519b\\u88c5\\u7532\\u90e8\\u961f\\u906d\\u6bc1\\u706d\\u6027\\u6253\\u51fb","url":"http:\\/\\/www.silkroad.news.cn\\/2012\\/0926\\/78.shtml","color":"","subtitle":"\\u5229\\u6bd4\\u4e9a\\u653f\\u5e9c","suburl":"","description":"\\u5230\\u76ee\\u524d\\u4e3a\\u6b62\\uff0c\\u897f\\u65b9\\u5df2\\u7ecf\\u8fdb\\u884c\\u4e86\\u56db\\u8f6e\\u7a7a\\u88ad\\uff0c\\u8fd9\\u56db\\u8f6e\\u7a7a\\u88ad\\u8ddf\\u7981\\u98de\\u6ca1\\u6709\\u592a\\u5927\\u7684\\u5173\\u7cfb\\uff0c\\u4e3b\\u8981\\u76ee\\u7684\\u8fd8\\u662f\\u5bfb\\u627e\\u5361\\u624e\\u83f2\\u7684\\u4f4f\\u6240\\uff0c\\u5bf9\\u4ed6\\u7684\\u4f4f\\u6240\\u8fdb\\u884c\\u6253\\u51fb\\uff0c\\u4ed6\\u7684\\u4f4f\\u6240\\u548c\\u7981\\u98de\\u6ca1\\u6709\\u4ec0\\u4e48\\u5173\\u7cfb\\u3002\\u7a7a\\u88ad\\u6467\\u6bc1\\u4e86\\u5927\\u91cf\\u7684\\u5229\\u6bd4\\u4e9a\\u653f\\u5e9c\\u519b\\u7684\\u5766\\u514b\\u8f66\\u3001\\u88c5\\u7532\\u8f66\\u548c\\u8f66\\u8f86\\uff0c\\u8fd9\\u4e9b\\u5730\\u9762\\u8f66\\u8f86\\u4e0d\\u662f\\u9632\\u7a7a\\u70ae\\u706b\\uff0c\\u8ddf\\u7981\\u98de\\u6ca1\\u6709\\u5173\\u7cfb\\uff0c\\u53e6\\u5916\\u6467\\u6bc1\\u4e86\\u6d77\\u519b\\u7684\\u57fa\\u5730\\uff0c\\u5f39\\u836f\\u5e93\\u3001\\u673a\\u573a\\u3002","thumb":"2012\\/0926\\/1348623374717.jpg","time":"1348623472"}]', NULL, NULL, NULL, '<div class="shoveler-item fl-l">\n    <ul class="m-imagetitle video-imagetitle" style="height:476px;">\n        <!--{loop $data $k $r}-->\n        <!--{if $k < 8}-->\n        <li class="item js-overlay{if ($k+1)%4 == 0} right{/if}"><a href="{$r[url]}" target="_blank" class="thumb-link"><img src="{thumb($r[thumb], 240, 180)}" width="240" height="180" alt=""></a><a class="title" href="{$r[url]}" title="{$r[title]}" target="_blank">{str_natcut($r[title], 50, '''')}</a><a href="{$r[url]}" target="_blank" class="overlay"><b class="overlay-play icon40x40"></b></a></li>\n        <!--{/if}-->\n        <!--{/loop}-->\n    </ul>\n</div>\n<div class="shoveler-item fl-l">\n    <ul class="m-imagetitle video-imagetitle">\n        <!--{loop $data $k $r}-->\n        <!--{if $k > 7 && $k < 16}-->\n        <?php $i = $k+1;?>\n        <li class="item js-overlay{if ($k+1)%4 == 0} right{/if}"><a href="{$r[url]}" target="_blank" class="thumb-link"><img src="{thumb($r[thumb], 240, 180)}" width="240" height="180" alt=""></a><a class="title" href="{$r[url]}" title="{$r[title]}" target="_blank">{str_natcut($r[title], 50, '''')}</a><a href="{$r[url]}" target="_blank" class="overlay"><b class="overlay-play icon40x40"></b></a></li>\n        <!--{/if}-->\n        <!--{/loop}-->\n    </ul>\n</div>\n<div class="shoveler-item fl-l">\n    <ul class="m-imagetitle video-imagetitle">\n        <!--{loop $data $k $r}-->\n        <!--{if $k > 15}-->\n        <?php $i = $k+1;?>\n        <li class="item js-overlay{if ($k+1)%4 == 0} right{/if}"><a href="{$r[url]}" target="_blank" class="thumb-link"><img src="{thumb($r[thumb], 240, 180)}" width="240" height="180" alt=""></a><a class="title" href="{$r[url]}" title="{$r[title]}" target="_blank">{str_natcut($r[title], 50, '''')}</a><a href="{$r[url]}" target="_blank" class="overlay"><b class="overlay-play icon40x40"></b></a></li>\n        <!--{/if}-->\n        <!--{/loop}-->\n    </ul>\n</div>', 'html', NULL, 24, 0, 0, '{"system_fields":{"contentid":{"checked":"1","func":"intval"},"title":{"checked":"1","required":"1","min_length":"","max_length":""},"url":{"checked":"1"},"color":{"checked":"1"},"icon":{"checked":"1"},"iconsrc":{"checked":"1"},"subtitle":{"checked":"1","min_length":"","max_length":""},"suburl":{"checked":"1"},"thumb":{"checked":"1","width":"","height":""},"description":{"checked":"1","min_length":"","max_length":""},"time":{"checked":"1","func":"section_fields_strtotime"}},"custom_fields":{"text":[],"name":[]}}', 1426915215, 1426915214, 1348939597, 4, 1426060794, 1, 1342607563, 1, '共 6 条，图片 + 标题，标题长度 20 个汉字以内，图片规格 120 x 90', 1, 0, 'section/list.html', 0, 10),
(46, 9, 'push', '访谈预告', '[]', '[]', NULL, NULL, NULL, '<!--{loop $data $k $r}-->\n<!-- 面包屑 -->\n<?php $_contentid = intval($r[''contentid'']); ?>\n<?php $_catid = intval(table(''content'', $_contentid, ''catid'')); ?>\n<?php $_category = table(''category'', $_catid); ?>\n<div class="herald-crumb">\n    <a href="{$_category[url]}" title="{$_category[name]}" target="_blank" class="now"><em>{str_natcut($_category[name], 14, '''')}</em><span></span></a>\n    <?php $number = table(''interview'', $_contentid, ''number''); ?>\n    <a href="{$r[url]}" title="" target="_blank"><em>第 <?php echo $number ? $number : 1; ?> 期</em><span></span></a>\n</div><!-- @end 面包屑 -->\n<div class="herald-summary">\n    <div class="f-l">\n        {db sql="SELECT `name`, `color`, `photo`, `url` FROM `#table_interview_guest` WHERE `contentid` = $_contentid" orderby="`sort` ASC, `guestid` ASC" size="1" return="g"}\n        <a href="{$g[url]}" target="_blank" title="{$g[name]}">\n            <img src="{thumb($g[photo], 60, 60, 1, null, 1)}" alt="" width="60" height="60" class="thumb">\n        </a>\n        <a href="{$g[url]}" target="_blank" title="{$g[name]}" class="t">{str_natcut($g[name], 4, '''')}</a>\n        {/db}\n    </div>\n    <div>\n        <h2><a href="{$r[url]}" target="_blank" title="{$r[title]}">{str_natcut($r[title], 15, '''')}</a></h2>\n        <p class="summary">{str_natcut($r[description], 90)}</p>\n    </div>\n</div>\n<!--{/loop}-->', 'html', NULL, 1, 0, 0, NULL, 1426060817, 1426060817, 0, 0, 1426060847, 1, 1342665140, 1, '共 1 条', 0, 0, 'section/list.html', 0, 10),
(50, 1, 'push', '旅游频道推荐', '[]', '[]', NULL, NULL, NULL, '               <div class="txt-list">\n<!--{loop $data $k $r}-->\n<!--{if $k==0}-->\n                  <h3 class="h3"><a href="{$r[url]}" title="{$r[title]}">{str_natcut($r[title],16,'''')}</a></h3>\n                   <ul class="list list-point">\n<!--{else}-->\n                       <li class="item"><em class="ico"></em><a href="{$r[url]}" title="{$r[title]}" class="title">{$r[title]}</a> </li>\n<!--{/if}-->\n                       {if $k==5}<li class="hr"></li>{/if}\n<!--{/loop}-->\n                   </ul>\n             </div>', 'html', NULL, 11, 300, 0, NULL, 1423123660, 1423123360, 0, 0, 1423206837, 1, 1342518182, 1, '权重60~90以上', 0, 0, 'section/list.html', 0, 10),
(51, 1, 'push', '房产频道左侧图', '[]', '[{"contentid":34,"title":"\\u97e9\\u4eba\\u6c14\\u56e2\\u56e2SJ\\u53c2\\u52a0\\u6d3b\\u52a8 \\u5c55\\u73b0\\u7cbe\\u5f69\\u821e\\u53f0(\\u56fe)","url":"http:\\/\\/www.silkroad.news.cn\\/2012\\/1008\\/34.shtml","color":"","subtitle":"\\u97e9\\u4eba\\u6c14\\u56e2\\u56e2","suburl":"","description":"\\u97e9\\u4eba\\u6c14\\u56e2\\u56e2SJ\\u53c2\\u52a0\\u6d3b\\u52a8 \\u5c55\\u73b0\\u7cbe\\u5f69\\u821e\\u53f0(\\u56fe)","thumb":"2012\\/1008\\/1349697676245.jpg","time":"1349687460"}]', NULL, NULL, NULL, '<!--{loop $data $k $r}-->\n<div class="fl-l picture pos-r"><a href="{$r[url]}" target="_blank" title="{$r[title]}"><img src="{thumb($r[thumb], 320, 240)}" width="320" height="240" alt=""></a><p class="text ie-rgba"><a href="{$r[url]}" target="_blank" title="{$r[title]}">{$r[title]}</a></p></div>\n<!--{/loop}-->', 'html', NULL, 1, 300, 0, '{"system_fields":{"contentid":{"checked":"1","func":"intval"},"title":{"checked":"1","required":"1","min_length":"","max_length":""},"url":{"checked":"1"},"color":{"checked":"1"},"icon":{"checked":"1"},"iconsrc":{"checked":"1"},"subtitle":{"checked":"1","min_length":"","max_length":""},"suburl":{"checked":"1"},"thumb":{"checked":"1","width":"","height":""},"description":{"checked":"1","min_length":"","max_length":""},"time":{"checked":"1","func":"section_fields_strtotime"}},"custom_fields":{"text":[],"name":[]}}', 1426917537, 1426917236, 0, 0, 1426060456, 1, 1342518182, 1, '权重60~90以上', 1, 0, 'section/list.html', 0, 10),
(52, 1, 'push', '房产频道列表', '[]', '[{"contentid":7,"title":"\\u53f0\\u6e7e\\u7834\\u4e07\\u4eba\\u8054\\u7f72\\u518d\\u8bae\\u58eb\\u5175\\u51a4\\u6b7b\\u6848\\u3010\\u89c6\\u9891\\u6302\\u4ef6\\u3011","url":"http:\\/\\/www.silkroad.news.cn\\/2012\\/1010\\/7.shtml","color":"","subtitle":"\\u53f0\\u6e7e\\u7834\\u4e07\\u4eba","suburl":"","description":"\\u53f0\\u6e7e\\u7834\\u4e07\\u4eba\\u8054\\u7f72\\u518d\\u8bae\\u58eb\\u5175\\u51a4\\u6b7b\\u6848\\u3010\\u89c6\\u9891\\u6302\\u4ef6\\u3011","thumb":"2012\\/1008\\/1349697830754.jpg","time":"1349850060"},{"contentid":13,"title":"\\u7f8e\\u56fd\\u516c\\u53f8\\u53d1\\u5c04\\u201c\\u9f99\\u201d\\u98de\\u8239 \\u9996\\u5411\\u56fd\\u9645\\u7a7a\\u95f4\\u7ad9\\u8fd0\\u8d27","url":"http:\\/\\/www.silkroad.news.cn\\/2012\\/1010\\/13.shtml","color":"","subtitle":"\\u7f8e\\u56fd\\u516c\\u53f8\\u53d1","suburl":"","description":"\\u7f8e\\u56fd\\u516c\\u53f8\\u53d1\\u5c04\\u201c\\u9f99\\u201d\\u98de\\u8239 \\u9996\\u5411\\u56fd\\u9645\\u7a7a\\u95f4\\u7ad9\\u8fd0\\u8d27","thumb":"2012\\/1008\\/1349698479695.jpg","time":"1349850060"},{"contentid":91,"title":" \\u5317\\u4eac\\u5e02\\u533a17\\u65e5\\u62e5\\u5835\\u8def\\u6bb5\\u5cf0\\u503c\\u8d85140\\u6761\\u521b\\u7eaa\\u5f55","url":"http:\\/\\/www.silkroad.news.cn\\/2012\\/1009\\/91.shtml","color":"","subtitle":" \\u5317\\u4eac\\u5e02\\u533a1","suburl":"","description":"9\\u670817\\u65e5\\uff0c\\u5317\\u4eac\\u5e02\\u533a\\u665a\\u9ad8\\u5cf0\\u62e5\\u5835\\u8def\\u6bb5\\u5cf0\\u503c\\u8d85140\\u6761\\uff0c\\u5df2\\u7ecf\\u8d85\\u8fc7\\u4eca\\u5e74\\u5e74\\u521d\\u56e0\\u5927\\u96ea\\u9020\\u621090\\u4f59\\u6761\\u62e5\\u5835\\u8def\\u6bb5\\u5cf0\\u503c\\u7684\\u7eaa\\u5f55\\u3002","thumb":"2012\\/1009\\/1349778701818.jpg","time":"1349778720"},{"contentid":68,"title":"CmsTop\\u5ba3\\u4f20\\u7247","url":"http:\\/\\/www.silkroad.news.cn\\/2012\\/1009\\/68.shtml","color":"","subtitle":"CmsTop\\u5ba3\\u4f20","suburl":"","description":"CmsTop\\u5ba3\\u4f20\\u7247","thumb":"2012\\/1009\\/1349758019346.jpg","time":"1349758037"},{"contentid":62,"title":"\\u4ed6\\u3001\\u5979 \\u4ece\\u56fe\\u4e66\\u9986\\u5f00\\u59cb\\u7684\\u7231\\u60c5","url":"http:\\/\\/www.silkroad.news.cn\\/2012\\/1009\\/62.shtml","color":"","subtitle":"\\u4ed6\\u3001\\u5979 \\u4ece","suburl":"","description":"\\u4ed6\\u3001\\u5979\\uff0c\\u56fe\\u4e66\\u9986\\u6ce1\\u599e\\u7684\\u597d\\u5730\\u65b9","thumb":"2012\\/1009\\/1349756193505.jpg","time":"1349756160"},{"contentid":61,"title":"\\u4e2d\\u56fd\\u4e2d\\u5b66\\u751f\\u9996\\u90e8\\u60c5\\u611f\\u7cfb\\u5217\\u7247\\u300a\\u804a\\u5929\\u2161\\uff1a\\u6210\\u957f\\u5b63\\u300b\\u9ad8\\u6e05\\u6b63\\u7247","url":"http:\\/\\/www.silkroad.news.cn\\/2012\\/1009\\/61.shtml","color":"","subtitle":"\\u4e2d\\u56fd\\u4e2d\\u5b66\\u751f","suburl":"","description":"\\u7531 \\u6df1\\u5733\\u5e02\\u7b2c\\u4e8c\\u804c\\u4e1a\\u6280\\u672f\\u5b66\\u6821 \\u51fa\\u54c1\\uff0c\\u552f\\u8bb0\\u5fc6\\u5f71\\u89c6\\u5de5\\u574a \\u6444\\u5236\\u7684\\u4e2d\\u56fd\\u4e2d\\u5b66\\u751f\\u9996\\u90e8\\u60c5\\u611f\\u7cfb\\u5217\\u7247\\u300a\\u804a\\u5929\\u2161\\uff1a\\u6210\\u957f\\u5b63\\u300b\\u3002","thumb":"2012\\/1009\\/1349755867505.jpg","time":"1349755680"}]', NULL, NULL, NULL, '<!--{loop $data $k $r}-->\n<!--{if $k==0}-->\n<h3 class="h3"><a href="{$r[url]}" target="_blank" title="{$r[title]}">{$r[title]}</a></h3>\n<p class="summary">{str_natcut($r[description], 45, ''...'')}</p>\n<div class="hr10"></div>\n<!--{/if}-->\n<!--{/loop}-->\n<ul class="ul">\n    <!--{loop $data $k $r}-->\n    <!--{if $k > 0}-->\n    <li><a href="{$r[url]}" target="_blank" title="{$r[title]}">{$r[title]}</a></li>\n    <!--{/if}-->\n    <!--{/loop}-->\n</ul>', 'html', NULL, 6, 300, 0, '{"system_fields":{"contentid":{"checked":"1","func":"intval"},"title":{"checked":"1","required":"1","min_length":"","max_length":""},"url":{"checked":"1"},"color":{"checked":"1"},"icon":{"checked":"1"},"iconsrc":{"checked":"1"},"subtitle":{"checked":"1","min_length":"","max_length":""},"suburl":{"checked":"1"},"thumb":{"checked":"1","width":"","height":""},"description":{"checked":"1","min_length":"","max_length":""},"time":{"checked":"1","func":"section_fields_strtotime"}},"custom_fields":{"text":[],"name":[]}}', 1426917537, 1426917236, 0, 0, 1426508744, 1, 1342517911, 1, '权重为95。5条 第一条为图文描述', 1, 0, 'section/list.html', 0, 10),
(53, 1, 'push', '个人专栏', '[]', '[{"contentid":20,"title":"\\u535a\\u4e16\\uff1a2020\\u5e74\\u5546\\u7528\\u8f66\\u6cb9\\u8017\\u6709\\u671b\\u518d\\u51cf\\u5c1115%","url":"http:\\/\\/www.silkroad.news.cn\\/2012\\/1010\\/20.shtml","color":"","subtitle":"\\u535a\\u4e16\\uff1a2020","suburl":"","description":"\\u535a\\u4e16\\uff1a2020\\u5e74\\u5546\\u7528\\u8f66\\u6cb9\\u8017\\u6709\\u671b\\u518d\\u51cf\\u5c1115%","thumb":"2012\\/1008\\/1349698585871.jpg","time":"1349850060"},{"contentid":88,"title":"\\u5f20\\u53ec\\u5fe0\\uff1a\\u89e3\\u51b3\\u5357\\u6d77\\u4e89\\u7aef\\u65f6\\u64e6\\u67aa\\u8d70\\u706b\\u5f88\\u6b63\\u5e38","url":"http:\\/\\/www.silkroad.news.cn\\/2012\\/1009\\/88.shtml","color":"","subtitle":"\\u5f20\\u53ec\\u5fe0\\uff1a\\u89e3","suburl":"","description":"\\u8d8a\\u535719\\u65e5\\u8fde\\u7eed\\u7b2c\\u4e09\\u4e2a\\u5468\\u672b\\u53d1\\u751f\\u53cd\\u534e\\u6297\\u8bae\\uff0c\\u4e00\\u4e9b\\u793a\\u5a01\\u8005\\u558a\\u51fa\\u201c\\u6253\\u5012\\u4e2d\\u56fd\\u201d\\u4e4b\\u7c7b\\u7684\\u6fc0\\u70c8\\u53e3\\u53f7\\u3002\\u5728\\u5bf9\\u793a\\u5a01\\u7ba1\\u63a7\\u4e25\\u5bc6\\u7684\\u8d8a\\u5357\\uff0c\\u8fd9\\u79cd\\u8fde\\u7eed\\u516c\\u5f00\\u6297\\u8bae\\u88ab\\u8ba4\\u4e3a\\u975e\\u5e38\\u7f55\\u89c1\\u3002\\u4e0e\\u4e2d\\u56fd \\u5173\\u7cfb\\u7d27\\u5f20\\u4e4b\\u9645\\uff0c\\u8d8a\\u5357\\u53c8\\u5411\\u7f8e\\u56fd\\u9760\\u4e86\\u4e00\\u6b65\\uff0c\\u4e24\\u56fd17\\u65e5\\u53d1\\u8868\\u5171\\u540c\\u58f0\\u660e\\uff0c\\u547c\\u5401\\u5357\\u6d77\\u201c\\u822a\\u884c\\u81ea\\u7531\\u201d\\uff0c\\u5e76\\u63a2\\u8ba8\\u628a\\u53cc\\u65b9\\u5173\\u7cfb\\u63d0\\u5347\\u5230\\u201c\\u6218\\u7565\\u7ea7\\u201d\\u3002","thumb":"2012\\/1009\\/1349776343282.jpg","time":"1349776320"},{"contentid":87,"title":"\\u5f20\\u53ec\\u5fe0\\uff1a\\u6211\\u519b\\u8230\\u8247\\u7684\\u5c16\\u7aef\\u6280\\u672f\\u843d\\u540e\\u7f8e\\u56fd10-20\\u5e74","url":"http:\\/\\/www.silkroad.news.cn\\/2012\\/1009\\/87.shtml","color":"","subtitle":"\\u5f20\\u53ec\\u5fe0\\uff1a\\u6211","suburl":"","description":"\\u4eca\\u5e74\\u768412\\u670826\\u65e5\\u662f \\u4e2d\\u56fd\\u4eba\\u6c11\\u6d77\\u519b\\u8d74\\u4e9a\\u4e01\\u6e7e\\u62a4\\u822a\\u6574\\u6574\\u4e09\\u5468\\u5e74\\u7eaa\\u5ff5\\u65e5\\uff0c\\u4e2d\\u56fd\\u6d77\\u519b\\u53ef\\u4ee5\\u8bf4\\u5411\\u4e16\\u754c\\u5c55\\u793a\\u4e86\\u6587\\u660e\\u4e4b\\u5e08\\uff0c\\u5a01\\u6b66\\u4e4b\\u5e08\\u7684\\u5149\\u8f89\\u7684\\u5f62\\u8c61\\u3002\\u56de\\u987e\\u8fd9\\u4e09\\u5e74\\u7684\\u62a4\\u822a\\u884c\\u52a8\\uff0c\\u4e2d\\u56fd\\u6d77\\u519b\\u7ecf\\u5386\\u4e86\\u54ea\\u4e9b\\u6ce2 \\u6298\\uff0c\\u6211\\u4eec\\u83b7\\u5f97\\u4e86\\u54ea\\u4e9b\\u7ecf\\u9a8c\\uff0c\\u53c8\\u68c0\\u9a8c\\u4e86\\u54ea\\u4e9b\\u4e2d\\u56fd\\u6d77\\u519b\\u7684\\u6b66\\u5668\\u88c5\\u5907\\u5462\\uff1f\\u4eca\\u5929\\u6211\\u4eec\\u5c31\\u8bf7\\u519b\\u4e8b\\u4e13\\u5bb6\\u5f20\\u53ec\\u5fe0\\u6559\\u6388\\u8ddf\\u60a8\\u4e00\\u8d77\\u6765\\u56de\\u987e\\u548c\\u89e3\\u8bfb\\u4e00\\u4e0b\\u6d77\\u519b\\u62a4\\u822a\\u4e09\\u5468\\u5e74\\u3002","thumb":"2012\\/1009\\/1349775718609.jpg","time":"1349775660"},{"contentid":83,"title":"\\u5f20\\u53ec\\u5fe0\\uff1a\\u4e2d\\u56fd30\\u591a\\u5e74\\u5fcd\\u8fb1\\u8d1f\\u91cd\\u88ab\\u8d8a\\u5357\\u8bef\\u89e3\\u4e3a\\u5bb3\\u6015","url":"http:\\/\\/www.silkroad.news.cn\\/2012\\/1009\\/83.shtml","color":"","subtitle":"\\u5f20\\u53ec\\u5fe0\\uff1a\\u4e2d","suburl":"","description":"\\u8fd1\\u671f\\u5357\\u6d77\\u4e89\\u7aef\\u7684\\u7d27\\u5f20\\u5c40\\u52bf\\u4e0d\\u65ad\\u5347\\u6e29\\uff0c\\u8d8a\\u5357\\u548c\\u7f8e\\u83f2\\u63a5\\u8fde\\u5728\\u5357\\u6d77\\u8fdb\\u884c\\u6f14\\u4e60\\uff0c\\u90a3\\u4e48\\u5357\\u6d77\\u4e89\\u7aef\\u7684\\u5347\\u7ea7\\u662f\\u5426\\u4f1a\\u5bfc\\u81f4\\u519b\\u4e8b\\u51b2\\u7a81\\u5462\\uff1f\\u4eca\\u5929\\u6211\\u4eec\\u5c31\\u6765\\u8bf7\\u6559\\u4e00\\u4e0b\\u519b\\u4e8b\\u4e13\\u5bb6\\u5f20\\u53ec\\u5fe0\\u6559\\u6388","thumb":"2012\\/1009\\/1349775045950.jpg","time":"1349775101"},{"contentid":64,"title":"\\u4fc4\\u4ea5\\u4fc4\\u5dde\\u7acb\\u5927\\u5b66\\u519b\\u4e50\\u56e2\\u9707\\u64bc\\u8868","url":"http:\\/\\/www.silkroad.news.cn\\/2012\\/1009\\/64.shtml","color":"","subtitle":"\\u4fc4\\u4ea5\\u4fc4\\u5dde\\u7acb","suburl":"","description":"\\u4e00\\u5929\\u5185\\u5f15\\u53d1\\u767e\\u4e07\\u7f51\\u53cb\\u56f4\\u89c2\\uff01\\u7f8e\\u56fd\\u4fc4\\u4ea5\\u4fc4\\u5dde\\u7acb\\u5927\\u5b66\\u519b\\u4e50\\u56e2\\u9707\\u64bc\\u8868\\u6f14\\u81f4\\u656c\\u7535\\u5b50\\u6e38\\u620f\\uff01\\u7b2c6\\u5206\\u949f\\u7684\\u8868\\u6f14\\u7b80\\u76f4\\u592a\\u795e\\u4e86\\uff01\\u4ed6\\u4eec\\u592a\\u725bA\\u4e86\\uff01\\u89e6\\u52a8\\u4f60\\u7684\\u7ae5\\u5e74\\u56de\\u5fc6\\u4e86\\u5417\\uff1f\\u8fd9\\u4e2a\\u5fc5\\u987b\\u819c\\u62dc\\uff01\\uff01\\uff01","thumb":"2012\\/1009\\/1349757123242.jpg","time":"1349757120"}]', NULL, NULL, NULL, '<div class="m-accordion js-accordion">\n<ul>\n    <!--{loop $data $k $r}-->\n    <li class="m-accordion-item top">\n        <a href="{$r[url]}" target="_blank" title="{$r[title]}" {if $k == 0}style="display:none;"{/if}>{str_natcut($r[title], 20, '''')}</a>\n        <div class="m-accordion-thumb ov" {if $k != 0}style="display:none;"{/if}>\n            <img class="thumb fl-l" src="{thumb($r[thumb], 90, 90)}" width="90" height="90" alt="" /><p><a href="{$r[url]}" target="_blank" title="{$r[title]}">{str_natcut($r[title], 36, '''')}</a></p>\n        </div>\n    </li>\n    <!--{/loop}-->\n</ul>\n</div>', 'html', NULL, 5, 300, 0, '{"system_fields":{"contentid":{"checked":"1","func":"intval"},"title":{"checked":"1","required":"1","min_length":"","max_length":""},"url":{"checked":"1"},"color":{"checked":"1"},"icon":{"checked":"1"},"iconsrc":{"checked":"1"},"subtitle":{"checked":"1","min_length":"","max_length":""},"suburl":{"checked":"1"},"thumb":{"checked":"1","width":"","height":""},"description":{"checked":"1","min_length":"","max_length":""},"time":{"checked":"1","func":"section_fields_strtotime"}},"custom_fields":{"text":[],"name":[]}}', 1426917537, 1426917236, 0, 0, 1426838233, 1, 1342517911, 1, '5条 第一条为图文描述', 1, 0, 'section/list.html', 0, 10),
(56, 1, 'push', '推荐文章', NULL, '[{"contentid":170,"icon":"blank","iconsrc":"","title":"\\u6628\\u65e5\\uff0c\\u56fd\\u5bb6\\u4e3b\\u5e2d\\u4e60\\u8fd1\\u5e73\\u5728\\u5317\\u4eac\\u4eba\\u6c11\\u5927\\u4f1a\\u5802\\u4f1a\\u89c1\\u7f8e\\u56fd\\u524d\\u56fd\\u52a1\\u537f\\u57fa\\u8f9b\\u683c","color":"","url":"http:\\/\\/www.silkroad.news.cn\\/2015\\/0319\\/170.shtml","subtitle":"","suburl":"","thumb":"","description":"\\u535a\\u9ccc\\u4e9a\\u6d32\\u8bba\\u575b2015\\u5e74\\u5e74\\u4f1a\\u5c06\\u4e8e3\\u670826\\u65e5\\u81f329\\u65e5\\u5728\\u4e2d\\u56fd\\u6d77\\u5357\\u7701\\u535a\\u9ccc\\u53ec\\u5f00\\uff0c\\u5e74\\u4f1a\\u4e3b\\u9898\\u4e3a\\u201c\\u4e9a\\u6d32\\u65b0\\u672a\\u6765\\uff1a\\u8fc8\\u5411\\u547d\\u8fd0\\u5171\\u540c\\u4f53\\u201d\\u3002","time":1426767502},{"contentid":85,"title":"\\u5f20\\u53ec\\u5fe0\\uff1aF-35\\u4ee5\\u7edd\\u5bf9\\u4f18\\u52bf\\u6218\\u80dcF\\/A-18\\u4e0e\\u53f0\\u98ce\\u8d62\\u5f97\\u65e5\\u672c\\u8ba2\\u5355","url":"http:\\/\\/www.silkroad.news.cn\\/2012\\/1009\\/85.shtml","color":"","subtitle":"\\u5f20\\u53ec\\u5fe0\\uff1aF-","suburl":"","description":"\\u65e5\\u672c\\u653f\\u5e9c\\u4e8e20\\u65e5\\u6b63\\u5f0f\\u51b3\\u5b9a\\u5c06F-35\\u9009\\u4e3a\\u822a\\u7a7a\\u81ea\\u536b\\u961f\\u4e0b\\u4e00\\u4ee3\\u4e3b\\u529b\\u6218\\u673a(FX)\\uff0c\\u9632\\u536b\\u7701\\u5f53\\u5929\\u516c\\u5e03\\u4e86\\u5bf9\\u5404\\u8bc4\\u4f30\\u9879\\u76ee\\u7684\\u6253\\u5206\\u8bc4\\u5ba1\\u7ed3\\u679c\\u3002\\u9690\\u5f62\\u6027\\u4f18\\u5f02\\u7684\\u7b2c\\u4e94 \\u4ee3\\u6218\\u673aF-35\\u5728\\u6027\\u80fd\\u3001\\u7ecf\\u8d39\\u3001\\u4fdd\\u517b\\u7b49\\u5404\\u6307\\u6807\\u4e2d\\u5747\\u83b7\\u6700\\u9ad8\\u5206\\u3001\\u4ee5\\u201c\\u538b\\u5012\\u6027\\u4f18\\u52bf\\u201d\\u6218\\u80dc\\u4e86\\u7ade\\u4e89\\u5bf9\\u624bF\\/A-18\\u53ca\\u201c\\u53f0\\u98ce\\u201d\\u6218\\u673a\\u3002","thumb":"2012\\/1009\\/1349775448375.jpg","time":"1349775540"},{"contentid":66,"title":"\\u4e0a\\u6d77\\u87cb\\u87c0\\u4ea4\\u6613\\u706b\\u7206 \\u4e00\\u53ea\\u87cb\\u87c0\\u80fd\\u5356\\u4e0a\\u4e07\\u5143","url":"http:\\/\\/www.silkroad.news.cn\\/2012\\/1009\\/66.shtml","color":"","subtitle":"\\u4e0a\\u6d77\\u87cb\\u87c0\\u4ea4","suburl":"","description":"\\u4e0a\\u6d77\\u87cb\\u87c0\\u4ea4\\u6613\\u706b\\u7206 \\u4e00\\u53ea\\u87cb\\u87c0\\u80fd\\u5356\\u4e0a\\u4e07\\u5143","thumb":"2012\\/1009\\/1349757398203.jpg","time":"1349757360"},{"contentid":65,"title":"\\u6c14\\u8d28\\u5973\\u795e\\u5b87\\u8f69\\u7ffb\\u5531\\u300a\\u4e0d\\u518d\\u8ba9\\u4f60\\u5b64\\u5355\\u300b","url":"http:\\/\\/www.silkroad.news.cn\\/2012\\/1009\\/65.shtml","color":"","subtitle":"\\u6c14\\u8d28\\u5973\\u795e\\u5b87","suburl":"","description":"\\u6f14\\u5531:\\u5b87\\u8f69\\n\\u526a\\u8f91&\\u8c03\\u8272:\\u5730\\u4e3b_L\\n\\u5b57\\u5e55:\\u5730\\u4e3b_L\\n\\u97f3\\u4e50\\u652f\\u6301:@\\u590d\\u4e50\\u73ed","thumb":"2012\\/1009\\/1349757234658.jpg","time":"1349757180"},{"contentid":59,"title":"\\u660e\\u5e741\\u67081\\u65e5\\u8d77\\u95ef\\u7ea2\\u706f\\u62636\\u5206 \\u56de\\u987e\\u95ef\\u7ea2\\u706f\\u9020\\u6210\\u7684\\u60e8\\u6848","url":"http:\\/\\/www.silkroad.news.cn\\/2012\\/1009\\/59.shtml","color":"","subtitle":"\\u660e\\u5e741\\u67081\\u65e5","suburl":"","description":"\\u660e\\u5e741\\u67081\\u65e5\\u8d77\\u95ef\\u7ea2\\u706f\\u62636\\u5206 \\u56de\\u987e\\u95ef\\u7ea2\\u706f\\u9020\\u6210\\u7684\\u60e8\\u6848","thumb":"2012\\/1009\\/1349755528603.jpg","time":"1349755080"}]', NULL, NULL, NULL, '<!--{loop $data $k $r}-->\n<!--{if $k > 0}--><div class="hr10"></div><!--{/if}-->\n<div class="ov imagetext">\n    <a href="{$r[url]}" target="_blank" title="{$r[title]}" class="fl-l"><img src="{thumb($r[thumb], 100, 76)}" width="100" height="76" alt=""></a>\n    <p><a href="{$r[url]}" target="_blank" title="{$r[title]}">{str_natcut($r[title], 35, '''')}</a></p>\n</div>\n<!--{/loop}-->\n', 'html', NULL, 5, 300, 0, '{"system_fields":{"contentid":{"checked":"1","func":"intval"},"title":{"checked":"1","required":"1","min_length":"","max_length":""},"url":{"checked":"1"},"color":{"checked":"1"},"icon":{"checked":"1"},"iconsrc":{"checked":"1"},"subtitle":{"checked":"1","min_length":"","max_length":""},"suburl":{"checked":"1"},"thumb":{"checked":"1","width":"","height":""},"description":{"checked":"1","min_length":"","max_length":""},"time":{"checked":"1","func":"section_fields_strtotime"}},"custom_fields":{"text":[],"name":[]}}', 1426917537, 1426917236, 0, 0, 1426822458, 1, 1342518363, 1, '权重85', 1, 0, 'section/list.html', 0, 10),
(75, 1, 'auto', '汽车频道热门车型', '                   <!-- 楼盘 -->\n                   <div class="txt-list-r">\n                      <div class="title"><a href="" class="f-l">热门车型</a><var class="f-r">价格区间</var></div>\n                       <ul>\n                          <li>\n                              <a href="" class="cor-f30">朗逸</a>\n                             <span  class="cor-f30">111.28-16.28万元</span>\n                          </li>\n                         <li class="bg-f4f4">\n                              <a href="" class="cor-f30">途观</a>\n                             <span class="cor-f30">219.98-30.98万元</span>\n                           </li>\n                         <li>\n                              <a href="" class="cor-f30">速腾</a>\n                             <span  class="cor-f30">313.18-18.58万元</span>\n                          </li>\n                         <li class="bg-f4f4">\n                              <a href="">奥迪A4L</a>\n                              <span>427.28-46.45万元</span>\n                           </li>\n                         <li>\n                              <a href="">福克斯两厢</a>\n                              <span>510.48-16.29万元</span>\n                           </li>\n                     </ul>\n                 </div>\n                    <!-- 楼盘 -->', '                 <!-- 楼盘 -->\n                   <div class="txt-list-r">\n                      <div class="title"><a href="" class="f-l">热门车型</a><var class="f-r">价格区间</var></div>\n                       <ul>\n                          <li>\n                              <a href="" class="cor-f30">朗逸</a>\n                             <span  class="cor-f30">111.28-16.28万元</span>\n                          </li>\n                         <li class="bg-f4f4">\n                              <a href="" class="cor-f30">途观</a>\n                             <span class="cor-f30">219.98-30.98万元</span>\n                           </li>\n                         <li>\n                              <a href="" class="cor-f30">速腾</a>\n                             <span  class="cor-f30">313.18-18.58万元</span>\n                          </li>\n                         <li class="bg-f4f4">\n                              <a href="">奥迪A4L</a>\n                              <span>427.28-46.45万元</span>\n                           </li>\n                         <li>\n                              <a href="">福克斯两厢</a>\n                              <span>510.48-16.29万元</span>\n                           </li>\n                     </ul>\n                 </div>\n                    <!-- 楼盘 -->', NULL, NULL, NULL, '', 'html', 0, 0, 0, 0, NULL, 1423123360, 1423123360, 0, 0, 1423206207, 1, 1343015725, 1, '', 0, 0, NULL, NULL, 10),
(76, 1, 'push', '活动中心', '[]', '[{"contentid":68,"title":"CmsTop\\u5ba3\\u4f20\\u7247","url":"http:\\/\\/www.silkroad.news.cn\\/2012\\/1009\\/68.shtml","color":"","subtitle":"CmsTop\\u5ba3\\u4f20","suburl":"","description":"CmsTop\\u5ba3\\u4f20\\u7247","thumb":"2012\\/1009\\/1349758019346.jpg","time":"1349758037"},{"contentid":59,"title":"\\u660e\\u5e741\\u67081\\u65e5\\u8d77\\u95ef\\u7ea2\\u706f\\u62636\\u5206 \\u56de\\u987e\\u95ef\\u7ea2\\u706f\\u9020\\u6210\\u7684\\u60e8\\u6848","url":"http:\\/\\/www.silkroad.news.cn\\/2012\\/1009\\/59.shtml","color":"","subtitle":"\\u660e\\u5e741\\u67081\\u65e5","suburl":"","description":"\\u660e\\u5e741\\u67081\\u65e5\\u8d77\\u95ef\\u7ea2\\u706f\\u62636\\u5206 \\u56de\\u987e\\u95ef\\u7ea2\\u706f\\u9020\\u6210\\u7684\\u60e8\\u6848","thumb":"2012\\/1009\\/1349755528603.jpg","time":"1349755080"},{"contentid":33,"title":"\\u7ae0\\u5b50\\u6021\\u5426\\u8ba4\\u4e0e\\u6492\\u8d1d\\u5b81\\u5a5a\\u8baf \\u8c03\\u4f83\\uff1a\\u8bf7\\u67ec\\u53d1\\u6211\\u4e00\\u5f20","url":"http:\\/\\/www.silkroad.news.cn\\/2012\\/1008\\/33.shtml","color":"","subtitle":"\\u7ae0\\u5b50\\u6021\\u5426\\u8ba4","suburl":"","description":"\\u7ae0\\u5b50\\u6021\\u5426\\u8ba4\\u4e0e\\u6492\\u8d1d\\u5b81\\u5a5a\\u8baf \\u8c03\\u4f83\\uff1a\\u8bf7\\u67ec\\u53d1\\u6211\\u4e00\\u5f20","thumb":"2012\\/1008\\/1349697641237.jpg","time":"1349690940"}]', NULL, NULL, NULL, '<!--{loop $data $k $r}-->\n<div class="shoveler-item fl-l ov">\n    <ul class="m-imagetitle video-imagetitle">\n        <li class="item js-overlay">\n            <div class="hr20"></div>\n            <div class="photo pos-r ov">\n                <a href="{$r[url]}" target="_blank" title="{$r[title]}"><img src="{thumb($r[thumb], 300, 150)}" width="300" height="150" alt=""></a>\n                <p class="pos-a text"><a href="{$r[url]}" target="_blank" title="{$r[title]}" class=''title''>{$r[title]}</a></p>\n                <div class="_overlay pos-a"></div>\n            </div>\n        </li>\n    </ul>\n</div>\n<!--{/loop}-->', 'html', NULL, 3, 0, 0, '{"system_fields":{"contentid":{"checked":"1","func":"intval"},"title":{"checked":"1","required":"1","min_length":"","max_length":""},"url":{"checked":"1"},"color":{"checked":"1"},"icon":{"checked":"1"},"iconsrc":{"checked":"1"},"subtitle":{"checked":"1","min_length":"","max_length":""},"suburl":{"checked":"1"},"thumb":{"checked":"1","width":"","height":""},"description":{"checked":"1","min_length":"","max_length":""},"time":{"checked":"1","func":"section_fields_strtotime"}},"custom_fields":{"text":[],"name":[]}}', 1426917237, 1426917236, 0, 0, 1426060456, 1, 1343015780, 1, '', 1, 0, 'section/list.html', 0, 10),
(77, 1, 'auto', '房产频道热门楼盘', '                   <!-- 楼盘 -->\n                   <div class="txt-list-r">\n                      <div class="title"><a href="" class="f-l">楼盘</a><var class="f-r">单价</var><var class="f-r">区域</var></div>\n                      <ul>\n                          <li>\n                              <a href="">东湖湾金湖街</a>\n                             <span>待定</span>\n                               <span>朝阳</span>\n                           </li>\n                         <li class="bg-f4f4">\n                              <a href="">绿地起航国际</a>\n                             <span>优惠</span>\n                               <span>房山</span>\n                           </li>\n                         <li>\n                              <a href="">星悦国际</a>\n                               <span>12800</span>\n                                <span>通州</span>\n                           </li>\n                         <li class="bg-f4f4">\n                              <a href="">鹭峯国际</a>\n                               <span>13800</span>\n                                <span>顺义</span>\n                           </li>\n                         <li>\n                              <a href="">领秀慧谷</a>\n                               <span>20000</span>\n                                <span>昌平</span>\n                           </li>\n                     </ul>\n                 </div>\n                    <!-- 楼盘 -->', '                 <!-- 楼盘 -->\n                   <div class="txt-list-r">\n                      <div class="title"><a href="" class="f-l">楼盘</a><var class="f-r">单价</var><var class="f-r">区域</var></div>\n                      <ul>\n                          <li>\n                              <a href="">东湖湾金湖街</a>\n                             <span>待定</span>\n                               <span>朝阳</span>\n                           </li>\n                         <li class="bg-f4f4">\n                              <a href="">绿地起航国际</a>\n                             <span>优惠</span>\n                               <span>房山</span>\n                           </li>\n                         <li>\n                              <a href="">星悦国际</a>\n                               <span>12800</span>\n                                <span>通州</span>\n                           </li>\n                         <li class="bg-f4f4">\n                              <a href="">鹭峯国际</a>\n                               <span>13800</span>\n                                <span>顺义</span>\n                           </li>\n                         <li>\n                              <a href="">领秀慧谷</a>\n                               <span>20000</span>\n                                <span>昌平</span>\n                           </li>\n                     </ul>\n                 </div>\n                    <!-- 楼盘 -->', NULL, NULL, NULL, '', 'html', 0, 0, 0, 0, NULL, 1423123360, 1423123360, 0, 0, 1423206588, 1, 1343015950, 1, '', 0, 0, NULL, NULL, 10);
INSERT INTO `cmstop_section` (`sectionid`, `pageid`, `type`, `name`, `origdata`, `data`, `url`, `method`, `args`, `template`, `output`, `width`, `rows`, `frequency`, `check`, `fields`, `nextupdate`, `published`, `locked`, `lockedby`, `updated`, `updatedby`, `created`, `createdby`, `description`, `status`, `list_enabled`, `list_template`, `list_pagesize`, `list_pages`) VALUES
(78, 1, 'push', '底部图片组', '[]', '[{"contentid":71,"title":"\\u5b9c\\u660c\\u4e07\\u4eba\\u76f8\\u4eb2\\u8282 \\u573a\\u9762\\u5341\\u5206\\u706b\\u7206","url":"http:\\/\\/www.silkroad.news.cn\\/2012\\/1017\\/71.shtml","color":"","subtitle":"\\u5b9c\\u660c\\u4e07\\u4eba\\u76f8","suburl":"","description":"\\u5b9c\\u660c\\u4e07\\u4eba\\u76f8\\u4eb2\\u8282","thumb":"http:\\/\\/media.cmstop.com\\/pic\\/20120412\\/ce725921f968c5a3449b7f44a4a8d27c-1.jpg","time":"1350442188"},{"contentid":74,"title":" \\u5f20\\u53ec\\u5fe0\\uff1a\\u4e2d\\u56fd30\\u591a\\u5e74\\u5fcd\\u8fb1\\u8d1f\\u91cd\\u88ab\\u8d8a\\u5357\\u8bef\\u89e3\\u4e3a\\u5bb3\\u6015","url":"http:\\/\\/www.silkroad.news.cn\\/2012\\/1017\\/74.shtml","color":"","subtitle":" \\u5f20\\u53ec\\u5fe0\\uff1a","suburl":"","description":"\\u7279\\u522b\\u63a8\\u8350\\u817e\\u8baf\\u72ec\\u5bb6\\u680f\\u76ee\\uff1a\\u66f4\\u591a\\u7cbe\\u5f69\\u5c3d\\u5728\\u300a\\u53ec\\u5fe0\\u8bf4\\u519b\\u4e8b\\u300b\\n\\u8d8a\\u5357\\u4e0d\\u7ba1\\u662f\\u5728\\u7ecf\\u6d4e\\u4e0a\\u8fd8\\u662f\\u519b\\u4e8b\\u4e0a\\u8fdc\\u8fdc\\u843d\\u540e\\u4e8e\\u4e2d\\u56fd\\uff0c\\u90a3\\u5b83\\u4e3a\\u4ec0\\u4e48\\u8fd8\\u6709\\u81ea\\u4fe1\\u548c\\u80c6\\u91cf\\u5982\\u6b64\\u5f3a\\u786c\\u7684\\u5728\\u5357\\u6d77\\u5e26\\u5934\\u8ddf\\u4e2d\\u56fd\\u5bf9\\u6297\\u5462\\uff1f\\n\\u9996\\u5148\\u8d8a\\u5357\\u5b83\\u6709\\u8fd9\\u6837\\u7684\\u60c5\\u8282\\uff0c\\u5f3a\\u70c8\\u7684\\u6c11\\u65cf\\u4e3b\\u4e49\\u3002\\u4f60\\u770b\\u56fd\\u9645\\u4e0a\\u7684\\u5f88\\u591a\\u7684\\u95ee\\u9898\\uff0c\\u4f60\\u770b\\u5229\\u6bd4\\u4e9a\\u4e5f\\u597d\\uff0c\\u4f60\\u770b\\u963f\\u5bcc\\u6c57\\u4e5f\\u597d\\uff0c\\u4f60\\u770b\\u4f0a\\u62c9\\u514b\\u4e5f\\u597d\\uff0c\\u6bcf\\u4e00\\u4e2a\\u6c11\\u65cf\\u90fd\\u6709\\u6bcf\\u4e00\\u4e2a\\u6c11\\u65cf\\u7684\\u60c5\\u8282\\u3002","thumb":"2012\\/0925\\/1348546274802.jpg","time":"1350442140"},{"contentid":13,"title":"\\u7f8e\\u56fd\\u516c\\u53f8\\u53d1\\u5c04\\u201c\\u9f99\\u201d\\u98de\\u8239 \\u9996\\u5411\\u56fd\\u9645\\u7a7a\\u95f4\\u7ad9\\u8fd0\\u8d27","url":"http:\\/\\/www.silkroad.news.cn\\/2012\\/1010\\/13.shtml","color":"","subtitle":"\\u7f8e\\u56fd\\u516c\\u53f8\\u53d1","suburl":"","description":"\\u7f8e\\u56fd\\u516c\\u53f8\\u53d1\\u5c04\\u201c\\u9f99\\u201d\\u98de\\u8239 \\u9996\\u5411\\u56fd\\u9645\\u7a7a\\u95f4\\u7ad9\\u8fd0\\u8d27","thumb":"2012\\/1008\\/1349698479695.jpg","time":"1349850060"},{"contentid":84,"title":"\\u5f20\\u53ec\\u5fe0\\uff1a\\u6b7c20\\u7784\\u51c6F-22\\u800c\\u8bbe\\u8ba1 \\u9690\\u8eab\\u5236\\u7a7a\\u5f88\\u5389\\u5bb3","url":"http:\\/\\/www.silkroad.news.cn\\/2012\\/1009\\/84.shtml","color":"","subtitle":"\\u5f20\\u53ec\\u5fe0\\uff1a\\u6b7c","suburl":"","description":"\\u65e5\\u672c\\u51b3\\u5b9a\\u8d2d\\u4e70\\u5dee\\u4e0d\\u591a40 \\u67b6\\u5de6\\u53f3\\u7684\\u7f8e\\u56fd\\u6700\\u5148\\u8fdb\\u7684F\\u201435\\u8054\\u5408\\u653b\\u51fb\\u673a\\uff0c\\u4f5c\\u4e3a\\u4e3b\\u529b\\u6218\\u673a\\uff0c\\u90a3\\u4e48F\\u201435\\u7a76\\u7adf\\u662f\\u54ea\\u4e9b\\u7279\\u70b9\\uff1f\\u65e5\\u672c\\u8ba2\\u8d2dF\\u201435\\u662f\\u5426\\u5bf9\\u7740\\u6b7c20\\u6765\\u7740\\u5462\\uff1f\\u4eca\\u5929\\u6211\\u4eec\\u8bf7\\u6765\\u5f20\\u53ec\\u5fe0\\u6559\\u6388\\u4e3a \\u5927\\u5bb6\\u89e3\\u8bfb\\u4e00\\u4e0b","thumb":"2012\\/1009\\/1349775448649.jpg","time":"1349775420"},{"contentid":66,"title":"\\u4e0a\\u6d77\\u87cb\\u87c0\\u4ea4\\u6613\\u706b\\u7206 \\u4e00\\u53ea\\u87cb\\u87c0\\u80fd\\u5356\\u4e0a\\u4e07\\u5143","url":"http:\\/\\/www.silkroad.news.cn\\/2012\\/1009\\/66.shtml","color":"","subtitle":"\\u4e0a\\u6d77\\u87cb\\u87c0\\u4ea4","suburl":"","description":"\\u4e0a\\u6d77\\u87cb\\u87c0\\u4ea4\\u6613\\u706b\\u7206 \\u4e00\\u53ea\\u87cb\\u87c0\\u80fd\\u5356\\u4e0a\\u4e07\\u5143","thumb":"2012\\/1009\\/1349757398203.jpg","time":"1349757360"},{"contentid":59,"title":"\\u660e\\u5e741\\u67081\\u65e5\\u8d77\\u95ef\\u7ea2\\u706f\\u62636\\u5206 \\u56de\\u987e\\u95ef\\u7ea2\\u706f\\u9020\\u6210\\u7684\\u60e8\\u6848","url":"http:\\/\\/www.silkroad.news.cn\\/2012\\/1009\\/59.shtml","color":"","subtitle":"\\u660e\\u5e741\\u67081\\u65e5","suburl":"","description":"\\u660e\\u5e741\\u67081\\u65e5\\u8d77\\u95ef\\u7ea2\\u706f\\u62636\\u5206 \\u56de\\u987e\\u95ef\\u7ea2\\u706f\\u9020\\u6210\\u7684\\u60e8\\u6848","thumb":"2012\\/1009\\/1349755528603.jpg","time":"1349755080"},{"contentid":76,"title":"\\u5f20\\u53ec\\u5fe0\\uff1a\\u62c9\\u767b\\u6b7b\\u540e\\u7684\\u4ef7\\u503c\\u6bd4\\u4ed6\\u6d3b\\u7740\\u65f6\\u8fd8\\u5927","url":"http:\\/\\/www.silkroad.news.cn\\/2012\\/0926\\/76.shtml","color":"","subtitle":"\\u5f20\\u53ec\\u5fe0\\uff1a\\u62c9","suburl":"","description":"5\\u67081\\u65e5\\u7f8e\\u56fd\\u6d77\\u8c79\\u7a81\\u51fb\\u961f\\u51fb\\u6bd9\\u4e86\\u672c.\\u62c9\\u767b\\uff0c\\u4f46\\u662f\\u62c9\\u767b\\u4e4b\\u6b7b\\u8ff7\\u96fe\\u91cd\\u91cd\\uff0c\\u62c9\\u767b\\u771f\\u7684\\u6b7b\\u4e86\\u5417\\uff1f\\u6050\\u6016\\u88ad\\u51fb\\u5c31\\u6b64\\u505c\\u6b62\\u4e86\\u5417\\uff1f\\u6211\\u4eec\\u8be5\\u5982\\u4f55\\u9762\\u5bf9\\u540e\\u62c9\\u767b\\u65f6\\u4ee3\\uff0c\\u6211\\u4eec\\u4eca\\u5929\\u5c31\\u8bf7\\u6559\\u4e00\\u4e0b\\u519b\\u4e8b\\u4e13\\u5bb6\\u5f20\\u53ec\\u5fe0\\u6559\\u6388\\u3002","thumb":"2012\\/0926\\/1348622427562.jpg","time":"1348622460"}]', NULL, NULL, NULL, '<!--{loop $data $k $r}-->\n<div class="item a{($k+1)}">\n    <a href="{$r[url]}" target="_blank" title="{$r[title]}"><img {if $k == 1}src="{thumb($r[thumb], 394, 296)}"{else}src="{thumb($r[thumb], 195, 144)}"{/if} {if $k == 1}width="394" height="296"{else}width="195" height="144"{/if} alt=""></a>\n    <div class="text ie-rgba">\n        <a href="{$r[url]}" target="_blank" title="{$r[title]}">{$r[title]}</a>\n    </div>\n</div>\n<!--{/loop}-->', 'html', NULL, 7, 0, 0, '{"system_fields":{"contentid":{"checked":"1","func":"intval"},"title":{"checked":"1","required":"1","min_length":"","max_length":""},"url":{"checked":"1"},"color":{"checked":"1"},"icon":{"checked":"1"},"iconsrc":{"checked":"1"},"subtitle":{"checked":"1","min_length":"","max_length":""},"suburl":{"checked":"1"},"thumb":{"checked":"1","width":"","height":""},"description":{"checked":"1","min_length":"","max_length":""},"time":{"checked":"1","func":"section_fields_strtotime"}},"custom_fields":{"text":[],"name":[]}}', 1426917237, 1426917236, 0, 0, 1426508860, 1, 1343015984, 1, '', 1, 0, 'section/list.html', 0, 10),
(79, 1, 'auto', '首页导航', '                   <!-- 热门路线 -->\n                 <div class="txt-list-r">\n                      <div class="title"><a href="" class="f-l">热门路线</a><var class="f-r">单价</var></div>\n                     <ul>\n                          <li><a href="">冰岛环岛观鲸10日之旅</a><span class="cor-f30">￥36666</span></li>\n                            <li class="bg-f4f4"><a href="">北欧双峡湾、世界遗产卑尔根深度10日</a><span class="cor-f30">￥16200</span></li>\n                         <li><a href="">武夷山九曲溪、虎啸岩双卧四日</a><span class="cor-f30">￥1580</span></li>\n                          <li class="bg-f4f4"><a href="">神奇宁夏双卧四日</a><span class="cor-f30">￥2080</span></li>\n                            <li><a href="">华东六市+双水乡乌镇南浔+太湖游船7日</a><span class="cor-f30">￥1160</span></li>\n                     </ul>\n                 </div>\n                    <!-- 热门路线 -->', '<li>\n    <a href="{WWW_URL}cmstop/" title="思拓">思拓</a>\n    <a href="{WWW_URL}ent/" class="second" title="新闻">新闻</a>\n</li>\n<li>\n    <a href="{WWW_URL}auto/" title="汽车">汽车</a>\n     <a href="{WWW_URL}house/" class="second" title="房产">房产</a>\n</li>\n<li>\n    <a href="{WWW_URL}travel/" title="旅游">旅游</a>\n     <a href="{WWW_URL}ent/" class="second" title="娱乐">娱乐</a>\n</li>\n<li>\n    <a href="{WWW_URL}jiaoyu/" title="教育">教育</a>\n    <a href="http://video.silkroad.news.cn/" class="second" title="视频">视频</a>\n</li>\n<li>\n    <a href="http://photo.silkroad.news.cn/" title="图片">图片</a>\n    <a href="http://special.silkroad.news.cn/" class="second" title="专题">专题</a> \n</li>\n<li>\n    <a href="{WWW_URL}activity/" title="活动">活动</a>\n     <a href="{APP_URL}?app=baoliao" class="second" title="料">报料</a> \n</li>\n<li>\n    <a href="http://space.silkroad.news.cn/" title="专栏">专栏</a>\n    <a href="{WWW_URL}paper/" class="second" title="报纸">报纸</a>\n</li>\n<li>\n    <a href="{WWW_URL}magazine/" title="杂志">杂志</a>\n    <a href="{APP_URL}tags.php" class="second" title="标签">标签</a> \n</li>\n<li>\n    <a href="{APP_URL}roll.php" title="滚动">滚动</a>\n     <a href="{APP_URL}rank.php" class="second" title="排行">排行</a>\n</li>\n<li class="last">\n    <a href="{WWW_URL}about/mobile.shtml" title="手机">手机</a>\n     <a href="{APP_URL}digg.php" class="second" title="Digg">Digg</a>\n</li>', NULL, NULL, NULL, '', 'html', NULL, 0, 0, 0, NULL, 1426917237, 1426917236, 0, 0, 1426917234, 1, 1343016067, 1, '', 1, 0, NULL, NULL, 10),
(80, 1, 'auto', '页脚导航', '           <div class="foot-top-cont">\n               <ul class="footer-link">\n                  <li><a href="">公司简介</a></li>\n                  <li>｜</li>\n                    <li><a href="">公司资质</a></li>\n                  <li>｜</li>\n                    <li><a href="">思拓动态</a></li>\n                  <li>｜</li>\n                    <li><a href="">诚聘英才</a></li>\n                  <li>｜</li>\n                    <li><a href="">联系方式</a></li>\n              </ul>\n             <p>联系我们：cmstop@cmstop.com</p>\n         </div>', '<div class="foot-top-cont">\n   <ul class="footer-link">\n      <li><a href="{WWW_URL}about/">关于我们</a></li>\n       <li>｜</li>\n        <li><a href="{WWW_URL}about/contact.shtml">联系我们</a></li>\n      <li>｜</li>\n        <li><a href="{WWW_URL}about/jobs.shtml">加入我们</a></li>\n     <li>｜</li>\n        <li><a href="{WWW_URL}about/copyright.shtml">版权声明</a></li>\n        <li>｜</li>\n        <li><a href="{WWW_URL}about/mobile.shtml">手机访问</a></li>\n       <li>｜</li>\n        <li><a href="{APP_URL}map.php">网站地图</a></li>\n      <li>｜</li>\n        <li><a href="{APP_URL}{url(''guestbook'')}">留言反馈</a></li>\n     <li>｜</li>\n        <li><a href="{APP_URL}{url(''contribution/index/index'')}">我要投稿</a></li>\n  </ul>\n <p>联系我们：webmaster@cmstop.com</p>\n</div>', NULL, NULL, NULL, '', 'html', NULL, 0, 0, 0, NULL, 1426581010, 1426581010, 0, 0, 1426582547, 1, 1343016273, 1, '', 0, 0, NULL, NULL, 10),
(81, 1, 'html', '友情链接', '                   <!-- 友情链接 -->\n                 <div class="mod-grid">\n                        <div class="ov">\n                          <div class="title"><a class="words" title="" href="">友情链接</a></div>\n                       </div>\n                        <div class="m-main">\n                          <div>\n                             <ul class="friends-link">\n                                 <li><a href="">1新华08</a></li>\n                                 <li><a href="">蓝色理想</a></li>\n                                  <li><a href="">落伍者</a></li>\n                                   <li><a href="">站长网</a></li>\n                                   <li><a href="">A5源码</a></li>\n                                  <li><a href="">源码之家</a></li>\n                                  <li><a href="">BiaNews</a></li>\n                                   <li><a href="">火车头采集器</a></li>\n                                    <li><a href="">ShopNC</a></li>\n                                    <li><a href="">雨林木风</a></li>\n                                  <li><a href="">软件网</a></li>\n                                   <li><a href="">西部数码</a></li>\n                                  <li><a href="">华夏名网</a></li>\n                              </ul>   \n                          </div>\n                        </div>\n                    </div>  \n                  <!-- 友情链接 -->   ', '                    <!-- 友情链接 -->\n                 <div class="mod-grid">\n                        <div class="ov">\n                          <div class="title"><a class="words" title="" href="">友情链接</a></div>\n                       </div>\n                        <div class="m-main">\n                          <div>\n                             <ul class="friends-link">\n                                                                    <li><a href="http://www.php100.com/">PHP100</a></li>\n                                                                    <li><a href="http://www.im286.com">落伍者</a></li>\n                                                                    <li><a href="http://www.blueidea.com/">蓝色理想</a></li>\n                                                                    <li><a href="http://www.admin5.com">站长网</a></li>\n                                                                    <li><a href="http://www.soft6.com">软件网</a></li>\n                                                                    <li><a href="http://www.bianews.com/">BiaNews</a></li>\n                                                                    <li><a href="http://www.locoy.com">火车头采集器</a></li>\n                                                                    <li><a href="http://www.discuz.net/">Discuz!</a></li>\n                                                                    <li><a href="http://www.shopnc.net/">ShopNC</a></li>\n                                                                    <li><a href="http://www.php168.net">国微CMS</a></li>\n                                                                    <li><a href="http://www.phpchina.com/">PHPChina</a></li>\n                                                                    <li><a href="http://down.admin5.com/">A5源码</a></li>\n                                                                    <li><a href="http://down.chinaz.com">源码网</a></li>\n                                                                    <li><a href="http://kaiyuan.hudong.com">HDwiki</a></li>\n                                                                    <li><a href="http://www.west263.com">西部数码</a></li>\n                                                                    <li><a href="http://www.sudu.cn">华夏名网</a></li>\n                                                                    <li><a href="http://www.meixie.com">美鞋网</a></li>\n                               </ul>   \n                          </div>\n                        </div>\n                    </div>  \n                  <!-- 友情链接 -->   ', NULL, NULL, NULL, '', 'html', 0, 0, 0, 0, NULL, 1426041676, 1426041676, 0, 0, 1426044806, 1, 1343016308, 1, '', 0, 0, NULL, NULL, 10),
(82, 1, 'auto', '服务客户', '                   <!-- 服务客户 -->\n                 <div class="mod-grid">\n                        <div class="ov">\n                          <div class="title"><a class="words" title="" href="">服务客户</a></div>\n                           <ul id="tabmenu" class="tab-head">\n                                <li><a title="" href="" rel="tab" class="tabactive">报业</a></li><!-- 当前样式为class="tabactive" -->\n                                <li><a title="" href="" rel="tab" class="">广电</a></li>\n                                <li><a title="" href="" rel="tab" class="">杂志社</a></li>\n                               <li><a title="" href="" rel="tab" class="">网络媒体</a></li>\n                              <li><a title="" href="" rel="tab" class="">其他</a></li>\n                            </ul>\n                     </div>\n                        <div id="tabcontent" class="m-main">\n                          <div title="tab" style="display: block;">\n                             <ul class="friends-link">\n                                 <li><a href="">1新华08</a></li>\n                                 <li><a href="">蓝色理想</a></li>\n                                  <li><a href="">落伍者</a></li>\n                                   <li><a href="">站长网</a></li>\n                                   <li><a href="">A5源码</a></li>\n                                  <li><a href="">源码之家</a></li>\n                                  <li><a href="">BiaNews</a></li>\n                                   <li><a href="">火车头采集器</a></li>\n                                    <li><a href="">ShopNC</a></li>\n                                    <li><a href="">雨林木风</a></li>\n                                  <li><a href="">软件网</a></li>\n                                   <li><a href="">西部数码</a></li>\n                                  <li><a href="">华夏名网</a></li>\n                              </ul>   \n                          </div>\n                            <div title="tab" style="display: none;">\n                              <ul class="friends-link">\n                                 <li><a href="">2新华08</a></li>\n                                 <li><a href="">蓝色理想</a></li>\n                                  <li><a href="">落伍者</a></li>\n                                   <li><a href="">站长网</a></li>\n                                   <li><a href="">A5源码</a></li>\n                                  <li><a href="">源码之家</a></li>\n                                  <li><a href="">BiaNews</a></li>\n                                   <li><a href="">火车头采集器</a></li>\n                                    <li><a href="">ShopNC</a></li>\n                                    <li><a href="">雨林木风</a></li>\n                                  <li><a href="">软件网</a></li>\n                                   <li><a href="">西部数码</a></li>\n                                  <li><a href="">华夏名网</a></li>\n                              </ul>   \n                          </div>\n                            <div title="tab" style="display: none;">\n                              <ul class="friends-link">\n                                 <li><a href="">3新华08</a></li>\n                                 <li><a href="">蓝色理想</a></li>\n                                  <li><a href="">落伍者</a></li>\n                                   <li><a href="">站长网</a></li>\n                                   <li><a href="">A5源码</a></li>\n                                  <li><a href="">源码之家</a></li>\n                                  <li><a href="">BiaNews</a></li>\n                                   <li><a href="">火车头采集器</a></li>\n                                    <li><a href="">ShopNC</a></li>\n                                    <li><a href="">雨林木风</a></li>\n                                  <li><a href="">软件网</a></li>\n                                   <li><a href="">西部数码</a></li>\n                                  <li><a href="">华夏名网</a></li>\n                              </ul>   \n                          </div>\n                            <div title="tab" style="display: none;">\n                              <ul class="friends-link">\n                                 <li><a href="">4新华08</a></li>\n                                 <li><a href="">蓝色理想</a></li>\n                                  <li><a href="">落伍者</a></li>\n                                   <li><a href="">站长网</a></li>\n                                   <li><a href="">A5源码</a></li>\n                                  <li><a href="">源码之家</a></li>\n                                  <li><a href="">BiaNews</a></li>\n                                   <li><a href="">火车头采集器</a></li>\n                                    <li><a href="">ShopNC</a></li>\n                                    <li><a href="">雨林木风</a></li>\n                                  <li><a href="">软件网</a></li>\n                                   <li><a href="">西部数码</a></li>\n                                  <li><a href="">华夏名网</a></li>\n                              </ul>   \n                          </div>\n                            <div title="tab" style="display: none;">\n                              <ul class="friends-link">\n                                 <li><a href="">5新华08</a></li>\n                                 <li><a href="">蓝色理想</a></li>\n                                  <li><a href="">落伍者</a></li>\n                                   <li><a href="">站长网</a></li>\n                                   <li><a href="">A5源码</a></li>\n                                  <li><a href="">源码之家</a></li>\n                                  <li><a href="">BiaNews</a></li>\n                                   <li><a href="">火车头采集器</a></li>\n                                    <li><a href="">ShopNC</a></li>\n                                    <li><a href="">雨林木风</a></li>\n                                  <li><a href="">软件网</a></li>\n                                   <li><a href="">西部数码</a></li>\n                                  <li><a href="">华夏名网</a></li>\n                              </ul>   \n                          </div>\n                        </div>\n                    </div>\n                    <!--服务客户-->', '                 <!-- 服务客户 -->\n                 <div class="mod-grid">\n                        <div class="ov">\n                          <div class="title"><a class="words" title="" href="">服务客户</a></div>\n                           <ul id="tabmenu" class="tab-head">\n                                <li><a title="" href="http://www.cmstop.com/case/" rel="tab" class="tabactive">报业</a></li><!-- 当前样式为class="tabactive" -->\n                             <li><a title="" href="http://www.cmstop.com/case/" rel="tab" class="">广电</a></li>\n                             <li><a title="" href="http://www.cmstop.com/case/" rel="tab" class="">杂志社</a></li>\n                                <li><a title="" href="http://www.cmstop.com/case/" rel="tab" class="">网络媒体</a></li>\n                               <li><a title="" href="http://www.cmstop.com/case/" rel="tab" class="">其他</a></li>\n                         </ul>\n                     </div>\n                        <div id="tabcontent" class="m-main">\n                          <div title="tab" style="display: block;">\n                             <ul class="friends-link">\n                                 <li><a href="http://www.cmstop.com/2011/228.shtml" title="参考消息">参考消息</a></li>\n                                 <li><a href="http://www.cmstop.com/2011/227.shtml" title="新华社新华08">新华社新华08</a></li>\n                                   <li><a href="http://www.cmstop.com/2011/160.shtml" title="经济观察报">经济观察报</a></li>\n                                   <li><a href="http://www.cmstop.com/2011/161.shtml" title="内蒙古日报">内蒙古日报</a></li>\n                                   <li><a href="http://www.cmstop.com/2011/163.shtml" title="新安晚报">新安晚报</a></li>\n                                 <li><a href="http://www.cmstop.com/2011/176.shtml" title="南京日报">南京日报</a></li>\n                                 <li><a href="http://www.cmstop.com/2011/162.shtml" title="苏州日报">苏州日报</a></li>\n                                 <li><a href="http://www.cmstop.com/2011/226.shtml" title="宿迁日报">宿迁日报</a></li>\n                                 <li><a href="http://www.cmstop.com/2011/165.shtml" title="淮安日报">淮安日报</a></li>\n                                 <li><a href="http://www.cmstop.com/2011/164.shtml" title="新余日报">新余日报</a></li>\n                                 <li><a href="http://www.cmstop.com/2011/225.shtml" title="九江日报">九江日报</a></li>\n                                 <li><a href="http://www.cmstop.com/2011/334.shtml" title="开封日报">开封日报</a></li>\n                                 <li><a href="http://www.cmstop.com/2011/335.shtml" title="十堰日报">十堰日报</a></li>\n                                 <li><a href="http://www.cmstop.com/2011/224.shtml" title="昭通日报">昭通日报</a></li>\n                                 <li><a href="http://www.cmstop.com/2011/222.shtml" title="中国航空报">中国航空报</a></li>\n                                   <li><a href="http://www.cmstop.com/2011/294.shtml" title="荆楚网">荆楚网</a></li>\n                                   <li><a href="http://www.cmstop.com/2010/304.shtml" title="证券时报">证券时报</a></li>\n                                 <li><a href="http://www.cmstop.com/2010/343.shtml" title="中国企业报">中国企业报</a></li>\n                               </ul>   \n                          </div>\n                            <div title="tab" style="display: none;">\n                              <ul class="friends-link">\n                                 <li><a title="山东电视台" href="http://www.cmstop.com/2011/170.shtml">山东电视台</a></li>\n                                   <li><a title="黑龙江电视台" href="http://www.cmstop.com/2011/167.shtml">黑龙江电视台</a></li>\n                                 <li><a title="湖北电视台" href="http://www.cmstop.com/2011/168.shtml">湖北电视台</a></li>\n                                   <li><a href="http://www.cmstop.com/2011/232.shtml" title="广东电视台">广东电视台</a></li>\n                                   <li><a href="http://www.cmstop.com/2011/169.shtml" title="贵州广电">贵州广电</a></li>\n                                 <li><a href="http://www.cmstop.com/2011/171.shtml" title="杭州萧山区广电">杭州萧山区广电</a></li>\n                                   <li><a href="http://www.cmstop.com/2011/172.shtml" title="上饶电视台">上饶电视台</a></li>\n                                   <li><a href="http://www.cmstop.com/2011/281.shtml" title="台山电视台">台山电视台</a></li>\n                                   <li><a href="http://www.cmstop.com/2010/306.shtml" title="日照广播电视台">日照广播电视台</a></li>\n                                   <li><a href="http://www.cmstop.com/2010/338.shtml" title="商洛电视台">商洛电视台</a></li>\n                               </ul>   \n                          </div>\n                            <div title="tab" style="display: none;">\n                              <ul class="friends-link">\n                                 <li><a href="http://www.cmstop.com/2012/313.shtml" title="《ELLE》杂志">《ELLE》杂志</a></li>\n                                 <li><a href="http://www.cmstop.com/2012/340.shtml" title="《嘉人》杂志">《嘉人》杂志</a></li>\n                                 <li><a href="http://www.cmstop.com/2012/288.shtml" title="《名车志》杂志">《名车志》杂志</a></li>\n                                   <li><a href="http://www.cmstop.com/2011/174.shtml" title="《三联生活周刊》杂志">《三联生活周刊》杂志</a></li>\n                                 <li><a href="http://www.cmstop.com/2011/173.shtml" title="《中国企业家》杂志">《中国企业家》杂志</a></li>\n                                   <li><a href="http://www.cmstop.com/2011/175.shtml" title="《女友》杂志">《女友》杂志</a></li>\n                             </ul>   \n                          </div>\n                            <div title="tab" style="display: none;">\n                              <ul class="friends-link">\n                                 <li><a href="http://www.cmstop.com/2011/184.shtml" title="华军软件园">华军软件园</a></li>\n                                   <li><a href="http://www.cmstop.com/2011/187.shtml" title="优米网">优米网</a></li>\n                                   <li><a href="http://www.cmstop.com/2011/185.shtml" title="TechWeb">TechWeb</a></li>\n                                   <li><a href="http://www.cmstop.com/2011/183.shtml" title="金山词霸">金山词霸</a></li>\n                                 <li><a href="http://www.cmstop.com/2011/289.shtml" title="7K7K小游戏">7K7K小游戏</a></li>\n                                   <li><a href="http://www.cmstop.com/2011/229.shtml" title="站长之家">站长之家</a></li>\n                                 <li><a href="http://www.cmstop.com/2011/291.shtml" title="亿房网">亿房网</a></li>\n                                   <li><a href="http://www.cmstop.com/2011/286.shtml" title="挖贝网">挖贝网</a></li>\n                                   <li><a href="http://www.cmstop.com/2011/261.shtml" title="薄荷女人网">薄荷女人网</a></li>\n                                   <li><a href="http://www.cmstop.com/2011/242.shtml" title="中国日报网 网视中国">中国日报网 网视中国</a></li>\n                             </ul>   \n                          </div>\n                            <div title="tab" style="display: none;">\n                              <ul class="friends-link">\n                                 <li><a href="http://www.candou.com/" title="蚕豆网">蚕豆网</a></li>\n                                 <li><a href="http://www.cmstop.com/2012/333.shtml" title="动米网">动米网</a></li>\n                                   <li><a href="http://www.cmstop.com/2012/329.shtml" title="上学网">上学网</a></li>\n                                   <li><a href="http://www.cmstop.com/2011/179.shtml" title="中国出版集团">中国出版集团</a></li>\n                                 <li><a href="http://www.cmstop.com/2011/237.shtml" title="武汉热线">武汉热线</a></li>\n                                 <li><a href="http://www.cmstop.com/2011/182.shtml" title="中国金融认证中心">中国金融认证中心</a></li>\n                                 <li><a href="http://www.cmstop.com/2011/239.shtml" title="中国大学生在线">中国大学生在线</a></li>\n                                   <li><a href="http://www.cmstop.com/2011/238.shtml" title="中共北京市委讲师团">中共北京市委讲师团</a></li>\n                                   <li><a href="http://www.cmstop.com/2011/236.shtml" title="中国会计视野网">中国会计视野网</a></li>\n                                   <li><a href="http://www.cmstop.com/2011/235.shtml" title="环球家电网">环球家电网</a></li>\n                                   <li><a href="http://www.cmstop.com/2011/234.shtml" title="家电网">家电网</a></li>\n                                   <li><a href="http://www.cmstop.com/2011/260.shtml" title="先行电力网">先行电力网</a></li>\n                                   <li><a href="http://www.cmstop.com/2011/191.shtml" title="海峡财讯">海峡财讯</a></li>\n                                 <li><a href="http://www.cmstop.com/2011/192.shtml" title="天和经济研究所">天和经济研究所</a></li>\n                                   <li><a href="http://www.cmstop.com/2011/193.shtml" title="上海金山区教育局">上海金山区教育局</a></li>\n                                 <li><a href="http://www.cmstop.com/2011/233.shtml" title="大连理工大学">大连理工大学</a></li>\n                                 <li><a href="http://www.cmstop.com/2011/262.shtml" title="新讯网">新讯网</a></li>\n                               </ul>   \n                          </div>\n                        </div>\n                    </div>\n                    <!--服务客户-->', NULL, NULL, NULL, '', 'html', 0, 0, 0, 0, NULL, 1426041676, 1426041676, 0, 0, 1426044809, 1, 1343016352, 1, '', 0, 0, NULL, NULL, 10),
(83, 1, 'auto', '报纸杂志推荐', '             <div class="foot-bottom-r">\n                   <div class="v-pic">\n                       <div class="thumb">\n                           <div class="opa"></div>\n                           <span class="info">2012年第3期总第666期</span>\n                          <a title="" href=""><img alt="" src="{IMG_URL}templates/{TEMPLATE}/img/thumb/w160-h213.png" /></a>\n                        </div>\n                        <div class="ov btn">\n                          <a title="" href="" class="more">更多杂志</a>\n                         <a title="" href="" class="online">在线阅读</a>\n                       </div>\n                    </div>\n                    <div class="v-pic">\n                       <div class="thumb">\n                           <div class="opa"></div>\n                           <span class="info">2012年第3期总第666期</span>\n                          <a title="" href=""><img alt="" src="{IMG_URL}templates/{TEMPLATE}/img/thumb/w160-h213.png" /></a>\n                        </div>\n                        <div class="ov btn">\n                          <a title="" href="" class="more">更多杂志</a>\n                         <a title="" href="" class="online">在线阅读</a>\n                       </div>\n                    </div>\n                </div>', '              <div class="foot-bottom-r">\n                   <div class="v-pic">\n                       <div class="thumb">\n                           <div class="opa"></div>\n                           <span class="info">2012年第3期总第666期</span>\n                          <a title="" href=""><img alt="" src="{IMG_URL}templates/{TEMPLATE}/img/thumb/w160-h213.png" /></a>\n                        </div>\n                        <div class="ov btn">\n                          <a title="" href="" class="more">更多杂志</a>\n                         <a title="" href="" class="online">在线阅读</a>\n                       </div>\n                    </div>\n                    <div class="v-pic">\n                       <div class="thumb">\n                           <div class="opa"></div>\n                           <span class="info">2012年第3期总第666期</span>\n                          <a title="" href=""><img alt="" src="{IMG_URL}templates/{TEMPLATE}/img/thumb/w160-h213.png" /></a>\n                        </div>\n                        <div class="ov btn">\n                          <a title="" href="" class="more">更多杂志</a>\n                         <a title="" href="" class="online">在线阅读</a>\n                       </div>\n                    </div>\n                </div>', NULL, NULL, NULL, '', 'html', 0, 0, 0, 0, NULL, 1426041676, 1426041676, 0, 0, 1426044798, 1, 1343016395, 1, '', 0, 0, NULL, NULL, 10),
(84, 16, 'auto', '关于我们', '<img src="{IMG_URL}templates/{TEMPLATE}/img/thumb/w720-h200.png" alt="" width="720" height="200" />\n<p>北京思拓合众科技有限公司（CmsTop Inc.）是国内领先的内容管理系统软件与服务提供商。公司创始人钟胜辉于2009年8月创办， 位于北京中关村上地高科技园区，是一家拥有自主知识产权的高科技软件企业。公司拥有产品策划、技术研发、市场推广、网站优化等各方面的优秀人才，团队成员 具有多年大型建站软件的研发和运营经验，长期致力于解决大中型网站资讯发布管理需求。</p>\n<p>CmsTop 是公司专为大中型网站资讯管理需求而设计的一款内容管理系统软件。CmsTop 专注于资讯领域应用，适合于网络媒体、传统媒体、政府和大 中型企业等，提供全面的内容展现形式，自由的页面维护能力，专业的运营分析报告，高效的操作流程，是多人协作追求高质量内容网站的首选建站利器。</p>\n<p>以服务客户为宗旨，以推动互联网信息产业的发展为己任，专注于资讯领域内容管理系统软件研发，是我们始终不变的追求。</p>', '<p>北京思拓合众科技有限公司（CmsTop Inc.）是国内领先的内容管理系统软件与服务提供商。公司创始人钟胜辉于2009年8月创办， 位于北京中关村上地高科技园区，是一家拥有自主知识产权的高科技软件企业。公司拥有产品策划、技术研发、市场推广、网站优化等各方面的优秀人才，团队成员 具有多年大型建站软件的研发和运营经验，长期致力于解决大中型网站资讯发布管理需求。</p>\n<p>CmsTop 是公司专为大中型网站资讯管理需求而设计的一款内容管理系统软件。CmsTop 专注于资讯领域应用，适合于网络媒体、传统媒体、政府和大 中型企业等，提供全面的内容展现形式，自由的页面维护能力，专业的运营分析报告，高效的操作流程，是多人协作追求高质量内容网站的首选建站利器。</p>\n<p>以服务客户为宗旨，以推动互联网信息产业的发展为己任，专注于资讯领域内容管理系统软件研发，是我们始终不变的追求。</p>', NULL, NULL, NULL, '', 'html', 0, 0, 300, 0, NULL, 1426061204, 1426060904, 0, 0, 1348896381, 1, 1343098963, 1, '', 1, 0, NULL, NULL, 10),
(85, 17, 'auto', '联系我们', '<img src="{IMG_URL}templates/{TEMPLATE}/img/thumb/w720-h200.png" alt="" width="720" height="200" />\n<p>北京思拓合众科技有限公司（CmsTop Inc.）是国内领先的内容管理系统软件与服务提供商。公司创始人钟胜辉于2009年8月创办， 位于北京中关村上地高科技园区，是一家拥有自主知识产权的高科技软件企业。公司拥有产品策划、技术研发、市场推广、网站优化等各方面的优秀人才，团队成员 具有多年大型建站软件的研发和运营经验，长期致力于解决大中型网站资讯发布管理需求。</p>\n<p>CmsTop 是公司专为大中型网站资讯管理需求而设计的一款内容管理系统软件。CmsTop 专注于资讯领域应用，适合于网络媒体、传统媒体、政府和大 中型企业等，提供全面的内容展现形式，自由的页面维护能力，专业的运营分析报告，高效的操作流程，是多人协作追求高质量内容网站的首选建站利器。</p>\n<p>以服务客户为宗旨，以推动互联网信息产业的发展为己任，专注于资讯领域内容管理系统软件研发，是我们始终不变的追求。</p>', '<p>免责声明</p>\n<p>\n北京思拓合众科技有限公司<br/>\n公司地址：北京市海淀区上地三街9号嘉华大厦E座407室<br/>\n联系电话：010-62961030 / 82145002<br/>\n邮政编码：100085<br/>\n电子邮箱：leijing@cmstop.com<br/>\n</p>', NULL, NULL, NULL, '', 'html', NULL, 0, 300, 0, NULL, 1426061212, 1426060912, 0, 0, 1348896383, 1, 1343099291, 1, '', 1, 0, NULL, NULL, 10),
(86, 18, 'auto', '加入我们', '<img src="{IMG_URL}templates/{TEMPLATE}/img/thumb/w720-h200.png" alt="" width="720" height="200" />\n<p>北京思拓合众科技有限公司（CmsTop Inc.）是国内领先的内容管理系统软件与服务提供商。公司创始人钟胜辉于2009年8月创办， 位于北京中关村上地高科技园区，是一家拥有自主知识产权的高科技软件企业。公司拥有产品策划、技术研发、市场推广、网站优化等各方面的优秀人才，团队成员 具有多年大型建站软件的研发和运营经验，长期致力于解决大中型网站资讯发布管理需求。</p>\n<p>CmsTop 是公司专为大中型网站资讯管理需求而设计的一款内容管理系统软件。CmsTop 专注于资讯领域应用，适合于网络媒体、传统媒体、政府和大 中型企业等，提供全面的内容展现形式，自由的页面维护能力，专业的运营分析报告，高效的操作流程，是多人协作追求高质量内容网站的首选建站利器。</p>\n<p>以服务客户为宗旨，以推动互联网信息产业的发展为己任，专注于资讯领域内容管理系统软件研发，是我们始终不变的追求。</p>', '<p>加入我们</p>\n<P>由于业务发展需要，继续扩招，共同打造顶级CMS产品！</P>\n<P>CmsTop（北京）招聘销售员、PHP程序员和UI设计师，有意者请发送简历至<a href="mailto:hr@cmstop.com"> hr#cmstop.com</a>(#->@)</P>', NULL, NULL, NULL, '', 'html', NULL, 0, 300, 0, NULL, 1426061224, 1426060924, 0, 0, 1348896386, 1, 1343099334, 1, '', 1, 0, NULL, NULL, 10),
(87, 19, 'auto', '版权声明', '<img src="{IMG_URL}templates/{TEMPLATE}/img/thumb/w720-h200.png" alt="" width="720" height="200" />\n<p>北京思拓合众科技有限公司（CmsTop Inc.）是国内领先的内容管理系统软件与服务提供商。公司创始人钟胜辉于2009年8月创办， 位于北京中关村上地高科技园区，是一家拥有自主知识产权的高科技软件企业。公司拥有产品策划、技术研发、市场推广、网站优化等各方面的优秀人才，团队成员 具有多年大型建站软件的研发和运营经验，长期致力于解决大中型网站资讯发布管理需求。</p>\n<p>CmsTop 是公司专为大中型网站资讯管理需求而设计的一款内容管理系统软件。CmsTop 专注于资讯领域应用，适合于网络媒体、传统媒体、政府和大 中型企业等，提供全面的内容展现形式，自由的页面维护能力，专业的运营分析报告，高效的操作流程，是多人协作追求高质量内容网站的首选建站利器。</p>\n<p>以服务客户为宗旨，以推动互联网信息产业的发展为己任，专注于资讯领域内容管理系统软件研发，是我们始终不变的追求。</p>', '<p>版权声明</p>\n<p>本公司网站系该网站上所有页面设计、页面内容的著作权人，对该网站所载、凡注明“来源：魅族网”的作品，包括但不限于网站所载的文字、数据、图形、照片、有声文件、动画文件、音视频资料等拥有完整的版权，受著作权法保护。严禁任何媒体、网站、个人或组织以任何形式或出于任何目的在未经本公司书面授权的情况下抄袭、转载、摘编、修改本网站内容，或链接、转贴或以其他方式复制用于商业目的或发行，或稍作修改后在其他网站上使用，前述行为均将构成对本网站版权之侵犯，本网站将依法追究其法律责任。</p>', NULL, NULL, NULL, '', 'html', 0, 0, 300, 0, NULL, 1426061232, 1426060932, 0, 0, 1348896389, 1, 1343099852, 1, '', 1, 0, NULL, NULL, 10),
(88, 1, 'push', '娱乐频道左侧幻灯片', '[]', '[{"contentid":14,"title":"\\u67e5\\u97e6\\u65af\\u8d62\\u5f97\\u59d4\\u5185\\u745e\\u62c9\\u603b\\u7edf\\u5927\\u9009 \\u8fde\\u7eed\\u6267\\u653f\\u8fd114\\u5e74","url":"http:\\/\\/www.silkroad.news.cn\\/2012\\/1010\\/14.shtml","color":"","subtitle":"\\u67e5\\u97e6\\u65af\\u8d62\\u5f97","suburl":"","description":"\\u67e5\\u97e6\\u65af\\u8d62\\u5f97\\u59d4\\u5185\\u745e\\u62c9\\u603b\\u7edf\\u5927\\u9009 \\u8fde\\u7eed\\u6267\\u653f\\u8fd114\\u5e74","thumb":"2012\\/1008\\/1349698537197.jpg","time":"1349850101"},{"contentid":18,"title":"\\u97e9\\u56fd\\u5bfc\\u5f39\\u5c04\\u7a0b\\u53ef\\u8fbe\\u4e2d\\u65e5\\u4fc4\\u9886\\u571f \\u53ef\\u80fd\\u52a0\\u5267\\u519b\\u5907\\u7ade\\u8d5b\\u3010\\u5206\\u9875\\u3011","url":"http:\\/\\/www.silkroad.news.cn\\/2012\\/1010\\/18.shtml","color":"","subtitle":"\\u97e9\\u56fd\\u5bfc\\u5f39\\u5c04","suburl":"","description":"\\u97e9\\u56fd\\u5bfc\\u5f39\\u5c04\\u7a0b\\u53ef\\u8fbe\\u4e2d\\u65e5\\u4fc4\\u9886\\u571f \\u53ef\\u80fd\\u52a0\\u5267\\u519b\\u5907\\u7ade\\u8d5b\\u3010\\u5206\\u9875\\u3011","thumb":"2012\\/1008\\/1349698491750.jpg","time":"1349850060"},{"contentid":83,"title":"\\u5f20\\u53ec\\u5fe0\\uff1a\\u4e2d\\u56fd30\\u591a\\u5e74\\u5fcd\\u8fb1\\u8d1f\\u91cd\\u88ab\\u8d8a\\u5357\\u8bef\\u89e3\\u4e3a\\u5bb3\\u6015","url":"http:\\/\\/www.silkroad.news.cn\\/2012\\/1009\\/83.shtml","color":"","subtitle":"\\u5f20\\u53ec\\u5fe0\\uff1a\\u4e2d","suburl":"","description":"\\u8fd1\\u671f\\u5357\\u6d77\\u4e89\\u7aef\\u7684\\u7d27\\u5f20\\u5c40\\u52bf\\u4e0d\\u65ad\\u5347\\u6e29\\uff0c\\u8d8a\\u5357\\u548c\\u7f8e\\u83f2\\u63a5\\u8fde\\u5728\\u5357\\u6d77\\u8fdb\\u884c\\u6f14\\u4e60\\uff0c\\u90a3\\u4e48\\u5357\\u6d77\\u4e89\\u7aef\\u7684\\u5347\\u7ea7\\u662f\\u5426\\u4f1a\\u5bfc\\u81f4\\u519b\\u4e8b\\u51b2\\u7a81\\u5462\\uff1f\\u4eca\\u5929\\u6211\\u4eec\\u5c31\\u6765\\u8bf7\\u6559\\u4e00\\u4e0b\\u519b\\u4e8b\\u4e13\\u5bb6\\u5f20\\u53ec\\u5fe0\\u6559\\u6388","thumb":"2012\\/1009\\/1349775045950.jpg","time":"1349775101"},{"contentid":33,"title":"\\u7ae0\\u5b50\\u6021\\u5426\\u8ba4\\u4e0e\\u6492\\u8d1d\\u5b81\\u5a5a\\u8baf \\u8c03\\u4f83\\uff1a\\u8bf7\\u67ec\\u53d1\\u6211\\u4e00\\u5f20","url":"http:\\/\\/www.silkroad.news.cn\\/2012\\/1008\\/33.shtml","color":"","subtitle":"\\u7ae0\\u5b50\\u6021\\u5426\\u8ba4","suburl":"","description":"\\u7ae0\\u5b50\\u6021\\u5426\\u8ba4\\u4e0e\\u6492\\u8d1d\\u5b81\\u5a5a\\u8baf \\u8c03\\u4f83\\uff1a\\u8bf7\\u67ec\\u53d1\\u6211\\u4e00\\u5f20","thumb":"2012\\/1008\\/1349697641237.jpg","time":"1349690940"},{"contentid":10,"title":"\\u897f\\u73ed\\u725957\\u57ce\\u6297\\u8bae\\u7d27\\u7f29\\u63aa\\u65bd 11\\u6708\\u6216\\u7ec4\\u7ec7\\u5168\\u9762\\u7f62\\u5de5","url":"http:\\/\\/www.silkroad.news.cn\\/2012\\/1008\\/10.shtml","color":"","subtitle":"\\u897f\\u73ed\\u725957\\u57ce","suburl":"","description":"\\u897f\\u73ed\\u725957\\u57ce\\u6297\\u8bae\\u7d27\\u7f29\\u63aa\\u65bd 11\\u6708\\u6216\\u7ec4\\u7ec7\\u5168\\u9762\\u7f62\\u5de5","thumb":"2012\\/1008\\/1349698425656.jpg","time":"1349681580"}]', NULL, NULL, NULL, '            <!-- 点击切换图片 -->\n           <div class="slider-box">\n              <ul class="slide-tab" id="slidePointTab">\n             <!--{loop $data $k $r}-->\n                 {if !empty($r)}<li><a rel="tab" href="{$r[url]}" title="{$r[title]}"></a></li>{/if}\n               <!--{/loop}-->\n                </ul>\n             <div class="slide-cont" id="slidePointCont">\n              <!--{loop $data $k $r}-->{if !empty($r)}\n                  <div title="tab">\n                     <div class="shadow"></div>\n                        <div class="title"><a href="{$r[url]}" title="{$r[title]}">{str_natcut($r[title],19,'''')}</a></div>\n                      <a href="{$r[url]}" title=""><img src="{thumb($r[thumb],280,360,1,null,1)}" alt="{$r[title]}"></a>\n                    </div>{/if}\n               <!--{/loop}-->\n                </div>\n            </div><!-- @end 点击切换图片 -->', 'html', NULL, 5, 0, 0, NULL, 1426917237, 1426917236, 0, 0, 1426060456, 1, 1343101742, 1, '', 1, 0, 'section/list.html', 0, 10);
INSERT INTO `cmstop_section` (`sectionid`, `pageid`, `type`, `name`, `origdata`, `data`, `url`, `method`, `args`, `template`, `output`, `width`, `rows`, `frequency`, `check`, `fields`, `nextupdate`, `published`, `locked`, `lockedby`, `updated`, `updatedby`, `created`, `createdby`, `description`, `status`, `list_enabled`, `list_template`, `list_pagesize`, `list_pages`) VALUES
(89, 8, 'push', '幻灯片', '[]', '[{"contentid":86,"icon":"blank","iconsrc":"","title":"\\u5f20\\u53ec\\u5fe0\\uff1a\\u7f8e\\u56fd\\u901a\\u8fc7\\u4f0a\\u62c9\\u514b\\u6218\\u4e89\\u5f97\\u5230\\u7684\\u6bd4\\u5931\\u53bb\\u7684\\u591a","color":"","url":"http:\\/\\/www.silkroad.news.cn\\/2012\\/1009\\/86.shtml","subtitle":"\\u5f20\\u53ec\\u5fe0\\uff1a\\u7f8e","suburl":"","thumb":"2012\\/1009\\/1349775628390.jpg","description":"12\\u670818\\u65e5\\u9a7b\\u4f0a\\u7f8e\\u519b\\u6700 \\u540e\\u4e00\\u6279\\u7f8e\\u519b\\u64a4\\u79bb\\u4f0a\\u62c9\\u514b\\uff0c\\u8fd9\\u8bc1\\u660e\\u5386\\u65f69\\u5e74\\u7684\\u4f0a\\u62c9\\u514b\\u6218\\u4e89\\u6b63\\u5f0f\\u5ba3\\u544a\\u7ed3\\u675f\\u4e86\\uff0c\\u7f8e\\u519b\\u8d70\\u540e\\u7a76\\u7adf\\u4f1a\\u7559\\u4e0b\\u4e00\\u4e2a\\u600e\\u4e48\\u6837\\u7684\\u4f0a\\u62c9\\u514b\\u5462\\uff1f\\u7f8e\\u519b\\u4e0b\\u4e00\\u4e2a\\u76ee\\u6807\\u53c8\\u6307\\u5411\\u8c01\\uff1f\\u4eca\\u5929\\u5c31\\u7f8e\\u519b\\u64a4\\u51fa\\u4f0a \\u62c9\\u514b\\u7684\\u8bdd\\u9898\\u91c7\\u8bbf\\u4e00\\u4e0b\\u8457\\u540d\\u7684\\u519b\\u4e8b\\u4e13\\u5bb6\\u5f20\\u53ec\\u5fe0\\u6559\\u6388","time":1349775600,"playtime":"3:10"},{"contentid":69,"icon":"blank","iconsrc":"","title":"\\u7f8e\\u4e3d\\u7684\\u5927\\u811a \\u5e9e\\u5927\\u7684\\u5e1d\\u4f01\\u9e45\\u738b\\u56fd \\u5feb\\u4e50\\u7684\\u4f01\\u9e45","color":"","url":"http:\\/\\/www.silkroad.news.cn\\/2012\\/1009\\/69.shtml","subtitle":"\\u7f8e\\u4e3d\\u7684\\u5927\\u811a","suburl":"","thumb":"http:\\/\\/media.cmstop.com\\/pic\\/20120516\\/50e4760d38e7866e57f947f618f1920e-4.jpg","description":"\\u5728\\u51b0\\u5929\\u96ea\\u5730\\u7684\\u5357\\u6781\\u6df1\\u5904\\uff0c\\u6709\\u4e00\\u4e2a\\u5e9e\\u5927\\u7684\\u5e1d\\u4f01\\u9e45\\u738b\\u56fd\\uff0c\\u5728\\u90a3\\u91cc\\uff0c\\u6bcf\\u4e00\\u53ea\\u5e1d\\u4f01\\u9e45\\u90fd\\u5fc5\\u987b\\u9760\\u7f8e\\u5999\\u7684\\u6b4c\\u58f0\\u627e\\u5230\\u81ea\\u5df1\\u7684\\u5fc3\\u7075\\u4f34\\u4fa3\\u3002\\u5c0f\\u4f01\\u9e45\\u66fc\\u6ce2\\uff08\\u4f0a\\u5229\\u4e9a\\u00b7\\u4f0d\\u5fb7\\uff09\\u5374\\u4ece\\u751f\\u4e0b\\u6765\\u5c31\\u201c\\u4e0e\\u4f17\\u4e0d\\u540c\\u201d\\uff0c\\u4ed6\\u5929\\u751f\\u5c31\\u4e0d\\u662f\\u5531\\u6b4c\\u7684\\u6599\\u5b50\\uff0c\\u5374\\u9177\\u7231\\u8df3\\u8e22\\u8e0f\\u821e\\u3002\\u867d\\u7136\\u66fc\\u6ce2\\u7684\\u5988\\u5988\\u8bfa\\u739b\\u00b7\\u73cd\\uff08\\u59ae\\u53ef\\u00b7\\u57fa\\u5fb7\\u66fc\\uff09\\u8fd8\\u65f6\\u800c\\u89c9\\u5f97\\u4ed6\\u7684\\u7231\\u597d\\u8fd8\\u7b97\\u53ef\\u7231\\uff0c\\u53ef\\u7238\\u7238\\u66fc\\u83f2\\u65af\\uff08\\u4f11\\u00b7\\u6770\\u514b\\u66fc\\uff09\\u5374\\u8ba4\\u4e3a\\u8fd9\\u7b80\\u76f4\\u4e0d\\u662f\\u5e1d\\u4f01\\u9e45\\u6240\\u4e3a\\uff0c\\u738b\\u56fd\\u7684\\u4e25\\u5389\\u9996\\u9886\\u8bfa\\u4e9a\\u66f4\\u662f\\u770b\\u8fd9\\u4e2a\\u5e1d\\u4f01\\u9e45\\u91cc\\u7684\\u53e6\\u7c7b\\u4e0d\\u987a\\u773c\\u3002 ","time":1349769300,"playtime":"2:10"},{"contentid":10,"icon":"blank","iconsrc":"","title":"\\u897f\\u73ed\\u725957\\u57ce\\u6297\\u8bae\\u7d27\\u7f29\\u63aa\\u65bd 11\\u6708\\u6216\\u7ec4\\u7ec7\\u5168\\u9762\\u7f62\\u5de5","color":"","url":"http:\\/\\/www.silkroad.news.cn\\/2012\\/1008\\/10.shtml","subtitle":"\\u897f\\u73ed\\u725957\\u57ce","suburl":"","thumb":"2012\\/1008\\/1349698425656.jpg","description":"\\u897f\\u73ed\\u725957\\u57ce\\u6297\\u8bae\\u7d27\\u7f29\\u63aa\\u65bd 11\\u6708\\u6216\\u7ec4\\u7ec7\\u5168\\u9762\\u7f62\\u5de5","time":1349681580,"playtime":"2:01"}]', NULL, NULL, NULL, '<!--{loop $data $k $c}-->\n<!--{if $k == 0}-->\n<section class="bigimage pos-r fl-l">\n    <img src="{thumb($c[thumb], 660, 440)}" width="660" height="440" alt="{$c[title]}">\n    <div class="titlepanel pos-a"><time class="pos-a">{$c[playtime]}</time><h1 class="title"><a href="{$c[url]}" target="_blank" title="{$c[title]}">{$c[title]}</a></h1></div>\n    <div class="_overlay"></div>\n</section>\n<!--{else}-->\n<section class="second pos-r fl-r m-imagetitle js-overlay">\n    <a href="{$c[url]}" target="_blank" class="thumb-link"><img src="{thumb($c[thumb], 340, 220)}" width="340" height="220" alt=""></a>\n    <div class="titlepanel pos-a"><time class="pos-a">{$c[playtime]}</time><h1 class="title"><a href="{$c[url]}" target="_blank" title="{$c[title]}">{$c[title]}</a></h1></div>\n    <a href="{$c[url]}" target="_blank" class="overlay"><b class="overlay-play icon40x40"></b></a>\n</section>\n<!--{/if}-->\n<!--{/loop}-->', 'html', NULL, 3, 0, 0, '{"system_fields":{"contentid":{"checked":"1","func":"intval"},"title":{"checked":"1","required":"1","min_length":"","max_length":""},"url":{"checked":"1"},"color":{"checked":"1"},"icon":{"checked":"1"},"iconsrc":{"checked":"1"},"subtitle":{"checked":"1","min_length":"","max_length":""},"suburl":{"checked":"1"},"thumb":{"checked":"1","width":"","height":""},"description":{"checked":"1","min_length":"","max_length":""},"time":{"checked":"1","func":"section_fields_strtotime"}},"custom_fields":{"text":["\\u65f6\\u957f"],"name":["playtime"]}}', 1426843260, 1426843260, 0, 0, 1426504380, 1, 1343110859, 1, '共 4 行，标题 26汉字以内，大图规格 660 x 440 , 小图规格  340x220', 1, 0, 'section/list.html', 0, 10),
(90, 8, 'hand', '精彩视频', null, '[[{\"title\":\"\\u5f20\\u53ec\\u5fe0\\uff1a\\u6b7c20\\u7784\\u51c6F-22\\u800c\\u8bbe\\u8ba1 \\u9690\\u8eab\\u5236\\u7a7a\\u5f88\\u5389\\u5bb3\",\"url\":\"http:\\/\\/www.silkroad.news.cn\\/2012\\/1009\\/84.shtml\",\"color\":\"\",\"subtitle\":\"\\u5f20\\u53ec\\u5fe0\\uff1a\\u6b7c\",\"suburl\":\"\",\"description\":\"\\u65e5\\u672c\\u51b3\\u5b9a\\u8d2d\\u4e70\\u5dee\\u4e0d\\u591a40 \\u67b6\\u5de6\\u53f3\\u7684\\u7f8e\\u56fd\\u6700\\u5148\\u8fdb\\u7684F\\u201435\\u8054\\u5408\\u653b\\u51fb\\u673a\\uff0c\\u4f5c\\u4e3a\\u4e3b\\u529b\\u6218\\u673a\\uff0c\\u90a3\\u4e48F\\u201435\\u7a76\\u7adf\\u662f\\u54ea\\u4e9b\\u7279\\u70b9\\uff1f\\u65e5\\u672c\\u8ba2\\u8d2dF\\u201435\\u662f\\u5426\\u5bf9\\u7740\\u6b7c20\\u6765\\u7740\\u5462\\uff1f\\u4eca\\u5929\\u6211\\u4eec\\u8bf7\\u6765\\u5f20\\u53ec\\u5fe0\\u6559\\u6388\\u4e3a \\u5927\\u5bb6\\u89e3\\u8bfb\\u4e00\\u4e0b\",\"thumb\":\"2012\\/1009\\/1349775448649.jpg\",\"time\":\"1349775420\"}],[{\"title\":\"CmsTop\\u5ba3\\u4f20\\u7247\",\"url\":\"http:\\/\\/www.silkroad.news.cn\\/2012\\/1009\\/68.shtml\",\"color\":\"\",\"subtitle\":\"CmsTop\\u5ba3\\u4f20\",\"suburl\":\"\",\"description\":\"CmsTop\\u5ba3\\u4f20\\u7247\",\"thumb\":\"2012\\/1009\\/1349758019346.jpg\",\"time\":\"1349758037\"}],[{\"title\":\"\\u4ed6\\u3001\\u5979 \\u4ece\\u56fe\\u4e66\\u9986\\u5f00\\u59cb\\u7684\\u7231\\u60c5\",\"url\":\"http:\\/\\/www.silkroad.news.cn\\/2012\\/1009\\/62.shtml\",\"color\":\"\",\"subtitle\":\"\\u4ed6\\u3001\\u5979 \\u4ece\",\"suburl\":\"\",\"description\":\"\\u4ed6\\u3001\\u5979\\uff0c\\u56fe\\u4e66\\u9986\\u6ce1\\u599e\\u7684\\u597d\\u5730\\u65b9\",\"thumb\":\"2012\\/1009\\/1349756193505.jpg\",\"time\":\"1349756160\"}],[{\"title\":\"\\u6cb3\\u5317\\u4fdd\\u5b9a\\u5c45\\u6c11\\u697c\\u7206\\u70b8\\u6848\\u5acc\\u7591\\u4eba\\u5728\\u5b89\\u5fbd\\u4e34\\u6cc9\\u53bf\\u6295\\u6848\",\"url\":\"http:\\/\\/www.silkroad.news.cn\\/2012\\/1008\\/2.shtml\",\"color\":\"\",\"subtitle\":\"\\u6cb3\\u5317\\u4fdd\\u5b9a\\u5c45\",\"suburl\":\"\",\"description\":\"\\u6cb3\\u5317\\u4fdd\\u5b9a\\u5c45\\u6c11\\u697c\\u7206\\u70b8\\u6848\\u5acc\\u7591\\u4eba\\u5728\\u5b89\\u5fbd\\u4e34\\u6cc9\\u53bf\\u6295\\u6848\",\"thumb\":\"2012\\/1008\\/1349698336780.jpg\",\"time\":\"1349683260\"}]]', null, null, null, '<!--{loop $data $k $r}-->\n <!--{loop $r $i $c}-->\n<?php $time=$c[time];?>\n    <!--{if ($k+1)%4 !=0}-->\n     <li class=\"item js-overlay\"><a href=\"{$c[url]}\" target=\"_blank\" class=\"thumb-link\"><span class=\"time\">{date(\'i:s\',$time)}</span><img src=\"{thumb($c[thumb],240,180)}\" width=\"240\" height=\"180\" alt=\"{$r[title]}\"></a><a class=\"title\" href=\"{$c[url]}\" target=\"_blank\">{$c[title]}</a><a href=\"{$c[url]}\" target=\"_blank\" class=\"overlay\"><b class=\"overlay-play icon40x40\"></b></a></li>\n<!--{else}-->\n <li class=\"item js-overlay right\"><a href=\"{$c[url]}\" target=\"_blank\" class=\"thumb-link\"><span class=\"time\">{date(\'i:s\',$time)}</span><img src=\"{thumb($c[thumb],240,180)}\" width=\"240\" height=\"180\" alt=\"{$r[title]}\"></a><a class=\"title\" href=\"{$c[url]}\" target=\"_blank\">{$c[title]}</a><a href=\"{$c[url]}\" target=\"_blank\" class=\"overlay\"><b class=\"overlay-play icon40x40\"></b></a></li>\n<!--{/if}-->\n    <!--{/loop}-->\n\n<!--{/loop}-->\n', 'html', null, '4', '300', '0', '{\"system_fields\":{\"contentid\":{\"checked\":\"1\",\"func\":\"intval\"},\"title\":{\"checked\":\"1\",\"required\":\"1\",\"min_length\":\"\",\"max_length\":\"\"},\"url\":{\"checked\":\"1\"},\"color\":{\"checked\":\"1\"},\"icon\":{\"checked\":\"1\"},\"iconsrc\":{\"checked\":\"1\"},\"subtitle\":{\"checked\":\"1\",\"min_length\":\"\",\"max_length\":\"\"},\"suburl\":{\"checked\":\"1\"},\"thumb\":{\"checked\":\"1\",\"width\":\"\",\"height\":\"\"},\"description\":{\"checked\":\"1\",\"min_length\":\"\",\"max_length\":\"\"},\"time\":{\"checked\":\"1\",\"func\":\"section_fields_strtotime\"}},\"custom_fields\":{\"text\":[],\"name\":[]}}', 1426843560, 1426843260, 0, 0, 1426504533, 1, 1343110872, 1, '16条有缩略图的组图，标题长度 20 个汉字', 1, 0, null, null, 10),
(93, 20, 'hand', '精彩推荐图片', NULL, '[[{"title":"\\u7f8e\\u56fd\\u81ea\\u7136\\u5386\\u53f2\\u535a\\u7269\\u9986\\u5e55\\u540e\\u9c9c\\u4e3a\\u4eba\\u77e5\\u7684\\u5de5\\u4f5c","url":"http:\\/\\/www.silkroad.news.cn\\/2012\\/1009\\/93.shtml","color":"","subtitle":"\\u7f8e\\u56fd\\u81ea\\u7136\\u5386","suburl":"","description":"\\u7f8e\\u56fd\\u81ea\\u7136\\u5386\\u53f2\\u535a\\u7269\\u9986\\u5e55\\u540e\\u9c9c\\u4e3a\\u4eba\\u77e5\\u7684\\u5de5\\u4f5c","thumb":"2012\\/1009\\/1349779282588.jpg","time":"1349779305"},{"contentid":136,"icon":"","iconsrc":"","title":"\\u4e60\\u8fd1\\u5e73\\u70b9\\u9898\\u4e1c\\u5317\\u8f6c\\u578b\\u5347\\u7ea7:\\u4e0d\\u80fd\\u518d\\u5531\\u5355\\u4e00\\"\\u4e8c\\u4eba\\u8f6c\\"","color":"","url":"http:\\/\\/www.silkroad.news.cn\\/2015\\/0311\\/136.shtml","thumb":""}],[{"title":"\\u4fc4\\u4ea5\\u4fc4\\u5dde\\u7acb\\u5927\\u5b66\\u519b\\u4e50\\u56e2\\u9707\\u64bc\\u8868","url":"http:\\/\\/www.silkroad.news.cn\\/2012\\/1009\\/64.shtml","color":"","subtitle":"\\u4fc4\\u4ea5\\u4fc4\\u5dde\\u7acb","suburl":"","description":"\\u4e00\\u5929\\u5185\\u5f15\\u53d1\\u767e\\u4e07\\u7f51\\u53cb\\u56f4\\u89c2\\uff01\\u7f8e\\u56fd\\u4fc4\\u4ea5\\u4fc4\\u5dde\\u7acb\\u5927\\u5b66\\u519b\\u4e50\\u56e2\\u9707\\u64bc\\u8868\\u6f14\\u81f4\\u656c\\u7535\\u5b50\\u6e38\\u620f\\uff01\\u7b2c6\\u5206\\u949f\\u7684\\u8868\\u6f14\\u7b80\\u76f4\\u592a\\u795e\\u4e86\\uff01\\u4ed6\\u4eec\\u592a\\u725bA\\u4e86\\uff01\\u89e6\\u52a8\\u4f60\\u7684\\u7ae5\\u5e74\\u56de\\u5fc6\\u4e86\\u5417\\uff1f\\u8fd9\\u4e2a\\u5fc5\\u987b\\u819c\\u62dc\\uff01\\uff01\\uff01","thumb":"2012\\/1009\\/1349757123242.jpg","time":"1349757120"}],[{"title":"\\u7ae0\\u5b50\\u6021\\u81ea\\u5632\\u201c\\u88ab\\u7ed3\\u5a5a\\u201d \\u7f51\\u53cb\\u591a\\u9001\\u795d\\u798f\\u76fc\\u6210\\u771f","url":"http:\\/\\/www.silkroad.news.cn\\/2012\\/1008\\/30.shtml","color":"","subtitle":"\\u7ae0\\u5b50\\u6021\\u81ea\\u5632","suburl":"","description":"\\u7ae0\\u5b50\\u6021\\u81ea\\u5632\\u201c\\u88ab\\u7ed3\\u5a5a\\u201d \\u7f51\\u53cb\\u591a\\u9001\\u795d\\u798f\\u76fc\\u6210\\u771f","thumb":"2012\\/1008\\/1349697594823.jpg","time":"1349692920"}],[{"title":"\\u5229\\u6bd4\\u4e9a\\u653f\\u5e9c\\u519b\\u88c5\\u7532\\u90e8\\u961f\\u906d\\u6bc1\\u706d\\u6027\\u6253\\u51fb","url":"http:\\/\\/www.silkroad.news.cn\\/2012\\/0926\\/78.shtml","color":"","subtitle":"\\u5229\\u6bd4\\u4e9a\\u653f\\u5e9c","suburl":"","description":"\\u5230\\u76ee\\u524d\\u4e3a\\u6b62\\uff0c\\u897f\\u65b9\\u5df2\\u7ecf\\u8fdb\\u884c\\u4e86\\u56db\\u8f6e\\u7a7a\\u88ad\\uff0c\\u8fd9\\u56db\\u8f6e\\u7a7a\\u88ad\\u8ddf\\u7981\\u98de\\u6ca1\\u6709\\u592a\\u5927\\u7684\\u5173\\u7cfb\\uff0c\\u4e3b\\u8981\\u76ee\\u7684\\u8fd8\\u662f\\u5bfb\\u627e\\u5361\\u624e\\u83f2\\u7684\\u4f4f\\u6240\\uff0c\\u5bf9\\u4ed6\\u7684\\u4f4f\\u6240\\u8fdb\\u884c\\u6253\\u51fb\\uff0c\\u4ed6\\u7684\\u4f4f\\u6240\\u548c\\u7981\\u98de\\u6ca1\\u6709\\u4ec0\\u4e48\\u5173\\u7cfb\\u3002\\u7a7a\\u88ad\\u6467\\u6bc1\\u4e86\\u5927\\u91cf\\u7684\\u5229\\u6bd4\\u4e9a\\u653f\\u5e9c\\u519b\\u7684\\u5766\\u514b\\u8f66\\u3001\\u88c5\\u7532\\u8f66\\u548c\\u8f66\\u8f86\\uff0c\\u8fd9\\u4e9b\\u5730\\u9762\\u8f66\\u8f86\\u4e0d\\u662f\\u9632\\u7a7a\\u70ae\\u706b\\uff0c\\u8ddf\\u7981\\u98de\\u6ca1\\u6709\\u5173\\u7cfb\\uff0c\\u53e6\\u5916\\u6467\\u6bc1\\u4e86\\u6d77\\u519b\\u7684\\u57fa\\u5730\\uff0c\\u5f39\\u836f\\u5e93\\u3001\\u673a\\u573a\\u3002","thumb":"2012\\/0926\\/1348623374717.jpg","time":"1348623472"}],[{"title":"\\u5f20\\u53ec\\u5fe0\\uff1a\\u62c9\\u767b\\u6b7b\\u540e\\u7684\\u4ef7\\u503c\\u6bd4\\u4ed6\\u6d3b\\u7740\\u65f6\\u8fd8\\u5927","url":"http:\\/\\/www.silkroad.news.cn\\/2012\\/0926\\/76.shtml","color":"","subtitle":"\\u5f20\\u53ec\\u5fe0\\uff1a\\u62c9","suburl":"","description":"5\\u67081\\u65e5\\u7f8e\\u56fd\\u6d77\\u8c79\\u7a81\\u51fb\\u961f\\u51fb\\u6bd9\\u4e86\\u672c.\\u62c9\\u767b\\uff0c\\u4f46\\u662f\\u62c9\\u767b\\u4e4b\\u6b7b\\u8ff7\\u96fe\\u91cd\\u91cd\\uff0c\\u62c9\\u767b\\u771f\\u7684\\u6b7b\\u4e86\\u5417\\uff1f\\u6050\\u6016\\u88ad\\u51fb\\u5c31\\u6b64\\u505c\\u6b62\\u4e86\\u5417\\uff1f\\u6211\\u4eec\\u8be5\\u5982\\u4f55\\u9762\\u5bf9\\u540e\\u62c9\\u767b\\u65f6\\u4ee3\\uff0c\\u6211\\u4eec\\u4eca\\u5929\\u5c31\\u8bf7\\u6559\\u4e00\\u4e0b\\u519b\\u4e8b\\u4e13\\u5bb6\\u5f20\\u53ec\\u5fe0\\u6559\\u6388\\u3002","thumb":"2012\\/0926\\/1348622427562.jpg","time":"1348622460"}],[]]', NULL, NULL, NULL, '<ul>\n<!--{loop $data $k $r}-->\n<!--{loop $r $i $c}-->\n    <li>\n        <a href="{$c[url]}">\n            <div>\n                <img alt="" src="{thumb($c[thumb], 140, 115, 1, null, 1, 95)}" width="140" height="115" />\n                <h3>{str_natcut($c[title], 8, '''')}</h3>\n            </div>\n        </a>\n    </li>\n<!--{/loop}-->\n<!--{/loop}-->\n</ul>', 'html,json', NULL, 6, 0, 0, '{"system_fields":{"contentid":{"checked":"1","func":"intval"},"title":{"checked":"1","required":"1","min_length":"","max_length":""},"url":{"checked":"1"},"color":{"checked":"1"},"icon":{"checked":"1"},"iconsrc":{"checked":"1"},"subtitle":{"min_length":"","max_length":""},"thumb":{"checked":"1","width":"","height":""},"description":{"min_length":"","max_length":""},"time":{"func":"section_fields_strtotime"}},"custom_fields":{"text":[],"name":[]}}', 1426842750, 1426842750, 1379317126, 112, 1426476552, 1, 1329968921, 112, '', 1, 0, 'section/list.html', 0, 10),
(94, 20, 'hand', '精彩推荐文字', NULL, '[[{"title":"\\u6cb3\\u5317\\u4fdd\\u5b9a\\u5c45\\u6c11\\u697c\\u7206\\u70b8\\u8b66\\u65b9\\u521d\\u6b65\\u8ba4\\u5b9a\\u4e3a\\u5211\\u4e8b\\u6848\\u4ef6","url":"http:\\/\\/www.silkroad.news.cn\\/2012\\/1010\\/6.shtml","color":"","subtitle":"\\u6cb3\\u5317\\u4fdd\\u5b9a\\u5c45","suburl":"","description":"\\u6cb3\\u5317\\u4fdd\\u5b9a\\u5c45\\u6c11\\u697c\\u7206\\u70b8\\u8b66\\u65b9\\u521d\\u6b65\\u8ba4\\u5b9a\\u4e3a\\u5211\\u4e8b\\u6848\\u4ef6","thumb":"2012\\/1008\\/1349698565235.jpg","time":"1349850060"}],[{"title":" \\u5168\\u56fd141\\u4e07\\u4eba\\u53c2\\u52a0\\"\\u56fd\\u8003\\" \\u4e891.6\\u4e07\\u5c97\\u4f4d","url":"http:\\/\\/www.silkroad.news.cn\\/2012\\/1009\\/90.shtml","color":"","subtitle":" \\u5168\\u56fd141\\u4e07","suburl":"","description":"\\u56fd\\u5bb6\\u516c\\u52a1\\u5458\\u62db\\u5f55\\u516c\\u5171\\u79d1\\u76ee\\u7b14\\u8bd5\\u5f00\\u8003\\uff0c\\u4eca\\u5e74\\u8003\\u751f\\u8d85\\u8fc7140\\u4e07\\uff0c\\u5c06\\u7ade\\u4e89137\\u4e2a\\u62db\\u8003\\u5355\\u4f4d\\u76841.6\\u4e07\\u4f59\\u5c97\\u4f4d\\uff0c\\u5e73\\u5747\\u7ade\\u4e89\\u6bd4\\u8fbe\\u523088\\u6bd41\\u3002\\u636e\\u7edf\\u8ba1\\uff0c\\u4eca\\u5e74\\u662f\\u7b14\\u8bd5\\u8d44\\u683c\\u5ba1\\u6838\\u8003\\u751f\\u4eba\\u6570\\u8fde\\u7eed\\u7b2c3\\u5e74\\u7a81\\u7834\\u767e\\u4e07\\u4eba\\uff0c\\u800c2003\\u5e74\\u8fd9\\u4e00\\u6570\\u5b57\\u4ec5\\u4e3a8.7\\u4e07\\u3002","thumb":"2012\\/1009\\/1349778567239.jpg","time":"1349778540"}],[{"title":"\\u5f20\\u53ec\\u5fe0\\uff1a\\u6211\\u519b\\u8230\\u8247\\u7684\\u5c16\\u7aef\\u6280\\u672f\\u843d\\u540e\\u7f8e\\u56fd10-20\\u5e74","url":"http:\\/\\/www.silkroad.news.cn\\/2012\\/1009\\/87.shtml","color":"","subtitle":"\\u5f20\\u53ec\\u5fe0\\uff1a\\u6211","suburl":"","description":"\\u4eca\\u5e74\\u768412\\u670826\\u65e5\\u662f \\u4e2d\\u56fd\\u4eba\\u6c11\\u6d77\\u519b\\u8d74\\u4e9a\\u4e01\\u6e7e\\u62a4\\u822a\\u6574\\u6574\\u4e09\\u5468\\u5e74\\u7eaa\\u5ff5\\u65e5\\uff0c\\u4e2d\\u56fd\\u6d77\\u519b\\u53ef\\u4ee5\\u8bf4\\u5411\\u4e16\\u754c\\u5c55\\u793a\\u4e86\\u6587\\u660e\\u4e4b\\u5e08\\uff0c\\u5a01\\u6b66\\u4e4b\\u5e08\\u7684\\u5149\\u8f89\\u7684\\u5f62\\u8c61\\u3002\\u56de\\u987e\\u8fd9\\u4e09\\u5e74\\u7684\\u62a4\\u822a\\u884c\\u52a8\\uff0c\\u4e2d\\u56fd\\u6d77\\u519b\\u7ecf\\u5386\\u4e86\\u54ea\\u4e9b\\u6ce2 \\u6298\\uff0c\\u6211\\u4eec\\u83b7\\u5f97\\u4e86\\u54ea\\u4e9b\\u7ecf\\u9a8c\\uff0c\\u53c8\\u68c0\\u9a8c\\u4e86\\u54ea\\u4e9b\\u4e2d\\u56fd\\u6d77\\u519b\\u7684\\u6b66\\u5668\\u88c5\\u5907\\u5462\\uff1f\\u4eca\\u5929\\u6211\\u4eec\\u5c31\\u8bf7\\u519b\\u4e8b\\u4e13\\u5bb6\\u5f20\\u53ec\\u5fe0\\u6559\\u6388\\u8ddf\\u60a8\\u4e00\\u8d77\\u6765\\u56de\\u987e\\u548c\\u89e3\\u8bfb\\u4e00\\u4e0b\\u6d77\\u519b\\u62a4\\u822a\\u4e09\\u5468\\u5e74\\u3002","thumb":"2012\\/1009\\/1349775718609.jpg","time":"1349775660"}],[{"title":"\\u4ed6\\u3001\\u5979 \\u4ece\\u56fe\\u4e66\\u9986\\u5f00\\u59cb\\u7684\\u7231\\u60c5","url":"http:\\/\\/www.silkroad.news.cn\\/2012\\/1009\\/62.shtml","color":"","subtitle":"\\u4ed6\\u3001\\u5979 \\u4ece","suburl":"","description":"\\u4ed6\\u3001\\u5979\\uff0c\\u56fe\\u4e66\\u9986\\u6ce1\\u599e\\u7684\\u597d\\u5730\\u65b9","thumb":"2012\\/1009\\/1349756193505.jpg","time":"1349756160"}],[{"title":"\\u5f20\\u7ff0\\u5e86\\u751f\\u90d1\\u723d\\u7d20\\u989c\\u73b0\\u8eab \\u6df1\\u60c5\\u6ce8\\u89c6\\u7537\\u65b9(\\u56fe)","url":"http:\\/\\/www.silkroad.news.cn\\/2012\\/1008\\/31.shtml","color":"","subtitle":"\\u5f20\\u7ff0\\u5e86\\u751f\\u90d1","suburl":"","description":"\\u5f20\\u7ff0\\u5e86\\u751f\\u90d1\\u723d\\u7d20\\u989c\\u73b0\\u8eab \\u6df1\\u60c5\\u6ce8\\u89c6\\u7537\\u65b9(\\u56fe)","thumb":"2012\\/1008\\/1349697611460.jpg","time":"1349691960"}],[{"title":"\\u7ae0\\u5b50\\u6021\\u5426\\u8ba4\\u4e0e\\u6492\\u8d1d\\u5b81\\u5a5a\\u8baf \\u8c03\\u4f83\\uff1a\\u8bf7\\u67ec\\u53d1\\u6211\\u4e00\\u5f20","url":"http:\\/\\/www.silkroad.news.cn\\/2012\\/1008\\/33.shtml","color":"","subtitle":"\\u7ae0\\u5b50\\u6021\\u5426\\u8ba4","suburl":"","description":"\\u7ae0\\u5b50\\u6021\\u5426\\u8ba4\\u4e0e\\u6492\\u8d1d\\u5b81\\u5a5a\\u8baf \\u8c03\\u4f83\\uff1a\\u8bf7\\u67ec\\u53d1\\u6211\\u4e00\\u5f20","thumb":"2012\\/1008\\/1349697641237.jpg","time":"1349690940"}]]', NULL, NULL, NULL, '<ul>\n<!--{loop $data $k $r}-->\n    <!--{loop $r $i $c}-->\n    <li>\n    <a href="{$c[url]}">{str_natcut($c[title],17,'''')}</a>\n    </li>\n    <!--{/loop}-->\n<!--{/loop}-->\n</ul>', 'html,json', NULL, 6, 0, 0, '{"system_fields":{"contentid":{"checked":"1","func":"intval"},"title":{"checked":"1","required":"1","min_length":"","max_length":""},"url":{"checked":"1"},"color":{"checked":"1"},"iconsrc":{"checked":"1"},"subtitle":{"min_length":"","max_length":""},"thumb":{"width":"","height":""},"description":{"min_length":"","max_length":""},"time":{"func":"section_fields_strtotime"}},"custom_fields":{"text":[],"name":[]}}', 1426842750, 1426842750, 0, 0, 1426237776, 1, 1329968936, 112, '', 1, 0, 'section/list.html', 0, 10),
(95, 9, 'push', '精彩观点', '[]', '[]', NULL, NULL, NULL, '<!--{loop $data $k $r}-->\n<li class="item">\n    <a class="photo" title="{$r[title]}" href="{$r[url]}" target="_blank"><img width="58" height="58" alt="" src="{thumb($r[thumb], 58, 58)}"></a>\n    <div class="texts">\n        <p class="title"><a class="title" title="{$r[title]}" href="{$r[url]}" target="_blank">{str_natcut($r[title], 17, '''')}</a></p>\n        <p class="summary">{str_natcut($r[description], 65)}</p>\n    </div>\n</li>\n<!--{/loop}-->', 'html', NULL, 4, 0, 0, NULL, 1426060817, 1426060817, 0, 0, 1426060850, 1, 1345514661, 30, '共 4 行，缩略图规格 58 x 58，标题长度 17 个汉字，描述长度 65 个汉字', 0, 0, 'section/list.html', 0, 10),
(96, 2, 'html', '顶部导航', '<!-- 导航 -->\n<div class="nav-wrapper">\n    <div class="border-radius">\n        <div class="main">\n            <nav class="nav">\n                <ul>\n                    <li><a href="{WWW_URL}" title="首页" class="index" target="_blank">首页</a></li>\n                    <li class="hr"></li>\n                    <li><a href="{WWW_URL}cmstop/" title="思拓专区" target="_blank">思拓专区</a></li>\n                    <li class="hr"></li>\n                    <li><a href="{WWW_URL}news/" title="新闻" target="_blank">新闻</a></li>\n                    <li class="hr"></li>\n                    <li><a href="{WWW_URL}ent/" title="娱乐" target="_blank">娱乐</a></li>\n                    <li class="hr"></li>\n                    <li><a href="{WWW_URL}auto/" title="汽车" target="_blank">汽车</a></li>\n                    <li class="hr"></li>\n                    <li><a href="{WWW_URL}house/" title="房产" target="_blank">房产</a></li>\n                    <li class="hr"></li>\n                    <li><a href="{WWW_URL}travel/" title="旅游" target="_blank">旅游</a></li>\n                    <li class="hr"></li>\n                    <li><a href="http://special.silkroad.news.cn/" title="专题" target="_blank">专题</a></li>\n                    <li class="hr"></li>\n                    <li><a href="#" target="_self" title="手机版">手机版</a></li>\n                    <li class="hr"></li>\n                    <li><a href="{SPACE_URL}" title="专栏" target="_blank">专栏</a></li>\n                    <li class="hr"></li>\n                    <li><a href="{WWW_URL}paper/" title="报纸" target="_blank">报纸</a></li>\n                    <li class="hr"></li>\n                    <li><a href="{WWW_URL}magazine/" title="杂志" target="_blank">杂志</a></li>\n                    <li class="hr"></li>\n                    <li><a href="http://talk.silkroad.news.cn/" title="访谈" target="_blank">访谈</a></li>\n                    <li class="hr"></li>\n                    <li><a href="#" target="_self" title="">调查</a></li>\n                    <li class="hr"></li>\n                    <li><a href="{APP_URL}roll.php" title="">滚动</a></li>\n                </ul>\n            </nav>\n        </div>\n        <div class="left"></div>\n        <div class="right"></div>\n    </div>\n</div><!-- @end 导航 -->', '<ul class="head-top-nav">\n<li><a href="http://www.silkroad.news.cn/" title="首页" class="b" target="_self">首页</a></li>\n<li><a href="http://www.silkroad.news.cn/news/" title="新闻" target="_self">新闻</a></li>\n<li><a href="http://photo.silkroad.news.cn/" title="图片" target="_self">图片</a></li>\n<li><a href="http://video.silkroad.news.cn/" title="视频" target="_self">视频</a></li>\n<li><a href="http://talk.silkroad.news.cn/" title="访谈" target="_self">访谈</a></li>\n<li><a href="http://special.silkroad.news.cn/" title="专题" target="_self">专题</a></li>\n<li><a href="http://space.silkroad.news.cn/" title="专栏" target="_self">专栏</a></li>\n<li><a href="http://www.silkroad.news.cn/paper/" title="报纸" target="_self">报纸</a></li>\n<li><a href="http://www.silkroad.news.cn/magazine/" title="杂志" target="_self">杂志</a></li>\n<li><a href="http://app.silkroad.news.cn/roll.php" title="滚动" target="_self">滚动</a></li>\n<li><a href="http://app.silkroad.news.cn/rank.php" title="排行" target="_self">排行</a></li>\n<li><a href="http://app.silkroad.news.cn/tags.php" title="标签" target="_self">标签</a></li>\n<li><a href="http://app.silkroad.news.cn/mood.php" title="心情" target="_self">心情</a></li>\n<li><a href="http://app.silkroad.news.cn/digg.php" title="Digg" target="_self">Digg</a></li>\n</ul>', NULL, NULL, NULL, '', 'html', NULL, 0, 0, 0, NULL, 1426060663, 1426060663, 0, 0, 1426475683, 1, 1347949824, 1, '', 0, 0, NULL, NULL, 10),
(244, 20, 'hand', '网站底部', '    <a href="">触屏版</a> <a href="">PC版</a> <a href="">客户端</a>\n    <p>Copyright © 2014 CmsTop. All Rights Reserved</p>', '<a href="{WWW_URL}">PC版</a>\n<a href="/">触屏版</a>\n<p>Copyright © 2014 CmsTop. All Rights Reserved</p>', NULL, NULL, NULL, '<a href="{WWW_URL}?mobile">PC版</a>\n<a href="/">触屏版</a>\n<p>Copyright © 2015 CmsTop. All Rights Reserved</p>', 'html', NULL, 0, 0, 0, '{"system_fields":{"contentid":{"checked":"1","func":"intval"},"title":{"checked":"1","required":"1","min_length":"","max_length":""},"url":{"checked":"1"},"color":{"checked":"1"},"icon":{"checked":"1"},"iconsrc":{"checked":"1"},"subtitle":{"checked":"1","min_length":"","max_length":""},"suburl":{"checked":"1"},"thumb":{"checked":"1","width":"","height":""},"description":{"checked":"1","min_length":"","max_length":""},"time":{"checked":"1","func":"section_fields_strtotime"}},"custom_fields":{"text":[],"name":[]}}', 1426844216, 1426844216, 0, 0, 1426844210, 1, 1393381693, 1, '', 1, 0, NULL, NULL, 10),
(245, 20, 'hand', '组图推荐', '[[],[],[],[],[],[]]', '[[{"title":" \\u5168\\u56fd141\\u4e07\\u4eba\\u53c2\\u52a0\\"\\u56fd\\u8003\\" \\u4e891.6\\u4e07\\u5c97\\u4f4d","url":"http:\\/\\/www.silkroad.news.cn\\/2012\\/1009\\/90.shtml","color":"","subtitle":" \\u5168\\u56fd141\\u4e07","suburl":"","description":"\\u56fd\\u5bb6\\u516c\\u52a1\\u5458\\u62db\\u5f55\\u516c\\u5171\\u79d1\\u76ee\\u7b14\\u8bd5\\u5f00\\u8003\\uff0c\\u4eca\\u5e74\\u8003\\u751f\\u8d85\\u8fc7140\\u4e07\\uff0c\\u5c06\\u7ade\\u4e89137\\u4e2a\\u62db\\u8003\\u5355\\u4f4d\\u76841.6\\u4e07\\u4f59\\u5c97\\u4f4d\\uff0c\\u5e73\\u5747\\u7ade\\u4e89\\u6bd4\\u8fbe\\u523088\\u6bd41\\u3002\\u636e\\u7edf\\u8ba1\\uff0c\\u4eca\\u5e74\\u662f\\u7b14\\u8bd5\\u8d44\\u683c\\u5ba1\\u6838\\u8003\\u751f\\u4eba\\u6570\\u8fde\\u7eed\\u7b2c3\\u5e74\\u7a81\\u7834\\u767e\\u4e07\\u4eba\\uff0c\\u800c2003\\u5e74\\u8fd9\\u4e00\\u6570\\u5b57\\u4ec5\\u4e3a8.7\\u4e07\\u3002","thumb":"2012\\/1009\\/1349778567239.jpg","time":"1349778540"}],[{"title":"\\u5f20\\u53ec\\u5fe0\\uff1a\\u4e2d\\u56fd30\\u591a\\u5e74\\u5fcd\\u8fb1\\u8d1f\\u91cd\\u88ab\\u8d8a\\u5357\\u8bef\\u89e3\\u4e3a\\u5bb3\\u6015","url":"http:\\/\\/www.silkroad.news.cn\\/2012\\/1009\\/83.shtml","color":"","subtitle":"\\u5f20\\u53ec\\u5fe0\\uff1a\\u4e2d","suburl":"","description":"\\u8fd1\\u671f\\u5357\\u6d77\\u4e89\\u7aef\\u7684\\u7d27\\u5f20\\u5c40\\u52bf\\u4e0d\\u65ad\\u5347\\u6e29\\uff0c\\u8d8a\\u5357\\u548c\\u7f8e\\u83f2\\u63a5\\u8fde\\u5728\\u5357\\u6d77\\u8fdb\\u884c\\u6f14\\u4e60\\uff0c\\u90a3\\u4e48\\u5357\\u6d77\\u4e89\\u7aef\\u7684\\u5347\\u7ea7\\u662f\\u5426\\u4f1a\\u5bfc\\u81f4\\u519b\\u4e8b\\u51b2\\u7a81\\u5462\\uff1f\\u4eca\\u5929\\u6211\\u4eec\\u5c31\\u6765\\u8bf7\\u6559\\u4e00\\u4e0b\\u519b\\u4e8b\\u4e13\\u5bb6\\u5f20\\u53ec\\u5fe0\\u6559\\u6388","thumb":"2012\\/1009\\/1349775045950.jpg","time":"1349775101"}],[{"title":"\\u4e0d\\u8981\\u968f\\u4fbf\\u7814\\u7a76\\u4eba\\u5de5\\u667a\\u80fd","url":"http:\\/\\/www.silkroad.news.cn\\/2012\\/1009\\/67.shtml","color":"","subtitle":"\\u4e0d\\u8981\\u968f\\u4fbf\\u7814","suburl":"","description":"\\u4e0d\\u8981\\u968f\\u4fbf\\u7814\\u7a76\\u4eba\\u5de5\\u667a\\u80fd","thumb":"2012\\/1009\\/1349757544853.jpg","time":"1349757546"}],[{"title":"\\u97e9\\u65b0\\u4eba\\u6734\\u667a\\u654f\\u4e13\\u8bbf\\uff1a\\u6ca1\\u6709\\u7b7e\\u7ea6YG\\u4ece\\u672a\\u540e\\u6094","url":"http:\\/\\/www.silkroad.news.cn\\/2012\\/1008\\/35.shtml","color":"","subtitle":"\\u97e9\\u65b0\\u4eba\\u6734\\u667a","suburl":"","description":"\\u97e9\\u65b0\\u4eba\\u6734\\u667a\\u654f\\u4e13\\u8bbf\\uff1a\\u6ca1\\u6709\\u7b7e\\u7ea6YG\\u4ece\\u672a\\u540e\\u6094","thumb":"2012\\/1008\\/1349698251593.jpg","time":"1349687220"}]]', NULL, NULL, NULL, '<ul class="ui-picture-list">\n<!--{loop $data $k $r}-->\n<!--{loop $r $i $c}-->\n    <li>\n        <div>\n            <img src="{thumb($c[thumb], 140, 105, 1, null, 1, 95)}" width="140" height="105" />\n            <a href="{$c[url]}"><h3>{str_natcut($c[title], 8, '''')}</h3></a>\n        </div>\n    </li>\n<!--{/loop}-->\n<!--{/loop}-->\n</ul>', 'html', NULL, 4, 0, 0, '{"system_fields":{"contentid":{"checked":"1","func":"intval"},"title":{"checked":"1","required":"1","min_length":"","max_length":""},"url":{"checked":"1"},"color":{"checked":"1"},"icon":{"checked":"1"},"iconsrc":{"checked":"1"},"subtitle":{"checked":"1","min_length":"","max_length":""},"suburl":{"checked":"1"},"thumb":{"checked":"1","width":"","height":""},"description":{"checked":"1","min_length":"","max_length":""},"time":{"checked":"1","func":"section_fields_strtotime"}},"custom_fields":{"text":[],"name":[]}}', 1426842750, 1426842750, 0, 0, 1426060953, 1, 1393469107, 1, '', 1, 0, NULL, NULL, 10),
(246, 20, 'auto', '客户端下载', '<a href=\"\"><img src=\"theme/default/css/images/download.png\" alt=\"\"></a>', '<a download=\"\" id=\"client-link\" href=\"{APP_URL}?app=mobile&controller=index&action=wapclient\">下载安装手机客户端</a>\n', null, null, null, '', 'html', null, 0, 0, 0, null, 1427350257, 1427350257, 0, 0, 1427350255, 1, 1393496013, 1, '', 1, 0, null, null, 10),
(247, 26, 'push', '今日推荐', '[]', '[]', NULL, NULL, NULL, '<!--{loop $data $k $r}-->\n<li class="m-accordion-item<!--{if $k < 3}--> top<!--{/if}-->">\n    <a href="{$r[''url'']}" target="_blank" title="{$r[''title'']}" class="title" style="display:none;">{$r[''title'']}</a>\n    <div class="m-accordion-thumb ov">\n        <img class="thumb fl-l" src="{thumb($r[thumb], 120, 90, 1, null, 1)}" width="120" height="90" alt="" /><p><a href="{$r[''url'']}" target="_blank" title="{$r[''title'']}">{$r[''title'']}</a></p>\n    </div>\n</li>\n<!--{/loop}-->', 'html', NULL, 10, 0, 0, '{"system_fields":{"contentid":{"checked":"1","func":"intval"},"title":{"checked":"1","required":"1","min_length":"","max_length":""},"url":{"checked":"1"},"color":{"checked":"1"},"icon":{"checked":"1"},"iconsrc":{"checked":"1"},"subtitle":{"checked":"1","min_length":"","max_length":""},"suburl":{"checked":"1"},"thumb":{"checked":"1","width":"","height":""},"description":{"checked":"1","min_length":"","max_length":""},"time":{"checked":"1","func":"section_fields_strtotime"}},"custom_fields":{"text":[],"name":[]}}', 1422945103, 1422945103, 0, 0, 1422946797, 1, 1422945103, 1, '文章内容页今日推荐区块', 0, 0, 'section/list.html', 0, 10),
(248, 12, 'push', '今日推荐', '[]', '[{"contentid":11,"icon":"","iconsrc":"","title":"\\u4e60\\u8fd1\\u5e73\\uff1a\\u9886\\u5bfc\\u5e72\\u90e8\\u8981\\u505a\\u5c0a\\u6cd5\\u5b66\\u6cd5\\u5b88\\u6cd5\\u7528\\u6cd5\\u7684\\u6a21\\u8303","color":"","url":"http:\\/\\/www.silkroad.news.cn\\/2015\\/0203\\/11.shtml","subtitle":"","suburl":"","thumb":"2015\\/0204\\/1423030477911.png","description":"\\u592e\\u89c6\\u7f51\\u6d88\\u606f(\\u65b0\\u95fb\\u8054\\u64ad)\\uff1a\\u7701\\u90e8\\u7ea7\\u4e3b\\u8981\\u9886\\u5bfc\\u5e72\\u90e8\\u5b66\\u4e60\\u8d2f\\u5f7b\\u5341\\u516b\\u5c4a\\u56db\\u4e2d\\u5168\\u4f1a\\u7cbe\\u795e\\u5168\\u9762\\u63a8\\u8fdb\\u4f9d\\u6cd5\\u6cbb\\u56fd\\u4e13\\u9898\\u7814\\u8ba8\\u73ed2\\u65e5\\u5728\\u4e2d\\u592e\\u515a\\u6821\\u5f00\\u73ed\\u3002\\u4e2d\\u5171\\u4e2d\\u592e\\u603b\\u4e66\\u8bb0\\u3001\\u56fd\\u5bb6\\u4e3b\\u5e2d\\u3001\\u4e2d\\u592e\\u519b\\u59d4\\u4e3b\\u5e2d\\u4e60\\u8fd1\\u5e73\\u5728\\u5f00\\u73ed\\u5f0f\\u4e0a\\u53d1\\u8868\\u91cd\\u8981\\u8bb2\\u8bdd\\u3002\\u4ed6\\u5f3a\\u8c03\\uff0c\\u5404\\u7ea7\\u9886\\u5bfc\\u5e72\\u90e8\\u5728\\u63a8\\u8fdb\\u4f9d\\u6cd5\\u6cbb\\u56fd\\u65b9\\u9762\\u80a9\\u8d1f\\u7740\\u91cd\\u8981\\u8d23\\u4efb\\uff0c\\u5168\\u9762\\u4f9d\\u6cd5\\u6cbb\\u56fd\\u5fc5\\u987b\\u6293\\u4f4f\\u9886\\u5bfc\\u5e72\\u90e8\\u8fd9\\u4e2a\\u201c\\u5173\\u952e\\u5c11\\u6570\\u201d\\u3002\\u9886\\u5bfc\\u5e72\\u90e8\\u8981\\u505a\\u5c0a\\u6cd5\\u5b66\\u6cd5\\u5b88\\u6cd5\\u7528\\u6cd5\\u7684\\u6a21\\u8303\\uff0c\\u5e26\\u52a8\\u5168\\u515a\\u5168\\u56fd\\u4e00\\u8d77\\u52aa\\u529b\\uff0c\\u5728\\u5efa\\u8bbe\\u4e2d\\u56fd\\u7279\\u8272\\u793e\\u4f1a\\u4e3b\\u4e49\\u6cd5\\u6cbb\\u4f53\\u7cfb\\u3001\\u5efa\\u8bbe\\u793e\\u4f1a\\u4e3b\\u4e49\\u6cd5\\u6cbb\\u56fd\\u5bb6\\u4e0a\\u4e0d\\u65ad\\u89c1\\u5230\\u65b0\\u6210\\u6548\\u3002","time":1422949344},{"contentid":12,"icon":"","iconsrc":"","title":"\\u4e60\\u8fd1\\u5e73\\uff1a\\u9886\\u5bfc\\u5e72\\u90e8\\u8981\\u505a\\u5c0a\\u6cd5\\u5b66\\u6cd5\\u5b88\\u6cd5\\u7528\\u6cd5\\u7684\\u6a21\\u8303","color":"","url":"http:\\/\\/www.silkroad.news.cn\\/2015\\/0203\\/12.shtml","subtitle":"","suburl":"","thumb":"2015\\/0204\\/1423030483624.jpg","description":"\\u592e\\u89c6\\u7f51\\u6d88\\u606f(\\u65b0\\u95fb\\u8054\\u64ad)\\uff1a\\u7701\\u90e8\\u7ea7\\u4e3b\\u8981\\u9886\\u5bfc\\u5e72\\u90e8\\u5b66\\u4e60\\u8d2f\\u5f7b\\u5341\\u516b\\u5c4a\\u56db\\u4e2d\\u5168\\u4f1a\\u7cbe\\u795e\\u5168\\u9762\\u63a8\\u8fdb\\u4f9d\\u6cd5\\u6cbb\\u56fd\\u4e13\\u9898\\u7814\\u8ba8\\u73ed2\\u65e5\\u5728\\u4e2d\\u592e\\u515a\\u6821\\u5f00\\u73ed\\u3002\\u4e2d\\u5171\\u4e2d\\u592e\\u603b\\u4e66\\u8bb0\\u3001\\u56fd\\u5bb6\\u4e3b\\u5e2d\\u3001\\u4e2d\\u592e\\u519b\\u59d4\\u4e3b\\u5e2d\\u4e60\\u8fd1\\u5e73\\u5728\\u5f00\\u73ed\\u5f0f\\u4e0a\\u53d1\\u8868\\u91cd\\u8981\\u8bb2\\u8bdd\\u3002\\u4ed6\\u5f3a\\u8c03\\uff0c\\u5404\\u7ea7\\u9886\\u5bfc\\u5e72\\u90e8\\u5728\\u63a8\\u8fdb\\u4f9d\\u6cd5\\u6cbb\\u56fd\\u65b9\\u9762\\u80a9\\u8d1f\\u7740\\u91cd\\u8981\\u8d23\\u4efb\\uff0c\\u5168\\u9762\\u4f9d\\u6cd5\\u6cbb\\u56fd\\u5fc5\\u987b\\u6293\\u4f4f\\u9886\\u5bfc\\u5e72\\u90e8\\u8fd9\\u4e2a\\u201c\\u5173\\u952e\\u5c11\\u6570\\u201d\\u3002\\u9886\\u5bfc\\u5e72\\u90e8\\u8981\\u505a\\u5c0a\\u6cd5\\u5b66\\u6cd5\\u5b88\\u6cd5\\u7528\\u6cd5\\u7684\\u6a21\\u8303\\uff0c\\u5e26\\u52a8\\u5168\\u515a\\u5168\\u56fd\\u4e00\\u8d77\\u52aa\\u529b\\uff0c\\u5728\\u5efa\\u8bbe\\u4e2d\\u56fd\\u7279\\u8272\\u793e\\u4f1a\\u4e3b\\u4e49\\u6cd5\\u6cbb\\u4f53\\u7cfb\\u3001\\u5efa\\u8bbe\\u793e\\u4f1a\\u4e3b\\u4e49\\u6cd5\\u6cbb\\u56fd\\u5bb6\\u4e0a\\u4e0d\\u65ad\\u89c1\\u5230\\u65b0\\u6210\\u6548\\u3002","time":1422949351},{"contentid":3,"icon":"","iconsrc":"","title":"\\u4e60\\u8fd1\\u5e73:\\u4f9d\\u6cd5\\u6cbb\\u56fd\\u987b\\u6293\\u4f4f\\u5173\\u952e\\u5c11\\u6570","color":"","url":"http:\\/\\/www.silkroad.news.cn\\/2015\\/0203\\/2.shtml","subtitle":"","suburl":"","thumb":"2015\\/0204\\/1423030494271.jpg","description":"\\u7701\\u90e8\\u7ea7\\u4e3b\\u8981\\u9886\\u5bfc\\u5e72\\u90e8\\u5b66\\u4e60\\u8d2f\\u5f7b\\u5341\\u516b\\u5c4a\\u56db\\u4e2d\\u5168\\u4f1a\\u7cbe\\u795e\\u5168\\u9762\\u63a8\\u8fdb\\u4f9d\\u6cd5\\u6cbb\\u56fd\\u4e13\\u9898\\u7814\\u8ba8\\u73ed2\\u65e5\\u5728\\u4e2d\\u592e\\u515a\\u6821\\u5f00\\u73ed\\u3002\\u4e2d\\u5171\\u4e2d\\u592e\\u603b\\u4e66\\u8bb0\\u3001\\u56fd\\u5bb6\\u4e3b\\u5e2d\\u3001\\u4e2d\\u592e\\u519b\\u59d4\\u4e3b\\u5e2d\\u4e60\\u8fd1\\u5e73\\u5728\\u5f00\\u73ed\\u5f0f\\u4e0a\\u53d1\\u8868\\u91cd\\u8981\\u8bb2\\u8bdd\\u3002\\u4ed6\\u5f3a\\u8c03\\uff0c\\u5404\\u7ea7\\u9886\\u5bfc\\u5e72\\u90e8\\u5728\\u63a8\\u8fdb\\u4f9d\\u6cd5\\u6cbb\\u56fd\\u65b9\\u9762\\u80a9\\u8d1f\\u7740\\u91cd\\u8981\\u8d23\\u4efb\\uff0c\\u5168\\u9762\\u4f9d\\u6cd5\\u6cbb\\u56fd\\u5fc5\\u987b\\u6293\\u4f4f\\u9886\\u5bfc\\u5e72\\u90e8\\u8fd9\\u4e2a\\u201c\\u5173\\u952e\\u5c11\\u6570\\u201d\\u3002\\u9886\\u5bfc\\u5e72\\u90e8\\u8981\\u505a\\u5c0a\\u6cd5\\u5b66\\u6cd5\\u5b88\\u6cd5\\u7528\\u6cd5\\u7684\\u6a21\\u8303\\uff0c\\u5e26\\u52a8\\u5168\\u515a\\u5168\\u56fd\\u4e00\\u8d77\\u52aa\\u529b\\uff0c\\u5728\\u5efa\\u8bbe\\u4e2d\\u56fd\\u7279\\u8272\\u793e\\u4f1a\\u4e3b\\u4e49\\u6cd5\\u6cbb\\u4f53\\u7cfb\\u3001\\u5efa\\u8bbe\\u793e\\u4f1a\\u4e3b\\u4e49\\u6cd5\\u6cbb\\u56fd\\u5bb6\\u4e0a\\u4e0d\\u65ad\\u89c1\\u5230\\u65b0\\u6210\\u6548\\u3002","time":1422946210},{"contentid":12,"icon":"","iconsrc":"","title":"\\u4e60\\u8fd1\\u5e73\\uff1a\\u9886\\u5bfc\\u5e72\\u90e8\\u8981\\u505a\\u5c0a\\u6cd5\\u5b66\\u6cd5\\u5b88\\u6cd5\\u7528\\u6cd5\\u7684\\u6a21\\u8303","color":"","url":"http:\\/\\/www.silkroad.news.cn\\/2015\\/0203\\/12.shtml","subtitle":"","suburl":"","thumb":"2015\\/0204\\/1423030401622.jpg","description":"\\u592e\\u89c6\\u7f51\\u6d88\\u606f(\\u65b0\\u95fb\\u8054\\u64ad)\\uff1a\\u7701\\u90e8\\u7ea7\\u4e3b\\u8981\\u9886\\u5bfc\\u5e72\\u90e8\\u5b66\\u4e60\\u8d2f\\u5f7b\\u5341\\u516b\\u5c4a\\u56db\\u4e2d\\u5168\\u4f1a\\u7cbe\\u795e\\u5168\\u9762\\u63a8\\u8fdb\\u4f9d\\u6cd5\\u6cbb\\u56fd\\u4e13\\u9898\\u7814\\u8ba8\\u73ed2\\u65e5\\u5728\\u4e2d\\u592e\\u515a\\u6821\\u5f00\\u73ed\\u3002\\u4e2d\\u5171\\u4e2d\\u592e\\u603b\\u4e66\\u8bb0\\u3001\\u56fd\\u5bb6\\u4e3b\\u5e2d\\u3001\\u4e2d\\u592e\\u519b\\u59d4\\u4e3b\\u5e2d\\u4e60\\u8fd1\\u5e73\\u5728\\u5f00\\u73ed\\u5f0f\\u4e0a\\u53d1\\u8868\\u91cd\\u8981\\u8bb2\\u8bdd\\u3002\\u4ed6\\u5f3a\\u8c03\\uff0c\\u5404\\u7ea7\\u9886\\u5bfc\\u5e72\\u90e8\\u5728\\u63a8\\u8fdb\\u4f9d\\u6cd5\\u6cbb\\u56fd\\u65b9\\u9762\\u80a9\\u8d1f\\u7740\\u91cd\\u8981\\u8d23\\u4efb\\uff0c\\u5168\\u9762\\u4f9d\\u6cd5\\u6cbb\\u56fd\\u5fc5\\u987b\\u6293\\u4f4f\\u9886\\u5bfc\\u5e72\\u90e8\\u8fd9\\u4e2a\\u201c\\u5173\\u952e\\u5c11\\u6570\\u201d\\u3002\\u9886\\u5bfc\\u5e72\\u90e8\\u8981\\u505a\\u5c0a\\u6cd5\\u5b66\\u6cd5\\u5b88\\u6cd5\\u7528\\u6cd5\\u7684\\u6a21\\u8303\\uff0c\\u5e26\\u52a8\\u5168\\u515a\\u5168\\u56fd\\u4e00\\u8d77\\u52aa\\u529b\\uff0c\\u5728\\u5efa\\u8bbe\\u4e2d\\u56fd\\u7279\\u8272\\u793e\\u4f1a\\u4e3b\\u4e49\\u6cd5\\u6cbb\\u4f53\\u7cfb\\u3001\\u5efa\\u8bbe\\u793e\\u4f1a\\u4e3b\\u4e49\\u6cd5\\u6cbb\\u56fd\\u5bb6\\u4e0a\\u4e0d\\u65ad\\u89c1\\u5230\\u65b0\\u6210\\u6548\\u3002","time":1422949351},{"contentid":11,"icon":"","iconsrc":"","title":"\\u4e60\\u8fd1\\u5e73\\uff1a\\u9886\\u5bfc\\u5e72\\u90e8\\u8981\\u505a\\u5c0a\\u6cd5\\u5b66\\u6cd5\\u5b88\\u6cd5\\u7528\\u6cd5\\u7684\\u6a21\\u8303","color":"","url":"http:\\/\\/www.silkroad.news.cn\\/2015\\/0203\\/11.shtml","subtitle":"","suburl":"","thumb":"2015\\/0204\\/1423030413315.jpg","description":"\\u592e\\u89c6\\u7f51\\u6d88\\u606f(\\u65b0\\u95fb\\u8054\\u64ad)\\uff1a\\u7701\\u90e8\\u7ea7\\u4e3b\\u8981\\u9886\\u5bfc\\u5e72\\u90e8\\u5b66\\u4e60\\u8d2f\\u5f7b\\u5341\\u516b\\u5c4a\\u56db\\u4e2d\\u5168\\u4f1a\\u7cbe\\u795e\\u5168\\u9762\\u63a8\\u8fdb\\u4f9d\\u6cd5\\u6cbb\\u56fd\\u4e13\\u9898\\u7814\\u8ba8\\u73ed2\\u65e5\\u5728\\u4e2d\\u592e\\u515a\\u6821\\u5f00\\u73ed\\u3002\\u4e2d\\u5171\\u4e2d\\u592e\\u603b\\u4e66\\u8bb0\\u3001\\u56fd\\u5bb6\\u4e3b\\u5e2d\\u3001\\u4e2d\\u592e\\u519b\\u59d4\\u4e3b\\u5e2d\\u4e60\\u8fd1\\u5e73\\u5728\\u5f00\\u73ed\\u5f0f\\u4e0a\\u53d1\\u8868\\u91cd\\u8981\\u8bb2\\u8bdd\\u3002\\u4ed6\\u5f3a\\u8c03\\uff0c\\u5404\\u7ea7\\u9886\\u5bfc\\u5e72\\u90e8\\u5728\\u63a8\\u8fdb\\u4f9d\\u6cd5\\u6cbb\\u56fd\\u65b9\\u9762\\u80a9\\u8d1f\\u7740\\u91cd\\u8981\\u8d23\\u4efb\\uff0c\\u5168\\u9762\\u4f9d\\u6cd5\\u6cbb\\u56fd\\u5fc5\\u987b\\u6293\\u4f4f\\u9886\\u5bfc\\u5e72\\u90e8\\u8fd9\\u4e2a\\u201c\\u5173\\u952e\\u5c11\\u6570\\u201d\\u3002\\u9886\\u5bfc\\u5e72\\u90e8\\u8981\\u505a\\u5c0a\\u6cd5\\u5b66\\u6cd5\\u5b88\\u6cd5\\u7528\\u6cd5\\u7684\\u6a21\\u8303\\uff0c\\u5e26\\u52a8\\u5168\\u515a\\u5168\\u56fd\\u4e00\\u8d77\\u52aa\\u529b\\uff0c\\u5728\\u5efa\\u8bbe\\u4e2d\\u56fd\\u7279\\u8272\\u793e\\u4f1a\\u4e3b\\u4e49\\u6cd5\\u6cbb\\u4f53\\u7cfb\\u3001\\u5efa\\u8bbe\\u793e\\u4f1a\\u4e3b\\u4e49\\u6cd5\\u6cbb\\u56fd\\u5bb6\\u4e0a\\u4e0d\\u65ad\\u89c1\\u5230\\u65b0\\u6210\\u6548\\u3002","time":1422949344},{"contentid":3,"icon":"","iconsrc":"","title":"\\u4e60\\u8fd1\\u5e73:\\u4f9d\\u6cd5\\u6cbb\\u56fd\\u987b\\u6293\\u4f4f\\u5173\\u952e\\u5c11\\u6570","color":"","url":"http:\\/\\/www.silkroad.news.cn\\/2015\\/0203\\/2.shtml","subtitle":"","suburl":"","thumb":"2015\\/0204\\/1423030383296.jpg","description":"\\u7701\\u90e8\\u7ea7\\u4e3b\\u8981\\u9886\\u5bfc\\u5e72\\u90e8\\u5b66\\u4e60\\u8d2f\\u5f7b\\u5341\\u516b\\u5c4a\\u56db\\u4e2d\\u5168\\u4f1a\\u7cbe\\u795e\\u5168\\u9762\\u63a8\\u8fdb\\u4f9d\\u6cd5\\u6cbb\\u56fd\\u4e13\\u9898\\u7814\\u8ba8\\u73ed2\\u65e5\\u5728\\u4e2d\\u592e\\u515a\\u6821\\u5f00\\u73ed\\u3002\\u4e2d\\u5171\\u4e2d\\u592e\\u603b\\u4e66\\u8bb0\\u3001\\u56fd\\u5bb6\\u4e3b\\u5e2d\\u3001\\u4e2d\\u592e\\u519b\\u59d4\\u4e3b\\u5e2d\\u4e60\\u8fd1\\u5e73\\u5728\\u5f00\\u73ed\\u5f0f\\u4e0a\\u53d1\\u8868\\u91cd\\u8981\\u8bb2\\u8bdd\\u3002\\u4ed6\\u5f3a\\u8c03\\uff0c\\u5404\\u7ea7\\u9886\\u5bfc\\u5e72\\u90e8\\u5728\\u63a8\\u8fdb\\u4f9d\\u6cd5\\u6cbb\\u56fd\\u65b9\\u9762\\u80a9\\u8d1f\\u7740\\u91cd\\u8981\\u8d23\\u4efb\\uff0c\\u5168\\u9762\\u4f9d\\u6cd5\\u6cbb\\u56fd\\u5fc5\\u987b\\u6293\\u4f4f\\u9886\\u5bfc\\u5e72\\u90e8\\u8fd9\\u4e2a\\u201c\\u5173\\u952e\\u5c11\\u6570\\u201d\\u3002\\u9886\\u5bfc\\u5e72\\u90e8\\u8981\\u505a\\u5c0a\\u6cd5\\u5b66\\u6cd5\\u5b88\\u6cd5\\u7528\\u6cd5\\u7684\\u6a21\\u8303\\uff0c\\u5e26\\u52a8\\u5168\\u515a\\u5168\\u56fd\\u4e00\\u8d77\\u52aa\\u529b\\uff0c\\u5728\\u5efa\\u8bbe\\u4e2d\\u56fd\\u7279\\u8272\\u793e\\u4f1a\\u4e3b\\u4e49\\u6cd5\\u6cbb\\u4f53\\u7cfb\\u3001\\u5efa\\u8bbe\\u793e\\u4f1a\\u4e3b\\u4e49\\u6cd5\\u6cbb\\u56fd\\u5bb6\\u4e0a\\u4e0d\\u65ad\\u89c1\\u5230\\u65b0\\u6210\\u6548\\u3002","time":1422946210},{"contentid":14,"icon":"","iconsrc":"","title":"\\u5e7c\\u513f\\u821e\\u8e48\\u300a\\u5c0f\\u82f9\\u679c\\u300b \\u7fa4\\u821e\\u8868\\u6f14\\u89c6\\u9891","color":"","url":"http:\\/\\/www.silkroad.news.cn\\/2015\\/0204\\/14.shtml","subtitle":"","suburl":"","thumb":"2015\\/0204\\/1423030422846.png","description":"\\u5e7c\\u513f\\u821e\\u8e48\\u300a\\u5c0f\\u82f9\\u679c\\u300b \\u7fa4\\u821e\\u8868\\u6f14\\u89c6\\u9891\\u5e7c\\u513f\\u821e\\u8e48\\u300a\\u5c0f\\u82f9\\u679c\\u300b \\u7fa4\\u821e\\u8868\\u6f14\\u89c6\\u9891\\u5e7c\\u513f\\u821e\\u8e48\\u300a\\u5c0f\\u82f9\\u679c\\u300b \\u7fa4\\u821e\\u8868\\u6f14\\u89c6\\u9891\\u5e7c\\u513f\\u821e\\u8e48\\u300a\\u5c0f\\u82f9\\u679c\\u300b \\u7fa4\\u821e\\u8868\\u6f14\\u89c6\\u9891\\u5e7c\\u513f\\u821e\\u8e48\\u300a\\u5c0f\\u82f9\\u679c\\u300b \\u7fa4\\u821e\\u8868\\u6f14\\u89c6\\u9891\\u5e7c\\u513f\\u821e\\u8e48\\u300a\\u5c0f\\u82f9\\u679c\\u300b \\u7fa4\\u821e\\u8868\\u6f14\\u89c6\\u9891","time":1423029909}]', NULL, NULL, NULL, '\n<!--{loop $data $k $r}-->\n <li class="m-accordion-item top">\n        <a href="{$r[url]}" target="_blank" class="title" style="display:none;">{$r[title]}</a>\n        <div class="m-accordion-thumb ov">\n            <img class="thumb fl-l" src="{thumb($r[thumb],120,90)}" width="120" height="90" alt="{$r[title]}" /><p><a href="{$r[url]}" target="_blank">{$r[title]}</a></p>\n        </div>\n    </li>\n<!--{/loop}-->\n', 'html', NULL, 10, 0, 0, '{"system_fields":{"contentid":{"checked":"1","func":"intval"},"title":{"checked":"1","required":"1","min_length":"","max_length":""},"url":{"checked":"1"},"color":{"checked":"1"},"icon":{"checked":"1"},"iconsrc":{"checked":"1"},"subtitle":{"checked":"1","min_length":"","max_length":""},"suburl":{"checked":"1"},"thumb":{"checked":"1","width":"","height":""},"description":{"checked":"1","min_length":"","max_length":""},"time":{"checked":"1","func":"section_fields_strtotime"}},"custom_fields":{"text":[],"name":[]}}', 1426731208, 1426731208, 0, 0, 1423030508, 1, 1423030312, 1, '', 1, 0, 'section/list.html', 0, 10),
(249, 8, 'push', '左侧幻灯片', '[]', '[]', NULL, NULL, NULL, '<!--{loop $data $k $r}-->\n  <img src="{thumb($r[thumb],660,440,1,null,0)}" width="660" height="440" alt="{$r[title]}">\n            <div class="titlepanel pos-a"><time class="pos-a">{date(''H:i'',$r[time])}</time><h1 class="title"><a href="{$r[url]}" target="_blank">{$r[title]}</a></h1></div>\n            <div class="_overlay"></div>\n<!--{/loop}-->\n', 'html', NULL, 1, 0, 0, '{"system_fields":{"contentid":{"checked":"1","func":"intval"},"title":{"checked":"1","required":"1","min_length":"","max_length":""},"url":{"checked":"1"},"color":{"checked":"1"},"icon":{"checked":"1"},"iconsrc":{"checked":"1"},"subtitle":{"checked":"1","min_length":"","max_length":""},"suburl":{"checked":"1"},"thumb":{"checked":"1","width":"","height":""},"description":{"checked":"1","min_length":"","max_length":""},"time":{"checked":"1","func":"section_fields_strtotime"}},"custom_fields":{"text":[],"name":[]}}', 1423103830, 1423103830, 0, 0, 1423104324, 1, 1423103544, 1, '', 0, 0, 'section/list.html', 0, 10),
(250, 6, 'auto', '视频内容页右侧广告', '', '<script type="text/javascript" id="adm-84">\n(function() {\n   window.ADMBlocks = window.ADMBlocks || [];\n   ADMBlocks.push({\n       id : ''84'',  // 广告位id\n       width : ''300'',  // 宽\n       height : ''240'',  // 高\n       type : ''1''  // 类型\n   });\n   var h=document.getElementsByTagName(''head'')[0], s=document.createElement(''script'');\n   s.async=true; s.src=''http://ad.cmstop.cn/js/show.js'';\n   h && h.insertBefore(s,h.firstChild)\n})();\n</script>\n', NULL, NULL, NULL, '', 'html', NULL, 0, 300, 0, NULL, 1426731498, 1426731198, 0, 0, 1426731101, 1, 1423556549, 1, '', 1, 0, NULL, NULL, 10),
(251, 6, 'auto', '视频内容页广告右下', '', '<script type="text/javascript" id="adm-85">\n(function() {\n   window.ADMBlocks = window.ADMBlocks || [];\n   ADMBlocks.push({\n       id : ''85'',  // 广告位id\n       width : ''300'',  // 宽\n       height : ''90'',  // 高\n       type : ''1''  // 类型\n   });\n   var h=document.getElementsByTagName(''head'')[0], s=document.createElement(''script'');\n   s.async=true; s.src=''http://ad.cmstop.cn/js/show.js'';\n   h && h.insertBefore(s,h.firstChild)\n})();\n</script>\n', NULL, NULL, NULL, '', 'html', NULL, 0, 300, 0, NULL, 1426731499, 1426731199, 0, 0, 1426731105, 1, 1423556948, 1, '', 1, 0, NULL, NULL, 10),
(252, 31, 'auto', '轮播右上部广告', '', '<script type="text/javascript" id="adm-75">\n(function() {\n   window.ADMBlocks = window.ADMBlocks || [];\n   ADMBlocks.push({\n       id : ''75'',  // 广告位id\n       width : ''235'',  // 宽\n       height : ''284'',  // 高\n       type : ''1''  // 类型\n   });\n   var h=document.getElementsByTagName(''head'')[0], s=document.createElement(''script'');\n   s.async=true; s.src=''http://ad.cmstop.cn/js/show.js'';\n   h && h.insertBefore(s,h.firstChild)\n})();\n</script>\n', NULL, NULL, NULL, '', 'html', NULL, 0, 300, 0, NULL, 1426731519, 1426731219, 0, 0, 1426731163, 1, 1423559907, 1, '', 1, 0, NULL, NULL, 10),
(253, 31, 'auto', '轮播右下侧广告', '', '<script type="text/javascript" id="adm-76">\n(function() {\n   window.ADMBlocks = window.ADMBlocks || [];\n   ADMBlocks.push({\n       id : ''76'',  // 广告位id\n       width : ''235'',  // 宽\n       height : ''160'',  // 高\n       type : ''1''  // 类型\n   });\n   var h=document.getElementsByTagName(''head'')[0], s=document.createElement(''script'');\n   s.async=true; s.src=''http://ad.cmstop.cn/js/show.js'';\n   h && h.insertBefore(s,h.firstChild)\n})();\n</script>\n', NULL, NULL, NULL, '', 'html', NULL, 0, 300, 0, NULL, 1426731520, 1426731220, 0, 0, 1426731166, 1, 1423560509, 1, '', 1, 0, NULL, NULL, 10),
(254, 33, 'auto', '右侧300x90', '', '<script type="text/javascript" id="adm-86">\n(function() {\n   window.ADMBlocks = window.ADMBlocks || [];\n   ADMBlocks.push({\n       id : ''86'',  // 广告位id\n       width : ''300'',  // 宽\n       height : ''90'',  // 高\n       type : ''1''  // 类型\n   });\n   var h=document.getElementsByTagName(''head'')[0], s=document.createElement(''script'');\n   s.async=true; s.src=''http://ad.cmstop.cn/js/show.js'';\n   h && h.insertBefore(s,h.firstChild)\n})();\n</script>\n', NULL, NULL, NULL, '', 'html', NULL, 0, 300, 0, NULL, 1426731523, 1426731223, 0, 0, 1426731171, 1, 1423561299, 1, '', 1, 0, NULL, NULL, 10),
(255, 36, 'auto', '顶部 1000 x 80', '<a href="http://www.silkroad.news.cn/"><img src="{IMG_URL}templates/{TEMPLATE}/img/ad/ad1.jpg" alt="" width="1100" height="80" /></a>', '<a href="http://www.silkroad.news.cn/"><img src="{IMG_URL}templates/{TEMPLATE}/img/ad/ad1.jpg" alt="" width="1000" height="80" /></a>', NULL, NULL, NULL, '', 'html', NULL, 0, 300, 0, NULL, 1425869412, 1425869112, 0, 0, 1425542604, 1, 1425540255, 1, '', 1, 0, NULL, NULL, 10),
(256, 33, 'auto', '访谈频道页 幻灯片右上广告', '', '<script type="text/javascript" id="adm-77">\n(function() {\n   window.ADMBlocks = window.ADMBlocks || [];\n   ADMBlocks.push({\n       id : ''77'',  // 广告位id\n       width : ''235'',  // 宽\n       height : ''284'',  // 高\n       type : ''1''  // 类型\n   });\n   var h=document.getElementsByTagName(''head'')[0], s=document.createElement(''script'');\n   s.async=true; s.src=''http://ad.cmstop.cn/js/show.js'';\n   h && h.insertBefore(s,h.firstChild)\n})();\n</script>\n', NULL, NULL, NULL, '', 'html', NULL, 0, 300, 0, NULL, 1426731524, 1426731224, 0, 0, 1426731175, 1, 1426061059, 1, '', 1, 0, NULL, NULL, 10);
INSERT INTO `cmstop_section` (`sectionid`, `pageid`, `type`, `name`, `origdata`, `data`, `url`, `method`, `args`, `template`, `output`, `width`, `rows`, `frequency`, `check`, `fields`, `nextupdate`, `published`, `locked`, `lockedby`, `updated`, `updatedby`, `created`, `createdby`, `description`, `status`, `list_enabled`, `list_template`, `list_pagesize`, `list_pages`) VALUES
(257, 1, 'push', '头条下标题', '[]', '[{"contentid":23,"title":"\\u4e00\\u8f86\\u65b0\\u8f66\\u7b49\\u540c\\u4e00\\u6bd2\\u6c14\\u5ba4 \\u90e8\\u5206\\u5546\\u5bb6\\u8d81\\u6b64\\u8d62\\u5546\\u673a","url":"http:\\/\\/www.silkroad.news.cn\\/2012\\/1008\\/23.shtml","color":"","subtitle":"\\u4e00\\u8f86\\u65b0\\u8f66\\u7b49","suburl":"","description":"\\u4e00\\u8f86\\u65b0\\u8f66\\u7b49\\u540c\\u4e00\\u6bd2\\u6c14\\u5ba4 \\u90e8\\u5206\\u5546\\u5bb6\\u8d81\\u6b64\\u8d62\\u5546\\u673a","thumb":"2012\\/1008\\/1349698647311.jpg","time":"1349661600"},{"contentid":77,"title":"\\u5f20\\u53ec\\u5fe0\\uff1a\\u7f8e\\u56fd\\u5728\\u5229\\u6bd4\\u4e9a\\u5c1d\\u8bd5\\u501f\\u5200\\u6740\\u4eba\\u65b0\\u6218\\u7565","url":"http:\\/\\/www.silkroad.news.cn\\/2012\\/0926\\/77.shtml","color":"","subtitle":"\\u5f20\\u53ec\\u5fe0\\uff1a\\u7f8e","suburl":"","description":"\\u5229\\u6bd4\\u4e9a\\u653f\\u5e9c\\u519b\\u88c5\\u7532\\u90e8\\u961f\\u906d\\u6bc1\\u706d\\u6027\\u6253\\u51fb","thumb":"2012\\/0926\\/1348622987668.png","time":"1348623129"},{"contentid":73,"title":"\\u9ad8\\u7aef\\u8bbf\\u8c08\\uff5c\\u7a46\\u62c9\\u5229\\uff1a\\u6797\\u80af\\u5c06\\u5165\\u534e \\u672a\\u6765\\u6216\\u56fd\\u4ea7","url":"http:\\/\\/www.silkroad.news.cn\\/2012\\/0925\\/73.shtml","color":"","subtitle":"\\u9ad8\\u7aef\\u8bbf\\u8c08\\uff5c","suburl":"","description":"8\\u670828\\u65e5\\uff0c\\u5728\\u6797\\u80af\\u54c1\\u724c\\u7b2c\\u4e00\\u6b21\\u4eae\\u76f8\\u4e2d\\u56fd\\u4e4b\\u65f6\\uff0c\\u9762\\u5bf9\\u817e\\u8baf\\u6c7d\\u8f66\\uff0c\\u798f\\u7279\\u5168\\u7403CEO\\u7a46\\u62c9\\u5229\\u4e0d\\u4ec5\\u89e3\\u8bfb\\u4e86\\u798f\\u7279\\u5728\\u4e2d\\u56fd\\u7684\\u53d1\\u5c55\\u6218\\u7565\\uff0c\\u540c\\u65f6\\u5bf9\\u4e8e\\u4eba\\u624d\\u672c\\u571f\\u5316\\u7684\\u95ee\\u9898\\u4e5f\\u8bf4\\u51fa\\u4e86\\u81ea\\u5df1\\u7684\\u770b\\u6cd5\\u3002","thumb":"2012\\/0925\\/1348542108721.jpg","time":"1348542780"}]', NULL, NULL, NULL, '<p class="ov">\n<!--{loop $data $k $r}-->\n    <a href="{$r[url]}" target="_blank" title="{$r[title]}">{$r[title]}</a>\n<!--{/loop}-->\n</p>', 'html', NULL, 3, 0, 0, '{"system_fields":{"contentid":{"checked":"1","func":"intval"},"title":{"checked":"1","required":"1","min_length":"","max_length":""},"url":{"checked":"1"},"color":{"checked":"1"},"icon":{"checked":"1"},"iconsrc":{"checked":"1"},"subtitle":{"checked":"1","min_length":"","max_length":""},"suburl":{"checked":"1"},"thumb":{"checked":"1","width":"","height":""},"description":{"checked":"1","min_length":"","max_length":""},"time":{"checked":"1","func":"section_fields_strtotime"}},"custom_fields":{"text":[],"name":[]}}', 1426917237, 1426917236, 0, 0, 1426558089, 1, 1426507526, 1, '', 1, 0, 'section/list.html', 0, 10),
(258, 9, 'hand', 'test', '[[],[],[],[],[],[]]', '[[{"contentid":149,"icon":"","iconsrc":"","title":"\\u516c\\u5b89\\u90e8:\\u8fd1\\u5e7496.7%\\u5047\\u5e01\\u5370\\u7248\\u51fa\\u81ea\\u540c\\u4e00\\u753b\\u5de5\\u4e4b\\u624b","color":"","url":"http:\\/\\/www.silkroad.news.cn\\/2015\\/0317\\/149.shtml","subtitle":"","suburl":"","thumb":"","description":"\\u65b0\\u534e\\u7f51\\u5317\\u4eac3\\u670816\\u65e5\\u7535 \\u5728\\u8fd9\\u91cc\\uff0c\\u4e0d\\u7ba1\\u591a\\u201c\\u771f\\u201d\\u7684\\u5047\\u5e01\\uff0c\\u90fd\\u80fd\\u9a8c\\u660e\\u6b63\\u8eab\\uff1b\\u5728\\u8fd9\\u91cc\\uff0c\\u4e00\\u5f20\\u5047\\u5e01\\u7eb8\\uff0c\\u80fd\\u900f\\u9732\\u51fa\\u6765\\u81ea\\u9488\\u53f6\\u6728\\u3001\\u9614\\u53f6\\u6728\\u7684\\u201c\\u524d\\u4e16\\u4eca\\u751f\\u201d\\uff0c\\u67e5\\u5230\\u539f\\u4ea7\\u5730\\u5728\\u54ea\\uff1b\\u5728\\u8fd9\\u91cc\\uff0c\\u4e00\\u5f20\\u5047\\u5e01\\uff0c\\u80fd\\u770b\\u51fa\\u753b\\u5de5\\u662f\\u8c01\\uff0c\\u5982\\u4f55\\u6d41\\u8361\\u6c5f\\u6e56","time":1426573500}],[{"contentid":149,"icon":"","iconsrc":"","title":"\\u516c\\u5b89\\u90e8:\\u8fd1\\u5e7496.7%\\u5047\\u5e01\\u5370\\u7248\\u51fa\\u81ea\\u540c\\u4e00\\u753b\\u5de5\\u4e4b\\u624b","color":"","url":"http:\\/\\/www.silkroad.news.cn\\/2015\\/0317\\/149.shtml","subtitle":"","suburl":"","thumb":"","description":"\\u65b0\\u534e\\u7f51\\u5317\\u4eac3\\u670816\\u65e5\\u7535 \\u5728\\u8fd9\\u91cc\\uff0c\\u4e0d\\u7ba1\\u591a\\u201c\\u771f\\u201d\\u7684\\u5047\\u5e01\\uff0c\\u90fd\\u80fd\\u9a8c\\u660e\\u6b63\\u8eab\\uff1b\\u5728\\u8fd9\\u91cc\\uff0c\\u4e00\\u5f20\\u5047\\u5e01\\u7eb8\\uff0c\\u80fd\\u900f\\u9732\\u51fa\\u6765\\u81ea\\u9488\\u53f6\\u6728\\u3001\\u9614\\u53f6\\u6728\\u7684\\u201c\\u524d\\u4e16\\u4eca\\u751f\\u201d\\uff0c\\u67e5\\u5230\\u539f\\u4ea7\\u5730\\u5728\\u54ea\\uff1b\\u5728\\u8fd9\\u91cc\\uff0c\\u4e00\\u5f20\\u5047\\u5e01\\uff0c\\u80fd\\u770b\\u51fa\\u753b\\u5de5\\u662f\\u8c01\\uff0c\\u5982\\u4f55\\u6d41\\u8361\\u6c5f\\u6e56","time":1426573500}],[{"contentid":146,"icon":"","iconsrc":"","title":"12121313","color":"","url":"http:\\/\\/special.silkroad.news.cn\\/150317-1","subtitle":"","suburl":"","thumb":"","description":"kjhkjhkj","time":1426562844}],[{"contentid":143,"icon":"","iconsrc":"","title":"asdasdas","color":"","url":"http:\\/\\/www.silkroad.news.cn\\/2015\\/0316\\/142.shtml","subtitle":"","suburl":"","thumb":"","description":"asdasd","time":1426498030}],[{"contentid":138,"icon":"","iconsrc":"","title":"CCC\\u534a\\u51b3\\u8d5b_\\u7b2c\\u5341\\u6bd4\\u8d5b\\u65e5_\\u706b\\u732b\\u5c0f\\u8c37\\u82b7vs\\u5c11\\u5e2e\\u4e3b_\\u65c5\\u6cd5\\u5e08\\u8425\\u5730\\u6d77\\u5c9bvs\\u8427\\u9b42","color":"","url":"http:\\/\\/www.silkroad.news.cn\\/2015\\/0312\\/138.shtml","subtitle":"","suburl":"","thumb":"http:\\/\\/upload.silkroad.news.cn\\/2015\\/0312\\/1426143336111.jpeg","description":"","time":1426143350}],[{"contentid":134,"icon":"","iconsrc":"","title":"\\u8fc7\\u5e74\\u56de\\u5bb6\\u505a\\u4ec0\\u4e48\\u8f66","color":"","url":"http:\\/\\/www.silkroad.news.cn\\/2015\\/0311\\/134.shtml","subtitle":"","suburl":"","thumb":"http:\\/\\/upload.silkroad.news.cn\\/2012\\/1016\\/1350374419657.jpg","description":"\\u8fc7\\u5e74\\u54ea\\u6709\\u4e0d\\u56de\\u5bb6\\u7684\\u8fc7\\u5e74\\u54ea\\u6709\\u4e0d\\u56de\\u5bb6\\u7684\\u8fc7\\u5e74\\u54ea\\u6709\\u4e0d\\u56de\\u5bb6\\u7684\\u8fc7\\u5e74\\u54ea\\u6709\\u4e0d\\u56de\\u5bb6\\u7684\\u8fc7\\u5e74\\u54ea\\u6709\\u4e0d\\u56de\\u5bb6\\u7684\\u8fc7\\u5e74\\u54ea\\u6709\\u4e0d\\u56de\\u5bb6\\u7684\\u8fc7\\u5e74\\u54ea\\u6709\\u4e0d\\u56de\\u5bb6\\u7684\\u8fc7\\u5e74\\u54ea\\u6709\\u4e0d\\u56de\\u5bb6\\u7684\\u8fc7\\u5e74\\u54ea\\u6709\\u4e0d\\u56de\\u5bb6\\u7684\\u8fc7\\u5e74\\u54ea\\u6709\\u4e0d\\u56de\\u5bb6\\u7684\\u8fc7\\u5e74\\u54ea\\u6709\\u4e0d\\u56de\\u5bb6\\u7684\\u8fc7\\u5e74\\u54ea\\u6709\\u4e0d\\u56de\\u5bb6\\u7684\\u8fc7\\u5e74\\u54ea\\u6709\\u4e0d\\u56de\\u5bb6\\u7684\\u8fc7\\u5e74\\u54ea\\u6709\\u4e0d\\u56de\\u5bb6\\u7684\\u8fc7\\u5e74\\u54ea\\u6709\\u4e0d\\u56de\\u5bb6\\u7684","time":1426063320}]]', NULL, NULL, NULL, '<ul>\n<?php console($data);?>\n<!--{loop $data $k $r}-->\n  <li><!--{loop $r $i $c}-->\n    <a{if $c[icon]} class="icon-{$c[icon]}"{/if} href="{$c[url]}">{$c[title]}</a>\n    <!--{/loop}-->\n  </li>\n<!--{/loop}-->\n</ul>', 'html', NULL, 6, 0, 0, '{"system_fields":{"contentid":{"checked":"1","func":"intval"},"title":{"checked":"1","required":"1","min_length":"","max_length":""},"url":{"checked":"1"},"color":{"checked":"1"},"icon":{"checked":"1"},"iconsrc":{"checked":"1"},"subtitle":{"checked":"1","min_length":"","max_length":""},"suburl":{"checked":"1"},"thumb":{"checked":"1","width":"","height":""},"description":{"checked":"1","min_length":"","max_length":""},"time":{"checked":"1","func":"section_fields_strtotime"}},"custom_fields":{"text":[],"name":[]}}', 1426660931, 1426660931, 0, 0, 1426734324, 1, 1426660768, 1, '', 0, 0, NULL, NULL, 10);

-- --------------------------------------------------------

--
-- Table structure for table `cmstop_section_history`
--

CREATE TABLE IF NOT EXISTS `cmstop_section_history` (
  `logid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `sectionid` smallint(5) unsigned NOT NULL,
  `data` mediumtext,
  `created` int(10) unsigned NOT NULL,
  `createdby` mediumint(8) unsigned NOT NULL,
  `ip` varchar(15) NOT NULL,
  `state` tinyint(1) unsigned NOT NULL,
  PRIMARY KEY (`logid`),
  KEY `createdby` (`createdby`),
  KEY `sectionid` (`sectionid`,`logid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `cmstop_section_log`
--

CREATE TABLE IF NOT EXISTS `cmstop_section_log` (
  `logid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `sectionid` smallint(5) unsigned NOT NULL,
  `action` varchar(10) NOT NULL,
  `createdby` mediumint(8) unsigned NOT NULL,
  `created` int(10) unsigned NOT NULL,
  PRIMARY KEY (`logid`),
  KEY `sectionid` (`sectionid`),
  KEY `createdby` (`createdby`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `cmstop_section_priv`
--

CREATE TABLE IF NOT EXISTS `cmstop_section_priv` (
  `sectionid` smallint(5) unsigned NOT NULL DEFAULT '0',
  `userid` mediumint(8) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`sectionid`,`userid`),
  KEY `userid` (`userid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `cmstop_section_recommend`
--

CREATE TABLE IF NOT EXISTS `cmstop_section_recommend` (
  `recommendid` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `contentid` mediumint(8) unsigned DEFAULT NULL,
  `sectionid` smallint(5) unsigned NOT NULL,
  `data` text,
  `published` int(10) unsigned NOT NULL,
  `recommended` int(10) unsigned NOT NULL,
  `recommendedby` mediumint(8) unsigned NOT NULL,
  `status` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `sort` smallint(5) unsigned NOT NULL DEFAULT '0',
  `istop` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `isdeleted` tinyint(3) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`recommendid`),
  KEY `contentid` (`contentid`),
  KEY `sectionid` (`sectionid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `cmstop_section_url`
--

CREATE TABLE IF NOT EXISTS `cmstop_section_url` (
  `sectionid` smallint(5) unsigned NOT NULL,
  `url` char(32) NOT NULL,
  KEY `url` (`url`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `cmstop_session`
--

CREATE TABLE IF NOT EXISTS `cmstop_session` (
  `sessionid` varchar(32) NOT NULL,
  `lastvisit` int(10) unsigned NOT NULL DEFAULT '0',
  `data` text,
  PRIMARY KEY (`sessionid`),
  KEY `lastvisit` (`lastvisit`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

-- --------------------------------------------------------

--
-- Table structure for table `cmstop_setting`
--

CREATE TABLE IF NOT EXISTS `cmstop_setting` (
  `app` varchar(15) NOT NULL DEFAULT 'system',
  `var` varchar(32) NOT NULL,
  `value` text,
  PRIMARY KEY (`app`,`var`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `cmstop_setting`
--

INSERT INTO `cmstop_setting` (`app`, `var`, `value`) VALUES
('activity', 'seccode', '\"1\"'),
('article', 'source', '\"\"'),
('article', 'thumb_height', '\"0\"'),
('article', 'thumb_width', '\"500\"'),
('article', 'watermark', '\"1\"'),
('article', 'weight', '\"60\"'),
('cloud', 'action', '\"set_setting\"'),
('cloud', 'app', '\"cloud\"'),
('cloud', 'bshare', '\"{\\\"uuid\\\":\\\"d06055db-abd9-4f2a-8b2e-0427409f882f\\\",\\\"secret\\\":\\\"cb1a32da-2be5-4f4b-b0c3-5992abec39f6\\\",\\\"name\\\":\\\"shaolei@cmstop.com\\\"}\"'),
('cloud', 'controller', '\"cloud\"'),
('cloud', 'follow_data', '\"<a title=\\\"\\u817e\\u8baf\\u5fae\\u535a\\\" target=\\\"_blank\\\" href=\\\"http:\\/\\/t.qq.com\\/cmstop\\\"><img width=\\\"16\\\" height=\\\"16\\\" style=\\\"margin-bottom: -3px;\\\" src=\\\"http:\\/\\/static.bshare.cn\\/frame\\/images\\/logos\\/s4\\/qqmb.gif\\\"><\\/a>  <a title=\\\"\\u65b0\\u6d6a\\u5fae\\u535a\\\" target=\\\"_blank\\\" href=\\\"http:\\/\\/weibo.com\\/cmstop\\\"><img width=\\\"16\\\" height=\\\"16\\\" style=\\\"margin-bottom: -3px;\\\" src=\\\"http:\\/\\/static.bshare.cn\\/frame\\/images\\/logos\\/s4\\/sinaminiblog.gif\\\"><\\/a>\"'),
('cloud', 'init', '\"ddfadfadfxcvvxcvjkfhxvnlsnflkcvn\"'),
('cloud', 'o2h_allowed', '\"1\"'),
('cloud', 'o2h_secret', '\"8IUJKLO9876TYHGFR4EDFGT65RFG3Y\"'),
('cloud', 'onekeyfollow', '\"1\"'),
('cloud', 'rule_address', '\"http:\\/\\/api.cloud.cmstop.com:8001\\/rule\\/\"'),
('cloud', 'rule_allowed', '\"1\"'),
('cloud', 'rule_auto_update', '\"1\"'),
('cloud', 'share', '\"1\"'),
('cloud', 'share_data', '\"<!-- Baidu Button BEGIN --><div id=\\\"bdshare\\\" class=\\\"bdshare_t bds_tools get-codes-bdshare\\\"><span class=\\\"bds_more\\\">\\u5206\\u4eab\\u5230\\uff1a<\\/span><a class=\\\"bds_qzone\\\"><\\/a><a class=\\\"bds_tsina\\\"><\\/a><a class=\\\"bds_tqq\\\"><\\/a><a class=\\\"bds_renren\\\"><\\/a><a class=\\\"bds_t163\\\"><\\/a><a class=\\\"shareCount\\\"><\\/a><\\/div><script type=\\\"text\\/javascript\\\" id=\\\"bdshare_js\\\" data=\\\"type=tools&uid=0\\\" ><\\/script><script type=\\\"text\\/javascript\\\" id=\\\"bdshell_js\\\"><\\/script><script type=\\\"text\\/javascript\\\">document.getElementById(\\\"bdshell_js\\\").src = \\\"http:\\/\\/bdimg.share.baidu.com\\/static\\/js\\/shell_v2.js?cdnversion=\\\" + Math.ceil(new Date()\\/3600000);<\\/script><!-- Baidu Button END -->\"'),
('cloud', 'spider_address', '\"http:\\/\\/api.cloud.cmstop.com:8001\\/spider\\/\"'),
('cloud', 'spider_allowed', '\"1\"'),
('cloud', 'thirdlogin', '\"1\"'),
('comment', 'defaultname', '\"\\u7f51\\u53cb\"'),
('comment', 'floorno', '\"5\"'),
('comment', 'hotcomment', '\"10\"'),
('comment', 'iptime', '\"1\"'),
('comment', 'ischeck', '\"0\"'),
('comment', 'islogin', '\"0\"'),
('comment', 'isseccode', '\"0\"'),
('comment', 'pagesize', '\"10\"'),
('comment', 'sensekeyword', '\"\"'),
('comment', 'timeinterval', '\"5\"'),
('comment', 'unsafekeyword', '\"\\u6cd5\\u8f6e\\u529f\"'),
('comment', 'wordage', '\"1000\"'),
('contribution', 'iscontribute', '\"0\"'),
('contribution', 'isseccode', '\"1\"'),
('contribution', 'pagesize', '\"15\"'),
('digg', 'pagesize', '\"15\"'),
('digg', 'refresh', '\"30\"'),
('editor', 'thumb_height', '\"0\"'),
('editor', 'thumb_width', '\"500\"'),
('editor', 'upload_max_filesize', '\"5\"'),
('editor', 'watermark', '\"1\"'),
('guestbook', 'guestbookname', '\"\\u7559\\u8a00\\u672c\\u6807\\u9898\"'),
('guestbook', 'iscode', '\"1\"'),
('guestbook', 'managelist', '\"cmstop\"'),
('guestbook', 'memberguest', '\"1\"'),
('guestbook', 'option', '{\"reply\":\"\",\"gender\":\"1\",\"email\":\"1\",\"address\":\"1\",\"telephone\":\"1\",\"qq\":\"1\",\"msn\":\"1\",\"homepage\":\"1\"}'),
('guestbook', 'pagesize', '\"10\"'),
('guestbook', 'repliedshow', '\"1\"'),
('guestbook', 'replymax', '\"1000\"'),
('guestbook', 'sensekeyword', '\"\\u6cd5\\u8f6e\\u529f\\n\"'),
('guestbook', 'showmanage', '\"\\u8bc4\\u8bba\\u7ba1\\u7406\\u5458\"'),
('guestbook', 'unguestlist', '\"1\"'),
('magazine', 'path', '\"{PSN:1}\\/magazine\"'),
('member', 'agreement', '\"<a href=\\\"#\\\">\\u6ce8\\u518c\\u534f\\u8bae<\\/a>\"'),
('member', 'allowreg', '\"1\"'),
('member', 'ban_name', '\"\"'),
('member', 'closereason', '\"\\u6ce8\\u518c\\u5df2\\u5173\\u95ed\"'),
('member', 'default_group', '\"4\"'),
('member', 'groupid', '\"4\"'),
('member', 'lock_minute', '\"60\"'),
('member', 'log_max', '\"5\"'),
('member', 'need_audit', '\"0\"'),
('member', 'pw_api', '\"\"'),
('member', 'pw_appid', '\"3\"'),
('member', 'pw_charset', '\"utf8\"'),
('member', 'pw_connect', '\"mysql\"'),
('member', 'pw_dbcharset', '\"utf8\"'),
('member', 'pw_dbconnect', '\"0\"'),
('member', 'pw_dbhost', '\"localhost\"'),
('member', 'pw_dbport', '\"\"'),
('member', 'pw_dbpw', '\"\"'),
('member', 'pw_dbtablepre', '\"pw_\"'),
('member', 'pw_dbuser', '\"root\"'),
('member', 'pw_ip', '\"\"'),
('member', 'pw_key', '\"\"'),
('member', 'uc', '\"0\"'),
('member', 'uc_api', '\"\"'),
('member', 'uc_appid', '\"2\"'),
('member', 'uc_charset', '\"utf8\"'),
('member', 'uc_connect', '\"mysql\"'),
('member', 'uc_dbcharset', '\"utf8\"'),
('member', 'uc_dbconnect', '\"0\"'),
('member', 'uc_dbhost', '\"localhost\"'),
('member', 'uc_dbname', '\"ucenter\"'),
('member', 'uc_dbpw', '\"\"'),
('member', 'uc_dbtablepre', '\"uc_\"'),
('member', 'uc_dbuser', '\"root\"'),
('member', 'uc_ip', '\"\"'),
('member', 'uc_key', '\"cmstop.com\"'),
('mobile', 'aboutus', '\"\\u5173\\u4e8e\\u6211\\u4eec\"'),
('mobile', 'ad_more', '\"{\\\"url\\\":[\\\"http:\\/\\/www.cmstop.com\\\",\\\"http:\\/\\/www.cmstop.com\\\"],\\\"horizon\\\":[\\\"http:\\/\\/m.silkroad.news.cn\\/templates\\/default\\/app\\/images\\/pad\\/more_h_1@2x.png\\\",\\\"http:\\/\\/m.silkroad.news.cn\\/templates\\/default\\/app\\/images\\/pad\\/more_h_2@2x.png\\\"],\\\"vertical\\\":[\\\"http:\\/\\/m.silkroad.news.cn\\/templates\\/default\\/app\\/images\\/pad\\/more_v_1@2x.png\\\",\\\"http:\\/\\/m.silkroad.news.cn\\/templates\\/default\\/app\\/images\\/pad\\/more_v_2@2x.png\\\"]}\"'),
('mobile', 'android_url', '\"\"'),
('mobile', 'android_version', '\"\"'),
('mobile', 'android_version_description', '\"\"'),
('mobile', 'android_version_url', '\"\"'),
('mobile', 'api', '[]'),
('mobile', 'baoliao', '{\"islogin\":\"1\",\"max_picsize\":\"10\",\"max_videosize\":\"100\"}'),
('mobile', 'bootstrap', '{"logo":{"320*480":"http:\\/\\/m.cmstop.net\\/templates\\/default\\/app\\/images\\/boot\\/320x480.png","480*800":"2015\\/0324\\/1427162307922.png","640*960":"2015\\/0320\\/1426822330235.png","640*1136":"2015\\/0320\\/1426822316448.png","2048*1536":"http:\\/\\/m.cmstop.net\\/templates\\/default\\/app\\/images\\/boot\\/2048x1536.png","1536*2048":"http:\\/\\/m.cmstop.net\\/templates\\/default\\/app\\/images\\/boot\\/1536x2048.png","1536*768":"http:\\/\\/m.cmstop.net\\/templates\\/default\\/app\\/images\\/boot\\/1024x768.png","768*1024":"http:\\/\\/m.cmstop.net\\/templates\\/default\\/app\\/images\\/boot\\/768x1024.png","750*1334":"http:\\/\\/m.cmstop.net\\/templates\\/default\\/app\\/images\\/boot\\/750x1334.png","1242*2208":"http:\\/\\/m.cmstop.net\\/templates\\/default\\/app\\/images\\/boot\\/1242x2208.png"}}'),
('mobile', 'category_version', '\"1\"'),
('mobile', 'comment', '{\"open\":\"1\",\"islogin\":\"1\"}'),
('mobile', 'content_description_length', '\"100\"'),
('mobile', 'content_title_length', '\"30\"'),
('mobile', 'disclaimer', '\"<section class=\\\"ui-wrap\\\">\\n       <div class=\\\"ui-disclaimer\\\">\\n           <p>\\u5c31\\u4e0b\\u5217\\u76f8\\u5173\\u4e8b\\u5b9c\\u7684\\u53d1\\u751f\\uff0c\\u601d\\u62d3\\u65b0\\u95fb\\u4e0d\\u627f\\u62c5\\u4efb\\u4f55\\u6cd5\\u5f8b\\u8d23\\u4efb\\uff1a<\\/p>\\n          \\n          <p>a.\\u601d\\u62d3\\u65b0\\u95fb\\u6839\\u636e\\u6cd5\\u5f8b\\u89c4\\u5b9a\\u6216\\u76f8\\u5173\\u653f\\u5e9c\\u7684\\u8981\\u6c42\\u63d0\\u4f9b\\u60a8\\u7684\\u4e2a\\u4eba\\u4fe1\\u606f\\uff1b<\\/p>\\n          \\n          <p>b.\\u7531\\u4e8e\\u60a8\\u5c06\\u7528\\u6237\\u5bc6\\u7801\\u544a\\u77e5\\u4ed6\\u4eba\\u6216\\u4e0e\\u4ed6\\u4eba\\u5171\\u4eab\\u6ce8\\u518c\\u5e10\\u6237\\uff0c\\u7531\\u6b64\\u5bfc\\u81f4\\u7684\\u4efb\\u4f55\\u4e2a\\u4eba\\u4fe1\\u606f\\u7684\\u6cc4\\u6f0f\\uff0c\\u6216\\u5176\\u4ed6\\u975e\\u56e0\\u601d\\u62d3\\u65b0\\u95fb\\u539f\\u56e0\\u5bfc\\u81f4\\u7684\\u4e2a\\u4eba\\u4fe1\\u606f\\u7684\\u6cc4\\u6f0f\\uff1b<\\/p>\\n         \\n          <p>c.\\u4efb\\u4f55\\u7b2c\\u4e09\\u65b9\\u6839\\u636e\\u601d\\u62d3\\u65b0\\u95fb\\u5404\\u670d\\u52a1\\u6761\\u6b3e\\u53ca\\u58f0\\u660e\\u4e2d\\u6240\\u5217\\u660e\\u7684\\u60c5\\u51b5\\u4f7f\\u7528\\u60a8\\u7684\\u4e2a\\u4eba\\u4fe1\\u606f\\uff0c\\u7531\\u6b64\\u6240\\u4ea7\\u751f\\u7684\\u7ea0\\u7eb7\\uff1b<\\/p>\\n         \\n          <p>d.\\u4efb\\u4f55\\u7531\\u4e8e\\u9ed1\\u5ba2\\u653b\\u51fb\\u3001\\u7535\\u8111\\u75c5\\u6bd2\\u4fb5\\u5165\\u6216\\u653f\\u5e9c\\u7ba1\\u5236\\u800c\\u9020\\u6210\\u7684\\u6682\\u65f6\\u6027\\u7f51\\u7ad9\\u5173\\u95ed\\uff1b<\\/p>\\n         \\n          <p>e.\\u56e0\\u4e0d\\u53ef\\u6297\\u529b\\u5bfc\\u81f4\\u7684\\u4efb\\u4f55\\u540e\\u679c\\uff1b<\\/p>\\n            \\n          <p>f.\\u601d\\u62d3\\u65b0\\u95fb\\u5728\\u5404\\u670d\\u52a1\\u6761\\u6b3e\\u53ca\\u58f0\\u660e\\u4e2d\\u5217\\u660e\\u7684\\u4f7f\\u7528\\u65b9\\u5f0f\\u6216\\u514d\\u8d23\\u60c5\\u5f62\\u3002<\\/p>\\n      <\\/div>\\n    <\\/section>\"'),
('mobile', 'display', '{\"thumb_align\":\"right\"}'),
('mobile', 'iphone_url', '\"\"'),
('mobile', 'iphone_version', '\"\"'),
('mobile', 'iphone_version_description', '\"\"'),
('mobile', 'iphone_version_url', '\"\"'),
('mobile', 'server', '[]'),
('mobile', 'slider_default_num', '\"3\"'),
('mobile', 'square_version', '\"1\"'),
('mobile', 'style', '{\"nav\":\"#0A78CD\",\"button0\":\"#42649F\",\"button1\":\"#D38300\",\"background\":{\"320*480\":\"http:\\/\\/m.silkroad.news.cn\\/templates\\/default\\/app\\/images\\/background\\/00\\/320x480.png\",\"640*960\":\"http:\\/\\/m.silkroad.news.cn\\/templates\\/default\\/app\\/images\\/background\\/00\\/640x960.png\",\"640*1136\":\"http:\\/\\/m.silkroad.news.cn\\/templates\\/default\\/app\\/images\\/background\\/00\\/640x1136.png\",\"480*800\":\"http:\\/\\/m.silkroad.news.cn\\/templates\\/default\\/app\\/images\\/background\\/00\\/480x800.png\"},\"id\":1}'),
('mobile', 'weatherid', '\"CHBJ000000\"'),
('mobile', 'weather_background', '{\"320*480\":\"http:\\/\\/m.silkroad.news.cn\\/templates\\/default\\/app\\/images\\/background\\/00\\/320x480.png\",\"640*960\":\"http:\\/\\/m.silkroad.news.cn\\/templates\\/default\\/app\\/images\\/background\\/00\\/640x960.png\",\"640*1136\":\"http:\\/\\/m.silkroad.news.cn\\/templates\\/default\\/app\\/images\\/background\\/00\\/640x1136.png\",\"480*800\":\"http:\\/\\/m.silkroad.news.cn\\/templates\\/default\\/app\\/images\\/background\\/00\\/480x800.png\"}'),
('mobile', 'weather_version', '\"2\"'),
('mobile', 'weibo', '[]'),
('mood', 'votetime', '\"30\"'),
('page', 'section_history_retain', '\"90\"'),
('page', 'section_history_retain_unit', '\"86400\"'),
('page', 'section_log_retain', '\"90\"'),
('page', 'section_log_retain_unit', '\"86400\"'),
('paper', 'path', '\"{PSN:1}\\/paper\"'),
('picture', 'refresh', '\"0\"'),
('picture', 'source', '\"\"'),
('picture', 'thumb_height', '\"600\"'),
('picture', 'thumb_width', '\"900\"'),
('picture', 'watermark', '\"1\"'),
('picture', 'weight', '\"60\"'),
('rss', 'category', '[\"1\",\"2\",\"3\",\"4\",\"5\",\"6\"]'),
('rss', 'output', '\"digest\"'),
('rss', 'size', '\"50\"'),
('rss', 'weight', '{\"min\":60,\"max\":100}'),
('search', 'addindex', '\"addcontent\"'),
('search', 'host', '\"127.0.0.1\"'),
('search', 'mainindex', '\"content\"'),
('search', 'open', '\"1\"'),
('search', 'order', '\"rel\"'),
('search', 'port', '\"3312\"'),
('special', 'life', '\"30\"'),
('special', 'psn', '\"{PSN:2}\"'),
('survey', 'seccode', '\"1\"'),
('system', 'attachexts', '\"gif|jpg|jpeg|bmp|png|txt|zip|rar|doc|docx|xls|ppt|pdf|swf|flv|mp4\"'),
('system', 'autolock', '\"15\"'),
('system', 'baidumapkey', '\"494b3144d2e34595f0f32460c8e6f15e\"'),
('system', 'baidunews', '{\"open\":\"1\",\"url\":\"{PSN:1}\\/xml\\/baidunews.xml\",\"category\":[\"3\",\"1\",\"5\",\"2\",\"6\",\"4\"],\"article\":\"1\",\"picture\":\"1\",\"number\":\"100\",\"frequency\":\"10\",\"webname\":\"http:\\/\\/www.silkroad.news.cn\\/\",\"adminemail\":\"admin@cmstop.com\",\"updatetime\":\"10\"}'),
('system', 'ccid', '\"\"'),
('system', 'closed', '\"0\"'),
('system', 'closedreason', '\"\\u7f51\\u7ad9\\u7ef4\\u62a4\\uff0c\\u8bf7\\u7a0d\\u540e\\u8bbf\\u95ee\\u3002\"'),
('system', 'defaultwt', '\"60\"'),
('system', 'default_watermark', '\"1\"'),
('system', 'enableadminlog', '\"1\"'),
('system', 'get_tags', '\"1\"'),
('system', 'gzip', '\"1\"'),
('system', 'imgeditor', '{\"preset_sizes\":{\"width\":[\"120\",\"120\",\"120\",\"80\",\"80\",\"450\",\"640\",\"310\",\"480\",\"60\",\"200\",\"\"],\"height\":[\"90\",\"77\",\"160\",\"60\",\"80\",\"250\",\"360\",\"230\",\"270\",\"60\",\"100\",\"\"]},\"preset_ratio\":{\"width\":[\"1\",\"2\",\"1\",\"4\",\"3\",\"3\",\"\"],\"height\":[\"1\",\"1\",\"2\",\"3\",\"4\",\"2\",\"\"]}}'),
('system', 'ipaccess', '\"\"'),
('system', 'ipbanned', '\"\"'),
('system', 'listpages', '\"20\"'),
('system', 'lockedhours', '\"1\"'),
('system', 'mail', '{\"mailer\":\"1\",\"smtp_host\":\"\",\"smtp_port\":\"25\",\"smtp_auth\":\"1\",\"smtp_username\":\"\",\"smtp_password\":\"\",\"from\":\"\",\"delimiter\":\"1\",\"sign\":\"<hr \\/>\\n<a href=\\\"http:\\/\\/www.cmstop.com\\\">CmsTop<\\/a>\"}'),
('system', 'maxlistcontents', '\"1000\"'),
('system', 'maxloginfailedtimes', '\"5\"'),
('system', 'minrefreshsecond', '\"0\"'),
('system', 'onlinehold', '\"30\"'),
('system', 'pagecached', '\"0\"'),
('system', 'pagecachettl', '\"3600\"'),
('system', 'pagesize', '\"50\"'),
('system', 'pv_interval', '\"1\"'),
('system', 'repeatcheck', '\"2\"'),
('system', 'rolldays', '\"7\"'),
('system', 'seodescription', '\"CmsTop \\u662f\\u56fd\\u5185\\u9886\\u5148\\u7684\\u5a92\\u4f53\\u7f51\\u7ad9\\u5185\\u5bb9\\u7ba1\\u7406\\u7cfb\\u7edf\\uff08CMS\\uff09\\uff0c\\u4e3b\\u8981\\u670d\\u52a1\\u4e8e\\u7f51\\u7edc\\u5a92\\u4f53\\u3001\\u62a5\\u4e1a\\u3001\\u6742\\u5fd7\\u3001\\u5e7f\\u7535\\u3001\\u653f\\u5e9c\\u548c\\u5927\\u4e2d\\u578b\\u4f01\\u4e1a\\u7b49\\uff0c\\u76ee\\u524d\\u5df2\\u670d\\u52a1\\u4e86\\u8d85\\u8fc7\\u767e\\u5bb6\\u77e5\\u540d\\u5a92\\u4f53\\u7f51\\u7ad9\\u3002\"'),
('system', 'seokeywords', '\"php,cms,\\u5185\\u5bb9\\u7ba1\\u7406\\u7cfb\\u7edf,cmstop,\\u601d\\u62d3\\u5408\\u4f17,\\u65b0\\u95fb\\u53d1\\u5e03\\u7cfb\\u7edf,\\u7f51\\u7ad9\\u7ba1\\u7406\\u7cfb\\u7edf,\\u5728\\u7ebf\\u8bbf\\u8c08,\\u7535\\u5b50\\u62a5\\u7eb8,\\u7535\\u5b50\\u6742\\u5fd7,\\u95ee\\u5377\\u8c03\\u67e5\"'),
('system', 'seotitle', '\"CmsTop \\u7f51\\u7ad9\\u5185\\u5bb9\\u7ba1\\u7406\\u7cfb\\u7edf\"'),
('system', 'sina_appkey', '\"\"'),
('system', 'sina_appsecret', '\"\"'),
('system', 'sitemaps', '{\"open\":\"1\",\"url\":\"{PSN:1}xml\\/sitemap.xml\",\"category\":[\"1\",\"4\",\"5\",\"6\",\"2\",\"7\",\"8\",\"9\",\"10\",\"3\",\"11\",\"12\",\"13\",\"14\",\"15\",\"16\"],\"modelid\":[\"1\",\"2\",\"3\",\"4\",\"5\",\"7\",\"8\",\"9\",\"10\"],\"number\":\"1000\",\"frequency\":\"10\",\"categorys\":\"1,4,5,6,2,7,8,9,10,3,11,12,13\"}'),
('system', 'sitename', '\"CmsTop\"'),
('system', 'siteurl', '\"http:\\/\\/www.cmstop.com\"'),
('system', 'statcode', '\"\"'),
('system', 'tencent_appkey', '\"\"'),
('system', 'tencent_appsecret', '\"\"'),
('system', 'thumb_enabled', '\"0\"'),
('system', 'thumb_height', '\"600\"'),
('system', 'thumb_quality', '\"100\"'),
('system', 'thumb_width', '\"800\"'),
('system', 'update_setting_19', 'true'),
('system', 'watermark_enabled', '\"1\"'),
('system', 'watermark_ext', '\"png\"'),
('system', 'watermark_minheight', '\"150\"'),
('system', 'watermark_minwidth', '\"300\"'),
('system', 'watermark_position', '\"9\"'),
('system', 'watermark_quality', '\"100\"'),
('system', 'watermark_trans', '\"65\"'),
('system', 'weight', '\"60|\\u5185\\u5bb9\\u9875\\u63a8\\u8350\\n80|\\u9996\\u9875\\u63a8\\u8350\\n100|\\u9996\\u9875\\u5934\\u6761\"'),
('video', 'ads', '{\"begin\":{\"open\":\"1\",\"file\":\"http:\\/\\/doc.cmstop.com\\/images\\/pubdefault\\/ad_begin.png\",\"link\":\"http:\\/\\/www.cmstop.com\\/product\\/media_1_6\\/?begin\",\"time\":\"5\"},\"pause\":{\"open\":\"1\",\"file\":\"http:\\/\\/doc.cmstop.com\\/images\\/pubdefault\\/ad_pause.png\",\"link\":\"http:\\/\\/www.cmstop.com\\/product\\/media_1_6\\/?pause\"},\"end\":{\"open\":\"1\",\"file\":\"http:\\/\\/doc.cmstop.com\\/images\\/pubdefault\\/ad_end.png\",\"link\":\"http:\\/\\/www.cmstop.com\\/product\\/media_1_6\\/?end\",\"time\":\"5\"}}'),
('video', 'apikey', '\"\"'),
('video', 'apiurl', '\"http:\\/\\/api.media.cmstop.com\\/api.php\"'),
('video', 'filetype', '\"*.avi;*.wmv;*.wm;*.asf;*.asx;*.rm;*.rmvb;*.mpg;*.mpeg;*.vob;*.dat;*.mov;*.3pg;*.flv;*.mp4;*.m4v;\"'),
('video', 'openserver', '\"0\"'),
('video', 'player', '\"http:\\/\\/api.media.cmstop.com\\/player\\/\"'),
('video', 'third_video_tag', '\"{\\\"youku\\\":{\\\"match\\\":{\\\"feature\\\":\\\"player.youku.com\\\",\\\"catch\\\":\\\"#sid\\/(.*)\\/#i\\\"},\\\"replace\\\":[\\\"http:\\/\\/pl.youku.com\\/playlist\\/m3u8?vid=%d&type=mp4&ts=%d&keyframe=0\\\",\\\"$0\\\",\\\"time\\\"]}}\"'),
('vote', 'seccode', '\"1\"'),
('wap', 'category_pagesize', '\"6\"'),
('wap', 'catids', '[\"1\",\"2\",\"3\",\"4\",\"5\",\"6\",\"7\",\"8\",\"9\",\"10\",\"11\",\"12\",\"13\",\"14\",\"15\",\"16\"]'),
('wap', 'comment_pagesize', '\"10\"'),
('wap', 'content_expires', '\"0\"'),
('wap', 'content_words', '\"1000\"'),
('wap', 'image_height', '\"100\"'),
('wap', 'image_width', '\"100\"'),
('wap', 'index_pagesize', '\"15\"'),
('wap', 'index_weight', '\"60\"'),
('wap', 'list_pagesize', '\"15\"'),
('wap', 'list_weight', '\"60\"'),
('wap', 'logo', '\"http:\\/\\/img.cmstop.com\\/images\\/logo.jpg\"'),
('wap', 'model', '[\"1\",\"2\"]'),
('wap', 'modelids', '[\"1\",\"2\"]'),
('wap', 'open', '\"1\"'),
('wap', 'template_article', '\"wap\\/article.html\"'),
('wap', 'template_comment', '\"wap\\/comment.html\"'),
('wap', 'template_index', '\"wap\\/index.html\"'),
('wap', 'template_list', '\"wap\\/list.html\"'),
('wap', 'template_picture', '\"wap\\/picture.html\"'),
('wap', 'webname', '\"CmsTop\"');

-- --------------------------------------------------------

--
-- Table structure for table `cmstop_source`
--

CREATE TABLE IF NOT EXISTS `cmstop_source` (
  `sourceid` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `name` char(40) NOT NULL,
  `logo` char(100) DEFAULT NULL,
  `url` char(100) NOT NULL,
  `initial` char(10) NOT NULL,
  `count` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `sort` tinyint(3) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`sourceid`),
  UNIQUE KEY `name` (`name`),
  KEY `initial` (`initial`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=7 ;

--
-- Dumping data for table `cmstop_source`
--

INSERT INTO `cmstop_source` (`sourceid`, `name`, `logo`, `url`, `initial`, `count`, `sort`) VALUES
(1, '腾讯', '', 'http://www.qq.com', 'tx', 0, 0),
(2, '新浪', '', 'http://www.sina.com.cn', 'xl', 0, 0),
(3, '搜狐', '', 'http://www.sohu.com', 'sh', 0, 0),
(4, '网易', '', 'http://www.163.com', 'wy', 0, 0),
(5, '人民网', '', 'http://www.people.com.cn', 'rmw', 0, 0),
(6, '新华网', '', 'http://www.xinhuanet.com', 'xhw', 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `cmstop_space`
--

CREATE TABLE IF NOT EXISTS `cmstop_space` (
  `spaceid` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `author` varchar(40) DEFAULT NULL,
  `initial` varchar(1) NOT NULL,
  `alias` varchar(20) DEFAULT NULL,
  `photo` varchar(200) DEFAULT NULL,
  `description` varchar(255) NOT NULL,
  `userid` mediumint(8) unsigned DEFAULT NULL,
  `created` int(10) unsigned NOT NULL,
  `createdby` mediumint(8) unsigned NOT NULL,
  `modified` int(10) unsigned DEFAULT NULL,
  `modifiedby` mediumint(8) unsigned DEFAULT NULL,
  `status` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `sort` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `iseditor` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `posts` smallint(5) unsigned NOT NULL DEFAULT '0',
  `comments` smallint(5) unsigned NOT NULL DEFAULT '0',
  `pv` mediumint(8) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`spaceid`),
  UNIQUE KEY `userid` (`userid`),
  UNIQUE KEY `alias` (`alias`),
  UNIQUE KEY `author` (`author`),
  KEY `initial` (`initial`,`status`),
  KEY `status` (`status`,`pv`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `cmstop_special`
--

CREATE TABLE IF NOT EXISTS `cmstop_special` (
  `contentid` mediumint(8) unsigned NOT NULL,
  `path` varchar(255) NOT NULL,
  `description` char(255) DEFAULT NULL,
  `mode` varchar(20) NOT NULL,
  `lastupdated` int(10) unsigned DEFAULT NULL,
  `morelist_template` varchar(100) DEFAULT NULL COMMENT '更多列表模板',
  `morelist_pagesize` smallint(5) unsigned DEFAULT '50' COMMENT '更多列表分页数',
  `morelist_maxpage` mediumint(8) unsigned DEFAULT '100' COMMENT '更多列表最多显示多少页',
  PRIMARY KEY (`contentid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `cmstop_special_page`
--

CREATE TABLE IF NOT EXISTS `cmstop_special_page` (
  `pageid` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `contentid` mediumint(8) unsigned DEFAULT NULL,
  `data` longtext,
  `name` varchar(20) NOT NULL,
  `file` varchar(100) NOT NULL,
  `path` varchar(100) DEFAULT NULL,
  `url` varchar(255) NOT NULL,
  `template` varchar(100) DEFAULT NULL,
  `version` varchar(10) DEFAULT '1.0',
  `locked` int(10) unsigned DEFAULT NULL,
  `lockedby` mediumint(8) unsigned DEFAULT NULL,
  `updated` int(10) unsigned DEFAULT NULL,
  `updatedby` mediumint(8) unsigned DEFAULT NULL,
  `published` int(10) unsigned DEFAULT NULL,
  `frequency` smallint(5) unsigned DEFAULT NULL,
  `created` int(10) unsigned NOT NULL,
  `createdby` mediumint(8) unsigned NOT NULL,
  PRIMARY KEY (`pageid`),
  KEY `contentid` (`contentid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `cmstop_special_scheme`
--

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

--
-- Dumping data for table `cmstop_special_scheme`
--

INSERT INTO `cmstop_special_scheme` (`entry`, `typeid`, `name`, `thumb`, `description`, `reserved`, `created`, `createdby`, `updated`, `updatedby`) VALUES
('00000', NULL, '空白方案', 'thumb.png', NULL, 1, 4000000000, 0, NULL, NULL),
('1000000001', 1, '春节方案', 'thumb.jpg', '春节专题方案，应用时可根据网站需要自行修改', 1, 1396231787, 0, NULL, NULL),
('1000000002', 1, '上元方案', 'thumb.jpg', '上元专题，应用时可根据网站需要自行修改', 1, 1396231787, 0, NULL, NULL),
('1000000003', 1, '端午方案', 'thumb.jpg', '端午专题方案，应用时可根据网站需要自行修改', 1, 1396231787, 0, NULL, NULL),
('1000000004', 1, '中秋方案', 'thumb.jpg', '中秋专题方案，应用时可根据网站需要自行修改', 1, 1396231787, 0, NULL, NULL),
('1000000005', 2, '简版事件方案', 'thumb.jpg', '适用于突发事件，并且新闻背景资料不多的情况', 1, 1396231787, 0, NULL, NULL),
('1000000006', 2, '事件方案', 'thumb.png', '常用专题方案，可以根据事件题材选择不同的事件风格', 1, 1396231787, 0, NULL, NULL),
('1000000007', 3, '政务方案', 'thumb.png', '政治会议专题专用方案', 1, 1396231787, 0, NULL, NULL),
('1000000008', 3, '行业方案', 'thumb.jpg', '用于行业会议专题的方案', 1, 1396231787, 0, NULL, NULL),
('1000000009', 4, '评选方案', 'thumb.png', '适合优秀人物，十大事件类的专题', 1, 1396231787, 0, NULL, NULL),
('1000000010', 5, '人物方案', 'thumb.jpg', '人物专题方案，可以看做今日话题专题方案的另一种表现形式', 1, 1396231787, 0, NULL, NULL),
('1000000011', 6, '图说方案', 'thumb.jpg', '适合利用高清组图讲述一件事情的专题', 1, 1396231787, 0, NULL, NULL),
('1000000012', 6, '辩论方案', 'thumb.jpg', '辩论专题专用，特别设计了正反方辩论', 1, 1396231787, 0, NULL, NULL),
('1000000013', 6, '今日话题', 'thumb.jpg', '针对一个事件或者一个人进行深度剖析解读', 1, 1396231787, 0, NULL, NULL),
('1000000014', 7, '内容引导方案', 'thumb.jpg', '放在文章内容页，增强网站粘度，吸引用户点击', 1, 1396231787, 0, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `cmstop_special_scheme_type`
--

CREATE TABLE IF NOT EXISTS `cmstop_special_scheme_type` (
  `typeid` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `sort` smallint(5) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`typeid`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=8 ;

--
-- Dumping data for table `cmstop_special_scheme_type`
--

INSERT INTO `cmstop_special_scheme_type` (`typeid`, `name`, `sort`) VALUES
(1, '节日', 1),
(2, '事件', 2),
(3, '会议', 3),
(4, '活动', 4),
(5, '人物', 5),
(6, '话题', 6),
(7, '迷你专题', 7);

-- --------------------------------------------------------

--
-- Table structure for table `cmstop_special_template`
--

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

-- --------------------------------------------------------

--
-- Table structure for table `cmstop_spider`
--

CREATE TABLE IF NOT EXISTS `cmstop_spider` (
  `spiderid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `guid` varchar(32) NOT NULL,
  `taskid` mediumint(8) unsigned NOT NULL,
  `contentid` mediumint(8) unsigned DEFAULT NULL,
  `title` varchar(255) NOT NULL,
  `url` varchar(255) NOT NULL,
  `status` enum('spiden','viewed','new') NOT NULL DEFAULT 'new',
  `created` int(10) unsigned NOT NULL,
  `createdby` mediumint(8) unsigned NOT NULL,
  `spiden` int(10) unsigned DEFAULT NULL,
  `spidenby` mediumint(8) unsigned DEFAULT NULL,
  PRIMARY KEY (`spiderid`),
  UNIQUE KEY `guid` (`guid`),
  KEY `taskid` (`taskid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `cmstop_spider_cron_log`
--

CREATE TABLE IF NOT EXISTS `cmstop_spider_cron_log` (
  `id` int(8) unsigned NOT NULL AUTO_INCREMENT,
  `taskid` int(8) unsigned NOT NULL,
  `start_time` int(10) unsigned NOT NULL DEFAULT '0',
  `end_time` int(10) unsigned NOT NULL DEFAULT '0',
  `total` smallint(5) unsigned NOT NULL DEFAULT '0',
  `failed` smallint(5) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `taskid` (`taskid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `cmstop_spider_history`
--

CREATE TABLE IF NOT EXISTS `cmstop_spider_history` (
  `historyid` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) DEFAULT NULL,
  `url` varchar(255) NOT NULL,
  `created` int(10) NOT NULL,
  PRIMARY KEY (`historyid`),
  UNIQUE KEY `url` (`url`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `cmstop_spider_rules`
--

CREATE TABLE IF NOT EXISTS `cmstop_spider_rules` (
  `ruleid` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `guid` varchar(255) DEFAULT NULL,
  `siteid` smallint(5) unsigned NOT NULL,
  `name` varchar(100) NOT NULL,
  `author` varchar(100) DEFAULT NULL,
  `version` int(10) DEFAULT NULL,
  `charset` varchar(10) NOT NULL,
  `enter_rule` varchar(255) NOT NULL,
  `list_rule` text,
  `content_rule` text,
  `description` text,
  `created` int(10) unsigned NOT NULL,
  `createdby` mediumint(8) unsigned NOT NULL,
  `updated` int(10) unsigned DEFAULT NULL,
  `updatedby` mediumint(8) unsigned DEFAULT NULL,
  PRIMARY KEY (`ruleid`),
  KEY `siteid` (`siteid`),
  KEY `guid` (`guid`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=6 ;

--
-- Dumping data for table `cmstop_spider_rules`
--

INSERT INTO `cmstop_spider_rules` (`ruleid`, `guid`, `siteid`, `name`, `author`, `version`, `charset`, `enter_rule`, `list_rule`, `content_rule`, `description`, `created`, `createdby`, `updated`, `updatedby`) VALUES
(1, NULL, 1, '网易-新闻', NULL, NULL, 'GBK', 'http://news.163.com/(*)', 'a:3:{s:9:"listStart";s:0:"";s:7:"listEnd";s:0:"";s:10:"urlPattern";s:40:"http://news.163.com/(*)/(*)/(*)/(*).html";}', 'a:16:{s:10:"rangeStart";s:22:"<div class="endContent";s:8:"rangeEnd";s:21:"<div class="endMore">";s:10:"titleStart";s:17:"<h1 id="h1title">";s:8:"titleEnd";s:5:"</h1>";s:12:"contentStart";s:18:"<div id="endText">";s:10:"contentEnd";s:15:"<!-- 分页 -->";s:9:"pageStart";s:24:"<div class="endPageNum">";s:7:"pageEnd";s:8:"</table>";s:11:"authorStart";s:0:"";s:9:"authorEnd";s:0:"";s:11:"sourceStart";s:0:"";s:9:"sourceEnd";s:0:"";s:12:"pubdateStart";s:19:"<span class="info">";s:10:"pubdateEnd";s:10:"　来源:";s:9:"allowTags";s:24:"a,b,p,br,img,span,strong";s:11:"replacement";a:2:{s:6:"source";a:1:{i:0;s:0:"";}s:6:"target";a:1:{i:0;s:0:"";}}}', '国内新闻', 1268643397, 8, 1269016827, 381),
(2, NULL, 1, '网易-娱乐', NULL, NULL, 'GBK', 'http://ent.163.com/(*)', 'a:3:{s:9:"listStart";s:0:"";s:7:"listEnd";s:0:"";s:10:"urlPattern";s:39:"http://ent.163.com/(*)/(*)/(*)/(*).html";}', 'a:16:{s:10:"rangeStart";s:22:"<div class="endContent";s:8:"rangeEnd";s:21:"<div class="endMore">";s:10:"titleStart";s:17:"<h1 id="h1title">";s:8:"titleEnd";s:5:"</h1>";s:12:"contentStart";s:18:"<div id="endText">";s:10:"contentEnd";s:15:"<!-- 分页 -->";s:9:"pageStart";s:24:"<div class="endPageNum">";s:7:"pageEnd";s:8:"</table>";s:11:"authorStart";s:0:"";s:9:"authorEnd";s:0:"";s:11:"sourceStart";s:0:"";s:9:"sourceEnd";s:0:"";s:12:"pubdateStart";s:19:"<span class="info">";s:10:"pubdateEnd";s:10:"　来源:";s:9:"allowTags";s:24:"a,b,p,br,img,span,strong";s:11:"replacement";a:2:{s:6:"source";a:1:{i:0;s:0:"";}s:6:"target";a:1:{i:0;s:0:"";}}}', '网易娱乐', 1268644411, 8, 1268648591, 8),
(3, NULL, 1, '网易-科技-互联网', NULL, NULL, 'GBK', 'http://tech.163.com/', 'a:3:{s:9:"listStart";s:33:"alt="互联网要闻" /></a></h2>";s:7:"listEnd";s:29:"<span class="blank12"></span>";s:10:"urlPattern";s:40:"http://tech.163.com/(*)/(*)/(*)/(*).html";}', 'a:16:{s:10:"rangeStart";s:22:"<div class="endContent";s:8:"rangeEnd";s:21:"<div class="endMore">";s:10:"titleStart";s:17:"<h1 id="h1title">";s:8:"titleEnd";s:5:"</h1>";s:12:"contentStart";s:18:"<div id="endText">";s:10:"contentEnd";s:15:"<!-- 分页 -->";s:9:"pageStart";s:24:"<div class="endPageNum">";s:7:"pageEnd";s:8:"</table>";s:11:"authorStart";s:0:"";s:9:"authorEnd";s:0:"";s:11:"sourceStart";s:0:"";s:9:"sourceEnd";s:0:"";s:12:"pubdateStart";s:19:"<span class="info">";s:10:"pubdateEnd";s:10:"　来源:";s:9:"allowTags";s:24:"a,b,p,br,img,span,strong";s:11:"replacement";a:2:{s:6:"source";a:1:{i:0;s:0:"";}s:6:"target";a:1:{i:0;s:0:"";}}}', '网易-科技-互联网', 1268646653, 8, 1269002483, 285),
(4, NULL, 1, '网易-科技-通信', NULL, NULL, 'GBK', 'http://tech.163.com/', 'a:3:{s:9:"listStart";s:74:"<span class="exp"><a href="http://tech.163.com/telecom/">更多</a></span>";s:7:"listEnd";s:29:"<span class="blank12"></span>";s:10:"urlPattern";s:40:"http://tech.163.com/(*)/(*)/(*)/(*).html";}', 'a:16:{s:10:"rangeStart";s:22:"<div class="endContent";s:8:"rangeEnd";s:21:"<div class="endMore">";s:10:"titleStart";s:17:"<h1 id="h1title">";s:8:"titleEnd";s:5:"</h1>";s:12:"contentStart";s:18:"<div id="endText">";s:10:"contentEnd";s:15:"<!-- 分页 -->";s:9:"pageStart";s:24:"<div class="endPageNum">";s:7:"pageEnd";s:8:"</table>";s:11:"authorStart";s:0:"";s:9:"authorEnd";s:0:"";s:11:"sourceStart";s:0:"";s:9:"sourceEnd";s:0:"";s:12:"pubdateStart";s:19:"<span class="info">";s:10:"pubdateEnd";s:10:"　来源:";s:9:"allowTags";s:24:"a,b,p,br,img,span,strong";s:11:"replacement";a:2:{s:6:"source";a:1:{i:0;s:0:"";}s:6:"target";a:1:{i:0;s:0:"";}}}', '网易-科技-通信', 1268647040, 8, 1268648575, 8),
(5, NULL, 1, '网易-科技-业界', NULL, NULL, 'UTF-8', 'http://tech.163.com/', 'a:7:{s:9:"listStart";s:69:"<span class="exp"><a href="http://tech.163.com/it/">更多</a></span>";s:7:"listEnd";s:13:"<!--colM.e-->";s:8:"listType";s:1:"0";s:7:"listUrl";s:0:"";s:10:"urlPattern";s:40:"http://tech.163.com/(*)/(*)/(*)/(*).html";s:12:"listNextPage";s:0:"";s:15:"listLimitLength";i:0;}', 'a:17:{s:10:"contentUrl";s:0:"";s:10:"rangeStart";s:22:"<div class="endContent";s:8:"rangeEnd";s:21:"<div class="endMore">";s:10:"titleStart";s:17:"<h1 id="h1title">";s:8:"titleEnd";s:5:"</h1>";s:12:"contentStart";s:18:"<div id="endText">";s:10:"contentEnd";s:15:"<!-- 分页 -->";s:11:"authorStart";s:0:"";s:9:"authorEnd";s:0:"";s:11:"sourceStart";s:0:"";s:9:"sourceEnd";s:0:"";s:12:"pubdateStart";s:19:"<span class="info">";s:10:"pubdateEnd";s:10:"　来源:";s:8:"nextPage";s:0:"";s:9:"allowTags";s:24:"a,b,p,br,img,span,strong";s:13:"saveRemoteImg";b:0;s:11:"replacement";a:2:{s:6:"source";a:1:{i:0;s:0:"";}s:6:"target";a:1:{i:0;s:0:"";}}}', '网易-科技-业界', 1268647389, 8, 1289534891, 5);

-- --------------------------------------------------------

--
-- Table structure for table `cmstop_spider_site`
--

CREATE TABLE IF NOT EXISTS `cmstop_spider_site` (
  `siteid` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  PRIMARY KEY (`siteid`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `cmstop_spider_site`
--

INSERT INTO `cmstop_spider_site` (`siteid`, `name`) VALUES
(1, '网易');

-- --------------------------------------------------------

--
-- Table structure for table `cmstop_spider_task`
--

CREATE TABLE IF NOT EXISTS `cmstop_spider_task` (
  `taskid` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `ruleid` mediumint(8) unsigned NOT NULL,
  `catid` smallint(5) unsigned NOT NULL,
  `title` varchar(255) NOT NULL,
  `url` varchar(255) NOT NULL,
  `frequency` smallint(5) unsigned DEFAULT NULL,
  `created` int(10) unsigned NOT NULL,
  `createdby` mediumint(8) unsigned NOT NULL,
  `updated` int(10) unsigned DEFAULT NULL,
  `updatedby` mediumint(8) unsigned DEFAULT NULL,
  `titlecheck` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `cron` enum('0','1') NOT NULL DEFAULT '0',
  `cron_frequency` smallint(5) unsigned NOT NULL DEFAULT '3600',
  `cron_count` smallint(5) unsigned NOT NULL DEFAULT '0',
  `cron_status` enum('1','3','6') NOT NULL DEFAULT '3',
  `cron_next` int(10) unsigned NOT NULL DEFAULT '0',
  `cron_last` int(10) unsigned NOT NULL,
  `titletags` varchar(255) DEFAULT NULL,
  `nottitletags` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`taskid`),
  KEY `ruleid` (`ruleid`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=11 ;

--
-- Dumping data for table `cmstop_spider_task`
--

INSERT INTO `cmstop_spider_task` (`taskid`, `ruleid`, `catid`, `title`, `url`, `frequency`, `created`, `createdby`, `updated`, `updatedby`, `titlecheck`, `cron`, `cron_frequency`, `cron_count`, `cron_status`, `cron_next`, `cron_last`, `titletags`, `nottitletags`) VALUES
(1, 1, 9, '网易-新闻-国内', 'http://news.163.com/domestic/', 0, 1268643397, 8, 0, 1, 0, '0', 3600, 0, '3', 0, 0, NULL, NULL),
(2, 1, 5, '网易-新闻-国际', 'http://news.163.com/world/', 0, 1268643909, 8, NULL, NULL, 0, '0', 3600, 0, '3', 0, 0, NULL, NULL),
(3, 1, 6, '网易-新闻-社会', 'http://news.163.com/shehui/', 0, 1268643978, 8, NULL, NULL, 0, '0', 3600, 0, '3', 0, 0, NULL, NULL),
(4, 2, 7, '网易-娱乐-明星', 'http://ent.163.com/star/', 0, 1268644411, 8, NULL, NULL, 0, '0', 3600, 0, '3', 0, 0, NULL, NULL),
(5, 2, 8, '网易-娱乐-电影', 'http://ent.163.com/movie/', 0, 1268644531, 8, NULL, NULL, 0, '0', 3600, 0, '3', 0, 0, NULL, NULL),
(6, 2, 9, '网易-娱乐-电视', 'http://ent.163.com/tv/', 0, 1268644584, 8, NULL, NULL, 0, '0', 3600, 0, '3', 0, 0, NULL, NULL),
(7, 2, 10, '网易-娱乐-音乐', 'http://ent.163.com/music/', 0, 1268644630, 8, NULL, NULL, 0, '0', 3600, 0, '3', 0, 0, NULL, NULL),
(8, 3, 11, '网易-科技-互联网', 'http://tech.163.com/', 0, 1268646653, 8, NULL, NULL, 0, '0', 3600, 0, '3', 0, 0, NULL, NULL),
(9, 4, 12, '网易-科技-通信', 'http://tech.163.com/', 0, 1268647040, 8, NULL, NULL, 0, '0', 3600, 0, '3', 0, 0, NULL, NULL),
(10, 5, 13, '网易-科技-业界', 'http://tech.163.com/', 0, 1268647389, 8, NULL, NULL, 0, '0', 3600, 0, '3', 0, 0, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `cmstop_status`
--

CREATE TABLE IF NOT EXISTS `cmstop_status` (
  `status` tinyint(3) unsigned NOT NULL,
  `name` varchar(50) NOT NULL,
  `description` varchar(255) NOT NULL,
  PRIMARY KEY (`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `cmstop_status`
--

INSERT INTO `cmstop_status` (`status`, `name`, `description`) VALUES
(0, '回收站', '回收站'),
(1, '草稿', '草稿'),
(2, '退稿', '已退稿'),
(3, '待审', '待审核'),
(4, '已撤', '已撤稿'),
(5, '待发', '定时发布'),
(6, '已发', '已发布');

-- --------------------------------------------------------

--
-- Table structure for table `cmstop_survey`
--

CREATE TABLE IF NOT EXISTS `cmstop_survey` (
  `contentid` mediumint(8) unsigned NOT NULL,
  `description` mediumtext,
  `starttime` int(10) unsigned DEFAULT NULL,
  `endtime` int(10) unsigned DEFAULT NULL,
  `maxanswers` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `minhours` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `checklogined` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `mailto` varchar(40) DEFAULT NULL,
  `template` varchar(100) DEFAULT NULL,
  `questions` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `answers` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `bgimg` varchar(255) DEFAULT '',
  PRIMARY KEY (`contentid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `cmstop_survey_answer`
--

CREATE TABLE IF NOT EXISTS `cmstop_survey_answer` (
  `answerid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `contentid` mediumint(8) unsigned NOT NULL,
  `created` int(10) unsigned NOT NULL,
  `createdby` mediumint(8) unsigned DEFAULT NULL,
  `ip` char(15) NOT NULL,
  PRIMARY KEY (`answerid`),
  KEY `contentid` (`contentid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `cmstop_survey_answer_option`
--

CREATE TABLE IF NOT EXISTS `cmstop_survey_answer_option` (
  `answerid` int(10) unsigned NOT NULL,
  `questionid` mediumint(8) unsigned NOT NULL,
  `optionid` mediumint(8) unsigned NOT NULL,
  KEY `answerid` (`answerid`),
  KEY `optionid` (`optionid`),
  KEY `questionid` (`questionid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `cmstop_survey_answer_record`
--

CREATE TABLE IF NOT EXISTS `cmstop_survey_answer_record` (
  `answerid` int(10) unsigned NOT NULL,
  `questionid` mediumint(8) unsigned NOT NULL,
  `content` text,
  KEY `answerid` (`answerid`),
  KEY `questionid` (`questionid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `cmstop_survey_question`
--

CREATE TABLE IF NOT EXISTS `cmstop_survey_question` (
  `questionid` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `contentid` mediumint(8) unsigned NOT NULL,
  `subject` varchar(255) NOT NULL,
  `description` varchar(255) DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `type` enum('radio','checkbox','select','text','textarea','hr','file') NOT NULL,
  `width` smallint(5) unsigned NOT NULL DEFAULT '0',
  `height` smallint(5) unsigned NOT NULL DEFAULT '0',
  `maxlength` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `validator` varchar(20) DEFAULT NULL,
  `required` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `minoptions` tinyint(1) unsigned NOT NULL,
  `maxoptions` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `allowfill` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `sort` tinyint(2) unsigned NOT NULL DEFAULT '0',
  `votes` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `records` mediumint(8) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`questionid`),
  KEY `contentid` (`contentid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `cmstop_survey_question_option`
--

CREATE TABLE IF NOT EXISTS `cmstop_survey_question_option` (
  `optionid` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `questionid` mediumint(8) unsigned NOT NULL,
  `name` varchar(255) NOT NULL,
  `image` varchar(255) DEFAULT NULL,
  `isfill` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `sort` tinyint(2) unsigned NOT NULL DEFAULT '0',
  `votes` mediumint(8) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`optionid`),
  KEY `questionid` (`questionid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `cmstop_tag`
--

CREATE TABLE IF NOT EXISTS `cmstop_tag` (
  `tagid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `tag` varchar(255) NOT NULL,
  `initial` char(1) NOT NULL,
  `style` char(7) NOT NULL,
  `usetimes` smallint(5) unsigned NOT NULL DEFAULT '0',
  `pv` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `sort` tinyint(3) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`tagid`),
  UNIQUE KEY `tag` (`tag`),
  KEY `initial` (`initial`),
  KEY `sort` (`sort`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `cmstop_template_clip`
--

CREATE TABLE IF NOT EXISTS `cmstop_template_clip` (
  `clipid` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `code` text,
  `created` int(10) unsigned NOT NULL,
  `createdby` mediumint(8) unsigned NOT NULL,
  PRIMARY KEY (`clipid`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT AUTO_INCREMENT=9 ;

--
-- Dumping data for table `cmstop_template_clip`
--

INSERT INTO `cmstop_template_clip` (`clipid`, `name`, `code`, `created`, `createdby`) VALUES
(1, '栏目最新内容（10条）', '<ul>\r\n<!--{content catid="1" orderby="published desc" size="10"}-->\r\n<li><a href="{$r[url]}">{$r[title]}</a></li>\r\n<!--{/content}-->\r\n</ul>', 1273008465, 5),
(2, '栏目最新文章（10条）', '<ul>\r\n<!--{content modelid="1" catid="1" orderby="published desc" size="10"}-->\r\n<li><a href="{$r[url]}">{$r[title]}</a></li>\r\n<!--{/content}-->\r\n</ul>', 1273008580, 5),
(3, '栏目最新组图', '<ul>\n<!--{content modelid="2" catid="1" orderby="published desc" size="10"}-->\n<li><a href="{$r[url]}"><img src="{thumb($r[thumb], 100, 100)}" /></a><h3><a href="{$r[url]}">{$r[title]}</a></h3></li>\n<!--{/content}-->\n</ul>', 1273009594, 5),
(5, '本周文章点击排行', '<ul>\r\n<!--{content modelid="1" published="7" orderby="pv desc" size="10"}-->\r\n<li><a href="{$r[url]}">{$r[title]}</a></li>\r\n<!--{/content}-->\r\n</ul>', 1273009900, 5),
(6, '本周文章评论排行', '<ul>\n<!--{content modelid="1" published="7" orderby="comments desc" size="10"}-->\n<li><a href="{$r[url]}">{$r[title]}</a></li>\n<!--{/content}-->\n</ul>', 1273009986, 5),
(7, '关于“cmstop”的内容', '<ul>\n<!--{content tags="cmstop" orderby="published desc" size="10"}-->\n<li><a href="{$r[url]}">{$r[title]}</a></li>\n<!--{/content}-->\n</ul>', 1273010197, 5),
(8, '子栏目循环调用', '<ul>\n<!--{loop subcategory(1) $cid $c}-->\n<li><a href="{$c[url]}">{$c[name]}</a></li>\n<!--{/loop}-->\n</ul>', 1273010465, 5);

-- --------------------------------------------------------

--
-- Table structure for table `cmstop_tweets`
--

CREATE TABLE IF NOT EXISTS `cmstop_tweets` (
  `tweetid` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(80) NOT NULL DEFAULT '',
  `driver` enum('qq','renren','kaixin','sina') NOT NULL,
  `username` varchar(80) NOT NULL DEFAULT '',
  `password` varchar(32) NOT NULL DEFAULT '',
  `key` varchar(32) DEFAULT NULL,
  `secret` varchar(32) DEFAULT NULL,
  `sina_key` char(32) DEFAULT NULL,
  `sina_secret` char(32) DEFAULT NULL,
  `status` tinyint(3) NOT NULL DEFAULT '0',
  `updated` int(10) NOT NULL,
  `created` int(10) NOT NULL,
  PRIMARY KEY (`tweetid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `cmstop_video`
--

CREATE TABLE IF NOT EXISTS `cmstop_video` (
  `contentid` mediumint(8) unsigned NOT NULL,
  `aid` int(10) unsigned DEFAULT NULL,
  `video` varchar(255) NOT NULL,
  `playtime` smallint(5) unsigned DEFAULT NULL,
  `description` mediumtext,
  `editor` varchar(15) DEFAULT NULL,
  `listid` mediumint(8) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`contentid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `cmstop_videolist`
--

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `cmstop_videolist_data`
--

CREATE TABLE IF NOT EXISTS `cmstop_videolist_data` (
  `contentid` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `listid` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `sort` mediumint(8) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`contentid`),
  KEY `listid` (`listid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `cmstop_video_56`
--

CREATE TABLE IF NOT EXISTS `cmstop_video_56` (
  `id` int(8) unsigned NOT NULL AUTO_INCREMENT,
  `contentid` int(10) unsigned NOT NULL,
  `vid` varchar(32) NOT NULL,
  `state` tinyint(1) unsigned NOT NULL,
  `time` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `contentid` (`contentid`),
  KEY `state` (`state`,`time`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `cmstop_video_thirdparty`
--

CREATE TABLE IF NOT EXISTS `cmstop_video_thirdparty` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(50) DEFAULT '',
  `authkey` char(32) DEFAULT '',
  `apiurl` varchar(200) DEFAULT '',
  `apitype` varchar(20) DEFAULT '',
  `status` tinyint(1) unsigned DEFAULT '0',
  `sort` tinyint(4) unsigned DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `cmstop_vote`
--

CREATE TABLE IF NOT EXISTS `cmstop_vote` (
  `contentid` mediumint(8) unsigned NOT NULL,
  `type` enum('radio','checkbox') NOT NULL DEFAULT 'radio',
  `description` varchar(255) DEFAULT NULL,
  `starttime` int(10) unsigned DEFAULT NULL,
  `endtime` int(10) unsigned DEFAULT NULL,
  `maxoptions` tinyint(2) unsigned NOT NULL DEFAULT '0',
  `maxvotes` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `mininterval` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `total` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `display` varchar(15) NOT NULL DEFAULT 'list',
  `thumb_width` smallint(5) unsigned NOT NULL DEFAULT '0',
  `thumb_height` smallint(5) unsigned NOT NULL DEFAULT '0',
  `bgimg` varchar(255) DEFAULT NULL,
  `arealimit` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `seccode_type` enum('normal','advanced') NOT NULL default 'normal',
  PRIMARY KEY (`contentid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `cmstop_vote_log`
--

CREATE TABLE IF NOT EXISTS `cmstop_vote_log` (
  `logid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `contentid` mediumint(8) unsigned NOT NULL,
  `created` int(10) unsigned NOT NULL,
  `createdby` mediumint(8) unsigned DEFAULT NULL,
  `ip` varchar(15) NOT NULL,
  PRIMARY KEY (`logid`),
  KEY `createdby` (`createdby`),
  KEY `contentid` (`contentid`,`ip`,`created`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `cmstop_vote_log_data`
--

CREATE TABLE IF NOT EXISTS `cmstop_vote_log_data` (
  `dataid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `logid` int(10) unsigned NOT NULL,
  `optionid` int(10) unsigned NOT NULL,
  PRIMARY KEY (`dataid`),
  KEY `logid` (`logid`),
  KEY `optionid` (`optionid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `cmstop_vote_option`
--

CREATE TABLE IF NOT EXISTS `cmstop_vote_option` (
  `optionid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `contentid` mediumint(8) unsigned NOT NULL,
  `name` varchar(100) NOT NULL,
  `link` varchar(255) DEFAULT NULL,
  `thumb` varchar(255) DEFAULT NULL,
  `sort` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `votes` mediumint(8) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`optionid`),
  KEY `contentid` (`contentid`,`sort`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `cmstop_watermark`
--

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
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `cmstop_watermark`
--

INSERT INTO `cmstop_watermark` (`watermarkid`, `name`, `image`, `ext`, `minwidth`, `minheight`, `position`, `trans`, `quality`, `disable`) VALUES
(1, '站点水印', 'watermark/cmstop.png', 'png', 300, 300, 9, 80, 100, 0);

-- --------------------------------------------------------

--
-- Table structure for table `cmstop_weather_city`
--

CREATE TABLE IF NOT EXISTS `cmstop_weather_city` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `province_id` varchar(2) NOT NULL DEFAULT '',
  `province` varchar(10) NOT NULL DEFAULT '',
  `town_id` varchar(4) NOT NULL DEFAULT '',
  `town` varchar(20) NOT NULL DEFAULT '',
  `city_id` varchar(6) NOT NULL DEFAULT '',
  `city` varchar(20) NOT NULL DEFAULT '',
  `weather_id` int(10) unsigned NOT NULL DEFAULT '0',
  `town_initial` varchar(2) NOT NULL DEFAULT '',
  `city_initial` varchar(2) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  UNIQUE KEY `weather_id` (`weather_id`),
  KEY `town_id` (`town_id`),
  KEY `town_initial` (`town_initial`),
  KEY `city_initial` (`city_initial`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2565 ;

--
-- Dumping data for table `cmstop_weather_city`
--

INSERT INTO `cmstop_weather_city` (`id`, `province_id`, `province`, `town_id`, `town`, `city_id`, `city`, `weather_id`, `town_initial`, `city_initial`) VALUES
(1, '01', '北京', '0101', '北京', '010101', '北京', 101010100, 'b', 'b'),
(2, '01', '北京', '0101', '北京', '010102', '海淀', 101010200, 'b', 'h'),
(3, '01', '北京', '0101', '北京', '010103', '朝阳', 101010300, 'b', 'c'),
(4, '01', '北京', '0101', '北京', '010104', '顺义', 101010400, 'b', 's'),
(5, '01', '北京', '0101', '北京', '010105', '怀柔', 101010500, 'b', 'h'),
(6, '01', '北京', '0101', '北京', '010106', '通州', 101010600, 'b', 't'),
(7, '01', '北京', '0101', '北京', '010107', '昌平', 101010700, 'b', 'c'),
(8, '01', '北京', '0101', '北京', '010108', '延庆', 101010800, 'b', 'y'),
(9, '01', '北京', '0101', '北京', '010109', '丰台', 101010900, 'b', 'f'),
(10, '01', '北京', '0101', '北京', '010110', '石景山', 101011000, 'b', 's'),
(11, '01', '北京', '0101', '北京', '010111', '大兴', 101011100, 'b', 'd'),
(12, '01', '北京', '0101', '北京', '010112', '房山', 101011200, 'b', 'f'),
(13, '01', '北京', '0101', '北京', '010113', '密云', 101011300, 'b', 'm'),
(14, '01', '北京', '0101', '北京', '010114', '门头沟', 101011400, 'b', 'm'),
(15, '01', '北京', '0101', '北京', '010115', '平谷', 101011500, 'b', 'p'),
(16, '02', '上海', '0201', '上海', '020101', '上海', 101020100, 's', 's'),
(17, '02', '上海', '0201', '上海', '020102', '闵行', 101020200, 's', 'm'),
(18, '02', '上海', '0201', '上海', '020103', '宝山', 101020300, 's', 'b'),
(19, '02', '上海', '0201', '上海', '020104', '嘉定', 101020500, 's', 'j'),
(20, '02', '上海', '0201', '上海', '020105', '浦东南汇', 101020600, 's', 'p'),
(21, '02', '上海', '0201', '上海', '020106', '金山', 101020700, 's', 'j'),
(22, '02', '上海', '0201', '上海', '020107', '青浦', 101020800, 's', 'q'),
(23, '02', '上海', '0201', '上海', '020108', '松江', 101020900, 's', 's'),
(24, '02', '上海', '0201', '上海', '020109', '奉贤', 101021000, 's', 'f'),
(25, '02', '上海', '0201', '上海', '020110', '崇明', 101021100, 's', 'c'),
(26, '02', '上海', '0201', '上海', '020111', '徐家汇', 101021200, 's', 'x'),
(27, '02', '上海', '0201', '上海', '020112', '浦东', 101021300, 's', 'p'),
(28, '03', '天津', '0301', '天津', '030101', '天津', 101030100, 't', 't'),
(29, '03', '天津', '0301', '天津', '030102', '武清', 101030200, 't', 'w'),
(30, '03', '天津', '0301', '天津', '030103', '宝坻', 101030300, 't', 'b'),
(31, '03', '天津', '0301', '天津', '030104', '东丽', 101030400, 't', 'd'),
(32, '03', '天津', '0301', '天津', '030105', '西青', 101030500, 't', 'x'),
(33, '03', '天津', '0301', '天津', '030106', '北辰', 101030600, 't', 'b'),
(34, '03', '天津', '0301', '天津', '030107', '宁河', 101030700, 't', 'n'),
(35, '03', '天津', '0301', '天津', '030108', '汉沽', 101030800, 't', 'h'),
(36, '03', '天津', '0301', '天津', '030109', '静海', 101030900, 't', 'j'),
(37, '03', '天津', '0301', '天津', '030110', '津南', 101031000, 't', 'j'),
(38, '03', '天津', '0301', '天津', '030111', '塘沽', 101031100, 't', 't'),
(39, '03', '天津', '0301', '天津', '030112', '大港', 101031200, 't', 'd'),
(40, '03', '天津', '0301', '天津', '030113', '蓟县', 101031400, 't', 'j'),
(41, '04', '重庆', '0401', '重庆', '040101', '重庆', 101040100, 'z', 'z'),
(42, '04', '重庆', '0401', '重庆', '040102', '永川', 101040200, 'z', 'y'),
(43, '04', '重庆', '0401', '重庆', '040103', '合川', 101040300, 'z', 'h'),
(44, '04', '重庆', '0401', '重庆', '040104', '南川', 101040400, 'z', 'n'),
(45, '04', '重庆', '0401', '重庆', '040105', '江津', 101040500, 'z', 'j'),
(46, '04', '重庆', '0401', '重庆', '040106', '万盛', 101040600, 'z', 'w'),
(47, '04', '重庆', '0401', '重庆', '040107', '渝北', 101040700, 'z', 'y'),
(48, '04', '重庆', '0401', '重庆', '040108', '北碚', 101040800, 'z', 'b'),
(49, '04', '重庆', '0401', '重庆', '040109', '巴南', 101040900, 'z', 'b'),
(50, '04', '重庆', '0401', '重庆', '040110', '长寿', 101041000, 'z', 'c'),
(51, '04', '重庆', '0401', '重庆', '040111', '黔江', 101041100, 'z', 'q'),
(52, '04', '重庆', '0401', '重庆', '040112', '万州', 101041300, 'z', 'w'),
(53, '04', '重庆', '0401', '重庆', '040113', '涪陵', 101041400, 'z', 'f'),
(54, '04', '重庆', '0401', '重庆', '040114', '开县', 101041500, 'z', 'k'),
(55, '04', '重庆', '0401', '重庆', '040115', '城口', 101041600, 'z', 'c'),
(56, '04', '重庆', '0401', '重庆', '040116', '云阳', 101041700, 'z', 'y'),
(57, '04', '重庆', '0401', '重庆', '040117', '巫溪', 101041800, 'z', 'w'),
(58, '04', '重庆', '0401', '重庆', '040118', '奉节', 101041900, 'z', 'f'),
(59, '04', '重庆', '0401', '重庆', '040119', '巫山', 101042000, 'z', 'w'),
(60, '04', '重庆', '0401', '重庆', '040120', '潼南', 101042100, 'z', 't'),
(61, '04', '重庆', '0401', '重庆', '040121', '垫江', 101042200, 'z', 'd'),
(62, '04', '重庆', '0401', '重庆', '040122', '梁平', 101042300, 'z', 'l'),
(63, '04', '重庆', '0401', '重庆', '040123', '忠县', 101042400, 'z', 'z'),
(64, '04', '重庆', '0401', '重庆', '040124', '石柱', 101042500, 'z', 's'),
(65, '04', '重庆', '0401', '重庆', '040125', '大足', 101042600, 'z', 'd'),
(66, '04', '重庆', '0401', '重庆', '040126', '荣昌', 101042700, 'z', 'r'),
(67, '04', '重庆', '0401', '重庆', '040127', '铜梁', 101042800, 'z', 't'),
(68, '04', '重庆', '0401', '重庆', '040128', '璧山', 101042900, 'z', 'b'),
(69, '04', '重庆', '0401', '重庆', '040129', '丰都', 101043000, 'z', 'f'),
(70, '04', '重庆', '0401', '重庆', '040130', '武隆', 101043100, 'z', 'w'),
(71, '04', '重庆', '0401', '重庆', '040131', '彭水', 101043200, 'z', 'p'),
(72, '04', '重庆', '0401', '重庆', '040132', '綦江', 101043300, 'z', 'q'),
(73, '04', '重庆', '0401', '重庆', '040133', '酉阳', 101043400, 'z', 'y'),
(74, '04', '重庆', '0401', '重庆', '040134', '秀山', 101043600, 'z', 'x'),
(75, '05', '黑龙江', '0501', '哈尔滨', '050101', '哈尔滨', 101050101, 'h', 'h'),
(76, '05', '黑龙江', '0501', '哈尔滨', '050102', '双城', 101050102, 'h', 's'),
(77, '05', '黑龙江', '0501', '哈尔滨', '050103', '呼兰', 101050103, 'h', 'h'),
(78, '05', '黑龙江', '0501', '哈尔滨', '050104', '阿城', 101050104, 'h', 'a'),
(79, '05', '黑龙江', '0501', '哈尔滨', '050105', '宾县', 101050105, 'h', 'b'),
(80, '05', '黑龙江', '0501', '哈尔滨', '050106', '依兰', 101050106, 'h', 'y'),
(81, '05', '黑龙江', '0501', '哈尔滨', '050107', '巴彦', 101050107, 'h', 'b'),
(82, '05', '黑龙江', '0501', '哈尔滨', '050108', '通河', 101050108, 'h', 't'),
(83, '05', '黑龙江', '0501', '哈尔滨', '050109', '方正', 101050109, 'h', 'f'),
(84, '05', '黑龙江', '0501', '哈尔滨', '050110', '延寿', 101050110, 'h', 'y'),
(85, '05', '黑龙江', '0501', '哈尔滨', '050111', '尚志', 101050111, 'h', 's'),
(86, '05', '黑龙江', '0501', '哈尔滨', '050112', '五常', 101050112, 'h', 'w'),
(87, '05', '黑龙江', '0501', '哈尔滨', '050113', '木兰', 101050113, 'h', 'm'),
(88, '05', '黑龙江', '0502', '齐齐哈尔', '050201', '齐齐哈尔', 101050201, 'q', 'q'),
(89, '05', '黑龙江', '0502', '齐齐哈尔', '050202', '讷河', 101050202, 'q', 'n'),
(90, '05', '黑龙江', '0502', '齐齐哈尔', '050203', '龙江', 101050203, 'q', 'l'),
(91, '05', '黑龙江', '0502', '齐齐哈尔', '050204', '甘南', 101050204, 'q', 'g'),
(92, '05', '黑龙江', '0502', '齐齐哈尔', '050205', '富裕', 101050205, 'q', 'f'),
(93, '05', '黑龙江', '0502', '齐齐哈尔', '050206', '依安', 101050206, 'q', 'y'),
(94, '05', '黑龙江', '0502', '齐齐哈尔', '050207', '拜泉', 101050207, 'q', 'b'),
(95, '05', '黑龙江', '0502', '齐齐哈尔', '050208', '克山', 101050208, 'q', 'k'),
(96, '05', '黑龙江', '0502', '齐齐哈尔', '050209', '克东', 101050209, 'q', 'k'),
(97, '05', '黑龙江', '0502', '齐齐哈尔', '050210', '泰来', 101050210, 'q', 't'),
(98, '05', '黑龙江', '0503', '牡丹江', '050301', '牡丹江', 101050301, 'm', 'm'),
(99, '05', '黑龙江', '0503', '牡丹江', '050302', '海林', 101050302, 'm', 'h'),
(100, '05', '黑龙江', '0503', '牡丹江', '050303', '穆棱', 101050303, 'm', 'm'),
(101, '05', '黑龙江', '0503', '牡丹江', '050304', '林口', 101050304, 'm', 'l'),
(102, '05', '黑龙江', '0503', '牡丹江', '050305', '绥芬河', 101050305, 'm', 's'),
(103, '05', '黑龙江', '0503', '牡丹江', '050306', '宁安', 101050306, 'm', 'n'),
(104, '05', '黑龙江', '0503', '牡丹江', '050307', '东宁', 101050307, 'm', 'd'),
(105, '05', '黑龙江', '0504', '佳木斯', '050401', '佳木斯', 101050401, 'j', 'j'),
(106, '05', '黑龙江', '0504', '佳木斯', '050402', '汤原', 101050402, 'j', 't'),
(107, '05', '黑龙江', '0504', '佳木斯', '050403', '抚远', 101050403, 'j', 'f'),
(108, '05', '黑龙江', '0504', '佳木斯', '050404', '桦川', 101050404, 'j', 'h'),
(109, '05', '黑龙江', '0504', '佳木斯', '050405', '桦南', 101050405, 'j', 'h'),
(110, '05', '黑龙江', '0504', '佳木斯', '050406', '同江', 101050406, 'j', 't'),
(111, '05', '黑龙江', '0504', '佳木斯', '050407', '富锦', 101050407, 'j', 'f'),
(112, '05', '黑龙江', '0505', '绥化', '050501', '绥化', 101050501, 's', 's'),
(113, '05', '黑龙江', '0505', '绥化', '050502', '肇东', 101050502, 's', 'z'),
(114, '05', '黑龙江', '0505', '绥化', '050503', '安达', 101050503, 's', 'a'),
(115, '05', '黑龙江', '0505', '绥化', '050504', '海伦', 101050504, 's', 'h'),
(116, '05', '黑龙江', '0505', '绥化', '050505', '明水', 101050505, 's', 'm'),
(117, '05', '黑龙江', '0505', '绥化', '050506', '望奎', 101050506, 's', 'w'),
(118, '05', '黑龙江', '0505', '绥化', '050507', '兰西', 101050507, 's', 'l'),
(119, '05', '黑龙江', '0505', '绥化', '050508', '青冈', 101050508, 's', 'q'),
(120, '05', '黑龙江', '0505', '绥化', '050509', '庆安', 101050509, 's', 'q'),
(121, '05', '黑龙江', '0505', '绥化', '050510', '绥棱', 101050510, 's', 's'),
(122, '05', '黑龙江', '0506', '黑河', '050601', '黑河', 101050601, 'h', 'h'),
(123, '05', '黑龙江', '0506', '黑河', '050602', '嫩江', 101050602, 'h', 'n'),
(124, '05', '黑龙江', '0506', '黑河', '050603', '孙吴', 101050603, 'h', 's'),
(125, '05', '黑龙江', '0506', '黑河', '050604', '逊克', 101050604, 'h', 'x'),
(126, '05', '黑龙江', '0506', '黑河', '050605', '五大连池', 101050605, 'h', 'w'),
(127, '05', '黑龙江', '0506', '黑河', '050606', '北安', 101050606, 'h', 'b'),
(128, '05', '黑龙江', '0507', '大兴安岭', '050701', '大兴安岭', 101050701, 'd', 'd'),
(129, '05', '黑龙江', '0507', '大兴安岭', '050702', '塔河', 101050702, 'd', 't'),
(130, '05', '黑龙江', '0507', '大兴安岭', '050703', '漠河', 101050703, 'd', 'm'),
(131, '05', '黑龙江', '0507', '大兴安岭', '050704', '呼玛', 101050704, 'd', 'h'),
(132, '05', '黑龙江', '0507', '大兴安岭', '050705', '呼中', 101050705, 'd', 'h'),
(133, '05', '黑龙江', '0507', '大兴安岭', '050706', '新林', 101050706, 'd', 'x'),
(134, '05', '黑龙江', '0507', '大兴安岭', '050707', '加格达奇', 101050708, 'd', 'j'),
(135, '05', '黑龙江', '0508', '伊春', '050801', '伊春', 101050801, 'y', 'y'),
(136, '05', '黑龙江', '0508', '伊春', '050802', '乌伊岭', 101050802, 'y', 'w'),
(137, '05', '黑龙江', '0508', '伊春', '050803', '五营', 101050803, 'y', 'w'),
(138, '05', '黑龙江', '0508', '伊春', '050804', '铁力', 101050804, 'y', 't'),
(139, '05', '黑龙江', '0508', '伊春', '050805', '嘉荫', 101050805, 'y', 'j'),
(140, '05', '黑龙江', '0509', '大庆', '050901', '大庆', 101050901, 'd', 'd'),
(141, '05', '黑龙江', '0509', '大庆', '050902', '林甸', 101050902, 'd', 'l'),
(142, '05', '黑龙江', '0509', '大庆', '050903', '肇州', 101050903, 'd', 'z'),
(143, '05', '黑龙江', '0509', '大庆', '050904', '肇源', 101050904, 'd', 'z'),
(144, '05', '黑龙江', '0509', '大庆', '050905', '杜尔伯特', 101050905, 'd', 'd'),
(145, '05', '黑龙江', '0510', '七台河', '051001', '七台河', 101051002, 'q', 'q'),
(146, '05', '黑龙江', '0510', '七台河', '051002', '勃利', 101051003, 'q', 'b'),
(147, '05', '黑龙江', '0511', '鸡西', '051101', '鸡西', 101051101, 'j', 'j'),
(148, '05', '黑龙江', '0511', '鸡西', '051102', '虎林', 101051102, 'j', 'h'),
(149, '05', '黑龙江', '0511', '鸡西', '051103', '密山', 101051103, 'j', 'm'),
(150, '05', '黑龙江', '0511', '鸡西', '051104', '鸡东', 101051104, 'j', 'j'),
(151, '05', '黑龙江', '0512', '鹤岗', '051201', '鹤岗', 101051201, 'h', 'h'),
(152, '05', '黑龙江', '0512', '鹤岗', '051202', '绥滨', 101051202, 'h', 's'),
(153, '05', '黑龙江', '0512', '鹤岗', '051203', '萝北', 101051203, 'h', 'l'),
(154, '05', '黑龙江', '0513', '双鸭山', '051301', '双鸭山', 101051301, 's', 's'),
(155, '05', '黑龙江', '0513', '双鸭山', '051302', '集贤', 101051302, 's', 'j'),
(156, '05', '黑龙江', '0513', '双鸭山', '051303', '宝清', 101051303, 's', 'b'),
(157, '05', '黑龙江', '0513', '双鸭山', '051304', '饶河', 101051304, 's', 'r'),
(158, '05', '黑龙江', '0513', '双鸭山', '051305', '友谊', 101051305, 's', 'y'),
(159, '06', '吉林', '0601', '长春', '060101', '长春', 101060101, 'c', 'c'),
(160, '06', '吉林', '0601', '长春', '060102', '农安', 101060102, 'c', 'n'),
(161, '06', '吉林', '0601', '长春', '060103', '德惠', 101060103, 'c', 'd'),
(162, '06', '吉林', '0601', '长春', '060104', '九台', 101060104, 'c', 'j'),
(163, '06', '吉林', '0601', '长春', '060105', '榆树', 101060105, 'c', 'y'),
(164, '06', '吉林', '0601', '长春', '060106', '双阳', 101060106, 'c', 's'),
(165, '06', '吉林', '0602', '吉林', '060201', '吉林', 101060201, 'j', 'j'),
(166, '06', '吉林', '0602', '吉林', '060202', '舒兰', 101060202, 'j', 's'),
(167, '06', '吉林', '0602', '吉林', '060203', '永吉', 101060203, 'j', 'y'),
(168, '06', '吉林', '0602', '吉林', '060204', '蛟河', 101060204, 'j', 'j'),
(169, '06', '吉林', '0602', '吉林', '060205', '磐石', 101060205, 'j', 'p'),
(170, '06', '吉林', '0602', '吉林', '060206', '桦甸', 101060206, 'j', 'h'),
(171, '06', '吉林', '0603', '延边', '060301', '延边', 101060301, 'y', 'y'),
(172, '06', '吉林', '0603', '延边', '060302', '敦化', 101060302, 'y', 'd'),
(173, '06', '吉林', '0603', '延边', '060303', '安图', 101060303, 'y', 'a'),
(174, '06', '吉林', '0603', '延边', '060304', '汪清', 101060304, 'y', 'w'),
(175, '06', '吉林', '0603', '延边', '060305', '和龙', 101060305, 'y', 'h'),
(176, '06', '吉林', '0603', '延边', '060306', '龙井', 101060307, 'y', 'l'),
(177, '06', '吉林', '0603', '延边', '060307', '珲春', 101060308, 'y', 'h'),
(178, '06', '吉林', '0603', '延边', '060308', '图们', 101060309, 'y', 't'),
(179, '06', '吉林', '0604', '四平', '060401', '四平', 101060401, 's', 's'),
(180, '06', '吉林', '0604', '四平', '060402', '双辽', 101060402, 's', 's'),
(181, '06', '吉林', '0604', '四平', '060403', '梨树', 101060403, 's', 'l'),
(182, '06', '吉林', '0604', '四平', '060404', '公主岭', 101060404, 's', 'g'),
(183, '06', '吉林', '0604', '四平', '060405', '伊通', 101060405, 's', 'y'),
(184, '06', '吉林', '0605', '通化', '060501', '通化', 101060501, 't', 't'),
(185, '06', '吉林', '0605', '通化', '060502', '梅河口', 101060502, 't', 'm'),
(186, '06', '吉林', '0605', '通化', '060503', '柳河', 101060503, 't', 'l'),
(187, '06', '吉林', '0605', '通化', '060504', '辉南', 101060504, 't', 'h'),
(188, '06', '吉林', '0605', '通化', '060505', '集安', 101060505, 't', 'j'),
(189, '06', '吉林', '0605', '通化', '060506', '通化县', 101060506, 't', 't'),
(190, '06', '吉林', '0606', '白城', '060601', '白城', 101060601, 'b', 'b'),
(191, '06', '吉林', '0606', '白城', '060602', '洮南', 101060602, 'b', 't'),
(192, '06', '吉林', '0606', '白城', '060603', '大安', 101060603, 'b', 'd'),
(193, '06', '吉林', '0606', '白城', '060604', '镇赉', 101060604, 'b', 'z'),
(194, '06', '吉林', '0606', '白城', '060605', '通榆', 101060605, 'b', 't'),
(195, '06', '吉林', '0607', '辽源', '060701', '辽源', 101060701, 'l', 'l'),
(196, '06', '吉林', '0607', '辽源', '060702', '东丰', 101060702, 'l', 'd'),
(197, '06', '吉林', '0607', '辽源', '060703', '东辽', 101060703, 'l', 'd'),
(198, '06', '吉林', '0608', '松原', '060801', '松原', 101060801, 's', 's'),
(199, '06', '吉林', '0608', '松原', '060802', '乾安', 101060802, 's', 'q'),
(200, '06', '吉林', '0608', '松原', '060803', '前郭', 101060803, 's', 'q'),
(201, '06', '吉林', '0608', '松原', '060804', '长岭', 101060804, 's', 'c'),
(202, '06', '吉林', '0608', '松原', '060805', '扶余', 101060805, 's', 'f'),
(203, '06', '吉林', '0609', '白山', '060901', '白山', 101060901, 'b', 'b'),
(204, '06', '吉林', '0609', '白山', '060902', '靖宇', 101060902, 'b', 'j'),
(205, '06', '吉林', '0609', '白山', '060903', '临江', 101060903, 'b', 'l'),
(206, '06', '吉林', '0609', '白山', '060904', '东岗', 101060904, 'b', 'd'),
(207, '06', '吉林', '0609', '白山', '060905', '长白', 101060905, 'b', 'c'),
(208, '06', '吉林', '0609', '白山', '060906', '抚松', 101060906, 'b', 'f'),
(209, '06', '吉林', '0609', '白山', '060907', '江源', 101060907, 'b', 'j'),
(210, '07', '辽宁', '0701', '沈阳', '070101', '沈阳', 101070101, 's', 's'),
(211, '07', '辽宁', '0701', '沈阳', '070102', '辽中', 101070103, 's', 'l'),
(212, '07', '辽宁', '0701', '沈阳', '070103', '康平', 101070104, 's', 'k'),
(213, '07', '辽宁', '0701', '沈阳', '070104', '法库', 101070105, 's', 'f'),
(214, '07', '辽宁', '0701', '沈阳', '070105', '新民', 101070106, 's', 'x'),
(215, '07', '辽宁', '0702', '大连', '070201', '大连', 101070201, 'd', 'd'),
(216, '07', '辽宁', '0702', '大连', '070202', '瓦房店', 101070202, 'd', 'w'),
(217, '07', '辽宁', '0702', '大连', '070203', '金州', 101070203, 'd', 'j'),
(218, '07', '辽宁', '0702', '大连', '070204', '普兰店', 101070204, 'd', 'p'),
(219, '07', '辽宁', '0702', '大连', '070205', '旅顺', 101070205, 'd', 'l'),
(220, '07', '辽宁', '0702', '大连', '070206', '长海', 101070206, 'd', 'c'),
(221, '07', '辽宁', '0702', '大连', '070207', '庄河', 101070207, 'd', 'z'),
(222, '07', '辽宁', '0703', '鞍山', '070301', '鞍山', 101070301, 'a', 'a'),
(223, '07', '辽宁', '0703', '鞍山', '070302', '台安', 101070302, 'a', 't'),
(224, '07', '辽宁', '0703', '鞍山', '070303', '岫岩', 101070303, 'a', 'x'),
(225, '07', '辽宁', '0703', '鞍山', '070304', '海城', 101070304, 'a', 'h'),
(226, '07', '辽宁', '0704', '抚顺', '070401', '抚顺', 101070401, 'f', 'f'),
(227, '07', '辽宁', '0704', '抚顺', '070402', '新宾', 101070402, 'f', 'x'),
(228, '07', '辽宁', '0704', '抚顺', '070403', '清原', 101070403, 'f', 'q'),
(229, '07', '辽宁', '0704', '抚顺', '070404', '章党', 101070404, 'f', 'z'),
(230, '07', '辽宁', '0705', '本溪', '070501', '本溪', 101070501, 'b', 'b'),
(231, '07', '辽宁', '0705', '本溪', '070502', '本溪县', 101070502, 'b', 'b'),
(232, '07', '辽宁', '0705', '本溪', '070503', '桓仁', 101070504, 'b', 'h'),
(233, '07', '辽宁', '0706', '丹东', '070601', '丹东', 101070601, 'd', 'd'),
(234, '07', '辽宁', '0706', '丹东', '070602', '凤城', 101070602, 'd', 'f'),
(235, '07', '辽宁', '0706', '丹东', '070603', '宽甸', 101070603, 'd', 'k'),
(236, '07', '辽宁', '0706', '丹东', '070604', '东港', 101070604, 'd', 'd'),
(237, '07', '辽宁', '0707', '锦州', '070701', '锦州', 101070701, 'j', 'j'),
(238, '07', '辽宁', '0707', '锦州', '070702', '凌海', 101070702, 'j', 'l'),
(239, '07', '辽宁', '0707', '锦州', '070703', '义县', 101070704, 'j', 'y'),
(240, '07', '辽宁', '0707', '锦州', '070704', '黑山', 101070705, 'j', 'h'),
(241, '07', '辽宁', '0707', '锦州', '070705', '北镇', 101070706, 'j', 'b'),
(242, '07', '辽宁', '0708', '营口', '070801', '营口', 101070801, 'y', 'y'),
(243, '07', '辽宁', '0708', '营口', '070802', '大石桥', 101070802, 'y', 'd'),
(244, '07', '辽宁', '0708', '营口', '070803', '盖州', 101070803, 'y', 'g'),
(245, '07', '辽宁', '0709', '阜新', '070901', '阜新', 101070901, 'f', 'f'),
(246, '07', '辽宁', '0709', '阜新', '070902', '彰武', 101070902, 'f', 'z'),
(247, '07', '辽宁', '0710', '辽阳', '071001', '辽阳', 101071001, 'l', 'l'),
(248, '07', '辽宁', '0710', '辽阳', '071002', '辽阳县', 101071002, 'l', 'l'),
(249, '07', '辽宁', '0710', '辽阳', '071003', '灯塔', 101071003, 'l', 'd'),
(250, '07', '辽宁', '0710', '辽阳', '071004', '弓长岭', 101071004, 'l', 'g'),
(251, '07', '辽宁', '0711', '铁岭', '071101', '铁岭', 101071101, 't', 't'),
(252, '07', '辽宁', '0711', '铁岭', '071102', '开原', 101071102, 't', 'k'),
(253, '07', '辽宁', '0711', '铁岭', '071103', '昌图', 101071103, 't', 'c'),
(254, '07', '辽宁', '0711', '铁岭', '071104', '西丰', 101071104, 't', 'x'),
(255, '07', '辽宁', '0711', '铁岭', '071105', '调兵山', 101071105, 't', 'd'),
(256, '07', '辽宁', '0712', '朝阳', '071201', '朝阳', 101071201, 'c', 'c'),
(257, '07', '辽宁', '0712', '朝阳', '071202', '凌源', 101071203, 'c', 'l'),
(258, '07', '辽宁', '0712', '朝阳', '071203', '喀左', 101071204, 'c', 'k'),
(259, '07', '辽宁', '0712', '朝阳', '071204', '北票', 101071205, 'c', 'b'),
(260, '07', '辽宁', '0712', '朝阳', '071205', '建平县', 101071207, 'c', 'j'),
(261, '07', '辽宁', '0713', '盘锦', '071301', '盘锦', 101071301, 'p', 'p'),
(262, '07', '辽宁', '0713', '盘锦', '071302', '大洼', 101071302, 'p', 'd'),
(263, '07', '辽宁', '0713', '盘锦', '071303', '盘山', 101071303, 'p', 'p'),
(264, '07', '辽宁', '0714', '葫芦岛', '071401', '葫芦岛', 101071401, 'h', 'h'),
(265, '07', '辽宁', '0714', '葫芦岛', '071402', '建昌', 101071402, 'h', 'j'),
(266, '07', '辽宁', '0714', '葫芦岛', '071403', '绥中', 101071403, 'h', 's'),
(267, '07', '辽宁', '0714', '葫芦岛', '071404', '兴城', 101071404, 'h', 'x'),
(268, '08', '内蒙古', '0801', '呼和浩特', '080101', '呼和浩特', 101080101, 'h', 'h'),
(269, '08', '内蒙古', '0801', '呼和浩特', '080102', '土左旗', 101080102, 'h', 't'),
(270, '08', '内蒙古', '0801', '呼和浩特', '080103', '托县', 101080103, 'h', 't'),
(271, '08', '内蒙古', '0801', '呼和浩特', '080104', '和林', 101080104, 'h', 'h'),
(272, '08', '内蒙古', '0801', '呼和浩特', '080105', '清水河', 101080105, 'h', 'q'),
(273, '08', '内蒙古', '0801', '呼和浩特', '080106', '呼市郊区', 101080106, 'h', 'h'),
(274, '08', '内蒙古', '0801', '呼和浩特', '080107', '武川', 101080107, 'h', 'w'),
(275, '08', '内蒙古', '0802', '包头', '080201', '包头', 101080201, 'b', 'b'),
(276, '08', '内蒙古', '0802', '包头', '080202', '白云鄂博', 101080202, 'b', 'b'),
(277, '08', '内蒙古', '0802', '包头', '080203', '满都拉', 101080203, 'b', 'm'),
(278, '08', '内蒙古', '0802', '包头', '080204', '土右旗', 101080204, 'b', 't'),
(279, '08', '内蒙古', '0802', '包头', '080205', '固阳', 101080205, 'b', 'g'),
(280, '08', '内蒙古', '0802', '包头', '080206', '达茂旗', 101080206, 'b', 'd'),
(281, '08', '内蒙古', '0802', '包头', '080207', '希拉穆仁', 101080207, 'b', 'x'),
(282, '08', '内蒙古', '0803', '乌海', '080301', '乌海', 101080301, 'w', 'w'),
(283, '08', '内蒙古', '0804', '乌兰察布', '080401', '乌兰察布', 101080401, 'w', 'w'),
(284, '08', '内蒙古', '0804', '乌兰察布', '080402', '卓资', 101080402, 'w', 'z'),
(285, '08', '内蒙古', '0804', '乌兰察布', '080403', '化德', 101080403, 'w', 'h'),
(286, '08', '内蒙古', '0804', '乌兰察布', '080404', '商都', 101080404, 'w', 's'),
(287, '08', '内蒙古', '0804', '乌兰察布', '080405', '兴和', 101080406, 'w', 'x'),
(288, '08', '内蒙古', '0804', '乌兰察布', '080406', '凉城', 101080407, 'w', 'l'),
(289, '08', '内蒙古', '0804', '乌兰察布', '080407', '察右前旗', 101080408, 'w', 'c'),
(290, '08', '内蒙古', '0804', '乌兰察布', '080408', '察右中旗', 101080409, 'w', 'c'),
(291, '08', '内蒙古', '0804', '乌兰察布', '080409', '察右后旗', 101080410, 'w', 'c'),
(292, '08', '内蒙古', '0804', '乌兰察布', '080410', '四子王旗', 101080411, 'w', 's'),
(293, '08', '内蒙古', '0804', '乌兰察布', '080411', '丰镇', 101080412, 'w', 'f'),
(294, '08', '内蒙古', '0805', '通辽', '080501', '通辽', 101080501, 't', 't'),
(295, '08', '内蒙古', '0805', '通辽', '080502', '舍伯吐', 101080502, 't', 's'),
(296, '08', '内蒙古', '0805', '通辽', '080503', '科左中旗', 101080503, 't', 'k'),
(297, '08', '内蒙古', '0805', '通辽', '080504', '科左后旗', 101080504, 't', 'k'),
(298, '08', '内蒙古', '0805', '通辽', '080505', '青龙山', 101080505, 't', 'q'),
(299, '08', '内蒙古', '0805', '通辽', '080506', '开鲁', 101080506, 't', 'k'),
(300, '08', '内蒙古', '0805', '通辽', '080507', '库伦', 101080507, 't', 'k'),
(301, '08', '内蒙古', '0805', '通辽', '080508', '奈曼', 101080508, 't', 'n'),
(302, '08', '内蒙古', '0805', '通辽', '080509', '扎鲁特', 101080509, 't', 'z'),
(303, '08', '内蒙古', '0805', '通辽', '080510', '巴雅尔吐胡硕', 101080511, 't', 'b'),
(304, '08', '内蒙古', '0805', '通辽', '080511', '霍林郭勒', 101081108, 't', 'h'),
(305, '08', '内蒙古', '0806', '赤峰', '080601', '赤峰', 101080601, 'c', 'c'),
(306, '08', '内蒙古', '0806', '赤峰', '080602', '阿鲁旗', 101080603, 'c', 'a'),
(307, '08', '内蒙古', '0806', '赤峰', '080603', '浩尔吐', 101080604, 'c', 'h'),
(308, '08', '内蒙古', '0806', '赤峰', '080604', '巴林左旗', 101080605, 'c', 'b'),
(309, '08', '内蒙古', '0806', '赤峰', '080605', '巴林右旗', 101080606, 'c', 'b'),
(310, '08', '内蒙古', '0806', '赤峰', '080606', '林西', 101080607, 'c', 'l'),
(311, '08', '内蒙古', '0806', '赤峰', '080607', '克什克腾', 101080608, 'c', 'k'),
(312, '08', '内蒙古', '0806', '赤峰', '080608', '翁牛特', 101080609, 'c', 'w'),
(313, '08', '内蒙古', '0806', '赤峰', '080609', '岗子', 101080610, 'c', 'g'),
(314, '08', '内蒙古', '0806', '赤峰', '080610', '喀喇沁', 101080611, 'c', 'k'),
(315, '08', '内蒙古', '0806', '赤峰', '080611', '八里罕', 101080612, 'c', 'b'),
(316, '08', '内蒙古', '0806', '赤峰', '080612', '宁城', 101080613, 'c', 'n'),
(317, '08', '内蒙古', '0806', '赤峰', '080613', '敖汉', 101080614, 'c', 'a'),
(318, '08', '内蒙古', '0806', '赤峰', '080614', '宝国吐', 101080615, 'c', 'b'),
(319, '08', '内蒙古', '0807', '鄂尔多斯', '080701', '鄂尔多斯', 101080701, 'e', 'e'),
(320, '08', '内蒙古', '0807', '鄂尔多斯', '080702', '达拉特', 101080703, 'e', 'd'),
(321, '08', '内蒙古', '0807', '鄂尔多斯', '080703', '准格尔', 101080704, 'e', 'z'),
(322, '08', '内蒙古', '0807', '鄂尔多斯', '080704', '鄂前旗', 101080705, 'e', 'e'),
(323, '08', '内蒙古', '0807', '鄂尔多斯', '080705', '河南', 101080706, 'e', 'h'),
(324, '08', '内蒙古', '0807', '鄂尔多斯', '080706', '伊克乌素', 101080707, 'e', 'y'),
(325, '08', '内蒙古', '0807', '鄂尔多斯', '080707', '鄂托克', 101080708, 'e', 'e'),
(326, '08', '内蒙古', '0807', '鄂尔多斯', '080708', '杭锦旗', 101080709, 'e', 'h'),
(327, '08', '内蒙古', '0807', '鄂尔多斯', '080709', '乌审旗', 101080710, 'e', 'w'),
(328, '08', '内蒙古', '0807', '鄂尔多斯', '080710', '伊金霍洛', 101080711, 'e', 'y'),
(329, '08', '内蒙古', '0807', '鄂尔多斯', '080711', '乌审召', 101080712, 'e', 'w'),
(330, '08', '内蒙古', '0807', '鄂尔多斯', '080712', '东胜', 101080713, 'e', 'd'),
(331, '08', '内蒙古', '0808', '巴彦淖尔', '080801', '巴彦淖尔', 101080801, 'b', 'b'),
(332, '08', '内蒙古', '0808', '巴彦淖尔', '080802', '五原', 101080802, 'b', 'w'),
(333, '08', '内蒙古', '0808', '巴彦淖尔', '080803', '磴口', 101080803, 'b', 'd'),
(334, '08', '内蒙古', '0808', '巴彦淖尔', '080804', '乌前旗', 101080804, 'b', 'w'),
(335, '08', '内蒙古', '0808', '巴彦淖尔', '080805', '大佘太', 101080805, 'b', 'd'),
(336, '08', '内蒙古', '0808', '巴彦淖尔', '080806', '乌中旗', 101080806, 'b', 'w'),
(337, '08', '内蒙古', '0808', '巴彦淖尔', '080807', '乌后旗', 101080807, 'b', 'w'),
(338, '08', '内蒙古', '0808', '巴彦淖尔', '080808', '海力素', 101080808, 'b', 'h'),
(339, '08', '内蒙古', '0808', '巴彦淖尔', '080809', '那仁宝力格', 101080809, 'b', 'n'),
(340, '08', '内蒙古', '0808', '巴彦淖尔', '080810', '杭锦后旗', 101080810, 'b', 'h'),
(341, '08', '内蒙古', '0809', '锡林郭勒', '080901', '锡林郭勒', 101080901, 'x', 'x'),
(342, '08', '内蒙古', '0809', '锡林郭勒', '080902', '二连浩特', 101080903, 'x', 'e'),
(343, '08', '内蒙古', '0809', '锡林郭勒', '080903', '阿巴嘎', 101080904, 'x', 'a'),
(344, '08', '内蒙古', '0809', '锡林郭勒', '080904', '苏左旗', 101080906, 'x', 's'),
(345, '08', '内蒙古', '0809', '锡林郭勒', '080905', '苏右旗', 101080907, 'x', 's'),
(346, '08', '内蒙古', '0809', '锡林郭勒', '080906', '朱日和', 101080908, 'x', 'z'),
(347, '08', '内蒙古', '0809', '锡林郭勒', '080907', '东乌旗', 101080909, 'x', 'd'),
(348, '08', '内蒙古', '0809', '锡林郭勒', '080908', '西乌旗', 101080910, 'x', 'x'),
(349, '08', '内蒙古', '0809', '锡林郭勒', '080909', '太仆寺', 101080911, 'x', 't'),
(350, '08', '内蒙古', '0809', '锡林郭勒', '080910', '镶黄旗', 101080912, 'x', 'x'),
(351, '08', '内蒙古', '0809', '锡林郭勒', '080911', '正镶白旗', 101080913, 'x', 'z'),
(352, '08', '内蒙古', '0809', '锡林郭勒', '080912', '正蓝旗', 101080914, 'x', 'z'),
(353, '08', '内蒙古', '0809', '锡林郭勒', '080913', '多伦', 101080915, 'x', 'd'),
(354, '08', '内蒙古', '0809', '锡林郭勒', '080914', '博克图', 101080916, 'x', 'b'),
(355, '08', '内蒙古', '0809', '锡林郭勒', '080915', '乌拉盖', 101080917, 'x', 'w'),
(356, '08', '内蒙古', '0810', '呼伦贝尔', '081001', '呼伦贝尔', 101081001, 'h', 'h'),
(357, '08', '内蒙古', '0810', '呼伦贝尔', '081002', '小二沟', 101081002, 'h', 'x'),
(358, '08', '内蒙古', '0810', '呼伦贝尔', '081003', '阿荣旗', 101081003, 'h', 'a'),
(359, '08', '内蒙古', '0810', '呼伦贝尔', '081004', '莫力达瓦', 101081004, 'h', 'm'),
(360, '08', '内蒙古', '0810', '呼伦贝尔', '081005', '鄂伦春旗', 101081005, 'h', 'e'),
(361, '08', '内蒙古', '0810', '呼伦贝尔', '081006', '鄂温克旗', 101081006, 'h', 'e'),
(362, '08', '内蒙古', '0810', '呼伦贝尔', '081007', '陈旗', 101081007, 'h', 'c'),
(363, '08', '内蒙古', '0810', '呼伦贝尔', '081008', '新左旗', 101081008, 'h', 'x'),
(364, '08', '内蒙古', '0810', '呼伦贝尔', '081009', '新右旗', 101081009, 'h', 'x'),
(365, '08', '内蒙古', '0810', '呼伦贝尔', '081010', '满洲里', 101081010, 'h', 'm'),
(366, '08', '内蒙古', '0810', '呼伦贝尔', '081011', '牙克石', 101081011, 'h', 'y'),
(367, '08', '内蒙古', '0810', '呼伦贝尔', '081012', '扎兰屯', 101081012, 'h', 'z'),
(368, '08', '内蒙古', '0810', '呼伦贝尔', '081013', '额尔古纳', 101081014, 'h', 'e'),
(369, '08', '内蒙古', '0810', '呼伦贝尔', '081014', '根河', 101081015, 'h', 'g'),
(370, '08', '内蒙古', '0810', '呼伦贝尔', '081015', '图里河', 101081016, 'h', 't'),
(371, '08', '内蒙古', '0811', '兴安盟', '081101', '兴安盟', 101080510, 'x', 'x'),
(372, '08', '内蒙古', '0811', '兴安盟', '081102', '乌兰浩特', 101081101, 'x', 'w'),
(373, '08', '内蒙古', '0811', '兴安盟', '081103', '阿尔山', 101081102, 'x', 'a'),
(374, '08', '内蒙古', '0811', '兴安盟', '081104', '科右中旗', 101081103, 'x', 'k'),
(375, '08', '内蒙古', '0811', '兴安盟', '081105', '胡尔勒', 101081104, 'x', 'h'),
(376, '08', '内蒙古', '0811', '兴安盟', '081106', '扎赉特', 101081105, 'x', 'z'),
(377, '08', '内蒙古', '0811', '兴安盟', '081107', '索伦', 101081106, 'x', 's'),
(378, '08', '内蒙古', '0811', '兴安盟', '081108', '突泉', 101081107, 'x', 't'),
(379, '08', '内蒙古', '0811', '兴安盟', '081109', '科右前旗', 101081109, 'x', 'k'),
(380, '08', '内蒙古', '0812', '阿拉善盟', '081201', '阿拉善盟', 101081201, 'a', 'a'),
(381, '08', '内蒙古', '0812', '阿拉善盟', '081202', '阿右旗', 101081202, 'a', 'a'),
(382, '08', '内蒙古', '0812', '阿拉善盟', '081203', '额济纳', 101081203, 'a', 'e'),
(383, '08', '内蒙古', '0812', '阿拉善盟', '081204', '拐子湖', 101081204, 'a', 'g'),
(384, '08', '内蒙古', '0812', '阿拉善盟', '081205', '吉兰太', 101081205, 'a', 'j'),
(385, '08', '内蒙古', '0812', '阿拉善盟', '081206', '锡林高勒', 101081206, 'a', 'x'),
(386, '08', '内蒙古', '0812', '阿拉善盟', '081207', '头道湖', 101081207, 'a', 't'),
(387, '08', '内蒙古', '0812', '阿拉善盟', '081208', '中泉子', 101081208, 'a', 'z'),
(388, '08', '内蒙古', '0812', '阿拉善盟', '081209', '诺尔公', 101081209, 'a', 'n'),
(389, '08', '内蒙古', '0812', '阿拉善盟', '081210', '雅布赖', 101081210, 'a', 'y'),
(390, '08', '内蒙古', '0812', '阿拉善盟', '081211', '乌斯泰', 101081211, 'a', 'w'),
(391, '08', '内蒙古', '0812', '阿拉善盟', '081212', '孪井滩', 101081212, 'a', 'l'),
(392, '09', '河北', '0901', '石家庄', '090101', '石家庄', 101090101, 's', 's'),
(393, '09', '河北', '0901', '石家庄', '090102', '井陉', 101090102, 's', 'j'),
(394, '09', '河北', '0901', '石家庄', '090103', '正定', 101090103, 's', 'z'),
(395, '09', '河北', '0901', '石家庄', '090104', '栾城', 101090104, 's', 'l'),
(396, '09', '河北', '0901', '石家庄', '090105', '行唐', 101090105, 's', 'x'),
(397, '09', '河北', '0901', '石家庄', '090106', '灵寿', 101090106, 's', 'l'),
(398, '09', '河北', '0901', '石家庄', '090107', '高邑', 101090107, 's', 'g'),
(399, '09', '河北', '0901', '石家庄', '090108', '深泽', 101090108, 's', 's'),
(400, '09', '河北', '0901', '石家庄', '090109', '赞皇', 101090109, 's', 'z'),
(401, '09', '河北', '0901', '石家庄', '090110', '无极', 101090110, 's', 'w'),
(402, '09', '河北', '0901', '石家庄', '090111', '平山', 101090111, 's', 'p'),
(403, '09', '河北', '0901', '石家庄', '090112', '元氏', 101090112, 's', 'y'),
(404, '09', '河北', '0901', '石家庄', '090113', '赵县', 101090113, 's', 'z'),
(405, '09', '河北', '0901', '石家庄', '090114', '辛集', 101090114, 's', 'x'),
(406, '09', '河北', '0901', '石家庄', '090115', '藁城', 101090115, 's', 'g'),
(407, '09', '河北', '0901', '石家庄', '090116', '晋州', 101090116, 's', 'j'),
(408, '09', '河北', '0901', '石家庄', '090117', '新乐', 101090117, 's', 'x'),
(409, '09', '河北', '0901', '石家庄', '090118', '鹿泉', 101090118, 's', 'l'),
(410, '09', '河北', '0902', '保定', '090201', '保定', 101090201, 'b', 'b'),
(411, '09', '河北', '0902', '保定', '090202', '满城', 101090202, 'b', 'm'),
(412, '09', '河北', '0902', '保定', '090203', '阜平', 101090203, 'b', 'f'),
(413, '09', '河北', '0902', '保定', '090204', '徐水', 101090204, 'b', 'x'),
(414, '09', '河北', '0902', '保定', '090205', '唐县', 101090205, 'b', 't'),
(415, '09', '河北', '0902', '保定', '090206', '高阳', 101090206, 'b', 'g'),
(416, '09', '河北', '0902', '保定', '090207', '容城', 101090207, 'b', 'r'),
(417, '09', '河北', '0902', '保定', '090208', '涞源', 101090209, 'b', 'l'),
(418, '09', '河北', '0902', '保定', '090209', '望都', 101090210, 'b', 'w'),
(419, '09', '河北', '0902', '保定', '090210', '安新', 101090211, 'b', 'a'),
(420, '09', '河北', '0902', '保定', '090211', '易县', 101090212, 'b', 'y'),
(421, '09', '河北', '0902', '保定', '090212', '曲阳', 101090214, 'b', 'q'),
(422, '09', '河北', '0902', '保定', '090213', '蠡县', 101090215, 'b', 'l'),
(423, '09', '河北', '0902', '保定', '090214', '顺平', 101090216, 'b', 's'),
(424, '09', '河北', '0902', '保定', '090215', '雄县', 101090217, 'b', 'x'),
(425, '09', '河北', '0902', '保定', '090216', '涿州', 101090218, 'b', 'z'),
(426, '09', '河北', '0902', '保定', '090217', '定州', 101090219, 'b', 'd'),
(427, '09', '河北', '0902', '保定', '090218', '安国', 101090220, 'b', 'a'),
(428, '09', '河北', '0902', '保定', '090219', '高碑店', 101090221, 'b', 'g'),
(429, '09', '河北', '0902', '保定', '090220', '涞水', 101090222, 'b', 'l'),
(430, '09', '河北', '0902', '保定', '090221', '定兴', 101090223, 'b', 'd'),
(431, '09', '河北', '0902', '保定', '090222', '清苑', 101090224, 'b', 'q'),
(432, '09', '河北', '0902', '保定', '090223', '博野', 101090225, 'b', 'b'),
(433, '09', '河北', '0903', '张家口', '090301', '张家口', 101090301, 'z', 'z'),
(434, '09', '河北', '0903', '张家口', '090302', '宣化', 101090302, 'z', 'x'),
(435, '09', '河北', '0903', '张家口', '090303', '张北', 101090303, 'z', 'z'),
(436, '09', '河北', '0903', '张家口', '090304', '康保', 101090304, 'z', 'k'),
(437, '09', '河北', '0903', '张家口', '090305', '沽源', 101090305, 'z', 'g'),
(438, '09', '河北', '0903', '张家口', '090306', '尚义', 101090306, 'z', 's'),
(439, '09', '河北', '0903', '张家口', '090307', '蔚县', 101090307, 'z', 'w'),
(440, '09', '河北', '0903', '张家口', '090308', '阳原', 101090308, 'z', 'y'),
(441, '09', '河北', '0903', '张家口', '090309', '怀安', 101090309, 'z', 'h'),
(442, '09', '河北', '0903', '张家口', '090310', '万全', 101090310, 'z', 'w'),
(443, '09', '河北', '0903', '张家口', '090311', '怀来', 101090311, 'z', 'h'),
(444, '09', '河北', '0903', '张家口', '090312', '涿鹿', 101090312, 'z', 'z'),
(445, '09', '河北', '0903', '张家口', '090313', '赤城', 101090313, 'z', 'c'),
(446, '09', '河北', '0903', '张家口', '090314', '崇礼', 101090314, 'z', 'c'),
(447, '09', '河北', '0904', '承德', '090401', '承德', 101090402, 'c', 'c'),
(448, '09', '河北', '0904', '承德', '090402', '承德县', 101090403, 'c', 'c'),
(449, '09', '河北', '0904', '承德', '090403', '兴隆', 101090404, 'c', 'x'),
(450, '09', '河北', '0904', '承德', '090404', '平泉', 101090405, 'c', 'p'),
(451, '09', '河北', '0904', '承德', '090405', '滦平', 101090406, 'c', 'l'),
(452, '09', '河北', '0904', '承德', '090406', '隆化', 101090407, 'c', 'l'),
(453, '09', '河北', '0904', '承德', '090407', '丰宁', 101090408, 'c', 'f'),
(454, '09', '河北', '0904', '承德', '090408', '宽城', 101090409, 'c', 'k'),
(455, '09', '河北', '0904', '承德', '090409', '围场', 101090410, 'c', 'w'),
(456, '09', '河北', '0905', '唐山', '090501', '唐山', 101090501, 't', 't'),
(457, '09', '河北', '0905', '唐山', '090502', '丰南', 101090502, 't', 'f'),
(458, '09', '河北', '0905', '唐山', '090503', '丰润', 101090503, 't', 'f'),
(459, '09', '河北', '0905', '唐山', '090504', '滦县', 101090504, 't', 'l'),
(460, '09', '河北', '0905', '唐山', '090505', '滦南', 101090505, 't', 'l'),
(461, '09', '河北', '0905', '唐山', '090506', '乐亭', 101090506, 't', 'l'),
(462, '09', '河北', '0905', '唐山', '090507', '迁西', 101090507, 't', 'q'),
(463, '09', '河北', '0905', '唐山', '090508', '玉田', 101090508, 't', 'y'),
(464, '09', '河北', '0905', '唐山', '090509', '唐海', 101090509, 't', 't'),
(465, '09', '河北', '0905', '唐山', '090510', '遵化', 101090510, 't', 'z'),
(466, '09', '河北', '0905', '唐山', '090511', '迁安', 101090511, 't', 'q'),
(467, '09', '河北', '0905', '唐山', '090512', '曹妃甸', 101090512, 't', 'c'),
(468, '09', '河北', '0906', '廊坊', '090601', '廊坊', 101090601, 'l', 'l'),
(469, '09', '河北', '0906', '廊坊', '090602', '固安', 101090602, 'l', 'g'),
(470, '09', '河北', '0906', '廊坊', '090603', '永清', 101090603, 'l', 'y'),
(471, '09', '河北', '0906', '廊坊', '090604', '香河', 101090604, 'l', 'x'),
(472, '09', '河北', '0906', '廊坊', '090605', '大城', 101090605, 'l', 'd'),
(473, '09', '河北', '0906', '廊坊', '090606', '文安', 101090606, 'l', 'w'),
(474, '09', '河北', '0906', '廊坊', '090607', '大厂', 101090607, 'l', 'd'),
(475, '09', '河北', '0906', '廊坊', '090608', '霸州', 101090608, 'l', 'b'),
(476, '09', '河北', '0906', '廊坊', '090609', '三河', 101090609, 'l', 's'),
(477, '09', '河北', '0907', '沧州', '090701', '沧州', 101090701, 'c', 'c'),
(478, '09', '河北', '0907', '沧州', '090702', '青县', 101090702, 'c', 'q'),
(479, '09', '河北', '0907', '沧州', '090703', '东光', 101090703, 'c', 'd'),
(480, '09', '河北', '0907', '沧州', '090704', '海兴', 101090704, 'c', 'h'),
(481, '09', '河北', '0907', '沧州', '090705', '盐山', 101090705, 'c', 'y'),
(482, '09', '河北', '0907', '沧州', '090706', '肃宁', 101090706, 'c', 's'),
(483, '09', '河北', '0907', '沧州', '090707', '南皮', 101090707, 'c', 'n'),
(484, '09', '河北', '0907', '沧州', '090708', '吴桥', 101090708, 'c', 'w'),
(485, '09', '河北', '0907', '沧州', '090709', '献县', 101090709, 'c', 'x'),
(486, '09', '河北', '0907', '沧州', '090710', '孟村', 101090710, 'c', 'm'),
(487, '09', '河北', '0907', '沧州', '090711', '泊头', 101090711, 'c', 'b'),
(488, '09', '河北', '0907', '沧州', '090712', '任丘', 101090712, 'c', 'r'),
(489, '09', '河北', '0907', '沧州', '090713', '黄骅', 101090713, 'c', 'h'),
(490, '09', '河北', '0907', '沧州', '090714', '河间', 101090714, 'c', 'h'),
(491, '09', '河北', '0907', '沧州', '090715', '沧县', 101090716, 'c', 'c'),
(492, '09', '河北', '0908', '衡水', '090801', '衡水', 101090801, 'h', 'h'),
(493, '09', '河北', '0908', '衡水', '090802', '枣强', 101090802, 'h', 'z'),
(494, '09', '河北', '0908', '衡水', '090803', '武邑', 101090803, 'h', 'w'),
(495, '09', '河北', '0908', '衡水', '090804', '武强', 101090804, 'h', 'w'),
(496, '09', '河北', '0908', '衡水', '090805', '饶阳', 101090805, 'h', 'r'),
(497, '09', '河北', '0908', '衡水', '090806', '安平', 101090806, 'h', 'a'),
(498, '09', '河北', '0908', '衡水', '090807', '故城', 101090807, 'h', 'g'),
(499, '09', '河北', '0908', '衡水', '090808', '景县', 101090808, 'h', 'j'),
(500, '09', '河北', '0908', '衡水', '090809', '阜城', 101090809, 'h', 'f'),
(501, '09', '河北', '0908', '衡水', '090810', '冀州', 101090810, 'h', 'j'),
(502, '09', '河北', '0908', '衡水', '090811', '深州', 101090811, 'h', 's'),
(503, '09', '河北', '0909', '邢台', '090901', '邢台', 101090901, 'x', 'x'),
(504, '09', '河北', '0909', '邢台', '090902', '临城', 101090902, 'x', 'l'),
(505, '09', '河北', '0909', '邢台', '090903', '内丘', 101090904, 'x', 'n'),
(506, '09', '河北', '0909', '邢台', '090904', '柏乡', 101090905, 'x', 'b'),
(507, '09', '河北', '0909', '邢台', '090905', '隆尧', 101090906, 'x', 'l'),
(508, '09', '河北', '0909', '邢台', '090906', '南和', 101090907, 'x', 'n'),
(509, '09', '河北', '0909', '邢台', '090907', '宁晋', 101090908, 'x', 'n'),
(510, '09', '河北', '0909', '邢台', '090908', '巨鹿', 101090909, 'x', 'j'),
(511, '09', '河北', '0909', '邢台', '090909', '新河', 101090910, 'x', 'x'),
(512, '09', '河北', '0909', '邢台', '090910', '广宗', 101090911, 'x', 'g'),
(513, '09', '河北', '0909', '邢台', '090911', '平乡', 101090912, 'x', 'p'),
(514, '09', '河北', '0909', '邢台', '090912', '威县', 101090913, 'x', 'w'),
(515, '09', '河北', '0909', '邢台', '090913', '清河', 101090914, 'x', 'q'),
(516, '09', '河北', '0909', '邢台', '090914', '临西', 101090915, 'x', 'l'),
(517, '09', '河北', '0909', '邢台', '090915', '南宫', 101090916, 'x', 'n'),
(518, '09', '河北', '0909', '邢台', '090916', '沙河', 101090917, 'x', 's'),
(519, '09', '河北', '0909', '邢台', '090917', '任县', 101090918, 'x', 'r'),
(520, '09', '河北', '0910', '邯郸', '091001', '邯郸', 101091001, 'h', 'h'),
(521, '09', '河北', '0910', '邯郸', '091002', '峰峰', 101091002, 'h', 'f'),
(522, '09', '河北', '0910', '邯郸', '091003', '临漳', 101091003, 'h', 'l'),
(523, '09', '河北', '0910', '邯郸', '091004', '成安', 101091004, 'h', 'c'),
(524, '09', '河北', '0910', '邯郸', '091005', '大名', 101091005, 'h', 'd'),
(525, '09', '河北', '0910', '邯郸', '091006', '涉县', 101091006, 'h', 's'),
(526, '09', '河北', '0910', '邯郸', '091007', '磁县', 101091007, 'h', 'c'),
(527, '09', '河北', '0910', '邯郸', '091008', '肥乡', 101091008, 'h', 'f'),
(528, '09', '河北', '0910', '邯郸', '091009', '永年', 101091009, 'h', 'y'),
(529, '09', '河北', '0910', '邯郸', '091010', '邱县', 101091010, 'h', 'q'),
(530, '09', '河北', '0910', '邯郸', '091011', '鸡泽', 101091011, 'h', 'j'),
(531, '09', '河北', '0910', '邯郸', '091012', '广平', 101091012, 'h', 'g'),
(532, '09', '河北', '0910', '邯郸', '091013', '馆陶', 101091013, 'h', 'g'),
(533, '09', '河北', '0910', '邯郸', '091014', '魏县', 101091014, 'h', 'w'),
(534, '09', '河北', '0910', '邯郸', '091015', '曲周', 101091015, 'h', 'q'),
(535, '09', '河北', '0910', '邯郸', '091016', '武安', 101091016, 'h', 'w'),
(536, '09', '河北', '0911', '秦皇岛', '091101', '秦皇岛', 101091101, 'q', 'q'),
(537, '09', '河北', '0911', '秦皇岛', '091102', '青龙', 101091102, 'q', 'q'),
(538, '09', '河北', '0911', '秦皇岛', '091103', '昌黎', 101091103, 'q', 'c'),
(539, '09', '河北', '0911', '秦皇岛', '091104', '抚宁', 101091104, 'q', 'f'),
(540, '09', '河北', '0911', '秦皇岛', '091105', '卢龙', 101091105, 'q', 'l'),
(541, '09', '河北', '0911', '秦皇岛', '091106', '北戴河', 101091106, 'q', 'b'),
(542, '10', '山西', '1001', '太原', '100101', '太原', 101100101, 't', 't'),
(543, '10', '山西', '1001', '太原', '100102', '清徐', 101100102, 't', 'q'),
(544, '10', '山西', '1001', '太原', '100103', '阳曲', 101100103, 't', 'y'),
(545, '10', '山西', '1001', '太原', '100104', '娄烦', 101100104, 't', 'l'),
(546, '10', '山西', '1001', '太原', '100105', '古交', 101100105, 't', 'g'),
(547, '10', '山西', '1001', '太原', '100106', '尖草坪区', 101100106, 't', 'j'),
(548, '10', '山西', '1001', '太原', '100107', '小店区', 101100107, 't', 'x'),
(549, '10', '山西', '1002', '大同', '100201', '大同', 101100201, 'd', 'd'),
(550, '10', '山西', '1002', '大同', '100202', '阳高', 101100202, 'd', 'y'),
(551, '10', '山西', '1002', '大同', '100203', '大同县', 101100203, 'd', 'd'),
(552, '10', '山西', '1002', '大同', '100204', '天镇', 101100204, 'd', 't'),
(553, '10', '山西', '1002', '大同', '100205', '广灵', 101100205, 'd', 'g'),
(554, '10', '山西', '1002', '大同', '100206', '灵丘', 101100206, 'd', 'l'),
(555, '10', '山西', '1002', '大同', '100207', '浑源', 101100207, 'd', 'h'),
(556, '10', '山西', '1002', '大同', '100208', '左云', 101100208, 'd', 'z'),
(557, '10', '山西', '1003', '阳泉', '100301', '阳泉', 101100301, 'y', 'y'),
(558, '10', '山西', '1003', '阳泉', '100302', '盂县', 101100302, 'y', 'y'),
(559, '10', '山西', '1003', '阳泉', '100303', '平定', 101100303, 'y', 'p'),
(560, '10', '山西', '1004', '晋中', '100401', '晋中', 101100401, 'j', 'j'),
(561, '10', '山西', '1004', '晋中', '100402', '榆次', 101100402, 'j', 'y'),
(562, '10', '山西', '1004', '晋中', '100403', '榆社', 101100403, 'j', 'y'),
(563, '10', '山西', '1004', '晋中', '100404', '左权', 101100404, 'j', 'z'),
(564, '10', '山西', '1004', '晋中', '100405', '和顺', 101100405, 'j', 'h'),
(565, '10', '山西', '1004', '晋中', '100406', '昔阳', 101100406, 'j', 'x'),
(566, '10', '山西', '1004', '晋中', '100407', '寿阳', 101100407, 'j', 's'),
(567, '10', '山西', '1004', '晋中', '100408', '太谷', 101100408, 'j', 't'),
(568, '10', '山西', '1004', '晋中', '100409', '祁县', 101100409, 'j', 'q'),
(569, '10', '山西', '1004', '晋中', '100410', '平遥', 101100410, 'j', 'p'),
(570, '10', '山西', '1004', '晋中', '100411', '灵石', 101100411, 'j', 'l'),
(571, '10', '山西', '1004', '晋中', '100412', '介休', 101100412, 'j', 'j'),
(572, '10', '山西', '1005', '长治', '100501', '长治', 101100501, 'c', 'c'),
(573, '10', '山西', '1005', '长治', '100502', '黎城', 101100502, 'c', 'l'),
(574, '10', '山西', '1005', '长治', '100503', '屯留', 101100503, 'c', 't'),
(575, '10', '山西', '1005', '长治', '100504', '潞城', 101100504, 'c', 'l'),
(576, '10', '山西', '1005', '长治', '100505', '襄垣', 101100505, 'c', 'x'),
(577, '10', '山西', '1005', '长治', '100506', '平顺', 101100506, 'c', 'p'),
(578, '10', '山西', '1005', '长治', '100507', '武乡', 101100507, 'c', 'w'),
(579, '10', '山西', '1005', '长治', '100508', '沁县', 101100508, 'c', 'q'),
(580, '10', '山西', '1005', '长治', '100509', '长子', 101100509, 'c', 'c'),
(581, '10', '山西', '1005', '长治', '100510', '沁源', 101100510, 'c', 'q'),
(582, '10', '山西', '1005', '长治', '100511', '壶关', 101100511, 'c', 'h'),
(583, '10', '山西', '1006', '晋城', '100601', '晋城', 101100601, 'j', 'j'),
(584, '10', '山西', '1006', '晋城', '100602', '沁水', 101100602, 'j', 'q'),
(585, '10', '山西', '1006', '晋城', '100603', '阳城', 101100603, 'j', 'y'),
(586, '10', '山西', '1006', '晋城', '100604', '陵川', 101100604, 'j', 'l'),
(587, '10', '山西', '1006', '晋城', '100605', '高平', 101100605, 'j', 'g'),
(588, '10', '山西', '1006', '晋城', '100606', '泽州', 101100606, 'j', 'z'),
(589, '10', '山西', '1007', '临汾', '100701', '临汾', 101100701, 'l', 'l'),
(590, '10', '山西', '1007', '临汾', '100702', '曲沃', 101100702, 'l', 'q'),
(591, '10', '山西', '1007', '临汾', '100703', '永和', 101100703, 'l', 'y'),
(592, '10', '山西', '1007', '临汾', '100704', '隰县', 101100704, 'l', 'x'),
(593, '10', '山西', '1007', '临汾', '100705', '大宁', 101100705, 'l', 'd'),
(594, '10', '山西', '1007', '临汾', '100706', '吉县', 101100706, 'l', 'j'),
(595, '10', '山西', '1007', '临汾', '100707', '襄汾', 101100707, 'l', 'x'),
(596, '10', '山西', '1007', '临汾', '100708', '蒲县', 101100708, 'l', 'p'),
(597, '10', '山西', '1007', '临汾', '100709', '汾西', 101100709, 'l', 'f'),
(598, '10', '山西', '1007', '临汾', '100710', '洪洞', 101100710, 'l', 'h'),
(599, '10', '山西', '1007', '临汾', '100711', '霍州', 101100711, 'l', 'h');
INSERT INTO `cmstop_weather_city` (`id`, `province_id`, `province`, `town_id`, `town`, `city_id`, `city`, `weather_id`, `town_initial`, `city_initial`) VALUES
(600, '10', '山西', '1007', '临汾', '100712', '乡宁', 101100712, 'l', 'x'),
(601, '10', '山西', '1007', '临汾', '100713', '翼城', 101100713, 'l', 'y'),
(602, '10', '山西', '1007', '临汾', '100714', '侯马', 101100714, 'l', 'h'),
(603, '10', '山西', '1007', '临汾', '100715', '浮山', 101100715, 'l', 'f'),
(604, '10', '山西', '1007', '临汾', '100716', '安泽', 101100716, 'l', 'a'),
(605, '10', '山西', '1007', '临汾', '100717', '古县', 101100717, 'l', 'g'),
(606, '10', '山西', '1008', '运城', '100801', '运城', 101100801, 'y', 'y'),
(607, '10', '山西', '1008', '运城', '100802', '临猗', 101100802, 'y', 'l'),
(608, '10', '山西', '1008', '运城', '100803', '稷山', 101100803, 'y', 'j'),
(609, '10', '山西', '1008', '运城', '100804', '万荣', 101100804, 'y', 'w'),
(610, '10', '山西', '1008', '运城', '100805', '河津', 101100805, 'y', 'h'),
(611, '10', '山西', '1008', '运城', '100806', '新绛', 101100806, 'y', 'x'),
(612, '10', '山西', '1008', '运城', '100807', '绛县', 101100807, 'y', 'j'),
(613, '10', '山西', '1008', '运城', '100808', '闻喜', 101100808, 'y', 'w'),
(614, '10', '山西', '1008', '运城', '100809', '垣曲', 101100809, 'y', 'y'),
(615, '10', '山西', '1008', '运城', '100810', '永济', 101100810, 'y', 'y'),
(616, '10', '山西', '1008', '运城', '100811', '芮城', 101100811, 'y', 'r'),
(617, '10', '山西', '1008', '运城', '100812', '夏县', 101100812, 'y', 'x'),
(618, '10', '山西', '1008', '运城', '100813', '平陆', 101100813, 'y', 'p'),
(619, '10', '山西', '1009', '朔州', '100901', '朔州', 101100901, 's', 's'),
(620, '10', '山西', '1009', '朔州', '100902', '平鲁', 101100902, 's', 'p'),
(621, '10', '山西', '1009', '朔州', '100903', '山阴', 101100903, 's', 's'),
(622, '10', '山西', '1009', '朔州', '100904', '右玉', 101100904, 's', 'y'),
(623, '10', '山西', '1009', '朔州', '100905', '应县', 101100905, 's', 'y'),
(624, '10', '山西', '1009', '朔州', '100906', '怀仁', 101100906, 's', 'h'),
(625, '10', '山西', '1010', '忻州', '101001', '忻州', 101101001, 'x', 'x'),
(626, '10', '山西', '1010', '忻州', '101002', '定襄', 101101002, 'x', 'd'),
(627, '10', '山西', '1010', '忻州', '101003', '五台县', 101101003, 'x', 'w'),
(628, '10', '山西', '1010', '忻州', '101004', '河曲', 101101004, 'x', 'h'),
(629, '10', '山西', '1010', '忻州', '101005', '偏关', 101101005, 'x', 'p'),
(630, '10', '山西', '1010', '忻州', '101006', '神池', 101101006, 'x', 's'),
(631, '10', '山西', '1010', '忻州', '101007', '宁武', 101101007, 'x', 'n'),
(632, '10', '山西', '1010', '忻州', '101008', '代县', 101101008, 'x', 'd'),
(633, '10', '山西', '1010', '忻州', '101009', '繁峙', 101101009, 'x', 'f'),
(634, '10', '山西', '1010', '忻州', '101010', '五台山', 101101010, 'x', 'w'),
(635, '10', '山西', '1010', '忻州', '101011', '保德', 101101011, 'x', 'b'),
(636, '10', '山西', '1010', '忻州', '101012', '静乐', 101101012, 'x', 'j'),
(637, '10', '山西', '1010', '忻州', '101013', '岢岚', 101101013, 'x', 'k'),
(638, '10', '山西', '1010', '忻州', '101014', '五寨', 101101014, 'x', 'w'),
(639, '10', '山西', '1010', '忻州', '101015', '原平', 101101015, 'x', 'y'),
(640, '10', '山西', '1011', '吕梁', '101101', '吕梁', 101101100, 'l', 'l'),
(641, '10', '山西', '1011', '吕梁', '101102', '离石', 101101101, 'l', 'l'),
(642, '10', '山西', '1011', '吕梁', '101103', '临县', 101101102, 'l', 'l'),
(643, '10', '山西', '1011', '吕梁', '101104', '兴县', 101101103, 'l', 'x'),
(644, '10', '山西', '1011', '吕梁', '101105', '岚县', 101101104, 'l', 'l'),
(645, '10', '山西', '1011', '吕梁', '101106', '柳林', 101101105, 'l', 'l'),
(646, '10', '山西', '1011', '吕梁', '101107', '石楼', 101101106, 'l', 's'),
(647, '10', '山西', '1011', '吕梁', '101108', '方山', 101101107, 'l', 'f'),
(648, '10', '山西', '1011', '吕梁', '101109', '交口', 101101108, 'l', 'j'),
(649, '10', '山西', '1011', '吕梁', '101110', '中阳', 101101109, 'l', 'z'),
(650, '10', '山西', '1011', '吕梁', '101111', '孝义', 101101110, 'l', 'x'),
(651, '10', '山西', '1011', '吕梁', '101112', '汾阳', 101101111, 'l', 'f'),
(652, '10', '山西', '1011', '吕梁', '101113', '文水', 101101112, 'l', 'w'),
(653, '10', '山西', '1011', '吕梁', '101114', '交城', 101101113, 'l', 'j'),
(654, '11', '陕西', '1101', '西安', '110101', '西安', 101110101, 'x', 'x'),
(655, '11', '陕西', '1101', '西安', '110102', '长安', 101110102, 'x', 'c'),
(656, '11', '陕西', '1101', '西安', '110103', '临潼', 101110103, 'x', 'l'),
(657, '11', '陕西', '1101', '西安', '110104', '蓝田', 101110104, 'x', 'l'),
(658, '11', '陕西', '1101', '西安', '110105', '周至', 101110105, 'x', 'z'),
(659, '11', '陕西', '1101', '西安', '110106', '户县', 101110106, 'x', 'h'),
(660, '11', '陕西', '1101', '西安', '110107', '高陵', 101110107, 'x', 'g'),
(661, '11', '陕西', '1102', '咸阳', '110201', '咸阳', 101110200, 'x', 'x'),
(662, '11', '陕西', '1102', '咸阳', '110202', '三原', 101110201, 'x', 's'),
(663, '11', '陕西', '1102', '咸阳', '110203', '礼泉', 101110202, 'x', 'l'),
(664, '11', '陕西', '1102', '咸阳', '110204', '永寿', 101110203, 'x', 'y'),
(665, '11', '陕西', '1102', '咸阳', '110205', '淳化', 101110204, 'x', 'c'),
(666, '11', '陕西', '1102', '咸阳', '110206', '泾阳', 101110205, 'x', 'j'),
(667, '11', '陕西', '1102', '咸阳', '110207', '武功', 101110206, 'x', 'w'),
(668, '11', '陕西', '1102', '咸阳', '110208', '乾县', 101110207, 'x', 'q'),
(669, '11', '陕西', '1102', '咸阳', '110209', '彬县', 101110208, 'x', 'b'),
(670, '11', '陕西', '1102', '咸阳', '110210', '长武', 101110209, 'x', 'c'),
(671, '11', '陕西', '1102', '咸阳', '110211', '旬邑', 101110210, 'x', 'x'),
(672, '11', '陕西', '1102', '咸阳', '110212', '兴平', 101110211, 'x', 'x'),
(673, '11', '陕西', '1103', '延安', '110301', '延安', 101110300, 'y', 'y'),
(674, '11', '陕西', '1103', '延安', '110302', '延长', 101110301, 'y', 'y'),
(675, '11', '陕西', '1103', '延安', '110303', '延川', 101110302, 'y', 'y'),
(676, '11', '陕西', '1103', '延安', '110304', '子长', 101110303, 'y', 'z'),
(677, '11', '陕西', '1103', '延安', '110305', '宜川', 101110304, 'y', 'y'),
(678, '11', '陕西', '1103', '延安', '110306', '富县', 101110305, 'y', 'f'),
(679, '11', '陕西', '1103', '延安', '110307', '志丹', 101110306, 'y', 'z'),
(680, '11', '陕西', '1103', '延安', '110308', '安塞', 101110307, 'y', 'a'),
(681, '11', '陕西', '1103', '延安', '110309', '甘泉', 101110308, 'y', 'g'),
(682, '11', '陕西', '1103', '延安', '110310', '洛川', 101110309, 'y', 'l'),
(683, '11', '陕西', '1103', '延安', '110311', '黄陵', 101110310, 'y', 'h'),
(684, '11', '陕西', '1103', '延安', '110312', '黄龙', 101110311, 'y', 'h'),
(685, '11', '陕西', '1103', '延安', '110313', '吴起', 101110312, 'y', 'w'),
(686, '11', '陕西', '1104', '榆林', '110401', '榆林', 101110401, 'y', 'y'),
(687, '11', '陕西', '1104', '榆林', '110402', '府谷', 101110402, 'y', 'f'),
(688, '11', '陕西', '1104', '榆林', '110403', '神木', 101110403, 'y', 's'),
(689, '11', '陕西', '1104', '榆林', '110404', '佳县', 101110404, 'y', 'j'),
(690, '11', '陕西', '1104', '榆林', '110405', '定边', 101110405, 'y', 'd'),
(691, '11', '陕西', '1104', '榆林', '110406', '靖边', 101110406, 'y', 'j'),
(692, '11', '陕西', '1104', '榆林', '110407', '横山', 101110407, 'y', 'h'),
(693, '11', '陕西', '1104', '榆林', '110408', '米脂', 101110408, 'y', 'm'),
(694, '11', '陕西', '1104', '榆林', '110409', '子洲', 101110409, 'y', 'z'),
(695, '11', '陕西', '1104', '榆林', '110410', '绥德', 101110410, 'y', 's'),
(696, '11', '陕西', '1104', '榆林', '110411', '吴堡', 101110411, 'y', 'w'),
(697, '11', '陕西', '1104', '榆林', '110412', '清涧', 101110412, 'y', 'q'),
(698, '11', '陕西', '1104', '榆林', '110413', '榆阳', 101110413, 'y', 'y'),
(699, '11', '陕西', '1105', '渭南', '110501', '渭南', 101110501, 'w', 'w'),
(700, '11', '陕西', '1105', '渭南', '110502', '华县', 101110502, 'w', 'h'),
(701, '11', '陕西', '1105', '渭南', '110503', '潼关', 101110503, 'w', 't'),
(702, '11', '陕西', '1105', '渭南', '110504', '大荔', 101110504, 'w', 'd'),
(703, '11', '陕西', '1105', '渭南', '110505', '白水', 101110505, 'w', 'b'),
(704, '11', '陕西', '1105', '渭南', '110506', '富平', 101110506, 'w', 'f'),
(705, '11', '陕西', '1105', '渭南', '110507', '蒲城', 101110507, 'w', 'p'),
(706, '11', '陕西', '1105', '渭南', '110508', '澄城', 101110508, 'w', 'c'),
(707, '11', '陕西', '1105', '渭南', '110509', '合阳', 101110509, 'w', 'h'),
(708, '11', '陕西', '1105', '渭南', '110510', '韩城', 101110510, 'w', 'h'),
(709, '11', '陕西', '1105', '渭南', '110511', '华阴', 101110511, 'w', 'h'),
(710, '11', '陕西', '1106', '商洛', '110601', '商洛', 101110601, 's', 's'),
(711, '11', '陕西', '1106', '商洛', '110602', '洛南', 101110602, 's', 'l'),
(712, '11', '陕西', '1106', '商洛', '110603', '柞水', 101110603, 's', 'z'),
(713, '11', '陕西', '1106', '商洛', '110604', '商州', 101110604, 's', 's'),
(714, '11', '陕西', '1106', '商洛', '110605', '镇安', 101110605, 's', 'z'),
(715, '11', '陕西', '1106', '商洛', '110606', '丹凤', 101110606, 's', 'd'),
(716, '11', '陕西', '1106', '商洛', '110607', '商南', 101110607, 's', 's'),
(717, '11', '陕西', '1106', '商洛', '110608', '山阳', 101110608, 's', 's'),
(718, '11', '陕西', '1107', '安康', '110701', '安康', 101110701, 'a', 'a'),
(719, '11', '陕西', '1107', '安康', '110702', '紫阳', 101110702, 'a', 'z'),
(720, '11', '陕西', '1107', '安康', '110703', '石泉', 101110703, 'a', 's'),
(721, '11', '陕西', '1107', '安康', '110704', '汉阴', 101110704, 'a', 'h'),
(722, '11', '陕西', '1107', '安康', '110705', '旬阳', 101110705, 'a', 'x'),
(723, '11', '陕西', '1107', '安康', '110706', '岚皋', 101110706, 'a', 'l'),
(724, '11', '陕西', '1107', '安康', '110707', '平利', 101110707, 'a', 'p'),
(725, '11', '陕西', '1107', '安康', '110708', '白河', 101110708, 'a', 'b'),
(726, '11', '陕西', '1107', '安康', '110709', '镇坪', 101110709, 'a', 'z'),
(727, '11', '陕西', '1107', '安康', '110710', '宁陕', 101110710, 'a', 'n'),
(728, '11', '陕西', '1108', '汉中', '110801', '汉中', 101110801, 'h', 'h'),
(729, '11', '陕西', '1108', '汉中', '110802', '略阳', 101110802, 'h', 'l'),
(730, '11', '陕西', '1108', '汉中', '110803', '勉县', 101110803, 'h', 'm'),
(731, '11', '陕西', '1108', '汉中', '110804', '留坝', 101110804, 'h', 'l'),
(732, '11', '陕西', '1108', '汉中', '110805', '洋县', 101110805, 'h', 'y'),
(733, '11', '陕西', '1108', '汉中', '110806', '城固', 101110806, 'h', 'c'),
(734, '11', '陕西', '1108', '汉中', '110807', '西乡', 101110807, 'h', 'x'),
(735, '11', '陕西', '1108', '汉中', '110808', '佛坪', 101110808, 'h', 'f'),
(736, '11', '陕西', '1108', '汉中', '110809', '宁强', 101110809, 'h', 'n'),
(737, '11', '陕西', '1108', '汉中', '110810', '南郑', 101110810, 'h', 'n'),
(738, '11', '陕西', '1108', '汉中', '110811', '镇巴', 101110811, 'h', 'z'),
(739, '11', '陕西', '1109', '宝鸡', '110901', '宝鸡', 101110901, 'b', 'b'),
(740, '11', '陕西', '1109', '宝鸡', '110902', '千阳', 101110903, 'b', 'q'),
(741, '11', '陕西', '1109', '宝鸡', '110903', '麟游', 101110904, 'b', 'l'),
(742, '11', '陕西', '1109', '宝鸡', '110904', '岐山', 101110905, 'b', 'q'),
(743, '11', '陕西', '1109', '宝鸡', '110905', '凤翔', 101110906, 'b', 'f'),
(744, '11', '陕西', '1109', '宝鸡', '110906', '扶风', 101110907, 'b', 'f'),
(745, '11', '陕西', '1109', '宝鸡', '110907', '眉县', 101110908, 'b', 'm'),
(746, '11', '陕西', '1109', '宝鸡', '110908', '太白', 101110909, 'b', 't'),
(747, '11', '陕西', '1109', '宝鸡', '110909', '凤县', 101110910, 'b', 'f'),
(748, '11', '陕西', '1109', '宝鸡', '110910', '陇县', 101110911, 'b', 'l'),
(749, '11', '陕西', '1109', '宝鸡', '110911', '陈仓', 101110912, 'b', 'c'),
(750, '11', '陕西', '1110', '铜川', '111001', '铜川', 101111001, 't', 't'),
(751, '11', '陕西', '1110', '铜川', '111002', '耀县', 101111002, 't', 'y'),
(752, '11', '陕西', '1110', '铜川', '111003', '宜君', 101111003, 't', 'y'),
(753, '11', '陕西', '1110', '铜川', '111004', '耀州', 101111004, 't', 'y'),
(754, '11', '陕西', '1111', '杨凌', '111101', '杨凌', 101111101, 'y', 'y'),
(755, '12', '山东', '1201', '济南', '120101', '济南', 101120101, 'j', 'j'),
(756, '12', '山东', '1201', '济南', '120102', '长清', 101120102, 'j', 'c'),
(757, '12', '山东', '1201', '济南', '120103', '商河', 101120103, 'j', 's'),
(758, '12', '山东', '1201', '济南', '120104', '章丘', 101120104, 'j', 'z'),
(759, '12', '山东', '1201', '济南', '120105', '平阴', 101120105, 'j', 'p'),
(760, '12', '山东', '1201', '济南', '120106', '济阳', 101120106, 'j', 'j'),
(761, '12', '山东', '1202', '青岛', '120201', '青岛', 101120201, 'q', 'q'),
(762, '12', '山东', '1202', '青岛', '120202', '崂山', 101120202, 'q', 'l'),
(763, '12', '山东', '1202', '青岛', '120203', '即墨', 101120204, 'q', 'j'),
(764, '12', '山东', '1202', '青岛', '120204', '胶州', 101120205, 'q', 'j'),
(765, '12', '山东', '1202', '青岛', '120205', '胶南', 101120206, 'q', 'j'),
(766, '12', '山东', '1202', '青岛', '120206', '莱西', 101120207, 'q', 'l'),
(767, '12', '山东', '1202', '青岛', '120207', '平度', 101120208, 'q', 'p'),
(768, '12', '山东', '1203', '淄博', '120301', '淄博', 101120301, 'z', 'z'),
(769, '12', '山东', '1203', '淄博', '120302', '淄川', 101120302, 'z', 'z'),
(770, '12', '山东', '1203', '淄博', '120303', '博山', 101120303, 'z', 'b'),
(771, '12', '山东', '1203', '淄博', '120304', '高青', 101120304, 'z', 'g'),
(772, '12', '山东', '1203', '淄博', '120305', '周村', 101120305, 'z', 'z'),
(773, '12', '山东', '1203', '淄博', '120306', '沂源', 101120306, 'z', 'y'),
(774, '12', '山东', '1203', '淄博', '120307', '桓台', 101120307, 'z', 'h'),
(775, '12', '山东', '1203', '淄博', '120308', '临淄', 101120308, 'z', 'l'),
(776, '12', '山东', '1204', '德州', '120401', '德州', 101120401, 'd', 'd'),
(777, '12', '山东', '1204', '德州', '120402', '武城', 101120402, 'd', 'w'),
(778, '12', '山东', '1204', '德州', '120403', '临邑', 101120403, 'd', 'l'),
(779, '12', '山东', '1204', '德州', '120404', '陵县', 101120404, 'd', 'l'),
(780, '12', '山东', '1204', '德州', '120405', '齐河', 101120405, 'd', 'q'),
(781, '12', '山东', '1204', '德州', '120406', '乐陵', 101120406, 'd', 'l'),
(782, '12', '山东', '1204', '德州', '120407', '庆云', 101120407, 'd', 'q'),
(783, '12', '山东', '1204', '德州', '120408', '平原', 101120408, 'd', 'p'),
(784, '12', '山东', '1204', '德州', '120409', '宁津', 101120409, 'd', 'n'),
(785, '12', '山东', '1204', '德州', '120410', '夏津', 101120410, 'd', 'x'),
(786, '12', '山东', '1204', '德州', '120411', '禹城', 101120411, 'd', 'y'),
(787, '12', '山东', '1205', '烟台', '120501', '烟台', 101120501, 'y', 'y'),
(788, '12', '山东', '1205', '烟台', '120502', '莱州', 101120502, 'y', 'l'),
(789, '12', '山东', '1205', '烟台', '120503', '长岛', 101120503, 'y', 'c'),
(790, '12', '山东', '1205', '烟台', '120504', '蓬莱', 101120504, 'y', 'p'),
(791, '12', '山东', '1205', '烟台', '120505', '龙口', 101120505, 'y', 'l'),
(792, '12', '山东', '1205', '烟台', '120506', '招远', 101120506, 'y', 'z'),
(793, '12', '山东', '1205', '烟台', '120507', '栖霞', 101120507, 'y', 'q'),
(794, '12', '山东', '1205', '烟台', '120508', '福山', 101120508, 'y', 'f'),
(795, '12', '山东', '1205', '烟台', '120509', '牟平', 101120509, 'y', 'm'),
(796, '12', '山东', '1205', '烟台', '120510', '莱阳', 101120510, 'y', 'l'),
(797, '12', '山东', '1205', '烟台', '120511', '海阳', 101120511, 'y', 'h'),
(798, '12', '山东', '1206', '潍坊', '120601', '潍坊', 101120601, 'w', 'w'),
(799, '12', '山东', '1206', '潍坊', '120602', '青州', 101120602, 'w', 'q'),
(800, '12', '山东', '1206', '潍坊', '120603', '寿光', 101120603, 'w', 's'),
(801, '12', '山东', '1206', '潍坊', '120604', '临朐', 101120604, 'w', 'l'),
(802, '12', '山东', '1206', '潍坊', '120605', '昌乐', 101120605, 'w', 'c'),
(803, '12', '山东', '1206', '潍坊', '120606', '昌邑', 101120606, 'w', 'c'),
(804, '12', '山东', '1206', '潍坊', '120607', '安丘', 101120607, 'w', 'a'),
(805, '12', '山东', '1206', '潍坊', '120608', '高密', 101120608, 'w', 'g'),
(806, '12', '山东', '1206', '潍坊', '120609', '诸城', 101120609, 'w', 'z'),
(807, '12', '山东', '1207', '济宁', '120701', '济宁', 101120701, 'j', 'j'),
(808, '12', '山东', '1207', '济宁', '120702', '嘉祥', 101120702, 'j', 'j'),
(809, '12', '山东', '1207', '济宁', '120703', '微山', 101120703, 'j', 'w'),
(810, '12', '山东', '1207', '济宁', '120704', '鱼台', 101120704, 'j', 'y'),
(811, '12', '山东', '1207', '济宁', '120705', '兖州', 101120705, 'j', 'y'),
(812, '12', '山东', '1207', '济宁', '120706', '金乡', 101120706, 'j', 'j'),
(813, '12', '山东', '1207', '济宁', '120707', '汶上', 101120707, 'j', 'w'),
(814, '12', '山东', '1207', '济宁', '120708', '泗水', 101120708, 'j', 's'),
(815, '12', '山东', '1207', '济宁', '120709', '梁山', 101120709, 'j', 'l'),
(816, '12', '山东', '1207', '济宁', '120710', '曲阜', 101120710, 'j', 'q'),
(817, '12', '山东', '1207', '济宁', '120711', '邹城', 101120711, 'j', 'z'),
(818, '12', '山东', '1208', '泰安', '120801', '泰安', 101120801, 't', 't'),
(819, '12', '山东', '1208', '泰安', '120802', '新泰', 101120802, 't', 'x'),
(820, '12', '山东', '1208', '泰安', '120803', '肥城', 101120804, 't', 'f'),
(821, '12', '山东', '1208', '泰安', '120804', '东平', 101120805, 't', 'd'),
(822, '12', '山东', '1208', '泰安', '120805', '宁阳', 101120806, 't', 'n'),
(823, '12', '山东', '1209', '临沂', '120901', '临沂', 101120901, 'l', 'l'),
(824, '12', '山东', '1209', '临沂', '120902', '莒南', 101120902, 'l', 'j'),
(825, '12', '山东', '1209', '临沂', '120903', '沂南', 101120903, 'l', 'y'),
(826, '12', '山东', '1209', '临沂', '120904', '苍山', 101120904, 'l', 'c'),
(827, '12', '山东', '1209', '临沂', '120905', '临沭', 101120905, 'l', 'l'),
(828, '12', '山东', '1209', '临沂', '120906', '郯城', 101120906, 'l', 't'),
(829, '12', '山东', '1209', '临沂', '120907', '蒙阴', 101120907, 'l', 'm'),
(830, '12', '山东', '1209', '临沂', '120908', '平邑', 101120908, 'l', 'p'),
(831, '12', '山东', '1209', '临沂', '120909', '费县', 101120909, 'l', 'f'),
(832, '12', '山东', '1209', '临沂', '120910', '沂水', 101120910, 'l', 'y'),
(833, '12', '山东', '1210', '菏泽', '121001', '菏泽', 101121001, 'h', 'h'),
(834, '12', '山东', '1210', '菏泽', '121002', '鄄城', 101121002, 'h', 'j'),
(835, '12', '山东', '1210', '菏泽', '121003', '郓城', 101121003, 'h', 'y'),
(836, '12', '山东', '1210', '菏泽', '121004', '东明', 101121004, 'h', 'd'),
(837, '12', '山东', '1210', '菏泽', '121005', '定陶', 101121005, 'h', 'd'),
(838, '12', '山东', '1210', '菏泽', '121006', '巨野', 101121006, 'h', 'j'),
(839, '12', '山东', '1210', '菏泽', '121007', '曹县', 101121007, 'h', 'c'),
(840, '12', '山东', '1210', '菏泽', '121008', '成武', 101121008, 'h', 'c'),
(841, '12', '山东', '1210', '菏泽', '121009', '单县', 101121009, 'h', 'd'),
(842, '12', '山东', '1211', '滨州', '121101', '滨州', 101121101, 'b', 'b'),
(843, '12', '山东', '1211', '滨州', '121102', '博兴', 101121102, 'b', 'b'),
(844, '12', '山东', '1211', '滨州', '121103', '无棣', 101121103, 'b', 'w'),
(845, '12', '山东', '1211', '滨州', '121104', '阳信', 101121104, 'b', 'y'),
(846, '12', '山东', '1211', '滨州', '121105', '惠民', 101121105, 'b', 'h'),
(847, '12', '山东', '1211', '滨州', '121106', '沾化', 101121106, 'b', 'z'),
(848, '12', '山东', '1211', '滨州', '121107', '邹平', 101121107, 'b', 'z'),
(849, '12', '山东', '1212', '东营', '121201', '东营', 101121201, 'd', 'd'),
(850, '12', '山东', '1212', '东营', '121202', '河口', 101121202, 'd', 'h'),
(851, '12', '山东', '1212', '东营', '121203', '垦利', 101121203, 'd', 'k'),
(852, '12', '山东', '1212', '东营', '121204', '利津', 101121204, 'd', 'l'),
(853, '12', '山东', '1212', '东营', '121205', '广饶', 101121205, 'd', 'g'),
(854, '12', '山东', '1213', '威海', '121301', '威海', 101121301, 'w', 'w'),
(855, '12', '山东', '1213', '威海', '121302', '文登', 101121302, 'w', 'w'),
(856, '12', '山东', '1213', '威海', '121303', '荣成', 101121303, 'w', 'r'),
(857, '12', '山东', '1213', '威海', '121304', '乳山', 101121304, 'w', 'r'),
(858, '12', '山东', '1213', '威海', '121305', '成山头', 101121305, 'w', 'c'),
(859, '12', '山东', '1213', '威海', '121306', '石岛', 101121306, 'w', 's'),
(860, '12', '山东', '1214', '枣庄', '121401', '枣庄', 101121401, 'z', 'z'),
(861, '12', '山东', '1214', '枣庄', '121402', '薛城', 101121402, 'z', 'x'),
(862, '12', '山东', '1214', '枣庄', '121403', '峄城', 101121403, 'z', 'y'),
(863, '12', '山东', '1214', '枣庄', '121404', '台儿庄', 101121404, 'z', 't'),
(864, '12', '山东', '1214', '枣庄', '121405', '滕州', 101121405, 'z', 't'),
(865, '12', '山东', '1215', '日照', '121501', '日照', 101121501, 'r', 'r'),
(866, '12', '山东', '1215', '日照', '121502', '五莲', 101121502, 'r', 'w'),
(867, '12', '山东', '1215', '日照', '121503', '莒县', 101121503, 'r', 'j'),
(868, '12', '山东', '1216', '莱芜', '121601', '莱芜', 101121601, 'l', 'l'),
(869, '12', '山东', '1217', '聊城', '121701', '聊城', 101121701, 'l', 'l'),
(870, '12', '山东', '1217', '聊城', '121702', '冠县', 101121702, 'l', 'g'),
(871, '12', '山东', '1217', '聊城', '121703', '阳谷', 101121703, 'l', 'y'),
(872, '12', '山东', '1217', '聊城', '121704', '高唐', 101121704, 'l', 'g'),
(873, '12', '山东', '1217', '聊城', '121705', '茌平', 101121705, 'l', 'c'),
(874, '12', '山东', '1217', '聊城', '121706', '东阿', 101121706, 'l', 'd'),
(875, '12', '山东', '1217', '聊城', '121707', '临清', 101121707, 'l', 'l'),
(876, '12', '山东', '1217', '聊城', '121708', '莘县', 101121709, 'l', 's'),
(877, '13', '新疆', '1301', '乌鲁木齐', '130101', '乌鲁木齐', 101130101, 'w', 'w'),
(878, '13', '新疆', '1301', '乌鲁木齐', '130102', '小渠子', 101130103, 'w', 'x'),
(879, '13', '新疆', '1301', '乌鲁木齐', '130103', '达坂城', 101130105, 'w', 'd'),
(880, '13', '新疆', '1301', '乌鲁木齐', '130104', '乌鲁木齐牧试站', 101130108, 'w', 'w'),
(881, '13', '新疆', '1301', '乌鲁木齐', '130105', '天池', 101130109, 'w', 't'),
(882, '13', '新疆', '1301', '乌鲁木齐', '130106', '白杨沟', 101130110, 'w', 'b'),
(883, '13', '新疆', '1302', '克拉玛依', '130201', '克拉玛依', 101130201, 'k', 'k'),
(884, '13', '新疆', '1302', '克拉玛依', '130202', '乌尔禾', 101130202, 'k', 'w'),
(885, '13', '新疆', '1302', '克拉玛依', '130203', '白碱滩', 101130203, 'k', 'b'),
(886, '13', '新疆', '1303', '石河子', '130301', '石河子', 101130301, 's', 's'),
(887, '13', '新疆', '1303', '石河子', '130302', '炮台', 101130302, 's', 'p'),
(888, '13', '新疆', '1303', '石河子', '130303', '莫索湾', 101130303, 's', 'm'),
(889, '13', '新疆', '1304', '昌吉', '130401', '昌吉', 101130401, 'c', 'c'),
(890, '13', '新疆', '1304', '昌吉', '130402', '呼图壁', 101130402, 'c', 'h'),
(891, '13', '新疆', '1304', '昌吉', '130403', '米泉', 101130403, 'c', 'm'),
(892, '13', '新疆', '1304', '昌吉', '130404', '阜康', 101130404, 'c', 'f'),
(893, '13', '新疆', '1304', '昌吉', '130405', '吉木萨尔', 101130405, 'c', 'j'),
(894, '13', '新疆', '1304', '昌吉', '130406', '奇台', 101130406, 'c', 'q'),
(895, '13', '新疆', '1304', '昌吉', '130407', '玛纳斯', 101130407, 'c', 'm'),
(896, '13', '新疆', '1304', '昌吉', '130408', '木垒', 101130408, 'c', 'm'),
(897, '13', '新疆', '1304', '昌吉', '130409', '蔡家湖', 101130409, 'c', 'c'),
(898, '13', '新疆', '1305', '吐鲁番', '130501', '吐鲁番', 101130501, 't', 't'),
(899, '13', '新疆', '1305', '吐鲁番', '130502', '托克逊', 101130502, 't', 't'),
(900, '13', '新疆', '1305', '吐鲁番', '130503', '鄯善', 101130504, 't', 'p'),
(901, '13', '新疆', '1306', '巴音郭楞', '130601', '巴音郭楞', 101130601, 'b', 'b'),
(902, '13', '新疆', '1306', '巴音郭楞', '130602', '轮台', 101130602, 'b', 'l'),
(903, '13', '新疆', '1306', '巴音郭楞', '130603', '尉犁', 101130603, 'b', 'w'),
(904, '13', '新疆', '1306', '巴音郭楞', '130604', '若羌', 101130604, 'b', 'r'),
(905, '13', '新疆', '1306', '巴音郭楞', '130605', '且末', 101130605, 'b', 'q'),
(906, '13', '新疆', '1306', '巴音郭楞', '130606', '和静', 101130606, 'b', 'h'),
(907, '13', '新疆', '1306', '巴音郭楞', '130607', '焉耆', 101130607, 'b', 'y'),
(908, '13', '新疆', '1306', '巴音郭楞', '130608', '和硕', 101130608, 'b', 'h'),
(909, '13', '新疆', '1306', '巴音郭楞', '130609', '巴音布鲁克', 101130610, 'b', 'b'),
(910, '13', '新疆', '1306', '巴音郭楞', '130610', '铁干里克', 101130611, 'b', 't'),
(911, '13', '新疆', '1306', '巴音郭楞', '130611', '博湖', 101130612, 'b', 'b'),
(912, '13', '新疆', '1306', '巴音郭楞', '130612', '塔中', 101130613, 'b', 't'),
(913, '13', '新疆', '1306', '巴音郭楞', '130613', '巴仑台', 101130614, 'b', 'b'),
(914, '13', '新疆', '1307', '阿拉尔', '130701', '阿拉尔', 101130701, 'a', 'a'),
(915, '13', '新疆', '1308', '阿克苏', '130801', '阿克苏', 101130801, 'a', 'a'),
(916, '13', '新疆', '1308', '阿克苏', '130802', '乌什', 101130802, 'a', 'w'),
(917, '13', '新疆', '1308', '阿克苏', '130803', '温宿', 101130803, 'a', 'w'),
(918, '13', '新疆', '1308', '阿克苏', '130804', '拜城', 101130804, 'a', 'b'),
(919, '13', '新疆', '1308', '阿克苏', '130805', '新和', 101130805, 'a', 'x'),
(920, '13', '新疆', '1308', '阿克苏', '130806', '沙雅', 101130806, 'a', 's'),
(921, '13', '新疆', '1308', '阿克苏', '130807', '库车', 101130807, 'a', 'k'),
(922, '13', '新疆', '1308', '阿克苏', '130808', '柯坪', 101130808, 'a', 'k'),
(923, '13', '新疆', '1308', '阿克苏', '130809', '阿瓦提', 101130809, 'a', 'a'),
(924, '13', '新疆', '1309', '喀什', '130901', '喀什', 101130901, 'k', 'k'),
(925, '13', '新疆', '1309', '喀什', '130902', '英吉沙', 101130902, 'k', 'y'),
(926, '13', '新疆', '1309', '喀什', '130903', '塔什库尔干', 101130903, 'k', 't'),
(927, '13', '新疆', '1309', '喀什', '130904', '麦盖提', 101130904, 'k', 'm'),
(928, '13', '新疆', '1309', '喀什', '130905', '莎车', 101130905, 'k', 's'),
(929, '13', '新疆', '1309', '喀什', '130906', '叶城', 101130906, 'k', 'y'),
(930, '13', '新疆', '1309', '喀什', '130907', '泽普', 101130907, 'k', 'z'),
(931, '13', '新疆', '1309', '喀什', '130908', '巴楚', 101130908, 'k', 'b'),
(932, '13', '新疆', '1309', '喀什', '130909', '岳普湖', 101130909, 'k', 'y'),
(933, '13', '新疆', '1309', '喀什', '130910', '伽师', 101130910, 'k', 'j'),
(934, '13', '新疆', '1309', '喀什', '130911', '疏附', 101130911, 'k', 's'),
(935, '13', '新疆', '1309', '喀什', '130912', '疏勒', 101130912, 'k', 's'),
(936, '13', '新疆', '1310', '伊犁', '131001', '伊犁', 101131001, 'y', 'y'),
(937, '13', '新疆', '1310', '伊犁', '131002', '察布查尔', 101131002, 'y', 'c'),
(938, '13', '新疆', '1310', '伊犁', '131003', '尼勒克', 101131003, 'y', 'n'),
(939, '13', '新疆', '1310', '伊犁', '131004', '伊宁县', 101131004, 'y', 'y'),
(940, '13', '新疆', '1310', '伊犁', '131005', '巩留', 101131005, 'y', 'g'),
(941, '13', '新疆', '1310', '伊犁', '131006', '新源', 101131006, 'y', 'x'),
(942, '13', '新疆', '1310', '伊犁', '131007', '昭苏', 101131007, 'y', 'z'),
(943, '13', '新疆', '1310', '伊犁', '131008', '特克斯', 101131008, 'y', 't'),
(944, '13', '新疆', '1310', '伊犁', '131009', '霍城', 101131009, 'y', 'h'),
(945, '13', '新疆', '1310', '伊犁', '131010', '霍尔果斯', 101131010, 'y', 'h'),
(946, '13', '新疆', '1310', '伊犁', '131011', '奎屯', 101131011, 'y', 'k'),
(947, '13', '新疆', '1311', '塔城', '131101', '塔城', 101131101, 't', 't'),
(948, '13', '新疆', '1311', '塔城', '131102', '裕民', 101131102, 't', 'y'),
(949, '13', '新疆', '1311', '塔城', '131103', '额敏', 101131103, 't', 'e'),
(950, '13', '新疆', '1311', '塔城', '131104', '和布克赛尔', 101131104, 't', 'h'),
(951, '13', '新疆', '1311', '塔城', '131105', '托里', 101131105, 't', 't'),
(952, '13', '新疆', '1311', '塔城', '131106', '乌苏', 101131106, 't', 'w'),
(953, '13', '新疆', '1311', '塔城', '131107', '沙湾', 101131107, 't', 's'),
(954, '13', '新疆', '1312', '哈密', '131201', '哈密', 101131201, 'h', 'h'),
(955, '13', '新疆', '1312', '哈密', '131202', '巴里坤', 101131203, 'h', 'b'),
(956, '13', '新疆', '1312', '哈密', '131203', '伊吾', 101131204, 'h', 'y'),
(957, '13', '新疆', '1313', '和田', '131301', '和田', 101131301, 'h', 'h'),
(958, '13', '新疆', '1313', '和田', '131302', '皮山', 101131302, 'h', 'p'),
(959, '13', '新疆', '1313', '和田', '131303', '策勒', 101131303, 'h', 'c'),
(960, '13', '新疆', '1313', '和田', '131304', '墨玉', 101131304, 'h', 'm'),
(961, '13', '新疆', '1313', '和田', '131305', '洛浦', 101131305, 'h', 'l'),
(962, '13', '新疆', '1313', '和田', '131306', '民丰', 101131306, 'h', 'm'),
(963, '13', '新疆', '1313', '和田', '131307', '于田', 101131307, 'h', 'y'),
(964, '13', '新疆', '1314', '阿勒泰', '131401', '阿勒泰', 101131401, 'a', 'a'),
(965, '13', '新疆', '1314', '阿勒泰', '131402', '哈巴河', 101131402, 'a', 'h'),
(966, '13', '新疆', '1314', '阿勒泰', '131403', '吉木乃', 101131405, 'a', 'j'),
(967, '13', '新疆', '1314', '阿勒泰', '131404', '布尔津', 101131406, 'a', 'b'),
(968, '13', '新疆', '1314', '阿勒泰', '131405', '福海', 101131407, 'a', 'f'),
(969, '13', '新疆', '1314', '阿勒泰', '131406', '富蕴', 101131408, 'a', 'f'),
(970, '13', '新疆', '1314', '阿勒泰', '131407', '青河', 101131409, 'a', 'q'),
(971, '13', '新疆', '1315', '克州', '131501', '克州', 101131501, 'k', 'k'),
(972, '13', '新疆', '1315', '克州', '131502', '乌恰', 101131502, 'k', 'w'),
(973, '13', '新疆', '1315', '克州', '131503', '阿克陶', 101131503, 'k', 'a'),
(974, '13', '新疆', '1315', '克州', '131504', '阿合奇', 101131504, 'k', 'a'),
(975, '13', '新疆', '1316', '博尔塔拉', '131601', '博尔塔拉', 101131601, 'b', 'b'),
(976, '13', '新疆', '1316', '博尔塔拉', '131602', '温泉', 101131602, 'b', 'w'),
(977, '13', '新疆', '1316', '博尔塔拉', '131603', '精河', 101131603, 'b', 'j'),
(978, '13', '新疆', '1316', '博尔塔拉', '131604', '阿拉山口', 101131606, 'b', 'a'),
(979, '14', '西藏', '1401', '拉萨', '140101', '拉萨', 101140101, 'l', 'l'),
(980, '14', '西藏', '1401', '拉萨', '140102', '当雄', 101140102, 'l', 'd'),
(981, '14', '西藏', '1401', '拉萨', '140103', '尼木', 101140103, 'l', 'n'),
(982, '14', '西藏', '1401', '拉萨', '140104', '林周', 101140104, 'l', 'l'),
(983, '14', '西藏', '1401', '拉萨', '140105', '堆龙德庆', 101140105, 'l', 'd'),
(984, '14', '西藏', '1401', '拉萨', '140106', '曲水', 101140106, 'l', 'q'),
(985, '14', '西藏', '1401', '拉萨', '140107', '达孜', 101140107, 'l', 'd'),
(986, '14', '西藏', '1401', '拉萨', '140108', '墨竹工卡', 101140108, 'l', 'm'),
(987, '14', '西藏', '1402', '日喀则', '140201', '日喀则', 101140201, 'r', 'r'),
(988, '14', '西藏', '1402', '日喀则', '140202', '拉孜', 101140202, 'r', 'l'),
(989, '14', '西藏', '1402', '日喀则', '140203', '南木林', 101140203, 'r', 'n'),
(990, '14', '西藏', '1402', '日喀则', '140204', '聂拉木', 101140204, 'r', 'n'),
(991, '14', '西藏', '1402', '日喀则', '140205', '定日', 101140205, 'r', 'd'),
(992, '14', '西藏', '1402', '日喀则', '140206', '江孜', 101140206, 'r', 'j'),
(993, '14', '西藏', '1402', '日喀则', '140207', '帕里', 101140207, 'r', 'p'),
(994, '14', '西藏', '1402', '日喀则', '140208', '仲巴', 101140208, 'r', 'z'),
(995, '14', '西藏', '1402', '日喀则', '140209', '萨嘎', 101140209, 'r', 's'),
(996, '14', '西藏', '1402', '日喀则', '140210', '吉隆', 101140210, 'r', 'j'),
(997, '14', '西藏', '1402', '日喀则', '140211', '昂仁', 101140211, 'r', 'a'),
(998, '14', '西藏', '1402', '日喀则', '140212', '定结', 101140212, 'r', 'd'),
(999, '14', '西藏', '1402', '日喀则', '140213', '萨迦', 101140213, 'r', 's'),
(1000, '14', '西藏', '1402', '日喀则', '140214', '谢通门', 101140214, 'r', 'x'),
(1001, '14', '西藏', '1402', '日喀则', '140215', '岗巴', 101140216, 'r', 'g'),
(1002, '14', '西藏', '1402', '日喀则', '140216', '白朗', 101140217, 'r', 'b'),
(1003, '14', '西藏', '1402', '日喀则', '140217', '亚东', 101140218, 'r', 'y'),
(1004, '14', '西藏', '1402', '日喀则', '140218', '康马', 101140219, 'r', 'k'),
(1005, '14', '西藏', '1402', '日喀则', '140219', '仁布', 101140220, 'r', 'r'),
(1006, '14', '西藏', '1403', '山南', '140301', '山南', 101140301, 's', 's'),
(1007, '14', '西藏', '1403', '山南', '140302', '贡嘎', 101140302, 's', 'g'),
(1008, '14', '西藏', '1403', '山南', '140303', '扎囊', 101140303, 's', 'z'),
(1009, '14', '西藏', '1403', '山南', '140304', '加查', 101140304, 's', 'j'),
(1010, '14', '西藏', '1403', '山南', '140305', '浪卡子', 101140305, 's', 'l'),
(1011, '14', '西藏', '1403', '山南', '140306', '错那', 101140306, 's', 'c'),
(1012, '14', '西藏', '1403', '山南', '140307', '隆子', 101140307, 's', 'l'),
(1013, '14', '西藏', '1403', '山南', '140308', '泽当', 101140308, 's', 'z'),
(1014, '14', '西藏', '1403', '山南', '140309', '乃东', 101140309, 's', 'n'),
(1015, '14', '西藏', '1403', '山南', '140310', '桑日', 101140310, 's', 's'),
(1016, '14', '西藏', '1403', '山南', '140311', '洛扎', 101140311, 's', 'l'),
(1017, '14', '西藏', '1403', '山南', '140312', '措美', 101140312, 's', 'c'),
(1018, '14', '西藏', '1403', '山南', '140313', '琼结', 101140313, 's', 'q'),
(1019, '14', '西藏', '1403', '山南', '140314', '曲松', 101140314, 's', 'q'),
(1020, '14', '西藏', '1404', '林芝', '140401', '林芝', 101140401, 'l', 'l'),
(1021, '14', '西藏', '1404', '林芝', '140402', '波密', 101140402, 'l', 'b'),
(1022, '14', '西藏', '1404', '林芝', '140403', '米林', 101140403, 'l', 'm'),
(1023, '14', '西藏', '1404', '林芝', '140404', '察隅', 101140404, 'l', 'c'),
(1024, '14', '西藏', '1404', '林芝', '140405', '工布江达', 101140405, 'l', 'g'),
(1025, '14', '西藏', '1404', '林芝', '140406', '朗县', 101140406, 'l', 'l'),
(1026, '14', '西藏', '1404', '林芝', '140407', '墨脱', 101140407, 'l', 'm'),
(1027, '14', '西藏', '1405', '昌都', '140501', '昌都', 101140501, 'c', 'c'),
(1028, '14', '西藏', '1405', '昌都', '140502', '丁青', 101140502, 'c', 'd'),
(1029, '14', '西藏', '1405', '昌都', '140503', '边坝', 101140503, 'c', 'b'),
(1030, '14', '西藏', '1405', '昌都', '140504', '洛隆', 101140504, 'c', 'l'),
(1031, '14', '西藏', '1405', '昌都', '140505', '左贡', 101140505, 'c', 'z'),
(1032, '14', '西藏', '1405', '昌都', '140506', '芒康', 101140506, 'c', 'm'),
(1033, '14', '西藏', '1405', '昌都', '140507', '类乌齐', 101140507, 'c', 'l'),
(1034, '14', '西藏', '1405', '昌都', '140508', '八宿', 101140508, 'c', 'b'),
(1035, '14', '西藏', '1405', '昌都', '140509', '江达', 101140509, 'c', 'j'),
(1036, '14', '西藏', '1405', '昌都', '140510', '察雅', 101140510, 'c', 'c'),
(1037, '14', '西藏', '1405', '昌都', '140511', '贡觉', 101140511, 'c', 'g'),
(1038, '14', '西藏', '1406', '那曲', '140601', '那曲', 101140601, 'n', 'n'),
(1039, '14', '西藏', '1406', '那曲', '140602', '尼玛', 101140602, 'n', 'n'),
(1040, '14', '西藏', '1406', '那曲', '140603', '嘉黎', 101140603, 'n', 'j'),
(1041, '14', '西藏', '1406', '那曲', '140604', '班戈', 101140604, 'n', 'b'),
(1042, '14', '西藏', '1406', '那曲', '140605', '安多', 101140605, 'n', 'a'),
(1043, '14', '西藏', '1406', '那曲', '140606', '索县', 101140606, 'n', 's'),
(1044, '14', '西藏', '1406', '那曲', '140607', '聂荣', 101140607, 'n', 'n'),
(1045, '14', '西藏', '1406', '那曲', '140608', '巴青', 101140608, 'n', 'b'),
(1046, '14', '西藏', '1406', '那曲', '140609', '比如', 101140609, 'n', 'b'),
(1047, '14', '西藏', '1406', '那曲', '140610', '双湖', 101140610, 'n', 's'),
(1048, '14', '西藏', '1407', '阿里', '140701', '阿里', 101140701, 'a', 'a'),
(1049, '14', '西藏', '1407', '阿里', '140702', '改则', 101140702, 'a', 'g'),
(1050, '14', '西藏', '1407', '阿里', '140703', '申扎', 101140703, 'a', 's'),
(1051, '14', '西藏', '1407', '阿里', '140704', '狮泉河', 101140704, 'a', 's'),
(1052, '14', '西藏', '1407', '阿里', '140705', '普兰', 101140705, 'a', 'p'),
(1053, '14', '西藏', '1407', '阿里', '140706', '札达', 101140706, 'a', 'z'),
(1054, '14', '西藏', '1407', '阿里', '140707', '噶尔', 101140707, 'a', 'g'),
(1055, '14', '西藏', '1407', '阿里', '140708', '日土', 101140708, 'a', 'r'),
(1056, '14', '西藏', '1407', '阿里', '140709', '革吉', 101140709, 'a', 'g'),
(1057, '14', '西藏', '1407', '阿里', '140710', '措勤', 101140710, 'a', 'c'),
(1058, '15', '青海', '1501', '西宁', '150101', '西宁', 101150101, 'x', 'x'),
(1059, '15', '青海', '1501', '西宁', '150102', '大通', 101150102, 'x', 'd'),
(1060, '15', '青海', '1501', '西宁', '150103', '湟源', 101150103, 'x', 'h'),
(1061, '15', '青海', '1501', '西宁', '150104', '湟中', 101150104, 'x', 'h'),
(1062, '15', '青海', '1502', '海东', '150201', '海东', 101150201, 'h', 'h'),
(1063, '15', '青海', '1502', '海东', '150202', '乐都', 101150202, 'h', 'l'),
(1064, '15', '青海', '1502', '海东', '150203', '民和', 101150203, 'h', 'm'),
(1065, '15', '青海', '1502', '海东', '150204', '互助', 101150204, 'h', 'h'),
(1066, '15', '青海', '1502', '海东', '150205', '化隆', 101150205, 'h', 'h'),
(1067, '15', '青海', '1502', '海东', '150206', '循化', 101150206, 'h', 'x'),
(1068, '15', '青海', '1502', '海东', '150207', '冷湖', 101150207, 'h', 'l'),
(1069, '15', '青海', '1502', '海东', '150208', '平安', 101150208, 'h', 'p'),
(1070, '15', '青海', '1503', '黄南', '150301', '黄南', 101150301, 'h', 'h'),
(1071, '15', '青海', '1503', '黄南', '150302', '尖扎', 101150302, 'h', 'j'),
(1072, '15', '青海', '1503', '黄南', '150303', '泽库', 101150303, 'h', 'z'),
(1073, '15', '青海', '1503', '黄南', '150304', '河南', 101150304, 'h', 'h'),
(1074, '15', '青海', '1503', '黄南', '150305', '同仁', 101150305, 'h', 't'),
(1075, '15', '青海', '1504', '海南', '150401', '海南', 101150401, 'h', 'h'),
(1076, '15', '青海', '1504', '海南', '150402', '贵德', 101150404, 'h', 'g'),
(1077, '15', '青海', '1504', '海南', '150403', '兴海', 101150406, 'h', 'x'),
(1078, '15', '青海', '1504', '海南', '150404', '贵南', 101150407, 'h', 'g'),
(1079, '15', '青海', '1504', '海南', '150405', '同德', 101150408, 'h', 't'),
(1080, '15', '青海', '1504', '海南', '150406', '共和', 101150409, 'h', 'g'),
(1081, '15', '青海', '1505', '果洛', '150501', '果洛', 101150501, 'g', 'g'),
(1082, '15', '青海', '1505', '果洛', '150502', '班玛', 101150502, 'g', 'b'),
(1083, '15', '青海', '1505', '果洛', '150503', '甘德', 101150503, 'g', 'g'),
(1084, '15', '青海', '1505', '果洛', '150504', '达日', 101150504, 'g', 'd'),
(1085, '15', '青海', '1505', '果洛', '150505', '久治', 101150505, 'g', 'j'),
(1086, '15', '青海', '1505', '果洛', '150506', '玛多', 101150506, 'g', 'm'),
(1087, '15', '青海', '1505', '果洛', '150507', '多县', 101150507, 'g', 'd'),
(1088, '15', '青海', '1505', '果洛', '150508', '玛沁', 101150508, 'g', 'm'),
(1089, '15', '青海', '1506', '玉树', '150601', '玉树', 101150601, 'y', 'y'),
(1090, '15', '青海', '1506', '玉树', '150602', '称多', 101150602, 'y', 'c'),
(1091, '15', '青海', '1506', '玉树', '150603', '治多', 101150603, 'y', 'z'),
(1092, '15', '青海', '1506', '玉树', '150604', '杂多', 101150604, 'y', 'z'),
(1093, '15', '青海', '1506', '玉树', '150605', '囊谦', 101150605, 'y', 'n'),
(1094, '15', '青海', '1506', '玉树', '150606', '曲麻莱', 101150606, 'y', 'q'),
(1095, '15', '青海', '1507', '海西', '150701', '海西', 101150701, 'h', 'h'),
(1096, '15', '青海', '1507', '海西', '150702', '天峻', 101150708, 'h', 't'),
(1097, '15', '青海', '1507', '海西', '150703', '乌兰', 101150709, 'h', 'w'),
(1098, '15', '青海', '1507', '海西', '150704', '茫崖', 101150712, 'h', 'm'),
(1099, '15', '青海', '1507', '海西', '150705', '大柴旦', 101150713, 'h', 'd'),
(1100, '15', '青海', '1507', '海西', '150706', '德令哈', 101150716, 'h', 'd'),
(1101, '15', '青海', '1508', '海北', '150801', '海北', 101150801, 'h', 'h'),
(1102, '15', '青海', '1508', '海北', '150802', '门源', 101150802, 'h', 'm'),
(1103, '15', '青海', '1508', '海北', '150803', '祁连', 101150803, 'h', 'q'),
(1104, '15', '青海', '1508', '海北', '150804', '海晏', 101150804, 'h', 'h'),
(1105, '15', '青海', '1508', '海北', '150805', '刚察', 101150806, 'h', 'g'),
(1106, '15', '青海', '1509', '格尔木', '150901', '格尔木', 101150901, 'g', 'g'),
(1107, '15', '青海', '1509', '格尔木', '150902', '都兰', 101150902, 'g', 'd'),
(1108, '16', '甘肃', '1601', '兰州', '160101', '兰州', 101160101, 'l', 'l'),
(1109, '16', '甘肃', '1601', '兰州', '160102', '皋兰', 101160102, 'l', 'g'),
(1110, '16', '甘肃', '1601', '兰州', '160103', '永登', 101160103, 'l', 'y'),
(1111, '16', '甘肃', '1601', '兰州', '160104', '榆中', 101160104, 'l', 'y'),
(1112, '16', '甘肃', '1602', '定西', '160201', '定西', 101160201, 'd', 'd'),
(1113, '16', '甘肃', '1602', '定西', '160202', '通渭', 101160202, 'd', 't'),
(1114, '16', '甘肃', '1602', '定西', '160203', '陇西', 101160203, 'd', 'l'),
(1115, '16', '甘肃', '1602', '定西', '160204', '渭源', 101160204, 'd', 'w'),
(1116, '16', '甘肃', '1602', '定西', '160205', '临洮', 101160205, 'd', 'l'),
(1117, '16', '甘肃', '1602', '定西', '160206', '漳县', 101160206, 'd', 'z'),
(1118, '16', '甘肃', '1602', '定西', '160207', '岷县', 101160207, 'd', 'm'),
(1119, '16', '甘肃', '1602', '定西', '160208', '安定', 101160208, 'd', 'a'),
(1120, '16', '甘肃', '1603', '平凉', '160301', '平凉', 101160301, 'p', 'p'),
(1121, '16', '甘肃', '1603', '平凉', '160302', '泾川', 101160302, 'p', 'j'),
(1122, '16', '甘肃', '1603', '平凉', '160303', '灵台', 101160303, 'p', 'l'),
(1123, '16', '甘肃', '1603', '平凉', '160304', '崇信', 101160304, 'p', 'c'),
(1124, '16', '甘肃', '1603', '平凉', '160305', '华亭', 101160305, 'p', 'h'),
(1125, '16', '甘肃', '1603', '平凉', '160306', '庄浪', 101160306, 'p', 'z'),
(1126, '16', '甘肃', '1603', '平凉', '160307', '静宁', 101160307, 'p', 'j'),
(1127, '16', '甘肃', '1603', '平凉', '160308', '崆峒', 101160308, 'p', 'k'),
(1128, '16', '甘肃', '1604', '庆阳', '160401', '庆阳', 101160401, 'q', 'q'),
(1129, '16', '甘肃', '1604', '庆阳', '160402', '环县', 101160403, 'q', 'h'),
(1130, '16', '甘肃', '1604', '庆阳', '160403', '华池', 101160404, 'q', 'h'),
(1131, '16', '甘肃', '1604', '庆阳', '160404', '合水', 101160405, 'q', 'h'),
(1132, '16', '甘肃', '1604', '庆阳', '160405', '正宁', 101160406, 'q', 'z'),
(1133, '16', '甘肃', '1604', '庆阳', '160406', '宁县', 101160407, 'q', 'n'),
(1134, '16', '甘肃', '1604', '庆阳', '160407', '镇原', 101160408, 'q', 'z'),
(1135, '16', '甘肃', '1604', '庆阳', '160408', '庆城', 101160409, 'q', 'q'),
(1136, '16', '甘肃', '1605', '武威', '160501', '武威', 101160501, 'w', 'w'),
(1137, '16', '甘肃', '1605', '武威', '160502', '民勤', 101160502, 'w', 'm'),
(1138, '16', '甘肃', '1605', '武威', '160503', '古浪', 101160503, 'w', 'g'),
(1139, '16', '甘肃', '1605', '武威', '160504', '天祝', 101160505, 'w', 't'),
(1140, '16', '甘肃', '1606', '金昌', '160601', '金昌', 101160601, 'j', 'j'),
(1141, '16', '甘肃', '1606', '金昌', '160602', '永昌', 101160602, 'j', 'y'),
(1142, '16', '甘肃', '1607', '张掖', '160701', '张掖', 101160701, 'z', 'z'),
(1143, '16', '甘肃', '1607', '张掖', '160702', '肃南', 101160702, 'z', 's'),
(1144, '16', '甘肃', '1607', '张掖', '160703', '民乐', 101160703, 'z', 'm'),
(1145, '16', '甘肃', '1607', '张掖', '160704', '临泽', 101160704, 'z', 'l'),
(1146, '16', '甘肃', '1607', '张掖', '160705', '高台', 101160705, 'z', 'g'),
(1147, '16', '甘肃', '1607', '张掖', '160706', '山丹', 101160706, 'z', 's'),
(1148, '16', '甘肃', '1608', '酒泉', '160801', '酒泉', 101160801, 'j', 'j'),
(1149, '16', '甘肃', '1608', '酒泉', '160802', '金塔', 101160803, 'j', 'j'),
(1150, '16', '甘肃', '1608', '酒泉', '160803', '阿克塞', 101160804, 'j', 'a'),
(1151, '16', '甘肃', '1608', '酒泉', '160804', '瓜州', 101160805, 'j', 'g'),
(1152, '16', '甘肃', '1608', '酒泉', '160805', '肃北', 101160806, 'j', 's'),
(1153, '16', '甘肃', '1608', '酒泉', '160806', '玉门', 101160807, 'j', 'y'),
(1154, '16', '甘肃', '1608', '酒泉', '160807', '敦煌', 101160808, 'j', 'd'),
(1155, '16', '甘肃', '1609', '天水', '160901', '天水', 101160901, 't', 't'),
(1156, '16', '甘肃', '1609', '天水', '160902', '清水', 101160903, 't', 'q'),
(1157, '16', '甘肃', '1609', '天水', '160903', '秦安', 101160904, 't', 'q'),
(1158, '16', '甘肃', '1609', '天水', '160904', '甘谷', 101160905, 't', 'g'),
(1159, '16', '甘肃', '1609', '天水', '160905', '武山', 101160906, 't', 'w'),
(1160, '16', '甘肃', '1609', '天水', '160906', '张家川', 101160907, 't', 'z'),
(1161, '16', '甘肃', '1609', '天水', '160907', '麦积', 101160908, 't', 'm'),
(1162, '16', '甘肃', '1610', '陇南', '161001', '陇南', 101161001, 'l', 'l'),
(1163, '16', '甘肃', '1610', '陇南', '161002', '成县', 101161002, 'l', 'c'),
(1164, '16', '甘肃', '1610', '陇南', '161003', '文县', 101161003, 'l', 'w'),
(1165, '16', '甘肃', '1610', '陇南', '161004', '宕昌', 101161004, 'l', 'd'),
(1166, '16', '甘肃', '1610', '陇南', '161005', '康县', 101161005, 'l', 'k'),
(1167, '16', '甘肃', '1610', '陇南', '161006', '西和', 101161006, 'l', 'x'),
(1168, '16', '甘肃', '1610', '陇南', '161007', '礼县', 101161007, 'l', 'l'),
(1169, '16', '甘肃', '1610', '陇南', '161008', '徽县', 101161008, 'l', 'h'),
(1170, '16', '甘肃', '1610', '陇南', '161009', '两当', 101161009, 'l', 'l'),
(1171, '16', '甘肃', '1611', '临夏', '161101', '临夏', 101161101, 'l', 'l'),
(1172, '16', '甘肃', '1611', '临夏', '161102', '康乐', 101161102, 'l', 'k'),
(1173, '16', '甘肃', '1611', '临夏', '161103', '永靖', 101161103, 'l', 'y'),
(1174, '16', '甘肃', '1611', '临夏', '161104', '广河', 101161104, 'l', 'g'),
(1175, '16', '甘肃', '1611', '临夏', '161105', '和政', 101161105, 'l', 'h'),
(1176, '16', '甘肃', '1611', '临夏', '161106', '东乡', 101161106, 'l', 'd'),
(1177, '16', '甘肃', '1611', '临夏', '161107', '积石山', 101161107, 'l', 'j'),
(1178, '16', '甘肃', '1612', '甘南', '161201', '甘南', 101161201, 'g', 'g'),
(1179, '16', '甘肃', '1612', '甘南', '161202', '临潭', 101161202, 'g', 'l'),
(1180, '16', '甘肃', '1612', '甘南', '161203', '卓尼', 101161203, 'g', 'z'),
(1181, '16', '甘肃', '1612', '甘南', '161204', '舟曲', 101161204, 'g', 'z'),
(1182, '16', '甘肃', '1612', '甘南', '161205', '迭部', 101161205, 'g', 'd'),
(1183, '16', '甘肃', '1612', '甘南', '161206', '玛曲', 101161206, 'g', 'm'),
(1184, '16', '甘肃', '1612', '甘南', '161207', '碌曲', 101161207, 'g', 'l'),
(1185, '16', '甘肃', '1612', '甘南', '161208', '夏河', 101161208, 'g', 'x'),
(1186, '16', '甘肃', '1613', '白银', '161301', '白银', 101161301, 'b', 'b'),
(1187, '16', '甘肃', '1613', '白银', '161302', '靖远', 101161302, 'b', 'j'),
(1188, '16', '甘肃', '1613', '白银', '161303', '会宁', 101161303, 'b', 'h'),
(1189, '16', '甘肃', '1613', '白银', '161304', '平川', 101161304, 'b', 'p'),
(1190, '16', '甘肃', '1613', '白银', '161305', '景泰', 101161305, 'b', 'j'),
(1191, '16', '甘肃', '1614', '嘉峪关', '161401', '嘉峪关', 101161401, 'j', 'j'),
(1192, '17', '宁夏', '1701', '银川', '170101', '银川', 101170101, 'y', 'y'),
(1193, '17', '宁夏', '1701', '银川', '170102', '永宁', 101170102, 'y', 'y'),
(1194, '17', '宁夏', '1701', '银川', '170103', '灵武', 101170103, 'y', 'l'),
(1195, '17', '宁夏', '1701', '银川', '170104', '贺兰', 101170104, 'y', 'h'),
(1196, '17', '宁夏', '1702', '石嘴山', '170201', '石嘴山', 101170201, 's', 's'),
(1197, '17', '宁夏', '1702', '石嘴山', '170202', '惠农', 101170202, 's', 'h'),
(1198, '17', '宁夏', '1702', '石嘴山', '170203', '平罗', 101170203, 's', 'p'),
(1199, '17', '宁夏', '1702', '石嘴山', '170204', '陶乐', 101170204, 's', 't'),
(1200, '17', '宁夏', '1703', '吴忠', '170301', '吴忠', 101170301, 'w', 'w'),
(1201, '17', '宁夏', '1703', '吴忠', '170302', '同心', 101170302, 'w', 't'),
(1202, '17', '宁夏', '1703', '吴忠', '170303', '盐池', 101170303, 'w', 'y'),
(1203, '17', '宁夏', '1703', '吴忠', '170304', '青铜峡', 101170306, 'w', 'q'),
(1204, '17', '宁夏', '1704', '固原', '170401', '固原', 101170401, 'g', 'g'),
(1205, '17', '宁夏', '1704', '固原', '170402', '西吉', 101170402, 'g', 'x'),
(1206, '17', '宁夏', '1704', '固原', '170403', '隆德', 101170403, 'g', 'l'),
(1207, '17', '宁夏', '1704', '固原', '170404', '泾源', 101170404, 'g', 'j'),
(1208, '17', '宁夏', '1704', '固原', '170405', '彭阳', 101170406, 'g', 'p'),
(1209, '17', '宁夏', '1705', '中卫', '170501', '中卫', 101170501, 'z', 'z'),
(1210, '17', '宁夏', '1705', '中卫', '170502', '中宁', 101170502, 'z', 'z'),
(1211, '17', '宁夏', '1705', '中卫', '170503', '海原', 101170504, 'z', 'h'),
(1212, '18', '河南', '1801', '郑州', '180101', '郑州', 101180101, 'z', 'z');
INSERT INTO `cmstop_weather_city` (`id`, `province_id`, `province`, `town_id`, `town`, `city_id`, `city`, `weather_id`, `town_initial`, `city_initial`) VALUES
(1213, '18', '河南', '1801', '郑州', '180102', '巩义', 101180102, 'z', 'g'),
(1214, '18', '河南', '1801', '郑州', '180103', '荥阳', 101180103, 'z', 'x'),
(1215, '18', '河南', '1801', '郑州', '180104', '登封', 101180104, 'z', 'd'),
(1216, '18', '河南', '1801', '郑州', '180105', '新密', 101180105, 'z', 'x'),
(1217, '18', '河南', '1801', '郑州', '180106', '新郑', 101180106, 'z', 'x'),
(1218, '18', '河南', '1801', '郑州', '180107', '中牟', 101180107, 'z', 'z'),
(1219, '18', '河南', '1801', '郑州', '180108', '上街', 101180108, 'z', 's'),
(1220, '18', '河南', '1802', '安阳', '180201', '安阳', 101180201, 'a', 'a'),
(1221, '18', '河南', '1802', '安阳', '180202', '汤阴', 101180202, 'a', 't'),
(1222, '18', '河南', '1802', '安阳', '180203', '滑县', 101180203, 'a', 'h'),
(1223, '18', '河南', '1802', '安阳', '180204', '内黄', 101180204, 'a', 'n'),
(1224, '18', '河南', '1802', '安阳', '180205', '林州', 101180205, 'a', 'l'),
(1225, '18', '河南', '1803', '新乡', '180301', '新乡', 101180301, 'x', 'x'),
(1226, '18', '河南', '1803', '新乡', '180302', '获嘉', 101180302, 'x', 'h'),
(1227, '18', '河南', '1803', '新乡', '180303', '原阳', 101180303, 'x', 'y'),
(1228, '18', '河南', '1803', '新乡', '180304', '辉县', 101180304, 'x', 'h'),
(1229, '18', '河南', '1803', '新乡', '180305', '卫辉', 101180305, 'x', 'w'),
(1230, '18', '河南', '1803', '新乡', '180306', '延津', 101180306, 'x', 'y'),
(1231, '18', '河南', '1803', '新乡', '180307', '封丘', 101180307, 'x', 'f'),
(1232, '18', '河南', '1803', '新乡', '180308', '长垣', 101180308, 'x', 'c'),
(1233, '18', '河南', '1804', '许昌', '180401', '许昌', 101180401, 'x', 'x'),
(1234, '18', '河南', '1804', '许昌', '180402', '鄢陵', 101180402, 'x', 'y'),
(1235, '18', '河南', '1804', '许昌', '180403', '襄城', 101180403, 'x', 'x'),
(1236, '18', '河南', '1804', '许昌', '180404', '长葛', 101180404, 'x', 'c'),
(1237, '18', '河南', '1804', '许昌', '180405', '禹州', 101180405, 'x', 'y'),
(1238, '18', '河南', '1805', '平顶山', '180501', '平顶山', 101180501, 'p', 'p'),
(1239, '18', '河南', '1805', '平顶山', '180502', '郏县', 101180502, 'p', 'j'),
(1240, '18', '河南', '1805', '平顶山', '180503', '宝丰', 101180503, 'p', 'b'),
(1241, '18', '河南', '1805', '平顶山', '180504', '汝州', 101180504, 'p', 'r'),
(1242, '18', '河南', '1805', '平顶山', '180505', '叶县', 101180505, 'p', 'y'),
(1243, '18', '河南', '1805', '平顶山', '180506', '舞钢', 101180506, 'p', 'w'),
(1244, '18', '河南', '1805', '平顶山', '180507', '鲁山', 101180507, 'p', 'l'),
(1245, '18', '河南', '1805', '平顶山', '180508', '石龙', 101180508, 'p', 's'),
(1246, '18', '河南', '1806', '信阳', '180601', '信阳', 101180601, 'x', 'x'),
(1247, '18', '河南', '1806', '信阳', '180602', '息县', 101180602, 'x', 'x'),
(1248, '18', '河南', '1806', '信阳', '180603', '罗山', 101180603, 'x', 'l'),
(1249, '18', '河南', '1806', '信阳', '180604', '光山', 101180604, 'x', 'g'),
(1250, '18', '河南', '1806', '信阳', '180605', '新县', 101180605, 'x', 'x'),
(1251, '18', '河南', '1806', '信阳', '180606', '淮滨', 101180606, 'x', 'h'),
(1252, '18', '河南', '1806', '信阳', '180607', '潢川', 101180607, 'x', 'h'),
(1253, '18', '河南', '1806', '信阳', '180608', '固始', 101180608, 'x', 'g'),
(1254, '18', '河南', '1806', '信阳', '180609', '商城', 101180609, 'x', 's'),
(1255, '18', '河南', '1807', '南阳', '180701', '南阳', 101180701, 'n', 'n'),
(1256, '18', '河南', '1807', '南阳', '180702', '南召', 101180702, 'n', 'n'),
(1257, '18', '河南', '1807', '南阳', '180703', '方城', 101180703, 'n', 'f'),
(1258, '18', '河南', '1807', '南阳', '180704', '社旗', 101180704, 'n', 's'),
(1259, '18', '河南', '1807', '南阳', '180705', '西峡', 101180705, 'n', 'x'),
(1260, '18', '河南', '1807', '南阳', '180706', '内乡', 101180706, 'n', 'n'),
(1261, '18', '河南', '1807', '南阳', '180707', '镇平', 101180707, 'n', 'z'),
(1262, '18', '河南', '1807', '南阳', '180708', '淅川', 101180708, 'n', 'z'),
(1263, '18', '河南', '1807', '南阳', '180709', '新野', 101180709, 'n', 'x'),
(1264, '18', '河南', '1807', '南阳', '180710', '唐河', 101180710, 'n', 't'),
(1265, '18', '河南', '1807', '南阳', '180711', '邓州', 101180711, 'n', 'd'),
(1266, '18', '河南', '1807', '南阳', '180712', '桐柏', 101180712, 'n', 't'),
(1267, '18', '河南', '1808', '开封', '180801', '开封', 101180801, 'k', 'k'),
(1268, '18', '河南', '1808', '开封', '180802', '杞县', 101180802, 'k', 'q'),
(1269, '18', '河南', '1808', '开封', '180803', '尉氏', 101180803, 'k', 'w'),
(1270, '18', '河南', '1808', '开封', '180804', '通许', 101180804, 'k', 't'),
(1271, '18', '河南', '1808', '开封', '180805', '兰考', 101180805, 'k', 'l'),
(1272, '18', '河南', '1809', '洛阳', '180901', '洛阳', 101180901, 'l', 'l'),
(1273, '18', '河南', '1809', '洛阳', '180902', '新安', 101180902, 'l', 'x'),
(1274, '18', '河南', '1809', '洛阳', '180903', '孟津', 101180903, 'l', 'm'),
(1275, '18', '河南', '1809', '洛阳', '180904', '宜阳', 101180904, 'l', 'y'),
(1276, '18', '河南', '1809', '洛阳', '180905', '洛宁', 101180905, 'l', 'l'),
(1277, '18', '河南', '1809', '洛阳', '180906', '伊川', 101180906, 'l', 'y'),
(1278, '18', '河南', '1809', '洛阳', '180907', '嵩县', 101180907, 'l', 's'),
(1279, '18', '河南', '1809', '洛阳', '180908', '偃师', 101180908, 'l', 'y'),
(1280, '18', '河南', '1809', '洛阳', '180909', '栾川', 101180909, 'l', 'l'),
(1281, '18', '河南', '1809', '洛阳', '180910', '汝阳', 101180910, 'l', 'r'),
(1282, '18', '河南', '1809', '洛阳', '180911', '吉利', 101180911, 'l', 'j'),
(1283, '18', '河南', '1810', '商丘', '181001', '商丘', 101181001, 's', 's'),
(1284, '18', '河南', '1810', '商丘', '181002', '睢县', 101181003, 's', 's'),
(1285, '18', '河南', '1810', '商丘', '181003', '民权', 101181004, 's', 'm'),
(1286, '18', '河南', '1810', '商丘', '181004', '虞城', 101181005, 's', 'y'),
(1287, '18', '河南', '1810', '商丘', '181005', '柘城', 101181006, 's', 'z'),
(1288, '18', '河南', '1810', '商丘', '181006', '宁陵', 101181007, 's', 'n'),
(1289, '18', '河南', '1810', '商丘', '181007', '夏邑', 101181008, 's', 'x'),
(1290, '18', '河南', '1810', '商丘', '181008', '永城', 101181009, 's', 'y'),
(1291, '18', '河南', '1811', '焦作', '181101', '焦作', 101181101, 'j', 'j'),
(1292, '18', '河南', '1811', '焦作', '181102', '修武', 101181102, 'j', 'x'),
(1293, '18', '河南', '1811', '焦作', '181103', '武陟', 101181103, 'j', 'w'),
(1294, '18', '河南', '1811', '焦作', '181104', '沁阳', 101181104, 'j', 'q'),
(1295, '18', '河南', '1811', '焦作', '181105', '博爱', 101181106, 'j', 'b'),
(1296, '18', '河南', '1811', '焦作', '181106', '温县', 101181107, 'j', 'w'),
(1297, '18', '河南', '1811', '焦作', '181107', '孟州', 101181108, 'j', 'm'),
(1298, '18', '河南', '1812', '鹤壁', '181201', '鹤壁', 101181201, 'h', 'h'),
(1299, '18', '河南', '1812', '鹤壁', '181202', '浚县', 101181202, 'h', 'j'),
(1300, '18', '河南', '1812', '鹤壁', '181203', '淇县', 101181203, 'h', 'q'),
(1301, '18', '河南', '1813', '濮阳', '181301', '濮阳', 101181301, 'y', 'p'),
(1302, '18', '河南', '1813', '濮阳', '181302', '台前', 101181302, 'y', 't'),
(1303, '18', '河南', '1813', '濮阳', '181303', '南乐', 101181303, 'y', 'n'),
(1304, '18', '河南', '1813', '濮阳', '181304', '清丰', 101181304, 'y', 'q'),
(1305, '18', '河南', '1813', '濮阳', '181305', '范县', 101181305, 'y', 'f'),
(1306, '18', '河南', '1814', '周口', '181401', '周口', 101181401, 'z', 'z'),
(1307, '18', '河南', '1814', '周口', '181402', '扶沟', 101181402, 'z', 'f'),
(1308, '18', '河南', '1814', '周口', '181403', '太康', 101181403, 'z', 't'),
(1309, '18', '河南', '1814', '周口', '181404', '淮阳', 101181404, 'z', 'h'),
(1310, '18', '河南', '1814', '周口', '181405', '西华', 101181405, 'z', 'x'),
(1311, '18', '河南', '1814', '周口', '181406', '商水', 101181406, 'z', 's'),
(1312, '18', '河南', '1814', '周口', '181407', '项城', 101181407, 'z', 'x'),
(1313, '18', '河南', '1814', '周口', '181408', '郸城', 101181408, 'z', 'd'),
(1314, '18', '河南', '1814', '周口', '181409', '鹿邑', 101181409, 'z', 'l'),
(1315, '18', '河南', '1814', '周口', '181410', '沈丘', 101181410, 'z', 's'),
(1316, '18', '河南', '1815', '漯河', '181501', '漯河', 101181501, 'h', 'l'),
(1317, '18', '河南', '1815', '漯河', '181502', '临颍', 101181502, 'h', 'l'),
(1318, '18', '河南', '1815', '漯河', '181503', '舞阳', 101181503, 'h', 'w'),
(1319, '18', '河南', '1816', '驻马店', '181601', '驻马店', 101181601, 'z', 'z'),
(1320, '18', '河南', '1816', '驻马店', '181602', '西平', 101181602, 'z', 'x'),
(1321, '18', '河南', '1816', '驻马店', '181603', '遂平', 101181603, 'z', 's'),
(1322, '18', '河南', '1816', '驻马店', '181604', '上蔡', 101181604, 'z', 's'),
(1323, '18', '河南', '1816', '驻马店', '181605', '汝南', 101181605, 'z', 'r'),
(1324, '18', '河南', '1816', '驻马店', '181606', '泌阳', 101181606, 'z', 'm'),
(1325, '18', '河南', '1816', '驻马店', '181607', '平舆', 101181607, 'z', 'p'),
(1326, '18', '河南', '1816', '驻马店', '181608', '新蔡', 101181608, 'z', 'x'),
(1327, '18', '河南', '1816', '驻马店', '181609', '确山', 101181609, 'z', 'q'),
(1328, '18', '河南', '1816', '驻马店', '181610', '正阳', 101181610, 'z', 'z'),
(1329, '18', '河南', '1817', '三门峡', '181701', '三门峡', 101181701, 's', 's'),
(1330, '18', '河南', '1817', '三门峡', '181702', '灵宝', 101181702, 's', 'l'),
(1331, '18', '河南', '1817', '三门峡', '181703', '渑池', 101181703, 's', 'm'),
(1332, '18', '河南', '1817', '三门峡', '181704', '卢氏', 101181704, 's', 'l'),
(1333, '18', '河南', '1817', '三门峡', '181705', '义马', 101181705, 's', 'y'),
(1334, '18', '河南', '1817', '三门峡', '181706', '陕县', 101181706, 's', 's'),
(1335, '18', '河南', '1818', '济源', '181801', '济源', 101181801, 'j', 'j'),
(1336, '19', '江苏', '1901', '南京', '190101', '南京', 101190101, 'n', 'n'),
(1337, '19', '江苏', '1901', '南京', '190102', '溧水', 101190102, 'n', 'l'),
(1338, '19', '江苏', '1901', '南京', '190103', '高淳', 101190103, 'n', 'g'),
(1339, '19', '江苏', '1901', '南京', '190104', '江宁', 101190104, 'n', 'j'),
(1340, '19', '江苏', '1901', '南京', '190105', '六合', 101190105, 'n', 'l'),
(1341, '19', '江苏', '1901', '南京', '190106', '江浦', 101190106, 'n', 'j'),
(1342, '19', '江苏', '1901', '南京', '190107', '浦口', 101190107, 'n', 'p'),
(1343, '19', '江苏', '1902', '无锡', '190201', '无锡', 101190201, 'w', 'w'),
(1344, '19', '江苏', '1902', '无锡', '190202', '江阴', 101190202, 'w', 'j'),
(1345, '19', '江苏', '1902', '无锡', '190203', '宜兴', 101190203, 'w', 'y'),
(1346, '19', '江苏', '1902', '无锡', '190204', '锡山', 101190204, 'w', 'x'),
(1347, '19', '江苏', '1903', '镇江', '190301', '镇江', 101190301, 'z', 'z'),
(1348, '19', '江苏', '1903', '镇江', '190302', '丹阳', 101190302, 'z', 'd'),
(1349, '19', '江苏', '1903', '镇江', '190303', '扬中', 101190303, 'z', 'y'),
(1350, '19', '江苏', '1903', '镇江', '190304', '句容', 101190304, 'z', 'j'),
(1351, '19', '江苏', '1903', '镇江', '190305', '丹徒', 101190305, 'z', 'd'),
(1352, '19', '江苏', '1904', '苏州', '190401', '苏州', 101190401, 's', 's'),
(1353, '19', '江苏', '1904', '苏州', '190402', '常熟', 101190402, 's', 'c'),
(1354, '19', '江苏', '1904', '苏州', '190403', '张家港', 101190403, 's', 'z'),
(1355, '19', '江苏', '1904', '苏州', '190404', '昆山', 101190404, 's', 'k'),
(1356, '19', '江苏', '1904', '苏州', '190405', '吴中', 101190405, 's', 'w'),
(1357, '19', '江苏', '1904', '苏州', '190406', '吴江', 101190407, 's', 'w'),
(1358, '19', '江苏', '1904', '苏州', '190407', '太仓', 101190408, 's', 't'),
(1359, '19', '江苏', '1905', '南通', '190501', '南通', 101190501, 'n', 'n'),
(1360, '19', '江苏', '1905', '南通', '190502', '海安', 101190502, 'n', 'h'),
(1361, '19', '江苏', '1905', '南通', '190503', '如皋', 101190503, 'n', 'r'),
(1362, '19', '江苏', '1905', '南通', '190504', '如东', 101190504, 'n', 'r'),
(1363, '19', '江苏', '1905', '南通', '190505', '启东', 101190507, 'n', 'q'),
(1364, '19', '江苏', '1905', '南通', '190506', '海门', 101190508, 'n', 'h'),
(1365, '19', '江苏', '1905', '南通', '190507', '通州', 101190509, 'n', 't'),
(1366, '19', '江苏', '1906', '扬州', '190601', '扬州', 101190601, 'y', 'y'),
(1367, '19', '江苏', '1906', '扬州', '190602', '宝应', 101190602, 'y', 'b'),
(1368, '19', '江苏', '1906', '扬州', '190603', '仪征', 101190603, 'y', 'y'),
(1369, '19', '江苏', '1906', '扬州', '190604', '高邮', 101190604, 'y', 'g'),
(1370, '19', '江苏', '1906', '扬州', '190605', '江都', 101190605, 'y', 'j'),
(1371, '19', '江苏', '1906', '扬州', '190606', '邗江', 101190606, 'y', 'h'),
(1372, '19', '江苏', '1907', '盐城', '190701', '盐城', 101190701, 'y', 'y'),
(1373, '19', '江苏', '1907', '盐城', '190702', '响水', 101190702, 'y', 'x'),
(1374, '19', '江苏', '1907', '盐城', '190703', '滨海', 101190703, 'y', 'b'),
(1375, '19', '江苏', '1907', '盐城', '190704', '阜宁', 101190704, 'y', 'f'),
(1376, '19', '江苏', '1907', '盐城', '190705', '射阳', 101190705, 'y', 's'),
(1377, '19', '江苏', '1907', '盐城', '190706', '建湖', 101190706, 'y', 'j'),
(1378, '19', '江苏', '1907', '盐城', '190707', '东台', 101190707, 'y', 'd'),
(1379, '19', '江苏', '1907', '盐城', '190708', '大丰', 101190708, 'y', 'd'),
(1380, '19', '江苏', '1907', '盐城', '190709', '盐都', 101190709, 'y', 'y'),
(1381, '19', '江苏', '1908', '徐州', '190801', '徐州', 101190801, 'x', 'x'),
(1382, '19', '江苏', '1908', '徐州', '190802', '铜山', 101190802, 'x', 't'),
(1383, '19', '江苏', '1908', '徐州', '190803', '丰县', 101190803, 'x', 'f'),
(1384, '19', '江苏', '1908', '徐州', '190804', '沛县', 101190804, 'x', 'p'),
(1385, '19', '江苏', '1908', '徐州', '190805', '邳州', 101190805, 'x', 'p'),
(1386, '19', '江苏', '1908', '徐州', '190806', '睢宁', 101190806, 'x', 's'),
(1387, '19', '江苏', '1908', '徐州', '190807', '新沂', 101190807, 'x', 'x'),
(1388, '19', '江苏', '1909', '淮安', '190901', '淮安', 101190901, 'h', 'h'),
(1389, '19', '江苏', '1909', '淮安', '190902', '金湖', 101190902, 'h', 'j'),
(1390, '19', '江苏', '1909', '淮安', '190903', '盱眙', 101190903, 'h', 'x'),
(1391, '19', '江苏', '1909', '淮安', '190904', '洪泽', 101190904, 'h', 'h'),
(1392, '19', '江苏', '1909', '淮安', '190905', '涟水', 101190905, 'h', 'l'),
(1393, '19', '江苏', '1909', '淮安', '190906', '淮阴区', 101190906, 'h', 'h'),
(1394, '19', '江苏', '1909', '淮安', '190907', '楚州', 101190908, 'h', 'c'),
(1395, '19', '江苏', '1910', '连云港', '191001', '连云港', 101191001, 'l', 'l'),
(1396, '19', '江苏', '1910', '连云港', '191002', '东海', 101191002, 'l', 'd'),
(1397, '19', '江苏', '1910', '连云港', '191003', '赣榆', 101191003, 'l', 'g'),
(1398, '19', '江苏', '1910', '连云港', '191004', '灌云', 101191004, 'l', 'g'),
(1399, '19', '江苏', '1910', '连云港', '191005', '灌南', 101191005, 'l', 'g'),
(1400, '19', '江苏', '1911', '常州', '191101', '常州', 101191101, 'c', 'c'),
(1401, '19', '江苏', '1911', '常州', '191102', '溧阳', 101191102, 'c', 'l'),
(1402, '19', '江苏', '1911', '常州', '191103', '金坛', 101191103, 'c', 'j'),
(1403, '19', '江苏', '1911', '常州', '191104', '武进', 101191104, 'c', 'w'),
(1404, '19', '江苏', '1912', '泰州', '191201', '泰州', 101191201, 't', 't'),
(1405, '19', '江苏', '1912', '泰州', '191202', '兴化', 101191202, 't', 'x'),
(1406, '19', '江苏', '1912', '泰州', '191203', '泰兴', 101191203, 't', 't'),
(1407, '19', '江苏', '1912', '泰州', '191204', '姜堰', 101191204, 't', 'j'),
(1408, '19', '江苏', '1912', '泰州', '191205', '靖江', 101191205, 't', 'j'),
(1409, '19', '江苏', '1913', '宿迁', '191301', '宿迁', 101191301, 's', 's'),
(1410, '19', '江苏', '1913', '宿迁', '191302', '沭阳', 101191302, 's', 's'),
(1411, '19', '江苏', '1913', '宿迁', '191303', '泗阳', 101191303, 's', 's'),
(1412, '19', '江苏', '1913', '宿迁', '191304', '泗洪', 101191304, 's', 's'),
(1413, '19', '江苏', '1913', '宿迁', '191305', '宿豫', 101191305, 's', 's'),
(1414, '20', '湖北', '2001', '武汉', '200101', '武汉', 101200101, 'w', 'w'),
(1415, '20', '湖北', '2001', '武汉', '200102', '蔡甸', 101200102, 'w', 'c'),
(1416, '20', '湖北', '2001', '武汉', '200103', '黄陂', 101200103, 'w', 'h'),
(1417, '20', '湖北', '2001', '武汉', '200104', '新洲', 101200104, 'w', 'x'),
(1418, '20', '湖北', '2001', '武汉', '200105', '江夏', 101200105, 'w', 'j'),
(1419, '20', '湖北', '2001', '武汉', '200106', '东西湖', 101200106, 'w', 'd'),
(1420, '20', '湖北', '2002', '襄阳', '200201', '襄阳', 101200201, 'x', 'x'),
(1421, '20', '湖北', '2002', '襄阳', '200202', '襄州', 101200202, 'x', 'x'),
(1422, '20', '湖北', '2002', '襄阳', '200203', '保康', 101200203, 'x', 'b'),
(1423, '20', '湖北', '2002', '襄阳', '200204', '南漳', 101200204, 'x', 'n'),
(1424, '20', '湖北', '2002', '襄阳', '200205', '宜城', 101200205, 'x', 'y'),
(1425, '20', '湖北', '2002', '襄阳', '200206', '老河口', 101200206, 'x', 'l'),
(1426, '20', '湖北', '2002', '襄阳', '200207', '谷城', 101200207, 'x', 'g'),
(1427, '20', '湖北', '2002', '襄阳', '200208', '枣阳', 101200208, 'x', 'z'),
(1428, '20', '湖北', '2003', '鄂州', '200301', '鄂州', 101200301, 'e', 'e'),
(1429, '20', '湖北', '2003', '鄂州', '200302', '梁子湖', 101200302, 'e', 'l'),
(1430, '20', '湖北', '2004', '孝感', '200401', '孝感', 101200401, 'x', 'x'),
(1431, '20', '湖北', '2004', '孝感', '200402', '安陆', 101200402, 'x', 'a'),
(1432, '20', '湖北', '2004', '孝感', '200403', '云梦', 101200403, 'x', 'y'),
(1433, '20', '湖北', '2004', '孝感', '200404', '大悟', 101200404, 'x', 'd'),
(1434, '20', '湖北', '2004', '孝感', '200405', '应城', 101200405, 'x', 'y'),
(1435, '20', '湖北', '2004', '孝感', '200406', '汉川', 101200406, 'x', 'h'),
(1436, '20', '湖北', '2004', '孝感', '200407', '孝昌', 101200407, 'x', 'x'),
(1437, '20', '湖北', '2005', '黄冈', '200501', '黄冈', 101200501, 'h', 'h'),
(1438, '20', '湖北', '2005', '黄冈', '200502', '红安', 101200502, 'h', 'h'),
(1439, '20', '湖北', '2005', '黄冈', '200503', '麻城', 101200503, 'h', 'm'),
(1440, '20', '湖北', '2005', '黄冈', '200504', '罗田', 101200504, 'h', 'l'),
(1441, '20', '湖北', '2005', '黄冈', '200505', '英山', 101200505, 'h', 'y'),
(1442, '20', '湖北', '2005', '黄冈', '200506', '浠水', 101200506, 'h', 'x'),
(1443, '20', '湖北', '2005', '黄冈', '200507', '蕲春', 101200507, 'h', 'q'),
(1444, '20', '湖北', '2005', '黄冈', '200508', '黄梅', 101200508, 'h', 'h'),
(1445, '20', '湖北', '2005', '黄冈', '200509', '武穴', 101200509, 'h', 'w'),
(1446, '20', '湖北', '2005', '黄冈', '200510', '团风', 101200510, 'h', 't'),
(1447, '20', '湖北', '2006', '黄石', '200601', '黄石', 101200601, 'h', 'h'),
(1448, '20', '湖北', '2006', '黄石', '200602', '大冶', 101200602, 'h', 'd'),
(1449, '20', '湖北', '2006', '黄石', '200603', '阳新', 101200603, 'h', 'y'),
(1450, '20', '湖北', '2006', '黄石', '200604', '铁山', 101200604, 'h', 't'),
(1451, '20', '湖北', '2006', '黄石', '200605', '下陆', 101200605, 'h', 'x'),
(1452, '20', '湖北', '2006', '黄石', '200606', '西塞山', 101200606, 'h', 'x'),
(1453, '20', '湖北', '2007', '咸宁', '200701', '咸宁', 101200701, 'x', 'x'),
(1454, '20', '湖北', '2007', '咸宁', '200702', '赤壁', 101200702, 'x', 'c'),
(1455, '20', '湖北', '2007', '咸宁', '200703', '嘉鱼', 101200703, 'x', 'j'),
(1456, '20', '湖北', '2007', '咸宁', '200704', '崇阳', 101200704, 'x', 'c'),
(1457, '20', '湖北', '2007', '咸宁', '200705', '通城', 101200705, 'x', 't'),
(1458, '20', '湖北', '2007', '咸宁', '200706', '通山', 101200706, 'x', 't'),
(1459, '20', '湖北', '2008', '荆州', '200801', '荆州', 101200801, 'j', 'j'),
(1460, '20', '湖北', '2008', '荆州', '200802', '江陵', 101200802, 'j', 'j'),
(1461, '20', '湖北', '2008', '荆州', '200803', '公安', 101200803, 'j', 'g'),
(1462, '20', '湖北', '2008', '荆州', '200804', '石首', 101200804, 'j', 's'),
(1463, '20', '湖北', '2008', '荆州', '200805', '监利', 101200805, 'j', 'j'),
(1464, '20', '湖北', '2008', '荆州', '200806', '洪湖', 101200806, 'j', 'h'),
(1465, '20', '湖北', '2008', '荆州', '200807', '松滋', 101200807, 'j', 's'),
(1466, '20', '湖北', '2008', '荆州', '200808', '沙市', 101201406, 'j', 's'),
(1467, '20', '湖北', '2009', '宜昌', '200901', '宜昌', 101200901, 'y', 'y'),
(1468, '20', '湖北', '2009', '宜昌', '200902', '远安', 101200902, 'y', 'y'),
(1469, '20', '湖北', '2009', '宜昌', '200903', '秭归', 101200903, 'y', 'z'),
(1470, '20', '湖北', '2009', '宜昌', '200904', '兴山', 101200904, 'y', 'x'),
(1471, '20', '湖北', '2009', '宜昌', '200905', '五峰', 101200906, 'y', 'w'),
(1472, '20', '湖北', '2009', '宜昌', '200906', '当阳', 101200907, 'y', 'd'),
(1473, '20', '湖北', '2009', '宜昌', '200907', '长阳', 101200908, 'y', 'c'),
(1474, '20', '湖北', '2009', '宜昌', '200908', '宜都', 101200909, 'y', 'y'),
(1475, '20', '湖北', '2009', '宜昌', '200909', '枝江', 101200910, 'y', 'z'),
(1476, '20', '湖北', '2009', '宜昌', '200910', '三峡', 101200911, 'y', 's'),
(1477, '20', '湖北', '2009', '宜昌', '200911', '夷陵', 101200912, 'y', 'y'),
(1478, '20', '湖北', '2010', '恩施', '201001', '恩施', 101201001, 'e', 'e'),
(1479, '20', '湖北', '2010', '恩施', '201002', '利川', 101201002, 'e', 'l'),
(1480, '20', '湖北', '2010', '恩施', '201003', '建始', 101201003, 'e', 'j'),
(1481, '20', '湖北', '2010', '恩施', '201004', '咸丰', 101201004, 'e', 'x'),
(1482, '20', '湖北', '2010', '恩施', '201005', '宣恩', 101201005, 'e', 'x'),
(1483, '20', '湖北', '2010', '恩施', '201006', '鹤峰', 101201006, 'e', 'h'),
(1484, '20', '湖北', '2010', '恩施', '201007', '来凤', 101201007, 'e', 'l'),
(1485, '20', '湖北', '2010', '恩施', '201008', '巴东', 101201008, 'e', 'b'),
(1486, '20', '湖北', '2011', '十堰', '201101', '十堰', 101201101, 's', 's'),
(1487, '20', '湖北', '2011', '十堰', '201102', '竹溪', 101201102, 's', 'z'),
(1488, '20', '湖北', '2011', '十堰', '201103', '郧西', 101201103, 's', 'y'),
(1489, '20', '湖北', '2011', '十堰', '201104', '郧县', 101201104, 's', 'y'),
(1490, '20', '湖北', '2011', '十堰', '201105', '竹山', 101201105, 's', 'z'),
(1491, '20', '湖北', '2011', '十堰', '201106', '房县', 101201106, 's', 'f'),
(1492, '20', '湖北', '2011', '十堰', '201107', '丹江口', 101201107, 's', 'd'),
(1493, '20', '湖北', '2011', '十堰', '201108', '茅箭', 101201108, 's', 'm'),
(1494, '20', '湖北', '2011', '十堰', '201109', '张湾', 101201109, 's', 'z'),
(1495, '20', '湖北', '2012', '神农架', '201201', '神农架', 101201201, 's', 's'),
(1496, '20', '湖北', '2013', '随州', '201301', '随州', 101201301, 's', 's'),
(1497, '20', '湖北', '2013', '随州', '201302', '广水', 101201302, 's', 'g'),
(1498, '20', '湖北', '2014', '荆门', '201401', '荆门', 101201401, 'j', 'j'),
(1499, '20', '湖北', '2014', '荆门', '201402', '钟祥', 101201402, 'j', 'z'),
(1500, '20', '湖北', '2014', '荆门', '201403', '京山', 101201403, 'j', 'j'),
(1501, '20', '湖北', '2014', '荆门', '201404', '掇刀', 101201404, 'j', 'd'),
(1502, '20', '湖北', '2014', '荆门', '201405', '沙洋', 101201405, 'j', 's'),
(1503, '20', '湖北', '2015', '天门', '201501', '天门', 101201501, 't', 't'),
(1504, '20', '湖北', '2016', '仙桃', '201601', '仙桃', 101201601, 'x', 'x'),
(1505, '20', '湖北', '2017', '潜江', '201701', '潜江', 101201701, 'q', 'q'),
(1506, '21', '浙江', '2101', '杭州', '210101', '杭州', 101210101, 'h', 'h'),
(1507, '21', '浙江', '2101', '杭州', '210102', '萧山', 101210102, 'h', 'x'),
(1508, '21', '浙江', '2101', '杭州', '210103', '桐庐', 101210103, 'h', 't'),
(1509, '21', '浙江', '2101', '杭州', '210104', '淳安', 101210104, 'h', 'c'),
(1510, '21', '浙江', '2101', '杭州', '210105', '建德', 101210105, 'h', 'j'),
(1511, '21', '浙江', '2101', '杭州', '210106', '余杭', 101210106, 'h', 'y'),
(1512, '21', '浙江', '2101', '杭州', '210107', '临安', 101210107, 'h', 'l'),
(1513, '21', '浙江', '2101', '杭州', '210108', '富阳', 101210108, 'h', 'f'),
(1514, '21', '浙江', '2102', '湖州', '210201', '湖州', 101210201, 'h', 'h'),
(1515, '21', '浙江', '2102', '湖州', '210202', '长兴', 101210202, 'h', 'c'),
(1516, '21', '浙江', '2102', '湖州', '210203', '安吉', 101210203, 'h', 'a'),
(1517, '21', '浙江', '2102', '湖州', '210204', '德清', 101210204, 'h', 'd'),
(1518, '21', '浙江', '2103', '嘉兴', '210301', '嘉兴', 101210301, 'j', 'j'),
(1519, '21', '浙江', '2103', '嘉兴', '210302', '嘉善', 101210302, 'j', 'j'),
(1520, '21', '浙江', '2103', '嘉兴', '210303', '海宁', 101210303, 'j', 'h'),
(1521, '21', '浙江', '2103', '嘉兴', '210304', '桐乡', 101210304, 'j', 't'),
(1522, '21', '浙江', '2103', '嘉兴', '210305', '平湖', 101210305, 'j', 'p'),
(1523, '21', '浙江', '2103', '嘉兴', '210306', '海盐', 101210306, 'j', 'h'),
(1524, '21', '浙江', '2104', '宁波', '210401', '宁波', 101210401, 'n', 'n'),
(1525, '21', '浙江', '2104', '宁波', '210402', '慈溪', 101210403, 'n', 'c'),
(1526, '21', '浙江', '2104', '宁波', '210403', '余姚', 101210404, 'n', 'y'),
(1527, '21', '浙江', '2104', '宁波', '210404', '奉化', 101210405, 'n', 'f'),
(1528, '21', '浙江', '2104', '宁波', '210405', '象山', 101210406, 'n', 'x'),
(1529, '21', '浙江', '2104', '宁波', '210406', '宁海', 101210408, 'n', 'n'),
(1530, '21', '浙江', '2104', '宁波', '210407', '北仑', 101210410, 'n', 'b'),
(1531, '21', '浙江', '2104', '宁波', '210408', '鄞州', 101210411, 'n', 'y'),
(1532, '21', '浙江', '2104', '宁波', '210409', '镇海', 101210412, 'n', 'z'),
(1533, '21', '浙江', '2105', '绍兴', '210501', '绍兴', 101210501, 's', 's'),
(1534, '21', '浙江', '2105', '绍兴', '210502', '诸暨', 101210502, 's', 'z'),
(1535, '21', '浙江', '2105', '绍兴', '210503', '上虞', 101210503, 's', 's'),
(1536, '21', '浙江', '2105', '绍兴', '210504', '新昌', 101210504, 's', 'x'),
(1537, '21', '浙江', '2105', '绍兴', '210505', '嵊州', 101210505, 's', 's'),
(1538, '21', '浙江', '2106', '台州', '210601', '台州', 101210601, 't', 't'),
(1539, '21', '浙江', '2106', '台州', '210602', '玉环', 101210603, 't', 'y'),
(1540, '21', '浙江', '2106', '台州', '210603', '三门', 101210604, 't', 's'),
(1541, '21', '浙江', '2106', '台州', '210604', '天台', 101210605, 't', 't'),
(1542, '21', '浙江', '2106', '台州', '210605', '仙居', 101210606, 't', 'x'),
(1543, '21', '浙江', '2106', '台州', '210606', '温岭', 101210607, 't', 'w'),
(1544, '21', '浙江', '2106', '台州', '210607', '洪家', 101210609, 't', 'h'),
(1545, '21', '浙江', '2106', '台州', '210608', '临海', 101210610, 't', 'l'),
(1546, '21', '浙江', '2106', '台州', '210609', '椒江', 101210611, 't', 'j'),
(1547, '21', '浙江', '2106', '台州', '210610', '黄岩', 101210612, 't', 'h'),
(1548, '21', '浙江', '2106', '台州', '210611', '路桥', 101210613, 't', 'l'),
(1549, '21', '浙江', '2107', '温州', '210701', '温州', 101210701, 'w', 'w'),
(1550, '21', '浙江', '2107', '温州', '210702', '泰顺', 101210702, 'w', 't'),
(1551, '21', '浙江', '2107', '温州', '210703', '文成', 101210703, 'w', 'w'),
(1552, '21', '浙江', '2107', '温州', '210704', '平阳', 101210704, 'w', 'p'),
(1553, '21', '浙江', '2107', '温州', '210705', '瑞安', 101210705, 'w', 'r'),
(1554, '21', '浙江', '2107', '温州', '210706', '洞头', 101210706, 'w', 'd'),
(1555, '21', '浙江', '2107', '温州', '210707', '乐清', 101210707, 'w', 'l'),
(1556, '21', '浙江', '2107', '温州', '210708', '永嘉', 101210708, 'w', 'y'),
(1557, '21', '浙江', '2107', '温州', '210709', '苍南', 101210709, 'w', 'c'),
(1558, '21', '浙江', '2108', '丽水', '210801', '丽水', 101210801, 'l', 'l'),
(1559, '21', '浙江', '2108', '丽水', '210802', '遂昌', 101210802, 'l', 's'),
(1560, '21', '浙江', '2108', '丽水', '210803', '龙泉', 101210803, 'l', 'l'),
(1561, '21', '浙江', '2108', '丽水', '210804', '缙云', 101210804, 'l', 'j'),
(1562, '21', '浙江', '2108', '丽水', '210805', '青田', 101210805, 'l', 'q'),
(1563, '21', '浙江', '2108', '丽水', '210806', '云和', 101210806, 'l', 'y'),
(1564, '21', '浙江', '2108', '丽水', '210807', '庆元', 101210807, 'l', 'q'),
(1565, '21', '浙江', '2108', '丽水', '210808', '松阳', 101210808, 'l', 's'),
(1566, '21', '浙江', '2108', '丽水', '210809', '景宁', 101210809, 'l', 'j'),
(1567, '21', '浙江', '2109', '金华', '210901', '金华', 101210901, 'j', 'j'),
(1568, '21', '浙江', '2109', '金华', '210902', '浦江', 101210902, 'j', 'p'),
(1569, '21', '浙江', '2109', '金华', '210903', '兰溪', 101210903, 'j', 'l'),
(1570, '21', '浙江', '2109', '金华', '210904', '义乌', 101210904, 'j', 'y'),
(1571, '21', '浙江', '2109', '金华', '210905', '东阳', 101210905, 'j', 'd'),
(1572, '21', '浙江', '2109', '金华', '210906', '武义', 101210906, 'j', 'w'),
(1573, '21', '浙江', '2109', '金华', '210907', '永康', 101210907, 'j', 'y'),
(1574, '21', '浙江', '2109', '金华', '210908', '磐安', 101210908, 'j', 'p'),
(1575, '21', '浙江', '2110', '衢州', '211001', '衢州', 101211001, 'z', 'q'),
(1576, '21', '浙江', '2110', '衢州', '211002', '常山', 101211002, 'z', 'c'),
(1577, '21', '浙江', '2110', '衢州', '211003', '开化', 101211003, 'z', 'k'),
(1578, '21', '浙江', '2110', '衢州', '211004', '龙游', 101211004, 'z', 'l'),
(1579, '21', '浙江', '2110', '衢州', '211005', '江山', 101211005, 'z', 'j'),
(1580, '21', '浙江', '2110', '衢州', '211006', '衢江', 101211006, 'z', 'q'),
(1581, '21', '浙江', '2111', '舟山', '211101', '舟山', 101211101, 'z', 'z'),
(1582, '21', '浙江', '2111', '舟山', '211102', '嵊泗', 101211102, 'z', 's'),
(1583, '21', '浙江', '2111', '舟山', '211103', '岱山', 101211104, 'z', 'd'),
(1584, '21', '浙江', '2111', '舟山', '211104', '普陀', 101211105, 'z', 'p'),
(1585, '21', '浙江', '2111', '舟山', '211105', '定海', 101211106, 'z', 'd'),
(1586, '22', '安徽', '2201', '合肥', '220101', '合肥', 101220101, 'h', 'h'),
(1587, '22', '安徽', '2201', '合肥', '220102', '长丰', 101220102, 'h', 'c'),
(1588, '22', '安徽', '2201', '合肥', '220103', '肥东', 101220103, 'h', 'f'),
(1589, '22', '安徽', '2201', '合肥', '220104', '肥西', 101220104, 'h', 'f'),
(1590, '22', '安徽', '2202', '蚌埠', '220201', '蚌埠', 101220201, 'b', 'b'),
(1591, '22', '安徽', '2202', '蚌埠', '220202', '怀远', 101220202, 'b', 'h'),
(1592, '22', '安徽', '2202', '蚌埠', '220203', '固镇', 101220203, 'b', 'g'),
(1593, '22', '安徽', '2202', '蚌埠', '220204', '五河', 101220204, 'b', 'w'),
(1594, '22', '安徽', '2203', '芜湖', '220301', '芜湖', 101220301, 'w', 'w'),
(1595, '22', '安徽', '2203', '芜湖', '220302', '繁昌', 101220302, 'w', 'f'),
(1596, '22', '安徽', '2203', '芜湖', '220303', '芜湖县', 101220303, 'w', 'w'),
(1597, '22', '安徽', '2203', '芜湖', '220304', '南陵', 101220304, 'w', 'n'),
(1598, '22', '安徽', '2204', '淮南', '220401', '淮南', 101220401, 'h', 'h'),
(1599, '22', '安徽', '2204', '淮南', '220402', '凤台', 101220402, 'h', 'f'),
(1600, '22', '安徽', '2204', '淮南', '220403', '潘集', 101220403, 'h', 'p'),
(1601, '22', '安徽', '2205', '马鞍山', '220501', '马鞍山', 101220501, 'm', 'm'),
(1602, '22', '安徽', '2205', '马鞍山', '220502', '当涂', 101220502, 'm', 'd'),
(1603, '22', '安徽', '2206', '安庆', '220601', '安庆', 101220601, 'a', 'a'),
(1604, '22', '安徽', '2206', '安庆', '220602', '枞阳', 101220602, 'a', 'z'),
(1605, '22', '安徽', '2206', '安庆', '220603', '太湖', 101220603, 'a', 't'),
(1606, '22', '安徽', '2206', '安庆', '220604', '潜山', 101220604, 'a', 'q'),
(1607, '22', '安徽', '2206', '安庆', '220605', '怀宁', 101220605, 'a', 'h'),
(1608, '22', '安徽', '2206', '安庆', '220606', '宿松', 101220606, 'a', 's'),
(1609, '22', '安徽', '2206', '安庆', '220607', '望江', 101220607, 'a', 'w'),
(1610, '22', '安徽', '2206', '安庆', '220608', '岳西', 101220608, 'a', 'y'),
(1611, '22', '安徽', '2206', '安庆', '220609', '桐城', 101220609, 'a', 't'),
(1612, '22', '安徽', '2207', '宿州', '220701', '宿州', 101220701, 's', 's'),
(1613, '22', '安徽', '2207', '宿州', '220702', '砀山', 101220702, 's', 'd'),
(1614, '22', '安徽', '2207', '宿州', '220703', '灵璧', 101220703, 's', 'l'),
(1615, '22', '安徽', '2207', '宿州', '220704', '泗县', 101220704, 's', 's'),
(1616, '22', '安徽', '2207', '宿州', '220705', '萧县', 101220705, 's', 'x'),
(1617, '22', '安徽', '2208', '阜阳', '220801', '阜阳', 101220801, 'f', 'f'),
(1618, '22', '安徽', '2208', '阜阳', '220802', '阜南', 101220802, 'f', 'f'),
(1619, '22', '安徽', '2208', '阜阳', '220803', '颍上', 101220803, 'f', 'y'),
(1620, '22', '安徽', '2208', '阜阳', '220804', '临泉', 101220804, 'f', 'l'),
(1621, '22', '安徽', '2208', '阜阳', '220805', '界首', 101220805, 'f', 'j'),
(1622, '22', '安徽', '2208', '阜阳', '220806', '太和', 101220806, 'f', 't'),
(1623, '22', '安徽', '2209', '亳州', '220901', '亳州', 101220901, 'z', 'b'),
(1624, '22', '安徽', '2209', '亳州', '220902', '涡阳', 101220902, 'z', 'w'),
(1625, '22', '安徽', '2209', '亳州', '220903', '利辛', 101220903, 'z', 'l'),
(1626, '22', '安徽', '2209', '亳州', '220904', '蒙城', 101220904, 'z', 'm'),
(1627, '22', '安徽', '2210', '黄山', '221001', '黄山', 101221001, 'h', 'h'),
(1628, '22', '安徽', '2210', '黄山', '221002', '黄山区', 101221002, 'h', 'h'),
(1629, '22', '安徽', '2210', '黄山', '221003', '屯溪', 101221003, 'h', 't'),
(1630, '22', '安徽', '2210', '黄山', '221004', '祁门', 101221004, 'h', 'q'),
(1631, '22', '安徽', '2210', '黄山', '221005', '黟县', 101221005, 'h', 'y'),
(1632, '22', '安徽', '2210', '黄山', '221006', '歙县', 101221006, 'h', 's'),
(1633, '22', '安徽', '2210', '黄山', '221007', '休宁', 101221007, 'h', 'x'),
(1634, '22', '安徽', '2210', '黄山', '221008', '黄山风景区', 101221008, 'h', 'h'),
(1635, '22', '安徽', '2211', '滁州', '221101', '滁州', 101221101, 'c', 'c'),
(1636, '22', '安徽', '2211', '滁州', '221102', '凤阳', 101221102, 'c', 'f'),
(1637, '22', '安徽', '2211', '滁州', '221103', '明光', 101221103, 'c', 'm'),
(1638, '22', '安徽', '2211', '滁州', '221104', '定远', 101221104, 'c', 'd'),
(1639, '22', '安徽', '2211', '滁州', '221105', '全椒', 101221105, 'c', 'q'),
(1640, '22', '安徽', '2211', '滁州', '221106', '来安', 101221106, 'c', 'l'),
(1641, '22', '安徽', '2211', '滁州', '221107', '天长', 101221107, 'c', 't'),
(1642, '22', '安徽', '2212', '淮北', '221201', '淮北', 101221201, 'h', 'h'),
(1643, '22', '安徽', '2212', '淮北', '221202', '濉溪', 101221202, 'h', 's'),
(1644, '22', '安徽', '2213', '铜陵', '221301', '铜陵', 101221301, 't', 't'),
(1645, '22', '安徽', '2214', '宣城', '221401', '宣城', 101221401, 'x', 'x'),
(1646, '22', '安徽', '2214', '宣城', '221402', '泾县', 101221402, 'x', 'j'),
(1647, '22', '安徽', '2214', '宣城', '221403', '旌德', 101221403, 'x', 'j'),
(1648, '22', '安徽', '2214', '宣城', '221404', '宁国', 101221404, 'x', 'n'),
(1649, '22', '安徽', '2214', '宣城', '221405', '绩溪', 101221405, 'x', 'j'),
(1650, '22', '安徽', '2214', '宣城', '221406', '广德', 101221406, 'x', 'g'),
(1651, '22', '安徽', '2214', '宣城', '221407', '郎溪', 101221407, 'x', 'l'),
(1652, '22', '安徽', '2215', '六安', '221501', '六安', 101221501, 'l', 'l'),
(1653, '22', '安徽', '2215', '六安', '221502', '霍邱', 101221502, 'l', 'h'),
(1654, '22', '安徽', '2215', '六安', '221503', '寿县', 101221503, 'l', 's'),
(1655, '22', '安徽', '2215', '六安', '221504', '金寨', 101221505, 'l', 'j'),
(1656, '22', '安徽', '2215', '六安', '221505', '霍山', 101221506, 'l', 'h'),
(1657, '22', '安徽', '2215', '六安', '221506', '舒城', 101221507, 'l', 's'),
(1658, '22', '安徽', '2216', '巢湖', '221601', '巢湖', 101221601, 'c', 'c'),
(1659, '22', '安徽', '2216', '巢湖', '221602', '庐江', 101221602, 'c', 'l'),
(1660, '22', '安徽', '2216', '巢湖', '221603', '无为', 101221603, 'c', 'w'),
(1661, '22', '安徽', '2216', '巢湖', '221604', '含山', 101221604, 'c', 'h'),
(1662, '22', '安徽', '2216', '巢湖', '221605', '和县', 101221605, 'c', 'h'),
(1663, '22', '安徽', '2217', '池州', '221701', '池州', 101221701, 'c', 'c'),
(1664, '22', '安徽', '2217', '池州', '221702', '东至', 101221702, 'c', 'd'),
(1665, '22', '安徽', '2217', '池州', '221703', '青阳', 101221703, 'c', 'q'),
(1666, '22', '安徽', '2217', '池州', '221704', '九华山', 101221704, 'c', 'j'),
(1667, '22', '安徽', '2217', '池州', '221705', '石台', 101221705, 'c', 's'),
(1668, '23', '福建', '2301', '福州', '230101', '福州', 101230101, 'f', 'f'),
(1669, '23', '福建', '2301', '福州', '230102', '闽清', 101230102, 'f', 'm'),
(1670, '23', '福建', '2301', '福州', '230103', '闽侯', 101230103, 'f', 'm'),
(1671, '23', '福建', '2301', '福州', '230104', '罗源', 101230104, 'f', 'l'),
(1672, '23', '福建', '2301', '福州', '230105', '连江', 101230105, 'f', 'l'),
(1673, '23', '福建', '2301', '福州', '230106', '永泰', 101230107, 'f', 'y'),
(1674, '23', '福建', '2301', '福州', '230107', '平潭', 101230108, 'f', 'p'),
(1675, '23', '福建', '2301', '福州', '230108', '长乐', 101230110, 'f', 'c'),
(1676, '23', '福建', '2301', '福州', '230109', '福清', 101230111, 'f', 'f'),
(1677, '23', '福建', '2302', '厦门', '230201', '厦门', 101230201, 'x', 'x'),
(1678, '23', '福建', '2302', '厦门', '230202', '同安', 101230202, 'x', 't'),
(1679, '23', '福建', '2303', '宁德', '230301', '宁德', 101230301, 'n', 'n'),
(1680, '23', '福建', '2303', '宁德', '230302', '古田', 101230302, 'n', 'g'),
(1681, '23', '福建', '2303', '宁德', '230303', '霞浦', 101230303, 'n', 'x'),
(1682, '23', '福建', '2303', '宁德', '230304', '寿宁', 101230304, 'n', 's'),
(1683, '23', '福建', '2303', '宁德', '230305', '周宁', 101230305, 'n', 'z'),
(1684, '23', '福建', '2303', '宁德', '230306', '福安', 101230306, 'n', 'f'),
(1685, '23', '福建', '2303', '宁德', '230307', '柘荣', 101230307, 'n', 'z'),
(1686, '23', '福建', '2303', '宁德', '230308', '福鼎', 101230308, 'n', 'f'),
(1687, '23', '福建', '2303', '宁德', '230309', '屏南', 101230309, 'n', 'p'),
(1688, '23', '福建', '2304', '莆田', '230401', '莆田', 101230401, 'p', 'p'),
(1689, '23', '福建', '2304', '莆田', '230402', '仙游', 101230402, 'p', 'x'),
(1690, '23', '福建', '2304', '莆田', '230403', '秀屿港', 101230403, 'p', 'x'),
(1691, '23', '福建', '2304', '莆田', '230404', '涵江', 101230404, 'p', 'h'),
(1692, '23', '福建', '2304', '莆田', '230405', '秀屿', 101230405, 'p', 'x'),
(1693, '23', '福建', '2304', '莆田', '230406', '荔城', 101230406, 'p', 'l'),
(1694, '23', '福建', '2304', '莆田', '230407', '城厢', 101230407, 'p', 'c'),
(1695, '23', '福建', '2305', '泉州', '230501', '泉州', 101230501, 'q', 'q'),
(1696, '23', '福建', '2305', '泉州', '230502', '安溪', 101230502, 'q', 'a'),
(1697, '23', '福建', '2305', '泉州', '230503', '永春', 101230504, 'q', 'y'),
(1698, '23', '福建', '2305', '泉州', '230504', '德化', 101230505, 'q', 'd'),
(1699, '23', '福建', '2305', '泉州', '230505', '南安', 101230506, 'q', 'n'),
(1700, '23', '福建', '2305', '泉州', '230506', '崇武', 101230507, 'q', 'c'),
(1701, '23', '福建', '2305', '泉州', '230507', '惠安', 101230508, 'q', 'h'),
(1702, '23', '福建', '2305', '泉州', '230508', '晋江', 101230509, 'q', 'j'),
(1703, '23', '福建', '2305', '泉州', '230509', '石狮', 101230510, 'q', 's'),
(1704, '23', '福建', '2306', '漳州', '230601', '漳州', 101230601, 'z', 'z'),
(1705, '23', '福建', '2306', '漳州', '230602', '长泰', 101230602, 'z', 'c'),
(1706, '23', '福建', '2306', '漳州', '230603', '南靖', 101230603, 'z', 'n'),
(1707, '23', '福建', '2306', '漳州', '230604', '平和', 101230604, 'z', 'p'),
(1708, '23', '福建', '2306', '漳州', '230605', '龙海', 101230605, 'z', 'l'),
(1709, '23', '福建', '2306', '漳州', '230606', '漳浦', 101230606, 'z', 'z'),
(1710, '23', '福建', '2306', '漳州', '230607', '诏安', 101230607, 'z', 'z'),
(1711, '23', '福建', '2306', '漳州', '230608', '东山', 101230608, 'z', 'd'),
(1712, '23', '福建', '2306', '漳州', '230609', '云霄', 101230609, 'z', 'y'),
(1713, '23', '福建', '2306', '漳州', '230610', '华安', 101230610, 'z', 'h'),
(1714, '23', '福建', '2307', '龙岩', '230701', '龙岩', 101230701, 'l', 'l'),
(1715, '23', '福建', '2307', '龙岩', '230702', '长汀', 101230702, 'l', 'c'),
(1716, '23', '福建', '2307', '龙岩', '230703', '连城', 101230703, 'l', 'l'),
(1717, '23', '福建', '2307', '龙岩', '230704', '武平', 101230704, 'l', 'w'),
(1718, '23', '福建', '2307', '龙岩', '230705', '上杭', 101230705, 'l', 's'),
(1719, '23', '福建', '2307', '龙岩', '230706', '永定', 101230706, 'l', 'y'),
(1720, '23', '福建', '2307', '龙岩', '230707', '漳平', 101230707, 'l', 'z'),
(1721, '23', '福建', '2308', '三明', '230801', '三明', 101230801, 's', 's'),
(1722, '23', '福建', '2308', '三明', '230802', '宁化', 101230802, 's', 'n'),
(1723, '23', '福建', '2308', '三明', '230803', '清流', 101230803, 's', 'q'),
(1724, '23', '福建', '2308', '三明', '230804', '泰宁', 101230804, 's', 't'),
(1725, '23', '福建', '2308', '三明', '230805', '将乐', 101230805, 's', 'j'),
(1726, '23', '福建', '2308', '三明', '230806', '建宁', 101230806, 's', 'j'),
(1727, '23', '福建', '2308', '三明', '230807', '明溪', 101230807, 's', 'm'),
(1728, '23', '福建', '2308', '三明', '230808', '沙县', 101230808, 's', 's'),
(1729, '23', '福建', '2308', '三明', '230809', '尤溪', 101230809, 's', 'y'),
(1730, '23', '福建', '2308', '三明', '230810', '永安', 101230810, 's', 'y'),
(1731, '23', '福建', '2308', '三明', '230811', '大田', 101230811, 's', 'd'),
(1732, '23', '福建', '2309', '南平', '230901', '南平', 101230901, 'n', 'n'),
(1733, '23', '福建', '2309', '南平', '230902', '顺昌', 101230902, 'n', 's'),
(1734, '23', '福建', '2309', '南平', '230903', '光泽', 101230903, 'n', 'g'),
(1735, '23', '福建', '2309', '南平', '230904', '邵武', 101230904, 'n', 's'),
(1736, '23', '福建', '2309', '南平', '230905', '武夷山', 101230905, 'n', 'w'),
(1737, '23', '福建', '2309', '南平', '230906', '浦城', 101230906, 'n', 'p'),
(1738, '23', '福建', '2309', '南平', '230907', '建阳', 101230907, 'n', 'j'),
(1739, '23', '福建', '2309', '南平', '230908', '松溪', 101230908, 'n', 's'),
(1740, '23', '福建', '2309', '南平', '230909', '政和', 101230909, 'n', 'z'),
(1741, '23', '福建', '2309', '南平', '230910', '建瓯', 101230910, 'n', 'j'),
(1742, '24', '江西', '2401', '南昌', '240101', '南昌', 101240101, 'n', 'n'),
(1743, '24', '江西', '2401', '南昌', '240102', '新建', 101240102, 'n', 'x'),
(1744, '24', '江西', '2401', '南昌', '240103', '南昌县', 101240103, 'n', 'n'),
(1745, '24', '江西', '2401', '南昌', '240104', '安义', 101240104, 'n', 'a'),
(1746, '24', '江西', '2401', '南昌', '240105', '进贤', 101240105, 'n', 'j'),
(1747, '24', '江西', '2402', '九江', '240201', '九江', 101240201, 'j', 'j'),
(1748, '24', '江西', '2402', '九江', '240202', '瑞昌', 101240202, 'j', 'r'),
(1749, '24', '江西', '2402', '九江', '240203', '庐山', 101240203, 'j', 'l'),
(1750, '24', '江西', '2402', '九江', '240204', '武宁', 101240204, 'j', 'w'),
(1751, '24', '江西', '2402', '九江', '240205', '德安', 101240205, 'j', 'd'),
(1752, '24', '江西', '2402', '九江', '240206', '永修', 101240206, 'j', 'y'),
(1753, '24', '江西', '2402', '九江', '240207', '湖口', 101240207, 'j', 'h'),
(1754, '24', '江西', '2402', '九江', '240208', '彭泽', 101240208, 'j', 'p'),
(1755, '24', '江西', '2402', '九江', '240209', '星子', 101240209, 'j', 'x'),
(1756, '24', '江西', '2402', '九江', '240210', '都昌', 101240210, 'j', 'd'),
(1757, '24', '江西', '2402', '九江', '240211', '修水', 101240212, 'j', 'x'),
(1758, '24', '江西', '2403', '上饶', '240301', '上饶', 101240301, 's', 's'),
(1759, '24', '江西', '2403', '上饶', '240302', '鄱阳', 101240302, 's', 'p'),
(1760, '24', '江西', '2403', '上饶', '240303', '婺源', 101240303, 's', 'w'),
(1761, '24', '江西', '2403', '上饶', '240304', '余干', 101240305, 's', 'y'),
(1762, '24', '江西', '2403', '上饶', '240305', '万年', 101240306, 's', 'w'),
(1763, '24', '江西', '2403', '上饶', '240306', '德兴', 101240307, 's', 'd'),
(1764, '24', '江西', '2403', '上饶', '240307', '上饶县', 101240308, 's', 's'),
(1765, '24', '江西', '2403', '上饶', '240308', '弋阳', 101240309, 's', 'y'),
(1766, '24', '江西', '2403', '上饶', '240309', '横峰', 101240310, 's', 'h'),
(1767, '24', '江西', '2403', '上饶', '240310', '铅山', 101240311, 's', 'q'),
(1768, '24', '江西', '2403', '上饶', '240311', '玉山', 101240312, 's', 'y'),
(1769, '24', '江西', '2403', '上饶', '240312', '广丰', 101240313, 's', 'g'),
(1770, '24', '江西', '2404', '抚州', '240401', '抚州', 101240401, 'f', 'f'),
(1771, '24', '江西', '2404', '抚州', '240402', '广昌', 101240402, 'f', 'g'),
(1772, '24', '江西', '2404', '抚州', '240403', '乐安', 101240403, 'f', 'l'),
(1773, '24', '江西', '2404', '抚州', '240404', '崇仁', 101240404, 'f', 'c'),
(1774, '24', '江西', '2404', '抚州', '240405', '金溪', 101240405, 'f', 'j'),
(1775, '24', '江西', '2404', '抚州', '240406', '资溪', 101240406, 'f', 'z'),
(1776, '24', '江西', '2404', '抚州', '240407', '宜黄', 101240407, 'f', 'y'),
(1777, '24', '江西', '2404', '抚州', '240408', '南城', 101240408, 'f', 'n'),
(1778, '24', '江西', '2404', '抚州', '240409', '南丰', 101240409, 'f', 'n'),
(1779, '24', '江西', '2404', '抚州', '240410', '黎川', 101240410, 'f', 'l'),
(1780, '24', '江西', '2404', '抚州', '240411', '东乡', 101240411, 'f', 'd'),
(1781, '24', '江西', '2405', '宜春', '240501', '宜春', 101240501, 'y', 'y'),
(1782, '24', '江西', '2405', '宜春', '240502', '铜鼓', 101240502, 'y', 't'),
(1783, '24', '江西', '2405', '宜春', '240503', '宜丰', 101240503, 'y', 'y'),
(1784, '24', '江西', '2405', '宜春', '240504', '万载', 101240504, 'y', 'w'),
(1785, '24', '江西', '2405', '宜春', '240505', '上高', 101240505, 'y', 's'),
(1786, '24', '江西', '2405', '宜春', '240506', '靖安', 101240506, 'y', 'j'),
(1787, '24', '江西', '2405', '宜春', '240507', '奉新', 101240507, 'y', 'f'),
(1788, '24', '江西', '2405', '宜春', '240508', '高安', 101240508, 'y', 'g'),
(1789, '24', '江西', '2405', '宜春', '240509', '樟树', 101240509, 'y', 'z'),
(1790, '24', '江西', '2405', '宜春', '240510', '丰城', 101240510, 'y', 'f'),
(1791, '24', '江西', '2406', '吉安', '240601', '吉安', 101240601, 'j', 'j'),
(1792, '24', '江西', '2406', '吉安', '240602', '吉安县', 101240602, 'j', 'j'),
(1793, '24', '江西', '2406', '吉安', '240603', '吉水', 101240603, 'j', 'j'),
(1794, '24', '江西', '2406', '吉安', '240604', '新干', 101240604, 'j', 'x'),
(1795, '24', '江西', '2406', '吉安', '240605', '峡江', 101240605, 'j', 'x'),
(1796, '24', '江西', '2406', '吉安', '240606', '永丰', 101240606, 'j', 'y'),
(1797, '24', '江西', '2406', '吉安', '240607', '永新', 101240607, 'j', 'y'),
(1798, '24', '江西', '2406', '吉安', '240608', '井冈山', 101240608, 'j', 'j'),
(1799, '24', '江西', '2406', '吉安', '240609', '万安', 101240609, 'j', 'w'),
(1800, '24', '江西', '2406', '吉安', '240610', '遂川', 101240610, 'j', 's'),
(1801, '24', '江西', '2406', '吉安', '240611', '泰和', 101240611, 'j', 't'),
(1802, '24', '江西', '2406', '吉安', '240612', '安福', 101240612, 'j', 'a'),
(1803, '24', '江西', '2406', '吉安', '240613', '宁冈', 101240613, 'j', 'n'),
(1804, '24', '江西', '2407', '赣州', '240701', '赣州', 101240701, 'g', 'g'),
(1805, '24', '江西', '2407', '赣州', '240702', '崇义', 101240702, 'g', 'c'),
(1806, '24', '江西', '2407', '赣州', '240703', '上犹', 101240703, 'g', 's'),
(1807, '24', '江西', '2407', '赣州', '240704', '南康', 101240704, 'g', 'n'),
(1808, '24', '江西', '2407', '赣州', '240705', '大余', 101240705, 'g', 'd'),
(1809, '24', '江西', '2407', '赣州', '240706', '信丰', 101240706, 'g', 'x'),
(1810, '24', '江西', '2407', '赣州', '240707', '宁都', 101240707, 'g', 'n'),
(1811, '24', '江西', '2407', '赣州', '240708', '石城', 101240708, 'g', 's'),
(1812, '24', '江西', '2407', '赣州', '240709', '瑞金', 101240709, 'g', 'r'),
(1813, '24', '江西', '2407', '赣州', '240710', '于都', 101240710, 'g', 'y'),
(1814, '24', '江西', '2407', '赣州', '240711', '会昌', 101240711, 'g', 'h'),
(1815, '24', '江西', '2407', '赣州', '240712', '安远', 101240712, 'g', 'a'),
(1816, '24', '江西', '2407', '赣州', '240713', '全南', 101240713, 'g', 'q'),
(1817, '24', '江西', '2407', '赣州', '240714', '龙南', 101240714, 'g', 'l'),
(1818, '24', '江西', '2407', '赣州', '240715', '定南', 101240715, 'g', 'd'),
(1819, '24', '江西', '2407', '赣州', '240716', '寻乌', 101240716, 'g', 'x'),
(1820, '24', '江西', '2407', '赣州', '240717', '兴国', 101240717, 'g', 'x'),
(1821, '24', '江西', '2407', '赣州', '240718', '赣县', 101240718, 'g', 'g'),
(1822, '24', '江西', '2408', '景德镇', '240801', '景德镇', 101240801, 'j', 'j'),
(1823, '24', '江西', '2408', '景德镇', '240802', '乐平', 101240802, 'j', 'l'),
(1824, '24', '江西', '2408', '景德镇', '240803', '浮梁', 101240803, 'j', 'f'),
(1825, '24', '江西', '2409', '萍乡', '240901', '萍乡', 101240901, 'p', 'p');
INSERT INTO `cmstop_weather_city` (`id`, `province_id`, `province`, `town_id`, `town`, `city_id`, `city`, `weather_id`, `town_initial`, `city_initial`) VALUES
(1826, '24', '江西', '2409', '萍乡', '240902', '莲花', 101240902, 'p', 'l'),
(1827, '24', '江西', '2409', '萍乡', '240903', '上栗', 101240903, 'p', 's'),
(1828, '24', '江西', '2409', '萍乡', '240904', '安源', 101240904, 'p', 'a'),
(1829, '24', '江西', '2409', '萍乡', '240905', '芦溪', 101240905, 'p', 'l'),
(1830, '24', '江西', '2409', '萍乡', '240906', '湘东', 101240906, 'p', 'x'),
(1831, '24', '江西', '2410', '新余', '241001', '新余', 101241001, 'x', 'x'),
(1832, '24', '江西', '2410', '新余', '241002', '分宜', 101241002, 'x', 'f'),
(1833, '24', '江西', '2411', '鹰潭', '241101', '鹰潭', 101241101, 'y', 'y'),
(1834, '24', '江西', '2411', '鹰潭', '241102', '余江', 101241102, 'y', 'y'),
(1835, '24', '江西', '2411', '鹰潭', '241103', '贵溪', 101241103, 'y', 'g'),
(1836, '25', '湖南', '2501', '长沙', '250101', '长沙', 101250101, 'c', 'c'),
(1837, '25', '湖南', '2501', '长沙', '250102', '宁乡', 101250102, 'c', 'n'),
(1838, '25', '湖南', '2501', '长沙', '250103', '浏阳', 101250103, 'c', 'l'),
(1839, '25', '湖南', '2501', '长沙', '250104', '马坡岭', 101250104, 'c', 'm'),
(1840, '25', '湖南', '2501', '长沙', '250105', '望城', 101250105, 'c', 'w'),
(1841, '25', '湖南', '2502', '湘潭', '250201', '湘潭', 101250201, 'x', 'x'),
(1842, '25', '湖南', '2502', '湘潭', '250202', '韶山', 101250202, 'x', 's'),
(1843, '25', '湖南', '2502', '湘潭', '250203', '湘乡', 101250203, 'x', 'x'),
(1844, '25', '湖南', '2503', '株洲', '250301', '株洲', 101250301, 'z', 'z'),
(1845, '25', '湖南', '2503', '株洲', '250302', '攸县', 101250302, 'z', 'y'),
(1846, '25', '湖南', '2503', '株洲', '250303', '醴陵', 101250303, 'z', 'l'),
(1847, '25', '湖南', '2503', '株洲', '250304', '茶陵', 101250305, 'z', 'c'),
(1848, '25', '湖南', '2503', '株洲', '250305', '炎陵', 101250306, 'z', 'y'),
(1849, '25', '湖南', '2504', '衡阳', '250401', '衡阳', 101250401, 'h', 'h'),
(1850, '25', '湖南', '2504', '衡阳', '250402', '衡山', 101250402, 'h', 'h'),
(1851, '25', '湖南', '2504', '衡阳', '250403', '衡东', 101250403, 'h', 'h'),
(1852, '25', '湖南', '2504', '衡阳', '250404', '祁东', 101250404, 'h', 'q'),
(1853, '25', '湖南', '2504', '衡阳', '250405', '衡阳县', 101250405, 'h', 'h'),
(1854, '25', '湖南', '2504', '衡阳', '250406', '常宁', 101250406, 'h', 'c'),
(1855, '25', '湖南', '2504', '衡阳', '250407', '衡南', 101250407, 'h', 'h'),
(1856, '25', '湖南', '2504', '衡阳', '250408', '耒阳', 101250408, 'h', 'l'),
(1857, '25', '湖南', '2504', '衡阳', '250409', '南岳', 101250409, 'h', 'n'),
(1858, '25', '湖南', '2505', '郴州', '250501', '郴州', 101250501, 'c', 'c'),
(1859, '25', '湖南', '2505', '郴州', '250502', '桂阳', 101250502, 'c', 'g'),
(1860, '25', '湖南', '2505', '郴州', '250503', '嘉禾', 101250503, 'c', 'j'),
(1861, '25', '湖南', '2505', '郴州', '250504', '宜章', 101250504, 'c', 'y'),
(1862, '25', '湖南', '2505', '郴州', '250505', '临武', 101250505, 'c', 'l'),
(1863, '25', '湖南', '2505', '郴州', '250506', '资兴', 101250507, 'c', 'z'),
(1864, '25', '湖南', '2505', '郴州', '250507', '汝城', 101250508, 'c', 'r'),
(1865, '25', '湖南', '2505', '郴州', '250508', '安仁', 101250509, 'c', 'a'),
(1866, '25', '湖南', '2505', '郴州', '250509', '永兴', 101250510, 'c', 'y'),
(1867, '25', '湖南', '2505', '郴州', '250510', '桂东', 101250511, 'c', 'g'),
(1868, '25', '湖南', '2505', '郴州', '250511', '苏仙', 101250512, 'c', 's'),
(1869, '25', '湖南', '2506', '常德', '250601', '常德', 101250601, 'c', 'c'),
(1870, '25', '湖南', '2506', '常德', '250602', '安乡', 101250602, 'c', 'a'),
(1871, '25', '湖南', '2506', '常德', '250603', '桃源', 101250603, 'c', 't'),
(1872, '25', '湖南', '2506', '常德', '250604', '汉寿', 101250604, 'c', 'h'),
(1873, '25', '湖南', '2506', '常德', '250605', '澧县', 101250605, 'c', 'l'),
(1874, '25', '湖南', '2506', '常德', '250606', '临澧', 101250606, 'c', 'l'),
(1875, '25', '湖南', '2506', '常德', '250607', '石门', 101250607, 'c', 's'),
(1876, '25', '湖南', '2506', '常德', '250608', '津市', 101250608, 'c', 'j'),
(1877, '25', '湖南', '2507', '益阳', '250701', '益阳', 101250700, 'y', 'y'),
(1878, '25', '湖南', '2507', '益阳', '250702', '赫山区', 101250701, 'y', 'h'),
(1879, '25', '湖南', '2507', '益阳', '250703', '南县', 101250702, 'y', 'n'),
(1880, '25', '湖南', '2507', '益阳', '250704', '桃江', 101250703, 'y', 't'),
(1881, '25', '湖南', '2507', '益阳', '250705', '安化', 101250704, 'y', 'a'),
(1882, '25', '湖南', '2507', '益阳', '250706', '沅江', 101250705, 'y', 'y'),
(1883, '25', '湖南', '2508', '娄底', '250801', '娄底', 101250801, 'l', 'l'),
(1884, '25', '湖南', '2508', '娄底', '250802', '双峰', 101250802, 'l', 's'),
(1885, '25', '湖南', '2508', '娄底', '250803', '冷水江', 101250803, 'l', 'l'),
(1886, '25', '湖南', '2508', '娄底', '250804', '新化', 101250805, 'l', 'x'),
(1887, '25', '湖南', '2508', '娄底', '250805', '涟源', 101250806, 'l', 'l'),
(1888, '25', '湖南', '2509', '邵阳', '250901', '邵阳', 101250901, 's', 's'),
(1889, '25', '湖南', '2509', '邵阳', '250902', '隆回', 101250902, 's', 'l'),
(1890, '25', '湖南', '2509', '邵阳', '250903', '洞口', 101250903, 's', 'd'),
(1891, '25', '湖南', '2509', '邵阳', '250904', '新邵', 101250904, 's', 'x'),
(1892, '25', '湖南', '2509', '邵阳', '250905', '邵东', 101250905, 's', 's'),
(1893, '25', '湖南', '2509', '邵阳', '250906', '绥宁', 101250906, 's', 's'),
(1894, '25', '湖南', '2509', '邵阳', '250907', '新宁', 101250907, 's', 'x'),
(1895, '25', '湖南', '2509', '邵阳', '250908', '武冈', 101250908, 's', 'w'),
(1896, '25', '湖南', '2509', '邵阳', '250909', '城步', 101250909, 's', 'c'),
(1897, '25', '湖南', '2509', '邵阳', '250910', '邵阳县', 101250910, 's', 's'),
(1898, '25', '湖南', '2510', '岳阳', '251001', '岳阳', 101251001, 'y', 'y'),
(1899, '25', '湖南', '2510', '岳阳', '251002', '华容', 101251002, 'y', 'h'),
(1900, '25', '湖南', '2510', '岳阳', '251003', '湘阴', 101251003, 'y', 'x'),
(1901, '25', '湖南', '2510', '岳阳', '251004', '汨罗', 101251004, 'y', 'm'),
(1902, '25', '湖南', '2510', '岳阳', '251005', '平江', 101251005, 'y', 'p'),
(1903, '25', '湖南', '2510', '岳阳', '251006', '临湘', 101251006, 'y', 'l'),
(1904, '25', '湖南', '2511', '张家界', '251101', '张家界', 101251101, 'z', 'z'),
(1905, '25', '湖南', '2511', '张家界', '251102', '桑植', 101251102, 'z', 's'),
(1906, '25', '湖南', '2511', '张家界', '251103', '慈利', 101251103, 'z', 'c'),
(1907, '25', '湖南', '2511', '张家界', '251104', '武陵源', 101251104, 'z', 'w'),
(1908, '25', '湖南', '2512', '怀化', '251201', '怀化', 101251201, 'h', 'h'),
(1909, '25', '湖南', '2512', '怀化', '251202', '沅陵', 101251203, 'h', 'y'),
(1910, '25', '湖南', '2512', '怀化', '251203', '辰溪', 101251204, 'h', 'c'),
(1911, '25', '湖南', '2512', '怀化', '251204', '靖州', 101251205, 'h', 'j'),
(1912, '25', '湖南', '2512', '怀化', '251205', '会同', 101251206, 'h', 'h'),
(1913, '25', '湖南', '2512', '怀化', '251206', '通道', 101251207, 'h', 't'),
(1914, '25', '湖南', '2512', '怀化', '251207', '麻阳', 101251208, 'h', 'm'),
(1915, '25', '湖南', '2512', '怀化', '251208', '新晃', 101251209, 'h', 'x'),
(1916, '25', '湖南', '2512', '怀化', '251209', '芷江', 101251210, 'h', 'z'),
(1917, '25', '湖南', '2512', '怀化', '251210', '溆浦', 101251211, 'h', 'x'),
(1918, '25', '湖南', '2512', '怀化', '251211', '中方', 101251212, 'h', 'z'),
(1919, '25', '湖南', '2512', '怀化', '251212', '洪江', 101251213, 'h', 'h'),
(1920, '25', '湖南', '2513', '永州', '251301', '永州', 101251401, 'y', 'y'),
(1921, '25', '湖南', '2513', '永州', '251302', '祁阳', 101251402, 'y', 'q'),
(1922, '25', '湖南', '2513', '永州', '251303', '东安', 101251403, 'y', 'd'),
(1923, '25', '湖南', '2513', '永州', '251304', '双牌', 101251404, 'y', 's'),
(1924, '25', '湖南', '2513', '永州', '251305', '道县', 101251405, 'y', 'd'),
(1925, '25', '湖南', '2513', '永州', '251306', '宁远', 101251406, 'y', 'n'),
(1926, '25', '湖南', '2513', '永州', '251307', '江永', 101251407, 'y', 'j'),
(1927, '25', '湖南', '2513', '永州', '251308', '蓝山', 101251408, 'y', 'l'),
(1928, '25', '湖南', '2513', '永州', '251309', '新田', 101251409, 'y', 'x'),
(1929, '25', '湖南', '2513', '永州', '251310', '江华', 101251410, 'y', 'j'),
(1930, '25', '湖南', '2513', '永州', '251311', '冷水滩', 101251411, 'y', 'l'),
(1931, '25', '湖南', '2514', '湘西', '251401', '湘西', 101251501, 'x', 'x'),
(1932, '25', '湖南', '2514', '湘西', '251402', '保靖', 101251502, 'x', 'b'),
(1933, '25', '湖南', '2514', '湘西', '251403', '永顺', 101251503, 'x', 'y'),
(1934, '25', '湖南', '2514', '湘西', '251404', '古丈', 101251504, 'x', 'g'),
(1935, '25', '湖南', '2514', '湘西', '251405', '凤凰', 101251505, 'x', 'f'),
(1936, '25', '湖南', '2514', '湘西', '251406', '泸溪', 101251506, 'x', 'l'),
(1937, '25', '湖南', '2514', '湘西', '251407', '龙山', 101251507, 'x', 'l'),
(1938, '25', '湖南', '2514', '湘西', '251408', '花垣', 101251508, 'x', 'h'),
(1939, '26', '贵州', '2601', '贵阳', '260101', '贵阳', 101260101, 'g', 'g'),
(1940, '26', '贵州', '2601', '贵阳', '260102', '白云', 101260102, 'g', 'b'),
(1941, '26', '贵州', '2601', '贵阳', '260103', '花溪', 101260103, 'g', 'h'),
(1942, '26', '贵州', '2601', '贵阳', '260104', '乌当', 101260104, 'g', 'w'),
(1943, '26', '贵州', '2601', '贵阳', '260105', '息烽', 101260105, 'g', 'x'),
(1944, '26', '贵州', '2601', '贵阳', '260106', '开阳', 101260106, 'g', 'k'),
(1945, '26', '贵州', '2601', '贵阳', '260107', '修文', 101260107, 'g', 'x'),
(1946, '26', '贵州', '2601', '贵阳', '260108', '清镇', 101260108, 'g', 'q'),
(1947, '26', '贵州', '2601', '贵阳', '260109', '小河', 101260109, 'g', 'x'),
(1948, '26', '贵州', '2601', '贵阳', '260110', '云岩', 101260110, 'g', 'y'),
(1949, '26', '贵州', '2601', '贵阳', '260111', '南明', 101260111, 'g', 'n'),
(1950, '26', '贵州', '2602', '遵义', '260201', '遵义', 101260201, 'z', 'z'),
(1951, '26', '贵州', '2602', '遵义', '260202', '遵义县', 101260202, 'z', 'z'),
(1952, '26', '贵州', '2602', '遵义', '260203', '仁怀', 101260203, 'z', 'r'),
(1953, '26', '贵州', '2602', '遵义', '260204', '绥阳', 101260204, 'z', 's'),
(1954, '26', '贵州', '2602', '遵义', '260205', '湄潭', 101260205, 'z', 'm'),
(1955, '26', '贵州', '2602', '遵义', '260206', '凤冈', 101260206, 'z', 'f'),
(1956, '26', '贵州', '2602', '遵义', '260207', '桐梓', 101260207, 'z', 't'),
(1957, '26', '贵州', '2602', '遵义', '260208', '赤水', 101260208, 'z', 'c'),
(1958, '26', '贵州', '2602', '遵义', '260209', '习水', 101260209, 'z', 'x'),
(1959, '26', '贵州', '2602', '遵义', '260210', '道真', 101260210, 'z', 'd'),
(1960, '26', '贵州', '2602', '遵义', '260211', '正安', 101260211, 'z', 'z'),
(1961, '26', '贵州', '2602', '遵义', '260212', '务川', 101260212, 'z', 'w'),
(1962, '26', '贵州', '2602', '遵义', '260213', '余庆', 101260213, 'z', 'y'),
(1963, '26', '贵州', '2602', '遵义', '260214', '汇川', 101260214, 'z', 'h'),
(1964, '26', '贵州', '2602', '遵义', '260215', '红花岗', 101260215, 'z', 'h'),
(1965, '26', '贵州', '2603', '安顺', '260301', '安顺', 101260301, 'a', 'a'),
(1966, '26', '贵州', '2603', '安顺', '260302', '普定', 101260302, 'a', 'p'),
(1967, '26', '贵州', '2603', '安顺', '260303', '镇宁', 101260303, 'a', 'z'),
(1968, '26', '贵州', '2603', '安顺', '260304', '平坝', 101260304, 'a', 'p'),
(1969, '26', '贵州', '2603', '安顺', '260305', '紫云', 101260305, 'a', 'z'),
(1970, '26', '贵州', '2603', '安顺', '260306', '关岭', 101260306, 'a', 'g'),
(1971, '26', '贵州', '2604', '黔南', '260401', '黔南', 101260401, 'q', 'q'),
(1972, '26', '贵州', '2604', '黔南', '260402', '贵定', 101260402, 'q', 'g'),
(1973, '26', '贵州', '2604', '黔南', '260403', '瓮安', 101260403, 'q', 'w'),
(1974, '26', '贵州', '2604', '黔南', '260404', '长顺', 101260404, 'q', 'c'),
(1975, '26', '贵州', '2604', '黔南', '260405', '福泉', 101260405, 'q', 'f'),
(1976, '26', '贵州', '2604', '黔南', '260406', '惠水', 101260406, 'q', 'h'),
(1977, '26', '贵州', '2604', '黔南', '260407', '龙里', 101260407, 'q', 'l'),
(1978, '26', '贵州', '2604', '黔南', '260408', '罗甸', 101260408, 'q', 'l'),
(1979, '26', '贵州', '2604', '黔南', '260409', '平塘', 101260409, 'q', 'p'),
(1980, '26', '贵州', '2604', '黔南', '260410', '独山', 101260410, 'q', 'd'),
(1981, '26', '贵州', '2604', '黔南', '260411', '三都', 101260411, 'q', 's'),
(1982, '26', '贵州', '2604', '黔南', '260412', '荔波', 101260412, 'q', 'l'),
(1983, '26', '贵州', '2605', '黔东南', '260501', '黔东南', 101260501, 'q', 'q'),
(1984, '26', '贵州', '2605', '黔东南', '260502', '岑巩', 101260502, 'q', 'c'),
(1985, '26', '贵州', '2605', '黔东南', '260503', '施秉', 101260503, 'q', 's'),
(1986, '26', '贵州', '2605', '黔东南', '260504', '镇远', 101260504, 'q', 'z'),
(1987, '26', '贵州', '2605', '黔东南', '260505', '黄平', 101260505, 'q', 'h'),
(1988, '26', '贵州', '2605', '黔东南', '260506', '麻江', 101260507, 'q', 'm'),
(1989, '26', '贵州', '2605', '黔东南', '260507', '丹寨', 101260508, 'q', 'd'),
(1990, '26', '贵州', '2605', '黔东南', '260508', '三穗', 101260509, 'q', 's'),
(1991, '26', '贵州', '2605', '黔东南', '260509', '台江', 101260510, 'q', 't'),
(1992, '26', '贵州', '2605', '黔东南', '260510', '剑河', 101260511, 'q', 'j'),
(1993, '26', '贵州', '2605', '黔东南', '260511', '雷山', 101260512, 'q', 'l'),
(1994, '26', '贵州', '2605', '黔东南', '260512', '黎平', 101260513, 'q', 'l'),
(1995, '26', '贵州', '2605', '黔东南', '260513', '天柱', 101260514, 'q', 't'),
(1996, '26', '贵州', '2605', '黔东南', '260514', '锦屏', 101260515, 'q', 'j'),
(1997, '26', '贵州', '2605', '黔东南', '260515', '榕江', 101260516, 'q', 'r'),
(1998, '26', '贵州', '2605', '黔东南', '260516', '从江', 101260517, 'q', 'c'),
(1999, '26', '贵州', '2606', '铜仁', '260601', '铜仁', 101260601, 't', 't'),
(2000, '26', '贵州', '2606', '铜仁', '260602', '江口', 101260602, 't', 'j'),
(2001, '26', '贵州', '2606', '铜仁', '260603', '玉屏', 101260603, 't', 'y'),
(2002, '26', '贵州', '2606', '铜仁', '260604', '万山', 101260604, 't', 'w'),
(2003, '26', '贵州', '2606', '铜仁', '260605', '思南', 101260605, 't', 's'),
(2004, '26', '贵州', '2606', '铜仁', '260606', '印江', 101260607, 't', 'y'),
(2005, '26', '贵州', '2606', '铜仁', '260607', '石阡', 101260608, 't', 's'),
(2006, '26', '贵州', '2606', '铜仁', '260608', '沿河', 101260609, 't', 'y'),
(2007, '26', '贵州', '2606', '铜仁', '260609', '德江', 101260610, 't', 'd'),
(2008, '26', '贵州', '2606', '铜仁', '260610', '松桃', 101260611, 't', 's'),
(2009, '26', '贵州', '2607', '毕节', '260701', '毕节', 101260701, 'b', 'b'),
(2010, '26', '贵州', '2607', '毕节', '260702', '赫章', 101260702, 'b', 'h'),
(2011, '26', '贵州', '2607', '毕节', '260703', '金沙', 101260703, 'b', 'j'),
(2012, '26', '贵州', '2607', '毕节', '260704', '威宁', 101260704, 'b', 'w'),
(2013, '26', '贵州', '2607', '毕节', '260705', '大方', 101260705, 'b', 'd'),
(2014, '26', '贵州', '2607', '毕节', '260706', '纳雍', 101260706, 'b', 'n'),
(2015, '26', '贵州', '2607', '毕节', '260707', '织金', 101260707, 'b', 'z'),
(2016, '26', '贵州', '2607', '毕节', '260708', '黔西', 101260708, 'b', 'q'),
(2017, '26', '贵州', '2608', '六盘水', '260801', '六盘水', 101260801, 'l', 'l'),
(2018, '26', '贵州', '2608', '六盘水', '260802', '六枝', 101260802, 'l', 'l'),
(2019, '26', '贵州', '2608', '六盘水', '260803', '盘县', 101260804, 'l', 'p'),
(2020, '26', '贵州', '2609', '黔西南', '260901', '黔西南', 101260901, 'q', 'q'),
(2021, '26', '贵州', '2609', '黔西南', '260902', '晴隆', 101260902, 'q', 'q'),
(2022, '26', '贵州', '2609', '黔西南', '260903', '兴仁', 101260903, 'q', 'x'),
(2023, '26', '贵州', '2609', '黔西南', '260904', '贞丰', 101260904, 'q', 'z'),
(2024, '26', '贵州', '2609', '黔西南', '260905', '望谟', 101260905, 'q', 'w'),
(2025, '26', '贵州', '2609', '黔西南', '260906', '安龙', 101260907, 'q', 'a'),
(2026, '26', '贵州', '2609', '黔西南', '260907', '册亨', 101260908, 'q', 'c'),
(2027, '26', '贵州', '2609', '黔西南', '260908', '普安', 101260909, 'q', 'p'),
(2028, '27', '四川', '2701', '成都', '270101', '成都', 101270101, 'c', 'c'),
(2029, '27', '四川', '2701', '成都', '270102', '龙泉驿', 101270102, 'c', 'l'),
(2030, '27', '四川', '2701', '成都', '270103', '新都', 101270103, 'c', 'x'),
(2031, '27', '四川', '2701', '成都', '270104', '温江', 101270104, 'c', 'w'),
(2032, '27', '四川', '2701', '成都', '270105', '金堂', 101270105, 'c', 'j'),
(2033, '27', '四川', '2701', '成都', '270106', '双流', 101270106, 'c', 's'),
(2034, '27', '四川', '2701', '成都', '270107', '郫县', 101270107, 'c', 'p'),
(2035, '27', '四川', '2701', '成都', '270108', '大邑', 101270108, 'c', 'd'),
(2036, '27', '四川', '2701', '成都', '270109', '蒲江', 101270109, 'c', 'p'),
(2037, '27', '四川', '2701', '成都', '270110', '新津', 101270110, 'c', 'x'),
(2038, '27', '四川', '2701', '成都', '270111', '都江堰', 101270111, 'c', 'd'),
(2039, '27', '四川', '2701', '成都', '270112', '彭州', 101270112, 'c', 'p'),
(2040, '27', '四川', '2701', '成都', '270113', '邛崃', 101270113, 'c', 'q'),
(2041, '27', '四川', '2701', '成都', '270114', '崇州', 101270114, 'c', 'c'),
(2042, '27', '四川', '2702', '攀枝花', '270201', '攀枝花', 101270201, 'p', 'p'),
(2043, '27', '四川', '2702', '攀枝花', '270202', '仁和', 101270202, 'p', 'r'),
(2044, '27', '四川', '2702', '攀枝花', '270203', '米易', 101270203, 'p', 'm'),
(2045, '27', '四川', '2702', '攀枝花', '270204', '盐边', 101270204, 'p', 'y'),
(2046, '27', '四川', '2703', '自贡', '270301', '自贡', 101270301, 'z', 'z'),
(2047, '27', '四川', '2703', '自贡', '270302', '富顺', 101270302, 'z', 'f'),
(2048, '27', '四川', '2703', '自贡', '270303', '荣县', 101270303, 'z', 'r'),
(2049, '27', '四川', '2704', '绵阳', '270401', '绵阳', 101270401, 'm', 'm'),
(2050, '27', '四川', '2704', '绵阳', '270402', '三台', 101270402, 'm', 's'),
(2051, '27', '四川', '2704', '绵阳', '270403', '盐亭', 101270403, 'm', 'y'),
(2052, '27', '四川', '2704', '绵阳', '270404', '安县', 101270404, 'm', 'a'),
(2053, '27', '四川', '2704', '绵阳', '270405', '梓潼', 101270405, 'm', 'z'),
(2054, '27', '四川', '2704', '绵阳', '270406', '北川', 101270406, 'm', 'b'),
(2055, '27', '四川', '2704', '绵阳', '270407', '平武', 101270407, 'm', 'p'),
(2056, '27', '四川', '2704', '绵阳', '270408', '江油', 101270408, 'm', 'j'),
(2057, '27', '四川', '2705', '南充', '270501', '南充', 101270501, 'n', 'n'),
(2058, '27', '四川', '2705', '南充', '270502', '南部', 101270502, 'n', 'n'),
(2059, '27', '四川', '2705', '南充', '270503', '营山', 101270503, 'n', 'y'),
(2060, '27', '四川', '2705', '南充', '270504', '蓬安', 101270504, 'n', 'p'),
(2061, '27', '四川', '2705', '南充', '270505', '仪陇', 101270505, 'n', 'y'),
(2062, '27', '四川', '2705', '南充', '270506', '西充', 101270506, 'n', 'x'),
(2063, '27', '四川', '2705', '南充', '270507', '阆中', 101270507, 'n', 'l'),
(2064, '27', '四川', '2706', '达州', '270601', '达州', 101270601, 'd', 'd'),
(2065, '27', '四川', '2706', '达州', '270602', '宣汉', 101270602, 'd', 'x'),
(2066, '27', '四川', '2706', '达州', '270603', '开江', 101270603, 'd', 'k'),
(2067, '27', '四川', '2706', '达州', '270604', '大竹', 101270604, 'd', 'd'),
(2068, '27', '四川', '2706', '达州', '270605', '渠县', 101270605, 'd', 'q'),
(2069, '27', '四川', '2706', '达州', '270606', '万源', 101270606, 'd', 'w'),
(2070, '27', '四川', '2706', '达州', '270607', '达川', 101270607, 'd', 'd'),
(2071, '27', '四川', '2706', '达州', '270608', '达县', 101270608, 'd', 'd'),
(2072, '27', '四川', '2707', '遂宁', '270701', '遂宁', 101270701, 's', 's'),
(2073, '27', '四川', '2707', '遂宁', '270702', '蓬溪', 101270702, 's', 'p'),
(2074, '27', '四川', '2707', '遂宁', '270703', '射洪', 101270703, 's', 's'),
(2075, '27', '四川', '2708', '广安', '270801', '广安', 101270801, 'g', 'g'),
(2076, '27', '四川', '2708', '广安', '270802', '岳池', 101270802, 'g', 'y'),
(2077, '27', '四川', '2708', '广安', '270803', '武胜', 101270803, 'g', 'w'),
(2078, '27', '四川', '2708', '广安', '270804', '邻水', 101270804, 'g', 'l'),
(2079, '27', '四川', '2708', '广安', '270805', '华蓥', 101270805, 'g', 'h'),
(2080, '27', '四川', '2709', '巴中', '270901', '巴中', 101270901, 'b', 'b'),
(2081, '27', '四川', '2709', '巴中', '270902', '通江', 101270902, 'b', 't'),
(2082, '27', '四川', '2709', '巴中', '270903', '南江', 101270903, 'b', 'n'),
(2083, '27', '四川', '2709', '巴中', '270904', '平昌', 101270904, 'b', 'p'),
(2084, '27', '四川', '2710', '泸州', '271001', '泸州', 101271001, 'z', 'l'),
(2085, '27', '四川', '2710', '泸州', '271002', '泸县', 101271003, 'z', 'l'),
(2086, '27', '四川', '2710', '泸州', '271003', '合江', 101271004, 'z', 'h'),
(2087, '27', '四川', '2710', '泸州', '271004', '叙永', 101271005, 'z', 'x'),
(2088, '27', '四川', '2710', '泸州', '271005', '古蔺', 101271006, 'z', 'g'),
(2089, '27', '四川', '2710', '泸州', '271006', '纳溪', 101271007, 'z', 'n'),
(2090, '27', '四川', '2711', '宜宾', '271101', '宜宾', 101271101, 'y', 'y'),
(2091, '27', '四川', '2711', '宜宾', '271102', '宜宾县', 101271103, 'y', 'y'),
(2092, '27', '四川', '2711', '宜宾', '271103', '南溪', 101271104, 'y', 'n'),
(2093, '27', '四川', '2711', '宜宾', '271104', '江安', 101271105, 'y', 'j'),
(2094, '27', '四川', '2711', '宜宾', '271105', '长宁', 101271106, 'y', 'c'),
(2095, '27', '四川', '2711', '宜宾', '271106', '高县', 101271107, 'y', 'g'),
(2096, '27', '四川', '2711', '宜宾', '271107', '珙县', 101271108, 'y', 'g'),
(2097, '27', '四川', '2711', '宜宾', '271108', '筠连', 101271109, 'y', 'j'),
(2098, '27', '四川', '2711', '宜宾', '271109', '兴文', 101271110, 'y', 'x'),
(2099, '27', '四川', '2711', '宜宾', '271110', '屏山', 101271111, 'y', 'p'),
(2100, '27', '四川', '2712', '内江', '271201', '内江', 101271201, 'n', 'n'),
(2101, '27', '四川', '2712', '内江', '271202', '东兴', 101271202, 'n', 'd'),
(2102, '27', '四川', '2712', '内江', '271203', '威远', 101271203, 'n', 'w'),
(2103, '27', '四川', '2712', '内江', '271204', '资中', 101271204, 'n', 'z'),
(2104, '27', '四川', '2712', '内江', '271205', '隆昌', 101271205, 'n', 'l'),
(2105, '27', '四川', '2713', '资阳', '271301', '资阳', 101271301, 'z', 'z'),
(2106, '27', '四川', '2713', '资阳', '271302', '安岳', 101271302, 'z', 'a'),
(2107, '27', '四川', '2713', '资阳', '271303', '乐至', 101271303, 'z', 'l'),
(2108, '27', '四川', '2713', '资阳', '271304', '简阳', 101271304, 'z', 'j'),
(2109, '27', '四川', '2714', '乐山', '271401', '乐山', 101271401, 'l', 'l'),
(2110, '27', '四川', '2714', '乐山', '271402', '犍为', 101271402, 'l', 'q'),
(2111, '27', '四川', '2714', '乐山', '271403', '井研', 101271403, 'l', 'j'),
(2112, '27', '四川', '2714', '乐山', '271404', '夹江', 101271404, 'l', 'j'),
(2113, '27', '四川', '2714', '乐山', '271405', '沐川', 101271405, 'l', 'm'),
(2114, '27', '四川', '2714', '乐山', '271406', '峨边', 101271406, 'l', 'e'),
(2115, '27', '四川', '2714', '乐山', '271407', '马边', 101271407, 'l', 'm'),
(2116, '27', '四川', '2714', '乐山', '271408', '峨眉', 101271408, 'l', 'e'),
(2117, '27', '四川', '2714', '乐山', '271409', '峨眉山', 101271409, 'l', 'e'),
(2118, '27', '四川', '2715', '眉山', '271501', '眉山', 101271501, 'm', 'm'),
(2119, '27', '四川', '2715', '眉山', '271502', '仁寿', 101271502, 'm', 'r'),
(2120, '27', '四川', '2715', '眉山', '271503', '彭山', 101271503, 'm', 'p'),
(2121, '27', '四川', '2715', '眉山', '271504', '洪雅', 101271504, 'm', 'h'),
(2122, '27', '四川', '2715', '眉山', '271505', '丹棱', 101271505, 'm', 'd'),
(2123, '27', '四川', '2715', '眉山', '271506', '青神', 101271506, 'm', 'q'),
(2124, '27', '四川', '2716', '凉山', '271601', '凉山', 101271601, 'l', 'l'),
(2125, '27', '四川', '2716', '凉山', '271602', '木里', 101271603, 'l', 'm'),
(2126, '27', '四川', '2716', '凉山', '271603', '盐源', 101271604, 'l', 'y'),
(2127, '27', '四川', '2716', '凉山', '271604', '德昌', 101271605, 'l', 'd'),
(2128, '27', '四川', '2716', '凉山', '271605', '会理', 101271606, 'l', 'h'),
(2129, '27', '四川', '2716', '凉山', '271606', '会东', 101271607, 'l', 'h'),
(2130, '27', '四川', '2716', '凉山', '271607', '宁南', 101271608, 'l', 'n'),
(2131, '27', '四川', '2716', '凉山', '271608', '普格', 101271609, 'l', 'p'),
(2132, '27', '四川', '2716', '凉山', '271609', '西昌', 101271610, 'l', 'x'),
(2133, '27', '四川', '2716', '凉山', '271610', '金阳', 101271611, 'l', 'j'),
(2134, '27', '四川', '2716', '凉山', '271611', '昭觉', 101271612, 'l', 'z'),
(2135, '27', '四川', '2716', '凉山', '271612', '喜德', 101271613, 'l', 'x'),
(2136, '27', '四川', '2716', '凉山', '271613', '冕宁', 101271614, 'l', 'm'),
(2137, '27', '四川', '2716', '凉山', '271614', '越西', 101271615, 'l', 'y'),
(2138, '27', '四川', '2716', '凉山', '271615', '甘洛', 101271616, 'l', 'g'),
(2139, '27', '四川', '2716', '凉山', '271616', '雷波', 101271617, 'l', 'l'),
(2140, '27', '四川', '2716', '凉山', '271617', '美姑', 101271618, 'l', 'm'),
(2141, '27', '四川', '2716', '凉山', '271618', '布拖', 101271619, 'l', 'b'),
(2142, '27', '四川', '2717', '雅安', '271701', '雅安', 101271701, 'y', 'y'),
(2143, '27', '四川', '2717', '雅安', '271702', '名山', 101271702, 'y', 'm'),
(2144, '27', '四川', '2717', '雅安', '271703', '荥经', 101271703, 'y', 'y'),
(2145, '27', '四川', '2717', '雅安', '271704', '汉源', 101271704, 'y', 'h'),
(2146, '27', '四川', '2717', '雅安', '271705', '石棉', 101271705, 'y', 's'),
(2147, '27', '四川', '2717', '雅安', '271706', '天全', 101271706, 'y', 't'),
(2148, '27', '四川', '2717', '雅安', '271707', '芦山', 101271707, 'y', 'l'),
(2149, '27', '四川', '2717', '雅安', '271708', '宝兴', 101271708, 'y', 'b'),
(2150, '27', '四川', '2718', '甘孜', '271801', '甘孜', 101271801, 'g', 'g'),
(2151, '27', '四川', '2718', '甘孜', '271802', '康定', 101271802, 'g', 'k'),
(2152, '27', '四川', '2718', '甘孜', '271803', '泸定', 101271803, 'g', 'l'),
(2153, '27', '四川', '2718', '甘孜', '271804', '丹巴', 101271804, 'g', 'd'),
(2154, '27', '四川', '2718', '甘孜', '271805', '九龙', 101271805, 'g', 'j'),
(2155, '27', '四川', '2718', '甘孜', '271806', '雅江', 101271806, 'g', 'y'),
(2156, '27', '四川', '2718', '甘孜', '271807', '道孚', 101271807, 'g', 'd'),
(2157, '27', '四川', '2718', '甘孜', '271808', '炉霍', 101271808, 'g', 'l'),
(2158, '27', '四川', '2718', '甘孜', '271809', '新龙', 101271809, 'g', 'x'),
(2159, '27', '四川', '2718', '甘孜', '271810', '德格', 101271810, 'g', 'd'),
(2160, '27', '四川', '2718', '甘孜', '271811', '白玉', 101271811, 'g', 'b'),
(2161, '27', '四川', '2718', '甘孜', '271812', '石渠', 101271812, 'g', 's'),
(2162, '27', '四川', '2718', '甘孜', '271813', '色达', 101271813, 'g', 's'),
(2163, '27', '四川', '2718', '甘孜', '271814', '理塘', 101271814, 'g', 'l'),
(2164, '27', '四川', '2718', '甘孜', '271815', '巴塘', 101271815, 'g', 'b'),
(2165, '27', '四川', '2718', '甘孜', '271816', '乡城', 101271816, 'g', 'x'),
(2166, '27', '四川', '2718', '甘孜', '271817', '稻城', 101271817, 'g', 'd'),
(2167, '27', '四川', '2718', '甘孜', '271818', '得荣', 101271818, 'g', 'd'),
(2168, '27', '四川', '2719', '阿坝', '271901', '阿坝', 101271901, 'a', 'a'),
(2169, '27', '四川', '2719', '阿坝', '271902', '汶川', 101271902, 'a', 'w'),
(2170, '27', '四川', '2719', '阿坝', '271903', '理县', 101271903, 'a', 'l'),
(2171, '27', '四川', '2719', '阿坝', '271904', '茂县', 101271904, 'a', 'm'),
(2172, '27', '四川', '2719', '阿坝', '271905', '松潘', 101271905, 'a', 's'),
(2173, '27', '四川', '2719', '阿坝', '271906', '九寨沟', 101271906, 'a', 'j'),
(2174, '27', '四川', '2719', '阿坝', '271907', '金川', 101271907, 'a', 'j'),
(2175, '27', '四川', '2719', '阿坝', '271908', '小金', 101271908, 'a', 'x'),
(2176, '27', '四川', '2719', '阿坝', '271909', '黑水', 101271909, 'a', 'h'),
(2177, '27', '四川', '2719', '阿坝', '271910', '马尔康', 101271910, 'a', 'm'),
(2178, '27', '四川', '2719', '阿坝', '271911', '壤塘', 101271911, 'a', 'r'),
(2179, '27', '四川', '2719', '阿坝', '271912', '若尔盖', 101271912, 'a', 'r'),
(2180, '27', '四川', '2719', '阿坝', '271913', '红原', 101271913, 'a', 'h'),
(2181, '27', '四川', '2720', '德阳', '272001', '德阳', 101272001, 'd', 'd'),
(2182, '27', '四川', '2720', '德阳', '272002', '中江', 101272002, 'd', 'z'),
(2183, '27', '四川', '2720', '德阳', '272003', '广汉', 101272003, 'd', 'g'),
(2184, '27', '四川', '2720', '德阳', '272004', '什邡', 101272004, 'd', 's'),
(2185, '27', '四川', '2720', '德阳', '272005', '绵竹', 101272005, 'd', 'm'),
(2186, '27', '四川', '2720', '德阳', '272006', '罗江', 101272006, 'd', 'l'),
(2187, '27', '四川', '2721', '广元', '272101', '广元', 101272101, 'g', 'g'),
(2188, '27', '四川', '2721', '广元', '272102', '旺苍', 101272102, 'g', 'w'),
(2189, '27', '四川', '2721', '广元', '272103', '青川', 101272103, 'g', 'q'),
(2190, '27', '四川', '2721', '广元', '272104', '剑阁', 101272104, 'g', 'j'),
(2191, '27', '四川', '2721', '广元', '272105', '苍溪', 101272105, 'g', 'c'),
(2192, '28', '广东', '2801', '广州', '280101', '广州', 101280101, 'g', 'g'),
(2193, '28', '广东', '2801', '广州', '280102', '番禺', 101280102, 'g', 'f'),
(2194, '28', '广东', '2801', '广州', '280103', '从化', 101280103, 'g', 'c'),
(2195, '28', '广东', '2801', '广州', '280104', '增城', 101280104, 'g', 'z'),
(2196, '28', '广东', '2801', '广州', '280105', '花都', 101280105, 'g', 'h'),
(2197, '28', '广东', '2802', '韶关', '280201', '韶关', 101280201, 's', 's'),
(2198, '28', '广东', '2802', '韶关', '280202', '乳源', 101280202, 's', 'r'),
(2199, '28', '广东', '2802', '韶关', '280203', '始兴', 101280203, 's', 's'),
(2200, '28', '广东', '2802', '韶关', '280204', '翁源', 101280204, 's', 'w'),
(2201, '28', '广东', '2802', '韶关', '280205', '乐昌', 101280205, 's', 'l'),
(2202, '28', '广东', '2802', '韶关', '280206', '仁化', 101280206, 's', 'r'),
(2203, '28', '广东', '2802', '韶关', '280207', '南雄', 101280207, 's', 'n'),
(2204, '28', '广东', '2802', '韶关', '280208', '新丰', 101280208, 's', 'x'),
(2205, '28', '广东', '2802', '韶关', '280209', '曲江', 101280209, 's', 'q'),
(2206, '28', '广东', '2802', '韶关', '280210', '浈江', 101280210, 's', 'z'),
(2207, '28', '广东', '2802', '韶关', '280211', '武江', 101280211, 's', 'w'),
(2208, '28', '广东', '2803', '惠州', '280301', '惠州', 101280301, 'h', 'h'),
(2209, '28', '广东', '2803', '惠州', '280302', '博罗', 101280302, 'h', 'b'),
(2210, '28', '广东', '2803', '惠州', '280303', '惠阳', 101280303, 'h', 'h'),
(2211, '28', '广东', '2803', '惠州', '280304', '惠东', 101280304, 'h', 'h'),
(2212, '28', '广东', '2803', '惠州', '280305', '龙门', 101280305, 'h', 'l'),
(2213, '28', '广东', '2804', '梅州', '280401', '梅州', 101280401, 'm', 'm'),
(2214, '28', '广东', '2804', '梅州', '280402', '兴宁', 101280402, 'm', 'x'),
(2215, '28', '广东', '2804', '梅州', '280403', '蕉岭', 101280403, 'm', 'j'),
(2216, '28', '广东', '2804', '梅州', '280404', '大埔', 101280404, 'm', 'd'),
(2217, '28', '广东', '2804', '梅州', '280405', '丰顺', 101280406, 'm', 'f'),
(2218, '28', '广东', '2804', '梅州', '280406', '平远', 101280407, 'm', 'p'),
(2219, '28', '广东', '2804', '梅州', '280407', '五华', 101280408, 'm', 'w'),
(2220, '28', '广东', '2804', '梅州', '280408', '梅县', 101280409, 'm', 'm'),
(2221, '28', '广东', '2805', '汕头', '280501', '汕头', 101280501, 's', 's'),
(2222, '28', '广东', '2805', '汕头', '280502', '潮阳', 101280502, 's', 'c'),
(2223, '28', '广东', '2805', '汕头', '280503', '澄海', 101280503, 's', 'c'),
(2224, '28', '广东', '2805', '汕头', '280504', '南澳', 101280504, 's', 'n'),
(2225, '28', '广东', '2806', '深圳', '280601', '深圳', 101280601, 's', 's'),
(2226, '28', '广东', '2807', '珠海', '280701', '珠海', 101280701, 'z', 'z'),
(2227, '28', '广东', '2807', '珠海', '280702', '斗门', 101280702, 'z', 'd'),
(2228, '28', '广东', '2807', '珠海', '280703', '金湾', 101280703, 'z', 'j'),
(2229, '28', '广东', '2808', '佛山', '280801', '佛山', 101280800, 'f', 'f'),
(2230, '28', '广东', '2808', '佛山', '280802', '顺德', 101280801, 'f', 's'),
(2231, '28', '广东', '2808', '佛山', '280803', '三水', 101280802, 'f', 's'),
(2232, '28', '广东', '2808', '佛山', '280804', '南海', 101280803, 'f', 'n'),
(2233, '28', '广东', '2808', '佛山', '280805', '高明', 101280804, 'f', 'g'),
(2234, '28', '广东', '2809', '肇庆', '280901', '肇庆', 101280901, 'z', 'z'),
(2235, '28', '广东', '2809', '肇庆', '280902', '广宁', 101280902, 'z', 'g'),
(2236, '28', '广东', '2809', '肇庆', '280903', '四会', 101280903, 'z', 's'),
(2237, '28', '广东', '2809', '肇庆', '280904', '德庆', 101280905, 'z', 'd'),
(2238, '28', '广东', '2809', '肇庆', '280905', '怀集', 101280906, 'z', 'h'),
(2239, '28', '广东', '2809', '肇庆', '280906', '封开', 101280907, 'z', 'f'),
(2240, '28', '广东', '2809', '肇庆', '280907', '高要', 101280908, 'z', 'g'),
(2241, '28', '广东', '2810', '湛江', '281001', '湛江', 101281001, 'z', 'z'),
(2242, '28', '广东', '2810', '湛江', '281002', '吴川', 101281002, 'z', 'w'),
(2243, '28', '广东', '2810', '湛江', '281003', '雷州', 101281003, 'z', 'l'),
(2244, '28', '广东', '2810', '湛江', '281004', '徐闻', 101281004, 'z', 'x'),
(2245, '28', '广东', '2810', '湛江', '281005', '廉江', 101281005, 'z', 'l'),
(2246, '28', '广东', '2810', '湛江', '281006', '赤坎', 101281006, 'z', 'c'),
(2247, '28', '广东', '2810', '湛江', '281007', '遂溪', 101281007, 'z', 's'),
(2248, '28', '广东', '2810', '湛江', '281008', '坡头', 101281008, 'z', 'p'),
(2249, '28', '广东', '2810', '湛江', '281009', '霞山', 101281009, 'z', 'x'),
(2250, '28', '广东', '2810', '湛江', '281010', '麻章', 101281010, 'z', 'm'),
(2251, '28', '广东', '2811', '江门', '281101', '江门', 101281101, 'j', 'j'),
(2252, '28', '广东', '2811', '江门', '281102', '开平', 101281103, 'j', 'k'),
(2253, '28', '广东', '2811', '江门', '281103', '新会', 101281104, 'j', 'x'),
(2254, '28', '广东', '2811', '江门', '281104', '恩平', 101281105, 'j', 'e'),
(2255, '28', '广东', '2811', '江门', '281105', '台山', 101281106, 'j', 't'),
(2256, '28', '广东', '2811', '江门', '281106', '蓬江', 101281107, 'j', 'p'),
(2257, '28', '广东', '2811', '江门', '281107', '鹤山', 101281108, 'j', 'h'),
(2258, '28', '广东', '2811', '江门', '281108', '江海', 101281109, 'j', 'j'),
(2259, '28', '广东', '2812', '河源', '281201', '河源', 101281201, 'h', 'h'),
(2260, '28', '广东', '2812', '河源', '281202', '紫金', 101281202, 'h', 'z'),
(2261, '28', '广东', '2812', '河源', '281203', '连平', 101281203, 'h', 'l'),
(2262, '28', '广东', '2812', '河源', '281204', '和平', 101281204, 'h', 'h'),
(2263, '28', '广东', '2812', '河源', '281205', '龙川', 101281205, 'h', 'l'),
(2264, '28', '广东', '2812', '河源', '281206', '东源', 101281206, 'h', 'd'),
(2265, '28', '广东', '2813', '清远', '281301', '清远', 101281301, 'q', 'q'),
(2266, '28', '广东', '2813', '清远', '281302', '连南', 101281302, 'q', 'l'),
(2267, '28', '广东', '2813', '清远', '281303', '连州', 101281303, 'q', 'l'),
(2268, '28', '广东', '2813', '清远', '281304', '连山', 101281304, 'q', 'l'),
(2269, '28', '广东', '2813', '清远', '281305', '阳山', 101281305, 'q', 'y'),
(2270, '28', '广东', '2813', '清远', '281306', '佛冈', 101281306, 'q', 'f'),
(2271, '28', '广东', '2813', '清远', '281307', '英德', 101281307, 'q', 'y'),
(2272, '28', '广东', '2813', '清远', '281308', '清新', 101281308, 'q', 'q'),
(2273, '28', '广东', '2814', '云浮', '281401', '云浮', 101281401, 'y', 'y'),
(2274, '28', '广东', '2814', '云浮', '281402', '罗定', 101281402, 'y', 'l'),
(2275, '28', '广东', '2814', '云浮', '281403', '新兴', 101281403, 'y', 'x'),
(2276, '28', '广东', '2814', '云浮', '281404', '郁南', 101281404, 'y', 'y'),
(2277, '28', '广东', '2814', '云浮', '281405', '云安', 101281406, 'y', 'y'),
(2278, '28', '广东', '2815', '潮州', '281501', '潮州', 101281501, 'c', 'c'),
(2279, '28', '广东', '2815', '潮州', '281502', '饶平', 101281502, 'c', 'r'),
(2280, '28', '广东', '2815', '潮州', '281503', '潮安', 101281503, 'c', 'c'),
(2281, '28', '广东', '2816', '东莞', '281601', '东莞', 101281601, 'd', 'd'),
(2282, '28', '广东', '2817', '中山', '281701', '中山', 101281701, 'z', 'z'),
(2283, '28', '广东', '2818', '阳江', '281801', '阳江', 101281801, 'y', 'y'),
(2284, '28', '广东', '2818', '阳江', '281802', '阳春', 101281802, 'y', 'y'),
(2285, '28', '广东', '2818', '阳江', '281803', '阳东', 101281803, 'y', 'y'),
(2286, '28', '广东', '2818', '阳江', '281804', '阳西', 101281804, 'y', 'y'),
(2287, '28', '广东', '2819', '揭阳', '281901', '揭阳', 101281901, 'j', 'j'),
(2288, '28', '广东', '2819', '揭阳', '281902', '揭西', 101281902, 'j', 'j'),
(2289, '28', '广东', '2819', '揭阳', '281903', '普宁', 101281903, 'j', 'p'),
(2290, '28', '广东', '2819', '揭阳', '281904', '惠来', 101281904, 'j', 'h'),
(2291, '28', '广东', '2819', '揭阳', '281905', '揭东', 101281905, 'j', 'j'),
(2292, '28', '广东', '2820', '茂名', '282001', '茂名', 101282001, 'm', 'm'),
(2293, '28', '广东', '2820', '茂名', '282002', '高州', 101282002, 'm', 'g'),
(2294, '28', '广东', '2820', '茂名', '282003', '化州', 101282003, 'm', 'h'),
(2295, '28', '广东', '2820', '茂名', '282004', '电白', 101282004, 'm', 'd'),
(2296, '28', '广东', '2820', '茂名', '282005', '信宜', 101282005, 'm', 'x'),
(2297, '28', '广东', '2820', '茂名', '282006', '茂港', 101282006, 'm', 'm'),
(2298, '28', '广东', '2821', '汕尾', '282101', '汕尾', 101282101, 's', 's'),
(2299, '28', '广东', '2821', '汕尾', '282102', '海丰', 101282102, 's', 'h'),
(2300, '28', '广东', '2821', '汕尾', '282103', '陆丰', 101282103, 's', 'l'),
(2301, '28', '广东', '2821', '汕尾', '282104', '陆河', 101282104, 's', 'l'),
(2302, '29', '云南', '2901', '昆明', '290101', '昆明', 101290101, 'k', 'k'),
(2303, '29', '云南', '2901', '昆明', '290102', '东川', 101290103, 'k', 'd'),
(2304, '29', '云南', '2901', '昆明', '290103', '寻甸', 101290104, 'k', 'x'),
(2305, '29', '云南', '2901', '昆明', '290104', '晋宁', 101290105, 'k', 'j'),
(2306, '29', '云南', '2901', '昆明', '290105', '宜良', 101290106, 'k', 'y'),
(2307, '29', '云南', '2901', '昆明', '290106', '石林', 101290107, 'k', 's'),
(2308, '29', '云南', '2901', '昆明', '290107', '呈贡', 101290108, 'k', 'c'),
(2309, '29', '云南', '2901', '昆明', '290108', '富民', 101290109, 'k', 'f'),
(2310, '29', '云南', '2901', '昆明', '290109', '嵩明', 101290110, 'k', 's'),
(2311, '29', '云南', '2901', '昆明', '290110', '禄劝', 101290111, 'k', 'l'),
(2312, '29', '云南', '2901', '昆明', '290111', '安宁', 101290112, 'k', 'a'),
(2313, '29', '云南', '2901', '昆明', '290112', '太华山', 101290113, 'k', 't'),
(2314, '29', '云南', '2902', '大理', '290201', '大理', 101290201, 'd', 'd'),
(2315, '29', '云南', '2902', '大理', '290202', '云龙', 101290202, 'd', 'y'),
(2316, '29', '云南', '2902', '大理', '290203', '漾濞', 101290203, 'd', 'y'),
(2317, '29', '云南', '2902', '大理', '290204', '永平', 101290204, 'd', 'y'),
(2318, '29', '云南', '2902', '大理', '290205', '宾川', 101290205, 'd', 'b'),
(2319, '29', '云南', '2902', '大理', '290206', '弥渡', 101290206, 'd', 'm'),
(2320, '29', '云南', '2902', '大理', '290207', '祥云', 101290207, 'd', 'x'),
(2321, '29', '云南', '2902', '大理', '290208', '巍山', 101290208, 'd', 'w'),
(2322, '29', '云南', '2902', '大理', '290209', '剑川', 101290209, 'd', 'j'),
(2323, '29', '云南', '2902', '大理', '290210', '洱源', 101290210, 'd', 'e'),
(2324, '29', '云南', '2902', '大理', '290211', '鹤庆', 101290211, 'd', 'h'),
(2325, '29', '云南', '2902', '大理', '290212', '南涧', 101290212, 'd', 'n'),
(2326, '29', '云南', '2903', '红河', '290301', '红河', 101290301, 'h', 'h'),
(2327, '29', '云南', '2903', '红河', '290302', '石屏', 101290302, 'h', 's'),
(2328, '29', '云南', '2903', '红河', '290303', '建水', 101290303, 'h', 'j'),
(2329, '29', '云南', '2903', '红河', '290304', '弥勒', 101290304, 'h', 'm'),
(2330, '29', '云南', '2903', '红河', '290305', '元阳', 101290305, 'h', 'y'),
(2331, '29', '云南', '2903', '红河', '290306', '绿春', 101290306, 'h', 'l'),
(2332, '29', '云南', '2903', '红河', '290307', '开远', 101290307, 'h', 'k'),
(2333, '29', '云南', '2903', '红河', '290308', '个旧', 101290308, 'h', 'g'),
(2334, '29', '云南', '2903', '红河', '290309', '蒙自', 101290309, 'h', 'm'),
(2335, '29', '云南', '2903', '红河', '290310', '屏边', 101290310, 'h', 'p'),
(2336, '29', '云南', '2903', '红河', '290311', '泸西', 101290311, 'h', 'l'),
(2337, '29', '云南', '2903', '红河', '290312', '金平', 101290312, 'h', 'j'),
(2338, '29', '云南', '2903', '红河', '290313', '河口', 101290313, 'h', 'h'),
(2339, '29', '云南', '2904', '曲靖', '290401', '曲靖', 101290401, 'q', 'q'),
(2340, '29', '云南', '2904', '曲靖', '290402', '沾益', 101290402, 'q', 'z'),
(2341, '29', '云南', '2904', '曲靖', '290403', '陆良', 101290403, 'q', 'l'),
(2342, '29', '云南', '2904', '曲靖', '290404', '富源', 101290404, 'q', 'f'),
(2343, '29', '云南', '2904', '曲靖', '290405', '马龙', 101290405, 'q', 'm'),
(2344, '29', '云南', '2904', '曲靖', '290406', '师宗', 101290406, 'q', 's'),
(2345, '29', '云南', '2904', '曲靖', '290407', '罗平', 101290407, 'q', 'l'),
(2346, '29', '云南', '2904', '曲靖', '290408', '会泽', 101290408, 'q', 'h'),
(2347, '29', '云南', '2904', '曲靖', '290409', '宣威', 101290409, 'q', 'x'),
(2348, '29', '云南', '2905', '保山', '290501', '保山', 101290501, 'b', 'b'),
(2349, '29', '云南', '2905', '保山', '290502', '龙陵', 101290503, 'b', 'l'),
(2350, '29', '云南', '2905', '保山', '290503', '施甸', 101290504, 'b', 's'),
(2351, '29', '云南', '2905', '保山', '290504', '昌宁', 101290505, 'b', 'c'),
(2352, '29', '云南', '2905', '保山', '290505', '腾冲', 101290506, 'b', 't'),
(2353, '29', '云南', '2906', '文山', '290601', '文山', 101290601, 'w', 'w'),
(2354, '29', '云南', '2906', '文山', '290602', '西畴', 101290602, 'w', 'x'),
(2355, '29', '云南', '2906', '文山', '290603', '马关', 101290603, 'w', 'm'),
(2356, '29', '云南', '2906', '文山', '290604', '麻栗坡', 101290604, 'w', 'm'),
(2357, '29', '云南', '2906', '文山', '290605', '砚山', 101290605, 'w', 'y'),
(2358, '29', '云南', '2906', '文山', '290606', '丘北', 101290606, 'w', 'q'),
(2359, '29', '云南', '2906', '文山', '290607', '广南', 101290607, 'w', 'g'),
(2360, '29', '云南', '2906', '文山', '290608', '富宁', 101290608, 'w', 'f'),
(2361, '29', '云南', '2907', '玉溪', '290701', '玉溪', 101290701, 'y', 'y'),
(2362, '29', '云南', '2907', '玉溪', '290702', '澄江', 101290702, 'y', 'c'),
(2363, '29', '云南', '2907', '玉溪', '290703', '江川', 101290703, 'y', 'j'),
(2364, '29', '云南', '2907', '玉溪', '290704', '通海', 101290704, 'y', 't'),
(2365, '29', '云南', '2907', '玉溪', '290705', '华宁', 101290705, 'y', 'h'),
(2366, '29', '云南', '2907', '玉溪', '290706', '新平', 101290706, 'y', 'x'),
(2367, '29', '云南', '2907', '玉溪', '290707', '易门', 101290707, 'y', 'y'),
(2368, '29', '云南', '2907', '玉溪', '290708', '峨山', 101290708, 'y', 'e'),
(2369, '29', '云南', '2907', '玉溪', '290709', '元江', 101290709, 'y', 'y'),
(2370, '29', '云南', '2908', '楚雄', '290801', '楚雄', 101290801, 'c', 'c'),
(2371, '29', '云南', '2908', '楚雄', '290802', '大姚', 101290802, 'c', 'd'),
(2372, '29', '云南', '2908', '楚雄', '290803', '元谋', 101290803, 'c', 'y'),
(2373, '29', '云南', '2908', '楚雄', '290804', '姚安', 101290804, 'c', 'y'),
(2374, '29', '云南', '2908', '楚雄', '290805', '牟定', 101290805, 'c', 'm'),
(2375, '29', '云南', '2908', '楚雄', '290806', '南华', 101290806, 'c', 'n'),
(2376, '29', '云南', '2908', '楚雄', '290807', '武定', 101290807, 'c', 'w'),
(2377, '29', '云南', '2908', '楚雄', '290808', '禄丰', 101290808, 'c', 'l'),
(2378, '29', '云南', '2908', '楚雄', '290809', '双柏', 101290809, 'c', 's'),
(2379, '29', '云南', '2908', '楚雄', '290810', '永仁', 101290810, 'c', 'y'),
(2380, '29', '云南', '2909', '普洱', '290901', '普洱', 101290901, 'p', 'p'),
(2381, '29', '云南', '2909', '普洱', '290902', '景谷', 101290902, 'p', 'j'),
(2382, '29', '云南', '2909', '普洱', '290903', '景东', 101290903, 'p', 'j'),
(2383, '29', '云南', '2909', '普洱', '290904', '澜沧', 101290904, 'p', 'l'),
(2384, '29', '云南', '2909', '普洱', '290905', '墨江', 101290906, 'p', 'm'),
(2385, '29', '云南', '2909', '普洱', '290906', '江城', 101290907, 'p', 'j'),
(2386, '29', '云南', '2909', '普洱', '290907', '孟连', 101290908, 'p', 'm'),
(2387, '29', '云南', '2909', '普洱', '290908', '西盟', 101290909, 'p', 'x'),
(2388, '29', '云南', '2909', '普洱', '290909', '镇沅', 101290911, 'p', 'z'),
(2389, '29', '云南', '2909', '普洱', '290910', '宁洱', 101290912, 'p', 'n'),
(2390, '29', '云南', '2910', '昭通', '291001', '昭通', 101291001, 'z', 'z'),
(2391, '29', '云南', '2910', '昭通', '291002', '鲁甸', 101291002, 'z', 'l'),
(2392, '29', '云南', '2910', '昭通', '291003', '彝良', 101291003, 'z', 'y'),
(2393, '29', '云南', '2910', '昭通', '291004', '镇雄', 101291004, 'z', 'z'),
(2394, '29', '云南', '2910', '昭通', '291005', '威信', 101291005, 'z', 'w'),
(2395, '29', '云南', '2910', '昭通', '291006', '巧家', 101291006, 'z', 'q'),
(2396, '29', '云南', '2910', '昭通', '291007', '绥江', 101291007, 'z', 's'),
(2397, '29', '云南', '2910', '昭通', '291008', '永善', 101291008, 'z', 'y'),
(2398, '29', '云南', '2910', '昭通', '291009', '盐津', 101291009, 'z', 'y'),
(2399, '29', '云南', '2910', '昭通', '291010', '大关', 101291010, 'z', 'd'),
(2400, '29', '云南', '2910', '昭通', '291011', '水富', 101291011, 'z', 's'),
(2401, '29', '云南', '2911', '临沧', '291101', '临沧', 101291101, 'l', 'l'),
(2402, '29', '云南', '2911', '临沧', '291102', '沧源', 101291102, 'l', 'c'),
(2403, '29', '云南', '2911', '临沧', '291103', '耿马', 101291103, 'l', 'g'),
(2404, '29', '云南', '2911', '临沧', '291104', '双江', 101291104, 'l', 's'),
(2405, '29', '云南', '2911', '临沧', '291105', '凤庆', 101291105, 'l', 'f'),
(2406, '29', '云南', '2911', '临沧', '291106', '永德', 101291106, 'l', 'y'),
(2407, '29', '云南', '2911', '临沧', '291107', '云县', 101291107, 'l', 'y'),
(2408, '29', '云南', '2911', '临沧', '291108', '镇康', 101291108, 'l', 'z'),
(2409, '29', '云南', '2912', '怒江', '291201', '怒江', 101291201, 'n', 'n'),
(2410, '29', '云南', '2912', '怒江', '291202', '福贡', 101291203, 'n', 'f'),
(2411, '29', '云南', '2912', '怒江', '291203', '兰坪', 101291204, 'n', 'l'),
(2412, '29', '云南', '2912', '怒江', '291204', '泸水', 101291205, 'n', 'l'),
(2413, '29', '云南', '2912', '怒江', '291205', '六库', 101291206, 'n', 'l'),
(2414, '29', '云南', '2912', '怒江', '291206', '贡山', 101291207, 'n', 'g'),
(2415, '29', '云南', '2913', '迪庆', '291301', '迪庆', 101291301, 'd', 'd'),
(2416, '29', '云南', '2913', '迪庆', '291302', '德钦', 101291302, 'd', 'd'),
(2417, '29', '云南', '2913', '迪庆', '291303', '维西', 101291303, 'd', 'w'),
(2418, '29', '云南', '2913', '迪庆', '291304', '中甸', 101291304, 'd', 'z'),
(2419, '29', '云南', '2914', '丽江', '291401', '丽江', 101291401, 'l', 'l'),
(2420, '29', '云南', '2914', '丽江', '291402', '永胜', 101291402, 'l', 'y'),
(2421, '29', '云南', '2914', '丽江', '291403', '华坪', 101291403, 'l', 'h'),
(2422, '29', '云南', '2914', '丽江', '291404', '宁蒗', 101291404, 'l', 'n'),
(2423, '29', '云南', '2915', '德宏', '291501', '德宏', 101291501, 'd', 'd'),
(2424, '29', '云南', '2915', '德宏', '291502', '陇川', 101291503, 'd', 'l'),
(2425, '29', '云南', '2915', '德宏', '291503', '盈江', 101291504, 'd', 'y'),
(2426, '29', '云南', '2915', '德宏', '291504', '瑞丽', 101291506, 'd', 'r'),
(2427, '29', '云南', '2915', '德宏', '291505', '梁河', 101291507, 'd', 'l'),
(2428, '29', '云南', '2915', '德宏', '291506', '潞西', 101291508, 'd', 'l'),
(2429, '29', '云南', '2916', '西双版纳', '291601', '西双版纳', 101291601, 'x', 'x'),
(2430, '29', '云南', '2916', '西双版纳', '291602', '勐海', 101291603, 'x', 'm'),
(2431, '29', '云南', '2916', '西双版纳', '291603', '勐腊', 101291605, 'x', 'm'),
(2432, '30', '广西', '3001', '南宁', '300101', '南宁', 101300101, 'n', 'n'),
(2433, '30', '广西', '3001', '南宁', '300102', '邕宁', 101300103, 'n', 'y'),
(2434, '30', '广西', '3001', '南宁', '300103', '横县', 101300104, 'n', 'h'),
(2435, '30', '广西', '3001', '南宁', '300104', '隆安', 101300105, 'n', 'l'),
(2436, '30', '广西', '3001', '南宁', '300105', '马山', 101300106, 'n', 'm'),
(2437, '30', '广西', '3001', '南宁', '300106', '上林', 101300107, 'n', 's');
INSERT INTO `cmstop_weather_city` (`id`, `province_id`, `province`, `town_id`, `town`, `city_id`, `city`, `weather_id`, `town_initial`, `city_initial`) VALUES
(2438, '30', '广西', '3001', '南宁', '300107', '武鸣', 101300108, 'n', 'w'),
(2439, '30', '广西', '3001', '南宁', '300108', '宾阳', 101300109, 'n', 'b'),
(2440, '30', '广西', '3002', '崇左', '300201', '崇左', 101300201, 'c', 'c'),
(2441, '30', '广西', '3002', '崇左', '300202', '天等', 101300202, 'c', 't'),
(2442, '30', '广西', '3002', '崇左', '300203', '龙州', 101300203, 'c', 'l'),
(2443, '30', '广西', '3002', '崇左', '300204', '凭祥', 101300204, 'c', 'p'),
(2444, '30', '广西', '3002', '崇左', '300205', '大新', 101300205, 'c', 'd'),
(2445, '30', '广西', '3002', '崇左', '300206', '扶绥', 101300206, 'c', 'f'),
(2446, '30', '广西', '3002', '崇左', '300207', '宁明', 101300207, 'c', 'n'),
(2447, '30', '广西', '3003', '柳州', '300301', '柳州', 101300301, 'l', 'l'),
(2448, '30', '广西', '3003', '柳州', '300302', '柳城', 101300302, 'l', 'l'),
(2449, '30', '广西', '3003', '柳州', '300303', '鹿寨', 101300304, 'l', 'l'),
(2450, '30', '广西', '3003', '柳州', '300304', '柳江', 101300305, 'l', 'l'),
(2451, '30', '广西', '3003', '柳州', '300305', '融安', 101300306, 'l', 'r'),
(2452, '30', '广西', '3003', '柳州', '300306', '融水', 101300307, 'l', 'r'),
(2453, '30', '广西', '3003', '柳州', '300307', '三江', 101300308, 'l', 's'),
(2454, '30', '广西', '3004', '来宾', '300401', '来宾', 101300401, 'l', 'l'),
(2455, '30', '广西', '3004', '来宾', '300402', '忻城', 101300402, 'l', 'x'),
(2456, '30', '广西', '3004', '来宾', '300403', '金秀', 101300403, 'l', 'j'),
(2457, '30', '广西', '3004', '来宾', '300404', '象州', 101300404, 'l', 'x'),
(2458, '30', '广西', '3004', '来宾', '300405', '武宣', 101300405, 'l', 'w'),
(2459, '30', '广西', '3004', '来宾', '300406', '合山', 101300406, 'l', 'h'),
(2460, '30', '广西', '3005', '桂林', '300501', '桂林', 101300501, 'g', 'g'),
(2461, '30', '广西', '3005', '桂林', '300502', '龙胜', 101300503, 'g', 'l'),
(2462, '30', '广西', '3005', '桂林', '300503', '永福', 101300504, 'g', 'y'),
(2463, '30', '广西', '3005', '桂林', '300504', '临桂', 101300505, 'g', 'l'),
(2464, '30', '广西', '3005', '桂林', '300505', '兴安', 101300506, 'g', 'x'),
(2465, '30', '广西', '3005', '桂林', '300506', '灵川', 101300507, 'g', 'l'),
(2466, '30', '广西', '3005', '桂林', '300507', '全州', 101300508, 'g', 'q'),
(2467, '30', '广西', '3005', '桂林', '300508', '灌阳', 101300509, 'g', 'g'),
(2468, '30', '广西', '3005', '桂林', '300509', '阳朔', 101300510, 'g', 'y'),
(2469, '30', '广西', '3005', '桂林', '300510', '恭城', 101300511, 'g', 'g'),
(2470, '30', '广西', '3005', '桂林', '300511', '平乐', 101300512, 'g', 'p'),
(2471, '30', '广西', '3005', '桂林', '300512', '荔浦', 101300513, 'g', 'l'),
(2472, '30', '广西', '3005', '桂林', '300513', '资源', 101300514, 'g', 'z'),
(2473, '30', '广西', '3006', '梧州', '300601', '梧州', 101300601, 'w', 'w'),
(2474, '30', '广西', '3006', '梧州', '300602', '藤县', 101300602, 'w', 't'),
(2475, '30', '广西', '3006', '梧州', '300603', '苍梧', 101300604, 'w', 'c'),
(2476, '30', '广西', '3006', '梧州', '300604', '蒙山', 101300605, 'w', 'm'),
(2477, '30', '广西', '3006', '梧州', '300605', '岑溪', 101300606, 'w', 'c'),
(2478, '30', '广西', '3007', '贺州', '300701', '贺州', 101300701, 'h', 'h'),
(2479, '30', '广西', '3007', '贺州', '300702', '昭平', 101300702, 'h', 'z'),
(2480, '30', '广西', '3007', '贺州', '300703', '富川', 101300703, 'h', 'f'),
(2481, '30', '广西', '3007', '贺州', '300704', '钟山', 101300704, 'h', 'z'),
(2482, '30', '广西', '3008', '贵港', '300801', '贵港', 101300801, 'g', 'g'),
(2483, '30', '广西', '3008', '贵港', '300802', '桂平', 101300802, 'g', 'g'),
(2484, '30', '广西', '3008', '贵港', '300803', '平南', 101300803, 'g', 'p'),
(2485, '30', '广西', '3009', '玉林', '300901', '玉林', 101300901, 'y', 'y'),
(2486, '30', '广西', '3009', '玉林', '300902', '博白', 101300902, 'y', 'b'),
(2487, '30', '广西', '3009', '玉林', '300903', '北流', 101300903, 'y', 'b'),
(2488, '30', '广西', '3009', '玉林', '300904', '容县', 101300904, 'y', 'r'),
(2489, '30', '广西', '3009', '玉林', '300905', '陆川', 101300905, 'y', 'l'),
(2490, '30', '广西', '3009', '玉林', '300906', '兴业', 101300906, 'y', 'x'),
(2491, '30', '广西', '3010', '百色', '301001', '百色', 101301001, 'b', 'b'),
(2492, '30', '广西', '3010', '百色', '301002', '那坡', 101301002, 'b', 'n'),
(2493, '30', '广西', '3010', '百色', '301003', '田阳', 101301003, 'b', 't'),
(2494, '30', '广西', '3010', '百色', '301004', '德保', 101301004, 'b', 'd'),
(2495, '30', '广西', '3010', '百色', '301005', '靖西', 101301005, 'b', 'j'),
(2496, '30', '广西', '3010', '百色', '301006', '田东', 101301006, 'b', 't'),
(2497, '30', '广西', '3010', '百色', '301007', '平果', 101301007, 'b', 'p'),
(2498, '30', '广西', '3010', '百色', '301008', '隆林', 101301008, 'b', 'l'),
(2499, '30', '广西', '3010', '百色', '301009', '西林', 101301009, 'b', 'x'),
(2500, '30', '广西', '3010', '百色', '301010', '乐业', 101301010, 'b', 'l'),
(2501, '30', '广西', '3010', '百色', '301011', '凌云', 101301011, 'b', 'l'),
(2502, '30', '广西', '3010', '百色', '301012', '田林', 101301012, 'b', 't'),
(2503, '30', '广西', '3011', '钦州', '301101', '钦州', 101301101, 'q', 'q'),
(2504, '30', '广西', '3011', '钦州', '301102', '浦北', 101301102, 'q', 'p'),
(2505, '30', '广西', '3011', '钦州', '301103', '灵山', 101301103, 'q', 'l'),
(2506, '30', '广西', '3012', '河池', '301201', '河池', 101301201, 'h', 'h'),
(2507, '30', '广西', '3012', '河池', '301202', '天峨', 101301202, 'h', 't'),
(2508, '30', '广西', '3012', '河池', '301203', '东兰', 101301203, 'h', 'd'),
(2509, '30', '广西', '3012', '河池', '301204', '巴马', 101301204, 'h', 'b'),
(2510, '30', '广西', '3012', '河池', '301205', '环江', 101301205, 'h', 'h'),
(2511, '30', '广西', '3012', '河池', '301206', '罗城', 101301206, 'h', 'l'),
(2512, '30', '广西', '3012', '河池', '301207', '宜州', 101301207, 'h', 'y'),
(2513, '30', '广西', '3012', '河池', '301208', '凤山', 101301208, 'h', 'f'),
(2514, '30', '广西', '3012', '河池', '301209', '南丹', 101301209, 'h', 'n'),
(2515, '30', '广西', '3012', '河池', '301210', '都安', 101301210, 'h', 'd'),
(2516, '30', '广西', '3012', '河池', '301211', '大化', 101301211, 'h', 'd'),
(2517, '30', '广西', '3013', '北海', '301301', '北海', 101301301, 'b', 'b'),
(2518, '30', '广西', '3013', '北海', '301302', '合浦', 101301302, 'b', 'h'),
(2519, '30', '广西', '3013', '北海', '301303', '涠洲岛', 101301303, 'b', 'w'),
(2520, '30', '广西', '3014', '防城港', '301401', '防城港', 101301401, 'f', 'f'),
(2521, '30', '广西', '3014', '防城港', '301402', '上思', 101301402, 'f', 's'),
(2522, '30', '广西', '3014', '防城港', '301403', '东兴', 101301403, 'f', 'd'),
(2523, '30', '广西', '3014', '防城港', '301404', '防城', 101301405, 'f', 'f'),
(2524, '31', '海南', '3101', '海口', '310101', '海口', 101310101, 'h', 'h'),
(2525, '31', '海南', '3102', '三亚', '310201', '三亚', 101310201, 's', 's'),
(2526, '31', '海南', '3103', '东方', '310301', '东方', 101310202, 'd', 'd'),
(2527, '31', '海南', '3104', '临高', '310401', '临高', 101310203, 'l', 'l'),
(2528, '31', '海南', '3105', '澄迈', '310501', '澄迈', 101310204, 'c', 'c'),
(2529, '31', '海南', '3106', '儋州', '310601', '儋州', 101310205, 'z', 'd'),
(2530, '31', '海南', '3107', '昌江', '310701', '昌江', 101310206, 'c', 'c'),
(2531, '31', '海南', '3108', '白沙', '310801', '白沙', 101310207, 'b', 'b'),
(2532, '31', '海南', '3109', '琼中', '310901', '琼中', 101310208, 'q', 'q'),
(2533, '31', '海南', '3110', '定安', '311001', '定安', 101310209, 'd', 'd'),
(2534, '31', '海南', '3111', '屯昌', '311101', '屯昌', 101310210, 't', 't'),
(2535, '31', '海南', '3112', '琼海', '311201', '琼海', 101310211, 'q', 'q'),
(2536, '31', '海南', '3113', '文昌', '311301', '文昌', 101310212, 'w', 'w'),
(2537, '31', '海南', '3114', '保亭', '311401', '保亭', 101310214, 'b', 'b'),
(2538, '31', '海南', '3115', '万宁', '311501', '万宁', 101310215, 'w', 'w'),
(2539, '31', '海南', '3116', '陵水', '311601', '陵水', 101310216, 'l', 'l'),
(2540, '31', '海南', '3117', '西沙', '311701', '西沙', 101310217, 'x', 'x'),
(2541, '31', '海南', '3118', '南沙岛', '311801', '南沙岛', 101310220, 'n', 'n'),
(2542, '31', '海南', '3119', '乐东', '311901', '乐东', 101310221, 'l', 'l'),
(2543, '31', '海南', '3120', '五指山', '312001', '五指山', 101310222, 'w', 'w'),
(2544, '32', '香港', '3201', '香港', '320101', '香港', 101320101, 'x', 'x'),
(2545, '32', '香港', '3201', '香港', '320102', '九龙', 101320102, 'x', 'j'),
(2546, '32', '香港', '3201', '香港', '320103', '新界', 101320103, 'x', 'x'),
(2547, '33', '澳门', '3301', '澳门', '330101', '澳门', 101330101, 'a', 'a'),
(2548, '33', '澳门', '3301', '澳门', '330102', '氹仔岛', 101330102, 'a', 'd'),
(2549, '33', '澳门', '3301', '澳门', '330103', '路环岛', 101330103, 'a', 'l'),
(2550, '34', '台湾', '3401', '台北', '340101', '台北', 101340101, 't', 't'),
(2551, '34', '台湾', '3401', '台北', '340102', '桃园', 101340102, 't', 't'),
(2552, '34', '台湾', '3401', '台北', '340103', '新竹', 101340103, 't', 'x'),
(2553, '34', '台湾', '3401', '台北', '340104', '宜兰', 101340104, 't', 'y'),
(2554, '34', '台湾', '3402', '高雄', '340201', '高雄', 101340201, 'g', 'g'),
(2555, '34', '台湾', '3402', '高雄', '340202', '嘉义', 101340202, 'g', 'j'),
(2556, '34', '台湾', '3402', '高雄', '340203', '台南', 101340203, 'g', 't'),
(2557, '34', '台湾', '3402', '高雄', '340204', '台东', 101340204, 'g', 't'),
(2558, '34', '台湾', '3402', '高雄', '340205', '屏东', 101340205, 'g', 'p'),
(2559, '34', '台湾', '3403', '台中', '340301', '台中', 101340401, 't', 't'),
(2560, '34', '台湾', '3403', '台中', '340302', '苗栗', 101340402, 't', 'm'),
(2561, '34', '台湾', '3403', '台中', '340303', '彰化', 101340403, 't', 'z'),
(2562, '34', '台湾', '3403', '台中', '340304', '南投', 101340404, 't', 'n'),
(2563, '34', '台湾', '3403', '台中', '340305', '花莲', 101340405, 't', 'h'),
(2564, '34', '台湾', '3403', '台中', '340306', '云林', 101340406, 't', 'y');

-- --------------------------------------------------------

--
-- Table structure for table `cmstop_wechat`
--

CREATE TABLE IF NOT EXISTS `cmstop_wechat` (
  `id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(40) NOT NULL DEFAULT '',
  `type` enum('subscribe','service') NOT NULL,
  `token` varchar(32) NOT NULL DEFAULT '',
  `appid` varchar(32) NOT NULL DEFAULT '',
  `secret` varchar(32) NOT NULL DEFAULT '',
  `state` enum('enable','disable','configured') NOT NULL,
  `exception` varchar(300) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `cmstop_wechat_material`
--

CREATE TABLE IF NOT EXISTS `cmstop_wechat_material` (
  `id` int(8) NOT NULL AUTO_INCREMENT,
  `title` varchar(80) NOT NULL,
  `content` text NOT NULL,
  `type` enum('list','picture','voice','video') NOT NULL,
  `created` int(10) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `created` (`created`,`type`),
  KEY `title` (`title`,`type`),
  KEY `type` (`type`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `cmstop_wechat_response`
--

CREATE TABLE IF NOT EXISTS `cmstop_wechat_response` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `account_id` smallint(5) unsigned NOT NULL,
  `from` varchar(32) NOT NULL DEFAULT '',
  `to` varchar(32) NOT NULL DEFAULT '',
  `content` text NOT NULL,
  `time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `type` enum('text','image','event','location','link') NOT NULL,
  `raw` text NOT NULL,
  `reply` text NOT NULL,
  `exception` varchar(500) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  KEY `time` (`time`,`type`),
  KEY `account_id` (`account_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `cmstop_wechat_route`
--

CREATE TABLE IF NOT EXISTS `cmstop_wechat_route` (
  `id` int(8) NOT NULL AUTO_INCREMENT,
  `account_id` smallint(6) unsigned NOT NULL,
  `name` varchar(60) NOT NULL,
  `tags` text NOT NULL,
  `content` mediumtext NOT NULL,
  `created` int(10) unsigned NOT NULL,
  `reply_all` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `created` (`created`),
  KEY `name` (`name`),
  KEY `account_id` (`account_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `cmstop_wechat_tag`
--

CREATE TABLE IF NOT EXISTS `cmstop_wechat_tag` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `route_id` int(8) unsigned NOT NULL,
  `name` varchar(30) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `cmstop_weibo`
--

CREATE TABLE IF NOT EXISTS `cmstop_weibo` (
  `weiboid` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `type` enum('sina_weibo','tencent_weibo') NOT NULL,
  `name` varchar(80) NOT NULL DEFAULT '',
  `openid` varchar(32) DEFAULT NULL,
  `access_token` varchar(32) NOT NULL,
  `expires` int(10) unsigned NOT NULL,
  `url` varchar(255) DEFAULT NULL,
  `state` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `sort` smallint(5) unsigned NOT NULL,
  PRIMARY KEY (`weiboid`),
  UNIQUE KEY `type` (`type`,`openid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `cmstop_widget`
--

CREATE TABLE IF NOT EXISTS `cmstop_widget` (
  `widgetid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(20) DEFAULT NULL,
  `engine` varchar(20) NOT NULL,
  `data` longtext,
  `setting` longtext,
  `skin` text,
  `status` tinyint(1) DEFAULT '0',
  `updated` int(10) unsigned DEFAULT NULL,
  `updatedby` mediumint(8) unsigned DEFAULT NULL,
  `published` int(10) unsigned DEFAULT NULL,
  `created` int(10) unsigned NOT NULL,
  `createdby` mediumint(8) unsigned NOT NULL,
  `description` varchar(255) DEFAULT NULL,
  `folder` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `thumb` varchar(200) DEFAULT NULL,
  PRIMARY KEY (`widgetid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `cmstop_widget_engine`
--

CREATE TABLE IF NOT EXISTS `cmstop_widget_engine` (
  `engineid` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(20) NOT NULL,
  `description` varchar(100) NOT NULL,
  `version` varchar(5) NOT NULL,
  `author` varchar(255) NOT NULL,
  `updateurl` varchar(255) DEFAULT NULL,
  `installed` int(10) unsigned DEFAULT NULL,
  `disabled` tinyint(1) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`engineid`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT AUTO_INCREMENT=22 ;

--
-- Dumping data for table `cmstop_widget_engine`
--

INSERT INTO `cmstop_widget_engine` (`engineid`, `name`, `description`, `version`, `author`, `updateurl`, `installed`, `disabled`) VALUES
(1, 'code', '代码', '1.0.0', 'cmstop', 'http://update.cmstop.com', NULL, 0),
(2, 'title', '标题', '1.0.0', 'cmstop', 'http://update.cmstop.com', NULL, 0),
(3, 'list', '列表', '1.0.0', 'cmstop', 'http://update.cmstop.com', NULL, 0),
(4, 'piclist', '图片列表', '1.0.0', 'cmstop', 'http://update.cmstop.com', NULL, 0),
(5, 'palist', '图文列表', '1.0.0', 'cmstop', 'http://update.cmstop.com', NULL, 0),
(6, 'slider', '幻灯片', '1.0.0', 'cmstop', 'http://update.cmstop.com', NULL, 0),
(7, 'menu', '专题菜单', '1.0.0', 'cmstop', 'http://update.cmstop.com', NULL, 0),
(8, 'flash', 'Flash', '1.0.0', 'cmstop', 'http://update.cmstop.com', NULL, 0),
(9, 'picture', '图片', '1.0.0', 'cmstop', 'http://update.cmstop.com', NULL, 0),
(10, 'video', '视频', '1.0.0', 'cmstop', 'http://update.cmstop.com', NULL, 0),
(11, 'comment', '评论', '1.0.0', 'cmstop', 'http://update.cmstop.com', NULL, 0),
(12, 'activity', '活动', '1.0.0', 'cmstop', 'http://update.cmstop.com', NULL, 0),
(13, 'survey', '调查', '1.0.0', 'cmstop', 'http://update.cmstop.com', NULL, 0),
(14, 'vote', '投票', '1.0.0', 'cmstop', 'http://update.cmstop.com', NULL, 0),
(15, 'weibo', '微博', '1.0.0', 'cmstop', 'http://update.cmstop.com', NULL, 0),
(16, 'map', '百度地图', '1.0.0', 'cmstop', 'http://update.cmstop.com', NULL, 0),
(17, 'html', 'HTML', '1.0.0', 'cmstop', 'http://update.cmstop.com', NULL, 0),
(18, 'share', '分享', '1.0.0', 'cmstop', 'http://update.cmstop.com', NULL, 0),
(19, 'weather', '天气预报', '1.0.0', 'cmstop', 'http://update.cmstop.com', NULL, 0),
(20, 'live', '视频直播', '1.0.0', 'cmstop', 'http://update.cmstop.com', NULL, 0),
(21, 'picture_group', '组图', '1.0.0', 'cmstop', 'http://update.cmstop.com', NULL, 0);

-- --------------------------------------------------------

--
-- Table structure for table `cmstop_workflow`
--

CREATE TABLE IF NOT EXISTS `cmstop_workflow` (
  `workflowid` tinyint(3) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(30) NOT NULL,
  `description` varchar(255) NOT NULL,
  `steps` tinyint(1) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`workflowid`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=4 ;

--
-- Dumping data for table `cmstop_workflow`
--

INSERT INTO `cmstop_workflow` (`workflowid`, `name`, `description`, `steps`) VALUES
(1, '一级审核', '', 1),
(2, '二级审核', '', 2),
(3, '直接发布', '', 0);

-- --------------------------------------------------------

--
-- Table structure for table `cmstop_workflow_log`
--

CREATE TABLE IF NOT EXISTS `cmstop_workflow_log` (
  `logid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `contentid` mediumint(8) unsigned NOT NULL,
  `before` tinyint(1) unsigned DEFAULT NULL,
  `after` tinyint(1) unsigned DEFAULT NULL,
  `action` enum('pass','reject') NOT NULL,
  `reason` char(255) DEFAULT NULL,
  `created` int(10) unsigned NOT NULL,
  `createdby` mediumint(8) unsigned NOT NULL,
  `ip` char(15) NOT NULL,
  PRIMARY KEY (`logid`),
  KEY `contentid` (`contentid`),
  KEY `created` (`created`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `cmstop_workflow_step`
--

CREATE TABLE IF NOT EXISTS `cmstop_workflow_step` (
  `workflowid` tinyint(3) unsigned NOT NULL,
  `step` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `roleid` tinyint(3) unsigned DEFAULT NULL,
  PRIMARY KEY (`workflowid`,`step`),
  KEY `roleid` (`roleid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `cmstop_workflow_step`
--

INSERT INTO `cmstop_workflow_step` (`workflowid`, `step`, `roleid`) VALUES
(2, 2, 3),
(1, 1, 6),
(2, 1, 6);

--
-- Constraints for dumped tables
--

--
-- Constraints for table `cmstop_aca`
--
ALTER TABLE `cmstop_aca`
  ADD CONSTRAINT `cmstop_aca_ibfk_1` FOREIGN KEY (`parentid`) REFERENCES `cmstop_aca` (`acaid`) ON DELETE CASCADE;

--
-- Constraints for table `cmstop_activity`
--
ALTER TABLE `cmstop_activity`
  ADD CONSTRAINT `cmstop_activity_ibfk_1` FOREIGN KEY (`contentid`) REFERENCES `cmstop_content` (`contentid`) ON DELETE CASCADE;

--
-- Constraints for table `cmstop_activity_sign`
--
ALTER TABLE `cmstop_activity_sign`
  ADD CONSTRAINT `cmstop_activity_sign_ibfk_1` FOREIGN KEY (`contentid`) REFERENCES `cmstop_activity` (`contentid`) ON DELETE CASCADE;

--
-- Constraints for table `cmstop_addon`
--
ALTER TABLE `cmstop_addon`
  ADD CONSTRAINT `cmstop_addon_ibfk_1` FOREIGN KEY (`contentid`) REFERENCES `cmstop_content` (`contentid`) ON DELETE SET NULL;

--
-- Constraints for table `cmstop_admin`
--
ALTER TABLE `cmstop_admin`
  ADD CONSTRAINT `cmstop_admin_ibfk_1` FOREIGN KEY (`userid`) REFERENCES `cmstop_member` (`userid`) ON DELETE CASCADE,
  ADD CONSTRAINT `cmstop_admin_ibfk_2` FOREIGN KEY (`roleid`) REFERENCES `cmstop_role` (`roleid`) ON DELETE SET NULL;

--
-- Constraints for table `cmstop_article`
--
ALTER TABLE `cmstop_article`
  ADD CONSTRAINT `cmstop_article_ibfk_1` FOREIGN KEY (`contentid`) REFERENCES `cmstop_content` (`contentid`) ON DELETE CASCADE;

--
-- Constraints for table `cmstop_attachment_folder_recent`
--
ALTER TABLE `cmstop_attachment_folder_recent`
  ADD CONSTRAINT `cmstop_attachment_folder_recent_ibfk_1` FOREIGN KEY (`fid`) REFERENCES `cmstop_attachment_folder` (`fid`) ON DELETE CASCADE;

--
-- Constraints for table `cmstop_category`
--
ALTER TABLE `cmstop_category`
  ADD CONSTRAINT `cmstop_category_ibfk_1` FOREIGN KEY (`parentid`) REFERENCES `cmstop_category` (`catid`) ON DELETE CASCADE;

--
-- Constraints for table `cmstop_category_priv`
--
ALTER TABLE `cmstop_category_priv`
  ADD CONSTRAINT `cmstop_category_priv_ibfk_1` FOREIGN KEY (`catid`) REFERENCES `cmstop_category` (`catid`) ON DELETE CASCADE,
  ADD CONSTRAINT `cmstop_category_priv_ibfk_2` FOREIGN KEY (`userid`) REFERENCES `cmstop_admin` (`userid`) ON DELETE CASCADE;

--
-- Constraints for table `cmstop_comment_report`
--
ALTER TABLE `cmstop_comment_report`
  ADD CONSTRAINT `cmstop_comment_report_ibfk_1` FOREIGN KEY (`commentid`) REFERENCES `cmstop_comment` (`commentid`) ON DELETE CASCADE;

--
-- Constraints for table `cmstop_content`
--
ALTER TABLE `cmstop_content`
  ADD CONSTRAINT `cmstop_content_ibfk_1` FOREIGN KEY (`spaceid`) REFERENCES `cmstop_space` (`spaceid`) ON DELETE SET NULL;

--
-- Constraints for table `cmstop_content_addon`
--
ALTER TABLE `cmstop_content_addon`
  ADD CONSTRAINT `cmstop_content_addon_ibfk_1` FOREIGN KEY (`contentid`) REFERENCES `cmstop_content` (`contentid`) ON DELETE CASCADE,
  ADD CONSTRAINT `cmstop_content_addon_ibfk_2` FOREIGN KEY (`addonid`) REFERENCES `cmstop_addon` (`addonid`) ON DELETE CASCADE;

--
-- Constraints for table `cmstop_content_log`
--
ALTER TABLE `cmstop_content_log`
  ADD CONSTRAINT `cmstop_content_log_ibfk_1` FOREIGN KEY (`contentid`) REFERENCES `cmstop_content` (`contentid`) ON DELETE CASCADE;

--
-- Constraints for table `cmstop_content_note`
--
ALTER TABLE `cmstop_content_note`
  ADD CONSTRAINT `cmstop_content_note_ibfk_1` FOREIGN KEY (`contentid`) REFERENCES `cmstop_content` (`contentid`) ON DELETE CASCADE;

--
-- Constraints for table `cmstop_content_property`
--
ALTER TABLE `cmstop_content_property`
  ADD CONSTRAINT `cmstop_content_property_ibfk_1` FOREIGN KEY (`contentid`) REFERENCES `cmstop_content` (`contentid`) ON DELETE CASCADE,
  ADD CONSTRAINT `cmstop_content_property_ibfk_2` FOREIGN KEY (`proid`) REFERENCES `cmstop_property` (`proid`) ON DELETE CASCADE;

--
-- Constraints for table `cmstop_content_tag`
--
ALTER TABLE `cmstop_content_tag`
  ADD CONSTRAINT `cmstop_content_tag_ibfk_3` FOREIGN KEY (`contentid`) REFERENCES `cmstop_content` (`contentid`) ON DELETE CASCADE,
  ADD CONSTRAINT `cmstop_content_tag_ibfk_4` FOREIGN KEY (`tagid`) REFERENCES `cmstop_tag` (`tagid`) ON DELETE CASCADE;

--
-- Constraints for table `cmstop_content_version`
--
ALTER TABLE `cmstop_content_version`
  ADD CONSTRAINT `cmstop_content_version_ibfk_1` FOREIGN KEY (`contentid`) REFERENCES `cmstop_content` (`contentid`) ON DELETE CASCADE;

--
-- Constraints for table `cmstop_contribution`
--
ALTER TABLE `cmstop_contribution`
  ADD CONSTRAINT `cmstop_contribution_ibfk_1` FOREIGN KEY (`contentid`) REFERENCES `cmstop_content` (`contentid`) ON DELETE SET NULL;

--
-- Constraints for table `cmstop_contribution_log`
--
ALTER TABLE `cmstop_contribution_log`
  ADD CONSTRAINT `cmstop_contribution_log_ibfk_1` FOREIGN KEY (`contributionid`) REFERENCES `cmstop_contribution` (`contributionid`) ON DELETE CASCADE;

--
-- Constraints for table `cmstop_cron_log`
--
ALTER TABLE `cmstop_cron_log`
  ADD CONSTRAINT `cmstop_cron_log_ibfk_1` FOREIGN KEY (`cronid`) REFERENCES `cmstop_cron` (`cronid`) ON DELETE CASCADE;

--
-- Constraints for table `cmstop_department`
--
ALTER TABLE `cmstop_department`
  ADD CONSTRAINT `cmstop_department_ibfk_2` FOREIGN KEY (`leaderid`) REFERENCES `cmstop_role` (`roleid`) ON DELETE SET NULL;

--
-- Constraints for table `cmstop_department_role`
--
ALTER TABLE `cmstop_department_role`
  ADD CONSTRAINT `cmstop_department_role_ibfk_1` FOREIGN KEY (`departmentid`) REFERENCES `cmstop_department` (`departmentid`) ON DELETE CASCADE,
  ADD CONSTRAINT `cmstop_department_role_ibfk_2` FOREIGN KEY (`roleid`) REFERENCES `cmstop_role` (`roleid`) ON DELETE CASCADE;

--
-- Constraints for table `cmstop_digg`
--
ALTER TABLE `cmstop_digg`
  ADD CONSTRAINT `cmstop_digg_ibfk_1` FOREIGN KEY (`contentid`) REFERENCES `cmstop_content` (`contentid`) ON DELETE CASCADE;

--
-- Constraints for table `cmstop_digg_log`
--
ALTER TABLE `cmstop_digg_log`
  ADD CONSTRAINT `cmstop_digg_log_ibfk_1` FOREIGN KEY (`contentid`) REFERENCES `cmstop_content` (`contentid`) ON DELETE CASCADE;

--
-- Constraints for table `cmstop_freelist`
--
ALTER TABLE `cmstop_freelist`
  ADD CONSTRAINT `cmstop_freelist_ibfk_1` FOREIGN KEY (`gid`) REFERENCES `cmstop_freelist_group` (`gid`) ON DELETE SET NULL;

--
-- Constraints for table `cmstop_interview`
--
ALTER TABLE `cmstop_interview`
  ADD CONSTRAINT `cmstop_interview_ibfk_1` FOREIGN KEY (`contentid`) REFERENCES `cmstop_content` (`contentid`) ON DELETE CASCADE;

--
-- Constraints for table `cmstop_interview_chat`
--
ALTER TABLE `cmstop_interview_chat`
  ADD CONSTRAINT `cmstop_interview_chat_ibfk_1` FOREIGN KEY (`contentid`) REFERENCES `cmstop_interview` (`contentid`) ON DELETE CASCADE,
  ADD CONSTRAINT `cmstop_interview_chat_ibfk_2` FOREIGN KEY (`guestid`) REFERENCES `cmstop_interview_guest` (`guestid`) ON DELETE CASCADE;

--
-- Constraints for table `cmstop_interview_guest`
--
ALTER TABLE `cmstop_interview_guest`
  ADD CONSTRAINT `cmstop_interview_guest_ibfk_1` FOREIGN KEY (`contentid`) REFERENCES `cmstop_interview` (`contentid`) ON DELETE CASCADE;

--
-- Constraints for table `cmstop_interview_question`
--
ALTER TABLE `cmstop_interview_question`
  ADD CONSTRAINT `cmstop_interview_question_ibfk_1` FOREIGN KEY (`contentid`) REFERENCES `cmstop_interview` (`contentid`) ON DELETE CASCADE;

--
-- Constraints for table `cmstop_link`
--
ALTER TABLE `cmstop_link`
  ADD CONSTRAINT `cmstop_link_ibfk_1` FOREIGN KEY (`contentid`) REFERENCES `cmstop_content` (`contentid`) ON DELETE CASCADE,
  ADD CONSTRAINT `cmstop_link_ibfk_2` FOREIGN KEY (`referenceid`) REFERENCES `cmstop_content` (`contentid`) ON DELETE SET NULL;

--
-- Constraints for table `cmstop_magazine_content`
--
ALTER TABLE `cmstop_magazine_content`
  ADD CONSTRAINT `cmstop_magazine_content_ibfk_1` FOREIGN KEY (`pid`) REFERENCES `cmstop_magazine_page` (`pid`) ON DELETE CASCADE,
  ADD CONSTRAINT `cmstop_magazine_content_ibfk_2` FOREIGN KEY (`contentid`) REFERENCES `cmstop_content` (`contentid`) ON DELETE CASCADE;

--
-- Constraints for table `cmstop_magazine_edition`
--
ALTER TABLE `cmstop_magazine_edition`
  ADD CONSTRAINT `mid` FOREIGN KEY (`mid`) REFERENCES `cmstop_magazine` (`mid`) ON DELETE CASCADE;

--
-- Constraints for table `cmstop_magazine_page`
--
ALTER TABLE `cmstop_magazine_page`
  ADD CONSTRAINT `cmstop_magazine_page_ibfk_1` FOREIGN KEY (`eid`) REFERENCES `cmstop_magazine_edition` (`eid`) ON DELETE CASCADE;

--
-- Constraints for table `cmstop_member_bind`
--
ALTER TABLE `cmstop_member_bind`
  ADD CONSTRAINT `cmstop_member_bind_ibfk_1` FOREIGN KEY (`userid`) REFERENCES `cmstop_member` (`userid`) ON DELETE CASCADE;

--
-- Constraints for table `cmstop_member_detail`
--
ALTER TABLE `cmstop_member_detail`
  ADD CONSTRAINT `cmstop_member_detail_ibfk_1` FOREIGN KEY (`userid`) REFERENCES `cmstop_member` (`userid`) ON DELETE CASCADE;

--
-- Constraints for table `cmstop_mobile_activity`
--
ALTER TABLE `cmstop_mobile_activity`
  ADD CONSTRAINT `cmstop_mobile_activity_ibfk_1` FOREIGN KEY (`contentid`) REFERENCES `cmstop_mobile_content` (`contentid`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `cmstop_mobile_article`
--
ALTER TABLE `cmstop_mobile_article`
  ADD CONSTRAINT `cmstop_mobile_article_ibfk_4` FOREIGN KEY (`contentid`) REFERENCES `cmstop_mobile_content` (`contentid`) ON DELETE CASCADE ON UPDATE NO ACTION;

--
-- Constraints for table `cmstop_mobile_autofill`
--
ALTER TABLE `cmstop_mobile_autofill`
  ADD CONSTRAINT `cmstop_mobile_autofill_ibfk_1` FOREIGN KEY (`catid`) REFERENCES `cmstop_mobile_category` (`catid`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `cmstop_mobile_autofill_log`
--
ALTER TABLE `cmstop_mobile_autofill_log`
  ADD CONSTRAINT `cmstop_mobile_autofill_log_ibfk_1` FOREIGN KEY (`catid`) REFERENCES `cmstop_mobile_category` (`catid`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `cmstop_mobile_category_bind`
--
ALTER TABLE `cmstop_mobile_category_bind`
  ADD CONSTRAINT `cmstop_mobile_category_bind_ibfk_10` FOREIGN KEY (`catid`) REFERENCES `cmstop_category` (`catid`) ON DELETE CASCADE ON UPDATE NO ACTION,
  ADD CONSTRAINT `cmstop_mobile_category_bind_ibfk_9` FOREIGN KEY (`mobile_catid`) REFERENCES `cmstop_mobile_category` (`catid`) ON DELETE CASCADE ON UPDATE NO ACTION;

--
-- Constraints for table `cmstop_mobile_category_priv`
--
ALTER TABLE `cmstop_mobile_category_priv`
  ADD CONSTRAINT `cmstop_mobile_category_priv_ibfk_7` FOREIGN KEY (`catid`) REFERENCES `cmstop_mobile_category` (`catid`) ON DELETE CASCADE ON UPDATE NO ACTION,
  ADD CONSTRAINT `cmstop_mobile_category_priv_ibfk_8` FOREIGN KEY (`userid`) REFERENCES `cmstop_member` (`userid`) ON DELETE CASCADE ON UPDATE NO ACTION;

--
-- Constraints for table `cmstop_mobile_content`
--
ALTER TABLE `cmstop_mobile_content`
  ADD CONSTRAINT `cmstop_mobile_content_ibfk_4` FOREIGN KEY (`topicid`) REFERENCES `cmstop_comment_topic` (`topicid`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `cmstop_mobile_content_addon`
--
ALTER TABLE `cmstop_mobile_content_addon`
  ADD CONSTRAINT `cmstop_mobile_content_addon_ibfk_1` FOREIGN KEY (`contentid`) REFERENCES `cmstop_mobile_content` (`contentid`) ON DELETE CASCADE ON UPDATE NO ACTION,
  ADD CONSTRAINT `cmstop_mobile_content_addon_ibfk_2` FOREIGN KEY (`addonid`) REFERENCES `cmstop_mobile_addon` (`addonid`) ON DELETE CASCADE ON UPDATE NO ACTION;

--
-- Constraints for table `cmstop_mobile_content_category`
--
ALTER TABLE `cmstop_mobile_content_category`
  ADD CONSTRAINT `cmstop_mobile_content_category_ibfk_1` FOREIGN KEY (`contentid`) REFERENCES `cmstop_mobile_content` (`contentid`) ON DELETE CASCADE ON UPDATE NO ACTION,
  ADD CONSTRAINT `cmstop_mobile_content_category_ibfk_2` FOREIGN KEY (`catid`) REFERENCES `cmstop_mobile_category` (`catid`) ON DELETE CASCADE ON UPDATE NO ACTION;

--
-- Constraints for table `cmstop_mobile_content_log`
--
ALTER TABLE `cmstop_mobile_content_log`
  ADD CONSTRAINT `cmstop_mobile_content_log_ibfk_5` FOREIGN KEY (`contentid`) REFERENCES `cmstop_mobile_content` (`contentid`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `cmstop_mobile_content_log_ibfk_6` FOREIGN KEY (`createdby`) REFERENCES `cmstop_member` (`userid`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `cmstop_mobile_content_related`
--
ALTER TABLE `cmstop_mobile_content_related`
  ADD CONSTRAINT `cmstop_mobile_content_related_ibfk_3` FOREIGN KEY (`contentid`) REFERENCES `cmstop_mobile_content` (`contentid`) ON DELETE CASCADE ON UPDATE NO ACTION,
  ADD CONSTRAINT `cmstop_mobile_content_related_ibfk_4` FOREIGN KEY (`related_contentid`) REFERENCES `cmstop_mobile_content` (`contentid`) ON DELETE CASCADE ON UPDATE NO ACTION;

--
-- Constraints for table `cmstop_mobile_content_stat`
--
ALTER TABLE `cmstop_mobile_content_stat`
  ADD CONSTRAINT `cmstop_mobile_content_stat_ibfk_1` FOREIGN KEY (`contentid`) REFERENCES `cmstop_mobile_content` (`contentid`) ON DELETE CASCADE ON UPDATE NO ACTION;

--
-- Constraints for table `cmstop_mobile_content_stat_day`
--
ALTER TABLE `cmstop_mobile_content_stat_day`
  ADD CONSTRAINT `cmstop_mobile_content_stat_day_ibfk_2` FOREIGN KEY (`contentid`) REFERENCES `cmstop_mobile_content` (`contentid`) ON DELETE CASCADE ON UPDATE NO ACTION;

--
-- Constraints for table `cmstop_mobile_link`
--
ALTER TABLE `cmstop_mobile_link`
  ADD CONSTRAINT `cmstop_mobile_link_ibfk_1` FOREIGN KEY (`contentid`) REFERENCES `cmstop_mobile_content` (`contentid`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `cmstop_mobile_slider`
--
ALTER TABLE `cmstop_mobile_slider`
  ADD CONSTRAINT `cmstop_mobile_slider_ibfk_3` FOREIGN KEY (`contentid`) REFERENCES `cmstop_mobile_content` (`contentid`) ON DELETE CASCADE ON UPDATE NO ACTION,
  ADD CONSTRAINT `cmstop_mobile_slider_ibfk_4` FOREIGN KEY (`catid`) REFERENCES `cmstop_mobile_category` (`catid`) ON DELETE CASCADE ON UPDATE NO ACTION;

--
-- Constraints for table `cmstop_mobile_special`
--
ALTER TABLE `cmstop_mobile_special`
  ADD CONSTRAINT `cmstop_mobile_special_ibfk_4` FOREIGN KEY (`contentid`) REFERENCES `cmstop_mobile_content` (`contentid`) ON DELETE CASCADE ON UPDATE NO ACTION;

--
-- Constraints for table `cmstop_mobile_special_category`
--
ALTER TABLE `cmstop_mobile_special_category`
  ADD CONSTRAINT `cmstop_mobile_special_category_ibfk_3` FOREIGN KEY (`catid`) REFERENCES `cmstop_mobile_category` (`catid`) ON DELETE CASCADE ON UPDATE NO ACTION,
  ADD CONSTRAINT `cmstop_mobile_special_category_ibfk_4` FOREIGN KEY (`contentid`) REFERENCES `cmstop_mobile_content` (`contentid`) ON DELETE CASCADE ON UPDATE NO ACTION;

--
-- Constraints for table `cmstop_mobile_special_content`
--
ALTER TABLE `cmstop_mobile_special_content`
  ADD CONSTRAINT `cmstop_mobile_special_content_ibfk_4` FOREIGN KEY (`specialid`) REFERENCES `cmstop_mobile_content` (`contentid`) ON DELETE CASCADE ON UPDATE NO ACTION,
  ADD CONSTRAINT `cmstop_mobile_special_content_ibfk_6` FOREIGN KEY (`contentid`) REFERENCES `cmstop_mobile_content` (`contentid`) ON DELETE CASCADE ON UPDATE NO ACTION,
  ADD CONSTRAINT `cmstop_mobile_special_content_ibfk_7` FOREIGN KEY (`catid`) REFERENCES `cmstop_mobile_special_category` (`catid`) ON DELETE CASCADE ON UPDATE NO ACTION;

--
-- Constraints for table `cmstop_mobile_video`
--
ALTER TABLE `cmstop_mobile_video`
  ADD CONSTRAINT `cmstop_mobile_video_ibfk_3` FOREIGN KEY (`contentid`) REFERENCES `cmstop_mobile_content` (`contentid`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `cmstop_mood_data`
--
ALTER TABLE `cmstop_mood_data`
  ADD CONSTRAINT `cmstop_mood_data_ibfk_1` FOREIGN KEY (`contentid`) REFERENCES `cmstop_content` (`contentid`) ON DELETE CASCADE;

--
-- Constraints for table `cmstop_page`
--
ALTER TABLE `cmstop_page`
  ADD CONSTRAINT `cmstop_page_ibfk_1` FOREIGN KEY (`parentid`) REFERENCES `cmstop_page` (`pageid`) ON DELETE CASCADE;

--
-- Constraints for table `cmstop_page_priv`
--
ALTER TABLE `cmstop_page_priv`
  ADD CONSTRAINT `cmstop_page_priv_ibfk_1` FOREIGN KEY (`pageid`) REFERENCES `cmstop_page` (`pageid`) ON DELETE CASCADE,
  ADD CONSTRAINT `cmstop_page_priv_ibfk_2` FOREIGN KEY (`userid`) REFERENCES `cmstop_admin` (`userid`) ON DELETE CASCADE;

--
-- Constraints for table `cmstop_page_stat`
--
ALTER TABLE `cmstop_page_stat`
  ADD CONSTRAINT `cmstop_page_stat_ibfk_1` FOREIGN KEY (`pageid`) REFERENCES `cmstop_page` (`pageid`) ON DELETE CASCADE;

--
-- Constraints for table `cmstop_paper_content`
--
ALTER TABLE `cmstop_paper_content`
  ADD CONSTRAINT `cmstop_paper_content_ibfk_1` FOREIGN KEY (`pageid`) REFERENCES `cmstop_paper_edition_page` (`pageid`) ON DELETE CASCADE;

--
-- Constraints for table `cmstop_paper_edition`
--
ALTER TABLE `cmstop_paper_edition`
  ADD CONSTRAINT `cmstop_paper_edition_ibfk_1` FOREIGN KEY (`paperid`) REFERENCES `cmstop_paper` (`paperid`) ON DELETE CASCADE;

--
-- Constraints for table `cmstop_paper_edition_page`
--
ALTER TABLE `cmstop_paper_edition_page`
  ADD CONSTRAINT `cmstop_paper_edition_page_ibfk_1` FOREIGN KEY (`editionid`) REFERENCES `cmstop_paper_edition` (`editionid`) ON DELETE CASCADE;

--
-- Constraints for table `cmstop_picture`
--
ALTER TABLE `cmstop_picture`
  ADD CONSTRAINT `cmstop_picture_ibfk_1` FOREIGN KEY (`contentid`) REFERENCES `cmstop_content` (`contentid`) ON DELETE CASCADE;

--
-- Constraints for table `cmstop_picture_group`
--
ALTER TABLE `cmstop_picture_group`
  ADD CONSTRAINT `cmstop_picture_group_ibfk_1` FOREIGN KEY (`contentid`) REFERENCES `cmstop_picture` (`contentid`) ON DELETE CASCADE;

--
-- Constraints for table `cmstop_place`
--
ALTER TABLE `cmstop_place`
  ADD CONSTRAINT `cmstop_place_ibfk_1` FOREIGN KEY (`pageid`) REFERENCES `cmstop_special_page` (`pageid`) ON DELETE SET NULL,
  ADD CONSTRAINT `cmstop_place_ibfk_2` FOREIGN KEY (`placeid`) REFERENCES `cmstop_widget` (`widgetid`) ON DELETE CASCADE;

--
-- Constraints for table `cmstop_place_data`
--
ALTER TABLE `cmstop_place_data`
  ADD CONSTRAINT `cmstop_place_data_ibfk_2` FOREIGN KEY (`contentid`) REFERENCES `cmstop_content` (`contentid`) ON DELETE CASCADE,
  ADD CONSTRAINT `cmstop_place_data_ibfk_3` FOREIGN KEY (`placeid`) REFERENCES `cmstop_place` (`placeid`) ON DELETE CASCADE;

--
-- Constraints for table `cmstop_push`
--
ALTER TABLE `cmstop_push`
  ADD CONSTRAINT `cmstop_push_ibfk_1` FOREIGN KEY (`taskid`) REFERENCES `cmstop_push_task` (`taskid`) ON DELETE CASCADE;

--
-- Constraints for table `cmstop_qrcode_stat`
--
ALTER TABLE `cmstop_qrcode_stat`
  ADD CONSTRAINT `cmstop_qrcode_stat_ibfk_1` FOREIGN KEY (`qrcodeid`) REFERENCES `cmstop_qrcode` (`qrcodeid`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `cmstop_related`
--
ALTER TABLE `cmstop_related`
  ADD CONSTRAINT `cmstop_related_ibfk_1` FOREIGN KEY (`contentid`) REFERENCES `cmstop_content` (`contentid`) ON DELETE CASCADE;

--
-- Constraints for table `cmstop_role_aca`
--
ALTER TABLE `cmstop_role_aca`
  ADD CONSTRAINT `cmstop_role_aca_ibfk_1` FOREIGN KEY (`acaid`) REFERENCES `cmstop_aca` (`acaid`) ON DELETE CASCADE,
  ADD CONSTRAINT `cmstop_role_aca_ibfk_2` FOREIGN KEY (`roleid`) REFERENCES `cmstop_role` (`roleid`) ON DELETE CASCADE;

--
-- Constraints for table `cmstop_search`
--
ALTER TABLE `cmstop_search`
  ADD CONSTRAINT `cmstop_search_ibfk_1` FOREIGN KEY (`contentid`) REFERENCES `cmstop_content` (`contentid`) ON DELETE CASCADE;

--
-- Constraints for table `cmstop_section_history`
--
ALTER TABLE `cmstop_section_history`
  ADD CONSTRAINT `cmstop_section_history_ibfk_1` FOREIGN KEY (`sectionid`) REFERENCES `cmstop_section` (`sectionid`) ON DELETE CASCADE;

--
-- Constraints for table `cmstop_section_log`
--
ALTER TABLE `cmstop_section_log`
  ADD CONSTRAINT `cmstop_section_log_ibfk_1` FOREIGN KEY (`sectionid`) REFERENCES `cmstop_section` (`sectionid`) ON DELETE CASCADE;

--
-- Constraints for table `cmstop_section_priv`
--
ALTER TABLE `cmstop_section_priv`
  ADD CONSTRAINT `cmstop_section_priv_ibfk_1` FOREIGN KEY (`sectionid`) REFERENCES `cmstop_section` (`sectionid`) ON DELETE CASCADE,
  ADD CONSTRAINT `cmstop_section_priv_ibfk_2` FOREIGN KEY (`userid`) REFERENCES `cmstop_admin` (`userid`) ON DELETE CASCADE;

--
-- Constraints for table `cmstop_section_recommend`
--
ALTER TABLE `cmstop_section_recommend`
  ADD CONSTRAINT `cmstop_section_recommend_ibfk_1` FOREIGN KEY (`contentid`) REFERENCES `cmstop_content` (`contentid`) ON DELETE CASCADE,
  ADD CONSTRAINT `cmstop_section_recommend_ibfk_2` FOREIGN KEY (`sectionid`) REFERENCES `cmstop_section` (`sectionid`) ON DELETE CASCADE;

--
-- Constraints for table `cmstop_space`
--
ALTER TABLE `cmstop_space`
  ADD CONSTRAINT `cmstop_space_ibfk_1` FOREIGN KEY (`userid`) REFERENCES `cmstop_member` (`userid`) ON DELETE CASCADE;

--
-- Constraints for table `cmstop_special`
--
ALTER TABLE `cmstop_special`
  ADD CONSTRAINT `cmstop_special_ibfk_1` FOREIGN KEY (`contentid`) REFERENCES `cmstop_content` (`contentid`) ON DELETE CASCADE;

--
-- Constraints for table `cmstop_special_page`
--
ALTER TABLE `cmstop_special_page`
  ADD CONSTRAINT `cmstop_special_page_ibfk_1` FOREIGN KEY (`contentid`) REFERENCES `cmstop_content` (`contentid`) ON DELETE CASCADE;

--
-- Constraints for table `cmstop_special_scheme`
--
ALTER TABLE `cmstop_special_scheme`
  ADD CONSTRAINT `cmstop_special_scheme_ibfk_1` FOREIGN KEY (`typeid`) REFERENCES `cmstop_special_scheme_type` (`typeid`) ON DELETE SET NULL;

--
-- Constraints for table `cmstop_spider_task`
--
ALTER TABLE `cmstop_spider_task`
  ADD CONSTRAINT `cmstop_spider_task_ibfk_1` FOREIGN KEY (`ruleid`) REFERENCES `cmstop_spider_rules` (`ruleid`) ON DELETE CASCADE;

--
-- Constraints for table `cmstop_survey`
--
ALTER TABLE `cmstop_survey`
  ADD CONSTRAINT `cmstop_survey_ibfk_1` FOREIGN KEY (`contentid`) REFERENCES `cmstop_content` (`contentid`) ON DELETE CASCADE;

--
-- Constraints for table `cmstop_survey_answer`
--
ALTER TABLE `cmstop_survey_answer`
  ADD CONSTRAINT `cmstop_survey_answer_ibfk_1` FOREIGN KEY (`contentid`) REFERENCES `cmstop_survey` (`contentid`) ON DELETE CASCADE;

--
-- Constraints for table `cmstop_survey_answer_option`
--
ALTER TABLE `cmstop_survey_answer_option`
  ADD CONSTRAINT `cmstop_survey_answer_option_ibfk_1` FOREIGN KEY (`answerid`) REFERENCES `cmstop_survey_answer` (`answerid`) ON DELETE CASCADE,
  ADD CONSTRAINT `cmstop_survey_answer_option_ibfk_2` FOREIGN KEY (`questionid`) REFERENCES `cmstop_survey_question` (`questionid`) ON DELETE CASCADE,
  ADD CONSTRAINT `cmstop_survey_answer_option_ibfk_3` FOREIGN KEY (`optionid`) REFERENCES `cmstop_survey_question_option` (`optionid`) ON DELETE CASCADE;

--
-- Constraints for table `cmstop_survey_answer_record`
--
ALTER TABLE `cmstop_survey_answer_record`
  ADD CONSTRAINT `cmstop_survey_answer_record_ibfk_1` FOREIGN KEY (`answerid`) REFERENCES `cmstop_survey_answer` (`answerid`) ON DELETE CASCADE,
  ADD CONSTRAINT `cmstop_survey_answer_record_ibfk_2` FOREIGN KEY (`questionid`) REFERENCES `cmstop_survey_question` (`questionid`) ON DELETE CASCADE;

--
-- Constraints for table `cmstop_survey_question`
--
ALTER TABLE `cmstop_survey_question`
  ADD CONSTRAINT `cmstop_survey_question_ibfk_1` FOREIGN KEY (`contentid`) REFERENCES `cmstop_survey` (`contentid`) ON DELETE CASCADE;

--
-- Constraints for table `cmstop_survey_question_option`
--
ALTER TABLE `cmstop_survey_question_option`
  ADD CONSTRAINT `cmstop_survey_question_option_ibfk_1` FOREIGN KEY (`questionid`) REFERENCES `cmstop_survey_question` (`questionid`) ON DELETE CASCADE;

--
-- Constraints for table `cmstop_video`
--
ALTER TABLE `cmstop_video`
  ADD CONSTRAINT `cmstop_video_ibfk_1` FOREIGN KEY (`contentid`) REFERENCES `cmstop_content` (`contentid`) ON DELETE CASCADE;

--
-- Constraints for table `cmstop_vote`
--
ALTER TABLE `cmstop_vote`
  ADD CONSTRAINT `cmstop_vote_ibfk_1` FOREIGN KEY (`contentid`) REFERENCES `cmstop_content` (`contentid`) ON DELETE CASCADE;

--
-- Constraints for table `cmstop_vote_log`
--
ALTER TABLE `cmstop_vote_log`
  ADD CONSTRAINT `cmstop_vote_log_ibfk_1` FOREIGN KEY (`contentid`) REFERENCES `cmstop_vote` (`contentid`) ON DELETE CASCADE;

--
-- Constraints for table `cmstop_vote_log_data`
--
ALTER TABLE `cmstop_vote_log_data`
  ADD CONSTRAINT `cmstop_vote_log_data_ibfk_1` FOREIGN KEY (`logid`) REFERENCES `cmstop_vote_log` (`logid`) ON DELETE CASCADE;

--
-- Constraints for table `cmstop_vote_option`
--
ALTER TABLE `cmstop_vote_option`
  ADD CONSTRAINT `cmstop_vote_option_ibfk_1` FOREIGN KEY (`contentid`) REFERENCES `cmstop_vote` (`contentid`) ON DELETE CASCADE;

--
-- Constraints for table `cmstop_workflow_step`
--
ALTER TABLE `cmstop_workflow_step`
  ADD CONSTRAINT `cmstop_workflow_step_ibfk_1` FOREIGN KEY (`workflowid`) REFERENCES `cmstop_workflow` (`workflowid`) ON DELETE CASCADE;

set global log_bin_trust_function_creators=TRUE;

DELIMITER $$
DROP FUNCTION IF EXISTS `IS_MOBILE`$$
CREATE FUNCTION `IS_MOBILE`(x VARCHAR(255), hasCloud TINYINT(1)) RETURNS tinyint(1)
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
     ELSEIF LEFT(x, 7) = '[youku]' AND hasCloud THEN
        SET result = 1;
     ELSEIF RIGHT(x,4) = '.mp4' THEN
          SET result = 1;
     END IF;
     RETURN result;
END$$

DELIMITER ;


set global log_bin_trust_function_creators=FALSE;