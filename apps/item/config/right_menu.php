	<?php
/*
 * 模型的右键菜单定义,通常复制item模型的配置即可，详细说明及特殊菜单项的设置参考interview
 */

return array(
	array('class' => 'view', 'text' => '查看'),
	array('class' => 'edit', 'text' => '编辑'),
	array('class' => 'remove', 'text' => '删除', 'status' => '!0'),
	array('class' => 'createhtml', 'aca' => 'item/html/show', 'text' => '生成', 'status' => '6'),
);