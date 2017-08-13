<?php
/*
 * 模型的右键菜单定义,通常复制article模型的配置即可，详细说明及特殊菜单项的设置参考interview
 */
return array(
	array('class' => 'edit', 'text' => '编辑', 'status' => '!0'),

	array('class' => 'remove', 'text' => '删除', 'status' => '!0'),
	array('class' => 'del', 'text' => '删除', 'status' => '0'),
	array('class' => 'publish', 'text' => '发布', 'status' => '1'),
	array('class' => 'unpublish', 'text' => '下线', 'status' => '6'),
	array('class' => 'restore', 'text' => '还原', 'status' => '0'),

	array('class' => 'approve', 'text' => '送审', 'status' => '1,2'),
	array('class' => 'pass', 'text' => '通过', 'status' => '2'),
	array('class' => 'reject', 'text' => '退稿', 'status' => '2,3'),

    array('class' => 'pushToPage', 'aca' => 'page/section/recommend', 'text' => '推送到页面','status' => '6'),
    array('class' => 'pushToPlace', 'aca' => 'special/special/recommend', 'text' => '推送到专题','status' => '6'),

	array('class' => 'move', 'text' => '移动','separator' => 1, 'status' => '!0'),

    array('class' => 'keyword', 'aca' => 'system/keylink/content_index', 'text' => '关键词链接','separator' => 1),
    array('class' => 'qrcode', 'aca' => 'system/qrcode/index', 'text' => '生成二维码', 'status' => '6'),
    array('class' => 'score', 'aca' => 'system/score/index', 'text' => '评分'),
    array('class' => 'note', 'aca' => 'system/content_note/index', 'text' => '批注'),
    array('class' => 'version', 'aca' => 'system/content_version/index', 'text' => '版本'),
    array('class' => 'log', 'aca' => 'system/content_log/index', 'text' => '日志'),
);
